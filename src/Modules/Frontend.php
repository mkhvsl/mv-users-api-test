<?php

declare(strict_types=1);

namespace Mkhvsl\MvUsersApiTest\Modules;

use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Psr\Container\ContainerInterface;

class Frontend implements ExecutableModule
{
    use ModuleClassNameIdTrait;

    private $properties;
    private $prefix;
    private $usersApi;

    public function run(ContainerInterface $container): bool
    {
        $this->properties = $container->get(Package::PROPERTIES);
        $this->prefix = str_replace('-', '_', $this->properties->baseName());
        $this->usersApi = $container->get(UsersApiService::class);

        add_action('template_redirect', [$this, 'renderPage']);
        add_action('wp_ajax_nopriv_' . $this->prefix . '_api_user', [$this, 'apiUser']);
        add_action('wp_ajax_' . $this->prefix . '_api_user', [$this, 'apiUser']);

        add_filter('query_vars', [$this, 'queryVars']);

        return true;
    }

    public function apiUser()
    {
        check_ajax_referer($this->properties->baseName());

        if (!isset($_POST['id'])) {
            wp_send_json([]);
        }

        $id = intval($_POST['id']);

        $user = $this->usersApi->user($id);

        wp_send_json($user);
    }

    public function renderPage()
    {
        if (get_query_var($this->properties->baseName()) === '1') {
            wp_enqueue_style(
                $this->properties->baseName() . '-uikit-style',
                'https://cdn.jsdelivr.net/npm/uikit@3.17.11/dist/css/uikit.min.css',
                [],
                $this->properties->version()
            );
            wp_enqueue_script(
                $this->properties->baseName() . '-uikit-script',
                'https://cdn.jsdelivr.net/npm/uikit@3.17.11/dist/js/uikit.min.js',
                [],
                $this->properties->version(),
                ['in_footer' => true]
            );
            wp_enqueue_script(
                $this->properties->baseName() . '-uikit-icons-script',
                'https://cdn.jsdelivr.net/npm/uikit@3.17.11/dist/js/uikit-icons.min.js',
                [],
                $this->properties->version(),
                ['in_footer' => true]
            );
            wp_enqueue_script(
                $this->properties->baseName() . '-script',
                plugin_dir_url($this->properties->pluginMainFile()) . 'public/js/script.js',
                ['jquery'],
                $this->properties->version(),
                ['in_footer' => true]
            );
            wp_localize_script(
                $this->properties->baseName() . '-script',
                'my_ajax_obj',
                [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce($this->properties->baseName()),
                ]
            );

            $users = $this->usersApi->users();

            include plugin_dir_path($this->properties->pluginMainFile()) . 'resources/views/page.php';
            die;
        }
    }

    public function queryVars(array $queryVars): array
    {
        $queryVars[] = $this->properties->baseName();
        return $queryVars;
    }
}
