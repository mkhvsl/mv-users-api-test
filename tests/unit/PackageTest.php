<?php

declare(strict_types=1);

namespace Mkhvsl\MvUsersApiTest\Tests\Unit;

use Brain\Monkey;
use Inpsyde\Modularity\Module\ExtendingModule;
use Inpsyde\Modularity\Module\FactoryModule;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Properties\Properties;
use Inpsyde\Modularity\Properties\PluginProperties;
use Mkhvsl\MvUsersApiTest\Tests\TestCase;
use Psr\Container\ContainerInterface;
use Mkhvsl\MvUsersApiTest\Modules\Frontend;
use Mkhvsl\MvUsersApiTest\Modules\Backend;
use Mkhvsl\MvUsersApiTest\Modules\Data;
use Mkhvsl\MvUsersApiTest\Modules\UsersApiService;

class PackageTest extends TestCase
{
    /**
     * @test
     */
    public function testPackage(): void
    {
        $expectedName = 'foo-bar';
        $properties = $this->mockProperties($expectedName);
        $package = Package::new($properties)
            ->addModule(new Frontend())
            ->addModule(new Backend())
            ->addModule(new Data())
            ;

        $usersApiService = $package->build()->container()->get(UsersApiService::class);

        $prefix = str_replace('-', '_', $properties->baseName());

        static::assertSame($expectedName, $package->name());
        static::assertInstanceOf(Properties::class, $package->properties());
        static::assertInstanceOf(ContainerInterface::class, $package->container());
        static::assertEquals($prefix, $usersApiService->prefix());
    }
}
