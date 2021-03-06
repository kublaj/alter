<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/07/14
 * Time: 14:46
 */

/**
 * Class Loader
 *
 * This class load all the user files
 */
class Loader
{

	private $app;
	private $folders = array('model', 'controller', 'view', 'option');

	function __construct($app)
	{
		$this->app = $app;
        $this->rw = new RegisterMetabox();
		$this->load();
        $this->rw->register();
	}

	private function load()
	{

		// User Models, Views and Controllers

		foreach ($this->folders as $folder) {
            foreach (glob(THEME_ABSOLUTE_PATH . '/' . $folder . "/*.php") as $file) {
                if(!empty($file)):
                    $this->loadFile($file);
                endif;
            }
		}

        if(empty($this->app->post)){
            $this->loadFile(ALTER . 'src/core/default/PostModel.php');
        }

        if(empty($this->app->page)){
            $this->loadFile(ALTER . 'src/core/default/PageModel.php');
        }

	}

    private function loadFile($file){

        try{

            $name = str_replace('.php', '', $file);
            $name_arr = explode('/', $name);
            $name = $name_arr[count($name_arr) - 1];;

            require $file;

            if(!class_exists($name)){
                throw new InvalidArgumentException("The class " . $name ." cannot be found, please check if the file and class name is correct");
            }

            $instance = new $name;

            // Register the meta-boxes if is a model
            if (is_subclass_of($instance, 'AppModel')) {
                $this->app->registerModel($instance);
                $this->rw->add($instance->getPostType(), $instance->getFields());
            }

            if (is_subclass_of($instance, 'OptionPage')) {
                $this->app->registerOption($instance);
            }

        }catch(InvalidArgumentException $e){
                trigger_error($e->getMessage(), E_USER_WARNING);
        }

    }

} 