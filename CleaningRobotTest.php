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

    public function dataProvider()
    {
        $testFileList = [
            ['test1.json', 'test1_result.json'],
            ['test2.json', 'test2_result.json']
        ];

        $dataToProvide = [];

        foreach ($testFileList as $testCase) {
            $inputJSON = file_get_contents($testCase[0]);
            $resultJSON = file_get_contents($testCase[1]);

            $dataToProvide[$testCase[0]] = [json_decode($inputJSON, true), json_decode($resultJSON, true)];
        }

        return $dataToProvide;
    }

    /**
     * @depends      testConstruct
     * @dataProvider dataProvider
     *
     * @param array $inputData
     * @param array $resultData
     * @param CleaningRobot $robot
     * @return array
     */
    public function testInput(array $inputData, array $resultData, CleaningRobot $robot)
    {
        $this->assertEquals(true, $robot->input($inputData));

        return [$robot, $resultData];
    }
}
