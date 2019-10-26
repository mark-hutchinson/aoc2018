<?php

$file = fopen("input.txt", "r");

$containsThreeCount = 0;
$containsTwoCount = 0;

while (!feof($file)) {
  $line = fgets($file);

  // count number of each char in the string
  $charCount = array();
  for ($i = 0; $i<strlen($line); $i++) {
    $char = $line[$i];
    if (!isset($charCount[$char])) {
      $charCount[$char] = 1;
    } else {
      $charCount[$char]++;
    }
  }

  // check if there are exactly 3 or exactly 2 of any character
  $containsThree = false;
  $containsTwo = false;
  foreach($charCount as $char => $count) {
    if ($count === 3) {
      $containsThree = true;
    }
    else if ($count === 2) {
      $containsTwo = true;
    }
  }

  // Increment counts
  if ($containsThree === true) $containsThreeCount++;
  if ($containsTwo === true) $containsTwoCount++;
}

echo $containsThreeCount * $containsTwoCount . "\n";