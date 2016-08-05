<?php

namespace Blog;

use Blog\Controller\BlogController;
use Blog\Controller\Factory\BlogControllerFactory;
use Blog\Controller\Factory\PostControllerFactory;
use Blog\Controller\PostController;
use Blog\Form\Factory\PostFormFactory;
use Blog\Form\PostForm;
use Blog\Model\Factory\CommentTableFactory;
use Blog\Model\Factory\CommentTableGatewayFactory;
use Blog\Model\Factory\PostTableFactory;
use Blog\Model\Factory\PostTableGatewayFactory;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface, ControllerProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . "/../config/module.config.php";
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PostTable::class => PostTableFactory::class,
                Model\PostTableGateway::class => PostTableGatewayFactory::class,
                PostForm::class => PostFormFactory::class,
                Model\CommentTable::class => CommentTableFactory::class,
                Model\CommentTableGateway::class => CommentTableGatewayFactory::class,
            ]
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                BlogController::class => BlogControllerFactory::class,
                PostController::class => PostControllerFactory::class
            ]
        ];
    }

}