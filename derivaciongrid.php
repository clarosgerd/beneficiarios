<?php include_once "usuarioinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($derivacion_grid)) $derivacion_grid = new cderivacion_grid();

// Page init
$derivacion_grid->Page_Init();

// Page main
$derivacion_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$derivacion_grid->Page_Render();
?>
<?php if ($derivacion->Export == "") { ?>
<script type="text/javascript">

// Form object
var fderivaciongrid = new ew_Form("fderivaciongrid", "grid");
fderivaciongrid.FormKeyCountName = '<?php echo $derivacion_grid->FormKeyCountName ?>';

// Validate form
fderivaciongrid.Validate = function() {
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
fderivaciongrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_tipoespecialidad", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipoderivacion", false)) return false;
	return true;
}

// Form_CustomValidate event
fderivaciongrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fderivaciongrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fderivaciongrid.Lists["x_id_tipoespecialidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoespecialidad"};
fderivaciongrid.Lists["x_id_tipoespecialidad"].Data = "<?php echo $derivacion_grid->id_tipoespecialidad->LookupFilterQuery(FALSE, "grid") ?>";
fderivaciongrid.Lists["x_tipoderivacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fderivaciongrid.Lists["x_tipoderivacion"].Options = <?php echo json_encode($derivacion_grid->tipoderivacion->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($derivacion->CurrentAction == "gridadd") {
	if ($derivacion->CurrentMode == "copy") {
		$bSelectLimit = $derivacion_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$derivacion_grid->TotalRecs = $derivacion->ListRecordCount();
			$derivacion_grid->Recordset = $derivacion_grid->LoadRecordset($derivacion_grid->StartRec-1, $derivacion_grid->DisplayRecs);
		} else {
			if ($derivacion_grid->Recordset = $derivacion_grid->LoadRecordset())
				$derivacion_grid->TotalRecs = $derivacion_grid->Recordset->RecordCount();
		}
		$derivacion_grid->StartRec = 1;
		$derivacion_grid->DisplayRecs = $derivacion_grid->TotalRecs;
	} else {
		$derivacion->CurrentFilter = "0=1";
		$derivacion_grid->StartRec = 1;
		$derivacion_grid->DisplayRecs = $derivacion->GridAddRowCount;
	}
	$derivacion_grid->TotalRecs = $derivacion_grid->DisplayRecs;
	$derivacion_grid->StopRec = $derivacion_grid->DisplayRecs;
} else {
	$bSelectLimit = $derivacion_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($derivacion_grid->TotalRecs <= 0)
			$derivacion_grid->TotalRecs = $derivacion->ListRecordCount();
	} else {
		if (!$derivacion_grid->Recordset && ($derivacion_grid->Recordset = $derivacion_grid->LoadRecordset()))
			$derivacion_grid->TotalRecs = $derivacion_grid->Recordset->RecordCount();
	}
	$derivacion_grid->StartRec = 1;
	$derivacion_grid->DisplayRecs = $derivacion_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$derivacion_grid->Recordset = $derivacion_grid->LoadRecordset($derivacion_grid->StartRec-1, $derivacion_grid->DisplayRecs);

	// Set no record found message
	if ($derivacion->CurrentAction == "" && $derivacion_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$derivacion_grid->setWarningMessage(ew_DeniedMsg());
		if ($derivacion_grid->SearchWhere == "0=101")
			$derivacion_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$derivacion_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$derivacion_grid->RenderOtherOptions();
?>
<?php $derivacion_grid->ShowPageHeader(); ?>
<?php
$derivacion_grid->ShowMessage();
?>
<?php if ($derivacion_grid->TotalRecs > 0 || $derivacion->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($derivacion_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> derivacion">
<div id="fderivaciongrid" class="ewForm ewListForm form-inline">
<?php if ($derivacion_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($derivacion_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_derivacion" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_derivaciongrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$derivacion_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$derivacion_grid->RenderListOptions();

// Render list options (header, left)
$derivacion_grid->ListOptions->Render("header", "left");
?>
<?php if ($derivacion->id->Visible) { // id ?>
	<?php if ($derivacion->SortUrl($derivacion->id) == "") { ?>
		<th data-name="id" class="<?php echo $derivacion->id->HeaderCellClass() ?>"><div id="elh_derivacion_id" class="derivacion_id"><div class="ewTableHeaderCaption"><?php echo $derivacion->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $derivacion->id->HeaderCellClass() ?>"><div><div id="elh_derivacion_id" class="derivacion_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $derivacion->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($derivacion->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($derivacion->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($derivacion->id_tipoespecialidad->Visible) { // id_tipoespecialidad ?>
	<?php if ($derivacion->SortUrl($derivacion->id_tipoespecialidad) == "") { ?>
		<th data-name="id_tipoespecialidad" class="<?php echo $derivacion->id_tipoespecialidad->HeaderCellClass() ?>"><div id="elh_derivacion_id_tipoespecialidad" class="derivacion_id_tipoespecialidad"><div class="ewTableHeaderCaption"><?php echo $derivacion->id_tipoespecialidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipoespecialidad" class="<?php echo $derivacion->id_tipoespecialidad->HeaderCellClass() ?>"><div><div id="elh_derivacion_id_tipoespecialidad" class="derivacion_id_tipoespecialidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $derivacion->id_tipoespecialidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($derivacion->id_tipoespecialidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($derivacion->id_tipoespecialidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($derivacion->tipoderivacion->Visible) { // tipoderivacion ?>
	<?php if ($derivacion->SortUrl($derivacion->tipoderivacion) == "") { ?>
		<th data-name="tipoderivacion" class="<?php echo $derivacion->tipoderivacion->HeaderCellClass() ?>"><div id="elh_derivacion_tipoderivacion" class="derivacion_tipoderivacion"><div class="ewTableHeaderCaption"><?php echo $derivacion->tipoderivacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoderivacion" class="<?php echo $derivacion->tipoderivacion->HeaderCellClass() ?>"><div><div id="elh_derivacion_tipoderivacion" class="derivacion_tipoderivacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $derivacion->tipoderivacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($derivacion->tipoderivacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($derivacion->tipoderivacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$derivacion_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$derivacion_grid->StartRec = 1;
$derivacion_grid->StopRec = $derivacion_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($derivacion_grid->FormKeyCountName) && ($derivacion->CurrentAction == "gridadd" || $derivacion->CurrentAction == "gridedit" || $derivacion->CurrentAction == "F")) {
		$derivacion_grid->KeyCount = $objForm->GetValue($derivacion_grid->FormKeyCountName);
		$derivacion_grid->StopRec = $derivacion_grid->StartRec + $derivacion_grid->KeyCount - 1;
	}
}
$derivacion_grid->RecCnt = $derivacion_grid->StartRec - 1;
if ($derivacion_grid->Recordset && !$derivacion_grid->Recordset->EOF) {
	$derivacion_grid->Recordset->MoveFirst();
	$bSelectLimit = $derivacion_grid->UseSelectLimit;
	if (!$bSelectLimit && $derivacion_grid->StartRec > 1)
		$derivacion_grid->Recordset->Move($derivacion_grid->StartRec - 1);
} elseif (!$derivacion->AllowAddDeleteRow && $derivacion_grid->StopRec == 0) {
	$derivacion_grid->StopRec = $derivacion->GridAddRowCount;
}

// Initialize aggregate
$derivacion->RowType = EW_ROWTYPE_AGGREGATEINIT;
$derivacion->ResetAttrs();
$derivacion_grid->RenderRow();
if ($derivacion->CurrentAction == "gridadd")
	$derivacion_grid->RowIndex = 0;
if ($derivacion->CurrentAction == "gridedit")
	$derivacion_grid->RowIndex = 0;
while ($derivacion_grid->RecCnt < $derivacion_grid->StopRec) {
	$derivacion_grid->RecCnt++;
	if (intval($derivacion_grid->RecCnt) >= intval($derivacion_grid->StartRec)) {
		$derivacion_grid->RowCnt++;
		if ($derivacion->CurrentAction == "gridadd" || $derivacion->CurrentAction == "gridedit" || $derivacion->CurrentAction == "F") {
			$derivacion_grid->RowIndex++;
			$objForm->Index = $derivacion_grid->RowIndex;
			if ($objForm->HasValue($derivacion_grid->FormActionName))
				$derivacion_grid->RowAction = strval($objForm->GetValue($derivacion_grid->FormActionName));
			elseif ($derivacion->CurrentAction == "gridadd")
				$derivacion_grid->RowAction = "insert";
			else
				$derivacion_grid->RowAction = "";
		}

		// Set up key count
		$derivacion_grid->KeyCount = $derivacion_grid->RowIndex;

		// Init row class and style
		$derivacion->ResetAttrs();
		$derivacion->CssClass = "";
		if ($derivacion->CurrentAction == "gridadd") {
			if ($derivacion->CurrentMode == "copy") {
				$derivacion_grid->LoadRowValues($derivacion_grid->Recordset); // Load row values
				$derivacion_grid->SetRecordKey($derivacion_grid->RowOldKey, $derivacion_grid->Recordset); // Set old record key
			} else {
				$derivacion_grid->LoadRowValues(); // Load default values
				$derivacion_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$derivacion_grid->LoadRowValues($derivacion_grid->Recordset); // Load row values
		}
		$derivacion->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($derivacion->CurrentAction == "gridadd") // Grid add
			$derivacion->RowType = EW_ROWTYPE_ADD; // Render add
		if ($derivacion->CurrentAction == "gridadd" && $derivacion->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$derivacion_grid->RestoreCurrentRowFormValues($derivacion_grid->RowIndex); // Restore form values
		if ($derivacion->CurrentAction == "gridedit") { // Grid edit
			if ($derivacion->EventCancelled) {
				$derivacion_grid->RestoreCurrentRowFormValues($derivacion_grid->RowIndex); // Restore form values
			}
			if ($derivacion_grid->RowAction == "insert")
				$derivacion->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$derivacion->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($derivacion->CurrentAction == "gridedit" && ($derivacion->RowType == EW_ROWTYPE_EDIT || $derivacion->RowType == EW_ROWTYPE_ADD) && $derivacion->EventCancelled) // Update failed
			$derivacion_grid->RestoreCurrentRowFormValues($derivacion_grid->RowIndex); // Restore form values
		if ($derivacion->RowType == EW_ROWTYPE_EDIT) // Edit row
			$derivacion_grid->EditRowCnt++;
		if ($derivacion->CurrentAction == "F") // Confirm row
			$derivacion_grid->RestoreCurrentRowFormValues($derivacion_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$derivacion->RowAttrs = array_merge($derivacion->RowAttrs, array('data-rowindex'=>$derivacion_grid->RowCnt, 'id'=>'r' . $derivacion_grid->RowCnt . '_derivacion', 'data-rowtype'=>$derivacion->RowType));

		// Render row
		$derivacion_grid->RenderRow();

		// Render list options
		$derivacion_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($derivacion_grid->RowAction <> "delete" && $derivacion_grid->RowAction <> "insertdelete" && !($derivacion_grid->RowAction == "insert" && $derivacion->CurrentAction == "F" && $derivacion_grid->EmptyRow())) {
?>
	<tr<?php echo $derivacion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$derivacion_grid->ListOptions->Render("body", "left", $derivacion_grid->RowCnt);
?>
	<?php if ($derivacion->id->Visible) { // id ?>
		<td data-name="id"<?php echo $derivacion->id->CellAttributes() ?>>
<?php if ($derivacion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="derivacion" data-field="x_id" name="o<?php echo $derivacion_grid->RowIndex ?>_id" id="o<?php echo $derivacion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($derivacion->id->OldValue) ?>">
<?php } ?>
<?php if ($derivacion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $derivacion_grid->RowCnt ?>_derivacion_id" class="form-group derivacion_id">
<span<?php echo $derivacion->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $derivacion->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="derivacion" data-field="x_id" name="x<?php echo $derivacion_grid->RowIndex ?>_id" id="x<?php echo $derivacion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($derivacion->id->CurrentValue) ?>">
<?php } ?>
<?php if ($derivacion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $derivacion_grid->RowCnt ?>_derivacion_id" class="derivacion_id">
<span<?php echo $derivacion->id->ViewAttributes() ?>>
<?php echo $derivacion->id->ListViewValue() ?></span>
</span>
<?php if ($derivacion->CurrentAction <> "F") { ?>
<input type="hidden" data-table="derivacion" data-field="x_id" name="x<?php echo $derivacion_grid->RowIndex ?>_id" id="x<?php echo $derivacion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($derivacion->id->FormValue) ?>">
<input type="hidden" data-table="derivacion" data-field="x_id" name="o<?php echo $derivacion_grid->RowIndex ?>_id" id="o<?php echo $derivacion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($derivacion->id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="derivacion" data-field="x_id" name="fderivaciongrid$x<?php echo $derivacion_grid->RowIndex ?>_id" id="fderivaciongrid$x<?php echo $derivacion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($derivacion->id->FormValue) ?>">
<input type="hidden" data-table="derivacion" data-field="x_id" name="fderivaciongrid$o<?php echo $derivacion_grid->RowIndex ?>_id" id="fderivaciongrid$o<?php echo $derivacion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($derivacion->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($derivacion->id_tipoespecialidad->Visible) { // id_tipoespecialidad ?>
		<td data-name="id_tipoespecialidad"<?php echo $derivacion->id_tipoespecialidad->CellAttributes() ?>>
<?php if ($derivacion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $derivacion_grid->RowCnt ?>_derivacion_id_tipoespecialidad" class="form-group derivacion_id_tipoespecialidad">
<select data-table="derivacion" data-field="x_id_tipoespecialidad" data-value-separator="<?php echo $derivacion->id_tipoespecialidad->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" name="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad"<?php echo $derivacion->id_tipoespecialidad->EditAttributes() ?>>
<?php echo $derivacion->id_tipoespecialidad->SelectOptionListHtml("x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipoespecialidad") && !$derivacion->id_tipoespecialidad->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $derivacion->id_tipoespecialidad->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad',url:'tipoespecialidadaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $derivacion->id_tipoespecialidad->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="derivacion" data-field="x_id_tipoespecialidad" name="o<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" id="o<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" value="<?php echo ew_HtmlEncode($derivacion->id_tipoespecialidad->OldValue) ?>">
<?php } ?>
<?php if ($derivacion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $derivacion_grid->RowCnt ?>_derivacion_id_tipoespecialidad" class="form-group derivacion_id_tipoespecialidad">
<select data-table="derivacion" data-field="x_id_tipoespecialidad" data-value-separator="<?php echo $derivacion->id_tipoespecialidad->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" name="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad"<?php echo $derivacion->id_tipoespecialidad->EditAttributes() ?>>
<?php echo $derivacion->id_tipoespecialidad->SelectOptionListHtml("x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipoespecialidad") && !$derivacion->id_tipoespecialidad->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $derivacion->id_tipoespecialidad->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad',url:'tipoespecialidadaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $derivacion->id_tipoespecialidad->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($derivacion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $derivacion_grid->RowCnt ?>_derivacion_id_tipoespecialidad" class="derivacion_id_tipoespecialidad">
<span<?php echo $derivacion->id_tipoespecialidad->ViewAttributes() ?>>
<?php echo $derivacion->id_tipoespecialidad->ListViewValue() ?></span>
</span>
<?php if ($derivacion->CurrentAction <> "F") { ?>
<input type="hidden" data-table="derivacion" data-field="x_id_tipoespecialidad" name="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" id="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" value="<?php echo ew_HtmlEncode($derivacion->id_tipoespecialidad->FormValue) ?>">
<input type="hidden" data-table="derivacion" data-field="x_id_tipoespecialidad" name="o<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" id="o<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" value="<?php echo ew_HtmlEncode($derivacion->id_tipoespecialidad->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="derivacion" data-field="x_id_tipoespecialidad" name="fderivaciongrid$x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" id="fderivaciongrid$x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" value="<?php echo ew_HtmlEncode($derivacion->id_tipoespecialidad->FormValue) ?>">
<input type="hidden" data-table="derivacion" data-field="x_id_tipoespecialidad" name="fderivaciongrid$o<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" id="fderivaciongrid$o<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" value="<?php echo ew_HtmlEncode($derivacion->id_tipoespecialidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($derivacion->tipoderivacion->Visible) { // tipoderivacion ?>
		<td data-name="tipoderivacion"<?php echo $derivacion->tipoderivacion->CellAttributes() ?>>
<?php if ($derivacion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $derivacion_grid->RowCnt ?>_derivacion_tipoderivacion" class="form-group derivacion_tipoderivacion">
<select data-table="derivacion" data-field="x_tipoderivacion" data-value-separator="<?php echo $derivacion->tipoderivacion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" name="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion"<?php echo $derivacion->tipoderivacion->EditAttributes() ?>>
<?php echo $derivacion->tipoderivacion->SelectOptionListHtml("x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion") ?>
</select>
</span>
<input type="hidden" data-table="derivacion" data-field="x_tipoderivacion" name="o<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" id="o<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" value="<?php echo ew_HtmlEncode($derivacion->tipoderivacion->OldValue) ?>">
<?php } ?>
<?php if ($derivacion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $derivacion_grid->RowCnt ?>_derivacion_tipoderivacion" class="form-group derivacion_tipoderivacion">
<select data-table="derivacion" data-field="x_tipoderivacion" data-value-separator="<?php echo $derivacion->tipoderivacion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" name="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion"<?php echo $derivacion->tipoderivacion->EditAttributes() ?>>
<?php echo $derivacion->tipoderivacion->SelectOptionListHtml("x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion") ?>
</select>
</span>
<?php } ?>
<?php if ($derivacion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $derivacion_grid->RowCnt ?>_derivacion_tipoderivacion" class="derivacion_tipoderivacion">
<span<?php echo $derivacion->tipoderivacion->ViewAttributes() ?>>
<?php echo $derivacion->tipoderivacion->ListViewValue() ?></span>
</span>
<?php if ($derivacion->CurrentAction <> "F") { ?>
<input type="hidden" data-table="derivacion" data-field="x_tipoderivacion" name="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" id="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" value="<?php echo ew_HtmlEncode($derivacion->tipoderivacion->FormValue) ?>">
<input type="hidden" data-table="derivacion" data-field="x_tipoderivacion" name="o<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" id="o<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" value="<?php echo ew_HtmlEncode($derivacion->tipoderivacion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="derivacion" data-field="x_tipoderivacion" name="fderivaciongrid$x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" id="fderivaciongrid$x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" value="<?php echo ew_HtmlEncode($derivacion->tipoderivacion->FormValue) ?>">
<input type="hidden" data-table="derivacion" data-field="x_tipoderivacion" name="fderivaciongrid$o<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" id="fderivaciongrid$o<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" value="<?php echo ew_HtmlEncode($derivacion->tipoderivacion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$derivacion_grid->ListOptions->Render("body", "right", $derivacion_grid->RowCnt);
?>
	</tr>
<?php if ($derivacion->RowType == EW_ROWTYPE_ADD || $derivacion->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fderivaciongrid.UpdateOpts(<?php echo $derivacion_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($derivacion->CurrentAction <> "gridadd" || $derivacion->CurrentMode == "copy")
		if (!$derivacion_grid->Recordset->EOF) $derivacion_grid->Recordset->MoveNext();
}
?>
<?php
	if ($derivacion->CurrentMode == "add" || $derivacion->CurrentMode == "copy" || $derivacion->CurrentMode == "edit") {
		$derivacion_grid->RowIndex = '$rowindex$';
		$derivacion_grid->LoadRowValues();

		// Set row properties
		$derivacion->ResetAttrs();
		$derivacion->RowAttrs = array_merge($derivacion->RowAttrs, array('data-rowindex'=>$derivacion_grid->RowIndex, 'id'=>'r0_derivacion', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($derivacion->RowAttrs["class"], "ewTemplate");
		$derivacion->RowType = EW_ROWTYPE_ADD;

		// Render row
		$derivacion_grid->RenderRow();

		// Render list options
		$derivacion_grid->RenderListOptions();
		$derivacion_grid->StartRowCnt = 0;
?>
	<tr<?php echo $derivacion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$derivacion_grid->ListOptions->Render("body", "left", $derivacion_grid->RowIndex);
?>
	<?php if ($derivacion->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($derivacion->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_derivacion_id" class="form-group derivacion_id">
<span<?php echo $derivacion->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $derivacion->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="derivacion" data-field="x_id" name="x<?php echo $derivacion_grid->RowIndex ?>_id" id="x<?php echo $derivacion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($derivacion->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="derivacion" data-field="x_id" name="o<?php echo $derivacion_grid->RowIndex ?>_id" id="o<?php echo $derivacion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($derivacion->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($derivacion->id_tipoespecialidad->Visible) { // id_tipoespecialidad ?>
		<td data-name="id_tipoespecialidad">
<?php if ($derivacion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_derivacion_id_tipoespecialidad" class="form-group derivacion_id_tipoespecialidad">
<select data-table="derivacion" data-field="x_id_tipoespecialidad" data-value-separator="<?php echo $derivacion->id_tipoespecialidad->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" name="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad"<?php echo $derivacion->id_tipoespecialidad->EditAttributes() ?>>
<?php echo $derivacion->id_tipoespecialidad->SelectOptionListHtml("x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipoespecialidad") && !$derivacion->id_tipoespecialidad->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $derivacion->id_tipoespecialidad->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad',url:'tipoespecialidadaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $derivacion->id_tipoespecialidad->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_derivacion_id_tipoespecialidad" class="form-group derivacion_id_tipoespecialidad">
<span<?php echo $derivacion->id_tipoespecialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $derivacion->id_tipoespecialidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="derivacion" data-field="x_id_tipoespecialidad" name="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" id="x<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" value="<?php echo ew_HtmlEncode($derivacion->id_tipoespecialidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="derivacion" data-field="x_id_tipoespecialidad" name="o<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" id="o<?php echo $derivacion_grid->RowIndex ?>_id_tipoespecialidad" value="<?php echo ew_HtmlEncode($derivacion->id_tipoespecialidad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($derivacion->tipoderivacion->Visible) { // tipoderivacion ?>
		<td data-name="tipoderivacion">
<?php if ($derivacion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_derivacion_tipoderivacion" class="form-group derivacion_tipoderivacion">
<select data-table="derivacion" data-field="x_tipoderivacion" data-value-separator="<?php echo $derivacion->tipoderivacion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" name="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion"<?php echo $derivacion->tipoderivacion->EditAttributes() ?>>
<?php echo $derivacion->tipoderivacion->SelectOptionListHtml("x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_derivacion_tipoderivacion" class="form-group derivacion_tipoderivacion">
<span<?php echo $derivacion->tipoderivacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $derivacion->tipoderivacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="derivacion" data-field="x_tipoderivacion" name="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" id="x<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" value="<?php echo ew_HtmlEncode($derivacion->tipoderivacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="derivacion" data-field="x_tipoderivacion" name="o<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" id="o<?php echo $derivacion_grid->RowIndex ?>_tipoderivacion" value="<?php echo ew_HtmlEncode($derivacion->tipoderivacion->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$derivacion_grid->ListOptions->Render("body", "right", $derivacion_grid->RowCnt);
?>
<script type="text/javascript">
fderivaciongrid.UpdateOpts(<?php echo $derivacion_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($derivacion->CurrentMode == "add" || $derivacion->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $derivacion_grid->FormKeyCountName ?>" id="<?php echo $derivacion_grid->FormKeyCountName ?>" value="<?php echo $derivacion_grid->KeyCount ?>">
<?php echo $derivacion_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($derivacion->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $derivacion_grid->FormKeyCountName ?>" id="<?php echo $derivacion_grid->FormKeyCountName ?>" value="<?php echo $derivacion_grid->KeyCount ?>">
<?php echo $derivacion_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($derivacion->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fderivaciongrid">
</div>
<?php

// Close recordset
if ($derivacion_grid->Recordset)
	$derivacion_grid->Recordset->Close();
?>
<?php if ($derivacion_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($derivacion_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($derivacion_grid->TotalRecs == 0 && $derivacion->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($derivacion_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($derivacion->Export == "") { ?>
<script type="text/javascript">
fderivaciongrid.Init();
</script>
<?php } ?>
<?php
$derivacion_grid->Page_Terminate();
?>
