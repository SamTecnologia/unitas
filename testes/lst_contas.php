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

$maxRows_rs_contas = 10;
$pageNum_rs_contas = 0;
if (isset($_GET['pageNum_rs_contas'])) {
  $pageNum_rs_contas = $_GET['pageNum_rs_contas'];
}
$startRow_rs_contas = $pageNum_rs_contas * $maxRows_rs_contas;

mysql_select_db($database_unitas, $unitas);
$query_rs_contas = "SELECT * FROM unts_contas";
$query_limit_rs_contas = sprintf("%s LIMIT %d, %d", $query_rs_contas, $startRow_rs_contas, $maxRows_rs_contas);
$rs_contas = mysql_query($query_limit_rs_contas, $unitas) or die(mysql_error());
$row_rs_contas = mysql_fetch_assoc($rs_contas);

if (isset($_GET['totalRows_rs_contas'])) {
  $totalRows_rs_contas = $_GET['totalRows_rs_contas'];
} else {
  $all_rs_contas = mysql_query($query_rs_contas);
  $totalRows_rs_contas = mysql_num_rows($all_rs_contas);
}
$totalPages_rs_contas = ceil($totalRows_rs_contas/$maxRows_rs_contas)-1;

$queryString_rs_contas = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_contas") == false && 
        stristr($param, "totalRows_rs_contas") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_contas = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_contas = sprintf("&totalRows_rs_contas=%d%s", $totalRows_rs_contas, $queryString_rs_contas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
</head>

<body>
<table border="1">
  <tr>
    <td>conta_id</td>
    <td>conta_tipo</td>
    <td>conta_descricao</td>
    <td>conta_visibilidade</td>
    <td>conta_ativa</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rs_contas['conta_id']; ?></td>
      <td><?php echo $row_rs_contas['conta_tipo']; ?></td>
      <td><?php echo $row_rs_contas['conta_descricao']; ?></td>
      <td><?php echo $row_rs_contas['conta_visibilidade']; ?></td>
      <td><?php echo $row_rs_contas['conta_ativa']; ?></td>
      <td><a href="frm_edit_contas.php?conta=<?php echo $row_rs_contas['conta_id']; ?>">editar</a></td>
      <td>excluir</td>
    </tr>
    <?php } while ($row_rs_contas = mysql_fetch_assoc($rs_contas)); ?>
</table>
<table width="30%" border="1">
  <tr>
    <td><a href="<?php printf("%s?pageNum_rs_contas=%d%s", $currentPage, 0, $queryString_rs_contas); ?>"><img src="../images/buttons/btn_cliente.bmp" alt="go to" /></a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><a href="<?php printf("%s?pageNum_rs_contas=%d%s", $currentPage, $totalPages_rs_contas, $queryString_rs_contas); ?>"><img src="../images/buttons/btn_logoff.bmp" alt="fim" /></a></td>
    <td><a href="frm_insert_contas.php"><img src="../images/buttons/btn_ficha.bmp" alt="novo" /></a></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_contas);
?>
