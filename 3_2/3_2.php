<?php

$file = fopen("input.txt", "r");

const COLLISION_MARKER = "X";

$grid = array(array());

// First pass - read through the file to populate the grid
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
    }
  }
}

// Second pass - check each claim to see if any collisions
rewind($file);
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

  $success = true;
  for ($i = $x; $i < ($x + $width); $i++) {
    for ($j = $y; $j < ($y + $height); $j++) {
      if ($grid[$i][$j] == COLLISION_MARKER) {
        $success = false;
        break;
      }
    }
    if ($success == false) {
      break;
    }
  }

  if ($success) {
    echo $claim . "\n";
    exit;
  }
}