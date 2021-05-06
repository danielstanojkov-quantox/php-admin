<?php

namespace App\Helpers;

class File
{
    public static function get($filename)
    {
        return file_get_contents($filename);
    }

    public static function put($filename, $data)
    {
        return file_put_contents($filename, $data);
    }

    public static function exists($filename)
    {
        return file_exists($filename);
    }

    public static function makeDirectory($dirname)
    {
        mkdir(APPROOT . "/$dirname");
    } 

    public static function makeFile($directory, $filename, $content)
    {
        $file = fopen(APPROOT . "/$directory/$filename", "w");
        fwrite($file, $content);
        fclose($file);
    }

    public static function createStorageFolder()
    {
        static::makeDirectory('storage');
        static::makeFile('storage', 'users.json', '');
    }
}
