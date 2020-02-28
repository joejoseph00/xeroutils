<html>
<body>
<div id="wrapper">

<?php

// To Reac CSV File
$csvfile = '2019_avril_mai.csv';
$handle = fopen($csvfile, "r");
echo '<table border="1"><tr><td>Column 1</td><td>Column 2</td></tr><tr>';
$newCsvArray = array();
$rowCount = 0;
while (($data = fgetcsv($handle, "r")) !== FALSE) {
  $data = array_map("utf8_encode", $data); //added
  $num = count ($data);
  for ($b4=0; $b4 < $num; $b4++) {
    if ($num > 3) {
      //XERO= *Date	*Amount         Payee	        Description	Reference	Check Number
      //BNC==  Date	 Description	Référence	Retraits	Dépôts  	  Solde	   Transit         émetteur	Contrepartie
      $newCsvArray[$rowCount][0] = $data[0];
      // Amount
      if (!empty($data[3])) {
        if (is_numeric($data[3])) {
          $newCsvArray[$rowCount][1] = $data[3] * -1;
          if ($data[3] < 1) {
            $newCsvArray[$rowCount][1] = $data[3];
          }
        }
        else {
          echo $data[3] . "\n<br>";
        }
      }
      if (!empty($data[4])) {
        $newCsvArray[$rowCount][1] = $data[4];
      }
      // Payee
      if (!empty($data[6])) {
        $newCsvArray[$rowCount][2] = $data[6];
      }
      else {
        $newCsvArray[$rowCount][2] = ' ';
      }
      // Description
      $newCsvArray[$rowCount][3] = $data[1];
      // Description
      if (!empty($data[1])) {
        $newCsvArray[$rowCount][3] = $data[1];
      }
      // Reference
      if (!empty($data[2])) {
        $newCsvArray[$rowCount][4] = $data[2];
      }
      else {
        $newCsvArray[$rowCount][4] = ' ';
      }
      $newCsvArray[$rowCount][5] = '';
      if ($rowCount === 1) {
        $newCsvArray[$rowCount][0] = '*Date';
        $newCsvArray[$rowCount][1] = '*Amount';
        $newCsvArray[$rowCount][2] = 'Payee';
        $newCsvArray[$rowCount][3] = 'Description';
        $newCsvArray[$rowCount][4] = 'Reference';
        $newCsvArray[$rowCount][5] = 'Check Number';
      }
    }
    else {
      $newCsvArray[$rowCount] = $data[$b4]; 
    }
  }
  $rowCount++;
  for ($c=0; $c < $num; $c++) {
    // output data
    echo "<td>$data[$c]</td>";
  }
  echo "</tr><tr>";
}

$colsNew = count($newCsvArray[1]);
for ($row = 1; $row < count($newCsvArray); $row++) {
  $newData = $newCsvArray[$row];
  for ($c=0; $c < $colsNew; $c++) {
    // output data
    if (isset($newData[$c])) {
      echo "<td>$newData[$c]</td>";
    }
    else {
      echo "<td>WTF?:$c</td>";
    }
  }
  echo "</tr><tr>";
}
fclose($handle);
echo "</tr>";
echo "</table>";


// To Write CSV File
$file = fopen("xero_format.csv","w");
fputcsv($file,$newCsvArray[1]);
// J'ai inversé l'ordre, date descendant.
// Reversed the order so date descending.
for ($rowNum = count($newCsvArray) -1; $rowNum > 1; $rowNum--) {
  if ($rowNum > 0) {
    // Skip bogus row.
    fputcsv($file,$newCsvArray[$rowNum]);
  }
}
fclose($file);

?>

</div>
</body>
</html>
