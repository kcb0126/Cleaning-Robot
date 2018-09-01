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
        $this->visited = [['X' => $this->currentX, 'Y' => $this->currentY]];
        $this->cleaned = [];

        while (count($this->remainingCommands) > 0) {
            $command = array_shift($this->remainingCommands);

            switch ($command) {
                case 'TL':
                    $result = $this->turnLeft();
                    if(!$result) break 2;
                    break;

                case 'TR':
                    $result = $this->turnRight();
                    if(!$result) break 2;
                    break;

                case 'A':
                    $result = $this->advance();
                    if (!$result) {
                        $result = $this->backOff();
                        if(!$result) {
                            break 2;
                        }
                    }
                    break;

                case 'C':
                    $result = $this->clean();
                    if(!$result) break 2;
                    break;

                default:
                    // do nothing
            }
        }

        return [
            'visited' => $this->visited,
            'cleaned' => $this->cleaned,
            'final' => ['X' => $this->currentX, 'Y' => $this->currentY, 'facing' => $this->currentFacing],
            'battery' => $this->battery
        ];
    }

    ///////////////////////////////// private methods //////////////////////////////

    private function turnLeft()
    {
        if($this->battery < 1) return false;
        $this->battery--;
        $index = array_search($this->currentFacing, $this->directions);
        $index = ($index - 1 + 4) % 4;
        $this->currentFacing = $this->directions[$index];
        return true;
    }

    private function turnRight()
    {
        if($this->battery < 1) return false;
        $this->battery--;
        $index = array_search($this->currentFacing, $this->directions);
        $index = ($index + 1) % 4;
        $this->currentFacing = $this->directions[$index];
        return true;
    }

    private function clean()
    {
        if($this->battery < 5) return false;
        $this->battery -= 5;
        $found = false;
        foreach ($this->cleaned as $item) {
            if($item['X'] === $this->currentX && $item['Y'] === $this->currentY) {
                $found = true;
                break;
            }
        }

        if (!$found) $this->cleaned[] = ['X' => $this->currentX, 'Y' => $this->currentY];
        return true;
    }

    /**
     * @return bool: false if it hits an obstacle
     */
    private function advance()
    {
        if($this->battery < 2) return false;
        $this->battery -= 2;
        $nextX = $this->currentX;
        $nextY = $this->currentY;
        switch ($this->currentFacing) {
            case 'N':
                $nextY--;
                break;

            case 'E':
                $nextX++;
                break;

            case 'S':
                $nextY++;
                break;

            case 'W':
                $nextX--;
                break;

            default:
                // do nothing
        }

        if($nextY >= 0 && $nextY < count($this->map) && $nextX >= 0
            && $nextX < count($this->map[$nextY])
            && $this->map[$nextY][$nextX] === 'S')
        {
            $this->currentX = $nextX;
            $this->currentY = $nextY;

            $found = false;
            foreach ($this->visited as $item) {
                if($item['X'] === $this->currentX && $item['Y'] === $this->currentY) {
                    $found = true;
                    break;
                }
            }
            if (!$found) $this->visited[] = ['X' => $this->currentX, 'Y' => $this->currentY];

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool: false if it hits an obstacle
     */
    private function back()
    {
        if($this->battery < 3) return false;
        $this->battery -= 3;
        $nextX = $this->currentX;
        $nextY = $this->currentY;
        switch ($this->currentFacing) {
            case 'N':
                $nextY++;
                break;

            case 'E':
                $nextX--;
                break;

            case 'S':
                $nextY--;
                break;

            case 'W':
                $nextX++;
                break;

            default:
                // do nothing
        }

        if($nextY >= 0 && $nextY < count($this->map) && $nextX >= 0
            && $nextX < count($this->map[$nextY])
            && $this->map[$nextY][$nextX] === 'S')
        {
            $this->currentX = $nextX;
            $this->currentY = $nextY;

            $found = false;
            foreach ($this->visited as $item) {
                if($item['X'] === $this->currentX && $item['Y'] === $this->currentY) {
                    $found = true;
                    break;
                }
            }
            if (!$found) $this->visited[] = ['X' => $this->currentX, 'Y' => $this->currentY];

            return true;
        } else {
            return false;
        }
    }

    private function backOff()
    {
first:  // 1. Turn right, then advance.
        $this->turnRight();
        $result = $this->advance();
        if($result) return true;

second: // 2. If that also hits an obstacle: Turn Left, Back, Turn Right, Advance
        $this->turnLeft();
        $result = $this->back();
        if (!$result) goto third;
        $this->turnRight();
        $result = $this->advance();
        if($result) return true;

third:  // 3. If that also hits an obstacle: Turn Left, Turn Left, Advance
        $this->turnLeft();
        $this->turnLeft();
        $result = $this->advance();
        if($result) return true;

forth:  // 4. If that also hits and obstacle: Turn Right, Back, Turn Right, Advance
        $this->turnRight();
        $result = $this->back();
        if (!$result) goto fifth;
        $this->turnRight();
        $result = $this->advance();
        if($result) return true;

fifth:  // 5. If that also hits and obstacle: Turn Left, Turn Left, Advance
        $this->turnLeft();
        $this->turnLeft();
        $result = $this->advance();
        if($result) return true;

        return false;
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

    /**
     * @var array
     */
    private $cleaned = [];

    /**
     * @var array
     */
    private $visited = [];
}