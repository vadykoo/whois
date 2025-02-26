<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhoisServer extends Model
{
    protected $fillable = ['domain', 'whois_server'];

    //find by domain
    public static function findByDomain($domain)
    {
        return self::where('domain', $domain)->first();
    }
}
