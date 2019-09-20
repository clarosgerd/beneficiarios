<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "ReportEstudiantessmryinfo.php" ?>
<?php

//
// Page class
//

$ReportEstudiantes_summary = NULL; // Initialize page object first

class crReportEstudiantes_summary extends crReportEstudiantes {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'ReportEstudiantes_summary';

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

		// Table object (ReportEstudiantes)
		if (!isset($GLOBALS["ReportEstudiantes"])) {
			$GLOBALS["ReportEstudiantes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ReportEstudiantes"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'ReportEstudiantes', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fReportEstudiantessummary";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'ReportEstudiantes');
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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_ReportEstudiantes\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_ReportEstudiantes',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fReportEstudiantessummary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fReportEstudiantessummary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fReportEstudiantessummary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->curso->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->nombredisca->SetVisibility();
		$this->nombretipodisca->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->codigorude->SetVisibility();
		$this->codigorude_es->SetVisibility();

		// Handle drill down
		$sDrillDownFilter = $this->GetDrillDownFilter();
		$grDrillDownInPanel = $this->DrillDownInPanel;
		if ($this->DrillDown)
			ewr_AddFilter($this->Filter, $sDrillDownFilter);

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 14;
		$nGrps = 6;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

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

		// Requires search criteria
		if (($this->Filter == $this->UserIDFilter || $grFormError != "") && !$this->DrillDown)
			$this->Filter = "0=101";

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total group count
		$sGrpSort = ewr_UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewr_BuildReportSql($this->getSqlSelectGroup(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, ""); // No need for ORDER BY for total count
		$this->TotalGrps = $this->GetGrpCnt($sSql);
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

		// Get current page groups
		$sSql = ewr_BuildReportSql($this->getSqlSelectGroup(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderByGroup(), $this->Filter, $sGrpSort);
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
		$this->SetupFieldCount();
	}

	// Get summary count
	function GetSummaryCount($lvl, $curValue = TRUE) {
		$cnt = 0;
		foreach ($this->DetailRows as $row) {
			$wrknombreinstitucion = $row["nombreinstitucion"];
			$wrkdepartamentoname = $row["departamentoname"];
			$wrkmunicipioname = $row["municipioname"];
			$wrkprovname = $row["provname"];
			$wrkunidadaname = $row["unidadaname"];
			if ($lvl >= 1) {
				$val = $curValue ? $this->nombreinstitucion->CurrentValue : $this->nombreinstitucion->OldValue;
				$grpval = $curValue ? $this->nombreinstitucion->GroupValue() : $this->nombreinstitucion->GroupOldValue();
				if (is_null($val) && !is_null($wrknombreinstitucion) || !is_null($val) && is_null($wrknombreinstitucion) ||
					$grpval <> $this->nombreinstitucion->getGroupValueBase($wrknombreinstitucion))
				continue;
			}
			if ($lvl >= 2) {
				$val = $curValue ? $this->departamentoname->CurrentValue : $this->departamentoname->OldValue;
				$grpval = $curValue ? $this->departamentoname->GroupValue() : $this->departamentoname->GroupOldValue();
				if (is_null($val) && !is_null($wrkdepartamentoname) || !is_null($val) && is_null($wrkdepartamentoname) ||
					$grpval <> $this->departamentoname->getGroupValueBase($wrkdepartamentoname))
				continue;
			}
			if ($lvl >= 3) {
				$val = $curValue ? $this->municipioname->CurrentValue : $this->municipioname->OldValue;
				$grpval = $curValue ? $this->municipioname->GroupValue() : $this->municipioname->GroupOldValue();
				if (is_null($val) && !is_null($wrkmunicipioname) || !is_null($val) && is_null($wrkmunicipioname) ||
					$grpval <> $this->municipioname->getGroupValueBase($wrkmunicipioname))
				continue;
			}
			if ($lvl >= 4) {
				$val = $curValue ? $this->provname->CurrentValue : $this->provname->OldValue;
				$grpval = $curValue ? $this->provname->GroupValue() : $this->provname->GroupOldValue();
				if (is_null($val) && !is_null($wrkprovname) || !is_null($val) && is_null($wrkprovname) ||
					$grpval <> $this->provname->getGroupValueBase($wrkprovname))
				continue;
			}
			if ($lvl >= 5) {
				$val = $curValue ? $this->unidadaname->CurrentValue : $this->unidadaname->OldValue;
				$grpval = $curValue ? $this->unidadaname->GroupValue() : $this->unidadaname->GroupOldValue();
				if (is_null($val) && !is_null($wrkunidadaname) || !is_null($val) && is_null($wrkunidadaname) ||
					$grpval <> $this->unidadaname->getGroupValueBase($wrkunidadaname))
				continue;
			}
			$cnt++;
		}
		return $cnt;
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		switch ($lvl) {
			case 1:
				return (is_null($this->nombreinstitucion->CurrentValue) && !is_null($this->nombreinstitucion->OldValue)) ||
					(!is_null($this->nombreinstitucion->CurrentValue) && is_null($this->nombreinstitucion->OldValue)) ||
					($this->nombreinstitucion->GroupValue() <> $this->nombreinstitucion->GroupOldValue());
			case 2:
				return (is_null($this->departamentoname->CurrentValue) && !is_null($this->departamentoname->OldValue)) ||
					(!is_null($this->departamentoname->CurrentValue) && is_null($this->departamentoname->OldValue)) ||
					($this->departamentoname->GroupValue() <> $this->departamentoname->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
			case 3:
				return (is_null($this->municipioname->CurrentValue) && !is_null($this->municipioname->OldValue)) ||
					(!is_null($this->municipioname->CurrentValue) && is_null($this->municipioname->OldValue)) ||
					($this->municipioname->GroupValue() <> $this->municipioname->GroupOldValue()) || $this->ChkLvlBreak(2); // Recurse upper level
			case 4:
				return (is_null($this->provname->CurrentValue) && !is_null($this->provname->OldValue)) ||
					(!is_null($this->provname->CurrentValue) && is_null($this->provname->OldValue)) ||
					($this->provname->GroupValue() <> $this->provname->GroupOldValue()) || $this->ChkLvlBreak(3); // Recurse upper level
			case 5:
				return (is_null($this->unidadaname->CurrentValue) && !is_null($this->unidadaname->OldValue)) ||
					(!is_null($this->unidadaname->CurrentValue) && is_null($this->unidadaname->OldValue)) ||
					($this->unidadaname->GroupValue() <> $this->unidadaname->GroupOldValue()) || $this->ChkLvlBreak(4); // Recurse upper level
		}
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

	// Get group count
	function GetGrpCnt($sql) {
		return $this->getRecordCount($sql);
	}

	// Get group recordset
	function GetGrpRs($wrksql, $start = -1, $grps = -1) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$this->nombreinstitucion->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$this->nombreinstitucion->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$this->nombreinstitucion->setDbValue("");
		}
	}

	// Get detail recordset
	function GetDetailRs($wrksql) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->Execute($wrksql);
		$dbtype = ewr_GetConnectionType($this->DBID);
		if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL") {
			$this->DetailRows = ($rswrk) ? $rswrk->GetRows() : array();
		} else { // Cannot MoveFirst, use another recordset
			$rstmp = $conn->Execute($wrksql);
			$this->DetailRows = ($rstmp) ? $rstmp->GetRows() : array();
			$rstmp->Close();
		}
		$conn->raiseErrorFn = "";
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
			$rs->MoveFirst(); // Move first
			if ($this->GrpCount == 1) {
				$this->FirstRowData = array();
				$this->FirstRowData['departamentoname'] = ewr_Conv($rs->fields('departamentoname'), 200);
				$this->FirstRowData['provname'] = ewr_Conv($rs->fields('provname'), 200);
				$this->FirstRowData['municipioname'] = ewr_Conv($rs->fields('municipioname'), 200);
				$this->FirstRowData['unidadaname'] = ewr_Conv($rs->fields('unidadaname'), 200);
				$this->FirstRowData['apellidopaterno'] = ewr_Conv($rs->fields('apellidopaterno'), 200);
				$this->FirstRowData['apellidomaterno'] = ewr_Conv($rs->fields('apellidomaterno'), 200);
				$this->FirstRowData['nombres'] = ewr_Conv($rs->fields('nombres'), 200);
				$this->FirstRowData['ci'] = ewr_Conv($rs->fields('ci'), 200);
				$this->FirstRowData['fechanacimiento'] = ewr_Conv($rs->fields('fechanacimiento'), 133);
				$this->FirstRowData['sexo'] = ewr_Conv($rs->fields('sexo'), 200);
				$this->FirstRowData['curso'] = ewr_Conv($rs->fields('curso'), 200);
				$this->FirstRowData['nrodiscapacidad'] = ewr_Conv($rs->fields('nrodiscapacidad'), 200);
				$this->FirstRowData['nombredisca'] = ewr_Conv($rs->fields('nombredisca'), 200);
				$this->FirstRowData['nombretipodisca'] = ewr_Conv($rs->fields('nombretipodisca'), 200);
				$this->FirstRowData['observaciones'] = ewr_Conv($rs->fields('observaciones'), 200);
				$this->FirstRowData['codigorude'] = ewr_Conv($rs->fields('codigorude'), 200);
				$this->FirstRowData['codigorude_es'] = ewr_Conv($rs->fields('codigorude_es'), 200);
				$this->FirstRowData['nombreinstitucion'] = ewr_Conv($rs->fields('nombreinstitucion'), 200);
			}
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->departamentoname->setDbValue($rs->fields('departamentoname'));
			$this->provname->setDbValue($rs->fields('provname'));
			$this->municipioname->setDbValue($rs->fields('municipioname'));
			$this->unidadaname->setDbValue($rs->fields('unidadaname'));
			$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
			$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
			$this->nombres->setDbValue($rs->fields('nombres'));
			$this->ci->setDbValue($rs->fields('ci'));
			$this->fechanacimiento->setDbValue($rs->fields('fechanacimiento'));
			$this->sexo->setDbValue($rs->fields('sexo'));
			$this->curso->setDbValue($rs->fields('curso'));
			$this->nrodiscapacidad->setDbValue($rs->fields('nrodiscapacidad'));
			$this->nombredisca->setDbValue($rs->fields('nombredisca'));
			$this->nombretipodisca->setDbValue($rs->fields('nombretipodisca'));
			$this->observaciones->setDbValue($rs->fields('observaciones'));
			$this->codigorude->setDbValue($rs->fields('codigorude'));
			$this->codigorude_es->setDbValue($rs->fields('codigorude_es'));
			if ($opt <> 1) {
				if (is_array($this->nombreinstitucion->GroupDbValues))
					$this->nombreinstitucion->setDbValue(@$this->nombreinstitucion->GroupDbValues[$rs->fields('nombreinstitucion')]);
				else
					$this->nombreinstitucion->setDbValue(ewr_GroupValue($this->nombreinstitucion, $rs->fields('nombreinstitucion')));
			}
			$this->Val[1] = $this->apellidopaterno->CurrentValue;
			$this->Val[2] = $this->apellidomaterno->CurrentValue;
			$this->Val[3] = $this->nombres->CurrentValue;
			$this->Val[4] = $this->ci->CurrentValue;
			$this->Val[5] = $this->fechanacimiento->CurrentValue;
			$this->Val[6] = $this->sexo->CurrentValue;
			$this->Val[7] = $this->curso->CurrentValue;
			$this->Val[8] = $this->nrodiscapacidad->CurrentValue;
			$this->Val[9] = $this->nombredisca->CurrentValue;
			$this->Val[10] = $this->nombretipodisca->CurrentValue;
			$this->Val[11] = $this->observaciones->CurrentValue;
			$this->Val[12] = $this->codigorude->CurrentValue;
			$this->Val[13] = $this->codigorude_es->CurrentValue;
		} else {
			$this->departamentoname->setDbValue("");
			$this->provname->setDbValue("");
			$this->municipioname->setDbValue("");
			$this->unidadaname->setDbValue("");
			$this->apellidopaterno->setDbValue("");
			$this->apellidomaterno->setDbValue("");
			$this->nombres->setDbValue("");
			$this->ci->setDbValue("");
			$this->fechanacimiento->setDbValue("");
			$this->sexo->setDbValue("");
			$this->curso->setDbValue("");
			$this->nrodiscapacidad->setDbValue("");
			$this->nombredisca->setDbValue("");
			$this->nombretipodisca->setDbValue("");
			$this->observaciones->setDbValue("");
			$this->codigorude->setDbValue("");
			$this->codigorude_es->setDbValue("");
			$this->nombreinstitucion->setDbValue("");
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

		// Set up departamentoname GroupDbValues
		$sSql = ewr_BuildReportSql($this->departamentoname->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->departamentoname->SqlOrderBy, "", "");
		$rswrk = $conn->Execute($sSql);
		while ($rswrk && !$rswrk->EOF) {
			$this->departamentoname->setDbValue($rswrk->fields[0]);
			if (!is_null($this->departamentoname->CurrentValue) && $this->departamentoname->CurrentValue <> "") {
				$grpval = $rswrk->fields('ew_report_groupvalue');
				$this->departamentoname->GroupDbValues[$this->departamentoname->CurrentValue] = $grpval;
			}
			$rswrk->MoveNext();
		}
		if ($rswrk)
			$rswrk->Close();
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
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP) $this->RowAttrs["data-group"] = $this->nombreinstitucion->GroupOldValue(); // Set up group attribute
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->departamentoname->GroupOldValue(); // Set up group attribute 2
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 3) $this->RowAttrs["data-group-3"] = $this->municipioname->GroupOldValue(); // Set up group attribute 3
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 4) $this->RowAttrs["data-group-4"] = $this->provname->GroupOldValue(); // Set up group attribute 4
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 5) $this->RowAttrs["data-group-5"] = $this->unidadaname->GroupOldValue(); // Set up group attribute 5

			// nombreinstitucion
			$this->nombreinstitucion->GroupViewValue = $this->nombreinstitucion->GroupOldValue();
			$this->nombreinstitucion->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$this->nombreinstitucion->GroupViewValue = ewr_DisplayGroupValue($this->nombreinstitucion, $this->nombreinstitucion->GroupViewValue);
			$this->nombreinstitucion->GroupSummaryOldValue = $this->nombreinstitucion->GroupSummaryValue;
			$this->nombreinstitucion->GroupSummaryValue = $this->nombreinstitucion->GroupViewValue;
			$this->nombreinstitucion->GroupSummaryViewValue = ($this->nombreinstitucion->GroupSummaryOldValue <> $this->nombreinstitucion->GroupSummaryValue) ? $this->nombreinstitucion->GroupSummaryValue : "&nbsp;";

			// departamentoname
			$this->departamentoname->GroupViewValue = $this->departamentoname->GroupOldValue();
			$this->departamentoname->CellAttrs["class"] = ($this->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$this->departamentoname->GroupViewValue = ewr_DisplayGroupValue($this->departamentoname, $this->departamentoname->GroupViewValue);
			$this->departamentoname->GroupSummaryOldValue = $this->departamentoname->GroupSummaryValue;
			$this->departamentoname->GroupSummaryValue = $this->departamentoname->GroupViewValue;
			$this->departamentoname->GroupSummaryViewValue = ($this->departamentoname->GroupSummaryOldValue <> $this->departamentoname->GroupSummaryValue) ? $this->departamentoname->GroupSummaryValue : "&nbsp;";

			// municipioname
			$this->municipioname->GroupViewValue = $this->municipioname->GroupOldValue();
			$this->municipioname->CellAttrs["class"] = ($this->RowGroupLevel == 3) ? "ewRptGrpSummary3" : "ewRptGrpField3";
			$this->municipioname->GroupViewValue = ewr_DisplayGroupValue($this->municipioname, $this->municipioname->GroupViewValue);
			$this->municipioname->GroupSummaryOldValue = $this->municipioname->GroupSummaryValue;
			$this->municipioname->GroupSummaryValue = $this->municipioname->GroupViewValue;
			$this->municipioname->GroupSummaryViewValue = ($this->municipioname->GroupSummaryOldValue <> $this->municipioname->GroupSummaryValue) ? $this->municipioname->GroupSummaryValue : "&nbsp;";

			// provname
			$this->provname->GroupViewValue = $this->provname->GroupOldValue();
			$this->provname->CellAttrs["class"] = ($this->RowGroupLevel == 4) ? "ewRptGrpSummary4" : "ewRptGrpField4";
			$this->provname->GroupViewValue = ewr_DisplayGroupValue($this->provname, $this->provname->GroupViewValue);
			$this->provname->GroupSummaryOldValue = $this->provname->GroupSummaryValue;
			$this->provname->GroupSummaryValue = $this->provname->GroupViewValue;
			$this->provname->GroupSummaryViewValue = ($this->provname->GroupSummaryOldValue <> $this->provname->GroupSummaryValue) ? $this->provname->GroupSummaryValue : "&nbsp;";

			// unidadaname
			$this->unidadaname->GroupViewValue = $this->unidadaname->GroupOldValue();
			$this->unidadaname->CellAttrs["class"] = ($this->RowGroupLevel == 5) ? "ewRptGrpSummary5" : "ewRptGrpField5";
			$this->unidadaname->GroupViewValue = ewr_DisplayGroupValue($this->unidadaname, $this->unidadaname->GroupViewValue);
			$this->unidadaname->GroupSummaryOldValue = $this->unidadaname->GroupSummaryValue;
			$this->unidadaname->GroupSummaryValue = $this->unidadaname->GroupViewValue;
			$this->unidadaname->GroupSummaryViewValue = ($this->unidadaname->GroupSummaryOldValue <> $this->unidadaname->GroupSummaryValue) ? $this->unidadaname->GroupSummaryValue : "&nbsp;";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// departamentoname
			$this->departamentoname->HrefValue = "";
			if ($this->Export == "") {
				$drillurl = $this->departamentoname->DrillDownUrl;
				$drillurl = str_replace("=f0", "=" . ewr_Encrypt($this->GetDrillDownSQL($this->nombreinstitucion, "nombreinstitucion", $this->RowTotalType)), $drillurl);
				$this->departamentoname->LinkAttrs["title"] = ewr_JsEncode($GLOBALS["ReportLanguage"]->Phrase("ClickToDrillDown"));
				$this->departamentoname->LinkAttrs["class"] = "ewDrillLink";
				$this->departamentoname->LinkAttrs["onclick"] = ewr_DrillDownJs($drillurl, 'ReportEstudiantes_departamentoname', $GLOBALS["ReportLanguage"]->TablePhrase('viewestudiante', 'TblCaption'), $this->UseDrillDownPanel);
			}

			// municipioname
			$this->municipioname->HrefValue = "";

			// provname
			$this->provname->HrefValue = "";

			// unidadaname
			$this->unidadaname->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// nombres
			$this->nombres->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// curso
			$this->curso->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->HrefValue = "";

			// nombredisca
			$this->nombredisca->HrefValue = "";

			// nombretipodisca
			$this->nombretipodisca->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// codigorude
			$this->codigorude->HrefValue = "";

			// codigorude_es
			$this->codigorude_es->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			$this->RowAttrs["data-group"] = $this->nombreinstitucion->GroupValue(); // Set up group attribute
			if ($this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->departamentoname->GroupValue(); // Set up group attribute 2
			if ($this->RowGroupLevel >= 3) $this->RowAttrs["data-group-3"] = $this->municipioname->GroupValue(); // Set up group attribute 3
			if ($this->RowGroupLevel >= 4) $this->RowAttrs["data-group-4"] = $this->provname->GroupValue(); // Set up group attribute 4
			if ($this->RowGroupLevel >= 5) $this->RowAttrs["data-group-5"] = $this->unidadaname->GroupValue(); // Set up group attribute 5
			} else {
			$this->RowAttrs["data-group"] = $this->nombreinstitucion->GroupValue(); // Set up group attribute
			$this->RowAttrs["data-group-2"] = $this->departamentoname->GroupValue(); // Set up group attribute 2
			$this->RowAttrs["data-group-3"] = $this->municipioname->GroupValue(); // Set up group attribute 3
			$this->RowAttrs["data-group-4"] = $this->provname->GroupValue(); // Set up group attribute 4
			$this->RowAttrs["data-group-5"] = $this->unidadaname->GroupValue(); // Set up group attribute 5
			}

			// nombreinstitucion
			$this->nombreinstitucion->GroupViewValue = $this->nombreinstitucion->GroupValue();
			$this->nombreinstitucion->CellAttrs["class"] = "ewRptGrpField1";
			$this->nombreinstitucion->GroupViewValue = ewr_DisplayGroupValue($this->nombreinstitucion, $this->nombreinstitucion->GroupViewValue);
			if ($this->nombreinstitucion->GroupValue() == $this->nombreinstitucion->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->nombreinstitucion->GroupViewValue = "&nbsp;";

			// departamentoname
			$this->departamentoname->GroupViewValue = $this->departamentoname->GroupValue();
			$this->departamentoname->CellAttrs["class"] = "ewRptGrpField2";
			$this->departamentoname->GroupViewValue = ewr_DisplayGroupValue($this->departamentoname, $this->departamentoname->GroupViewValue);
			if ($this->departamentoname->GroupValue() == $this->departamentoname->GroupOldValue() && !$this->ChkLvlBreak(2))
				$this->departamentoname->GroupViewValue = "&nbsp;";

			// municipioname
			$this->municipioname->GroupViewValue = $this->municipioname->GroupValue();
			$this->municipioname->CellAttrs["class"] = "ewRptGrpField3";
			$this->municipioname->GroupViewValue = ewr_DisplayGroupValue($this->municipioname, $this->municipioname->GroupViewValue);
			if ($this->municipioname->GroupValue() == $this->municipioname->GroupOldValue() && !$this->ChkLvlBreak(3))
				$this->municipioname->GroupViewValue = "&nbsp;";

			// provname
			$this->provname->GroupViewValue = $this->provname->GroupValue();
			$this->provname->CellAttrs["class"] = "ewRptGrpField4";
			$this->provname->GroupViewValue = ewr_DisplayGroupValue($this->provname, $this->provname->GroupViewValue);
			if ($this->provname->GroupValue() == $this->provname->GroupOldValue() && !$this->ChkLvlBreak(4))
				$this->provname->GroupViewValue = "&nbsp;";

			// unidadaname
			$this->unidadaname->GroupViewValue = $this->unidadaname->GroupValue();
			$this->unidadaname->CellAttrs["class"] = "ewRptGrpField5";
			$this->unidadaname->GroupViewValue = ewr_DisplayGroupValue($this->unidadaname, $this->unidadaname->GroupViewValue);
			if ($this->unidadaname->GroupValue() == $this->unidadaname->GroupOldValue() && !$this->ChkLvlBreak(5))
				$this->unidadaname->GroupViewValue = "&nbsp;";

			// apellidopaterno
			$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
			$this->apellidopaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// apellidomaterno
			$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
			$this->apellidomaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombres
			$this->nombres->ViewValue = $this->nombres->CurrentValue;
			$this->nombres->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ci
			$this->ci->ViewValue = $this->ci->CurrentValue;
			$this->ci->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fechanacimiento
			$this->fechanacimiento->ViewValue = $this->fechanacimiento->CurrentValue;
			$this->fechanacimiento->ViewValue = ewr_FormatDateTime($this->fechanacimiento->ViewValue, 0);
			$this->fechanacimiento->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// sexo
			$this->sexo->ViewValue = $this->sexo->CurrentValue;
			$this->sexo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// curso
			$this->curso->ViewValue = $this->curso->CurrentValue;
			$this->curso->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nrodiscapacidad
			$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
			$this->nrodiscapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombredisca
			$this->nombredisca->ViewValue = $this->nombredisca->CurrentValue;
			$this->nombredisca->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombretipodisca
			$this->nombretipodisca->ViewValue = $this->nombretipodisca->CurrentValue;
			$this->nombretipodisca->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// codigorude
			$this->codigorude->ViewValue = $this->codigorude->CurrentValue;
			$this->codigorude->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// codigorude_es
			$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
			$this->codigorude_es->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// departamentoname
			$this->departamentoname->HrefValue = "";
			if ($this->Export == "") {
				$drillurl = $this->departamentoname->DrillDownUrl;
				$drillurl = str_replace("=f0", "=" . ewr_Encrypt($this->GetDrillDownSQL($this->nombreinstitucion, "nombreinstitucion", 0)), $drillurl);
				$this->departamentoname->LinkAttrs["title"] = ewr_JsEncode($ReportLanguage->Phrase("ClickToDrillDown"));
				$this->departamentoname->LinkAttrs["class"] = "ewDrillLink";
				$this->departamentoname->LinkAttrs["onclick"] = ewr_DrillDownJs($drillurl, 'ReportEstudiantes_departamentoname', $GLOBALS["ReportLanguage"]->TablePhrase('viewestudiante', 'TblCaption'), $this->UseDrillDownPanel);
			}

			// municipioname
			$this->municipioname->HrefValue = "";

			// provname
			$this->provname->HrefValue = "";

			// unidadaname
			$this->unidadaname->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// nombres
			$this->nombres->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// curso
			$this->curso->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->HrefValue = "";

			// nombredisca
			$this->nombredisca->HrefValue = "";

			// nombretipodisca
			$this->nombretipodisca->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// codigorude
			$this->codigorude->HrefValue = "";

			// codigorude_es
			$this->codigorude_es->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// nombreinstitucion
			$CurrentValue = $this->nombreinstitucion->GroupViewValue;
			$ViewValue = &$this->nombreinstitucion->GroupViewValue;
			$ViewAttrs = &$this->nombreinstitucion->ViewAttrs;
			$CellAttrs = &$this->nombreinstitucion->CellAttrs;
			$HrefValue = &$this->nombreinstitucion->HrefValue;
			$LinkAttrs = &$this->nombreinstitucion->LinkAttrs;
			$this->Cell_Rendered($this->nombreinstitucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// departamentoname
			$CurrentValue = $this->departamentoname->GroupViewValue;
			$ViewValue = &$this->departamentoname->GroupViewValue;
			$ViewAttrs = &$this->departamentoname->ViewAttrs;
			$CellAttrs = &$this->departamentoname->CellAttrs;
			$HrefValue = &$this->departamentoname->HrefValue;
			$LinkAttrs = &$this->departamentoname->LinkAttrs;
			$this->Cell_Rendered($this->departamentoname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// municipioname
			$CurrentValue = $this->municipioname->GroupViewValue;
			$ViewValue = &$this->municipioname->GroupViewValue;
			$ViewAttrs = &$this->municipioname->ViewAttrs;
			$CellAttrs = &$this->municipioname->CellAttrs;
			$HrefValue = &$this->municipioname->HrefValue;
			$LinkAttrs = &$this->municipioname->LinkAttrs;
			$this->Cell_Rendered($this->municipioname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// provname
			$CurrentValue = $this->provname->GroupViewValue;
			$ViewValue = &$this->provname->GroupViewValue;
			$ViewAttrs = &$this->provname->ViewAttrs;
			$CellAttrs = &$this->provname->CellAttrs;
			$HrefValue = &$this->provname->HrefValue;
			$LinkAttrs = &$this->provname->LinkAttrs;
			$this->Cell_Rendered($this->provname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// unidadaname
			$CurrentValue = $this->unidadaname->GroupViewValue;
			$ViewValue = &$this->unidadaname->GroupViewValue;
			$ViewAttrs = &$this->unidadaname->ViewAttrs;
			$CellAttrs = &$this->unidadaname->CellAttrs;
			$HrefValue = &$this->unidadaname->HrefValue;
			$LinkAttrs = &$this->unidadaname->LinkAttrs;
			$this->Cell_Rendered($this->unidadaname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// nombreinstitucion
			$CurrentValue = $this->nombreinstitucion->GroupValue();
			$ViewValue = &$this->nombreinstitucion->GroupViewValue;
			$ViewAttrs = &$this->nombreinstitucion->ViewAttrs;
			$CellAttrs = &$this->nombreinstitucion->CellAttrs;
			$HrefValue = &$this->nombreinstitucion->HrefValue;
			$LinkAttrs = &$this->nombreinstitucion->LinkAttrs;
			$this->Cell_Rendered($this->nombreinstitucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// departamentoname
			$CurrentValue = $this->departamentoname->GroupValue();
			$ViewValue = &$this->departamentoname->GroupViewValue;
			$ViewAttrs = &$this->departamentoname->ViewAttrs;
			$CellAttrs = &$this->departamentoname->CellAttrs;
			$HrefValue = &$this->departamentoname->HrefValue;
			$LinkAttrs = &$this->departamentoname->LinkAttrs;
			$this->Cell_Rendered($this->departamentoname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// municipioname
			$CurrentValue = $this->municipioname->GroupValue();
			$ViewValue = &$this->municipioname->GroupViewValue;
			$ViewAttrs = &$this->municipioname->ViewAttrs;
			$CellAttrs = &$this->municipioname->CellAttrs;
			$HrefValue = &$this->municipioname->HrefValue;
			$LinkAttrs = &$this->municipioname->LinkAttrs;
			$this->Cell_Rendered($this->municipioname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// provname
			$CurrentValue = $this->provname->GroupValue();
			$ViewValue = &$this->provname->GroupViewValue;
			$ViewAttrs = &$this->provname->ViewAttrs;
			$CellAttrs = &$this->provname->CellAttrs;
			$HrefValue = &$this->provname->HrefValue;
			$LinkAttrs = &$this->provname->LinkAttrs;
			$this->Cell_Rendered($this->provname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// unidadaname
			$CurrentValue = $this->unidadaname->GroupValue();
			$ViewValue = &$this->unidadaname->GroupViewValue;
			$ViewAttrs = &$this->unidadaname->ViewAttrs;
			$CellAttrs = &$this->unidadaname->CellAttrs;
			$HrefValue = &$this->unidadaname->HrefValue;
			$LinkAttrs = &$this->unidadaname->LinkAttrs;
			$this->Cell_Rendered($this->unidadaname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// apellidopaterno
			$CurrentValue = $this->apellidopaterno->CurrentValue;
			$ViewValue = &$this->apellidopaterno->ViewValue;
			$ViewAttrs = &$this->apellidopaterno->ViewAttrs;
			$CellAttrs = &$this->apellidopaterno->CellAttrs;
			$HrefValue = &$this->apellidopaterno->HrefValue;
			$LinkAttrs = &$this->apellidopaterno->LinkAttrs;
			$this->Cell_Rendered($this->apellidopaterno, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// apellidomaterno
			$CurrentValue = $this->apellidomaterno->CurrentValue;
			$ViewValue = &$this->apellidomaterno->ViewValue;
			$ViewAttrs = &$this->apellidomaterno->ViewAttrs;
			$CellAttrs = &$this->apellidomaterno->CellAttrs;
			$HrefValue = &$this->apellidomaterno->HrefValue;
			$LinkAttrs = &$this->apellidomaterno->LinkAttrs;
			$this->Cell_Rendered($this->apellidomaterno, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombres
			$CurrentValue = $this->nombres->CurrentValue;
			$ViewValue = &$this->nombres->ViewValue;
			$ViewAttrs = &$this->nombres->ViewAttrs;
			$CellAttrs = &$this->nombres->CellAttrs;
			$HrefValue = &$this->nombres->HrefValue;
			$LinkAttrs = &$this->nombres->LinkAttrs;
			$this->Cell_Rendered($this->nombres, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// ci
			$CurrentValue = $this->ci->CurrentValue;
			$ViewValue = &$this->ci->ViewValue;
			$ViewAttrs = &$this->ci->ViewAttrs;
			$CellAttrs = &$this->ci->CellAttrs;
			$HrefValue = &$this->ci->HrefValue;
			$LinkAttrs = &$this->ci->LinkAttrs;
			$this->Cell_Rendered($this->ci, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fechanacimiento
			$CurrentValue = $this->fechanacimiento->CurrentValue;
			$ViewValue = &$this->fechanacimiento->ViewValue;
			$ViewAttrs = &$this->fechanacimiento->ViewAttrs;
			$CellAttrs = &$this->fechanacimiento->CellAttrs;
			$HrefValue = &$this->fechanacimiento->HrefValue;
			$LinkAttrs = &$this->fechanacimiento->LinkAttrs;
			$this->Cell_Rendered($this->fechanacimiento, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// sexo
			$CurrentValue = $this->sexo->CurrentValue;
			$ViewValue = &$this->sexo->ViewValue;
			$ViewAttrs = &$this->sexo->ViewAttrs;
			$CellAttrs = &$this->sexo->CellAttrs;
			$HrefValue = &$this->sexo->HrefValue;
			$LinkAttrs = &$this->sexo->LinkAttrs;
			$this->Cell_Rendered($this->sexo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// curso
			$CurrentValue = $this->curso->CurrentValue;
			$ViewValue = &$this->curso->ViewValue;
			$ViewAttrs = &$this->curso->ViewAttrs;
			$CellAttrs = &$this->curso->CellAttrs;
			$HrefValue = &$this->curso->HrefValue;
			$LinkAttrs = &$this->curso->LinkAttrs;
			$this->Cell_Rendered($this->curso, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nrodiscapacidad
			$CurrentValue = $this->nrodiscapacidad->CurrentValue;
			$ViewValue = &$this->nrodiscapacidad->ViewValue;
			$ViewAttrs = &$this->nrodiscapacidad->ViewAttrs;
			$CellAttrs = &$this->nrodiscapacidad->CellAttrs;
			$HrefValue = &$this->nrodiscapacidad->HrefValue;
			$LinkAttrs = &$this->nrodiscapacidad->LinkAttrs;
			$this->Cell_Rendered($this->nrodiscapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombredisca
			$CurrentValue = $this->nombredisca->CurrentValue;
			$ViewValue = &$this->nombredisca->ViewValue;
			$ViewAttrs = &$this->nombredisca->ViewAttrs;
			$CellAttrs = &$this->nombredisca->CellAttrs;
			$HrefValue = &$this->nombredisca->HrefValue;
			$LinkAttrs = &$this->nombredisca->LinkAttrs;
			$this->Cell_Rendered($this->nombredisca, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombretipodisca
			$CurrentValue = $this->nombretipodisca->CurrentValue;
			$ViewValue = &$this->nombretipodisca->ViewValue;
			$ViewAttrs = &$this->nombretipodisca->ViewAttrs;
			$CellAttrs = &$this->nombretipodisca->CellAttrs;
			$HrefValue = &$this->nombretipodisca->HrefValue;
			$LinkAttrs = &$this->nombretipodisca->LinkAttrs;
			$this->Cell_Rendered($this->nombretipodisca, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// observaciones
			$CurrentValue = $this->observaciones->CurrentValue;
			$ViewValue = &$this->observaciones->ViewValue;
			$ViewAttrs = &$this->observaciones->ViewAttrs;
			$CellAttrs = &$this->observaciones->CellAttrs;
			$HrefValue = &$this->observaciones->HrefValue;
			$LinkAttrs = &$this->observaciones->LinkAttrs;
			$this->Cell_Rendered($this->observaciones, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// codigorude
			$CurrentValue = $this->codigorude->CurrentValue;
			$ViewValue = &$this->codigorude->ViewValue;
			$ViewAttrs = &$this->codigorude->ViewAttrs;
			$CellAttrs = &$this->codigorude->CellAttrs;
			$HrefValue = &$this->codigorude->HrefValue;
			$LinkAttrs = &$this->codigorude->LinkAttrs;
			$this->Cell_Rendered($this->codigorude, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// codigorude_es
			$CurrentValue = $this->codigorude_es->CurrentValue;
			$ViewValue = &$this->codigorude_es->ViewValue;
			$ViewAttrs = &$this->codigorude_es->ViewAttrs;
			$CellAttrs = &$this->codigorude_es->CellAttrs;
			$HrefValue = &$this->codigorude_es->HrefValue;
			$LinkAttrs = &$this->codigorude_es->LinkAttrs;
			$this->Cell_Rendered($this->codigorude_es, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->nombreinstitucion->Visible) $this->GrpColumnCount += 1;
		if ($this->departamentoname->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->municipioname->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->provname->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->unidadaname->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->apellidopaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidomaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->nombres->Visible) $this->DtlColumnCount += 1;
		if ($this->ci->Visible) $this->DtlColumnCount += 1;
		if ($this->fechanacimiento->Visible) $this->DtlColumnCount += 1;
		if ($this->sexo->Visible) $this->DtlColumnCount += 1;
		if ($this->curso->Visible) $this->DtlColumnCount += 1;
		if ($this->nrodiscapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->nombredisca->Visible) $this->DtlColumnCount += 1;
		if ($this->nombretipodisca->Visible) $this->DtlColumnCount += 1;
		if ($this->observaciones->Visible) $this->DtlColumnCount += 1;
		if ($this->codigorude->Visible) $this->DtlColumnCount += 1;
		if ($this->codigorude_es->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("summary", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Return drill down filter
	function GetDrillDownFilter() {
		global $ReportLanguage;
		$sFilterList = "";
		$filter = "";
		$post = $_POST;
		$opt = @$post["d"];
		if ($opt == "1" || $opt == "2") {
			$mastertable = @$post["s"]; // Get source table
			$sql = @$post["nombreinstitucion"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@nombreinstitucion", "`nombreinstitucion`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreinstitucion->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}

			// Save to session
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_" . EWR_TABLE_MASTER_TABLE] = $mastertable;
			$_SESSION['do_ReportEstudiantes'] = $opt;
			$_SESSION['df_ReportEstudiantes'] = $filter;
			$_SESSION['dl_ReportEstudiantes'] = $sFilterList;
		} elseif (@$_GET["cmd"] == "resetdrilldown") { // Clear drill down
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_" . EWR_TABLE_MASTER_TABLE] = "";
			$_SESSION['do_ReportEstudiantes'] = "";
			$_SESSION['df_ReportEstudiantes'] = "";
			$_SESSION['dl_ReportEstudiantes'] = "";
		} else { // Restore from Session
			$opt = @$_SESSION['do_ReportEstudiantes'];
			$filter = @$_SESSION['df_ReportEstudiantes'];
			$sFilterList = @$_SESSION['dl_ReportEstudiantes'];
		}
		if ($opt == "1" || $opt == "2")
			$this->DrillDown = TRUE;
		if ($opt == "1") {
			$this->DrillDownInPanel = TRUE;
			$GLOBALS["gbSkipHeaderFooter"] = TRUE;
		}
		if ($filter <> "") {
			if ($sFilterList == "")
				$sFilterList = "<div><span class=\"ewFilterValue\">" . $ReportLanguage->Phrase("DrillDownAllRecords") . "</span></div>";
			$this->DrillDownList = "<div id=\"ewrDrillDownFilters\">" . $ReportLanguage->Phrase("DrillDownFilters") . "</div>" . $sFilterList;
		}
		return $filter;
	}

	// Show drill down filters
	function ShowDrillDownList() {
		$divstyle = "";
		$divdataclass = "";
		if ($this->DrillDownList <> "") {
			$sMessage = "<div id=\"ewrDrillDownList\"" . $divstyle . "><div class=\"alert alert-info\"" . $divdataclass . ">" . $this->DrillDownList . "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Return drill down SQL
	// - fld = source field object
	// - target = target field name
	// - rowtype = row type
	//  * 0 = detail
	//  * 1 = group
	//  * 2 = page
	//  * 3 = grand
	// - parm = filter/column index
	//  * -1  = use field filter value / current/old value
	//  * 0   = use grouping/column field value
	//  * > 0 = use column index
	function GetDrillDownSQL($fld, $target, $rowtype, $parm = 0) {
		$sql = "";

		// Handle grand/page total
		if ($fld->FldVar == "x_nombreinstitucion") { // First grouping field
			if ($rowtype == EWR_ROWTOTAL_GRAND) { // Grand total
				$sql = $fld->CurrentFilter;
				if ($sql == "")
					$sql = "1=1"; // Show all records
			} elseif ($rowtype == EWR_ROWTOTAL_PAGE && $this->PageFirstGroupFilter <> "") { // Page total
				$sql = str_replace($fld->FldExpression, "@" . $target, "(" . $this->PageFirstGroupFilter . ")");
			}
		}

		// Handle group/row/column field
		if ($parm >= 0 && $sql == "") {
			switch (substr($fld->FldVar,2)) {
			case "nombreinstitucion":
				if ($fld->FldGroupSql <> "") {
					$sql = str_replace("%s", "@" . $target, $fld->FldGroupSql) . " = " . ewr_QuotedValue(($rowtype == 0) ? $fld->CurrentValue : $fld->OldValue, EWR_DATATYPE_STRING, $this->DBID);
					ewr_AddFilter($sql, str_replace($fld->FldExpression, "@" . $target, $fld->CurrentFilter));
				} else {
					$sql = "@" . $target . " = " . ewr_QuotedValue(($rowtype == 0) ? $fld->CurrentValue : $fld->OldValue, $fld->FldDataType, $this->DBID);
				}
				break;
			}
		}

		// Detail field
		if ($sql == "" && $rowtype == 0)
			if ($fld->CurrentFilter <> "") // Use current filter
				$sql = str_replace($fld->FldExpression, "@" . $target, $fld->CurrentFilter);
			elseif ($fld->CurrentValue <> "") // Use current value for detail row
				$sql = "@" . $target . "=" . ewr_QuotedValue($fld->CurrentValue, $fld->FldDataType, $this->DBID);
		return $sql;
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
			$this->departamentoname->setSort("");
			$this->municipioname->setSort("");
			$this->provname->setSort("");
			$this->unidadaname->setSort("");
			$this->apellidopaterno->setSort("");
			$this->apellidomaterno->setSort("");
			$this->nombres->setSort("");
			$this->ci->setSort("");
			$this->fechanacimiento->setSort("");
			$this->sexo->setSort("");
			$this->curso->setSort("");
			$this->nrodiscapacidad->setSort("");
			$this->nombredisca->setSort("");
			$this->nombretipodisca->setSort("");
			$this->observaciones->setSort("");
			$this->codigorude->setSort("");
			$this->codigorude_es->setSort("");

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
if (!isset($ReportEstudiantes_summary)) $ReportEstudiantes_summary = new crReportEstudiantes_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$ReportEstudiantes_summary;

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
var ReportEstudiantes_summary = new ewr_Page("ReportEstudiantes_summary");

// Page properties
ReportEstudiantes_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = ReportEstudiantes_summary.PageID;
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
<?php if ($Page->ShowDrillDownFilter) { ?>
<?php $Page->ShowDrillDownList() ?>
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
	$Page->GetGrpRow(1);
	$Page->GrpCounter[0] = 1;
	$Page->GrpCounter[1] = 1;
	$Page->GrpCounter[2] = 1;
	$Page->GrpCounter[3] = 1;
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray($Page->StopGrp - $Page->StartGrp + 1, -1);
while ($rsgrp && !$rsgrp->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->GrpCount > 1) { ?>
</tbody>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "ReportEstudiantessmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<span data-class="tpb<?php echo $Page->GrpCount-1 ?>_ReportEstudiantes"><?php echo $Page->PageBreakContent ?></span>
<?php } ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_ReportEstudiantes" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->nombreinstitucion->Visible) { ?>
	<?php if ($Page->nombreinstitucion->ShowGroupHeaderAsRow) { ?>
	<td data-field="nombreinstitucion">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="ReportEstudiantes_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->departamentoname->Visible) { ?>
	<?php if ($Page->departamentoname->ShowGroupHeaderAsRow) { ?>
	<td data-field="departamentoname">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="departamentoname"><div class="ReportEstudiantes_departamentoname"><span class="ewTableHeaderCaption"><?php echo $Page->departamentoname->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="departamentoname">
<?php if ($Page->SortUrl($Page->departamentoname) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_departamentoname">
			<span class="ewTableHeaderCaption"><?php echo $Page->departamentoname->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_departamentoname" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->departamentoname) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->departamentoname->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->departamentoname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->departamentoname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->municipioname->Visible) { ?>
	<?php if ($Page->municipioname->ShowGroupHeaderAsRow) { ?>
	<td data-field="municipioname">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="municipioname"><div class="ReportEstudiantes_municipioname"><span class="ewTableHeaderCaption"><?php echo $Page->municipioname->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="municipioname">
<?php if ($Page->SortUrl($Page->municipioname) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_municipioname">
			<span class="ewTableHeaderCaption"><?php echo $Page->municipioname->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_municipioname" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->municipioname) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->municipioname->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->municipioname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->municipioname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->provname->Visible) { ?>
	<?php if ($Page->provname->ShowGroupHeaderAsRow) { ?>
	<td data-field="provname">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="provname"><div class="ReportEstudiantes_provname"><span class="ewTableHeaderCaption"><?php echo $Page->provname->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="provname">
<?php if ($Page->SortUrl($Page->provname) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_provname">
			<span class="ewTableHeaderCaption"><?php echo $Page->provname->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_provname" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->provname) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->provname->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->provname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->provname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->unidadaname->Visible) { ?>
	<?php if ($Page->unidadaname->ShowGroupHeaderAsRow) { ?>
	<td data-field="unidadaname">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="unidadaname"><div class="ReportEstudiantes_unidadaname"><span class="ewTableHeaderCaption"><?php echo $Page->unidadaname->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="unidadaname">
<?php if ($Page->SortUrl($Page->unidadaname) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_unidadaname">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadaname->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_unidadaname" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->unidadaname) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadaname->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->unidadaname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->unidadaname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidopaterno"><div class="ReportEstudiantes_apellidopaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidopaterno">
<?php if ($Page->SortUrl($Page->apellidopaterno) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_apellidopaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_apellidopaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidopaterno) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidomaterno"><div class="ReportEstudiantes_apellidomaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidomaterno">
<?php if ($Page->SortUrl($Page->apellidomaterno) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_apellidomaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_apellidomaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidomaterno) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombres->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombres"><div class="ReportEstudiantes_nombres"><span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombres">
<?php if ($Page->SortUrl($Page->nombres) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_nombres">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_nombres" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombres) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ci"><div class="ReportEstudiantes_ci"><span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ci">
<?php if ($Page->SortUrl($Page->ci) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_ci">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_ci" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ci) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fechanacimiento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fechanacimiento"><div class="ReportEstudiantes_fechanacimiento"><span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fechanacimiento">
<?php if ($Page->SortUrl($Page->fechanacimiento) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_fechanacimiento">
			<span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_fechanacimiento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fechanacimiento) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fechanacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fechanacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sexo"><div class="ReportEstudiantes_sexo"><span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sexo">
<?php if ($Page->SortUrl($Page->sexo) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_sexo">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_sexo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sexo) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->curso->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="curso"><div class="ReportEstudiantes_curso"><span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="curso">
<?php if ($Page->SortUrl($Page->curso) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_curso">
			<span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_curso" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->curso) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->curso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->curso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nrodiscapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nrodiscapacidad"><div class="ReportEstudiantes_nrodiscapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nrodiscapacidad">
<?php if ($Page->SortUrl($Page->nrodiscapacidad) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_nrodiscapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_nrodiscapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nrodiscapacidad) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombredisca->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombredisca"><div class="ReportEstudiantes_nombredisca"><span class="ewTableHeaderCaption"><?php echo $Page->nombredisca->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombredisca">
<?php if ($Page->SortUrl($Page->nombredisca) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_nombredisca">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombredisca->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_nombredisca" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombredisca) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombredisca->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombredisca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombredisca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombretipodisca->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombretipodisca"><div class="ReportEstudiantes_nombretipodisca"><span class="ewTableHeaderCaption"><?php echo $Page->nombretipodisca->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombretipodisca">
<?php if ($Page->SortUrl($Page->nombretipodisca) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_nombretipodisca">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombretipodisca->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_nombretipodisca" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombretipodisca) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombretipodisca->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombretipodisca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombretipodisca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="observaciones"><div class="ReportEstudiantes_observaciones"><span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="observaciones">
<?php if ($Page->SortUrl($Page->observaciones) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_observaciones">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_observaciones" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->observaciones) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->codigorude->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="codigorude"><div class="ReportEstudiantes_codigorude"><span class="ewTableHeaderCaption"><?php echo $Page->codigorude->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="codigorude">
<?php if ($Page->SortUrl($Page->codigorude) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_codigorude">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_codigorude" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->codigorude) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->codigorude->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->codigorude->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->codigorude_es->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="codigorude_es"><div class="ReportEstudiantes_codigorude_es"><span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="codigorude_es">
<?php if ($Page->SortUrl($Page->codigorude_es) == "") { ?>
		<div class="ewTableHeaderBtn ReportEstudiantes_codigorude_es">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer ReportEstudiantes_codigorude_es" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->codigorude_es) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->codigorude_es->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->codigorude_es->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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

	// Build detail SQL
	$sWhere = ewr_DetailFilterSql($Page->nombreinstitucion, $Page->getSqlFirstGroupField(), $Page->nombreinstitucion->GroupValue(), $Page->DBID);
	if ($Page->PageFirstGroupFilter <> "") $Page->PageFirstGroupFilter .= " OR ";
	$Page->PageFirstGroupFilter .= $sWhere;
	if ($Page->Filter != "")
		$sWhere = "($Page->Filter) AND ($sWhere)";
	$sSql = ewr_BuildReportSql($Page->getSqlSelect(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $sWhere, $Page->Sort);
	$rs = $Page->GetDetailRs($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Page->GetRow(1);
	$Page->GrpIdx[$Page->GrpCount] = array(-1);
	$Page->GrpIdx[$Page->GrpCount][] = array(-1);
	$Page->GrpIdx[$Page->GrpCount][][] = array(-1);
	$Page->GrpIdx[$Page->GrpCount][][][] = array(-1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Page->RecCount++;
		$Page->RecIndex++;
?>
<?php if ($Page->nombreinstitucion->Visible && $Page->ChkLvlBreak(1) && $Page->nombreinstitucion->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 1;
		$Page->nombreinstitucion->Count = $Page->GetSummaryCount(1);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="nombreinstitucion" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 1) ?>"<?php echo $Page->nombreinstitucion->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption ReportEstudiantes_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<span class="ewSummaryCaption ReportEstudiantes_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption ReportEstudiantes_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_ReportEstudiantes_nombreinstitucion"<?php echo $Page->nombreinstitucion->ViewAttributes() ?>><?php echo $Page->nombreinstitucion->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->nombreinstitucion->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->departamentoname->Visible && $Page->ChkLvlBreak(2) && $Page->departamentoname->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 2;
		$Page->departamentoname->Count = $Page->GetSummaryCount(2);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->departamentoname->Visible) { ?>
		<td data-field="departamentoname"<?php echo $Page->departamentoname->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="departamentoname" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 2) ?>"<?php echo $Page->departamentoname->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption ReportEstudiantes_departamentoname"><span class="ewTableHeaderCaption"><?php echo $Page->departamentoname->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->departamentoname) == "") { ?>
		<span class="ewSummaryCaption ReportEstudiantes_departamentoname">
			<span class="ewTableHeaderCaption"><?php echo $Page->departamentoname->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption ReportEstudiantes_departamentoname" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->departamentoname) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->departamentoname->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->departamentoname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->departamentoname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_ReportEstudiantes_departamentoname"<?php echo $Page->departamentoname->ViewAttributes() ?>>
<?php if ($Page->departamentoname->HrefValue <> "" || @$Page->departamentoname->LinkAttrs["onclick"] <> "") { ?>
<?php if ($Page->departamentoname->GroupViewValue <> "" && $Page->departamentoname->GroupViewValue <> "&nbsp;") { ?>
<a<?php echo $Page->departamentoname->LinkAttributes() ?>><?php echo $Page->departamentoname->GroupViewValue ?></a>
<?php } else { echo "&nbsp;"; } ?>
<?php } else { ?>
<?php if ($Page->departamentoname->GroupViewValue <> "" && $Page->departamentoname->GroupViewValue <> "&nbsp;") { ?>
<?php echo $Page->departamentoname->GroupViewValue ?>
<?php } else { echo "&nbsp;"; } ?>
<?php } ?>
</span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->departamentoname->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->municipioname->Visible && $Page->ChkLvlBreak(3) && $Page->municipioname->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 3;
		$Page->municipioname->Count = $Page->GetSummaryCount(3);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->departamentoname->Visible) { ?>
		<td data-field="departamentoname"<?php echo $Page->departamentoname->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->municipioname->Visible) { ?>
		<td data-field="municipioname"<?php echo $Page->municipioname->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="municipioname" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 3) ?>"<?php echo $Page->municipioname->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption ReportEstudiantes_municipioname"><span class="ewTableHeaderCaption"><?php echo $Page->municipioname->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->municipioname) == "") { ?>
		<span class="ewSummaryCaption ReportEstudiantes_municipioname">
			<span class="ewTableHeaderCaption"><?php echo $Page->municipioname->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption ReportEstudiantes_municipioname" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->municipioname) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->municipioname->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->municipioname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->municipioname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_ReportEstudiantes_municipioname"<?php echo $Page->municipioname->ViewAttributes() ?>><?php echo $Page->municipioname->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->municipioname->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->provname->Visible && $Page->ChkLvlBreak(4) && $Page->provname->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 4;
		$Page->provname->Count = $Page->GetSummaryCount(4);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->departamentoname->Visible) { ?>
		<td data-field="departamentoname"<?php echo $Page->departamentoname->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->municipioname->Visible) { ?>
		<td data-field="municipioname"<?php echo $Page->municipioname->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->provname->Visible) { ?>
		<td data-field="provname"<?php echo $Page->provname->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="provname" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 4) ?>"<?php echo $Page->provname->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption ReportEstudiantes_provname"><span class="ewTableHeaderCaption"><?php echo $Page->provname->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->provname) == "") { ?>
		<span class="ewSummaryCaption ReportEstudiantes_provname">
			<span class="ewTableHeaderCaption"><?php echo $Page->provname->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption ReportEstudiantes_provname" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->provname) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->provname->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->provname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->provname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->GrpCounter[2] ?>_ReportEstudiantes_provname"<?php echo $Page->provname->ViewAttributes() ?>><?php echo $Page->provname->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->provname->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->unidadaname->Visible && $Page->ChkLvlBreak(5) && $Page->unidadaname->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 5;
		$Page->unidadaname->Count = $Page->GetSummaryCount(5);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->departamentoname->Visible) { ?>
		<td data-field="departamentoname"<?php echo $Page->departamentoname->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->municipioname->Visible) { ?>
		<td data-field="municipioname"<?php echo $Page->municipioname->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->provname->Visible) { ?>
		<td data-field="provname"<?php echo $Page->provname->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->unidadaname->Visible) { ?>
		<td data-field="unidadaname"<?php echo $Page->unidadaname->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="unidadaname" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 5) ?>"<?php echo $Page->unidadaname->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption ReportEstudiantes_unidadaname"><span class="ewTableHeaderCaption"><?php echo $Page->unidadaname->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->unidadaname) == "") { ?>
		<span class="ewSummaryCaption ReportEstudiantes_unidadaname">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadaname->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption ReportEstudiantes_unidadaname" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->unidadaname) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadaname->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->unidadaname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->unidadaname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->GrpCounter[2] ?>_<?php echo $Page->GrpCounter[3] ?>_ReportEstudiantes_unidadaname"<?php echo $Page->unidadaname->ViewAttributes() ?>><?php echo $Page->unidadaname->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->unidadaname->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->nombreinstitucion->Visible) { ?>
	<?php if ($Page->nombreinstitucion->ShowGroupHeaderAsRow) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_ReportEstudiantes_nombreinstitucion"<?php echo $Page->nombreinstitucion->ViewAttributes() ?>><?php echo $Page->nombreinstitucion->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->departamentoname->Visible) { ?>
	<?php if ($Page->departamentoname->ShowGroupHeaderAsRow) { ?>
		<td data-field="departamentoname"<?php echo $Page->departamentoname->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="departamentoname"<?php echo $Page->departamentoname->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_ReportEstudiantes_departamentoname"<?php echo $Page->departamentoname->ViewAttributes() ?>>
<?php if ($Page->departamentoname->HrefValue <> "" || @$Page->departamentoname->LinkAttrs["onclick"] <> "") { ?>
<?php if ($Page->departamentoname->GroupViewValue <> "" && $Page->departamentoname->GroupViewValue <> "&nbsp;") { ?>
<a<?php echo $Page->departamentoname->LinkAttributes() ?>><?php echo $Page->departamentoname->GroupViewValue ?></a>
<?php } else { echo "&nbsp;"; } ?>
<?php } else { ?>
<?php if ($Page->departamentoname->GroupViewValue <> "" && $Page->departamentoname->GroupViewValue <> "&nbsp;") { ?>
<?php echo $Page->departamentoname->GroupViewValue ?>
<?php } else { echo "&nbsp;"; } ?>
<?php } ?>
</span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->municipioname->Visible) { ?>
	<?php if ($Page->municipioname->ShowGroupHeaderAsRow) { ?>
		<td data-field="municipioname"<?php echo $Page->municipioname->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="municipioname"<?php echo $Page->municipioname->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_ReportEstudiantes_municipioname"<?php echo $Page->municipioname->ViewAttributes() ?>><?php echo $Page->municipioname->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->provname->Visible) { ?>
	<?php if ($Page->provname->ShowGroupHeaderAsRow) { ?>
		<td data-field="provname"<?php echo $Page->provname->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="provname"<?php echo $Page->provname->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->GrpCounter[2] ?>_ReportEstudiantes_provname"<?php echo $Page->provname->ViewAttributes() ?>><?php echo $Page->provname->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->unidadaname->Visible) { ?>
	<?php if ($Page->unidadaname->ShowGroupHeaderAsRow) { ?>
		<td data-field="unidadaname"<?php echo $Page->unidadaname->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="unidadaname"<?php echo $Page->unidadaname->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->GrpCounter[2] ?>_<?php echo $Page->GrpCounter[3] ?>_ReportEstudiantes_unidadaname"<?php echo $Page->unidadaname->ViewAttributes() ?>><?php echo $Page->unidadaname->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
		<td data-field="apellidopaterno"<?php echo $Page->apellidopaterno->CellAttributes() ?>>
<span<?php echo $Page->apellidopaterno->ViewAttributes() ?>><?php echo $Page->apellidopaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
		<td data-field="apellidomaterno"<?php echo $Page->apellidomaterno->CellAttributes() ?>>
<span<?php echo $Page->apellidomaterno->ViewAttributes() ?>><?php echo $Page->apellidomaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombres->Visible) { ?>
		<td data-field="nombres"<?php echo $Page->nombres->CellAttributes() ?>>
<span<?php echo $Page->nombres->ViewAttributes() ?>><?php echo $Page->nombres->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
		<td data-field="ci"<?php echo $Page->ci->CellAttributes() ?>>
<span<?php echo $Page->ci->ViewAttributes() ?>><?php echo $Page->ci->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fechanacimiento->Visible) { ?>
		<td data-field="fechanacimiento"<?php echo $Page->fechanacimiento->CellAttributes() ?>>
<span<?php echo $Page->fechanacimiento->ViewAttributes() ?>><?php echo $Page->fechanacimiento->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
		<td data-field="sexo"<?php echo $Page->sexo->CellAttributes() ?>>
<span<?php echo $Page->sexo->ViewAttributes() ?>><?php echo $Page->sexo->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->curso->Visible) { ?>
		<td data-field="curso"<?php echo $Page->curso->CellAttributes() ?>>
<span<?php echo $Page->curso->ViewAttributes() ?>><?php echo $Page->curso->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nrodiscapacidad->Visible) { ?>
		<td data-field="nrodiscapacidad"<?php echo $Page->nrodiscapacidad->CellAttributes() ?>>
<span<?php echo $Page->nrodiscapacidad->ViewAttributes() ?>><?php echo $Page->nrodiscapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombredisca->Visible) { ?>
		<td data-field="nombredisca"<?php echo $Page->nombredisca->CellAttributes() ?>>
<span<?php echo $Page->nombredisca->ViewAttributes() ?>><?php echo $Page->nombredisca->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombretipodisca->Visible) { ?>
		<td data-field="nombretipodisca"<?php echo $Page->nombretipodisca->CellAttributes() ?>>
<span<?php echo $Page->nombretipodisca->ViewAttributes() ?>><?php echo $Page->nombretipodisca->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
		<td data-field="observaciones"<?php echo $Page->observaciones->CellAttributes() ?>>
<span<?php echo $Page->observaciones->ViewAttributes() ?>><?php echo $Page->observaciones->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->codigorude->Visible) { ?>
		<td data-field="codigorude"<?php echo $Page->codigorude->CellAttributes() ?>>
<span<?php echo $Page->codigorude->ViewAttributes() ?>><?php echo $Page->codigorude->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->codigorude_es->Visible) { ?>
		<td data-field="codigorude_es"<?php echo $Page->codigorude_es->CellAttributes() ?>>
<span<?php echo $Page->codigorude_es->ViewAttributes() ?>><?php echo $Page->codigorude_es->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);

		// Show Footers
?>
<?php
	} // End detail records loop
?>
<?php

	// Next group
	$Page->GetGrpRow(2);

	// Show header if page break
	if ($Page->Export <> "")
		$Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? FALSE : ($Page->GrpCount % $Page->ExportPageBreakCount == 0);

	// Page_Breaking server event
	if ($Page->ShowHeader)
		$Page->Page_Breaking($Page->ShowHeader, $Page->PageBreakContent);
	$Page->GrpCount++;
	$Page->GrpCounter[3] = 1;
	$Page->GrpCounter[2] = 1;
	$Page->GrpCounter[1] = 1;
	$Page->GrpCounter[0] = 1;

	// Handle EOF
	if (!$rsgrp || $rsgrp->EOF)
		$Page->ShowHeader = FALSE;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
<?php if ($Page->unidadaname->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2) ?></span>)</td></tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
<?php } ?>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && FALSE) { // No header displayed ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_ReportEstudiantes" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "ReportEstudiantessmrypager.php" ?>
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
