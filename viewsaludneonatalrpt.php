<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewsaludneonatalrptinfo.php" ?>
<?php

//
// Page class
//

$viewsaludneonatal_rpt = NULL; // Initialize page object first

class crviewsaludneonatal_rpt extends crviewsaludneonatal {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewsaludneonatal_rpt';

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

		// Table object (viewsaludneonatal)
		if (!isset($GLOBALS["viewsaludneonatal"])) {
			$GLOBALS["viewsaludneonatal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewsaludneonatal"];
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
			define("EWR_TABLE_NAME", 'viewsaludneonatal', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewsaludneonatalrpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewsaludneonatal');
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
		$this->fecha_tamizaje->PlaceHolder = $this->fecha_tamizaje->FldCaption();
		$this->fecha_nacimiento->PlaceHolder = $this->fecha_nacimiento->FldCaption();
		$this->dias->PlaceHolder = $this->dias->FldCaption();
		$this->sexo->PlaceHolder = $this->sexo->FldCaption();
		$this->resultadotamizaje->PlaceHolder = $this->resultadotamizaje->FldCaption();
		$this->repetirprueba->PlaceHolder = $this->repetirprueba->FldCaption();
		$this->parentesco->PlaceHolder = $this->parentesco->FldCaption();
		$this->discapacidad->PlaceHolder = $this->discapacidad->FldCaption();
		$this->tipodiscapacidad->PlaceHolder = $this->tipodiscapacidad->FldCaption();

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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewsaludneonatal\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewsaludneonatal',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewsaludneonatalrpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewsaludneonatalrpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewsaludneonatalrpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->fecha_tamizaje->SetVisibility();
		$this->ci->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->dias->SetVisibility();
		$this->semanas->SetVisibility();
		$this->meses->SetVisibility();
		$this->sexo->SetVisibility();
		$this->resultado->SetVisibility();
		$this->resultadotamizaje->SetVisibility();
		$this->tapon->SetVisibility();
		$this->repetirprueba->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->parentesco->SetVisibility();
		$this->nombrescompleto->SetVisibility();
		$this->nombreinstitucion->SetVisibility();
		$this->nombreneonatal->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapacidad->SetVisibility();
		$this->tipotapo->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 20;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->fecha_tamizaje->SelectionList = "";
		$this->fecha_tamizaje->DefaultSelectionList = "";
		$this->fecha_tamizaje->ValueList = "";
		$this->ci->SelectionList = "";
		$this->ci->DefaultSelectionList = "";
		$this->ci->ValueList = "";
		$this->fecha_nacimiento->SelectionList = "";
		$this->fecha_nacimiento->DefaultSelectionList = "";
		$this->fecha_nacimiento->ValueList = "";
		$this->dias->SelectionList = "";
		$this->dias->DefaultSelectionList = "";
		$this->dias->ValueList = "";
		$this->semanas->SelectionList = "";
		$this->semanas->DefaultSelectionList = "";
		$this->semanas->ValueList = "";
		$this->meses->SelectionList = "";
		$this->meses->DefaultSelectionList = "";
		$this->meses->ValueList = "";
		$this->sexo->SelectionList = "";
		$this->sexo->DefaultSelectionList = "";
		$this->sexo->ValueList = "";
		$this->resultado->SelectionList = "";
		$this->resultado->DefaultSelectionList = "";
		$this->resultado->ValueList = "";
		$this->resultadotamizaje->SelectionList = "";
		$this->resultadotamizaje->DefaultSelectionList = "";
		$this->resultadotamizaje->ValueList = "";
		$this->tapon->SelectionList = "";
		$this->tapon->DefaultSelectionList = "";
		$this->tapon->ValueList = "";
		$this->repetirprueba->SelectionList = "";
		$this->repetirprueba->DefaultSelectionList = "";
		$this->repetirprueba->ValueList = "";
		$this->observaciones->SelectionList = "";
		$this->observaciones->DefaultSelectionList = "";
		$this->observaciones->ValueList = "";
		$this->parentesco->SelectionList = "";
		$this->parentesco->DefaultSelectionList = "";
		$this->parentesco->ValueList = "";
		$this->nombrescompleto->SelectionList = "";
		$this->nombrescompleto->DefaultSelectionList = "";
		$this->nombrescompleto->ValueList = "";
		$this->nombreinstitucion->SelectionList = "";
		$this->nombreinstitucion->DefaultSelectionList = "";
		$this->nombreinstitucion->ValueList = "";
		$this->discapacidad->SelectionList = "";
		$this->discapacidad->DefaultSelectionList = "";
		$this->discapacidad->ValueList = "";
		$this->tipodiscapacidad->SelectionList = "";
		$this->tipodiscapacidad->DefaultSelectionList = "";
		$this->tipodiscapacidad->ValueList = "";
		$this->tipotapo->SelectionList = "";
		$this->tipotapo->DefaultSelectionList = "";
		$this->tipotapo->ValueList = "";

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
				$this->FirstRowData['fecha_tamizaje'] = ewr_Conv($rs->fields('fecha_tamizaje'), 133);
				$this->FirstRowData['ci'] = ewr_Conv($rs->fields('ci'), 200);
				$this->FirstRowData['fecha_nacimiento'] = ewr_Conv($rs->fields('fecha_nacimiento'), 133);
				$this->FirstRowData['dias'] = ewr_Conv($rs->fields('dias'), 200);
				$this->FirstRowData['semanas'] = ewr_Conv($rs->fields('semanas'), 200);
				$this->FirstRowData['meses'] = ewr_Conv($rs->fields('meses'), 200);
				$this->FirstRowData['sexo'] = ewr_Conv($rs->fields('sexo'), 200);
				$this->FirstRowData['resultado'] = ewr_Conv($rs->fields('resultado'), 200);
				$this->FirstRowData['resultadotamizaje'] = ewr_Conv($rs->fields('resultadotamizaje'), 200);
				$this->FirstRowData['tapon'] = ewr_Conv($rs->fields('tapon'), 200);
				$this->FirstRowData['repetirprueba'] = ewr_Conv($rs->fields('repetirprueba'), 200);
				$this->FirstRowData['observaciones'] = ewr_Conv($rs->fields('observaciones'), 200);
				$this->FirstRowData['parentesco'] = ewr_Conv($rs->fields('parentesco'), 200);
				$this->FirstRowData['nombrescompleto'] = ewr_Conv($rs->fields('nombrescompleto'), 200);
				$this->FirstRowData['nombreinstitucion'] = ewr_Conv($rs->fields('nombreinstitucion'), 200);
				$this->FirstRowData['discapacidad'] = ewr_Conv($rs->fields('discapacidad'), 200);
				$this->FirstRowData['tipodiscapacidad'] = ewr_Conv($rs->fields('tipodiscapacidad'), 200);
				$this->FirstRowData['tipotapo'] = ewr_Conv($rs->fields('tipotapo'), 200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->fecha_tamizaje->setDbValue($rs->fields('fecha_tamizaje'));
			$this->ci->setDbValue($rs->fields('ci'));
			$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
			$this->dias->setDbValue($rs->fields('dias'));
			$this->semanas->setDbValue($rs->fields('semanas'));
			$this->meses->setDbValue($rs->fields('meses'));
			$this->sexo->setDbValue($rs->fields('sexo'));
			$this->resultado->setDbValue($rs->fields('resultado'));
			$this->resultadotamizaje->setDbValue($rs->fields('resultadotamizaje'));
			$this->tapon->setDbValue($rs->fields('tapon'));
			$this->repetirprueba->setDbValue($rs->fields('repetirprueba'));
			$this->observaciones->setDbValue($rs->fields('observaciones'));
			$this->parentesco->setDbValue($rs->fields('parentesco'));
			$this->nombrescompleto->setDbValue($rs->fields('nombrescompleto'));
			$this->nombreinstitucion->setDbValue($rs->fields('nombreinstitucion'));
			$this->nombreneonatal->setDbValue($rs->fields('nombreneonatal'));
			$this->discapacidad->setDbValue($rs->fields('discapacidad'));
			$this->tipodiscapacidad->setDbValue($rs->fields('tipodiscapacidad'));
			$this->tipotapo->setDbValue($rs->fields('tipotapo'));
			$this->Val[1] = $this->fecha_tamizaje->CurrentValue;
			$this->Val[2] = $this->ci->CurrentValue;
			$this->Val[3] = $this->fecha_nacimiento->CurrentValue;
			$this->Val[4] = $this->dias->CurrentValue;
			$this->Val[5] = $this->semanas->CurrentValue;
			$this->Val[6] = $this->meses->CurrentValue;
			$this->Val[7] = $this->sexo->CurrentValue;
			$this->Val[8] = $this->resultado->CurrentValue;
			$this->Val[9] = $this->resultadotamizaje->CurrentValue;
			$this->Val[10] = $this->tapon->CurrentValue;
			$this->Val[11] = $this->repetirprueba->CurrentValue;
			$this->Val[12] = $this->observaciones->CurrentValue;
			$this->Val[13] = $this->parentesco->CurrentValue;
			$this->Val[14] = $this->nombrescompleto->CurrentValue;
			$this->Val[15] = $this->nombreinstitucion->CurrentValue;
			$this->Val[16] = $this->nombreneonatal->CurrentValue;
			$this->Val[17] = $this->discapacidad->CurrentValue;
			$this->Val[18] = $this->tipodiscapacidad->CurrentValue;
			$this->Val[19] = $this->tipotapo->CurrentValue;
		} else {
			$this->fecha_tamizaje->setDbValue("");
			$this->ci->setDbValue("");
			$this->fecha_nacimiento->setDbValue("");
			$this->dias->setDbValue("");
			$this->semanas->setDbValue("");
			$this->meses->setDbValue("");
			$this->sexo->setDbValue("");
			$this->resultado->setDbValue("");
			$this->resultadotamizaje->setDbValue("");
			$this->tapon->setDbValue("");
			$this->repetirprueba->setDbValue("");
			$this->observaciones->setDbValue("");
			$this->parentesco->setDbValue("");
			$this->nombrescompleto->setDbValue("");
			$this->nombreinstitucion->setDbValue("");
			$this->nombreneonatal->setDbValue("");
			$this->discapacidad->setDbValue("");
			$this->tipodiscapacidad->setDbValue("");
			$this->tipotapo->setDbValue("");
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
			// Build distinct values for fecha_tamizaje

			if ($popupname == 'viewsaludneonatal_fecha_tamizaje') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha_tamizaje, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->fecha_tamizaje->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->fecha_tamizaje->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->fecha_tamizaje->setDbValue($rswrk->fields[0]);
					$this->fecha_tamizaje->ViewValue = @$rswrk->fields[1];
					if (is_null($this->fecha_tamizaje->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->fecha_tamizaje->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->fecha_tamizaje->ValueList, $this->fecha_tamizaje->CurrentValue, $this->fecha_tamizaje->ViewValue, FALSE, $this->fecha_tamizaje->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->fecha_tamizaje->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->fecha_tamizaje->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->fecha_tamizaje;
			}

			// Build distinct values for ci
			if ($popupname == 'viewsaludneonatal_ci') {
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

			// Build distinct values for fecha_nacimiento
			if ($popupname == 'viewsaludneonatal_fecha_nacimiento') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha_nacimiento, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->fecha_nacimiento->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->fecha_nacimiento->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->fecha_nacimiento->setDbValue($rswrk->fields[0]);
					$this->fecha_nacimiento->ViewValue = @$rswrk->fields[1];
					if (is_null($this->fecha_nacimiento->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->fecha_nacimiento->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->fecha_nacimiento->ValueList, $this->fecha_nacimiento->CurrentValue, $this->fecha_nacimiento->ViewValue, FALSE, $this->fecha_nacimiento->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->fecha_nacimiento->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->fecha_nacimiento->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->fecha_nacimiento;
			}

			// Build distinct values for dias
			if ($popupname == 'viewsaludneonatal_dias') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->dias, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->dias->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->dias->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->dias->setDbValue($rswrk->fields[0]);
					$this->dias->ViewValue = @$rswrk->fields[1];
					if (is_null($this->dias->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->dias->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->dias->ValueList, $this->dias->CurrentValue, $this->dias->ViewValue, FALSE, $this->dias->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->dias->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->dias->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->dias;
			}

			// Build distinct values for semanas
			if ($popupname == 'viewsaludneonatal_semanas') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->semanas, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->semanas->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->semanas->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->semanas->setDbValue($rswrk->fields[0]);
					$this->semanas->ViewValue = @$rswrk->fields[1];
					if (is_null($this->semanas->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->semanas->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->semanas->ValueList, $this->semanas->CurrentValue, $this->semanas->ViewValue, FALSE, $this->semanas->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->semanas->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->semanas->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->semanas;
			}

			// Build distinct values for meses
			if ($popupname == 'viewsaludneonatal_meses') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->meses, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->meses->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->meses->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->meses->setDbValue($rswrk->fields[0]);
					$this->meses->ViewValue = @$rswrk->fields[1];
					if (is_null($this->meses->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->meses->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->meses->ValueList, $this->meses->CurrentValue, $this->meses->ViewValue, FALSE, $this->meses->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->meses->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->meses->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->meses;
			}

			// Build distinct values for sexo
			if ($popupname == 'viewsaludneonatal_sexo') {
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

			// Build distinct values for resultado
			if ($popupname == 'viewsaludneonatal_resultado') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->resultado, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->resultado->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->resultado->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->resultado->setDbValue($rswrk->fields[0]);
					$this->resultado->ViewValue = @$rswrk->fields[1];
					if (is_null($this->resultado->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->resultado->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->resultado->ValueList, $this->resultado->CurrentValue, $this->resultado->ViewValue, FALSE, $this->resultado->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->resultado->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->resultado->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->resultado;
			}

			// Build distinct values for resultadotamizaje
			if ($popupname == 'viewsaludneonatal_resultadotamizaje') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->resultadotamizaje, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->resultadotamizaje->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->resultadotamizaje->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->resultadotamizaje->setDbValue($rswrk->fields[0]);
					$this->resultadotamizaje->ViewValue = @$rswrk->fields[1];
					if (is_null($this->resultadotamizaje->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->resultadotamizaje->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->resultadotamizaje->ValueList, $this->resultadotamizaje->CurrentValue, $this->resultadotamizaje->ViewValue, FALSE, $this->resultadotamizaje->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->resultadotamizaje->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->resultadotamizaje->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->resultadotamizaje;
			}

			// Build distinct values for tapon
			if ($popupname == 'viewsaludneonatal_tapon') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->tapon, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->tapon->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->tapon->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->tapon->setDbValue($rswrk->fields[0]);
					$this->tapon->ViewValue = @$rswrk->fields[1];
					if (is_null($this->tapon->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->tapon->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->tapon->ValueList, $this->tapon->CurrentValue, $this->tapon->ViewValue, FALSE, $this->tapon->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->tapon->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->tapon->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->tapon;
			}

			// Build distinct values for repetirprueba
			if ($popupname == 'viewsaludneonatal_repetirprueba') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->repetirprueba, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->repetirprueba->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->repetirprueba->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->repetirprueba->setDbValue($rswrk->fields[0]);
					$this->repetirprueba->ViewValue = @$rswrk->fields[1];
					if (is_null($this->repetirprueba->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->repetirprueba->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->repetirprueba->ValueList, $this->repetirprueba->CurrentValue, $this->repetirprueba->ViewValue, FALSE, $this->repetirprueba->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->repetirprueba->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->repetirprueba->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->repetirprueba;
			}

			// Build distinct values for observaciones
			if ($popupname == 'viewsaludneonatal_observaciones') {
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

			// Build distinct values for parentesco
			if ($popupname == 'viewsaludneonatal_parentesco') {
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

			// Build distinct values for nombrescompleto
			if ($popupname == 'viewsaludneonatal_nombrescompleto') {
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

			// Build distinct values for nombreinstitucion
			if ($popupname == 'viewsaludneonatal_nombreinstitucion') {
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

			// Build distinct values for discapacidad
			if ($popupname == 'viewsaludneonatal_discapacidad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->discapacidad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->discapacidad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->discapacidad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->discapacidad->setDbValue($rswrk->fields[0]);
					$this->discapacidad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->discapacidad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->discapacidad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->discapacidad->ValueList, $this->discapacidad->CurrentValue, $this->discapacidad->ViewValue, FALSE, $this->discapacidad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->discapacidad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->discapacidad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->discapacidad;
			}

			// Build distinct values for tipodiscapacidad
			if ($popupname == 'viewsaludneonatal_tipodiscapacidad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->tipodiscapacidad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->tipodiscapacidad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->tipodiscapacidad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->tipodiscapacidad->setDbValue($rswrk->fields[0]);
					$this->tipodiscapacidad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->tipodiscapacidad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->tipodiscapacidad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->tipodiscapacidad->ValueList, $this->tipodiscapacidad->CurrentValue, $this->tipodiscapacidad->ViewValue, FALSE, $this->tipodiscapacidad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->tipodiscapacidad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->tipodiscapacidad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->tipodiscapacidad;
			}

			// Build distinct values for tipotapo
			if ($popupname == 'viewsaludneonatal_tipotapo') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->tipotapo, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->tipotapo->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->tipotapo->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->tipotapo->setDbValue($rswrk->fields[0]);
					$this->tipotapo->ViewValue = @$rswrk->fields[1];
					if (is_null($this->tipotapo->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->tipotapo->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->tipotapo->ValueList, $this->tipotapo->CurrentValue, $this->tipotapo->ViewValue, FALSE, $this->tipotapo->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->tipotapo->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->tipotapo->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->tipotapo;
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
				$this->ClearSessionSelection('fecha_tamizaje');
				$this->ClearSessionSelection('ci');
				$this->ClearSessionSelection('fecha_nacimiento');
				$this->ClearSessionSelection('dias');
				$this->ClearSessionSelection('semanas');
				$this->ClearSessionSelection('meses');
				$this->ClearSessionSelection('sexo');
				$this->ClearSessionSelection('resultado');
				$this->ClearSessionSelection('resultadotamizaje');
				$this->ClearSessionSelection('tapon');
				$this->ClearSessionSelection('repetirprueba');
				$this->ClearSessionSelection('observaciones');
				$this->ClearSessionSelection('parentesco');
				$this->ClearSessionSelection('nombrescompleto');
				$this->ClearSessionSelection('nombreinstitucion');
				$this->ClearSessionSelection('discapacidad');
				$this->ClearSessionSelection('tipodiscapacidad');
				$this->ClearSessionSelection('tipotapo');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get fecha_tamizaje selected values

		if (is_array(@$_SESSION["sel_viewsaludneonatal_fecha_tamizaje"])) {
			$this->LoadSelectionFromSession('fecha_tamizaje');
		} elseif (@$_SESSION["sel_viewsaludneonatal_fecha_tamizaje"] == EWR_INIT_VALUE) { // Select all
			$this->fecha_tamizaje->SelectionList = "";
		}

		// Get ci selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_ci"])) {
			$this->LoadSelectionFromSession('ci');
		} elseif (@$_SESSION["sel_viewsaludneonatal_ci"] == EWR_INIT_VALUE) { // Select all
			$this->ci->SelectionList = "";
		}

		// Get fecha_nacimiento selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_fecha_nacimiento"])) {
			$this->LoadSelectionFromSession('fecha_nacimiento');
		} elseif (@$_SESSION["sel_viewsaludneonatal_fecha_nacimiento"] == EWR_INIT_VALUE) { // Select all
			$this->fecha_nacimiento->SelectionList = "";
		}

		// Get dias selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_dias"])) {
			$this->LoadSelectionFromSession('dias');
		} elseif (@$_SESSION["sel_viewsaludneonatal_dias"] == EWR_INIT_VALUE) { // Select all
			$this->dias->SelectionList = "";
		}

		// Get semanas selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_semanas"])) {
			$this->LoadSelectionFromSession('semanas');
		} elseif (@$_SESSION["sel_viewsaludneonatal_semanas"] == EWR_INIT_VALUE) { // Select all
			$this->semanas->SelectionList = "";
		}

		// Get meses selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_meses"])) {
			$this->LoadSelectionFromSession('meses');
		} elseif (@$_SESSION["sel_viewsaludneonatal_meses"] == EWR_INIT_VALUE) { // Select all
			$this->meses->SelectionList = "";
		}

		// Get sexo selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_sexo"])) {
			$this->LoadSelectionFromSession('sexo');
		} elseif (@$_SESSION["sel_viewsaludneonatal_sexo"] == EWR_INIT_VALUE) { // Select all
			$this->sexo->SelectionList = "";
		}

		// Get resultado selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_resultado"])) {
			$this->LoadSelectionFromSession('resultado');
		} elseif (@$_SESSION["sel_viewsaludneonatal_resultado"] == EWR_INIT_VALUE) { // Select all
			$this->resultado->SelectionList = "";
		}

		// Get resultadotamizaje selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_resultadotamizaje"])) {
			$this->LoadSelectionFromSession('resultadotamizaje');
		} elseif (@$_SESSION["sel_viewsaludneonatal_resultadotamizaje"] == EWR_INIT_VALUE) { // Select all
			$this->resultadotamizaje->SelectionList = "";
		}

		// Get tapon selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_tapon"])) {
			$this->LoadSelectionFromSession('tapon');
		} elseif (@$_SESSION["sel_viewsaludneonatal_tapon"] == EWR_INIT_VALUE) { // Select all
			$this->tapon->SelectionList = "";
		}

		// Get repetirprueba selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_repetirprueba"])) {
			$this->LoadSelectionFromSession('repetirprueba');
		} elseif (@$_SESSION["sel_viewsaludneonatal_repetirprueba"] == EWR_INIT_VALUE) { // Select all
			$this->repetirprueba->SelectionList = "";
		}

		// Get observaciones selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_observaciones"])) {
			$this->LoadSelectionFromSession('observaciones');
		} elseif (@$_SESSION["sel_viewsaludneonatal_observaciones"] == EWR_INIT_VALUE) { // Select all
			$this->observaciones->SelectionList = "";
		}

		// Get parentesco selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_parentesco"])) {
			$this->LoadSelectionFromSession('parentesco');
		} elseif (@$_SESSION["sel_viewsaludneonatal_parentesco"] == EWR_INIT_VALUE) { // Select all
			$this->parentesco->SelectionList = "";
		}

		// Get nombrescompleto selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_nombrescompleto"])) {
			$this->LoadSelectionFromSession('nombrescompleto');
		} elseif (@$_SESSION["sel_viewsaludneonatal_nombrescompleto"] == EWR_INIT_VALUE) { // Select all
			$this->nombrescompleto->SelectionList = "";
		}

		// Get nombreinstitucion selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_nombreinstitucion"])) {
			$this->LoadSelectionFromSession('nombreinstitucion');
		} elseif (@$_SESSION["sel_viewsaludneonatal_nombreinstitucion"] == EWR_INIT_VALUE) { // Select all
			$this->nombreinstitucion->SelectionList = "";
		}

		// Get discapacidad selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_discapacidad"])) {
			$this->LoadSelectionFromSession('discapacidad');
		} elseif (@$_SESSION["sel_viewsaludneonatal_discapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->discapacidad->SelectionList = "";
		}

		// Get tipodiscapacidad selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_tipodiscapacidad"])) {
			$this->LoadSelectionFromSession('tipodiscapacidad');
		} elseif (@$_SESSION["sel_viewsaludneonatal_tipodiscapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->tipodiscapacidad->SelectionList = "";
		}

		// Get tipotapo selected values
		if (is_array(@$_SESSION["sel_viewsaludneonatal_tipotapo"])) {
			$this->LoadSelectionFromSession('tipotapo');
		} elseif (@$_SESSION["sel_viewsaludneonatal_tipotapo"] == EWR_INIT_VALUE) { // Select all
			$this->tipotapo->SelectionList = "";
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

			// fecha_tamizaje
			$this->fecha_tamizaje->HrefValue = "";

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

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// nombreneonatal
			$this->nombreneonatal->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->HrefValue = "";

			// tipotapo
			$this->tipotapo->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// fecha_tamizaje
			$this->fecha_tamizaje->ViewValue = $this->fecha_tamizaje->CurrentValue;
			$this->fecha_tamizaje->ViewValue = ewr_FormatDateTime($this->fecha_tamizaje->ViewValue, 0);
			$this->fecha_tamizaje->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

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

			// nombreinstitucion
			$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
			$this->nombreinstitucion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreneonatal
			$this->nombreneonatal->ViewValue = $this->nombreneonatal->CurrentValue;
			$this->nombreneonatal->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// discapacidad
			$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
			$this->discapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipodiscapacidad
			$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->CurrentValue;
			$this->tipodiscapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipotapo
			$this->tipotapo->ViewValue = $this->tipotapo->CurrentValue;
			$this->tipotapo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha_tamizaje
			$this->fecha_tamizaje->HrefValue = "";

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

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// nombreneonatal
			$this->nombreneonatal->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->HrefValue = "";

			// tipotapo
			$this->tipotapo->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// fecha_tamizaje
			$CurrentValue = $this->fecha_tamizaje->CurrentValue;
			$ViewValue = &$this->fecha_tamizaje->ViewValue;
			$ViewAttrs = &$this->fecha_tamizaje->ViewAttrs;
			$CellAttrs = &$this->fecha_tamizaje->CellAttrs;
			$HrefValue = &$this->fecha_tamizaje->HrefValue;
			$LinkAttrs = &$this->fecha_tamizaje->LinkAttrs;
			$this->Cell_Rendered($this->fecha_tamizaje, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// nombreinstitucion
			$CurrentValue = $this->nombreinstitucion->CurrentValue;
			$ViewValue = &$this->nombreinstitucion->ViewValue;
			$ViewAttrs = &$this->nombreinstitucion->ViewAttrs;
			$CellAttrs = &$this->nombreinstitucion->CellAttrs;
			$HrefValue = &$this->nombreinstitucion->HrefValue;
			$LinkAttrs = &$this->nombreinstitucion->LinkAttrs;
			$this->Cell_Rendered($this->nombreinstitucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombreneonatal
			$CurrentValue = $this->nombreneonatal->CurrentValue;
			$ViewValue = &$this->nombreneonatal->ViewValue;
			$ViewAttrs = &$this->nombreneonatal->ViewAttrs;
			$CellAttrs = &$this->nombreneonatal->CellAttrs;
			$HrefValue = &$this->nombreneonatal->HrefValue;
			$LinkAttrs = &$this->nombreneonatal->LinkAttrs;
			$this->Cell_Rendered($this->nombreneonatal, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// discapacidad
			$CurrentValue = $this->discapacidad->CurrentValue;
			$ViewValue = &$this->discapacidad->ViewValue;
			$ViewAttrs = &$this->discapacidad->ViewAttrs;
			$CellAttrs = &$this->discapacidad->CellAttrs;
			$HrefValue = &$this->discapacidad->HrefValue;
			$LinkAttrs = &$this->discapacidad->LinkAttrs;
			$this->Cell_Rendered($this->discapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipodiscapacidad
			$CurrentValue = $this->tipodiscapacidad->CurrentValue;
			$ViewValue = &$this->tipodiscapacidad->ViewValue;
			$ViewAttrs = &$this->tipodiscapacidad->ViewAttrs;
			$CellAttrs = &$this->tipodiscapacidad->CellAttrs;
			$HrefValue = &$this->tipodiscapacidad->HrefValue;
			$LinkAttrs = &$this->tipodiscapacidad->LinkAttrs;
			$this->Cell_Rendered($this->tipodiscapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipotapo
			$CurrentValue = $this->tipotapo->CurrentValue;
			$ViewValue = &$this->tipotapo->ViewValue;
			$ViewAttrs = &$this->tipotapo->ViewAttrs;
			$CellAttrs = &$this->tipotapo->CellAttrs;
			$HrefValue = &$this->tipotapo->HrefValue;
			$LinkAttrs = &$this->tipotapo->LinkAttrs;
			$this->Cell_Rendered($this->tipotapo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->fecha_tamizaje->Visible) $this->DtlColumnCount += 1;
		if ($this->ci->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha_nacimiento->Visible) $this->DtlColumnCount += 1;
		if ($this->dias->Visible) $this->DtlColumnCount += 1;
		if ($this->semanas->Visible) $this->DtlColumnCount += 1;
		if ($this->meses->Visible) $this->DtlColumnCount += 1;
		if ($this->sexo->Visible) $this->DtlColumnCount += 1;
		if ($this->resultado->Visible) $this->DtlColumnCount += 1;
		if ($this->resultadotamizaje->Visible) $this->DtlColumnCount += 1;
		if ($this->tapon->Visible) $this->DtlColumnCount += 1;
		if ($this->repetirprueba->Visible) $this->DtlColumnCount += 1;
		if ($this->observaciones->Visible) $this->DtlColumnCount += 1;
		if ($this->parentesco->Visible) $this->DtlColumnCount += 1;
		if ($this->nombrescompleto->Visible) $this->DtlColumnCount += 1;
		if ($this->nombreinstitucion->Visible) $this->DtlColumnCount += 1;
		if ($this->nombreneonatal->Visible) $this->DtlColumnCount += 1;
		if ($this->discapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->tipodiscapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->tipotapo->Visible) $this->DtlColumnCount += 1;
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

			// Clear extended filter for field fecha_tamizaje
			if ($this->ClearExtFilter == 'viewsaludneonatal_fecha_tamizaje')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'fecha_tamizaje');

			// Clear extended filter for field fecha_nacimiento
			if ($this->ClearExtFilter == 'viewsaludneonatal_fecha_nacimiento')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'fecha_nacimiento');

			// Clear extended filter for field dias
			if ($this->ClearExtFilter == 'viewsaludneonatal_dias')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'dias');

			// Clear extended filter for field sexo
			if ($this->ClearExtFilter == 'viewsaludneonatal_sexo')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'sexo');

			// Clear extended filter for field resultadotamizaje
			if ($this->ClearExtFilter == 'viewsaludneonatal_resultadotamizaje')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'resultadotamizaje');

			// Clear extended filter for field repetirprueba
			if ($this->ClearExtFilter == 'viewsaludneonatal_repetirprueba')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'repetirprueba');

			// Clear extended filter for field parentesco
			if ($this->ClearExtFilter == 'viewsaludneonatal_parentesco')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'parentesco');

			// Clear extended filter for field discapacidad
			if ($this->ClearExtFilter == 'viewsaludneonatal_discapacidad')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'discapacidad');

			// Clear extended filter for field tipodiscapacidad
			if ($this->ClearExtFilter == 'viewsaludneonatal_tipodiscapacidad')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'tipodiscapacidad');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionFilterValues($this->fecha_tamizaje->SearchValue, $this->fecha_tamizaje->SearchOperator, $this->fecha_tamizaje->SearchCondition, $this->fecha_tamizaje->SearchValue2, $this->fecha_tamizaje->SearchOperator2, 'fecha_tamizaje'); // Field fecha_tamizaje
			$this->SetSessionFilterValues($this->fecha_nacimiento->SearchValue, $this->fecha_nacimiento->SearchOperator, $this->fecha_nacimiento->SearchCondition, $this->fecha_nacimiento->SearchValue2, $this->fecha_nacimiento->SearchOperator2, 'fecha_nacimiento'); // Field fecha_nacimiento
			$this->SetSessionFilterValues($this->dias->SearchValue, $this->dias->SearchOperator, $this->dias->SearchCondition, $this->dias->SearchValue2, $this->dias->SearchOperator2, 'dias'); // Field dias
			$this->SetSessionFilterValues($this->sexo->SearchValue, $this->sexo->SearchOperator, $this->sexo->SearchCondition, $this->sexo->SearchValue2, $this->sexo->SearchOperator2, 'sexo'); // Field sexo
			$this->SetSessionFilterValues($this->resultadotamizaje->SearchValue, $this->resultadotamizaje->SearchOperator, $this->resultadotamizaje->SearchCondition, $this->resultadotamizaje->SearchValue2, $this->resultadotamizaje->SearchOperator2, 'resultadotamizaje'); // Field resultadotamizaje
			$this->SetSessionFilterValues($this->repetirprueba->SearchValue, $this->repetirprueba->SearchOperator, $this->repetirprueba->SearchCondition, $this->repetirprueba->SearchValue2, $this->repetirprueba->SearchOperator2, 'repetirprueba'); // Field repetirprueba
			$this->SetSessionFilterValues($this->parentesco->SearchValue, $this->parentesco->SearchOperator, $this->parentesco->SearchCondition, $this->parentesco->SearchValue2, $this->parentesco->SearchOperator2, 'parentesco'); // Field parentesco
			$this->SetSessionFilterValues($this->discapacidad->SearchValue, $this->discapacidad->SearchOperator, $this->discapacidad->SearchCondition, $this->discapacidad->SearchValue2, $this->discapacidad->SearchOperator2, 'discapacidad'); // Field discapacidad
			$this->SetSessionFilterValues($this->tipodiscapacidad->SearchValue, $this->tipodiscapacidad->SearchOperator, $this->tipodiscapacidad->SearchCondition, $this->tipodiscapacidad->SearchValue2, $this->tipodiscapacidad->SearchOperator2, 'tipodiscapacidad'); // Field tipodiscapacidad

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field fecha_tamizaje
			if ($this->GetFilterValues($this->fecha_tamizaje)) {
				$bSetupFilter = TRUE;
			}

			// Field fecha_nacimiento
			if ($this->GetFilterValues($this->fecha_nacimiento)) {
				$bSetupFilter = TRUE;
			}

			// Field dias
			if ($this->GetFilterValues($this->dias)) {
				$bSetupFilter = TRUE;
			}

			// Field sexo
			if ($this->GetFilterValues($this->sexo)) {
				$bSetupFilter = TRUE;
			}

			// Field resultadotamizaje
			if ($this->GetFilterValues($this->resultadotamizaje)) {
				$bSetupFilter = TRUE;
			}

			// Field repetirprueba
			if ($this->GetFilterValues($this->repetirprueba)) {
				$bSetupFilter = TRUE;
			}

			// Field parentesco
			if ($this->GetFilterValues($this->parentesco)) {
				$bSetupFilter = TRUE;
			}

			// Field discapacidad
			if ($this->GetFilterValues($this->discapacidad)) {
				$bSetupFilter = TRUE;
			}

			// Field tipodiscapacidad
			if ($this->GetFilterValues($this->tipodiscapacidad)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionFilterValues($this->fecha_tamizaje); // Field fecha_tamizaje
			$this->GetSessionFilterValues($this->fecha_nacimiento); // Field fecha_nacimiento
			$this->GetSessionFilterValues($this->dias); // Field dias
			$this->GetSessionFilterValues($this->sexo); // Field sexo
			$this->GetSessionFilterValues($this->resultadotamizaje); // Field resultadotamizaje
			$this->GetSessionFilterValues($this->repetirprueba); // Field repetirprueba
			$this->GetSessionFilterValues($this->parentesco); // Field parentesco
			$this->GetSessionFilterValues($this->discapacidad); // Field discapacidad
			$this->GetSessionFilterValues($this->tipodiscapacidad); // Field tipodiscapacidad
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildExtendedFilter($this->fecha_tamizaje, $sFilter, FALSE, TRUE); // Field fecha_tamizaje
		$this->BuildExtendedFilter($this->fecha_nacimiento, $sFilter, FALSE, TRUE); // Field fecha_nacimiento
		$this->BuildExtendedFilter($this->dias, $sFilter, FALSE, TRUE); // Field dias
		$this->BuildExtendedFilter($this->sexo, $sFilter, FALSE, TRUE); // Field sexo
		$this->BuildExtendedFilter($this->resultadotamizaje, $sFilter, FALSE, TRUE); // Field resultadotamizaje
		$this->BuildExtendedFilter($this->repetirprueba, $sFilter, FALSE, TRUE); // Field repetirprueba
		$this->BuildExtendedFilter($this->parentesco, $sFilter, FALSE, TRUE); // Field parentesco
		$this->BuildExtendedFilter($this->discapacidad, $sFilter, FALSE, TRUE); // Field discapacidad
		$this->BuildExtendedFilter($this->tipodiscapacidad, $sFilter, FALSE, TRUE); // Field tipodiscapacidad

		// Save parms to session
		$this->SetSessionFilterValues($this->fecha_tamizaje->SearchValue, $this->fecha_tamizaje->SearchOperator, $this->fecha_tamizaje->SearchCondition, $this->fecha_tamizaje->SearchValue2, $this->fecha_tamizaje->SearchOperator2, 'fecha_tamizaje'); // Field fecha_tamizaje
		$this->SetSessionFilterValues($this->fecha_nacimiento->SearchValue, $this->fecha_nacimiento->SearchOperator, $this->fecha_nacimiento->SearchCondition, $this->fecha_nacimiento->SearchValue2, $this->fecha_nacimiento->SearchOperator2, 'fecha_nacimiento'); // Field fecha_nacimiento
		$this->SetSessionFilterValues($this->dias->SearchValue, $this->dias->SearchOperator, $this->dias->SearchCondition, $this->dias->SearchValue2, $this->dias->SearchOperator2, 'dias'); // Field dias
		$this->SetSessionFilterValues($this->sexo->SearchValue, $this->sexo->SearchOperator, $this->sexo->SearchCondition, $this->sexo->SearchValue2, $this->sexo->SearchOperator2, 'sexo'); // Field sexo
		$this->SetSessionFilterValues($this->resultadotamizaje->SearchValue, $this->resultadotamizaje->SearchOperator, $this->resultadotamizaje->SearchCondition, $this->resultadotamizaje->SearchValue2, $this->resultadotamizaje->SearchOperator2, 'resultadotamizaje'); // Field resultadotamizaje
		$this->SetSessionFilterValues($this->repetirprueba->SearchValue, $this->repetirprueba->SearchOperator, $this->repetirprueba->SearchCondition, $this->repetirprueba->SearchValue2, $this->repetirprueba->SearchOperator2, 'repetirprueba'); // Field repetirprueba
		$this->SetSessionFilterValues($this->parentesco->SearchValue, $this->parentesco->SearchOperator, $this->parentesco->SearchCondition, $this->parentesco->SearchValue2, $this->parentesco->SearchOperator2, 'parentesco'); // Field parentesco
		$this->SetSessionFilterValues($this->discapacidad->SearchValue, $this->discapacidad->SearchOperator, $this->discapacidad->SearchCondition, $this->discapacidad->SearchValue2, $this->discapacidad->SearchOperator2, 'discapacidad'); // Field discapacidad
		$this->SetSessionFilterValues($this->tipodiscapacidad->SearchValue, $this->tipodiscapacidad->SearchOperator, $this->tipodiscapacidad->SearchCondition, $this->tipodiscapacidad->SearchValue2, $this->tipodiscapacidad->SearchOperator2, 'tipodiscapacidad'); // Field tipodiscapacidad

		// Setup filter
		if ($bSetupFilter) {

			// Field fecha_tamizaje
			$sWrk = "";
			$this->BuildExtendedFilter($this->fecha_tamizaje, $sWrk);
			ewr_LoadSelectionFromFilter($this->fecha_tamizaje, $sWrk, $this->fecha_tamizaje->SelectionList);
			$_SESSION['sel_viewsaludneonatal_fecha_tamizaje'] = ($this->fecha_tamizaje->SelectionList == "") ? EWR_INIT_VALUE : $this->fecha_tamizaje->SelectionList;

			// Field fecha_nacimiento
			$sWrk = "";
			$this->BuildExtendedFilter($this->fecha_nacimiento, $sWrk);
			ewr_LoadSelectionFromFilter($this->fecha_nacimiento, $sWrk, $this->fecha_nacimiento->SelectionList);
			$_SESSION['sel_viewsaludneonatal_fecha_nacimiento'] = ($this->fecha_nacimiento->SelectionList == "") ? EWR_INIT_VALUE : $this->fecha_nacimiento->SelectionList;

			// Field dias
			$sWrk = "";
			$this->BuildExtendedFilter($this->dias, $sWrk);
			ewr_LoadSelectionFromFilter($this->dias, $sWrk, $this->dias->SelectionList);
			$_SESSION['sel_viewsaludneonatal_dias'] = ($this->dias->SelectionList == "") ? EWR_INIT_VALUE : $this->dias->SelectionList;

			// Field sexo
			$sWrk = "";
			$this->BuildExtendedFilter($this->sexo, $sWrk);
			ewr_LoadSelectionFromFilter($this->sexo, $sWrk, $this->sexo->SelectionList);
			$_SESSION['sel_viewsaludneonatal_sexo'] = ($this->sexo->SelectionList == "") ? EWR_INIT_VALUE : $this->sexo->SelectionList;

			// Field resultadotamizaje
			$sWrk = "";
			$this->BuildExtendedFilter($this->resultadotamizaje, $sWrk);
			ewr_LoadSelectionFromFilter($this->resultadotamizaje, $sWrk, $this->resultadotamizaje->SelectionList);
			$_SESSION['sel_viewsaludneonatal_resultadotamizaje'] = ($this->resultadotamizaje->SelectionList == "") ? EWR_INIT_VALUE : $this->resultadotamizaje->SelectionList;

			// Field repetirprueba
			$sWrk = "";
			$this->BuildExtendedFilter($this->repetirprueba, $sWrk);
			ewr_LoadSelectionFromFilter($this->repetirprueba, $sWrk, $this->repetirprueba->SelectionList);
			$_SESSION['sel_viewsaludneonatal_repetirprueba'] = ($this->repetirprueba->SelectionList == "") ? EWR_INIT_VALUE : $this->repetirprueba->SelectionList;

			// Field parentesco
			$sWrk = "";
			$this->BuildExtendedFilter($this->parentesco, $sWrk);
			ewr_LoadSelectionFromFilter($this->parentesco, $sWrk, $this->parentesco->SelectionList);
			$_SESSION['sel_viewsaludneonatal_parentesco'] = ($this->parentesco->SelectionList == "") ? EWR_INIT_VALUE : $this->parentesco->SelectionList;

			// Field discapacidad
			$sWrk = "";
			$this->BuildExtendedFilter($this->discapacidad, $sWrk);
			ewr_LoadSelectionFromFilter($this->discapacidad, $sWrk, $this->discapacidad->SelectionList);
			$_SESSION['sel_viewsaludneonatal_discapacidad'] = ($this->discapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->discapacidad->SelectionList;

			// Field tipodiscapacidad
			$sWrk = "";
			$this->BuildExtendedFilter($this->tipodiscapacidad, $sWrk);
			ewr_LoadSelectionFromFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->SelectionList);
			$_SESSION['sel_viewsaludneonatal_tipodiscapacidad'] = ($this->tipodiscapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->tipodiscapacidad->SelectionList;
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewsaludneonatal_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsaludneonatal_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewsaludneonatal_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewsaludneonatal_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewsaludneonatal_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewsaludneonatal_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewsaludneonatal_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewsaludneonatal_' . $parm] = $sv;
		$_SESSION['so_viewsaludneonatal_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewsaludneonatal_' . $parm] = $sv1;
		$_SESSION['so_viewsaludneonatal_' . $parm] = $so1;
		$_SESSION['sc_viewsaludneonatal_' . $parm] = $sc;
		$_SESSION['sv2_viewsaludneonatal_' . $parm] = $sv2;
		$_SESSION['so2_viewsaludneonatal_' . $parm] = $so2;
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
		if (!ewr_CheckDateDef($this->fecha_tamizaje->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fecha_tamizaje->FldErrMsg();
		}
		if (!ewr_CheckDateDef($this->fecha_tamizaje->SearchValue2)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fecha_tamizaje->FldErrMsg();
		}
		if (!ewr_CheckDateDef($this->fecha_nacimiento->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fecha_nacimiento->FldErrMsg();
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
		$_SESSION["sel_viewsaludneonatal_$parm"] = "";
		$_SESSION["rf_viewsaludneonatal_$parm"] = "";
		$_SESSION["rt_viewsaludneonatal_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewsaludneonatal_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewsaludneonatal_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewsaludneonatal_$parm"];
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

		// Field fecha_tamizaje
		$this->SetDefaultExtFilter($this->fecha_tamizaje, "BETWEEN", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->fecha_tamizaje);
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha_tamizaje, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->fecha_tamizaje, $sWrk, $this->fecha_tamizaje->DefaultSelectionList);
		if (!$this->SearchCommand) $this->fecha_tamizaje->SelectionList = $this->fecha_tamizaje->DefaultSelectionList;

		// Field fecha_nacimiento
		$this->SetDefaultExtFilter($this->fecha_nacimiento, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->fecha_nacimiento);
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha_nacimiento, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->fecha_nacimiento, $sWrk, $this->fecha_nacimiento->DefaultSelectionList);
		if (!$this->SearchCommand) $this->fecha_nacimiento->SelectionList = $this->fecha_nacimiento->DefaultSelectionList;

		// Field dias
		$this->SetDefaultExtFilter($this->dias, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->dias);
		$sWrk = "";
		$this->BuildExtendedFilter($this->dias, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->dias, $sWrk, $this->dias->DefaultSelectionList);
		if (!$this->SearchCommand) $this->dias->SelectionList = $this->dias->DefaultSelectionList;

		// Field sexo
		$this->SetDefaultExtFilter($this->sexo, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->sexo);
		$sWrk = "";
		$this->BuildExtendedFilter($this->sexo, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->sexo, $sWrk, $this->sexo->DefaultSelectionList);
		if (!$this->SearchCommand) $this->sexo->SelectionList = $this->sexo->DefaultSelectionList;

		// Field resultadotamizaje
		$this->SetDefaultExtFilter($this->resultadotamizaje, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->resultadotamizaje);
		$sWrk = "";
		$this->BuildExtendedFilter($this->resultadotamizaje, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->resultadotamizaje, $sWrk, $this->resultadotamizaje->DefaultSelectionList);
		if (!$this->SearchCommand) $this->resultadotamizaje->SelectionList = $this->resultadotamizaje->DefaultSelectionList;

		// Field repetirprueba
		$this->SetDefaultExtFilter($this->repetirprueba, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->repetirprueba);
		$sWrk = "";
		$this->BuildExtendedFilter($this->repetirprueba, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->repetirprueba, $sWrk, $this->repetirprueba->DefaultSelectionList);
		if (!$this->SearchCommand) $this->repetirprueba->SelectionList = $this->repetirprueba->DefaultSelectionList;

		// Field parentesco
		$this->SetDefaultExtFilter($this->parentesco, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->parentesco);
		$sWrk = "";
		$this->BuildExtendedFilter($this->parentesco, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->parentesco, $sWrk, $this->parentesco->DefaultSelectionList);
		if (!$this->SearchCommand) $this->parentesco->SelectionList = $this->parentesco->DefaultSelectionList;

		// Field discapacidad
		$this->SetDefaultExtFilter($this->discapacidad, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->discapacidad);
		$sWrk = "";
		$this->BuildExtendedFilter($this->discapacidad, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->discapacidad, $sWrk, $this->discapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->discapacidad->SelectionList = $this->discapacidad->DefaultSelectionList;

		// Field tipodiscapacidad
		$this->SetDefaultExtFilter($this->tipodiscapacidad, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->tipodiscapacidad);
		$sWrk = "";
		$this->BuildExtendedFilter($this->tipodiscapacidad, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->tipodiscapacidad->SelectionList = $this->tipodiscapacidad->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field fecha_tamizaje
		// $this->fecha_tamizaje->DefaultSelectionList = array("val1", "val2");
		// Field ci
		// $this->ci->DefaultSelectionList = array("val1", "val2");
		// Field fecha_nacimiento
		// $this->fecha_nacimiento->DefaultSelectionList = array("val1", "val2");
		// Field dias
		// $this->dias->DefaultSelectionList = array("val1", "val2");
		// Field semanas
		// $this->semanas->DefaultSelectionList = array("val1", "val2");
		// Field meses
		// $this->meses->DefaultSelectionList = array("val1", "val2");
		// Field sexo
		// $this->sexo->DefaultSelectionList = array("val1", "val2");
		// Field resultado
		// $this->resultado->DefaultSelectionList = array("val1", "val2");
		// Field resultadotamizaje
		// $this->resultadotamizaje->DefaultSelectionList = array("val1", "val2");
		// Field tapon
		// $this->tapon->DefaultSelectionList = array("val1", "val2");
		// Field repetirprueba
		// $this->repetirprueba->DefaultSelectionList = array("val1", "val2");
		// Field observaciones
		// $this->observaciones->DefaultSelectionList = array("val1", "val2");
		// Field parentesco
		// $this->parentesco->DefaultSelectionList = array("val1", "val2");
		// Field nombrescompleto
		// $this->nombrescompleto->DefaultSelectionList = array("val1", "val2");
		// Field nombreinstitucion
		// $this->nombreinstitucion->DefaultSelectionList = array("val1", "val2");
		// Field discapacidad
		// $this->discapacidad->DefaultSelectionList = array("val1", "val2");
		// Field tipodiscapacidad
		// $this->tipodiscapacidad->DefaultSelectionList = array("val1", "val2");
		// Field tipotapo
		// $this->tipotapo->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check fecha_tamizaje text filter
		if ($this->TextFilterApplied($this->fecha_tamizaje))
			return TRUE;

		// Check fecha_tamizaje popup filter
		if (!ewr_MatchedArray($this->fecha_tamizaje->DefaultSelectionList, $this->fecha_tamizaje->SelectionList))
			return TRUE;

		// Check ci popup filter
		if (!ewr_MatchedArray($this->ci->DefaultSelectionList, $this->ci->SelectionList))
			return TRUE;

		// Check fecha_nacimiento text filter
		if ($this->TextFilterApplied($this->fecha_nacimiento))
			return TRUE;

		// Check fecha_nacimiento popup filter
		if (!ewr_MatchedArray($this->fecha_nacimiento->DefaultSelectionList, $this->fecha_nacimiento->SelectionList))
			return TRUE;

		// Check dias text filter
		if ($this->TextFilterApplied($this->dias))
			return TRUE;

		// Check dias popup filter
		if (!ewr_MatchedArray($this->dias->DefaultSelectionList, $this->dias->SelectionList))
			return TRUE;

		// Check semanas popup filter
		if (!ewr_MatchedArray($this->semanas->DefaultSelectionList, $this->semanas->SelectionList))
			return TRUE;

		// Check meses popup filter
		if (!ewr_MatchedArray($this->meses->DefaultSelectionList, $this->meses->SelectionList))
			return TRUE;

		// Check sexo text filter
		if ($this->TextFilterApplied($this->sexo))
			return TRUE;

		// Check sexo popup filter
		if (!ewr_MatchedArray($this->sexo->DefaultSelectionList, $this->sexo->SelectionList))
			return TRUE;

		// Check resultado popup filter
		if (!ewr_MatchedArray($this->resultado->DefaultSelectionList, $this->resultado->SelectionList))
			return TRUE;

		// Check resultadotamizaje text filter
		if ($this->TextFilterApplied($this->resultadotamizaje))
			return TRUE;

		// Check resultadotamizaje popup filter
		if (!ewr_MatchedArray($this->resultadotamizaje->DefaultSelectionList, $this->resultadotamizaje->SelectionList))
			return TRUE;

		// Check tapon popup filter
		if (!ewr_MatchedArray($this->tapon->DefaultSelectionList, $this->tapon->SelectionList))
			return TRUE;

		// Check repetirprueba text filter
		if ($this->TextFilterApplied($this->repetirprueba))
			return TRUE;

		// Check repetirprueba popup filter
		if (!ewr_MatchedArray($this->repetirprueba->DefaultSelectionList, $this->repetirprueba->SelectionList))
			return TRUE;

		// Check observaciones popup filter
		if (!ewr_MatchedArray($this->observaciones->DefaultSelectionList, $this->observaciones->SelectionList))
			return TRUE;

		// Check parentesco text filter
		if ($this->TextFilterApplied($this->parentesco))
			return TRUE;

		// Check parentesco popup filter
		if (!ewr_MatchedArray($this->parentesco->DefaultSelectionList, $this->parentesco->SelectionList))
			return TRUE;

		// Check nombrescompleto popup filter
		if (!ewr_MatchedArray($this->nombrescompleto->DefaultSelectionList, $this->nombrescompleto->SelectionList))
			return TRUE;

		// Check nombreinstitucion popup filter
		if (!ewr_MatchedArray($this->nombreinstitucion->DefaultSelectionList, $this->nombreinstitucion->SelectionList))
			return TRUE;

		// Check discapacidad text filter
		if ($this->TextFilterApplied($this->discapacidad))
			return TRUE;

		// Check discapacidad popup filter
		if (!ewr_MatchedArray($this->discapacidad->DefaultSelectionList, $this->discapacidad->SelectionList))
			return TRUE;

		// Check tipodiscapacidad text filter
		if ($this->TextFilterApplied($this->tipodiscapacidad))
			return TRUE;

		// Check tipodiscapacidad popup filter
		if (!ewr_MatchedArray($this->tipodiscapacidad->DefaultSelectionList, $this->tipodiscapacidad->SelectionList))
			return TRUE;

		// Check tipotapo popup filter
		if (!ewr_MatchedArray($this->tipotapo->DefaultSelectionList, $this->tipotapo->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field fecha_tamizaje
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha_tamizaje, $sExtWrk);
		if (is_array($this->fecha_tamizaje->SelectionList))
			$sWrk = ewr_JoinArray($this->fecha_tamizaje->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fecha_tamizaje->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field ci
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->ci->SelectionList))
			$sWrk = ewr_JoinArray($this->ci->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->ci->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field fecha_nacimiento
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha_nacimiento, $sExtWrk);
		if (is_array($this->fecha_nacimiento->SelectionList))
			$sWrk = ewr_JoinArray($this->fecha_nacimiento->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fecha_nacimiento->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field dias
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->dias, $sExtWrk);
		if (is_array($this->dias->SelectionList))
			$sWrk = ewr_JoinArray($this->dias->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->dias->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field semanas
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->semanas->SelectionList))
			$sWrk = ewr_JoinArray($this->semanas->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->semanas->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field meses
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->meses->SelectionList))
			$sWrk = ewr_JoinArray($this->meses->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->meses->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field sexo
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->sexo, $sExtWrk);
		if (is_array($this->sexo->SelectionList))
			$sWrk = ewr_JoinArray($this->sexo->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->sexo->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field resultado
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->resultado->SelectionList))
			$sWrk = ewr_JoinArray($this->resultado->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->resultado->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field resultadotamizaje
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->resultadotamizaje, $sExtWrk);
		if (is_array($this->resultadotamizaje->SelectionList))
			$sWrk = ewr_JoinArray($this->resultadotamizaje->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->resultadotamizaje->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field tapon
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->tapon->SelectionList))
			$sWrk = ewr_JoinArray($this->tapon->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tapon->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field repetirprueba
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->repetirprueba, $sExtWrk);
		if (is_array($this->repetirprueba->SelectionList))
			$sWrk = ewr_JoinArray($this->repetirprueba->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->repetirprueba->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field observaciones
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->observaciones->SelectionList))
			$sWrk = ewr_JoinArray($this->observaciones->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->observaciones->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field parentesco
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->parentesco, $sExtWrk);
		if (is_array($this->parentesco->SelectionList))
			$sWrk = ewr_JoinArray($this->parentesco->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->parentesco->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombrescompleto
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->nombrescompleto->SelectionList))
			$sWrk = ewr_JoinArray($this->nombrescompleto->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombrescompleto->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombreinstitucion
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->nombreinstitucion->SelectionList))
			$sWrk = ewr_JoinArray($this->nombreinstitucion->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreinstitucion->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field discapacidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->discapacidad, $sExtWrk);
		if (is_array($this->discapacidad->SelectionList))
			$sWrk = ewr_JoinArray($this->discapacidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->discapacidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field tipodiscapacidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->tipodiscapacidad, $sExtWrk);
		if (is_array($this->tipodiscapacidad->SelectionList))
			$sWrk = ewr_JoinArray($this->tipodiscapacidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tipodiscapacidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field tipotapo
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->tipotapo->SelectionList))
			$sWrk = ewr_JoinArray($this->tipotapo->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tipotapo->FldCaption() . "</span>" . $sFilter . "</div>";
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

		// Field fecha_tamizaje
		$sWrk = "";
		if ($this->fecha_tamizaje->SearchValue <> "" || $this->fecha_tamizaje->SearchValue2 <> "") {
			$sWrk = "\"sv_fecha_tamizaje\":\"" . ewr_JsEncode2($this->fecha_tamizaje->SearchValue) . "\"," .
				"\"so_fecha_tamizaje\":\"" . ewr_JsEncode2($this->fecha_tamizaje->SearchOperator) . "\"," .
				"\"sc_fecha_tamizaje\":\"" . ewr_JsEncode2($this->fecha_tamizaje->SearchCondition) . "\"," .
				"\"sv2_fecha_tamizaje\":\"" . ewr_JsEncode2($this->fecha_tamizaje->SearchValue2) . "\"," .
				"\"so2_fecha_tamizaje\":\"" . ewr_JsEncode2($this->fecha_tamizaje->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->fecha_tamizaje->SelectionList <> EWR_INIT_VALUE) ? $this->fecha_tamizaje->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_fecha_tamizaje\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field ci
		$sWrk = "";
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

		// Field fecha_nacimiento
		$sWrk = "";
		if ($this->fecha_nacimiento->SearchValue <> "" || $this->fecha_nacimiento->SearchValue2 <> "") {
			$sWrk = "\"sv_fecha_nacimiento\":\"" . ewr_JsEncode2($this->fecha_nacimiento->SearchValue) . "\"," .
				"\"so_fecha_nacimiento\":\"" . ewr_JsEncode2($this->fecha_nacimiento->SearchOperator) . "\"," .
				"\"sc_fecha_nacimiento\":\"" . ewr_JsEncode2($this->fecha_nacimiento->SearchCondition) . "\"," .
				"\"sv2_fecha_nacimiento\":\"" . ewr_JsEncode2($this->fecha_nacimiento->SearchValue2) . "\"," .
				"\"so2_fecha_nacimiento\":\"" . ewr_JsEncode2($this->fecha_nacimiento->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->fecha_nacimiento->SelectionList <> EWR_INIT_VALUE) ? $this->fecha_nacimiento->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_fecha_nacimiento\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field dias
		$sWrk = "";
		if ($this->dias->SearchValue <> "" || $this->dias->SearchValue2 <> "") {
			$sWrk = "\"sv_dias\":\"" . ewr_JsEncode2($this->dias->SearchValue) . "\"," .
				"\"so_dias\":\"" . ewr_JsEncode2($this->dias->SearchOperator) . "\"," .
				"\"sc_dias\":\"" . ewr_JsEncode2($this->dias->SearchCondition) . "\"," .
				"\"sv2_dias\":\"" . ewr_JsEncode2($this->dias->SearchValue2) . "\"," .
				"\"so2_dias\":\"" . ewr_JsEncode2($this->dias->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->dias->SelectionList <> EWR_INIT_VALUE) ? $this->dias->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_dias\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field semanas
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->semanas->SelectionList <> EWR_INIT_VALUE) ? $this->semanas->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_semanas\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field meses
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->meses->SelectionList <> EWR_INIT_VALUE) ? $this->meses->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_meses\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field resultado
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->resultado->SelectionList <> EWR_INIT_VALUE) ? $this->resultado->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_resultado\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field resultadotamizaje
		$sWrk = "";
		if ($this->resultadotamizaje->SearchValue <> "" || $this->resultadotamizaje->SearchValue2 <> "") {
			$sWrk = "\"sv_resultadotamizaje\":\"" . ewr_JsEncode2($this->resultadotamizaje->SearchValue) . "\"," .
				"\"so_resultadotamizaje\":\"" . ewr_JsEncode2($this->resultadotamizaje->SearchOperator) . "\"," .
				"\"sc_resultadotamizaje\":\"" . ewr_JsEncode2($this->resultadotamizaje->SearchCondition) . "\"," .
				"\"sv2_resultadotamizaje\":\"" . ewr_JsEncode2($this->resultadotamizaje->SearchValue2) . "\"," .
				"\"so2_resultadotamizaje\":\"" . ewr_JsEncode2($this->resultadotamizaje->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->resultadotamizaje->SelectionList <> EWR_INIT_VALUE) ? $this->resultadotamizaje->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_resultadotamizaje\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field tapon
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->tapon->SelectionList <> EWR_INIT_VALUE) ? $this->tapon->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_tapon\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field repetirprueba
		$sWrk = "";
		if ($this->repetirprueba->SearchValue <> "" || $this->repetirprueba->SearchValue2 <> "") {
			$sWrk = "\"sv_repetirprueba\":\"" . ewr_JsEncode2($this->repetirprueba->SearchValue) . "\"," .
				"\"so_repetirprueba\":\"" . ewr_JsEncode2($this->repetirprueba->SearchOperator) . "\"," .
				"\"sc_repetirprueba\":\"" . ewr_JsEncode2($this->repetirprueba->SearchCondition) . "\"," .
				"\"sv2_repetirprueba\":\"" . ewr_JsEncode2($this->repetirprueba->SearchValue2) . "\"," .
				"\"so2_repetirprueba\":\"" . ewr_JsEncode2($this->repetirprueba->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->repetirprueba->SelectionList <> EWR_INIT_VALUE) ? $this->repetirprueba->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_repetirprueba\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field observaciones
		$sWrk = "";
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

		// Field parentesco
		$sWrk = "";
		if ($this->parentesco->SearchValue <> "" || $this->parentesco->SearchValue2 <> "") {
			$sWrk = "\"sv_parentesco\":\"" . ewr_JsEncode2($this->parentesco->SearchValue) . "\"," .
				"\"so_parentesco\":\"" . ewr_JsEncode2($this->parentesco->SearchOperator) . "\"," .
				"\"sc_parentesco\":\"" . ewr_JsEncode2($this->parentesco->SearchCondition) . "\"," .
				"\"sv2_parentesco\":\"" . ewr_JsEncode2($this->parentesco->SearchValue2) . "\"," .
				"\"so2_parentesco\":\"" . ewr_JsEncode2($this->parentesco->SearchOperator2) . "\"";
		}
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

		// Field nombrescompleto
		$sWrk = "";
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

		// Field nombreinstitucion
		$sWrk = "";
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

		// Field discapacidad
		$sWrk = "";
		if ($this->discapacidad->SearchValue <> "" || $this->discapacidad->SearchValue2 <> "") {
			$sWrk = "\"sv_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchValue) . "\"," .
				"\"so_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchOperator) . "\"," .
				"\"sc_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchCondition) . "\"," .
				"\"sv2_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchValue2) . "\"," .
				"\"so2_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->discapacidad->SelectionList <> EWR_INIT_VALUE) ? $this->discapacidad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_discapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field tipodiscapacidad
		$sWrk = "";
		if ($this->tipodiscapacidad->SearchValue <> "" || $this->tipodiscapacidad->SearchValue2 <> "") {
			$sWrk = "\"sv_tipodiscapacidad\":\"" . ewr_JsEncode2($this->tipodiscapacidad->SearchValue) . "\"," .
				"\"so_tipodiscapacidad\":\"" . ewr_JsEncode2($this->tipodiscapacidad->SearchOperator) . "\"," .
				"\"sc_tipodiscapacidad\":\"" . ewr_JsEncode2($this->tipodiscapacidad->SearchCondition) . "\"," .
				"\"sv2_tipodiscapacidad\":\"" . ewr_JsEncode2($this->tipodiscapacidad->SearchValue2) . "\"," .
				"\"so2_tipodiscapacidad\":\"" . ewr_JsEncode2($this->tipodiscapacidad->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->tipodiscapacidad->SelectionList <> EWR_INIT_VALUE) ? $this->tipodiscapacidad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_tipodiscapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field tipotapo
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->tipotapo->SelectionList <> EWR_INIT_VALUE) ? $this->tipotapo->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_tipotapo\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field fecha_tamizaje
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_fecha_tamizaje", $filter) || array_key_exists("so_fecha_tamizaje", $filter) ||
			array_key_exists("sc_fecha_tamizaje", $filter) ||
			array_key_exists("sv2_fecha_tamizaje", $filter) || array_key_exists("so2_fecha_tamizaje", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_fecha_tamizaje"], @$filter["so_fecha_tamizaje"], @$filter["sc_fecha_tamizaje"], @$filter["sv2_fecha_tamizaje"], @$filter["so2_fecha_tamizaje"], "fecha_tamizaje");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_fecha_tamizaje", $filter)) {
			$sWrk = $filter["sel_fecha_tamizaje"];
			$sWrk = explode("||", $sWrk);
			$this->fecha_tamizaje->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_fecha_tamizaje"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha_tamizaje"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha_tamizaje");
			$this->fecha_tamizaje->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_fecha_tamizaje"] = "";
		}

		// Field ci
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_ci", $filter)) {
			$sWrk = $filter["sel_ci"];
			$sWrk = explode("||", $sWrk);
			$this->ci->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_ci"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field fecha_nacimiento
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_fecha_nacimiento", $filter) || array_key_exists("so_fecha_nacimiento", $filter) ||
			array_key_exists("sc_fecha_nacimiento", $filter) ||
			array_key_exists("sv2_fecha_nacimiento", $filter) || array_key_exists("so2_fecha_nacimiento", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_fecha_nacimiento"], @$filter["so_fecha_nacimiento"], @$filter["sc_fecha_nacimiento"], @$filter["sv2_fecha_nacimiento"], @$filter["so2_fecha_nacimiento"], "fecha_nacimiento");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_fecha_nacimiento", $filter)) {
			$sWrk = $filter["sel_fecha_nacimiento"];
			$sWrk = explode("||", $sWrk);
			$this->fecha_nacimiento->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_fecha_nacimiento"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha_nacimiento"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha_nacimiento");
			$this->fecha_nacimiento->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_fecha_nacimiento"] = "";
		}

		// Field dias
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_dias", $filter) || array_key_exists("so_dias", $filter) ||
			array_key_exists("sc_dias", $filter) ||
			array_key_exists("sv2_dias", $filter) || array_key_exists("so2_dias", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_dias"], @$filter["so_dias"], @$filter["sc_dias"], @$filter["sv2_dias"], @$filter["so2_dias"], "dias");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_dias", $filter)) {
			$sWrk = $filter["sel_dias"];
			$sWrk = explode("||", $sWrk);
			$this->dias->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_dias"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "dias"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "dias");
			$this->dias->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_dias"] = "";
		}

		// Field semanas
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_semanas", $filter)) {
			$sWrk = $filter["sel_semanas"];
			$sWrk = explode("||", $sWrk);
			$this->semanas->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_semanas"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field meses
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_meses", $filter)) {
			$sWrk = $filter["sel_meses"];
			$sWrk = explode("||", $sWrk);
			$this->meses->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_meses"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
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
			$_SESSION["sel_viewsaludneonatal_sexo"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "sexo"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "sexo");
			$this->sexo->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_sexo"] = "";
		}

		// Field resultado
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_resultado", $filter)) {
			$sWrk = $filter["sel_resultado"];
			$sWrk = explode("||", $sWrk);
			$this->resultado->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_resultado"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field resultadotamizaje
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_resultadotamizaje", $filter) || array_key_exists("so_resultadotamizaje", $filter) ||
			array_key_exists("sc_resultadotamizaje", $filter) ||
			array_key_exists("sv2_resultadotamizaje", $filter) || array_key_exists("so2_resultadotamizaje", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_resultadotamizaje"], @$filter["so_resultadotamizaje"], @$filter["sc_resultadotamizaje"], @$filter["sv2_resultadotamizaje"], @$filter["so2_resultadotamizaje"], "resultadotamizaje");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_resultadotamizaje", $filter)) {
			$sWrk = $filter["sel_resultadotamizaje"];
			$sWrk = explode("||", $sWrk);
			$this->resultadotamizaje->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_resultadotamizaje"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "resultadotamizaje"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "resultadotamizaje");
			$this->resultadotamizaje->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_resultadotamizaje"] = "";
		}

		// Field tapon
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_tapon", $filter)) {
			$sWrk = $filter["sel_tapon"];
			$sWrk = explode("||", $sWrk);
			$this->tapon->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_tapon"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field repetirprueba
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_repetirprueba", $filter) || array_key_exists("so_repetirprueba", $filter) ||
			array_key_exists("sc_repetirprueba", $filter) ||
			array_key_exists("sv2_repetirprueba", $filter) || array_key_exists("so2_repetirprueba", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_repetirprueba"], @$filter["so_repetirprueba"], @$filter["sc_repetirprueba"], @$filter["sv2_repetirprueba"], @$filter["so2_repetirprueba"], "repetirprueba");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_repetirprueba", $filter)) {
			$sWrk = $filter["sel_repetirprueba"];
			$sWrk = explode("||", $sWrk);
			$this->repetirprueba->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_repetirprueba"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "repetirprueba"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "repetirprueba");
			$this->repetirprueba->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_repetirprueba"] = "";
		}

		// Field observaciones
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_observaciones", $filter)) {
			$sWrk = $filter["sel_observaciones"];
			$sWrk = explode("||", $sWrk);
			$this->observaciones->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_observaciones"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field parentesco
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_parentesco", $filter) || array_key_exists("so_parentesco", $filter) ||
			array_key_exists("sc_parentesco", $filter) ||
			array_key_exists("sv2_parentesco", $filter) || array_key_exists("so2_parentesco", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_parentesco"], @$filter["so_parentesco"], @$filter["sc_parentesco"], @$filter["sv2_parentesco"], @$filter["so2_parentesco"], "parentesco");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_parentesco", $filter)) {
			$sWrk = $filter["sel_parentesco"];
			$sWrk = explode("||", $sWrk);
			$this->parentesco->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_parentesco"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "parentesco"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "parentesco");
			$this->parentesco->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_parentesco"] = "";
		}

		// Field nombrescompleto
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_nombrescompleto", $filter)) {
			$sWrk = $filter["sel_nombrescompleto"];
			$sWrk = explode("||", $sWrk);
			$this->nombrescompleto->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_nombrescompleto"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field nombreinstitucion
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_nombreinstitucion", $filter)) {
			$sWrk = $filter["sel_nombreinstitucion"];
			$sWrk = explode("||", $sWrk);
			$this->nombreinstitucion->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_nombreinstitucion"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field discapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_discapacidad", $filter) || array_key_exists("so_discapacidad", $filter) ||
			array_key_exists("sc_discapacidad", $filter) ||
			array_key_exists("sv2_discapacidad", $filter) || array_key_exists("so2_discapacidad", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_discapacidad"], @$filter["so_discapacidad"], @$filter["sc_discapacidad"], @$filter["sv2_discapacidad"], @$filter["so2_discapacidad"], "discapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_discapacidad", $filter)) {
			$sWrk = $filter["sel_discapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->discapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_discapacidad"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "discapacidad"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "discapacidad");
			$this->discapacidad->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_discapacidad"] = "";
		}

		// Field tipodiscapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_tipodiscapacidad", $filter) || array_key_exists("so_tipodiscapacidad", $filter) ||
			array_key_exists("sc_tipodiscapacidad", $filter) ||
			array_key_exists("sv2_tipodiscapacidad", $filter) || array_key_exists("so2_tipodiscapacidad", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_tipodiscapacidad"], @$filter["so_tipodiscapacidad"], @$filter["sc_tipodiscapacidad"], @$filter["sv2_tipodiscapacidad"], @$filter["so2_tipodiscapacidad"], "tipodiscapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_tipodiscapacidad", $filter)) {
			$sWrk = $filter["sel_tipodiscapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->tipodiscapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_tipodiscapacidad"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "tipodiscapacidad"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "tipodiscapacidad");
			$this->tipodiscapacidad->SelectionList = "";
			$_SESSION["sel_viewsaludneonatal_tipodiscapacidad"] = "";
		}

		// Field tipotapo
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_tipotapo", $filter)) {
			$sWrk = $filter["sel_tipotapo"];
			$sWrk = explode("||", $sWrk);
			$this->tipotapo->SelectionList = $sWrk;
			$_SESSION["sel_viewsaludneonatal_tipotapo"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->ExtendedFilterExist($this->fecha_tamizaje)) {
			if (is_array($this->fecha_tamizaje->SelectionList)) {
				$sFilter = ewr_FilterSql($this->fecha_tamizaje, "`fecha_tamizaje`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha_tamizaje, $sFilter, "popup");
				$this->fecha_tamizaje->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->ci->SelectionList)) {
				$sFilter = ewr_FilterSql($this->ci, "`ci`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->ci, $sFilter, "popup");
				$this->ci->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->fecha_nacimiento)) {
			if (is_array($this->fecha_nacimiento->SelectionList)) {
				$sFilter = ewr_FilterSql($this->fecha_nacimiento, "`fecha_nacimiento`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha_nacimiento, $sFilter, "popup");
				$this->fecha_nacimiento->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->dias)) {
			if (is_array($this->dias->SelectionList)) {
				$sFilter = ewr_FilterSql($this->dias, "`dias`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->dias, $sFilter, "popup");
				$this->dias->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->semanas->SelectionList)) {
				$sFilter = ewr_FilterSql($this->semanas, "`semanas`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->semanas, $sFilter, "popup");
				$this->semanas->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->meses->SelectionList)) {
				$sFilter = ewr_FilterSql($this->meses, "`meses`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->meses, $sFilter, "popup");
				$this->meses->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->sexo)) {
			if (is_array($this->sexo->SelectionList)) {
				$sFilter = ewr_FilterSql($this->sexo, "`sexo`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->sexo, $sFilter, "popup");
				$this->sexo->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->resultado->SelectionList)) {
				$sFilter = ewr_FilterSql($this->resultado, "`resultado`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->resultado, $sFilter, "popup");
				$this->resultado->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->resultadotamizaje)) {
			if (is_array($this->resultadotamizaje->SelectionList)) {
				$sFilter = ewr_FilterSql($this->resultadotamizaje, "`resultadotamizaje`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->resultadotamizaje, $sFilter, "popup");
				$this->resultadotamizaje->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->tapon->SelectionList)) {
				$sFilter = ewr_FilterSql($this->tapon, "`tapon`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->tapon, $sFilter, "popup");
				$this->tapon->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->repetirprueba)) {
			if (is_array($this->repetirprueba->SelectionList)) {
				$sFilter = ewr_FilterSql($this->repetirprueba, "`repetirprueba`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->repetirprueba, $sFilter, "popup");
				$this->repetirprueba->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->observaciones->SelectionList)) {
				$sFilter = ewr_FilterSql($this->observaciones, "`observaciones`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->observaciones, $sFilter, "popup");
				$this->observaciones->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->parentesco)) {
			if (is_array($this->parentesco->SelectionList)) {
				$sFilter = ewr_FilterSql($this->parentesco, "`parentesco`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->parentesco, $sFilter, "popup");
				$this->parentesco->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->nombrescompleto->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombrescompleto, "`nombrescompleto`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombrescompleto, $sFilter, "popup");
				$this->nombrescompleto->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->nombreinstitucion->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombreinstitucion, "`nombreinstitucion`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreinstitucion, $sFilter, "popup");
				$this->nombreinstitucion->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->discapacidad)) {
			if (is_array($this->discapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->discapacidad, "`discapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->discapacidad, $sFilter, "popup");
				$this->discapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->tipodiscapacidad)) {
			if (is_array($this->tipodiscapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->tipodiscapacidad, "`tipodiscapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->tipodiscapacidad, $sFilter, "popup");
				$this->tipodiscapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->tipotapo->SelectionList)) {
				$sFilter = ewr_FilterSql($this->tipotapo, "`tipotapo`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->tipotapo, $sFilter, "popup");
				$this->tipotapo->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
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
			$this->fecha_tamizaje->setSort("");
			$this->ci->setSort("");
			$this->fecha_nacimiento->setSort("");
			$this->dias->setSort("");
			$this->semanas->setSort("");
			$this->meses->setSort("");
			$this->sexo->setSort("");
			$this->resultado->setSort("");
			$this->resultadotamizaje->setSort("");
			$this->tapon->setSort("");
			$this->repetirprueba->setSort("");
			$this->observaciones->setSort("");
			$this->parentesco->setSort("");
			$this->nombrescompleto->setSort("");
			$this->nombreinstitucion->setSort("");
			$this->nombreneonatal->setSort("");
			$this->discapacidad->setSort("");
			$this->tipodiscapacidad->setSort("");
			$this->tipotapo->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->fecha_tamizaje); // fecha_tamizaje
			$this->UpdateSort($this->ci); // ci
			$this->UpdateSort($this->fecha_nacimiento); // fecha_nacimiento
			$this->UpdateSort($this->dias); // dias
			$this->UpdateSort($this->semanas); // semanas
			$this->UpdateSort($this->meses); // meses
			$this->UpdateSort($this->sexo); // sexo
			$this->UpdateSort($this->resultado); // resultado
			$this->UpdateSort($this->resultadotamizaje); // resultadotamizaje
			$this->UpdateSort($this->tapon); // tapon
			$this->UpdateSort($this->repetirprueba); // repetirprueba
			$this->UpdateSort($this->observaciones); // observaciones
			$this->UpdateSort($this->parentesco); // parentesco
			$this->UpdateSort($this->nombrescompleto); // nombrescompleto
			$this->UpdateSort($this->nombreinstitucion); // nombreinstitucion
			$this->UpdateSort($this->nombreneonatal); // nombreneonatal
			$this->UpdateSort($this->discapacidad); // discapacidad
			$this->UpdateSort($this->tipodiscapacidad); // tipodiscapacidad
			$this->UpdateSort($this->tipotapo); // tipotapo
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
if (!isset($viewsaludneonatal_rpt)) $viewsaludneonatal_rpt = new crviewsaludneonatal_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewsaludneonatal_rpt;

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
var viewsaludneonatal_rpt = new ewr_Page("viewsaludneonatal_rpt");

// Page properties
viewsaludneonatal_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewsaludneonatal_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewsaludneonatalrpt = new ewr_Form("fviewsaludneonatalrpt");

// Validate method
fviewsaludneonatalrpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_fecha_tamizaje;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fecha_tamizaje->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_fecha_tamizaje;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fecha_tamizaje->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv_fecha_nacimiento;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fecha_nacimiento->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewsaludneonatalrpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewsaludneonatalrpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewsaludneonatalrpt.ValidateRequired = false; // No JavaScript validation
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
<form name="fviewsaludneonatalrpt" id="fviewsaludneonatalrpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewsaludneonatalrpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_fecha_tamizaje" class="ewCell form-group">
	<label for="sv_fecha_tamizaje" class="ewSearchCaption ewLabel"><?php echo $Page->fecha_tamizaje->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so_fecha_tamizaje" id="so_fecha_tamizaje" value="BETWEEN"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fecha_tamizaje->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_fecha_tamizaje" id="sv_fecha_tamizaje" name="sv_fecha_tamizaje" placeholder="<?php echo $Page->fecha_tamizaje->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha_tamizaje->SearchValue) ?>"<?php echo $Page->fecha_tamizaje->EditAttributes() ?>>
</span>
	<span class="ewSearchCond btw1_fecha_tamizaje"><?php echo $ReportLanguage->Phrase("AND") ?></span>
	<span class="ewSearchField btw1_fecha_tamizaje">
<?php ewr_PrependClass($Page->fecha_tamizaje->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_fecha_tamizaje" id="sv2_fecha_tamizaje" name="sv2_fecha_tamizaje" placeholder="<?php echo $Page->fecha_tamizaje->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha_tamizaje->SearchValue2) ?>"<?php echo $Page->fecha_tamizaje->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_fecha_nacimiento" class="ewCell form-group">
	<label for="sv_fecha_nacimiento" class="ewSearchCaption ewLabel"><?php echo $Page->fecha_nacimiento->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_fecha_nacimiento" id="so_fecha_nacimiento" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fecha_nacimiento->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_fecha_nacimiento" id="sv_fecha_nacimiento" name="sv_fecha_nacimiento" placeholder="<?php echo $Page->fecha_nacimiento->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha_nacimiento->SearchValue) ?>"<?php echo $Page->fecha_nacimiento->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_3" class="ewRow">
<div id="c_dias" class="ewCell form-group">
	<label for="sv_dias" class="ewSearchCaption ewLabel"><?php echo $Page->dias->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_dias" id="so_dias" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->dias->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_dias" id="sv_dias" name="sv_dias" size="30" maxlength="100" placeholder="<?php echo $Page->dias->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->dias->SearchValue) ?>"<?php echo $Page->dias->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_4" class="ewRow">
<div id="c_sexo" class="ewCell form-group">
	<label for="sv_sexo" class="ewSearchCaption ewLabel"><?php echo $Page->sexo->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_sexo" id="so_sexo" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->sexo->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_sexo" id="sv_sexo" name="sv_sexo" size="30" maxlength="100" placeholder="<?php echo $Page->sexo->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->sexo->SearchValue) ?>"<?php echo $Page->sexo->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_5" class="ewRow">
<div id="c_resultadotamizaje" class="ewCell form-group">
	<label for="sv_resultadotamizaje" class="ewSearchCaption ewLabel"><?php echo $Page->resultadotamizaje->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_resultadotamizaje" id="so_resultadotamizaje" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->resultadotamizaje->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_resultadotamizaje" id="sv_resultadotamizaje" name="sv_resultadotamizaje" size="30" maxlength="100" placeholder="<?php echo $Page->resultadotamizaje->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->resultadotamizaje->SearchValue) ?>"<?php echo $Page->resultadotamizaje->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_6" class="ewRow">
<div id="c_repetirprueba" class="ewCell form-group">
	<label for="sv_repetirprueba" class="ewSearchCaption ewLabel"><?php echo $Page->repetirprueba->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_repetirprueba" id="so_repetirprueba" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->repetirprueba->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_repetirprueba" id="sv_repetirprueba" name="sv_repetirprueba" size="30" maxlength="100" placeholder="<?php echo $Page->repetirprueba->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->repetirprueba->SearchValue) ?>"<?php echo $Page->repetirprueba->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_7" class="ewRow">
<div id="c_parentesco" class="ewCell form-group">
	<label for="sv_parentesco" class="ewSearchCaption ewLabel"><?php echo $Page->parentesco->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_parentesco" id="so_parentesco" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->parentesco->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_parentesco" id="sv_parentesco" name="sv_parentesco" size="30" maxlength="100" placeholder="<?php echo $Page->parentesco->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->parentesco->SearchValue) ?>"<?php echo $Page->parentesco->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_8" class="ewRow">
<div id="c_discapacidad" class="ewCell form-group">
	<label for="sv_discapacidad" class="ewSearchCaption ewLabel"><?php echo $Page->discapacidad->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_discapacidad" id="so_discapacidad" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->discapacidad->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_discapacidad" id="sv_discapacidad" name="sv_discapacidad" size="30" maxlength="100" placeholder="<?php echo $Page->discapacidad->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->discapacidad->SearchValue) ?>"<?php echo $Page->discapacidad->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_9" class="ewRow">
<div id="c_tipodiscapacidad" class="ewCell form-group">
	<label for="sv_tipodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $Page->tipodiscapacidad->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_tipodiscapacidad" id="so_tipodiscapacidad" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->tipodiscapacidad->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewsaludneonatal" data-field="x_tipodiscapacidad" id="sv_tipodiscapacidad" name="sv_tipodiscapacidad" size="30" maxlength="100" placeholder="<?php echo $Page->tipodiscapacidad->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->tipodiscapacidad->SearchValue) ?>"<?php echo $Page->tipodiscapacidad->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewsaludneonatalrpt.Init();
fviewsaludneonatalrpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<div id="gmp_viewsaludneonatal" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->fecha_tamizaje->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_tamizaje"><div class="viewsaludneonatal_fecha_tamizaje"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_tamizaje->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_tamizaje">
<?php if ($Page->SortUrl($Page->fecha_tamizaje) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_fecha_tamizaje">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_tamizaje->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_fecha_tamizaje', range: false, from: '<?php echo $Page->fecha_tamizaje->RangeFrom; ?>', to: '<?php echo $Page->fecha_tamizaje->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_fecha_tamizaje<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_fecha_tamizaje" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_tamizaje) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_tamizaje->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_tamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_tamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_fecha_tamizaje', range: false, from: '<?php echo $Page->fecha_tamizaje->RangeFrom; ?>', to: '<?php echo $Page->fecha_tamizaje->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_fecha_tamizaje<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ci"><div class="viewsaludneonatal_ci"><span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ci">
<?php if ($Page->SortUrl($Page->ci) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_ci">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_ci', range: false, from: '<?php echo $Page->ci->RangeFrom; ?>', to: '<?php echo $Page->ci->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_ci<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_ci" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ci) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_ci', range: false, from: '<?php echo $Page->ci->RangeFrom; ?>', to: '<?php echo $Page->ci->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_ci<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha_nacimiento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_nacimiento"><div class="viewsaludneonatal_fecha_nacimiento"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_nacimiento">
<?php if ($Page->SortUrl($Page->fecha_nacimiento) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_fecha_nacimiento">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_fecha_nacimiento', range: false, from: '<?php echo $Page->fecha_nacimiento->RangeFrom; ?>', to: '<?php echo $Page->fecha_nacimiento->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_fecha_nacimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_fecha_nacimiento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_nacimiento) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_nacimiento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_fecha_nacimiento', range: false, from: '<?php echo $Page->fecha_nacimiento->RangeFrom; ?>', to: '<?php echo $Page->fecha_nacimiento->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_fecha_nacimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->dias->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="dias"><div class="viewsaludneonatal_dias"><span class="ewTableHeaderCaption"><?php echo $Page->dias->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="dias">
<?php if ($Page->SortUrl($Page->dias) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_dias">
			<span class="ewTableHeaderCaption"><?php echo $Page->dias->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_dias', range: false, from: '<?php echo $Page->dias->RangeFrom; ?>', to: '<?php echo $Page->dias->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_dias<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_dias" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->dias) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->dias->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->dias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->dias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_dias', range: false, from: '<?php echo $Page->dias->RangeFrom; ?>', to: '<?php echo $Page->dias->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_dias<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->semanas->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="semanas"><div class="viewsaludneonatal_semanas"><span class="ewTableHeaderCaption"><?php echo $Page->semanas->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="semanas">
<?php if ($Page->SortUrl($Page->semanas) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_semanas">
			<span class="ewTableHeaderCaption"><?php echo $Page->semanas->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_semanas', range: false, from: '<?php echo $Page->semanas->RangeFrom; ?>', to: '<?php echo $Page->semanas->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_semanas<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_semanas" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->semanas) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->semanas->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->semanas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->semanas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_semanas', range: false, from: '<?php echo $Page->semanas->RangeFrom; ?>', to: '<?php echo $Page->semanas->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_semanas<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->meses->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="meses"><div class="viewsaludneonatal_meses"><span class="ewTableHeaderCaption"><?php echo $Page->meses->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="meses">
<?php if ($Page->SortUrl($Page->meses) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_meses">
			<span class="ewTableHeaderCaption"><?php echo $Page->meses->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_meses', range: false, from: '<?php echo $Page->meses->RangeFrom; ?>', to: '<?php echo $Page->meses->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_meses<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_meses" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->meses) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->meses->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->meses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->meses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_meses', range: false, from: '<?php echo $Page->meses->RangeFrom; ?>', to: '<?php echo $Page->meses->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_meses<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sexo"><div class="viewsaludneonatal_sexo"><span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sexo">
<?php if ($Page->SortUrl($Page->sexo) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_sexo">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_sexo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sexo) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->resultado->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="resultado"><div class="viewsaludneonatal_resultado"><span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="resultado">
<?php if ($Page->SortUrl($Page->resultado) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_resultado">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_resultado', range: false, from: '<?php echo $Page->resultado->RangeFrom; ?>', to: '<?php echo $Page->resultado->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_resultado<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_resultado" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->resultado) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultado->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_resultado', range: false, from: '<?php echo $Page->resultado->RangeFrom; ?>', to: '<?php echo $Page->resultado->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_resultado<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->resultadotamizaje->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="resultadotamizaje"><div class="viewsaludneonatal_resultadotamizaje"><span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="resultadotamizaje">
<?php if ($Page->SortUrl($Page->resultadotamizaje) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_resultadotamizaje">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_resultadotamizaje', range: false, from: '<?php echo $Page->resultadotamizaje->RangeFrom; ?>', to: '<?php echo $Page->resultadotamizaje->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_resultadotamizaje<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_resultadotamizaje" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->resultadotamizaje) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->resultadotamizaje->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->resultadotamizaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->resultadotamizaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_resultadotamizaje', range: false, from: '<?php echo $Page->resultadotamizaje->RangeFrom; ?>', to: '<?php echo $Page->resultadotamizaje->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_resultadotamizaje<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tapon->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tapon"><div class="viewsaludneonatal_tapon"><span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tapon">
<?php if ($Page->SortUrl($Page->tapon) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_tapon">
			<span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_tapon', range: false, from: '<?php echo $Page->tapon->RangeFrom; ?>', to: '<?php echo $Page->tapon->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_tapon<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_tapon" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tapon) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tapon->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tapon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tapon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_tapon', range: false, from: '<?php echo $Page->tapon->RangeFrom; ?>', to: '<?php echo $Page->tapon->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_tapon<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->repetirprueba->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="repetirprueba"><div class="viewsaludneonatal_repetirprueba"><span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="repetirprueba">
<?php if ($Page->SortUrl($Page->repetirprueba) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_repetirprueba">
			<span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_repetirprueba', range: false, from: '<?php echo $Page->repetirprueba->RangeFrom; ?>', to: '<?php echo $Page->repetirprueba->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_repetirprueba<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_repetirprueba" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->repetirprueba) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->repetirprueba->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->repetirprueba->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->repetirprueba->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_repetirprueba', range: false, from: '<?php echo $Page->repetirprueba->RangeFrom; ?>', to: '<?php echo $Page->repetirprueba->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_repetirprueba<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="observaciones"><div class="viewsaludneonatal_observaciones"><span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="observaciones">
<?php if ($Page->SortUrl($Page->observaciones) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_observaciones">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_observaciones', range: false, from: '<?php echo $Page->observaciones->RangeFrom; ?>', to: '<?php echo $Page->observaciones->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_observaciones<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_observaciones" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->observaciones) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_observaciones', range: false, from: '<?php echo $Page->observaciones->RangeFrom; ?>', to: '<?php echo $Page->observaciones->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_observaciones<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->parentesco->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="parentesco"><div class="viewsaludneonatal_parentesco"><span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="parentesco">
<?php if ($Page->SortUrl($Page->parentesco) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_parentesco">
			<span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_parentesco', range: false, from: '<?php echo $Page->parentesco->RangeFrom; ?>', to: '<?php echo $Page->parentesco->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_parentesco<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_parentesco" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->parentesco) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->parentesco->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_parentesco', range: false, from: '<?php echo $Page->parentesco->RangeFrom; ?>', to: '<?php echo $Page->parentesco->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_parentesco<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombrescompleto->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombrescompleto"><div class="viewsaludneonatal_nombrescompleto"><span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombrescompleto">
<?php if ($Page->SortUrl($Page->nombrescompleto) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_nombrescompleto">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_nombrescompleto', range: false, from: '<?php echo $Page->nombrescompleto->RangeFrom; ?>', to: '<?php echo $Page->nombrescompleto->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_nombrescompleto<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_nombrescompleto" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombrescompleto) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrescompleto->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombrescompleto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombrescompleto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_nombrescompleto', range: false, from: '<?php echo $Page->nombrescompleto->RangeFrom; ?>', to: '<?php echo $Page->nombrescompleto->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_nombrescompleto<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreinstitucion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="viewsaludneonatal_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreneonatal->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreneonatal"><div class="viewsaludneonatal_nombreneonatal"><span class="ewTableHeaderCaption"><?php echo $Page->nombreneonatal->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreneonatal">
<?php if ($Page->SortUrl($Page->nombreneonatal) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_nombreneonatal">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreneonatal->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_nombreneonatal" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreneonatal) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreneonatal->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreneonatal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreneonatal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="discapacidad"><div class="viewsaludneonatal_discapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="discapacidad">
<?php if ($Page->SortUrl($Page->discapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_discapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_discapacidad', range: false, from: '<?php echo $Page->discapacidad->RangeFrom; ?>', to: '<?php echo $Page->discapacidad->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_discapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_discapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->discapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_discapacidad', range: false, from: '<?php echo $Page->discapacidad->RangeFrom; ?>', to: '<?php echo $Page->discapacidad->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_discapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipodiscapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipodiscapacidad"><div class="viewsaludneonatal_tipodiscapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipodiscapacidad">
<?php if ($Page->SortUrl($Page->tipodiscapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_tipodiscapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_tipodiscapacidad', range: false, from: '<?php echo $Page->tipodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->tipodiscapacidad->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_tipodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_tipodiscapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipodiscapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_tipodiscapacidad', range: false, from: '<?php echo $Page->tipodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->tipodiscapacidad->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_tipodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipotapo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipotapo"><div class="viewsaludneonatal_tipotapo"><span class="ewTableHeaderCaption"><?php echo $Page->tipotapo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipotapo">
<?php if ($Page->SortUrl($Page->tipotapo) == "") { ?>
		<div class="ewTableHeaderBtn viewsaludneonatal_tipotapo">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipotapo->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_tipotapo', range: false, from: '<?php echo $Page->tipotapo->RangeFrom; ?>', to: '<?php echo $Page->tipotapo->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_tipotapo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewsaludneonatal_tipotapo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipotapo) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipotapo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipotapo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipotapo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewsaludneonatal_tipotapo', range: false, from: '<?php echo $Page->tipotapo->RangeFrom; ?>', to: '<?php echo $Page->tipotapo->RangeTo; ?>', url: 'viewsaludneonatalrpt.php' });" id="x_tipotapo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
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
<?php if ($Page->fecha_tamizaje->Visible) { ?>
		<td data-field="fecha_tamizaje"<?php echo $Page->fecha_tamizaje->CellAttributes() ?>>
<span<?php echo $Page->fecha_tamizaje->ViewAttributes() ?>><?php echo $Page->fecha_tamizaje->ListViewValue() ?></span></td>
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
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes() ?>>
<span<?php echo $Page->nombreinstitucion->ViewAttributes() ?>><?php echo $Page->nombreinstitucion->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombreneonatal->Visible) { ?>
		<td data-field="nombreneonatal"<?php echo $Page->nombreneonatal->CellAttributes() ?>>
<span<?php echo $Page->nombreneonatal->ViewAttributes() ?>><?php echo $Page->nombreneonatal->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
		<td data-field="discapacidad"<?php echo $Page->discapacidad->CellAttributes() ?>>
<span<?php echo $Page->discapacidad->ViewAttributes() ?>><?php echo $Page->discapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipodiscapacidad->Visible) { ?>
		<td data-field="tipodiscapacidad"<?php echo $Page->tipodiscapacidad->CellAttributes() ?>>
<span<?php echo $Page->tipodiscapacidad->ViewAttributes() ?>><?php echo $Page->tipodiscapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipotapo->Visible) { ?>
		<td data-field="tipotapo"<?php echo $Page->tipotapo->CellAttributes() ?>>
<span<?php echo $Page->tipotapo->ViewAttributes() ?>><?php echo $Page->tipotapo->ListViewValue() ?></span></td>
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
<div id="gmp_viewsaludneonatal" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
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
<?php include "viewsaludneonatalrptpager.php" ?>
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
