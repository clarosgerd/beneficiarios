<?php include_once "usuarioinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($diagnosticoaudiologia_grid)) $diagnosticoaudiologia_grid = new cdiagnosticoaudiologia_grid();

// Page init
$diagnosticoaudiologia_grid->Page_Init();

// Page main
$diagnosticoaudiologia_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$diagnosticoaudiologia_grid->Page_Render();
?>
<?php if ($diagnosticoaudiologia->Export == "") { ?>
<script type="text/javascript">

// Form object
var fdiagnosticoaudiologiagrid = new ew_Form("fdiagnosticoaudiologiagrid", "grid");
fdiagnosticoaudiologiagrid.FormKeyCountName = '<?php echo $diagnosticoaudiologia_grid->FormKeyCountName ?>';

// Validate form
fdiagnosticoaudiologiagrid.Validate = function() {
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
fdiagnosticoaudiologiagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_tipodiagnosticoaudiologia", false)) return false;
	if (ew_ValueChanged(fobj, infix, "resultado", false)) return false;
	return true;
}

// Form_CustomValidate event
fdiagnosticoaudiologiagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdiagnosticoaudiologiagrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdiagnosticoaudiologiagrid.Lists["x_id_tipodiagnosticoaudiologia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiagnosticoaudiologia"};
fdiagnosticoaudiologiagrid.Lists["x_id_tipodiagnosticoaudiologia"].Data = "<?php echo $diagnosticoaudiologia_grid->id_tipodiagnosticoaudiologia->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($diagnosticoaudiologia->CurrentAction == "gridadd") {
	if ($diagnosticoaudiologia->CurrentMode == "copy") {
		$bSelectLimit = $diagnosticoaudiologia_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$diagnosticoaudiologia_grid->TotalRecs = $diagnosticoaudiologia->ListRecordCount();
			$diagnosticoaudiologia_grid->Recordset = $diagnosticoaudiologia_grid->LoadRecordset($diagnosticoaudiologia_grid->StartRec-1, $diagnosticoaudiologia_grid->DisplayRecs);
		} else {
			if ($diagnosticoaudiologia_grid->Recordset = $diagnosticoaudiologia_grid->LoadRecordset())
				$diagnosticoaudiologia_grid->TotalRecs = $diagnosticoaudiologia_grid->Recordset->RecordCount();
		}
		$diagnosticoaudiologia_grid->StartRec = 1;
		$diagnosticoaudiologia_grid->DisplayRecs = $diagnosticoaudiologia_grid->TotalRecs;
	} else {
		$diagnosticoaudiologia->CurrentFilter = "0=1";
		$diagnosticoaudiologia_grid->StartRec = 1;
		$diagnosticoaudiologia_grid->DisplayRecs = $diagnosticoaudiologia->GridAddRowCount;
	}
	$diagnosticoaudiologia_grid->TotalRecs = $diagnosticoaudiologia_grid->DisplayRecs;
	$diagnosticoaudiologia_grid->StopRec = $diagnosticoaudiologia_grid->DisplayRecs;
} else {
	$bSelectLimit = $diagnosticoaudiologia_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($diagnosticoaudiologia_grid->TotalRecs <= 0)
			$diagnosticoaudiologia_grid->TotalRecs = $diagnosticoaudiologia->ListRecordCount();
	} else {
		if (!$diagnosticoaudiologia_grid->Recordset && ($diagnosticoaudiologia_grid->Recordset = $diagnosticoaudiologia_grid->LoadRecordset()))
			$diagnosticoaudiologia_grid->TotalRecs = $diagnosticoaudiologia_grid->Recordset->RecordCount();
	}
	$diagnosticoaudiologia_grid->StartRec = 1;
	$diagnosticoaudiologia_grid->DisplayRecs = $diagnosticoaudiologia_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$diagnosticoaudiologia_grid->Recordset = $diagnosticoaudiologia_grid->LoadRecordset($diagnosticoaudiologia_grid->StartRec-1, $diagnosticoaudiologia_grid->DisplayRecs);

	// Set no record found message
	if ($diagnosticoaudiologia->CurrentAction == "" && $diagnosticoaudiologia_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$diagnosticoaudiologia_grid->setWarningMessage(ew_DeniedMsg());
		if ($diagnosticoaudiologia_grid->SearchWhere == "0=101")
			$diagnosticoaudiologia_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$diagnosticoaudiologia_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$diagnosticoaudiologia_grid->RenderOtherOptions();
?>
<?php $diagnosticoaudiologia_grid->ShowPageHeader(); ?>
<?php
$diagnosticoaudiologia_grid->ShowMessage();
?>
<?php if ($diagnosticoaudiologia_grid->TotalRecs > 0 || $diagnosticoaudiologia->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($diagnosticoaudiologia_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> diagnosticoaudiologia">
<div id="fdiagnosticoaudiologiagrid" class="ewForm ewListForm form-inline">
<?php if ($diagnosticoaudiologia_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($diagnosticoaudiologia_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_diagnosticoaudiologia" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_diagnosticoaudiologiagrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$diagnosticoaudiologia_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$diagnosticoaudiologia_grid->RenderListOptions();

// Render list options (header, left)
$diagnosticoaudiologia_grid->ListOptions->Render("header", "left");
?>
<?php if ($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->Visible) { // id_tipodiagnosticoaudiologia ?>
	<?php if ($diagnosticoaudiologia->SortUrl($diagnosticoaudiologia->id_tipodiagnosticoaudiologia) == "") { ?>
		<th data-name="id_tipodiagnosticoaudiologia" class="<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->HeaderCellClass() ?>"><div id="elh_diagnosticoaudiologia_id_tipodiagnosticoaudiologia" class="diagnosticoaudiologia_id_tipodiagnosticoaudiologia"><div class="ewTableHeaderCaption"><?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipodiagnosticoaudiologia" class="<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->HeaderCellClass() ?>"><div><div id="elh_diagnosticoaudiologia_id_tipodiagnosticoaudiologia" class="diagnosticoaudiologia_id_tipodiagnosticoaudiologia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($diagnosticoaudiologia->resultado->Visible) { // resultado ?>
	<?php if ($diagnosticoaudiologia->SortUrl($diagnosticoaudiologia->resultado) == "") { ?>
		<th data-name="resultado" class="<?php echo $diagnosticoaudiologia->resultado->HeaderCellClass() ?>"><div id="elh_diagnosticoaudiologia_resultado" class="diagnosticoaudiologia_resultado"><div class="ewTableHeaderCaption"><?php echo $diagnosticoaudiologia->resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultado" class="<?php echo $diagnosticoaudiologia->resultado->HeaderCellClass() ?>"><div><div id="elh_diagnosticoaudiologia_resultado" class="diagnosticoaudiologia_resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $diagnosticoaudiologia->resultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($diagnosticoaudiologia->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($diagnosticoaudiologia->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$diagnosticoaudiologia_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$diagnosticoaudiologia_grid->StartRec = 1;
$diagnosticoaudiologia_grid->StopRec = $diagnosticoaudiologia_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($diagnosticoaudiologia_grid->FormKeyCountName) && ($diagnosticoaudiologia->CurrentAction == "gridadd" || $diagnosticoaudiologia->CurrentAction == "gridedit" || $diagnosticoaudiologia->CurrentAction == "F")) {
		$diagnosticoaudiologia_grid->KeyCount = $objForm->GetValue($diagnosticoaudiologia_grid->FormKeyCountName);
		$diagnosticoaudiologia_grid->StopRec = $diagnosticoaudiologia_grid->StartRec + $diagnosticoaudiologia_grid->KeyCount - 1;
	}
}
$diagnosticoaudiologia_grid->RecCnt = $diagnosticoaudiologia_grid->StartRec - 1;
if ($diagnosticoaudiologia_grid->Recordset && !$diagnosticoaudiologia_grid->Recordset->EOF) {
	$diagnosticoaudiologia_grid->Recordset->MoveFirst();
	$bSelectLimit = $diagnosticoaudiologia_grid->UseSelectLimit;
	if (!$bSelectLimit && $diagnosticoaudiologia_grid->StartRec > 1)
		$diagnosticoaudiologia_grid->Recordset->Move($diagnosticoaudiologia_grid->StartRec - 1);
} elseif (!$diagnosticoaudiologia->AllowAddDeleteRow && $diagnosticoaudiologia_grid->StopRec == 0) {
	$diagnosticoaudiologia_grid->StopRec = $diagnosticoaudiologia->GridAddRowCount;
}

// Initialize aggregate
$diagnosticoaudiologia->RowType = EW_ROWTYPE_AGGREGATEINIT;
$diagnosticoaudiologia->ResetAttrs();
$diagnosticoaudiologia_grid->RenderRow();
if ($diagnosticoaudiologia->CurrentAction == "gridadd")
	$diagnosticoaudiologia_grid->RowIndex = 0;
if ($diagnosticoaudiologia->CurrentAction == "gridedit")
	$diagnosticoaudiologia_grid->RowIndex = 0;
while ($diagnosticoaudiologia_grid->RecCnt < $diagnosticoaudiologia_grid->StopRec) {
	$diagnosticoaudiologia_grid->RecCnt++;
	if (intval($diagnosticoaudiologia_grid->RecCnt) >= intval($diagnosticoaudiologia_grid->StartRec)) {
		$diagnosticoaudiologia_grid->RowCnt++;
		if ($diagnosticoaudiologia->CurrentAction == "gridadd" || $diagnosticoaudiologia->CurrentAction == "gridedit" || $diagnosticoaudiologia->CurrentAction == "F") {
			$diagnosticoaudiologia_grid->RowIndex++;
			$objForm->Index = $diagnosticoaudiologia_grid->RowIndex;
			if ($objForm->HasValue($diagnosticoaudiologia_grid->FormActionName))
				$diagnosticoaudiologia_grid->RowAction = strval($objForm->GetValue($diagnosticoaudiologia_grid->FormActionName));
			elseif ($diagnosticoaudiologia->CurrentAction == "gridadd")
				$diagnosticoaudiologia_grid->RowAction = "insert";
			else
				$diagnosticoaudiologia_grid->RowAction = "";
		}

		// Set up key count
		$diagnosticoaudiologia_grid->KeyCount = $diagnosticoaudiologia_grid->RowIndex;

		// Init row class and style
		$diagnosticoaudiologia->ResetAttrs();
		$diagnosticoaudiologia->CssClass = "";
		if ($diagnosticoaudiologia->CurrentAction == "gridadd") {
			if ($diagnosticoaudiologia->CurrentMode == "copy") {
				$diagnosticoaudiologia_grid->LoadRowValues($diagnosticoaudiologia_grid->Recordset); // Load row values
				$diagnosticoaudiologia_grid->SetRecordKey($diagnosticoaudiologia_grid->RowOldKey, $diagnosticoaudiologia_grid->Recordset); // Set old record key
			} else {
				$diagnosticoaudiologia_grid->LoadRowValues(); // Load default values
				$diagnosticoaudiologia_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$diagnosticoaudiologia_grid->LoadRowValues($diagnosticoaudiologia_grid->Recordset); // Load row values
		}
		$diagnosticoaudiologia->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($diagnosticoaudiologia->CurrentAction == "gridadd") // Grid add
			$diagnosticoaudiologia->RowType = EW_ROWTYPE_ADD; // Render add
		if ($diagnosticoaudiologia->CurrentAction == "gridadd" && $diagnosticoaudiologia->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$diagnosticoaudiologia_grid->RestoreCurrentRowFormValues($diagnosticoaudiologia_grid->RowIndex); // Restore form values
		if ($diagnosticoaudiologia->CurrentAction == "gridedit") { // Grid edit
			if ($diagnosticoaudiologia->EventCancelled) {
				$diagnosticoaudiologia_grid->RestoreCurrentRowFormValues($diagnosticoaudiologia_grid->RowIndex); // Restore form values
			}
			if ($diagnosticoaudiologia_grid->RowAction == "insert")
				$diagnosticoaudiologia->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$diagnosticoaudiologia->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($diagnosticoaudiologia->CurrentAction == "gridedit" && ($diagnosticoaudiologia->RowType == EW_ROWTYPE_EDIT || $diagnosticoaudiologia->RowType == EW_ROWTYPE_ADD) && $diagnosticoaudiologia->EventCancelled) // Update failed
			$diagnosticoaudiologia_grid->RestoreCurrentRowFormValues($diagnosticoaudiologia_grid->RowIndex); // Restore form values
		if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_EDIT) // Edit row
			$diagnosticoaudiologia_grid->EditRowCnt++;
		if ($diagnosticoaudiologia->CurrentAction == "F") // Confirm row
			$diagnosticoaudiologia_grid->RestoreCurrentRowFormValues($diagnosticoaudiologia_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$diagnosticoaudiologia->RowAttrs = array_merge($diagnosticoaudiologia->RowAttrs, array('data-rowindex'=>$diagnosticoaudiologia_grid->RowCnt, 'id'=>'r' . $diagnosticoaudiologia_grid->RowCnt . '_diagnosticoaudiologia', 'data-rowtype'=>$diagnosticoaudiologia->RowType));

		// Render row
		$diagnosticoaudiologia_grid->RenderRow();

		// Render list options
		$diagnosticoaudiologia_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($diagnosticoaudiologia_grid->RowAction <> "delete" && $diagnosticoaudiologia_grid->RowAction <> "insertdelete" && !($diagnosticoaudiologia_grid->RowAction == "insert" && $diagnosticoaudiologia->CurrentAction == "F" && $diagnosticoaudiologia_grid->EmptyRow())) {
?>
	<tr<?php echo $diagnosticoaudiologia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$diagnosticoaudiologia_grid->ListOptions->Render("body", "left", $diagnosticoaudiologia_grid->RowCnt);
?>
	<?php if ($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->Visible) { // id_tipodiagnosticoaudiologia ?>
		<td data-name="id_tipodiagnosticoaudiologia"<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->CellAttributes() ?>>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $diagnosticoaudiologia_grid->RowCnt ?>_diagnosticoaudiologia_id_tipodiagnosticoaudiologia" class="form-group diagnosticoaudiologia_id_tipodiagnosticoaudiologia">
<select data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" data-value-separator="<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia"<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->EditAttributes() ?>>
<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->SelectOptionListHtml("x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipodiagnosticoaudiologia") && !$diagnosticoaudiologia->id_tipodiagnosticoaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia',url:'tipodiagnosticoaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" name="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" id="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->OldValue) ?>">
<?php } ?>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $diagnosticoaudiologia_grid->RowCnt ?>_diagnosticoaudiologia_id_tipodiagnosticoaudiologia" class="form-group diagnosticoaudiologia_id_tipodiagnosticoaudiologia">
<select data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" data-value-separator="<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia"<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->EditAttributes() ?>>
<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->SelectOptionListHtml("x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipodiagnosticoaudiologia") && !$diagnosticoaudiologia->id_tipodiagnosticoaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia',url:'tipodiagnosticoaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $diagnosticoaudiologia_grid->RowCnt ?>_diagnosticoaudiologia_id_tipodiagnosticoaudiologia" class="diagnosticoaudiologia_id_tipodiagnosticoaudiologia">
<span<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->ViewAttributes() ?>>
<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->ListViewValue() ?></span>
</span>
<?php if ($diagnosticoaudiologia->CurrentAction <> "F") { ?>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FormValue) ?>">
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" name="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" id="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" name="fdiagnosticoaudiologiagrid$x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" id="fdiagnosticoaudiologiagrid$x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FormValue) ?>">
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" name="fdiagnosticoaudiologiagrid$o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" id="fdiagnosticoaudiologiagrid$o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id->CurrentValue) ?>">
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id" name="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id" id="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id->OldValue) ?>">
<?php } ?>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_EDIT || $diagnosticoaudiologia->CurrentMode == "edit") { ?>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($diagnosticoaudiologia->resultado->Visible) { // resultado ?>
		<td data-name="resultado"<?php echo $diagnosticoaudiologia->resultado->CellAttributes() ?>>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $diagnosticoaudiologia_grid->RowCnt ?>_diagnosticoaudiologia_resultado" class="form-group diagnosticoaudiologia_resultado">
<input type="text" data-table="diagnosticoaudiologia" data-field="x_resultado" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->getPlaceHolder()) ?>" value="<?php echo $diagnosticoaudiologia->resultado->EditValue ?>"<?php echo $diagnosticoaudiologia->resultado->EditAttributes() ?>>
</span>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_resultado" name="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->OldValue) ?>">
<?php } ?>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $diagnosticoaudiologia_grid->RowCnt ?>_diagnosticoaudiologia_resultado" class="form-group diagnosticoaudiologia_resultado">
<input type="text" data-table="diagnosticoaudiologia" data-field="x_resultado" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->getPlaceHolder()) ?>" value="<?php echo $diagnosticoaudiologia->resultado->EditValue ?>"<?php echo $diagnosticoaudiologia->resultado->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $diagnosticoaudiologia_grid->RowCnt ?>_diagnosticoaudiologia_resultado" class="diagnosticoaudiologia_resultado">
<span<?php echo $diagnosticoaudiologia->resultado->ViewAttributes() ?>>
<?php echo $diagnosticoaudiologia->resultado->ListViewValue() ?></span>
</span>
<?php if ($diagnosticoaudiologia->CurrentAction <> "F") { ?>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_resultado" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->FormValue) ?>">
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_resultado" name="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_resultado" name="fdiagnosticoaudiologiagrid$x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="fdiagnosticoaudiologiagrid$x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->FormValue) ?>">
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_resultado" name="fdiagnosticoaudiologiagrid$o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="fdiagnosticoaudiologiagrid$o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$diagnosticoaudiologia_grid->ListOptions->Render("body", "right", $diagnosticoaudiologia_grid->RowCnt);
?>
	</tr>
<?php if ($diagnosticoaudiologia->RowType == EW_ROWTYPE_ADD || $diagnosticoaudiologia->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdiagnosticoaudiologiagrid.UpdateOpts(<?php echo $diagnosticoaudiologia_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($diagnosticoaudiologia->CurrentAction <> "gridadd" || $diagnosticoaudiologia->CurrentMode == "copy")
		if (!$diagnosticoaudiologia_grid->Recordset->EOF) $diagnosticoaudiologia_grid->Recordset->MoveNext();
}
?>
<?php
	if ($diagnosticoaudiologia->CurrentMode == "add" || $diagnosticoaudiologia->CurrentMode == "copy" || $diagnosticoaudiologia->CurrentMode == "edit") {
		$diagnosticoaudiologia_grid->RowIndex = '$rowindex$';
		$diagnosticoaudiologia_grid->LoadRowValues();

		// Set row properties
		$diagnosticoaudiologia->ResetAttrs();
		$diagnosticoaudiologia->RowAttrs = array_merge($diagnosticoaudiologia->RowAttrs, array('data-rowindex'=>$diagnosticoaudiologia_grid->RowIndex, 'id'=>'r0_diagnosticoaudiologia', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($diagnosticoaudiologia->RowAttrs["class"], "ewTemplate");
		$diagnosticoaudiologia->RowType = EW_ROWTYPE_ADD;

		// Render row
		$diagnosticoaudiologia_grid->RenderRow();

		// Render list options
		$diagnosticoaudiologia_grid->RenderListOptions();
		$diagnosticoaudiologia_grid->StartRowCnt = 0;
?>
	<tr<?php echo $diagnosticoaudiologia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$diagnosticoaudiologia_grid->ListOptions->Render("body", "left", $diagnosticoaudiologia_grid->RowIndex);
?>
	<?php if ($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->Visible) { // id_tipodiagnosticoaudiologia ?>
		<td data-name="id_tipodiagnosticoaudiologia">
<?php if ($diagnosticoaudiologia->CurrentAction <> "F") { ?>
<span id="el$rowindex$_diagnosticoaudiologia_id_tipodiagnosticoaudiologia" class="form-group diagnosticoaudiologia_id_tipodiagnosticoaudiologia">
<select data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" data-value-separator="<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia"<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->EditAttributes() ?>>
<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->SelectOptionListHtml("x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipodiagnosticoaudiologia") && !$diagnosticoaudiologia->id_tipodiagnosticoaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia',url:'tipodiagnosticoaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_diagnosticoaudiologia_id_tipodiagnosticoaudiologia" class="form-group diagnosticoaudiologia_id_tipodiagnosticoaudiologia">
<span<?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $diagnosticoaudiologia->id_tipodiagnosticoaudiologia->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_id_tipodiagnosticoaudiologia" name="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" id="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_id_tipodiagnosticoaudiologia" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->id_tipodiagnosticoaudiologia->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($diagnosticoaudiologia->resultado->Visible) { // resultado ?>
		<td data-name="resultado">
<?php if ($diagnosticoaudiologia->CurrentAction <> "F") { ?>
<span id="el$rowindex$_diagnosticoaudiologia_resultado" class="form-group diagnosticoaudiologia_resultado">
<input type="text" data-table="diagnosticoaudiologia" data-field="x_resultado" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->getPlaceHolder()) ?>" value="<?php echo $diagnosticoaudiologia->resultado->EditValue ?>"<?php echo $diagnosticoaudiologia->resultado->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_diagnosticoaudiologia_resultado" class="form-group diagnosticoaudiologia_resultado">
<span<?php echo $diagnosticoaudiologia->resultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $diagnosticoaudiologia->resultado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_resultado" name="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="x<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="diagnosticoaudiologia" data-field="x_resultado" name="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" id="o<?php echo $diagnosticoaudiologia_grid->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($diagnosticoaudiologia->resultado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$diagnosticoaudiologia_grid->ListOptions->Render("body", "right", $diagnosticoaudiologia_grid->RowCnt);
?>
<script type="text/javascript">
fdiagnosticoaudiologiagrid.UpdateOpts(<?php echo $diagnosticoaudiologia_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($diagnosticoaudiologia->CurrentMode == "add" || $diagnosticoaudiologia->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $diagnosticoaudiologia_grid->FormKeyCountName ?>" id="<?php echo $diagnosticoaudiologia_grid->FormKeyCountName ?>" value="<?php echo $diagnosticoaudiologia_grid->KeyCount ?>">
<?php echo $diagnosticoaudiologia_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($diagnosticoaudiologia->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $diagnosticoaudiologia_grid->FormKeyCountName ?>" id="<?php echo $diagnosticoaudiologia_grid->FormKeyCountName ?>" value="<?php echo $diagnosticoaudiologia_grid->KeyCount ?>">
<?php echo $diagnosticoaudiologia_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($diagnosticoaudiologia->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdiagnosticoaudiologiagrid">
</div>
<?php

// Close recordset
if ($diagnosticoaudiologia_grid->Recordset)
	$diagnosticoaudiologia_grid->Recordset->Close();
?>
<?php if ($diagnosticoaudiologia_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($diagnosticoaudiologia_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($diagnosticoaudiologia_grid->TotalRecs == 0 && $diagnosticoaudiologia->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($diagnosticoaudiologia_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($diagnosticoaudiologia->Export == "") { ?>
<script type="text/javascript">
fdiagnosticoaudiologiagrid.Init();
</script>
<?php } ?>
<?php
$diagnosticoaudiologia_grid->Page_Terminate();
?>
