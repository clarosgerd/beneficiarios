<?php

// Global variable for table object
$viewsaludmarcologicosummary = NULL;

//
// Table class for viewsaludmarcologicosummary
//
class crviewsaludmarcologicosummary extends crTableBase {
	var $ShowGroupHeaderAsRow = TRUE;
	var $ShowCompactSummaryFooter = FALSE;
	var $nombreinstitucion;
	var $fecha;
	var $cuadro1;
	var $cuadro2;
	var $cuadro3;
	var $cuadro4;
	var $cuadro5;
	var $cuadro6;
	var $cuadro7;
	var $cuadro8;
	var $cuadro9;
	var $cuadro10;
	var $cuadro11;
	var $cuadro12;
	var $cuadro13;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewsaludmarcologicosummary';
		$this->TableName = 'viewsaludmarcologicosummary';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// nombreinstitucion
		$this->nombreinstitucion = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_nombreinstitucion', 'nombreinstitucion', '`nombreinstitucion`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombreinstitucion->Sortable = TRUE; // Allow sort
		$this->nombreinstitucion->DateFilter = "";
		$this->nombreinstitucion->SqlSelect = "SELECT DISTINCT `nombreinstitucion`, `nombreinstitucion` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->nombreinstitucion->SqlOrderBy = "`nombreinstitucion`";
		$this->fields['nombreinstitucion'] = &$this->nombreinstitucion;

		// fecha
		$this->fecha = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_fecha', 'fecha', '`fecha`', 3, EWR_DATATYPE_NUMBER, 0);
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->DateFilter = "";
		$this->fecha->SqlSelect = "SELECT DISTINCT `fecha`, `fecha` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fecha->SqlOrderBy = "`fecha`";
		ewr_RegisterFilter($this->fecha, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->fecha, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->fecha, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		$this->fields['fecha'] = &$this->fecha;

		// cuadro1
		$this->cuadro1 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro1', 'cuadro1', '`cuadro1`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro1->Sortable = TRUE; // Allow sort
		$this->cuadro1->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro1->DateFilter = "";
		$this->cuadro1->SqlSelect = "";
		$this->cuadro1->SqlOrderBy = "";
		$this->fields['cuadro1'] = &$this->cuadro1;

		// cuadro2
		$this->cuadro2 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro2', 'cuadro2', '`cuadro2`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro2->Sortable = TRUE; // Allow sort
		$this->cuadro2->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro2->DateFilter = "";
		$this->cuadro2->SqlSelect = "";
		$this->cuadro2->SqlOrderBy = "";
		$this->fields['cuadro2'] = &$this->cuadro2;

		// cuadro3
		$this->cuadro3 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro3', 'cuadro3', '`cuadro3`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro3->Sortable = TRUE; // Allow sort
		$this->cuadro3->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro3->DateFilter = "";
		$this->cuadro3->SqlSelect = "";
		$this->cuadro3->SqlOrderBy = "";
		$this->fields['cuadro3'] = &$this->cuadro3;

		// cuadro4
		$this->cuadro4 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro4', 'cuadro4', '`cuadro4`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro4->Sortable = TRUE; // Allow sort
		$this->cuadro4->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro4->DateFilter = "";
		$this->cuadro4->SqlSelect = "";
		$this->cuadro4->SqlOrderBy = "";
		$this->fields['cuadro4'] = &$this->cuadro4;

		// cuadro5
		$this->cuadro5 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro5', 'cuadro5', '`cuadro5`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro5->Sortable = TRUE; // Allow sort
		$this->cuadro5->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro5->DateFilter = "";
		$this->cuadro5->SqlSelect = "";
		$this->cuadro5->SqlOrderBy = "";
		$this->fields['cuadro5'] = &$this->cuadro5;

		// cuadro6
		$this->cuadro6 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro6', 'cuadro6', '`cuadro6`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro6->Sortable = TRUE; // Allow sort
		$this->cuadro6->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro6->DateFilter = "";
		$this->cuadro6->SqlSelect = "";
		$this->cuadro6->SqlOrderBy = "";
		$this->fields['cuadro6'] = &$this->cuadro6;

		// cuadro7
		$this->cuadro7 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro7', 'cuadro7', '`cuadro7`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro7->Sortable = TRUE; // Allow sort
		$this->cuadro7->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro7->DateFilter = "";
		$this->cuadro7->SqlSelect = "";
		$this->cuadro7->SqlOrderBy = "";
		$this->fields['cuadro7'] = &$this->cuadro7;

		// cuadro8
		$this->cuadro8 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro8', 'cuadro8', '`cuadro8`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro8->Sortable = TRUE; // Allow sort
		$this->cuadro8->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro8->DateFilter = "";
		$this->cuadro8->SqlSelect = "";
		$this->cuadro8->SqlOrderBy = "";
		$this->fields['cuadro8'] = &$this->cuadro8;

		// cuadro9
		$this->cuadro9 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro9', 'cuadro9', '`cuadro9`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro9->Sortable = TRUE; // Allow sort
		$this->cuadro9->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro9->DateFilter = "";
		$this->cuadro9->SqlSelect = "";
		$this->cuadro9->SqlOrderBy = "";
		$this->fields['cuadro9'] = &$this->cuadro9;

		// cuadro10
		$this->cuadro10 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro10', 'cuadro10', '`cuadro10`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro10->Sortable = TRUE; // Allow sort
		$this->cuadro10->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cuadro10->DateFilter = "";
		$this->cuadro10->SqlSelect = "";
		$this->cuadro10->SqlOrderBy = "";
		$this->fields['cuadro10'] = &$this->cuadro10;

		// cuadro11
		$this->cuadro11 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro11', 'cuadro11', '`cuadro11`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro11->Sortable = TRUE; // Allow sort
		$this->cuadro11->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->cuadro11->DateFilter = "";
		$this->cuadro11->SqlSelect = "";
		$this->cuadro11->SqlOrderBy = "";
		$this->fields['cuadro11'] = &$this->cuadro11;

		// cuadro12
		$this->cuadro12 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro12', 'cuadro12', '`cuadro12`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro12->Sortable = TRUE; // Allow sort
		$this->cuadro12->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->cuadro12->DateFilter = "";
		$this->cuadro12->SqlSelect = "";
		$this->cuadro12->SqlOrderBy = "";
		$this->fields['cuadro12'] = &$this->cuadro12;

		// cuadro13
		$this->cuadro13 = new crField('viewsaludmarcologicosummary', 'viewsaludmarcologicosummary', 'x_cuadro13', 'cuadro13', '`cuadro13`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cuadro13->Sortable = TRUE; // Allow sort
		$this->cuadro13->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->cuadro13->DateFilter = "";
		$this->cuadro13->SqlSelect = "";
		$this->cuadro13->SqlOrderBy = "";
		$this->fields['cuadro13'] = &$this->cuadro13;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ofld->GroupingFieldId == 0)
				$this->setDetailOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			if ($ofld->GroupingFieldId == 0) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				$fldsql = $fld->FldExpression;
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	// From

	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewsaludmarcologicosummary`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}

	// Select
	var $_SqlSelect = "";

	function getSqlSelect() {
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}

	// Where
	var $_SqlWhere = "";

	function getSqlWhere() {
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}

	// Group By
	var $_SqlGroupBy = "";

	function getSqlGroupBy() {
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}

	// Having
	var $_SqlHaving = "";

	function getSqlHaving() {
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}

	// Order By
	var $_SqlOrderBy = "";

	function getSqlOrderBy() {
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Select Aggregate
	var $_SqlSelectAgg = "";

	function getSqlSelectAgg() {
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Aggregate Prefix
	var $_SqlAggPfx = "";

	function getSqlAggPfx() {
		return ($this->_SqlAggPfx <> "") ? $this->_SqlAggPfx : "";
	}

	function SqlAggPfx() { // For backward compatibility
		return $this->getSqlAggPfx();
	}

	function setSqlAggPfx($v) {
		$this->_SqlAggPfx = $v;
	}

	// Aggregate Suffix
	var $_SqlAggSfx = "";

	function getSqlAggSfx() {
		return ($this->_SqlAggSfx <> "") ? $this->_SqlAggSfx : "";
	}

	function SqlAggSfx() { // For backward compatibility
		return $this->getSqlAggSfx();
	}

	function setSqlAggSfx($v) {
		$this->_SqlAggSfx = $v;
	}

	// Select Count
	var $_SqlSelectCount = "";

	function getSqlSelectCount() {
		return ($this->_SqlSelectCount <> "") ? $this->_SqlSelectCount : "SELECT COUNT(*) FROM " . $this->getSqlFrom();
	}

	function SqlSelectCount() { // For backward compatibility
		return $this->getSqlSelectCount();
	}

	function setSqlSelectCount($v) {
		$this->_SqlSelectCount = $v;
	}

	// Get record count
	public function getRecordCount($sql)
	{
		$cnt = -1;
		$rs = NULL;
		$sql = preg_replace('/\/\*BeginOrderBy\*\/[\s\S]+\/\*EndOrderBy\*\//', "", $sql); // Remove ORDER BY clause (MSSQL)
		$pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';

		// Skip Custom View / SubQuery and SELECT DISTINCT
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
			preg_match($pattern, $sql) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sql) && !preg_match('/^\s*select\s+distinct\s+/i', $sql)) {
			$sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sql);
		} else {
			$sqlwrk = "SELECT COUNT(*) FROM (" . $sql . ") COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->execute($sqlwrk)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->close();
			}
			return (int)$cnt;
		}

		// Unable to get count, get record count directly
		if ($rs = $conn->execute($sql)) {
			$cnt = $rs->RecordCount();
			$rs->close();
			return (int)$cnt;
		}
		return $cnt;
	}

	// Sort URL
	function SortUrl(&$fld) {
		global $grDashboardReport;
		if ($this->Export <> "" || $grDashboardReport ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {

			//$sUrlParm = "order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort();
			$sUrlParm = "order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort();
			return ewr_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld) {
		global $grLanguage;
		switch ($fld->FldVar) {
		case "x_nombreinstitucion":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`nombreinstitucion` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `nombreinstitucion`, `nombreinstitucion` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewsaludmarcologicosummary`",
			"where" => $sWhereWrk,
			"orderby" => "`nombreinstitucion` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_fecha":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`fecha` = {filter_value}', "t0" => "3", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `fecha`, `fecha` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewsaludmarcologicosummary`",
			"where" => $sWhereWrk,
			"orderby" => "`fecha` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld) {
		global $grLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		//if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//	$filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}
}
?>
