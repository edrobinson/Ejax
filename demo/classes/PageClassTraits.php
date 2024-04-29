<?php

/* 
  Page Classes Traits

  Functions common to all of the site page generation classes.
  
*/

trait PageClassTraits
{
    private $di;        //Dependancy injector
    private $db;        //Database connection
    private $smarty;    //Smarty instance
    private $config;    //Project configuration class instance
    private $className; //Class name from the request URI
    private $crud;      //Instance of the crud class

    /**
     * Functionallity common to all of the page class constructors.
     *
     * @return void
     */
    private function setup()
    {
        //$this->ejax = new Ejax();
        
    }


    /**
     * Sanitize user input using htmlspecialchars
     */
    private function sanitize($input) 
    {
        if(is_array($input)) :
            foreach($input as $key=>$value):
                $result[$key] = $this->sanitize($value);
            endforeach;
        else:
            $result = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        endif;

        return $result;
    }

    /**
     * Load the page content template assigning it to the content tag
     * and display the page wrapper template.
     */
    private function showPage($template)
    {
        $this->smarty->assign('content', $this->smarty->fetch($template));
        $this->smarty->display("pageSkeleton.tpl");
    }
    
    /**
     * Instance Smarty and setup directories.
     * Assign the footer date and text.
     */
    private function setupSmarty()
    {
      $smarty = new Smarty();

      $smarty->setTemplateDir("smarty-templates");
      $smarty->setCompileDir("smarty-templates/templates_c");
      $smarty->setCacheDir("smarty-templates/cache");
      $smarty->setConfigDir("smarty-templates/configs");
      $smarty->force_compile = true; //Make false for production
      
      //Do the common assigns
      $smarty->assign("year", date("Y")); //Date year for footers
      $smarty->assign("footertext", 'All rights reserved.');
      return $smarty;
      
    }
}
