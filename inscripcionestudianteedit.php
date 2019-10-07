<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "inscripcionestudianteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$inscripcionestudiante_edit = NULL; // Initialize page object first

class cinscripcionestudiante_edit extends cinscripcionestudiante {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'inscripcionestudiante';

	// Page object name
	var $PageObjName = 'inscripcionestudiante_edit';

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

		// Table object (inscripcionestudiante)
		if (!isset($GLOBALS["inscripcionestudiante"]) || get_class($GLOBALS["inscripcionestudiante"]) == "cinscripcionestudiante") {
			$GLOBALS["inscripcionestudiante"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inscripcionestudiante"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inscripcionestudiante', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("inscripcionestudiantelist.php"));
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
		$this->id_estudiante->SetVisibility();
		$this->id_curso->SetVisibility();
		$this->id_gestion->SetVisibility();
		$this->fecha->SetVisibility();
		$this->esincritoespecial->SetVisibility();

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
		global $EW_EXPORT, $inscripcionestudiante;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inscripcionestudiante);
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
					if ($pageName == "inscripcionestudianteview.php")
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
			if ($objForm->HasValue("x_id_estudiante")) {
				$this->id_estudiante->setFormValue($objForm->GetValue("x_id_estudiante"));
			}
			if ($objForm->HasValue("x_id_curso")) {
				$this->id_curso->setFormValue($objForm->GetValue("x_id_curso"));
			}
			if ($objForm->HasValue("x_id_gestion")) {
				$this->id_gestion->setFormValue($objForm->GetValue("x_id_gestion"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id_estudiante"])) {
				$this->id_estudiante->setQueryStringValue($_GET["id_estudiante"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_estudiante->CurrentValue = NULL;
			}
			if (isset($_GET["id_curso"])) {
				$this->id_curso->setQueryStringValue($_GET["id_curso"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_curso->CurrentValue = NULL;
			}
			if (isset($_GET["id_gestion"])) {
				$this->id_gestion->setQueryStringValue($_GET["id_gestion"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_gestion->CurrentValue = NULL;
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
					$this->Page_Terminate("inscripcionestudiantelist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "inscripcionestudiantelist.php")
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
		if (!$this->id_estudiante->FldIsDetailKey) {
			$this->id_estudiante->setFormValue($objForm->GetValue("x_id_estudiante"));
		}
		if (!$this->id_curso->FldIsDetailKey) {
			$this->id_curso->setFormValue($objForm->GetValue("x_id_curso"));
		}
		if (!$this->id_gestion->FldIsDetailKey) {
			$this->id_gestion->setFormValue($objForm->GetValue("x_id_gestion"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		}
		if (!$this->esincritoespecial->FldIsDetailKey) {
			$this->esincritoespecial->setFormValue($objForm->GetValue("x_esincritoespecial"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_estudiante->CurrentValue = $this->id_estudiante->FormValue;
		$this->id_curso->CurrentValue = $this->id_curso->FormValue;
		$this->id_gestion->CurrentValue = $this->id_gestion->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		$this->esincritoespecial->CurrentValue = $this->esincritoespecial->FormValue;
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
		$this->id_estudiante->setDbValue($row['id_estudiante']);
		$this->id_curso->setDbValue($row['id_curso']);
		$this->id_gestion->setDbValue($row['id_gestion']);
		$this->fecha->setDbValue($row['fecha']);
		$this->esincritoespecial->setDbValue($row['esincritoespecial']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_estudiante'] = NULL;
		$row['id_curso'] = NULL;
		$row['id_gestion'] = NULL;
		$row['fecha'] = NULL;
		$row['esincritoespecial'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_estudiante->DbValue = $row['id_estudiante'];
		$this->id_curso->DbValue = $row['id_curso'];
		$this->id_gestion->DbValue = $row['id_gestion'];
		$this->fecha->DbValue = $row['fecha'];
		$this->esincritoespecial->DbValue = $row['esincritoespecial'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_estudiante")) <> "")
			$this->id_estudiante->CurrentValue = $this->getKey("id_estudiante"); // id_estudiante
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("id_curso")) <> "")
			$this->id_curso->CurrentValue = $this->getKey("id_curso"); // id_curso
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("id_gestion")) <> "")
			$this->id_gestion->CurrentValue = $this->getKey("id_gestion"); // id_gestion
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
		// id_estudiante
		// id_curso
		// id_gestion
		// fecha
		// esincritoespecial

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_estudiante
		if (strval($this->id_estudiante->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_estudiante->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estudiante`";
		$sWhereWrk = "";
		$this->id_estudiante->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_estudiante, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_estudiante->ViewValue = $this->id_estudiante->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_estudiante->ViewValue = $this->id_estudiante->CurrentValue;
			}
		} else {
			$this->id_estudiante->ViewValue = NULL;
		}
		$this->id_estudiante->ViewCustomAttributes = "";

		// id_curso
		if (strval($this->id_curso->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_curso->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `curso` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `curso`";
		$sWhereWrk = "";
		$this->id_curso->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_curso, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_curso->ViewValue = $this->id_curso->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_curso->ViewValue = $this->id_curso->CurrentValue;
			}
		} else {
			$this->id_curso->ViewValue = NULL;
		}
		$this->id_curso->ViewCustomAttributes = "";

		// id_gestion
		if (strval($this->id_gestion->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_gestion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `id` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gestion`";
		$sWhereWrk = "";
		$this->id_gestion->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_gestion, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_gestion->ViewValue = $this->id_gestion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_gestion->ViewValue = $this->id_gestion->CurrentValue;
			}
		} else {
			$this->id_gestion->ViewValue = NULL;
		}
		$this->id_gestion->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// esincritoespecial
		$this->esincritoespecial->ViewValue = $this->esincritoespecial->CurrentValue;
		$this->esincritoespecial->ViewCustomAttributes = "";

			// id_estudiante
			$this->id_estudiante->LinkCustomAttributes = "";
			$this->id_estudiante->HrefValue = "";
			$this->id_estudiante->TooltipValue = "";

			// id_curso
			$this->id_curso->LinkCustomAttributes = "";
			$this->id_curso->HrefValue = "";
			$this->id_curso->TooltipValue = "";

			// id_gestion
			$this->id_gestion->LinkCustomAttributes = "";
			$this->id_gestion->HrefValue = "";
			$this->id_gestion->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// esincritoespecial
			$this->esincritoespecial->LinkCustomAttributes = "";
			$this->esincritoespecial->HrefValue = "";
			$this->esincritoespecial->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_estudiante
			$this->id_estudiante->EditAttrs["class"] = "form-control";
			$this->id_estudiante->EditCustomAttributes = "";
			if (strval($this->id_estudiante->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_estudiante->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estudiante`";
			$sWhereWrk = "";
			$this->id_estudiante->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_estudiante, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$arwrk[2] = $rswrk->fields('Disp2Fld');
					$arwrk[3] = $rswrk->fields('Disp3Fld');
					$this->id_estudiante->EditValue = $this->id_estudiante->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_estudiante->EditValue = $this->id_estudiante->CurrentValue;
				}
			} else {
				$this->id_estudiante->EditValue = NULL;
			}
			$this->id_estudiante->ViewCustomAttributes = "";

			// id_curso
			$this->id_curso->EditAttrs["class"] = "form-control";
			$this->id_curso->EditCustomAttributes = "";
			if (strval($this->id_curso->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_curso->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `curso` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `curso`";
			$sWhereWrk = "";
			$this->id_curso->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_curso, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->id_curso->EditValue = $this->id_curso->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_curso->EditValue = $this->id_curso->CurrentValue;
				}
			} else {
				$this->id_curso->EditValue = NULL;
			}
			$this->id_curso->ViewCustomAttributes = "";

			// id_gestion
			$this->id_gestion->EditAttrs["class"] = "form-control";
			$this->id_gestion->EditCustomAttributes = "";
			if (strval($this->id_gestion->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_gestion->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `id` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gestion`";
			$sWhereWrk = "";
			$this->id_gestion->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_gestion, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->id_gestion->EditValue = $this->id_gestion->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_gestion->EditValue = $this->id_gestion->CurrentValue;
				}
			} else {
				$this->id_gestion->EditValue = NULL;
			}
			$this->id_gestion->ViewCustomAttributes = "";

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// esincritoespecial
			$this->esincritoespecial->EditAttrs["class"] = "form-control";
			$this->esincritoespecial->EditCustomAttributes = "";
			$this->esincritoespecial->EditValue = ew_HtmlEncode($this->esincritoespecial->CurrentValue);
			$this->esincritoespecial->PlaceHolder = ew_RemoveHtml($this->esincritoespecial->FldCaption());

			// Edit refer script
			// id_estudiante

			$this->id_estudiante->LinkCustomAttributes = "";
			$this->id_estudiante->HrefValue = "";

			// id_curso
			$this->id_curso->LinkCustomAttributes = "";
			$this->id_curso->HrefValue = "";

			// id_gestion
			$this->id_gestion->LinkCustomAttributes = "";
			$this->id_gestion->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// esincritoespecial
			$this->esincritoespecial->LinkCustomAttributes = "";
			$this->esincritoespecial->HrefValue = "";
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
		if (!$this->id_estudiante->FldIsDetailKey && !is_null($this->id_estudiante->FormValue) && $this->id_estudiante->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_estudiante->FldCaption(), $this->id_estudiante->ReqErrMsg));
		}
		if (!$this->id_curso->FldIsDetailKey && !is_null($this->id_curso->FormValue) && $this->id_curso->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_curso->FldCaption(), $this->id_curso->ReqErrMsg));
		}
		if (!$this->id_gestion->FldIsDetailKey && !is_null($this->id_gestion->FormValue) && $this->id_gestion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_gestion->FldCaption(), $this->id_gestion->ReqErrMsg));
		}
		if (!$this->fecha->FldIsDetailKey && !is_null($this->fecha->FormValue) && $this->fecha->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha->FldCaption(), $this->fecha->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckInteger($this->esincritoespecial->FormValue)) {
			ew_AddMessage($gsFormError, $this->esincritoespecial->FldErrMsg());
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

			// id_estudiante
			// id_curso
			// id_gestion
			// fecha

			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 0), ew_CurrentDate(), $this->fecha->ReadOnly);

			// esincritoespecial
			$this->esincritoespecial->SetDbValueDef($rsnew, $this->esincritoespecial->CurrentValue, 0, $this->esincritoespecial->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("inscripcionestudiantelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($inscripcionestudiante_edit)) $inscripcionestudiante_edit = new cinscripcionestudiante_edit();

// Page init
$inscripcionestudiante_edit->Page_Init();

// Page main
$inscripcionestudiante_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inscripcionestudiante_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = finscripcionestudianteedit = new ew_Form("finscripcionestudianteedit", "edit");

// Validate form
finscripcionestudianteedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_estudiante");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inscripcionestudiante->id_estudiante->FldCaption(), $inscripcionestudiante->id_estudiante->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_curso");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inscripcionestudiante->id_curso->FldCaption(), $inscripcionestudiante->id_curso->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_gestion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inscripcionestudiante->id_gestion->FldCaption(), $inscripcionestudiante->id_gestion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inscripcionestudiante->fecha->FldCaption(), $inscripcionestudiante->fecha->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inscripcionestudiante->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_esincritoespecial");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inscripcionestudiante->esincritoespecial->FldErrMsg()) ?>");

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
finscripcionestudianteedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finscripcionestudianteedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finscripcionestudianteedit.Lists["x_id_estudiante"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estudiante"};
finscripcionestudianteedit.Lists["x_id_estudiante"].Data = "<?php echo $inscripcionestudiante_edit->id_estudiante->LookupFilterQuery(FALSE, "edit") ?>";
finscripcionestudianteedit.Lists["x_id_curso"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_curso","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"curso"};
finscripcionestudianteedit.Lists["x_id_curso"].Data = "<?php echo $inscripcionestudiante_edit->id_curso->LookupFilterQuery(FALSE, "edit") ?>";
finscripcionestudianteedit.Lists["x_id_gestion"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_id","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestion"};
finscripcionestudianteedit.Lists["x_id_gestion"].Data = "<?php echo $inscripcionestudiante_edit->id_gestion->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $inscripcionestudiante_edit->ShowPageHeader(); ?>
<?php
$inscripcionestudiante_edit->ShowMessage();
?>
<form name="finscripcionestudianteedit" id="finscripcionestudianteedit" class="<?php echo $inscripcionestudiante_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inscripcionestudiante_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inscripcionestudiante_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inscripcionestudiante">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($inscripcionestudiante_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($inscripcionestudiante->id_estudiante->Visible) { // id_estudiante ?>
	<div id="r_id_estudiante" class="form-group">
		<label id="elh_inscripcionestudiante_id_estudiante" for="x_id_estudiante" class="<?php echo $inscripcionestudiante_edit->LeftColumnClass ?>"><?php echo $inscripcionestudiante->id_estudiante->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inscripcionestudiante_edit->RightColumnClass ?>"><div<?php echo $inscripcionestudiante->id_estudiante->CellAttributes() ?>>
<span id="el_inscripcionestudiante_id_estudiante">
<span<?php echo $inscripcionestudiante->id_estudiante->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inscripcionestudiante->id_estudiante->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inscripcionestudiante" data-field="x_id_estudiante" name="x_id_estudiante" id="x_id_estudiante" value="<?php echo ew_HtmlEncode($inscripcionestudiante->id_estudiante->CurrentValue) ?>">
<?php echo $inscripcionestudiante->id_estudiante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inscripcionestudiante->id_curso->Visible) { // id_curso ?>
	<div id="r_id_curso" class="form-group">
		<label id="elh_inscripcionestudiante_id_curso" for="x_id_curso" class="<?php echo $inscripcionestudiante_edit->LeftColumnClass ?>"><?php echo $inscripcionestudiante->id_curso->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inscripcionestudiante_edit->RightColumnClass ?>"><div<?php echo $inscripcionestudiante->id_curso->CellAttributes() ?>>
<span id="el_inscripcionestudiante_id_curso">
<span<?php echo $inscripcionestudiante->id_curso->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inscripcionestudiante->id_curso->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inscripcionestudiante" data-field="x_id_curso" name="x_id_curso" id="x_id_curso" value="<?php echo ew_HtmlEncode($inscripcionestudiante->id_curso->CurrentValue) ?>">
<?php echo $inscripcionestudiante->id_curso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inscripcionestudiante->id_gestion->Visible) { // id_gestion ?>
	<div id="r_id_gestion" class="form-group">
		<label id="elh_inscripcionestudiante_id_gestion" for="x_id_gestion" class="<?php echo $inscripcionestudiante_edit->LeftColumnClass ?>"><?php echo $inscripcionestudiante->id_gestion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inscripcionestudiante_edit->RightColumnClass ?>"><div<?php echo $inscripcionestudiante->id_gestion->CellAttributes() ?>>
<span id="el_inscripcionestudiante_id_gestion">
<span<?php echo $inscripcionestudiante->id_gestion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inscripcionestudiante->id_gestion->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inscripcionestudiante" data-field="x_id_gestion" name="x_id_gestion" id="x_id_gestion" value="<?php echo ew_HtmlEncode($inscripcionestudiante->id_gestion->CurrentValue) ?>">
<?php echo $inscripcionestudiante->id_gestion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inscripcionestudiante->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_inscripcionestudiante_fecha" for="x_fecha" class="<?php echo $inscripcionestudiante_edit->LeftColumnClass ?>"><?php echo $inscripcionestudiante->fecha->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inscripcionestudiante_edit->RightColumnClass ?>"><div<?php echo $inscripcionestudiante->fecha->CellAttributes() ?>>
<span id="el_inscripcionestudiante_fecha">
<input type="text" data-table="inscripcionestudiante" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($inscripcionestudiante->fecha->getPlaceHolder()) ?>" value="<?php echo $inscripcionestudiante->fecha->EditValue ?>"<?php echo $inscripcionestudiante->fecha->EditAttributes() ?>>
</span>
<?php echo $inscripcionestudiante->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inscripcionestudiante->esincritoespecial->Visible) { // esincritoespecial ?>
	<div id="r_esincritoespecial" class="form-group">
		<label id="elh_inscripcionestudiante_esincritoespecial" for="x_esincritoespecial" class="<?php echo $inscripcionestudiante_edit->LeftColumnClass ?>"><?php echo $inscripcionestudiante->esincritoespecial->FldCaption() ?></label>
		<div class="<?php echo $inscripcionestudiante_edit->RightColumnClass ?>"><div<?php echo $inscripcionestudiante->esincritoespecial->CellAttributes() ?>>
<span id="el_inscripcionestudiante_esincritoespecial">
<input type="text" data-table="inscripcionestudiante" data-field="x_esincritoespecial" name="x_esincritoespecial" id="x_esincritoespecial" size="30" placeholder="<?php echo ew_HtmlEncode($inscripcionestudiante->esincritoespecial->getPlaceHolder()) ?>" value="<?php echo $inscripcionestudiante->esincritoespecial->EditValue ?>"<?php echo $inscripcionestudiante->esincritoespecial->EditAttributes() ?>>
</span>
<?php echo $inscripcionestudiante->esincritoespecial->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$inscripcionestudiante_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $inscripcionestudiante_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $inscripcionestudiante_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
finscripcionestudianteedit.Init();
</script>
<?php
$inscripcionestudiante_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$inscripcionestudiante_edit->Page_Terminate();
?>
