<?php

// Global variable for table object
$unidadeducativa = NULL;

//
// Table class for unidadeducativa
//
class crunidadeducativa extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $id;
	var $nombre;
	var $codigo_sie;
	var $departamento;
	var $provincia;
	var $municipio;
	var $direccion;
	var $telefono;
	var $_email;
	var $id_persona;
	var $id_centro;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'unidadeducativa';
		$this->TableName = 'unidadeducativa';
		$this->TableType = 'TABLE';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// id
		$this->id = new crField('unidadeducativa', 'unidadeducativa', 'x_id', 'id', '`id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id->DateFilter = "";
		$this->id->SqlSelect = "";
		$this->id->SqlOrderBy = "";
		$this->fields['id'] = &$this->id;

		// nombre
		$this->nombre = new crField('unidadeducativa', 'unidadeducativa', 'x_nombre', 'nombre', '`nombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->nombre->DateFilter = "";
		$this->nombre->SqlSelect = "";
		$this->nombre->SqlOrderBy = "";
		$this->fields['nombre'] = &$this->nombre;

		// codigo_sie
		$this->codigo_sie = new crField('unidadeducativa', 'unidadeducativa', 'x_codigo_sie', 'codigo_sie', '`codigo_sie`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigo_sie->Sortable = TRUE; // Allow sort
		$this->codigo_sie->DateFilter = "";
		$this->codigo_sie->SqlSelect = "";
		$this->codigo_sie->SqlOrderBy = "";
		$this->fields['codigo_sie'] = &$this->codigo_sie;

		// departamento
		$this->departamento = new crField('unidadeducativa', 'unidadeducativa', 'x_departamento', 'departamento', '`departamento`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->departamento->Sortable = TRUE; // Allow sort
		$this->departamento->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->departamento->DateFilter = "";
		$this->departamento->SqlSelect = "";
		$this->departamento->SqlOrderBy = "";
		$this->fields['departamento'] = &$this->departamento;

		// provincia
		$this->provincia = new crField('unidadeducativa', 'unidadeducativa', 'x_provincia', 'provincia', '`provincia`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->provincia->Sortable = TRUE; // Allow sort
		$this->provincia->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->provincia->DateFilter = "";
		$this->provincia->SqlSelect = "";
		$this->provincia->SqlOrderBy = "";
		$this->fields['provincia'] = &$this->provincia;

		// municipio
		$this->municipio = new crField('unidadeducativa', 'unidadeducativa', 'x_municipio', 'municipio', '`municipio`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->municipio->Sortable = TRUE; // Allow sort
		$this->municipio->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->municipio->DateFilter = "";
		$this->municipio->SqlSelect = "";
		$this->municipio->SqlOrderBy = "";
		$this->fields['municipio'] = &$this->municipio;

		// direccion
		$this->direccion = new crField('unidadeducativa', 'unidadeducativa', 'x_direccion', 'direccion', '`direccion`', 200, EWR_DATATYPE_STRING, -1);
		$this->direccion->Sortable = TRUE; // Allow sort
		$this->direccion->DateFilter = "";
		$this->direccion->SqlSelect = "";
		$this->direccion->SqlOrderBy = "";
		$this->fields['direccion'] = &$this->direccion;

		// telefono
		$this->telefono = new crField('unidadeducativa', 'unidadeducativa', 'x_telefono', 'telefono', '`telefono`', 200, EWR_DATATYPE_STRING, -1);
		$this->telefono->Sortable = TRUE; // Allow sort
		$this->telefono->DateFilter = "";
		$this->telefono->SqlSelect = "";
		$this->telefono->SqlOrderBy = "";
		$this->fields['telefono'] = &$this->telefono;

		// email
		$this->_email = new crField('unidadeducativa', 'unidadeducativa', 'x__email', 'email', '`email`', 200, EWR_DATATYPE_STRING, -1);
		$this->_email->Sortable = TRUE; // Allow sort
		$this->_email->DateFilter = "";
		$this->_email->SqlSelect = "";
		$this->_email->SqlOrderBy = "";
		$this->fields['email'] = &$this->_email;

		// id_persona
		$this->id_persona = new crField('unidadeducativa', 'unidadeducativa', 'x_id_persona', 'id_persona', '`id_persona`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_persona->Sortable = TRUE; // Allow sort
		$this->id_persona->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_persona->DateFilter = "";
		$this->id_persona->SqlSelect = "";
		$this->id_persona->SqlOrderBy = "";
		$this->fields['id_persona'] = &$this->id_persona;

		// id_centro
		$this->id_centro = new crField('unidadeducativa', 'unidadeducativa', 'x_id_centro', 'id_centro', '`id_centro`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_centro->Sortable = TRUE; // Allow sort
		$this->id_centro->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_centro->DateFilter = "";
		$this->id_centro->SqlSelect = "";
		$this->id_centro->SqlOrderBy = "";
		$this->fields['id_centro'] = &$this->id_centro;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`unidadeducativa`";
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
		return "";
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
