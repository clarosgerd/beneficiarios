<?php

// Global variable for table object
$viewdocente = NULL;

//
// Table class for viewdocente
//
class crviewdocente extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = FALSE;
	var $deoartamento;
	var $unidadeducativa;
	var $nombres;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nrodiscapacidad;
	var $ci;
	var $fechanacimiento;
	var $sexo;
	var $celular;
	var $materias;
	var $discapacidad;
	var $tipodiscapacidad;
	var $nombreinstitucion;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewdocente';
		$this->TableName = 'viewdocente';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// deoartamento
		$this->deoartamento = new crField('viewdocente', 'viewdocente', 'x_deoartamento', 'deoartamento', '`deoartamento`', 200, EWR_DATATYPE_STRING, -1);
		$this->deoartamento->Sortable = TRUE; // Allow sort
		$this->deoartamento->DateFilter = "";
		$this->deoartamento->SqlSelect = "SELECT DISTINCT `deoartamento`, `deoartamento` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->deoartamento->SqlOrderBy = "`deoartamento`";
		$this->fields['deoartamento'] = &$this->deoartamento;

		// unidadeducativa
		$this->unidadeducativa = new crField('viewdocente', 'viewdocente', 'x_unidadeducativa', 'unidadeducativa', '`unidadeducativa`', 200, EWR_DATATYPE_STRING, -1);
		$this->unidadeducativa->Sortable = TRUE; // Allow sort
		$this->unidadeducativa->DateFilter = "";
		$this->unidadeducativa->SqlSelect = "SELECT DISTINCT `unidadeducativa`, `unidadeducativa` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->unidadeducativa->SqlOrderBy = "`unidadeducativa`";
		$this->fields['unidadeducativa'] = &$this->unidadeducativa;

		// nombres
		$this->nombres = new crField('viewdocente', 'viewdocente', 'x_nombres', 'nombres', '`nombres`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombres->Sortable = TRUE; // Allow sort
		$this->nombres->DateFilter = "";
		$this->nombres->SqlSelect = "SELECT DISTINCT `nombres`, `nombres` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->nombres->SqlOrderBy = "`nombres`";
		$this->fields['nombres'] = &$this->nombres;

		// apellidopaterno
		$this->apellidopaterno = new crField('viewdocente', 'viewdocente', 'x_apellidopaterno', 'apellidopaterno', '`apellidopaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->apellidopaterno->DateFilter = "";
		$this->apellidopaterno->SqlSelect = "SELECT DISTINCT `apellidopaterno`, `apellidopaterno` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->apellidopaterno->SqlOrderBy = "`apellidopaterno`";
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new crField('viewdocente', 'viewdocente', 'x_apellidomaterno', 'apellidomaterno', '`apellidomaterno`', 200, EWR_DATATYPE_STRING, -1);
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->apellidomaterno->DateFilter = "";
		$this->apellidomaterno->SqlSelect = "SELECT DISTINCT `apellidomaterno`, `apellidomaterno` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->apellidomaterno->SqlOrderBy = "`apellidomaterno`";
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nrodiscapacidad
		$this->nrodiscapacidad = new crField('viewdocente', 'viewdocente', 'x_nrodiscapacidad', 'nrodiscapacidad', '`nrodiscapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->nrodiscapacidad->Sortable = TRUE; // Allow sort
		$this->nrodiscapacidad->DateFilter = "";
		$this->nrodiscapacidad->SqlSelect = "SELECT DISTINCT `nrodiscapacidad`, `nrodiscapacidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->nrodiscapacidad->SqlOrderBy = "`nrodiscapacidad`";
		$this->fields['nrodiscapacidad'] = &$this->nrodiscapacidad;

		// ci
		$this->ci = new crField('viewdocente', 'viewdocente', 'x_ci', 'ci', '`ci`', 200, EWR_DATATYPE_STRING, -1);
		$this->ci->Sortable = TRUE; // Allow sort
		$this->ci->DateFilter = "";
		$this->ci->SqlSelect = "";
		$this->ci->SqlOrderBy = "";
		$this->fields['ci'] = &$this->ci;

		// fechanacimiento
		$this->fechanacimiento = new crField('viewdocente', 'viewdocente', 'x_fechanacimiento', 'fechanacimiento', '`fechanacimiento`', 133, EWR_DATATYPE_DATE, 0);
		$this->fechanacimiento->Sortable = TRUE; // Allow sort
		$this->fechanacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fechanacimiento->DateFilter = "";
		$this->fechanacimiento->SqlSelect = "SELECT DISTINCT `fechanacimiento`, `fechanacimiento` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fechanacimiento->SqlOrderBy = "`fechanacimiento`";
		$this->fields['fechanacimiento'] = &$this->fechanacimiento;

		// sexo
		$this->sexo = new crField('viewdocente', 'viewdocente', 'x_sexo', 'sexo', '`sexo`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->sexo->DateFilter = "";
		$this->sexo->SqlSelect = "SELECT DISTINCT `sexo`, `sexo` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->sexo->SqlOrderBy = "`sexo`";
		$this->fields['sexo'] = &$this->sexo;

		// celular
		$this->celular = new crField('viewdocente', 'viewdocente', 'x_celular', 'celular', '`celular`', 200, EWR_DATATYPE_STRING, -1);
		$this->celular->Sortable = TRUE; // Allow sort
		$this->celular->DateFilter = "";
		$this->celular->SqlSelect = "SELECT DISTINCT `celular`, `celular` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->celular->SqlOrderBy = "`celular`";
		$this->fields['celular'] = &$this->celular;

		// materias
		$this->materias = new crField('viewdocente', 'viewdocente', 'x_materias', 'materias', '`materias`', 200, EWR_DATATYPE_STRING, -1);
		$this->materias->Sortable = TRUE; // Allow sort
		$this->materias->DateFilter = "";
		$this->materias->SqlSelect = "SELECT DISTINCT `materias`, `materias` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->materias->SqlOrderBy = "`materias`";
		$this->fields['materias'] = &$this->materias;

		// discapacidad
		$this->discapacidad = new crField('viewdocente', 'viewdocente', 'x_discapacidad', 'discapacidad', '`discapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->discapacidad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->discapacidad->DateFilter = "";
		$this->discapacidad->SqlSelect = "SELECT DISTINCT `discapacidad`, `discapacidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->discapacidad->SqlOrderBy = "`discapacidad`";
		$this->fields['discapacidad'] = &$this->discapacidad;

		// tipodiscapacidad
		$this->tipodiscapacidad = new crField('viewdocente', 'viewdocente', 'x_tipodiscapacidad', 'tipodiscapacidad', '`tipodiscapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipodiscapacidad->Sortable = TRUE; // Allow sort
		$this->tipodiscapacidad->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->tipodiscapacidad->DateFilter = "";
		$this->tipodiscapacidad->SqlSelect = "SELECT DISTINCT `tipodiscapacidad`, `tipodiscapacidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->tipodiscapacidad->SqlOrderBy = "`tipodiscapacidad`";
		$this->fields['tipodiscapacidad'] = &$this->tipodiscapacidad;

		// nombreinstitucion
		$this->nombreinstitucion = new crField('viewdocente', 'viewdocente', 'x_nombreinstitucion', 'nombreinstitucion', '`nombreinstitucion`', 200, EWR_DATATYPE_STRING, -1);
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewdocente`";
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
		case "x_deoartamento":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`deoartamento` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `deoartamento`, `deoartamento` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewdocente`",
			"where" => $sWhereWrk,
			"orderby" => "`deoartamento` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_unidadeducativa":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`unidadeducativa` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `unidadeducativa`, `unidadeducativa` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewdocente`",
			"where" => $sWhereWrk,
			"orderby" => "`unidadeducativa` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_nombres":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`nombres` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `nombres`, `nombres` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewdocente`",
			"where" => $sWhereWrk,
			"orderby" => "`nombres` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_apellidopaterno":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`apellidopaterno` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `apellidopaterno`, `apellidopaterno` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewdocente`",
			"where" => $sWhereWrk,
			"orderby" => "`apellidopaterno` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_apellidomaterno":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`apellidomaterno` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `apellidomaterno`, `apellidomaterno` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewdocente`",
			"where" => $sWhereWrk,
			"orderby" => "`apellidomaterno` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_discapacidad":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`discapacidad` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `discapacidad`, `discapacidad` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewdocente`",
			"where" => $sWhereWrk,
			"orderby" => "`discapacidad` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_tipodiscapacidad":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`tipodiscapacidad` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `tipodiscapacidad`, `tipodiscapacidad` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewdocente`",
			"where" => $sWhereWrk,
			"orderby" => "`tipodiscapacidad` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
		case "x_nombreinstitucion":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`nombreinstitucion` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `nombreinstitucion`, `nombreinstitucion` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `viewdocente`",
			"where" => $sWhereWrk,
			"orderby" => "`nombreinstitucion` ASC"
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
