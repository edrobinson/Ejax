### EjaxTraits Documentation

The project provides a PHP trait that handles the complete
request and response processes.

Use the trait in your classes to service client side requests.

```
<?php
/*
  File: Ejax/EjaxTraits.php
  
  This trait contains 3 methods that drive all of the request processing
  for your service classes:
  
  1. canProcessRequest() determines if there is a request available to process
     based on the presence of the $_POST global. Returns true or false.
     
  2. processRequest() reads the PHP input stream to get the raw request data.
     Then it json_decodes raw data and calls the dispatcher.
     
  3. dispatcher() extracts the requested method and its arguments, 
     makes sure the method is calllable in your class then calls the metdod
     and returns the response to the client.  
*/

/*
  This trait is what drives your server classes by
  detecting a request, routing it to the proper method
  and returning the response to the client.
  
  In your server class constructor just call processRequest().
*/
trait EjaxTraits
{
    
    public $reqData;    //The request data
    public $ejax;       	//Instance of Ejax

    /**
     * This method determines if there is a request available
     * or not based on the existance of $_POST.
     * @return boolean true if we can process a request.
     *
     * The function is used by the processRequest function below.
     */
    public function canProcessRequest()
    {
      return (isset($_POST)) ? true : false;
    }
        
    /**
     * This method starts the request handling:
     * 1. If a request is available:
     *    1. Instance Ejax.
     *    2. Read and decode the client request data.
     *    3. Invoke the dispatcher to complete the request.
     *
     * 2. Otherwise, it's an error so echo a message about it.
     */
    public function processRequest()
    {
        if($this->canProcessRequest())
        {
            $this->ejax    = new Ejax();                    
            $this->reqData = json_decode(file_get_contents("php://input"));
            $this->dispatcher();
        }else{
          echo('No request data received - please contact support.');
          die;
        }
    }
    
    /**
     * This function completes the request as follows:
     * 1. Extract the method and arguments from the request data.
     * 2. Check that the requested method is part of this class and is public.
     * 3. Invoke the method.
     * 4. Send the response.
     */
    public function dispatcher()
    {
        $sMethod    = $this->reqData[0];        //Requested method
        $aArguments = $this->reqData[1];      //Method arguments
        
        //Assure that the method exists in this class
		//and that it is pubic
        try {
            $ref = new ReflectionMethod($this, $sMethod);		//Does the method exist?
            if (!$ref->isPublic()) //Is it public?
			{
               throw new RuntimeException("The called method is not public.");
			}
        } catch (Exception $e) {
            $this->ejax->alert("Error: Method $sMethod is not available in this service.");
            $this->ejax->sendResponse();
        }
        //Complete the request.
        $this->$sMethod($aArguments);         //Invoke the method
        $this->ejax->sendResponse();         	 //Send the response when the method completes.
        }  
    }
```