<?php require_once('../Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("lst_codigo", true, "numeric", "", "", "", "");
$formValidation->addField("lst_nome", true, "text", "", "", "", "");
$formValidation->addField("lst_foto", true, "", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileUpload(&$tNG) {
  $uploadObj = new tNG_FileUpload($tNG);
  $uploadObj->setFormFieldName("lst_foto");
  $uploadObj->setDbFieldName("lst_foto");
  $uploadObj->setFolder("../lista/images/");
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("jpg, png, bmp");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_FileUpload trigger

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

mysql_select_db($database_unitas, $unitas);
$query_rs_lista = "SELECT * FROM unts_lista ORDER BY unts_lista.lst_nome";
$rs_lista = mysql_query($query_rs_lista, $unitas) or die(mysql_error());
$row_rs_lista = mysql_fetch_assoc($rs_lista);
$totalRows_rs_lista = mysql_num_rows($rs_lista);

// Make an insert transaction instance
$ins_unts_lista = new tNG_insert($conn_unitas);
$tNGs->addTransaction($ins_unts_lista);
// Register triggers
$ins_unts_lista->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_lista->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_lista->registerTrigger("AFTER", "Trigger_FileUpload", 97);
// Add columns
$ins_unts_lista->setTable("unts_lista");
$ins_unts_lista->addColumn("lst_codigo", "NUMERIC_TYPE", "POST", "lst_codigo");
$ins_unts_lista->addColumn("lst_nome", "STRING_TYPE", "POST", "lst_nome");
$ins_unts_lista->addColumn("lst_foto", "FILE_TYPE", "FILES", "lst_foto");
$ins_unts_lista->setPrimaryKey("lst_id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_lista = $tNGs->getRecordset("unts_lista");
$row_rsunts_lista = mysql_fetch_assoc($rsunts_lista);
$totalRows_rsunts_lista = mysql_num_rows($rsunts_lista);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
</head>

<body>
<h1>Cadastro de Fotos</h1>
<p>
  <?php
	echo $tNGs->getErrorMsg();
?>
</p>
<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
    <tr>
      <td class="KT_th"><label for="lst_codigo">Código do Dizimista:</label></td>
      <td><input name="lst_codigo" type="text" id="lst_codigo" value="<?php echo KT_escapeAttribute($row_rsunts_lista['lst_codigo']); ?>" size="4" maxlength="4" />
      <?php echo $tNGs->displayFieldHint("lst_codigo");?> <?php echo $tNGs->displayFieldError("unts_lista", "lst_codigo"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="lst_nome">Nome:</label></td>
      <td><input type="text" name="lst_nome" id="lst_nome" value="<?php echo KT_escapeAttribute($row_rsunts_lista['lst_nome']); ?>" size="130" />
      <?php echo $tNGs->displayFieldHint("lst_nome");?> <?php echo $tNGs->displayFieldError("unts_lista", "lst_nome"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="lst_foto">Foto:</label></td>
      <td><input type="file" name="lst_foto" id="lst_foto" size="32" />
        <?php echo $tNGs->displayFieldError("unts_lista", "lst_foto"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td colspan="2"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="Insert record" /></td>
    </tr>
  </table>
</form>
<h2>Lista de Dizimistas Cadastrados</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>N&ordm;</strong></td>
    <td><strong>Nome</strong></td>
    <td><strong>C&oacute;digo</strong></td>
    <td><strong>Foto</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td valign="top"><?php $cont = $cont + 1; echo $cont;?></td>
      <td valign="top"><a href="editar_dizimista.php?lst_id=<?php echo $row_rs_lista['lst_id']; ?>"><?php echo $row_rs_lista['lst_nome']; ?></a></td>
      <td valign="top"><?php echo $row_rs_lista['lst_codigo']; ?></td>
      <td valign="top"><div id="imagem" style="padding: 3px 3px 3px 3px;"><img src="images/<?php echo $row_rs_lista['lst_foto']; ?>" alt="imagem dizimista" width="75" height="77" /></div></td>
    </tr>
    <?php } while ($row_rs_lista = mysql_fetch_assoc($rs_lista)); ?>
</table>

</body>
</html>
<?php
mysql_free_result($rs_lista);
?>
