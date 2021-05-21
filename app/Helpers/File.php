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
    public function get(string $filename): string
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
    public function put(string $filename, string $data): bool
    {
        return file_put_contents($filename, $data);
    }

    /**
     * Check if file exists on server
     *
     * @param string $filename
     * @return bool
     */
    public function exists(string $filename): bool
    {
        return file_exists($filename);
    }

    /**
     * Makes directory on server
     *
     * @param string $dirname
     * @return void
     */
    public function makeDirectory(string $dirname): void
    {
        mkdir(app('app_root') . "/$dirname");
    }

    /**
     * Makes file on server
     *
     * @param string $filename
     * @param string $content
     * @return void
     */
    public function makeFile(string $filename, string $content): void
    {
        $file = fopen(app('app_root') . "/$filename", "w");
        fwrite($file, $content);
        fclose($file);
    }
}
