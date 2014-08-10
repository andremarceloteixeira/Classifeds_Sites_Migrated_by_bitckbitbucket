<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Anuncios\Controller\Index' =>  'Anuncios\Controller\IndexController',
            'Anuncios\Controller\Utilizador' =>  'Anuncios\Controller\UtilizadorController',
            'Anuncios\Controller\Article' => 'Anuncios\Controller\ArticleController',
            'Anuncios\Controller\Contactar' => 'Anuncios\Controller\ContactarController'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'catalog' => __DIR__ . '/../view'
        ),
    ),
    'router' => array(
        'routes' => array(
            'anuncios' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/anuncios[/category/:category][/city/:city]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'category' => '[0-9]+',
                        'city' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Anuncios\Controller\Index',
                        'action'     => 'anuncios',
                    ),
                ),
            ),
            'anunciar' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/anunciar[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Anuncios\Controller\Index',
                        'action'     => 'add',
                    ),
                ),
            ),
            'contactar' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/contactar[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Anuncios\Controller\Contactar',
                        'action'     => 'index',
                    ),
                ),
            ),
            'registar' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/registar',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Anuncios\Controller\Utilizador',
                        'action'     => 'index',
                    ),
                ),
            ),
            'article' => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'priority' => 10,
                'options' => array(
                    'route' => '/',
                    'https' => false,
                    'regex' => '/(?<article>[a-z0-9_-]*).(?<format>(html))',
                    'defaults' => array(
                        'controller' => 'Anuncios\Controller\Article',
                        'action' => 'index',
                        'format' => 'html'
                    ),
                    'spec' => '/%article%.%format%'
                )
            ),
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'Anuncios_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Anuncios/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Anuncios\Entity' =>  'Anuncios_driver'
                ),
            ),
        ),
    ),
    'service_manager' => array (
        'factories' => array(
            /*'Zend\Authentication\AuthenticationService' => 'CsnUser\Service\Factory\AuthenticationFactory',*/
            'mail.transport' => 'Anuncios\Service\Factory\MailTransportFactory',
            'anuncios_module_options' => 'Anuncios\Service\Factory\ModuleOptionsFactory',
            /*'csnuser_error_view' => 'CsnUser\Service\Factory\ErrorViewFactory',
            'csnuser_user_form' => 'CsnUser\Service\Factory\UserFormFactory', */
        ),
    ),
);