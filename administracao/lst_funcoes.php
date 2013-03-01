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
$tfi_listunts_funcao1 = new TFI_TableFilter($conn_unitas, "tfi_listunts_funcao1");
$tfi_listunts_funcao1->addColumn("unts_funcao.func_descricao", "STRING_TYPE", "func_descricao", "%");
$tfi_listunts_funcao1->addColumn("unts_funcao.func_ativa", "STRING_TYPE", "func_ativa", "%");
$tfi_listunts_funcao1->Execute();

// Sorter
$tso_listunts_funcao1 = new TSO_TableSorter("rsunts_funcao1", "tso_listunts_funcao1");
$tso_listunts_funcao1->addColumn("unts_funcao.func_descricao");
$tso_listunts_funcao1->addColumn("unts_funcao.func_ativa");
$tso_listunts_funcao1->setDefault("unts_funcao.func_descricao");
$tso_listunts_funcao1->Execute();

// Navigation
$nav_listunts_funcao1 = new NAV_Regular("nav_listunts_funcao1", "rsunts_funcao1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rsunts_funcao1 = $_SESSION['max_rows_nav_listunts_funcao1'];
$pageNum_rsunts_funcao1 = 0;
if (isset($_GET['pageNum_rsunts_funcao1'])) {
  $pageNum_rsunts_funcao1 = $_GET['pageNum_rsunts_funcao1'];
}
$startRow_rsunts_funcao1 = $pageNum_rsunts_funcao1 * $maxRows_rsunts_funcao1;

// Defining List Recordset variable
$NXTFilter_rsunts_funcao1 = "1=1";
if (isset($_SESSION['filter_tfi_listunts_funcao1'])) {
  $NXTFilter_rsunts_funcao1 = $_SESSION['filter_tfi_listunts_funcao1'];
}
// Defining List Recordset variable
$NXTSort_rsunts_funcao1 = "unts_funcao.func_descricao";
if (isset($_SESSION['sorter_tso_listunts_funcao1'])) {
  $NXTSort_rsunts_funcao1 = $_SESSION['sorter_tso_listunts_funcao1'];
}
mysql_select_db($database_unitas, $unitas);

$query_rsunts_funcao1 = "SELECT unts_funcao.func_descricao, unts_funcao.func_ativa, unts_funcao.func_id FROM unts_funcao WHERE {$NXTFilter_rsunts_funcao1} ORDER BY {$NXTSort_rsunts_funcao1}";
$query_limit_rsunts_funcao1 = sprintf("%s LIMIT %d, %d", $query_rsunts_funcao1, $startRow_rsunts_funcao1, $maxRows_rsunts_funcao1);
$rsunts_funcao1 = mysql_query($query_limit_rsunts_funcao1, $unitas) or die(mysql_error());
$row_rsunts_funcao1 = mysql_fetch_assoc($rsunts_funcao1);

if (isset($_GET['totalRows_rsunts_funcao1'])) {
  $totalRows_rsunts_funcao1 = $_GET['totalRows_rsunts_funcao1'];
} else {
  $all_rsunts_funcao1 = mysql_query($query_rsunts_funcao1);
  $totalRows_rsunts_funcao1 = mysql_num_rows($all_rsunts_funcao1);
}
$totalPages_rsunts_funcao1 = ceil($totalRows_rsunts_funcao1/$maxRows_rsunts_funcao1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listunts_funcao1->checkBoundries();
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
  .KT_col_func_descricao {width:210px; overflow:hidden;}
  .KT_col_func_ativa {width:14px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listunts_funcao1">
  <h1> Lista de Fun&ccedil;&otilde;es
    <?php
  $nav_listunts_funcao1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listunts_funcao1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listunts_funcao1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listunts_funcao1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listunts_funcao1'] == 1) {
?>
          <a href="<?php echo $tfi_listunts_funcao1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listunts_funcao1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="func_descricao" class="KT_sorter KT_col_func_descricao <?php echo $tso_listunts_funcao1->getSortIcon('unts_funcao.func_descricao'); ?>"> <a href="<?php echo $tso_listunts_funcao1->getSortLink('unts_funcao.func_descricao'); ?>">Descriçao</a></th>
            <th id="func_ativa" class="KT_sorter KT_col_func_ativa <?php echo $tso_listunts_funcao1->getSortIcon('unts_funcao.func_ativa'); ?>"> <a href="<?php echo $tso_listunts_funcao1->getSortLink('unts_funcao.func_ativa'); ?>">Ativa</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listunts_funcao1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listunts_funcao1_func_descricao" id="tfi_listunts_funcao1_func_descricao" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listunts_funcao1_func_descricao']); ?>" size="30" maxlength="255" /></td>
              <td><input type="text" name="tfi_listunts_funcao1_func_ativa" id="tfi_listunts_funcao1_func_ativa" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listunts_funcao1_func_ativa']); ?>" size="2" maxlength="2" /></td>
              <td><input type="submit" name="tfi_listunts_funcao1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsunts_funcao1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsunts_funcao1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_unts_funcao" class="id_checkbox" value="<?php echo $row_rsunts_funcao1['func_id']; ?>" />
                  <input type="hidden" name="func_id" class="id_field" value="<?php echo $row_rsunts_funcao1['func_id']; ?>" /></td>
                <td><div class="KT_col_func_descricao"><?php echo KT_FormatForList($row_rsunts_funcao1['func_descricao'], 30); ?></div></td>
                <td><div class="KT_col_func_ativa"><?php echo KT_FormatForList($row_rsunts_funcao1['func_ativa'], 2); ?></div></td>
                <td><a class="KT_edit_link" href="frm_funcao.php?func_id=<?php echo $row_rsunts_funcao1['func_id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsunts_funcao1 = mysql_fetch_assoc($rsunts_funcao1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listunts_funcao1->Prepare();
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
        <a class="KT_additem_op_link" href="frm_funcao.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
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
mysql_free_result($rsunts_funcao1);
?>
