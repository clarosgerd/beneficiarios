<?php

// Global variable for table object
$viewsaludneonatal = NULL;

//
// Table class for viewsaludneonatal
//
class crviewsaludneonatal extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $fecha_tamizaje;
	var $ci;
	var $fecha_nacimiento;
	var $dias;
	var $semanas;
	var $meses;
	var $sexo;
	var $resultado;
	var $resultadotamizaje;
	var $tapon;
	var $repetirprueba;
	var $observaciones;
	var $parentesco;
	var $nombrescompleto;
	var $nombreinstitucion;
	var $nombreneonatal;
	var $discapacidad;
	var $tipodiscapacidad;
	var $tipotapo;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'viewsaludneonatal';
		$this->TableName = 'viewsaludneonatal';
		$this->TableType = 'VIEW';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// fecha_tamizaje
		$this->fecha_tamizaje = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_fecha_tamizaje', 'fecha_tamizaje', '`fecha_tamizaje`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_tamizaje->Sortable = TRUE; // Allow sort
		$this->fecha_tamizaje->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_tamizaje->DateFilter = "";
		$this->fecha_tamizaje->SqlSelect = "SELECT DISTINCT `fecha_tamizaje`, `fecha_tamizaje` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fecha_tamizaje->SqlOrderBy = "`fecha_tamizaje`";
		$this->fields['fecha_tamizaje'] = &$this->fecha_tamizaje;

		// ci
		$this->ci = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_ci', 'ci', '`ci`', 200, EWR_DATATYPE_STRING, -1);
		$this->ci->Sortable = TRUE; // Allow sort
		$this->ci->DateFilter = "";
		$this->ci->SqlSelect = "SELECT DISTINCT `ci`, `ci` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->ci->SqlOrderBy = "`ci`";
		$this->fields['ci'] = &$this->ci;

		// fecha_nacimiento
		$this->fecha_nacimiento = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_fecha_nacimiento', 'fecha_nacimiento', '`fecha_nacimiento`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_nacimiento->Sortable = TRUE; // Allow sort
		$this->fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_nacimiento->DateFilter = "";
		$this->fecha_nacimiento->SqlSelect = "SELECT DISTINCT `fecha_nacimiento`, `fecha_nacimiento` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fecha_nacimiento->SqlOrderBy = "`fecha_nacimiento`";
		$this->fields['fecha_nacimiento'] = &$this->fecha_nacimiento;

		// dias
		$this->dias = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_dias', 'dias', '`dias`', 200, EWR_DATATYPE_STRING, -1);
		$this->dias->Sortable = TRUE; // Allow sort
		$this->dias->DateFilter = "";
		$this->dias->SqlSelect = "SELECT DISTINCT `dias`, `dias` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->dias->SqlOrderBy = "`dias`";
		$this->fields['dias'] = &$this->dias;

		// semanas
		$this->semanas = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_semanas', 'semanas', '`semanas`', 200, EWR_DATATYPE_STRING, -1);
		$this->semanas->Sortable = TRUE; // Allow sort
		$this->semanas->DateFilter = "";
		$this->semanas->SqlSelect = "SELECT DISTINCT `semanas`, `semanas` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->semanas->SqlOrderBy = "`semanas`";
		$this->fields['semanas'] = &$this->semanas;

		// meses
		$this->meses = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_meses', 'meses', '`meses`', 200, EWR_DATATYPE_STRING, -1);
		$this->meses->Sortable = TRUE; // Allow sort
		$this->meses->DateFilter = "";
		$this->meses->SqlSelect = "SELECT DISTINCT `meses`, `meses` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->meses->SqlOrderBy = "`meses`";
		$this->fields['meses'] = &$this->meses;

		// sexo
		$this->sexo = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_sexo', 'sexo', '`sexo`', 200, EWR_DATATYPE_STRING, -1);
		$this->sexo->Sortable = TRUE; // Allow sort
		$this->sexo->DateFilter = "";
		$this->sexo->SqlSelect = "SELECT DISTINCT `sexo`, `sexo` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->sexo->SqlOrderBy = "`sexo`";
		$this->fields['sexo'] = &$this->sexo;

		// resultado
		$this->resultado = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_resultado', 'resultado', '`resultado`', 200, EWR_DATATYPE_STRING, -1);
		$this->resultado->Sortable = TRUE; // Allow sort
		$this->resultado->DateFilter = "";
		$this->resultado->SqlSelect = "SELECT DISTINCT `resultado`, `resultado` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->resultado->SqlOrderBy = "`resultado`";
		$this->fields['resultado'] = &$this->resultado;

		// resultadotamizaje
		$this->resultadotamizaje = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_resultadotamizaje', 'resultadotamizaje', '`resultadotamizaje`', 200, EWR_DATATYPE_STRING, -1);
		$this->resultadotamizaje->Sortable = TRUE; // Allow sort
		$this->resultadotamizaje->DateFilter = "";
		$this->resultadotamizaje->SqlSelect = "SELECT DISTINCT `resultadotamizaje`, `resultadotamizaje` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->resultadotamizaje->SqlOrderBy = "`resultadotamizaje`";
		$this->fields['resultadotamizaje'] = &$this->resultadotamizaje;

		// tapon
		$this->tapon = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_tapon', 'tapon', '`tapon`', 200, EWR_DATATYPE_STRING, -1);
		$this->tapon->Sortable = TRUE; // Allow sort
		$this->tapon->DateFilter = "";
		$this->tapon->SqlSelect = "SELECT DISTINCT `tapon`, `tapon` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->tapon->SqlOrderBy = "`tapon`";
		$this->fields['tapon'] = &$this->tapon;

		// repetirprueba
		$this->repetirprueba = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_repetirprueba', 'repetirprueba', '`repetirprueba`', 200, EWR_DATATYPE_STRING, -1);
		$this->repetirprueba->Sortable = TRUE; // Allow sort
		$this->repetirprueba->DateFilter = "";
		$this->repetirprueba->SqlSelect = "SELECT DISTINCT `repetirprueba`, `repetirprueba` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->repetirprueba->SqlOrderBy = "`repetirprueba`";
		$this->fields['repetirprueba'] = &$this->repetirprueba;

		// observaciones
		$this->observaciones = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_observaciones', 'observaciones', '`observaciones`', 200, EWR_DATATYPE_STRING, -1);
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->observaciones->DateFilter = "";
		$this->observaciones->SqlSelect = "SELECT DISTINCT `observaciones`, `observaciones` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->observaciones->SqlOrderBy = "`observaciones`";
		$this->fields['observaciones'] = &$this->observaciones;

		// parentesco
		$this->parentesco = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_parentesco', 'parentesco', '`parentesco`', 200, EWR_DATATYPE_STRING, -1);
		$this->parentesco->Sortable = TRUE; // Allow sort
		$this->parentesco->DateFilter = "";
		$this->parentesco->SqlSelect = "SELECT DISTINCT `parentesco`, `parentesco` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->parentesco->SqlOrderBy = "`parentesco`";
		$this->fields['parentesco'] = &$this->parentesco;

		// nombrescompleto
		$this->nombrescompleto = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_nombrescompleto', 'nombrescompleto', '`nombrescompleto`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombrescompleto->Sortable = TRUE; // Allow sort
		$this->nombrescompleto->DateFilter = "";
		$this->nombrescompleto->SqlSelect = "SELECT DISTINCT `nombrescompleto`, `nombrescompleto` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->nombrescompleto->SqlOrderBy = "`nombrescompleto`";
		$this->fields['nombrescompleto'] = &$this->nombrescompleto;

		// nombreinstitucion
		$this->nombreinstitucion = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_nombreinstitucion', 'nombreinstitucion', '`nombreinstitucion`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombreinstitucion->Sortable = TRUE; // Allow sort
		$this->nombreinstitucion->DateFilter = "";
		$this->nombreinstitucion->SqlSelect = "SELECT DISTINCT `nombreinstitucion`, `nombreinstitucion` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->nombreinstitucion->SqlOrderBy = "`nombreinstitucion`";
		$this->fields['nombreinstitucion'] = &$this->nombreinstitucion;

		// nombreneonatal
		$this->nombreneonatal = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_nombreneonatal', 'nombreneonatal', '`nombreneonatal`', 201, EWR_DATATYPE_MEMO, -1);
		$this->nombreneonatal->Sortable = TRUE; // Allow sort
		$this->nombreneonatal->DateFilter = "";
		$this->nombreneonatal->SqlSelect = "";
		$this->nombreneonatal->SqlOrderBy = "";
		$this->fields['nombreneonatal'] = &$this->nombreneonatal;

		// discapacidad
		$this->discapacidad = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_discapacidad', 'discapacidad', '`discapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->discapacidad->DateFilter = "";
		$this->discapacidad->SqlSelect = "SELECT DISTINCT `discapacidad`, `discapacidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->discapacidad->SqlOrderBy = "`discapacidad`";
		$this->fields['discapacidad'] = &$this->discapacidad;

		// tipodiscapacidad
		$this->tipodiscapacidad = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_tipodiscapacidad', 'tipodiscapacidad', '`tipodiscapacidad`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipodiscapacidad->Sortable = TRUE; // Allow sort
		$this->tipodiscapacidad->DateFilter = "";
		$this->tipodiscapacidad->SqlSelect = "SELECT DISTINCT `tipodiscapacidad`, `tipodiscapacidad` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->tipodiscapacidad->SqlOrderBy = "`tipodiscapacidad`";
		$this->fields['tipodiscapacidad'] = &$this->tipodiscapacidad;

		// tipotapo
		$this->tipotapo = new crField('viewsaludneonatal', 'viewsaludneonatal', 'x_tipotapo', 'tipotapo', '`tipotapo`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipotapo->Sortable = TRUE; // Allow sort
		$this->tipotapo->DateFilter = "";
		$this->tipotapo->SqlSelect = "SELECT DISTINCT `tipotapo`, `tipotapo` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->tipotapo->SqlOrderBy = "`tipotapo`";
		$this->fields['tipotapo'] = &$this->tipotapo;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`viewsaludneonatal`";
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
