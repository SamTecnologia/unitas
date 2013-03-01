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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE unts_contas SET conta_tipo=%s, conta_descricao=%s, conta_visibilidade=%s, conta_ativa=%s WHERE conta_id=%s",
                       GetSQLValueString($_POST['conta_tipo'], "text"),
                       GetSQLValueString($_POST['conta_descricao'], "text"),
                       GetSQLValueString($_POST['conta_visibilidade'], "int"),
                       GetSQLValueString($_POST['conta_ativa'], "text"),
                       GetSQLValueString($_POST['conta_id'], "int"));

  mysql_select_db($database_unitas, $unitas);
  $Result1 = mysql_query($updateSQL, $unitas) or die(mysql_error());

  $updateGoTo = "lst_contas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_unitas, $unitas);
$query_rs_tesouraria = "SELECT * FROM unts_tesouraria ORDER BY unts_tesouraria.tesra_descricao";
$rs_tesouraria = mysql_query($query_rs_tesouraria, $unitas) or die(mysql_error());
$row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria);
$totalRows_rs_tesouraria = mysql_num_rows($rs_tesouraria);

$conta_rs_conta = "-1";
if (isset($_GET['conta'])) {
  $conta_rs_conta = $_GET['conta'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_conta = sprintf("SELECT * FROM unts_contas WHERE unts_contas.conta_id = %s", GetSQLValueString($conta_rs_conta, "int"));
$rs_conta = mysql_query($query_rs_conta, $unitas) or die(mysql_error());
$row_rs_conta = mysql_fetch_assoc($rs_conta);
$totalRows_rs_conta = mysql_num_rows($rs_conta);
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
        <option value="R" <?php if (!(strcmp("R", htmlentities($row_rs_conta['conta_tipo'], ENT_COMPAT, 'iso-8859-2')))) {echo "SELECTED";} ?>>Receita</option>
        <option value="D" <?php if (!(strcmp("D", htmlentities($row_rs_conta['conta_tipo'], ENT_COMPAT, 'iso-8859-2')))) {echo "SELECTED";} ?>>Despesa</option>
        <option value="M" <?php if (!(strcmp("M", htmlentities($row_rs_conta['conta_tipo'], ENT_COMPAT, 'iso-8859-2')))) {echo "SELECTED";} ?>>Movimentaçao</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Descriçao:</td>
      <td><input type="text" name="conta_descricao" value="<?php echo htmlentities($row_rs_conta['conta_descricao'], ENT_COMPAT, 'iso-8859-2'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Visibilidade:</td>
      <td><select name="conta_visibilidade">
        <?php 
do {  
?>
        <option value="<?php echo $row_rs_tesouraria['tesra_id']?>" <?php if (!(strcmp($row_rs_tesouraria['tesra_id'], htmlentities($row_rs_conta['conta_visibilidade'], ENT_COMPAT, 'iso-8859-2')))) {echo "SELECTED";} ?>><?php echo $row_rs_tesouraria['tesra_descricao']?></option>
        <?php
} while ($row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ativa:</td>
      <td><select name="conta_ativa">
        <option value="S" <?php if (!(strcmp("S", htmlentities($row_rs_conta['conta_ativa'], ENT_COMPAT, 'iso-8859-2')))) {echo "SELECTED";} ?>>Sim</option>
        <option value="N" <?php if (!(strcmp("N", htmlentities($row_rs_conta['conta_ativa'], ENT_COMPAT, 'iso-8859-2')))) {echo "SELECTED";} ?>>Nao</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="conta_id" value="<?php echo $row_rs_conta['conta_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_tesouraria);

mysql_free_result($rs_conta);
?>
