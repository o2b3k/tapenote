<?php

return [
    'left_menu' => [
        [
            'route' => 'dashboard',
            'title' => 'Dashboard',
            'icon' => 'fa fa-fw fa-dashboard',
        ],
        [
            'route' => 'countries.index',
            'title' => 'Countries',
            'icon' => 'fa fa-fw fa-map-marker',
        ],
        [
            'route' => 'categories.index',
            'title' => 'Categories',
            'icon' => 'fa fa-fw fa-th-list',
        ],
        [
            'route' => 'monuments.index',
            'title' => 'Monuments',
            'icon' => 'fa fa-fw fa-flag-o'
        ],
        [
            'role' => \App\Constants\UserRoles::ROLE_SUPER_USER,
            'route' => 'users.index',
            'title' => 'Users',
            'icon' => 'fa fa-fw fa-users',
        ],
    ]
];