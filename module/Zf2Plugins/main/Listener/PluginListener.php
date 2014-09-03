<?php
namespace Zf2Plugins\Listener;
 
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response as HttpResponse;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Application as MvcApplication;
use Zend\EventManager\EventManagerInterface;

/**
 * EVENT_BOOTSTRAP => module && controller && !action
 * EVENT_DISPATCH_ERROR => !module || !controller
 * EVENT_ROUTE => module
 * EVENT_DISPATCH => !action
 * EVENT_RENDER => *  note:after rendering controller
 * EVENT_RENDER_ERROR => !(view file)
 * EVENT_FINISH => !module || !controller || !action
 * @author mbrostami <mb.rostami.h@gmail.com> 
 */

/* @var $enablePlugins \Zf2Plugins\PluginService\ControllerPluginManager */

class PluginListener implements ListenerAggregateInterface
{
	/**
	 * @var \Zend\Stdlib\CallbackHandler[]
	 */
	protected $listeners = array();
	protected $events;  
	
	public function attach(EventManagerInterface $events, $priority = 1)
	{
		$this->events	   = $events;   
		$this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), -70); 
		$this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -70); 
	}

	/**
	 * Detach all our listeners from the event manager
	 *
	 * @param  EventManagerInterface $events
	 * @return void
	 */
	public function detach(EventManagerInterface $events)
	{
		foreach ($this->listeners as $index => $listener) {
			if ($events->detach($listener)) {
				unset($this->listeners[$index]);
			}
		}
	} 
	 
	public function onRoute(MvcEvent $event)
	{
		$response   = $event->getResponse();  
		$routeMatch = $event->getRouteMatch(); 
		if ($routeMatch) {
			$request = strtolower($routeMatch->getParam("controller"));
			$request = str_replace("\\", "-", $request);
			$request = str_replace("controller-", "", $request);
			$action  = $routeMatch->getParam("action");
			$request = strtolower($request."-".$action);  
		} else {
			return ;
		} 
		$serviceManager = $event->getApplication()->getServiceManager(); 
		$plugins = $serviceManager->get("Plugins");
		$plugins->autoLoad($serviceManager, $request); 
		$plugins->setEvent($event);
		return;
	}
	  
	public function onRender(MvcEvent $event)
	{
		$serviceManager = $event->getApplication()->getServiceManager(); 
		$controllerPluginManager = $serviceManager->get("ControllerPluginManager");

		$enablePlugins = $controllerPluginManager->get("enablePlugins"); 
		$enablePlugins->autoloadPlugins(); 
	}
}