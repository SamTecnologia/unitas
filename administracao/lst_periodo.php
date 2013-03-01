<?php require_once('../Connections/unitas.php'); ?>
<?php require_once('../Connections/unitas.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_periodo = 10;
$pageNum_rs_periodo = 0;
if (isset($_GET['pageNum_rs_periodo'])) {
  $pageNum_rs_periodo = $_GET['pageNum_rs_periodo'];
}
$startRow_rs_periodo = $pageNum_rs_periodo * $maxRows_rs_periodo;

mysql_select_db($database_unitas, $unitas);
$query_rs_periodo = "SELECT * FROM unts_periodo";
$query_limit_rs_periodo = sprintf("%s LIMIT %d, %d", $query_rs_periodo, $startRow_rs_periodo, $maxRows_rs_periodo);
$rs_periodo = mysql_query($query_limit_rs_periodo, $unitas) or die(mysql_error());
$row_rs_periodo = mysql_fetch_assoc($rs_periodo);

if (isset($_GET['totalRows_rs_periodo'])) {
  $totalRows_rs_periodo = $_GET['totalRows_rs_periodo'];
} else {
  $all_rs_periodo = mysql_query($query_rs_periodo);
  $totalRows_rs_periodo = mysql_num_rows($all_rs_periodo);
}
$totalPages_rs_periodo = ceil($totalRows_rs_periodo/$maxRows_rs_periodo)-1;

$queryString_rs_periodo = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_periodo") == false && 
        stristr($param, "totalRows_rs_periodo") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_periodo = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_periodo = sprintf("&totalRows_rs_periodo=%d%s", $totalRows_rs_periodo, $queryString_rs_periodo);

$queryString_rs_periodo = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_periodo") == false && 
        stristr($param, "totalRows_rs_periodo") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_periodo = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_periodo = sprintf("&totalRows_rs_periodo=%d%s", $totalRows_rs_periodo, $queryString_rs_periodo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Administra&ccedil;&atilde;o</title>
</head>

<body>
<div id="formulario" style="border: 1px solid red; width: 604px;">
	<div id="filtro" style="border: 1px solid green">Filtar por: </div>
	<div id="dados" style="border: 1px solid blue"> 
	  <strong>Lista de Per&iacute;odos</strong><br />
	  Registro <?php echo ($startRow_rs_periodo + 1) ?> a <?php echo min($startRow_rs_periodo + $maxRows_rs_periodo, $totalRows_rs_periodo) ?> de <?php echo $totalRows_rs_periodo ?>
	  <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td><strong>N&ordm;</strong></td>
            <td><strong>C&oacute;digo</strong></td>
            <td><strong>M&ecirc;s</strong></td>
            <td><strong>Ano</strong></td>
            <td><strong>Ativo</strong></td>
            <td colspan="2"><strong>A&ccedil;&atilde;o</strong></td>
          </tr>
          <?php do { ?>
        <tr>
              <td><?php $cont = $cont + 1; echo $cont;?></td>
              <td><?php echo $row_rs_periodo['perio_id']; ?></td>
              <td><?php echo $row_rs_periodo['perio_mes']; ?></td>
              <td><?php echo $row_rs_periodo['perio_ano']; ?></td>
              <td><?php echo $row_rs_periodo['perio_ativo']; ?></td>
              <td width="16" height="16"><a href="frm_periodo_editar.php?periodo=<?php echo $row_rs_periodo['perio_id']; ?>"><img src="../images/buttons/Editar01.bmp" alt="editar registro" width="16" height="16" /></a></td>
              <td width="16" height="16"><img src="../images/buttons/Excluir01.bmp" alt="excluir registro" width="16" height="16" /></td>
            </tr>
            <?php } while ($row_rs_periodo = mysql_fetch_assoc($rs_periodo)); ?>
        </table>
	</div>
    <div id="navegacao" style="border: 1px solid yellow; width: 300px; float: left;" align="center">
      <table width="30%">
        <tr>
          <td><a href="<?php printf("%s?pageNum_rs_periodo=%d%s", $currentPage, 0, $queryString_rs_periodo); ?>"><img src="../images/buttons/NavegaPrimeiro.bmp" alt="primeira pagina" width="32" height="32" /></a></td></td>
          <td><a href="<?php printf("%s?pageNum_rs_periodo=%d%s", $currentPage, max(0, $pageNum_rs_periodo - 1), $queryString_rs_periodo); ?>"><img src="../images/buttons/NavegaAnterior.bmp" alt="pagina anterior" width="32" height="32" /></a></td>
          <td><a href="<?php printf("%s?pageNum_rs_periodo=%d%s", $currentPage, min($totalPages_rs_periodo, $pageNum_rs_periodo + 1), $queryString_rs_periodo); ?>"><img src="../images/buttons/NavegaPosterior.bmp" alt="proxima pagina" width="32" height="32" /></a></td>
          <td><a href="<?php printf("%s?pageNum_rs_periodo=%d%s", $currentPage, $totalPages_rs_periodo, $queryString_rs_periodo); ?>"><img src="../images/buttons/NavegaUltimo.bmp" alt="ultima pagina" width="32" height="32" /></a></td>
        </tr>
      </table>
  </div>
    <div id="botton" align="right" style="border: 1px solid pink; width:300px; float:left;"><a href="frm_periodo.php"><img src="../images/buttons/Adicionar02.bmp" alt="novo registro" width="32" height="32" /></a></div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_periodo);

mysql_free_result($rs_periodo);
?>
