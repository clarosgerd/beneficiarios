<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "neonatalrptinfo.php" ?>
<?php

//
// Page class
//

$neonatal_rpt = NULL; // Initialize page object first

class crneonatal_rpt extends crneonatal {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'neonatal_rpt';

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

		// Table object (neonatal)
		if (!isset($GLOBALS["neonatal"])) {
			$GLOBALS["neonatal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["neonatal"];
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
			define("EWR_TABLE_NAME", 'neonatal', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fneonatalrpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'neonatal');
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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_neonatal\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_neonatal',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fneonatalrpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fneonatalrpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fneonatalrpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->id->SetVisibility();
		$this->fecha_tamizaje->SetVisibility();
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

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 23;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

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
				$this->FirstRowData['id'] = ewr_Conv($rs->fields('id'), 3);
				$this->FirstRowData['fecha_tamizaje'] = ewr_Conv($rs->fields('fecha_tamizaje'), 133);
				$this->FirstRowData['id_centro'] = ewr_Conv($rs->fields('id_centro'), 3);
				$this->FirstRowData['apellidopaterno'] = ewr_Conv($rs->fields('apellidopaterno'), 200);
				$this->FirstRowData['apellidomaterno'] = ewr_Conv($rs->fields('apellidomaterno'), 200);
				$this->FirstRowData['nombre'] = ewr_Conv($rs->fields('nombre'), 200);
				$this->FirstRowData['ci'] = ewr_Conv($rs->fields('ci'), 200);
				$this->FirstRowData['fecha_nacimiento'] = ewr_Conv($rs->fields('fecha_nacimiento'), 133);
				$this->FirstRowData['dias'] = ewr_Conv($rs->fields('dias'), 200);
				$this->FirstRowData['semanas'] = ewr_Conv($rs->fields('semanas'), 200);
				$this->FirstRowData['meses'] = ewr_Conv($rs->fields('meses'), 200);
				$this->FirstRowData['sexo'] = ewr_Conv($rs->fields('sexo'), 200);
				$this->FirstRowData['discapacidad'] = ewr_Conv($rs->fields('discapacidad'), 3);
				$this->FirstRowData['id_tipodiscapacidad'] = ewr_Conv($rs->fields('id_tipodiscapacidad'), 3);
				$this->FirstRowData['resultado'] = ewr_Conv($rs->fields('resultado'), 200);
				$this->FirstRowData['resultadotamizaje'] = ewr_Conv($rs->fields('resultadotamizaje'), 200);
				$this->FirstRowData['tapon'] = ewr_Conv($rs->fields('tapon'), 200);
				$this->FirstRowData['tipo'] = ewr_Conv($rs->fields('tipo'), 200);
				$this->FirstRowData['repetirprueba'] = ewr_Conv($rs->fields('repetirprueba'), 200);
				$this->FirstRowData['observaciones'] = ewr_Conv($rs->fields('observaciones'), 200);
				$this->FirstRowData['id_apoderado'] = ewr_Conv($rs->fields('id_apoderado'), 3);
				$this->FirstRowData['id_referencia'] = ewr_Conv($rs->fields('id_referencia'), 3);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->id->setDbValue($rs->fields('id'));
			$this->fecha_tamizaje->setDbValue($rs->fields('fecha_tamizaje'));
			$this->id_centro->setDbValue($rs->fields('id_centro'));
			$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
			$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
			$this->nombre->setDbValue($rs->fields('nombre'));
			$this->ci->setDbValue($rs->fields('ci'));
			$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
			$this->dias->setDbValue($rs->fields('dias'));
			$this->semanas->setDbValue($rs->fields('semanas'));
			$this->meses->setDbValue($rs->fields('meses'));
			$this->sexo->setDbValue($rs->fields('sexo'));
			$this->discapacidad->setDbValue($rs->fields('discapacidad'));
			$this->id_tipodiscapacidad->setDbValue($rs->fields('id_tipodiscapacidad'));
			$this->resultado->setDbValue($rs->fields('resultado'));
			$this->resultadotamizaje->setDbValue($rs->fields('resultadotamizaje'));
			$this->tapon->setDbValue($rs->fields('tapon'));
			$this->tipo->setDbValue($rs->fields('tipo'));
			$this->repetirprueba->setDbValue($rs->fields('repetirprueba'));
			$this->observaciones->setDbValue($rs->fields('observaciones'));
			$this->id_apoderado->setDbValue($rs->fields('id_apoderado'));
			$this->id_referencia->setDbValue($rs->fields('id_referencia'));
			$this->Val[1] = $this->id->CurrentValue;
			$this->Val[2] = $this->fecha_tamizaje->CurrentValue;
			$this->Val[3] = $this->id_centro->CurrentValue;
			$this->Val[4] = $this->apellidopaterno->CurrentValue;
			$this->Val[5] = $this->apellidomaterno->CurrentValue;
			$this->Val[6] = $this->nombre->CurrentValue;
			$this->Val[7] = $this->ci->CurrentValue;
			$this->Val[8] = $this->fecha_nacimiento->CurrentValue;
			$this->Val[9] = $this->dias->CurrentValue;
			$this->Val[10] = $this->semanas->CurrentValue;
			$this->Val[11] = $this->meses->CurrentValue;
			$this->Val[12] = $this->sexo->CurrentValue;
			$this->Val[13] = $this->discapacidad->CurrentValue;
			$this->Val[14] = $this->id_tipodiscapacidad->CurrentValue;
			$this->Val[15] = $this->resultado->CurrentValue;
			$this->Val[16] = $this->resultadotamizaje->CurrentValue;
			$this->Val[17] = $this->tapon->CurrentValue;
			$this->Val[18] = $this->tipo->CurrentValue;
			$this->Val[19] = $this->repetirprueba->CurrentValue;
			$this->Val[20] = $this->observaciones->CurrentValue;
			$this->Val[21] = $this->id_apoderado->CurrentValue;
			$this->Val[22] = $this->id_referencia->CurrentValue;
		} else {
			$this->id->setDbValue("");
			$this->fecha_tamizaje->setDbValue("");
			$this->id_centro->setDbValue("");
			$this->apellidopaterno->setDbValue("");
			$this->apellidomaterno->setDbValue("");
			$this->nombre->setDbValue("");
			$this->ci->setDbValue("");
			$this->fecha_nacimiento->setDbValue("");
			$this->dias->setDbValue("");
			$this->semanas->setDbValue("");
			$this->meses->setDbValue("");
			$this->sexo->setDbValue("");
			$this->discapacidad->setDbValue("");
			$this->id_tipodiscapacidad->setDbValue("");
			$this->resultado->setDbValue("");
			$this->resultadotamizaje->setDbValue("");
			$this->tapon->setDbValue("");
			$this->tipo->setDbValue("");
			$this->repetirprueba->setDbValue("");
			$this->observaciones->setDbValue("");
			$this->id_apoderado->setDbValue("");
			$this->id_referencia->setDbValue("");
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

			// id
			$this->id->HrefValue = "";

			// fecha_tamizaje
			$this->fecha_tamizaje->HrefValue = "";

			// id_centro
			$this->id_centro->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->HrefValue = "";

			// dias
			$this->dias->HrefValue = "";

			// semanas
			$this->semanas->HrefValue = "";

			// meses
			$this->meses->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->HrefValue = "";

			// resultado
			$this->resultado->HrefValue = "";

			// resultadotamizaje
			$this->resultadotamizaje->HrefValue = "";

			// tapon
			$this->tapon->HrefValue = "";

			// tipo
			$this->tipo->HrefValue = "";

			// repetirprueba
			$this->repetirprueba->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// id_apoderado
			$this->id_apoderado->HrefValue = "";

			// id_referencia
			$this->id_referencia->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha_tamizaje
			$this->fecha_tamizaje->ViewValue = $this->fecha_tamizaje->CurrentValue;
			$this->fecha_tamizaje->ViewValue = ewr_FormatDateTime($this->fecha_tamizaje->ViewValue, 0);
			$this->fecha_tamizaje->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_centro
			$this->id_centro->ViewValue = $this->id_centro->CurrentValue;
			$this->id_centro->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// apellidopaterno
			$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
			$this->apellidopaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// apellidomaterno
			$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
			$this->apellidomaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ci
			$this->ci->ViewValue = $this->ci->CurrentValue;
			$this->ci->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha_nacimiento
			$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
			$this->fecha_nacimiento->ViewValue = ewr_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
			$this->fecha_nacimiento->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// dias
			$this->dias->ViewValue = $this->dias->CurrentValue;
			$this->dias->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// semanas
			$this->semanas->ViewValue = $this->semanas->CurrentValue;
			$this->semanas->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// meses
			$this->meses->ViewValue = $this->meses->CurrentValue;
			$this->meses->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// sexo
			$this->sexo->ViewValue = $this->sexo->CurrentValue;
			$this->sexo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// discapacidad
			$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
			$this->discapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->ViewValue = $this->id_tipodiscapacidad->CurrentValue;
			$this->id_tipodiscapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// resultado
			$this->resultado->ViewValue = $this->resultado->CurrentValue;
			$this->resultado->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// resultadotamizaje
			$this->resultadotamizaje->ViewValue = $this->resultadotamizaje->CurrentValue;
			$this->resultadotamizaje->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tapon
			$this->tapon->ViewValue = $this->tapon->CurrentValue;
			$this->tapon->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipo
			$this->tipo->ViewValue = $this->tipo->CurrentValue;
			$this->tipo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// repetirprueba
			$this->repetirprueba->ViewValue = $this->repetirprueba->CurrentValue;
			$this->repetirprueba->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_apoderado
			$this->id_apoderado->ViewValue = $this->id_apoderado->CurrentValue;
			$this->id_apoderado->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_referencia
			$this->id_referencia->ViewValue = $this->id_referencia->CurrentValue;
			$this->id_referencia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id
			$this->id->HrefValue = "";

			// fecha_tamizaje
			$this->fecha_tamizaje->HrefValue = "";

			// id_centro
			$this->id_centro->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->HrefValue = "";

			// dias
			$this->dias->HrefValue = "";

			// semanas
			$this->semanas->HrefValue = "";

			// meses
			$this->meses->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// id_tipodiscapacidad
			$this->id_tipodiscapacidad->HrefValue = "";

			// resultado
			$this->resultado->HrefValue = "";

			// resultadotamizaje
			$this->resultadotamizaje->HrefValue = "";

			// tapon
			$this->tapon->HrefValue = "";

			// tipo
			$this->tipo->HrefValue = "";

			// repetirprueba
			$this->repetirprueba->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// id_apoderado
			$this->id_apoderado->HrefValue = "";

			// id_referencia
			$this->id_referencia->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// id
			$CurrentValue = $this->id->CurrentValue;
			$ViewValue = &$this->id->ViewValue;
			$ViewAttrs = &$this->id->ViewAttrs;
			$CellAttrs = &$this->id->CellAttrs;
			$HrefValue = &$this->id->HrefValue;
			$LinkAttrs = &$this->id->LinkAttrs;
			$this->Cell_Rendered($this->id, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fecha_tamizaje
			$CurrentValue = $this->fecha_tamizaje->CurrentValue;
			$ViewValue = &$this->fecha_tamizaje->ViewValue;
			$ViewAttrs = &$this->fecha_tamizaje->ViewAttrs;
			$CellAttrs = &$this->fecha_tamizaje->CellAttrs;
			$HrefValue = &$this->fecha_tamizaje->HrefValue;
			$LinkAttrs = &$this->fecha_tamizaje->LinkAttrs;
			$this->Cell_Rendered($this->fecha_tamizaje, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id_centro
			$CurrentValue = $this->id_centro->CurrentValue;
			$ViewValue = &$this->id_centro->ViewValue;
			$ViewAttrs = &$this->id_centro->ViewAttrs;
			$CellAttrs = &$this->id_centro->CellAttrs;
			$HrefValue = &$this->id_centro->HrefValue;
			$LinkAttrs = &$this->id_centro->LinkAttrs;
			$this->Cell_Rendered($this->id_centro, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// nombre
			$CurrentValue = $this->nombre->CurrentValue;
			$ViewValue = &$this->nombre->ViewValue;
			$ViewAttrs = &$this->nombre->ViewAttrs;
			$CellAttrs = &$this->nombre->CellAttrs;
			$HrefValue = &$this->nombre->HrefValue;
			$LinkAttrs = &$this->nombre->LinkAttrs;
			$this->Cell_Rendered($this->nombre, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// dias
			$CurrentValue = $this->dias->CurrentValue;
			$ViewValue = &$this->dias->ViewValue;
			$ViewAttrs = &$this->dias->ViewAttrs;
			$CellAttrs = &$this->dias->CellAttrs;
			$HrefValue = &$this->dias->HrefValue;
			$LinkAttrs = &$this->dias->LinkAttrs;
			$this->Cell_Rendered($this->dias, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// semanas
			$CurrentValue = $this->semanas->CurrentValue;
			$ViewValue = &$this->semanas->ViewValue;
			$ViewAttrs = &$this->semanas->ViewAttrs;
			$CellAttrs = &$this->semanas->CellAttrs;
			$HrefValue = &$this->semanas->HrefValue;
			$LinkAttrs = &$this->semanas->LinkAttrs;
			$this->Cell_Rendered($this->semanas, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// meses
			$CurrentValue = $this->meses->CurrentValue;
			$ViewValue = &$this->meses->ViewValue;
			$ViewAttrs = &$this->meses->ViewAttrs;
			$CellAttrs = &$this->meses->CellAttrs;
			$HrefValue = &$this->meses->HrefValue;
			$LinkAttrs = &$this->meses->LinkAttrs;
			$this->Cell_Rendered($this->meses, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// sexo
			$CurrentValue = $this->sexo->CurrentValue;
			$ViewValue = &$this->sexo->ViewValue;
			$ViewAttrs = &$this->sexo->ViewAttrs;
			$CellAttrs = &$this->sexo->CellAttrs;
			$HrefValue = &$this->sexo->HrefValue;
			$LinkAttrs = &$this->sexo->LinkAttrs;
			$this->Cell_Rendered($this->sexo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// discapacidad
			$CurrentValue = $this->discapacidad->CurrentValue;
			$ViewValue = &$this->discapacidad->ViewValue;
			$ViewAttrs = &$this->discapacidad->ViewAttrs;
			$CellAttrs = &$this->discapacidad->CellAttrs;
			$HrefValue = &$this->discapacidad->HrefValue;
			$LinkAttrs = &$this->discapacidad->LinkAttrs;
			$this->Cell_Rendered($this->discapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id_tipodiscapacidad
			$CurrentValue = $this->id_tipodiscapacidad->CurrentValue;
			$ViewValue = &$this->id_tipodiscapacidad->ViewValue;
			$ViewAttrs = &$this->id_tipodiscapacidad->ViewAttrs;
			$CellAttrs = &$this->id_tipodiscapacidad->CellAttrs;
			$HrefValue = &$this->id_tipodiscapacidad->HrefValue;
			$LinkAttrs = &$this->id_tipodiscapacidad->LinkAttrs;
			$this->Cell_Rendered($this->id_tipodiscapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// tipo
			$CurrentValue = $this->tipo->CurrentValue;
			$ViewValue = &$this->tipo->ViewValue;
			$ViewAttrs = &$this->tipo->ViewAttrs;
			$CellAttrs = &$this->tipo->CellAttrs;
			$HrefValue = &$this->tipo->HrefValue;
			$LinkAttrs = &$this->tipo->LinkAttrs;
			$this->Cell_Rendered($this->tipo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// id_apoderado
			$CurrentValue = $this->id_apoderado->CurrentValue;
			$ViewValue = &$this->id_apoderado->ViewValue;
			$ViewAttrs = &$this->id_apoderado->ViewAttrs;
			$CellAttrs = &$this->id_apoderado->CellAttrs;
			$HrefValue = &$this->id_apoderado->HrefValue;
			$LinkAttrs = &$this->id_apoderado->LinkAttrs;
			$this->Cell_Rendered($this->id_apoderado, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id_referencia
			$CurrentValue = $this->id_referencia->CurrentValue;
			$ViewValue = &$this->id_referencia->ViewValue;
			$ViewAttrs = &$this->id_referencia->ViewAttrs;
			$CellAttrs = &$this->id_referencia->CellAttrs;
			$HrefValue = &$this->id_referencia->HrefValue;
			$LinkAttrs = &$this->id_referencia->LinkAttrs;
			$this->Cell_Rendered($this->id_referencia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->id->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha_tamizaje->Visible) $this->DtlColumnCount += 1;
		if ($this->id_centro->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidopaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidomaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->nombre->Visible) $this->DtlColumnCount += 1;
		if ($this->ci->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha_nacimiento->Visible) $this->DtlColumnCount += 1;
		if ($this->dias->Visible) $this->DtlColumnCount += 1;
		if ($this->semanas->Visible) $this->DtlColumnCount += 1;
		if ($this->meses->Visible) $this->DtlColumnCount += 1;
		if ($this->sexo->Visible) $this->DtlColumnCount += 1;
		if ($this->discapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->id_tipodiscapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->resultado->Visible) $this->DtlColumnCount += 1;
		if ($this->resultadotamizaje->Visible) $this->DtlColumnCount += 1;
		if ($this->tapon->Visible) $this->DtlColumnCount += 1;
		if ($this->tipo->Visible) $this->DtlColumnCount += 1;
		if ($this->repetirprueba->Visible) $this->DtlColumnCount += 1;
		if ($this->observaciones->Visible) $this->DtlColumnCount += 1;
		if ($this->id_apoderado->Visible) $this->DtlColumnCount += 1;
		if ($this->id_referencia->Visible) $this->DtlColumnCount += 1;
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
			$this->id->setSort("");
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
if (!isset($neonatal_rpt)) $neonatal_rpt = new crneonatal_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$neonatal_rpt;

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
var neonatal_rpt = new ewr_Page("neonatal_rpt");

// Page properties
neonatal_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = neonatal_rpt.PageID;
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
<div id="gmp_neonatal" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->id->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id"><div class="neonatal_id"><span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id">
<?php if ($Page->SortUrl($Page->id) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_id">
			<span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_id" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha_tamizaje->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_tamizaje"><div class="neonatal_fecha_tamizaje"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_tamizaje->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_tamizaje">
<?php if ($Page->SortUrl($Page->fecha_tamizaje) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_fecha_tamizaje">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_tamizaje->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_fecha_tamizaje" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_tamizaje) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_tamizaje->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_tamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_tamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_centro->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_centro"><div class="neonatal_id_centro"><span class="ewTableHeaderCaption"><?php echo $Page->id_centro->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_centro">
<?php if ($Page->SortUrl($Page->id_centro) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_id_centro">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_centro->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_id_centro" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_centro) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_centro->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_centro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_centro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidopaterno"><div class="neonatal_apellidopaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidopaterno">
<?php if ($Page->SortUrl($Page->apellidopaterno) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_apellidopaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_apellidopaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidopaterno) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidomaterno"><div class="neonatal_apellidomaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidomaterno">
<?php if ($Page->SortUrl($Page->apellidomaterno) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_apellidomaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_apellidomaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidomaterno) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombre->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombre"><div class="neonatal_nombre"><span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombre">
<?php if ($Page->SortUrl($Page->nombre) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_nombre">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_nombre" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombre) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ci"><div class="neonatal_ci"><span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ci">
<?php if ($Page->SortUrl($Page->ci) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_ci">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_ci" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ci) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha_nacimiento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_nacimiento"><div class="neonatal_fecha_nacimiento"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_nacimiento">
<?php if ($Page->SortUrl($Page->fecha_nacimiento) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_fecha_nacimiento">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_fecha_nacimiento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_nacimiento) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->dias->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="dias"><div class="neonatal_dias"><span class="ewTableHeaderCaption"><?php echo $Page->dias->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="dias">
<?php if ($Page->SortUrl($Page->dias) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_dias">
			<span class="ewTableHeaderCaption"><?php echo $Page->dias->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_dias" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->dias) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->dias->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->dias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->dias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->semanas->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="semanas"><div class="neonatal_semanas"><span class="ewTableHeaderCaption"><?php echo $Page->semanas->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="semanas">
<?php if ($Page->SortUrl($Page->semanas) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_semanas">
			<span class="ewTableHeaderCaption"><?php echo $Page->semanas->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_semanas" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->semanas) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->semanas->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->semanas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->semanas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->meses->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="meses"><div class="neonatal_meses"><span class="ewTableHeaderCaption"><?php echo $Page->meses->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="meses">
<?php if ($Page->SortUrl($Page->meses) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_meses">
			<span class="ewTableHeaderCaption"><?php echo $Page->meses->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_meses" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->meses) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->meses->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->meses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->meses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sexo"><div class="neonatal_sexo"><span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sexo">
<?php if ($Page->SortUrl($Page->sexo) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_sexo">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_sexo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sexo) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="discapacidad"><div class="neonatal_discapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="discapacidad">
<?php if ($Page->SortUrl($Page->discapacidad) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_discapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_discapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->discapacidad) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_tipodiscapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_tipodiscapacidad"><div class="neonatal_id_tipodiscapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->id_tipodiscapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_tipodiscapacidad">
<?php if ($Page->SortUrl($Page->id_tipodiscapacidad) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_id_tipodiscapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_tipodiscapacidad->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_id_tipodiscapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_tipodiscapacidad) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_tipodiscapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->resultado->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="resultado"><div class="neonatal_resultado"><span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="resultado">
<?php if ($Page->SortUrl($Page->resultado) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_resultado">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_resultado" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->resultado) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->resultadotamizaje->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="resultadotamizaje"><div class="neonatal_resultadotamizaje"><span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="resultadotamizaje">
<?php if ($Page->SortUrl($Page->resultadotamizaje) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_resultadotamizaje">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_resultadotamizaje" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->resultadotamizaje) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->resultadotamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->resultadotamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tapon->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tapon"><div class="neonatal_tapon"><span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tapon">
<?php if ($Page->SortUrl($Page->tapon) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_tapon">
			<span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_tapon" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tapon) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tapon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tapon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipo"><div class="neonatal_tipo"><span class="ewTableHeaderCaption"><?php echo $Page->tipo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipo">
<?php if ($Page->SortUrl($Page->tipo) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_tipo">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipo->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_tipo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipo) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->repetirprueba->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="repetirprueba"><div class="neonatal_repetirprueba"><span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="repetirprueba">
<?php if ($Page->SortUrl($Page->repetirprueba) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_repetirprueba">
			<span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_repetirprueba" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->repetirprueba) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->repetirprueba->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->repetirprueba->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="observaciones"><div class="neonatal_observaciones"><span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="observaciones">
<?php if ($Page->SortUrl($Page->observaciones) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_observaciones">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_observaciones" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->observaciones) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_apoderado->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_apoderado"><div class="neonatal_id_apoderado"><span class="ewTableHeaderCaption"><?php echo $Page->id_apoderado->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_apoderado">
<?php if ($Page->SortUrl($Page->id_apoderado) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_id_apoderado">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_apoderado->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_id_apoderado" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_apoderado) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_apoderado->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_apoderado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_apoderado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_referencia->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_referencia"><div class="neonatal_id_referencia"><span class="ewTableHeaderCaption"><?php echo $Page->id_referencia->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_referencia">
<?php if ($Page->SortUrl($Page->id_referencia) == "") { ?>
		<div class="ewTableHeaderBtn neonatal_id_referencia">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_referencia->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer neonatal_id_referencia" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_referencia) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_referencia->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_referencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_referencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
<?php if ($Page->id->Visible) { ?>
		<td data-field="id"<?php echo $Page->id->CellAttributes() ?>>
<span<?php echo $Page->id->ViewAttributes() ?>><?php echo $Page->id->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fecha_tamizaje->Visible) { ?>
		<td data-field="fecha_tamizaje"<?php echo $Page->fecha_tamizaje->CellAttributes() ?>>
<span<?php echo $Page->fecha_tamizaje->ViewAttributes() ?>><?php echo $Page->fecha_tamizaje->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_centro->Visible) { ?>
		<td data-field="id_centro"<?php echo $Page->id_centro->CellAttributes() ?>>
<span<?php echo $Page->id_centro->ViewAttributes() ?>><?php echo $Page->id_centro->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
		<td data-field="apellidopaterno"<?php echo $Page->apellidopaterno->CellAttributes() ?>>
<span<?php echo $Page->apellidopaterno->ViewAttributes() ?>><?php echo $Page->apellidopaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
		<td data-field="apellidomaterno"<?php echo $Page->apellidomaterno->CellAttributes() ?>>
<span<?php echo $Page->apellidomaterno->ViewAttributes() ?>><?php echo $Page->apellidomaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombre->Visible) { ?>
		<td data-field="nombre"<?php echo $Page->nombre->CellAttributes() ?>>
<span<?php echo $Page->nombre->ViewAttributes() ?>><?php echo $Page->nombre->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
		<td data-field="ci"<?php echo $Page->ci->CellAttributes() ?>>
<span<?php echo $Page->ci->ViewAttributes() ?>><?php echo $Page->ci->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fecha_nacimiento->Visible) { ?>
		<td data-field="fecha_nacimiento"<?php echo $Page->fecha_nacimiento->CellAttributes() ?>>
<span<?php echo $Page->fecha_nacimiento->ViewAttributes() ?>><?php echo $Page->fecha_nacimiento->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->dias->Visible) { ?>
		<td data-field="dias"<?php echo $Page->dias->CellAttributes() ?>>
<span<?php echo $Page->dias->ViewAttributes() ?>><?php echo $Page->dias->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->semanas->Visible) { ?>
		<td data-field="semanas"<?php echo $Page->semanas->CellAttributes() ?>>
<span<?php echo $Page->semanas->ViewAttributes() ?>><?php echo $Page->semanas->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->meses->Visible) { ?>
		<td data-field="meses"<?php echo $Page->meses->CellAttributes() ?>>
<span<?php echo $Page->meses->ViewAttributes() ?>><?php echo $Page->meses->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
		<td data-field="sexo"<?php echo $Page->sexo->CellAttributes() ?>>
<span<?php echo $Page->sexo->ViewAttributes() ?>><?php echo $Page->sexo->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
		<td data-field="discapacidad"<?php echo $Page->discapacidad->CellAttributes() ?>>
<span<?php echo $Page->discapacidad->ViewAttributes() ?>><?php echo $Page->discapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_tipodiscapacidad->Visible) { ?>
		<td data-field="id_tipodiscapacidad"<?php echo $Page->id_tipodiscapacidad->CellAttributes() ?>>
<span<?php echo $Page->id_tipodiscapacidad->ViewAttributes() ?>><?php echo $Page->id_tipodiscapacidad->ListViewValue() ?></span></td>
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
<?php if ($Page->tipo->Visible) { ?>
		<td data-field="tipo"<?php echo $Page->tipo->CellAttributes() ?>>
<span<?php echo $Page->tipo->ViewAttributes() ?>><?php echo $Page->tipo->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->repetirprueba->Visible) { ?>
		<td data-field="repetirprueba"<?php echo $Page->repetirprueba->CellAttributes() ?>>
<span<?php echo $Page->repetirprueba->ViewAttributes() ?>><?php echo $Page->repetirprueba->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
		<td data-field="observaciones"<?php echo $Page->observaciones->CellAttributes() ?>>
<span<?php echo $Page->observaciones->ViewAttributes() ?>><?php echo $Page->observaciones->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_apoderado->Visible) { ?>
		<td data-field="id_apoderado"<?php echo $Page->id_apoderado->CellAttributes() ?>>
<span<?php echo $Page->id_apoderado->ViewAttributes() ?>><?php echo $Page->id_apoderado->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_referencia->Visible) { ?>
		<td data-field="id_referencia"<?php echo $Page->id_referencia->CellAttributes() ?>>
<span<?php echo $Page->id_referencia->ViewAttributes() ?>><?php echo $Page->id_referencia->ListViewValue() ?></span></td>
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
<div id="gmp_neonatal" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "neonatalrptpager.php" ?>
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