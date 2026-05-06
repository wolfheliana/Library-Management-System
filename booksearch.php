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

//---------------------Book Search------------------------//
echo '<div style="text-align: center">';
//check which search method is being used
$isbnexists = isset($_GET['isbn']);
$titleexists = isset($_GET['title']);
$authorexists = isset($_GET['author']);
$genreexists = isset($_GET['genre']);

//-------------------isbn search-------------------//
if ($isbnexists) {

//initial page setup
if (isset($_GET['pagenumber'])) {
$pagenumber = $_GET['pagenumber'];
}
else {
$pagenumber = 0;
}

//get isbn from form and search for related books
$isbn = $_GET['isbn'];
$result = pg_query($dbconn, "SELECT * FROM books WHERE isbn = '$isbn' ORDER BY title, isbn 
 LIMIT 10 OFFSET ($pagenumber * 10)")
  or die('Query failed: ' . pg_last_error());

//calculate total number of pages
$totalpagequery = pg_query($dbconn, "SELECT * FROM books WHERE isbn = '$isbn'");
$totalpages = ceil((pg_num_rows($totalpagequery) / 10));

//check if any books were found
if (pg_num_rows($result) > 0) {

//set up table
echo '<table style="margin: 0 auto;">';
echo '<tr><th>Title</th><th>Author</th><th>Genre</th><th>ISBN</th></tr>';

//display a limited set of book information related to books with the searched isbn
while ($book_data = pg_fetch_assoc($result)){
echo '<tr>';
echo '<td>'.$book_data['title'].'</td>';
echo '<td>'.$book_data['author'].'</td>';
echo '<td>'.$book_data['genre'].'</td>';
echo '<td><a href="catalog.php?book='.$book_data['isbn'].'">'.$book_data['isbn'].'</a></td>';
echo "</tr>";
}

echo "</table>";

//------------------navigation------------------//
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';

if ($totalpages > 1) {

//pagination navigation (say 3 times fast) for first page
if ($pagenumber == 0) {
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href="booksearch.php?isbn='.$isbn.'&pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href="booksearch.php?isbn='.$isbn.'&pagenumber='.($totalpages-1).'">|Last|</a>';
}

//pagination navigation for last page
else if ($pagenumber == $totalpages-1) {
//navigation to first page
echo '<a href="booksearch.php?isbn='.$isbn.'&pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href="booksearch.php?isbn='.$isbn.'&pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
}

//pagination navigation for middle page
else {
//navigation to first page
echo '<a href="booksearch.php?isbn='.$isbn.'&pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href="booksearch.php?isbn='.$isbn.'&pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href="booksearch.php?isbn='.$isbn.'&pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href="booksearch.php?isbn='.$isbn.'&pagenumber='.($totalpages-1).'">|Last|</a>';
}
}
//------------------------------------------------//
}

//if no books were found, display corresponding message (and nav)
else {
echo '<h3>No Books Found</h3>';
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';
}
}
//----------------------------------------------------//

//-------------------title search-------------------//
else if ($titleexists) {
//initial page setip
if (isset($_GET['pagenumber'])) {
$pagenumber = $_GET['pagenumber'];
}
else {
$pagenumber = 0;
}

//get title from form and search for related books
$title = $_GET['title'];
$result = pg_query($dbconn, "SELECT * FROM books WHERE title = '$title' ORDER BY title, isbn 
 LIMIT 10 OFFSET ($pagenumber * 10)")
  or die('Query failed: ' . pg_last_error());

//calculate total number of pages
$totalpagequery = pg_query($dbconn, "SELECT * FROM books WHERE title = '$title'");
$totalpages = ceil((pg_num_rows($totalpagequery) / 10));

//check if any books were found
if (pg_num_rows($result) > 0) {

//set up table
echo '<table style="margin: 0 auto;">';
echo '<tr><th>Title</th><th>Author</th><th>Genre</th><th>ISBN</th></tr>';

//display a limited set of book information related to books with the searched title
while ($book_data = pg_fetch_assoc($result)){
echo '<tr>';
echo '<td>'.$book_data['title'].'</td>';
echo '<td>'.$book_data['author'].'</td>';
echo '<td>'.$book_data['genre'].'</td>';
echo '<td><a href="catalog.php?book='.$book_data['isbn'].'">'.$book_data['isbn'].'</a></td>';
echo "</tr>";
}

echo "</table>";

//------------------navigation------------------//
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';

if ($totalpages > 1) {

//pagination navigation (say 3 times fast) for first page
if ($pagenumber == 0) {
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href="booksearch.php?title='.$title.'&pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href="booksearch.php?title='.$title.'&pagenumber='.($totalpages-1).'">|Last|</a>';
}

//pagination navigation for last page
else if ($pagenumber == $totalpages-1) {
//navigation to first page
echo '<a href="booksearch.php?title='.$title.'&pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href="booksearch.php?title='.$title.'&pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
}

//pagination navigation for middle page
else {
//navigation to first page
echo '<a href="booksearch.php?title='.$title.'&pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href="booksearch.php?title='.$title.'&pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href="booksearch.php?title='.$title.'&pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href="booksearch.php?title='.$title.'&pagenumber='.($totalpages-1).'">|Last|</a>';
}
}
//------------------------------------------------//
}

//if no books were found, display corresponding message (and nav)
else {
echo '<h3>No Books Found</h3>';
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';
}
}
//----------------------------------------------------//

//-------------------author search-------------------//
else if ($authorexists) {
//initial page setup
if (isset($_GET['pagenumber'])) {
$pagenumber = $_GET['pagenumber'];
}
else {
$pagenumber = 0;
}

//get author from form and search for related books
$author = $_GET['author'];
$result = pg_query($dbconn, "SELECT * FROM books WHERE author = '$author' ORDER BY title, isbn 
 LIMIT 10 OFFSET ($pagenumber * 10)")
  or die('Query failed: ' . pg_last_error());

//calculate total number of pages
$totalpagequery = pg_query($dbconn, "SELECT * FROM books WHERE author = '$author'");
$totalpages = ceil((pg_num_rows($totalpagequery) / 10));

//check if any books were found
if (pg_num_rows($result) > 0) {

//set up table
echo '<table style="margin: 0 auto;">';
echo '<tr><th>Title</th><th>Author</th><th>Genre</th><th>ISBN</th></tr>';

//display a limited set of book information related to books with the searched author
while ($book_data = pg_fetch_assoc($result)){
echo '<tr>';
echo '<td>'.$book_data['title'].'</td>';
echo '<td>'.$book_data['author'].'</td>';
echo '<td>'.$book_data['genre'].'</td>';
echo '<td><a href="catalog.php?book='.$book_data['isbn'].'">'.$book_data['isbn'].'</a></td>';
echo "</tr>";
}

echo "</table>";

//------------------navigation------------------//
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';

if ($totalpages > 1) {

//pagination navigation (say 3 times fast) for first page
if ($pagenumber == 0) {
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href="booksearch.php?author='.$author.'&pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href="booksearch.php?author='.$author.'&pagenumber='.($totalpages-1).'">|Last|</a>';
}

//pagination navigation for last page
else if ($pagenumber == $totalpages-1) {
//navigation to first page
echo '<a href="booksearch.php?author='.$author.'&pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href="booksearch.php?author='.$author.'&pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
}

//pagination navigation for middle page
else {
//navigation to first page
echo '<a href="booksearch.php?author='.$author.'&pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href="booksearch.php?author='.$author.'&pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href="booksearch.php?author='.$author.'&pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href="booksearch.php?author='.$author.'&pagenumber='.($totalpages-1).'">|Last|</a>';
}
}
//------------------------------------------------//
}

//if no books were found, display corresponding message (and nav)
else {
echo '<h3>No Books Found</h3>';
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';
}
}

//-------------------genre search-------------------//
else if ($genreexists) {
//set up pages
if (isset($_GET['pagenumber'])) {
$pagenumber = $_GET['pagenumber'];
}
else {
$pagenumber = 0;
}

//get genre from form and search for related books
$genre = $_GET['genre'];
$result = pg_query($dbconn, "SELECT * FROM books WHERE genre = '$genre' ORDER BY title, isbn 
 LIMIT 10 OFFSET ($pagenumber * 10)")
  or die('Query failed: ' . pg_last_error());

//calculate total number of pages
$totalpagequery = pg_query($dbconn, "SELECT * FROM books WHERE genre = '$genre'");
$totalpages = ceil((pg_num_rows($totalpagequery) / 10));

//check if any books were found
if (pg_num_rows($result) > 0) {

//set up table
echo '<table style="margin: 0 auto;">';
echo '<tr><th>Title</th><th>Author</th><th>Genre</th><th>ISBN</th></tr>';

//display a limited set of book information related to books with the searched genre
while ($book_data = pg_fetch_assoc($result)){
echo '<tr>';
echo '<td>'.$book_data['title'].'</td>';
echo '<td>'.$book_data['author'].'</td>';
echo '<td>'.$book_data['genre'].'</td>';
echo '<td><a href="catalog.php?book='.$book_data['isbn'].'">'.$book_data['isbn'].'</a></td>';
echo "</tr>";
}

echo "</table>";

//------------------navigation------------------//
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';

if ($totalpages > 1) {

//pagination navigation (say 3 times fast) for first page
if ($pagenumber == 0) {
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href="booksearch.php?genre='.$genre.'&pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href="booksearch.php?genre='.$genre.'&pagenumber='.($totalpages-1).'">|Last|</a>';
}

//pagination navigation for last page
else if ($pagenumber == $totalpages-1) {
//navigation to first page
echo '<a href="booksearch.php?genre='.$genre.'&pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href="booksearch.php?genre='.$genre.'&pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
}

//pagination navigation for middle page
else {
//navigation to first page
echo '<a href="booksearch.php?genre='.$genre.'&pagenumber=0">|First|</a>';
//navigation to previous page
echo '<a href="booksearch.php?genre='.$genre.'&pagenumber='.($pagenumber-1).'">|Prev|</a>';
//display current page number
echo $pagenumber;
//navigation to next page
echo '<a href="booksearch.php?genre='.$genre.'&pagenumber='.($pagenumber+1).'">|Next|</a>';
//navigation to last page
echo '<a href="booksearch.php?genre='.$genre.'&pagenumber='.($totalpages-1).'">|Last|</a>';
}
}
//------------------------------------------------//
}

//if no books were found, display corresponding message (and nav)
else {
echo '<h3>No Books Found</h3>';
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "catalog.php"> |Full Catalog| </a>';
}
}
//--------------------------------------------------//
echo '</div>';
//--------------------------------------------------------------//

?>
</body>

</html>