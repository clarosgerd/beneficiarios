<?php include_once "usuarioinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pruebasaudiologia_grid)) $pruebasaudiologia_grid = new cpruebasaudiologia_grid();

// Page init
$pruebasaudiologia_grid->Page_Init();

// Page main
$pruebasaudiologia_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pruebasaudiologia_grid->Page_Render();
?>
<?php if ($pruebasaudiologia->Export == "") { ?>
<script type="text/javascript">

// Form object
var fpruebasaudiologiagrid = new ew_Form("fpruebasaudiologiagrid", "grid");
fpruebasaudiologiagrid.FormKeyCountName = '<?php echo $pruebasaudiologia_grid->FormKeyCountName ?>';

// Validate form
fpruebasaudiologiagrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fpruebasaudiologiagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_tipopruebasaudiologia", false)) return false;
	if (ew_ValueChanged(fobj, infix, "resultado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "recomendacion", false)) return false;
	return true;
}

// Form_CustomValidate event
fpruebasaudiologiagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpruebasaudiologiagrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpruebasaudiologiagrid.Lists["x_id_tipopruebasaudiologia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipopruebasaudiologia"};
fpruebasaudiologiagrid.Lists["x_id_tipopruebasaudiologia"].Data = "<?php echo $pruebasaudiologia_grid->id_tipopruebasaudiologia->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($pruebasaudiologia->CurrentAction == "gridadd") {
	if ($pruebasaudiologia->CurrentMode == "copy") {
		$bSelectLimit = $pruebasaudiologia_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$pruebasaudiologia_grid->TotalRecs = $pruebasaudiologia->ListRecordCount();
			$pruebasaudiologia_grid->Recordset = $pruebasaudiologia_grid->LoadRecordset($pruebasaudiologia_grid->StartRec-1, $pruebasaudiologia_grid->DisplayRecs);
		} else {
			if ($pruebasaudiologia_grid->Recordset = $pruebasaudiologia_grid->LoadRecordset())
				$pruebasaudiologia_grid->TotalRecs = $pruebasaudiologia_grid->Recordset->RecordCount();
		}
		$pruebasaudiologia_grid->StartRec = 1;
		$pruebasaudiologia_grid->DisplayRecs = $pruebasaudiologia_grid->TotalRecs;
	} else {
		$pruebasaudiologia->CurrentFilter = "0=1";
		$pruebasaudiologia_grid->StartRec = 1;
		$pruebasaudiologia_grid->DisplayRecs = $pruebasaudiologia->GridAddRowCount;
	}
	$pruebasaudiologia_grid->TotalRecs = $pruebasaudiologia_grid->DisplayRecs;
	$pruebasaudiologia_grid->StopRec = $pruebasaudiologia_grid->DisplayRecs;
} else {
	$bSelectLimit = $pruebasaudiologia_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pruebasaudiologia_grid->TotalRecs <= 0)
			$pruebasaudiologia_grid->TotalRecs = $pruebasaudiologia->ListRecordCount();
	} else {
		if (!$pruebasaudiologia_grid->Recordset && ($pruebasaudiologia_grid->Recordset = $pruebasaudiologia_grid->LoadRecordset()))
			$pruebasaudiologia_grid->TotalRecs = $pruebasaudiologia_grid->Recordset->RecordCount();
	}
	$pruebasaudiologia_grid->StartRec = 1;
	$pruebasaudiologia_grid->DisplayRecs = $pruebasaudiologia_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$pruebasaudiologia_grid->Recordset = $pruebasaudiologia_grid->LoadRecordset($pruebasaudiologia_grid->StartRec-1, $pruebasaudiologia_grid->DisplayRecs);

	// Set no record found message
	if ($pruebasaudiologia->CurrentAction == "" && $pruebasaudiologia_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$pruebasaudiologia_grid->setWarningMessage(ew_DeniedMsg());
		if ($pruebasaudiologia_grid->SearchWhere == "0=101")
			$pruebasaudiologia_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pruebasaudiologia_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$pruebasaudiologia_grid->RenderOtherOptions();
?>
<?php $pruebasaudiologia_grid->ShowPageHeader(); ?>
<?php
$pruebasaudiologia_grid->ShowMessage();
?>
<?php if ($pruebasaudiologia_grid->TotalRecs > 0 || $pruebasaudiologia->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($pruebasaudiologia_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> pruebasaudiologia">
<div id="fpruebasaudiologiagrid" class="ewForm ewListForm form-inline">
<?php if ($pruebasaudiologia_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($pruebasaudiologia_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_pruebasaudiologia" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_pruebasaudiologiagrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$pruebasaudiologia_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pruebasaudiologia_grid->RenderListOptions();

// Render list options (header, left)
$pruebasaudiologia_grid->ListOptions->Render("header", "left");
?>
<?php if ($pruebasaudiologia->id_tipopruebasaudiologia->Visible) { // id_tipopruebasaudiologia ?>
	<?php if ($pruebasaudiologia->SortUrl($pruebasaudiologia->id_tipopruebasaudiologia) == "") { ?>
		<th data-name="id_tipopruebasaudiologia" class="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->HeaderCellClass() ?>"><div id="elh_pruebasaudiologia_id_tipopruebasaudiologia" class="pruebasaudiologia_id_tipopruebasaudiologia"><div class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipopruebasaudiologia" class="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->HeaderCellClass() ?>"><div><div id="elh_pruebasaudiologia_id_tipopruebasaudiologia" class="pruebasaudiologia_id_tipopruebasaudiologia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pruebasaudiologia->id_tipopruebasaudiologia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pruebasaudiologia->id_tipopruebasaudiologia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pruebasaudiologia->resultado->Visible) { // resultado ?>
	<?php if ($pruebasaudiologia->SortUrl($pruebasaudiologia->resultado) == "") { ?>
		<th data-name="resultado" class="<?php echo $pruebasaudiologia->resultado->HeaderCellClass() ?>"><div id="elh_pruebasaudiologia_resultado" class="pruebasaudiologia_resultado"><div class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultado" class="<?php echo $pruebasaudiologia->resultado->HeaderCellClass() ?>"><div><div id="elh_pruebasaudiologia_resultado" class="pruebasaudiologia_resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->resultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pruebasaudiologia->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pruebasaudiologia->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pruebasaudiologia->recomendacion->Visible) { // recomendacion ?>
	<?php if ($pruebasaudiologia->SortUrl($pruebasaudiologia->recomendacion) == "") { ?>
		<th data-name="recomendacion" class="<?php echo $pruebasaudiologia->recomendacion->HeaderCellClass() ?>"><div id="elh_pruebasaudiologia_recomendacion" class="pruebasaudiologia_recomendacion"><div class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->recomendacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="recomendacion" class="<?php echo $pruebasaudiologia->recomendacion->HeaderCellClass() ?>"><div><div id="elh_pruebasaudiologia_recomendacion" class="pruebasaudiologia_recomendacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->recomendacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pruebasaudiologia->recomendacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pruebasaudiologia->recomendacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$pruebasaudiologia_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$pruebasaudiologia_grid->StartRec = 1;
$pruebasaudiologia_grid->StopRec = $pruebasaudiologia_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pruebasaudiologia_grid->FormKeyCountName) && ($pruebasaudiologia->CurrentAction == "gridadd" || $pruebasaudiologia->CurrentAction == "gridedit" || $pruebasaudiologia->CurrentAction == "F")) {
		$pruebasaudiologia_grid->KeyCount = $objForm->GetValue($pruebasaudiologia_grid->FormKeyCountName);
		$pruebasaudiologia_grid->StopRec = $pruebasaudiologia_grid->StartRec + $pruebasaudiologia_grid->KeyCount - 1;
	}
}
$pruebasaudiologia_grid->RecCnt = $pruebasaudiologia_grid->StartRec - 1;
if ($pruebasaudiologia_grid->Recordset && !$pruebasaudiologia_grid->Recordset->EOF) {
	$pruebasaudiologia_grid->Recordset->MoveFirst();
	$bSelectLimit = $pruebasaudiologia_grid->UseSelectLimit;
	if (!$bSelectLimit && $pruebasaudiologia_grid->StartRec > 1)
		$pruebasaudiologia_grid->Recordset->Move($pruebasaudiologia_grid->StartRec - 1);
} elseif (!$pruebasaudiologia->AllowAddDeleteRow && $pruebasaudiologia_grid->StopRec == 0) {
	$pruebasaudiologia_grid->StopRec = $pruebasaudiologia->GridAddRowCount;
}

// Initialize aggregate
$pruebasaudiologia->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pruebasaudiologia->ResetAttrs();
$pruebasaudiologia_grid->RenderRow();
if ($pruebasaudiologia->CurrentAction == "gridadd")
	$pruebasaudiologia_grid->RowIndex = 0;
if ($pruebasaudiologia->CurrentAction == "gridedit")
	$pruebasaudiologia_grid->RowIndex = 0;
while ($pruebasaudiologia_grid->RecCnt < $pruebasaudiologia_grid->StopRec) {
	$pruebasaudiologia_grid->RecCnt++;
	if (intval($pruebasaudiologia_grid->RecCnt) >= intval($pruebasaudiologia_grid->StartRec)) {
		$pruebasaudiologia_grid->RowCnt++;
		if ($pruebasaudiologia->CurrentAction == "gridadd" || $pruebasaudiologia->CurrentAction == "gridedit" || $pruebasaudiologia->CurrentAction == "F") {
			$pruebasaudiologia_grid->RowIndex++;
			$objForm->Index = $pruebasaudiologia_grid->RowIndex;
			if ($objForm->HasValue($pruebasaudiologia_grid->FormActionName))
				$pruebasaudiologia_grid->RowAction = strval($objForm->GetValue($pruebasaudiologia_grid->FormActionName));
			elseif ($pruebasaudiologia->CurrentAction == "gridadd")
				$pruebasaudiologia_grid->RowAction = "insert";
			else
				$pruebasaudiologia_grid->RowAction = "";
		}

		// Set up key count
		$pruebasaudiologia_grid->KeyCount = $pruebasaudiologia_grid->RowIndex;

		// Init row class and style
		$pruebasaudiologia->ResetAttrs();
		$pruebasaudiologia->CssClass = "";
		if ($pruebasaudiologia->CurrentAction == "gridadd") {
			if ($pruebasaudiologia->CurrentMode == "copy") {
				$pruebasaudiologia_grid->LoadRowValues($pruebasaudiologia_grid->Recordset); // Load row values
				$pruebasaudiologia_grid->SetRecordKey($pruebasaudiologia_grid->RowOldKey, $pruebasaudiologia_grid->Recordset); // Set old record key
			} else {
				$pruebasaudiologia_grid->LoadRowValues(); // Load default values
				$pruebasaudiologia_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$pruebasaudiologia_grid->LoadRowValues($pruebasaudiologia_grid->Recordset); // Load row values
		}
		$pruebasaudiologia->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pruebasaudiologia->CurrentAction == "gridadd") // Grid add
			$pruebasaudiologia->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pruebasaudiologia->CurrentAction == "gridadd" && $pruebasaudiologia->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pruebasaudiologia_grid->RestoreCurrentRowFormValues($pruebasaudiologia_grid->RowIndex); // Restore form values
		if ($pruebasaudiologia->CurrentAction == "gridedit") { // Grid edit
			if ($pruebasaudiologia->EventCancelled) {
				$pruebasaudiologia_grid->RestoreCurrentRowFormValues($pruebasaudiologia_grid->RowIndex); // Restore form values
			}
			if ($pruebasaudiologia_grid->RowAction == "insert")
				$pruebasaudiologia->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pruebasaudiologia->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pruebasaudiologia->CurrentAction == "gridedit" && ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT || $pruebasaudiologia->RowType == EW_ROWTYPE_ADD) && $pruebasaudiologia->EventCancelled) // Update failed
			$pruebasaudiologia_grid->RestoreCurrentRowFormValues($pruebasaudiologia_grid->RowIndex); // Restore form values
		if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pruebasaudiologia_grid->EditRowCnt++;
		if ($pruebasaudiologia->CurrentAction == "F") // Confirm row
			$pruebasaudiologia_grid->RestoreCurrentRowFormValues($pruebasaudiologia_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$pruebasaudiologia->RowAttrs = array_merge($pruebasaudiologia->RowAttrs, array('data-rowindex'=>$pruebasaudiologia_grid->RowCnt, 'id'=>'r' . $pruebasaudiologia_grid->RowCnt . '_pruebasaudiologia', 'data-rowtype'=>$pruebasaudiologia->RowType));

		// Render row
		$pruebasaudiologia_grid->RenderRow();

		// Render list options
		$pruebasaudiologia_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pruebasaudiologia_grid->RowAction <> "delete" && $pruebasaudiologia_grid->RowAction <> "insertdelete" && !($pruebasaudiologia_grid->RowAction == "insert" && $pruebasaudiologia->CurrentAction == "F" && $pruebasaudiologia_grid->EmptyRow())) {
?>
	<tr<?php echo $pruebasaudiologia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pruebasaudiologia_grid->ListOptions->Render("body", "left", $pruebasaudiologia_grid->RowCnt);
?>
	<?php if ($pruebasaudiologia->id_tipopruebasaudiologia->Visible) { // id_tipopruebasaudiologia ?>
		<td data-name="id_tipopruebasaudiologia"<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->CellAttributes() ?>>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_id_tipopruebasaudiologia" class="form-group pruebasaudiologia_id_tipopruebasaudiologia">
<select data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" data-value-separator="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia"<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->EditAttributes() ?>>
<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->SelectOptionListHtml("x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipopruebasaudiologia") && !$pruebasaudiologia->id_tipopruebasaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia',url:'tipopruebasaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id_tipopruebasaudiologia->OldValue) ?>">
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_id_tipopruebasaudiologia" class="form-group pruebasaudiologia_id_tipopruebasaudiologia">
<select data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" data-value-separator="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia"<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->EditAttributes() ?>>
<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->SelectOptionListHtml("x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipopruebasaudiologia") && !$pruebasaudiologia->id_tipopruebasaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia',url:'tipopruebasaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_id_tipopruebasaudiologia" class="pruebasaudiologia_id_tipopruebasaudiologia">
<span<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->ViewAttributes() ?>>
<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->ListViewValue() ?></span>
</span>
<?php if ($pruebasaudiologia->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id_tipopruebasaudiologia->FormValue) ?>">
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id_tipopruebasaudiologia->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" name="fpruebasaudiologiagrid$x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" id="fpruebasaudiologiagrid$x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id_tipopruebasaudiologia->FormValue) ?>">
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" name="fpruebasaudiologiagrid$o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" id="fpruebasaudiologiagrid$o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id_tipopruebasaudiologia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id->CurrentValue) ?>">
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id->OldValue) ?>">
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT || $pruebasaudiologia->CurrentMode == "edit") { ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($pruebasaudiologia->resultado->Visible) { // resultado ?>
		<td data-name="resultado"<?php echo $pruebasaudiologia->resultado->CellAttributes() ?>>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_resultado" class="form-group pruebasaudiologia_resultado">
<input type="text" data-table="pruebasaudiologia" data-field="x_resultado" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->resultado->EditValue ?>"<?php echo $pruebasaudiologia->resultado->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_resultado" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->OldValue) ?>">
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_resultado" class="form-group pruebasaudiologia_resultado">
<input type="text" data-table="pruebasaudiologia" data-field="x_resultado" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->resultado->EditValue ?>"<?php echo $pruebasaudiologia->resultado->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_resultado" class="pruebasaudiologia_resultado">
<span<?php echo $pruebasaudiologia->resultado->ViewAttributes() ?>>
<?php echo $pruebasaudiologia->resultado->ListViewValue() ?></span>
</span>
<?php if ($pruebasaudiologia->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_resultado" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->FormValue) ?>">
<input type="hidden" data-table="pruebasaudiologia" data-field="x_resultado" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_resultado" name="fpruebasaudiologiagrid$x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="fpruebasaudiologiagrid$x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->FormValue) ?>">
<input type="hidden" data-table="pruebasaudiologia" data-field="x_resultado" name="fpruebasaudiologiagrid$o<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="fpruebasaudiologiagrid$o<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pruebasaudiologia->recomendacion->Visible) { // recomendacion ?>
		<td data-name="recomendacion"<?php echo $pruebasaudiologia->recomendacion->CellAttributes() ?>>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_recomendacion" class="form-group pruebasaudiologia_recomendacion">
<input type="text" data-table="pruebasaudiologia" data-field="x_recomendacion" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->recomendacion->EditValue ?>"<?php echo $pruebasaudiologia->recomendacion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_recomendacion" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" value="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->OldValue) ?>">
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_recomendacion" class="form-group pruebasaudiologia_recomendacion">
<input type="text" data-table="pruebasaudiologia" data-field="x_recomendacion" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->recomendacion->EditValue ?>"<?php echo $pruebasaudiologia->recomendacion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pruebasaudiologia_grid->RowCnt ?>_pruebasaudiologia_recomendacion" class="pruebasaudiologia_recomendacion">
<span<?php echo $pruebasaudiologia->recomendacion->ViewAttributes() ?>>
<?php echo $pruebasaudiologia->recomendacion->ListViewValue() ?></span>
</span>
<?php if ($pruebasaudiologia->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_recomendacion" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" value="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->FormValue) ?>">
<input type="hidden" data-table="pruebasaudiologia" data-field="x_recomendacion" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" value="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_recomendacion" name="fpruebasaudiologiagrid$x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="fpruebasaudiologiagrid$x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" value="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->FormValue) ?>">
<input type="hidden" data-table="pruebasaudiologia" data-field="x_recomendacion" name="fpruebasaudiologiagrid$o<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="fpruebasaudiologiagrid$o<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" value="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pruebasaudiologia_grid->ListOptions->Render("body", "right", $pruebasaudiologia_grid->RowCnt);
?>
	</tr>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_ADD || $pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpruebasaudiologiagrid.UpdateOpts(<?php echo $pruebasaudiologia_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pruebasaudiologia->CurrentAction <> "gridadd" || $pruebasaudiologia->CurrentMode == "copy")
		if (!$pruebasaudiologia_grid->Recordset->EOF) $pruebasaudiologia_grid->Recordset->MoveNext();
}
?>
<?php
	if ($pruebasaudiologia->CurrentMode == "add" || $pruebasaudiologia->CurrentMode == "copy" || $pruebasaudiologia->CurrentMode == "edit") {
		$pruebasaudiologia_grid->RowIndex = '$rowindex$';
		$pruebasaudiologia_grid->LoadRowValues();

		// Set row properties
		$pruebasaudiologia->ResetAttrs();
		$pruebasaudiologia->RowAttrs = array_merge($pruebasaudiologia->RowAttrs, array('data-rowindex'=>$pruebasaudiologia_grid->RowIndex, 'id'=>'r0_pruebasaudiologia', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pruebasaudiologia->RowAttrs["class"], "ewTemplate");
		$pruebasaudiologia->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pruebasaudiologia_grid->RenderRow();

		// Render list options
		$pruebasaudiologia_grid->RenderListOptions();
		$pruebasaudiologia_grid->StartRowCnt = 0;
?>
	<tr<?php echo $pruebasaudiologia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pruebasaudiologia_grid->ListOptions->Render("body", "left", $pruebasaudiologia_grid->RowIndex);
?>
	<?php if ($pruebasaudiologia->id_tipopruebasaudiologia->Visible) { // id_tipopruebasaudiologia ?>
		<td data-name="id_tipopruebasaudiologia">
<?php if ($pruebasaudiologia->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pruebasaudiologia_id_tipopruebasaudiologia" class="form-group pruebasaudiologia_id_tipopruebasaudiologia">
<select data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" data-value-separator="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia"<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->EditAttributes() ?>>
<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->SelectOptionListHtml("x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipopruebasaudiologia") && !$pruebasaudiologia->id_tipopruebasaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia',url:'tipopruebasaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_pruebasaudiologia_id_tipopruebasaudiologia" class="form-group pruebasaudiologia_id_tipopruebasaudiologia">
<span<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pruebasaudiologia->id_tipopruebasaudiologia->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id_tipopruebasaudiologia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_id_tipopruebasaudiologia" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id_tipopruebasaudiologia->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pruebasaudiologia->resultado->Visible) { // resultado ?>
		<td data-name="resultado">
<?php if ($pruebasaudiologia->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pruebasaudiologia_resultado" class="form-group pruebasaudiologia_resultado">
<input type="text" data-table="pruebasaudiologia" data-field="x_resultado" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->resultado->EditValue ?>"<?php echo $pruebasaudiologia->resultado->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pruebasaudiologia_resultado" class="form-group pruebasaudiologia_resultado">
<span<?php echo $pruebasaudiologia->resultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pruebasaudiologia->resultado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_resultado" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_resultado" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pruebasaudiologia->recomendacion->Visible) { // recomendacion ?>
		<td data-name="recomendacion">
<?php if ($pruebasaudiologia->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pruebasaudiologia_recomendacion" class="form-group pruebasaudiologia_recomendacion">
<input type="text" data-table="pruebasaudiologia" data-field="x_recomendacion" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->recomendacion->EditValue ?>"<?php echo $pruebasaudiologia->recomendacion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pruebasaudiologia_recomendacion" class="form-group pruebasaudiologia_recomendacion">
<span<?php echo $pruebasaudiologia->recomendacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pruebasaudiologia->recomendacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_recomendacion" name="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="x<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" value="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_recomendacion" name="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" id="o<?php echo $pruebasaudiologia_grid->RowIndex ?>_recomendacion" value="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pruebasaudiologia_grid->ListOptions->Render("body", "right", $pruebasaudiologia_grid->RowIndex);
?>
<script type="text/javascript">
fpruebasaudiologiagrid.UpdateOpts(<?php echo $pruebasaudiologia_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($pruebasaudiologia->CurrentMode == "add" || $pruebasaudiologia->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pruebasaudiologia_grid->FormKeyCountName ?>" id="<?php echo $pruebasaudiologia_grid->FormKeyCountName ?>" value="<?php echo $pruebasaudiologia_grid->KeyCount ?>">
<?php echo $pruebasaudiologia_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pruebasaudiologia->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pruebasaudiologia_grid->FormKeyCountName ?>" id="<?php echo $pruebasaudiologia_grid->FormKeyCountName ?>" value="<?php echo $pruebasaudiologia_grid->KeyCount ?>">
<?php echo $pruebasaudiologia_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pruebasaudiologia->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpruebasaudiologiagrid">
</div>
<?php

// Close recordset
if ($pruebasaudiologia_grid->Recordset)
	$pruebasaudiologia_grid->Recordset->Close();
?>
<?php if ($pruebasaudiologia_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($pruebasaudiologia_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($pruebasaudiologia_grid->TotalRecs == 0 && $pruebasaudiologia->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pruebasaudiologia_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pruebasaudiologia->Export == "") { ?>
<script type="text/javascript">
fpruebasaudiologiagrid.Init();
</script>
<?php } ?>
<?php
$pruebasaudiologia_grid->Page_Terminate();
?>
