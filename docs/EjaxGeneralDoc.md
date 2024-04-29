## Ejax General Documentation 

The Ejax project provides a simple tool set similar to
Xajax and Jaxon but much less complicated.  
  
It enables communication between your web pages and a PHP class on the server.  
The client code is a Javascript class that enables sending requests for services from the classes on the server and receiving and processing responses from that class.  
The server side code consists a PHP class and a single file of PHP traits that you use to process requests.

Your service classes provide methods that are called from the client to provide things like providing CRUD database services or changing CSS styling on a DOM element based on some condition..

There is no convoluted code. Just 3 source files.

### Installation:

Download the Zip file and unzip into a location accessible to your project. 

### Usage:

#### Client side:
 1. Script Ejax.js into your web pages sometime after Jquery. 
```<script src="assets/Ejax/Ejax.js"></script>``` 
 2. If you write seperate JS rather than script for your pages,  
 script it in after the Ejax js file.
 ```<script src="assets/js/index.js"></script> <- Optional
    Otherwise embed your js code to call the server methods.
 ``` 
 
### The js you write calls the request method in Ejax.js
#### Here's a sample of a call to the server:
```
function sayWelcome(){
  ejax.request(theUrl, 'welcomeMessage','Welcome to Ejax.');
}
```
This function calls the WelcomeMessage method  in the demo service class and  
the server returns an assign action that places its the message into  
the "target" div tag,  

 3. Invoke the methods with event handlers in the HTML. 

 Your server side class uses the Ejax PHP class and EjaxTraits to receive, process and respond to the request.
#### Server side:
You provide a service in the form of a PHP class which uses the EjaxTraits. Your class provides public methods which will be invoked by the client requests.

Your class constructor does anything it needs to to then invokes $this->processRequest() which loads the request data and calls the method specified then sends the response back.
```
Example:
class myPageClass
{
  use EjaxTraits;
  
  public function __constructor()
  {
    ... Do any initialization you require ...
    $this->processRequest();
  }
  
  //This function provides some action that the clients wants.  
  //Within someService will be calls to Ejax service methods that  
  //will end up as commands in the client Ejax JS. Each of the service  
  //methods adds an action command to the response array.
  public function someService(args)
  {
      ... Do What it Does..
  }
}
```
When someService() completes control reverts to the dispatcher which invokes the Ejax sendResponse method sending your commands to the client.  

That's about all there is to it.

Note, Ejax doesn't provide anything to help writing you web pages.  
You can, however, use the canProcessRequest() trait to see if there is a request available and, if not, you can invoke your page generation code to display the page in the class. I have used this for for a long time and it reduces the number of class files you need to track.  
```
Example:  
class myPageClass
{
  use EjaxTraits;
  
  public function __constructor()
  {
      if ($this->canProcessRequest())
      {
        $this->processRequest();
      }else{
        $this->displayPage();
      }
  }
  
  private function displayPage()
  { your display code here}
...
}
```
### Requirements:  

1. All clases are kept in the classes folder.
2. All class names are capitalized. (class Classname{}) and the class files are named to match (Classname.php).

### Flow:

Say the user fills out a form and clicks a button that invokes your js click handler. The handler loooks like this:

```
//Send the contact form to the server.
//It sends the info back in the target div
function submitForm() {
  data = $('#form1').serializeArray();
  var formData  = JSON.stringify(data);            
  ejax.request(theUrl, 'processForm', formData);
}
```

The handler sends the request.

The server side class reads the request, calls the processForm method passing formData parameter.

In my demo the method is coded thus:
```
    public function processForm($data)
    {
        //Decode the form data into an assoc array.
      >>  $datalist = json_decode($data,true);

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
      >>  $this->ejax->assign('target', 'innerHTML', $s);
    }
```

The important lines are marked with >>:  

1. The first decodes the json from the server.  

2. The second invokes the Ejax assign method to add the content to the innerHTML of the target div tag.

Here's a simpler server side method:
```
    public function welcomeMessage($message)
    {
        $s = "<h4>$message</h4>";                       //Wrap the message
        $this->ejax->assign('target', 'innerHTML', $s); //Assign it to the element of id "target"
    }
```

All that happend here is the message param string is wrapped in H4 tags and assigned to the innerHTML of the target div.
