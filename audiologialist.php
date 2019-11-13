<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "audiologiainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "pruebasaudiologiagridcls.php" ?>
<?php include_once "diagnosticoaudiologiagridcls.php" ?>
<?php include_once "tratamientogridcls.php" ?>
<?php include_once "derivaciongridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$audiologia_list = NULL; // Initialize page object first

class caudiologia_list extends caudiologia {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'audiologia';

	// Page object name
	var $PageObjName = 'audiologia_list';

	// Grid form hidden field names
	var $FormName = 'faudiologialist';
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

		// Table object (audiologia)
		if (!isset($GLOBALS["audiologia"]) || get_class($GLOBALS["audiologia"]) == "caudiologia") {
			$GLOBALS["audiologia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["audiologia"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "audiologiaadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "audiologiadelete.php";
		$this->MultiUpdateUrl = "audiologiaupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'audiologia', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption faudiologialistsrch";

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
		$this->id_especialista->SetVisibility();
		$this->especialidad->SetVisibility();
		$this->fecha->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->fecha->Visible = FALSE;
		$this->id_escolar->SetVisibility();
		$this->id_neonato->SetVisibility();
		$this->id_otros->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->id_atencion->SetVisibility();

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

			// Get the keys for master table
			$sDetailTblVar = $this->getCurrentDetailTable();
			if ($sDetailTblVar <> "") {
				$DetailTblVar = explode(",", $sDetailTblVar);
				if (in_array("pruebasaudiologia", $DetailTblVar)) {

					// Process auto fill for detail table 'pruebasaudiologia'
					if (preg_match('/^fpruebasaudiologia(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["pruebasaudiologia_grid"])) $GLOBALS["pruebasaudiologia_grid"] = new cpruebasaudiologia_grid;
						$GLOBALS["pruebasaudiologia_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
				if (in_array("diagnosticoaudiologia", $DetailTblVar)) {

					// Process auto fill for detail table 'diagnosticoaudiologia'
					if (preg_match('/^fdiagnosticoaudiologia(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["diagnosticoaudiologia_grid"])) $GLOBALS["diagnosticoaudiologia_grid"] = new cdiagnosticoaudiologia_grid;
						$GLOBALS["diagnosticoaudiologia_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
				if (in_array("tratamiento", $DetailTblVar)) {

					// Process auto fill for detail table 'tratamiento'
					if (preg_match('/^ftratamiento(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["tratamiento_grid"])) $GLOBALS["tratamiento_grid"] = new ctratamiento_grid;
						$GLOBALS["tratamiento_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
				if (in_array("derivacion", $DetailTblVar)) {

					// Process auto fill for detail table 'derivacion'
					if (preg_match('/^fderivacion(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["derivacion_grid"])) $GLOBALS["derivacion_grid"] = new cderivacion_grid;
						$GLOBALS["derivacion_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
			}
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
		global $EW_EXPORT, $audiologia;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($audiologia);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "faudiologialistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->id_especialista->AdvancedSearch->ToJson(), ","); // Field id_especialista
		$sFilterList = ew_Concat($sFilterList, $this->especialidad->AdvancedSearch->ToJson(), ","); // Field especialidad
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJson(), ","); // Field fecha
		$sFilterList = ew_Concat($sFilterList, $this->id_escolar->AdvancedSearch->ToJson(), ","); // Field id_escolar
		$sFilterList = ew_Concat($sFilterList, $this->id_neonato->AdvancedSearch->ToJson(), ","); // Field id_neonato
		$sFilterList = ew_Concat($sFilterList, $this->id_otros->AdvancedSearch->ToJson(), ","); // Field id_otros
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJson(), ","); // Field observaciones
		$sFilterList = ew_Concat($sFilterList, $this->id_atencion->AdvancedSearch->ToJson(), ","); // Field id_atencion
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "faudiologialistsrch", $filters);

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

		// Field id_especialista
		$this->id_especialista->AdvancedSearch->SearchValue = @$filter["x_id_especialista"];
		$this->id_especialista->AdvancedSearch->SearchOperator = @$filter["z_id_especialista"];
		$this->id_especialista->AdvancedSearch->SearchCondition = @$filter["v_id_especialista"];
		$this->id_especialista->AdvancedSearch->SearchValue2 = @$filter["y_id_especialista"];
		$this->id_especialista->AdvancedSearch->SearchOperator2 = @$filter["w_id_especialista"];
		$this->id_especialista->AdvancedSearch->Save();

		// Field especialidad
		$this->especialidad->AdvancedSearch->SearchValue = @$filter["x_especialidad"];
		$this->especialidad->AdvancedSearch->SearchOperator = @$filter["z_especialidad"];
		$this->especialidad->AdvancedSearch->SearchCondition = @$filter["v_especialidad"];
		$this->especialidad->AdvancedSearch->SearchValue2 = @$filter["y_especialidad"];
		$this->especialidad->AdvancedSearch->SearchOperator2 = @$filter["w_especialidad"];
		$this->especialidad->AdvancedSearch->Save();

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();

		// Field id_escolar
		$this->id_escolar->AdvancedSearch->SearchValue = @$filter["x_id_escolar"];
		$this->id_escolar->AdvancedSearch->SearchOperator = @$filter["z_id_escolar"];
		$this->id_escolar->AdvancedSearch->SearchCondition = @$filter["v_id_escolar"];
		$this->id_escolar->AdvancedSearch->SearchValue2 = @$filter["y_id_escolar"];
		$this->id_escolar->AdvancedSearch->SearchOperator2 = @$filter["w_id_escolar"];
		$this->id_escolar->AdvancedSearch->Save();

		// Field id_neonato
		$this->id_neonato->AdvancedSearch->SearchValue = @$filter["x_id_neonato"];
		$this->id_neonato->AdvancedSearch->SearchOperator = @$filter["z_id_neonato"];
		$this->id_neonato->AdvancedSearch->SearchCondition = @$filter["v_id_neonato"];
		$this->id_neonato->AdvancedSearch->SearchValue2 = @$filter["y_id_neonato"];
		$this->id_neonato->AdvancedSearch->SearchOperator2 = @$filter["w_id_neonato"];
		$this->id_neonato->AdvancedSearch->Save();

		// Field id_otros
		$this->id_otros->AdvancedSearch->SearchValue = @$filter["x_id_otros"];
		$this->id_otros->AdvancedSearch->SearchOperator = @$filter["z_id_otros"];
		$this->id_otros->AdvancedSearch->SearchCondition = @$filter["v_id_otros"];
		$this->id_otros->AdvancedSearch->SearchValue2 = @$filter["y_id_otros"];
		$this->id_otros->AdvancedSearch->SearchOperator2 = @$filter["w_id_otros"];
		$this->id_otros->AdvancedSearch->Save();

		// Field observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$filter["x_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator = @$filter["z_observaciones"];
		$this->observaciones->AdvancedSearch->SearchCondition = @$filter["v_observaciones"];
		$this->observaciones->AdvancedSearch->SearchValue2 = @$filter["y_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones"];
		$this->observaciones->AdvancedSearch->Save();

		// Field id_atencion
		$this->id_atencion->AdvancedSearch->SearchValue = @$filter["x_id_atencion"];
		$this->id_atencion->AdvancedSearch->SearchOperator = @$filter["z_id_atencion"];
		$this->id_atencion->AdvancedSearch->SearchCondition = @$filter["v_id_atencion"];
		$this->id_atencion->AdvancedSearch->SearchValue2 = @$filter["y_id_atencion"];
		$this->id_atencion->AdvancedSearch->SearchOperator2 = @$filter["w_id_atencion"];
		$this->id_atencion->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->id_especialista, $Default, FALSE); // id_especialista
		$this->BuildSearchSql($sWhere, $this->especialidad, $Default, FALSE); // especialidad
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->id_escolar, $Default, FALSE); // id_escolar
		$this->BuildSearchSql($sWhere, $this->id_neonato, $Default, FALSE); // id_neonato
		$this->BuildSearchSql($sWhere, $this->id_otros, $Default, FALSE); // id_otros
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->id_atencion, $Default, FALSE); // id_atencion

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->id_especialista->AdvancedSearch->Save(); // id_especialista
			$this->especialidad->AdvancedSearch->Save(); // especialidad
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->id_escolar->AdvancedSearch->Save(); // id_escolar
			$this->id_neonato->AdvancedSearch->Save(); // id_neonato
			$this->id_otros->AdvancedSearch->Save(); // id_otros
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->id_atencion->AdvancedSearch->Save(); // id_atencion
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
		if ($this->id_especialista->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->especialidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_escolar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_neonato->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_otros->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_atencion->AdvancedSearch->IssetSession())
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
		$this->id_especialista->AdvancedSearch->UnsetSession();
		$this->especialidad->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->id_escolar->AdvancedSearch->UnsetSession();
		$this->id_neonato->AdvancedSearch->UnsetSession();
		$this->id_otros->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->id_atencion->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->id_especialista->AdvancedSearch->Load();
		$this->especialidad->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->id_escolar->AdvancedSearch->Load();
		$this->id_neonato->AdvancedSearch->Load();
		$this->id_otros->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->id_atencion->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_especialista, $bCtrl); // id_especialista
			$this->UpdateSort($this->especialidad, $bCtrl); // especialidad
			$this->UpdateSort($this->fecha, $bCtrl); // fecha
			$this->UpdateSort($this->id_escolar, $bCtrl); // id_escolar
			$this->UpdateSort($this->id_neonato, $bCtrl); // id_neonato
			$this->UpdateSort($this->id_otros, $bCtrl); // id_otros
			$this->UpdateSort($this->observaciones, $bCtrl); // observaciones
			$this->UpdateSort($this->id_atencion, $bCtrl); // id_atencion
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
				$this->id_atencion->setSort("DESC");
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
				$this->id_especialista->setSort("");
				$this->especialidad->setSort("");
				$this->fecha->setSort("");
				$this->id_escolar->setSort("");
				$this->id_neonato->setSort("");
				$this->id_otros->setSort("");
				$this->observaciones->setSort("");
				$this->id_atencion->setSort("");
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

		// "detail_pruebasaudiologia"
		$item = &$this->ListOptions->Add("detail_pruebasaudiologia");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'pruebasaudiologia') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["pruebasaudiologia_grid"])) $GLOBALS["pruebasaudiologia_grid"] = new cpruebasaudiologia_grid;

		// "detail_diagnosticoaudiologia"
		$item = &$this->ListOptions->Add("detail_diagnosticoaudiologia");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'diagnosticoaudiologia') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["diagnosticoaudiologia_grid"])) $GLOBALS["diagnosticoaudiologia_grid"] = new cdiagnosticoaudiologia_grid;

		// "detail_tratamiento"
		$item = &$this->ListOptions->Add("detail_tratamiento");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'tratamiento') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["tratamiento_grid"])) $GLOBALS["tratamiento_grid"] = new ctratamiento_grid;

		// "detail_derivacion"
		$item = &$this->ListOptions->Add("detail_derivacion");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'derivacion') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["derivacion_grid"])) $GLOBALS["derivacion_grid"] = new cderivacion_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssClass = "text-nowrap";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("pruebasaudiologia");
		$pages->Add("diagnosticoaudiologia");
		$pages->Add("tratamiento");
		$pages->Add("derivacion");
		$this->DetailPages = $pages;

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
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_pruebasaudiologia"
		$oListOpt = &$this->ListOptions->Items["detail_pruebasaudiologia"];
		if ($Security->AllowList(CurrentProjectID() . 'pruebasaudiologia')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("pruebasaudiologia", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("pruebasaudiologialist.php?" . EW_TABLE_SHOW_MASTER . "=audiologia&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["pruebasaudiologia_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'pruebasaudiologia')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=pruebasaudiologia");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "pruebasaudiologia";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_diagnosticoaudiologia"
		$oListOpt = &$this->ListOptions->Items["detail_diagnosticoaudiologia"];
		if ($Security->AllowList(CurrentProjectID() . 'diagnosticoaudiologia')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("diagnosticoaudiologia", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("diagnosticoaudiologialist.php?" . EW_TABLE_SHOW_MASTER . "=audiologia&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["diagnosticoaudiologia_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'diagnosticoaudiologia')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=diagnosticoaudiologia");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "diagnosticoaudiologia";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_tratamiento"
		$oListOpt = &$this->ListOptions->Items["detail_tratamiento"];
		if ($Security->AllowList(CurrentProjectID() . 'tratamiento')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("tratamiento", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("tratamientolist.php?" . EW_TABLE_SHOW_MASTER . "=audiologia&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["tratamiento_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'tratamiento')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=tratamiento");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "tratamiento";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_derivacion"
		$oListOpt = &$this->ListOptions->Items["detail_derivacion"];
		if ($Security->AllowList(CurrentProjectID() . 'derivacion')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("derivacion", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("derivacionlist.php?" . EW_TABLE_SHOW_MASTER . "=audiologia&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["derivacion_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'derivacion')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=derivacion");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "derivacion";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"faudiologialistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"faudiologialistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.faudiologialist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"faudiologialistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

		// id_especialista
		$this->id_especialista->AdvancedSearch->SearchValue = @$_GET["x_id_especialista"];
		if ($this->id_especialista->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_especialista->AdvancedSearch->SearchOperator = @$_GET["z_id_especialista"];

		// especialidad
		$this->especialidad->AdvancedSearch->SearchValue = @$_GET["x_especialidad"];
		if ($this->especialidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->especialidad->AdvancedSearch->SearchOperator = @$_GET["z_especialidad"];

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = @$_GET["x_fecha"];
		if ($this->fecha->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$_GET["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$_GET["y_fecha"];
		if ($this->fecha->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator2 = @$_GET["w_fecha"];

		// id_escolar
		$this->id_escolar->AdvancedSearch->SearchValue = @$_GET["x_id_escolar"];
		if ($this->id_escolar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_escolar->AdvancedSearch->SearchOperator = @$_GET["z_id_escolar"];

		// id_neonato
		$this->id_neonato->AdvancedSearch->SearchValue = @$_GET["x_id_neonato"];
		if ($this->id_neonato->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_neonato->AdvancedSearch->SearchOperator = @$_GET["z_id_neonato"];

		// id_otros
		$this->id_otros->AdvancedSearch->SearchValue = @$_GET["x_id_otros"];
		if ($this->id_otros->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_otros->AdvancedSearch->SearchOperator = @$_GET["z_id_otros"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$_GET["x_observaciones"];
		if ($this->observaciones->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];

		// id_atencion
		$this->id_atencion->AdvancedSearch->SearchValue = @$_GET["x_id_atencion"];
		if ($this->id_atencion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_atencion->AdvancedSearch->SearchOperator = @$_GET["z_id_atencion"];
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
		$this->id_especialista->setDbValue($row['id_especialista']);
		$this->especialidad->setDbValue($row['especialidad']);
		$this->fecha->setDbValue($row['fecha']);
		$this->id_escolar->setDbValue($row['id_escolar']);
		$this->id_neonato->setDbValue($row['id_neonato']);
		$this->id_otros->setDbValue($row['id_otros']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_atencion->setDbValue($row['id_atencion']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_especialista'] = NULL;
		$row['especialidad'] = NULL;
		$row['fecha'] = NULL;
		$row['id_escolar'] = NULL;
		$row['id_neonato'] = NULL;
		$row['id_otros'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_atencion'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_especialista->DbValue = $row['id_especialista'];
		$this->especialidad->DbValue = $row['especialidad'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_escolar->DbValue = $row['id_escolar'];
		$this->id_neonato->DbValue = $row['id_neonato'];
		$this->id_otros->DbValue = $row['id_otros'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_atencion->DbValue = $row['id_atencion'];
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
		// id_especialista
		// especialidad
		// fecha
		// id_escolar
		// id_neonato
		// id_otros
		// observaciones
		// id_atencion
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_especialista
		$this->id_especialista->ViewValue = $this->id_especialista->CurrentValue;
		if (strval($this->id_especialista->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_especialista->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `especialidad` AS `Disp4Fld` FROM `especialista`";
		$sWhereWrk = "";
		$this->id_especialista->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->id_especialista->ViewValue = $this->id_especialista->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_especialista->ViewValue = $this->id_especialista->CurrentValue;
			}
		} else {
			$this->id_especialista->ViewValue = NULL;
		}
		$this->id_especialista->ViewCustomAttributes = "";

		// especialidad
		$this->especialidad->ViewValue = $this->especialidad->CurrentValue;
		if (strval($this->especialidad->CurrentValue) <> "") {
			$sFilterWrk = "`nombre`" . ew_SearchString("=", $this->especialidad->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `nombre`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoespecialidad`";
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

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// id_escolar
		if (strval($this->id_escolar->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_escolar->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `escolar`";
		$sWhereWrk = "";
		$this->id_escolar->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_escolar, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_escolar->ViewValue = $this->id_escolar->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_escolar->ViewValue = $this->id_escolar->CurrentValue;
			}
		} else {
			$this->id_escolar->ViewValue = NULL;
		}
		$this->id_escolar->ViewCustomAttributes = "";

		// id_neonato
		$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
		if (strval($this->id_neonato->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_neonato->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
		$sWhereWrk = "";
		$this->id_neonato->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_neonato->ViewValue = $this->id_neonato->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
			}
		} else {
			$this->id_neonato->ViewValue = NULL;
		}
		$this->id_neonato->ViewCustomAttributes = "";

		// id_otros
		if (strval($this->id_otros->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_otros->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `otros`";
		$sWhereWrk = "";
		$this->id_otros->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_otros, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_otros->ViewValue = $this->id_otros->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_otros->ViewValue = $this->id_otros->CurrentValue;
			}
		} else {
			$this->id_otros->ViewValue = NULL;
		}
		$this->id_otros->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_atencion
		$this->id_atencion->ViewValue = $this->id_atencion->CurrentValue;
		$this->id_atencion->ViewCustomAttributes = "";

			// id_especialista
			$this->id_especialista->LinkCustomAttributes = "";
			$this->id_especialista->HrefValue = "";
			$this->id_especialista->TooltipValue = "";

			// especialidad
			$this->especialidad->LinkCustomAttributes = "";
			$this->especialidad->HrefValue = "";
			$this->especialidad->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// id_escolar
			$this->id_escolar->LinkCustomAttributes = "";
			$this->id_escolar->HrefValue = "";
			$this->id_escolar->TooltipValue = "";

			// id_neonato
			$this->id_neonato->LinkCustomAttributes = "";
			$this->id_neonato->HrefValue = "";
			$this->id_neonato->TooltipValue = "";

			// id_otros
			$this->id_otros->LinkCustomAttributes = "";
			$this->id_otros->HrefValue = "";
			$this->id_otros->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// id_atencion
			$this->id_atencion->LinkCustomAttributes = "";
			$this->id_atencion->HrefValue = "";
			$this->id_atencion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_especialista
			$this->id_especialista->EditAttrs["class"] = "form-control";
			$this->id_especialista->EditCustomAttributes = "";
			$this->id_especialista->EditValue = ew_HtmlEncode($this->id_especialista->AdvancedSearch->SearchValue);
			if (strval($this->id_especialista->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_especialista->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `especialidad` AS `Disp4Fld` FROM `especialista`";
			$sWhereWrk = "";
			$this->id_especialista->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$arwrk[4] = ew_HtmlEncode($rswrk->fields('Disp4Fld'));
					$this->id_especialista->EditValue = $this->id_especialista->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_especialista->EditValue = ew_HtmlEncode($this->id_especialista->AdvancedSearch->SearchValue);
				}
			} else {
				$this->id_especialista->EditValue = NULL;
			}
			$this->id_especialista->PlaceHolder = ew_RemoveHtml($this->id_especialista->FldCaption());

			// especialidad
			$this->especialidad->EditAttrs["class"] = "form-control";
			$this->especialidad->EditCustomAttributes = "";
			$this->especialidad->EditValue = ew_HtmlEncode($this->especialidad->AdvancedSearch->SearchValue);
			if (strval($this->especialidad->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`nombre`" . ew_SearchString("=", $this->especialidad->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `nombre`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoespecialidad`";
			$sWhereWrk = "";
			$this->especialidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->especialidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->especialidad->EditValue = $this->especialidad->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->especialidad->EditValue = ew_HtmlEncode($this->especialidad->AdvancedSearch->SearchValue);
				}
			} else {
				$this->especialidad->EditValue = NULL;
			}
			$this->especialidad->PlaceHolder = ew_RemoveHtml($this->especialidad->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue2, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// id_escolar
			$this->id_escolar->EditAttrs["class"] = "form-control";
			$this->id_escolar->EditCustomAttributes = "";
			if (trim(strval($this->id_escolar->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_escolar->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `escolar`";
			$sWhereWrk = "";
			$this->id_escolar->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_escolar, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_escolar->EditValue = $arwrk;

			// id_neonato
			$this->id_neonato->EditAttrs["class"] = "form-control";
			$this->id_neonato->EditCustomAttributes = "";
			$this->id_neonato->EditValue = ew_HtmlEncode($this->id_neonato->AdvancedSearch->SearchValue);
			if (strval($this->id_neonato->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_neonato->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
			$sWhereWrk = "";
			$this->id_neonato->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->id_neonato->EditValue = $this->id_neonato->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_neonato->EditValue = ew_HtmlEncode($this->id_neonato->AdvancedSearch->SearchValue);
				}
			} else {
				$this->id_neonato->EditValue = NULL;
			}
			$this->id_neonato->PlaceHolder = ew_RemoveHtml($this->id_neonato->FldCaption());

			// id_otros
			$this->id_otros->EditAttrs["class"] = "form-control";
			$this->id_otros->EditCustomAttributes = "";
			if (trim(strval($this->id_otros->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_otros->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `otros`";
			$sWhereWrk = "";
			$this->id_otros->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_otros, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_otros->EditValue = $arwrk;

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->AdvancedSearch->SearchValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// id_atencion
			$this->id_atencion->EditAttrs["class"] = "form-control";
			$this->id_atencion->EditCustomAttributes = "";
			$this->id_atencion->EditValue = ew_HtmlEncode($this->id_atencion->AdvancedSearch->SearchValue);
			$this->id_atencion->PlaceHolder = ew_RemoveHtml($this->id_atencion->FldCaption());
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
		if (!ew_CheckInteger($this->id_especialista->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_especialista->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fecha->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fecha->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_neonato->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_neonato->FldErrMsg());
		}
		if (!ew_CheckInteger($this->observaciones->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->observaciones->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_atencion->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_atencion->FldErrMsg());
		}

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
		$this->id_especialista->AdvancedSearch->Load();
		$this->especialidad->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->id_escolar->AdvancedSearch->Load();
		$this->id_neonato->AdvancedSearch->Load();
		$this->id_otros->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->id_atencion->AdvancedSearch->Load();
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
		case "x_id_especialista":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `especialidad` AS `Disp4Fld` FROM `especialista`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_especialidad":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `nombre` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipoespecialidad`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`nombre` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->especialidad, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_escolar":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `escolar`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_escolar, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_neonato":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_otros":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `otros`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_otros, $sWhereWrk); // Call Lookup Selecting
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
		case "x_id_especialista":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, `especialidad` AS `Disp4Fld` FROM `especialista`";
				$sWhereWrk = "`nombres` LIKE '{query_value}%' OR CONCAT(`nombres`,'" . ew_ValueSeparator(1, $this->id_especialista) . "',`apellidopaterno`,'" . ew_ValueSeparator(2, $this->id_especialista) . "',`apellidomaterno`,'" . ew_ValueSeparator(3, $this->id_especialista) . "',`especialidad`) LIKE '{query_value}%'";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_especialista, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_especialidad":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `nombre`, `nombre` AS `DispFld` FROM `tipoespecialidad`";
				$sWhereWrk = "`nombre` LIKE '{query_value}%'";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->especialidad, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_neonato":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld` FROM `neonatal`";
				$sWhereWrk = "`nombre` LIKE '{query_value}%' OR CONCAT(`nombre`,'" . ew_ValueSeparator(1, $this->id_neonato) . "',`apellidopaterno`,'" . ew_ValueSeparator(2, $this->id_neonato) . "',`apellidomaterno`) LIKE '{query_value}%'";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($audiologia_list)) $audiologia_list = new caudiologia_list();

// Page init
$audiologia_list->Page_Init();

// Page main
$audiologia_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$audiologia_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = faudiologialist = new ew_Form("faudiologialist", "list");
faudiologialist.FormKeyCountName = '<?php echo $audiologia_list->FormKeyCountName ?>';

// Form_CustomValidate event
faudiologialist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
faudiologialist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
faudiologialist.Lists["x_id_especialista"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno","x_especialidad"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"especialista"};
faudiologialist.Lists["x_id_especialista"].Data = "<?php echo $audiologia_list->id_especialista->LookupFilterQuery(FALSE, "list") ?>";
faudiologialist.AutoSuggests["x_id_especialista"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_list->id_especialista->LookupFilterQuery(TRUE, "list"))) ?>;
faudiologialist.Lists["x_especialidad"] = {"LinkField":"x_nombre","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoespecialidad"};
faudiologialist.Lists["x_especialidad"].Data = "<?php echo $audiologia_list->especialidad->LookupFilterQuery(FALSE, "list") ?>";
faudiologialist.AutoSuggests["x_especialidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_list->especialidad->LookupFilterQuery(TRUE, "list"))) ?>;
faudiologialist.Lists["x_id_escolar"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"escolar"};
faudiologialist.Lists["x_id_escolar"].Data = "<?php echo $audiologia_list->id_escolar->LookupFilterQuery(FALSE, "list") ?>";
faudiologialist.Lists["x_id_neonato"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"neonatal"};
faudiologialist.Lists["x_id_neonato"].Data = "<?php echo $audiologia_list->id_neonato->LookupFilterQuery(FALSE, "list") ?>";
faudiologialist.AutoSuggests["x_id_neonato"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_list->id_neonato->LookupFilterQuery(TRUE, "list"))) ?>;
faudiologialist.Lists["x_id_otros"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"otros"};
faudiologialist.Lists["x_id_otros"].Data = "<?php echo $audiologia_list->id_otros->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = faudiologialistsrch = new ew_Form("faudiologialistsrch");

// Validate function for search
faudiologialistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id_especialista");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->id_especialista->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fecha");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->fecha->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_id_neonato");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->id_neonato->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_observaciones");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->observaciones->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_id_atencion");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($audiologia->id_atencion->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
faudiologialistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
faudiologialistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
faudiologialistsrch.Lists["x_id_especialista"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno","x_especialidad"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"especialista"};
faudiologialistsrch.Lists["x_id_especialista"].Data = "<?php echo $audiologia_list->id_especialista->LookupFilterQuery(FALSE, "extbs") ?>";
faudiologialistsrch.AutoSuggests["x_id_especialista"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_list->id_especialista->LookupFilterQuery(TRUE, "extbs"))) ?>;
faudiologialistsrch.Lists["x_especialidad"] = {"LinkField":"x_nombre","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoespecialidad"};
faudiologialistsrch.Lists["x_especialidad"].Data = "<?php echo $audiologia_list->especialidad->LookupFilterQuery(FALSE, "extbs") ?>";
faudiologialistsrch.AutoSuggests["x_especialidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_list->especialidad->LookupFilterQuery(TRUE, "extbs"))) ?>;
faudiologialistsrch.Lists["x_id_escolar"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"escolar"};
faudiologialistsrch.Lists["x_id_escolar"].Data = "<?php echo $audiologia_list->id_escolar->LookupFilterQuery(FALSE, "extbs") ?>";
faudiologialistsrch.Lists["x_id_neonato"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"neonatal"};
faudiologialistsrch.Lists["x_id_neonato"].Data = "<?php echo $audiologia_list->id_neonato->LookupFilterQuery(FALSE, "extbs") ?>";
faudiologialistsrch.AutoSuggests["x_id_neonato"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $audiologia_list->id_neonato->LookupFilterQuery(TRUE, "extbs"))) ?>;
faudiologialistsrch.Lists["x_id_otros"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"otros"};
faudiologialistsrch.Lists["x_id_otros"].Data = "<?php echo $audiologia_list->id_otros->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($audiologia_list->TotalRecs > 0 && $audiologia_list->ExportOptions->Visible()) { ?>
<?php $audiologia_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($audiologia_list->SearchOptions->Visible()) { ?>
<?php $audiologia_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($audiologia_list->FilterOptions->Visible()) { ?>
<?php $audiologia_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $audiologia_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($audiologia_list->TotalRecs <= 0)
			$audiologia_list->TotalRecs = $audiologia->ListRecordCount();
	} else {
		if (!$audiologia_list->Recordset && ($audiologia_list->Recordset = $audiologia_list->LoadRecordset()))
			$audiologia_list->TotalRecs = $audiologia_list->Recordset->RecordCount();
	}
	$audiologia_list->StartRec = 1;
	if ($audiologia_list->DisplayRecs <= 0 || ($audiologia->Export <> "" && $audiologia->ExportAll)) // Display all records
		$audiologia_list->DisplayRecs = $audiologia_list->TotalRecs;
	if (!($audiologia->Export <> "" && $audiologia->ExportAll))
		$audiologia_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$audiologia_list->Recordset = $audiologia_list->LoadRecordset($audiologia_list->StartRec-1, $audiologia_list->DisplayRecs);

	// Set no record found message
	if ($audiologia->CurrentAction == "" && $audiologia_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$audiologia_list->setWarningMessage(ew_DeniedMsg());
		if ($audiologia_list->SearchWhere == "0=101")
			$audiologia_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$audiologia_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$audiologia_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($audiologia->Export == "" && $audiologia->CurrentAction == "") { ?>
<form name="faudiologialistsrch" id="faudiologialistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($audiologia_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="faudiologialistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="audiologia">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$audiologia_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$audiologia->RowType = EW_ROWTYPE_SEARCH;

// Render row
$audiologia->ResetAttrs();
$audiologia_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($audiologia->id_especialista->Visible) { // id_especialista ?>
	<div id="xsc_id_especialista" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $audiologia->id_especialista->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_especialista" id="z_id_especialista" value="="></span>
		<span class="ewSearchField">
<?php
$wrkonchange = trim(" " . @$audiologia->id_especialista->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$audiologia->id_especialista->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_especialista" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_especialista" id="sv_x_id_especialista" value="<?php echo $audiologia->id_especialista->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->id_especialista->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($audiologia->id_especialista->getPlaceHolder()) ?>"<?php echo $audiologia->id_especialista->EditAttributes() ?>>
</span>
<input type="hidden" data-table="audiologia" data-field="x_id_especialista" data-value-separator="<?php echo $audiologia->id_especialista->DisplayValueSeparatorAttribute() ?>" name="x_id_especialista" id="x_id_especialista" value="<?php echo ew_HtmlEncode($audiologia->id_especialista->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
faudiologialistsrch.CreateAutoSuggest({"id":"x_id_especialista","forceSelect":false});
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($audiologia->especialidad->Visible) { // especialidad ?>
	<div id="xsc_especialidad" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $audiologia->especialidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_especialidad" id="z_especialidad" value="="></span>
		<span class="ewSearchField">
<?php
$wrkonchange = trim(" " . @$audiologia->especialidad->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$audiologia->especialidad->EditAttrs["onchange"] = "";
?>
<span id="as_x_especialidad" style="white-space: nowrap; z-index: 8970">
	<input type="text" name="sv_x_especialidad" id="sv_x_especialidad" value="<?php echo $audiologia->especialidad->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->especialidad->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($audiologia->especialidad->getPlaceHolder()) ?>"<?php echo $audiologia->especialidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="audiologia" data-field="x_especialidad" data-value-separator="<?php echo $audiologia->especialidad->DisplayValueSeparatorAttribute() ?>" name="x_especialidad" id="x_especialidad" value="<?php echo ew_HtmlEncode($audiologia->especialidad->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
faudiologialistsrch.CreateAutoSuggest({"id":"x_especialidad","forceSelect":false});
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($audiologia->fecha->Visible) { // fecha ?>
	<div id="xsc_fecha" class="ewCell form-group">
		<label for="x_fecha" class="ewSearchCaption ewLabel"><?php echo $audiologia->fecha->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_fecha" id="z_fecha" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="audiologia" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($audiologia->fecha->getPlaceHolder()) ?>" value="<?php echo $audiologia->fecha->EditValue ?>"<?php echo $audiologia->fecha->EditAttributes() ?>>
<?php if (!$audiologia->fecha->ReadOnly && !$audiologia->fecha->Disabled && !isset($audiologia->fecha->EditAttrs["readonly"]) && !isset($audiologia->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("faudiologialistsrch", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_fecha">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_fecha">
<input type="text" data-table="audiologia" data-field="x_fecha" name="y_fecha" id="y_fecha" placeholder="<?php echo ew_HtmlEncode($audiologia->fecha->getPlaceHolder()) ?>" value="<?php echo $audiologia->fecha->EditValue2 ?>"<?php echo $audiologia->fecha->EditAttributes() ?>>
<?php if (!$audiologia->fecha->ReadOnly && !$audiologia->fecha->Disabled && !isset($audiologia->fecha->EditAttrs["readonly"]) && !isset($audiologia->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("faudiologialistsrch", "y_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($audiologia->id_escolar->Visible) { // id_escolar ?>
	<div id="xsc_id_escolar" class="ewCell form-group">
		<label for="x_id_escolar" class="ewSearchCaption ewLabel"><?php echo $audiologia->id_escolar->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_escolar" id="z_id_escolar" value="="></span>
		<span class="ewSearchField">
<select data-table="audiologia" data-field="x_id_escolar" data-value-separator="<?php echo $audiologia->id_escolar->DisplayValueSeparatorAttribute() ?>" id="x_id_escolar" name="x_id_escolar"<?php echo $audiologia->id_escolar->EditAttributes() ?>>
<?php echo $audiologia->id_escolar->SelectOptionListHtml("x_id_escolar") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($audiologia->id_neonato->Visible) { // id_neonato ?>
	<div id="xsc_id_neonato" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $audiologia->id_neonato->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_neonato" id="z_id_neonato" value="="></span>
		<span class="ewSearchField">
<?php
$wrkonchange = trim(" " . @$audiologia->id_neonato->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$audiologia->id_neonato->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_neonato" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_id_neonato" id="sv_x_id_neonato" value="<?php echo $audiologia->id_neonato->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->id_neonato->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($audiologia->id_neonato->getPlaceHolder()) ?>"<?php echo $audiologia->id_neonato->EditAttributes() ?>>
</span>
<input type="hidden" data-table="audiologia" data-field="x_id_neonato" data-value-separator="<?php echo $audiologia->id_neonato->DisplayValueSeparatorAttribute() ?>" name="x_id_neonato" id="x_id_neonato" value="<?php echo ew_HtmlEncode($audiologia->id_neonato->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
faudiologialistsrch.CreateAutoSuggest({"id":"x_id_neonato","forceSelect":false});
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($audiologia->id_otros->Visible) { // id_otros ?>
	<div id="xsc_id_otros" class="ewCell form-group">
		<label for="x_id_otros" class="ewSearchCaption ewLabel"><?php echo $audiologia->id_otros->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_otros" id="z_id_otros" value="="></span>
		<span class="ewSearchField">
<select data-table="audiologia" data-field="x_id_otros" data-value-separator="<?php echo $audiologia->id_otros->DisplayValueSeparatorAttribute() ?>" id="x_id_otros" name="x_id_otros"<?php echo $audiologia->id_otros->EditAttributes() ?>>
<?php echo $audiologia->id_otros->SelectOptionListHtml("x_id_otros") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($audiologia->observaciones->Visible) { // observaciones ?>
	<div id="xsc_observaciones" class="ewCell form-group">
		<label for="x_observaciones" class="ewSearchCaption ewLabel"><?php echo $audiologia->observaciones->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_observaciones" id="z_observaciones" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="audiologia" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->observaciones->getPlaceHolder()) ?>" value="<?php echo $audiologia->observaciones->EditValue ?>"<?php echo $audiologia->observaciones->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
<?php if ($audiologia->id_atencion->Visible) { // id_atencion ?>
	<div id="xsc_id_atencion" class="ewCell form-group">
		<label for="x_id_atencion" class="ewSearchCaption ewLabel"><?php echo $audiologia->id_atencion->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_atencion" id="z_id_atencion" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="audiologia" data-field="x_id_atencion" name="x_id_atencion" id="x_id_atencion" size="30" placeholder="<?php echo ew_HtmlEncode($audiologia->id_atencion->getPlaceHolder()) ?>" value="<?php echo $audiologia->id_atencion->EditValue ?>"<?php echo $audiologia->id_atencion->EditAttributes() ?>>
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
<?php $audiologia_list->ShowPageHeader(); ?>
<?php
$audiologia_list->ShowMessage();
?>
<?php if ($audiologia_list->TotalRecs > 0 || $audiologia->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($audiologia_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> audiologia">
<div class="box-header ewGridUpperPanel">
<?php if ($audiologia->CurrentAction <> "gridadd" && $audiologia->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($audiologia_list->Pager)) $audiologia_list->Pager = new cPrevNextPager($audiologia_list->StartRec, $audiologia_list->DisplayRecs, $audiologia_list->TotalRecs, $audiologia_list->AutoHidePager) ?>
<?php if ($audiologia_list->Pager->RecordCount > 0 && $audiologia_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($audiologia_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $audiologia_list->PageUrl() ?>start=<?php echo $audiologia_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($audiologia_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $audiologia_list->PageUrl() ?>start=<?php echo $audiologia_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $audiologia_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($audiologia_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $audiologia_list->PageUrl() ?>start=<?php echo $audiologia_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($audiologia_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $audiologia_list->PageUrl() ?>start=<?php echo $audiologia_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $audiologia_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($audiologia_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $audiologia_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $audiologia_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $audiologia_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($audiologia_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="faudiologialist" id="faudiologialist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($audiologia_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $audiologia_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="audiologia">
<div id="gmp_audiologia" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($audiologia_list->TotalRecs > 0 || $audiologia->CurrentAction == "gridedit") { ?>
<table id="tbl_audiologialist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$audiologia_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$audiologia_list->RenderListOptions();

// Render list options (header, left)
$audiologia_list->ListOptions->Render("header", "left");
?>
<?php if ($audiologia->id_especialista->Visible) { // id_especialista ?>
	<?php if ($audiologia->SortUrl($audiologia->id_especialista) == "") { ?>
		<th data-name="id_especialista" class="<?php echo $audiologia->id_especialista->HeaderCellClass() ?>"><div id="elh_audiologia_id_especialista" class="audiologia_id_especialista"><div class="ewTableHeaderCaption"><?php echo $audiologia->id_especialista->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_especialista" class="<?php echo $audiologia->id_especialista->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $audiologia->SortUrl($audiologia->id_especialista) ?>',2);"><div id="elh_audiologia_id_especialista" class="audiologia_id_especialista">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $audiologia->id_especialista->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($audiologia->id_especialista->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($audiologia->id_especialista->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($audiologia->especialidad->Visible) { // especialidad ?>
	<?php if ($audiologia->SortUrl($audiologia->especialidad) == "") { ?>
		<th data-name="especialidad" class="<?php echo $audiologia->especialidad->HeaderCellClass() ?>"><div id="elh_audiologia_especialidad" class="audiologia_especialidad"><div class="ewTableHeaderCaption"><?php echo $audiologia->especialidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="especialidad" class="<?php echo $audiologia->especialidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $audiologia->SortUrl($audiologia->especialidad) ?>',2);"><div id="elh_audiologia_especialidad" class="audiologia_especialidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $audiologia->especialidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($audiologia->especialidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($audiologia->especialidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($audiologia->fecha->Visible) { // fecha ?>
	<?php if ($audiologia->SortUrl($audiologia->fecha) == "") { ?>
		<th data-name="fecha" class="<?php echo $audiologia->fecha->HeaderCellClass() ?>"><div id="elh_audiologia_fecha" class="audiologia_fecha"><div class="ewTableHeaderCaption"><?php echo $audiologia->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha" class="<?php echo $audiologia->fecha->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $audiologia->SortUrl($audiologia->fecha) ?>',2);"><div id="elh_audiologia_fecha" class="audiologia_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $audiologia->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($audiologia->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($audiologia->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($audiologia->id_escolar->Visible) { // id_escolar ?>
	<?php if ($audiologia->SortUrl($audiologia->id_escolar) == "") { ?>
		<th data-name="id_escolar" class="<?php echo $audiologia->id_escolar->HeaderCellClass() ?>"><div id="elh_audiologia_id_escolar" class="audiologia_id_escolar"><div class="ewTableHeaderCaption"><?php echo $audiologia->id_escolar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_escolar" class="<?php echo $audiologia->id_escolar->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $audiologia->SortUrl($audiologia->id_escolar) ?>',2);"><div id="elh_audiologia_id_escolar" class="audiologia_id_escolar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $audiologia->id_escolar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($audiologia->id_escolar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($audiologia->id_escolar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($audiologia->id_neonato->Visible) { // id_neonato ?>
	<?php if ($audiologia->SortUrl($audiologia->id_neonato) == "") { ?>
		<th data-name="id_neonato" class="<?php echo $audiologia->id_neonato->HeaderCellClass() ?>"><div id="elh_audiologia_id_neonato" class="audiologia_id_neonato"><div class="ewTableHeaderCaption"><?php echo $audiologia->id_neonato->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_neonato" class="<?php echo $audiologia->id_neonato->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $audiologia->SortUrl($audiologia->id_neonato) ?>',2);"><div id="elh_audiologia_id_neonato" class="audiologia_id_neonato">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $audiologia->id_neonato->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($audiologia->id_neonato->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($audiologia->id_neonato->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($audiologia->id_otros->Visible) { // id_otros ?>
	<?php if ($audiologia->SortUrl($audiologia->id_otros) == "") { ?>
		<th data-name="id_otros" class="<?php echo $audiologia->id_otros->HeaderCellClass() ?>"><div id="elh_audiologia_id_otros" class="audiologia_id_otros"><div class="ewTableHeaderCaption"><?php echo $audiologia->id_otros->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_otros" class="<?php echo $audiologia->id_otros->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $audiologia->SortUrl($audiologia->id_otros) ?>',2);"><div id="elh_audiologia_id_otros" class="audiologia_id_otros">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $audiologia->id_otros->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($audiologia->id_otros->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($audiologia->id_otros->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($audiologia->observaciones->Visible) { // observaciones ?>
	<?php if ($audiologia->SortUrl($audiologia->observaciones) == "") { ?>
		<th data-name="observaciones" class="<?php echo $audiologia->observaciones->HeaderCellClass() ?>"><div id="elh_audiologia_observaciones" class="audiologia_observaciones"><div class="ewTableHeaderCaption"><?php echo $audiologia->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones" class="<?php echo $audiologia->observaciones->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $audiologia->SortUrl($audiologia->observaciones) ?>',2);"><div id="elh_audiologia_observaciones" class="audiologia_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $audiologia->observaciones->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($audiologia->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($audiologia->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($audiologia->id_atencion->Visible) { // id_atencion ?>
	<?php if ($audiologia->SortUrl($audiologia->id_atencion) == "") { ?>
		<th data-name="id_atencion" class="<?php echo $audiologia->id_atencion->HeaderCellClass() ?>"><div id="elh_audiologia_id_atencion" class="audiologia_id_atencion"><div class="ewTableHeaderCaption"><?php echo $audiologia->id_atencion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_atencion" class="<?php echo $audiologia->id_atencion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $audiologia->SortUrl($audiologia->id_atencion) ?>',2);"><div id="elh_audiologia_id_atencion" class="audiologia_id_atencion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $audiologia->id_atencion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($audiologia->id_atencion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($audiologia->id_atencion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$audiologia_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($audiologia->ExportAll && $audiologia->Export <> "") {
	$audiologia_list->StopRec = $audiologia_list->TotalRecs;
} else {

	// Set the last record to display
	if ($audiologia_list->TotalRecs > $audiologia_list->StartRec + $audiologia_list->DisplayRecs - 1)
		$audiologia_list->StopRec = $audiologia_list->StartRec + $audiologia_list->DisplayRecs - 1;
	else
		$audiologia_list->StopRec = $audiologia_list->TotalRecs;
}
$audiologia_list->RecCnt = $audiologia_list->StartRec - 1;
if ($audiologia_list->Recordset && !$audiologia_list->Recordset->EOF) {
	$audiologia_list->Recordset->MoveFirst();
	$bSelectLimit = $audiologia_list->UseSelectLimit;
	if (!$bSelectLimit && $audiologia_list->StartRec > 1)
		$audiologia_list->Recordset->Move($audiologia_list->StartRec - 1);
} elseif (!$audiologia->AllowAddDeleteRow && $audiologia_list->StopRec == 0) {
	$audiologia_list->StopRec = $audiologia->GridAddRowCount;
}

// Initialize aggregate
$audiologia->RowType = EW_ROWTYPE_AGGREGATEINIT;
$audiologia->ResetAttrs();
$audiologia_list->RenderRow();
while ($audiologia_list->RecCnt < $audiologia_list->StopRec) {
	$audiologia_list->RecCnt++;
	if (intval($audiologia_list->RecCnt) >= intval($audiologia_list->StartRec)) {
		$audiologia_list->RowCnt++;

		// Set up key count
		$audiologia_list->KeyCount = $audiologia_list->RowIndex;

		// Init row class and style
		$audiologia->ResetAttrs();
		$audiologia->CssClass = "";
		if ($audiologia->CurrentAction == "gridadd") {
		} else {
			$audiologia_list->LoadRowValues($audiologia_list->Recordset); // Load row values
		}
		$audiologia->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$audiologia->RowAttrs = array_merge($audiologia->RowAttrs, array('data-rowindex'=>$audiologia_list->RowCnt, 'id'=>'r' . $audiologia_list->RowCnt . '_audiologia', 'data-rowtype'=>$audiologia->RowType));

		// Render row
		$audiologia_list->RenderRow();

		// Render list options
		$audiologia_list->RenderListOptions();
?>
	<tr<?php echo $audiologia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$audiologia_list->ListOptions->Render("body", "left", $audiologia_list->RowCnt);
?>
	<?php if ($audiologia->id_especialista->Visible) { // id_especialista ?>
		<td data-name="id_especialista"<?php echo $audiologia->id_especialista->CellAttributes() ?>>
<span id="el<?php echo $audiologia_list->RowCnt ?>_audiologia_id_especialista" class="audiologia_id_especialista">
<span<?php echo $audiologia->id_especialista->ViewAttributes() ?>>
<?php echo $audiologia->id_especialista->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($audiologia->especialidad->Visible) { // especialidad ?>
		<td data-name="especialidad"<?php echo $audiologia->especialidad->CellAttributes() ?>>
<span id="el<?php echo $audiologia_list->RowCnt ?>_audiologia_especialidad" class="audiologia_especialidad">
<span<?php echo $audiologia->especialidad->ViewAttributes() ?>>
<?php echo $audiologia->especialidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($audiologia->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $audiologia->fecha->CellAttributes() ?>>
<span id="el<?php echo $audiologia_list->RowCnt ?>_audiologia_fecha" class="audiologia_fecha">
<span<?php echo $audiologia->fecha->ViewAttributes() ?>>
<?php echo $audiologia->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($audiologia->id_escolar->Visible) { // id_escolar ?>
		<td data-name="id_escolar"<?php echo $audiologia->id_escolar->CellAttributes() ?>>
<span id="el<?php echo $audiologia_list->RowCnt ?>_audiologia_id_escolar" class="audiologia_id_escolar">
<span<?php echo $audiologia->id_escolar->ViewAttributes() ?>>
<?php echo $audiologia->id_escolar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($audiologia->id_neonato->Visible) { // id_neonato ?>
		<td data-name="id_neonato"<?php echo $audiologia->id_neonato->CellAttributes() ?>>
<span id="el<?php echo $audiologia_list->RowCnt ?>_audiologia_id_neonato" class="audiologia_id_neonato">
<span<?php echo $audiologia->id_neonato->ViewAttributes() ?>>
<?php echo $audiologia->id_neonato->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($audiologia->id_otros->Visible) { // id_otros ?>
		<td data-name="id_otros"<?php echo $audiologia->id_otros->CellAttributes() ?>>
<span id="el<?php echo $audiologia_list->RowCnt ?>_audiologia_id_otros" class="audiologia_id_otros">
<span<?php echo $audiologia->id_otros->ViewAttributes() ?>>
<?php echo $audiologia->id_otros->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($audiologia->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $audiologia->observaciones->CellAttributes() ?>>
<span id="el<?php echo $audiologia_list->RowCnt ?>_audiologia_observaciones" class="audiologia_observaciones">
<span<?php echo $audiologia->observaciones->ViewAttributes() ?>>
<?php echo $audiologia->observaciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($audiologia->id_atencion->Visible) { // id_atencion ?>
		<td data-name="id_atencion"<?php echo $audiologia->id_atencion->CellAttributes() ?>>
<span id="el<?php echo $audiologia_list->RowCnt ?>_audiologia_id_atencion" class="audiologia_id_atencion">
<span<?php echo $audiologia->id_atencion->ViewAttributes() ?>>
<?php echo $audiologia->id_atencion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$audiologia_list->ListOptions->Render("body", "right", $audiologia_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($audiologia->CurrentAction <> "gridadd")
		$audiologia_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($audiologia->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($audiologia_list->Recordset)
	$audiologia_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($audiologia->CurrentAction <> "gridadd" && $audiologia->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($audiologia_list->Pager)) $audiologia_list->Pager = new cPrevNextPager($audiologia_list->StartRec, $audiologia_list->DisplayRecs, $audiologia_list->TotalRecs, $audiologia_list->AutoHidePager) ?>
<?php if ($audiologia_list->Pager->RecordCount > 0 && $audiologia_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($audiologia_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $audiologia_list->PageUrl() ?>start=<?php echo $audiologia_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($audiologia_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $audiologia_list->PageUrl() ?>start=<?php echo $audiologia_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $audiologia_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($audiologia_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $audiologia_list->PageUrl() ?>start=<?php echo $audiologia_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($audiologia_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $audiologia_list->PageUrl() ?>start=<?php echo $audiologia_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $audiologia_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($audiologia_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $audiologia_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $audiologia_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $audiologia_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($audiologia_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($audiologia_list->TotalRecs == 0 && $audiologia->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($audiologia_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
faudiologialistsrch.FilterList = <?php echo $audiologia_list->GetFilterList() ?>;
faudiologialistsrch.Init();
faudiologialist.Init();
</script>
<?php
$audiologia_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$audiologia_list->Page_Terminate();
?>
