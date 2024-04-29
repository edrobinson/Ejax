#### Ejax in a Nutshell   

##### Client Side:  

1. Script in the Ejax.js file:  
```
<script src="assets/Ejax/src/Ejax.js"></script>
```  
2.	In your JS, create an instance of Ejax:  
```
var ejax = new Ejax(theUrl);
```  
3. Call a server side method:  
```
function sayWelcome(){
ejax.request(theUrl, 'welcomeMessage','Welcome to Ejax.');
}
```  
4. The server responds with one or more commands to ejax which the response handler in the ejax code processes them and changes to the display are made.  

##### Server Side:  
1. The server loads the requested file that contains a class that is instanced by code in the file. The class uses the EjaxTraits.  
2. The class constructor invokes the processRequest trait method:
```
class IndexServer
{
    use EjaxTraits; //Client request handlers
    
    public function __construct()
    {
        $this->processRequest(); //Process the client request - see EjaxTraits
    } 
...
```  
3. The request data is saved, the Ejax class is instanced and the   dispatcher trait is invoked:
```
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
```  
4. The dispatcher extracts the called method name and the passed arguments, determines if the method exists and is public and, if so, calls the method. When the method returns the Ejax sendResponse method is called to send the the result to the client:
```
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
##### Back on the Client:  
1. The request completes with the receipt of the response JSON which is forwarded to the handleResponse method:
```
  handleResponse(actions) {
    actions.forEach((action) => {
      let method = action.action;  //Who to call
      let args      = action.params; //Arguments
      this[method](args); 				//Call the method thru the method var
    })
  }
```
HandleResponse iterates over the response command array calling each Ejax method requested resulting in the requested changes to the state of the page.  
  
That's it...


