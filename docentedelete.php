<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "docenteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$docente_delete = NULL; // Initialize page object first

class cdocente_delete extends cdocente {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'docente';

	// Page object name
	var $PageObjName = 'docente_delete';

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

		// Table object (docente)
		if (!isset($GLOBALS["docente"]) || get_class($GLOBALS["docente"]) == "cdocente") {
			$GLOBALS["docente"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["docente"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'docente', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("docentelist.php"));
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
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->id_departamento->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->celular->SetVisibility();
		$this->materias->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapacidad->SetVisibility();

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
		global $EW_EXPORT, $docente;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($docente);
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
			$this->Page_Terminate("docentelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in docente class, docenteinfo.php

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
				$this->Page_Terminate("docentelist.php"); // Return to list
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
		$this->id_departamento->setDbValue($row['id_departamento']);
		$this->unidadeducativa->setDbValue($row['unidadeducativa']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombres->setDbValue($row['nombres']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->celular->setDbValue($row['celular']);
		$this->materias->setDbValue($row['materias']);
		$this->discapacidad->setDbValue($row['discapacidad']);
		if (array_key_exists('EV__discapacidad', $rs->fields)) {
			$this->discapacidad->VirtualValue = $rs->fields('EV__discapacidad'); // Set up virtual field value
		} else {
			$this->discapacidad->VirtualValue = ""; // Clear value
		}
		$this->tipodiscapacidad->setDbValue($row['tipodiscapacidad']);
		if (array_key_exists('EV__tipodiscapacidad', $rs->fields)) {
			$this->tipodiscapacidad->VirtualValue = $rs->fields('EV__tipodiscapacidad'); // Set up virtual field value
		} else {
			$this->tipodiscapacidad->VirtualValue = ""; // Clear value
		}
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_departamento'] = NULL;
		$row['unidadeducativa'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombres'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['celular'] = NULL;
		$row['materias'] = NULL;
		$row['discapacidad'] = NULL;
		$row['tipodiscapacidad'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_departamento->DbValue = $row['id_departamento'];
		$this->unidadeducativa->DbValue = $row['unidadeducativa'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombres->DbValue = $row['nombres'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->celular->DbValue = $row['celular'];
		$this->materias->DbValue = $row['materias'];
		$this->discapacidad->DbValue = $row['discapacidad'];
		$this->tipodiscapacidad->DbValue = $row['tipodiscapacidad'];
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
		// id_departamento
		// unidadeducativa
		// apellidopaterno
		// apellidomaterno
		// nombres
		// nrodiscapacidad
		// ci
		// fechanacimiento
		// sexo
		// celular
		// materias
		// discapacidad
		// tipodiscapacidad
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_departamento
		if (strval($this->id_departamento->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_departamento->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
		$sWhereWrk = "";
		$this->id_departamento->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_departamento, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_departamento->ViewValue = $this->id_departamento->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_departamento->ViewValue = $this->id_departamento->CurrentValue;
			}
		} else {
			$this->id_departamento->ViewValue = NULL;
		}
		$this->id_departamento->ViewCustomAttributes = "";

		// unidadeducativa
		if (strval($this->unidadeducativa->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->unidadeducativa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `codigo_sie` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->unidadeducativa->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`codigo_sie`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
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
		$this->fechanacimiento->ViewValue = ew_FormatDateTime($this->fechanacimiento->ViewValue, 6);
		$this->fechanacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// materias
		$this->materias->ViewValue = $this->materias->CurrentValue;
		$this->materias->ViewCustomAttributes = "";

		// discapacidad
		if ($this->discapacidad->VirtualValue <> "") {
			$this->discapacidad->ViewValue = $this->discapacidad->VirtualValue;
		} else {
		if (strval($this->discapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
		$sWhereWrk = "";
		$this->discapacidad->LookupFilters = array("dx1" => '`nombre`');
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
		}
		$this->discapacidad->ViewCustomAttributes = "";

		// tipodiscapacidad
		if ($this->tipodiscapacidad->VirtualValue <> "") {
			$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->VirtualValue;
		} else {
		if (strval($this->tipodiscapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipodiscapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiscapacidad`";
		$sWhereWrk = "";
		$this->tipodiscapacidad->LookupFilters = array("dx1" => '`nombre`');
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
		}
		$this->tipodiscapacidad->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_departamento
			$this->id_departamento->LinkCustomAttributes = "";
			$this->id_departamento->HrefValue = "";
			$this->id_departamento->TooltipValue = "";

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

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// materias
			$this->materias->LinkCustomAttributes = "";
			$this->materias->HrefValue = "";
			$this->materias->TooltipValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";
			$this->discapacidad->TooltipValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->LinkCustomAttributes = "";
			$this->tipodiscapacidad->HrefValue = "";
			$this->tipodiscapacidad->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("docentelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($docente_delete)) $docente_delete = new cdocente_delete();

// Page init
$docente_delete->Page_Init();

// Page main
$docente_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$docente_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdocentedelete = new ew_Form("fdocentedelete", "delete");

// Form_CustomValidate event
fdocentedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdocentedelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdocentedelete.Lists["x_id_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fdocentedelete.Lists["x_id_departamento"].Data = "<?php echo $docente_delete->id_departamento->LookupFilterQuery(FALSE, "delete") ?>";
fdocentedelete.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_codigo_sie","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fdocentedelete.Lists["x_unidadeducativa"].Data = "<?php echo $docente_delete->unidadeducativa->LookupFilterQuery(FALSE, "delete") ?>";
fdocentedelete.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdocentedelete.Lists["x_sexo"].Options = <?php echo json_encode($docente_delete->sexo->Options()) ?>;
fdocentedelete.Lists["x_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fdocentedelete.Lists["x_discapacidad"].Data = "<?php echo $docente_delete->discapacidad->LookupFilterQuery(FALSE, "delete") ?>";
fdocentedelete.Lists["x_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
fdocentedelete.Lists["x_tipodiscapacidad"].Data = "<?php echo $docente_delete->tipodiscapacidad->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $docente_delete->ShowPageHeader(); ?>
<?php
$docente_delete->ShowMessage();
?>
<form name="fdocentedelete" id="fdocentedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($docente_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $docente_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="docente">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($docente_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($docente->id->Visible) { // id ?>
		<th class="<?php echo $docente->id->HeaderCellClass() ?>"><span id="elh_docente_id" class="docente_id"><?php echo $docente->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->id_departamento->Visible) { // id_departamento ?>
		<th class="<?php echo $docente->id_departamento->HeaderCellClass() ?>"><span id="elh_docente_id_departamento" class="docente_id_departamento"><?php echo $docente->id_departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->unidadeducativa->Visible) { // unidadeducativa ?>
		<th class="<?php echo $docente->unidadeducativa->HeaderCellClass() ?>"><span id="elh_docente_unidadeducativa" class="docente_unidadeducativa"><?php echo $docente->unidadeducativa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->apellidopaterno->Visible) { // apellidopaterno ?>
		<th class="<?php echo $docente->apellidopaterno->HeaderCellClass() ?>"><span id="elh_docente_apellidopaterno" class="docente_apellidopaterno"><?php echo $docente->apellidopaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->apellidomaterno->Visible) { // apellidomaterno ?>
		<th class="<?php echo $docente->apellidomaterno->HeaderCellClass() ?>"><span id="elh_docente_apellidomaterno" class="docente_apellidomaterno"><?php echo $docente->apellidomaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->nombres->Visible) { // nombres ?>
		<th class="<?php echo $docente->nombres->HeaderCellClass() ?>"><span id="elh_docente_nombres" class="docente_nombres"><?php echo $docente->nombres->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<th class="<?php echo $docente->nrodiscapacidad->HeaderCellClass() ?>"><span id="elh_docente_nrodiscapacidad" class="docente_nrodiscapacidad"><?php echo $docente->nrodiscapacidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->ci->Visible) { // ci ?>
		<th class="<?php echo $docente->ci->HeaderCellClass() ?>"><span id="elh_docente_ci" class="docente_ci"><?php echo $docente->ci->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->fechanacimiento->Visible) { // fechanacimiento ?>
		<th class="<?php echo $docente->fechanacimiento->HeaderCellClass() ?>"><span id="elh_docente_fechanacimiento" class="docente_fechanacimiento"><?php echo $docente->fechanacimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->sexo->Visible) { // sexo ?>
		<th class="<?php echo $docente->sexo->HeaderCellClass() ?>"><span id="elh_docente_sexo" class="docente_sexo"><?php echo $docente->sexo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->celular->Visible) { // celular ?>
		<th class="<?php echo $docente->celular->HeaderCellClass() ?>"><span id="elh_docente_celular" class="docente_celular"><?php echo $docente->celular->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->materias->Visible) { // materias ?>
		<th class="<?php echo $docente->materias->HeaderCellClass() ?>"><span id="elh_docente_materias" class="docente_materias"><?php echo $docente->materias->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->discapacidad->Visible) { // discapacidad ?>
		<th class="<?php echo $docente->discapacidad->HeaderCellClass() ?>"><span id="elh_docente_discapacidad" class="docente_discapacidad"><?php echo $docente->discapacidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($docente->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
		<th class="<?php echo $docente->tipodiscapacidad->HeaderCellClass() ?>"><span id="elh_docente_tipodiscapacidad" class="docente_tipodiscapacidad"><?php echo $docente->tipodiscapacidad->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$docente_delete->RecCnt = 0;
$i = 0;
while (!$docente_delete->Recordset->EOF) {
	$docente_delete->RecCnt++;
	$docente_delete->RowCnt++;

	// Set row properties
	$docente->ResetAttrs();
	$docente->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$docente_delete->LoadRowValues($docente_delete->Recordset);

	// Render row
	$docente_delete->RenderRow();
?>
	<tr<?php echo $docente->RowAttributes() ?>>
<?php if ($docente->id->Visible) { // id ?>
		<td<?php echo $docente->id->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_id" class="docente_id">
<span<?php echo $docente->id->ViewAttributes() ?>>
<?php echo $docente->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->id_departamento->Visible) { // id_departamento ?>
		<td<?php echo $docente->id_departamento->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_id_departamento" class="docente_id_departamento">
<span<?php echo $docente->id_departamento->ViewAttributes() ?>>
<?php echo $docente->id_departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->unidadeducativa->Visible) { // unidadeducativa ?>
		<td<?php echo $docente->unidadeducativa->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_unidadeducativa" class="docente_unidadeducativa">
<span<?php echo $docente->unidadeducativa->ViewAttributes() ?>>
<?php echo $docente->unidadeducativa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->apellidopaterno->Visible) { // apellidopaterno ?>
		<td<?php echo $docente->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_apellidopaterno" class="docente_apellidopaterno">
<span<?php echo $docente->apellidopaterno->ViewAttributes() ?>>
<?php echo $docente->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->apellidomaterno->Visible) { // apellidomaterno ?>
		<td<?php echo $docente->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_apellidomaterno" class="docente_apellidomaterno">
<span<?php echo $docente->apellidomaterno->ViewAttributes() ?>>
<?php echo $docente->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->nombres->Visible) { // nombres ?>
		<td<?php echo $docente->nombres->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_nombres" class="docente_nombres">
<span<?php echo $docente->nombres->ViewAttributes() ?>>
<?php echo $docente->nombres->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td<?php echo $docente->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_nrodiscapacidad" class="docente_nrodiscapacidad">
<span<?php echo $docente->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $docente->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->ci->Visible) { // ci ?>
		<td<?php echo $docente->ci->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_ci" class="docente_ci">
<span<?php echo $docente->ci->ViewAttributes() ?>>
<?php echo $docente->ci->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->fechanacimiento->Visible) { // fechanacimiento ?>
		<td<?php echo $docente->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_fechanacimiento" class="docente_fechanacimiento">
<span<?php echo $docente->fechanacimiento->ViewAttributes() ?>>
<?php echo $docente->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->sexo->Visible) { // sexo ?>
		<td<?php echo $docente->sexo->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_sexo" class="docente_sexo">
<span<?php echo $docente->sexo->ViewAttributes() ?>>
<?php echo $docente->sexo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->celular->Visible) { // celular ?>
		<td<?php echo $docente->celular->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_celular" class="docente_celular">
<span<?php echo $docente->celular->ViewAttributes() ?>>
<?php echo $docente->celular->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->materias->Visible) { // materias ?>
		<td<?php echo $docente->materias->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_materias" class="docente_materias">
<span<?php echo $docente->materias->ViewAttributes() ?>>
<?php echo $docente->materias->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->discapacidad->Visible) { // discapacidad ?>
		<td<?php echo $docente->discapacidad->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_discapacidad" class="docente_discapacidad">
<span<?php echo $docente->discapacidad->ViewAttributes() ?>>
<?php echo $docente->discapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($docente->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
		<td<?php echo $docente->tipodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $docente_delete->RowCnt ?>_docente_tipodiscapacidad" class="docente_tipodiscapacidad">
<span<?php echo $docente->tipodiscapacidad->ViewAttributes() ?>>
<?php echo $docente->tipodiscapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$docente_delete->Recordset->MoveNext();
}
$docente_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $docente_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdocentedelete.Init();
</script>
<?php
$docente_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$docente_delete->Page_Terminate();
?>
