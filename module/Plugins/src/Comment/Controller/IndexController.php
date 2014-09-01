<?php
namespace Comment\Controller;
  
use Comment\Model\CommentTable; 
use Comment\Form\CommentForm;
use Zend\View\Model\ViewModel; 
use Zend\Mvc\Controller\AbstractActionController;
 
class IndexController extends AbstractActionController 
{ 
	public function renderViewAction()
	{   
		/// sample ViewModel method
		$request = $this->getRequest();
		$postData = $request->getPost();
		$view['postData'] = $postData;
		return new ViewModel($view);
	}
	
	public function renderClosureAction()
	{
		$view['someData'] = 'data'; 
		return function($paramsFromMainView = false) use ($view) {
			if ($paramsFromMainView == 'ViewModel') {
				$viewModel = new ViewModel($view);
				$viewModel->setVariable('mainViewParam', $paramsFromMainView); 
				$viewModel->setTemplate("comment/index/render-closure.phtml");
				return $viewModel;
			} elseif ($paramsFromMainView == 'Array') {
				return array(
					'someArray',
					'someOtherArray'
				);
			} elseif ($paramsFromMainView == 'String') {
				return 'someString';
			}
		};
	}
	
	public function ajaxAction()
	{
		$this->layout("layout/empty");
		$request = $this->getRequest();
		$view 	 = array();
		if ($request->isPost()) {
			$postData = $request->getPost(); 
			if (isset($postData['ajaxVariable'])) {
				$view['ajaxVariable'] = $postData['ajaxVariable'];
			}
		}
		return new ViewModel($view);
	}
}