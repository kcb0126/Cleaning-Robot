<?php
/**
 * Created by PhpStorm.
 * User: kcb01
 * Date: 8/31/2018
 * Time: 5:41 PM
 */

include_once('CleaningRobot.php');

if($argc < 3) {
    die("\nphp main.php <source.json> <result.json>\n");
}

$sourceFile = $argv[1];
$resultFile = $argv[2];

$inputJSON = file_get_contents($sourceFile);

$robot = new CleaningRobot();

if(!$robot->input(json_decode($inputJSON, true))) {
    die("\nSource json file is not correct.\n");
}

$result = $robot->execute();

file_put_contents($resultFile, json_encode($result, JSON_PRETTY_PRINT));