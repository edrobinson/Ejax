## Ejax.php Documentation

This document explains the workings of the Ejax server side class - Ejax.php.  
  
### This class serves 2 purposes:
  1. Receive and route requests from the client to your service methods.
  2. Provide methods that create your calls to the client methods
  3. Return a response to the client to update the browser.  

#### Sample callable service to add a textarea to a page from the server  
```
  public function addNewTextarea
  {
    //Create a textarea with id = 'ta' before the element
    //with id = 'sendForm' - a button
    $this->ejax->create('ta','sendForm','textarea', 'before');
    
    //Add some text to the new textarea
    $this->ejax->assign('ta', 'value', 'Text area ta');
    
    //Add a red border to it
    $this->ejax->addCss('ta', 'border', '3px solid red');

    //Add an right margin to it.
    $this->ejax->addCss('ta', 'margin-right', '20px');
  }  
```  
#### When this function ends the response is sent back to the client where the commands are executed.  
## The class code:  
I am obsessive about commenting the code so here is the class code.  
```
<?php
/*
  This is the server side Ejax class.
  
  Provides methods that create "actions" or calls to
  the client side JS methods. These are called by your
  server classes to do things like assign attribute values
  or add CSS to an element.
  
  The actions are collected into an array of actions that
  constitute the response to a request.
  
  The SendResponse method json encodes the action array and echoes it 
  to the client.
  
  Note: I use the words "action" and "actions" for the command structures generated as response elements.  
  There is no "Response" class.  
  The response is an array of actions built by your method calls to the Ejax services.
*/

class Ejax
{
    public $aActions = [];   //Response actions array

    /**
     * Create a new action and add it to the response array of actions
     * @param string $sAction Client side method name
     * @param array  $aParams Array of the method's arguments
     */
    public function action($sAction='', $aParams = '')
    {
        $aAction = array("action" => $sAction, "params" => $aParams);
        $this->aActions[] = $aAction;
    }
    
    /**
     * Send the response array to the client
     */
    public function sendResponse()
    {
      if(count($this->aActions) > 0)
      {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');
        $jResponse =  json_encode($this->aActions);
        echo($jResponse);
      }else{
        echo('No response commands were generated..');
      }
      die;
    }

    /*
      ****************  Begin the calls to the Ejax Client services ******************
    */    
 
    
    /**
     * Show an alert
     * @param string string $sMessage The alert message
     */
    public function alert(string $sMessage)
    {
      $this->action('alert', ["message"=>$sMessage]);
    }

     /**
      * Change the inner or outer HTML of an element
      * @param string $id        Element Id
      * @param string $attribute inner HTML or outerHTML
      * @param string $msg       The new content
      */
     public function assign(string $id, string $attribute, string $msg)
    {
       $params = ["id" => $id, "attribute" => $attribute, "msg" => $msg];
       $this->action('assign', $params);
    }
    
    
    /**
     * Call a JS function with optional args
     * @param string $sMethod The function name
     * @param array  $aParams Array or arguments
     */
    public function call($sMethod, $Params='')
    {
        $this->action('call',["method"=>$sMethod, "params"=>$Params]);
    }
    
    /**
     * Add a style to an element
     * @param string $iId   Element ID
     * @param string $prop  The CSS attribute
     * @param string $value The attribute value
     */
    public function addCSS($iId, $prop, $value)
    {
        $this->action('addCSS',["id"=>$iId, "prop"=>$prop, "value"=>$value]);
    }
    
    /**
     * remove a CSS attribute
     * @param string $iId  Element ID
     * @param string $prop CSS property to remove
     */
    public function removeCSS($iId, $prop)
    {
        $this->action('removeCSS',["id"=>$iId, "prop"=>$prop]);
    }
    
    /**
     * Add to the end of an element's property
     * @param string  $iId      Element ID
     * @param [$prop] Attribute to append to
     * @param string  $value    Appended text
     */
    public function append($iId='', $prop='', $value='')
    {
        $this->action('append',["id"=>$iId, "prop"=>$prop, "value"=>$value]);
    }
    
    /**
     * Add to the beginning of an elements test
     * @param string $iId   Element id
     * @param string $prop  Attribute to alter
     * @param string $value Text to prepend
     */
    public function prepend($iId, $prop, $value)
    {
        $this->action('prepend',["id"=>$iId, "prop"=>$prop, "value"=>$value]);
    }
    
    /**
     * Replace text in an element's text
     * @param string $id     Element's id
     * @param string $prop   Attribute name
     * @param string $search Text to be replaced
     * @param string $value  Text to insert
     */
    public function replace($id, $prop, $search, $value)
    {
        $this->action('replace',["id"=>$id, "prop"=>$prop, "search"=>$search, "value"=>$value]);
    }

    /**
     * Clear an attribute of an Element
     * @param string $id   Element id
     * @param string $prop Attribute to clear
     */
    public function clearAttr($id, $prop)
    {
        $this->action('clearAttr',["id"=>$id, "prop"=>$prop]);
    }

    /**
     * Create a new element after the specified element
     * @param float  $id     Id of the new element
     * @param string $parent Id of the parent element
     * @param string $tag    Html tag name to create
     */
    public function create($id, $parent, $tag, $position)
    {
        $this->action('create', ["id"=>$id, "parent"=>$parent, "tag"=>$tag, "position"=>$position]);
    }
    
    /**
     * Remove an element by it's id
     * @param string $id The element's id value
     */                                    
    public function remove($id)
    {
        $this->action('remove', ["id"=>$id]);
    }
    
    /**
     * Execute a piece of JS code
     * @param string $jsCode The code to execute
     */
    public function script($sJsCode)
    {
        $this->action('script', ["jscode"=>$sJsCode]);
    }
}
```