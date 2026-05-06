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

//---------------------Add New Book------------------------//
//get data from the form
$isbn = $_POST['isbn'];
$barcode = $_POST['barcode'];
$title = $_POST['title'];
$author = $_POST['author'];
$genre = $_POST['genre'];
$releasedate = $_POST['releasedate'];

//begin transaction
pg_query($dbconn, "BEGIN TRANSACTION");

//check to see if a different copy of the new book already exists
$copycheck = pg_query($dbconn, "
SELECT isbn FROM books WHERE isbn = '$isbn'
");

//if a copy of the book already exists, add info to copies table and update quantity in books table
if (pg_num_rows($copycheck) > 0) {
$update = pg_query($dbconn,
  "UPDATE books SET quantity = (quantity + 1) WHERE isbn = '$isbn'"
);
$insertion = pg_query($dbconn,
  "INSERT INTO copies (barcode, isbn)
   VALUES ('$barcode', '$isbn')
  "
);

if ($update && $insertion){
//commit transaction
pg_query($dbconn, "COMMIT");
//display confirmation message for librarian
echo '<h2>New Book Successfully Added!</h2><br>';
}
else {
echo '<h2>Error! Please Try Again!</h2><br>';
}
}

//if no copy of a book exists, add all info to books table and copies table
else {
$insertionbook = pg_query($dbconn,
  "INSERT INTO books (isbn, title, author, genre, releasedate, quantity)
   VALUES ('$isbn', '$title', '$author', '$genre', '$releasedate', 1)
   "
);
$insertioncopy = pg_query($dbconn,
  "INSERT INTO copies (barcode, isbn)
   VALUES ('$barcode', '$isbn')
");

if ($insertionbook && $insertioncopy){
//commit transaction
pg_query($dbconn, "COMMIT");
//display confirmation message for librarian
echo '<h2>New Book Successfully Added!</h2><br>';
}
else {
echo '<h2>Error! Please Try Again!</h2><br>';
}
}

//link back to list of all books and home
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';
//--------------------------------------------------------------//

?>
</body>

</html>