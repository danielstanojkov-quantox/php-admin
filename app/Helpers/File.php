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
    public static function get(string $filename): string
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
    public static function put(string $filename, string $data): bool
    {
        return file_put_contents($filename, $data);
    }

    /**
     * Check if file exists on server
     *
     * @param string $filename
     * @return bool
     */
    public static function exists(string $filename): bool
    {
        return file_exists($filename);
    }

    /**
     * Makes directory on server
     *
     * @param string $dirname
     * @return void
     */
    public static function makeDirectory(string $dirname): void
    {
        mkdir(app('APP_ROOT') . "/$dirname");
    }

    /**
     * Makes file on server
     *
     * @param string $filename
     * @param string $content
     * @return void
     */
    public static function makeFile(string $filename, string $content): void
    {
        $file = fopen(app('APP_ROOT') . "/$filename", "w");
        fwrite($file, $content);
        fclose($file);
    }
}
