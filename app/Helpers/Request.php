<?php

namespace App\Helpers;

class Request
{
    /**
     *
     * @var Server $server
     */
    private $server;

    /**
     * Request Constructor
     *
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     *  Check if the request is POST
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->server->method() === 'POST';
    }

    /**
     *  Check if the request is GET
     *
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->server->method() === 'GET';
    }

    /**
     * Get data from the request
     *
     * @return mixed
     */
    public function all(): mixed
    {
        switch ($this->server->method()) {
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
    public function input(string $field): mixed
    {
        return $this->all()[$field] ?? null;
    }

    /**
     *  Check if the request has ceratin parameter
     *
     * @return bool
     */
    public function has($parameter): bool
    {
        return key_exists($parameter, $this->all() ?? []);
    }

    /**
     * Return uploaded file
     *
     * @param string $filename
     * @return array
     */
    public function file(string $filename): array
    {
        return $_FILES[$filename];
    }

    /**
     * Check if file is being uploaded
     *
     * @param string $filename
     * @return bool
     */
    public function fileExists(string $filename): bool
    {
        return isset($_FILES[$filename]);
    }
}
