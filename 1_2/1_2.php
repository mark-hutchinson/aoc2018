<?php
$inputFile = fopen("input.txt", "r");

$currFreq = 0;
$frequenciesSeen = array();
$frequenciesSeen[$currFreq] = true;

while (true) {

  // keep reading file until we find a duplicate
  if (feof($inputFile)) {
    rewind($inputFile);
  }
  $line = fgets($inputFile);
  $number = intval($line);

  $currFreq += $number;
  if (isset($frequenciesSeen[$currFreq])) {
    break; // We found it!
  } else {
    $frequenciesSeen[$currFreq] = true;
  }

}
echo $currFreq . "\n";