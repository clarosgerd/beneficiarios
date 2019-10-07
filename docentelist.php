<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "docenteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$docente_list = NULL; // Initialize page object first

class cdocente_list extends cdocente {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'docente';

	// Page object name
	var $PageObjName = 'docente_list';

	// Grid form hidden field names
	var $FormName = 'fdocentelist';
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

		// Table object (docente)
		if (!isset($GLOBALS["docente"]) || get_class($GLOBALS["docente"]) == "cdocente") {
			$GLOBALS["docente"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["docente"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "docenteadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "docentedelete.php";
		$this->MultiUpdateUrl = "docenteupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'docente', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fdocentelistsrch";

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
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->id_departamento->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->celular->SetVisibility();
		$this->materias->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapacidad->SetVisibility();

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
		global $EW_EXPORT, $docente;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($docente);
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

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server" && isset($UserProfile))
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fdocentelistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->id_departamento->AdvancedSearch->ToJson(), ","); // Field id_departamento
		$sFilterList = ew_Concat($sFilterList, $this->unidadeducativa->AdvancedSearch->ToJson(), ","); // Field unidadeducativa
		$sFilterList = ew_Concat($sFilterList, $this->apellidopaterno->AdvancedSearch->ToJson(), ","); // Field apellidopaterno
		$sFilterList = ew_Concat($sFilterList, $this->apellidomaterno->AdvancedSearch->ToJson(), ","); // Field apellidomaterno
		$sFilterList = ew_Concat($sFilterList, $this->nombres->AdvancedSearch->ToJson(), ","); // Field nombres
		$sFilterList = ew_Concat($sFilterList, $this->nrodiscapacidad->AdvancedSearch->ToJson(), ","); // Field nrodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->ci->AdvancedSearch->ToJson(), ","); // Field ci
		$sFilterList = ew_Concat($sFilterList, $this->fechanacimiento->AdvancedSearch->ToJson(), ","); // Field fechanacimiento
		$sFilterList = ew_Concat($sFilterList, $this->sexo->AdvancedSearch->ToJson(), ","); // Field sexo
		$sFilterList = ew_Concat($sFilterList, $this->celular->AdvancedSearch->ToJson(), ","); // Field celular
		$sFilterList = ew_Concat($sFilterList, $this->materias->AdvancedSearch->ToJson(), ","); // Field materias
		$sFilterList = ew_Concat($sFilterList, $this->discapacidad->AdvancedSearch->ToJson(), ","); // Field discapacidad
		$sFilterList = ew_Concat($sFilterList, $this->tipodiscapacidad->AdvancedSearch->ToJson(), ","); // Field tipodiscapacidad
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fdocentelistsrch", $filters);

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

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field id_departamento
		$this->id_departamento->AdvancedSearch->SearchValue = @$filter["x_id_departamento"];
		$this->id_departamento->AdvancedSearch->SearchOperator = @$filter["z_id_departamento"];
		$this->id_departamento->AdvancedSearch->SearchCondition = @$filter["v_id_departamento"];
		$this->id_departamento->AdvancedSearch->SearchValue2 = @$filter["y_id_departamento"];
		$this->id_departamento->AdvancedSearch->SearchOperator2 = @$filter["w_id_departamento"];
		$this->id_departamento->AdvancedSearch->Save();

		// Field unidadeducativa
		$this->unidadeducativa->AdvancedSearch->SearchValue = @$filter["x_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->SearchOperator = @$filter["z_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->SearchCondition = @$filter["v_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->SearchValue2 = @$filter["y_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->SearchOperator2 = @$filter["w_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->Save();

		// Field apellidopaterno
		$this->apellidopaterno->AdvancedSearch->SearchValue = @$filter["x_apellidopaterno"];
		$this->apellidopaterno->AdvancedSearch->SearchOperator = @$filter["z_apellidopaterno"];
		$this->apellidopaterno->AdvancedSearch->SearchCondition = @$filter["v_apellidopaterno"];
		$this->apellidopaterno->AdvancedSearch->SearchValue2 = @$filter["y_apellidopaterno"];
		$this->apellidopaterno->AdvancedSearch->SearchOperator2 = @$filter["w_apellidopaterno"];
		$this->apellidopaterno->AdvancedSearch->Save();

		// Field apellidomaterno
		$this->apellidomaterno->AdvancedSearch->SearchValue = @$filter["x_apellidomaterno"];
		$this->apellidomaterno->AdvancedSearch->SearchOperator = @$filter["z_apellidomaterno"];
		$this->apellidomaterno->AdvancedSearch->SearchCondition = @$filter["v_apellidomaterno"];
		$this->apellidomaterno->AdvancedSearch->SearchValue2 = @$filter["y_apellidomaterno"];
		$this->apellidomaterno->AdvancedSearch->SearchOperator2 = @$filter["w_apellidomaterno"];
		$this->apellidomaterno->AdvancedSearch->Save();

		// Field nombres
		$this->nombres->AdvancedSearch->SearchValue = @$filter["x_nombres"];
		$this->nombres->AdvancedSearch->SearchOperator = @$filter["z_nombres"];
		$this->nombres->AdvancedSearch->SearchCondition = @$filter["v_nombres"];
		$this->nombres->AdvancedSearch->SearchValue2 = @$filter["y_nombres"];
		$this->nombres->AdvancedSearch->SearchOperator2 = @$filter["w_nombres"];
		$this->nombres->AdvancedSearch->Save();

		// Field nrodiscapacidad
		$this->nrodiscapacidad->AdvancedSearch->SearchValue = @$filter["x_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator = @$filter["z_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchCondition = @$filter["v_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchValue2 = @$filter["y_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->Save();

		// Field ci
		$this->ci->AdvancedSearch->SearchValue = @$filter["x_ci"];
		$this->ci->AdvancedSearch->SearchOperator = @$filter["z_ci"];
		$this->ci->AdvancedSearch->SearchCondition = @$filter["v_ci"];
		$this->ci->AdvancedSearch->SearchValue2 = @$filter["y_ci"];
		$this->ci->AdvancedSearch->SearchOperator2 = @$filter["w_ci"];
		$this->ci->AdvancedSearch->Save();

		// Field fechanacimiento
		$this->fechanacimiento->AdvancedSearch->SearchValue = @$filter["x_fechanacimiento"];
		$this->fechanacimiento->AdvancedSearch->SearchOperator = @$filter["z_fechanacimiento"];
		$this->fechanacimiento->AdvancedSearch->SearchCondition = @$filter["v_fechanacimiento"];
		$this->fechanacimiento->AdvancedSearch->SearchValue2 = @$filter["y_fechanacimiento"];
		$this->fechanacimiento->AdvancedSearch->SearchOperator2 = @$filter["w_fechanacimiento"];
		$this->fechanacimiento->AdvancedSearch->Save();

		// Field sexo
		$this->sexo->AdvancedSearch->SearchValue = @$filter["x_sexo"];
		$this->sexo->AdvancedSearch->SearchOperator = @$filter["z_sexo"];
		$this->sexo->AdvancedSearch->SearchCondition = @$filter["v_sexo"];
		$this->sexo->AdvancedSearch->SearchValue2 = @$filter["y_sexo"];
		$this->sexo->AdvancedSearch->SearchOperator2 = @$filter["w_sexo"];
		$this->sexo->AdvancedSearch->Save();

		// Field celular
		$this->celular->AdvancedSearch->SearchValue = @$filter["x_celular"];
		$this->celular->AdvancedSearch->SearchOperator = @$filter["z_celular"];
		$this->celular->AdvancedSearch->SearchCondition = @$filter["v_celular"];
		$this->celular->AdvancedSearch->SearchValue2 = @$filter["y_celular"];
		$this->celular->AdvancedSearch->SearchOperator2 = @$filter["w_celular"];
		$this->celular->AdvancedSearch->Save();

		// Field materias
		$this->materias->AdvancedSearch->SearchValue = @$filter["x_materias"];
		$this->materias->AdvancedSearch->SearchOperator = @$filter["z_materias"];
		$this->materias->AdvancedSearch->SearchCondition = @$filter["v_materias"];
		$this->materias->AdvancedSearch->SearchValue2 = @$filter["y_materias"];
		$this->materias->AdvancedSearch->SearchOperator2 = @$filter["w_materias"];
		$this->materias->AdvancedSearch->Save();

		// Field discapacidad
		$this->discapacidad->AdvancedSearch->SearchValue = @$filter["x_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchOperator = @$filter["z_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchCondition = @$filter["v_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchValue2 = @$filter["y_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_discapacidad"];
		$this->discapacidad->AdvancedSearch->Save();

		// Field tipodiscapacidad
		$this->tipodiscapacidad->AdvancedSearch->SearchValue = @$filter["x_tipodiscapacidad"];
		$this->tipodiscapacidad->AdvancedSearch->SearchOperator = @$filter["z_tipodiscapacidad"];
		$this->tipodiscapacidad->AdvancedSearch->SearchCondition = @$filter["v_tipodiscapacidad"];
		$this->tipodiscapacidad->AdvancedSearch->SearchValue2 = @$filter["y_tipodiscapacidad"];
		$this->tipodiscapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_tipodiscapacidad"];
		$this->tipodiscapacidad->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->id_departamento, $Default, FALSE); // id_departamento
		$this->BuildSearchSql($sWhere, $this->unidadeducativa, $Default, FALSE); // unidadeducativa
		$this->BuildSearchSql($sWhere, $this->apellidopaterno, $Default, FALSE); // apellidopaterno
		$this->BuildSearchSql($sWhere, $this->apellidomaterno, $Default, FALSE); // apellidomaterno
		$this->BuildSearchSql($sWhere, $this->nombres, $Default, FALSE); // nombres
		$this->BuildSearchSql($sWhere, $this->nrodiscapacidad, $Default, FALSE); // nrodiscapacidad
		$this->BuildSearchSql($sWhere, $this->ci, $Default, FALSE); // ci
		$this->BuildSearchSql($sWhere, $this->fechanacimiento, $Default, FALSE); // fechanacimiento
		$this->BuildSearchSql($sWhere, $this->sexo, $Default, FALSE); // sexo
		$this->BuildSearchSql($sWhere, $this->celular, $Default, FALSE); // celular
		$this->BuildSearchSql($sWhere, $this->materias, $Default, FALSE); // materias
		$this->BuildSearchSql($sWhere, $this->discapacidad, $Default, FALSE); // discapacidad
		$this->BuildSearchSql($sWhere, $this->tipodiscapacidad, $Default, FALSE); // tipodiscapacidad

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->id_departamento->AdvancedSearch->Save(); // id_departamento
			$this->unidadeducativa->AdvancedSearch->Save(); // unidadeducativa
			$this->apellidopaterno->AdvancedSearch->Save(); // apellidopaterno
			$this->apellidomaterno->AdvancedSearch->Save(); // apellidomaterno
			$this->nombres->AdvancedSearch->Save(); // nombres
			$this->nrodiscapacidad->AdvancedSearch->Save(); // nrodiscapacidad
			$this->ci->AdvancedSearch->Save(); // ci
			$this->fechanacimiento->AdvancedSearch->Save(); // fechanacimiento
			$this->sexo->AdvancedSearch->Save(); // sexo
			$this->celular->AdvancedSearch->Save(); // celular
			$this->materias->AdvancedSearch->Save(); // materias
			$this->discapacidad->AdvancedSearch->Save(); // discapacidad
			$this->tipodiscapacidad->AdvancedSearch->Save(); // tipodiscapacidad
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
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_departamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->unidadeducativa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidopaterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidomaterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nrodiscapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ci->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechanacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->celular->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->materias->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->discapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipodiscapacidad->AdvancedSearch->IssetSession())
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
		$this->id->AdvancedSearch->UnsetSession();
		$this->id_departamento->AdvancedSearch->UnsetSession();
		$this->unidadeducativa->AdvancedSearch->UnsetSession();
		$this->apellidopaterno->AdvancedSearch->UnsetSession();
		$this->apellidomaterno->AdvancedSearch->UnsetSession();
		$this->nombres->AdvancedSearch->UnsetSession();
		$this->nrodiscapacidad->AdvancedSearch->UnsetSession();
		$this->ci->AdvancedSearch->UnsetSession();
		$this->fechanacimiento->AdvancedSearch->UnsetSession();
		$this->sexo->AdvancedSearch->UnsetSession();
		$this->celular->AdvancedSearch->UnsetSession();
		$this->materias->AdvancedSearch->UnsetSession();
		$this->discapacidad->AdvancedSearch->UnsetSession();
		$this->tipodiscapacidad->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->id_departamento->AdvancedSearch->Load();
		$this->unidadeducativa->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fechanacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->celular->AdvancedSearch->Load();
		$this->materias->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->tipodiscapacidad->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id, $bCtrl); // id
			$this->UpdateSort($this->id_departamento, $bCtrl); // id_departamento
			$this->UpdateSort($this->unidadeducativa, $bCtrl); // unidadeducativa
			$this->UpdateSort($this->apellidopaterno, $bCtrl); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno, $bCtrl); // apellidomaterno
			$this->UpdateSort($this->nombres, $bCtrl); // nombres
			$this->UpdateSort($this->nrodiscapacidad, $bCtrl); // nrodiscapacidad
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->fechanacimiento, $bCtrl); // fechanacimiento
			$this->UpdateSort($this->sexo, $bCtrl); // sexo
			$this->UpdateSort($this->celular, $bCtrl); // celular
			$this->UpdateSort($this->materias, $bCtrl); // materias
			$this->UpdateSort($this->discapacidad, $bCtrl); // discapacidad
			$this->UpdateSort($this->tipodiscapacidad, $bCtrl); // tipodiscapacidad
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
				$this->id->setSort("");
				$this->id_departamento->setSort("");
				$this->unidadeducativa->setSort("");
				$this->apellidopaterno->setSort("");
				$this->apellidomaterno->setSort("");
				$this->nombres->setSort("");
				$this->nrodiscapacidad->setSort("");
				$this->ci->setSort("");
				$this->fechanacimiento->setSort("");
				$this->sexo->setSort("");
				$this->celular->setSort("");
				$this->materias->setSort("");
				$this->discapacidad->setSort("");
				$this->tipodiscapacidad->setSort("");
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
			$option->UseDropDownButton = FALSE;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdocentelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdocentelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdocentelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdocentelistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		// id

		$this->id->AdvancedSearch->SearchValue = @$_GET["x_id"];
		if ($this->id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// id_departamento
		$this->id_departamento->AdvancedSearch->SearchValue = @$_GET["x_id_departamento"];
		if ($this->id_departamento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_departamento->AdvancedSearch->SearchOperator = @$_GET["z_id_departamento"];

		// unidadeducativa
		$this->unidadeducativa->AdvancedSearch->SearchValue = @$_GET["x_unidadeducativa"];
		if ($this->unidadeducativa->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->unidadeducativa->AdvancedSearch->SearchOperator = @$_GET["z_unidadeducativa"];

		// apellidopaterno
		$this->apellidopaterno->AdvancedSearch->SearchValue = @$_GET["x_apellidopaterno"];
		if ($this->apellidopaterno->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->apellidopaterno->AdvancedSearch->SearchOperator = @$_GET["z_apellidopaterno"];

		// apellidomaterno
		$this->apellidomaterno->AdvancedSearch->SearchValue = @$_GET["x_apellidomaterno"];
		if ($this->apellidomaterno->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->apellidomaterno->AdvancedSearch->SearchOperator = @$_GET["z_apellidomaterno"];

		// nombres
		$this->nombres->AdvancedSearch->SearchValue = @$_GET["x_nombres"];
		if ($this->nombres->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombres->AdvancedSearch->SearchOperator = @$_GET["z_nombres"];

		// nrodiscapacidad
		$this->nrodiscapacidad->AdvancedSearch->SearchValue = @$_GET["x_nrodiscapacidad"];
		if ($this->nrodiscapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator = @$_GET["z_nrodiscapacidad"];

		// ci
		$this->ci->AdvancedSearch->SearchValue = @$_GET["x_ci"];
		if ($this->ci->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->ci->AdvancedSearch->SearchOperator = @$_GET["z_ci"];

		// fechanacimiento
		$this->fechanacimiento->AdvancedSearch->SearchValue = @$_GET["x_fechanacimiento"];
		if ($this->fechanacimiento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fechanacimiento->AdvancedSearch->SearchOperator = @$_GET["z_fechanacimiento"];

		// sexo
		$this->sexo->AdvancedSearch->SearchValue = @$_GET["x_sexo"];
		if ($this->sexo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sexo->AdvancedSearch->SearchOperator = @$_GET["z_sexo"];

		// celular
		$this->celular->AdvancedSearch->SearchValue = @$_GET["x_celular"];
		if ($this->celular->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->celular->AdvancedSearch->SearchOperator = @$_GET["z_celular"];

		// materias
		$this->materias->AdvancedSearch->SearchValue = @$_GET["x_materias"];
		if ($this->materias->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->materias->AdvancedSearch->SearchOperator = @$_GET["z_materias"];

		// discapacidad
		$this->discapacidad->AdvancedSearch->SearchValue = @$_GET["x_discapacidad"];
		if ($this->discapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->discapacidad->AdvancedSearch->SearchOperator = @$_GET["z_discapacidad"];

		// tipodiscapacidad
		$this->tipodiscapacidad->AdvancedSearch->SearchValue = @$_GET["x_tipodiscapacidad"];
		if ($this->tipodiscapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipodiscapacidad->AdvancedSearch->SearchOperator = @$_GET["z_tipodiscapacidad"];
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
		$this->id_departamento->setDbValue($row['id_departamento']);
		$this->unidadeducativa->setDbValue($row['unidadeducativa']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombres->setDbValue($row['nombres']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->celular->setDbValue($row['celular']);
		$this->materias->setDbValue($row['materias']);
		$this->discapacidad->setDbValue($row['discapacidad']);
		if (array_key_exists('EV__discapacidad', $rs->fields)) {
			$this->discapacidad->VirtualValue = $rs->fields('EV__discapacidad'); // Set up virtual field value
		} else {
			$this->discapacidad->VirtualValue = ""; // Clear value
		}
		$this->tipodiscapacidad->setDbValue($row['tipodiscapacidad']);
		if (array_key_exists('EV__tipodiscapacidad', $rs->fields)) {
			$this->tipodiscapacidad->VirtualValue = $rs->fields('EV__tipodiscapacidad'); // Set up virtual field value
		} else {
			$this->tipodiscapacidad->VirtualValue = ""; // Clear value
		}
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_departamento'] = NULL;
		$row['unidadeducativa'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombres'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['celular'] = NULL;
		$row['materias'] = NULL;
		$row['discapacidad'] = NULL;
		$row['tipodiscapacidad'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_departamento->DbValue = $row['id_departamento'];
		$this->unidadeducativa->DbValue = $row['unidadeducativa'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombres->DbValue = $row['nombres'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->celular->DbValue = $row['celular'];
		$this->materias->DbValue = $row['materias'];
		$this->discapacidad->DbValue = $row['discapacidad'];
		$this->tipodiscapacidad->DbValue = $row['tipodiscapacidad'];
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
		// id_departamento
		// unidadeducativa
		// apellidopaterno
		// apellidomaterno
		// nombres
		// nrodiscapacidad
		// ci
		// fechanacimiento
		// sexo
		// celular
		// materias
		// discapacidad
		// tipodiscapacidad
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

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

		// unidadeducativa
		if (strval($this->unidadeducativa->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->unidadeducativa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `codigo_sie` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->unidadeducativa->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`codigo_sie`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->unidadeducativa->ViewValue = $this->unidadeducativa->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->unidadeducativa->ViewValue = $this->unidadeducativa->CurrentValue;
			}
		} else {
			$this->unidadeducativa->ViewValue = NULL;
		}
		$this->unidadeducativa->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombres
		$this->nombres->ViewValue = $this->nombres->CurrentValue;
		$this->nombres->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fechanacimiento
		$this->fechanacimiento->ViewValue = $this->fechanacimiento->CurrentValue;
		$this->fechanacimiento->ViewValue = ew_FormatDateTime($this->fechanacimiento->ViewValue, 6);
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

		// materias
		$this->materias->ViewValue = $this->materias->CurrentValue;
		$this->materias->ViewCustomAttributes = "";

		// discapacidad
		if ($this->discapacidad->VirtualValue <> "") {
			$this->discapacidad->ViewValue = $this->discapacidad->VirtualValue;
		} else {
		if (strval($this->discapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
		$sWhereWrk = "";
		$this->discapacidad->LookupFilters = array("dx1" => '`nombre`');
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
		}
		$this->discapacidad->ViewCustomAttributes = "";

		// tipodiscapacidad
		if ($this->tipodiscapacidad->VirtualValue <> "") {
			$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->VirtualValue;
		} else {
		if (strval($this->tipodiscapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipodiscapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiscapacidad`";
		$sWhereWrk = "";
		$this->tipodiscapacidad->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->CurrentValue;
			}
		} else {
			$this->tipodiscapacidad->ViewValue = NULL;
		}
		}
		$this->tipodiscapacidad->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_departamento
			$this->id_departamento->LinkCustomAttributes = "";
			$this->id_departamento->HrefValue = "";
			$this->id_departamento->TooltipValue = "";

			// unidadeducativa
			$this->unidadeducativa->LinkCustomAttributes = "";
			$this->unidadeducativa->HrefValue = "";
			$this->unidadeducativa->TooltipValue = "";

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

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
			$this->nrodiscapacidad->TooltipValue = "";

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

			// materias
			$this->materias->LinkCustomAttributes = "";
			$this->materias->HrefValue = "";
			$this->materias->TooltipValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";
			$this->discapacidad->TooltipValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->LinkCustomAttributes = "";
			$this->tipodiscapacidad->HrefValue = "";
			$this->tipodiscapacidad->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// id_departamento
			$this->id_departamento->EditAttrs["class"] = "form-control";
			$this->id_departamento->EditCustomAttributes = "";
			if (trim(strval($this->id_departamento->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_departamento->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamento`";
			$sWhereWrk = "";
			$this->id_departamento->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_departamento, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_departamento->EditValue = $arwrk;

			// unidadeducativa
			$this->unidadeducativa->EditCustomAttributes = "";
			if (trim(strval($this->unidadeducativa->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->unidadeducativa->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `codigo_sie` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `unidadeducativa`";
			$sWhereWrk = "";
			$this->unidadeducativa->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`codigo_sie`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->unidadeducativa->AdvancedSearch->ViewValue = $this->unidadeducativa->DisplayValue($arwrk);
			} else {
				$this->unidadeducativa->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->unidadeducativa->EditValue = $arwrk;

			// apellidopaterno
			$this->apellidopaterno->EditAttrs["class"] = "form-control";
			$this->apellidopaterno->EditCustomAttributes = "";
			$this->apellidopaterno->EditValue = ew_HtmlEncode($this->apellidopaterno->AdvancedSearch->SearchValue);
			$this->apellidopaterno->PlaceHolder = ew_RemoveHtml($this->apellidopaterno->FldCaption());

			// apellidomaterno
			$this->apellidomaterno->EditAttrs["class"] = "form-control";
			$this->apellidomaterno->EditCustomAttributes = "";
			$this->apellidomaterno->EditValue = ew_HtmlEncode($this->apellidomaterno->AdvancedSearch->SearchValue);
			$this->apellidomaterno->PlaceHolder = ew_RemoveHtml($this->apellidomaterno->FldCaption());

			// nombres
			$this->nombres->EditAttrs["class"] = "form-control";
			$this->nombres->EditCustomAttributes = "";
			$this->nombres->EditValue = ew_HtmlEncode($this->nombres->AdvancedSearch->SearchValue);
			$this->nombres->PlaceHolder = ew_RemoveHtml($this->nombres->FldCaption());

			// nrodiscapacidad
			$this->nrodiscapacidad->EditAttrs["class"] = "form-control";
			$this->nrodiscapacidad->EditCustomAttributes = "";
			$this->nrodiscapacidad->EditValue = ew_HtmlEncode($this->nrodiscapacidad->AdvancedSearch->SearchValue);
			$this->nrodiscapacidad->PlaceHolder = ew_RemoveHtml($this->nrodiscapacidad->FldCaption());

			// ci
			$this->ci->EditAttrs["class"] = "form-control";
			$this->ci->EditCustomAttributes = "";
			$this->ci->EditValue = ew_HtmlEncode($this->ci->AdvancedSearch->SearchValue);
			$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

			// fechanacimiento
			$this->fechanacimiento->EditAttrs["class"] = "form-control";
			$this->fechanacimiento->EditCustomAttributes = "";
			$this->fechanacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fechanacimiento->AdvancedSearch->SearchValue, 6), 6));
			$this->fechanacimiento->PlaceHolder = ew_RemoveHtml($this->fechanacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// celular
			$this->celular->EditAttrs["class"] = "form-control";
			$this->celular->EditCustomAttributes = "";
			$this->celular->EditValue = ew_HtmlEncode($this->celular->AdvancedSearch->SearchValue);
			$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

			// materias
			$this->materias->EditAttrs["class"] = "form-control";
			$this->materias->EditCustomAttributes = "";
			$this->materias->EditValue = ew_HtmlEncode($this->materias->AdvancedSearch->SearchValue);
			$this->materias->PlaceHolder = ew_RemoveHtml($this->materias->FldCaption());

			// discapacidad
			$this->discapacidad->EditAttrs["class"] = "form-control";
			$this->discapacidad->EditCustomAttributes = "";
			$this->discapacidad->EditValue = ew_HtmlEncode($this->discapacidad->AdvancedSearch->SearchValue);
			$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

			// tipodiscapacidad
			$this->tipodiscapacidad->EditAttrs["class"] = "form-control";
			$this->tipodiscapacidad->EditCustomAttributes = "";
			$this->tipodiscapacidad->EditValue = ew_HtmlEncode($this->tipodiscapacidad->AdvancedSearch->SearchValue);
			$this->tipodiscapacidad->PlaceHolder = ew_RemoveHtml($this->tipodiscapacidad->FldCaption());
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
		$this->id->AdvancedSearch->Load();
		$this->id_departamento->AdvancedSearch->Load();
		$this->unidadeducativa->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fechanacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->celular->AdvancedSearch->Load();
		$this->materias->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->tipodiscapacidad->AdvancedSearch->Load();
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
		case "x_id_departamento":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_departamento, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_unidadeducativa":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `codigo_sie` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`codigo_sie`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($docente_list)) $docente_list = new cdocente_list();

// Page init
$docente_list->Page_Init();

// Page main
$docente_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$docente_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdocentelist = new ew_Form("fdocentelist", "list");
fdocentelist.FormKeyCountName = '<?php echo $docente_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdocentelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdocentelist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdocentelist.Lists["x_id_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fdocentelist.Lists["x_id_departamento"].Data = "<?php echo $docente_list->id_departamento->LookupFilterQuery(FALSE, "list") ?>";
fdocentelist.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_codigo_sie","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fdocentelist.Lists["x_unidadeducativa"].Data = "<?php echo $docente_list->unidadeducativa->LookupFilterQuery(FALSE, "list") ?>";
fdocentelist.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdocentelist.Lists["x_sexo"].Options = <?php echo json_encode($docente_list->sexo->Options()) ?>;
fdocentelist.Lists["x_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fdocentelist.Lists["x_discapacidad"].Data = "<?php echo $docente_list->discapacidad->LookupFilterQuery(FALSE, "list") ?>";
fdocentelist.Lists["x_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
fdocentelist.Lists["x_tipodiscapacidad"].Data = "<?php echo $docente_list->tipodiscapacidad->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fdocentelistsrch = new ew_Form("fdocentelistsrch");

// Validate function for search
fdocentelistsrch.Validate = function(fobj) {
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
fdocentelistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdocentelistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdocentelistsrch.Lists["x_id_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fdocentelistsrch.Lists["x_id_departamento"].Data = "<?php echo $docente_list->id_departamento->LookupFilterQuery(FALSE, "extbs") ?>";
fdocentelistsrch.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_codigo_sie","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fdocentelistsrch.Lists["x_unidadeducativa"].Data = "<?php echo $docente_list->unidadeducativa->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($docente_list->TotalRecs > 0 && $docente_list->ExportOptions->Visible()) { ?>
<?php $docente_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($docente_list->SearchOptions->Visible()) { ?>
<?php $docente_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($docente_list->FilterOptions->Visible()) { ?>
<?php $docente_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $docente_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($docente_list->TotalRecs <= 0)
			$docente_list->TotalRecs = $docente->ListRecordCount();
	} else {
		if (!$docente_list->Recordset && ($docente_list->Recordset = $docente_list->LoadRecordset()))
			$docente_list->TotalRecs = $docente_list->Recordset->RecordCount();
	}
	$docente_list->StartRec = 1;
	if ($docente_list->DisplayRecs <= 0 || ($docente->Export <> "" && $docente->ExportAll)) // Display all records
		$docente_list->DisplayRecs = $docente_list->TotalRecs;
	if (!($docente->Export <> "" && $docente->ExportAll))
		$docente_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$docente_list->Recordset = $docente_list->LoadRecordset($docente_list->StartRec-1, $docente_list->DisplayRecs);

	// Set no record found message
	if ($docente->CurrentAction == "" && $docente_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$docente_list->setWarningMessage(ew_DeniedMsg());
		if ($docente_list->SearchWhere == "0=101")
			$docente_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$docente_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$docente_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($docente->Export == "" && $docente->CurrentAction == "") { ?>
<form name="fdocentelistsrch" id="fdocentelistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($docente_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fdocentelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="docente">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$docente_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$docente->RowType = EW_ROWTYPE_SEARCH;

// Render row
$docente->ResetAttrs();
$docente_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($docente->id_departamento->Visible) { // id_departamento ?>
	<div id="xsc_id_departamento" class="ewCell form-group">
		<label for="x_id_departamento" class="ewSearchCaption ewLabel"><?php echo $docente->id_departamento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_id_departamento" id="z_id_departamento" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="docente" data-field="x_id_departamento" data-value-separator="<?php echo $docente->id_departamento->DisplayValueSeparatorAttribute() ?>" id="x_id_departamento" name="x_id_departamento"<?php echo $docente->id_departamento->EditAttributes() ?>>
<?php echo $docente->id_departamento->SelectOptionListHtml("x_id_departamento") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($docente->unidadeducativa->Visible) { // unidadeducativa ?>
	<div id="xsc_unidadeducativa" class="ewCell form-group">
		<label for="x_unidadeducativa" class="ewSearchCaption ewLabel"><?php echo $docente->unidadeducativa->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_unidadeducativa" id="z_unidadeducativa" value="LIKE"></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_unidadeducativa"><?php echo (strval($docente->unidadeducativa->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $docente->unidadeducativa->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($docente->unidadeducativa->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_unidadeducativa',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($docente->unidadeducativa->ReadOnly || $docente->unidadeducativa->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="docente" data-field="x_unidadeducativa" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $docente->unidadeducativa->DisplayValueSeparatorAttribute() ?>" name="x_unidadeducativa" id="x_unidadeducativa" value="<?php echo $docente->unidadeducativa->AdvancedSearch->SearchValue ?>"<?php echo $docente->unidadeducativa->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($docente->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="xsc_apellidopaterno" class="ewCell form-group">
		<label for="x_apellidopaterno" class="ewSearchCaption ewLabel"><?php echo $docente->apellidopaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidopaterno" id="z_apellidopaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="docente" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $docente->apellidopaterno->EditValue ?>"<?php echo $docente->apellidopaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($docente->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="xsc_apellidomaterno" class="ewCell form-group">
		<label for="x_apellidomaterno" class="ewSearchCaption ewLabel"><?php echo $docente->apellidomaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidomaterno" id="z_apellidomaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="docente" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $docente->apellidomaterno->EditValue ?>"<?php echo $docente->apellidomaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($docente->nombres->Visible) { // nombres ?>
	<div id="xsc_nombres" class="ewCell form-group">
		<label for="x_nombres" class="ewSearchCaption ewLabel"><?php echo $docente->nombres->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombres" id="z_nombres" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="docente" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->nombres->getPlaceHolder()) ?>" value="<?php echo $docente->nombres->EditValue ?>"<?php echo $docente->nombres->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($docente->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="xsc_nrodiscapacidad" class="ewCell form-group">
		<label for="x_nrodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $docente->nrodiscapacidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nrodiscapacidad" id="z_nrodiscapacidad" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="docente" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($docente->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $docente->nrodiscapacidad->EditValue ?>"<?php echo $docente->nrodiscapacidad->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($docente->ci->Visible) { // ci ?>
	<div id="xsc_ci" class="ewCell form-group">
		<label for="x_ci" class="ewSearchCaption ewLabel"><?php echo $docente->ci->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ci" id="z_ci" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="docente" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($docente->ci->getPlaceHolder()) ?>" value="<?php echo $docente->ci->EditValue ?>"<?php echo $docente->ci->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
<?php if ($docente->celular->Visible) { // celular ?>
	<div id="xsc_celular" class="ewCell form-group">
		<label for="x_celular" class="ewSearchCaption ewLabel"><?php echo $docente->celular->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_celular" id="z_celular" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="docente" data-field="x_celular" name="x_celular" id="x_celular" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($docente->celular->getPlaceHolder()) ?>" value="<?php echo $docente->celular->EditValue ?>"<?php echo $docente->celular->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_9" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $docente_list->ShowPageHeader(); ?>
<?php
$docente_list->ShowMessage();
?>
<?php if ($docente_list->TotalRecs > 0 || $docente->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($docente_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> docente">
<div class="box-header ewGridUpperPanel">
<?php if ($docente->CurrentAction <> "gridadd" && $docente->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($docente_list->Pager)) $docente_list->Pager = new cPrevNextPager($docente_list->StartRec, $docente_list->DisplayRecs, $docente_list->TotalRecs, $docente_list->AutoHidePager) ?>
<?php if ($docente_list->Pager->RecordCount > 0 && $docente_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($docente_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $docente_list->PageUrl() ?>start=<?php echo $docente_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($docente_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $docente_list->PageUrl() ?>start=<?php echo $docente_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $docente_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($docente_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $docente_list->PageUrl() ?>start=<?php echo $docente_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($docente_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $docente_list->PageUrl() ?>start=<?php echo $docente_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $docente_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($docente_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $docente_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $docente_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $docente_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($docente_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fdocentelist" id="fdocentelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($docente_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $docente_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="docente">
<div id="gmp_docente" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($docente_list->TotalRecs > 0 || $docente->CurrentAction == "gridedit") { ?>
<table id="tbl_docentelist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$docente_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$docente_list->RenderListOptions();

// Render list options (header, left)
$docente_list->ListOptions->Render("header", "left");
?>
<?php if ($docente->id->Visible) { // id ?>
	<?php if ($docente->SortUrl($docente->id) == "") { ?>
		<th data-name="id" class="<?php echo $docente->id->HeaderCellClass() ?>"><div id="elh_docente_id" class="docente_id"><div class="ewTableHeaderCaption"><?php echo $docente->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $docente->id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->id) ?>',2);"><div id="elh_docente_id" class="docente_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->id_departamento->Visible) { // id_departamento ?>
	<?php if ($docente->SortUrl($docente->id_departamento) == "") { ?>
		<th data-name="id_departamento" class="<?php echo $docente->id_departamento->HeaderCellClass() ?>"><div id="elh_docente_id_departamento" class="docente_id_departamento"><div class="ewTableHeaderCaption"><?php echo $docente->id_departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_departamento" class="<?php echo $docente->id_departamento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->id_departamento) ?>',2);"><div id="elh_docente_id_departamento" class="docente_id_departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->id_departamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->id_departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->id_departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->unidadeducativa->Visible) { // unidadeducativa ?>
	<?php if ($docente->SortUrl($docente->unidadeducativa) == "") { ?>
		<th data-name="unidadeducativa" class="<?php echo $docente->unidadeducativa->HeaderCellClass() ?>"><div id="elh_docente_unidadeducativa" class="docente_unidadeducativa"><div class="ewTableHeaderCaption"><?php echo $docente->unidadeducativa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="unidadeducativa" class="<?php echo $docente->unidadeducativa->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->unidadeducativa) ?>',2);"><div id="elh_docente_unidadeducativa" class="docente_unidadeducativa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->unidadeducativa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->unidadeducativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->unidadeducativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->apellidopaterno->Visible) { // apellidopaterno ?>
	<?php if ($docente->SortUrl($docente->apellidopaterno) == "") { ?>
		<th data-name="apellidopaterno" class="<?php echo $docente->apellidopaterno->HeaderCellClass() ?>"><div id="elh_docente_apellidopaterno" class="docente_apellidopaterno"><div class="ewTableHeaderCaption"><?php echo $docente->apellidopaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidopaterno" class="<?php echo $docente->apellidopaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->apellidopaterno) ?>',2);"><div id="elh_docente_apellidopaterno" class="docente_apellidopaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->apellidopaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->apellidomaterno->Visible) { // apellidomaterno ?>
	<?php if ($docente->SortUrl($docente->apellidomaterno) == "") { ?>
		<th data-name="apellidomaterno" class="<?php echo $docente->apellidomaterno->HeaderCellClass() ?>"><div id="elh_docente_apellidomaterno" class="docente_apellidomaterno"><div class="ewTableHeaderCaption"><?php echo $docente->apellidomaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidomaterno" class="<?php echo $docente->apellidomaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->apellidomaterno) ?>',2);"><div id="elh_docente_apellidomaterno" class="docente_apellidomaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->apellidomaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->nombres->Visible) { // nombres ?>
	<?php if ($docente->SortUrl($docente->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $docente->nombres->HeaderCellClass() ?>"><div id="elh_docente_nombres" class="docente_nombres"><div class="ewTableHeaderCaption"><?php echo $docente->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $docente->nombres->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->nombres) ?>',2);"><div id="elh_docente_nombres" class="docente_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<?php if ($docente->SortUrl($docente->nrodiscapacidad) == "") { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $docente->nrodiscapacidad->HeaderCellClass() ?>"><div id="elh_docente_nrodiscapacidad" class="docente_nrodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $docente->nrodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $docente->nrodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->nrodiscapacidad) ?>',2);"><div id="elh_docente_nrodiscapacidad" class="docente_nrodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->nrodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->ci->Visible) { // ci ?>
	<?php if ($docente->SortUrl($docente->ci) == "") { ?>
		<th data-name="ci" class="<?php echo $docente->ci->HeaderCellClass() ?>"><div id="elh_docente_ci" class="docente_ci"><div class="ewTableHeaderCaption"><?php echo $docente->ci->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ci" class="<?php echo $docente->ci->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->ci) ?>',2);"><div id="elh_docente_ci" class="docente_ci">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->ci->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->fechanacimiento->Visible) { // fechanacimiento ?>
	<?php if ($docente->SortUrl($docente->fechanacimiento) == "") { ?>
		<th data-name="fechanacimiento" class="<?php echo $docente->fechanacimiento->HeaderCellClass() ?>"><div id="elh_docente_fechanacimiento" class="docente_fechanacimiento"><div class="ewTableHeaderCaption"><?php echo $docente->fechanacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechanacimiento" class="<?php echo $docente->fechanacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->fechanacimiento) ?>',2);"><div id="elh_docente_fechanacimiento" class="docente_fechanacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->fechanacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->fechanacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->fechanacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->sexo->Visible) { // sexo ?>
	<?php if ($docente->SortUrl($docente->sexo) == "") { ?>
		<th data-name="sexo" class="<?php echo $docente->sexo->HeaderCellClass() ?>"><div id="elh_docente_sexo" class="docente_sexo"><div class="ewTableHeaderCaption"><?php echo $docente->sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sexo" class="<?php echo $docente->sexo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->sexo) ?>',2);"><div id="elh_docente_sexo" class="docente_sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->celular->Visible) { // celular ?>
	<?php if ($docente->SortUrl($docente->celular) == "") { ?>
		<th data-name="celular" class="<?php echo $docente->celular->HeaderCellClass() ?>"><div id="elh_docente_celular" class="docente_celular"><div class="ewTableHeaderCaption"><?php echo $docente->celular->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="celular" class="<?php echo $docente->celular->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->celular) ?>',2);"><div id="elh_docente_celular" class="docente_celular">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->celular->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->celular->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->celular->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->materias->Visible) { // materias ?>
	<?php if ($docente->SortUrl($docente->materias) == "") { ?>
		<th data-name="materias" class="<?php echo $docente->materias->HeaderCellClass() ?>"><div id="elh_docente_materias" class="docente_materias"><div class="ewTableHeaderCaption"><?php echo $docente->materias->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="materias" class="<?php echo $docente->materias->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->materias) ?>',2);"><div id="elh_docente_materias" class="docente_materias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->materias->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->materias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->materias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->discapacidad->Visible) { // discapacidad ?>
	<?php if ($docente->SortUrl($docente->discapacidad) == "") { ?>
		<th data-name="discapacidad" class="<?php echo $docente->discapacidad->HeaderCellClass() ?>"><div id="elh_docente_discapacidad" class="docente_discapacidad"><div class="ewTableHeaderCaption"><?php echo $docente->discapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="discapacidad" class="<?php echo $docente->discapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->discapacidad) ?>',2);"><div id="elh_docente_discapacidad" class="docente_discapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->discapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($docente->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
	<?php if ($docente->SortUrl($docente->tipodiscapacidad) == "") { ?>
		<th data-name="tipodiscapacidad" class="<?php echo $docente->tipodiscapacidad->HeaderCellClass() ?>"><div id="elh_docente_tipodiscapacidad" class="docente_tipodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $docente->tipodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipodiscapacidad" class="<?php echo $docente->tipodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $docente->SortUrl($docente->tipodiscapacidad) ?>',2);"><div id="elh_docente_tipodiscapacidad" class="docente_tipodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $docente->tipodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($docente->tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($docente->tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$docente_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($docente->ExportAll && $docente->Export <> "") {
	$docente_list->StopRec = $docente_list->TotalRecs;
} else {

	// Set the last record to display
	if ($docente_list->TotalRecs > $docente_list->StartRec + $docente_list->DisplayRecs - 1)
		$docente_list->StopRec = $docente_list->StartRec + $docente_list->DisplayRecs - 1;
	else
		$docente_list->StopRec = $docente_list->TotalRecs;
}
$docente_list->RecCnt = $docente_list->StartRec - 1;
if ($docente_list->Recordset && !$docente_list->Recordset->EOF) {
	$docente_list->Recordset->MoveFirst();
	$bSelectLimit = $docente_list->UseSelectLimit;
	if (!$bSelectLimit && $docente_list->StartRec > 1)
		$docente_list->Recordset->Move($docente_list->StartRec - 1);
} elseif (!$docente->AllowAddDeleteRow && $docente_list->StopRec == 0) {
	$docente_list->StopRec = $docente->GridAddRowCount;
}

// Initialize aggregate
$docente->RowType = EW_ROWTYPE_AGGREGATEINIT;
$docente->ResetAttrs();
$docente_list->RenderRow();
while ($docente_list->RecCnt < $docente_list->StopRec) {
	$docente_list->RecCnt++;
	if (intval($docente_list->RecCnt) >= intval($docente_list->StartRec)) {
		$docente_list->RowCnt++;

		// Set up key count
		$docente_list->KeyCount = $docente_list->RowIndex;

		// Init row class and style
		$docente->ResetAttrs();
		$docente->CssClass = "";
		if ($docente->CurrentAction == "gridadd") {
		} else {
			$docente_list->LoadRowValues($docente_list->Recordset); // Load row values
		}
		$docente->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$docente->RowAttrs = array_merge($docente->RowAttrs, array('data-rowindex'=>$docente_list->RowCnt, 'id'=>'r' . $docente_list->RowCnt . '_docente', 'data-rowtype'=>$docente->RowType));

		// Render row
		$docente_list->RenderRow();

		// Render list options
		$docente_list->RenderListOptions();
?>
	<tr<?php echo $docente->RowAttributes() ?>>
<?php

// Render list options (body, left)
$docente_list->ListOptions->Render("body", "left", $docente_list->RowCnt);
?>
	<?php if ($docente->id->Visible) { // id ?>
		<td data-name="id"<?php echo $docente->id->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_id" class="docente_id">
<span<?php echo $docente->id->ViewAttributes() ?>>
<?php echo $docente->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->id_departamento->Visible) { // id_departamento ?>
		<td data-name="id_departamento"<?php echo $docente->id_departamento->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_id_departamento" class="docente_id_departamento">
<span<?php echo $docente->id_departamento->ViewAttributes() ?>>
<?php echo $docente->id_departamento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->unidadeducativa->Visible) { // unidadeducativa ?>
		<td data-name="unidadeducativa"<?php echo $docente->unidadeducativa->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_unidadeducativa" class="docente_unidadeducativa">
<span<?php echo $docente->unidadeducativa->ViewAttributes() ?>>
<?php echo $docente->unidadeducativa->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->apellidopaterno->Visible) { // apellidopaterno ?>
		<td data-name="apellidopaterno"<?php echo $docente->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_apellidopaterno" class="docente_apellidopaterno">
<span<?php echo $docente->apellidopaterno->ViewAttributes() ?>>
<?php echo $docente->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->apellidomaterno->Visible) { // apellidomaterno ?>
		<td data-name="apellidomaterno"<?php echo $docente->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_apellidomaterno" class="docente_apellidomaterno">
<span<?php echo $docente->apellidomaterno->ViewAttributes() ?>>
<?php echo $docente->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $docente->nombres->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_nombres" class="docente_nombres">
<span<?php echo $docente->nombres->ViewAttributes() ?>>
<?php echo $docente->nombres->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td data-name="nrodiscapacidad"<?php echo $docente->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_nrodiscapacidad" class="docente_nrodiscapacidad">
<span<?php echo $docente->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $docente->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->ci->Visible) { // ci ?>
		<td data-name="ci"<?php echo $docente->ci->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_ci" class="docente_ci">
<span<?php echo $docente->ci->ViewAttributes() ?>>
<?php echo $docente->ci->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->fechanacimiento->Visible) { // fechanacimiento ?>
		<td data-name="fechanacimiento"<?php echo $docente->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_fechanacimiento" class="docente_fechanacimiento">
<span<?php echo $docente->fechanacimiento->ViewAttributes() ?>>
<?php echo $docente->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->sexo->Visible) { // sexo ?>
		<td data-name="sexo"<?php echo $docente->sexo->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_sexo" class="docente_sexo">
<span<?php echo $docente->sexo->ViewAttributes() ?>>
<?php echo $docente->sexo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->celular->Visible) { // celular ?>
		<td data-name="celular"<?php echo $docente->celular->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_celular" class="docente_celular">
<span<?php echo $docente->celular->ViewAttributes() ?>>
<?php echo $docente->celular->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->materias->Visible) { // materias ?>
		<td data-name="materias"<?php echo $docente->materias->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_materias" class="docente_materias">
<span<?php echo $docente->materias->ViewAttributes() ?>>
<?php echo $docente->materias->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->discapacidad->Visible) { // discapacidad ?>
		<td data-name="discapacidad"<?php echo $docente->discapacidad->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_discapacidad" class="docente_discapacidad">
<span<?php echo $docente->discapacidad->ViewAttributes() ?>>
<?php echo $docente->discapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($docente->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
		<td data-name="tipodiscapacidad"<?php echo $docente->tipodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $docente_list->RowCnt ?>_docente_tipodiscapacidad" class="docente_tipodiscapacidad">
<span<?php echo $docente->tipodiscapacidad->ViewAttributes() ?>>
<?php echo $docente->tipodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$docente_list->ListOptions->Render("body", "right", $docente_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($docente->CurrentAction <> "gridadd")
		$docente_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($docente->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($docente_list->Recordset)
	$docente_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($docente->CurrentAction <> "gridadd" && $docente->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($docente_list->Pager)) $docente_list->Pager = new cPrevNextPager($docente_list->StartRec, $docente_list->DisplayRecs, $docente_list->TotalRecs, $docente_list->AutoHidePager) ?>
<?php if ($docente_list->Pager->RecordCount > 0 && $docente_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($docente_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $docente_list->PageUrl() ?>start=<?php echo $docente_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($docente_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $docente_list->PageUrl() ?>start=<?php echo $docente_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $docente_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($docente_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $docente_list->PageUrl() ?>start=<?php echo $docente_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($docente_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $docente_list->PageUrl() ?>start=<?php echo $docente_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $docente_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($docente_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $docente_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $docente_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $docente_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($docente_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($docente_list->TotalRecs == 0 && $docente->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($docente_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fdocentelistsrch.FilterList = <?php echo $docente_list->GetFilterList() ?>;
fdocentelistsrch.Init();
fdocentelist.Init();
</script>
<?php
$docente_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$docente_list->Page_Terminate();
?>
