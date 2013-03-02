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

$colname_rs_usuario = "nenhum";
if (isset($_SESSION['MM_Username'])) {
  $colname_rs_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conecta, $conecta);
$query_rs_usuario = sprintf("SELECT * FROM unts_usuario WHERE unts_usuario.user_login = %s", GetSQLValueString($colname_rs_usuario, "text"));
$rs_usuario = mysql_query($query_rs_usuario, $conecta) or die(mysql_error());
$row_rs_usuario = mysql_fetch_assoc($rs_usuario);
$totalRows_rs_usuario = mysql_num_rows($rs_usuario);

$titulosite = 'Unitas - Unidade em Administração Eclesial';

mysql_free_result($rs_usuario);
$idusuario = $row_rs_usuario['user_id'];
$nomeusuario = $row_rs_usuario['user_nome'];
?>
<?php
function saudacao(){
	$hr = date(" H ");
	
	if($hr >= 12 && $hr<18) {
		$resp = "Boa Tarde, ";
	}else if ($hr >= 0 && $hr<12 ){
		$resp = "Bom Dia, ";
	}else{
		$resp = "Boa Noite, ";
	}
	echo $resp;	
}
?>