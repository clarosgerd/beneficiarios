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

$escolar_edit = NULL; // Initialize page object first

class cescolar_edit extends cescolar {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'escolar';

	// Page object name
	var $PageObjName = 'escolar_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "escolarview.php")
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
					$this->Page_Terminate("escolarlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "escolarlist.php")
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
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
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
		if (!$this->curso->FldIsDetailKey) {
			$this->curso->setFormValue($objForm->GetValue("x_curso"));
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
		if (!$this->tapodonde->FldIsDetailKey) {
			$this->tapodonde->setFormValue($objForm->GetValue("x_tapodonde"));
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
		if (!$this->codigorude->FldIsDetailKey) {
			$this->codigorude->setFormValue($objForm->GetValue("x_codigorude"));
		}
		if (!$this->codigorude_es->FldIsDetailKey) {
			$this->codigorude_es->setFormValue($objForm->GetValue("x_codigorude_es"));
		}
		if (!$this->nrodiscapacidad->FldIsDetailKey) {
			$this->nrodiscapacidad->setFormValue($objForm->GetValue("x_nrodiscapacidad"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		$this->unidadeducativa->CurrentValue = $this->unidadeducativa->FormValue;
		$this->apellidopaterno->CurrentValue = $this->apellidopaterno->FormValue;
		$this->apellidomaterno->CurrentValue = $this->apellidomaterno->FormValue;
		$this->nombres->CurrentValue = $this->nombres->FormValue;
		$this->ci->CurrentValue = $this->ci->FormValue;
		$this->fechanacimiento->CurrentValue = $this->fechanacimiento->FormValue;
		$this->fechanacimiento->CurrentValue = ew_UnFormatDateTime($this->fechanacimiento->CurrentValue, 0);
		$this->sexo->CurrentValue = $this->sexo->FormValue;
		$this->curso->CurrentValue = $this->curso->FormValue;
		$this->id_discapacidad->CurrentValue = $this->id_discapacidad->FormValue;
		$this->id_tipodiscapacidad->CurrentValue = $this->id_tipodiscapacidad->FormValue;
		$this->resultado->CurrentValue = $this->resultado->FormValue;
		$this->resultadotamizaje->CurrentValue = $this->resultadotamizaje->FormValue;
		$this->tapon->CurrentValue = $this->tapon->FormValue;
		$this->tapodonde->CurrentValue = $this->tapodonde->FormValue;
		$this->repetirprueba->CurrentValue = $this->repetirprueba->FormValue;
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
		$this->id_apoderado->CurrentValue = $this->id_apoderado->FormValue;
		$this->id_referencia->CurrentValue = $this->id_referencia->FormValue;
		$this->codigorude->CurrentValue = $this->codigorude->FormValue;
		$this->codigorude_es->CurrentValue = $this->codigorude_es->FormValue;
		$this->nrodiscapacidad->CurrentValue = $this->nrodiscapacidad->FormValue;
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
		// fecha
		// id_departamento
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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// unidadeducativa
			$this->unidadeducativa->EditAttrs["class"] = "form-control";
			$this->unidadeducativa->EditCustomAttributes = "";
			if (trim(strval($this->unidadeducativa->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->unidadeducativa->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `unidadeducativa`";
			$sWhereWrk = "";
			$this->unidadeducativa->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
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

			// curso
			$this->curso->EditAttrs["class"] = "form-control";
			$this->curso->EditCustomAttributes = "";
			$this->curso->EditValue = ew_HtmlEncode($this->curso->CurrentValue);
			$this->curso->PlaceHolder = ew_RemoveHtml($this->curso->FldCaption());

			// id_discapacidad
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
			$this->id_tipodiscapacidad->EditCustomAttributes = "";
			if (trim(strval($this->id_tipodiscapacidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipodiscapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipodiscapacidad`";
			$sWhereWrk = "";
			$this->id_tipodiscapacidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_tipodiscapacidad->EditValue = $arwrk;

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
			$this->tapon->EditValue = $this->tapon->Options(FALSE);

			// tapodonde
			$this->tapodonde->EditCustomAttributes = "";
			$this->tapodonde->EditValue = $this->tapodonde->Options(FALSE);

			// repetirprueba
			$this->repetirprueba->EditCustomAttributes = "";
			$this->repetirprueba->EditValue = $this->repetirprueba->Options(FALSE);

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
			$sSqlWrk = "SELECT `id`, `nombrescompleto` AS `DispFld`, `nombrescentromedico` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `referencia`";
			$sWhereWrk = "";
			$this->id_referencia->LookupFilters = array("dx1" => '`nombrescompleto`', "dx2" => '`nombrescentromedico`');
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

			// codigorude
			$this->codigorude->EditAttrs["class"] = "form-control";
			$this->codigorude->EditCustomAttributes = "";
			$this->codigorude->EditValue = ew_HtmlEncode($this->codigorude->CurrentValue);
			$this->codigorude->PlaceHolder = ew_RemoveHtml($this->codigorude->FldCaption());

			// codigorude_es
			$this->codigorude_es->EditAttrs["class"] = "form-control";
			$this->codigorude_es->EditCustomAttributes = "";
			$this->codigorude_es->EditValue = ew_HtmlEncode($this->codigorude_es->CurrentValue);
			$this->codigorude_es->PlaceHolder = ew_RemoveHtml($this->codigorude_es->FldCaption());

			// nrodiscapacidad
			$this->nrodiscapacidad->EditAttrs["class"] = "form-control";
			$this->nrodiscapacidad->EditCustomAttributes = "";
			$this->nrodiscapacidad->EditValue = ew_HtmlEncode($this->nrodiscapacidad->CurrentValue);
			$this->nrodiscapacidad->PlaceHolder = ew_RemoveHtml($this->nrodiscapacidad->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

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

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->LinkCustomAttributes = "";
			$this->fechanacimiento->HrefValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";

			// curso
			$this->curso->LinkCustomAttributes = "";
			$this->curso->HrefValue = "";

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

			// tapodonde
			$this->tapodonde->LinkCustomAttributes = "";
			$this->tapodonde->HrefValue = "";

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

			// codigorude
			$this->codigorude->LinkCustomAttributes = "";
			$this->codigorude->HrefValue = "";

			// codigorude_es
			$this->codigorude_es->LinkCustomAttributes = "";
			$this->codigorude_es->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
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
		if (!$this->fecha->FldIsDetailKey && !is_null($this->fecha->FormValue) && $this->fecha->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha->FldCaption(), $this->fecha->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
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
		if (!$this->curso->FldIsDetailKey && !is_null($this->curso->FormValue) && $this->curso->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->curso->FldCaption(), $this->curso->ReqErrMsg));
		}
		if ($this->id_discapacidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_discapacidad->FldCaption(), $this->id_discapacidad->ReqErrMsg));
		}
		if ($this->resultado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->resultado->FldCaption(), $this->resultado->ReqErrMsg));
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

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 0), ew_CurrentDate(), $this->fecha->ReadOnly);

			// unidadeducativa
			$this->unidadeducativa->SetDbValueDef($rsnew, $this->unidadeducativa->CurrentValue, 0, $this->unidadeducativa->ReadOnly);

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
			$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, NULL, $this->sexo->ReadOnly);

			// curso
			$this->curso->SetDbValueDef($rsnew, $this->curso->CurrentValue, "", $this->curso->ReadOnly);

			// id_discapacidad
			$this->id_discapacidad->SetDbValueDef($rsnew, $this->id_discapacidad->CurrentValue, 0, $this->id_discapacidad->ReadOnly);

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->SetDbValueDef($rsnew, $this->id_tipodiscapacidad->CurrentValue, NULL, $this->id_tipodiscapacidad->ReadOnly);

			// resultado
			$this->resultado->SetDbValueDef($rsnew, $this->resultado->CurrentValue, "", $this->resultado->ReadOnly);

			// resultadotamizaje
			$this->resultadotamizaje->SetDbValueDef($rsnew, $this->resultadotamizaje->CurrentValue, NULL, $this->resultadotamizaje->ReadOnly);

			// tapon
			$this->tapon->SetDbValueDef($rsnew, $this->tapon->CurrentValue, NULL, $this->tapon->ReadOnly);

			// tapodonde
			$this->tapodonde->SetDbValueDef($rsnew, $this->tapodonde->CurrentValue, NULL, $this->tapodonde->ReadOnly);

			// repetirprueba
			$this->repetirprueba->SetDbValueDef($rsnew, $this->repetirprueba->CurrentValue, NULL, $this->repetirprueba->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

			// id_apoderado
			$this->id_apoderado->SetDbValueDef($rsnew, $this->id_apoderado->CurrentValue, NULL, $this->id_apoderado->ReadOnly);

			// id_referencia
			$this->id_referencia->SetDbValueDef($rsnew, $this->id_referencia->CurrentValue, NULL, $this->id_referencia->ReadOnly);

			// codigorude
			$this->codigorude->SetDbValueDef($rsnew, $this->codigorude->CurrentValue, NULL, $this->codigorude->ReadOnly);

			// codigorude_es
			$this->codigorude_es->SetDbValueDef($rsnew, $this->codigorude_es->CurrentValue, NULL, $this->codigorude_es->ReadOnly);

			// nrodiscapacidad
			$this->nrodiscapacidad->SetDbValueDef($rsnew, $this->nrodiscapacidad->CurrentValue, NULL, $this->nrodiscapacidad->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("escolarlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_unidadeducativa":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
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
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
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
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombrescompleto` AS `DispFld`, `nombrescentromedico` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombrescompleto`', "dx2" => '`nombrescentromedico`');
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
if (!isset($escolar_edit)) $escolar_edit = new cescolar_edit();

// Page init
$escolar_edit->Page_Init();

// Page main
$escolar_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$escolar_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fescolaredit = new ew_Form("fescolaredit", "edit");

// Validate form
fescolaredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->fecha->FldCaption(), $escolar->fecha->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($escolar->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unidadeducativa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->unidadeducativa->FldCaption(), $escolar->unidadeducativa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidopaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->apellidopaterno->FldCaption(), $escolar->apellidopaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidomaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->apellidomaterno->FldCaption(), $escolar->apellidomaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->nombres->FldCaption(), $escolar->nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ci");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->ci->FldCaption(), $escolar->ci->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechanacimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->fechanacimiento->FldCaption(), $escolar->fechanacimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechanacimiento");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($escolar->fechanacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->sexo->FldCaption(), $escolar->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_curso");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->curso->FldCaption(), $escolar->curso->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_discapacidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->id_discapacidad->FldCaption(), $escolar->id_discapacidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_resultado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $escolar->resultado->FldCaption(), $escolar->resultado->ReqErrMsg)) ?>");

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
fescolaredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fescolaredit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fescolaredit.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fescolaredit.Lists["x_unidadeducativa"].Data = "<?php echo $escolar_edit->unidadeducativa->LookupFilterQuery(FALSE, "edit") ?>";
fescolaredit.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolaredit.Lists["x_sexo"].Options = <?php echo json_encode($escolar_edit->sexo->Options()) ?>;
fescolaredit.Lists["x_id_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fescolaredit.Lists["x_id_discapacidad"].Data = "<?php echo $escolar_edit->id_discapacidad->LookupFilterQuery(FALSE, "edit") ?>";
fescolaredit.Lists["x_id_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
fescolaredit.Lists["x_id_tipodiscapacidad"].Data = "<?php echo $escolar_edit->id_tipodiscapacidad->LookupFilterQuery(FALSE, "edit") ?>";
fescolaredit.Lists["x_resultado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolaredit.Lists["x_resultado"].Options = <?php echo json_encode($escolar_edit->resultado->Options()) ?>;
fescolaredit.Lists["x_tapon"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolaredit.Lists["x_tapon"].Options = <?php echo json_encode($escolar_edit->tapon->Options()) ?>;
fescolaredit.Lists["x_tapodonde"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolaredit.Lists["x_tapodonde"].Options = <?php echo json_encode($escolar_edit->tapodonde->Options()) ?>;
fescolaredit.Lists["x_repetirprueba"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolaredit.Lists["x_repetirprueba"].Options = <?php echo json_encode($escolar_edit->repetirprueba->Options()) ?>;
fescolaredit.Lists["x_id_apoderado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"apoderado"};
fescolaredit.Lists["x_id_apoderado"].Data = "<?php echo $escolar_edit->id_apoderado->LookupFilterQuery(FALSE, "edit") ?>";
fescolaredit.Lists["x_id_referencia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombrescompleto","x_nombrescentromedico","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referencia"};
fescolaredit.Lists["x_id_referencia"].Data = "<?php echo $escolar_edit->id_referencia->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $escolar_edit->ShowPageHeader(); ?>
<?php
$escolar_edit->ShowMessage();
?>
<form name="fescolaredit" id="fescolaredit" class="<?php echo $escolar_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($escolar_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $escolar_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="escolar">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($escolar_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($escolar->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_escolar_id" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->id->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->id->CellAttributes() ?>>
<span id="el_escolar_id">
<span<?php echo $escolar->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $escolar->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="escolar" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($escolar->id->CurrentValue) ?>">
<?php echo $escolar->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_escolar_fecha" for="x_fecha" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->fecha->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->fecha->CellAttributes() ?>>
<span id="el_escolar_fecha">
<input type="text" data-table="escolar" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($escolar->fecha->getPlaceHolder()) ?>" value="<?php echo $escolar->fecha->EditValue ?>"<?php echo $escolar->fecha->EditAttributes() ?>>
<?php if (!$escolar->fecha->ReadOnly && !$escolar->fecha->Disabled && !isset($escolar->fecha->EditAttrs["readonly"]) && !isset($escolar->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fescolaredit", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $escolar->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->unidadeducativa->Visible) { // unidadeducativa ?>
	<div id="r_unidadeducativa" class="form-group">
		<label id="elh_escolar_unidadeducativa" for="x_unidadeducativa" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->unidadeducativa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->unidadeducativa->CellAttributes() ?>>
<span id="el_escolar_unidadeducativa">
<select data-table="escolar" data-field="x_unidadeducativa" data-value-separator="<?php echo $escolar->unidadeducativa->DisplayValueSeparatorAttribute() ?>" id="x_unidadeducativa" name="x_unidadeducativa"<?php echo $escolar->unidadeducativa->EditAttributes() ?>>
<?php echo $escolar->unidadeducativa->SelectOptionListHtml("x_unidadeducativa") ?>
</select>
</span>
<?php echo $escolar->unidadeducativa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="r_apellidopaterno" class="form-group">
		<label id="elh_escolar_apellidopaterno" for="x_apellidopaterno" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->apellidopaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->apellidopaterno->CellAttributes() ?>>
<span id="el_escolar_apellidopaterno">
<input type="text" data-table="escolar" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $escolar->apellidopaterno->EditValue ?>"<?php echo $escolar->apellidopaterno->EditAttributes() ?>>
</span>
<?php echo $escolar->apellidopaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="r_apellidomaterno" class="form-group">
		<label id="elh_escolar_apellidomaterno" for="x_apellidomaterno" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->apellidomaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->apellidomaterno->CellAttributes() ?>>
<span id="el_escolar_apellidomaterno">
<input type="text" data-table="escolar" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $escolar->apellidomaterno->EditValue ?>"<?php echo $escolar->apellidomaterno->EditAttributes() ?>>
</span>
<?php echo $escolar->apellidomaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->nombres->Visible) { // nombres ?>
	<div id="r_nombres" class="form-group">
		<label id="elh_escolar_nombres" for="x_nombres" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->nombres->CellAttributes() ?>>
<span id="el_escolar_nombres">
<input type="text" data-table="escolar" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->nombres->getPlaceHolder()) ?>" value="<?php echo $escolar->nombres->EditValue ?>"<?php echo $escolar->nombres->EditAttributes() ?>>
</span>
<?php echo $escolar->nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->ci->Visible) { // ci ?>
	<div id="r_ci" class="form-group">
		<label id="elh_escolar_ci" for="x_ci" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->ci->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->ci->CellAttributes() ?>>
<span id="el_escolar_ci">
<input type="text" data-table="escolar" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($escolar->ci->getPlaceHolder()) ?>" value="<?php echo $escolar->ci->EditValue ?>"<?php echo $escolar->ci->EditAttributes() ?>>
</span>
<?php echo $escolar->ci->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->fechanacimiento->Visible) { // fechanacimiento ?>
	<div id="r_fechanacimiento" class="form-group">
		<label id="elh_escolar_fechanacimiento" for="x_fechanacimiento" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->fechanacimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->fechanacimiento->CellAttributes() ?>>
<span id="el_escolar_fechanacimiento">
<input type="text" data-table="escolar" data-field="x_fechanacimiento" name="x_fechanacimiento" id="x_fechanacimiento" placeholder="<?php echo ew_HtmlEncode($escolar->fechanacimiento->getPlaceHolder()) ?>" value="<?php echo $escolar->fechanacimiento->EditValue ?>"<?php echo $escolar->fechanacimiento->EditAttributes() ?>>
<?php if (!$escolar->fechanacimiento->ReadOnly && !$escolar->fechanacimiento->Disabled && !isset($escolar->fechanacimiento->EditAttrs["readonly"]) && !isset($escolar->fechanacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fescolaredit", "x_fechanacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $escolar->fechanacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->sexo->Visible) { // sexo ?>
	<div id="r_sexo" class="form-group">
		<label id="elh_escolar_sexo" for="x_sexo" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->sexo->CellAttributes() ?>>
<span id="el_escolar_sexo">
<select data-table="escolar" data-field="x_sexo" data-value-separator="<?php echo $escolar->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $escolar->sexo->EditAttributes() ?>>
<?php echo $escolar->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</span>
<?php echo $escolar->sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->curso->Visible) { // curso ?>
	<div id="r_curso" class="form-group">
		<label id="elh_escolar_curso" for="x_curso" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->curso->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->curso->CellAttributes() ?>>
<span id="el_escolar_curso">
<input type="text" data-table="escolar" data-field="x_curso" name="x_curso" id="x_curso" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->curso->getPlaceHolder()) ?>" value="<?php echo $escolar->curso->EditValue ?>"<?php echo $escolar->curso->EditAttributes() ?>>
</span>
<?php echo $escolar->curso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->id_discapacidad->Visible) { // id_discapacidad ?>
	<div id="r_id_discapacidad" class="form-group">
		<label id="elh_escolar_id_discapacidad" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->id_discapacidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->id_discapacidad->CellAttributes() ?>>
<span id="el_escolar_id_discapacidad">
<div id="tp_x_id_discapacidad" class="ewTemplate"><input type="radio" data-table="escolar" data-field="x_id_discapacidad" data-value-separator="<?php echo $escolar->id_discapacidad->DisplayValueSeparatorAttribute() ?>" name="x_id_discapacidad" id="x_id_discapacidad" value="{value}"<?php echo $escolar->id_discapacidad->EditAttributes() ?>></div>
<div id="dsl_x_id_discapacidad" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $escolar->id_discapacidad->RadioButtonListHtml(FALSE, "x_id_discapacidad") ?>
</div></div>
</span>
<?php echo $escolar->id_discapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
	<div id="r_id_tipodiscapacidad" class="form-group">
		<label id="elh_escolar_id_tipodiscapacidad" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->id_tipodiscapacidad->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->id_tipodiscapacidad->CellAttributes() ?>>
<span id="el_escolar_id_tipodiscapacidad">
<div id="tp_x_id_tipodiscapacidad" class="ewTemplate"><input type="radio" data-table="escolar" data-field="x_id_tipodiscapacidad" data-value-separator="<?php echo $escolar->id_tipodiscapacidad->DisplayValueSeparatorAttribute() ?>" name="x_id_tipodiscapacidad" id="x_id_tipodiscapacidad" value="{value}"<?php echo $escolar->id_tipodiscapacidad->EditAttributes() ?>></div>
<div id="dsl_x_id_tipodiscapacidad" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $escolar->id_tipodiscapacidad->RadioButtonListHtml(FALSE, "x_id_tipodiscapacidad") ?>
</div></div>
</span>
<?php echo $escolar->id_tipodiscapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->resultado->Visible) { // resultado ?>
	<div id="r_resultado" class="form-group">
		<label id="elh_escolar_resultado" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->resultado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->resultado->CellAttributes() ?>>
<span id="el_escolar_resultado">
<div id="tp_x_resultado" class="ewTemplate"><input type="radio" data-table="escolar" data-field="x_resultado" data-value-separator="<?php echo $escolar->resultado->DisplayValueSeparatorAttribute() ?>" name="x_resultado" id="x_resultado" value="{value}"<?php echo $escolar->resultado->EditAttributes() ?>></div>
<div id="dsl_x_resultado" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $escolar->resultado->RadioButtonListHtml(FALSE, "x_resultado") ?>
</div></div>
</span>
<?php echo $escolar->resultado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->resultadotamizaje->Visible) { // resultadotamizaje ?>
	<div id="r_resultadotamizaje" class="form-group">
		<label id="elh_escolar_resultadotamizaje" for="x_resultadotamizaje" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->resultadotamizaje->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->resultadotamizaje->CellAttributes() ?>>
<span id="el_escolar_resultadotamizaje">
<input type="text" data-table="escolar" data-field="x_resultadotamizaje" name="x_resultadotamizaje" id="x_resultadotamizaje" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->resultadotamizaje->getPlaceHolder()) ?>" value="<?php echo $escolar->resultadotamizaje->EditValue ?>"<?php echo $escolar->resultadotamizaje->EditAttributes() ?>>
</span>
<?php echo $escolar->resultadotamizaje->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->tapon->Visible) { // tapon ?>
	<div id="r_tapon" class="form-group">
		<label id="elh_escolar_tapon" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->tapon->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->tapon->CellAttributes() ?>>
<span id="el_escolar_tapon">
<div id="tp_x_tapon" class="ewTemplate"><input type="radio" data-table="escolar" data-field="x_tapon" data-value-separator="<?php echo $escolar->tapon->DisplayValueSeparatorAttribute() ?>" name="x_tapon" id="x_tapon" value="{value}"<?php echo $escolar->tapon->EditAttributes() ?>></div>
<div id="dsl_x_tapon" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $escolar->tapon->RadioButtonListHtml(FALSE, "x_tapon") ?>
</div></div>
</span>
<?php echo $escolar->tapon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->tapodonde->Visible) { // tapodonde ?>
	<div id="r_tapodonde" class="form-group">
		<label id="elh_escolar_tapodonde" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->tapodonde->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->tapodonde->CellAttributes() ?>>
<span id="el_escolar_tapodonde">
<div id="tp_x_tapodonde" class="ewTemplate"><input type="radio" data-table="escolar" data-field="x_tapodonde" data-value-separator="<?php echo $escolar->tapodonde->DisplayValueSeparatorAttribute() ?>" name="x_tapodonde" id="x_tapodonde" value="{value}"<?php echo $escolar->tapodonde->EditAttributes() ?>></div>
<div id="dsl_x_tapodonde" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $escolar->tapodonde->RadioButtonListHtml(FALSE, "x_tapodonde") ?>
</div></div>
</span>
<?php echo $escolar->tapodonde->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->repetirprueba->Visible) { // repetirprueba ?>
	<div id="r_repetirprueba" class="form-group">
		<label id="elh_escolar_repetirprueba" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->repetirprueba->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->repetirprueba->CellAttributes() ?>>
<span id="el_escolar_repetirprueba">
<div id="tp_x_repetirprueba" class="ewTemplate"><input type="radio" data-table="escolar" data-field="x_repetirprueba" data-value-separator="<?php echo $escolar->repetirprueba->DisplayValueSeparatorAttribute() ?>" name="x_repetirprueba" id="x_repetirprueba" value="{value}"<?php echo $escolar->repetirprueba->EditAttributes() ?>></div>
<div id="dsl_x_repetirprueba" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $escolar->repetirprueba->RadioButtonListHtml(FALSE, "x_repetirprueba") ?>
</div></div>
</span>
<?php echo $escolar->repetirprueba->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_escolar_observaciones" for="x_observaciones" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->observaciones->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->observaciones->CellAttributes() ?>>
<span id="el_escolar_observaciones">
<input type="text" data-table="escolar" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($escolar->observaciones->getPlaceHolder()) ?>" value="<?php echo $escolar->observaciones->EditValue ?>"<?php echo $escolar->observaciones->EditAttributes() ?>>
</span>
<?php echo $escolar->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->id_apoderado->Visible) { // id_apoderado ?>
	<div id="r_id_apoderado" class="form-group">
		<label id="elh_escolar_id_apoderado" for="x_id_apoderado" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->id_apoderado->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->id_apoderado->CellAttributes() ?>>
<span id="el_escolar_id_apoderado">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_apoderado"><?php echo (strval($escolar->id_apoderado->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $escolar->id_apoderado->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($escolar->id_apoderado->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_apoderado',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($escolar->id_apoderado->ReadOnly || $escolar->id_apoderado->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="escolar" data-field="x_id_apoderado" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $escolar->id_apoderado->DisplayValueSeparatorAttribute() ?>" name="x_id_apoderado" id="x_id_apoderado" value="<?php echo $escolar->id_apoderado->CurrentValue ?>"<?php echo $escolar->id_apoderado->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "apoderado") && !$escolar->id_apoderado->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $escolar->id_apoderado->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_apoderado',url:'apoderadoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_apoderado"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $escolar->id_apoderado->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $escolar->id_apoderado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->id_referencia->Visible) { // id_referencia ?>
	<div id="r_id_referencia" class="form-group">
		<label id="elh_escolar_id_referencia" for="x_id_referencia" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->id_referencia->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->id_referencia->CellAttributes() ?>>
<span id="el_escolar_id_referencia">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_referencia"><?php echo (strval($escolar->id_referencia->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $escolar->id_referencia->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($escolar->id_referencia->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_referencia',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($escolar->id_referencia->ReadOnly || $escolar->id_referencia->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="escolar" data-field="x_id_referencia" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $escolar->id_referencia->DisplayValueSeparatorAttribute() ?>" name="x_id_referencia" id="x_id_referencia" value="<?php echo $escolar->id_referencia->CurrentValue ?>"<?php echo $escolar->id_referencia->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "referencia") && !$escolar->id_referencia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $escolar->id_referencia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_referencia',url:'referenciaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_referencia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $escolar->id_referencia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $escolar->id_referencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->codigorude->Visible) { // codigorude ?>
	<div id="r_codigorude" class="form-group">
		<label id="elh_escolar_codigorude" for="x_codigorude" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->codigorude->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->codigorude->CellAttributes() ?>>
<span id="el_escolar_codigorude">
<input type="text" data-table="escolar" data-field="x_codigorude" name="x_codigorude" id="x_codigorude" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->codigorude->getPlaceHolder()) ?>" value="<?php echo $escolar->codigorude->EditValue ?>"<?php echo $escolar->codigorude->EditAttributes() ?>>
</span>
<?php echo $escolar->codigorude->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->codigorude_es->Visible) { // codigorude_es ?>
	<div id="r_codigorude_es" class="form-group">
		<label id="elh_escolar_codigorude_es" for="x_codigorude_es" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->codigorude_es->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->codigorude_es->CellAttributes() ?>>
<span id="el_escolar_codigorude_es">
<input type="text" data-table="escolar" data-field="x_codigorude_es" name="x_codigorude_es" id="x_codigorude_es" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->codigorude_es->getPlaceHolder()) ?>" value="<?php echo $escolar->codigorude_es->EditValue ?>"<?php echo $escolar->codigorude_es->EditAttributes() ?>>
</span>
<?php echo $escolar->codigorude_es->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($escolar->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="r_nrodiscapacidad" class="form-group">
		<label id="elh_escolar_nrodiscapacidad" for="x_nrodiscapacidad" class="<?php echo $escolar_edit->LeftColumnClass ?>"><?php echo $escolar->nrodiscapacidad->FldCaption() ?></label>
		<div class="<?php echo $escolar_edit->RightColumnClass ?>"><div<?php echo $escolar->nrodiscapacidad->CellAttributes() ?>>
<span id="el_escolar_nrodiscapacidad">
<input type="text" data-table="escolar" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $escolar->nrodiscapacidad->EditValue ?>"<?php echo $escolar->nrodiscapacidad->EditAttributes() ?>>
</span>
<?php echo $escolar->nrodiscapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$escolar_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $escolar_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $escolar_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fescolaredit.Init();
</script>
<?php
$escolar_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$escolar_edit->Page_Terminate();
?>
