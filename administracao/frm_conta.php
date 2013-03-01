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
$formValidation->addField("conta_tipo", true, "text", "", "", "", "");
$formValidation->addField("conta_descricao", true, "text", "", "", "", "");
$formValidation->addField("conta_ativa", true, "text", "", "", "", "");
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
$query_rs_tesouraria = "SELECT * FROM unts_tesouraria ORDER BY unts_tesouraria.tesra_descricao";
$rs_tesouraria = mysql_query($query_rs_tesouraria, $unitas) or die(mysql_error());
$row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria);
$totalRows_rs_tesouraria = mysql_num_rows($rs_tesouraria);

mysql_select_db($database_unitas, $unitas);
$query_Recordset2 = "SELECT comun_razao_social, comun_id FROM unts_comunidade ORDER BY comun_razao_social";
$Recordset2 = mysql_query($query_Recordset2, $unitas) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

// Make an insert transaction instance
$ins_unts_contas = new tNG_multipleInsert($conn_unitas);
$tNGs->addTransaction($ins_unts_contas);
// Register triggers
$ins_unts_contas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_contas->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_contas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_unts_contas->setTable("unts_contas");
$ins_unts_contas->addColumn("conta_tipo", "STRING_TYPE", "POST", "conta_tipo");
$ins_unts_contas->addColumn("conta_descricao", "STRING_TYPE", "POST", "conta_descricao");
$ins_unts_contas->addColumn("conta_visibilidade", "NUMERIC_TYPE", "POST", "conta_visibilidade");
$ins_unts_contas->addColumn("conta_ativa", "STRING_TYPE", "POST", "conta_ativa");
$ins_unts_contas->setPrimaryKey("conta_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_unts_contas = new tNG_multipleUpdate($conn_unitas);
$tNGs->addTransaction($upd_unts_contas);
// Register triggers
$upd_unts_contas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_unts_contas->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_unts_contas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_unts_contas->setTable("unts_contas");
$upd_unts_contas->addColumn("conta_tipo", "STRING_TYPE", "POST", "conta_tipo");
$upd_unts_contas->addColumn("conta_descricao", "STRING_TYPE", "POST", "conta_descricao");
$upd_unts_contas->addColumn("conta_visibilidade", "NUMERIC_TYPE", "POST", "conta_visibilidade");
$upd_unts_contas->addColumn("conta_ativa", "STRING_TYPE", "POST", "conta_ativa");
$upd_unts_contas->setPrimaryKey("conta_id", "NUMERIC_TYPE", "GET", "conta_id");

// Make an instance of the transaction object
$del_unts_contas = new tNG_multipleDelete($conn_unitas);
$tNGs->addTransaction($del_unts_contas);
// Register triggers
$del_unts_contas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_unts_contas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_unts_contas->setTable("unts_contas");
$del_unts_contas->setPrimaryKey("conta_id", "NUMERIC_TYPE", "GET", "conta_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_contas = $tNGs->getRecordset("unts_contas");
$row_rsunts_contas = mysql_fetch_assoc($rsunts_contas);
$totalRows_rsunts_contas = mysql_num_rows($rsunts_contas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
if (@$_GET['conta_id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Conta
  </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsunts_contas > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="conta_tipo_<?php echo $cnt1; ?>">Tipo:</label></td>
            <td><select name="conta_tipo_<?php echo $cnt1; ?>" id="conta_tipo_<?php echo $cnt1; ?>">
              <option value="R" <?php if (!(strcmp("R", KT_escapeAttribute($row_rsunts_contas['conta_tipo'])))) {echo "SELECTED";} ?>>Receita</option>
              <option value="D" <?php if (!(strcmp("D", KT_escapeAttribute($row_rsunts_contas['conta_tipo'])))) {echo "SELECTED";} ?>>Despesa</option>
              <option value="M" <?php if (!(strcmp("M", KT_escapeAttribute($row_rsunts_contas['conta_tipo'])))) {echo "SELECTED";} ?>>Movimentaçao</option>
            </select>
              <?php echo $tNGs->displayFieldError("unts_contas", "conta_tipo", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="conta_descricao_<?php echo $cnt1; ?>">Descriçao:</label></td>
            <td><input type="text" name="conta_descricao_<?php echo $cnt1; ?>" id="conta_descricao_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_contas['conta_descricao']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("conta_descricao");?> <?php echo $tNGs->displayFieldError("unts_contas", "conta_descricao", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="conta_visibilidade_<?php echo $cnt1; ?>">Visibilidade:</label></td>
            <td><select name="conta_visibilidade_<?php echo $cnt1; ?>" id="conta_visibilidade_<?php echo $cnt1; ?>">
              <option value="0" <?php if (!(strcmp(0, $row_rsunts_contas['conta_visibilidade']))) {echo "selected=\"selected\"";} ?>><?php echo NXT_getResource("Select one..."); ?></option>
              <?php
do {  
?>
              <option value="<?php echo $row_rs_tesouraria['tesra_id']?>"<?php if (!(strcmp($row_rs_tesouraria['tesra_id'], $row_rsunts_contas['conta_visibilidade']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_tesouraria['tesra_descricao']?></option>
              <?php
} while ($row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria));
  $rows = mysql_num_rows($rs_tesouraria);
  if($rows > 0) {
      mysql_data_seek($rs_tesouraria, 0);
	  $row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("unts_contas", "conta_visibilidade", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="conta_ativa_<?php echo $cnt1; ?>">Ativa:</label></td>
            <td><select name="conta_ativa_<?php echo $cnt1; ?>" id="conta_ativa_<?php echo $cnt1; ?>">
              <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute($row_rsunts_contas['conta_ativa'])))) {echo "SELECTED";} ?>>Sim</option>
              <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute($row_rsunts_contas['conta_ativa'])))) {echo "SELECTED";} ?>>Nao</option>
            </select>
              <?php echo $tNGs->displayFieldError("unts_contas", "conta_ativa", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_unts_contas_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsunts_contas['kt_pk_unts_contas']); ?>" />
        <?php } while ($row_rsunts_contas = mysql_fetch_assoc($rsunts_contas)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['conta_id'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'conta_id')" />
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
</body>
</html>
<?php
mysql_free_result($rs_tesouraria);

mysql_free_result($Recordset2);
?>
