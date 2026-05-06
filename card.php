<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8" />
<title>Library</title>

</head>

<body>

<?php

//---------------------PHP Setup------------------------//
//read credentials from Apache configuration (instead of hardcoding them)
$creds = "user='" . getenv('psql_user') . "'"
       . "password='" . getenv('psql_pw') . "'";
//try to connect to PostgreSQL
$dbconn = pg_connect("host=localhost dbname=library $creds")
  or die('Could not connect: ' . pg_last_error());
//------------------------------------------------------//

//get individual data from cards table
$user = $_REQUEST['user'];
$query = "
SELECT * FROM cards
WHERE id = '$user';
";

//turn into usable data
$result = pg_query($dbconn, $query)
or die('Query failed: ' . pg_last_error());

$card_data = pg_fetch_assoc($result);

//display library card info
//display card holder name
echo '<h1>'.$card_data['name'].'</h1>';
//display card id
echo '<h2>Card ID: '.$card_data['id'].'</h2>';
//display address
echo '<h2>Address: '.$card_data['address'].'</h2>';
//-------------------------------------------//

?>
</body>

</html>