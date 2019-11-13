<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewsaludmarcologicorptinfo.php" ?>
<?php

//
// Page class
//

$viewsaludmarcologico_rpt = NULL; // Initialize page object first

class crviewsaludmarcologico_rpt extends crviewsaludmarcologico {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewsaludmarcologico_rpt';

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

		// Table object (viewsaludmarcologico)
		if (!isset($GLOBALS["viewsaludmarcologico"])) {
			$GLOBALS["viewsaludmarcologico"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewsaludmarcologico"];
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
			define("EWR_TABLE_NAME", 'viewsaludmarcologico', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewsaludmarcologicorpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewsaludmarcologico');
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
		$item->Visible = FALSE;
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
//		$item->Visible = FALSE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewsaludmarcologico\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewsaludmarcologico',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewsaludmarcologicorpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewsaludmarcologicorpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewsaludmarcologicorpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = FALSE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = FALSE && $this->FilterApplied;

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
		$this->nombreinstitucion->SetVisibility();
		$this->fecha->SetVisibility();
		$this->cuadro1->SetVisibility();
		$this->cuadro2->SetVisibility();
		$this->cuadro3->SetVisibility();
		$this->cuadro4->SetVisibility();
		$this->cuadro5->SetVisibility();
		$this->cuadro6->SetVisibility();
		$this->cuadro7->SetVisibility();
		$this->cuadro8->SetVisibility();
		$this->cuadro9->SetVisibility();
		$this->cuadro10->SetVisibility();
		$this->cuadro11->SetVisibility();
		$this->cuadro12->SetVisibility();
		$this->cuadro13->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 16;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

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

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// No filter
		$this->FilterApplied = FALSE;
		$this->FilterOptions->GetItem("savecurrentfilter")->Visible = FALSE;
		$this->FilterOptions->GetItem("deletefilter")->Visible = FALSE;

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
		$this->ShowHeader = ($this->TotalGrps > 0);

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
				$this->FirstRowData['nombreinstitucion'] = ewr_Conv($rs->fields('nombreinstitucion'), 200);
				$this->FirstRowData['fecha'] = ewr_Conv($rs->fields('fecha'), 133);
				$this->FirstRowData['cuadro1'] = ewr_Conv($rs->fields('cuadro1'), 20);
				$this->FirstRowData['cuadro2'] = ewr_Conv($rs->fields('cuadro2'), 20);
				$this->FirstRowData['cuadro3'] = ewr_Conv($rs->fields('cuadro3'), 20);
				$this->FirstRowData['cuadro4'] = ewr_Conv($rs->fields('cuadro4'), 20);
				$this->FirstRowData['cuadro5'] = ewr_Conv($rs->fields('cuadro5'), 20);
				$this->FirstRowData['cuadro6'] = ewr_Conv($rs->fields('cuadro6'), 20);
				$this->FirstRowData['cuadro7'] = ewr_Conv($rs->fields('cuadro7'), 20);
				$this->FirstRowData['cuadro8'] = ewr_Conv($rs->fields('cuadro8'), 20);
				$this->FirstRowData['cuadro9'] = ewr_Conv($rs->fields('cuadro9'), 20);
				$this->FirstRowData['cuadro10'] = ewr_Conv($rs->fields('cuadro10'), 20);
				$this->FirstRowData['cuadro11'] = ewr_Conv($rs->fields('cuadro11'), 20);
				$this->FirstRowData['cuadro12'] = ewr_Conv($rs->fields('cuadro12'), 20);
				$this->FirstRowData['cuadro13'] = ewr_Conv($rs->fields('cuadro13'), 20);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->nombreinstitucion->setDbValue($rs->fields('nombreinstitucion'));
			$this->fecha->setDbValue($rs->fields('fecha'));
			$this->cuadro1->setDbValue($rs->fields('cuadro1'));
			$this->cuadro2->setDbValue($rs->fields('cuadro2'));
			$this->cuadro3->setDbValue($rs->fields('cuadro3'));
			$this->cuadro4->setDbValue($rs->fields('cuadro4'));
			$this->cuadro5->setDbValue($rs->fields('cuadro5'));
			$this->cuadro6->setDbValue($rs->fields('cuadro6'));
			$this->cuadro7->setDbValue($rs->fields('cuadro7'));
			$this->cuadro8->setDbValue($rs->fields('cuadro8'));
			$this->cuadro9->setDbValue($rs->fields('cuadro9'));
			$this->cuadro10->setDbValue($rs->fields('cuadro10'));
			$this->cuadro11->setDbValue($rs->fields('cuadro11'));
			$this->cuadro12->setDbValue($rs->fields('cuadro12'));
			$this->cuadro13->setDbValue($rs->fields('cuadro13'));
			$this->Val[1] = $this->nombreinstitucion->CurrentValue;
			$this->Val[2] = $this->fecha->CurrentValue;
			$this->Val[3] = $this->cuadro1->CurrentValue;
			$this->Val[4] = $this->cuadro2->CurrentValue;
			$this->Val[5] = $this->cuadro3->CurrentValue;
			$this->Val[6] = $this->cuadro4->CurrentValue;
			$this->Val[7] = $this->cuadro5->CurrentValue;
			$this->Val[8] = $this->cuadro6->CurrentValue;
			$this->Val[9] = $this->cuadro7->CurrentValue;
			$this->Val[10] = $this->cuadro8->CurrentValue;
			$this->Val[11] = $this->cuadro9->CurrentValue;
			$this->Val[12] = $this->cuadro10->CurrentValue;
			$this->Val[13] = $this->cuadro11->CurrentValue;
			$this->Val[14] = $this->cuadro12->CurrentValue;
			$this->Val[15] = $this->cuadro13->CurrentValue;
		} else {
			$this->nombreinstitucion->setDbValue("");
			$this->fecha->setDbValue("");
			$this->cuadro1->setDbValue("");
			$this->cuadro2->setDbValue("");
			$this->cuadro3->setDbValue("");
			$this->cuadro4->setDbValue("");
			$this->cuadro5->setDbValue("");
			$this->cuadro6->setDbValue("");
			$this->cuadro7->setDbValue("");
			$this->cuadro8->setDbValue("");
			$this->cuadro9->setDbValue("");
			$this->cuadro10->setDbValue("");
			$this->cuadro11->setDbValue("");
			$this->cuadro12->setDbValue("");
			$this->cuadro13->setDbValue("");
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
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
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

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// cuadro1
			$this->cuadro1->HrefValue = "";

			// cuadro2
			$this->cuadro2->HrefValue = "";

			// cuadro3
			$this->cuadro3->HrefValue = "";

			// cuadro4
			$this->cuadro4->HrefValue = "";

			// cuadro5
			$this->cuadro5->HrefValue = "";

			// cuadro6
			$this->cuadro6->HrefValue = "";

			// cuadro7
			$this->cuadro7->HrefValue = "";

			// cuadro8
			$this->cuadro8->HrefValue = "";

			// cuadro9
			$this->cuadro9->HrefValue = "";

			// cuadro10
			$this->cuadro10->HrefValue = "";

			// cuadro11
			$this->cuadro11->HrefValue = "";

			// cuadro12
			$this->cuadro12->HrefValue = "";

			// cuadro13
			$this->cuadro13->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// nombreinstitucion
			$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
			$this->nombreinstitucion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ewr_FormatDateTime($this->fecha->ViewValue, 0);
			$this->fecha->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro1
			$this->cuadro1->ViewValue = $this->cuadro1->CurrentValue;
			$this->cuadro1->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro2
			$this->cuadro2->ViewValue = $this->cuadro2->CurrentValue;
			$this->cuadro2->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro3
			$this->cuadro3->ViewValue = $this->cuadro3->CurrentValue;
			$this->cuadro3->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro4
			$this->cuadro4->ViewValue = $this->cuadro4->CurrentValue;
			$this->cuadro4->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro5
			$this->cuadro5->ViewValue = $this->cuadro5->CurrentValue;
			$this->cuadro5->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro6
			$this->cuadro6->ViewValue = $this->cuadro6->CurrentValue;
			$this->cuadro6->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro7
			$this->cuadro7->ViewValue = $this->cuadro7->CurrentValue;
			$this->cuadro7->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro8
			$this->cuadro8->ViewValue = $this->cuadro8->CurrentValue;
			$this->cuadro8->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro9
			$this->cuadro9->ViewValue = $this->cuadro9->CurrentValue;
			$this->cuadro9->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro10
			$this->cuadro10->ViewValue = $this->cuadro10->CurrentValue;
			$this->cuadro10->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro11
			$this->cuadro11->ViewValue = $this->cuadro11->CurrentValue;
			$this->cuadro11->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro12
			$this->cuadro12->ViewValue = $this->cuadro12->CurrentValue;
			$this->cuadro12->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cuadro13
			$this->cuadro13->ViewValue = $this->cuadro13->CurrentValue;
			$this->cuadro13->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// cuadro1
			$this->cuadro1->HrefValue = "";

			// cuadro2
			$this->cuadro2->HrefValue = "";

			// cuadro3
			$this->cuadro3->HrefValue = "";

			// cuadro4
			$this->cuadro4->HrefValue = "";

			// cuadro5
			$this->cuadro5->HrefValue = "";

			// cuadro6
			$this->cuadro6->HrefValue = "";

			// cuadro7
			$this->cuadro7->HrefValue = "";

			// cuadro8
			$this->cuadro8->HrefValue = "";

			// cuadro9
			$this->cuadro9->HrefValue = "";

			// cuadro10
			$this->cuadro10->HrefValue = "";

			// cuadro11
			$this->cuadro11->HrefValue = "";

			// cuadro12
			$this->cuadro12->HrefValue = "";

			// cuadro13
			$this->cuadro13->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// nombreinstitucion
			$CurrentValue = $this->nombreinstitucion->CurrentValue;
			$ViewValue = &$this->nombreinstitucion->ViewValue;
			$ViewAttrs = &$this->nombreinstitucion->ViewAttrs;
			$CellAttrs = &$this->nombreinstitucion->CellAttrs;
			$HrefValue = &$this->nombreinstitucion->HrefValue;
			$LinkAttrs = &$this->nombreinstitucion->LinkAttrs;
			$this->Cell_Rendered($this->nombreinstitucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fecha
			$CurrentValue = $this->fecha->CurrentValue;
			$ViewValue = &$this->fecha->ViewValue;
			$ViewAttrs = &$this->fecha->ViewAttrs;
			$CellAttrs = &$this->fecha->CellAttrs;
			$HrefValue = &$this->fecha->HrefValue;
			$LinkAttrs = &$this->fecha->LinkAttrs;
			$this->Cell_Rendered($this->fecha, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro1
			$CurrentValue = $this->cuadro1->CurrentValue;
			$ViewValue = &$this->cuadro1->ViewValue;
			$ViewAttrs = &$this->cuadro1->ViewAttrs;
			$CellAttrs = &$this->cuadro1->CellAttrs;
			$HrefValue = &$this->cuadro1->HrefValue;
			$LinkAttrs = &$this->cuadro1->LinkAttrs;
			$this->Cell_Rendered($this->cuadro1, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro2
			$CurrentValue = $this->cuadro2->CurrentValue;
			$ViewValue = &$this->cuadro2->ViewValue;
			$ViewAttrs = &$this->cuadro2->ViewAttrs;
			$CellAttrs = &$this->cuadro2->CellAttrs;
			$HrefValue = &$this->cuadro2->HrefValue;
			$LinkAttrs = &$this->cuadro2->LinkAttrs;
			$this->Cell_Rendered($this->cuadro2, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro3
			$CurrentValue = $this->cuadro3->CurrentValue;
			$ViewValue = &$this->cuadro3->ViewValue;
			$ViewAttrs = &$this->cuadro3->ViewAttrs;
			$CellAttrs = &$this->cuadro3->CellAttrs;
			$HrefValue = &$this->cuadro3->HrefValue;
			$LinkAttrs = &$this->cuadro3->LinkAttrs;
			$this->Cell_Rendered($this->cuadro3, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro4
			$CurrentValue = $this->cuadro4->CurrentValue;
			$ViewValue = &$this->cuadro4->ViewValue;
			$ViewAttrs = &$this->cuadro4->ViewAttrs;
			$CellAttrs = &$this->cuadro4->CellAttrs;
			$HrefValue = &$this->cuadro4->HrefValue;
			$LinkAttrs = &$this->cuadro4->LinkAttrs;
			$this->Cell_Rendered($this->cuadro4, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro5
			$CurrentValue = $this->cuadro5->CurrentValue;
			$ViewValue = &$this->cuadro5->ViewValue;
			$ViewAttrs = &$this->cuadro5->ViewAttrs;
			$CellAttrs = &$this->cuadro5->CellAttrs;
			$HrefValue = &$this->cuadro5->HrefValue;
			$LinkAttrs = &$this->cuadro5->LinkAttrs;
			$this->Cell_Rendered($this->cuadro5, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro6
			$CurrentValue = $this->cuadro6->CurrentValue;
			$ViewValue = &$this->cuadro6->ViewValue;
			$ViewAttrs = &$this->cuadro6->ViewAttrs;
			$CellAttrs = &$this->cuadro6->CellAttrs;
			$HrefValue = &$this->cuadro6->HrefValue;
			$LinkAttrs = &$this->cuadro6->LinkAttrs;
			$this->Cell_Rendered($this->cuadro6, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro7
			$CurrentValue = $this->cuadro7->CurrentValue;
			$ViewValue = &$this->cuadro7->ViewValue;
			$ViewAttrs = &$this->cuadro7->ViewAttrs;
			$CellAttrs = &$this->cuadro7->CellAttrs;
			$HrefValue = &$this->cuadro7->HrefValue;
			$LinkAttrs = &$this->cuadro7->LinkAttrs;
			$this->Cell_Rendered($this->cuadro7, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro8
			$CurrentValue = $this->cuadro8->CurrentValue;
			$ViewValue = &$this->cuadro8->ViewValue;
			$ViewAttrs = &$this->cuadro8->ViewAttrs;
			$CellAttrs = &$this->cuadro8->CellAttrs;
			$HrefValue = &$this->cuadro8->HrefValue;
			$LinkAttrs = &$this->cuadro8->LinkAttrs;
			$this->Cell_Rendered($this->cuadro8, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro9
			$CurrentValue = $this->cuadro9->CurrentValue;
			$ViewValue = &$this->cuadro9->ViewValue;
			$ViewAttrs = &$this->cuadro9->ViewAttrs;
			$CellAttrs = &$this->cuadro9->CellAttrs;
			$HrefValue = &$this->cuadro9->HrefValue;
			$LinkAttrs = &$this->cuadro9->LinkAttrs;
			$this->Cell_Rendered($this->cuadro9, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro10
			$CurrentValue = $this->cuadro10->CurrentValue;
			$ViewValue = &$this->cuadro10->ViewValue;
			$ViewAttrs = &$this->cuadro10->ViewAttrs;
			$CellAttrs = &$this->cuadro10->CellAttrs;
			$HrefValue = &$this->cuadro10->HrefValue;
			$LinkAttrs = &$this->cuadro10->LinkAttrs;
			$this->Cell_Rendered($this->cuadro10, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro11
			$CurrentValue = $this->cuadro11->CurrentValue;
			$ViewValue = &$this->cuadro11->ViewValue;
			$ViewAttrs = &$this->cuadro11->ViewAttrs;
			$CellAttrs = &$this->cuadro11->CellAttrs;
			$HrefValue = &$this->cuadro11->HrefValue;
			$LinkAttrs = &$this->cuadro11->LinkAttrs;
			$this->Cell_Rendered($this->cuadro11, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro12
			$CurrentValue = $this->cuadro12->CurrentValue;
			$ViewValue = &$this->cuadro12->ViewValue;
			$ViewAttrs = &$this->cuadro12->ViewAttrs;
			$CellAttrs = &$this->cuadro12->CellAttrs;
			$HrefValue = &$this->cuadro12->HrefValue;
			$LinkAttrs = &$this->cuadro12->LinkAttrs;
			$this->Cell_Rendered($this->cuadro12, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cuadro13
			$CurrentValue = $this->cuadro13->CurrentValue;
			$ViewValue = &$this->cuadro13->ViewValue;
			$ViewAttrs = &$this->cuadro13->ViewAttrs;
			$CellAttrs = &$this->cuadro13->CellAttrs;
			$HrefValue = &$this->cuadro13->HrefValue;
			$LinkAttrs = &$this->cuadro13->LinkAttrs;
			$this->Cell_Rendered($this->cuadro13, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->nombreinstitucion->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro1->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro2->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro3->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro4->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro5->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro6->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro7->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro8->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro9->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro10->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro11->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro12->Visible) $this->DtlColumnCount += 1;
		if ($this->cuadro13->Visible) $this->DtlColumnCount += 1;
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
		$item->Visible = FALSE;
		if ($item->Visible)
			$ReportTypes["pdf"] = $ReportLanguage->Phrase("ReportFormPdf");
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a class=\"ewrExportLink ewPdf\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
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
			$this->nombreinstitucion->setSort("");
			$this->fecha->setSort("");
			$this->cuadro1->setSort("");
			$this->cuadro2->setSort("");
			$this->cuadro3->setSort("");
			$this->cuadro4->setSort("");
			$this->cuadro5->setSort("");
			$this->cuadro6->setSort("");
			$this->cuadro7->setSort("");
			$this->cuadro8->setSort("");
			$this->cuadro9->setSort("");
			$this->cuadro10->setSort("");
			$this->cuadro11->setSort("");
			$this->cuadro12->setSort("");
			$this->cuadro13->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
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
if (!isset($viewsaludmarcologico_rpt)) $viewsaludmarcologico_rpt = new crviewsaludmarcologico_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewsaludmarcologico_rpt;

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
<script type="text/javascript">

// Create page object
var viewsaludmarcologico_rpt = new ewr_Page("viewsaludmarcologico_rpt");

// Page properties
viewsaludmarcologico_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewsaludmarcologico_rpt.PageID;
</script>
<?php if (!$Page->DrillDown && !$grDashboardReport) { ?>
<?php } ?>
<?php if (!$Page->DrillDown && !$grDashboardReport) { ?>
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
<div id="report_summary">
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
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_viewsaludmarcologico" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->nombreinstitucion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="viewsaludmarcologico_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha"><div class="viewsaludmarcologico_fecha"><span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha">
<?php if ($Page->SortUrl($Page->fecha) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_fecha">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_fecha" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro1->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro1"><div class="viewsaludmarcologico_cuadro1"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro1->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro1">
<?php if ($Page->SortUrl($Page->cuadro1) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro1">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro1->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro1" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro1) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro1->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro2->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro2"><div class="viewsaludmarcologico_cuadro2"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro2->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro2">
<?php if ($Page->SortUrl($Page->cuadro2) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro2">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro2->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro2" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro2) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro2->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro3->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro3"><div class="viewsaludmarcologico_cuadro3"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro3->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro3">
<?php if ($Page->SortUrl($Page->cuadro3) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro3">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro3->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro3" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro3) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro3->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro4->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro4"><div class="viewsaludmarcologico_cuadro4"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro4->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro4">
<?php if ($Page->SortUrl($Page->cuadro4) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro4">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro4->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro4" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro4) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro4->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro5->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro5"><div class="viewsaludmarcologico_cuadro5"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro5->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro5">
<?php if ($Page->SortUrl($Page->cuadro5) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro5">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro5->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro5" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro5) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro5->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro6->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro6"><div class="viewsaludmarcologico_cuadro6"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro6->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro6">
<?php if ($Page->SortUrl($Page->cuadro6) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro6">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro6->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro6" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro6) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro6->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro6->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro6->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro7->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro7"><div class="viewsaludmarcologico_cuadro7"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro7->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro7">
<?php if ($Page->SortUrl($Page->cuadro7) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro7">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro7->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro7" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro7) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro7->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro7->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro7->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro8->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro8"><div class="viewsaludmarcologico_cuadro8"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro8->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro8">
<?php if ($Page->SortUrl($Page->cuadro8) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro8">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro8->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro8" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro8) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro8->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro8->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro8->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro9->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro9"><div class="viewsaludmarcologico_cuadro9"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro9->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro9">
<?php if ($Page->SortUrl($Page->cuadro9) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro9">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro9->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro9" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro9) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro9->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro9->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro9->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro10->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro10"><div class="viewsaludmarcologico_cuadro10"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro10->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro10">
<?php if ($Page->SortUrl($Page->cuadro10) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro10">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro10->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro10" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro10) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro10->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro10->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro10->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro11->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro11"><div class="viewsaludmarcologico_cuadro11"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro11->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro11">
<?php if ($Page->SortUrl($Page->cuadro11) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro11">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro11->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro11" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro11) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro11->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro11->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro11->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro12->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro12"><div class="viewsaludmarcologico_cuadro12"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro12->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro12">
<?php if ($Page->SortUrl($Page->cuadro12) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro12">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro12->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro12" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro12) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro12->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro12->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro12->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro13->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro13"><div class="viewsaludmarcologico_cuadro13"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro13->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro13">
<?php if ($Page->SortUrl($Page->cuadro13) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludmarcologico_cuadro13">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro13->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludmarcologico_cuadro13" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro13) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro13->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro13->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro13->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes() ?>>
<span<?php echo $Page->nombreinstitucion->ViewAttributes() ?>><?php echo $Page->nombreinstitucion->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fecha->Visible) { ?>
		<td data-field="fecha"<?php echo $Page->fecha->CellAttributes() ?>>
<span<?php echo $Page->fecha->ViewAttributes() ?>><?php echo $Page->fecha->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro1->Visible) { ?>
		<td data-field="cuadro1"<?php echo $Page->cuadro1->CellAttributes() ?>>
<span<?php echo $Page->cuadro1->ViewAttributes() ?>><?php echo $Page->cuadro1->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro2->Visible) { ?>
		<td data-field="cuadro2"<?php echo $Page->cuadro2->CellAttributes() ?>>
<span<?php echo $Page->cuadro2->ViewAttributes() ?>><?php echo $Page->cuadro2->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro3->Visible) { ?>
		<td data-field="cuadro3"<?php echo $Page->cuadro3->CellAttributes() ?>>
<span<?php echo $Page->cuadro3->ViewAttributes() ?>><?php echo $Page->cuadro3->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro4->Visible) { ?>
		<td data-field="cuadro4"<?php echo $Page->cuadro4->CellAttributes() ?>>
<span<?php echo $Page->cuadro4->ViewAttributes() ?>><?php echo $Page->cuadro4->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro5->Visible) { ?>
		<td data-field="cuadro5"<?php echo $Page->cuadro5->CellAttributes() ?>>
<span<?php echo $Page->cuadro5->ViewAttributes() ?>><?php echo $Page->cuadro5->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro6->Visible) { ?>
		<td data-field="cuadro6"<?php echo $Page->cuadro6->CellAttributes() ?>>
<span<?php echo $Page->cuadro6->ViewAttributes() ?>><?php echo $Page->cuadro6->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro7->Visible) { ?>
		<td data-field="cuadro7"<?php echo $Page->cuadro7->CellAttributes() ?>>
<span<?php echo $Page->cuadro7->ViewAttributes() ?>><?php echo $Page->cuadro7->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro8->Visible) { ?>
		<td data-field="cuadro8"<?php echo $Page->cuadro8->CellAttributes() ?>>
<span<?php echo $Page->cuadro8->ViewAttributes() ?>><?php echo $Page->cuadro8->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro9->Visible) { ?>
		<td data-field="cuadro9"<?php echo $Page->cuadro9->CellAttributes() ?>>
<span<?php echo $Page->cuadro9->ViewAttributes() ?>><?php echo $Page->cuadro9->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro10->Visible) { ?>
		<td data-field="cuadro10"<?php echo $Page->cuadro10->CellAttributes() ?>>
<span<?php echo $Page->cuadro10->ViewAttributes() ?>><?php echo $Page->cuadro10->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro11->Visible) { ?>
		<td data-field="cuadro11"<?php echo $Page->cuadro11->CellAttributes() ?>>
<span<?php echo $Page->cuadro11->ViewAttributes() ?>><?php echo $Page->cuadro11->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro12->Visible) { ?>
		<td data-field="cuadro12"<?php echo $Page->cuadro12->CellAttributes() ?>>
<span<?php echo $Page->cuadro12->ViewAttributes() ?>><?php echo $Page->cuadro12->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cuadro13->Visible) { ?>
		<td data-field="cuadro13"<?php echo $Page->cuadro13->CellAttributes() ?>>
<span<?php echo $Page->cuadro13->ViewAttributes() ?>><?php echo $Page->cuadro13->ListViewValue() ?></span></td>
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
<?php } elseif (!$Page->ShowHeader && FALSE) { // No header displayed ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_viewsaludmarcologico" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "viewsaludmarcologicorptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
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
<?php if (!$Page->DrillDown && !$grDashboardReport) { ?>
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
