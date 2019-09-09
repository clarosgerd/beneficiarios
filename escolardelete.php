<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "escolarinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$escolar_delete = NULL; // Initialize page object first

class cescolar_delete extends cescolar {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'escolar';

	// Page object name
	var $PageObjName = 'escolar_delete';

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
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

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
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Table object (escolar)
		if (!isset($GLOBALS["escolar"]) || get_class($GLOBALS["escolar"]) == "cescolar") {
			$GLOBALS["escolar"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["escolar"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'escolar', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("escolarlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
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

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->fecha->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->curso->SetVisibility();
		$this->id_discapacidad->SetVisibility();
		$this->id_tipodiscapacidad->SetVisibility();
		$this->resultado->SetVisibility();
		$this->resultadotamizaje->SetVisibility();
		$this->tapon->SetVisibility();
		$this->tapodonde->SetVisibility();
		$this->repetirprueba->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->id_apoderado->SetVisibility();
		$this->id_referencia->SetVisibility();
		$this->codigorude->SetVisibility();
		$this->codigorude_es->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();

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
		global $EW_EXPORT, $escolar;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($escolar);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("escolarlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in escolar class, escolarinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("escolarlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->id->setDbValue($row['id']);
		$this->fecha->setDbValue($row['fecha']);
		$this->id_departamento->setDbValue($row['id_departamento']);
		$this->unidadeducativa->setDbValue($row['unidadeducativa']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombres->setDbValue($row['nombres']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->curso->setDbValue($row['curso']);
		$this->id_discapacidad->setDbValue($row['id_discapacidad']);
		$this->id_tipodiscapacidad->setDbValue($row['id_tipodiscapacidad']);
		$this->resultado->setDbValue($row['resultado']);
		$this->resultadotamizaje->setDbValue($row['resultadotamizaje']);
		$this->tapon->setDbValue($row['tapon']);
		$this->tapodonde->setDbValue($row['tapodonde']);
		$this->repetirprueba->setDbValue($row['repetirprueba']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_apoderado->setDbValue($row['id_apoderado']);
		if (array_key_exists('EV__id_apoderado', $rs->fields)) {
			$this->id_apoderado->VirtualValue = $rs->fields('EV__id_apoderado'); // Set up virtual field value
		} else {
			$this->id_apoderado->VirtualValue = ""; // Clear value
		}
		$this->id_referencia->setDbValue($row['id_referencia']);
		if (array_key_exists('EV__id_referencia', $rs->fields)) {
			$this->id_referencia->VirtualValue = $rs->fields('EV__id_referencia'); // Set up virtual field value
		} else {
			$this->id_referencia->VirtualValue = ""; // Clear value
		}
		$this->codigorude->setDbValue($row['codigorude']);
		$this->codigorude_es->setDbValue($row['codigorude_es']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['fecha'] = NULL;
		$row['id_departamento'] = NULL;
		$row['unidadeducativa'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombres'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['curso'] = NULL;
		$row['id_discapacidad'] = NULL;
		$row['id_tipodiscapacidad'] = NULL;
		$row['resultado'] = NULL;
		$row['resultadotamizaje'] = NULL;
		$row['tapon'] = NULL;
		$row['tapodonde'] = NULL;
		$row['repetirprueba'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_apoderado'] = NULL;
		$row['id_referencia'] = NULL;
		$row['codigorude'] = NULL;
		$row['codigorude_es'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_departamento->DbValue = $row['id_departamento'];
		$this->unidadeducativa->DbValue = $row['unidadeducativa'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombres->DbValue = $row['nombres'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->curso->DbValue = $row['curso'];
		$this->id_discapacidad->DbValue = $row['id_discapacidad'];
		$this->id_tipodiscapacidad->DbValue = $row['id_tipodiscapacidad'];
		$this->resultado->DbValue = $row['resultado'];
		$this->resultadotamizaje->DbValue = $row['resultadotamizaje'];
		$this->tapon->DbValue = $row['tapon'];
		$this->tapodonde->DbValue = $row['tapodonde'];
		$this->repetirprueba->DbValue = $row['repetirprueba'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_apoderado->DbValue = $row['id_apoderado'];
		$this->id_referencia->DbValue = $row['id_referencia'];
		$this->codigorude->DbValue = $row['codigorude'];
		$this->codigorude_es->DbValue = $row['codigorude_es'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->id_centro->DbValue = $row['id_centro'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// fecha
		// id_departamento

		$this->id_departamento->CellCssStyle = "white-space: nowrap;";

		// unidadeducativa
		// apellidopaterno
		// apellidomaterno
		// nombres
		// ci
		// fechanacimiento
		// sexo
		// curso
		// id_discapacidad
		// id_tipodiscapacidad
		// resultado
		// resultadotamizaje
		// tapon
		// tapodonde
		// repetirprueba
		// observaciones
		// id_apoderado
		// id_referencia
		// codigorude
		// codigorude_es
		// nrodiscapacidad
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

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

		// curso
		$this->curso->ViewValue = $this->curso->CurrentValue;
		$this->curso->ViewCustomAttributes = "";

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
			$this->tapon->ViewValue = $this->tapon->OptionCaption($this->tapon->CurrentValue);
		} else {
			$this->tapon->ViewValue = NULL;
		}
		$this->tapon->ViewCustomAttributes = "";

		// tapodonde
		if (strval($this->tapodonde->CurrentValue) <> "") {
			$this->tapodonde->ViewValue = $this->tapodonde->OptionCaption($this->tapodonde->CurrentValue);
		} else {
			$this->tapodonde->ViewValue = NULL;
		}
		$this->tapodonde->ViewCustomAttributes = "";

		// repetirprueba
		if (strval($this->repetirprueba->CurrentValue) <> "") {
			$this->repetirprueba->ViewValue = $this->repetirprueba->OptionCaption($this->repetirprueba->CurrentValue);
		} else {
			$this->repetirprueba->ViewValue = NULL;
		}
		$this->repetirprueba->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_apoderado
		if ($this->id_apoderado->VirtualValue <> "") {
			$this->id_apoderado->ViewValue = $this->id_apoderado->VirtualValue;
		} else {
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
		}
		$this->id_apoderado->ViewCustomAttributes = "";

		// id_referencia
		if ($this->id_referencia->VirtualValue <> "") {
			$this->id_referencia->ViewValue = $this->id_referencia->VirtualValue;
		} else {
		if (strval($this->id_referencia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_referencia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombrescompleto` AS `DispFld`, `nombrescentromedico` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
		$sWhereWrk = "";
		$this->id_referencia->LookupFilters = array("dx1" => '`nombrescompleto`', "dx2" => '`nombrescentromedico`');
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
		}
		$this->id_referencia->ViewCustomAttributes = "";

		// codigorude
		$this->codigorude->ViewValue = $this->codigorude->CurrentValue;
		$this->codigorude->ViewCustomAttributes = "";

		// codigorude_es
		$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
		$this->codigorude_es->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

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

			// tapodonde
			$this->tapodonde->LinkCustomAttributes = "";
			$this->tapodonde->HrefValue = "";
			$this->tapodonde->TooltipValue = "";

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

			// codigorude
			$this->codigorude->LinkCustomAttributes = "";
			$this->codigorude->HrefValue = "";
			$this->codigorude->TooltipValue = "";

			// codigorude_es
			$this->codigorude_es->LinkCustomAttributes = "";
			$this->codigorude_es->HrefValue = "";
			$this->codigorude_es->TooltipValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
			$this->nrodiscapacidad->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("escolarlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
if (!isset($escolar_delete)) $escolar_delete = new cescolar_delete();

// Page init
$escolar_delete->Page_Init();

// Page main
$escolar_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$escolar_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fescolardelete = new ew_Form("fescolardelete", "delete");

// Form_CustomValidate event
fescolardelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fescolardelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fescolardelete.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fescolardelete.Lists["x_unidadeducativa"].Data = "<?php echo $escolar_delete->unidadeducativa->LookupFilterQuery(FALSE, "delete") ?>";
fescolardelete.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolardelete.Lists["x_sexo"].Options = <?php echo json_encode($escolar_delete->sexo->Options()) ?>;
fescolardelete.Lists["x_id_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fescolardelete.Lists["x_id_discapacidad"].Data = "<?php echo $escolar_delete->id_discapacidad->LookupFilterQuery(FALSE, "delete") ?>";
fescolardelete.Lists["x_id_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
fescolardelete.Lists["x_id_tipodiscapacidad"].Data = "<?php echo $escolar_delete->id_tipodiscapacidad->LookupFilterQuery(FALSE, "delete") ?>";
fescolardelete.Lists["x_resultado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolardelete.Lists["x_resultado"].Options = <?php echo json_encode($escolar_delete->resultado->Options()) ?>;
fescolardelete.Lists["x_tapon"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolardelete.Lists["x_tapon"].Options = <?php echo json_encode($escolar_delete->tapon->Options()) ?>;
fescolardelete.Lists["x_tapodonde"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolardelete.Lists["x_tapodonde"].Options = <?php echo json_encode($escolar_delete->tapodonde->Options()) ?>;
fescolardelete.Lists["x_repetirprueba"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolardelete.Lists["x_repetirprueba"].Options = <?php echo json_encode($escolar_delete->repetirprueba->Options()) ?>;
fescolardelete.Lists["x_id_apoderado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"apoderado"};
fescolardelete.Lists["x_id_apoderado"].Data = "<?php echo $escolar_delete->id_apoderado->LookupFilterQuery(FALSE, "delete") ?>";
fescolardelete.Lists["x_id_referencia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombrescompleto","x_nombrescentromedico","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referencia"};
fescolardelete.Lists["x_id_referencia"].Data = "<?php echo $escolar_delete->id_referencia->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $escolar_delete->ShowPageHeader(); ?>
<?php
$escolar_delete->ShowMessage();
?>
<form name="fescolardelete" id="fescolardelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($escolar_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $escolar_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="escolar">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($escolar_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($escolar->fecha->Visible) { // fecha ?>
		<th class="<?php echo $escolar->fecha->HeaderCellClass() ?>"><span id="elh_escolar_fecha" class="escolar_fecha"><?php echo $escolar->fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->unidadeducativa->Visible) { // unidadeducativa ?>
		<th class="<?php echo $escolar->unidadeducativa->HeaderCellClass() ?>"><span id="elh_escolar_unidadeducativa" class="escolar_unidadeducativa"><?php echo $escolar->unidadeducativa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->apellidopaterno->Visible) { // apellidopaterno ?>
		<th class="<?php echo $escolar->apellidopaterno->HeaderCellClass() ?>"><span id="elh_escolar_apellidopaterno" class="escolar_apellidopaterno"><?php echo $escolar->apellidopaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->apellidomaterno->Visible) { // apellidomaterno ?>
		<th class="<?php echo $escolar->apellidomaterno->HeaderCellClass() ?>"><span id="elh_escolar_apellidomaterno" class="escolar_apellidomaterno"><?php echo $escolar->apellidomaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->nombres->Visible) { // nombres ?>
		<th class="<?php echo $escolar->nombres->HeaderCellClass() ?>"><span id="elh_escolar_nombres" class="escolar_nombres"><?php echo $escolar->nombres->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->ci->Visible) { // ci ?>
		<th class="<?php echo $escolar->ci->HeaderCellClass() ?>"><span id="elh_escolar_ci" class="escolar_ci"><?php echo $escolar->ci->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->fechanacimiento->Visible) { // fechanacimiento ?>
		<th class="<?php echo $escolar->fechanacimiento->HeaderCellClass() ?>"><span id="elh_escolar_fechanacimiento" class="escolar_fechanacimiento"><?php echo $escolar->fechanacimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->sexo->Visible) { // sexo ?>
		<th class="<?php echo $escolar->sexo->HeaderCellClass() ?>"><span id="elh_escolar_sexo" class="escolar_sexo"><?php echo $escolar->sexo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->curso->Visible) { // curso ?>
		<th class="<?php echo $escolar->curso->HeaderCellClass() ?>"><span id="elh_escolar_curso" class="escolar_curso"><?php echo $escolar->curso->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->id_discapacidad->Visible) { // id_discapacidad ?>
		<th class="<?php echo $escolar->id_discapacidad->HeaderCellClass() ?>"><span id="elh_escolar_id_discapacidad" class="escolar_id_discapacidad"><?php echo $escolar->id_discapacidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
		<th class="<?php echo $escolar->id_tipodiscapacidad->HeaderCellClass() ?>"><span id="elh_escolar_id_tipodiscapacidad" class="escolar_id_tipodiscapacidad"><?php echo $escolar->id_tipodiscapacidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->resultado->Visible) { // resultado ?>
		<th class="<?php echo $escolar->resultado->HeaderCellClass() ?>"><span id="elh_escolar_resultado" class="escolar_resultado"><?php echo $escolar->resultado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->resultadotamizaje->Visible) { // resultadotamizaje ?>
		<th class="<?php echo $escolar->resultadotamizaje->HeaderCellClass() ?>"><span id="elh_escolar_resultadotamizaje" class="escolar_resultadotamizaje"><?php echo $escolar->resultadotamizaje->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->tapon->Visible) { // tapon ?>
		<th class="<?php echo $escolar->tapon->HeaderCellClass() ?>"><span id="elh_escolar_tapon" class="escolar_tapon"><?php echo $escolar->tapon->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->tapodonde->Visible) { // tapodonde ?>
		<th class="<?php echo $escolar->tapodonde->HeaderCellClass() ?>"><span id="elh_escolar_tapodonde" class="escolar_tapodonde"><?php echo $escolar->tapodonde->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->repetirprueba->Visible) { // repetirprueba ?>
		<th class="<?php echo $escolar->repetirprueba->HeaderCellClass() ?>"><span id="elh_escolar_repetirprueba" class="escolar_repetirprueba"><?php echo $escolar->repetirprueba->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->observaciones->Visible) { // observaciones ?>
		<th class="<?php echo $escolar->observaciones->HeaderCellClass() ?>"><span id="elh_escolar_observaciones" class="escolar_observaciones"><?php echo $escolar->observaciones->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->id_apoderado->Visible) { // id_apoderado ?>
		<th class="<?php echo $escolar->id_apoderado->HeaderCellClass() ?>"><span id="elh_escolar_id_apoderado" class="escolar_id_apoderado"><?php echo $escolar->id_apoderado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->id_referencia->Visible) { // id_referencia ?>
		<th class="<?php echo $escolar->id_referencia->HeaderCellClass() ?>"><span id="elh_escolar_id_referencia" class="escolar_id_referencia"><?php echo $escolar->id_referencia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->codigorude->Visible) { // codigorude ?>
		<th class="<?php echo $escolar->codigorude->HeaderCellClass() ?>"><span id="elh_escolar_codigorude" class="escolar_codigorude"><?php echo $escolar->codigorude->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->codigorude_es->Visible) { // codigorude_es ?>
		<th class="<?php echo $escolar->codigorude_es->HeaderCellClass() ?>"><span id="elh_escolar_codigorude_es" class="escolar_codigorude_es"><?php echo $escolar->codigorude_es->FldCaption() ?></span></th>
<?php } ?>
<?php if ($escolar->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<th class="<?php echo $escolar->nrodiscapacidad->HeaderCellClass() ?>"><span id="elh_escolar_nrodiscapacidad" class="escolar_nrodiscapacidad"><?php echo $escolar->nrodiscapacidad->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$escolar_delete->RecCnt = 0;
$i = 0;
while (!$escolar_delete->Recordset->EOF) {
	$escolar_delete->RecCnt++;
	$escolar_delete->RowCnt++;

	// Set row properties
	$escolar->ResetAttrs();
	$escolar->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$escolar_delete->LoadRowValues($escolar_delete->Recordset);

	// Render row
	$escolar_delete->RenderRow();
?>
	<tr<?php echo $escolar->RowAttributes() ?>>
<?php if ($escolar->fecha->Visible) { // fecha ?>
		<td<?php echo $escolar->fecha->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_fecha" class="escolar_fecha">
<span<?php echo $escolar->fecha->ViewAttributes() ?>>
<?php echo $escolar->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->unidadeducativa->Visible) { // unidadeducativa ?>
		<td<?php echo $escolar->unidadeducativa->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_unidadeducativa" class="escolar_unidadeducativa">
<span<?php echo $escolar->unidadeducativa->ViewAttributes() ?>>
<?php echo $escolar->unidadeducativa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->apellidopaterno->Visible) { // apellidopaterno ?>
		<td<?php echo $escolar->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_apellidopaterno" class="escolar_apellidopaterno">
<span<?php echo $escolar->apellidopaterno->ViewAttributes() ?>>
<?php echo $escolar->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->apellidomaterno->Visible) { // apellidomaterno ?>
		<td<?php echo $escolar->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_apellidomaterno" class="escolar_apellidomaterno">
<span<?php echo $escolar->apellidomaterno->ViewAttributes() ?>>
<?php echo $escolar->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->nombres->Visible) { // nombres ?>
		<td<?php echo $escolar->nombres->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_nombres" class="escolar_nombres">
<span<?php echo $escolar->nombres->ViewAttributes() ?>>
<?php echo $escolar->nombres->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->ci->Visible) { // ci ?>
		<td<?php echo $escolar->ci->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_ci" class="escolar_ci">
<span<?php echo $escolar->ci->ViewAttributes() ?>>
<?php echo $escolar->ci->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->fechanacimiento->Visible) { // fechanacimiento ?>
		<td<?php echo $escolar->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_fechanacimiento" class="escolar_fechanacimiento">
<span<?php echo $escolar->fechanacimiento->ViewAttributes() ?>>
<?php echo $escolar->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->sexo->Visible) { // sexo ?>
		<td<?php echo $escolar->sexo->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_sexo" class="escolar_sexo">
<span<?php echo $escolar->sexo->ViewAttributes() ?>>
<?php echo $escolar->sexo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->curso->Visible) { // curso ?>
		<td<?php echo $escolar->curso->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_curso" class="escolar_curso">
<span<?php echo $escolar->curso->ViewAttributes() ?>>
<?php echo $escolar->curso->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->id_discapacidad->Visible) { // id_discapacidad ?>
		<td<?php echo $escolar->id_discapacidad->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_id_discapacidad" class="escolar_id_discapacidad">
<span<?php echo $escolar->id_discapacidad->ViewAttributes() ?>>
<?php echo $escolar->id_discapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
		<td<?php echo $escolar->id_tipodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_id_tipodiscapacidad" class="escolar_id_tipodiscapacidad">
<span<?php echo $escolar->id_tipodiscapacidad->ViewAttributes() ?>>
<?php echo $escolar->id_tipodiscapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->resultado->Visible) { // resultado ?>
		<td<?php echo $escolar->resultado->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_resultado" class="escolar_resultado">
<span<?php echo $escolar->resultado->ViewAttributes() ?>>
<?php echo $escolar->resultado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->resultadotamizaje->Visible) { // resultadotamizaje ?>
		<td<?php echo $escolar->resultadotamizaje->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_resultadotamizaje" class="escolar_resultadotamizaje">
<span<?php echo $escolar->resultadotamizaje->ViewAttributes() ?>>
<?php echo $escolar->resultadotamizaje->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->tapon->Visible) { // tapon ?>
		<td<?php echo $escolar->tapon->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_tapon" class="escolar_tapon">
<span<?php echo $escolar->tapon->ViewAttributes() ?>>
<?php echo $escolar->tapon->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->tapodonde->Visible) { // tapodonde ?>
		<td<?php echo $escolar->tapodonde->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_tapodonde" class="escolar_tapodonde">
<span<?php echo $escolar->tapodonde->ViewAttributes() ?>>
<?php echo $escolar->tapodonde->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->repetirprueba->Visible) { // repetirprueba ?>
		<td<?php echo $escolar->repetirprueba->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_repetirprueba" class="escolar_repetirprueba">
<span<?php echo $escolar->repetirprueba->ViewAttributes() ?>>
<?php echo $escolar->repetirprueba->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->observaciones->Visible) { // observaciones ?>
		<td<?php echo $escolar->observaciones->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_observaciones" class="escolar_observaciones">
<span<?php echo $escolar->observaciones->ViewAttributes() ?>>
<?php echo $escolar->observaciones->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->id_apoderado->Visible) { // id_apoderado ?>
		<td<?php echo $escolar->id_apoderado->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_id_apoderado" class="escolar_id_apoderado">
<span<?php echo $escolar->id_apoderado->ViewAttributes() ?>>
<?php echo $escolar->id_apoderado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->id_referencia->Visible) { // id_referencia ?>
		<td<?php echo $escolar->id_referencia->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_id_referencia" class="escolar_id_referencia">
<span<?php echo $escolar->id_referencia->ViewAttributes() ?>>
<?php echo $escolar->id_referencia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->codigorude->Visible) { // codigorude ?>
		<td<?php echo $escolar->codigorude->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_codigorude" class="escolar_codigorude">
<span<?php echo $escolar->codigorude->ViewAttributes() ?>>
<?php echo $escolar->codigorude->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->codigorude_es->Visible) { // codigorude_es ?>
		<td<?php echo $escolar->codigorude_es->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_codigorude_es" class="escolar_codigorude_es">
<span<?php echo $escolar->codigorude_es->ViewAttributes() ?>>
<?php echo $escolar->codigorude_es->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($escolar->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td<?php echo $escolar->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $escolar_delete->RowCnt ?>_escolar_nrodiscapacidad" class="escolar_nrodiscapacidad">
<span<?php echo $escolar->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $escolar->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$escolar_delete->Recordset->MoveNext();
}
$escolar_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $escolar_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fescolardelete.Init();
</script>
<?php
$escolar_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$escolar_delete->Page_Terminate();
?>
