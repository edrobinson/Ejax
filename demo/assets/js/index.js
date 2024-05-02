/**
  Index_c Page Javascript to demonstrate the Ejax
  client side functionallity.
  
  Study Ejax.js to understand the client side services.

  Study the Index_c.php file for a better understanding
  of the services.

 */
//The url on the server
//Intercepted by index.php which loads the IndexServer class
var theUrl = 'IndexServer';

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
  var form = document.getElementById('form1')
  var data = new FormData(form)
  var formData = JSON.stringify(Object.fromEntries(data));
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

/*
	The display contains 2 select tags:
	1. A selection of functions to be tested
	2. A selectionof fields to be effected
	
	The function sends a request to the user function, runTest()
	which calls the Ejax function on the selected element
	
*/
function runTest()
{
  var methodName = document.getElementById("TestSelect").value;
  var targetId          = document.getElementById("sfield").value; 
  var jparam            = JSON.stringify({"methodName" : methodName, "targetId" : targetId});
   if(methodName != '')
  {
    ejax.request(theUrl, 'runTest', jparam );
  }
}

//Testing userFunc. Fill the contact form.
function formFiller(args)
{
  Object.entries(args).forEach((entry) => {
    const [key, value] = entry;
    document.getElementById(key).value = value;
  });
}
