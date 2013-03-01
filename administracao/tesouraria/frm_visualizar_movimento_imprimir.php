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

$tesour_rs_com_tesouraria = "-1";
if (isset($_GET['tesouraria'])) {
  $tesour_rs_com_tesouraria = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_com_tesouraria = sprintf("SELECT unts_tesouraria . * , unts_comunidade . * FROM unts_tesouraria INNER JOIN unts_comunidade ON unts_comunidade.comun_id = unts_tesouraria.comun_id WHERE tesra_id = %s", GetSQLValueString($tesour_rs_com_tesouraria, "int"));
$rs_com_tesouraria = mysql_query($query_rs_com_tesouraria, $unitas) or die(mysql_error());
$row_rs_com_tesouraria = mysql_fetch_assoc($rs_com_tesouraria);
$totalRows_rs_com_tesouraria = mysql_num_rows($rs_com_tesouraria);

$tsraria_rs_funcoes = "-1";
if (isset($_GET['tesouraria'])) {
  $tsraria_rs_funcoes = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_funcoes = sprintf("SELECT unts_usuario . * , unts_permissao . * , unts_funcao . * FROM unts_permissao INNER JOIN unts_usuario ON unts_usuario.user_id = unts_permissao.user_id INNER JOIN unts_funcao_usuario ON unts_funcao_usuario.user_id = unts_permissao.user_id INNER JOIN unts_funcao ON unts_funcao.func_id = unts_funcao_usuario.func_id WHERE ( unts_funcao.func_id =2 OR unts_funcao.func_id =3 ) AND tesra_id = %s", GetSQLValueString($tsraria_rs_funcoes, "int"));
$rs_funcoes = mysql_query($query_rs_funcoes, $unitas) or die(mysql_error());
$row_rs_funcoes = mysql_fetch_assoc($rs_funcoes);
$totalRows_rs_funcoes = mysql_num_rows($rs_funcoes);
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
    <td width="10%" height="93" align="left" valign="middle"><p><img src="/unitas/images/tesourarias_logo/<?php echo $row_rs_com_tesouraria['comun_id']; ?>/<?php echo $row_rs_com_tesouraria['comun_icone']; ?>" width="90" height="91" alt="Logomarca  <?php echo $row_rs_com_tesouraria['comun_razao_social']; ?>" /></p></td>
    <td width="79%" align="center"><strong><?php echo $row_rs_com_tesouraria['comun_sigla']; ?> - <?php echo $row_rs_com_tesouraria['comun_razao_social']; ?></strong><br />
      Par&oacute;quia Santa M&atilde;e de Deus - Ibes<br />
    Arquidiocese de Vit&oacute;ria-ES</td>
    <td width="11%" align="right"><img src="/unitas/images/psmd_logo.png" width="45" height="45" alt="Logomarca Par&oacute;quia santa M&atilde;e de Deus" /><br /><img src="/unitas/images/aves_logo.png" width="45" height="45" alt="Logomarca da Arquidiocese de Vit&oacute;ria-ES" /></td>
  </tr>
</table>
<h1>Movimento Financeiro</h1>
<table width="100%" border="1" cellspacing="0">
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
<table width="100%">
  <tr>
    <td align="center">
    <?php do { ?>
        <div id="assinatura" style="float: left; width: 200px; margin: 10px; padding: 0 80px 0 80px;" align="center">
          <?php echo "______________________________"; ?><br />
          <?php echo $row_rs_funcoes['user_nome']; ?><br />
          <?php echo $row_rs_funcoes['func_descricao']; ?>
        </div>
  <?php } while ($row_rs_funcoes = mysql_fetch_assoc($rs_funcoes)); ?>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_itens);

mysql_free_result($rs_saldoinicial);

mysql_free_result($rs_tot_saidas);

mysql_free_result($rs_tot_entradas);

mysql_free_result($rs_com_tesouraria);

mysql_free_result($rs_funcoes);
?>
