<?php
/*
Plugin Name: Sample Data Installer
Plugin URI: http://themes.zone
Description: Sample Data Installer for StoreX Store Theme
Author: Themes Zone
Author URI: http://themes.zone
Version: 1.0.0
License: GNU General Public License v3.0
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

global $wpdb;
	$our_site_url = get_site_url();	
	$our_site_url = str_replace('http://', '', $our_site_url);
	replace_file(dirname(__FILE__).'/demo.sql', 'storex.themes.zone', $our_site_url);
    replace_file(dirname(__FILE__).'/demo.sql', 'wp_', $wpdb->prefix);


	$query = "";
	$sql = file(dirname(__FILE__).'/demo.sql');
	foreach ($sql as $key=>$line) {
  		$line = trim($line);
		if ($line != "" && substr($line, 0, 2) != '--')
		{
			$query .= $line;
			if (substr($line, -1) == ';') 
			{
				
				$query_result = $wpdb->query($query);
				if ($query_results === FALSE)
				{
    			    echo($query);
    			}
	 		$query = "";
    		}
	  }
	}
	unset ($line);

function replace_file($path, $string, $replace)
{
    
    if (is_file($path) === true)
    {
        $file = fopen($path, 'r');
        $temp = tempnam(dirname(__FILE__), 'tmp');

        if (is_resource($file) === true)
        {
            while (feof($file) === false)
            {
                file_put_contents($temp, str_replace($string, $replace, fgets($file)), FILE_APPEND);
            }

            fclose($file);
        }

        unlink($path);
    }

    return rename($temp, $path);
}