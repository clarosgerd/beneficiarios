<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "otrosinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$otros_list = NULL; // Initialize page object first

class cotros_list extends cotros {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'otros';

	// Page object name
	var $PageObjName = 'otros_list';

	// Grid form hidden field names
	var $FormName = 'fotroslist';
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

		// Table object (otros)
		if (!isset($GLOBALS["otros"]) || get_class($GLOBALS["otros"]) == "cotros") {
			$GLOBALS["otros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["otros"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "otrosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "otrosdelete.php";
		$this->MultiUpdateUrl = "otrosupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'otros', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fotroslistsrch";

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
		$this->id_actividad->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombre->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->nivelestudio->SetVisibility();
		$this->id_discapacidad->SetVisibility();
		$this->id_tipodiscapacidad->SetVisibility();
		$this->resultado->SetVisibility();
		$this->resultadotamizaje->SetVisibility();
		$this->tapon->SetVisibility();
		$this->tipo->SetVisibility();
		$this->repetirprueba->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->id_apoderado->SetVisibility();
		$this->id_referencia->SetVisibility();

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
		global $EW_EXPORT, $otros;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($otros);
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
		$sFilterList = ew_Concat($sFilterList, $this->id_actividad->AdvancedSearch->ToJson(), ","); // Field id_actividad
		$sFilterList = ew_Concat($sFilterList, $this->apellidopaterno->AdvancedSearch->ToJson(), ","); // Field apellidopaterno
		$sFilterList = ew_Concat($sFilterList, $this->apellidomaterno->AdvancedSearch->ToJson(), ","); // Field apellidomaterno
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJson(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->nrodiscapacidad->AdvancedSearch->ToJson(), ","); // Field nrodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->ci->AdvancedSearch->ToJson(), ","); // Field ci
		$sFilterList = ew_Concat($sFilterList, $this->fecha_nacimiento->AdvancedSearch->ToJson(), ","); // Field fecha_nacimiento
		$sFilterList = ew_Concat($sFilterList, $this->sexo->AdvancedSearch->ToJson(), ","); // Field sexo
		$sFilterList = ew_Concat($sFilterList, $this->nivelestudio->AdvancedSearch->ToJson(), ","); // Field nivelestudio
		$sFilterList = ew_Concat($sFilterList, $this->id_discapacidad->AdvancedSearch->ToJson(), ","); // Field id_discapacidad
		$sFilterList = ew_Concat($sFilterList, $this->id_tipodiscapacidad->AdvancedSearch->ToJson(), ","); // Field id_tipodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->resultado->AdvancedSearch->ToJson(), ","); // Field resultado
		$sFilterList = ew_Concat($sFilterList, $this->resultadotamizaje->AdvancedSearch->ToJson(), ","); // Field resultadotamizaje
		$sFilterList = ew_Concat($sFilterList, $this->tapon->AdvancedSearch->ToJson(), ","); // Field tapon
		$sFilterList = ew_Concat($sFilterList, $this->tipo->AdvancedSearch->ToJson(), ","); // Field tipo
		$sFilterList = ew_Concat($sFilterList, $this->repetirprueba->AdvancedSearch->ToJson(), ","); // Field repetirprueba
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJson(), ","); // Field observaciones
		$sFilterList = ew_Concat($sFilterList, $this->id_apoderado->AdvancedSearch->ToJson(), ","); // Field id_apoderado
		$sFilterList = ew_Concat($sFilterList, $this->id_referencia->AdvancedSearch->ToJson(), ","); // Field id_referencia
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fotroslistsrch", $filters);

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

		// Field id_actividad
		$this->id_actividad->AdvancedSearch->SearchValue = @$filter["x_id_actividad"];
		$this->id_actividad->AdvancedSearch->SearchOperator = @$filter["z_id_actividad"];
		$this->id_actividad->AdvancedSearch->SearchCondition = @$filter["v_id_actividad"];
		$this->id_actividad->AdvancedSearch->SearchValue2 = @$filter["y_id_actividad"];
		$this->id_actividad->AdvancedSearch->SearchOperator2 = @$filter["w_id_actividad"];
		$this->id_actividad->AdvancedSearch->Save();

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

		// Field nombre
		$this->nombre->AdvancedSearch->SearchValue = @$filter["x_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator = @$filter["z_nombre"];
		$this->nombre->AdvancedSearch->SearchCondition = @$filter["v_nombre"];
		$this->nombre->AdvancedSearch->SearchValue2 = @$filter["y_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator2 = @$filter["w_nombre"];
		$this->nombre->AdvancedSearch->Save();

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

		// Field fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = @$filter["x_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = @$filter["z_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchCondition = @$filter["v_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchValue2 = @$filter["y_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->Save();

		// Field sexo
		$this->sexo->AdvancedSearch->SearchValue = @$filter["x_sexo"];
		$this->sexo->AdvancedSearch->SearchOperator = @$filter["z_sexo"];
		$this->sexo->AdvancedSearch->SearchCondition = @$filter["v_sexo"];
		$this->sexo->AdvancedSearch->SearchValue2 = @$filter["y_sexo"];
		$this->sexo->AdvancedSearch->SearchOperator2 = @$filter["w_sexo"];
		$this->sexo->AdvancedSearch->Save();

		// Field nivelestudio
		$this->nivelestudio->AdvancedSearch->SearchValue = @$filter["x_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->SearchOperator = @$filter["z_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->SearchCondition = @$filter["v_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->SearchValue2 = @$filter["y_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->SearchOperator2 = @$filter["w_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->Save();

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

		// Field tipo
		$this->tipo->AdvancedSearch->SearchValue = @$filter["x_tipo"];
		$this->tipo->AdvancedSearch->SearchOperator = @$filter["z_tipo"];
		$this->tipo->AdvancedSearch->SearchCondition = @$filter["v_tipo"];
		$this->tipo->AdvancedSearch->SearchValue2 = @$filter["y_tipo"];
		$this->tipo->AdvancedSearch->SearchOperator2 = @$filter["w_tipo"];
		$this->tipo->AdvancedSearch->Save();

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
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->id_actividad, $Default, FALSE); // id_actividad
		$this->BuildSearchSql($sWhere, $this->apellidopaterno, $Default, FALSE); // apellidopaterno
		$this->BuildSearchSql($sWhere, $this->apellidomaterno, $Default, FALSE); // apellidomaterno
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->nrodiscapacidad, $Default, FALSE); // nrodiscapacidad
		$this->BuildSearchSql($sWhere, $this->ci, $Default, FALSE); // ci
		$this->BuildSearchSql($sWhere, $this->fecha_nacimiento, $Default, FALSE); // fecha_nacimiento
		$this->BuildSearchSql($sWhere, $this->sexo, $Default, FALSE); // sexo
		$this->BuildSearchSql($sWhere, $this->nivelestudio, $Default, FALSE); // nivelestudio
		$this->BuildSearchSql($sWhere, $this->id_discapacidad, $Default, FALSE); // id_discapacidad
		$this->BuildSearchSql($sWhere, $this->id_tipodiscapacidad, $Default, FALSE); // id_tipodiscapacidad
		$this->BuildSearchSql($sWhere, $this->resultado, $Default, FALSE); // resultado
		$this->BuildSearchSql($sWhere, $this->resultadotamizaje, $Default, FALSE); // resultadotamizaje
		$this->BuildSearchSql($sWhere, $this->tapon, $Default, FALSE); // tapon
		$this->BuildSearchSql($sWhere, $this->tipo, $Default, FALSE); // tipo
		$this->BuildSearchSql($sWhere, $this->repetirprueba, $Default, FALSE); // repetirprueba
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->id_apoderado, $Default, FALSE); // id_apoderado
		$this->BuildSearchSql($sWhere, $this->id_referencia, $Default, FALSE); // id_referencia

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->id_actividad->AdvancedSearch->Save(); // id_actividad
			$this->apellidopaterno->AdvancedSearch->Save(); // apellidopaterno
			$this->apellidomaterno->AdvancedSearch->Save(); // apellidomaterno
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->nrodiscapacidad->AdvancedSearch->Save(); // nrodiscapacidad
			$this->ci->AdvancedSearch->Save(); // ci
			$this->fecha_nacimiento->AdvancedSearch->Save(); // fecha_nacimiento
			$this->sexo->AdvancedSearch->Save(); // sexo
			$this->nivelestudio->AdvancedSearch->Save(); // nivelestudio
			$this->id_discapacidad->AdvancedSearch->Save(); // id_discapacidad
			$this->id_tipodiscapacidad->AdvancedSearch->Save(); // id_tipodiscapacidad
			$this->resultado->AdvancedSearch->Save(); // resultado
			$this->resultadotamizaje->AdvancedSearch->Save(); // resultadotamizaje
			$this->tapon->AdvancedSearch->Save(); // tapon
			$this->tipo->AdvancedSearch->Save(); // tipo
			$this->repetirprueba->AdvancedSearch->Save(); // repetirprueba
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->id_apoderado->AdvancedSearch->Save(); // id_apoderado
			$this->id_referencia->AdvancedSearch->Save(); // id_referencia
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
		if ($this->id_actividad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidopaterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidomaterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nrodiscapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ci->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_nacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nivelestudio->AdvancedSearch->IssetSession())
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
		if ($this->tipo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->repetirprueba->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_apoderado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_referencia->AdvancedSearch->IssetSession())
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
		$this->id_actividad->AdvancedSearch->UnsetSession();
		$this->apellidopaterno->AdvancedSearch->UnsetSession();
		$this->apellidomaterno->AdvancedSearch->UnsetSession();
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->nrodiscapacidad->AdvancedSearch->UnsetSession();
		$this->ci->AdvancedSearch->UnsetSession();
		$this->fecha_nacimiento->AdvancedSearch->UnsetSession();
		$this->sexo->AdvancedSearch->UnsetSession();
		$this->nivelestudio->AdvancedSearch->UnsetSession();
		$this->id_discapacidad->AdvancedSearch->UnsetSession();
		$this->id_tipodiscapacidad->AdvancedSearch->UnsetSession();
		$this->resultado->AdvancedSearch->UnsetSession();
		$this->resultadotamizaje->AdvancedSearch->UnsetSession();
		$this->tapon->AdvancedSearch->UnsetSession();
		$this->tipo->AdvancedSearch->UnsetSession();
		$this->repetirprueba->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->id_apoderado->AdvancedSearch->UnsetSession();
		$this->id_referencia->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->id_actividad->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->nivelestudio->AdvancedSearch->Load();
		$this->id_discapacidad->AdvancedSearch->Load();
		$this->id_tipodiscapacidad->AdvancedSearch->Load();
		$this->resultado->AdvancedSearch->Load();
		$this->resultadotamizaje->AdvancedSearch->Load();
		$this->tapon->AdvancedSearch->Load();
		$this->tipo->AdvancedSearch->Load();
		$this->repetirprueba->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->id_apoderado->AdvancedSearch->Load();
		$this->id_referencia->AdvancedSearch->Load();
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
			$this->UpdateSort($this->id_actividad, $bCtrl); // id_actividad
			$this->UpdateSort($this->apellidopaterno, $bCtrl); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno, $bCtrl); // apellidomaterno
			$this->UpdateSort($this->nombre, $bCtrl); // nombre
			$this->UpdateSort($this->nrodiscapacidad, $bCtrl); // nrodiscapacidad
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->fecha_nacimiento, $bCtrl); // fecha_nacimiento
			$this->UpdateSort($this->sexo, $bCtrl); // sexo
			$this->UpdateSort($this->nivelestudio, $bCtrl); // nivelestudio
			$this->UpdateSort($this->id_discapacidad, $bCtrl); // id_discapacidad
			$this->UpdateSort($this->id_tipodiscapacidad, $bCtrl); // id_tipodiscapacidad
			$this->UpdateSort($this->resultado, $bCtrl); // resultado
			$this->UpdateSort($this->resultadotamizaje, $bCtrl); // resultadotamizaje
			$this->UpdateSort($this->tapon, $bCtrl); // tapon
			$this->UpdateSort($this->tipo, $bCtrl); // tipo
			$this->UpdateSort($this->repetirprueba, $bCtrl); // repetirprueba
			$this->UpdateSort($this->observaciones, $bCtrl); // observaciones
			$this->UpdateSort($this->id_apoderado, $bCtrl); // id_apoderado
			$this->UpdateSort($this->id_referencia, $bCtrl); // id_referencia
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
				$this->id->setSort("");
				$this->id_actividad->setSort("");
				$this->apellidopaterno->setSort("");
				$this->apellidomaterno->setSort("");
				$this->nombre->setSort("");
				$this->nrodiscapacidad->setSort("");
				$this->ci->setSort("");
				$this->fecha_nacimiento->setSort("");
				$this->sexo->setSort("");
				$this->nivelestudio->setSort("");
				$this->id_discapacidad->setSort("");
				$this->id_tipodiscapacidad->setSort("");
				$this->resultado->setSort("");
				$this->resultadotamizaje->setSort("");
				$this->tapon->setSort("");
				$this->tipo->setSort("");
				$this->repetirprueba->setSort("");
				$this->observaciones->setSort("");
				$this->id_apoderado->setSort("");
				$this->id_referencia->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fotroslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fotroslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fotroslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fotroslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

		// id_actividad
		$this->id_actividad->AdvancedSearch->SearchValue = @$_GET["x_id_actividad"];
		if ($this->id_actividad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_actividad->AdvancedSearch->SearchOperator = @$_GET["z_id_actividad"];

		// apellidopaterno
		$this->apellidopaterno->AdvancedSearch->SearchValue = @$_GET["x_apellidopaterno"];
		if ($this->apellidopaterno->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->apellidopaterno->AdvancedSearch->SearchOperator = @$_GET["z_apellidopaterno"];

		// apellidomaterno
		$this->apellidomaterno->AdvancedSearch->SearchValue = @$_GET["x_apellidomaterno"];
		if ($this->apellidomaterno->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->apellidomaterno->AdvancedSearch->SearchOperator = @$_GET["z_apellidomaterno"];

		// nombre
		$this->nombre->AdvancedSearch->SearchValue = @$_GET["x_nombre"];
		if ($this->nombre->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombre->AdvancedSearch->SearchOperator = @$_GET["z_nombre"];

		// nrodiscapacidad
		$this->nrodiscapacidad->AdvancedSearch->SearchValue = @$_GET["x_nrodiscapacidad"];
		if ($this->nrodiscapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator = @$_GET["z_nrodiscapacidad"];

		// ci
		$this->ci->AdvancedSearch->SearchValue = @$_GET["x_ci"];
		if ($this->ci->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->ci->AdvancedSearch->SearchOperator = @$_GET["z_ci"];

		// fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = @$_GET["x_fecha_nacimiento"];
		if ($this->fecha_nacimiento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = @$_GET["z_fecha_nacimiento"];

		// sexo
		$this->sexo->AdvancedSearch->SearchValue = @$_GET["x_sexo"];
		if ($this->sexo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sexo->AdvancedSearch->SearchOperator = @$_GET["z_sexo"];

		// nivelestudio
		$this->nivelestudio->AdvancedSearch->SearchValue = @$_GET["x_nivelestudio"];
		if ($this->nivelestudio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nivelestudio->AdvancedSearch->SearchOperator = @$_GET["z_nivelestudio"];

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

		// tipo
		$this->tipo->AdvancedSearch->SearchValue = @$_GET["x_tipo"];
		if ($this->tipo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipo->AdvancedSearch->SearchOperator = @$_GET["z_tipo"];

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
		$this->id_actividad->setDbValue($row['id_actividad']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->ci->setDbValue($row['ci']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->nivelestudio->setDbValue($row['nivelestudio']);
		$this->id_discapacidad->setDbValue($row['id_discapacidad']);
		$this->id_tipodiscapacidad->setDbValue($row['id_tipodiscapacidad']);
		$this->resultado->setDbValue($row['resultado']);
		$this->resultadotamizaje->setDbValue($row['resultadotamizaje']);
		$this->tapon->setDbValue($row['tapon']);
		$this->tipo->setDbValue($row['tipo']);
		$this->repetirprueba->setDbValue($row['repetirprueba']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_apoderado->setDbValue($row['id_apoderado']);
		$this->id_referencia->setDbValue($row['id_referencia']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_actividad'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombre'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['ci'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['nivelestudio'] = NULL;
		$row['id_discapacidad'] = NULL;
		$row['id_tipodiscapacidad'] = NULL;
		$row['resultado'] = NULL;
		$row['resultadotamizaje'] = NULL;
		$row['tapon'] = NULL;
		$row['tipo'] = NULL;
		$row['repetirprueba'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_apoderado'] = NULL;
		$row['id_referencia'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_actividad->DbValue = $row['id_actividad'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->ci->DbValue = $row['ci'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->nivelestudio->DbValue = $row['nivelestudio'];
		$this->id_discapacidad->DbValue = $row['id_discapacidad'];
		$this->id_tipodiscapacidad->DbValue = $row['id_tipodiscapacidad'];
		$this->resultado->DbValue = $row['resultado'];
		$this->resultadotamizaje->DbValue = $row['resultadotamizaje'];
		$this->tapon->DbValue = $row['tapon'];
		$this->tipo->DbValue = $row['tipo'];
		$this->repetirprueba->DbValue = $row['repetirprueba'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_apoderado->DbValue = $row['id_apoderado'];
		$this->id_referencia->DbValue = $row['id_referencia'];
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
		// id_actividad
		// apellidopaterno
		// apellidomaterno
		// nombre
		// nrodiscapacidad
		// ci
		// fecha_nacimiento
		// sexo
		// nivelestudio
		// id_discapacidad
		// id_tipodiscapacidad
		// resultado
		// resultadotamizaje
		// tapon
		// tipo
		// repetirprueba
		// observaciones
		// id_apoderado
		// id_referencia
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_actividad
		if (strval($this->id_actividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
		$sWhereWrk = "";
		$this->id_actividad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_actividad->ViewValue = $this->id_actividad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_actividad->ViewValue = $this->id_actividad->CurrentValue;
			}
		} else {
			$this->id_actividad->ViewValue = NULL;
		}
		$this->id_actividad->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// nivelestudio
		$this->nivelestudio->ViewValue = $this->nivelestudio->CurrentValue;
		$this->nivelestudio->ViewCustomAttributes = "";

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
		$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->CurrentValue;
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
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tapon->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tapon`";
		$sWhereWrk = "";
		$this->tapon->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tapon, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tapon->ViewValue = $this->tapon->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tapon->ViewValue = $this->tapon->CurrentValue;
			}
		} else {
			$this->tapon->ViewValue = NULL;
		}
		$this->tapon->ViewCustomAttributes = "";

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$this->tipo->ViewValue = $this->tipo->OptionCaption($this->tipo->CurrentValue);
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

		// repetirprueba
		$this->repetirprueba->ViewValue = $this->repetirprueba->CurrentValue;
		$this->repetirprueba->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_apoderado
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
		$this->id_apoderado->ViewCustomAttributes = "";

		// id_referencia
		if (strval($this->id_referencia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_referencia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombrescentromedico` AS `DispFld`, `nombrescompleto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referencia`";
		$sWhereWrk = "";
		$this->id_referencia->LookupFilters = array("dx1" => '`nombrescentromedico`', "dx2" => '`nombrescompleto`');
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
		$this->id_referencia->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";
			$this->id_actividad->TooltipValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";
			$this->apellidopaterno->TooltipValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";
			$this->apellidomaterno->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
			$this->nrodiscapacidad->TooltipValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";
			$this->sexo->TooltipValue = "";

			// nivelestudio
			$this->nivelestudio->LinkCustomAttributes = "";
			$this->nivelestudio->HrefValue = "";
			$this->nivelestudio->TooltipValue = "";

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

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// id_actividad
			$this->id_actividad->EditAttrs["class"] = "form-control";
			$this->id_actividad->EditCustomAttributes = "";

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

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->AdvancedSearch->SearchValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

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

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_nacimiento->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// nivelestudio
			$this->nivelestudio->EditAttrs["class"] = "form-control";
			$this->nivelestudio->EditCustomAttributes = "";
			$this->nivelestudio->EditValue = ew_HtmlEncode($this->nivelestudio->AdvancedSearch->SearchValue);
			$this->nivelestudio->PlaceHolder = ew_RemoveHtml($this->nivelestudio->FldCaption());

			// id_discapacidad
			$this->id_discapacidad->EditAttrs["class"] = "form-control";
			$this->id_discapacidad->EditCustomAttributes = "";
			if (trim(strval($this->id_discapacidad->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_discapacidad->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `discapacidad`";
			$sWhereWrk = "";
			$this->id_discapacidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_discapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_discapacidad->EditValue = $arwrk;

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->EditAttrs["class"] = "form-control";
			$this->id_tipodiscapacidad->EditCustomAttributes = "";
			$this->id_tipodiscapacidad->EditValue = ew_HtmlEncode($this->id_tipodiscapacidad->AdvancedSearch->SearchValue);
			$this->id_tipodiscapacidad->PlaceHolder = ew_RemoveHtml($this->id_tipodiscapacidad->FldCaption());

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

			// tipo
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = $this->tipo->Options(FALSE);

			// repetirprueba
			$this->repetirprueba->EditAttrs["class"] = "form-control";
			$this->repetirprueba->EditCustomAttributes = "";
			$this->repetirprueba->EditValue = ew_HtmlEncode($this->repetirprueba->AdvancedSearch->SearchValue);
			$this->repetirprueba->PlaceHolder = ew_RemoveHtml($this->repetirprueba->FldCaption());

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->AdvancedSearch->SearchValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// id_apoderado
			$this->id_apoderado->EditAttrs["class"] = "form-control";
			$this->id_apoderado->EditCustomAttributes = "";

			// id_referencia
			$this->id_referencia->EditAttrs["class"] = "form-control";
			$this->id_referencia->EditCustomAttributes = "";
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
		$this->id_actividad->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->nivelestudio->AdvancedSearch->Load();
		$this->id_discapacidad->AdvancedSearch->Load();
		$this->id_tipodiscapacidad->AdvancedSearch->Load();
		$this->resultado->AdvancedSearch->Load();
		$this->resultadotamizaje->AdvancedSearch->Load();
		$this->tapon->AdvancedSearch->Load();
		$this->tipo->AdvancedSearch->Load();
		$this->repetirprueba->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->id_apoderado->AdvancedSearch->Load();
		$this->id_referencia->AdvancedSearch->Load();
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
		case "x_id_discapacidad":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->id_discapacidad, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($otros_list)) $otros_list = new cotros_list();

// Page init
$otros_list->Page_Init();

// Page main
$otros_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$otros_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fotroslist = new ew_Form("fotroslist", "list");
fotroslist.FormKeyCountName = '<?php echo $otros_list->FormKeyCountName ?>';

// Form_CustomValidate event
fotroslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fotroslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fotroslist.Lists["x_id_actividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreactividad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"actividad"};
fotroslist.Lists["x_id_actividad"].Data = "<?php echo $otros_list->id_actividad->LookupFilterQuery(FALSE, "list") ?>";
fotroslist.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fotroslist.Lists["x_sexo"].Options = <?php echo json_encode($otros_list->sexo->Options()) ?>;
fotroslist.Lists["x_id_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fotroslist.Lists["x_id_discapacidad"].Data = "<?php echo $otros_list->id_discapacidad->LookupFilterQuery(FALSE, "list") ?>";
fotroslist.Lists["x_id_tipodiscapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiscapacidad"};
fotroslist.Lists["x_id_tipodiscapacidad"].Data = "<?php echo $otros_list->id_tipodiscapacidad->LookupFilterQuery(FALSE, "list") ?>";
fotroslist.AutoSuggests["x_id_tipodiscapacidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $otros_list->id_tipodiscapacidad->LookupFilterQuery(TRUE, "list"))) ?>;
fotroslist.Lists["x_resultado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fotroslist.Lists["x_resultado"].Options = <?php echo json_encode($otros_list->resultado->Options()) ?>;
fotroslist.Lists["x_tapon"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tapon"};
fotroslist.Lists["x_tapon"].Data = "<?php echo $otros_list->tapon->LookupFilterQuery(FALSE, "list") ?>";
fotroslist.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fotroslist.Lists["x_tipo"].Options = <?php echo json_encode($otros_list->tipo->Options()) ?>;
fotroslist.Lists["x_id_apoderado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"apoderado"};
fotroslist.Lists["x_id_apoderado"].Data = "<?php echo $otros_list->id_apoderado->LookupFilterQuery(FALSE, "list") ?>";
fotroslist.Lists["x_id_referencia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombrescentromedico","x_nombrescompleto","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referencia"};
fotroslist.Lists["x_id_referencia"].Data = "<?php echo $otros_list->id_referencia->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fotroslistsrch = new ew_Form("fotroslistsrch");

// Validate function for search
fotroslistsrch.Validate = function(fobj) {
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
fotroslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fotroslistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fotroslistsrch.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fotroslistsrch.Lists["x_sexo"].Options = <?php echo json_encode($otros_list->sexo->Options()) ?>;
fotroslistsrch.Lists["x_id_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fotroslistsrch.Lists["x_id_discapacidad"].Data = "<?php echo $otros_list->id_discapacidad->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($otros_list->TotalRecs > 0 && $otros_list->ExportOptions->Visible()) { ?>
<?php $otros_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($otros_list->SearchOptions->Visible()) { ?>
<?php $otros_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($otros_list->FilterOptions->Visible()) { ?>
<?php $otros_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $otros_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($otros_list->TotalRecs <= 0)
			$otros_list->TotalRecs = $otros->ListRecordCount();
	} else {
		if (!$otros_list->Recordset && ($otros_list->Recordset = $otros_list->LoadRecordset()))
			$otros_list->TotalRecs = $otros_list->Recordset->RecordCount();
	}
	$otros_list->StartRec = 1;
	if ($otros_list->DisplayRecs <= 0 || ($otros->Export <> "" && $otros->ExportAll)) // Display all records
		$otros_list->DisplayRecs = $otros_list->TotalRecs;
	if (!($otros->Export <> "" && $otros->ExportAll))
		$otros_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$otros_list->Recordset = $otros_list->LoadRecordset($otros_list->StartRec-1, $otros_list->DisplayRecs);

	// Set no record found message
	if ($otros->CurrentAction == "" && $otros_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$otros_list->setWarningMessage(ew_DeniedMsg());
		if ($otros_list->SearchWhere == "0=101")
			$otros_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$otros_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$otros_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($otros->Export == "" && $otros->CurrentAction == "") { ?>
<form name="fotroslistsrch" id="fotroslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($otros_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fotroslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="otros">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$otros_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$otros->RowType = EW_ROWTYPE_SEARCH;

// Render row
$otros->ResetAttrs();
$otros_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($otros->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="xsc_apellidopaterno" class="ewCell form-group">
		<label for="x_apellidopaterno" class="ewSearchCaption ewLabel"><?php echo $otros->apellidopaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidopaterno" id="z_apellidopaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="otros" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $otros->apellidopaterno->EditValue ?>"<?php echo $otros->apellidopaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($otros->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="xsc_apellidomaterno" class="ewCell form-group">
		<label for="x_apellidomaterno" class="ewSearchCaption ewLabel"><?php echo $otros->apellidomaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidomaterno" id="z_apellidomaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="otros" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $otros->apellidomaterno->EditValue ?>"<?php echo $otros->apellidomaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($otros->nombre->Visible) { // nombre ?>
	<div id="xsc_nombre" class="ewCell form-group">
		<label for="x_nombre" class="ewSearchCaption ewLabel"><?php echo $otros->nombre->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombre" id="z_nombre" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="otros" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->nombre->getPlaceHolder()) ?>" value="<?php echo $otros->nombre->EditValue ?>"<?php echo $otros->nombre->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($otros->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="xsc_nrodiscapacidad" class="ewCell form-group">
		<label for="x_nrodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $otros->nrodiscapacidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nrodiscapacidad" id="z_nrodiscapacidad" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="otros" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $otros->nrodiscapacidad->EditValue ?>"<?php echo $otros->nrodiscapacidad->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($otros->ci->Visible) { // ci ?>
	<div id="xsc_ci" class="ewCell form-group">
		<label for="x_ci" class="ewSearchCaption ewLabel"><?php echo $otros->ci->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ci" id="z_ci" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="otros" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->ci->getPlaceHolder()) ?>" value="<?php echo $otros->ci->EditValue ?>"<?php echo $otros->ci->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($otros->sexo->Visible) { // sexo ?>
	<div id="xsc_sexo" class="ewCell form-group">
		<label for="x_sexo" class="ewSearchCaption ewLabel"><?php echo $otros->sexo->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_sexo" id="z_sexo" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="otros" data-field="x_sexo" data-value-separator="<?php echo $otros->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $otros->sexo->EditAttributes() ?>>
<?php echo $otros->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($otros->nivelestudio->Visible) { // nivelestudio ?>
	<div id="xsc_nivelestudio" class="ewCell form-group">
		<label for="x_nivelestudio" class="ewSearchCaption ewLabel"><?php echo $otros->nivelestudio->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nivelestudio" id="z_nivelestudio" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="otros" data-field="x_nivelestudio" name="x_nivelestudio" id="x_nivelestudio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($otros->nivelestudio->getPlaceHolder()) ?>" value="<?php echo $otros->nivelestudio->EditValue ?>"<?php echo $otros->nivelestudio->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
<?php if ($otros->id_discapacidad->Visible) { // id_discapacidad ?>
	<div id="xsc_id_discapacidad" class="ewCell form-group">
		<label for="x_id_discapacidad" class="ewSearchCaption ewLabel"><?php echo $otros->id_discapacidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_id_discapacidad" id="z_id_discapacidad" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="otros" data-field="x_id_discapacidad" data-value-separator="<?php echo $otros->id_discapacidad->DisplayValueSeparatorAttribute() ?>" id="x_id_discapacidad" name="x_id_discapacidad"<?php echo $otros->id_discapacidad->EditAttributes() ?>>
<?php echo $otros->id_discapacidad->SelectOptionListHtml("x_id_discapacidad") ?>
</select>
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
<?php $otros_list->ShowPageHeader(); ?>
<?php
$otros_list->ShowMessage();
?>
<?php if ($otros_list->TotalRecs > 0 || $otros->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($otros_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> otros">
<div class="box-header ewGridUpperPanel">
<?php if ($otros->CurrentAction <> "gridadd" && $otros->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($otros_list->Pager)) $otros_list->Pager = new cPrevNextPager($otros_list->StartRec, $otros_list->DisplayRecs, $otros_list->TotalRecs, $otros_list->AutoHidePager) ?>
<?php if ($otros_list->Pager->RecordCount > 0 && $otros_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($otros_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $otros_list->PageUrl() ?>start=<?php echo $otros_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($otros_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $otros_list->PageUrl() ?>start=<?php echo $otros_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $otros_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($otros_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $otros_list->PageUrl() ?>start=<?php echo $otros_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($otros_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $otros_list->PageUrl() ?>start=<?php echo $otros_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $otros_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($otros_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $otros_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $otros_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $otros_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($otros_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fotroslist" id="fotroslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($otros_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $otros_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="otros">
<div id="gmp_otros" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($otros_list->TotalRecs > 0 || $otros->CurrentAction == "gridedit") { ?>
<table id="tbl_otroslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$otros_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$otros_list->RenderListOptions();

// Render list options (header, left)
$otros_list->ListOptions->Render("header", "left");
?>
<?php if ($otros->id->Visible) { // id ?>
	<?php if ($otros->SortUrl($otros->id) == "") { ?>
		<th data-name="id" class="<?php echo $otros->id->HeaderCellClass() ?>"><div id="elh_otros_id" class="otros_id"><div class="ewTableHeaderCaption"><?php echo $otros->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $otros->id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->id) ?>',2);"><div id="elh_otros_id" class="otros_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->id_actividad->Visible) { // id_actividad ?>
	<?php if ($otros->SortUrl($otros->id_actividad) == "") { ?>
		<th data-name="id_actividad" class="<?php echo $otros->id_actividad->HeaderCellClass() ?>"><div id="elh_otros_id_actividad" class="otros_id_actividad"><div class="ewTableHeaderCaption"><?php echo $otros->id_actividad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_actividad" class="<?php echo $otros->id_actividad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->id_actividad) ?>',2);"><div id="elh_otros_id_actividad" class="otros_id_actividad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->id_actividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->id_actividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->id_actividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->apellidopaterno->Visible) { // apellidopaterno ?>
	<?php if ($otros->SortUrl($otros->apellidopaterno) == "") { ?>
		<th data-name="apellidopaterno" class="<?php echo $otros->apellidopaterno->HeaderCellClass() ?>"><div id="elh_otros_apellidopaterno" class="otros_apellidopaterno"><div class="ewTableHeaderCaption"><?php echo $otros->apellidopaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidopaterno" class="<?php echo $otros->apellidopaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->apellidopaterno) ?>',2);"><div id="elh_otros_apellidopaterno" class="otros_apellidopaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->apellidopaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->apellidomaterno->Visible) { // apellidomaterno ?>
	<?php if ($otros->SortUrl($otros->apellidomaterno) == "") { ?>
		<th data-name="apellidomaterno" class="<?php echo $otros->apellidomaterno->HeaderCellClass() ?>"><div id="elh_otros_apellidomaterno" class="otros_apellidomaterno"><div class="ewTableHeaderCaption"><?php echo $otros->apellidomaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidomaterno" class="<?php echo $otros->apellidomaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->apellidomaterno) ?>',2);"><div id="elh_otros_apellidomaterno" class="otros_apellidomaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->apellidomaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->nombre->Visible) { // nombre ?>
	<?php if ($otros->SortUrl($otros->nombre) == "") { ?>
		<th data-name="nombre" class="<?php echo $otros->nombre->HeaderCellClass() ?>"><div id="elh_otros_nombre" class="otros_nombre"><div class="ewTableHeaderCaption"><?php echo $otros->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre" class="<?php echo $otros->nombre->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->nombre) ?>',2);"><div id="elh_otros_nombre" class="otros_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<?php if ($otros->SortUrl($otros->nrodiscapacidad) == "") { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $otros->nrodiscapacidad->HeaderCellClass() ?>"><div id="elh_otros_nrodiscapacidad" class="otros_nrodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $otros->nrodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $otros->nrodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->nrodiscapacidad) ?>',2);"><div id="elh_otros_nrodiscapacidad" class="otros_nrodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->nrodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->ci->Visible) { // ci ?>
	<?php if ($otros->SortUrl($otros->ci) == "") { ?>
		<th data-name="ci" class="<?php echo $otros->ci->HeaderCellClass() ?>"><div id="elh_otros_ci" class="otros_ci"><div class="ewTableHeaderCaption"><?php echo $otros->ci->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ci" class="<?php echo $otros->ci->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->ci) ?>',2);"><div id="elh_otros_ci" class="otros_ci">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->ci->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<?php if ($otros->SortUrl($otros->fecha_nacimiento) == "") { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $otros->fecha_nacimiento->HeaderCellClass() ?>"><div id="elh_otros_fecha_nacimiento" class="otros_fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $otros->fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $otros->fecha_nacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->fecha_nacimiento) ?>',2);"><div id="elh_otros_fecha_nacimiento" class="otros_fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->sexo->Visible) { // sexo ?>
	<?php if ($otros->SortUrl($otros->sexo) == "") { ?>
		<th data-name="sexo" class="<?php echo $otros->sexo->HeaderCellClass() ?>"><div id="elh_otros_sexo" class="otros_sexo"><div class="ewTableHeaderCaption"><?php echo $otros->sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sexo" class="<?php echo $otros->sexo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->sexo) ?>',2);"><div id="elh_otros_sexo" class="otros_sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->nivelestudio->Visible) { // nivelestudio ?>
	<?php if ($otros->SortUrl($otros->nivelestudio) == "") { ?>
		<th data-name="nivelestudio" class="<?php echo $otros->nivelestudio->HeaderCellClass() ?>"><div id="elh_otros_nivelestudio" class="otros_nivelestudio"><div class="ewTableHeaderCaption"><?php echo $otros->nivelestudio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nivelestudio" class="<?php echo $otros->nivelestudio->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->nivelestudio) ?>',2);"><div id="elh_otros_nivelestudio" class="otros_nivelestudio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->nivelestudio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->nivelestudio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->nivelestudio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->id_discapacidad->Visible) { // id_discapacidad ?>
	<?php if ($otros->SortUrl($otros->id_discapacidad) == "") { ?>
		<th data-name="id_discapacidad" class="<?php echo $otros->id_discapacidad->HeaderCellClass() ?>"><div id="elh_otros_id_discapacidad" class="otros_id_discapacidad"><div class="ewTableHeaderCaption"><?php echo $otros->id_discapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_discapacidad" class="<?php echo $otros->id_discapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->id_discapacidad) ?>',2);"><div id="elh_otros_id_discapacidad" class="otros_id_discapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->id_discapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->id_discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->id_discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
	<?php if ($otros->SortUrl($otros->id_tipodiscapacidad) == "") { ?>
		<th data-name="id_tipodiscapacidad" class="<?php echo $otros->id_tipodiscapacidad->HeaderCellClass() ?>"><div id="elh_otros_id_tipodiscapacidad" class="otros_id_tipodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $otros->id_tipodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipodiscapacidad" class="<?php echo $otros->id_tipodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->id_tipodiscapacidad) ?>',2);"><div id="elh_otros_id_tipodiscapacidad" class="otros_id_tipodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->id_tipodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->id_tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->id_tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->resultado->Visible) { // resultado ?>
	<?php if ($otros->SortUrl($otros->resultado) == "") { ?>
		<th data-name="resultado" class="<?php echo $otros->resultado->HeaderCellClass() ?>"><div id="elh_otros_resultado" class="otros_resultado"><div class="ewTableHeaderCaption"><?php echo $otros->resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultado" class="<?php echo $otros->resultado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->resultado) ?>',2);"><div id="elh_otros_resultado" class="otros_resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->resultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->resultadotamizaje->Visible) { // resultadotamizaje ?>
	<?php if ($otros->SortUrl($otros->resultadotamizaje) == "") { ?>
		<th data-name="resultadotamizaje" class="<?php echo $otros->resultadotamizaje->HeaderCellClass() ?>"><div id="elh_otros_resultadotamizaje" class="otros_resultadotamizaje"><div class="ewTableHeaderCaption"><?php echo $otros->resultadotamizaje->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultadotamizaje" class="<?php echo $otros->resultadotamizaje->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->resultadotamizaje) ?>',2);"><div id="elh_otros_resultadotamizaje" class="otros_resultadotamizaje">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->resultadotamizaje->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->resultadotamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->resultadotamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->tapon->Visible) { // tapon ?>
	<?php if ($otros->SortUrl($otros->tapon) == "") { ?>
		<th data-name="tapon" class="<?php echo $otros->tapon->HeaderCellClass() ?>"><div id="elh_otros_tapon" class="otros_tapon"><div class="ewTableHeaderCaption"><?php echo $otros->tapon->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tapon" class="<?php echo $otros->tapon->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->tapon) ?>',2);"><div id="elh_otros_tapon" class="otros_tapon">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->tapon->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->tapon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->tapon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->tipo->Visible) { // tipo ?>
	<?php if ($otros->SortUrl($otros->tipo) == "") { ?>
		<th data-name="tipo" class="<?php echo $otros->tipo->HeaderCellClass() ?>"><div id="elh_otros_tipo" class="otros_tipo"><div class="ewTableHeaderCaption"><?php echo $otros->tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo" class="<?php echo $otros->tipo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->tipo) ?>',2);"><div id="elh_otros_tipo" class="otros_tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->tipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->repetirprueba->Visible) { // repetirprueba ?>
	<?php if ($otros->SortUrl($otros->repetirprueba) == "") { ?>
		<th data-name="repetirprueba" class="<?php echo $otros->repetirprueba->HeaderCellClass() ?>"><div id="elh_otros_repetirprueba" class="otros_repetirprueba"><div class="ewTableHeaderCaption"><?php echo $otros->repetirprueba->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="repetirprueba" class="<?php echo $otros->repetirprueba->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->repetirprueba) ?>',2);"><div id="elh_otros_repetirprueba" class="otros_repetirprueba">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->repetirprueba->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->repetirprueba->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->repetirprueba->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->observaciones->Visible) { // observaciones ?>
	<?php if ($otros->SortUrl($otros->observaciones) == "") { ?>
		<th data-name="observaciones" class="<?php echo $otros->observaciones->HeaderCellClass() ?>"><div id="elh_otros_observaciones" class="otros_observaciones"><div class="ewTableHeaderCaption"><?php echo $otros->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones" class="<?php echo $otros->observaciones->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->observaciones) ?>',2);"><div id="elh_otros_observaciones" class="otros_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->observaciones->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->id_apoderado->Visible) { // id_apoderado ?>
	<?php if ($otros->SortUrl($otros->id_apoderado) == "") { ?>
		<th data-name="id_apoderado" class="<?php echo $otros->id_apoderado->HeaderCellClass() ?>"><div id="elh_otros_id_apoderado" class="otros_id_apoderado"><div class="ewTableHeaderCaption"><?php echo $otros->id_apoderado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_apoderado" class="<?php echo $otros->id_apoderado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->id_apoderado) ?>',2);"><div id="elh_otros_id_apoderado" class="otros_id_apoderado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->id_apoderado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->id_apoderado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->id_apoderado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($otros->id_referencia->Visible) { // id_referencia ?>
	<?php if ($otros->SortUrl($otros->id_referencia) == "") { ?>
		<th data-name="id_referencia" class="<?php echo $otros->id_referencia->HeaderCellClass() ?>"><div id="elh_otros_id_referencia" class="otros_id_referencia"><div class="ewTableHeaderCaption"><?php echo $otros->id_referencia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_referencia" class="<?php echo $otros->id_referencia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $otros->SortUrl($otros->id_referencia) ?>',2);"><div id="elh_otros_id_referencia" class="otros_id_referencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $otros->id_referencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($otros->id_referencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($otros->id_referencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$otros_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($otros->ExportAll && $otros->Export <> "") {
	$otros_list->StopRec = $otros_list->TotalRecs;
} else {

	// Set the last record to display
	if ($otros_list->TotalRecs > $otros_list->StartRec + $otros_list->DisplayRecs - 1)
		$otros_list->StopRec = $otros_list->StartRec + $otros_list->DisplayRecs - 1;
	else
		$otros_list->StopRec = $otros_list->TotalRecs;
}
$otros_list->RecCnt = $otros_list->StartRec - 1;
if ($otros_list->Recordset && !$otros_list->Recordset->EOF) {
	$otros_list->Recordset->MoveFirst();
	$bSelectLimit = $otros_list->UseSelectLimit;
	if (!$bSelectLimit && $otros_list->StartRec > 1)
		$otros_list->Recordset->Move($otros_list->StartRec - 1);
} elseif (!$otros->AllowAddDeleteRow && $otros_list->StopRec == 0) {
	$otros_list->StopRec = $otros->GridAddRowCount;
}

// Initialize aggregate
$otros->RowType = EW_ROWTYPE_AGGREGATEINIT;
$otros->ResetAttrs();
$otros_list->RenderRow();
while ($otros_list->RecCnt < $otros_list->StopRec) {
	$otros_list->RecCnt++;
	if (intval($otros_list->RecCnt) >= intval($otros_list->StartRec)) {
		$otros_list->RowCnt++;

		// Set up key count
		$otros_list->KeyCount = $otros_list->RowIndex;

		// Init row class and style
		$otros->ResetAttrs();
		$otros->CssClass = "";
		if ($otros->CurrentAction == "gridadd") {
		} else {
			$otros_list->LoadRowValues($otros_list->Recordset); // Load row values
		}
		$otros->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$otros->RowAttrs = array_merge($otros->RowAttrs, array('data-rowindex'=>$otros_list->RowCnt, 'id'=>'r' . $otros_list->RowCnt . '_otros', 'data-rowtype'=>$otros->RowType));

		// Render row
		$otros_list->RenderRow();

		// Render list options
		$otros_list->RenderListOptions();
?>
	<tr<?php echo $otros->RowAttributes() ?>>
<?php

// Render list options (body, left)
$otros_list->ListOptions->Render("body", "left", $otros_list->RowCnt);
?>
	<?php if ($otros->id->Visible) { // id ?>
		<td data-name="id"<?php echo $otros->id->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_id" class="otros_id">
<span<?php echo $otros->id->ViewAttributes() ?>>
<?php echo $otros->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->id_actividad->Visible) { // id_actividad ?>
		<td data-name="id_actividad"<?php echo $otros->id_actividad->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_id_actividad" class="otros_id_actividad">
<span<?php echo $otros->id_actividad->ViewAttributes() ?>>
<?php echo $otros->id_actividad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->apellidopaterno->Visible) { // apellidopaterno ?>
		<td data-name="apellidopaterno"<?php echo $otros->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_apellidopaterno" class="otros_apellidopaterno">
<span<?php echo $otros->apellidopaterno->ViewAttributes() ?>>
<?php echo $otros->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->apellidomaterno->Visible) { // apellidomaterno ?>
		<td data-name="apellidomaterno"<?php echo $otros->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_apellidomaterno" class="otros_apellidomaterno">
<span<?php echo $otros->apellidomaterno->ViewAttributes() ?>>
<?php echo $otros->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $otros->nombre->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_nombre" class="otros_nombre">
<span<?php echo $otros->nombre->ViewAttributes() ?>>
<?php echo $otros->nombre->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td data-name="nrodiscapacidad"<?php echo $otros->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_nrodiscapacidad" class="otros_nrodiscapacidad">
<span<?php echo $otros->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $otros->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->ci->Visible) { // ci ?>
		<td data-name="ci"<?php echo $otros->ci->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_ci" class="otros_ci">
<span<?php echo $otros->ci->ViewAttributes() ?>>
<?php echo $otros->ci->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento"<?php echo $otros->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_fecha_nacimiento" class="otros_fecha_nacimiento">
<span<?php echo $otros->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $otros->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->sexo->Visible) { // sexo ?>
		<td data-name="sexo"<?php echo $otros->sexo->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_sexo" class="otros_sexo">
<span<?php echo $otros->sexo->ViewAttributes() ?>>
<?php echo $otros->sexo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->nivelestudio->Visible) { // nivelestudio ?>
		<td data-name="nivelestudio"<?php echo $otros->nivelestudio->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_nivelestudio" class="otros_nivelestudio">
<span<?php echo $otros->nivelestudio->ViewAttributes() ?>>
<?php echo $otros->nivelestudio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->id_discapacidad->Visible) { // id_discapacidad ?>
		<td data-name="id_discapacidad"<?php echo $otros->id_discapacidad->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_id_discapacidad" class="otros_id_discapacidad">
<span<?php echo $otros->id_discapacidad->ViewAttributes() ?>>
<?php echo $otros->id_discapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
		<td data-name="id_tipodiscapacidad"<?php echo $otros->id_tipodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_id_tipodiscapacidad" class="otros_id_tipodiscapacidad">
<span<?php echo $otros->id_tipodiscapacidad->ViewAttributes() ?>>
<?php echo $otros->id_tipodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->resultado->Visible) { // resultado ?>
		<td data-name="resultado"<?php echo $otros->resultado->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_resultado" class="otros_resultado">
<span<?php echo $otros->resultado->ViewAttributes() ?>>
<?php echo $otros->resultado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->resultadotamizaje->Visible) { // resultadotamizaje ?>
		<td data-name="resultadotamizaje"<?php echo $otros->resultadotamizaje->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_resultadotamizaje" class="otros_resultadotamizaje">
<span<?php echo $otros->resultadotamizaje->ViewAttributes() ?>>
<?php echo $otros->resultadotamizaje->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->tapon->Visible) { // tapon ?>
		<td data-name="tapon"<?php echo $otros->tapon->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_tapon" class="otros_tapon">
<span<?php echo $otros->tapon->ViewAttributes() ?>>
<?php echo $otros->tapon->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->tipo->Visible) { // tipo ?>
		<td data-name="tipo"<?php echo $otros->tipo->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_tipo" class="otros_tipo">
<span<?php echo $otros->tipo->ViewAttributes() ?>>
<?php echo $otros->tipo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->repetirprueba->Visible) { // repetirprueba ?>
		<td data-name="repetirprueba"<?php echo $otros->repetirprueba->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_repetirprueba" class="otros_repetirprueba">
<span<?php echo $otros->repetirprueba->ViewAttributes() ?>>
<?php echo $otros->repetirprueba->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $otros->observaciones->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_observaciones" class="otros_observaciones">
<span<?php echo $otros->observaciones->ViewAttributes() ?>>
<?php echo $otros->observaciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->id_apoderado->Visible) { // id_apoderado ?>
		<td data-name="id_apoderado"<?php echo $otros->id_apoderado->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_id_apoderado" class="otros_id_apoderado">
<span<?php echo $otros->id_apoderado->ViewAttributes() ?>>
<?php echo $otros->id_apoderado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($otros->id_referencia->Visible) { // id_referencia ?>
		<td data-name="id_referencia"<?php echo $otros->id_referencia->CellAttributes() ?>>
<span id="el<?php echo $otros_list->RowCnt ?>_otros_id_referencia" class="otros_id_referencia">
<span<?php echo $otros->id_referencia->ViewAttributes() ?>>
<?php echo $otros->id_referencia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$otros_list->ListOptions->Render("body", "right", $otros_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($otros->CurrentAction <> "gridadd")
		$otros_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($otros->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($otros_list->Recordset)
	$otros_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($otros->CurrentAction <> "gridadd" && $otros->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($otros_list->Pager)) $otros_list->Pager = new cPrevNextPager($otros_list->StartRec, $otros_list->DisplayRecs, $otros_list->TotalRecs, $otros_list->AutoHidePager) ?>
<?php if ($otros_list->Pager->RecordCount > 0 && $otros_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($otros_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $otros_list->PageUrl() ?>start=<?php echo $otros_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($otros_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $otros_list->PageUrl() ?>start=<?php echo $otros_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $otros_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($otros_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $otros_list->PageUrl() ?>start=<?php echo $otros_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($otros_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $otros_list->PageUrl() ?>start=<?php echo $otros_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $otros_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($otros_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $otros_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $otros_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $otros_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($otros_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($otros_list->TotalRecs == 0 && $otros->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($otros_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fotroslistsrch.FilterList = <?php echo $otros_list->GetFilterList() ?>;
fotroslistsrch.Init();
fotroslist.Init();
</script>
<?php
$otros_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$otros_list->Page_Terminate();
?>
