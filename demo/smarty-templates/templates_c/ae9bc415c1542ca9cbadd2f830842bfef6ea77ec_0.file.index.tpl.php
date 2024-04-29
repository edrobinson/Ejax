<?php
/* Smarty version 4.3.4, created on 2024-04-28 19:13:41
  from 'C:\wamp64\www\Ejax\demo\smarty-templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_662e9fe5cb48e2_41395747',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ae9bc415c1542ca9cbadd2f830842bfef6ea77ec' => 
    array (
      0 => 'C:\\wamp64\\www\\Ejax\\demo\\smarty-templates\\index.tpl',
      1 => 1714329479,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:TestSelect.tpl' => 1,
  ),
),false)) {
function content_662e9fe5cb48e2_41395747 (Smarty_Internal_Template $_smarty_tpl) {
?>    <div class="d-flex justify-content-center mt-5">
      <button class="btn btn-primary me-2" onclick="sayWelcome()">Click for Welcome</button>
      <button class="btn btn-primary me-2" onclick="getImage()">Click for Image</button>
 
    </div>
    
    <div class="d-flex justify-content-center mt-3">
			<?php $_smarty_tpl->_subTemplateRender("file:TestSelect.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> &nbsp;
	</div>
    
       <!-- A contact form to be sent to the server -->
       <div class="d-flex justify-content-center mt-3 mb-3">
          <form class="form" id="form1" name="form1"  style="border: 1px solid blue; padding: 10px;">
            <div class="row">
              <div class="form-group col-md-6">
                <input type="text" class="form-control" name="name" id="name" placeholder="Your Name (name)">
              </div>
              <div class="form-group col-md-6">
                <input type="email" class="form-control" name="email" id="email">
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="website" id="website" placeholder="Website URL(website)">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject(subject)">
            </div>
            <div class="form-group">
              <textarea class="form-control" name="message" id="message" rows="5" placeholder="Message(message)" style="border: 1px solid red"></textarea>
            </div>
            <div class="text-center mt-3"><button class="btn btn-primary" type="button" onclick="submitForm()" id="sendForm">Send Message</button></div>
          </form>
        </div>
        <!-- End Contact Form -->

    <!-- The servr side code sends the responses to this div. -->
    <div class="d-flex justify-content-center mb-3" 
    id="target" name="target" style="border: 1px solid green";height="20px";width="100%">Target</div>
    <!-- Content Ends -->
    <?php echo '<script'; ?>
 src="../../Ejax/src/Ejax.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="assets/js/index.js"><?php echo '</script'; ?>
>
    
<?php }
}
