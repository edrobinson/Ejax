### Ejax - Quick and Simple Client/Server Tool  
Ejax provides tools send a request to the server and receive and the response using Javascript and PHP.  
The response contains commands to update page without refreshing the page.  

Nothing new. Xajax did it several years ago and Jaxon does it today. The difference is that Ejax does it with only 3 files and less than 1000 lines of heavily commented code.

#### The code
 - Ejax.js contains the client side Javascript code that makes requests and receives and processes the response from the server.
 - Ejax.php contains the server side code that receives the client requests and invokes methods in your classes that generate commands or "actions" to be processed in the client code.
 - EjaxTraits.php contains a few methods to detect the client request, dispatch the requesterd service in your class and invoke the response method in the Ejax class. The traits handle the entire request/response cycle.
 
 #### Installation  
 At present there is only the zip download which you extract into your project. If it looks like the project will get some usage, I'll add it to Packagelist to use with Composer.
 
 #### Usage
 ##### Server Side
 You must use PHP classes server side. Your classes use the EjaxTraits like this:
```
require 'Ejax/src/Ejax.php';        //The main client side class
require 'Ejax/src/EjaxTraits.php';  //The traits the handle the requst and response

class IndexServer
{
    use EjaxTraits;
    
    public function __construct()
    {
        $this->processRequest();
    } 
    
    ... Add your callable methods here. They must be public ...
}    
``` 

That is the essence of Ejax server classes.    

I usually use Smarty templating and other packages in my projects so I setup Composer autoloading which excludes the requires.  

Here's the processRequest method:
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
It instantiates the main Ejax class, gets the client data and calls the dispatcher method in the traits.

Here is the dispatcher:
```
    public function dispatcher()
    {
        $sMethod    = $this->reqData[0];         //Requested method
        $aArguments = $this->reqData[1];      //Method arguments
        
        //Assure that the method exists in this class.
        try {
            $ref = new ReflectionMethod($this, $sMethod);
        } catch (Exception $e) {
            $this->ejax->alert("Error: Method $sMethod is not available in this service.");
            $this->ejax->sendResponse();
        }
        
        //Assure that it is a public method
        try{
            if (!$ref->isPublic()) {
              throw new RuntimeException("The called method is not public.");
            }         
        }catch (Exception $e) {
            $this->ejax->alert("Error: Method $sMethod is not available in this service.");
            $this->ejax->sendResponse();
        }
        
        //O.K. Complete the request.
        $this->$sMethod($aArguments);         //Invoke the requested method.
        $this->ejax->sendResponse();          //Send the response when the method completes.
        }  
    }
```
Dispatcher extracts the name of the method to call and its arguments. It then determins that the method is part of the current class and that it is public.  
If both test pass, it calls the method and sends the response when it returns. If either fails, it sends an alert to the client letting the user know there was a problem with the request.

Here is a simple PHP class that handles client side requests:
```
<?php
/**
 *  Ejax server side demonstration class.
 *  Called from the home page of the demo project.
 *
 *  The constructor calls the processRequest trait and
 *  and it handles all of processing:
 */

 require '../vendor/autoload.php';

class IndexServer
{
    use EjaxTraits; //Client request handlers
    
    public function __construct()
    {
        $this->processRequest(); //Process the client request - see EjaxTraits
    } 
    
    /******************************** Begin Client Service Methods ***************************/

    //Client calls this sending a message string.
    //Function wraps it in H4 tag and assigns it to the client "target" div.
    public function welcomeMessage($message)
    {
        $s = "<h4>$message</h4>";                         //Wrap the message
        $this->ejax->assign('target', 'innerHTML', $message); //Assign it to the element of id "target"
    }

    /*
      This function receives a Jaxon request from the client
      and makes an img tag which it sends back with  command
      to insert it into the target div.
    */
    public function getImage()
    {
        $s = "<image src='assets/img/idaho.jpg'width='500' height='500'/>";

        $this->ejax->assign('target', 'innerHTML', $s);
        $this->ejax->removeCSS('message','border');
    }

    /*
      Process a form:

      Receive a form's fields from the client,
      extract it and format a reply.
      Return the reply to the client Jaxon code
      which assigns the string to the target div.
    */
    public function processForm($data)
    {
        //Decode the form data into an assoc array.
        $datalist = json_decode($data,true);
        //Extract to assoc array
        $aVals = [];
        
        for($i=0;$i<count($datalist); $i++)
        {
          $aItem = $datalist[$i];
          $key = $aItem['name'];
          $val = $aItem['value'];
          $aVals[$key]=$val;
          
        }
        
        //Construct the response message.
        $s = '<p>';

        $s  = 'Name:....'. $aVals['name'].'<br/>';
        $s .= 'Email:...'. $aVals['email'].'<br/>';
        $s .= 'Website:.'. $aVals['website'].'<br/>';
        $s .= 'Subject:.'. $aVals['subject']. '<br/>';
        $s .= 'Message:.'. $aVals['message'].'<br/>';

        $s .= '</p>';

        //Assign the string to the #target div.
        $this->ejax->assign('target', 'innerHTML', $s);
    }
    
    //Alert first and last name from the args
    public function info2($args)
    {
      $args = json_decode($args, true);
      $firstName = $args['firstName'];
      $lastName  = $args['lastName'];
      
      $s = "Hello, $firstName $lastName. How goes it?";
      $this->ejax->alert($s);
    }
}
//End of class definition

//Instance the class - IMPORTANT!!! nothing will happen if the class isn't instanced...
$idc = new IndexServer();

//End of file
```

### This is a list of all of the Ejax php class's action methods that you can call from your server classes.
| Command                                                                       | What it Does                                 |
|-------------------------------------------------------------------------------|----------------------------------------------|
| alert(string $sMessage)                                                      | Display an alert                             |
| assign(string $id, string $attribute, string $msg)                           | Set an elements text                         |
| call($sMethod, $Params=)                                                     | Call a JS function of yours                  |
| addCSS($id, $prop, $value)                                                  | Add a CSS style                              |
| removeCSS($id, $prop)                                                       | Remove a CSS style                           |
| append($id=, $prop=, $value=)                                               | Append a property to an element              |
| prepend($id, $prop, $value)                                                 | Prepend a property to an element             |
| replace($id, $prop, $search, $value)                                         | Search an replace in an elements text        |
| clearAttr($id, $prop)                                                        | Remove an attribute                          |
| create($id, $parent, $tag, $position)                                        | Create a new element before or after another |
| remove($id)                                                                  | Remove an element from the DOM               |
| script($sJsCode)                                                             | Execute a piece of JS code                   |
| setAttribute(string $id, string $name, string $value)                        | Sets an attribute of an element              |
| removeAttribute(string $id, string $name) Removes an attribute of an element | Removes an attribute of an element           |
| userFunc(args)                                                               | Call a function in the global space          |

There are more actions that can be implemented but I felt that these were the most commonly used.  
Feel free to suggest additional functionality.  
The functions invoke a similarily named JS function on the client that carries out the action to update the page.
##### Client Side:
The file Ejax/src/Ejax.js provides a Javascript "class" that sends requests to the server and process the response actions.  
The first method in the class is "request." It is an async function that uses the fetch api to send the request, receive and save the response and call the response handler, handleResponse() method.  
handleResponse iterates over the received actions array and calls each service method.  
I generally write a JS script specific to each page that are called from within the HTML using onClick or whatever is called for.  
You are not limited to calling the Ejax methods. You can also call functions in your own JS using the Ejax "call" method.  

Here's an example of a JS file:
```
/**
  Index Page Javascript to demonstrate the Ejax
  client side functionallity.
  
  Study Ejax.js to understand the client side services.

 */
//The url on the server
var theUrl = 'classes/IndexServer.php';

//Instance the client side Ejax class
var ejax = new Ejax(theUrl);

//Call the 'welcomeMessage' method in Index_c class
//The method creates an HTML string containing the 
//welcome message and inserts it into the div with id "target'.
//It also calls the additionalActions method to test other service
function sayWelcome(){
  ejax.request(theUrl, 'welcomeMessage','Welcome to Ejax.');
}

//Call getImage in index.php.
//The method creates an image tag and inserts it into the target div.
function getImage(){
  ejax.request(theUrl, 'getImage', '');
}

//Send the contact form to the server.
//It sends the info back in the target div
function submitForm() {
  data = $('#form1').serializeArray();
  var formData  = JSON.stringify(data);            
  ejax.request(theUrl, 'processForm', formData);
}

//Called from the server by the Ejax call method
function info(msg)
{
  alert(msg);
}

//Same as info except the args var is an array
function info2(args)
{
  let data = JSON.stringify(args);
  let firstName = args['firstName'];
  let lastName  = args['lastName'];
  let msg = "Hello " + firstName + ' ' + lastName;
  alert(msg);
}

function btnClick()
{
  alert('This message is from the button click');
  return false;
}

//Run the testing select change from the client
//Sends a request do a particular action
function runTest()
{
  var methodName = document.getElementById("TestSelect").value;
  var targetId   = document.getElementById("sfield").value; 
  jparam = JSON.stringify({"methodName":methodName, "targetId":targetId});
  if(methodName != '')
  {
    ejax.request(theUrl, 'runTest', jparam );
    document.getElementById('TestSelect').selectedIndex = 0; //Reset the select box
  }
}
```
If you have a very simple page you might prefer to just add a script tag to contain the function/s you need.  
In any event you need to, at minimum, instance the Ejax class i.e var ejax = new Ejax(theUrl);  
"theUrl" is the URL of the server side PHP containing your class and is used by the Ejax class request method...

### Client Page Loading
I haven't addressed loading your pages onto the client. Most projects I have seen create one class to load the page and another to service the client requests. With Ejax, you can accomplish both from a single class.  
The traits contain a small function named "canProcessRequest" that checks to see if a request is available from the client. If so, it returns true otherwise false. You can take advantage of this in your class constructor by calling canProcessRequest and either processing the request or loading the page like this:

```
    public function __construct()
    {
        if ($this->canProcessRequest())
        {
          $this->processRequest();
        }else{
          $this->yourPageLoadCode(); 
        }
    } 
```
Using this hack, your index.php loads and instances the same class as the client uses.

An alternate, if you already have page classes, is to use the procedure explained here and instance the page class in yourPageLoadCode.

My preference is to put everything in one class as it simplifies things a bit and reduces the number of files I have to keep track of.

### Demo Project
The download includes a demo project to aid you in learning Ejax.  

In my WAMP setup I have the Ejax folder in the www folder and I run the demo with:  
 **localhost/Ejax/demo**  
 
 This displays the page. Use the dropdowns to run tests if the various commands available using the "Run Selected Test" button.  
 The "Click for Welcome"  button displays a welcome message.  
 The "Click for Image" displays an image.
 
 The assets/js/index.js file services the buttons.
 
 The classes/IndexServer.php serves all of the requests.
 
 

