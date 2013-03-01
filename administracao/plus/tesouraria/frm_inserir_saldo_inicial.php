<?php require_once('../../Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../../");

// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("saldo_valor", true, "double", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_unts_saldo_ini = new tNG_insert($conn_unitas);
$tNGs->addTransaction($ins_unts_saldo_ini);
// Register triggers
$ins_unts_saldo_ini->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_saldo_ini->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_saldo_ini->registerTrigger("END", "Trigger_Default_Redirect", 99, "frm_saldo_inicial.php?tesouraria={GET.tesouraria}&movimento={movim_id}");
// Add columns
$ins_unts_saldo_ini->setTable("unts_saldo_ini");
$ins_unts_saldo_ini->addColumn("movim_id", "NUMERIC_TYPE", "POST", "movim_id", "{GET.movimento}");
$ins_unts_saldo_ini->addColumn("conta_id", "NUMERIC_TYPE", "POST", "conta_id", "{GET.conta}");
$ins_unts_saldo_ini->addColumn("saldo_valor", "DOUBLE_TYPE", "POST", "saldo_valor");
$ins_unts_saldo_ini->addColumn("saldo_ativo", "STRING_TYPE", "POST", "saldo_ativo", "S");
$ins_unts_saldo_ini->setPrimaryKey("saldo_id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_saldo_ini = $tNGs->getRecordset("unts_saldo_ini");
$row_rsunts_saldo_ini = mysql_fetch_assoc($rsunts_saldo_ini);
$totalRows_rsunts_saldo_ini = mysql_num_rows($rsunts_saldo_ini);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../../includes/common/js/base.js" type="text/javascript"></script>
<script src="../../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
    <tr>
      <td class="KT_th"><label for="saldo_valor">Saldo Inicial:</label></td>
      <td><input type="text" name="saldo_valor" id="saldo_valor" value="<?php echo KT_escapeAttribute($row_rsunts_saldo_ini['saldo_valor']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("saldo_valor");?> <?php echo $tNGs->displayFieldError("unts_saldo_ini", "saldo_valor"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td colspan="2"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="Salvar" /></td>
    </tr>
  </table>
  <input type="hidden" name="movim_id" id="movim_id" value="<?php echo KT_escapeAttribute($row_rsunts_saldo_ini['movim_id']); ?>" />
  <input type="hidden" name="conta_id" id="conta_id" value="<?php echo KT_escapeAttribute($row_rsunts_saldo_ini['conta_id']); ?>" />
  <input type="hidden" name="saldo_ativo" id="saldo_ativo" value="<?php echo KT_escapeAttribute($row_rsunts_saldo_ini['saldo_ativo']); ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>