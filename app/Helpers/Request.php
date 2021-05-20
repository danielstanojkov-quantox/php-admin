<?php

namespace App\Helpers;

class Request
{
    /**
     *  Check if the request is POST
     *
     * @return bool
     */
    public static function isPost(): bool
    {
        return Server::method() === 'POST';
    }

    /**
     *  Check if the request is GET
     *
     * @return bool
     */
    public static function isGet(): bool
    {
        return Server::method() === 'GET';
    }

    /**
     * Get data from the request
     *
     * @return mixed
     */
    public static function all(): mixed
    {
        switch (Server::method()) {
            case 'POST':
                return filter_input_array(INPUT_POST);
            case 'GET':
                return filter_input_array(INPUT_GET);

            default:
                return [];
        }
    }

    /**
     * Return specified field from the request
     *
     * @param string $field
     * @return mixed
     */
    public static function input(string $field): mixed
    {
        return static::all()[$field] ?? null;
    }

    /**
     *  Check if the request has ceratin parameter
     *
     * @return bool
     */
    public static function has($parameter): bool
    {
        return key_exists($parameter, static::all() ?? []);
    }

    /**
     * Return uploaded file
     *
     * @param string $filename
     * @return array
     */
    public static function file(string $filename): array
    {
        return $_FILES[$filename];
    }

    /**
     * Check if file is being uploaded
     *
     * @param string $filename
     * @return bool
     */
    public static function fileExists(string $filename): bool
    {
        return isset($_FILES[$filename]);
    }
}
