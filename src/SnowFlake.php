<?php

namespace Wujunze\IdGen;

/**
 *
 * SnowFlake算法PHP实现
 *
 * ID 生成策略
 * 毫秒级时间41位+机器ID 10位+毫秒内序列12位。
 * 0           41     51     64
 *  +-----------+------+------+
 *  |time       |pc    |inc   |
 *  +-----------+------+------+
 *
 *  前41bits是以微秒为单位的timestamp。
 *
 *  接着10bits是事先配置好的机器ID。
 *
 *  最后12bits是累加计数器。
 *
 *  macheine id(10bits)标明最多只能有1024台机器同时产生ID，
 * sequence number(12bits)也标明1台机器1ms中最多产生4096个ID
 */
class SnowFlake
{

    /**
     * @var int
     */
    const DEBUG = 1;

    /**
     * @var int
     */
    const WORKER_ID_BITS = 4;

    /**
     * @var int
     */
    const SEQUENCE_BITS = 10;


    /**
     * @var int
     */
    public static $workerId;

    /**
     * @var int
     */
    public static $epoch = 1361775855078;

    /**
     * @var int
     */
    public static $sequence = 0;

    /**
     * @var int
     */
    public static $maxWorkerId = 15;

    /**
     * @var int
     */
    public static $workerIdShift = 10;

    /**
     * @var int
     */
    public static $timestampLeftShift = 14;

    /**
     * @var int
     */
    public static $sequenceMask = 1023;


    /**
     * @var int
     */
    private static $lastTimestamp = -1;

    /**
     * SnowFlake constructor.
     * @param int $workId
     */
    public function __construct($workId = 1)
    {
        if ($workId > self::$maxWorkerId || $workId < 0) {
            throw new \LogicException("worker Id can't be greater than 15 or less than 0");
        }
        self::$workerId = $workId;
    }

    /**
     * @return string
     */
    public function timeGen()
    {
        //获得当前时间戳
        $time = explode(' ', microtime());
        $time2 = substr($time[0], 2, 3);

        return $time[1].$time2;
    }

    /**
     * @param $lastTimestamp
     * @return string
     */
    public function tilNextMillis($lastTimestamp)
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }

        return $timestamp;
    }

    /**
     * @return int
     */
    public function nextId()
    {
        $timestamp = $this->timeGen();
        if (self::$lastTimestamp == $timestamp) {
            self::$sequence = (self::$sequence + 1) & self::$sequenceMask;
            if (self::$sequence == 0) {
                $timestamp = $this->tilNextMillis(self::$lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }
        if ($timestamp < self::$lastTimestamp) {
            throw new \LogicException("Clock moved backwards. Refusing to generate id for ".(self::$lastTimestamp - $timestamp)." milliseconds");
        }
        self::$lastTimestamp = $timestamp;

        return ((sprintf('%.0f', $timestamp) - sprintf('%.0f', self::$epoch)) << self::$timestampLeftShift) | (self::$workerId << self::$workerIdShift) | self::$sequence;

    }

}