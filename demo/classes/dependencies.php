<?php

/*
  Pimple container for the site code's dependencies.
*/
use Jaxon\Jaxon;
use Jaxon\Response\Response;

require "assets/vendor/autoload.php";

class Dependencies
{
    public $injector; //Pimple\Continer instance

    //The constructor builds all of the definitions
    public function __construct()
    {
        $this->injector = new \Pimple\Container();
        $this->init();
    }
    
    //Fill the container...
    private function init()
    {
        //Site routes
        $this->injector["routes"] = function ($c) {
            require "assets/includes/routes.php";
            return $routes;
        };

        //Site router
        $this->injector["router"] = function ($c) {
            require "classes/Router.php";
            $router = new Router($c["routes"]);
            return $router;
        };

        //Site DB Credentials
        $this->injector["dbservername"] = "localhost";
        $this->injector["dbusername"] = "root";
        $this->injector["dbpassword"] = "";
        $this->injector["dbname"] = "sddb";

        //Site database connection.
        $this->injector["db"] = function ($c) {
            $host = $c["dbservername"];
            $dbname = $c["dbname"];

            try {
                $db = new PDO(
                    "mysql:host=$host;dbname=$dbname;",
                    $c["dbusername"],
                    $c["dbpassword"]
                );
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db;
            } catch (PDOException $e) {
                return false;
            }
        };

        //Smarty Templating engine
        //Instantiates Smarty and sets the folders.
        $this->injector["smarty"] = function ($c) {
            $smarty = new Smarty();

            $smarty->setTemplateDir("smarty-templates");
            $smarty->setCompileDir("smarty-templates/templates_c");
            $smarty->setCacheDir("smarty-templates/cache");
            $smarty->setConfigDir("smarty-templates/configs");
            $smarty->force_compile = true; //Make false for production
            //Do the common assigns
            $smarty->assign("year", date("Y")); //Date year for footers
            $smarty->assign("footertext", $c['config_server']->footerText);
            return $smarty;
        };

        //Jaxon Main object
        $this->injector["jaxon"] = function ($c) {
            return new Jaxon();
        };

        //Jaxon Response object
        $this->injector["jaxon_response"] = function ($c) {
            return new Response();
        };

        //Site config server
        $this->injector["config_server"] = function ($c) {
            require "classes/ConfigServer.php";
            return new configurationServer($c["db"]);
        };

        //CRUD Server class object
        $this->injector["crud_server"] = function ($c, $table) {
            require "classes/Crud.php";
            $crud = new Crud($table);
            return $crud;
        };

        //A couple of items for the Books page class.
        //The Smarty template
        $this->injector["book_template"] = "book.tpl";
        //The folder holding the book cover images
        $this->injector["book_image_path"] = "images/books/";
    }

    /****************** Public "Getters" ************************/

    //General get function
    public function get($key)
    {
        return $this->injector[$key];
    }

    //Instance of Router
    public function getRouter()
    {
        return $this->injector["router"];
    }

    //Instance of Smarty
    public function getSmarty()
    {
        return $this->injector["smarty"];
    }

    //Instance of the DataBase connection
    public function getDB()
    {
        return $this->injector["db"];
    }

    //Instance of Jaxon PHP Main class
    public function getJaxon()
    {
        return $this->injector["jaxon"];
    }

    //Instance of Jaxon Response class
    public function getJaxonResponse()
    {
        return $this->injector["jaxon_response"];
    }

    //Instance of the site configuration server
    public function getConfigServer()
    {
        return $this->injector["config_server"];
    }

    //Where the book cover images live
    public function getBookImagePath()
    {
        return $this->injector["book_image_path"];
    }

    //The template for individual books.
    public function getBookTemplate()
    {
        return $this->injector["book_template"];
    }
    
    //The CRUD class
    public function getCrud($table)
    {
      require 'classes/Crud.php';
      $crud = new CRUD($table);
      return $crud;
    }
}
