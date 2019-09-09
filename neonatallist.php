<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "neonatalinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$neonatal_list = NULL; // Initialize page object first

class cneonatal_list extends cneonatal {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'neonatal';

	// Page object name
	var $PageObjName = 'neonatal_list';

	// Grid form hidden field names
	var $FormName = 'fneonatallist';
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

		// Table object (neonatal)
		if (!isset($GLOBALS["neonatal"]) || get_class($GLOBALS["neonatal"]) == "cneonatal") {
			$GLOBALS["neonatal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["neonatal"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "neonataladd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "neonataldelete.php";
		$this->MultiUpdateUrl = "neonatalupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'neonatal', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fneonatallistsrch";

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
		$this->fecha_tamizaje->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->fecha_tamizaje->Visible = FALSE;
		$this->id_centro->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombre->SetVisibility();
		$this->ci->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->dias->SetVisibility();
		$this->semanas->SetVisibility();
		$this->meses->SetVisibility();
		$this->sexo->SetVisibility();
		$this->discapacidad->SetVisibility();
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
		global $EW_EXPORT, $neonatal;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($neonatal);
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
		$sFilterList = ew_Concat($sFilterList, $this->fecha_tamizaje->AdvancedSearch->ToJson(), ","); // Field fecha_tamizaje
		$sFilterList = ew_Concat($sFilterList, $this->id_centro->AdvancedSearch->ToJson(), ","); // Field id_centro
		$sFilterList = ew_Concat($sFilterList, $this->apellidopaterno->AdvancedSearch->ToJson(), ","); // Field apellidopaterno
		$sFilterList = ew_Concat($sFilterList, $this->apellidomaterno->AdvancedSearch->ToJson(), ","); // Field apellidomaterno
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJson(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->ci->AdvancedSearch->ToJson(), ","); // Field ci
		$sFilterList = ew_Concat($sFilterList, $this->fecha_nacimiento->AdvancedSearch->ToJson(), ","); // Field fecha_nacimiento
		$sFilterList = ew_Concat($sFilterList, $this->dias->AdvancedSearch->ToJson(), ","); // Field dias
		$sFilterList = ew_Concat($sFilterList, $this->semanas->AdvancedSearch->ToJson(), ","); // Field semanas
		$sFilterList = ew_Concat($sFilterList, $this->meses->AdvancedSearch->ToJson(), ","); // Field meses
		$sFilterList = ew_Concat($sFilterList, $this->sexo->AdvancedSearch->ToJson(), ","); // Field sexo
		$sFilterList = ew_Concat($sFilterList, $this->discapacidad->AdvancedSearch->ToJson(), ","); // Field discapacidad
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fneonatallistsrch", $filters);

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

		// Field fecha_tamizaje
		$this->fecha_tamizaje->AdvancedSearch->SearchValue = @$filter["x_fecha_tamizaje"];
		$this->fecha_tamizaje->AdvancedSearch->SearchOperator = @$filter["z_fecha_tamizaje"];
		$this->fecha_tamizaje->AdvancedSearch->SearchCondition = @$filter["v_fecha_tamizaje"];
		$this->fecha_tamizaje->AdvancedSearch->SearchValue2 = @$filter["y_fecha_tamizaje"];
		$this->fecha_tamizaje->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_tamizaje"];
		$this->fecha_tamizaje->AdvancedSearch->Save();

		// Field id_centro
		$this->id_centro->AdvancedSearch->SearchValue = @$filter["x_id_centro"];
		$this->id_centro->AdvancedSearch->SearchOperator = @$filter["z_id_centro"];
		$this->id_centro->AdvancedSearch->SearchCondition = @$filter["v_id_centro"];
		$this->id_centro->AdvancedSearch->SearchValue2 = @$filter["y_id_centro"];
		$this->id_centro->AdvancedSearch->SearchOperator2 = @$filter["w_id_centro"];
		$this->id_centro->AdvancedSearch->Save();

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

		// Field sexo
		$this->sexo->AdvancedSearch->SearchValue = @$filter["x_sexo"];
		$this->sexo->AdvancedSearch->SearchOperator = @$filter["z_sexo"];
		$this->sexo->AdvancedSearch->SearchCondition = @$filter["v_sexo"];
		$this->sexo->AdvancedSearch->SearchValue2 = @$filter["y_sexo"];
		$this->sexo->AdvancedSearch->SearchOperator2 = @$filter["w_sexo"];
		$this->sexo->AdvancedSearch->Save();

		// Field discapacidad
		$this->discapacidad->AdvancedSearch->SearchValue = @$filter["x_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchOperator = @$filter["z_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchCondition = @$filter["v_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchValue2 = @$filter["y_discapacidad"];
		$this->discapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_discapacidad"];
		$this->discapacidad->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->fecha_tamizaje, $Default, FALSE); // fecha_tamizaje
		$this->BuildSearchSql($sWhere, $this->id_centro, $Default, FALSE); // id_centro
		$this->BuildSearchSql($sWhere, $this->apellidopaterno, $Default, FALSE); // apellidopaterno
		$this->BuildSearchSql($sWhere, $this->apellidomaterno, $Default, FALSE); // apellidomaterno
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->ci, $Default, FALSE); // ci
		$this->BuildSearchSql($sWhere, $this->fecha_nacimiento, $Default, FALSE); // fecha_nacimiento
		$this->BuildSearchSql($sWhere, $this->dias, $Default, FALSE); // dias
		$this->BuildSearchSql($sWhere, $this->semanas, $Default, FALSE); // semanas
		$this->BuildSearchSql($sWhere, $this->meses, $Default, FALSE); // meses
		$this->BuildSearchSql($sWhere, $this->sexo, $Default, FALSE); // sexo
		$this->BuildSearchSql($sWhere, $this->discapacidad, $Default, FALSE); // discapacidad
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
			$this->fecha_tamizaje->AdvancedSearch->Save(); // fecha_tamizaje
			$this->id_centro->AdvancedSearch->Save(); // id_centro
			$this->apellidopaterno->AdvancedSearch->Save(); // apellidopaterno
			$this->apellidomaterno->AdvancedSearch->Save(); // apellidomaterno
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->ci->AdvancedSearch->Save(); // ci
			$this->fecha_nacimiento->AdvancedSearch->Save(); // fecha_nacimiento
			$this->dias->AdvancedSearch->Save(); // dias
			$this->semanas->AdvancedSearch->Save(); // semanas
			$this->meses->AdvancedSearch->Save(); // meses
			$this->sexo->AdvancedSearch->Save(); // sexo
			$this->discapacidad->AdvancedSearch->Save(); // discapacidad
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
		if ($this->fecha_tamizaje->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_centro->AdvancedSearch->IssetSession())
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
		if ($this->sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->discapacidad->AdvancedSearch->IssetSession())
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
		$this->fecha_tamizaje->AdvancedSearch->UnsetSession();
		$this->id_centro->AdvancedSearch->UnsetSession();
		$this->apellidopaterno->AdvancedSearch->UnsetSession();
		$this->apellidomaterno->AdvancedSearch->UnsetSession();
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->ci->AdvancedSearch->UnsetSession();
		$this->fecha_nacimiento->AdvancedSearch->UnsetSession();
		$this->dias->AdvancedSearch->UnsetSession();
		$this->semanas->AdvancedSearch->UnsetSession();
		$this->meses->AdvancedSearch->UnsetSession();
		$this->sexo->AdvancedSearch->UnsetSession();
		$this->discapacidad->AdvancedSearch->UnsetSession();
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
		$this->fecha_tamizaje->AdvancedSearch->Load();
		$this->id_centro->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->dias->AdvancedSearch->Load();
		$this->semanas->AdvancedSearch->Load();
		$this->meses->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
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
			$this->UpdateSort($this->fecha_tamizaje, $bCtrl); // fecha_tamizaje
			$this->UpdateSort($this->id_centro, $bCtrl); // id_centro
			$this->UpdateSort($this->apellidopaterno, $bCtrl); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno, $bCtrl); // apellidomaterno
			$this->UpdateSort($this->nombre, $bCtrl); // nombre
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->fecha_nacimiento, $bCtrl); // fecha_nacimiento
			$this->UpdateSort($this->dias, $bCtrl); // dias
			$this->UpdateSort($this->semanas, $bCtrl); // semanas
			$this->UpdateSort($this->meses, $bCtrl); // meses
			$this->UpdateSort($this->sexo, $bCtrl); // sexo
			$this->UpdateSort($this->discapacidad, $bCtrl); // discapacidad
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
				$this->setSessionOrderByList($sOrderBy);
				$this->fecha_tamizaje->setSort("");
				$this->id_centro->setSort("");
				$this->apellidopaterno->setSort("");
				$this->apellidomaterno->setSort("");
				$this->nombre->setSort("");
				$this->ci->setSort("");
				$this->fecha_nacimiento->setSort("");
				$this->dias->setSort("");
				$this->semanas->setSort("");
				$this->meses->setSort("");
				$this->sexo->setSort("");
				$this->discapacidad->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fneonatallistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fneonatallistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fneonatallist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fneonatallistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

		// fecha_tamizaje
		$this->fecha_tamizaje->AdvancedSearch->SearchValue = @$_GET["x_fecha_tamizaje"];
		if ($this->fecha_tamizaje->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_tamizaje->AdvancedSearch->SearchOperator = @$_GET["z_fecha_tamizaje"];

		// id_centro
		$this->id_centro->AdvancedSearch->SearchValue = @$_GET["x_id_centro"];
		if ($this->id_centro->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_centro->AdvancedSearch->SearchOperator = @$_GET["z_id_centro"];

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

		// sexo
		$this->sexo->AdvancedSearch->SearchValue = @$_GET["x_sexo"];
		if ($this->sexo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sexo->AdvancedSearch->SearchOperator = @$_GET["z_sexo"];

		// discapacidad
		$this->discapacidad->AdvancedSearch->SearchValue = @$_GET["x_discapacidad"];
		if ($this->discapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->discapacidad->AdvancedSearch->SearchOperator = @$_GET["z_discapacidad"];

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
		$this->fecha_tamizaje->setDbValue($row['fecha_tamizaje']);
		$this->id_centro->setDbValue($row['id_centro']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->ci->setDbValue($row['ci']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->dias->setDbValue($row['dias']);
		$this->semanas->setDbValue($row['semanas']);
		$this->meses->setDbValue($row['meses']);
		$this->sexo->setDbValue($row['sexo']);
		$this->discapacidad->setDbValue($row['discapacidad']);
		$this->id_tipodiscapacidad->setDbValue($row['id_tipodiscapacidad']);
		$this->resultado->setDbValue($row['resultado']);
		$this->resultadotamizaje->setDbValue($row['resultadotamizaje']);
		$this->tapon->setDbValue($row['tapon']);
		$this->tipo->setDbValue($row['tipo']);
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
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['fecha_tamizaje'] = NULL;
		$row['id_centro'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombre'] = NULL;
		$row['ci'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['dias'] = NULL;
		$row['semanas'] = NULL;
		$row['meses'] = NULL;
		$row['sexo'] = NULL;
		$row['discapacidad'] = NULL;
		$row['id_tipodiscapacidad'] = NULL;
		$row['resultado'] = NULL;
		$row['resultadotamizaje'] = NULL;
		$row['tapon'] = NULL;
		$row['tipo'] = NULL;
		$row['repetirprueba'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_apoderado'] = NULL;
		$row['id_referencia'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->fecha_tamizaje->DbValue = $row['fecha_tamizaje'];
		$this->id_centro->DbValue = $row['id_centro'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->ci->DbValue = $row['ci'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->dias->DbValue = $row['dias'];
		$this->semanas->DbValue = $row['semanas'];
		$this->meses->DbValue = $row['meses'];
		$this->sexo->DbValue = $row['sexo'];
		$this->discapacidad->DbValue = $row['discapacidad'];
		$this->id_tipodiscapacidad->DbValue = $row['id_tipodiscapacidad'];
		$this->resultado->DbValue = $row['resultado'];
		$this->resultadotamizaje->DbValue = $row['resultadotamizaje'];
		$this->tapon->DbValue = $row['tapon'];
		$this->tipo->DbValue = $row['tipo'];
		$this->repetirprueba->DbValue = $row['repetirprueba'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_apoderado->DbValue = $row['id_apoderado'];
		$this->id_referencia->DbValue = $row['id_referencia'];
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
		// fecha_tamizaje
		// id_centro
		// apellidopaterno
		// apellidomaterno
		// nombre
		// ci
		// fecha_nacimiento
		// dias
		// semanas
		// meses
		// sexo
		// discapacidad
		// id_tipodiscapacidad
		// resultado
		// resultadotamizaje
		// tapon
		// tipo
		// repetirprueba
		// observaciones
		// id_apoderado
		// id_referencia

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// fecha_tamizaje
		$this->fecha_tamizaje->ViewValue = $this->fecha_tamizaje->CurrentValue;
		$this->fecha_tamizaje->ViewValue = ew_FormatDateTime($this->fecha_tamizaje->ViewValue, 0);
		$this->fecha_tamizaje->ViewCustomAttributes = "";

		// id_centro
		if (strval($this->id_centro->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_centro->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucionesdesalud`";
		$sWhereWrk = "";
		$this->id_centro->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_centro, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_centro->ViewValue = $this->id_centro->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
			}
		} else {
			$this->id_centro->ViewValue = NULL;
		}
		$this->id_centro->ViewCustomAttributes = "";

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

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

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

		// id_tipodiscapacidad
		$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->CurrentValue;
		$this->id_tipodiscapacidad->ViewCustomAttributes = "";

		// resultado
		$this->resultado->ViewValue = $this->resultado->CurrentValue;
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

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$this->tipo->ViewValue = $this->tipo->OptionCaption($this->tipo->CurrentValue);
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

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
		$sSqlWrk = "SELECT `id`, `nombres` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidopaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `apoderado`";
		$sWhereWrk = "";
		$this->id_apoderado->LookupFilters = array("dx1" => '`nombres`', "dx2" => '`apellidopaterno`', "dx3" => '`apellidopaterno`');
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
		}
		$this->id_referencia->ViewCustomAttributes = "";

			// fecha_tamizaje
			$this->fecha_tamizaje->LinkCustomAttributes = "";
			$this->fecha_tamizaje->HrefValue = "";
			$this->fecha_tamizaje->TooltipValue = "";

			// id_centro
			$this->id_centro->LinkCustomAttributes = "";
			$this->id_centro->HrefValue = "";
			$this->id_centro->TooltipValue = "";

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

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// dias
			$this->dias->LinkCustomAttributes = "";
			$this->dias->HrefValue = "";
			$this->dias->TooltipValue = "";

			// semanas
			$this->semanas->LinkCustomAttributes = "";
			$this->semanas->HrefValue = "";
			$this->semanas->TooltipValue = "";

			// meses
			$this->meses->LinkCustomAttributes = "";
			$this->meses->HrefValue = "";
			$this->meses->TooltipValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";
			$this->sexo->TooltipValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";
			$this->discapacidad->TooltipValue = "";

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

			// fecha_tamizaje
			$this->fecha_tamizaje->EditAttrs["class"] = "form-control";
			$this->fecha_tamizaje->EditCustomAttributes = "";
			$this->fecha_tamizaje->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_tamizaje->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha_tamizaje->PlaceHolder = ew_RemoveHtml($this->fecha_tamizaje->FldCaption());

			// id_centro
			$this->id_centro->EditAttrs["class"] = "form-control";
			$this->id_centro->EditCustomAttributes = "";

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

			// dias
			$this->dias->EditAttrs["class"] = "form-control";
			$this->dias->EditCustomAttributes = "";
			$this->dias->EditValue = ew_HtmlEncode($this->dias->AdvancedSearch->SearchValue);
			$this->dias->PlaceHolder = ew_RemoveHtml($this->dias->FldCaption());

			// semanas
			$this->semanas->EditAttrs["class"] = "form-control";
			$this->semanas->EditCustomAttributes = "";
			$this->semanas->EditValue = ew_HtmlEncode($this->semanas->AdvancedSearch->SearchValue);
			$this->semanas->PlaceHolder = ew_RemoveHtml($this->semanas->FldCaption());

			// meses
			$this->meses->EditAttrs["class"] = "form-control";
			$this->meses->EditCustomAttributes = "";
			$this->meses->EditValue = ew_HtmlEncode($this->meses->AdvancedSearch->SearchValue);
			$this->meses->PlaceHolder = ew_RemoveHtml($this->meses->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// discapacidad
			$this->discapacidad->EditAttrs["class"] = "form-control";
			$this->discapacidad->EditCustomAttributes = "";
			$this->discapacidad->EditValue = ew_HtmlEncode($this->discapacidad->AdvancedSearch->SearchValue);
			if (strval($this->discapacidad->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->discapacidad->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
			$sWhereWrk = "";
			$this->discapacidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->discapacidad->EditValue = $this->discapacidad->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->discapacidad->EditValue = ew_HtmlEncode($this->discapacidad->AdvancedSearch->SearchValue);
				}
			} else {
				$this->discapacidad->EditValue = NULL;
			}
			$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->EditAttrs["class"] = "form-control";
			$this->id_tipodiscapacidad->EditCustomAttributes = "";
			$this->id_tipodiscapacidad->EditValue = ew_HtmlEncode($this->id_tipodiscapacidad->AdvancedSearch->SearchValue);
			$this->id_tipodiscapacidad->PlaceHolder = ew_RemoveHtml($this->id_tipodiscapacidad->FldCaption());

			// resultado
			$this->resultado->EditAttrs["class"] = "form-control";
			$this->resultado->EditCustomAttributes = "";
			$this->resultado->EditValue = ew_HtmlEncode($this->resultado->AdvancedSearch->SearchValue);
			$this->resultado->PlaceHolder = ew_RemoveHtml($this->resultado->FldCaption());

			// resultadotamizaje
			$this->resultadotamizaje->EditAttrs["class"] = "form-control";
			$this->resultadotamizaje->EditCustomAttributes = "";
			$this->resultadotamizaje->EditValue = ew_HtmlEncode($this->resultadotamizaje->AdvancedSearch->SearchValue);
			$this->resultadotamizaje->PlaceHolder = ew_RemoveHtml($this->resultadotamizaje->FldCaption());

			// tapon
			$this->tapon->EditCustomAttributes = "";
			$this->tapon->EditValue = $this->tapon->Options(FALSE);

			// tipo
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = $this->tipo->Options(FALSE);

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
		$this->fecha_tamizaje->AdvancedSearch->Load();
		$this->id_centro->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->dias->AdvancedSearch->Load();
		$this->semanas->AdvancedSearch->Load();
		$this->meses->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
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
		case "x_discapacidad":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `discapacidad`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
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
		case "x_discapacidad":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld` FROM `discapacidad`";
				$sWhereWrk = "`nombre` LIKE '{query_value}%'";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->discapacidad, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($neonatal_list)) $neonatal_list = new cneonatal_list();

// Page init
$neonatal_list->Page_Init();

// Page main
$neonatal_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$neonatal_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fneonatallist = new ew_Form("fneonatallist", "list");
fneonatallist.FormKeyCountName = '<?php echo $neonatal_list->FormKeyCountName ?>';

// Form_CustomValidate event
fneonatallist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fneonatallist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fneonatallist.Lists["x_id_centro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institucionesdesalud"};
fneonatallist.Lists["x_id_centro"].Data = "<?php echo $neonatal_list->id_centro->LookupFilterQuery(FALSE, "list") ?>";
fneonatallist.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonatallist.Lists["x_sexo"].Options = <?php echo json_encode($neonatal_list->sexo->Options()) ?>;
fneonatallist.Lists["x_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fneonatallist.Lists["x_discapacidad"].Data = "<?php echo $neonatal_list->discapacidad->LookupFilterQuery(FALSE, "list") ?>";
fneonatallist.AutoSuggests["x_discapacidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $neonatal_list->discapacidad->LookupFilterQuery(TRUE, "list"))) ?>;
fneonatallist.Lists["x_tapon"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonatallist.Lists["x_tapon"].Options = <?php echo json_encode($neonatal_list->tapon->Options()) ?>;
fneonatallist.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonatallist.Lists["x_tipo"].Options = <?php echo json_encode($neonatal_list->tipo->Options()) ?>;
fneonatallist.Lists["x_repetirprueba"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonatallist.Lists["x_repetirprueba"].Options = <?php echo json_encode($neonatal_list->repetirprueba->Options()) ?>;
fneonatallist.Lists["x_id_apoderado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidopaterno","x_apellidopaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"apoderado"};
fneonatallist.Lists["x_id_apoderado"].Data = "<?php echo $neonatal_list->id_apoderado->LookupFilterQuery(FALSE, "list") ?>";
fneonatallist.Lists["x_id_referencia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombrescentromedico","x_nombrescompleto","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referencia"};
fneonatallist.Lists["x_id_referencia"].Data = "<?php echo $neonatal_list->id_referencia->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fneonatallistsrch = new ew_Form("fneonatallistsrch");

// Validate function for search
fneonatallistsrch.Validate = function(fobj) {
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
fneonatallistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fneonatallistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fneonatallistsrch.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fneonatallistsrch.Lists["x_sexo"].Options = <?php echo json_encode($neonatal_list->sexo->Options()) ?>;
fneonatallistsrch.Lists["x_discapacidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"discapacidad"};
fneonatallistsrch.Lists["x_discapacidad"].Data = "<?php echo $neonatal_list->discapacidad->LookupFilterQuery(FALSE, "extbs") ?>";
fneonatallistsrch.AutoSuggests["x_discapacidad"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $neonatal_list->discapacidad->LookupFilterQuery(TRUE, "extbs"))) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($neonatal_list->TotalRecs > 0 && $neonatal_list->ExportOptions->Visible()) { ?>
<?php $neonatal_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($neonatal_list->SearchOptions->Visible()) { ?>
<?php $neonatal_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($neonatal_list->FilterOptions->Visible()) { ?>
<?php $neonatal_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $neonatal_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($neonatal_list->TotalRecs <= 0)
			$neonatal_list->TotalRecs = $neonatal->ListRecordCount();
	} else {
		if (!$neonatal_list->Recordset && ($neonatal_list->Recordset = $neonatal_list->LoadRecordset()))
			$neonatal_list->TotalRecs = $neonatal_list->Recordset->RecordCount();
	}
	$neonatal_list->StartRec = 1;
	if ($neonatal_list->DisplayRecs <= 0 || ($neonatal->Export <> "" && $neonatal->ExportAll)) // Display all records
		$neonatal_list->DisplayRecs = $neonatal_list->TotalRecs;
	if (!($neonatal->Export <> "" && $neonatal->ExportAll))
		$neonatal_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$neonatal_list->Recordset = $neonatal_list->LoadRecordset($neonatal_list->StartRec-1, $neonatal_list->DisplayRecs);

	// Set no record found message
	if ($neonatal->CurrentAction == "" && $neonatal_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$neonatal_list->setWarningMessage(ew_DeniedMsg());
		if ($neonatal_list->SearchWhere == "0=101")
			$neonatal_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$neonatal_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$neonatal_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($neonatal->Export == "" && $neonatal->CurrentAction == "") { ?>
<form name="fneonatallistsrch" id="fneonatallistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($neonatal_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fneonatallistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="neonatal">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$neonatal_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$neonatal->RowType = EW_ROWTYPE_SEARCH;

// Render row
$neonatal->ResetAttrs();
$neonatal_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($neonatal->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="xsc_apellidopaterno" class="ewCell form-group">
		<label for="x_apellidopaterno" class="ewSearchCaption ewLabel"><?php echo $neonatal->apellidopaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidopaterno" id="z_apellidopaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="neonatal" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $neonatal->apellidopaterno->EditValue ?>"<?php echo $neonatal->apellidopaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($neonatal->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="xsc_apellidomaterno" class="ewCell form-group">
		<label for="x_apellidomaterno" class="ewSearchCaption ewLabel"><?php echo $neonatal->apellidomaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidomaterno" id="z_apellidomaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="neonatal" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $neonatal->apellidomaterno->EditValue ?>"<?php echo $neonatal->apellidomaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($neonatal->nombre->Visible) { // nombre ?>
	<div id="xsc_nombre" class="ewCell form-group">
		<label for="x_nombre" class="ewSearchCaption ewLabel"><?php echo $neonatal->nombre->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombre" id="z_nombre" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="neonatal" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->nombre->getPlaceHolder()) ?>" value="<?php echo $neonatal->nombre->EditValue ?>"<?php echo $neonatal->nombre->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($neonatal->ci->Visible) { // ci ?>
	<div id="xsc_ci" class="ewCell form-group">
		<label for="x_ci" class="ewSearchCaption ewLabel"><?php echo $neonatal->ci->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ci" id="z_ci" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="neonatal" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->ci->getPlaceHolder()) ?>" value="<?php echo $neonatal->ci->EditValue ?>"<?php echo $neonatal->ci->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($neonatal->dias->Visible) { // dias ?>
	<div id="xsc_dias" class="ewCell form-group">
		<label for="x_dias" class="ewSearchCaption ewLabel"><?php echo $neonatal->dias->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_dias" id="z_dias" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="neonatal" data-field="x_dias" name="x_dias" id="x_dias" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->dias->getPlaceHolder()) ?>" value="<?php echo $neonatal->dias->EditValue ?>"<?php echo $neonatal->dias->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($neonatal->semanas->Visible) { // semanas ?>
	<div id="xsc_semanas" class="ewCell form-group">
		<label for="x_semanas" class="ewSearchCaption ewLabel"><?php echo $neonatal->semanas->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_semanas" id="z_semanas" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="neonatal" data-field="x_semanas" name="x_semanas" id="x_semanas" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->semanas->getPlaceHolder()) ?>" value="<?php echo $neonatal->semanas->EditValue ?>"<?php echo $neonatal->semanas->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($neonatal->meses->Visible) { // meses ?>
	<div id="xsc_meses" class="ewCell form-group">
		<label for="x_meses" class="ewSearchCaption ewLabel"><?php echo $neonatal->meses->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_meses" id="z_meses" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="neonatal" data-field="x_meses" name="x_meses" id="x_meses" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->meses->getPlaceHolder()) ?>" value="<?php echo $neonatal->meses->EditValue ?>"<?php echo $neonatal->meses->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
<?php if ($neonatal->sexo->Visible) { // sexo ?>
	<div id="xsc_sexo" class="ewCell form-group">
		<label for="x_sexo" class="ewSearchCaption ewLabel"><?php echo $neonatal->sexo->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_sexo" id="z_sexo" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="neonatal" data-field="x_sexo" data-value-separator="<?php echo $neonatal->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $neonatal->sexo->EditAttributes() ?>>
<?php echo $neonatal->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_9" class="ewRow">
<?php if ($neonatal->discapacidad->Visible) { // discapacidad ?>
	<div id="xsc_discapacidad" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $neonatal->discapacidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_discapacidad" id="z_discapacidad" value="LIKE"></span>
		<span class="ewSearchField">
<?php
$wrkonchange = trim(" " . @$neonatal->discapacidad->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$neonatal->discapacidad->EditAttrs["onchange"] = "";
?>
<span id="as_x_discapacidad" style="white-space: nowrap; z-index: 8870">
	<input type="text" name="sv_x_discapacidad" id="sv_x_discapacidad" value="<?php echo $neonatal->discapacidad->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->discapacidad->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($neonatal->discapacidad->getPlaceHolder()) ?>"<?php echo $neonatal->discapacidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="neonatal" data-field="x_discapacidad" data-value-separator="<?php echo $neonatal->discapacidad->DisplayValueSeparatorAttribute() ?>" name="x_discapacidad" id="x_discapacidad" value="<?php echo ew_HtmlEncode($neonatal->discapacidad->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fneonatallistsrch.CreateAutoSuggest({"id":"x_discapacidad","forceSelect":false});
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_10" class="ewRow">
<?php if ($neonatal->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
	<div id="xsc_id_tipodiscapacidad" class="ewCell form-group">
		<label for="x_id_tipodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $neonatal->id_tipodiscapacidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_id_tipodiscapacidad" id="z_id_tipodiscapacidad" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="neonatal" data-field="x_id_tipodiscapacidad" name="x_id_tipodiscapacidad" id="x_id_tipodiscapacidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($neonatal->id_tipodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $neonatal->id_tipodiscapacidad->EditValue ?>"<?php echo $neonatal->id_tipodiscapacidad->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_11" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $neonatal_list->ShowPageHeader(); ?>
<?php
$neonatal_list->ShowMessage();
?>
<?php if ($neonatal_list->TotalRecs > 0 || $neonatal->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($neonatal_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> neonatal">
<div class="box-header ewGridUpperPanel">
<?php if ($neonatal->CurrentAction <> "gridadd" && $neonatal->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($neonatal_list->Pager)) $neonatal_list->Pager = new cPrevNextPager($neonatal_list->StartRec, $neonatal_list->DisplayRecs, $neonatal_list->TotalRecs, $neonatal_list->AutoHidePager) ?>
<?php if ($neonatal_list->Pager->RecordCount > 0 && $neonatal_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($neonatal_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $neonatal_list->PageUrl() ?>start=<?php echo $neonatal_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($neonatal_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $neonatal_list->PageUrl() ?>start=<?php echo $neonatal_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $neonatal_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($neonatal_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $neonatal_list->PageUrl() ?>start=<?php echo $neonatal_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($neonatal_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $neonatal_list->PageUrl() ?>start=<?php echo $neonatal_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $neonatal_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($neonatal_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $neonatal_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $neonatal_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $neonatal_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($neonatal_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fneonatallist" id="fneonatallist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($neonatal_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $neonatal_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="neonatal">
<div id="gmp_neonatal" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($neonatal_list->TotalRecs > 0 || $neonatal->CurrentAction == "gridedit") { ?>
<table id="tbl_neonatallist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$neonatal_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$neonatal_list->RenderListOptions();

// Render list options (header, left)
$neonatal_list->ListOptions->Render("header", "left");
?>
<?php if ($neonatal->fecha_tamizaje->Visible) { // fecha_tamizaje ?>
	<?php if ($neonatal->SortUrl($neonatal->fecha_tamizaje) == "") { ?>
		<th data-name="fecha_tamizaje" class="<?php echo $neonatal->fecha_tamizaje->HeaderCellClass() ?>"><div id="elh_neonatal_fecha_tamizaje" class="neonatal_fecha_tamizaje"><div class="ewTableHeaderCaption"><?php echo $neonatal->fecha_tamizaje->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_tamizaje" class="<?php echo $neonatal->fecha_tamizaje->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->fecha_tamizaje) ?>',2);"><div id="elh_neonatal_fecha_tamizaje" class="neonatal_fecha_tamizaje">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->fecha_tamizaje->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->fecha_tamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->fecha_tamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->id_centro->Visible) { // id_centro ?>
	<?php if ($neonatal->SortUrl($neonatal->id_centro) == "") { ?>
		<th data-name="id_centro" class="<?php echo $neonatal->id_centro->HeaderCellClass() ?>"><div id="elh_neonatal_id_centro" class="neonatal_id_centro"><div class="ewTableHeaderCaption"><?php echo $neonatal->id_centro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_centro" class="<?php echo $neonatal->id_centro->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->id_centro) ?>',2);"><div id="elh_neonatal_id_centro" class="neonatal_id_centro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->id_centro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->id_centro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->id_centro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->apellidopaterno->Visible) { // apellidopaterno ?>
	<?php if ($neonatal->SortUrl($neonatal->apellidopaterno) == "") { ?>
		<th data-name="apellidopaterno" class="<?php echo $neonatal->apellidopaterno->HeaderCellClass() ?>"><div id="elh_neonatal_apellidopaterno" class="neonatal_apellidopaterno"><div class="ewTableHeaderCaption"><?php echo $neonatal->apellidopaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidopaterno" class="<?php echo $neonatal->apellidopaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->apellidopaterno) ?>',2);"><div id="elh_neonatal_apellidopaterno" class="neonatal_apellidopaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->apellidopaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->apellidomaterno->Visible) { // apellidomaterno ?>
	<?php if ($neonatal->SortUrl($neonatal->apellidomaterno) == "") { ?>
		<th data-name="apellidomaterno" class="<?php echo $neonatal->apellidomaterno->HeaderCellClass() ?>"><div id="elh_neonatal_apellidomaterno" class="neonatal_apellidomaterno"><div class="ewTableHeaderCaption"><?php echo $neonatal->apellidomaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidomaterno" class="<?php echo $neonatal->apellidomaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->apellidomaterno) ?>',2);"><div id="elh_neonatal_apellidomaterno" class="neonatal_apellidomaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->apellidomaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->nombre->Visible) { // nombre ?>
	<?php if ($neonatal->SortUrl($neonatal->nombre) == "") { ?>
		<th data-name="nombre" class="<?php echo $neonatal->nombre->HeaderCellClass() ?>"><div id="elh_neonatal_nombre" class="neonatal_nombre"><div class="ewTableHeaderCaption"><?php echo $neonatal->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre" class="<?php echo $neonatal->nombre->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->nombre) ?>',2);"><div id="elh_neonatal_nombre" class="neonatal_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->ci->Visible) { // ci ?>
	<?php if ($neonatal->SortUrl($neonatal->ci) == "") { ?>
		<th data-name="ci" class="<?php echo $neonatal->ci->HeaderCellClass() ?>"><div id="elh_neonatal_ci" class="neonatal_ci"><div class="ewTableHeaderCaption"><?php echo $neonatal->ci->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ci" class="<?php echo $neonatal->ci->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->ci) ?>',2);"><div id="elh_neonatal_ci" class="neonatal_ci">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->ci->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<?php if ($neonatal->SortUrl($neonatal->fecha_nacimiento) == "") { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $neonatal->fecha_nacimiento->HeaderCellClass() ?>"><div id="elh_neonatal_fecha_nacimiento" class="neonatal_fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $neonatal->fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $neonatal->fecha_nacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->fecha_nacimiento) ?>',2);"><div id="elh_neonatal_fecha_nacimiento" class="neonatal_fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->dias->Visible) { // dias ?>
	<?php if ($neonatal->SortUrl($neonatal->dias) == "") { ?>
		<th data-name="dias" class="<?php echo $neonatal->dias->HeaderCellClass() ?>"><div id="elh_neonatal_dias" class="neonatal_dias"><div class="ewTableHeaderCaption"><?php echo $neonatal->dias->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dias" class="<?php echo $neonatal->dias->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->dias) ?>',2);"><div id="elh_neonatal_dias" class="neonatal_dias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->dias->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->dias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->dias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->semanas->Visible) { // semanas ?>
	<?php if ($neonatal->SortUrl($neonatal->semanas) == "") { ?>
		<th data-name="semanas" class="<?php echo $neonatal->semanas->HeaderCellClass() ?>"><div id="elh_neonatal_semanas" class="neonatal_semanas"><div class="ewTableHeaderCaption"><?php echo $neonatal->semanas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="semanas" class="<?php echo $neonatal->semanas->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->semanas) ?>',2);"><div id="elh_neonatal_semanas" class="neonatal_semanas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->semanas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->semanas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->semanas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->meses->Visible) { // meses ?>
	<?php if ($neonatal->SortUrl($neonatal->meses) == "") { ?>
		<th data-name="meses" class="<?php echo $neonatal->meses->HeaderCellClass() ?>"><div id="elh_neonatal_meses" class="neonatal_meses"><div class="ewTableHeaderCaption"><?php echo $neonatal->meses->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="meses" class="<?php echo $neonatal->meses->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->meses) ?>',2);"><div id="elh_neonatal_meses" class="neonatal_meses">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->meses->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->meses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->meses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->sexo->Visible) { // sexo ?>
	<?php if ($neonatal->SortUrl($neonatal->sexo) == "") { ?>
		<th data-name="sexo" class="<?php echo $neonatal->sexo->HeaderCellClass() ?>"><div id="elh_neonatal_sexo" class="neonatal_sexo"><div class="ewTableHeaderCaption"><?php echo $neonatal->sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sexo" class="<?php echo $neonatal->sexo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->sexo) ?>',2);"><div id="elh_neonatal_sexo" class="neonatal_sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->discapacidad->Visible) { // discapacidad ?>
	<?php if ($neonatal->SortUrl($neonatal->discapacidad) == "") { ?>
		<th data-name="discapacidad" class="<?php echo $neonatal->discapacidad->HeaderCellClass() ?>"><div id="elh_neonatal_discapacidad" class="neonatal_discapacidad"><div class="ewTableHeaderCaption"><?php echo $neonatal->discapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="discapacidad" class="<?php echo $neonatal->discapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->discapacidad) ?>',2);"><div id="elh_neonatal_discapacidad" class="neonatal_discapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->discapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
	<?php if ($neonatal->SortUrl($neonatal->id_tipodiscapacidad) == "") { ?>
		<th data-name="id_tipodiscapacidad" class="<?php echo $neonatal->id_tipodiscapacidad->HeaderCellClass() ?>"><div id="elh_neonatal_id_tipodiscapacidad" class="neonatal_id_tipodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $neonatal->id_tipodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipodiscapacidad" class="<?php echo $neonatal->id_tipodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->id_tipodiscapacidad) ?>',2);"><div id="elh_neonatal_id_tipodiscapacidad" class="neonatal_id_tipodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->id_tipodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->id_tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->id_tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->resultado->Visible) { // resultado ?>
	<?php if ($neonatal->SortUrl($neonatal->resultado) == "") { ?>
		<th data-name="resultado" class="<?php echo $neonatal->resultado->HeaderCellClass() ?>"><div id="elh_neonatal_resultado" class="neonatal_resultado"><div class="ewTableHeaderCaption"><?php echo $neonatal->resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultado" class="<?php echo $neonatal->resultado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->resultado) ?>',2);"><div id="elh_neonatal_resultado" class="neonatal_resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->resultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->resultadotamizaje->Visible) { // resultadotamizaje ?>
	<?php if ($neonatal->SortUrl($neonatal->resultadotamizaje) == "") { ?>
		<th data-name="resultadotamizaje" class="<?php echo $neonatal->resultadotamizaje->HeaderCellClass() ?>"><div id="elh_neonatal_resultadotamizaje" class="neonatal_resultadotamizaje"><div class="ewTableHeaderCaption"><?php echo $neonatal->resultadotamizaje->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultadotamizaje" class="<?php echo $neonatal->resultadotamizaje->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->resultadotamizaje) ?>',2);"><div id="elh_neonatal_resultadotamizaje" class="neonatal_resultadotamizaje">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->resultadotamizaje->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->resultadotamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->resultadotamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->tapon->Visible) { // tapon ?>
	<?php if ($neonatal->SortUrl($neonatal->tapon) == "") { ?>
		<th data-name="tapon" class="<?php echo $neonatal->tapon->HeaderCellClass() ?>"><div id="elh_neonatal_tapon" class="neonatal_tapon"><div class="ewTableHeaderCaption"><?php echo $neonatal->tapon->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tapon" class="<?php echo $neonatal->tapon->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->tapon) ?>',2);"><div id="elh_neonatal_tapon" class="neonatal_tapon">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->tapon->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->tapon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->tapon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->tipo->Visible) { // tipo ?>
	<?php if ($neonatal->SortUrl($neonatal->tipo) == "") { ?>
		<th data-name="tipo" class="<?php echo $neonatal->tipo->HeaderCellClass() ?>"><div id="elh_neonatal_tipo" class="neonatal_tipo"><div class="ewTableHeaderCaption"><?php echo $neonatal->tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo" class="<?php echo $neonatal->tipo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->tipo) ?>',2);"><div id="elh_neonatal_tipo" class="neonatal_tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->tipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->repetirprueba->Visible) { // repetirprueba ?>
	<?php if ($neonatal->SortUrl($neonatal->repetirprueba) == "") { ?>
		<th data-name="repetirprueba" class="<?php echo $neonatal->repetirprueba->HeaderCellClass() ?>"><div id="elh_neonatal_repetirprueba" class="neonatal_repetirprueba"><div class="ewTableHeaderCaption"><?php echo $neonatal->repetirprueba->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="repetirprueba" class="<?php echo $neonatal->repetirprueba->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->repetirprueba) ?>',2);"><div id="elh_neonatal_repetirprueba" class="neonatal_repetirprueba">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->repetirprueba->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->repetirprueba->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->repetirprueba->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->observaciones->Visible) { // observaciones ?>
	<?php if ($neonatal->SortUrl($neonatal->observaciones) == "") { ?>
		<th data-name="observaciones" class="<?php echo $neonatal->observaciones->HeaderCellClass() ?>"><div id="elh_neonatal_observaciones" class="neonatal_observaciones"><div class="ewTableHeaderCaption"><?php echo $neonatal->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones" class="<?php echo $neonatal->observaciones->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->observaciones) ?>',2);"><div id="elh_neonatal_observaciones" class="neonatal_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->observaciones->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->id_apoderado->Visible) { // id_apoderado ?>
	<?php if ($neonatal->SortUrl($neonatal->id_apoderado) == "") { ?>
		<th data-name="id_apoderado" class="<?php echo $neonatal->id_apoderado->HeaderCellClass() ?>"><div id="elh_neonatal_id_apoderado" class="neonatal_id_apoderado"><div class="ewTableHeaderCaption"><?php echo $neonatal->id_apoderado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_apoderado" class="<?php echo $neonatal->id_apoderado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->id_apoderado) ?>',2);"><div id="elh_neonatal_id_apoderado" class="neonatal_id_apoderado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->id_apoderado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->id_apoderado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->id_apoderado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($neonatal->id_referencia->Visible) { // id_referencia ?>
	<?php if ($neonatal->SortUrl($neonatal->id_referencia) == "") { ?>
		<th data-name="id_referencia" class="<?php echo $neonatal->id_referencia->HeaderCellClass() ?>"><div id="elh_neonatal_id_referencia" class="neonatal_id_referencia"><div class="ewTableHeaderCaption"><?php echo $neonatal->id_referencia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_referencia" class="<?php echo $neonatal->id_referencia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $neonatal->SortUrl($neonatal->id_referencia) ?>',2);"><div id="elh_neonatal_id_referencia" class="neonatal_id_referencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $neonatal->id_referencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($neonatal->id_referencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($neonatal->id_referencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$neonatal_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($neonatal->ExportAll && $neonatal->Export <> "") {
	$neonatal_list->StopRec = $neonatal_list->TotalRecs;
} else {

	// Set the last record to display
	if ($neonatal_list->TotalRecs > $neonatal_list->StartRec + $neonatal_list->DisplayRecs - 1)
		$neonatal_list->StopRec = $neonatal_list->StartRec + $neonatal_list->DisplayRecs - 1;
	else
		$neonatal_list->StopRec = $neonatal_list->TotalRecs;
}
$neonatal_list->RecCnt = $neonatal_list->StartRec - 1;
if ($neonatal_list->Recordset && !$neonatal_list->Recordset->EOF) {
	$neonatal_list->Recordset->MoveFirst();
	$bSelectLimit = $neonatal_list->UseSelectLimit;
	if (!$bSelectLimit && $neonatal_list->StartRec > 1)
		$neonatal_list->Recordset->Move($neonatal_list->StartRec - 1);
} elseif (!$neonatal->AllowAddDeleteRow && $neonatal_list->StopRec == 0) {
	$neonatal_list->StopRec = $neonatal->GridAddRowCount;
}

// Initialize aggregate
$neonatal->RowType = EW_ROWTYPE_AGGREGATEINIT;
$neonatal->ResetAttrs();
$neonatal_list->RenderRow();
while ($neonatal_list->RecCnt < $neonatal_list->StopRec) {
	$neonatal_list->RecCnt++;
	if (intval($neonatal_list->RecCnt) >= intval($neonatal_list->StartRec)) {
		$neonatal_list->RowCnt++;

		// Set up key count
		$neonatal_list->KeyCount = $neonatal_list->RowIndex;

		// Init row class and style
		$neonatal->ResetAttrs();
		$neonatal->CssClass = "";
		if ($neonatal->CurrentAction == "gridadd") {
		} else {
			$neonatal_list->LoadRowValues($neonatal_list->Recordset); // Load row values
		}
		$neonatal->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$neonatal->RowAttrs = array_merge($neonatal->RowAttrs, array('data-rowindex'=>$neonatal_list->RowCnt, 'id'=>'r' . $neonatal_list->RowCnt . '_neonatal', 'data-rowtype'=>$neonatal->RowType));

		// Render row
		$neonatal_list->RenderRow();

		// Render list options
		$neonatal_list->RenderListOptions();
?>
	<tr<?php echo $neonatal->RowAttributes() ?>>
<?php

// Render list options (body, left)
$neonatal_list->ListOptions->Render("body", "left", $neonatal_list->RowCnt);
?>
	<?php if ($neonatal->fecha_tamizaje->Visible) { // fecha_tamizaje ?>
		<td data-name="fecha_tamizaje"<?php echo $neonatal->fecha_tamizaje->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_fecha_tamizaje" class="neonatal_fecha_tamizaje">
<span<?php echo $neonatal->fecha_tamizaje->ViewAttributes() ?>>
<?php echo $neonatal->fecha_tamizaje->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->id_centro->Visible) { // id_centro ?>
		<td data-name="id_centro"<?php echo $neonatal->id_centro->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_id_centro" class="neonatal_id_centro">
<span<?php echo $neonatal->id_centro->ViewAttributes() ?>>
<?php echo $neonatal->id_centro->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->apellidopaterno->Visible) { // apellidopaterno ?>
		<td data-name="apellidopaterno"<?php echo $neonatal->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_apellidopaterno" class="neonatal_apellidopaterno">
<span<?php echo $neonatal->apellidopaterno->ViewAttributes() ?>>
<?php echo $neonatal->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->apellidomaterno->Visible) { // apellidomaterno ?>
		<td data-name="apellidomaterno"<?php echo $neonatal->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_apellidomaterno" class="neonatal_apellidomaterno">
<span<?php echo $neonatal->apellidomaterno->ViewAttributes() ?>>
<?php echo $neonatal->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $neonatal->nombre->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_nombre" class="neonatal_nombre">
<span<?php echo $neonatal->nombre->ViewAttributes() ?>>
<?php echo $neonatal->nombre->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->ci->Visible) { // ci ?>
		<td data-name="ci"<?php echo $neonatal->ci->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_ci" class="neonatal_ci">
<span<?php echo $neonatal->ci->ViewAttributes() ?>>
<?php echo $neonatal->ci->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento"<?php echo $neonatal->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_fecha_nacimiento" class="neonatal_fecha_nacimiento">
<span<?php echo $neonatal->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $neonatal->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->dias->Visible) { // dias ?>
		<td data-name="dias"<?php echo $neonatal->dias->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_dias" class="neonatal_dias">
<span<?php echo $neonatal->dias->ViewAttributes() ?>>
<?php echo $neonatal->dias->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->semanas->Visible) { // semanas ?>
		<td data-name="semanas"<?php echo $neonatal->semanas->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_semanas" class="neonatal_semanas">
<span<?php echo $neonatal->semanas->ViewAttributes() ?>>
<?php echo $neonatal->semanas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->meses->Visible) { // meses ?>
		<td data-name="meses"<?php echo $neonatal->meses->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_meses" class="neonatal_meses">
<span<?php echo $neonatal->meses->ViewAttributes() ?>>
<?php echo $neonatal->meses->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->sexo->Visible) { // sexo ?>
		<td data-name="sexo"<?php echo $neonatal->sexo->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_sexo" class="neonatal_sexo">
<span<?php echo $neonatal->sexo->ViewAttributes() ?>>
<?php echo $neonatal->sexo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->discapacidad->Visible) { // discapacidad ?>
		<td data-name="discapacidad"<?php echo $neonatal->discapacidad->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_discapacidad" class="neonatal_discapacidad">
<span<?php echo $neonatal->discapacidad->ViewAttributes() ?>>
<?php echo $neonatal->discapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->id_tipodiscapacidad->Visible) { // id_tipodiscapacidad ?>
		<td data-name="id_tipodiscapacidad"<?php echo $neonatal->id_tipodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_id_tipodiscapacidad" class="neonatal_id_tipodiscapacidad">
<span<?php echo $neonatal->id_tipodiscapacidad->ViewAttributes() ?>>
<?php echo $neonatal->id_tipodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->resultado->Visible) { // resultado ?>
		<td data-name="resultado"<?php echo $neonatal->resultado->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_resultado" class="neonatal_resultado">
<span<?php echo $neonatal->resultado->ViewAttributes() ?>>
<?php echo $neonatal->resultado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->resultadotamizaje->Visible) { // resultadotamizaje ?>
		<td data-name="resultadotamizaje"<?php echo $neonatal->resultadotamizaje->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_resultadotamizaje" class="neonatal_resultadotamizaje">
<span<?php echo $neonatal->resultadotamizaje->ViewAttributes() ?>>
<?php echo $neonatal->resultadotamizaje->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->tapon->Visible) { // tapon ?>
		<td data-name="tapon"<?php echo $neonatal->tapon->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_tapon" class="neonatal_tapon">
<span<?php echo $neonatal->tapon->ViewAttributes() ?>>
<?php echo $neonatal->tapon->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->tipo->Visible) { // tipo ?>
		<td data-name="tipo"<?php echo $neonatal->tipo->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_tipo" class="neonatal_tipo">
<span<?php echo $neonatal->tipo->ViewAttributes() ?>>
<?php echo $neonatal->tipo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->repetirprueba->Visible) { // repetirprueba ?>
		<td data-name="repetirprueba"<?php echo $neonatal->repetirprueba->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_repetirprueba" class="neonatal_repetirprueba">
<span<?php echo $neonatal->repetirprueba->ViewAttributes() ?>>
<?php echo $neonatal->repetirprueba->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $neonatal->observaciones->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_observaciones" class="neonatal_observaciones">
<span<?php echo $neonatal->observaciones->ViewAttributes() ?>>
<?php echo $neonatal->observaciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->id_apoderado->Visible) { // id_apoderado ?>
		<td data-name="id_apoderado"<?php echo $neonatal->id_apoderado->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_id_apoderado" class="neonatal_id_apoderado">
<span<?php echo $neonatal->id_apoderado->ViewAttributes() ?>>
<?php echo $neonatal->id_apoderado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($neonatal->id_referencia->Visible) { // id_referencia ?>
		<td data-name="id_referencia"<?php echo $neonatal->id_referencia->CellAttributes() ?>>
<span id="el<?php echo $neonatal_list->RowCnt ?>_neonatal_id_referencia" class="neonatal_id_referencia">
<span<?php echo $neonatal->id_referencia->ViewAttributes() ?>>
<?php echo $neonatal->id_referencia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$neonatal_list->ListOptions->Render("body", "right", $neonatal_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($neonatal->CurrentAction <> "gridadd")
		$neonatal_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($neonatal->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($neonatal_list->Recordset)
	$neonatal_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($neonatal->CurrentAction <> "gridadd" && $neonatal->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($neonatal_list->Pager)) $neonatal_list->Pager = new cPrevNextPager($neonatal_list->StartRec, $neonatal_list->DisplayRecs, $neonatal_list->TotalRecs, $neonatal_list->AutoHidePager) ?>
<?php if ($neonatal_list->Pager->RecordCount > 0 && $neonatal_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($neonatal_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $neonatal_list->PageUrl() ?>start=<?php echo $neonatal_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($neonatal_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $neonatal_list->PageUrl() ?>start=<?php echo $neonatal_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $neonatal_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($neonatal_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $neonatal_list->PageUrl() ?>start=<?php echo $neonatal_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($neonatal_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $neonatal_list->PageUrl() ?>start=<?php echo $neonatal_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $neonatal_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($neonatal_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $neonatal_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $neonatal_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $neonatal_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($neonatal_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($neonatal_list->TotalRecs == 0 && $neonatal->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($neonatal_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fneonatallistsrch.FilterList = <?php echo $neonatal_list->GetFilterList() ?>;
fneonatallistsrch.Init();
fneonatallist.Init();
</script>
<?php
$neonatal_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$neonatal_list->Page_Terminate();
?>
