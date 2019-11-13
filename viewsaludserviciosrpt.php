<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewsaludserviciosrptinfo.php" ?>
<?php

//
// Page class
//

$viewsaludservicios_rpt = NULL; // Initialize page object first

class crviewsaludservicios_rpt extends crviewsaludservicios {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewsaludservicios_rpt';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $ReportLanguage;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $ReportLanguage;
		if ($this->Subheading <> "")
			return $this->Subheading;
		return "";
	}

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $grToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$grToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $ReportLanguage;
		global $UserTable, $UserTableConn;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (viewsaludservicios)
		if (!isset($GLOBALS["viewsaludservicios"])) {
			$GLOBALS["viewsaludservicios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewsaludservicios"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'rpt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'viewsaludservicios', TRUE);

		// Start timer
		if (!isset($GLOBALS["grTimer"]))
			$GLOBALS["grTimer"] = new crTimer();

		// Debug message
		ewr_LoadDebugMsg();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new crusuario();
			$UserTableConn = ReportConn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fviewsaludserviciosrpt";

		// Generate report options
		$this->GenerateOptions = new crListOptions();
		$this->GenerateOptions->Tag = "div";
		$this->GenerateOptions->TagClassName = "ewGenerateOption";
	}

	//
	// Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security, $UserProfile;
		global $gsCustomExport;

		// User profile
		$UserProfile = new crUserProfile();

		// Security
		$Security = new crAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin(); // Auto login
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewsaludservicios');
		$Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ewr_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("index.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ewr_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("login.php"));
		}

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		$this->ticketdeatencion->PlaceHolder = $this->ticketdeatencion->FldCaption();
		$this->especialidad->PlaceHolder = $this->especialidad->FldCaption();
		$this->nombreneonatal->PlaceHolder = $this->nombreneonatal->FldCaption();
		$this->nombreescolar->PlaceHolder = $this->nombreescolar->FldCaption();
		$this->nombreotros->PlaceHolder = $this->nombreotros->FldCaption();
		$this->observaciones->PlaceHolder = $this->observaciones->FldCaption();

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Security, $ReportLanguage, $ReportOptions;
		$exportid = session_id();
		$ReportTypes = array();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a class=\"ewrExportLink ewPrint\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a class=\"ewrExportLink ewExcel\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a class=\"ewrExportLink ewWord\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["word"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormWord") : "";

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a class=\"ewrExportLink ewPdf\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewsaludservicios\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewsaludservicios',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewsaludserviciosrpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewsaludserviciosrpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewsaludserviciosrpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = TRUE && $this->FilterApplied;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->SearchOptions->HideAllOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;
		global $grDashboardReport;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			if (ob_get_length())
				ob_end_clean();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				if (@$this->GenOptions["reporttype"] == "email") {
					$saveResponse = $this->$fn($sContent, $this->GenOptions);
					$this->WriteGenResponse($saveResponse);
				} else {
					echo $this->$fn($sContent, array());
				}
				$url = ""; // Avoid redirect
			} else {
				$saveToFile = $this->$fn($sContent, $this->GenOptions);
				if (@$this->GenOptions["reporttype"] <> "") {
					$saveUrl = ($saveToFile <> "") ? ewr_FullUrl($saveToFile, "genurl") : $ReportLanguage->Phrase("GenerateSuccess");
					$this->WriteGenResponse($saveUrl);
					$url = ""; // Avoid redirect
				}
			}
		}

		// Close connection if not in dashboard
		if (!$grDashboardReport)
			ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ewr_SaveDebugMsg();
			header("Location: " . $url);
		}
		if (!$grDashboardReport)
			exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpColumnCount = 0;
	var $SubGrpColumnCount = 0;
	var $DtlColumnCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;
	var $DetailRows = array();
	var $TopContentClass = "col-sm-12 ewTop";
	var $LeftContentClass = "ewLeft";
	var $CenterContentClass = "col-sm-12 ewCenter";
	var $RightContentClass = "ewRight";
	var $BottomContentClass = "col-sm-12 ewBottom";

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $grFormError;
		global $grDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;
		global $grDashboardReport;

		// Set field visibility for detail fields
		$this->ticketdeatencion->SetVisibility();
		$this->especialidad->SetVisibility();
		$this->nombreneonatal->SetVisibility();
		$this->nombreescolar->SetVisibility();
		$this->nombreotros->SetVisibility();
		$this->observaciones->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 7;
		$nGrps = 1;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->ticketdeatencion->SelectionList = "";
		$this->ticketdeatencion->DefaultSelectionList = "";
		$this->ticketdeatencion->ValueList = "";
		$this->especialidad->SelectionList = "";
		$this->especialidad->DefaultSelectionList = "";
		$this->especialidad->ValueList = "";
		$this->observaciones->SelectionList = "";
		$this->observaciones->DefaultSelectionList = "";
		$this->observaciones->ValueList = "";

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Restore filter list
		$this->RestoreFilterList();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total count
		$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, ""); // No need for ORDER BY for total count
		$this->TotalGrps = $this->GetCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown || $grDashboardReport) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = TRUE;

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup($this->GenOptions);

		// Set no record found message
		if ($this->TotalGrps == 0) {
			if ($Security->CanList()) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
			} else {
				$this->setWarningMessage(ewr_DeniedMsg());
			}
		}

		// Hide export options if export/dashboard report
		if ($this->Export <> "" || $grDashboardReport)
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown/dashboard report
		if ($this->Export <> "" || $this->DrillDown || $grDashboardReport) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
			$this->GenerateOptions->HideAllOptions();
		}

		// Get current page records
		$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(), $this->Filter, $this->Sort);
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
		$this->SetupFieldCount();
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get count
	function GetCnt($sql) {
		return $this->getRecordCount($sql);
	}

	// Get recordset
	function GetRs($wrksql, $start, $grps) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
				$this->FirstRowData = array();
				$this->FirstRowData['ticketdeatencion'] = ewr_Conv($rs->fields('ticketdeatencion'), 3);
				$this->FirstRowData['especialidad'] = ewr_Conv($rs->fields('especialidad'), 200);
				$this->FirstRowData['observaciones'] = ewr_Conv($rs->fields('observaciones'), 3);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->ticketdeatencion->setDbValue($rs->fields('ticketdeatencion'));
			$this->especialidad->setDbValue($rs->fields('especialidad'));
			$this->nombreneonatal->setDbValue($rs->fields('nombreneonatal'));
			$this->nombreescolar->setDbValue($rs->fields('nombreescolar'));
			$this->nombreotros->setDbValue($rs->fields('nombreotros'));
			$this->observaciones->setDbValue($rs->fields('observaciones'));
			$this->nombreespecialista->setDbValue($rs->fields('nombreespecialista'));
			$this->Val[1] = $this->ticketdeatencion->CurrentValue;
			$this->Val[2] = $this->especialidad->CurrentValue;
			$this->Val[3] = $this->nombreneonatal->CurrentValue;
			$this->Val[4] = $this->nombreescolar->CurrentValue;
			$this->Val[5] = $this->nombreotros->CurrentValue;
			$this->Val[6] = $this->observaciones->CurrentValue;
		} else {
			$this->ticketdeatencion->setDbValue("");
			$this->especialidad->setDbValue("");
			$this->nombreneonatal->setDbValue("");
			$this->nombreescolar->setDbValue("");
			$this->nombreotros->setDbValue("");
			$this->observaciones->setDbValue("");
			$this->nombreespecialista->setDbValue("");
		}
	}

	// Set up starting group
	function SetUpStartGroup($options = array()) {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;
		$startGrp = (@$options["start"] <> "") ? $options["start"] : @$_GET[EWR_TABLE_START_GROUP];
		$pageNo = (@$options["pageno"] <> "") ? $options["pageno"] : @$_GET["pageno"];

		// Check for a 'start' parameter
		if ($startGrp != "") {
			$this->StartGrp = $startGrp;
			$this->setStartGroup($this->StartGrp);
		} elseif ($pageNo != "") {
			$nPageNo = $pageNo;
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Build distinct values for ticketdeatencion

			if ($popupname == 'viewsaludservicios_ticketdeatencion') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->ticketdeatencion, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->ticketdeatencion->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->ticketdeatencion->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->ticketdeatencion->setDbValue($rswrk->fields[0]);
					$this->ticketdeatencion->ViewValue = @$rswrk->fields[1];
					if (is_null($this->ticketdeatencion->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->ticketdeatencion->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->ticketdeatencion->ValueList, $this->ticketdeatencion->CurrentValue, $this->ticketdeatencion->ViewValue, FALSE, $this->ticketdeatencion->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->ticketdeatencion->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->ticketdeatencion->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->ticketdeatencion;
			}

			// Build distinct values for especialidad
			if ($popupname == 'viewsaludservicios_especialidad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->especialidad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->especialidad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->especialidad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->especialidad->setDbValue($rswrk->fields[0]);
					$this->especialidad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->especialidad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->especialidad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->especialidad->ValueList, $this->especialidad->CurrentValue, $this->especialidad->ViewValue, FALSE, $this->especialidad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->especialidad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->especialidad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->especialidad;
			}

			// Build distinct values for observaciones
			if ($popupname == 'viewsaludservicios_observaciones') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->observaciones, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->observaciones->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->observaciones->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->observaciones->setDbValue($rswrk->fields[0]);
					$this->observaciones->ViewValue = @$rswrk->fields[1];
					if (is_null($this->observaciones->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->observaciones->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->observaciones->ValueList, $this->observaciones->CurrentValue, $this->observaciones->ViewValue, FALSE, $this->observaciones->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->observaciones->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->observaciones->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->observaciones;
			}

			// Output data as Json
			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				if (ob_get_length())
					ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = $_POST["sel_$sName"];
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$this->PopupName = $sName;
					if (ewr_IsAdvancedFilterValue($arValues) || $arValues == EWR_INIT_VALUE)
						$this->PopupValue = $arValues;
					if (!ewr_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = @$_POST["rf_$sName"];
					$_SESSION["rt_$sName"] = @$_POST["rt_$sName"];
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ClearSessionSelection('ticketdeatencion');
				$this->ClearSessionSelection('especialidad');
				$this->ClearSessionSelection('observaciones');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get ticketdeatencion selected values

		if (is_array(@$_SESSION["sel_viewsaludservicios_ticketdeatencion"])) {
			$this->LoadSelectionFromSession('ticketdeatencion');
		} elseif (@$_SESSION["sel_viewsaludservicios_ticketdeatencion"] == EWR_INIT_VALUE) { // Select all
			$this->ticketdeatencion->SelectionList = "";
		}

		// Get especialidad selected values
		if (is_array(@$_SESSION["sel_viewsaludservicios_especialidad"])) {
			$this->LoadSelectionFromSession('especialidad');
		} elseif (@$_SESSION["sel_viewsaludservicios_especialidad"] == EWR_INIT_VALUE) { // Select all
			$this->especialidad->SelectionList = "";
		}

		// Get observaciones selected values
		if (is_array(@$_SESSION["sel_viewsaludservicios_observaciones"])) {
			$this->LoadSelectionFromSession('observaciones');
		} elseif (@$_SESSION["sel_viewsaludservicios_observaciones"] == EWR_INIT_VALUE) { // Select all
			$this->observaciones->SelectionList = "";
		}
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if (!$this->GrandSummarySetup) { // Get Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}
		$bGotSummary = TRUE;

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL && !($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER)) { // Summary row
			ewr_PrependClass($this->RowAttrs["class"], ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : ""); // Set up row class

			// ticketdeatencion
			$this->ticketdeatencion->HrefValue = "";

			// especialidad
			$this->especialidad->HrefValue = "";

			// nombreneonatal
			$this->nombreneonatal->HrefValue = "";

			// nombreescolar
			$this->nombreescolar->HrefValue = "";

			// nombreotros
			$this->nombreotros->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// ticketdeatencion
			$this->ticketdeatencion->ViewValue = $this->ticketdeatencion->CurrentValue;
			$this->ticketdeatencion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// especialidad
			$this->especialidad->ViewValue = $this->especialidad->CurrentValue;
			$this->especialidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreneonatal
			$this->nombreneonatal->ViewValue = $this->nombreneonatal->CurrentValue;
			$this->nombreneonatal->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreescolar
			$this->nombreescolar->ViewValue = $this->nombreescolar->CurrentValue;
			$this->nombreescolar->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreotros
			$this->nombreotros->ViewValue = $this->nombreotros->CurrentValue;
			$this->nombreotros->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ticketdeatencion
			$this->ticketdeatencion->HrefValue = "";

			// especialidad
			$this->especialidad->HrefValue = "";

			// nombreneonatal
			$this->nombreneonatal->HrefValue = "";

			// nombreescolar
			$this->nombreescolar->HrefValue = "";

			// nombreotros
			$this->nombreotros->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// ticketdeatencion
			$CurrentValue = $this->ticketdeatencion->CurrentValue;
			$ViewValue = &$this->ticketdeatencion->ViewValue;
			$ViewAttrs = &$this->ticketdeatencion->ViewAttrs;
			$CellAttrs = &$this->ticketdeatencion->CellAttrs;
			$HrefValue = &$this->ticketdeatencion->HrefValue;
			$LinkAttrs = &$this->ticketdeatencion->LinkAttrs;
			$this->Cell_Rendered($this->ticketdeatencion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// especialidad
			$CurrentValue = $this->especialidad->CurrentValue;
			$ViewValue = &$this->especialidad->ViewValue;
			$ViewAttrs = &$this->especialidad->ViewAttrs;
			$CellAttrs = &$this->especialidad->CellAttrs;
			$HrefValue = &$this->especialidad->HrefValue;
			$LinkAttrs = &$this->especialidad->LinkAttrs;
			$this->Cell_Rendered($this->especialidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombreneonatal
			$CurrentValue = $this->nombreneonatal->CurrentValue;
			$ViewValue = &$this->nombreneonatal->ViewValue;
			$ViewAttrs = &$this->nombreneonatal->ViewAttrs;
			$CellAttrs = &$this->nombreneonatal->CellAttrs;
			$HrefValue = &$this->nombreneonatal->HrefValue;
			$LinkAttrs = &$this->nombreneonatal->LinkAttrs;
			$this->Cell_Rendered($this->nombreneonatal, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombreescolar
			$CurrentValue = $this->nombreescolar->CurrentValue;
			$ViewValue = &$this->nombreescolar->ViewValue;
			$ViewAttrs = &$this->nombreescolar->ViewAttrs;
			$CellAttrs = &$this->nombreescolar->CellAttrs;
			$HrefValue = &$this->nombreescolar->HrefValue;
			$LinkAttrs = &$this->nombreescolar->LinkAttrs;
			$this->Cell_Rendered($this->nombreescolar, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombreotros
			$CurrentValue = $this->nombreotros->CurrentValue;
			$ViewValue = &$this->nombreotros->ViewValue;
			$ViewAttrs = &$this->nombreotros->ViewAttrs;
			$CellAttrs = &$this->nombreotros->CellAttrs;
			$HrefValue = &$this->nombreotros->HrefValue;
			$LinkAttrs = &$this->nombreotros->LinkAttrs;
			$this->Cell_Rendered($this->nombreotros, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// observaciones
			$CurrentValue = $this->observaciones->CurrentValue;
			$ViewValue = &$this->observaciones->ViewValue;
			$ViewAttrs = &$this->observaciones->ViewAttrs;
			$CellAttrs = &$this->observaciones->CellAttrs;
			$HrefValue = &$this->observaciones->HrefValue;
			$LinkAttrs = &$this->observaciones->LinkAttrs;
			$this->Cell_Rendered($this->observaciones, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		$this->SubGrpColumnCount = 0;
		$this->DtlColumnCount = 0;
		if ($this->ticketdeatencion->Visible) $this->DtlColumnCount += 1;
		if ($this->especialidad->Visible) $this->DtlColumnCount += 1;
		if ($this->nombreneonatal->Visible) $this->DtlColumnCount += 1;
		if ($this->nombreescolar->Visible) $this->DtlColumnCount += 1;
		if ($this->nombreotros->Visible) $this->DtlColumnCount += 1;
		if ($this->observaciones->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("rpt", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $ReportOptions;
		$ReportTypes = $ReportOptions["ReportTypes"];
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		if ($item->Visible)
			$ReportTypes["pdf"] = $ReportLanguage->Phrase("ReportFormPdf");
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a class=\"ewrExportLink ewPdf\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $grFormError;
		$sFilter = "";
		if ($this->DrillDown)
			return "";
		$bPostBack = ewr_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

			// Clear extended filter for field ticketdeatencion
			if ($this->ClearExtFilter == 'viewsaludservicios_ticketdeatencion')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'ticketdeatencion');

			// Clear extended filter for field especialidad
			if ($this->ClearExtFilter == 'viewsaludservicios_especialidad')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'especialidad');

			// Clear extended filter for field observaciones
			if ($this->ClearExtFilter == 'viewsaludservicios_observaciones')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'observaciones');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionFilterValues($this->ticketdeatencion->SearchValue, $this->ticketdeatencion->SearchOperator, $this->ticketdeatencion->SearchCondition, $this->ticketdeatencion->SearchValue2, $this->ticketdeatencion->SearchOperator2, 'ticketdeatencion'); // Field ticketdeatencion
			$this->SetSessionFilterValues($this->especialidad->SearchValue, $this->especialidad->SearchOperator, $this->especialidad->SearchCondition, $this->especialidad->SearchValue2, $this->especialidad->SearchOperator2, 'especialidad'); // Field especialidad
			$this->SetSessionFilterValues($this->nombreneonatal->SearchValue, $this->nombreneonatal->SearchOperator, $this->nombreneonatal->SearchCondition, $this->nombreneonatal->SearchValue2, $this->nombreneonatal->SearchOperator2, 'nombreneonatal'); // Field nombreneonatal
			$this->SetSessionFilterValues($this->nombreescolar->SearchValue, $this->nombreescolar->SearchOperator, $this->nombreescolar->SearchCondition, $this->nombreescolar->SearchValue2, $this->nombreescolar->SearchOperator2, 'nombreescolar'); // Field nombreescolar
			$this->SetSessionFilterValues($this->nombreotros->SearchValue, $this->nombreotros->SearchOperator, $this->nombreotros->SearchCondition, $this->nombreotros->SearchValue2, $this->nombreotros->SearchOperator2, 'nombreotros'); // Field nombreotros
			$this->SetSessionFilterValues($this->observaciones->SearchValue, $this->observaciones->SearchOperator, $this->observaciones->SearchCondition, $this->observaciones->SearchValue2, $this->observaciones->SearchOperator2, 'observaciones'); // Field observaciones

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field ticketdeatencion
			if ($this->GetFilterValues($this->ticketdeatencion)) {
				$bSetupFilter = TRUE;
			}

			// Field especialidad
			if ($this->GetFilterValues($this->especialidad)) {
				$bSetupFilter = TRUE;
			}

			// Field nombreneonatal
			if ($this->GetFilterValues($this->nombreneonatal)) {
				$bSetupFilter = TRUE;
			}

			// Field nombreescolar
			if ($this->GetFilterValues($this->nombreescolar)) {
				$bSetupFilter = TRUE;
			}

			// Field nombreotros
			if ($this->GetFilterValues($this->nombreotros)) {
				$bSetupFilter = TRUE;
			}

			// Field observaciones
			if ($this->GetFilterValues($this->observaciones)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionFilterValues($this->ticketdeatencion); // Field ticketdeatencion
			$this->GetSessionFilterValues($this->especialidad); // Field especialidad
			$this->GetSessionFilterValues($this->nombreneonatal); // Field nombreneonatal
			$this->GetSessionFilterValues($this->nombreescolar); // Field nombreescolar
			$this->GetSessionFilterValues($this->nombreotros); // Field nombreotros
			$this->GetSessionFilterValues($this->observaciones); // Field observaciones
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildExtendedFilter($this->ticketdeatencion, $sFilter, FALSE, TRUE); // Field ticketdeatencion
		$this->BuildExtendedFilter($this->especialidad, $sFilter, FALSE, TRUE); // Field especialidad
		$this->BuildExtendedFilter($this->nombreneonatal, $sFilter, FALSE, TRUE); // Field nombreneonatal
		$this->BuildExtendedFilter($this->nombreescolar, $sFilter, FALSE, TRUE); // Field nombreescolar
		$this->BuildExtendedFilter($this->nombreotros, $sFilter, FALSE, TRUE); // Field nombreotros
		$this->BuildExtendedFilter($this->observaciones, $sFilter, FALSE, TRUE); // Field observaciones

		// Save parms to session
		$this->SetSessionFilterValues($this->ticketdeatencion->SearchValue, $this->ticketdeatencion->SearchOperator, $this->ticketdeatencion->SearchCondition, $this->ticketdeatencion->SearchValue2, $this->ticketdeatencion->SearchOperator2, 'ticketdeatencion'); // Field ticketdeatencion
		$this->SetSessionFilterValues($this->especialidad->SearchValue, $this->especialidad->SearchOperator, $this->especialidad->SearchCondition, $this->especialidad->SearchValue2, $this->especialidad->SearchOperator2, 'especialidad'); // Field especialidad
		$this->SetSessionFilterValues($this->nombreneonatal->SearchValue, $this->nombreneonatal->SearchOperator, $this->nombreneonatal->SearchCondition, $this->nombreneonatal->SearchValue2, $this->nombreneonatal->SearchOperator2, 'nombreneonatal'); // Field nombreneonatal
		$this->SetSessionFilterValues($this->nombreescolar->SearchValue, $this->nombreescolar->SearchOperator, $this->nombreescolar->SearchCondition, $this->nombreescolar->SearchValue2, $this->nombreescolar->SearchOperator2, 'nombreescolar'); // Field nombreescolar
		$this->SetSessionFilterValues($this->nombreotros->SearchValue, $this->nombreotros->SearchOperator, $this->nombreotros->SearchCondition, $this->nombreotros->SearchValue2, $this->nombreotros->SearchOperator2, 'nombreotros'); // Field nombreotros
		$this->SetSessionFilterValues($this->observaciones->SearchValue, $this->observaciones->SearchOperator, $this->observaciones->SearchCondition, $this->observaciones->SearchValue2, $this->observaciones->SearchOperator2, 'observaciones'); // Field observaciones

		// Setup filter
		if ($bSetupFilter) {

			// Field ticketdeatencion
			$sWrk = "";
			$this->BuildExtendedFilter($this->ticketdeatencion, $sWrk);
			ewr_LoadSelectionFromFilter($this->ticketdeatencion, $sWrk, $this->ticketdeatencion->SelectionList);
			$_SESSION['sel_viewsaludservicios_ticketdeatencion'] = ($this->ticketdeatencion->SelectionList == "") ? EWR_INIT_VALUE : $this->ticketdeatencion->SelectionList;

			// Field especialidad
			$sWrk = "";
			$this->BuildExtendedFilter($this->especialidad, $sWrk);
			ewr_LoadSelectionFromFilter($this->especialidad, $sWrk, $this->especialidad->SelectionList);
			$_SESSION['sel_viewsaludservicios_especialidad'] = ($this->especialidad->SelectionList == "") ? EWR_INIT_VALUE : $this->especialidad->SelectionList;

			// Field observaciones
			$sWrk = "";
			$this->BuildExtendedFilter($this->observaciones, $sWrk);
			ewr_LoadSelectionFromFilter($this->observaciones, $sWrk, $this->observaciones->SelectionList);
			$_SESSION['sel_viewsaludservicios_observaciones'] = ($this->observaciones->SelectionList == "") ? EWR_INIT_VALUE : $this->observaciones->SelectionList;
		}
		return $sFilter;
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr, $Default = FALSE, $SaveFilter = FALSE) {
		$FldVal = ($Default) ? $fld->DefaultDropDownValue : $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownFilter($fld, $val, $FldOpr);

				// Call Page Filtering event
				if (substr($val, 0, 2) <> "@@")
					$this->Page_Filtering($fld, $sWrk, "dropdown", $FldOpr, $val);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownFilter($fld, $FldVal, $FldOpr);

			// Call Page Filtering event
			if (substr($FldVal, 0, 2) <> "@@")
				$this->Page_Filtering($fld, $sSql, "dropdown", $FldOpr, $FldVal);
		}
		if ($sSql <> "") {
			ewr_AddFilter($FilterClause, $sSql);
			if ($SaveFilter) $fld->CurrentFilter = $sSql;
		}
	}

	function GetDropDownFilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDelimiter = $fld->FldDelimiter;
		$FldVal = strval($FldVal);
		if ($FldOpr == "") $FldOpr = "=";
		$sWrk = "";
		if (ewr_SameStr($FldVal, EWR_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif (ewr_SameStr($FldVal, EWR_NOT_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NOT NULL";
		} elseif (ewr_SameStr($FldVal, EWR_EMPTY_VALUE)) {
			$sWrk = $FldExpression . " = ''";
		} elseif (ewr_SameStr($FldVal, EWR_ALL_VALUE)) {
			$sWrk = "1 = 1";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = $this->GetCustomFilter($fld, $FldVal, $this->DBID);
			} elseif ($FldDelimiter <> "" && trim($FldVal) <> "" && ($FldDataType == EWR_DATATYPE_STRING || $FldDataType == EWR_DATATYPE_MEMO)) {
				$sWrk = ewr_GetMultiSearchSql($FldExpression, trim($FldVal), $this->DBID);
			} else {
				if ($FldVal <> "" && $FldVal <> EWR_INIT_VALUE) {
					if ($FldDataType == EWR_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = ewr_DateFilterString($FldExpression, $FldOpr, $FldVal, $FldDataType, $this->DBID);
					} else {
						$sWrk = ewr_FilterString($FldOpr, $FldVal, $FldDataType, $this->DBID);
						if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
					}
				}
			}
		}
		return $sWrk;
	}

	// Get custom filter
	function GetCustomFilter(&$fld, $FldVal, $dbid = 0) {
		$sWrk = "";
		if (is_array($fld->AdvancedFilters)) {
			foreach ($fld->AdvancedFilters as $filter) {
				if ($filter->ID == $FldVal && $filter->Enabled) {
					$sFld = $fld->FldExpression;
					$sFn = $filter->FunctionName;
					$wrkid = (substr($filter->ID, 0, 2) == "@@") ? substr($filter->ID,2) : $filter->ID;
					if ($sFn <> "")
						$sWrk = $sFn($sFld, $dbid);
					else
						$sWrk = "";
					$this->Page_Filtering($fld, $sWrk, "custom", $wrkid);
					break;
				}
			}
		}
		return $sWrk;
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause, $Default = FALSE, $SaveFilter = FALSE) {
		$sWrk = ewr_GetExtendedFilter($fld, $Default, $this->DBID);
		if (!$Default)
			$this->Page_Filtering($fld, $sWrk, "extended", $fld->SearchOperator, $fld->SearchValue, $fld->SearchCondition, $fld->SearchOperator2, $fld->SearchValue2);
		if ($sWrk <> "") {
			ewr_AddFilter($FilterClause, $sWrk);
			if ($SaveFilter) $fld->CurrentFilter = $sWrk;
		}
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["so_$parm"]))
			$fld->SearchOperator = @$_GET["so_$parm"];
		if (isset($_GET["sv_$parm"])) {
			$fld->DropDownValue = @$_GET["sv_$parm"];
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv_$parm"])) {
			$fld->SearchValue = @$_GET["sv_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["so_$parm"])) {
			$fld->SearchOperator = @$_GET["so_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = @$_GET["sc_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = @$_GET["sv2_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = $_GET["so2_$parm"];
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DropDownValue)) {
			if (is_array($fld->DefaultDropDownValue)) {
				if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
					return TRUE;
				else
					return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
			} else {
				return TRUE;
			}
		} else {
			if (is_array($fld->DefaultDropDownValue))
				return TRUE;
			else
				$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWR_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWR_INIT_VALUE || $v2 == EWR_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewsaludservicios_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsaludservicios_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewsaludservicios_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsaludservicios_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewsaludservicios_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewsaludservicios_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewsaludservicios_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewsaludservicios_' . $parm] = $sv;
		$_SESSION['so_viewsaludservicios_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewsaludservicios_' . $parm] = $sv1;
		$_SESSION['so_viewsaludservicios_' . $parm] = $so1;
		$_SESSION['sc_viewsaludservicios_' . $parm] = $sc;
		$_SESSION['sv2_viewsaludservicios_' . $parm] = $sv2;
		$_SESSION['so2_viewsaludservicios_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWR_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $grFormError;

		// Initialize form error message
		$grFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return ($grFormError == "");
		if (!ewr_CheckInteger($this->ticketdeatencion->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->ticketdeatencion->FldErrMsg();
		}
		if (!ewr_CheckInteger($this->especialidad->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->especialidad->FldErrMsg();
		}
		if (!ewr_CheckInteger($this->observaciones->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->observaciones->FldErrMsg();
		}

		// Return validate result
		$ValidateForm = ($grFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$grFormError .= ($grFormError <> "") ? "<p>&nbsp;</p>" : "";
			$grFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_viewsaludservicios_$parm"] = "";
		$_SESSION["rf_viewsaludservicios_$parm"] = "";
		$_SESSION["rt_viewsaludservicios_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewsaludservicios_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewsaludservicios_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewsaludservicios_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/
		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/

		// Field ticketdeatencion
		$this->SetDefaultExtFilter($this->ticketdeatencion, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->ticketdeatencion);
		$sWrk = "";
		$this->BuildExtendedFilter($this->ticketdeatencion, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->ticketdeatencion, $sWrk, $this->ticketdeatencion->DefaultSelectionList);
		if (!$this->SearchCommand) $this->ticketdeatencion->SelectionList = $this->ticketdeatencion->DefaultSelectionList;

		// Field especialidad
		$this->SetDefaultExtFilter($this->especialidad, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->especialidad);
		$sWrk = "";
		$this->BuildExtendedFilter($this->especialidad, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->especialidad, $sWrk, $this->especialidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->especialidad->SelectionList = $this->especialidad->DefaultSelectionList;

		// Field nombreneonatal
		$this->SetDefaultExtFilter($this->nombreneonatal, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombreneonatal);

		// Field nombreescolar
		$this->SetDefaultExtFilter($this->nombreescolar, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombreescolar);

		// Field nombreotros
		$this->SetDefaultExtFilter($this->nombreotros, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombreotros);

		// Field observaciones
		$this->SetDefaultExtFilter($this->observaciones, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->observaciones);
		$sWrk = "";
		$this->BuildExtendedFilter($this->observaciones, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->observaciones, $sWrk, $this->observaciones->DefaultSelectionList);
		if (!$this->SearchCommand) $this->observaciones->SelectionList = $this->observaciones->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field ticketdeatencion
		// $this->ticketdeatencion->DefaultSelectionList = array("val1", "val2");
		// Field especialidad
		// $this->especialidad->DefaultSelectionList = array("val1", "val2");
		// Field observaciones
		// $this->observaciones->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check ticketdeatencion text filter
		if ($this->TextFilterApplied($this->ticketdeatencion))
			return TRUE;

		// Check ticketdeatencion popup filter
		if (!ewr_MatchedArray($this->ticketdeatencion->DefaultSelectionList, $this->ticketdeatencion->SelectionList))
			return TRUE;

		// Check especialidad text filter
		if ($this->TextFilterApplied($this->especialidad))
			return TRUE;

		// Check especialidad popup filter
		if (!ewr_MatchedArray($this->especialidad->DefaultSelectionList, $this->especialidad->SelectionList))
			return TRUE;

		// Check nombreneonatal text filter
		if ($this->TextFilterApplied($this->nombreneonatal))
			return TRUE;

		// Check nombreescolar text filter
		if ($this->TextFilterApplied($this->nombreescolar))
			return TRUE;

		// Check nombreotros text filter
		if ($this->TextFilterApplied($this->nombreotros))
			return TRUE;

		// Check observaciones text filter
		if ($this->TextFilterApplied($this->observaciones))
			return TRUE;

		// Check observaciones popup filter
		if (!ewr_MatchedArray($this->observaciones->DefaultSelectionList, $this->observaciones->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field ticketdeatencion
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->ticketdeatencion, $sExtWrk);
		if (is_array($this->ticketdeatencion->SelectionList))
			$sWrk = ewr_JoinArray($this->ticketdeatencion->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->ticketdeatencion->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field especialidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->especialidad, $sExtWrk);
		if (is_array($this->especialidad->SelectionList))
			$sWrk = ewr_JoinArray($this->especialidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->especialidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombreneonatal
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreneonatal, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreneonatal->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombreescolar
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreescolar, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreescolar->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombreotros
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreotros, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreotros->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field observaciones
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->observaciones, $sExtWrk);
		if (is_array($this->observaciones->SelectionList))
			$sWrk = ewr_JoinArray($this->observaciones->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->observaciones->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = "";
		$divdataclass = "";

		// Show Filters
		if ($sFilterList <> "" || $showDate) {
			$sMessage = "<div" . $divstyle . $divdataclass . "><div id=\"ewrFilterList\" class=\"alert alert-info\">";
			if ($showDate)
				$sMessage .= "<div id=\"ewrCurrentDate\">" . $ReportLanguage->Phrase("ReportGeneratedDate") . ewr_FormatDateTime(date("Y-m-d H:i:s"), 1) . "</div>";
			if ($sFilterList <> "")
				$sMessage .= "<div id=\"ewrCurrentFilters\">" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList;
			$sMessage .= "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field ticketdeatencion
		$sWrk = "";
		if ($this->ticketdeatencion->SearchValue <> "" || $this->ticketdeatencion->SearchValue2 <> "") {
			$sWrk = "\"sv_ticketdeatencion\":\"" . ewr_JsEncode2($this->ticketdeatencion->SearchValue) . "\"," .
				"\"so_ticketdeatencion\":\"" . ewr_JsEncode2($this->ticketdeatencion->SearchOperator) . "\"," .
				"\"sc_ticketdeatencion\":\"" . ewr_JsEncode2($this->ticketdeatencion->SearchCondition) . "\"," .
				"\"sv2_ticketdeatencion\":\"" . ewr_JsEncode2($this->ticketdeatencion->SearchValue2) . "\"," .
				"\"so2_ticketdeatencion\":\"" . ewr_JsEncode2($this->ticketdeatencion->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->ticketdeatencion->SelectionList <> EWR_INIT_VALUE) ? $this->ticketdeatencion->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_ticketdeatencion\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field especialidad
		$sWrk = "";
		if ($this->especialidad->SearchValue <> "" || $this->especialidad->SearchValue2 <> "") {
			$sWrk = "\"sv_especialidad\":\"" . ewr_JsEncode2($this->especialidad->SearchValue) . "\"," .
				"\"so_especialidad\":\"" . ewr_JsEncode2($this->especialidad->SearchOperator) . "\"," .
				"\"sc_especialidad\":\"" . ewr_JsEncode2($this->especialidad->SearchCondition) . "\"," .
				"\"sv2_especialidad\":\"" . ewr_JsEncode2($this->especialidad->SearchValue2) . "\"," .
				"\"so2_especialidad\":\"" . ewr_JsEncode2($this->especialidad->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->especialidad->SelectionList <> EWR_INIT_VALUE) ? $this->especialidad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_especialidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombreneonatal
		$sWrk = "";
		if ($this->nombreneonatal->SearchValue <> "" || $this->nombreneonatal->SearchValue2 <> "") {
			$sWrk = "\"sv_nombreneonatal\":\"" . ewr_JsEncode2($this->nombreneonatal->SearchValue) . "\"," .
				"\"so_nombreneonatal\":\"" . ewr_JsEncode2($this->nombreneonatal->SearchOperator) . "\"," .
				"\"sc_nombreneonatal\":\"" . ewr_JsEncode2($this->nombreneonatal->SearchCondition) . "\"," .
				"\"sv2_nombreneonatal\":\"" . ewr_JsEncode2($this->nombreneonatal->SearchValue2) . "\"," .
				"\"so2_nombreneonatal\":\"" . ewr_JsEncode2($this->nombreneonatal->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombreescolar
		$sWrk = "";
		if ($this->nombreescolar->SearchValue <> "" || $this->nombreescolar->SearchValue2 <> "") {
			$sWrk = "\"sv_nombreescolar\":\"" . ewr_JsEncode2($this->nombreescolar->SearchValue) . "\"," .
				"\"so_nombreescolar\":\"" . ewr_JsEncode2($this->nombreescolar->SearchOperator) . "\"," .
				"\"sc_nombreescolar\":\"" . ewr_JsEncode2($this->nombreescolar->SearchCondition) . "\"," .
				"\"sv2_nombreescolar\":\"" . ewr_JsEncode2($this->nombreescolar->SearchValue2) . "\"," .
				"\"so2_nombreescolar\":\"" . ewr_JsEncode2($this->nombreescolar->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombreotros
		$sWrk = "";
		if ($this->nombreotros->SearchValue <> "" || $this->nombreotros->SearchValue2 <> "") {
			$sWrk = "\"sv_nombreotros\":\"" . ewr_JsEncode2($this->nombreotros->SearchValue) . "\"," .
				"\"so_nombreotros\":\"" . ewr_JsEncode2($this->nombreotros->SearchOperator) . "\"," .
				"\"sc_nombreotros\":\"" . ewr_JsEncode2($this->nombreotros->SearchCondition) . "\"," .
				"\"sv2_nombreotros\":\"" . ewr_JsEncode2($this->nombreotros->SearchValue2) . "\"," .
				"\"so2_nombreotros\":\"" . ewr_JsEncode2($this->nombreotros->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field observaciones
		$sWrk = "";
		if ($this->observaciones->SearchValue <> "" || $this->observaciones->SearchValue2 <> "") {
			$sWrk = "\"sv_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchValue) . "\"," .
				"\"so_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchOperator) . "\"," .
				"\"sc_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchCondition) . "\"," .
				"\"sv2_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchValue2) . "\"," .
				"\"so2_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->observaciones->SelectionList <> EWR_INIT_VALUE) ? $this->observaciones->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_observaciones\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Return filter list in json
		if ($sFilterList <> "")
			return "{" . $sFilterList . "}";
		else
			return "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		return $this->SetupFilterList($filter);
	}

	// Setup list of filters
	function SetupFilterList($filter) {
		if (!is_array($filter))
			return FALSE;

		// Field ticketdeatencion
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_ticketdeatencion", $filter) || array_key_exists("so_ticketdeatencion", $filter) ||
			array_key_exists("sc_ticketdeatencion", $filter) ||
			array_key_exists("sv2_ticketdeatencion", $filter) || array_key_exists("so2_ticketdeatencion", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_ticketdeatencion"], @$filter["so_ticketdeatencion"], @$filter["sc_ticketdeatencion"], @$filter["sv2_ticketdeatencion"], @$filter["so2_ticketdeatencion"], "ticketdeatencion");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_ticketdeatencion", $filter)) {
			$sWrk = $filter["sel_ticketdeatencion"];
			$sWrk = explode("||", $sWrk);
			$this->ticketdeatencion->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludservicios_ticketdeatencion"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "ticketdeatencion"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "ticketdeatencion");
			$this->ticketdeatencion->SelectionList = "";
			$_SESSION["sel_viewsaludservicios_ticketdeatencion"] = "";
		}

		// Field especialidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_especialidad", $filter) || array_key_exists("so_especialidad", $filter) ||
			array_key_exists("sc_especialidad", $filter) ||
			array_key_exists("sv2_especialidad", $filter) || array_key_exists("so2_especialidad", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_especialidad"], @$filter["so_especialidad"], @$filter["sc_especialidad"], @$filter["sv2_especialidad"], @$filter["so2_especialidad"], "especialidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_especialidad", $filter)) {
			$sWrk = $filter["sel_especialidad"];
			$sWrk = explode("||", $sWrk);
			$this->especialidad->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludservicios_especialidad"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "especialidad"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "especialidad");
			$this->especialidad->SelectionList = "";
			$_SESSION["sel_viewsaludservicios_especialidad"] = "";
		}

		// Field nombreneonatal
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombreneonatal", $filter) || array_key_exists("so_nombreneonatal", $filter) ||
			array_key_exists("sc_nombreneonatal", $filter) ||
			array_key_exists("sv2_nombreneonatal", $filter) || array_key_exists("so2_nombreneonatal", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombreneonatal"], @$filter["so_nombreneonatal"], @$filter["sc_nombreneonatal"], @$filter["sv2_nombreneonatal"], @$filter["so2_nombreneonatal"], "nombreneonatal");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreneonatal");
		}

		// Field nombreescolar
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombreescolar", $filter) || array_key_exists("so_nombreescolar", $filter) ||
			array_key_exists("sc_nombreescolar", $filter) ||
			array_key_exists("sv2_nombreescolar", $filter) || array_key_exists("so2_nombreescolar", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombreescolar"], @$filter["so_nombreescolar"], @$filter["sc_nombreescolar"], @$filter["sv2_nombreescolar"], @$filter["so2_nombreescolar"], "nombreescolar");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreescolar");
		}

		// Field nombreotros
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombreotros", $filter) || array_key_exists("so_nombreotros", $filter) ||
			array_key_exists("sc_nombreotros", $filter) ||
			array_key_exists("sv2_nombreotros", $filter) || array_key_exists("so2_nombreotros", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombreotros"], @$filter["so_nombreotros"], @$filter["sc_nombreotros"], @$filter["sv2_nombreotros"], @$filter["so2_nombreotros"], "nombreotros");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreotros");
		}

		// Field observaciones
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_observaciones", $filter) || array_key_exists("so_observaciones", $filter) ||
			array_key_exists("sc_observaciones", $filter) ||
			array_key_exists("sv2_observaciones", $filter) || array_key_exists("so2_observaciones", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_observaciones"], @$filter["so_observaciones"], @$filter["sc_observaciones"], @$filter["sv2_observaciones"], @$filter["so2_observaciones"], "observaciones");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_observaciones", $filter)) {
			$sWrk = $filter["sel_observaciones"];
			$sWrk = explode("||", $sWrk);
			$this->observaciones->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludservicios_observaciones"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "observaciones"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "observaciones");
			$this->observaciones->SelectionList = "";
			$_SESSION["sel_viewsaludservicios_observaciones"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->ExtendedFilterExist($this->ticketdeatencion)) {
			if (is_array($this->ticketdeatencion->SelectionList)) {
				$sFilter = ewr_FilterSql($this->ticketdeatencion, "`ticketdeatencion`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->ticketdeatencion, $sFilter, "popup");
				$this->ticketdeatencion->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->especialidad)) {
			if (is_array($this->especialidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->especialidad, "`especialidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->especialidad, $sFilter, "popup");
				$this->especialidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->observaciones)) {
			if (is_array($this->observaciones->SelectionList)) {
				$sFilter = ewr_FilterSql($this->observaciones, "`observaciones`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->observaciones, $sFilter, "popup");
				$this->observaciones->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		return $sWrk;
	}

	// Get sort parameters based on sort links clicked
	function GetSort($options = array()) {
		if ($this->DrillDown)
			return "";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : @$_GET["order"];
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : @$_GET["ordertype"];

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->ticketdeatencion->setSort("");
			$this->especialidad->setSort("");
			$this->nombreneonatal->setSort("");
			$this->nombreescolar->setSort("");
			$this->nombreotros->setSort("");
			$this->observaciones->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->ticketdeatencion); // ticketdeatencion
			$this->UpdateSort($this->especialidad); // especialidad
			$this->UpdateSort($this->nombreneonatal); // nombreneonatal
			$this->UpdateSort($this->nombreescolar); // nombreescolar
			$this->UpdateSort($this->nombreotros); // nombreotros
			$this->UpdateSort($this->observaciones); // observaciones
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export to WORD
	function ExportWord($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			header('Content-Type: application/vnd.ms-word' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
			echo $html;
		}
		return $saveToFile;
	}

	// Export to EXCEL
	function ExportExcel($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
			echo $html;
		}
		return $saveToFile;
	}

	// Export PDF
	function ExportPdf($html, $options = array()) {
		global $gsExportFile;
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		if (EWR_DEBUG_ENABLED) // Add debug message
			$html = str_replace("</body>", ewr_DebugMsg() . "</body>", $html);
		$dompdf = new \Dompdf\Dompdf(array("pdf_backend" => "Cpdf"));
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml encoding="uft-8">' . ewr_ConvertToUtf8($html)); // Convert to utf-8
		$spans = $doc->getElementsByTagName("span");
		foreach ($spans as $span) {
			if ($span->getAttribute("class") == "ewFilterCaption")
				$span->parentNode->insertBefore($doc->createElement("span", ":&nbsp;"), $span->nextSibling);
		}
		$images = $doc->getElementsByTagName("img");
		$pageSize = "a4";
		$pageOrientation = "portrait";
		foreach ($images as $image) {
			$imagefn = $image->getAttribute("src");
			if (file_exists($imagefn)) {
				$imagefn = realpath($imagefn);
				$size = getimagesize($imagefn); // Get image size
				if ($size[0] <> 0) {
					if (ewr_SameText($pageSize, "letter")) { // Letter paper (8.5 in. by 11 in.)
						$w = ewr_SameText($pageOrientation, "portrait") ? 216 : 279;
					} elseif (ewr_SameText($pageSize, "legal")) { // Legal paper (8.5 in. by 14 in.)
						$w = ewr_SameText($pageOrientation, "portrait") ? 216 : 356;
					} else {
						$w = ewr_SameText($pageOrientation, "portrait") ? 210 : 297; // A4 paper (210 mm by 297 mm)
					}
					$w = min($size[0], ($w - 20 * 2) / 25.4 * 72); // Resize image, adjust the multiplying factor if necessary
					$h = $w / $size[0] * $size[1];
					$image->setAttribute("width", $w);
					$image->setAttribute("height", $h);
				}
			}
		}
		$html = $doc->saveHTML();
		$html = ewr_ConvertFromUtf8($html);
		$dompdf->load_html($html);
		$dompdf->set_paper($pageSize, $pageOrientation);
		$dompdf->render();
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $dompdf->output());
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			$sExportFile = strtolower(substr($gsExportFile, -4)) == ".pdf" ? $gsExportFile : $gsExportFile . ".pdf";
			$dompdf->stream($sExportFile, array("Attachment" => 1)); // 0 to open in browser, 1 to download
		}
		ewr_DeleteTmpImages($html);
		return $saveToFile;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
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
}
?>
<?php

// Create page object
if (!isset($viewsaludservicios_rpt)) $viewsaludservicios_rpt = new crviewsaludservicios_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewsaludservicios_rpt;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();
if (!$grDashboardReport)
	ewr_Header(FALSE);

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php if (!$grDashboardReport) { ?>
<?php include_once "header.php" ?>
<?php include_once "phprptinc/header.php" ?>
<?php } ?>
<?php if ($Page->Export == "") { ?>
<script type="text/javascript">

// Create page object
var viewsaludservicios_rpt = new ewr_Page("viewsaludservicios_rpt");

// Page properties
viewsaludservicios_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewsaludservicios_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewsaludserviciosrpt = new ewr_Form("fviewsaludserviciosrpt");

// Validate method
fviewsaludserviciosrpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_ticketdeatencion;
	if (elm && !ewr_CheckInteger(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->ticketdeatencion->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv_especialidad;
	if (elm && !ewr_CheckInteger(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->especialidad->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv_observaciones;
	if (elm && !ewr_CheckInteger(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->observaciones->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewsaludserviciosrpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewsaludserviciosrpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewsaludserviciosrpt.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<a id="top"></a>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<!-- Content Container -->
<div id="ewContainer" class="container-fluid ewContainer">
<?php } ?>
<?php if (@$Page->GenOptions["showfilter"] == "1") { ?>
<?php $Page->ShowFilterList(TRUE) ?>
<?php } ?>
<div class="ewToolbar">
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
	$Page->GenerateOptions->Render("body");
}
?>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<div class="row">
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<!-- Center Container - Report -->
<div id="ewCenter" class="col-sm-12 ewCenter">
<?php } ?>
<!-- Summary Report begins -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<!-- Search form (begin) -->
<form name="fviewsaludserviciosrpt" id="fviewsaludserviciosrpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewsaludserviciosrpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_ticketdeatencion" class="ewCell form-group">
	<label for="sv_ticketdeatencion" class="ewSearchCaption ewLabel"><?php echo $Page->ticketdeatencion->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_ticketdeatencion" id="so_ticketdeatencion" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->ticketdeatencion->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludservicios" data-field="x_ticketdeatencion" id="sv_ticketdeatencion" name="sv_ticketdeatencion" placeholder="<?php echo $Page->ticketdeatencion->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->ticketdeatencion->SearchValue) ?>"<?php echo $Page->ticketdeatencion->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_especialidad" class="ewCell form-group">
	<label for="sv_especialidad" class="ewSearchCaption ewLabel"><?php echo $Page->especialidad->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_especialidad" id="so_especialidad" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->especialidad->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludservicios" data-field="x_especialidad" id="sv_especialidad" name="sv_especialidad" size="30" placeholder="<?php echo $Page->especialidad->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->especialidad->SearchValue) ?>"<?php echo $Page->especialidad->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_3" class="ewRow">
<div id="c_nombreneonatal" class="ewCell form-group">
	<label for="sv_nombreneonatal" class="ewSearchCaption ewLabel"><?php echo $Page->nombreneonatal->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombreneonatal" id="so_nombreneonatal" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombreneonatal->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludservicios" data-field="x_nombreneonatal" id="sv_nombreneonatal" name="sv_nombreneonatal" size="30" maxlength="100" placeholder="<?php echo $Page->nombreneonatal->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombreneonatal->SearchValue) ?>"<?php echo $Page->nombreneonatal->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_4" class="ewRow">
<div id="c_nombreescolar" class="ewCell form-group">
	<label for="sv_nombreescolar" class="ewSearchCaption ewLabel"><?php echo $Page->nombreescolar->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombreescolar" id="so_nombreescolar" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombreescolar->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludservicios" data-field="x_nombreescolar" id="sv_nombreescolar" name="sv_nombreescolar" size="30" maxlength="100" placeholder="<?php echo $Page->nombreescolar->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombreescolar->SearchValue) ?>"<?php echo $Page->nombreescolar->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_5" class="ewRow">
<div id="c_nombreotros" class="ewCell form-group">
	<label for="sv_nombreotros" class="ewSearchCaption ewLabel"><?php echo $Page->nombreotros->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombreotros" id="so_nombreotros" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombreotros->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludservicios" data-field="x_nombreotros" id="sv_nombreotros" name="sv_nombreotros" size="30" maxlength="100" placeholder="<?php echo $Page->nombreotros->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombreotros->SearchValue) ?>"<?php echo $Page->nombreotros->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_6" class="ewRow">
<div id="c_observaciones" class="ewCell form-group">
	<label for="sv_observaciones" class="ewSearchCaption ewLabel"><?php echo $Page->observaciones->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_observaciones" id="so_observaciones" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->observaciones->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludservicios" data-field="x_observaciones" id="sv_observaciones" name="sv_observaciones" size="30" placeholder="<?php echo $Page->observaciones->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->observaciones->SearchValue) ?>"<?php echo $Page->observaciones->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewsaludserviciosrpt.Init();
fviewsaludserviciosrpt.FilterList = <?php echo $Page->GetFilterList() ?>;
</script>
<!-- Search form (end) -->
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->ShowFilterList() ?>
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetRow(1);
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray(2, -1);
$Page->GrpIdx[0] = -1;
$Page->GrpIdx[1] = $Page->StopGrp - $Page->StartGrp + 1;
while ($rs && !$rs->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_viewsaludservicios" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->ticketdeatencion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ticketdeatencion"><div class="viewsaludservicios_ticketdeatencion"><span class="ewTableHeaderCaption"><?php echo $Page->ticketdeatencion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ticketdeatencion">
<?php if ($Page->SortUrl($Page->ticketdeatencion) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludservicios_ticketdeatencion">
			<span class="ewTableHeaderCaption"><?php echo $Page->ticketdeatencion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludservicios_ticketdeatencion', range: false, from: '<?php echo $Page->ticketdeatencion->RangeFrom; ?>', to: '<?php echo $Page->ticketdeatencion->RangeTo; ?>', url: 'viewsaludserviciosrpt.php' });" id="x_ticketdeatencion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludservicios_ticketdeatencion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ticketdeatencion) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ticketdeatencion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ticketdeatencion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ticketdeatencion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludservicios_ticketdeatencion', range: false, from: '<?php echo $Page->ticketdeatencion->RangeFrom; ?>', to: '<?php echo $Page->ticketdeatencion->RangeTo; ?>', url: 'viewsaludserviciosrpt.php' });" id="x_ticketdeatencion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->especialidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="especialidad"><div class="viewsaludservicios_especialidad"><span class="ewTableHeaderCaption"><?php echo $Page->especialidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="especialidad">
<?php if ($Page->SortUrl($Page->especialidad) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludservicios_especialidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->especialidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludservicios_especialidad', range: false, from: '<?php echo $Page->especialidad->RangeFrom; ?>', to: '<?php echo $Page->especialidad->RangeTo; ?>', url: 'viewsaludserviciosrpt.php' });" id="x_especialidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludservicios_especialidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->especialidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->especialidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->especialidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->especialidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludservicios_especialidad', range: false, from: '<?php echo $Page->especialidad->RangeFrom; ?>', to: '<?php echo $Page->especialidad->RangeTo; ?>', url: 'viewsaludserviciosrpt.php' });" id="x_especialidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreneonatal->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreneonatal"><div class="viewsaludservicios_nombreneonatal"><span class="ewTableHeaderCaption"><?php echo $Page->nombreneonatal->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreneonatal">
<?php if ($Page->SortUrl($Page->nombreneonatal) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludservicios_nombreneonatal">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreneonatal->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludservicios_nombreneonatal" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreneonatal) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreneonatal->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreneonatal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreneonatal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreescolar->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreescolar"><div class="viewsaludservicios_nombreescolar"><span class="ewTableHeaderCaption"><?php echo $Page->nombreescolar->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreescolar">
<?php if ($Page->SortUrl($Page->nombreescolar) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludservicios_nombreescolar">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreescolar->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludservicios_nombreescolar" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreescolar) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreescolar->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreescolar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreescolar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreotros->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreotros"><div class="viewsaludservicios_nombreotros"><span class="ewTableHeaderCaption"><?php echo $Page->nombreotros->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreotros">
<?php if ($Page->SortUrl($Page->nombreotros) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludservicios_nombreotros">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreotros->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludservicios_nombreotros" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreotros) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreotros->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreotros->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreotros->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="observaciones"><div class="viewsaludservicios_observaciones"><span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="observaciones">
<?php if ($Page->SortUrl($Page->observaciones) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludservicios_observaciones">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludservicios_observaciones', range: false, from: '<?php echo $Page->observaciones->RangeFrom; ?>', to: '<?php echo $Page->observaciones->RangeTo; ?>', url: 'viewsaludserviciosrpt.php' });" id="x_observaciones<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludservicios_observaciones" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->observaciones) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludservicios_observaciones', range: false, from: '<?php echo $Page->observaciones->RangeFrom; ?>', to: '<?php echo $Page->observaciones->RangeTo; ?>', url: 'viewsaludserviciosrpt.php' });" id="x_observaciones<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}
	$Page->RecCount++;
	$Page->RecIndex++;
?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->ticketdeatencion->Visible) { ?>
		<td data-field="ticketdeatencion"<?php echo $Page->ticketdeatencion->CellAttributes() ?>>
<span<?php echo $Page->ticketdeatencion->ViewAttributes() ?>><?php echo $Page->ticketdeatencion->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->especialidad->Visible) { ?>
		<td data-field="especialidad"<?php echo $Page->especialidad->CellAttributes() ?>>
<span<?php echo $Page->especialidad->ViewAttributes() ?>><?php echo $Page->especialidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombreneonatal->Visible) { ?>
		<td data-field="nombreneonatal"<?php echo $Page->nombreneonatal->CellAttributes() ?>>
<span<?php echo $Page->nombreneonatal->ViewAttributes() ?>><?php echo $Page->nombreneonatal->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombreescolar->Visible) { ?>
		<td data-field="nombreescolar"<?php echo $Page->nombreescolar->CellAttributes() ?>>
<span<?php echo $Page->nombreescolar->ViewAttributes() ?>><?php echo $Page->nombreescolar->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombreotros->Visible) { ?>
		<td data-field="nombreotros"<?php echo $Page->nombreotros->CellAttributes() ?>>
<span<?php echo $Page->nombreotros->ViewAttributes() ?>><?php echo $Page->nombreotros->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
		<td data-field="observaciones"<?php echo $Page->observaciones->CellAttributes() ?>>
<span<?php echo $Page->observaciones->ViewAttributes() ?>><?php echo $Page->observaciones->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);
	$Page->GrpCount++;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && TRUE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_viewsaludservicios" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || TRUE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "viewsaludserviciosrptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /#ewCenter -->
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /.row -->
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /.ewContainer -->
<?php } ?>
<?php
$Page->ShowPageFooter();
if (EWR_DEBUG_ENABLED)
	echo ewr_DebugMsg();
?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// console.log("page loaded");

</script>
<?php } ?>
<?php if (!$grDashboardReport) { ?>
<?php include_once "phprptinc/footer.php" ?>
<?php include_once "footer.php" ?>
<?php } ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
