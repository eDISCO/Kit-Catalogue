<?php
/*
 *
 */
class Controller_Building extends Ecl_Mvc_Controller {



	public function beforeAction() {
		if ( (false == $this->model('app.allow_anonymous')) && ($this->model('user')->isAnonymous()) ) {

			$this->abort();
			$this->router()->action('index', 'signin');
			return;
		}

		$this->router()->layout()->addBreadcrumb($this->model()->lang['building.label.plural'], $this->router()->makeAbsoluteUri('/'. $this->model()->lang['building.route'].'/'));
	}// /method



	public function actionIndex() {
		$user = $this->model('user');

		$this->view()->buildings = $this->model('buildingstore')->findAllUsed($user->param('visibility'));

		$this->view()->render('building_all');
	}// /method



	public function actionView() {
		$user = $this->model('user');

		$category = $this->model('categorystore')->find($this->param('catid'));

		if (empty($category)) {
			$this->router()->action('error', '404');
			return true;
		}

		$this->view()->category = $category;
		$this->view()->items = $this->model('itemstore')->findForCategory($category->id, $user->param('visibility'));

		$this->view()->render('category_view');
	}// /method



	public function actionViewitem() {
		$item_name  = $this->param('itemname');
		$item_id = $this->param('itemid');

		$category = $this->model('categorystore')->find($this->param('catid'));

		$item = $this->model('itemstore')->find($item_id);


		if ( (empty($category)) || (empty($item)) ) {
			$this->router()->action('error', '404');
			return true;
		}


		if ( (KC__VISIBILITY_PUBLIC != $item->visibility) && ($this->model('user')->isAnonymous()) ) {
			$this->router()->action('404', 'error');
			return true;
		}

		$this->view()->category = $category;
		$this->view()->item = $item;

		$this->view()->render('category_viewitem');
	}// /method



/* --------------------------------------------------------------------------------
 * Private Methods
 */



}// /class
?>
