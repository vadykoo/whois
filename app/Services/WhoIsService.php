<?php

namespace App\Services;

use App\Models\WhoisServer;

class WhoIsService
{
    private WhoisServer $whoIsServer;

    public function __construct()
    {
        $this->whoIsServer = new WhoisServer();
    }

    public function lookup(string $url): string
    {
        //get top-level domain
        $tld = $this->getTopLevelDomain($url);
        $findServerWhoIs = $this->findServerWhoIs($tld);
        $whoIsInfo = $this->getWhoIsInfo($findServerWhoIs, $url);
        return $whoIsInfo;
    }

    private function getTopLevelDomain(string $url)
    {
        //remove all / and spaces
        $url = preg_replace('/\s+/', '', $url);
        $domainArray = explode(".", parse_url($url)['path']);
        return end($domainArray);
    }

    private function findServerWhoIs(string $tld): string
    {
        $server = $this->whoIsServer->findByDomain($tld);
        if ($server) {
            return $server->whois_server;
        }

        return "Sorry, we do not have info about this domain";
    }

    private function getWhoIsInfo(string $server, string $url): string
    {
        $domain = parse_url($url)['path'];
        $port = 43;
        $timeout = 10;

        $sock = fsockopen($server, $port, $errno, $errstr, $timeout);
        if (!$sock) {
            return "Error: $errno - $errstr";
        }

        fwrite($sock, $domain . "\r\n");
        $response = '';

        while (!feof($sock)) {
            $response .= fgets($sock, 128);
        }

        fclose($sock);

        return $response;
    }
}
