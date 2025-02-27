<?php

namespace App\Exceptions;

use Exception;

class WhoisException extends Exception
{
    public const UNSUPPORTED_DOMAIN = "Sorry, we do not support this domain yet";
    public const CONNECTION_FAILED = "Connection failed: %s";
    public const NO_RESPONSE = "No response received from WHOIS server";
}
