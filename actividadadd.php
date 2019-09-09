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

$actividad_add = NULL; // Initialize page object first

class cactividad_add extends cactividad {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'actividad';

	// Page object name
	var $PageObjName = 'actividad_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
					$this->Page_Terminate("actividadlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "actividadlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "actividadview.php")
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
		$this->id_sector->CurrentValue = NULL;
		$this->id_sector->OldValue = $this->id_sector->CurrentValue;
		$this->id_tipoactividad->CurrentValue = NULL;
		$this->id_tipoactividad->OldValue = $this->id_tipoactividad->CurrentValue;
		$this->organizador->CurrentValue = NULL;
		$this->organizador->OldValue = $this->organizador->CurrentValue;
		$this->nombreactividad->CurrentValue = NULL;
		$this->nombreactividad->OldValue = $this->nombreactividad->CurrentValue;
		$this->nombrelocal->CurrentValue = NULL;
		$this->nombrelocal->OldValue = $this->nombrelocal->CurrentValue;
		$this->direccionlocal->CurrentValue = NULL;
		$this->direccionlocal->OldValue = $this->direccionlocal->CurrentValue;
		$this->fecha_inicio->CurrentValue = NULL;
		$this->fecha_inicio->OldValue = $this->fecha_inicio->CurrentValue;
		$this->fecha_fin->CurrentValue = NULL;
		$this->fecha_fin->OldValue = $this->fecha_fin->CurrentValue;
		$this->horasprogramadas->CurrentValue = NULL;
		$this->horasprogramadas->OldValue = $this->horasprogramadas->CurrentValue;
		$this->id_persona->CurrentValue = NULL;
		$this->id_persona->OldValue = $this->id_persona->CurrentValue;
		$this->contenido->CurrentValue = NULL;
		$this->contenido->OldValue = $this->contenido->CurrentValue;
		$this->observaciones->CurrentValue = NULL;
		$this->observaciones->OldValue = $this->observaciones->CurrentValue;
		$this->id_centro->CurrentValue = SESSION["centro"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		if (!$this->id_centro->FldIsDetailKey) {
			$this->id_centro->setFormValue($objForm->GetValue("x_id_centro"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['id_sector'] = $this->id_sector->CurrentValue;
		$row['id_tipoactividad'] = $this->id_tipoactividad->CurrentValue;
		$row['organizador'] = $this->organizador->CurrentValue;
		$row['nombreactividad'] = $this->nombreactividad->CurrentValue;
		$row['nombrelocal'] = $this->nombrelocal->CurrentValue;
		$row['direccionlocal'] = $this->direccionlocal->CurrentValue;
		$row['fecha_inicio'] = $this->fecha_inicio->CurrentValue;
		$row['fecha_fin'] = $this->fecha_fin->CurrentValue;
		$row['horasprogramadas'] = $this->horasprogramadas->CurrentValue;
		$row['id_persona'] = $this->id_persona->CurrentValue;
		$row['contenido'] = $this->contenido->CurrentValue;
		$row['observaciones'] = $this->observaciones->CurrentValue;
		$row['id_centro'] = $this->id_centro->CurrentValue;
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
		$this->id_sector->LookupFilters = array();
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
		$sSqlWrk = "SELECT `id`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `centros`";
		$sWhereWrk = "";
		$this->organizador->LookupFilters = array();
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

		// id_centro
		if (strval($this->id_centro->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_centro->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `centros`";
		$sWhereWrk = "";
		$this->id_centro->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_centro, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_centro->ViewValue = $this->id_centro->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
			}
		} else {
			$this->id_centro->ViewValue = NULL;
		}
		$this->id_centro->ViewCustomAttributes = "";

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

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";
			$this->id_centro->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_sector
			$this->id_sector->EditAttrs["class"] = "form-control";
			$this->id_sector->EditCustomAttributes = "";
			if (trim(strval($this->id_sector->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_sector->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sector`";
			$sWhereWrk = "";
			$this->id_sector->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_sector, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
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
			$this->organizador->EditAttrs["class"] = "form-control";
			$this->organizador->EditCustomAttributes = "";
			if (trim(strval($this->organizador->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->organizador->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `centros`";
			$sWhereWrk = "";
			$this->organizador->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->organizador, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
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

			// id_centro
			$this->id_centro->EditAttrs["class"] = "form-control";
			$this->id_centro->EditCustomAttributes = "";
			if (trim(strval($this->id_centro->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_centro->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `centros`";
			$sWhereWrk = "";
			$this->id_centro->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_centro, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_centro->EditValue = $arwrk;

			// Add refer script
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
		if (!$this->id_centro->FldIsDetailKey && !is_null($this->id_centro->FormValue) && $this->id_centro->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_centro->FldCaption(), $this->id_centro->ReqErrMsg));
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

		// id_sector
		$this->id_sector->SetDbValueDef($rsnew, $this->id_sector->CurrentValue, 0, FALSE);

		// id_tipoactividad
		$this->id_tipoactividad->SetDbValueDef($rsnew, $this->id_tipoactividad->CurrentValue, 0, FALSE);

		// organizador
		$this->organizador->SetDbValueDef($rsnew, $this->organizador->CurrentValue, 0, FALSE);

		// nombreactividad
		$this->nombreactividad->SetDbValueDef($rsnew, $this->nombreactividad->CurrentValue, "", FALSE);

		// nombrelocal
		$this->nombrelocal->SetDbValueDef($rsnew, $this->nombrelocal->CurrentValue, "", FALSE);

		// direccionlocal
		$this->direccionlocal->SetDbValueDef($rsnew, $this->direccionlocal->CurrentValue, "", FALSE);

		// fecha_inicio
		$this->fecha_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// fecha_fin
		$this->fecha_fin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_fin->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// horasprogramadas
		$this->horasprogramadas->SetDbValueDef($rsnew, $this->horasprogramadas->CurrentValue, "", FALSE);

		// id_persona
		$this->id_persona->SetDbValueDef($rsnew, $this->id_persona->CurrentValue, 0, FALSE);

		// contenido
		$this->contenido->SetDbValueDef($rsnew, $this->contenido->CurrentValue, "", FALSE);

		// observaciones
		$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("actividadlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_sector":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sector`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
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
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `centros`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
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
		case "x_id_centro":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `centros`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_centro, $sWhereWrk); // Call Lookup Selecting
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
			$sWhereWrk = "`nombre` LIKE '{query_value}%' OR CONCAT(COALESCE(`nombre`, ''),'" . ew_ValueSeparator(1, $this->id_persona) . "',COALESCE(`apellidopaterno`,''),'" . ew_ValueSeparator(2, $this->id_persona) . "',COALESCE(`apellidomaterno`,'')) LIKE '{query_value}%'";
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
if (!isset($actividad_add)) $actividad_add = new cactividad_add();

// Page init
$actividad_add->Page_Init();

// Page main
$actividad_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$actividad_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = factividadadd = new ew_Form("factividadadd", "add");

// Validate form
factividadadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->id_centro->FldCaption(), $actividad->id_centro->ReqErrMsg)) ?>");

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
factividadadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factividadadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factividadadd.Lists["x_id_sector"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sector"};
factividadadd.Lists["x_id_sector"].Data = "<?php echo $actividad_add->id_sector->LookupFilterQuery(FALSE, "add") ?>";
factividadadd.Lists["x_id_tipoactividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoactividad"};
factividadadd.Lists["x_id_tipoactividad"].Data = "<?php echo $actividad_add->id_tipoactividad->LookupFilterQuery(FALSE, "add") ?>";
factividadadd.Lists["x_organizador"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreinstitucion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"centros"};
factividadadd.Lists["x_organizador"].Data = "<?php echo $actividad_add->organizador->LookupFilterQuery(FALSE, "add") ?>";
factividadadd.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
factividadadd.Lists["x_id_persona"].Data = "<?php echo $actividad_add->id_persona->LookupFilterQuery(FALSE, "add") ?>";
factividadadd.AutoSuggests["x_id_persona"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $actividad_add->id_persona->LookupFilterQuery(TRUE, "add"))) ?>;
factividadadd.Lists["x_id_centro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreinstitucion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"centros"};
factividadadd.Lists["x_id_centro"].Data = "<?php echo $actividad_add->id_centro->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $actividad_add->ShowPageHeader(); ?>
<?php
$actividad_add->ShowMessage();
?>
<form name="factividadadd" id="factividadadd" class="<?php echo $actividad_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($actividad_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $actividad_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="actividad">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($actividad_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($actividad->id_sector->Visible) { // id_sector ?>
	<div id="r_id_sector" class="form-group">
		<label id="elh_actividad_id_sector" for="x_id_sector" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->id_sector->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->id_sector->CellAttributes() ?>>
<span id="el_actividad_id_sector">
<select data-table="actividad" data-field="x_id_sector" data-value-separator="<?php echo $actividad->id_sector->DisplayValueSeparatorAttribute() ?>" id="x_id_sector" name="x_id_sector"<?php echo $actividad->id_sector->EditAttributes() ?>>
<?php echo $actividad->id_sector->SelectOptionListHtml("x_id_sector") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "sector") && !$actividad->id_sector->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $actividad->id_sector->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_sector',url:'sectoraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_sector"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $actividad->id_sector->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $actividad->id_sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->id_tipoactividad->Visible) { // id_tipoactividad ?>
	<div id="r_id_tipoactividad" class="form-group">
		<label id="elh_actividad_id_tipoactividad" for="x_id_tipoactividad" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->id_tipoactividad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->id_tipoactividad->CellAttributes() ?>>
<span id="el_actividad_id_tipoactividad">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_tipoactividad"><?php echo (strval($actividad->id_tipoactividad->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $actividad->id_tipoactividad->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($actividad->id_tipoactividad->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_tipoactividad',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($actividad->id_tipoactividad->ReadOnly || $actividad->id_tipoactividad->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="actividad" data-field="x_id_tipoactividad" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $actividad->id_tipoactividad->DisplayValueSeparatorAttribute() ?>" name="x_id_tipoactividad" id="x_id_tipoactividad" value="<?php echo $actividad->id_tipoactividad->CurrentValue ?>"<?php echo $actividad->id_tipoactividad->EditAttributes() ?>>
</span>
<?php echo $actividad->id_tipoactividad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->organizador->Visible) { // organizador ?>
	<div id="r_organizador" class="form-group">
		<label id="elh_actividad_organizador" for="x_organizador" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->organizador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->organizador->CellAttributes() ?>>
<span id="el_actividad_organizador">
<select data-table="actividad" data-field="x_organizador" data-value-separator="<?php echo $actividad->organizador->DisplayValueSeparatorAttribute() ?>" id="x_organizador" name="x_organizador"<?php echo $actividad->organizador->EditAttributes() ?>>
<?php echo $actividad->organizador->SelectOptionListHtml("x_organizador") ?>
</select>
</span>
<?php echo $actividad->organizador->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->nombreactividad->Visible) { // nombreactividad ?>
	<div id="r_nombreactividad" class="form-group">
		<label id="elh_actividad_nombreactividad" for="x_nombreactividad" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->nombreactividad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->nombreactividad->CellAttributes() ?>>
<span id="el_actividad_nombreactividad">
<input type="text" data-table="actividad" data-field="x_nombreactividad" name="x_nombreactividad" id="x_nombreactividad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->nombreactividad->getPlaceHolder()) ?>" value="<?php echo $actividad->nombreactividad->EditValue ?>"<?php echo $actividad->nombreactividad->EditAttributes() ?>>
</span>
<?php echo $actividad->nombreactividad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->nombrelocal->Visible) { // nombrelocal ?>
	<div id="r_nombrelocal" class="form-group">
		<label id="elh_actividad_nombrelocal" for="x_nombrelocal" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->nombrelocal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->nombrelocal->CellAttributes() ?>>
<span id="el_actividad_nombrelocal">
<input type="text" data-table="actividad" data-field="x_nombrelocal" name="x_nombrelocal" id="x_nombrelocal" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->nombrelocal->getPlaceHolder()) ?>" value="<?php echo $actividad->nombrelocal->EditValue ?>"<?php echo $actividad->nombrelocal->EditAttributes() ?>>
</span>
<?php echo $actividad->nombrelocal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->direccionlocal->Visible) { // direccionlocal ?>
	<div id="r_direccionlocal" class="form-group">
		<label id="elh_actividad_direccionlocal" for="x_direccionlocal" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->direccionlocal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->direccionlocal->CellAttributes() ?>>
<span id="el_actividad_direccionlocal">
<input type="text" data-table="actividad" data-field="x_direccionlocal" name="x_direccionlocal" id="x_direccionlocal" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->direccionlocal->getPlaceHolder()) ?>" value="<?php echo $actividad->direccionlocal->EditValue ?>"<?php echo $actividad->direccionlocal->EditAttributes() ?>>
</span>
<?php echo $actividad->direccionlocal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->fecha_inicio->Visible) { // fecha_inicio ?>
	<div id="r_fecha_inicio" class="form-group">
		<label id="elh_actividad_fecha_inicio" for="x_fecha_inicio" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->fecha_inicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->fecha_inicio->CellAttributes() ?>>
<span id="el_actividad_fecha_inicio">
<input type="text" data-table="actividad" data-field="x_fecha_inicio" name="x_fecha_inicio" id="x_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($actividad->fecha_inicio->getPlaceHolder()) ?>" value="<?php echo $actividad->fecha_inicio->EditValue ?>"<?php echo $actividad->fecha_inicio->EditAttributes() ?>>
<?php if (!$actividad->fecha_inicio->ReadOnly && !$actividad->fecha_inicio->Disabled && !isset($actividad->fecha_inicio->EditAttrs["readonly"]) && !isset($actividad->fecha_inicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factividadadd", "x_fecha_inicio", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $actividad->fecha_inicio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->fecha_fin->Visible) { // fecha_fin ?>
	<div id="r_fecha_fin" class="form-group">
		<label id="elh_actividad_fecha_fin" for="x_fecha_fin" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->fecha_fin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->fecha_fin->CellAttributes() ?>>
<span id="el_actividad_fecha_fin">
<input type="text" data-table="actividad" data-field="x_fecha_fin" name="x_fecha_fin" id="x_fecha_fin" placeholder="<?php echo ew_HtmlEncode($actividad->fecha_fin->getPlaceHolder()) ?>" value="<?php echo $actividad->fecha_fin->EditValue ?>"<?php echo $actividad->fecha_fin->EditAttributes() ?>>
<?php if (!$actividad->fecha_fin->ReadOnly && !$actividad->fecha_fin->Disabled && !isset($actividad->fecha_fin->EditAttrs["readonly"]) && !isset($actividad->fecha_fin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factividadadd", "x_fecha_fin", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $actividad->fecha_fin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->horasprogramadas->Visible) { // horasprogramadas ?>
	<div id="r_horasprogramadas" class="form-group">
		<label id="elh_actividad_horasprogramadas" for="x_horasprogramadas" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->horasprogramadas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->horasprogramadas->CellAttributes() ?>>
<span id="el_actividad_horasprogramadas">
<input type="text" data-table="actividad" data-field="x_horasprogramadas" name="x_horasprogramadas" id="x_horasprogramadas" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->horasprogramadas->getPlaceHolder()) ?>" value="<?php echo $actividad->horasprogramadas->EditValue ?>"<?php echo $actividad->horasprogramadas->EditAttributes() ?>>
</span>
<?php echo $actividad->horasprogramadas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->id_persona->Visible) { // id_persona ?>
	<div id="r_id_persona" class="form-group">
		<label id="elh_actividad_id_persona" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->id_persona->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->id_persona->CellAttributes() ?>>
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
factividadadd.CreateAutoSuggest({"id":"x_id_persona","forceSelect":false});
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
		<label id="elh_actividad_contenido" for="x_contenido" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->contenido->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->contenido->CellAttributes() ?>>
<span id="el_actividad_contenido">
<textarea data-table="actividad" data-field="x_contenido" name="x_contenido" id="x_contenido" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($actividad->contenido->getPlaceHolder()) ?>"<?php echo $actividad->contenido->EditAttributes() ?>><?php echo $actividad->contenido->EditValue ?></textarea>
</span>
<?php echo $actividad->contenido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_actividad_observaciones" for="x_observaciones" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->observaciones->FldCaption() ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->observaciones->CellAttributes() ?>>
<span id="el_actividad_observaciones">
<textarea data-table="actividad" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($actividad->observaciones->getPlaceHolder()) ?>"<?php echo $actividad->observaciones->EditAttributes() ?>><?php echo $actividad->observaciones->EditValue ?></textarea>
</span>
<?php echo $actividad->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($actividad->id_centro->Visible) { // id_centro ?>
	<div id="r_id_centro" class="form-group">
		<label id="elh_actividad_id_centro" for="x_id_centro" class="<?php echo $actividad_add->LeftColumnClass ?>"><?php echo $actividad->id_centro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $actividad_add->RightColumnClass ?>"><div<?php echo $actividad->id_centro->CellAttributes() ?>>
<span id="el_actividad_id_centro">
<select data-table="actividad" data-field="x_id_centro" data-value-separator="<?php echo $actividad->id_centro->DisplayValueSeparatorAttribute() ?>" id="x_id_centro" name="x_id_centro"<?php echo $actividad->id_centro->EditAttributes() ?>>
<?php echo $actividad->id_centro->SelectOptionListHtml("x_id_centro") ?>
</select>
</span>
<?php echo $actividad->id_centro->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$actividad_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $actividad_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $actividad_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
factividadadd.Init();
</script>
<?php
$actividad_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$actividad_add->Page_Terminate();
?>
