<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array( 
            'plugins-my-plugin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/plugins/my-plugin[/:controller][/:action]', 
                    'defaults' => array(
                        '__NAMESPACE__' => 'MyPlugin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index' 
                    ),
                ) 
            ),  
        ),
    ),  
    'controllers' => array (
    		'invokables' => array (
    				'MyPlugin\Controller\Index' => 'MyPlugin\Controller\IndexController',
    		)
    ),
    'autoload-plugins' => array(
    		'my-plugin' => array(
    				'my-plugin/index/render-view' => array(
		    				'patterns' => array(
		    					'/.*/'  //plugin will autoload in all routes
		    					// '/sample-*/'  plugin will autoload in all routes which start with sample-
		    				),
		    				'routes' => array(
 		    					// 'sample-index-index'  // exactly match by this routes
		    				) 
    				),'my-plugin/index/render-closure' => array(
		    				'patterns' => array(
  		    					// plugin doesn't load auto we load this by SampleController
		    				) 
    				)
    		)
    ),
    'view_manager' => array(
        'template_path_stack' => array(
        		__DIR__ . '/../view',
        ),
    ), 
);
