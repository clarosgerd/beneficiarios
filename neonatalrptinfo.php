<?php

// Global variable for table object
$neonatal = NULL;

//
// Table class for neonatal
//
class crneonatal extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $id;
	var $fecha_tamizaje;
	var $id_centro;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nombre;
	var $ci;
	var $fecha_nacimiento;
	var $dias;
	var $semanas;
	var $meses;
	var $sexo;
	var $discapacidad;
	var $id_tipodiscapacidad;
	var $resultado;
	var $resultadotamizaje;
	var $tapon;
	var $tipo;
	var $repetirprueba;
	var $observaciones;
	var $id_apoderado;
	var $id_referencia;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'neonatal';
		$this->TableName = 'neonatal';
		$this->TableType = 'TABLE';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// id
		$this->id = new crField('neonatal', 'neonatal', 'x_id', 'id', '`id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id->DateFilter = "";
		$this->id->SqlSelect = "";
		$this->id->SqlOrderBy = "";
		$this->fields['id'] = &$this->id;

		// fecha_tamizaje
		$this->fecha_tamizaje = new crField('neonatal', 'neonatal', 'x_fecha_tamizaje', 'fecha_tamizaje', '`fecha_tamizaje`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_tamizaje->Sortable = TRUE; // Allow sort
		$this->fecha_tamizaje->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_tamizaje->DateFilter = "";
		$this->fecha_tamizaje->SqlSelect = "";
		$this->fecha_tamizaje->SqlOrderBy = "";
		$this->fields['fecha_tamizaje'] = &$this->fecha_tamizaje;

		// id_centro
		$this->id_centro = new crField('neonatal', 'neonatal', 'x_id_centro', 'id_centro', '`id_centro`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_centro->Sortable = TRUE; // Allow sort
		$this->id_centro->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_centro->DateFilter = "";
		$this->id_centro->SqlSelect = "";
		$this->id_centro->SqlOrderBy = "";
		$this->fields['id_centro'] = &$this->id_centro;

		// apellidopaterno
		$this->apellidopaterno = new crField('neonatal', 'neonatal', 'x_apellidopaterno', 'apellidopaterno', '`apellidopaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->apellidopaterno->DateFilter = "";
		$this->apellidopaterno->SqlSelect = "";
		$this->apellidopaterno->SqlOrderBy = "";
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new crField('neonatal', 'neonatal', 'x_apellidomaterno', 'apellidomaterno', '`apellidomaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->apellidomaterno->DateFilter = "";
		$this->apellidomaterno->SqlSelect = "";
		$this->apellidomaterno->SqlOrderBy = "";
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nombre
		$this->nombre = new crField('neonatal', 'neonatal', 'x_nombre', 'nombre', '`nombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->nombre->DateFilter = "";
		$this->nombre->SqlSelect = "";
		$this->nombre->SqlOrderBy = "";
		$this->fields['nombre'] = &$this->nombre;

		// ci
		$this->ci = new crField('neonatal', 'neonatal', 'x_ci', 'ci', '`ci`', 200, EWR_DATATYPE_STRING, -1);
		$this->ci->Sortable = TRUE; // Allow sort
		$this->ci->DateFilter = "";
		$this->ci->SqlSelect = "";
		$this->ci->SqlOrderBy = "";
		$this->fields['ci'] = &$this->ci;

		// fecha_nacimiento
		$this->fecha_nacimiento = new crField('neonatal', 'neonatal', 'x_fecha_nacimiento', 'fecha_nacimiento', '`fecha_nacimiento`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_nacimiento->Sortable = TRUE; // Allow sort
		$this->fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_nacimiento->DateFilter = "";
		$this->fecha_nacimiento->SqlSelect = "";
		$this->fecha_nacimiento->SqlOrderBy = "";
		$this->fields['fecha_nacimiento'] = &$this->fecha_nacimiento;

		// dias
		$this->dias = new crField('neonatal', 'neonatal', 'x_dias', 'dias', '`dias`', 200, EWR_DATATYPE_STRING, -1);
		$this->dias->Sortable = TRUE; // Allow sort
		$this->dias->DateFilter = "";
		$this->dias->SqlSelect = "";
		$this->dias->SqlOrderBy = "";
		$this->fields['dias'] = &$this->dias;

		// semanas
		$this->semanas = new crField('neonatal', 'neonatal', 'x_semanas', 'semanas', '`semanas`', 200, EWR_DATATYPE_STRING, -1);
		$this->semanas->Sortable = TRUE; // Allow sort
		$this->semanas->DateFilter = "";
		$this->semanas->SqlSelect = "";
		$this->semanas->SqlOrderBy = "";
		$this->fields['semanas'] = &$this->semanas;

		// meses
		$this->meses = new crField('neonatal', 'neonatal', 'x_meses', 'meses', '`meses`', 200, EWR_DATATYPE_STRING, -1);
		$this->meses->Sortable = TRUE; // Allow sort
		$this->meses->DateFilter = "";
		$this->meses->SqlSelect = "";
		$this->meses->SqlOrderBy = "";
		$this->fields['meses'] = &$this->meses;

		// sexo
		$this->sexo = new crField('neonatal', 'neonatal', 'x_sexo', 'sexo', '`sexo`', 200, EWR_DATATYPE_STRING, -1);
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->DateFilter = "";
		$this->sexo->SqlSelect = "";
		$this->sexo->SqlOrderBy = "";
		$this->fields['sexo'] = &$this->sexo;

		// discapacidad
		$this->discapacidad = new crField('neonatal', 'neonatal', 'x_discapacidad', 'discapacidad', '`discapacidad`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->discapacidad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->discapacidad->DateFilter = "";
		$this->discapacidad->SqlSelect = "";
		$this->discapacidad->SqlOrderBy = "";
		$this->fields['discapacidad'] = &$this->discapacidad;

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad = new crField('neonatal', 'neonatal', 'x_id_tipodiscapacidad', 'id_tipodiscapacidad', '`id_tipodiscapacidad`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_tipodiscapacidad->Sortable = TRUE; // Allow sort
		$this->id_tipodiscapacidad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_tipodiscapacidad->DateFilter = "";
		$this->id_tipodiscapacidad->SqlSelect = "";
		$this->id_tipodiscapacidad->SqlOrderBy = "";
		$this->fields['id_tipodiscapacidad'] = &$this->id_tipodiscapacidad;

		// resultado
		$this->resultado = new crField('neonatal', 'neonatal', 'x_resultado', 'resultado', '`resultado`', 200, EWR_DATATYPE_STRING, -1);
		$this->resultado->Sortable = TRUE; // Allow sort
		$this->resultado->DateFilter = "";
		$this->resultado->SqlSelect = "";
		$this->resultado->SqlOrderBy = "";
		$this->fields['resultado'] = &$this->resultado;

		// resultadotamizaje
		$this->resultadotamizaje = new crField('neonatal', 'neonatal', 'x_resultadotamizaje', 'resultadotamizaje', '`resultadotamizaje`', 200, EWR_DATATYPE_STRING, -1);
		$this->resultadotamizaje->Sortable = TRUE; // Allow sort
		$this->resultadotamizaje->DateFilter = "";
		$this->resultadotamizaje->SqlSelect = "";
		$this->resultadotamizaje->SqlOrderBy = "";
		$this->fields['resultadotamizaje'] = &$this->resultadotamizaje;

		// tapon
		$this->tapon = new crField('neonatal', 'neonatal', 'x_tapon', 'tapon', '`tapon`', 200, EWR_DATATYPE_STRING, -1);
		$this->tapon->Sortable = TRUE; // Allow sort
		$this->tapon->DateFilter = "";
		$this->tapon->SqlSelect = "";
		$this->tapon->SqlOrderBy = "";
		$this->fields['tapon'] = &$this->tapon;

		// tipo
		$this->tipo = new crField('neonatal', 'neonatal', 'x_tipo', 'tipo', '`tipo`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipo->Sortable = TRUE; // Allow sort
		$this->tipo->DateFilter = "";
		$this->tipo->SqlSelect = "";
		$this->tipo->SqlOrderBy = "";
		$this->fields['tipo'] = &$this->tipo;

		// repetirprueba
		$this->repetirprueba = new crField('neonatal', 'neonatal', 'x_repetirprueba', 'repetirprueba', '`repetirprueba`', 200, EWR_DATATYPE_STRING, -1);
		$this->repetirprueba->Sortable = TRUE; // Allow sort
		$this->repetirprueba->DateFilter = "";
		$this->repetirprueba->SqlSelect = "";
		$this->repetirprueba->SqlOrderBy = "";
		$this->fields['repetirprueba'] = &$this->repetirprueba;

		// observaciones
		$this->observaciones = new crField('neonatal', 'neonatal', 'x_observaciones', 'observaciones', '`observaciones`', 200, EWR_DATATYPE_STRING, -1);
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->observaciones->DateFilter = "";
		$this->observaciones->SqlSelect = "";
		$this->observaciones->SqlOrderBy = "";
		$this->fields['observaciones'] = &$this->observaciones;

		// id_apoderado
		$this->id_apoderado = new crField('neonatal', 'neonatal', 'x_id_apoderado', 'id_apoderado', '`id_apoderado`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_apoderado->Sortable = TRUE; // Allow sort
		$this->id_apoderado->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_apoderado->DateFilter = "";
		$this->id_apoderado->SqlSelect = "";
		$this->id_apoderado->SqlOrderBy = "";
		$this->fields['id_apoderado'] = &$this->id_apoderado;

		// id_referencia
		$this->id_referencia = new crField('neonatal', 'neonatal', 'x_id_referencia', 'id_referencia', '`id_referencia`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_referencia->Sortable = TRUE; // Allow sort
		$this->id_referencia->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_referencia->DateFilter = "";
		$this->id_referencia->SqlSelect = "";
		$this->id_referencia->SqlOrderBy = "";
		$this->fields['id_referencia'] = &$this->id_referencia;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`neonatal`";
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
