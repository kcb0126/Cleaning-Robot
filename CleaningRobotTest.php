<?php
/**
 * Created by PhpStorm.
 * User: kcb01
 * Date: 8/31/2018
 * Time: 5:42 PM
 */

include_once('CleaningRobot.php');

class CleaningRobotTest extends PHPUnit\Framework\TestCase
{
    /**
     * @return CleaningRobot
     */
    public function testConstruct()
    {
        $robot = new CleaningRobot();
        $this->assertNotNull($robot);
        return $robot;
    }

    public function inputDataProvider()
    {
        return [
            'test1' => [
                [
                    "map" => [
                        ["S", "S", "S", "S"],
                        ["S", "S", "C", "S"],
                        ["S", "S", "S", "S"],
                        ["S", "null", "S", "S"]
                    ],
                    "start" => ["X" => 3, "Y" => 0, "facing" => "N"],
                    "commands" => [ "TL","A","C","A","C","TR","A","C"],
                    "battery" => 80
                ], true
            ],

            'test2' => [
                [
                    "map" => [
                        ["S", "S", "S", "S"],
                        ["S", "S", "C", "S"],
                        ["S", "S", "S", "S"],
                        ["S", "null", "S", "S"]
                    ],
                    "start" => ["X" => 3, "Y" => 1, "facing" => "S"],
                    "commands" => [ "TR","A","C","A","C","TR","A","C"],
                    "battery" => 1094
                ], true]
        ];
    }

    /**
     * @depends      testConstruct
     * @dataProvider inputDataProvider
     *
     * @param array $data
     * @param bool $succeeded
     * @param CleaningRobot $robot
     * @return CleaningRobot
     */
    public function testInput(array $data, bool $succeeded, CleaningRobot $robot)
    {
        $this->assertEquals($succeeded, $robot->input($data));

        return $robot;
    }
}
