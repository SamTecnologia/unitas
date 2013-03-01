<?php require_once('../../Connections/unitas.php'); ?>
<?php require_once("../../includes/common/KT_common.php");?>
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

$tes_rs_contas_m = "-1";
if (isset($_GET['tesouraria'])) {
  $tes_rs_contas_m = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_contas_m = sprintf("SELECT    * FROM    unts_contas WHERE    ((conta_tipo ='M' AND conta_visibilidade = 0) OR    (conta_tipo = 'M' AND conta_visibilidade = %s)) AND    conta_ativa = 'S'", GetSQLValueString($tes_rs_contas_m, "int"));
$rs_contas_m = mysql_query($query_rs_contas_m, $unitas) or die(mysql_error());
$row_rs_contas_m = mysql_fetch_assoc($rs_contas_m);
$totalRows_rs_contas_m = mysql_num_rows($rs_contas_m);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
</head>

<body>
<h1>Cadastro de Saldos</h1>	
<table width="100%" border="1">
  <tr>
    <td width="727" align="center"><strong>CONTA DE MOVIMENTA&Ccedil;&Atilde;O</strong></td>
    <td width="216" align="center"><strong>SALDO</strong></td>
    <td colspan="2" align="center"><strong>A&Ccedil;&Atilde;O</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rs_contas_m['conta_id']; ?>-<?php echo $row_rs_contas_m['conta_descricao']; ?></td>
      <td align="right">
      	<?php
			$query_saldo_ini = 'SELECT * FROM unts_saldo_ini WHERE movim_id = '.$_GET['movimento'].' AND conta_id = '.$row_rs_contas_m['conta_id'];
			$rs_saldo_ini = mysql_query($query_saldo_ini);
			$row_rs_saldo_ini = mysql_fetch_assoc($rs_saldo_ini);
			$totalRows_rs_saldo_ini = mysql_num_rows($rs_saldo_ini);
			
			if ($row_rs_saldo_ini['saldo_valor'] == NULL){ echo 0;}else {echo number_format($row_rs_saldo_ini['saldo_valor'],2,",",".");}
		
		?>
      </td>
      <td width="21" height="16" align="center" valign="middle">
      <?php if ($row_rs_saldo_ini['saldo_valor'] == NULL) {?>
      <a href="/unitas/administracao/tesouraria/frm_inserir_saldo_inicial.php?conta=<?php echo $row_rs_contas_m['conta_id']; ?>&movimento=<?php echo $_GET['movimento']; ?>&tesouraria=<?php echo $_GET['tesouraria']; ?>"><?php };?><img src="/unitas/images/adcionar.png" width="16" height="16" /></a></td>
      <td width="19" height="16" align="center" valign="middle">
      <?php if ($row_rs_saldo_ini['saldo_valor'] != NULL) {?>
      <a href="/unitas/administracao/tesouraria/frm_alterar_saldo_inicial.php?tesouraria=<?php echo $_GET['tesouraria']; ?>&amp;movimento=<?php echo $_GET['movimento']; ?>&amp;saldo_id=<?php echo $row_rs_saldo_ini['saldo_id'];?>"><?php };?><img src="/unitas/images/editar.png" width="16" height="16" /></a></td>
    </tr>
    <?php } while ($row_rs_contas_m = mysql_fetch_assoc($rs_contas_m)); ?>
</table>
<form id="form1" name="form1" method="post" action="/unitas/administracao/tesouraria/frm_tesouraria.php?movimento=<?php echo $_GET['movimento']; ?>">
  <label>
    <input type="submit" name="voltar" id="voltar" value="Finalizar" />
  </label>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_contas_m);
?>
