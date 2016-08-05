<?php

namespace Blog;

use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            #Controller\BlogController::class => InvokableFactory::class
        ]
    ],
    'router' => [
        'routes' => [
            'admin-blog' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin'
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'post' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/blog[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\BlogController::class,
                                'action' => 'index'
                            ]
                        ]
                    ],
                ]
            ],
            'site-post' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/post[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PostController::class,
                        'action' => 'index'
                    ]
                ]
            ],
        ]


    ],
    'view_manager' => [
        'template_path_stack' => [
            'blog' => __DIR__ . "/../view"
        ]
    ]
];