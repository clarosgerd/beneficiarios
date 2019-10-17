<?php

// Global variable for table object
$estudiante = NULL;

//
// Table class for estudiante
//
class cestudiante extends cTable {
	var $id;
	var $codigorude;
	var $codigorude_es;
	var $departamento;
	var $municipio;
	var $provincisa;
	var $unidadeducativa;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nombres;
	var $nrodiscapacidad;
	var $ci;
	var $fechanacimiento;
	var $sexo;
	var $curso;
	var $discapacidad;
	var $tipodiscapacidad;
	var $observaciones;
	var $id_centro;
	var $gestion;
	var $esincritoespecial;
	var $fecha;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'estudiante';
		$this->TableName = 'estudiante';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`estudiante`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('estudiante', 'estudiante', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = FALSE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// codigorude
		$this->codigorude = new cField('estudiante', 'estudiante', 'x_codigorude', 'codigorude', '`codigorude`', '`codigorude`', 200, -1, FALSE, '`codigorude`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigorude->Sortable = TRUE; // Allow sort
		$this->fields['codigorude'] = &$this->codigorude;

		// codigorude_es
		$this->codigorude_es = new cField('estudiante', 'estudiante', 'x_codigorude_es', 'codigorude_es', '`codigorude_es`', '`codigorude_es`', 200, -1, FALSE, '`codigorude_es`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigorude_es->Sortable = TRUE; // Allow sort
		$this->fields['codigorude_es'] = &$this->codigorude_es;

		// departamento
		$this->departamento = new cField('estudiante', 'estudiante', 'x_departamento', 'departamento', '`departamento`', '`departamento`', 3, -1, FALSE, '`departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->departamento->Sortable = TRUE; // Allow sort
		$this->departamento->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->departamento->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['departamento'] = &$this->departamento;

		// municipio
		$this->municipio = new cField('estudiante', 'estudiante', 'x_municipio', 'municipio', '`municipio`', '`municipio`', 3, -1, FALSE, '`municipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->municipio->Sortable = TRUE; // Allow sort
		$this->municipio->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->municipio->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->municipio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['municipio'] = &$this->municipio;

		// provincisa
		$this->provincisa = new cField('estudiante', 'estudiante', 'x_provincisa', 'provincisa', '`provincisa`', '`provincisa`', 3, -1, FALSE, '`provincisa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->provincisa->Sortable = TRUE; // Allow sort
		$this->provincisa->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->provincisa->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['provincisa'] = &$this->provincisa;

		// unidadeducativa
		$this->unidadeducativa = new cField('estudiante', 'estudiante', 'x_unidadeducativa', 'unidadeducativa', '`unidadeducativa`', '`unidadeducativa`', 3, -1, FALSE, '`unidadeducativa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->unidadeducativa->Sortable = TRUE; // Allow sort
		$this->unidadeducativa->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->unidadeducativa->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['unidadeducativa'] = &$this->unidadeducativa;

		// apellidopaterno
		$this->apellidopaterno = new cField('estudiante', 'estudiante', 'x_apellidopaterno', 'apellidopaterno', '`apellidopaterno`', '`apellidopaterno`', 200, -1, FALSE, '`apellidopaterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new cField('estudiante', 'estudiante', 'x_apellidomaterno', 'apellidomaterno', '`apellidomaterno`', '`apellidomaterno`', 200, -1, FALSE, '`apellidomaterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nombres
		$this->nombres = new cField('estudiante', 'estudiante', 'x_nombres', 'nombres', '`nombres`', '`nombres`', 200, -1, FALSE, '`nombres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombres->Sortable = TRUE; // Allow sort
		$this->fields['nombres'] = &$this->nombres;

		// nrodiscapacidad
		$this->nrodiscapacidad = new cField('estudiante', 'estudiante', 'x_nrodiscapacidad', 'nrodiscapacidad', '`nrodiscapacidad`', '`nrodiscapacidad`', 200, -1, FALSE, '`nrodiscapacidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nrodiscapacidad->Sortable = TRUE; // Allow sort
		$this->fields['nrodiscapacidad'] = &$this->nrodiscapacidad;

		// ci
		$this->ci = new cField('estudiante', 'estudiante', 'x_ci', 'ci', '`ci`', '`ci`', 200, -1, FALSE, '`ci`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ci->Sortable = TRUE; // Allow sort
		$this->fields['ci'] = &$this->ci;

		// fechanacimiento
		$this->fechanacimiento = new cField('estudiante', 'estudiante', 'x_fechanacimiento', 'fechanacimiento', '`fechanacimiento`', ew_CastDateFieldForLike('`fechanacimiento`', 7, "DB"), 133, 7, FALSE, '`fechanacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechanacimiento->Sortable = TRUE; // Allow sort
		$this->fechanacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechanacimiento'] = &$this->fechanacimiento;

		// sexo
		$this->sexo = new cField('estudiante', 'estudiante', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 200, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sexo->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sexo->OptionCount = 2;
		$this->sexo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sexo'] = &$this->sexo;

		// curso
		$this->curso = new cField('estudiante', 'estudiante', 'x_curso', 'curso', '`curso`', '`curso`', 3, -1, FALSE, '`curso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->curso->Sortable = TRUE; // Allow sort
		$this->curso->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->curso->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['curso'] = &$this->curso;

		// discapacidad
		$this->discapacidad = new cField('estudiante', 'estudiante', 'x_discapacidad', 'discapacidad', '`discapacidad`', '`discapacidad`', 3, -1, FALSE, '`discapacidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->fields['discapacidad'] = &$this->discapacidad;

		// tipodiscapacidad
		$this->tipodiscapacidad = new cField('estudiante', 'estudiante', 'x_tipodiscapacidad', 'tipodiscapacidad', '`tipodiscapacidad`', '`tipodiscapacidad`', 3, -1, FALSE, '`tipodiscapacidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipodiscapacidad->Sortable = TRUE; // Allow sort
		$this->tipodiscapacidad->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->tipodiscapacidad->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['tipodiscapacidad'] = &$this->tipodiscapacidad;

		// observaciones
		$this->observaciones = new cField('estudiante', 'estudiante', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 200, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// id_centro
		$this->id_centro = new cField('estudiante', 'estudiante', 'x_id_centro', 'id_centro', '`id_centro`', '`id_centro`', 3, -1, FALSE, '`id_centro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_centro->Sortable = FALSE; // Allow sort
		$this->id_centro->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_centro->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_centro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_centro'] = &$this->id_centro;

		// gestion
		$this->gestion = new cField('estudiante', 'estudiante', 'x_gestion', 'gestion', '`gestion`', '`gestion`', 3, -1, FALSE, '`gestion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->gestion->Sortable = FALSE; // Allow sort
		$this->gestion->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->gestion->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->gestion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['gestion'] = &$this->gestion;

		// esincritoespecial
		$this->esincritoespecial = new cField('estudiante', 'estudiante', 'x_esincritoespecial', 'esincritoespecial', '`esincritoespecial`', '`esincritoespecial`', 3, -1, FALSE, '`esincritoespecial`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->esincritoespecial->Sortable = FALSE; // Allow sort
		$this->esincritoespecial->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->esincritoespecial->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->esincritoespecial->OptionCount = 2;
		$this->esincritoespecial->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['esincritoespecial'] = &$this->esincritoespecial;

		// fecha
		$this->fecha = new cField('estudiante', 'estudiante', 'x_fecha', 'fecha', '`fecha`', ew_CastDateFieldForLike('`fecha`', 0, "DB"), 133, 0, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha->Sortable = FALSE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha'] = &$this->fecha;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`estudiante`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "`id_centro` like '".$_SESSION["centro"]."'";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->id->setDbValue($conn->Insert_ID());
			$rs['id'] = $this->id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "estudiantelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "estudianteview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "estudianteedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "estudianteadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "estudiantelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("estudianteview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("estudianteview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "estudianteadd.php?" . $this->UrlParm($parm);
		else
			$url = "estudianteadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("estudianteedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("estudianteadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("estudiantedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = $_POST["id"];
			elseif (isset($_GET["id"]))
				$arKeys[] = $_GET["id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->codigorude->setDbValue($rs->fields('codigorude'));
		$this->codigorude_es->setDbValue($rs->fields('codigorude_es'));
		$this->departamento->setDbValue($rs->fields('departamento'));
		$this->municipio->setDbValue($rs->fields('municipio'));
		$this->provincisa->setDbValue($rs->fields('provincisa'));
		$this->unidadeducativa->setDbValue($rs->fields('unidadeducativa'));
		$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
		$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
		$this->nombres->setDbValue($rs->fields('nombres'));
		$this->nrodiscapacidad->setDbValue($rs->fields('nrodiscapacidad'));
		$this->ci->setDbValue($rs->fields('ci'));
		$this->fechanacimiento->setDbValue($rs->fields('fechanacimiento'));
		$this->sexo->setDbValue($rs->fields('sexo'));
		$this->curso->setDbValue($rs->fields('curso'));
		$this->discapacidad->setDbValue($rs->fields('discapacidad'));
		$this->tipodiscapacidad->setDbValue($rs->fields('tipodiscapacidad'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->id_centro->setDbValue($rs->fields('id_centro'));
		$this->gestion->setDbValue($rs->fields('gestion'));
		$this->esincritoespecial->setDbValue($rs->fields('esincritoespecial'));
		$this->fecha->setDbValue($rs->fields('fecha'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// codigorude
		// codigorude_es
		// departamento
		// municipio
		// provincisa
		// unidadeducativa
		// apellidopaterno
		// apellidomaterno
		// nombres
		// nrodiscapacidad
		// ci
		// fechanacimiento
		// sexo
		// curso
		// discapacidad
		// tipodiscapacidad
		// observaciones
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";

		// gestion
		$this->gestion->CellCssStyle = "white-space: nowrap;";

		// esincritoespecial
		$this->esincritoespecial->CellCssStyle = "white-space: nowrap;";

		// fecha
		$this->fecha->CellCssStyle = "white-space: nowrap;";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// codigorude
		$this->codigorude->ViewValue = $this->codigorude->CurrentValue;
		$this->codigorude->ViewCustomAttributes = "";

		// codigorude_es
		$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
		$this->codigorude_es->ViewCustomAttributes = "";

		// departamento
		if (strval($this->departamento->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->departamento->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
		$sWhereWrk = "";
		$this->departamento->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->departamento, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->departamento->ViewValue = $this->departamento->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->departamento->ViewValue = $this->departamento->CurrentValue;
			}
		} else {
			$this->departamento->ViewValue = NULL;
		}
		$this->departamento->ViewCustomAttributes = "";

		// municipio
		if (strval($this->municipio->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->municipio->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
		$sWhereWrk = "";
		$this->municipio->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->municipio, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->municipio->ViewValue = $this->municipio->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->municipio->ViewValue = $this->municipio->CurrentValue;
			}
		} else {
			$this->municipio->ViewValue = NULL;
		}
		$this->municipio->ViewCustomAttributes = "";

		// provincisa
		if (strval($this->provincisa->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->provincisa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincia`";
		$sWhereWrk = "";
		$this->provincisa->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->provincisa, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->provincisa->ViewValue = $this->provincisa->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->provincisa->ViewValue = $this->provincisa->CurrentValue;
			}
		} else {
			$this->provincisa->ViewValue = NULL;
		}
		$this->provincisa->ViewCustomAttributes = "";

		// unidadeducativa
		if (strval($this->unidadeducativa->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->unidadeducativa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->unidadeducativa->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->unidadeducativa->ViewValue = $this->unidadeducativa->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->unidadeducativa->ViewValue = $this->unidadeducativa->CurrentValue;
			}
		} else {
			$this->unidadeducativa->ViewValue = NULL;
		}
		$this->unidadeducativa->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombres
		$this->nombres->ViewValue = $this->nombres->CurrentValue;
		$this->nombres->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fechanacimiento
		$this->fechanacimiento->ViewValue = $this->fechanacimiento->CurrentValue;
		$this->fechanacimiento->ViewValue = ew_FormatDateTime($this->fechanacimiento->ViewValue, 7);
		$this->fechanacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// curso
		if (strval($this->curso->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->curso->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `curso` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `curso`";
		$sWhereWrk = "";
		$this->curso->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->curso, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->curso->ViewValue = $this->curso->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->curso->ViewValue = $this->curso->CurrentValue;
			}
		} else {
			$this->curso->ViewValue = NULL;
		}
		$this->curso->ViewCustomAttributes = "";

		// discapacidad
		$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
		if (strval($this->discapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
		$sWhereWrk = "";
		$this->discapacidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->discapacidad->ViewValue = $this->discapacidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
			}
		} else {
			$this->discapacidad->ViewValue = NULL;
		}
		$this->discapacidad->ViewCustomAttributes = "";

		// tipodiscapacidad
		if (strval($this->tipodiscapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipodiscapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiscapacidad`";
		$sWhereWrk = "";
		$this->tipodiscapacidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->CurrentValue;
			}
		} else {
			$this->tipodiscapacidad->ViewValue = NULL;
		}
		$this->tipodiscapacidad->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_centro
		if (strval($this->id_centro->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_centro->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `centros`";
		$sWhereWrk = "";
		$this->id_centro->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_centro, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_centro->ViewValue = $this->id_centro->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
			}
		} else {
			$this->id_centro->ViewValue = NULL;
		}
		$this->id_centro->ViewCustomAttributes = "";

		// gestion
		if (strval($this->gestion->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->gestion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `id` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gestion`";
		$sWhereWrk = "";
		$this->gestion->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->gestion, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->gestion->ViewValue = $this->gestion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->gestion->ViewValue = $this->gestion->CurrentValue;
			}
		} else {
			$this->gestion->ViewValue = NULL;
		}
		$this->gestion->ViewCustomAttributes = "";

		// esincritoespecial
		if (strval($this->esincritoespecial->CurrentValue) <> "") {
			$this->esincritoespecial->ViewValue = $this->esincritoespecial->OptionCaption($this->esincritoespecial->CurrentValue);
		} else {
			$this->esincritoespecial->ViewValue = NULL;
		}
		$this->esincritoespecial->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// codigorude
		$this->codigorude->LinkCustomAttributes = "";
		$this->codigorude->HrefValue = "";
		$this->codigorude->TooltipValue = "";

		// codigorude_es
		$this->codigorude_es->LinkCustomAttributes = "";
		$this->codigorude_es->HrefValue = "";
		$this->codigorude_es->TooltipValue = "";

		// departamento
		$this->departamento->LinkCustomAttributes = "";
		$this->departamento->HrefValue = "";
		$this->departamento->TooltipValue = "";

		// municipio
		$this->municipio->LinkCustomAttributes = "";
		$this->municipio->HrefValue = "";
		$this->municipio->TooltipValue = "";

		// provincisa
		$this->provincisa->LinkCustomAttributes = "";
		$this->provincisa->HrefValue = "";
		$this->provincisa->TooltipValue = "";

		// unidadeducativa
		$this->unidadeducativa->LinkCustomAttributes = "";
		$this->unidadeducativa->HrefValue = "";
		$this->unidadeducativa->TooltipValue = "";

		// apellidopaterno
		$this->apellidopaterno->LinkCustomAttributes = "";
		$this->apellidopaterno->HrefValue = "";
		$this->apellidopaterno->TooltipValue = "";

		// apellidomaterno
		$this->apellidomaterno->LinkCustomAttributes = "";
		$this->apellidomaterno->HrefValue = "";
		$this->apellidomaterno->TooltipValue = "";

		// nombres
		$this->nombres->LinkCustomAttributes = "";
		$this->nombres->HrefValue = "";
		$this->nombres->TooltipValue = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->LinkCustomAttributes = "";
		$this->nrodiscapacidad->HrefValue = "";
		$this->nrodiscapacidad->TooltipValue = "";

		// ci
		$this->ci->LinkCustomAttributes = "";
		$this->ci->HrefValue = "";
		$this->ci->TooltipValue = "";

		// fechanacimiento
		$this->fechanacimiento->LinkCustomAttributes = "";
		$this->fechanacimiento->HrefValue = "";
		$this->fechanacimiento->TooltipValue = "";

		// sexo
		$this->sexo->LinkCustomAttributes = "";
		$this->sexo->HrefValue = "";
		$this->sexo->TooltipValue = "";

		// curso
		$this->curso->LinkCustomAttributes = "";
		$this->curso->HrefValue = "";
		$this->curso->TooltipValue = "";

		// discapacidad
		$this->discapacidad->LinkCustomAttributes = "";
		$this->discapacidad->HrefValue = "";
		$this->discapacidad->TooltipValue = "";

		// tipodiscapacidad
		$this->tipodiscapacidad->LinkCustomAttributes = "";
		$this->tipodiscapacidad->HrefValue = "";
		$this->tipodiscapacidad->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// id_centro
		$this->id_centro->LinkCustomAttributes = "";
		$this->id_centro->HrefValue = "";
		$this->id_centro->TooltipValue = "";

		// gestion
		$this->gestion->LinkCustomAttributes = "";
		$this->gestion->HrefValue = "";
		$this->gestion->TooltipValue = "";

		// esincritoespecial
		$this->esincritoespecial->LinkCustomAttributes = "";
		$this->esincritoespecial->HrefValue = "";
		$this->esincritoespecial->TooltipValue = "";

		// fecha
		$this->fecha->LinkCustomAttributes = "";
		$this->fecha->HrefValue = "";
		$this->fecha->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// codigorude
		$this->codigorude->EditAttrs["class"] = "form-control";
		$this->codigorude->EditCustomAttributes = "";
		$this->codigorude->EditValue = $this->codigorude->CurrentValue;
		$this->codigorude->PlaceHolder = ew_RemoveHtml($this->codigorude->FldCaption());

		// codigorude_es
		$this->codigorude_es->EditAttrs["class"] = "form-control";
		$this->codigorude_es->EditCustomAttributes = "";
		$this->codigorude_es->EditValue = $this->codigorude_es->CurrentValue;
		$this->codigorude_es->PlaceHolder = ew_RemoveHtml($this->codigorude_es->FldCaption());

		// departamento
		$this->departamento->EditAttrs["class"] = "form-control";
		$this->departamento->EditCustomAttributes = "";

		// municipio
		$this->municipio->EditAttrs["class"] = "form-control";
		$this->municipio->EditCustomAttributes = "";

		// provincisa
		$this->provincisa->EditAttrs["class"] = "form-control";
		$this->provincisa->EditCustomAttributes = "";

		// unidadeducativa
		$this->unidadeducativa->EditAttrs["class"] = "form-control";
		$this->unidadeducativa->EditCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->EditAttrs["class"] = "form-control";
		$this->apellidopaterno->EditCustomAttributes = "";
		$this->apellidopaterno->EditValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->PlaceHolder = ew_RemoveHtml($this->apellidopaterno->FldCaption());

		// apellidomaterno
		$this->apellidomaterno->EditAttrs["class"] = "form-control";
		$this->apellidomaterno->EditCustomAttributes = "";
		$this->apellidomaterno->EditValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->PlaceHolder = ew_RemoveHtml($this->apellidomaterno->FldCaption());

		// nombres
		$this->nombres->EditAttrs["class"] = "form-control";
		$this->nombres->EditCustomAttributes = "";
		$this->nombres->EditValue = $this->nombres->CurrentValue;
		$this->nombres->PlaceHolder = ew_RemoveHtml($this->nombres->FldCaption());

		// nrodiscapacidad
		$this->nrodiscapacidad->EditAttrs["class"] = "form-control";
		$this->nrodiscapacidad->EditCustomAttributes = "";
		$this->nrodiscapacidad->EditValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->PlaceHolder = ew_RemoveHtml($this->nrodiscapacidad->FldCaption());

		// ci
		$this->ci->EditAttrs["class"] = "form-control";
		$this->ci->EditCustomAttributes = "";
		$this->ci->EditValue = $this->ci->CurrentValue;
		$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

		// fechanacimiento
		$this->fechanacimiento->EditAttrs["class"] = "form-control";
		$this->fechanacimiento->EditCustomAttributes = "";
		$this->fechanacimiento->EditValue = ew_FormatDateTime($this->fechanacimiento->CurrentValue, 7);
		$this->fechanacimiento->PlaceHolder = ew_RemoveHtml($this->fechanacimiento->FldCaption());

		// sexo
		$this->sexo->EditAttrs["class"] = "form-control";
		$this->sexo->EditCustomAttributes = "";
		$this->sexo->EditValue = $this->sexo->Options(TRUE);

		// curso
		$this->curso->EditAttrs["class"] = "form-control";
		$this->curso->EditCustomAttributes = "";

		// discapacidad
		$this->discapacidad->EditAttrs["class"] = "form-control";
		$this->discapacidad->EditCustomAttributes = "";
		$this->discapacidad->EditValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

		// tipodiscapacidad
		$this->tipodiscapacidad->EditAttrs["class"] = "form-control";
		$this->tipodiscapacidad->EditCustomAttributes = "";

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// id_centro
		$this->id_centro->EditAttrs["class"] = "form-control";
		$this->id_centro->EditCustomAttributes = "";

		// gestion
		$this->gestion->EditAttrs["class"] = "form-control";
		$this->gestion->EditCustomAttributes = "";

		// esincritoespecial
		$this->esincritoespecial->EditAttrs["class"] = "form-control";
		$this->esincritoespecial->EditCustomAttributes = "";
		$this->esincritoespecial->EditValue = $this->esincritoespecial->Options(TRUE);

		// fecha
		$this->fecha->EditAttrs["class"] = "form-control";
		$this->fecha->EditCustomAttributes = "";
		$this->fecha->EditValue = ew_FormatDateTime($this->fecha->CurrentValue, 8);
		$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->codigorude->Exportable) $Doc->ExportCaption($this->codigorude);
					if ($this->codigorude_es->Exportable) $Doc->ExportCaption($this->codigorude_es);
					if ($this->departamento->Exportable) $Doc->ExportCaption($this->departamento);
					if ($this->municipio->Exportable) $Doc->ExportCaption($this->municipio);
					if ($this->provincisa->Exportable) $Doc->ExportCaption($this->provincisa);
					if ($this->unidadeducativa->Exportable) $Doc->ExportCaption($this->unidadeducativa);
					if ($this->apellidopaterno->Exportable) $Doc->ExportCaption($this->apellidopaterno);
					if ($this->apellidomaterno->Exportable) $Doc->ExportCaption($this->apellidomaterno);
					if ($this->nombres->Exportable) $Doc->ExportCaption($this->nombres);
					if ($this->nrodiscapacidad->Exportable) $Doc->ExportCaption($this->nrodiscapacidad);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fechanacimiento->Exportable) $Doc->ExportCaption($this->fechanacimiento);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->curso->Exportable) $Doc->ExportCaption($this->curso);
					if ($this->discapacidad->Exportable) $Doc->ExportCaption($this->discapacidad);
					if ($this->tipodiscapacidad->Exportable) $Doc->ExportCaption($this->tipodiscapacidad);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
				} else {
					if ($this->codigorude->Exportable) $Doc->ExportCaption($this->codigorude);
					if ($this->codigorude_es->Exportable) $Doc->ExportCaption($this->codigorude_es);
					if ($this->departamento->Exportable) $Doc->ExportCaption($this->departamento);
					if ($this->municipio->Exportable) $Doc->ExportCaption($this->municipio);
					if ($this->provincisa->Exportable) $Doc->ExportCaption($this->provincisa);
					if ($this->unidadeducativa->Exportable) $Doc->ExportCaption($this->unidadeducativa);
					if ($this->apellidopaterno->Exportable) $Doc->ExportCaption($this->apellidopaterno);
					if ($this->apellidomaterno->Exportable) $Doc->ExportCaption($this->apellidomaterno);
					if ($this->nombres->Exportable) $Doc->ExportCaption($this->nombres);
					if ($this->nrodiscapacidad->Exportable) $Doc->ExportCaption($this->nrodiscapacidad);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fechanacimiento->Exportable) $Doc->ExportCaption($this->fechanacimiento);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->curso->Exportable) $Doc->ExportCaption($this->curso);
					if ($this->discapacidad->Exportable) $Doc->ExportCaption($this->discapacidad);
					if ($this->tipodiscapacidad->Exportable) $Doc->ExportCaption($this->tipodiscapacidad);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->codigorude->Exportable) $Doc->ExportField($this->codigorude);
						if ($this->codigorude_es->Exportable) $Doc->ExportField($this->codigorude_es);
						if ($this->departamento->Exportable) $Doc->ExportField($this->departamento);
						if ($this->municipio->Exportable) $Doc->ExportField($this->municipio);
						if ($this->provincisa->Exportable) $Doc->ExportField($this->provincisa);
						if ($this->unidadeducativa->Exportable) $Doc->ExportField($this->unidadeducativa);
						if ($this->apellidopaterno->Exportable) $Doc->ExportField($this->apellidopaterno);
						if ($this->apellidomaterno->Exportable) $Doc->ExportField($this->apellidomaterno);
						if ($this->nombres->Exportable) $Doc->ExportField($this->nombres);
						if ($this->nrodiscapacidad->Exportable) $Doc->ExportField($this->nrodiscapacidad);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fechanacimiento->Exportable) $Doc->ExportField($this->fechanacimiento);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->curso->Exportable) $Doc->ExportField($this->curso);
						if ($this->discapacidad->Exportable) $Doc->ExportField($this->discapacidad);
						if ($this->tipodiscapacidad->Exportable) $Doc->ExportField($this->tipodiscapacidad);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
					} else {
						if ($this->codigorude->Exportable) $Doc->ExportField($this->codigorude);
						if ($this->codigorude_es->Exportable) $Doc->ExportField($this->codigorude_es);
						if ($this->departamento->Exportable) $Doc->ExportField($this->departamento);
						if ($this->municipio->Exportable) $Doc->ExportField($this->municipio);
						if ($this->provincisa->Exportable) $Doc->ExportField($this->provincisa);
						if ($this->unidadeducativa->Exportable) $Doc->ExportField($this->unidadeducativa);
						if ($this->apellidopaterno->Exportable) $Doc->ExportField($this->apellidopaterno);
						if ($this->apellidomaterno->Exportable) $Doc->ExportField($this->apellidomaterno);
						if ($this->nombres->Exportable) $Doc->ExportField($this->nombres);
						if ($this->nrodiscapacidad->Exportable) $Doc->ExportField($this->nrodiscapacidad);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fechanacimiento->Exportable) $Doc->ExportField($this->fechanacimiento);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->curso->Exportable) $Doc->ExportField($this->curso);
						if ($this->discapacidad->Exportable) $Doc->ExportField($this->discapacidad);
						if ($this->tipodiscapacidad->Exportable) $Doc->ExportField($this->tipodiscapacidad);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
		$sql="SELECT COUNT(*) FROM inscripcionestudiante WHERE id_estudiante='".$rsold["id"]."' and id_gestion=YEAR('".$rsnew["fecha"]."') and id_curso='".$rsnew["curso"]."'";
	$MyCount = ew_ExecuteScalar($sql);
	if ($MyCount==0){

	// Insert record
	// NOTE: Modify your SQL here, replace the table name, field name and field values

	$sql_insert="INSERT INTO `inscripcionestudiante`(`id_estudiante`, `id_curso`, `id_gestion`,`fecha`) VALUES ('".$rsold["id"]."',YEAR('".$rsnew["fecha"]."'),'".$rsnew["gestion"]."','".$rsnew["fecha"]."')";
	$MyResult = ew_Execute($sql_insert);
	}
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
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
}
?>
