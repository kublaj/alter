<?php

class TemplateParser{

	private $app;

	public function __construct($app){

		$this->app = $app;
		$this->detectPage();

	}

	private function loadController($controller){

		$this->registerControllers();
		return $this->app->controller->{$controller};

	}

	private function loadFiles($folder){

		$files = array();

		foreach(glob( ALTER_APP.'/'.$folder.'/*.php') as $file){

			$name = str_replace('.php', '', $file);
	        $name_arr = explode('/', $name);
	        $name = $name_arr[count($name_arr) - 1];	        

	        require $file;

	        array_push($files, $name);

		}

		return $files;

	}

	private function registerControllers(){

		foreach($this->loadFiles('controller') as $name){
			$instance = new $name($this->app);
			$this->app->registerController($instance);
		}

	}

	private function detectPage(){

		if(is_home()){
			$this->renderIndex();
		}

	}

	private function renderIndex(){

		$controller = $this->loadController('index');		

		if($controller){
			echo $controller->render();
		}

	}

}