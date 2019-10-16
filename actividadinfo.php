<?php

// Global variable for table object
$actividad = NULL;

//
// Table class for actividad
//
class cactividad extends cTable {
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
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'actividad';
		$this->TableName = 'actividad';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`actividad`";
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
		$this->id = new cField('actividad', 'actividad', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// id_sector
		$this->id_sector = new cField('actividad', 'actividad', 'x_id_sector', 'id_sector', '`id_sector`', '`id_sector`', 3, -1, FALSE, '`id_sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_sector->Sortable = TRUE; // Allow sort
		$this->id_sector->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_sector->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_sector->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_sector'] = &$this->id_sector;

		// id_tipoactividad
		$this->id_tipoactividad = new cField('actividad', 'actividad', 'x_id_tipoactividad', 'id_tipoactividad', '`id_tipoactividad`', '`id_tipoactividad`', 3, -1, FALSE, '`id_tipoactividad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_tipoactividad->Sortable = TRUE; // Allow sort
		$this->id_tipoactividad->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_tipoactividad->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_tipoactividad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tipoactividad'] = &$this->id_tipoactividad;

		// organizador
		$this->organizador = new cField('actividad', 'actividad', 'x_organizador', 'organizador', '`organizador`', '`organizador`', 3, -1, FALSE, '`organizador`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->organizador->Sortable = TRUE; // Allow sort
		$this->organizador->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->organizador->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['organizador'] = &$this->organizador;

		// nombreactividad
		$this->nombreactividad = new cField('actividad', 'actividad', 'x_nombreactividad', 'nombreactividad', '`nombreactividad`', '`nombreactividad`', 200, -1, FALSE, '`nombreactividad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombreactividad->Sortable = TRUE; // Allow sort
		$this->fields['nombreactividad'] = &$this->nombreactividad;

		// nombrelocal
		$this->nombrelocal = new cField('actividad', 'actividad', 'x_nombrelocal', 'nombrelocal', '`nombrelocal`', '`nombrelocal`', 200, -1, FALSE, '`nombrelocal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombrelocal->Sortable = TRUE; // Allow sort
		$this->fields['nombrelocal'] = &$this->nombrelocal;

		// direccionlocal
		$this->direccionlocal = new cField('actividad', 'actividad', 'x_direccionlocal', 'direccionlocal', '`direccionlocal`', '`direccionlocal`', 200, -1, FALSE, '`direccionlocal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccionlocal->Sortable = TRUE; // Allow sort
		$this->fields['direccionlocal'] = &$this->direccionlocal;

		// fecha_inicio
		$this->fecha_inicio = new cField('actividad', 'actividad', 'x_fecha_inicio', 'fecha_inicio', '`fecha_inicio`', ew_CastDateFieldForLike('`fecha_inicio`', 0, "DB"), 133, 0, FALSE, '`fecha_inicio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_inicio->Sortable = TRUE; // Allow sort
		$this->fecha_inicio->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha_inicio'] = &$this->fecha_inicio;

		// fecha_fin
		$this->fecha_fin = new cField('actividad', 'actividad', 'x_fecha_fin', 'fecha_fin', '`fecha_fin`', ew_CastDateFieldForLike('`fecha_fin`', 0, "DB"), 133, 0, FALSE, '`fecha_fin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_fin->Sortable = TRUE; // Allow sort
		$this->fecha_fin->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha_fin'] = &$this->fecha_fin;

		// horasprogramadas
		$this->horasprogramadas = new cField('actividad', 'actividad', 'x_horasprogramadas', 'horasprogramadas', '`horasprogramadas`', '`horasprogramadas`', 200, -1, FALSE, '`horasprogramadas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->horasprogramadas->Sortable = TRUE; // Allow sort
		$this->fields['horasprogramadas'] = &$this->horasprogramadas;

		// id_persona
		$this->id_persona = new cField('actividad', 'actividad', 'x_id_persona', 'id_persona', '`id_persona`', '`id_persona`', 3, -1, FALSE, '`EV__id_persona`', TRUE, FALSE, TRUE, 'FORMATTED TEXT', 'TEXT');
		$this->id_persona->Sortable = TRUE; // Allow sort
		$this->fields['id_persona'] = &$this->id_persona;

		// contenido
		$this->contenido = new cField('actividad', 'actividad', 'x_contenido', 'contenido', '`contenido`', '`contenido`', 200, -1, FALSE, '`contenido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->contenido->Sortable = TRUE; // Allow sort
		$this->fields['contenido'] = &$this->contenido;

		// observaciones
		$this->observaciones = new cField('actividad', 'actividad', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 200, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// id_centro
		$this->id_centro = new cField('actividad', 'actividad', 'x_id_centro', 'id_centro', '`id_centro`', '`id_centro`', 3, -1, FALSE, '`id_centro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_centro->Sortable = FALSE; // Allow sort
		$this->id_centro->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_centro->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`actividad`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT CONCAT(COALESCE(`nombre`, ''),'" . ew_ValueSeparator(1, $this->id_persona) . "',COALESCE(`apellidopaterno`,''),'" . ew_ValueSeparator(2, $this->id_persona) . "',COALESCE(`apellidomaterno`,'')) FROM `persona` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `actividad`.`id_persona` LIMIT 1) AS `EV__id_persona` FROM `actividad`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "`id_centro` like  '".$_SESSION["centro"]."'";
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
		if ($this->UseVirtualFields()) {
			$sSelect = $this->getSqlSelectList();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		} else {
			$sSelect = $this->getSqlSelect();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		}
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->UseSessionForListSQL ? $this->getSessionWhere() : $this->CurrentFilter;
		$sOrderBy = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->BasicSearch->getKeyword() <> "")
			return TRUE;
		if ($this->id_persona->AdvancedSearch->SearchValue <> "" ||
			$this->id_persona->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_persona->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_persona->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
		if ($this->UseVirtualFields())
			$sql = ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		else
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
			return "actividadlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "actividadview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "actividadedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "actividadadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "actividadlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("actividadview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("actividadview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "actividadadd.php?" . $this->UrlParm($parm);
		else
			$url = "actividadadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("actividadedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("actividadadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("actividaddelete.php", $this->UrlParm());
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
		$this->id_sector->setDbValue($rs->fields('id_sector'));
		$this->id_tipoactividad->setDbValue($rs->fields('id_tipoactividad'));
		$this->organizador->setDbValue($rs->fields('organizador'));
		$this->nombreactividad->setDbValue($rs->fields('nombreactividad'));
		$this->nombrelocal->setDbValue($rs->fields('nombrelocal'));
		$this->direccionlocal->setDbValue($rs->fields('direccionlocal'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->horasprogramadas->setDbValue($rs->fields('horasprogramadas'));
		$this->id_persona->setDbValue($rs->fields('id_persona'));
		$this->contenido->setDbValue($rs->fields('contenido'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->id_centro->setDbValue($rs->fields('id_centro'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// id_sector
		// id_tipoactividad
		// organizador
		// nombreactividad
		// nombrelocal
		// direccionlocal
		// fecha_inicio
		// fecha_fin
		// horasprogramadas
		// id_persona
		// contenido
		// observaciones
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_sector
		if (strval($this->id_sector->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_sector->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sector`";
		$sWhereWrk = "";
		$this->id_sector->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_sector, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_sector->ViewValue = $this->id_sector->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_sector->ViewValue = $this->id_sector->CurrentValue;
			}
		} else {
			$this->id_sector->ViewValue = NULL;
		}
		$this->id_sector->ViewCustomAttributes = "";

		// id_tipoactividad
		if (strval($this->id_tipoactividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipoactividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoactividad`";
		$sWhereWrk = "";
		$this->id_tipoactividad->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipoactividad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipoactividad->ViewValue = $this->id_tipoactividad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipoactividad->ViewValue = $this->id_tipoactividad->CurrentValue;
			}
		} else {
			$this->id_tipoactividad->ViewValue = NULL;
		}
		$this->id_tipoactividad->ViewCustomAttributes = "";

		// organizador
		if (strval($this->organizador->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->organizador->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->organizador->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->organizador, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->organizador->ViewValue = $this->organizador->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->organizador->ViewValue = $this->organizador->CurrentValue;
			}
		} else {
			$this->organizador->ViewValue = NULL;
		}
		$this->organizador->ViewCustomAttributes = "";

		// nombreactividad
		$this->nombreactividad->ViewValue = $this->nombreactividad->CurrentValue;
		$this->nombreactividad->ViewCustomAttributes = "";

		// nombrelocal
		$this->nombrelocal->ViewValue = $this->nombrelocal->CurrentValue;
		$this->nombrelocal->ViewCustomAttributes = "";

		// direccionlocal
		$this->direccionlocal->ViewValue = $this->direccionlocal->CurrentValue;
		$this->direccionlocal->ViewCustomAttributes = "";

		// fecha_inicio
		$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
		$this->fecha_inicio->ViewValue = ew_FormatDateTime($this->fecha_inicio->ViewValue, 0);
		$this->fecha_inicio->ViewCustomAttributes = "";

		// fecha_fin
		$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
		$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 0);
		$this->fecha_fin->ViewCustomAttributes = "";

		// horasprogramadas
		$this->horasprogramadas->ViewValue = $this->horasprogramadas->CurrentValue;
		$this->horasprogramadas->ViewCustomAttributes = "";

		// id_persona
		if ($this->id_persona->VirtualValue <> "") {
			$this->id_persona->ViewValue = $this->id_persona->VirtualValue;
		} else {
			$this->id_persona->ViewValue = $this->id_persona->CurrentValue;
		if (strval($this->id_persona->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `persona`";
		$sWhereWrk = "";
		$this->id_persona->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_persona->ViewValue = $this->id_persona->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_persona->ViewValue = $this->id_persona->CurrentValue;
			}
		} else {
			$this->id_persona->ViewValue = NULL;
		}
		}
		$this->id_persona->ViewCustomAttributes = "";

		// contenido
		$this->contenido->ViewValue = $this->contenido->CurrentValue;
		$this->contenido->ViewCustomAttributes = "";

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

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// id_sector
		$this->id_sector->LinkCustomAttributes = "";
		$this->id_sector->HrefValue = "";
		$this->id_sector->TooltipValue = "";

		// id_tipoactividad
		$this->id_tipoactividad->LinkCustomAttributes = "";
		$this->id_tipoactividad->HrefValue = "";
		$this->id_tipoactividad->TooltipValue = "";

		// organizador
		$this->organizador->LinkCustomAttributes = "";
		$this->organizador->HrefValue = "";
		$this->organizador->TooltipValue = "";

		// nombreactividad
		$this->nombreactividad->LinkCustomAttributes = "";
		$this->nombreactividad->HrefValue = "";
		$this->nombreactividad->TooltipValue = "";

		// nombrelocal
		$this->nombrelocal->LinkCustomAttributes = "";
		$this->nombrelocal->HrefValue = "";
		$this->nombrelocal->TooltipValue = "";

		// direccionlocal
		$this->direccionlocal->LinkCustomAttributes = "";
		$this->direccionlocal->HrefValue = "";
		$this->direccionlocal->TooltipValue = "";

		// fecha_inicio
		$this->fecha_inicio->LinkCustomAttributes = "";
		$this->fecha_inicio->HrefValue = "";
		$this->fecha_inicio->TooltipValue = "";

		// fecha_fin
		$this->fecha_fin->LinkCustomAttributes = "";
		$this->fecha_fin->HrefValue = "";
		$this->fecha_fin->TooltipValue = "";

		// horasprogramadas
		$this->horasprogramadas->LinkCustomAttributes = "";
		$this->horasprogramadas->HrefValue = "";
		$this->horasprogramadas->TooltipValue = "";

		// id_persona
		$this->id_persona->LinkCustomAttributes = "";
		$this->id_persona->HrefValue = "";
		$this->id_persona->TooltipValue = "";

		// contenido
		$this->contenido->LinkCustomAttributes = "";
		$this->contenido->HrefValue = "";
		$this->contenido->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

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

		// id_sector
		$this->id_sector->EditAttrs["class"] = "form-control";
		$this->id_sector->EditCustomAttributes = "";

		// id_tipoactividad
		$this->id_tipoactividad->EditAttrs["class"] = "form-control";
		$this->id_tipoactividad->EditCustomAttributes = "";

		// organizador
		$this->organizador->EditAttrs["class"] = "form-control";
		$this->organizador->EditCustomAttributes = "";

		// nombreactividad
		$this->nombreactividad->EditAttrs["class"] = "form-control";
		$this->nombreactividad->EditCustomAttributes = "";
		$this->nombreactividad->EditValue = $this->nombreactividad->CurrentValue;
		$this->nombreactividad->PlaceHolder = ew_RemoveHtml($this->nombreactividad->FldCaption());

		// nombrelocal
		$this->nombrelocal->EditAttrs["class"] = "form-control";
		$this->nombrelocal->EditCustomAttributes = "";
		$this->nombrelocal->EditValue = $this->nombrelocal->CurrentValue;
		$this->nombrelocal->PlaceHolder = ew_RemoveHtml($this->nombrelocal->FldCaption());

		// direccionlocal
		$this->direccionlocal->EditAttrs["class"] = "form-control";
		$this->direccionlocal->EditCustomAttributes = "";
		$this->direccionlocal->EditValue = $this->direccionlocal->CurrentValue;
		$this->direccionlocal->PlaceHolder = ew_RemoveHtml($this->direccionlocal->FldCaption());

		// fecha_inicio
		$this->fecha_inicio->EditAttrs["class"] = "form-control";
		$this->fecha_inicio->EditCustomAttributes = "";
		$this->fecha_inicio->EditValue = ew_FormatDateTime($this->fecha_inicio->CurrentValue, 8);
		$this->fecha_inicio->PlaceHolder = ew_RemoveHtml($this->fecha_inicio->FldCaption());

		// fecha_fin
		$this->fecha_fin->EditAttrs["class"] = "form-control";
		$this->fecha_fin->EditCustomAttributes = "";
		$this->fecha_fin->EditValue = ew_FormatDateTime($this->fecha_fin->CurrentValue, 8);
		$this->fecha_fin->PlaceHolder = ew_RemoveHtml($this->fecha_fin->FldCaption());

		// horasprogramadas
		$this->horasprogramadas->EditAttrs["class"] = "form-control";
		$this->horasprogramadas->EditCustomAttributes = "";
		$this->horasprogramadas->EditValue = $this->horasprogramadas->CurrentValue;
		$this->horasprogramadas->PlaceHolder = ew_RemoveHtml($this->horasprogramadas->FldCaption());

		// id_persona
		$this->id_persona->EditAttrs["class"] = "form-control";
		$this->id_persona->EditCustomAttributes = "";
		$this->id_persona->EditValue = $this->id_persona->CurrentValue;
		$this->id_persona->PlaceHolder = ew_RemoveHtml($this->id_persona->FldCaption());

		// contenido
		$this->contenido->EditAttrs["class"] = "form-control";
		$this->contenido->EditCustomAttributes = "";
		$this->contenido->EditValue = $this->contenido->CurrentValue;
		$this->contenido->PlaceHolder = ew_RemoveHtml($this->contenido->FldCaption());

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// id_centro
		$this->id_centro->EditAttrs["class"] = "form-control";
		$this->id_centro->EditCustomAttributes = "";

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
					if ($this->id_sector->Exportable) $Doc->ExportCaption($this->id_sector);
					if ($this->id_tipoactividad->Exportable) $Doc->ExportCaption($this->id_tipoactividad);
					if ($this->organizador->Exportable) $Doc->ExportCaption($this->organizador);
					if ($this->nombreactividad->Exportable) $Doc->ExportCaption($this->nombreactividad);
					if ($this->nombrelocal->Exportable) $Doc->ExportCaption($this->nombrelocal);
					if ($this->direccionlocal->Exportable) $Doc->ExportCaption($this->direccionlocal);
					if ($this->fecha_inicio->Exportable) $Doc->ExportCaption($this->fecha_inicio);
					if ($this->fecha_fin->Exportable) $Doc->ExportCaption($this->fecha_fin);
					if ($this->horasprogramadas->Exportable) $Doc->ExportCaption($this->horasprogramadas);
					if ($this->id_persona->Exportable) $Doc->ExportCaption($this->id_persona);
					if ($this->contenido->Exportable) $Doc->ExportCaption($this->contenido);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->id_sector->Exportable) $Doc->ExportCaption($this->id_sector);
					if ($this->id_tipoactividad->Exportable) $Doc->ExportCaption($this->id_tipoactividad);
					if ($this->organizador->Exportable) $Doc->ExportCaption($this->organizador);
					if ($this->nombreactividad->Exportable) $Doc->ExportCaption($this->nombreactividad);
					if ($this->nombrelocal->Exportable) $Doc->ExportCaption($this->nombrelocal);
					if ($this->direccionlocal->Exportable) $Doc->ExportCaption($this->direccionlocal);
					if ($this->fecha_inicio->Exportable) $Doc->ExportCaption($this->fecha_inicio);
					if ($this->fecha_fin->Exportable) $Doc->ExportCaption($this->fecha_fin);
					if ($this->horasprogramadas->Exportable) $Doc->ExportCaption($this->horasprogramadas);
					if ($this->id_persona->Exportable) $Doc->ExportCaption($this->id_persona);
					if ($this->contenido->Exportable) $Doc->ExportCaption($this->contenido);
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
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->id_sector->Exportable) $Doc->ExportField($this->id_sector);
						if ($this->id_tipoactividad->Exportable) $Doc->ExportField($this->id_tipoactividad);
						if ($this->organizador->Exportable) $Doc->ExportField($this->organizador);
						if ($this->nombreactividad->Exportable) $Doc->ExportField($this->nombreactividad);
						if ($this->nombrelocal->Exportable) $Doc->ExportField($this->nombrelocal);
						if ($this->direccionlocal->Exportable) $Doc->ExportField($this->direccionlocal);
						if ($this->fecha_inicio->Exportable) $Doc->ExportField($this->fecha_inicio);
						if ($this->fecha_fin->Exportable) $Doc->ExportField($this->fecha_fin);
						if ($this->horasprogramadas->Exportable) $Doc->ExportField($this->horasprogramadas);
						if ($this->id_persona->Exportable) $Doc->ExportField($this->id_persona);
						if ($this->contenido->Exportable) $Doc->ExportField($this->contenido);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->id_sector->Exportable) $Doc->ExportField($this->id_sector);
						if ($this->id_tipoactividad->Exportable) $Doc->ExportField($this->id_tipoactividad);
						if ($this->organizador->Exportable) $Doc->ExportField($this->organizador);
						if ($this->nombreactividad->Exportable) $Doc->ExportField($this->nombreactividad);
						if ($this->nombrelocal->Exportable) $Doc->ExportField($this->nombrelocal);
						if ($this->direccionlocal->Exportable) $Doc->ExportField($this->direccionlocal);
						if ($this->fecha_inicio->Exportable) $Doc->ExportField($this->fecha_inicio);
						if ($this->fecha_fin->Exportable) $Doc->ExportField($this->fecha_fin);
						if ($this->horasprogramadas->Exportable) $Doc->ExportField($this->horasprogramadas);
						if ($this->id_persona->Exportable) $Doc->ExportField($this->id_persona);
						if ($this->contenido->Exportable) $Doc->ExportField($this->contenido);
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
