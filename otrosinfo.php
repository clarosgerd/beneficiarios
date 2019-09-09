<?php

// Global variable for table object
$otros = NULL;

//
// Table class for otros
//
class cotros extends cTable {
	var $id;
	var $id_actividad;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nombre;
	var $nrodiscapacidad;
	var $ci;
	var $fecha_nacimiento;
	var $sexo;
	var $nivelestudio;
	var $id_discapacidad;
	var $id_tipodiscapacidad;
	var $resultado;
	var $resultadotamizaje;
	var $tapon;
	var $tipo;
	var $repetirprueba;
	var $observaciones;
	var $id_apoderado;
	var $id_referencia;
	var $id_centro;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'otros';
		$this->TableName = 'otros';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`otros`";
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
		$this->id = new cField('otros', 'otros', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// id_actividad
		$this->id_actividad = new cField('otros', 'otros', 'x_id_actividad', 'id_actividad', '`id_actividad`', '`id_actividad`', 3, -1, FALSE, '`id_actividad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_actividad->Sortable = TRUE; // Allow sort
		$this->id_actividad->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_actividad->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_actividad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_actividad'] = &$this->id_actividad;

		// apellidopaterno
		$this->apellidopaterno = new cField('otros', 'otros', 'x_apellidopaterno', 'apellidopaterno', '`apellidopaterno`', '`apellidopaterno`', 200, -1, FALSE, '`apellidopaterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new cField('otros', 'otros', 'x_apellidomaterno', 'apellidomaterno', '`apellidomaterno`', '`apellidomaterno`', 200, -1, FALSE, '`apellidomaterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nombre
		$this->nombre = new cField('otros', 'otros', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->fields['nombre'] = &$this->nombre;

		// nrodiscapacidad
		$this->nrodiscapacidad = new cField('otros', 'otros', 'x_nrodiscapacidad', 'nrodiscapacidad', '`nrodiscapacidad`', '`nrodiscapacidad`', 200, -1, FALSE, '`nrodiscapacidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nrodiscapacidad->Sortable = TRUE; // Allow sort
		$this->fields['nrodiscapacidad'] = &$this->nrodiscapacidad;

		// ci
		$this->ci = new cField('otros', 'otros', 'x_ci', 'ci', '`ci`', '`ci`', 200, -1, FALSE, '`ci`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ci->Sortable = TRUE; // Allow sort
		$this->fields['ci'] = &$this->ci;

		// fecha_nacimiento
		$this->fecha_nacimiento = new cField('otros', 'otros', 'x_fecha_nacimiento', 'fecha_nacimiento', '`fecha_nacimiento`', ew_CastDateFieldForLike('`fecha_nacimiento`', 0, "DB"), 133, 0, FALSE, '`fecha_nacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_nacimiento->Sortable = TRUE; // Allow sort
		$this->fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha_nacimiento'] = &$this->fecha_nacimiento;

		// sexo
		$this->sexo = new cField('otros', 'otros', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 200, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sexo->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sexo->OptionCount = 2;
		$this->fields['sexo'] = &$this->sexo;

		// nivelestudio
		$this->nivelestudio = new cField('otros', 'otros', 'x_nivelestudio', 'nivelestudio', '`nivelestudio`', '`nivelestudio`', 200, -1, FALSE, '`nivelestudio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nivelestudio->Sortable = TRUE; // Allow sort
		$this->fields['nivelestudio'] = &$this->nivelestudio;

		// id_discapacidad
		$this->id_discapacidad = new cField('otros', 'otros', 'x_id_discapacidad', 'id_discapacidad', '`id_discapacidad`', '`id_discapacidad`', 3, -1, FALSE, '`id_discapacidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_discapacidad->Sortable = TRUE; // Allow sort
		$this->id_discapacidad->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_discapacidad->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['id_discapacidad'] = &$this->id_discapacidad;

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad = new cField('otros', 'otros', 'x_id_tipodiscapacidad', 'id_tipodiscapacidad', '`id_tipodiscapacidad`', '`id_tipodiscapacidad`', 3, -1, FALSE, '`id_tipodiscapacidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_tipodiscapacidad->Sortable = TRUE; // Allow sort
		$this->fields['id_tipodiscapacidad'] = &$this->id_tipodiscapacidad;

		// resultado
		$this->resultado = new cField('otros', 'otros', 'x_resultado', 'resultado', '`resultado`', '`resultado`', 200, -1, FALSE, '`resultado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->resultado->Sortable = TRUE; // Allow sort
		$this->resultado->OptionCount = 2;
		$this->fields['resultado'] = &$this->resultado;

		// resultadotamizaje
		$this->resultadotamizaje = new cField('otros', 'otros', 'x_resultadotamizaje', 'resultadotamizaje', '`resultadotamizaje`', '`resultadotamizaje`', 200, -1, FALSE, '`resultadotamizaje`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resultadotamizaje->Sortable = TRUE; // Allow sort
		$this->fields['resultadotamizaje'] = &$this->resultadotamizaje;

		// tapon
		$this->tapon = new cField('otros', 'otros', 'x_tapon', 'tapon', '`tapon`', '`tapon`', 3, -1, FALSE, '`tapon`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->tapon->Sortable = TRUE; // Allow sort
		$this->fields['tapon'] = &$this->tapon;

		// tipo
		$this->tipo = new cField('otros', 'otros', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 200, -1, FALSE, '`tipo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->tipo->Sortable = TRUE; // Allow sort
		$this->tipo->OptionCount = 3;
		$this->fields['tipo'] = &$this->tipo;

		// repetirprueba
		$this->repetirprueba = new cField('otros', 'otros', 'x_repetirprueba', 'repetirprueba', '`repetirprueba`', '`repetirprueba`', 200, -1, FALSE, '`repetirprueba`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->repetirprueba->Sortable = TRUE; // Allow sort
		$this->fields['repetirprueba'] = &$this->repetirprueba;

		// observaciones
		$this->observaciones = new cField('otros', 'otros', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 200, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// id_apoderado
		$this->id_apoderado = new cField('otros', 'otros', 'x_id_apoderado', 'id_apoderado', '`id_apoderado`', '`id_apoderado`', 3, -1, FALSE, '`id_apoderado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_apoderado->Sortable = TRUE; // Allow sort
		$this->id_apoderado->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_apoderado->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_apoderado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_apoderado'] = &$this->id_apoderado;

		// id_referencia
		$this->id_referencia = new cField('otros', 'otros', 'x_id_referencia', 'id_referencia', '`id_referencia`', '`id_referencia`', 3, -1, FALSE, '`id_referencia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_referencia->Sortable = TRUE; // Allow sort
		$this->id_referencia->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_referencia->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_referencia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_referencia'] = &$this->id_referencia;

		// id_centro
		$this->id_centro = new cField('otros', 'otros', 'x_id_centro', 'id_centro', '`id_centro`', '`id_centro`', 3, -1, FALSE, '`id_centro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_centro->Sortable = FALSE; // Allow sort
		$this->id_centro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_centro'] = &$this->id_centro;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`otros`";
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
			return "otroslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "otrosview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "otrosedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "otrosadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "otroslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("otrosview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("otrosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "otrosadd.php?" . $this->UrlParm($parm);
		else
			$url = "otrosadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("otrosedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("otrosadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("otrosdelete.php", $this->UrlParm());
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
		$this->id_actividad->setDbValue($rs->fields('id_actividad'));
		$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
		$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->nrodiscapacidad->setDbValue($rs->fields('nrodiscapacidad'));
		$this->ci->setDbValue($rs->fields('ci'));
		$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
		$this->sexo->setDbValue($rs->fields('sexo'));
		$this->nivelestudio->setDbValue($rs->fields('nivelestudio'));
		$this->id_discapacidad->setDbValue($rs->fields('id_discapacidad'));
		$this->id_tipodiscapacidad->setDbValue($rs->fields('id_tipodiscapacidad'));
		$this->resultado->setDbValue($rs->fields('resultado'));
		$this->resultadotamizaje->setDbValue($rs->fields('resultadotamizaje'));
		$this->tapon->setDbValue($rs->fields('tapon'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->repetirprueba->setDbValue($rs->fields('repetirprueba'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->id_apoderado->setDbValue($rs->fields('id_apoderado'));
		$this->id_referencia->setDbValue($rs->fields('id_referencia'));
		$this->id_centro->setDbValue($rs->fields('id_centro'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// id_actividad
		// apellidopaterno
		// apellidomaterno
		// nombre
		// nrodiscapacidad
		// ci
		// fecha_nacimiento
		// sexo
		// nivelestudio
		// id_discapacidad
		// id_tipodiscapacidad
		// resultado
		// resultadotamizaje
		// tapon
		// tipo
		// repetirprueba
		// observaciones
		// id_apoderado
		// id_referencia
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_actividad
		if (strval($this->id_actividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
		$sWhereWrk = "";
		$this->id_actividad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_actividad->ViewValue = $this->id_actividad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_actividad->ViewValue = $this->id_actividad->CurrentValue;
			}
		} else {
			$this->id_actividad->ViewValue = NULL;
		}
		$this->id_actividad->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// nivelestudio
		$this->nivelestudio->ViewValue = $this->nivelestudio->CurrentValue;
		$this->nivelestudio->ViewCustomAttributes = "";

		// id_discapacidad
		if (strval($this->id_discapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
		$sWhereWrk = "";
		$this->id_discapacidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_discapacidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_discapacidad->ViewValue = $this->id_discapacidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_discapacidad->ViewValue = $this->id_discapacidad->CurrentValue;
			}
		} else {
			$this->id_discapacidad->ViewValue = NULL;
		}
		$this->id_discapacidad->ViewCustomAttributes = "";

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->CurrentValue;
		if (strval($this->id_tipodiscapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipodiscapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiscapacidad`";
		$sWhereWrk = "";
		$this->id_tipodiscapacidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->CurrentValue;
			}
		} else {
			$this->id_tipodiscapacidad->ViewValue = NULL;
		}
		$this->id_tipodiscapacidad->ViewCustomAttributes = "";

		// resultado
		if (strval($this->resultado->CurrentValue) <> "") {
			$this->resultado->ViewValue = $this->resultado->OptionCaption($this->resultado->CurrentValue);
		} else {
			$this->resultado->ViewValue = NULL;
		}
		$this->resultado->ViewCustomAttributes = "";

		// resultadotamizaje
		$this->resultadotamizaje->ViewValue = $this->resultadotamizaje->CurrentValue;
		$this->resultadotamizaje->ViewCustomAttributes = "";

		// tapon
		if (strval($this->tapon->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tapon->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tapon`";
		$sWhereWrk = "";
		$this->tapon->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tapon, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tapon->ViewValue = $this->tapon->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tapon->ViewValue = $this->tapon->CurrentValue;
			}
		} else {
			$this->tapon->ViewValue = NULL;
		}
		$this->tapon->ViewCustomAttributes = "";

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$this->tipo->ViewValue = $this->tipo->OptionCaption($this->tipo->CurrentValue);
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

		// repetirprueba
		$this->repetirprueba->ViewValue = $this->repetirprueba->CurrentValue;
		$this->repetirprueba->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_apoderado
		if (strval($this->id_apoderado->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_apoderado->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `apoderado`";
		$sWhereWrk = "";
		$this->id_apoderado->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_apoderado, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_apoderado->ViewValue = $this->id_apoderado->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_apoderado->ViewValue = $this->id_apoderado->CurrentValue;
			}
		} else {
			$this->id_apoderado->ViewValue = NULL;
		}
		$this->id_apoderado->ViewCustomAttributes = "";

		// id_referencia
		if (strval($this->id_referencia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_referencia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombrescentromedico` AS `DispFld`, `nombrescompleto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
		$sWhereWrk = "";
		$this->id_referencia->LookupFilters = array("dx1" => '`nombrescentromedico`', "dx2" => '`nombrescompleto`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_referencia, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->id_referencia->ViewValue = $this->id_referencia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_referencia->ViewValue = $this->id_referencia->CurrentValue;
			}
		} else {
			$this->id_referencia->ViewValue = NULL;
		}
		$this->id_referencia->ViewCustomAttributes = "";

		// id_centro
		$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
		$this->id_centro->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// id_actividad
		$this->id_actividad->LinkCustomAttributes = "";
		$this->id_actividad->HrefValue = "";
		$this->id_actividad->TooltipValue = "";

		// apellidopaterno
		$this->apellidopaterno->LinkCustomAttributes = "";
		$this->apellidopaterno->HrefValue = "";
		$this->apellidopaterno->TooltipValue = "";

		// apellidomaterno
		$this->apellidomaterno->LinkCustomAttributes = "";
		$this->apellidomaterno->HrefValue = "";
		$this->apellidomaterno->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->LinkCustomAttributes = "";
		$this->nrodiscapacidad->HrefValue = "";
		$this->nrodiscapacidad->TooltipValue = "";

		// ci
		$this->ci->LinkCustomAttributes = "";
		$this->ci->HrefValue = "";
		$this->ci->TooltipValue = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->LinkCustomAttributes = "";
		$this->fecha_nacimiento->HrefValue = "";
		$this->fecha_nacimiento->TooltipValue = "";

		// sexo
		$this->sexo->LinkCustomAttributes = "";
		$this->sexo->HrefValue = "";
		$this->sexo->TooltipValue = "";

		// nivelestudio
		$this->nivelestudio->LinkCustomAttributes = "";
		$this->nivelestudio->HrefValue = "";
		$this->nivelestudio->TooltipValue = "";

		// id_discapacidad
		$this->id_discapacidad->LinkCustomAttributes = "";
		$this->id_discapacidad->HrefValue = "";
		$this->id_discapacidad->TooltipValue = "";

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad->LinkCustomAttributes = "";
		$this->id_tipodiscapacidad->HrefValue = "";
		$this->id_tipodiscapacidad->TooltipValue = "";

		// resultado
		$this->resultado->LinkCustomAttributes = "";
		$this->resultado->HrefValue = "";
		$this->resultado->TooltipValue = "";

		// resultadotamizaje
		$this->resultadotamizaje->LinkCustomAttributes = "";
		$this->resultadotamizaje->HrefValue = "";
		$this->resultadotamizaje->TooltipValue = "";

		// tapon
		$this->tapon->LinkCustomAttributes = "";
		$this->tapon->HrefValue = "";
		$this->tapon->TooltipValue = "";

		// tipo
		$this->tipo->LinkCustomAttributes = "";
		$this->tipo->HrefValue = "";
		$this->tipo->TooltipValue = "";

		// repetirprueba
		$this->repetirprueba->LinkCustomAttributes = "";
		$this->repetirprueba->HrefValue = "";
		$this->repetirprueba->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// id_apoderado
		$this->id_apoderado->LinkCustomAttributes = "";
		$this->id_apoderado->HrefValue = "";
		$this->id_apoderado->TooltipValue = "";

		// id_referencia
		$this->id_referencia->LinkCustomAttributes = "";
		$this->id_referencia->HrefValue = "";
		$this->id_referencia->TooltipValue = "";

		// id_centro
		$this->id_centro->LinkCustomAttributes = "";
		$this->id_centro->HrefValue = "";
		$this->id_centro->TooltipValue = "";

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

		// id_actividad
		$this->id_actividad->EditAttrs["class"] = "form-control";
		$this->id_actividad->EditCustomAttributes = "";

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

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = $this->nombre->CurrentValue;
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

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

		// fecha_nacimiento
		$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
		$this->fecha_nacimiento->EditCustomAttributes = "";
		$this->fecha_nacimiento->EditValue = ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 8);
		$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

		// sexo
		$this->sexo->EditAttrs["class"] = "form-control";
		$this->sexo->EditCustomAttributes = "";
		$this->sexo->EditValue = $this->sexo->Options(TRUE);

		// nivelestudio
		$this->nivelestudio->EditAttrs["class"] = "form-control";
		$this->nivelestudio->EditCustomAttributes = "";
		$this->nivelestudio->EditValue = $this->nivelestudio->CurrentValue;
		$this->nivelestudio->PlaceHolder = ew_RemoveHtml($this->nivelestudio->FldCaption());

		// id_discapacidad
		$this->id_discapacidad->EditAttrs["class"] = "form-control";
		$this->id_discapacidad->EditCustomAttributes = "";

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad->EditAttrs["class"] = "form-control";
		$this->id_tipodiscapacidad->EditCustomAttributes = "";
		$this->id_tipodiscapacidad->EditValue = $this->id_tipodiscapacidad->CurrentValue;
		$this->id_tipodiscapacidad->PlaceHolder = ew_RemoveHtml($this->id_tipodiscapacidad->FldCaption());

		// resultado
		$this->resultado->EditCustomAttributes = "";
		$this->resultado->EditValue = $this->resultado->Options(FALSE);

		// resultadotamizaje
		$this->resultadotamizaje->EditAttrs["class"] = "form-control";
		$this->resultadotamizaje->EditCustomAttributes = "";
		$this->resultadotamizaje->EditValue = $this->resultadotamizaje->CurrentValue;
		$this->resultadotamizaje->PlaceHolder = ew_RemoveHtml($this->resultadotamizaje->FldCaption());

		// tapon
		$this->tapon->EditCustomAttributes = "";

		// tipo
		$this->tipo->EditCustomAttributes = "";
		$this->tipo->EditValue = $this->tipo->Options(FALSE);

		// repetirprueba
		$this->repetirprueba->EditAttrs["class"] = "form-control";
		$this->repetirprueba->EditCustomAttributes = "";
		$this->repetirprueba->EditValue = $this->repetirprueba->CurrentValue;
		$this->repetirprueba->PlaceHolder = ew_RemoveHtml($this->repetirprueba->FldCaption());

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// id_apoderado
		$this->id_apoderado->EditAttrs["class"] = "form-control";
		$this->id_apoderado->EditCustomAttributes = "";

		// id_referencia
		$this->id_referencia->EditAttrs["class"] = "form-control";
		$this->id_referencia->EditCustomAttributes = "";

		// id_centro
		$this->id_centro->EditAttrs["class"] = "form-control";
		$this->id_centro->EditCustomAttributes = "";
		$this->id_centro->EditValue = $this->id_centro->CurrentValue;
		$this->id_centro->PlaceHolder = ew_RemoveHtml($this->id_centro->FldCaption());

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
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->id_actividad->Exportable) $Doc->ExportCaption($this->id_actividad);
					if ($this->apellidopaterno->Exportable) $Doc->ExportCaption($this->apellidopaterno);
					if ($this->apellidomaterno->Exportable) $Doc->ExportCaption($this->apellidomaterno);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->nrodiscapacidad->Exportable) $Doc->ExportCaption($this->nrodiscapacidad);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->nivelestudio->Exportable) $Doc->ExportCaption($this->nivelestudio);
					if ($this->id_discapacidad->Exportable) $Doc->ExportCaption($this->id_discapacidad);
					if ($this->id_tipodiscapacidad->Exportable) $Doc->ExportCaption($this->id_tipodiscapacidad);
					if ($this->resultado->Exportable) $Doc->ExportCaption($this->resultado);
					if ($this->resultadotamizaje->Exportable) $Doc->ExportCaption($this->resultadotamizaje);
					if ($this->tapon->Exportable) $Doc->ExportCaption($this->tapon);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->repetirprueba->Exportable) $Doc->ExportCaption($this->repetirprueba);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->id_apoderado->Exportable) $Doc->ExportCaption($this->id_apoderado);
					if ($this->id_referencia->Exportable) $Doc->ExportCaption($this->id_referencia);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->id_actividad->Exportable) $Doc->ExportCaption($this->id_actividad);
					if ($this->apellidopaterno->Exportable) $Doc->ExportCaption($this->apellidopaterno);
					if ($this->apellidomaterno->Exportable) $Doc->ExportCaption($this->apellidomaterno);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->nrodiscapacidad->Exportable) $Doc->ExportCaption($this->nrodiscapacidad);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->nivelestudio->Exportable) $Doc->ExportCaption($this->nivelestudio);
					if ($this->id_discapacidad->Exportable) $Doc->ExportCaption($this->id_discapacidad);
					if ($this->id_tipodiscapacidad->Exportable) $Doc->ExportCaption($this->id_tipodiscapacidad);
					if ($this->resultado->Exportable) $Doc->ExportCaption($this->resultado);
					if ($this->resultadotamizaje->Exportable) $Doc->ExportCaption($this->resultadotamizaje);
					if ($this->tapon->Exportable) $Doc->ExportCaption($this->tapon);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->repetirprueba->Exportable) $Doc->ExportCaption($this->repetirprueba);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->id_apoderado->Exportable) $Doc->ExportCaption($this->id_apoderado);
					if ($this->id_referencia->Exportable) $Doc->ExportCaption($this->id_referencia);
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
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->id_actividad->Exportable) $Doc->ExportField($this->id_actividad);
						if ($this->apellidopaterno->Exportable) $Doc->ExportField($this->apellidopaterno);
						if ($this->apellidomaterno->Exportable) $Doc->ExportField($this->apellidomaterno);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->nrodiscapacidad->Exportable) $Doc->ExportField($this->nrodiscapacidad);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->nivelestudio->Exportable) $Doc->ExportField($this->nivelestudio);
						if ($this->id_discapacidad->Exportable) $Doc->ExportField($this->id_discapacidad);
						if ($this->id_tipodiscapacidad->Exportable) $Doc->ExportField($this->id_tipodiscapacidad);
						if ($this->resultado->Exportable) $Doc->ExportField($this->resultado);
						if ($this->resultadotamizaje->Exportable) $Doc->ExportField($this->resultadotamizaje);
						if ($this->tapon->Exportable) $Doc->ExportField($this->tapon);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->repetirprueba->Exportable) $Doc->ExportField($this->repetirprueba);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->id_apoderado->Exportable) $Doc->ExportField($this->id_apoderado);
						if ($this->id_referencia->Exportable) $Doc->ExportField($this->id_referencia);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->id_actividad->Exportable) $Doc->ExportField($this->id_actividad);
						if ($this->apellidopaterno->Exportable) $Doc->ExportField($this->apellidopaterno);
						if ($this->apellidomaterno->Exportable) $Doc->ExportField($this->apellidomaterno);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->nrodiscapacidad->Exportable) $Doc->ExportField($this->nrodiscapacidad);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->nivelestudio->Exportable) $Doc->ExportField($this->nivelestudio);
						if ($this->id_discapacidad->Exportable) $Doc->ExportField($this->id_discapacidad);
						if ($this->id_tipodiscapacidad->Exportable) $Doc->ExportField($this->id_tipodiscapacidad);
						if ($this->resultado->Exportable) $Doc->ExportField($this->resultado);
						if ($this->resultadotamizaje->Exportable) $Doc->ExportField($this->resultadotamizaje);
						if ($this->tapon->Exportable) $Doc->ExportField($this->tapon);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->repetirprueba->Exportable) $Doc->ExportField($this->repetirprueba);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->id_apoderado->Exportable) $Doc->ExportField($this->id_apoderado);
						if ($this->id_referencia->Exportable) $Doc->ExportField($this->id_referencia);
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
