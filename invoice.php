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

//----------Data Acquisition and Setup----------//

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

//get id
$id = $card_data['id'];

//------------------------------------------------------//

//----------------------Display Invoice----------------------//
echo '<h2>Invoice:</h2>';
//display name and address for mailing purposes
echo 'Name: '.$card_data['name'].'<br>';
echo 'Address: '.$card_data['address'];

//---------------Display Current Checkouts---------------//
//get current checkouts and calulate date information
$result = pg_query($dbconn, "SELECT b.title, b.releasedate, cc.barcode, cc.checkoutdate,

(cc.checkoutdate + 7) AS duedate,
GREATEST(CURRENT_DATE - (cc.checkoutdate + 7), 0) AS daysoverdue,

CASE
WHEN (cc.checkoutdate - b.releasedate) <= 30 THEN 0.50
ELSE 0.10
END AS feeamount

FROM
currentcheckouts cc
JOIN copies c ON cc.barcode = c.barcode
JOIN books b ON c.isbn = b.isbn
WHERE cc.id = '$id'");

//set up table
echo '<h3>Current Checkouts</h3>';

echo '<table border="1">';
echo '<tr><th>Title</th><th>Barcode</th><th>Due Date</th><th>Days Overdue</th><th>Overdue Fee Per Day</th><th>Total Fee Owed</th></tr>';

//create variable to check is table has information
$currentcheckoutsexist = FALSE;

//display current chcekout invoice

while ($current_checkout_data = pg_fetch_assoc($result)){

//set tracking variable to true if data exists
$currentcheckoutsexist = TRUE;

//display information in table format
echo '<tr>';
echo '<td>'.$current_checkout_data['title'].'</td>';
echo '<td>'.$current_checkout_data['barcode'].'</td>';
echo '<td>'.($current_checkout_data['duedate']).'</td>';
echo '<td>'.($current_checkout_data['daysoverdue']).'</td>';
echo '<td>$'.($current_checkout_data['feeamount']).'</td>';
echo '<td>$'.(($current_checkout_data['feeamount']))*($current_checkout_data['daysoverdue']).'</td>';
echo '</tr>';
}

if ($currentcheckoutsexist == FALSE) {
echo'<tr><td colspan="6">No Current Checkouts</td></tr>';
}

echo '</table>';
//-------------------------------------------------------//

//---------------Display Past Checkouts---------------//
//get past checkouts and calulate date information
$result = pg_query($dbconn, "SELECT b.title, b.releasedate, pc.barcode, pc.checkoutdate, pc.returndate,

(pc.checkoutdate + 7) AS duedate,
GREATEST(pc.returndate - (pc.checkoutdate + 7), 0) AS daysoverdue,

CASE
WHEN (pc.checkoutdate - b.releasedate) <= 30 THEN 0.50
ELSE 0.10
END AS feeamount

FROM
pastcheckouts pc
JOIN copies c ON pc.barcode = c.barcode
JOIN books b ON c.isbn = b.isbn
WHERE pc.id = '$id'");

//set up table
echo '<h3>Past Checkouts</h3>';

echo '<table border="1">';
echo '<tr><th>Title</th><th>Barcode</th><th>Due Date</th><th>Date Returned</th><th>Days Overdue</th><th>Overdue Fee Per Day</th><th>Total Fee Owed</th></tr>';

//create variable to check is table has information
$pastcheckoutsexist = FALSE;

//display current chcekout invoice

while ($past_checkout_data = pg_fetch_assoc($result)){

//set tracking variable to true if data exists
$pastcheckoutsexist = TRUE;

//display information in table format
echo '<tr>';
echo '<td>'.$past_checkout_data['title'].'</td>';
echo '<td>'.$past_checkout_data['barcode'].'</td>';
echo '<td>'.($past_checkout_data['duedate']).'</td>';
echo '<td>'.($past_checkout_data['returndate']).'</td>';
echo '<td>'.($past_checkout_data['daysoverdue']).'</td>';
echo '<td>$'.($past_checkout_data['feeamount']).'</td>';
echo '<td>$'.(($past_checkout_data['feeamount']))*($past_checkout_data['daysoverdue']).'</td>';
echo '</tr>';
}

if ($pastcheckoutsexist == FALSE) {
echo'<tr><td colspan="7">No Current Checkouts</td></tr>';
}

echo '</table>';
//----------------------------------------------------//



//-----------------------------------------------------------//
?>
</body>

</html>