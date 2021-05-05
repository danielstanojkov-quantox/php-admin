<?php

function session($name = "", $message = "")
{
    if (!empty($name) && !empty($message)) {
        $_SESSION[$name] = $message;
    } elseif (empty($message) && !empty($name)) {
        $value = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $value;
    }
}

function session_exists($name)
{
    return isset($_SESSION[$name]) ? true : false;
}
