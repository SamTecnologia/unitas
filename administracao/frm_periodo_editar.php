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
  $updateSQL = sprintf("UPDATE unts_periodo SET perio_mes=%s, perio_ano=%s, perio_ativo=%s WHERE perio_id=%s",
                       GetSQLValueString($_POST['perio_mes'], "text"),
                       GetSQLValueString($_POST['perio_ano'], "int"),
                       GetSQLValueString($_POST['perio_ativo'], "text"),
                       GetSQLValueString($_POST['perio_id'], "int"));

  mysql_select_db($database_unitas, $unitas);
  $Result1 = mysql_query($updateSQL, $unitas) or die(mysql_error());

  $updateGoTo = "lst_periodo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$periodo_rs_periodo = "-1";
if (isset($_GET['periodo'])) {
  $periodo_rs_periodo = $_GET['periodo'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_periodo = sprintf("SELECT * FROM unts_periodo WHERE unts_periodo.perio_id = %s", GetSQLValueString($periodo_rs_periodo, "int"));
$rs_periodo = mysql_query($query_rs_periodo, $unitas) or die(mysql_error());
$row_rs_periodo = mysql_fetch_assoc($rs_periodo);
$totalRows_rs_periodo = mysql_num_rows($rs_periodo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Administra&ccedil;&atilde;o</title>
</head>

<body>
<div id="formulario" style="width: 300px; height:155px;float:left;">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mes:</td>
      <td><select name="perio_mes">
    <option value=""  <?php if (!(strcmp("", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Selecione</option>
<option value="Jan" <?php if (!(strcmp("Jan", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Jan</option>
<option value="Fev" <?php if (!(strcmp("Fev", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Fev</option>
<option value="Mar" <?php if (!(strcmp("Mar", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Mar</option>
<option value="Abr" <?php if (!(strcmp("Abr", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Abr</option>
<option value="Mai" <?php if (!(strcmp("Mai", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Mai</option>
<option value="Jun" <?php if (!(strcmp("Jun", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Jun</option>
<option value="Jul" <?php if (!(strcmp("Jul", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Jul</option>
<option value="Ago" <?php if (!(strcmp("Ago", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Ago</option>
<option value="Set" <?php if (!(strcmp("Set", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Set</option>
<option value="Out" <?php if (!(strcmp("Out", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Out</option>
<option value="Nov" <?php if (!(strcmp("Nov", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Nov</option>
<option value="Dez" <?php if (!(strcmp("Dez", $row_rs_periodo['perio_mes']))) {echo "selected=\"selected\"";} ?>>Dez</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ano:</td>
      <td><input name="perio_ano" type="text" value="<?php echo htmlentities($row_rs_periodo['perio_ano'], ENT_COMPAT, 'iso-8859-2'); ?>" size="4" maxlength="4" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ativo:</td>
      <td><select name="perio_ativo">
        <option value="S" <?php if (!(strcmp("S", htmlentities($row_rs_periodo['perio_ativo'], ENT_COMPAT, 'iso-8859-2')))) {echo "SELECTED";} ?>>Sim</option>
        <option value="N" <?php if (!(strcmp("N", htmlentities($row_rs_periodo['perio_ativo'], ENT_COMPAT, 'iso-8859-2')))) {echo "SELECTED";} ?>>Nao</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Alterar" /></td>
    </tr>
  </table>
  <input type="hidden" name="perio_id" value="<?php echo $row_rs_periodo['perio_id']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="perio_id" value="<?php echo $row_rs_periodo['perio_id']; ?>" />
</form>
</div>
<div id="lista" style="width:300px; height:155px; float:left;"></div>
        <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_periodo);
?>
