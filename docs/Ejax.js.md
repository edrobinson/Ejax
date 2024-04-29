### Ejax Javascript Documentation
The file  "Ejax/src/Ejax.js" contains the client side portion of the project.

The Javascript contains a JS class with methods to send requests and process responses to and from the server plus methods to make changes to the DOM of your pages.

#### Ejax.js Source Code:  
As with the server side class, I have included documentation comments in the 
JS code
```javascript
/**
 * This is the client side JS of the Ejax project.
 */

class Ejax { 
  
  errored = false;  //Error flag
  url; //Server URL

  //The constructor just stores the URL of the server PHP
  constructor(sUrl) {
	this.url = sUrl;
  }
  /******************** Request and Response Handlers *********************/
  /*
  Send a request to a PHP class on the server then
  run the response handler method when complete.

  Parameters:
  theURL:         string  The URL of the service - a class
  theMethod   string  The method of the class
  theArgs        array   The arguments to the method
  */
  async request(theURL, theMethod, theArgs) {
    this.errored = false; 

    //Prep the data
    var theData = JSON.stringify([theMethod, theArgs]);

    const theRequest = new Request(theURL, {
      method: "POST",
      headers:{
        "Content-Type": "application/json; charset=utf-8"
      },
      body: theData
    });

    //Run the request
    try {
      const response = await fetch(theRequest);
      if (!response.ok) {
        throw new Error("Network response was not OK" + response.body);
      }

      const json = await response.json();
      this.handleResponse(json);

    } catch (error) {
      this.errored = true;
      alert("An error occured. Your request could not be completed.");
      console.log("Error: ", error.message);
    }
  }

  /*
  Process the response from the server

  The response consiste of an array of JSON objects.

  Each object defines a command that specifies:
  1. The method to invoke.
  2. The Id of the element on the page.
  3. The attribute of the element to be effected.
  4. The value to be assigned to the attribute.

  Using forEach, we iterate over the commands and
  invoke the methods specified.
  */
  handleResponse(actions) {
    actions.forEach((action) => {
      let method = action.action;  //Who to call
      let args      = action.params; //What to say
      this[method](args); 				//Call the method thru the method var
    })
  }

  /****************************** Begin Response Services Methods *****************************/  
	/*
	The assign method is used alter the text content of an element.
	The method code uses the tagName property of the element
	to determine which attribute to alter.
	*/
  assign(args) {
    let id = args['id'];
    let value = args['msg'];

    //Valdate the required inputs
	if (id == '' || value == '') {
      alert('In the assign method, id and value are both required.');
      throw new Error('In assign, all parameters must be non blank".');
      return false;
    }

    let element = document.getElementById(id);
    var tagtype = element.tagName;
    tagtype = tagtype.toLowerCase();
	if(tagtype == 'input')
	{
	  element.value = value;					//Use value on all inputs
	}else{
	  element.innerHTML = value;		//innerHTML on everything else	
	}
  }
  
  //Implement the DOM setAttribute method
  //i.e set the href on an <a> tag
  setAttribute(args){
	let id = args['id'];  
	let name = args['name'];
	let value = args['value'];
	let element = document.getElementById(id);
	element.setAttribute(name, value);
	
  }
  
  //or remove it ...
  removeAttribute(args){
	let id = args['id'];  
	let name = args['name'];
	document.getElementById(id).removeAttribute(name);
  }

  // Append the specified data to the given element's attribute
  //append(string $sTarget, string $sAttribute, string $sData)
  append(args) {
    let id = args['id'];
    let property = args['prop'];
    let value = args['value'];
	
	let element  = document.getElementById(id)
    let content = element.innerHTML + value;
    element.innerHTML = content
  }

  // Prepend the specified data to the given element's attribute
  //prepend(string $sTarget, string $sAttribute, string $sData)
  prepend(args) {
    let id = args['id'];
    let property = args['prop'];
    let value = args['value'];
	let element = document.getElementById(id)
    let content = value + element.innerHTML;
    element.innerHTML = content;
  }

  // Replace a specified value with another value within the given element's attribute
  //replace(string $sTarget, string $sAttribute, string $sSearch, string $sData)
  replace(args) {
    var id = args['id'];
    var attribute = args['prop'];
    var searchval = args['search'];
    var replaceval = args['value'];

	var element = document.getElementById(id)
    var content = element.innerHTML;

    let res = content.replace(searchval, replaceval);

    element.innerHTML = res;
  }

  // Clear the text of an element
  clearText(args) {
    var id = args['id'];
    var element = document.getElementById(id);
    var tagtype = element.tagName;
    tagtype = tagtype.toLowerCase();
	if(tagtype == 'input')
	{
	  element.value = '';	
	}else{
	  element.innerHTML = '';	
	}
  }

  // Create a new element in the document
  //create(string $sParent, string $sTag, string $sId, string $position)
  //position takes before or after only
  create(args) {
    var id = args['id']; //New element id
    var sParentId = args['parent']; //Parent id
    var sTag = args['tag']; //Tag type - i.e. textarea
    var position = args['position']; //Relative position - after, before

    if (position != 'after' && position != 'before') {
      alert('In Create, the position must be "before" or "after".');
      throw new Error('In Create, the position must be "before" or "after".');
      return false;
	}
    var parent = document.getElementById(sParentId); //Get the new element's parent's id
    var newElement = document.createElement(sTag); //The new element
    newElement.setAttribute('id', id); //Set it's id

    //Position the new element relative to it's parent
    switch (position) {
    case 'after':
      parent.after(newElement);
      break;
    case 'before':
      parent.parentNode.insertBefore(newElement, parent);
      break;
    }

  }

  // Remove an element from the document
  //remove(string $sTarget)
  remove(args) {
    let sTarget = args['id'];
    document.getElementById(sTarget).remove();
  }

  //Call a JS function passing args
  call(args) {
    let x = args.method + '(' + args.params + ')';
    eval(x);
  }

  //Add a css value to the target id
  addCSS(args) 
  {
    let id = args['id'];
    let prop = args['prop'];
    let value = args['value'];
    document.getElementById(id).style[prop] = value
  }

  //or remove it...
  removeCSS(args) 
  {
    let id = args['id'];
    let prop = args['prop'];
    document.getElementById(id).style[prop] = ''
  }

  /*
  Running javascript code
   */

  // Display an alert message
  //alert(string $sMessage)
  alert(args) 
  {
    alert(args['message']);
  }

  // Execute the specified javascript code
  //script(string $sJsCode)
  script(args) {
    var js = args['jscode'];
    eval(js);
  }
  
  //Call a user function in the global space.
  //The arguments are the function name and an array of it's arguments
  userFunc(args)
  {
     let funcname = args['functionName'];
     let funcargs = args['functionArgs'];
     
     window[funcname](funcargs);
  }

  //**************************** End of Response Service Methods *************************************
} //Class Ends

  ```
