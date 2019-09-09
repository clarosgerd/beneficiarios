<?php

// Global variable for table object
$apoderado = NULL;

//
// Table class for apoderado
//
class capoderado extends cTable {
	var $id;
	var $parentesco;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nombres;
	var $ci;
	var $fechanacimiento;
	var $sexo;
	var $direcciF3n;
	var $celular;
	var $ocupaciF3n;
	var $observaciones;
	var $id_centro;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'apoderado';
		$this->TableName = 'apoderado';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`apoderado`";
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
		$this->id = new cField('apoderado', 'apoderado', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// parentesco
		$this->parentesco = new cField('apoderado', 'apoderado', 'x_parentesco', 'parentesco', '`parentesco`', '`parentesco`', 200, -1, FALSE, '`parentesco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->parentesco->Sortable = TRUE; // Allow sort
		$this->fields['parentesco'] = &$this->parentesco;

		// apellidopaterno
		$this->apellidopaterno = new cField('apoderado', 'apoderado', 'x_apellidopaterno', 'apellidopaterno', '`apellidopaterno`', '`apellidopaterno`', 200, -1, FALSE, '`apellidopaterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new cField('apoderado', 'apoderado', 'x_apellidomaterno', 'apellidomaterno', '`apellidomaterno`', '`apellidomaterno`', 200, -1, FALSE, '`apellidomaterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nombres
		$this->nombres = new cField('apoderado', 'apoderado', 'x_nombres', 'nombres', '`nombres`', '`nombres`', 200, -1, FALSE, '`nombres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombres->Sortable = TRUE; // Allow sort
		$this->fields['nombres'] = &$this->nombres;

		// ci
		$this->ci = new cField('apoderado', 'apoderado', 'x_ci', 'ci', '`ci`', '`ci`', 200, -1, FALSE, '`ci`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ci->Sortable = TRUE; // Allow sort
		$this->fields['ci'] = &$this->ci;

		// fechanacimiento
		$this->fechanacimiento = new cField('apoderado', 'apoderado', 'x_fechanacimiento', 'fechanacimiento', '`fechanacimiento`', ew_CastDateFieldForLike('`fechanacimiento`', 0, "DB"), 133, 0, FALSE, '`fechanacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechanacimiento->Sortable = TRUE; // Allow sort
		$this->fechanacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fechanacimiento'] = &$this->fechanacimiento;

		// sexo
		$this->sexo = new cField('apoderado', 'apoderado', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 3, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sexo->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sexo->OptionCount = 2;
		$this->sexo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sexo'] = &$this->sexo;

		// dirección
		$this->direcciF3n = new cField('apoderado', 'apoderado', 'x_direcciF3n', 'dirección', '`dirección`', '`dirección`', 200, -1, FALSE, '`dirección`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direcciF3n->Sortable = TRUE; // Allow sort
		$this->direcciF3n->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dirección'] = &$this->direcciF3n;

		// celular
		$this->celular = new cField('apoderado', 'apoderado', 'x_celular', 'celular', '`celular`', '`celular`', 3, -1, FALSE, '`celular`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->celular->Sortable = TRUE; // Allow sort
		$this->celular->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['celular'] = &$this->celular;

		// ocupación
		$this->ocupaciF3n = new cField('apoderado', 'apoderado', 'x_ocupaciF3n', 'ocupación', '`ocupación`', '`ocupación`', 200, -1, FALSE, '`ocupación`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ocupaciF3n->Sortable = TRUE; // Allow sort
		$this->fields['ocupación'] = &$this->ocupaciF3n;

		// observaciones
		$this->observaciones = new cField('apoderado', 'apoderado', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 200, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// id_centro
		$this->id_centro = new cField('apoderado', 'apoderado', 'x_id_centro', 'id_centro', '`id_centro`', '`id_centro`', 3, -1, FALSE, '`id_centro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`apoderado`";
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
			return "apoderadolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "apoderadoview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "apoderadoedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "apoderadoadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "apoderadolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("apoderadoview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("apoderadoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "apoderadoadd.php?" . $this->UrlParm($parm);
		else
			$url = "apoderadoadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("apoderadoedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("apoderadoadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("apoderadodelete.php", $this->UrlParm());
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
		$this->parentesco->setDbValue($rs->fields('parentesco'));
		$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
		$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
		$this->nombres->setDbValue($rs->fields('nombres'));
		$this->ci->setDbValue($rs->fields('ci'));
		$this->fechanacimiento->setDbValue($rs->fields('fechanacimiento'));
		$this->sexo->setDbValue($rs->fields('sexo'));
		$this->direcciF3n->setDbValue($rs->fields('dirección'));
		$this->celular->setDbValue($rs->fields('celular'));
		$this->ocupaciF3n->setDbValue($rs->fields('ocupación'));
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
		// parentesco
		// apellidopaterno
		// apellidomaterno
		// nombres
		// ci
		// fechanacimiento
		// sexo
		// dirección
		// celular
		// ocupación
		// observaciones
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// parentesco
		$this->parentesco->ViewValue = $this->parentesco->CurrentValue;
		$this->parentesco->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombres
		$this->nombres->ViewValue = $this->nombres->CurrentValue;
		$this->nombres->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fechanacimiento
		$this->fechanacimiento->ViewValue = $this->fechanacimiento->CurrentValue;
		$this->fechanacimiento->ViewValue = ew_FormatDateTime($this->fechanacimiento->ViewValue, 0);
		$this->fechanacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// dirección
		$this->direcciF3n->ViewValue = $this->direcciF3n->CurrentValue;
		$this->direcciF3n->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// ocupación
		$this->ocupaciF3n->ViewValue = $this->ocupaciF3n->CurrentValue;
		$this->ocupaciF3n->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_centro
		$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
		$this->id_centro->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// parentesco
		$this->parentesco->LinkCustomAttributes = "";
		$this->parentesco->HrefValue = "";
		$this->parentesco->TooltipValue = "";

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

		// dirección
		$this->direcciF3n->LinkCustomAttributes = "";
		$this->direcciF3n->HrefValue = "";
		$this->direcciF3n->TooltipValue = "";

		// celular
		$this->celular->LinkCustomAttributes = "";
		$this->celular->HrefValue = "";
		$this->celular->TooltipValue = "";

		// ocupación
		$this->ocupaciF3n->LinkCustomAttributes = "";
		$this->ocupaciF3n->HrefValue = "";
		$this->ocupaciF3n->TooltipValue = "";

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

		// parentesco
		$this->parentesco->EditAttrs["class"] = "form-control";
		$this->parentesco->EditCustomAttributes = "";
		$this->parentesco->EditValue = $this->parentesco->CurrentValue;
		$this->parentesco->PlaceHolder = ew_RemoveHtml($this->parentesco->FldCaption());

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

		// ci
		$this->ci->EditAttrs["class"] = "form-control";
		$this->ci->EditCustomAttributes = "";
		$this->ci->EditValue = $this->ci->CurrentValue;
		$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

		// fechanacimiento
		$this->fechanacimiento->EditAttrs["class"] = "form-control";
		$this->fechanacimiento->EditCustomAttributes = "";
		$this->fechanacimiento->EditValue = ew_FormatDateTime($this->fechanacimiento->CurrentValue, 8);
		$this->fechanacimiento->PlaceHolder = ew_RemoveHtml($this->fechanacimiento->FldCaption());

		// sexo
		$this->sexo->EditAttrs["class"] = "form-control";
		$this->sexo->EditCustomAttributes = "";
		$this->sexo->EditValue = $this->sexo->Options(TRUE);

		// dirección
		$this->direcciF3n->EditAttrs["class"] = "form-control";
		$this->direcciF3n->EditCustomAttributes = "";
		$this->direcciF3n->EditValue = $this->direcciF3n->CurrentValue;
		$this->direcciF3n->PlaceHolder = ew_RemoveHtml($this->direcciF3n->FldCaption());

		// celular
		$this->celular->EditAttrs["class"] = "form-control";
		$this->celular->EditCustomAttributes = "";
		$this->celular->EditValue = $this->celular->CurrentValue;
		$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

		// ocupación
		$this->ocupaciF3n->EditAttrs["class"] = "form-control";
		$this->ocupaciF3n->EditCustomAttributes = "";
		$this->ocupaciF3n->EditValue = $this->ocupaciF3n->CurrentValue;
		$this->ocupaciF3n->PlaceHolder = ew_RemoveHtml($this->ocupaciF3n->FldCaption());

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

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
					if ($this->parentesco->Exportable) $Doc->ExportCaption($this->parentesco);
					if ($this->apellidopaterno->Exportable) $Doc->ExportCaption($this->apellidopaterno);
					if ($this->apellidomaterno->Exportable) $Doc->ExportCaption($this->apellidomaterno);
					if ($this->nombres->Exportable) $Doc->ExportCaption($this->nombres);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fechanacimiento->Exportable) $Doc->ExportCaption($this->fechanacimiento);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->direcciF3n->Exportable) $Doc->ExportCaption($this->direcciF3n);
					if ($this->celular->Exportable) $Doc->ExportCaption($this->celular);
					if ($this->ocupaciF3n->Exportable) $Doc->ExportCaption($this->ocupaciF3n);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->parentesco->Exportable) $Doc->ExportCaption($this->parentesco);
					if ($this->apellidopaterno->Exportable) $Doc->ExportCaption($this->apellidopaterno);
					if ($this->apellidomaterno->Exportable) $Doc->ExportCaption($this->apellidomaterno);
					if ($this->nombres->Exportable) $Doc->ExportCaption($this->nombres);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fechanacimiento->Exportable) $Doc->ExportCaption($this->fechanacimiento);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->direcciF3n->Exportable) $Doc->ExportCaption($this->direcciF3n);
					if ($this->celular->Exportable) $Doc->ExportCaption($this->celular);
					if ($this->ocupaciF3n->Exportable) $Doc->ExportCaption($this->ocupaciF3n);
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
						if ($this->parentesco->Exportable) $Doc->ExportField($this->parentesco);
						if ($this->apellidopaterno->Exportable) $Doc->ExportField($this->apellidopaterno);
						if ($this->apellidomaterno->Exportable) $Doc->ExportField($this->apellidomaterno);
						if ($this->nombres->Exportable) $Doc->ExportField($this->nombres);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fechanacimiento->Exportable) $Doc->ExportField($this->fechanacimiento);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->direcciF3n->Exportable) $Doc->ExportField($this->direcciF3n);
						if ($this->celular->Exportable) $Doc->ExportField($this->celular);
						if ($this->ocupaciF3n->Exportable) $Doc->ExportField($this->ocupaciF3n);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->parentesco->Exportable) $Doc->ExportField($this->parentesco);
						if ($this->apellidopaterno->Exportable) $Doc->ExportField($this->apellidopaterno);
						if ($this->apellidomaterno->Exportable) $Doc->ExportField($this->apellidomaterno);
						if ($this->nombres->Exportable) $Doc->ExportField($this->nombres);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fechanacimiento->Exportable) $Doc->ExportField($this->fechanacimiento);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->direcciF3n->Exportable) $Doc->ExportField($this->direcciF3n);
						if ($this->celular->Exportable) $Doc->ExportField($this->celular);
						if ($this->ocupaciF3n->Exportable) $Doc->ExportField($this->ocupaciF3n);
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
