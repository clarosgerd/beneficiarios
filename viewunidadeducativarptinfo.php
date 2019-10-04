<?php

// Global variable for table object
$viewunidadeducativa = NULL;

//
// Table class for viewunidadeducativa
//
class crviewunidadeducativa extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = FALSE;
	var $centro;
	var $unidadeducativa;
	var $codigo_sie;
	var $departamento;
	var $municipio;
	var $principio;
	var $direccion;
	var $telefono;
	var $_email;
	var $cantidad;
	var $sind;
	var $cond;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewunidadeducativa';
		$this->TableName = 'viewunidadeducativa';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// centro
		$this->centro = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_centro', 'centro', '`centro`', 200, EWR_DATATYPE_STRING, -1);
		$this->centro->Sortable = TRUE; // Allow sort
		$this->centro->DateFilter = "";
		$this->centro->SqlSelect = "";
		$this->centro->SqlOrderBy = "";
		$this->fields['centro'] = &$this->centro;

		// unidadeducativa
		$this->unidadeducativa = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_unidadeducativa', 'unidadeducativa', '`unidadeducativa`', 200, EWR_DATATYPE_STRING, -1);
		$this->unidadeducativa->Sortable = TRUE; // Allow sort
		$this->unidadeducativa->DateFilter = "";
		$this->unidadeducativa->SqlSelect = "";
		$this->unidadeducativa->SqlOrderBy = "";
		$this->fields['unidadeducativa'] = &$this->unidadeducativa;

		// codigo_sie
		$this->codigo_sie = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_codigo_sie', 'codigo_sie', '`codigo_sie`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigo_sie->Sortable = TRUE; // Allow sort
		$this->codigo_sie->DateFilter = "";
		$this->codigo_sie->SqlSelect = "";
		$this->codigo_sie->SqlOrderBy = "";
		$this->fields['codigo_sie'] = &$this->codigo_sie;

		// departamento
		$this->departamento = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_departamento', 'departamento', '`departamento`', 200, EWR_DATATYPE_STRING, -1);
		$this->departamento->Sortable = TRUE; // Allow sort
		$this->departamento->DateFilter = "";
		$this->departamento->SqlSelect = "";
		$this->departamento->SqlOrderBy = "";
		$this->fields['departamento'] = &$this->departamento;

		// municipio
		$this->municipio = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_municipio', 'municipio', '`municipio`', 200, EWR_DATATYPE_STRING, -1);
		$this->municipio->Sortable = TRUE; // Allow sort
		$this->municipio->DateFilter = "";
		$this->municipio->SqlSelect = "";
		$this->municipio->SqlOrderBy = "";
		$this->fields['municipio'] = &$this->municipio;

		// principio
		$this->principio = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_principio', 'principio', '`principio`', 200, EWR_DATATYPE_STRING, -1);
		$this->principio->Sortable = TRUE; // Allow sort
		$this->principio->DateFilter = "";
		$this->principio->SqlSelect = "";
		$this->principio->SqlOrderBy = "";
		$this->fields['principio'] = &$this->principio;

		// direccion
		$this->direccion = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_direccion', 'direccion', '`direccion`', 200, EWR_DATATYPE_STRING, -1);
		$this->direccion->Sortable = TRUE; // Allow sort
		$this->direccion->DateFilter = "";
		$this->direccion->SqlSelect = "";
		$this->direccion->SqlOrderBy = "";
		$this->fields['direccion'] = &$this->direccion;

		// telefono
		$this->telefono = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_telefono', 'telefono', '`telefono`', 200, EWR_DATATYPE_STRING, -1);
		$this->telefono->Sortable = TRUE; // Allow sort
		$this->telefono->DateFilter = "";
		$this->telefono->SqlSelect = "";
		$this->telefono->SqlOrderBy = "";
		$this->fields['telefono'] = &$this->telefono;

		// email
		$this->_email = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x__email', 'email', '`email`', 200, EWR_DATATYPE_STRING, -1);
		$this->_email->Sortable = TRUE; // Allow sort
		$this->_email->DateFilter = "";
		$this->_email->SqlSelect = "";
		$this->_email->SqlOrderBy = "";
		$this->fields['email'] = &$this->_email;

		// cantidad
		$this->cantidad = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_cantidad', 'cantidad', '`cantidad`', 20, EWR_DATATYPE_NUMBER, -1);
		$this->cantidad->Sortable = TRUE; // Allow sort
		$this->cantidad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->cantidad->DateFilter = "";
		$this->cantidad->SqlSelect = "";
		$this->cantidad->SqlOrderBy = "";
		$this->fields['cantidad'] = &$this->cantidad;

		// sind
		$this->sind = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_sind', 'sind', '`sind`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->sind->Sortable = TRUE; // Allow sort
		$this->sind->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->sind->DateFilter = "";
		$this->sind->SqlSelect = "";
		$this->sind->SqlOrderBy = "";
		$this->fields['sind'] = &$this->sind;

		// cond
		$this->cond = new crField('viewunidadeducativa', 'viewunidadeducativa', 'x_cond', 'cond', '`cond`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->cond->Sortable = TRUE; // Allow sort
		$this->cond->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->cond->DateFilter = "";
		$this->cond->SqlSelect = "";
		$this->cond->SqlOrderBy = "";
		$this->fields['cond'] = &$this->cond;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewunidadeducativa`";
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
