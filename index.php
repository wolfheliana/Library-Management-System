<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8" />
<title>Library</title>

</head>

<body style="text-align: center; color: #5f3600; background-color: #3bcab2;">

<?php

//---------------------PHP Setup------------------------//
//read credentials from Apache configuration (instead of hardcoding them)
$creds = "user='" . getenv('psql_user') . "'"
       . "password='" . getenv('psql_pw') . "'";
//try to connect to PostgreSQL
$dbconn = pg_connect("host=localhost dbname=library $creds")
  or die('Could not connect: ' . pg_last_error());
//------------------------------------------------------//

//---------------------Home Page Display------------------------//
echo '<h1>Imaginary Library</h1>';
echo '<h2>Welcome to the Imaginary Library</h2>';
echo '<p>This is the home page. Click the links below to explore.';
echo '<br>';
echo '<a href = "catalog.php">Catalog</a>';
echo '<br>';
echo '<a href = "users.php">Library Card Holders</a>';
echo '<br>';
echo '<a href = "checkout.php">Check Out</a>';
echo '<br>';
echo '<a href = "checkin.php">Check In</a>';
//--------------------------------------------------------------//

?>
</body>

</html>