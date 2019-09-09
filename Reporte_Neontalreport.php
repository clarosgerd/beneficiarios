<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php

// Global variable for table object
$Reporte_Neontal = NULL;

//
// Table class for Reporte Neontal
//
class cReporte_Neontal extends cTableBase {
	var $id_neonato;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nombre;
	var $ci;
	var $fecha_nacimiento;
	var $dias;
	var $semanas;
	var $meses;
	var $discapacidad;
	var $resultado;
	var $observaciones;
	var $tipoprueba;
	var $resultadprueba;
	var $recomendacion;
	var $id_tipodiagnosticoaudiologia;
	var $nombrediagnotico;
	var $resultadodiagnostico;
	var $tipotratamiento;
	var $tipoderivacion;
	var $nombreespcialidad;
	var $observaciones1;
	var $fecha;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Reporte_Neontal';
		$this->TableName = 'Reporte Neontal';
		$this->TableType = 'REPORT';

		// Update Table
		$this->UpdateTable = "audiologia INNER JOIN pruebasaudiologia ON audiologia.id = pruebasaudiologia.id_audiologia INNER JOIN diagnosticoaudiologia ON audiologia.id = diagnosticoaudiologia.id_audiologia INNER JOIN neonatal ON audiologia.id_neonato = neonatal.id INNER JOIN tratamiento ON audiologia.id = tratamiento.id_audiologia INNER JOIN derivacion ON audiologia.id = derivacion.id_audiologia INNER JOIN tipopruebasaudiologia ON pruebasaudiologia.id_tipopruebasaudiologia = tipopruebasaudiologia.id INNER JOIN tipodiagnosticoaudiologia ON diagnosticoaudiologia.id_tipodiagnosticoaudiologia = tipodiagnosticoaudiologia.id INNER JOIN tipotratamientoaudiologia ON tratamiento.id_tipotratamientoaudiologia = tipotratamientoaudiologia.id INNER JOIN tipoespecialidad ON derivacion.id_tipoespecialidad = tipoespecialidad.id";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// id_neonato
		$this->id_neonato = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_id_neonato', 'id_neonato', 'audiologia.id_neonato', 'audiologia.id_neonato', 3, -1, FALSE, 'audiologia.id_neonato', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_neonato->Sortable = TRUE; // Allow sort
		$this->id_neonato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_neonato'] = &$this->id_neonato;

		// apellidopaterno
		$this->apellidopaterno = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_apellidopaterno', 'apellidopaterno', 'neonatal.apellidopaterno', 'neonatal.apellidopaterno', 200, -1, FALSE, 'neonatal.apellidopaterno', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_apellidomaterno', 'apellidomaterno', 'neonatal.apellidomaterno', 'neonatal.apellidomaterno', 200, -1, FALSE, 'neonatal.apellidomaterno', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nombre
		$this->nombre = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_nombre', 'nombre', 'neonatal.nombre', 'neonatal.nombre', 200, -1, FALSE, 'neonatal.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->fields['nombre'] = &$this->nombre;

		// ci
		$this->ci = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_ci', 'ci', 'neonatal.ci', 'neonatal.ci', 200, -1, FALSE, 'neonatal.ci', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ci->Sortable = TRUE; // Allow sort
		$this->fields['ci'] = &$this->ci;

		// fecha_nacimiento
		$this->fecha_nacimiento = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_fecha_nacimiento', 'fecha_nacimiento', 'neonatal.fecha_nacimiento', ew_CastDateFieldForLike('neonatal.fecha_nacimiento', 0, "DB"), 133, 0, FALSE, 'neonatal.fecha_nacimiento', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_nacimiento->Sortable = TRUE; // Allow sort
		$this->fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha_nacimiento'] = &$this->fecha_nacimiento;

		// dias
		$this->dias = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_dias', 'dias', 'neonatal.dias', 'neonatal.dias', 200, -1, FALSE, 'neonatal.dias', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dias->Sortable = TRUE; // Allow sort
		$this->fields['dias'] = &$this->dias;

		// semanas
		$this->semanas = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_semanas', 'semanas', 'neonatal.semanas', 'neonatal.semanas', 200, -1, FALSE, 'neonatal.semanas', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->semanas->Sortable = TRUE; // Allow sort
		$this->fields['semanas'] = &$this->semanas;

		// meses
		$this->meses = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_meses', 'meses', 'neonatal.meses', 'neonatal.meses', 200, -1, FALSE, 'neonatal.meses', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->meses->Sortable = TRUE; // Allow sort
		$this->fields['meses'] = &$this->meses;

		// discapacidad
		$this->discapacidad = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_discapacidad', 'discapacidad', 'neonatal.discapacidad', 'neonatal.discapacidad', 3, -1, FALSE, 'neonatal.discapacidad', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->fields['discapacidad'] = &$this->discapacidad;

		// resultado
		$this->resultado = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_resultado', 'resultado', 'neonatal.resultado', 'neonatal.resultado', 200, -1, FALSE, 'neonatal.resultado', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resultado->Sortable = TRUE; // Allow sort
		$this->fields['resultado'] = &$this->resultado;

		// observaciones
		$this->observaciones = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_observaciones', 'observaciones', 'neonatal.observaciones', 'neonatal.observaciones', 200, -1, FALSE, 'neonatal.observaciones', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// tipoprueba
		$this->tipoprueba = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_tipoprueba', 'tipoprueba', 'tipopruebasaudiologia.nombre', 'tipopruebasaudiologia.nombre', 200, -1, FALSE, 'tipopruebasaudiologia.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipoprueba->Sortable = TRUE; // Allow sort
		$this->fields['tipoprueba'] = &$this->tipoprueba;

		// resultadprueba
		$this->resultadprueba = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_resultadprueba', 'resultadprueba', 'pruebasaudiologia.resultado', 'pruebasaudiologia.resultado', 200, -1, FALSE, 'pruebasaudiologia.resultado', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resultadprueba->Sortable = TRUE; // Allow sort
		$this->fields['resultadprueba'] = &$this->resultadprueba;

		// recomendacion
		$this->recomendacion = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_recomendacion', 'recomendacion', 'pruebasaudiologia.recomendacion', 'pruebasaudiologia.recomendacion', 200, -1, FALSE, 'pruebasaudiologia.recomendacion', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->recomendacion->Sortable = TRUE; // Allow sort
		$this->fields['recomendacion'] = &$this->recomendacion;

		// id_tipodiagnosticoaudiologia
		$this->id_tipodiagnosticoaudiologia = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_id_tipodiagnosticoaudiologia', 'id_tipodiagnosticoaudiologia', 'diagnosticoaudiologia.id_tipodiagnosticoaudiologia', 'diagnosticoaudiologia.id_tipodiagnosticoaudiologia', 3, -1, FALSE, 'diagnosticoaudiologia.id_tipodiagnosticoaudiologia', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_tipodiagnosticoaudiologia->Sortable = TRUE; // Allow sort
		$this->id_tipodiagnosticoaudiologia->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_tipodiagnosticoaudiologia->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_tipodiagnosticoaudiologia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tipodiagnosticoaudiologia'] = &$this->id_tipodiagnosticoaudiologia;

		// nombrediagnotico
		$this->nombrediagnotico = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_nombrediagnotico', 'nombrediagnotico', 'tipodiagnosticoaudiologia.nombre', 'tipodiagnosticoaudiologia.nombre', 200, -1, FALSE, 'tipodiagnosticoaudiologia.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombrediagnotico->Sortable = TRUE; // Allow sort
		$this->fields['nombrediagnotico'] = &$this->nombrediagnotico;

		// resultadodiagnostico
		$this->resultadodiagnostico = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_resultadodiagnostico', 'resultadodiagnostico', 'diagnosticoaudiologia.resultado', 'diagnosticoaudiologia.resultado', 200, -1, FALSE, 'diagnosticoaudiologia.resultado', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resultadodiagnostico->Sortable = TRUE; // Allow sort
		$this->fields['resultadodiagnostico'] = &$this->resultadodiagnostico;

		// tipotratamiento
		$this->tipotratamiento = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_tipotratamiento', 'tipotratamiento', 'tipotratamientoaudiologia.nombre', 'tipotratamientoaudiologia.nombre', 200, -1, FALSE, 'tipotratamientoaudiologia.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipotratamiento->Sortable = TRUE; // Allow sort
		$this->fields['tipotratamiento'] = &$this->tipotratamiento;

		// tipoderivacion
		$this->tipoderivacion = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_tipoderivacion', 'tipoderivacion', 'derivacion.tipoderivacion', 'derivacion.tipoderivacion', 200, -1, FALSE, 'derivacion.tipoderivacion', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipoderivacion->Sortable = TRUE; // Allow sort
		$this->tipoderivacion->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->tipoderivacion->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->tipoderivacion->OptionCount = 2;
		$this->fields['tipoderivacion'] = &$this->tipoderivacion;

		// nombreespcialidad
		$this->nombreespcialidad = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_nombreespcialidad', 'nombreespcialidad', 'tipoespecialidad.nombre', 'tipoespecialidad.nombre', 200, -1, FALSE, 'tipoespecialidad.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombreespcialidad->Sortable = TRUE; // Allow sort
		$this->fields['nombreespcialidad'] = &$this->nombreespcialidad;

		// observaciones1
		$this->observaciones1 = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_observaciones1', 'observaciones1', 'audiologia.observaciones', 'audiologia.observaciones', 3, -1, FALSE, 'audiologia.observaciones', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->observaciones1->Sortable = TRUE; // Allow sort
		$this->observaciones1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['observaciones1'] = &$this->observaciones1;

		// fecha
		$this->fecha = new cField('Reporte_Neontal', 'Reporte Neontal', 'x_fecha', 'fecha', 'audiologia.fecha', ew_CastDateFieldForLike('audiologia.fecha', 0, "DB"), 133, 0, FALSE, 'audiologia.fecha', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha'] = &$this->fecha;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Report detail level SQL
	var $_SqlDetailSelect = "";

	function getSqlDetailSelect() { // Select
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT audiologia.id_neonato, neonatal.apellidopaterno, neonatal.apellidomaterno, neonatal.nombre, neonatal.ci, neonatal.fecha_nacimiento, neonatal.dias, neonatal.semanas, neonatal.meses, neonatal.discapacidad, neonatal.resultado, neonatal.observaciones, tipopruebasaudiologia.nombre AS tipoprueba, pruebasaudiologia.resultado AS resultadprueba, pruebasaudiologia.recomendacion, diagnosticoaudiologia.id_tipodiagnosticoaudiologia, tipodiagnosticoaudiologia.nombre AS nombrediagnotico, diagnosticoaudiologia.resultado AS resultadodiagnostico, tipotratamientoaudiologia.nombre AS tipotratamiento, derivacion.tipoderivacion, tipoespecialidad.nombre AS nombreespcialidad, audiologia.observaciones AS observaciones1, audiologia.fecha FROM audiologia INNER JOIN pruebasaudiologia ON audiologia.id = pruebasaudiologia.id_audiologia INNER JOIN diagnosticoaudiologia ON audiologia.id = diagnosticoaudiologia.id_audiologia INNER JOIN neonatal ON audiologia.id_neonato = neonatal.id INNER JOIN tratamiento ON audiologia.id = tratamiento.id_audiologia INNER JOIN derivacion ON audiologia.id = derivacion.id_audiologia INNER JOIN tipopruebasaudiologia ON pruebasaudiologia.id_tipopruebasaudiologia = tipopruebasaudiologia.id INNER JOIN tipodiagnosticoaudiologia ON diagnosticoaudiologia.id_tipodiagnosticoaudiologia = tipodiagnosticoaudiologia.id INNER JOIN tipotratamientoaudiologia ON tratamiento.id_tipotratamientoaudiologia = tipotratamientoaudiologia.id INNER JOIN tipoespecialidad ON derivacion.id_tipoespecialidad = tipoespecialidad.id";
	}

	function SqlDetailSelect() { // For backward compatibility
		return $this->getSqlDetailSelect();
	}

	function setSqlDetailSelect($v) {
		$this->_SqlDetailSelect = $v;
	}
	var $_SqlDetailWhere = "";

	function getSqlDetailWhere() { // Where
		return ($this->_SqlDetailWhere <> "") ? $this->_SqlDetailWhere : "";
	}

	function SqlDetailWhere() { // For backward compatibility
		return $this->getSqlDetailWhere();
	}

	function setSqlDetailWhere($v) {
		$this->_SqlDetailWhere = $v;
	}
	var $_SqlDetailGroupBy = "";

	function getSqlDetailGroupBy() { // Group By
		return ($this->_SqlDetailGroupBy <> "") ? $this->_SqlDetailGroupBy : "audiologia.id_neonato, neonatal.apellidopaterno, neonatal.apellidomaterno, neonatal.nombre, neonatal.ci, neonatal.fecha_nacimiento, neonatal.dias, neonatal.semanas, neonatal.meses, neonatal.discapacidad, neonatal.resultado, neonatal.observaciones, audiologia.observaciones, audiologia.fecha";
	}

	function SqlDetailGroupBy() { // For backward compatibility
		return $this->getSqlDetailGroupBy();
	}

	function setSqlDetailGroupBy($v) {
		$this->_SqlDetailGroupBy = $v;
	}
	var $_SqlDetailHaving = "";

	function getSqlDetailHaving() { // Having
		return ($this->_SqlDetailHaving <> "") ? $this->_SqlDetailHaving : "";
	}

	function SqlDetailHaving() { // For backward compatibility
		return $this->getSqlDetailHaving();
	}

	function setSqlDetailHaving($v) {
		$this->_SqlDetailHaving = $v;
	}
	var $_SqlDetailOrderBy = "";

	function getSqlDetailOrderBy() { // Order By
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "";
	}

	function SqlDetailOrderBy() { // For backward compatibility
		return $this->getSqlDetailOrderBy();
	}

	function setSqlDetailOrderBy($v) {
		$this->_SqlDetailOrderBy = $v;
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

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlDetailSelect(), $this->getSqlDetailWhere(),
			$this->getSqlDetailGroupBy(), $this->getSqlDetailHaving(),
			$this->getSqlDetailOrderBy(), $sFilter, $sSort);
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
			return "Reporte_Neontalreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "")
			return $Language->Phrase("View");
		elseif ($pageName == "")
			return $Language->Phrase("Edit");
		elseif ($pageName == "")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "Reporte_Neontalreport.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "?" . $this->UrlParm($parm);
		else
			$url = "";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
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
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$Reporte_Neontal_report = NULL; // Initialize page object first

class cReporte_Neontal_report extends cReporte_Neontal {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'Reporte Neontal';

	// Page object name
	var $PageObjName = 'Reporte_Neontal_report';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Reporte_Neontal)
		if (!isset($GLOBALS["Reporte_Neontal"]) || get_class($GLOBALS["Reporte_Neontal"]) == "cReporte_Neontal") {
			$GLOBALS["Reporte_Neontal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Reporte_Neontal"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Reporte Neontal', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new cusuario();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT_REPORT;
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_clean(); // ob_get_contents() and ob_end_clean()
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $RecCnt = 0;
	var $RowCnt = 0; // For custom view tag
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(1, NULL);
		$this->ReportCounts = &ew_InitArray(1, 0);
		$this->LevelBreak = &ew_InitArray(1, FALSE);
		$this->ReportTotals = &ew_Init2DArray(1, 24, 0);
		$this->ReportMaxs = &ew_Init2DArray(1, 24, 0);
		$this->ReportMins = &ew_Init2DArray(1, 24, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_neonato
		// apellidopaterno
		// apellidomaterno
		// nombre
		// ci
		// fecha_nacimiento
		// dias
		// semanas
		// meses
		// discapacidad
		// resultado
		// observaciones
		// tipoprueba
		// resultadprueba
		// recomendacion
		// id_tipodiagnosticoaudiologia
		// nombrediagnotico
		// resultadodiagnostico
		// tipotratamiento
		// tipoderivacion
		// nombreespcialidad
		// observaciones1
		// fecha

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_neonato
		$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
		if (strval($this->id_neonato->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_neonato->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
		$sWhereWrk = "";
		$this->id_neonato->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_neonato->ViewValue = $this->id_neonato->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
			}
		} else {
			$this->id_neonato->ViewValue = NULL;
		}
		$this->id_neonato->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// dias
		$this->dias->ViewValue = $this->dias->CurrentValue;
		$this->dias->ViewCustomAttributes = "";

		// semanas
		$this->semanas->ViewValue = $this->semanas->CurrentValue;
		$this->semanas->ViewCustomAttributes = "";

		// meses
		$this->meses->ViewValue = $this->meses->CurrentValue;
		$this->meses->ViewCustomAttributes = "";

		// discapacidad
		$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->ViewCustomAttributes = "";

		// resultado
		$this->resultado->ViewValue = $this->resultado->CurrentValue;
		$this->resultado->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// tipoprueba
		$this->tipoprueba->ViewValue = $this->tipoprueba->CurrentValue;
		$this->tipoprueba->ViewCustomAttributes = "";

		// resultadprueba
		$this->resultadprueba->ViewValue = $this->resultadprueba->CurrentValue;
		$this->resultadprueba->ViewCustomAttributes = "";

		// recomendacion
		$this->recomendacion->ViewValue = $this->recomendacion->CurrentValue;
		$this->recomendacion->ViewCustomAttributes = "";

		// id_tipodiagnosticoaudiologia
		if (strval($this->id_tipodiagnosticoaudiologia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipodiagnosticoaudiologia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiagnosticoaudiologia`";
		$sWhereWrk = "";
		$this->id_tipodiagnosticoaudiologia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipodiagnosticoaudiologia->ViewValue = $this->id_tipodiagnosticoaudiologia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipodiagnosticoaudiologia->ViewValue = $this->id_tipodiagnosticoaudiologia->CurrentValue;
			}
		} else {
			$this->id_tipodiagnosticoaudiologia->ViewValue = NULL;
		}
		$this->id_tipodiagnosticoaudiologia->ViewCustomAttributes = "";

		// nombrediagnotico
		$this->nombrediagnotico->ViewValue = $this->nombrediagnotico->CurrentValue;
		$this->nombrediagnotico->ViewCustomAttributes = "";

		// resultadodiagnostico
		$this->resultadodiagnostico->ViewValue = $this->resultadodiagnostico->CurrentValue;
		$this->resultadodiagnostico->ViewCustomAttributes = "";

		// tipotratamiento
		$this->tipotratamiento->ViewValue = $this->tipotratamiento->CurrentValue;
		$this->tipotratamiento->ViewCustomAttributes = "";

		// tipoderivacion
		if (strval($this->tipoderivacion->CurrentValue) <> "") {
			$this->tipoderivacion->ViewValue = $this->tipoderivacion->OptionCaption($this->tipoderivacion->CurrentValue);
		} else {
			$this->tipoderivacion->ViewValue = NULL;
		}
		$this->tipoderivacion->ViewCustomAttributes = "";

		// nombreespcialidad
		$this->nombreespcialidad->ViewValue = $this->nombreespcialidad->CurrentValue;
		$this->nombreespcialidad->ViewCustomAttributes = "";

		// observaciones1
		$this->observaciones1->ViewValue = $this->observaciones1->CurrentValue;
		$this->observaciones1->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

			// id_neonato
			$this->id_neonato->LinkCustomAttributes = "";
			$this->id_neonato->HrefValue = "";
			$this->id_neonato->TooltipValue = "";

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

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// dias
			$this->dias->LinkCustomAttributes = "";
			$this->dias->HrefValue = "";
			$this->dias->TooltipValue = "";

			// semanas
			$this->semanas->LinkCustomAttributes = "";
			$this->semanas->HrefValue = "";
			$this->semanas->TooltipValue = "";

			// meses
			$this->meses->LinkCustomAttributes = "";
			$this->meses->HrefValue = "";
			$this->meses->TooltipValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";
			$this->discapacidad->TooltipValue = "";

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";
			$this->resultado->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// tipoprueba
			$this->tipoprueba->LinkCustomAttributes = "";
			$this->tipoprueba->HrefValue = "";
			$this->tipoprueba->TooltipValue = "";

			// resultadprueba
			$this->resultadprueba->LinkCustomAttributes = "";
			$this->resultadprueba->HrefValue = "";
			$this->resultadprueba->TooltipValue = "";

			// recomendacion
			$this->recomendacion->LinkCustomAttributes = "";
			$this->recomendacion->HrefValue = "";
			$this->recomendacion->TooltipValue = "";

			// id_tipodiagnosticoaudiologia
			$this->id_tipodiagnosticoaudiologia->LinkCustomAttributes = "";
			$this->id_tipodiagnosticoaudiologia->HrefValue = "";
			$this->id_tipodiagnosticoaudiologia->TooltipValue = "";

			// nombrediagnotico
			$this->nombrediagnotico->LinkCustomAttributes = "";
			$this->nombrediagnotico->HrefValue = "";
			$this->nombrediagnotico->TooltipValue = "";

			// resultadodiagnostico
			$this->resultadodiagnostico->LinkCustomAttributes = "";
			$this->resultadodiagnostico->HrefValue = "";
			$this->resultadodiagnostico->TooltipValue = "";

			// tipotratamiento
			$this->tipotratamiento->LinkCustomAttributes = "";
			$this->tipotratamiento->HrefValue = "";
			$this->tipotratamiento->TooltipValue = "";

			// tipoderivacion
			$this->tipoderivacion->LinkCustomAttributes = "";
			$this->tipoderivacion->HrefValue = "";
			$this->tipoderivacion->TooltipValue = "";

			// nombreespcialidad
			$this->nombreespcialidad->LinkCustomAttributes = "";
			$this->nombreespcialidad->HrefValue = "";
			$this->nombreespcialidad->TooltipValue = "";

			// observaciones1
			$this->observaciones1->LinkCustomAttributes = "";
			$this->observaciones1->HrefValue = "";
			$this->observaciones1->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($Reporte_Neontal_report)) $Reporte_Neontal_report = new cReporte_Neontal_report();

// Page init
$Reporte_Neontal_report->Page_Init();

// Page main
$Reporte_Neontal_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Reporte_Neontal_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Reporte_Neontal->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php
$Reporte_Neontal_report->RecCnt = 1; // No grouping
if ($Reporte_Neontal_report->DbDetailFilter <> "") {
	if ($Reporte_Neontal_report->ReportFilter <> "") $Reporte_Neontal_report->ReportFilter .= " AND ";
	$Reporte_Neontal_report->ReportFilter .= "(" . $Reporte_Neontal_report->DbDetailFilter . ")";
}
$ReportConn = &$Reporte_Neontal_report->Connection();

// Set up detail SQL
$Reporte_Neontal->CurrentFilter = $Reporte_Neontal_report->ReportFilter;
$Reporte_Neontal_report->ReportSql = $Reporte_Neontal->DetailSQL();

// Load recordset
$Reporte_Neontal_report->Recordset = $ReportConn->Execute($Reporte_Neontal_report->ReportSql);
$Reporte_Neontal_report->RecordExists = !$Reporte_Neontal_report->Recordset->EOF;
?>
<?php if ($Reporte_Neontal->Export == "") { ?>
<?php if ($Reporte_Neontal_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Reporte_Neontal_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Reporte_Neontal_report->ShowPageHeader(); ?>
<table class="ewReportTable">
<?php

	// Get detail records
	$Reporte_Neontal_report->ReportFilter = $Reporte_Neontal_report->DefaultFilter;
	if ($Reporte_Neontal_report->DbDetailFilter <> "") {
		if ($Reporte_Neontal_report->ReportFilter <> "")
			$Reporte_Neontal_report->ReportFilter .= " AND ";
		$Reporte_Neontal_report->ReportFilter .= "(" . $Reporte_Neontal_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$Reporte_Neontal->CurrentFilter = $Reporte_Neontal_report->ReportFilter;
	$Reporte_Neontal_report->ReportSql = $Reporte_Neontal->DetailSQL();

	// Load detail records
	$Reporte_Neontal_report->DetailRecordset = $ReportConn->Execute($Reporte_Neontal_report->ReportSql);
	$Reporte_Neontal_report->DtlRecordCount = $Reporte_Neontal_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Reporte_Neontal_report->DetailRecordset->EOF) {
		$Reporte_Neontal_report->RecCnt++;
	}
	if ($Reporte_Neontal_report->RecCnt == 1) {
		$Reporte_Neontal_report->ReportCounts[0] = 0;
	}
	$Reporte_Neontal_report->ReportCounts[0] += $Reporte_Neontal_report->DtlRecordCount;
	if ($Reporte_Neontal_report->RecordExists) {
?>
	<tr>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->id_neonato->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->apellidopaterno->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->apellidomaterno->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->nombre->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->ci->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->fecha_nacimiento->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->dias->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->semanas->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->meses->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->discapacidad->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->resultado->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->observaciones->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->tipoprueba->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->resultadprueba->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->recomendacion->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->id_tipodiagnosticoaudiologia->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->nombrediagnotico->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->resultadodiagnostico->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->tipotratamiento->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->tipoderivacion->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->nombreespcialidad->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->observaciones1->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Reporte_Neontal->fecha->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Reporte_Neontal_report->DetailRecordset->EOF) {
		$Reporte_Neontal_report->RowCnt++;
		$Reporte_Neontal->id_neonato->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('id_neonato'));
		$Reporte_Neontal->apellidopaterno->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('apellidopaterno'));
		$Reporte_Neontal->apellidomaterno->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('apellidomaterno'));
		$Reporte_Neontal->nombre->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('nombre'));
		$Reporte_Neontal->ci->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('ci'));
		$Reporte_Neontal->fecha_nacimiento->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('fecha_nacimiento'));
		$Reporte_Neontal->dias->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('dias'));
		$Reporte_Neontal->semanas->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('semanas'));
		$Reporte_Neontal->meses->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('meses'));
		$Reporte_Neontal->discapacidad->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('discapacidad'));
		$Reporte_Neontal->resultado->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('resultado'));
		$Reporte_Neontal->observaciones->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('observaciones'));
		$Reporte_Neontal->tipoprueba->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('tipoprueba'));
		$Reporte_Neontal->resultadprueba->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('resultadprueba'));
		$Reporte_Neontal->recomendacion->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('recomendacion'));
		$Reporte_Neontal->id_tipodiagnosticoaudiologia->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('id_tipodiagnosticoaudiologia'));
		$Reporte_Neontal->nombrediagnotico->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('nombrediagnotico'));
		$Reporte_Neontal->resultadodiagnostico->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('resultadodiagnostico'));
		$Reporte_Neontal->tipotratamiento->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('tipotratamiento'));
		$Reporte_Neontal->tipoderivacion->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('tipoderivacion'));
		$Reporte_Neontal->nombreespcialidad->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('nombreespcialidad'));
		$Reporte_Neontal->observaciones1->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('observaciones1'));
		$Reporte_Neontal->fecha->setDbValue($Reporte_Neontal_report->DetailRecordset->fields('fecha'));

		// Render for view
		$Reporte_Neontal->RowType = EW_ROWTYPE_VIEW;
		$Reporte_Neontal->ResetAttrs();
		$Reporte_Neontal_report->RenderRow();
?>
	<tr>
		<td<?php echo $Reporte_Neontal->id_neonato->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->id_neonato->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->id_neonato->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->apellidopaterno->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->apellidopaterno->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->apellidopaterno->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->apellidomaterno->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->apellidomaterno->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->apellidomaterno->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->nombre->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->nombre->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->nombre->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->ci->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->ci->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->ci->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->fecha_nacimiento->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->fecha_nacimiento->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->dias->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->dias->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->dias->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->semanas->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->semanas->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->semanas->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->meses->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->meses->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->meses->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->discapacidad->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->discapacidad->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->discapacidad->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->resultado->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->resultado->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->resultado->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->observaciones->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->observaciones->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->observaciones->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->tipoprueba->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->tipoprueba->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->tipoprueba->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->resultadprueba->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->resultadprueba->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->resultadprueba->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->recomendacion->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->recomendacion->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->recomendacion->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->id_tipodiagnosticoaudiologia->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->id_tipodiagnosticoaudiologia->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->id_tipodiagnosticoaudiologia->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->nombrediagnotico->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->nombrediagnotico->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->nombrediagnotico->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->resultadodiagnostico->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->resultadodiagnostico->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->resultadodiagnostico->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->tipotratamiento->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->tipotratamiento->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->tipotratamiento->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->tipoderivacion->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->tipoderivacion->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->tipoderivacion->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->nombreespcialidad->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->nombreespcialidad->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->nombreespcialidad->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->observaciones1->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->observaciones1->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->observaciones1->ViewValue ?></span>
</td>
		<td<?php echo $Reporte_Neontal->fecha->CellAttributes() ?>>
<span<?php echo $Reporte_Neontal->fecha->ViewAttributes() ?>>
<?php echo $Reporte_Neontal->fecha->ViewValue ?></span>
</td>
	</tr>
<?php
		$Reporte_Neontal_report->DetailRecordset->MoveNext();
	}
	$Reporte_Neontal_report->DetailRecordset->Close();
?>
<?php if ($Reporte_Neontal_report->RecordExists) { ?>
	<tr><td colspan=23>&nbsp;<br></td></tr>
	<tr><td colspan=23 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Reporte_Neontal_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Reporte_Neontal_report->RecordExists) { ?>
	<tr><td colspan=23>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
<?php
$Reporte_Neontal_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Reporte_Neontal->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Reporte_Neontal_report->Page_Terminate();
?>
