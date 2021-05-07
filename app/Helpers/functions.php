<?php

/**
 * Session flush message helper 
 *
 * @param string $name
 * @param string $message
 * @return mixed
 */
function session($name = "", $message = ""): mixed
{
    if (!empty($name) && !empty($message)) {
        $_SESSION[$name] = $message;
        return null;
    } elseif (empty($message) && !empty($name)) {
        $value = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $value;
    }
}

/**
 * Checks if session of particular key exist
 *
 * @param string $key
 * @return bool
 */
function session_exists($key): bool
{
    return isset($_SESSION[$key]);
}

/**
 * Gets the value of an environment variable.
 *
 * @param  string  $key
 * @return mixed
 */
function env($key): mixed
{
    $data = file_get_contents(ENV);

    $data = explode("\n", $data);

    $data = array_filter($data, function ($entry) use ($key) {
        $str =  strstr($entry, '=', true);
        return $str == $key ? true : false;
    });

    if (count($data) == 0) return null;

    $data = implode('', $data);
    $str = strstr($data, '=');
    $str = trim(substr($str, 1));

    return $str;
}
