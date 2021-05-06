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

function session_exists($key)
{
    return isset($_SESSION[$key]) ? true : false;
}

/**
 * Gets the value of an environment variable.
 *
 * @param  string  $key
 * @return string
 */
function env($key)
{
    $data = file_get_contents(ENV);

    $data = explode("\n", $data);

    $data = array_filter($data, function ($entry) use ($key) {
        $str =  strstr($entry, '=', true);
        return $str == $key ? true : false;
    });

    if (count($data) == 0) return;

    $data = implode('', $data);
    $str = strstr($data, '=');
    $str = trim(substr($str, 1));

    return $str;
}
