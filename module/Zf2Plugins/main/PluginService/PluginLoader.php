<?php 
namespace Zf2Plugins\PluginService;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\MvcEvent; 
 
class PluginLoader
{
	/**
	 * Array of plugin names which are matching by route
	 * @var array
	 */
	public $autoPlugins;
	
	/**
	 * All plugins which are loads by controller plugin manager
	 * @var array
	 */
	public $loadedPlugins;
	/**
	 * @var \Zend\View\Model\ViewModel
	 */
	public $viewModel;
	
	/**
	 * Check plugins for route match by plugin names
	 * @param ServiceManager $serviceLocator
	 * @param string $route
	 */
	public function autoLoad(ServiceManager $serviceLocator, $route)
	{ 
		$configs = $serviceLocator->get("Config");   
		$autoloadPlugins = $configs['autoload-plugins'];
		$loadedplugins = array();

		if ($autoloadPlugins) {
			foreach ($autoloadPlugins as $pluginName => $plugins) {
				foreach ($plugins as $loadPlugin => $renderRoutes) {
					if (is_array($renderRoutes)) { 
						if (isset($renderRoutes['patterns'])) {
							foreach ($renderRoutes['patterns'] as $pattern) {
								if (preg_match($pattern, $route)) {
									$loadedplugins[] = $loadPlugin;
									break 1;
								}
							}
						}
						if (isset($renderRoutes['routes'])) {
							if (in_array($route, $renderRoutes['routes'])) {
								$loadedplugins[] = $loadPlugin;
							} 
						}
						
					}
				}
			}
		}  
		$this->autoPlugins =  $loadedplugins;
	}
	
	/**
	 * Set MvcEvent variable for set view variables in controllerplugin , called from accesslistener
	 * @param MvcEvent $event
	 */
	public function setEvent(MvcEvent $event)
	{
		$this->viewModel = $event->getViewModel();
	}
	
	/**
	 * Set loaded plugin
	 * @param string $pluginName
	 */
	public function loadPlugin($pluginName)
	{
		$this->loadedPlugins[] = $pluginName;
	}
}