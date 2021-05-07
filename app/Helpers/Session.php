<?php

namespace App\Helpers;

class Session
{
    /**
     * Starts a new session on the server
     *
     * @return void
     */
    public static function start(): void
    {
        session_start();
    }

    /**
     * Destroys existing session on the server
     *
     * @return void
     */
    public static function destroy(): void
    {
        session_destroy();
    }

    /**
     * Checks if session exist for a specified key
     *
     * @param string $name
     * @return void
     */
    public static function exists($name): void
    {
        session_exists($name);
    }

    /**
     * Creates a session for a single request
     *
     * @param string $name
     * @param string $message
     * @return void
     */
    public static function flash($name = "", $message = ""): void
    {
        session($name, $message);
    }
}
