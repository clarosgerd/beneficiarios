<?php

// Global variable for table object
$viewestudiante = NULL;

//
// Table class for viewestudiante
//
class crviewestudiante extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = FALSE;
	var $codigorude;
	var $codigorude_es;
	var $ci;
	var $fechanacimiento;
	var $sexo;
	var $nrodiscapacidad;
	var $nombreinstitucion;
	var $departamento;
	var $municipio;
	var $provincia;
	var $unidadeducativa;
	var $nombre;
	var $materno;
	var $paterno;
	var $edad;
	var $discapacidad;
	var $tipodiscapcidad;
	var $observaciones;
	var $id_estudiante;
	var $curso;
	var $fecha;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewestudiante';
		$this->TableName = 'viewestudiante';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// codigorude
		$this->codigorude = new crField('viewestudiante', 'viewestudiante', 'x_codigorude', 'codigorude', '`codigorude`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigorude->Sortable = TRUE; // Allow sort
		$this->codigorude->DateFilter = "";
		$this->codigorude->SqlSelect = "";
		$this->codigorude->SqlOrderBy = "";
		$this->fields['codigorude'] = &$this->codigorude;

		// codigorude_es
		$this->codigorude_es = new crField('viewestudiante', 'viewestudiante', 'x_codigorude_es', 'codigorude_es', '`codigorude_es`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigorude_es->Sortable = TRUE; // Allow sort
		$this->codigorude_es->DateFilter = "";
		$this->codigorude_es->SqlSelect = "";
		$this->codigorude_es->SqlOrderBy = "";
		$this->fields['codigorude_es'] = &$this->codigorude_es;

		// ci
		$this->ci = new crField('viewestudiante', 'viewestudiante', 'x_ci', 'ci', '`ci`', 200, EWR_DATATYPE_STRING, -1);
		$this->ci->Sortable = TRUE; // Allow sort
		$this->ci->DateFilter = "";
		$this->ci->SqlSelect = "";
		$this->ci->SqlOrderBy = "";
		$this->fields['ci'] = &$this->ci;

		// fechanacimiento
		$this->fechanacimiento = new crField('viewestudiante', 'viewestudiante', 'x_fechanacimiento', 'fechanacimiento', '`fechanacimiento`', 133, EWR_DATATYPE_DATE, 0);
		$this->fechanacimiento->Sortable = TRUE; // Allow sort
		$this->fechanacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fechanacimiento->DateFilter = "";
		$this->fechanacimiento->SqlSelect = "";
		$this->fechanacimiento->SqlOrderBy = "";
		$this->fields['fechanacimiento'] = &$this->fechanacimiento;

		// sexo
		$this->sexo = new crField('viewestudiante', 'viewestudiante', 'x_sexo', 'sexo', '`sexo`', 200, EWR_DATATYPE_STRING, -1);
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->DateFilter = "";
		$this->sexo->SqlSelect = "";
		$this->sexo->SqlOrderBy = "";
		$this->fields['sexo'] = &$this->sexo;

		// nrodiscapacidad
		$this->nrodiscapacidad = new crField('viewestudiante', 'viewestudiante', 'x_nrodiscapacidad', 'nrodiscapacidad', '`nrodiscapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->nrodiscapacidad->Sortable = TRUE; // Allow sort
		$this->nrodiscapacidad->DateFilter = "";
		$this->nrodiscapacidad->SqlSelect = "";
		$this->nrodiscapacidad->SqlOrderBy = "";
		$this->fields['nrodiscapacidad'] = &$this->nrodiscapacidad;

		// nombreinstitucion
		$this->nombreinstitucion = new crField('viewestudiante', 'viewestudiante', 'x_nombreinstitucion', 'nombreinstitucion', '`nombreinstitucion`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombreinstitucion->Sortable = TRUE; // Allow sort
		$this->nombreinstitucion->DateFilter = "";
		$this->nombreinstitucion->SqlSelect = "";
		$this->nombreinstitucion->SqlOrderBy = "";
		$this->fields['nombreinstitucion'] = &$this->nombreinstitucion;

		// departamento
		$this->departamento = new crField('viewestudiante', 'viewestudiante', 'x_departamento', 'departamento', '`departamento`', 200, EWR_DATATYPE_STRING, -1);
		$this->departamento->Sortable = TRUE; // Allow sort
		$this->departamento->DateFilter = "";
		$this->departamento->SqlSelect = "";
		$this->departamento->SqlOrderBy = "";
		$this->fields['departamento'] = &$this->departamento;

		// municipio
		$this->municipio = new crField('viewestudiante', 'viewestudiante', 'x_municipio', 'municipio', '`municipio`', 200, EWR_DATATYPE_STRING, -1);
		$this->municipio->Sortable = TRUE; // Allow sort
		$this->municipio->DateFilter = "";
		$this->municipio->SqlSelect = "";
		$this->municipio->SqlOrderBy = "";
		$this->fields['municipio'] = &$this->municipio;

		// provincia
		$this->provincia = new crField('viewestudiante', 'viewestudiante', 'x_provincia', 'provincia', '`provincia`', 200, EWR_DATATYPE_STRING, -1);
		$this->provincia->Sortable = TRUE; // Allow sort
		$this->provincia->DateFilter = "";
		$this->provincia->SqlSelect = "";
		$this->provincia->SqlOrderBy = "";
		$this->fields['provincia'] = &$this->provincia;

		// unidadeducativa
		$this->unidadeducativa = new crField('viewestudiante', 'viewestudiante', 'x_unidadeducativa', 'unidadeducativa', '`unidadeducativa`', 200, EWR_DATATYPE_STRING, -1);
		$this->unidadeducativa->Sortable = TRUE; // Allow sort
		$this->unidadeducativa->DateFilter = "";
		$this->unidadeducativa->SqlSelect = "";
		$this->unidadeducativa->SqlOrderBy = "";
		$this->fields['unidadeducativa'] = &$this->unidadeducativa;

		// nombre
		$this->nombre = new crField('viewestudiante', 'viewestudiante', 'x_nombre', 'nombre', '`nombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->nombre->DateFilter = "";
		$this->nombre->SqlSelect = "";
		$this->nombre->SqlOrderBy = "";
		$this->fields['nombre'] = &$this->nombre;

		// materno
		$this->materno = new crField('viewestudiante', 'viewestudiante', 'x_materno', 'materno', '`materno`', 200, EWR_DATATYPE_STRING, -1);
		$this->materno->Sortable = TRUE; // Allow sort
		$this->materno->DateFilter = "";
		$this->materno->SqlSelect = "";
		$this->materno->SqlOrderBy = "";
		$this->fields['materno'] = &$this->materno;

		// paterno
		$this->paterno = new crField('viewestudiante', 'viewestudiante', 'x_paterno', 'paterno', '`paterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->paterno->Sortable = TRUE; // Allow sort
		$this->paterno->DateFilter = "";
		$this->paterno->SqlSelect = "";
		$this->paterno->SqlOrderBy = "";
		$this->fields['paterno'] = &$this->paterno;

		// edad
		$this->edad = new crField('viewestudiante', 'viewestudiante', 'x_edad', 'edad', '`edad`', 20, EWR_DATATYPE_NUMBER, -1);
		$this->edad->Sortable = TRUE; // Allow sort
		$this->edad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->edad->DateFilter = "";
		$this->edad->SqlSelect = "";
		$this->edad->SqlOrderBy = "";
		$this->fields['edad'] = &$this->edad;

		// discapacidad
		$this->discapacidad = new crField('viewestudiante', 'viewestudiante', 'x_discapacidad', 'discapacidad', '`discapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->discapacidad->DateFilter = "";
		$this->discapacidad->SqlSelect = "";
		$this->discapacidad->SqlOrderBy = "";
		$this->fields['discapacidad'] = &$this->discapacidad;

		// tipodiscapcidad
		$this->tipodiscapcidad = new crField('viewestudiante', 'viewestudiante', 'x_tipodiscapcidad', 'tipodiscapcidad', '`tipodiscapcidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipodiscapcidad->Sortable = TRUE; // Allow sort
		$this->tipodiscapcidad->DateFilter = "";
		$this->tipodiscapcidad->SqlSelect = "";
		$this->tipodiscapcidad->SqlOrderBy = "";
		$this->fields['tipodiscapcidad'] = &$this->tipodiscapcidad;

		// observaciones
		$this->observaciones = new crField('viewestudiante', 'viewestudiante', 'x_observaciones', 'observaciones', '`observaciones`', 200, EWR_DATATYPE_STRING, -1);
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->observaciones->DateFilter = "";
		$this->observaciones->SqlSelect = "";
		$this->observaciones->SqlOrderBy = "";
		$this->fields['observaciones'] = &$this->observaciones;

		// id_estudiante
		$this->id_estudiante = new crField('viewestudiante', 'viewestudiante', 'x_id_estudiante', 'id_estudiante', '`id_estudiante`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_estudiante->Sortable = FALSE; // Allow sort
		$this->id_estudiante->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_estudiante->DateFilter = "";
		$this->id_estudiante->SqlSelect = "";
		$this->id_estudiante->SqlOrderBy = "";
		$this->fields['id_estudiante'] = &$this->id_estudiante;

		// curso
		$this->curso = new crField('viewestudiante', 'viewestudiante', 'x_curso', 'curso', '`curso`', 200, EWR_DATATYPE_STRING, -1);
		$this->curso->Sortable = TRUE; // Allow sort
		$this->curso->DateFilter = "";
		$this->curso->SqlSelect = "";
		$this->curso->SqlOrderBy = "";
		$this->fields['curso'] = &$this->curso;

		// fecha
		$this->fecha = new crField('viewestudiante', 'viewestudiante', 'x_fecha', 'fecha', '`fecha`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha->DateFilter = "";
		$this->fecha->SqlSelect = "";
		$this->fecha->SqlOrderBy = "";
		ewr_RegisterFilter($this->fecha, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->fecha, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->fecha, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		$this->fields['fecha'] = &$this->fecha;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewestudiante`";
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
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT COUNT(*) AS `cnt_discapacidad` FROM " . $this->getSqlFrom();
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
		case "x_sexo":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`sexo` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "{filter}";
		$fld->LookupFilters += array(
			"dx1" => '`sexo`',
			"select" => "SELECT DISTINCT `sexo`, `sexo` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewestudiante`",
			"where" => $sWhereWrk,
			"orderby" => "`sexo` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_departamento":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`departamento` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "{filter}";
		$fld->LookupFilters += array(
			"dx1" => '`departamento`',
			"select" => "SELECT DISTINCT `departamento`, `departamento` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewestudiante`",
			"where" => $sWhereWrk,
			"orderby" => "`departamento` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_curso":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`curso` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `curso`, `curso` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewestudiante`",
			"where" => $sWhereWrk,
			"orderby" => "`curso` ASC"
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
