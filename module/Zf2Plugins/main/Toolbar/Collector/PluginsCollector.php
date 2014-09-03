<?php

namespace Zf2Plugins\Toolbar\Collector;

use ZendDeveloperTools\Collector\CollectorInterface;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container; 

/**
 * Session Data Collector.
 */
class PluginsCollector implements CollectorInterface
{ 
	/**
	 * all plugins which loaded with controller
	 * @var array
	 */
	protected $loadedPlugins; 
	 
    /**
     * @inheritdoc
     */
    public function getName()
    {
         // this name must same with *collectors* name in the configuration
        return 'plugins.toolbar';
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * @inheritdoc
     */
    public function collect(MvcEvent $mvcEvent)
    { 
    	$plugins = $mvcEvent->getApplication()->getServiceManager()->get('Plugins'); 
    	$this->loadedPlugins =  $plugins->loadedPlugins;
    }
    
    public function getPluginsData()
    {  
        $data['loadedPlugins']  = $this->loadedPlugins; 
        return $data;
    }
}
