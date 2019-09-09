<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "referenciainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$referencia_addopt = NULL; // Initialize page object first

class creferencia_addopt extends creferencia {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'referencia';

	// Page object name
	var $PageObjName = 'referencia_addopt';

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

		// Table object (referencia)
		if (!isset($GLOBALS["referencia"]) || get_class($GLOBALS["referencia"]) == "creferencia") {
			$GLOBALS["referencia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["referencia"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'referencia', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("referencialist.php"));
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
		$this->id_medio->SetVisibility();
		$this->nombrescompleto->SetVisibility();
		$this->nombrescentromedico->SetVisibility();
		$this->direcciF3n->SetVisibility();
		$this->telefono->SetVisibility();
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
		global $EW_EXPORT, $referencia;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($referencia);
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
					$row["x_id_medio"] = $this->id_medio->DbValue;
					$row["x_nombrescompleto"] = ew_ConvertToUtf8($this->nombrescompleto->DbValue);
					$row["x_nombrescentromedico"] = ew_ConvertToUtf8($this->nombrescentromedico->DbValue);
					$row["x_direcciF3n"] = $this->direcciF3n->DbValue;
					$row["x_telefono"] = $this->telefono->DbValue;
					$row["x_id_centro"] = $this->id_centro->DbValue;
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
		$this->id_medio->CurrentValue = NULL;
		$this->id_medio->OldValue = $this->id_medio->CurrentValue;
		$this->nombrescompleto->CurrentValue = NULL;
		$this->nombrescompleto->OldValue = $this->nombrescompleto->CurrentValue;
		$this->nombrescentromedico->CurrentValue = NULL;
		$this->nombrescentromedico->OldValue = $this->nombrescentromedico->CurrentValue;
		$this->direcciF3n->CurrentValue = NULL;
		$this->direcciF3n->OldValue = $this->direcciF3n->CurrentValue;
		$this->telefono->CurrentValue = NULL;
		$this->telefono->OldValue = $this->telefono->CurrentValue;
		$this->id_centro->CurrentValue = SESSION["centro"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_medio->FldIsDetailKey) {
			$this->id_medio->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_medio")));
		}
		if (!$this->nombrescompleto->FldIsDetailKey) {
			$this->nombrescompleto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nombrescompleto")));
		}
		if (!$this->nombrescentromedico->FldIsDetailKey) {
			$this->nombrescentromedico->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nombrescentromedico")));
		}
		if (!$this->direcciF3n->FldIsDetailKey) {
			$this->direcciF3n->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_direcciF3n")));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_telefono")));
		}
		if (!$this->id_centro->FldIsDetailKey) {
			$this->id_centro->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_centro")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_medio->CurrentValue = ew_ConvertToUtf8($this->id_medio->FormValue);
		$this->nombrescompleto->CurrentValue = ew_ConvertToUtf8($this->nombrescompleto->FormValue);
		$this->nombrescentromedico->CurrentValue = ew_ConvertToUtf8($this->nombrescentromedico->FormValue);
		$this->direcciF3n->CurrentValue = ew_ConvertToUtf8($this->direcciF3n->FormValue);
		$this->telefono->CurrentValue = ew_ConvertToUtf8($this->telefono->FormValue);
		$this->id_centro->CurrentValue = ew_ConvertToUtf8($this->id_centro->FormValue);
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
		$this->id_medio->setDbValue($row['id_medio']);
		$this->nombrescompleto->setDbValue($row['nombrescompleto']);
		$this->nombrescentromedico->setDbValue($row['nombrescentromedico']);
		$this->direcciF3n->setDbValue($row['dirección']);
		$this->telefono->setDbValue($row['telefono']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['id_medio'] = $this->id_medio->CurrentValue;
		$row['nombrescompleto'] = $this->nombrescompleto->CurrentValue;
		$row['nombrescentromedico'] = $this->nombrescentromedico->CurrentValue;
		$row['dirección'] = $this->direcciF3n->CurrentValue;
		$row['telefono'] = $this->telefono->CurrentValue;
		$row['id_centro'] = $this->id_centro->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_medio->DbValue = $row['id_medio'];
		$this->nombrescompleto->DbValue = $row['nombrescompleto'];
		$this->nombrescentromedico->DbValue = $row['nombrescentromedico'];
		$this->direcciF3n->DbValue = $row['dirección'];
		$this->telefono->DbValue = $row['telefono'];
		$this->id_centro->DbValue = $row['id_centro'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// id_medio
		// nombrescompleto
		// nombrescentromedico
		// dirección
		// telefono
		// id_centro

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_medio
		if (strval($this->id_medio->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_medio->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medio`";
		$sWhereWrk = "";
		$this->id_medio->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_medio, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_medio->ViewValue = $this->id_medio->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_medio->ViewValue = $this->id_medio->CurrentValue;
			}
		} else {
			$this->id_medio->ViewValue = NULL;
		}
		$this->id_medio->ViewCustomAttributes = "";

		// nombrescompleto
		$this->nombrescompleto->ViewValue = $this->nombrescompleto->CurrentValue;
		$this->nombrescompleto->ViewCustomAttributes = "";

		// nombrescentromedico
		$this->nombrescentromedico->ViewValue = $this->nombrescentromedico->CurrentValue;
		$this->nombrescentromedico->ViewCustomAttributes = "";

		// dirección
		$this->direcciF3n->ViewValue = $this->direcciF3n->CurrentValue;
		$this->direcciF3n->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// id_centro
		$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
		$this->id_centro->ViewCustomAttributes = "";

			// id_medio
			$this->id_medio->LinkCustomAttributes = "";
			$this->id_medio->HrefValue = "";
			$this->id_medio->TooltipValue = "";

			// nombrescompleto
			$this->nombrescompleto->LinkCustomAttributes = "";
			$this->nombrescompleto->HrefValue = "";
			$this->nombrescompleto->TooltipValue = "";

			// nombrescentromedico
			$this->nombrescentromedico->LinkCustomAttributes = "";
			$this->nombrescentromedico->HrefValue = "";
			$this->nombrescentromedico->TooltipValue = "";

			// dirección
			$this->direcciF3n->LinkCustomAttributes = "";
			$this->direcciF3n->HrefValue = "";
			$this->direcciF3n->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";
			$this->id_centro->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_medio
			$this->id_medio->EditCustomAttributes = "";
			if (trim(strval($this->id_medio->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_medio->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `medio`";
			$sWhereWrk = "";
			$this->id_medio->LookupFilters = array("dx1" => '`nombre`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_medio, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_medio->ViewValue = $this->id_medio->DisplayValue($arwrk);
			} else {
				$this->id_medio->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_medio->EditValue = $arwrk;

			// nombrescompleto
			$this->nombrescompleto->EditAttrs["class"] = "form-control";
			$this->nombrescompleto->EditCustomAttributes = "";
			$this->nombrescompleto->EditValue = ew_HtmlEncode($this->nombrescompleto->CurrentValue);
			$this->nombrescompleto->PlaceHolder = ew_RemoveHtml($this->nombrescompleto->FldCaption());

			// nombrescentromedico
			$this->nombrescentromedico->EditAttrs["class"] = "form-control";
			$this->nombrescentromedico->EditCustomAttributes = "";
			$this->nombrescentromedico->EditValue = ew_HtmlEncode($this->nombrescentromedico->CurrentValue);
			$this->nombrescentromedico->PlaceHolder = ew_RemoveHtml($this->nombrescentromedico->FldCaption());

			// dirección
			$this->direcciF3n->EditAttrs["class"] = "form-control";
			$this->direcciF3n->EditCustomAttributes = "";
			$this->direcciF3n->EditValue = ew_HtmlEncode($this->direcciF3n->CurrentValue);
			$this->direcciF3n->PlaceHolder = ew_RemoveHtml($this->direcciF3n->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// id_centro
			$this->id_centro->EditAttrs["class"] = "form-control";
			$this->id_centro->EditCustomAttributes = "";
			$this->id_centro->EditValue = ew_HtmlEncode($this->id_centro->CurrentValue);
			$this->id_centro->PlaceHolder = ew_RemoveHtml($this->id_centro->FldCaption());

			// Add refer script
			// id_medio

			$this->id_medio->LinkCustomAttributes = "";
			$this->id_medio->HrefValue = "";

			// nombrescompleto
			$this->nombrescompleto->LinkCustomAttributes = "";
			$this->nombrescompleto->HrefValue = "";

			// nombrescentromedico
			$this->nombrescentromedico->LinkCustomAttributes = "";
			$this->nombrescentromedico->HrefValue = "";

			// dirección
			$this->direcciF3n->LinkCustomAttributes = "";
			$this->direcciF3n->HrefValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";

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
		if (!$this->id_medio->FldIsDetailKey && !is_null($this->id_medio->FormValue) && $this->id_medio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_medio->FldCaption(), $this->id_medio->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->direcciF3n->FormValue)) {
			ew_AddMessage($gsFormError, $this->direcciF3n->FldErrMsg());
		}
		if (!ew_CheckInteger($this->telefono->FormValue)) {
			ew_AddMessage($gsFormError, $this->telefono->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// id_medio
		$this->id_medio->SetDbValueDef($rsnew, $this->id_medio->CurrentValue, 0, FALSE);

		// nombrescompleto
		$this->nombrescompleto->SetDbValueDef($rsnew, $this->nombrescompleto->CurrentValue, NULL, FALSE);

		// nombrescentromedico
		$this->nombrescentromedico->SetDbValueDef($rsnew, $this->nombrescentromedico->CurrentValue, NULL, FALSE);

		// dirección
		$this->direcciF3n->SetDbValueDef($rsnew, $this->direcciF3n->CurrentValue, NULL, FALSE);

		// telefono
		$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("referencialist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_medio":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medio`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_medio, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($referencia_addopt)) $referencia_addopt = new creferencia_addopt();

// Page init
$referencia_addopt->Page_Init();

// Page main
$referencia_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$referencia_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = freferenciaaddopt = new ew_Form("freferenciaaddopt", "addopt");

// Validate form
freferenciaaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_medio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $referencia->id_medio->FldCaption(), $referencia->id_medio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direcciF3n");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($referencia->direcciF3n->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_telefono");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($referencia->telefono->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $referencia->id_centro->FldCaption(), $referencia->id_centro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($referencia->id_centro->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
freferenciaaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
freferenciaaddopt.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
freferenciaaddopt.Lists["x_id_medio"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"medio"};
freferenciaaddopt.Lists["x_id_medio"].Data = "<?php echo $referencia_addopt->id_medio->LookupFilterQuery(FALSE, "addopt") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$referencia_addopt->ShowMessage();
?>
<form name="freferenciaaddopt" id="freferenciaaddopt" class="ewForm form-horizontal" action="referenciaaddopt.php" method="post">
<?php if ($referencia_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $referencia_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="referencia">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($referencia->id_medio->Visible) { // id_medio ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_medio"><?php echo $referencia->id_medio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_medio"><?php echo (strval($referencia->id_medio->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $referencia->id_medio->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($referencia->id_medio->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_medio',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($referencia->id_medio->ReadOnly || $referencia->id_medio->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="referencia" data-field="x_id_medio" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $referencia->id_medio->DisplayValueSeparatorAttribute() ?>" name="x_id_medio" id="x_id_medio" value="<?php echo $referencia->id_medio->CurrentValue ?>"<?php echo $referencia->id_medio->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($referencia->nombrescompleto->Visible) { // nombrescompleto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_nombrescompleto"><?php echo $referencia->nombrescompleto->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="referencia" data-field="x_nombrescompleto" name="x_nombrescompleto" id="x_nombrescompleto" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($referencia->nombrescompleto->getPlaceHolder()) ?>" value="<?php echo $referencia->nombrescompleto->EditValue ?>"<?php echo $referencia->nombrescompleto->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($referencia->nombrescentromedico->Visible) { // nombrescentromedico ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_nombrescentromedico"><?php echo $referencia->nombrescentromedico->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="referencia" data-field="x_nombrescentromedico" name="x_nombrescentromedico" id="x_nombrescentromedico" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($referencia->nombrescentromedico->getPlaceHolder()) ?>" value="<?php echo $referencia->nombrescentromedico->EditValue ?>"<?php echo $referencia->nombrescentromedico->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($referencia->direcciF3n->Visible) { // dirección ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_direcciF3n"><?php echo $referencia->direcciF3n->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="referencia" data-field="x_direcciF3n" name="x_direcciF3n" id="x_direcciF3n" size="30" placeholder="<?php echo ew_HtmlEncode($referencia->direcciF3n->getPlaceHolder()) ?>" value="<?php echo $referencia->direcciF3n->EditValue ?>"<?php echo $referencia->direcciF3n->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($referencia->telefono->Visible) { // telefono ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_telefono"><?php echo $referencia->telefono->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="referencia" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" placeholder="<?php echo ew_HtmlEncode($referencia->telefono->getPlaceHolder()) ?>" value="<?php echo $referencia->telefono->EditValue ?>"<?php echo $referencia->telefono->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($referencia->id_centro->Visible) { // id_centro ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_centro"><?php echo $referencia->id_centro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="referencia" data-field="x_id_centro" name="x_id_centro" id="x_id_centro" size="30" placeholder="<?php echo ew_HtmlEncode($referencia->id_centro->getPlaceHolder()) ?>" value="<?php echo $referencia->id_centro->EditValue ?>"<?php echo $referencia->id_centro->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
</form>
<script type="text/javascript">
freferenciaaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$referencia_addopt->Page_Terminate();
?>
