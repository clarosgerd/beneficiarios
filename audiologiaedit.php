<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "audiologiainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "pruebasaudiologiagridcls.php" ?>
<?php include_once "diagnosticoaudiologiagridcls.php" ?>
<?php include_once "tratamientogridcls.php" ?>
<?php include_once "derivaciongridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$audiologia_edit = NULL; // Initialize page object first

class caudiologia_edit extends caudiologia {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'audiologia';

	// Page object name
	var $PageObjName = 'audiologia_edit';

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

		// Table object (audiologia)
		if (!isset($GLOBALS["audiologia"]) || get_class($GLOBALS["audiologia"]) == "caudiologia") {
			$GLOBALS["audiologia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["audiologia"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'audiologia', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("audiologialist.php"));
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
		$this->id_especialista->SetVisibility();
		$this->especialidad->SetVisibility();
		$this->fecha->SetVisibility();
		$this->id_escolar->SetVisibility();
		$this->id_neonato->SetVisibility();
		$this->id_otros->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->id_atencion->SetVisibility();

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

			// Get the keys for master table
			$sDetailTblVar = $this->getCurrentDetailTable();
			if ($sDetailTblVar <> "") {
				$DetailTblVar = explode(",", $sDetailTblVar);
				if (in_array("pruebasaudiologia", $DetailTblVar)) {

					// Process auto fill for detail table 'pruebasaudiologia'
					if (preg_match('/^fpruebasaudiologia(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["pruebasaudiologia_grid"])) $GLOBALS["pruebasaudiologia_grid"] = new cpruebasaudiologia_grid;
						$GLOBALS["pruebasaudiologia_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
				if (in_array("diagnosticoaudiologia", $DetailTblVar)) {

					// Process auto fill for detail table 'diagnosticoaudiologia'
					if (preg_match('/^fdiagnosticoaudiologia(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["diagnosticoaudiologia_grid"])) $GLOBALS["diagnosticoaudiologia_grid"] = new cdiagnosticoaudiologia_grid;
						$GLOBALS["diagnosticoaudiologia_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
				if (in_array("tratamiento", $DetailTblVar)) {

					// Process auto fill for detail table 'tratamiento'
					if (preg_match('/^ftratamiento(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["tratamiento_grid"])) $GLOBALS["tratamiento_grid"] = new ctratamiento_grid;
						$GLOBALS["tratamiento_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
				if (in_array("derivacion", $DetailTblVar)) {

					// Process auto fill for detail table 'derivacion'
					if (preg_match('/^fderivacion(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["derivacion_grid"])) $GLOBALS["derivacion_grid"] = new cderivacion_grid;
						$GLOBALS["derivacion_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
			}
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
		global $EW_EXPORT, $audiologia;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($audiologia);
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
					if ($pageName == "audiologiaview.php")
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

			// Set up detail parameters
			$this->SetupDetailParms();
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
					$this->Page_Terminate("audiologialist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "audiologialist.php")
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

					// Set up detail parameters
					$this->SetupDetailParms();
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
		if (!$this->id_especialista->FldIsDetailKey) {
			$this->id_especialista->setFormValue($objForm->GetValue("x_id_especialista"));
		}
		if (!$this->especialidad->FldIsDetailKey) {
			$this->especialidad->setFormValue($objForm->GetValue("x_especialidad"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		}
		if (!$this->id_escolar->FldIsDetailKey) {
			$this->id_escolar->setFormValue($objForm->GetValue("x_id_escolar"));
		}
		if (!$this->id_neonato->FldIsDetailKey) {
			$this->id_neonato->setFormValue($objForm->GetValue("x_id_neonato"));
		}
		if (!$this->id_otros->FldIsDetailKey) {
			$this->id_otros->setFormValue($objForm->GetValue("x_id_otros"));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
		if (!$this->id_atencion->FldIsDetailKey) {
			$this->id_atencion->setFormValue($objForm->GetValue("x_id_atencion"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->id_especialista->CurrentValue = $this->id_especialista->FormValue;
		$this->especialidad->CurrentValue = $this->especialidad->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		$this->id_escolar->CurrentValue = $this->id_escolar->FormValue;
		$this->id_neonato->CurrentValue = $this->id_neonato->FormValue;
		$this->id_otros->CurrentValue = $this->id_otros->FormValue;
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
		$this->id_atencion->CurrentValue = $this->id_atencion->FormValue;
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
		$this->id_especialista->setDbValue($row['id_especialista']);
		$this->especialidad->setDbValue($row['especialidad']);
		$this->fecha->setDbValue($row['fecha']);
		$this->id_escolar->setDbValue($row['id_escolar']);
		$this->id_neonato->setDbValue($row['id_neonato']);
		$this->id_otros->setDbValue($row['id_otros']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_atencion->setDbValue($row['id_atencion']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_especialista'] = NULL;
		$row['especialidad'] = NULL;
		$row['fecha'] = NULL;
		$row['id_escolar'] = NULL;
		$row['id_neonato'] = NULL;
		$row['id_otros'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_atencion'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_especialista->DbValue = $row['id_especialista'];
		$this->especialidad->DbValue = $row['especialidad'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_escolar->DbValue = $row['id_escolar'];
		$this->id_neonato->DbValue = $row['id_neonato'];
		$this->id_otros->DbValue = $row['id_otros'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_atencion->DbValue = $row['id_atencion'];
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
		// id_especialista
		// especialidad
		// fecha
		// id_escolar
		// id_neonato
		// id_otros
		// observaciones
		// id_atencion
		// id_centro

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_especialista
		$this->id_especialista->ViewValue = $this->id_especialista->CurrentValue;
		if (strval($this->id_especialista->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_especialista->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `especialidad` AS `Disp4Fld` FROM `especialista`";
		$sWhereWrk = "";
		$this->id_especialista->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->id_especialista->ViewValue = $this->id_especialista->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_especialista->ViewValue = $this->id_especialista->CurrentValue;
			}
		} else {
			$this->id_especialista->ViewValue = NULL;
		}
		$this->id_especialista->ViewCustomAttributes = "";

		// especialidad
		$this->especialidad->ViewValue = $this->especialidad->CurrentValue;
		if (strval($this->especialidad->CurrentValue) <> "") {
			$sFilterWrk = "`nombre`" . ew_SearchString("=", $this->especialidad->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `nombre`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoespecialidad`";
		$sWhereWrk = "";
		$this->especialidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->especialidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->especialidad->ViewValue = $this->especialidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->especialidad->ViewValue = $this->especialidad->CurrentValue;
			}
		} else {
			$this->especialidad->ViewValue = NULL;
		}
		$this->especialidad->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// id_escolar
		if (strval($this->id_escolar->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_escolar->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `escolar`";
		$sWhereWrk = "";
		$this->id_escolar->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_escolar, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_escolar->ViewValue = $this->id_escolar->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_escolar->ViewValue = $this->id_escolar->CurrentValue;
			}
		} else {
			$this->id_escolar->ViewValue = NULL;
		}
		$this->id_escolar->ViewCustomAttributes = "";

		// id_neonato
		$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
		if (strval($this->id_neonato->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_neonato->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
		$sWhereWrk = "";
		$this->id_neonato->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
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

		// id_otros
		if (strval($this->id_otros->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_otros->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `otros`";
		$sWhereWrk = "";
		$this->id_otros->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_otros, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_otros->ViewValue = $this->id_otros->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_otros->ViewValue = $this->id_otros->CurrentValue;
			}
		} else {
			$this->id_otros->ViewValue = NULL;
		}
		$this->id_otros->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_atencion
		$this->id_atencion->ViewValue = $this->id_atencion->CurrentValue;
		$this->id_atencion->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_especialista
			$this->id_especialista->LinkCustomAttributes = "";
			$this->id_especialista->HrefValue = "";
			$this->id_especialista->TooltipValue = "";

			// especialidad
			$this->especialidad->LinkCustomAttributes = "";
			$this->especialidad->HrefValue = "";
			$this->especialidad->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// id_escolar
			$this->id_escolar->LinkCustomAttributes = "";
			$this->id_escolar->HrefValue = "";
			$this->id_escolar->TooltipValue = "";

			// id_neonato
			$this->id_neonato->LinkCustomAttributes = "";
			$this->id_neonato->HrefValue = "";
			$this->id_neonato->TooltipValue = "";

			// id_otros
			$this->id_otros->LinkCustomAttributes = "";
			$this->id_otros->HrefValue = "";
			$this->id_otros->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// id_atencion
			$this->id_atencion->LinkCustomAttributes = "";
			$this->id_atencion->HrefValue = "";
			$this->id_atencion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// id_especialista
			$this->id_especialista->EditAttrs["class"] = "form-control";
			$this->id_especialista->EditCustomAttributes = "";
			$this->id_especialista->EditValue = ew_HtmlEncode($this->id_especialista->CurrentValue);
			if (strval($this->id_especialista->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_especialista->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `especialidad` AS `Disp4Fld` FROM `especialista`";
			$sWhereWrk = "";
			$this->id_especialista->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$arwrk[4] = ew_HtmlEncode($rswrk->fields('Disp4Fld'));
					$this->id_especialista->EditValue = $this->id_especialista->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_especialista->EditValue = ew_HtmlEncode($this->id_especialista->CurrentValue);
				}
			} else {
				$this->id_especialista->EditValue = NULL;
			}
			$this->id_especialista->PlaceHolder = ew_RemoveHtml($this->id_especialista->FldCaption());

			// especialidad
			$this->especialidad->EditAttrs["class"] = "form-control";
			$this->especialidad->EditCustomAttributes = "";
			$this->especialidad->EditValue = ew_HtmlEncode($this->especialidad->CurrentValue);
			if (strval($this->especialidad->CurrentValue) <> "") {
				$sFilterWrk = "`nombre`" . ew_SearchString("=", $this->especialidad->CurrentValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `nombre`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoespecialidad`";
			$sWhereWrk = "";
			$this->especialidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->especialidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->especialidad->EditValue = $this->especialidad->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->especialidad->EditValue = ew_HtmlEncode($this->especialidad->CurrentValue);
				}
			} else {
				$this->especialidad->EditValue = NULL;
			}
			$this->especialidad->PlaceHolder = ew_RemoveHtml($this->especialidad->FldCaption());

			// fecha
			// id_escolar

			$this->id_escolar->EditAttrs["class"] = "form-control";
			$this->id_escolar->EditCustomAttributes = "";
			if (trim(strval($this->id_escolar->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_escolar->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `escolar`";
			$sWhereWrk = "";
			$this->id_escolar->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_escolar, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_escolar->EditValue = $arwrk;

			// id_neonato
			$this->id_neonato->EditAttrs["class"] = "form-control";
			$this->id_neonato->EditCustomAttributes = "";
			$this->id_neonato->EditValue = ew_HtmlEncode($this->id_neonato->CurrentValue);
			if (strval($this->id_neonato->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_neonato->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
			$sWhereWrk = "";
			$this->id_neonato->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->id_neonato->EditValue = $this->id_neonato->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_neonato->EditValue = ew_HtmlEncode($this->id_neonato->CurrentValue);
				}
			} else {
				$this->id_neonato->EditValue = NULL;
			}
			$this->id_neonato->PlaceHolder = ew_RemoveHtml($this->id_neonato->FldCaption());

			// id_otros
			$this->id_otros->EditAttrs["class"] = "form-control";
			$this->id_otros->EditCustomAttributes = "";
			if (trim(strval($this->id_otros->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_otros->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `otros`";
			$sWhereWrk = "";
			$this->id_otros->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_otros, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_otros->EditValue = $arwrk;

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// id_atencion
			$this->id_atencion->EditAttrs["class"] = "form-control";
			$this->id_atencion->EditCustomAttributes = "";
			$this->id_atencion->EditValue = ew_HtmlEncode($this->id_atencion->CurrentValue);
			$this->id_atencion->PlaceHolder = ew_RemoveHtml($this->id_atencion->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// id_especialista
			$this->id_especialista->LinkCustomAttributes = "";
			$this->id_especialista->HrefValue = "";

			// especialidad
			$this->especialidad->LinkCustomAttributes = "";
			$this->especialidad->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// id_escolar
			$this->id_escolar->LinkCustomAttributes = "";
			$this->id_escolar->HrefValue = "";

			// id_neonato
			$this->id_neonato->LinkCustomAttributes = "";
			$this->id_neonato->HrefValue = "";

			// id_otros
			$this->id_otros->LinkCustomAttributes = "";
			$this->id_otros->HrefValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";

			// id_atencion
			$this->id_atencion->LinkCustomAttributes = "";
			$this->id_atencion->HrefValue = "";
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
		if (!$this->id_especialista->FldIsDetailKey && !is_null($this->id_especialista->FormValue) && $this->id_especialista->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_especialista->FldCaption(), $this->id_especialista->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_especialista->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_especialista->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_neonato->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_neonato->FldErrMsg());
		}
		if (!ew_CheckInteger($this->observaciones->FormValue)) {
			ew_AddMessage($gsFormError, $this->observaciones->FldErrMsg());
		}
		if (!$this->id_atencion->FldIsDetailKey && !is_null($this->id_atencion->FormValue) && $this->id_atencion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_atencion->FldCaption(), $this->id_atencion->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_atencion->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_atencion->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("pruebasaudiologia", $DetailTblVar) && $GLOBALS["pruebasaudiologia"]->DetailEdit) {
			if (!isset($GLOBALS["pruebasaudiologia_grid"])) $GLOBALS["pruebasaudiologia_grid"] = new cpruebasaudiologia_grid(); // get detail page object
			$GLOBALS["pruebasaudiologia_grid"]->ValidateGridForm();
		}
		if (in_array("diagnosticoaudiologia", $DetailTblVar) && $GLOBALS["diagnosticoaudiologia"]->DetailEdit) {
			if (!isset($GLOBALS["diagnosticoaudiologia_grid"])) $GLOBALS["diagnosticoaudiologia_grid"] = new cdiagnosticoaudiologia_grid(); // get detail page object
			$GLOBALS["diagnosticoaudiologia_grid"]->ValidateGridForm();
		}
		if (in_array("tratamiento", $DetailTblVar) && $GLOBALS["tratamiento"]->DetailEdit) {
			if (!isset($GLOBALS["tratamiento_grid"])) $GLOBALS["tratamiento_grid"] = new ctratamiento_grid(); // get detail page object
			$GLOBALS["tratamiento_grid"]->ValidateGridForm();
		}
		if (in_array("derivacion", $DetailTblVar) && $GLOBALS["derivacion"]->DetailEdit) {
			if (!isset($GLOBALS["derivacion_grid"])) $GLOBALS["derivacion_grid"] = new cderivacion_grid(); // get detail page object
			$GLOBALS["derivacion_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// id_especialista
			$this->id_especialista->SetDbValueDef($rsnew, $this->id_especialista->CurrentValue, 0, $this->id_especialista->ReadOnly);

			// especialidad
			$this->especialidad->SetDbValueDef($rsnew, $this->especialidad->CurrentValue, NULL, $this->especialidad->ReadOnly);

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_CurrentDateTime(), ew_CurrentDate());
			$rsnew['fecha'] = &$this->fecha->DbValue;

			// id_escolar
			$this->id_escolar->SetDbValueDef($rsnew, $this->id_escolar->CurrentValue, NULL, $this->id_escolar->ReadOnly);

			// id_neonato
			$this->id_neonato->SetDbValueDef($rsnew, $this->id_neonato->CurrentValue, NULL, $this->id_neonato->ReadOnly);

			// id_otros
			$this->id_otros->SetDbValueDef($rsnew, $this->id_otros->CurrentValue, NULL, $this->id_otros->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

			// id_atencion
			$this->id_atencion->SetDbValueDef($rsnew, $this->id_atencion->CurrentValue, 0, $this->id_atencion->ReadOnly);

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

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("pruebasaudiologia", $DetailTblVar) && $GLOBALS["pruebasaudiologia"]->DetailEdit) {
						if (!isset($GLOBALS["pruebasaudiologia_grid"])) $GLOBALS["pruebasaudiologia_grid"] = new cpruebasaudiologia_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "pruebasaudiologia"); // Load user level of detail table
						$EditRow = $GLOBALS["pruebasaudiologia_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("diagnosticoaudiologia", $DetailTblVar) && $GLOBALS["diagnosticoaudiologia"]->DetailEdit) {
						if (!isset($GLOBALS["diagnosticoaudiologia_grid"])) $GLOBALS["diagnosticoaudiologia_grid"] = new cdiagnosticoaudiologia_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "diagnosticoaudiologia"); // Load user level of detail table
						$EditRow = $GLOBALS["diagnosticoaudiologia_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("tratamiento", $DetailTblVar) && $GLOBALS["tratamiento"]->DetailEdit) {
						if (!isset($GLOBALS["tratamiento_grid"])) $GLOBALS["tratamiento_grid"] = new ctratamiento_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "tratamiento"); // Load user level of detail table
						$EditRow = $GLOBALS["tratamiento_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("derivacion", $DetailTblVar) && $GLOBALS["derivacion"]->DetailEdit) {
						if (!isset($GLOBALS["derivacion_grid"])) $GLOBALS["derivacion_grid"] = new cderivacion_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "derivacion"); // Load user level of detail table
						$EditRow = $GLOBALS["derivacion_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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

	// Set up detail parms based on QueryString
	function SetupDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("pruebasaudiologia", $DetailTblVar)) {
				if (!isset($GLOBALS["pruebasaudiologia_grid"]))
					$GLOBALS["pruebasaudiologia_grid"] = new cpruebasaudiologia_grid;
				if ($GLOBALS["pruebasaudiologia_grid"]->DetailEdit) {
					$GLOBALS["pruebasaudiologia_grid"]->CurrentMode = "edit";
					$GLOBALS["pruebasaudiologia_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["pruebasaudiologia_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["pruebasaudiologia_grid"]->setStartRecordNumber(1);
					$GLOBALS["pruebasaudiologia_grid"]->id_audiologia->FldIsDetailKey = TRUE;
					$GLOBALS["pruebasaudiologia_grid"]->id_audiologia->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["pruebasaudiologia_grid"]->id_audiologia->setSessionValue($GLOBALS["pruebasaudiologia_grid"]->id_audiologia->CurrentValue);
				}
			}
			if (in_array("diagnosticoaudiologia", $DetailTblVar)) {
				if (!isset($GLOBALS["diagnosticoaudiologia_grid"]))
					$GLOBALS["diagnosticoaudiologia_grid"] = new cdiagnosticoaudiologia_grid;
				if ($GLOBALS["diagnosticoaudiologia_grid"]->DetailEdit) {
					$GLOBALS["diagnosticoaudiologia_grid"]->CurrentMode = "edit";
					$GLOBALS["diagnosticoaudiologia_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["diagnosticoaudiologia_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["diagnosticoaudiologia_grid"]->setStartRecordNumber(1);
					$GLOBALS["diagnosticoaudiologia_grid"]->id_audiologia->FldIsDetailKey = TRUE;
					$GLOBALS["diagnosticoaudiologia_grid"]->id_audiologia->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["diagnosticoaudiologia_grid"]->id_audiologia->setSessionValue($GLOBALS["diagnosticoaudiologia_grid"]->id_audiologia->CurrentValue);
				}
			}
			if (in_array("tratamiento", $DetailTblVar)) {
				if (!isset($GLOBALS["tratamiento_grid"]))
					$GLOBALS["tratamiento_grid"] = new ctratamiento_grid;
				if ($GLOBALS["tratamiento_grid"]->DetailEdit) {
					$GLOBALS["tratamiento_grid"]->CurrentMode = "edit";
					$GLOBALS["tratamiento_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["tratamiento_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["tratamiento_grid"]->setStartRecordNumber(1);
					$GLOBALS["tratamiento_grid"]->id_audiologia->FldIsDetailKey = TRUE;
					$GLOBALS["tratamiento_grid"]->id_audiologia->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["tratamiento_grid"]->id_audiologia->setSessionValue($GLOBALS["tratamiento_grid"]->id_audiologia->CurrentValue);
				}
			}
			if (in_array("derivacion", $DetailTblVar)) {
				if (!isset($GLOBALS["derivacion_grid"]))
					$GLOBALS["derivacion_grid"] = new cderivacion_grid;
				if ($GLOBALS["derivacion_grid"]->DetailEdit) {
					$GLOBALS["derivacion_grid"]->CurrentMode = "edit";
					$GLOBALS["derivacion_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["derivacion_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["derivacion_grid"]->setStartRecordNumber(1);
					$GLOBALS["derivacion_grid"]->id_audiologia->FldIsDetailKey = TRUE;
					$GLOBALS["derivacion_grid"]->id_audiologia->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["derivacion_grid"]->id_audiologia->setSessionValue($GLOBALS["derivacion_grid"]->id_audiologia->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("audiologialist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_especialista":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `especialidad` AS `Disp4Fld` FROM `especialista`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_especialidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `nombre` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoespecialidad`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`nombre` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->especialidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_escolar":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `escolar`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_escolar, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_neonato":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_otros":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `otros`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_otros, $sWhereWrk); // Call Lookup Selecting
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
		case "x_id_especialista":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `especialidad` AS `Disp4Fld` FROM `especialista`";
			$sWhereWrk = "`nombres` LIKE '{query_value}%' OR CONCAT(`nombres`,'" . ew_ValueSeparator(1, $this->id_especialista) . "',`apellidopaterno`,'" . ew_ValueSeparator(2, $this->id_especialista) . "',`apellidomaterno`,'" . ew_ValueSeparator(3, $this->id_especialista) . "',`especialidad`) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_especialidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `nombre`, `nombre` AS `DispFld` FROM `tipoespecialidad`";
			$sWhereWrk = "`nombre` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->especialidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_neonato":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld` FROM `neonatal`";
			$sWhereWrk = "`nombre` LIKE '{query_value}%' OR CONCAT(`nombre`,'" . ew_ValueSeparator(1, $this->id_neonato) . "',`apellidopaterno`,'" . ew_ValueSeparator(2, $this->id_neonato) . "',`apellidomaterno`) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($audiologia_edit)) $audiologia_edit = new caudiologia_edit();

// Page init
$audiologia_edit->Page_Init();

// Page main
$audiologia_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$audiologia_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = faudiologiaedit = new ew_Form("faudiologiaedit", "edit");

// Validate form
faudiologiaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_especialista");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $audiologia->id_especialista->FldCaption(), $audiologia->id_especialista->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_especialista");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->id_especialista->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_neonato");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->id_neonato->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_observaciones");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->observaciones->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_atencion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $audiologia->id_atencion->FldCaption(), $audiologia->id_atencion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_atencion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->id_atencion->FldErrMsg()) ?>");

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
faudiologiaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
faudiologiaedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
faudiologiaedit.Lists["x_id_especialista"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno","x_especialidad"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"especialista"};
faudiologiaedit.Lists["x_id_especialista"].Data = "<?php echo $audiologia_edit->id_especialista->LookupFilterQuery(FALSE, "edit") ?>";
faudiologiaedit.AutoSuggests["x_id_especialista"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_edit->id_especialista->LookupFilterQuery(TRUE, "edit"))) ?>;
faudiologiaedit.Lists["x_especialidad"] = {"LinkField":"x_nombre","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoespecialidad"};
faudiologiaedit.Lists["x_especialidad"].Data = "<?php echo $audiologia_edit->especialidad->LookupFilterQuery(FALSE, "edit") ?>";
faudiologiaedit.AutoSuggests["x_especialidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_edit->especialidad->LookupFilterQuery(TRUE, "edit"))) ?>;
faudiologiaedit.Lists["x_id_escolar"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"escolar"};
faudiologiaedit.Lists["x_id_escolar"].Data = "<?php echo $audiologia_edit->id_escolar->LookupFilterQuery(FALSE, "edit") ?>";
faudiologiaedit.Lists["x_id_neonato"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"neonatal"};
faudiologiaedit.Lists["x_id_neonato"].Data = "<?php echo $audiologia_edit->id_neonato->LookupFilterQuery(FALSE, "edit") ?>";
faudiologiaedit.AutoSuggests["x_id_neonato"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_edit->id_neonato->LookupFilterQuery(TRUE, "edit"))) ?>;
faudiologiaedit.Lists["x_id_otros"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"otros"};
faudiologiaedit.Lists["x_id_otros"].Data = "<?php echo $audiologia_edit->id_otros->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $audiologia_edit->ShowPageHeader(); ?>
<?php
$audiologia_edit->ShowMessage();
?>
<form name="faudiologiaedit" id="faudiologiaedit" class="<?php echo $audiologia_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($audiologia_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $audiologia_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="audiologia">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($audiologia_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($audiologia->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_audiologia_id" class="<?php echo $audiologia_edit->LeftColumnClass ?>"><?php echo $audiologia->id->FldCaption() ?></label>
		<div class="<?php echo $audiologia_edit->RightColumnClass ?>"><div<?php echo $audiologia->id->CellAttributes() ?>>
<span id="el_audiologia_id">
<span<?php echo $audiologia->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $audiologia->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="audiologia" data-field="x_id" data-page="1" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($audiologia->id->CurrentValue) ?>">
<?php echo $audiologia->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($audiologia->id_especialista->Visible) { // id_especialista ?>
	<div id="r_id_especialista" class="form-group">
		<label id="elh_audiologia_id_especialista" class="<?php echo $audiologia_edit->LeftColumnClass ?>"><?php echo $audiologia->id_especialista->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $audiologia_edit->RightColumnClass ?>"><div<?php echo $audiologia->id_especialista->CellAttributes() ?>>
<span id="el_audiologia_id_especialista">
<?php
$wrkonchange = trim(" " . @$audiologia->id_especialista->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$audiologia->id_especialista->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_especialista" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_especialista" id="sv_x_id_especialista" value="<?php echo $audiologia->id_especialista->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->id_especialista->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($audiologia->id_especialista->getPlaceHolder()) ?>"<?php echo $audiologia->id_especialista->EditAttributes() ?>>
</span>
<input type="hidden" data-table="audiologia" data-field="x_id_especialista" data-page="1" data-value-separator="<?php echo $audiologia->id_especialista->DisplayValueSeparatorAttribute() ?>" name="x_id_especialista" id="x_id_especialista" value="<?php echo ew_HtmlEncode($audiologia->id_especialista->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
faudiologiaedit.CreateAutoSuggest({"id":"x_id_especialista","forceSelect":false});
</script>
</span>
<?php echo $audiologia->id_especialista->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($audiologia->especialidad->Visible) { // especialidad ?>
	<div id="r_especialidad" class="form-group">
		<label id="elh_audiologia_especialidad" class="<?php echo $audiologia_edit->LeftColumnClass ?>"><?php echo $audiologia->especialidad->FldCaption() ?></label>
		<div class="<?php echo $audiologia_edit->RightColumnClass ?>"><div<?php echo $audiologia->especialidad->CellAttributes() ?>>
<span id="el_audiologia_especialidad">
<?php
$wrkonchange = trim(" " . @$audiologia->especialidad->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$audiologia->especialidad->EditAttrs["onchange"] = "";
?>
<span id="as_x_especialidad" style="white-space: nowrap; z-index: 8970">
	<input type="text" name="sv_x_especialidad" id="sv_x_especialidad" value="<?php echo $audiologia->especialidad->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->especialidad->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($audiologia->especialidad->getPlaceHolder()) ?>"<?php echo $audiologia->especialidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="audiologia" data-field="x_especialidad" data-page="1" data-value-separator="<?php echo $audiologia->especialidad->DisplayValueSeparatorAttribute() ?>" name="x_especialidad" id="x_especialidad" value="<?php echo ew_HtmlEncode($audiologia->especialidad->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
faudiologiaedit.CreateAutoSuggest({"id":"x_especialidad","forceSelect":false});
</script>
</span>
<?php echo $audiologia->especialidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($audiologia->id_escolar->Visible) { // id_escolar ?>
	<div id="r_id_escolar" class="form-group">
		<label id="elh_audiologia_id_escolar" for="x_id_escolar" class="<?php echo $audiologia_edit->LeftColumnClass ?>"><?php echo $audiologia->id_escolar->FldCaption() ?></label>
		<div class="<?php echo $audiologia_edit->RightColumnClass ?>"><div<?php echo $audiologia->id_escolar->CellAttributes() ?>>
<span id="el_audiologia_id_escolar">
<select data-table="audiologia" data-field="x_id_escolar" data-page="1" data-value-separator="<?php echo $audiologia->id_escolar->DisplayValueSeparatorAttribute() ?>" id="x_id_escolar" name="x_id_escolar"<?php echo $audiologia->id_escolar->EditAttributes() ?>>
<?php echo $audiologia->id_escolar->SelectOptionListHtml("x_id_escolar") ?>
</select>
</span>
<?php echo $audiologia->id_escolar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($audiologia->id_neonato->Visible) { // id_neonato ?>
	<div id="r_id_neonato" class="form-group">
		<label id="elh_audiologia_id_neonato" class="<?php echo $audiologia_edit->LeftColumnClass ?>"><?php echo $audiologia->id_neonato->FldCaption() ?></label>
		<div class="<?php echo $audiologia_edit->RightColumnClass ?>"><div<?php echo $audiologia->id_neonato->CellAttributes() ?>>
<span id="el_audiologia_id_neonato">
<?php
$wrkonchange = trim(" " . @$audiologia->id_neonato->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$audiologia->id_neonato->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_neonato" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_id_neonato" id="sv_x_id_neonato" value="<?php echo $audiologia->id_neonato->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->id_neonato->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($audiologia->id_neonato->getPlaceHolder()) ?>"<?php echo $audiologia->id_neonato->EditAttributes() ?>>
</span>
<input type="hidden" data-table="audiologia" data-field="x_id_neonato" data-page="1" data-value-separator="<?php echo $audiologia->id_neonato->DisplayValueSeparatorAttribute() ?>" name="x_id_neonato" id="x_id_neonato" value="<?php echo ew_HtmlEncode($audiologia->id_neonato->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
faudiologiaedit.CreateAutoSuggest({"id":"x_id_neonato","forceSelect":false});
</script>
</span>
<?php echo $audiologia->id_neonato->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($audiologia->id_otros->Visible) { // id_otros ?>
	<div id="r_id_otros" class="form-group">
		<label id="elh_audiologia_id_otros" for="x_id_otros" class="<?php echo $audiologia_edit->LeftColumnClass ?>"><?php echo $audiologia->id_otros->FldCaption() ?></label>
		<div class="<?php echo $audiologia_edit->RightColumnClass ?>"><div<?php echo $audiologia->id_otros->CellAttributes() ?>>
<span id="el_audiologia_id_otros">
<select data-table="audiologia" data-field="x_id_otros" data-page="1" data-value-separator="<?php echo $audiologia->id_otros->DisplayValueSeparatorAttribute() ?>" id="x_id_otros" name="x_id_otros"<?php echo $audiologia->id_otros->EditAttributes() ?>>
<?php echo $audiologia->id_otros->SelectOptionListHtml("x_id_otros") ?>
</select>
</span>
<?php echo $audiologia->id_otros->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($audiologia->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_audiologia_observaciones" for="x_observaciones" class="<?php echo $audiologia_edit->LeftColumnClass ?>"><?php echo $audiologia->observaciones->FldCaption() ?></label>
		<div class="<?php echo $audiologia_edit->RightColumnClass ?>"><div<?php echo $audiologia->observaciones->CellAttributes() ?>>
<span id="el_audiologia_observaciones">
<input type="text" data-table="audiologia" data-field="x_observaciones" data-page="1" name="x_observaciones" id="x_observaciones" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->observaciones->getPlaceHolder()) ?>" value="<?php echo $audiologia->observaciones->EditValue ?>"<?php echo $audiologia->observaciones->EditAttributes() ?>>
</span>
<?php echo $audiologia->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($audiologia->id_atencion->Visible) { // id_atencion ?>
	<div id="r_id_atencion" class="form-group">
		<label id="elh_audiologia_id_atencion" for="x_id_atencion" class="<?php echo $audiologia_edit->LeftColumnClass ?>"><?php echo $audiologia->id_atencion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $audiologia_edit->RightColumnClass ?>"><div<?php echo $audiologia->id_atencion->CellAttributes() ?>>
<span id="el_audiologia_id_atencion">
<input type="text" data-table="audiologia" data-field="x_id_atencion" data-page="1" name="x_id_atencion" id="x_id_atencion" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->id_atencion->getPlaceHolder()) ?>" value="<?php echo $audiologia->id_atencion->EditValue ?>"<?php echo $audiologia->id_atencion->EditAttributes() ?>>
</span>
<?php echo $audiologia->id_atencion->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php
	if (in_array("pruebasaudiologia", explode(",", $audiologia->getCurrentDetailTable())) && $pruebasaudiologia->DetailEdit) {
?>
<?php if ($audiologia->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("pruebasaudiologia", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "pruebasaudiologiagrid.php" ?>
<?php } ?>
<?php
	if (in_array("diagnosticoaudiologia", explode(",", $audiologia->getCurrentDetailTable())) && $diagnosticoaudiologia->DetailEdit) {
?>
<?php if ($audiologia->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("diagnosticoaudiologia", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "diagnosticoaudiologiagrid.php" ?>
<?php } ?>
<?php
	if (in_array("tratamiento", explode(",", $audiologia->getCurrentDetailTable())) && $tratamiento->DetailEdit) {
?>
<?php if ($audiologia->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("tratamiento", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "tratamientogrid.php" ?>
<?php } ?>
<?php
	if (in_array("derivacion", explode(",", $audiologia->getCurrentDetailTable())) && $derivacion->DetailEdit) {
?>
<?php if ($audiologia->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("derivacion", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "derivaciongrid.php" ?>
<?php } ?>
<?php if (!$audiologia_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $audiologia_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $audiologia_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
faudiologiaedit.Init();
</script>
<?php
$audiologia_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$audiologia_edit->Page_Terminate();
?>
