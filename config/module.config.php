<?
return [
    'service_manager' => [
        'factories' => [
            \XT\Option\Service\Groups::class
                => \XT\Option\Service\Factory\GroupsFactory::class,

            \XT\Option\Service\Options::class
                => \XT\Option\Service\Factory\OptionsFactory::class,

            \XT\Option\Service\OptionManager::class
                =>  \XT\Option\Service\Factory\OptionManagerFactory::class,

            ],
        ],

    'view_manager' => [
        'template_map' => [
            'ichte/option/option/index'         => __DIR__.'/../view/index.phtml',
            'ichte/option/option/addgroup'      => __DIR__.'/../view/addgroup.phtml',
            'ichte/option/option/editgroup'     => __DIR__.'/../view/editgroup.phtml',
            'ichte/option/option/delgroup'      => __DIR__.'/../view/delgroup.phtml',
            'ichte/option/option/conf'          => __DIR__.'/../view/conf.phtml',
            'ichte/option/option_temp'          => __DIR__.'/../view/partial/option.phtml',
            'ichte/option/option/addoption'     => __DIR__.'/../view/addoption.phtml',
        ],
    ],

    'controllers' => [
        'factories' => [
                \XT\Option\Controller\OptionController::class => \XT\Option\Controller\OptionController::class,
            ]
    ],
    'router' => [
        'routes' => [
            'ahdconfig' => [
                'type'    => 'Segment',

                'options' => [
                    'route'    => '/admin-config/[:action/]',
                    'defaults' => [
                        'controller'    => \XT\Option\Controller\OptionController::class,
                        'action'        => 'index',
                        'admin'         => 9
                    ]
                ],
                ],
            ],
        ],
    ];