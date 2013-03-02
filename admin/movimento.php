<?php require_once('../Connections/conecta.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
	include_once('../funcoes/variaveis.php');
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

if (isset($_GET['tesouraria'])) {
  $tesouraria_rs_usuario = $_GET['tesouraria'];
}
mysql_select_db($database_conecta, $conecta);
$query_rs_usuario = sprintf("SELECT CONCAT( unts_periodo.perio_mes, '/', unts_periodo.perio_ano ) AS perio_descricao, unts_movimento . * FROM unts_movimento INNER JOIN unts_periodo ON unts_periodo.perio_id = unts_movimento.perio_id WHERE unts_movimento.tesra_id = %s AND unts_movimento.movim_ativo = 'S' ORDER BY perio_id DESC", GetSQLValueString($tesouraria_rs_usuario, "int"));
$rs_usuario = mysql_query($query_rs_usuario, $conecta) or die(mysql_error());
$row_rs_usuario = mysql_fetch_assoc($rs_usuario);
$totalRows_rs_usuario = mysql_num_rows($rs_usuario);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulosite.'- Início'; ?></title>
<link href="../css/template.css" rel="stylesheet" type="text/css" />

</head>

<body>
	<div id="geral">
    	<div id="topo">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><img src="../img/logomarca.png" width="250" height="89" /></td>
    <td width="50%" nowrap="nowrap"><h1>Unidade Em Administração Eclesial</h1></td>
  </tr>
</table>

      </div>
        <div id="header">
        	<h3>Seleção de Movimento</h3>
            <?php saudacao(); echo $nomeusuario;?>, selecione um movimento na relação abaixo:
        </div>
        <div id="content">
        	<table border="0" cellspacing="4" cellpadding="0">
  <tr>
    <th>Código</th>
    <th>Tesouraria</th>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php $cont=''; $cont = $cont + 1; echo $cont; ?></td>
      <td nowrap="nowrap"><a href="tesouraria/tesouraria.php?movimento=<?php echo $row_rs_usuario['movim_id']; ?>"><?php echo $row_rs_usuario['perio_descricao']; ?></a></td>
    </tr>
    <?php } while ($row_rs_usuario = mysql_fetch_assoc($rs_usuario)); ?>
          </table>

        </div>
    </div>
</body>
</html>
<?php
mysql_free_result($rs_usuario);
?>
