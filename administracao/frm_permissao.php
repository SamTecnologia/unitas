<?php require_once('../Connections/unitas.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

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
$query_Recordset1 = "SELECT tesra_descricao, tesra_id FROM unts_tesouraria ORDER BY tesra_descricao";
$Recordset1 = mysql_query($query_Recordset1, $unitas) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_unitas, $unitas);
$query_Recordset2 = "SELECT user_nome, user_id FROM unts_usuario ORDER BY user_nome";
$Recordset2 = mysql_query($query_Recordset2, $unitas) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_unitas, $unitas);
$query_Recordset3 = "SELECT tesra_descricao, tesra_id FROM unts_tesouraria ORDER BY tesra_descricao";
$Recordset3 = mysql_query($query_Recordset3, $unitas) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_unitas, $unitas);
$query_Recordset4 = "SELECT user_nome, user_id FROM unts_usuario ORDER BY user_nome";
$Recordset4 = mysql_query($query_Recordset4, $unitas) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

// Make an insert transaction instance
$ins_unts_permissao = new tNG_multipleInsert($conn_unitas);
$tNGs->addTransaction($ins_unts_permissao);
// Register triggers
$ins_unts_permissao->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_permissao->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_permissao->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_unts_permissao->setTable("unts_permissao");
$ins_unts_permissao->addColumn("tesra_id", "NUMERIC_TYPE", "POST", "tesra_id");
$ins_unts_permissao->addColumn("user_id", "NUMERIC_TYPE", "POST", "user_id");
$ins_unts_permissao->addColumn("permi_data_ini", "DATE_TYPE", "POST", "permi_data_ini");
$ins_unts_permissao->addColumn("permi_data_fim", "DATE_TYPE", "POST", "permi_data_fim");
$ins_unts_permissao->addColumn("permi_ativa", "STRING_TYPE", "POST", "permi_ativa");
$ins_unts_permissao->setPrimaryKey("permi_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_unts_permissao = new tNG_multipleUpdate($conn_unitas);
$tNGs->addTransaction($upd_unts_permissao);
// Register triggers
$upd_unts_permissao->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_unts_permissao->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_unts_permissao->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_unts_permissao->setTable("unts_permissao");
$upd_unts_permissao->addColumn("tesra_id", "NUMERIC_TYPE", "POST", "tesra_id");
$upd_unts_permissao->addColumn("user_id", "NUMERIC_TYPE", "POST", "user_id");
$upd_unts_permissao->addColumn("permi_data_ini", "DATE_TYPE", "POST", "permi_data_ini");
$upd_unts_permissao->addColumn("permi_data_fim", "DATE_TYPE", "POST", "permi_data_fim");
$upd_unts_permissao->addColumn("permi_ativa", "STRING_TYPE", "POST", "permi_ativa");
$upd_unts_permissao->setPrimaryKey("permi_id", "NUMERIC_TYPE", "GET", "permi_id");

// Make an instance of the transaction object
$del_unts_permissao = new tNG_multipleDelete($conn_unitas);
$tNGs->addTransaction($del_unts_permissao);
// Register triggers
$del_unts_permissao->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_unts_permissao->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_unts_permissao->setTable("unts_permissao");
$del_unts_permissao->setPrimaryKey("permi_id", "NUMERIC_TYPE", "GET", "permi_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_permissao = $tNGs->getRecordset("unts_permissao");
$row_rsunts_permissao = mysql_fetch_assoc($rsunts_permissao);
$totalRows_rsunts_permissao = mysql_num_rows($rsunts_permissao);
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
<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../includes/resources/calendar.js"></script>
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['permi_id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Permiss&atilde;o </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsunts_permissao > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="tesra_id_<?php echo $cnt1; ?>">Tesra_id:</label></td>
            <td><select name="tesra_id_<?php echo $cnt1; ?>" id="tesra_id_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_Recordset3['tesra_id']?>"<?php if (!(strcmp($row_Recordset3['tesra_id'], $row_rsunts_permissao['tesra_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['tesra_descricao']?></option>
              <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("unts_permissao", "tesra_id", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="user_id_<?php echo $cnt1; ?>">Usuário:</label></td>
            <td><select name="user_id_<?php echo $cnt1; ?>" id="user_id_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_Recordset4['user_id']?>"<?php if (!(strcmp($row_Recordset4['user_id'], $row_rsunts_permissao['user_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['user_nome']?></option>
              <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("unts_permissao", "user_id", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="permi_data_ini_<?php echo $cnt1; ?>">Data Inicial:</label></td>
            <td><input name="permi_data_ini_<?php echo $cnt1; ?>" id="permi_data_ini_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsunts_permissao['permi_data_ini']); ?>" size="10" maxlength="22" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" />
              <?php echo $tNGs->displayFieldHint("permi_data_ini");?> <?php echo $tNGs->displayFieldError("unts_permissao", "permi_data_ini", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="permi_data_fim_<?php echo $cnt1; ?>">Data Final:</label></td>
            <td><input name="permi_data_fim_<?php echo $cnt1; ?>" id="permi_data_fim_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsunts_permissao['permi_data_fim']); ?>" size="10" maxlength="22" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" />
              <?php echo $tNGs->displayFieldHint("permi_data_fim");?> <?php echo $tNGs->displayFieldError("unts_permissao", "permi_data_fim", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="permi_ativa_<?php echo $cnt1; ?>">Ativa:</label></td>
            <td><select name="permi_ativa_<?php echo $cnt1; ?>" id="permi_ativa_<?php echo $cnt1; ?>">
              <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute($row_rsunts_permissao['permi_ativa'])))) {echo "SELECTED";} ?>>Sim</option>
              <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute($row_rsunts_permissao['permi_ativa'])))) {echo "SELECTED";} ?>>Nao</option>
            </select>
              <?php echo $tNGs->displayFieldError("unts_permissao", "permi_ativa", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_unts_permissao_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsunts_permissao['kt_pk_unts_permissao']); ?>" />
        <?php } while ($row_rsunts_permissao = mysql_fetch_assoc($rsunts_permissao)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['permi_id'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'permi_id')" />
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

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);
?>
