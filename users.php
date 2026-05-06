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

//---------------------Individual Library Card Pages------------------------//
echo '<div style="text-align: center">';
if (isset($_REQUEST['user'])) {
$user = $_REQUEST['user'];

//----------Data Acquisition and Setup----------//
//get individual data from cards table
$result = pg_query($dbconn, "SELECT * FROM cards WHERE id = '$user'")
  or die('Query failed: ' . pg_last_error());

$card_data = pg_fetch_assoc($result);

//---------------------------------------------//

//----------------Print Options-------------//
echo '<a href ="card.php?user='.$card_data['id'].'" target="_blank">';
echo '<button>Print Replacement Card</button></a>';
echo '<a href ="invoice.php?user='.$card_data['id'].'" target="_blank">';
echo '<button>Print Invoice</button></a>';
//------------------------------------------//

//----------Display Basic Card Info----------//
//display card holder name
echo '<h1>'.$card_data['name'].'</h1>';
//display number of books checked out
echo '<h2>Number of Books Currently Checked Out: '.$card_data['checkoutcount'].'</h2>';
//display card id
echo 'Card ID: '.$card_data['id'].'<br>';
//display address
echo 'Address: '.$card_data['address'].'<br>';
//-------------------------------------------//

//------------------Update Info-------------------//
echo '<h3>Update Name and Address:</h3>';
//create update form and send submitted info to userupdate.php
echo '
<form action="userupdate.php" method="post">
<input type="hidden" name="id" value="' . $card_data['id'] . '">
Name: <input type="text" name="name" maxlength="80" required> example format: Paige Reeder<br>
Address: <input type="text" name = "address" maxlength="200" required> example format: 123 MAIN ST, NEW YORK NY 10001<br>
<input type="submit">
</form>
';
//------------------------------------------------//

//navigation
echo '<br>';
echo'<a href = "index.php"> |Home| </a>';
echo'<a href = "users.php"> |All Library Cards| </a>';
echo '</div>';
}

//-------------------------------------------------------------------------//


//---------------------------------All Cards------------------------------------//
else{

//---------------------Display List of Users------------------------//
echo '<h2>All Library Cards:</h2>';

//get all library card data from database
$query = "
SELECT * FROM cards
ORDER BY id;
";

//turn into usable data
$result = pg_query($dbconn, $query)
or die('Query failed: ' . pg_last_error());

//loop through and display all library card holders
while($card_data = pg_fetch_assoc($result)) {
  echo '<a href="users.php?user=' . $card_data['id'] . '">' . $card_data['name'] . '</a><br>';
}
//-----------------------------------------------------------------//

//---------------------Register New Users---------------------------//
echo '<h3>Register a new library card:</h3>';

//create registration form and send submitted data to cardregistration.php
echo '
<form action="cardregistration.php" method="post">
ID: <input type="text" name="id" minlength="6" maxlength="6" required> example format: 000001<br>
Name: <input type="text" name="name" maxlength="80" required> example format: Paige Reeder<br>
Address: <input type="text" name = "address" maxlength="200" required> example format: 123 MAIN ST, NEW YORK NY 10001<br>
<input type="submit">
</form>
';
//-------------------------------------------------------------------//

//navigation
echo '<br>';
echo '<a href = "index.php"> |Home| </a>';
echo '</div>';
}
//---------------------------------------------------------------------------//
?>
</body>

</html>