<?php

/**
 * Session flush message helper 
 *
 * @param string $name
 * @param string $message
 * @return mixed
 */
function session(string $name = "", string $message = ""): mixed
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
function sessionExists(string $key): bool
{
    return isset($_SESSION[$key]);
}

/**
 * Gets the value of an environment variable.
 *
 * @param  string  $key
 * @return mixed
 */
function env(string $key): mixed
{
    $data = file_get_contents(
        dirname(__DIR__, 2) . '/.env'
    );

    $data = explode("\n", $data);

    $data = array_filter($data, function ($entry) use ($key) {
        $string =  strstr($entry, '=', true);
        return $string === $key;
    });

    if (count($data) == 0) return null;

    $data = implode('', $data);
    $string = strstr($data, '=');
    $string = trim(substr($string, 1));

    return $string;
}

/**
 * Get full url
 *
 * @return string
 */
function fullUrl()
{
    $domain = $_SERVER['HTTP_HOST'];

    return "http://" . $domain . $_SERVER['REQUEST_URI'];
}

/**
 * Retrieve configuration variable
 *
 * @param string $key
 * @return string
 */
function app(string $key): string
{
    $configurations = require(dirname(__DIR__, 2) . '/config/app.php');
    return $configurations[$key];
}

/**
 * Return value from the request
 *
 * @param string $key
 * @return mixed
 */
function request(string $key): mixed
{
    return $_REQUEST[$key] ?? null;
}
