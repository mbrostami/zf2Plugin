<?php
namespace Sample\Controller;
  
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
class IndexController extends AbstractActionController 
{
  	public function indexAction()
  	{
  		/// Optional 
  		/// If you want to enable plugin manualy you can use these lines {
	  		$params = array();
	  		$this->enablePlugins(array(
	  			'comment/index/render-closure' => $params
	  		));
	  	/// }
  		return new ViewModel();
  	}
}