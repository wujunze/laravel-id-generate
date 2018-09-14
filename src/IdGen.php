<?php

namespace Wujunze\IdGen;

/**
 * Class IdGen
 * @package Wujunze\IdGen
 */
class IdGen extends Uuid
{
    const COUPON_BASE_TS = 1532059932; //base time 2018-07-20 12:12:12


    /**
     * @return int
     */
    public static function getSamplePk()
    {
        //1514764800000 2018-01-01 00:00:00
        return ((get_current_ms() - 1514764800000) << 8) | mt_rand(0, 255);
    }

    /**
     *
     * @param int $type type max 15
     * @param int $shareKey shareKey
     *
     * @return int
     *
     * @throws \Exception
     */
    public static function genIdByTypeShareKey($type, $shareKey)
    {
        if ($type > 15) {
            throw new \InvalidArgumentException('invalid type');
        }

        return (((((time() << 4) | $type) << 8) | (crc32($shareKey) % 256)) << 10) | mt_rand(0, 1023);
    }

    /**
     *
     * @param int $type max support 16 types
     *
     * @return int
     *
     * @throws \Exception
     */
    public static function genIdByType(int $type): int
    {

        if ($type < 0 || $type > 15) {
            throw new \InvalidArgumentException('invalid type');
        }

        return ((((time() - self::COUPON_BASE_TS) << 4) | $type) << 3) | mt_rand(0, 7);
    }


    /**
     * gen code
     *
     * @param int $type type max support 16 types
     * @param int $source source max support 16 types
     * @param int $sequence  sequence max support 16383 types
     *
     * @return int
     *
     * @throws \Exception
     */
    public static function genCode(int $type, int $source, int $sequence): int
    {
        if ($type < 0 || $type > 15) {
            throw new \InvalidArgumentException('invalid type');
        }

        if ($source < 0 || $source > 15) {
            throw new \InvalidArgumentException('invalid source');
        }

        if ($sequence < 0 || $sequence > 16383) {
            throw new \InvalidArgumentException('invalid sequence');
        }

        return ((((((get_current_ms() - (self::COUPON_BASE_TS * 1000)) << 4) | $type) << 4) | $source) << 14) | $sequence;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function IdValidate($id)
    {
        return is_numeric($id);
    }
}
