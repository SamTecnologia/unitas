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
$query_Recordset1 = "SELECT comun_sigla, comun_id FROM unts_comunidade ORDER BY comun_sigla";
$Recordset1 = mysql_query($query_Recordset1, $unitas) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

// Make an insert transaction instance
$ins_unts_tesouraria = new tNG_multipleInsert($conn_unitas);
$tNGs->addTransaction($ins_unts_tesouraria);
// Register triggers
$ins_unts_tesouraria->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_tesouraria->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_tesouraria->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_unts_tesouraria->setTable("unts_tesouraria");
$ins_unts_tesouraria->addColumn("tesra_descricao", "STRING_TYPE", "POST", "tesra_descricao");
$ins_unts_tesouraria->addColumn("comun_id", "NUMERIC_TYPE", "POST", "comun_id");
$ins_unts_tesouraria->addColumn("tesra_ativo", "STRING_TYPE", "POST", "tesra_ativo");
$ins_unts_tesouraria->setPrimaryKey("tesra_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_unts_tesouraria = new tNG_multipleUpdate($conn_unitas);
$tNGs->addTransaction($upd_unts_tesouraria);
// Register triggers
$upd_unts_tesouraria->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_unts_tesouraria->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_unts_tesouraria->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_unts_tesouraria->setTable("unts_tesouraria");
$upd_unts_tesouraria->addColumn("tesra_descricao", "STRING_TYPE", "POST", "tesra_descricao");
$upd_unts_tesouraria->addColumn("comun_id", "NUMERIC_TYPE", "POST", "comun_id");
$upd_unts_tesouraria->addColumn("tesra_ativo", "STRING_TYPE", "POST", "tesra_ativo");
$upd_unts_tesouraria->setPrimaryKey("tesra_id", "NUMERIC_TYPE", "GET", "tesra_id");

// Make an instance of the transaction object
$del_unts_tesouraria = new tNG_multipleDelete($conn_unitas);
$tNGs->addTransaction($del_unts_tesouraria);
// Register triggers
$del_unts_tesouraria->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_unts_tesouraria->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_unts_tesouraria->setTable("unts_tesouraria");
$del_unts_tesouraria->setPrimaryKey("tesra_id", "NUMERIC_TYPE", "GET", "tesra_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_tesouraria = $tNGs->getRecordset("unts_tesouraria");
$row_rsunts_tesouraria = mysql_fetch_assoc($rsunts_tesouraria);
$totalRows_rsunts_tesouraria = mysql_num_rows($rsunts_tesouraria);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
if (@$_GET['tesra_id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Tesouraria </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsunts_tesouraria > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="tesra_descricao_<?php echo $cnt1; ?>">Descriçao:</label></td>
            <td><input type="text" name="tesra_descricao_<?php echo $cnt1; ?>" id="tesra_descricao_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_tesouraria['tesra_descricao']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("tesra_descricao");?> <?php echo $tNGs->displayFieldError("unts_tesouraria", "tesra_descricao", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="comun_id_<?php echo $cnt1; ?>">Comunidade:</label></td>
            <td><select name="comun_id_<?php echo $cnt1; ?>" id="comun_id_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_Recordset1['comun_id']?>"<?php if (!(strcmp($row_Recordset1['comun_id'], $row_rsunts_tesouraria['comun_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['comun_sigla']?></option>
              <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("unts_tesouraria", "comun_id", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="tesra_ativo_<?php echo $cnt1; ?>">Tesra_ativo:</label></td>
            <td><select name="tesra_ativo_<?php echo $cnt1; ?>" id="tesra_ativo_<?php echo $cnt1; ?>">
              <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute($row_rsunts_tesouraria['tesra_ativo'])))) {echo "SELECTED";} ?>>Sim</option>
              <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute($row_rsunts_tesouraria['tesra_ativo'])))) {echo "SELECTED";} ?>>Nao</option>
            </select>
              <?php echo $tNGs->displayFieldError("unts_tesouraria", "tesra_ativo", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_unts_tesouraria_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsunts_tesouraria['kt_pk_unts_tesouraria']); ?>" />
        <?php } while ($row_rsunts_tesouraria = mysql_fetch_assoc($rsunts_tesouraria)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['tesra_id'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'tesra_id')" />
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
?>
