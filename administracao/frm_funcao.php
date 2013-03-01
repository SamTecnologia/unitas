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
$formValidation->addField("func_descricao", true, "text", "", "", "", "");
$formValidation->addField("func_ativa", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_unts_funcao = new tNG_multipleInsert($conn_unitas);
$tNGs->addTransaction($ins_unts_funcao);
// Register triggers
$ins_unts_funcao->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_funcao->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_funcao->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_unts_funcao->setTable("unts_funcao");
$ins_unts_funcao->addColumn("func_descricao", "STRING_TYPE", "POST", "func_descricao");
$ins_unts_funcao->addColumn("func_ativa", "STRING_TYPE", "POST", "func_ativa");
$ins_unts_funcao->setPrimaryKey("func_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_unts_funcao = new tNG_multipleUpdate($conn_unitas);
$tNGs->addTransaction($upd_unts_funcao);
// Register triggers
$upd_unts_funcao->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_unts_funcao->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_unts_funcao->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_unts_funcao->setTable("unts_funcao");
$upd_unts_funcao->addColumn("func_descricao", "STRING_TYPE", "POST", "func_descricao");
$upd_unts_funcao->addColumn("func_ativa", "STRING_TYPE", "POST", "func_ativa");
$upd_unts_funcao->setPrimaryKey("func_id", "NUMERIC_TYPE", "GET", "func_id");

// Make an instance of the transaction object
$del_unts_funcao = new tNG_multipleDelete($conn_unitas);
$tNGs->addTransaction($del_unts_funcao);
// Register triggers
$del_unts_funcao->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_unts_funcao->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_unts_funcao->setTable("unts_funcao");
$del_unts_funcao->setPrimaryKey("func_id", "NUMERIC_TYPE", "GET", "func_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_funcao = $tNGs->getRecordset("unts_funcao");
$row_rsunts_funcao = mysql_fetch_assoc($rsunts_funcao);
$totalRows_rsunts_funcao = mysql_num_rows($rsunts_funcao);
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
if (@$_GET['func_id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Fun&ccedil;&atilde;o </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsunts_funcao > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="func_descricao_<?php echo $cnt1; ?>">Descriçao:</label></td>
            <td><input type="text" name="func_descricao_<?php echo $cnt1; ?>" id="func_descricao_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_funcao['func_descricao']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("func_descricao");?> <?php echo $tNGs->displayFieldError("unts_funcao", "func_descricao", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="func_ativa_<?php echo $cnt1; ?>">Ativa:</label></td>
            <td><select name="func_ativa_<?php echo $cnt1; ?>" id="func_ativa_<?php echo $cnt1; ?>">
              <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute($row_rsunts_funcao['func_ativa'])))) {echo "SELECTED";} ?>>Sim</option>
              <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute($row_rsunts_funcao['func_ativa'])))) {echo "SELECTED";} ?>>Nao</option>
            </select>
              <?php echo $tNGs->displayFieldError("unts_funcao", "func_ativa", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_unts_funcao_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsunts_funcao['kt_pk_unts_funcao']); ?>" />
        <?php } while ($row_rsunts_funcao = mysql_fetch_assoc($rsunts_funcao)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['func_id'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'func_id')" />
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