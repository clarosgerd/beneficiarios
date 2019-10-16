<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "participanteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$participante_edit = NULL; // Initialize page object first

class cparticipante_edit extends cparticipante {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'participante';

	// Page object name
	var $PageObjName = 'participante_edit';

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

		// Table object (participante)
		if (!isset($GLOBALS["participante"]) || get_class($GLOBALS["participante"]) == "cparticipante") {
			$GLOBALS["participante"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["participante"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'participante', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("participantelist.php"));
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
		$this->id_actividad->SetVisibility();
		$this->id_categoria->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombre->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->ci->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->celular->SetVisibility();
		$this->direcciondomicilio->SetVisibility();
		$this->ocupacion->SetVisibility();
		$this->_email->SetVisibility();
		$this->cargo->SetVisibility();
		$this->nivelestudio->SetVisibility();
		$this->id_institucion->SetVisibility();
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
		global $EW_EXPORT, $participante;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($participante);
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
					if ($pageName == "participanteview.php")
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
					$this->Page_Terminate("participantelist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "participantelist.php")
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
		if (!$this->id_actividad->FldIsDetailKey) {
			$this->id_actividad->setFormValue($objForm->GetValue("x_id_actividad"));
		}
		if (!$this->id_categoria->FldIsDetailKey) {
			$this->id_categoria->setFormValue($objForm->GetValue("x_id_categoria"));
		}
		if (!$this->apellidopaterno->FldIsDetailKey) {
			$this->apellidopaterno->setFormValue($objForm->GetValue("x_apellidopaterno"));
		}
		if (!$this->apellidomaterno->FldIsDetailKey) {
			$this->apellidomaterno->setFormValue($objForm->GetValue("x_apellidomaterno"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey) {
			$this->fecha_nacimiento->setFormValue($objForm->GetValue("x_fecha_nacimiento"));
			$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		}
		if (!$this->sexo->FldIsDetailKey) {
			$this->sexo->setFormValue($objForm->GetValue("x_sexo"));
		}
		if (!$this->ci->FldIsDetailKey) {
			$this->ci->setFormValue($objForm->GetValue("x_ci"));
		}
		if (!$this->nrodiscapacidad->FldIsDetailKey) {
			$this->nrodiscapacidad->setFormValue($objForm->GetValue("x_nrodiscapacidad"));
		}
		if (!$this->celular->FldIsDetailKey) {
			$this->celular->setFormValue($objForm->GetValue("x_celular"));
		}
		if (!$this->direcciondomicilio->FldIsDetailKey) {
			$this->direcciondomicilio->setFormValue($objForm->GetValue("x_direcciondomicilio"));
		}
		if (!$this->ocupacion->FldIsDetailKey) {
			$this->ocupacion->setFormValue($objForm->GetValue("x_ocupacion"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->cargo->FldIsDetailKey) {
			$this->cargo->setFormValue($objForm->GetValue("x_cargo"));
		}
		if (!$this->nivelestudio->FldIsDetailKey) {
			$this->nivelestudio->setFormValue($objForm->GetValue("x_nivelestudio"));
		}
		if (!$this->id_institucion->FldIsDetailKey) {
			$this->id_institucion->setFormValue($objForm->GetValue("x_id_institucion"));
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
		$this->id->CurrentValue = $this->id->FormValue;
		$this->id_sector->CurrentValue = $this->id_sector->FormValue;
		$this->id_actividad->CurrentValue = $this->id_actividad->FormValue;
		$this->id_categoria->CurrentValue = $this->id_categoria->FormValue;
		$this->apellidopaterno->CurrentValue = $this->apellidopaterno->FormValue;
		$this->apellidomaterno->CurrentValue = $this->apellidomaterno->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->fecha_nacimiento->CurrentValue = $this->fecha_nacimiento->FormValue;
		$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		$this->sexo->CurrentValue = $this->sexo->FormValue;
		$this->ci->CurrentValue = $this->ci->FormValue;
		$this->nrodiscapacidad->CurrentValue = $this->nrodiscapacidad->FormValue;
		$this->celular->CurrentValue = $this->celular->FormValue;
		$this->direcciondomicilio->CurrentValue = $this->direcciondomicilio->FormValue;
		$this->ocupacion->CurrentValue = $this->ocupacion->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->cargo->CurrentValue = $this->cargo->FormValue;
		$this->nivelestudio->CurrentValue = $this->nivelestudio->FormValue;
		$this->id_institucion->CurrentValue = $this->id_institucion->FormValue;
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
		$this->id_actividad->setDbValue($row['id_actividad']);
		$this->id_categoria->setDbValue($row['id_categoria']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->ci->setDbValue($row['ci']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->celular->setDbValue($row['celular']);
		$this->direcciondomicilio->setDbValue($row['direcciondomicilio']);
		$this->ocupacion->setDbValue($row['ocupacion']);
		$this->_email->setDbValue($row['email']);
		$this->cargo->setDbValue($row['cargo']);
		$this->nivelestudio->setDbValue($row['nivelestudio']);
		$this->id_institucion->setDbValue($row['id_institucion']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_sector'] = NULL;
		$row['id_actividad'] = NULL;
		$row['id_categoria'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombre'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['ci'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['celular'] = NULL;
		$row['direcciondomicilio'] = NULL;
		$row['ocupacion'] = NULL;
		$row['email'] = NULL;
		$row['cargo'] = NULL;
		$row['nivelestudio'] = NULL;
		$row['id_institucion'] = NULL;
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
		$this->id_actividad->DbValue = $row['id_actividad'];
		$this->id_categoria->DbValue = $row['id_categoria'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->ci->DbValue = $row['ci'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->celular->DbValue = $row['celular'];
		$this->direcciondomicilio->DbValue = $row['direcciondomicilio'];
		$this->ocupacion->DbValue = $row['ocupacion'];
		$this->_email->DbValue = $row['email'];
		$this->cargo->DbValue = $row['cargo'];
		$this->nivelestudio->DbValue = $row['nivelestudio'];
		$this->id_institucion->DbValue = $row['id_institucion'];
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
		// id_actividad
		// id_categoria
		// apellidopaterno
		// apellidomaterno
		// nombre
		// fecha_nacimiento
		// sexo
		// ci
		// nrodiscapacidad
		// celular
		// direcciondomicilio
		// ocupacion
		// email
		// cargo
		// nivelestudio
		// id_institucion
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

		// id_actividad
		if (strval($this->id_actividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
		$sWhereWrk = "";
		$this->id_actividad->LookupFilters = array("dx1" => '`nombreactividad`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_actividad->ViewValue = $this->id_actividad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_actividad->ViewValue = $this->id_actividad->CurrentValue;
			}
		} else {
			$this->id_actividad->ViewValue = NULL;
		}
		$this->id_actividad->ViewCustomAttributes = "";

		// id_categoria
		if (strval($this->id_categoria->CurrentValue) <> "") {
			$arwrk = explode(",", $this->id_categoria->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
		$sWhereWrk = "";
		$this->id_categoria->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_categoria, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_categoria->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->id_categoria->ViewValue .= $this->id_categoria->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->id_categoria->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->id_categoria->ViewValue = $this->id_categoria->CurrentValue;
			}
		} else {
			$this->id_categoria->ViewValue = NULL;
		}
		$this->id_categoria->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 7);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// direcciondomicilio
		$this->direcciondomicilio->ViewValue = $this->direcciondomicilio->CurrentValue;
		$this->direcciondomicilio->ViewCustomAttributes = "";

		// ocupacion
		$this->ocupacion->ViewValue = $this->ocupacion->CurrentValue;
		$this->ocupacion->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// cargo
		$this->cargo->ViewValue = $this->cargo->CurrentValue;
		$this->cargo->ViewCustomAttributes = "";

		// nivelestudio
		$this->nivelestudio->ViewValue = $this->nivelestudio->CurrentValue;
		$this->nivelestudio->ViewCustomAttributes = "";

		// id_institucion
		if (strval($this->id_institucion->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_institucion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->id_institucion->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_institucion, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_institucion->ViewValue = $this->id_institucion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_institucion->ViewValue = $this->id_institucion->CurrentValue;
			}
		} else {
			$this->id_institucion->ViewValue = NULL;
		}
		$this->id_institucion->ViewCustomAttributes = "";

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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_sector
			$this->id_sector->LinkCustomAttributes = "";
			$this->id_sector->HrefValue = "";
			$this->id_sector->TooltipValue = "";

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";
			$this->id_actividad->TooltipValue = "";

			// id_categoria
			$this->id_categoria->LinkCustomAttributes = "";
			$this->id_categoria->HrefValue = "";
			$this->id_categoria->TooltipValue = "";

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

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";
			$this->sexo->TooltipValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
			$this->nrodiscapacidad->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// direcciondomicilio
			$this->direcciondomicilio->LinkCustomAttributes = "";
			$this->direcciondomicilio->HrefValue = "";
			$this->direcciondomicilio->TooltipValue = "";

			// ocupacion
			$this->ocupacion->LinkCustomAttributes = "";
			$this->ocupacion->HrefValue = "";
			$this->ocupacion->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// cargo
			$this->cargo->LinkCustomAttributes = "";
			$this->cargo->HrefValue = "";
			$this->cargo->TooltipValue = "";

			// nivelestudio
			$this->nivelestudio->LinkCustomAttributes = "";
			$this->nivelestudio->HrefValue = "";
			$this->nivelestudio->TooltipValue = "";

			// id_institucion
			$this->id_institucion->LinkCustomAttributes = "";
			$this->id_institucion->HrefValue = "";
			$this->id_institucion->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";
			$this->id_centro->TooltipValue = "";
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

			// id_actividad
			$this->id_actividad->EditCustomAttributes = "";
			if (trim(strval($this->id_actividad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `actividad`";
			$sWhereWrk = "";
			$this->id_actividad->LookupFilters = array("dx1" => '`nombreactividad`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_actividad->ViewValue = $this->id_actividad->DisplayValue($arwrk);
			} else {
				$this->id_actividad->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_actividad->EditValue = $arwrk;

			// id_categoria
			$this->id_categoria->EditCustomAttributes = "";
			if (trim(strval($this->id_categoria->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->id_categoria->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categoria`";
			$sWhereWrk = "";
			$this->id_categoria->LookupFilters = array("dx1" => '`nombre`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_categoria, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_categoria->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->id_categoria->ViewValue .= $this->id_categoria->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->id_categoria->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->id_categoria->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_categoria->EditValue = $arwrk;

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

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 7));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// ci
			$this->ci->EditAttrs["class"] = "form-control";
			$this->ci->EditCustomAttributes = "";
			$this->ci->EditValue = ew_HtmlEncode($this->ci->CurrentValue);
			$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

			// nrodiscapacidad
			$this->nrodiscapacidad->EditAttrs["class"] = "form-control";
			$this->nrodiscapacidad->EditCustomAttributes = "";
			$this->nrodiscapacidad->EditValue = ew_HtmlEncode($this->nrodiscapacidad->CurrentValue);
			$this->nrodiscapacidad->PlaceHolder = ew_RemoveHtml($this->nrodiscapacidad->FldCaption());

			// celular
			$this->celular->EditAttrs["class"] = "form-control";
			$this->celular->EditCustomAttributes = "";
			$this->celular->EditValue = ew_HtmlEncode($this->celular->CurrentValue);
			$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

			// direcciondomicilio
			$this->direcciondomicilio->EditAttrs["class"] = "form-control";
			$this->direcciondomicilio->EditCustomAttributes = "";
			$this->direcciondomicilio->EditValue = ew_HtmlEncode($this->direcciondomicilio->CurrentValue);
			$this->direcciondomicilio->PlaceHolder = ew_RemoveHtml($this->direcciondomicilio->FldCaption());

			// ocupacion
			$this->ocupacion->EditAttrs["class"] = "form-control";
			$this->ocupacion->EditCustomAttributes = "";
			$this->ocupacion->EditValue = ew_HtmlEncode($this->ocupacion->CurrentValue);
			$this->ocupacion->PlaceHolder = ew_RemoveHtml($this->ocupacion->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// cargo
			$this->cargo->EditAttrs["class"] = "form-control";
			$this->cargo->EditCustomAttributes = "";
			$this->cargo->EditValue = ew_HtmlEncode($this->cargo->CurrentValue);
			$this->cargo->PlaceHolder = ew_RemoveHtml($this->cargo->FldCaption());

			// nivelestudio
			$this->nivelestudio->EditAttrs["class"] = "form-control";
			$this->nivelestudio->EditCustomAttributes = "";
			$this->nivelestudio->EditValue = ew_HtmlEncode($this->nivelestudio->CurrentValue);
			$this->nivelestudio->PlaceHolder = ew_RemoveHtml($this->nivelestudio->FldCaption());

			// id_institucion
			$this->id_institucion->EditAttrs["class"] = "form-control";
			$this->id_institucion->EditCustomAttributes = "";
			if (trim(strval($this->id_institucion->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_institucion->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `unidadeducativa`";
			$sWhereWrk = "";
			$this->id_institucion->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_institucion, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_institucion->EditValue = $arwrk;

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

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// id_sector
			$this->id_sector->LinkCustomAttributes = "";
			$this->id_sector->HrefValue = "";

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";

			// id_categoria
			$this->id_categoria->LinkCustomAttributes = "";
			$this->id_categoria->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";

			// direcciondomicilio
			$this->direcciondomicilio->LinkCustomAttributes = "";
			$this->direcciondomicilio->HrefValue = "";

			// ocupacion
			$this->ocupacion->LinkCustomAttributes = "";
			$this->ocupacion->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// cargo
			$this->cargo->LinkCustomAttributes = "";
			$this->cargo->HrefValue = "";

			// nivelestudio
			$this->nivelestudio->LinkCustomAttributes = "";
			$this->nivelestudio->HrefValue = "";

			// id_institucion
			$this->id_institucion->LinkCustomAttributes = "";
			$this->id_institucion->HrefValue = "";

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
		if ($this->id_sector->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_sector->FldCaption(), $this->id_sector->ReqErrMsg));
		}
		if (!$this->id_actividad->FldIsDetailKey && !is_null($this->id_actividad->FormValue) && $this->id_actividad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_actividad->FldCaption(), $this->id_actividad->ReqErrMsg));
		}
		if ($this->id_categoria->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_categoria->FldCaption(), $this->id_categoria->ReqErrMsg));
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
		if (!$this->fecha_nacimiento->FldIsDetailKey && !is_null($this->fecha_nacimiento->FormValue) && $this->fecha_nacimiento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha_nacimiento->FldCaption(), $this->fecha_nacimiento->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha_nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_nacimiento->FldErrMsg());
		}
		if (!$this->sexo->FldIsDetailKey && !is_null($this->sexo->FormValue) && $this->sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sexo->FldCaption(), $this->sexo->ReqErrMsg));
		}
		if (!ew_CheckEmail($this->_email->FormValue)) {
			ew_AddMessage($gsFormError, $this->_email->FldErrMsg());
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

			// id_actividad
			$this->id_actividad->SetDbValueDef($rsnew, $this->id_actividad->CurrentValue, 0, $this->id_actividad->ReadOnly);

			// id_categoria
			$this->id_categoria->SetDbValueDef($rsnew, $this->id_categoria->CurrentValue, 0, $this->id_categoria->ReadOnly);

			// apellidopaterno
			$this->apellidopaterno->SetDbValueDef($rsnew, $this->apellidopaterno->CurrentValue, "", $this->apellidopaterno->ReadOnly);

			// apellidomaterno
			$this->apellidomaterno->SetDbValueDef($rsnew, $this->apellidomaterno->CurrentValue, "", $this->apellidomaterno->ReadOnly);

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", $this->nombre->ReadOnly);

			// fecha_nacimiento
			$this->fecha_nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7), ew_CurrentDate(), $this->fecha_nacimiento->ReadOnly);

			// sexo
			$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, "", $this->sexo->ReadOnly);

			// ci
			$this->ci->SetDbValueDef($rsnew, $this->ci->CurrentValue, NULL, $this->ci->ReadOnly);

			// nrodiscapacidad
			$this->nrodiscapacidad->SetDbValueDef($rsnew, $this->nrodiscapacidad->CurrentValue, NULL, $this->nrodiscapacidad->ReadOnly);

			// celular
			$this->celular->SetDbValueDef($rsnew, $this->celular->CurrentValue, NULL, $this->celular->ReadOnly);

			// direcciondomicilio
			$this->direcciondomicilio->SetDbValueDef($rsnew, $this->direcciondomicilio->CurrentValue, NULL, $this->direcciondomicilio->ReadOnly);

			// ocupacion
			$this->ocupacion->SetDbValueDef($rsnew, $this->ocupacion->CurrentValue, NULL, $this->ocupacion->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

			// cargo
			$this->cargo->SetDbValueDef($rsnew, $this->cargo->CurrentValue, NULL, $this->cargo->ReadOnly);

			// nivelestudio
			$this->nivelestudio->SetDbValueDef($rsnew, $this->nivelestudio->CurrentValue, NULL, $this->nivelestudio->ReadOnly);

			// id_institucion
			$this->id_institucion->SetDbValueDef($rsnew, $this->id_institucion->CurrentValue, NULL, $this->id_institucion->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

			// id_centro
			$this->id_centro->SetDbValueDef($rsnew, $this->id_centro->CurrentValue, 0, $this->id_centro->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("participantelist.php"), "", $this->TableVar, TRUE);
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
		case "x_id_actividad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombreactividad`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_categoria":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nombre`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_categoria, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_institucion":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_institucion, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($participante_edit)) $participante_edit = new cparticipante_edit();

// Page init
$participante_edit->Page_Init();

// Page main
$participante_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$participante_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fparticipanteedit = new ew_Form("fparticipanteedit", "edit");

// Validate form
fparticipanteedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->id_sector->FldCaption(), $participante->id_sector->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_actividad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->id_actividad->FldCaption(), $participante->id_actividad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_categoria[]");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->id_categoria->FldCaption(), $participante->id_categoria->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidopaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->apellidopaterno->FldCaption(), $participante->apellidopaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellidomaterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->apellidomaterno->FldCaption(), $participante->apellidomaterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->nombre->FldCaption(), $participante->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->fecha_nacimiento->FldCaption(), $participante->fecha_nacimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($participante->fecha_nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->sexo->FldCaption(), $participante->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($participante->_email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_centro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $participante->id_centro->FldCaption(), $participante->id_centro->ReqErrMsg)) ?>");

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
fparticipanteedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fparticipanteedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fparticipanteedit.Lists["x_id_sector"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sector"};
fparticipanteedit.Lists["x_id_sector"].Data = "<?php echo $participante_edit->id_sector->LookupFilterQuery(FALSE, "edit") ?>";
fparticipanteedit.Lists["x_id_actividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreactividad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"actividad"};
fparticipanteedit.Lists["x_id_actividad"].Data = "<?php echo $participante_edit->id_actividad->LookupFilterQuery(FALSE, "edit") ?>";
fparticipanteedit.Lists["x_id_categoria[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categoria"};
fparticipanteedit.Lists["x_id_categoria[]"].Data = "<?php echo $participante_edit->id_categoria->LookupFilterQuery(FALSE, "edit") ?>";
fparticipanteedit.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fparticipanteedit.Lists["x_sexo"].Options = <?php echo json_encode($participante_edit->sexo->Options()) ?>;
fparticipanteedit.Lists["x_id_institucion"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fparticipanteedit.Lists["x_id_institucion"].Data = "<?php echo $participante_edit->id_institucion->LookupFilterQuery(FALSE, "edit") ?>";
fparticipanteedit.Lists["x_id_centro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreinstitucion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"centros"};
fparticipanteedit.Lists["x_id_centro"].Data = "<?php echo $participante_edit->id_centro->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $participante_edit->ShowPageHeader(); ?>
<?php
$participante_edit->ShowMessage();
?>
<form name="fparticipanteedit" id="fparticipanteedit" class="<?php echo $participante_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($participante_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $participante_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="participante">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($participante_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($participante->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_participante_id" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->id->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->id->CellAttributes() ?>>
<span id="el_participante_id">
<span<?php echo $participante->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $participante->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="participante" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($participante->id->CurrentValue) ?>">
<?php echo $participante->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->id_sector->Visible) { // id_sector ?>
	<div id="r_id_sector" class="form-group">
		<label id="elh_participante_id_sector" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->id_sector->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->id_sector->CellAttributes() ?>>
<span id="el_participante_id_sector">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_sector"><?php echo (strval($participante->id_sector->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $participante->id_sector->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($participante->id_sector->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_sector',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($participante->id_sector->ReadOnly || $participante->id_sector->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="participante" data-field="x_id_sector" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $participante->id_sector->DisplayValueSeparatorAttribute() ?>" name="x_id_sector" id="x_id_sector" value="<?php echo $participante->id_sector->CurrentValue ?>"<?php echo $participante->id_sector->EditAttributes() ?>>
</span>
<?php echo $participante->id_sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->id_actividad->Visible) { // id_actividad ?>
	<div id="r_id_actividad" class="form-group">
		<label id="elh_participante_id_actividad" for="x_id_actividad" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->id_actividad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->id_actividad->CellAttributes() ?>>
<span id="el_participante_id_actividad">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_actividad"><?php echo (strval($participante->id_actividad->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $participante->id_actividad->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($participante->id_actividad->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_actividad',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($participante->id_actividad->ReadOnly || $participante->id_actividad->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="participante" data-field="x_id_actividad" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $participante->id_actividad->DisplayValueSeparatorAttribute() ?>" name="x_id_actividad" id="x_id_actividad" value="<?php echo $participante->id_actividad->CurrentValue ?>"<?php echo $participante->id_actividad->EditAttributes() ?>>
</span>
<?php echo $participante->id_actividad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->id_categoria->Visible) { // id_categoria ?>
	<div id="r_id_categoria" class="form-group">
		<label id="elh_participante_id_categoria" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->id_categoria->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->id_categoria->CellAttributes() ?>>
<span id="el_participante_id_categoria">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_categoria"><?php echo (strval($participante->id_categoria->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $participante->id_categoria->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($participante->id_categoria->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_categoria[]',m:1,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($participante->id_categoria->ReadOnly || $participante->id_categoria->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="participante" data-field="x_id_categoria" data-multiple="1" data-lookup="1" data-value-separator="<?php echo $participante->id_categoria->DisplayValueSeparatorAttribute() ?>" name="x_id_categoria[]" id="x_id_categoria[]" value="<?php echo $participante->id_categoria->CurrentValue ?>"<?php echo $participante->id_categoria->EditAttributes() ?>>
</span>
<?php echo $participante->id_categoria->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="r_apellidopaterno" class="form-group">
		<label id="elh_participante_apellidopaterno" for="x_apellidopaterno" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->apellidopaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->apellidopaterno->CellAttributes() ?>>
<span id="el_participante_apellidopaterno">
<input type="text" data-table="participante" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $participante->apellidopaterno->EditValue ?>"<?php echo $participante->apellidopaterno->EditAttributes() ?>>
</span>
<?php echo $participante->apellidopaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="r_apellidomaterno" class="form-group">
		<label id="elh_participante_apellidomaterno" for="x_apellidomaterno" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->apellidomaterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->apellidomaterno->CellAttributes() ?>>
<span id="el_participante_apellidomaterno">
<input type="text" data-table="participante" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $participante->apellidomaterno->EditValue ?>"<?php echo $participante->apellidomaterno->EditAttributes() ?>>
</span>
<?php echo $participante->apellidomaterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_participante_nombre" for="x_nombre" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->nombre->CellAttributes() ?>>
<span id="el_participante_nombre">
<input type="text" data-table="participante" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->nombre->getPlaceHolder()) ?>" value="<?php echo $participante->nombre->EditValue ?>"<?php echo $participante->nombre->EditAttributes() ?>>
</span>
<?php echo $participante->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<div id="r_fecha_nacimiento" class="form-group">
		<label id="elh_participante_fecha_nacimiento" for="x_fecha_nacimiento" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->fecha_nacimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->fecha_nacimiento->CellAttributes() ?>>
<span id="el_participante_fecha_nacimiento">
<input type="text" data-table="participante" data-field="x_fecha_nacimiento" data-format="7" name="x_fecha_nacimiento" id="x_fecha_nacimiento" placeholder="<?php echo ew_HtmlEncode($participante->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $participante->fecha_nacimiento->EditValue ?>"<?php echo $participante->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$participante->fecha_nacimiento->ReadOnly && !$participante->fecha_nacimiento->Disabled && !isset($participante->fecha_nacimiento->EditAttrs["readonly"]) && !isset($participante->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fparticipanteedit", "x_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php echo $participante->fecha_nacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->sexo->Visible) { // sexo ?>
	<div id="r_sexo" class="form-group">
		<label id="elh_participante_sexo" for="x_sexo" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->sexo->CellAttributes() ?>>
<span id="el_participante_sexo">
<select data-table="participante" data-field="x_sexo" data-value-separator="<?php echo $participante->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $participante->sexo->EditAttributes() ?>>
<?php echo $participante->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</span>
<?php echo $participante->sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->ci->Visible) { // ci ?>
	<div id="r_ci" class="form-group">
		<label id="elh_participante_ci" for="x_ci" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->ci->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->ci->CellAttributes() ?>>
<span id="el_participante_ci">
<input type="text" data-table="participante" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->ci->getPlaceHolder()) ?>" value="<?php echo $participante->ci->EditValue ?>"<?php echo $participante->ci->EditAttributes() ?>>
</span>
<?php echo $participante->ci->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="r_nrodiscapacidad" class="form-group">
		<label id="elh_participante_nrodiscapacidad" for="x_nrodiscapacidad" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->nrodiscapacidad->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->nrodiscapacidad->CellAttributes() ?>>
<span id="el_participante_nrodiscapacidad">
<input type="text" data-table="participante" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $participante->nrodiscapacidad->EditValue ?>"<?php echo $participante->nrodiscapacidad->EditAttributes() ?>>
</span>
<?php echo $participante->nrodiscapacidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->celular->Visible) { // celular ?>
	<div id="r_celular" class="form-group">
		<label id="elh_participante_celular" for="x_celular" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->celular->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->celular->CellAttributes() ?>>
<span id="el_participante_celular">
<input type="text" data-table="participante" data-field="x_celular" name="x_celular" id="x_celular" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->celular->getPlaceHolder()) ?>" value="<?php echo $participante->celular->EditValue ?>"<?php echo $participante->celular->EditAttributes() ?>>
</span>
<?php echo $participante->celular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->direcciondomicilio->Visible) { // direcciondomicilio ?>
	<div id="r_direcciondomicilio" class="form-group">
		<label id="elh_participante_direcciondomicilio" for="x_direcciondomicilio" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->direcciondomicilio->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->direcciondomicilio->CellAttributes() ?>>
<span id="el_participante_direcciondomicilio">
<input type="text" data-table="participante" data-field="x_direcciondomicilio" name="x_direcciondomicilio" id="x_direcciondomicilio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->direcciondomicilio->getPlaceHolder()) ?>" value="<?php echo $participante->direcciondomicilio->EditValue ?>"<?php echo $participante->direcciondomicilio->EditAttributes() ?>>
</span>
<?php echo $participante->direcciondomicilio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->ocupacion->Visible) { // ocupacion ?>
	<div id="r_ocupacion" class="form-group">
		<label id="elh_participante_ocupacion" for="x_ocupacion" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->ocupacion->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->ocupacion->CellAttributes() ?>>
<span id="el_participante_ocupacion">
<input type="text" data-table="participante" data-field="x_ocupacion" name="x_ocupacion" id="x_ocupacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->ocupacion->getPlaceHolder()) ?>" value="<?php echo $participante->ocupacion->EditValue ?>"<?php echo $participante->ocupacion->EditAttributes() ?>>
</span>
<?php echo $participante->ocupacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_participante__email" for="x__email" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->_email->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->_email->CellAttributes() ?>>
<span id="el_participante__email">
<input type="text" data-table="participante" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->_email->getPlaceHolder()) ?>" value="<?php echo $participante->_email->EditValue ?>"<?php echo $participante->_email->EditAttributes() ?>>
</span>
<?php echo $participante->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->cargo->Visible) { // cargo ?>
	<div id="r_cargo" class="form-group">
		<label id="elh_participante_cargo" for="x_cargo" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->cargo->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->cargo->CellAttributes() ?>>
<span id="el_participante_cargo">
<input type="text" data-table="participante" data-field="x_cargo" name="x_cargo" id="x_cargo" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->cargo->getPlaceHolder()) ?>" value="<?php echo $participante->cargo->EditValue ?>"<?php echo $participante->cargo->EditAttributes() ?>>
</span>
<?php echo $participante->cargo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->nivelestudio->Visible) { // nivelestudio ?>
	<div id="r_nivelestudio" class="form-group">
		<label id="elh_participante_nivelestudio" for="x_nivelestudio" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->nivelestudio->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->nivelestudio->CellAttributes() ?>>
<span id="el_participante_nivelestudio">
<input type="text" data-table="participante" data-field="x_nivelestudio" name="x_nivelestudio" id="x_nivelestudio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->nivelestudio->getPlaceHolder()) ?>" value="<?php echo $participante->nivelestudio->EditValue ?>"<?php echo $participante->nivelestudio->EditAttributes() ?>>
</span>
<?php echo $participante->nivelestudio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->id_institucion->Visible) { // id_institucion ?>
	<div id="r_id_institucion" class="form-group">
		<label id="elh_participante_id_institucion" for="x_id_institucion" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->id_institucion->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->id_institucion->CellAttributes() ?>>
<span id="el_participante_id_institucion">
<select data-table="participante" data-field="x_id_institucion" data-value-separator="<?php echo $participante->id_institucion->DisplayValueSeparatorAttribute() ?>" id="x_id_institucion" name="x_id_institucion"<?php echo $participante->id_institucion->EditAttributes() ?>>
<?php echo $participante->id_institucion->SelectOptionListHtml("x_id_institucion") ?>
</select>
</span>
<?php echo $participante->id_institucion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_participante_observaciones" for="x_observaciones" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->observaciones->FldCaption() ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->observaciones->CellAttributes() ?>>
<span id="el_participante_observaciones">
<input type="text" data-table="participante" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->observaciones->getPlaceHolder()) ?>" value="<?php echo $participante->observaciones->EditValue ?>"<?php echo $participante->observaciones->EditAttributes() ?>>
</span>
<?php echo $participante->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($participante->id_centro->Visible) { // id_centro ?>
	<div id="r_id_centro" class="form-group">
		<label id="elh_participante_id_centro" for="x_id_centro" class="<?php echo $participante_edit->LeftColumnClass ?>"><?php echo $participante->id_centro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $participante_edit->RightColumnClass ?>"><div<?php echo $participante->id_centro->CellAttributes() ?>>
<span id="el_participante_id_centro">
<select data-table="participante" data-field="x_id_centro" data-value-separator="<?php echo $participante->id_centro->DisplayValueSeparatorAttribute() ?>" id="x_id_centro" name="x_id_centro"<?php echo $participante->id_centro->EditAttributes() ?>>
<?php echo $participante->id_centro->SelectOptionListHtml("x_id_centro") ?>
</select>
</span>
<?php echo $participante->id_centro->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$participante_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $participante_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $participante_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fparticipanteedit.Init();
</script>
<?php
$participante_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$participante_edit->Page_Terminate();
?>
