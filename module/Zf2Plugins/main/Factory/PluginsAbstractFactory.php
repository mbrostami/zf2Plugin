<?php 
namespace Zf2Plugins\Factory;

use Zend\ServiceManager\AbstractFactoryInterface; 
use Zend\ServiceManager\ServiceLocatorInterface;  
use Zf2Plugins\PluginService\PluginLoader;

class PluginsAbstractFactory implements AbstractFactoryInterface
{
	public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
	{  
		switch ($requestedName)
		{ 
			case "Plugins":
				return true;
		} 
	}

	public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
	{  
		switch ($requestedName)
		{ 
			case "Plugins": 
				return new PluginLoader();
		} 
	}
	 
}