<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "otrosinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$otros_edit = NULL; // Initialize page object first

class cotros_edit extends cotros {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'otros';

	// Page object name
	var $PageObjName = 'otros_edit';

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

		// Table object (otros)
		if (!isset($GLOBALS["otros"]) || get_class($GLOBALS["otros"]) == "cotros") {
			$GLOBALS["otros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["otros"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'otros', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("otroslist.php"));
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
		$this->id_actividad->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombre->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->nivelestudio->SetVisibility();
		$this->id_discapacidad->SetVisibility();
		$this->id_tipodiscapacidad->SetVisibility();
		$this->resultado->SetVisibility();
		$this->resultadotamizaje->SetVisibility();
		$this->tapon->SetVisibility();
		$this->tipo->SetVisibility();
		$this->repetirprueba->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->id_apoderado->SetVisibility();
		$this->id_referencia->SetVisibility();
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
		global $EW_EXPORT, $otros;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($otros);
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
					if ($pageName == "otrosview.php")
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
					$this->Page_Terminate("otroslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "otroslist.php")
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
		if (!$this->id_actividad->FldIsDetailKey) {
			$this->id_actividad->setFormValue($objForm->GetValue("x_id_actividad"));
		}
		if (!$this->apellidopaterno->FldIsDetailKey) {
			$this->apellidopaterno->setFormValue($objForm->GetValue("x_apellidopaterno"));
		}
		if (!$this->apellidomaterno->FldIsDetailKey) {
			$this->apellidomaterno->setFormValue($objForm->GetValue("x_apellidomaterno"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->nrodiscapacidad->FldIsDetailKey) {
			$this->nrodiscapacidad->setFormValue($objForm->GetValue("x_nrodiscapacidad"));
		}
		if (!$this->ci->FldIsDetailKey) {
			$this->ci->setFormValue($objForm->GetValue("x_ci"));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey) {
			$this->fecha_nacimiento->setFormValue($objForm->GetValue("x_fecha_nacimiento"));
			$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 0);
		}
		if (!$this->sexo->FldIsDetailKey) {
			$this->sexo->setFormValue($objForm->GetValue("x_sexo"));
		}
		if (!$this->nivelestudio->FldIsDetailKey) {
			$this->nivelestudio->setFormValue($objForm->GetValue("x_nivelestudio"));
		}
		if (!$this->id_discapacidad->FldIsDetailKey) {
			$this->id_discapacidad->setFormValue($objForm->GetValue("x_id_discapacidad"));
		}
		if (!$this->id_tipodiscapacidad->FldIsDetailKey) {
			$this->id_tipodiscapacidad->setFormValue($objForm->GetValue("x_id_tipodiscapacidad"));
		}
		if (!$this->resultado->FldIsDetailKey) {
			$this->resultado->setFormValue($objForm->GetValue("x_resultado"));
		}
		if (!$this->resultadotamizaje->FldIsDetailKey) {
			$this->resultadotamizaje->setFormValue($objForm->GetValue("x_resultadotamizaje"));
		}
		if (!$this->tapon->FldIsDetailKey) {
			$this->tapon->setFormValue($objForm->GetValue("x_tapon"));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
		if (!$this->repetirprueba->FldIsDetailKey) {
			$this->repetirprueba->setFormValue($objForm->GetValue("x_repetirprueba"));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
		if (!$this->id_apoderado->FldIsDetailKey) {
			$this->id_apoderado->setFormValue($objForm->GetValue("x_id_apoderado"));
		}
		if (!$this->id_referencia->FldIsDetailKey) {
			$this->id_referencia->setFormValue($objForm->GetValue("x_id_referencia"));
		}
		if (!$this->id_centro->FldIsDetailKey) {
			$this->id_centro->setFormValue($objForm->GetValue("x_id_centro"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->id_actividad->CurrentValue = $this->id_actividad->FormValue;
		$this->apellidopaterno->CurrentValue = $this->apellidopaterno->FormValue;
		$this->apellidomaterno->CurrentValue = $this->apellidomaterno->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->nrodiscapacidad->CurrentValue = $this->nrodiscapacidad->FormValue;
		$this->ci->CurrentValue = $this->ci->FormValue;
		$this->fecha_nacimiento->CurrentValue = $this->fecha_nacimiento->FormValue;
		$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 0);
		$this->sexo->CurrentValue = $this->sexo->FormValue;
		$this->nivelestudio->CurrentValue = $this->nivelestudio->FormValue;
		$this->id_discapacidad->CurrentValue = $this->id_discapacidad->FormValue;
		$this->id_tipodiscapacidad->CurrentValue = $this->id_tipodiscapacidad->FormValue;
		$this->resultado->CurrentValue = $this->resultado->FormValue;
		$this->resultadotamizaje->CurrentValue = $this->resultadotamizaje->FormValue;
		$this->tapon->CurrentValue = $this->tapon->FormValue;
		$this->tipo->CurrentValue = $this->tipo->FormValue;
		$this->repetirprueba->CurrentValue = $this->repetirprueba->FormValue;
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
		$this->id_apoderado->CurrentValue = $this->id_apoderado->FormValue;
		$this->id_referencia->CurrentValue = $this->id_referencia->FormValue;
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
		$this->id_actividad->setDbValue($row['id_actividad']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->ci->setDbValue($row['ci']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->nivelestudio->setDbValue($row['nivelestudio']);
		$this->id_discapacidad->setDbValue($row['id_discapacidad']);
		$this->id_tipodiscapacidad->setDbValue($row['id_tipodiscapacidad']);
		$this->resultado->setDbValue($row['resultado']);
		$this->resultadotamizaje->setDbValue($row['resultadotamizaje']);
		$this->tapon->setDbValue($row['tapon']);
		$this->tipo->setDbValue($row['tipo']);
		$this->repetirprueba->setDbValue($row['repetirprueba']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_apoderado->setDbValue($row['id_apoderado']);
		$this->id_referencia->setDbValue($row['id_referencia']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_actividad'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombre'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['ci'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['nivelestudio'] = NULL;
		$row['id_discapacidad'] = NULL;
		$row['id_tipodiscapacidad'] = NULL;
		$row['resultado'] = NULL;
		$row['resultadotamizaje'] = NULL;
		$row['tapon'] = NULL;
		$row['tipo'] = NULL;
		$row['repetirprueba'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_apoderado'] = NULL;
		$row['id_referencia'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_actividad->DbValue = $row['id_actividad'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->ci->DbValue = $row['ci'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->nivelestudio->DbValue = $row['nivelestudio'];
		$this->id_discapacidad->DbValue = $row['id_discapacidad'];
		$this->id_tipodiscapacidad->DbValue = $row['id_tipodiscapacidad'];
		$this->resultado->DbValue = $row['resultado'];
		$this->resultadotamizaje->DbValue = $row['resultadotamizaje'];
		$this->tapon->DbValue = $row['tapon'];
		$this->tipo->DbValue = $row['tipo'];
		$this->repetirprueba->DbValue = $row['repetirprueba'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_apoderado->DbValue = $row['id_apoderado'];
		$this->id_referencia->DbValue = $row['id_referencia'];
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
		// id_actividad
		// apellidopaterno
		// apellidomaterno
		// nombre
		// nrodiscapacidad
		// ci
		// fecha_nacimiento
		// sexo
		// nivelestudio
		// id_discapacidad
		// id_tipodiscapacidad
		// resultado
		// resultadotamizaje
		// tapon
		// tipo
		// repetirprueba
		// observaciones
		// id_apoderado
		// id_referencia
		// id_centro

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_actividad
		if (strval($this->id_actividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
		$sWhereWrk = "";
		$this->id_actividad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_actividad->ViewValue = $this->id_actividad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_actividad->ViewValue = $this->id_actividad->CurrentValue;
			}
		} else {
			$this->id_actividad->ViewValue = NULL;
		}
		$this->id_actividad->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// nivelestudio
		$this->nivelestudio->ViewValue = $this->nivelestudio->CurrentValue;
		$this->nivelestudio->ViewCustomAttributes = "";

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
		$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->CurrentValue;
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
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tapon->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tapon`";
		$sWhereWrk = "";
		$this->tapon->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tapon, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tapon->ViewValue = $this->tapon->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tapon->ViewValue = $this->tapon->CurrentValue;
			}
		} else {
			$this->tapon->ViewValue = NULL;
		}
		$this->tapon->ViewCustomAttributes = "";

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$this->tipo->ViewValue = $this->tipo->OptionCaption($this->tipo->CurrentValue);
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

		// repetirprueba
		$this->repetirprueba->ViewValue = $this->repetirprueba->CurrentValue;
		$this->repetirprueba->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_apoderado
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
		$this->id_apoderado->ViewCustomAttributes = "";

		// id_referencia
		if (strval($this->id_referencia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_referencia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombrescentromedico` AS `DispFld`, `nombrescompleto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
		$sWhereWrk = "";
		$this->id_referencia->LookupFilters = array("dx1" => '`nombrescentromedico`', "dx2" => '`nombrescompleto`');
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
		$this->id_referencia->ViewCustomAttributes = "";

		// id_centro
		$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
		$this->id_centro->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";
			$this->id_actividad->TooltipValue = "";

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

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
			$this->nrodiscapacidad->TooltipValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";
			$this->sexo->TooltipValue = "";

			// nivelestudio
			$this->nivelestudio->LinkCustomAttributes = "";
			$this->nivelestudio->HrefValue = "";
			$this->nivelestudio->TooltipValue = "";

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

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";

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

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";
			$this->id_centro->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// id_actividad
			$this->id_actividad->EditAttrs["class"] = "form-control";
			$this->id_actividad->EditCustomAttributes = "";
			if (trim(strval($this->id_actividad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `actividad`";
			$sWhereWrk = "";
			$this->id_actividad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_actividad->EditValue = $arwrk;

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

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

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

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 8));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// nivelestudio
			$this->nivelestudio->EditAttrs["class"] = "form-control";
			$this->nivelestudio->EditCustomAttributes = "";
			$this->nivelestudio->EditValue = ew_HtmlEncode($this->nivelestudio->CurrentValue);
			$this->nivelestudio->PlaceHolder = ew_RemoveHtml($this->nivelestudio->FldCaption());

			// id_discapacidad
			$this->id_discapacidad->EditAttrs["class"] = "form-control";
			$this->id_discapacidad->EditCustomAttributes = "";
			if (trim(strval($this->id_discapacidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `discapacidad`";
			$sWhereWrk = "";
			$this->id_discapacidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_discapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_discapacidad->EditValue = $arwrk;

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->EditAttrs["class"] = "form-control";
			$this->id_tipodiscapacidad->EditCustomAttributes = "";
			$this->id_tipodiscapacidad->EditValue = ew_HtmlEncode($this->id_tipodiscapacidad->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->id_tipodiscapacidad->EditValue = $this->id_tipodiscapacidad->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_tipodiscapacidad->EditValue = ew_HtmlEncode($this->id_tipodiscapacidad->CurrentValue);
				}
			} else {
				$this->id_tipodiscapacidad->EditValue = NULL;
			}
			$this->id_tipodiscapacidad->PlaceHolder = ew_RemoveHtml($this->id_tipodiscapacidad->FldCaption());

			// resultado
			$this->resultado->EditCustomAttributes = "";
			$this->resultado->EditValue = $this->resultado->Options(FALSE);

			// resultadotamizaje
			$this->resultadotamizaje->EditAttrs["class"] = "form-control";
			$this->resultadotamizaje->EditCustomAttributes = "";
			$this->resultadotamizaje->EditValue = ew_HtmlEncode($this->resultadotamizaje->CurrentValue);
			$this->resultadotamizaje->PlaceHolder = ew_RemoveHtml($this->resultadotamizaje->FldCaption());

			// tapon
			$this->tapon->EditCustomAttributes = "";
			if (trim(strval($this->tapon->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->tapon->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tapon`";
			$sWhereWrk = "";
			$this->tapon->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tapon, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->tapon->EditValue = $arwrk;

			// tipo
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = $this->tipo->Options(FALSE);

			// repetirprueba
			$this->repetirprueba->EditAttrs["class"] = "form-control";
			$this->repetirprueba->EditCustomAttributes = "";
			$this->repetirprueba->EditValue = ew_HtmlEncode($this->repetirprueba->CurrentValue);
			$this->repetirprueba->PlaceHolder = ew_RemoveHtml($this->repetirprueba->FldCaption());

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// id_apoderado
			$this->id_apoderado->EditCustomAttributes = "";
			if (trim(strval($this->id_apoderado->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_apoderado->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `apoderado`";
			$sWhereWrk = "";
			$this->id_apoderado->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_apoderado, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->id_apoderado->ViewValue = $this->id_apoderado->DisplayValue($arwrk);
			} else {
				$this->id_apoderado->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_apoderado->EditValue = $arwrk;

			// id_referencia
			$this->id_referencia->EditCustomAttributes = "";
			if (trim(strval($this->id_referencia->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_referencia->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombrescentromedico` AS `DispFld`, `nombrescompleto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `referencia`";
			$sWhereWrk = "";
			$this->id_referencia->LookupFilters = array("dx1" => '`nombrescentromedico`', "dx2" => '`nombrescompleto`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_referencia, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->id_referencia->ViewValue = $this->id_referencia->DisplayValue($arwrk);
			} else {
				$this->id_referencia->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_referencia->EditValue = $arwrk;

			// id_centro
			$this->id_centro->EditAttrs["class"] = "form-control";
			$this->id_centro->EditCustomAttributes = "";
			$this->id_centro->EditValue = ew_HtmlEncode($this->id_centro->CurrentValue);
			$this->id_centro->PlaceHolder = ew_RemoveHtml($this->id_centro->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";

			// nivelestudio
			$this->nivelestudio->LinkCustomAttributes = "";
			$this->nivelestudio->HrefValue = "";

			// id_discapacidad
			$this->id_discapacidad->LinkCustomAttributes = "";
			$this->id_discapacidad->HrefValue = "";

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->LinkCustomAttributes = "";
			$this->id_tipodiscapacidad->HrefValue = "";

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";

			// resultadotamizaje
			$this->resultadotamizaje->LinkCustomAttributes = "";
			$this->resultadotamizaje->HrefValue = "";

			// tapon
			$this->tapon->LinkCustomAttributes = "";
			$this->tapon->HrefValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";

			// repetirprueba
			$this->repetirprueba->LinkCustomAttributes = "";
			$this->repetirprueba->HrefValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";

			// id_apoderado
			$this->id_apoderado->LinkCustomAttributes = "";
			$this->id_apoderado->HrefValue = "";

			// id_referencia
			$this->id_referencia->LinkCustomAttributes = "";
			$this->id_referencia->HrefValue = "";

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
		if (!$this->id_actividad->FldIsDetailKey && !is_null($this->id_actividad->FormValue) && $this->id_actividad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_actividad->FldCaption(), $this->id_actividad->ReqErrMsg));
		}
		if (!$this->apellidopaterno->FldIsDetailKey && !is_null($this->apellidopaterno->FormValue) && $this->apellidopaterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->apellidopaterno->FldCaption(), $this->apellidopaterno->ReqErrMsg));
		}
		if (!$this->apellidomaterno->FldIsDetailKey && !is_null($this->apellidomaterno->FormValue) && $this->apellidomaterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->apellidomaterno->FldCaption(), $this->apellidomaterno->ReqErrMsg));
		}
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey && !is_null($this->fecha_nacimiento->FormValue) && $this->fecha_nacimiento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha_nacimiento->FldCaption(), $this->fecha_nacimiento->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->fecha_nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_nacimiento->FldErrMsg());
		}
		if (!$this->sexo->FldIsDetailKey && !is_null($this->sexo->FormValue) && $this->sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sexo->FldCaption(), $this->sexo->ReqErrMsg));
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

			// id_actividad
			$this->id_actividad->SetDbValueDef($rsnew, $this->id_actividad->CurrentValue, 0, $this->id_actividad->ReadOnly);

			// apellidopaterno
			$this->apellidopaterno->SetDbValueDef($rsnew, $this->apellidopaterno->CurrentValue, "", $this->apellidopaterno->ReadOnly);

			// apellidomaterno
			$this->apellidomaterno->SetDbValueDef($rsnew, $this->apellidomaterno->CurrentValue, "", $this->apellidomaterno->ReadOnly);

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", $this->nombre->ReadOnly);

			// nrodiscapacidad
			$this->nrodiscapacidad->SetDbValueDef($rsnew, $this->nrodiscapacidad->CurrentValue, NULL, $this->nrodiscapacidad->ReadOnly);

			// ci
			$this->ci->SetDbValueDef($rsnew, $this->ci->CurrentValue, NULL, $this->ci->ReadOnly);

			// fecha_nacimiento
			$this->fecha_nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 0), ew_CurrentDate(), $this->fecha_nacimiento->ReadOnly);

			// sexo
			$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, "", $this->sexo->ReadOnly);

			// nivelestudio
			$this->nivelestudio->SetDbValueDef($rsnew, $this->nivelestudio->CurrentValue, NULL, $this->nivelestudio->ReadOnly);

			// id_discapacidad
			$this->id_discapacidad->SetDbValueDef($rsnew, $this->id_discapacidad->CurrentValue, NULL, $this->id_discapacidad->ReadOnly);

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->SetDbValueDef($rsnew, $this->id_tipodiscapacidad->CurrentValue, NULL, $this->id_tipodiscapacidad->ReadOnly);

			// resultado
			$this->resultado->SetDbValueDef($rsnew, $this->resultado->CurrentValue, NULL, $this->resultado->ReadOnly);

			// resultadotamizaje
			$this->resultadotamizaje->SetDbValueDef($rsnew, $this->resultadotamizaje->CurrentValue, NULL, $this->resultadotamizaje->ReadOnly);

			// tapon
			$this->tapon->SetDbValueDef($rsnew, $this->tapon->CurrentValue, NULL, $this->tapon->ReadOnly);

			// tipo
			$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, NULL, $this->tipo->ReadOnly);

			// repetirprueba
			$this->repetirprueba->SetDbValueDef($rsnew, $this->repetirprueba->CurrentValue, NULL, $this->repetirprueba->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

			// id_apoderado
			$this->id_apoderado->SetDbValueDef($rsnew, $this->id_apoderado->CurrentValue, NULL, $this->id_apoderado->ReadOnly);

			// id_referencia
			$this->id_referencia->SetDbValueDef($rsnew, $this->id_referencia->CurrentValue, NULL, $this->id_referencia->ReadOnly);

			// id_centro
			$this->id_centro->SetDbValueDef($rsnew, $this->id_centro->CurrentValue, 0, $this->id_centro->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("otroslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_actividad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_discapacidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_discapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_tipodiscapacidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiscapacidad`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_tapon":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tapon`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->tapon, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_apoderado":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `apoderado`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_apoderado, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_referencia":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombrescentromedico` AS `DispFld`, `nombrescompleto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombrescentromedico`', "dx2" => '`nombrescompleto`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_referencia, $sWhereWrk); // Call Lookup Selecting
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
		case "x_id_tipodiscapacidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld` FROM `tipodiscapacidad`";
			$sWhereWrk = "`nombre` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($otros_edit)) $otros_edit = new cotros_edit();

// Page init
$otros_edit->Page_Init();

// Page main
$otros_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$otros_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fotrosedit = new ew_Form("fotrosedit", "edit");

// Validate form
fotrosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_actividad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otros->id_actividad->FldCaption(), $otros->id_actividad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidopaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otros->apellidopaterno->FldCaption(), $otros->apellidopaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidomaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otros->apellidomaterno->FldCaption(), $otros->apellidomaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otros->nombre->FldCaption(), $otros->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otros->fecha_nacimiento->FldCaption(), $otros->fecha_nacimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($otros->fecha_nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otros->sexo->FldCaption(), $otros->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otros->id_centro->FldCaption(), $otros->id_centro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($otros->id_centro->FldErrMsg()) ?>");

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
fotrosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fotrosedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fotrosedit.Lists["x_id_actividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreactividad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"actividad"};
fotrosedit.Lists["x_id_actividad"].Data = "<?php echo $otros_edit->id_actividad->LookupFilterQuery(FALSE, "edit") ?>";
fotrosedit.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fotrosedit.Lists["x_sexo"].Options = <?php echo json_encode($otros_edit->sexo->Options()) ?>;
fotrosedit.Lists["x_id_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fotrosedit.Lists["x_id_discapacidad"].Data = "<?php echo $otros_edit->id_discapacidad->LookupFilterQuery(FALSE, "edit") ?>";
fotrosedit.Lists["x_id_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
fotrosedit.Lists["x_id_tipodiscapacidad"].Data = "<?php echo $otros_edit->id_tipodiscapacidad->LookupFilterQuery(FALSE, "edit") ?>";
fotrosedit.AutoSuggests["x_id_tipodiscapacidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $otros_edit->id_tipodiscapacidad->LookupFilterQuery(TRUE, "edit"))) ?>;
fotrosedit.Lists["x_resultado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fotrosedit.Lists["x_resultado"].Options = <?php echo json_encode($otros_edit->resultado->Options()) ?>;
fotrosedit.Lists["x_tapon"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tapon"};
fotrosedit.Lists["x_tapon"].Data = "<?php echo $otros_edit->tapon->LookupFilterQuery(FALSE, "edit") ?>";
fotrosedit.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fotrosedit.Lists["x_tipo"].Options = <?php echo json_encode($otros_edit->tipo->Options()) ?>;
fotrosedit.Lists["x_id_apoderado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"apoderado"};
fotrosedit.Lists["x_id_apoderado"].Data = "<?php echo $otros_edit->id_apoderado->LookupFilterQuery(FALSE, "edit") ?>";
fotrosedit.Lists["x_id_referencia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombrescentromedico","x_nombrescompleto","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referencia"};
fotrosedit.Lists["x_id_referencia"].Data = "<?php echo $otros_edit->id_referencia->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $otros_edit->ShowPageHeader(); ?>
<?php
$otros_edit->ShowMessage();
?>
<form name="fotrosedit" id="fotrosedit" class="<?php echo $otros_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($otros_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $otros_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="otros">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($otros_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($otros->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_otros_id" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->id->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->id->CellAttributes() ?>>
<span id="el_otros_id">
<span<?php echo $otros->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $otros->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="otros" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($otros->id->CurrentValue) ?>">
<?php echo $otros->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->id_actividad->Visible) { // id_actividad ?>
	<div id="r_id_actividad" class="form-group">
		<label id="elh_otros_id_actividad" for="x_id_actividad" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->id_actividad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->id_actividad->CellAttributes() ?>>
<span id="el_otros_id_actividad">
<select data-table="otros" data-field="x_id_actividad" data-value-separator="<?php echo $otros->id_actividad->DisplayValueSeparatorAttribute() ?>" id="x_id_actividad" name="x_id_actividad"<?php echo $otros->id_actividad->EditAttributes() ?>>
<?php echo $otros->id_actividad->SelectOptionListHtml("x_id_actividad") ?>
</select>
</span>
<?php echo $otros->id_actividad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="r_apellidopaterno" class="form-group">
		<label id="elh_otros_apellidopaterno" for="x_apellidopaterno" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->apellidopaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->apellidopaterno->CellAttributes() ?>>
<span id="el_otros_apellidopaterno">
<input type="text" data-table="otros" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $otros->apellidopaterno->EditValue ?>"<?php echo $otros->apellidopaterno->EditAttributes() ?>>
</span>
<?php echo $otros->apellidopaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="r_apellidomaterno" class="form-group">
		<label id="elh_otros_apellidomaterno" for="x_apellidomaterno" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->apellidomaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->apellidomaterno->CellAttributes() ?>>
<span id="el_otros_apellidomaterno">
<input type="text" data-table="otros" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $otros->apellidomaterno->EditValue ?>"<?php echo $otros->apellidomaterno->EditAttributes() ?>>
</span>
<?php echo $otros->apellidomaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_otros_nombre" for="x_nombre" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->nombre->CellAttributes() ?>>
<span id="el_otros_nombre">
<input type="text" data-table="otros" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->nombre->getPlaceHolder()) ?>" value="<?php echo $otros->nombre->EditValue ?>"<?php echo $otros->nombre->EditAttributes() ?>>
</span>
<?php echo $otros->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="r_nrodiscapacidad" class="form-group">
		<label id="elh_otros_nrodiscapacidad" for="x_nrodiscapacidad" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->nrodiscapacidad->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->nrodiscapacidad->CellAttributes() ?>>
<span id="el_otros_nrodiscapacidad">
<input type="text" data-table="otros" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $otros->nrodiscapacidad->EditValue ?>"<?php echo $otros->nrodiscapacidad->EditAttributes() ?>>
</span>
<?php echo $otros->nrodiscapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->ci->Visible) { // ci ?>
	<div id="r_ci" class="form-group">
		<label id="elh_otros_ci" for="x_ci" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->ci->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->ci->CellAttributes() ?>>
<span id="el_otros_ci">
<input type="text" data-table="otros" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->ci->getPlaceHolder()) ?>" value="<?php echo $otros->ci->EditValue ?>"<?php echo $otros->ci->EditAttributes() ?>>
</span>
<?php echo $otros->ci->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<div id="r_fecha_nacimiento" class="form-group">
		<label id="elh_otros_fecha_nacimiento" for="x_fecha_nacimiento" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->fecha_nacimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->fecha_nacimiento->CellAttributes() ?>>
<span id="el_otros_fecha_nacimiento">
<input type="text" data-table="otros" data-field="x_fecha_nacimiento" name="x_fecha_nacimiento" id="x_fecha_nacimiento" placeholder="<?php echo ew_HtmlEncode($otros->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $otros->fecha_nacimiento->EditValue ?>"<?php echo $otros->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$otros->fecha_nacimiento->ReadOnly && !$otros->fecha_nacimiento->Disabled && !isset($otros->fecha_nacimiento->EditAttrs["readonly"]) && !isset($otros->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fotrosedit", "x_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $otros->fecha_nacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->sexo->Visible) { // sexo ?>
	<div id="r_sexo" class="form-group">
		<label id="elh_otros_sexo" for="x_sexo" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->sexo->CellAttributes() ?>>
<span id="el_otros_sexo">
<select data-table="otros" data-field="x_sexo" data-value-separator="<?php echo $otros->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $otros->sexo->EditAttributes() ?>>
<?php echo $otros->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</span>
<?php echo $otros->sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->nivelestudio->Visible) { // nivelestudio ?>
	<div id="r_nivelestudio" class="form-group">
		<label id="elh_otros_nivelestudio" for="x_nivelestudio" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->nivelestudio->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->nivelestudio->CellAttributes() ?>>
<span id="el_otros_nivelestudio">
<input type="text" data-table="otros" data-field="x_nivelestudio" name="x_nivelestudio" id="x_nivelestudio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->nivelestudio->getPlaceHolder()) ?>" value="<?php echo $otros->nivelestudio->EditValue ?>"<?php echo $otros->nivelestudio->EditAttributes() ?>>
</span>
<?php echo $otros->nivelestudio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->id_discapacidad->Visible) { // id_discapacidad ?>
	<div id="r_id_discapacidad" class="form-group">
		<label id="elh_otros_id_discapacidad" for="x_id_discapacidad" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->id_discapacidad->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->id_discapacidad->CellAttributes() ?>>
<span id="el_otros_id_discapacidad">
<select data-table="otros" data-field="x_id_discapacidad" data-value-separator="<?php echo $otros->id_discapacidad->DisplayValueSeparatorAttribute() ?>" id="x_id_discapacidad" name="x_id_discapacidad"<?php echo $otros->id_discapacidad->EditAttributes() ?>>
<?php echo $otros->id_discapacidad->SelectOptionListHtml("x_id_discapacidad") ?>
</select>
</span>
<?php echo $otros->id_discapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
	<div id="r_id_tipodiscapacidad" class="form-group">
		<label id="elh_otros_id_tipodiscapacidad" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->id_tipodiscapacidad->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->id_tipodiscapacidad->CellAttributes() ?>>
<span id="el_otros_id_tipodiscapacidad">
<?php
$wrkonchange = trim(" " . @$otros->id_tipodiscapacidad->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$otros->id_tipodiscapacidad->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_tipodiscapacidad" style="white-space: nowrap; z-index: 8880">
	<input type="text" name="sv_x_id_tipodiscapacidad" id="sv_x_id_tipodiscapacidad" value="<?php echo $otros->id_tipodiscapacidad->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->id_tipodiscapacidad->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($otros->id_tipodiscapacidad->getPlaceHolder()) ?>"<?php echo $otros->id_tipodiscapacidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="otros" data-field="x_id_tipodiscapacidad" data-value-separator="<?php echo $otros->id_tipodiscapacidad->DisplayValueSeparatorAttribute() ?>" name="x_id_tipodiscapacidad" id="x_id_tipodiscapacidad" value="<?php echo ew_HtmlEncode($otros->id_tipodiscapacidad->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fotrosedit.CreateAutoSuggest({"id":"x_id_tipodiscapacidad","forceSelect":false});
</script>
</span>
<?php echo $otros->id_tipodiscapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->resultado->Visible) { // resultado ?>
	<div id="r_resultado" class="form-group">
		<label id="elh_otros_resultado" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->resultado->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->resultado->CellAttributes() ?>>
<span id="el_otros_resultado">
<div id="tp_x_resultado" class="ewTemplate"><input type="radio" data-table="otros" data-field="x_resultado" data-value-separator="<?php echo $otros->resultado->DisplayValueSeparatorAttribute() ?>" name="x_resultado" id="x_resultado" value="{value}"<?php echo $otros->resultado->EditAttributes() ?>></div>
<div id="dsl_x_resultado" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $otros->resultado->RadioButtonListHtml(FALSE, "x_resultado") ?>
</div></div>
</span>
<?php echo $otros->resultado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->resultadotamizaje->Visible) { // resultadotamizaje ?>
	<div id="r_resultadotamizaje" class="form-group">
		<label id="elh_otros_resultadotamizaje" for="x_resultadotamizaje" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->resultadotamizaje->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->resultadotamizaje->CellAttributes() ?>>
<span id="el_otros_resultadotamizaje">
<input type="text" data-table="otros" data-field="x_resultadotamizaje" name="x_resultadotamizaje" id="x_resultadotamizaje" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->resultadotamizaje->getPlaceHolder()) ?>" value="<?php echo $otros->resultadotamizaje->EditValue ?>"<?php echo $otros->resultadotamizaje->EditAttributes() ?>>
</span>
<?php echo $otros->resultadotamizaje->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->tapon->Visible) { // tapon ?>
	<div id="r_tapon" class="form-group">
		<label id="elh_otros_tapon" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->tapon->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->tapon->CellAttributes() ?>>
<span id="el_otros_tapon">
<div id="tp_x_tapon" class="ewTemplate"><input type="radio" data-table="otros" data-field="x_tapon" data-value-separator="<?php echo $otros->tapon->DisplayValueSeparatorAttribute() ?>" name="x_tapon" id="x_tapon" value="{value}"<?php echo $otros->tapon->EditAttributes() ?>></div>
<div id="dsl_x_tapon" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $otros->tapon->RadioButtonListHtml(FALSE, "x_tapon") ?>
</div></div>
</span>
<?php echo $otros->tapon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->tipo->Visible) { // tipo ?>
	<div id="r_tipo" class="form-group">
		<label id="elh_otros_tipo" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->tipo->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->tipo->CellAttributes() ?>>
<span id="el_otros_tipo">
<div id="tp_x_tipo" class="ewTemplate"><input type="radio" data-table="otros" data-field="x_tipo" data-value-separator="<?php echo $otros->tipo->DisplayValueSeparatorAttribute() ?>" name="x_tipo" id="x_tipo" value="{value}"<?php echo $otros->tipo->EditAttributes() ?>></div>
<div id="dsl_x_tipo" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $otros->tipo->RadioButtonListHtml(FALSE, "x_tipo") ?>
</div></div>
</span>
<?php echo $otros->tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->repetirprueba->Visible) { // repetirprueba ?>
	<div id="r_repetirprueba" class="form-group">
		<label id="elh_otros_repetirprueba" for="x_repetirprueba" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->repetirprueba->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->repetirprueba->CellAttributes() ?>>
<span id="el_otros_repetirprueba">
<input type="text" data-table="otros" data-field="x_repetirprueba" name="x_repetirprueba" id="x_repetirprueba" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->repetirprueba->getPlaceHolder()) ?>" value="<?php echo $otros->repetirprueba->EditValue ?>"<?php echo $otros->repetirprueba->EditAttributes() ?>>
</span>
<?php echo $otros->repetirprueba->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_otros_observaciones" for="x_observaciones" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->observaciones->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->observaciones->CellAttributes() ?>>
<span id="el_otros_observaciones">
<input type="text" data-table="otros" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->observaciones->getPlaceHolder()) ?>" value="<?php echo $otros->observaciones->EditValue ?>"<?php echo $otros->observaciones->EditAttributes() ?>>
</span>
<?php echo $otros->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->id_apoderado->Visible) { // id_apoderado ?>
	<div id="r_id_apoderado" class="form-group">
		<label id="elh_otros_id_apoderado" for="x_id_apoderado" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->id_apoderado->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->id_apoderado->CellAttributes() ?>>
<span id="el_otros_id_apoderado">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_apoderado"><?php echo (strval($otros->id_apoderado->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $otros->id_apoderado->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($otros->id_apoderado->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_apoderado',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($otros->id_apoderado->ReadOnly || $otros->id_apoderado->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="otros" data-field="x_id_apoderado" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $otros->id_apoderado->DisplayValueSeparatorAttribute() ?>" name="x_id_apoderado" id="x_id_apoderado" value="<?php echo $otros->id_apoderado->CurrentValue ?>"<?php echo $otros->id_apoderado->EditAttributes() ?>>
</span>
<?php echo $otros->id_apoderado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->id_referencia->Visible) { // id_referencia ?>
	<div id="r_id_referencia" class="form-group">
		<label id="elh_otros_id_referencia" for="x_id_referencia" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->id_referencia->FldCaption() ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->id_referencia->CellAttributes() ?>>
<span id="el_otros_id_referencia">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_referencia"><?php echo (strval($otros->id_referencia->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $otros->id_referencia->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($otros->id_referencia->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_referencia',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($otros->id_referencia->ReadOnly || $otros->id_referencia->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="otros" data-field="x_id_referencia" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $otros->id_referencia->DisplayValueSeparatorAttribute() ?>" name="x_id_referencia" id="x_id_referencia" value="<?php echo $otros->id_referencia->CurrentValue ?>"<?php echo $otros->id_referencia->EditAttributes() ?>>
</span>
<?php echo $otros->id_referencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otros->id_centro->Visible) { // id_centro ?>
	<div id="r_id_centro" class="form-group">
		<label id="elh_otros_id_centro" for="x_id_centro" class="<?php echo $otros_edit->LeftColumnClass ?>"><?php echo $otros->id_centro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otros_edit->RightColumnClass ?>"><div<?php echo $otros->id_centro->CellAttributes() ?>>
<span id="el_otros_id_centro">
<input type="text" data-table="otros" data-field="x_id_centro" name="x_id_centro" id="x_id_centro" size="30" placeholder="<?php echo ew_HtmlEncode($otros->id_centro->getPlaceHolder()) ?>" value="<?php echo $otros->id_centro->EditValue ?>"<?php echo $otros->id_centro->EditAttributes() ?>>
</span>
<?php echo $otros->id_centro->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$otros_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $otros_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $otros_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fotrosedit.Init();
</script>
<?php
$otros_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$otros_edit->Page_Terminate();
?>
