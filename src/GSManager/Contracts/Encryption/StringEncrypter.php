<?php

namespace GSManager\Contracts\Encryption;

interface StringEncrypter
{
    /**
     * Encrypt a string without serialization.
     *
     * @param  string  $value
     * @return string
     *
     * @throws \GSManager\Contracts\Encryption\EncryptException
     */
    public function encryptString(#[\SensitiveParameter] $value);

    /**
     * Decrypt the given string without unserialization.
     *
     * @param  string  $payload
     * @return string
     *
     * @throws \GSManager\Contracts\Encryption\DecryptException
     */
    public function decryptString($payload);
}
