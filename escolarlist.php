<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "escolarinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$escolar_list = NULL; // Initialize page object first

class cescolar_list extends cescolar {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'escolar';

	// Page object name
	var $PageObjName = 'escolar_list';

	// Grid form hidden field names
	var $FormName = 'fescolarlist';
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

		// Table object (escolar)
		if (!isset($GLOBALS["escolar"]) || get_class($GLOBALS["escolar"]) == "cescolar") {
			$GLOBALS["escolar"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["escolar"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "escolaradd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "escolardelete.php";
		$this->MultiUpdateUrl = "escolarupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'escolar', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fescolarlistsrch";

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
		$this->fecha->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->curso->SetVisibility();
		$this->id_discapacidad->SetVisibility();
		$this->id_tipodiscapacidad->SetVisibility();
		$this->resultado->SetVisibility();
		$this->resultadotamizaje->SetVisibility();
		$this->tapon->SetVisibility();
		$this->tapodonde->SetVisibility();
		$this->repetirprueba->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->id_apoderado->SetVisibility();
		$this->id_referencia->SetVisibility();
		$this->codigorude->SetVisibility();
		$this->codigorude_es->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();

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
		global $EW_EXPORT, $escolar;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($escolar);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fescolarlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJson(), ","); // Field fecha
		$sFilterList = ew_Concat($sFilterList, $this->id_departamento->AdvancedSearch->ToJson(), ","); // Field id_departamento
		$sFilterList = ew_Concat($sFilterList, $this->unidadeducativa->AdvancedSearch->ToJson(), ","); // Field unidadeducativa
		$sFilterList = ew_Concat($sFilterList, $this->apellidopaterno->AdvancedSearch->ToJson(), ","); // Field apellidopaterno
		$sFilterList = ew_Concat($sFilterList, $this->apellidomaterno->AdvancedSearch->ToJson(), ","); // Field apellidomaterno
		$sFilterList = ew_Concat($sFilterList, $this->nombres->AdvancedSearch->ToJson(), ","); // Field nombres
		$sFilterList = ew_Concat($sFilterList, $this->ci->AdvancedSearch->ToJson(), ","); // Field ci
		$sFilterList = ew_Concat($sFilterList, $this->fechanacimiento->AdvancedSearch->ToJson(), ","); // Field fechanacimiento
		$sFilterList = ew_Concat($sFilterList, $this->sexo->AdvancedSearch->ToJson(), ","); // Field sexo
		$sFilterList = ew_Concat($sFilterList, $this->curso->AdvancedSearch->ToJson(), ","); // Field curso
		$sFilterList = ew_Concat($sFilterList, $this->id_discapacidad->AdvancedSearch->ToJson(), ","); // Field id_discapacidad
		$sFilterList = ew_Concat($sFilterList, $this->id_tipodiscapacidad->AdvancedSearch->ToJson(), ","); // Field id_tipodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->resultado->AdvancedSearch->ToJson(), ","); // Field resultado
		$sFilterList = ew_Concat($sFilterList, $this->resultadotamizaje->AdvancedSearch->ToJson(), ","); // Field resultadotamizaje
		$sFilterList = ew_Concat($sFilterList, $this->tapon->AdvancedSearch->ToJson(), ","); // Field tapon
		$sFilterList = ew_Concat($sFilterList, $this->tapodonde->AdvancedSearch->ToJson(), ","); // Field tapodonde
		$sFilterList = ew_Concat($sFilterList, $this->repetirprueba->AdvancedSearch->ToJson(), ","); // Field repetirprueba
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJson(), ","); // Field observaciones
		$sFilterList = ew_Concat($sFilterList, $this->id_apoderado->AdvancedSearch->ToJson(), ","); // Field id_apoderado
		$sFilterList = ew_Concat($sFilterList, $this->id_referencia->AdvancedSearch->ToJson(), ","); // Field id_referencia
		$sFilterList = ew_Concat($sFilterList, $this->codigorude->AdvancedSearch->ToJson(), ","); // Field codigorude
		$sFilterList = ew_Concat($sFilterList, $this->codigorude_es->AdvancedSearch->ToJson(), ","); // Field codigorude_es
		$sFilterList = ew_Concat($sFilterList, $this->nrodiscapacidad->AdvancedSearch->ToJson(), ","); // Field nrodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->id_centro->AdvancedSearch->ToJson(), ","); // Field id_centro
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fescolarlistsrch", $filters);

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

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();

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

		// Field id_discapacidad
		$this->id_discapacidad->AdvancedSearch->SearchValue = @$filter["x_id_discapacidad"];
		$this->id_discapacidad->AdvancedSearch->SearchOperator = @$filter["z_id_discapacidad"];
		$this->id_discapacidad->AdvancedSearch->SearchCondition = @$filter["v_id_discapacidad"];
		$this->id_discapacidad->AdvancedSearch->SearchValue2 = @$filter["y_id_discapacidad"];
		$this->id_discapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_id_discapacidad"];
		$this->id_discapacidad->AdvancedSearch->Save();

		// Field id_tipodiscapacidad
		$this->id_tipodiscapacidad->AdvancedSearch->SearchValue = @$filter["x_id_tipodiscapacidad"];
		$this->id_tipodiscapacidad->AdvancedSearch->SearchOperator = @$filter["z_id_tipodiscapacidad"];
		$this->id_tipodiscapacidad->AdvancedSearch->SearchCondition = @$filter["v_id_tipodiscapacidad"];
		$this->id_tipodiscapacidad->AdvancedSearch->SearchValue2 = @$filter["y_id_tipodiscapacidad"];
		$this->id_tipodiscapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_id_tipodiscapacidad"];
		$this->id_tipodiscapacidad->AdvancedSearch->Save();

		// Field resultado
		$this->resultado->AdvancedSearch->SearchValue = @$filter["x_resultado"];
		$this->resultado->AdvancedSearch->SearchOperator = @$filter["z_resultado"];
		$this->resultado->AdvancedSearch->SearchCondition = @$filter["v_resultado"];
		$this->resultado->AdvancedSearch->SearchValue2 = @$filter["y_resultado"];
		$this->resultado->AdvancedSearch->SearchOperator2 = @$filter["w_resultado"];
		$this->resultado->AdvancedSearch->Save();

		// Field resultadotamizaje
		$this->resultadotamizaje->AdvancedSearch->SearchValue = @$filter["x_resultadotamizaje"];
		$this->resultadotamizaje->AdvancedSearch->SearchOperator = @$filter["z_resultadotamizaje"];
		$this->resultadotamizaje->AdvancedSearch->SearchCondition = @$filter["v_resultadotamizaje"];
		$this->resultadotamizaje->AdvancedSearch->SearchValue2 = @$filter["y_resultadotamizaje"];
		$this->resultadotamizaje->AdvancedSearch->SearchOperator2 = @$filter["w_resultadotamizaje"];
		$this->resultadotamizaje->AdvancedSearch->Save();

		// Field tapon
		$this->tapon->AdvancedSearch->SearchValue = @$filter["x_tapon"];
		$this->tapon->AdvancedSearch->SearchOperator = @$filter["z_tapon"];
		$this->tapon->AdvancedSearch->SearchCondition = @$filter["v_tapon"];
		$this->tapon->AdvancedSearch->SearchValue2 = @$filter["y_tapon"];
		$this->tapon->AdvancedSearch->SearchOperator2 = @$filter["w_tapon"];
		$this->tapon->AdvancedSearch->Save();

		// Field tapodonde
		$this->tapodonde->AdvancedSearch->SearchValue = @$filter["x_tapodonde"];
		$this->tapodonde->AdvancedSearch->SearchOperator = @$filter["z_tapodonde"];
		$this->tapodonde->AdvancedSearch->SearchCondition = @$filter["v_tapodonde"];
		$this->tapodonde->AdvancedSearch->SearchValue2 = @$filter["y_tapodonde"];
		$this->tapodonde->AdvancedSearch->SearchOperator2 = @$filter["w_tapodonde"];
		$this->tapodonde->AdvancedSearch->Save();

		// Field repetirprueba
		$this->repetirprueba->AdvancedSearch->SearchValue = @$filter["x_repetirprueba"];
		$this->repetirprueba->AdvancedSearch->SearchOperator = @$filter["z_repetirprueba"];
		$this->repetirprueba->AdvancedSearch->SearchCondition = @$filter["v_repetirprueba"];
		$this->repetirprueba->AdvancedSearch->SearchValue2 = @$filter["y_repetirprueba"];
		$this->repetirprueba->AdvancedSearch->SearchOperator2 = @$filter["w_repetirprueba"];
		$this->repetirprueba->AdvancedSearch->Save();

		// Field observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$filter["x_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator = @$filter["z_observaciones"];
		$this->observaciones->AdvancedSearch->SearchCondition = @$filter["v_observaciones"];
		$this->observaciones->AdvancedSearch->SearchValue2 = @$filter["y_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones"];
		$this->observaciones->AdvancedSearch->Save();

		// Field id_apoderado
		$this->id_apoderado->AdvancedSearch->SearchValue = @$filter["x_id_apoderado"];
		$this->id_apoderado->AdvancedSearch->SearchOperator = @$filter["z_id_apoderado"];
		$this->id_apoderado->AdvancedSearch->SearchCondition = @$filter["v_id_apoderado"];
		$this->id_apoderado->AdvancedSearch->SearchValue2 = @$filter["y_id_apoderado"];
		$this->id_apoderado->AdvancedSearch->SearchOperator2 = @$filter["w_id_apoderado"];
		$this->id_apoderado->AdvancedSearch->Save();

		// Field id_referencia
		$this->id_referencia->AdvancedSearch->SearchValue = @$filter["x_id_referencia"];
		$this->id_referencia->AdvancedSearch->SearchOperator = @$filter["z_id_referencia"];
		$this->id_referencia->AdvancedSearch->SearchCondition = @$filter["v_id_referencia"];
		$this->id_referencia->AdvancedSearch->SearchValue2 = @$filter["y_id_referencia"];
		$this->id_referencia->AdvancedSearch->SearchOperator2 = @$filter["w_id_referencia"];
		$this->id_referencia->AdvancedSearch->Save();

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

		// Field nrodiscapacidad
		$this->nrodiscapacidad->AdvancedSearch->SearchValue = @$filter["x_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator = @$filter["z_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchCondition = @$filter["v_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchValue2 = @$filter["y_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->Save();

		// Field id_centro
		$this->id_centro->AdvancedSearch->SearchValue = @$filter["x_id_centro"];
		$this->id_centro->AdvancedSearch->SearchOperator = @$filter["z_id_centro"];
		$this->id_centro->AdvancedSearch->SearchCondition = @$filter["v_id_centro"];
		$this->id_centro->AdvancedSearch->SearchValue2 = @$filter["y_id_centro"];
		$this->id_centro->AdvancedSearch->SearchOperator2 = @$filter["w_id_centro"];
		$this->id_centro->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->id_departamento, $Default, FALSE); // id_departamento
		$this->BuildSearchSql($sWhere, $this->unidadeducativa, $Default, FALSE); // unidadeducativa
		$this->BuildSearchSql($sWhere, $this->apellidopaterno, $Default, FALSE); // apellidopaterno
		$this->BuildSearchSql($sWhere, $this->apellidomaterno, $Default, FALSE); // apellidomaterno
		$this->BuildSearchSql($sWhere, $this->nombres, $Default, FALSE); // nombres
		$this->BuildSearchSql($sWhere, $this->ci, $Default, FALSE); // ci
		$this->BuildSearchSql($sWhere, $this->fechanacimiento, $Default, FALSE); // fechanacimiento
		$this->BuildSearchSql($sWhere, $this->sexo, $Default, FALSE); // sexo
		$this->BuildSearchSql($sWhere, $this->curso, $Default, FALSE); // curso
		$this->BuildSearchSql($sWhere, $this->id_discapacidad, $Default, FALSE); // id_discapacidad
		$this->BuildSearchSql($sWhere, $this->id_tipodiscapacidad, $Default, FALSE); // id_tipodiscapacidad
		$this->BuildSearchSql($sWhere, $this->resultado, $Default, FALSE); // resultado
		$this->BuildSearchSql($sWhere, $this->resultadotamizaje, $Default, FALSE); // resultadotamizaje
		$this->BuildSearchSql($sWhere, $this->tapon, $Default, FALSE); // tapon
		$this->BuildSearchSql($sWhere, $this->tapodonde, $Default, FALSE); // tapodonde
		$this->BuildSearchSql($sWhere, $this->repetirprueba, $Default, FALSE); // repetirprueba
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->id_apoderado, $Default, FALSE); // id_apoderado
		$this->BuildSearchSql($sWhere, $this->id_referencia, $Default, FALSE); // id_referencia
		$this->BuildSearchSql($sWhere, $this->codigorude, $Default, FALSE); // codigorude
		$this->BuildSearchSql($sWhere, $this->codigorude_es, $Default, FALSE); // codigorude_es
		$this->BuildSearchSql($sWhere, $this->nrodiscapacidad, $Default, FALSE); // nrodiscapacidad
		$this->BuildSearchSql($sWhere, $this->id_centro, $Default, FALSE); // id_centro

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->id_departamento->AdvancedSearch->Save(); // id_departamento
			$this->unidadeducativa->AdvancedSearch->Save(); // unidadeducativa
			$this->apellidopaterno->AdvancedSearch->Save(); // apellidopaterno
			$this->apellidomaterno->AdvancedSearch->Save(); // apellidomaterno
			$this->nombres->AdvancedSearch->Save(); // nombres
			$this->ci->AdvancedSearch->Save(); // ci
			$this->fechanacimiento->AdvancedSearch->Save(); // fechanacimiento
			$this->sexo->AdvancedSearch->Save(); // sexo
			$this->curso->AdvancedSearch->Save(); // curso
			$this->id_discapacidad->AdvancedSearch->Save(); // id_discapacidad
			$this->id_tipodiscapacidad->AdvancedSearch->Save(); // id_tipodiscapacidad
			$this->resultado->AdvancedSearch->Save(); // resultado
			$this->resultadotamizaje->AdvancedSearch->Save(); // resultadotamizaje
			$this->tapon->AdvancedSearch->Save(); // tapon
			$this->tapodonde->AdvancedSearch->Save(); // tapodonde
			$this->repetirprueba->AdvancedSearch->Save(); // repetirprueba
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->id_apoderado->AdvancedSearch->Save(); // id_apoderado
			$this->id_referencia->AdvancedSearch->Save(); // id_referencia
			$this->codigorude->AdvancedSearch->Save(); // codigorude
			$this->codigorude_es->AdvancedSearch->Save(); // codigorude_es
			$this->nrodiscapacidad->AdvancedSearch->Save(); // nrodiscapacidad
			$this->id_centro->AdvancedSearch->Save(); // id_centro
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
		if ($this->fecha->AdvancedSearch->IssetSession())
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
		if ($this->ci->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechanacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->curso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_discapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_tipodiscapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resultado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resultadotamizaje->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tapon->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tapodonde->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->repetirprueba->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_apoderado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_referencia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigorude->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigorude_es->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nrodiscapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_centro->AdvancedSearch->IssetSession())
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
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->id_departamento->AdvancedSearch->UnsetSession();
		$this->unidadeducativa->AdvancedSearch->UnsetSession();
		$this->apellidopaterno->AdvancedSearch->UnsetSession();
		$this->apellidomaterno->AdvancedSearch->UnsetSession();
		$this->nombres->AdvancedSearch->UnsetSession();
		$this->ci->AdvancedSearch->UnsetSession();
		$this->fechanacimiento->AdvancedSearch->UnsetSession();
		$this->sexo->AdvancedSearch->UnsetSession();
		$this->curso->AdvancedSearch->UnsetSession();
		$this->id_discapacidad->AdvancedSearch->UnsetSession();
		$this->id_tipodiscapacidad->AdvancedSearch->UnsetSession();
		$this->resultado->AdvancedSearch->UnsetSession();
		$this->resultadotamizaje->AdvancedSearch->UnsetSession();
		$this->tapon->AdvancedSearch->UnsetSession();
		$this->tapodonde->AdvancedSearch->UnsetSession();
		$this->repetirprueba->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->id_apoderado->AdvancedSearch->UnsetSession();
		$this->id_referencia->AdvancedSearch->UnsetSession();
		$this->codigorude->AdvancedSearch->UnsetSession();
		$this->codigorude_es->AdvancedSearch->UnsetSession();
		$this->nrodiscapacidad->AdvancedSearch->UnsetSession();
		$this->id_centro->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->fecha->AdvancedSearch->Load();
		$this->id_departamento->AdvancedSearch->Load();
		$this->unidadeducativa->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fechanacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->curso->AdvancedSearch->Load();
		$this->id_discapacidad->AdvancedSearch->Load();
		$this->id_tipodiscapacidad->AdvancedSearch->Load();
		$this->resultado->AdvancedSearch->Load();
		$this->resultadotamizaje->AdvancedSearch->Load();
		$this->tapon->AdvancedSearch->Load();
		$this->tapodonde->AdvancedSearch->Load();
		$this->repetirprueba->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->id_apoderado->AdvancedSearch->Load();
		$this->id_referencia->AdvancedSearch->Load();
		$this->codigorude->AdvancedSearch->Load();
		$this->codigorude_es->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->id_centro->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->fecha, $bCtrl); // fecha
			$this->UpdateSort($this->unidadeducativa, $bCtrl); // unidadeducativa
			$this->UpdateSort($this->apellidopaterno, $bCtrl); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno, $bCtrl); // apellidomaterno
			$this->UpdateSort($this->nombres, $bCtrl); // nombres
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->fechanacimiento, $bCtrl); // fechanacimiento
			$this->UpdateSort($this->sexo, $bCtrl); // sexo
			$this->UpdateSort($this->curso, $bCtrl); // curso
			$this->UpdateSort($this->id_discapacidad, $bCtrl); // id_discapacidad
			$this->UpdateSort($this->id_tipodiscapacidad, $bCtrl); // id_tipodiscapacidad
			$this->UpdateSort($this->resultado, $bCtrl); // resultado
			$this->UpdateSort($this->resultadotamizaje, $bCtrl); // resultadotamizaje
			$this->UpdateSort($this->tapon, $bCtrl); // tapon
			$this->UpdateSort($this->tapodonde, $bCtrl); // tapodonde
			$this->UpdateSort($this->repetirprueba, $bCtrl); // repetirprueba
			$this->UpdateSort($this->observaciones, $bCtrl); // observaciones
			$this->UpdateSort($this->id_apoderado, $bCtrl); // id_apoderado
			$this->UpdateSort($this->id_referencia, $bCtrl); // id_referencia
			$this->UpdateSort($this->codigorude, $bCtrl); // codigorude
			$this->UpdateSort($this->codigorude_es, $bCtrl); // codigorude_es
			$this->UpdateSort($this->nrodiscapacidad, $bCtrl); // nrodiscapacidad
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
				$this->fecha->setSort("");
				$this->unidadeducativa->setSort("");
				$this->apellidopaterno->setSort("");
				$this->apellidomaterno->setSort("");
				$this->nombres->setSort("");
				$this->ci->setSort("");
				$this->fechanacimiento->setSort("");
				$this->sexo->setSort("");
				$this->curso->setSort("");
				$this->id_discapacidad->setSort("");
				$this->id_tipodiscapacidad->setSort("");
				$this->resultado->setSort("");
				$this->resultadotamizaje->setSort("");
				$this->tapon->setSort("");
				$this->tapodonde->setSort("");
				$this->repetirprueba->setSort("");
				$this->observaciones->setSort("");
				$this->id_apoderado->setSort("");
				$this->id_referencia->setSort("");
				$this->codigorude->setSort("");
				$this->codigorude_es->setSort("");
				$this->nrodiscapacidad->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fescolarlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fescolarlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fescolarlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fescolarlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		// fecha

		$this->fecha->AdvancedSearch->SearchValue = @$_GET["x_fecha"];
		if ($this->fecha->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];

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

		// id_discapacidad
		$this->id_discapacidad->AdvancedSearch->SearchValue = @$_GET["x_id_discapacidad"];
		if ($this->id_discapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_discapacidad->AdvancedSearch->SearchOperator = @$_GET["z_id_discapacidad"];

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad->AdvancedSearch->SearchValue = @$_GET["x_id_tipodiscapacidad"];
		if ($this->id_tipodiscapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_tipodiscapacidad->AdvancedSearch->SearchOperator = @$_GET["z_id_tipodiscapacidad"];

		// resultado
		$this->resultado->AdvancedSearch->SearchValue = @$_GET["x_resultado"];
		if ($this->resultado->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resultado->AdvancedSearch->SearchOperator = @$_GET["z_resultado"];

		// resultadotamizaje
		$this->resultadotamizaje->AdvancedSearch->SearchValue = @$_GET["x_resultadotamizaje"];
		if ($this->resultadotamizaje->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resultadotamizaje->AdvancedSearch->SearchOperator = @$_GET["z_resultadotamizaje"];

		// tapon
		$this->tapon->AdvancedSearch->SearchValue = @$_GET["x_tapon"];
		if ($this->tapon->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tapon->AdvancedSearch->SearchOperator = @$_GET["z_tapon"];

		// tapodonde
		$this->tapodonde->AdvancedSearch->SearchValue = @$_GET["x_tapodonde"];
		if ($this->tapodonde->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tapodonde->AdvancedSearch->SearchOperator = @$_GET["z_tapodonde"];

		// repetirprueba
		$this->repetirprueba->AdvancedSearch->SearchValue = @$_GET["x_repetirprueba"];
		if ($this->repetirprueba->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->repetirprueba->AdvancedSearch->SearchOperator = @$_GET["z_repetirprueba"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$_GET["x_observaciones"];
		if ($this->observaciones->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];

		// id_apoderado
		$this->id_apoderado->AdvancedSearch->SearchValue = @$_GET["x_id_apoderado"];
		if ($this->id_apoderado->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_apoderado->AdvancedSearch->SearchOperator = @$_GET["z_id_apoderado"];

		// id_referencia
		$this->id_referencia->AdvancedSearch->SearchValue = @$_GET["x_id_referencia"];
		if ($this->id_referencia->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_referencia->AdvancedSearch->SearchOperator = @$_GET["z_id_referencia"];

		// codigorude
		$this->codigorude->AdvancedSearch->SearchValue = @$_GET["x_codigorude"];
		if ($this->codigorude->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->codigorude->AdvancedSearch->SearchOperator = @$_GET["z_codigorude"];

		// codigorude_es
		$this->codigorude_es->AdvancedSearch->SearchValue = @$_GET["x_codigorude_es"];
		if ($this->codigorude_es->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->codigorude_es->AdvancedSearch->SearchOperator = @$_GET["z_codigorude_es"];

		// nrodiscapacidad
		$this->nrodiscapacidad->AdvancedSearch->SearchValue = @$_GET["x_nrodiscapacidad"];
		if ($this->nrodiscapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator = @$_GET["z_nrodiscapacidad"];

		// id_centro
		$this->id_centro->AdvancedSearch->SearchValue = @$_GET["x_id_centro"];
		if ($this->id_centro->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_centro->AdvancedSearch->SearchOperator = @$_GET["z_id_centro"];
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
		$this->fecha->setDbValue($row['fecha']);
		$this->id_departamento->setDbValue($row['id_departamento']);
		$this->unidadeducativa->setDbValue($row['unidadeducativa']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombres->setDbValue($row['nombres']);
		$this->ci->setDbValue($row['ci']);
		$this->fechanacimiento->setDbValue($row['fechanacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->curso->setDbValue($row['curso']);
		$this->id_discapacidad->setDbValue($row['id_discapacidad']);
		$this->id_tipodiscapacidad->setDbValue($row['id_tipodiscapacidad']);
		$this->resultado->setDbValue($row['resultado']);
		$this->resultadotamizaje->setDbValue($row['resultadotamizaje']);
		$this->tapon->setDbValue($row['tapon']);
		$this->tapodonde->setDbValue($row['tapodonde']);
		$this->repetirprueba->setDbValue($row['repetirprueba']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_apoderado->setDbValue($row['id_apoderado']);
		if (array_key_exists('EV__id_apoderado', $rs->fields)) {
			$this->id_apoderado->VirtualValue = $rs->fields('EV__id_apoderado'); // Set up virtual field value
		} else {
			$this->id_apoderado->VirtualValue = ""; // Clear value
		}
		$this->id_referencia->setDbValue($row['id_referencia']);
		if (array_key_exists('EV__id_referencia', $rs->fields)) {
			$this->id_referencia->VirtualValue = $rs->fields('EV__id_referencia'); // Set up virtual field value
		} else {
			$this->id_referencia->VirtualValue = ""; // Clear value
		}
		$this->codigorude->setDbValue($row['codigorude']);
		$this->codigorude_es->setDbValue($row['codigorude_es']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['fecha'] = NULL;
		$row['id_departamento'] = NULL;
		$row['unidadeducativa'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombres'] = NULL;
		$row['ci'] = NULL;
		$row['fechanacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['curso'] = NULL;
		$row['id_discapacidad'] = NULL;
		$row['id_tipodiscapacidad'] = NULL;
		$row['resultado'] = NULL;
		$row['resultadotamizaje'] = NULL;
		$row['tapon'] = NULL;
		$row['tapodonde'] = NULL;
		$row['repetirprueba'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_apoderado'] = NULL;
		$row['id_referencia'] = NULL;
		$row['codigorude'] = NULL;
		$row['codigorude_es'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_departamento->DbValue = $row['id_departamento'];
		$this->unidadeducativa->DbValue = $row['unidadeducativa'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombres->DbValue = $row['nombres'];
		$this->ci->DbValue = $row['ci'];
		$this->fechanacimiento->DbValue = $row['fechanacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->curso->DbValue = $row['curso'];
		$this->id_discapacidad->DbValue = $row['id_discapacidad'];
		$this->id_tipodiscapacidad->DbValue = $row['id_tipodiscapacidad'];
		$this->resultado->DbValue = $row['resultado'];
		$this->resultadotamizaje->DbValue = $row['resultadotamizaje'];
		$this->tapon->DbValue = $row['tapon'];
		$this->tapodonde->DbValue = $row['tapodonde'];
		$this->repetirprueba->DbValue = $row['repetirprueba'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_apoderado->DbValue = $row['id_apoderado'];
		$this->id_referencia->DbValue = $row['id_referencia'];
		$this->codigorude->DbValue = $row['codigorude'];
		$this->codigorude_es->DbValue = $row['codigorude_es'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
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
		// fecha
		// id_departamento

		$this->id_departamento->CellCssStyle = "white-space: nowrap;";

		// unidadeducativa
		// apellidopaterno
		// apellidomaterno
		// nombres
		// ci
		// fechanacimiento
		// sexo
		// curso
		// id_discapacidad
		// id_tipodiscapacidad
		// resultado
		// resultadotamizaje
		// tapon
		// tapodonde
		// repetirprueba
		// observaciones
		// id_apoderado
		// id_referencia
		// codigorude
		// codigorude_es
		// nrodiscapacidad
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

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

		// curso
		$this->curso->ViewValue = $this->curso->CurrentValue;
		$this->curso->ViewCustomAttributes = "";

		// id_discapacidad
		if (strval($this->id_discapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_discapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
		$sWhereWrk = "";
		$this->id_discapacidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_discapacidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_discapacidad->ViewValue = $this->id_discapacidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_discapacidad->ViewValue = $this->id_discapacidad->CurrentValue;
			}
		} else {
			$this->id_discapacidad->ViewValue = NULL;
		}
		$this->id_discapacidad->ViewCustomAttributes = "";

		// id_tipodiscapacidad
		if (strval($this->id_tipodiscapacidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipodiscapacidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiscapacidad`";
		$sWhereWrk = "";
		$this->id_tipodiscapacidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipodiscapacidad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->CurrentValue;
			}
		} else {
			$this->id_tipodiscapacidad->ViewValue = NULL;
		}
		$this->id_tipodiscapacidad->ViewCustomAttributes = "";

		// resultado
		if (strval($this->resultado->CurrentValue) <> "") {
			$this->resultado->ViewValue = $this->resultado->OptionCaption($this->resultado->CurrentValue);
		} else {
			$this->resultado->ViewValue = NULL;
		}
		$this->resultado->ViewCustomAttributes = "";

		// resultadotamizaje
		$this->resultadotamizaje->ViewValue = $this->resultadotamizaje->CurrentValue;
		$this->resultadotamizaje->ViewCustomAttributes = "";

		// tapon
		if (strval($this->tapon->CurrentValue) <> "") {
			$this->tapon->ViewValue = $this->tapon->OptionCaption($this->tapon->CurrentValue);
		} else {
			$this->tapon->ViewValue = NULL;
		}
		$this->tapon->ViewCustomAttributes = "";

		// tapodonde
		if (strval($this->tapodonde->CurrentValue) <> "") {
			$this->tapodonde->ViewValue = $this->tapodonde->OptionCaption($this->tapodonde->CurrentValue);
		} else {
			$this->tapodonde->ViewValue = NULL;
		}
		$this->tapodonde->ViewCustomAttributes = "";

		// repetirprueba
		if (strval($this->repetirprueba->CurrentValue) <> "") {
			$this->repetirprueba->ViewValue = $this->repetirprueba->OptionCaption($this->repetirprueba->CurrentValue);
		} else {
			$this->repetirprueba->ViewValue = NULL;
		}
		$this->repetirprueba->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_apoderado
		if ($this->id_apoderado->VirtualValue <> "") {
			$this->id_apoderado->ViewValue = $this->id_apoderado->VirtualValue;
		} else {
		if (strval($this->id_apoderado->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_apoderado->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `apoderado`";
		$sWhereWrk = "";
		$this->id_apoderado->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_apoderado, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_apoderado->ViewValue = $this->id_apoderado->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_apoderado->ViewValue = $this->id_apoderado->CurrentValue;
			}
		} else {
			$this->id_apoderado->ViewValue = NULL;
		}
		}
		$this->id_apoderado->ViewCustomAttributes = "";

		// id_referencia
		if ($this->id_referencia->VirtualValue <> "") {
			$this->id_referencia->ViewValue = $this->id_referencia->VirtualValue;
		} else {
		if (strval($this->id_referencia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_referencia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombrescompleto` AS `DispFld`, `nombrescentromedico` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
		$sWhereWrk = "";
		$this->id_referencia->LookupFilters = array("dx1" => '`nombrescompleto`', "dx2" => '`nombrescentromedico`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_referencia, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->id_referencia->ViewValue = $this->id_referencia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_referencia->ViewValue = $this->id_referencia->CurrentValue;
			}
		} else {
			$this->id_referencia->ViewValue = NULL;
		}
		}
		$this->id_referencia->ViewCustomAttributes = "";

		// codigorude
		$this->codigorude->ViewValue = $this->codigorude->CurrentValue;
		$this->codigorude->ViewCustomAttributes = "";

		// codigorude_es
		$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
		$this->codigorude_es->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

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

			// id_discapacidad
			$this->id_discapacidad->LinkCustomAttributes = "";
			$this->id_discapacidad->HrefValue = "";
			$this->id_discapacidad->TooltipValue = "";

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->LinkCustomAttributes = "";
			$this->id_tipodiscapacidad->HrefValue = "";
			$this->id_tipodiscapacidad->TooltipValue = "";

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";
			$this->resultado->TooltipValue = "";

			// resultadotamizaje
			$this->resultadotamizaje->LinkCustomAttributes = "";
			$this->resultadotamizaje->HrefValue = "";
			$this->resultadotamizaje->TooltipValue = "";

			// tapon
			$this->tapon->LinkCustomAttributes = "";
			$this->tapon->HrefValue = "";
			$this->tapon->TooltipValue = "";

			// tapodonde
			$this->tapodonde->LinkCustomAttributes = "";
			$this->tapodonde->HrefValue = "";
			$this->tapodonde->TooltipValue = "";

			// repetirprueba
			$this->repetirprueba->LinkCustomAttributes = "";
			$this->repetirprueba->HrefValue = "";
			$this->repetirprueba->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// id_apoderado
			$this->id_apoderado->LinkCustomAttributes = "";
			$this->id_apoderado->HrefValue = "";
			$this->id_apoderado->TooltipValue = "";

			// id_referencia
			$this->id_referencia->LinkCustomAttributes = "";
			$this->id_referencia->HrefValue = "";
			$this->id_referencia->TooltipValue = "";

			// codigorude
			$this->codigorude->LinkCustomAttributes = "";
			$this->codigorude->HrefValue = "";
			$this->codigorude->TooltipValue = "";

			// codigorude_es
			$this->codigorude_es->LinkCustomAttributes = "";
			$this->codigorude_es->HrefValue = "";
			$this->codigorude_es->TooltipValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
			$this->nrodiscapacidad->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

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

			// ci
			$this->ci->EditAttrs["class"] = "form-control";
			$this->ci->EditCustomAttributes = "";
			$this->ci->EditValue = ew_HtmlEncode($this->ci->AdvancedSearch->SearchValue);
			$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

			// fechanacimiento
			$this->fechanacimiento->EditAttrs["class"] = "form-control";
			$this->fechanacimiento->EditCustomAttributes = "";
			$this->fechanacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fechanacimiento->AdvancedSearch->SearchValue, 0), 8));
			$this->fechanacimiento->PlaceHolder = ew_RemoveHtml($this->fechanacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// curso
			$this->curso->EditAttrs["class"] = "form-control";
			$this->curso->EditCustomAttributes = "";
			$this->curso->EditValue = ew_HtmlEncode($this->curso->AdvancedSearch->SearchValue);
			$this->curso->PlaceHolder = ew_RemoveHtml($this->curso->FldCaption());

			// id_discapacidad
			$this->id_discapacidad->EditCustomAttributes = "";

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->EditCustomAttributes = "";

			// resultado
			$this->resultado->EditCustomAttributes = "";
			$this->resultado->EditValue = $this->resultado->Options(FALSE);

			// resultadotamizaje
			$this->resultadotamizaje->EditAttrs["class"] = "form-control";
			$this->resultadotamizaje->EditCustomAttributes = "";
			$this->resultadotamizaje->EditValue = ew_HtmlEncode($this->resultadotamizaje->AdvancedSearch->SearchValue);
			$this->resultadotamizaje->PlaceHolder = ew_RemoveHtml($this->resultadotamizaje->FldCaption());

			// tapon
			$this->tapon->EditCustomAttributes = "";
			$this->tapon->EditValue = $this->tapon->Options(FALSE);

			// tapodonde
			$this->tapodonde->EditCustomAttributes = "";
			$this->tapodonde->EditValue = $this->tapodonde->Options(FALSE);

			// repetirprueba
			$this->repetirprueba->EditCustomAttributes = "";
			$this->repetirprueba->EditValue = $this->repetirprueba->Options(FALSE);

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->AdvancedSearch->SearchValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// id_apoderado
			$this->id_apoderado->EditAttrs["class"] = "form-control";
			$this->id_apoderado->EditCustomAttributes = "";
			$this->id_apoderado->EditValue = ew_HtmlEncode($this->id_apoderado->AdvancedSearch->SearchValue);
			$this->id_apoderado->PlaceHolder = ew_RemoveHtml($this->id_apoderado->FldCaption());

			// id_referencia
			$this->id_referencia->EditAttrs["class"] = "form-control";
			$this->id_referencia->EditCustomAttributes = "";
			$this->id_referencia->EditValue = ew_HtmlEncode($this->id_referencia->AdvancedSearch->SearchValue);
			$this->id_referencia->PlaceHolder = ew_RemoveHtml($this->id_referencia->FldCaption());

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

			// nrodiscapacidad
			$this->nrodiscapacidad->EditAttrs["class"] = "form-control";
			$this->nrodiscapacidad->EditCustomAttributes = "";
			$this->nrodiscapacidad->EditValue = ew_HtmlEncode($this->nrodiscapacidad->AdvancedSearch->SearchValue);
			$this->nrodiscapacidad->PlaceHolder = ew_RemoveHtml($this->nrodiscapacidad->FldCaption());
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
		$this->fecha->AdvancedSearch->Load();
		$this->id_departamento->AdvancedSearch->Load();
		$this->unidadeducativa->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fechanacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->curso->AdvancedSearch->Load();
		$this->id_discapacidad->AdvancedSearch->Load();
		$this->id_tipodiscapacidad->AdvancedSearch->Load();
		$this->resultado->AdvancedSearch->Load();
		$this->resultadotamizaje->AdvancedSearch->Load();
		$this->tapon->AdvancedSearch->Load();
		$this->tapodonde->AdvancedSearch->Load();
		$this->repetirprueba->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->id_apoderado->AdvancedSearch->Load();
		$this->id_referencia->AdvancedSearch->Load();
		$this->codigorude->AdvancedSearch->Load();
		$this->codigorude_es->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->id_centro->AdvancedSearch->Load();
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
if (!isset($escolar_list)) $escolar_list = new cescolar_list();

// Page init
$escolar_list->Page_Init();

// Page main
$escolar_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$escolar_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fescolarlist = new ew_Form("fescolarlist", "list");
fescolarlist.FormKeyCountName = '<?php echo $escolar_list->FormKeyCountName ?>';

// Form_CustomValidate event
fescolarlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fescolarlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fescolarlist.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fescolarlist.Lists["x_unidadeducativa"].Data = "<?php echo $escolar_list->unidadeducativa->LookupFilterQuery(FALSE, "list") ?>";
fescolarlist.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolarlist.Lists["x_sexo"].Options = <?php echo json_encode($escolar_list->sexo->Options()) ?>;
fescolarlist.Lists["x_id_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fescolarlist.Lists["x_id_discapacidad"].Data = "<?php echo $escolar_list->id_discapacidad->LookupFilterQuery(FALSE, "list") ?>";
fescolarlist.Lists["x_id_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
fescolarlist.Lists["x_id_tipodiscapacidad"].Data = "<?php echo $escolar_list->id_tipodiscapacidad->LookupFilterQuery(FALSE, "list") ?>";
fescolarlist.Lists["x_resultado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolarlist.Lists["x_resultado"].Options = <?php echo json_encode($escolar_list->resultado->Options()) ?>;
fescolarlist.Lists["x_tapon"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolarlist.Lists["x_tapon"].Options = <?php echo json_encode($escolar_list->tapon->Options()) ?>;
fescolarlist.Lists["x_tapodonde"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolarlist.Lists["x_tapodonde"].Options = <?php echo json_encode($escolar_list->tapodonde->Options()) ?>;
fescolarlist.Lists["x_repetirprueba"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fescolarlist.Lists["x_repetirprueba"].Options = <?php echo json_encode($escolar_list->repetirprueba->Options()) ?>;
fescolarlist.Lists["x_id_apoderado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"apoderado"};
fescolarlist.Lists["x_id_apoderado"].Data = "<?php echo $escolar_list->id_apoderado->LookupFilterQuery(FALSE, "list") ?>";
fescolarlist.Lists["x_id_referencia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombrescompleto","x_nombrescentromedico","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referencia"};
fescolarlist.Lists["x_id_referencia"].Data = "<?php echo $escolar_list->id_referencia->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fescolarlistsrch = new ew_Form("fescolarlistsrch");

// Validate function for search
fescolarlistsrch.Validate = function(fobj) {
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
fescolarlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fescolarlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fescolarlistsrch.Lists["x_unidadeducativa"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fescolarlistsrch.Lists["x_unidadeducativa"].Data = "<?php echo $escolar_list->unidadeducativa->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($escolar_list->TotalRecs > 0 && $escolar_list->ExportOptions->Visible()) { ?>
<?php $escolar_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($escolar_list->SearchOptions->Visible()) { ?>
<?php $escolar_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($escolar_list->FilterOptions->Visible()) { ?>
<?php $escolar_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $escolar_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($escolar_list->TotalRecs <= 0)
			$escolar_list->TotalRecs = $escolar->ListRecordCount();
	} else {
		if (!$escolar_list->Recordset && ($escolar_list->Recordset = $escolar_list->LoadRecordset()))
			$escolar_list->TotalRecs = $escolar_list->Recordset->RecordCount();
	}
	$escolar_list->StartRec = 1;
	if ($escolar_list->DisplayRecs <= 0 || ($escolar->Export <> "" && $escolar->ExportAll)) // Display all records
		$escolar_list->DisplayRecs = $escolar_list->TotalRecs;
	if (!($escolar->Export <> "" && $escolar->ExportAll))
		$escolar_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$escolar_list->Recordset = $escolar_list->LoadRecordset($escolar_list->StartRec-1, $escolar_list->DisplayRecs);

	// Set no record found message
	if ($escolar->CurrentAction == "" && $escolar_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$escolar_list->setWarningMessage(ew_DeniedMsg());
		if ($escolar_list->SearchWhere == "0=101")
			$escolar_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$escolar_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$escolar_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($escolar->Export == "" && $escolar->CurrentAction == "") { ?>
<form name="fescolarlistsrch" id="fescolarlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($escolar_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fescolarlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="escolar">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$escolar_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$escolar->RowType = EW_ROWTYPE_SEARCH;

// Render row
$escolar->ResetAttrs();
$escolar_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($escolar->unidadeducativa->Visible) { // unidadeducativa ?>
	<div id="xsc_unidadeducativa" class="ewCell form-group">
		<label for="x_unidadeducativa" class="ewSearchCaption ewLabel"><?php echo $escolar->unidadeducativa->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_unidadeducativa" id="z_unidadeducativa" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="escolar" data-field="x_unidadeducativa" data-value-separator="<?php echo $escolar->unidadeducativa->DisplayValueSeparatorAttribute() ?>" id="x_unidadeducativa" name="x_unidadeducativa"<?php echo $escolar->unidadeducativa->EditAttributes() ?>>
<?php echo $escolar->unidadeducativa->SelectOptionListHtml("x_unidadeducativa") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($escolar->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="xsc_apellidopaterno" class="ewCell form-group">
		<label for="x_apellidopaterno" class="ewSearchCaption ewLabel"><?php echo $escolar->apellidopaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidopaterno" id="z_apellidopaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="escolar" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $escolar->apellidopaterno->EditValue ?>"<?php echo $escolar->apellidopaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($escolar->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="xsc_apellidomaterno" class="ewCell form-group">
		<label for="x_apellidomaterno" class="ewSearchCaption ewLabel"><?php echo $escolar->apellidomaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidomaterno" id="z_apellidomaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="escolar" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $escolar->apellidomaterno->EditValue ?>"<?php echo $escolar->apellidomaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($escolar->nombres->Visible) { // nombres ?>
	<div id="xsc_nombres" class="ewCell form-group">
		<label for="x_nombres" class="ewSearchCaption ewLabel"><?php echo $escolar->nombres->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombres" id="z_nombres" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="escolar" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->nombres->getPlaceHolder()) ?>" value="<?php echo $escolar->nombres->EditValue ?>"<?php echo $escolar->nombres->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($escolar->ci->Visible) { // ci ?>
	<div id="xsc_ci" class="ewCell form-group">
		<label for="x_ci" class="ewSearchCaption ewLabel"><?php echo $escolar->ci->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ci" id="z_ci" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="escolar" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($escolar->ci->getPlaceHolder()) ?>" value="<?php echo $escolar->ci->EditValue ?>"<?php echo $escolar->ci->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($escolar->curso->Visible) { // curso ?>
	<div id="xsc_curso" class="ewCell form-group">
		<label for="x_curso" class="ewSearchCaption ewLabel"><?php echo $escolar->curso->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_curso" id="z_curso" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="escolar" data-field="x_curso" name="x_curso" id="x_curso" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($escolar->curso->getPlaceHolder()) ?>" value="<?php echo $escolar->curso->EditValue ?>"<?php echo $escolar->curso->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $escolar_list->ShowPageHeader(); ?>
<?php
$escolar_list->ShowMessage();
?>
<?php if ($escolar_list->TotalRecs > 0 || $escolar->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($escolar_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> escolar">
<div class="box-header ewGridUpperPanel">
<?php if ($escolar->CurrentAction <> "gridadd" && $escolar->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($escolar_list->Pager)) $escolar_list->Pager = new cPrevNextPager($escolar_list->StartRec, $escolar_list->DisplayRecs, $escolar_list->TotalRecs, $escolar_list->AutoHidePager) ?>
<?php if ($escolar_list->Pager->RecordCount > 0 && $escolar_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($escolar_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $escolar_list->PageUrl() ?>start=<?php echo $escolar_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($escolar_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $escolar_list->PageUrl() ?>start=<?php echo $escolar_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $escolar_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($escolar_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $escolar_list->PageUrl() ?>start=<?php echo $escolar_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($escolar_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $escolar_list->PageUrl() ?>start=<?php echo $escolar_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $escolar_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($escolar_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $escolar_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $escolar_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $escolar_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($escolar_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fescolarlist" id="fescolarlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($escolar_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $escolar_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="escolar">
<div id="gmp_escolar" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($escolar_list->TotalRecs > 0 || $escolar->CurrentAction == "gridedit") { ?>
<table id="tbl_escolarlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$escolar_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$escolar_list->RenderListOptions();

// Render list options (header, left)
$escolar_list->ListOptions->Render("header", "left");
?>
<?php if ($escolar->fecha->Visible) { // fecha ?>
	<?php if ($escolar->SortUrl($escolar->fecha) == "") { ?>
		<th data-name="fecha" class="<?php echo $escolar->fecha->HeaderCellClass() ?>"><div id="elh_escolar_fecha" class="escolar_fecha"><div class="ewTableHeaderCaption"><?php echo $escolar->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha" class="<?php echo $escolar->fecha->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->fecha) ?>',2);"><div id="elh_escolar_fecha" class="escolar_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->unidadeducativa->Visible) { // unidadeducativa ?>
	<?php if ($escolar->SortUrl($escolar->unidadeducativa) == "") { ?>
		<th data-name="unidadeducativa" class="<?php echo $escolar->unidadeducativa->HeaderCellClass() ?>"><div id="elh_escolar_unidadeducativa" class="escolar_unidadeducativa"><div class="ewTableHeaderCaption"><?php echo $escolar->unidadeducativa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="unidadeducativa" class="<?php echo $escolar->unidadeducativa->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->unidadeducativa) ?>',2);"><div id="elh_escolar_unidadeducativa" class="escolar_unidadeducativa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->unidadeducativa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->unidadeducativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->unidadeducativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->apellidopaterno->Visible) { // apellidopaterno ?>
	<?php if ($escolar->SortUrl($escolar->apellidopaterno) == "") { ?>
		<th data-name="apellidopaterno" class="<?php echo $escolar->apellidopaterno->HeaderCellClass() ?>"><div id="elh_escolar_apellidopaterno" class="escolar_apellidopaterno"><div class="ewTableHeaderCaption"><?php echo $escolar->apellidopaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidopaterno" class="<?php echo $escolar->apellidopaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->apellidopaterno) ?>',2);"><div id="elh_escolar_apellidopaterno" class="escolar_apellidopaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->apellidopaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->apellidomaterno->Visible) { // apellidomaterno ?>
	<?php if ($escolar->SortUrl($escolar->apellidomaterno) == "") { ?>
		<th data-name="apellidomaterno" class="<?php echo $escolar->apellidomaterno->HeaderCellClass() ?>"><div id="elh_escolar_apellidomaterno" class="escolar_apellidomaterno"><div class="ewTableHeaderCaption"><?php echo $escolar->apellidomaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidomaterno" class="<?php echo $escolar->apellidomaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->apellidomaterno) ?>',2);"><div id="elh_escolar_apellidomaterno" class="escolar_apellidomaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->apellidomaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->nombres->Visible) { // nombres ?>
	<?php if ($escolar->SortUrl($escolar->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $escolar->nombres->HeaderCellClass() ?>"><div id="elh_escolar_nombres" class="escolar_nombres"><div class="ewTableHeaderCaption"><?php echo $escolar->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $escolar->nombres->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->nombres) ?>',2);"><div id="elh_escolar_nombres" class="escolar_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->ci->Visible) { // ci ?>
	<?php if ($escolar->SortUrl($escolar->ci) == "") { ?>
		<th data-name="ci" class="<?php echo $escolar->ci->HeaderCellClass() ?>"><div id="elh_escolar_ci" class="escolar_ci"><div class="ewTableHeaderCaption"><?php echo $escolar->ci->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ci" class="<?php echo $escolar->ci->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->ci) ?>',2);"><div id="elh_escolar_ci" class="escolar_ci">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->ci->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->fechanacimiento->Visible) { // fechanacimiento ?>
	<?php if ($escolar->SortUrl($escolar->fechanacimiento) == "") { ?>
		<th data-name="fechanacimiento" class="<?php echo $escolar->fechanacimiento->HeaderCellClass() ?>"><div id="elh_escolar_fechanacimiento" class="escolar_fechanacimiento"><div class="ewTableHeaderCaption"><?php echo $escolar->fechanacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechanacimiento" class="<?php echo $escolar->fechanacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->fechanacimiento) ?>',2);"><div id="elh_escolar_fechanacimiento" class="escolar_fechanacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->fechanacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->fechanacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->fechanacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->sexo->Visible) { // sexo ?>
	<?php if ($escolar->SortUrl($escolar->sexo) == "") { ?>
		<th data-name="sexo" class="<?php echo $escolar->sexo->HeaderCellClass() ?>"><div id="elh_escolar_sexo" class="escolar_sexo"><div class="ewTableHeaderCaption"><?php echo $escolar->sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sexo" class="<?php echo $escolar->sexo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->sexo) ?>',2);"><div id="elh_escolar_sexo" class="escolar_sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->curso->Visible) { // curso ?>
	<?php if ($escolar->SortUrl($escolar->curso) == "") { ?>
		<th data-name="curso" class="<?php echo $escolar->curso->HeaderCellClass() ?>"><div id="elh_escolar_curso" class="escolar_curso"><div class="ewTableHeaderCaption"><?php echo $escolar->curso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="curso" class="<?php echo $escolar->curso->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->curso) ?>',2);"><div id="elh_escolar_curso" class="escolar_curso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->curso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->curso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->curso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->id_discapacidad->Visible) { // id_discapacidad ?>
	<?php if ($escolar->SortUrl($escolar->id_discapacidad) == "") { ?>
		<th data-name="id_discapacidad" class="<?php echo $escolar->id_discapacidad->HeaderCellClass() ?>"><div id="elh_escolar_id_discapacidad" class="escolar_id_discapacidad"><div class="ewTableHeaderCaption"><?php echo $escolar->id_discapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_discapacidad" class="<?php echo $escolar->id_discapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->id_discapacidad) ?>',2);"><div id="elh_escolar_id_discapacidad" class="escolar_id_discapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->id_discapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->id_discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->id_discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
	<?php if ($escolar->SortUrl($escolar->id_tipodiscapacidad) == "") { ?>
		<th data-name="id_tipodiscapacidad" class="<?php echo $escolar->id_tipodiscapacidad->HeaderCellClass() ?>"><div id="elh_escolar_id_tipodiscapacidad" class="escolar_id_tipodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $escolar->id_tipodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipodiscapacidad" class="<?php echo $escolar->id_tipodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->id_tipodiscapacidad) ?>',2);"><div id="elh_escolar_id_tipodiscapacidad" class="escolar_id_tipodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->id_tipodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->id_tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->id_tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->resultado->Visible) { // resultado ?>
	<?php if ($escolar->SortUrl($escolar->resultado) == "") { ?>
		<th data-name="resultado" class="<?php echo $escolar->resultado->HeaderCellClass() ?>"><div id="elh_escolar_resultado" class="escolar_resultado"><div class="ewTableHeaderCaption"><?php echo $escolar->resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultado" class="<?php echo $escolar->resultado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->resultado) ?>',2);"><div id="elh_escolar_resultado" class="escolar_resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->resultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->resultadotamizaje->Visible) { // resultadotamizaje ?>
	<?php if ($escolar->SortUrl($escolar->resultadotamizaje) == "") { ?>
		<th data-name="resultadotamizaje" class="<?php echo $escolar->resultadotamizaje->HeaderCellClass() ?>"><div id="elh_escolar_resultadotamizaje" class="escolar_resultadotamizaje"><div class="ewTableHeaderCaption"><?php echo $escolar->resultadotamizaje->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultadotamizaje" class="<?php echo $escolar->resultadotamizaje->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->resultadotamizaje) ?>',2);"><div id="elh_escolar_resultadotamizaje" class="escolar_resultadotamizaje">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->resultadotamizaje->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->resultadotamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->resultadotamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->tapon->Visible) { // tapon ?>
	<?php if ($escolar->SortUrl($escolar->tapon) == "") { ?>
		<th data-name="tapon" class="<?php echo $escolar->tapon->HeaderCellClass() ?>"><div id="elh_escolar_tapon" class="escolar_tapon"><div class="ewTableHeaderCaption"><?php echo $escolar->tapon->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tapon" class="<?php echo $escolar->tapon->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->tapon) ?>',2);"><div id="elh_escolar_tapon" class="escolar_tapon">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->tapon->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->tapon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->tapon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->tapodonde->Visible) { // tapodonde ?>
	<?php if ($escolar->SortUrl($escolar->tapodonde) == "") { ?>
		<th data-name="tapodonde" class="<?php echo $escolar->tapodonde->HeaderCellClass() ?>"><div id="elh_escolar_tapodonde" class="escolar_tapodonde"><div class="ewTableHeaderCaption"><?php echo $escolar->tapodonde->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tapodonde" class="<?php echo $escolar->tapodonde->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->tapodonde) ?>',2);"><div id="elh_escolar_tapodonde" class="escolar_tapodonde">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->tapodonde->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->tapodonde->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->tapodonde->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->repetirprueba->Visible) { // repetirprueba ?>
	<?php if ($escolar->SortUrl($escolar->repetirprueba) == "") { ?>
		<th data-name="repetirprueba" class="<?php echo $escolar->repetirprueba->HeaderCellClass() ?>"><div id="elh_escolar_repetirprueba" class="escolar_repetirprueba"><div class="ewTableHeaderCaption"><?php echo $escolar->repetirprueba->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="repetirprueba" class="<?php echo $escolar->repetirprueba->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->repetirprueba) ?>',2);"><div id="elh_escolar_repetirprueba" class="escolar_repetirprueba">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->repetirprueba->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->repetirprueba->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->repetirprueba->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->observaciones->Visible) { // observaciones ?>
	<?php if ($escolar->SortUrl($escolar->observaciones) == "") { ?>
		<th data-name="observaciones" class="<?php echo $escolar->observaciones->HeaderCellClass() ?>"><div id="elh_escolar_observaciones" class="escolar_observaciones"><div class="ewTableHeaderCaption"><?php echo $escolar->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones" class="<?php echo $escolar->observaciones->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->observaciones) ?>',2);"><div id="elh_escolar_observaciones" class="escolar_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->observaciones->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->id_apoderado->Visible) { // id_apoderado ?>
	<?php if ($escolar->SortUrl($escolar->id_apoderado) == "") { ?>
		<th data-name="id_apoderado" class="<?php echo $escolar->id_apoderado->HeaderCellClass() ?>"><div id="elh_escolar_id_apoderado" class="escolar_id_apoderado"><div class="ewTableHeaderCaption"><?php echo $escolar->id_apoderado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_apoderado" class="<?php echo $escolar->id_apoderado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->id_apoderado) ?>',2);"><div id="elh_escolar_id_apoderado" class="escolar_id_apoderado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->id_apoderado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->id_apoderado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->id_apoderado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->id_referencia->Visible) { // id_referencia ?>
	<?php if ($escolar->SortUrl($escolar->id_referencia) == "") { ?>
		<th data-name="id_referencia" class="<?php echo $escolar->id_referencia->HeaderCellClass() ?>"><div id="elh_escolar_id_referencia" class="escolar_id_referencia"><div class="ewTableHeaderCaption"><?php echo $escolar->id_referencia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_referencia" class="<?php echo $escolar->id_referencia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->id_referencia) ?>',2);"><div id="elh_escolar_id_referencia" class="escolar_id_referencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->id_referencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->id_referencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->id_referencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->codigorude->Visible) { // codigorude ?>
	<?php if ($escolar->SortUrl($escolar->codigorude) == "") { ?>
		<th data-name="codigorude" class="<?php echo $escolar->codigorude->HeaderCellClass() ?>"><div id="elh_escolar_codigorude" class="escolar_codigorude"><div class="ewTableHeaderCaption"><?php echo $escolar->codigorude->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigorude" class="<?php echo $escolar->codigorude->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->codigorude) ?>',2);"><div id="elh_escolar_codigorude" class="escolar_codigorude">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->codigorude->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->codigorude->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->codigorude->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->codigorude_es->Visible) { // codigorude_es ?>
	<?php if ($escolar->SortUrl($escolar->codigorude_es) == "") { ?>
		<th data-name="codigorude_es" class="<?php echo $escolar->codigorude_es->HeaderCellClass() ?>"><div id="elh_escolar_codigorude_es" class="escolar_codigorude_es"><div class="ewTableHeaderCaption"><?php echo $escolar->codigorude_es->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigorude_es" class="<?php echo $escolar->codigorude_es->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->codigorude_es) ?>',2);"><div id="elh_escolar_codigorude_es" class="escolar_codigorude_es">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->codigorude_es->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->codigorude_es->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->codigorude_es->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($escolar->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<?php if ($escolar->SortUrl($escolar->nrodiscapacidad) == "") { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $escolar->nrodiscapacidad->HeaderCellClass() ?>"><div id="elh_escolar_nrodiscapacidad" class="escolar_nrodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $escolar->nrodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $escolar->nrodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $escolar->SortUrl($escolar->nrodiscapacidad) ?>',2);"><div id="elh_escolar_nrodiscapacidad" class="escolar_nrodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $escolar->nrodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($escolar->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($escolar->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$escolar_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($escolar->ExportAll && $escolar->Export <> "") {
	$escolar_list->StopRec = $escolar_list->TotalRecs;
} else {

	// Set the last record to display
	if ($escolar_list->TotalRecs > $escolar_list->StartRec + $escolar_list->DisplayRecs - 1)
		$escolar_list->StopRec = $escolar_list->StartRec + $escolar_list->DisplayRecs - 1;
	else
		$escolar_list->StopRec = $escolar_list->TotalRecs;
}
$escolar_list->RecCnt = $escolar_list->StartRec - 1;
if ($escolar_list->Recordset && !$escolar_list->Recordset->EOF) {
	$escolar_list->Recordset->MoveFirst();
	$bSelectLimit = $escolar_list->UseSelectLimit;
	if (!$bSelectLimit && $escolar_list->StartRec > 1)
		$escolar_list->Recordset->Move($escolar_list->StartRec - 1);
} elseif (!$escolar->AllowAddDeleteRow && $escolar_list->StopRec == 0) {
	$escolar_list->StopRec = $escolar->GridAddRowCount;
}

// Initialize aggregate
$escolar->RowType = EW_ROWTYPE_AGGREGATEINIT;
$escolar->ResetAttrs();
$escolar_list->RenderRow();
while ($escolar_list->RecCnt < $escolar_list->StopRec) {
	$escolar_list->RecCnt++;
	if (intval($escolar_list->RecCnt) >= intval($escolar_list->StartRec)) {
		$escolar_list->RowCnt++;

		// Set up key count
		$escolar_list->KeyCount = $escolar_list->RowIndex;

		// Init row class and style
		$escolar->ResetAttrs();
		$escolar->CssClass = "";
		if ($escolar->CurrentAction == "gridadd") {
		} else {
			$escolar_list->LoadRowValues($escolar_list->Recordset); // Load row values
		}
		$escolar->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$escolar->RowAttrs = array_merge($escolar->RowAttrs, array('data-rowindex'=>$escolar_list->RowCnt, 'id'=>'r' . $escolar_list->RowCnt . '_escolar', 'data-rowtype'=>$escolar->RowType));

		// Render row
		$escolar_list->RenderRow();

		// Render list options
		$escolar_list->RenderListOptions();
?>
	<tr<?php echo $escolar->RowAttributes() ?>>
<?php

// Render list options (body, left)
$escolar_list->ListOptions->Render("body", "left", $escolar_list->RowCnt);
?>
	<?php if ($escolar->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $escolar->fecha->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_fecha" class="escolar_fecha">
<span<?php echo $escolar->fecha->ViewAttributes() ?>>
<?php echo $escolar->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->unidadeducativa->Visible) { // unidadeducativa ?>
		<td data-name="unidadeducativa"<?php echo $escolar->unidadeducativa->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_unidadeducativa" class="escolar_unidadeducativa">
<span<?php echo $escolar->unidadeducativa->ViewAttributes() ?>>
<?php echo $escolar->unidadeducativa->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->apellidopaterno->Visible) { // apellidopaterno ?>
		<td data-name="apellidopaterno"<?php echo $escolar->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_apellidopaterno" class="escolar_apellidopaterno">
<span<?php echo $escolar->apellidopaterno->ViewAttributes() ?>>
<?php echo $escolar->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->apellidomaterno->Visible) { // apellidomaterno ?>
		<td data-name="apellidomaterno"<?php echo $escolar->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_apellidomaterno" class="escolar_apellidomaterno">
<span<?php echo $escolar->apellidomaterno->ViewAttributes() ?>>
<?php echo $escolar->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $escolar->nombres->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_nombres" class="escolar_nombres">
<span<?php echo $escolar->nombres->ViewAttributes() ?>>
<?php echo $escolar->nombres->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->ci->Visible) { // ci ?>
		<td data-name="ci"<?php echo $escolar->ci->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_ci" class="escolar_ci">
<span<?php echo $escolar->ci->ViewAttributes() ?>>
<?php echo $escolar->ci->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->fechanacimiento->Visible) { // fechanacimiento ?>
		<td data-name="fechanacimiento"<?php echo $escolar->fechanacimiento->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_fechanacimiento" class="escolar_fechanacimiento">
<span<?php echo $escolar->fechanacimiento->ViewAttributes() ?>>
<?php echo $escolar->fechanacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->sexo->Visible) { // sexo ?>
		<td data-name="sexo"<?php echo $escolar->sexo->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_sexo" class="escolar_sexo">
<span<?php echo $escolar->sexo->ViewAttributes() ?>>
<?php echo $escolar->sexo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->curso->Visible) { // curso ?>
		<td data-name="curso"<?php echo $escolar->curso->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_curso" class="escolar_curso">
<span<?php echo $escolar->curso->ViewAttributes() ?>>
<?php echo $escolar->curso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->id_discapacidad->Visible) { // id_discapacidad ?>
		<td data-name="id_discapacidad"<?php echo $escolar->id_discapacidad->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_id_discapacidad" class="escolar_id_discapacidad">
<span<?php echo $escolar->id_discapacidad->ViewAttributes() ?>>
<?php echo $escolar->id_discapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
		<td data-name="id_tipodiscapacidad"<?php echo $escolar->id_tipodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_id_tipodiscapacidad" class="escolar_id_tipodiscapacidad">
<span<?php echo $escolar->id_tipodiscapacidad->ViewAttributes() ?>>
<?php echo $escolar->id_tipodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->resultado->Visible) { // resultado ?>
		<td data-name="resultado"<?php echo $escolar->resultado->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_resultado" class="escolar_resultado">
<span<?php echo $escolar->resultado->ViewAttributes() ?>>
<?php echo $escolar->resultado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->resultadotamizaje->Visible) { // resultadotamizaje ?>
		<td data-name="resultadotamizaje"<?php echo $escolar->resultadotamizaje->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_resultadotamizaje" class="escolar_resultadotamizaje">
<span<?php echo $escolar->resultadotamizaje->ViewAttributes() ?>>
<?php echo $escolar->resultadotamizaje->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->tapon->Visible) { // tapon ?>
		<td data-name="tapon"<?php echo $escolar->tapon->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_tapon" class="escolar_tapon">
<span<?php echo $escolar->tapon->ViewAttributes() ?>>
<?php echo $escolar->tapon->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->tapodonde->Visible) { // tapodonde ?>
		<td data-name="tapodonde"<?php echo $escolar->tapodonde->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_tapodonde" class="escolar_tapodonde">
<span<?php echo $escolar->tapodonde->ViewAttributes() ?>>
<?php echo $escolar->tapodonde->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->repetirprueba->Visible) { // repetirprueba ?>
		<td data-name="repetirprueba"<?php echo $escolar->repetirprueba->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_repetirprueba" class="escolar_repetirprueba">
<span<?php echo $escolar->repetirprueba->ViewAttributes() ?>>
<?php echo $escolar->repetirprueba->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $escolar->observaciones->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_observaciones" class="escolar_observaciones">
<span<?php echo $escolar->observaciones->ViewAttributes() ?>>
<?php echo $escolar->observaciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->id_apoderado->Visible) { // id_apoderado ?>
		<td data-name="id_apoderado"<?php echo $escolar->id_apoderado->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_id_apoderado" class="escolar_id_apoderado">
<span<?php echo $escolar->id_apoderado->ViewAttributes() ?>>
<?php echo $escolar->id_apoderado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->id_referencia->Visible) { // id_referencia ?>
		<td data-name="id_referencia"<?php echo $escolar->id_referencia->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_id_referencia" class="escolar_id_referencia">
<span<?php echo $escolar->id_referencia->ViewAttributes() ?>>
<?php echo $escolar->id_referencia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->codigorude->Visible) { // codigorude ?>
		<td data-name="codigorude"<?php echo $escolar->codigorude->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_codigorude" class="escolar_codigorude">
<span<?php echo $escolar->codigorude->ViewAttributes() ?>>
<?php echo $escolar->codigorude->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->codigorude_es->Visible) { // codigorude_es ?>
		<td data-name="codigorude_es"<?php echo $escolar->codigorude_es->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_codigorude_es" class="escolar_codigorude_es">
<span<?php echo $escolar->codigorude_es->ViewAttributes() ?>>
<?php echo $escolar->codigorude_es->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($escolar->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td data-name="nrodiscapacidad"<?php echo $escolar->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $escolar_list->RowCnt ?>_escolar_nrodiscapacidad" class="escolar_nrodiscapacidad">
<span<?php echo $escolar->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $escolar->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$escolar_list->ListOptions->Render("body", "right", $escolar_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($escolar->CurrentAction <> "gridadd")
		$escolar_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($escolar->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($escolar_list->Recordset)
	$escolar_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($escolar->CurrentAction <> "gridadd" && $escolar->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($escolar_list->Pager)) $escolar_list->Pager = new cPrevNextPager($escolar_list->StartRec, $escolar_list->DisplayRecs, $escolar_list->TotalRecs, $escolar_list->AutoHidePager) ?>
<?php if ($escolar_list->Pager->RecordCount > 0 && $escolar_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($escolar_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $escolar_list->PageUrl() ?>start=<?php echo $escolar_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($escolar_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $escolar_list->PageUrl() ?>start=<?php echo $escolar_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $escolar_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($escolar_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $escolar_list->PageUrl() ?>start=<?php echo $escolar_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($escolar_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $escolar_list->PageUrl() ?>start=<?php echo $escolar_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $escolar_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($escolar_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $escolar_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $escolar_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $escolar_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($escolar_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($escolar_list->TotalRecs == 0 && $escolar->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($escolar_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fescolarlistsrch.FilterList = <?php echo $escolar_list->GetFilterList() ?>;
fescolarlistsrch.Init();
fescolarlist.Init();
</script>
<?php
$escolar_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$escolar_list->Page_Terminate();
?>
