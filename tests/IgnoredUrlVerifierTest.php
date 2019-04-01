<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\IgnoredUrlVerifier\Tests;

use webignition\IgnoredUrlVerifier\IgnoredUrlVerifier;

class IgnoredUrlVerifierTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var IgnoredUrlVerifier
     */
    private $ignoredUrlVerifier;

    protected function setUp()
    {
        parent::setUp();

        $this->ignoredUrlVerifier = new IgnoredUrlVerifier();
    }

    /**
     * @dataProvider isUrlIgnoredHostsDataProvider
     */
    public function testIsUrlIgnored(string $url, array $exclusions, bool $expectedIsIgnored)
    {
        $this->assertEquals(
            $expectedIsIgnored,
            $this->ignoredUrlVerifier->isUrlIgnored($url, $exclusions)
        );
    }

    public function isUrlIgnoredHostsDataProvider(): array
    {
        return [
            'hosts: none' => [
                'url' => 'http://example.com',
                'exclusions' => [],
                'expectedIsIgnored' => false,
            ],
            'hosts: no match' => [
                'url' => 'http://example.com',
                'exclusions' => [
                    IgnoredUrlVerifier::EXCLUSIONS_HOSTS => [
                        'foo.example.com',
                        'bar.example.com',
                    ],
                ],
                'expectedIsIgnored' => false,
            ],
            'hosts: ascii url matches ascii domain' => [
                'url' => 'http://example.com',
                'exclusions' => [
                    IgnoredUrlVerifier::EXCLUSIONS_HOSTS => [
                        'example.com',
                    ],
                ],
                'expectedIsIgnored' => true,
            ],
            'hosts: punycode url matches unicode domain' => [
                'url' => 'http://xn--u2u.com',
                'exclusions' => [
                    IgnoredUrlVerifier::EXCLUSIONS_HOSTS => [
                        '搜.com',
                    ],
                ],
                'expectedIsIgnored' => true,
            ],
            'hosts: unicode url matches punycode domain' => [
                'url' => 'http://搜.com',
                'exclusions' => [
                    IgnoredUrlVerifier::EXCLUSIONS_HOSTS => [
                        'xn--u2u.com',
                    ],
                ],
                'expectedIsIgnored' => true,
            ],
        ];
    }
}
