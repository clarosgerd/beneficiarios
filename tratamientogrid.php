<?php include_once "usuarioinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tratamiento_grid)) $tratamiento_grid = new ctratamiento_grid();

// Page init
$tratamiento_grid->Page_Init();

// Page main
$tratamiento_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tratamiento_grid->Page_Render();
?>
<?php if ($tratamiento->Export == "") { ?>
<script type="text/javascript">

// Form object
var ftratamientogrid = new ew_Form("ftratamientogrid", "grid");
ftratamientogrid.FormKeyCountName = '<?php echo $tratamiento_grid->FormKeyCountName ?>';

// Validate form
ftratamientogrid.Validate = function() {
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
ftratamientogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_tipotratamientoaudiologia", false)) return false;
	return true;
}

// Form_CustomValidate event
ftratamientogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ftratamientogrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
ftratamientogrid.Lists["x_id_tipotratamientoaudiologia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipotratamientoaudiologia"};
ftratamientogrid.Lists["x_id_tipotratamientoaudiologia"].Data = "<?php echo $tratamiento_grid->id_tipotratamientoaudiologia->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($tratamiento->CurrentAction == "gridadd") {
	if ($tratamiento->CurrentMode == "copy") {
		$bSelectLimit = $tratamiento_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$tratamiento_grid->TotalRecs = $tratamiento->ListRecordCount();
			$tratamiento_grid->Recordset = $tratamiento_grid->LoadRecordset($tratamiento_grid->StartRec-1, $tratamiento_grid->DisplayRecs);
		} else {
			if ($tratamiento_grid->Recordset = $tratamiento_grid->LoadRecordset())
				$tratamiento_grid->TotalRecs = $tratamiento_grid->Recordset->RecordCount();
		}
		$tratamiento_grid->StartRec = 1;
		$tratamiento_grid->DisplayRecs = $tratamiento_grid->TotalRecs;
	} else {
		$tratamiento->CurrentFilter = "0=1";
		$tratamiento_grid->StartRec = 1;
		$tratamiento_grid->DisplayRecs = $tratamiento->GridAddRowCount;
	}
	$tratamiento_grid->TotalRecs = $tratamiento_grid->DisplayRecs;
	$tratamiento_grid->StopRec = $tratamiento_grid->DisplayRecs;
} else {
	$bSelectLimit = $tratamiento_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tratamiento_grid->TotalRecs <= 0)
			$tratamiento_grid->TotalRecs = $tratamiento->ListRecordCount();
	} else {
		if (!$tratamiento_grid->Recordset && ($tratamiento_grid->Recordset = $tratamiento_grid->LoadRecordset()))
			$tratamiento_grid->TotalRecs = $tratamiento_grid->Recordset->RecordCount();
	}
	$tratamiento_grid->StartRec = 1;
	$tratamiento_grid->DisplayRecs = $tratamiento_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$tratamiento_grid->Recordset = $tratamiento_grid->LoadRecordset($tratamiento_grid->StartRec-1, $tratamiento_grid->DisplayRecs);

	// Set no record found message
	if ($tratamiento->CurrentAction == "" && $tratamiento_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$tratamiento_grid->setWarningMessage(ew_DeniedMsg());
		if ($tratamiento_grid->SearchWhere == "0=101")
			$tratamiento_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tratamiento_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$tratamiento_grid->RenderOtherOptions();
?>
<?php $tratamiento_grid->ShowPageHeader(); ?>
<?php
$tratamiento_grid->ShowMessage();
?>
<?php if ($tratamiento_grid->TotalRecs > 0 || $tratamiento->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($tratamiento_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> tratamiento">
<div id="ftratamientogrid" class="ewForm ewListForm form-inline">
<?php if ($tratamiento_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($tratamiento_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_tratamiento" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_tratamientogrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$tratamiento_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tratamiento_grid->RenderListOptions();

// Render list options (header, left)
$tratamiento_grid->ListOptions->Render("header", "left");
?>
<?php if ($tratamiento->id_tipotratamientoaudiologia->Visible) { // id_tipotratamientoaudiologia ?>
	<?php if ($tratamiento->SortUrl($tratamiento->id_tipotratamientoaudiologia) == "") { ?>
		<th data-name="id_tipotratamientoaudiologia" class="<?php echo $tratamiento->id_tipotratamientoaudiologia->HeaderCellClass() ?>"><div id="elh_tratamiento_id_tipotratamientoaudiologia" class="tratamiento_id_tipotratamientoaudiologia"><div class="ewTableHeaderCaption"><?php echo $tratamiento->id_tipotratamientoaudiologia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipotratamientoaudiologia" class="<?php echo $tratamiento->id_tipotratamientoaudiologia->HeaderCellClass() ?>"><div><div id="elh_tratamiento_id_tipotratamientoaudiologia" class="tratamiento_id_tipotratamientoaudiologia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tratamiento->id_tipotratamientoaudiologia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tratamiento->id_tipotratamientoaudiologia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tratamiento->id_tipotratamientoaudiologia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$tratamiento_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$tratamiento_grid->StartRec = 1;
$tratamiento_grid->StopRec = $tratamiento_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($tratamiento_grid->FormKeyCountName) && ($tratamiento->CurrentAction == "gridadd" || $tratamiento->CurrentAction == "gridedit" || $tratamiento->CurrentAction == "F")) {
		$tratamiento_grid->KeyCount = $objForm->GetValue($tratamiento_grid->FormKeyCountName);
		$tratamiento_grid->StopRec = $tratamiento_grid->StartRec + $tratamiento_grid->KeyCount - 1;
	}
}
$tratamiento_grid->RecCnt = $tratamiento_grid->StartRec - 1;
if ($tratamiento_grid->Recordset && !$tratamiento_grid->Recordset->EOF) {
	$tratamiento_grid->Recordset->MoveFirst();
	$bSelectLimit = $tratamiento_grid->UseSelectLimit;
	if (!$bSelectLimit && $tratamiento_grid->StartRec > 1)
		$tratamiento_grid->Recordset->Move($tratamiento_grid->StartRec - 1);
} elseif (!$tratamiento->AllowAddDeleteRow && $tratamiento_grid->StopRec == 0) {
	$tratamiento_grid->StopRec = $tratamiento->GridAddRowCount;
}

// Initialize aggregate
$tratamiento->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tratamiento->ResetAttrs();
$tratamiento_grid->RenderRow();
if ($tratamiento->CurrentAction == "gridadd")
	$tratamiento_grid->RowIndex = 0;
if ($tratamiento->CurrentAction == "gridedit")
	$tratamiento_grid->RowIndex = 0;
while ($tratamiento_grid->RecCnt < $tratamiento_grid->StopRec) {
	$tratamiento_grid->RecCnt++;
	if (intval($tratamiento_grid->RecCnt) >= intval($tratamiento_grid->StartRec)) {
		$tratamiento_grid->RowCnt++;
		if ($tratamiento->CurrentAction == "gridadd" || $tratamiento->CurrentAction == "gridedit" || $tratamiento->CurrentAction == "F") {
			$tratamiento_grid->RowIndex++;
			$objForm->Index = $tratamiento_grid->RowIndex;
			if ($objForm->HasValue($tratamiento_grid->FormActionName))
				$tratamiento_grid->RowAction = strval($objForm->GetValue($tratamiento_grid->FormActionName));
			elseif ($tratamiento->CurrentAction == "gridadd")
				$tratamiento_grid->RowAction = "insert";
			else
				$tratamiento_grid->RowAction = "";
		}

		// Set up key count
		$tratamiento_grid->KeyCount = $tratamiento_grid->RowIndex;

		// Init row class and style
		$tratamiento->ResetAttrs();
		$tratamiento->CssClass = "";
		if ($tratamiento->CurrentAction == "gridadd") {
			if ($tratamiento->CurrentMode == "copy") {
				$tratamiento_grid->LoadRowValues($tratamiento_grid->Recordset); // Load row values
				$tratamiento_grid->SetRecordKey($tratamiento_grid->RowOldKey, $tratamiento_grid->Recordset); // Set old record key
			} else {
				$tratamiento_grid->LoadRowValues(); // Load default values
				$tratamiento_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$tratamiento_grid->LoadRowValues($tratamiento_grid->Recordset); // Load row values
		}
		$tratamiento->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($tratamiento->CurrentAction == "gridadd") // Grid add
			$tratamiento->RowType = EW_ROWTYPE_ADD; // Render add
		if ($tratamiento->CurrentAction == "gridadd" && $tratamiento->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$tratamiento_grid->RestoreCurrentRowFormValues($tratamiento_grid->RowIndex); // Restore form values
		if ($tratamiento->CurrentAction == "gridedit") { // Grid edit
			if ($tratamiento->EventCancelled) {
				$tratamiento_grid->RestoreCurrentRowFormValues($tratamiento_grid->RowIndex); // Restore form values
			}
			if ($tratamiento_grid->RowAction == "insert")
				$tratamiento->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$tratamiento->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($tratamiento->CurrentAction == "gridedit" && ($tratamiento->RowType == EW_ROWTYPE_EDIT || $tratamiento->RowType == EW_ROWTYPE_ADD) && $tratamiento->EventCancelled) // Update failed
			$tratamiento_grid->RestoreCurrentRowFormValues($tratamiento_grid->RowIndex); // Restore form values
		if ($tratamiento->RowType == EW_ROWTYPE_EDIT) // Edit row
			$tratamiento_grid->EditRowCnt++;
		if ($tratamiento->CurrentAction == "F") // Confirm row
			$tratamiento_grid->RestoreCurrentRowFormValues($tratamiento_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$tratamiento->RowAttrs = array_merge($tratamiento->RowAttrs, array('data-rowindex'=>$tratamiento_grid->RowCnt, 'id'=>'r' . $tratamiento_grid->RowCnt . '_tratamiento', 'data-rowtype'=>$tratamiento->RowType));

		// Render row
		$tratamiento_grid->RenderRow();

		// Render list options
		$tratamiento_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($tratamiento_grid->RowAction <> "delete" && $tratamiento_grid->RowAction <> "insertdelete" && !($tratamiento_grid->RowAction == "insert" && $tratamiento->CurrentAction == "F" && $tratamiento_grid->EmptyRow())) {
?>
	<tr<?php echo $tratamiento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tratamiento_grid->ListOptions->Render("body", "left", $tratamiento_grid->RowCnt);
?>
	<?php if ($tratamiento->id_tipotratamientoaudiologia->Visible) { // id_tipotratamientoaudiologia ?>
		<td data-name="id_tipotratamientoaudiologia"<?php echo $tratamiento->id_tipotratamientoaudiologia->CellAttributes() ?>>
<?php if ($tratamiento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tratamiento_grid->RowCnt ?>_tratamiento_id_tipotratamientoaudiologia" class="form-group tratamiento_id_tipotratamientoaudiologia">
<select data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" data-value-separator="<?php echo $tratamiento->id_tipotratamientoaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" name="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia"<?php echo $tratamiento->id_tipotratamientoaudiologia->EditAttributes() ?>>
<?php echo $tratamiento->id_tipotratamientoaudiologia->SelectOptionListHtml("x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipotratamientoaudiologia") && !$tratamiento->id_tipotratamientoaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $tratamiento->id_tipotratamientoaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia',url:'tipotratamientoaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $tratamiento->id_tipotratamientoaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" name="o<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" id="o<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" value="<?php echo ew_HtmlEncode($tratamiento->id_tipotratamientoaudiologia->OldValue) ?>">
<?php } ?>
<?php if ($tratamiento->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tratamiento_grid->RowCnt ?>_tratamiento_id_tipotratamientoaudiologia" class="form-group tratamiento_id_tipotratamientoaudiologia">
<select data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" data-value-separator="<?php echo $tratamiento->id_tipotratamientoaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" name="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia"<?php echo $tratamiento->id_tipotratamientoaudiologia->EditAttributes() ?>>
<?php echo $tratamiento->id_tipotratamientoaudiologia->SelectOptionListHtml("x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipotratamientoaudiologia") && !$tratamiento->id_tipotratamientoaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $tratamiento->id_tipotratamientoaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia',url:'tipotratamientoaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $tratamiento->id_tipotratamientoaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($tratamiento->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $tratamiento_grid->RowCnt ?>_tratamiento_id_tipotratamientoaudiologia" class="tratamiento_id_tipotratamientoaudiologia">
<span<?php echo $tratamiento->id_tipotratamientoaudiologia->ViewAttributes() ?>>
<?php echo $tratamiento->id_tipotratamientoaudiologia->ListViewValue() ?></span>
</span>
<?php if ($tratamiento->CurrentAction <> "F") { ?>
<input type="hidden" data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" name="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" id="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" value="<?php echo ew_HtmlEncode($tratamiento->id_tipotratamientoaudiologia->FormValue) ?>">
<input type="hidden" data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" name="o<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" id="o<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" value="<?php echo ew_HtmlEncode($tratamiento->id_tipotratamientoaudiologia->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" name="ftratamientogrid$x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" id="ftratamientogrid$x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" value="<?php echo ew_HtmlEncode($tratamiento->id_tipotratamientoaudiologia->FormValue) ?>">
<input type="hidden" data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" name="ftratamientogrid$o<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" id="ftratamientogrid$o<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" value="<?php echo ew_HtmlEncode($tratamiento->id_tipotratamientoaudiologia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php if ($tratamiento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="tratamiento" data-field="x_id" name="x<?php echo $tratamiento_grid->RowIndex ?>_id" id="x<?php echo $tratamiento_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tratamiento->id->CurrentValue) ?>">
<input type="hidden" data-table="tratamiento" data-field="x_id" name="o<?php echo $tratamiento_grid->RowIndex ?>_id" id="o<?php echo $tratamiento_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tratamiento->id->OldValue) ?>">
<?php } ?>
<?php if ($tratamiento->RowType == EW_ROWTYPE_EDIT || $tratamiento->CurrentMode == "edit") { ?>
<input type="hidden" data-table="tratamiento" data-field="x_id" name="x<?php echo $tratamiento_grid->RowIndex ?>_id" id="x<?php echo $tratamiento_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tratamiento->id->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$tratamiento_grid->ListOptions->Render("body", "right", $tratamiento_grid->RowCnt);
?>
	</tr>
<?php if ($tratamiento->RowType == EW_ROWTYPE_ADD || $tratamiento->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftratamientogrid.UpdateOpts(<?php echo $tratamiento_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($tratamiento->CurrentAction <> "gridadd" || $tratamiento->CurrentMode == "copy")
		if (!$tratamiento_grid->Recordset->EOF) $tratamiento_grid->Recordset->MoveNext();
}
?>
<?php
	if ($tratamiento->CurrentMode == "add" || $tratamiento->CurrentMode == "copy" || $tratamiento->CurrentMode == "edit") {
		$tratamiento_grid->RowIndex = '$rowindex$';
		$tratamiento_grid->LoadRowValues();

		// Set row properties
		$tratamiento->ResetAttrs();
		$tratamiento->RowAttrs = array_merge($tratamiento->RowAttrs, array('data-rowindex'=>$tratamiento_grid->RowIndex, 'id'=>'r0_tratamiento', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($tratamiento->RowAttrs["class"], "ewTemplate");
		$tratamiento->RowType = EW_ROWTYPE_ADD;

		// Render row
		$tratamiento_grid->RenderRow();

		// Render list options
		$tratamiento_grid->RenderListOptions();
		$tratamiento_grid->StartRowCnt = 0;
?>
	<tr<?php echo $tratamiento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tratamiento_grid->ListOptions->Render("body", "left", $tratamiento_grid->RowIndex);
?>
	<?php if ($tratamiento->id_tipotratamientoaudiologia->Visible) { // id_tipotratamientoaudiologia ?>
		<td data-name="id_tipotratamientoaudiologia">
<?php if ($tratamiento->CurrentAction <> "F") { ?>
<span id="el$rowindex$_tratamiento_id_tipotratamientoaudiologia" class="form-group tratamiento_id_tipotratamientoaudiologia">
<select data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" data-value-separator="<?php echo $tratamiento->id_tipotratamientoaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" name="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia"<?php echo $tratamiento->id_tipotratamientoaudiologia->EditAttributes() ?>>
<?php echo $tratamiento->id_tipotratamientoaudiologia->SelectOptionListHtml("x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipotratamientoaudiologia") && !$tratamiento->id_tipotratamientoaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $tratamiento->id_tipotratamientoaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia',url:'tipotratamientoaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $tratamiento->id_tipotratamientoaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_tratamiento_id_tipotratamientoaudiologia" class="form-group tratamiento_id_tipotratamientoaudiologia">
<span<?php echo $tratamiento->id_tipotratamientoaudiologia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tratamiento->id_tipotratamientoaudiologia->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" name="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" id="x<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" value="<?php echo ew_HtmlEncode($tratamiento->id_tipotratamientoaudiologia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="tratamiento" data-field="x_id_tipotratamientoaudiologia" name="o<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" id="o<?php echo $tratamiento_grid->RowIndex ?>_id_tipotratamientoaudiologia" value="<?php echo ew_HtmlEncode($tratamiento->id_tipotratamientoaudiologia->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tratamiento_grid->ListOptions->Render("body", "right", $tratamiento_grid->RowIndex);
?>
<script type="text/javascript">
ftratamientogrid.UpdateOpts(<?php echo $tratamiento_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($tratamiento->CurrentMode == "add" || $tratamiento->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $tratamiento_grid->FormKeyCountName ?>" id="<?php echo $tratamiento_grid->FormKeyCountName ?>" value="<?php echo $tratamiento_grid->KeyCount ?>">
<?php echo $tratamiento_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tratamiento->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $tratamiento_grid->FormKeyCountName ?>" id="<?php echo $tratamiento_grid->FormKeyCountName ?>" value="<?php echo $tratamiento_grid->KeyCount ?>">
<?php echo $tratamiento_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tratamiento->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ftratamientogrid">
</div>
<?php

// Close recordset
if ($tratamiento_grid->Recordset)
	$tratamiento_grid->Recordset->Close();
?>
<?php if ($tratamiento_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($tratamiento_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($tratamiento_grid->TotalRecs == 0 && $tratamiento->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tratamiento_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($tratamiento->Export == "") { ?>
<script type="text/javascript">
ftratamientogrid.Init();
</script>
<?php } ?>
<?php
$tratamiento_grid->Page_Terminate();
?>
