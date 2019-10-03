<?php

// Global variable for table object
$viewactividad = NULL;

//
// Table class for viewactividad
//
class crviewactividad extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $sector;
	var $tipoactividad;
	var $organizador;
	var $nombreactividad;
	var $nombrelocal;
	var $direccionlocal;
	var $fecha_inicio;
	var $fecha_fin;
	var $horasprogramadas;
	var $perosnanombre;
	var $personaapellidomaterno;
	var $personaapellidopaterno;
	var $contenido;
	var $observaciones;
	var $nombreinstitucion;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewactividad';
		$this->TableName = 'viewactividad';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// sector
		$this->sector = new crField('viewactividad', 'viewactividad', 'x_sector', 'sector', '`sector`', 200, EWR_DATATYPE_STRING, -1);
		$this->sector->Sortable = TRUE; // Allow sort
		$this->sector->DateFilter = "";
		$this->sector->SqlSelect = "";
		$this->sector->SqlOrderBy = "";
		$this->fields['sector'] = &$this->sector;

		// tipoactividad
		$this->tipoactividad = new crField('viewactividad', 'viewactividad', 'x_tipoactividad', 'tipoactividad', '`tipoactividad`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipoactividad->Sortable = TRUE; // Allow sort
		$this->tipoactividad->DateFilter = "";
		$this->tipoactividad->SqlSelect = "";
		$this->tipoactividad->SqlOrderBy = "";
		$this->fields['tipoactividad'] = &$this->tipoactividad;

		// organizador
		$this->organizador = new crField('viewactividad', 'viewactividad', 'x_organizador', 'organizador', '`organizador`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->organizador->Sortable = TRUE; // Allow sort
		$this->organizador->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->organizador->DateFilter = "";
		$this->organizador->SqlSelect = "";
		$this->organizador->SqlOrderBy = "";
		$this->fields['organizador'] = &$this->organizador;

		// nombreactividad
		$this->nombreactividad = new crField('viewactividad', 'viewactividad', 'x_nombreactividad', 'nombreactividad', '`nombreactividad`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombreactividad->Sortable = TRUE; // Allow sort
		$this->nombreactividad->DateFilter = "";
		$this->nombreactividad->SqlSelect = "";
		$this->nombreactividad->SqlOrderBy = "";
		$this->fields['nombreactividad'] = &$this->nombreactividad;

		// nombrelocal
		$this->nombrelocal = new crField('viewactividad', 'viewactividad', 'x_nombrelocal', 'nombrelocal', '`nombrelocal`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombrelocal->Sortable = TRUE; // Allow sort
		$this->nombrelocal->DateFilter = "";
		$this->nombrelocal->SqlSelect = "";
		$this->nombrelocal->SqlOrderBy = "";
		$this->fields['nombrelocal'] = &$this->nombrelocal;

		// direccionlocal
		$this->direccionlocal = new crField('viewactividad', 'viewactividad', 'x_direccionlocal', 'direccionlocal', '`direccionlocal`', 200, EWR_DATATYPE_STRING, -1);
		$this->direccionlocal->Sortable = TRUE; // Allow sort
		$this->direccionlocal->DateFilter = "";
		$this->direccionlocal->SqlSelect = "";
		$this->direccionlocal->SqlOrderBy = "";
		$this->fields['direccionlocal'] = &$this->direccionlocal;

		// fecha_inicio
		$this->fecha_inicio = new crField('viewactividad', 'viewactividad', 'x_fecha_inicio', 'fecha_inicio', '`fecha_inicio`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_inicio->Sortable = TRUE; // Allow sort
		$this->fecha_inicio->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_inicio->DateFilter = "";
		$this->fecha_inicio->SqlSelect = "";
		$this->fecha_inicio->SqlOrderBy = "";
		$this->fields['fecha_inicio'] = &$this->fecha_inicio;

		// fecha_fin
		$this->fecha_fin = new crField('viewactividad', 'viewactividad', 'x_fecha_fin', 'fecha_fin', '`fecha_fin`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_fin->Sortable = TRUE; // Allow sort
		$this->fecha_fin->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_fin->DateFilter = "";
		$this->fecha_fin->SqlSelect = "";
		$this->fecha_fin->SqlOrderBy = "";
		$this->fields['fecha_fin'] = &$this->fecha_fin;

		// horasprogramadas
		$this->horasprogramadas = new crField('viewactividad', 'viewactividad', 'x_horasprogramadas', 'horasprogramadas', '`horasprogramadas`', 200, EWR_DATATYPE_STRING, -1);
		$this->horasprogramadas->Sortable = TRUE; // Allow sort
		$this->horasprogramadas->DateFilter = "";
		$this->horasprogramadas->SqlSelect = "";
		$this->horasprogramadas->SqlOrderBy = "";
		$this->fields['horasprogramadas'] = &$this->horasprogramadas;

		// perosnanombre
		$this->perosnanombre = new crField('viewactividad', 'viewactividad', 'x_perosnanombre', 'perosnanombre', '`perosnanombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->perosnanombre->Sortable = TRUE; // Allow sort
		$this->perosnanombre->DateFilter = "";
		$this->perosnanombre->SqlSelect = "";
		$this->perosnanombre->SqlOrderBy = "";
		$this->fields['perosnanombre'] = &$this->perosnanombre;

		// personaapellidomaterno
		$this->personaapellidomaterno = new crField('viewactividad', 'viewactividad', 'x_personaapellidomaterno', 'personaapellidomaterno', '`personaapellidomaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->personaapellidomaterno->Sortable = TRUE; // Allow sort
		$this->personaapellidomaterno->DateFilter = "";
		$this->personaapellidomaterno->SqlSelect = "";
		$this->personaapellidomaterno->SqlOrderBy = "";
		$this->fields['personaapellidomaterno'] = &$this->personaapellidomaterno;

		// personaapellidopaterno
		$this->personaapellidopaterno = new crField('viewactividad', 'viewactividad', 'x_personaapellidopaterno', 'personaapellidopaterno', '`personaapellidopaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->personaapellidopaterno->Sortable = TRUE; // Allow sort
		$this->personaapellidopaterno->DateFilter = "";
		$this->personaapellidopaterno->SqlSelect = "";
		$this->personaapellidopaterno->SqlOrderBy = "";
		$this->fields['personaapellidopaterno'] = &$this->personaapellidopaterno;

		// contenido
		$this->contenido = new crField('viewactividad', 'viewactividad', 'x_contenido', 'contenido', '`contenido`', 200, EWR_DATATYPE_STRING, -1);
		$this->contenido->Sortable = TRUE; // Allow sort
		$this->contenido->DateFilter = "";
		$this->contenido->SqlSelect = "";
		$this->contenido->SqlOrderBy = "";
		$this->fields['contenido'] = &$this->contenido;

		// observaciones
		$this->observaciones = new crField('viewactividad', 'viewactividad', 'x_observaciones', 'observaciones', '`observaciones`', 200, EWR_DATATYPE_STRING, -1);
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->observaciones->DateFilter = "";
		$this->observaciones->SqlSelect = "";
		$this->observaciones->SqlOrderBy = "";
		$this->fields['observaciones'] = &$this->observaciones;

		// nombreinstitucion
		$this->nombreinstitucion = new crField('viewactividad', 'viewactividad', 'x_nombreinstitucion', 'nombreinstitucion', '`nombreinstitucion`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombreinstitucion->Sortable = TRUE; // Allow sort
		$this->nombreinstitucion->DateFilter = "";
		$this->nombreinstitucion->SqlSelect = "";
		$this->nombreinstitucion->SqlOrderBy = "";
		$this->fields['nombreinstitucion'] = &$this->nombreinstitucion;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewactividad`";
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
