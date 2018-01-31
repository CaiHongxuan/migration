<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 16:12
 */

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;

class DB
{

    private static $_capsule;

    private static $config = [
       'driver'    => 'mysql',
       'host'      => 'localhost',
       'database'  => 'your database',
       'username'  => 'username',
       'password'  => 'password',
       'charset'   => 'utf8',
       'collation' => 'utf8_unicode_ci',
       'prefix'    => '',
    ];


    public static function init()
    {
        if (!self::$_capsule instanceof Capsule) {
            self::$_capsule = new Capsule();
            self::$_capsule->addConnection(self::$config);
            self::$_capsule->setAsGlobal(); // 通过静态方法使这个Capsule实例全局可用
            self::$_capsule->bootEloquent();
        }

        return self::$_capsule;
    }
}
