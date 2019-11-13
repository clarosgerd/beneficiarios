<?php

// Global variable for table object
$viewestudiantesetareo = NULL;

//
// Table class for viewestudiantesetareo
//
class crviewestudiantesetareo extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = FALSE;
	var $chardemo;
	var $unidadeducativa;
	var $_0_3F;
	var $_4_6F;
	var $_7_9F;
	var $_10_12F;
	var $_13_15F;
	var $_16_18F;
	var $_19F;
	var $_0_3M;
	var $_4_6M;
	var $_7_9M;
	var $_10_12M;
	var $_13_15M;
	var $_16_18M;
	var $_19M;
	var $fecha;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewestudiantesetareo';
		$this->TableName = 'viewestudiantesetareo';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// unidadeducativa
		$this->unidadeducativa = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x_unidadeducativa', 'unidadeducativa', '`unidadeducativa`', 200, EWR_DATATYPE_STRING, -1);
		$this->unidadeducativa->Sortable = TRUE; // Allow sort
		$this->unidadeducativa->DateFilter = "";
		$this->unidadeducativa->SqlSelect = "";
		$this->unidadeducativa->SqlOrderBy = "";
		$this->fields['unidadeducativa'] = &$this->unidadeducativa;

		// 0-3F
		$this->_0_3F = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__0_3F', '0-3F', '`0-3F`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_0_3F->Sortable = TRUE; // Allow sort
		$this->_0_3F->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_0_3F->DateFilter = "";
		$this->_0_3F->SqlSelect = "";
		$this->_0_3F->SqlOrderBy = "";
		$this->fields['0-3F'] = &$this->_0_3F;

		// 4-6F
		$this->_4_6F = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__4_6F', '4-6F', '`4-6F`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_4_6F->Sortable = TRUE; // Allow sort
		$this->_4_6F->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_4_6F->DateFilter = "";
		$this->_4_6F->SqlSelect = "";
		$this->_4_6F->SqlOrderBy = "";
		$this->fields['4-6F'] = &$this->_4_6F;

		// 7-9F
		$this->_7_9F = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__7_9F', '7-9F', '`7-9F`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_7_9F->Sortable = TRUE; // Allow sort
		$this->_7_9F->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_7_9F->DateFilter = "";
		$this->_7_9F->SqlSelect = "";
		$this->_7_9F->SqlOrderBy = "";
		$this->fields['7-9F'] = &$this->_7_9F;

		// 10-12F
		$this->_10_12F = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__10_12F', '10-12F', '`10-12F`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_10_12F->Sortable = TRUE; // Allow sort
		$this->_10_12F->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_10_12F->DateFilter = "";
		$this->_10_12F->SqlSelect = "";
		$this->_10_12F->SqlOrderBy = "";
		$this->fields['10-12F'] = &$this->_10_12F;

		// 13-15F
		$this->_13_15F = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__13_15F', '13-15F', '`13-15F`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_13_15F->Sortable = TRUE; // Allow sort
		$this->_13_15F->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_13_15F->DateFilter = "";
		$this->_13_15F->SqlSelect = "";
		$this->_13_15F->SqlOrderBy = "";
		$this->fields['13-15F'] = &$this->_13_15F;

		// 16-18F
		$this->_16_18F = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__16_18F', '16-18F', '`16-18F`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_16_18F->Sortable = TRUE; // Allow sort
		$this->_16_18F->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_16_18F->DateFilter = "";
		$this->_16_18F->SqlSelect = "";
		$this->_16_18F->SqlOrderBy = "";
		$this->fields['16-18F'] = &$this->_16_18F;

		// 19F
		$this->_19F = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__19F', '19F', '`19F`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_19F->Sortable = TRUE; // Allow sort
		$this->_19F->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_19F->DateFilter = "";
		$this->_19F->SqlSelect = "";
		$this->_19F->SqlOrderBy = "";
		$this->fields['19F'] = &$this->_19F;

		// 0-3M
		$this->_0_3M = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__0_3M', '0-3M', '`0-3M`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_0_3M->Sortable = TRUE; // Allow sort
		$this->_0_3M->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_0_3M->DateFilter = "";
		$this->_0_3M->SqlSelect = "";
		$this->_0_3M->SqlOrderBy = "";
		$this->fields['0-3M'] = &$this->_0_3M;

		// 4-6M
		$this->_4_6M = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__4_6M', '4-6M', '`4-6M`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_4_6M->Sortable = TRUE; // Allow sort
		$this->_4_6M->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_4_6M->DateFilter = "";
		$this->_4_6M->SqlSelect = "";
		$this->_4_6M->SqlOrderBy = "";
		$this->fields['4-6M'] = &$this->_4_6M;

		// 7-9M
		$this->_7_9M = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__7_9M', '7-9M', '`7-9M`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_7_9M->Sortable = TRUE; // Allow sort
		$this->_7_9M->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_7_9M->DateFilter = "";
		$this->_7_9M->SqlSelect = "";
		$this->_7_9M->SqlOrderBy = "";
		$this->fields['7-9M'] = &$this->_7_9M;

		// 10-12M
		$this->_10_12M = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__10_12M', '10-12M', '`10-12M`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_10_12M->Sortable = TRUE; // Allow sort
		$this->_10_12M->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->_10_12M->DateFilter = "";
		$this->_10_12M->SqlSelect = "";
		$this->_10_12M->SqlOrderBy = "";
		$this->fields['10-12M'] = &$this->_10_12M;

		// 13-15M
		$this->_13_15M = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__13_15M', '13-15M', '`13-15M`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_13_15M->Sortable = TRUE; // Allow sort
		$this->_13_15M->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_13_15M->DateFilter = "";
		$this->_13_15M->SqlSelect = "";
		$this->_13_15M->SqlOrderBy = "";
		$this->fields['13-15M'] = &$this->_13_15M;

		// 16-18M
		$this->_16_18M = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__16_18M', '16-18M', '`16-18M`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_16_18M->Sortable = TRUE; // Allow sort
		$this->_16_18M->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_16_18M->DateFilter = "";
		$this->_16_18M->SqlSelect = "";
		$this->_16_18M->SqlOrderBy = "";
		$this->fields['16-18M'] = &$this->_16_18M;

		// 19M
		$this->_19M = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x__19M', '19M', '`19M`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->_19M->Sortable = TRUE; // Allow sort
		$this->_19M->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->_19M->DateFilter = "";
		$this->_19M->SqlSelect = "";
		$this->_19M->SqlOrderBy = "";
		$this->fields['19M'] = &$this->_19M;

		// fecha
		$this->fecha = new crField('viewestudiantesetareo', 'viewestudiantesetareo', 'x_fecha', 'fecha', '`fecha`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha->Sortable = FALSE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha->DateFilter = "";
		$this->fecha->SqlSelect = "SELECT DISTINCT `fecha`, `fecha` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fecha->SqlOrderBy = "`fecha`";
		$this->fields['fecha'] = &$this->fecha;

		// chardemo
		$this->chardemo = new crChart($this, 'chardemo', 'chardemo', 'unidadeducativa', '0-3F', 4111, '0-3F|4-6F|7-9F|10-12F|13-15F|16-18F|19F|0-3M|4-6M|7-9M|10-12M|13-15M|16-18M|19M', 1, 'SUM', 600, 500);
		$this->chardemo->ChartSeriesRenderAs = ',,,,,,,,,,,,,';
		$this->chardemo->ChartSortType = 0;
		$this->chardemo->ChartSortSeq = "";
		$this->chardemo->SqlSelect = "SELECT `unidadeducativa`, '', SUM(`0-3F`), SUM(`4-6F`), SUM(`7-9F`), SUM(`10-12F`), SUM(`13-15F`), SUM(`16-18F`), SUM(`19F`), SUM(`0-3M`), SUM(`4-6M`), SUM(`7-9M`), SUM(`10-12M`), SUM(`13-15M`), SUM(`16-18M`), SUM(`19M`) FROM ";
		$this->chardemo->SqlGroupBy = "`unidadeducativa`";
		$this->chardemo->SqlOrderBy = "";
		$this->chardemo->SeriesDateType = "";
		$this->chardemo->ID = "viewestudiantesetareo_chardemo"; // Chart ID
		$this->chardemo->SetChartParms(array(array("type", "4111", FALSE),
			array("seriestype", "1", FALSE)));  // Chart type / Chart series type
		$this->chardemo->SetChartParm("bgcolor", "FCFCFC", TRUE); // Background color
		$this->chardemo->SetChartParms(array(array("caption", $this->chardemo->ChartCaption()),
			array("xaxisname", $this->chardemo->ChartXAxisName()))); // Chart caption / X axis name
		$this->chardemo->SetChartParm("yaxisname", $this->chardemo->ChartYAxisName(), TRUE); // Y axis name
		$this->chardemo->SetChartParms(array(array("shownames", "1"),
			array("showvalues", "1"),
			array("showhovercap", "1"))); // Show names / Show values / Show hover
		$this->chardemo->SetChartParm("alpha", "50", FALSE); // Chart alpha
		$this->chardemo->SetChartParm("colorpalette", "#FF0000|#FF0080|#FF00FF|#8000FF|#FF8000|#FF3D3D|#7AFFFF|#0000FF|#FFFF00|#FF7A7A|#3DFFFF|#0080FF|#80FF00|#00FF00|#00FF80|#00FFFF", FALSE); // Chart color palette
		$this->chardemo->SetChartParms(array(array("showLimits", "1"),
	array("showDivLineValues", "1"),
	array("yAxisMinValue", "0"),
	array("yAxisMaxValue", "0"),
	array("exportMode", "auto"),
	array("showAlternateVGridColor", "0"),
	));
		$this->chardemo->ChartGridConfig = '{}';
		$this->chardemo->Trends[] = array(0, 0, "FF0000", "", 1, "0", "1", 100, "", "0", "0", 0, 0, "S");
		$this->chardemo->Trends[] = array(0, 0, "FF0000", "", 1, "0", "1", 100, "", "0", "0", 0, 0, "S");
		$this->chardemo->Trends[] = array(0, 0, "FF0000", "", 1, "0", "1", 100, "", "0", "0", 0, 0, "S");
		$this->chardemo->Trends[] = array(0, 0, "FF0000", "", 1, "0", "1", 100, "", "0", "0", 0, 0, "S");
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewestudiantesetareo`";
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
		case "x_unidadeducativa":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`unidadeducativa` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `unidadeducativa`, `unidadeducativa` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewestudiantesetareo`",
			"where" => $sWhereWrk,
			"orderby" => "`unidadeducativa` ASC"
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
