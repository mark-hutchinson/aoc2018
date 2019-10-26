<?php
$inputFile = fopen("input.txt", "r");
$result = 0;
while (!feof($inputFile)) {
  $line = fgets($inputFile);
  $number = intval($line);
  $result += $number;
}
echo $result . "\n";