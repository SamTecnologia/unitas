<?php require_once('../../Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../../includes/common/KT_common.php');
?>
<?php require_once("../../includes/common/KT_common.php");?>

<?php
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

$tesour_rs_com_tesouraria = "-1";
if (isset($_GET['tesouraria'])) {
  $tesour_rs_com_tesouraria = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_com_tesouraria = sprintf("SELECT unts_tesouraria . * , unts_comunidade . * FROM unts_tesouraria INNER JOIN unts_comunidade ON unts_comunidade.comun_id = unts_tesouraria.comun_id WHERE tesra_id = %s", GetSQLValueString($tesour_rs_com_tesouraria, "int"));
$rs_com_tesouraria = mysql_query($query_rs_com_tesouraria, $unitas) or die(mysql_error());
$row_rs_com_tesouraria = mysql_fetch_assoc($rs_com_tesouraria);
$totalRows_rs_com_tesouraria = mysql_num_rows($rs_com_tesouraria);

$movim_rs_caixas = "-1";
if (isset($_GET['movimento'])) {
  $movim_rs_caixas = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_caixas = sprintf("SELECT item_data FROM unts_item_mov WHERE movim_id =%s AND (conta_id_e = 3 OR conta_id_s = 3) GROUP BY item_data", GetSQLValueString($movim_rs_caixas, "int"));
$rs_caixas = mysql_query($query_rs_caixas, $unitas) or die(mysql_error());
$row_rs_caixas = mysql_fetch_assoc($rs_caixas);
$totalRows_rs_caixas = mysql_num_rows($rs_caixas);

$movimto_rs_scaixa_ini = "-1";
if (isset($_GET['movimento'])) {
  $movimto_rs_scaixa_ini = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_scaixa_ini = sprintf("SELECT * FROM unts_saldo_ini WHERE unts_saldo_ini.conta_id = 3 AND unts_saldo_ini.movim_id = %s", GetSQLValueString($movimto_rs_scaixa_ini, "int"));
$rs_scaixa_ini = mysql_query($query_rs_scaixa_ini, $unitas) or die(mysql_error());
$row_rs_scaixa_ini = mysql_fetch_assoc($rs_scaixa_ini);
$totalRows_rs_scaixa_ini = mysql_num_rows($rs_scaixa_ini);



$tsraria_rs_funcoes = "-1";
if (isset($_GET['tesouraria'])) {
  $tsraria_rs_funcoes = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_funcoes = sprintf("SELECT unts_usuario.*,    unts_permissao.*,    unts_funcao.* FROM unts_permissao INNER JOIN unts_usuario ON unts_usuario.user_id = unts_permissao.user_id INNER JOIN unts_funcao_usuario ON unts_funcao_usuario.user_id = unts_permissao.user_id INNER JOIN unts_funcao ON unts_funcao.func_id = unts_funcao_usuario.func_id WHERE tesra_id = %s ORDER BY unts_funcao.func_descricao", GetSQLValueString($tsraria_rs_funcoes, "int"));
$rs_funcoes = mysql_query($query_rs_funcoes, $unitas) or die(mysql_error());
$row_rs_funcoes = mysql_fetch_assoc($rs_funcoes);
$totalRows_rs_funcoes = mysql_num_rows($rs_funcoes);

?>

<?php do {
   $aux = $row_rs_funcoes['user_nome'];
   
   if ($row_rs_funcoes['func_id'] == 2){$prim_tes = $aux;} elseif($row_rs_funcoes['func_id'] == 3){$seg_tes = $aux;}
   
  } while ($row_rs_funcoes = mysql_fetch_assoc($rs_funcoes)); ?>
  
<?php $saldo = $row_rs_scaixa_ini['saldo_valor'];?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
<style type="text/css">
<!--
.negrito {
	font-weight: bold;
}
-->
</style>
</head>

<body>
<?php if ($totalRows_rs_caixas > 0) { // Show if recordset not empty ?>
  <?php do { $cont = $cont + 1;?>
  
  <div class="page" style="page-break-after: always">
    <table width="100%">
      <tr>
        <td colspan="2" rowspan="2" align="center" valign="middle"><img src="/unitas/images/aves_logo.png" width="81" height="75" alt="logomarca da Arquidiocese de Vit&oacute;ria-ES" /></td>
        <td colspan="4" align="center"><strong>MITRA ARQUIDIOCESANA DE VIT&Oacute;RIA - ES</strong></td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align="center"><strong>BOLETIM DI&Aacute;RIO DE CAIXA</strong></td>
        <td align="center" valign="middle"><img src="/unitas/images/psmd_logo.png" width="63" height="86" alt="logomarca Par&oacute;quia Santa M&atilde;e de Deus" /></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td width="15%">&nbsp;</td>
        <td width="15%">&nbsp;</td>
        <td width="15%">&nbsp;</td>
        <td width="15%">&nbsp;</td>
      </tr>
      <tr align="center" valign="middle">
        <td colspan="2" rowspan="3"><img src="/unitas/images/scj_logo.png" width="87" height="73" alt="logomarca Sagrado Cora&ccedil;&atilde;o de Jesus" /><br /></td>
        <td colspan="3" align="left" valign="middle"><strong> Par&oacute;quia Santa M&atilde;e de Deus</strong></td>
        <td colspan="2" rowspan="3" align="right"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td colspan="3" align="left"><strong><?php echo $row_rs_com_tesouraria['comun_razao_social']; ?></strong></td>
      </tr>
      <tr>
        <td colspan="3" align="left"><strong>Caixa n&ordm;: <?php echo $cont;?> - <?php echo KT_FormatDate($row_rs_caixas['item_data']); ?></strong></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="4%" align="center">N&ordm;.</td>
        <td colspan="4" align="center">HIST&Oacute;RICO</td>
        <td align="center">ENTRADAS</td>
        <td align="center">SA&Iacute;DAS</td>
      </tr>
      <?php
	  $movto = $_GET['movimento'];
	  $data = $row_rs_caixas['item_data'];
	  	mysql_select_db($database_unitas, $unitas);
		$query_rs_itens_caixa = sprintf(
										"SELECT
										   *
										FROM
										   unts_item_mov
										WHERE
										   movim_id = $movto AND
										   item_data = '$data' AND
										   (conta_id_e = 3 OR conta_id_s = 3)"
   										);
		$rs_itens_caixa = mysql_query($query_rs_itens_caixa, $unitas) or die(mysql_error());
		$row_rs_itens_caixa = mysql_fetch_assoc($rs_itens_caixa);
		$totalRows_rs_itens_caixa = mysql_num_rows($rs_itens_caixa);
	  ?>   
      <?php do { $item = $item + 1;?>
      <tr>
        <td align="center"><?php echo $item;?></td>
        <td colspan="4" align="left"><?php echo $row_rs_itens_caixa['item_historico']; ?></td>
        <td align="right"><?php if ($row_rs_itens_caixa['conta_id_s']==3){echo number_format($row_rs_itens_caixa['item_valor'],2,",","."); $aux_e =  $aux_e + $row_rs_itens_caixa['item_valor'];} else {$aux_e = 0;}?></td>
        <td align="right"><?php if ($row_rs_itens_caixa['conta_id_e']==3){echo number_format($row_rs_itens_caixa['item_valor'],2,",",".");$aux_s =  $aux_s + $row_rs_itens_caixa['item_valor'];} else {$aux_s = 0;}?></td>
      </tr>
      <?php } while ($row_rs_itens_caixa = mysql_fetch_assoc($rs_itens_caixa)); $item = 0; $entradas = $entradas + $aux_e; $saidas = $saidas + $aux_s; $aux_e = 0; $aux_s = 0;?>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="right" class="negrito">TOTAIS DO DIA</td>
        <td align="right" class="negrito"><?php echo number_format($entradas,2,",",".");?></td>
        <td align="right" class="negrito"><?php echo number_format($saidas,2,",",".");?></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td colspan="2" align="center">APURA&Ccedil;&Atilde;O</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="right">Saldo Anterior</td>
        <td align="right"><?php echo number_format($saldo,2,",",".");?></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="right">Entradas (+)</td>
        <td align="right"><?php echo number_format($entradas,2,",",".");?></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="right">Sa&iacute;das (-)</td>
        <td align="right"><?php echo number_format($saidas,2,",",".");?></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="right">SALDO ATUAL</td>
        <td align="right"><?php $saldo = (($saldo + $entradas) - $saidas); echo number_format($saldo,2,",","."); $entradas = 0; $saidas = 0;?></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="7" align="center"><table width="100%">
          <tr>
            <td>&nbsp;</td>
            <td align="center">________________________________________</td>
            <td>&nbsp;</td>
            <td align="center">________________________________________</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center"><?php echo $prim_tes;?><br />Primeiro (a) Tesoureiro (a)</td>
            <td>&nbsp;</td>
            <td align="center"><?php echo $seg_tes;?><br />Segundo (a) Tesoureiro (a)</td>
            <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
  </div>
  <?php } while ($row_rs_caixas = mysql_fetch_assoc($rs_caixas)); ?>
  <?php } // Show if recordset not empty ?>
  <strong>
<?php if ($totalRows_rs_caixas == 0) { // Show if recordset empty ?>
    N&atilde;o h&aacute;, at&eacute; o momento, movimenta&ccedil;&atilde;o financeira que envolva a Conta Caixa para este Movimento
    <?php } // Show if recordset empty ?>
  </strong>
<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($rs_com_tesouraria);

mysql_free_result($rs_caixas);

mysql_free_result($rs_scaixa_ini);
?>