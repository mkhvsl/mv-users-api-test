<?php

declare(strict_types=1);

namespace Mkhvsl\MvUsersApiTest\Services;

class UsersApi
{
    private const URL = 'https://jsonplaceholder.typicode.com/users/';

    private $prefix;

    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function users(): array
    {
        $response = get_transient($this->prefix . '_users');
        if ($response === false) {
            $response = wp_remote_get($this::URL);

            if (is_wp_error($response)) {
                return ['errors' => $response->errors];
            }

            set_transient($this->prefix . '_users', $response, 60 * 60);
        }

        $body = wp_remote_retrieve_body($response);
        $users = json_decode($body);

        return ['data' => $users];
    }

    public function user(int $id): array
    {
        $response = get_transient($this->prefix . '_user' . $id);
        if ($response === false) {
            $response = wp_remote_get($this::URL . $id);

            if (is_wp_error($response)) {
                return ['errors' => $response->errors];
            }

            set_transient($this->prefix . '_user' . $id, $response, 60 * 60);
        }

        $body = wp_remote_retrieve_body($response);
        $user = json_decode($body);

        return ['data' => $user];
    }
}
