<?php
/**
 *  Ejax server side demonstration class.
 *  Called from the home page of the demo project.
 *
 *  The constructor calls the propcessRequest trait and
 *  and it handles all of processing.
  */

 require './vendor/autoload.php';
 
class IndexServer
{
    use EjaxTraits; //Client request handlers
    
    public function __construct()
    {
        $this->processRequest(); //Process the client request - see EjaxTraits
    } 
    
    /******************************** Begin Callable Client Service Methods ***************************/

    //Client calls this sending a message string.
    //Function wraps it in H4 tag and assigns it to the client "target" div.
    public function welcomeMessage($message)
    {
        $s = "<h4>$message</h4>";                         //Wrap the message
        $this->ejax->assign('target', $message); //Assign it to the element of id "target"
    }

    /*
      This function receives a Jaxon request from the client
      and makes an img tag which it sends back with  command
      to insert it into the target div.
    */
    public function getImage()
    {
        $s = "<image src='assets/img/idaho.jpg'width='500' height='500'/>";

        $this->ejax->assign('target',  $s);
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
        
        //Construct the response message.
        $s = '<p>';

        $s  .= 'Name:....'. $datalist['name'].'<br/>';
        $s .= 'Email:...'. $datalist['email'].'<br/>';
        $s .= 'Website:.'. $datalist['website'].'<br/>';
        $s .= 'Subject:.'. $datalist['subject']. '<br/>';
        $s .= 'Message:.'. $datalist['message'].'<br/>';

        $s .= '</p>';

        //Assign the string to the #target div.
        $this->ejax->assign('target', $s);
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
    
	/*
		This function services the "Run Selected Test" button.
		The user has selected an Ejax method and target element from the drop downs.
		The arguments are the chosen method and target.
		
	*/
    public function runTest($args)
    {
      $args = json_decode($args, true);
      $action = $args['methodName'];
      $id        = $args['targetId'];

      switch($action)
      {
        case "assign":
          $this->ejax->assign($id, 'Assign request complete');
          break;
        case "append":
            $this->ejax->append($id, 'value', ' appended text.');
            break;
        case "prepend":
            $this->ejax->prepend('target', 'innerHTML', 'prepended text - ');
            break;
        case "replace":
            $this->ejax->replace('target', 'innerHTML', 'Welcome','replaced');
            break;
        case "clearText":
            $this->ejax->clearText($id);
            break;
        case "create":
            $this->ejax->create('newta', 'target', 'textarea','after');
            break;
        case "remove":
            $this->ejax->remove('newta');
            break;
        case "addCSS":
            $this->ejax->addCSS('target', 'backgroundColor', 'red');
            break;
        case "removeCSS":
            $this->ejax->removeCSS('target', 'backgroundColor');
            break;
        case "alert":
            $this->ejax->alert('Message from the server');
            break;
        case "script":
            $this->ejax->script("alert('Message from the server script function')");
            break;
        case "call":
            $this->ejax->call('info', 'Hello');
            break;
        case "userFunc":
            $this->fillContact();
            break;
		case "setAttribute":
			$this->ejax->setAttribute('message', 'rows', '15');
			break;
		case "removeAttribute":
			$this->ejax->removeAttribute('message', 'rows');
			break;
			
      }

    }
    
    //Tests the userFunc
    //Creates an array of values for The
    //contact form and returns it, calling the 'formFiller'
    //on the client
    public function fillContact()
    {
        $aVals = [];

        $aVals['name'] = 'Joe Blow';
        $aVals['email'] = 'jblow@gmail.com';
        $aVals['website'] = 'jblow.com';
        $aVals['subject'] = 'Contacting You';
        $aVals['message'] = 'See you by 5PM';

        //Assign the string to the #target div.
        $this->ejax->userFunc('formFiller', $aVals);
    }
}
