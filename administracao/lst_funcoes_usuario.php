<?php require_once('../Connections/unitas.php'); ?>
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
$tfi_listunts_funcao_usuario1 = new TFI_TableFilter($conn_unitas, "tfi_listunts_funcao_usuario1");
$tfi_listunts_funcao_usuario1->addColumn("unts_usuario.user_id", "NUMERIC_TYPE", "user_id", "=");
$tfi_listunts_funcao_usuario1->addColumn("unts_funcao.func_id", "NUMERIC_TYPE", "func_id", "=");
$tfi_listunts_funcao_usuario1->addColumn("unts_funcao_usuario.func_user_ativo", "STRING_TYPE", "func_user_ativo", "%");
$tfi_listunts_funcao_usuario1->Execute();

// Sorter
$tso_listunts_funcao_usuario1 = new TSO_TableSorter("rsunts_funcao_usuario1", "tso_listunts_funcao_usuario1");
$tso_listunts_funcao_usuario1->addColumn("unts_usuario.user_nome");
$tso_listunts_funcao_usuario1->addColumn("unts_funcao.func_descricao");
$tso_listunts_funcao_usuario1->addColumn("unts_funcao_usuario.func_user_ativo");
$tso_listunts_funcao_usuario1->setDefault("unts_funcao_usuario.user_id");
$tso_listunts_funcao_usuario1->Execute();

// Navigation
$nav_listunts_funcao_usuario1 = new NAV_Regular("nav_listunts_funcao_usuario1", "rsunts_funcao_usuario1", "../", $_SERVER['PHP_SELF'], 10);

mysql_select_db($database_unitas, $unitas);
$query_Recordset1 = "SELECT user_nome, user_id FROM unts_usuario ORDER BY user_nome";
$Recordset1 = mysql_query($query_Recordset1, $unitas) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_unitas, $unitas);
$query_Recordset2 = "SELECT func_descricao, func_id FROM unts_funcao ORDER BY func_descricao";
$Recordset2 = mysql_query($query_Recordset2, $unitas) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

//NeXTenesio3 Special List Recordset
$maxRows_rsunts_funcao_usuario1 = $_SESSION['max_rows_nav_listunts_funcao_usuario1'];
$pageNum_rsunts_funcao_usuario1 = 0;
if (isset($_GET['pageNum_rsunts_funcao_usuario1'])) {
  $pageNum_rsunts_funcao_usuario1 = $_GET['pageNum_rsunts_funcao_usuario1'];
}
$startRow_rsunts_funcao_usuario1 = $pageNum_rsunts_funcao_usuario1 * $maxRows_rsunts_funcao_usuario1;

// Defining List Recordset variable
$NXTFilter_rsunts_funcao_usuario1 = "1=1";
if (isset($_SESSION['filter_tfi_listunts_funcao_usuario1'])) {
  $NXTFilter_rsunts_funcao_usuario1 = $_SESSION['filter_tfi_listunts_funcao_usuario1'];
}
// Defining List Recordset variable
$NXTSort_rsunts_funcao_usuario1 = "unts_funcao_usuario.user_id";
if (isset($_SESSION['sorter_tso_listunts_funcao_usuario1'])) {
  $NXTSort_rsunts_funcao_usuario1 = $_SESSION['sorter_tso_listunts_funcao_usuario1'];
}
mysql_select_db($database_unitas, $unitas);

$query_rsunts_funcao_usuario1 = "SELECT unts_usuario.user_nome AS user_id, unts_funcao.func_descricao AS func_id, unts_funcao_usuario.func_user_ativo, unts_funcao_usuario.func_user_id FROM (unts_funcao_usuario LEFT JOIN unts_usuario ON unts_funcao_usuario.user_id = unts_usuario.user_id) LEFT JOIN unts_funcao ON unts_funcao_usuario.func_id = unts_funcao.func_id WHERE {$NXTFilter_rsunts_funcao_usuario1} ORDER BY {$NXTSort_rsunts_funcao_usuario1}";
$query_limit_rsunts_funcao_usuario1 = sprintf("%s LIMIT %d, %d", $query_rsunts_funcao_usuario1, $startRow_rsunts_funcao_usuario1, $maxRows_rsunts_funcao_usuario1);
$rsunts_funcao_usuario1 = mysql_query($query_limit_rsunts_funcao_usuario1, $unitas) or die(mysql_error());
$row_rsunts_funcao_usuario1 = mysql_fetch_assoc($rsunts_funcao_usuario1);

if (isset($_GET['totalRows_rsunts_funcao_usuario1'])) {
  $totalRows_rsunts_funcao_usuario1 = $_GET['totalRows_rsunts_funcao_usuario1'];
} else {
  $all_rsunts_funcao_usuario1 = mysql_query($query_rsunts_funcao_usuario1);
  $totalRows_rsunts_funcao_usuario1 = mysql_num_rows($all_rsunts_funcao_usuario1);
}
$totalPages_rsunts_funcao_usuario1 = ceil($totalRows_rsunts_funcao_usuario1/$maxRows_rsunts_funcao_usuario1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listunts_funcao_usuario1->checkBoundries();
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
  .KT_col_user_id {width:140px; overflow:hidden;}
  .KT_col_func_id {width:140px; overflow:hidden;}
  .KT_col_func_user_ativo {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listunts_funcao_usuario1">
  <h1> Lista de Fun&ccedil;&otilde;es do Usu&aacute;rio
    <?php
  $nav_listunts_funcao_usuario1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listunts_funcao_usuario1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listunts_funcao_usuario1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listunts_funcao_usuario1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listunts_funcao_usuario1'] == 1) {
?>
          <a href="<?php echo $tfi_listunts_funcao_usuario1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listunts_funcao_usuario1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="user_id" class="KT_sorter KT_col_user_id <?php echo $tso_listunts_funcao_usuario1->getSortIcon('unts_usuario.user_nome'); ?>"> <a href="<?php echo $tso_listunts_funcao_usuario1->getSortLink('unts_usuario.user_nome'); ?>">Usuário</a></th>
            <th id="func_id" class="KT_sorter KT_col_func_id <?php echo $tso_listunts_funcao_usuario1->getSortIcon('unts_funcao.func_descricao'); ?>"> <a href="<?php echo $tso_listunts_funcao_usuario1->getSortLink('unts_funcao.func_descricao'); ?>">Funçao</a></th>
            <th id="func_user_ativo" class="KT_sorter KT_col_func_user_ativo <?php echo $tso_listunts_funcao_usuario1->getSortIcon('unts_funcao_usuario.func_user_ativo'); ?>"> <a href="<?php echo $tso_listunts_funcao_usuario1->getSortLink('unts_funcao_usuario.func_user_ativo'); ?>">Ativa</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listunts_funcao_usuario1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><select name="tfi_listunts_funcao_usuario1_user_id" id="tfi_listunts_funcao_usuario1_user_id">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listunts_funcao_usuario1_user_id']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset1['user_id']?>"<?php if (!(strcmp($row_Recordset1['user_id'], @$_SESSION['tfi_listunts_funcao_usuario1_user_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['user_nome']?></option>
                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
              </select></td>
              <td><select name="tfi_listunts_funcao_usuario1_func_id" id="tfi_listunts_funcao_usuario1_func_id">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listunts_funcao_usuario1_func_id']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset2['func_id']?>"<?php if (!(strcmp($row_Recordset2['func_id'], @$_SESSION['tfi_listunts_funcao_usuario1_func_id']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['func_descricao']?></option>
                <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
              </select></td>
              <td><input type="text" name="tfi_listunts_funcao_usuario1_func_user_ativo" id="tfi_listunts_funcao_usuario1_func_user_ativo" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listunts_funcao_usuario1_func_user_ativo']); ?>" size="20" maxlength="2" /></td>
              <td><input type="submit" name="tfi_listunts_funcao_usuario1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsunts_funcao_usuario1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsunts_funcao_usuario1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_unts_funcao_usuario" class="id_checkbox" value="<?php echo $row_rsunts_funcao_usuario1['func_user_id']; ?>" />
                  <input type="hidden" name="func_user_id" class="id_field" value="<?php echo $row_rsunts_funcao_usuario1['func_user_id']; ?>" /></td>
                <td><div class="KT_col_user_id"><?php echo KT_FormatForList($row_rsunts_funcao_usuario1['user_id'], 20); ?></div></td>
                <td><div class="KT_col_func_id"><?php echo KT_FormatForList($row_rsunts_funcao_usuario1['func_id'], 20); ?></div></td>
                <td><div class="KT_col_func_user_ativo"><?php echo KT_FormatForList($row_rsunts_funcao_usuario1['func_user_ativo'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="frm_funcao_usuario.php?func_user_id=<?php echo $row_rsunts_funcao_usuario1['func_user_id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsunts_funcao_usuario1 = mysql_fetch_assoc($rsunts_funcao_usuario1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listunts_funcao_usuario1->Prepare();
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
        <a class="KT_additem_op_link" href="frm_funcao_usuario.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<form id="form2" name="form2" method="post" action="/unitas/administracao/index2.php">
  <label>
    <input type="submit" name="volta" id="volta" value="Voltar" />
  </label>
</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($rsunts_funcao_usuario1);
?>
