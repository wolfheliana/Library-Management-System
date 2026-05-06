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

//---------------------Delete Book------------------------//
//get data from the form
$barcode = $_POST['barcode'];

//begin transaction
pg_query($dbconn, "BEGIN TRANSACTION");

//get isbn from given barcode
$isbnresult = pg_query($dbconn, 
"SELECT isbn FROM copies WHERE barcode = '$barcode'"
);
$isbn = pg_fetch_assoc($isbnresult);
$isbn = $isbn['isbn'];

//check to see if there is more than one copy of the book
$copycheck = pg_query($dbconn, 
"SELECT isbn FROM copies WHERE isbn = '$isbn'"
);

//if multiple copies exist, reduce quantity in books by 1 and delete copy
if (pg_num_rows($copycheck) > 1) {
$update = pg_query($dbconn,
  "UPDATE books SET quantity = (quantity - 1) WHERE isbn = '$isbn'"
  );

$deletion = pg_query($dbconn,
  "DELETE FROM copies WHERE barcode = '$barcode'"
  );

if ($isbn && $update && $deletion){
//commit transaction
pg_query($dbconn, "COMMIT");
//display confirmation message for librarian
echo '<h2>Book Deleted Successfully!</h2><br>';
}
else {
echo '<h2>Error! Please Try Again!</h2><br>';
}
}

//if the book being deleted is the only copy, delete all corresponding info from both books and copies tables
else {
$deletioncopy = pg_query($dbconn,
  "DELETE FROM copies WHERE barcode = '$barcode'"
  );

$deletionbook = pg_query($dbconn,
  "DELETE FROM books WHERE isbn = '$isbn'"
);

if ($isbn && $deletioncopy && $deletionbook){
//commit transaction
pg_query($dbconn, "COMMIT");
//display confirmation message for librarian
echo '<h2>Book Successfully Deleted!</h2><br>';
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