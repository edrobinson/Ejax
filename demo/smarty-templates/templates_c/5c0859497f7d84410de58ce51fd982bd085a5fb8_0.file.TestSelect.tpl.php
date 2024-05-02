<?php
/* Smarty version 4.3.4, created on 2024-05-01 21:52:04
  from 'C:\wamp64\www\Ejax\demo\smarty-templates\TestSelect.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6632b984dc4488_51266774',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5c0859497f7d84410de58ce51fd982bd085a5fb8' => 
    array (
      0 => 'C:\\wamp64\\www\\Ejax\\demo\\smarty-templates\\TestSelect.tpl',
      1 => 1714077287,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6632b984dc4488_51266774 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container">
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-4">
		<select class="form-select me-3" id="TestSelect" name="TestSelect" style="border: 1px solid green">
		<option value="">------------ Select a Test to Run ------------</option>
		<option value="assign">Assign</option>
		<option value="append">Append </option>
		<option value="prepend">Prepend</option>
		<option value="replace">Replace in Text</option>
		<option value="clearText">Clear Text</option>
		<option value="create">Create Element</option>
		<option value="remove">Remove Element</option>
		<option value="addCSS">Add Css Style</option>
		<option value="removeCSS">Remove CSS Style</option>
		<option value="alert">Show an Alert</option>
		<option value="script">Run JS Code</option>
		<option value="call">Call a JS Method</option>
		<option value="userFunc">Call a User Function</option>
		<option value="setAttribute">Set an Attribute</option>
		<option value="removeAttribute">Remove an Attribute</option>
		<!--<option value=""></option>-->
		</select>
		</div>
		<br/>
		<div class="col-sm-4">
		<select class="form-select" id="sfield" name="sfield">
		<option value="">Select the id of the field to be altered.</option>
		<option value="name">Form Name</option>
		<option value="email">Form Email</option>
		<option value="website">Form Website</option>
		<option value="subject">Form Subject</option>
		<option value="message">Form Message</option>
		<option value="target">Target Div</option>
		</select>
		</div>
		<div class="col-sm-2"><button class="btn btn-primary" id="testBtn" onclick="runTest()">Run Selected Test</button></div>
	</div>
	</div>
</div><?php }
}
