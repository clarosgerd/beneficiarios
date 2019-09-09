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

$docente_add = NULL; // Initialize page object first

class cdocente_add extends cdocente {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'docente';

	// Page object name
	var $PageObjName = 'docente_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
		$this->id_centro->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "docenteview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("docentelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "docentelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "docenteview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->id_departamento->CurrentValue = NULL;
		$this->id_departamento->OldValue = $this->id_departamento->CurrentValue;
		$this->unidadeducativa->CurrentValue = NULL;
		$this->unidadeducativa->OldValue = $this->unidadeducativa->CurrentValue;
		$this->apellidopaterno->CurrentValue = NULL;
		$this->apellidopaterno->OldValue = $this->apellidopaterno->CurrentValue;
		$this->apellidomaterno->CurrentValue = NULL;
		$this->apellidomaterno->OldValue = $this->apellidomaterno->CurrentValue;
		$this->nombres->CurrentValue = NULL;
		$this->nombres->OldValue = $this->nombres->CurrentValue;
		$this->nrodiscapacidad->CurrentValue = NULL;
		$this->nrodiscapacidad->OldValue = $this->nrodiscapacidad->CurrentValue;
		$this->ci->CurrentValue = NULL;
		$this->ci->OldValue = $this->ci->CurrentValue;
		$this->fechanacimiento->CurrentValue = NULL;
		$this->fechanacimiento->OldValue = $this->fechanacimiento->CurrentValue;
		$this->sexo->CurrentValue = NULL;
		$this->sexo->OldValue = $this->sexo->CurrentValue;
		$this->celular->CurrentValue = NULL;
		$this->celular->OldValue = $this->celular->CurrentValue;
		$this->materias->CurrentValue = NULL;
		$this->materias->OldValue = $this->materias->CurrentValue;
		$this->discapacidad->CurrentValue = NULL;
		$this->discapacidad->OldValue = $this->discapacidad->CurrentValue;
		$this->tipodiscapacidad->CurrentValue = NULL;
		$this->tipodiscapacidad->OldValue = $this->tipodiscapacidad->CurrentValue;
		$this->id_centro->CurrentValue = SESSION["centro"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_departamento->FldIsDetailKey) {
			$this->id_departamento->setFormValue($objForm->GetValue("x_id_departamento"));
		}
		if (!$this->unidadeducativa->FldIsDetailKey) {
			$this->unidadeducativa->setFormValue($objForm->GetValue("x_unidadeducativa"));
		}
		if (!$this->apellidopaterno->FldIsDetailKey) {
			$this->apellidopaterno->setFormValue($objForm->GetValue("x_apellidopaterno"));
		}
		if (!$this->apellidomaterno->FldIsDetailKey) {
			$this->apellidomaterno->setFormValue($objForm->GetValue("x_apellidomaterno"));
		}
		if (!$this->nombres->FldIsDetailKey) {
			$this->nombres->setFormValue($objForm->GetValue("x_nombres"));
		}
		if (!$this->nrodiscapacidad->FldIsDetailKey) {
			$this->nrodiscapacidad->setFormValue($objForm->GetValue("x_nrodiscapacidad"));
		}
		if (!$this->ci->FldIsDetailKey) {
			$this->ci->setFormValue($objForm->GetValue("x_ci"));
		}
		if (!$this->fechanacimiento->FldIsDetailKey) {
			$this->fechanacimiento->setFormValue($objForm->GetValue("x_fechanacimiento"));
			$this->fechanacimiento->CurrentValue = ew_UnFormatDateTime($this->fechanacimiento->CurrentValue, 6);
		}
		if (!$this->sexo->FldIsDetailKey) {
			$this->sexo->setFormValue($objForm->GetValue("x_sexo"));
		}
		if (!$this->celular->FldIsDetailKey) {
			$this->celular->setFormValue($objForm->GetValue("x_celular"));
		}
		if (!$this->materias->FldIsDetailKey) {
			$this->materias->setFormValue($objForm->GetValue("x_materias"));
		}
		if (!$this->discapacidad->FldIsDetailKey) {
			$this->discapacidad->setFormValue($objForm->GetValue("x_discapacidad"));
		}
		if (!$this->tipodiscapacidad->FldIsDetailKey) {
			$this->tipodiscapacidad->setFormValue($objForm->GetValue("x_tipodiscapacidad"));
		}
		if (!$this->id_centro->FldIsDetailKey) {
			$this->id_centro->setFormValue($objForm->GetValue("x_id_centro"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_departamento->CurrentValue = $this->id_departamento->FormValue;
		$this->unidadeducativa->CurrentValue = $this->unidadeducativa->FormValue;
		$this->apellidopaterno->CurrentValue = $this->apellidopaterno->FormValue;
		$this->apellidomaterno->CurrentValue = $this->apellidomaterno->FormValue;
		$this->nombres->CurrentValue = $this->nombres->FormValue;
		$this->nrodiscapacidad->CurrentValue = $this->nrodiscapacidad->FormValue;
		$this->ci->CurrentValue = $this->ci->FormValue;
		$this->fechanacimiento->CurrentValue = $this->fechanacimiento->FormValue;
		$this->fechanacimiento->CurrentValue = ew_UnFormatDateTime($this->fechanacimiento->CurrentValue, 6);
		$this->sexo->CurrentValue = $this->sexo->FormValue;
		$this->celular->CurrentValue = $this->celular->FormValue;
		$this->materias->CurrentValue = $this->materias->FormValue;
		$this->discapacidad->CurrentValue = $this->discapacidad->FormValue;
		$this->tipodiscapacidad->CurrentValue = $this->tipodiscapacidad->FormValue;
		$this->id_centro->CurrentValue = $this->id_centro->FormValue;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['id_departamento'] = $this->id_departamento->CurrentValue;
		$row['unidadeducativa'] = $this->unidadeducativa->CurrentValue;
		$row['apellidopaterno'] = $this->apellidopaterno->CurrentValue;
		$row['apellidomaterno'] = $this->apellidomaterno->CurrentValue;
		$row['nombres'] = $this->nombres->CurrentValue;
		$row['nrodiscapacidad'] = $this->nrodiscapacidad->CurrentValue;
		$row['ci'] = $this->ci->CurrentValue;
		$row['fechanacimiento'] = $this->fechanacimiento->CurrentValue;
		$row['sexo'] = $this->sexo->CurrentValue;
		$row['celular'] = $this->celular->CurrentValue;
		$row['materias'] = $this->materias->CurrentValue;
		$row['discapacidad'] = $this->discapacidad->CurrentValue;
		$row['tipodiscapacidad'] = $this->tipodiscapacidad->CurrentValue;
		$row['id_centro'] = $this->id_centro->CurrentValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
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

		// id_centro
		$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
		$this->id_centro->ViewCustomAttributes = "";

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

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";
			$this->id_centro->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_departamento
			$this->id_departamento->EditAttrs["class"] = "form-control";
			$this->id_departamento->EditCustomAttributes = "";
			if (trim(strval($this->id_departamento->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_departamento->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamento`";
			$sWhereWrk = "";
			$this->id_departamento->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_departamento, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_departamento->EditValue = $arwrk;

			// unidadeducativa
			$this->unidadeducativa->EditCustomAttributes = "";
			if (trim(strval($this->unidadeducativa->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->unidadeducativa->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `codigo_sie` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `unidadeducativa`";
			$sWhereWrk = "";
			$this->unidadeducativa->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`codigo_sie`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->unidadeducativa->ViewValue = $this->unidadeducativa->DisplayValue($arwrk);
			} else {
				$this->unidadeducativa->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->unidadeducativa->EditValue = $arwrk;

			// apellidopaterno
			$this->apellidopaterno->EditAttrs["class"] = "form-control";
			$this->apellidopaterno->EditCustomAttributes = "";
			$this->apellidopaterno->EditValue = ew_HtmlEncode($this->apellidopaterno->CurrentValue);
			$this->apellidopaterno->PlaceHolder = ew_RemoveHtml($this->apellidopaterno->FldCaption());

			// apellidomaterno
			$this->apellidomaterno->EditAttrs["class"] = "form-control";
			$this->apellidomaterno->EditCustomAttributes = "";
			$this->apellidomaterno->EditValue = ew_HtmlEncode($this->apellidomaterno->CurrentValue);
			$this->apellidomaterno->PlaceHolder = ew_RemoveHtml($this->apellidomaterno->FldCaption());

			// nombres
			$this->nombres->EditAttrs["class"] = "form-control";
			$this->nombres->EditCustomAttributes = "";
			$this->nombres->EditValue = ew_HtmlEncode($this->nombres->CurrentValue);
			$this->nombres->PlaceHolder = ew_RemoveHtml($this->nombres->FldCaption());

			// nrodiscapacidad
			$this->nrodiscapacidad->EditAttrs["class"] = "form-control";
			$this->nrodiscapacidad->EditCustomAttributes = "";
			$this->nrodiscapacidad->EditValue = ew_HtmlEncode($this->nrodiscapacidad->CurrentValue);
			$this->nrodiscapacidad->PlaceHolder = ew_RemoveHtml($this->nrodiscapacidad->FldCaption());

			// ci
			$this->ci->EditAttrs["class"] = "form-control";
			$this->ci->EditCustomAttributes = "";
			$this->ci->EditValue = ew_HtmlEncode($this->ci->CurrentValue);
			$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

			// fechanacimiento
			$this->fechanacimiento->EditAttrs["class"] = "form-control";
			$this->fechanacimiento->EditCustomAttributes = "";
			$this->fechanacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechanacimiento->CurrentValue, 6));
			$this->fechanacimiento->PlaceHolder = ew_RemoveHtml($this->fechanacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// celular
			$this->celular->EditAttrs["class"] = "form-control";
			$this->celular->EditCustomAttributes = "";
			$this->celular->EditValue = ew_HtmlEncode($this->celular->CurrentValue);
			$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

			// materias
			$this->materias->EditAttrs["class"] = "form-control";
			$this->materias->EditCustomAttributes = "";
			$this->materias->EditValue = ew_HtmlEncode($this->materias->CurrentValue);
			$this->materias->PlaceHolder = ew_RemoveHtml($this->materias->FldCaption());

			// discapacidad
			$this->discapacidad->EditCustomAttributes = "";
			if (trim(strval($this->discapacidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `discapacidad`";
			$sWhereWrk = "";
			$this->discapacidad->LookupFilters = array("dx1" => '`nombre`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->discapacidad->ViewValue = $this->discapacidad->DisplayValue($arwrk);
			} else {
				$this->discapacidad->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->discapacidad->EditValue = $arwrk;

			// tipodiscapacidad
			$this->tipodiscapacidad->EditCustomAttributes = "";
			if (trim(strval($this->tipodiscapacidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipodiscapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipodiscapacidad`";
			$sWhereWrk = "";
			$this->tipodiscapacidad->LookupFilters = array("dx1" => '`nombre`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->DisplayValue($arwrk);
			} else {
				$this->tipodiscapacidad->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->tipodiscapacidad->EditValue = $arwrk;

			// id_centro
			$this->id_centro->EditAttrs["class"] = "form-control";
			$this->id_centro->EditCustomAttributes = "";
			$this->id_centro->EditValue = ew_HtmlEncode($this->id_centro->CurrentValue);
			$this->id_centro->PlaceHolder = ew_RemoveHtml($this->id_centro->FldCaption());

			// Add refer script
			// id_departamento

			$this->id_departamento->LinkCustomAttributes = "";
			$this->id_departamento->HrefValue = "";

			// unidadeducativa
			$this->unidadeducativa->LinkCustomAttributes = "";
			$this->unidadeducativa->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";

			// nombres
			$this->nombres->LinkCustomAttributes = "";
			$this->nombres->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->LinkCustomAttributes = "";
			$this->fechanacimiento->HrefValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";

			// materias
			$this->materias->LinkCustomAttributes = "";
			$this->materias->HrefValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->LinkCustomAttributes = "";
			$this->tipodiscapacidad->HrefValue = "";

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->id_departamento->FldIsDetailKey && !is_null($this->id_departamento->FormValue) && $this->id_departamento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_departamento->FldCaption(), $this->id_departamento->ReqErrMsg));
		}
		if (!$this->unidadeducativa->FldIsDetailKey && !is_null($this->unidadeducativa->FormValue) && $this->unidadeducativa->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unidadeducativa->FldCaption(), $this->unidadeducativa->ReqErrMsg));
		}
		if (!$this->apellidopaterno->FldIsDetailKey && !is_null($this->apellidopaterno->FormValue) && $this->apellidopaterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->apellidopaterno->FldCaption(), $this->apellidopaterno->ReqErrMsg));
		}
		if (!$this->apellidomaterno->FldIsDetailKey && !is_null($this->apellidomaterno->FormValue) && $this->apellidomaterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->apellidomaterno->FldCaption(), $this->apellidomaterno->ReqErrMsg));
		}
		if (!$this->nombres->FldIsDetailKey && !is_null($this->nombres->FormValue) && $this->nombres->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombres->FldCaption(), $this->nombres->ReqErrMsg));
		}
		if (!$this->fechanacimiento->FldIsDetailKey && !is_null($this->fechanacimiento->FormValue) && $this->fechanacimiento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fechanacimiento->FldCaption(), $this->fechanacimiento->ReqErrMsg));
		}
		if (!ew_CheckUSDate($this->fechanacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechanacimiento->FldErrMsg());
		}
		if (!$this->sexo->FldIsDetailKey && !is_null($this->sexo->FormValue) && $this->sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sexo->FldCaption(), $this->sexo->ReqErrMsg));
		}
		if (!$this->discapacidad->FldIsDetailKey && !is_null($this->discapacidad->FormValue) && $this->discapacidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->discapacidad->FldCaption(), $this->discapacidad->ReqErrMsg));
		}
		if (!$this->tipodiscapacidad->FldIsDetailKey && !is_null($this->tipodiscapacidad->FormValue) && $this->tipodiscapacidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipodiscapacidad->FldCaption(), $this->tipodiscapacidad->ReqErrMsg));
		}
		if (!$this->id_centro->FldIsDetailKey && !is_null($this->id_centro->FormValue) && $this->id_centro->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_centro->FldCaption(), $this->id_centro->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_centro->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_centro->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// id_departamento
		$this->id_departamento->SetDbValueDef($rsnew, $this->id_departamento->CurrentValue, 0, FALSE);

		// unidadeducativa
		$this->unidadeducativa->SetDbValueDef($rsnew, $this->unidadeducativa->CurrentValue, 0, FALSE);

		// apellidopaterno
		$this->apellidopaterno->SetDbValueDef($rsnew, $this->apellidopaterno->CurrentValue, "", FALSE);

		// apellidomaterno
		$this->apellidomaterno->SetDbValueDef($rsnew, $this->apellidomaterno->CurrentValue, "", FALSE);

		// nombres
		$this->nombres->SetDbValueDef($rsnew, $this->nombres->CurrentValue, "", FALSE);

		// nrodiscapacidad
		$this->nrodiscapacidad->SetDbValueDef($rsnew, $this->nrodiscapacidad->CurrentValue, NULL, FALSE);

		// ci
		$this->ci->SetDbValueDef($rsnew, $this->ci->CurrentValue, NULL, FALSE);

		// fechanacimiento
		$this->fechanacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechanacimiento->CurrentValue, 6), ew_CurrentDate(), FALSE);

		// sexo
		$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, 0, FALSE);

		// celular
		$this->celular->SetDbValueDef($rsnew, $this->celular->CurrentValue, NULL, FALSE);

		// materias
		$this->materias->SetDbValueDef($rsnew, $this->materias->CurrentValue, NULL, FALSE);

		// discapacidad
		$this->discapacidad->SetDbValueDef($rsnew, $this->discapacidad->CurrentValue, 0, FALSE);

		// tipodiscapacidad
		$this->tipodiscapacidad->SetDbValueDef($rsnew, $this->tipodiscapacidad->CurrentValue, 0, FALSE);

		// id_centro
		$this->id_centro->SetDbValueDef($rsnew, $this->id_centro->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("docentelist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_departamento":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_departamento, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_unidadeducativa":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `codigo_sie` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`codigo_sie`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_discapacidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_tipodiscapacidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiscapacidad`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($docente_add)) $docente_add = new cdocente_add();

// Page init
$docente_add->Page_Init();

// Page main
$docente_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$docente_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fdocenteadd = new ew_Form("fdocenteadd", "add");

// Validate form
fdocenteadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_id_departamento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->id_departamento->FldCaption(), $docente->id_departamento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_unidadeducativa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->unidadeducativa->FldCaption(), $docente->unidadeducativa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidopaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->apellidopaterno->FldCaption(), $docente->apellidopaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidomaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->apellidomaterno->FldCaption(), $docente->apellidomaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->nombres->FldCaption(), $docente->nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechanacimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->fechanacimiento->FldCaption(), $docente->fechanacimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechanacimiento");
			if (elm && !ew_CheckUSDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($docente->fechanacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->sexo->FldCaption(), $docente->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_discapacidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->discapacidad->FldCaption(), $docente->discapacidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipodiscapacidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->tipodiscapacidad->FldCaption(), $docente->tipodiscapacidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $docente->id_centro->FldCaption(), $docente->id_centro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($docente->id_centro->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fdocenteadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdocenteadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdocenteadd.Lists["x_id_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fdocenteadd.Lists["x_id_departamento"].Data = "<?php echo $docente_add->id_departamento->LookupFilterQuery(FALSE, "add") ?>";
fdocenteadd.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_codigo_sie","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fdocenteadd.Lists["x_unidadeducativa"].Data = "<?php echo $docente_add->unidadeducativa->LookupFilterQuery(FALSE, "add") ?>";
fdocenteadd.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdocenteadd.Lists["x_sexo"].Options = <?php echo json_encode($docente_add->sexo->Options()) ?>;
fdocenteadd.Lists["x_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fdocenteadd.Lists["x_discapacidad"].Data = "<?php echo $docente_add->discapacidad->LookupFilterQuery(FALSE, "add") ?>";
fdocenteadd.Lists["x_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
fdocenteadd.Lists["x_tipodiscapacidad"].Data = "<?php echo $docente_add->tipodiscapacidad->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $docente_add->ShowPageHeader(); ?>
<?php
$docente_add->ShowMessage();
?>
<form name="fdocenteadd" id="fdocenteadd" class="<?php echo $docente_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($docente_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $docente_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="docente">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($docente_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($docente->id_departamento->Visible) { // id_departamento ?>
	<div id="r_id_departamento" class="form-group">
		<label id="elh_docente_id_departamento" for="x_id_departamento" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->id_departamento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->id_departamento->CellAttributes() ?>>
<span id="el_docente_id_departamento">
<select data-table="docente" data-field="x_id_departamento" data-value-separator="<?php echo $docente->id_departamento->DisplayValueSeparatorAttribute() ?>" id="x_id_departamento" name="x_id_departamento"<?php echo $docente->id_departamento->EditAttributes() ?>>
<?php echo $docente->id_departamento->SelectOptionListHtml("x_id_departamento") ?>
</select>
</span>
<?php echo $docente->id_departamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->unidadeducativa->Visible) { // unidadeducativa ?>
	<div id="r_unidadeducativa" class="form-group">
		<label id="elh_docente_unidadeducativa" for="x_unidadeducativa" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->unidadeducativa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->unidadeducativa->CellAttributes() ?>>
<span id="el_docente_unidadeducativa">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_unidadeducativa"><?php echo (strval($docente->unidadeducativa->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $docente->unidadeducativa->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($docente->unidadeducativa->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_unidadeducativa',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($docente->unidadeducativa->ReadOnly || $docente->unidadeducativa->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="docente" data-field="x_unidadeducativa" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $docente->unidadeducativa->DisplayValueSeparatorAttribute() ?>" name="x_unidadeducativa" id="x_unidadeducativa" value="<?php echo $docente->unidadeducativa->CurrentValue ?>"<?php echo $docente->unidadeducativa->EditAttributes() ?>>
</span>
<?php echo $docente->unidadeducativa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="r_apellidopaterno" class="form-group">
		<label id="elh_docente_apellidopaterno" for="x_apellidopaterno" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->apellidopaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->apellidopaterno->CellAttributes() ?>>
<span id="el_docente_apellidopaterno">
<input type="text" data-table="docente" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $docente->apellidopaterno->EditValue ?>"<?php echo $docente->apellidopaterno->EditAttributes() ?>>
</span>
<?php echo $docente->apellidopaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="r_apellidomaterno" class="form-group">
		<label id="elh_docente_apellidomaterno" for="x_apellidomaterno" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->apellidomaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->apellidomaterno->CellAttributes() ?>>
<span id="el_docente_apellidomaterno">
<input type="text" data-table="docente" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $docente->apellidomaterno->EditValue ?>"<?php echo $docente->apellidomaterno->EditAttributes() ?>>
</span>
<?php echo $docente->apellidomaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->nombres->Visible) { // nombres ?>
	<div id="r_nombres" class="form-group">
		<label id="elh_docente_nombres" for="x_nombres" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->nombres->CellAttributes() ?>>
<span id="el_docente_nombres">
<input type="text" data-table="docente" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->nombres->getPlaceHolder()) ?>" value="<?php echo $docente->nombres->EditValue ?>"<?php echo $docente->nombres->EditAttributes() ?>>
</span>
<?php echo $docente->nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="r_nrodiscapacidad" class="form-group">
		<label id="elh_docente_nrodiscapacidad" for="x_nrodiscapacidad" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->nrodiscapacidad->FldCaption() ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->nrodiscapacidad->CellAttributes() ?>>
<span id="el_docente_nrodiscapacidad">
<input type="text" data-table="docente" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($docente->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $docente->nrodiscapacidad->EditValue ?>"<?php echo $docente->nrodiscapacidad->EditAttributes() ?>>
</span>
<?php echo $docente->nrodiscapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->ci->Visible) { // ci ?>
	<div id="r_ci" class="form-group">
		<label id="elh_docente_ci" for="x_ci" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->ci->FldCaption() ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->ci->CellAttributes() ?>>
<span id="el_docente_ci">
<input type="text" data-table="docente" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($docente->ci->getPlaceHolder()) ?>" value="<?php echo $docente->ci->EditValue ?>"<?php echo $docente->ci->EditAttributes() ?>>
</span>
<?php echo $docente->ci->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->fechanacimiento->Visible) { // fechanacimiento ?>
	<div id="r_fechanacimiento" class="form-group">
		<label id="elh_docente_fechanacimiento" for="x_fechanacimiento" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->fechanacimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->fechanacimiento->CellAttributes() ?>>
<span id="el_docente_fechanacimiento">
<input type="text" data-table="docente" data-field="x_fechanacimiento" data-format="6" name="x_fechanacimiento" id="x_fechanacimiento" placeholder="<?php echo ew_HtmlEncode($docente->fechanacimiento->getPlaceHolder()) ?>" value="<?php echo $docente->fechanacimiento->EditValue ?>"<?php echo $docente->fechanacimiento->EditAttributes() ?>>
<?php if (!$docente->fechanacimiento->ReadOnly && !$docente->fechanacimiento->Disabled && !isset($docente->fechanacimiento->EditAttrs["readonly"]) && !isset($docente->fechanacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdocenteadd", "x_fechanacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":6});
</script>
<?php } ?>
</span>
<?php echo $docente->fechanacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->sexo->Visible) { // sexo ?>
	<div id="r_sexo" class="form-group">
		<label id="elh_docente_sexo" for="x_sexo" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->sexo->CellAttributes() ?>>
<span id="el_docente_sexo">
<select data-table="docente" data-field="x_sexo" data-value-separator="<?php echo $docente->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $docente->sexo->EditAttributes() ?>>
<?php echo $docente->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</span>
<?php echo $docente->sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->celular->Visible) { // celular ?>
	<div id="r_celular" class="form-group">
		<label id="elh_docente_celular" for="x_celular" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->celular->FldCaption() ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->celular->CellAttributes() ?>>
<span id="el_docente_celular">
<input type="text" data-table="docente" data-field="x_celular" name="x_celular" id="x_celular" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->celular->getPlaceHolder()) ?>" value="<?php echo $docente->celular->EditValue ?>"<?php echo $docente->celular->EditAttributes() ?>>
</span>
<?php echo $docente->celular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->materias->Visible) { // materias ?>
	<div id="r_materias" class="form-group">
		<label id="elh_docente_materias" for="x_materias" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->materias->FldCaption() ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->materias->CellAttributes() ?>>
<span id="el_docente_materias">
<input type="text" data-table="docente" data-field="x_materias" name="x_materias" id="x_materias" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->materias->getPlaceHolder()) ?>" value="<?php echo $docente->materias->EditValue ?>"<?php echo $docente->materias->EditAttributes() ?>>
</span>
<?php echo $docente->materias->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->discapacidad->Visible) { // discapacidad ?>
	<div id="r_discapacidad" class="form-group">
		<label id="elh_docente_discapacidad" for="x_discapacidad" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->discapacidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->discapacidad->CellAttributes() ?>>
<span id="el_docente_discapacidad">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_discapacidad"><?php echo (strval($docente->discapacidad->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $docente->discapacidad->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($docente->discapacidad->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_discapacidad',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($docente->discapacidad->ReadOnly || $docente->discapacidad->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="docente" data-field="x_discapacidad" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $docente->discapacidad->DisplayValueSeparatorAttribute() ?>" name="x_discapacidad" id="x_discapacidad" value="<?php echo $docente->discapacidad->CurrentValue ?>"<?php echo $docente->discapacidad->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "discapacidad") && !$docente->discapacidad->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $docente->discapacidad->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_discapacidad',url:'discapacidadaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_discapacidad"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $docente->discapacidad->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $docente->discapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
	<div id="r_tipodiscapacidad" class="form-group">
		<label id="elh_docente_tipodiscapacidad" for="x_tipodiscapacidad" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->tipodiscapacidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->tipodiscapacidad->CellAttributes() ?>>
<span id="el_docente_tipodiscapacidad">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_tipodiscapacidad"><?php echo (strval($docente->tipodiscapacidad->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $docente->tipodiscapacidad->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($docente->tipodiscapacidad->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_tipodiscapacidad',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($docente->tipodiscapacidad->ReadOnly || $docente->tipodiscapacidad->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="docente" data-field="x_tipodiscapacidad" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $docente->tipodiscapacidad->DisplayValueSeparatorAttribute() ?>" name="x_tipodiscapacidad" id="x_tipodiscapacidad" value="<?php echo $docente->tipodiscapacidad->CurrentValue ?>"<?php echo $docente->tipodiscapacidad->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "tipodiscapacidad") && !$docente->tipodiscapacidad->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $docente->tipodiscapacidad->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_tipodiscapacidad',url:'tipodiscapacidadaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_tipodiscapacidad"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $docente->tipodiscapacidad->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $docente->tipodiscapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($docente->id_centro->Visible) { // id_centro ?>
	<div id="r_id_centro" class="form-group">
		<label id="elh_docente_id_centro" for="x_id_centro" class="<?php echo $docente_add->LeftColumnClass ?>"><?php echo $docente->id_centro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $docente_add->RightColumnClass ?>"><div<?php echo $docente->id_centro->CellAttributes() ?>>
<span id="el_docente_id_centro">
<input type="text" data-table="docente" data-field="x_id_centro" name="x_id_centro" id="x_id_centro" size="30" placeholder="<?php echo ew_HtmlEncode($docente->id_centro->getPlaceHolder()) ?>" value="<?php echo $docente->id_centro->EditValue ?>"<?php echo $docente->id_centro->EditAttributes() ?>>
</span>
<?php echo $docente->id_centro->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$docente_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $docente_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $docente_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdocenteadd.Init();
</script>
<?php
$docente_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$docente_add->Page_Terminate();
?>
