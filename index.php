<?php require_once('Connections/conecta.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=$_POST['pwd'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "admin/index.php";
  $MM_redirectLoginFailed = "index2.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conecta, $conecta);
  
  $LoginRS__query=sprintf("SELECT user_login, user_senha FROM unts_usuario WHERE user_login=%s AND user_senha=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conecta) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<?php
	include_once('funcoes/variaveis.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulosite.'- Login'; ?></title>
</head>

<body>
	<div id="geral">
	  <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
	    <table width="315" border="0" align="center" cellpadding="0" cellspacing="4">
	      <tr>
	        <td colspan="2" align="center"><h3>Acesso Restrito</h3></td>
          </tr>
	      <tr>
	        <th width="76" align="right">Usuário:</th>
	        <td width="240"><label for="user"></label>
            <input name="user" type="text" id="user" size="40" /></td>
          </tr>
	      <tr>
	        <th align="right">Senha:</th>
	        <td><label for="pwd"></label>
            <input name="pwd" type="password" id="pwd" size="40" /></td>
          </tr>
	      <tr>
	        <th>&nbsp;</th>
	        <td align="right"><input type="submit" name="button" id="button" value="Ok" /></td>
          </tr>
        </table>
      </form>
    
    </div>
</body>
</html>