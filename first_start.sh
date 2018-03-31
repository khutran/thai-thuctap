dir=$(pwd)
echo "$dir";

## Folder
chmod -R 775 $dir/www/
echo "change permission success $dir/www/"

chmod -R 777 $dir/www/hrautomail.vicoders.com/app/*
chmod -R 777 $dir/www/hrautomail.vicoders.com/media/*
echo "change permission success $dir/www/hrautomail.vicoders.com"

chmod -R 777 $dir/www/viwebsite.vicoders.com/app/*
chmod -R 777 $dir/www/viwebsite.vicoders.com/media/*
echo "change permission success $dir/www/viwebsite.vicoders.com"