<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewsaludotrosrptinfo.php" ?>
<?php

//
// Page class
//

$viewsaludotros_rpt = NULL; // Initialize page object first

class crviewsaludotros_rpt extends crviewsaludotros {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewsaludotros_rpt';

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

		// Table object (viewsaludotros)
		if (!isset($GLOBALS["viewsaludotros"])) {
			$GLOBALS["viewsaludotros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewsaludotros"];
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
			define("EWR_TABLE_NAME", 'viewsaludotros', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewsaludotrosrpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewsaludotros');
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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewsaludotros\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewsaludotros',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewsaludotrosrpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewsaludotrosrpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewsaludotrosrpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->nombreactividad->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->nivelestudio->SetVisibility();
		$this->resultado->SetVisibility();
		$this->resultadotamizaje->SetVisibility();
		$this->tapon->SetVisibility();
		$this->repetirprueba->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->parentesco->SetVisibility();
		$this->nombrescompleto->SetVisibility();
		$this->discacidad->SetVisibility();
		$this->tipo_discapacidad->SetVisibility();
		$this->tipo_tapo->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 17;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->nombreactividad->SelectionList = "";
		$this->nombreactividad->DefaultSelectionList = "";
		$this->nombreactividad->ValueList = "";
		$this->nrodiscapacidad->SelectionList = "";
		$this->nrodiscapacidad->DefaultSelectionList = "";
		$this->nrodiscapacidad->ValueList = "";
		$this->sexo->SelectionList = "";
		$this->sexo->DefaultSelectionList = "";
		$this->sexo->ValueList = "";

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
				$this->FirstRowData['nombreactividad'] = ewr_Conv($rs->fields('nombreactividad'), 200);
				$this->FirstRowData['nrodiscapacidad'] = ewr_Conv($rs->fields('nrodiscapacidad'), 200);
				$this->FirstRowData['ci'] = ewr_Conv($rs->fields('ci'), 200);
				$this->FirstRowData['fecha_nacimiento'] = ewr_Conv($rs->fields('fecha_nacimiento'), 133);
				$this->FirstRowData['sexo'] = ewr_Conv($rs->fields('sexo'), 200);
				$this->FirstRowData['nivelestudio'] = ewr_Conv($rs->fields('nivelestudio'), 200);
				$this->FirstRowData['resultado'] = ewr_Conv($rs->fields('resultado'), 200);
				$this->FirstRowData['resultadotamizaje'] = ewr_Conv($rs->fields('resultadotamizaje'), 200);
				$this->FirstRowData['tapon'] = ewr_Conv($rs->fields('tapon'), 3);
				$this->FirstRowData['repetirprueba'] = ewr_Conv($rs->fields('repetirprueba'), 200);
				$this->FirstRowData['observaciones'] = ewr_Conv($rs->fields('observaciones'), 200);
				$this->FirstRowData['parentesco'] = ewr_Conv($rs->fields('parentesco'), 200);
				$this->FirstRowData['nombrescompleto'] = ewr_Conv($rs->fields('nombrescompleto'), 200);
				$this->FirstRowData['discacidad'] = ewr_Conv($rs->fields('discacidad'), 200);
				$this->FirstRowData['tipo_discapacidad'] = ewr_Conv($rs->fields('tipo discapacidad'), 200);
				$this->FirstRowData['tipo_tapo'] = ewr_Conv($rs->fields('tipo tapo'), 200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->nombreactividad->setDbValue($rs->fields('nombreactividad'));
			$this->nrodiscapacidad->setDbValue($rs->fields('nrodiscapacidad'));
			$this->ci->setDbValue($rs->fields('ci'));
			$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
			$this->sexo->setDbValue($rs->fields('sexo'));
			$this->nivelestudio->setDbValue($rs->fields('nivelestudio'));
			$this->resultado->setDbValue($rs->fields('resultado'));
			$this->resultadotamizaje->setDbValue($rs->fields('resultadotamizaje'));
			$this->tapon->setDbValue($rs->fields('tapon'));
			$this->repetirprueba->setDbValue($rs->fields('repetirprueba'));
			$this->observaciones->setDbValue($rs->fields('observaciones'));
			$this->parentesco->setDbValue($rs->fields('parentesco'));
			$this->nombrescompleto->setDbValue($rs->fields('nombrescompleto'));
			$this->discacidad->setDbValue($rs->fields('discacidad'));
			$this->tipo_discapacidad->setDbValue($rs->fields('tipo discapacidad'));
			$this->tipo_tapo->setDbValue($rs->fields('tipo tapo'));
			$this->nombreotros->setDbValue($rs->fields('nombreotros'));
			$this->Val[1] = $this->nombreactividad->CurrentValue;
			$this->Val[2] = $this->nrodiscapacidad->CurrentValue;
			$this->Val[3] = $this->ci->CurrentValue;
			$this->Val[4] = $this->fecha_nacimiento->CurrentValue;
			$this->Val[5] = $this->sexo->CurrentValue;
			$this->Val[6] = $this->nivelestudio->CurrentValue;
			$this->Val[7] = $this->resultado->CurrentValue;
			$this->Val[8] = $this->resultadotamizaje->CurrentValue;
			$this->Val[9] = $this->tapon->CurrentValue;
			$this->Val[10] = $this->repetirprueba->CurrentValue;
			$this->Val[11] = $this->observaciones->CurrentValue;
			$this->Val[12] = $this->parentesco->CurrentValue;
			$this->Val[13] = $this->nombrescompleto->CurrentValue;
			$this->Val[14] = $this->discacidad->CurrentValue;
			$this->Val[15] = $this->tipo_discapacidad->CurrentValue;
			$this->Val[16] = $this->tipo_tapo->CurrentValue;
		} else {
			$this->nombreactividad->setDbValue("");
			$this->nrodiscapacidad->setDbValue("");
			$this->ci->setDbValue("");
			$this->fecha_nacimiento->setDbValue("");
			$this->sexo->setDbValue("");
			$this->nivelestudio->setDbValue("");
			$this->resultado->setDbValue("");
			$this->resultadotamizaje->setDbValue("");
			$this->tapon->setDbValue("");
			$this->repetirprueba->setDbValue("");
			$this->observaciones->setDbValue("");
			$this->parentesco->setDbValue("");
			$this->nombrescompleto->setDbValue("");
			$this->discacidad->setDbValue("");
			$this->tipo_discapacidad->setDbValue("");
			$this->tipo_tapo->setDbValue("");
			$this->nombreotros->setDbValue("");
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
			// Build distinct values for nombreactividad

			if ($popupname == 'viewsaludotros_nombreactividad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreactividad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nombreactividad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nombreactividad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nombreactividad->setDbValue($rswrk->fields[0]);
					$this->nombreactividad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nombreactividad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nombreactividad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nombreactividad->ValueList, $this->nombreactividad->CurrentValue, $this->nombreactividad->ViewValue, FALSE, $this->nombreactividad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nombreactividad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nombreactividad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nombreactividad;
			}

			// Build distinct values for nrodiscapacidad
			if ($popupname == 'viewsaludotros_nrodiscapacidad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nrodiscapacidad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nrodiscapacidad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nrodiscapacidad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nrodiscapacidad->setDbValue($rswrk->fields[0]);
					$this->nrodiscapacidad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nrodiscapacidad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nrodiscapacidad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nrodiscapacidad->ValueList, $this->nrodiscapacidad->CurrentValue, $this->nrodiscapacidad->ViewValue, FALSE, $this->nrodiscapacidad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nrodiscapacidad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nrodiscapacidad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nrodiscapacidad;
			}

			// Build distinct values for sexo
			if ($popupname == 'viewsaludotros_sexo') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->sexo, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->sexo->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->sexo->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->sexo->setDbValue($rswrk->fields[0]);
					$this->sexo->ViewValue = @$rswrk->fields[1];
					if (is_null($this->sexo->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->sexo->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->sexo->ValueList, $this->sexo->CurrentValue, $this->sexo->ViewValue, FALSE, $this->sexo->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->sexo->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->sexo->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->sexo;
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
				$this->ClearSessionSelection('nombreactividad');
				$this->ClearSessionSelection('nrodiscapacidad');
				$this->ClearSessionSelection('sexo');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get nombreactividad selected values

		if (is_array(@$_SESSION["sel_viewsaludotros_nombreactividad"])) {
			$this->LoadSelectionFromSession('nombreactividad');
		} elseif (@$_SESSION["sel_viewsaludotros_nombreactividad"] == EWR_INIT_VALUE) { // Select all
			$this->nombreactividad->SelectionList = "";
		}

		// Get nrodiscapacidad selected values
		if (is_array(@$_SESSION["sel_viewsaludotros_nrodiscapacidad"])) {
			$this->LoadSelectionFromSession('nrodiscapacidad');
		} elseif (@$_SESSION["sel_viewsaludotros_nrodiscapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->nrodiscapacidad->SelectionList = "";
		}

		// Get sexo selected values
		if (is_array(@$_SESSION["sel_viewsaludotros_sexo"])) {
			$this->LoadSelectionFromSession('sexo');
		} elseif (@$_SESSION["sel_viewsaludotros_sexo"] == EWR_INIT_VALUE) { // Select all
			$this->sexo->SelectionList = "";
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

			// nombreactividad
			$this->nombreactividad->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// nivelestudio
			$this->nivelestudio->HrefValue = "";

			// resultado
			$this->resultado->HrefValue = "";

			// resultadotamizaje
			$this->resultadotamizaje->HrefValue = "";

			// tapon
			$this->tapon->HrefValue = "";

			// repetirprueba
			$this->repetirprueba->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// parentesco
			$this->parentesco->HrefValue = "";

			// nombrescompleto
			$this->nombrescompleto->HrefValue = "";

			// discacidad
			$this->discacidad->HrefValue = "";

			// tipo discapacidad
			$this->tipo_discapacidad->HrefValue = "";

			// tipo tapo
			$this->tipo_tapo->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// nombreactividad
			$this->nombreactividad->ViewValue = $this->nombreactividad->CurrentValue;
			$this->nombreactividad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nrodiscapacidad
			$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
			$this->nrodiscapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ci
			$this->ci->ViewValue = $this->ci->CurrentValue;
			$this->ci->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha_nacimiento
			$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
			$this->fecha_nacimiento->ViewValue = ewr_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
			$this->fecha_nacimiento->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// sexo
			$this->sexo->ViewValue = $this->sexo->CurrentValue;
			$this->sexo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nivelestudio
			$this->nivelestudio->ViewValue = $this->nivelestudio->CurrentValue;
			$this->nivelestudio->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// resultado
			$this->resultado->ViewValue = $this->resultado->CurrentValue;
			$this->resultado->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// resultadotamizaje
			$this->resultadotamizaje->ViewValue = $this->resultadotamizaje->CurrentValue;
			$this->resultadotamizaje->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tapon
			$this->tapon->ViewValue = $this->tapon->CurrentValue;
			$this->tapon->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// repetirprueba
			$this->repetirprueba->ViewValue = $this->repetirprueba->CurrentValue;
			$this->repetirprueba->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// parentesco
			$this->parentesco->ViewValue = $this->parentesco->CurrentValue;
			$this->parentesco->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombrescompleto
			$this->nombrescompleto->ViewValue = $this->nombrescompleto->CurrentValue;
			$this->nombrescompleto->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// discacidad
			$this->discacidad->ViewValue = $this->discacidad->CurrentValue;
			$this->discacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipo discapacidad
			$this->tipo_discapacidad->ViewValue = $this->tipo_discapacidad->CurrentValue;
			$this->tipo_discapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipo tapo
			$this->tipo_tapo->ViewValue = $this->tipo_tapo->CurrentValue;
			$this->tipo_tapo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreactividad
			$this->nombreactividad->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// nivelestudio
			$this->nivelestudio->HrefValue = "";

			// resultado
			$this->resultado->HrefValue = "";

			// resultadotamizaje
			$this->resultadotamizaje->HrefValue = "";

			// tapon
			$this->tapon->HrefValue = "";

			// repetirprueba
			$this->repetirprueba->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// parentesco
			$this->parentesco->HrefValue = "";

			// nombrescompleto
			$this->nombrescompleto->HrefValue = "";

			// discacidad
			$this->discacidad->HrefValue = "";

			// tipo discapacidad
			$this->tipo_discapacidad->HrefValue = "";

			// tipo tapo
			$this->tipo_tapo->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// nombreactividad
			$CurrentValue = $this->nombreactividad->CurrentValue;
			$ViewValue = &$this->nombreactividad->ViewValue;
			$ViewAttrs = &$this->nombreactividad->ViewAttrs;
			$CellAttrs = &$this->nombreactividad->CellAttrs;
			$HrefValue = &$this->nombreactividad->HrefValue;
			$LinkAttrs = &$this->nombreactividad->LinkAttrs;
			$this->Cell_Rendered($this->nombreactividad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nrodiscapacidad
			$CurrentValue = $this->nrodiscapacidad->CurrentValue;
			$ViewValue = &$this->nrodiscapacidad->ViewValue;
			$ViewAttrs = &$this->nrodiscapacidad->ViewAttrs;
			$CellAttrs = &$this->nrodiscapacidad->CellAttrs;
			$HrefValue = &$this->nrodiscapacidad->HrefValue;
			$LinkAttrs = &$this->nrodiscapacidad->LinkAttrs;
			$this->Cell_Rendered($this->nrodiscapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// ci
			$CurrentValue = $this->ci->CurrentValue;
			$ViewValue = &$this->ci->ViewValue;
			$ViewAttrs = &$this->ci->ViewAttrs;
			$CellAttrs = &$this->ci->CellAttrs;
			$HrefValue = &$this->ci->HrefValue;
			$LinkAttrs = &$this->ci->LinkAttrs;
			$this->Cell_Rendered($this->ci, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fecha_nacimiento
			$CurrentValue = $this->fecha_nacimiento->CurrentValue;
			$ViewValue = &$this->fecha_nacimiento->ViewValue;
			$ViewAttrs = &$this->fecha_nacimiento->ViewAttrs;
			$CellAttrs = &$this->fecha_nacimiento->CellAttrs;
			$HrefValue = &$this->fecha_nacimiento->HrefValue;
			$LinkAttrs = &$this->fecha_nacimiento->LinkAttrs;
			$this->Cell_Rendered($this->fecha_nacimiento, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// sexo
			$CurrentValue = $this->sexo->CurrentValue;
			$ViewValue = &$this->sexo->ViewValue;
			$ViewAttrs = &$this->sexo->ViewAttrs;
			$CellAttrs = &$this->sexo->CellAttrs;
			$HrefValue = &$this->sexo->HrefValue;
			$LinkAttrs = &$this->sexo->LinkAttrs;
			$this->Cell_Rendered($this->sexo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nivelestudio
			$CurrentValue = $this->nivelestudio->CurrentValue;
			$ViewValue = &$this->nivelestudio->ViewValue;
			$ViewAttrs = &$this->nivelestudio->ViewAttrs;
			$CellAttrs = &$this->nivelestudio->CellAttrs;
			$HrefValue = &$this->nivelestudio->HrefValue;
			$LinkAttrs = &$this->nivelestudio->LinkAttrs;
			$this->Cell_Rendered($this->nivelestudio, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// resultado
			$CurrentValue = $this->resultado->CurrentValue;
			$ViewValue = &$this->resultado->ViewValue;
			$ViewAttrs = &$this->resultado->ViewAttrs;
			$CellAttrs = &$this->resultado->CellAttrs;
			$HrefValue = &$this->resultado->HrefValue;
			$LinkAttrs = &$this->resultado->LinkAttrs;
			$this->Cell_Rendered($this->resultado, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// resultadotamizaje
			$CurrentValue = $this->resultadotamizaje->CurrentValue;
			$ViewValue = &$this->resultadotamizaje->ViewValue;
			$ViewAttrs = &$this->resultadotamizaje->ViewAttrs;
			$CellAttrs = &$this->resultadotamizaje->CellAttrs;
			$HrefValue = &$this->resultadotamizaje->HrefValue;
			$LinkAttrs = &$this->resultadotamizaje->LinkAttrs;
			$this->Cell_Rendered($this->resultadotamizaje, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tapon
			$CurrentValue = $this->tapon->CurrentValue;
			$ViewValue = &$this->tapon->ViewValue;
			$ViewAttrs = &$this->tapon->ViewAttrs;
			$CellAttrs = &$this->tapon->CellAttrs;
			$HrefValue = &$this->tapon->HrefValue;
			$LinkAttrs = &$this->tapon->LinkAttrs;
			$this->Cell_Rendered($this->tapon, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// repetirprueba
			$CurrentValue = $this->repetirprueba->CurrentValue;
			$ViewValue = &$this->repetirprueba->ViewValue;
			$ViewAttrs = &$this->repetirprueba->ViewAttrs;
			$CellAttrs = &$this->repetirprueba->CellAttrs;
			$HrefValue = &$this->repetirprueba->HrefValue;
			$LinkAttrs = &$this->repetirprueba->LinkAttrs;
			$this->Cell_Rendered($this->repetirprueba, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// observaciones
			$CurrentValue = $this->observaciones->CurrentValue;
			$ViewValue = &$this->observaciones->ViewValue;
			$ViewAttrs = &$this->observaciones->ViewAttrs;
			$CellAttrs = &$this->observaciones->CellAttrs;
			$HrefValue = &$this->observaciones->HrefValue;
			$LinkAttrs = &$this->observaciones->LinkAttrs;
			$this->Cell_Rendered($this->observaciones, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// parentesco
			$CurrentValue = $this->parentesco->CurrentValue;
			$ViewValue = &$this->parentesco->ViewValue;
			$ViewAttrs = &$this->parentesco->ViewAttrs;
			$CellAttrs = &$this->parentesco->CellAttrs;
			$HrefValue = &$this->parentesco->HrefValue;
			$LinkAttrs = &$this->parentesco->LinkAttrs;
			$this->Cell_Rendered($this->parentesco, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombrescompleto
			$CurrentValue = $this->nombrescompleto->CurrentValue;
			$ViewValue = &$this->nombrescompleto->ViewValue;
			$ViewAttrs = &$this->nombrescompleto->ViewAttrs;
			$CellAttrs = &$this->nombrescompleto->CellAttrs;
			$HrefValue = &$this->nombrescompleto->HrefValue;
			$LinkAttrs = &$this->nombrescompleto->LinkAttrs;
			$this->Cell_Rendered($this->nombrescompleto, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// discacidad
			$CurrentValue = $this->discacidad->CurrentValue;
			$ViewValue = &$this->discacidad->ViewValue;
			$ViewAttrs = &$this->discacidad->ViewAttrs;
			$CellAttrs = &$this->discacidad->CellAttrs;
			$HrefValue = &$this->discacidad->HrefValue;
			$LinkAttrs = &$this->discacidad->LinkAttrs;
			$this->Cell_Rendered($this->discacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipo discapacidad
			$CurrentValue = $this->tipo_discapacidad->CurrentValue;
			$ViewValue = &$this->tipo_discapacidad->ViewValue;
			$ViewAttrs = &$this->tipo_discapacidad->ViewAttrs;
			$CellAttrs = &$this->tipo_discapacidad->CellAttrs;
			$HrefValue = &$this->tipo_discapacidad->HrefValue;
			$LinkAttrs = &$this->tipo_discapacidad->LinkAttrs;
			$this->Cell_Rendered($this->tipo_discapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipo tapo
			$CurrentValue = $this->tipo_tapo->CurrentValue;
			$ViewValue = &$this->tipo_tapo->ViewValue;
			$ViewAttrs = &$this->tipo_tapo->ViewAttrs;
			$CellAttrs = &$this->tipo_tapo->CellAttrs;
			$HrefValue = &$this->tipo_tapo->HrefValue;
			$LinkAttrs = &$this->tipo_tapo->LinkAttrs;
			$this->Cell_Rendered($this->tipo_tapo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->nombreactividad->Visible) $this->DtlColumnCount += 1;
		if ($this->nrodiscapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->ci->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha_nacimiento->Visible) $this->DtlColumnCount += 1;
		if ($this->sexo->Visible) $this->DtlColumnCount += 1;
		if ($this->nivelestudio->Visible) $this->DtlColumnCount += 1;
		if ($this->resultado->Visible) $this->DtlColumnCount += 1;
		if ($this->resultadotamizaje->Visible) $this->DtlColumnCount += 1;
		if ($this->tapon->Visible) $this->DtlColumnCount += 1;
		if ($this->repetirprueba->Visible) $this->DtlColumnCount += 1;
		if ($this->observaciones->Visible) $this->DtlColumnCount += 1;
		if ($this->parentesco->Visible) $this->DtlColumnCount += 1;
		if ($this->nombrescompleto->Visible) $this->DtlColumnCount += 1;
		if ($this->discacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->tipo_discapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->tipo_tapo->Visible) $this->DtlColumnCount += 1;
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

			// Set/clear dropdown for field nombreactividad
			if ($this->PopupName == 'viewsaludotros_nombreactividad' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->nombreactividad->DropDownValue = EWR_ALL_VALUE;
				else
					$this->nombreactividad->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewsaludotros_nombreactividad') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'nombreactividad');
			}

			// Set/clear dropdown for field nrodiscapacidad
			if ($this->PopupName == 'viewsaludotros_nrodiscapacidad' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->nrodiscapacidad->DropDownValue = EWR_ALL_VALUE;
				else
					$this->nrodiscapacidad->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewsaludotros_nrodiscapacidad') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'nrodiscapacidad');
			}

			// Set/clear dropdown for field sexo
			if ($this->PopupName == 'viewsaludotros_sexo' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->sexo->DropDownValue = EWR_ALL_VALUE;
				else
					$this->sexo->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewsaludotros_sexo') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'sexo');
			}

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->nombreactividad->DropDownValue, $this->nombreactividad->SearchOperator, 'nombreactividad'); // Field nombreactividad
			$this->SetSessionDropDownValue($this->nrodiscapacidad->DropDownValue, $this->nrodiscapacidad->SearchOperator, 'nrodiscapacidad'); // Field nrodiscapacidad
			$this->SetSessionDropDownValue($this->sexo->DropDownValue, $this->sexo->SearchOperator, 'sexo'); // Field sexo

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field nombreactividad
			if ($this->GetDropDownValue($this->nombreactividad)) {
				$bSetupFilter = TRUE;
			} elseif ($this->nombreactividad->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewsaludotros_nombreactividad'])) {
				$bSetupFilter = TRUE;
			}

			// Field nrodiscapacidad
			if ($this->GetDropDownValue($this->nrodiscapacidad)) {
				$bSetupFilter = TRUE;
			} elseif ($this->nrodiscapacidad->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewsaludotros_nrodiscapacidad'])) {
				$bSetupFilter = TRUE;
			}

			// Field sexo
			if ($this->GetDropDownValue($this->sexo)) {
				$bSetupFilter = TRUE;
			} elseif ($this->sexo->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewsaludotros_sexo'])) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->nombreactividad); // Field nombreactividad
			$this->GetSessionDropDownValue($this->nrodiscapacidad); // Field nrodiscapacidad
			$this->GetSessionDropDownValue($this->sexo); // Field sexo
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->nombreactividad, $sFilter, $this->nombreactividad->SearchOperator, FALSE, TRUE); // Field nombreactividad
		$this->BuildDropDownFilter($this->nrodiscapacidad, $sFilter, $this->nrodiscapacidad->SearchOperator, FALSE, TRUE); // Field nrodiscapacidad
		$this->BuildDropDownFilter($this->sexo, $sFilter, $this->sexo->SearchOperator, FALSE, TRUE); // Field sexo

		// Save parms to session
		$this->SetSessionDropDownValue($this->nombreactividad->DropDownValue, $this->nombreactividad->SearchOperator, 'nombreactividad'); // Field nombreactividad
		$this->SetSessionDropDownValue($this->nrodiscapacidad->DropDownValue, $this->nrodiscapacidad->SearchOperator, 'nrodiscapacidad'); // Field nrodiscapacidad
		$this->SetSessionDropDownValue($this->sexo->DropDownValue, $this->sexo->SearchOperator, 'sexo'); // Field sexo

		// Setup filter
		if ($bSetupFilter) {

			// Field nombreactividad
			$sWrk = "";
			$this->BuildDropDownFilter($this->nombreactividad, $sWrk, $this->nombreactividad->SearchOperator);
			ewr_LoadSelectionFromFilter($this->nombreactividad, $sWrk, $this->nombreactividad->SelectionList, $this->nombreactividad->DropDownValue);
			$_SESSION['sel_viewsaludotros_nombreactividad'] = ($this->nombreactividad->SelectionList == "") ? EWR_INIT_VALUE : $this->nombreactividad->SelectionList;

			// Field nrodiscapacidad
			$sWrk = "";
			$this->BuildDropDownFilter($this->nrodiscapacidad, $sWrk, $this->nrodiscapacidad->SearchOperator);
			ewr_LoadSelectionFromFilter($this->nrodiscapacidad, $sWrk, $this->nrodiscapacidad->SelectionList, $this->nrodiscapacidad->DropDownValue);
			$_SESSION['sel_viewsaludotros_nrodiscapacidad'] = ($this->nrodiscapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->nrodiscapacidad->SelectionList;

			// Field sexo
			$sWrk = "";
			$this->BuildDropDownFilter($this->sexo, $sWrk, $this->sexo->SearchOperator);
			ewr_LoadSelectionFromFilter($this->sexo, $sWrk, $this->sexo->SelectionList, $this->sexo->DropDownValue);
			$_SESSION['sel_viewsaludotros_sexo'] = ($this->sexo->SelectionList == "") ? EWR_INIT_VALUE : $this->sexo->SelectionList;
		}

		// Field nombreactividad
		ewr_LoadDropDownList($this->nombreactividad->DropDownList, $this->nombreactividad->DropDownValue);

		// Field nrodiscapacidad
		ewr_LoadDropDownList($this->nrodiscapacidad->DropDownList, $this->nrodiscapacidad->DropDownValue);

		// Field sexo
		ewr_LoadDropDownList($this->sexo->DropDownList, $this->sexo->DropDownValue);
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewsaludotros_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsaludotros_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewsaludotros_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsaludotros_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewsaludotros_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewsaludotros_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewsaludotros_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewsaludotros_' . $parm] = $sv;
		$_SESSION['so_viewsaludotros_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewsaludotros_' . $parm] = $sv1;
		$_SESSION['so_viewsaludotros_' . $parm] = $so1;
		$_SESSION['sc_viewsaludotros_' . $parm] = $sc;
		$_SESSION['sv2_viewsaludotros_' . $parm] = $sv2;
		$_SESSION['so2_viewsaludotros_' . $parm] = $so2;
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
		$_SESSION["sel_viewsaludotros_$parm"] = "";
		$_SESSION["rf_viewsaludotros_$parm"] = "";
		$_SESSION["rt_viewsaludotros_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewsaludotros_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewsaludotros_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewsaludotros_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field nombreactividad
		$this->nombreactividad->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->nombreactividad->DropDownValue = $this->nombreactividad->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->nombreactividad, $sWrk, $this->nombreactividad->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->nombreactividad, $sWrk, $this->nombreactividad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombreactividad->SelectionList = $this->nombreactividad->DefaultSelectionList;

		// Field nrodiscapacidad
		$this->nrodiscapacidad->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->nrodiscapacidad->DropDownValue = $this->nrodiscapacidad->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->nrodiscapacidad, $sWrk, $this->nrodiscapacidad->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->nrodiscapacidad, $sWrk, $this->nrodiscapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nrodiscapacidad->SelectionList = $this->nrodiscapacidad->DefaultSelectionList;

		// Field sexo
		$this->sexo->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->sexo->DropDownValue = $this->sexo->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->sexo, $sWrk, $this->sexo->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->sexo, $sWrk, $this->sexo->DefaultSelectionList);
		if (!$this->SearchCommand) $this->sexo->SelectionList = $this->sexo->DefaultSelectionList;
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
		/**
		* Set up default values for popup filters
		*/

		// Field nombreactividad
		// $this->nombreactividad->DefaultSelectionList = array("val1", "val2");
		// Field nrodiscapacidad
		// $this->nrodiscapacidad->DefaultSelectionList = array("val1", "val2");
		// Field sexo
		// $this->sexo->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check nombreactividad extended filter
		if ($this->NonTextFilterApplied($this->nombreactividad))
			return TRUE;

		// Check nombreactividad popup filter
		if (!ewr_MatchedArray($this->nombreactividad->DefaultSelectionList, $this->nombreactividad->SelectionList))
			return TRUE;

		// Check nrodiscapacidad extended filter
		if ($this->NonTextFilterApplied($this->nrodiscapacidad))
			return TRUE;

		// Check nrodiscapacidad popup filter
		if (!ewr_MatchedArray($this->nrodiscapacidad->DefaultSelectionList, $this->nrodiscapacidad->SelectionList))
			return TRUE;

		// Check sexo extended filter
		if ($this->NonTextFilterApplied($this->sexo))
			return TRUE;

		// Check sexo popup filter
		if (!ewr_MatchedArray($this->sexo->DefaultSelectionList, $this->sexo->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field nombreactividad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->nombreactividad, $sExtWrk, $this->nombreactividad->SearchOperator);
		if (is_array($this->nombreactividad->SelectionList))
			$sWrk = ewr_JoinArray($this->nombreactividad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreactividad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nrodiscapacidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->nrodiscapacidad, $sExtWrk, $this->nrodiscapacidad->SearchOperator);
		if (is_array($this->nrodiscapacidad->SelectionList))
			$sWrk = ewr_JoinArray($this->nrodiscapacidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nrodiscapacidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field sexo
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->sexo, $sExtWrk, $this->sexo->SearchOperator);
		if (is_array($this->sexo->SelectionList))
			$sWrk = ewr_JoinArray($this->sexo->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->sexo->FldCaption() . "</span>" . $sFilter . "</div>";
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

		// Field nombreactividad
		$sWrk = "";
		$sWrk = ($this->nombreactividad->DropDownValue <> EWR_INIT_VALUE) ? $this->nombreactividad->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_nombreactividad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->nombreactividad->SelectionList <> EWR_INIT_VALUE) ? $this->nombreactividad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nombreactividad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nrodiscapacidad
		$sWrk = "";
		$sWrk = ($this->nrodiscapacidad->DropDownValue <> EWR_INIT_VALUE) ? $this->nrodiscapacidad->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_nrodiscapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->nrodiscapacidad->SelectionList <> EWR_INIT_VALUE) ? $this->nrodiscapacidad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nrodiscapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field sexo
		$sWrk = "";
		$sWrk = ($this->sexo->DropDownValue <> EWR_INIT_VALUE) ? $this->sexo->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_sexo\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->sexo->SelectionList <> EWR_INIT_VALUE) ? $this->sexo->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_sexo\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field nombreactividad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombreactividad", $filter)) {
			$sWrk = $filter["sv_nombreactividad"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_nombreactividad"], "nombreactividad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombreactividad", $filter)) {
			$sWrk = $filter["sel_nombreactividad"];
			$sWrk = explode("||", $sWrk);
			$this->nombreactividad->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludotros_nombreactividad"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nombreactividad"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nombreactividad");
			$this->nombreactividad->SelectionList = "";
			$_SESSION["sel_viewsaludotros_nombreactividad"] = "";
		}

		// Field nrodiscapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nrodiscapacidad", $filter)) {
			$sWrk = $filter["sv_nrodiscapacidad"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_nrodiscapacidad"], "nrodiscapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nrodiscapacidad", $filter)) {
			$sWrk = $filter["sel_nrodiscapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->nrodiscapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludotros_nrodiscapacidad"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nrodiscapacidad"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nrodiscapacidad");
			$this->nrodiscapacidad->SelectionList = "";
			$_SESSION["sel_viewsaludotros_nrodiscapacidad"] = "";
		}

		// Field sexo
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_sexo", $filter)) {
			$sWrk = $filter["sv_sexo"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_sexo"], "sexo");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_sexo", $filter)) {
			$sWrk = $filter["sel_sexo"];
			$sWrk = explode("||", $sWrk);
			$this->sexo->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludotros_sexo"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "sexo"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "sexo");
			$this->sexo->SelectionList = "";
			$_SESSION["sel_viewsaludotros_sexo"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->DropDownFilterExist($this->nombreactividad, $this->nombreactividad->SearchOperator)) {
			if (is_array($this->nombreactividad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombreactividad, "`nombreactividad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreactividad, $sFilter, "popup");
				$this->nombreactividad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->nrodiscapacidad, $this->nrodiscapacidad->SearchOperator)) {
			if (is_array($this->nrodiscapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nrodiscapacidad, "`nrodiscapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nrodiscapacidad, $sFilter, "popup");
				$this->nrodiscapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->sexo, $this->sexo->SearchOperator)) {
			if (is_array($this->sexo->SelectionList)) {
				$sFilter = ewr_FilterSql($this->sexo, "`sexo`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->sexo, $sFilter, "popup");
				$this->sexo->CurrentFilter = $sFilter;
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
			$this->nombreactividad->setSort("");
			$this->nrodiscapacidad->setSort("");
			$this->ci->setSort("");
			$this->fecha_nacimiento->setSort("");
			$this->sexo->setSort("");
			$this->nivelestudio->setSort("");
			$this->resultado->setSort("");
			$this->resultadotamizaje->setSort("");
			$this->tapon->setSort("");
			$this->repetirprueba->setSort("");
			$this->observaciones->setSort("");
			$this->parentesco->setSort("");
			$this->nombrescompleto->setSort("");
			$this->discacidad->setSort("");
			$this->tipo_discapacidad->setSort("");
			$this->tipo_tapo->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->nombreactividad); // nombreactividad
			$this->UpdateSort($this->nrodiscapacidad); // nrodiscapacidad
			$this->UpdateSort($this->ci); // ci
			$this->UpdateSort($this->fecha_nacimiento); // fecha_nacimiento
			$this->UpdateSort($this->sexo); // sexo
			$this->UpdateSort($this->nivelestudio); // nivelestudio
			$this->UpdateSort($this->resultado); // resultado
			$this->UpdateSort($this->resultadotamizaje); // resultadotamizaje
			$this->UpdateSort($this->tapon); // tapon
			$this->UpdateSort($this->repetirprueba); // repetirprueba
			$this->UpdateSort($this->observaciones); // observaciones
			$this->UpdateSort($this->parentesco); // parentesco
			$this->UpdateSort($this->nombrescompleto); // nombrescompleto
			$this->UpdateSort($this->discacidad); // discacidad
			$this->UpdateSort($this->tipo_discapacidad); // tipo discapacidad
			$this->UpdateSort($this->tipo_tapo); // tipo tapo
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
if (!isset($viewsaludotros_rpt)) $viewsaludotros_rpt = new crviewsaludotros_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewsaludotros_rpt;

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
var viewsaludotros_rpt = new ewr_Page("viewsaludotros_rpt");

// Page properties
viewsaludotros_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewsaludotros_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewsaludotrosrpt = new ewr_Form("fviewsaludotrosrpt");

// Validate method
fviewsaludotrosrpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewsaludotrosrpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewsaludotrosrpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewsaludotrosrpt.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fviewsaludotrosrpt.Lists["sv_nombreactividad"] = {"LinkField":"sv_nombreactividad","Ajax":true,"DisplayFields":["sv_nombreactividad","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewsaludotrosrpt.Lists["sv_nrodiscapacidad"] = {"LinkField":"sv_nrodiscapacidad","Ajax":true,"DisplayFields":["sv_nrodiscapacidad","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewsaludotrosrpt.Lists["sv_sexo[]"] = {"LinkField":"sv_sexo","Ajax":true,"DisplayFields":["sv_sexo","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
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
<form name="fviewsaludotrosrpt" id="fviewsaludotrosrpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewsaludotrosrpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_nombreactividad" class="ewCell form-group">
	<label for="sv_nombreactividad" class="ewSearchCaption ewLabel"><?php echo $Page->nombreactividad->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->nombreactividad->EditAttrs["class"], "form-control"); ?>
<select data-table="viewsaludotros" data-field="x_nombreactividad" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->nombreactividad->DisplayValueSeparator) ? json_encode($Page->nombreactividad->DisplayValueSeparator) : $Page->nombreactividad->DisplayValueSeparator) ?>" id="sv_nombreactividad" name="sv_nombreactividad"<?php echo $Page->nombreactividad->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->nombreactividad->AdvancedFilters) ? count($Page->nombreactividad->AdvancedFilters) : 0;
	$cntd = is_array($Page->nombreactividad->DropDownList) ? count($Page->nombreactividad->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->nombreactividad->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->nombreactividad->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->nombreactividad->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->nombreactividad->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_nombreactividad" id="s_sv_nombreactividad" value="<?php echo $Page->nombreactividad->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewsaludotrosrpt.Lists["sv_nombreactividad"].Options = <?php echo ewr_ArrayToJson($Page->nombreactividad->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_nrodiscapacidad" class="ewCell form-group">
	<label for="sv_nrodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $Page->nrodiscapacidad->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->nrodiscapacidad->EditAttrs["class"], "form-control"); ?>
<select data-table="viewsaludotros" data-field="x_nrodiscapacidad" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->nrodiscapacidad->DisplayValueSeparator) ? json_encode($Page->nrodiscapacidad->DisplayValueSeparator) : $Page->nrodiscapacidad->DisplayValueSeparator) ?>" id="sv_nrodiscapacidad" name="sv_nrodiscapacidad"<?php echo $Page->nrodiscapacidad->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->nrodiscapacidad->AdvancedFilters) ? count($Page->nrodiscapacidad->AdvancedFilters) : 0;
	$cntd = is_array($Page->nrodiscapacidad->DropDownList) ? count($Page->nrodiscapacidad->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->nrodiscapacidad->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->nrodiscapacidad->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->nrodiscapacidad->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->nrodiscapacidad->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_nrodiscapacidad" id="s_sv_nrodiscapacidad" value="<?php echo $Page->nrodiscapacidad->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewsaludotrosrpt.Lists["sv_nrodiscapacidad"].Options = <?php echo ewr_ArrayToJson($Page->nrodiscapacidad->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_sexo" class="ewCell form-group">
	<label for="sv_sexo" class="ewSearchCaption ewLabel"><?php echo $Page->sexo->FldCaption() ?></label>
	<span class="ewSearchField">
<?php $selwrk = ewr_MatchedFilterValue($Page->sexo->DropDownValue, EWR_ALL_VALUE) ? " selected" : ""; ?>
<?php ewr_PrependClass($Page->sexo->EditAttrs["class"], "form-control"); ?>
<select data-table="viewsaludotros" data-field="x_sexo" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->sexo->DisplayValueSeparator) ? json_encode($Page->sexo->DisplayValueSeparator) : $Page->sexo->DisplayValueSeparator) ?>" id="sv_sexo[]" name="sv_sexo[]" multiple="multiple"<?php echo $Page->sexo->EditAttributes() ?>>
<option value="<?php echo EWR_ALL_VALUE; ?>"<?php echo $selwrk ?>><?php echo $ReportLanguage->Phrase("SelectAll") ?></option>
<?php
	$cntf = is_array($Page->sexo->AdvancedFilters) ? count($Page->sexo->AdvancedFilters) : 0;
	$cntd = is_array($Page->sexo->DropDownList) ? count($Page->sexo->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->sexo->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->sexo->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->sexo->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->sexo->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_sexo" id="s_sv_sexo" value="<?php echo $Page->sexo->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewsaludotrosrpt.Lists["sv_sexo[]"].Options = <?php echo ewr_ArrayToJson($Page->sexo->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewsaludotrosrpt.Init();
fviewsaludotrosrpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<div id="gmp_viewsaludotros" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->nombreactividad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreactividad"><div class="viewsaludotros_nombreactividad"><span class="ewTableHeaderCaption"><?php echo $Page->nombreactividad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreactividad">
<?php if ($Page->SortUrl($Page->nombreactividad) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_nombreactividad">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreactividad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludotros_nombreactividad', range: false, from: '<?php echo $Page->nombreactividad->RangeFrom; ?>', to: '<?php echo $Page->nombreactividad->RangeTo; ?>', url: 'viewsaludotrosrpt.php' });" id="x_nombreactividad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_nombreactividad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreactividad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreactividad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreactividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreactividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludotros_nombreactividad', range: false, from: '<?php echo $Page->nombreactividad->RangeFrom; ?>', to: '<?php echo $Page->nombreactividad->RangeTo; ?>', url: 'viewsaludotrosrpt.php' });" id="x_nombreactividad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nrodiscapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nrodiscapacidad"><div class="viewsaludotros_nrodiscapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nrodiscapacidad">
<?php if ($Page->SortUrl($Page->nrodiscapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_nrodiscapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludotros_nrodiscapacidad', range: false, from: '<?php echo $Page->nrodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->nrodiscapacidad->RangeTo; ?>', url: 'viewsaludotrosrpt.php' });" id="x_nrodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_nrodiscapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nrodiscapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludotros_nrodiscapacidad', range: false, from: '<?php echo $Page->nrodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->nrodiscapacidad->RangeTo; ?>', url: 'viewsaludotrosrpt.php' });" id="x_nrodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ci"><div class="viewsaludotros_ci"><span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ci">
<?php if ($Page->SortUrl($Page->ci) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_ci">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_ci" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ci) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha_nacimiento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_nacimiento"><div class="viewsaludotros_fecha_nacimiento"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_nacimiento">
<?php if ($Page->SortUrl($Page->fecha_nacimiento) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_fecha_nacimiento">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_fecha_nacimiento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_nacimiento) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sexo"><div class="viewsaludotros_sexo"><span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sexo">
<?php if ($Page->SortUrl($Page->sexo) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_sexo">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludotros_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewsaludotrosrpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_sexo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sexo) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludotros_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewsaludotrosrpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nivelestudio->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nivelestudio"><div class="viewsaludotros_nivelestudio"><span class="ewTableHeaderCaption"><?php echo $Page->nivelestudio->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nivelestudio">
<?php if ($Page->SortUrl($Page->nivelestudio) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_nivelestudio">
			<span class="ewTableHeaderCaption"><?php echo $Page->nivelestudio->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_nivelestudio" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nivelestudio) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nivelestudio->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nivelestudio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nivelestudio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->resultado->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="resultado"><div class="viewsaludotros_resultado"><span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="resultado">
<?php if ($Page->SortUrl($Page->resultado) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_resultado">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_resultado" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->resultado) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->resultadotamizaje->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="resultadotamizaje"><div class="viewsaludotros_resultadotamizaje"><span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="resultadotamizaje">
<?php if ($Page->SortUrl($Page->resultadotamizaje) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_resultadotamizaje">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_resultadotamizaje" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->resultadotamizaje) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->resultadotamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->resultadotamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tapon->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tapon"><div class="viewsaludotros_tapon"><span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tapon">
<?php if ($Page->SortUrl($Page->tapon) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_tapon">
			<span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_tapon" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tapon) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tapon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tapon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->repetirprueba->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="repetirprueba"><div class="viewsaludotros_repetirprueba"><span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="repetirprueba">
<?php if ($Page->SortUrl($Page->repetirprueba) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_repetirprueba">
			<span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_repetirprueba" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->repetirprueba) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->repetirprueba->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->repetirprueba->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="observaciones"><div class="viewsaludotros_observaciones"><span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="observaciones">
<?php if ($Page->SortUrl($Page->observaciones) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_observaciones">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_observaciones" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->observaciones) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->parentesco->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="parentesco"><div class="viewsaludotros_parentesco"><span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="parentesco">
<?php if ($Page->SortUrl($Page->parentesco) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_parentesco">
			<span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_parentesco" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->parentesco) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombrescompleto->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombrescompleto"><div class="viewsaludotros_nombrescompleto"><span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombrescompleto">
<?php if ($Page->SortUrl($Page->nombrescompleto) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_nombrescompleto">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_nombrescompleto" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombrescompleto) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombrescompleto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombrescompleto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->discacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="discacidad"><div class="viewsaludotros_discacidad"><span class="ewTableHeaderCaption"><?php echo $Page->discacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="discacidad">
<?php if ($Page->SortUrl($Page->discacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_discacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->discacidad->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_discacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->discacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->discacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->discacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->discacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipo_discapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipo_discapacidad"><div class="viewsaludotros_tipo_discapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->tipo_discapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipo_discapacidad">
<?php if ($Page->SortUrl($Page->tipo_discapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_tipo_discapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipo_discapacidad->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_tipo_discapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipo_discapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipo_discapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipo_discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipo_discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipo_tapo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipo_tapo"><div class="viewsaludotros_tipo_tapo"><span class="ewTableHeaderCaption"><?php echo $Page->tipo_tapo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipo_tapo">
<?php if ($Page->SortUrl($Page->tipo_tapo) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludotros_tipo_tapo">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipo_tapo->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludotros_tipo_tapo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipo_tapo) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipo_tapo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipo_tapo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipo_tapo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
<?php if ($Page->nombreactividad->Visible) { ?>
		<td data-field="nombreactividad"<?php echo $Page->nombreactividad->CellAttributes() ?>>
<span<?php echo $Page->nombreactividad->ViewAttributes() ?>><?php echo $Page->nombreactividad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nrodiscapacidad->Visible) { ?>
		<td data-field="nrodiscapacidad"<?php echo $Page->nrodiscapacidad->CellAttributes() ?>>
<span<?php echo $Page->nrodiscapacidad->ViewAttributes() ?>><?php echo $Page->nrodiscapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
		<td data-field="ci"<?php echo $Page->ci->CellAttributes() ?>>
<span<?php echo $Page->ci->ViewAttributes() ?>><?php echo $Page->ci->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fecha_nacimiento->Visible) { ?>
		<td data-field="fecha_nacimiento"<?php echo $Page->fecha_nacimiento->CellAttributes() ?>>
<span<?php echo $Page->fecha_nacimiento->ViewAttributes() ?>><?php echo $Page->fecha_nacimiento->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
		<td data-field="sexo"<?php echo $Page->sexo->CellAttributes() ?>>
<span<?php echo $Page->sexo->ViewAttributes() ?>><?php echo $Page->sexo->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nivelestudio->Visible) { ?>
		<td data-field="nivelestudio"<?php echo $Page->nivelestudio->CellAttributes() ?>>
<span<?php echo $Page->nivelestudio->ViewAttributes() ?>><?php echo $Page->nivelestudio->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->resultado->Visible) { ?>
		<td data-field="resultado"<?php echo $Page->resultado->CellAttributes() ?>>
<span<?php echo $Page->resultado->ViewAttributes() ?>><?php echo $Page->resultado->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->resultadotamizaje->Visible) { ?>
		<td data-field="resultadotamizaje"<?php echo $Page->resultadotamizaje->CellAttributes() ?>>
<span<?php echo $Page->resultadotamizaje->ViewAttributes() ?>><?php echo $Page->resultadotamizaje->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tapon->Visible) { ?>
		<td data-field="tapon"<?php echo $Page->tapon->CellAttributes() ?>>
<span<?php echo $Page->tapon->ViewAttributes() ?>><?php echo $Page->tapon->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->repetirprueba->Visible) { ?>
		<td data-field="repetirprueba"<?php echo $Page->repetirprueba->CellAttributes() ?>>
<span<?php echo $Page->repetirprueba->ViewAttributes() ?>><?php echo $Page->repetirprueba->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
		<td data-field="observaciones"<?php echo $Page->observaciones->CellAttributes() ?>>
<span<?php echo $Page->observaciones->ViewAttributes() ?>><?php echo $Page->observaciones->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->parentesco->Visible) { ?>
		<td data-field="parentesco"<?php echo $Page->parentesco->CellAttributes() ?>>
<span<?php echo $Page->parentesco->ViewAttributes() ?>><?php echo $Page->parentesco->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombrescompleto->Visible) { ?>
		<td data-field="nombrescompleto"<?php echo $Page->nombrescompleto->CellAttributes() ?>>
<span<?php echo $Page->nombrescompleto->ViewAttributes() ?>><?php echo $Page->nombrescompleto->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->discacidad->Visible) { ?>
		<td data-field="discacidad"<?php echo $Page->discacidad->CellAttributes() ?>>
<span<?php echo $Page->discacidad->ViewAttributes() ?>><?php echo $Page->discacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipo_discapacidad->Visible) { ?>
		<td data-field="tipo_discapacidad"<?php echo $Page->tipo_discapacidad->CellAttributes() ?>>
<span<?php echo $Page->tipo_discapacidad->ViewAttributes() ?>><?php echo $Page->tipo_discapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipo_tapo->Visible) { ?>
		<td data-field="tipo_tapo"<?php echo $Page->tipo_tapo->CellAttributes() ?>>
<span<?php echo $Page->tipo_tapo->ViewAttributes() ?>><?php echo $Page->tipo_tapo->ListViewValue() ?></span></td>
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
<div id="gmp_viewsaludotros" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
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
<?php include "viewsaludotrosrptpager.php" ?>
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
