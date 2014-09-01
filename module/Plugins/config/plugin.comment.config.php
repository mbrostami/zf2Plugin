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
            'plugins-comment' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/plugins/comment[/:controller][/:action]', 
                    'defaults' => array(
                        '__NAMESPACE__' => 'Comment\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index' 
                    ),
                ) 
            ),  
        ),
    ),  
    'controllers' => array (
    		'invokables' => array (
    				'Comment\Controller\Index' => 'Comment\Controller\IndexController',
    		)
    ),
    'autoload-plugins' => array(
    		'comment' => array(
    				'comment/index/render-view' => array(
		    				'patterns' => array(
		    					'/.*/'  //plugin will autoload in all routes
		    					// '/sample-*/'  plugin will autoload in all routes which start with sample-
		    				),
		    				'routes' => array(
 		    					// 'sample-index-index'  // exactly match by this routes
		    				) 
    				),'comment/index/render-closure' => array(
		    				'patterns' => array(
  		    					// '/.*/'
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
