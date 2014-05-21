<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 02/05/14
 * Time: 10:41 PM
 */

add_theme_support( 'post-thumbnails' );

// Constants
define('__DIR__', realpath(dirname(__FILE__)));
define('THEME_PATH', get_bloginfo('template_url'));
define('ALTER', THEME_PATH . "/alter/");
define('ALTER_APP', __DIR__ . "/../../app");
define('RWMB_URL', ALTER . "vendor/meta-box/" );

// Assets constants
if(!defined('ALTER_IMG')) define('ALTER_IMG', THEME_PATH . "img/");
if(!defined('ALTER_IMG')) define('ALTER_CSS', THEME_PATH . "css/");
if(!defined('ALTER_IMG')) define('ALTER_JS', THEME_PATH . "js/");

// Importa os vendors
require __DIR__."/../vendor/meta-box/meta-box.php";
require __DIR__."/../vendor/Wordpress-for-Developers/lib/load.php";

require_once __DIR__."/../vendor/Twig/Autoloader.php";
Twig_Autoloader::register();

// ---- Import framework Classes

// Exceptions
require_once  __DIR__."/exceptions/NoPostFoundException.php";

// Builders
require_once  __DIR__."/builders/AdminPage.php";
require_once  __DIR__."/builders/AppModel.php";
require_once  __DIR__."/builders/AppController.php";
require_once  __DIR__."/builders/AppTaxonomy.php";
require_once  __DIR__."/builders/OptionPage.php";
require_once  __DIR__."/builders/Post.php";

// Util
require_once  __DIR__."/util/Helper.php";
require_once  __DIR__."/util/RegisterMetabox.php";

// Main
require_once  __DIR__."/main/App.php";
require_once  __DIR__."/main/Initializer.php";
require_once  __DIR__."/main/TemplateParser.php";


// Initialize the app
global $app, $h;
$app = new App();
$h = new Helper();

new Initializer($app);