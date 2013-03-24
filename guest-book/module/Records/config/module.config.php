<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Records\Controller\Records' => 'Records\Controller\RecordsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'records' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '[/page[/:page]][/]',
                    'constraints' => array(
                        'page' => '[0-9]*',
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Records\Controller\Records',
                        'action'   => 'index',
                        'page' => 1,
                    ),
                ),
                'may_terminate' => true,
                    'child_routes'  => array(
                        'query' => array(
                            'type' => 'query',
                            'options' => array(
                                'defaults' => array(
                                    'field' => 'date',
                                    'order' => 'desc'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Records' => __DIR__ . '/../view',
        ),
    ),
);
