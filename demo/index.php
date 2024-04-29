<?php
/**
 index.php
 
 Url Structure: siteurl/className[/additional options]
 
 Using Composer autoload with classmap to the classes folder:
 
     "autoload": {
        "classmap": ["../classes"],
        }
        
  Classes and class files are named the same:
  
  Someclass.php holds class Someclass.
    
  Note capitalization of file and class names.

 */
    session_start();
    error_reporting ( E_ALL ) ;
    ini_set ( 'display_errors' , 1 ) ;

    define ('CLASSES_PATH', './classes/');    
    define ('CLASS_EXT', '.php');
    require 'vendor/autoload.php';

    //Find the requested class name
    $path = $_SERVER['REQUEST_URI'];

    $_SESSION['path'] = $path;
    $pathParts = explode('/',$path);
    $className = $pathParts[2];
    
    if($className == 'demo')
    {
      $className = 'Index';
    }

    //Capitalize the class name to match the file and class names.
    //Suffix with "Page" - all page classes are like IndexPage.php
    $className = ucfirst($className);   
    
    //Does it exist>
    $classPath = CLASSES_PATH.$className.CLASS_EXT;

    if (!file_exists($classPath))
    {
      die("Index says: The class file, $classPath, does not exist.");
    }
    
    //Load the class file
    require $classPath;
    //Instance the class.
    $class = new $className;
    //All done.
    