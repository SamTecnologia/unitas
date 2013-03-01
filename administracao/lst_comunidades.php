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
$tfi_listunts_comunidade3 = new TFI_TableFilter($conn_unitas, "tfi_listunts_comunidade3");
$tfi_listunts_comunidade3->addColumn("unts_comunidade.comun_razao_social", "STRING_TYPE", "comun_razao_social", "%");
$tfi_listunts_comunidade3->addColumn("unts_comunidade.comun_telefone", "STRING_TYPE", "comun_telefone", "%");
$tfi_listunts_comunidade3->addColumn("unts_comunidade.comun_email", "STRING_TYPE", "comun_email", "%");
$tfi_listunts_comunidade3->addColumn("unts_comunidade.comun_ativo", "STRING_TYPE", "comun_ativo", "%");
$tfi_listunts_comunidade3->Execute();

// Sorter
$tso_listunts_comunidade3 = new TSO_TableSorter("rsunts_comunidade1", "tso_listunts_comunidade3");
$tso_listunts_comunidade3->addColumn("unts_comunidade.comun_razao_social");
$tso_listunts_comunidade3->addColumn("unts_comunidade.comun_telefone");
$tso_listunts_comunidade3->addColumn("unts_comunidade.comun_email");
$tso_listunts_comunidade3->addColumn("unts_comunidade.comun_ativo");
$tso_listunts_comunidade3->setDefault("unts_comunidade.comun_razao_social");
$tso_listunts_comunidade3->Execute();

// Navigation
$nav_listunts_comunidade3 = new NAV_Regular("nav_listunts_comunidade3", "rsunts_comunidade1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rsunts_comunidade1 = $_SESSION['max_rows_nav_listunts_comunidade3'];
$pageNum_rsunts_comunidade1 = 0;
if (isset($_GET['pageNum_rsunts_comunidade1'])) {
  $pageNum_rsunts_comunidade1 = $_GET['pageNum_rsunts_comunidade1'];
}
$startRow_rsunts_comunidade1 = $pageNum_rsunts_comunidade1 * $maxRows_rsunts_comunidade1;

// Defining List Recordset variable
$NXTFilter_rsunts_comunidade1 = "1=1";
if (isset($_SESSION['filter_tfi_listunts_comunidade3'])) {
  $NXTFilter_rsunts_comunidade1 = $_SESSION['filter_tfi_listunts_comunidade3'];
}
// Defining List Recordset variable
$NXTSort_rsunts_comunidade1 = "unts_comunidade.comun_razao_social";
if (isset($_SESSION['sorter_tso_listunts_comunidade3'])) {
  $NXTSort_rsunts_comunidade1 = $_SESSION['sorter_tso_listunts_comunidade3'];
}
mysql_select_db($database_unitas, $unitas);

$query_rsunts_comunidade1 = "SELECT unts_comunidade.comun_razao_social, unts_comunidade.comun_telefone, unts_comunidade.comun_email, unts_comunidade.comun_ativo, unts_comunidade.comun_id FROM unts_comunidade WHERE {$NXTFilter_rsunts_comunidade1} ORDER BY {$NXTSort_rsunts_comunidade1}";
$query_limit_rsunts_comunidade1 = sprintf("%s LIMIT %d, %d", $query_rsunts_comunidade1, $startRow_rsunts_comunidade1, $maxRows_rsunts_comunidade1);
$rsunts_comunidade1 = mysql_query($query_limit_rsunts_comunidade1, $unitas) or die(mysql_error());
$row_rsunts_comunidade1 = mysql_fetch_assoc($rsunts_comunidade1);

if (isset($_GET['totalRows_rsunts_comunidade1'])) {
  $totalRows_rsunts_comunidade1 = $_GET['totalRows_rsunts_comunidade1'];
} else {
  $all_rsunts_comunidade1 = mysql_query($query_rsunts_comunidade1);
  $totalRows_rsunts_comunidade1 = mysql_num_rows($all_rsunts_comunidade1);
}
$totalPages_rsunts_comunidade1 = ceil($totalRows_rsunts_comunidade1/$maxRows_rsunts_comunidade1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listunts_comunidade3->checkBoundries();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Unitas - Administraçao</title>
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
  .KT_col_comun_razao_social {width:140px; overflow:hidden;}
  .KT_col_comun_telefone {width:140px; overflow:hidden;}
  .KT_col_comun_email {width:140px; overflow:hidden;}
  .KT_col_comun_ativo {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listunts_comunidade3">
  <h1> Comunidade
    <?php
  $nav_listunts_comunidade3->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listunts_comunidade3->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listunts_comunidade3'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listunts_comunidade3']; ?>
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
  if (@$_SESSION['has_filter_tfi_listunts_comunidade3'] == 1) {
?>
          <a href="<?php echo $tfi_listunts_comunidade3->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listunts_comunidade3->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th class="KT_sorter KT_col_comun_razao_social <?php echo $tso_listunts_comunidade3->getSortIcon('unts_comunidade.comun_razao_social'); ?>" id="comun_razao_social"> <a href="<?php echo $tso_listunts_comunidade3->getSortLink('unts_comunidade.comun_razao_social'); ?>">Nome</a></th>
            <th id="comun_telefone" class="KT_sorter KT_col_comun_telefone <?php echo $tso_listunts_comunidade3->getSortIcon('unts_comunidade.comun_telefone'); ?>"> <a href="<?php echo $tso_listunts_comunidade3->getSortLink('unts_comunidade.comun_telefone'); ?>">Telefone</a></th>
            <th id="comun_email" class="KT_sorter KT_col_comun_email <?php echo $tso_listunts_comunidade3->getSortIcon('unts_comunidade.comun_email'); ?>"> <a href="<?php echo $tso_listunts_comunidade3->getSortLink('unts_comunidade.comun_email'); ?>">E-mail</a></th>
            <th id="comun_ativo" class="KT_sorter KT_col_comun_ativo <?php echo $tso_listunts_comunidade3->getSortIcon('unts_comunidade.comun_ativo'); ?>"> <a href="<?php echo $tso_listunts_comunidade3->getSortLink('unts_comunidade.comun_ativo'); ?>">Ativa</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listunts_comunidade3'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listunts_comunidade3_comun_razao_social" id="tfi_listunts_comunidade3_comun_razao_social" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listunts_comunidade3_comun_razao_social']); ?>" size="120" maxlength="255" /></td>
              <td><input type="text" name="tfi_listunts_comunidade3_comun_telefone" id="tfi_listunts_comunidade3_comun_telefone" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listunts_comunidade3_comun_telefone']); ?>" size="20" maxlength="13" /></td>
              <td><input type="text" name="tfi_listunts_comunidade3_comun_email" id="tfi_listunts_comunidade3_comun_email" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listunts_comunidade3_comun_email']); ?>" size="20" maxlength="255" /></td>
              <td><select name="tfi_listunts_comunidade3_comun_ativo" id="tfi_listunts_comunidade3_comun_ativo">
                <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute(@$_SESSION['tfi_listunts_comunidade3_comun_ativo'])))) {echo "SELECTED";} ?>>Sim</option>
                <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute(@$_SESSION['tfi_listunts_comunidade3_comun_ativo'])))) {echo "SELECTED";} ?>>Nao</option>
              </select></td>
              <td><input type="submit" name="tfi_listunts_comunidade3" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsunts_comunidade1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsunts_comunidade1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_unts_comunidade" class="id_checkbox" value="<?php echo $row_rsunts_comunidade1['comun_id']; ?>" />
                  <input type="hidden" name="comun_id" class="id_field" value="<?php echo $row_rsunts_comunidade1['comun_id']; ?>" /></td>
                <td><div class="KT_col_comun_razao_social"><?php echo KT_FormatForList($row_rsunts_comunidade1['comun_razao_social'], 20); ?></div></td>
                <td><div class="KT_col_comun_telefone"><?php echo KT_FormatForList($row_rsunts_comunidade1['comun_telefone'], 20); ?></div></td>
                <td><div class="KT_col_comun_email"><?php echo KT_FormatForList($row_rsunts_comunidade1['comun_email'], 20); ?></div></td>
                <td><div class="KT_col_comun_ativo"><?php echo KT_FormatForList($row_rsunts_comunidade1['comun_ativo'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="frm_comunidade.php?comun_id=<?php echo $row_rsunts_comunidade1['comun_id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsunts_comunidade1 = mysql_fetch_assoc($rsunts_comunidade1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listunts_comunidade3->Prepare();
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
        <a class="KT_additem_op_link" href="frm_comunidade.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
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
mysql_free_result($rsunts_comunidade1);
?>
