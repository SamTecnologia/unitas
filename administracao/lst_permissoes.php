<?php virtual('/unitas/Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the required classes
require_once('../includes/tfi/TFI.php');
require_once('../includes/tso/TSO.php');
require_once('../includes/nav/NAV.php');

// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);

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

// Filter
$tfi_listunts_permissao2 = new TFI_TableFilter($conn_unitas, "tfi_listunts_permissao2");
$tfi_listunts_permissao2->addColumn("unts_tesouraria.tesra_id", "NUMERIC_TYPE", "tesra_id", "=");
$tfi_listunts_permissao2->addColumn("unts_usuario.user_id", "NUMERIC_TYPE", "user_id", "=");
$tfi_listunts_permissao2->addColumn("unts_permissao.permi_data_ini", "DATE_TYPE", "permi_data_ini", "=");
$tfi_listunts_permissao2->addColumn("unts_permissao.permi_data_fim", "DATE_TYPE", "permi_data_fim", "=");
$tfi_listunts_permissao2->addColumn("unts_permissao.permi_ativa", "STRING_TYPE", "permi_ativa", "%");
$tfi_listunts_permissao2->Execute();

// Sorter
$tso_listunts_permissao2 = new TSO_TableSorter("rsunts_permissao1", "tso_listunts_permissao2");
$tso_listunts_permissao2->addColumn("unts_tesouraria.tesra_descricao");
$tso_listunts_permissao2->addColumn("unts_usuario.user_nome");
$tso_listunts_permissao2->addColumn("unts_permissao.permi_data_ini");
$tso_listunts_permissao2->addColumn("unts_permissao.permi_data_fim");
$tso_listunts_permissao2->addColumn("unts_permissao.permi_ativa");
$tso_listunts_permissao2->setDefault("unts_permissao.tesra_id");
$tso_listunts_permissao2->Execute();

// Navigation
$nav_listunts_permissao2 = new NAV_Regular("nav_listunts_permissao2", "rsunts_permissao1", "../", $_SERVER['PHP_SELF'], 10);

mysql_select_db($database_unitas, $unitas);
$query_rs_tesouraria = "SELECT * FROM unts_tesouraria WHERE unts_tesouraria.tesra_ativo = 'S' ORDER BY unts_tesouraria.tesra_descricao";
$rs_tesouraria = mysql_query($query_rs_tesouraria, $unitas) or die(mysql_error());
$row_rs_tesouraria = mysql_fetch_assoc($rs_tesouraria);
$totalRows_rs_tesouraria = mysql_num_rows($rs_tesouraria);

mysql_select_db($database_unitas, $unitas);
$query_rs_usuario = "SELECT * FROM unts_usuario WHERE unts_usuario.user_ativo = 'S' ORDER BY unts_usuario.user_nome";
$rs_usuario = mysql_query($query_rs_usuario, $unitas) or die(mysql_error());
$row_rs_usuario = mysql_fetch_assoc($rs_usuario);
$totalRows_rs_usuario = mysql_num_rows($rs_usuario);

mysql_select_db($database_unitas, $unitas);
$query_Recordset1 = "SELECT tesra_descricao, tesra_id FROM unts_tesouraria ORDER BY tesra_descricao";
$Recordset1 = mysql_query($query_Recordset1, $unitas) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_unitas, $unitas);
$query_Recordset2 = "SELECT user_nome, user_id FROM unts_usuario ORDER BY user_nome";
$Recordset2 = mysql_query($query_Recordset2, $unitas) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_unitas, $unitas);
$query_Recordset3 = "SELECT tesra_descricao, tesra_id FROM unts_tesouraria ORDER BY tesra_descricao";
$Recordset3 = mysql_query($query_Recordset3, $unitas) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_unitas, $unitas);
$query_Recordset4 = "SELECT user_nome, user_id FROM unts_usuario ORDER BY user_nome";
$Recordset4 = mysql_query($query_Recordset4, $unitas) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

//NeXTenesio3 Special List Recordset
$maxRows_rsunts_permissao1 = $_SESSION['max_rows_nav_listunts_permissao2'];
$pageNum_rsunts_permissao1 = 0;
if (isset($_GET['pageNum_rsunts_permissao1'])) {
  $pageNum_rsunts_permissao1 = $_GET['pageNum_rsunts_permissao1'];
}
$startRow_rsunts_permissao1 = $pageNum_rsunts_permissao1 * $maxRows_rsunts_permissao1;

// Defining List Recordset variable
$NXTFilter_rsunts_permissao1 = "1=1";
if (isset($_SESSION['filter_tfi_listunts_permissao2'])) {
  $NXTFilter_rsunts_permissao1 = $_SESSION['filter_tfi_listunts_permissao2'];
}
// Defining List Recordset variable
$NXTSort_rsunts_permissao1 = "unts_permissao.tesra_id";
if (isset($_SESSION['sorter_tso_listunts_permissao2'])) {
  $NXTSort_rsunts_permissao1 = $_SESSION['sorter_tso_listunts_permissao2'];
}
mysql_select_db($database_unitas, $unitas);

$query_rsunts_permissao1 = "SELECT unts_tesouraria.tesra_descricao AS tesra_id, unts_usuario.user_nome AS user_id, unts_permissao.permi_data_ini, unts_permissao.permi_data_fim, unts_permissao.permi_ativa, unts_permissao.permi_id FROM (unts_permissao LEFT JOIN unts_tesouraria ON unts_permissao.tesra_id = unts_tesouraria.tesra_id) LEFT JOIN unts_usuario ON unts_permissao.user_id = unts_usuario.user_id WHERE {$NXTFilter_rsunts_permissao1} ORDER BY {$NXTSort_rsunts_permissao1}";
$query_limit_rsunts_permissao1 = sprintf("%s LIMIT %d, %d", $query_rsunts_permissao1, $startRow_rsunts_permissao1, $maxRows_rsunts_permissao1);
$rsunts_permissao1 = mysql_query($query_limit_rsunts_permissao1, $unitas) or die(mysql_error());
$row_rsunts_permissao1 = mysql_fetch_assoc($rsunts_permissao1);

if (isset($_GET['totalRows_rsunts_permissao1'])) {
  $totalRows_rsunts_permissao1 = $_GET['totalRows_rsunts_permissao1'];
} else {
  $all_rsunts_permissao1 = mysql_query($query_rsunts_permissao1);
  $totalRows_rsunts_permissao1 = mysql_num_rows($all_rsunts_permissao1);
}
$totalPages_rsunts_permissao1 = ceil($totalRows_rsunts_permissao1/$maxRows_rsunts_permissao1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listunts_permissao2->checkBoundries();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Administra&ccedil;&atilde;o</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_LIST_SETTINGS = {
  duplicate_buttons: true,
  duplicate_navigation: true,
  row_effects: true,
  show_as_buttons: true,
  record_counter: true
}
</script>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_tesra_id {width:140px; overflow:hidden;}
  .KT_col_user_id {width:140px; overflow:hidden;}
  .KT_col_permi_data_ini {width:140px; overflow:hidden;}
  .KT_col_permi_data_fim {width:140px; overflow:hidden;}
  .KT_col_permi_ativa {width:140px; overflow:hidden;}
</style>
</head>

<body>
<h1>Gerenciamento de Permiss&otilde;es</h1>
<p>&nbsp;
<div class="KT_tng" id="listunts_permissao2">
  <h1> Lista de Permiss&otilde;es
    <?php
  $nav_listunts_permissao2->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listunts_permissao2->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listunts_permissao2'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listunts_permissao2']; ?>
          <?php 
  // else Conditional region1
  } else { ?>
          <?php echo NXT_getResource("all"); ?>
          <?php } 
  // endif Conditional region1
?>
<?php echo NXT_getResource("records"); ?></a> &nbsp;
        &nbsp;
        <?php 
  // Show IF Conditional region2
  if (@$_SESSION['has_filter_tfi_listunts_permissao2'] == 1) {
?>
          <a href="<?php echo $tfi_listunts_permissao2->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listunts_permissao2->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="tesra_id" class="KT_sorter KT_col_tesra_id <?php echo $tso_listunts_permissao2->getSortIcon('unts_tesouraria.tesra_descricao'); ?>"> <a href="<?php echo $tso_listunts_permissao2->getSortLink('unts_tesouraria.tesra_descricao'); ?>">Tesra_id</a></th>
            <th id="user_id" class="KT_sorter KT_col_user_id <?php echo $tso_listunts_permissao2->getSortIcon('unts_usuario.user_nome'); ?>"> <a href="<?php echo $tso_listunts_permissao2->getSortLink('unts_usuario.user_nome'); ?>">Usuário</a></th>
            <th id="permi_data_ini" class="KT_sorter KT_col_permi_data_ini <?php echo $tso_listunts_permissao2->getSortIcon('unts_permissao.permi_data_ini'); ?>"> <a href="<?php echo $tso_listunts_permissao2->getSortLink('unts_permissao.permi_data_ini'); ?>">Data Inicial</a></th>
            <th id="permi_data_fim" class="KT_sorter KT_col_permi_data_fim <?php echo $tso_listunts_permissao2->getSortIcon('unts_permissao.permi_data_fim'); ?>"> <a href="<?php echo $tso_listunts_permissao2->getSortLink('unts_permissao.permi_data_fim'); ?>">Data Final</a></th>
            <th id="permi_ativa" class="KT_sorter KT_col_permi_ativa <?php echo $tso_listunts_permissao2->getSortIcon('unts_permissao.permi_ativa'); ?>"> <a href="<?php echo $tso_listunts_permissao2->getSortLink('unts_permissao.permi_ativa'); ?>">Ativa</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listunts_permissao2'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><select name="tfi_listunts_permissao2_tesra_id" id="tfi_listunts_permissao2_tesra_id">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listunts_permissao2_tesra_id']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset3['tesra_id']?>"<?php if (!(strcmp($row_Recordset3['tesra_id'], @$_SESSION['tfi_listunts_permissao2_tesra_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['tesra_descricao']?></option>
                <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
              </select></td>
              <td><select name="tfi_listunts_permissao2_user_id" id="tfi_listunts_permissao2_user_id">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listunts_permissao2_user_id']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset4['user_id']?>"<?php if (!(strcmp($row_Recordset4['user_id'], @$_SESSION['tfi_listunts_permissao2_user_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['user_nome']?></option>
                <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
              </select></td>
              <td><input type="text" name="tfi_listunts_permissao2_permi_data_ini" id="tfi_listunts_permissao2_permi_data_ini" value="<?php echo @$_SESSION['tfi_listunts_permissao2_permi_data_ini']; ?>" size="10" maxlength="22" /></td>
              <td><input type="text" name="tfi_listunts_permissao2_permi_data_fim" id="tfi_listunts_permissao2_permi_data_fim" value="<?php echo @$_SESSION['tfi_listunts_permissao2_permi_data_fim']; ?>" size="10" maxlength="22" /></td>
              <td><input type="text" name="tfi_listunts_permissao2_permi_ativa" id="tfi_listunts_permissao2_permi_ativa" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listunts_permissao2_permi_ativa']); ?>" size="20" maxlength="2" /></td>
              <td><input type="submit" name="tfi_listunts_permissao2" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsunts_permissao1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="7"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsunts_permissao1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_unts_permissao" class="id_checkbox" value="<?php echo $row_rsunts_permissao1['permi_id']; ?>" />
                  <input type="hidden" name="permi_id" class="id_field" value="<?php echo $row_rsunts_permissao1['permi_id']; ?>" /></td>
                <td><div class="KT_col_tesra_id"><?php echo KT_FormatForList($row_rsunts_permissao1['tesra_id'], 20); ?></div></td>
                <td><div class="KT_col_user_id"><?php echo KT_FormatForList($row_rsunts_permissao1['user_id'], 20); ?></div></td>
                <td><div class="KT_col_permi_data_ini"><?php echo KT_formatDate($row_rsunts_permissao1['permi_data_ini']); ?></div></td>
                <td><div class="KT_col_permi_data_fim"><?php echo KT_formatDate($row_rsunts_permissao1['permi_data_fim']); ?></div></td>
                <td><div class="KT_col_permi_ativa"><?php echo KT_FormatForList($row_rsunts_permissao1['permi_ativa'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="frm_permissao.php?permi_id=<?php echo $row_rsunts_permissao1['permi_id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsunts_permissao1 = mysql_fetch_assoc($rsunts_permissao1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listunts_permissao2->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a></div>
        <span>&nbsp;</span>
        <select name="no_new" id="no_new">
          <option value="1">1</option>
          <option value="3">3</option>
          <option value="6">6</option>
        </select>
        <a class="KT_additem_op_link" href="frm_permissao.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
</p>
<form id="form2" name="form2" method="post" action="/unitas/administracao/index2.php">
  <label>
    <input type="submit" name="volta" id="volta" value="Voltar" />
  </label>
</form>
</body>
</html>
<?php
mysql_free_result($rs_tesouraria);

mysql_free_result($rs_usuario);

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($rsunts_permissao1);
?>
