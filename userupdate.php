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

//---------------------Update User Info------------------------//
//get data from the form
$id = $_POST['id'];
$name = $_POST['name'];
$address = $_POST['address'];

//insert form data into library card table
$update = pg_query($dbconn,
  "UPDATE cards
   SET name = '$name', address = '$address'
   WHERE id = '$id'"
);

if ($update){
//display confirmation message for librarian
echo '<h2>Info Successfully Updated!</h2><br>';
}
else {
echo '<h2>Error! Please Try Again!</h2><br>';
}
//link back to individual card page and home
echo'<a href = "index.php"> |Home| </a>';
echo '<a href="users.php?user='.$id.'"> |Previous Library Card| </a>';
//--------------------------------------------------------------//

?>
</body>

</html>