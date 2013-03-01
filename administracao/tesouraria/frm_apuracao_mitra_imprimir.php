<?php require_once('../../Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../../includes/common/KT_common.php');
?>
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

$nro_movimento_rs_fundo_paroquial = "-1";
if (isset($_GET['movimento'])) {
  $nro_movimento_rs_fundo_paroquial = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_fundo_paroquial = sprintf("SELECT unts_contas . * , SUM( item_valor ) AS tot_receita FROM unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_e WHERE movim_id =%s AND conta_tipo <> 'M' AND conta_descricao LIKE '%%Fundo Paroquial%%' GROUP BY conta_id_e ORDER BY conta_descricao", GetSQLValueString($nro_movimento_rs_fundo_paroquial, "int"));
$rs_fundo_paroquial = mysql_query($query_rs_fundo_paroquial, $unitas) or die(mysql_error());
$row_rs_fundo_paroquial = mysql_fetch_assoc($rs_fundo_paroquial);
$totalRows_rs_fundo_paroquial = mysql_num_rows($rs_fundo_paroquial);

$encsoc_movim_rs_encargos_sociais = "-1";
if (isset($_GET['movimento'])) {
  $encsoc_movim_rs_encargos_sociais = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_encargos_sociais = sprintf("SELECT   unts_contas.*,   SUM( item_valor ) AS tot_receita FROM   unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_e WHERE   movim_id = %s AND   conta_tipo <> 'M' AND   conta_descricao LIKE '%%Encargos Sociais%%' GROUP BY conta_id_e ORDER BY conta_descricao", GetSQLValueString($encsoc_movim_rs_encargos_sociais, "int"));
$rs_encargos_sociais = mysql_query($query_rs_encargos_sociais, $unitas) or die(mysql_error());
$row_rs_encargos_sociais = mysql_fetch_assoc($rs_encargos_sociais);
$totalRows_rs_encargos_sociais = mysql_num_rows($rs_encargos_sociais);

$cenfor_rs_centro_formacao = "-1";
if (isset($_GET['movimento'])) {
  $cenfor_rs_centro_formacao = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_centro_formacao = sprintf("SELECT   unts_contas.*,   SUM( item_valor ) AS tot_receita FROM   unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_e WHERE   movim_id = %s AND   conta_tipo <> 'M' AND   conta_descricao LIKE '%%Repasse Centro de Forma%%' GROUP BY conta_id_e ORDER BY conta_descricao", GetSQLValueString($cenfor_rs_centro_formacao, "int"));
$rs_centro_formacao = mysql_query($query_rs_centro_formacao, $unitas) or die(mysql_error());
$row_rs_centro_formacao = mysql_fetch_assoc($rs_centro_formacao);
$totalRows_rs_centro_formacao = mysql_num_rows($rs_centro_formacao);

$ofeesp_movim_rs_oferta_especifica = "-1";
if (isset($_GET['movimento'])) {
  $ofeesp_movim_rs_oferta_especifica = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_oferta_especifica = sprintf("SELECT unts_contas . * , SUM( item_valor ) AS tot_receita FROM unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_e WHERE movim_id =%s AND conta_tipo <> 'M' AND conta_descricao LIKE '%%Oferta espec%%' GROUP BY conta_id_e ORDER BY conta_descricao", GetSQLValueString($ofeesp_movim_rs_oferta_especifica, "int"));
$rs_oferta_especifica = mysql_query($query_rs_oferta_especifica, $unitas) or die(mysql_error());
$row_rs_oferta_especifica = mysql_fetch_assoc($rs_oferta_especifica);
$totalRows_rs_oferta_especifica = mysql_num_rows($rs_oferta_especifica);

$outras_movim_rs_outras = "-1";
if (isset($_GET['movimento'])) {
  $outras_movim_rs_outras = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_outras = sprintf("SELECT unts_contas . * , SUM( item_valor ) AS tot_receita FROM unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_e WHERE movim_id =%s AND conta_tipo <> 'M' AND conta_descricao NOT LIKE '%%Fundo Paroquial%%' AND conta_descricao NOT LIKE '%%Encargos Sociais%%' AND conta_descricao NOT LIKE '%%ROferta Especifica%%' AND conta_descricao NOT LIKE '%%Repasse Centro de Forma%%' GROUP BY conta_id_e ORDER BY conta_descricao", GetSQLValueString($outras_movim_rs_outras, "int"));
$rs_outras = mysql_query($query_rs_outras, $unitas) or die(mysql_error());
$row_rs_outras = mysql_fetch_assoc($rs_outras);
$totalRows_rs_outras = mysql_num_rows($rs_outras);

$cheque_movim_rs_cheques = "-1";
if (isset($_GET['movimento'])) {
  $cheque_movim_rs_cheques = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_cheques = sprintf("SELECT unts_item_mov.*,   unts_movimento.tesra_id FROM unts_item_mov INNER JOIN unts_movimento ON unts_movimento.movim_id = unts_item_mov.movim_id WHERE unts_item_mov.movim_id = %s AND unts_item_mov.conta_id_e = 68 AND unts_item_mov.item_ativo = 'S' ORDER BY unts_item_mov.item_documento, unts_item_mov.item_data", GetSQLValueString($cheque_movim_rs_cheques, "int"));
$rs_cheques = mysql_query($query_rs_cheques, $unitas) or die(mysql_error());
$row_rs_cheques = mysql_fetch_assoc($rs_cheques);
$totalRows_rs_cheques = mysql_num_rows($rs_cheques);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
</head>

<body>
<table width="100%">
  <tr>
    <td width="10%" height="93" align="left" valign="middle"><p><img src="/unitas/images/psmd_logo.png" width="60" height="60" alt="Logomarca Par&oacute;quia santa M&atilde;e de Deus" /></p></td>
    <td width="79%" align="center"><strong><?php echo $row_rs_com_tesouraria['comun_sigla']; ?> - <?php echo $row_rs_com_tesouraria['comun_razao_social']; ?></strong><br />
      Par&oacute;quia Santa M&atilde;e de Deus - Ibes<br />
      Arquidiocese de Vit&oacute;ria-ES</td>
    <td width="11%" align="right"><br />
    <img src="/unitas/images/aves_logo.png" width="60" height="60" alt="Logomarca da Arquidiocese de Vit&oacute;ria-ES" /></td>
  </tr>
</table>
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
</table>
<strong><br />
</strong>
<div id="entradas" style="border: 1px solid black">
  <p><strong>ENTRADAS: RECEBIMENTOS</strong></p>
<table width="100%" border="1">
  <tr>
    <td colspan="3"><strong>Fundo Paroquial Recebido das Comunidades</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rs_fundo_paroquial['conta_id']; ?></td>
    <td><?php echo $row_rs_fundo_paroquial['conta_descricao']; ?></td>
    <td width="10%" align="right"><?php echo number_format($row_rs_fundo_paroquial['tot_receita'],2,",","."); ?></td>
  </tr>
  <?php $tot_fundo_paroquial = $tot_fundo_paroquial + $row_rs_fundo_paroquial['tot_receita'];?>
  <?php } while ($row_rs_fundo_paroquial = mysql_fetch_assoc($rs_fundo_paroquial)); ?>
  <tr>
    <td colspan="2" align="right"><strong>Total das Receitas no M&ecirc;s</strong></td>
    <td width="10%" align="right"><strong><?php echo number_format($tot_fundo_paroquial,2,",",".");?></strong></td>
  </tr>
</table>
<br />
<table width="100%" border="1">
  <tr>
    <td colspan="3"><strong>Encargos Sociais Recebidos das Comunidades</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rs_encargos_sociais['conta_id']; ?></td>
    <td><?php echo $row_rs_encargos_sociais['conta_descricao']; ?></td>
    <td width="10%" align="right"><?php echo number_format($row_rs_encargos_sociais['tot_receita'],2,",","."); ?></td>
  </tr>
  <?php $tot_encargos_sociais = $tot_encargos_sociais + $row_rs_encargos_sociais['tot_receita'];?>
  <?php } while ($row_rs_encargos_sociais = mysql_fetch_assoc($rs_encargos_sociais)); ?>
  <tr>
    <td colspan="2" align="right"><strong>Total das Receitas no M&ecirc;s</strong></td>
    <td width="10%" align="right"><strong><?php echo number_format($tot_encargos_sociais,2,",",".");?></strong></td>
  </tr>
</table>
<br />
<table width="100%" border="1">
  <tr>
    <td colspan="3"><strong>Repasse Centro de Forma&ccedil;&atilde;o</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rs_centro_formacao['conta_id']; ?></td>
    <td><?php echo $row_rs_centro_formacao['conta_descricao']; ?></td>
    <td width="10%" align="right"><?php echo number_format($row_rs_centro_formacao['tot_receita'],2,",","."); ?></td>
  </tr>
  <?php $tot_centro_formacao = $tot_centro_formacao + $row_rs_centro_formacao['tot_receita'];?>
  <?php } while ($row_rs_centro_formacao = mysql_fetch_assoc($rs_centro_formacao)); ?>
  <tr>
    <td colspan="2" align="right"><strong>Total das Receitas no M&ecirc;s</strong></td>
    <td width="10%" align="right"><strong><?php echo number_format($tot_centro_formacao,2,",",".");?></strong></td>
  </tr>
</table>
<br />
<table width="100%" border="1">
  <tr>
    <td colspan="3"><strong>Ofertas espec&iacute;ficas Recebidas das Comunidades</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rs_oferta_especifica['conta_id']; ?></td>
    <td><?php echo $row_rs_oferta_especifica['conta_descricao']; ?></td>
    <td width="10%" align="right"><?php echo number_format($row_rs_oferta_especifica['tot_receita'],2,",","."); ?></td>
  </tr>
  <?php $tot_oferta_especifica = $tot_oferta_especifica + $row_rs_oferta_especifica['tot_receita'];?>
  <?php } while ($row_rs_oferta_especifica = mysql_fetch_assoc($rs_oferta_especifica)); ?>
  <tr>
    <td colspan="2" align="right"><strong>Total das Receitas no M&ecirc;s</strong></td>
    <td width="10%" align="right"><strong><?php echo number_format($tot_oferta_especifica,2,",",".");?></strong></td>
  </tr>
</table>
<table width="100%" border="1">
  <tr>
    <td colspan="3"><strong>Outras Receitas</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rs_outras['conta_id']; ?></td>
    <td><?php echo $row_rs_outras['conta_descricao']; ?></td>
    <td width="10%" align="right"><?php echo number_format($row_rs_outras['tot_receita'],2,",","."); ?></td>
  </tr>
  <?php $tot_outras = $tot_outras + $row_rs_outras['tot_receita'];?>
  <?php } while ($row_rs_outras = mysql_fetch_assoc($rs_outras)); ?>
  <tr>
    <td colspan="2" align="right"><strong>Total das Receitas no M&ecirc;s</strong></td>
    <td width="10%" align="right"><strong><?php echo number_format($tot_outras,2,",",".");?></strong></td>
  </tr>
</table>
</div>
<br />
<div id="saidas" style="border: 1px solid black">
<p><strong>PAGAMENTOS</strong></p>
<table width="100%" border="1" cellspacing="0">
  <tr>
    <td align="center"><strong>N&ordm;</strong></td>
    <td align="center"><strong>Data</strong></td>
    <td align="center"><strong>Documento</strong></td>
    <td align="center"><strong>Hist&oacute;rico</strong></td>
    <td align="center"><strong>Valor</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td height="24"><?php $cont = $cont + 1; echo $cont;?></td>
    <td><?php echo KT_FormatDate($row_rs_cheques['item_data']); ?></td>
    <td><?php echo $row_rs_cheques['item_documento']; ?></td>
    <td><?php echo $row_rs_cheques['item_historico']; ?></td>
    <td align="right"><?php echo number_format($row_rs_cheques['item_valor'],2,",","."); ?><?php $tot_cheques = $tot_cheques + $row_rs_cheques['item_valor'];?></td>
  </tr>
  <?php } while ($row_rs_cheques = mysql_fetch_assoc($rs_cheques)); ?>
  <tr>
    <td height="24" colspan="4" align="right">Total de Pagamentos</td>
    <td align="right"><?php echo number_format($tot_cheques,2,",","."); ?></td>
  </tr>
</table>
</div>
<br />
<div id="apura_mitra" style="border: 1px solid black">
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td>BASE PARA C&Aacute;LCULO DE REPASSE &Agrave; MITRA</td>
    <td align="right"><?php echo number_format($tot_fundo_paroquial,2,",",".");?></td>
  </tr>
  <tr>
    <td>Repasse para a Mitra (14%)</td>
    <td align="right"><?php $mitra = $tot_fundo_paroquial * 0.14; echo number_format($mitra,2,",",".");?></td>
  </tr>
  <tr>
    <td>Repasse Fundo Presbiteral (4%)</td>
    <td align="right"><?php $presb = $tot_fundo_paroquial * 0.04; echo number_format($presb,2,",",".");?></td>
  </tr>
  <tr>
    <td>Repasse para o Semin&aacute;rio (3%)</td>
    <td align="right"><?php $semin = $tot_fundo_paroquial * 0.03; echo number_format($semin,2,",",".");?></td>
  </tr>
  <tr>
    <td>Repasse para a Funda&ccedil;&atilde;o Nossa Senhora da Penha (3%)</td>
    <td align="right"><?php $fnsp = $tot_fundo_paroquial * 0.03; echo number_format($fnsp,2,",",".");?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><?php $repasse_mitra = ($mitra + $presb + $semin + $fnsp); echo number_format($repasse_mitra,2,",",".");?></td>
  </tr>
</table>
</div>
<br />
<div id="apura_area_cformacao" style="border: 1px solid black">
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td>C&Aacute;LCULO PARA REPASSE &Agrave; &Aacute;REA DE VILA VELHA E PARA O CENTRO DE FORMA&Ccedil;&Atilde;O</td>
    <td align="right"></td>
  </tr>
  <tr>
    <td>Repasse para &Aacute;rea de Vila Velha (1%)</td>
    <td align="right"><?php $area_vv = $tot_fundo_paroquial * 0.01; echo number_format($area_vv,2,",",".");?></td>
  </tr>
  <tr>
    <td>Repasse para o Centro de Forma&ccedil;&atilde;o oriundo do Fundo Paroquial (1%)</td>
    <td align="right"><?php $cf_funpar = $tot_fundo_paroquial * 0.01; echo number_format($cf_funpar,2,",",".");?></td>
  </tr>
  <tr>
    <td>Repasse para o Centro de Forma&ccedil;&atilde;o oriundo do Fundo Paroquial (1%)</td>
    <td align="right"><?php echo number_format($tot_centro_formacao,2,",",".");?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><?php $repasse_area_cf = ($area_vv + $cf_funpar + $tot_centro_formacao); echo number_format($repasse_area_cf,2,",",".");?></td>
  </tr>
</table>
</div>
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
<br />
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td align="center">_______________________________________</td>
    <td align="center">&nbsp;</td>
    <td align="center">________________________________________</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="21">&nbsp;</td>
    <td align="center"><strong>Maria da Penha Quemelli<br />
    Controle Financeiro</strong></td>
    <td align="center">&nbsp;</td>
    <td align="center"><strong>Padre Arlindo Moura de Melo<br />
    P&aacute;roco</strong></td>
    <td>&nbsp;</td>
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

mysql_free_result($rs_fundo_paroquial);

mysql_free_result($rs_encargos_sociais);

mysql_free_result($rs_centro_formacao);

mysql_free_result($rs_oferta_especifica);

mysql_free_result($rs_outras);

mysql_free_result($rs_cheques);
?>
