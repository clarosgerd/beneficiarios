<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewsalsudapoderadorptinfo.php" ?>
<?php

//
// Page class
//

$viewsalsudapoderado_rpt = NULL; // Initialize page object first

class crviewsalsudapoderado_rpt extends crviewsalsudapoderado {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewsalsudapoderado_rpt';

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

		// Table object (viewsalsudapoderado)
		if (!isset($GLOBALS["viewsalsudapoderado"])) {
			$GLOBALS["viewsalsudapoderado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewsalsudapoderado"];
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
			define("EWR_TABLE_NAME", 'viewsalsudapoderado', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewsalsudapoderadorpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewsalsudapoderado');
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
		$this->nombreinstitucion->PlaceHolder = $this->nombreinstitucion->FldCaption();
		$this->ci->PlaceHolder = $this->ci->FldCaption();
		$this->sexo->PlaceHolder = $this->sexo->FldCaption();
		$this->fechanacimiento->PlaceHolder = $this->fechanacimiento->FldCaption();
		$this->ocupacion->PlaceHolder = $this->ocupacion->FldCaption();

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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewsalsudapoderado\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewsalsudapoderado',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewsalsudapoderadorpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewsalsudapoderadorpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewsalsudapoderadorpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->nombreinstitucion->SetVisibility();
		$this->parentesco->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombres->SetVisibility();
		$this->ci->SetVisibility();
		$this->sexo->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->direccion->SetVisibility();
		$this->celular->SetVisibility();
		$this->ocupacion->SetVisibility();
		$this->observaciones->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 13;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->nombreinstitucion->SelectionList = "";
		$this->nombreinstitucion->DefaultSelectionList = "";
		$this->nombreinstitucion->ValueList = "";
		$this->parentesco->SelectionList = "";
		$this->parentesco->DefaultSelectionList = "";
		$this->parentesco->ValueList = "";
		$this->ci->SelectionList = "";
		$this->ci->DefaultSelectionList = "";
		$this->ci->ValueList = "";
		$this->sexo->SelectionList = "";
		$this->sexo->DefaultSelectionList = "";
		$this->sexo->ValueList = "";
		$this->fechanacimiento->SelectionList = "";
		$this->fechanacimiento->DefaultSelectionList = "";
		$this->fechanacimiento->ValueList = "";
		$this->ocupacion->SelectionList = "";
		$this->ocupacion->DefaultSelectionList = "";
		$this->ocupacion->ValueList = "";

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
				$this->FirstRowData['nombreinstitucion'] = ewr_Conv($rs->fields('nombreinstitucion'), 200);
				$this->FirstRowData['parentesco'] = ewr_Conv($rs->fields('parentesco'), 200);
				$this->FirstRowData['apellidopaterno'] = ewr_Conv($rs->fields('apellidopaterno'), 200);
				$this->FirstRowData['apellidomaterno'] = ewr_Conv($rs->fields('apellidomaterno'), 200);
				$this->FirstRowData['nombres'] = ewr_Conv($rs->fields('nombres'), 200);
				$this->FirstRowData['ci'] = ewr_Conv($rs->fields('ci'), 200);
				$this->FirstRowData['sexo'] = ewr_Conv($rs->fields('sexo'), 3);
				$this->FirstRowData['fechanacimiento'] = ewr_Conv($rs->fields('fechanacimiento'), 133);
				$this->FirstRowData['direccion'] = ewr_Conv($rs->fields('direccion'), 200);
				$this->FirstRowData['celular'] = ewr_Conv($rs->fields('celular'), 3);
				$this->FirstRowData['ocupacion'] = ewr_Conv($rs->fields('ocupacion'), 200);
				$this->FirstRowData['observaciones'] = ewr_Conv($rs->fields('observaciones'), 200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->nombreinstitucion->setDbValue($rs->fields('nombreinstitucion'));
			$this->parentesco->setDbValue($rs->fields('parentesco'));
			$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
			$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
			$this->nombres->setDbValue($rs->fields('nombres'));
			$this->ci->setDbValue($rs->fields('ci'));
			$this->sexo->setDbValue($rs->fields('sexo'));
			$this->fechanacimiento->setDbValue($rs->fields('fechanacimiento'));
			$this->direccion->setDbValue($rs->fields('direccion'));
			$this->celular->setDbValue($rs->fields('celular'));
			$this->ocupacion->setDbValue($rs->fields('ocupacion'));
			$this->observaciones->setDbValue($rs->fields('observaciones'));
			$this->Val[1] = $this->nombreinstitucion->CurrentValue;
			$this->Val[2] = $this->parentesco->CurrentValue;
			$this->Val[3] = $this->apellidopaterno->CurrentValue;
			$this->Val[4] = $this->apellidomaterno->CurrentValue;
			$this->Val[5] = $this->nombres->CurrentValue;
			$this->Val[6] = $this->ci->CurrentValue;
			$this->Val[7] = $this->sexo->CurrentValue;
			$this->Val[8] = $this->fechanacimiento->CurrentValue;
			$this->Val[9] = $this->direccion->CurrentValue;
			$this->Val[10] = $this->celular->CurrentValue;
			$this->Val[11] = $this->ocupacion->CurrentValue;
			$this->Val[12] = $this->observaciones->CurrentValue;
		} else {
			$this->nombreinstitucion->setDbValue("");
			$this->parentesco->setDbValue("");
			$this->apellidopaterno->setDbValue("");
			$this->apellidomaterno->setDbValue("");
			$this->nombres->setDbValue("");
			$this->ci->setDbValue("");
			$this->sexo->setDbValue("");
			$this->fechanacimiento->setDbValue("");
			$this->direccion->setDbValue("");
			$this->celular->setDbValue("");
			$this->ocupacion->setDbValue("");
			$this->observaciones->setDbValue("");
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
			// Build distinct values for nombreinstitucion

			if ($popupname == 'viewsalsudapoderado_nombreinstitucion') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreinstitucion, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nombreinstitucion->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nombreinstitucion->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nombreinstitucion->setDbValue($rswrk->fields[0]);
					$this->nombreinstitucion->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nombreinstitucion->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nombreinstitucion->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nombreinstitucion->ValueList, $this->nombreinstitucion->CurrentValue, $this->nombreinstitucion->ViewValue, FALSE, $this->nombreinstitucion->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nombreinstitucion->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nombreinstitucion->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nombreinstitucion;
			}

			// Build distinct values for parentesco
			if ($popupname == 'viewsalsudapoderado_parentesco') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->parentesco, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->parentesco->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->parentesco->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->parentesco->setDbValue($rswrk->fields[0]);
					$this->parentesco->ViewValue = @$rswrk->fields[1];
					if (is_null($this->parentesco->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->parentesco->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->parentesco->ValueList, $this->parentesco->CurrentValue, $this->parentesco->ViewValue, FALSE, $this->parentesco->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->parentesco->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->parentesco->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->parentesco;
			}

			// Build distinct values for ci
			if ($popupname == 'viewsalsudapoderado_ci') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->ci, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->ci->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->ci->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->ci->setDbValue($rswrk->fields[0]);
					$this->ci->ViewValue = @$rswrk->fields[1];
					if (is_null($this->ci->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->ci->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->ci->ValueList, $this->ci->CurrentValue, $this->ci->ViewValue, FALSE, $this->ci->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->ci->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->ci->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->ci;
			}

			// Build distinct values for sexo
			if ($popupname == 'viewsalsudapoderado_sexo') {
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

			// Build distinct values for fechanacimiento
			if ($popupname == 'viewsalsudapoderado_fechanacimiento') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->fechanacimiento, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->fechanacimiento->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->fechanacimiento->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->fechanacimiento->setDbValue($rswrk->fields[0]);
					$this->fechanacimiento->ViewValue = @$rswrk->fields[1];
					if (is_null($this->fechanacimiento->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->fechanacimiento->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->fechanacimiento->ValueList, $this->fechanacimiento->CurrentValue, $this->fechanacimiento->ViewValue, FALSE, $this->fechanacimiento->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->fechanacimiento->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->fechanacimiento->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->fechanacimiento;
			}

			// Build distinct values for ocupacion
			if ($popupname == 'viewsalsudapoderado_ocupacion') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->ocupacion, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->ocupacion->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->ocupacion->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->ocupacion->setDbValue($rswrk->fields[0]);
					$this->ocupacion->ViewValue = @$rswrk->fields[1];
					if (is_null($this->ocupacion->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->ocupacion->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->ocupacion->ValueList, $this->ocupacion->CurrentValue, $this->ocupacion->ViewValue, FALSE, $this->ocupacion->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->ocupacion->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->ocupacion->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->ocupacion;
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
				$this->ClearSessionSelection('nombreinstitucion');
				$this->ClearSessionSelection('parentesco');
				$this->ClearSessionSelection('ci');
				$this->ClearSessionSelection('sexo');
				$this->ClearSessionSelection('fechanacimiento');
				$this->ClearSessionSelection('ocupacion');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get nombreinstitucion selected values

		if (is_array(@$_SESSION["sel_viewsalsudapoderado_nombreinstitucion"])) {
			$this->LoadSelectionFromSession('nombreinstitucion');
		} elseif (@$_SESSION["sel_viewsalsudapoderado_nombreinstitucion"] == EWR_INIT_VALUE) { // Select all
			$this->nombreinstitucion->SelectionList = "";
		}

		// Get parentesco selected values
		if (is_array(@$_SESSION["sel_viewsalsudapoderado_parentesco"])) {
			$this->LoadSelectionFromSession('parentesco');
		} elseif (@$_SESSION["sel_viewsalsudapoderado_parentesco"] == EWR_INIT_VALUE) { // Select all
			$this->parentesco->SelectionList = "";
		}

		// Get ci selected values
		if (is_array(@$_SESSION["sel_viewsalsudapoderado_ci"])) {
			$this->LoadSelectionFromSession('ci');
		} elseif (@$_SESSION["sel_viewsalsudapoderado_ci"] == EWR_INIT_VALUE) { // Select all
			$this->ci->SelectionList = "";
		}

		// Get sexo selected values
		if (is_array(@$_SESSION["sel_viewsalsudapoderado_sexo"])) {
			$this->LoadSelectionFromSession('sexo');
		} elseif (@$_SESSION["sel_viewsalsudapoderado_sexo"] == EWR_INIT_VALUE) { // Select all
			$this->sexo->SelectionList = "";
		}

		// Get fechanacimiento selected values
		if (is_array(@$_SESSION["sel_viewsalsudapoderado_fechanacimiento"])) {
			$this->LoadSelectionFromSession('fechanacimiento');
		} elseif (@$_SESSION["sel_viewsalsudapoderado_fechanacimiento"] == EWR_INIT_VALUE) { // Select all
			$this->fechanacimiento->SelectionList = "";
		}

		// Get ocupacion selected values
		if (is_array(@$_SESSION["sel_viewsalsudapoderado_ocupacion"])) {
			$this->LoadSelectionFromSession('ocupacion');
		} elseif (@$_SESSION["sel_viewsalsudapoderado_ocupacion"] == EWR_INIT_VALUE) { // Select all
			$this->ocupacion->SelectionList = "";
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

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// parentesco
			$this->parentesco->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// nombres
			$this->nombres->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// celular
			$this->celular->HrefValue = "";

			// ocupacion
			$this->ocupacion->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// nombreinstitucion
			$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
			$this->nombreinstitucion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// parentesco
			$this->parentesco->ViewValue = $this->parentesco->CurrentValue;
			$this->parentesco->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

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

			// sexo
			$this->sexo->ViewValue = $this->sexo->CurrentValue;
			$this->sexo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fechanacimiento
			$this->fechanacimiento->ViewValue = $this->fechanacimiento->CurrentValue;
			$this->fechanacimiento->ViewValue = ewr_FormatDateTime($this->fechanacimiento->ViewValue, 0);
			$this->fechanacimiento->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// celular
			$this->celular->ViewValue = $this->celular->CurrentValue;
			$this->celular->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ocupacion
			$this->ocupacion->ViewValue = $this->ocupacion->CurrentValue;
			$this->ocupacion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// parentesco
			$this->parentesco->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// nombres
			$this->nombres->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// celular
			$this->celular->HrefValue = "";

			// ocupacion
			$this->ocupacion->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";
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

			// parentesco
			$CurrentValue = $this->parentesco->CurrentValue;
			$ViewValue = &$this->parentesco->ViewValue;
			$ViewAttrs = &$this->parentesco->ViewAttrs;
			$CellAttrs = &$this->parentesco->CellAttrs;
			$HrefValue = &$this->parentesco->HrefValue;
			$LinkAttrs = &$this->parentesco->LinkAttrs;
			$this->Cell_Rendered($this->parentesco, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// sexo
			$CurrentValue = $this->sexo->CurrentValue;
			$ViewValue = &$this->sexo->ViewValue;
			$ViewAttrs = &$this->sexo->ViewAttrs;
			$CellAttrs = &$this->sexo->CellAttrs;
			$HrefValue = &$this->sexo->HrefValue;
			$LinkAttrs = &$this->sexo->LinkAttrs;
			$this->Cell_Rendered($this->sexo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fechanacimiento
			$CurrentValue = $this->fechanacimiento->CurrentValue;
			$ViewValue = &$this->fechanacimiento->ViewValue;
			$ViewAttrs = &$this->fechanacimiento->ViewAttrs;
			$CellAttrs = &$this->fechanacimiento->CellAttrs;
			$HrefValue = &$this->fechanacimiento->HrefValue;
			$LinkAttrs = &$this->fechanacimiento->LinkAttrs;
			$this->Cell_Rendered($this->fechanacimiento, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// direccion
			$CurrentValue = $this->direccion->CurrentValue;
			$ViewValue = &$this->direccion->ViewValue;
			$ViewAttrs = &$this->direccion->ViewAttrs;
			$CellAttrs = &$this->direccion->CellAttrs;
			$HrefValue = &$this->direccion->HrefValue;
			$LinkAttrs = &$this->direccion->LinkAttrs;
			$this->Cell_Rendered($this->direccion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// celular
			$CurrentValue = $this->celular->CurrentValue;
			$ViewValue = &$this->celular->ViewValue;
			$ViewAttrs = &$this->celular->ViewAttrs;
			$CellAttrs = &$this->celular->CellAttrs;
			$HrefValue = &$this->celular->HrefValue;
			$LinkAttrs = &$this->celular->LinkAttrs;
			$this->Cell_Rendered($this->celular, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// ocupacion
			$CurrentValue = $this->ocupacion->CurrentValue;
			$ViewValue = &$this->ocupacion->ViewValue;
			$ViewAttrs = &$this->ocupacion->ViewAttrs;
			$CellAttrs = &$this->ocupacion->CellAttrs;
			$HrefValue = &$this->ocupacion->HrefValue;
			$LinkAttrs = &$this->ocupacion->LinkAttrs;
			$this->Cell_Rendered($this->ocupacion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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
		if ($this->nombreinstitucion->Visible) $this->DtlColumnCount += 1;
		if ($this->parentesco->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidopaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidomaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->nombres->Visible) $this->DtlColumnCount += 1;
		if ($this->ci->Visible) $this->DtlColumnCount += 1;
		if ($this->sexo->Visible) $this->DtlColumnCount += 1;
		if ($this->fechanacimiento->Visible) $this->DtlColumnCount += 1;
		if ($this->direccion->Visible) $this->DtlColumnCount += 1;
		if ($this->celular->Visible) $this->DtlColumnCount += 1;
		if ($this->ocupacion->Visible) $this->DtlColumnCount += 1;
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

			// Clear extended filter for field nombreinstitucion
			if ($this->ClearExtFilter == 'viewsalsudapoderado_nombreinstitucion')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'nombreinstitucion');

			// Set/clear dropdown for field parentesco
			if ($this->PopupName == 'viewsalsudapoderado_parentesco' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->parentesco->DropDownValue = EWR_ALL_VALUE;
				else
					$this->parentesco->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewsalsudapoderado_parentesco') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'parentesco');
			}

			// Clear extended filter for field ci
			if ($this->ClearExtFilter == 'viewsalsudapoderado_ci')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'ci');

			// Clear extended filter for field sexo
			if ($this->ClearExtFilter == 'viewsalsudapoderado_sexo')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'sexo');

			// Clear extended filter for field fechanacimiento
			if ($this->ClearExtFilter == 'viewsalsudapoderado_fechanacimiento')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'fechanacimiento');

			// Clear extended filter for field ocupacion
			if ($this->ClearExtFilter == 'viewsalsudapoderado_ocupacion')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'ocupacion');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionFilterValues($this->nombreinstitucion->SearchValue, $this->nombreinstitucion->SearchOperator, $this->nombreinstitucion->SearchCondition, $this->nombreinstitucion->SearchValue2, $this->nombreinstitucion->SearchOperator2, 'nombreinstitucion'); // Field nombreinstitucion
			$this->SetSessionDropDownValue($this->parentesco->DropDownValue, $this->parentesco->SearchOperator, 'parentesco'); // Field parentesco
			$this->SetSessionFilterValues($this->ci->SearchValue, $this->ci->SearchOperator, $this->ci->SearchCondition, $this->ci->SearchValue2, $this->ci->SearchOperator2, 'ci'); // Field ci
			$this->SetSessionFilterValues($this->sexo->SearchValue, $this->sexo->SearchOperator, $this->sexo->SearchCondition, $this->sexo->SearchValue2, $this->sexo->SearchOperator2, 'sexo'); // Field sexo
			$this->SetSessionFilterValues($this->fechanacimiento->SearchValue, $this->fechanacimiento->SearchOperator, $this->fechanacimiento->SearchCondition, $this->fechanacimiento->SearchValue2, $this->fechanacimiento->SearchOperator2, 'fechanacimiento'); // Field fechanacimiento
			$this->SetSessionFilterValues($this->ocupacion->SearchValue, $this->ocupacion->SearchOperator, $this->ocupacion->SearchCondition, $this->ocupacion->SearchValue2, $this->ocupacion->SearchOperator2, 'ocupacion'); // Field ocupacion

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field nombreinstitucion
			if ($this->GetFilterValues($this->nombreinstitucion)) {
				$bSetupFilter = TRUE;
			}

			// Field parentesco
			if ($this->GetDropDownValue($this->parentesco)) {
				$bSetupFilter = TRUE;
			} elseif ($this->parentesco->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewsalsudapoderado_parentesco'])) {
				$bSetupFilter = TRUE;
			}

			// Field ci
			if ($this->GetFilterValues($this->ci)) {
				$bSetupFilter = TRUE;
			}

			// Field sexo
			if ($this->GetFilterValues($this->sexo)) {
				$bSetupFilter = TRUE;
			}

			// Field fechanacimiento
			if ($this->GetFilterValues($this->fechanacimiento)) {
				$bSetupFilter = TRUE;
			}

			// Field ocupacion
			if ($this->GetFilterValues($this->ocupacion)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionFilterValues($this->nombreinstitucion); // Field nombreinstitucion
			$this->GetSessionDropDownValue($this->parentesco); // Field parentesco
			$this->GetSessionFilterValues($this->ci); // Field ci
			$this->GetSessionFilterValues($this->sexo); // Field sexo
			$this->GetSessionFilterValues($this->fechanacimiento); // Field fechanacimiento
			$this->GetSessionFilterValues($this->ocupacion); // Field ocupacion
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildExtendedFilter($this->nombreinstitucion, $sFilter, FALSE, TRUE); // Field nombreinstitucion
		$this->BuildDropDownFilter($this->parentesco, $sFilter, $this->parentesco->SearchOperator, FALSE, TRUE); // Field parentesco
		$this->BuildExtendedFilter($this->ci, $sFilter, FALSE, TRUE); // Field ci
		$this->BuildExtendedFilter($this->sexo, $sFilter, FALSE, TRUE); // Field sexo
		$this->BuildExtendedFilter($this->fechanacimiento, $sFilter, FALSE, TRUE); // Field fechanacimiento
		$this->BuildExtendedFilter($this->ocupacion, $sFilter, FALSE, TRUE); // Field ocupacion

		// Save parms to session
		$this->SetSessionFilterValues($this->nombreinstitucion->SearchValue, $this->nombreinstitucion->SearchOperator, $this->nombreinstitucion->SearchCondition, $this->nombreinstitucion->SearchValue2, $this->nombreinstitucion->SearchOperator2, 'nombreinstitucion'); // Field nombreinstitucion
		$this->SetSessionDropDownValue($this->parentesco->DropDownValue, $this->parentesco->SearchOperator, 'parentesco'); // Field parentesco
		$this->SetSessionFilterValues($this->ci->SearchValue, $this->ci->SearchOperator, $this->ci->SearchCondition, $this->ci->SearchValue2, $this->ci->SearchOperator2, 'ci'); // Field ci
		$this->SetSessionFilterValues($this->sexo->SearchValue, $this->sexo->SearchOperator, $this->sexo->SearchCondition, $this->sexo->SearchValue2, $this->sexo->SearchOperator2, 'sexo'); // Field sexo
		$this->SetSessionFilterValues($this->fechanacimiento->SearchValue, $this->fechanacimiento->SearchOperator, $this->fechanacimiento->SearchCondition, $this->fechanacimiento->SearchValue2, $this->fechanacimiento->SearchOperator2, 'fechanacimiento'); // Field fechanacimiento
		$this->SetSessionFilterValues($this->ocupacion->SearchValue, $this->ocupacion->SearchOperator, $this->ocupacion->SearchCondition, $this->ocupacion->SearchValue2, $this->ocupacion->SearchOperator2, 'ocupacion'); // Field ocupacion

		// Setup filter
		if ($bSetupFilter) {

			// Field nombreinstitucion
			$sWrk = "";
			$this->BuildExtendedFilter($this->nombreinstitucion, $sWrk);
			ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SelectionList);
			$_SESSION['sel_viewsalsudapoderado_nombreinstitucion'] = ($this->nombreinstitucion->SelectionList == "") ? EWR_INIT_VALUE : $this->nombreinstitucion->SelectionList;

			// Field parentesco
			$sWrk = "";
			$this->BuildDropDownFilter($this->parentesco, $sWrk, $this->parentesco->SearchOperator);
			ewr_LoadSelectionFromFilter($this->parentesco, $sWrk, $this->parentesco->SelectionList, $this->parentesco->DropDownValue);
			$_SESSION['sel_viewsalsudapoderado_parentesco'] = ($this->parentesco->SelectionList == "") ? EWR_INIT_VALUE : $this->parentesco->SelectionList;

			// Field ci
			$sWrk = "";
			$this->BuildExtendedFilter($this->ci, $sWrk);
			ewr_LoadSelectionFromFilter($this->ci, $sWrk, $this->ci->SelectionList);
			$_SESSION['sel_viewsalsudapoderado_ci'] = ($this->ci->SelectionList == "") ? EWR_INIT_VALUE : $this->ci->SelectionList;

			// Field sexo
			$sWrk = "";
			$this->BuildExtendedFilter($this->sexo, $sWrk);
			ewr_LoadSelectionFromFilter($this->sexo, $sWrk, $this->sexo->SelectionList);
			$_SESSION['sel_viewsalsudapoderado_sexo'] = ($this->sexo->SelectionList == "") ? EWR_INIT_VALUE : $this->sexo->SelectionList;

			// Field fechanacimiento
			$sWrk = "";
			$this->BuildExtendedFilter($this->fechanacimiento, $sWrk);
			ewr_LoadSelectionFromFilter($this->fechanacimiento, $sWrk, $this->fechanacimiento->SelectionList);
			$_SESSION['sel_viewsalsudapoderado_fechanacimiento'] = ($this->fechanacimiento->SelectionList == "") ? EWR_INIT_VALUE : $this->fechanacimiento->SelectionList;

			// Field ocupacion
			$sWrk = "";
			$this->BuildExtendedFilter($this->ocupacion, $sWrk);
			ewr_LoadSelectionFromFilter($this->ocupacion, $sWrk, $this->ocupacion->SelectionList);
			$_SESSION['sel_viewsalsudapoderado_ocupacion'] = ($this->ocupacion->SelectionList == "") ? EWR_INIT_VALUE : $this->ocupacion->SelectionList;
		}

		// Field parentesco
		ewr_LoadDropDownList($this->parentesco->DropDownList, $this->parentesco->DropDownValue);
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewsalsudapoderado_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsalsudapoderado_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewsalsudapoderado_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsalsudapoderado_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewsalsudapoderado_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewsalsudapoderado_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewsalsudapoderado_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewsalsudapoderado_' . $parm] = $sv;
		$_SESSION['so_viewsalsudapoderado_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewsalsudapoderado_' . $parm] = $sv1;
		$_SESSION['so_viewsalsudapoderado_' . $parm] = $so1;
		$_SESSION['sc_viewsalsudapoderado_' . $parm] = $sc;
		$_SESSION['sv2_viewsalsudapoderado_' . $parm] = $sv2;
		$_SESSION['so2_viewsalsudapoderado_' . $parm] = $so2;
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
		if (!ewr_CheckInteger($this->sexo->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->sexo->FldErrMsg();
		}
		if (!ewr_CheckDateDef($this->fechanacimiento->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fechanacimiento->FldErrMsg();
		}
		if (!ewr_CheckDateDef($this->fechanacimiento->SearchValue2)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fechanacimiento->FldErrMsg();
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
		$_SESSION["sel_viewsalsudapoderado_$parm"] = "";
		$_SESSION["rf_viewsalsudapoderado_$parm"] = "";
		$_SESSION["rt_viewsalsudapoderado_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewsalsudapoderado_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewsalsudapoderado_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewsalsudapoderado_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field parentesco
		$this->parentesco->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->parentesco->DropDownValue = $this->parentesco->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->parentesco, $sWrk, $this->parentesco->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->parentesco, $sWrk, $this->parentesco->DefaultSelectionList);
		if (!$this->SearchCommand) $this->parentesco->SelectionList = $this->parentesco->DefaultSelectionList;
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

		// Field nombreinstitucion
		$this->SetDefaultExtFilter($this->nombreinstitucion, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombreinstitucion);
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreinstitucion, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombreinstitucion->SelectionList = $this->nombreinstitucion->DefaultSelectionList;

		// Field ci
		$this->SetDefaultExtFilter($this->ci, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->ci);
		$sWrk = "";
		$this->BuildExtendedFilter($this->ci, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->ci, $sWrk, $this->ci->DefaultSelectionList);
		if (!$this->SearchCommand) $this->ci->SelectionList = $this->ci->DefaultSelectionList;

		// Field sexo
		$this->SetDefaultExtFilter($this->sexo, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->sexo);
		$sWrk = "";
		$this->BuildExtendedFilter($this->sexo, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->sexo, $sWrk, $this->sexo->DefaultSelectionList);
		if (!$this->SearchCommand) $this->sexo->SelectionList = $this->sexo->DefaultSelectionList;

		// Field fechanacimiento
		$this->SetDefaultExtFilter($this->fechanacimiento, "BETWEEN", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->fechanacimiento);
		$sWrk = "";
		$this->BuildExtendedFilter($this->fechanacimiento, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->fechanacimiento, $sWrk, $this->fechanacimiento->DefaultSelectionList);
		if (!$this->SearchCommand) $this->fechanacimiento->SelectionList = $this->fechanacimiento->DefaultSelectionList;

		// Field ocupacion
		$this->SetDefaultExtFilter($this->ocupacion, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->ocupacion);
		$sWrk = "";
		$this->BuildExtendedFilter($this->ocupacion, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->ocupacion, $sWrk, $this->ocupacion->DefaultSelectionList);
		if (!$this->SearchCommand) $this->ocupacion->SelectionList = $this->ocupacion->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field nombreinstitucion
		// $this->nombreinstitucion->DefaultSelectionList = array("val1", "val2");
		// Field parentesco
		// $this->parentesco->DefaultSelectionList = array("val1", "val2");
		// Field ci
		// $this->ci->DefaultSelectionList = array("val1", "val2");
		// Field sexo
		// $this->sexo->DefaultSelectionList = array("val1", "val2");
		// Field fechanacimiento
		// $this->fechanacimiento->DefaultSelectionList = array("val1", "val2");
		// Field ocupacion
		// $this->ocupacion->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check nombreinstitucion text filter
		if ($this->TextFilterApplied($this->nombreinstitucion))
			return TRUE;

		// Check nombreinstitucion popup filter
		if (!ewr_MatchedArray($this->nombreinstitucion->DefaultSelectionList, $this->nombreinstitucion->SelectionList))
			return TRUE;

		// Check parentesco extended filter
		if ($this->NonTextFilterApplied($this->parentesco))
			return TRUE;

		// Check parentesco popup filter
		if (!ewr_MatchedArray($this->parentesco->DefaultSelectionList, $this->parentesco->SelectionList))
			return TRUE;

		// Check ci text filter
		if ($this->TextFilterApplied($this->ci))
			return TRUE;

		// Check ci popup filter
		if (!ewr_MatchedArray($this->ci->DefaultSelectionList, $this->ci->SelectionList))
			return TRUE;

		// Check sexo text filter
		if ($this->TextFilterApplied($this->sexo))
			return TRUE;

		// Check sexo popup filter
		if (!ewr_MatchedArray($this->sexo->DefaultSelectionList, $this->sexo->SelectionList))
			return TRUE;

		// Check fechanacimiento text filter
		if ($this->TextFilterApplied($this->fechanacimiento))
			return TRUE;

		// Check fechanacimiento popup filter
		if (!ewr_MatchedArray($this->fechanacimiento->DefaultSelectionList, $this->fechanacimiento->SelectionList))
			return TRUE;

		// Check ocupacion text filter
		if ($this->TextFilterApplied($this->ocupacion))
			return TRUE;

		// Check ocupacion popup filter
		if (!ewr_MatchedArray($this->ocupacion->DefaultSelectionList, $this->ocupacion->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field nombreinstitucion
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreinstitucion, $sExtWrk);
		if (is_array($this->nombreinstitucion->SelectionList))
			$sWrk = ewr_JoinArray($this->nombreinstitucion->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreinstitucion->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field parentesco
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->parentesco, $sExtWrk, $this->parentesco->SearchOperator);
		if (is_array($this->parentesco->SelectionList))
			$sWrk = ewr_JoinArray($this->parentesco->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->parentesco->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field ci
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->ci, $sExtWrk);
		if (is_array($this->ci->SelectionList))
			$sWrk = ewr_JoinArray($this->ci->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->ci->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field sexo
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->sexo, $sExtWrk);
		if (is_array($this->sexo->SelectionList))
			$sWrk = ewr_JoinArray($this->sexo->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->sexo->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field fechanacimiento
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->fechanacimiento, $sExtWrk);
		if (is_array($this->fechanacimiento->SelectionList))
			$sWrk = ewr_JoinArray($this->fechanacimiento->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fechanacimiento->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field ocupacion
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->ocupacion, $sExtWrk);
		if (is_array($this->ocupacion->SelectionList))
			$sWrk = ewr_JoinArray($this->ocupacion->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->ocupacion->FldCaption() . "</span>" . $sFilter . "</div>";
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

		// Field nombreinstitucion
		$sWrk = "";
		if ($this->nombreinstitucion->SearchValue <> "" || $this->nombreinstitucion->SearchValue2 <> "") {
			$sWrk = "\"sv_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchValue) . "\"," .
				"\"so_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchOperator) . "\"," .
				"\"sc_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchCondition) . "\"," .
				"\"sv2_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchValue2) . "\"," .
				"\"so2_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->nombreinstitucion->SelectionList <> EWR_INIT_VALUE) ? $this->nombreinstitucion->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nombreinstitucion\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field parentesco
		$sWrk = "";
		$sWrk = ($this->parentesco->DropDownValue <> EWR_INIT_VALUE) ? $this->parentesco->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_parentesco\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->parentesco->SelectionList <> EWR_INIT_VALUE) ? $this->parentesco->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_parentesco\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field ci
		$sWrk = "";
		if ($this->ci->SearchValue <> "" || $this->ci->SearchValue2 <> "") {
			$sWrk = "\"sv_ci\":\"" . ewr_JsEncode2($this->ci->SearchValue) . "\"," .
				"\"so_ci\":\"" . ewr_JsEncode2($this->ci->SearchOperator) . "\"," .
				"\"sc_ci\":\"" . ewr_JsEncode2($this->ci->SearchCondition) . "\"," .
				"\"sv2_ci\":\"" . ewr_JsEncode2($this->ci->SearchValue2) . "\"," .
				"\"so2_ci\":\"" . ewr_JsEncode2($this->ci->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->ci->SelectionList <> EWR_INIT_VALUE) ? $this->ci->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_ci\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field sexo
		$sWrk = "";
		if ($this->sexo->SearchValue <> "" || $this->sexo->SearchValue2 <> "") {
			$sWrk = "\"sv_sexo\":\"" . ewr_JsEncode2($this->sexo->SearchValue) . "\"," .
				"\"so_sexo\":\"" . ewr_JsEncode2($this->sexo->SearchOperator) . "\"," .
				"\"sc_sexo\":\"" . ewr_JsEncode2($this->sexo->SearchCondition) . "\"," .
				"\"sv2_sexo\":\"" . ewr_JsEncode2($this->sexo->SearchValue2) . "\"," .
				"\"so2_sexo\":\"" . ewr_JsEncode2($this->sexo->SearchOperator2) . "\"";
		}
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

		// Field fechanacimiento
		$sWrk = "";
		if ($this->fechanacimiento->SearchValue <> "" || $this->fechanacimiento->SearchValue2 <> "") {
			$sWrk = "\"sv_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchValue) . "\"," .
				"\"so_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchOperator) . "\"," .
				"\"sc_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchCondition) . "\"," .
				"\"sv2_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchValue2) . "\"," .
				"\"so2_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->fechanacimiento->SelectionList <> EWR_INIT_VALUE) ? $this->fechanacimiento->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_fechanacimiento\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field ocupacion
		$sWrk = "";
		if ($this->ocupacion->SearchValue <> "" || $this->ocupacion->SearchValue2 <> "") {
			$sWrk = "\"sv_ocupacion\":\"" . ewr_JsEncode2($this->ocupacion->SearchValue) . "\"," .
				"\"so_ocupacion\":\"" . ewr_JsEncode2($this->ocupacion->SearchOperator) . "\"," .
				"\"sc_ocupacion\":\"" . ewr_JsEncode2($this->ocupacion->SearchCondition) . "\"," .
				"\"sv2_ocupacion\":\"" . ewr_JsEncode2($this->ocupacion->SearchValue2) . "\"," .
				"\"so2_ocupacion\":\"" . ewr_JsEncode2($this->ocupacion->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->ocupacion->SelectionList <> EWR_INIT_VALUE) ? $this->ocupacion->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_ocupacion\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field nombreinstitucion
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombreinstitucion", $filter) || array_key_exists("so_nombreinstitucion", $filter) ||
			array_key_exists("sc_nombreinstitucion", $filter) ||
			array_key_exists("sv2_nombreinstitucion", $filter) || array_key_exists("so2_nombreinstitucion", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombreinstitucion"], @$filter["so_nombreinstitucion"], @$filter["sc_nombreinstitucion"], @$filter["sv2_nombreinstitucion"], @$filter["so2_nombreinstitucion"], "nombreinstitucion");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombreinstitucion", $filter)) {
			$sWrk = $filter["sel_nombreinstitucion"];
			$sWrk = explode("||", $sWrk);
			$this->nombreinstitucion->SelectionList = $sWrk;
			$_SESSION["sel_viewsalsudapoderado_nombreinstitucion"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreinstitucion"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreinstitucion");
			$this->nombreinstitucion->SelectionList = "";
			$_SESSION["sel_viewsalsudapoderado_nombreinstitucion"] = "";
		}

		// Field parentesco
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_parentesco", $filter)) {
			$sWrk = $filter["sv_parentesco"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_parentesco"], "parentesco");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_parentesco", $filter)) {
			$sWrk = $filter["sel_parentesco"];
			$sWrk = explode("||", $sWrk);
			$this->parentesco->SelectionList = $sWrk;
			$_SESSION["sel_viewsalsudapoderado_parentesco"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "parentesco"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "parentesco");
			$this->parentesco->SelectionList = "";
			$_SESSION["sel_viewsalsudapoderado_parentesco"] = "";
		}

		// Field ci
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_ci", $filter) || array_key_exists("so_ci", $filter) ||
			array_key_exists("sc_ci", $filter) ||
			array_key_exists("sv2_ci", $filter) || array_key_exists("so2_ci", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_ci"], @$filter["so_ci"], @$filter["sc_ci"], @$filter["sv2_ci"], @$filter["so2_ci"], "ci");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_ci", $filter)) {
			$sWrk = $filter["sel_ci"];
			$sWrk = explode("||", $sWrk);
			$this->ci->SelectionList = $sWrk;
			$_SESSION["sel_viewsalsudapoderado_ci"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "ci"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "ci");
			$this->ci->SelectionList = "";
			$_SESSION["sel_viewsalsudapoderado_ci"] = "";
		}

		// Field sexo
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_sexo", $filter) || array_key_exists("so_sexo", $filter) ||
			array_key_exists("sc_sexo", $filter) ||
			array_key_exists("sv2_sexo", $filter) || array_key_exists("so2_sexo", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_sexo"], @$filter["so_sexo"], @$filter["sc_sexo"], @$filter["sv2_sexo"], @$filter["so2_sexo"], "sexo");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_sexo", $filter)) {
			$sWrk = $filter["sel_sexo"];
			$sWrk = explode("||", $sWrk);
			$this->sexo->SelectionList = $sWrk;
			$_SESSION["sel_viewsalsudapoderado_sexo"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "sexo"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "sexo");
			$this->sexo->SelectionList = "";
			$_SESSION["sel_viewsalsudapoderado_sexo"] = "";
		}

		// Field fechanacimiento
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_fechanacimiento", $filter) || array_key_exists("so_fechanacimiento", $filter) ||
			array_key_exists("sc_fechanacimiento", $filter) ||
			array_key_exists("sv2_fechanacimiento", $filter) || array_key_exists("so2_fechanacimiento", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_fechanacimiento"], @$filter["so_fechanacimiento"], @$filter["sc_fechanacimiento"], @$filter["sv2_fechanacimiento"], @$filter["so2_fechanacimiento"], "fechanacimiento");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_fechanacimiento", $filter)) {
			$sWrk = $filter["sel_fechanacimiento"];
			$sWrk = explode("||", $sWrk);
			$this->fechanacimiento->SelectionList = $sWrk;
			$_SESSION["sel_viewsalsudapoderado_fechanacimiento"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fechanacimiento"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fechanacimiento");
			$this->fechanacimiento->SelectionList = "";
			$_SESSION["sel_viewsalsudapoderado_fechanacimiento"] = "";
		}

		// Field ocupacion
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_ocupacion", $filter) || array_key_exists("so_ocupacion", $filter) ||
			array_key_exists("sc_ocupacion", $filter) ||
			array_key_exists("sv2_ocupacion", $filter) || array_key_exists("so2_ocupacion", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_ocupacion"], @$filter["so_ocupacion"], @$filter["sc_ocupacion"], @$filter["sv2_ocupacion"], @$filter["so2_ocupacion"], "ocupacion");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_ocupacion", $filter)) {
			$sWrk = $filter["sel_ocupacion"];
			$sWrk = explode("||", $sWrk);
			$this->ocupacion->SelectionList = $sWrk;
			$_SESSION["sel_viewsalsudapoderado_ocupacion"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "ocupacion"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "ocupacion");
			$this->ocupacion->SelectionList = "";
			$_SESSION["sel_viewsalsudapoderado_ocupacion"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->ExtendedFilterExist($this->nombreinstitucion)) {
			if (is_array($this->nombreinstitucion->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombreinstitucion, "`nombreinstitucion`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreinstitucion, $sFilter, "popup");
				$this->nombreinstitucion->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->parentesco, $this->parentesco->SearchOperator)) {
			if (is_array($this->parentesco->SelectionList)) {
				$sFilter = ewr_FilterSql($this->parentesco, "`parentesco`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->parentesco, $sFilter, "popup");
				$this->parentesco->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->ci)) {
			if (is_array($this->ci->SelectionList)) {
				$sFilter = ewr_FilterSql($this->ci, "`ci`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->ci, $sFilter, "popup");
				$this->ci->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->sexo)) {
			if (is_array($this->sexo->SelectionList)) {
				$sFilter = ewr_FilterSql($this->sexo, "`sexo`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->sexo, $sFilter, "popup");
				$this->sexo->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->fechanacimiento)) {
			if (is_array($this->fechanacimiento->SelectionList)) {
				$sFilter = ewr_FilterSql($this->fechanacimiento, "`fechanacimiento`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->fechanacimiento, $sFilter, "popup");
				$this->fechanacimiento->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->ocupacion)) {
			if (is_array($this->ocupacion->SelectionList)) {
				$sFilter = ewr_FilterSql($this->ocupacion, "`ocupacion`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->ocupacion, $sFilter, "popup");
				$this->ocupacion->CurrentFilter = $sFilter;
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
			$this->nombreinstitucion->setSort("");
			$this->parentesco->setSort("");
			$this->apellidopaterno->setSort("");
			$this->apellidomaterno->setSort("");
			$this->nombres->setSort("");
			$this->ci->setSort("");
			$this->sexo->setSort("");
			$this->fechanacimiento->setSort("");
			$this->direccion->setSort("");
			$this->celular->setSort("");
			$this->ocupacion->setSort("");
			$this->observaciones->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->nombreinstitucion, $bCtrl); // nombreinstitucion
			$this->UpdateSort($this->parentesco, $bCtrl); // parentesco
			$this->UpdateSort($this->apellidopaterno, $bCtrl); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno, $bCtrl); // apellidomaterno
			$this->UpdateSort($this->nombres, $bCtrl); // nombres
			$this->UpdateSort($this->ci, $bCtrl); // ci
			$this->UpdateSort($this->sexo, $bCtrl); // sexo
			$this->UpdateSort($this->fechanacimiento, $bCtrl); // fechanacimiento
			$this->UpdateSort($this->direccion, $bCtrl); // direccion
			$this->UpdateSort($this->celular, $bCtrl); // celular
			$this->UpdateSort($this->ocupacion, $bCtrl); // ocupacion
			$this->UpdateSort($this->observaciones, $bCtrl); // observaciones
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
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
if (!isset($viewsalsudapoderado_rpt)) $viewsalsudapoderado_rpt = new crviewsalsudapoderado_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewsalsudapoderado_rpt;

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
var viewsalsudapoderado_rpt = new ewr_Page("viewsalsudapoderado_rpt");

// Page properties
viewsalsudapoderado_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewsalsudapoderado_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewsalsudapoderadorpt = new ewr_Form("fviewsalsudapoderadorpt");

// Validate method
fviewsalsudapoderadorpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_sexo;
	if (elm && !ewr_CheckInteger(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->sexo->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv_fechanacimiento;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fechanacimiento->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_fechanacimiento;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fechanacimiento->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewsalsudapoderadorpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewsalsudapoderadorpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewsalsudapoderadorpt.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fviewsalsudapoderadorpt.Lists["sv_parentesco"] = {"LinkField":"sv_parentesco","Ajax":true,"DisplayFields":["sv_parentesco","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
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
<form name="fviewsalsudapoderadorpt" id="fviewsalsudapoderadorpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewsalsudapoderadorpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_nombreinstitucion" class="ewCell form-group">
	<label for="sv_nombreinstitucion" class="ewSearchCaption ewLabel"><?php echo $Page->nombreinstitucion->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombreinstitucion" id="so_nombreinstitucion" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombreinstitucion->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsalsudapoderado" data-field="x_nombreinstitucion" id="sv_nombreinstitucion" name="sv_nombreinstitucion" size="30" maxlength="100" placeholder="<?php echo $Page->nombreinstitucion->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombreinstitucion->SearchValue) ?>"<?php echo $Page->nombreinstitucion->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_parentesco" class="ewCell form-group">
	<label for="sv_parentesco" class="ewSearchCaption ewLabel"><?php echo $Page->parentesco->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->parentesco->EditAttrs["class"], "form-control"); ?>
<select data-table="viewsalsudapoderado" data-field="x_parentesco" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->parentesco->DisplayValueSeparator) ? json_encode($Page->parentesco->DisplayValueSeparator) : $Page->parentesco->DisplayValueSeparator) ?>" id="sv_parentesco" name="sv_parentesco"<?php echo $Page->parentesco->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->parentesco->AdvancedFilters) ? count($Page->parentesco->AdvancedFilters) : 0;
	$cntd = is_array($Page->parentesco->DropDownList) ? count($Page->parentesco->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->parentesco->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->parentesco->DropDownValue, $filter->ID) ? " selected" : "";
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
<option value="<?php echo $Page->parentesco->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->parentesco->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_parentesco" id="s_sv_parentesco" value="<?php echo $Page->parentesco->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewsalsudapoderadorpt.Lists["sv_parentesco"].Options = <?php echo ewr_ArrayToJson($Page->parentesco->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
<div id="r_3" class="ewRow">
<div id="c_ci" class="ewCell form-group">
	<label for="sv_ci" class="ewSearchCaption ewLabel"><?php echo $Page->ci->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_ci" id="so_ci" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->ci->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsalsudapoderado" data-field="x_ci" id="sv_ci" name="sv_ci" size="30" maxlength="15" placeholder="<?php echo $Page->ci->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->ci->SearchValue) ?>"<?php echo $Page->ci->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_4" class="ewRow">
<div id="c_sexo" class="ewCell form-group">
	<label for="sv_sexo" class="ewSearchCaption ewLabel"><?php echo $Page->sexo->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_sexo" id="so_sexo" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->sexo->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsalsudapoderado" data-field="x_sexo" id="sv_sexo" name="sv_sexo" size="30" placeholder="<?php echo $Page->sexo->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->sexo->SearchValue) ?>"<?php echo $Page->sexo->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_5" class="ewRow">
<div id="c_fechanacimiento" class="ewCell form-group">
	<label for="sv_fechanacimiento" class="ewSearchCaption ewLabel"><?php echo $Page->fechanacimiento->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so_fechanacimiento" id="so_fechanacimiento" value="BETWEEN"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fechanacimiento->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsalsudapoderado" data-field="x_fechanacimiento" id="sv_fechanacimiento" name="sv_fechanacimiento" placeholder="<?php echo $Page->fechanacimiento->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fechanacimiento->SearchValue) ?>"<?php echo $Page->fechanacimiento->EditAttributes() ?>>
</span>
	<span class="ewSearchCond btw1_fechanacimiento"><?php echo $ReportLanguage->Phrase("AND") ?></span>
	<span class="ewSearchField btw1_fechanacimiento">
<?php ewr_PrependClass($Page->fechanacimiento->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsalsudapoderado" data-field="x_fechanacimiento" id="sv2_fechanacimiento" name="sv2_fechanacimiento" placeholder="<?php echo $Page->fechanacimiento->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fechanacimiento->SearchValue2) ?>"<?php echo $Page->fechanacimiento->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_6" class="ewRow">
<div id="c_ocupacion" class="ewCell form-group">
	<label for="sv_ocupacion" class="ewSearchCaption ewLabel"><?php echo $Page->ocupacion->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_ocupacion" id="so_ocupacion" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->ocupacion->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsalsudapoderado" data-field="x_ocupacion" id="sv_ocupacion" name="sv_ocupacion" size="30" maxlength="100" placeholder="<?php echo $Page->ocupacion->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->ocupacion->SearchValue) ?>"<?php echo $Page->ocupacion->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewsalsudapoderadorpt.Init();
fviewsalsudapoderadorpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<div id="gmp_viewsalsudapoderado" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->nombreinstitucion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="viewsalsudapoderado_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->parentesco->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="parentesco"><div class="viewsalsudapoderado_parentesco"><span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="parentesco">
<?php if ($Page->SortUrl($Page->parentesco) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_parentesco">
			<span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_parentesco', range: false, from: '<?php echo $Page->parentesco->RangeFrom; ?>', to: '<?php echo $Page->parentesco->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_parentesco<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_parentesco" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->parentesco) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_parentesco', range: false, from: '<?php echo $Page->parentesco->RangeFrom; ?>', to: '<?php echo $Page->parentesco->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_parentesco<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidopaterno"><div class="viewsalsudapoderado_apellidopaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidopaterno">
<?php if ($Page->SortUrl($Page->apellidopaterno) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_apellidopaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_apellidopaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidopaterno) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidomaterno"><div class="viewsalsudapoderado_apellidomaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidomaterno">
<?php if ($Page->SortUrl($Page->apellidomaterno) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_apellidomaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_apellidomaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidomaterno) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombres->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombres"><div class="viewsalsudapoderado_nombres"><span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombres">
<?php if ($Page->SortUrl($Page->nombres) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_nombres">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_nombres" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombres) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ci"><div class="viewsalsudapoderado_ci"><span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ci">
<?php if ($Page->SortUrl($Page->ci) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_ci">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_ci', range: false, from: '<?php echo $Page->ci->RangeFrom; ?>', to: '<?php echo $Page->ci->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_ci<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_ci" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ci) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_ci', range: false, from: '<?php echo $Page->ci->RangeFrom; ?>', to: '<?php echo $Page->ci->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_ci<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sexo"><div class="viewsalsudapoderado_sexo"><span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sexo">
<?php if ($Page->SortUrl($Page->sexo) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_sexo">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_sexo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sexo) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fechanacimiento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fechanacimiento"><div class="viewsalsudapoderado_fechanacimiento"><span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fechanacimiento">
<?php if ($Page->SortUrl($Page->fechanacimiento) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_fechanacimiento">
			<span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_fechanacimiento', range: false, from: '<?php echo $Page->fechanacimiento->RangeFrom; ?>', to: '<?php echo $Page->fechanacimiento->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_fechanacimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_fechanacimiento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fechanacimiento) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fechanacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fechanacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_fechanacimiento', range: false, from: '<?php echo $Page->fechanacimiento->RangeFrom; ?>', to: '<?php echo $Page->fechanacimiento->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_fechanacimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->direccion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="direccion"><div class="viewsalsudapoderado_direccion"><span class="ewTableHeaderCaption"><?php echo $Page->direccion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="direccion">
<?php if ($Page->SortUrl($Page->direccion) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_direccion">
			<span class="ewTableHeaderCaption"><?php echo $Page->direccion->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_direccion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->direccion) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->direccion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->celular->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="celular"><div class="viewsalsudapoderado_celular"><span class="ewTableHeaderCaption"><?php echo $Page->celular->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="celular">
<?php if ($Page->SortUrl($Page->celular) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_celular">
			<span class="ewTableHeaderCaption"><?php echo $Page->celular->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_celular" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->celular) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->celular->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->celular->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->celular->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ocupacion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ocupacion"><div class="viewsalsudapoderado_ocupacion"><span class="ewTableHeaderCaption"><?php echo $Page->ocupacion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ocupacion">
<?php if ($Page->SortUrl($Page->ocupacion) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_ocupacion">
			<span class="ewTableHeaderCaption"><?php echo $Page->ocupacion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_ocupacion', range: false, from: '<?php echo $Page->ocupacion->RangeFrom; ?>', to: '<?php echo $Page->ocupacion->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_ocupacion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_ocupacion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ocupacion) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ocupacion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ocupacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ocupacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsalsudapoderado_ocupacion', range: false, from: '<?php echo $Page->ocupacion->RangeFrom; ?>', to: '<?php echo $Page->ocupacion->RangeTo; ?>', url: 'viewsalsudapoderadorpt.php' });" id="x_ocupacion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="observaciones"><div class="viewsalsudapoderado_observaciones"><span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="observaciones">
<?php if ($Page->SortUrl($Page->observaciones) == "") { ?>
		<div class="ewTableHeaderBtn viewsalsudapoderado_observaciones">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsalsudapoderado_observaciones" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->observaciones) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
<?php if ($Page->parentesco->Visible) { ?>
		<td data-field="parentesco"<?php echo $Page->parentesco->CellAttributes() ?>>
<span<?php echo $Page->parentesco->ViewAttributes() ?>><?php echo $Page->parentesco->ListViewValue() ?></span></td>
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
<?php if ($Page->sexo->Visible) { ?>
		<td data-field="sexo"<?php echo $Page->sexo->CellAttributes() ?>>
<span<?php echo $Page->sexo->ViewAttributes() ?>><?php echo $Page->sexo->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fechanacimiento->Visible) { ?>
		<td data-field="fechanacimiento"<?php echo $Page->fechanacimiento->CellAttributes() ?>>
<span<?php echo $Page->fechanacimiento->ViewAttributes() ?>><?php echo $Page->fechanacimiento->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->direccion->Visible) { ?>
		<td data-field="direccion"<?php echo $Page->direccion->CellAttributes() ?>>
<span<?php echo $Page->direccion->ViewAttributes() ?>><?php echo $Page->direccion->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->celular->Visible) { ?>
		<td data-field="celular"<?php echo $Page->celular->CellAttributes() ?>>
<span<?php echo $Page->celular->ViewAttributes() ?>><?php echo $Page->celular->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ocupacion->Visible) { ?>
		<td data-field="ocupacion"<?php echo $Page->ocupacion->CellAttributes() ?>>
<span<?php echo $Page->ocupacion->ViewAttributes() ?>><?php echo $Page->ocupacion->ListViewValue() ?></span></td>
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
<div id="gmp_viewsalsudapoderado" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
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
<?php include "viewsalsudapoderadorptpager.php" ?>
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
