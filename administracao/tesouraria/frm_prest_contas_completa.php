<?php require_once('../../Connections/unitas.php'); ?>
<?php require_once("../../includes/common/KT_common.php");?>


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

$movim_rs_saldo_ini = "-1";
if (isset($_GET['movimento'])) {
  $movim_rs_saldo_ini = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_saldo_ini = sprintf("SELECT unts_contas . * , unts_saldo_ini . * FROM unts_saldo_ini LEFT JOIN unts_contas ON unts_contas.conta_id = unts_saldo_ini.conta_id WHERE movim_id = %s", GetSQLValueString($movim_rs_saldo_ini, "int"));
$rs_saldo_ini = mysql_query($query_rs_saldo_ini, $unitas) or die(mysql_error());
$row_rs_saldo_ini = mysql_fetch_assoc($rs_saldo_ini);
$totalRows_rs_saldo_ini = mysql_num_rows($rs_saldo_ini);

$tesour_rs_com_tesouraria = "-1";
if (isset($_GET['tesouraria'])) {
  $tesour_rs_com_tesouraria = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_com_tesouraria = sprintf("SELECT unts_tesouraria . * , unts_comunidade . * FROM unts_tesouraria INNER JOIN unts_comunidade ON unts_comunidade.comun_id = unts_tesouraria.comun_id WHERE tesra_id = %s", GetSQLValueString($tesour_rs_com_tesouraria, "int"));
$rs_com_tesouraria = mysql_query($query_rs_com_tesouraria, $unitas) or die(mysql_error());
$row_rs_com_tesouraria = mysql_fetch_assoc($rs_com_tesouraria);
$totalRows_rs_com_tesouraria = mysql_num_rows($rs_com_tesouraria);

$mov_rs_receitas = "-1";
if (isset($_GET['movimento'])) {
  $mov_rs_receitas = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_receitas = sprintf("SELECT unts_contas . * , SUM( item_valor ) AS tot_receita FROM unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_e WHERE movim_id = %s AND conta_tipo <> 'M' GROUP BY conta_id_e", GetSQLValueString($mov_rs_receitas, "int"));
$rs_receitas = mysql_query($query_rs_receitas, $unitas) or die(mysql_error());
$row_rs_receitas = mysql_fetch_assoc($rs_receitas);
$totalRows_rs_receitas = mysql_num_rows($rs_receitas);

$movim_rs_despesas = "-1";
if (isset($_GET['movimento'])) {
  $movim_rs_despesas = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_despesas = sprintf("SELECT unts_contas. * , SUM( item_valor ) AS tot_despesas FROM unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_s WHERE movim_id = %s AND conta_tipo <> 'M' GROUP BY conta_id_s", GetSQLValueString($movim_rs_despesas, "int"));
$rs_despesas = mysql_query($query_rs_despesas, $unitas) or die(mysql_error());
$row_rs_despesas = mysql_fetch_assoc($rs_despesas);
$totalRows_rs_despesas = mysql_num_rows($rs_despesas);

$movimto_rs_saldo_final = "-1";
if (isset($_GET['movimento'])) {
  $movimto_rs_saldo_final = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_saldo_final = sprintf("SELECT unts_contas . * , unts_saldo_ini . * FROM unts_saldo_ini LEFT JOIN unts_contas ON unts_contas.conta_id = unts_saldo_ini.conta_id WHERE movim_id = %s", GetSQLValueString($movimto_rs_saldo_final, "int"));
$rs_saldo_final = mysql_query($query_rs_saldo_final, $unitas) or die(mysql_error());
$row_rs_saldo_final = mysql_fetch_assoc($rs_saldo_final);
$totalRows_rs_saldo_final = mysql_num_rows($rs_saldo_final);

$movim_rs_movimento = "-1";
if (isset($_GET['movimento'])) {
  $movim_rs_movimento = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_movimento = sprintf("SELECT    unts_movimento.*,    unts_periodo.* FROM    unts_movimento INNER JOIN unts_periodo ON unts_periodo.perio_id = unts_movimento.perio_id WHERE    movim_id = %s", GetSQLValueString($movim_rs_movimento, "int"));
$rs_movimento = mysql_query($query_rs_movimento, $unitas) or die(mysql_error());
$row_rs_movimento = mysql_fetch_assoc($rs_movimento);
$totalRows_rs_movimento = mysql_num_rows($rs_movimento);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
</head>

<body>
<div id="botoes">
    <div id="btn_voltar" style="float:left">
    <form id="form1" name="form1" method="post" action="/unitas/administracao/tesouraria/frm_tesouraria.php?movimento=<?php echo $_GET['movimento']; ?>">
      <label>
        <input type="submit" name="voltar" id="voltar" value="Voltar" />
      </label>
    </form>
    </div>
    
        <div id="btn_imprimir" style="float:left">
    <form action="/unitas/administracao/tesouraria/frm_prest_contas_completa_imprimir.php?movimento=<?php echo $_GET['movimento'];?>&tesouraria=<?php echo $_GET['tesouraria']; ?>" method="post" name="form1" target="_blank" id="form1">
      <label>
        <input type="submit" name="imprimir" id="imprimir" value="Imprimir" />
      </label>
    </form>
  </div>
</div>
<p>&nbsp;</p>
<p align="center"><strong>PRESTA&Ccedil;&Atilde;O DE CONTAS</strong> - <strong><?php echo $row_rs_movimento['perio_mes']; ?>/<?php echo $row_rs_movimento['perio_ano']; ?></strong></p>
<table width="100%" border="1">
  <tr>
    <td colspan="2"><strong>SALDO INICIAL NO PER&Iacute;ODO</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rs_saldo_ini['conta_descricao']; ?></td>
      <td width="10%" align="right"><?php echo number_format($row_rs_saldo_ini['saldo_valor'],2,",","."); ?></td>
    </tr>
    <?php } while ($row_rs_saldo_ini = mysql_fetch_assoc($rs_saldo_ini)); ?>
</table><br />

<table width="100%" border="1">
  <tr>
    <td colspan="3"><strong>RECEITAS</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rs_receitas['conta_id']; ?></td>
      <td><?php echo $row_rs_receitas['conta_descricao']; ?></td>
      <td width="10%" align="right"><?php echo number_format($row_rs_receitas['tot_receita'],2,",","."); ?></td>
    </tr>
    <?php $tot_receitas = $tot_receitas + $row_rs_receitas['tot_receita'];?>
    <?php } while ($row_rs_receitas = mysql_fetch_assoc($rs_receitas)); ?>
<tr>
    <td colspan="2" align="right"><strong>Total das Receitas no M&ecirc;s</strong></td>
    <td width="10%" align="right"><strong><?php echo number_format($tot_receitas,2,",",".");?></strong></td>
  </tr>
</table><br />
<table width="100%" border="1">
  <tr>
    <td colspan="3"><strong>DESPESAS</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rs_despesas['conta_id']; ?></td>
      <td><?php echo $row_rs_despesas['conta_descricao']; ?></td>
      <td width="10%" align="right"><?php echo number_format($row_rs_despesas['tot_despesas'],2,",","."); ?></td>
    </tr>
    <?php $tot_despesas = $tot_despesas + $row_rs_despesas['tot_despesas'];?>
    <?php } while ($row_rs_despesas = mysql_fetch_assoc($rs_despesas)); ?>
<tr>
    <td colspan="2" align="right"><strong>Total das Despesas no m&ecirc;s</strong></td>
    <td width="10%" align="right"><strong><?php echo number_format($tot_despesas,2,",",".");?></strong></td>
  </tr>
</table>
<br />
<table width="100%" border="1">
  <tr>
    <td colspan="5" align="left"><strong>APURA&Ccedil;&Atilde;O DE SALDOS</strong></td>
  </tr>
  <tr align="center">
      <td><strong>Conta</strong></td>
      <td width="10%"><strong>Saldo Inicial</strong></td>
      <td width="10%"><strong>Entradas</strong></td>
      <td width="10%"><strong>Sa&iacute;das</strong></td>
      <td width="10%"><strong>Saldo Final</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rs_saldo_final['conta_id']; ?>-<?php echo $row_rs_saldo_final['conta_descricao']; ?></td>
      <td width="10%" align="right"><?php echo number_format($row_rs_saldo_final['saldo_valor'],2,",","."); ?></td>
      <td width="10%" align="right"><?php $total_entradas = calcula_entradas ($row_rs_saldo_final['conta_id'], 'o',$row_rs_saldo_final['movim_id']); echo number_format($total_entradas,2,",",".");?></td>
      <td width="10%" align="right"><?php $total_saidas = calcula_entradas($row_rs_saldo_final['conta_id'], 'd',$row_rs_saldo_final['movim_id']); echo number_format($total_saidas,2,",",".");?></td>
      <td width="10%" align="right"><?php $subtot = ($row_rs_saldo_final['saldo_valor'] + $total_entradas) - $total_saidas; echo number_format($subtot,2,",",".");?></td>
    </tr>
    <?php $tot_geral = $tot_geral + $subtot;?>
    <?php } while ($row_rs_saldo_final = mysql_fetch_assoc($rs_saldo_final)); ?>
<tr>
    <td colspan="4" align="right"><strong>Total Geral</strong></td>
    <td align="right"><strong><?php echo number_format($tot_geral,2,",",".");?></strong></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_saldo_ini);

mysql_free_result($rs_com_tesouraria);

mysql_free_result($rs_receitas);

mysql_free_result($rs_despesas);

mysql_free_result($rs_saldo_final);

mysql_free_result($rs_movimento);
?>
