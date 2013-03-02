<?php require_once('../../Connections/conecta.php'); ?>
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

$MM_restrictGoTo = "../../index.php";
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
	include_once('../../funcoes/variaveis.php');
?>
<?php
if (isset($_GET['tesouraria'])) {
  $tesouraria_rs_usuario = $_GET['tesouraria'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulosite.'- Início'; ?></title>
<link href="../../css/template.css" rel="stylesheet" type="text/css" />

</head>

<body>
	<div id="geral">
    	<div id="topo">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="220"><img src="../../img/logomarca.png" width="250" height="89" /></td>
    <td align="center" nowrap="nowrap"><h1>Unidade Em Administração Eclesial</h1></td>
  </tr>
        </table>
        <ul id="menu">
            <li><a href="../index.php">Início</a></li>
            <li><a href="#">Cadastro</a>
<ul>
          <li><a href="#">Período</a></li>
                <li><a href="#">Tesouraria</a></li>
                <li><a href="#">Usuário</a></li>
                <li><a href="#">Comunidade</a></li>
                <li><a href="#">Funções</a></li>
              </ul>
            </li>
            <li><a href="#">Atribuições</a>
<ul>
          <li><a href="#">Usuários/Tesouraria</a></li>
                <li><a href="#">Usuários/Funções</a></li>
              </ul>
            </li>
            <li><a href="#">Movimentos</a>
<ul>
          <li><a href="#">Novo Movimento</a></li>
                <li><a href="#">Seleção de Movimento</a></li>
              </ul>
            </li>
            <li><a href="#">Contas</a>
<ul>
          <li><a href="#">Cadastro</a></li>
              </ul>
            </li>
            <li><a href="tesouraria/frm_seleciona_movimento.php?tesouraria=<?php echo $row_rs_tesouraria['tesra_id']; ?>">Relatórios</a></li>
          </ul>
      </div>
        <div id="header">
        	<h3>Seleção de Tesouraria</h3>
            <?php saudacao(); echo $nomeusuario;?>, selecione uma tesouraria na relação abaixo:
        </div>
        <div id="content"></div>
    </div>
</body>
</html>