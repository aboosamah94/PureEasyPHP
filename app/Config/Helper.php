<?php

namespace Config;

class Helper
{
    protected static $loadedHelpers = [];

    public static function load($helper)
    {
        if (!isset(self::$loadedHelpers[$helper])) {
            $helperPath = Paths::getHelperFolderPath() . '/' . $helper . '.php';

            if (file_exists($helperPath)) {
                require_once $helperPath;
                self::$loadedHelpers[$helper] = true;
            } else {
                throw new \Exception('Helper file not found: ' . $helperPath);
            }
        }
    }
}
