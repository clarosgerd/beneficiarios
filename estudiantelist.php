<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "estudianteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$estudiante_list = NULL; // Initialize page object first

class cestudiante_list extends cestudiante {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'estudiante';

	// Page object name
	var $PageObjName = 'estudiante_list';

	// Grid form hidden field names
	var $FormName = 'festudiantelist';
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

		// Table object (estudiante)
		if (!isset($GLOBALS["estudiante"]) || get_class($GLOBALS["estudiante"]) == "cestudiante") {
			$GLOBALS["estudiante"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["estudiante"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "estudianteadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "estudiantedelete.php";
		$this->MultiUpdateUrl = "estudianteupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'estudiante', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption festudiantelistsrch";

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
		$this->codigorude->SetVisibility();
		$this->codigorude_es->SetVisibility();
		$this->departamento->SetVisibility();
		$this->municipio->SetVisibility();
		$this->provincisa->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->curso->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapacidad->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->fecha->SetVisibility();

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
		global $EW_EXPORT, $estudiante;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($estudiante);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "festudiantelistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->codigorude->AdvancedSearch->ToJson(), ","); // Field codigorude
		$sFilterList = ew_Concat($sFilterList, $this->codigorude_es->AdvancedSearch->ToJson(), ","); // Field codigorude_es
		$sFilterList = ew_Concat($sFilterList, $this->departamento->AdvancedSearch->ToJson(), ","); // Field departamento
		$sFilterList = ew_Concat($sFilterList, $this->municipio->AdvancedSearch->ToJson(), ","); // Field municipio
		$sFilterList = ew_Concat($sFilterList, $this->provincisa->AdvancedSearch->ToJson(), ","); // Field provincisa
		$sFilterList = ew_Concat($sFilterList, $this->unidadeducativa->AdvancedSearch->ToJson(), ","); // Field unidadeducativa
		$sFilterList = ew_Concat($sFilterList, $this->apellidopaterno->AdvancedSearch->ToJson(), ","); // Field apellidopaterno
		$sFilterList = ew_Concat($sFilterList, $this->apellidomaterno->AdvancedSearch->ToJson(), ","); // Field apellidomaterno
		$sFilterList = ew_Concat($sFilterList, $this->nombres->AdvancedSearch->ToJson(), ","); // Field nombres
		$sFilterList = ew_Concat($sFilterList, $this->nrodiscapacidad->AdvancedSearch->ToJson(), ","); // Field nrodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->ci->AdvancedSearch->ToJson(), ","); // Field ci
		$sFilterList = ew_Concat($sFilterList, $this->fechanacimiento->AdvancedSearch->ToJson(), ","); // Field fechanacimiento
		$sFilterList = ew_Concat($sFilterList, $this->sexo->AdvancedSearch->ToJson(), ","); // Field sexo
		$sFilterList = ew_Concat($sFilterList, $this->curso->AdvancedSearch->ToJson(), ","); // Field curso
		$sFilterList = ew_Concat($sFilterList, $this->discapacidad->AdvancedSearch->ToJson(), ","); // Field discapacidad
		$sFilterList = ew_Concat($sFilterList, $this->tipodiscapacidad->AdvancedSearch->ToJson(), ","); // Field tipodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJson(), ","); // Field observaciones
		$sFilterList = ew_Concat($sFilterList, $this->id_centro->AdvancedSearch->ToJson(), ","); // Field id_centro
		$sFilterList = ew_Concat($sFilterList, $this->gestion->AdvancedSearch->ToJson(), ","); // Field gestion
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJson(), ","); // Field fecha
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "festudiantelistsrch", $filters);

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

		// Field codigorude
		$this->codigorude->AdvancedSearch->SearchValue = @$filter["x_codigorude"];
		$this->codigorude->AdvancedSearch->SearchOperator = @$filter["z_codigorude"];
		$this->codigorude->AdvancedSearch->SearchCondition = @$filter["v_codigorude"];
		$this->codigorude->AdvancedSearch->SearchValue2 = @$filter["y_codigorude"];
		$this->codigorude->AdvancedSearch->SearchOperator2 = @$filter["w_codigorude"];
		$this->codigorude->AdvancedSearch->Save();

		// Field codigorude_es
		$this->codigorude_es->AdvancedSearch->SearchValue = @$filter["x_codigorude_es"];
		$this->codigorude_es->AdvancedSearch->SearchOperator = @$filter["z_codigorude_es"];
		$this->codigorude_es->AdvancedSearch->SearchCondition = @$filter["v_codigorude_es"];
		$this->codigorude_es->AdvancedSearch->SearchValue2 = @$filter["y_codigorude_es"];
		$this->codigorude_es->AdvancedSearch->SearchOperator2 = @$filter["w_codigorude_es"];
		$this->codigorude_es->AdvancedSearch->Save();

		// Field departamento
		$this->departamento->AdvancedSearch->SearchValue = @$filter["x_departamento"];
		$this->departamento->AdvancedSearch->SearchOperator = @$filter["z_departamento"];
		$this->departamento->AdvancedSearch->SearchCondition = @$filter["v_departamento"];
		$this->departamento->AdvancedSearch->SearchValue2 = @$filter["y_departamento"];
		$this->departamento->AdvancedSearch->SearchOperator2 = @$filter["w_departamento"];
		$this->departamento->AdvancedSearch->Save();

		// Field municipio
		$this->municipio->AdvancedSearch->SearchValue = @$filter["x_municipio"];
		$this->municipio->AdvancedSearch->SearchOperator = @$filter["z_municipio"];
		$this->municipio->AdvancedSearch->SearchCondition = @$filter["v_municipio"];
		$this->municipio->AdvancedSearch->SearchValue2 = @$filter["y_municipio"];
		$this->municipio->AdvancedSearch->SearchOperator2 = @$filter["w_municipio"];
		$this->municipio->AdvancedSearch->Save();

		// Field provincisa
		$this->provincisa->AdvancedSearch->SearchValue = @$filter["x_provincisa"];
		$this->provincisa->AdvancedSearch->SearchOperator = @$filter["z_provincisa"];
		$this->provincisa->AdvancedSearch->SearchCondition = @$filter["v_provincisa"];
		$this->provincisa->AdvancedSearch->SearchValue2 = @$filter["y_provincisa"];
		$this->provincisa->AdvancedSearch->SearchOperator2 = @$filter["w_provincisa"];
		$this->provincisa->AdvancedSearch->Save();

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

		// Field curso
		$this->curso->AdvancedSearch->SearchValue = @$filter["x_curso"];
		$this->curso->AdvancedSearch->SearchOperator = @$filter["z_curso"];
		$this->curso->AdvancedSearch->SearchCondition = @$filter["v_curso"];
		$this->curso->AdvancedSearch->SearchValue2 = @$filter["y_curso"];
		$this->curso->AdvancedSearch->SearchOperator2 = @$filter["w_curso"];
		$this->curso->AdvancedSearch->Save();

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

		// Field observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$filter["x_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator = @$filter["z_observaciones"];
		$this->observaciones->AdvancedSearch->SearchCondition = @$filter["v_observaciones"];
		$this->observaciones->AdvancedSearch->SearchValue2 = @$filter["y_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones"];
		$this->observaciones->AdvancedSearch->Save();

		// Field id_centro
		$this->id_centro->AdvancedSearch->SearchValue = @$filter["x_id_centro"];
		$this->id_centro->AdvancedSearch->SearchOperator = @$filter["z_id_centro"];
		$this->id_centro->AdvancedSearch->SearchCondition = @$filter["v_id_centro"];
		$this->id_centro->AdvancedSearch->SearchValue2 = @$filter["y_id_centro"];
		$this->id_centro->AdvancedSearch->SearchOperator2 = @$filter["w_id_centro"];
		$this->id_centro->AdvancedSearch->Save();

		// Field gestion
		$this->gestion->AdvancedSearch->SearchValue = @$filter["x_gestion"];
		$this->gestion->AdvancedSearch->SearchOperator = @$filter["z_gestion"];
		$this->gestion->AdvancedSearch->SearchCondition = @$filter["v_gestion"];
		$this->gestion->AdvancedSearch->SearchValue2 = @$filter["y_gestion"];
		$this->gestion->AdvancedSearch->SearchOperator2 = @$filter["w_gestion"];
		$this->gestion->AdvancedSearch->Save();

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->codigorude, $Default, FALSE); // codigorude
		$this->BuildSearchSql($sWhere, $this->codigorude_es, $Default, FALSE); // codigorude_es
		$this->BuildSearchSql($sWhere, $this->departamento, $Default, FALSE); // departamento
		$this->BuildSearchSql($sWhere, $this->municipio, $Default, FALSE); // municipio
		$this->BuildSearchSql($sWhere, $this->provincisa, $Default, FALSE); // provincisa
		$this->BuildSearchSql($sWhere, $this->unidadeducativa, $Default, FALSE); // unidadeducativa
		$this->BuildSearchSql($sWhere, $this->apellidopaterno, $Default, FALSE); // apellidopaterno
		$this->BuildSearchSql($sWhere, $this->apellidomaterno, $Default, FALSE); // apellidomaterno
		$this->BuildSearchSql($sWhere, $this->nombres, $Default, FALSE); // nombres
		$this->BuildSearchSql($sWhere, $this->nrodiscapacidad, $Default, FALSE); // nrodiscapacidad
		$this->BuildSearchSql($sWhere, $this->ci, $Default, FALSE); // ci
		$this->BuildSearchSql($sWhere, $this->fechanacimiento, $Default, FALSE); // fechanacimiento
		$this->BuildSearchSql($sWhere, $this->sexo, $Default, FALSE); // sexo
		$this->BuildSearchSql($sWhere, $this->curso, $Default, FALSE); // curso
		$this->BuildSearchSql($sWhere, $this->discapacidad, $Default, FALSE); // discapacidad
		$this->BuildSearchSql($sWhere, $this->tipodiscapacidad, $Default, FALSE); // tipodiscapacidad
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->id_centro, $Default, FALSE); // id_centro
		$this->BuildSearchSql($sWhere, $this->gestion, $Default, FALSE); // gestion
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->codigorude->AdvancedSearch->Save(); // codigorude
			$this->codigorude_es->AdvancedSearch->Save(); // codigorude_es
			$this->departamento->AdvancedSearch->Save(); // departamento
			$this->municipio->AdvancedSearch->Save(); // municipio
			$this->provincisa->AdvancedSearch->Save(); // provincisa
			$this->unidadeducativa->AdvancedSearch->Save(); // unidadeducativa
			$this->apellidopaterno->AdvancedSearch->Save(); // apellidopaterno
			$this->apellidomaterno->AdvancedSearch->Save(); // apellidomaterno
			$this->nombres->AdvancedSearch->Save(); // nombres
			$this->nrodiscapacidad->AdvancedSearch->Save(); // nrodiscapacidad
			$this->ci->AdvancedSearch->Save(); // ci
			$this->fechanacimiento->AdvancedSearch->Save(); // fechanacimiento
			$this->sexo->AdvancedSearch->Save(); // sexo
			$this->curso->AdvancedSearch->Save(); // curso
			$this->discapacidad->AdvancedSearch->Save(); // discapacidad
			$this->tipodiscapacidad->AdvancedSearch->Save(); // tipodiscapacidad
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->id_centro->AdvancedSearch->Save(); // id_centro
			$this->gestion->AdvancedSearch->Save(); // gestion
			$this->fecha->AdvancedSearch->Save(); // fecha
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
		if ($this->codigorude->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigorude_es->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->departamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->municipio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->provincisa->AdvancedSearch->IssetSession())
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
		if ($this->curso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->discapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipodiscapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_centro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->gestion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha->AdvancedSearch->IssetSession())
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
		$this->codigorude->AdvancedSearch->UnsetSession();
		$this->codigorude_es->AdvancedSearch->UnsetSession();
		$this->departamento->AdvancedSearch->UnsetSession();
		$this->municipio->AdvancedSearch->UnsetSession();
		$this->provincisa->AdvancedSearch->UnsetSession();
		$this->unidadeducativa->AdvancedSearch->UnsetSession();
		$this->apellidopaterno->AdvancedSearch->UnsetSession();
		$this->apellidomaterno->AdvancedSearch->UnsetSession();
		$this->nombres->AdvancedSearch->UnsetSession();
		$this->nrodiscapacidad->AdvancedSearch->UnsetSession();
		$this->ci->AdvancedSearch->UnsetSession();
		$this->fechanacimiento->AdvancedSearch->UnsetSession();
		$this->sexo->AdvancedSearch->UnsetSession();
		$this->curso->AdvancedSearch->UnsetSession();
		$this->discapacidad->AdvancedSearch->UnsetSession();
		$this->tipodiscapacidad->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->id_centro->AdvancedSearch->UnsetSession();
		$this->gestion->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->codigorude->AdvancedSearch->Load();
		$this->codigorude_es->AdvancedSearch->Load();
		$this->departamento->AdvancedSearch->Load();
		$this->municipio->AdvancedSearch->Load();
		$this->provincisa->AdvancedSearch->Load();
		$this->unidadeducativa->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fechanacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->curso->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->tipodiscapacidad->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->id_centro->AdvancedSearch->Load();
		$this->gestion->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->codigorude, $bCtrl); // codigorude
			$this->UpdateSort($this->codigorude_es, $bCtrl); // codigorude_es
			$this->UpdateSort($this->departamento, $bCtrl); // departamento
			$this->UpdateSort($this->municipio, $bCtrl); // municipio
			$this->UpdateSort($this->provincisa, $bCtrl); // provincisa
			$this->UpdateSort($this->unidadeducativa, $bCtrl); // unidadeducativa
			$this->UpdateSort($this->apellidopaterno, $bCtrl); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno, $bCtrl); // apellidomaterno
			$this->UpdateSort($this->nombres, $bCtrl); // nombres
			$this->UpdateSort($this->nrodiscapacidad, $bCtrl); // nrodiscapacidad
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->fechanacimiento, $bCtrl); // fechanacimiento
			$this->UpdateSort($this->sexo, $bCtrl); // sexo
			$this->UpdateSort($this->curso, $bCtrl); // curso
			$this->UpdateSort($this->discapacidad, $bCtrl); // discapacidad
			$this->UpdateSort($this->tipodiscapacidad, $bCtrl); // tipodiscapacidad
			$this->UpdateSort($this->observaciones, $bCtrl); // observaciones
			$this->UpdateSort($this->fecha, $bCtrl); // fecha
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
				$this->codigorude->setSort("");
				$this->codigorude_es->setSort("");
				$this->departamento->setSort("");
				$this->municipio->setSort("");
				$this->provincisa->setSort("");
				$this->unidadeducativa->setSort("");
				$this->apellidopaterno->setSort("");
				$this->apellidomaterno->setSort("");
				$this->nombres->setSort("");
				$this->nrodiscapacidad->setSort("");
				$this->ci->setSort("");
				$this->fechanacimiento->setSort("");
				$this->sexo->setSort("");
				$this->curso->setSort("");
				$this->discapacidad->setSort("");
				$this->tipodiscapacidad->setSort("");
				$this->observaciones->setSort("");
				$this->fecha->setSort("");
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
		$item->Visible = $Security->CanEdit();
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

		// Add multi update
		$item = &$option->Add("multiupdate");
		$item->Body = "<a class=\"ewAction ewMultiUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" data-table=\"estudiante\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" href=\"\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'UpdateBtn',f:document.festudiantelist,url:'" . $this->MultiUpdateUrl . "'});return false;\">" . $Language->Phrase("UpdateSelectedLink") . "</a>";
		$item->Visible = ($Security->CanEdit());

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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"festudiantelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"festudiantelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.festudiantelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"festudiantelistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		// codigorude

		$this->codigorude->AdvancedSearch->SearchValue = @$_GET["x_codigorude"];
		if ($this->codigorude->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->codigorude->AdvancedSearch->SearchOperator = @$_GET["z_codigorude"];

		// codigorude_es
		$this->codigorude_es->AdvancedSearch->SearchValue = @$_GET["x_codigorude_es"];
		if ($this->codigorude_es->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->codigorude_es->AdvancedSearch->SearchOperator = @$_GET["z_codigorude_es"];

		// departamento
		$this->departamento->AdvancedSearch->SearchValue = @$_GET["x_departamento"];
		if ($this->departamento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->departamento->AdvancedSearch->SearchOperator = @$_GET["z_departamento"];

		// municipio
		$this->municipio->AdvancedSearch->SearchValue = @$_GET["x_municipio"];
		if ($this->municipio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->municipio->AdvancedSearch->SearchOperator = @$_GET["z_municipio"];

		// provincisa
		$this->provincisa->AdvancedSearch->SearchValue = @$_GET["x_provincisa"];
		if ($this->provincisa->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->provincisa->AdvancedSearch->SearchOperator = @$_GET["z_provincisa"];

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

		// curso
		$this->curso->AdvancedSearch->SearchValue = @$_GET["x_curso"];
		if ($this->curso->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->curso->AdvancedSearch->SearchOperator = @$_GET["z_curso"];

		// discapacidad
		$this->discapacidad->AdvancedSearch->SearchValue = @$_GET["x_discapacidad"];
		if ($this->discapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->discapacidad->AdvancedSearch->SearchOperator = @$_GET["z_discapacidad"];

		// tipodiscapacidad
		$this->tipodiscapacidad->AdvancedSearch->SearchValue = @$_GET["x_tipodiscapacidad"];
		if ($this->tipodiscapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipodiscapacidad->AdvancedSearch->SearchOperator = @$_GET["z_tipodiscapacidad"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$_GET["x_observaciones"];
		if ($this->observaciones->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];

		// gestion
		$this->gestion->AdvancedSearch->SearchValue = @$_GET["x_gestion"];
		if ($this->gestion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->gestion->AdvancedSearch->SearchOperator = @$_GET["z_gestion"];

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = @$_GET["x_fecha"];
		if ($this->fecha->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];
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
		$this->codigorude->setDbValue($row['codigorude']);
		$this->codigorude_es->setDbValue($row['codigorude_es']);
		$this->departamento->setDbValue($row['departamento']);
		$this->municipio->setDbValue($row['municipio']);
		$this->provincisa->setDbValue($row['provincisa']);
		$this->unidadeducativa->setDbValue($row['unidadeducativa']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombres->setDbValue($row['nombres']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->curso->setDbValue($row['curso']);
		$this->discapacidad->setDbValue($row['discapacidad']);
		$this->tipodiscapacidad->setDbValue($row['tipodiscapacidad']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
		$this->gestion->setDbValue($row['gestion']);
		$this->esincritoespecial->setDbValue($row['esincritoespecial']);
		$this->fecha->setDbValue($row['fecha']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['codigorude'] = NULL;
		$row['codigorude_es'] = NULL;
		$row['departamento'] = NULL;
		$row['municipio'] = NULL;
		$row['provincisa'] = NULL;
		$row['unidadeducativa'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombres'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['curso'] = NULL;
		$row['discapacidad'] = NULL;
		$row['tipodiscapacidad'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_centro'] = NULL;
		$row['gestion'] = NULL;
		$row['esincritoespecial'] = NULL;
		$row['fecha'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->codigorude->DbValue = $row['codigorude'];
		$this->codigorude_es->DbValue = $row['codigorude_es'];
		$this->departamento->DbValue = $row['departamento'];
		$this->municipio->DbValue = $row['municipio'];
		$this->provincisa->DbValue = $row['provincisa'];
		$this->unidadeducativa->DbValue = $row['unidadeducativa'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombres->DbValue = $row['nombres'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->curso->DbValue = $row['curso'];
		$this->discapacidad->DbValue = $row['discapacidad'];
		$this->tipodiscapacidad->DbValue = $row['tipodiscapacidad'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_centro->DbValue = $row['id_centro'];
		$this->gestion->DbValue = $row['gestion'];
		$this->esincritoespecial->DbValue = $row['esincritoespecial'];
		$this->fecha->DbValue = $row['fecha'];
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

		// codigorude
		// codigorude_es
		// departamento
		// municipio
		// provincisa
		// unidadeducativa
		// apellidopaterno
		// apellidomaterno
		// nombres
		// nrodiscapacidad
		// ci
		// fechanacimiento
		// sexo
		// curso
		// discapacidad
		// tipodiscapacidad
		// observaciones
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";

		// gestion
		$this->gestion->CellCssStyle = "white-space: nowrap;";

		// esincritoespecial
		$this->esincritoespecial->CellCssStyle = "white-space: nowrap;";

		// fecha
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// codigorude
		$this->codigorude->ViewValue = $this->codigorude->CurrentValue;
		$this->codigorude->ViewCustomAttributes = "";

		// codigorude_es
		$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
		$this->codigorude_es->ViewCustomAttributes = "";

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

		// provincisa
		if (strval($this->provincisa->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->provincisa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincia`";
		$sWhereWrk = "";
		$this->provincisa->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->provincisa, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->provincisa->ViewValue = $this->provincisa->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->provincisa->ViewValue = $this->provincisa->CurrentValue;
			}
		} else {
			$this->provincisa->ViewValue = NULL;
		}
		$this->provincisa->ViewCustomAttributes = "";

		// unidadeducativa
		if (strval($this->unidadeducativa->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->unidadeducativa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->unidadeducativa->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
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
		$this->fechanacimiento->ViewValue = ew_FormatDateTime($this->fechanacimiento->ViewValue, 7);
		$this->fechanacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// curso
		if (strval($this->curso->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->curso->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `curso` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `curso`";
		$sWhereWrk = "";
		$this->curso->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->curso, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->curso->ViewValue = $this->curso->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->curso->ViewValue = $this->curso->CurrentValue;
			}
		} else {
			$this->curso->ViewValue = NULL;
		}
		$this->curso->ViewCustomAttributes = "";

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

		// tipodiscapacidad
		if (strval($this->tipodiscapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipodiscapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiscapacidad`";
		$sWhereWrk = "";
		$this->tipodiscapacidad->LookupFilters = array();
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
		$this->tipodiscapacidad->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

			// codigorude
			$this->codigorude->LinkCustomAttributes = "";
			$this->codigorude->HrefValue = "";
			$this->codigorude->TooltipValue = "";

			// codigorude_es
			$this->codigorude_es->LinkCustomAttributes = "";
			$this->codigorude_es->HrefValue = "";
			$this->codigorude_es->TooltipValue = "";

			// departamento
			$this->departamento->LinkCustomAttributes = "";
			$this->departamento->HrefValue = "";
			$this->departamento->TooltipValue = "";

			// municipio
			$this->municipio->LinkCustomAttributes = "";
			$this->municipio->HrefValue = "";
			$this->municipio->TooltipValue = "";

			// provincisa
			$this->provincisa->LinkCustomAttributes = "";
			$this->provincisa->HrefValue = "";
			$this->provincisa->TooltipValue = "";

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

			// curso
			$this->curso->LinkCustomAttributes = "";
			$this->curso->HrefValue = "";
			$this->curso->TooltipValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";
			$this->discapacidad->TooltipValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->LinkCustomAttributes = "";
			$this->tipodiscapacidad->HrefValue = "";
			$this->tipodiscapacidad->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// codigorude
			$this->codigorude->EditAttrs["class"] = "form-control";
			$this->codigorude->EditCustomAttributes = "";
			$this->codigorude->EditValue = ew_HtmlEncode($this->codigorude->AdvancedSearch->SearchValue);
			$this->codigorude->PlaceHolder = ew_RemoveHtml($this->codigorude->FldCaption());

			// codigorude_es
			$this->codigorude_es->EditAttrs["class"] = "form-control";
			$this->codigorude_es->EditCustomAttributes = "";
			$this->codigorude_es->EditValue = ew_HtmlEncode($this->codigorude_es->AdvancedSearch->SearchValue);
			$this->codigorude_es->PlaceHolder = ew_RemoveHtml($this->codigorude_es->FldCaption());

			// departamento
			$this->departamento->EditAttrs["class"] = "form-control";
			$this->departamento->EditCustomAttributes = "";
			if (trim(strval($this->departamento->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->departamento->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamento`";
			$sWhereWrk = "";
			$this->departamento->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->departamento, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->departamento->EditValue = $arwrk;

			// municipio
			$this->municipio->EditAttrs["class"] = "form-control";
			$this->municipio->EditCustomAttributes = "";

			// provincisa
			$this->provincisa->EditAttrs["class"] = "form-control";
			$this->provincisa->EditCustomAttributes = "";
			if (trim(strval($this->provincisa->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->provincisa->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `provincia`";
			$sWhereWrk = "";
			$this->provincisa->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->provincisa, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->provincisa->EditValue = $arwrk;

			// unidadeducativa
			$this->unidadeducativa->EditAttrs["class"] = "form-control";
			$this->unidadeducativa->EditCustomAttributes = "";
			if (trim(strval($this->unidadeducativa->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->unidadeducativa->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `unidadeducativa`";
			$sWhereWrk = "";
			$this->unidadeducativa->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
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
			$this->fechanacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fechanacimiento->AdvancedSearch->SearchValue, 7), 7));
			$this->fechanacimiento->PlaceHolder = ew_RemoveHtml($this->fechanacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// curso
			$this->curso->EditAttrs["class"] = "form-control";
			$this->curso->EditCustomAttributes = "";
			if (trim(strval($this->curso->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->curso->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `curso` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `curso`";
			$sWhereWrk = "";
			$this->curso->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->curso, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->curso->EditValue = $arwrk;

			// discapacidad
			$this->discapacidad->EditAttrs["class"] = "form-control";
			$this->discapacidad->EditCustomAttributes = "";
			$this->discapacidad->EditValue = ew_HtmlEncode($this->discapacidad->AdvancedSearch->SearchValue);
			$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

			// tipodiscapacidad
			$this->tipodiscapacidad->EditAttrs["class"] = "form-control";
			$this->tipodiscapacidad->EditCustomAttributes = "";

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->AdvancedSearch->SearchValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());
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
		$this->codigorude->AdvancedSearch->Load();
		$this->codigorude_es->AdvancedSearch->Load();
		$this->departamento->AdvancedSearch->Load();
		$this->municipio->AdvancedSearch->Load();
		$this->provincisa->AdvancedSearch->Load();
		$this->unidadeducativa->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fechanacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->curso->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->tipodiscapacidad->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->gestion->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
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
		case "x_departamento":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->departamento, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_provincisa":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincia`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->provincisa, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_unidadeducativa":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->unidadeducativa, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_curso":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `curso` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `curso`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->curso, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($estudiante_list)) $estudiante_list = new cestudiante_list();

// Page init
$estudiante_list->Page_Init();

// Page main
$estudiante_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$estudiante_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = festudiantelist = new ew_Form("festudiantelist", "list");
festudiantelist.FormKeyCountName = '<?php echo $estudiante_list->FormKeyCountName ?>';

// Form_CustomValidate event
festudiantelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
festudiantelist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
festudiantelist.Lists["x_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
festudiantelist.Lists["x_departamento"].Data = "<?php echo $estudiante_list->departamento->LookupFilterQuery(FALSE, "list") ?>";
festudiantelist.Lists["x_municipio"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"municipio"};
festudiantelist.Lists["x_municipio"].Data = "<?php echo $estudiante_list->municipio->LookupFilterQuery(FALSE, "list") ?>";
festudiantelist.Lists["x_provincisa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincia"};
festudiantelist.Lists["x_provincisa"].Data = "<?php echo $estudiante_list->provincisa->LookupFilterQuery(FALSE, "list") ?>";
festudiantelist.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
festudiantelist.Lists["x_unidadeducativa"].Data = "<?php echo $estudiante_list->unidadeducativa->LookupFilterQuery(FALSE, "list") ?>";
festudiantelist.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
festudiantelist.Lists["x_sexo"].Options = <?php echo json_encode($estudiante_list->sexo->Options()) ?>;
festudiantelist.Lists["x_curso"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_curso","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"curso"};
festudiantelist.Lists["x_curso"].Data = "<?php echo $estudiante_list->curso->LookupFilterQuery(FALSE, "list") ?>";
festudiantelist.Lists["x_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
festudiantelist.Lists["x_discapacidad"].Data = "<?php echo $estudiante_list->discapacidad->LookupFilterQuery(FALSE, "list") ?>";
festudiantelist.AutoSuggests["x_discapacidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $estudiante_list->discapacidad->LookupFilterQuery(TRUE, "list"))) ?>;
festudiantelist.Lists["x_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
festudiantelist.Lists["x_tipodiscapacidad"].Data = "<?php echo $estudiante_list->tipodiscapacidad->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = festudiantelistsrch = new ew_Form("festudiantelistsrch");

// Validate function for search
festudiantelistsrch.Validate = function(fobj) {
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
festudiantelistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
festudiantelistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
festudiantelistsrch.Lists["x_departamento"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
festudiantelistsrch.Lists["x_departamento"].Data = "<?php echo $estudiante_list->departamento->LookupFilterQuery(FALSE, "extbs") ?>";
festudiantelistsrch.Lists["x_provincisa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincia"};
festudiantelistsrch.Lists["x_provincisa"].Data = "<?php echo $estudiante_list->provincisa->LookupFilterQuery(FALSE, "extbs") ?>";
festudiantelistsrch.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
festudiantelistsrch.Lists["x_unidadeducativa"].Data = "<?php echo $estudiante_list->unidadeducativa->LookupFilterQuery(FALSE, "extbs") ?>";
festudiantelistsrch.Lists["x_curso"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_curso","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"curso"};
festudiantelistsrch.Lists["x_curso"].Data = "<?php echo $estudiante_list->curso->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($estudiante_list->TotalRecs > 0 && $estudiante_list->ExportOptions->Visible()) { ?>
<?php $estudiante_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($estudiante_list->SearchOptions->Visible()) { ?>
<?php $estudiante_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($estudiante_list->FilterOptions->Visible()) { ?>
<?php $estudiante_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $estudiante_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($estudiante_list->TotalRecs <= 0)
			$estudiante_list->TotalRecs = $estudiante->ListRecordCount();
	} else {
		if (!$estudiante_list->Recordset && ($estudiante_list->Recordset = $estudiante_list->LoadRecordset()))
			$estudiante_list->TotalRecs = $estudiante_list->Recordset->RecordCount();
	}
	$estudiante_list->StartRec = 1;
	if ($estudiante_list->DisplayRecs <= 0 || ($estudiante->Export <> "" && $estudiante->ExportAll)) // Display all records
		$estudiante_list->DisplayRecs = $estudiante_list->TotalRecs;
	if (!($estudiante->Export <> "" && $estudiante->ExportAll))
		$estudiante_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$estudiante_list->Recordset = $estudiante_list->LoadRecordset($estudiante_list->StartRec-1, $estudiante_list->DisplayRecs);

	// Set no record found message
	if ($estudiante->CurrentAction == "" && $estudiante_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$estudiante_list->setWarningMessage(ew_DeniedMsg());
		if ($estudiante_list->SearchWhere == "0=101")
			$estudiante_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$estudiante_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$estudiante_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($estudiante->Export == "" && $estudiante->CurrentAction == "") { ?>
<form name="festudiantelistsrch" id="festudiantelistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($estudiante_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="festudiantelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="estudiante">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$estudiante_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$estudiante->RowType = EW_ROWTYPE_SEARCH;

// Render row
$estudiante->ResetAttrs();
$estudiante_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($estudiante->codigorude->Visible) { // codigorude ?>
	<div id="xsc_codigorude" class="ewCell form-group">
		<label for="x_codigorude" class="ewSearchCaption ewLabel"><?php echo $estudiante->codigorude->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_codigorude" id="z_codigorude" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="estudiante" data-field="x_codigorude" name="x_codigorude" id="x_codigorude" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($estudiante->codigorude->getPlaceHolder()) ?>" value="<?php echo $estudiante->codigorude->EditValue ?>"<?php echo $estudiante->codigorude->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($estudiante->codigorude_es->Visible) { // codigorude_es ?>
	<div id="xsc_codigorude_es" class="ewCell form-group">
		<label for="x_codigorude_es" class="ewSearchCaption ewLabel"><?php echo $estudiante->codigorude_es->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_codigorude_es" id="z_codigorude_es" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="estudiante" data-field="x_codigorude_es" name="x_codigorude_es" id="x_codigorude_es" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($estudiante->codigorude_es->getPlaceHolder()) ?>" value="<?php echo $estudiante->codigorude_es->EditValue ?>"<?php echo $estudiante->codigorude_es->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($estudiante->departamento->Visible) { // departamento ?>
	<div id="xsc_departamento" class="ewCell form-group">
		<label for="x_departamento" class="ewSearchCaption ewLabel"><?php echo $estudiante->departamento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_departamento" id="z_departamento" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="estudiante" data-field="x_departamento" data-value-separator="<?php echo $estudiante->departamento->DisplayValueSeparatorAttribute() ?>" id="x_departamento" name="x_departamento"<?php echo $estudiante->departamento->EditAttributes() ?>>
<?php echo $estudiante->departamento->SelectOptionListHtml("x_departamento") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($estudiante->provincisa->Visible) { // provincisa ?>
	<div id="xsc_provincisa" class="ewCell form-group">
		<label for="x_provincisa" class="ewSearchCaption ewLabel"><?php echo $estudiante->provincisa->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_provincisa" id="z_provincisa" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="estudiante" data-field="x_provincisa" data-value-separator="<?php echo $estudiante->provincisa->DisplayValueSeparatorAttribute() ?>" id="x_provincisa" name="x_provincisa"<?php echo $estudiante->provincisa->EditAttributes() ?>>
<?php echo $estudiante->provincisa->SelectOptionListHtml("x_provincisa") ?>
</select>
</span>
	</div>
<?php } ?>
<?php if ($estudiante->unidadeducativa->Visible) { // unidadeducativa ?>
	<div id="xsc_unidadeducativa" class="ewCell form-group">
		<label for="x_unidadeducativa" class="ewSearchCaption ewLabel"><?php echo $estudiante->unidadeducativa->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_unidadeducativa" id="z_unidadeducativa" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="estudiante" data-field="x_unidadeducativa" data-value-separator="<?php echo $estudiante->unidadeducativa->DisplayValueSeparatorAttribute() ?>" id="x_unidadeducativa" name="x_unidadeducativa"<?php echo $estudiante->unidadeducativa->EditAttributes() ?>>
<?php echo $estudiante->unidadeducativa->SelectOptionListHtml("x_unidadeducativa") ?>
</select>
</span>
	</div>
<?php } ?>
<?php if ($estudiante->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="xsc_apellidopaterno" class="ewCell form-group">
		<label for="x_apellidopaterno" class="ewSearchCaption ewLabel"><?php echo $estudiante->apellidopaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidopaterno" id="z_apellidopaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="estudiante" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($estudiante->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $estudiante->apellidopaterno->EditValue ?>"<?php echo $estudiante->apellidopaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($estudiante->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="xsc_apellidomaterno" class="ewCell form-group">
		<label for="x_apellidomaterno" class="ewSearchCaption ewLabel"><?php echo $estudiante->apellidomaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidomaterno" id="z_apellidomaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="estudiante" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($estudiante->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $estudiante->apellidomaterno->EditValue ?>"<?php echo $estudiante->apellidomaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($estudiante->nombres->Visible) { // nombres ?>
	<div id="xsc_nombres" class="ewCell form-group">
		<label for="x_nombres" class="ewSearchCaption ewLabel"><?php echo $estudiante->nombres->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombres" id="z_nombres" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="estudiante" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($estudiante->nombres->getPlaceHolder()) ?>" value="<?php echo $estudiante->nombres->EditValue ?>"<?php echo $estudiante->nombres->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($estudiante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="xsc_nrodiscapacidad" class="ewCell form-group">
		<label for="x_nrodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $estudiante->nrodiscapacidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nrodiscapacidad" id="z_nrodiscapacidad" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="estudiante" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($estudiante->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $estudiante->nrodiscapacidad->EditValue ?>"<?php echo $estudiante->nrodiscapacidad->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($estudiante->curso->Visible) { // curso ?>
	<div id="xsc_curso" class="ewCell form-group">
		<label for="x_curso" class="ewSearchCaption ewLabel"><?php echo $estudiante->curso->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_curso" id="z_curso" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="estudiante" data-field="x_curso" data-value-separator="<?php echo $estudiante->curso->DisplayValueSeparatorAttribute() ?>" id="x_curso" name="x_curso"<?php echo $estudiante->curso->EditAttributes() ?>>
<?php echo $estudiante->curso->SelectOptionListHtml("x_curso") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $estudiante_list->ShowPageHeader(); ?>
<?php
$estudiante_list->ShowMessage();
?>
<?php if ($estudiante_list->TotalRecs > 0 || $estudiante->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($estudiante_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> estudiante">
<div class="box-header ewGridUpperPanel">
<?php if ($estudiante->CurrentAction <> "gridadd" && $estudiante->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($estudiante_list->Pager)) $estudiante_list->Pager = new cPrevNextPager($estudiante_list->StartRec, $estudiante_list->DisplayRecs, $estudiante_list->TotalRecs, $estudiante_list->AutoHidePager) ?>
<?php if ($estudiante_list->Pager->RecordCount > 0 && $estudiante_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($estudiante_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $estudiante_list->PageUrl() ?>start=<?php echo $estudiante_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($estudiante_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $estudiante_list->PageUrl() ?>start=<?php echo $estudiante_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $estudiante_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($estudiante_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $estudiante_list->PageUrl() ?>start=<?php echo $estudiante_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($estudiante_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $estudiante_list->PageUrl() ?>start=<?php echo $estudiante_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $estudiante_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($estudiante_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $estudiante_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $estudiante_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $estudiante_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($estudiante_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="festudiantelist" id="festudiantelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($estudiante_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $estudiante_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="estudiante">
<div id="gmp_estudiante" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($estudiante_list->TotalRecs > 0 || $estudiante->CurrentAction == "gridedit") { ?>
<table id="tbl_estudiantelist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$estudiante_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$estudiante_list->RenderListOptions();

// Render list options (header, left)
$estudiante_list->ListOptions->Render("header", "left");
?>
<?php if ($estudiante->codigorude->Visible) { // codigorude ?>
	<?php if ($estudiante->SortUrl($estudiante->codigorude) == "") { ?>
		<th data-name="codigorude" class="<?php echo $estudiante->codigorude->HeaderCellClass() ?>"><div id="elh_estudiante_codigorude" class="estudiante_codigorude"><div class="ewTableHeaderCaption"><?php echo $estudiante->codigorude->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigorude" class="<?php echo $estudiante->codigorude->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->codigorude) ?>',2);"><div id="elh_estudiante_codigorude" class="estudiante_codigorude">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->codigorude->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->codigorude->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->codigorude->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->codigorude_es->Visible) { // codigorude_es ?>
	<?php if ($estudiante->SortUrl($estudiante->codigorude_es) == "") { ?>
		<th data-name="codigorude_es" class="<?php echo $estudiante->codigorude_es->HeaderCellClass() ?>"><div id="elh_estudiante_codigorude_es" class="estudiante_codigorude_es"><div class="ewTableHeaderCaption"><?php echo $estudiante->codigorude_es->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigorude_es" class="<?php echo $estudiante->codigorude_es->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->codigorude_es) ?>',2);"><div id="elh_estudiante_codigorude_es" class="estudiante_codigorude_es">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->codigorude_es->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->codigorude_es->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->codigorude_es->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->departamento->Visible) { // departamento ?>
	<?php if ($estudiante->SortUrl($estudiante->departamento) == "") { ?>
		<th data-name="departamento" class="<?php echo $estudiante->departamento->HeaderCellClass() ?>"><div id="elh_estudiante_departamento" class="estudiante_departamento"><div class="ewTableHeaderCaption"><?php echo $estudiante->departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departamento" class="<?php echo $estudiante->departamento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->departamento) ?>',2);"><div id="elh_estudiante_departamento" class="estudiante_departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->departamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->municipio->Visible) { // municipio ?>
	<?php if ($estudiante->SortUrl($estudiante->municipio) == "") { ?>
		<th data-name="municipio" class="<?php echo $estudiante->municipio->HeaderCellClass() ?>"><div id="elh_estudiante_municipio" class="estudiante_municipio"><div class="ewTableHeaderCaption"><?php echo $estudiante->municipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="municipio" class="<?php echo $estudiante->municipio->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->municipio) ?>',2);"><div id="elh_estudiante_municipio" class="estudiante_municipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->municipio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->municipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->municipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->provincisa->Visible) { // provincisa ?>
	<?php if ($estudiante->SortUrl($estudiante->provincisa) == "") { ?>
		<th data-name="provincisa" class="<?php echo $estudiante->provincisa->HeaderCellClass() ?>"><div id="elh_estudiante_provincisa" class="estudiante_provincisa"><div class="ewTableHeaderCaption"><?php echo $estudiante->provincisa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="provincisa" class="<?php echo $estudiante->provincisa->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->provincisa) ?>',2);"><div id="elh_estudiante_provincisa" class="estudiante_provincisa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->provincisa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->provincisa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->provincisa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->unidadeducativa->Visible) { // unidadeducativa ?>
	<?php if ($estudiante->SortUrl($estudiante->unidadeducativa) == "") { ?>
		<th data-name="unidadeducativa" class="<?php echo $estudiante->unidadeducativa->HeaderCellClass() ?>"><div id="elh_estudiante_unidadeducativa" class="estudiante_unidadeducativa"><div class="ewTableHeaderCaption"><?php echo $estudiante->unidadeducativa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="unidadeducativa" class="<?php echo $estudiante->unidadeducativa->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->unidadeducativa) ?>',2);"><div id="elh_estudiante_unidadeducativa" class="estudiante_unidadeducativa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->unidadeducativa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->unidadeducativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->unidadeducativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->apellidopaterno->Visible) { // apellidopaterno ?>
	<?php if ($estudiante->SortUrl($estudiante->apellidopaterno) == "") { ?>
		<th data-name="apellidopaterno" class="<?php echo $estudiante->apellidopaterno->HeaderCellClass() ?>"><div id="elh_estudiante_apellidopaterno" class="estudiante_apellidopaterno"><div class="ewTableHeaderCaption"><?php echo $estudiante->apellidopaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidopaterno" class="<?php echo $estudiante->apellidopaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->apellidopaterno) ?>',2);"><div id="elh_estudiante_apellidopaterno" class="estudiante_apellidopaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->apellidopaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->apellidomaterno->Visible) { // apellidomaterno ?>
	<?php if ($estudiante->SortUrl($estudiante->apellidomaterno) == "") { ?>
		<th data-name="apellidomaterno" class="<?php echo $estudiante->apellidomaterno->HeaderCellClass() ?>"><div id="elh_estudiante_apellidomaterno" class="estudiante_apellidomaterno"><div class="ewTableHeaderCaption"><?php echo $estudiante->apellidomaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidomaterno" class="<?php echo $estudiante->apellidomaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->apellidomaterno) ?>',2);"><div id="elh_estudiante_apellidomaterno" class="estudiante_apellidomaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->apellidomaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->nombres->Visible) { // nombres ?>
	<?php if ($estudiante->SortUrl($estudiante->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $estudiante->nombres->HeaderCellClass() ?>"><div id="elh_estudiante_nombres" class="estudiante_nombres"><div class="ewTableHeaderCaption"><?php echo $estudiante->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $estudiante->nombres->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->nombres) ?>',2);"><div id="elh_estudiante_nombres" class="estudiante_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<?php if ($estudiante->SortUrl($estudiante->nrodiscapacidad) == "") { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $estudiante->nrodiscapacidad->HeaderCellClass() ?>"><div id="elh_estudiante_nrodiscapacidad" class="estudiante_nrodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $estudiante->nrodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $estudiante->nrodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->nrodiscapacidad) ?>',2);"><div id="elh_estudiante_nrodiscapacidad" class="estudiante_nrodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->nrodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->ci->Visible) { // ci ?>
	<?php if ($estudiante->SortUrl($estudiante->ci) == "") { ?>
		<th data-name="ci" class="<?php echo $estudiante->ci->HeaderCellClass() ?>"><div id="elh_estudiante_ci" class="estudiante_ci"><div class="ewTableHeaderCaption"><?php echo $estudiante->ci->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ci" class="<?php echo $estudiante->ci->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->ci) ?>',2);"><div id="elh_estudiante_ci" class="estudiante_ci">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->ci->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->fechanacimiento->Visible) { // fechanacimiento ?>
	<?php if ($estudiante->SortUrl($estudiante->fechanacimiento) == "") { ?>
		<th data-name="fechanacimiento" class="<?php echo $estudiante->fechanacimiento->HeaderCellClass() ?>"><div id="elh_estudiante_fechanacimiento" class="estudiante_fechanacimiento"><div class="ewTableHeaderCaption"><?php echo $estudiante->fechanacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechanacimiento" class="<?php echo $estudiante->fechanacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->fechanacimiento) ?>',2);"><div id="elh_estudiante_fechanacimiento" class="estudiante_fechanacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->fechanacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->fechanacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->fechanacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->sexo->Visible) { // sexo ?>
	<?php if ($estudiante->SortUrl($estudiante->sexo) == "") { ?>
		<th data-name="sexo" class="<?php echo $estudiante->sexo->HeaderCellClass() ?>"><div id="elh_estudiante_sexo" class="estudiante_sexo"><div class="ewTableHeaderCaption"><?php echo $estudiante->sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sexo" class="<?php echo $estudiante->sexo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->sexo) ?>',2);"><div id="elh_estudiante_sexo" class="estudiante_sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->curso->Visible) { // curso ?>
	<?php if ($estudiante->SortUrl($estudiante->curso) == "") { ?>
		<th data-name="curso" class="<?php echo $estudiante->curso->HeaderCellClass() ?>"><div id="elh_estudiante_curso" class="estudiante_curso"><div class="ewTableHeaderCaption"><?php echo $estudiante->curso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="curso" class="<?php echo $estudiante->curso->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->curso) ?>',2);"><div id="elh_estudiante_curso" class="estudiante_curso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->curso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->curso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->curso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->discapacidad->Visible) { // discapacidad ?>
	<?php if ($estudiante->SortUrl($estudiante->discapacidad) == "") { ?>
		<th data-name="discapacidad" class="<?php echo $estudiante->discapacidad->HeaderCellClass() ?>"><div id="elh_estudiante_discapacidad" class="estudiante_discapacidad"><div class="ewTableHeaderCaption"><?php echo $estudiante->discapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="discapacidad" class="<?php echo $estudiante->discapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->discapacidad) ?>',2);"><div id="elh_estudiante_discapacidad" class="estudiante_discapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->discapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
	<?php if ($estudiante->SortUrl($estudiante->tipodiscapacidad) == "") { ?>
		<th data-name="tipodiscapacidad" class="<?php echo $estudiante->tipodiscapacidad->HeaderCellClass() ?>"><div id="elh_estudiante_tipodiscapacidad" class="estudiante_tipodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $estudiante->tipodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipodiscapacidad" class="<?php echo $estudiante->tipodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->tipodiscapacidad) ?>',2);"><div id="elh_estudiante_tipodiscapacidad" class="estudiante_tipodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->tipodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->observaciones->Visible) { // observaciones ?>
	<?php if ($estudiante->SortUrl($estudiante->observaciones) == "") { ?>
		<th data-name="observaciones" class="<?php echo $estudiante->observaciones->HeaderCellClass() ?>"><div id="elh_estudiante_observaciones" class="estudiante_observaciones"><div class="ewTableHeaderCaption"><?php echo $estudiante->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones" class="<?php echo $estudiante->observaciones->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->observaciones) ?>',2);"><div id="elh_estudiante_observaciones" class="estudiante_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->observaciones->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($estudiante->fecha->Visible) { // fecha ?>
	<?php if ($estudiante->SortUrl($estudiante->fecha) == "") { ?>
		<th data-name="fecha" class="<?php echo $estudiante->fecha->HeaderCellClass() ?>"><div id="elh_estudiante_fecha" class="estudiante_fecha"><div class="ewTableHeaderCaption"><?php echo $estudiante->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha" class="<?php echo $estudiante->fecha->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estudiante->SortUrl($estudiante->fecha) ?>',2);"><div id="elh_estudiante_fecha" class="estudiante_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estudiante->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estudiante->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estudiante->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$estudiante_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($estudiante->ExportAll && $estudiante->Export <> "") {
	$estudiante_list->StopRec = $estudiante_list->TotalRecs;
} else {

	// Set the last record to display
	if ($estudiante_list->TotalRecs > $estudiante_list->StartRec + $estudiante_list->DisplayRecs - 1)
		$estudiante_list->StopRec = $estudiante_list->StartRec + $estudiante_list->DisplayRecs - 1;
	else
		$estudiante_list->StopRec = $estudiante_list->TotalRecs;
}
$estudiante_list->RecCnt = $estudiante_list->StartRec - 1;
if ($estudiante_list->Recordset && !$estudiante_list->Recordset->EOF) {
	$estudiante_list->Recordset->MoveFirst();
	$bSelectLimit = $estudiante_list->UseSelectLimit;
	if (!$bSelectLimit && $estudiante_list->StartRec > 1)
		$estudiante_list->Recordset->Move($estudiante_list->StartRec - 1);
} elseif (!$estudiante->AllowAddDeleteRow && $estudiante_list->StopRec == 0) {
	$estudiante_list->StopRec = $estudiante->GridAddRowCount;
}

// Initialize aggregate
$estudiante->RowType = EW_ROWTYPE_AGGREGATEINIT;
$estudiante->ResetAttrs();
$estudiante_list->RenderRow();
while ($estudiante_list->RecCnt < $estudiante_list->StopRec) {
	$estudiante_list->RecCnt++;
	if (intval($estudiante_list->RecCnt) >= intval($estudiante_list->StartRec)) {
		$estudiante_list->RowCnt++;

		// Set up key count
		$estudiante_list->KeyCount = $estudiante_list->RowIndex;

		// Init row class and style
		$estudiante->ResetAttrs();
		$estudiante->CssClass = "";
		if ($estudiante->CurrentAction == "gridadd") {
		} else {
			$estudiante_list->LoadRowValues($estudiante_list->Recordset); // Load row values
		}
		$estudiante->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$estudiante->RowAttrs = array_merge($estudiante->RowAttrs, array('data-rowindex'=>$estudiante_list->RowCnt, 'id'=>'r' . $estudiante_list->RowCnt . '_estudiante', 'data-rowtype'=>$estudiante->RowType));

		// Render row
		$estudiante_list->RenderRow();

		// Render list options
		$estudiante_list->RenderListOptions();
?>
	<tr<?php echo $estudiante->RowAttributes() ?>>
<?php

// Render list options (body, left)
$estudiante_list->ListOptions->Render("body", "left", $estudiante_list->RowCnt);
?>
	<?php if ($estudiante->codigorude->Visible) { // codigorude ?>
		<td data-name="codigorude"<?php echo $estudiante->codigorude->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_codigorude" class="estudiante_codigorude">
<span<?php echo $estudiante->codigorude->ViewAttributes() ?>>
<?php echo $estudiante->codigorude->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->codigorude_es->Visible) { // codigorude_es ?>
		<td data-name="codigorude_es"<?php echo $estudiante->codigorude_es->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_codigorude_es" class="estudiante_codigorude_es">
<span<?php echo $estudiante->codigorude_es->ViewAttributes() ?>>
<?php echo $estudiante->codigorude_es->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->departamento->Visible) { // departamento ?>
		<td data-name="departamento"<?php echo $estudiante->departamento->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_departamento" class="estudiante_departamento">
<span<?php echo $estudiante->departamento->ViewAttributes() ?>>
<?php echo $estudiante->departamento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->municipio->Visible) { // municipio ?>
		<td data-name="municipio"<?php echo $estudiante->municipio->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_municipio" class="estudiante_municipio">
<span<?php echo $estudiante->municipio->ViewAttributes() ?>>
<?php echo $estudiante->municipio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->provincisa->Visible) { // provincisa ?>
		<td data-name="provincisa"<?php echo $estudiante->provincisa->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_provincisa" class="estudiante_provincisa">
<span<?php echo $estudiante->provincisa->ViewAttributes() ?>>
<?php echo $estudiante->provincisa->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->unidadeducativa->Visible) { // unidadeducativa ?>
		<td data-name="unidadeducativa"<?php echo $estudiante->unidadeducativa->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_unidadeducativa" class="estudiante_unidadeducativa">
<span<?php echo $estudiante->unidadeducativa->ViewAttributes() ?>>
<?php echo $estudiante->unidadeducativa->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->apellidopaterno->Visible) { // apellidopaterno ?>
		<td data-name="apellidopaterno"<?php echo $estudiante->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_apellidopaterno" class="estudiante_apellidopaterno">
<span<?php echo $estudiante->apellidopaterno->ViewAttributes() ?>>
<?php echo $estudiante->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->apellidomaterno->Visible) { // apellidomaterno ?>
		<td data-name="apellidomaterno"<?php echo $estudiante->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_apellidomaterno" class="estudiante_apellidomaterno">
<span<?php echo $estudiante->apellidomaterno->ViewAttributes() ?>>
<?php echo $estudiante->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $estudiante->nombres->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_nombres" class="estudiante_nombres">
<span<?php echo $estudiante->nombres->ViewAttributes() ?>>
<?php echo $estudiante->nombres->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td data-name="nrodiscapacidad"<?php echo $estudiante->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_nrodiscapacidad" class="estudiante_nrodiscapacidad">
<span<?php echo $estudiante->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $estudiante->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->ci->Visible) { // ci ?>
		<td data-name="ci"<?php echo $estudiante->ci->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_ci" class="estudiante_ci">
<span<?php echo $estudiante->ci->ViewAttributes() ?>>
<?php echo $estudiante->ci->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->fechanacimiento->Visible) { // fechanacimiento ?>
		<td data-name="fechanacimiento"<?php echo $estudiante->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_fechanacimiento" class="estudiante_fechanacimiento">
<span<?php echo $estudiante->fechanacimiento->ViewAttributes() ?>>
<?php echo $estudiante->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->sexo->Visible) { // sexo ?>
		<td data-name="sexo"<?php echo $estudiante->sexo->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_sexo" class="estudiante_sexo">
<span<?php echo $estudiante->sexo->ViewAttributes() ?>>
<?php echo $estudiante->sexo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->curso->Visible) { // curso ?>
		<td data-name="curso"<?php echo $estudiante->curso->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_curso" class="estudiante_curso">
<span<?php echo $estudiante->curso->ViewAttributes() ?>>
<?php echo $estudiante->curso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->discapacidad->Visible) { // discapacidad ?>
		<td data-name="discapacidad"<?php echo $estudiante->discapacidad->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_discapacidad" class="estudiante_discapacidad">
<span<?php echo $estudiante->discapacidad->ViewAttributes() ?>>
<?php echo $estudiante->discapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->tipodiscapacidad->Visible) { // tipodiscapacidad ?>
		<td data-name="tipodiscapacidad"<?php echo $estudiante->tipodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_tipodiscapacidad" class="estudiante_tipodiscapacidad">
<span<?php echo $estudiante->tipodiscapacidad->ViewAttributes() ?>>
<?php echo $estudiante->tipodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $estudiante->observaciones->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_observaciones" class="estudiante_observaciones">
<span<?php echo $estudiante->observaciones->ViewAttributes() ?>>
<?php echo $estudiante->observaciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($estudiante->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $estudiante->fecha->CellAttributes() ?>>
<span id="el<?php echo $estudiante_list->RowCnt ?>_estudiante_fecha" class="estudiante_fecha">
<span<?php echo $estudiante->fecha->ViewAttributes() ?>>
<?php echo $estudiante->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$estudiante_list->ListOptions->Render("body", "right", $estudiante_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($estudiante->CurrentAction <> "gridadd")
		$estudiante_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($estudiante->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($estudiante_list->Recordset)
	$estudiante_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($estudiante->CurrentAction <> "gridadd" && $estudiante->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($estudiante_list->Pager)) $estudiante_list->Pager = new cPrevNextPager($estudiante_list->StartRec, $estudiante_list->DisplayRecs, $estudiante_list->TotalRecs, $estudiante_list->AutoHidePager) ?>
<?php if ($estudiante_list->Pager->RecordCount > 0 && $estudiante_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($estudiante_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $estudiante_list->PageUrl() ?>start=<?php echo $estudiante_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($estudiante_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $estudiante_list->PageUrl() ?>start=<?php echo $estudiante_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $estudiante_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($estudiante_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $estudiante_list->PageUrl() ?>start=<?php echo $estudiante_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($estudiante_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $estudiante_list->PageUrl() ?>start=<?php echo $estudiante_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $estudiante_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($estudiante_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $estudiante_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $estudiante_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $estudiante_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($estudiante_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($estudiante_list->TotalRecs == 0 && $estudiante->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($estudiante_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
festudiantelistsrch.FilterList = <?php echo $estudiante_list->GetFilterList() ?>;
festudiantelistsrch.Init();
festudiantelist.Init();
</script>
<?php
$estudiante_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$estudiante_list->Page_Terminate();
?>
