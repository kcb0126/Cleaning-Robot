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
    protected function assertEqualsArrayAsSet(array $expected, array $actual)
    {
        foreach ($expected as $item) {
            $found = false;
            foreach ($actual as $key => $itemActual) {
                if($item === $itemActual) {
                    $found = true;
                    unset($actual[$key]);
                    break;
                }
            }

            if(!$found) $this->assertTrue(false, 'Failed asserting that two arrays are equal');
        }

        $this->assertEmpty($actual, 'Failed asserting that two arrays are equal');
    }

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
     * @param array $expectedResult
     * @param CleaningRobot $robot
     * @return array
     */
    public function testInputAndExecute(array $inputData, array $expectedResult, CleaningRobot $robot)
    {
        $this->assertEquals(true, $robot->input($inputData));

        $result = $robot->execute();
        $this->assertEqualsArrayAsSet($expectedResult['visited'], $result['visited']);
        $this->assertEqualsArrayAsSet($expectedResult['cleaned'], $result['cleaned']);
        $this->assertEquals($expectedResult['final'], $result['final']);
        $this->assertEquals($expectedResult['battery'], $result['battery']);
    }

    public function testTurnning()
    {
        $turnLeft = function ($facing) {
            $directions = ['N', 'E', 'S', 'W'];
            $index = array_search($facing, $directions);
            $index = ($index - 1 + 4) % 4;
            return $directions[$index];
        };

        $turnRight = function ($facing) {
            $directions = ['N', 'E', 'S', 'W'];
            $index = array_search($facing, $directions);
            $index = ($index + 1) % 4;
            return $directions[$index];
        };

        $this->assertEquals('W', $turnLeft('N'));
        $this->assertEquals('N', $turnLeft('E'));
        $this->assertEquals('E', $turnLeft('S'));
        $this->assertEquals('S', $turnLeft('W'));

        $this->assertEquals('E', $turnRight('N'));
        $this->assertEquals('S', $turnRight('E'));
        $this->assertEquals('W', $turnRight('S'));
        $this->assertEquals('N', $turnRight('W'));
    }
}
