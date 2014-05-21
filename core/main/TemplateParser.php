<?php

class TemplateParser{

	private $app;

	public function __construct($app){

		$this->app = $app;
		$this->detectPage();

	}

	private function detectPage(){

		if(is_home()){
			$this->renderIndex();
		}

	}

	private function renderIndex(){

		if(!empty($this->app->controller->index)){
			echo $this->app->controller->index->render();
		}

	}

}