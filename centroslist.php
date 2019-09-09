<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "centrosinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$centros_list = NULL; // Initialize page object first

class ccentros_list extends ccentros {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'centros';

	// Page object name
	var $PageObjName = 'centros_list';

	// Grid form hidden field names
	var $FormName = 'fcentroslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (centros)
		if (!isset($GLOBALS["centros"]) || get_class($GLOBALS["centros"]) == "ccentros") {
			$GLOBALS["centros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["centros"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "centrosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "centrosdelete.php";
		$this->MultiUpdateUrl = "centrosupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'centros', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fcentroslistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->nombreinstitucion->SetVisibility();
		$this->direccion->SetVisibility();
		$this->telefono->SetVisibility();
		$this->ciudad->SetVisibility();
		$this->provincia->SetVisibility();
		$this->municipio->SetVisibility();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $centros;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($centros);
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->nombreinstitucion->AdvancedSearch->ToJson(), ","); // Field nombreinstitucion
		$sFilterList = ew_Concat($sFilterList, $this->direccion->AdvancedSearch->ToJson(), ","); // Field direccion
		$sFilterList = ew_Concat($sFilterList, $this->telefono->AdvancedSearch->ToJson(), ","); // Field telefono
		$sFilterList = ew_Concat($sFilterList, $this->ciudad->AdvancedSearch->ToJson(), ","); // Field ciudad
		$sFilterList = ew_Concat($sFilterList, $this->provincia->AdvancedSearch->ToJson(), ","); // Field provincia
		$sFilterList = ew_Concat($sFilterList, $this->municipio->AdvancedSearch->ToJson(), ","); // Field municipio
		$sFilterList = ew_Concat($sFilterList, $this->id_persona->AdvancedSearch->ToJson(), ","); // Field id_persona
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fcentroslistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field nombreinstitucion
		$this->nombreinstitucion->AdvancedSearch->SearchValue = @$filter["x_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->SearchOperator = @$filter["z_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->SearchCondition = @$filter["v_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->SearchValue2 = @$filter["y_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->SearchOperator2 = @$filter["w_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->Save();

		// Field direccion
		$this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
		$this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
		$this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
		$this->direccion->AdvancedSearch->Save();

		// Field telefono
		$this->telefono->AdvancedSearch->SearchValue = @$filter["x_telefono"];
		$this->telefono->AdvancedSearch->SearchOperator = @$filter["z_telefono"];
		$this->telefono->AdvancedSearch->SearchCondition = @$filter["v_telefono"];
		$this->telefono->AdvancedSearch->SearchValue2 = @$filter["y_telefono"];
		$this->telefono->AdvancedSearch->SearchOperator2 = @$filter["w_telefono"];
		$this->telefono->AdvancedSearch->Save();

		// Field ciudad
		$this->ciudad->AdvancedSearch->SearchValue = @$filter["x_ciudad"];
		$this->ciudad->AdvancedSearch->SearchOperator = @$filter["z_ciudad"];
		$this->ciudad->AdvancedSearch->SearchCondition = @$filter["v_ciudad"];
		$this->ciudad->AdvancedSearch->SearchValue2 = @$filter["y_ciudad"];
		$this->ciudad->AdvancedSearch->SearchOperator2 = @$filter["w_ciudad"];
		$this->ciudad->AdvancedSearch->Save();

		// Field provincia
		$this->provincia->AdvancedSearch->SearchValue = @$filter["x_provincia"];
		$this->provincia->AdvancedSearch->SearchOperator = @$filter["z_provincia"];
		$this->provincia->AdvancedSearch->SearchCondition = @$filter["v_provincia"];
		$this->provincia->AdvancedSearch->SearchValue2 = @$filter["y_provincia"];
		$this->provincia->AdvancedSearch->SearchOperator2 = @$filter["w_provincia"];
		$this->provincia->AdvancedSearch->Save();

		// Field municipio
		$this->municipio->AdvancedSearch->SearchValue = @$filter["x_municipio"];
		$this->municipio->AdvancedSearch->SearchOperator = @$filter["z_municipio"];
		$this->municipio->AdvancedSearch->SearchCondition = @$filter["v_municipio"];
		$this->municipio->AdvancedSearch->SearchValue2 = @$filter["y_municipio"];
		$this->municipio->AdvancedSearch->SearchOperator2 = @$filter["w_municipio"];
		$this->municipio->AdvancedSearch->Save();

		// Field id_persona
		$this->id_persona->AdvancedSearch->SearchValue = @$filter["x_id_persona"];
		$this->id_persona->AdvancedSearch->SearchOperator = @$filter["z_id_persona"];
		$this->id_persona->AdvancedSearch->SearchCondition = @$filter["v_id_persona"];
		$this->id_persona->AdvancedSearch->SearchValue2 = @$filter["y_id_persona"];
		$this->id_persona->AdvancedSearch->SearchOperator2 = @$filter["w_id_persona"];
		$this->id_persona->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nombreinstitucion, $Default, FALSE); // nombreinstitucion
		$this->BuildSearchSql($sWhere, $this->direccion, $Default, FALSE); // direccion
		$this->BuildSearchSql($sWhere, $this->telefono, $Default, FALSE); // telefono
		$this->BuildSearchSql($sWhere, $this->ciudad, $Default, FALSE); // ciudad
		$this->BuildSearchSql($sWhere, $this->provincia, $Default, FALSE); // provincia
		$this->BuildSearchSql($sWhere, $this->municipio, $Default, FALSE); // municipio
		$this->BuildSearchSql($sWhere, $this->id_persona, $Default, FALSE); // id_persona

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->nombreinstitucion->AdvancedSearch->Save(); // nombreinstitucion
			$this->direccion->AdvancedSearch->Save(); // direccion
			$this->telefono->AdvancedSearch->Save(); // telefono
			$this->ciudad->AdvancedSearch->Save(); // ciudad
			$this->provincia->AdvancedSearch->Save(); // provincia
			$this->municipio->AdvancedSearch->Save(); // municipio
			$this->id_persona->AdvancedSearch->Save(); // id_persona
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = $Fld->FldParm();
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->nombreinstitucion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->telefono->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ciudad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->provincia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->municipio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_persona->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->nombreinstitucion->AdvancedSearch->UnsetSession();
		$this->direccion->AdvancedSearch->UnsetSession();
		$this->telefono->AdvancedSearch->UnsetSession();
		$this->ciudad->AdvancedSearch->UnsetSession();
		$this->provincia->AdvancedSearch->UnsetSession();
		$this->municipio->AdvancedSearch->UnsetSession();
		$this->id_persona->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nombreinstitucion->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->telefono->AdvancedSearch->Load();
		$this->ciudad->AdvancedSearch->Load();
		$this->provincia->AdvancedSearch->Load();
		$this->municipio->AdvancedSearch->Load();
		$this->id_persona->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nombreinstitucion, $bCtrl); // nombreinstitucion
			$this->UpdateSort($this->direccion, $bCtrl); // direccion
			$this->UpdateSort($this->telefono, $bCtrl); // telefono
			$this->UpdateSort($this->ciudad, $bCtrl); // ciudad
			$this->UpdateSort($this->provincia, $bCtrl); // provincia
			$this->UpdateSort($this->municipio, $bCtrl); // municipio
			$this->UpdateSort($this->id_persona, $bCtrl); // id_persona
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->nombreinstitucion->setSort("");
				$this->direccion->setSort("");
				$this->telefono->setSort("");
				$this->ciudad->setSort("");
				$this->provincia->setSort("");
				$this->municipio->setSort("");
				$this->id_persona->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fcentroslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fcentroslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fcentroslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fcentroslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// nombreinstitucion

		$this->nombreinstitucion->AdvancedSearch->SearchValue = @$_GET["x_nombreinstitucion"];
		if ($this->nombreinstitucion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombreinstitucion->AdvancedSearch->SearchOperator = @$_GET["z_nombreinstitucion"];

		// direccion
		$this->direccion->AdvancedSearch->SearchValue = @$_GET["x_direccion"];
		if ($this->direccion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->direccion->AdvancedSearch->SearchOperator = @$_GET["z_direccion"];

		// telefono
		$this->telefono->AdvancedSearch->SearchValue = @$_GET["x_telefono"];
		if ($this->telefono->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->telefono->AdvancedSearch->SearchOperator = @$_GET["z_telefono"];

		// ciudad
		$this->ciudad->AdvancedSearch->SearchValue = @$_GET["x_ciudad"];
		if ($this->ciudad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->ciudad->AdvancedSearch->SearchOperator = @$_GET["z_ciudad"];

		// provincia
		$this->provincia->AdvancedSearch->SearchValue = @$_GET["x_provincia"];
		if ($this->provincia->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->provincia->AdvancedSearch->SearchOperator = @$_GET["z_provincia"];

		// municipio
		$this->municipio->AdvancedSearch->SearchValue = @$_GET["x_municipio"];
		if ($this->municipio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->municipio->AdvancedSearch->SearchOperator = @$_GET["z_municipio"];

		// id_persona
		$this->id_persona->AdvancedSearch->SearchValue = @$_GET["x_id_persona"];
		if ($this->id_persona->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_persona->AdvancedSearch->SearchOperator = @$_GET["z_id_persona"];
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
		$this->nombreinstitucion->setDbValue($row['nombreinstitucion']);
		$this->direccion->setDbValue($row['direccion']);
		$this->telefono->setDbValue($row['telefono']);
		$this->ciudad->setDbValue($row['ciudad']);
		$this->provincia->setDbValue($row['provincia']);
		$this->municipio->setDbValue($row['municipio']);
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
		$row['nombreinstitucion'] = NULL;
		$row['direccion'] = NULL;
		$row['telefono'] = NULL;
		$row['ciudad'] = NULL;
		$row['provincia'] = NULL;
		$row['municipio'] = NULL;
		$row['id_persona'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->nombreinstitucion->DbValue = $row['nombreinstitucion'];
		$this->direccion->DbValue = $row['direccion'];
		$this->telefono->DbValue = $row['telefono'];
		$this->ciudad->DbValue = $row['ciudad'];
		$this->provincia->DbValue = $row['provincia'];
		$this->municipio->DbValue = $row['municipio'];
		$this->id_persona->DbValue = $row['id_persona'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// nombreinstitucion
		// direccion
		// telefono
		// ciudad
		// provincia
		// municipio
		// id_persona

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// nombreinstitucion
		$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
		$this->nombreinstitucion->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// ciudad
		if (strval($this->ciudad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->ciudad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
		$sWhereWrk = "";
		$this->ciudad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->ciudad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->ciudad->ViewValue = $this->ciudad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->ciudad->ViewValue = $this->ciudad->CurrentValue;
			}
		} else {
			$this->ciudad->ViewValue = NULL;
		}
		$this->ciudad->ViewCustomAttributes = "";

		// provincia
		if (strval($this->provincia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->provincia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincia`";
		$sWhereWrk = "";
		$this->provincia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->provincia, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->provincia->ViewValue = $this->provincia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->provincia->ViewValue = $this->provincia->CurrentValue;
			}
		} else {
			$this->provincia->ViewValue = NULL;
		}
		$this->provincia->ViewCustomAttributes = "";

		// municipio
		if (strval($this->municipio->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->municipio->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
		$sWhereWrk = "";
		$this->municipio->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->municipio, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->municipio->ViewValue = $this->municipio->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->municipio->ViewValue = $this->municipio->CurrentValue;
			}
		} else {
			$this->municipio->ViewValue = NULL;
		}
		$this->municipio->ViewCustomAttributes = "";

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

			// nombreinstitucion
			$this->nombreinstitucion->LinkCustomAttributes = "";
			$this->nombreinstitucion->HrefValue = "";
			$this->nombreinstitucion->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// ciudad
			$this->ciudad->LinkCustomAttributes = "";
			$this->ciudad->HrefValue = "";
			$this->ciudad->TooltipValue = "";

			// provincia
			$this->provincia->LinkCustomAttributes = "";
			$this->provincia->HrefValue = "";
			$this->provincia->TooltipValue = "";

			// municipio
			$this->municipio->LinkCustomAttributes = "";
			$this->municipio->HrefValue = "";
			$this->municipio->TooltipValue = "";

			// id_persona
			$this->id_persona->LinkCustomAttributes = "";
			$this->id_persona->HrefValue = "";
			$this->id_persona->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nombreinstitucion
			$this->nombreinstitucion->EditAttrs["class"] = "form-control";
			$this->nombreinstitucion->EditCustomAttributes = "";
			$this->nombreinstitucion->EditValue = ew_HtmlEncode($this->nombreinstitucion->AdvancedSearch->SearchValue);
			$this->nombreinstitucion->PlaceHolder = ew_RemoveHtml($this->nombreinstitucion->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->AdvancedSearch->SearchValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->AdvancedSearch->SearchValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// ciudad
			$this->ciudad->EditAttrs["class"] = "form-control";
			$this->ciudad->EditCustomAttributes = "";
			if (trim(strval($this->ciudad->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->ciudad->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamento`";
			$sWhereWrk = "";
			$this->ciudad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->ciudad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->ciudad->EditValue = $arwrk;

			// provincia
			$this->provincia->EditAttrs["class"] = "form-control";
			$this->provincia->EditCustomAttributes = "";
			if (trim(strval($this->provincia->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->provincia->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `provincia`";
			$sWhereWrk = "";
			$this->provincia->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->provincia, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->provincia->EditValue = $arwrk;

			// municipio
			$this->municipio->EditAttrs["class"] = "form-control";
			$this->municipio->EditCustomAttributes = "";
			if (trim(strval($this->municipio->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->municipio->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `municipio`";
			$sWhereWrk = "";
			$this->municipio->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->municipio, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->municipio->EditValue = $arwrk;

			// id_persona
			$this->id_persona->EditAttrs["class"] = "form-control";
			$this->id_persona->EditCustomAttributes = "";
			$this->id_persona->EditValue = ew_HtmlEncode($this->id_persona->AdvancedSearch->SearchValue);
			$this->id_persona->PlaceHolder = ew_RemoveHtml($this->id_persona->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->nombreinstitucion->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->telefono->AdvancedSearch->Load();
		$this->ciudad->AdvancedSearch->Load();
		$this->provincia->AdvancedSearch->Load();
		$this->municipio->AdvancedSearch->Load();
		$this->id_persona->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
		case "x_ciudad":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->ciudad, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_provincia":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincia`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->provincia, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_municipio":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->municipio, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_persona":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `persona`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
			}
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($centros_list)) $centros_list = new ccentros_list();

// Page init
$centros_list->Page_Init();

// Page main
$centros_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$centros_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fcentroslist = new ew_Form("fcentroslist", "list");
fcentroslist.FormKeyCountName = '<?php echo $centros_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcentroslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fcentroslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fcentroslist.Lists["x_ciudad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fcentroslist.Lists["x_ciudad"].Data = "<?php echo $centros_list->ciudad->LookupFilterQuery(FALSE, "list") ?>";
fcentroslist.Lists["x_provincia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_descripcion","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincia"};
fcentroslist.Lists["x_provincia"].Data = "<?php echo $centros_list->provincia->LookupFilterQuery(FALSE, "list") ?>";
fcentroslist.Lists["x_municipio"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_descripcion","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"municipio"};
fcentroslist.Lists["x_municipio"].Data = "<?php echo $centros_list->municipio->LookupFilterQuery(FALSE, "list") ?>";
fcentroslist.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
fcentroslist.Lists["x_id_persona"].Data = "<?php echo $centros_list->id_persona->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fcentroslistsrch = new ew_Form("fcentroslistsrch");

// Validate function for search
fcentroslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fcentroslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fcentroslistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fcentroslistsrch.Lists["x_ciudad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fcentroslistsrch.Lists["x_ciudad"].Data = "<?php echo $centros_list->ciudad->LookupFilterQuery(FALSE, "extbs") ?>";
fcentroslistsrch.Lists["x_provincia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_descripcion","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincia"};
fcentroslistsrch.Lists["x_provincia"].Data = "<?php echo $centros_list->provincia->LookupFilterQuery(FALSE, "extbs") ?>";
fcentroslistsrch.Lists["x_municipio"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_descripcion","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"municipio"};
fcentroslistsrch.Lists["x_municipio"].Data = "<?php echo $centros_list->municipio->LookupFilterQuery(FALSE, "extbs") ?>";
fcentroslistsrch.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
fcentroslistsrch.Lists["x_id_persona"].Data = "<?php echo $centros_list->id_persona->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($centros_list->TotalRecs > 0 && $centros_list->ExportOptions->Visible()) { ?>
<?php $centros_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($centros_list->SearchOptions->Visible()) { ?>
<?php $centros_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($centros_list->FilterOptions->Visible()) { ?>
<?php $centros_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $centros_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($centros_list->TotalRecs <= 0)
			$centros_list->TotalRecs = $centros->ListRecordCount();
	} else {
		if (!$centros_list->Recordset && ($centros_list->Recordset = $centros_list->LoadRecordset()))
			$centros_list->TotalRecs = $centros_list->Recordset->RecordCount();
	}
	$centros_list->StartRec = 1;
	if ($centros_list->DisplayRecs <= 0 || ($centros->Export <> "" && $centros->ExportAll)) // Display all records
		$centros_list->DisplayRecs = $centros_list->TotalRecs;
	if (!($centros->Export <> "" && $centros->ExportAll))
		$centros_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$centros_list->Recordset = $centros_list->LoadRecordset($centros_list->StartRec-1, $centros_list->DisplayRecs);

	// Set no record found message
	if ($centros->CurrentAction == "" && $centros_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$centros_list->setWarningMessage(ew_DeniedMsg());
		if ($centros_list->SearchWhere == "0=101")
			$centros_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$centros_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$centros_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($centros->Export == "" && $centros->CurrentAction == "") { ?>
<form name="fcentroslistsrch" id="fcentroslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($centros_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fcentroslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="centros">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$centros_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$centros->RowType = EW_ROWTYPE_SEARCH;

// Render row
$centros->ResetAttrs();
$centros_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($centros->nombreinstitucion->Visible) { // nombreinstitucion ?>
	<div id="xsc_nombreinstitucion" class="ewCell form-group">
		<label for="x_nombreinstitucion" class="ewSearchCaption ewLabel"><?php echo $centros->nombreinstitucion->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombreinstitucion" id="z_nombreinstitucion" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="centros" data-field="x_nombreinstitucion" name="x_nombreinstitucion" id="x_nombreinstitucion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($centros->nombreinstitucion->getPlaceHolder()) ?>" value="<?php echo $centros->nombreinstitucion->EditValue ?>"<?php echo $centros->nombreinstitucion->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($centros->direccion->Visible) { // direccion ?>
	<div id="xsc_direccion" class="ewCell form-group">
		<label for="x_direccion" class="ewSearchCaption ewLabel"><?php echo $centros->direccion->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_direccion" id="z_direccion" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="centros" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($centros->direccion->getPlaceHolder()) ?>" value="<?php echo $centros->direccion->EditValue ?>"<?php echo $centros->direccion->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($centros->telefono->Visible) { // telefono ?>
	<div id="xsc_telefono" class="ewCell form-group">
		<label for="x_telefono" class="ewSearchCaption ewLabel"><?php echo $centros->telefono->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_telefono" id="z_telefono" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="centros" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($centros->telefono->getPlaceHolder()) ?>" value="<?php echo $centros->telefono->EditValue ?>"<?php echo $centros->telefono->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($centros->ciudad->Visible) { // ciudad ?>
	<div id="xsc_ciudad" class="ewCell form-group">
		<label for="x_ciudad" class="ewSearchCaption ewLabel"><?php echo $centros->ciudad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ciudad" id="z_ciudad" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="centros" data-field="x_ciudad" data-value-separator="<?php echo $centros->ciudad->DisplayValueSeparatorAttribute() ?>" id="x_ciudad" name="x_ciudad"<?php echo $centros->ciudad->EditAttributes() ?>>
<?php echo $centros->ciudad->SelectOptionListHtml("x_ciudad") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($centros->provincia->Visible) { // provincia ?>
	<div id="xsc_provincia" class="ewCell form-group">
		<label for="x_provincia" class="ewSearchCaption ewLabel"><?php echo $centros->provincia->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_provincia" id="z_provincia" value="="></span>
		<span class="ewSearchField">
<select data-table="centros" data-field="x_provincia" data-value-separator="<?php echo $centros->provincia->DisplayValueSeparatorAttribute() ?>" id="x_provincia" name="x_provincia"<?php echo $centros->provincia->EditAttributes() ?>>
<?php echo $centros->provincia->SelectOptionListHtml("x_provincia") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($centros->municipio->Visible) { // municipio ?>
	<div id="xsc_municipio" class="ewCell form-group">
		<label for="x_municipio" class="ewSearchCaption ewLabel"><?php echo $centros->municipio->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_municipio" id="z_municipio" value="="></span>
		<span class="ewSearchField">
<select data-table="centros" data-field="x_municipio" data-value-separator="<?php echo $centros->municipio->DisplayValueSeparatorAttribute() ?>" id="x_municipio" name="x_municipio"<?php echo $centros->municipio->EditAttributes() ?>>
<?php echo $centros->municipio->SelectOptionListHtml("x_municipio") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($centros->id_persona->Visible) { // id_persona ?>
	<div id="xsc_id_persona" class="ewCell form-group">
		<label for="x_id_persona" class="ewSearchCaption ewLabel"><?php echo $centros->id_persona->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_persona" id="z_id_persona" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="centros" data-field="x_id_persona" name="x_id_persona" id="x_id_persona" size="30" placeholder="<?php echo ew_HtmlEncode($centros->id_persona->getPlaceHolder()) ?>" value="<?php echo $centros->id_persona->EditValue ?>"<?php echo $centros->id_persona->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $centros_list->ShowPageHeader(); ?>
<?php
$centros_list->ShowMessage();
?>
<?php if ($centros_list->TotalRecs > 0 || $centros->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($centros_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> centros">
<div class="box-header ewGridUpperPanel">
<?php if ($centros->CurrentAction <> "gridadd" && $centros->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($centros_list->Pager)) $centros_list->Pager = new cPrevNextPager($centros_list->StartRec, $centros_list->DisplayRecs, $centros_list->TotalRecs, $centros_list->AutoHidePager) ?>
<?php if ($centros_list->Pager->RecordCount > 0 && $centros_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($centros_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $centros_list->PageUrl() ?>start=<?php echo $centros_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($centros_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $centros_list->PageUrl() ?>start=<?php echo $centros_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $centros_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($centros_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $centros_list->PageUrl() ?>start=<?php echo $centros_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($centros_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $centros_list->PageUrl() ?>start=<?php echo $centros_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $centros_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($centros_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $centros_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $centros_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $centros_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($centros_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fcentroslist" id="fcentroslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($centros_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $centros_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="centros">
<div id="gmp_centros" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($centros_list->TotalRecs > 0 || $centros->CurrentAction == "gridedit") { ?>
<table id="tbl_centroslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$centros_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$centros_list->RenderListOptions();

// Render list options (header, left)
$centros_list->ListOptions->Render("header", "left");
?>
<?php if ($centros->nombreinstitucion->Visible) { // nombreinstitucion ?>
	<?php if ($centros->SortUrl($centros->nombreinstitucion) == "") { ?>
		<th data-name="nombreinstitucion" class="<?php echo $centros->nombreinstitucion->HeaderCellClass() ?>"><div id="elh_centros_nombreinstitucion" class="centros_nombreinstitucion"><div class="ewTableHeaderCaption"><?php echo $centros->nombreinstitucion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombreinstitucion" class="<?php echo $centros->nombreinstitucion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $centros->SortUrl($centros->nombreinstitucion) ?>',2);"><div id="elh_centros_nombreinstitucion" class="centros_nombreinstitucion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $centros->nombreinstitucion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($centros->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($centros->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($centros->direccion->Visible) { // direccion ?>
	<?php if ($centros->SortUrl($centros->direccion) == "") { ?>
		<th data-name="direccion" class="<?php echo $centros->direccion->HeaderCellClass() ?>"><div id="elh_centros_direccion" class="centros_direccion"><div class="ewTableHeaderCaption"><?php echo $centros->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion" class="<?php echo $centros->direccion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $centros->SortUrl($centros->direccion) ?>',2);"><div id="elh_centros_direccion" class="centros_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $centros->direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($centros->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($centros->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($centros->telefono->Visible) { // telefono ?>
	<?php if ($centros->SortUrl($centros->telefono) == "") { ?>
		<th data-name="telefono" class="<?php echo $centros->telefono->HeaderCellClass() ?>"><div id="elh_centros_telefono" class="centros_telefono"><div class="ewTableHeaderCaption"><?php echo $centros->telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono" class="<?php echo $centros->telefono->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $centros->SortUrl($centros->telefono) ?>',2);"><div id="elh_centros_telefono" class="centros_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $centros->telefono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($centros->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($centros->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($centros->ciudad->Visible) { // ciudad ?>
	<?php if ($centros->SortUrl($centros->ciudad) == "") { ?>
		<th data-name="ciudad" class="<?php echo $centros->ciudad->HeaderCellClass() ?>"><div id="elh_centros_ciudad" class="centros_ciudad"><div class="ewTableHeaderCaption"><?php echo $centros->ciudad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ciudad" class="<?php echo $centros->ciudad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $centros->SortUrl($centros->ciudad) ?>',2);"><div id="elh_centros_ciudad" class="centros_ciudad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $centros->ciudad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($centros->ciudad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($centros->ciudad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($centros->provincia->Visible) { // provincia ?>
	<?php if ($centros->SortUrl($centros->provincia) == "") { ?>
		<th data-name="provincia" class="<?php echo $centros->provincia->HeaderCellClass() ?>"><div id="elh_centros_provincia" class="centros_provincia"><div class="ewTableHeaderCaption"><?php echo $centros->provincia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="provincia" class="<?php echo $centros->provincia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $centros->SortUrl($centros->provincia) ?>',2);"><div id="elh_centros_provincia" class="centros_provincia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $centros->provincia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($centros->provincia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($centros->provincia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($centros->municipio->Visible) { // municipio ?>
	<?php if ($centros->SortUrl($centros->municipio) == "") { ?>
		<th data-name="municipio" class="<?php echo $centros->municipio->HeaderCellClass() ?>"><div id="elh_centros_municipio" class="centros_municipio"><div class="ewTableHeaderCaption"><?php echo $centros->municipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="municipio" class="<?php echo $centros->municipio->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $centros->SortUrl($centros->municipio) ?>',2);"><div id="elh_centros_municipio" class="centros_municipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $centros->municipio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($centros->municipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($centros->municipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($centros->id_persona->Visible) { // id_persona ?>
	<?php if ($centros->SortUrl($centros->id_persona) == "") { ?>
		<th data-name="id_persona" class="<?php echo $centros->id_persona->HeaderCellClass() ?>"><div id="elh_centros_id_persona" class="centros_id_persona"><div class="ewTableHeaderCaption"><?php echo $centros->id_persona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_persona" class="<?php echo $centros->id_persona->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $centros->SortUrl($centros->id_persona) ?>',2);"><div id="elh_centros_id_persona" class="centros_id_persona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $centros->id_persona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($centros->id_persona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($centros->id_persona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$centros_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($centros->ExportAll && $centros->Export <> "") {
	$centros_list->StopRec = $centros_list->TotalRecs;
} else {

	// Set the last record to display
	if ($centros_list->TotalRecs > $centros_list->StartRec + $centros_list->DisplayRecs - 1)
		$centros_list->StopRec = $centros_list->StartRec + $centros_list->DisplayRecs - 1;
	else
		$centros_list->StopRec = $centros_list->TotalRecs;
}
$centros_list->RecCnt = $centros_list->StartRec - 1;
if ($centros_list->Recordset && !$centros_list->Recordset->EOF) {
	$centros_list->Recordset->MoveFirst();
	$bSelectLimit = $centros_list->UseSelectLimit;
	if (!$bSelectLimit && $centros_list->StartRec > 1)
		$centros_list->Recordset->Move($centros_list->StartRec - 1);
} elseif (!$centros->AllowAddDeleteRow && $centros_list->StopRec == 0) {
	$centros_list->StopRec = $centros->GridAddRowCount;
}

// Initialize aggregate
$centros->RowType = EW_ROWTYPE_AGGREGATEINIT;
$centros->ResetAttrs();
$centros_list->RenderRow();
while ($centros_list->RecCnt < $centros_list->StopRec) {
	$centros_list->RecCnt++;
	if (intval($centros_list->RecCnt) >= intval($centros_list->StartRec)) {
		$centros_list->RowCnt++;

		// Set up key count
		$centros_list->KeyCount = $centros_list->RowIndex;

		// Init row class and style
		$centros->ResetAttrs();
		$centros->CssClass = "";
		if ($centros->CurrentAction == "gridadd") {
		} else {
			$centros_list->LoadRowValues($centros_list->Recordset); // Load row values
		}
		$centros->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$centros->RowAttrs = array_merge($centros->RowAttrs, array('data-rowindex'=>$centros_list->RowCnt, 'id'=>'r' . $centros_list->RowCnt . '_centros', 'data-rowtype'=>$centros->RowType));

		// Render row
		$centros_list->RenderRow();

		// Render list options
		$centros_list->RenderListOptions();
?>
	<tr<?php echo $centros->RowAttributes() ?>>
<?php

// Render list options (body, left)
$centros_list->ListOptions->Render("body", "left", $centros_list->RowCnt);
?>
	<?php if ($centros->nombreinstitucion->Visible) { // nombreinstitucion ?>
		<td data-name="nombreinstitucion"<?php echo $centros->nombreinstitucion->CellAttributes() ?>>
<span id="el<?php echo $centros_list->RowCnt ?>_centros_nombreinstitucion" class="centros_nombreinstitucion">
<span<?php echo $centros->nombreinstitucion->ViewAttributes() ?>>
<?php echo $centros->nombreinstitucion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($centros->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $centros->direccion->CellAttributes() ?>>
<span id="el<?php echo $centros_list->RowCnt ?>_centros_direccion" class="centros_direccion">
<span<?php echo $centros->direccion->ViewAttributes() ?>>
<?php echo $centros->direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($centros->telefono->Visible) { // telefono ?>
		<td data-name="telefono"<?php echo $centros->telefono->CellAttributes() ?>>
<span id="el<?php echo $centros_list->RowCnt ?>_centros_telefono" class="centros_telefono">
<span<?php echo $centros->telefono->ViewAttributes() ?>>
<?php echo $centros->telefono->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($centros->ciudad->Visible) { // ciudad ?>
		<td data-name="ciudad"<?php echo $centros->ciudad->CellAttributes() ?>>
<span id="el<?php echo $centros_list->RowCnt ?>_centros_ciudad" class="centros_ciudad">
<span<?php echo $centros->ciudad->ViewAttributes() ?>>
<?php echo $centros->ciudad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($centros->provincia->Visible) { // provincia ?>
		<td data-name="provincia"<?php echo $centros->provincia->CellAttributes() ?>>
<span id="el<?php echo $centros_list->RowCnt ?>_centros_provincia" class="centros_provincia">
<span<?php echo $centros->provincia->ViewAttributes() ?>>
<?php echo $centros->provincia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($centros->municipio->Visible) { // municipio ?>
		<td data-name="municipio"<?php echo $centros->municipio->CellAttributes() ?>>
<span id="el<?php echo $centros_list->RowCnt ?>_centros_municipio" class="centros_municipio">
<span<?php echo $centros->municipio->ViewAttributes() ?>>
<?php echo $centros->municipio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($centros->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona"<?php echo $centros->id_persona->CellAttributes() ?>>
<span id="el<?php echo $centros_list->RowCnt ?>_centros_id_persona" class="centros_id_persona">
<span<?php echo $centros->id_persona->ViewAttributes() ?>>
<?php echo $centros->id_persona->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$centros_list->ListOptions->Render("body", "right", $centros_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($centros->CurrentAction <> "gridadd")
		$centros_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($centros->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($centros_list->Recordset)
	$centros_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($centros->CurrentAction <> "gridadd" && $centros->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($centros_list->Pager)) $centros_list->Pager = new cPrevNextPager($centros_list->StartRec, $centros_list->DisplayRecs, $centros_list->TotalRecs, $centros_list->AutoHidePager) ?>
<?php if ($centros_list->Pager->RecordCount > 0 && $centros_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($centros_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $centros_list->PageUrl() ?>start=<?php echo $centros_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($centros_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $centros_list->PageUrl() ?>start=<?php echo $centros_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $centros_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($centros_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $centros_list->PageUrl() ?>start=<?php echo $centros_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($centros_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $centros_list->PageUrl() ?>start=<?php echo $centros_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $centros_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($centros_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $centros_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $centros_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $centros_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($centros_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($centros_list->TotalRecs == 0 && $centros->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($centros_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fcentroslistsrch.FilterList = <?php echo $centros_list->GetFilterList() ?>;
fcentroslistsrch.Init();
fcentroslist.Init();
</script>
<?php
$centros_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$centros_list->Page_Terminate();
?>
