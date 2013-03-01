<?php require_once('../Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);

//start Trigger_CheckPasswords trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckPasswords(&$tNG) {
  $myThrowError = new tNG_ThrowError($tNG);
  $myThrowError->setErrorMsg("Could not create account.");
  $myThrowError->setField("user_senha");
  $myThrowError->setFieldErrorMsg("The two passwords do not match.");
  return $myThrowError->Execute();
}
//end Trigger_CheckPasswords trigger

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("user_login", true, "text", "", "", "", "");
$formValidation->addField("user_senha", true, "text", "", "", "", "");
$formValidation->addField("user_nome", true, "text", "", "", "", "");
$formValidation->addField("user_telefone", true, "text", "", "", "", "");
$formValidation->addField("user_email", true, "text", "email", "", "", "");
$formValidation->addField("user_nivel", true, "text", "", "", "", "");
$formValidation->addField("user_ativo", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_CheckOldPassword trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckOldPassword(&$tNG) {
  return Trigger_UpdatePassword_CheckOldPassword($tNG);
}
//end Trigger_CheckOldPassword trigger

// Make an insert transaction instance
$ins_unts_usuario = new tNG_multipleInsert($conn_unitas);
$tNGs->addTransaction($ins_unts_usuario);
// Register triggers
$ins_unts_usuario->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_usuario->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_usuario->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_unts_usuario->registerConditionalTrigger("{POST.user_senha} != {POST.re_user_senha}", "BEFORE", "Trigger_CheckPasswords", 50);
// Add columns
$ins_unts_usuario->setTable("unts_usuario");
$ins_unts_usuario->addColumn("user_login", "STRING_TYPE", "POST", "user_login");
$ins_unts_usuario->addColumn("user_senha", "STRING_TYPE", "POST", "user_senha");
$ins_unts_usuario->addColumn("user_nome", "STRING_TYPE", "POST", "user_nome");
$ins_unts_usuario->addColumn("user_telefone", "STRING_TYPE", "POST", "user_telefone");
$ins_unts_usuario->addColumn("user_email", "STRING_TYPE", "POST", "user_email");
$ins_unts_usuario->addColumn("user_nivel", "STRING_TYPE", "POST", "user_nivel");
$ins_unts_usuario->addColumn("user_ativo", "STRING_TYPE", "POST", "user_ativo");
$ins_unts_usuario->setPrimaryKey("user_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_unts_usuario = new tNG_multipleUpdate($conn_unitas);
$tNGs->addTransaction($upd_unts_usuario);
// Register triggers
$upd_unts_usuario->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_unts_usuario->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_unts_usuario->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_unts_usuario->registerConditionalTrigger("{POST.user_senha} != {POST.re_user_senha}", "BEFORE", "Trigger_CheckPasswords", 50);
$upd_unts_usuario->registerTrigger("BEFORE", "Trigger_CheckOldPassword", 60);
// Add columns
$upd_unts_usuario->setTable("unts_usuario");
$upd_unts_usuario->addColumn("user_login", "STRING_TYPE", "POST", "user_login");
$upd_unts_usuario->addColumn("user_senha", "STRING_TYPE", "POST", "user_senha");
$upd_unts_usuario->addColumn("user_nome", "STRING_TYPE", "POST", "user_nome");
$upd_unts_usuario->addColumn("user_telefone", "STRING_TYPE", "POST", "user_telefone");
$upd_unts_usuario->addColumn("user_email", "STRING_TYPE", "POST", "user_email");
$upd_unts_usuario->addColumn("user_nivel", "STRING_TYPE", "POST", "user_nivel");
$upd_unts_usuario->addColumn("user_ativo", "STRING_TYPE", "POST", "user_ativo");
$upd_unts_usuario->setPrimaryKey("user_id", "NUMERIC_TYPE", "GET", "user_id");

// Make an instance of the transaction object
$del_unts_usuario = new tNG_multipleDelete($conn_unitas);
$tNGs->addTransaction($del_unts_usuario);
// Register triggers
$del_unts_usuario->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_unts_usuario->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_unts_usuario->setTable("unts_usuario");
$del_unts_usuario->setPrimaryKey("user_id", "NUMERIC_TYPE", "GET", "user_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_usuario = $tNGs->getRecordset("unts_usuario");
$row_rsunts_usuario = mysql_fetch_assoc($rsunts_usuario);
$totalRows_rsunts_usuario = mysql_num_rows($rsunts_usuario);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Administra&ccedil;&atilde;o</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: true,
  show_as_grid: true,
  merge_down_value: true
}
</script>
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['user_id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Usu&aacute;rio </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsunts_usuario > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="user_login_<?php echo $cnt1; ?>">Login:</label></td>
            <td><input type="text" name="user_login_<?php echo $cnt1; ?>" id="user_login_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_usuario['user_login']); ?>" size="30" maxlength="30" />
              <?php echo $tNGs->displayFieldHint("user_login");?> <?php echo $tNGs->displayFieldError("unts_usuario", "user_login", $cnt1); ?></td>
          </tr>
          <?php 
// Show IF Conditional show_old_user_senha_on_update_only 
if (@$_GET['user_id'] != "") {
?>
            <tr>
              <td class="KT_th"><label for="old_user_senha_<?php echo $cnt1; ?>">Old Senha:</label></td>
              <td><input type="password" name="old_user_senha_<?php echo $cnt1; ?>" id="old_user_senha_<?php echo $cnt1; ?>" value="" size="8" maxlength="8" />
                <?php echo $tNGs->displayFieldError("unts_usuario", "old_user_senha", $cnt1); ?></td>
            </tr>
            <?php } 
// endif Conditional show_old_user_senha_on_update_only
?>
          <tr>
            <td class="KT_th"><label for="user_senha_<?php echo $cnt1; ?>">Senha:</label></td>
            <td><input type="password" name="user_senha_<?php echo $cnt1; ?>" id="user_senha_<?php echo $cnt1; ?>" value="" size="8" maxlength="8" />
              <?php echo $tNGs->displayFieldHint("user_senha");?> <?php echo $tNGs->displayFieldError("unts_usuario", "user_senha", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="re_user_senha_<?php echo $cnt1; ?>">Re-type Senha:</label></td>
            <td><input type="password" name="re_user_senha_<?php echo $cnt1; ?>" id="re_user_senha_<?php echo $cnt1; ?>" value="" size="8" maxlength="8" /></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="user_nome_<?php echo $cnt1; ?>">Nome:</label></td>
            <td><input type="text" name="user_nome_<?php echo $cnt1; ?>" id="user_nome_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_usuario['user_nome']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("user_nome");?> <?php echo $tNGs->displayFieldError("unts_usuario", "user_nome", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="user_telefone_<?php echo $cnt1; ?>">Telefone:</label></td>
            <td><input type="text" name="user_telefone_<?php echo $cnt1; ?>" id="user_telefone_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_usuario['user_telefone']); ?>" size="13" maxlength="13" />
              <?php echo $tNGs->displayFieldHint("user_telefone");?> <?php echo $tNGs->displayFieldError("unts_usuario", "user_telefone", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="user_email_<?php echo $cnt1; ?>">E-mail:</label></td>
            <td><input type="text" name="user_email_<?php echo $cnt1; ?>" id="user_email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_usuario['user_email']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("user_email");?> <?php echo $tNGs->displayFieldError("unts_usuario", "user_email", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="user_nivel_<?php echo $cnt1; ?>">User_nivel:</label></td>
            <td><select name="user_nivel_<?php echo $cnt1; ?>" id="user_nivel_<?php echo $cnt1; ?>">
              <option value="U" <?php if (!(strcmp("U", KT_escapeAttribute($row_rsunts_usuario['user_nivel'])))) {echo "selected=\"selected\"";} ?>>Usuário Nao Administrador</option>
              <option value="A" <?php if (!(strcmp("A", KT_escapeAttribute($row_rsunts_usuario['user_nivel'])))) {echo "selected=\"selected\"";} ?>>Usuário Administrador</option>
              <option value="P" <?php if (!(strcmp("P", KT_escapeAttribute($row_rsunts_usuario['user_nivel'])))) {echo "selected=\"selected\"";} ?>>Usuário Plus</option>
            </select>
              <?php echo $tNGs->displayFieldError("unts_usuario", "user_nivel", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="user_ativo_<?php echo $cnt1; ?>">Ativo:</label></td>
            <td><select name="user_ativo_<?php echo $cnt1; ?>" id="user_ativo_<?php echo $cnt1; ?>">
              <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute($row_rsunts_usuario['user_ativo'])))) {echo "SELECTED";} ?>>Sim</option>
              <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute($row_rsunts_usuario['user_ativo'])))) {echo "SELECTED";} ?>>Nao</option>
            </select>
              <?php echo $tNGs->displayFieldError("unts_usuario", "user_ativo", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_unts_usuario_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsunts_usuario['kt_pk_unts_usuario']); ?>" />
        <?php } while ($row_rsunts_usuario = mysql_fetch_assoc($rsunts_usuario)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['user_id'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'user_id')" />
            </div>
            <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
            <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
            <?php }
      // endif Conditional region1
      ?>
<input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
        </div>
      </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>