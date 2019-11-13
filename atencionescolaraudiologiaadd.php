<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "atencionescolaraudiologiainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$atencionescolaraudiologia_add = NULL; // Initialize page object first

class catencionescolaraudiologia_add extends catencionescolaraudiologia {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'atencionescolaraudiologia';

	// Page object name
	var $PageObjName = 'atencionescolaraudiologia_add';

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

		// Table object (atencionescolaraudiologia)
		if (!isset($GLOBALS["atencionescolaraudiologia"]) || get_class($GLOBALS["atencionescolaraudiologia"]) == "catencionescolaraudiologia") {
			$GLOBALS["atencionescolaraudiologia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["atencionescolaraudiologia"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'atencionescolaraudiologia', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("atencionescolaraudiologialist.php"));
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
		$this->id_escolar->SetVisibility();
		$this->id_especialista->SetVisibility();
		$this->id_referencia->SetVisibility();

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
		global $EW_EXPORT, $atencionescolaraudiologia;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($atencionescolaraudiologia);
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
					if ($pageName == "atencionescolaraudiologiaview.php")
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
					$this->Page_Terminate("atencionescolaraudiologialist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "atencionescolaraudiologialist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "atencionescolaraudiologiaview.php")
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
		$this->id_escolar->CurrentValue = NULL;
		$this->id_escolar->OldValue = $this->id_escolar->CurrentValue;
		$this->id_especialista->CurrentValue = NULL;
		$this->id_especialista->OldValue = $this->id_especialista->CurrentValue;
		$this->id_referencia->CurrentValue = NULL;
		$this->id_referencia->OldValue = $this->id_referencia->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_escolar->FldIsDetailKey) {
			$this->id_escolar->setFormValue($objForm->GetValue("x_id_escolar"));
		}
		if (!$this->id_especialista->FldIsDetailKey) {
			$this->id_especialista->setFormValue($objForm->GetValue("x_id_especialista"));
		}
		if (!$this->id_referencia->FldIsDetailKey) {
			$this->id_referencia->setFormValue($objForm->GetValue("x_id_referencia"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_escolar->CurrentValue = $this->id_escolar->FormValue;
		$this->id_especialista->CurrentValue = $this->id_especialista->FormValue;
		$this->id_referencia->CurrentValue = $this->id_referencia->FormValue;
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
		$this->id_escolar->setDbValue($row['id_escolar']);
		if (array_key_exists('EV__id_escolar', $rs->fields)) {
			$this->id_escolar->VirtualValue = $rs->fields('EV__id_escolar'); // Set up virtual field value
		} else {
			$this->id_escolar->VirtualValue = ""; // Clear value
		}
		$this->id_especialista->setDbValue($row['id_especialista']);
		if (array_key_exists('EV__id_especialista', $rs->fields)) {
			$this->id_especialista->VirtualValue = $rs->fields('EV__id_especialista'); // Set up virtual field value
		} else {
			$this->id_especialista->VirtualValue = ""; // Clear value
		}
		$this->id_referencia->setDbValue($row['id_referencia']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['id_escolar'] = $this->id_escolar->CurrentValue;
		$row['id_especialista'] = $this->id_especialista->CurrentValue;
		$row['id_referencia'] = $this->id_referencia->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_escolar->DbValue = $row['id_escolar'];
		$this->id_especialista->DbValue = $row['id_especialista'];
		$this->id_referencia->DbValue = $row['id_referencia'];
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
		// id_escolar
		// id_especialista
		// id_referencia

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_escolar
		if ($this->id_escolar->VirtualValue <> "") {
			$this->id_escolar->ViewValue = $this->id_escolar->VirtualValue;
		} else {
		if (strval($this->id_escolar->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_escolar->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `escolar`";
		$sWhereWrk = "";
		$this->id_escolar->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
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
		}
		$this->id_escolar->ViewCustomAttributes = "";

		// id_especialista
		if ($this->id_especialista->VirtualValue <> "") {
			$this->id_especialista->ViewValue = $this->id_especialista->VirtualValue;
		} else {
		if (strval($this->id_especialista->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_especialista->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `nombreespecialidad` AS `Disp4Fld` FROM `especialista`";
		$sWhereWrk = "";
		$this->id_especialista->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`', "dx4" => '`nombreespecialidad`');
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
		}
		$this->id_especialista->ViewCustomAttributes = "";

		// id_referencia
		if (strval($this->id_referencia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_referencia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombrescompleto` AS `DispFld`, `nombrescentromedico` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
		$sWhereWrk = "";
		$this->id_referencia->LookupFilters = array();
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

			// id_escolar
			$this->id_escolar->LinkCustomAttributes = "";
			$this->id_escolar->HrefValue = "";
			$this->id_escolar->TooltipValue = "";

			// id_especialista
			$this->id_especialista->LinkCustomAttributes = "";
			$this->id_especialista->HrefValue = "";
			$this->id_especialista->TooltipValue = "";

			// id_referencia
			$this->id_referencia->LinkCustomAttributes = "";
			$this->id_referencia->HrefValue = "";
			$this->id_referencia->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_escolar
			$this->id_escolar->EditCustomAttributes = "";
			if (trim(strval($this->id_escolar->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_escolar->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `escolar`";
			$sWhereWrk = "";
			$this->id_escolar->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_escolar, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->id_escolar->ViewValue = $this->id_escolar->DisplayValue($arwrk);
			} else {
				$this->id_escolar->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_escolar->EditValue = $arwrk;

			// id_especialista
			$this->id_especialista->EditCustomAttributes = "";
			if (trim(strval($this->id_especialista->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_especialista->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `nombreespecialidad` AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `especialista`";
			$sWhereWrk = "";
			$this->id_especialista->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`', "dx4" => '`nombreespecialidad`');
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
				$this->id_especialista->ViewValue = $this->id_especialista->DisplayValue($arwrk);
			} else {
				$this->id_especialista->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_especialista->EditValue = $arwrk;

			// id_referencia
			$this->id_referencia->EditCustomAttributes = "";
			if (trim(strval($this->id_referencia->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_referencia->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombrescompleto` AS `DispFld`, `nombrescentromedico` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `referencia`";
			$sWhereWrk = "";
			$this->id_referencia->LookupFilters = array();
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

			// Add refer script
			// id_escolar

			$this->id_escolar->LinkCustomAttributes = "";
			$this->id_escolar->HrefValue = "";

			// id_especialista
			$this->id_especialista->LinkCustomAttributes = "";
			$this->id_especialista->HrefValue = "";

			// id_referencia
			$this->id_referencia->LinkCustomAttributes = "";
			$this->id_referencia->HrefValue = "";
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

		// id_escolar
		$this->id_escolar->SetDbValueDef($rsnew, $this->id_escolar->CurrentValue, NULL, FALSE);

		// id_especialista
		$this->id_especialista->SetDbValueDef($rsnew, $this->id_especialista->CurrentValue, 0, FALSE);

		// id_referencia
		$this->id_referencia->SetDbValueDef($rsnew, $this->id_referencia->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("atencionescolaraudiologialist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_escolar":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `escolar`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_escolar, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_especialista":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `nombreespecialidad` AS `Disp4Fld` FROM `especialista`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`', "dx4" => '`nombreespecialidad`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_referencia":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombrescompleto` AS `DispFld`, `nombrescentromedico` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
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
if (!isset($atencionescolaraudiologia_add)) $atencionescolaraudiologia_add = new catencionescolaraudiologia_add();

// Page init
$atencionescolaraudiologia_add->Page_Init();

// Page main
$atencionescolaraudiologia_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atencionescolaraudiologia_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fatencionescolaraudiologiaadd = new ew_Form("fatencionescolaraudiologiaadd", "add");

// Validate form
fatencionescolaraudiologiaadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $atencionescolaraudiologia->id_especialista->FldCaption(), $atencionescolaraudiologia->id_especialista->ReqErrMsg)) ?>");

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
fatencionescolaraudiologiaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fatencionescolaraudiologiaadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fatencionescolaraudiologiaadd.Lists["x_id_escolar"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"escolar"};
fatencionescolaraudiologiaadd.Lists["x_id_escolar"].Data = "<?php echo $atencionescolaraudiologia_add->id_escolar->LookupFilterQuery(FALSE, "add") ?>";
fatencionescolaraudiologiaadd.Lists["x_id_especialista"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno","x_nombreespecialidad"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"especialista"};
fatencionescolaraudiologiaadd.Lists["x_id_especialista"].Data = "<?php echo $atencionescolaraudiologia_add->id_especialista->LookupFilterQuery(FALSE, "add") ?>";
fatencionescolaraudiologiaadd.Lists["x_id_referencia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombrescompleto","x_nombrescentromedico","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referencia"};
fatencionescolaraudiologiaadd.Lists["x_id_referencia"].Data = "<?php echo $atencionescolaraudiologia_add->id_referencia->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $atencionescolaraudiologia_add->ShowPageHeader(); ?>
<?php
$atencionescolaraudiologia_add->ShowMessage();
?>
<form name="fatencionescolaraudiologiaadd" id="fatencionescolaraudiologiaadd" class="<?php echo $atencionescolaraudiologia_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($atencionescolaraudiologia_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $atencionescolaraudiologia_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="atencionescolaraudiologia">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($atencionescolaraudiologia_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($atencionescolaraudiologia->id_escolar->Visible) { // id_escolar ?>
	<div id="r_id_escolar" class="form-group">
		<label id="elh_atencionescolaraudiologia_id_escolar" for="x_id_escolar" class="<?php echo $atencionescolaraudiologia_add->LeftColumnClass ?>"><?php echo $atencionescolaraudiologia->id_escolar->FldCaption() ?></label>
		<div class="<?php echo $atencionescolaraudiologia_add->RightColumnClass ?>"><div<?php echo $atencionescolaraudiologia->id_escolar->CellAttributes() ?>>
<span id="el_atencionescolaraudiologia_id_escolar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_escolar"><?php echo (strval($atencionescolaraudiologia->id_escolar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $atencionescolaraudiologia->id_escolar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($atencionescolaraudiologia->id_escolar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_escolar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($atencionescolaraudiologia->id_escolar->ReadOnly || $atencionescolaraudiologia->id_escolar->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="atencionescolaraudiologia" data-field="x_id_escolar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $atencionescolaraudiologia->id_escolar->DisplayValueSeparatorAttribute() ?>" name="x_id_escolar" id="x_id_escolar" value="<?php echo $atencionescolaraudiologia->id_escolar->CurrentValue ?>"<?php echo $atencionescolaraudiologia->id_escolar->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "escolar") && !$atencionescolaraudiologia->id_escolar->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $atencionescolaraudiologia->id_escolar->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_escolar',url:'escolaraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_escolar"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $atencionescolaraudiologia->id_escolar->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $atencionescolaraudiologia->id_escolar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atencionescolaraudiologia->id_especialista->Visible) { // id_especialista ?>
	<div id="r_id_especialista" class="form-group">
		<label id="elh_atencionescolaraudiologia_id_especialista" for="x_id_especialista" class="<?php echo $atencionescolaraudiologia_add->LeftColumnClass ?>"><?php echo $atencionescolaraudiologia->id_especialista->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $atencionescolaraudiologia_add->RightColumnClass ?>"><div<?php echo $atencionescolaraudiologia->id_especialista->CellAttributes() ?>>
<span id="el_atencionescolaraudiologia_id_especialista">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_especialista"><?php echo (strval($atencionescolaraudiologia->id_especialista->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $atencionescolaraudiologia->id_especialista->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($atencionescolaraudiologia->id_especialista->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_especialista',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($atencionescolaraudiologia->id_especialista->ReadOnly || $atencionescolaraudiologia->id_especialista->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="atencionescolaraudiologia" data-field="x_id_especialista" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $atencionescolaraudiologia->id_especialista->DisplayValueSeparatorAttribute() ?>" name="x_id_especialista" id="x_id_especialista" value="<?php echo $atencionescolaraudiologia->id_especialista->CurrentValue ?>"<?php echo $atencionescolaraudiologia->id_especialista->EditAttributes() ?>>
</span>
<?php echo $atencionescolaraudiologia->id_especialista->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atencionescolaraudiologia->id_referencia->Visible) { // id_referencia ?>
	<div id="r_id_referencia" class="form-group">
		<label id="elh_atencionescolaraudiologia_id_referencia" for="x_id_referencia" class="<?php echo $atencionescolaraudiologia_add->LeftColumnClass ?>"><?php echo $atencionescolaraudiologia->id_referencia->FldCaption() ?></label>
		<div class="<?php echo $atencionescolaraudiologia_add->RightColumnClass ?>"><div<?php echo $atencionescolaraudiologia->id_referencia->CellAttributes() ?>>
<span id="el_atencionescolaraudiologia_id_referencia">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($atencionescolaraudiologia->id_referencia->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $atencionescolaraudiologia->id_referencia->ViewValue ?>
	</span>
	<?php if (!$atencionescolaraudiologia->id_referencia->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_id_referencia" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $atencionescolaraudiologia->id_referencia->RadioButtonListHtml(TRUE, "x_id_referencia") ?>
		</div>
	</div>
	<div id="tp_x_id_referencia" class="ewTemplate"><input type="radio" data-table="atencionescolaraudiologia" data-field="x_id_referencia" data-value-separator="<?php echo $atencionescolaraudiologia->id_referencia->DisplayValueSeparatorAttribute() ?>" name="x_id_referencia" id="x_id_referencia" value="{value}"<?php echo $atencionescolaraudiologia->id_referencia->EditAttributes() ?>></div>
</div>
<?php if (AllowAdd(CurrentProjectID() . "referencia") && !$atencionescolaraudiologia->id_referencia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $atencionescolaraudiologia->id_referencia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_referencia',url:'referenciaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_referencia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $atencionescolaraudiologia->id_referencia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $atencionescolaraudiologia->id_referencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$atencionescolaraudiologia_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $atencionescolaraudiologia_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $atencionescolaraudiologia_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fatencionescolaraudiologiaadd.Init();
</script>
<?php
$atencionescolaraudiologia_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$atencionescolaraudiologia_add->Page_Terminate();
?>
