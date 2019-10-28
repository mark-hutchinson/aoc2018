<?php

$items = array();

const INVALID_GUARD = -1;

// Read and sort the input in items array
$file = fopen("input.txt", "r");
while(!feof($file)) {
  $line = fgets($file);
  $items[] = new TimeLog($line);
}
fclose($file);
usort($items, 'compareTimeLog');

// Tracking for sleepiest guard
$guardSleepTime = array(); // guard ID to total time slept
$sleepiestGuard = INVALID_GUARD;
$mostTimeSlept = 0;

// Tracking for which minute was sleepiest
$guardSleepMinutes = array(array()); // guard ID to array with count of minutes asleep
$sleepiestMinute = 0;
$maxNumTimesSlept = 0;

$currentGuard = INVALID_GUARD;
$minuteFellAsleep = -1;
foreach ($items as $item) {
  echo $item->toString() . "\n";

  if ($item->guardId != INVALID_GUARD) {
    $currentGuard = $item->guardId;
  } else if ($item->didFallAsleep) {
    $minuteFellAsleep = $item->minutes;
  } else if ($item->didWakeUp) {
    if ($minuteFellAsleep < 0) exit("Woke up when not asleep");
    trackSleepiestGuard($minuteFellAsleep, $item->minutes, $currentGuard);
    trackMinutesSlept($minuteFellAsleep, $item->minutes, $currentGuard);
    $minuteFellAsleep = -1;
  }
}

// Find out which was the sleepiest minute
for ($i = 0; $i<60; $i++) {
  if($guardSleepMinutes[$sleepiestGuard][$i] > $maxNumTimesSlept) {
    $maxNumTimesSlept = $guardSleepMinutes[$sleepiestGuard][$i];
    $sleepiestMinute = $i;
  }
}

echo "Guard #$sleepiestGuard slept for $mostTimeSlept. They slept the most during minute $sleepiestMinute \n";
$result = $sleepiestGuard * $sleepiestMinute;
echo "Result: $result \n";


function compareTimeLog($a, $b) {
  return $a->timestamp > $b->timestamp;
}

function trackSleepiestGuard($sleepMinute, $wakeMinute, $guardId) {

  global $sleepiestGuard;
  global $mostTimeSlept;
  global $guardSleepTime;

  $timeSlept = $wakeMinute - $sleepMinute;

  if (!isset($guardSleepTime[$guardId])) {
    $guardSleepTime[$guardId] = $timeSlept;
  } else {
    $guardSleepTime[$guardId] += $timeSlept;
  }

  // Keep track of the sleepiest guard as we go
  if ($guardSleepTime[$guardId] > $mostTimeSlept) {
    $mostTimeSlept = $guardSleepTime[$guardId];
    $sleepiestGuard = $guardId;
  }
}

function trackMinutesSlept($sleepMinute, $wakeMinute, $guardId) {
  global $guardSleepMinutes;
  if (!isset($guardSleepMinutes[$guardId]) ) {
    $guardSleepMinutes[$guardId] = array_fill(0, 60, 0);
  }
  for ($i = $sleepMinute; $i < $wakeMinute; $i++) {
    $guardSleepMinutes[$guardId][$i] ++;
  }
}

class TimeLog {

  public $dateString;
  public $timeString;
  public $timestamp;

  public $minutes;

  public $guardId = INVALID_GUARD;
  public $didFallAsleep = false;
  public $didWakeUp = false;

  function __construct($input) {
    $words = explode(" ", $input);

    $this->dateString = substr($words[0], 1);
    $this->timeString = substr($words[1], 0, -1);

    $this->minutes = intval(explode(":", $this->timeString)[1]);

    // convert to timestamp for easy sorting
    $d = $this->dateString . " " . $this->timeString;
    $this->timestamp = DateTime::createFromFormat("Y-m-d G:i", $d)->getTimestamp();

    if ($words[2] == "Guard") {
      $this->guardId = intval(substr($words[3], 1));
    } else if ($words[2] == "falls") {
      $this->didFallAsleep = true;
    } else if ($words[2] == "wakes") {
      $this->didWakeUp = true;
    } else {
      exit("unexpected input " . $words[2]);
    }
  }

  function toString() {
    $str = $this->dateString . " " . $this->timeString;
    if ($this->guardId >= 0) {
      $str .= " Guard #" . $this->guardId . " started shift";
    } else if ($this->didFallAsleep) {
      $str .= " falls asleep";
    } else if ($this->didWakeUp) {
      $str .= " wakes up";
    }
    return $str;
  }
}