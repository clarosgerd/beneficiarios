<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "apoderadoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$apoderado_delete = NULL; // Initialize page object first

class capoderado_delete extends capoderado {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'apoderado';

	// Page object name
	var $PageObjName = 'apoderado_delete';

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

		// Table object (apoderado)
		if (!isset($GLOBALS["apoderado"]) || get_class($GLOBALS["apoderado"]) == "capoderado") {
			$GLOBALS["apoderado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["apoderado"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'apoderado', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("apoderadolist.php"));
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
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->parentesco->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->direccion->SetVisibility();
		$this->celular->SetVisibility();
		$this->ocupacion->SetVisibility();
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
		global $EW_EXPORT, $apoderado;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($apoderado);
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
			$this->Page_Terminate("apoderadolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in apoderado class, apoderadoinfo.php

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
				$this->Page_Terminate("apoderadolist.php"); // Return to list
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
		$this->parentesco->setDbValue($row['parentesco']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombres->setDbValue($row['nombres']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->direccion->setDbValue($row['direccion']);
		$this->celular->setDbValue($row['celular']);
		$this->ocupacion->setDbValue($row['ocupacion']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['parentesco'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombres'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['direccion'] = NULL;
		$row['celular'] = NULL;
		$row['ocupacion'] = NULL;
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
		$this->parentesco->DbValue = $row['parentesco'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombres->DbValue = $row['nombres'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->direccion->DbValue = $row['direccion'];
		$this->celular->DbValue = $row['celular'];
		$this->ocupacion->DbValue = $row['ocupacion'];
		$this->observaciones->DbValue = $row['observaciones'];
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
		// parentesco
		// apellidopaterno
		// apellidomaterno
		// nombres
		// ci
		// fechanacimiento
		// sexo
		// direccion
		// celular
		// ocupacion
		// observaciones
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// parentesco
		$this->parentesco->ViewValue = $this->parentesco->CurrentValue;
		$this->parentesco->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombres
		$this->nombres->ViewValue = $this->nombres->CurrentValue;
		$this->nombres->ViewCustomAttributes = "";

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

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// ocupacion
		$this->ocupacion->ViewValue = $this->ocupacion->CurrentValue;
		$this->ocupacion->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// parentesco
			$this->parentesco->LinkCustomAttributes = "";
			$this->parentesco->HrefValue = "";
			$this->parentesco->TooltipValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";
			$this->apellidopaterno->TooltipValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";
			$this->apellidomaterno->TooltipValue = "";

			// nombres
			$this->nombres->LinkCustomAttributes = "";
			$this->nombres->HrefValue = "";
			$this->nombres->TooltipValue = "";

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

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// ocupacion
			$this->ocupacion->LinkCustomAttributes = "";
			$this->ocupacion->HrefValue = "";
			$this->ocupacion->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("apoderadolist.php"), "", $this->TableVar, TRUE);
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
if (!isset($apoderado_delete)) $apoderado_delete = new capoderado_delete();

// Page init
$apoderado_delete->Page_Init();

// Page main
$apoderado_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$apoderado_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fapoderadodelete = new ew_Form("fapoderadodelete", "delete");

// Form_CustomValidate event
fapoderadodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fapoderadodelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fapoderadodelete.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fapoderadodelete.Lists["x_sexo"].Options = <?php echo json_encode($apoderado_delete->sexo->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $apoderado_delete->ShowPageHeader(); ?>
<?php
$apoderado_delete->ShowMessage();
?>
<form name="fapoderadodelete" id="fapoderadodelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($apoderado_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $apoderado_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="apoderado">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($apoderado_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($apoderado->id->Visible) { // id ?>
		<th class="<?php echo $apoderado->id->HeaderCellClass() ?>"><span id="elh_apoderado_id" class="apoderado_id"><?php echo $apoderado->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->parentesco->Visible) { // parentesco ?>
		<th class="<?php echo $apoderado->parentesco->HeaderCellClass() ?>"><span id="elh_apoderado_parentesco" class="apoderado_parentesco"><?php echo $apoderado->parentesco->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->apellidopaterno->Visible) { // apellidopaterno ?>
		<th class="<?php echo $apoderado->apellidopaterno->HeaderCellClass() ?>"><span id="elh_apoderado_apellidopaterno" class="apoderado_apellidopaterno"><?php echo $apoderado->apellidopaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->apellidomaterno->Visible) { // apellidomaterno ?>
		<th class="<?php echo $apoderado->apellidomaterno->HeaderCellClass() ?>"><span id="elh_apoderado_apellidomaterno" class="apoderado_apellidomaterno"><?php echo $apoderado->apellidomaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->nombres->Visible) { // nombres ?>
		<th class="<?php echo $apoderado->nombres->HeaderCellClass() ?>"><span id="elh_apoderado_nombres" class="apoderado_nombres"><?php echo $apoderado->nombres->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->ci->Visible) { // ci ?>
		<th class="<?php echo $apoderado->ci->HeaderCellClass() ?>"><span id="elh_apoderado_ci" class="apoderado_ci"><?php echo $apoderado->ci->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->fechanacimiento->Visible) { // fechanacimiento ?>
		<th class="<?php echo $apoderado->fechanacimiento->HeaderCellClass() ?>"><span id="elh_apoderado_fechanacimiento" class="apoderado_fechanacimiento"><?php echo $apoderado->fechanacimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->sexo->Visible) { // sexo ?>
		<th class="<?php echo $apoderado->sexo->HeaderCellClass() ?>"><span id="elh_apoderado_sexo" class="apoderado_sexo"><?php echo $apoderado->sexo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->direccion->Visible) { // direccion ?>
		<th class="<?php echo $apoderado->direccion->HeaderCellClass() ?>"><span id="elh_apoderado_direccion" class="apoderado_direccion"><?php echo $apoderado->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->celular->Visible) { // celular ?>
		<th class="<?php echo $apoderado->celular->HeaderCellClass() ?>"><span id="elh_apoderado_celular" class="apoderado_celular"><?php echo $apoderado->celular->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->ocupacion->Visible) { // ocupacion ?>
		<th class="<?php echo $apoderado->ocupacion->HeaderCellClass() ?>"><span id="elh_apoderado_ocupacion" class="apoderado_ocupacion"><?php echo $apoderado->ocupacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($apoderado->observaciones->Visible) { // observaciones ?>
		<th class="<?php echo $apoderado->observaciones->HeaderCellClass() ?>"><span id="elh_apoderado_observaciones" class="apoderado_observaciones"><?php echo $apoderado->observaciones->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$apoderado_delete->RecCnt = 0;
$i = 0;
while (!$apoderado_delete->Recordset->EOF) {
	$apoderado_delete->RecCnt++;
	$apoderado_delete->RowCnt++;

	// Set row properties
	$apoderado->ResetAttrs();
	$apoderado->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$apoderado_delete->LoadRowValues($apoderado_delete->Recordset);

	// Render row
	$apoderado_delete->RenderRow();
?>
	<tr<?php echo $apoderado->RowAttributes() ?>>
<?php if ($apoderado->id->Visible) { // id ?>
		<td<?php echo $apoderado->id->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_id" class="apoderado_id">
<span<?php echo $apoderado->id->ViewAttributes() ?>>
<?php echo $apoderado->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->parentesco->Visible) { // parentesco ?>
		<td<?php echo $apoderado->parentesco->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_parentesco" class="apoderado_parentesco">
<span<?php echo $apoderado->parentesco->ViewAttributes() ?>>
<?php echo $apoderado->parentesco->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->apellidopaterno->Visible) { // apellidopaterno ?>
		<td<?php echo $apoderado->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_apellidopaterno" class="apoderado_apellidopaterno">
<span<?php echo $apoderado->apellidopaterno->ViewAttributes() ?>>
<?php echo $apoderado->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->apellidomaterno->Visible) { // apellidomaterno ?>
		<td<?php echo $apoderado->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_apellidomaterno" class="apoderado_apellidomaterno">
<span<?php echo $apoderado->apellidomaterno->ViewAttributes() ?>>
<?php echo $apoderado->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->nombres->Visible) { // nombres ?>
		<td<?php echo $apoderado->nombres->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_nombres" class="apoderado_nombres">
<span<?php echo $apoderado->nombres->ViewAttributes() ?>>
<?php echo $apoderado->nombres->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->ci->Visible) { // ci ?>
		<td<?php echo $apoderado->ci->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_ci" class="apoderado_ci">
<span<?php echo $apoderado->ci->ViewAttributes() ?>>
<?php echo $apoderado->ci->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->fechanacimiento->Visible) { // fechanacimiento ?>
		<td<?php echo $apoderado->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_fechanacimiento" class="apoderado_fechanacimiento">
<span<?php echo $apoderado->fechanacimiento->ViewAttributes() ?>>
<?php echo $apoderado->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->sexo->Visible) { // sexo ?>
		<td<?php echo $apoderado->sexo->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_sexo" class="apoderado_sexo">
<span<?php echo $apoderado->sexo->ViewAttributes() ?>>
<?php echo $apoderado->sexo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->direccion->Visible) { // direccion ?>
		<td<?php echo $apoderado->direccion->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_direccion" class="apoderado_direccion">
<span<?php echo $apoderado->direccion->ViewAttributes() ?>>
<?php echo $apoderado->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->celular->Visible) { // celular ?>
		<td<?php echo $apoderado->celular->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_celular" class="apoderado_celular">
<span<?php echo $apoderado->celular->ViewAttributes() ?>>
<?php echo $apoderado->celular->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->ocupacion->Visible) { // ocupacion ?>
		<td<?php echo $apoderado->ocupacion->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_ocupacion" class="apoderado_ocupacion">
<span<?php echo $apoderado->ocupacion->ViewAttributes() ?>>
<?php echo $apoderado->ocupacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($apoderado->observaciones->Visible) { // observaciones ?>
		<td<?php echo $apoderado->observaciones->CellAttributes() ?>>
<span id="el<?php echo $apoderado_delete->RowCnt ?>_apoderado_observaciones" class="apoderado_observaciones">
<span<?php echo $apoderado->observaciones->ViewAttributes() ?>>
<?php echo $apoderado->observaciones->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$apoderado_delete->Recordset->MoveNext();
}
$apoderado_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $apoderado_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fapoderadodelete.Init();
</script>
<?php
$apoderado_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$apoderado_delete->Page_Terminate();
?>
