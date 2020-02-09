<?php

namespace common\tests\unit\models;

use common\models\Apple;

/**
 * Login form test
 */
class AppleTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function testCommonTest()
    {
        $apple = new Apple('green');
        $apple->save();
        self::assertEquals('green', $apple->color);


        $this->expectExceptionMessage('Apple on tree. Can`t be eated');
        $apple->eat(50);

        self::assertEquals(1.0, $apple->size);

        $apple->fallToGround(); // упасть на землю
        $apple->eat(25); // откусить четверть яблока
        self::assertEquals(0.75, $apple->size);
        $apple->delete();


    }


}
