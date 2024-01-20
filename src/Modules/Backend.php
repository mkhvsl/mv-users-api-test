<?php

declare(strict_types=1);

namespace Mkhvsl\MvUsersApiTest\Modules;

use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Psr\Container\ContainerInterface;

class Backend implements ExecutableModule
{
    use ModuleClassNameIdTrait;

    private $properties;
    private $prefix;

    public function run(ContainerInterface $container): bool
    {
        $this->properties = $container->get(Package::PROPERTIES);
        $this->prefix = str_replace('-', '_', $this->properties->baseName());

        register_activation_hook($this->properties->pluginMainFile(), [$this, 'activation']);
        register_deactivation_hook($this->properties->pluginMainFile(), [$this, 'deactivation']);

        add_action('init', [$this, 'init']);
        add_action('admin_menu', [$this, 'settingsPage']);
        add_action('admin_init', [$this, 'registerSettings']);

        add_action('added_option_' . $this->prefix . '_settings', [$this, 'settingsAdd']);
        add_action('updated_option_' . $this->prefix . '_settings', [$this, 'settingsUpdate']);

        add_filter(
            'plugin_action_links_' . plugin_basename($this->properties->pluginMainFile()),
            [$this, 'settingsLink']
        );

        return true;
    }

    public function registerSettings()
    {
        register_setting(
            $this->prefix . '_settings',
            $this->prefix . '_settings',
            [$this, 'settingsValidate']
        );
        add_settings_section(
            'plugin_settings',
            'Settings',
            [$this, 'sectionText'],
            $this->prefix
        );
        add_settings_field(
            $this->prefix . '_setting_url',
            'URL',
            [$this, 'settingUrl'],
            $this->prefix,
            'plugin_settings'
        );
    }

    public function settingsValidate(array $input): array
    {
        $newinput = $input;
        $newinput['url'] = preg_replace('/[^-_a-z0-9]/', '', trim($input['url']));

        return $newinput;
    }

    public function sectionText(array $input)
    {
        echo '<p>Here you can set all the settings</p>';
    }

    public function settingUrl()
    {
        $options = get_option($this->prefix . '_settings');
        echo "<input id='" . esc_attr($this->prefix . '_setting_url') . "' name='" . esc_attr($this->prefix . '_settings[url]') . "' type='text' value='" . esc_attr($options['url']) . "' />";
    }

    public function settingsLink(array $links): array
    {
        $links[] = '<a href="' . get_admin_url() . 'options-general.php?page=' . $this->properties->baseName() . '">' . __('Settings', 'textdomain') . '</a>';

        return $links;
    }

    public function settingsPage()
    {
        add_options_page(
            $this->properties->name(),
            $this->properties->name(),
            'manage_options',
            $this->properties->baseName(),
            [$this, 'renderSettingsPage']
        );
    }

    public function renderSettingsPage()
    {
        include plugin_dir_path($this->properties->pluginMainFile()) . 'resources/views/settings.php';
    }

    public function activation()
    {
        $this->init();
    }

    public function deactivation()
    {
        flush_rewrite_rules();
    }

    public function init()
    {
        $options = get_option($this->prefix . '_settings');
        if ($options === false) {
            update_option($this->prefix . '_settings', ['url' => $this->properties->baseName()]);
            $options = get_option($this->prefix . '_settings');
        }

        add_rewrite_rule(
            '^' . $options['url'] . '$',
            'index.php?' . $this->properties->baseName() . '=1',
            'top'
        );

        flush_rewrite_rules();
    }

    public function settingsAdd(string $optionValue)
    {
        $this->init();
    }

    public function settingsUpdate(string $oldValue, string $optionValue)
    {
        $this->init();
    }
}
