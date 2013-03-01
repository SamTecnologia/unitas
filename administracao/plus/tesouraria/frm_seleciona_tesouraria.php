<?php require_once('../../../Connections/unitas.php'); ?>
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

mysql_select_db($database_unitas, $unitas);
$query_rs_tesouraria = "SELECT * FROM unts_tesouraria WHERE unts_tesouraria.tesra_ativo = 'S' ORDER BY unts_tesouraria.tesra_descricao";
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
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td>Selecione um movimento abaixo</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="frm_seleciona_movimento.php?tesouraria=<?php echo $row_rs_tesouraria['tesra_id']; ?>"><?php echo $row_rs_tesouraria['tesra_descricao']; ?></a></td>
    </tr>
    <?php } while ($row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria)); ?>
</table>
<p>&nbsp;</p>
</body>

</html>
<?php
mysql_free_result($rs_tesouraria);
?>
