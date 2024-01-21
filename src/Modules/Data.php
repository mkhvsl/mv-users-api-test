<?php

declare(strict_types=1);

namespace Mkhvsl\MvUsersApiTest\Modules;

use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Psr\Container\ContainerInterface;
use Mkhvsl\MvUsersApiTest\Services\UsersApi;

class Data implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            UsersApiService::class => static function (ContainerInterface $container): UsersApi {
                $properties = $container->get(Package::PROPERTIES);
                $prefix = str_replace('-', '_', $properties->baseName());

                return new UsersApi($prefix);
            },
        ];
    }
}
