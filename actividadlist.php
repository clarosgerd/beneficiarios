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

$actividad_list = NULL; // Initialize page object first

class cactividad_list extends cactividad {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'actividad';

	// Page object name
	var $PageObjName = 'actividad_list';

	// Grid form hidden field names
	var $FormName = 'factividadlist';
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

		// Table object (actividad)
		if (!isset($GLOBALS["actividad"]) || get_class($GLOBALS["actividad"]) == "cactividad") {
			$GLOBALS["actividad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["actividad"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "actividadadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "actividaddelete.php";
		$this->MultiUpdateUrl = "actividadupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption factividadlistsrch";

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
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->id_sector->AdvancedSearch->ToJson(), ","); // Field id_sector
		$sFilterList = ew_Concat($sFilterList, $this->id_tipoactividad->AdvancedSearch->ToJson(), ","); // Field id_tipoactividad
		$sFilterList = ew_Concat($sFilterList, $this->organizador->AdvancedSearch->ToJson(), ","); // Field organizador
		$sFilterList = ew_Concat($sFilterList, $this->nombreactividad->AdvancedSearch->ToJson(), ","); // Field nombreactividad
		$sFilterList = ew_Concat($sFilterList, $this->nombrelocal->AdvancedSearch->ToJson(), ","); // Field nombrelocal
		$sFilterList = ew_Concat($sFilterList, $this->direccionlocal->AdvancedSearch->ToJson(), ","); // Field direccionlocal
		$sFilterList = ew_Concat($sFilterList, $this->fecha_inicio->AdvancedSearch->ToJson(), ","); // Field fecha_inicio
		$sFilterList = ew_Concat($sFilterList, $this->fecha_fin->AdvancedSearch->ToJson(), ","); // Field fecha_fin
		$sFilterList = ew_Concat($sFilterList, $this->horasprogramadas->AdvancedSearch->ToJson(), ","); // Field horasprogramadas
		$sFilterList = ew_Concat($sFilterList, $this->id_persona->AdvancedSearch->ToJson(), ","); // Field id_persona
		$sFilterList = ew_Concat($sFilterList, $this->contenido->AdvancedSearch->ToJson(), ","); // Field contenido
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJson(), ","); // Field observaciones
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "factividadlistsrch", $filters);

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

		// Field id_sector
		$this->id_sector->AdvancedSearch->SearchValue = @$filter["x_id_sector"];
		$this->id_sector->AdvancedSearch->SearchOperator = @$filter["z_id_sector"];
		$this->id_sector->AdvancedSearch->SearchCondition = @$filter["v_id_sector"];
		$this->id_sector->AdvancedSearch->SearchValue2 = @$filter["y_id_sector"];
		$this->id_sector->AdvancedSearch->SearchOperator2 = @$filter["w_id_sector"];
		$this->id_sector->AdvancedSearch->Save();

		// Field id_tipoactividad
		$this->id_tipoactividad->AdvancedSearch->SearchValue = @$filter["x_id_tipoactividad"];
		$this->id_tipoactividad->AdvancedSearch->SearchOperator = @$filter["z_id_tipoactividad"];
		$this->id_tipoactividad->AdvancedSearch->SearchCondition = @$filter["v_id_tipoactividad"];
		$this->id_tipoactividad->AdvancedSearch->SearchValue2 = @$filter["y_id_tipoactividad"];
		$this->id_tipoactividad->AdvancedSearch->SearchOperator2 = @$filter["w_id_tipoactividad"];
		$this->id_tipoactividad->AdvancedSearch->Save();

		// Field organizador
		$this->organizador->AdvancedSearch->SearchValue = @$filter["x_organizador"];
		$this->organizador->AdvancedSearch->SearchOperator = @$filter["z_organizador"];
		$this->organizador->AdvancedSearch->SearchCondition = @$filter["v_organizador"];
		$this->organizador->AdvancedSearch->SearchValue2 = @$filter["y_organizador"];
		$this->organizador->AdvancedSearch->SearchOperator2 = @$filter["w_organizador"];
		$this->organizador->AdvancedSearch->Save();

		// Field nombreactividad
		$this->nombreactividad->AdvancedSearch->SearchValue = @$filter["x_nombreactividad"];
		$this->nombreactividad->AdvancedSearch->SearchOperator = @$filter["z_nombreactividad"];
		$this->nombreactividad->AdvancedSearch->SearchCondition = @$filter["v_nombreactividad"];
		$this->nombreactividad->AdvancedSearch->SearchValue2 = @$filter["y_nombreactividad"];
		$this->nombreactividad->AdvancedSearch->SearchOperator2 = @$filter["w_nombreactividad"];
		$this->nombreactividad->AdvancedSearch->Save();

		// Field nombrelocal
		$this->nombrelocal->AdvancedSearch->SearchValue = @$filter["x_nombrelocal"];
		$this->nombrelocal->AdvancedSearch->SearchOperator = @$filter["z_nombrelocal"];
		$this->nombrelocal->AdvancedSearch->SearchCondition = @$filter["v_nombrelocal"];
		$this->nombrelocal->AdvancedSearch->SearchValue2 = @$filter["y_nombrelocal"];
		$this->nombrelocal->AdvancedSearch->SearchOperator2 = @$filter["w_nombrelocal"];
		$this->nombrelocal->AdvancedSearch->Save();

		// Field direccionlocal
		$this->direccionlocal->AdvancedSearch->SearchValue = @$filter["x_direccionlocal"];
		$this->direccionlocal->AdvancedSearch->SearchOperator = @$filter["z_direccionlocal"];
		$this->direccionlocal->AdvancedSearch->SearchCondition = @$filter["v_direccionlocal"];
		$this->direccionlocal->AdvancedSearch->SearchValue2 = @$filter["y_direccionlocal"];
		$this->direccionlocal->AdvancedSearch->SearchOperator2 = @$filter["w_direccionlocal"];
		$this->direccionlocal->AdvancedSearch->Save();

		// Field fecha_inicio
		$this->fecha_inicio->AdvancedSearch->SearchValue = @$filter["x_fecha_inicio"];
		$this->fecha_inicio->AdvancedSearch->SearchOperator = @$filter["z_fecha_inicio"];
		$this->fecha_inicio->AdvancedSearch->SearchCondition = @$filter["v_fecha_inicio"];
		$this->fecha_inicio->AdvancedSearch->SearchValue2 = @$filter["y_fecha_inicio"];
		$this->fecha_inicio->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_inicio"];
		$this->fecha_inicio->AdvancedSearch->Save();

		// Field fecha_fin
		$this->fecha_fin->AdvancedSearch->SearchValue = @$filter["x_fecha_fin"];
		$this->fecha_fin->AdvancedSearch->SearchOperator = @$filter["z_fecha_fin"];
		$this->fecha_fin->AdvancedSearch->SearchCondition = @$filter["v_fecha_fin"];
		$this->fecha_fin->AdvancedSearch->SearchValue2 = @$filter["y_fecha_fin"];
		$this->fecha_fin->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_fin"];
		$this->fecha_fin->AdvancedSearch->Save();

		// Field horasprogramadas
		$this->horasprogramadas->AdvancedSearch->SearchValue = @$filter["x_horasprogramadas"];
		$this->horasprogramadas->AdvancedSearch->SearchOperator = @$filter["z_horasprogramadas"];
		$this->horasprogramadas->AdvancedSearch->SearchCondition = @$filter["v_horasprogramadas"];
		$this->horasprogramadas->AdvancedSearch->SearchValue2 = @$filter["y_horasprogramadas"];
		$this->horasprogramadas->AdvancedSearch->SearchOperator2 = @$filter["w_horasprogramadas"];
		$this->horasprogramadas->AdvancedSearch->Save();

		// Field id_persona
		$this->id_persona->AdvancedSearch->SearchValue = @$filter["x_id_persona"];
		$this->id_persona->AdvancedSearch->SearchOperator = @$filter["z_id_persona"];
		$this->id_persona->AdvancedSearch->SearchCondition = @$filter["v_id_persona"];
		$this->id_persona->AdvancedSearch->SearchValue2 = @$filter["y_id_persona"];
		$this->id_persona->AdvancedSearch->SearchOperator2 = @$filter["w_id_persona"];
		$this->id_persona->AdvancedSearch->Save();

		// Field contenido
		$this->contenido->AdvancedSearch->SearchValue = @$filter["x_contenido"];
		$this->contenido->AdvancedSearch->SearchOperator = @$filter["z_contenido"];
		$this->contenido->AdvancedSearch->SearchCondition = @$filter["v_contenido"];
		$this->contenido->AdvancedSearch->SearchValue2 = @$filter["y_contenido"];
		$this->contenido->AdvancedSearch->SearchOperator2 = @$filter["w_contenido"];
		$this->contenido->AdvancedSearch->Save();

		// Field observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$filter["x_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator = @$filter["z_observaciones"];
		$this->observaciones->AdvancedSearch->SearchCondition = @$filter["v_observaciones"];
		$this->observaciones->AdvancedSearch->SearchValue2 = @$filter["y_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones"];
		$this->observaciones->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->id_sector, $Default, FALSE); // id_sector
		$this->BuildSearchSql($sWhere, $this->id_tipoactividad, $Default, FALSE); // id_tipoactividad
		$this->BuildSearchSql($sWhere, $this->organizador, $Default, FALSE); // organizador
		$this->BuildSearchSql($sWhere, $this->nombreactividad, $Default, FALSE); // nombreactividad
		$this->BuildSearchSql($sWhere, $this->nombrelocal, $Default, FALSE); // nombrelocal
		$this->BuildSearchSql($sWhere, $this->direccionlocal, $Default, FALSE); // direccionlocal
		$this->BuildSearchSql($sWhere, $this->fecha_inicio, $Default, FALSE); // fecha_inicio
		$this->BuildSearchSql($sWhere, $this->fecha_fin, $Default, FALSE); // fecha_fin
		$this->BuildSearchSql($sWhere, $this->horasprogramadas, $Default, FALSE); // horasprogramadas
		$this->BuildSearchSql($sWhere, $this->id_persona, $Default, FALSE); // id_persona
		$this->BuildSearchSql($sWhere, $this->contenido, $Default, FALSE); // contenido
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->id_sector->AdvancedSearch->Save(); // id_sector
			$this->id_tipoactividad->AdvancedSearch->Save(); // id_tipoactividad
			$this->organizador->AdvancedSearch->Save(); // organizador
			$this->nombreactividad->AdvancedSearch->Save(); // nombreactividad
			$this->nombrelocal->AdvancedSearch->Save(); // nombrelocal
			$this->direccionlocal->AdvancedSearch->Save(); // direccionlocal
			$this->fecha_inicio->AdvancedSearch->Save(); // fecha_inicio
			$this->fecha_fin->AdvancedSearch->Save(); // fecha_fin
			$this->horasprogramadas->AdvancedSearch->Save(); // horasprogramadas
			$this->id_persona->AdvancedSearch->Save(); // id_persona
			$this->contenido->AdvancedSearch->Save(); // contenido
			$this->observaciones->AdvancedSearch->Save(); // observaciones
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
		if ($this->id_sector->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_tipoactividad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->organizador->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombreactividad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombrelocal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccionlocal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_inicio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_fin->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->horasprogramadas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_persona->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contenido->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
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
		$this->id_sector->AdvancedSearch->UnsetSession();
		$this->id_tipoactividad->AdvancedSearch->UnsetSession();
		$this->organizador->AdvancedSearch->UnsetSession();
		$this->nombreactividad->AdvancedSearch->UnsetSession();
		$this->nombrelocal->AdvancedSearch->UnsetSession();
		$this->direccionlocal->AdvancedSearch->UnsetSession();
		$this->fecha_inicio->AdvancedSearch->UnsetSession();
		$this->fecha_fin->AdvancedSearch->UnsetSession();
		$this->horasprogramadas->AdvancedSearch->UnsetSession();
		$this->id_persona->AdvancedSearch->UnsetSession();
		$this->contenido->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->id_sector->AdvancedSearch->Load();
		$this->id_tipoactividad->AdvancedSearch->Load();
		$this->organizador->AdvancedSearch->Load();
		$this->nombreactividad->AdvancedSearch->Load();
		$this->nombrelocal->AdvancedSearch->Load();
		$this->direccionlocal->AdvancedSearch->Load();
		$this->fecha_inicio->AdvancedSearch->Load();
		$this->fecha_fin->AdvancedSearch->Load();
		$this->horasprogramadas->AdvancedSearch->Load();
		$this->id_persona->AdvancedSearch->Load();
		$this->contenido->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
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
			$this->UpdateSort($this->id_sector, $bCtrl); // id_sector
			$this->UpdateSort($this->id_tipoactividad, $bCtrl); // id_tipoactividad
			$this->UpdateSort($this->organizador, $bCtrl); // organizador
			$this->UpdateSort($this->nombreactividad, $bCtrl); // nombreactividad
			$this->UpdateSort($this->nombrelocal, $bCtrl); // nombrelocal
			$this->UpdateSort($this->direccionlocal, $bCtrl); // direccionlocal
			$this->UpdateSort($this->fecha_inicio, $bCtrl); // fecha_inicio
			$this->UpdateSort($this->fecha_fin, $bCtrl); // fecha_fin
			$this->UpdateSort($this->horasprogramadas, $bCtrl); // horasprogramadas
			$this->UpdateSort($this->id_persona, $bCtrl); // id_persona
			$this->UpdateSort($this->contenido, $bCtrl); // contenido
			$this->UpdateSort($this->observaciones, $bCtrl); // observaciones
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
				$this->id_sector->setSort("");
				$this->id_tipoactividad->setSort("");
				$this->organizador->setSort("");
				$this->nombreactividad->setSort("");
				$this->nombrelocal->setSort("");
				$this->direccionlocal->setSort("");
				$this->fecha_inicio->setSort("");
				$this->fecha_fin->setSort("");
				$this->horasprogramadas->setSort("");
				$this->id_persona->setSort("");
				$this->contenido->setSort("");
				$this->observaciones->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"factividadlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"factividadlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.factividadlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"factividadlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

		// id_sector
		$this->id_sector->AdvancedSearch->SearchValue = @$_GET["x_id_sector"];
		if ($this->id_sector->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_sector->AdvancedSearch->SearchOperator = @$_GET["z_id_sector"];

		// id_tipoactividad
		$this->id_tipoactividad->AdvancedSearch->SearchValue = @$_GET["x_id_tipoactividad"];
		if ($this->id_tipoactividad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_tipoactividad->AdvancedSearch->SearchOperator = @$_GET["z_id_tipoactividad"];

		// organizador
		$this->organizador->AdvancedSearch->SearchValue = @$_GET["x_organizador"];
		if ($this->organizador->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->organizador->AdvancedSearch->SearchOperator = @$_GET["z_organizador"];

		// nombreactividad
		$this->nombreactividad->AdvancedSearch->SearchValue = @$_GET["x_nombreactividad"];
		if ($this->nombreactividad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombreactividad->AdvancedSearch->SearchOperator = @$_GET["z_nombreactividad"];

		// nombrelocal
		$this->nombrelocal->AdvancedSearch->SearchValue = @$_GET["x_nombrelocal"];
		if ($this->nombrelocal->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombrelocal->AdvancedSearch->SearchOperator = @$_GET["z_nombrelocal"];

		// direccionlocal
		$this->direccionlocal->AdvancedSearch->SearchValue = @$_GET["x_direccionlocal"];
		if ($this->direccionlocal->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->direccionlocal->AdvancedSearch->SearchOperator = @$_GET["z_direccionlocal"];

		// fecha_inicio
		$this->fecha_inicio->AdvancedSearch->SearchValue = @$_GET["x_fecha_inicio"];
		if ($this->fecha_inicio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_inicio->AdvancedSearch->SearchOperator = @$_GET["z_fecha_inicio"];

		// fecha_fin
		$this->fecha_fin->AdvancedSearch->SearchValue = @$_GET["x_fecha_fin"];
		if ($this->fecha_fin->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_fin->AdvancedSearch->SearchOperator = @$_GET["z_fecha_fin"];

		// horasprogramadas
		$this->horasprogramadas->AdvancedSearch->SearchValue = @$_GET["x_horasprogramadas"];
		if ($this->horasprogramadas->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->horasprogramadas->AdvancedSearch->SearchOperator = @$_GET["z_horasprogramadas"];

		// id_persona
		$this->id_persona->AdvancedSearch->SearchValue = @$_GET["x_id_persona"];
		if ($this->id_persona->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_persona->AdvancedSearch->SearchOperator = @$_GET["z_id_persona"];

		// contenido
		$this->contenido->AdvancedSearch->SearchValue = @$_GET["x_contenido"];
		if ($this->contenido->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->contenido->AdvancedSearch->SearchOperator = @$_GET["z_contenido"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$_GET["x_observaciones"];
		if ($this->observaciones->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];
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
		$this->id_sector->LookupFilters = array();
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
		$sSqlWrk = "SELECT `id`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `centros`";
		$sWhereWrk = "";
		$this->organizador->LookupFilters = array();
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

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// id_sector
			$this->id_sector->EditAttrs["class"] = "form-control";
			$this->id_sector->EditCustomAttributes = "";

			// id_tipoactividad
			$this->id_tipoactividad->EditAttrs["class"] = "form-control";
			$this->id_tipoactividad->EditCustomAttributes = "";

			// organizador
			$this->organizador->EditAttrs["class"] = "form-control";
			$this->organizador->EditCustomAttributes = "";
			if (trim(strval($this->organizador->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->organizador->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `centros`";
			$sWhereWrk = "";
			$this->organizador->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->organizador, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->organizador->EditValue = $arwrk;

			// nombreactividad
			$this->nombreactividad->EditAttrs["class"] = "form-control";
			$this->nombreactividad->EditCustomAttributes = "";
			$this->nombreactividad->EditValue = ew_HtmlEncode($this->nombreactividad->AdvancedSearch->SearchValue);
			$this->nombreactividad->PlaceHolder = ew_RemoveHtml($this->nombreactividad->FldCaption());

			// nombrelocal
			$this->nombrelocal->EditAttrs["class"] = "form-control";
			$this->nombrelocal->EditCustomAttributes = "";
			$this->nombrelocal->EditValue = ew_HtmlEncode($this->nombrelocal->AdvancedSearch->SearchValue);
			$this->nombrelocal->PlaceHolder = ew_RemoveHtml($this->nombrelocal->FldCaption());

			// direccionlocal
			$this->direccionlocal->EditAttrs["class"] = "form-control";
			$this->direccionlocal->EditCustomAttributes = "";
			$this->direccionlocal->EditValue = ew_HtmlEncode($this->direccionlocal->AdvancedSearch->SearchValue);
			$this->direccionlocal->PlaceHolder = ew_RemoveHtml($this->direccionlocal->FldCaption());

			// fecha_inicio
			$this->fecha_inicio->EditAttrs["class"] = "form-control";
			$this->fecha_inicio->EditCustomAttributes = "";
			$this->fecha_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_inicio->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha_inicio->PlaceHolder = ew_RemoveHtml($this->fecha_inicio->FldCaption());

			// fecha_fin
			$this->fecha_fin->EditAttrs["class"] = "form-control";
			$this->fecha_fin->EditCustomAttributes = "";
			$this->fecha_fin->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_fin->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha_fin->PlaceHolder = ew_RemoveHtml($this->fecha_fin->FldCaption());

			// horasprogramadas
			$this->horasprogramadas->EditAttrs["class"] = "form-control";
			$this->horasprogramadas->EditCustomAttributes = "";
			$this->horasprogramadas->EditValue = ew_HtmlEncode($this->horasprogramadas->AdvancedSearch->SearchValue);
			$this->horasprogramadas->PlaceHolder = ew_RemoveHtml($this->horasprogramadas->FldCaption());

			// id_persona
			$this->id_persona->EditAttrs["class"] = "form-control";
			$this->id_persona->EditCustomAttributes = "";
			$this->id_persona->EditValue = ew_HtmlEncode($this->id_persona->AdvancedSearch->SearchValue);
			$this->id_persona->PlaceHolder = ew_RemoveHtml($this->id_persona->FldCaption());

			// contenido
			$this->contenido->EditAttrs["class"] = "form-control";
			$this->contenido->EditCustomAttributes = "";
			$this->contenido->EditValue = ew_HtmlEncode($this->contenido->AdvancedSearch->SearchValue);
			$this->contenido->PlaceHolder = ew_RemoveHtml($this->contenido->FldCaption());

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->AdvancedSearch->SearchValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());
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
		$this->id_sector->AdvancedSearch->Load();
		$this->id_tipoactividad->AdvancedSearch->Load();
		$this->organizador->AdvancedSearch->Load();
		$this->nombreactividad->AdvancedSearch->Load();
		$this->nombrelocal->AdvancedSearch->Load();
		$this->direccionlocal->AdvancedSearch->Load();
		$this->fecha_inicio->AdvancedSearch->Load();
		$this->fecha_fin->AdvancedSearch->Load();
		$this->horasprogramadas->AdvancedSearch->Load();
		$this->id_persona->AdvancedSearch->Load();
		$this->contenido->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
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
		case "x_organizador":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombreinstitucion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `centros`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->organizador, $sWhereWrk); // Call Lookup Selecting
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
		case "x_id_persona":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld` FROM `persona`";
				$sWhereWrk = "`nombre` LIKE '{query_value}%' OR CONCAT(COALESCE(`nombre`, ''),'" . ew_ValueSeparator(1, $this->id_persona) . "',COALESCE(`apellidopaterno`,''),'" . ew_ValueSeparator(2, $this->id_persona) . "',COALESCE(`apellidomaterno`,'')) LIKE '{query_value}%'";
				$fld->LookupFilters = array("dx1" => '`nombre`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidomaterno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($actividad_list)) $actividad_list = new cactividad_list();

// Page init
$actividad_list->Page_Init();

// Page main
$actividad_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$actividad_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = factividadlist = new ew_Form("factividadlist", "list");
factividadlist.FormKeyCountName = '<?php echo $actividad_list->FormKeyCountName ?>';

// Form_CustomValidate event
factividadlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factividadlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factividadlist.Lists["x_id_sector"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sector"};
factividadlist.Lists["x_id_sector"].Data = "<?php echo $actividad_list->id_sector->LookupFilterQuery(FALSE, "list") ?>";
factividadlist.Lists["x_id_tipoactividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipoactividad"};
factividadlist.Lists["x_id_tipoactividad"].Data = "<?php echo $actividad_list->id_tipoactividad->LookupFilterQuery(FALSE, "list") ?>";
factividadlist.Lists["x_organizador"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreinstitucion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"centros"};
factividadlist.Lists["x_organizador"].Data = "<?php echo $actividad_list->organizador->LookupFilterQuery(FALSE, "list") ?>";
factividadlist.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
factividadlist.Lists["x_id_persona"].Data = "<?php echo $actividad_list->id_persona->LookupFilterQuery(FALSE, "list") ?>";
factividadlist.AutoSuggests["x_id_persona"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $actividad_list->id_persona->LookupFilterQuery(TRUE, "list"))) ?>;

// Form object for search
var CurrentSearchForm = factividadlistsrch = new ew_Form("factividadlistsrch");

// Validate function for search
factividadlistsrch.Validate = function(fobj) {
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
factividadlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factividadlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factividadlistsrch.Lists["x_organizador"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreinstitucion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"centros"};
factividadlistsrch.Lists["x_organizador"].Data = "<?php echo $actividad_list->organizador->LookupFilterQuery(FALSE, "extbs") ?>";
factividadlistsrch.Lists["x_id_persona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"persona"};
factividadlistsrch.Lists["x_id_persona"].Data = "<?php echo $actividad_list->id_persona->LookupFilterQuery(FALSE, "extbs") ?>";
factividadlistsrch.AutoSuggests["x_id_persona"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $actividad_list->id_persona->LookupFilterQuery(TRUE, "extbs"))) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($actividad_list->TotalRecs > 0 && $actividad_list->ExportOptions->Visible()) { ?>
<?php $actividad_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($actividad_list->SearchOptions->Visible()) { ?>
<?php $actividad_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($actividad_list->FilterOptions->Visible()) { ?>
<?php $actividad_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $actividad_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($actividad_list->TotalRecs <= 0)
			$actividad_list->TotalRecs = $actividad->ListRecordCount();
	} else {
		if (!$actividad_list->Recordset && ($actividad_list->Recordset = $actividad_list->LoadRecordset()))
			$actividad_list->TotalRecs = $actividad_list->Recordset->RecordCount();
	}
	$actividad_list->StartRec = 1;
	if ($actividad_list->DisplayRecs <= 0 || ($actividad->Export <> "" && $actividad->ExportAll)) // Display all records
		$actividad_list->DisplayRecs = $actividad_list->TotalRecs;
	if (!($actividad->Export <> "" && $actividad->ExportAll))
		$actividad_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$actividad_list->Recordset = $actividad_list->LoadRecordset($actividad_list->StartRec-1, $actividad_list->DisplayRecs);

	// Set no record found message
	if ($actividad->CurrentAction == "" && $actividad_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$actividad_list->setWarningMessage(ew_DeniedMsg());
		if ($actividad_list->SearchWhere == "0=101")
			$actividad_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$actividad_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$actividad_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($actividad->Export == "" && $actividad->CurrentAction == "") { ?>
<form name="factividadlistsrch" id="factividadlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($actividad_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="factividadlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="actividad">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$actividad_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$actividad->RowType = EW_ROWTYPE_SEARCH;

// Render row
$actividad->ResetAttrs();
$actividad_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($actividad->organizador->Visible) { // organizador ?>
	<div id="xsc_organizador" class="ewCell form-group">
		<label for="x_organizador" class="ewSearchCaption ewLabel"><?php echo $actividad->organizador->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_organizador" id="z_organizador" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="actividad" data-field="x_organizador" data-value-separator="<?php echo $actividad->organizador->DisplayValueSeparatorAttribute() ?>" id="x_organizador" name="x_organizador"<?php echo $actividad->organizador->EditAttributes() ?>>
<?php echo $actividad->organizador->SelectOptionListHtml("x_organizador") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($actividad->nombreactividad->Visible) { // nombreactividad ?>
	<div id="xsc_nombreactividad" class="ewCell form-group">
		<label for="x_nombreactividad" class="ewSearchCaption ewLabel"><?php echo $actividad->nombreactividad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombreactividad" id="z_nombreactividad" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="actividad" data-field="x_nombreactividad" name="x_nombreactividad" id="x_nombreactividad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->nombreactividad->getPlaceHolder()) ?>" value="<?php echo $actividad->nombreactividad->EditValue ?>"<?php echo $actividad->nombreactividad->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($actividad->nombrelocal->Visible) { // nombrelocal ?>
	<div id="xsc_nombrelocal" class="ewCell form-group">
		<label for="x_nombrelocal" class="ewSearchCaption ewLabel"><?php echo $actividad->nombrelocal->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombrelocal" id="z_nombrelocal" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="actividad" data-field="x_nombrelocal" name="x_nombrelocal" id="x_nombrelocal" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->nombrelocal->getPlaceHolder()) ?>" value="<?php echo $actividad->nombrelocal->EditValue ?>"<?php echo $actividad->nombrelocal->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($actividad->direccionlocal->Visible) { // direccionlocal ?>
	<div id="xsc_direccionlocal" class="ewCell form-group">
		<label for="x_direccionlocal" class="ewSearchCaption ewLabel"><?php echo $actividad->direccionlocal->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_direccionlocal" id="z_direccionlocal" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="actividad" data-field="x_direccionlocal" name="x_direccionlocal" id="x_direccionlocal" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->direccionlocal->getPlaceHolder()) ?>" value="<?php echo $actividad->direccionlocal->EditValue ?>"<?php echo $actividad->direccionlocal->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($actividad->horasprogramadas->Visible) { // horasprogramadas ?>
	<div id="xsc_horasprogramadas" class="ewCell form-group">
		<label for="x_horasprogramadas" class="ewSearchCaption ewLabel"><?php echo $actividad->horasprogramadas->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_horasprogramadas" id="z_horasprogramadas" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="actividad" data-field="x_horasprogramadas" name="x_horasprogramadas" id="x_horasprogramadas" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->horasprogramadas->getPlaceHolder()) ?>" value="<?php echo $actividad->horasprogramadas->EditValue ?>"<?php echo $actividad->horasprogramadas->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($actividad->id_persona->Visible) { // id_persona ?>
	<div id="xsc_id_persona" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $actividad->id_persona->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_id_persona" id="z_id_persona" value="LIKE"></span>
		<span class="ewSearchField">
<?php
$wrkonchange = trim(" " . @$actividad->id_persona->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$actividad->id_persona->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_persona" style="white-space: nowrap; z-index: 8890">
	<input type="text" name="sv_x_id_persona" id="sv_x_id_persona" value="<?php echo $actividad->id_persona->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->id_persona->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($actividad->id_persona->getPlaceHolder()) ?>"<?php echo $actividad->id_persona->EditAttributes() ?>>
</span>
<input type="hidden" data-table="actividad" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $actividad->id_persona->DisplayValueSeparatorAttribute() ?>" name="x_id_persona" id="x_id_persona" value="<?php echo ew_HtmlEncode($actividad->id_persona->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
factividadlistsrch.CreateAutoSuggest({"id":"x_id_persona","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($actividad->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_persona',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($actividad->id_persona->ReadOnly || $actividad->id_persona->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
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
<?php $actividad_list->ShowPageHeader(); ?>
<?php
$actividad_list->ShowMessage();
?>
<?php if ($actividad_list->TotalRecs > 0 || $actividad->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($actividad_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> actividad">
<div class="box-header ewGridUpperPanel">
<?php if ($actividad->CurrentAction <> "gridadd" && $actividad->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($actividad_list->Pager)) $actividad_list->Pager = new cPrevNextPager($actividad_list->StartRec, $actividad_list->DisplayRecs, $actividad_list->TotalRecs, $actividad_list->AutoHidePager) ?>
<?php if ($actividad_list->Pager->RecordCount > 0 && $actividad_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($actividad_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($actividad_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $actividad_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($actividad_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($actividad_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $actividad_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($actividad_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $actividad_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $actividad_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $actividad_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($actividad_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="factividadlist" id="factividadlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($actividad_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $actividad_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="actividad">
<div id="gmp_actividad" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($actividad_list->TotalRecs > 0 || $actividad->CurrentAction == "gridedit") { ?>
<table id="tbl_actividadlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$actividad_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$actividad_list->RenderListOptions();

// Render list options (header, left)
$actividad_list->ListOptions->Render("header", "left");
?>
<?php if ($actividad->id->Visible) { // id ?>
	<?php if ($actividad->SortUrl($actividad->id) == "") { ?>
		<th data-name="id" class="<?php echo $actividad->id->HeaderCellClass() ?>"><div id="elh_actividad_id" class="actividad_id"><div class="ewTableHeaderCaption"><?php echo $actividad->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $actividad->id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->id) ?>',2);"><div id="elh_actividad_id" class="actividad_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->id_sector->Visible) { // id_sector ?>
	<?php if ($actividad->SortUrl($actividad->id_sector) == "") { ?>
		<th data-name="id_sector" class="<?php echo $actividad->id_sector->HeaderCellClass() ?>"><div id="elh_actividad_id_sector" class="actividad_id_sector"><div class="ewTableHeaderCaption"><?php echo $actividad->id_sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_sector" class="<?php echo $actividad->id_sector->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->id_sector) ?>',2);"><div id="elh_actividad_id_sector" class="actividad_id_sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->id_sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->id_sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->id_sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->id_tipoactividad->Visible) { // id_tipoactividad ?>
	<?php if ($actividad->SortUrl($actividad->id_tipoactividad) == "") { ?>
		<th data-name="id_tipoactividad" class="<?php echo $actividad->id_tipoactividad->HeaderCellClass() ?>"><div id="elh_actividad_id_tipoactividad" class="actividad_id_tipoactividad"><div class="ewTableHeaderCaption"><?php echo $actividad->id_tipoactividad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipoactividad" class="<?php echo $actividad->id_tipoactividad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->id_tipoactividad) ?>',2);"><div id="elh_actividad_id_tipoactividad" class="actividad_id_tipoactividad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->id_tipoactividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->id_tipoactividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->id_tipoactividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->organizador->Visible) { // organizador ?>
	<?php if ($actividad->SortUrl($actividad->organizador) == "") { ?>
		<th data-name="organizador" class="<?php echo $actividad->organizador->HeaderCellClass() ?>"><div id="elh_actividad_organizador" class="actividad_organizador"><div class="ewTableHeaderCaption"><?php echo $actividad->organizador->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="organizador" class="<?php echo $actividad->organizador->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->organizador) ?>',2);"><div id="elh_actividad_organizador" class="actividad_organizador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->organizador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->organizador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->organizador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->nombreactividad->Visible) { // nombreactividad ?>
	<?php if ($actividad->SortUrl($actividad->nombreactividad) == "") { ?>
		<th data-name="nombreactividad" class="<?php echo $actividad->nombreactividad->HeaderCellClass() ?>"><div id="elh_actividad_nombreactividad" class="actividad_nombreactividad"><div class="ewTableHeaderCaption"><?php echo $actividad->nombreactividad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombreactividad" class="<?php echo $actividad->nombreactividad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->nombreactividad) ?>',2);"><div id="elh_actividad_nombreactividad" class="actividad_nombreactividad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->nombreactividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->nombreactividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->nombreactividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->nombrelocal->Visible) { // nombrelocal ?>
	<?php if ($actividad->SortUrl($actividad->nombrelocal) == "") { ?>
		<th data-name="nombrelocal" class="<?php echo $actividad->nombrelocal->HeaderCellClass() ?>"><div id="elh_actividad_nombrelocal" class="actividad_nombrelocal"><div class="ewTableHeaderCaption"><?php echo $actividad->nombrelocal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombrelocal" class="<?php echo $actividad->nombrelocal->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->nombrelocal) ?>',2);"><div id="elh_actividad_nombrelocal" class="actividad_nombrelocal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->nombrelocal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->nombrelocal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->nombrelocal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->direccionlocal->Visible) { // direccionlocal ?>
	<?php if ($actividad->SortUrl($actividad->direccionlocal) == "") { ?>
		<th data-name="direccionlocal" class="<?php echo $actividad->direccionlocal->HeaderCellClass() ?>"><div id="elh_actividad_direccionlocal" class="actividad_direccionlocal"><div class="ewTableHeaderCaption"><?php echo $actividad->direccionlocal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccionlocal" class="<?php echo $actividad->direccionlocal->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->direccionlocal) ?>',2);"><div id="elh_actividad_direccionlocal" class="actividad_direccionlocal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->direccionlocal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->direccionlocal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->direccionlocal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->fecha_inicio->Visible) { // fecha_inicio ?>
	<?php if ($actividad->SortUrl($actividad->fecha_inicio) == "") { ?>
		<th data-name="fecha_inicio" class="<?php echo $actividad->fecha_inicio->HeaderCellClass() ?>"><div id="elh_actividad_fecha_inicio" class="actividad_fecha_inicio"><div class="ewTableHeaderCaption"><?php echo $actividad->fecha_inicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_inicio" class="<?php echo $actividad->fecha_inicio->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->fecha_inicio) ?>',2);"><div id="elh_actividad_fecha_inicio" class="actividad_fecha_inicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->fecha_inicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->fecha_inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->fecha_inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->fecha_fin->Visible) { // fecha_fin ?>
	<?php if ($actividad->SortUrl($actividad->fecha_fin) == "") { ?>
		<th data-name="fecha_fin" class="<?php echo $actividad->fecha_fin->HeaderCellClass() ?>"><div id="elh_actividad_fecha_fin" class="actividad_fecha_fin"><div class="ewTableHeaderCaption"><?php echo $actividad->fecha_fin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_fin" class="<?php echo $actividad->fecha_fin->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->fecha_fin) ?>',2);"><div id="elh_actividad_fecha_fin" class="actividad_fecha_fin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->fecha_fin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->fecha_fin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->fecha_fin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->horasprogramadas->Visible) { // horasprogramadas ?>
	<?php if ($actividad->SortUrl($actividad->horasprogramadas) == "") { ?>
		<th data-name="horasprogramadas" class="<?php echo $actividad->horasprogramadas->HeaderCellClass() ?>"><div id="elh_actividad_horasprogramadas" class="actividad_horasprogramadas"><div class="ewTableHeaderCaption"><?php echo $actividad->horasprogramadas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="horasprogramadas" class="<?php echo $actividad->horasprogramadas->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->horasprogramadas) ?>',2);"><div id="elh_actividad_horasprogramadas" class="actividad_horasprogramadas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->horasprogramadas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->horasprogramadas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->horasprogramadas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->id_persona->Visible) { // id_persona ?>
	<?php if ($actividad->SortUrl($actividad->id_persona) == "") { ?>
		<th data-name="id_persona" class="<?php echo $actividad->id_persona->HeaderCellClass() ?>"><div id="elh_actividad_id_persona" class="actividad_id_persona"><div class="ewTableHeaderCaption"><?php echo $actividad->id_persona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_persona" class="<?php echo $actividad->id_persona->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->id_persona) ?>',2);"><div id="elh_actividad_id_persona" class="actividad_id_persona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->id_persona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->id_persona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->id_persona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->contenido->Visible) { // contenido ?>
	<?php if ($actividad->SortUrl($actividad->contenido) == "") { ?>
		<th data-name="contenido" class="<?php echo $actividad->contenido->HeaderCellClass() ?>"><div id="elh_actividad_contenido" class="actividad_contenido"><div class="ewTableHeaderCaption"><?php echo $actividad->contenido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contenido" class="<?php echo $actividad->contenido->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->contenido) ?>',2);"><div id="elh_actividad_contenido" class="actividad_contenido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->contenido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->contenido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->contenido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($actividad->observaciones->Visible) { // observaciones ?>
	<?php if ($actividad->SortUrl($actividad->observaciones) == "") { ?>
		<th data-name="observaciones" class="<?php echo $actividad->observaciones->HeaderCellClass() ?>"><div id="elh_actividad_observaciones" class="actividad_observaciones"><div class="ewTableHeaderCaption"><?php echo $actividad->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones" class="<?php echo $actividad->observaciones->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $actividad->SortUrl($actividad->observaciones) ?>',2);"><div id="elh_actividad_observaciones" class="actividad_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->observaciones->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$actividad_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($actividad->ExportAll && $actividad->Export <> "") {
	$actividad_list->StopRec = $actividad_list->TotalRecs;
} else {

	// Set the last record to display
	if ($actividad_list->TotalRecs > $actividad_list->StartRec + $actividad_list->DisplayRecs - 1)
		$actividad_list->StopRec = $actividad_list->StartRec + $actividad_list->DisplayRecs - 1;
	else
		$actividad_list->StopRec = $actividad_list->TotalRecs;
}
$actividad_list->RecCnt = $actividad_list->StartRec - 1;
if ($actividad_list->Recordset && !$actividad_list->Recordset->EOF) {
	$actividad_list->Recordset->MoveFirst();
	$bSelectLimit = $actividad_list->UseSelectLimit;
	if (!$bSelectLimit && $actividad_list->StartRec > 1)
		$actividad_list->Recordset->Move($actividad_list->StartRec - 1);
} elseif (!$actividad->AllowAddDeleteRow && $actividad_list->StopRec == 0) {
	$actividad_list->StopRec = $actividad->GridAddRowCount;
}

// Initialize aggregate
$actividad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$actividad->ResetAttrs();
$actividad_list->RenderRow();
while ($actividad_list->RecCnt < $actividad_list->StopRec) {
	$actividad_list->RecCnt++;
	if (intval($actividad_list->RecCnt) >= intval($actividad_list->StartRec)) {
		$actividad_list->RowCnt++;

		// Set up key count
		$actividad_list->KeyCount = $actividad_list->RowIndex;

		// Init row class and style
		$actividad->ResetAttrs();
		$actividad->CssClass = "";
		if ($actividad->CurrentAction == "gridadd") {
		} else {
			$actividad_list->LoadRowValues($actividad_list->Recordset); // Load row values
		}
		$actividad->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$actividad->RowAttrs = array_merge($actividad->RowAttrs, array('data-rowindex'=>$actividad_list->RowCnt, 'id'=>'r' . $actividad_list->RowCnt . '_actividad', 'data-rowtype'=>$actividad->RowType));

		// Render row
		$actividad_list->RenderRow();

		// Render list options
		$actividad_list->RenderListOptions();
?>
	<tr<?php echo $actividad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$actividad_list->ListOptions->Render("body", "left", $actividad_list->RowCnt);
?>
	<?php if ($actividad->id->Visible) { // id ?>
		<td data-name="id"<?php echo $actividad->id->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_id" class="actividad_id">
<span<?php echo $actividad->id->ViewAttributes() ?>>
<?php echo $actividad->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->id_sector->Visible) { // id_sector ?>
		<td data-name="id_sector"<?php echo $actividad->id_sector->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_id_sector" class="actividad_id_sector">
<span<?php echo $actividad->id_sector->ViewAttributes() ?>>
<?php echo $actividad->id_sector->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->id_tipoactividad->Visible) { // id_tipoactividad ?>
		<td data-name="id_tipoactividad"<?php echo $actividad->id_tipoactividad->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_id_tipoactividad" class="actividad_id_tipoactividad">
<span<?php echo $actividad->id_tipoactividad->ViewAttributes() ?>>
<?php echo $actividad->id_tipoactividad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->organizador->Visible) { // organizador ?>
		<td data-name="organizador"<?php echo $actividad->organizador->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_organizador" class="actividad_organizador">
<span<?php echo $actividad->organizador->ViewAttributes() ?>>
<?php echo $actividad->organizador->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->nombreactividad->Visible) { // nombreactividad ?>
		<td data-name="nombreactividad"<?php echo $actividad->nombreactividad->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_nombreactividad" class="actividad_nombreactividad">
<span<?php echo $actividad->nombreactividad->ViewAttributes() ?>>
<?php echo $actividad->nombreactividad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->nombrelocal->Visible) { // nombrelocal ?>
		<td data-name="nombrelocal"<?php echo $actividad->nombrelocal->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_nombrelocal" class="actividad_nombrelocal">
<span<?php echo $actividad->nombrelocal->ViewAttributes() ?>>
<?php echo $actividad->nombrelocal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->direccionlocal->Visible) { // direccionlocal ?>
		<td data-name="direccionlocal"<?php echo $actividad->direccionlocal->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_direccionlocal" class="actividad_direccionlocal">
<span<?php echo $actividad->direccionlocal->ViewAttributes() ?>>
<?php echo $actividad->direccionlocal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->fecha_inicio->Visible) { // fecha_inicio ?>
		<td data-name="fecha_inicio"<?php echo $actividad->fecha_inicio->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_fecha_inicio" class="actividad_fecha_inicio">
<span<?php echo $actividad->fecha_inicio->ViewAttributes() ?>>
<?php echo $actividad->fecha_inicio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->fecha_fin->Visible) { // fecha_fin ?>
		<td data-name="fecha_fin"<?php echo $actividad->fecha_fin->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_fecha_fin" class="actividad_fecha_fin">
<span<?php echo $actividad->fecha_fin->ViewAttributes() ?>>
<?php echo $actividad->fecha_fin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->horasprogramadas->Visible) { // horasprogramadas ?>
		<td data-name="horasprogramadas"<?php echo $actividad->horasprogramadas->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_horasprogramadas" class="actividad_horasprogramadas">
<span<?php echo $actividad->horasprogramadas->ViewAttributes() ?>>
<?php echo $actividad->horasprogramadas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona"<?php echo $actividad->id_persona->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_id_persona" class="actividad_id_persona">
<span<?php echo $actividad->id_persona->ViewAttributes() ?>>
<?php echo $actividad->id_persona->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->contenido->Visible) { // contenido ?>
		<td data-name="contenido"<?php echo $actividad->contenido->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_contenido" class="actividad_contenido">
<span<?php echo $actividad->contenido->ViewAttributes() ?>>
<?php echo $actividad->contenido->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($actividad->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $actividad->observaciones->CellAttributes() ?>>
<span id="el<?php echo $actividad_list->RowCnt ?>_actividad_observaciones" class="actividad_observaciones">
<span<?php echo $actividad->observaciones->ViewAttributes() ?>>
<?php echo $actividad->observaciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$actividad_list->ListOptions->Render("body", "right", $actividad_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($actividad->CurrentAction <> "gridadd")
		$actividad_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($actividad->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($actividad_list->Recordset)
	$actividad_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($actividad->CurrentAction <> "gridadd" && $actividad->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($actividad_list->Pager)) $actividad_list->Pager = new cPrevNextPager($actividad_list->StartRec, $actividad_list->DisplayRecs, $actividad_list->TotalRecs, $actividad_list->AutoHidePager) ?>
<?php if ($actividad_list->Pager->RecordCount > 0 && $actividad_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($actividad_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($actividad_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $actividad_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($actividad_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($actividad_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $actividad_list->PageUrl() ?>start=<?php echo $actividad_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $actividad_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($actividad_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $actividad_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $actividad_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $actividad_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($actividad_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($actividad_list->TotalRecs == 0 && $actividad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($actividad_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
factividadlistsrch.FilterList = <?php echo $actividad_list->GetFilterList() ?>;
factividadlistsrch.Init();
factividadlist.Init();
</script>
<?php
$actividad_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$actividad_list->Page_Terminate();
?>
