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

//---------------------Individual Book Pages------------------------//
if (isset($_REQUEST['book'])) {

$book = $_REQUEST['book'];

//----------Data Acquisition and Setup----------//
//get individual book data from books table
$query = "
SELECT * FROM books WHERE isbn = '$book';
";

//turn into usable data
$result = pg_query($dbconn, $query)
or die('Query failed: ' . pg_last_error());

$book_data = pg_fetch_assoc($result);

//get barcodes from copies table
$barcoderesult = pg_query($dbconn, 
"SELECT barcode FROM copies WHERE isbn = '".$book_data['isbn']."'"
);

//---------------------------------------------//

//----------Display General Book Info----------//
//display book title
echo '<h1>'.$book_data['title'].'</h1>';
//display isbn
echo 'ISBN: '.$book_data['isbn'].'<br>';
//display author
echo 'Author: '.$book_data['author'].'<br>';
//display genre
echo 'Genre: '.$book_data['genre'].'<br>';
//-------------------------------------------//

//----------Display Copy Info----------//
echo '<h3>Copy Info</h3>';
//display number of copies
echo 'Number of Copies: '.$book_data['quantity'].'<br>';
//display number of copies available
echo 'Available: '.($book_data['quantity'] - $book_data['checkedout']).'<br>';
//display all copies
echo 'All Copies:<br>';
while ($copy = pg_fetch_assoc($barcoderesult)) {
  echo '<li>Barcode: '.$copy['barcode'].'</li>';
}
//-------------------------------------------//
//navigation
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';
}
//-----------------------------------------------------------------//

//---------------------Full Catalog------------------------//
else {
echo '<h1>Welcome to the Catalog!</h1>';
echo '<h2>Search, Add, or Delete Books to and from the Collection!</h2>';
//----------Data Acquisition and Setup----------//

//---------------------------------------------//

//---------------Search Function---------------//
echo '<h3>Search By:</h3>';

//create isbn search form and send submitted info to booksearch.php
echo '
<form action="booksearch.php" method="get">
ISBN: <input type="text" name="isbn" minlength="13" maxlength="13" required>
<input type="submit"><br>
</form>
';

//create title search form and send submitted info to booksearch.php
echo '
<form action="booksearch.php" method="get">
Title: <input type="text" name="title" maxlength="150" required>
<input type="submit"><br>
</form>
';

//create author search form and send submitted info to booksearch.php
echo '
<form action="booksearch.php" method="get">
Author: <input type="text" name="author" maxlength="80" required>
<input type="submit"><br>
</form>
';

//create genre search form and send submitted info to booksearch.php
echo '
<form action="booksearch.php" method="get">
Genre: <input type="text" name="genre" maxlength="25" required>
<input type="submit"><br>
</form>
';
//--------------------------------------------//

//---------------Add New Books---------------//
echo '<h3>Add New Book to Collection:</h3>';
//create book addition form and send submitted info to bookaddition.php
echo '
<form action="bookaddition.php" method="post">
ISBN: <input type="text" name="isbn" minlength="13" maxlength="13" required> example format: 9780608108781<br>
Barcode: <input type="text" name="barcode" minlength="4" maxlength="4" required> example format: B001<br>
Title: <input type="text" name="title" maxlength="150" required> example format: To Code a Database<br>
Author: <input type="text" name = "author" maxlength="80" required> example format: Eliana Wolf<br>
Genre: <input type="text" name = "genre" maxlength="25" required> example format: Psychological Horror<br>
Release Date: <input type="date" name = "releasedate" required> example format: 11-30-1979<br>
<input type="submit">
</form>
';
//-------------------------------------------//

//---------------Delete Books---------------//
echo '<h3>Delete Book From Collection:</h3>';
//create book deletion form and send submitted info to bookdeletion.php
echo '
<form action="bookdeletion.php" method="post">
Barcode: <input type="text" name="barcode" minlength="4" maxlength="4" required> example format: B001<br>
<input type="submit">
</form>
';
//------------------------------------------//

//--------------------Book List Display--------------------//
echo '<h2>All Books (click on an ISBN for more information or page navigation below for more books!)</h2>';

if (isset($_GET['pagenumber'])) {
$pagenumber = $_GET['pagenumber'];
}
else {
$pagenumber = 0;
}

$result = pg_query($dbconn, "SELECT * FROM books ORDER BY title, isbn 
 LIMIT 10 OFFSET ($pagenumber * 10)")
  or die('Query failed: ' . pg_last_error());

$totalpagequery = pg_query($dbconn, "SELECT * FROM books");

$totalpages = ceil((pg_num_rows($totalpagequery) / 10));

//set up table
echo '<table style="margin: 0 auto;">';
echo '<tr><th>Title</th><th>Author</th><th>Genre</th><th>ISBN</th></tr>';

//display a limited set of book information
while ($book_data = pg_fetch_assoc($result)){
echo '<tr>';
echo '<td>'.$book_data['title'].'</td>';
echo '<td>'.$book_data['author'].'</td>';
echo '<td>'.$book_data['genre'].'</td>';
echo '<td><a href="catalog.php?book='.$book_data['isbn'].'">'.$book_data['isbn'].'</a></td>';
echo "</tr>";
}

echo "</table>";
//---------------Navigation---------------//
echo '<br>';
echo '<a href = "index.php"> |Home| </a>';

//pagination navigation (say 3 times fast) for first page
if ($pagenumber == 0) {
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href = "catalog.php?pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href = "catalog.php?pagenumber='.($totalpages-1).'">|Last|</a>';
}

//pagination navigation for last page
else if ($pagenumber == $totalpages-1) {
//navigation to first page
echo '<a href = "catalog.php?pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href = "catalog.php?pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
}

//pagination navigation for middle page
else {
//navigation to first page
echo '<a href = "catalog.php?pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href = "catalog.php?pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href = "catalog.php?pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href = "catalog.php?pagenumber='.($totalpages-1).'">|Last|</a>';
}
//------------------------------------------//
}


//----------------------------------------------------//


//---------------------------------------------------------//
?>
</body>

</html>