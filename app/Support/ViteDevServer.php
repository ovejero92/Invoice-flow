<?php

namespace App\Support;

final class ViteDevServer
{
    /**
     * True when public/hot exists but nothing accepts TCP on its host:port
     * (typical after stopping npm run dev without deleting hot).
     */
    public static function hotFileExistsWithoutReachableServer(): bool
    {
        $path = public_path('hot');
        if (! is_file($path) || ! is_readable($path)) {
            return false;
        }

        $url = trim((string) file_get_contents($path));
        if ($url === '') {
            return true;
        }

        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        if ($host === null || $host === '' || $port === null || $port === false) {
            return true;
        }

        return ! self::tcpAcceptsConnections($host, (int) $port);
    }

    private static function tcpAcceptsConnections(string $host, int $port): bool
    {
        $errno = 0;
        $errstr = '';

        if (str_contains($host, ':') && ! str_starts_with($host, '[')) {
            $host = '['.$host.']';
        }

        $socket = @fsockopen($host, $port, $errno, $errstr, 0.3);
        if ($socket !== false) {
            fclose($socket);

            return true;
        }

        return false;
    }
}
