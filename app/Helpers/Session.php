<?php

namespace App\Helpers;

class Session
{
    /**
     * Starts a new session on the server
     *
     * @return void
     */
    public function start(): void
    {
        session_start();
    }

    /**
     * Destroys existing session on the server
     *
     * @return void
     */
    public function destroy(): void
    {
        session_destroy();
    }

    /**
     * Checks if session exist for a specified key
     *
     * @param string $name
     * @return void
     */
    public function exists(string $name): void
    {
        sessionExists($name);
    }

    /**
     * Creates a session for a single request
     *
     * @param string $name
     * @param string $message
     * @return void
     */
    public function flash(string $name = "", string $message = ""): void
    {
        session($name, $message);
    }
}
