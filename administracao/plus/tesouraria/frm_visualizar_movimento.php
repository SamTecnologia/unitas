<?php require_once('../../../Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../../../includes/common/KT_common.php');
?>
<?php require_once("../../../includes/common/KT_common.php");?>


<?php
	function calcula_entradas ($conta, $tipo, $movim)
	{
		if ($tipo == 'o') {$cta = 'conta_id_s';$nome = 'saida';}elseif($tipo == 'd'){$cta = 'conta_id_e';$nome='entrada';} 
		
		$query_rs_calculo = 'SELECT SUM(item_valor) AS '.$nome.' FROM unts_item_mov WHERE '. $cta. '='. $conta.' AND movim_id='.$movim;
		$rs_calculo = mysql_query($query_rs_calculo);
		$row_rs_calculo = mysql_fetch_assoc($rs_calculo);
		$totalRows_rs_calculo = mysql_num_rows($rs_calculo);
		if ($tipo == 'o') {return $row_rs_calculo['saida'];} elseif($tipo == 'd'){return $row_rs_calculo['entrada'];}
		#echo $query_rs_calculo;
	}
?>


<?php
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

$nro_movimento_rs_itens = "-1";
if (isset($_GET['movimento'])) {
  $nro_movimento_rs_itens = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_itens = sprintf("SELECT   unts_item_mov.*,   unts_movimento.tesra_id FROM   unts_item_mov INNER JOIN unts_movimento ON unts_movimento.movim_id = unts_item_mov.movim_id WHERE unts_item_mov.movim_id = %s AND unts_item_mov.item_ativo = 'S' ORDER BY unts_item_mov.item_data", GetSQLValueString($nro_movimento_rs_itens, "int"));
$rs_itens = mysql_query($query_rs_itens, $unitas) or die(mysql_error());
$row_rs_itens = mysql_fetch_assoc($rs_itens);
$totalRows_rs_itens = mysql_num_rows($rs_itens);

$movim_rs_saldoinicial = "-1";
if (isset($_GET['movimento'])) {
  $movim_rs_saldoinicial = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_saldoinicial = sprintf("SELECT unts_saldo_ini . * , unts_contas . * FROM unts_saldo_ini LEFT JOIN unts_contas ON unts_contas.conta_id = unts_saldo_ini.conta_id WHERE unts_saldo_ini.movim_id = %s", GetSQLValueString($movim_rs_saldoinicial, "int"));
$rs_saldoinicial = mysql_query($query_rs_saldoinicial, $unitas) or die(mysql_error());
$row_rs_saldoinicial = mysql_fetch_assoc($rs_saldoinicial);
$totalRows_rs_saldoinicial = mysql_num_rows($rs_saldoinicial);

$movto_rs_tot_saidas = "-1";
if (isset($_GET['movimento'])) {
  $movto_rs_tot_saidas = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_tot_saidas = sprintf("SELECT unts_saldo_ini . * , SUM( unts_item_mov.item_valor ) FROM unts_saldo_ini LEFT JOIN unts_item_mov ON unts_item_mov.conta_id_e = unts_saldo_ini.conta_id WHERE unts_saldo_ini.movim_id =%s GROUP BY unts_saldo_ini.conta_id", GetSQLValueString($movto_rs_tot_saidas, "int"));
$rs_tot_saidas = mysql_query($query_rs_tot_saidas, $unitas) or die(mysql_error());
$row_rs_tot_saidas = mysql_fetch_assoc($rs_tot_saidas);
$totalRows_rs_tot_saidas = mysql_num_rows($rs_tot_saidas);

$mvto_rs_tot_entradas = "-1";
if (isset($_GET['movimento'])) {
  $mvto_rs_tot_entradas = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_tot_entradas = sprintf("SELECT unts_saldo_ini . * , SUM( unts_item_mov.item_valor ) FROM unts_saldo_ini LEFT JOIN unts_item_mov ON unts_item_mov.conta_id_s = unts_saldo_ini.conta_id WHERE unts_saldo_ini.movim_id =%s GROUP BY unts_saldo_ini.conta_id", GetSQLValueString($mvto_rs_tot_entradas, "int"));
$rs_tot_entradas = mysql_query($query_rs_tot_entradas, $unitas) or die(mysql_error());
$row_rs_tot_entradas = mysql_fetch_assoc($rs_tot_entradas);
$totalRows_rs_tot_entradas = mysql_num_rows($rs_tot_entradas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
</head>

<body>
<h1>Movimento Financeiro</h1>
<form id="form1" name="form1" method="post" action="/unitas/administracao/plus/tesouraria/frm_tesouraria.php?movimento=<?php echo $_GET['movimento']; ?>">
    <label>
      <input type="submit" name="voltar" id="voltar" value="Voltar" />
    </label>
</form>
<table width="100%" border="1">
  <tr>
    <td align="center"><strong>N&ordm;</strong></td>
    <td align="center"><strong>Data</strong></td>
    <td align="center"><strong>Hist&oacute;rico</strong></td>
    <td align="center"><strong>Documento</strong></td>
    <td align="center"><strong>Efetivado</strong></td>
    <td align="center"><strong>Valor</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td height="24"><?php $cont = $cont + 1; echo $cont;?></td>
      <td><?php echo KT_FormatDate($row_rs_itens['item_data']); ?></td>
      <td><?php echo $row_rs_itens['item_historico']; ?></td>
      <td><?php echo $row_rs_itens['item_documento']; ?></td>
      <td><?php echo $row_rs_itens['item_efetivado']; ?></td>
      <td align="right"><?php echo number_format($row_rs_itens['item_valor'],2,",","."); ?></td>
    </tr>
    <?php } while ($row_rs_itens = mysql_fetch_assoc($rs_itens)); ?>
</table>
<p>Apura&ccedil;&atilde;o dos Resultados:</p>
<table width="100%" border="1">
  <tr>
    <td align="center"><strong>Conta de Movimento</strong></td>
    <td align="center"><strong>Saldo Incial</strong></td>
    <td align="center"><strong>Entradas</strong></td>
    <td align="center"><strong>Sa&iacute;das</strong></td>
    <td align="center"><strong>Saldo Final</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rs_saldoinicial['conta_descricao']; ?></td>
      <td align="right"><?php $saldo_inicial = $row_rs_saldoinicial['saldo_valor']; echo number_format($saldo_inicial,2,",",".");?></td>
      <td align="right"><?php $total_entradas = calcula_entradas ($row_rs_saldoinicial['conta_id'], 'o',$row_rs_saldoinicial['movim_id']); echo number_format($total_entradas,2,",",".");?></td>
      <td align="right"><?php $total_saidas = calcula_entradas($row_rs_saldoinicial['conta_id'], 'd',$row_rs_saldoinicial['movim_id']); echo number_format($total_saidas,2,",",".");?></td>
      <td align="right"><?php $valor_final = (($saldo_inicial + $total_entradas)- $total_saidas); echo number_format($valor_final,2,",",".");?></td>
    </tr>
    <?php } while ($row_rs_saldoinicial = mysql_fetch_assoc($rs_saldoinicial)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_itens);

mysql_free_result($rs_saldoinicial);

mysql_free_result($rs_tot_saidas);

mysql_free_result($rs_tot_entradas);
?>
