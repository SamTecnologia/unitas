<?php require_once('../Connections/unitas.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_unitas, "../");
//Grand Levels: Any
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

$usuario_rs_tesouraria = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $usuario_rs_tesouraria = $_SESSION['kt_login_id'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_tesouraria = sprintf("SELECT unts_permissao . * , unts_usuario.*, unts_tesouraria . * FROM unts_permissao INNER JOIN unts_tesouraria ON unts_tesouraria.tesra_id = unts_permissao.tesra_id INNER JOIN unts_usuario ON unts_usuario.user_id = unts_permissao.user_id WHERE tesra_ativo = 'S' AND unts_permissao.user_id =%s", GetSQLValueString($usuario_rs_tesouraria, "int"));
$rs_tesouraria = mysql_query($query_rs_tesouraria, $unitas) or die(mysql_error());
$row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria);
$totalRows_rs_tesouraria = mysql_num_rows($rs_tesouraria);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Administra&ccedil;&atilde;o</title>
</head>

<body>
<p align="right"><strong><?php echo $row_rs_tesouraria['user_id']; ?>-<?php echo $row_rs_tesouraria['user_nome']; ?></strong></p>
<h1>Sele&ccedil;&atilde;o de Tesouraria</h1>
<p>Prezado Sr(a). <strong><?php echo $row_rs_tesouraria['user_nome']; ?></strong>, selecione uma tesouraria na rela&ccedil;&atilde;o abaixo:</p>
<?php do { ?>
  <table width="300" border="1">
      <tr>
        <td width="50"><?php $cont = $cont + 1; echo $cont;?></td>
        <td><a href="tesouraria/frm_seleciona_movimento.php?tesouraria=<?php echo $row_rs_tesouraria['tesra_id']; ?>"><?php echo $row_rs_tesouraria['tesra_descricao']; ?></a></td>
      </tr>
  </table>
<?php } while ($row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria)); ?>
</body>
</html>
<?php
mysql_free_result($rs_tesouraria);
?>
