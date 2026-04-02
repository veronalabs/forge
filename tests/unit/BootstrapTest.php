<?php

namespace {{PLUGIN_NAMESPACE}}\Tests\Unit;

use PHPUnit\Framework\TestCase;
use {{PLUGIN_NAMESPACE}}\Container\ServiceContainer;

class BootstrapTest extends TestCase
{
    protected function tearDown(): void
    {
        ServiceContainer::getInstance()->reset();
    }

    public function testServiceContainerIsSingleton(): void
    {
        $a = ServiceContainer::getInstance();
        $b = ServiceContainer::getInstance();

        $this->assertSame($a, $b);
    }

    public function testRegisterAndGetService(): void
    {
        $container = ServiceContainer::getInstance();

        $container->register('test', function () {
            return new \stdClass();
        });

        $service = $container->get('test');

        $this->assertInstanceOf(\stdClass::class, $service);
    }

    public function testServiceIsResolvedOnce(): void
    {
        $container = ServiceContainer::getInstance();

        $container->register('test', function () {
            return new \stdClass();
        });

        $a = $container->get('test');
        $b = $container->get('test');

        $this->assertSame($a, $b);
    }

    public function testAliasResolvesToTarget(): void
    {
        $container = ServiceContainer::getInstance();

        $container->register('original', function () {
            return new \stdClass();
        });

        $container->alias('shortcut', 'original');

        $this->assertSame($container->get('original'), $container->get('shortcut'));
    }

    public function testGetReturnsNullForUnregistered(): void
    {
        $container = ServiceContainer::getInstance();

        $this->assertNull($container->get('nonexistent'));
    }

    public function testHasReturnsTrueForRegistered(): void
    {
        $container = ServiceContainer::getInstance();

        $container->register('test', function () {
            return new \stdClass();
        });

        $this->assertTrue($container->has('test'));
        $this->assertFalse($container->has('missing'));
    }
}
