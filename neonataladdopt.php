<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "neonatalinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$neonatal_addopt = NULL; // Initialize page object first

class cneonatal_addopt extends cneonatal {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'neonatal';

	// Page object name
	var $PageObjName = 'neonatal_addopt';

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

		// Table object (neonatal)
		if (!isset($GLOBALS["neonatal"]) || get_class($GLOBALS["neonatal"]) == "cneonatal") {
			$GLOBALS["neonatal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["neonatal"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'neonatal', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("neonatallist.php"));
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
		$this->fecha_tamizaje->SetVisibility();
		$this->id_centro->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombre->SetVisibility();
		$this->ci->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->dias->SetVisibility();
		$this->semanas->SetVisibility();
		$this->meses->SetVisibility();
		$this->sexo->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->id_tipodiscapacidad->SetVisibility();
		$this->resultado->SetVisibility();
		$this->resultadotamizaje->SetVisibility();
		$this->tapon->SetVisibility();
		$this->tipo->SetVisibility();
		$this->repetirprueba->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->id_apoderado->SetVisibility();
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
		global $EW_EXPORT, $neonatal;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($neonatal);
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
					$row["x_fecha_tamizaje"] = $this->fecha_tamizaje->DbValue;
					$row["x_id_centro"] = $this->id_centro->DbValue;
					$row["x_apellidopaterno"] = ew_ConvertToUtf8($this->apellidopaterno->DbValue);
					$row["x_apellidomaterno"] = ew_ConvertToUtf8($this->apellidomaterno->DbValue);
					$row["x_nombre"] = ew_ConvertToUtf8($this->nombre->DbValue);
					$row["x_ci"] = ew_ConvertToUtf8($this->ci->DbValue);
					$row["x_fecha_nacimiento"] = $this->fecha_nacimiento->DbValue;
					$row["x_dias"] = ew_ConvertToUtf8($this->dias->DbValue);
					$row["x_semanas"] = ew_ConvertToUtf8($this->semanas->DbValue);
					$row["x_meses"] = ew_ConvertToUtf8($this->meses->DbValue);
					$row["x_sexo"] = ew_ConvertToUtf8($this->sexo->DbValue);
					$row["x_discapacidad"] = $this->discapacidad->DbValue;
					$row["x_id_tipodiscapacidad"] = $this->id_tipodiscapacidad->DbValue;
					$row["x_resultado"] = ew_ConvertToUtf8($this->resultado->DbValue);
					$row["x_resultadotamizaje"] = ew_ConvertToUtf8($this->resultadotamizaje->DbValue);
					$row["x_tapon"] = ew_ConvertToUtf8($this->tapon->DbValue);
					$row["x_tipo"] = ew_ConvertToUtf8($this->tipo->DbValue);
					$row["x_repetirprueba"] = ew_ConvertToUtf8($this->repetirprueba->DbValue);
					$row["x_observaciones"] = ew_ConvertToUtf8($this->observaciones->DbValue);
					$row["x_id_apoderado"] = $this->id_apoderado->DbValue;
					$row["x_id_referencia"] = $this->id_referencia->DbValue;
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
		$this->fecha_tamizaje->CurrentValue = NULL;
		$this->fecha_tamizaje->OldValue = $this->fecha_tamizaje->CurrentValue;
		$this->id_centro->CurrentValue = NULL;
		$this->id_centro->OldValue = $this->id_centro->CurrentValue;
		$this->apellidopaterno->CurrentValue = NULL;
		$this->apellidopaterno->OldValue = $this->apellidopaterno->CurrentValue;
		$this->apellidomaterno->CurrentValue = NULL;
		$this->apellidomaterno->OldValue = $this->apellidomaterno->CurrentValue;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->ci->CurrentValue = NULL;
		$this->ci->OldValue = $this->ci->CurrentValue;
		$this->fecha_nacimiento->CurrentValue = NULL;
		$this->fecha_nacimiento->OldValue = $this->fecha_nacimiento->CurrentValue;
		$this->dias->CurrentValue = NULL;
		$this->dias->OldValue = $this->dias->CurrentValue;
		$this->semanas->CurrentValue = NULL;
		$this->semanas->OldValue = $this->semanas->CurrentValue;
		$this->meses->CurrentValue = NULL;
		$this->meses->OldValue = $this->meses->CurrentValue;
		$this->sexo->CurrentValue = NULL;
		$this->sexo->OldValue = $this->sexo->CurrentValue;
		$this->discapacidad->CurrentValue = NULL;
		$this->discapacidad->OldValue = $this->discapacidad->CurrentValue;
		$this->id_tipodiscapacidad->CurrentValue = NULL;
		$this->id_tipodiscapacidad->OldValue = $this->id_tipodiscapacidad->CurrentValue;
		$this->resultado->CurrentValue = NULL;
		$this->resultado->OldValue = $this->resultado->CurrentValue;
		$this->resultadotamizaje->CurrentValue = NULL;
		$this->resultadotamizaje->OldValue = $this->resultadotamizaje->CurrentValue;
		$this->tapon->CurrentValue = NULL;
		$this->tapon->OldValue = $this->tapon->CurrentValue;
		$this->tipo->CurrentValue = NULL;
		$this->tipo->OldValue = $this->tipo->CurrentValue;
		$this->repetirprueba->CurrentValue = NULL;
		$this->repetirprueba->OldValue = $this->repetirprueba->CurrentValue;
		$this->observaciones->CurrentValue = NULL;
		$this->observaciones->OldValue = $this->observaciones->CurrentValue;
		$this->id_apoderado->CurrentValue = NULL;
		$this->id_apoderado->OldValue = $this->id_apoderado->CurrentValue;
		$this->id_referencia->CurrentValue = NULL;
		$this->id_referencia->OldValue = $this->id_referencia->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->fecha_tamizaje->FldIsDetailKey) {
			$this->fecha_tamizaje->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_fecha_tamizaje")));
			$this->fecha_tamizaje->CurrentValue = ew_UnFormatDateTime($this->fecha_tamizaje->CurrentValue, 0);
		}
		if (!$this->id_centro->FldIsDetailKey) {
			$this->id_centro->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_centro")));
		}
		if (!$this->apellidopaterno->FldIsDetailKey) {
			$this->apellidopaterno->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_apellidopaterno")));
		}
		if (!$this->apellidomaterno->FldIsDetailKey) {
			$this->apellidomaterno->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_apellidomaterno")));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nombre")));
		}
		if (!$this->ci->FldIsDetailKey) {
			$this->ci->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ci")));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey) {
			$this->fecha_nacimiento->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_fecha_nacimiento")));
			$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 0);
		}
		if (!$this->dias->FldIsDetailKey) {
			$this->dias->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_dias")));
		}
		if (!$this->semanas->FldIsDetailKey) {
			$this->semanas->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_semanas")));
		}
		if (!$this->meses->FldIsDetailKey) {
			$this->meses->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_meses")));
		}
		if (!$this->sexo->FldIsDetailKey) {
			$this->sexo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_sexo")));
		}
		if (!$this->discapacidad->FldIsDetailKey) {
			$this->discapacidad->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_discapacidad")));
		}
		if (!$this->id_tipodiscapacidad->FldIsDetailKey) {
			$this->id_tipodiscapacidad->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_tipodiscapacidad")));
		}
		if (!$this->resultado->FldIsDetailKey) {
			$this->resultado->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_resultado")));
		}
		if (!$this->resultadotamizaje->FldIsDetailKey) {
			$this->resultadotamizaje->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_resultadotamizaje")));
		}
		if (!$this->tapon->FldIsDetailKey) {
			$this->tapon->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_tapon")));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_tipo")));
		}
		if (!$this->repetirprueba->FldIsDetailKey) {
			$this->repetirprueba->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_repetirprueba")));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_observaciones")));
		}
		if (!$this->id_apoderado->FldIsDetailKey) {
			$this->id_apoderado->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_apoderado")));
		}
		if (!$this->id_referencia->FldIsDetailKey) {
			$this->id_referencia->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_referencia")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->fecha_tamizaje->CurrentValue = ew_ConvertToUtf8($this->fecha_tamizaje->FormValue);
		$this->fecha_tamizaje->CurrentValue = ew_UnFormatDateTime($this->fecha_tamizaje->CurrentValue, 0);
		$this->id_centro->CurrentValue = ew_ConvertToUtf8($this->id_centro->FormValue);
		$this->apellidopaterno->CurrentValue = ew_ConvertToUtf8($this->apellidopaterno->FormValue);
		$this->apellidomaterno->CurrentValue = ew_ConvertToUtf8($this->apellidomaterno->FormValue);
		$this->nombre->CurrentValue = ew_ConvertToUtf8($this->nombre->FormValue);
		$this->ci->CurrentValue = ew_ConvertToUtf8($this->ci->FormValue);
		$this->fecha_nacimiento->CurrentValue = ew_ConvertToUtf8($this->fecha_nacimiento->FormValue);
		$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 0);
		$this->dias->CurrentValue = ew_ConvertToUtf8($this->dias->FormValue);
		$this->semanas->CurrentValue = ew_ConvertToUtf8($this->semanas->FormValue);
		$this->meses->CurrentValue = ew_ConvertToUtf8($this->meses->FormValue);
		$this->sexo->CurrentValue = ew_ConvertToUtf8($this->sexo->FormValue);
		$this->discapacidad->CurrentValue = ew_ConvertToUtf8($this->discapacidad->FormValue);
		$this->id_tipodiscapacidad->CurrentValue = ew_ConvertToUtf8($this->id_tipodiscapacidad->FormValue);
		$this->resultado->CurrentValue = ew_ConvertToUtf8($this->resultado->FormValue);
		$this->resultadotamizaje->CurrentValue = ew_ConvertToUtf8($this->resultadotamizaje->FormValue);
		$this->tapon->CurrentValue = ew_ConvertToUtf8($this->tapon->FormValue);
		$this->tipo->CurrentValue = ew_ConvertToUtf8($this->tipo->FormValue);
		$this->repetirprueba->CurrentValue = ew_ConvertToUtf8($this->repetirprueba->FormValue);
		$this->observaciones->CurrentValue = ew_ConvertToUtf8($this->observaciones->FormValue);
		$this->id_apoderado->CurrentValue = ew_ConvertToUtf8($this->id_apoderado->FormValue);
		$this->id_referencia->CurrentValue = ew_ConvertToUtf8($this->id_referencia->FormValue);
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
		$this->fecha_tamizaje->setDbValue($row['fecha_tamizaje']);
		$this->id_centro->setDbValue($row['id_centro']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->ci->setDbValue($row['ci']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->dias->setDbValue($row['dias']);
		$this->semanas->setDbValue($row['semanas']);
		$this->meses->setDbValue($row['meses']);
		$this->sexo->setDbValue($row['sexo']);
		$this->discapacidad->setDbValue($row['discapacidad']);
		$this->id_tipodiscapacidad->setDbValue($row['id_tipodiscapacidad']);
		$this->resultado->setDbValue($row['resultado']);
		$this->resultadotamizaje->setDbValue($row['resultadotamizaje']);
		$this->tapon->setDbValue($row['tapon']);
		$this->tipo->setDbValue($row['tipo']);
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
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['fecha_tamizaje'] = $this->fecha_tamizaje->CurrentValue;
		$row['id_centro'] = $this->id_centro->CurrentValue;
		$row['apellidopaterno'] = $this->apellidopaterno->CurrentValue;
		$row['apellidomaterno'] = $this->apellidomaterno->CurrentValue;
		$row['nombre'] = $this->nombre->CurrentValue;
		$row['ci'] = $this->ci->CurrentValue;
		$row['fecha_nacimiento'] = $this->fecha_nacimiento->CurrentValue;
		$row['dias'] = $this->dias->CurrentValue;
		$row['semanas'] = $this->semanas->CurrentValue;
		$row['meses'] = $this->meses->CurrentValue;
		$row['sexo'] = $this->sexo->CurrentValue;
		$row['discapacidad'] = $this->discapacidad->CurrentValue;
		$row['id_tipodiscapacidad'] = $this->id_tipodiscapacidad->CurrentValue;
		$row['resultado'] = $this->resultado->CurrentValue;
		$row['resultadotamizaje'] = $this->resultadotamizaje->CurrentValue;
		$row['tapon'] = $this->tapon->CurrentValue;
		$row['tipo'] = $this->tipo->CurrentValue;
		$row['repetirprueba'] = $this->repetirprueba->CurrentValue;
		$row['observaciones'] = $this->observaciones->CurrentValue;
		$row['id_apoderado'] = $this->id_apoderado->CurrentValue;
		$row['id_referencia'] = $this->id_referencia->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->fecha_tamizaje->DbValue = $row['fecha_tamizaje'];
		$this->id_centro->DbValue = $row['id_centro'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->ci->DbValue = $row['ci'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->dias->DbValue = $row['dias'];
		$this->semanas->DbValue = $row['semanas'];
		$this->meses->DbValue = $row['meses'];
		$this->sexo->DbValue = $row['sexo'];
		$this->discapacidad->DbValue = $row['discapacidad'];
		$this->id_tipodiscapacidad->DbValue = $row['id_tipodiscapacidad'];
		$this->resultado->DbValue = $row['resultado'];
		$this->resultadotamizaje->DbValue = $row['resultadotamizaje'];
		$this->tapon->DbValue = $row['tapon'];
		$this->tipo->DbValue = $row['tipo'];
		$this->repetirprueba->DbValue = $row['repetirprueba'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_apoderado->DbValue = $row['id_apoderado'];
		$this->id_referencia->DbValue = $row['id_referencia'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// fecha_tamizaje
		// id_centro
		// apellidopaterno
		// apellidomaterno
		// nombre
		// ci
		// fecha_nacimiento
		// dias
		// semanas
		// meses
		// sexo
		// discapacidad
		// id_tipodiscapacidad
		// resultado
		// resultadotamizaje
		// tapon
		// tipo
		// repetirprueba
		// observaciones
		// id_apoderado
		// id_referencia

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// fecha_tamizaje
		$this->fecha_tamizaje->ViewValue = $this->fecha_tamizaje->CurrentValue;
		$this->fecha_tamizaje->ViewValue = ew_FormatDateTime($this->fecha_tamizaje->ViewValue, 0);
		$this->fecha_tamizaje->ViewCustomAttributes = "";

		// id_centro
		if (strval($this->id_centro->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_centro->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucionesdesalud`";
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

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// dias
		$this->dias->ViewValue = $this->dias->CurrentValue;
		$this->dias->ViewCustomAttributes = "";

		// semanas
		$this->semanas->ViewValue = $this->semanas->CurrentValue;
		$this->semanas->ViewCustomAttributes = "";

		// meses
		$this->meses->ViewValue = $this->meses->CurrentValue;
		$this->meses->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// discapacidad
		$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
		if (strval($this->discapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
		$sWhereWrk = "";
		$this->discapacidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->discapacidad->ViewValue = $this->discapacidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
			}
		} else {
			$this->discapacidad->ViewValue = NULL;
		}
		$this->discapacidad->ViewCustomAttributes = "";

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->CurrentValue;
		$this->id_tipodiscapacidad->ViewCustomAttributes = "";

		// resultado
		$this->resultado->ViewValue = $this->resultado->CurrentValue;
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

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$this->tipo->ViewValue = $this->tipo->OptionCaption($this->tipo->CurrentValue);
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

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
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidopaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `apoderado`";
		$sWhereWrk = "";
		$this->id_apoderado->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidopaterno`');
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
		}
		$this->id_referencia->ViewCustomAttributes = "";

			// fecha_tamizaje
			$this->fecha_tamizaje->LinkCustomAttributes = "";
			$this->fecha_tamizaje->HrefValue = "";
			$this->fecha_tamizaje->TooltipValue = "";

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";
			$this->id_centro->TooltipValue = "";

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

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// dias
			$this->dias->LinkCustomAttributes = "";
			$this->dias->HrefValue = "";
			$this->dias->TooltipValue = "";

			// semanas
			$this->semanas->LinkCustomAttributes = "";
			$this->semanas->HrefValue = "";
			$this->semanas->TooltipValue = "";

			// meses
			$this->meses->LinkCustomAttributes = "";
			$this->meses->HrefValue = "";
			$this->meses->TooltipValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";
			$this->sexo->TooltipValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";
			$this->discapacidad->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// fecha_tamizaje
			// id_centro

			$this->id_centro->EditAttrs["class"] = "form-control";
			$this->id_centro->EditCustomAttributes = "";
			if (trim(strval($this->id_centro->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_centro->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `institucionesdesalud`";
			$sWhereWrk = "";
			$this->id_centro->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_centro, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_centro->EditValue = $arwrk;

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

			// dias
			$this->dias->EditAttrs["class"] = "form-control";
			$this->dias->EditCustomAttributes = "";
			$this->dias->EditValue = ew_HtmlEncode($this->dias->CurrentValue);
			$this->dias->PlaceHolder = ew_RemoveHtml($this->dias->FldCaption());

			// semanas
			$this->semanas->EditAttrs["class"] = "form-control";
			$this->semanas->EditCustomAttributes = "";
			$this->semanas->EditValue = ew_HtmlEncode($this->semanas->CurrentValue);
			$this->semanas->PlaceHolder = ew_RemoveHtml($this->semanas->FldCaption());

			// meses
			$this->meses->EditAttrs["class"] = "form-control";
			$this->meses->EditCustomAttributes = "";
			$this->meses->EditValue = ew_HtmlEncode($this->meses->CurrentValue);
			$this->meses->PlaceHolder = ew_RemoveHtml($this->meses->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// discapacidad
			$this->discapacidad->EditAttrs["class"] = "form-control";
			$this->discapacidad->EditCustomAttributes = "";
			$this->discapacidad->EditValue = ew_HtmlEncode($this->discapacidad->CurrentValue);
			if (strval($this->discapacidad->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
			$sWhereWrk = "";
			$this->discapacidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->discapacidad->EditValue = $this->discapacidad->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->discapacidad->EditValue = ew_HtmlEncode($this->discapacidad->CurrentValue);
				}
			} else {
				$this->discapacidad->EditValue = NULL;
			}
			$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->EditAttrs["class"] = "form-control";
			$this->id_tipodiscapacidad->EditCustomAttributes = "";
			$this->id_tipodiscapacidad->EditValue = ew_HtmlEncode($this->id_tipodiscapacidad->CurrentValue);
			$this->id_tipodiscapacidad->PlaceHolder = ew_RemoveHtml($this->id_tipodiscapacidad->FldCaption());

			// resultado
			$this->resultado->EditAttrs["class"] = "form-control";
			$this->resultado->EditCustomAttributes = "";
			$this->resultado->EditValue = ew_HtmlEncode($this->resultado->CurrentValue);
			$this->resultado->PlaceHolder = ew_RemoveHtml($this->resultado->FldCaption());

			// resultadotamizaje
			$this->resultadotamizaje->EditAttrs["class"] = "form-control";
			$this->resultadotamizaje->EditCustomAttributes = "";
			$this->resultadotamizaje->EditValue = ew_HtmlEncode($this->resultadotamizaje->CurrentValue);
			$this->resultadotamizaje->PlaceHolder = ew_RemoveHtml($this->resultadotamizaje->FldCaption());

			// tapon
			$this->tapon->EditCustomAttributes = "";
			$this->tapon->EditValue = $this->tapon->Options(FALSE);

			// tipo
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = $this->tipo->Options(FALSE);

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
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidopaterno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `apoderado`";
			$sWhereWrk = "";
			$this->id_apoderado->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidopaterno`');
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

			// Add refer script
			// fecha_tamizaje

			$this->fecha_tamizaje->LinkCustomAttributes = "";
			$this->fecha_tamizaje->HrefValue = "";

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";

			// dias
			$this->dias->LinkCustomAttributes = "";
			$this->dias->HrefValue = "";

			// semanas
			$this->semanas->LinkCustomAttributes = "";
			$this->semanas->HrefValue = "";

			// meses
			$this->meses->LinkCustomAttributes = "";
			$this->meses->HrefValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";

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
		if (!$this->id_centro->FldIsDetailKey && !is_null($this->id_centro->FormValue) && $this->id_centro->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_centro->FldCaption(), $this->id_centro->ReqErrMsg));
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
		if (!$this->ci->FldIsDetailKey && !is_null($this->ci->FormValue) && $this->ci->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ci->FldCaption(), $this->ci->ReqErrMsg));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey && !is_null($this->fecha_nacimiento->FormValue) && $this->fecha_nacimiento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha_nacimiento->FldCaption(), $this->fecha_nacimiento->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->fecha_nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_nacimiento->FldErrMsg());
		}
		if (!$this->dias->FldIsDetailKey && !is_null($this->dias->FormValue) && $this->dias->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dias->FldCaption(), $this->dias->ReqErrMsg));
		}
		if (!$this->semanas->FldIsDetailKey && !is_null($this->semanas->FormValue) && $this->semanas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->semanas->FldCaption(), $this->semanas->ReqErrMsg));
		}
		if (!$this->meses->FldIsDetailKey && !is_null($this->meses->FormValue) && $this->meses->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->meses->FldCaption(), $this->meses->ReqErrMsg));
		}
		if (!$this->sexo->FldIsDetailKey && !is_null($this->sexo->FormValue) && $this->sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sexo->FldCaption(), $this->sexo->ReqErrMsg));
		}
		if (!$this->discapacidad->FldIsDetailKey && !is_null($this->discapacidad->FormValue) && $this->discapacidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->discapacidad->FldCaption(), $this->discapacidad->ReqErrMsg));
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

		// fecha_tamizaje
		$this->fecha_tamizaje->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
		$rsnew['fecha_tamizaje'] = &$this->fecha_tamizaje->DbValue;

		// id_centro
		$this->id_centro->SetDbValueDef($rsnew, $this->id_centro->CurrentValue, 0, FALSE);

		// apellidopaterno
		$this->apellidopaterno->SetDbValueDef($rsnew, $this->apellidopaterno->CurrentValue, "", FALSE);

		// apellidomaterno
		$this->apellidomaterno->SetDbValueDef($rsnew, $this->apellidomaterno->CurrentValue, "", FALSE);

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", FALSE);

		// ci
		$this->ci->SetDbValueDef($rsnew, $this->ci->CurrentValue, "", FALSE);

		// fecha_nacimiento
		$this->fecha_nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// dias
		$this->dias->SetDbValueDef($rsnew, $this->dias->CurrentValue, "", FALSE);

		// semanas
		$this->semanas->SetDbValueDef($rsnew, $this->semanas->CurrentValue, "", FALSE);

		// meses
		$this->meses->SetDbValueDef($rsnew, $this->meses->CurrentValue, "", FALSE);

		// sexo
		$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, "", FALSE);

		// discapacidad
		$this->discapacidad->SetDbValueDef($rsnew, $this->discapacidad->CurrentValue, 0, FALSE);

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad->SetDbValueDef($rsnew, $this->id_tipodiscapacidad->CurrentValue, NULL, FALSE);

		// resultado
		$this->resultado->SetDbValueDef($rsnew, $this->resultado->CurrentValue, NULL, FALSE);

		// resultadotamizaje
		$this->resultadotamizaje->SetDbValueDef($rsnew, $this->resultadotamizaje->CurrentValue, NULL, FALSE);

		// tapon
		$this->tapon->SetDbValueDef($rsnew, $this->tapon->CurrentValue, NULL, FALSE);

		// tipo
		$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, NULL, FALSE);

		// repetirprueba
		$this->repetirprueba->SetDbValueDef($rsnew, $this->repetirprueba->CurrentValue, NULL, FALSE);

		// observaciones
		$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, FALSE);

		// id_apoderado
		$this->id_apoderado->SetDbValueDef($rsnew, $this->id_apoderado->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("neonatallist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_centro":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucionesdesalud`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_centro, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_discapacidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_apoderado":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidopaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `apoderado`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidopaterno`');
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
		case "x_discapacidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld` FROM `discapacidad`";
			$sWhereWrk = "`nombre` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($neonatal_addopt)) $neonatal_addopt = new cneonatal_addopt();

// Page init
$neonatal_addopt->Page_Init();

// Page main
$neonatal_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$neonatal_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = fneonataladdopt = new ew_Form("fneonataladdopt", "addopt");

// Validate form
fneonataladdopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->id_centro->FldCaption(), $neonatal->id_centro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidopaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->apellidopaterno->FldCaption(), $neonatal->apellidopaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidomaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->apellidomaterno->FldCaption(), $neonatal->apellidomaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->nombre->FldCaption(), $neonatal->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ci");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->ci->FldCaption(), $neonatal->ci->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->fecha_nacimiento->FldCaption(), $neonatal->fecha_nacimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($neonatal->fecha_nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dias");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->dias->FldCaption(), $neonatal->dias->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_semanas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->semanas->FldCaption(), $neonatal->semanas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_meses");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->meses->FldCaption(), $neonatal->meses->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->sexo->FldCaption(), $neonatal->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_discapacidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $neonatal->discapacidad->FldCaption(), $neonatal->discapacidad->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fneonataladdopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fneonataladdopt.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fneonataladdopt.Lists["x_id_centro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institucionesdesalud"};
fneonataladdopt.Lists["x_id_centro"].Data = "<?php echo $neonatal_addopt->id_centro->LookupFilterQuery(FALSE, "addopt") ?>";
fneonataladdopt.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonataladdopt.Lists["x_sexo"].Options = <?php echo json_encode($neonatal_addopt->sexo->Options()) ?>;
fneonataladdopt.Lists["x_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fneonataladdopt.Lists["x_discapacidad"].Data = "<?php echo $neonatal_addopt->discapacidad->LookupFilterQuery(FALSE, "addopt") ?>";
fneonataladdopt.AutoSuggests["x_discapacidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $neonatal_addopt->discapacidad->LookupFilterQuery(TRUE, "addopt"))) ?>;
fneonataladdopt.Lists["x_tapon"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonataladdopt.Lists["x_tapon"].Options = <?php echo json_encode($neonatal_addopt->tapon->Options()) ?>;
fneonataladdopt.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonataladdopt.Lists["x_tipo"].Options = <?php echo json_encode($neonatal_addopt->tipo->Options()) ?>;
fneonataladdopt.Lists["x_repetirprueba"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonataladdopt.Lists["x_repetirprueba"].Options = <?php echo json_encode($neonatal_addopt->repetirprueba->Options()) ?>;
fneonataladdopt.Lists["x_id_apoderado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidopaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"apoderado"};
fneonataladdopt.Lists["x_id_apoderado"].Data = "<?php echo $neonatal_addopt->id_apoderado->LookupFilterQuery(FALSE, "addopt") ?>";
fneonataladdopt.Lists["x_id_referencia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombrescentromedico","x_nombrescompleto","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referencia"};
fneonataladdopt.Lists["x_id_referencia"].Data = "<?php echo $neonatal_addopt->id_referencia->LookupFilterQuery(FALSE, "addopt") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$neonatal_addopt->ShowMessage();
?>
<form name="fneonataladdopt" id="fneonataladdopt" class="ewForm form-horizontal" action="neonataladdopt.php" method="post">
<?php if ($neonatal_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $neonatal_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="neonatal">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($neonatal->fecha_tamizaje->Visible) { // fecha_tamizaje ?>
<?php } ?>
<?php if ($neonatal->id_centro->Visible) { // id_centro ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_centro"><?php echo $neonatal->id_centro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<select data-table="neonatal" data-field="x_id_centro" data-value-separator="<?php echo $neonatal->id_centro->DisplayValueSeparatorAttribute() ?>" id="x_id_centro" name="x_id_centro"<?php echo $neonatal->id_centro->EditAttributes() ?>>
<?php echo $neonatal->id_centro->SelectOptionListHtml("x_id_centro") ?>
</select>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->apellidopaterno->Visible) { // apellidopaterno ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_apellidopaterno"><?php echo $neonatal->apellidopaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $neonatal->apellidopaterno->EditValue ?>"<?php echo $neonatal->apellidopaterno->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->apellidomaterno->Visible) { // apellidomaterno ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_apellidomaterno"><?php echo $neonatal->apellidomaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $neonatal->apellidomaterno->EditValue ?>"<?php echo $neonatal->apellidomaterno->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->nombre->Visible) { // nombre ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_nombre"><?php echo $neonatal->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->nombre->getPlaceHolder()) ?>" value="<?php echo $neonatal->nombre->EditValue ?>"<?php echo $neonatal->nombre->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->ci->Visible) { // ci ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_ci"><?php echo $neonatal->ci->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->ci->getPlaceHolder()) ?>" value="<?php echo $neonatal->ci->EditValue ?>"<?php echo $neonatal->ci->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_fecha_nacimiento"><?php echo $neonatal->fecha_nacimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_fecha_nacimiento" name="x_fecha_nacimiento" id="x_fecha_nacimiento" placeholder="<?php echo ew_HtmlEncode($neonatal->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $neonatal->fecha_nacimiento->EditValue ?>"<?php echo $neonatal->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$neonatal->fecha_nacimiento->ReadOnly && !$neonatal->fecha_nacimiento->Disabled && !isset($neonatal->fecha_nacimiento->EditAttrs["readonly"]) && !isset($neonatal->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fneonataladdopt", "x_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->dias->Visible) { // dias ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_dias"><?php echo $neonatal->dias->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_dias" name="x_dias" id="x_dias" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->dias->getPlaceHolder()) ?>" value="<?php echo $neonatal->dias->EditValue ?>"<?php echo $neonatal->dias->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->semanas->Visible) { // semanas ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_semanas"><?php echo $neonatal->semanas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_semanas" name="x_semanas" id="x_semanas" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->semanas->getPlaceHolder()) ?>" value="<?php echo $neonatal->semanas->EditValue ?>"<?php echo $neonatal->semanas->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->meses->Visible) { // meses ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_meses"><?php echo $neonatal->meses->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_meses" name="x_meses" id="x_meses" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->meses->getPlaceHolder()) ?>" value="<?php echo $neonatal->meses->EditValue ?>"<?php echo $neonatal->meses->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->sexo->Visible) { // sexo ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_sexo"><?php echo $neonatal->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<select data-table="neonatal" data-field="x_sexo" data-value-separator="<?php echo $neonatal->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $neonatal->sexo->EditAttributes() ?>>
<?php echo $neonatal->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->discapacidad->Visible) { // discapacidad ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel"><?php echo $neonatal->discapacidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<?php
$wrkonchange = trim(" " . @$neonatal->discapacidad->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$neonatal->discapacidad->EditAttrs["onchange"] = "";
?>
<span id="as_x_discapacidad" style="white-space: nowrap; z-index: 8870">
	<input type="text" name="sv_x_discapacidad" id="sv_x_discapacidad" value="<?php echo $neonatal->discapacidad->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->discapacidad->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($neonatal->discapacidad->getPlaceHolder()) ?>"<?php echo $neonatal->discapacidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="neonatal" data-field="x_discapacidad" data-value-separator="<?php echo $neonatal->discapacidad->DisplayValueSeparatorAttribute() ?>" name="x_discapacidad" id="x_discapacidad" value="<?php echo ew_HtmlEncode($neonatal->discapacidad->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fneonataladdopt.CreateAutoSuggest({"id":"x_discapacidad","forceSelect":false});
</script>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_tipodiscapacidad"><?php echo $neonatal->id_tipodiscapacidad->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_id_tipodiscapacidad" name="x_id_tipodiscapacidad" id="x_id_tipodiscapacidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->id_tipodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $neonatal->id_tipodiscapacidad->EditValue ?>"<?php echo $neonatal->id_tipodiscapacidad->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->resultado->Visible) { // resultado ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_resultado"><?php echo $neonatal->resultado->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_resultado" name="x_resultado" id="x_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->resultado->getPlaceHolder()) ?>" value="<?php echo $neonatal->resultado->EditValue ?>"<?php echo $neonatal->resultado->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->resultadotamizaje->Visible) { // resultadotamizaje ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_resultadotamizaje"><?php echo $neonatal->resultadotamizaje->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_resultadotamizaje" name="x_resultadotamizaje" id="x_resultadotamizaje" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->resultadotamizaje->getPlaceHolder()) ?>" value="<?php echo $neonatal->resultadotamizaje->EditValue ?>"<?php echo $neonatal->resultadotamizaje->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->tapon->Visible) { // tapon ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel"><?php echo $neonatal->tapon->FldCaption() ?></label>
		<div class="col-sm-10">
<div id="tp_x_tapon" class="ewTemplate"><input type="radio" data-table="neonatal" data-field="x_tapon" data-value-separator="<?php echo $neonatal->tapon->DisplayValueSeparatorAttribute() ?>" name="x_tapon" id="x_tapon" value="{value}"<?php echo $neonatal->tapon->EditAttributes() ?>></div>
<div id="dsl_x_tapon" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $neonatal->tapon->RadioButtonListHtml(FALSE, "x_tapon") ?>
</div></div>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->tipo->Visible) { // tipo ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel"><?php echo $neonatal->tipo->FldCaption() ?></label>
		<div class="col-sm-10">
<div id="tp_x_tipo" class="ewTemplate"><input type="radio" data-table="neonatal" data-field="x_tipo" data-value-separator="<?php echo $neonatal->tipo->DisplayValueSeparatorAttribute() ?>" name="x_tipo" id="x_tipo" value="{value}"<?php echo $neonatal->tipo->EditAttributes() ?>></div>
<div id="dsl_x_tipo" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $neonatal->tipo->RadioButtonListHtml(FALSE, "x_tipo") ?>
</div></div>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->repetirprueba->Visible) { // repetirprueba ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel"><?php echo $neonatal->repetirprueba->FldCaption() ?></label>
		<div class="col-sm-10">
<div id="tp_x_repetirprueba" class="ewTemplate"><input type="radio" data-table="neonatal" data-field="x_repetirprueba" data-value-separator="<?php echo $neonatal->repetirprueba->DisplayValueSeparatorAttribute() ?>" name="x_repetirprueba" id="x_repetirprueba" value="{value}"<?php echo $neonatal->repetirprueba->EditAttributes() ?>></div>
<div id="dsl_x_repetirprueba" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $neonatal->repetirprueba->RadioButtonListHtml(FALSE, "x_repetirprueba") ?>
</div></div>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->observaciones->Visible) { // observaciones ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_observaciones"><?php echo $neonatal->observaciones->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="neonatal" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->observaciones->getPlaceHolder()) ?>" value="<?php echo $neonatal->observaciones->EditValue ?>"<?php echo $neonatal->observaciones->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->id_apoderado->Visible) { // id_apoderado ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_apoderado"><?php echo $neonatal->id_apoderado->FldCaption() ?></label>
		<div class="col-sm-10">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_apoderado"><?php echo (strval($neonatal->id_apoderado->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $neonatal->id_apoderado->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($neonatal->id_apoderado->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_apoderado',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($neonatal->id_apoderado->ReadOnly || $neonatal->id_apoderado->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="neonatal" data-field="x_id_apoderado" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $neonatal->id_apoderado->DisplayValueSeparatorAttribute() ?>" name="x_id_apoderado" id="x_id_apoderado" value="<?php echo $neonatal->id_apoderado->CurrentValue ?>"<?php echo $neonatal->id_apoderado->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($neonatal->id_referencia->Visible) { // id_referencia ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_referencia"><?php echo $neonatal->id_referencia->FldCaption() ?></label>
		<div class="col-sm-10">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_referencia"><?php echo (strval($neonatal->id_referencia->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $neonatal->id_referencia->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($neonatal->id_referencia->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_referencia',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($neonatal->id_referencia->ReadOnly || $neonatal->id_referencia->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="neonatal" data-field="x_id_referencia" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $neonatal->id_referencia->DisplayValueSeparatorAttribute() ?>" name="x_id_referencia" id="x_id_referencia" value="<?php echo $neonatal->id_referencia->CurrentValue ?>"<?php echo $neonatal->id_referencia->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
</form>
<script type="text/javascript">
fneonataladdopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$neonatal_addopt->Page_Terminate();
?>
