<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "viewestudianteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$viewestudiante_list = NULL; // Initialize page object first

class cviewestudiante_list extends cviewestudiante {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'viewestudiante';

	// Page object name
	var $PageObjName = 'viewestudiante_list';

	// Grid form hidden field names
	var $FormName = 'fviewestudiantelist';
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

		// Table object (viewestudiante)
		if (!isset($GLOBALS["viewestudiante"]) || get_class($GLOBALS["viewestudiante"]) == "cviewestudiante") {
			$GLOBALS["viewestudiante"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewestudiante"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "viewestudianteadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "viewestudiantedelete.php";
		$this->MultiUpdateUrl = "viewestudianteupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'viewestudiante', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewestudiantelistsrch";

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
		$this->departamento->SetVisibility();
		$this->codigorude->SetVisibility();
		$this->codigorude_es->SetVisibility();
		$this->municipio->SetVisibility();
		$this->provincia->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->nombre->SetVisibility();
		$this->materno->SetVisibility();
		$this->paterno->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->edad->SetVisibility();
		$this->sexo->SetVisibility();
		$this->curso->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapcidad->SetVisibility();
		$this->nombreinstitucion->SetVisibility();

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
		global $EW_EXPORT, $viewestudiante;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($viewestudiante);
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
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

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

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->departamento->AdvancedSearch->ToJson(), ","); // Field departamento
		$sFilterList = ew_Concat($sFilterList, $this->codigorude->AdvancedSearch->ToJson(), ","); // Field codigorude
		$sFilterList = ew_Concat($sFilterList, $this->codigorude_es->AdvancedSearch->ToJson(), ","); // Field codigorude_es
		$sFilterList = ew_Concat($sFilterList, $this->municipio->AdvancedSearch->ToJson(), ","); // Field municipio
		$sFilterList = ew_Concat($sFilterList, $this->provincia->AdvancedSearch->ToJson(), ","); // Field provincia
		$sFilterList = ew_Concat($sFilterList, $this->unidadeducativa->AdvancedSearch->ToJson(), ","); // Field unidadeducativa
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJson(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->materno->AdvancedSearch->ToJson(), ","); // Field materno
		$sFilterList = ew_Concat($sFilterList, $this->paterno->AdvancedSearch->ToJson(), ","); // Field paterno
		$sFilterList = ew_Concat($sFilterList, $this->nrodiscapacidad->AdvancedSearch->ToJson(), ","); // Field nrodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->ci->AdvancedSearch->ToJson(), ","); // Field ci
		$sFilterList = ew_Concat($sFilterList, $this->fechanacimiento->AdvancedSearch->ToJson(), ","); // Field fechanacimiento
		$sFilterList = ew_Concat($sFilterList, $this->edad->AdvancedSearch->ToJson(), ","); // Field edad
		$sFilterList = ew_Concat($sFilterList, $this->sexo->AdvancedSearch->ToJson(), ","); // Field sexo
		$sFilterList = ew_Concat($sFilterList, $this->curso->AdvancedSearch->ToJson(), ","); // Field curso
		$sFilterList = ew_Concat($sFilterList, $this->discapacidad->AdvancedSearch->ToJson(), ","); // Field discapacidad
		$sFilterList = ew_Concat($sFilterList, $this->tipodiscapcidad->AdvancedSearch->ToJson(), ","); // Field tipodiscapcidad
		$sFilterList = ew_Concat($sFilterList, $this->nombreinstitucion->AdvancedSearch->ToJson(), ","); // Field nombreinstitucion
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fviewestudiantelistsrch", $filters);

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

		// Field departamento
		$this->departamento->AdvancedSearch->SearchValue = @$filter["x_departamento"];
		$this->departamento->AdvancedSearch->SearchOperator = @$filter["z_departamento"];
		$this->departamento->AdvancedSearch->SearchCondition = @$filter["v_departamento"];
		$this->departamento->AdvancedSearch->SearchValue2 = @$filter["y_departamento"];
		$this->departamento->AdvancedSearch->SearchOperator2 = @$filter["w_departamento"];
		$this->departamento->AdvancedSearch->Save();

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

		// Field municipio
		$this->municipio->AdvancedSearch->SearchValue = @$filter["x_municipio"];
		$this->municipio->AdvancedSearch->SearchOperator = @$filter["z_municipio"];
		$this->municipio->AdvancedSearch->SearchCondition = @$filter["v_municipio"];
		$this->municipio->AdvancedSearch->SearchValue2 = @$filter["y_municipio"];
		$this->municipio->AdvancedSearch->SearchOperator2 = @$filter["w_municipio"];
		$this->municipio->AdvancedSearch->Save();

		// Field provincia
		$this->provincia->AdvancedSearch->SearchValue = @$filter["x_provincia"];
		$this->provincia->AdvancedSearch->SearchOperator = @$filter["z_provincia"];
		$this->provincia->AdvancedSearch->SearchCondition = @$filter["v_provincia"];
		$this->provincia->AdvancedSearch->SearchValue2 = @$filter["y_provincia"];
		$this->provincia->AdvancedSearch->SearchOperator2 = @$filter["w_provincia"];
		$this->provincia->AdvancedSearch->Save();

		// Field unidadeducativa
		$this->unidadeducativa->AdvancedSearch->SearchValue = @$filter["x_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->SearchOperator = @$filter["z_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->SearchCondition = @$filter["v_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->SearchValue2 = @$filter["y_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->SearchOperator2 = @$filter["w_unidadeducativa"];
		$this->unidadeducativa->AdvancedSearch->Save();

		// Field nombre
		$this->nombre->AdvancedSearch->SearchValue = @$filter["x_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator = @$filter["z_nombre"];
		$this->nombre->AdvancedSearch->SearchCondition = @$filter["v_nombre"];
		$this->nombre->AdvancedSearch->SearchValue2 = @$filter["y_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator2 = @$filter["w_nombre"];
		$this->nombre->AdvancedSearch->Save();

		// Field materno
		$this->materno->AdvancedSearch->SearchValue = @$filter["x_materno"];
		$this->materno->AdvancedSearch->SearchOperator = @$filter["z_materno"];
		$this->materno->AdvancedSearch->SearchCondition = @$filter["v_materno"];
		$this->materno->AdvancedSearch->SearchValue2 = @$filter["y_materno"];
		$this->materno->AdvancedSearch->SearchOperator2 = @$filter["w_materno"];
		$this->materno->AdvancedSearch->Save();

		// Field paterno
		$this->paterno->AdvancedSearch->SearchValue = @$filter["x_paterno"];
		$this->paterno->AdvancedSearch->SearchOperator = @$filter["z_paterno"];
		$this->paterno->AdvancedSearch->SearchCondition = @$filter["v_paterno"];
		$this->paterno->AdvancedSearch->SearchValue2 = @$filter["y_paterno"];
		$this->paterno->AdvancedSearch->SearchOperator2 = @$filter["w_paterno"];
		$this->paterno->AdvancedSearch->Save();

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

		// Field edad
		$this->edad->AdvancedSearch->SearchValue = @$filter["x_edad"];
		$this->edad->AdvancedSearch->SearchOperator = @$filter["z_edad"];
		$this->edad->AdvancedSearch->SearchCondition = @$filter["v_edad"];
		$this->edad->AdvancedSearch->SearchValue2 = @$filter["y_edad"];
		$this->edad->AdvancedSearch->SearchOperator2 = @$filter["w_edad"];
		$this->edad->AdvancedSearch->Save();

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

		// Field tipodiscapcidad
		$this->tipodiscapcidad->AdvancedSearch->SearchValue = @$filter["x_tipodiscapcidad"];
		$this->tipodiscapcidad->AdvancedSearch->SearchOperator = @$filter["z_tipodiscapcidad"];
		$this->tipodiscapcidad->AdvancedSearch->SearchCondition = @$filter["v_tipodiscapcidad"];
		$this->tipodiscapcidad->AdvancedSearch->SearchValue2 = @$filter["y_tipodiscapcidad"];
		$this->tipodiscapcidad->AdvancedSearch->SearchOperator2 = @$filter["w_tipodiscapcidad"];
		$this->tipodiscapcidad->AdvancedSearch->Save();

		// Field nombreinstitucion
		$this->nombreinstitucion->AdvancedSearch->SearchValue = @$filter["x_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->SearchOperator = @$filter["z_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->SearchCondition = @$filter["v_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->SearchValue2 = @$filter["y_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->SearchOperator2 = @$filter["w_nombreinstitucion"];
		$this->nombreinstitucion->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->departamento, $Default, FALSE); // departamento
		$this->BuildSearchSql($sWhere, $this->codigorude, $Default, FALSE); // codigorude
		$this->BuildSearchSql($sWhere, $this->codigorude_es, $Default, FALSE); // codigorude_es
		$this->BuildSearchSql($sWhere, $this->municipio, $Default, FALSE); // municipio
		$this->BuildSearchSql($sWhere, $this->provincia, $Default, FALSE); // provincia
		$this->BuildSearchSql($sWhere, $this->unidadeducativa, $Default, FALSE); // unidadeducativa
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->materno, $Default, FALSE); // materno
		$this->BuildSearchSql($sWhere, $this->paterno, $Default, FALSE); // paterno
		$this->BuildSearchSql($sWhere, $this->nrodiscapacidad, $Default, FALSE); // nrodiscapacidad
		$this->BuildSearchSql($sWhere, $this->ci, $Default, FALSE); // ci
		$this->BuildSearchSql($sWhere, $this->fechanacimiento, $Default, FALSE); // fechanacimiento
		$this->BuildSearchSql($sWhere, $this->edad, $Default, FALSE); // edad
		$this->BuildSearchSql($sWhere, $this->sexo, $Default, FALSE); // sexo
		$this->BuildSearchSql($sWhere, $this->curso, $Default, FALSE); // curso
		$this->BuildSearchSql($sWhere, $this->discapacidad, $Default, FALSE); // discapacidad
		$this->BuildSearchSql($sWhere, $this->tipodiscapcidad, $Default, FALSE); // tipodiscapcidad
		$this->BuildSearchSql($sWhere, $this->nombreinstitucion, $Default, FALSE); // nombreinstitucion

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->departamento->AdvancedSearch->Save(); // departamento
			$this->codigorude->AdvancedSearch->Save(); // codigorude
			$this->codigorude_es->AdvancedSearch->Save(); // codigorude_es
			$this->municipio->AdvancedSearch->Save(); // municipio
			$this->provincia->AdvancedSearch->Save(); // provincia
			$this->unidadeducativa->AdvancedSearch->Save(); // unidadeducativa
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->materno->AdvancedSearch->Save(); // materno
			$this->paterno->AdvancedSearch->Save(); // paterno
			$this->nrodiscapacidad->AdvancedSearch->Save(); // nrodiscapacidad
			$this->ci->AdvancedSearch->Save(); // ci
			$this->fechanacimiento->AdvancedSearch->Save(); // fechanacimiento
			$this->edad->AdvancedSearch->Save(); // edad
			$this->sexo->AdvancedSearch->Save(); // sexo
			$this->curso->AdvancedSearch->Save(); // curso
			$this->discapacidad->AdvancedSearch->Save(); // discapacidad
			$this->tipodiscapcidad->AdvancedSearch->Save(); // tipodiscapcidad
			$this->nombreinstitucion->AdvancedSearch->Save(); // nombreinstitucion
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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->departamento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->codigorude, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->codigorude_es, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->provincia, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->unidadeducativa, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->materno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->paterno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nrodiscapacidad, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ci, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->curso, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->discapacidad, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipodiscapcidad, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->departamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigorude->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigorude_es->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->municipio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->provincia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->unidadeducativa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->materno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->paterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nrodiscapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ci->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechanacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->edad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->curso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->discapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipodiscapcidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombreinstitucion->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->departamento->AdvancedSearch->UnsetSession();
		$this->codigorude->AdvancedSearch->UnsetSession();
		$this->codigorude_es->AdvancedSearch->UnsetSession();
		$this->municipio->AdvancedSearch->UnsetSession();
		$this->provincia->AdvancedSearch->UnsetSession();
		$this->unidadeducativa->AdvancedSearch->UnsetSession();
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->materno->AdvancedSearch->UnsetSession();
		$this->paterno->AdvancedSearch->UnsetSession();
		$this->nrodiscapacidad->AdvancedSearch->UnsetSession();
		$this->ci->AdvancedSearch->UnsetSession();
		$this->fechanacimiento->AdvancedSearch->UnsetSession();
		$this->edad->AdvancedSearch->UnsetSession();
		$this->sexo->AdvancedSearch->UnsetSession();
		$this->curso->AdvancedSearch->UnsetSession();
		$this->discapacidad->AdvancedSearch->UnsetSession();
		$this->tipodiscapcidad->AdvancedSearch->UnsetSession();
		$this->nombreinstitucion->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->departamento->AdvancedSearch->Load();
		$this->codigorude->AdvancedSearch->Load();
		$this->codigorude_es->AdvancedSearch->Load();
		$this->municipio->AdvancedSearch->Load();
		$this->provincia->AdvancedSearch->Load();
		$this->unidadeducativa->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->materno->AdvancedSearch->Load();
		$this->paterno->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fechanacimiento->AdvancedSearch->Load();
		$this->edad->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->curso->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->tipodiscapcidad->AdvancedSearch->Load();
		$this->nombreinstitucion->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->departamento, $bCtrl); // departamento
			$this->UpdateSort($this->codigorude, $bCtrl); // codigorude
			$this->UpdateSort($this->codigorude_es, $bCtrl); // codigorude_es
			$this->UpdateSort($this->municipio, $bCtrl); // municipio
			$this->UpdateSort($this->provincia, $bCtrl); // provincia
			$this->UpdateSort($this->unidadeducativa, $bCtrl); // unidadeducativa
			$this->UpdateSort($this->nombre, $bCtrl); // nombre
			$this->UpdateSort($this->materno, $bCtrl); // materno
			$this->UpdateSort($this->paterno, $bCtrl); // paterno
			$this->UpdateSort($this->nrodiscapacidad, $bCtrl); // nrodiscapacidad
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->fechanacimiento, $bCtrl); // fechanacimiento
			$this->UpdateSort($this->edad, $bCtrl); // edad
			$this->UpdateSort($this->sexo, $bCtrl); // sexo
			$this->UpdateSort($this->curso, $bCtrl); // curso
			$this->UpdateSort($this->discapacidad, $bCtrl); // discapacidad
			$this->UpdateSort($this->tipodiscapcidad, $bCtrl); // tipodiscapcidad
			$this->UpdateSort($this->nombreinstitucion, $bCtrl); // nombreinstitucion
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
				$this->departamento->setSort("");
				$this->codigorude->setSort("");
				$this->codigorude_es->setSort("");
				$this->municipio->setSort("");
				$this->provincia->setSort("");
				$this->unidadeducativa->setSort("");
				$this->nombre->setSort("");
				$this->materno->setSort("");
				$this->paterno->setSort("");
				$this->nrodiscapacidad->setSort("");
				$this->ci->setSort("");
				$this->fechanacimiento->setSort("");
				$this->edad->setSort("");
				$this->sexo->setSort("");
				$this->curso->setSort("");
				$this->discapacidad->setSort("");
				$this->tipodiscapcidad->setSort("");
				$this->nombreinstitucion->setSort("");
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
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewestudiantelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewestudiantelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fviewestudiantelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fviewestudiantelistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// departamento

		$this->departamento->AdvancedSearch->SearchValue = @$_GET["x_departamento"];
		if ($this->departamento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->departamento->AdvancedSearch->SearchOperator = @$_GET["z_departamento"];

		// codigorude
		$this->codigorude->AdvancedSearch->SearchValue = @$_GET["x_codigorude"];
		if ($this->codigorude->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->codigorude->AdvancedSearch->SearchOperator = @$_GET["z_codigorude"];

		// codigorude_es
		$this->codigorude_es->AdvancedSearch->SearchValue = @$_GET["x_codigorude_es"];
		if ($this->codigorude_es->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->codigorude_es->AdvancedSearch->SearchOperator = @$_GET["z_codigorude_es"];

		// municipio
		$this->municipio->AdvancedSearch->SearchValue = @$_GET["x_municipio"];
		if ($this->municipio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->municipio->AdvancedSearch->SearchOperator = @$_GET["z_municipio"];

		// provincia
		$this->provincia->AdvancedSearch->SearchValue = @$_GET["x_provincia"];
		if ($this->provincia->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->provincia->AdvancedSearch->SearchOperator = @$_GET["z_provincia"];

		// unidadeducativa
		$this->unidadeducativa->AdvancedSearch->SearchValue = @$_GET["x_unidadeducativa"];
		if ($this->unidadeducativa->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->unidadeducativa->AdvancedSearch->SearchOperator = @$_GET["z_unidadeducativa"];

		// nombre
		$this->nombre->AdvancedSearch->SearchValue = @$_GET["x_nombre"];
		if ($this->nombre->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombre->AdvancedSearch->SearchOperator = @$_GET["z_nombre"];

		// materno
		$this->materno->AdvancedSearch->SearchValue = @$_GET["x_materno"];
		if ($this->materno->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->materno->AdvancedSearch->SearchOperator = @$_GET["z_materno"];

		// paterno
		$this->paterno->AdvancedSearch->SearchValue = @$_GET["x_paterno"];
		if ($this->paterno->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->paterno->AdvancedSearch->SearchOperator = @$_GET["z_paterno"];

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

		// edad
		$this->edad->AdvancedSearch->SearchValue = @$_GET["x_edad"];
		if ($this->edad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->edad->AdvancedSearch->SearchOperator = @$_GET["z_edad"];

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

		// tipodiscapcidad
		$this->tipodiscapcidad->AdvancedSearch->SearchValue = @$_GET["x_tipodiscapcidad"];
		if ($this->tipodiscapcidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipodiscapcidad->AdvancedSearch->SearchOperator = @$_GET["z_tipodiscapcidad"];

		// nombreinstitucion
		$this->nombreinstitucion->AdvancedSearch->SearchValue = @$_GET["x_nombreinstitucion"];
		if ($this->nombreinstitucion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombreinstitucion->AdvancedSearch->SearchOperator = @$_GET["z_nombreinstitucion"];
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
		$this->departamento->setDbValue($row['departamento']);
		$this->codigorude->setDbValue($row['codigorude']);
		$this->codigorude_es->setDbValue($row['codigorude_es']);
		$this->municipio->setDbValue($row['municipio']);
		$this->provincia->setDbValue($row['provincia']);
		$this->unidadeducativa->setDbValue($row['unidadeducativa']);
		$this->nombre->setDbValue($row['nombre']);
		$this->materno->setDbValue($row['materno']);
		$this->paterno->setDbValue($row['paterno']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->edad->setDbValue($row['edad']);
		$this->sexo->setDbValue($row['sexo']);
		$this->curso->setDbValue($row['curso']);
		$this->discapacidad->setDbValue($row['discapacidad']);
		$this->tipodiscapcidad->setDbValue($row['tipodiscapcidad']);
		$this->nombreinstitucion->setDbValue($row['nombreinstitucion']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['departamento'] = NULL;
		$row['codigorude'] = NULL;
		$row['codigorude_es'] = NULL;
		$row['municipio'] = NULL;
		$row['provincia'] = NULL;
		$row['unidadeducativa'] = NULL;
		$row['nombre'] = NULL;
		$row['materno'] = NULL;
		$row['paterno'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['edad'] = NULL;
		$row['sexo'] = NULL;
		$row['curso'] = NULL;
		$row['discapacidad'] = NULL;
		$row['tipodiscapcidad'] = NULL;
		$row['nombreinstitucion'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->departamento->DbValue = $row['departamento'];
		$this->codigorude->DbValue = $row['codigorude'];
		$this->codigorude_es->DbValue = $row['codigorude_es'];
		$this->municipio->DbValue = $row['municipio'];
		$this->provincia->DbValue = $row['provincia'];
		$this->unidadeducativa->DbValue = $row['unidadeducativa'];
		$this->nombre->DbValue = $row['nombre'];
		$this->materno->DbValue = $row['materno'];
		$this->paterno->DbValue = $row['paterno'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->edad->DbValue = $row['edad'];
		$this->sexo->DbValue = $row['sexo'];
		$this->curso->DbValue = $row['curso'];
		$this->discapacidad->DbValue = $row['discapacidad'];
		$this->tipodiscapcidad->DbValue = $row['tipodiscapcidad'];
		$this->nombreinstitucion->DbValue = $row['nombreinstitucion'];
	}

	// Load old record
	function LoadOldRecord() {
		return FALSE;
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
		// departamento
		// codigorude
		// codigorude_es
		// municipio
		// provincia
		// unidadeducativa
		// nombre
		// materno
		// paterno
		// nrodiscapacidad
		// ci
		// fechanacimiento
		// edad
		// sexo
		// curso
		// discapacidad
		// tipodiscapcidad
		// nombreinstitucion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// departamento
		$this->departamento->ViewValue = $this->departamento->CurrentValue;
		$this->departamento->ViewCustomAttributes = "";

		// codigorude
		$this->codigorude->ViewValue = $this->codigorude->CurrentValue;
		$this->codigorude->ViewCustomAttributes = "";

		// codigorude_es
		$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
		$this->codigorude_es->ViewCustomAttributes = "";

		// municipio
		$this->municipio->ViewValue = $this->municipio->CurrentValue;
		$this->municipio->ViewCustomAttributes = "";

		// provincia
		$this->provincia->ViewValue = $this->provincia->CurrentValue;
		$this->provincia->ViewCustomAttributes = "";

		// unidadeducativa
		$this->unidadeducativa->ViewValue = $this->unidadeducativa->CurrentValue;
		$this->unidadeducativa->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// materno
		$this->materno->ViewValue = $this->materno->CurrentValue;
		$this->materno->ViewCustomAttributes = "";

		// paterno
		$this->paterno->ViewValue = $this->paterno->CurrentValue;
		$this->paterno->ViewCustomAttributes = "";

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

		// edad
		$this->edad->ViewValue = $this->edad->CurrentValue;
		$this->edad->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// curso
		$this->curso->ViewValue = $this->curso->CurrentValue;
		$this->curso->ViewCustomAttributes = "";

		// discapacidad
		$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->ViewCustomAttributes = "";

		// tipodiscapcidad
		$this->tipodiscapcidad->ViewValue = $this->tipodiscapcidad->CurrentValue;
		$this->tipodiscapcidad->ViewCustomAttributes = "";

		// nombreinstitucion
		$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
		$this->nombreinstitucion->ViewCustomAttributes = "";

			// departamento
			$this->departamento->LinkCustomAttributes = "";
			$this->departamento->HrefValue = "";
			$this->departamento->TooltipValue = "";

			// codigorude
			$this->codigorude->LinkCustomAttributes = "";
			$this->codigorude->HrefValue = "";
			$this->codigorude->TooltipValue = "";

			// codigorude_es
			$this->codigorude_es->LinkCustomAttributes = "";
			$this->codigorude_es->HrefValue = "";
			$this->codigorude_es->TooltipValue = "";

			// municipio
			$this->municipio->LinkCustomAttributes = "";
			$this->municipio->HrefValue = "";
			$this->municipio->TooltipValue = "";

			// provincia
			$this->provincia->LinkCustomAttributes = "";
			$this->provincia->HrefValue = "";
			$this->provincia->TooltipValue = "";

			// unidadeducativa
			$this->unidadeducativa->LinkCustomAttributes = "";
			$this->unidadeducativa->HrefValue = "";
			$this->unidadeducativa->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// materno
			$this->materno->LinkCustomAttributes = "";
			$this->materno->HrefValue = "";
			$this->materno->TooltipValue = "";

			// paterno
			$this->paterno->LinkCustomAttributes = "";
			$this->paterno->HrefValue = "";
			$this->paterno->TooltipValue = "";

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

			// edad
			$this->edad->LinkCustomAttributes = "";
			$this->edad->HrefValue = "";
			$this->edad->TooltipValue = "";

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

			// tipodiscapcidad
			$this->tipodiscapcidad->LinkCustomAttributes = "";
			$this->tipodiscapcidad->HrefValue = "";
			$this->tipodiscapcidad->TooltipValue = "";

			// nombreinstitucion
			$this->nombreinstitucion->LinkCustomAttributes = "";
			$this->nombreinstitucion->HrefValue = "";
			$this->nombreinstitucion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// departamento
			$this->departamento->EditAttrs["class"] = "form-control";
			$this->departamento->EditCustomAttributes = "";
			$this->departamento->EditValue = ew_HtmlEncode($this->departamento->AdvancedSearch->SearchValue);
			$this->departamento->PlaceHolder = ew_RemoveHtml($this->departamento->FldCaption());

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

			// municipio
			$this->municipio->EditAttrs["class"] = "form-control";
			$this->municipio->EditCustomAttributes = "";
			$this->municipio->EditValue = ew_HtmlEncode($this->municipio->AdvancedSearch->SearchValue);
			$this->municipio->PlaceHolder = ew_RemoveHtml($this->municipio->FldCaption());

			// provincia
			$this->provincia->EditAttrs["class"] = "form-control";
			$this->provincia->EditCustomAttributes = "";
			$this->provincia->EditValue = ew_HtmlEncode($this->provincia->AdvancedSearch->SearchValue);
			$this->provincia->PlaceHolder = ew_RemoveHtml($this->provincia->FldCaption());

			// unidadeducativa
			$this->unidadeducativa->EditAttrs["class"] = "form-control";
			$this->unidadeducativa->EditCustomAttributes = "";
			$this->unidadeducativa->EditValue = ew_HtmlEncode($this->unidadeducativa->AdvancedSearch->SearchValue);
			$this->unidadeducativa->PlaceHolder = ew_RemoveHtml($this->unidadeducativa->FldCaption());

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->AdvancedSearch->SearchValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// materno
			$this->materno->EditAttrs["class"] = "form-control";
			$this->materno->EditCustomAttributes = "";
			$this->materno->EditValue = ew_HtmlEncode($this->materno->AdvancedSearch->SearchValue);
			$this->materno->PlaceHolder = ew_RemoveHtml($this->materno->FldCaption());

			// paterno
			$this->paterno->EditAttrs["class"] = "form-control";
			$this->paterno->EditCustomAttributes = "";
			$this->paterno->EditValue = ew_HtmlEncode($this->paterno->AdvancedSearch->SearchValue);
			$this->paterno->PlaceHolder = ew_RemoveHtml($this->paterno->FldCaption());

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

			// edad
			$this->edad->EditAttrs["class"] = "form-control";
			$this->edad->EditCustomAttributes = "";
			$this->edad->EditValue = ew_HtmlEncode($this->edad->AdvancedSearch->SearchValue);
			$this->edad->PlaceHolder = ew_RemoveHtml($this->edad->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// curso
			$this->curso->EditAttrs["class"] = "form-control";
			$this->curso->EditCustomAttributes = "";
			$this->curso->EditValue = ew_HtmlEncode($this->curso->AdvancedSearch->SearchValue);
			$this->curso->PlaceHolder = ew_RemoveHtml($this->curso->FldCaption());

			// discapacidad
			$this->discapacidad->EditAttrs["class"] = "form-control";
			$this->discapacidad->EditCustomAttributes = "";
			$this->discapacidad->EditValue = ew_HtmlEncode($this->discapacidad->AdvancedSearch->SearchValue);
			$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

			// tipodiscapcidad
			$this->tipodiscapcidad->EditAttrs["class"] = "form-control";
			$this->tipodiscapcidad->EditCustomAttributes = "";
			$this->tipodiscapcidad->EditValue = ew_HtmlEncode($this->tipodiscapcidad->AdvancedSearch->SearchValue);
			$this->tipodiscapcidad->PlaceHolder = ew_RemoveHtml($this->tipodiscapcidad->FldCaption());

			// nombreinstitucion
			$this->nombreinstitucion->EditAttrs["class"] = "form-control";
			$this->nombreinstitucion->EditCustomAttributes = "";
			$this->nombreinstitucion->EditValue = ew_HtmlEncode($this->nombreinstitucion->AdvancedSearch->SearchValue);
			$this->nombreinstitucion->PlaceHolder = ew_RemoveHtml($this->nombreinstitucion->FldCaption());
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
		$this->departamento->AdvancedSearch->Load();
		$this->codigorude->AdvancedSearch->Load();
		$this->codigorude_es->AdvancedSearch->Load();
		$this->municipio->AdvancedSearch->Load();
		$this->provincia->AdvancedSearch->Load();
		$this->unidadeducativa->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->materno->AdvancedSearch->Load();
		$this->paterno->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fechanacimiento->AdvancedSearch->Load();
		$this->edad->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->curso->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->tipodiscapcidad->AdvancedSearch->Load();
		$this->nombreinstitucion->AdvancedSearch->Load();
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
if (!isset($viewestudiante_list)) $viewestudiante_list = new cviewestudiante_list();

// Page init
$viewestudiante_list->Page_Init();

// Page main
$viewestudiante_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$viewestudiante_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fviewestudiantelist = new ew_Form("fviewestudiantelist", "list");
fviewestudiantelist.FormKeyCountName = '<?php echo $viewestudiante_list->FormKeyCountName ?>';

// Form_CustomValidate event
fviewestudiantelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fviewestudiantelist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fviewestudiantelist.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewestudiantelist.Lists["x_sexo"].Options = <?php echo json_encode($viewestudiante_list->sexo->Options()) ?>;

// Form object for search
var CurrentSearchForm = fviewestudiantelistsrch = new ew_Form("fviewestudiantelistsrch");

// Validate function for search
fviewestudiantelistsrch.Validate = function(fobj) {
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
fviewestudiantelistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fviewestudiantelistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($viewestudiante_list->TotalRecs > 0 && $viewestudiante_list->ExportOptions->Visible()) { ?>
<?php $viewestudiante_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($viewestudiante_list->SearchOptions->Visible()) { ?>
<?php $viewestudiante_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($viewestudiante_list->FilterOptions->Visible()) { ?>
<?php $viewestudiante_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $viewestudiante_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($viewestudiante_list->TotalRecs <= 0)
			$viewestudiante_list->TotalRecs = $viewestudiante->ListRecordCount();
	} else {
		if (!$viewestudiante_list->Recordset && ($viewestudiante_list->Recordset = $viewestudiante_list->LoadRecordset()))
			$viewestudiante_list->TotalRecs = $viewestudiante_list->Recordset->RecordCount();
	}
	$viewestudiante_list->StartRec = 1;
	if ($viewestudiante_list->DisplayRecs <= 0 || ($viewestudiante->Export <> "" && $viewestudiante->ExportAll)) // Display all records
		$viewestudiante_list->DisplayRecs = $viewestudiante_list->TotalRecs;
	if (!($viewestudiante->Export <> "" && $viewestudiante->ExportAll))
		$viewestudiante_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$viewestudiante_list->Recordset = $viewestudiante_list->LoadRecordset($viewestudiante_list->StartRec-1, $viewestudiante_list->DisplayRecs);

	// Set no record found message
	if ($viewestudiante->CurrentAction == "" && $viewestudiante_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$viewestudiante_list->setWarningMessage(ew_DeniedMsg());
		if ($viewestudiante_list->SearchWhere == "0=101")
			$viewestudiante_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$viewestudiante_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$viewestudiante_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($viewestudiante->Export == "" && $viewestudiante->CurrentAction == "") { ?>
<form name="fviewestudiantelistsrch" id="fviewestudiantelistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($viewestudiante_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fviewestudiantelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="viewestudiante">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$viewestudiante_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$viewestudiante->RowType = EW_ROWTYPE_SEARCH;

// Render row
$viewestudiante->ResetAttrs();
$viewestudiante_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($viewestudiante->codigorude->Visible) { // codigorude ?>
	<div id="xsc_codigorude" class="ewCell form-group">
		<label for="x_codigorude" class="ewSearchCaption ewLabel"><?php echo $viewestudiante->codigorude->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_codigorude" id="z_codigorude" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="viewestudiante" data-field="x_codigorude" name="x_codigorude" id="x_codigorude" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($viewestudiante->codigorude->getPlaceHolder()) ?>" value="<?php echo $viewestudiante->codigorude->EditValue ?>"<?php echo $viewestudiante->codigorude->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($viewestudiante->codigorude_es->Visible) { // codigorude_es ?>
	<div id="xsc_codigorude_es" class="ewCell form-group">
		<label for="x_codigorude_es" class="ewSearchCaption ewLabel"><?php echo $viewestudiante->codigorude_es->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_codigorude_es" id="z_codigorude_es" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="viewestudiante" data-field="x_codigorude_es" name="x_codigorude_es" id="x_codigorude_es" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($viewestudiante->codigorude_es->getPlaceHolder()) ?>" value="<?php echo $viewestudiante->codigorude_es->EditValue ?>"<?php echo $viewestudiante->codigorude_es->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($viewestudiante->unidadeducativa->Visible) { // unidadeducativa ?>
	<div id="xsc_unidadeducativa" class="ewCell form-group">
		<label for="x_unidadeducativa" class="ewSearchCaption ewLabel"><?php echo $viewestudiante->unidadeducativa->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_unidadeducativa" id="z_unidadeducativa" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="viewestudiante" data-field="x_unidadeducativa" name="x_unidadeducativa" id="x_unidadeducativa" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($viewestudiante->unidadeducativa->getPlaceHolder()) ?>" value="<?php echo $viewestudiante->unidadeducativa->EditValue ?>"<?php echo $viewestudiante->unidadeducativa->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($viewestudiante->nombre->Visible) { // nombre ?>
	<div id="xsc_nombre" class="ewCell form-group">
		<label for="x_nombre" class="ewSearchCaption ewLabel"><?php echo $viewestudiante->nombre->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombre" id="z_nombre" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="viewestudiante" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($viewestudiante->nombre->getPlaceHolder()) ?>" value="<?php echo $viewestudiante->nombre->EditValue ?>"<?php echo $viewestudiante->nombre->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($viewestudiante->materno->Visible) { // materno ?>
	<div id="xsc_materno" class="ewCell form-group">
		<label for="x_materno" class="ewSearchCaption ewLabel"><?php echo $viewestudiante->materno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_materno" id="z_materno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="viewestudiante" data-field="x_materno" name="x_materno" id="x_materno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($viewestudiante->materno->getPlaceHolder()) ?>" value="<?php echo $viewestudiante->materno->EditValue ?>"<?php echo $viewestudiante->materno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($viewestudiante->paterno->Visible) { // paterno ?>
	<div id="xsc_paterno" class="ewCell form-group">
		<label for="x_paterno" class="ewSearchCaption ewLabel"><?php echo $viewestudiante->paterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_paterno" id="z_paterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="viewestudiante" data-field="x_paterno" name="x_paterno" id="x_paterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($viewestudiante->paterno->getPlaceHolder()) ?>" value="<?php echo $viewestudiante->paterno->EditValue ?>"<?php echo $viewestudiante->paterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($viewestudiante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="xsc_nrodiscapacidad" class="ewCell form-group">
		<label for="x_nrodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $viewestudiante->nrodiscapacidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nrodiscapacidad" id="z_nrodiscapacidad" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="viewestudiante" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($viewestudiante->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $viewestudiante->nrodiscapacidad->EditValue ?>"<?php echo $viewestudiante->nrodiscapacidad->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
<?php if ($viewestudiante->nombreinstitucion->Visible) { // nombreinstitucion ?>
	<div id="xsc_nombreinstitucion" class="ewCell form-group">
		<label for="x_nombreinstitucion" class="ewSearchCaption ewLabel"><?php echo $viewestudiante->nombreinstitucion->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombreinstitucion" id="z_nombreinstitucion" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="viewestudiante" data-field="x_nombreinstitucion" name="x_nombreinstitucion" id="x_nombreinstitucion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($viewestudiante->nombreinstitucion->getPlaceHolder()) ?>" value="<?php echo $viewestudiante->nombreinstitucion->EditValue ?>"<?php echo $viewestudiante->nombreinstitucion->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_9" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($viewestudiante_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($viewestudiante_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $viewestudiante_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($viewestudiante_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($viewestudiante_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($viewestudiante_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($viewestudiante_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $viewestudiante_list->ShowPageHeader(); ?>
<?php
$viewestudiante_list->ShowMessage();
?>
<?php if ($viewestudiante_list->TotalRecs > 0 || $viewestudiante->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($viewestudiante_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> viewestudiante">
<div class="box-header ewGridUpperPanel">
<?php if ($viewestudiante->CurrentAction <> "gridadd" && $viewestudiante->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($viewestudiante_list->Pager)) $viewestudiante_list->Pager = new cPrevNextPager($viewestudiante_list->StartRec, $viewestudiante_list->DisplayRecs, $viewestudiante_list->TotalRecs, $viewestudiante_list->AutoHidePager) ?>
<?php if ($viewestudiante_list->Pager->RecordCount > 0 && $viewestudiante_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($viewestudiante_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $viewestudiante_list->PageUrl() ?>start=<?php echo $viewestudiante_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($viewestudiante_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $viewestudiante_list->PageUrl() ?>start=<?php echo $viewestudiante_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $viewestudiante_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($viewestudiante_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $viewestudiante_list->PageUrl() ?>start=<?php echo $viewestudiante_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($viewestudiante_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $viewestudiante_list->PageUrl() ?>start=<?php echo $viewestudiante_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $viewestudiante_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($viewestudiante_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $viewestudiante_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $viewestudiante_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $viewestudiante_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($viewestudiante_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fviewestudiantelist" id="fviewestudiantelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($viewestudiante_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $viewestudiante_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="viewestudiante">
<div id="gmp_viewestudiante" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($viewestudiante_list->TotalRecs > 0 || $viewestudiante->CurrentAction == "gridedit") { ?>
<table id="tbl_viewestudiantelist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$viewestudiante_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$viewestudiante_list->RenderListOptions();

// Render list options (header, left)
$viewestudiante_list->ListOptions->Render("header", "left");
?>
<?php if ($viewestudiante->departamento->Visible) { // departamento ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->departamento) == "") { ?>
		<th data-name="departamento" class="<?php echo $viewestudiante->departamento->HeaderCellClass() ?>"><div id="elh_viewestudiante_departamento" class="viewestudiante_departamento"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departamento" class="<?php echo $viewestudiante->departamento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->departamento) ?>',2);"><div id="elh_viewestudiante_departamento" class="viewestudiante_departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->departamento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->codigorude->Visible) { // codigorude ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->codigorude) == "") { ?>
		<th data-name="codigorude" class="<?php echo $viewestudiante->codigorude->HeaderCellClass() ?>"><div id="elh_viewestudiante_codigorude" class="viewestudiante_codigorude"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->codigorude->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigorude" class="<?php echo $viewestudiante->codigorude->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->codigorude) ?>',2);"><div id="elh_viewestudiante_codigorude" class="viewestudiante_codigorude">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->codigorude->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->codigorude->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->codigorude->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->codigorude_es->Visible) { // codigorude_es ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->codigorude_es) == "") { ?>
		<th data-name="codigorude_es" class="<?php echo $viewestudiante->codigorude_es->HeaderCellClass() ?>"><div id="elh_viewestudiante_codigorude_es" class="viewestudiante_codigorude_es"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->codigorude_es->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigorude_es" class="<?php echo $viewestudiante->codigorude_es->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->codigorude_es) ?>',2);"><div id="elh_viewestudiante_codigorude_es" class="viewestudiante_codigorude_es">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->codigorude_es->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->codigorude_es->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->codigorude_es->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->municipio->Visible) { // municipio ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->municipio) == "") { ?>
		<th data-name="municipio" class="<?php echo $viewestudiante->municipio->HeaderCellClass() ?>"><div id="elh_viewestudiante_municipio" class="viewestudiante_municipio"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->municipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="municipio" class="<?php echo $viewestudiante->municipio->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->municipio) ?>',2);"><div id="elh_viewestudiante_municipio" class="viewestudiante_municipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->municipio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->municipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->municipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->provincia->Visible) { // provincia ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->provincia) == "") { ?>
		<th data-name="provincia" class="<?php echo $viewestudiante->provincia->HeaderCellClass() ?>"><div id="elh_viewestudiante_provincia" class="viewestudiante_provincia"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->provincia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="provincia" class="<?php echo $viewestudiante->provincia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->provincia) ?>',2);"><div id="elh_viewestudiante_provincia" class="viewestudiante_provincia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->provincia->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->provincia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->provincia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->unidadeducativa->Visible) { // unidadeducativa ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->unidadeducativa) == "") { ?>
		<th data-name="unidadeducativa" class="<?php echo $viewestudiante->unidadeducativa->HeaderCellClass() ?>"><div id="elh_viewestudiante_unidadeducativa" class="viewestudiante_unidadeducativa"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->unidadeducativa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="unidadeducativa" class="<?php echo $viewestudiante->unidadeducativa->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->unidadeducativa) ?>',2);"><div id="elh_viewestudiante_unidadeducativa" class="viewestudiante_unidadeducativa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->unidadeducativa->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->unidadeducativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->unidadeducativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->nombre->Visible) { // nombre ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->nombre) == "") { ?>
		<th data-name="nombre" class="<?php echo $viewestudiante->nombre->HeaderCellClass() ?>"><div id="elh_viewestudiante_nombre" class="viewestudiante_nombre"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre" class="<?php echo $viewestudiante->nombre->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->nombre) ?>',2);"><div id="elh_viewestudiante_nombre" class="viewestudiante_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->materno->Visible) { // materno ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->materno) == "") { ?>
		<th data-name="materno" class="<?php echo $viewestudiante->materno->HeaderCellClass() ?>"><div id="elh_viewestudiante_materno" class="viewestudiante_materno"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="materno" class="<?php echo $viewestudiante->materno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->materno) ?>',2);"><div id="elh_viewestudiante_materno" class="viewestudiante_materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->materno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->paterno->Visible) { // paterno ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->paterno) == "") { ?>
		<th data-name="paterno" class="<?php echo $viewestudiante->paterno->HeaderCellClass() ?>"><div id="elh_viewestudiante_paterno" class="viewestudiante_paterno"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="paterno" class="<?php echo $viewestudiante->paterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->paterno) ?>',2);"><div id="elh_viewestudiante_paterno" class="viewestudiante_paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->paterno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->nrodiscapacidad) == "") { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $viewestudiante->nrodiscapacidad->HeaderCellClass() ?>"><div id="elh_viewestudiante_nrodiscapacidad" class="viewestudiante_nrodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->nrodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $viewestudiante->nrodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->nrodiscapacidad) ?>',2);"><div id="elh_viewestudiante_nrodiscapacidad" class="viewestudiante_nrodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->nrodiscapacidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->ci->Visible) { // ci ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->ci) == "") { ?>
		<th data-name="ci" class="<?php echo $viewestudiante->ci->HeaderCellClass() ?>"><div id="elh_viewestudiante_ci" class="viewestudiante_ci"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->ci->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ci" class="<?php echo $viewestudiante->ci->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->ci) ?>',2);"><div id="elh_viewestudiante_ci" class="viewestudiante_ci">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->ci->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->fechanacimiento->Visible) { // fechanacimiento ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->fechanacimiento) == "") { ?>
		<th data-name="fechanacimiento" class="<?php echo $viewestudiante->fechanacimiento->HeaderCellClass() ?>"><div id="elh_viewestudiante_fechanacimiento" class="viewestudiante_fechanacimiento"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->fechanacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechanacimiento" class="<?php echo $viewestudiante->fechanacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->fechanacimiento) ?>',2);"><div id="elh_viewestudiante_fechanacimiento" class="viewestudiante_fechanacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->fechanacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->fechanacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->fechanacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->edad->Visible) { // edad ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->edad) == "") { ?>
		<th data-name="edad" class="<?php echo $viewestudiante->edad->HeaderCellClass() ?>"><div id="elh_viewestudiante_edad" class="viewestudiante_edad"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->edad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="edad" class="<?php echo $viewestudiante->edad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->edad) ?>',2);"><div id="elh_viewestudiante_edad" class="viewestudiante_edad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->edad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->edad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->edad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->sexo->Visible) { // sexo ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->sexo) == "") { ?>
		<th data-name="sexo" class="<?php echo $viewestudiante->sexo->HeaderCellClass() ?>"><div id="elh_viewestudiante_sexo" class="viewestudiante_sexo"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sexo" class="<?php echo $viewestudiante->sexo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->sexo) ?>',2);"><div id="elh_viewestudiante_sexo" class="viewestudiante_sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->curso->Visible) { // curso ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->curso) == "") { ?>
		<th data-name="curso" class="<?php echo $viewestudiante->curso->HeaderCellClass() ?>"><div id="elh_viewestudiante_curso" class="viewestudiante_curso"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->curso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="curso" class="<?php echo $viewestudiante->curso->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->curso) ?>',2);"><div id="elh_viewestudiante_curso" class="viewestudiante_curso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->curso->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->curso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->curso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->discapacidad->Visible) { // discapacidad ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->discapacidad) == "") { ?>
		<th data-name="discapacidad" class="<?php echo $viewestudiante->discapacidad->HeaderCellClass() ?>"><div id="elh_viewestudiante_discapacidad" class="viewestudiante_discapacidad"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->discapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="discapacidad" class="<?php echo $viewestudiante->discapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->discapacidad) ?>',2);"><div id="elh_viewestudiante_discapacidad" class="viewestudiante_discapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->discapacidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->tipodiscapcidad->Visible) { // tipodiscapcidad ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->tipodiscapcidad) == "") { ?>
		<th data-name="tipodiscapcidad" class="<?php echo $viewestudiante->tipodiscapcidad->HeaderCellClass() ?>"><div id="elh_viewestudiante_tipodiscapcidad" class="viewestudiante_tipodiscapcidad"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->tipodiscapcidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipodiscapcidad" class="<?php echo $viewestudiante->tipodiscapcidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->tipodiscapcidad) ?>',2);"><div id="elh_viewestudiante_tipodiscapcidad" class="viewestudiante_tipodiscapcidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->tipodiscapcidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->tipodiscapcidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->tipodiscapcidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($viewestudiante->nombreinstitucion->Visible) { // nombreinstitucion ?>
	<?php if ($viewestudiante->SortUrl($viewestudiante->nombreinstitucion) == "") { ?>
		<th data-name="nombreinstitucion" class="<?php echo $viewestudiante->nombreinstitucion->HeaderCellClass() ?>"><div id="elh_viewestudiante_nombreinstitucion" class="viewestudiante_nombreinstitucion"><div class="ewTableHeaderCaption"><?php echo $viewestudiante->nombreinstitucion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombreinstitucion" class="<?php echo $viewestudiante->nombreinstitucion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $viewestudiante->SortUrl($viewestudiante->nombreinstitucion) ?>',2);"><div id="elh_viewestudiante_nombreinstitucion" class="viewestudiante_nombreinstitucion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $viewestudiante->nombreinstitucion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($viewestudiante->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($viewestudiante->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$viewestudiante_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($viewestudiante->ExportAll && $viewestudiante->Export <> "") {
	$viewestudiante_list->StopRec = $viewestudiante_list->TotalRecs;
} else {

	// Set the last record to display
	if ($viewestudiante_list->TotalRecs > $viewestudiante_list->StartRec + $viewestudiante_list->DisplayRecs - 1)
		$viewestudiante_list->StopRec = $viewestudiante_list->StartRec + $viewestudiante_list->DisplayRecs - 1;
	else
		$viewestudiante_list->StopRec = $viewestudiante_list->TotalRecs;
}
$viewestudiante_list->RecCnt = $viewestudiante_list->StartRec - 1;
if ($viewestudiante_list->Recordset && !$viewestudiante_list->Recordset->EOF) {
	$viewestudiante_list->Recordset->MoveFirst();
	$bSelectLimit = $viewestudiante_list->UseSelectLimit;
	if (!$bSelectLimit && $viewestudiante_list->StartRec > 1)
		$viewestudiante_list->Recordset->Move($viewestudiante_list->StartRec - 1);
} elseif (!$viewestudiante->AllowAddDeleteRow && $viewestudiante_list->StopRec == 0) {
	$viewestudiante_list->StopRec = $viewestudiante->GridAddRowCount;
}

// Initialize aggregate
$viewestudiante->RowType = EW_ROWTYPE_AGGREGATEINIT;
$viewestudiante->ResetAttrs();
$viewestudiante_list->RenderRow();
while ($viewestudiante_list->RecCnt < $viewestudiante_list->StopRec) {
	$viewestudiante_list->RecCnt++;
	if (intval($viewestudiante_list->RecCnt) >= intval($viewestudiante_list->StartRec)) {
		$viewestudiante_list->RowCnt++;

		// Set up key count
		$viewestudiante_list->KeyCount = $viewestudiante_list->RowIndex;

		// Init row class and style
		$viewestudiante->ResetAttrs();
		$viewestudiante->CssClass = "";
		if ($viewestudiante->CurrentAction == "gridadd") {
		} else {
			$viewestudiante_list->LoadRowValues($viewestudiante_list->Recordset); // Load row values
		}
		$viewestudiante->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$viewestudiante->RowAttrs = array_merge($viewestudiante->RowAttrs, array('data-rowindex'=>$viewestudiante_list->RowCnt, 'id'=>'r' . $viewestudiante_list->RowCnt . '_viewestudiante', 'data-rowtype'=>$viewestudiante->RowType));

		// Render row
		$viewestudiante_list->RenderRow();

		// Render list options
		$viewestudiante_list->RenderListOptions();
?>
	<tr<?php echo $viewestudiante->RowAttributes() ?>>
<?php

// Render list options (body, left)
$viewestudiante_list->ListOptions->Render("body", "left", $viewestudiante_list->RowCnt);
?>
	<?php if ($viewestudiante->departamento->Visible) { // departamento ?>
		<td data-name="departamento"<?php echo $viewestudiante->departamento->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_departamento" class="viewestudiante_departamento">
<span<?php echo $viewestudiante->departamento->ViewAttributes() ?>>
<?php echo $viewestudiante->departamento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->codigorude->Visible) { // codigorude ?>
		<td data-name="codigorude"<?php echo $viewestudiante->codigorude->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_codigorude" class="viewestudiante_codigorude">
<span<?php echo $viewestudiante->codigorude->ViewAttributes() ?>>
<?php echo $viewestudiante->codigorude->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->codigorude_es->Visible) { // codigorude_es ?>
		<td data-name="codigorude_es"<?php echo $viewestudiante->codigorude_es->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_codigorude_es" class="viewestudiante_codigorude_es">
<span<?php echo $viewestudiante->codigorude_es->ViewAttributes() ?>>
<?php echo $viewestudiante->codigorude_es->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->municipio->Visible) { // municipio ?>
		<td data-name="municipio"<?php echo $viewestudiante->municipio->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_municipio" class="viewestudiante_municipio">
<span<?php echo $viewestudiante->municipio->ViewAttributes() ?>>
<?php echo $viewestudiante->municipio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->provincia->Visible) { // provincia ?>
		<td data-name="provincia"<?php echo $viewestudiante->provincia->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_provincia" class="viewestudiante_provincia">
<span<?php echo $viewestudiante->provincia->ViewAttributes() ?>>
<?php echo $viewestudiante->provincia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->unidadeducativa->Visible) { // unidadeducativa ?>
		<td data-name="unidadeducativa"<?php echo $viewestudiante->unidadeducativa->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_unidadeducativa" class="viewestudiante_unidadeducativa">
<span<?php echo $viewestudiante->unidadeducativa->ViewAttributes() ?>>
<?php echo $viewestudiante->unidadeducativa->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $viewestudiante->nombre->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_nombre" class="viewestudiante_nombre">
<span<?php echo $viewestudiante->nombre->ViewAttributes() ?>>
<?php echo $viewestudiante->nombre->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->materno->Visible) { // materno ?>
		<td data-name="materno"<?php echo $viewestudiante->materno->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_materno" class="viewestudiante_materno">
<span<?php echo $viewestudiante->materno->ViewAttributes() ?>>
<?php echo $viewestudiante->materno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->paterno->Visible) { // paterno ?>
		<td data-name="paterno"<?php echo $viewestudiante->paterno->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_paterno" class="viewestudiante_paterno">
<span<?php echo $viewestudiante->paterno->ViewAttributes() ?>>
<?php echo $viewestudiante->paterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td data-name="nrodiscapacidad"<?php echo $viewestudiante->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_nrodiscapacidad" class="viewestudiante_nrodiscapacidad">
<span<?php echo $viewestudiante->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $viewestudiante->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->ci->Visible) { // ci ?>
		<td data-name="ci"<?php echo $viewestudiante->ci->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_ci" class="viewestudiante_ci">
<span<?php echo $viewestudiante->ci->ViewAttributes() ?>>
<?php echo $viewestudiante->ci->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->fechanacimiento->Visible) { // fechanacimiento ?>
		<td data-name="fechanacimiento"<?php echo $viewestudiante->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_fechanacimiento" class="viewestudiante_fechanacimiento">
<span<?php echo $viewestudiante->fechanacimiento->ViewAttributes() ?>>
<?php echo $viewestudiante->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->edad->Visible) { // edad ?>
		<td data-name="edad"<?php echo $viewestudiante->edad->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_edad" class="viewestudiante_edad">
<span<?php echo $viewestudiante->edad->ViewAttributes() ?>>
<?php echo $viewestudiante->edad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->sexo->Visible) { // sexo ?>
		<td data-name="sexo"<?php echo $viewestudiante->sexo->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_sexo" class="viewestudiante_sexo">
<span<?php echo $viewestudiante->sexo->ViewAttributes() ?>>
<?php echo $viewestudiante->sexo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->curso->Visible) { // curso ?>
		<td data-name="curso"<?php echo $viewestudiante->curso->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_curso" class="viewestudiante_curso">
<span<?php echo $viewestudiante->curso->ViewAttributes() ?>>
<?php echo $viewestudiante->curso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->discapacidad->Visible) { // discapacidad ?>
		<td data-name="discapacidad"<?php echo $viewestudiante->discapacidad->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_discapacidad" class="viewestudiante_discapacidad">
<span<?php echo $viewestudiante->discapacidad->ViewAttributes() ?>>
<?php echo $viewestudiante->discapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->tipodiscapcidad->Visible) { // tipodiscapcidad ?>
		<td data-name="tipodiscapcidad"<?php echo $viewestudiante->tipodiscapcidad->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_tipodiscapcidad" class="viewestudiante_tipodiscapcidad">
<span<?php echo $viewestudiante->tipodiscapcidad->ViewAttributes() ?>>
<?php echo $viewestudiante->tipodiscapcidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($viewestudiante->nombreinstitucion->Visible) { // nombreinstitucion ?>
		<td data-name="nombreinstitucion"<?php echo $viewestudiante->nombreinstitucion->CellAttributes() ?>>
<span id="el<?php echo $viewestudiante_list->RowCnt ?>_viewestudiante_nombreinstitucion" class="viewestudiante_nombreinstitucion">
<span<?php echo $viewestudiante->nombreinstitucion->ViewAttributes() ?>>
<?php echo $viewestudiante->nombreinstitucion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$viewestudiante_list->ListOptions->Render("body", "right", $viewestudiante_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($viewestudiante->CurrentAction <> "gridadd")
		$viewestudiante_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($viewestudiante->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($viewestudiante_list->Recordset)
	$viewestudiante_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($viewestudiante->CurrentAction <> "gridadd" && $viewestudiante->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($viewestudiante_list->Pager)) $viewestudiante_list->Pager = new cPrevNextPager($viewestudiante_list->StartRec, $viewestudiante_list->DisplayRecs, $viewestudiante_list->TotalRecs, $viewestudiante_list->AutoHidePager) ?>
<?php if ($viewestudiante_list->Pager->RecordCount > 0 && $viewestudiante_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($viewestudiante_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $viewestudiante_list->PageUrl() ?>start=<?php echo $viewestudiante_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($viewestudiante_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $viewestudiante_list->PageUrl() ?>start=<?php echo $viewestudiante_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $viewestudiante_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($viewestudiante_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $viewestudiante_list->PageUrl() ?>start=<?php echo $viewestudiante_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($viewestudiante_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $viewestudiante_list->PageUrl() ?>start=<?php echo $viewestudiante_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $viewestudiante_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($viewestudiante_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $viewestudiante_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $viewestudiante_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $viewestudiante_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($viewestudiante_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($viewestudiante_list->TotalRecs == 0 && $viewestudiante->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($viewestudiante_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fviewestudiantelistsrch.FilterList = <?php echo $viewestudiante_list->GetFilterList() ?>;
fviewestudiantelistsrch.Init();
fviewestudiantelist.Init();
</script>
<?php
$viewestudiante_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$viewestudiante_list->Page_Terminate();
?>
