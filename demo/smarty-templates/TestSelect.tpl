<div class="container">
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
</div>