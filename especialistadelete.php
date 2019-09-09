<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "especialistainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$especialista_delete = NULL; // Initialize page object first

class cespecialista_delete extends cespecialista {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'especialista';

	// Page object name
	var $PageObjName = 'especialista_delete';

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

		// Table object (especialista)
		if (!isset($GLOBALS["especialista"]) || get_class($GLOBALS["especialista"]) == "cespecialista") {
			$GLOBALS["especialista"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["especialista"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'especialista', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("especialistalist.php"));
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
		$this->nombres->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->matricula->SetVisibility();
		$this->especialidad->SetVisibility();
		$this->id_departamento->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->celular->SetVisibility();
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
		global $EW_EXPORT, $especialista;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($especialista);
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
			$this->Page_Terminate("especialistalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in especialista class, especialistainfo.php

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
				$this->Page_Terminate("especialistalist.php"); // Return to list
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
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
		$this->nombres->setDbValue($row['nombres']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->matricula->setDbValue($row['matricula']);
		$this->especialidad->setDbValue($row['especialidad']);
		$this->id_departamento->setDbValue($row['id_departamento']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->celular->setDbValue($row['celular']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->nombreespecialidad->setDbValue($row['nombreespecialidad']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['nombres'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['matricula'] = NULL;
		$row['especialidad'] = NULL;
		$row['id_departamento'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['celular'] = NULL;
		$row['observaciones'] = NULL;
		$row['nombreespecialidad'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->nombres->DbValue = $row['nombres'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->matricula->DbValue = $row['matricula'];
		$this->especialidad->DbValue = $row['especialidad'];
		$this->id_departamento->DbValue = $row['id_departamento'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->celular->DbValue = $row['celular'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->nombreespecialidad->DbValue = $row['nombreespecialidad'];
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

		$this->id->CellCssStyle = "white-space: nowrap;";

		// nombres
		// apellidopaterno
		// apellidomaterno
		// matricula
		// especialidad
		// id_departamento
		// ci
		// fechanacimiento
		// sexo
		// celular
		// observaciones
		// nombreespecialidad

		$this->nombreespecialidad->CellCssStyle = "white-space: nowrap;";

		// id_centro
		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// nombres
		$this->nombres->ViewValue = $this->nombres->CurrentValue;
		$this->nombres->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// matricula
		$this->matricula->ViewValue = $this->matricula->CurrentValue;
		$this->matricula->ViewCustomAttributes = "";

		// especialidad
		if (strval($this->especialidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->especialidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoespecialidad`";
		$sWhereWrk = "";
		$this->especialidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->especialidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->especialidad->ViewValue = $this->especialidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->especialidad->ViewValue = $this->especialidad->CurrentValue;
			}
		} else {
			$this->especialidad->ViewValue = NULL;
		}
		$this->especialidad->ViewCustomAttributes = "";

		// id_departamento
		if (strval($this->id_departamento->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_departamento->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
		$sWhereWrk = "";
		$this->id_departamento->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_departamento, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_departamento->ViewValue = $this->id_departamento->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_departamento->ViewValue = $this->id_departamento->CurrentValue;
			}
		} else {
			$this->id_departamento->ViewValue = NULL;
		}
		$this->id_departamento->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fechanacimiento
		$this->fechanacimiento->ViewValue = $this->fechanacimiento->CurrentValue;
		$this->fechanacimiento->ViewValue = ew_FormatDateTime($this->fechanacimiento->ViewValue, 0);
		$this->fechanacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

			// nombres
			$this->nombres->LinkCustomAttributes = "";
			$this->nombres->HrefValue = "";
			$this->nombres->TooltipValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";
			$this->apellidopaterno->TooltipValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";
			$this->apellidomaterno->TooltipValue = "";

			// matricula
			$this->matricula->LinkCustomAttributes = "";
			$this->matricula->HrefValue = "";
			$this->matricula->TooltipValue = "";

			// especialidad
			$this->especialidad->LinkCustomAttributes = "";
			$this->especialidad->HrefValue = "";
			$this->especialidad->TooltipValue = "";

			// id_departamento
			$this->id_departamento->LinkCustomAttributes = "";
			$this->id_departamento->HrefValue = "";
			$this->id_departamento->TooltipValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// fechanacimiento
			$this->fechanacimiento->LinkCustomAttributes = "";
			$this->fechanacimiento->HrefValue = "";
			$this->fechanacimiento->TooltipValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";
			$this->sexo->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("especialistalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($especialista_delete)) $especialista_delete = new cespecialista_delete();

// Page init
$especialista_delete->Page_Init();

// Page main
$especialista_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$especialista_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fespecialistadelete = new ew_Form("fespecialistadelete", "delete");

// Form_CustomValidate event
fespecialistadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fespecialistadelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fespecialistadelete.Lists["x_especialidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoespecialidad"};
fespecialistadelete.Lists["x_especialidad"].Data = "<?php echo $especialista_delete->especialidad->LookupFilterQuery(FALSE, "delete") ?>";
fespecialistadelete.Lists["x_id_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fespecialistadelete.Lists["x_id_departamento"].Data = "<?php echo $especialista_delete->id_departamento->LookupFilterQuery(FALSE, "delete") ?>";
fespecialistadelete.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fespecialistadelete.Lists["x_sexo"].Options = <?php echo json_encode($especialista_delete->sexo->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $especialista_delete->ShowPageHeader(); ?>
<?php
$especialista_delete->ShowMessage();
?>
<form name="fespecialistadelete" id="fespecialistadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($especialista_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $especialista_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="especialista">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($especialista_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($especialista->nombres->Visible) { // nombres ?>
		<th class="<?php echo $especialista->nombres->HeaderCellClass() ?>"><span id="elh_especialista_nombres" class="especialista_nombres"><?php echo $especialista->nombres->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->apellidopaterno->Visible) { // apellidopaterno ?>
		<th class="<?php echo $especialista->apellidopaterno->HeaderCellClass() ?>"><span id="elh_especialista_apellidopaterno" class="especialista_apellidopaterno"><?php echo $especialista->apellidopaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->apellidomaterno->Visible) { // apellidomaterno ?>
		<th class="<?php echo $especialista->apellidomaterno->HeaderCellClass() ?>"><span id="elh_especialista_apellidomaterno" class="especialista_apellidomaterno"><?php echo $especialista->apellidomaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->matricula->Visible) { // matricula ?>
		<th class="<?php echo $especialista->matricula->HeaderCellClass() ?>"><span id="elh_especialista_matricula" class="especialista_matricula"><?php echo $especialista->matricula->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->especialidad->Visible) { // especialidad ?>
		<th class="<?php echo $especialista->especialidad->HeaderCellClass() ?>"><span id="elh_especialista_especialidad" class="especialista_especialidad"><?php echo $especialista->especialidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->id_departamento->Visible) { // id_departamento ?>
		<th class="<?php echo $especialista->id_departamento->HeaderCellClass() ?>"><span id="elh_especialista_id_departamento" class="especialista_id_departamento"><?php echo $especialista->id_departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->ci->Visible) { // ci ?>
		<th class="<?php echo $especialista->ci->HeaderCellClass() ?>"><span id="elh_especialista_ci" class="especialista_ci"><?php echo $especialista->ci->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->fechanacimiento->Visible) { // fechanacimiento ?>
		<th class="<?php echo $especialista->fechanacimiento->HeaderCellClass() ?>"><span id="elh_especialista_fechanacimiento" class="especialista_fechanacimiento"><?php echo $especialista->fechanacimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->sexo->Visible) { // sexo ?>
		<th class="<?php echo $especialista->sexo->HeaderCellClass() ?>"><span id="elh_especialista_sexo" class="especialista_sexo"><?php echo $especialista->sexo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->celular->Visible) { // celular ?>
		<th class="<?php echo $especialista->celular->HeaderCellClass() ?>"><span id="elh_especialista_celular" class="especialista_celular"><?php echo $especialista->celular->FldCaption() ?></span></th>
<?php } ?>
<?php if ($especialista->observaciones->Visible) { // observaciones ?>
		<th class="<?php echo $especialista->observaciones->HeaderCellClass() ?>"><span id="elh_especialista_observaciones" class="especialista_observaciones"><?php echo $especialista->observaciones->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$especialista_delete->RecCnt = 0;
$i = 0;
while (!$especialista_delete->Recordset->EOF) {
	$especialista_delete->RecCnt++;
	$especialista_delete->RowCnt++;

	// Set row properties
	$especialista->ResetAttrs();
	$especialista->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$especialista_delete->LoadRowValues($especialista_delete->Recordset);

	// Render row
	$especialista_delete->RenderRow();
?>
	<tr<?php echo $especialista->RowAttributes() ?>>
<?php if ($especialista->nombres->Visible) { // nombres ?>
		<td<?php echo $especialista->nombres->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_nombres" class="especialista_nombres">
<span<?php echo $especialista->nombres->ViewAttributes() ?>>
<?php echo $especialista->nombres->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->apellidopaterno->Visible) { // apellidopaterno ?>
		<td<?php echo $especialista->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_apellidopaterno" class="especialista_apellidopaterno">
<span<?php echo $especialista->apellidopaterno->ViewAttributes() ?>>
<?php echo $especialista->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->apellidomaterno->Visible) { // apellidomaterno ?>
		<td<?php echo $especialista->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_apellidomaterno" class="especialista_apellidomaterno">
<span<?php echo $especialista->apellidomaterno->ViewAttributes() ?>>
<?php echo $especialista->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->matricula->Visible) { // matricula ?>
		<td<?php echo $especialista->matricula->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_matricula" class="especialista_matricula">
<span<?php echo $especialista->matricula->ViewAttributes() ?>>
<?php echo $especialista->matricula->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->especialidad->Visible) { // especialidad ?>
		<td<?php echo $especialista->especialidad->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_especialidad" class="especialista_especialidad">
<span<?php echo $especialista->especialidad->ViewAttributes() ?>>
<?php echo $especialista->especialidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->id_departamento->Visible) { // id_departamento ?>
		<td<?php echo $especialista->id_departamento->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_id_departamento" class="especialista_id_departamento">
<span<?php echo $especialista->id_departamento->ViewAttributes() ?>>
<?php echo $especialista->id_departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->ci->Visible) { // ci ?>
		<td<?php echo $especialista->ci->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_ci" class="especialista_ci">
<span<?php echo $especialista->ci->ViewAttributes() ?>>
<?php echo $especialista->ci->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->fechanacimiento->Visible) { // fechanacimiento ?>
		<td<?php echo $especialista->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_fechanacimiento" class="especialista_fechanacimiento">
<span<?php echo $especialista->fechanacimiento->ViewAttributes() ?>>
<?php echo $especialista->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->sexo->Visible) { // sexo ?>
		<td<?php echo $especialista->sexo->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_sexo" class="especialista_sexo">
<span<?php echo $especialista->sexo->ViewAttributes() ?>>
<?php echo $especialista->sexo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->celular->Visible) { // celular ?>
		<td<?php echo $especialista->celular->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_celular" class="especialista_celular">
<span<?php echo $especialista->celular->ViewAttributes() ?>>
<?php echo $especialista->celular->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($especialista->observaciones->Visible) { // observaciones ?>
		<td<?php echo $especialista->observaciones->CellAttributes() ?>>
<span id="el<?php echo $especialista_delete->RowCnt ?>_especialista_observaciones" class="especialista_observaciones">
<span<?php echo $especialista->observaciones->ViewAttributes() ?>>
<?php echo $especialista->observaciones->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$especialista_delete->Recordset->MoveNext();
}
$especialista_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $especialista_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fespecialistadelete.Init();
</script>
<?php
$especialista_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$especialista_delete->Page_Terminate();
?>
