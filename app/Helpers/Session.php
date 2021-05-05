<?php

namespace App\Helpers;

class Session
{
    public static function start()
    {
        session_start();
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function exists($name)
    {
        session_exists($name);
    }

    public static function flash($name = "", $message = "")
    {
      session($name, $message);
    }
}
