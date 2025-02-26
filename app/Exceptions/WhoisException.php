<?php

namespace App\Exceptions;

use Exception;

class WhoisException extends Exception
{
    public static function invalidResponse(string $response): self
    {
        return new self("Invalid WHOIS response received: " . $response);
    }

    public static function serverNotFound(string $tld): self
    {
        return new self("No WHOIS server found for TLD: " . $tld);
    }
}
