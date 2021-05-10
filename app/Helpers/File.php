<?php

namespace App\Helpers;

class File
{
    /**
     * Get contents of a file
     *
     * @param string $filename
     * @return string
     */
    public static function get($filename): string
    {
        return file_get_contents($filename);
    }

    /**
     * Put contents in a file
     *
     * @param string $filename
     * @param string $data
     * @return bool
     */
    public static function put($filename, $data): bool
    {
        return file_put_contents($filename, $data);
    }

    /**
     * Check if file exists on server
     *
     * @param string $filename
     * @return bool
     */
    public static function exists($filename): bool
    {
        return file_exists($filename);
    }

    /**
     * Makes directory on server
     *
     * @param string $dirname
     * @return void
     */
    public static function makeDirectory($dirname): void
    {
        mkdir(APPROOT . "/$dirname");
    }

    /**
     * Makes file on server
     *
     * @param string $directory
     * @param string $filename
     * @param string $content
     * @return void
     */
    public static function makeFile($filename, $content): void
    {
        $file = fopen(APPROOT . "/$filename", "w");
        fwrite($file, $content);
        fclose($file);
    }
}
