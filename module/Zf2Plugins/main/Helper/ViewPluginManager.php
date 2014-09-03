<?php
namespace Zf2Plugins\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Traits\HelperTrait; 
use Zend\View\HelperPluginManager;
use Zend\View\Model\ViewModel;
  

class ViewPluginManager extends AbstractHelper
{
 
	/**  
	 * @var \Zf2Plugins\PluginService\PluginLoader
	 */
	protected $pluginLoader;
	
	public function __construct(HelperPluginManager $helperPluginManager)
	{
		$this->serviceLocator = $helperPluginManager->getServiceLocator();
		$this->pluginLoader = $this->serviceLocator->get("Plugins"); 
	}

	/**
	 * Get exists plugin (in controller) or get view html of the given pluginName
	 * @param string $pluginName
	 * @param mixed $variables
	 * @param string $checkExists
	 * @return Ambigous <string, unknown>|string|\Zend\View\Model\ViewModel|Ambigous <\Closure, \Zend\View\Model\ViewModel>
	 */
	public function __invoke($pluginName, $variables = false, $checkExists = true)
	{   
		$layoutObject = $this->pluginLoader->viewModel;  
		$pluginNameTemp = preg_replace_callback('/(?<!^)([-])+([a-z])/', function ($match) {
			return (isset($match[2]) ? $match[2] : "" );
		}, $pluginName);   
		$pluginNameTemp = $pluginName;
		/// Check loaded plugins from controller (autoload | manualy)
		if ($checkExists && isset($layoutObject->$pluginNameTemp)) { 
			if ($variables !== false) {
				$plugin = $layoutObject->$pluginNameTemp;
				if ($plugin instanceof \Closure) {
					$viewModel = $plugin($variables);
					if ($viewModel instanceof ViewModel) {
						return $this->getView()->render($viewModel, $variables);
					} else {
						return $viewModel;
					}
				} elseif ($plugin instanceof ViewModel) { 
					$html = $this->getView()->render($plugin, $variables); 
					return $html;
				} else {
					return $plugin;					
				}
			} else { 
				$plugin = $layoutObject->$pluginNameTemp;
				if ($plugin instanceof \Closure) {
					$viewModel = $plugin();
					if ($viewModel instanceof ViewModel) {
						return $this->getView()->render($viewModel);
					} else {
						return $viewModel;
					}
				} elseif ($plugin instanceof ViewModel) { 
					$html = $this->getView()->render($plugin); 
					return $html;
				} else {
					return $plugin;					
				}
			}
		}  
		return $this->getView()->render(strtolower($pluginName)); 
	}
	  
}