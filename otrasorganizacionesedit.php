<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "otrasorganizacionesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$otrasorganizaciones_edit = NULL; // Initialize page object first

class cotrasorganizaciones_edit extends cotrasorganizaciones {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'otrasorganizaciones';

	// Page object name
	var $PageObjName = 'otrasorganizaciones_edit';

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

		// Table object (otrasorganizaciones)
		if (!isset($GLOBALS["otrasorganizaciones"]) || get_class($GLOBALS["otrasorganizaciones"]) == "cotrasorganizaciones") {
			$GLOBALS["otrasorganizaciones"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["otrasorganizaciones"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'otrasorganizaciones', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("otrasorganizacioneslist.php"));
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
		$this->nombre->SetVisibility();
		$this->departamento->SetVisibility();
		$this->ciudad->SetVisibility();
		$this->direccion->SetVisibility();
		$this->telefono->SetVisibility();
		$this->_email->SetVisibility();
		$this->id_persona->SetVisibility();

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
		global $EW_EXPORT, $otrasorganizaciones;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($otrasorganizaciones);
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
					if ($pageName == "otrasorganizacionesview.php")
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
					$this->Page_Terminate("otrasorganizacioneslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "otrasorganizacioneslist.php")
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
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->departamento->FldIsDetailKey) {
			$this->departamento->setFormValue($objForm->GetValue("x_departamento"));
		}
		if (!$this->ciudad->FldIsDetailKey) {
			$this->ciudad->setFormValue($objForm->GetValue("x_ciudad"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->id_persona->FldIsDetailKey) {
			$this->id_persona->setFormValue($objForm->GetValue("x_id_persona"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->departamento->CurrentValue = $this->departamento->FormValue;
		$this->ciudad->CurrentValue = $this->ciudad->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->id_persona->CurrentValue = $this->id_persona->FormValue;
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
		$this->nombre->setDbValue($row['nombre']);
		$this->departamento->setDbValue($row['departamento']);
		$this->ciudad->setDbValue($row['ciudad']);
		$this->direccion->setDbValue($row['direccion']);
		$this->telefono->setDbValue($row['telefono']);
		$this->_email->setDbValue($row['email']);
		$this->id_persona->setDbValue($row['id_persona']);
		if (array_key_exists('EV__id_persona', $rs->fields)) {
			$this->id_persona->VirtualValue = $rs->fields('EV__id_persona'); // Set up virtual field value
		} else {
			$this->id_persona->VirtualValue = ""; // Clear value
		}
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['nombre'] = NULL;
		$row['departamento'] = NULL;
		$row['ciudad'] = NULL;
		$row['direccion'] = NULL;
		$row['telefono'] = NULL;
		$row['email'] = NULL;
		$row['id_persona'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->nombre->DbValue = $row['nombre'];
		$this->departamento->DbValue = $row['departamento'];
		$this->ciudad->DbValue = $row['ciudad'];
		$this->direccion->DbValue = $row['direccion'];
		$this->telefono->DbValue = $row['telefono'];
		$this->_email->DbValue = $row['email'];
		$this->id_persona->DbValue = $row['id_persona'];
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
		// nombre
		// departamento
		// ciudad
		// direccion
		// telefono
		// email
		// id_persona

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// departamento
		$this->departamento->ViewValue = $this->departamento->CurrentValue;
		$this->departamento->ViewCustomAttributes = "";

		// ciudad
		if (strval($this->ciudad->CurrentValue) <> "") {
			$this->ciudad->ViewValue = $this->ciudad->OptionCaption($this->ciudad->CurrentValue);
		} else {
			$this->ciudad->ViewValue = NULL;
		}
		$this->ciudad->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// id_persona
		if ($this->id_persona->VirtualValue <> "") {
			$this->id_persona->ViewValue = $this->id_persona->VirtualValue;
		} else {
		if (strval($this->id_persona->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `persona`";
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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// departamento
			$this->departamento->LinkCustomAttributes = "";
			$this->departamento->HrefValue = "";
			$this->departamento->TooltipValue = "";

			// ciudad
			$this->ciudad->LinkCustomAttributes = "";
			$this->ciudad->HrefValue = "";
			$this->ciudad->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// id_persona
			$this->id_persona->LinkCustomAttributes = "";
			$this->id_persona->HrefValue = "";
			$this->id_persona->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// departamento
			$this->departamento->EditAttrs["class"] = "form-control";
			$this->departamento->EditCustomAttributes = "";
			$this->departamento->EditValue = ew_HtmlEncode($this->departamento->CurrentValue);
			$this->departamento->PlaceHolder = ew_RemoveHtml($this->departamento->FldCaption());

			// ciudad
			$this->ciudad->EditAttrs["class"] = "form-control";
			$this->ciudad->EditCustomAttributes = "";
			$this->ciudad->EditValue = $this->ciudad->Options(TRUE);

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// id_persona
			$this->id_persona->EditCustomAttributes = "";
			if (trim(strval($this->id_persona->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT DISTINCT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `persona`";
			$sWhereWrk = "";
			$this->id_persona->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->id_persona->ViewValue = $this->id_persona->DisplayValue($arwrk);
			} else {
				$this->id_persona->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_persona->EditValue = $arwrk;

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// departamento
			$this->departamento->LinkCustomAttributes = "";
			$this->departamento->HrefValue = "";

			// ciudad
			$this->ciudad->LinkCustomAttributes = "";
			$this->ciudad->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// id_persona
			$this->id_persona->LinkCustomAttributes = "";
			$this->id_persona->HrefValue = "";
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
		if (!$this->id->FldIsDetailKey && !is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id->FldCaption(), $this->id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id->FormValue)) {
			ew_AddMessage($gsFormError, $this->id->FldErrMsg());
		}
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->departamento->FldIsDetailKey && !is_null($this->departamento->FormValue) && $this->departamento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->departamento->FldCaption(), $this->departamento->ReqErrMsg));
		}
		if (!$this->ciudad->FldIsDetailKey && !is_null($this->ciudad->FormValue) && $this->ciudad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ciudad->FldCaption(), $this->ciudad->ReqErrMsg));
		}
		if (!$this->direccion->FldIsDetailKey && !is_null($this->direccion->FormValue) && $this->direccion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->direccion->FldCaption(), $this->direccion->ReqErrMsg));
		}
		if (!$this->telefono->FldIsDetailKey && !is_null($this->telefono->FormValue) && $this->telefono->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->telefono->FldCaption(), $this->telefono->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
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

			// id
			// nombre

			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", $this->nombre->ReadOnly);

			// departamento
			$this->departamento->SetDbValueDef($rsnew, $this->departamento->CurrentValue, "", $this->departamento->ReadOnly);

			// ciudad
			$this->ciudad->SetDbValueDef($rsnew, $this->ciudad->CurrentValue, "", $this->ciudad->ReadOnly);

			// direccion
			$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, "", $this->direccion->ReadOnly);

			// telefono
			$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, "", $this->telefono->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// id_persona
			$this->id_persona->SetDbValueDef($rsnew, $this->id_persona->CurrentValue, NULL, $this->id_persona->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("otrasorganizacioneslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_persona":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT DISTINCT `id` AS `LinkFld`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `persona`";
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
if (!isset($otrasorganizaciones_edit)) $otrasorganizaciones_edit = new cotrasorganizaciones_edit();

// Page init
$otrasorganizaciones_edit->Page_Init();

// Page main
$otrasorganizaciones_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$otrasorganizaciones_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fotrasorganizacionesedit = new ew_Form("fotrasorganizacionesedit", "edit");

// Validate form
fotrasorganizacionesedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otrasorganizaciones->id->FldCaption(), $otrasorganizaciones->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($otrasorganizaciones->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otrasorganizaciones->nombre->FldCaption(), $otrasorganizaciones->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_departamento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otrasorganizaciones->departamento->FldCaption(), $otrasorganizaciones->departamento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ciudad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otrasorganizaciones->ciudad->FldCaption(), $otrasorganizaciones->ciudad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otrasorganizaciones->direccion->FldCaption(), $otrasorganizaciones->direccion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telefono");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otrasorganizaciones->telefono->FldCaption(), $otrasorganizaciones->telefono->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $otrasorganizaciones->_email->FldCaption(), $otrasorganizaciones->_email->ReqErrMsg)) ?>");

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
fotrasorganizacionesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fotrasorganizacionesedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fotrasorganizacionesedit.Lists["x_ciudad"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fotrasorganizacionesedit.Lists["x_ciudad"].Options = <?php echo json_encode($otrasorganizaciones_edit->ciudad->Options()) ?>;
fotrasorganizacionesedit.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
fotrasorganizacionesedit.Lists["x_id_persona"].Data = "<?php echo $otrasorganizaciones_edit->id_persona->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $otrasorganizaciones_edit->ShowPageHeader(); ?>
<?php
$otrasorganizaciones_edit->ShowMessage();
?>
<form name="fotrasorganizacionesedit" id="fotrasorganizacionesedit" class="<?php echo $otrasorganizaciones_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($otrasorganizaciones_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $otrasorganizaciones_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="otrasorganizaciones">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($otrasorganizaciones_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($otrasorganizaciones->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_otrasorganizaciones_id" for="x_id" class="<?php echo $otrasorganizaciones_edit->LeftColumnClass ?>"><?php echo $otrasorganizaciones->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otrasorganizaciones_edit->RightColumnClass ?>"><div<?php echo $otrasorganizaciones->id->CellAttributes() ?>>
<span id="el_otrasorganizaciones_id">
<span<?php echo $otrasorganizaciones->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $otrasorganizaciones->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="otrasorganizaciones" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($otrasorganizaciones->id->CurrentValue) ?>">
<?php echo $otrasorganizaciones->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otrasorganizaciones->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_otrasorganizaciones_nombre" for="x_nombre" class="<?php echo $otrasorganizaciones_edit->LeftColumnClass ?>"><?php echo $otrasorganizaciones->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otrasorganizaciones_edit->RightColumnClass ?>"><div<?php echo $otrasorganizaciones->nombre->CellAttributes() ?>>
<span id="el_otrasorganizaciones_nombre">
<input type="text" data-table="otrasorganizaciones" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otrasorganizaciones->nombre->getPlaceHolder()) ?>" value="<?php echo $otrasorganizaciones->nombre->EditValue ?>"<?php echo $otrasorganizaciones->nombre->EditAttributes() ?>>
</span>
<?php echo $otrasorganizaciones->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otrasorganizaciones->departamento->Visible) { // departamento ?>
	<div id="r_departamento" class="form-group">
		<label id="elh_otrasorganizaciones_departamento" for="x_departamento" class="<?php echo $otrasorganizaciones_edit->LeftColumnClass ?>"><?php echo $otrasorganizaciones->departamento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otrasorganizaciones_edit->RightColumnClass ?>"><div<?php echo $otrasorganizaciones->departamento->CellAttributes() ?>>
<span id="el_otrasorganizaciones_departamento">
<input type="text" data-table="otrasorganizaciones" data-field="x_departamento" name="x_departamento" id="x_departamento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otrasorganizaciones->departamento->getPlaceHolder()) ?>" value="<?php echo $otrasorganizaciones->departamento->EditValue ?>"<?php echo $otrasorganizaciones->departamento->EditAttributes() ?>>
</span>
<?php echo $otrasorganizaciones->departamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otrasorganizaciones->ciudad->Visible) { // ciudad ?>
	<div id="r_ciudad" class="form-group">
		<label id="elh_otrasorganizaciones_ciudad" for="x_ciudad" class="<?php echo $otrasorganizaciones_edit->LeftColumnClass ?>"><?php echo $otrasorganizaciones->ciudad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otrasorganizaciones_edit->RightColumnClass ?>"><div<?php echo $otrasorganizaciones->ciudad->CellAttributes() ?>>
<span id="el_otrasorganizaciones_ciudad">
<select data-table="otrasorganizaciones" data-field="x_ciudad" data-value-separator="<?php echo $otrasorganizaciones->ciudad->DisplayValueSeparatorAttribute() ?>" id="x_ciudad" name="x_ciudad"<?php echo $otrasorganizaciones->ciudad->EditAttributes() ?>>
<?php echo $otrasorganizaciones->ciudad->SelectOptionListHtml("x_ciudad") ?>
</select>
</span>
<?php echo $otrasorganizaciones->ciudad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otrasorganizaciones->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_otrasorganizaciones_direccion" for="x_direccion" class="<?php echo $otrasorganizaciones_edit->LeftColumnClass ?>"><?php echo $otrasorganizaciones->direccion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otrasorganizaciones_edit->RightColumnClass ?>"><div<?php echo $otrasorganizaciones->direccion->CellAttributes() ?>>
<span id="el_otrasorganizaciones_direccion">
<input type="text" data-table="otrasorganizaciones" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otrasorganizaciones->direccion->getPlaceHolder()) ?>" value="<?php echo $otrasorganizaciones->direccion->EditValue ?>"<?php echo $otrasorganizaciones->direccion->EditAttributes() ?>>
</span>
<?php echo $otrasorganizaciones->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otrasorganizaciones->telefono->Visible) { // telefono ?>
	<div id="r_telefono" class="form-group">
		<label id="elh_otrasorganizaciones_telefono" for="x_telefono" class="<?php echo $otrasorganizaciones_edit->LeftColumnClass ?>"><?php echo $otrasorganizaciones->telefono->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otrasorganizaciones_edit->RightColumnClass ?>"><div<?php echo $otrasorganizaciones->telefono->CellAttributes() ?>>
<span id="el_otrasorganizaciones_telefono">
<input type="text" data-table="otrasorganizaciones" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otrasorganizaciones->telefono->getPlaceHolder()) ?>" value="<?php echo $otrasorganizaciones->telefono->EditValue ?>"<?php echo $otrasorganizaciones->telefono->EditAttributes() ?>>
</span>
<?php echo $otrasorganizaciones->telefono->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otrasorganizaciones->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_otrasorganizaciones__email" for="x__email" class="<?php echo $otrasorganizaciones_edit->LeftColumnClass ?>"><?php echo $otrasorganizaciones->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $otrasorganizaciones_edit->RightColumnClass ?>"><div<?php echo $otrasorganizaciones->_email->CellAttributes() ?>>
<span id="el_otrasorganizaciones__email">
<input type="text" data-table="otrasorganizaciones" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otrasorganizaciones->_email->getPlaceHolder()) ?>" value="<?php echo $otrasorganizaciones->_email->EditValue ?>"<?php echo $otrasorganizaciones->_email->EditAttributes() ?>>
</span>
<?php echo $otrasorganizaciones->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($otrasorganizaciones->id_persona->Visible) { // id_persona ?>
	<div id="r_id_persona" class="form-group">
		<label id="elh_otrasorganizaciones_id_persona" for="x_id_persona" class="<?php echo $otrasorganizaciones_edit->LeftColumnClass ?>"><?php echo $otrasorganizaciones->id_persona->FldCaption() ?></label>
		<div class="<?php echo $otrasorganizaciones_edit->RightColumnClass ?>"><div<?php echo $otrasorganizaciones->id_persona->CellAttributes() ?>>
<span id="el_otrasorganizaciones_id_persona">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_persona"><?php echo (strval($otrasorganizaciones->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $otrasorganizaciones->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($otrasorganizaciones->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_persona',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($otrasorganizaciones->id_persona->ReadOnly || $otrasorganizaciones->id_persona->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="otrasorganizaciones" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $otrasorganizaciones->id_persona->DisplayValueSeparatorAttribute() ?>" name="x_id_persona" id="x_id_persona" value="<?php echo $otrasorganizaciones->id_persona->CurrentValue ?>"<?php echo $otrasorganizaciones->id_persona->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "persona") && !$otrasorganizaciones->id_persona->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $otrasorganizaciones->id_persona->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_persona',url:'personaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_persona"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $otrasorganizaciones->id_persona->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $otrasorganizaciones->id_persona->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$otrasorganizaciones_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $otrasorganizaciones_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $otrasorganizaciones_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fotrasorganizacionesedit.Init();
</script>
<?php
$otrasorganizaciones_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$otrasorganizaciones_edit->Page_Terminate();
?>
