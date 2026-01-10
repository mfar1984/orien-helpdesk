<?php

namespace App\Services;

class HashLinkService
{
    /**
     * Generate a secure hash link for URL identification.
     * 
     * @return string 64-character hexadecimal string
     */
    public static function generate(): string
    {
        return bin2hex(random_bytes(32));
    }
}
