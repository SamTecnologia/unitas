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

// Start trigger
$formValidation = new tNG_FormValidation();
$tNGs->prepareValidation($formValidation);
// End trigger

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_unitas, $unitas);
$query_Recordset1 = "SELECT user_nome, user_id FROM unts_usuario ORDER BY user_nome";
$Recordset1 = mysql_query($query_Recordset1, $unitas) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_unitas, $unitas);
$query_Recordset2 = "SELECT func_descricao, func_id FROM unts_funcao ORDER BY func_descricao";
$Recordset2 = mysql_query($query_Recordset2, $unitas) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

// Make an insert transaction instance
$ins_unts_funcao_usuario = new tNG_multipleInsert($conn_unitas);
$tNGs->addTransaction($ins_unts_funcao_usuario);
// Register triggers
$ins_unts_funcao_usuario->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_funcao_usuario->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_funcao_usuario->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_unts_funcao_usuario->setTable("unts_funcao_usuario");
$ins_unts_funcao_usuario->addColumn("user_id", "NUMERIC_TYPE", "POST", "user_id");
$ins_unts_funcao_usuario->addColumn("func_id", "NUMERIC_TYPE", "POST", "func_id");
$ins_unts_funcao_usuario->addColumn("func_user_ativo", "STRING_TYPE", "POST", "func_user_ativo");
$ins_unts_funcao_usuario->setPrimaryKey("func_user_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_unts_funcao_usuario = new tNG_multipleUpdate($conn_unitas);
$tNGs->addTransaction($upd_unts_funcao_usuario);
// Register triggers
$upd_unts_funcao_usuario->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_unts_funcao_usuario->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_unts_funcao_usuario->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_unts_funcao_usuario->setTable("unts_funcao_usuario");
$upd_unts_funcao_usuario->addColumn("user_id", "NUMERIC_TYPE", "POST", "user_id");
$upd_unts_funcao_usuario->addColumn("func_id", "NUMERIC_TYPE", "POST", "func_id");
$upd_unts_funcao_usuario->addColumn("func_user_ativo", "STRING_TYPE", "POST", "func_user_ativo");
$upd_unts_funcao_usuario->setPrimaryKey("func_user_id", "NUMERIC_TYPE", "GET", "func_user_id");

// Make an instance of the transaction object
$del_unts_funcao_usuario = new tNG_multipleDelete($conn_unitas);
$tNGs->addTransaction($del_unts_funcao_usuario);
// Register triggers
$del_unts_funcao_usuario->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_unts_funcao_usuario->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_unts_funcao_usuario->setTable("unts_funcao_usuario");
$del_unts_funcao_usuario->setPrimaryKey("func_user_id", "NUMERIC_TYPE", "GET", "func_user_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_funcao_usuario = $tNGs->getRecordset("unts_funcao_usuario");
$row_rsunts_funcao_usuario = mysql_fetch_assoc($rsunts_funcao_usuario);
$totalRows_rsunts_funcao_usuario = mysql_num_rows($rsunts_funcao_usuario);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
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
if (@$_GET['func_user_id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Fun&ccedil;&atilde;o do Usu&aacute;rio </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsunts_funcao_usuario > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="user_id_<?php echo $cnt1; ?>">Usuário:</label></td>
            <td><select name="user_id_<?php echo $cnt1; ?>" id="user_id_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_Recordset1['user_id']?>"<?php if (!(strcmp($row_Recordset1['user_id'], $row_rsunts_funcao_usuario['user_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['user_nome']?></option>
              <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("unts_funcao_usuario", "user_id", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="func_id_<?php echo $cnt1; ?>">Funçao:</label></td>
            <td><select name="func_id_<?php echo $cnt1; ?>" id="func_id_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_Recordset2['func_id']?>"<?php if (!(strcmp($row_Recordset2['func_id'], $row_rsunts_funcao_usuario['func_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['func_descricao']?></option>
              <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("unts_funcao_usuario", "func_id", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="func_user_ativo_<?php echo $cnt1; ?>">Ativa:</label></td>
            <td><select name="func_user_ativo_<?php echo $cnt1; ?>" id="func_user_ativo_<?php echo $cnt1; ?>">
              <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute($row_rsunts_funcao_usuario['func_user_ativo'])))) {echo "SELECTED";} ?>>Sim</option>
              <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute($row_rsunts_funcao_usuario['func_user_ativo'])))) {echo "SELECTED";} ?>>Nao</option>
            </select>
              <?php echo $tNGs->displayFieldError("unts_funcao_usuario", "func_user_ativo", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_unts_funcao_usuario_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsunts_funcao_usuario['kt_pk_unts_funcao_usuario']); ?>" />
        <?php } while ($row_rsunts_funcao_usuario = mysql_fetch_assoc($rsunts_funcao_usuario)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['func_user_id'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'func_user_id')" />
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
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
