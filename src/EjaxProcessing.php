<?php
/*
  File: Ejax/EjaxProcessing.php
  
  This file contains 3 functions that drive all of the request/response processing
  for your service scripts.
  
  If your server side scripts do not define classes, require this file  and simply
  call processRequest().
  
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
    
    $reqData = '';    //The request data
    $ejax = '';       	//Instance of Ejax

    /**
     * This method determines if there is a request available
     * or not based on the existance of $_POST.
     * @return boolean true if we can process a request.
     *
     * The function is used by the processRequest function below.
     */
    function canProcessRequest()
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
    function processRequest()
    {
        if($this->canProcessRequest())
        {
            $ejax    = new Ejax();                    
            $reqData = json_decode(file_get_contents("php://input"));
            dispatcher($ejax, $reqData);
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
    function dispatcher($ejax, $reqData);
    {
        $sFunction     = $reqData[0];        //Requested function
        $aArguments = $reqData[1];      //Method arguments

        //Assure that the function exists 
		//and that it is user defined.
		try{
			$func = new ReflectionFunction($sFunction)
			if(!$func->isUserDefined)
			{
				$ejax->alert("Error. Function $sFunction s not available.");
				$ejax->sendResponse();
			}
		}catch (Exception $e) {
			$ejax->alert("Error. Function $sFunction s not available.");
			$ejax->sendResponse();
		}
        //Complete the request.
        $sFunction($aArguments);         //Invoke the method
        $ejax->sendResponse();         	 //Send the response when the method completes.
        }  
    }
