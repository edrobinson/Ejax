### Combine client server functionallity and the page rendering in one class
The Ejax trait provides a method that determines whether or not a request is available to process. Using this method, you can combine the page rendering and client services in a single class.

#### Example
```
<?php
 require '../vendor/autoload.php';

class IndexServer
{
    use EjaxTraits; //Client request handlers
    use PageClassTraits; //My page generation traits
    
    public function __construct()
    {
        if($this->canProcessRequest())
        {
          $this->processRequest(); //Process the client request
        }else{
          $this->pageSetup();
        }
    }

    //Any code that you need to display the page
	//Finish the template vars and display the page - Smarty
    private function pageSetup()
    {
        $this->smarty = $this->setupSmarty();
        $this->smarty->assign('title', 'Home'); //Assign the page title
        $this->showPage('index.tpl');          //Display the page
    }

/*************************** Client Service Methods **********************/    
    
    //Sample Service method.
    //Receive the user's name and send it back in an alert call.
    private function echoUserName($userName)
    {
        $this->ejax->alert($userName); //Create a response action
    }
    
    //Add methods to service any other client requests your page generates.
}
```
Note: The service method invokes the Ejax instance. It was created in the constructor  
when the can process returned true.   
 
Note: When the service methods finish, control is returned to the dispatcher
which calls the response method thereby completing the request/response cycle.    
  