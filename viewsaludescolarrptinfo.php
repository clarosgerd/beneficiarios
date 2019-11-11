<?php

// Global variable for table object
$viewsaludescolar = NULL;

//
// Table class for viewsaludescolar
//
class crviewsaludescolar extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = FALSE;
	var $codigorude;
	var $codigorude_es;
	var $fecha;
	var $unidad_eductiva;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nombres;
	var $ci;
	var $nrodiscapacidad;
	var $fechanacimiento;
	var $sexo;
	var $curso;
	var $discapcidad;
	var $tipo;
	var $resultado;
	var $resultadotamizaje;
	var $nombre;
	var $tapodonde;
	var $repetirprueba;
	var $observaciones;
	var $parentesco;
	var $nombrescompleto;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewsaludescolar';
		$this->TableName = 'viewsaludescolar';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// codigorude
		$this->codigorude = new crField('viewsaludescolar', 'viewsaludescolar', 'x_codigorude', 'codigorude', '`codigorude`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigorude->Sortable = TRUE; // Allow sort
		$this->codigorude->DateFilter = "";
		$this->codigorude->SqlSelect = "";
		$this->codigorude->SqlOrderBy = "";
		$this->fields['codigorude'] = &$this->codigorude;

		// codigorude_es
		$this->codigorude_es = new crField('viewsaludescolar', 'viewsaludescolar', 'x_codigorude_es', 'codigorude_es', '`codigorude_es`', 200, EWR_DATATYPE_STRING, -1);
		$this->codigorude_es->Sortable = TRUE; // Allow sort
		$this->codigorude_es->DateFilter = "";
		$this->codigorude_es->SqlSelect = "";
		$this->codigorude_es->SqlOrderBy = "";
		$this->fields['codigorude_es'] = &$this->codigorude_es;

		// fecha
		$this->fecha = new crField('viewsaludescolar', 'viewsaludescolar', 'x_fecha', 'fecha', '`fecha`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha->DateFilter = "";
		$this->fecha->SqlSelect = "SELECT DISTINCT `fecha`, `fecha` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fecha->SqlOrderBy = "`fecha`";
		$this->fields['fecha'] = &$this->fecha;

		// unidad eductiva
		$this->unidad_eductiva = new crField('viewsaludescolar', 'viewsaludescolar', 'x_unidad_eductiva', 'unidad eductiva', '`unidad eductiva`', 200, EWR_DATATYPE_STRING, -1);
		$this->unidad_eductiva->Sortable = TRUE; // Allow sort
		$this->unidad_eductiva->DateFilter = "";
		$this->unidad_eductiva->SqlSelect = "SELECT DISTINCT `unidad eductiva`, `unidad eductiva` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->unidad_eductiva->SqlOrderBy = "`unidad eductiva`";
		$this->fields['unidad eductiva'] = &$this->unidad_eductiva;

		// apellidopaterno
		$this->apellidopaterno = new crField('viewsaludescolar', 'viewsaludescolar', 'x_apellidopaterno', 'apellidopaterno', '`apellidopaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->apellidopaterno->DateFilter = "";
		$this->apellidopaterno->SqlSelect = "";
		$this->apellidopaterno->SqlOrderBy = "";
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new crField('viewsaludescolar', 'viewsaludescolar', 'x_apellidomaterno', 'apellidomaterno', '`apellidomaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->apellidomaterno->DateFilter = "";
		$this->apellidomaterno->SqlSelect = "";
		$this->apellidomaterno->SqlOrderBy = "";
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nombres
		$this->nombres = new crField('viewsaludescolar', 'viewsaludescolar', 'x_nombres', 'nombres', '`nombres`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombres->Sortable = TRUE; // Allow sort
		$this->nombres->DateFilter = "";
		$this->nombres->SqlSelect = "";
		$this->nombres->SqlOrderBy = "";
		$this->fields['nombres'] = &$this->nombres;

		// ci
		$this->ci = new crField('viewsaludescolar', 'viewsaludescolar', 'x_ci', 'ci', '`ci`', 200, EWR_DATATYPE_STRING, -1);
		$this->ci->Sortable = TRUE; // Allow sort
		$this->ci->DateFilter = "";
		$this->ci->SqlSelect = "";
		$this->ci->SqlOrderBy = "";
		$this->fields['ci'] = &$this->ci;

		// nrodiscapacidad
		$this->nrodiscapacidad = new crField('viewsaludescolar', 'viewsaludescolar', 'x_nrodiscapacidad', 'nrodiscapacidad', '`nrodiscapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->nrodiscapacidad->Sortable = TRUE; // Allow sort
		$this->nrodiscapacidad->DateFilter = "";
		$this->nrodiscapacidad->SqlSelect = "";
		$this->nrodiscapacidad->SqlOrderBy = "";
		$this->fields['nrodiscapacidad'] = &$this->nrodiscapacidad;

		// fechanacimiento
		$this->fechanacimiento = new crField('viewsaludescolar', 'viewsaludescolar', 'x_fechanacimiento', 'fechanacimiento', '`fechanacimiento`', 133, EWR_DATATYPE_DATE, 0);
		$this->fechanacimiento->Sortable = TRUE; // Allow sort
		$this->fechanacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fechanacimiento->DateFilter = "";
		$this->fechanacimiento->SqlSelect = "SELECT DISTINCT `fechanacimiento`, `fechanacimiento` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fechanacimiento->SqlOrderBy = "`fechanacimiento`";
		$this->fields['fechanacimiento'] = &$this->fechanacimiento;

		// sexo
		$this->sexo = new crField('viewsaludescolar', 'viewsaludescolar', 'x_sexo', 'sexo', '`sexo`', 200, EWR_DATATYPE_STRING, -1);
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->DateFilter = "";
		$this->sexo->SqlSelect = "SELECT DISTINCT `sexo`, `sexo` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->sexo->SqlOrderBy = "`sexo`";
		$this->fields['sexo'] = &$this->sexo;

		// curso
		$this->curso = new crField('viewsaludescolar', 'viewsaludescolar', 'x_curso', 'curso', '`curso`', 200, EWR_DATATYPE_STRING, -1);
		$this->curso->Sortable = TRUE; // Allow sort
		$this->curso->DateFilter = "";
		$this->curso->SqlSelect = "SELECT DISTINCT `curso`, `curso` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->curso->SqlOrderBy = "`curso`";
		$this->fields['curso'] = &$this->curso;

		// discapcidad
		$this->discapcidad = new crField('viewsaludescolar', 'viewsaludescolar', 'x_discapcidad', 'discapcidad', '`discapcidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->discapcidad->Sortable = TRUE; // Allow sort
		$this->discapcidad->DateFilter = "";
		$this->discapcidad->SqlSelect = "SELECT DISTINCT `discapcidad`, `discapcidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->discapcidad->SqlOrderBy = "`discapcidad`";
		$this->fields['discapcidad'] = &$this->discapcidad;

		// tipo
		$this->tipo = new crField('viewsaludescolar', 'viewsaludescolar', 'x_tipo', 'tipo', '`tipo`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipo->Sortable = TRUE; // Allow sort
		$this->tipo->DateFilter = "";
		$this->tipo->SqlSelect = "";
		$this->tipo->SqlOrderBy = "";
		$this->fields['tipo'] = &$this->tipo;

		// resultado
		$this->resultado = new crField('viewsaludescolar', 'viewsaludescolar', 'x_resultado', 'resultado', '`resultado`', 200, EWR_DATATYPE_STRING, -1);
		$this->resultado->Sortable = TRUE; // Allow sort
		$this->resultado->DateFilter = "";
		$this->resultado->SqlSelect = "";
		$this->resultado->SqlOrderBy = "";
		$this->fields['resultado'] = &$this->resultado;

		// resultadotamizaje
		$this->resultadotamizaje = new crField('viewsaludescolar', 'viewsaludescolar', 'x_resultadotamizaje', 'resultadotamizaje', '`resultadotamizaje`', 200, EWR_DATATYPE_STRING, -1);
		$this->resultadotamizaje->Sortable = TRUE; // Allow sort
		$this->resultadotamizaje->DateFilter = "";
		$this->resultadotamizaje->SqlSelect = "";
		$this->resultadotamizaje->SqlOrderBy = "";
		$this->fields['resultadotamizaje'] = &$this->resultadotamizaje;

		// nombre
		$this->nombre = new crField('viewsaludescolar', 'viewsaludescolar', 'x_nombre', 'nombre', '`nombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->nombre->DateFilter = "";
		$this->nombre->SqlSelect = "";
		$this->nombre->SqlOrderBy = "";
		$this->fields['nombre'] = &$this->nombre;

		// tapodonde
		$this->tapodonde = new crField('viewsaludescolar', 'viewsaludescolar', 'x_tapodonde', 'tapodonde', '`tapodonde`', 200, EWR_DATATYPE_STRING, -1);
		$this->tapodonde->Sortable = TRUE; // Allow sort
		$this->tapodonde->DateFilter = "";
		$this->tapodonde->SqlSelect = "";
		$this->tapodonde->SqlOrderBy = "";
		$this->fields['tapodonde'] = &$this->tapodonde;

		// repetirprueba
		$this->repetirprueba = new crField('viewsaludescolar', 'viewsaludescolar', 'x_repetirprueba', 'repetirprueba', '`repetirprueba`', 200, EWR_DATATYPE_STRING, -1);
		$this->repetirprueba->Sortable = TRUE; // Allow sort
		$this->repetirprueba->DateFilter = "";
		$this->repetirprueba->SqlSelect = "";
		$this->repetirprueba->SqlOrderBy = "";
		$this->fields['repetirprueba'] = &$this->repetirprueba;

		// observaciones
		$this->observaciones = new crField('viewsaludescolar', 'viewsaludescolar', 'x_observaciones', 'observaciones', '`observaciones`', 200, EWR_DATATYPE_STRING, -1);
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->observaciones->DateFilter = "";
		$this->observaciones->SqlSelect = "";
		$this->observaciones->SqlOrderBy = "";
		$this->fields['observaciones'] = &$this->observaciones;

		// parentesco
		$this->parentesco = new crField('viewsaludescolar', 'viewsaludescolar', 'x_parentesco', 'parentesco', '`parentesco`', 200, EWR_DATATYPE_STRING, -1);
		$this->parentesco->Sortable = TRUE; // Allow sort
		$this->parentesco->DateFilter = "";
		$this->parentesco->SqlSelect = "";
		$this->parentesco->SqlOrderBy = "";
		$this->fields['parentesco'] = &$this->parentesco;

		// nombrescompleto
		$this->nombrescompleto = new crField('viewsaludescolar', 'viewsaludescolar', 'x_nombrescompleto', 'nombrescompleto', '`nombrescompleto`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombrescompleto->Sortable = TRUE; // Allow sort
		$this->nombrescompleto->DateFilter = "";
		$this->nombrescompleto->SqlSelect = "";
		$this->nombrescompleto->SqlOrderBy = "";
		$this->fields['nombrescompleto'] = &$this->nombrescompleto;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewsaludescolar`";
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
		case "x_unidad_eductiva":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`unidad eductiva` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `unidad eductiva`, `unidad eductiva` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewsaludescolar`",
			"where" => $sWhereWrk,
			"orderby" => "`unidad eductiva` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_sexo":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`sexo` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `sexo`, `sexo` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewsaludescolar`",
			"where" => $sWhereWrk,
			"orderby" => "`sexo` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_curso":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`curso` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `curso`, `curso` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewsaludescolar`",
			"where" => $sWhereWrk,
			"orderby" => "`curso` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_discapcidad":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`discapcidad` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `discapcidad`, `discapcidad` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewsaludescolar`",
			"where" => $sWhereWrk,
			"orderby" => "`discapcidad` ASC"
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
