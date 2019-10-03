<?php

// Global variable for table object
$viewestudiantecurso = NULL;

//
// Table class for viewestudiantecurso
//
class crviewestudiantecurso extends crTableBase {
	var $ShowGroupHeaderAsRow = TRUE;
	var $ShowCompactSummaryFooter = TRUE;
	var $departamento;
	var $codigorude;
	var $codigorude_es;
	var $municipio;
	var $provincia;
	var $unidadeducativa;
	var $nombre;
	var $materno;
	var $paterno;
	var $nrodiscapacidad;
	var $ci;
	var $fechanacimiento;
	var $edad;
	var $sexo;
	var $curso;
	var $discapacidad;
	var $tipodiscapcidad;
	var $nombreinstitucion;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewestudiantecurso';
		$this->TableName = 'viewestudiantecurso';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// departamento
		$this->departamento = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_departamento', 'departamento', '`departamento`', 200, EWR_DATATYPE_STRING, -1);
		$this->departamento->Sortable = TRUE; // Allow sort
		$this->departamento->DateFilter = "";
		$this->departamento->SqlSelect = "SELECT DISTINCT `departamento`, `departamento` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->departamento->SqlOrderBy = "`departamento`";
		$this->fields['departamento'] = &$this->departamento;

		// codigorude
		$this->codigorude = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_codigorude', 'codigorude', '`codigorude`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigorude->Sortable = TRUE; // Allow sort
		$this->codigorude->DateFilter = "";
		$this->codigorude->SqlSelect = "SELECT DISTINCT `codigorude`, `codigorude` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->codigorude->SqlOrderBy = "`codigorude`";
		$this->fields['codigorude'] = &$this->codigorude;

		// codigorude_es
		$this->codigorude_es = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_codigorude_es', 'codigorude_es', '`codigorude_es`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigorude_es->Sortable = TRUE; // Allow sort
		$this->codigorude_es->DateFilter = "";
		$this->codigorude_es->SqlSelect = "SELECT DISTINCT `codigorude_es`, `codigorude_es` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->codigorude_es->SqlOrderBy = "`codigorude_es`";
		$this->fields['codigorude_es'] = &$this->codigorude_es;

		// municipio
		$this->municipio = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_municipio', 'municipio', '`municipio`', 200, EWR_DATATYPE_STRING, -1);
		$this->municipio->Sortable = TRUE; // Allow sort
		$this->municipio->DateFilter = "";
		$this->municipio->SqlSelect = "SELECT DISTINCT `municipio`, `municipio` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->municipio->SqlOrderBy = "`municipio`";
		$this->fields['municipio'] = &$this->municipio;

		// provincia
		$this->provincia = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_provincia', 'provincia', '`provincia`', 200, EWR_DATATYPE_STRING, -1);
		$this->provincia->Sortable = TRUE; // Allow sort
		$this->provincia->DateFilter = "";
		$this->provincia->SqlSelect = "SELECT DISTINCT `provincia`, `provincia` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->provincia->SqlOrderBy = "`provincia`";
		$this->fields['provincia'] = &$this->provincia;

		// unidadeducativa
		$this->unidadeducativa = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_unidadeducativa', 'unidadeducativa', '`unidadeducativa`', 200, EWR_DATATYPE_STRING, -1);
		$this->unidadeducativa->Sortable = TRUE; // Allow sort
		$this->unidadeducativa->DateFilter = "";
		$this->unidadeducativa->SqlSelect = "SELECT DISTINCT `unidadeducativa`, `unidadeducativa` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->unidadeducativa->SqlOrderBy = "`unidadeducativa`";
		$this->fields['unidadeducativa'] = &$this->unidadeducativa;

		// nombre
		$this->nombre = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_nombre', 'nombre', '`nombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->nombre->DateFilter = "";
		$this->nombre->SqlSelect = "SELECT DISTINCT `nombre`, `nombre` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->nombre->SqlOrderBy = "`nombre`";
		$this->fields['nombre'] = &$this->nombre;

		// materno
		$this->materno = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_materno', 'materno', '`materno`', 200, EWR_DATATYPE_STRING, -1);
		$this->materno->Sortable = TRUE; // Allow sort
		$this->materno->DateFilter = "";
		$this->materno->SqlSelect = "SELECT DISTINCT `materno`, `materno` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->materno->SqlOrderBy = "`materno`";
		$this->fields['materno'] = &$this->materno;

		// paterno
		$this->paterno = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_paterno', 'paterno', '`paterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->paterno->Sortable = TRUE; // Allow sort
		$this->paterno->DateFilter = "";
		$this->paterno->SqlSelect = "SELECT DISTINCT `paterno`, `paterno` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->paterno->SqlOrderBy = "`paterno`";
		$this->fields['paterno'] = &$this->paterno;

		// nrodiscapacidad
		$this->nrodiscapacidad = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_nrodiscapacidad', 'nrodiscapacidad', '`nrodiscapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->nrodiscapacidad->Sortable = TRUE; // Allow sort
		$this->nrodiscapacidad->DateFilter = "";
		$this->nrodiscapacidad->SqlSelect = "SELECT DISTINCT `nrodiscapacidad`, `nrodiscapacidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->nrodiscapacidad->SqlOrderBy = "`nrodiscapacidad`";
		$this->fields['nrodiscapacidad'] = &$this->nrodiscapacidad;

		// ci
		$this->ci = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_ci', 'ci', '`ci`', 200, EWR_DATATYPE_STRING, -1);
		$this->ci->Sortable = TRUE; // Allow sort
		$this->ci->DateFilter = "";
		$this->ci->SqlSelect = "SELECT DISTINCT `ci`, `ci` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->ci->SqlOrderBy = "`ci`";
		$this->fields['ci'] = &$this->ci;

		// fechanacimiento
		$this->fechanacimiento = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_fechanacimiento', 'fechanacimiento', '`fechanacimiento`', 133, EWR_DATATYPE_DATE, 0);
		$this->fechanacimiento->Sortable = TRUE; // Allow sort
		$this->fechanacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fechanacimiento->DateFilter = "";
		$this->fechanacimiento->SqlSelect = "SELECT DISTINCT `fechanacimiento`, `fechanacimiento` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fechanacimiento->SqlOrderBy = "`fechanacimiento`";
		$this->fields['fechanacimiento'] = &$this->fechanacimiento;

		// edad
		$this->edad = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_edad', 'edad', '`edad`', 20, EWR_DATATYPE_NUMBER, -1);
		$this->edad->Sortable = TRUE; // Allow sort
		$this->edad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->edad->DateFilter = "";
		$this->edad->SqlSelect = "SELECT DISTINCT `edad`, `edad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->edad->SqlOrderBy = "`edad`";
		$this->fields['edad'] = &$this->edad;

		// sexo
		$this->sexo = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_sexo', 'sexo', '`sexo`', 200, EWR_DATATYPE_STRING, -1);
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->DateFilter = "";
		$this->sexo->SqlSelect = "SELECT DISTINCT `sexo`, `sexo` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->sexo->SqlOrderBy = "`sexo`";
		$this->fields['sexo'] = &$this->sexo;

		// curso
		$this->curso = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_curso', 'curso', '`curso`', 200, EWR_DATATYPE_STRING, -1);
		$this->curso->Sortable = TRUE; // Allow sort
		$this->curso->DateFilter = "";
		$this->curso->SqlSelect = "SELECT DISTINCT `curso`, `curso` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->curso->SqlOrderBy = "`curso`";
		$this->fields['curso'] = &$this->curso;

		// discapacidad
		$this->discapacidad = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_discapacidad', 'discapacidad', '`discapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->discapacidad->DateFilter = "";
		$this->discapacidad->SqlSelect = "SELECT DISTINCT `discapacidad`, `discapacidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->discapacidad->SqlOrderBy = "`discapacidad`";
		$this->fields['discapacidad'] = &$this->discapacidad;

		// tipodiscapcidad
		$this->tipodiscapcidad = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_tipodiscapcidad', 'tipodiscapcidad', '`tipodiscapcidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipodiscapcidad->Sortable = TRUE; // Allow sort
		$this->tipodiscapcidad->DateFilter = "";
		$this->tipodiscapcidad->SqlSelect = "SELECT DISTINCT `tipodiscapcidad`, `tipodiscapcidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->tipodiscapcidad->SqlOrderBy = "`tipodiscapcidad`";
		$this->fields['tipodiscapcidad'] = &$this->tipodiscapcidad;

		// nombreinstitucion
		$this->nombreinstitucion = new crField('viewestudiantecurso', 'viewestudiantecurso', 'x_nombreinstitucion', 'nombreinstitucion', '`nombreinstitucion`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombreinstitucion->Sortable = TRUE; // Allow sort
		$this->nombreinstitucion->DateFilter = "";
		$this->nombreinstitucion->SqlSelect = "SELECT DISTINCT `nombreinstitucion`, `nombreinstitucion` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->nombreinstitucion->SqlOrderBy = "`nombreinstitucion`";
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewestudiantecurso`";
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
		case "x_departamento":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`departamento` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "{filter}";
		$fld->LookupFilters += array(
			"dx1" => '`departamento`',
			"select" => "SELECT DISTINCT `departamento`, `departamento` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewestudiantecurso`",
			"where" => $sWhereWrk,
			"orderby" => "`departamento` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_sexo":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`sexo` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "{filter}";
		$fld->LookupFilters += array(
			"dx1" => '`sexo`',
			"select" => "SELECT DISTINCT `sexo`, `sexo` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewestudiantecurso`",
			"where" => $sWhereWrk,
			"orderby" => "`sexo` ASC"
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
