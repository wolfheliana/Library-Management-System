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

//---------------------Scan Library Card------------------------//
if (!isset($_POST['card'])) {
echo '<h3>Scan Library Card to Begin the Check Out Process:</h3>';
//create library card form and send submitted info to checkout.php (this file)
//have a specific date field instead of using CURRENTDATE for easier testing
echo '
<form action="checkout.php" method="post">
Card ID: <input type="text" name="card" minlength="6" maxlength="6" required> example format: 000001<br>
Date: <input type = "date" name = "checkoutdate" required> example format: 11-30-1979<br>
<input type="submit">
</form>
';

//navigation
echo '<br>';
echo '<a href = "index.php"> |Home| </a>';
}
//--------------------------------------------------------------//

//---------------------Scan Books------------------------//
//if a library card has been scanned, begin check out process
if (isset($_POST['card']) && !isset($_POST['barcode'])) {

$card = $_POST['card'];

//check if library card exists
$cardresult = pg_query($dbconn, 
"SELECT * FROM cards WHERE id = '$card'"
);

if (pg_num_rows($cardresult) > 0) {

echo '<h3>Scan Book:</h3>';
//create book scan form and send submitted info to checkout.php (this file)
echo '
<form action="checkout.php" method="post">
<input type="hidden" name="card" value="' . $_POST['card'] . '">
<input type="hidden" name="checkoutdate" value="' . $_POST['checkoutdate'] . '">
Barcode: <input type="text" name="barcode" minlength="4" maxlength="4" required> example format: B001
<input type="submit">
</form>
';

//button to end process
echo '<div>
<a href ="index.php">
<button>End Check Out Process</button></a>
</div>';
}

//display error message if card not found
else {
echo '<h2>Error! Card Not Found! Please Try Again!</h2><br>';

//create library card form and send submitted info to checkout.php (this file)
echo '<h3>Scan Library Card to Begin the Check Out Process:</h3>';
echo '
<form action="checkout.php" method="post">
Card ID: <input type="text" name="card" minlength="6" maxlength="6" required> example format: 000001<br>
Date: <input type = "date" name = "checkoutdate" required> example format: 11-30-1979<br>
<input type="submit">
</form>
';

//navigation
echo '<br>';
echo '<a href = "index.php"> |Home| </a>';
}
}
//------------------------------------------------------//

//---------------------Process Checkout------------------------//
//if a book has been scanned, process the checkout
if (isset($_POST['barcode'])) {

//get info from the forms
$card = $_POST['card'];
$barcode = $_POST['barcode'];
$date = $_POST['checkoutdate'];

//check if barcode exists
$barcoderesult = pg_query($dbconn, 
"SELECT * FROM copies WHERE barcode = '$barcode'"
);

if (pg_num_rows($barcoderesult) > 0) {

//begin transaction
pg_query($dbconn, "BEGIN TRANSACTION");

//get isbn from given barcode
$isbnresult = pg_query($dbconn, 
"SELECT isbn FROM copies WHERE barcode = '$barcode'"
);
$isbn = pg_fetch_assoc($isbnresult);
$isbn = $isbn['isbn'];

//increase check out count on library card
$cardsupdate = pg_query($dbconn, 
"UPDATE cards SET checkoutcount = (checkoutcount + 1) WHERE id = '$card'"
);

//increase check out count on book title
$booksupdate = pg_query($dbconn, 
"UPDATE books SET checkedout = (checkedout + 1) WHERE isbn = '$isbn'"
);

$checkoutsupdate = pg_query($dbconn, 
"INSERT INTO currentcheckouts (barcode, id, checkoutdate)
VALUES
('$barcode', '$card', '$date')"
);

if ($cardsupdate && $booksupdate && $checkoutsupdate) {
//commit transaction
pg_query($dbconn, "COMMIT");
//display confirmation message for librarian
echo '<h2>Book Checked Out Successfully!</h2><br>';
}
else {
echo '<h2>Error! Please Try Again!</h2><br>';
}

//continue checkouts
echo '<h3>Scan Book:</h3>';
//create book scan form and send submitted info to checkout.php (this file)
echo '
<form action="checkout.php" method="post">
<input type="hidden" name="card" value="' . $_POST['card'] . '">
<input type="hidden" name="checkoutdate" value="' . $_POST['checkoutdate'] . '">
Barcode: <input type="text" name="barcode" minlength="4" maxlength="4" required> example format: B001
<input type="submit">
</form>
';

//button to end process
echo '<div>
<a href ="index.php">
<button>End Check Out Process</button></a>
</div>';
}

//display error message if book not found
else {
echo '<h2>Error! Book Not Found! Please Try Again!</h2><br>';

//create book scan form and send submitted info to checkout.php (this file)
echo '
<form action="checkout.php" method="post">
<input type="hidden" name="card" value="' . $_POST['card'] . '">
<input type="hidden" name="checkoutdate" value="' . $_POST['checkoutdate'] . '">
Barcode: <input type="text" name="barcode" minlength="4" maxlength="4" required> example format: B001
<input type="submit">
</form>
';

//button to end process
echo '<div>
<a href ="index.php">
<button>End Check Out Process</button></a>
</div>';
}
}
//------------------------------------------------------//
?>
</body>

</html>