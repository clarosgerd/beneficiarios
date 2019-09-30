<?php

// Global variable for table object
$viewestudiante = NULL;

//
// Table class for viewestudiante
//
class cviewestudiante extends cTable {
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
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'viewestudiante';
		$this->TableName = 'viewestudiante';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`viewestudiante`";
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

		// departamento
		$this->departamento = new cField('viewestudiante', 'viewestudiante', 'x_departamento', 'departamento', '`departamento`', '`departamento`', 200, -1, FALSE, '`departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->departamento->Sortable = TRUE; // Allow sort
		$this->fields['departamento'] = &$this->departamento;

		// codigorude
		$this->codigorude = new cField('viewestudiante', 'viewestudiante', 'x_codigorude', 'codigorude', '`codigorude`', '`codigorude`', 200, -1, FALSE, '`codigorude`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigorude->Sortable = TRUE; // Allow sort
		$this->fields['codigorude'] = &$this->codigorude;

		// codigorude_es
		$this->codigorude_es = new cField('viewestudiante', 'viewestudiante', 'x_codigorude_es', 'codigorude_es', '`codigorude_es`', '`codigorude_es`', 200, -1, FALSE, '`codigorude_es`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigorude_es->Sortable = TRUE; // Allow sort
		$this->fields['codigorude_es'] = &$this->codigorude_es;

		// municipio
		$this->municipio = new cField('viewestudiante', 'viewestudiante', 'x_municipio', 'municipio', '`municipio`', '`municipio`', 200, -1, FALSE, '`municipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->municipio->Sortable = TRUE; // Allow sort
		$this->fields['municipio'] = &$this->municipio;

		// provincia
		$this->provincia = new cField('viewestudiante', 'viewestudiante', 'x_provincia', 'provincia', '`provincia`', '`provincia`', 200, -1, FALSE, '`provincia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->provincia->Sortable = TRUE; // Allow sort
		$this->fields['provincia'] = &$this->provincia;

		// unidadeducativa
		$this->unidadeducativa = new cField('viewestudiante', 'viewestudiante', 'x_unidadeducativa', 'unidadeducativa', '`unidadeducativa`', '`unidadeducativa`', 200, -1, FALSE, '`unidadeducativa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->unidadeducativa->Sortable = TRUE; // Allow sort
		$this->fields['unidadeducativa'] = &$this->unidadeducativa;

		// nombre
		$this->nombre = new cField('viewestudiante', 'viewestudiante', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->fields['nombre'] = &$this->nombre;

		// materno
		$this->materno = new cField('viewestudiante', 'viewestudiante', 'x_materno', 'materno', '`materno`', '`materno`', 200, -1, FALSE, '`materno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->materno->Sortable = TRUE; // Allow sort
		$this->fields['materno'] = &$this->materno;

		// paterno
		$this->paterno = new cField('viewestudiante', 'viewestudiante', 'x_paterno', 'paterno', '`paterno`', '`paterno`', 200, -1, FALSE, '`paterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->paterno->Sortable = TRUE; // Allow sort
		$this->fields['paterno'] = &$this->paterno;

		// nrodiscapacidad
		$this->nrodiscapacidad = new cField('viewestudiante', 'viewestudiante', 'x_nrodiscapacidad', 'nrodiscapacidad', '`nrodiscapacidad`', '`nrodiscapacidad`', 200, -1, FALSE, '`nrodiscapacidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nrodiscapacidad->Sortable = TRUE; // Allow sort
		$this->fields['nrodiscapacidad'] = &$this->nrodiscapacidad;

		// ci
		$this->ci = new cField('viewestudiante', 'viewestudiante', 'x_ci', 'ci', '`ci`', '`ci`', 200, -1, FALSE, '`ci`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ci->Sortable = TRUE; // Allow sort
		$this->fields['ci'] = &$this->ci;

		// fechanacimiento
		$this->fechanacimiento = new cField('viewestudiante', 'viewestudiante', 'x_fechanacimiento', 'fechanacimiento', '`fechanacimiento`', ew_CastDateFieldForLike('`fechanacimiento`', 7, "DB"), 133, 7, FALSE, '`fechanacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechanacimiento->Sortable = TRUE; // Allow sort
		$this->fechanacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechanacimiento'] = &$this->fechanacimiento;

		// edad
		$this->edad = new cField('viewestudiante', 'viewestudiante', 'x_edad', 'edad', '`edad`', '`edad`', 20, -1, FALSE, '`edad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->edad->Sortable = TRUE; // Allow sort
		$this->edad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['edad'] = &$this->edad;

		// sexo
		$this->sexo = new cField('viewestudiante', 'viewestudiante', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 200, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sexo->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sexo->OptionCount = 2;
		$this->sexo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sexo'] = &$this->sexo;

		// curso
		$this->curso = new cField('viewestudiante', 'viewestudiante', 'x_curso', 'curso', '`curso`', '`curso`', 200, -1, FALSE, '`curso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->curso->Sortable = TRUE; // Allow sort
		$this->fields['curso'] = &$this->curso;

		// discapacidad
		$this->discapacidad = new cField('viewestudiante', 'viewestudiante', 'x_discapacidad', 'discapacidad', '`discapacidad`', '`discapacidad`', 200, -1, FALSE, '`discapacidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->fields['discapacidad'] = &$this->discapacidad;

		// tipodiscapcidad
		$this->tipodiscapcidad = new cField('viewestudiante', 'viewestudiante', 'x_tipodiscapcidad', 'tipodiscapcidad', '`tipodiscapcidad`', '`tipodiscapcidad`', 200, -1, FALSE, '`tipodiscapcidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipodiscapcidad->Sortable = TRUE; // Allow sort
		$this->fields['tipodiscapcidad'] = &$this->tipodiscapcidad;

		// nombreinstitucion
		$this->nombreinstitucion = new cField('viewestudiante', 'viewestudiante', 'x_nombreinstitucion', 'nombreinstitucion', '`nombreinstitucion`', '`nombreinstitucion`', 200, -1, FALSE, '`nombreinstitucion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombreinstitucion->Sortable = TRUE; // Allow sort
		$this->fields['nombreinstitucion'] = &$this->nombreinstitucion;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewestudiante`";
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
		$this->TableFilter = "";
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
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
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
			return "viewestudiantelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "viewestudianteview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "viewestudianteedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "viewestudianteadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "viewestudiantelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("viewestudianteview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("viewestudianteview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "viewestudianteadd.php?" . $this->UrlParm($parm);
		else
			$url = "viewestudianteadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("viewestudianteedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("viewestudianteadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("viewestudiantedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
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

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
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
		$this->departamento->setDbValue($rs->fields('departamento'));
		$this->codigorude->setDbValue($rs->fields('codigorude'));
		$this->codigorude_es->setDbValue($rs->fields('codigorude_es'));
		$this->municipio->setDbValue($rs->fields('municipio'));
		$this->provincia->setDbValue($rs->fields('provincia'));
		$this->unidadeducativa->setDbValue($rs->fields('unidadeducativa'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->materno->setDbValue($rs->fields('materno'));
		$this->paterno->setDbValue($rs->fields('paterno'));
		$this->nrodiscapacidad->setDbValue($rs->fields('nrodiscapacidad'));
		$this->ci->setDbValue($rs->fields('ci'));
		$this->fechanacimiento->setDbValue($rs->fields('fechanacimiento'));
		$this->edad->setDbValue($rs->fields('edad'));
		$this->sexo->setDbValue($rs->fields('sexo'));
		$this->curso->setDbValue($rs->fields('curso'));
		$this->discapacidad->setDbValue($rs->fields('discapacidad'));
		$this->tipodiscapcidad->setDbValue($rs->fields('tipodiscapcidad'));
		$this->nombreinstitucion->setDbValue($rs->fields('nombreinstitucion'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// departamento
		// codigorude
		// codigorude_es
		// municipio
		// provincia
		// unidadeducativa
		// nombre
		// materno
		// paterno
		// nrodiscapacidad
		// ci
		// fechanacimiento
		// edad
		// sexo
		// curso
		// discapacidad
		// tipodiscapcidad
		// nombreinstitucion
		// departamento

		$this->departamento->ViewValue = $this->departamento->CurrentValue;
		$this->departamento->ViewCustomAttributes = "";

		// codigorude
		$this->codigorude->ViewValue = $this->codigorude->CurrentValue;
		$this->codigorude->ViewCustomAttributes = "";

		// codigorude_es
		$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
		$this->codigorude_es->ViewCustomAttributes = "";

		// municipio
		$this->municipio->ViewValue = $this->municipio->CurrentValue;
		$this->municipio->ViewCustomAttributes = "";

		// provincia
		$this->provincia->ViewValue = $this->provincia->CurrentValue;
		$this->provincia->ViewCustomAttributes = "";

		// unidadeducativa
		$this->unidadeducativa->ViewValue = $this->unidadeducativa->CurrentValue;
		$this->unidadeducativa->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// materno
		$this->materno->ViewValue = $this->materno->CurrentValue;
		$this->materno->ViewCustomAttributes = "";

		// paterno
		$this->paterno->ViewValue = $this->paterno->CurrentValue;
		$this->paterno->ViewCustomAttributes = "";

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

		// edad
		$this->edad->ViewValue = $this->edad->CurrentValue;
		$this->edad->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// curso
		$this->curso->ViewValue = $this->curso->CurrentValue;
		$this->curso->ViewCustomAttributes = "";

		// discapacidad
		$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->ViewCustomAttributes = "";

		// tipodiscapcidad
		$this->tipodiscapcidad->ViewValue = $this->tipodiscapcidad->CurrentValue;
		$this->tipodiscapcidad->ViewCustomAttributes = "";

		// nombreinstitucion
		$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
		$this->nombreinstitucion->ViewCustomAttributes = "";

		// departamento
		$this->departamento->LinkCustomAttributes = "";
		$this->departamento->HrefValue = "";
		$this->departamento->TooltipValue = "";

		// codigorude
		$this->codigorude->LinkCustomAttributes = "";
		$this->codigorude->HrefValue = "";
		$this->codigorude->TooltipValue = "";

		// codigorude_es
		$this->codigorude_es->LinkCustomAttributes = "";
		$this->codigorude_es->HrefValue = "";
		$this->codigorude_es->TooltipValue = "";

		// municipio
		$this->municipio->LinkCustomAttributes = "";
		$this->municipio->HrefValue = "";
		$this->municipio->TooltipValue = "";

		// provincia
		$this->provincia->LinkCustomAttributes = "";
		$this->provincia->HrefValue = "";
		$this->provincia->TooltipValue = "";

		// unidadeducativa
		$this->unidadeducativa->LinkCustomAttributes = "";
		$this->unidadeducativa->HrefValue = "";
		$this->unidadeducativa->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// materno
		$this->materno->LinkCustomAttributes = "";
		$this->materno->HrefValue = "";
		$this->materno->TooltipValue = "";

		// paterno
		$this->paterno->LinkCustomAttributes = "";
		$this->paterno->HrefValue = "";
		$this->paterno->TooltipValue = "";

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

		// edad
		$this->edad->LinkCustomAttributes = "";
		$this->edad->HrefValue = "";
		$this->edad->TooltipValue = "";

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

		// tipodiscapcidad
		$this->tipodiscapcidad->LinkCustomAttributes = "";
		$this->tipodiscapcidad->HrefValue = "";
		$this->tipodiscapcidad->TooltipValue = "";

		// nombreinstitucion
		$this->nombreinstitucion->LinkCustomAttributes = "";
		$this->nombreinstitucion->HrefValue = "";
		$this->nombreinstitucion->TooltipValue = "";

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

		// departamento
		$this->departamento->EditAttrs["class"] = "form-control";
		$this->departamento->EditCustomAttributes = "";
		$this->departamento->EditValue = $this->departamento->CurrentValue;
		$this->departamento->PlaceHolder = ew_RemoveHtml($this->departamento->FldCaption());

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

		// municipio
		$this->municipio->EditAttrs["class"] = "form-control";
		$this->municipio->EditCustomAttributes = "";
		$this->municipio->EditValue = $this->municipio->CurrentValue;
		$this->municipio->PlaceHolder = ew_RemoveHtml($this->municipio->FldCaption());

		// provincia
		$this->provincia->EditAttrs["class"] = "form-control";
		$this->provincia->EditCustomAttributes = "";
		$this->provincia->EditValue = $this->provincia->CurrentValue;
		$this->provincia->PlaceHolder = ew_RemoveHtml($this->provincia->FldCaption());

		// unidadeducativa
		$this->unidadeducativa->EditAttrs["class"] = "form-control";
		$this->unidadeducativa->EditCustomAttributes = "";
		$this->unidadeducativa->EditValue = $this->unidadeducativa->CurrentValue;
		$this->unidadeducativa->PlaceHolder = ew_RemoveHtml($this->unidadeducativa->FldCaption());

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = $this->nombre->CurrentValue;
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

		// materno
		$this->materno->EditAttrs["class"] = "form-control";
		$this->materno->EditCustomAttributes = "";
		$this->materno->EditValue = $this->materno->CurrentValue;
		$this->materno->PlaceHolder = ew_RemoveHtml($this->materno->FldCaption());

		// paterno
		$this->paterno->EditAttrs["class"] = "form-control";
		$this->paterno->EditCustomAttributes = "";
		$this->paterno->EditValue = $this->paterno->CurrentValue;
		$this->paterno->PlaceHolder = ew_RemoveHtml($this->paterno->FldCaption());

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

		// edad
		$this->edad->EditAttrs["class"] = "form-control";
		$this->edad->EditCustomAttributes = "";
		$this->edad->EditValue = $this->edad->CurrentValue;
		$this->edad->PlaceHolder = ew_RemoveHtml($this->edad->FldCaption());

		// sexo
		$this->sexo->EditAttrs["class"] = "form-control";
		$this->sexo->EditCustomAttributes = "";
		$this->sexo->EditValue = $this->sexo->Options(TRUE);

		// curso
		$this->curso->EditAttrs["class"] = "form-control";
		$this->curso->EditCustomAttributes = "";
		$this->curso->EditValue = $this->curso->CurrentValue;
		$this->curso->PlaceHolder = ew_RemoveHtml($this->curso->FldCaption());

		// discapacidad
		$this->discapacidad->EditAttrs["class"] = "form-control";
		$this->discapacidad->EditCustomAttributes = "";
		$this->discapacidad->EditValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

		// tipodiscapcidad
		$this->tipodiscapcidad->EditAttrs["class"] = "form-control";
		$this->tipodiscapcidad->EditCustomAttributes = "";
		$this->tipodiscapcidad->EditValue = $this->tipodiscapcidad->CurrentValue;
		$this->tipodiscapcidad->PlaceHolder = ew_RemoveHtml($this->tipodiscapcidad->FldCaption());

		// nombreinstitucion
		$this->nombreinstitucion->EditAttrs["class"] = "form-control";
		$this->nombreinstitucion->EditCustomAttributes = "";
		$this->nombreinstitucion->EditValue = $this->nombreinstitucion->CurrentValue;
		$this->nombreinstitucion->PlaceHolder = ew_RemoveHtml($this->nombreinstitucion->FldCaption());

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
					if ($this->departamento->Exportable) $Doc->ExportCaption($this->departamento);
					if ($this->codigorude->Exportable) $Doc->ExportCaption($this->codigorude);
					if ($this->codigorude_es->Exportable) $Doc->ExportCaption($this->codigorude_es);
					if ($this->municipio->Exportable) $Doc->ExportCaption($this->municipio);
					if ($this->provincia->Exportable) $Doc->ExportCaption($this->provincia);
					if ($this->unidadeducativa->Exportable) $Doc->ExportCaption($this->unidadeducativa);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->materno->Exportable) $Doc->ExportCaption($this->materno);
					if ($this->paterno->Exportable) $Doc->ExportCaption($this->paterno);
					if ($this->nrodiscapacidad->Exportable) $Doc->ExportCaption($this->nrodiscapacidad);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fechanacimiento->Exportable) $Doc->ExportCaption($this->fechanacimiento);
					if ($this->edad->Exportable) $Doc->ExportCaption($this->edad);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->curso->Exportable) $Doc->ExportCaption($this->curso);
					if ($this->discapacidad->Exportable) $Doc->ExportCaption($this->discapacidad);
					if ($this->tipodiscapcidad->Exportable) $Doc->ExportCaption($this->tipodiscapcidad);
					if ($this->nombreinstitucion->Exportable) $Doc->ExportCaption($this->nombreinstitucion);
				} else {
					if ($this->departamento->Exportable) $Doc->ExportCaption($this->departamento);
					if ($this->codigorude->Exportable) $Doc->ExportCaption($this->codigorude);
					if ($this->codigorude_es->Exportable) $Doc->ExportCaption($this->codigorude_es);
					if ($this->municipio->Exportable) $Doc->ExportCaption($this->municipio);
					if ($this->provincia->Exportable) $Doc->ExportCaption($this->provincia);
					if ($this->unidadeducativa->Exportable) $Doc->ExportCaption($this->unidadeducativa);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->materno->Exportable) $Doc->ExportCaption($this->materno);
					if ($this->paterno->Exportable) $Doc->ExportCaption($this->paterno);
					if ($this->nrodiscapacidad->Exportable) $Doc->ExportCaption($this->nrodiscapacidad);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fechanacimiento->Exportable) $Doc->ExportCaption($this->fechanacimiento);
					if ($this->edad->Exportable) $Doc->ExportCaption($this->edad);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->curso->Exportable) $Doc->ExportCaption($this->curso);
					if ($this->discapacidad->Exportable) $Doc->ExportCaption($this->discapacidad);
					if ($this->tipodiscapcidad->Exportable) $Doc->ExportCaption($this->tipodiscapcidad);
					if ($this->nombreinstitucion->Exportable) $Doc->ExportCaption($this->nombreinstitucion);
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
						if ($this->departamento->Exportable) $Doc->ExportField($this->departamento);
						if ($this->codigorude->Exportable) $Doc->ExportField($this->codigorude);
						if ($this->codigorude_es->Exportable) $Doc->ExportField($this->codigorude_es);
						if ($this->municipio->Exportable) $Doc->ExportField($this->municipio);
						if ($this->provincia->Exportable) $Doc->ExportField($this->provincia);
						if ($this->unidadeducativa->Exportable) $Doc->ExportField($this->unidadeducativa);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->materno->Exportable) $Doc->ExportField($this->materno);
						if ($this->paterno->Exportable) $Doc->ExportField($this->paterno);
						if ($this->nrodiscapacidad->Exportable) $Doc->ExportField($this->nrodiscapacidad);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fechanacimiento->Exportable) $Doc->ExportField($this->fechanacimiento);
						if ($this->edad->Exportable) $Doc->ExportField($this->edad);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->curso->Exportable) $Doc->ExportField($this->curso);
						if ($this->discapacidad->Exportable) $Doc->ExportField($this->discapacidad);
						if ($this->tipodiscapcidad->Exportable) $Doc->ExportField($this->tipodiscapcidad);
						if ($this->nombreinstitucion->Exportable) $Doc->ExportField($this->nombreinstitucion);
					} else {
						if ($this->departamento->Exportable) $Doc->ExportField($this->departamento);
						if ($this->codigorude->Exportable) $Doc->ExportField($this->codigorude);
						if ($this->codigorude_es->Exportable) $Doc->ExportField($this->codigorude_es);
						if ($this->municipio->Exportable) $Doc->ExportField($this->municipio);
						if ($this->provincia->Exportable) $Doc->ExportField($this->provincia);
						if ($this->unidadeducativa->Exportable) $Doc->ExportField($this->unidadeducativa);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->materno->Exportable) $Doc->ExportField($this->materno);
						if ($this->paterno->Exportable) $Doc->ExportField($this->paterno);
						if ($this->nrodiscapacidad->Exportable) $Doc->ExportField($this->nrodiscapacidad);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fechanacimiento->Exportable) $Doc->ExportField($this->fechanacimiento);
						if ($this->edad->Exportable) $Doc->ExportField($this->edad);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->curso->Exportable) $Doc->ExportField($this->curso);
						if ($this->discapacidad->Exportable) $Doc->ExportField($this->discapacidad);
						if ($this->tipodiscapcidad->Exportable) $Doc->ExportField($this->tipodiscapcidad);
						if ($this->nombreinstitucion->Exportable) $Doc->ExportField($this->nombreinstitucion);
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
