<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "institucionesdesaludinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$institucionesdesalud_delete = NULL; // Initialize page object first

class cinstitucionesdesalud_delete extends cinstitucionesdesalud {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'institucionesdesalud';

	// Page object name
	var $PageObjName = 'institucionesdesalud_delete';

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

		// Table object (institucionesdesalud)
		if (!isset($GLOBALS["institucionesdesalud"]) || get_class($GLOBALS["institucionesdesalud"]) == "cinstitucionesdesalud") {
			$GLOBALS["institucionesdesalud"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["institucionesdesalud"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'institucionesdesalud', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("institucionesdesaludlist.php"));
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
		$this->nombre->SetVisibility();
		$this->departamento->SetVisibility();
		$this->municipio->SetVisibility();
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
		global $EW_EXPORT, $institucionesdesalud;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($institucionesdesalud);
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
			$this->Page_Terminate("institucionesdesaludlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in institucionesdesalud class, institucionesdesaludinfo.php

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
				$this->Page_Terminate("institucionesdesaludlist.php"); // Return to list
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
		$this->departamento->setDbValue($row['departamento']);
		$this->provincia->setDbValue($row['provincia']);
		$this->municipio->setDbValue($row['municipio']);
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
		$row['provincia'] = NULL;
		$row['municipio'] = NULL;
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
		$this->provincia->DbValue = $row['provincia'];
		$this->municipio->DbValue = $row['municipio'];
		$this->direccion->DbValue = $row['direccion'];
		$this->telefono->DbValue = $row['telefono'];
		$this->_email->DbValue = $row['email'];
		$this->id_persona->DbValue = $row['id_persona'];
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
		// provincia

		$this->provincia->CellCssStyle = "white-space: nowrap;";

		// municipio
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
		$this->municipio->ViewValue = $this->municipio->CurrentValue;
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

			// municipio
			$this->municipio->LinkCustomAttributes = "";
			$this->municipio->HrefValue = "";
			$this->municipio->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("institucionesdesaludlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($institucionesdesalud_delete)) $institucionesdesalud_delete = new cinstitucionesdesalud_delete();

// Page init
$institucionesdesalud_delete->Page_Init();

// Page main
$institucionesdesalud_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institucionesdesalud_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = finstitucionesdesaluddelete = new ew_Form("finstitucionesdesaluddelete", "delete");

// Form_CustomValidate event
finstitucionesdesaluddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitucionesdesaluddelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstitucionesdesaluddelete.Lists["x_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
finstitucionesdesaluddelete.Lists["x_departamento"].Data = "<?php echo $institucionesdesalud_delete->departamento->LookupFilterQuery(FALSE, "delete") ?>";
finstitucionesdesaluddelete.Lists["x_municipio"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"municipio"};
finstitucionesdesaluddelete.Lists["x_municipio"].Data = "<?php echo $institucionesdesalud_delete->municipio->LookupFilterQuery(FALSE, "delete") ?>";
finstitucionesdesaluddelete.AutoSuggests["x_municipio"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $institucionesdesalud_delete->municipio->LookupFilterQuery(TRUE, "delete"))) ?>;
finstitucionesdesaluddelete.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
finstitucionesdesaluddelete.Lists["x_id_persona"].Data = "<?php echo $institucionesdesalud_delete->id_persona->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $institucionesdesalud_delete->ShowPageHeader(); ?>
<?php
$institucionesdesalud_delete->ShowMessage();
?>
<form name="finstitucionesdesaluddelete" id="finstitucionesdesaluddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($institucionesdesalud_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institucionesdesalud_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institucionesdesalud">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($institucionesdesalud_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($institucionesdesalud->id->Visible) { // id ?>
		<th class="<?php echo $institucionesdesalud->id->HeaderCellClass() ?>"><span id="elh_institucionesdesalud_id" class="institucionesdesalud_id"><?php echo $institucionesdesalud->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institucionesdesalud->nombre->Visible) { // nombre ?>
		<th class="<?php echo $institucionesdesalud->nombre->HeaderCellClass() ?>"><span id="elh_institucionesdesalud_nombre" class="institucionesdesalud_nombre"><?php echo $institucionesdesalud->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institucionesdesalud->departamento->Visible) { // departamento ?>
		<th class="<?php echo $institucionesdesalud->departamento->HeaderCellClass() ?>"><span id="elh_institucionesdesalud_departamento" class="institucionesdesalud_departamento"><?php echo $institucionesdesalud->departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institucionesdesalud->municipio->Visible) { // municipio ?>
		<th class="<?php echo $institucionesdesalud->municipio->HeaderCellClass() ?>"><span id="elh_institucionesdesalud_municipio" class="institucionesdesalud_municipio"><?php echo $institucionesdesalud->municipio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institucionesdesalud->direccion->Visible) { // direccion ?>
		<th class="<?php echo $institucionesdesalud->direccion->HeaderCellClass() ?>"><span id="elh_institucionesdesalud_direccion" class="institucionesdesalud_direccion"><?php echo $institucionesdesalud->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institucionesdesalud->telefono->Visible) { // telefono ?>
		<th class="<?php echo $institucionesdesalud->telefono->HeaderCellClass() ?>"><span id="elh_institucionesdesalud_telefono" class="institucionesdesalud_telefono"><?php echo $institucionesdesalud->telefono->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institucionesdesalud->_email->Visible) { // email ?>
		<th class="<?php echo $institucionesdesalud->_email->HeaderCellClass() ?>"><span id="elh_institucionesdesalud__email" class="institucionesdesalud__email"><?php echo $institucionesdesalud->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institucionesdesalud->id_persona->Visible) { // id_persona ?>
		<th class="<?php echo $institucionesdesalud->id_persona->HeaderCellClass() ?>"><span id="elh_institucionesdesalud_id_persona" class="institucionesdesalud_id_persona"><?php echo $institucionesdesalud->id_persona->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$institucionesdesalud_delete->RecCnt = 0;
$i = 0;
while (!$institucionesdesalud_delete->Recordset->EOF) {
	$institucionesdesalud_delete->RecCnt++;
	$institucionesdesalud_delete->RowCnt++;

	// Set row properties
	$institucionesdesalud->ResetAttrs();
	$institucionesdesalud->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$institucionesdesalud_delete->LoadRowValues($institucionesdesalud_delete->Recordset);

	// Render row
	$institucionesdesalud_delete->RenderRow();
?>
	<tr<?php echo $institucionesdesalud->RowAttributes() ?>>
<?php if ($institucionesdesalud->id->Visible) { // id ?>
		<td<?php echo $institucionesdesalud->id->CellAttributes() ?>>
<span id="el<?php echo $institucionesdesalud_delete->RowCnt ?>_institucionesdesalud_id" class="institucionesdesalud_id">
<span<?php echo $institucionesdesalud->id->ViewAttributes() ?>>
<?php echo $institucionesdesalud->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institucionesdesalud->nombre->Visible) { // nombre ?>
		<td<?php echo $institucionesdesalud->nombre->CellAttributes() ?>>
<span id="el<?php echo $institucionesdesalud_delete->RowCnt ?>_institucionesdesalud_nombre" class="institucionesdesalud_nombre">
<span<?php echo $institucionesdesalud->nombre->ViewAttributes() ?>>
<?php echo $institucionesdesalud->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institucionesdesalud->departamento->Visible) { // departamento ?>
		<td<?php echo $institucionesdesalud->departamento->CellAttributes() ?>>
<span id="el<?php echo $institucionesdesalud_delete->RowCnt ?>_institucionesdesalud_departamento" class="institucionesdesalud_departamento">
<span<?php echo $institucionesdesalud->departamento->ViewAttributes() ?>>
<?php echo $institucionesdesalud->departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institucionesdesalud->municipio->Visible) { // municipio ?>
		<td<?php echo $institucionesdesalud->municipio->CellAttributes() ?>>
<span id="el<?php echo $institucionesdesalud_delete->RowCnt ?>_institucionesdesalud_municipio" class="institucionesdesalud_municipio">
<span<?php echo $institucionesdesalud->municipio->ViewAttributes() ?>>
<?php echo $institucionesdesalud->municipio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institucionesdesalud->direccion->Visible) { // direccion ?>
		<td<?php echo $institucionesdesalud->direccion->CellAttributes() ?>>
<span id="el<?php echo $institucionesdesalud_delete->RowCnt ?>_institucionesdesalud_direccion" class="institucionesdesalud_direccion">
<span<?php echo $institucionesdesalud->direccion->ViewAttributes() ?>>
<?php echo $institucionesdesalud->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institucionesdesalud->telefono->Visible) { // telefono ?>
		<td<?php echo $institucionesdesalud->telefono->CellAttributes() ?>>
<span id="el<?php echo $institucionesdesalud_delete->RowCnt ?>_institucionesdesalud_telefono" class="institucionesdesalud_telefono">
<span<?php echo $institucionesdesalud->telefono->ViewAttributes() ?>>
<?php echo $institucionesdesalud->telefono->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institucionesdesalud->_email->Visible) { // email ?>
		<td<?php echo $institucionesdesalud->_email->CellAttributes() ?>>
<span id="el<?php echo $institucionesdesalud_delete->RowCnt ?>_institucionesdesalud__email" class="institucionesdesalud__email">
<span<?php echo $institucionesdesalud->_email->ViewAttributes() ?>>
<?php echo $institucionesdesalud->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institucionesdesalud->id_persona->Visible) { // id_persona ?>
		<td<?php echo $institucionesdesalud->id_persona->CellAttributes() ?>>
<span id="el<?php echo $institucionesdesalud_delete->RowCnt ?>_institucionesdesalud_id_persona" class="institucionesdesalud_id_persona">
<span<?php echo $institucionesdesalud->id_persona->ViewAttributes() ?>>
<?php echo $institucionesdesalud->id_persona->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$institucionesdesalud_delete->Recordset->MoveNext();
}
$institucionesdesalud_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $institucionesdesalud_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
finstitucionesdesaluddelete.Init();
</script>
<?php
$institucionesdesalud_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$institucionesdesalud_delete->Page_Terminate();
?>
