<?php
/**
 * Created by PhpStorm.
 * User: wujunze
 * Date: 2018/9/14
 * Time: 下午1:39
 */

namespace WuJunze\IdGen\Tests;


use Wujunze\IdGen\IdGen;
use Wujunze\IdGen\IdGenServiceProvider;
use Validator;

class IdGenServiceProviderTest extends TestCase
{

    public function testBoot ()
    {
        $app = app();
        $provider = new IdGenServiceProvider($app);
        $this->assertInstanceOf(IdGenServiceProvider::class, $provider);
    }

}