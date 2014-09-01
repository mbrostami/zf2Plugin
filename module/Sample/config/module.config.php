<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'controllers' => array (
			'invokables' => array (
					'Sample\Controller\Index' => 'Sample\Controller\IndexController',
			)
	),
    'router' => array(
        'routes' => array( 
            'home' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sample\Controller',
                        'controller'    => 'index',
                        'action'        => 'index' 
                    ),
                ), 
            ), 
        ),
    ), 
	'view_manager' => array(
			'display_not_found_reason' => true,
			'display_exceptions'       => true,
			'doctype'                  => 'HTML5',
			'not_found_template'       => 'error/404',
			'exception_template'       => 'error/index',
			'template_map' => array( 
					'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml', 
					'error/404'               => __DIR__ . '/../view/error/404.phtml',
					'error/index'             => __DIR__ . '/../view/error/index.phtml',
			),
			'template_path_stack' => array(
					__DIR__ . '/../view',
			),
	),
);
