<?php require_once('../Connections/unitas.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_unitas = new KT_connection($unitas, $database_unitas);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("comun_sigla", true, "text", "", "", "", "");
$formValidation->addField("comun_razao_social", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../images/tesourarias_logo/{comun_id}/{comun_icone}/");
  $deleteObj->setDbFieldName("comun_icone");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_FileUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileUpload(&$tNG) {
  $uploadObj = new tNG_FileUpload($tNG);
  $uploadObj->setFormFieldName("comun_icone");
  $uploadObj->setDbFieldName("comun_icone");
  $uploadObj->setFolder("../images/tesourarias_logo/{comun_id}/");
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("bmp, jpg, png");
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
$query_rs_municipio = "SELECT * FROM unts_municipio ORDER BY unts_municipio.munic_descricao";
$rs_municipio = mysql_query($query_rs_municipio, $unitas) or die(mysql_error());
$row_rs_municipio = mysql_fetch_assoc($rs_municipio);
$totalRows_rs_municipio = mysql_num_rows($rs_municipio);

mysql_select_db($database_unitas, $unitas);
$query_rs_estado = "SELECT * FROM unts_uf ORDER BY unts_uf.uf_descricao";
$rs_estado = mysql_query($query_rs_estado, $unitas) or die(mysql_error());
$row_rs_estado = mysql_fetch_assoc($rs_estado);
$totalRows_rs_estado = mysql_num_rows($rs_estado);

// Make an insert transaction instance
$ins_unts_comunidade = new tNG_multipleInsert($conn_unitas);
$tNGs->addTransaction($ins_unts_comunidade);
// Register triggers
$ins_unts_comunidade->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_unts_comunidade->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_unts_comunidade->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_unts_comunidade->registerTrigger("AFTER", "Trigger_FileUpload", 97);
// Add columns
$ins_unts_comunidade->setTable("unts_comunidade");
$ins_unts_comunidade->addColumn("comun_sigla", "STRING_TYPE", "POST", "comun_sigla");
$ins_unts_comunidade->addColumn("comun_razao_social", "STRING_TYPE", "POST", "comun_razao_social");
$ins_unts_comunidade->addColumn("comun_icone", "FILE_TYPE", "FILES", "comun_icone");
$ins_unts_comunidade->addColumn("comun_logradouro", "STRING_TYPE", "POST", "comun_logradouro");
$ins_unts_comunidade->addColumn("comun_numero", "STRING_TYPE", "POST", "comun_numero", "s/no");
$ins_unts_comunidade->addColumn("comun_complemento", "STRING_TYPE", "POST", "comun_complemento", "sem complemento");
$ins_unts_comunidade->addColumn("comun_bairro", "STRING_TYPE", "POST", "comun_bairro");
$ins_unts_comunidade->addColumn("comun_municipio", "STRING_TYPE", "POST", "comun_municipio");
$ins_unts_comunidade->addColumn("comun_estado", "STRING_TYPE", "POST", "comun_estado");
$ins_unts_comunidade->addColumn("comun_cep", "STRING_TYPE", "POST", "comun_cep");
$ins_unts_comunidade->addColumn("comun_telefone", "STRING_TYPE", "POST", "comun_telefone");
$ins_unts_comunidade->addColumn("comun_fax", "STRING_TYPE", "POST", "comun_fax");
$ins_unts_comunidade->addColumn("comun_email", "STRING_TYPE", "POST", "comun_email");
$ins_unts_comunidade->addColumn("comun_site", "STRING_TYPE", "POST", "comun_site");
$ins_unts_comunidade->addColumn("comun_ativo", "STRING_TYPE", "POST", "comun_ativo");
$ins_unts_comunidade->setPrimaryKey("comun_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_unts_comunidade = new tNG_multipleUpdate($conn_unitas);
$tNGs->addTransaction($upd_unts_comunidade);
// Register triggers
$upd_unts_comunidade->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_unts_comunidade->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_unts_comunidade->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_unts_comunidade->registerTrigger("AFTER", "Trigger_FileUpload", 97);
// Add columns
$upd_unts_comunidade->setTable("unts_comunidade");
$upd_unts_comunidade->addColumn("comun_sigla", "STRING_TYPE", "POST", "comun_sigla");
$upd_unts_comunidade->addColumn("comun_razao_social", "STRING_TYPE", "POST", "comun_razao_social");
$upd_unts_comunidade->addColumn("comun_icone", "FILE_TYPE", "FILES", "comun_icone");
$upd_unts_comunidade->addColumn("comun_logradouro", "STRING_TYPE", "POST", "comun_logradouro");
$upd_unts_comunidade->addColumn("comun_numero", "STRING_TYPE", "POST", "comun_numero");
$upd_unts_comunidade->addColumn("comun_complemento", "STRING_TYPE", "POST", "comun_complemento");
$upd_unts_comunidade->addColumn("comun_bairro", "STRING_TYPE", "POST", "comun_bairro");
$upd_unts_comunidade->addColumn("comun_municipio", "STRING_TYPE", "POST", "comun_municipio");
$upd_unts_comunidade->addColumn("comun_estado", "STRING_TYPE", "POST", "comun_estado");
$upd_unts_comunidade->addColumn("comun_cep", "STRING_TYPE", "POST", "comun_cep");
$upd_unts_comunidade->addColumn("comun_telefone", "STRING_TYPE", "POST", "comun_telefone");
$upd_unts_comunidade->addColumn("comun_fax", "STRING_TYPE", "POST", "comun_fax");
$upd_unts_comunidade->addColumn("comun_email", "STRING_TYPE", "POST", "comun_email");
$upd_unts_comunidade->addColumn("comun_site", "STRING_TYPE", "POST", "comun_site");
$upd_unts_comunidade->addColumn("comun_ativo", "STRING_TYPE", "POST", "comun_ativo");
$upd_unts_comunidade->setPrimaryKey("comun_id", "NUMERIC_TYPE", "GET", "comun_id");

// Make an instance of the transaction object
$del_unts_comunidade = new tNG_multipleDelete($conn_unitas);
$tNGs->addTransaction($del_unts_comunidade);
// Register triggers
$del_unts_comunidade->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_unts_comunidade->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_unts_comunidade->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_unts_comunidade->setTable("unts_comunidade");
$del_unts_comunidade->setPrimaryKey("comun_id", "NUMERIC_TYPE", "GET", "comun_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsunts_comunidade = $tNGs->getRecordset("unts_comunidade");
$row_rsunts_comunidade = mysql_fetch_assoc($rsunts_comunidade);
$totalRows_rsunts_comunidade = mysql_num_rows($rsunts_comunidade);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>

<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: true,
  show_as_grid: true,
  merge_down_value: true
}
</script>
</head>

<body>
<p>&nbsp;
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['comun_id'] == "") {
?>
    <?php echo NXT_getResource("Insert_FH"); ?>
    <?php 
// else Conditional region1
} else { ?>
    <?php echo NXT_getResource("Update_FH"); ?>
    <?php } 
// endif Conditional region1
?>
  Comunidade </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
      <?php $cnt1++; ?>
      <?php 
// Show IF Conditional region1 
if (@$totalRows_rsunts_comunidade > 1) {
?>
      <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
      <?php } 
// endif Conditional region1
?>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <tr>
          <td class="KT_th"><label for="comun_sigla_<?php echo $cnt1; ?>">Sigla:</label></td>
          <td><input type="text" name="comun_sigla_<?php echo $cnt1; ?>" id="comun_sigla_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_sigla']); ?>" size="10" maxlength="10" />
            <?php echo $tNGs->displayFieldHint("comun_sigla");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_sigla", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_razao_social_<?php echo $cnt1; ?>">Razao Social:</label></td>
          <td><input type="text" name="comun_razao_social_<?php echo $cnt1; ?>" id="comun_razao_social_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_razao_social']); ?>" size="32" maxlength="255" />
            <?php echo $tNGs->displayFieldHint("comun_razao_social");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_razao_social", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_icone_<?php echo $cnt1; ?>">Logomarca:</label></td>
          <td><input type="file" name="comun_icone_<?php echo $cnt1; ?>" id="comun_icone_<?php echo $cnt1; ?>" size="32" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_icone']); ?>" />
            <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_icone", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_logradouro_<?php echo $cnt1; ?>">Logradouro:</label></td>
          <td><input type="text" name="comun_logradouro_<?php echo $cnt1; ?>" id="comun_logradouro_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_logradouro']); ?>" size="32" maxlength="255" />
            <?php echo $tNGs->displayFieldHint("comun_logradouro");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_logradouro", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_numero_<?php echo $cnt1; ?>">Número:</label></td>
          <td><input type="text" name="comun_numero_<?php echo $cnt1; ?>" id="comun_numero_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_numero']); ?>" size="10" maxlength="10" />
            <?php echo $tNGs->displayFieldHint("comun_numero");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_numero", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_complemento_<?php echo $cnt1; ?>">Complemento:</label></td>
          <td><input type="text" name="comun_complemento_<?php echo $cnt1; ?>" id="comun_complemento_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_complemento']); ?>" size="30" maxlength="30" />
            <?php echo $tNGs->displayFieldHint("comun_complemento");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_complemento", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_bairro_<?php echo $cnt1; ?>">Bairro:</label></td>
          <td><input type="text" name="comun_bairro_<?php echo $cnt1; ?>" id="comun_bairro_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_bairro']); ?>" size="32" maxlength="120" />
            <?php echo $tNGs->displayFieldHint("comun_bairro");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_bairro", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_municipio_<?php echo $cnt1; ?>">Município:</label></td>
          <td><select name="comun_municipio_<?php echo $cnt1; ?>" id="comun_municipio_<?php echo $cnt1; ?>">
            <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
            <?php 
do {  
?>
            <option value="<?php echo $row_rs_municipio['munic_id']?>"<?php if (!(strcmp($row_rs_municipio['munic_id'], $row_rsunts_comunidade['comun_municipio']))) {echo "SELECTED";} ?>><?php echo $row_rs_municipio['munic_descricao']?></option>
            <?php
} while ($row_rs_municipio = mysql_fetch_assoc($rs_municipio));
  $rows = mysql_num_rows($rs_municipio);
  if($rows > 0) {
      mysql_data_seek($rs_municipio, 0);
	  $row_rs_municipio = mysql_fetch_assoc($rs_municipio);
  }
?>
          </select>
            <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_municipio", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_estado_<?php echo $cnt1; ?>">Estado:</label></td>
          <td><select name="comun_estado_<?php echo $cnt1; ?>" id="comun_estado_<?php echo $cnt1; ?>">
            <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
            <?php 
do {  
?>
            <option value="<?php echo $row_rs_estado['uf_id']?>"<?php if (!(strcmp($row_rs_estado['uf_id'], $row_rsunts_comunidade['comun_estado']))) {echo "SELECTED";} ?>><?php echo $row_rs_estado['uf_descricao']?></option>
            <?php
} while ($row_rs_estado = mysql_fetch_assoc($rs_estado));
  $rows = mysql_num_rows($rs_estado);
  if($rows > 0) {
      mysql_data_seek($rs_estado, 0);
	  $row_rs_estado = mysql_fetch_assoc($rs_estado);
  }
?>
          </select>
            <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_estado", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_cep_<?php echo $cnt1; ?>">CEP:</label></td>
          <td><input type="text" name="comun_cep_<?php echo $cnt1; ?>" id="comun_cep_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_cep']); ?>" size="10" maxlength="10" />
            <?php echo $tNGs->displayFieldHint("comun_cep");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_cep", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_telefone_<?php echo $cnt1; ?>">Telefone:</label></td>
          <td><input type="text" name="comun_telefone_<?php echo $cnt1; ?>" id="comun_telefone_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_telefone']); ?>" size="13" maxlength="13" />
            <?php echo $tNGs->displayFieldHint("comun_telefone");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_telefone", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_fax_<?php echo $cnt1; ?>">Fax:</label></td>
          <td><input type="text" name="comun_fax_<?php echo $cnt1; ?>" id="comun_fax_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_fax']); ?>" size="13" maxlength="13" />
            <?php echo $tNGs->displayFieldHint("comun_fax");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_fax", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_email_<?php echo $cnt1; ?>">E-mail:</label></td>
          <td><input type="text" name="comun_email_<?php echo $cnt1; ?>" id="comun_email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_email']); ?>" size="32" maxlength="255" />
            <?php echo $tNGs->displayFieldHint("comun_email");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_email", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_site_<?php echo $cnt1; ?>">Site:</label></td>
          <td><input type="text" name="comun_site_<?php echo $cnt1; ?>" id="comun_site_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['comun_site']); ?>" size="32" maxlength="255" />
            <?php echo $tNGs->displayFieldHint("comun_site");?> <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_site", $cnt1); ?></td>
        </tr>
        <tr>
          <td class="KT_th"><label for="comun_ativo_<?php echo $cnt1; ?>">Ativo:</label></td>
          <td><select name="comun_ativo_<?php echo $cnt1; ?>" id="comun_ativo_<?php echo $cnt1; ?>">
            <option value="S" <?php if (!(strcmp("S", KT_escapeAttribute($row_rsunts_comunidade['comun_ativo'])))) {echo "SELECTED";} ?>>Sim</option>
            <option value="N" <?php if (!(strcmp("N", KT_escapeAttribute($row_rsunts_comunidade['comun_ativo'])))) {echo "SELECTED";} ?>>Nao</option>
          </select>
            <?php echo $tNGs->displayFieldError("unts_comunidade", "comun_ativo", $cnt1); ?></td>
        </tr>
      </table>
      <input type="hidden" name="kt_pk_unts_comunidade_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsunts_comunidade['kt_pk_unts_comunidade']); ?>" />
      <?php } while ($row_rsunts_comunidade = mysql_fetch_assoc($rsunts_comunidade)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['comun_id'] == "") {
      ?>
          <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
          <?php 
      // else Conditional region1
      } else { ?>
          <div class="KT_operations">
            <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'comun_id')" />
          </div>
          <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
          <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
          <?php }
      // endif Conditional region1
      ?>
          <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
        </div>
      </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</p>
</body>
</html>
<?php
mysql_free_result($rs_municipio);

mysql_free_result($rs_estado);
?>
