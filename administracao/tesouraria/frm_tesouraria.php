<?php require_once('../../Connections/unitas.php'); ?>
<?php require_once("../../includes/common/KT_common.php");?>
<?php
// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_unitas, "../../");
//Grand Levels: Any
$restrict->Execute();
//End Restrict Access To Page

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

$user_rs_usuario = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $user_rs_usuario = $_SESSION['kt_login_id'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_usuario = sprintf("SELECT * FROM unts_usuario WHERE unts_usuario.user_id = %s", GetSQLValueString($user_rs_usuario, "int"));
$rs_usuario = mysql_query($query_rs_usuario, $unitas) or die(mysql_error());
$row_rs_usuario = mysql_fetch_assoc($rs_usuario);
$totalRows_rs_usuario = mysql_num_rows($rs_usuario);

$mov_rs_movimento = "-1";
if (isset($_GET['movimento'])) {
  $mov_rs_movimento = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_movimento = sprintf("SELECT CONCAT( unts_periodo.perio_mes, '/', unts_periodo.perio_ano ) AS mov_perio, unts_movimento . * , unts_tesouraria . * , unts_comunidade . * FROM unts_movimento INNER JOIN unts_tesouraria ON unts_tesouraria.tesra_id = unts_movimento.tesra_id INNER JOIN unts_comunidade ON unts_comunidade.comun_id = unts_tesouraria.comun_id INNER JOIN unts_periodo ON unts_periodo.perio_id = unts_movimento.perio_id WHERE unts_movimento.movim_ativo = 'S' AND unts_movimento.movim_id = %s", GetSQLValueString($mov_rs_movimento, "int"));
$rs_movimento = mysql_query($query_rs_movimento, $unitas) or die(mysql_error());
$row_rs_movimento = mysql_fetch_assoc($rs_movimento);
$totalRows_rs_movimento = mysql_num_rows($rs_movimento);

$movim_rs_saldoinicial = "-1";
if (isset($_GET['movimento'])) {
  $movim_rs_saldoinicial = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_saldoinicial = sprintf("SELECT unts_saldo_ini . * , unts_contas . * FROM unts_saldo_ini LEFT JOIN unts_contas ON unts_contas.conta_id = unts_saldo_ini.conta_id WHERE unts_saldo_ini.movim_id = %s", GetSQLValueString($movim_rs_saldoinicial, "int"));
$rs_saldoinicial = mysql_query($query_rs_saldoinicial, $unitas) or die(mysql_error());
$row_rs_saldoinicial = mysql_fetch_assoc($rs_saldoinicial);
$totalRows_rs_saldoinicial = mysql_num_rows($rs_saldoinicial);

$moviment_rs_tot_receitas = "-1";
if (isset($_GET['movimento'])) {
  $moviment_rs_tot_receitas = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_tot_receitas = sprintf("SELECT SUM( item_valor ) AS total_receitas FROM unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_e WHERE movim_id =%s AND conta_tipo = 'R'", GetSQLValueString($moviment_rs_tot_receitas, "int"));
$rs_tot_receitas = mysql_query($query_rs_tot_receitas, $unitas) or die(mysql_error());
$row_rs_tot_receitas = mysql_fetch_assoc($rs_tot_receitas);
$totalRows_rs_tot_receitas = mysql_num_rows($rs_tot_receitas);

$movimen_rs_tot_despesas = "-1";
if (isset($_GET['movimento'])) {
  $movimen_rs_tot_despesas = $_GET['movimento'];
}
mysql_select_db($database_unitas, $unitas);
$query_rs_tot_despesas = sprintf("SELECT SUM( item_valor ) AS total_despesas FROM unts_item_mov INNER JOIN unts_contas ON unts_contas.conta_id = unts_item_mov.conta_id_s WHERE movim_id = %s AND conta_tipo = 'D'", GetSQLValueString($movimen_rs_tot_despesas, "int"));
$rs_tot_despesas = mysql_query($query_rs_tot_despesas, $unitas) or die(mysql_error());
$row_rs_tot_despesas = mysql_fetch_assoc($rs_tot_despesas);
$totalRows_rs_tot_despesas = mysql_num_rows($rs_tot_despesas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<link rel="stylesheet" href="/unitas/css/unitas.css" type="text/css" /> 
<title>Unitas - Movimenta&ccedil;&atilde;o Financeira</title>
</head>
<body>
<p align="right"><strong><?php echo $row_rs_usuario['user_id']; ?> - <?php echo $row_rs_usuario['user_nome']; ?></strong></p>
<div id="geral">
    <div id="ferramentas">
        <div id="logo_unitas">
        </div>
        <div id="top_menu">
                <a href="/unitas/administracao/tesouraria/frm_movimento.php?tesouraria=<?php echo $row_rs_movimento['tesra_id']; ?>">Novo Movimento</a> | 
                <a href="/unitas/administracao/tesouraria/frm_seleciona_movimento.php?tesouraria=<?php echo $row_rs_movimento['tesra_id']; ?>">Movimento</a> | 
                <a href="/unitas/administracao/frm_permissao_usuario.php">Tesourarias</a> | 
                Relatórios | <a href="/unitas/administracao/index2.php">Administra&ccedil;&atilde;o</a> | 
                <?php if ($row_rs_movimento['tesra_id'] == 6){ ?><a href="/unitas/administracao/plus/index.php">&Aacute;rea Plus</a><?php }?>
        </div>
    </div>
  <div id="tesouraria">
        <div id="logo_comunidade" align="center" style="width:140px;"><img src="../../images/tesourarias_logo/<?php echo $row_rs_movimento['comun_id']; ?>/<?php echo $row_rs_movimento['comun_icone']; ?>" width="90" height="91" alt="logomarca <?php echo $row_rs_movimento['comun_razao_social']; ?>" /><br />
        <strong><?php echo $row_rs_movimento['tesra_descricao']; ?></strong><br /><strong><?php echo $row_rs_movimento['mov_perio']; ?></strong></div>
      <div id="saldo_ini">
            <h4>Saldo Inicial</h4>
             <?php do { ?>
              <table width="100%">
                <tr>
                  <td width="5"></td>
                  <td><?php echo $row_rs_saldoinicial['conta_id']; ?> - <?php echo $row_rs_saldoinicial['conta_descricao']; ?></td>
                  <td align="right"><?php echo number_format($row_rs_saldoinicial['saldo_valor'],2,",","."); ?></td>
                </tr>
             </table>
              <?php } while ($row_rs_saldoinicial = mysql_fetch_assoc($rs_saldoinicial)); ?>
    </div>
        <div = id="balanco">
        <h4>Apura&ccedil;&atilde;o de Receitas e Despesas</h4>
            <table width="100%">
                <tr>
                  <td>Receitas no M&ecirc;s:</td>
                  <td align="right"><?php echo number_format($row_rs_tot_receitas['total_receitas'],2,",","."); ?></td>
                </tr>
                <tr>
                  <td>Despesas no M&ecirc;s:</td>
                  <td align="right"><?php echo number_format($row_rs_tot_despesas['total_despesas'],2,",","."); ?></td>
                </tr>
                <tr>
                  <td>Saldo:</td>
                  <td align="right"><?php echo number_format(($row_rs_tot_receitas['total_receitas'] - $row_rs_tot_despesas['total_despesas']),2,",",".")?></td>
                </tr>
            </table>
    	</div>   
	</div>
        <?php $area = $_GET['area'];?>
    <div id="menu_lateral">
    	<ul>
        <li><a href="?area=novo_lancamento&movimento=<?php echo $row_rs_movimento['movim_id']; ?>&amp;tesouraria=<?php echo $row_rs_movimento['tesra_id']; ?>&amp;comunidade=<?php echo $row_rs_movimento['comun_id']; ?>">Novo Lan&ccedil;amento</a></li><br />
        <li><a href="?area=visu_movimento&movimento=<?php echo $row_rs_movimento['movim_id']; ?>">Visualizar Movimento</a></li><br />
        <li><a href="?area=sald_inicial&tesouraria=<?php echo $row_rs_movimento['tesra_id']; ?>&amp;movimento=<?php echo $_GET['movimento'];?>&comunidade= <?php echo $row_rs_movimento['comun_id']; ?>">Saldo inicial</a></li>
        <li><a href="?area=rela_caixas&tesouraria=<?php echo $row_rs_movimento['tesra_id']; ?>&amp;movimento=<?php echo $row_rs_movimento['movim_id']; ?>">Caixas</a></li>
        <br />
        <li><a href="?area=pres_contas&movimento=<?php echo $_GET['movimento']; ?>&amp;tesouraria=<?php echo $row_rs_movimento['tesra_id']; ?>">Prest Contas </a></li>
        <br />   
        <li><a href="?area=pres_contas_simplificada&tesouraria=<?php echo $row_rs_movimento['tesra_id']; ?>&amp;movimento=<?php echo $_GET['movimento']; ?>">Prest Contas Comunidade</a></li>
        <?php if ($row_rs_movimento['tesra_id'] == 6){?>
        <li><a href="?area=apuracao_mitra&tesouraria=<?php echo $row_rs_movimento['tesra_id']; ?>&amp;movimento=<?php echo $_GET['movimento']; ?>">Apura&ccedil;&atilde;o Mitra</a></li>
        <?php }?>
      </ul>
  </div> 
    <div id="conteudo">
                <?php
                    $area = @$_GET["area"];

                    switch ($area)
                    {
                        default:
                            include "frm_novo_lancamento.php";
                            break;
                        
                        case "novo_lancamento":
                            include "frm_novo_lancamento.php";
                            break;
                            
                        case "visu_movimento":
                            include "frm_visualizar_movimento.php";
                            break;
                            
                        case "sald_inicial":
                            include "frm_saldo_inicial.php";
                            break;
                        
                        case "rela_caixas":
                            include "frm_relatorio_caixas.php";
                            break;
                        
                        case "pres_contas":
                            include "frm_prest_contas_completa.php";
                            break;
                        
                        case "pres_contas_simplificada":
                            include "frm_prest_contas_simplificada.php";
                            break;
                        
                        case "apuracao_mitra":
                            include "frm_apuracao_mitra.php";
                            break;
                    }
                    
                ?>
		<div class="clear"></div>
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_usuario);

mysql_free_result($rs_movimento);

//mysql_free_result($rs_saldoinicial);

mysql_free_result($rs_tot_receitas);

mysql_free_result($rs_tot_despesas);
?>
