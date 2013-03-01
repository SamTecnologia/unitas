<?php require_once('../Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');
?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');
?>
<?php
// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");
?>
<?php
// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);
?>

<?php
// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("user_email", true, "text", "email", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger
?>
<?php
//start Trigger_ForgotPasswordCheckEmail trigger
//remove this line if you want to edit the code by hand
function Trigger_ForgotPasswordCheckEmail(&$tNG) {
  return Trigger_ForgotPassword_CheckEmail($tNG);
}
//end Trigger_ForgotPasswordCheckEmail trigger
?>
<?php
//start Trigger_ForgotPassword_Email trigger
//remove this line if you want to edit the code by hand
function Trigger_ForgotPassword_Email(&$tNG) {
  $emailObj = new tNG_Email($tNG);
  $emailObj->setFrom("{KT_defaultSender}");
  $emailObj->setTo("{user_email}");
  $emailObj->setCC("");
  $emailObj->setBCC("");
  $emailObj->setSubject("Forgot password email");
  //FromFile method
  $emailObj->setContentFile("../includes/mailtemplates/forgot.html");
  $emailObj->setEncoding("ISO-8859-1");
  $emailObj->setFormat("HTML/Text");
  $emailObj->setImportance("Normal");
  return $emailObj->Execute();
}
//end Trigger_ForgotPassword_Email trigger
?>
<?php
// Make an update transaction instance
$forgotpass_transaction = new tNG_update($conn_unitas);
$tNGs->addTransaction($forgotpass_transaction);
// Register triggers
$forgotpass_transaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$forgotpass_transaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$forgotpass_transaction->registerTrigger("BEFORE", "Trigger_ForgotPasswordCheckEmail", 20);
$forgotpass_transaction->registerTrigger("AFTER", "Trigger_ForgotPassword_Email", 1);
$forgotpass_transaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "{kt_login_redirect}");
// Add columns
$forgotpass_transaction->setTable("unts_usuario");
$forgotpass_transaction->addColumn("user_email", "STRING_TYPE", "POST", "user_email");
$forgotpass_transaction->setPrimaryKey("user_email", "STRING_TYPE", "POST", "user_email");
?>
<?php
// Execute all the registered transactions
$tNGs->executeTransactions();
?>
<?php
// Get the transaction recordset
$rsunts_usuario = $tNGs->getRecordset("unts_usuario");
$row_rsunts_usuario = mysql_fetch_assoc($rsunts_usuario);
$totalRows_rsunts_usuario = mysql_num_rows($rsunts_usuario);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Forgot Password Page</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>

<?php echo $tNGs->displayValidationRules();?>
</head>

<body>

<?php
	echo $tNGs->getErrorMsg();
?>
	<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
	<table cellpadding="2" cellspacing="0" class="KT_tngtable">
			<tr>
	<td class="KT_th"><label for="user_email"> Email:</label></td>
	<td>
		<input type="text" name="user_email" id="user_email" value="<?php echo KT_escapeAttribute($row_rsunts_usuario['user_email']); ?>" size="32" />
		<?php echo $tNGs->displayFieldHint("user_email");?>
		<?php echo $tNGs->displayFieldError("unts_usuario", "user_email"); ?>
	</td>
</tr>
			<tr class="KT_buttons"> 
				<td colspan="2">
					<input type="submit" name="KT_Update1" id="KT_Update1" value="Submit" />
				</td>
			</tr>      
		</table>
		
	</form>
	<p>&nbsp;</p>

</body>
</html>
