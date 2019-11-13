<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "apoderadoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$apoderado_edit = NULL; // Initialize page object first

class capoderado_edit extends capoderado {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'apoderado';

	// Page object name
	var $PageObjName = 'apoderado_edit';

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

		// Table object (apoderado)
		if (!isset($GLOBALS["apoderado"]) || get_class($GLOBALS["apoderado"]) == "capoderado") {
			$GLOBALS["apoderado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["apoderado"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'apoderado', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("apoderadolist.php"));
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
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->parentesco->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->direccion->SetVisibility();
		$this->celular->SetVisibility();
		$this->ocupacion->SetVisibility();
		$this->observaciones->SetVisibility();

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
		global $EW_EXPORT, $apoderado;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($apoderado);
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
					if ($pageName == "apoderadoview.php")
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_id")) {
				$this->id->setFormValue($objForm->GetValue("x_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id"])) {
				$this->id->setQueryStringValue($_GET["id"]);
				$loadByQuery = TRUE;
			} else {
				$this->id->CurrentValue = NULL;
			}
		}

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("apoderadolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "apoderadolist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->parentesco->FldIsDetailKey) {
			$this->parentesco->setFormValue($objForm->GetValue("x_parentesco"));
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
		if (!$this->ci->FldIsDetailKey) {
			$this->ci->setFormValue($objForm->GetValue("x_ci"));
		}
		if (!$this->fechanacimiento->FldIsDetailKey) {
			$this->fechanacimiento->setFormValue($objForm->GetValue("x_fechanacimiento"));
			$this->fechanacimiento->CurrentValue = ew_UnFormatDateTime($this->fechanacimiento->CurrentValue, 0);
		}
		if (!$this->sexo->FldIsDetailKey) {
			$this->sexo->setFormValue($objForm->GetValue("x_sexo"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->celular->FldIsDetailKey) {
			$this->celular->setFormValue($objForm->GetValue("x_celular"));
		}
		if (!$this->ocupacion->FldIsDetailKey) {
			$this->ocupacion->setFormValue($objForm->GetValue("x_ocupacion"));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->parentesco->CurrentValue = $this->parentesco->FormValue;
		$this->apellidopaterno->CurrentValue = $this->apellidopaterno->FormValue;
		$this->apellidomaterno->CurrentValue = $this->apellidomaterno->FormValue;
		$this->nombres->CurrentValue = $this->nombres->FormValue;
		$this->ci->CurrentValue = $this->ci->FormValue;
		$this->fechanacimiento->CurrentValue = $this->fechanacimiento->FormValue;
		$this->fechanacimiento->CurrentValue = ew_UnFormatDateTime($this->fechanacimiento->CurrentValue, 0);
		$this->sexo->CurrentValue = $this->sexo->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->celular->CurrentValue = $this->celular->FormValue;
		$this->ocupacion->CurrentValue = $this->ocupacion->FormValue;
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
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
		$this->parentesco->setDbValue($row['parentesco']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombres->setDbValue($row['nombres']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->direccion->setDbValue($row['direccion']);
		$this->celular->setDbValue($row['celular']);
		$this->ocupacion->setDbValue($row['ocupacion']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['parentesco'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombres'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['direccion'] = NULL;
		$row['celular'] = NULL;
		$row['ocupacion'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->parentesco->DbValue = $row['parentesco'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombres->DbValue = $row['nombres'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->direccion->DbValue = $row['direccion'];
		$this->celular->DbValue = $row['celular'];
		$this->ocupacion->DbValue = $row['ocupacion'];
		$this->observaciones->DbValue = $row['observaciones'];
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
		// parentesco
		// apellidopaterno
		// apellidomaterno
		// nombres
		// ci
		// fechanacimiento
		// sexo
		// direccion
		// celular
		// ocupacion
		// observaciones
		// id_centro

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// ocupacion
		$this->ocupacion->ViewValue = $this->ocupacion->CurrentValue;
		$this->ocupacion->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

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

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// ocupacion
			$this->ocupacion->LinkCustomAttributes = "";
			$this->ocupacion->HrefValue = "";
			$this->ocupacion->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// parentesco
			$this->parentesco->EditAttrs["class"] = "form-control";
			$this->parentesco->EditCustomAttributes = "";
			$this->parentesco->EditValue = ew_HtmlEncode($this->parentesco->CurrentValue);
			$this->parentesco->PlaceHolder = ew_RemoveHtml($this->parentesco->FldCaption());

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

			// ci
			$this->ci->EditAttrs["class"] = "form-control";
			$this->ci->EditCustomAttributes = "";
			$this->ci->EditValue = ew_HtmlEncode($this->ci->CurrentValue);
			$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

			// fechanacimiento
			$this->fechanacimiento->EditAttrs["class"] = "form-control";
			$this->fechanacimiento->EditCustomAttributes = "";
			$this->fechanacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechanacimiento->CurrentValue, 8));
			$this->fechanacimiento->PlaceHolder = ew_RemoveHtml($this->fechanacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// celular
			$this->celular->EditAttrs["class"] = "form-control";
			$this->celular->EditCustomAttributes = "";
			$this->celular->EditValue = ew_HtmlEncode($this->celular->CurrentValue);
			$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

			// ocupacion
			$this->ocupacion->EditAttrs["class"] = "form-control";
			$this->ocupacion->EditCustomAttributes = "";
			$this->ocupacion->EditValue = ew_HtmlEncode($this->ocupacion->CurrentValue);
			$this->ocupacion->PlaceHolder = ew_RemoveHtml($this->ocupacion->FldCaption());

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// parentesco
			$this->parentesco->LinkCustomAttributes = "";
			$this->parentesco->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";

			// nombres
			$this->nombres->LinkCustomAttributes = "";
			$this->nombres->HrefValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->LinkCustomAttributes = "";
			$this->fechanacimiento->HrefValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";

			// ocupacion
			$this->ocupacion->LinkCustomAttributes = "";
			$this->ocupacion->HrefValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
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
		if (!$this->parentesco->FldIsDetailKey && !is_null($this->parentesco->FormValue) && $this->parentesco->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->parentesco->FldCaption(), $this->parentesco->ReqErrMsg));
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
		if (!$this->ci->FldIsDetailKey && !is_null($this->ci->FormValue) && $this->ci->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ci->FldCaption(), $this->ci->ReqErrMsg));
		}
		if (!$this->fechanacimiento->FldIsDetailKey && !is_null($this->fechanacimiento->FormValue) && $this->fechanacimiento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fechanacimiento->FldCaption(), $this->fechanacimiento->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->fechanacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechanacimiento->FldErrMsg());
		}
		if (!$this->sexo->FldIsDetailKey && !is_null($this->sexo->FormValue) && $this->sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sexo->FldCaption(), $this->sexo->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->direccion->FormValue)) {
			ew_AddMessage($gsFormError, $this->direccion->FldErrMsg());
		}
		if (!ew_CheckInteger($this->celular->FormValue)) {
			ew_AddMessage($gsFormError, $this->celular->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// parentesco
			$this->parentesco->SetDbValueDef($rsnew, $this->parentesco->CurrentValue, NULL, $this->parentesco->ReadOnly);

			// apellidopaterno
			$this->apellidopaterno->SetDbValueDef($rsnew, $this->apellidopaterno->CurrentValue, "", $this->apellidopaterno->ReadOnly);

			// apellidomaterno
			$this->apellidomaterno->SetDbValueDef($rsnew, $this->apellidomaterno->CurrentValue, "", $this->apellidomaterno->ReadOnly);

			// nombres
			$this->nombres->SetDbValueDef($rsnew, $this->nombres->CurrentValue, "", $this->nombres->ReadOnly);

			// ci
			$this->ci->SetDbValueDef($rsnew, $this->ci->CurrentValue, "", $this->ci->ReadOnly);

			// fechanacimiento
			$this->fechanacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechanacimiento->CurrentValue, 0), ew_CurrentDate(), $this->fechanacimiento->ReadOnly);

			// sexo
			$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, 0, $this->sexo->ReadOnly);

			// direccion
			$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, $this->direccion->ReadOnly);

			// celular
			$this->celular->SetDbValueDef($rsnew, $this->celular->CurrentValue, NULL, $this->celular->ReadOnly);

			// ocupacion
			$this->ocupacion->SetDbValueDef($rsnew, $this->ocupacion->CurrentValue, NULL, $this->ocupacion->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("apoderadolist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($apoderado_edit)) $apoderado_edit = new capoderado_edit();

// Page init
$apoderado_edit->Page_Init();

// Page main
$apoderado_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$apoderado_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fapoderadoedit = new ew_Form("fapoderadoedit", "edit");

// Validate form
fapoderadoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_parentesco");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $apoderado->parentesco->FldCaption(), $apoderado->parentesco->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidopaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $apoderado->apellidopaterno->FldCaption(), $apoderado->apellidopaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidomaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $apoderado->apellidomaterno->FldCaption(), $apoderado->apellidomaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $apoderado->nombres->FldCaption(), $apoderado->nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ci");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $apoderado->ci->FldCaption(), $apoderado->ci->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechanacimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $apoderado->fechanacimiento->FldCaption(), $apoderado->fechanacimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechanacimiento");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($apoderado->fechanacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $apoderado->sexo->FldCaption(), $apoderado->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($apoderado->direccion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_celular");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($apoderado->celular->FldErrMsg()) ?>");

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
fapoderadoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fapoderadoedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fapoderadoedit.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fapoderadoedit.Lists["x_sexo"].Options = <?php echo json_encode($apoderado_edit->sexo->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $apoderado_edit->ShowPageHeader(); ?>
<?php
$apoderado_edit->ShowMessage();
?>
<form name="fapoderadoedit" id="fapoderadoedit" class="<?php echo $apoderado_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($apoderado_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $apoderado_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="apoderado">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($apoderado_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($apoderado->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_apoderado_id" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->id->FldCaption() ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->id->CellAttributes() ?>>
<span id="el_apoderado_id">
<span<?php echo $apoderado->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $apoderado->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="apoderado" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($apoderado->id->CurrentValue) ?>">
<?php echo $apoderado->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->parentesco->Visible) { // parentesco ?>
	<div id="r_parentesco" class="form-group">
		<label id="elh_apoderado_parentesco" for="x_parentesco" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->parentesco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->parentesco->CellAttributes() ?>>
<span id="el_apoderado_parentesco">
<input type="text" data-table="apoderado" data-field="x_parentesco" name="x_parentesco" id="x_parentesco" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($apoderado->parentesco->getPlaceHolder()) ?>" value="<?php echo $apoderado->parentesco->EditValue ?>"<?php echo $apoderado->parentesco->EditAttributes() ?>>
</span>
<?php echo $apoderado->parentesco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="r_apellidopaterno" class="form-group">
		<label id="elh_apoderado_apellidopaterno" for="x_apellidopaterno" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->apellidopaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->apellidopaterno->CellAttributes() ?>>
<span id="el_apoderado_apellidopaterno">
<input type="text" data-table="apoderado" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($apoderado->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $apoderado->apellidopaterno->EditValue ?>"<?php echo $apoderado->apellidopaterno->EditAttributes() ?>>
</span>
<?php echo $apoderado->apellidopaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="r_apellidomaterno" class="form-group">
		<label id="elh_apoderado_apellidomaterno" for="x_apellidomaterno" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->apellidomaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->apellidomaterno->CellAttributes() ?>>
<span id="el_apoderado_apellidomaterno">
<input type="text" data-table="apoderado" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($apoderado->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $apoderado->apellidomaterno->EditValue ?>"<?php echo $apoderado->apellidomaterno->EditAttributes() ?>>
</span>
<?php echo $apoderado->apellidomaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->nombres->Visible) { // nombres ?>
	<div id="r_nombres" class="form-group">
		<label id="elh_apoderado_nombres" for="x_nombres" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->nombres->CellAttributes() ?>>
<span id="el_apoderado_nombres">
<input type="text" data-table="apoderado" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($apoderado->nombres->getPlaceHolder()) ?>" value="<?php echo $apoderado->nombres->EditValue ?>"<?php echo $apoderado->nombres->EditAttributes() ?>>
</span>
<?php echo $apoderado->nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->ci->Visible) { // ci ?>
	<div id="r_ci" class="form-group">
		<label id="elh_apoderado_ci" for="x_ci" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->ci->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->ci->CellAttributes() ?>>
<span id="el_apoderado_ci">
<input type="text" data-table="apoderado" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($apoderado->ci->getPlaceHolder()) ?>" value="<?php echo $apoderado->ci->EditValue ?>"<?php echo $apoderado->ci->EditAttributes() ?>>
</span>
<?php echo $apoderado->ci->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->fechanacimiento->Visible) { // fechanacimiento ?>
	<div id="r_fechanacimiento" class="form-group">
		<label id="elh_apoderado_fechanacimiento" for="x_fechanacimiento" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->fechanacimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->fechanacimiento->CellAttributes() ?>>
<span id="el_apoderado_fechanacimiento">
<input type="text" data-table="apoderado" data-field="x_fechanacimiento" name="x_fechanacimiento" id="x_fechanacimiento" placeholder="<?php echo ew_HtmlEncode($apoderado->fechanacimiento->getPlaceHolder()) ?>" value="<?php echo $apoderado->fechanacimiento->EditValue ?>"<?php echo $apoderado->fechanacimiento->EditAttributes() ?>>
<?php if (!$apoderado->fechanacimiento->ReadOnly && !$apoderado->fechanacimiento->Disabled && !isset($apoderado->fechanacimiento->EditAttrs["readonly"]) && !isset($apoderado->fechanacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fapoderadoedit", "x_fechanacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $apoderado->fechanacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->sexo->Visible) { // sexo ?>
	<div id="r_sexo" class="form-group">
		<label id="elh_apoderado_sexo" for="x_sexo" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->sexo->CellAttributes() ?>>
<span id="el_apoderado_sexo">
<select data-table="apoderado" data-field="x_sexo" data-value-separator="<?php echo $apoderado->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $apoderado->sexo->EditAttributes() ?>>
<?php echo $apoderado->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</span>
<?php echo $apoderado->sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_apoderado_direccion" for="x_direccion" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->direccion->FldCaption() ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->direccion->CellAttributes() ?>>
<span id="el_apoderado_direccion">
<input type="text" data-table="apoderado" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" placeholder="<?php echo ew_HtmlEncode($apoderado->direccion->getPlaceHolder()) ?>" value="<?php echo $apoderado->direccion->EditValue ?>"<?php echo $apoderado->direccion->EditAttributes() ?>>
</span>
<?php echo $apoderado->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->celular->Visible) { // celular ?>
	<div id="r_celular" class="form-group">
		<label id="elh_apoderado_celular" for="x_celular" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->celular->FldCaption() ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->celular->CellAttributes() ?>>
<span id="el_apoderado_celular">
<input type="text" data-table="apoderado" data-field="x_celular" name="x_celular" id="x_celular" size="30" placeholder="<?php echo ew_HtmlEncode($apoderado->celular->getPlaceHolder()) ?>" value="<?php echo $apoderado->celular->EditValue ?>"<?php echo $apoderado->celular->EditAttributes() ?>>
</span>
<?php echo $apoderado->celular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->ocupacion->Visible) { // ocupacion ?>
	<div id="r_ocupacion" class="form-group">
		<label id="elh_apoderado_ocupacion" for="x_ocupacion" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->ocupacion->FldCaption() ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->ocupacion->CellAttributes() ?>>
<span id="el_apoderado_ocupacion">
<input type="text" data-table="apoderado" data-field="x_ocupacion" name="x_ocupacion" id="x_ocupacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($apoderado->ocupacion->getPlaceHolder()) ?>" value="<?php echo $apoderado->ocupacion->EditValue ?>"<?php echo $apoderado->ocupacion->EditAttributes() ?>>
</span>
<?php echo $apoderado->ocupacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($apoderado->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_apoderado_observaciones" for="x_observaciones" class="<?php echo $apoderado_edit->LeftColumnClass ?>"><?php echo $apoderado->observaciones->FldCaption() ?></label>
		<div class="<?php echo $apoderado_edit->RightColumnClass ?>"><div<?php echo $apoderado->observaciones->CellAttributes() ?>>
<span id="el_apoderado_observaciones">
<input type="text" data-table="apoderado" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($apoderado->observaciones->getPlaceHolder()) ?>" value="<?php echo $apoderado->observaciones->EditValue ?>"<?php echo $apoderado->observaciones->EditAttributes() ?>>
</span>
<?php echo $apoderado->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$apoderado_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $apoderado_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $apoderado_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fapoderadoedit.Init();
</script>
<?php
$apoderado_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$apoderado_edit->Page_Terminate();
?>
