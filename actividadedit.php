<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "actividadinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$actividad_edit = NULL; // Initialize page object first

class cactividad_edit extends cactividad {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'actividad';

	// Page object name
	var $PageObjName = 'actividad_edit';

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

		// Table object (actividad)
		if (!isset($GLOBALS["actividad"]) || get_class($GLOBALS["actividad"]) == "cactividad") {
			$GLOBALS["actividad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["actividad"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'actividad', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("actividadlist.php"));
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
		$this->id_sector->SetVisibility();
		$this->id_tipoactividad->SetVisibility();
		$this->organizador->SetVisibility();
		$this->nombreactividad->SetVisibility();
		$this->nombrelocal->SetVisibility();
		$this->direccionlocal->SetVisibility();
		$this->fecha_inicio->SetVisibility();
		$this->fecha_fin->SetVisibility();
		$this->horasprogramadas->SetVisibility();
		$this->id_persona->SetVisibility();
		$this->contenido->SetVisibility();
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
		global $EW_EXPORT, $actividad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($actividad);
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
					if ($pageName == "actividadview.php")
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
					$this->Page_Terminate("actividadlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "actividadlist.php")
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
		if (!$this->id_sector->FldIsDetailKey) {
			$this->id_sector->setFormValue($objForm->GetValue("x_id_sector"));
		}
		if (!$this->id_tipoactividad->FldIsDetailKey) {
			$this->id_tipoactividad->setFormValue($objForm->GetValue("x_id_tipoactividad"));
		}
		if (!$this->organizador->FldIsDetailKey) {
			$this->organizador->setFormValue($objForm->GetValue("x_organizador"));
		}
		if (!$this->nombreactividad->FldIsDetailKey) {
			$this->nombreactividad->setFormValue($objForm->GetValue("x_nombreactividad"));
		}
		if (!$this->nombrelocal->FldIsDetailKey) {
			$this->nombrelocal->setFormValue($objForm->GetValue("x_nombrelocal"));
		}
		if (!$this->direccionlocal->FldIsDetailKey) {
			$this->direccionlocal->setFormValue($objForm->GetValue("x_direccionlocal"));
		}
		if (!$this->fecha_inicio->FldIsDetailKey) {
			$this->fecha_inicio->setFormValue($objForm->GetValue("x_fecha_inicio"));
			$this->fecha_inicio->CurrentValue = ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 0);
		}
		if (!$this->fecha_fin->FldIsDetailKey) {
			$this->fecha_fin->setFormValue($objForm->GetValue("x_fecha_fin"));
			$this->fecha_fin->CurrentValue = ew_UnFormatDateTime($this->fecha_fin->CurrentValue, 0);
		}
		if (!$this->horasprogramadas->FldIsDetailKey) {
			$this->horasprogramadas->setFormValue($objForm->GetValue("x_horasprogramadas"));
		}
		if (!$this->id_persona->FldIsDetailKey) {
			$this->id_persona->setFormValue($objForm->GetValue("x_id_persona"));
		}
		if (!$this->contenido->FldIsDetailKey) {
			$this->contenido->setFormValue($objForm->GetValue("x_contenido"));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->id_sector->CurrentValue = $this->id_sector->FormValue;
		$this->id_tipoactividad->CurrentValue = $this->id_tipoactividad->FormValue;
		$this->organizador->CurrentValue = $this->organizador->FormValue;
		$this->nombreactividad->CurrentValue = $this->nombreactividad->FormValue;
		$this->nombrelocal->CurrentValue = $this->nombrelocal->FormValue;
		$this->direccionlocal->CurrentValue = $this->direccionlocal->FormValue;
		$this->fecha_inicio->CurrentValue = $this->fecha_inicio->FormValue;
		$this->fecha_inicio->CurrentValue = ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 0);
		$this->fecha_fin->CurrentValue = $this->fecha_fin->FormValue;
		$this->fecha_fin->CurrentValue = ew_UnFormatDateTime($this->fecha_fin->CurrentValue, 0);
		$this->horasprogramadas->CurrentValue = $this->horasprogramadas->FormValue;
		$this->id_persona->CurrentValue = $this->id_persona->FormValue;
		$this->contenido->CurrentValue = $this->contenido->FormValue;
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
		$this->id_sector->setDbValue($row['id_sector']);
		$this->id_tipoactividad->setDbValue($row['id_tipoactividad']);
		$this->organizador->setDbValue($row['organizador']);
		$this->nombreactividad->setDbValue($row['nombreactividad']);
		$this->nombrelocal->setDbValue($row['nombrelocal']);
		$this->direccionlocal->setDbValue($row['direccionlocal']);
		$this->fecha_inicio->setDbValue($row['fecha_inicio']);
		$this->fecha_fin->setDbValue($row['fecha_fin']);
		$this->horasprogramadas->setDbValue($row['horasprogramadas']);
		$this->id_persona->setDbValue($row['id_persona']);
		if (array_key_exists('EV__id_persona', $rs->fields)) {
			$this->id_persona->VirtualValue = $rs->fields('EV__id_persona'); // Set up virtual field value
		} else {
			$this->id_persona->VirtualValue = ""; // Clear value
		}
		$this->contenido->setDbValue($row['contenido']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_sector'] = NULL;
		$row['id_tipoactividad'] = NULL;
		$row['organizador'] = NULL;
		$row['nombreactividad'] = NULL;
		$row['nombrelocal'] = NULL;
		$row['direccionlocal'] = NULL;
		$row['fecha_inicio'] = NULL;
		$row['fecha_fin'] = NULL;
		$row['horasprogramadas'] = NULL;
		$row['id_persona'] = NULL;
		$row['contenido'] = NULL;
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
		$this->id_sector->DbValue = $row['id_sector'];
		$this->id_tipoactividad->DbValue = $row['id_tipoactividad'];
		$this->organizador->DbValue = $row['organizador'];
		$this->nombreactividad->DbValue = $row['nombreactividad'];
		$this->nombrelocal->DbValue = $row['nombrelocal'];
		$this->direccionlocal->DbValue = $row['direccionlocal'];
		$this->fecha_inicio->DbValue = $row['fecha_inicio'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->horasprogramadas->DbValue = $row['horasprogramadas'];
		$this->id_persona->DbValue = $row['id_persona'];
		$this->contenido->DbValue = $row['contenido'];
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
		// id_sector
		// id_tipoactividad
		// organizador
		// nombreactividad
		// nombrelocal
		// direccionlocal
		// fecha_inicio
		// fecha_fin
		// horasprogramadas
		// id_persona
		// contenido
		// observaciones
		// id_centro

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_sector
		if (strval($this->id_sector->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_sector->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sector`";
		$sWhereWrk = "";
		$this->id_sector->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_sector, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_sector->ViewValue = $this->id_sector->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_sector->ViewValue = $this->id_sector->CurrentValue;
			}
		} else {
			$this->id_sector->ViewValue = NULL;
		}
		$this->id_sector->ViewCustomAttributes = "";

		// id_tipoactividad
		if (strval($this->id_tipoactividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipoactividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoactividad`";
		$sWhereWrk = "";
		$this->id_tipoactividad->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipoactividad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipoactividad->ViewValue = $this->id_tipoactividad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipoactividad->ViewValue = $this->id_tipoactividad->CurrentValue;
			}
		} else {
			$this->id_tipoactividad->ViewValue = NULL;
		}
		$this->id_tipoactividad->ViewCustomAttributes = "";

		// organizador
		if (strval($this->organizador->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->organizador->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->organizador->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->organizador, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->organizador->ViewValue = $this->organizador->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->organizador->ViewValue = $this->organizador->CurrentValue;
			}
		} else {
			$this->organizador->ViewValue = NULL;
		}
		$this->organizador->ViewCustomAttributes = "";

		// nombreactividad
		$this->nombreactividad->ViewValue = $this->nombreactividad->CurrentValue;
		$this->nombreactividad->ViewCustomAttributes = "";

		// nombrelocal
		$this->nombrelocal->ViewValue = $this->nombrelocal->CurrentValue;
		$this->nombrelocal->ViewCustomAttributes = "";

		// direccionlocal
		$this->direccionlocal->ViewValue = $this->direccionlocal->CurrentValue;
		$this->direccionlocal->ViewCustomAttributes = "";

		// fecha_inicio
		$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
		$this->fecha_inicio->ViewValue = ew_FormatDateTime($this->fecha_inicio->ViewValue, 0);
		$this->fecha_inicio->ViewCustomAttributes = "";

		// fecha_fin
		$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
		$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 0);
		$this->fecha_fin->ViewCustomAttributes = "";

		// horasprogramadas
		$this->horasprogramadas->ViewValue = $this->horasprogramadas->CurrentValue;
		$this->horasprogramadas->ViewCustomAttributes = "";

		// id_persona
		if ($this->id_persona->VirtualValue <> "") {
			$this->id_persona->ViewValue = $this->id_persona->VirtualValue;
		} else {
			$this->id_persona->ViewValue = $this->id_persona->CurrentValue;
		if (strval($this->id_persona->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `persona`";
		$sWhereWrk = "";
		$this->id_persona->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_persona->ViewValue = $this->id_persona->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_persona->ViewValue = $this->id_persona->CurrentValue;
			}
		} else {
			$this->id_persona->ViewValue = NULL;
		}
		}
		$this->id_persona->ViewCustomAttributes = "";

		// contenido
		$this->contenido->ViewValue = $this->contenido->CurrentValue;
		$this->contenido->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_sector
			$this->id_sector->LinkCustomAttributes = "";
			$this->id_sector->HrefValue = "";
			$this->id_sector->TooltipValue = "";

			// id_tipoactividad
			$this->id_tipoactividad->LinkCustomAttributes = "";
			$this->id_tipoactividad->HrefValue = "";
			$this->id_tipoactividad->TooltipValue = "";

			// organizador
			$this->organizador->LinkCustomAttributes = "";
			$this->organizador->HrefValue = "";
			$this->organizador->TooltipValue = "";

			// nombreactividad
			$this->nombreactividad->LinkCustomAttributes = "";
			$this->nombreactividad->HrefValue = "";
			$this->nombreactividad->TooltipValue = "";

			// nombrelocal
			$this->nombrelocal->LinkCustomAttributes = "";
			$this->nombrelocal->HrefValue = "";
			$this->nombrelocal->TooltipValue = "";

			// direccionlocal
			$this->direccionlocal->LinkCustomAttributes = "";
			$this->direccionlocal->HrefValue = "";
			$this->direccionlocal->TooltipValue = "";

			// fecha_inicio
			$this->fecha_inicio->LinkCustomAttributes = "";
			$this->fecha_inicio->HrefValue = "";
			$this->fecha_inicio->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

			// horasprogramadas
			$this->horasprogramadas->LinkCustomAttributes = "";
			$this->horasprogramadas->HrefValue = "";
			$this->horasprogramadas->TooltipValue = "";

			// id_persona
			$this->id_persona->LinkCustomAttributes = "";
			$this->id_persona->HrefValue = "";
			$this->id_persona->TooltipValue = "";

			// contenido
			$this->contenido->LinkCustomAttributes = "";
			$this->contenido->HrefValue = "";
			$this->contenido->TooltipValue = "";

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

			// id_sector
			$this->id_sector->EditCustomAttributes = "";
			if (trim(strval($this->id_sector->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_sector->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sector`";
			$sWhereWrk = "";
			$this->id_sector->LookupFilters = array("dx1" => '`nombre`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_sector, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_sector->ViewValue = $this->id_sector->DisplayValue($arwrk);
			} else {
				$this->id_sector->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_sector->EditValue = $arwrk;

			// id_tipoactividad
			$this->id_tipoactividad->EditCustomAttributes = "";
			if (trim(strval($this->id_tipoactividad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipoactividad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipoactividad`";
			$sWhereWrk = "";
			$this->id_tipoactividad->LookupFilters = array("dx1" => '`nombre`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_tipoactividad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_tipoactividad->ViewValue = $this->id_tipoactividad->DisplayValue($arwrk);
			} else {
				$this->id_tipoactividad->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_tipoactividad->EditValue = $arwrk;

			// organizador
			$this->organizador->EditCustomAttributes = "";
			if (trim(strval($this->organizador->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->organizador->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `unidadeducativa`";
			$sWhereWrk = "";
			$this->organizador->LookupFilters = array("dx1" => '`nombre`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->organizador, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->organizador->ViewValue = $this->organizador->DisplayValue($arwrk);
			} else {
				$this->organizador->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->organizador->EditValue = $arwrk;

			// nombreactividad
			$this->nombreactividad->EditAttrs["class"] = "form-control";
			$this->nombreactividad->EditCustomAttributes = "";
			$this->nombreactividad->EditValue = ew_HtmlEncode($this->nombreactividad->CurrentValue);
			$this->nombreactividad->PlaceHolder = ew_RemoveHtml($this->nombreactividad->FldCaption());

			// nombrelocal
			$this->nombrelocal->EditAttrs["class"] = "form-control";
			$this->nombrelocal->EditCustomAttributes = "";
			$this->nombrelocal->EditValue = ew_HtmlEncode($this->nombrelocal->CurrentValue);
			$this->nombrelocal->PlaceHolder = ew_RemoveHtml($this->nombrelocal->FldCaption());

			// direccionlocal
			$this->direccionlocal->EditAttrs["class"] = "form-control";
			$this->direccionlocal->EditCustomAttributes = "";
			$this->direccionlocal->EditValue = ew_HtmlEncode($this->direccionlocal->CurrentValue);
			$this->direccionlocal->PlaceHolder = ew_RemoveHtml($this->direccionlocal->FldCaption());

			// fecha_inicio
			$this->fecha_inicio->EditAttrs["class"] = "form-control";
			$this->fecha_inicio->EditCustomAttributes = "";
			$this->fecha_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_inicio->CurrentValue, 8));
			$this->fecha_inicio->PlaceHolder = ew_RemoveHtml($this->fecha_inicio->FldCaption());

			// fecha_fin
			$this->fecha_fin->EditAttrs["class"] = "form-control";
			$this->fecha_fin->EditCustomAttributes = "";
			$this->fecha_fin->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_fin->CurrentValue, 8));
			$this->fecha_fin->PlaceHolder = ew_RemoveHtml($this->fecha_fin->FldCaption());

			// horasprogramadas
			$this->horasprogramadas->EditAttrs["class"] = "form-control";
			$this->horasprogramadas->EditCustomAttributes = "";
			$this->horasprogramadas->EditValue = ew_HtmlEncode($this->horasprogramadas->CurrentValue);
			$this->horasprogramadas->PlaceHolder = ew_RemoveHtml($this->horasprogramadas->FldCaption());

			// id_persona
			$this->id_persona->EditAttrs["class"] = "form-control";
			$this->id_persona->EditCustomAttributes = "";
			$this->id_persona->EditValue = ew_HtmlEncode($this->id_persona->CurrentValue);
			$this->id_persona->PlaceHolder = ew_RemoveHtml($this->id_persona->FldCaption());

			// contenido
			$this->contenido->EditAttrs["class"] = "form-control";
			$this->contenido->EditCustomAttributes = "";
			$this->contenido->EditValue = ew_HtmlEncode($this->contenido->CurrentValue);
			$this->contenido->PlaceHolder = ew_RemoveHtml($this->contenido->FldCaption());

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// id_sector
			$this->id_sector->LinkCustomAttributes = "";
			$this->id_sector->HrefValue = "";

			// id_tipoactividad
			$this->id_tipoactividad->LinkCustomAttributes = "";
			$this->id_tipoactividad->HrefValue = "";

			// organizador
			$this->organizador->LinkCustomAttributes = "";
			$this->organizador->HrefValue = "";

			// nombreactividad
			$this->nombreactividad->LinkCustomAttributes = "";
			$this->nombreactividad->HrefValue = "";

			// nombrelocal
			$this->nombrelocal->LinkCustomAttributes = "";
			$this->nombrelocal->HrefValue = "";

			// direccionlocal
			$this->direccionlocal->LinkCustomAttributes = "";
			$this->direccionlocal->HrefValue = "";

			// fecha_inicio
			$this->fecha_inicio->LinkCustomAttributes = "";
			$this->fecha_inicio->HrefValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";

			// horasprogramadas
			$this->horasprogramadas->LinkCustomAttributes = "";
			$this->horasprogramadas->HrefValue = "";

			// id_persona
			$this->id_persona->LinkCustomAttributes = "";
			$this->id_persona->HrefValue = "";

			// contenido
			$this->contenido->LinkCustomAttributes = "";
			$this->contenido->HrefValue = "";

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
		if (!$this->id_sector->FldIsDetailKey && !is_null($this->id_sector->FormValue) && $this->id_sector->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_sector->FldCaption(), $this->id_sector->ReqErrMsg));
		}
		if (!$this->id_tipoactividad->FldIsDetailKey && !is_null($this->id_tipoactividad->FormValue) && $this->id_tipoactividad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_tipoactividad->FldCaption(), $this->id_tipoactividad->ReqErrMsg));
		}
		if (!$this->organizador->FldIsDetailKey && !is_null($this->organizador->FormValue) && $this->organizador->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->organizador->FldCaption(), $this->organizador->ReqErrMsg));
		}
		if (!$this->nombreactividad->FldIsDetailKey && !is_null($this->nombreactividad->FormValue) && $this->nombreactividad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombreactividad->FldCaption(), $this->nombreactividad->ReqErrMsg));
		}
		if (!$this->nombrelocal->FldIsDetailKey && !is_null($this->nombrelocal->FormValue) && $this->nombrelocal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombrelocal->FldCaption(), $this->nombrelocal->ReqErrMsg));
		}
		if (!$this->direccionlocal->FldIsDetailKey && !is_null($this->direccionlocal->FormValue) && $this->direccionlocal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->direccionlocal->FldCaption(), $this->direccionlocal->ReqErrMsg));
		}
		if (!$this->fecha_inicio->FldIsDetailKey && !is_null($this->fecha_inicio->FormValue) && $this->fecha_inicio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha_inicio->FldCaption(), $this->fecha_inicio->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->fecha_inicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_inicio->FldErrMsg());
		}
		if (!$this->fecha_fin->FldIsDetailKey && !is_null($this->fecha_fin->FormValue) && $this->fecha_fin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha_fin->FldCaption(), $this->fecha_fin->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->fecha_fin->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_fin->FldErrMsg());
		}
		if (!$this->horasprogramadas->FldIsDetailKey && !is_null($this->horasprogramadas->FormValue) && $this->horasprogramadas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->horasprogramadas->FldCaption(), $this->horasprogramadas->ReqErrMsg));
		}
		if (!$this->id_persona->FldIsDetailKey && !is_null($this->id_persona->FormValue) && $this->id_persona->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_persona->FldCaption(), $this->id_persona->ReqErrMsg));
		}
		if (!$this->contenido->FldIsDetailKey && !is_null($this->contenido->FormValue) && $this->contenido->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->contenido->FldCaption(), $this->contenido->ReqErrMsg));
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

			// id_sector
			$this->id_sector->SetDbValueDef($rsnew, $this->id_sector->CurrentValue, 0, $this->id_sector->ReadOnly);

			// id_tipoactividad
			$this->id_tipoactividad->SetDbValueDef($rsnew, $this->id_tipoactividad->CurrentValue, 0, $this->id_tipoactividad->ReadOnly);

			// organizador
			$this->organizador->SetDbValueDef($rsnew, $this->organizador->CurrentValue, 0, $this->organizador->ReadOnly);

			// nombreactividad
			$this->nombreactividad->SetDbValueDef($rsnew, $this->nombreactividad->CurrentValue, "", $this->nombreactividad->ReadOnly);

			// nombrelocal
			$this->nombrelocal->SetDbValueDef($rsnew, $this->nombrelocal->CurrentValue, "", $this->nombrelocal->ReadOnly);

			// direccionlocal
			$this->direccionlocal->SetDbValueDef($rsnew, $this->direccionlocal->CurrentValue, "", $this->direccionlocal->ReadOnly);

			// fecha_inicio
			$this->fecha_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 0), ew_CurrentDate(), $this->fecha_inicio->ReadOnly);

			// fecha_fin
			$this->fecha_fin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_fin->CurrentValue, 0), ew_CurrentDate(), $this->fecha_fin->ReadOnly);

			// horasprogramadas
			$this->horasprogramadas->SetDbValueDef($rsnew, $this->horasprogramadas->CurrentValue, "", $this->horasprogramadas->ReadOnly);

			// id_persona
			$this->id_persona->SetDbValueDef($rsnew, $this->id_persona->CurrentValue, 0, $this->id_persona->ReadOnly);

			// contenido
			$this->contenido->SetDbValueDef($rsnew, $this->contenido->CurrentValue, "", $this->contenido->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("actividadlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_sector":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sector`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_sector, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_tipoactividad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoactividad`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_tipoactividad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_organizador":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->organizador, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_persona":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `persona`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
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
		case "x_id_persona":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld` FROM `persona`";
			$sWhereWrk = "`nombre` LIKE '{query_value}%' OR CONCAT(`nombre`,'" . ew_ValueSeparator(1, $this->id_persona) . "',`apellidopaterno`,'" . ew_ValueSeparator(2, $this->id_persona) . "',`apellidomaterno`) LIKE '{query_value}%'";
			$fld->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($actividad_edit)) $actividad_edit = new cactividad_edit();

// Page init
$actividad_edit->Page_Init();

// Page main
$actividad_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$actividad_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = factividadedit = new ew_Form("factividadedit", "edit");

// Validate form
factividadedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_sector");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->id_sector->FldCaption(), $actividad->id_sector->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_tipoactividad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->id_tipoactividad->FldCaption(), $actividad->id_tipoactividad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_organizador");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->organizador->FldCaption(), $actividad->organizador->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombreactividad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->nombreactividad->FldCaption(), $actividad->nombreactividad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombrelocal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->nombrelocal->FldCaption(), $actividad->nombrelocal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccionlocal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->direccionlocal->FldCaption(), $actividad->direccionlocal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_inicio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->fecha_inicio->FldCaption(), $actividad->fecha_inicio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_inicio");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->fecha_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_fin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->fecha_fin->FldCaption(), $actividad->fecha_fin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_fin");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->fecha_fin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_horasprogramadas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->horasprogramadas->FldCaption(), $actividad->horasprogramadas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_persona");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->id_persona->FldCaption(), $actividad->id_persona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_contenido");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->contenido->FldCaption(), $actividad->contenido->ReqErrMsg)) ?>");

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
factividadedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factividadedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factividadedit.Lists["x_id_sector"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sector"};
factividadedit.Lists["x_id_sector"].Data = "<?php echo $actividad_edit->id_sector->LookupFilterQuery(FALSE, "edit") ?>";
factividadedit.Lists["x_id_tipoactividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoactividad"};
factividadedit.Lists["x_id_tipoactividad"].Data = "<?php echo $actividad_edit->id_tipoactividad->LookupFilterQuery(FALSE, "edit") ?>";
factividadedit.Lists["x_organizador"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
factividadedit.Lists["x_organizador"].Data = "<?php echo $actividad_edit->organizador->LookupFilterQuery(FALSE, "edit") ?>";
factividadedit.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
factividadedit.Lists["x_id_persona"].Data = "<?php echo $actividad_edit->id_persona->LookupFilterQuery(FALSE, "edit") ?>";
factividadedit.AutoSuggests["x_id_persona"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $actividad_edit->id_persona->LookupFilterQuery(TRUE, "edit"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $actividad_edit->ShowPageHeader(); ?>
<?php
$actividad_edit->ShowMessage();
?>
<form name="factividadedit" id="factividadedit" class="<?php echo $actividad_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($actividad_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $actividad_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="actividad">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($actividad_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($actividad->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_actividad_id" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->id->FldCaption() ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->id->CellAttributes() ?>>
<span id="el_actividad_id">
<span<?php echo $actividad->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($actividad->id->CurrentValue) ?>">
<?php echo $actividad->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->id_sector->Visible) { // id_sector ?>
	<div id="r_id_sector" class="form-group">
		<label id="elh_actividad_id_sector" for="x_id_sector" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->id_sector->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->id_sector->CellAttributes() ?>>
<span id="el_actividad_id_sector">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_sector"><?php echo (strval($actividad->id_sector->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $actividad->id_sector->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($actividad->id_sector->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_sector',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($actividad->id_sector->ReadOnly || $actividad->id_sector->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="actividad" data-field="x_id_sector" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $actividad->id_sector->DisplayValueSeparatorAttribute() ?>" name="x_id_sector" id="x_id_sector" value="<?php echo $actividad->id_sector->CurrentValue ?>"<?php echo $actividad->id_sector->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "sector") && !$actividad->id_sector->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $actividad->id_sector->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_sector',url:'sectoraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_sector"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $actividad->id_sector->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $actividad->id_sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->id_tipoactividad->Visible) { // id_tipoactividad ?>
	<div id="r_id_tipoactividad" class="form-group">
		<label id="elh_actividad_id_tipoactividad" for="x_id_tipoactividad" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->id_tipoactividad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->id_tipoactividad->CellAttributes() ?>>
<span id="el_actividad_id_tipoactividad">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_tipoactividad"><?php echo (strval($actividad->id_tipoactividad->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $actividad->id_tipoactividad->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($actividad->id_tipoactividad->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_tipoactividad',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($actividad->id_tipoactividad->ReadOnly || $actividad->id_tipoactividad->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="actividad" data-field="x_id_tipoactividad" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $actividad->id_tipoactividad->DisplayValueSeparatorAttribute() ?>" name="x_id_tipoactividad" id="x_id_tipoactividad" value="<?php echo $actividad->id_tipoactividad->CurrentValue ?>"<?php echo $actividad->id_tipoactividad->EditAttributes() ?>>
</span>
<?php echo $actividad->id_tipoactividad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->organizador->Visible) { // organizador ?>
	<div id="r_organizador" class="form-group">
		<label id="elh_actividad_organizador" for="x_organizador" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->organizador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->organizador->CellAttributes() ?>>
<span id="el_actividad_organizador">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_organizador"><?php echo (strval($actividad->organizador->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $actividad->organizador->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($actividad->organizador->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_organizador',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($actividad->organizador->ReadOnly || $actividad->organizador->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="actividad" data-field="x_organizador" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $actividad->organizador->DisplayValueSeparatorAttribute() ?>" name="x_organizador" id="x_organizador" value="<?php echo $actividad->organizador->CurrentValue ?>"<?php echo $actividad->organizador->EditAttributes() ?>>
</span>
<?php echo $actividad->organizador->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->nombreactividad->Visible) { // nombreactividad ?>
	<div id="r_nombreactividad" class="form-group">
		<label id="elh_actividad_nombreactividad" for="x_nombreactividad" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->nombreactividad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->nombreactividad->CellAttributes() ?>>
<span id="el_actividad_nombreactividad">
<input type="text" data-table="actividad" data-field="x_nombreactividad" name="x_nombreactividad" id="x_nombreactividad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->nombreactividad->getPlaceHolder()) ?>" value="<?php echo $actividad->nombreactividad->EditValue ?>"<?php echo $actividad->nombreactividad->EditAttributes() ?>>
</span>
<?php echo $actividad->nombreactividad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->nombrelocal->Visible) { // nombrelocal ?>
	<div id="r_nombrelocal" class="form-group">
		<label id="elh_actividad_nombrelocal" for="x_nombrelocal" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->nombrelocal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->nombrelocal->CellAttributes() ?>>
<span id="el_actividad_nombrelocal">
<input type="text" data-table="actividad" data-field="x_nombrelocal" name="x_nombrelocal" id="x_nombrelocal" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->nombrelocal->getPlaceHolder()) ?>" value="<?php echo $actividad->nombrelocal->EditValue ?>"<?php echo $actividad->nombrelocal->EditAttributes() ?>>
</span>
<?php echo $actividad->nombrelocal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->direccionlocal->Visible) { // direccionlocal ?>
	<div id="r_direccionlocal" class="form-group">
		<label id="elh_actividad_direccionlocal" for="x_direccionlocal" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->direccionlocal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->direccionlocal->CellAttributes() ?>>
<span id="el_actividad_direccionlocal">
<input type="text" data-table="actividad" data-field="x_direccionlocal" name="x_direccionlocal" id="x_direccionlocal" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->direccionlocal->getPlaceHolder()) ?>" value="<?php echo $actividad->direccionlocal->EditValue ?>"<?php echo $actividad->direccionlocal->EditAttributes() ?>>
</span>
<?php echo $actividad->direccionlocal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->fecha_inicio->Visible) { // fecha_inicio ?>
	<div id="r_fecha_inicio" class="form-group">
		<label id="elh_actividad_fecha_inicio" for="x_fecha_inicio" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->fecha_inicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->fecha_inicio->CellAttributes() ?>>
<span id="el_actividad_fecha_inicio">
<input type="text" data-table="actividad" data-field="x_fecha_inicio" name="x_fecha_inicio" id="x_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($actividad->fecha_inicio->getPlaceHolder()) ?>" value="<?php echo $actividad->fecha_inicio->EditValue ?>"<?php echo $actividad->fecha_inicio->EditAttributes() ?>>
<?php if (!$actividad->fecha_inicio->ReadOnly && !$actividad->fecha_inicio->Disabled && !isset($actividad->fecha_inicio->EditAttrs["readonly"]) && !isset($actividad->fecha_inicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factividadedit", "x_fecha_inicio", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $actividad->fecha_inicio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->fecha_fin->Visible) { // fecha_fin ?>
	<div id="r_fecha_fin" class="form-group">
		<label id="elh_actividad_fecha_fin" for="x_fecha_fin" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->fecha_fin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->fecha_fin->CellAttributes() ?>>
<span id="el_actividad_fecha_fin">
<input type="text" data-table="actividad" data-field="x_fecha_fin" name="x_fecha_fin" id="x_fecha_fin" placeholder="<?php echo ew_HtmlEncode($actividad->fecha_fin->getPlaceHolder()) ?>" value="<?php echo $actividad->fecha_fin->EditValue ?>"<?php echo $actividad->fecha_fin->EditAttributes() ?>>
<?php if (!$actividad->fecha_fin->ReadOnly && !$actividad->fecha_fin->Disabled && !isset($actividad->fecha_fin->EditAttrs["readonly"]) && !isset($actividad->fecha_fin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factividadedit", "x_fecha_fin", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $actividad->fecha_fin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->horasprogramadas->Visible) { // horasprogramadas ?>
	<div id="r_horasprogramadas" class="form-group">
		<label id="elh_actividad_horasprogramadas" for="x_horasprogramadas" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->horasprogramadas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->horasprogramadas->CellAttributes() ?>>
<span id="el_actividad_horasprogramadas">
<input type="text" data-table="actividad" data-field="x_horasprogramadas" name="x_horasprogramadas" id="x_horasprogramadas" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->horasprogramadas->getPlaceHolder()) ?>" value="<?php echo $actividad->horasprogramadas->EditValue ?>"<?php echo $actividad->horasprogramadas->EditAttributes() ?>>
</span>
<?php echo $actividad->horasprogramadas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->id_persona->Visible) { // id_persona ?>
	<div id="r_id_persona" class="form-group">
		<label id="elh_actividad_id_persona" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->id_persona->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->id_persona->CellAttributes() ?>>
<span id="el_actividad_id_persona">
<?php
$wrkonchange = trim(" " . @$actividad->id_persona->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$actividad->id_persona->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_persona" style="white-space: nowrap; z-index: 8890">
	<input type="text" name="sv_x_id_persona" id="sv_x_id_persona" value="<?php echo $actividad->id_persona->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->id_persona->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($actividad->id_persona->getPlaceHolder()) ?>"<?php echo $actividad->id_persona->EditAttributes() ?>>
</span>
<input type="hidden" data-table="actividad" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $actividad->id_persona->DisplayValueSeparatorAttribute() ?>" name="x_id_persona" id="x_id_persona" value="<?php echo ew_HtmlEncode($actividad->id_persona->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
factividadedit.CreateAutoSuggest({"id":"x_id_persona","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($actividad->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_persona',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($actividad->id_persona->ReadOnly || $actividad->id_persona->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<?php if (AllowAdd(CurrentProjectID() . "persona") && !$actividad->id_persona->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $actividad->id_persona->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_persona',url:'personaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_persona"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $actividad->id_persona->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $actividad->id_persona->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->contenido->Visible) { // contenido ?>
	<div id="r_contenido" class="form-group">
		<label id="elh_actividad_contenido" for="x_contenido" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->contenido->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->contenido->CellAttributes() ?>>
<span id="el_actividad_contenido">
<textarea data-table="actividad" data-field="x_contenido" name="x_contenido" id="x_contenido" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($actividad->contenido->getPlaceHolder()) ?>"<?php echo $actividad->contenido->EditAttributes() ?>><?php echo $actividad->contenido->EditValue ?></textarea>
</span>
<?php echo $actividad->contenido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_actividad_observaciones" for="x_observaciones" class="<?php echo $actividad_edit->LeftColumnClass ?>"><?php echo $actividad->observaciones->FldCaption() ?></label>
		<div class="<?php echo $actividad_edit->RightColumnClass ?>"><div<?php echo $actividad->observaciones->CellAttributes() ?>>
<span id="el_actividad_observaciones">
<textarea data-table="actividad" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($actividad->observaciones->getPlaceHolder()) ?>"<?php echo $actividad->observaciones->EditAttributes() ?>><?php echo $actividad->observaciones->EditValue ?></textarea>
</span>
<?php echo $actividad->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$actividad_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $actividad_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $actividad_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
factividadedit.Init();
</script>
<?php
$actividad_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$actividad_edit->Page_Terminate();
?>
