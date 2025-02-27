<?php

namespace App\Services;

use App\Models\WhoisServer;
use App\Exceptions\WhoisException;

class WhoIsService
{
    /**
     * @var WhoisServer
     */
    private WhoisServer $whoIsServer;

    /**
     * WhoIsService constructor.
     */
    public function __construct()
    {
        $this->whoIsServer = new WhoisServer();
    }
    /**
     * Run a whois lookup on a domain
     * @param string $cleanDomain
     * @return string
     * @throws WhoisException
     */
    public function lookup(string $cleanDomain): string
    {
        try {
            // Get top-level domain tld
            $tldArray = explode('.', $cleanDomain);
            $tld = end($tldArray);
            $findServerWhoIs = $this->findServerWhoIs($tld);
            $whoIsInfo = $this->getWhoIsInfo($findServerWhoIs, $cleanDomain);

            return $whoIsInfo;
        } catch (WhoisException $e) {
            throw new WhoisException($e->getMessage());
        }
    }

    /**
     * Get the clean domain
     * @param string $url
     * @return string
     */
    public function getCleanDomain(string $url): string
    {
        // Sanitize and normalize the URL
        $url = trim(strtolower($url));
        // Remove protocol and www if present
        $url = preg_replace('#^(https?://)?(www\.)?#i', '', $url);
        // Remove query string and path
        $url = preg_replace('#[?/].*$#', '', $url);

        return $url;
    }

    /**
     * Find the whois server for a domain
     * @param string $tld
     * @return string
     * @throws WhoisException
     */
    private function findServerWhoIs(string $tld): string
    {
        $server = $this->whoIsServer->findByDomain($tld);
        if (!$server) {
            throw new WhoisException(WhoisException::UNSUPPORTED_DOMAIN);
        }
        return $server->whois_server;
    }

    /**
     * Get whois info from a server
     * @param string $server
     * @param string $url
     * @return string
     * @throws WhoisException
     */
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
