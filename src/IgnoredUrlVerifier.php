<?php

namespace webignition\IgnoredUrlVerifier;

use webignition\Uri\Host;
use webignition\Uri\Normalizer;
use webignition\Uri\Uri;

class IgnoredUrlVerifier
{
    const EXCLUSIONS_HOSTS = 'hosts';
    const EXCLUSIONS_SCHEMES = 'schemes';
    const EXCLUSIONS_URLS = 'urls';

    public function isUrlIgnored(string $url, array $exclusions): bool
    {
        $uri = new Uri($url);
        $uri = Normalizer::normalize($uri);

        $scheme = $uri->getScheme();
        $excludedSchemes = $exclusions[self::EXCLUSIONS_SCHEMES] ?? [];
        $excludedSchemes = is_array($excludedSchemes) ? $excludedSchemes : [];

        if (in_array($scheme, $excludedSchemes)) {
            return true;
        }

        $host = new Host($uri->getHost());
        $excludedHosts = $exclusions[self::EXCLUSIONS_HOSTS] ?? [];
        $excludedHosts = is_array($excludedHosts) ? $excludedHosts : [];

        foreach ($excludedHosts as $excludedHost) {
            if ($host->isEquivalentTo(new Host($excludedHost))) {
                return true;
            }
        }

        $excludedUrls = $exclusions[self::EXCLUSIONS_URLS] ?? [];
        $excludedUrls = is_array($excludedUrls) ? $excludedUrls : [];

        if (in_array((string) $uri, $excludedUrls)) {
            return true;
        }

        return false;
    }
}
