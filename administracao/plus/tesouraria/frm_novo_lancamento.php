<?php require_once('../../Connections/unitas.php'); ?>
<?php
//MX Widgets3 include
require_once('../../includes/wdg/WDG.php');

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
$formValidation->addField("movim_id", true, "numeric", "", "", "", "");
$formValidation->addField("item_data", true, "date", "", "", "", "");
$formValidation->addField("conta_id_e", true, "numeric", "", "", "", "");
$formValidation->addField("conta_id_s", true, "numeric", "", "", "", "");
$formValidation->addField("item_historico", true, "text", "", "", "", "");
$formValidation->addField("item_documento", true, "text", "", "", "", "");
$formValidation->addField("item_valor", true, "double", "", "", "", "");
$formValidation->addField("item_efetivado", true, "text", "", "", "", "");
$formValidation->addField("item_ativo", true, "text", "", "", "", "");
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

$tes_rs_conta_e = "-1";
if (isset($_GET['tesouraria'])) {
  $tes_rs_conta_e = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_conta_e = sprintf("SELECT * FROM unts_contas WHERE (conta_ativa = 'S' AND conta_visibilidade = 0) OR    (conta_ativa = 'S' AND conta_visibilidade = %s) ORDER BY conta_descricao ASC", GetSQLValueString($tes_rs_conta_e, "int"));
$rs_conta_e = mysql_query($query_rs_conta_e, $unitas) or die(mysql_error());
$row_rs_conta_e = mysql_fetch_assoc($rs_conta_e);
$totalRows_rs_conta_e = mysql_num_rows($rs_conta_e);

$tesour_rs_conta_s = "-1";
if (isset($_GET['tesouraria'])) {
  $tesour_rs_conta_s = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_conta_s = sprintf("SELECT * FROM unts_contas WHERE (conta_ativa = 'S' AND conta_visibilidade = 0) OR    (conta_ativa = 'S' AND conta_visibilidade = %s) ORDER BY conta_descricao ASC", GetSQLValueString($tesour_rs_conta_s, "int"));
$rs_conta_s = mysql_query($query_rs_conta_s, $unitas) or die(mysql_error());
$row_rs_conta_s = mysql_fetch_assoc($rs_conta_s);
$totalRows_rs_conta_s = mysql_num_rows($rs_conta_s);

// Make an insert transaction instance
$ins_unts_item_mov = new tNG_insert($conn_unitas);
$tNGs->addTransaction($ins_unts_item_mov);
// Register triggers
$ins_unts_item_mov->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_item_mov->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_item_mov->registerTrigger("END", "Trigger_Default_Redirect", 99, "/unitas/administracao/tesouraria/frm_tesouraria.php?tesouraria={GET.tesouraria}&movimento={GET.movimento}");
// Add columns
$ins_unts_item_mov->setTable("unts_item_mov");
$ins_unts_item_mov->addColumn("movim_id", "NUMERIC_TYPE", "POST", "movim_id", "{GET.movimento}");
$ins_unts_item_mov->addColumn("item_data", "DATE_TYPE", "POST", "item_data");
$ins_unts_item_mov->addColumn("conta_id_e", "NUMERIC_TYPE", "POST", "conta_id_e");
$ins_unts_item_mov->addColumn("conta_id_s", "NUMERIC_TYPE", "POST", "conta_id_s");
$ins_unts_item_mov->addColumn("item_historico", "STRING_TYPE", "POST", "item_historico");
$ins_unts_item_mov->addColumn("item_documento", "STRING_TYPE", "POST", "item_documento");
$ins_unts_item_mov->addColumn("item_valor", "DOUBLE_TYPE", "POST", "item_valor");
$ins_unts_item_mov->addColumn("item_efetivado", "STRING_TYPE", "POST", "item_efetivado");
$ins_unts_item_mov->addColumn("item_ativo", "STRING_TYPE", "POST", "item_ativo", "S");
$ins_unts_item_mov->setPrimaryKey("item_id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_item_mov = $tNGs->getRecordset("unts_item_mov");
$row_rsunts_item_mov = mysql_fetch_assoc($rsunts_item_mov);
$totalRows_rsunts_item_mov = mysql_num_rows($rsunts_item_mov);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../../includes/common/js/base.js" type="text/javascript"></script>
<script src="../../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script type="text/javascript" src="../../includes/common/js/sigslot_core.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../../includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../../includes/resources/calendar.js"></script>
</head>

<body>
<h1>Lan&ccedil;amento</h1>
<p>&nbsp;
  <?php
	echo $tNGs->getErrorMsg();
?>
<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
    <tr>
      <td class="KT_th"><label for="item_data">Data:</label></td>
      <td><input name="item_data" id="item_data" value="<?php echo KT_formatDate($row_rsunts_item_mov['item_data']); ?>" size="10" maxlength="10" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" />
      <?php echo $tNGs->displayFieldHint("item_data");?> <?php echo $tNGs->displayFieldError("unts_item_mov", "item_data"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="conta_id_e">Origem da Verba:</label></td>
      <td><select name="conta_id_e" id="conta_id_e">
        <?php 
do {  
?>
        <option value="<?php echo $row_rs_conta_e['conta_id']?>"<?php if (!(strcmp($row_rs_conta_e['conta_id'], $row_rsunts_item_mov['conta_id_e']))) {echo "SELECTED";} ?>><?php echo $row_rs_conta_e['conta_descricao']?></option>
        <?php
} while ($row_rs_conta_e = mysql_fetch_assoc($rs_conta_e));
  $rows = mysql_num_rows($rs_conta_e);
  if($rows > 0) {
      mysql_data_seek($rs_conta_e, 0);
	  $row_rs_conta_e = mysql_fetch_assoc($rs_conta_e);
  }
?>
      </select>
        <?php echo $tNGs->displayFieldError("unts_item_mov", "conta_id_e"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="conta_id_s">Destino da Verba:</label></td>
      <td><select name="conta_id_s" id="conta_id_s">
        <?php 
do {  
?>
        <option value="<?php echo $row_rs_conta_s['conta_id']?>"<?php if (!(strcmp($row_rs_conta_s['conta_id'], $row_rsunts_item_mov['conta_id_s']))) {echo "SELECTED";} ?>><?php echo $row_rs_conta_s['conta_descricao']?></option>
        <?php
} while ($row_rs_conta_s = mysql_fetch_assoc($rs_conta_s));
  $rows = mysql_num_rows($rs_conta_s);
  if($rows > 0) {
      mysql_data_seek($rs_conta_s, 0);
	  $row_rs_conta_s = mysql_fetch_assoc($rs_conta_s);
  }
?>
      </select>
        <?php echo $tNGs->displayFieldError("unts_item_mov", "conta_id_s"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="item_historico">Histórico:</label></td>
      <td><textarea name="item_historico" id="item_historico" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsunts_item_mov['item_historico']); ?></textarea>
        <?php echo $tNGs->displayFieldHint("item_historico");?> <?php echo $tNGs->displayFieldError("unts_item_mov", "item_historico"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="item_documento">Documento:</label></td>
      <td><input type="text" name="item_documento" id="item_documento" value="<?php echo KT_escapeAttribute($row_rsunts_item_mov['item_documento']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("item_documento");?> <?php echo $tNGs->displayFieldError("unts_item_mov", "item_documento"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="item_valor">Valor:</label></td>
      <td><input type="text" name="item_valor" id="item_valor" value="<?php echo KT_escapeAttribute($row_rsunts_item_mov['item_valor']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("item_valor");?> <?php echo $tNGs->displayFieldError("unts_item_mov", "item_valor"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="item_efetivado">Efetivado:</label></td>
      <td><select name="item_efetivado" id="item_efetivado">
        <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute($row_rsunts_item_mov['item_efetivado'])))) {echo "SELECTED";} ?>>Sim</option>
        <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute($row_rsunts_item_mov['item_efetivado'])))) {echo "SELECTED";} ?>>Nao</option>
      </select>
        <?php echo $tNGs->displayFieldError("unts_item_mov", "item_efetivado"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td colspan="2"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="movim_id" id="movim_id" value="<?php echo KT_escapeAttribute($row_rsunts_item_mov['movim_id']); ?>" />
  <input type="hidden" name="item_ativo" id="item_ativo" value="<?php echo KT_escapeAttribute($row_rsunts_item_mov['item_ativo']); ?>" />
</form>
<form id="form2" name="form2" method="post" action="/unitas/administracao/tesouraria/frm_tesouraria.php?movimento=<?php echo $_GET['movimento']; ?>">
  <label>
    <input type="submit" name="voltar" id="voltar" value="Finalizar" />
  </label>
</form>
<p>&nbsp;</p>
</p>
</body>
</html>
<?php
mysql_free_result($rs_conta_e);

mysql_free_result($rs_conta_s);
?>
