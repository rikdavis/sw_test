<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="css/swtest.css">
  </head>
  <body>
    <li class="button" onclick="location='index.html'">Home</li>
    <h2>TASK 2 - Shipping Date Update</h2>
    <div class="container">
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

$sql  = "SELECT orderid, LEFT(SUBSTRING_INDEX(comments, 'Expected Ship Date:', -1), 9) AS ship_raw, ";
$sql .= "SUBSTR(LEFT(SUBSTRING_INDEX(comments, 'Expected Ship Date:', -1), 9), 8, 2) + 2000 AS ship_year, ";
$sql .= "SUBSTR(LEFT(SUBSTRING_INDEX(comments, 'Expected Ship Date:', -1), 9), 5, 2) AS ship_day, ";
$sql .= "SUBSTR(LEFT(SUBSTRING_INDEX(comments, 'Expected Ship Date:', -1), 9), 2, 2) AS ship_month ";
$sql .= "FROM sweetwater_test WHERE comments LIKE '%Expected Ship Date:%'";

$results = $mysqli->query($sql);
$rec_count = 0;
foreach($results as $result) {
  $new_date = $result['ship_year'] . '-' . $result['ship_month'] . '-' . $result['ship_day'];
  $updt_qry = "UPDATE sweetwater_test SET shipdate_expected = '$new_date' WHERE orderid = {$result['orderid']}";
  if($mysqli->query($updt_qry) === TRUE) print "Order ID: {$result['orderid']} updated to $new_date<br/>" . PHP_EOL;
  $rec_count++;
}

$report = "$rec_count of $results->num_rows records updated";
print "<h4>$report</h4>" . PHP_EOL;
$results->close();
?>
    </div>
  </body>
</html>
