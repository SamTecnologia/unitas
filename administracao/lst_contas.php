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
$tfi_listunts_contas5 = new TFI_TableFilter($conn_unitas, "tfi_listunts_contas5");
$tfi_listunts_contas5->addColumn("unts_contas.conta_tipo", "STRING_TYPE", "conta_tipo", "%");
$tfi_listunts_contas5->addColumn("unts_contas.conta_descricao", "STRING_TYPE", "conta_descricao", "%");
$tfi_listunts_contas5->addColumn("unts_tesouraria.tesra_id", "NUMERIC_TYPE", "conta_visibilidade", "=");
$tfi_listunts_contas5->addColumn("unts_contas.conta_ativa", "STRING_TYPE", "conta_ativa", "%");
$tfi_listunts_contas5->Execute();

// Sorter
$tso_listunts_contas5 = new TSO_TableSorter("rsunts_contas1", "tso_listunts_contas5");
$tso_listunts_contas5->addColumn("unts_contas.conta_tipo");
$tso_listunts_contas5->addColumn("unts_contas.conta_descricao");
$tso_listunts_contas5->addColumn("unts_tesouraria.tesra_descricao");
$tso_listunts_contas5->addColumn("unts_contas.conta_ativa");
$tso_listunts_contas5->setDefault("unts_contas.conta_tipo");
$tso_listunts_contas5->Execute();

// Navigation
$nav_listunts_contas5 = new NAV_Regular("nav_listunts_contas5", "rsunts_contas1", "../", $_SERVER['PHP_SELF'], 10);

mysql_select_db($database_unitas, $unitas);
$query_Recordset1 = "SELECT tesra_descricao, tesra_id FROM unts_tesouraria ORDER BY tesra_descricao";
$Recordset1 = mysql_query($query_Recordset1, $unitas) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_unitas, $unitas);
$query_Recordset2 = "SELECT comun_razao_social, comun_id FROM unts_comunidade ORDER BY comun_razao_social";
$Recordset2 = mysql_query($query_Recordset2, $unitas) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

//NeXTenesio3 Special List Recordset
$maxRows_rsunts_contas1 = $_SESSION['max_rows_nav_listunts_contas5'];
$pageNum_rsunts_contas1 = 0;
if (isset($_GET['pageNum_rsunts_contas1'])) {
  $pageNum_rsunts_contas1 = $_GET['pageNum_rsunts_contas1'];
}
$startRow_rsunts_contas1 = $pageNum_rsunts_contas1 * $maxRows_rsunts_contas1;

// Defining List Recordset variable
$NXTFilter_rsunts_contas1 = "1=1";
if (isset($_SESSION['filter_tfi_listunts_contas5'])) {
  $NXTFilter_rsunts_contas1 = $_SESSION['filter_tfi_listunts_contas5'];
}
// Defining List Recordset variable
$NXTSort_rsunts_contas1 = "unts_contas.conta_tipo";
if (isset($_SESSION['sorter_tso_listunts_contas5'])) {
  $NXTSort_rsunts_contas1 = $_SESSION['sorter_tso_listunts_contas5'];
}
mysql_select_db($database_unitas, $unitas);

$query_rsunts_contas1 = "SELECT unts_contas.conta_tipo, unts_contas.conta_descricao, unts_tesouraria.tesra_descricao AS conta_visibilidade, unts_contas.conta_ativa, unts_contas.conta_id FROM unts_contas LEFT JOIN unts_tesouraria ON unts_contas.conta_visibilidade = unts_tesouraria.tesra_id WHERE {$NXTFilter_rsunts_contas1} ORDER BY {$NXTSort_rsunts_contas1}";
$query_limit_rsunts_contas1 = sprintf("%s LIMIT %d, %d", $query_rsunts_contas1, $startRow_rsunts_contas1, $maxRows_rsunts_contas1);
$rsunts_contas1 = mysql_query($query_limit_rsunts_contas1, $unitas) or die(mysql_error());
$row_rsunts_contas1 = mysql_fetch_assoc($rsunts_contas1);

if (isset($_GET['totalRows_rsunts_contas1'])) {
  $totalRows_rsunts_contas1 = $_GET['totalRows_rsunts_contas1'];
} else {
  $all_rsunts_contas1 = mysql_query($query_rsunts_contas1);
  $totalRows_rsunts_contas1 = mysql_num_rows($all_rsunts_contas1);
}
$totalPages_rsunts_contas1 = ceil($totalRows_rsunts_contas1/$maxRows_rsunts_contas1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listunts_contas5->checkBoundries();
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
  .KT_col_conta_tipo {width:14px; overflow:hidden;}
  .KT_col_conta_descricao {width:140px; overflow:hidden;}
  .KT_col_conta_visibilidade {width:140px; overflow:hidden;}
  .KT_col_conta_ativa {width:140px; overflow:hidden;}
</style>
</head>
<body>
<div class="KT_tng" id="listunts_contas5">
  <h1> Lista de Contas
    <?php
  $nav_listunts_contas5->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listunts_contas5->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listunts_contas5'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listunts_contas5']; ?>
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
  if (@$_SESSION['has_filter_tfi_listunts_contas5'] == 1) {
?>
          <a href="<?php echo $tfi_listunts_contas5->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listunts_contas5->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="conta_tipo" class="KT_sorter KT_col_conta_tipo <?php echo $tso_listunts_contas5->getSortIcon('unts_contas.conta_tipo'); ?>"> <a href="<?php echo $tso_listunts_contas5->getSortLink('unts_contas.conta_tipo'); ?>">Tipo</a></th>
            <th id="conta_descricao" class="KT_sorter KT_col_conta_descricao <?php echo $tso_listunts_contas5->getSortIcon('unts_contas.conta_descricao'); ?>"> <a href="<?php echo $tso_listunts_contas5->getSortLink('unts_contas.conta_descricao'); ?>">Descriçao</a></th>
            <th id="conta_visibilidade" class="KT_sorter KT_col_conta_visibilidade <?php echo $tso_listunts_contas5->getSortIcon('unts_tesouraria.tesra_descricao'); ?>"> <a href="<?php echo $tso_listunts_contas5->getSortLink('unts_tesouraria.tesra_descricao'); ?>">Visibilidade</a></th>
            <th id="conta_ativa" class="KT_sorter KT_col_conta_ativa <?php echo $tso_listunts_contas5->getSortIcon('unts_contas.conta_ativa'); ?>"> <a href="<?php echo $tso_listunts_contas5->getSortLink('unts_contas.conta_ativa'); ?>">Ativa</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listunts_contas5'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><select name="tfi_listunts_contas5_conta_tipo" id="tfi_listunts_contas5_conta_tipo">
                <option value="R" <?php if (!(strcmp("R", KT_escapeAttribute(@$_SESSION['tfi_listunts_contas5_conta_tipo'])))) {echo "SELECTED";} ?>>Receita</option>
                <option value="D" <?php if (!(strcmp("D", KT_escapeAttribute(@$_SESSION['tfi_listunts_contas5_conta_tipo'])))) {echo "SELECTED";} ?>>Despesa</option>
                <option value="M" <?php if (!(strcmp("M", KT_escapeAttribute(@$_SESSION['tfi_listunts_contas5_conta_tipo'])))) {echo "SELECTED";} ?>>Movimentaçao</option>
              </select></td>
              <td><input type="text" name="tfi_listunts_contas5_conta_descricao" id="tfi_listunts_contas5_conta_descricao" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listunts_contas5_conta_descricao']); ?>" size="20" maxlength="255" /></td>
              <td><select name="tfi_listunts_contas5_conta_visibilidade" id="tfi_listunts_contas5_conta_visibilidade">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listunts_contas5_conta_visibilidade']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset2['comun_id']?>"<?php if (!(strcmp($row_Recordset2['comun_id'], @$_SESSION['tfi_listunts_contas5_conta_visibilidade']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['comun_razao_social']?></option>
                <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
              </select></td>
              <td><select name="tfi_listunts_contas5_conta_ativa" id="tfi_listunts_contas5_conta_ativa">
                <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute(@$_SESSION['tfi_listunts_contas5_conta_ativa'])))) {echo "SELECTED";} ?>>Sim</option>
                <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute(@$_SESSION['tfi_listunts_contas5_conta_ativa'])))) {echo "SELECTED";} ?>>Nao</option>
              </select></td>
              <td><input type="submit" name="tfi_listunts_contas5" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsunts_contas1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsunts_contas1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_unts_contas" class="id_checkbox" value="<?php echo $row_rsunts_contas1['conta_id']; ?>" />
                  <input type="hidden" name="conta_id" class="id_field" value="<?php echo $row_rsunts_contas1['conta_id']; ?>" /></td>
                <td><div class="KT_col_conta_tipo"><?php echo KT_FormatForList($row_rsunts_contas1['conta_tipo'], 2); ?></div></td>
                <td><div class="KT_col_conta_descricao"><?php echo KT_FormatForList($row_rsunts_contas1['conta_descricao'], 20); ?></div></td>
                <td><div class="KT_col_conta_visibilidade"><?php echo KT_FormatForList($row_rsunts_contas1['conta_visibilidade'], 20); ?></div></td>
                <td><div class="KT_col_conta_ativa"><?php echo KT_FormatForList($row_rsunts_contas1['conta_ativa'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="frm_conta.php?conta_id=<?php echo $row_rsunts_contas1['conta_id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsunts_contas1 = mysql_fetch_assoc($rsunts_contas1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listunts_contas5->Prepare();
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
        <a class="KT_additem_op_link" href="frm_conta.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
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

mysql_free_result($rsunts_contas1);
?>
