## Ejax PHP methods  

### This is a list of all of the Ejax php class's action methods that you can call from your server classes.
```
alert(string $sMessage)                             Display an alert          
assign(string $id, string $attribute, string $msg)  Set an element attribute
call($sMethod, $Params='')                          Call a JS function of yours
addCSS($iId, $prop, $value)                         Add a CSS style
removeCSS($iId, $prop)                              Remove a CSS style
append($iId='', $prop='', $value='')                Append a property to an element
prepend($iId, $prop, $value)                        Prepend a property to an element
replace($id, $prop, $search, $value)                Search an replace in an element's text
clearAttr($id, $prop)                               Remove an attribute
create($id, $parent, $tag, $position)               Create a new element before or after another
remove($id)                                         Remove an element from the DOM          
script($sJsCode)                                    Execute a piece of JS code
setAttribute(string $id, string $name, string $value) Set an   attribute on an element
removeAttribute(string $id, string $name) Remove an attribute
```

There are more actions that can be implemented but I felt that these were the most commonly used.

Feel free to suggest additional functionality.

The functions invoke a similarily named JS function on the client that carries out the action.