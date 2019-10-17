<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "unidadeducativainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$unidadeducativa_delete = NULL; // Initialize page object first

class cunidadeducativa_delete extends cunidadeducativa {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'unidadeducativa';

	// Page object name
	var $PageObjName = 'unidadeducativa_delete';

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

		// Table object (unidadeducativa)
		if (!isset($GLOBALS["unidadeducativa"]) || get_class($GLOBALS["unidadeducativa"]) == "cunidadeducativa") {
			$GLOBALS["unidadeducativa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["unidadeducativa"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'unidadeducativa', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("unidadeducativalist.php"));
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

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->nombre->SetVisibility();
		$this->codigo_sie->SetVisibility();
		$this->departamento->SetVisibility();
		$this->municipio->SetVisibility();
		$this->provincia->SetVisibility();
		$this->direccion->SetVisibility();
		$this->telefono->SetVisibility();
		$this->_email->SetVisibility();
		$this->id_persona->SetVisibility();
		$this->esespecial->SetVisibility();

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
		global $EW_EXPORT, $unidadeducativa;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($unidadeducativa);
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("unidadeducativalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in unidadeducativa class, unidadeducativainfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("unidadeducativalist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->codigo_sie->setDbValue($row['codigo_sie']);
		$this->departamento->setDbValue($row['departamento']);
		$this->municipio->setDbValue($row['municipio']);
		$this->provincia->setDbValue($row['provincia']);
		$this->direccion->setDbValue($row['direccion']);
		$this->telefono->setDbValue($row['telefono']);
		$this->_email->setDbValue($row['email']);
		$this->id_persona->setDbValue($row['id_persona']);
		if (array_key_exists('EV__id_persona', $rs->fields)) {
			$this->id_persona->VirtualValue = $rs->fields('EV__id_persona'); // Set up virtual field value
		} else {
			$this->id_persona->VirtualValue = ""; // Clear value
		}
		$this->id_centro->setDbValue($row['id_centro']);
		$this->esespecial->setDbValue($row['esespecial']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['nombre'] = NULL;
		$row['codigo_sie'] = NULL;
		$row['departamento'] = NULL;
		$row['municipio'] = NULL;
		$row['provincia'] = NULL;
		$row['direccion'] = NULL;
		$row['telefono'] = NULL;
		$row['email'] = NULL;
		$row['id_persona'] = NULL;
		$row['id_centro'] = NULL;
		$row['esespecial'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->nombre->DbValue = $row['nombre'];
		$this->codigo_sie->DbValue = $row['codigo_sie'];
		$this->departamento->DbValue = $row['departamento'];
		$this->municipio->DbValue = $row['municipio'];
		$this->provincia->DbValue = $row['provincia'];
		$this->direccion->DbValue = $row['direccion'];
		$this->telefono->DbValue = $row['telefono'];
		$this->_email->DbValue = $row['email'];
		$this->id_persona->DbValue = $row['id_persona'];
		$this->id_centro->DbValue = $row['id_centro'];
		$this->esespecial->DbValue = $row['esespecial'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// nombre
		// codigo_sie
		// departamento
		// municipio
		// provincia
		// direccion
		// telefono
		// email
		// id_persona
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";

		// esespecial
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// codigo_sie
		$this->codigo_sie->ViewValue = $this->codigo_sie->CurrentValue;
		$this->codigo_sie->ViewCustomAttributes = "";

		// departamento
		if (strval($this->departamento->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->departamento->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
		$sWhereWrk = "";
		$this->departamento->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->departamento, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->departamento->ViewValue = $this->departamento->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->departamento->ViewValue = $this->departamento->CurrentValue;
			}
		} else {
			$this->departamento->ViewValue = NULL;
		}
		$this->departamento->ViewCustomAttributes = "";

		// municipio
		if (strval($this->municipio->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->municipio->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
		$sWhereWrk = "";
		$this->municipio->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->municipio, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->municipio->ViewValue = $this->municipio->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->municipio->ViewValue = $this->municipio->CurrentValue;
			}
		} else {
			$this->municipio->ViewValue = NULL;
		}
		$this->municipio->ViewCustomAttributes = "";

		// provincia
		if (strval($this->provincia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->provincia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincia`";
		$sWhereWrk = "";
		$this->provincia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->provincia, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->provincia->ViewValue = $this->provincia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->provincia->ViewValue = $this->provincia->CurrentValue;
			}
		} else {
			$this->provincia->ViewValue = NULL;
		}
		$this->provincia->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewValue = trim($this->_email->ViewValue);
		$this->_email->ViewCustomAttributes = "";

		// id_persona
		if ($this->id_persona->VirtualValue <> "") {
			$this->id_persona->ViewValue = $this->id_persona->VirtualValue;
		} else {
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

		// esespecial
		if (strval($this->esespecial->CurrentValue) <> "") {
			$this->esespecial->ViewValue = $this->esespecial->OptionCaption($this->esespecial->CurrentValue);
		} else {
			$this->esespecial->ViewValue = NULL;
		}
		$this->esespecial->ViewCustomAttributes = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// codigo_sie
			$this->codigo_sie->LinkCustomAttributes = "";
			$this->codigo_sie->HrefValue = "";
			$this->codigo_sie->TooltipValue = "";

			// departamento
			$this->departamento->LinkCustomAttributes = "";
			$this->departamento->HrefValue = "";
			$this->departamento->TooltipValue = "";

			// municipio
			$this->municipio->LinkCustomAttributes = "";
			$this->municipio->HrefValue = "";
			$this->municipio->TooltipValue = "";

			// provincia
			$this->provincia->LinkCustomAttributes = "";
			$this->provincia->HrefValue = "";
			$this->provincia->TooltipValue = "";

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

			// esespecial
			$this->esespecial->LinkCustomAttributes = "";
			$this->esespecial->HrefValue = "";
			$this->esespecial->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("unidadeducativalist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($unidadeducativa_delete)) $unidadeducativa_delete = new cunidadeducativa_delete();

// Page init
$unidadeducativa_delete->Page_Init();

// Page main
$unidadeducativa_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$unidadeducativa_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = funidadeducativadelete = new ew_Form("funidadeducativadelete", "delete");

// Form_CustomValidate event
funidadeducativadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
funidadeducativadelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
funidadeducativadelete.Lists["x_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
funidadeducativadelete.Lists["x_departamento"].Data = "<?php echo $unidadeducativa_delete->departamento->LookupFilterQuery(FALSE, "delete") ?>";
funidadeducativadelete.Lists["x_municipio"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"municipio"};
funidadeducativadelete.Lists["x_municipio"].Data = "<?php echo $unidadeducativa_delete->municipio->LookupFilterQuery(FALSE, "delete") ?>";
funidadeducativadelete.Lists["x_provincia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincia"};
funidadeducativadelete.Lists["x_provincia"].Data = "<?php echo $unidadeducativa_delete->provincia->LookupFilterQuery(FALSE, "delete") ?>";
funidadeducativadelete.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
funidadeducativadelete.Lists["x_id_persona"].Data = "<?php echo $unidadeducativa_delete->id_persona->LookupFilterQuery(FALSE, "delete") ?>";
funidadeducativadelete.Lists["x_esespecial"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
funidadeducativadelete.Lists["x_esespecial"].Options = <?php echo json_encode($unidadeducativa_delete->esespecial->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $unidadeducativa_delete->ShowPageHeader(); ?>
<?php
$unidadeducativa_delete->ShowMessage();
?>
<form name="funidadeducativadelete" id="funidadeducativadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($unidadeducativa_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $unidadeducativa_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="unidadeducativa">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($unidadeducativa_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($unidadeducativa->nombre->Visible) { // nombre ?>
		<th class="<?php echo $unidadeducativa->nombre->HeaderCellClass() ?>"><span id="elh_unidadeducativa_nombre" class="unidadeducativa_nombre"><?php echo $unidadeducativa->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->codigo_sie->Visible) { // codigo_sie ?>
		<th class="<?php echo $unidadeducativa->codigo_sie->HeaderCellClass() ?>"><span id="elh_unidadeducativa_codigo_sie" class="unidadeducativa_codigo_sie"><?php echo $unidadeducativa->codigo_sie->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->departamento->Visible) { // departamento ?>
		<th class="<?php echo $unidadeducativa->departamento->HeaderCellClass() ?>"><span id="elh_unidadeducativa_departamento" class="unidadeducativa_departamento"><?php echo $unidadeducativa->departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->municipio->Visible) { // municipio ?>
		<th class="<?php echo $unidadeducativa->municipio->HeaderCellClass() ?>"><span id="elh_unidadeducativa_municipio" class="unidadeducativa_municipio"><?php echo $unidadeducativa->municipio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->provincia->Visible) { // provincia ?>
		<th class="<?php echo $unidadeducativa->provincia->HeaderCellClass() ?>"><span id="elh_unidadeducativa_provincia" class="unidadeducativa_provincia"><?php echo $unidadeducativa->provincia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->direccion->Visible) { // direccion ?>
		<th class="<?php echo $unidadeducativa->direccion->HeaderCellClass() ?>"><span id="elh_unidadeducativa_direccion" class="unidadeducativa_direccion"><?php echo $unidadeducativa->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->telefono->Visible) { // telefono ?>
		<th class="<?php echo $unidadeducativa->telefono->HeaderCellClass() ?>"><span id="elh_unidadeducativa_telefono" class="unidadeducativa_telefono"><?php echo $unidadeducativa->telefono->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->_email->Visible) { // email ?>
		<th class="<?php echo $unidadeducativa->_email->HeaderCellClass() ?>"><span id="elh_unidadeducativa__email" class="unidadeducativa__email"><?php echo $unidadeducativa->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->id_persona->Visible) { // id_persona ?>
		<th class="<?php echo $unidadeducativa->id_persona->HeaderCellClass() ?>"><span id="elh_unidadeducativa_id_persona" class="unidadeducativa_id_persona"><?php echo $unidadeducativa->id_persona->FldCaption() ?></span></th>
<?php } ?>
<?php if ($unidadeducativa->esespecial->Visible) { // esespecial ?>
		<th class="<?php echo $unidadeducativa->esespecial->HeaderCellClass() ?>"><span id="elh_unidadeducativa_esespecial" class="unidadeducativa_esespecial"><?php echo $unidadeducativa->esespecial->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$unidadeducativa_delete->RecCnt = 0;
$i = 0;
while (!$unidadeducativa_delete->Recordset->EOF) {
	$unidadeducativa_delete->RecCnt++;
	$unidadeducativa_delete->RowCnt++;

	// Set row properties
	$unidadeducativa->ResetAttrs();
	$unidadeducativa->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$unidadeducativa_delete->LoadRowValues($unidadeducativa_delete->Recordset);

	// Render row
	$unidadeducativa_delete->RenderRow();
?>
	<tr<?php echo $unidadeducativa->RowAttributes() ?>>
<?php if ($unidadeducativa->nombre->Visible) { // nombre ?>
		<td<?php echo $unidadeducativa->nombre->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_nombre" class="unidadeducativa_nombre">
<span<?php echo $unidadeducativa->nombre->ViewAttributes() ?>>
<?php echo $unidadeducativa->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->codigo_sie->Visible) { // codigo_sie ?>
		<td<?php echo $unidadeducativa->codigo_sie->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_codigo_sie" class="unidadeducativa_codigo_sie">
<span<?php echo $unidadeducativa->codigo_sie->ViewAttributes() ?>>
<?php echo $unidadeducativa->codigo_sie->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->departamento->Visible) { // departamento ?>
		<td<?php echo $unidadeducativa->departamento->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_departamento" class="unidadeducativa_departamento">
<span<?php echo $unidadeducativa->departamento->ViewAttributes() ?>>
<?php echo $unidadeducativa->departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->municipio->Visible) { // municipio ?>
		<td<?php echo $unidadeducativa->municipio->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_municipio" class="unidadeducativa_municipio">
<span<?php echo $unidadeducativa->municipio->ViewAttributes() ?>>
<?php echo $unidadeducativa->municipio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->provincia->Visible) { // provincia ?>
		<td<?php echo $unidadeducativa->provincia->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_provincia" class="unidadeducativa_provincia">
<span<?php echo $unidadeducativa->provincia->ViewAttributes() ?>>
<?php echo $unidadeducativa->provincia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->direccion->Visible) { // direccion ?>
		<td<?php echo $unidadeducativa->direccion->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_direccion" class="unidadeducativa_direccion">
<span<?php echo $unidadeducativa->direccion->ViewAttributes() ?>>
<?php echo $unidadeducativa->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->telefono->Visible) { // telefono ?>
		<td<?php echo $unidadeducativa->telefono->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_telefono" class="unidadeducativa_telefono">
<span<?php echo $unidadeducativa->telefono->ViewAttributes() ?>>
<?php echo $unidadeducativa->telefono->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->_email->Visible) { // email ?>
		<td<?php echo $unidadeducativa->_email->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa__email" class="unidadeducativa__email">
<span<?php echo $unidadeducativa->_email->ViewAttributes() ?>>
<?php echo $unidadeducativa->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->id_persona->Visible) { // id_persona ?>
		<td<?php echo $unidadeducativa->id_persona->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_id_persona" class="unidadeducativa_id_persona">
<span<?php echo $unidadeducativa->id_persona->ViewAttributes() ?>>
<?php echo $unidadeducativa->id_persona->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($unidadeducativa->esespecial->Visible) { // esespecial ?>
		<td<?php echo $unidadeducativa->esespecial->CellAttributes() ?>>
<span id="el<?php echo $unidadeducativa_delete->RowCnt ?>_unidadeducativa_esespecial" class="unidadeducativa_esespecial">
<span<?php echo $unidadeducativa->esespecial->ViewAttributes() ?>>
<?php echo $unidadeducativa->esespecial->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$unidadeducativa_delete->Recordset->MoveNext();
}
$unidadeducativa_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $unidadeducativa_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
funidadeducativadelete.Init();
</script>
<?php
$unidadeducativa_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$unidadeducativa_delete->Page_Terminate();
?>
