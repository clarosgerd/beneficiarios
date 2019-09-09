<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "Report_Neonatalinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$Report_Neonatal_list = NULL; // Initialize page object first

class cReport_Neonatal_list extends cReport_Neonatal {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'Report Neonatal';

	// Page object name
	var $PageObjName = 'Report_Neonatal_list';

	// Grid form hidden field names
	var $FormName = 'fReport_Neonatallist';
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

		// Table object (Report_Neonatal)
		if (!isset($GLOBALS["Report_Neonatal"]) || get_class($GLOBALS["Report_Neonatal"]) == "cReport_Neonatal") {
			$GLOBALS["Report_Neonatal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Report_Neonatal"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "Report_Neonataladd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "Report_Neonataldelete.php";
		$this->MultiUpdateUrl = "Report_Neonatalupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Report Neonatal', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fReport_Neonatallistsrch";

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
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->id_neonato->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->apellidopaterno->Visible = FALSE;
		$this->apellidomaterno->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->apellidomaterno->Visible = FALSE;
		$this->nombre->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->nombre->Visible = FALSE;
		$this->ci->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->ci->Visible = FALSE;
		$this->fecha_nacimiento->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->fecha_nacimiento->Visible = FALSE;
		$this->dias->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->dias->Visible = FALSE;
		$this->semanas->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->semanas->Visible = FALSE;
		$this->meses->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->meses->Visible = FALSE;
		$this->discapacidad->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->discapacidad->Visible = FALSE;
		$this->resultado->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->resultado->Visible = FALSE;
		$this->observaciones->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->observaciones->Visible = FALSE;
		$this->tipoprueba->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->tipoprueba->Visible = FALSE;
		$this->resultadprueba->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->resultadprueba->Visible = FALSE;
		$this->recomendacion->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->recomendacion->Visible = FALSE;
		$this->id_tipodiagnosticoaudiologia->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->id_tipodiagnosticoaudiologia->Visible = FALSE;
		$this->nombrediagnotico->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->nombrediagnotico->Visible = FALSE;
		$this->resultadodiagnostico->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->resultadodiagnostico->Visible = FALSE;
		$this->tipotratamiento->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->tipotratamiento->Visible = FALSE;
		$this->tipoderivacion->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->tipoderivacion->Visible = FALSE;
		$this->nombreespcialidad->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->nombreespcialidad->Visible = FALSE;
		$this->observaciones1->SetVisibility();
		$this->fecha->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->fecha->Visible = FALSE;

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
		global $EW_EXPORT, $Report_Neonatal;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($Report_Neonatal);
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

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
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
		$sFilterList = ew_Concat($sFilterList, $this->id_neonato->AdvancedSearch->ToJson(), ","); // Field id_neonato
		$sFilterList = ew_Concat($sFilterList, $this->apellidopaterno->AdvancedSearch->ToJson(), ","); // Field apellidopaterno
		$sFilterList = ew_Concat($sFilterList, $this->apellidomaterno->AdvancedSearch->ToJson(), ","); // Field apellidomaterno
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJson(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->ci->AdvancedSearch->ToJson(), ","); // Field ci
		$sFilterList = ew_Concat($sFilterList, $this->fecha_nacimiento->AdvancedSearch->ToJson(), ","); // Field fecha_nacimiento
		$sFilterList = ew_Concat($sFilterList, $this->dias->AdvancedSearch->ToJson(), ","); // Field dias
		$sFilterList = ew_Concat($sFilterList, $this->semanas->AdvancedSearch->ToJson(), ","); // Field semanas
		$sFilterList = ew_Concat($sFilterList, $this->meses->AdvancedSearch->ToJson(), ","); // Field meses
		$sFilterList = ew_Concat($sFilterList, $this->discapacidad->AdvancedSearch->ToJson(), ","); // Field discapacidad
		$sFilterList = ew_Concat($sFilterList, $this->resultado->AdvancedSearch->ToJson(), ","); // Field resultado
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJson(), ","); // Field observaciones
		$sFilterList = ew_Concat($sFilterList, $this->tipoprueba->AdvancedSearch->ToJson(), ","); // Field tipoprueba
		$sFilterList = ew_Concat($sFilterList, $this->resultadprueba->AdvancedSearch->ToJson(), ","); // Field resultadprueba
		$sFilterList = ew_Concat($sFilterList, $this->recomendacion->AdvancedSearch->ToJson(), ","); // Field recomendacion
		$sFilterList = ew_Concat($sFilterList, $this->id_tipodiagnosticoaudiologia->AdvancedSearch->ToJson(), ","); // Field id_tipodiagnosticoaudiologia
		$sFilterList = ew_Concat($sFilterList, $this->nombrediagnotico->AdvancedSearch->ToJson(), ","); // Field nombrediagnotico
		$sFilterList = ew_Concat($sFilterList, $this->resultadodiagnostico->AdvancedSearch->ToJson(), ","); // Field resultadodiagnostico
		$sFilterList = ew_Concat($sFilterList, $this->tipotratamiento->AdvancedSearch->ToJson(), ","); // Field tipotratamiento
		$sFilterList = ew_Concat($sFilterList, $this->tipoderivacion->AdvancedSearch->ToJson(), ","); // Field tipoderivacion
		$sFilterList = ew_Concat($sFilterList, $this->nombreespcialidad->AdvancedSearch->ToJson(), ","); // Field nombreespcialidad
		$sFilterList = ew_Concat($sFilterList, $this->observaciones1->AdvancedSearch->ToJson(), ","); // Field observaciones1
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJson(), ","); // Field fecha
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fReport_Neonatallistsrch", $filters);

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

		// Field id_neonato
		$this->id_neonato->AdvancedSearch->SearchValue = @$filter["x_id_neonato"];
		$this->id_neonato->AdvancedSearch->SearchOperator = @$filter["z_id_neonato"];
		$this->id_neonato->AdvancedSearch->SearchCondition = @$filter["v_id_neonato"];
		$this->id_neonato->AdvancedSearch->SearchValue2 = @$filter["y_id_neonato"];
		$this->id_neonato->AdvancedSearch->SearchOperator2 = @$filter["w_id_neonato"];
		$this->id_neonato->AdvancedSearch->Save();

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

		// Field dias
		$this->dias->AdvancedSearch->SearchValue = @$filter["x_dias"];
		$this->dias->AdvancedSearch->SearchOperator = @$filter["z_dias"];
		$this->dias->AdvancedSearch->SearchCondition = @$filter["v_dias"];
		$this->dias->AdvancedSearch->SearchValue2 = @$filter["y_dias"];
		$this->dias->AdvancedSearch->SearchOperator2 = @$filter["w_dias"];
		$this->dias->AdvancedSearch->Save();

		// Field semanas
		$this->semanas->AdvancedSearch->SearchValue = @$filter["x_semanas"];
		$this->semanas->AdvancedSearch->SearchOperator = @$filter["z_semanas"];
		$this->semanas->AdvancedSearch->SearchCondition = @$filter["v_semanas"];
		$this->semanas->AdvancedSearch->SearchValue2 = @$filter["y_semanas"];
		$this->semanas->AdvancedSearch->SearchOperator2 = @$filter["w_semanas"];
		$this->semanas->AdvancedSearch->Save();

		// Field meses
		$this->meses->AdvancedSearch->SearchValue = @$filter["x_meses"];
		$this->meses->AdvancedSearch->SearchOperator = @$filter["z_meses"];
		$this->meses->AdvancedSearch->SearchCondition = @$filter["v_meses"];
		$this->meses->AdvancedSearch->SearchValue2 = @$filter["y_meses"];
		$this->meses->AdvancedSearch->SearchOperator2 = @$filter["w_meses"];
		$this->meses->AdvancedSearch->Save();

		// Field discapacidad
		$this->discapacidad->AdvancedSearch->SearchValue = @$filter["x_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchOperator = @$filter["z_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchCondition = @$filter["v_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchValue2 = @$filter["y_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_discapacidad"];
		$this->discapacidad->AdvancedSearch->Save();

		// Field resultado
		$this->resultado->AdvancedSearch->SearchValue = @$filter["x_resultado"];
		$this->resultado->AdvancedSearch->SearchOperator = @$filter["z_resultado"];
		$this->resultado->AdvancedSearch->SearchCondition = @$filter["v_resultado"];
		$this->resultado->AdvancedSearch->SearchValue2 = @$filter["y_resultado"];
		$this->resultado->AdvancedSearch->SearchOperator2 = @$filter["w_resultado"];
		$this->resultado->AdvancedSearch->Save();

		// Field observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$filter["x_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator = @$filter["z_observaciones"];
		$this->observaciones->AdvancedSearch->SearchCondition = @$filter["v_observaciones"];
		$this->observaciones->AdvancedSearch->SearchValue2 = @$filter["y_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones"];
		$this->observaciones->AdvancedSearch->Save();

		// Field tipoprueba
		$this->tipoprueba->AdvancedSearch->SearchValue = @$filter["x_tipoprueba"];
		$this->tipoprueba->AdvancedSearch->SearchOperator = @$filter["z_tipoprueba"];
		$this->tipoprueba->AdvancedSearch->SearchCondition = @$filter["v_tipoprueba"];
		$this->tipoprueba->AdvancedSearch->SearchValue2 = @$filter["y_tipoprueba"];
		$this->tipoprueba->AdvancedSearch->SearchOperator2 = @$filter["w_tipoprueba"];
		$this->tipoprueba->AdvancedSearch->Save();

		// Field resultadprueba
		$this->resultadprueba->AdvancedSearch->SearchValue = @$filter["x_resultadprueba"];
		$this->resultadprueba->AdvancedSearch->SearchOperator = @$filter["z_resultadprueba"];
		$this->resultadprueba->AdvancedSearch->SearchCondition = @$filter["v_resultadprueba"];
		$this->resultadprueba->AdvancedSearch->SearchValue2 = @$filter["y_resultadprueba"];
		$this->resultadprueba->AdvancedSearch->SearchOperator2 = @$filter["w_resultadprueba"];
		$this->resultadprueba->AdvancedSearch->Save();

		// Field recomendacion
		$this->recomendacion->AdvancedSearch->SearchValue = @$filter["x_recomendacion"];
		$this->recomendacion->AdvancedSearch->SearchOperator = @$filter["z_recomendacion"];
		$this->recomendacion->AdvancedSearch->SearchCondition = @$filter["v_recomendacion"];
		$this->recomendacion->AdvancedSearch->SearchValue2 = @$filter["y_recomendacion"];
		$this->recomendacion->AdvancedSearch->SearchOperator2 = @$filter["w_recomendacion"];
		$this->recomendacion->AdvancedSearch->Save();

		// Field id_tipodiagnosticoaudiologia
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchValue = @$filter["x_id_tipodiagnosticoaudiologia"];
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchOperator = @$filter["z_id_tipodiagnosticoaudiologia"];
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchCondition = @$filter["v_id_tipodiagnosticoaudiologia"];
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchValue2 = @$filter["y_id_tipodiagnosticoaudiologia"];
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchOperator2 = @$filter["w_id_tipodiagnosticoaudiologia"];
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->Save();

		// Field nombrediagnotico
		$this->nombrediagnotico->AdvancedSearch->SearchValue = @$filter["x_nombrediagnotico"];
		$this->nombrediagnotico->AdvancedSearch->SearchOperator = @$filter["z_nombrediagnotico"];
		$this->nombrediagnotico->AdvancedSearch->SearchCondition = @$filter["v_nombrediagnotico"];
		$this->nombrediagnotico->AdvancedSearch->SearchValue2 = @$filter["y_nombrediagnotico"];
		$this->nombrediagnotico->AdvancedSearch->SearchOperator2 = @$filter["w_nombrediagnotico"];
		$this->nombrediagnotico->AdvancedSearch->Save();

		// Field resultadodiagnostico
		$this->resultadodiagnostico->AdvancedSearch->SearchValue = @$filter["x_resultadodiagnostico"];
		$this->resultadodiagnostico->AdvancedSearch->SearchOperator = @$filter["z_resultadodiagnostico"];
		$this->resultadodiagnostico->AdvancedSearch->SearchCondition = @$filter["v_resultadodiagnostico"];
		$this->resultadodiagnostico->AdvancedSearch->SearchValue2 = @$filter["y_resultadodiagnostico"];
		$this->resultadodiagnostico->AdvancedSearch->SearchOperator2 = @$filter["w_resultadodiagnostico"];
		$this->resultadodiagnostico->AdvancedSearch->Save();

		// Field tipotratamiento
		$this->tipotratamiento->AdvancedSearch->SearchValue = @$filter["x_tipotratamiento"];
		$this->tipotratamiento->AdvancedSearch->SearchOperator = @$filter["z_tipotratamiento"];
		$this->tipotratamiento->AdvancedSearch->SearchCondition = @$filter["v_tipotratamiento"];
		$this->tipotratamiento->AdvancedSearch->SearchValue2 = @$filter["y_tipotratamiento"];
		$this->tipotratamiento->AdvancedSearch->SearchOperator2 = @$filter["w_tipotratamiento"];
		$this->tipotratamiento->AdvancedSearch->Save();

		// Field tipoderivacion
		$this->tipoderivacion->AdvancedSearch->SearchValue = @$filter["x_tipoderivacion"];
		$this->tipoderivacion->AdvancedSearch->SearchOperator = @$filter["z_tipoderivacion"];
		$this->tipoderivacion->AdvancedSearch->SearchCondition = @$filter["v_tipoderivacion"];
		$this->tipoderivacion->AdvancedSearch->SearchValue2 = @$filter["y_tipoderivacion"];
		$this->tipoderivacion->AdvancedSearch->SearchOperator2 = @$filter["w_tipoderivacion"];
		$this->tipoderivacion->AdvancedSearch->Save();

		// Field nombreespcialidad
		$this->nombreespcialidad->AdvancedSearch->SearchValue = @$filter["x_nombreespcialidad"];
		$this->nombreespcialidad->AdvancedSearch->SearchOperator = @$filter["z_nombreespcialidad"];
		$this->nombreespcialidad->AdvancedSearch->SearchCondition = @$filter["v_nombreespcialidad"];
		$this->nombreespcialidad->AdvancedSearch->SearchValue2 = @$filter["y_nombreespcialidad"];
		$this->nombreespcialidad->AdvancedSearch->SearchOperator2 = @$filter["w_nombreespcialidad"];
		$this->nombreespcialidad->AdvancedSearch->Save();

		// Field observaciones1
		$this->observaciones1->AdvancedSearch->SearchValue = @$filter["x_observaciones1"];
		$this->observaciones1->AdvancedSearch->SearchOperator = @$filter["z_observaciones1"];
		$this->observaciones1->AdvancedSearch->SearchCondition = @$filter["v_observaciones1"];
		$this->observaciones1->AdvancedSearch->SearchValue2 = @$filter["y_observaciones1"];
		$this->observaciones1->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones1"];
		$this->observaciones1->AdvancedSearch->Save();

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_neonato, $Default, FALSE); // id_neonato
		$this->BuildSearchSql($sWhere, $this->apellidopaterno, $Default, FALSE); // apellidopaterno
		$this->BuildSearchSql($sWhere, $this->apellidomaterno, $Default, FALSE); // apellidomaterno
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->ci, $Default, FALSE); // ci
		$this->BuildSearchSql($sWhere, $this->fecha_nacimiento, $Default, FALSE); // fecha_nacimiento
		$this->BuildSearchSql($sWhere, $this->dias, $Default, FALSE); // dias
		$this->BuildSearchSql($sWhere, $this->semanas, $Default, FALSE); // semanas
		$this->BuildSearchSql($sWhere, $this->meses, $Default, FALSE); // meses
		$this->BuildSearchSql($sWhere, $this->discapacidad, $Default, FALSE); // discapacidad
		$this->BuildSearchSql($sWhere, $this->resultado, $Default, FALSE); // resultado
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->tipoprueba, $Default, FALSE); // tipoprueba
		$this->BuildSearchSql($sWhere, $this->resultadprueba, $Default, FALSE); // resultadprueba
		$this->BuildSearchSql($sWhere, $this->recomendacion, $Default, FALSE); // recomendacion
		$this->BuildSearchSql($sWhere, $this->id_tipodiagnosticoaudiologia, $Default, FALSE); // id_tipodiagnosticoaudiologia
		$this->BuildSearchSql($sWhere, $this->nombrediagnotico, $Default, FALSE); // nombrediagnotico
		$this->BuildSearchSql($sWhere, $this->resultadodiagnostico, $Default, FALSE); // resultadodiagnostico
		$this->BuildSearchSql($sWhere, $this->tipotratamiento, $Default, FALSE); // tipotratamiento
		$this->BuildSearchSql($sWhere, $this->tipoderivacion, $Default, FALSE); // tipoderivacion
		$this->BuildSearchSql($sWhere, $this->nombreespcialidad, $Default, FALSE); // nombreespcialidad
		$this->BuildSearchSql($sWhere, $this->observaciones1, $Default, FALSE); // observaciones1
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id_neonato->AdvancedSearch->Save(); // id_neonato
			$this->apellidopaterno->AdvancedSearch->Save(); // apellidopaterno
			$this->apellidomaterno->AdvancedSearch->Save(); // apellidomaterno
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->ci->AdvancedSearch->Save(); // ci
			$this->fecha_nacimiento->AdvancedSearch->Save(); // fecha_nacimiento
			$this->dias->AdvancedSearch->Save(); // dias
			$this->semanas->AdvancedSearch->Save(); // semanas
			$this->meses->AdvancedSearch->Save(); // meses
			$this->discapacidad->AdvancedSearch->Save(); // discapacidad
			$this->resultado->AdvancedSearch->Save(); // resultado
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->tipoprueba->AdvancedSearch->Save(); // tipoprueba
			$this->resultadprueba->AdvancedSearch->Save(); // resultadprueba
			$this->recomendacion->AdvancedSearch->Save(); // recomendacion
			$this->id_tipodiagnosticoaudiologia->AdvancedSearch->Save(); // id_tipodiagnosticoaudiologia
			$this->nombrediagnotico->AdvancedSearch->Save(); // nombrediagnotico
			$this->resultadodiagnostico->AdvancedSearch->Save(); // resultadodiagnostico
			$this->tipotratamiento->AdvancedSearch->Save(); // tipotratamiento
			$this->tipoderivacion->AdvancedSearch->Save(); // tipoderivacion
			$this->nombreespcialidad->AdvancedSearch->Save(); // nombreespcialidad
			$this->observaciones1->AdvancedSearch->Save(); // observaciones1
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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->apellidopaterno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->apellidomaterno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ci, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dias, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->semanas, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->meses, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->discapacidad, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->resultado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->observaciones, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipoprueba, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->resultadprueba, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->recomendacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->resultadodiagnostico, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipotratamiento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipoderivacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nombreespcialidad, $arKeywords, $type);
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
		if ($this->id_neonato->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidopaterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidomaterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ci->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_nacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dias->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->semanas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->meses->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->discapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resultado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipoprueba->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resultadprueba->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->recomendacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_tipodiagnosticoaudiologia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombrediagnotico->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resultadodiagnostico->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipotratamiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipoderivacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombreespcialidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones1->AdvancedSearch->IssetSession())
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
		$this->id_neonato->AdvancedSearch->UnsetSession();
		$this->apellidopaterno->AdvancedSearch->UnsetSession();
		$this->apellidomaterno->AdvancedSearch->UnsetSession();
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->ci->AdvancedSearch->UnsetSession();
		$this->fecha_nacimiento->AdvancedSearch->UnsetSession();
		$this->dias->AdvancedSearch->UnsetSession();
		$this->semanas->AdvancedSearch->UnsetSession();
		$this->meses->AdvancedSearch->UnsetSession();
		$this->discapacidad->AdvancedSearch->UnsetSession();
		$this->resultado->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->tipoprueba->AdvancedSearch->UnsetSession();
		$this->resultadprueba->AdvancedSearch->UnsetSession();
		$this->recomendacion->AdvancedSearch->UnsetSession();
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->UnsetSession();
		$this->nombrediagnotico->AdvancedSearch->UnsetSession();
		$this->resultadodiagnostico->AdvancedSearch->UnsetSession();
		$this->tipotratamiento->AdvancedSearch->UnsetSession();
		$this->tipoderivacion->AdvancedSearch->UnsetSession();
		$this->nombreespcialidad->AdvancedSearch->UnsetSession();
		$this->observaciones1->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id_neonato->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->dias->AdvancedSearch->Load();
		$this->semanas->AdvancedSearch->Load();
		$this->meses->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->resultado->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->tipoprueba->AdvancedSearch->Load();
		$this->resultadprueba->AdvancedSearch->Load();
		$this->recomendacion->AdvancedSearch->Load();
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->Load();
		$this->nombrediagnotico->AdvancedSearch->Load();
		$this->resultadodiagnostico->AdvancedSearch->Load();
		$this->tipotratamiento->AdvancedSearch->Load();
		$this->tipoderivacion->AdvancedSearch->Load();
		$this->nombreespcialidad->AdvancedSearch->Load();
		$this->observaciones1->AdvancedSearch->Load();
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
			$this->UpdateSort($this->id_neonato, $bCtrl); // id_neonato
			$this->UpdateSort($this->apellidopaterno, $bCtrl); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno, $bCtrl); // apellidomaterno
			$this->UpdateSort($this->nombre, $bCtrl); // nombre
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->fecha_nacimiento, $bCtrl); // fecha_nacimiento
			$this->UpdateSort($this->dias, $bCtrl); // dias
			$this->UpdateSort($this->semanas, $bCtrl); // semanas
			$this->UpdateSort($this->meses, $bCtrl); // meses
			$this->UpdateSort($this->discapacidad, $bCtrl); // discapacidad
			$this->UpdateSort($this->resultado, $bCtrl); // resultado
			$this->UpdateSort($this->observaciones, $bCtrl); // observaciones
			$this->UpdateSort($this->tipoprueba, $bCtrl); // tipoprueba
			$this->UpdateSort($this->resultadprueba, $bCtrl); // resultadprueba
			$this->UpdateSort($this->recomendacion, $bCtrl); // recomendacion
			$this->UpdateSort($this->id_tipodiagnosticoaudiologia, $bCtrl); // id_tipodiagnosticoaudiologia
			$this->UpdateSort($this->nombrediagnotico, $bCtrl); // nombrediagnotico
			$this->UpdateSort($this->resultadodiagnostico, $bCtrl); // resultadodiagnostico
			$this->UpdateSort($this->tipotratamiento, $bCtrl); // tipotratamiento
			$this->UpdateSort($this->tipoderivacion, $bCtrl); // tipoderivacion
			$this->UpdateSort($this->nombreespcialidad, $bCtrl); // nombreespcialidad
			$this->UpdateSort($this->observaciones1, $bCtrl); // observaciones1
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
				$this->id_neonato->setSort("");
				$this->apellidopaterno->setSort("");
				$this->apellidomaterno->setSort("");
				$this->nombre->setSort("");
				$this->ci->setSort("");
				$this->fecha_nacimiento->setSort("");
				$this->dias->setSort("");
				$this->semanas->setSort("");
				$this->meses->setSort("");
				$this->discapacidad->setSort("");
				$this->resultado->setSort("");
				$this->observaciones->setSort("");
				$this->tipoprueba->setSort("");
				$this->resultadprueba->setSort("");
				$this->recomendacion->setSort("");
				$this->id_tipodiagnosticoaudiologia->setSort("");
				$this->nombrediagnotico->setSort("");
				$this->resultadodiagnostico->setSort("");
				$this->tipotratamiento->setSort("");
				$this->tipoderivacion->setSort("");
				$this->nombreespcialidad->setSort("");
				$this->observaciones1->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fReport_Neonatallistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fReport_Neonatallistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fReport_Neonatallist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fReport_Neonatallistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"Report_Neonatalsrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"Report_Neonatal\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SearchBtn',url:'Report_Neonatalsrch.php'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fReport_Neonatallistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

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
		// id_neonato

		$this->id_neonato->AdvancedSearch->SearchValue = @$_GET["x_id_neonato"];
		if ($this->id_neonato->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_neonato->AdvancedSearch->SearchOperator = @$_GET["z_id_neonato"];

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

		// ci
		$this->ci->AdvancedSearch->SearchValue = @$_GET["x_ci"];
		if ($this->ci->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->ci->AdvancedSearch->SearchOperator = @$_GET["z_ci"];

		// fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = @$_GET["x_fecha_nacimiento"];
		if ($this->fecha_nacimiento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = @$_GET["z_fecha_nacimiento"];

		// dias
		$this->dias->AdvancedSearch->SearchValue = @$_GET["x_dias"];
		if ($this->dias->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->dias->AdvancedSearch->SearchOperator = @$_GET["z_dias"];

		// semanas
		$this->semanas->AdvancedSearch->SearchValue = @$_GET["x_semanas"];
		if ($this->semanas->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->semanas->AdvancedSearch->SearchOperator = @$_GET["z_semanas"];

		// meses
		$this->meses->AdvancedSearch->SearchValue = @$_GET["x_meses"];
		if ($this->meses->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->meses->AdvancedSearch->SearchOperator = @$_GET["z_meses"];

		// discapacidad
		$this->discapacidad->AdvancedSearch->SearchValue = @$_GET["x_discapacidad"];
		if ($this->discapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->discapacidad->AdvancedSearch->SearchOperator = @$_GET["z_discapacidad"];

		// resultado
		$this->resultado->AdvancedSearch->SearchValue = @$_GET["x_resultado"];
		if ($this->resultado->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resultado->AdvancedSearch->SearchOperator = @$_GET["z_resultado"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$_GET["x_observaciones"];
		if ($this->observaciones->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];

		// tipoprueba
		$this->tipoprueba->AdvancedSearch->SearchValue = @$_GET["x_tipoprueba"];
		if ($this->tipoprueba->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipoprueba->AdvancedSearch->SearchOperator = @$_GET["z_tipoprueba"];

		// resultadprueba
		$this->resultadprueba->AdvancedSearch->SearchValue = @$_GET["x_resultadprueba"];
		if ($this->resultadprueba->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resultadprueba->AdvancedSearch->SearchOperator = @$_GET["z_resultadprueba"];

		// recomendacion
		$this->recomendacion->AdvancedSearch->SearchValue = @$_GET["x_recomendacion"];
		if ($this->recomendacion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->recomendacion->AdvancedSearch->SearchOperator = @$_GET["z_recomendacion"];

		// id_tipodiagnosticoaudiologia
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchValue = @$_GET["x_id_tipodiagnosticoaudiologia"];
		if ($this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchOperator = @$_GET["z_id_tipodiagnosticoaudiologia"];

		// nombrediagnotico
		$this->nombrediagnotico->AdvancedSearch->SearchValue = @$_GET["x_nombrediagnotico"];
		if ($this->nombrediagnotico->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombrediagnotico->AdvancedSearch->SearchOperator = @$_GET["z_nombrediagnotico"];

		// resultadodiagnostico
		$this->resultadodiagnostico->AdvancedSearch->SearchValue = @$_GET["x_resultadodiagnostico"];
		if ($this->resultadodiagnostico->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resultadodiagnostico->AdvancedSearch->SearchOperator = @$_GET["z_resultadodiagnostico"];

		// tipotratamiento
		$this->tipotratamiento->AdvancedSearch->SearchValue = @$_GET["x_tipotratamiento"];
		if ($this->tipotratamiento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipotratamiento->AdvancedSearch->SearchOperator = @$_GET["z_tipotratamiento"];

		// tipoderivacion
		$this->tipoderivacion->AdvancedSearch->SearchValue = @$_GET["x_tipoderivacion"];
		if ($this->tipoderivacion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipoderivacion->AdvancedSearch->SearchOperator = @$_GET["z_tipoderivacion"];

		// nombreespcialidad
		$this->nombreespcialidad->AdvancedSearch->SearchValue = @$_GET["x_nombreespcialidad"];
		if ($this->nombreespcialidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombreespcialidad->AdvancedSearch->SearchOperator = @$_GET["z_nombreespcialidad"];

		// observaciones1
		$this->observaciones1->AdvancedSearch->SearchValue = @$_GET["x_observaciones1"];
		if ($this->observaciones1->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->observaciones1->AdvancedSearch->SearchOperator = @$_GET["z_observaciones1"];

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
		$this->id_neonato->setDbValue($row['id_neonato']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->ci->setDbValue($row['ci']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->dias->setDbValue($row['dias']);
		$this->semanas->setDbValue($row['semanas']);
		$this->meses->setDbValue($row['meses']);
		$this->discapacidad->setDbValue($row['discapacidad']);
		$this->resultado->setDbValue($row['resultado']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->tipoprueba->setDbValue($row['tipoprueba']);
		$this->resultadprueba->setDbValue($row['resultadprueba']);
		$this->recomendacion->setDbValue($row['recomendacion']);
		$this->id_tipodiagnosticoaudiologia->setDbValue($row['id_tipodiagnosticoaudiologia']);
		$this->nombrediagnotico->setDbValue($row['nombrediagnotico']);
		$this->resultadodiagnostico->setDbValue($row['resultadodiagnostico']);
		$this->tipotratamiento->setDbValue($row['tipotratamiento']);
		$this->tipoderivacion->setDbValue($row['tipoderivacion']);
		$this->nombreespcialidad->setDbValue($row['nombreespcialidad']);
		$this->observaciones1->setDbValue($row['observaciones1']);
		$this->fecha->setDbValue($row['fecha']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_neonato'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombre'] = NULL;
		$row['ci'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['dias'] = NULL;
		$row['semanas'] = NULL;
		$row['meses'] = NULL;
		$row['discapacidad'] = NULL;
		$row['resultado'] = NULL;
		$row['observaciones'] = NULL;
		$row['tipoprueba'] = NULL;
		$row['resultadprueba'] = NULL;
		$row['recomendacion'] = NULL;
		$row['id_tipodiagnosticoaudiologia'] = NULL;
		$row['nombrediagnotico'] = NULL;
		$row['resultadodiagnostico'] = NULL;
		$row['tipotratamiento'] = NULL;
		$row['tipoderivacion'] = NULL;
		$row['nombreespcialidad'] = NULL;
		$row['observaciones1'] = NULL;
		$row['fecha'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_neonato->DbValue = $row['id_neonato'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->ci->DbValue = $row['ci'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->dias->DbValue = $row['dias'];
		$this->semanas->DbValue = $row['semanas'];
		$this->meses->DbValue = $row['meses'];
		$this->discapacidad->DbValue = $row['discapacidad'];
		$this->resultado->DbValue = $row['resultado'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->tipoprueba->DbValue = $row['tipoprueba'];
		$this->resultadprueba->DbValue = $row['resultadprueba'];
		$this->recomendacion->DbValue = $row['recomendacion'];
		$this->id_tipodiagnosticoaudiologia->DbValue = $row['id_tipodiagnosticoaudiologia'];
		$this->nombrediagnotico->DbValue = $row['nombrediagnotico'];
		$this->resultadodiagnostico->DbValue = $row['resultadodiagnostico'];
		$this->tipotratamiento->DbValue = $row['tipotratamiento'];
		$this->tipoderivacion->DbValue = $row['tipoderivacion'];
		$this->nombreespcialidad->DbValue = $row['nombreespcialidad'];
		$this->observaciones1->DbValue = $row['observaciones1'];
		$this->fecha->DbValue = $row['fecha'];
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
		// id_neonato
		// apellidopaterno
		// apellidomaterno
		// nombre
		// ci
		// fecha_nacimiento
		// dias
		// semanas
		// meses
		// discapacidad
		// resultado
		// observaciones
		// tipoprueba
		// resultadprueba
		// recomendacion
		// id_tipodiagnosticoaudiologia
		// nombrediagnotico
		// resultadodiagnostico
		// tipotratamiento
		// tipoderivacion
		// nombreespcialidad
		// observaciones1
		// fecha

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// dias
		$this->dias->ViewValue = $this->dias->CurrentValue;
		$this->dias->ViewCustomAttributes = "";

		// semanas
		$this->semanas->ViewValue = $this->semanas->CurrentValue;
		$this->semanas->ViewCustomAttributes = "";

		// meses
		$this->meses->ViewValue = $this->meses->CurrentValue;
		$this->meses->ViewCustomAttributes = "";

		// discapacidad
		$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->ViewCustomAttributes = "";

		// resultado
		$this->resultado->ViewValue = $this->resultado->CurrentValue;
		$this->resultado->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// tipoprueba
		$this->tipoprueba->ViewValue = $this->tipoprueba->CurrentValue;
		$this->tipoprueba->ViewCustomAttributes = "";

		// resultadprueba
		$this->resultadprueba->ViewValue = $this->resultadprueba->CurrentValue;
		$this->resultadprueba->ViewCustomAttributes = "";

		// recomendacion
		$this->recomendacion->ViewValue = $this->recomendacion->CurrentValue;
		$this->recomendacion->ViewCustomAttributes = "";

		// id_tipodiagnosticoaudiologia
		if (strval($this->id_tipodiagnosticoaudiologia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipodiagnosticoaudiologia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiagnosticoaudiologia`";
		$sWhereWrk = "";
		$this->id_tipodiagnosticoaudiologia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipodiagnosticoaudiologia, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipodiagnosticoaudiologia->ViewValue = $this->id_tipodiagnosticoaudiologia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipodiagnosticoaudiologia->ViewValue = $this->id_tipodiagnosticoaudiologia->CurrentValue;
			}
		} else {
			$this->id_tipodiagnosticoaudiologia->ViewValue = NULL;
		}
		$this->id_tipodiagnosticoaudiologia->ViewCustomAttributes = "";

		// nombrediagnotico
		$this->nombrediagnotico->ViewValue = $this->nombrediagnotico->CurrentValue;
		$this->nombrediagnotico->ViewCustomAttributes = "";

		// resultadodiagnostico
		$this->resultadodiagnostico->ViewValue = $this->resultadodiagnostico->CurrentValue;
		$this->resultadodiagnostico->ViewCustomAttributes = "";

		// tipotratamiento
		$this->tipotratamiento->ViewValue = $this->tipotratamiento->CurrentValue;
		$this->tipotratamiento->ViewCustomAttributes = "";

		// tipoderivacion
		if (strval($this->tipoderivacion->CurrentValue) <> "") {
			$this->tipoderivacion->ViewValue = $this->tipoderivacion->OptionCaption($this->tipoderivacion->CurrentValue);
		} else {
			$this->tipoderivacion->ViewValue = NULL;
		}
		$this->tipoderivacion->ViewCustomAttributes = "";

		// nombreespcialidad
		$this->nombreespcialidad->ViewValue = $this->nombreespcialidad->CurrentValue;
		$this->nombreespcialidad->ViewCustomAttributes = "";

		// observaciones1
		$this->observaciones1->ViewValue = $this->observaciones1->CurrentValue;
		$this->observaciones1->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

			// id_neonato
			$this->id_neonato->LinkCustomAttributes = "";
			$this->id_neonato->HrefValue = "";
			$this->id_neonato->TooltipValue = "";
			if ($this->Export == "")
				$this->id_neonato->ViewValue = $this->HighlightValue($this->id_neonato);

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";
			$this->apellidopaterno->TooltipValue = "";
			if ($this->Export == "")
				$this->apellidopaterno->ViewValue = $this->HighlightValue($this->apellidopaterno);

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";
			$this->apellidomaterno->TooltipValue = "";
			if ($this->Export == "")
				$this->apellidomaterno->ViewValue = $this->HighlightValue($this->apellidomaterno);

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";
			if ($this->Export == "")
				$this->nombre->ViewValue = $this->HighlightValue($this->nombre);

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";
			if ($this->Export == "")
				$this->ci->ViewValue = $this->HighlightValue($this->ci);

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// dias
			$this->dias->LinkCustomAttributes = "";
			$this->dias->HrefValue = "";
			$this->dias->TooltipValue = "";
			if ($this->Export == "")
				$this->dias->ViewValue = $this->HighlightValue($this->dias);

			// semanas
			$this->semanas->LinkCustomAttributes = "";
			$this->semanas->HrefValue = "";
			$this->semanas->TooltipValue = "";
			if ($this->Export == "")
				$this->semanas->ViewValue = $this->HighlightValue($this->semanas);

			// meses
			$this->meses->LinkCustomAttributes = "";
			$this->meses->HrefValue = "";
			$this->meses->TooltipValue = "";
			if ($this->Export == "")
				$this->meses->ViewValue = $this->HighlightValue($this->meses);

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";
			$this->discapacidad->TooltipValue = "";
			if ($this->Export == "")
				$this->discapacidad->ViewValue = $this->HighlightValue($this->discapacidad);

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";
			$this->resultado->TooltipValue = "";
			if ($this->Export == "")
				$this->resultado->ViewValue = $this->HighlightValue($this->resultado);

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";
			if ($this->Export == "")
				$this->observaciones->ViewValue = $this->HighlightValue($this->observaciones);

			// tipoprueba
			$this->tipoprueba->LinkCustomAttributes = "";
			$this->tipoprueba->HrefValue = "";
			$this->tipoprueba->TooltipValue = "";
			if ($this->Export == "")
				$this->tipoprueba->ViewValue = $this->HighlightValue($this->tipoprueba);

			// resultadprueba
			$this->resultadprueba->LinkCustomAttributes = "";
			$this->resultadprueba->HrefValue = "";
			$this->resultadprueba->TooltipValue = "";
			if ($this->Export == "")
				$this->resultadprueba->ViewValue = $this->HighlightValue($this->resultadprueba);

			// recomendacion
			$this->recomendacion->LinkCustomAttributes = "";
			$this->recomendacion->HrefValue = "";
			$this->recomendacion->TooltipValue = "";
			if ($this->Export == "")
				$this->recomendacion->ViewValue = $this->HighlightValue($this->recomendacion);

			// id_tipodiagnosticoaudiologia
			$this->id_tipodiagnosticoaudiologia->LinkCustomAttributes = "";
			$this->id_tipodiagnosticoaudiologia->HrefValue = "";
			$this->id_tipodiagnosticoaudiologia->TooltipValue = "";

			// nombrediagnotico
			$this->nombrediagnotico->LinkCustomAttributes = "";
			$this->nombrediagnotico->HrefValue = "";
			$this->nombrediagnotico->TooltipValue = "";
			if ($this->Export == "")
				$this->nombrediagnotico->ViewValue = $this->HighlightValue($this->nombrediagnotico);

			// resultadodiagnostico
			$this->resultadodiagnostico->LinkCustomAttributes = "";
			$this->resultadodiagnostico->HrefValue = "";
			$this->resultadodiagnostico->TooltipValue = "";
			if ($this->Export == "")
				$this->resultadodiagnostico->ViewValue = $this->HighlightValue($this->resultadodiagnostico);

			// tipotratamiento
			$this->tipotratamiento->LinkCustomAttributes = "";
			$this->tipotratamiento->HrefValue = "";
			$this->tipotratamiento->TooltipValue = "";
			if ($this->Export == "")
				$this->tipotratamiento->ViewValue = $this->HighlightValue($this->tipotratamiento);

			// tipoderivacion
			$this->tipoderivacion->LinkCustomAttributes = "";
			$this->tipoderivacion->HrefValue = "";
			$this->tipoderivacion->TooltipValue = "";

			// nombreespcialidad
			$this->nombreespcialidad->LinkCustomAttributes = "";
			$this->nombreespcialidad->HrefValue = "";
			$this->nombreespcialidad->TooltipValue = "";
			if ($this->Export == "")
				$this->nombreespcialidad->ViewValue = $this->HighlightValue($this->nombreespcialidad);

			// observaciones1
			$this->observaciones1->LinkCustomAttributes = "";
			$this->observaciones1->HrefValue = "";
			$this->observaciones1->TooltipValue = "";
			if ($this->Export == "")
				$this->observaciones1->ViewValue = $this->HighlightValue($this->observaciones1);

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";
		}

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
		$this->id_neonato->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->dias->AdvancedSearch->Load();
		$this->semanas->AdvancedSearch->Load();
		$this->meses->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->resultado->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->tipoprueba->AdvancedSearch->Load();
		$this->resultadprueba->AdvancedSearch->Load();
		$this->recomendacion->AdvancedSearch->Load();
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->Load();
		$this->nombrediagnotico->AdvancedSearch->Load();
		$this->resultadodiagnostico->AdvancedSearch->Load();
		$this->tipotratamiento->AdvancedSearch->Load();
		$this->tipoderivacion->AdvancedSearch->Load();
		$this->nombreespcialidad->AdvancedSearch->Load();
		$this->observaciones1->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_Report_Neonatal\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_Report_Neonatal',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fReport_Neonatallist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetupStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
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
if (!isset($Report_Neonatal_list)) $Report_Neonatal_list = new cReport_Neonatal_list();

// Page init
$Report_Neonatal_list->Page_Init();

// Page main
$Report_Neonatal_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Report_Neonatal_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Report_Neonatal->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fReport_Neonatallist = new ew_Form("fReport_Neonatallist", "list");
fReport_Neonatallist.FormKeyCountName = '<?php echo $Report_Neonatal_list->FormKeyCountName ?>';

// Form_CustomValidate event
fReport_Neonatallist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fReport_Neonatallist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fReport_Neonatallist.Lists["x_id_neonato"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"neonatal"};
fReport_Neonatallist.Lists["x_id_neonato"].Data = "<?php echo $Report_Neonatal_list->id_neonato->LookupFilterQuery(FALSE, "list") ?>";
fReport_Neonatallist.AutoSuggests["x_id_neonato"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $Report_Neonatal_list->id_neonato->LookupFilterQuery(TRUE, "list"))) ?>;
fReport_Neonatallist.Lists["x_id_tipodiagnosticoaudiologia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiagnosticoaudiologia"};
fReport_Neonatallist.Lists["x_id_tipodiagnosticoaudiologia"].Data = "<?php echo $Report_Neonatal_list->id_tipodiagnosticoaudiologia->LookupFilterQuery(FALSE, "list") ?>";
fReport_Neonatallist.Lists["x_tipoderivacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fReport_Neonatallist.Lists["x_tipoderivacion"].Options = <?php echo json_encode($Report_Neonatal_list->tipoderivacion->Options()) ?>;

// Form object for search
var CurrentSearchForm = fReport_Neonatallistsrch = new ew_Form("fReport_Neonatallistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Report_Neonatal->Export == "") { ?>
<div class="ewToolbar">
<?php if ($Report_Neonatal_list->TotalRecs > 0 && $Report_Neonatal_list->ExportOptions->Visible()) { ?>
<?php $Report_Neonatal_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($Report_Neonatal_list->SearchOptions->Visible()) { ?>
<?php $Report_Neonatal_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($Report_Neonatal_list->FilterOptions->Visible()) { ?>
<?php $Report_Neonatal_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $Report_Neonatal_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($Report_Neonatal_list->TotalRecs <= 0)
			$Report_Neonatal_list->TotalRecs = $Report_Neonatal->ListRecordCount();
	} else {
		if (!$Report_Neonatal_list->Recordset && ($Report_Neonatal_list->Recordset = $Report_Neonatal_list->LoadRecordset()))
			$Report_Neonatal_list->TotalRecs = $Report_Neonatal_list->Recordset->RecordCount();
	}
	$Report_Neonatal_list->StartRec = 1;
	if ($Report_Neonatal_list->DisplayRecs <= 0 || ($Report_Neonatal->Export <> "" && $Report_Neonatal->ExportAll)) // Display all records
		$Report_Neonatal_list->DisplayRecs = $Report_Neonatal_list->TotalRecs;
	if (!($Report_Neonatal->Export <> "" && $Report_Neonatal->ExportAll))
		$Report_Neonatal_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$Report_Neonatal_list->Recordset = $Report_Neonatal_list->LoadRecordset($Report_Neonatal_list->StartRec-1, $Report_Neonatal_list->DisplayRecs);

	// Set no record found message
	if ($Report_Neonatal->CurrentAction == "" && $Report_Neonatal_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$Report_Neonatal_list->setWarningMessage(ew_DeniedMsg());
		if ($Report_Neonatal_list->SearchWhere == "0=101")
			$Report_Neonatal_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$Report_Neonatal_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$Report_Neonatal_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($Report_Neonatal->Export == "" && $Report_Neonatal->CurrentAction == "") { ?>
<form name="fReport_Neonatallistsrch" id="fReport_Neonatallistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($Report_Neonatal_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fReport_Neonatallistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="Report_Neonatal">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($Report_Neonatal_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($Report_Neonatal_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $Report_Neonatal_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($Report_Neonatal_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($Report_Neonatal_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($Report_Neonatal_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($Report_Neonatal_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $Report_Neonatal_list->ShowPageHeader(); ?>
<?php
$Report_Neonatal_list->ShowMessage();
?>
<?php if ($Report_Neonatal_list->TotalRecs > 0 || $Report_Neonatal->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($Report_Neonatal_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> Report_Neonatal">
<?php if ($Report_Neonatal->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($Report_Neonatal->CurrentAction <> "gridadd" && $Report_Neonatal->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($Report_Neonatal_list->Pager)) $Report_Neonatal_list->Pager = new cPrevNextPager($Report_Neonatal_list->StartRec, $Report_Neonatal_list->DisplayRecs, $Report_Neonatal_list->TotalRecs, $Report_Neonatal_list->AutoHidePager) ?>
<?php if ($Report_Neonatal_list->Pager->RecordCount > 0 && $Report_Neonatal_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($Report_Neonatal_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $Report_Neonatal_list->PageUrl() ?>start=<?php echo $Report_Neonatal_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($Report_Neonatal_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $Report_Neonatal_list->PageUrl() ?>start=<?php echo $Report_Neonatal_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $Report_Neonatal_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($Report_Neonatal_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $Report_Neonatal_list->PageUrl() ?>start=<?php echo $Report_Neonatal_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($Report_Neonatal_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $Report_Neonatal_list->PageUrl() ?>start=<?php echo $Report_Neonatal_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $Report_Neonatal_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($Report_Neonatal_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $Report_Neonatal_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $Report_Neonatal_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $Report_Neonatal_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($Report_Neonatal_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fReport_Neonatallist" id="fReport_Neonatallist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($Report_Neonatal_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $Report_Neonatal_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="Report_Neonatal">
<div id="gmp_Report_Neonatal" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($Report_Neonatal_list->TotalRecs > 0 || $Report_Neonatal->CurrentAction == "gridedit") { ?>
<table id="tbl_Report_Neonatallist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$Report_Neonatal_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$Report_Neonatal_list->RenderListOptions();

// Render list options (header, left)
$Report_Neonatal_list->ListOptions->Render("header", "left");
?>
<?php if ($Report_Neonatal->id_neonato->Visible) { // id_neonato ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->id_neonato) == "") { ?>
		<th data-name="id_neonato" class="<?php echo $Report_Neonatal->id_neonato->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_id_neonato" class="Report_Neonatal_id_neonato"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->id_neonato->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_neonato" class="<?php echo $Report_Neonatal->id_neonato->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->id_neonato) ?>',2);"><div id="elh_Report_Neonatal_id_neonato" class="Report_Neonatal_id_neonato">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->id_neonato->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->id_neonato->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->id_neonato->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->apellidopaterno->Visible) { // apellidopaterno ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->apellidopaterno) == "") { ?>
		<th data-name="apellidopaterno" class="<?php echo $Report_Neonatal->apellidopaterno->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_apellidopaterno" class="Report_Neonatal_apellidopaterno"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->apellidopaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidopaterno" class="<?php echo $Report_Neonatal->apellidopaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->apellidopaterno) ?>',2);"><div id="elh_Report_Neonatal_apellidopaterno" class="Report_Neonatal_apellidopaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->apellidopaterno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->apellidomaterno->Visible) { // apellidomaterno ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->apellidomaterno) == "") { ?>
		<th data-name="apellidomaterno" class="<?php echo $Report_Neonatal->apellidomaterno->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_apellidomaterno" class="Report_Neonatal_apellidomaterno"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->apellidomaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidomaterno" class="<?php echo $Report_Neonatal->apellidomaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->apellidomaterno) ?>',2);"><div id="elh_Report_Neonatal_apellidomaterno" class="Report_Neonatal_apellidomaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->apellidomaterno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->nombre->Visible) { // nombre ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->nombre) == "") { ?>
		<th data-name="nombre" class="<?php echo $Report_Neonatal->nombre->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_nombre" class="Report_Neonatal_nombre"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre" class="<?php echo $Report_Neonatal->nombre->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->nombre) ?>',2);"><div id="elh_Report_Neonatal_nombre" class="Report_Neonatal_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->ci->Visible) { // ci ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->ci) == "") { ?>
		<th data-name="ci" class="<?php echo $Report_Neonatal->ci->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_ci" class="Report_Neonatal_ci"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->ci->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ci" class="<?php echo $Report_Neonatal->ci->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->ci) ?>',2);"><div id="elh_Report_Neonatal_ci" class="Report_Neonatal_ci">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->ci->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->fecha_nacimiento) == "") { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $Report_Neonatal->fecha_nacimiento->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_fecha_nacimiento" class="Report_Neonatal_fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $Report_Neonatal->fecha_nacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->fecha_nacimiento) ?>',2);"><div id="elh_Report_Neonatal_fecha_nacimiento" class="Report_Neonatal_fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->dias->Visible) { // dias ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->dias) == "") { ?>
		<th data-name="dias" class="<?php echo $Report_Neonatal->dias->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_dias" class="Report_Neonatal_dias"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->dias->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dias" class="<?php echo $Report_Neonatal->dias->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->dias) ?>',2);"><div id="elh_Report_Neonatal_dias" class="Report_Neonatal_dias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->dias->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->dias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->dias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->semanas->Visible) { // semanas ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->semanas) == "") { ?>
		<th data-name="semanas" class="<?php echo $Report_Neonatal->semanas->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_semanas" class="Report_Neonatal_semanas"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->semanas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="semanas" class="<?php echo $Report_Neonatal->semanas->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->semanas) ?>',2);"><div id="elh_Report_Neonatal_semanas" class="Report_Neonatal_semanas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->semanas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->semanas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->semanas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->meses->Visible) { // meses ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->meses) == "") { ?>
		<th data-name="meses" class="<?php echo $Report_Neonatal->meses->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_meses" class="Report_Neonatal_meses"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->meses->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="meses" class="<?php echo $Report_Neonatal->meses->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->meses) ?>',2);"><div id="elh_Report_Neonatal_meses" class="Report_Neonatal_meses">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->meses->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->meses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->meses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->discapacidad->Visible) { // discapacidad ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->discapacidad) == "") { ?>
		<th data-name="discapacidad" class="<?php echo $Report_Neonatal->discapacidad->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_discapacidad" class="Report_Neonatal_discapacidad"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->discapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="discapacidad" class="<?php echo $Report_Neonatal->discapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->discapacidad) ?>',2);"><div id="elh_Report_Neonatal_discapacidad" class="Report_Neonatal_discapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->discapacidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->resultado->Visible) { // resultado ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->resultado) == "") { ?>
		<th data-name="resultado" class="<?php echo $Report_Neonatal->resultado->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_resultado" class="Report_Neonatal_resultado"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultado" class="<?php echo $Report_Neonatal->resultado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->resultado) ?>',2);"><div id="elh_Report_Neonatal_resultado" class="Report_Neonatal_resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->resultado->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->observaciones->Visible) { // observaciones ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->observaciones) == "") { ?>
		<th data-name="observaciones" class="<?php echo $Report_Neonatal->observaciones->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_observaciones" class="Report_Neonatal_observaciones"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones" class="<?php echo $Report_Neonatal->observaciones->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->observaciones) ?>',2);"><div id="elh_Report_Neonatal_observaciones" class="Report_Neonatal_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->observaciones->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->tipoprueba->Visible) { // tipoprueba ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->tipoprueba) == "") { ?>
		<th data-name="tipoprueba" class="<?php echo $Report_Neonatal->tipoprueba->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_tipoprueba" class="Report_Neonatal_tipoprueba"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->tipoprueba->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoprueba" class="<?php echo $Report_Neonatal->tipoprueba->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->tipoprueba) ?>',2);"><div id="elh_Report_Neonatal_tipoprueba" class="Report_Neonatal_tipoprueba">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->tipoprueba->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->tipoprueba->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->tipoprueba->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->resultadprueba->Visible) { // resultadprueba ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->resultadprueba) == "") { ?>
		<th data-name="resultadprueba" class="<?php echo $Report_Neonatal->resultadprueba->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_resultadprueba" class="Report_Neonatal_resultadprueba"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->resultadprueba->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultadprueba" class="<?php echo $Report_Neonatal->resultadprueba->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->resultadprueba) ?>',2);"><div id="elh_Report_Neonatal_resultadprueba" class="Report_Neonatal_resultadprueba">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->resultadprueba->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->resultadprueba->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->resultadprueba->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->recomendacion->Visible) { // recomendacion ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->recomendacion) == "") { ?>
		<th data-name="recomendacion" class="<?php echo $Report_Neonatal->recomendacion->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_recomendacion" class="Report_Neonatal_recomendacion"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->recomendacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="recomendacion" class="<?php echo $Report_Neonatal->recomendacion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->recomendacion) ?>',2);"><div id="elh_Report_Neonatal_recomendacion" class="Report_Neonatal_recomendacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->recomendacion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->recomendacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->recomendacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->id_tipodiagnosticoaudiologia->Visible) { // id_tipodiagnosticoaudiologia ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->id_tipodiagnosticoaudiologia) == "") { ?>
		<th data-name="id_tipodiagnosticoaudiologia" class="<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_id_tipodiagnosticoaudiologia" class="Report_Neonatal_id_tipodiagnosticoaudiologia"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipodiagnosticoaudiologia" class="<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->id_tipodiagnosticoaudiologia) ?>',2);"><div id="elh_Report_Neonatal_id_tipodiagnosticoaudiologia" class="Report_Neonatal_id_tipodiagnosticoaudiologia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->id_tipodiagnosticoaudiologia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->id_tipodiagnosticoaudiologia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->nombrediagnotico->Visible) { // nombrediagnotico ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->nombrediagnotico) == "") { ?>
		<th data-name="nombrediagnotico" class="<?php echo $Report_Neonatal->nombrediagnotico->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_nombrediagnotico" class="Report_Neonatal_nombrediagnotico"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->nombrediagnotico->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombrediagnotico" class="<?php echo $Report_Neonatal->nombrediagnotico->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->nombrediagnotico) ?>',2);"><div id="elh_Report_Neonatal_nombrediagnotico" class="Report_Neonatal_nombrediagnotico">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->nombrediagnotico->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->nombrediagnotico->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->nombrediagnotico->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->resultadodiagnostico->Visible) { // resultadodiagnostico ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->resultadodiagnostico) == "") { ?>
		<th data-name="resultadodiagnostico" class="<?php echo $Report_Neonatal->resultadodiagnostico->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_resultadodiagnostico" class="Report_Neonatal_resultadodiagnostico"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->resultadodiagnostico->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultadodiagnostico" class="<?php echo $Report_Neonatal->resultadodiagnostico->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->resultadodiagnostico) ?>',2);"><div id="elh_Report_Neonatal_resultadodiagnostico" class="Report_Neonatal_resultadodiagnostico">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->resultadodiagnostico->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->resultadodiagnostico->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->resultadodiagnostico->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->tipotratamiento->Visible) { // tipotratamiento ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->tipotratamiento) == "") { ?>
		<th data-name="tipotratamiento" class="<?php echo $Report_Neonatal->tipotratamiento->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_tipotratamiento" class="Report_Neonatal_tipotratamiento"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->tipotratamiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipotratamiento" class="<?php echo $Report_Neonatal->tipotratamiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->tipotratamiento) ?>',2);"><div id="elh_Report_Neonatal_tipotratamiento" class="Report_Neonatal_tipotratamiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->tipotratamiento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->tipotratamiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->tipotratamiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->tipoderivacion->Visible) { // tipoderivacion ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->tipoderivacion) == "") { ?>
		<th data-name="tipoderivacion" class="<?php echo $Report_Neonatal->tipoderivacion->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_tipoderivacion" class="Report_Neonatal_tipoderivacion"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->tipoderivacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoderivacion" class="<?php echo $Report_Neonatal->tipoderivacion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->tipoderivacion) ?>',2);"><div id="elh_Report_Neonatal_tipoderivacion" class="Report_Neonatal_tipoderivacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->tipoderivacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->tipoderivacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->tipoderivacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->nombreespcialidad->Visible) { // nombreespcialidad ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->nombreespcialidad) == "") { ?>
		<th data-name="nombreespcialidad" class="<?php echo $Report_Neonatal->nombreespcialidad->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_nombreespcialidad" class="Report_Neonatal_nombreespcialidad"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->nombreespcialidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombreespcialidad" class="<?php echo $Report_Neonatal->nombreespcialidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->nombreespcialidad) ?>',2);"><div id="elh_Report_Neonatal_nombreespcialidad" class="Report_Neonatal_nombreespcialidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->nombreespcialidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->nombreespcialidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->nombreespcialidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->observaciones1->Visible) { // observaciones1 ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->observaciones1) == "") { ?>
		<th data-name="observaciones1" class="<?php echo $Report_Neonatal->observaciones1->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_observaciones1" class="Report_Neonatal_observaciones1"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->observaciones1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones1" class="<?php echo $Report_Neonatal->observaciones1->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->observaciones1) ?>',2);"><div id="elh_Report_Neonatal_observaciones1" class="Report_Neonatal_observaciones1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->observaciones1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->observaciones1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->observaciones1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($Report_Neonatal->fecha->Visible) { // fecha ?>
	<?php if ($Report_Neonatal->SortUrl($Report_Neonatal->fecha) == "") { ?>
		<th data-name="fecha" class="<?php echo $Report_Neonatal->fecha->HeaderCellClass() ?>"><div id="elh_Report_Neonatal_fecha" class="Report_Neonatal_fecha"><div class="ewTableHeaderCaption"><?php echo $Report_Neonatal->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha" class="<?php echo $Report_Neonatal->fecha->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Report_Neonatal->SortUrl($Report_Neonatal->fecha) ?>',2);"><div id="elh_Report_Neonatal_fecha" class="Report_Neonatal_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Report_Neonatal->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Report_Neonatal->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Report_Neonatal->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$Report_Neonatal_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($Report_Neonatal->ExportAll && $Report_Neonatal->Export <> "") {
	$Report_Neonatal_list->StopRec = $Report_Neonatal_list->TotalRecs;
} else {

	// Set the last record to display
	if ($Report_Neonatal_list->TotalRecs > $Report_Neonatal_list->StartRec + $Report_Neonatal_list->DisplayRecs - 1)
		$Report_Neonatal_list->StopRec = $Report_Neonatal_list->StartRec + $Report_Neonatal_list->DisplayRecs - 1;
	else
		$Report_Neonatal_list->StopRec = $Report_Neonatal_list->TotalRecs;
}
$Report_Neonatal_list->RecCnt = $Report_Neonatal_list->StartRec - 1;
if ($Report_Neonatal_list->Recordset && !$Report_Neonatal_list->Recordset->EOF) {
	$Report_Neonatal_list->Recordset->MoveFirst();
	$bSelectLimit = $Report_Neonatal_list->UseSelectLimit;
	if (!$bSelectLimit && $Report_Neonatal_list->StartRec > 1)
		$Report_Neonatal_list->Recordset->Move($Report_Neonatal_list->StartRec - 1);
} elseif (!$Report_Neonatal->AllowAddDeleteRow && $Report_Neonatal_list->StopRec == 0) {
	$Report_Neonatal_list->StopRec = $Report_Neonatal->GridAddRowCount;
}

// Initialize aggregate
$Report_Neonatal->RowType = EW_ROWTYPE_AGGREGATEINIT;
$Report_Neonatal->ResetAttrs();
$Report_Neonatal_list->RenderRow();
while ($Report_Neonatal_list->RecCnt < $Report_Neonatal_list->StopRec) {
	$Report_Neonatal_list->RecCnt++;
	if (intval($Report_Neonatal_list->RecCnt) >= intval($Report_Neonatal_list->StartRec)) {
		$Report_Neonatal_list->RowCnt++;

		// Set up key count
		$Report_Neonatal_list->KeyCount = $Report_Neonatal_list->RowIndex;

		// Init row class and style
		$Report_Neonatal->ResetAttrs();
		$Report_Neonatal->CssClass = "";
		if ($Report_Neonatal->CurrentAction == "gridadd") {
		} else {
			$Report_Neonatal_list->LoadRowValues($Report_Neonatal_list->Recordset); // Load row values
		}
		$Report_Neonatal->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$Report_Neonatal->RowAttrs = array_merge($Report_Neonatal->RowAttrs, array('data-rowindex'=>$Report_Neonatal_list->RowCnt, 'id'=>'r' . $Report_Neonatal_list->RowCnt . '_Report_Neonatal', 'data-rowtype'=>$Report_Neonatal->RowType));

		// Render row
		$Report_Neonatal_list->RenderRow();

		// Render list options
		$Report_Neonatal_list->RenderListOptions();
?>
	<tr<?php echo $Report_Neonatal->RowAttributes() ?>>
<?php

// Render list options (body, left)
$Report_Neonatal_list->ListOptions->Render("body", "left", $Report_Neonatal_list->RowCnt);
?>
	<?php if ($Report_Neonatal->id_neonato->Visible) { // id_neonato ?>
		<td data-name="id_neonato"<?php echo $Report_Neonatal->id_neonato->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_id_neonato" class="Report_Neonatal_id_neonato">
<span<?php echo $Report_Neonatal->id_neonato->ViewAttributes() ?>>
<?php echo $Report_Neonatal->id_neonato->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->apellidopaterno->Visible) { // apellidopaterno ?>
		<td data-name="apellidopaterno"<?php echo $Report_Neonatal->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_apellidopaterno" class="Report_Neonatal_apellidopaterno">
<span<?php echo $Report_Neonatal->apellidopaterno->ViewAttributes() ?>>
<?php echo $Report_Neonatal->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->apellidomaterno->Visible) { // apellidomaterno ?>
		<td data-name="apellidomaterno"<?php echo $Report_Neonatal->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_apellidomaterno" class="Report_Neonatal_apellidomaterno">
<span<?php echo $Report_Neonatal->apellidomaterno->ViewAttributes() ?>>
<?php echo $Report_Neonatal->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $Report_Neonatal->nombre->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_nombre" class="Report_Neonatal_nombre">
<span<?php echo $Report_Neonatal->nombre->ViewAttributes() ?>>
<?php echo $Report_Neonatal->nombre->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->ci->Visible) { // ci ?>
		<td data-name="ci"<?php echo $Report_Neonatal->ci->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_ci" class="Report_Neonatal_ci">
<span<?php echo $Report_Neonatal->ci->ViewAttributes() ?>>
<?php echo $Report_Neonatal->ci->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento"<?php echo $Report_Neonatal->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_fecha_nacimiento" class="Report_Neonatal_fecha_nacimiento">
<span<?php echo $Report_Neonatal->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $Report_Neonatal->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->dias->Visible) { // dias ?>
		<td data-name="dias"<?php echo $Report_Neonatal->dias->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_dias" class="Report_Neonatal_dias">
<span<?php echo $Report_Neonatal->dias->ViewAttributes() ?>>
<?php echo $Report_Neonatal->dias->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->semanas->Visible) { // semanas ?>
		<td data-name="semanas"<?php echo $Report_Neonatal->semanas->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_semanas" class="Report_Neonatal_semanas">
<span<?php echo $Report_Neonatal->semanas->ViewAttributes() ?>>
<?php echo $Report_Neonatal->semanas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->meses->Visible) { // meses ?>
		<td data-name="meses"<?php echo $Report_Neonatal->meses->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_meses" class="Report_Neonatal_meses">
<span<?php echo $Report_Neonatal->meses->ViewAttributes() ?>>
<?php echo $Report_Neonatal->meses->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->discapacidad->Visible) { // discapacidad ?>
		<td data-name="discapacidad"<?php echo $Report_Neonatal->discapacidad->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_discapacidad" class="Report_Neonatal_discapacidad">
<span<?php echo $Report_Neonatal->discapacidad->ViewAttributes() ?>>
<?php echo $Report_Neonatal->discapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->resultado->Visible) { // resultado ?>
		<td data-name="resultado"<?php echo $Report_Neonatal->resultado->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_resultado" class="Report_Neonatal_resultado">
<span<?php echo $Report_Neonatal->resultado->ViewAttributes() ?>>
<?php echo $Report_Neonatal->resultado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $Report_Neonatal->observaciones->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_observaciones" class="Report_Neonatal_observaciones">
<span<?php echo $Report_Neonatal->observaciones->ViewAttributes() ?>>
<?php echo $Report_Neonatal->observaciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->tipoprueba->Visible) { // tipoprueba ?>
		<td data-name="tipoprueba"<?php echo $Report_Neonatal->tipoprueba->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_tipoprueba" class="Report_Neonatal_tipoprueba">
<span<?php echo $Report_Neonatal->tipoprueba->ViewAttributes() ?>>
<?php echo $Report_Neonatal->tipoprueba->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->resultadprueba->Visible) { // resultadprueba ?>
		<td data-name="resultadprueba"<?php echo $Report_Neonatal->resultadprueba->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_resultadprueba" class="Report_Neonatal_resultadprueba">
<span<?php echo $Report_Neonatal->resultadprueba->ViewAttributes() ?>>
<?php echo $Report_Neonatal->resultadprueba->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->recomendacion->Visible) { // recomendacion ?>
		<td data-name="recomendacion"<?php echo $Report_Neonatal->recomendacion->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_recomendacion" class="Report_Neonatal_recomendacion">
<span<?php echo $Report_Neonatal->recomendacion->ViewAttributes() ?>>
<?php echo $Report_Neonatal->recomendacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->id_tipodiagnosticoaudiologia->Visible) { // id_tipodiagnosticoaudiologia ?>
		<td data-name="id_tipodiagnosticoaudiologia"<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_id_tipodiagnosticoaudiologia" class="Report_Neonatal_id_tipodiagnosticoaudiologia">
<span<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->ViewAttributes() ?>>
<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->nombrediagnotico->Visible) { // nombrediagnotico ?>
		<td data-name="nombrediagnotico"<?php echo $Report_Neonatal->nombrediagnotico->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_nombrediagnotico" class="Report_Neonatal_nombrediagnotico">
<span<?php echo $Report_Neonatal->nombrediagnotico->ViewAttributes() ?>>
<?php echo $Report_Neonatal->nombrediagnotico->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->resultadodiagnostico->Visible) { // resultadodiagnostico ?>
		<td data-name="resultadodiagnostico"<?php echo $Report_Neonatal->resultadodiagnostico->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_resultadodiagnostico" class="Report_Neonatal_resultadodiagnostico">
<span<?php echo $Report_Neonatal->resultadodiagnostico->ViewAttributes() ?>>
<?php echo $Report_Neonatal->resultadodiagnostico->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->tipotratamiento->Visible) { // tipotratamiento ?>
		<td data-name="tipotratamiento"<?php echo $Report_Neonatal->tipotratamiento->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_tipotratamiento" class="Report_Neonatal_tipotratamiento">
<span<?php echo $Report_Neonatal->tipotratamiento->ViewAttributes() ?>>
<?php echo $Report_Neonatal->tipotratamiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->tipoderivacion->Visible) { // tipoderivacion ?>
		<td data-name="tipoderivacion"<?php echo $Report_Neonatal->tipoderivacion->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_tipoderivacion" class="Report_Neonatal_tipoderivacion">
<span<?php echo $Report_Neonatal->tipoderivacion->ViewAttributes() ?>>
<?php echo $Report_Neonatal->tipoderivacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->nombreespcialidad->Visible) { // nombreespcialidad ?>
		<td data-name="nombreespcialidad"<?php echo $Report_Neonatal->nombreespcialidad->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_nombreespcialidad" class="Report_Neonatal_nombreespcialidad">
<span<?php echo $Report_Neonatal->nombreespcialidad->ViewAttributes() ?>>
<?php echo $Report_Neonatal->nombreespcialidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->observaciones1->Visible) { // observaciones1 ?>
		<td data-name="observaciones1"<?php echo $Report_Neonatal->observaciones1->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_observaciones1" class="Report_Neonatal_observaciones1">
<span<?php echo $Report_Neonatal->observaciones1->ViewAttributes() ?>>
<?php echo $Report_Neonatal->observaciones1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($Report_Neonatal->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $Report_Neonatal->fecha->CellAttributes() ?>>
<span id="el<?php echo $Report_Neonatal_list->RowCnt ?>_Report_Neonatal_fecha" class="Report_Neonatal_fecha">
<span<?php echo $Report_Neonatal->fecha->ViewAttributes() ?>>
<?php echo $Report_Neonatal->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$Report_Neonatal_list->ListOptions->Render("body", "right", $Report_Neonatal_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($Report_Neonatal->CurrentAction <> "gridadd")
		$Report_Neonatal_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($Report_Neonatal->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($Report_Neonatal_list->Recordset)
	$Report_Neonatal_list->Recordset->Close();
?>
<?php if ($Report_Neonatal->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($Report_Neonatal->CurrentAction <> "gridadd" && $Report_Neonatal->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($Report_Neonatal_list->Pager)) $Report_Neonatal_list->Pager = new cPrevNextPager($Report_Neonatal_list->StartRec, $Report_Neonatal_list->DisplayRecs, $Report_Neonatal_list->TotalRecs, $Report_Neonatal_list->AutoHidePager) ?>
<?php if ($Report_Neonatal_list->Pager->RecordCount > 0 && $Report_Neonatal_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($Report_Neonatal_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $Report_Neonatal_list->PageUrl() ?>start=<?php echo $Report_Neonatal_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($Report_Neonatal_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $Report_Neonatal_list->PageUrl() ?>start=<?php echo $Report_Neonatal_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $Report_Neonatal_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($Report_Neonatal_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $Report_Neonatal_list->PageUrl() ?>start=<?php echo $Report_Neonatal_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($Report_Neonatal_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $Report_Neonatal_list->PageUrl() ?>start=<?php echo $Report_Neonatal_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $Report_Neonatal_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($Report_Neonatal_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $Report_Neonatal_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $Report_Neonatal_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $Report_Neonatal_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($Report_Neonatal_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($Report_Neonatal_list->TotalRecs == 0 && $Report_Neonatal->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($Report_Neonatal_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($Report_Neonatal->Export == "") { ?>
<script type="text/javascript">
fReport_Neonatallistsrch.FilterList = <?php echo $Report_Neonatal_list->GetFilterList() ?>;
fReport_Neonatallistsrch.Init();
fReport_Neonatallist.Init();
</script>
<?php } ?>
<?php
$Report_Neonatal_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Report_Neonatal->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Report_Neonatal_list->Page_Terminate();
?>
