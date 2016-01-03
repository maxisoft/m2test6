<?php

define('EMAIL_FOR_REPORTS', 'jordan_shadow23@yahoo.com');
define('RECAPTCHA_PRIVATE_KEY', '@privatekey@');
define('FINISH_URI', 'register.php');
define('FINISH_ACTION', 'redirect');
define('FINISH_MESSAGE', 'Thanks For Register');
define('UPLOAD_ALLOWED_FILE_TYPES', 'doc, docx, xls, csv, txt, rtf, html, zip, jpg, jpeg, png, gif');

define('_DIR_', str_replace('\\', '/', dirname(__FILE__)) . '/');
require_once _DIR_ . '/handler.php';

?>

<?php if (frmd_message()): ?>
<link rel="stylesheet" href="<?php echo dirname($form_path); ?>/formoid-default-skyblue.css" type="text/css" />
<span class="alert alert-success"><?php echo FINISH_MESSAGE; ?></span>
<?php else: ?>
<!-- Start Formoid form-->
<link rel="stylesheet" href="<?php echo dirname($form_path); ?>/formoid-default-skyblue.css" type="text/css" />
<script type="text/javascript" src="<?php echo dirname($form_path); ?>/jquery.min.js"></script>
<form action="register.php" class="formoid-default-skyblue" style="background-color:#FFFFFF;font-size:14px;font-family:'Open Sans','Helvetica Neue','Helvetica',Arial,Verdana,sans-serif;color:#666666;max-width:480px;min-width:150px" method="post"><div class="title"><h2>My form</h2></div>
	<div class="element-input<?php frmd_add_class("input"); ?>"><label class="title">login</label><input class="large" type="text" name="input" /></div>
	<div class="element-input<?php frmd_add_class("input2"); ?>"><label class="title">password</label><input class="large" type="text" name="input2" /></div>
	<div class="element-select<?php frmd_add_class("select"); ?>"><label class="title">role</label><div class="large"><span><select name="select" >

		<option value="admin">admin</option>
		<option value="student">student</option>
		<option value="teacher">teacher</option></select><i></i></span></div></div>
	<div class="element-input<?php frmd_add_class("input3"); ?>"><label class="title">first_name</label><input class="large" type="text" name="input3" /></div>
	<div class="element-input<?php frmd_add_class("input4"); ?>"><label class="title">last_name</label><input class="large" type="text" name="input4" /></div>
	<div class="element-date<?php frmd_add_class("date"); ?>"><label class="title">date_of_birth</label><input class="large" data-format="yyyy-mm-dd" type="date" name="date" placeholder="yyyy-mm-dd"/></div>
<div class="submit"><input type="submit" value="Register"/></div></form><script type="text/javascript" src="<?php echo dirname($form_path); ?>/formoid-default-skyblue.js"></script>

<!-- Stop Formoid form-->
<?php endif; ?>

<?php frmd_end_form(); ?>