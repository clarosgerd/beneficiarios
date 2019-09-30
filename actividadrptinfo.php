<?php

// Global variable for table object
$actividad = NULL;

//
// Table class for actividad
//
class cractividad extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $id;
	var $id_sector;
	var $id_tipoactividad;
	var $organizador;
	var $nombreactividad;
	var $nombrelocal;
	var $direccionlocal;
	var $fecha_inicio;
	var $fecha_fin;
	var $horasprogramadas;
	var $id_persona;
	var $contenido;
	var $observaciones;
	var $id_centro;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'actividad';
		$this->TableName = 'actividad';
		$this->TableType = 'TABLE';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// id
		$this->id = new crField('actividad', 'actividad', 'x_id', 'id', '`id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id->DateFilter = "";
		$this->id->SqlSelect = "";
		$this->id->SqlOrderBy = "";
		$this->fields['id'] = &$this->id;

		// id_sector
		$this->id_sector = new crField('actividad', 'actividad', 'x_id_sector', 'id_sector', '`id_sector`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_sector->Sortable = TRUE; // Allow sort
		$this->id_sector->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_sector->DateFilter = "";
		$this->id_sector->SqlSelect = "";
		$this->id_sector->SqlOrderBy = "";
		$this->fields['id_sector'] = &$this->id_sector;

		// id_tipoactividad
		$this->id_tipoactividad = new crField('actividad', 'actividad', 'x_id_tipoactividad', 'id_tipoactividad', '`id_tipoactividad`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_tipoactividad->Sortable = TRUE; // Allow sort
		$this->id_tipoactividad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_tipoactividad->DateFilter = "";
		$this->id_tipoactividad->SqlSelect = "";
		$this->id_tipoactividad->SqlOrderBy = "";
		$this->fields['id_tipoactividad'] = &$this->id_tipoactividad;

		// organizador
		$this->organizador = new crField('actividad', 'actividad', 'x_organizador', 'organizador', '`organizador`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->organizador->Sortable = TRUE; // Allow sort
		$this->organizador->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->organizador->DateFilter = "";
		$this->organizador->SqlSelect = "";
		$this->organizador->SqlOrderBy = "";
		$this->fields['organizador'] = &$this->organizador;

		// nombreactividad
		$this->nombreactividad = new crField('actividad', 'actividad', 'x_nombreactividad', 'nombreactividad', '`nombreactividad`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombreactividad->Sortable = TRUE; // Allow sort
		$this->nombreactividad->DateFilter = "";
		$this->nombreactividad->SqlSelect = "";
		$this->nombreactividad->SqlOrderBy = "";
		$this->fields['nombreactividad'] = &$this->nombreactividad;

		// nombrelocal
		$this->nombrelocal = new crField('actividad', 'actividad', 'x_nombrelocal', 'nombrelocal', '`nombrelocal`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombrelocal->Sortable = TRUE; // Allow sort
		$this->nombrelocal->DateFilter = "";
		$this->nombrelocal->SqlSelect = "";
		$this->nombrelocal->SqlOrderBy = "";
		$this->fields['nombrelocal'] = &$this->nombrelocal;

		// direccionlocal
		$this->direccionlocal = new crField('actividad', 'actividad', 'x_direccionlocal', 'direccionlocal', '`direccionlocal`', 200, EWR_DATATYPE_STRING, -1);
		$this->direccionlocal->Sortable = TRUE; // Allow sort
		$this->direccionlocal->DateFilter = "";
		$this->direccionlocal->SqlSelect = "";
		$this->direccionlocal->SqlOrderBy = "";
		$this->fields['direccionlocal'] = &$this->direccionlocal;

		// fecha_inicio
		$this->fecha_inicio = new crField('actividad', 'actividad', 'x_fecha_inicio', 'fecha_inicio', '`fecha_inicio`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_inicio->Sortable = TRUE; // Allow sort
		$this->fecha_inicio->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_inicio->DateFilter = "";
		$this->fecha_inicio->SqlSelect = "";
		$this->fecha_inicio->SqlOrderBy = "";
		$this->fields['fecha_inicio'] = &$this->fecha_inicio;

		// fecha_fin
		$this->fecha_fin = new crField('actividad', 'actividad', 'x_fecha_fin', 'fecha_fin', '`fecha_fin`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_fin->Sortable = TRUE; // Allow sort
		$this->fecha_fin->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_fin->DateFilter = "";
		$this->fecha_fin->SqlSelect = "";
		$this->fecha_fin->SqlOrderBy = "";
		$this->fields['fecha_fin'] = &$this->fecha_fin;

		// horasprogramadas
		$this->horasprogramadas = new crField('actividad', 'actividad', 'x_horasprogramadas', 'horasprogramadas', '`horasprogramadas`', 200, EWR_DATATYPE_STRING, -1);
		$this->horasprogramadas->Sortable = TRUE; // Allow sort
		$this->horasprogramadas->DateFilter = "";
		$this->horasprogramadas->SqlSelect = "";
		$this->horasprogramadas->SqlOrderBy = "";
		$this->fields['horasprogramadas'] = &$this->horasprogramadas;

		// id_persona
		$this->id_persona = new crField('actividad', 'actividad', 'x_id_persona', 'id_persona', '`id_persona`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_persona->Sortable = TRUE; // Allow sort
		$this->id_persona->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_persona->DateFilter = "";
		$this->id_persona->SqlSelect = "";
		$this->id_persona->SqlOrderBy = "";
		$this->fields['id_persona'] = &$this->id_persona;

		// contenido
		$this->contenido = new crField('actividad', 'actividad', 'x_contenido', 'contenido', '`contenido`', 200, EWR_DATATYPE_STRING, -1);
		$this->contenido->Sortable = TRUE; // Allow sort
		$this->contenido->DateFilter = "";
		$this->contenido->SqlSelect = "";
		$this->contenido->SqlOrderBy = "";
		$this->fields['contenido'] = &$this->contenido;

		// observaciones
		$this->observaciones = new crField('actividad', 'actividad', 'x_observaciones', 'observaciones', '`observaciones`', 200, EWR_DATATYPE_STRING, -1);
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->observaciones->DateFilter = "";
		$this->observaciones->SqlSelect = "";
		$this->observaciones->SqlOrderBy = "";
		$this->fields['observaciones'] = &$this->observaciones;

		// id_centro
		$this->id_centro = new crField('actividad', 'actividad', 'x_id_centro', 'id_centro', '`id_centro`', 3, EWR_DATATYPE_NUMBER, -1);
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`actividad`";
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
