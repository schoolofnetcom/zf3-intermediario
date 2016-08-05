<?php

namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Db\Adapter\AdapterInterface;

class AuthenticationServiceFactory
{

    //pegar adaptador de banco de dados
    //configurar um adaptador para administrar a autenticação do usuário
    //cria a sessão para guardamos o usuário
    //criar o serviço de AuthenticationService
    public function __invoke(ContainerInterface $container)
    {
        $passwordCallbackVerify = function ($passwordInDatabase, $passwordSent) {
            return password_verify($passwordSent, $passwordInDatabase);
        };
        $dbAdapter = $container->get(AdapterInterface::class);
        $authAdapter = new CallbackCheckAdapter($dbAdapter, 'users', 'username', 'password', $passwordCallbackVerify);
        $storage = new Session();
        return new AuthenticationService($storage, $authAdapter);
    }


}