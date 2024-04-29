## Using the main server side Ejax class

The file Ejax/Ejax.php contains the server side Ejax class.

You call methods in this class from your service classes to send commands back to the browser to fulfill the client request.  
```php
<?php
/*
  This is the server side Ejax class.
  
  Provides methods that create "actions" or calls to
  the client side JS methods. These are called by your
  server classes to do things like assign attribute values
  or add CSS to an element.
  
  The actions are collected into an array of actions that
  constitute the response to a request.
  
  The sendResponse method json encodes the action array and echoes it 
  to the client.
*/

class Ejax
{
	/**
	   * Array of comands to the client
	   */
	public $aActions = [];

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
     public function assign(string $id,  string $msg)
    {
       $params = ["id" => $id, "msg" => $msg];
       $this->action('assign', $params);
    }
	
	
	/**
	 *  @brief Implement the DOM setAttribute method
	 *  @param  $id Element id
	 *  @param  $name Attribute Name
	 *  @param  $value Vale of the attribute
	 *  @return No return
	 */
	 public function 	setAttribute(string $id, string $name, string $value)
	{
       $params = ["id" => $id, "name" => $name, "value" => $value];
       $this->action('setAttribute', $params);
	}
    
	/**
	 *  @brief implement DOM removeAttribute method
	 *  
	 *  @param  $id element id
	 *  @param  $name Name of attribute to remove
	 *  @return none
	 */
	public function removeAttribute(string $id, string $name)
	{
       $params = ["id" => $id, "name" => $name];
       $this->action('removeAttribute', $params);
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
     * Clear the text of an element
     * @param string $id   Element id
     */
    public function clearText($id)
    {
        $this->action('clearText',["id"=>$id]);
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
    
    /**
     *  @brief Invoke a JS function no in the Ejax class
     *  
     *  @param  $sFunctionName The function name
     *  @param  $aFunctionArguments The funection arguments
     *  @return one
     */
	 public function userFunc($sFunctionName, $aFunctionArguments)
    {
      $this->action('userFunc', ["functionName"=>$sFunctionName, "functionArgs"=>$aFunctionArguments]);
    }

}
```