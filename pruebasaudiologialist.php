<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "pruebasaudiologiainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "audiologiainfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$pruebasaudiologia_list = NULL; // Initialize page object first

class cpruebasaudiologia_list extends cpruebasaudiologia {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'pruebasaudiologia';

	// Page object name
	var $PageObjName = 'pruebasaudiologia_list';

	// Grid form hidden field names
	var $FormName = 'fpruebasaudiologialist';
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

		// Table object (pruebasaudiologia)
		if (!isset($GLOBALS["pruebasaudiologia"]) || get_class($GLOBALS["pruebasaudiologia"]) == "cpruebasaudiologia") {
			$GLOBALS["pruebasaudiologia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pruebasaudiologia"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "pruebasaudiologiaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "pruebasaudiologiadelete.php";
		$this->MultiUpdateUrl = "pruebasaudiologiaupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (audiologia)
		if (!isset($GLOBALS['audiologia'])) $GLOBALS['audiologia'] = new caudiologia();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pruebasaudiologia', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fpruebasaudiologialistsrch";

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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->id_tipopruebasaudiologia->SetVisibility();
		$this->resultado->SetVisibility();
		$this->recomendacion->SetVisibility();

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

		// Set up master detail parameters
		$this->SetupMasterParms();

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
		global $EW_EXPORT, $pruebasaudiologia;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pruebasaudiologia);
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				}
			}

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

			// Set up sorting order
			$this->SetupSortOrder();
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

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "audiologia") {
			global $audiologia;
			$rsmaster = $audiologia->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("audiologialist.php"); // Return to master page
			} else {
				$audiologia->LoadListRowValues($rsmaster);
				$audiologia->RowType = EW_ROWTYPE_MASTER; // Master row
				$audiologia->RenderListRow();
				$rsmaster->Close();
			}
		}

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

	// Exit inline mode
	function ClearInlineMode() {
		$this->setKey("id", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (isset($_GET["id"])) {
			$this->id->setQueryStringValue($_GET["id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id", $this->id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1;
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("id")) <> strval($this->id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		$this->CurrentAction = "add";
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old record
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
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

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_tipopruebasaudiologia, $bCtrl); // id_tipopruebasaudiologia
			$this->UpdateSort($this->resultado, $bCtrl); // resultado
			$this->UpdateSort($this->recomendacion, $bCtrl); // recomendacion
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->id_audiologia->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id_tipopruebasaudiologia->setSort("");
				$this->resultado->setSort("");
				$this->recomendacion->setSort("");
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

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanAdd() && ($this->CurrentAction == "add");
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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_UrlAddHash($this->PageName(), "r" . $this->RowCnt . "_" . $this->TableVar) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\">";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_UrlAddHash($this->InlineEditUrl, "r" . $this->RowCnt . "_" . $this->TableVar)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
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

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fpruebasaudiologialistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fpruebasaudiologialistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = FALSE;
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fpruebasaudiologialist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->id_tipopruebasaudiologia->CurrentValue = NULL;
		$this->id_tipopruebasaudiologia->OldValue = $this->id_tipopruebasaudiologia->CurrentValue;
		$this->resultado->CurrentValue = NULL;
		$this->resultado->OldValue = $this->resultado->CurrentValue;
		$this->recomendacion->CurrentValue = NULL;
		$this->recomendacion->OldValue = $this->recomendacion->CurrentValue;
		$this->id_audiologia->CurrentValue = NULL;
		$this->id_audiologia->OldValue = $this->id_audiologia->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_tipopruebasaudiologia->FldIsDetailKey) {
			$this->id_tipopruebasaudiologia->setFormValue($objForm->GetValue("x_id_tipopruebasaudiologia"));
		}
		if (!$this->resultado->FldIsDetailKey) {
			$this->resultado->setFormValue($objForm->GetValue("x_resultado"));
		}
		if (!$this->recomendacion->FldIsDetailKey) {
			$this->recomendacion->setFormValue($objForm->GetValue("x_recomendacion"));
		}
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->id_tipopruebasaudiologia->CurrentValue = $this->id_tipopruebasaudiologia->FormValue;
		$this->resultado->CurrentValue = $this->resultado->FormValue;
		$this->recomendacion->CurrentValue = $this->recomendacion->FormValue;
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
		$this->id_tipopruebasaudiologia->setDbValue($row['id_tipopruebasaudiologia']);
		$this->resultado->setDbValue($row['resultado']);
		$this->recomendacion->setDbValue($row['recomendacion']);
		$this->id_audiologia->setDbValue($row['id_audiologia']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['id_tipopruebasaudiologia'] = $this->id_tipopruebasaudiologia->CurrentValue;
		$row['resultado'] = $this->resultado->CurrentValue;
		$row['recomendacion'] = $this->recomendacion->CurrentValue;
		$row['id_audiologia'] = $this->id_audiologia->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_tipopruebasaudiologia->DbValue = $row['id_tipopruebasaudiologia'];
		$this->resultado->DbValue = $row['resultado'];
		$this->recomendacion->DbValue = $row['recomendacion'];
		$this->id_audiologia->DbValue = $row['id_audiologia'];
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

		// id_tipopruebasaudiologia
		// resultado
		// recomendacion
		// id_audiologia

		$this->id_audiologia->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_tipopruebasaudiologia
		if (strval($this->id_tipopruebasaudiologia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipopruebasaudiologia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipopruebasaudiologia`";
		$sWhereWrk = "";
		$this->id_tipopruebasaudiologia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipopruebasaudiologia, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipopruebasaudiologia->ViewValue = $this->id_tipopruebasaudiologia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipopruebasaudiologia->ViewValue = $this->id_tipopruebasaudiologia->CurrentValue;
			}
		} else {
			$this->id_tipopruebasaudiologia->ViewValue = NULL;
		}
		$this->id_tipopruebasaudiologia->ViewCustomAttributes = "";

		// resultado
		$this->resultado->ViewValue = $this->resultado->CurrentValue;
		$this->resultado->ViewCustomAttributes = "";

		// recomendacion
		$this->recomendacion->ViewValue = $this->recomendacion->CurrentValue;
		$this->recomendacion->ViewCustomAttributes = "";

		// id_audiologia
		$this->id_audiologia->ViewValue = $this->id_audiologia->CurrentValue;
		$this->id_audiologia->ViewCustomAttributes = "";

			// id_tipopruebasaudiologia
			$this->id_tipopruebasaudiologia->LinkCustomAttributes = "";
			$this->id_tipopruebasaudiologia->HrefValue = "";
			$this->id_tipopruebasaudiologia->TooltipValue = "";

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";
			$this->resultado->TooltipValue = "";

			// recomendacion
			$this->recomendacion->LinkCustomAttributes = "";
			$this->recomendacion->HrefValue = "";
			$this->recomendacion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_tipopruebasaudiologia
			$this->id_tipopruebasaudiologia->EditAttrs["class"] = "form-control";
			$this->id_tipopruebasaudiologia->EditCustomAttributes = "";
			if (trim(strval($this->id_tipopruebasaudiologia->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipopruebasaudiologia->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipopruebasaudiologia`";
			$sWhereWrk = "";
			$this->id_tipopruebasaudiologia->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_tipopruebasaudiologia, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_tipopruebasaudiologia->EditValue = $arwrk;

			// resultado
			$this->resultado->EditAttrs["class"] = "form-control";
			$this->resultado->EditCustomAttributes = "";
			$this->resultado->EditValue = ew_HtmlEncode($this->resultado->CurrentValue);
			$this->resultado->PlaceHolder = ew_RemoveHtml($this->resultado->FldCaption());

			// recomendacion
			$this->recomendacion->EditAttrs["class"] = "form-control";
			$this->recomendacion->EditCustomAttributes = "";
			$this->recomendacion->EditValue = ew_HtmlEncode($this->recomendacion->CurrentValue);
			$this->recomendacion->PlaceHolder = ew_RemoveHtml($this->recomendacion->FldCaption());

			// Add refer script
			// id_tipopruebasaudiologia

			$this->id_tipopruebasaudiologia->LinkCustomAttributes = "";
			$this->id_tipopruebasaudiologia->HrefValue = "";

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";

			// recomendacion
			$this->recomendacion->LinkCustomAttributes = "";
			$this->recomendacion->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_tipopruebasaudiologia
			$this->id_tipopruebasaudiologia->EditAttrs["class"] = "form-control";
			$this->id_tipopruebasaudiologia->EditCustomAttributes = "";
			if (trim(strval($this->id_tipopruebasaudiologia->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipopruebasaudiologia->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipopruebasaudiologia`";
			$sWhereWrk = "";
			$this->id_tipopruebasaudiologia->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_tipopruebasaudiologia, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_tipopruebasaudiologia->EditValue = $arwrk;

			// resultado
			$this->resultado->EditAttrs["class"] = "form-control";
			$this->resultado->EditCustomAttributes = "";
			$this->resultado->EditValue = ew_HtmlEncode($this->resultado->CurrentValue);
			$this->resultado->PlaceHolder = ew_RemoveHtml($this->resultado->FldCaption());

			// recomendacion
			$this->recomendacion->EditAttrs["class"] = "form-control";
			$this->recomendacion->EditCustomAttributes = "";
			$this->recomendacion->EditValue = ew_HtmlEncode($this->recomendacion->CurrentValue);
			$this->recomendacion->PlaceHolder = ew_RemoveHtml($this->recomendacion->FldCaption());

			// Edit refer script
			// id_tipopruebasaudiologia

			$this->id_tipopruebasaudiologia->LinkCustomAttributes = "";
			$this->id_tipopruebasaudiologia->HrefValue = "";

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";

			// recomendacion
			$this->recomendacion->LinkCustomAttributes = "";
			$this->recomendacion->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// id_tipopruebasaudiologia
			$this->id_tipopruebasaudiologia->SetDbValueDef($rsnew, $this->id_tipopruebasaudiologia->CurrentValue, NULL, $this->id_tipopruebasaudiologia->ReadOnly);

			// resultado
			$this->resultado->SetDbValueDef($rsnew, $this->resultado->CurrentValue, NULL, $this->resultado->ReadOnly);

			// recomendacion
			$this->recomendacion->SetDbValueDef($rsnew, $this->recomendacion->CurrentValue, NULL, $this->recomendacion->ReadOnly);

			// Check referential integrity for master table 'audiologia'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_audiologia();
			$KeyValue = isset($rsnew['id_audiologia']) ? $rsnew['id_audiologia'] : $rsold['id_audiologia'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["audiologia"])) $GLOBALS["audiologia"] = new caudiologia();
				$rsmaster = $GLOBALS["audiologia"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "audiologia", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Check referential integrity for master table 'audiologia'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_audiologia();
		if ($this->id_audiologia->getSessionValue() <> "") {
			$sMasterFilter = str_replace("@id@", ew_AdjustSql($this->id_audiologia->getSessionValue(), "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["audiologia"])) $GLOBALS["audiologia"] = new caudiologia();
			$rsmaster = $GLOBALS["audiologia"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "audiologia", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// id_tipopruebasaudiologia
		$this->id_tipopruebasaudiologia->SetDbValueDef($rsnew, $this->id_tipopruebasaudiologia->CurrentValue, NULL, FALSE);

		// resultado
		$this->resultado->SetDbValueDef($rsnew, $this->resultado->CurrentValue, NULL, FALSE);

		// recomendacion
		$this->recomendacion->SetDbValueDef($rsnew, $this->recomendacion->CurrentValue, NULL, FALSE);

		// id_audiologia
		if ($this->id_audiologia->getSessionValue() <> "") {
			$rsnew['id_audiologia'] = $this->id_audiologia->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up master/detail based on QueryString
	function SetupMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "audiologia") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id"] <> "") {
					$GLOBALS["audiologia"]->id->setQueryStringValue($_GET["fk_id"]);
					$this->id_audiologia->setQueryStringValue($GLOBALS["audiologia"]->id->QueryStringValue);
					$this->id_audiologia->setSessionValue($this->id_audiologia->QueryStringValue);
					if (!is_numeric($GLOBALS["audiologia"]->id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "audiologia") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id"] <> "") {
					$GLOBALS["audiologia"]->id->setFormValue($_POST["fk_id"]);
					$this->id_audiologia->setFormValue($GLOBALS["audiologia"]->id->FormValue);
					$this->id_audiologia->setSessionValue($this->id_audiologia->FormValue);
					if (!is_numeric($GLOBALS["audiologia"]->id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			if (!$this->IsAddOrEdit()) {
				$this->StartRec = 1;
				$this->setStartRecordNumber($this->StartRec);
			}

			// Clear previous master key from Session
			if ($sMasterTblVar <> "audiologia") {
				if ($this->id_audiologia->CurrentValue == "") $this->id_audiologia->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
		case "x_id_tipopruebasaudiologia":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipopruebasaudiologia`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_tipopruebasaudiologia, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
	parse_str($GLOBALS["pruebasaudiologia"]->InlineAddUrl, $output);
	$value=0; // baz
		if (isset($output["fk_id"]))
		{
		$value=$output["fk_id"]; // baz
		}else
		{
		$value=$_GET['fk_id'];
		}
	$options = &$this->OtherOptions;
	$option = $options["addedit"];
	$item = &$option->Add("mybutton"); //add
	$item->Body = "<a class=\"btn btn-success\" title=\"Your Title\" data-caption=\"Your Caption\" href=\"diagnosticoaudiologialist.php?showmaster=audiologia&fk_id=$value\">diagnostico</a>"; // add your a tag content here
	$options1 = &$this->OtherOptions;
	$option1 = $options1["addedit"];
	$item1 = &$option1->Add("mybutton1"); //add
	$item1->Body = "<a class=\"btn btn-danger\" title=\"Your Title\" data-caption=\"Your Caption\" href=\"tratamientolist.php?showmaster=audiologia&fk_id=$value\">tratamiento</a>"; // add your a tag content here
	$options2 = &$this->OtherOptions;
	$option2 = $options2["addedit"];
	$item2 = &$option2->Add("mybutton2"); //add
	$item2->Body = "<a class=\"btn btn-info\" title=\"Your Title\" data-caption=\"Your Caption\" href=\"derivacionlist.php?showmaster=audiologia&fk_id=$value\">derivacion</a>"; // add your a tag content here
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
if (!isset($pruebasaudiologia_list)) $pruebasaudiologia_list = new cpruebasaudiologia_list();

// Page init
$pruebasaudiologia_list->Page_Init();

// Page main
$pruebasaudiologia_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pruebasaudiologia_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fpruebasaudiologialist = new ew_Form("fpruebasaudiologialist", "list");
fpruebasaudiologialist.FormKeyCountName = '<?php echo $pruebasaudiologia_list->FormKeyCountName ?>';

// Validate form
fpruebasaudiologialist.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpruebasaudiologialist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpruebasaudiologialist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpruebasaudiologialist.Lists["x_id_tipopruebasaudiologia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipopruebasaudiologia"};
fpruebasaudiologialist.Lists["x_id_tipopruebasaudiologia"].Data = "<?php echo $pruebasaudiologia_list->id_tipopruebasaudiologia->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($pruebasaudiologia_list->TotalRecs > 0 && $pruebasaudiologia_list->ExportOptions->Visible()) { ?>
<?php $pruebasaudiologia_list->ExportOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php if (($pruebasaudiologia->Export == "") || (EW_EXPORT_MASTER_RECORD && $pruebasaudiologia->Export == "print")) { ?>
<?php
if ($pruebasaudiologia_list->DbMasterFilter <> "" && $pruebasaudiologia->getCurrentMasterTable() == "audiologia") {
	if ($pruebasaudiologia_list->MasterRecordExists) {
?>
<?php include_once "audiologiamaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $pruebasaudiologia_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pruebasaudiologia_list->TotalRecs <= 0)
			$pruebasaudiologia_list->TotalRecs = $pruebasaudiologia->ListRecordCount();
	} else {
		if (!$pruebasaudiologia_list->Recordset && ($pruebasaudiologia_list->Recordset = $pruebasaudiologia_list->LoadRecordset()))
			$pruebasaudiologia_list->TotalRecs = $pruebasaudiologia_list->Recordset->RecordCount();
	}
	$pruebasaudiologia_list->StartRec = 1;
	if ($pruebasaudiologia_list->DisplayRecs <= 0 || ($pruebasaudiologia->Export <> "" && $pruebasaudiologia->ExportAll)) // Display all records
		$pruebasaudiologia_list->DisplayRecs = $pruebasaudiologia_list->TotalRecs;
	if (!($pruebasaudiologia->Export <> "" && $pruebasaudiologia->ExportAll))
		$pruebasaudiologia_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$pruebasaudiologia_list->Recordset = $pruebasaudiologia_list->LoadRecordset($pruebasaudiologia_list->StartRec-1, $pruebasaudiologia_list->DisplayRecs);

	// Set no record found message
	if ($pruebasaudiologia->CurrentAction == "" && $pruebasaudiologia_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$pruebasaudiologia_list->setWarningMessage(ew_DeniedMsg());
		if ($pruebasaudiologia_list->SearchWhere == "0=101")
			$pruebasaudiologia_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pruebasaudiologia_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$pruebasaudiologia_list->RenderOtherOptions();
?>
<?php $pruebasaudiologia_list->ShowPageHeader(); ?>
<?php
$pruebasaudiologia_list->ShowMessage();
?>
<?php if ($pruebasaudiologia_list->TotalRecs > 0 || $pruebasaudiologia->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($pruebasaudiologia_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> pruebasaudiologia">
<div class="box-header ewGridUpperPanel">
<?php if ($pruebasaudiologia->CurrentAction <> "gridadd" && $pruebasaudiologia->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pruebasaudiologia_list->Pager)) $pruebasaudiologia_list->Pager = new cPrevNextPager($pruebasaudiologia_list->StartRec, $pruebasaudiologia_list->DisplayRecs, $pruebasaudiologia_list->TotalRecs, $pruebasaudiologia_list->AutoHidePager) ?>
<?php if ($pruebasaudiologia_list->Pager->RecordCount > 0 && $pruebasaudiologia_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pruebasaudiologia_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pruebasaudiologia_list->PageUrl() ?>start=<?php echo $pruebasaudiologia_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pruebasaudiologia_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pruebasaudiologia_list->PageUrl() ?>start=<?php echo $pruebasaudiologia_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pruebasaudiologia_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pruebasaudiologia_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pruebasaudiologia_list->PageUrl() ?>start=<?php echo $pruebasaudiologia_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pruebasaudiologia_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pruebasaudiologia_list->PageUrl() ?>start=<?php echo $pruebasaudiologia_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pruebasaudiologia_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($pruebasaudiologia_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pruebasaudiologia_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pruebasaudiologia_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pruebasaudiologia_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pruebasaudiologia_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fpruebasaudiologialist" id="fpruebasaudiologialist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pruebasaudiologia_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pruebasaudiologia_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pruebasaudiologia">
<?php if ($pruebasaudiologia->getCurrentMasterTable() == "audiologia" && $pruebasaudiologia->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="audiologia">
<input type="hidden" name="fk_id" value="<?php echo $pruebasaudiologia->id_audiologia->getSessionValue() ?>">
<?php } ?>
<div id="gmp_pruebasaudiologia" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($pruebasaudiologia_list->TotalRecs > 0 || $pruebasaudiologia->CurrentAction == "add" || $pruebasaudiologia->CurrentAction == "copy" || $pruebasaudiologia->CurrentAction == "gridedit") { ?>
<table id="tbl_pruebasaudiologialist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$pruebasaudiologia_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pruebasaudiologia_list->RenderListOptions();

// Render list options (header, left)
$pruebasaudiologia_list->ListOptions->Render("header", "left");
?>
<?php if ($pruebasaudiologia->id_tipopruebasaudiologia->Visible) { // id_tipopruebasaudiologia ?>
	<?php if ($pruebasaudiologia->SortUrl($pruebasaudiologia->id_tipopruebasaudiologia) == "") { ?>
		<th data-name="id_tipopruebasaudiologia" class="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->HeaderCellClass() ?>"><div id="elh_pruebasaudiologia_id_tipopruebasaudiologia" class="pruebasaudiologia_id_tipopruebasaudiologia"><div class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipopruebasaudiologia" class="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pruebasaudiologia->SortUrl($pruebasaudiologia->id_tipopruebasaudiologia) ?>',2);"><div id="elh_pruebasaudiologia_id_tipopruebasaudiologia" class="pruebasaudiologia_id_tipopruebasaudiologia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pruebasaudiologia->id_tipopruebasaudiologia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pruebasaudiologia->id_tipopruebasaudiologia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pruebasaudiologia->resultado->Visible) { // resultado ?>
	<?php if ($pruebasaudiologia->SortUrl($pruebasaudiologia->resultado) == "") { ?>
		<th data-name="resultado" class="<?php echo $pruebasaudiologia->resultado->HeaderCellClass() ?>"><div id="elh_pruebasaudiologia_resultado" class="pruebasaudiologia_resultado"><div class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="resultado" class="<?php echo $pruebasaudiologia->resultado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pruebasaudiologia->SortUrl($pruebasaudiologia->resultado) ?>',2);"><div id="elh_pruebasaudiologia_resultado" class="pruebasaudiologia_resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->resultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pruebasaudiologia->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pruebasaudiologia->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pruebasaudiologia->recomendacion->Visible) { // recomendacion ?>
	<?php if ($pruebasaudiologia->SortUrl($pruebasaudiologia->recomendacion) == "") { ?>
		<th data-name="recomendacion" class="<?php echo $pruebasaudiologia->recomendacion->HeaderCellClass() ?>"><div id="elh_pruebasaudiologia_recomendacion" class="pruebasaudiologia_recomendacion"><div class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->recomendacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="recomendacion" class="<?php echo $pruebasaudiologia->recomendacion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pruebasaudiologia->SortUrl($pruebasaudiologia->recomendacion) ?>',2);"><div id="elh_pruebasaudiologia_recomendacion" class="pruebasaudiologia_recomendacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pruebasaudiologia->recomendacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pruebasaudiologia->recomendacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pruebasaudiologia->recomendacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$pruebasaudiologia_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($pruebasaudiologia->CurrentAction == "add" || $pruebasaudiologia->CurrentAction == "copy") {
		$pruebasaudiologia_list->RowIndex = 0;
		$pruebasaudiologia_list->KeyCount = $pruebasaudiologia_list->RowIndex;
		if ($pruebasaudiologia->CurrentAction == "add")
			$pruebasaudiologia_list->LoadRowValues();
		if ($pruebasaudiologia->EventCancelled) // Insert failed
			$pruebasaudiologia_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$pruebasaudiologia->ResetAttrs();
		$pruebasaudiologia->RowAttrs = array_merge($pruebasaudiologia->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_pruebasaudiologia', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$pruebasaudiologia->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pruebasaudiologia_list->RenderRow();

		// Render list options
		$pruebasaudiologia_list->RenderListOptions();
		$pruebasaudiologia_list->StartRowCnt = 0;
?>
	<tr<?php echo $pruebasaudiologia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pruebasaudiologia_list->ListOptions->Render("body", "left", $pruebasaudiologia_list->RowCnt);
?>
	<?php if ($pruebasaudiologia->id_tipopruebasaudiologia->Visible) { // id_tipopruebasaudiologia ?>
		<td data-name="id_tipopruebasaudiologia">
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_id_tipopruebasaudiologia" class="form-group pruebasaudiologia_id_tipopruebasaudiologia">
<select data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" data-value-separator="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia" name="x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia"<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->EditAttributes() ?>>
<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->SelectOptionListHtml("x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipopruebasaudiologia") && !$pruebasaudiologia->id_tipopruebasaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia',url:'tipopruebasaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" name="o<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia" id="o<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id_tipopruebasaudiologia->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pruebasaudiologia->resultado->Visible) { // resultado ?>
		<td data-name="resultado">
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_resultado" class="form-group pruebasaudiologia_resultado">
<input type="text" data-table="pruebasaudiologia" data-field="x_resultado" name="x<?php echo $pruebasaudiologia_list->RowIndex ?>_resultado" id="x<?php echo $pruebasaudiologia_list->RowIndex ?>_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->resultado->EditValue ?>"<?php echo $pruebasaudiologia->resultado->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_resultado" name="o<?php echo $pruebasaudiologia_list->RowIndex ?>_resultado" id="o<?php echo $pruebasaudiologia_list->RowIndex ?>_resultado" value="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pruebasaudiologia->recomendacion->Visible) { // recomendacion ?>
		<td data-name="recomendacion">
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_recomendacion" class="form-group pruebasaudiologia_recomendacion">
<input type="text" data-table="pruebasaudiologia" data-field="x_recomendacion" name="x<?php echo $pruebasaudiologia_list->RowIndex ?>_recomendacion" id="x<?php echo $pruebasaudiologia_list->RowIndex ?>_recomendacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->recomendacion->EditValue ?>"<?php echo $pruebasaudiologia->recomendacion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_recomendacion" name="o<?php echo $pruebasaudiologia_list->RowIndex ?>_recomendacion" id="o<?php echo $pruebasaudiologia_list->RowIndex ?>_recomendacion" value="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pruebasaudiologia_list->ListOptions->Render("body", "right", $pruebasaudiologia_list->RowCnt);
?>
<script type="text/javascript">
fpruebasaudiologialist.UpdateOpts(<?php echo $pruebasaudiologia_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($pruebasaudiologia->ExportAll && $pruebasaudiologia->Export <> "") {
	$pruebasaudiologia_list->StopRec = $pruebasaudiologia_list->TotalRecs;
} else {

	// Set the last record to display
	if ($pruebasaudiologia_list->TotalRecs > $pruebasaudiologia_list->StartRec + $pruebasaudiologia_list->DisplayRecs - 1)
		$pruebasaudiologia_list->StopRec = $pruebasaudiologia_list->StartRec + $pruebasaudiologia_list->DisplayRecs - 1;
	else
		$pruebasaudiologia_list->StopRec = $pruebasaudiologia_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pruebasaudiologia_list->FormKeyCountName) && ($pruebasaudiologia->CurrentAction == "gridadd" || $pruebasaudiologia->CurrentAction == "gridedit" || $pruebasaudiologia->CurrentAction == "F")) {
		$pruebasaudiologia_list->KeyCount = $objForm->GetValue($pruebasaudiologia_list->FormKeyCountName);
		$pruebasaudiologia_list->StopRec = $pruebasaudiologia_list->StartRec + $pruebasaudiologia_list->KeyCount - 1;
	}
}
$pruebasaudiologia_list->RecCnt = $pruebasaudiologia_list->StartRec - 1;
if ($pruebasaudiologia_list->Recordset && !$pruebasaudiologia_list->Recordset->EOF) {
	$pruebasaudiologia_list->Recordset->MoveFirst();
	$bSelectLimit = $pruebasaudiologia_list->UseSelectLimit;
	if (!$bSelectLimit && $pruebasaudiologia_list->StartRec > 1)
		$pruebasaudiologia_list->Recordset->Move($pruebasaudiologia_list->StartRec - 1);
} elseif (!$pruebasaudiologia->AllowAddDeleteRow && $pruebasaudiologia_list->StopRec == 0) {
	$pruebasaudiologia_list->StopRec = $pruebasaudiologia->GridAddRowCount;
}

// Initialize aggregate
$pruebasaudiologia->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pruebasaudiologia->ResetAttrs();
$pruebasaudiologia_list->RenderRow();
$pruebasaudiologia_list->EditRowCnt = 0;
if ($pruebasaudiologia->CurrentAction == "edit")
	$pruebasaudiologia_list->RowIndex = 1;
while ($pruebasaudiologia_list->RecCnt < $pruebasaudiologia_list->StopRec) {
	$pruebasaudiologia_list->RecCnt++;
	if (intval($pruebasaudiologia_list->RecCnt) >= intval($pruebasaudiologia_list->StartRec)) {
		$pruebasaudiologia_list->RowCnt++;

		// Set up key count
		$pruebasaudiologia_list->KeyCount = $pruebasaudiologia_list->RowIndex;

		// Init row class and style
		$pruebasaudiologia->ResetAttrs();
		$pruebasaudiologia->CssClass = "";
		if ($pruebasaudiologia->CurrentAction == "gridadd") {
			$pruebasaudiologia_list->LoadRowValues(); // Load default values
		} else {
			$pruebasaudiologia_list->LoadRowValues($pruebasaudiologia_list->Recordset); // Load row values
		}
		$pruebasaudiologia->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pruebasaudiologia->CurrentAction == "edit") {
			if ($pruebasaudiologia_list->CheckInlineEditKey() && $pruebasaudiologia_list->EditRowCnt == 0) { // Inline edit
				$pruebasaudiologia->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($pruebasaudiologia->CurrentAction == "edit" && $pruebasaudiologia->RowType == EW_ROWTYPE_EDIT && $pruebasaudiologia->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$pruebasaudiologia_list->RestoreFormValues(); // Restore form values
		}
		if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pruebasaudiologia_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$pruebasaudiologia->RowAttrs = array_merge($pruebasaudiologia->RowAttrs, array('data-rowindex'=>$pruebasaudiologia_list->RowCnt, 'id'=>'r' . $pruebasaudiologia_list->RowCnt . '_pruebasaudiologia', 'data-rowtype'=>$pruebasaudiologia->RowType));

		// Render row
		$pruebasaudiologia_list->RenderRow();

		// Render list options
		$pruebasaudiologia_list->RenderListOptions();
?>
	<tr<?php echo $pruebasaudiologia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pruebasaudiologia_list->ListOptions->Render("body", "left", $pruebasaudiologia_list->RowCnt);
?>
	<?php if ($pruebasaudiologia->id_tipopruebasaudiologia->Visible) { // id_tipopruebasaudiologia ?>
		<td data-name="id_tipopruebasaudiologia"<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->CellAttributes() ?>>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_id_tipopruebasaudiologia" class="form-group pruebasaudiologia_id_tipopruebasaudiologia">
<select data-table="pruebasaudiologia" data-field="x_id_tipopruebasaudiologia" data-value-separator="<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia" name="x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia"<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->EditAttributes() ?>>
<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->SelectOptionListHtml("x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipopruebasaudiologia") && !$pruebasaudiologia->id_tipopruebasaudiologia->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia',url:'tipopruebasaudiologiaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $pruebasaudiologia_list->RowIndex ?>_id_tipopruebasaudiologia"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_id_tipopruebasaudiologia" class="pruebasaudiologia_id_tipopruebasaudiologia">
<span<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->ViewAttributes() ?>>
<?php echo $pruebasaudiologia->id_tipopruebasaudiologia->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT || $pruebasaudiologia->CurrentMode == "edit") { ?>
<input type="hidden" data-table="pruebasaudiologia" data-field="x_id" name="x<?php echo $pruebasaudiologia_list->RowIndex ?>_id" id="x<?php echo $pruebasaudiologia_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pruebasaudiologia->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($pruebasaudiologia->resultado->Visible) { // resultado ?>
		<td data-name="resultado"<?php echo $pruebasaudiologia->resultado->CellAttributes() ?>>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_resultado" class="form-group pruebasaudiologia_resultado">
<input type="text" data-table="pruebasaudiologia" data-field="x_resultado" name="x<?php echo $pruebasaudiologia_list->RowIndex ?>_resultado" id="x<?php echo $pruebasaudiologia_list->RowIndex ?>_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->resultado->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->resultado->EditValue ?>"<?php echo $pruebasaudiologia->resultado->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_resultado" class="pruebasaudiologia_resultado">
<span<?php echo $pruebasaudiologia->resultado->ViewAttributes() ?>>
<?php echo $pruebasaudiologia->resultado->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pruebasaudiologia->recomendacion->Visible) { // recomendacion ?>
		<td data-name="recomendacion"<?php echo $pruebasaudiologia->recomendacion->CellAttributes() ?>>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_recomendacion" class="form-group pruebasaudiologia_recomendacion">
<input type="text" data-table="pruebasaudiologia" data-field="x_recomendacion" name="x<?php echo $pruebasaudiologia_list->RowIndex ?>_recomendacion" id="x<?php echo $pruebasaudiologia_list->RowIndex ?>_recomendacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pruebasaudiologia->recomendacion->getPlaceHolder()) ?>" value="<?php echo $pruebasaudiologia->recomendacion->EditValue ?>"<?php echo $pruebasaudiologia->recomendacion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pruebasaudiologia_list->RowCnt ?>_pruebasaudiologia_recomendacion" class="pruebasaudiologia_recomendacion">
<span<?php echo $pruebasaudiologia->recomendacion->ViewAttributes() ?>>
<?php echo $pruebasaudiologia->recomendacion->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pruebasaudiologia_list->ListOptions->Render("body", "right", $pruebasaudiologia_list->RowCnt);
?>
	</tr>
<?php if ($pruebasaudiologia->RowType == EW_ROWTYPE_ADD || $pruebasaudiologia->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpruebasaudiologialist.UpdateOpts(<?php echo $pruebasaudiologia_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($pruebasaudiologia->CurrentAction <> "gridadd")
		$pruebasaudiologia_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($pruebasaudiologia->CurrentAction == "add" || $pruebasaudiologia->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $pruebasaudiologia_list->FormKeyCountName ?>" id="<?php echo $pruebasaudiologia_list->FormKeyCountName ?>" value="<?php echo $pruebasaudiologia_list->KeyCount ?>">
<?php } ?>
<?php if ($pruebasaudiologia->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $pruebasaudiologia_list->FormKeyCountName ?>" id="<?php echo $pruebasaudiologia_list->FormKeyCountName ?>" value="<?php echo $pruebasaudiologia_list->KeyCount ?>">
<?php } ?>
<?php if ($pruebasaudiologia->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($pruebasaudiologia_list->Recordset)
	$pruebasaudiologia_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($pruebasaudiologia->CurrentAction <> "gridadd" && $pruebasaudiologia->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pruebasaudiologia_list->Pager)) $pruebasaudiologia_list->Pager = new cPrevNextPager($pruebasaudiologia_list->StartRec, $pruebasaudiologia_list->DisplayRecs, $pruebasaudiologia_list->TotalRecs, $pruebasaudiologia_list->AutoHidePager) ?>
<?php if ($pruebasaudiologia_list->Pager->RecordCount > 0 && $pruebasaudiologia_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pruebasaudiologia_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pruebasaudiologia_list->PageUrl() ?>start=<?php echo $pruebasaudiologia_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pruebasaudiologia_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pruebasaudiologia_list->PageUrl() ?>start=<?php echo $pruebasaudiologia_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pruebasaudiologia_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pruebasaudiologia_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pruebasaudiologia_list->PageUrl() ?>start=<?php echo $pruebasaudiologia_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pruebasaudiologia_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pruebasaudiologia_list->PageUrl() ?>start=<?php echo $pruebasaudiologia_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pruebasaudiologia_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($pruebasaudiologia_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pruebasaudiologia_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pruebasaudiologia_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pruebasaudiologia_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pruebasaudiologia_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($pruebasaudiologia_list->TotalRecs == 0 && $pruebasaudiologia->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pruebasaudiologia_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fpruebasaudiologialist.Init();
</script>
<?php
$pruebasaudiologia_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pruebasaudiologia_list->Page_Terminate();
?>
