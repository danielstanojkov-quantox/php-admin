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
     * @return array
     */
    public static function all(): array
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
    public static function input($field): mixed
    {
        return static::all()[$field] ?? null;
    }
}
