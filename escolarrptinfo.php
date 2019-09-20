<?php

// Global variable for table object
$escolar = NULL;

//
// Table class for escolar
//
class crescolar extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $id;
	var $codigorude;
	var $codigorude_es;
	var $fecha;
	var $id_departamento;
	var $unidadeducativa;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nombres;
	var $ci;
	var $nrodiscapacidad;
	var $fechanacimiento;
	var $sexo;
	var $curso;
	var $id_discapacidad;
	var $id_tipodiscapacidad;
	var $resultado;
	var $resultadotamizaje;
	var $tapon;
	var $tapodonde;
	var $repetirprueba;
	var $observaciones;
	var $id_apoderado;
	var $id_referencia;
	var $id_centro;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'escolar';
		$this->TableName = 'escolar';
		$this->TableType = 'TABLE';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// id
		$this->id = new crField('escolar', 'escolar', 'x_id', 'id', '`id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id->DateFilter = "";
		$this->id->SqlSelect = "";
		$this->id->SqlOrderBy = "";
		$this->fields['id'] = &$this->id;

		// codigorude
		$this->codigorude = new crField('escolar', 'escolar', 'x_codigorude', 'codigorude', '`codigorude`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigorude->Sortable = TRUE; // Allow sort
		$this->codigorude->DateFilter = "";
		$this->codigorude->SqlSelect = "";
		$this->codigorude->SqlOrderBy = "";
		$this->fields['codigorude'] = &$this->codigorude;

		// codigorude_es
		$this->codigorude_es = new crField('escolar', 'escolar', 'x_codigorude_es', 'codigorude_es', '`codigorude_es`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigorude_es->Sortable = TRUE; // Allow sort
		$this->codigorude_es->DateFilter = "";
		$this->codigorude_es->SqlSelect = "";
		$this->codigorude_es->SqlOrderBy = "";
		$this->fields['codigorude_es'] = &$this->codigorude_es;

		// fecha
		$this->fecha = new crField('escolar', 'escolar', 'x_fecha', 'fecha', '`fecha`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha->DateFilter = "";
		$this->fecha->SqlSelect = "";
		$this->fecha->SqlOrderBy = "";
		$this->fields['fecha'] = &$this->fecha;

		// id_departamento
		$this->id_departamento = new crField('escolar', 'escolar', 'x_id_departamento', 'id_departamento', '`id_departamento`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_departamento->Sortable = TRUE; // Allow sort
		$this->id_departamento->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_departamento->DateFilter = "";
		$this->id_departamento->SqlSelect = "";
		$this->id_departamento->SqlOrderBy = "";
		$this->fields['id_departamento'] = &$this->id_departamento;

		// unidadeducativa
		$this->unidadeducativa = new crField('escolar', 'escolar', 'x_unidadeducativa', 'unidadeducativa', '`unidadeducativa`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->unidadeducativa->Sortable = TRUE; // Allow sort
		$this->unidadeducativa->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->unidadeducativa->DateFilter = "";
		$this->unidadeducativa->SqlSelect = "";
		$this->unidadeducativa->SqlOrderBy = "";
		$this->fields['unidadeducativa'] = &$this->unidadeducativa;

		// apellidopaterno
		$this->apellidopaterno = new crField('escolar', 'escolar', 'x_apellidopaterno', 'apellidopaterno', '`apellidopaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->apellidopaterno->DateFilter = "";
		$this->apellidopaterno->SqlSelect = "";
		$this->apellidopaterno->SqlOrderBy = "";
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new crField('escolar', 'escolar', 'x_apellidomaterno', 'apellidomaterno', '`apellidomaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->apellidomaterno->DateFilter = "";
		$this->apellidomaterno->SqlSelect = "";
		$this->apellidomaterno->SqlOrderBy = "";
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nombres
		$this->nombres = new crField('escolar', 'escolar', 'x_nombres', 'nombres', '`nombres`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombres->Sortable = TRUE; // Allow sort
		$this->nombres->DateFilter = "";
		$this->nombres->SqlSelect = "";
		$this->nombres->SqlOrderBy = "";
		$this->fields['nombres'] = &$this->nombres;

		// ci
		$this->ci = new crField('escolar', 'escolar', 'x_ci', 'ci', '`ci`', 200, EWR_DATATYPE_STRING, -1);
		$this->ci->Sortable = TRUE; // Allow sort
		$this->ci->DateFilter = "";
		$this->ci->SqlSelect = "";
		$this->ci->SqlOrderBy = "";
		$this->fields['ci'] = &$this->ci;

		// nrodiscapacidad
		$this->nrodiscapacidad = new crField('escolar', 'escolar', 'x_nrodiscapacidad', 'nrodiscapacidad', '`nrodiscapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->nrodiscapacidad->Sortable = TRUE; // Allow sort
		$this->nrodiscapacidad->DateFilter = "";
		$this->nrodiscapacidad->SqlSelect = "";
		$this->nrodiscapacidad->SqlOrderBy = "";
		$this->fields['nrodiscapacidad'] = &$this->nrodiscapacidad;

		// fechanacimiento
		$this->fechanacimiento = new crField('escolar', 'escolar', 'x_fechanacimiento', 'fechanacimiento', '`fechanacimiento`', 133, EWR_DATATYPE_DATE, 0);
		$this->fechanacimiento->Sortable = TRUE; // Allow sort
		$this->fechanacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fechanacimiento->DateFilter = "";
		$this->fechanacimiento->SqlSelect = "";
		$this->fechanacimiento->SqlOrderBy = "";
		$this->fields['fechanacimiento'] = &$this->fechanacimiento;

		// sexo
		$this->sexo = new crField('escolar', 'escolar', 'x_sexo', 'sexo', '`sexo`', 200, EWR_DATATYPE_STRING, -1);
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->DateFilter = "";
		$this->sexo->SqlSelect = "";
		$this->sexo->SqlOrderBy = "";
		$this->fields['sexo'] = &$this->sexo;

		// curso
		$this->curso = new crField('escolar', 'escolar', 'x_curso', 'curso', '`curso`', 200, EWR_DATATYPE_STRING, -1);
		$this->curso->Sortable = TRUE; // Allow sort
		$this->curso->DateFilter = "";
		$this->curso->SqlSelect = "";
		$this->curso->SqlOrderBy = "";
		$this->fields['curso'] = &$this->curso;

		// id_discapacidad
		$this->id_discapacidad = new crField('escolar', 'escolar', 'x_id_discapacidad', 'id_discapacidad', '`id_discapacidad`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_discapacidad->Sortable = TRUE; // Allow sort
		$this->id_discapacidad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_discapacidad->DateFilter = "";
		$this->id_discapacidad->SqlSelect = "";
		$this->id_discapacidad->SqlOrderBy = "";
		$this->fields['id_discapacidad'] = &$this->id_discapacidad;

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad = new crField('escolar', 'escolar', 'x_id_tipodiscapacidad', 'id_tipodiscapacidad', '`id_tipodiscapacidad`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_tipodiscapacidad->Sortable = TRUE; // Allow sort
		$this->id_tipodiscapacidad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_tipodiscapacidad->DateFilter = "";
		$this->id_tipodiscapacidad->SqlSelect = "";
		$this->id_tipodiscapacidad->SqlOrderBy = "";
		$this->fields['id_tipodiscapacidad'] = &$this->id_tipodiscapacidad;

		// resultado
		$this->resultado = new crField('escolar', 'escolar', 'x_resultado', 'resultado', '`resultado`', 200, EWR_DATATYPE_STRING, -1);
		$this->resultado->Sortable = TRUE; // Allow sort
		$this->resultado->DateFilter = "";
		$this->resultado->SqlSelect = "";
		$this->resultado->SqlOrderBy = "";
		$this->fields['resultado'] = &$this->resultado;

		// resultadotamizaje
		$this->resultadotamizaje = new crField('escolar', 'escolar', 'x_resultadotamizaje', 'resultadotamizaje', '`resultadotamizaje`', 200, EWR_DATATYPE_STRING, -1);
		$this->resultadotamizaje->Sortable = TRUE; // Allow sort
		$this->resultadotamizaje->DateFilter = "";
		$this->resultadotamizaje->SqlSelect = "";
		$this->resultadotamizaje->SqlOrderBy = "";
		$this->fields['resultadotamizaje'] = &$this->resultadotamizaje;

		// tapon
		$this->tapon = new crField('escolar', 'escolar', 'x_tapon', 'tapon', '`tapon`', 200, EWR_DATATYPE_STRING, -1);
		$this->tapon->Sortable = TRUE; // Allow sort
		$this->tapon->DateFilter = "";
		$this->tapon->SqlSelect = "";
		$this->tapon->SqlOrderBy = "";
		$this->fields['tapon'] = &$this->tapon;

		// tapodonde
		$this->tapodonde = new crField('escolar', 'escolar', 'x_tapodonde', 'tapodonde', '`tapodonde`', 200, EWR_DATATYPE_STRING, -1);
		$this->tapodonde->Sortable = TRUE; // Allow sort
		$this->tapodonde->DateFilter = "";
		$this->tapodonde->SqlSelect = "";
		$this->tapodonde->SqlOrderBy = "";
		$this->fields['tapodonde'] = &$this->tapodonde;

		// repetirprueba
		$this->repetirprueba = new crField('escolar', 'escolar', 'x_repetirprueba', 'repetirprueba', '`repetirprueba`', 200, EWR_DATATYPE_STRING, -1);
		$this->repetirprueba->Sortable = TRUE; // Allow sort
		$this->repetirprueba->DateFilter = "";
		$this->repetirprueba->SqlSelect = "";
		$this->repetirprueba->SqlOrderBy = "";
		$this->fields['repetirprueba'] = &$this->repetirprueba;

		// observaciones
		$this->observaciones = new crField('escolar', 'escolar', 'x_observaciones', 'observaciones', '`observaciones`', 200, EWR_DATATYPE_STRING, -1);
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->observaciones->DateFilter = "";
		$this->observaciones->SqlSelect = "";
		$this->observaciones->SqlOrderBy = "";
		$this->fields['observaciones'] = &$this->observaciones;

		// id_apoderado
		$this->id_apoderado = new crField('escolar', 'escolar', 'x_id_apoderado', 'id_apoderado', '`id_apoderado`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_apoderado->Sortable = TRUE; // Allow sort
		$this->id_apoderado->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_apoderado->DateFilter = "";
		$this->id_apoderado->SqlSelect = "";
		$this->id_apoderado->SqlOrderBy = "";
		$this->fields['id_apoderado'] = &$this->id_apoderado;

		// id_referencia
		$this->id_referencia = new crField('escolar', 'escolar', 'x_id_referencia', 'id_referencia', '`id_referencia`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_referencia->Sortable = TRUE; // Allow sort
		$this->id_referencia->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_referencia->DateFilter = "";
		$this->id_referencia->SqlSelect = "";
		$this->id_referencia->SqlOrderBy = "";
		$this->fields['id_referencia'] = &$this->id_referencia;

		// id_centro
		$this->id_centro = new crField('escolar', 'escolar', 'x_id_centro', 'id_centro', '`id_centro`', 3, EWR_DATATYPE_NUMBER, -1);
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`escolar`";
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
