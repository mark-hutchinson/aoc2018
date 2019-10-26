<?php

$file = fopen("input.txt", "r");

$lines = array();
while (!feof($file)) {
  $line = fgets($file);
  $lines[] = $line;
}

// Compare every line to every other line
for ($i = 0; $i < count($lines); $i++) {
  for ($j = $i+1; $j < count($lines); $j++){

    $str1 = $lines[$i];
    $str2 = $lines[$j];

    $diffIndex = -1;
    $success = true;
    // going to assume that string lengths are the same
    for ($k =0; $k < strlen($str1); $k++) {
      if ($str1[$k] != $str2[$k]) {
        if ($diffIndex != -1) {
          // we've already found one difference - no need to keep searching
          $success = false;
          break;
        }
        // These strings differ at index $k
        $diffIndex = $k;
      }
    }

    if ($success === true) {
      // Remove the char that differs
      echo substr($str1, 0, $diffIndex) . substr($str1, $diffIndex+1) . "\n";
      exit;
    }
  }
}

