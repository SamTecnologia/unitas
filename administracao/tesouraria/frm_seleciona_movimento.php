<?php require_once('../../Connections/unitas.php'); ?>
<?php
// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_unitas, "../../");
//Grand Levels: Level
$restrict->addLevel("A");
$restrict->Execute();
//End Restrict Access To Page

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

$tesouraria_rs_movimento = "-1";
if (isset($_GET['tesouraria'])) {
  $tesouraria_rs_movimento = $_GET['tesouraria'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_movimento = sprintf("SELECT CONCAT( unts_periodo.perio_mes, '/', unts_periodo.perio_ano ) AS perio_descricao, unts_movimento . * FROM unts_movimento INNER JOIN unts_periodo ON unts_periodo.perio_id = unts_movimento.perio_id WHERE unts_movimento.tesra_id = %s AND unts_movimento.movim_ativo = 'S' ORDER BY perio_id DESC", GetSQLValueString($tesouraria_rs_movimento, "int"));
$rs_movimento = mysql_query($query_rs_movimento, $unitas) or die(mysql_error());
$row_rs_movimento = mysql_fetch_assoc($rs_movimento);
$totalRows_rs_movimento = mysql_num_rows($rs_movimento);

$usuario_rs_usuario = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $usuario_rs_usuario = $_SESSION['kt_login_id'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_usuario = sprintf("SELECT * FROM unts_usuario WHERE unts_usuario.user_id = %s", GetSQLValueString($usuario_rs_usuario, "int"));
$rs_usuario = mysql_query($query_rs_usuario, $unitas) or die(mysql_error());
$row_rs_usuario = mysql_fetch_assoc($rs_usuario);
$totalRows_rs_usuario = mysql_num_rows($rs_usuario);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
</head>

<body>
<p align="right"><strong><?php echo $row_rs_usuario['user_id']; ?> - <?php echo $row_rs_usuario['user_nome']; ?></strong></p>
<?php if ($totalRows_rs_movimento > 0) { // Show if recordset not empty ?>
  <table width="50%" border="1">
    <tr>
      <td width="4%"><strong>N&ordm;</strong></td>
      <td width="91%"><strong>Movimento</strong></td>
      <td width="5%" align="center"><strong><a href="/unitas/administracao/tesouraria/frm_movimento.php?tesouraria=<?php echo $_GET['tesouraria']; ?>"><img src="/unitas/images/adcionar.png" width="16" height="16" /></a></strong>
      Novo</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php $cont = $cont+1;echo $cont;?></td>
        <td colspan="2"><a href="/unitas/administracao/tesouraria/frm_tesouraria.php?movimento=<?php echo $row_rs_movimento['movim_id']; ?>"><?php echo $row_rs_movimento['perio_descricao']; ?></a></td>
      </tr>
      <?php } while ($row_rs_movimento = mysql_fetch_assoc($rs_movimento)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rs_movimento == 0) { // Show if recordset empty ?>
  <p>N&atilde;o h&aacute; Movimento Financeiro cadastrado para essa tesouraria. Clique <a href="/unitas/administracao/tesouraria/frm_movimento.php?tesouraria=<?php echo $_GET['tesouraria']; ?>">aqui </a>para cadastrar Movimento.</p>
  <?php } // Show if recordset empty ?>
</body>
</html>
<?php
mysql_free_result($rs_movimento);

mysql_free_result($rs_usuario);
?>
