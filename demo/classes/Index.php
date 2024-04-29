<?php
/**
    Home Page Display Class 

    All this class does is display the page in the browser.
    You need a similar class for each HTML page you use.
    It uses Smarty to set the page tag assignments anddisplays the page.
 */
class Index
{
    use PageClassTraits;
    
    public function __construct()
    {
        //Initialize
        $this->setup();
        $this->pageSetup();
     }      
    
    //Finish the template vars and display the page
    private function pageSetup()
    {
        $this->smarty = $this->setupSmarty();
        $this->smarty->assign('title', 'Ejax Demo'); //Assign the page title
        $this->showPage('index.tpl');          //Display the page
    }
}
