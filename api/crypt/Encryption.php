<?php

class Encryption
{
    // Cipher type
    private $cipher = 'AES-256-CBC';
    private $key;

    // You should change this, or use a more secure encryption handler.
    public function __construct($key = 'a9dnUachH3wjcDHetGwC0jvMm0VbF8ilvww5lSndjv2M1gYe3Ll95qZS40oT') {
        $this->key = $key;
    }

    // Encrypts whatever is passed through to $string.
    public function encrypt($string) {
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($string, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        $encrypted = base64_encode($iv . $encrypted);
        return $encrypted;
    }

    // Decrypts whatever is passed through to $string, as long as it's an encrypted string.
    public function decrypt($encryptedString) {
        $encryptedString = base64_decode($encryptedString);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($encryptedString, 0, $ivLength);
        $encrypted = substr($encryptedString, $ivLength);
        $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

}