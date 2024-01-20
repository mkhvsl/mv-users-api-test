<?php

declare(strict_types=1);

/*
 * Plugin Name:       MV Users API Test
 * Plugin URI:        https://github.com/mkhvsl/mv-users-api-test
 * Description:       WordPress Users API Test Plugin
 * Author:            Mykhailo Vasylenko
 * Author URI:        https://github.com/mkhvsl/
 * Version:           1.0.0
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mv-users-api-test
 */

namespace Mkhvsl\MvUsersApiTest;

use Inpsyde\Modularity;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

function plugin(): Modularity\Package
{
    static $package;
    if (!$package) {
        $properties = Modularity\Properties\PluginProperties::new(__FILE__);
        $package = Modularity\Package::new($properties)
            ->addModule(new Modules\Frontend())
            ->addModule(new Modules\Backend())
            ;
    }

    return $package;
}

add_action(
    'plugins_loaded',
    static function (): void {
        plugin()->boot();
    }
);
