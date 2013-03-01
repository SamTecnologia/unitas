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
$formValidation->addField("perio_id", true, "numeric", "", "", "", "");
$formValidation->addField("movim_data_ini", true, "date", "", "", "", "");
$formValidation->addField("movim_data_fim", true, "date", "", "", "", "");
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
$query_rs_periodo = "SELECT    CONCAT( perio_mes, '/', perio_ano ) AS perio_descricao,    unts_periodo . * FROM unts_periodo WHERE unts_periodo.perio_ativo = 'S'";
$rs_periodo = mysql_query($query_rs_periodo, $unitas) or die(mysql_error());
$row_rs_periodo = mysql_fetch_assoc($rs_periodo);
$totalRows_rs_periodo = mysql_num_rows($rs_periodo);

// Make an insert transaction instance
$ins_unts_movimento = new tNG_insert($conn_unitas);
$tNGs->addTransaction($ins_unts_movimento);
// Register triggers
$ins_unts_movimento->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_movimento->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_movimento->registerTrigger("END", "Trigger_Default_Redirect", 99, "/unitas/administracao/tesouraria/frm_saldo_inicial.php?tesouraria={GET.tesouraria}&movimento={movim_id}");
// Add columns
$ins_unts_movimento->setTable("unts_movimento");
$ins_unts_movimento->addColumn("tesra_id", "NUMERIC_TYPE", "POST", "tesra_id", "{GET.tesouraria}");
$ins_unts_movimento->addColumn("perio_id", "NUMERIC_TYPE", "POST", "perio_id");
$ins_unts_movimento->addColumn("movim_data_ini", "DATE_TYPE", "POST", "movim_data_ini");
$ins_unts_movimento->addColumn("movim_data_fim", "DATE_TYPE", "POST", "movim_data_fim");
$ins_unts_movimento->addColumn("movim_ativo", "STRING_TYPE", "POST", "movim_ativo", "S");
$ins_unts_movimento->setPrimaryKey("movim_id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_movimento = $tNGs->getRecordset("unts_movimento");
$row_rsunts_movimento = mysql_fetch_assoc($rsunts_movimento);
$totalRows_rsunts_movimento = mysql_num_rows($rsunts_movimento);
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
<h1>Novo Movimento</h1>
<?php
	echo $tNGs->getErrorMsg();
?>
<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
    <tr>
      <td class="KT_th"><label for="perio_id">Período:</label></td>
      <td><select name="perio_id" id="perio_id">
        <?php 
do {  
?>
        <option value="<?php echo $row_rs_periodo['perio_id']?>"<?php if (!(strcmp($row_rs_periodo['perio_id'], $row_rsunts_movimento['perio_id']))) {echo "SELECTED";} ?>><?php echo $row_rs_periodo['perio_descricao']?></option>
        <?php
} while ($row_rs_periodo = mysql_fetch_assoc($rs_periodo));
  $rows = mysql_num_rows($rs_periodo);
  if($rows > 0) {
      mysql_data_seek($rs_periodo, 0);
	  $row_rs_periodo = mysql_fetch_assoc($rs_periodo);
  }
?>
      </select>
        <?php echo $tNGs->displayFieldError("unts_movimento", "perio_id"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="movim_data_ini">Data Incial:</label></td>
      <td><input name="movim_data_ini" id="movim_data_ini" value="<?php echo KT_formatDate($row_rsunts_movimento['movim_data_ini']); ?>" size="32" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" />
      <?php echo $tNGs->displayFieldHint("movim_data_ini");?> <?php echo $tNGs->displayFieldError("unts_movimento", "movim_data_ini"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="movim_data_fim">Data Final:</label></td>
      <td><input name="movim_data_fim" id="movim_data_fim" value="<?php echo KT_formatDate($row_rsunts_movimento['movim_data_fim']); ?>" size="32" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" />
        <?php echo $tNGs->displayFieldHint("movim_data_fim");?> <?php echo $tNGs->displayFieldError("unts_movimento", "movim_data_fim"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="movim_ativo">Ativo:</label></td>
      <td><input type="text" name="movim_ativo" id="movim_ativo" value="<?php echo KT_escapeAttribute($row_rsunts_movimento['movim_ativo']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("movim_ativo");?> <?php echo $tNGs->displayFieldError("unts_movimento", "movim_ativo"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td colspan="2"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="tesra_id" id="tesra_id" value="<?php echo KT_escapeAttribute($row_rsunts_movimento['tesra_id']); ?>" />
</form>
</form>
<form id="form2" name="form2" method="post" action="/unitas/administracao/tesouraria/frm_seleciona_movimento.php?tesouraria=<?php echo $_GET['tesouraria']; ?>">
  <label>
    <input type="submit" name="voltar" id="voltar" value="voltar" />
  </label>
</form>
</body>
</html>
<?php
mysql_free_result($rs_periodo);
?>
