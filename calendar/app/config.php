<?php

return [
    '/' => [
        'controller' => 'auth',
        'action' => 'displayAuthPage',
        'view' => 'auth_page',
        'open' => true
    ],
    '/login' => [
        'controller' => 'auth',
        'action' => 'login',
        'view' => null, 
        'open' => false
    ],
    '/logout' => [
        'controller' => 'auth', 
        'action' => 'logout',
        'view' => null,
        'open' => false
    ],
    '/register' => [
        'controller' => 'register',
        'action' => 'displayRegisterPage',
        'view' => 'register_page', 
        'open' => true
    ],
    '/register/sign-up' => [
        'controller' => 'register',
        'action' => 'signUp',
        'view' => null,
        'open' => false
    ],
    '/calendar' => [
        'controller' => 'calendar',
        'action' => 'displayCalendar',
        'view' => 'calendar',
        'open' => true
    ],
    '/calendar/add' => [
        'controller' => 'calendar',
        'action' => 'addTask',
        'view' => null,
        'open' => false
    ],
    '/calendar/delete' => [
        'controller' => 'calendar',
        'action' => 'deleteTask',
        'view' => null,
        'open' => false
    ],
    '/calendar/edit' => [
        'controller' => 'calendar',
        'action' => 'editTask',
        'view' => null,
        'open' => false
    ], 
    '/calendar/change-status' => [
        'controller' => 'calendar',
        'action' => 'changeTaskStatus',
        'view' => null,
        'open' => false
    ]
];