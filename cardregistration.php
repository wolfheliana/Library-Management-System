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

//---------------------Register New Users------------------------//
//get data from the form
$id = $_POST['id'];
$name = $_POST['name'];
$address = $_POST['address'];

//insert form data into library card table
$insertion = pg_query($dbconn,
  "INSERT INTO cards (id, name, address) VALUES ('$id', '$name', '$address')"
);

if ($insertion){
//display confirmation message for librarian
echo '<h2>New Library Card Successfully Registered!</h2><br>';
}
else {
echo '<h2>Error! Please Try Again!</h2><br>';
}
//link back to list of all library cards
echo'<a href = "index.php"> |Home| </a>';
echo '<a href="users.php"> |All Library Cards| </a>';
//--------------------------------------------------------------//

?>
</body>

</html>