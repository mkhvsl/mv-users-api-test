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

    public function run(ContainerInterface $container): bool
    {
        $this->properties = $container->get(Package::PROPERTIES);
        $this->prefix = str_replace('-', '_', $this->properties->baseName());

        add_action('template_redirect', [$this, 'renderPage']);
        add_action('wp_ajax_nopriv_' . $this->prefix . '_api_user', [$this, 'apiUser']);
        add_action('wp_ajax_' . $this->prefix . '_api_user', [$this, 'apiUser']);

        add_filter('query_vars', [$this, 'queryVars']);

        return true;
    }

    public function apiUser()
    {
        check_ajax_referer('title_example');

        if (!isset($_POST['id'])) {
            wp_send_json('Error, please contact site administrator');
        }

        $id = wp_unslash(intval($_POST['id']));

        $response = get_transient($this->prefix . '_user' . $id);
        if ($response === false) {
            $response = wp_remote_get('https://jsonplaceholder.typicode.com/users/' . $id);
            if (is_wp_error($response)) {
                wp_send_json('Error, please contact site administrator');
            }
            set_transient($this->prefix . '_user' . $id, $response, 60 * 60);
        }

        $body = wp_remote_retrieve_body($response);
        $user = json_decode($body);

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
                    'nonce' => wp_create_nonce('title_example'),
                ]
            );

            $response = get_transient($this->prefix . '_users');
            if ($response === false) {
                $response = wp_remote_get('https://jsonplaceholder.typicode.com/users/');
                set_transient($this->prefix . '_users', $response, 60 * 60);
            }

            $body = wp_remote_retrieve_body($response);
            $users = json_decode($body);

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
