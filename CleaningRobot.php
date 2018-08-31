<?php
/**
 * Created by PhpStorm.
 * User: kcb01
 * Date: 8/31/2018
 * Time: 5:41 PM
 */

class CleaningRobot
{
    //////////////////////////////// public methods ////////////////////////////////

    /**
     * CleaningRobot constructor.
     */
    public function __construct()
    {
    }

    /**
     * input data from array
     * @param array $data
     * @return bool: true if succeeded, false if failed
     */
    public function input(array $data)
    {
        // input map data from array
        if (array_key_exists('map', $data)) {
            $this->map = $data['map'];
        } else {
            return false;
        }

        // input beginning position and direction
        if (array_key_exists('start', $data)) {
            $start = $data['start'];

            if (array_key_exists('X', $start)) {
                $this->currentX = $start['X'];
            } else {
                return false;
            }

            if (array_key_exists('Y', $start)) {
                $this->currentY = $start['Y'];
            } else {
                return false;
            }

            if (array_key_exists('facing', $start)) {
                $this->currentFacing = $start['facing'];
            } else {
                return false;
            }
        } else {
            return false;
        } // end of beginning position and direction

        // input commands given to this robot
        if (array_key_exists('commands', $data)) {
            $this->remainingCommands = $data['commands'];
        } else {
            return false;
        }

        // input initial battery
        if (array_key_exists('battery', $data)) {
            $this->battery = $data['battery'];
        } else {
            return false;
        }

        return true;
    }

    /**
     * execute commands and return result.
     * @return array
     */
    public function execute()
    {
        // TODO: execute commands and return result.
        return [];
    }

    ///////////////////////////////// private methods //////////////////////////////

    private function turnLeft()
    {
        $index = array_search($this->currentFacing, $this->directions);
        $index = ($index - 1 + 4) % 4;
        $this->currentFacing = $this->directions[$index];
    }

    private function turnRight()
    {
        $index = array_search($this->currentFacing, $this->directions);
        $index = ($index + 1) % 4;
        $this->currentFacing = $this->directions[$index];
    }

    ///////////////////////////////////// constants ////////////////////////////////

    private $directions = ['N', 'E', 'S', 'W'];

    //////////////////////////////////// properties ////////////////////////////////

    /**
     * @var array
     */
    private $map;

    /**
     * @var int
     */
    private $currentX;

    /**
     * @var int
     */
    private $currentY;

    /**
     * @var string
     */
    private $currentFacing;

    /**
     * @var int
     */
    private $battery;

    /**
     * @var array
     */
    private $remainingCommands;
}