<?php

namespace Tests\Router;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Router\Router;

#[CoversClass(Router::class)]
class RouterTest extends TestCase {

    public static function dataProviderTestResolvePath(): array {
        return [
            'simple' => [
                'routePath' => '/',
                'path' => '/',
                'expected' => true,
            ],
            'simple path' => [
                'routePath' => '/blog',
                'path' => '/blog',
                'expected' => true,
            ],
            'simple path unmatched' => [
                'routePath' => '/blog',
                'path' => '/blog/123',
                'expected' => false,
            ],
            'simple wildcard' => [
                'routePath' => '/*',
                'path' => '/',
                'expected' => true,
            ],
            'wildcard match' => [
                'routePath' => '/*/*',
                'path' => '/foo/bar/baz',
                'expected' => true,
                'expectedParameters' => [
                    '-' => ['foo', 'bar/baz']
                ]
            ],
            'named match' => [
                'routePath' => '/post/{post-id}',
                'path' => '/post/123',
                'expected' => true,
                'expectedParameters' => [
                    'post-id' => '123'
                ]
            ],
            'named match with query' => [
                'routePath' => '/category/{category}/post/{post-id}',
                'path' => '/category/blog/post/123/?order=asc',
                'expected' => true,
                'expectedParameters' => [
                    'category' => 'blog',
                    'post-id' => '123',
                    'order' => 'asc'
                ]
            ],
            'named match with query override' => [
                'routePath' => '/category/{category}/post/{post-id}',
                'path' => '/category/blog/post/123/?category=news',
                'expected' => true,
                'expectedParameters' => [
                    'category' => 'blog',
                    'post-id' => '123',
                    'query-category' => 'news',
                ]
            ],
        ];
    }

    /**
     * @param string $routePath
     * @param string $path
     * @param bool $expected
     * @param array|null $expectedParameters
     * @param callable|null $callback
     * @return void
     */
    #[DataProvider('dataProviderTestResolvePath')]
    #[Test]
    public function testResolvePath(string $routePath, string $path, bool $expected, ?array $expectedParameters = null, ?callable $callback = null): void {
        $resolvedParameters = [];
        $resolved = Router::resolvePath($routePath, $path, $resolvedParameters);
        $this->assertEquals($expected, $resolved);
        if (!is_null($expectedParameters)) {
            $this->assertEquals($expectedParameters, $resolvedParameters);
        }
        if (!is_null($callback)) {
            $callback($resolved, $resolvedParameters);
        }
    }
}
