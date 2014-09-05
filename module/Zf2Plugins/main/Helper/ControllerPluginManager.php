<?php
namespace Zf2Plugins\Helper;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Application\Traits\HelperTrait;  
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/* @var $viewRenderer \Zend\View\Renderer\PhpRenderer */ 
/* @var $pluginLoader \Zf2Plugins\PluginService\PluginLoader */

class ControllerPluginManager extends AbstractPlugin
{ 
	public $renderOnce = false;
	
	public function __construct(PluginManager $pluginManager)
	{ 
		$this->serviceLocator = $pluginManager->getServiceLocator(); 
	}
	 
	public function autoloadPlugins()
	{
		$pluginLoader = $this->serviceLocator->get("Plugins");
		$controllerObject = $this->getController();
		if (! $controllerObject) {
			return;
		}   
		if ($pluginLoader->autoPlugins) { 
			foreach ($pluginLoader->autoPlugins as $pluginName) {  
				$pluginRequest = explode("/", $pluginName);
				$pluginRequest = preg_replace_callback('/(?<!^)([-])+([a-z])/', function ($match) {
					return (isset($match[2]) ? ucfirst($match[2]) : "" );
				}, $pluginRequest);  
				$forward = false; 
				if (isset($pluginRequest[2])) {
					list($plugin, $controller, $method) = $pluginRequest;
					$forward = $controllerObject->forward()->dispatch(ucfirst($plugin).'\Controller\\'.ucfirst($controller), array('action' => ucfirst($method)));
				} elseif (isset($pluginRequest[1])) {
					list($plugin, $controller) = $pluginRequest;
					$forward = $controllerObject->forward()->dispatch(ucfirst($plugin).'\Controller\\'.ucfirst($controller));
				}  
				if ($forward) { 
					if ($forward instanceof \Closure) {
						$pluginLoader->loadPlugin($pluginName);
						$pluginLoader->viewModel->setVariable($pluginName, function() use ($forward) {
							return $forward;
						}); 
					} else {
						$pluginLoader->loadPlugin($pluginName);
						$pluginLoader->viewModel->setVariable($pluginName, $forward);  
					}
				}
			} 
		}
	}
	
	public function __invoke($extraPluginNames)
	{ 
		$pluginLoader = $this->serviceLocator->get("Plugins");
		$controllerObject = $this->getController();
		if (! $controllerObject) {
			return;
		}
		if (is_array($extraPluginNames)) { 
			foreach ($extraPluginNames as $pluginName => $variables) {
				$pluginRequest = explode("/", $pluginName);
				$pluginRequest = preg_replace_callback('/(?<!^)([-])+([a-z])/', function ($match) {
					return (isset($match[2]) ? ucfirst($match[2]) : "" );
				}, $pluginRequest); 
				if (isset($pluginRequest[2])) {
					list($plugin, $controller, $action) = $pluginRequest;  
					$variables['action'] = $action;
					$forward = $controllerObject->forward()->dispatch(ucfirst($plugin).'\Controller\\'.ucfirst($controller), $variables);
				} elseif (isset($pluginRequest[1])) {
					list($plugin, $controller) = $pluginRequest;  
					$forward = $controllerObject->forward()->dispatch(ucfirst($plugin).'\Controller\\'.ucfirst($controller), $variables);
				}
				if ($forward instanceof \Closure) {
					$pluginLoader->loadPlugin($pluginName);
					$pluginLoader->viewModel->setVariable($pluginName, function() use ($forward) {
						return $forward;
					}); 
					return $forward; 
				} else { 
					$pluginLoader->loadPlugin($pluginName);
					$pluginLoader->viewModel->setVariable($pluginName, $forward);
					return $forward;
				} 
			}
		} 
	}
  
}