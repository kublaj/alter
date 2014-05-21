<?php

class AppController{

	protected $app;
	protected $view;
	protected $template;

	public function __construct($app){
		
		$this->app = $app;
		$this->register($app);
		$this->getView();

	}

	protected function getView(){

		$className = str_split(str_replace('Controller', '', get_class($this)));
		$templatefile = '';
		$c = 0;

		foreach($className as $letter){

			if(ctype_upper($letter) && $c > 0){
				$templatefile .= '-'.strtolower($letter);
			}else{
				$templatefile .= strtolower($letter);
			}

			$c++;

		}

		if(file_exists(ALTER_APP.'/view/'.$templatefile.'.html')){
			$this->template = file_get_contents(ALTER_APP.'/view/'.$templatefile.'.html');
		}else{
			throw new Exception('The view for the '.get_class($this).' was not found!');
		}		

	}

	public function render(){

		switch ($this->app->template_engine) {
			
			case 'twig':
				
				$retorno = $this->renderTwig();

				break;
						
		}

		return $retorno;

	}

	private function getAttributes(){

		$attributes = array();
		$notAllowed = array('app', 'view', 'template', 'protected', 'private');

		foreach($this as $key => $value) {
    		
			if(!in_array($key, $notAllowed)){
				$attributes[$key] = $value;
			}

		}

		return $attributes;

	}

	private function renderTwig(){

		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader);

		return $twig->render($this->template, $this->getAttributes());

	}

}