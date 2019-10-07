<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "estudianteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$estudiante_delete = NULL; // Initialize page object first

class cestudiante_delete extends cestudiante {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'estudiante';

	// Page object name
	var $PageObjName = 'estudiante_delete';

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

		// Table object (estudiante)
		if (!isset($GLOBALS["estudiante"]) || get_class($GLOBALS["estudiante"]) == "cestudiante") {
			$GLOBALS["estudiante"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["estudiante"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'estudiante', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("estudiantelist.php"));
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
		$this->codigorude->SetVisibility();
		$this->codigorude_es->SetVisibility();
		$this->departamento->SetVisibility();
		$this->municipio->SetVisibility();
		$this->provincisa->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->curso->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapacidad->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->fecha->SetVisibility();

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
		global $EW_EXPORT, $estudiante;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($estudiante);
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
			$this->Page_Terminate("estudiantelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in estudiante class, estudianteinfo.php

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
				$this->Page_Terminate("estudiantelist.php"); // Return to list
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
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
		$this->codigorude->setDbValue($row['codigorude']);
		$this->codigorude_es->setDbValue($row['codigorude_es']);
		$this->departamento->setDbValue($row['departamento']);
		$this->municipio->setDbValue($row['municipio']);
		$this->provincisa->setDbValue($row['provincisa']);
		$this->unidadeducativa->setDbValue($row['unidadeducativa']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombres->setDbValue($row['nombres']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->curso->setDbValue($row['curso']);
		$this->discapacidad->setDbValue($row['discapacidad']);
		$this->tipodiscapacidad->setDbValue($row['tipodiscapacidad']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
		$this->gestion->setDbValue($row['gestion']);
		$this->esincritoespecial->setDbValue($row['esincritoespecial']);
		$this->fecha->setDbValue($row['fecha']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['codigorude'] = NULL;
		$row['codigorude_es'] = NULL;
		$row['departamento'] = NULL;
		$row['municipio'] = NULL;
		$row['provincisa'] = NULL;
		$row['unidadeducativa'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombres'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['curso'] = NULL;
		$row['discapacidad'] = NULL;
		$row['tipodiscapacidad'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_centro'] = NULL;
		$row['gestion'] = NULL;
		$row['esincritoespecial'] = NULL;
		$row['fecha'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->codigorude->DbValue = $row['codigorude'];
		$this->codigorude_es->DbValue = $row['codigorude_es'];
		$this->departamento->DbValue = $row['departamento'];
		$this->municipio->DbValue = $row['municipio'];
		$this->provincisa->DbValue = $row['provincisa'];
		$this->unidadeducativa->DbValue = $row['unidadeducativa'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombres->DbValue = $row['nombres'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->curso->DbValue = $row['curso'];
		$this->discapacidad->DbValue = $row['discapacidad'];
		$this->tipodiscapacidad->DbValue = $row['tipodiscapacidad'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_centro->DbValue = $row['id_centro'];
		$this->gestion->DbValue = $row['gestion'];
		$this->esincritoespecial->DbValue = $row['esincritoespecial'];
		$this->fecha->DbValue = $row['fecha'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

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

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("estudiantelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($estudiante_delete)) $estudiante_delete = new cestudiante_delete();

// Page init
$estudiante_delete->Page_Init();

// Page main
$estudiante_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$estudiante_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = festudiantedelete = new ew_Form("festudiantedelete", "delete");

// Form_CustomValidate event
festudiantedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
festudiantedelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
festudiantedelete.Lists["x_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
festudiantedelete.Lists["x_departamento"].Data = "<?php echo $estudiante_delete->departamento->LookupFilterQuery(FALSE, "delete") ?>";
festudiantedelete.Lists["x_municipio"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"municipio"};
festudiantedelete.Lists["x_municipio"].Data = "<?php echo $estudiante_delete->municipio->LookupFilterQuery(FALSE, "delete") ?>";
festudiantedelete.Lists["x_provincisa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincia"};
festudiantedelete.Lists["x_provincisa"].Data = "<?php echo $estudiante_delete->provincisa->LookupFilterQuery(FALSE, "delete") ?>";
festudiantedelete.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
festudiantedelete.Lists["x_unidadeducativa"].Data = "<?php echo $estudiante_delete->unidadeducativa->LookupFilterQuery(FALSE, "delete") ?>";
festudiantedelete.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
festudiantedelete.Lists["x_sexo"].Options = <?php echo json_encode($estudiante_delete->sexo->Options()) ?>;
festudiantedelete.Lists["x_curso"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_curso","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"curso"};
festudiantedelete.Lists["x_curso"].Data = "<?php echo $estudiante_delete->curso->LookupFilterQuery(FALSE, "delete") ?>";
festudiantedelete.Lists["x_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
festudiantedelete.Lists["x_discapacidad"].Data = "<?php echo $estudiante_delete->discapacidad->LookupFilterQuery(FALSE, "delete") ?>";
festudiantedelete.AutoSuggests["x_discapacidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $estudiante_delete->discapacidad->LookupFilterQuery(TRUE, "delete"))) ?>;
festudiantedelete.Lists["x_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
festudiantedelete.Lists["x_tipodiscapacidad"].Data = "<?php echo $estudiante_delete->tipodiscapacidad->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $estudiante_delete->ShowPageHeader(); ?>
<?php
$estudiante_delete->ShowMessage();
?>
<form name="festudiantedelete" id="festudiantedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($estudiante_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $estudiante_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="estudiante">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($estudiante_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($estudiante->codigorude->Visible) { // codigorude ?>
		<th class="<?php echo $estudiante->codigorude->HeaderCellClass() ?>"><span id="elh_estudiante_codigorude" class="estudiante_codigorude"><?php echo $estudiante->codigorude->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->codigorude_es->Visible) { // codigorude_es ?>
		<th class="<?php echo $estudiante->codigorude_es->HeaderCellClass() ?>"><span id="elh_estudiante_codigorude_es" class="estudiante_codigorude_es"><?php echo $estudiante->codigorude_es->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->departamento->Visible) { // departamento ?>
		<th class="<?php echo $estudiante->departamento->HeaderCellClass() ?>"><span id="elh_estudiante_departamento" class="estudiante_departamento"><?php echo $estudiante->departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->municipio->Visible) { // municipio ?>
		<th class="<?php echo $estudiante->municipio->HeaderCellClass() ?>"><span id="elh_estudiante_municipio" class="estudiante_municipio"><?php echo $estudiante->municipio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->provincisa->Visible) { // provincisa ?>
		<th class="<?php echo $estudiante->provincisa->HeaderCellClass() ?>"><span id="elh_estudiante_provincisa" class="estudiante_provincisa"><?php echo $estudiante->provincisa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->unidadeducativa->Visible) { // unidadeducativa ?>
		<th class="<?php echo $estudiante->unidadeducativa->HeaderCellClass() ?>"><span id="elh_estudiante_unidadeducativa" class="estudiante_unidadeducativa"><?php echo $estudiante->unidadeducativa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->apellidopaterno->Visible) { // apellidopaterno ?>
		<th class="<?php echo $estudiante->apellidopaterno->HeaderCellClass() ?>"><span id="elh_estudiante_apellidopaterno" class="estudiante_apellidopaterno"><?php echo $estudiante->apellidopaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->apellidomaterno->Visible) { // apellidomaterno ?>
		<th class="<?php echo $estudiante->apellidomaterno->HeaderCellClass() ?>"><span id="elh_estudiante_apellidomaterno" class="estudiante_apellidomaterno"><?php echo $estudiante->apellidomaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->nombres->Visible) { // nombres ?>
		<th class="<?php echo $estudiante->nombres->HeaderCellClass() ?>"><span id="elh_estudiante_nombres" class="estudiante_nombres"><?php echo $estudiante->nombres->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<th class="<?php echo $estudiante->nrodiscapacidad->HeaderCellClass() ?>"><span id="elh_estudiante_nrodiscapacidad" class="estudiante_nrodiscapacidad"><?php echo $estudiante->nrodiscapacidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->ci->Visible) { // ci ?>
		<th class="<?php echo $estudiante->ci->HeaderCellClass() ?>"><span id="elh_estudiante_ci" class="estudiante_ci"><?php echo $estudiante->ci->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->fechanacimiento->Visible) { // fechanacimiento ?>
		<th class="<?php echo $estudiante->fechanacimiento->HeaderCellClass() ?>"><span id="elh_estudiante_fechanacimiento" class="estudiante_fechanacimiento"><?php echo $estudiante->fechanacimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->sexo->Visible) { // sexo ?>
		<th class="<?php echo $estudiante->sexo->HeaderCellClass() ?>"><span id="elh_estudiante_sexo" class="estudiante_sexo"><?php echo $estudiante->sexo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->curso->Visible) { // curso ?>
		<th class="<?php echo $estudiante->curso->HeaderCellClass() ?>"><span id="elh_estudiante_curso" class="estudiante_curso"><?php echo $estudiante->curso->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->discapacidad->Visible) { // discapacidad ?>
		<th class="<?php echo $estudiante->discapacidad->HeaderCellClass() ?>"><span id="elh_estudiante_discapacidad" class="estudiante_discapacidad"><?php echo $estudiante->discapacidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
		<th class="<?php echo $estudiante->tipodiscapacidad->HeaderCellClass() ?>"><span id="elh_estudiante_tipodiscapacidad" class="estudiante_tipodiscapacidad"><?php echo $estudiante->tipodiscapacidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->observaciones->Visible) { // observaciones ?>
		<th class="<?php echo $estudiante->observaciones->HeaderCellClass() ?>"><span id="elh_estudiante_observaciones" class="estudiante_observaciones"><?php echo $estudiante->observaciones->FldCaption() ?></span></th>
<?php } ?>
<?php if ($estudiante->fecha->Visible) { // fecha ?>
		<th class="<?php echo $estudiante->fecha->HeaderCellClass() ?>"><span id="elh_estudiante_fecha" class="estudiante_fecha"><?php echo $estudiante->fecha->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$estudiante_delete->RecCnt = 0;
$i = 0;
while (!$estudiante_delete->Recordset->EOF) {
	$estudiante_delete->RecCnt++;
	$estudiante_delete->RowCnt++;

	// Set row properties
	$estudiante->ResetAttrs();
	$estudiante->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$estudiante_delete->LoadRowValues($estudiante_delete->Recordset);

	// Render row
	$estudiante_delete->RenderRow();
?>
	<tr<?php echo $estudiante->RowAttributes() ?>>
<?php if ($estudiante->codigorude->Visible) { // codigorude ?>
		<td<?php echo $estudiante->codigorude->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_codigorude" class="estudiante_codigorude">
<span<?php echo $estudiante->codigorude->ViewAttributes() ?>>
<?php echo $estudiante->codigorude->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->codigorude_es->Visible) { // codigorude_es ?>
		<td<?php echo $estudiante->codigorude_es->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_codigorude_es" class="estudiante_codigorude_es">
<span<?php echo $estudiante->codigorude_es->ViewAttributes() ?>>
<?php echo $estudiante->codigorude_es->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->departamento->Visible) { // departamento ?>
		<td<?php echo $estudiante->departamento->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_departamento" class="estudiante_departamento">
<span<?php echo $estudiante->departamento->ViewAttributes() ?>>
<?php echo $estudiante->departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->municipio->Visible) { // municipio ?>
		<td<?php echo $estudiante->municipio->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_municipio" class="estudiante_municipio">
<span<?php echo $estudiante->municipio->ViewAttributes() ?>>
<?php echo $estudiante->municipio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->provincisa->Visible) { // provincisa ?>
		<td<?php echo $estudiante->provincisa->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_provincisa" class="estudiante_provincisa">
<span<?php echo $estudiante->provincisa->ViewAttributes() ?>>
<?php echo $estudiante->provincisa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->unidadeducativa->Visible) { // unidadeducativa ?>
		<td<?php echo $estudiante->unidadeducativa->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_unidadeducativa" class="estudiante_unidadeducativa">
<span<?php echo $estudiante->unidadeducativa->ViewAttributes() ?>>
<?php echo $estudiante->unidadeducativa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->apellidopaterno->Visible) { // apellidopaterno ?>
		<td<?php echo $estudiante->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_apellidopaterno" class="estudiante_apellidopaterno">
<span<?php echo $estudiante->apellidopaterno->ViewAttributes() ?>>
<?php echo $estudiante->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->apellidomaterno->Visible) { // apellidomaterno ?>
		<td<?php echo $estudiante->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_apellidomaterno" class="estudiante_apellidomaterno">
<span<?php echo $estudiante->apellidomaterno->ViewAttributes() ?>>
<?php echo $estudiante->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->nombres->Visible) { // nombres ?>
		<td<?php echo $estudiante->nombres->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_nombres" class="estudiante_nombres">
<span<?php echo $estudiante->nombres->ViewAttributes() ?>>
<?php echo $estudiante->nombres->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td<?php echo $estudiante->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_nrodiscapacidad" class="estudiante_nrodiscapacidad">
<span<?php echo $estudiante->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $estudiante->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->ci->Visible) { // ci ?>
		<td<?php echo $estudiante->ci->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_ci" class="estudiante_ci">
<span<?php echo $estudiante->ci->ViewAttributes() ?>>
<?php echo $estudiante->ci->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->fechanacimiento->Visible) { // fechanacimiento ?>
		<td<?php echo $estudiante->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_fechanacimiento" class="estudiante_fechanacimiento">
<span<?php echo $estudiante->fechanacimiento->ViewAttributes() ?>>
<?php echo $estudiante->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->sexo->Visible) { // sexo ?>
		<td<?php echo $estudiante->sexo->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_sexo" class="estudiante_sexo">
<span<?php echo $estudiante->sexo->ViewAttributes() ?>>
<?php echo $estudiante->sexo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->curso->Visible) { // curso ?>
		<td<?php echo $estudiante->curso->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_curso" class="estudiante_curso">
<span<?php echo $estudiante->curso->ViewAttributes() ?>>
<?php echo $estudiante->curso->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->discapacidad->Visible) { // discapacidad ?>
		<td<?php echo $estudiante->discapacidad->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_discapacidad" class="estudiante_discapacidad">
<span<?php echo $estudiante->discapacidad->ViewAttributes() ?>>
<?php echo $estudiante->discapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
		<td<?php echo $estudiante->tipodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_tipodiscapacidad" class="estudiante_tipodiscapacidad">
<span<?php echo $estudiante->tipodiscapacidad->ViewAttributes() ?>>
<?php echo $estudiante->tipodiscapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->observaciones->Visible) { // observaciones ?>
		<td<?php echo $estudiante->observaciones->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_observaciones" class="estudiante_observaciones">
<span<?php echo $estudiante->observaciones->ViewAttributes() ?>>
<?php echo $estudiante->observaciones->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($estudiante->fecha->Visible) { // fecha ?>
		<td<?php echo $estudiante->fecha->CellAttributes() ?>>
<span id="el<?php echo $estudiante_delete->RowCnt ?>_estudiante_fecha" class="estudiante_fecha">
<span<?php echo $estudiante->fecha->ViewAttributes() ?>>
<?php echo $estudiante->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$estudiante_delete->Recordset->MoveNext();
}
$estudiante_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $estudiante_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
festudiantedelete.Init();
</script>
<?php
$estudiante_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$estudiante_delete->Page_Terminate();
?>
