<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewsaludreferenciarptinfo.php" ?>
<?php

//
// Page class
//

$viewsaludreferencia_rpt = NULL; // Initialize page object first

class crviewsaludreferencia_rpt extends crviewsaludreferencia {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewsaludreferencia_rpt';

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

		// Table object (viewsaludreferencia)
		if (!isset($GLOBALS["viewsaludreferencia"])) {
			$GLOBALS["viewsaludreferencia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewsaludreferencia"];
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
			define("EWR_TABLE_NAME", 'viewsaludreferencia', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewsaludreferenciarpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewsaludreferencia');
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
		$this->nombrescompleto->PlaceHolder = $this->nombrescompleto->FldCaption();
		$this->nombrescentromedico->PlaceHolder = $this->nombrescentromedico->FldCaption();
		$this->direccion->PlaceHolder = $this->direccion->FldCaption();
		$this->telefono->PlaceHolder = $this->telefono->FldCaption();
		$this->nombre->PlaceHolder = $this->nombre->FldCaption();

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
		$item->Visible = TRUE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a class=\"ewrExportLink ewExcel\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a class=\"ewrExportLink ewWord\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;
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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewsaludreferencia\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewsaludreferencia',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewsaludreferenciarpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewsaludreferenciarpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewsaludreferenciarpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->nombrescompleto->SetVisibility();
		$this->nombrescentromedico->SetVisibility();
		$this->direccion->SetVisibility();
		$this->telefono->SetVisibility();
		$this->nombre->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 6;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->nombrescompleto->SelectionList = "";
		$this->nombrescompleto->DefaultSelectionList = "";
		$this->nombrescompleto->ValueList = "";
		$this->nombrescentromedico->SelectionList = "";
		$this->nombrescentromedico->DefaultSelectionList = "";
		$this->nombrescentromedico->ValueList = "";
		$this->direccion->SelectionList = "";
		$this->direccion->DefaultSelectionList = "";
		$this->direccion->ValueList = "";
		$this->telefono->SelectionList = "";
		$this->telefono->DefaultSelectionList = "";
		$this->telefono->ValueList = "";
		$this->nombre->SelectionList = "";
		$this->nombre->DefaultSelectionList = "";
		$this->nombre->ValueList = "";

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
				$this->FirstRowData['nombrescompleto'] = ewr_Conv($rs->fields('nombrescompleto'), 200);
				$this->FirstRowData['nombrescentromedico'] = ewr_Conv($rs->fields('nombrescentromedico'), 200);
				$this->FirstRowData['direccion'] = ewr_Conv($rs->fields('direccion'), 3);
				$this->FirstRowData['telefono'] = ewr_Conv($rs->fields('telefono'), 3);
				$this->FirstRowData['nombre'] = ewr_Conv($rs->fields('nombre'), 200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->nombrescompleto->setDbValue($rs->fields('nombrescompleto'));
			$this->nombrescentromedico->setDbValue($rs->fields('nombrescentromedico'));
			$this->direccion->setDbValue($rs->fields('direccion'));
			$this->telefono->setDbValue($rs->fields('telefono'));
			$this->nombre->setDbValue($rs->fields('nombre'));
			$this->Val[1] = $this->nombrescompleto->CurrentValue;
			$this->Val[2] = $this->nombrescentromedico->CurrentValue;
			$this->Val[3] = $this->direccion->CurrentValue;
			$this->Val[4] = $this->telefono->CurrentValue;
			$this->Val[5] = $this->nombre->CurrentValue;
		} else {
			$this->nombrescompleto->setDbValue("");
			$this->nombrescentromedico->setDbValue("");
			$this->direccion->setDbValue("");
			$this->telefono->setDbValue("");
			$this->nombre->setDbValue("");
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
			// Build distinct values for nombrescompleto

			if ($popupname == 'viewsaludreferencia_nombrescompleto') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nombrescompleto, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nombrescompleto->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nombrescompleto->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nombrescompleto->setDbValue($rswrk->fields[0]);
					$this->nombrescompleto->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nombrescompleto->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nombrescompleto->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nombrescompleto->ValueList, $this->nombrescompleto->CurrentValue, $this->nombrescompleto->ViewValue, FALSE, $this->nombrescompleto->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nombrescompleto->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nombrescompleto->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nombrescompleto;
			}

			// Build distinct values for nombrescentromedico
			if ($popupname == 'viewsaludreferencia_nombrescentromedico') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nombrescentromedico, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nombrescentromedico->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nombrescentromedico->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nombrescentromedico->setDbValue($rswrk->fields[0]);
					$this->nombrescentromedico->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nombrescentromedico->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nombrescentromedico->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nombrescentromedico->ValueList, $this->nombrescentromedico->CurrentValue, $this->nombrescentromedico->ViewValue, FALSE, $this->nombrescentromedico->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nombrescentromedico->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nombrescentromedico->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nombrescentromedico;
			}

			// Build distinct values for direccion
			if ($popupname == 'viewsaludreferencia_direccion') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->direccion, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->direccion->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->direccion->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->direccion->setDbValue($rswrk->fields[0]);
					$this->direccion->ViewValue = @$rswrk->fields[1];
					if (is_null($this->direccion->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->direccion->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->direccion->ValueList, $this->direccion->CurrentValue, $this->direccion->ViewValue, FALSE, $this->direccion->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->direccion->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->direccion->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->direccion;
			}

			// Build distinct values for telefono
			if ($popupname == 'viewsaludreferencia_telefono') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->telefono, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->telefono->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->telefono->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->telefono->setDbValue($rswrk->fields[0]);
					$this->telefono->ViewValue = @$rswrk->fields[1];
					if (is_null($this->telefono->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->telefono->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->telefono->ValueList, $this->telefono->CurrentValue, $this->telefono->ViewValue, FALSE, $this->telefono->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->telefono->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->telefono->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->telefono;
			}

			// Build distinct values for nombre
			if ($popupname == 'viewsaludreferencia_nombre') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nombre, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nombre->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nombre->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nombre->setDbValue($rswrk->fields[0]);
					$this->nombre->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nombre->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nombre->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nombre->ValueList, $this->nombre->CurrentValue, $this->nombre->ViewValue, FALSE, $this->nombre->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nombre->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nombre->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nombre;
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
				$this->ClearSessionSelection('nombrescompleto');
				$this->ClearSessionSelection('nombrescentromedico');
				$this->ClearSessionSelection('direccion');
				$this->ClearSessionSelection('telefono');
				$this->ClearSessionSelection('nombre');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get nombrescompleto selected values

		if (is_array(@$_SESSION["sel_viewsaludreferencia_nombrescompleto"])) {
			$this->LoadSelectionFromSession('nombrescompleto');
		} elseif (@$_SESSION["sel_viewsaludreferencia_nombrescompleto"] == EWR_INIT_VALUE) { // Select all
			$this->nombrescompleto->SelectionList = "";
		}

		// Get nombrescentromedico selected values
		if (is_array(@$_SESSION["sel_viewsaludreferencia_nombrescentromedico"])) {
			$this->LoadSelectionFromSession('nombrescentromedico');
		} elseif (@$_SESSION["sel_viewsaludreferencia_nombrescentromedico"] == EWR_INIT_VALUE) { // Select all
			$this->nombrescentromedico->SelectionList = "";
		}

		// Get direccion selected values
		if (is_array(@$_SESSION["sel_viewsaludreferencia_direccion"])) {
			$this->LoadSelectionFromSession('direccion');
		} elseif (@$_SESSION["sel_viewsaludreferencia_direccion"] == EWR_INIT_VALUE) { // Select all
			$this->direccion->SelectionList = "";
		}

		// Get telefono selected values
		if (is_array(@$_SESSION["sel_viewsaludreferencia_telefono"])) {
			$this->LoadSelectionFromSession('telefono');
		} elseif (@$_SESSION["sel_viewsaludreferencia_telefono"] == EWR_INIT_VALUE) { // Select all
			$this->telefono->SelectionList = "";
		}

		// Get nombre selected values
		if (is_array(@$_SESSION["sel_viewsaludreferencia_nombre"])) {
			$this->LoadSelectionFromSession('nombre');
		} elseif (@$_SESSION["sel_viewsaludreferencia_nombre"] == EWR_INIT_VALUE) { // Select all
			$this->nombre->SelectionList = "";
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

			// nombrescompleto
			$this->nombrescompleto->HrefValue = "";

			// nombrescentromedico
			$this->nombrescentromedico->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// telefono
			$this->telefono->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// nombrescompleto
			$this->nombrescompleto->ViewValue = $this->nombrescompleto->CurrentValue;
			$this->nombrescompleto->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombrescentromedico
			$this->nombrescentromedico->ViewValue = $this->nombrescentromedico->CurrentValue;
			$this->nombrescentromedico->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombrescompleto
			$this->nombrescompleto->HrefValue = "";

			// nombrescentromedico
			$this->nombrescentromedico->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// telefono
			$this->telefono->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// nombrescompleto
			$CurrentValue = $this->nombrescompleto->CurrentValue;
			$ViewValue = &$this->nombrescompleto->ViewValue;
			$ViewAttrs = &$this->nombrescompleto->ViewAttrs;
			$CellAttrs = &$this->nombrescompleto->CellAttrs;
			$HrefValue = &$this->nombrescompleto->HrefValue;
			$LinkAttrs = &$this->nombrescompleto->LinkAttrs;
			$this->Cell_Rendered($this->nombrescompleto, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombrescentromedico
			$CurrentValue = $this->nombrescentromedico->CurrentValue;
			$ViewValue = &$this->nombrescentromedico->ViewValue;
			$ViewAttrs = &$this->nombrescentromedico->ViewAttrs;
			$CellAttrs = &$this->nombrescentromedico->CellAttrs;
			$HrefValue = &$this->nombrescentromedico->HrefValue;
			$LinkAttrs = &$this->nombrescentromedico->LinkAttrs;
			$this->Cell_Rendered($this->nombrescentromedico, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// direccion
			$CurrentValue = $this->direccion->CurrentValue;
			$ViewValue = &$this->direccion->ViewValue;
			$ViewAttrs = &$this->direccion->ViewAttrs;
			$CellAttrs = &$this->direccion->CellAttrs;
			$HrefValue = &$this->direccion->HrefValue;
			$LinkAttrs = &$this->direccion->LinkAttrs;
			$this->Cell_Rendered($this->direccion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// telefono
			$CurrentValue = $this->telefono->CurrentValue;
			$ViewValue = &$this->telefono->ViewValue;
			$ViewAttrs = &$this->telefono->ViewAttrs;
			$CellAttrs = &$this->telefono->CellAttrs;
			$HrefValue = &$this->telefono->HrefValue;
			$LinkAttrs = &$this->telefono->LinkAttrs;
			$this->Cell_Rendered($this->telefono, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombre
			$CurrentValue = $this->nombre->CurrentValue;
			$ViewValue = &$this->nombre->ViewValue;
			$ViewAttrs = &$this->nombre->ViewAttrs;
			$CellAttrs = &$this->nombre->CellAttrs;
			$HrefValue = &$this->nombre->HrefValue;
			$LinkAttrs = &$this->nombre->LinkAttrs;
			$this->Cell_Rendered($this->nombre, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->nombrescompleto->Visible) $this->DtlColumnCount += 1;
		if ($this->nombrescentromedico->Visible) $this->DtlColumnCount += 1;
		if ($this->direccion->Visible) $this->DtlColumnCount += 1;
		if ($this->telefono->Visible) $this->DtlColumnCount += 1;
		if ($this->nombre->Visible) $this->DtlColumnCount += 1;
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

			// Clear extended filter for field nombrescompleto
			if ($this->ClearExtFilter == 'viewsaludreferencia_nombrescompleto')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'nombrescompleto');

			// Clear extended filter for field nombrescentromedico
			if ($this->ClearExtFilter == 'viewsaludreferencia_nombrescentromedico')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'nombrescentromedico');

			// Clear extended filter for field direccion
			if ($this->ClearExtFilter == 'viewsaludreferencia_direccion')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'direccion');

			// Clear extended filter for field telefono
			if ($this->ClearExtFilter == 'viewsaludreferencia_telefono')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'telefono');

			// Clear extended filter for field nombre
			if ($this->ClearExtFilter == 'viewsaludreferencia_nombre')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'nombre');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionFilterValues($this->nombrescompleto->SearchValue, $this->nombrescompleto->SearchOperator, $this->nombrescompleto->SearchCondition, $this->nombrescompleto->SearchValue2, $this->nombrescompleto->SearchOperator2, 'nombrescompleto'); // Field nombrescompleto
			$this->SetSessionFilterValues($this->nombrescentromedico->SearchValue, $this->nombrescentromedico->SearchOperator, $this->nombrescentromedico->SearchCondition, $this->nombrescentromedico->SearchValue2, $this->nombrescentromedico->SearchOperator2, 'nombrescentromedico'); // Field nombrescentromedico
			$this->SetSessionFilterValues($this->direccion->SearchValue, $this->direccion->SearchOperator, $this->direccion->SearchCondition, $this->direccion->SearchValue2, $this->direccion->SearchOperator2, 'direccion'); // Field direccion
			$this->SetSessionFilterValues($this->telefono->SearchValue, $this->telefono->SearchOperator, $this->telefono->SearchCondition, $this->telefono->SearchValue2, $this->telefono->SearchOperator2, 'telefono'); // Field telefono
			$this->SetSessionFilterValues($this->nombre->SearchValue, $this->nombre->SearchOperator, $this->nombre->SearchCondition, $this->nombre->SearchValue2, $this->nombre->SearchOperator2, 'nombre'); // Field nombre

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field nombrescompleto
			if ($this->GetFilterValues($this->nombrescompleto)) {
				$bSetupFilter = TRUE;
			}

			// Field nombrescentromedico
			if ($this->GetFilterValues($this->nombrescentromedico)) {
				$bSetupFilter = TRUE;
			}

			// Field direccion
			if ($this->GetFilterValues($this->direccion)) {
				$bSetupFilter = TRUE;
			}

			// Field telefono
			if ($this->GetFilterValues($this->telefono)) {
				$bSetupFilter = TRUE;
			}

			// Field nombre
			if ($this->GetFilterValues($this->nombre)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionFilterValues($this->nombrescompleto); // Field nombrescompleto
			$this->GetSessionFilterValues($this->nombrescentromedico); // Field nombrescentromedico
			$this->GetSessionFilterValues($this->direccion); // Field direccion
			$this->GetSessionFilterValues($this->telefono); // Field telefono
			$this->GetSessionFilterValues($this->nombre); // Field nombre
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildExtendedFilter($this->nombrescompleto, $sFilter, FALSE, TRUE); // Field nombrescompleto
		$this->BuildExtendedFilter($this->nombrescentromedico, $sFilter, FALSE, TRUE); // Field nombrescentromedico
		$this->BuildExtendedFilter($this->direccion, $sFilter, FALSE, TRUE); // Field direccion
		$this->BuildExtendedFilter($this->telefono, $sFilter, FALSE, TRUE); // Field telefono
		$this->BuildExtendedFilter($this->nombre, $sFilter, FALSE, TRUE); // Field nombre

		// Save parms to session
		$this->SetSessionFilterValues($this->nombrescompleto->SearchValue, $this->nombrescompleto->SearchOperator, $this->nombrescompleto->SearchCondition, $this->nombrescompleto->SearchValue2, $this->nombrescompleto->SearchOperator2, 'nombrescompleto'); // Field nombrescompleto
		$this->SetSessionFilterValues($this->nombrescentromedico->SearchValue, $this->nombrescentromedico->SearchOperator, $this->nombrescentromedico->SearchCondition, $this->nombrescentromedico->SearchValue2, $this->nombrescentromedico->SearchOperator2, 'nombrescentromedico'); // Field nombrescentromedico
		$this->SetSessionFilterValues($this->direccion->SearchValue, $this->direccion->SearchOperator, $this->direccion->SearchCondition, $this->direccion->SearchValue2, $this->direccion->SearchOperator2, 'direccion'); // Field direccion
		$this->SetSessionFilterValues($this->telefono->SearchValue, $this->telefono->SearchOperator, $this->telefono->SearchCondition, $this->telefono->SearchValue2, $this->telefono->SearchOperator2, 'telefono'); // Field telefono
		$this->SetSessionFilterValues($this->nombre->SearchValue, $this->nombre->SearchOperator, $this->nombre->SearchCondition, $this->nombre->SearchValue2, $this->nombre->SearchOperator2, 'nombre'); // Field nombre

		// Setup filter
		if ($bSetupFilter) {

			// Field nombrescompleto
			$sWrk = "";
			$this->BuildExtendedFilter($this->nombrescompleto, $sWrk);
			ewr_LoadSelectionFromFilter($this->nombrescompleto, $sWrk, $this->nombrescompleto->SelectionList);
			$_SESSION['sel_viewsaludreferencia_nombrescompleto'] = ($this->nombrescompleto->SelectionList == "") ? EWR_INIT_VALUE : $this->nombrescompleto->SelectionList;

			// Field nombrescentromedico
			$sWrk = "";
			$this->BuildExtendedFilter($this->nombrescentromedico, $sWrk);
			ewr_LoadSelectionFromFilter($this->nombrescentromedico, $sWrk, $this->nombrescentromedico->SelectionList);
			$_SESSION['sel_viewsaludreferencia_nombrescentromedico'] = ($this->nombrescentromedico->SelectionList == "") ? EWR_INIT_VALUE : $this->nombrescentromedico->SelectionList;

			// Field direccion
			$sWrk = "";
			$this->BuildExtendedFilter($this->direccion, $sWrk);
			ewr_LoadSelectionFromFilter($this->direccion, $sWrk, $this->direccion->SelectionList);
			$_SESSION['sel_viewsaludreferencia_direccion'] = ($this->direccion->SelectionList == "") ? EWR_INIT_VALUE : $this->direccion->SelectionList;

			// Field telefono
			$sWrk = "";
			$this->BuildExtendedFilter($this->telefono, $sWrk);
			ewr_LoadSelectionFromFilter($this->telefono, $sWrk, $this->telefono->SelectionList);
			$_SESSION['sel_viewsaludreferencia_telefono'] = ($this->telefono->SelectionList == "") ? EWR_INIT_VALUE : $this->telefono->SelectionList;

			// Field nombre
			$sWrk = "";
			$this->BuildExtendedFilter($this->nombre, $sWrk);
			ewr_LoadSelectionFromFilter($this->nombre, $sWrk, $this->nombre->SelectionList);
			$_SESSION['sel_viewsaludreferencia_nombre'] = ($this->nombre->SelectionList == "") ? EWR_INIT_VALUE : $this->nombre->SelectionList;
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewsaludreferencia_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsaludreferencia_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewsaludreferencia_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsaludreferencia_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewsaludreferencia_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewsaludreferencia_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewsaludreferencia_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewsaludreferencia_' . $parm] = $sv;
		$_SESSION['so_viewsaludreferencia_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewsaludreferencia_' . $parm] = $sv1;
		$_SESSION['so_viewsaludreferencia_' . $parm] = $so1;
		$_SESSION['sc_viewsaludreferencia_' . $parm] = $sc;
		$_SESSION['sv2_viewsaludreferencia_' . $parm] = $sv2;
		$_SESSION['so2_viewsaludreferencia_' . $parm] = $so2;
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
		if (!ewr_CheckInteger($this->direccion->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->direccion->FldErrMsg();
		}
		if (!ewr_CheckInteger($this->telefono->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->telefono->FldErrMsg();
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
		$_SESSION["sel_viewsaludreferencia_$parm"] = "";
		$_SESSION["rf_viewsaludreferencia_$parm"] = "";
		$_SESSION["rt_viewsaludreferencia_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewsaludreferencia_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewsaludreferencia_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewsaludreferencia_$parm"];
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

		// Field nombrescompleto
		$this->SetDefaultExtFilter($this->nombrescompleto, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombrescompleto);
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombrescompleto, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->nombrescompleto, $sWrk, $this->nombrescompleto->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombrescompleto->SelectionList = $this->nombrescompleto->DefaultSelectionList;

		// Field nombrescentromedico
		$this->SetDefaultExtFilter($this->nombrescentromedico, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombrescentromedico);
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombrescentromedico, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->nombrescentromedico, $sWrk, $this->nombrescentromedico->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombrescentromedico->SelectionList = $this->nombrescentromedico->DefaultSelectionList;

		// Field direccion
		$this->SetDefaultExtFilter($this->direccion, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->direccion);
		$sWrk = "";
		$this->BuildExtendedFilter($this->direccion, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->direccion, $sWrk, $this->direccion->DefaultSelectionList);
		if (!$this->SearchCommand) $this->direccion->SelectionList = $this->direccion->DefaultSelectionList;

		// Field telefono
		$this->SetDefaultExtFilter($this->telefono, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->telefono);
		$sWrk = "";
		$this->BuildExtendedFilter($this->telefono, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->telefono, $sWrk, $this->telefono->DefaultSelectionList);
		if (!$this->SearchCommand) $this->telefono->SelectionList = $this->telefono->DefaultSelectionList;

		// Field nombre
		$this->SetDefaultExtFilter($this->nombre, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombre);
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombre, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->nombre, $sWrk, $this->nombre->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombre->SelectionList = $this->nombre->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field nombrescompleto
		// $this->nombrescompleto->DefaultSelectionList = array("val1", "val2");
		// Field nombrescentromedico
		// $this->nombrescentromedico->DefaultSelectionList = array("val1", "val2");
		// Field direccion
		// $this->direccion->DefaultSelectionList = array("val1", "val2");
		// Field telefono
		// $this->telefono->DefaultSelectionList = array("val1", "val2");
		// Field nombre
		// $this->nombre->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check nombrescompleto text filter
		if ($this->TextFilterApplied($this->nombrescompleto))
			return TRUE;

		// Check nombrescompleto popup filter
		if (!ewr_MatchedArray($this->nombrescompleto->DefaultSelectionList, $this->nombrescompleto->SelectionList))
			return TRUE;

		// Check nombrescentromedico text filter
		if ($this->TextFilterApplied($this->nombrescentromedico))
			return TRUE;

		// Check nombrescentromedico popup filter
		if (!ewr_MatchedArray($this->nombrescentromedico->DefaultSelectionList, $this->nombrescentromedico->SelectionList))
			return TRUE;

		// Check direccion text filter
		if ($this->TextFilterApplied($this->direccion))
			return TRUE;

		// Check direccion popup filter
		if (!ewr_MatchedArray($this->direccion->DefaultSelectionList, $this->direccion->SelectionList))
			return TRUE;

		// Check telefono text filter
		if ($this->TextFilterApplied($this->telefono))
			return TRUE;

		// Check telefono popup filter
		if (!ewr_MatchedArray($this->telefono->DefaultSelectionList, $this->telefono->SelectionList))
			return TRUE;

		// Check nombre text filter
		if ($this->TextFilterApplied($this->nombre))
			return TRUE;

		// Check nombre popup filter
		if (!ewr_MatchedArray($this->nombre->DefaultSelectionList, $this->nombre->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field nombrescompleto
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombrescompleto, $sExtWrk);
		if (is_array($this->nombrescompleto->SelectionList))
			$sWrk = ewr_JoinArray($this->nombrescompleto->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombrescompleto->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombrescentromedico
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombrescentromedico, $sExtWrk);
		if (is_array($this->nombrescentromedico->SelectionList))
			$sWrk = ewr_JoinArray($this->nombrescentromedico->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombrescentromedico->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field direccion
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->direccion, $sExtWrk);
		if (is_array($this->direccion->SelectionList))
			$sWrk = ewr_JoinArray($this->direccion->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->direccion->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field telefono
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->telefono, $sExtWrk);
		if (is_array($this->telefono->SelectionList))
			$sWrk = ewr_JoinArray($this->telefono->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->telefono->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombre
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombre, $sExtWrk);
		if (is_array($this->nombre->SelectionList))
			$sWrk = ewr_JoinArray($this->nombre->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombre->FldCaption() . "</span>" . $sFilter . "</div>";
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

		// Field nombrescompleto
		$sWrk = "";
		if ($this->nombrescompleto->SearchValue <> "" || $this->nombrescompleto->SearchValue2 <> "") {
			$sWrk = "\"sv_nombrescompleto\":\"" . ewr_JsEncode2($this->nombrescompleto->SearchValue) . "\"," .
				"\"so_nombrescompleto\":\"" . ewr_JsEncode2($this->nombrescompleto->SearchOperator) . "\"," .
				"\"sc_nombrescompleto\":\"" . ewr_JsEncode2($this->nombrescompleto->SearchCondition) . "\"," .
				"\"sv2_nombrescompleto\":\"" . ewr_JsEncode2($this->nombrescompleto->SearchValue2) . "\"," .
				"\"so2_nombrescompleto\":\"" . ewr_JsEncode2($this->nombrescompleto->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->nombrescompleto->SelectionList <> EWR_INIT_VALUE) ? $this->nombrescompleto->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nombrescompleto\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombrescentromedico
		$sWrk = "";
		if ($this->nombrescentromedico->SearchValue <> "" || $this->nombrescentromedico->SearchValue2 <> "") {
			$sWrk = "\"sv_nombrescentromedico\":\"" . ewr_JsEncode2($this->nombrescentromedico->SearchValue) . "\"," .
				"\"so_nombrescentromedico\":\"" . ewr_JsEncode2($this->nombrescentromedico->SearchOperator) . "\"," .
				"\"sc_nombrescentromedico\":\"" . ewr_JsEncode2($this->nombrescentromedico->SearchCondition) . "\"," .
				"\"sv2_nombrescentromedico\":\"" . ewr_JsEncode2($this->nombrescentromedico->SearchValue2) . "\"," .
				"\"so2_nombrescentromedico\":\"" . ewr_JsEncode2($this->nombrescentromedico->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->nombrescentromedico->SelectionList <> EWR_INIT_VALUE) ? $this->nombrescentromedico->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nombrescentromedico\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field direccion
		$sWrk = "";
		if ($this->direccion->SearchValue <> "" || $this->direccion->SearchValue2 <> "") {
			$sWrk = "\"sv_direccion\":\"" . ewr_JsEncode2($this->direccion->SearchValue) . "\"," .
				"\"so_direccion\":\"" . ewr_JsEncode2($this->direccion->SearchOperator) . "\"," .
				"\"sc_direccion\":\"" . ewr_JsEncode2($this->direccion->SearchCondition) . "\"," .
				"\"sv2_direccion\":\"" . ewr_JsEncode2($this->direccion->SearchValue2) . "\"," .
				"\"so2_direccion\":\"" . ewr_JsEncode2($this->direccion->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->direccion->SelectionList <> EWR_INIT_VALUE) ? $this->direccion->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_direccion\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field telefono
		$sWrk = "";
		if ($this->telefono->SearchValue <> "" || $this->telefono->SearchValue2 <> "") {
			$sWrk = "\"sv_telefono\":\"" . ewr_JsEncode2($this->telefono->SearchValue) . "\"," .
				"\"so_telefono\":\"" . ewr_JsEncode2($this->telefono->SearchOperator) . "\"," .
				"\"sc_telefono\":\"" . ewr_JsEncode2($this->telefono->SearchCondition) . "\"," .
				"\"sv2_telefono\":\"" . ewr_JsEncode2($this->telefono->SearchValue2) . "\"," .
				"\"so2_telefono\":\"" . ewr_JsEncode2($this->telefono->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->telefono->SelectionList <> EWR_INIT_VALUE) ? $this->telefono->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_telefono\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombre
		$sWrk = "";
		if ($this->nombre->SearchValue <> "" || $this->nombre->SearchValue2 <> "") {
			$sWrk = "\"sv_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchValue) . "\"," .
				"\"so_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchOperator) . "\"," .
				"\"sc_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchCondition) . "\"," .
				"\"sv2_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchValue2) . "\"," .
				"\"so2_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->nombre->SelectionList <> EWR_INIT_VALUE) ? $this->nombre->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nombre\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field nombrescompleto
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombrescompleto", $filter) || array_key_exists("so_nombrescompleto", $filter) ||
			array_key_exists("sc_nombrescompleto", $filter) ||
			array_key_exists("sv2_nombrescompleto", $filter) || array_key_exists("so2_nombrescompleto", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombrescompleto"], @$filter["so_nombrescompleto"], @$filter["sc_nombrescompleto"], @$filter["sv2_nombrescompleto"], @$filter["so2_nombrescompleto"], "nombrescompleto");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombrescompleto", $filter)) {
			$sWrk = $filter["sel_nombrescompleto"];
			$sWrk = explode("||", $sWrk);
			$this->nombrescompleto->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludreferencia_nombrescompleto"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombrescompleto"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombrescompleto");
			$this->nombrescompleto->SelectionList = "";
			$_SESSION["sel_viewsaludreferencia_nombrescompleto"] = "";
		}

		// Field nombrescentromedico
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombrescentromedico", $filter) || array_key_exists("so_nombrescentromedico", $filter) ||
			array_key_exists("sc_nombrescentromedico", $filter) ||
			array_key_exists("sv2_nombrescentromedico", $filter) || array_key_exists("so2_nombrescentromedico", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombrescentromedico"], @$filter["so_nombrescentromedico"], @$filter["sc_nombrescentromedico"], @$filter["sv2_nombrescentromedico"], @$filter["so2_nombrescentromedico"], "nombrescentromedico");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombrescentromedico", $filter)) {
			$sWrk = $filter["sel_nombrescentromedico"];
			$sWrk = explode("||", $sWrk);
			$this->nombrescentromedico->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludreferencia_nombrescentromedico"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombrescentromedico"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombrescentromedico");
			$this->nombrescentromedico->SelectionList = "";
			$_SESSION["sel_viewsaludreferencia_nombrescentromedico"] = "";
		}

		// Field direccion
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_direccion", $filter) || array_key_exists("so_direccion", $filter) ||
			array_key_exists("sc_direccion", $filter) ||
			array_key_exists("sv2_direccion", $filter) || array_key_exists("so2_direccion", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_direccion"], @$filter["so_direccion"], @$filter["sc_direccion"], @$filter["sv2_direccion"], @$filter["so2_direccion"], "direccion");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_direccion", $filter)) {
			$sWrk = $filter["sel_direccion"];
			$sWrk = explode("||", $sWrk);
			$this->direccion->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludreferencia_direccion"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "direccion"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "direccion");
			$this->direccion->SelectionList = "";
			$_SESSION["sel_viewsaludreferencia_direccion"] = "";
		}

		// Field telefono
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_telefono", $filter) || array_key_exists("so_telefono", $filter) ||
			array_key_exists("sc_telefono", $filter) ||
			array_key_exists("sv2_telefono", $filter) || array_key_exists("so2_telefono", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_telefono"], @$filter["so_telefono"], @$filter["sc_telefono"], @$filter["sv2_telefono"], @$filter["so2_telefono"], "telefono");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_telefono", $filter)) {
			$sWrk = $filter["sel_telefono"];
			$sWrk = explode("||", $sWrk);
			$this->telefono->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludreferencia_telefono"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "telefono"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "telefono");
			$this->telefono->SelectionList = "";
			$_SESSION["sel_viewsaludreferencia_telefono"] = "";
		}

		// Field nombre
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombre", $filter) || array_key_exists("so_nombre", $filter) ||
			array_key_exists("sc_nombre", $filter) ||
			array_key_exists("sv2_nombre", $filter) || array_key_exists("so2_nombre", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombre"], @$filter["so_nombre"], @$filter["sc_nombre"], @$filter["sv2_nombre"], @$filter["so2_nombre"], "nombre");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombre", $filter)) {
			$sWrk = $filter["sel_nombre"];
			$sWrk = explode("||", $sWrk);
			$this->nombre->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludreferencia_nombre"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombre"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombre");
			$this->nombre->SelectionList = "";
			$_SESSION["sel_viewsaludreferencia_nombre"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->ExtendedFilterExist($this->nombrescompleto)) {
			if (is_array($this->nombrescompleto->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombrescompleto, "`nombrescompleto`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombrescompleto, $sFilter, "popup");
				$this->nombrescompleto->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->nombrescentromedico)) {
			if (is_array($this->nombrescentromedico->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombrescentromedico, "`nombrescentromedico`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombrescentromedico, $sFilter, "popup");
				$this->nombrescentromedico->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->direccion)) {
			if (is_array($this->direccion->SelectionList)) {
				$sFilter = ewr_FilterSql($this->direccion, "`direccion`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->direccion, $sFilter, "popup");
				$this->direccion->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->telefono)) {
			if (is_array($this->telefono->SelectionList)) {
				$sFilter = ewr_FilterSql($this->telefono, "`telefono`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->telefono, $sFilter, "popup");
				$this->telefono->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->nombre)) {
			if (is_array($this->nombre->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombre, "`nombre`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombre, $sFilter, "popup");
				$this->nombre->CurrentFilter = $sFilter;
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

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->nombrescompleto->setSort("");
			$this->nombrescentromedico->setSort("");
			$this->direccion->setSort("");
			$this->telefono->setSort("");
			$this->nombre->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->nombrescompleto, $bCtrl); // nombrescompleto
			$this->UpdateSort($this->nombrescentromedico, $bCtrl); // nombrescentromedico
			$this->UpdateSort($this->direccion, $bCtrl); // direccion
			$this->UpdateSort($this->telefono, $bCtrl); // telefono
			$this->UpdateSort($this->nombre, $bCtrl); // nombre
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export to HTML
	function ExportHtml($html, $options = array()) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');

		$folder = @$this->GenOptions["folder"];
		$fileName = @$this->GenOptions["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";

		// Save generate file for print
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			$baseTag = "<base href=\"" . ewr_BaseUrl() . "\">";
			$html = preg_replace('/<head>/', '<head>' . $baseTag, $html);
			ewr_SaveFile($folder, $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file")
			echo $html;
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
if (!isset($viewsaludreferencia_rpt)) $viewsaludreferencia_rpt = new crviewsaludreferencia_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewsaludreferencia_rpt;

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
<?php if ($Page->Export == "" || $Page->Export == "print" || $Page->Export == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">

// Create page object
var viewsaludreferencia_rpt = new ewr_Page("viewsaludreferencia_rpt");

// Page properties
viewsaludreferencia_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewsaludreferencia_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewsaludreferenciarpt = new ewr_Form("fviewsaludreferenciarpt");

// Validate method
fviewsaludreferenciarpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_direccion;
	if (elm && !ewr_CheckInteger(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->direccion->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv_telefono;
	if (elm && !ewr_CheckInteger(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->telefono->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewsaludreferenciarpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewsaludreferenciarpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewsaludreferenciarpt.ValidateRequired = false; // No JavaScript validation
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
<form name="fviewsaludreferenciarpt" id="fviewsaludreferenciarpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewsaludreferenciarpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_nombrescompleto" class="ewCell form-group">
	<label for="sv_nombrescompleto" class="ewSearchCaption ewLabel"><?php echo $Page->nombrescompleto->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombrescompleto" id="so_nombrescompleto" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombrescompleto->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludreferencia" data-field="x_nombrescompleto" id="sv_nombrescompleto" name="sv_nombrescompleto" size="30" maxlength="100" placeholder="<?php echo $Page->nombrescompleto->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombrescompleto->SearchValue) ?>"<?php echo $Page->nombrescompleto->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_nombrescentromedico" class="ewCell form-group">
	<label for="sv_nombrescentromedico" class="ewSearchCaption ewLabel"><?php echo $Page->nombrescentromedico->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombrescentromedico" id="so_nombrescentromedico" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombrescentromedico->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludreferencia" data-field="x_nombrescentromedico" id="sv_nombrescentromedico" name="sv_nombrescentromedico" size="30" maxlength="100" placeholder="<?php echo $Page->nombrescentromedico->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombrescentromedico->SearchValue) ?>"<?php echo $Page->nombrescentromedico->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_3" class="ewRow">
<div id="c_direccion" class="ewCell form-group">
	<label for="sv_direccion" class="ewSearchCaption ewLabel"><?php echo $Page->direccion->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_direccion" id="so_direccion" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->direccion->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludreferencia" data-field="x_direccion" id="sv_direccion" name="sv_direccion" size="30" placeholder="<?php echo $Page->direccion->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->direccion->SearchValue) ?>"<?php echo $Page->direccion->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_4" class="ewRow">
<div id="c_telefono" class="ewCell form-group">
	<label for="sv_telefono" class="ewSearchCaption ewLabel"><?php echo $Page->telefono->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_telefono" id="so_telefono" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->telefono->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludreferencia" data-field="x_telefono" id="sv_telefono" name="sv_telefono" size="30" placeholder="<?php echo $Page->telefono->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->telefono->SearchValue) ?>"<?php echo $Page->telefono->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_5" class="ewRow">
<div id="c_nombre" class="ewCell form-group">
	<label for="sv_nombre" class="ewSearchCaption ewLabel"><?php echo $Page->nombre->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombre" id="so_nombre" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombre->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludreferencia" data-field="x_nombre" id="sv_nombre" name="sv_nombre" size="30" maxlength="100" placeholder="<?php echo $Page->nombre->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombre->SearchValue) ?>"<?php echo $Page->nombre->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewsaludreferenciarpt.Init();
fviewsaludreferenciarpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<div id="gmp_viewsaludreferencia" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->nombrescompleto->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombrescompleto"><div class="viewsaludreferencia_nombrescompleto"><span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombrescompleto">
<?php if ($Page->SortUrl($Page->nombrescompleto) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludreferencia_nombrescompleto">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_nombrescompleto', range: false, from: '<?php echo $Page->nombrescompleto->RangeFrom; ?>', to: '<?php echo $Page->nombrescompleto->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_nombrescompleto<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludreferencia_nombrescompleto" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombrescompleto) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombrescompleto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombrescompleto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_nombrescompleto', range: false, from: '<?php echo $Page->nombrescompleto->RangeFrom; ?>', to: '<?php echo $Page->nombrescompleto->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_nombrescompleto<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombrescentromedico->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombrescentromedico"><div class="viewsaludreferencia_nombrescentromedico"><span class="ewTableHeaderCaption"><?php echo $Page->nombrescentromedico->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombrescentromedico">
<?php if ($Page->SortUrl($Page->nombrescentromedico) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludreferencia_nombrescentromedico">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrescentromedico->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_nombrescentromedico', range: false, from: '<?php echo $Page->nombrescentromedico->RangeFrom; ?>', to: '<?php echo $Page->nombrescentromedico->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_nombrescentromedico<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludreferencia_nombrescentromedico" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombrescentromedico) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrescentromedico->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombrescentromedico->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombrescentromedico->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_nombrescentromedico', range: false, from: '<?php echo $Page->nombrescentromedico->RangeFrom; ?>', to: '<?php echo $Page->nombrescentromedico->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_nombrescentromedico<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->direccion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="direccion"><div class="viewsaludreferencia_direccion"><span class="ewTableHeaderCaption"><?php echo $Page->direccion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="direccion">
<?php if ($Page->SortUrl($Page->direccion) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludreferencia_direccion">
			<span class="ewTableHeaderCaption"><?php echo $Page->direccion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_direccion', range: false, from: '<?php echo $Page->direccion->RangeFrom; ?>', to: '<?php echo $Page->direccion->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_direccion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludreferencia_direccion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->direccion) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->direccion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_direccion', range: false, from: '<?php echo $Page->direccion->RangeFrom; ?>', to: '<?php echo $Page->direccion->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_direccion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->telefono->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="telefono"><div class="viewsaludreferencia_telefono"><span class="ewTableHeaderCaption"><?php echo $Page->telefono->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="telefono">
<?php if ($Page->SortUrl($Page->telefono) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludreferencia_telefono">
			<span class="ewTableHeaderCaption"><?php echo $Page->telefono->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_telefono', range: false, from: '<?php echo $Page->telefono->RangeFrom; ?>', to: '<?php echo $Page->telefono->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_telefono<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludreferencia_telefono" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->telefono) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->telefono->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_telefono', range: false, from: '<?php echo $Page->telefono->RangeFrom; ?>', to: '<?php echo $Page->telefono->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_telefono<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombre->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombre"><div class="viewsaludreferencia_nombre"><span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombre">
<?php if ($Page->SortUrl($Page->nombre) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludreferencia_nombre">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_nombre', range: false, from: '<?php echo $Page->nombre->RangeFrom; ?>', to: '<?php echo $Page->nombre->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_nombre<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludreferencia_nombre" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombre) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludreferencia_nombre', range: false, from: '<?php echo $Page->nombre->RangeFrom; ?>', to: '<?php echo $Page->nombre->RangeTo; ?>', url: 'viewsaludreferenciarpt.php' });" id="x_nombre<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
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
<?php if ($Page->nombrescompleto->Visible) { ?>
		<td data-field="nombrescompleto"<?php echo $Page->nombrescompleto->CellAttributes() ?>>
<span<?php echo $Page->nombrescompleto->ViewAttributes() ?>><?php echo $Page->nombrescompleto->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombrescentromedico->Visible) { ?>
		<td data-field="nombrescentromedico"<?php echo $Page->nombrescentromedico->CellAttributes() ?>>
<span<?php echo $Page->nombrescentromedico->ViewAttributes() ?>><?php echo $Page->nombrescentromedico->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->direccion->Visible) { ?>
		<td data-field="direccion"<?php echo $Page->direccion->CellAttributes() ?>>
<span<?php echo $Page->direccion->ViewAttributes() ?>><?php echo $Page->direccion->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->telefono->Visible) { ?>
		<td data-field="telefono"<?php echo $Page->telefono->CellAttributes() ?>>
<span<?php echo $Page->telefono->ViewAttributes() ?>><?php echo $Page->telefono->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombre->Visible) { ?>
		<td data-field="nombre"<?php echo $Page->nombre->CellAttributes() ?>>
<span<?php echo $Page->nombre->ViewAttributes() ?>><?php echo $Page->nombre->ListViewValue() ?></span></td>
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
<div id="gmp_viewsaludreferencia" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
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
<?php include "viewsaludreferenciarptpager.php" ?>
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
