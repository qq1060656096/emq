<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 22:00
 */

namespace Zwei\Emq\Helper;


class Helper
{
    /**
     * @param $var
     * @return false|string
     */
    public static function varDump($var){
        ob_start();
        var_dump($var);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
}
