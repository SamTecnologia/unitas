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
  $insertSQL = sprintf("INSERT INTO unts_periodo (perio_mes, perio_ano, perio_ativo) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['perio_mes'], "text"),
                       GetSQLValueString($_POST['perio_ano'], "int"),
                       GetSQLValueString($_POST['perio_ativo'], "text"));

  mysql_select_db($database_unitas, $unitas);
  $Result1 = mysql_query($insertSQL, $unitas) or die(mysql_error());

  $insertGoTo = "lst_periodo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_rs_periodo = 06;
$pageNum_rs_periodo = 0;
if (isset($_GET['pageNum_rs_periodo'])) {
  $pageNum_rs_periodo = $_GET['pageNum_rs_periodo'];
}
$startRow_rs_periodo = $pageNum_rs_periodo * $maxRows_rs_periodo;

mysql_select_db($database_unitas, $unitas);
$query_rs_periodo = "SELECT perio_mes, perio_ano FROM unts_periodo ORDER BY perio_id DESC";
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
                <option value="" >Selecione</option>
                <option value="Jan" <?php if (!(strcmp("Jan", ""))) {echo "SELECTED";} ?>>Jan</option>
                <option value="Fev" <?php if (!(strcmp("Fev", ""))) {echo "SELECTED";} ?>>Fev</option>
                <option value="Mar" <?php if (!(strcmp("Mar", ""))) {echo "SELECTED";} ?>>Mar</option>
                <option value="Abr" <?php if (!(strcmp("Abr", ""))) {echo "SELECTED";} ?>>Abr</option>
                <option value="Mai" <?php if (!(strcmp("Mai", ""))) {echo "SELECTED";} ?>>Mai</option>
                <option value="Jun" <?php if (!(strcmp("Jun", ""))) {echo "SELECTED";} ?>>Jun</option>
                <option value="Jul" <?php if (!(strcmp("Jul", ""))) {echo "SELECTED";} ?>>Jul</option>
                <option value="Ago" <?php if (!(strcmp("Ago", ""))) {echo "SELECTED";} ?>>Ago</option>
                <option value="Set" <?php if (!(strcmp("Set", ""))) {echo "SELECTED";} ?>>Set</option>
                <option value="Out" <?php if (!(strcmp("Out", ""))) {echo "SELECTED";} ?>>Out</option>
                <option value="Nov" <?php if (!(strcmp("Nov", ""))) {echo "SELECTED";} ?>>Nov</option>
                <option value="Dez" <?php if (!(strcmp("Dez", ""))) {echo "SELECTED";} ?>>Dez</option>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Ano:</td>
              <td><input name="perio_ano" type="text" value="" size="4" maxlength="4" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Ativo:</td>
              <td><select name="perio_ativo">
                <option value="S" <?php if (!(strcmp("S", ""))) {echo "SELECTED";} ?>>Sim</option>
                <option value="N" <?php if (!(strcmp("N", ""))) {echo "SELECTED";} ?>>Nao</option>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="Inserir" /></td>
            </tr>
          </table>
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
</div>
        <div id="lista" style="width:300px; height:155px; float:left;">
        <table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><strong>&Uacute;ltimos Per&iacute;odos Cadastrados</strong></td>
  </tr>
  <?php do { ?>
  <tr>
      <td><?php echo $row_rs_periodo['perio_mes']; ?>/<?php echo $row_rs_periodo['perio_ano']; ?></td>
  </tr>
  <?php } while ($row_rs_periodo = mysql_fetch_assoc($rs_periodo)); ?>
        </table>

</div>
        <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_periodo);
?>
