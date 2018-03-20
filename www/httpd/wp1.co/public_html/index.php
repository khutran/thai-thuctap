<html>
    <head>
        <title>Welcome to wptest.com!</title>
    </head>
    <body>
        <h1>Success!  The 2 wp1.com server block is working!</h1>
    </body>
</html>

<?php
# Fill our vars and run on cli
# $ php -f db-connect-test.php
    $dbname = 'wordpress';
    $dbuser = 'thaild';
    $dbpass = 'thaild';
    $dbhost = '172.22.0.3';
 
    $con=mysqli_connect("172.22.0.3","thaild","thaild","wordpress");
    // Check connection
    if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }else {
        echo "Sucsses connect to db";
    }

?>

<?php
    phpinfo();
?>