<?php

namespace Wujunze\IdGen;

class Guid
{
    const EPOC_OFFSET = 1542643200; // Change to time() of project start.
    const MAX_SEQUENCE_NUM = 63;

    private static $generator = null;
    private $lastTimestamp = 0;
    private $sequence = 0;

    private function __construct()
    {
        // Only allow instantiation via singleton pattern (getGenerator()).
    }

    public function generate($machine_id)
    {
        // Get custom timestamp.
        $time = $this->generateTimestamp();

        // Reset sequence counter if timstamp is different from last used.
        if ($this->lastTimestamp !== $time) {
            $this->sequence = 0;
        }

        // If the same timestamp has been used MAX_SEQUENCE_NUM times, go to
        // sleep for 1ms, then generate new timestamp.
        if ($this->sequence === self::MAX_SEQUENCE_NUM + 1) {
            usleep(1000);
            $this->sequence = 0;
            $time = $this->generateTimestamp();
        }

        // Remember this timestamp.
        $this->lastTimestamp = $time;

        // Machine ID
        $mid = ((63 & $machine_id) << 16);

        // Process ID
        $pid = ((1023 & getmypid()) << 6);

        // Sequence.
        $seq = (63 & $this->sequence);

        $this->sequence++;

        return $time | $mid | $pid | $seq;
    }

    /**
     * Generates a custom EPOC timestamp positioned for merging with ID.
     *
     * @return int Internal timestamp.
     */
    private function generateTimestamp()
    {
        $microtime = explode(' ', microtime());
        $microtime[1] = (int)$microtime[1] - self::EPOC_OFFSET;
        $time = $microtime[1].substr($microtime[0], 2, 3);

        return ((0x1FFFFFFFFFF & $time) << 22);
    }

    /**
     * Singleton pattern to make sure only one generator is used in the
     * current PHP process.
     *
     * @return Guid
     */
    public static function getGenerator()
    {
        if (is_null(self::$generator)) {
            self::$generator = new Guid();
        }

        return self::$generator;
    }

    /**
     * Takes a generated ID and returns the Unix timestamp.
     *
     * @param int $id Generated ID.
     * @return int Unix timestamp.
     */
    public function getUnixTimestamp($id)
    {
        $time = ($id >> 22);
        $time = (int)substr($time, 0, strlen($time) - 3);

        return (int)$time + self::EPOC_OFFSET;
    }

    /**
     * Takes a generated ID and returns the equivalence of PHP microtime().
     *
     * @param int $id Generated ID.
     * @return string Microtime in millisecond resolution.
     */
    public function getMicrotime($id)
    {
        $time = ($id >> 22);
        $microtime = substr($time, strlen($time) - 3);

        return "0.{$microtime}00000 ".$this->getUnixTimestamp($id);
    }
}