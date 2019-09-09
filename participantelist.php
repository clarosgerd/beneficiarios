<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "participanteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$participante_list = NULL; // Initialize page object first

class cparticipante_list extends cparticipante {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'participante';

	// Page object name
	var $PageObjName = 'participante_list';

	// Grid form hidden field names
	var $FormName = 'fparticipantelist';
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

		// Table object (participante)
		if (!isset($GLOBALS["participante"]) || get_class($GLOBALS["participante"]) == "cparticipante") {
			$GLOBALS["participante"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["participante"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "participanteadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "participantedelete.php";
		$this->MultiUpdateUrl = "participanteupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'participante', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fparticipantelistsrch";

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
		$this->id_actividad->SetVisibility();
		$this->id_categoria->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombre->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->ci->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->celular->SetVisibility();
		$this->direcciondomicilio->SetVisibility();
		$this->ocupacion->SetVisibility();
		$this->_email->SetVisibility();
		$this->cargo->SetVisibility();
		$this->nivelestudio->SetVisibility();
		$this->id_institucion->SetVisibility();
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
		global $EW_EXPORT, $participante;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($participante);
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
		$sFilterList = ew_Concat($sFilterList, $this->id_actividad->AdvancedSearch->ToJson(), ","); // Field id_actividad
		$sFilterList = ew_Concat($sFilterList, $this->id_categoria->AdvancedSearch->ToJson(), ","); // Field id_categoria
		$sFilterList = ew_Concat($sFilterList, $this->apellidopaterno->AdvancedSearch->ToJson(), ","); // Field apellidopaterno
		$sFilterList = ew_Concat($sFilterList, $this->apellidomaterno->AdvancedSearch->ToJson(), ","); // Field apellidomaterno
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJson(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->fecha_nacimiento->AdvancedSearch->ToJson(), ","); // Field fecha_nacimiento
		$sFilterList = ew_Concat($sFilterList, $this->sexo->AdvancedSearch->ToJson(), ","); // Field sexo
		$sFilterList = ew_Concat($sFilterList, $this->ci->AdvancedSearch->ToJson(), ","); // Field ci
		$sFilterList = ew_Concat($sFilterList, $this->nrodiscapacidad->AdvancedSearch->ToJson(), ","); // Field nrodiscapacidad
		$sFilterList = ew_Concat($sFilterList, $this->celular->AdvancedSearch->ToJson(), ","); // Field celular
		$sFilterList = ew_Concat($sFilterList, $this->direcciondomicilio->AdvancedSearch->ToJson(), ","); // Field direcciondomicilio
		$sFilterList = ew_Concat($sFilterList, $this->ocupacion->AdvancedSearch->ToJson(), ","); // Field ocupacion
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJson(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->cargo->AdvancedSearch->ToJson(), ","); // Field cargo
		$sFilterList = ew_Concat($sFilterList, $this->nivelestudio->AdvancedSearch->ToJson(), ","); // Field nivelestudio
		$sFilterList = ew_Concat($sFilterList, $this->id_institucion->AdvancedSearch->ToJson(), ","); // Field id_institucion
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fparticipantelistsrch", $filters);

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

		// Field id_actividad
		$this->id_actividad->AdvancedSearch->SearchValue = @$filter["x_id_actividad"];
		$this->id_actividad->AdvancedSearch->SearchOperator = @$filter["z_id_actividad"];
		$this->id_actividad->AdvancedSearch->SearchCondition = @$filter["v_id_actividad"];
		$this->id_actividad->AdvancedSearch->SearchValue2 = @$filter["y_id_actividad"];
		$this->id_actividad->AdvancedSearch->SearchOperator2 = @$filter["w_id_actividad"];
		$this->id_actividad->AdvancedSearch->Save();

		// Field id_categoria
		$this->id_categoria->AdvancedSearch->SearchValue = @$filter["x_id_categoria"];
		$this->id_categoria->AdvancedSearch->SearchOperator = @$filter["z_id_categoria"];
		$this->id_categoria->AdvancedSearch->SearchCondition = @$filter["v_id_categoria"];
		$this->id_categoria->AdvancedSearch->SearchValue2 = @$filter["y_id_categoria"];
		$this->id_categoria->AdvancedSearch->SearchOperator2 = @$filter["w_id_categoria"];
		$this->id_categoria->AdvancedSearch->Save();

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

		// Field ci
		$this->ci->AdvancedSearch->SearchValue = @$filter["x_ci"];
		$this->ci->AdvancedSearch->SearchOperator = @$filter["z_ci"];
		$this->ci->AdvancedSearch->SearchCondition = @$filter["v_ci"];
		$this->ci->AdvancedSearch->SearchValue2 = @$filter["y_ci"];
		$this->ci->AdvancedSearch->SearchOperator2 = @$filter["w_ci"];
		$this->ci->AdvancedSearch->Save();

		// Field nrodiscapacidad
		$this->nrodiscapacidad->AdvancedSearch->SearchValue = @$filter["x_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator = @$filter["z_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchCondition = @$filter["v_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchValue2 = @$filter["y_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator2 = @$filter["w_nrodiscapacidad"];
		$this->nrodiscapacidad->AdvancedSearch->Save();

		// Field celular
		$this->celular->AdvancedSearch->SearchValue = @$filter["x_celular"];
		$this->celular->AdvancedSearch->SearchOperator = @$filter["z_celular"];
		$this->celular->AdvancedSearch->SearchCondition = @$filter["v_celular"];
		$this->celular->AdvancedSearch->SearchValue2 = @$filter["y_celular"];
		$this->celular->AdvancedSearch->SearchOperator2 = @$filter["w_celular"];
		$this->celular->AdvancedSearch->Save();

		// Field direcciondomicilio
		$this->direcciondomicilio->AdvancedSearch->SearchValue = @$filter["x_direcciondomicilio"];
		$this->direcciondomicilio->AdvancedSearch->SearchOperator = @$filter["z_direcciondomicilio"];
		$this->direcciondomicilio->AdvancedSearch->SearchCondition = @$filter["v_direcciondomicilio"];
		$this->direcciondomicilio->AdvancedSearch->SearchValue2 = @$filter["y_direcciondomicilio"];
		$this->direcciondomicilio->AdvancedSearch->SearchOperator2 = @$filter["w_direcciondomicilio"];
		$this->direcciondomicilio->AdvancedSearch->Save();

		// Field ocupacion
		$this->ocupacion->AdvancedSearch->SearchValue = @$filter["x_ocupacion"];
		$this->ocupacion->AdvancedSearch->SearchOperator = @$filter["z_ocupacion"];
		$this->ocupacion->AdvancedSearch->SearchCondition = @$filter["v_ocupacion"];
		$this->ocupacion->AdvancedSearch->SearchValue2 = @$filter["y_ocupacion"];
		$this->ocupacion->AdvancedSearch->SearchOperator2 = @$filter["w_ocupacion"];
		$this->ocupacion->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field cargo
		$this->cargo->AdvancedSearch->SearchValue = @$filter["x_cargo"];
		$this->cargo->AdvancedSearch->SearchOperator = @$filter["z_cargo"];
		$this->cargo->AdvancedSearch->SearchCondition = @$filter["v_cargo"];
		$this->cargo->AdvancedSearch->SearchValue2 = @$filter["y_cargo"];
		$this->cargo->AdvancedSearch->SearchOperator2 = @$filter["w_cargo"];
		$this->cargo->AdvancedSearch->Save();

		// Field nivelestudio
		$this->nivelestudio->AdvancedSearch->SearchValue = @$filter["x_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->SearchOperator = @$filter["z_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->SearchCondition = @$filter["v_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->SearchValue2 = @$filter["y_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->SearchOperator2 = @$filter["w_nivelestudio"];
		$this->nivelestudio->AdvancedSearch->Save();

		// Field id_institucion
		$this->id_institucion->AdvancedSearch->SearchValue = @$filter["x_id_institucion"];
		$this->id_institucion->AdvancedSearch->SearchOperator = @$filter["z_id_institucion"];
		$this->id_institucion->AdvancedSearch->SearchCondition = @$filter["v_id_institucion"];
		$this->id_institucion->AdvancedSearch->SearchValue2 = @$filter["y_id_institucion"];
		$this->id_institucion->AdvancedSearch->SearchOperator2 = @$filter["w_id_institucion"];
		$this->id_institucion->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->id_actividad, $Default, FALSE); // id_actividad
		$this->BuildSearchSql($sWhere, $this->id_categoria, $Default, FALSE); // id_categoria
		$this->BuildSearchSql($sWhere, $this->apellidopaterno, $Default, FALSE); // apellidopaterno
		$this->BuildSearchSql($sWhere, $this->apellidomaterno, $Default, FALSE); // apellidomaterno
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->fecha_nacimiento, $Default, FALSE); // fecha_nacimiento
		$this->BuildSearchSql($sWhere, $this->sexo, $Default, FALSE); // sexo
		$this->BuildSearchSql($sWhere, $this->ci, $Default, FALSE); // ci
		$this->BuildSearchSql($sWhere, $this->nrodiscapacidad, $Default, FALSE); // nrodiscapacidad
		$this->BuildSearchSql($sWhere, $this->celular, $Default, FALSE); // celular
		$this->BuildSearchSql($sWhere, $this->direcciondomicilio, $Default, FALSE); // direcciondomicilio
		$this->BuildSearchSql($sWhere, $this->ocupacion, $Default, FALSE); // ocupacion
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->cargo, $Default, FALSE); // cargo
		$this->BuildSearchSql($sWhere, $this->nivelestudio, $Default, FALSE); // nivelestudio
		$this->BuildSearchSql($sWhere, $this->id_institucion, $Default, FALSE); // id_institucion
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->id_sector->AdvancedSearch->Save(); // id_sector
			$this->id_actividad->AdvancedSearch->Save(); // id_actividad
			$this->id_categoria->AdvancedSearch->Save(); // id_categoria
			$this->apellidopaterno->AdvancedSearch->Save(); // apellidopaterno
			$this->apellidomaterno->AdvancedSearch->Save(); // apellidomaterno
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->fecha_nacimiento->AdvancedSearch->Save(); // fecha_nacimiento
			$this->sexo->AdvancedSearch->Save(); // sexo
			$this->ci->AdvancedSearch->Save(); // ci
			$this->nrodiscapacidad->AdvancedSearch->Save(); // nrodiscapacidad
			$this->celular->AdvancedSearch->Save(); // celular
			$this->direcciondomicilio->AdvancedSearch->Save(); // direcciondomicilio
			$this->ocupacion->AdvancedSearch->Save(); // ocupacion
			$this->_email->AdvancedSearch->Save(); // email
			$this->cargo->AdvancedSearch->Save(); // cargo
			$this->nivelestudio->AdvancedSearch->Save(); // nivelestudio
			$this->id_institucion->AdvancedSearch->Save(); // id_institucion
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
		if ($this->id_actividad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_categoria->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidopaterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apellidomaterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_nacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ci->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nrodiscapacidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->celular->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direcciondomicilio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ocupacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cargo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nivelestudio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_institucion->AdvancedSearch->IssetSession())
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
		$this->id_actividad->AdvancedSearch->UnsetSession();
		$this->id_categoria->AdvancedSearch->UnsetSession();
		$this->apellidopaterno->AdvancedSearch->UnsetSession();
		$this->apellidomaterno->AdvancedSearch->UnsetSession();
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->fecha_nacimiento->AdvancedSearch->UnsetSession();
		$this->sexo->AdvancedSearch->UnsetSession();
		$this->ci->AdvancedSearch->UnsetSession();
		$this->nrodiscapacidad->AdvancedSearch->UnsetSession();
		$this->celular->AdvancedSearch->UnsetSession();
		$this->direcciondomicilio->AdvancedSearch->UnsetSession();
		$this->ocupacion->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->cargo->AdvancedSearch->UnsetSession();
		$this->nivelestudio->AdvancedSearch->UnsetSession();
		$this->id_institucion->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->id_sector->AdvancedSearch->Load();
		$this->id_actividad->AdvancedSearch->Load();
		$this->id_categoria->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->celular->AdvancedSearch->Load();
		$this->direcciondomicilio->AdvancedSearch->Load();
		$this->ocupacion->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->cargo->AdvancedSearch->Load();
		$this->nivelestudio->AdvancedSearch->Load();
		$this->id_institucion->AdvancedSearch->Load();
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
			$this->UpdateSort($this->id_actividad, $bCtrl); // id_actividad
			$this->UpdateSort($this->id_categoria, $bCtrl); // id_categoria
			$this->UpdateSort($this->apellidopaterno, $bCtrl); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno, $bCtrl); // apellidomaterno
			$this->UpdateSort($this->nombre, $bCtrl); // nombre
			$this->UpdateSort($this->fecha_nacimiento, $bCtrl); // fecha_nacimiento
			$this->UpdateSort($this->sexo, $bCtrl); // sexo
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->nrodiscapacidad, $bCtrl); // nrodiscapacidad
			$this->UpdateSort($this->celular, $bCtrl); // celular
			$this->UpdateSort($this->direcciondomicilio, $bCtrl); // direcciondomicilio
			$this->UpdateSort($this->ocupacion, $bCtrl); // ocupacion
			$this->UpdateSort($this->_email, $bCtrl); // email
			$this->UpdateSort($this->cargo, $bCtrl); // cargo
			$this->UpdateSort($this->nivelestudio, $bCtrl); // nivelestudio
			$this->UpdateSort($this->id_institucion, $bCtrl); // id_institucion
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
				$this->id->setSort("");
				$this->id_sector->setSort("");
				$this->id_actividad->setSort("");
				$this->id_categoria->setSort("");
				$this->apellidopaterno->setSort("");
				$this->apellidomaterno->setSort("");
				$this->nombre->setSort("");
				$this->fecha_nacimiento->setSort("");
				$this->sexo->setSort("");
				$this->ci->setSort("");
				$this->nrodiscapacidad->setSort("");
				$this->celular->setSort("");
				$this->direcciondomicilio->setSort("");
				$this->ocupacion->setSort("");
				$this->_email->setSort("");
				$this->cargo->setSort("");
				$this->nivelestudio->setSort("");
				$this->id_institucion->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fparticipantelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fparticipantelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fparticipantelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fparticipantelistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

		// id_actividad
		$this->id_actividad->AdvancedSearch->SearchValue = @$_GET["x_id_actividad"];
		if ($this->id_actividad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_actividad->AdvancedSearch->SearchOperator = @$_GET["z_id_actividad"];

		// id_categoria
		$this->id_categoria->AdvancedSearch->SearchValue = @$_GET["x_id_categoria"];
		if ($this->id_categoria->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_categoria->AdvancedSearch->SearchOperator = @$_GET["z_id_categoria"];
		if (is_array($this->id_categoria->AdvancedSearch->SearchValue)) $this->id_categoria->AdvancedSearch->SearchValue = implode(",", $this->id_categoria->AdvancedSearch->SearchValue);
		if (is_array($this->id_categoria->AdvancedSearch->SearchValue2)) $this->id_categoria->AdvancedSearch->SearchValue2 = implode(",", $this->id_categoria->AdvancedSearch->SearchValue2);

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

		// fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = @$_GET["x_fecha_nacimiento"];
		if ($this->fecha_nacimiento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = @$_GET["z_fecha_nacimiento"];

		// sexo
		$this->sexo->AdvancedSearch->SearchValue = @$_GET["x_sexo"];
		if ($this->sexo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sexo->AdvancedSearch->SearchOperator = @$_GET["z_sexo"];

		// ci
		$this->ci->AdvancedSearch->SearchValue = @$_GET["x_ci"];
		if ($this->ci->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->ci->AdvancedSearch->SearchOperator = @$_GET["z_ci"];

		// nrodiscapacidad
		$this->nrodiscapacidad->AdvancedSearch->SearchValue = @$_GET["x_nrodiscapacidad"];
		if ($this->nrodiscapacidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nrodiscapacidad->AdvancedSearch->SearchOperator = @$_GET["z_nrodiscapacidad"];

		// celular
		$this->celular->AdvancedSearch->SearchValue = @$_GET["x_celular"];
		if ($this->celular->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->celular->AdvancedSearch->SearchOperator = @$_GET["z_celular"];

		// direcciondomicilio
		$this->direcciondomicilio->AdvancedSearch->SearchValue = @$_GET["x_direcciondomicilio"];
		if ($this->direcciondomicilio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->direcciondomicilio->AdvancedSearch->SearchOperator = @$_GET["z_direcciondomicilio"];

		// ocupacion
		$this->ocupacion->AdvancedSearch->SearchValue = @$_GET["x_ocupacion"];
		if ($this->ocupacion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->ocupacion->AdvancedSearch->SearchOperator = @$_GET["z_ocupacion"];

		// email
		$this->_email->AdvancedSearch->SearchValue = @$_GET["x__email"];
		if ($this->_email->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->_email->AdvancedSearch->SearchOperator = @$_GET["z__email"];

		// cargo
		$this->cargo->AdvancedSearch->SearchValue = @$_GET["x_cargo"];
		if ($this->cargo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->cargo->AdvancedSearch->SearchOperator = @$_GET["z_cargo"];

		// nivelestudio
		$this->nivelestudio->AdvancedSearch->SearchValue = @$_GET["x_nivelestudio"];
		if ($this->nivelestudio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nivelestudio->AdvancedSearch->SearchOperator = @$_GET["z_nivelestudio"];

		// id_institucion
		$this->id_institucion->AdvancedSearch->SearchValue = @$_GET["x_id_institucion"];
		if ($this->id_institucion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_institucion->AdvancedSearch->SearchOperator = @$_GET["z_id_institucion"];

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
		$this->id_sector->setDbValue($row['id_sector']);
		$this->id_actividad->setDbValue($row['id_actividad']);
		$this->id_categoria->setDbValue($row['id_categoria']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->ci->setDbValue($row['ci']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->celular->setDbValue($row['celular']);
		$this->direcciondomicilio->setDbValue($row['direcciondomicilio']);
		$this->ocupacion->setDbValue($row['ocupacion']);
		$this->_email->setDbValue($row['email']);
		$this->cargo->setDbValue($row['cargo']);
		$this->nivelestudio->setDbValue($row['nivelestudio']);
		$this->id_institucion->setDbValue($row['id_institucion']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_sector'] = NULL;
		$row['id_actividad'] = NULL;
		$row['id_categoria'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombre'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['ci'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['celular'] = NULL;
		$row['direcciondomicilio'] = NULL;
		$row['ocupacion'] = NULL;
		$row['email'] = NULL;
		$row['cargo'] = NULL;
		$row['nivelestudio'] = NULL;
		$row['id_institucion'] = NULL;
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
		$this->id_actividad->DbValue = $row['id_actividad'];
		$this->id_categoria->DbValue = $row['id_categoria'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->ci->DbValue = $row['ci'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->celular->DbValue = $row['celular'];
		$this->direcciondomicilio->DbValue = $row['direcciondomicilio'];
		$this->ocupacion->DbValue = $row['ocupacion'];
		$this->_email->DbValue = $row['email'];
		$this->cargo->DbValue = $row['cargo'];
		$this->nivelestudio->DbValue = $row['nivelestudio'];
		$this->id_institucion->DbValue = $row['id_institucion'];
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
		// id_actividad
		// id_categoria
		// apellidopaterno
		// apellidomaterno
		// nombre
		// fecha_nacimiento
		// sexo
		// ci
		// nrodiscapacidad
		// celular
		// direcciondomicilio
		// ocupacion
		// email
		// cargo
		// nivelestudio
		// id_institucion
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

		// id_actividad
		if (strval($this->id_actividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
		$sWhereWrk = "";
		$this->id_actividad->LookupFilters = array("dx1" => '`nombreactividad`');
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

		// id_categoria
		if (strval($this->id_categoria->CurrentValue) <> "") {
			$arwrk = explode(",", $this->id_categoria->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
		$sWhereWrk = "";
		$this->id_categoria->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_categoria, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_categoria->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->id_categoria->ViewValue .= $this->id_categoria->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->id_categoria->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->id_categoria->ViewValue = $this->id_categoria->CurrentValue;
			}
		} else {
			$this->id_categoria->ViewValue = NULL;
		}
		$this->id_categoria->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 7);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// direcciondomicilio
		$this->direcciondomicilio->ViewValue = $this->direcciondomicilio->CurrentValue;
		$this->direcciondomicilio->ViewCustomAttributes = "";

		// ocupacion
		$this->ocupacion->ViewValue = $this->ocupacion->CurrentValue;
		$this->ocupacion->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// cargo
		$this->cargo->ViewValue = $this->cargo->CurrentValue;
		$this->cargo->ViewCustomAttributes = "";

		// nivelestudio
		$this->nivelestudio->ViewValue = $this->nivelestudio->CurrentValue;
		$this->nivelestudio->ViewCustomAttributes = "";

		// id_institucion
		$this->id_institucion->ViewCustomAttributes = "";

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

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";
			$this->id_actividad->TooltipValue = "";

			// id_categoria
			$this->id_categoria->LinkCustomAttributes = "";
			$this->id_categoria->HrefValue = "";
			$this->id_categoria->TooltipValue = "";

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

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";
			$this->sexo->TooltipValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
			$this->nrodiscapacidad->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// direcciondomicilio
			$this->direcciondomicilio->LinkCustomAttributes = "";
			$this->direcciondomicilio->HrefValue = "";
			$this->direcciondomicilio->TooltipValue = "";

			// ocupacion
			$this->ocupacion->LinkCustomAttributes = "";
			$this->ocupacion->HrefValue = "";
			$this->ocupacion->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// cargo
			$this->cargo->LinkCustomAttributes = "";
			$this->cargo->HrefValue = "";
			$this->cargo->TooltipValue = "";

			// nivelestudio
			$this->nivelestudio->LinkCustomAttributes = "";
			$this->nivelestudio->HrefValue = "";
			$this->nivelestudio->TooltipValue = "";

			// id_institucion
			$this->id_institucion->LinkCustomAttributes = "";
			$this->id_institucion->HrefValue = "";
			$this->id_institucion->TooltipValue = "";

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
			$this->id_sector->EditCustomAttributes = "";

			// id_actividad
			$this->id_actividad->EditAttrs["class"] = "form-control";
			$this->id_actividad->EditCustomAttributes = "";

			// id_categoria
			$this->id_categoria->EditCustomAttributes = "";

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

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_nacimiento->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = $this->sexo->Options(TRUE);

			// ci
			$this->ci->EditAttrs["class"] = "form-control";
			$this->ci->EditCustomAttributes = "";
			$this->ci->EditValue = ew_HtmlEncode($this->ci->AdvancedSearch->SearchValue);
			$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

			// nrodiscapacidad
			$this->nrodiscapacidad->EditAttrs["class"] = "form-control";
			$this->nrodiscapacidad->EditCustomAttributes = "";
			$this->nrodiscapacidad->EditValue = ew_HtmlEncode($this->nrodiscapacidad->AdvancedSearch->SearchValue);
			$this->nrodiscapacidad->PlaceHolder = ew_RemoveHtml($this->nrodiscapacidad->FldCaption());

			// celular
			$this->celular->EditAttrs["class"] = "form-control";
			$this->celular->EditCustomAttributes = "";
			$this->celular->EditValue = ew_HtmlEncode($this->celular->AdvancedSearch->SearchValue);
			$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

			// direcciondomicilio
			$this->direcciondomicilio->EditAttrs["class"] = "form-control";
			$this->direcciondomicilio->EditCustomAttributes = "";
			$this->direcciondomicilio->EditValue = ew_HtmlEncode($this->direcciondomicilio->AdvancedSearch->SearchValue);
			$this->direcciondomicilio->PlaceHolder = ew_RemoveHtml($this->direcciondomicilio->FldCaption());

			// ocupacion
			$this->ocupacion->EditAttrs["class"] = "form-control";
			$this->ocupacion->EditCustomAttributes = "";
			$this->ocupacion->EditValue = ew_HtmlEncode($this->ocupacion->AdvancedSearch->SearchValue);
			$this->ocupacion->PlaceHolder = ew_RemoveHtml($this->ocupacion->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->AdvancedSearch->SearchValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// cargo
			$this->cargo->EditAttrs["class"] = "form-control";
			$this->cargo->EditCustomAttributes = "";
			$this->cargo->EditValue = ew_HtmlEncode($this->cargo->AdvancedSearch->SearchValue);
			$this->cargo->PlaceHolder = ew_RemoveHtml($this->cargo->FldCaption());

			// nivelestudio
			$this->nivelestudio->EditAttrs["class"] = "form-control";
			$this->nivelestudio->EditCustomAttributes = "";
			$this->nivelestudio->EditValue = ew_HtmlEncode($this->nivelestudio->AdvancedSearch->SearchValue);
			$this->nivelestudio->PlaceHolder = ew_RemoveHtml($this->nivelestudio->FldCaption());

			// id_institucion
			$this->id_institucion->EditAttrs["class"] = "form-control";
			$this->id_institucion->EditCustomAttributes = "";

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
		$this->id_actividad->AdvancedSearch->Load();
		$this->id_categoria->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->sexo->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->nrodiscapacidad->AdvancedSearch->Load();
		$this->celular->AdvancedSearch->Load();
		$this->direcciondomicilio->AdvancedSearch->Load();
		$this->ocupacion->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->cargo->AdvancedSearch->Load();
		$this->nivelestudio->AdvancedSearch->Load();
		$this->id_institucion->AdvancedSearch->Load();
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
if (!isset($participante_list)) $participante_list = new cparticipante_list();

// Page init
$participante_list->Page_Init();

// Page main
$participante_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$participante_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fparticipantelist = new ew_Form("fparticipantelist", "list");
fparticipantelist.FormKeyCountName = '<?php echo $participante_list->FormKeyCountName ?>';

// Form_CustomValidate event
fparticipantelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fparticipantelist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fparticipantelist.Lists["x_id_sector"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sector"};
fparticipantelist.Lists["x_id_sector"].Data = "<?php echo $participante_list->id_sector->LookupFilterQuery(FALSE, "list") ?>";
fparticipantelist.Lists["x_id_actividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreactividad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"actividad"};
fparticipantelist.Lists["x_id_actividad"].Data = "<?php echo $participante_list->id_actividad->LookupFilterQuery(FALSE, "list") ?>";
fparticipantelist.Lists["x_id_categoria[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categoria"};
fparticipantelist.Lists["x_id_categoria[]"].Data = "<?php echo $participante_list->id_categoria->LookupFilterQuery(FALSE, "list") ?>";
fparticipantelist.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fparticipantelist.Lists["x_sexo"].Options = <?php echo json_encode($participante_list->sexo->Options()) ?>;

// Form object for search
var CurrentSearchForm = fparticipantelistsrch = new ew_Form("fparticipantelistsrch");

// Validate function for search
fparticipantelistsrch.Validate = function(fobj) {
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
fparticipantelistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fparticipantelistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fparticipantelistsrch.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fparticipantelistsrch.Lists["x_sexo"].Options = <?php echo json_encode($participante_list->sexo->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($participante_list->TotalRecs > 0 && $participante_list->ExportOptions->Visible()) { ?>
<?php $participante_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($participante_list->SearchOptions->Visible()) { ?>
<?php $participante_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($participante_list->FilterOptions->Visible()) { ?>
<?php $participante_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $participante_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($participante_list->TotalRecs <= 0)
			$participante_list->TotalRecs = $participante->ListRecordCount();
	} else {
		if (!$participante_list->Recordset && ($participante_list->Recordset = $participante_list->LoadRecordset()))
			$participante_list->TotalRecs = $participante_list->Recordset->RecordCount();
	}
	$participante_list->StartRec = 1;
	if ($participante_list->DisplayRecs <= 0 || ($participante->Export <> "" && $participante->ExportAll)) // Display all records
		$participante_list->DisplayRecs = $participante_list->TotalRecs;
	if (!($participante->Export <> "" && $participante->ExportAll))
		$participante_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$participante_list->Recordset = $participante_list->LoadRecordset($participante_list->StartRec-1, $participante_list->DisplayRecs);

	// Set no record found message
	if ($participante->CurrentAction == "" && $participante_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$participante_list->setWarningMessage(ew_DeniedMsg());
		if ($participante_list->SearchWhere == "0=101")
			$participante_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$participante_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$participante_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($participante->Export == "" && $participante->CurrentAction == "") { ?>
<form name="fparticipantelistsrch" id="fparticipantelistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($participante_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fparticipantelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="participante">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$participante_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$participante->RowType = EW_ROWTYPE_SEARCH;

// Render row
$participante->ResetAttrs();
$participante_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($participante->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="xsc_apellidopaterno" class="ewCell form-group">
		<label for="x_apellidopaterno" class="ewSearchCaption ewLabel"><?php echo $participante->apellidopaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidopaterno" id="z_apellidopaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="participante" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $participante->apellidopaterno->EditValue ?>"<?php echo $participante->apellidopaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($participante->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="xsc_apellidomaterno" class="ewCell form-group">
		<label for="x_apellidomaterno" class="ewSearchCaption ewLabel"><?php echo $participante->apellidomaterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidomaterno" id="z_apellidomaterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="participante" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $participante->apellidomaterno->EditValue ?>"<?php echo $participante->apellidomaterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($participante->nombre->Visible) { // nombre ?>
	<div id="xsc_nombre" class="ewCell form-group">
		<label for="x_nombre" class="ewSearchCaption ewLabel"><?php echo $participante->nombre->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombre" id="z_nombre" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="participante" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->nombre->getPlaceHolder()) ?>" value="<?php echo $participante->nombre->EditValue ?>"<?php echo $participante->nombre->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($participante->sexo->Visible) { // sexo ?>
	<div id="xsc_sexo" class="ewCell form-group">
		<label for="x_sexo" class="ewSearchCaption ewLabel"><?php echo $participante->sexo->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_sexo" id="z_sexo" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="participante" data-field="x_sexo" data-value-separator="<?php echo $participante->sexo->DisplayValueSeparatorAttribute() ?>" id="x_sexo" name="x_sexo"<?php echo $participante->sexo->EditAttributes() ?>>
<?php echo $participante->sexo->SelectOptionListHtml("x_sexo") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($participante->ci->Visible) { // ci ?>
	<div id="xsc_ci" class="ewCell form-group">
		<label for="x_ci" class="ewSearchCaption ewLabel"><?php echo $participante->ci->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ci" id="z_ci" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="participante" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->ci->getPlaceHolder()) ?>" value="<?php echo $participante->ci->EditValue ?>"<?php echo $participante->ci->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($participante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<div id="xsc_nrodiscapacidad" class="ewCell form-group">
		<label for="x_nrodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $participante->nrodiscapacidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nrodiscapacidad" id="z_nrodiscapacidad" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="participante" data-field="x_nrodiscapacidad" name="x_nrodiscapacidad" id="x_nrodiscapacidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($participante->nrodiscapacidad->getPlaceHolder()) ?>" value="<?php echo $participante->nrodiscapacidad->EditValue ?>"<?php echo $participante->nrodiscapacidad->EditAttributes() ?>>
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
<?php $participante_list->ShowPageHeader(); ?>
<?php
$participante_list->ShowMessage();
?>
<?php if ($participante_list->TotalRecs > 0 || $participante->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($participante_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> participante">
<div class="box-header ewGridUpperPanel">
<?php if ($participante->CurrentAction <> "gridadd" && $participante->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($participante_list->Pager)) $participante_list->Pager = new cPrevNextPager($participante_list->StartRec, $participante_list->DisplayRecs, $participante_list->TotalRecs, $participante_list->AutoHidePager) ?>
<?php if ($participante_list->Pager->RecordCount > 0 && $participante_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($participante_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $participante_list->PageUrl() ?>start=<?php echo $participante_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($participante_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $participante_list->PageUrl() ?>start=<?php echo $participante_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $participante_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($participante_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $participante_list->PageUrl() ?>start=<?php echo $participante_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($participante_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $participante_list->PageUrl() ?>start=<?php echo $participante_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $participante_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($participante_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $participante_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $participante_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $participante_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($participante_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fparticipantelist" id="fparticipantelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($participante_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $participante_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="participante">
<div id="gmp_participante" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($participante_list->TotalRecs > 0 || $participante->CurrentAction == "gridedit") { ?>
<table id="tbl_participantelist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$participante_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$participante_list->RenderListOptions();

// Render list options (header, left)
$participante_list->ListOptions->Render("header", "left");
?>
<?php if ($participante->id->Visible) { // id ?>
	<?php if ($participante->SortUrl($participante->id) == "") { ?>
		<th data-name="id" class="<?php echo $participante->id->HeaderCellClass() ?>"><div id="elh_participante_id" class="participante_id"><div class="ewTableHeaderCaption"><?php echo $participante->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $participante->id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->id) ?>',2);"><div id="elh_participante_id" class="participante_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->id_sector->Visible) { // id_sector ?>
	<?php if ($participante->SortUrl($participante->id_sector) == "") { ?>
		<th data-name="id_sector" class="<?php echo $participante->id_sector->HeaderCellClass() ?>"><div id="elh_participante_id_sector" class="participante_id_sector"><div class="ewTableHeaderCaption"><?php echo $participante->id_sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_sector" class="<?php echo $participante->id_sector->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->id_sector) ?>',2);"><div id="elh_participante_id_sector" class="participante_id_sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->id_sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->id_sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->id_sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->id_actividad->Visible) { // id_actividad ?>
	<?php if ($participante->SortUrl($participante->id_actividad) == "") { ?>
		<th data-name="id_actividad" class="<?php echo $participante->id_actividad->HeaderCellClass() ?>"><div id="elh_participante_id_actividad" class="participante_id_actividad"><div class="ewTableHeaderCaption"><?php echo $participante->id_actividad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_actividad" class="<?php echo $participante->id_actividad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->id_actividad) ?>',2);"><div id="elh_participante_id_actividad" class="participante_id_actividad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->id_actividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->id_actividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->id_actividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->id_categoria->Visible) { // id_categoria ?>
	<?php if ($participante->SortUrl($participante->id_categoria) == "") { ?>
		<th data-name="id_categoria" class="<?php echo $participante->id_categoria->HeaderCellClass() ?>"><div id="elh_participante_id_categoria" class="participante_id_categoria"><div class="ewTableHeaderCaption"><?php echo $participante->id_categoria->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_categoria" class="<?php echo $participante->id_categoria->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->id_categoria) ?>',2);"><div id="elh_participante_id_categoria" class="participante_id_categoria">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->id_categoria->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->id_categoria->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->id_categoria->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->apellidopaterno->Visible) { // apellidopaterno ?>
	<?php if ($participante->SortUrl($participante->apellidopaterno) == "") { ?>
		<th data-name="apellidopaterno" class="<?php echo $participante->apellidopaterno->HeaderCellClass() ?>"><div id="elh_participante_apellidopaterno" class="participante_apellidopaterno"><div class="ewTableHeaderCaption"><?php echo $participante->apellidopaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidopaterno" class="<?php echo $participante->apellidopaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->apellidopaterno) ?>',2);"><div id="elh_participante_apellidopaterno" class="participante_apellidopaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->apellidopaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->apellidomaterno->Visible) { // apellidomaterno ?>
	<?php if ($participante->SortUrl($participante->apellidomaterno) == "") { ?>
		<th data-name="apellidomaterno" class="<?php echo $participante->apellidomaterno->HeaderCellClass() ?>"><div id="elh_participante_apellidomaterno" class="participante_apellidomaterno"><div class="ewTableHeaderCaption"><?php echo $participante->apellidomaterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellidomaterno" class="<?php echo $participante->apellidomaterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->apellidomaterno) ?>',2);"><div id="elh_participante_apellidomaterno" class="participante_apellidomaterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->apellidomaterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->nombre->Visible) { // nombre ?>
	<?php if ($participante->SortUrl($participante->nombre) == "") { ?>
		<th data-name="nombre" class="<?php echo $participante->nombre->HeaderCellClass() ?>"><div id="elh_participante_nombre" class="participante_nombre"><div class="ewTableHeaderCaption"><?php echo $participante->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre" class="<?php echo $participante->nombre->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->nombre) ?>',2);"><div id="elh_participante_nombre" class="participante_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<?php if ($participante->SortUrl($participante->fecha_nacimiento) == "") { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $participante->fecha_nacimiento->HeaderCellClass() ?>"><div id="elh_participante_fecha_nacimiento" class="participante_fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $participante->fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $participante->fecha_nacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->fecha_nacimiento) ?>',2);"><div id="elh_participante_fecha_nacimiento" class="participante_fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->sexo->Visible) { // sexo ?>
	<?php if ($participante->SortUrl($participante->sexo) == "") { ?>
		<th data-name="sexo" class="<?php echo $participante->sexo->HeaderCellClass() ?>"><div id="elh_participante_sexo" class="participante_sexo"><div class="ewTableHeaderCaption"><?php echo $participante->sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sexo" class="<?php echo $participante->sexo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->sexo) ?>',2);"><div id="elh_participante_sexo" class="participante_sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->ci->Visible) { // ci ?>
	<?php if ($participante->SortUrl($participante->ci) == "") { ?>
		<th data-name="ci" class="<?php echo $participante->ci->HeaderCellClass() ?>"><div id="elh_participante_ci" class="participante_ci"><div class="ewTableHeaderCaption"><?php echo $participante->ci->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ci" class="<?php echo $participante->ci->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->ci) ?>',2);"><div id="elh_participante_ci" class="participante_ci">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->ci->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
	<?php if ($participante->SortUrl($participante->nrodiscapacidad) == "") { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $participante->nrodiscapacidad->HeaderCellClass() ?>"><div id="elh_participante_nrodiscapacidad" class="participante_nrodiscapacidad"><div class="ewTableHeaderCaption"><?php echo $participante->nrodiscapacidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nrodiscapacidad" class="<?php echo $participante->nrodiscapacidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->nrodiscapacidad) ?>',2);"><div id="elh_participante_nrodiscapacidad" class="participante_nrodiscapacidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->nrodiscapacidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->celular->Visible) { // celular ?>
	<?php if ($participante->SortUrl($participante->celular) == "") { ?>
		<th data-name="celular" class="<?php echo $participante->celular->HeaderCellClass() ?>"><div id="elh_participante_celular" class="participante_celular"><div class="ewTableHeaderCaption"><?php echo $participante->celular->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="celular" class="<?php echo $participante->celular->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->celular) ?>',2);"><div id="elh_participante_celular" class="participante_celular">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->celular->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->celular->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->celular->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->direcciondomicilio->Visible) { // direcciondomicilio ?>
	<?php if ($participante->SortUrl($participante->direcciondomicilio) == "") { ?>
		<th data-name="direcciondomicilio" class="<?php echo $participante->direcciondomicilio->HeaderCellClass() ?>"><div id="elh_participante_direcciondomicilio" class="participante_direcciondomicilio"><div class="ewTableHeaderCaption"><?php echo $participante->direcciondomicilio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direcciondomicilio" class="<?php echo $participante->direcciondomicilio->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->direcciondomicilio) ?>',2);"><div id="elh_participante_direcciondomicilio" class="participante_direcciondomicilio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->direcciondomicilio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->direcciondomicilio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->direcciondomicilio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->ocupacion->Visible) { // ocupacion ?>
	<?php if ($participante->SortUrl($participante->ocupacion) == "") { ?>
		<th data-name="ocupacion" class="<?php echo $participante->ocupacion->HeaderCellClass() ?>"><div id="elh_participante_ocupacion" class="participante_ocupacion"><div class="ewTableHeaderCaption"><?php echo $participante->ocupacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ocupacion" class="<?php echo $participante->ocupacion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->ocupacion) ?>',2);"><div id="elh_participante_ocupacion" class="participante_ocupacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->ocupacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->ocupacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->ocupacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->_email->Visible) { // email ?>
	<?php if ($participante->SortUrl($participante->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $participante->_email->HeaderCellClass() ?>"><div id="elh_participante__email" class="participante__email"><div class="ewTableHeaderCaption"><?php echo $participante->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $participante->_email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->_email) ?>',2);"><div id="elh_participante__email" class="participante__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->_email->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->cargo->Visible) { // cargo ?>
	<?php if ($participante->SortUrl($participante->cargo) == "") { ?>
		<th data-name="cargo" class="<?php echo $participante->cargo->HeaderCellClass() ?>"><div id="elh_participante_cargo" class="participante_cargo"><div class="ewTableHeaderCaption"><?php echo $participante->cargo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cargo" class="<?php echo $participante->cargo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->cargo) ?>',2);"><div id="elh_participante_cargo" class="participante_cargo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->cargo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->cargo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->cargo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->nivelestudio->Visible) { // nivelestudio ?>
	<?php if ($participante->SortUrl($participante->nivelestudio) == "") { ?>
		<th data-name="nivelestudio" class="<?php echo $participante->nivelestudio->HeaderCellClass() ?>"><div id="elh_participante_nivelestudio" class="participante_nivelestudio"><div class="ewTableHeaderCaption"><?php echo $participante->nivelestudio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nivelestudio" class="<?php echo $participante->nivelestudio->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->nivelestudio) ?>',2);"><div id="elh_participante_nivelestudio" class="participante_nivelestudio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->nivelestudio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->nivelestudio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->nivelestudio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->id_institucion->Visible) { // id_institucion ?>
	<?php if ($participante->SortUrl($participante->id_institucion) == "") { ?>
		<th data-name="id_institucion" class="<?php echo $participante->id_institucion->HeaderCellClass() ?>"><div id="elh_participante_id_institucion" class="participante_id_institucion"><div class="ewTableHeaderCaption"><?php echo $participante->id_institucion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_institucion" class="<?php echo $participante->id_institucion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->id_institucion) ?>',2);"><div id="elh_participante_id_institucion" class="participante_id_institucion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->id_institucion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->id_institucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->id_institucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($participante->observaciones->Visible) { // observaciones ?>
	<?php if ($participante->SortUrl($participante->observaciones) == "") { ?>
		<th data-name="observaciones" class="<?php echo $participante->observaciones->HeaderCellClass() ?>"><div id="elh_participante_observaciones" class="participante_observaciones"><div class="ewTableHeaderCaption"><?php echo $participante->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones" class="<?php echo $participante->observaciones->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $participante->SortUrl($participante->observaciones) ?>',2);"><div id="elh_participante_observaciones" class="participante_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $participante->observaciones->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($participante->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($participante->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$participante_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($participante->ExportAll && $participante->Export <> "") {
	$participante_list->StopRec = $participante_list->TotalRecs;
} else {

	// Set the last record to display
	if ($participante_list->TotalRecs > $participante_list->StartRec + $participante_list->DisplayRecs - 1)
		$participante_list->StopRec = $participante_list->StartRec + $participante_list->DisplayRecs - 1;
	else
		$participante_list->StopRec = $participante_list->TotalRecs;
}
$participante_list->RecCnt = $participante_list->StartRec - 1;
if ($participante_list->Recordset && !$participante_list->Recordset->EOF) {
	$participante_list->Recordset->MoveFirst();
	$bSelectLimit = $participante_list->UseSelectLimit;
	if (!$bSelectLimit && $participante_list->StartRec > 1)
		$participante_list->Recordset->Move($participante_list->StartRec - 1);
} elseif (!$participante->AllowAddDeleteRow && $participante_list->StopRec == 0) {
	$participante_list->StopRec = $participante->GridAddRowCount;
}

// Initialize aggregate
$participante->RowType = EW_ROWTYPE_AGGREGATEINIT;
$participante->ResetAttrs();
$participante_list->RenderRow();
while ($participante_list->RecCnt < $participante_list->StopRec) {
	$participante_list->RecCnt++;
	if (intval($participante_list->RecCnt) >= intval($participante_list->StartRec)) {
		$participante_list->RowCnt++;

		// Set up key count
		$participante_list->KeyCount = $participante_list->RowIndex;

		// Init row class and style
		$participante->ResetAttrs();
		$participante->CssClass = "";
		if ($participante->CurrentAction == "gridadd") {
		} else {
			$participante_list->LoadRowValues($participante_list->Recordset); // Load row values
		}
		$participante->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$participante->RowAttrs = array_merge($participante->RowAttrs, array('data-rowindex'=>$participante_list->RowCnt, 'id'=>'r' . $participante_list->RowCnt . '_participante', 'data-rowtype'=>$participante->RowType));

		// Render row
		$participante_list->RenderRow();

		// Render list options
		$participante_list->RenderListOptions();
?>
	<tr<?php echo $participante->RowAttributes() ?>>
<?php

// Render list options (body, left)
$participante_list->ListOptions->Render("body", "left", $participante_list->RowCnt);
?>
	<?php if ($participante->id->Visible) { // id ?>
		<td data-name="id"<?php echo $participante->id->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_id" class="participante_id">
<span<?php echo $participante->id->ViewAttributes() ?>>
<?php echo $participante->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->id_sector->Visible) { // id_sector ?>
		<td data-name="id_sector"<?php echo $participante->id_sector->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_id_sector" class="participante_id_sector">
<span<?php echo $participante->id_sector->ViewAttributes() ?>>
<?php echo $participante->id_sector->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->id_actividad->Visible) { // id_actividad ?>
		<td data-name="id_actividad"<?php echo $participante->id_actividad->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_id_actividad" class="participante_id_actividad">
<span<?php echo $participante->id_actividad->ViewAttributes() ?>>
<?php echo $participante->id_actividad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->id_categoria->Visible) { // id_categoria ?>
		<td data-name="id_categoria"<?php echo $participante->id_categoria->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_id_categoria" class="participante_id_categoria">
<span<?php echo $participante->id_categoria->ViewAttributes() ?>>
<?php echo $participante->id_categoria->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->apellidopaterno->Visible) { // apellidopaterno ?>
		<td data-name="apellidopaterno"<?php echo $participante->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_apellidopaterno" class="participante_apellidopaterno">
<span<?php echo $participante->apellidopaterno->ViewAttributes() ?>>
<?php echo $participante->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->apellidomaterno->Visible) { // apellidomaterno ?>
		<td data-name="apellidomaterno"<?php echo $participante->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_apellidomaterno" class="participante_apellidomaterno">
<span<?php echo $participante->apellidomaterno->ViewAttributes() ?>>
<?php echo $participante->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $participante->nombre->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_nombre" class="participante_nombre">
<span<?php echo $participante->nombre->ViewAttributes() ?>>
<?php echo $participante->nombre->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento"<?php echo $participante->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_fecha_nacimiento" class="participante_fecha_nacimiento">
<span<?php echo $participante->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $participante->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->sexo->Visible) { // sexo ?>
		<td data-name="sexo"<?php echo $participante->sexo->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_sexo" class="participante_sexo">
<span<?php echo $participante->sexo->ViewAttributes() ?>>
<?php echo $participante->sexo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->ci->Visible) { // ci ?>
		<td data-name="ci"<?php echo $participante->ci->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_ci" class="participante_ci">
<span<?php echo $participante->ci->ViewAttributes() ?>>
<?php echo $participante->ci->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td data-name="nrodiscapacidad"<?php echo $participante->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_nrodiscapacidad" class="participante_nrodiscapacidad">
<span<?php echo $participante->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $participante->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->celular->Visible) { // celular ?>
		<td data-name="celular"<?php echo $participante->celular->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_celular" class="participante_celular">
<span<?php echo $participante->celular->ViewAttributes() ?>>
<?php echo $participante->celular->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->direcciondomicilio->Visible) { // direcciondomicilio ?>
		<td data-name="direcciondomicilio"<?php echo $participante->direcciondomicilio->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_direcciondomicilio" class="participante_direcciondomicilio">
<span<?php echo $participante->direcciondomicilio->ViewAttributes() ?>>
<?php echo $participante->direcciondomicilio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->ocupacion->Visible) { // ocupacion ?>
		<td data-name="ocupacion"<?php echo $participante->ocupacion->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_ocupacion" class="participante_ocupacion">
<span<?php echo $participante->ocupacion->ViewAttributes() ?>>
<?php echo $participante->ocupacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $participante->_email->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante__email" class="participante__email">
<span<?php echo $participante->_email->ViewAttributes() ?>>
<?php echo $participante->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->cargo->Visible) { // cargo ?>
		<td data-name="cargo"<?php echo $participante->cargo->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_cargo" class="participante_cargo">
<span<?php echo $participante->cargo->ViewAttributes() ?>>
<?php echo $participante->cargo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->nivelestudio->Visible) { // nivelestudio ?>
		<td data-name="nivelestudio"<?php echo $participante->nivelestudio->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_nivelestudio" class="participante_nivelestudio">
<span<?php echo $participante->nivelestudio->ViewAttributes() ?>>
<?php echo $participante->nivelestudio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->id_institucion->Visible) { // id_institucion ?>
		<td data-name="id_institucion"<?php echo $participante->id_institucion->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_id_institucion" class="participante_id_institucion">
<span<?php echo $participante->id_institucion->ViewAttributes() ?>>
<?php echo $participante->id_institucion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($participante->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $participante->observaciones->CellAttributes() ?>>
<span id="el<?php echo $participante_list->RowCnt ?>_participante_observaciones" class="participante_observaciones">
<span<?php echo $participante->observaciones->ViewAttributes() ?>>
<?php echo $participante->observaciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$participante_list->ListOptions->Render("body", "right", $participante_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($participante->CurrentAction <> "gridadd")
		$participante_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($participante->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($participante_list->Recordset)
	$participante_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($participante->CurrentAction <> "gridadd" && $participante->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($participante_list->Pager)) $participante_list->Pager = new cPrevNextPager($participante_list->StartRec, $participante_list->DisplayRecs, $participante_list->TotalRecs, $participante_list->AutoHidePager) ?>
<?php if ($participante_list->Pager->RecordCount > 0 && $participante_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($participante_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $participante_list->PageUrl() ?>start=<?php echo $participante_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($participante_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $participante_list->PageUrl() ?>start=<?php echo $participante_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $participante_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($participante_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $participante_list->PageUrl() ?>start=<?php echo $participante_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($participante_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $participante_list->PageUrl() ?>start=<?php echo $participante_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $participante_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($participante_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $participante_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $participante_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $participante_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($participante_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($participante_list->TotalRecs == 0 && $participante->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($participante_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fparticipantelistsrch.FilterList = <?php echo $participante_list->GetFilterList() ?>;
fparticipantelistsrch.Init();
fparticipantelist.Init();
</script>
<?php
$participante_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$participante_list->Page_Terminate();
?>
