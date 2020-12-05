<?php

// define routes

return [
	/****************************
	 ******* Sample route *******/
	'welcome' => [
		'pattern' => '',
		'controller' => \App\Controller\WelcomeController::class,
		'action' => 'index'
	],

    'welcome-alt' => [
        'pattern' => 'landing',
        'controller' => \App\Controller\WelcomeController::class,
        'action' => 'index'
    ]
];