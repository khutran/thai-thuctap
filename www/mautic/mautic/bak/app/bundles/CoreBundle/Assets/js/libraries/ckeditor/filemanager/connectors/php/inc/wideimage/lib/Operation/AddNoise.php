<?php
    /**
     * @author Tomasz Kapusta
     * @copyright 2010

     **/

    /**
     * Noise filter.
     */
    class WideImage_Operation_AddNoise
    {
        /**
         * Returns image with noise added.
         *
         * @param WideImage_Image $image
         * @param float           $amount
         * @param const           $type
         * @param float           $threshold
         *
         * @return WideImage_Image
         */
        public function execute($image, $amount, $type)
        {
            switch ($type) {
                case 'salt&pepper':    $fun = 'saltPepperNoise_fun';
                                        break;
                case 'color':    $fun = 'colorNoise_fun';
                                        break;
                default:    $fun = 'monoNoise_fun';
                                        break;
            }

            return self::filter($image->asTrueColor(), $fun, $amount);
        }

        /**
         * Returns image with every pixel changed by specififed function.
         *
         * @param WideImage_Image $image
         * @param string          $function
         * @param int             $value
         *
         * @return WideImage_Image
         */
        public function filter($image, $function, $value)
        {
            for ($y = 0; $y < $image->getHeight(); ++$y) {
                for ($x = 0; $x < $image->getWidth(); ++$x) {
                    $rgb = imagecolorat($image->getHandle(), $x, $y);

                    $a = ($rgb >> 24) & 0xFF;
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;

                    self::$function($r, $g, $b, $value);

                    $color = imagecolorallocatealpha($image->getHandle(), $r, $g, $b, $a);
                    imagesetpixel($image->getHandle(), $x, $y, $color);
                }
            }

            return $image;
        }

        /**
         * Adds color noise by altering given R,G,B values using specififed amount.
         *
         * @param int $r
         * @param int $g
         * @param int $b
         * @param int $value
         */
        public function colorNoise_fun(&$r, &$g, &$b, $amount)
        {
            $r = self::byte($r + mt_rand(0, $amount) - ($amount >> 1));
            $g = self::byte($g + mt_rand(0, $amount) - ($amount >> 1));
            $b = self::byte($b + mt_rand(0, $amount) - ($amount >> 1));
        }

        /**
         * Adds mono noise by altering given R,G,B values using specififed amount.
         *
         * @param int $r
         * @param int $g
         * @param int $b
         * @param int $value
         */
        public function monoNoise_fun(&$r, &$g, &$b, $amount)
        {
            $rand = mt_rand(0, $amount) - ($amount >> 1);

            $r = self::byte($r + $rand);
            $g = self::byte($g + $rand);
            $b = self::byte($b + $rand);
        }

        /**
         * Adds salt&pepper noise by altering given R,G,B values using specififed amount.
         *
         * @param int $r
         * @param int $g
         * @param int $b
         * @param int $value
         */
        public function saltPepperNoise_fun(&$r, &$g, &$b, $amount)
        {
            if (mt_rand(0, 255 - $amount) != 0) {
                return;
            }

            $rand = mt_rand(0, 1);
            switch ($rand) {
                case 0:    $r = $g = $b = 0;
                            break;
                case 1:    $r = $g = $b = 255;
                            break;
            }
        }

        /**
         * Returns value within (0,255).
         *
         * @param int $b
         *
         * @return int
         */
        public function byte($b)
        {
            if ($b > 255) {
                return 255;
            }
            if ($b < 0) {
                return 0;
            }

            return (int) $b;
        }
    }
