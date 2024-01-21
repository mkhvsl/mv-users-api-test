<?php

echo "<input id='" . esc_attr($this->prefix . '_setting_url') . "' name='" . esc_attr($this->prefix . '_settings[url]') . "' type='text' value='" . esc_attr($options['url']) . "' />";