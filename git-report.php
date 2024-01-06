#!/usr/bin/php
<?php

/**
 * @var int $third takes the third value from argv and converts to int
 * @return int returns 0 if non string passed originally or more than 0 if
 * number
 */
$records = (int)$argv[2];

if($argc < 3 || $argc > 3 || $argv[1] != "-d" || $records === 0) {
  $error = <<<EOD
    Usage: ./git-report.php -d 10
    -d | number of days you wish to see the report
    -d must be more than 0
    EOD;

  die($error);
};

$dates_table =  [
  "Jan" => 1,
  "Feb" => 2,
  "Mar" => 3,
  "Mar" => 4,
  "Apr" => 5,
  "May" => 6,
  "Jun" => 7,
  "Jul" => 8,
  "Aug" => 9,
  "Oct" => 10,
  "Nov" => 11,
  "Dec" => 12,
];

// replace fd with find
$command = "git --no-pager log | rg Date | awk '{print \$6 \"-\" \$3 \"-\" \$4}'";
$res = shell_exec($command);

$arr_of_dates = preg_split("/((\r?\n)|(\r\n?))/", $res);
$arr = array_pop($arr_of_dates); // remove last value of array.

if ($arr_of_dates) {
  $res =  array_map(function ($item) {
    global $dates_table;
    preg_match("/(?<=\-)(.*)(?=\-)/", $item, $match);

    return preg_replace("/" . $match[0] . "/", $dates_table[$match[0]], $item);
  }, $arr_of_dates);

  $vals = array_count_values($res);

  $counter = $records + 1; # # to get 10 records

  foreach($vals as $date => $val) {
    $counter--;

    if ($counter < 1) {
      break;
    };
    echo $date . " - " . $val . "\n";
  };
}

