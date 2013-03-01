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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO unts_contas (conta_tipo, conta_descricao, conta_visibilidade, conta_ativa) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['conta_tipo'], "text"),
                       GetSQLValueString($_POST['conta_descricao'], "text"),
                       GetSQLValueString($_POST['conta_visibilidade'], "int"),
                       GetSQLValueString($_POST['conta_ativa'], "text"));

  mysql_select_db($database_unitas, $unitas);
  $Result1 = mysql_query($insertSQL, $unitas) or die(mysql_error());

  $insertGoTo = "lst_contas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_unitas, $unitas);
$query_rs_tesouraria = "SELECT * FROM unts_tesouraria ORDER BY unts_tesouraria.tesra_descricao";
$rs_tesouraria = mysql_query($query_rs_tesouraria, $unitas) or die(mysql_error());
$row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria);
$totalRows_rs_tesouraria = mysql_num_rows($rs_tesouraria);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tipo:</td>
      <td><select name="conta_tipo">
        <option value="R" <?php if (!(strcmp("R", ""))) {echo "SELECTED";} ?>>Receita</option>
        <option value="D" <?php if (!(strcmp("D", ""))) {echo "SELECTED";} ?>>Despesa</option>
        <option value="M" <?php if (!(strcmp("M", ""))) {echo "SELECTED";} ?>>Movimentaçao</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Descriçao:</td>
      <td><input type="text" name="conta_descricao" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Visibilidade:</td>
      <td><select name="conta_visibilidade">
        <option value="0">Pública</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_tesouraria['tesra_id']?>"><?php echo $row_rs_tesouraria['tesra_descricao']?></option>
        <?php
} while ($row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria));
  $rows = mysql_num_rows($rs_tesouraria);
  if($rows > 0) {
      mysql_data_seek($rs_tesouraria, 0);
	  $row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria);
  }
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ativa:</td>
      <td><select name="conta_ativa">
        <option value="S" <?php if (!(strcmp("S", ""))) {echo "SELECTED";} ?>>Sim</option>
        <option value="N" <?php if (!(strcmp("N", ""))) {echo "SELECTED";} ?>>Nao</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_tesouraria);
?>
