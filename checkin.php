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

//---------------------Scan Book------------------------//
if (!isset($_POST['barcode'])) {
echo '<h3>Scan Book to Check Back In:</h3>';
//create check in and send submitted info to checkin.php (this file)
//have a specific date field instead of using CURRENTDATE for easier testing
echo '
<form action="checkin.php" method="post">
Barcode: <input type="text" name="barcode" minlength="4" maxlength="4" required> example format: B001<br>
Date: <input type = "date" name = "checkindate" required> example format: 11-30-1979<br>
<input type="submit">
</form>
';

//navigation
echo '<br>';
echo '<a href = "index.php"> |Home| </a>';
}
//--------------------------------------------------------------//

//---------------------Process Check In------------------------//
if (isset($_POST['barcode'])) {

//get barcode and check in date from form
$barcode = $_POST['barcode'];
$checkindate = $_POST['checkindate'];

//check if book is actually checked out
$barcoderesult = pg_query($dbconn, 
"SELECT * FROM currentcheckouts WHERE barcode = '$barcode'"
);

if (pg_num_rows($barcoderesult) > 0) {

//begin transaction
pg_query($dbconn, "BEGIN TRANSACTION");

//turn into usable data
$checkout_data = pg_fetch_assoc($barcoderesult);

//get card id and checkout date
$card = $checkout_data['id'];
$checkoutdate = $checkout_data['checkoutdate'];

//get isbn from given barcode
$isbnresult = pg_query($dbconn, 
"SELECT isbn FROM copies WHERE barcode = '$barcode'"
);
$isbn = pg_fetch_assoc($isbnresult);
$isbn = $isbn['isbn'];

//decrease check out count on library card
$cardsupdate = pg_query($dbconn, 
"UPDATE cards SET checkoutcount = (checkoutcount - 1) WHERE id = '$card'"
);

//decrease check out count on book title
$booksupdate = pg_query($dbconn, 
"UPDATE books SET checkedout = (checkedout - 1) WHERE isbn = '$isbn'"
);

//insert info into past checkouts table
$pcheckoutinsertion = pg_query($dbconn,
"INSERT INTO pastcheckouts (barcode, id, checkoutdate, returndate)
 VALUES ('$barcode', '$card', '$checkoutdate', '$checkindate')"
);

//delete info from currentcheckouts table
$ccheckoutdeletion = pg_query($dbconn,
"DELETE FROM currentcheckouts WHERE barcode = '$barcode'"
);


if ($cardsupdate && $booksupdate && $pcheckoutinsertion && $ccheckoutdeletion) {
//commit transaction
pg_query($dbconn, "COMMIT");
//display confirmation message for librarian
echo '<h2>Book Checked In Successfully!</h2><br>';
}
else {
echo '<h2>Error! Please Try Again!</h2><br>';
}

//continue check ins
echo '<h3>Scan Book to Check Back In:</h3>';
//create check in and send submitted info to checkin.php (this file)
echo '
<form action="checkin.php" method="post">
<input type="hidden" name="checkindate" value="' . $checkindate . '">
Barcode: <input type="text" name="barcode" minlength="4" maxlength="4" required> example format: B001
<input type="submit">
</form>
';

//button to end process
echo '<div>
<a href ="index.php">
<button>End Check In Process</button></a>
</div>';
}

//display error message if book not found
else {
echo '<h2>Error! Book Not Found! Please Try Again!</h2><br>';

//create book scan form and send submitted info to checkout.php (this file)
echo '
<form action="checkin.php" method="post">
Barcode: <input type="text" name="barcode" minlength="4" maxlength="4" required> example format: B001<br>
Date: <input type = "date" name = "checkindate" required> example format: 11-30-1979<br>
<input type="submit">
</form>
';

//button to end process
echo '<div>
<a href ="index.php">
<button>End Check In Process</button></a>
</div>';
}
}
//--------------------------------------------------------------//
?>
</body>

</html>