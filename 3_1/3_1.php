<?php

$file = fopen("input.txt", "r");

const COLLISION_MARKER = "X";

$grid = array(array());
$maxX = 0;
$maxY = 0;

while (!feof($file)) {
  // Example input:
  // #1 @ 16,576: 17x14
  $input = explode(" ", fgets($file));

  $claim = substr($input[0], 1); // trim the #
  $coords = substr($input[2], 0, -1); // trim the : char
  $x = intval(explode(",",$coords)[0]);
  $y = intval(explode(",", $coords)[1]);
  $width = explode("x", $input[3])[0];
  $height = explode("x", $input[3])[1];

  // Add the claim to the grid
  for ($i = $x; $i < ($x + $width); $i++) {
    for ($j = $y; $j < ($y + $height); $j++) {
      if (!isset($grid[$i][$j])) {
        $grid[$i][$j] = $claim;
      } else {
        // This square is already claimed
        $grid[$i][$j] = COLLISION_MARKER;
      }

      // keep track of size of grid - useful if we want to print it out
      if ($i > $maxX) {
        $maxX = $i;
      }
      if ($j > $maxY) {
        $maxY = $j;
      }
    }
  }
}

//printGrid($grid, $maxX, $maxY);

echo countMatches($grid,COLLISION_MARKER) . "\n";

function countMatches($grid, $match) {
  $count = 0;
  foreach($grid as $row) {
    foreach($row as $value) {
      if ($value === $match) {
        $count++;
      }
    }
  }
  return $count;
}

function printGrid($grid, $width, $height) {
  for ($j = 0; $j<$height; $j++) {
    for ($i = 0; $i<$width; $i++) {
      $gridVal = ".";
      if (isset($grid[$i][$j])) {
        $gridVal = $grid[$i][$j];
      }
      echo $gridVal . " ";
    }
    echo "\n";
  }
}