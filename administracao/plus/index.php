<?php require_once('../../Connections/unitas.php'); ?>
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

$mes = Date(m);
$mes_ant = Date(m)-1;
$ano = Date(Y);

$mes_diz_mes_atual = "-1";
if (isset($mes)) {
  $mes_diz_mes_atual = $mes;
}
$ano_diz_mes_atual = "1900";
if (isset($ano)) {
  $ano_diz_mes_atual = $ano;
}
mysql_select_db($database_unitas, $unitas);
$query_diz_mes_atual = sprintf("SELECT unts_movimento.tesra_id, unts_tesouraria.tesra_descricao, unts_item_mov.conta_id_e, MONTH( unts_item_mov.item_data ) , YEAR( unts_item_mov.item_data ) , SUM( unts_item_mov.item_valor ) FROM unts_movimento INNER JOIN unts_tesouraria ON unts_tesouraria.tesra_id = unts_movimento.tesra_id INNER JOIN unts_item_mov ON unts_item_mov.movim_id = unts_movimento.movim_id WHERE unts_item_mov.conta_id_e =1 AND MONTH( unts_item_mov.item_data ) = %s AND YEAR( unts_item_mov.item_data ) = %s GROUP BY unts_movimento.tesra_id ORDER BY unts_tesouraria.tesra_descricao", GetSQLValueString($mes_diz_mes_atual, "date"),GetSQLValueString($ano_diz_mes_atual, "date"));
$diz_mes_atual = mysql_query($query_diz_mes_atual, $unitas) or die(mysql_error());
$row_diz_mes_atual = mysql_fetch_assoc($diz_mes_atual);
$totalRows_diz_mes_atual = mysql_num_rows($diz_mes_atual);

$mes_ofe_mes_atual = "-1";
if (isset($mes)) {
  $mes_ofe_mes_atual = $mes;
}
$ano_ofe_mes_atual = "1900";
if (isset($ano)) {
  $ano_ofe_mes_atual = $ano;
}
mysql_select_db($database_unitas, $unitas);
$query_ofe_mes_atual = sprintf("SELECT unts_movimento.tesra_id, unts_tesouraria.tesra_descricao, unts_item_mov.conta_id_e, MONTH( unts_item_mov.item_data ) , YEAR( unts_item_mov.item_data ) , SUM( unts_item_mov.item_valor ) FROM unts_movimento INNER JOIN unts_tesouraria ON unts_tesouraria.tesra_id = unts_movimento.tesra_id INNER JOIN unts_item_mov ON unts_item_mov.movim_id = unts_movimento.movim_id WHERE unts_item_mov.conta_id_e =2 AND MONTH( unts_item_mov.item_data ) = %s AND YEAR( unts_item_mov.item_data ) = %s GROUP BY unts_movimento.tesra_id ORDER BY unts_tesouraria.tesra_descricao", GetSQLValueString($mes_ofe_mes_atual, "date"),GetSQLValueString($ano_ofe_mes_atual, "date"));
$ofe_mes_atual = mysql_query($query_ofe_mes_atual, $unitas) or die(mysql_error());
$row_ofe_mes_atual = mysql_fetch_assoc($ofe_mes_atual);
$totalRows_ofe_mes_atual = mysql_num_rows($ofe_mes_atual);

$mes_a_diz_mes_anterior = "-1";
if (isset($mes_ant)) {
  $mes_a_diz_mes_anterior = $mes_ant;
}
$ano_diz_mes_anterior = "1900";
if (isset($ano)) {
  $ano_diz_mes_anterior = $ano;
}
mysql_select_db($database_unitas, $unitas);
$query_diz_mes_anterior = sprintf("SELECT unts_movimento.tesra_id, unts_tesouraria.tesra_descricao, unts_item_mov.conta_id_e, MONTH( unts_item_mov.item_data ) , YEAR( unts_item_mov.item_data ) , SUM( unts_item_mov.item_valor ) FROM unts_movimento INNER JOIN unts_tesouraria ON unts_tesouraria.tesra_id = unts_movimento.tesra_id INNER JOIN unts_item_mov ON unts_item_mov.movim_id = unts_movimento.movim_id WHERE unts_item_mov.conta_id_e =1 AND MONTH( unts_item_mov.item_data ) = %s AND YEAR( unts_item_mov.item_data ) = %s GROUP BY unts_movimento.tesra_id ORDER BY unts_tesouraria.tesra_descricao", GetSQLValueString($mes_a_diz_mes_anterior, "date"),GetSQLValueString($ano_diz_mes_anterior, "date"));
$diz_mes_anterior = mysql_query($query_diz_mes_anterior, $unitas) or die(mysql_error());
$row_diz_mes_anterior = mysql_fetch_assoc($diz_mes_anterior);
$totalRows_diz_mes_anterior = mysql_num_rows($diz_mes_anterior);

$mes_a_ofe_mes_anterior = "-1";
if (isset($mes_ant)) {
  $mes_a_ofe_mes_anterior = $mes_ant;
}
$ano_ofe_mes_anterior = "1900";
if (isset($ano)) {
  $ano_ofe_mes_anterior = $ano;
}
mysql_select_db($database_unitas, $unitas);
$query_ofe_mes_anterior = sprintf("SELECT unts_movimento.tesra_id, unts_tesouraria.tesra_descricao, unts_item_mov.conta_id_e, MONTH( unts_item_mov.item_data ) , YEAR( unts_item_mov.item_data ) , SUM( unts_item_mov.item_valor ) FROM unts_movimento INNER JOIN unts_tesouraria ON unts_tesouraria.tesra_id = unts_movimento.tesra_id INNER JOIN unts_item_mov ON unts_item_mov.movim_id = unts_movimento.movim_id WHERE unts_item_mov.conta_id_e =2 AND MONTH( unts_item_mov.item_data ) = %s AND YEAR( unts_item_mov.item_data ) = %s GROUP BY unts_movimento.tesra_id ORDER BY unts_tesouraria.tesra_descricao", GetSQLValueString($mes_a_ofe_mes_anterior, "date"),GetSQLValueString($ano_ofe_mes_anterior, "date"));
$ofe_mes_anterior = mysql_query($query_ofe_mes_anterior, $unitas) or die(mysql_error());
$row_ofe_mes_anterior = mysql_fetch_assoc($ofe_mes_anterior);
$totalRows_ofe_mes_anterior = mysql_num_rows($ofe_mes_anterior);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Administra&ccedil;&atilde;o</title>
</head>

<body>
<div id="top_menu" style="border: 1px solid black;" align="center">
<a href="tesouraria/frm_seleciona_tesouraria.php">Movimentos</a> | Consultas | Relatórios | <a href="../frm_permissao_usuario.php">Sair</a></div><br />
<div id="geral" style="width: 900px;">
	<div id="mes_atual" style="width: 900px;">
    Demonstrativo de Arrecada&ccedil;&atilde;o: <?php echo $mes."/".$ano;?><br />
        <div id="dizimos" style="width:300px;float:left;">
          <table width="100%" border="1" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2" align="center">Arrecada&ccedil;&atilde;o de D&iacute;zimos</td>
            </tr>
            <tr>
              <td>Comunidade</td>
              <td>Arrecada&ccedil;&atilde;o</td>
            </tr>
            <?php do { ?>
            <tr>
              <td><?php echo $row_diz_mes_atual['tesra_descricao']; ?></td>
              <td align="right"><?php echo number_format($row_diz_mes_atual['SUM( unts_item_mov.item_valor )'],2,",","."); ?><?php $tot_diz_atual = $tot_diz_atual + $row_diz_mes_atual['SUM( unts_item_mov.item_valor )'];?></td>
            </tr>
            <?php } while ($row_diz_mes_atual = mysql_fetch_assoc($diz_mes_atual)); ?>
            <tr>
              <td align="right">Total no m&ecirc;s</td>
              <td align="right"><?php echo number_format($tot_diz_atual,2,",",".");?></td>
            </tr>
          </table>
        </div>
      	<div id="ofertas" style="width:300px;float:left;">
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="center">Arrecada&ccedil;&atilde;o de Ofertas</td>
    </tr>
  <tr>
    <td>Comunidade</td>
    <td>Arrecada&ccedil;&atilde;o</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_ofe_mes_atual['tesra_descricao']; ?></td>
      <td align="right"><?php echo number_format($row_ofe_mes_atual['SUM( unts_item_mov.item_valor )'],2,",","."); ?><?php $tot_ofe_atual = $tot_ofe_atual +  $row_ofe_mes_atual['SUM( unts_item_mov.item_valor )'];?></td>
    </tr>
   <?php } while ($row_ofe_mes_atual = mysql_fetch_assoc($ofe_mes_atual)); ?>
    <tr>
      <td align="right">Total no m&ecirc;s</td>
      <td align="right"><?php echo number_format($tot_ofe_atual,2,",",".");?></td>
    </tr>
        </table>
		</div>
        <div id="outras" style="width:300px; float:left;">
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2">Outras Entradas</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
        </table>

        </div>
    </div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div id="mes_anterior">
    Demonstrativo de Arrecada&ccedil;&atilde;o: <?php echo $mes_ant."/".$ano;?><br />
        <div id="dizimos_a" style="width:300px;float:left;">
          <table width="100%" border="1" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2" align="center">Arrecada&ccedil;&atilde;o de D&iacute;zimos</td>
            </tr>
            <tr>
              <td>Comunidade</td>
              <td>Arrecada&ccedil;&atilde;o</td>
            </tr>
            <?php do { ?>
            <tr>
              <td><?php echo $row_diz_mes_anterior['tesra_descricao']; ?></td>
              <td align="right"><?php echo number_format($row_diz_mes_anterior['SUM( unts_item_mov.item_valor )'],2,",","."); ?><?php $tot_diz_mes_anterior = $tot_diz_mes_anterior + $row_diz_mes_anterior['SUM( unts_item_mov.item_valor )'];?></td>
            </tr>
            <?php } while ($row_diz_mes_anterior = mysql_fetch_assoc($diz_mes_anterior)); ?>
            <tr>
              <td align="right">Total no m&ecirc;s</td>
              <td align="right"><?php echo number_format($tot_diz_mes_anterior,2,",",".");?></td>
            </tr>
          </table>
        </div>
      	<div id="ofertas" style="width:300px;float:left;">
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="center">Arrecada&ccedil;&atilde;o de Ofertas</td>
    </tr>
  <tr>
    <td>Comunidade</td>
    <td>Arrecada&ccedil;&atilde;o</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_ofe_mes_anterior['tesra_descricao']; ?></td>
      <td align="right"><?php echo number_format($row_ofe_mes_anterior['SUM( unts_item_mov.item_valor )'],2,",","."); ?><?php $tot_ofe_mes_anterior = $tot_ofe_mes_anterior +  $row_ofe_mes_anterior['SUM( unts_item_mov.item_valor )'];?></td>
    </tr>
   <?php } while ($row_ofe_mes_anterior = mysql_fetch_assoc($ofe_mes_anterior)); ?>
    <tr>
      <td>&nbsp;</td>
      <td align="right"><?php echo number_format($tot_ofe_mes_anterior,2,",",".");?></td>
    </tr>
        </table>
		</div>
        <div id="outras" style="width:300px;">
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2">Outras Entradas</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
        </table>

</div>
    </div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($diz_mes_atual);

mysql_free_result($ofe_mes_atual);

mysql_free_result($diz_mes_anterior);

mysql_free_result($ofe_mes_anterior);
?>
