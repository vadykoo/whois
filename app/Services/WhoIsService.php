<?php

namespace App\Services;

use App\Models\WhoisServer;
use App\Exceptions\WhoisException;

class WhoIsService
{
    private WhoisServer $whoIsServer;

    public function __construct()
    {
        $this->whoIsServer = new WhoisServer();
    }

    public function lookup(string $url): string
    {
        try {
            // Get top-level domain tld
            $cleanDomain = $this->getClenDomain($url);
            $tldArray = explode('.', $cleanDomain);
            $tld = end($tldArray);
            $findServerWhoIs = $this->findServerWhoIs($tld);
            $whoIsInfo = $this->getWhoIsInfo($findServerWhoIs, $cleanDomain);

            return $whoIsInfo;
        } catch (WhoisException $e) {
            throw new WhoisException($e->getMessage());
        }
    }

    private function getClenDomain(string $url): string
    {
        // Sanitize and normalize the URL
        $url = trim(strtolower($url));
        // Remove protocol and www if present
        $url = preg_replace('#^(https?://)?(www\.)?#i', '', $url);
        // Remove query string and path
        $url = preg_replace('#[?/].*$#', '', $url);

        return $url;
    }

    private function findServerWhoIs(string $tld): string
    {
        $server = $this->whoIsServer->findByDomain($tld);
        if (!$server) {
            throw new WhoisException(WhoisException::UNSUPPORTED_DOMAIN);
        }
        return $server->whois_server;
    }

    private function getWhoIsInfo(string $server, string $url): string
    {
        $domain = parse_url($url)['path'];
        $port = 43;
        $timeout = 10;

        $sock = fsockopen($server, $port, $errno, $errstr, $timeout);
        if (!$sock) {
            throw new WhoisException(sprintf(WhoisException::CONNECTION_FAILED, $errstr));
        }

        fwrite($sock, $domain . "\r\n");
        $response = '';

        while (!feof($sock)) {
            $response .= fgets($sock, 128);
        }

        fclose($sock);

        if (empty($response)) {
            throw new WhoisException(WhoisException::NO_RESPONSE);
        }

        return $response;
    }
}
