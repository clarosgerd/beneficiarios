<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "actividadinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$actividad_delete = NULL; // Initialize page object first

class cactividad_delete extends cactividad {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'actividad';

	// Page object name
	var $PageObjName = 'actividad_delete';

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

		// Table object (actividad)
		if (!isset($GLOBALS["actividad"]) || get_class($GLOBALS["actividad"]) == "cactividad") {
			$GLOBALS["actividad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["actividad"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'actividad', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("actividadlist.php"));
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
		$this->id_sector->SetVisibility();
		$this->id_tipoactividad->SetVisibility();
		$this->organizador->SetVisibility();
		$this->nombreactividad->SetVisibility();
		$this->nombrelocal->SetVisibility();
		$this->direccionlocal->SetVisibility();
		$this->fecha_inicio->SetVisibility();
		$this->fecha_fin->SetVisibility();
		$this->horasprogramadas->SetVisibility();
		$this->id_persona->SetVisibility();
		$this->contenido->SetVisibility();

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
		global $EW_EXPORT, $actividad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($actividad);
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
			$this->Page_Terminate("actividadlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in actividad class, actividadinfo.php

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
				$this->Page_Terminate("actividadlist.php"); // Return to list
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
		$this->id_sector->setDbValue($row['id_sector']);
		$this->id_tipoactividad->setDbValue($row['id_tipoactividad']);
		$this->organizador->setDbValue($row['organizador']);
		$this->nombreactividad->setDbValue($row['nombreactividad']);
		$this->nombrelocal->setDbValue($row['nombrelocal']);
		$this->direccionlocal->setDbValue($row['direccionlocal']);
		$this->fecha_inicio->setDbValue($row['fecha_inicio']);
		$this->fecha_fin->setDbValue($row['fecha_fin']);
		$this->horasprogramadas->setDbValue($row['horasprogramadas']);
		$this->id_persona->setDbValue($row['id_persona']);
		if (array_key_exists('EV__id_persona', $rs->fields)) {
			$this->id_persona->VirtualValue = $rs->fields('EV__id_persona'); // Set up virtual field value
		} else {
			$this->id_persona->VirtualValue = ""; // Clear value
		}
		$this->contenido->setDbValue($row['contenido']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_sector'] = NULL;
		$row['id_tipoactividad'] = NULL;
		$row['organizador'] = NULL;
		$row['nombreactividad'] = NULL;
		$row['nombrelocal'] = NULL;
		$row['direccionlocal'] = NULL;
		$row['fecha_inicio'] = NULL;
		$row['fecha_fin'] = NULL;
		$row['horasprogramadas'] = NULL;
		$row['id_persona'] = NULL;
		$row['contenido'] = NULL;
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
		$this->id_tipoactividad->DbValue = $row['id_tipoactividad'];
		$this->organizador->DbValue = $row['organizador'];
		$this->nombreactividad->DbValue = $row['nombreactividad'];
		$this->nombrelocal->DbValue = $row['nombrelocal'];
		$this->direccionlocal->DbValue = $row['direccionlocal'];
		$this->fecha_inicio->DbValue = $row['fecha_inicio'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->horasprogramadas->DbValue = $row['horasprogramadas'];
		$this->id_persona->DbValue = $row['id_persona'];
		$this->contenido->DbValue = $row['contenido'];
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
		// id_sector
		// id_tipoactividad
		// organizador
		// nombreactividad
		// nombrelocal
		// direccionlocal
		// fecha_inicio
		// fecha_fin
		// horasprogramadas
		// id_persona
		// contenido
		// observaciones
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
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

		// id_tipoactividad
		if (strval($this->id_tipoactividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipoactividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoactividad`";
		$sWhereWrk = "";
		$this->id_tipoactividad->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipoactividad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipoactividad->ViewValue = $this->id_tipoactividad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipoactividad->ViewValue = $this->id_tipoactividad->CurrentValue;
			}
		} else {
			$this->id_tipoactividad->ViewValue = NULL;
		}
		$this->id_tipoactividad->ViewCustomAttributes = "";

		// organizador
		if (strval($this->organizador->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->organizador->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->organizador->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->organizador, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->organizador->ViewValue = $this->organizador->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->organizador->ViewValue = $this->organizador->CurrentValue;
			}
		} else {
			$this->organizador->ViewValue = NULL;
		}
		$this->organizador->ViewCustomAttributes = "";

		// nombreactividad
		$this->nombreactividad->ViewValue = $this->nombreactividad->CurrentValue;
		$this->nombreactividad->ViewCustomAttributes = "";

		// nombrelocal
		$this->nombrelocal->ViewValue = $this->nombrelocal->CurrentValue;
		$this->nombrelocal->ViewCustomAttributes = "";

		// direccionlocal
		$this->direccionlocal->ViewValue = $this->direccionlocal->CurrentValue;
		$this->direccionlocal->ViewCustomAttributes = "";

		// fecha_inicio
		$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
		$this->fecha_inicio->ViewValue = ew_FormatDateTime($this->fecha_inicio->ViewValue, 0);
		$this->fecha_inicio->ViewCustomAttributes = "";

		// fecha_fin
		$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
		$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 0);
		$this->fecha_fin->ViewCustomAttributes = "";

		// horasprogramadas
		$this->horasprogramadas->ViewValue = $this->horasprogramadas->CurrentValue;
		$this->horasprogramadas->ViewCustomAttributes = "";

		// id_persona
		if ($this->id_persona->VirtualValue <> "") {
			$this->id_persona->ViewValue = $this->id_persona->VirtualValue;
		} else {
			$this->id_persona->ViewValue = $this->id_persona->CurrentValue;
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

		// contenido
		$this->contenido->ViewValue = $this->contenido->CurrentValue;
		$this->contenido->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_sector
			$this->id_sector->LinkCustomAttributes = "";
			$this->id_sector->HrefValue = "";
			$this->id_sector->TooltipValue = "";

			// id_tipoactividad
			$this->id_tipoactividad->LinkCustomAttributes = "";
			$this->id_tipoactividad->HrefValue = "";
			$this->id_tipoactividad->TooltipValue = "";

			// organizador
			$this->organizador->LinkCustomAttributes = "";
			$this->organizador->HrefValue = "";
			$this->organizador->TooltipValue = "";

			// nombreactividad
			$this->nombreactividad->LinkCustomAttributes = "";
			$this->nombreactividad->HrefValue = "";
			$this->nombreactividad->TooltipValue = "";

			// nombrelocal
			$this->nombrelocal->LinkCustomAttributes = "";
			$this->nombrelocal->HrefValue = "";
			$this->nombrelocal->TooltipValue = "";

			// direccionlocal
			$this->direccionlocal->LinkCustomAttributes = "";
			$this->direccionlocal->HrefValue = "";
			$this->direccionlocal->TooltipValue = "";

			// fecha_inicio
			$this->fecha_inicio->LinkCustomAttributes = "";
			$this->fecha_inicio->HrefValue = "";
			$this->fecha_inicio->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

			// horasprogramadas
			$this->horasprogramadas->LinkCustomAttributes = "";
			$this->horasprogramadas->HrefValue = "";
			$this->horasprogramadas->TooltipValue = "";

			// id_persona
			$this->id_persona->LinkCustomAttributes = "";
			$this->id_persona->HrefValue = "";
			$this->id_persona->TooltipValue = "";

			// contenido
			$this->contenido->LinkCustomAttributes = "";
			$this->contenido->HrefValue = "";
			$this->contenido->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("actividadlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($actividad_delete)) $actividad_delete = new cactividad_delete();

// Page init
$actividad_delete->Page_Init();

// Page main
$actividad_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$actividad_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = factividaddelete = new ew_Form("factividaddelete", "delete");

// Form_CustomValidate event
factividaddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factividaddelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factividaddelete.Lists["x_id_sector"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sector"};
factividaddelete.Lists["x_id_sector"].Data = "<?php echo $actividad_delete->id_sector->LookupFilterQuery(FALSE, "delete") ?>";
factividaddelete.Lists["x_id_tipoactividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoactividad"};
factividaddelete.Lists["x_id_tipoactividad"].Data = "<?php echo $actividad_delete->id_tipoactividad->LookupFilterQuery(FALSE, "delete") ?>";
factividaddelete.Lists["x_organizador"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
factividaddelete.Lists["x_organizador"].Data = "<?php echo $actividad_delete->organizador->LookupFilterQuery(FALSE, "delete") ?>";
factividaddelete.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
factividaddelete.Lists["x_id_persona"].Data = "<?php echo $actividad_delete->id_persona->LookupFilterQuery(FALSE, "delete") ?>";
factividaddelete.AutoSuggests["x_id_persona"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $actividad_delete->id_persona->LookupFilterQuery(TRUE, "delete"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $actividad_delete->ShowPageHeader(); ?>
<?php
$actividad_delete->ShowMessage();
?>
<form name="factividaddelete" id="factividaddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($actividad_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $actividad_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="actividad">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($actividad_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($actividad->id->Visible) { // id ?>
		<th class="<?php echo $actividad->id->HeaderCellClass() ?>"><span id="elh_actividad_id" class="actividad_id"><?php echo $actividad->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->id_sector->Visible) { // id_sector ?>
		<th class="<?php echo $actividad->id_sector->HeaderCellClass() ?>"><span id="elh_actividad_id_sector" class="actividad_id_sector"><?php echo $actividad->id_sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->id_tipoactividad->Visible) { // id_tipoactividad ?>
		<th class="<?php echo $actividad->id_tipoactividad->HeaderCellClass() ?>"><span id="elh_actividad_id_tipoactividad" class="actividad_id_tipoactividad"><?php echo $actividad->id_tipoactividad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->organizador->Visible) { // organizador ?>
		<th class="<?php echo $actividad->organizador->HeaderCellClass() ?>"><span id="elh_actividad_organizador" class="actividad_organizador"><?php echo $actividad->organizador->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->nombreactividad->Visible) { // nombreactividad ?>
		<th class="<?php echo $actividad->nombreactividad->HeaderCellClass() ?>"><span id="elh_actividad_nombreactividad" class="actividad_nombreactividad"><?php echo $actividad->nombreactividad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->nombrelocal->Visible) { // nombrelocal ?>
		<th class="<?php echo $actividad->nombrelocal->HeaderCellClass() ?>"><span id="elh_actividad_nombrelocal" class="actividad_nombrelocal"><?php echo $actividad->nombrelocal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->direccionlocal->Visible) { // direccionlocal ?>
		<th class="<?php echo $actividad->direccionlocal->HeaderCellClass() ?>"><span id="elh_actividad_direccionlocal" class="actividad_direccionlocal"><?php echo $actividad->direccionlocal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->fecha_inicio->Visible) { // fecha_inicio ?>
		<th class="<?php echo $actividad->fecha_inicio->HeaderCellClass() ?>"><span id="elh_actividad_fecha_inicio" class="actividad_fecha_inicio"><?php echo $actividad->fecha_inicio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->fecha_fin->Visible) { // fecha_fin ?>
		<th class="<?php echo $actividad->fecha_fin->HeaderCellClass() ?>"><span id="elh_actividad_fecha_fin" class="actividad_fecha_fin"><?php echo $actividad->fecha_fin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->horasprogramadas->Visible) { // horasprogramadas ?>
		<th class="<?php echo $actividad->horasprogramadas->HeaderCellClass() ?>"><span id="elh_actividad_horasprogramadas" class="actividad_horasprogramadas"><?php echo $actividad->horasprogramadas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->id_persona->Visible) { // id_persona ?>
		<th class="<?php echo $actividad->id_persona->HeaderCellClass() ?>"><span id="elh_actividad_id_persona" class="actividad_id_persona"><?php echo $actividad->id_persona->FldCaption() ?></span></th>
<?php } ?>
<?php if ($actividad->contenido->Visible) { // contenido ?>
		<th class="<?php echo $actividad->contenido->HeaderCellClass() ?>"><span id="elh_actividad_contenido" class="actividad_contenido"><?php echo $actividad->contenido->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$actividad_delete->RecCnt = 0;
$i = 0;
while (!$actividad_delete->Recordset->EOF) {
	$actividad_delete->RecCnt++;
	$actividad_delete->RowCnt++;

	// Set row properties
	$actividad->ResetAttrs();
	$actividad->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$actividad_delete->LoadRowValues($actividad_delete->Recordset);

	// Render row
	$actividad_delete->RenderRow();
?>
	<tr<?php echo $actividad->RowAttributes() ?>>
<?php if ($actividad->id->Visible) { // id ?>
		<td<?php echo $actividad->id->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_id" class="actividad_id">
<span<?php echo $actividad->id->ViewAttributes() ?>>
<?php echo $actividad->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->id_sector->Visible) { // id_sector ?>
		<td<?php echo $actividad->id_sector->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_id_sector" class="actividad_id_sector">
<span<?php echo $actividad->id_sector->ViewAttributes() ?>>
<?php echo $actividad->id_sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->id_tipoactividad->Visible) { // id_tipoactividad ?>
		<td<?php echo $actividad->id_tipoactividad->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_id_tipoactividad" class="actividad_id_tipoactividad">
<span<?php echo $actividad->id_tipoactividad->ViewAttributes() ?>>
<?php echo $actividad->id_tipoactividad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->organizador->Visible) { // organizador ?>
		<td<?php echo $actividad->organizador->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_organizador" class="actividad_organizador">
<span<?php echo $actividad->organizador->ViewAttributes() ?>>
<?php echo $actividad->organizador->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->nombreactividad->Visible) { // nombreactividad ?>
		<td<?php echo $actividad->nombreactividad->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_nombreactividad" class="actividad_nombreactividad">
<span<?php echo $actividad->nombreactividad->ViewAttributes() ?>>
<?php echo $actividad->nombreactividad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->nombrelocal->Visible) { // nombrelocal ?>
		<td<?php echo $actividad->nombrelocal->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_nombrelocal" class="actividad_nombrelocal">
<span<?php echo $actividad->nombrelocal->ViewAttributes() ?>>
<?php echo $actividad->nombrelocal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->direccionlocal->Visible) { // direccionlocal ?>
		<td<?php echo $actividad->direccionlocal->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_direccionlocal" class="actividad_direccionlocal">
<span<?php echo $actividad->direccionlocal->ViewAttributes() ?>>
<?php echo $actividad->direccionlocal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->fecha_inicio->Visible) { // fecha_inicio ?>
		<td<?php echo $actividad->fecha_inicio->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_fecha_inicio" class="actividad_fecha_inicio">
<span<?php echo $actividad->fecha_inicio->ViewAttributes() ?>>
<?php echo $actividad->fecha_inicio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->fecha_fin->Visible) { // fecha_fin ?>
		<td<?php echo $actividad->fecha_fin->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_fecha_fin" class="actividad_fecha_fin">
<span<?php echo $actividad->fecha_fin->ViewAttributes() ?>>
<?php echo $actividad->fecha_fin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->horasprogramadas->Visible) { // horasprogramadas ?>
		<td<?php echo $actividad->horasprogramadas->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_horasprogramadas" class="actividad_horasprogramadas">
<span<?php echo $actividad->horasprogramadas->ViewAttributes() ?>>
<?php echo $actividad->horasprogramadas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->id_persona->Visible) { // id_persona ?>
		<td<?php echo $actividad->id_persona->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_id_persona" class="actividad_id_persona">
<span<?php echo $actividad->id_persona->ViewAttributes() ?>>
<?php echo $actividad->id_persona->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($actividad->contenido->Visible) { // contenido ?>
		<td<?php echo $actividad->contenido->CellAttributes() ?>>
<span id="el<?php echo $actividad_delete->RowCnt ?>_actividad_contenido" class="actividad_contenido">
<span<?php echo $actividad->contenido->ViewAttributes() ?>>
<?php echo $actividad->contenido->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$actividad_delete->Recordset->MoveNext();
}
$actividad_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $actividad_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
factividaddelete.Init();
</script>
<?php
$actividad_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$actividad_delete->Page_Terminate();
?>
