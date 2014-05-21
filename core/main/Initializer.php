<?php

class Initializer {

	private $app;
	private $rw;

	public function __construct($app){

		$this->app = $app;
		$this->rw = new RegisterMetabox();

		$this->registerModels();
		$this->registerControllers();
		$this->registerViews();
		$this->registerOptions();

	}

	public function __destruct(){

		$this->rw->register();

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

	private function registerModels(){

		foreach($this->loadFiles('model') as $name){

			//var_dump($name);

			$instance = new $name;

			$this->app->registerModel($instance);
			$this->rw->add($instance->getPostType(), $instance->getFields());

		}

	}

	private function registerControllers(){

		foreach($this->loadFiles('controller') as $name){
			$instance = new $name($this->app);
			$this->app->registerController($instance);
		}

	}

	private function registerViews(){

		foreach($this->loadFiles('view') as $name){
			$instance = new $name;
		}

	}

	private function registerOptions(){

		foreach($this->loadFiles('option') as $name){

			$instance = new $name;

			$this->app->registerOption($instance);

		}

	}

}