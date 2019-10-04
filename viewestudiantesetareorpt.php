<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewestudiantesetareorptinfo.php" ?>
<?php

//
// Page class
//

$viewestudiantesetareo_rpt = NULL; // Initialize page object first

class crviewestudiantesetareo_rpt extends crviewestudiantesetareo {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewestudiantesetareo_rpt';

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

		// Table object (viewestudiantesetareo)
		if (!isset($GLOBALS["viewestudiantesetareo"])) {
			$GLOBALS["viewestudiantesetareo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewestudiantesetareo"];
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
			define("EWR_TABLE_NAME", 'viewestudiantesetareo', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewestudiantesetareorpt";

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

		// Process generate request
		$this->ProcessGenRequest();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin(); // Auto login
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewestudiantesetareo');
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

		// Generate request URL
		if ($this->GenerateRequestUrl()) {
			$this->Page_Terminate();
			exit();
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

	// Generate request URL
	function GenerateRequestUrl() {
		global $Security, $ReportLanguage, $grLanguage, $ReportOptions;
		global $UserTableConn;
		$post = $_POST;

		// Check if create URL request
		if (count($post) == 0 || @$post["generateurl"] <> "1" || @$post["reporttype"] == "") {
			$UserNameList = array();
			if ($Security->IsSysAdmin())
				$UserNameList["@@admin"] = $ReportLanguage->Phrase("ReportFormUserDefault");

			// Get list of user names
			$sSql = EWR_LOGIN_SELECT_SQL;
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					$usr = $rs->fields('login');
					$userid = $rs->fields('login');
					if (!$Security->IsSysAdmin() && !in_array(strval($userid), $Security->UserID))
						$usr = "";
					$lvl = $rs->fields('id_rol');
					$priv = $Security->GetUserLevelPrivEx($this->ProjectID . $this->TableName, $lvl);
					if (($priv & EWR_ALLOW_REPORT) <> EWR_ALLOW_REPORT)
						$usr = "";
					if ($usr <> "")
						$UserNameList[$usr] = $usr;
					$rs->MoveNext();
				}
			}
			$ReportOptions["UserNameList"] = $UserNameList;
			$ReportOptions["ShowFilter"] = FALSE;
			return FALSE;
		}

		// Check if login
		if (!$Security->IsLoggedIn())
			return FALSE;

		// Get username/password
		$usr = @$post["username"];
		$pwd = "";
		if ($usr == "@@admin") {
			$usr = EWR_ADMIN_USER_NAME;
			$pwd = EWR_ADMIN_PASSWORD;
		} else {
			$sFilter = str_replace("%u", ewr_AdjustSql($usr, EWR_USER_TABLE_DBID), EWR_USER_NAME_FILTER);
			$sSql = EWR_LOGIN_SELECT_SQL . " WHERE " . $sFilter;
			if ($rs = $UserTableConn->Execute($sSql)) {
				if (!$rs->EOF)
					$pwd = $rs->fields('password');
			}
		}
		if ($usr == "" || $pwd == "") // No user specified
			return FALSE;
		$usr = ewr_Encrypt($usr, EWR_REPORT_LOG_ENCRYPT_KEY);
		$pwd = ewr_Encrypt($pwd, EWR_REPORT_LOG_ENCRYPT_KEY);

		// Set report parameters
		$reportType = @$post["reporttype"];
		$genKey = ewr_Encrypt($this->TableVar, EWR_REPORT_LOG_ENCRYPT_KEY);
		$url = ewr_FullUrl();
		$url .= "?reporttype=" . $reportType . "&k=" . urlencode($genKey) . "&u=" . urlencode($usr) . "&p=" . urlencode($pwd);
		if ($reportType == "email") {
			$sender = @$post["sender"];
			$recipient = @$post["recipient"];
			$cc = @$post["cc"];
			$bcc = @$post["bcc"];
			$subject = @$post["subject"];
			if ($sender == "" || $recipient == "" || $subject == "")
				return FALSE;
			$url .= "&sender=" . urlencode($sender) . "&recipient=" . urlencode($recipient) . "&subject=" . urlencode($subject);
			if ($cc <> "") $url .= "&cc=" . urlencode($cc);
			if ($bcc <> "") $url .= "&bcc=" . urlencode($bcc);
		}
		$pageOption = @$post["pageoption"];
		$url .= ($pageOption == "all") ? "&exportall=1" : "&exportall=0&start=1"; // All pages / First page

		// Set report filter
		$filterName = @$post["filtername"];
		if ($filterName == "")
			$filterName = "_none";
		elseif ($filterName == "@@current")
			$filterName = "_user";
		$url .= "&filtername=" . urlencode($filterName);
		$filter = json_decode(@$post["filter"], TRUE);
		if (is_array($filter)) {
			foreach ($filter as $key => $val)
				$url .= "&" . $key . "=" . urlencode($val);
		}

		// Set response type
		$responseType = @$post["responsetype"];
		$url .= "&responsetype=" . urlencode($responseType);

		// Set show current filter
		$showCurrentFilter = @$post["showcurrentfilter"];
		$url .= "&showfilter=" . ($showCurrentFilter == "1" ? "1" : "0");
		echo json_encode(array("url" => $url));
		return TRUE;
	}

	// Process generate request
	function ProcessGenRequest() {
		global $Security, $ReportLanguage;
		$ar = ewr_IsHttpPost() ? $_POST : $_GET;
		$genType = @$ar["reporttype"];
		$genKey = @$ar["k"];
		if (array_key_exists("k", $ar)) // Remove key
			unset($ar["k"]);
		if (ewr_Decrypt($genKey, EWR_REPORT_LOG_ENCRYPT_KEY) == $this->TableVar && $genType <> "") {
			$usr = @$ar["u"];
			$usr = ewr_Decrypt($usr, EWR_REPORT_LOG_ENCRYPT_KEY);
			if (array_key_exists("u", $ar)) // Update actual user name
				$ar["u"] = $usr;
			$pwd = @$ar["p"];
			if (array_key_exists("p", $ar)) // Remove password
				unset($ar["p"]);
			$pwd = ewr_Decrypt($pwd, EWR_REPORT_LOG_ENCRYPT_KEY);
			$encrypted = @$ar["encrypted"] == "1";
			$bLogin = $Security->ValidateUser($usr, $pwd, FALSE, $encrypted); // Manual login
			if (!$bLogin) {
				echo ewr_DeniedMsg();
				exit();
			} else {
				if ($genType == "html") $genType = "print";
				$this->Export = $genType;
				$this->ShowCurrentFilter = FALSE;
				if (@$ar["exportall"] <> "") // Export all option specified
					$this->ExportAll = $ar["exportall"] == "1";
				$this->GenOptions = $this->GetGenOptions($ar); // Set up generate options
				$this->SetupGenFilterList($ar); // Update filter list
			}
		}
	}

	// Generate file extension
	function GenFileExt($genType) {
		if ($genType == "print" || $genType == "html")
			return "html";
		elseif ($genType == "excel")
			return "xls";
		elseif ($genType == "word")
			return "doc";
		elseif ($genType == "pdf")
			return "pdf";
		else
			return $genType;
	}

	// Get Generate options
	function GetGenOptions($ar) {
		$options = array();
		$options["parms"] = json_encode($ar);

		// Set up gen type / filename
		$genType = @$ar["reporttype"];
		$options["reporttype"] = $genType;
		$options["filtername"] = @$ar["filtername"];
		$options["responsetype"] = @$ar["responsetype"];
		if ($genType == "email") { // Email
			$options["sender"] = @$ar["sender"];
			$options["recipient"] = @$ar["recipient"];
			$options["subject"] = @$ar["subject"];
		} else {
			$options["folder"] = (@$ar["folder"] <> "") ? $ar["folder"] : EWR_UPLOAD_DEST_PATH;
			$options["filename"] = (@$ar["filename"] <> "") ? $ar["filename"] : $this->TableVar . "_" . ewr_RandomGuid() . "." . $this->GenFileExt($genType);
		}

		// Paging
		$options["start"] = @$ar["start"]; // Start
		$options["pageno"] = @$ar["pageno"]; // Page number
		$options["grpperpage"] = @$ar["grpperpage"]; // Group per page

		// Sort
		$options["order"] = @$ar["order"]; // Order by
		$options["ordertype"] = @$ar["ordertype"]; // ASC/DESC
		if ($options["order"] == "") // Reset sort if not specified
			$options["resetsort"] = "1";
		$options["showfilter"] = @$ar["showfilter"]; // Show current filter
		return $options;
	}

	// Set up generate filter
	function SetupGenFilterList($ar) {
		return FALSE;
	}

	// Write generate response
	function WriteGenResponse($genurl) {
		if ($genurl <> "") {
			$genType = @$this->GenOptions["reporttype"];
			$responseType = @$this->GenOptions["responsetype"];
			if ($responseType == "json" || $genType == "email")
				echo json_encode(array("url" => $genurl));
		}
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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewestudiantesetareo\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewestudiantesetareo',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewestudiantesetareorpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewestudiantesetareorpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Button to create generate URL
		$item = &$this->GenerateOptions->Add("generateurl");
		$item->Body = "<a type=\"button\" title=\"" . $ReportLanguage->Phrase("GenerateReportUrl", TRUE) . "\" onclick=\"ewr_ModalGenerateUrlShow(event);\" class=\"ewGenerateUrlBtn btn btn-default\"><span class=\"glyphicon glyphicon-link ewIcon\"></span></a>";
		$item->Visible = $Security->IsLoggedIn(); // Check if logged in
		$this->GenerateOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->GenerateOptions->Add($this->GenerateOptions->GroupOptionName);
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewestudiantesetareorpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->_0_3F->SetVisibility();
		$this->_4_6F->SetVisibility();
		$this->_7_9F->SetVisibility();
		$this->_10_12F->SetVisibility();
		$this->_13_15F->SetVisibility();
		$this->_16_18F->SetVisibility();
		$this->_19F->SetVisibility();
		$this->_0_3M->SetVisibility();
		$this->_4_6M->SetVisibility();
		$this->_7_9M->SetVisibility();
		$this->_10_12M->SetVisibility();
		$this->_13_15M->SetVisibility();
		$this->_16_18M->SetVisibility();
		$this->_19M->SetVisibility();

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
				$this->FirstRowData['_0_3F'] = ewr_Conv($rs->fields('0-3F'), 131);
				$this->FirstRowData['_4_6F'] = ewr_Conv($rs->fields('4-6F'), 131);
				$this->FirstRowData['_7_9F'] = ewr_Conv($rs->fields('7-9F'), 131);
				$this->FirstRowData['_10_12F'] = ewr_Conv($rs->fields('10-12F'), 131);
				$this->FirstRowData['_13_15F'] = ewr_Conv($rs->fields('13-15F'), 131);
				$this->FirstRowData['_16_18F'] = ewr_Conv($rs->fields('16-18F'), 131);
				$this->FirstRowData['_19F'] = ewr_Conv($rs->fields('19F'), 131);
				$this->FirstRowData['_0_3M'] = ewr_Conv($rs->fields('0-3M'), 131);
				$this->FirstRowData['_4_6M'] = ewr_Conv($rs->fields('4-6M'), 131);
				$this->FirstRowData['_7_9M'] = ewr_Conv($rs->fields('7-9M'), 131);
				$this->FirstRowData['_10_12M'] = ewr_Conv($rs->fields('10-12M'), 20);
				$this->FirstRowData['_13_15M'] = ewr_Conv($rs->fields('13-15M'), 131);
				$this->FirstRowData['_16_18M'] = ewr_Conv($rs->fields('16-18M'), 131);
				$this->FirstRowData['_19M'] = ewr_Conv($rs->fields('19M'), 131);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->nombreinstitucion->setDbValue($rs->fields('nombreinstitucion'));
			$this->_0_3F->setDbValue($rs->fields('0-3F'));
			$this->_4_6F->setDbValue($rs->fields('4-6F'));
			$this->_7_9F->setDbValue($rs->fields('7-9F'));
			$this->_10_12F->setDbValue($rs->fields('10-12F'));
			$this->_13_15F->setDbValue($rs->fields('13-15F'));
			$this->_16_18F->setDbValue($rs->fields('16-18F'));
			$this->_19F->setDbValue($rs->fields('19F'));
			$this->_0_3M->setDbValue($rs->fields('0-3M'));
			$this->_4_6M->setDbValue($rs->fields('4-6M'));
			$this->_7_9M->setDbValue($rs->fields('7-9M'));
			$this->_10_12M->setDbValue($rs->fields('10-12M'));
			$this->_13_15M->setDbValue($rs->fields('13-15M'));
			$this->_16_18M->setDbValue($rs->fields('16-18M'));
			$this->_19M->setDbValue($rs->fields('19M'));
			$this->Val[1] = $this->nombreinstitucion->CurrentValue;
			$this->Val[2] = $this->_0_3F->CurrentValue;
			$this->Val[3] = $this->_4_6F->CurrentValue;
			$this->Val[4] = $this->_7_9F->CurrentValue;
			$this->Val[5] = $this->_10_12F->CurrentValue;
			$this->Val[6] = $this->_13_15F->CurrentValue;
			$this->Val[7] = $this->_16_18F->CurrentValue;
			$this->Val[8] = $this->_19F->CurrentValue;
			$this->Val[9] = $this->_0_3M->CurrentValue;
			$this->Val[10] = $this->_4_6M->CurrentValue;
			$this->Val[11] = $this->_7_9M->CurrentValue;
			$this->Val[12] = $this->_10_12M->CurrentValue;
			$this->Val[13] = $this->_13_15M->CurrentValue;
			$this->Val[14] = $this->_16_18M->CurrentValue;
			$this->Val[15] = $this->_19M->CurrentValue;
		} else {
			$this->nombreinstitucion->setDbValue("");
			$this->_0_3F->setDbValue("");
			$this->_4_6F->setDbValue("");
			$this->_7_9F->setDbValue("");
			$this->_10_12F->setDbValue("");
			$this->_13_15F->setDbValue("");
			$this->_16_18F->setDbValue("");
			$this->_19F->setDbValue("");
			$this->_0_3M->setDbValue("");
			$this->_4_6M->setDbValue("");
			$this->_7_9M->setDbValue("");
			$this->_10_12M->setDbValue("");
			$this->_13_15M->setDbValue("");
			$this->_16_18M->setDbValue("");
			$this->_19M->setDbValue("");
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

			// 0-3F
			$this->_0_3F->HrefValue = "";

			// 4-6F
			$this->_4_6F->HrefValue = "";

			// 7-9F
			$this->_7_9F->HrefValue = "";

			// 10-12F
			$this->_10_12F->HrefValue = "";

			// 13-15F
			$this->_13_15F->HrefValue = "";

			// 16-18F
			$this->_16_18F->HrefValue = "";

			// 19F
			$this->_19F->HrefValue = "";

			// 0-3M
			$this->_0_3M->HrefValue = "";

			// 4-6M
			$this->_4_6M->HrefValue = "";

			// 7-9M
			$this->_7_9M->HrefValue = "";

			// 10-12M
			$this->_10_12M->HrefValue = "";

			// 13-15M
			$this->_13_15M->HrefValue = "";

			// 16-18M
			$this->_16_18M->HrefValue = "";

			// 19M
			$this->_19M->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// nombreinstitucion
			$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
			$this->nombreinstitucion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 0-3F
			$this->_0_3F->ViewValue = $this->_0_3F->CurrentValue;
			$this->_0_3F->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 4-6F
			$this->_4_6F->ViewValue = $this->_4_6F->CurrentValue;
			$this->_4_6F->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 7-9F
			$this->_7_9F->ViewValue = $this->_7_9F->CurrentValue;
			$this->_7_9F->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 10-12F
			$this->_10_12F->ViewValue = $this->_10_12F->CurrentValue;
			$this->_10_12F->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 13-15F
			$this->_13_15F->ViewValue = $this->_13_15F->CurrentValue;
			$this->_13_15F->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 16-18F
			$this->_16_18F->ViewValue = $this->_16_18F->CurrentValue;
			$this->_16_18F->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 19F
			$this->_19F->ViewValue = $this->_19F->CurrentValue;
			$this->_19F->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 0-3M
			$this->_0_3M->ViewValue = $this->_0_3M->CurrentValue;
			$this->_0_3M->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 4-6M
			$this->_4_6M->ViewValue = $this->_4_6M->CurrentValue;
			$this->_4_6M->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 7-9M
			$this->_7_9M->ViewValue = $this->_7_9M->CurrentValue;
			$this->_7_9M->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 10-12M
			$this->_10_12M->ViewValue = $this->_10_12M->CurrentValue;
			$this->_10_12M->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 13-15M
			$this->_13_15M->ViewValue = $this->_13_15M->CurrentValue;
			$this->_13_15M->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 16-18M
			$this->_16_18M->ViewValue = $this->_16_18M->CurrentValue;
			$this->_16_18M->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// 19M
			$this->_19M->ViewValue = $this->_19M->CurrentValue;
			$this->_19M->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";

			// 0-3F
			$this->_0_3F->HrefValue = "";

			// 4-6F
			$this->_4_6F->HrefValue = "";

			// 7-9F
			$this->_7_9F->HrefValue = "";

			// 10-12F
			$this->_10_12F->HrefValue = "";

			// 13-15F
			$this->_13_15F->HrefValue = "";

			// 16-18F
			$this->_16_18F->HrefValue = "";

			// 19F
			$this->_19F->HrefValue = "";

			// 0-3M
			$this->_0_3M->HrefValue = "";

			// 4-6M
			$this->_4_6M->HrefValue = "";

			// 7-9M
			$this->_7_9M->HrefValue = "";

			// 10-12M
			$this->_10_12M->HrefValue = "";

			// 13-15M
			$this->_13_15M->HrefValue = "";

			// 16-18M
			$this->_16_18M->HrefValue = "";

			// 19M
			$this->_19M->HrefValue = "";
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

			// 0-3F
			$CurrentValue = $this->_0_3F->CurrentValue;
			$ViewValue = &$this->_0_3F->ViewValue;
			$ViewAttrs = &$this->_0_3F->ViewAttrs;
			$CellAttrs = &$this->_0_3F->CellAttrs;
			$HrefValue = &$this->_0_3F->HrefValue;
			$LinkAttrs = &$this->_0_3F->LinkAttrs;
			$this->Cell_Rendered($this->_0_3F, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 4-6F
			$CurrentValue = $this->_4_6F->CurrentValue;
			$ViewValue = &$this->_4_6F->ViewValue;
			$ViewAttrs = &$this->_4_6F->ViewAttrs;
			$CellAttrs = &$this->_4_6F->CellAttrs;
			$HrefValue = &$this->_4_6F->HrefValue;
			$LinkAttrs = &$this->_4_6F->LinkAttrs;
			$this->Cell_Rendered($this->_4_6F, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 7-9F
			$CurrentValue = $this->_7_9F->CurrentValue;
			$ViewValue = &$this->_7_9F->ViewValue;
			$ViewAttrs = &$this->_7_9F->ViewAttrs;
			$CellAttrs = &$this->_7_9F->CellAttrs;
			$HrefValue = &$this->_7_9F->HrefValue;
			$LinkAttrs = &$this->_7_9F->LinkAttrs;
			$this->Cell_Rendered($this->_7_9F, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 10-12F
			$CurrentValue = $this->_10_12F->CurrentValue;
			$ViewValue = &$this->_10_12F->ViewValue;
			$ViewAttrs = &$this->_10_12F->ViewAttrs;
			$CellAttrs = &$this->_10_12F->CellAttrs;
			$HrefValue = &$this->_10_12F->HrefValue;
			$LinkAttrs = &$this->_10_12F->LinkAttrs;
			$this->Cell_Rendered($this->_10_12F, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 13-15F
			$CurrentValue = $this->_13_15F->CurrentValue;
			$ViewValue = &$this->_13_15F->ViewValue;
			$ViewAttrs = &$this->_13_15F->ViewAttrs;
			$CellAttrs = &$this->_13_15F->CellAttrs;
			$HrefValue = &$this->_13_15F->HrefValue;
			$LinkAttrs = &$this->_13_15F->LinkAttrs;
			$this->Cell_Rendered($this->_13_15F, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 16-18F
			$CurrentValue = $this->_16_18F->CurrentValue;
			$ViewValue = &$this->_16_18F->ViewValue;
			$ViewAttrs = &$this->_16_18F->ViewAttrs;
			$CellAttrs = &$this->_16_18F->CellAttrs;
			$HrefValue = &$this->_16_18F->HrefValue;
			$LinkAttrs = &$this->_16_18F->LinkAttrs;
			$this->Cell_Rendered($this->_16_18F, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 19F
			$CurrentValue = $this->_19F->CurrentValue;
			$ViewValue = &$this->_19F->ViewValue;
			$ViewAttrs = &$this->_19F->ViewAttrs;
			$CellAttrs = &$this->_19F->CellAttrs;
			$HrefValue = &$this->_19F->HrefValue;
			$LinkAttrs = &$this->_19F->LinkAttrs;
			$this->Cell_Rendered($this->_19F, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 0-3M
			$CurrentValue = $this->_0_3M->CurrentValue;
			$ViewValue = &$this->_0_3M->ViewValue;
			$ViewAttrs = &$this->_0_3M->ViewAttrs;
			$CellAttrs = &$this->_0_3M->CellAttrs;
			$HrefValue = &$this->_0_3M->HrefValue;
			$LinkAttrs = &$this->_0_3M->LinkAttrs;
			$this->Cell_Rendered($this->_0_3M, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 4-6M
			$CurrentValue = $this->_4_6M->CurrentValue;
			$ViewValue = &$this->_4_6M->ViewValue;
			$ViewAttrs = &$this->_4_6M->ViewAttrs;
			$CellAttrs = &$this->_4_6M->CellAttrs;
			$HrefValue = &$this->_4_6M->HrefValue;
			$LinkAttrs = &$this->_4_6M->LinkAttrs;
			$this->Cell_Rendered($this->_4_6M, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 7-9M
			$CurrentValue = $this->_7_9M->CurrentValue;
			$ViewValue = &$this->_7_9M->ViewValue;
			$ViewAttrs = &$this->_7_9M->ViewAttrs;
			$CellAttrs = &$this->_7_9M->CellAttrs;
			$HrefValue = &$this->_7_9M->HrefValue;
			$LinkAttrs = &$this->_7_9M->LinkAttrs;
			$this->Cell_Rendered($this->_7_9M, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 10-12M
			$CurrentValue = $this->_10_12M->CurrentValue;
			$ViewValue = &$this->_10_12M->ViewValue;
			$ViewAttrs = &$this->_10_12M->ViewAttrs;
			$CellAttrs = &$this->_10_12M->CellAttrs;
			$HrefValue = &$this->_10_12M->HrefValue;
			$LinkAttrs = &$this->_10_12M->LinkAttrs;
			$this->Cell_Rendered($this->_10_12M, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 13-15M
			$CurrentValue = $this->_13_15M->CurrentValue;
			$ViewValue = &$this->_13_15M->ViewValue;
			$ViewAttrs = &$this->_13_15M->ViewAttrs;
			$CellAttrs = &$this->_13_15M->CellAttrs;
			$HrefValue = &$this->_13_15M->HrefValue;
			$LinkAttrs = &$this->_13_15M->LinkAttrs;
			$this->Cell_Rendered($this->_13_15M, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 16-18M
			$CurrentValue = $this->_16_18M->CurrentValue;
			$ViewValue = &$this->_16_18M->ViewValue;
			$ViewAttrs = &$this->_16_18M->ViewAttrs;
			$CellAttrs = &$this->_16_18M->CellAttrs;
			$HrefValue = &$this->_16_18M->HrefValue;
			$LinkAttrs = &$this->_16_18M->LinkAttrs;
			$this->Cell_Rendered($this->_16_18M, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// 19M
			$CurrentValue = $this->_19M->CurrentValue;
			$ViewValue = &$this->_19M->ViewValue;
			$ViewAttrs = &$this->_19M->ViewAttrs;
			$CellAttrs = &$this->_19M->CellAttrs;
			$HrefValue = &$this->_19M->HrefValue;
			$LinkAttrs = &$this->_19M->LinkAttrs;
			$this->Cell_Rendered($this->_19M, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->_0_3F->Visible) $this->DtlColumnCount += 1;
		if ($this->_4_6F->Visible) $this->DtlColumnCount += 1;
		if ($this->_7_9F->Visible) $this->DtlColumnCount += 1;
		if ($this->_10_12F->Visible) $this->DtlColumnCount += 1;
		if ($this->_13_15F->Visible) $this->DtlColumnCount += 1;
		if ($this->_16_18F->Visible) $this->DtlColumnCount += 1;
		if ($this->_19F->Visible) $this->DtlColumnCount += 1;
		if ($this->_0_3M->Visible) $this->DtlColumnCount += 1;
		if ($this->_4_6M->Visible) $this->DtlColumnCount += 1;
		if ($this->_7_9M->Visible) $this->DtlColumnCount += 1;
		if ($this->_10_12M->Visible) $this->DtlColumnCount += 1;
		if ($this->_13_15M->Visible) $this->DtlColumnCount += 1;
		if ($this->_16_18M->Visible) $this->DtlColumnCount += 1;
		if ($this->_19M->Visible) $this->DtlColumnCount += 1;
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
			$this->_0_3F->setSort("");
			$this->_4_6F->setSort("");
			$this->_7_9F->setSort("");
			$this->_10_12F->setSort("");
			$this->_13_15F->setSort("");
			$this->_16_18F->setSort("");
			$this->_19F->setSort("");
			$this->_0_3M->setSort("");
			$this->_4_6M->setSort("");
			$this->_7_9M->setSort("");
			$this->_10_12M->setSort("");
			$this->_13_15M->setSort("");
			$this->_16_18M->setSort("");
			$this->_19M->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->nombreinstitucion); // nombreinstitucion
			$this->UpdateSort($this->_0_3F); // 0-3F
			$this->UpdateSort($this->_4_6F); // 4-6F
			$this->UpdateSort($this->_7_9F); // 7-9F
			$this->UpdateSort($this->_10_12F); // 10-12F
			$this->UpdateSort($this->_13_15F); // 13-15F
			$this->UpdateSort($this->_16_18F); // 16-18F
			$this->UpdateSort($this->_19F); // 19F
			$this->UpdateSort($this->_0_3M); // 0-3M
			$this->UpdateSort($this->_4_6M); // 4-6M
			$this->UpdateSort($this->_7_9M); // 7-9M
			$this->UpdateSort($this->_10_12M); // 10-12M
			$this->UpdateSort($this->_13_15M); // 13-15M
			$this->UpdateSort($this->_16_18M); // 16-18M
			$this->UpdateSort($this->_19M); // 19M
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
if (!isset($viewestudiantesetareo_rpt)) $viewestudiantesetareo_rpt = new crviewestudiantesetareo_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewestudiantesetareo_rpt;

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
var viewestudiantesetareo_rpt = new ewr_Page("viewestudiantesetareo_rpt");

// Page properties
viewestudiantesetareo_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewestudiantesetareo_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
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
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-header ewGridUpperPanel">
<?php include "viewestudiantesetareorptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_viewestudiantesetareo" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->nombreinstitucion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="viewestudiantesetareo_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_0_3F->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_0_3F"><div class="viewestudiantesetareo__0_3F"><span class="ewTableHeaderCaption"><?php echo $Page->_0_3F->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_0_3F">
<?php if ($Page->SortUrl($Page->_0_3F) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__0_3F">
			<span class="ewTableHeaderCaption"><?php echo $Page->_0_3F->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__0_3F" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_0_3F) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_0_3F->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_0_3F->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_0_3F->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_4_6F->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_4_6F"><div class="viewestudiantesetareo__4_6F"><span class="ewTableHeaderCaption"><?php echo $Page->_4_6F->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_4_6F">
<?php if ($Page->SortUrl($Page->_4_6F) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__4_6F">
			<span class="ewTableHeaderCaption"><?php echo $Page->_4_6F->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__4_6F" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_4_6F) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_4_6F->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_4_6F->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_4_6F->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_7_9F->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_7_9F"><div class="viewestudiantesetareo__7_9F"><span class="ewTableHeaderCaption"><?php echo $Page->_7_9F->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_7_9F">
<?php if ($Page->SortUrl($Page->_7_9F) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__7_9F">
			<span class="ewTableHeaderCaption"><?php echo $Page->_7_9F->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__7_9F" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_7_9F) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_7_9F->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_7_9F->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_7_9F->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_10_12F->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_10_12F"><div class="viewestudiantesetareo__10_12F"><span class="ewTableHeaderCaption"><?php echo $Page->_10_12F->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_10_12F">
<?php if ($Page->SortUrl($Page->_10_12F) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__10_12F">
			<span class="ewTableHeaderCaption"><?php echo $Page->_10_12F->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__10_12F" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_10_12F) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_10_12F->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_10_12F->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_10_12F->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_13_15F->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_13_15F"><div class="viewestudiantesetareo__13_15F"><span class="ewTableHeaderCaption"><?php echo $Page->_13_15F->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_13_15F">
<?php if ($Page->SortUrl($Page->_13_15F) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__13_15F">
			<span class="ewTableHeaderCaption"><?php echo $Page->_13_15F->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__13_15F" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_13_15F) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_13_15F->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_13_15F->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_13_15F->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_16_18F->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_16_18F"><div class="viewestudiantesetareo__16_18F"><span class="ewTableHeaderCaption"><?php echo $Page->_16_18F->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_16_18F">
<?php if ($Page->SortUrl($Page->_16_18F) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__16_18F">
			<span class="ewTableHeaderCaption"><?php echo $Page->_16_18F->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__16_18F" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_16_18F) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_16_18F->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_16_18F->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_16_18F->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_19F->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_19F"><div class="viewestudiantesetareo__19F"><span class="ewTableHeaderCaption"><?php echo $Page->_19F->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_19F">
<?php if ($Page->SortUrl($Page->_19F) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__19F">
			<span class="ewTableHeaderCaption"><?php echo $Page->_19F->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__19F" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_19F) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_19F->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_19F->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_19F->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_0_3M->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_0_3M"><div class="viewestudiantesetareo__0_3M"><span class="ewTableHeaderCaption"><?php echo $Page->_0_3M->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_0_3M">
<?php if ($Page->SortUrl($Page->_0_3M) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__0_3M">
			<span class="ewTableHeaderCaption"><?php echo $Page->_0_3M->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__0_3M" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_0_3M) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_0_3M->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_0_3M->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_0_3M->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_4_6M->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_4_6M"><div class="viewestudiantesetareo__4_6M"><span class="ewTableHeaderCaption"><?php echo $Page->_4_6M->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_4_6M">
<?php if ($Page->SortUrl($Page->_4_6M) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__4_6M">
			<span class="ewTableHeaderCaption"><?php echo $Page->_4_6M->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__4_6M" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_4_6M) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_4_6M->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_4_6M->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_4_6M->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_7_9M->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_7_9M"><div class="viewestudiantesetareo__7_9M"><span class="ewTableHeaderCaption"><?php echo $Page->_7_9M->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_7_9M">
<?php if ($Page->SortUrl($Page->_7_9M) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__7_9M">
			<span class="ewTableHeaderCaption"><?php echo $Page->_7_9M->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__7_9M" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_7_9M) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_7_9M->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_7_9M->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_7_9M->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_10_12M->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_10_12M"><div class="viewestudiantesetareo__10_12M"><span class="ewTableHeaderCaption"><?php echo $Page->_10_12M->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_10_12M">
<?php if ($Page->SortUrl($Page->_10_12M) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__10_12M">
			<span class="ewTableHeaderCaption"><?php echo $Page->_10_12M->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__10_12M" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_10_12M) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_10_12M->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_10_12M->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_10_12M->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_13_15M->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_13_15M"><div class="viewestudiantesetareo__13_15M"><span class="ewTableHeaderCaption"><?php echo $Page->_13_15M->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_13_15M">
<?php if ($Page->SortUrl($Page->_13_15M) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__13_15M">
			<span class="ewTableHeaderCaption"><?php echo $Page->_13_15M->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__13_15M" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_13_15M) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_13_15M->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_13_15M->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_13_15M->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_16_18M->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_16_18M"><div class="viewestudiantesetareo__16_18M"><span class="ewTableHeaderCaption"><?php echo $Page->_16_18M->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_16_18M">
<?php if ($Page->SortUrl($Page->_16_18M) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__16_18M">
			<span class="ewTableHeaderCaption"><?php echo $Page->_16_18M->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__16_18M" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_16_18M) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_16_18M->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_16_18M->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_16_18M->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_19M->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_19M"><div class="viewestudiantesetareo__19M"><span class="ewTableHeaderCaption"><?php echo $Page->_19M->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_19M">
<?php if ($Page->SortUrl($Page->_19M) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo__19M">
			<span class="ewTableHeaderCaption"><?php echo $Page->_19M->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo__19M" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->_19M) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->_19M->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->_19M->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->_19M->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
<?php if ($Page->_0_3F->Visible) { ?>
		<td data-field="_0_3F"<?php echo $Page->_0_3F->CellAttributes() ?>>
<span<?php echo $Page->_0_3F->ViewAttributes() ?>><?php echo $Page->_0_3F->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_4_6F->Visible) { ?>
		<td data-field="_4_6F"<?php echo $Page->_4_6F->CellAttributes() ?>>
<span<?php echo $Page->_4_6F->ViewAttributes() ?>><?php echo $Page->_4_6F->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_7_9F->Visible) { ?>
		<td data-field="_7_9F"<?php echo $Page->_7_9F->CellAttributes() ?>>
<span<?php echo $Page->_7_9F->ViewAttributes() ?>><?php echo $Page->_7_9F->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_10_12F->Visible) { ?>
		<td data-field="_10_12F"<?php echo $Page->_10_12F->CellAttributes() ?>>
<span<?php echo $Page->_10_12F->ViewAttributes() ?>><?php echo $Page->_10_12F->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_13_15F->Visible) { ?>
		<td data-field="_13_15F"<?php echo $Page->_13_15F->CellAttributes() ?>>
<span<?php echo $Page->_13_15F->ViewAttributes() ?>><?php echo $Page->_13_15F->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_16_18F->Visible) { ?>
		<td data-field="_16_18F"<?php echo $Page->_16_18F->CellAttributes() ?>>
<span<?php echo $Page->_16_18F->ViewAttributes() ?>><?php echo $Page->_16_18F->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_19F->Visible) { ?>
		<td data-field="_19F"<?php echo $Page->_19F->CellAttributes() ?>>
<span<?php echo $Page->_19F->ViewAttributes() ?>><?php echo $Page->_19F->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_0_3M->Visible) { ?>
		<td data-field="_0_3M"<?php echo $Page->_0_3M->CellAttributes() ?>>
<span<?php echo $Page->_0_3M->ViewAttributes() ?>><?php echo $Page->_0_3M->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_4_6M->Visible) { ?>
		<td data-field="_4_6M"<?php echo $Page->_4_6M->CellAttributes() ?>>
<span<?php echo $Page->_4_6M->ViewAttributes() ?>><?php echo $Page->_4_6M->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_7_9M->Visible) { ?>
		<td data-field="_7_9M"<?php echo $Page->_7_9M->CellAttributes() ?>>
<span<?php echo $Page->_7_9M->ViewAttributes() ?>><?php echo $Page->_7_9M->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_10_12M->Visible) { ?>
		<td data-field="_10_12M"<?php echo $Page->_10_12M->CellAttributes() ?>>
<span<?php echo $Page->_10_12M->ViewAttributes() ?>><?php echo $Page->_10_12M->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_13_15M->Visible) { ?>
		<td data-field="_13_15M"<?php echo $Page->_13_15M->CellAttributes() ?>>
<span<?php echo $Page->_13_15M->ViewAttributes() ?>><?php echo $Page->_13_15M->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_16_18M->Visible) { ?>
		<td data-field="_16_18M"<?php echo $Page->_16_18M->CellAttributes() ?>>
<span<?php echo $Page->_16_18M->ViewAttributes() ?>><?php echo $Page->_16_18M->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_19M->Visible) { ?>
		<td data-field="_19M"<?php echo $Page->_19M->CellAttributes() ?>>
<span<?php echo $Page->_19M->ViewAttributes() ?>><?php echo $Page->_19M->ListViewValue() ?></span></td>
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
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-header ewGridUpperPanel">
<?php include "viewestudiantesetareorptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_viewestudiantesetareo" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0) { ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "viewestudiantesetareorptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
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
