<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "personainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$persona_addopt = NULL; // Initialize page object first

class cpersona_addopt extends cpersona {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'persona';

	// Page object name
	var $PageObjName = 'persona_addopt';

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

		// Table object (persona)
		if (!isset($GLOBALS["persona"]) || get_class($GLOBALS["persona"]) == "cpersona") {
			$GLOBALS["persona"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["persona"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'persona', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("personalist.php"));
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
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombre->SetVisibility();
		$this->telefono->SetVisibility();
		$this->_email->SetVisibility();

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
		global $EW_EXPORT, $persona;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($persona);
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used

		$this->LoadRowValues(); // Load default values

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_id"] = $this->id->DbValue;
					$row["x_nombreinstitucion"] = ew_ConvertToUtf8($this->nombreinstitucion->DbValue);
					$row["x_apellidopaterno"] = ew_ConvertToUtf8($this->apellidopaterno->DbValue);
					$row["x_apellidomaterno"] = ew_ConvertToUtf8($this->apellidomaterno->DbValue);
					$row["x_nombre"] = ew_ConvertToUtf8($this->nombre->DbValue);
					$row["x_telefono"] = ew_ConvertToUtf8($this->telefono->DbValue);
					$row["x__email"] = ew_ConvertToUtf8($this->_email->DbValue);
					$row["x_tipopersona"] = ew_ConvertToUtf8($this->tipopersona->DbValue);
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					ew_Header(FALSE, "utf-8", TRUE);
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->nombreinstitucion->CurrentValue = NULL;
		$this->nombreinstitucion->OldValue = $this->nombreinstitucion->CurrentValue;
		$this->apellidopaterno->CurrentValue = NULL;
		$this->apellidopaterno->OldValue = $this->apellidopaterno->CurrentValue;
		$this->apellidomaterno->CurrentValue = NULL;
		$this->apellidomaterno->OldValue = $this->apellidomaterno->CurrentValue;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->telefono->CurrentValue = NULL;
		$this->telefono->OldValue = $this->telefono->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->tipopersona->CurrentValue = NULL;
		$this->tipopersona->OldValue = $this->tipopersona->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->apellidopaterno->FldIsDetailKey) {
			$this->apellidopaterno->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_apellidopaterno")));
		}
		if (!$this->apellidomaterno->FldIsDetailKey) {
			$this->apellidomaterno->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_apellidomaterno")));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nombre")));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_telefono")));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x__email")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->apellidopaterno->CurrentValue = ew_ConvertToUtf8($this->apellidopaterno->FormValue);
		$this->apellidomaterno->CurrentValue = ew_ConvertToUtf8($this->apellidomaterno->FormValue);
		$this->nombre->CurrentValue = ew_ConvertToUtf8($this->nombre->FormValue);
		$this->telefono->CurrentValue = ew_ConvertToUtf8($this->telefono->FormValue);
		$this->_email->CurrentValue = ew_ConvertToUtf8($this->_email->FormValue);
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
		$this->nombreinstitucion->setDbValue($row['nombreinstitucion']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->telefono->setDbValue($row['telefono']);
		$this->_email->setDbValue($row['email']);
		$this->tipopersona->setDbValue($row['tipopersona']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['nombreinstitucion'] = $this->nombreinstitucion->CurrentValue;
		$row['apellidopaterno'] = $this->apellidopaterno->CurrentValue;
		$row['apellidomaterno'] = $this->apellidomaterno->CurrentValue;
		$row['nombre'] = $this->nombre->CurrentValue;
		$row['telefono'] = $this->telefono->CurrentValue;
		$row['email'] = $this->_email->CurrentValue;
		$row['tipopersona'] = $this->tipopersona->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->nombreinstitucion->DbValue = $row['nombreinstitucion'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->telefono->DbValue = $row['telefono'];
		$this->_email->DbValue = $row['email'];
		$this->tipopersona->DbValue = $row['tipopersona'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// nombreinstitucion
		// apellidopaterno
		// apellidomaterno
		// nombre
		// telefono
		// email
		// tipopersona

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// nombreinstitucion
		$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
		$this->nombreinstitucion->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// tipopersona
		$this->tipopersona->ViewValue = $this->tipopersona->CurrentValue;
		$this->tipopersona->ViewCustomAttributes = "";

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

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// Add refer script
			// apellidopaterno

			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
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
		if (!$this->apellidopaterno->FldIsDetailKey && !is_null($this->apellidopaterno->FormValue) && $this->apellidopaterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->apellidopaterno->FldCaption(), $this->apellidopaterno->ReqErrMsg));
		}
		if (!$this->apellidomaterno->FldIsDetailKey && !is_null($this->apellidomaterno->FormValue) && $this->apellidomaterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->apellidomaterno->FldCaption(), $this->apellidomaterno->ReqErrMsg));
		}
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// apellidopaterno
		$this->apellidopaterno->SetDbValueDef($rsnew, $this->apellidopaterno->CurrentValue, "", FALSE);

		// apellidomaterno
		$this->apellidomaterno->SetDbValueDef($rsnew, $this->apellidomaterno->CurrentValue, "", FALSE);

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", FALSE);

		// telefono
		$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("personalist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
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

	// Custom validate event
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
if (!isset($persona_addopt)) $persona_addopt = new cpersona_addopt();

// Page init
$persona_addopt->Page_Init();

// Page main
$persona_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$persona_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = fpersonaaddopt = new ew_Form("fpersonaaddopt", "addopt");

// Validate form
fpersonaaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_apellidopaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $persona->apellidopaterno->FldCaption(), $persona->apellidopaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidomaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $persona->apellidomaterno->FldCaption(), $persona->apellidomaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $persona->nombre->FldCaption(), $persona->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telefono");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $persona->telefono->FldCaption(), $persona->telefono->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $persona->_email->FldCaption(), $persona->_email->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpersonaaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpersonaaddopt.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$persona_addopt->ShowMessage();
?>
<form name="fpersonaaddopt" id="fpersonaaddopt" class="ewForm form-horizontal" action="personaaddopt.php" method="post">
<?php if ($persona_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $persona_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="persona">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($persona->apellidopaterno->Visible) { // apellidopaterno ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_apellidopaterno"><?php echo $persona->apellidopaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="persona" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($persona->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $persona->apellidopaterno->EditValue ?>"<?php echo $persona->apellidopaterno->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($persona->apellidomaterno->Visible) { // apellidomaterno ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_apellidomaterno"><?php echo $persona->apellidomaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="persona" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($persona->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $persona->apellidomaterno->EditValue ?>"<?php echo $persona->apellidomaterno->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($persona->nombre->Visible) { // nombre ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_nombre"><?php echo $persona->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="persona" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($persona->nombre->getPlaceHolder()) ?>" value="<?php echo $persona->nombre->EditValue ?>"<?php echo $persona->nombre->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($persona->telefono->Visible) { // telefono ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_telefono"><?php echo $persona->telefono->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="persona" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($persona->telefono->getPlaceHolder()) ?>" value="<?php echo $persona->telefono->EditValue ?>"<?php echo $persona->telefono->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($persona->_email->Visible) { // email ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x__email"><?php echo $persona->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="persona" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($persona->_email->getPlaceHolder()) ?>" value="<?php echo $persona->_email->EditValue ?>"<?php echo $persona->_email->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
</form>
<script type="text/javascript">
fpersonaaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$persona_addopt->Page_Terminate();
?>
