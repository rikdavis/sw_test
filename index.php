<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="css/swtest.css">
  </head>
  <body>
    <h2>Comments Report</h2>
    <div class="container">
<?php
/*
 I chose to keep this work as rudimentary as possible to show coding rather than use of frameworks.
 I KNOW! Absolute horrid form to do HTML and code all in one file.
 Again, this is simply to show work more than extensibility or best practices.
 Please forgive this ugliness.
*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
// Create groups by where clause context
$groups = ["candy", "call me_don\'t call me", "referred", "signature"];

// Start group loop
foreach($groups as $group) {
  print "<h3>Group: $group</h3>";
  // Detect if we're on the call me element yet.
  if(preg_match('/\\_/', $group)) {
    // Break the element up into the two separate contexts
    $qry_parts = explode('_', $group);
    // Include the first element of the qry_parts array into the SQL string
    // letting the do|while loop grab the second one to concat to it.
    $sql = "SELECT * FROM sweetwater_test WHERE comments LIKE '%$qry_parts[0]%' OR ";
    do {
      // Iterate now instead of post concat operation to prevent
      // reading the first element of the parts array.
      $part_idx++;
      $sql .= "'%$qry_parts[$part_idx]%'";
    } while($part_idx < count($qry_parts) - 1);
  } else {
    $sql = "SELECT * FROM sweetwater_test WHERE comments LIKE '%$group%'";
  }

  $results = $mysqli->query($sql);
  // Start query results loop
  print "<table>";
  foreach($results as $idx => $result) {
    // Let's just grab the field names for only the first iteration
    if(!$idx) {
      print "<tr class=\"field_titles\">" . PHP_EOL;
      foreach(array_keys($result) as $field) {
        if($idx) break;
        print "<td>$field</td>" . PHP_EOL;
      }
      print "</tr>" . PHP_EOL;
    }
    print "<tr class=\"data\">" . PHP_EOL;
    foreach(array_values($result) as $value) {
      print "<td>$value</td>" . PHP_EOL;
    }
    print "</tr>" . PHP_EOL;
  }
  print "</table>";

  $results->close();
}
?>
    </div> <!-container close->
  </body>
</html>