<?php 
namespace Zf2Plugins;
 
use Zend\Mvc\MvcEvent;
use Zend\Db\Adapter\Adapter as Adapter;
use Zend\Config\Reader\Json as JsonParser;
use Zend\ServiceManager\ServiceManager;
use Zf2Plugins\Helper\ControllerPluginManager;
use Zf2Plugins\Helper\ViewPluginManager;
use Zf2Plugins\Listener\PluginListener;

class Module
{ 
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager   = $e->getApplication()->getEventManager(); 
        $pluginListener = new PluginListener();
        $pluginListener->attach($eventManager); 
    }

    public function getConfig()
    {
    	$config = array();
    	foreach (glob(__DIR__ . '/config/plugin.*.config.php') as $file) {
    		$config = array_replace_recursive($config, require $file);
    	} 
        return $config;
    }

    public function getControllerPluginConfig()
    {
    	return array(
    			'factories' => array( 
    					'enablePlugins' => function($controllerPluginManager) {
    						$pluginManager = new ControllerPluginManager($controllerPluginManager);
    						return $pluginManager;
    					},
    			)
    	);
    }
    
    public function getViewHelperConfig()
    {
    	return array(
    			'factories' => array( 
    					'getPlugin' => function($helperPluginManager) {
    						$pluginManager = new ViewPluginManager($helperPluginManager);
    						return $pluginManager;
    					},
    			)
    	);
    }
    
    public function getAutoloaderConfig()
    { 
    	$folders = scandir( __DIR__ . '/src/');
    	$namespaces[__NAMESPACE__] = __DIR__ . '/main';
    	if ($folders) {
    		foreach ($folders as $folder) {
    			if ($folder == '.' || $folder == '..') {
    				continue;
    			}
    			$namespaces[$folder] = __DIR__ . '/src/' . $folder;
    		}
    	} 
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => $namespaces,
            ),
        );
    }
    
    public function getServiceConfig()
    {
    	return array( 
    			'abstract_factories' => array(
    					'Zf2Plugins\Factory\PluginsAbstractFactory'
    			),
    	);
    }
    
  
}
