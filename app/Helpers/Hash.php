<?php

namespace App\Helpers;

use Exception;

class Hash
{
    /**
     * Encrypts a value
     *
     * @param string $pure_string
     * @return string
     */
    public static function encrypt($pureString): string
    {
        $encryptionKey = env('APP_KEY');
        if (!$encryptionKey) throw new Exception("APP_KEY must be set.");
        $cipher = 'AES-256-CBC';
        $options = OPENSSL_RAW_DATA;
        $hashAlgo = 'sha256';
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertextRaw = openssl_encrypt($pureString, $cipher, $encryptionKey, $options, $iv);
        $hmac = hash_hmac($hashAlgo, $ciphertextRaw, $encryptionKey, true);
        return $iv . $hmac . $ciphertextRaw;
    }

    /**
     * Dencrypts a value
     *
     * @param string $pure_string
     * @return string
     */
    public static function decrypt($encryptedString): string
    {
        $encryptionKey = env('APP_KEY');
        if (!$encryptionKey) throw new Exception("APP_KEY must be set.");
        $cipher = 'AES-256-CBC';
        $options = OPENSSL_RAW_DATA;
        $hashAlgo = 'sha256';
        $sha2len = 32;
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($encryptedString, 0, $ivlen);
        $hmac = substr($encryptedString, $ivlen, $sha2len);
        $ciphertextRaw = substr($encryptedString, $ivlen + $sha2len);
        $originalPlaintext = openssl_decrypt($ciphertextRaw, $cipher, $encryptionKey, $options, $iv);
        $calcmac = hash_hmac($hashAlgo, $ciphertextRaw, $encryptionKey, true);
        if (function_exists('hash_equals')) {
            if (hash_equals($hmac, $calcmac)) return $originalPlaintext;
        } else {
            if (static::hash_equals_custom($hmac, $calcmac)) return $originalPlaintext;
        }
    }

    /**
     * Intermediate function used in encryption proccess
     *
     * @param string $knownString
     * @param string $userString
     * @return bool
     */
    private static function hash_equals_custom($knownString, $userString): bool
    {
        if (function_exists('mb_strlen')) {
            $kLen = mb_strlen($knownString, '8bit');
            $uLen = mb_strlen($userString, '8bit');
        } else {
            $kLen = strlen($knownString);
            $uLen = strlen($userString);
        }
        if ($kLen !== $uLen) {
            return false;
        }
        $result = 0;
        for ($i = 0; $i < $kLen; $i++) {
            $result |= (ord($knownString[$i]) ^ ord($userString[$i]));
        }
        return 0 === $result;
    }
}
