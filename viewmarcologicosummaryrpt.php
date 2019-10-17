<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewmarcologicosummaryrptinfo.php" ?>
<?php

//
// Page class
//

$viewmarcologicosummary_rpt = NULL; // Initialize page object first

class crviewmarcologicosummary_rpt extends crviewmarcologicosummary {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewmarcologicosummary_rpt';

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

		// Table object (viewmarcologicosummary)
		if (!isset($GLOBALS["viewmarcologicosummary"])) {
			$GLOBALS["viewmarcologicosummary"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewmarcologicosummary"];
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
			define("EWR_TABLE_NAME", 'viewmarcologicosummary', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewmarcologicosummaryrpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewmarcologicosummary');
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
		$this->fecha->PlaceHolder = $this->fecha->FldCaption();

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
			$ReportOptions["ShowFilter"] = TRUE;
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
		$filter = array();
		$keys = preg_grep('/^(sv|sv2|so|so2|sc|sel)_/', array_keys($ar));
		foreach ($keys as $key) {
			$filter[$key] = @$ar[$key];
		}
		return $this->SetupFilterList($filter); 
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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewmarcologicosummary\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewmarcologicosummary',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewmarcologicosummaryrpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewmarcologicosummaryrpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewmarcologicosummaryrpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->fecha->SelectionList = "";
		$this->fecha->DefaultSelectionList = "";
		$this->fecha->ValueList = "";

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
				$this->FirstRowData['fecha'] = ewr_Conv($rs->fields('fecha'), 133);
				$this->FirstRowData['cuadro1'] = ewr_Conv($rs->fields('cuadro1'), 131);
				$this->FirstRowData['cuadro2'] = ewr_Conv($rs->fields('cuadro2'), 131);
				$this->FirstRowData['cuadro3'] = ewr_Conv($rs->fields('cuadro3'), 131);
				$this->FirstRowData['cuadro4'] = ewr_Conv($rs->fields('cuadro4'), 131);
				$this->FirstRowData['cuadro5'] = ewr_Conv($rs->fields('cuadro5'), 131);
				$this->FirstRowData['cuadro6'] = ewr_Conv($rs->fields('cuadro6'), 131);
				$this->FirstRowData['cuadro7'] = ewr_Conv($rs->fields('cuadro7'), 131);
				$this->FirstRowData['cuadro8'] = ewr_Conv($rs->fields('cuadro8'), 131);
				$this->FirstRowData['cuadro9'] = ewr_Conv($rs->fields('cuadro9'), 131);
				$this->FirstRowData['cuadro10'] = ewr_Conv($rs->fields('cuadro10'), 131);
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

			if ($popupname == 'viewmarcologicosummary_nombreinstitucion') {
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

			// Build distinct values for fecha
			if ($popupname == 'viewmarcologicosummary_fecha') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->fecha->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->fecha->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->fecha->setDbValue($rswrk->fields[0]);
					$this->fecha->ViewValue = @$rswrk->fields[1];
					if (is_null($this->fecha->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->fecha->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->fecha->ValueList, $this->fecha->CurrentValue, $this->fecha->ViewValue, FALSE, $this->fecha->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->fecha->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->fecha->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->fecha;
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
				$this->ClearSessionSelection('fecha');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get nombreinstitucion selected values

		if (is_array(@$_SESSION["sel_viewmarcologicosummary_nombreinstitucion"])) {
			$this->LoadSelectionFromSession('nombreinstitucion');
		} elseif (@$_SESSION["sel_viewmarcologicosummary_nombreinstitucion"] == EWR_INIT_VALUE) { // Select all
			$this->nombreinstitucion->SelectionList = "";
		}

		// Get fecha selected values
		if (is_array(@$_SESSION["sel_viewmarcologicosummary_fecha"])) {
			$this->LoadSelectionFromSession('fecha');
		} elseif (@$_SESSION["sel_viewmarcologicosummary_fecha"] == EWR_INIT_VALUE) { // Select all
			$this->fecha->SelectionList = "";
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

			// Set/clear dropdown for field nombreinstitucion
			if ($this->PopupName == 'viewmarcologicosummary_nombreinstitucion' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->nombreinstitucion->DropDownValue = EWR_ALL_VALUE;
				else
					$this->nombreinstitucion->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewmarcologicosummary_nombreinstitucion') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'nombreinstitucion');
			}

			// Clear extended filter for field fecha
			if ($this->ClearExtFilter == 'viewmarcologicosummary_fecha')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'fecha');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->nombreinstitucion->DropDownValue, $this->nombreinstitucion->SearchOperator, 'nombreinstitucion'); // Field nombreinstitucion
			$this->SetSessionFilterValues($this->fecha->SearchValue, $this->fecha->SearchOperator, $this->fecha->SearchCondition, $this->fecha->SearchValue2, $this->fecha->SearchOperator2, 'fecha'); // Field fecha

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field nombreinstitucion
			if ($this->GetDropDownValue($this->nombreinstitucion)) {
				$bSetupFilter = TRUE;
			} elseif ($this->nombreinstitucion->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewmarcologicosummary_nombreinstitucion'])) {
				$bSetupFilter = TRUE;
			}

			// Field fecha
			if ($this->GetFilterValues($this->fecha)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->nombreinstitucion); // Field nombreinstitucion
			$this->GetSessionFilterValues($this->fecha); // Field fecha
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->nombreinstitucion, $sFilter, $this->nombreinstitucion->SearchOperator, FALSE, TRUE); // Field nombreinstitucion
		$this->BuildExtendedFilter($this->fecha, $sFilter, FALSE, TRUE); // Field fecha

		// Save parms to session
		$this->SetSessionDropDownValue($this->nombreinstitucion->DropDownValue, $this->nombreinstitucion->SearchOperator, 'nombreinstitucion'); // Field nombreinstitucion
		$this->SetSessionFilterValues($this->fecha->SearchValue, $this->fecha->SearchOperator, $this->fecha->SearchCondition, $this->fecha->SearchValue2, $this->fecha->SearchOperator2, 'fecha'); // Field fecha

		// Setup filter
		if ($bSetupFilter) {

			// Field nombreinstitucion
			$sWrk = "";
			$this->BuildDropDownFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SearchOperator);
			ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SelectionList, $this->nombreinstitucion->DropDownValue);
			$_SESSION['sel_viewmarcologicosummary_nombreinstitucion'] = ($this->nombreinstitucion->SelectionList == "") ? EWR_INIT_VALUE : $this->nombreinstitucion->SelectionList;

			// Field fecha
			$sWrk = "";
			$this->BuildExtendedFilter($this->fecha, $sWrk);
			ewr_LoadSelectionFromFilter($this->fecha, $sWrk, $this->fecha->SelectionList);
			$_SESSION['sel_viewmarcologicosummary_fecha'] = ($this->fecha->SelectionList == "") ? EWR_INIT_VALUE : $this->fecha->SelectionList;
		}

		// Field nombreinstitucion
		ewr_LoadDropDownList($this->nombreinstitucion->DropDownList, $this->nombreinstitucion->DropDownValue);
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewmarcologicosummary_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewmarcologicosummary_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewmarcologicosummary_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewmarcologicosummary_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewmarcologicosummary_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewmarcologicosummary_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewmarcologicosummary_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewmarcologicosummary_' . $parm] = $sv;
		$_SESSION['so_viewmarcologicosummary_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewmarcologicosummary_' . $parm] = $sv1;
		$_SESSION['so_viewmarcologicosummary_' . $parm] = $so1;
		$_SESSION['sc_viewmarcologicosummary_' . $parm] = $sc;
		$_SESSION['sv2_viewmarcologicosummary_' . $parm] = $sv2;
		$_SESSION['so2_viewmarcologicosummary_' . $parm] = $so2;
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
		if (!ewr_CheckDateDef($this->fecha->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fecha->FldErrMsg();
		}
		if (!ewr_CheckDateDef($this->fecha->SearchValue2)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fecha->FldErrMsg();
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
		$_SESSION["sel_viewmarcologicosummary_$parm"] = "";
		$_SESSION["rf_viewmarcologicosummary_$parm"] = "";
		$_SESSION["rt_viewmarcologicosummary_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewmarcologicosummary_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewmarcologicosummary_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewmarcologicosummary_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field nombreinstitucion
		$this->nombreinstitucion->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->nombreinstitucion->DropDownValue = $this->nombreinstitucion->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombreinstitucion->SelectionList = $this->nombreinstitucion->DefaultSelectionList;
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

		// Field fecha
		$this->SetDefaultExtFilter($this->fecha, "BETWEEN", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->fecha);
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->fecha, $sWrk, $this->fecha->DefaultSelectionList);
		if (!$this->SearchCommand) $this->fecha->SelectionList = $this->fecha->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field nombreinstitucion
		// $this->nombreinstitucion->DefaultSelectionList = array("val1", "val2");
		// Field fecha
		// $this->fecha->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check nombreinstitucion extended filter
		if ($this->NonTextFilterApplied($this->nombreinstitucion))
			return TRUE;

		// Check nombreinstitucion popup filter
		if (!ewr_MatchedArray($this->nombreinstitucion->DefaultSelectionList, $this->nombreinstitucion->SelectionList))
			return TRUE;

		// Check fecha text filter
		if ($this->TextFilterApplied($this->fecha))
			return TRUE;

		// Check fecha popup filter
		if (!ewr_MatchedArray($this->fecha->DefaultSelectionList, $this->fecha->SelectionList))
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
		$this->BuildDropDownFilter($this->nombreinstitucion, $sExtWrk, $this->nombreinstitucion->SearchOperator);
		if (is_array($this->nombreinstitucion->SelectionList))
			$sWrk = ewr_JoinArray($this->nombreinstitucion->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreinstitucion->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field fecha
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha, $sExtWrk);
		if (is_array($this->fecha->SelectionList))
			$sWrk = ewr_JoinArray($this->fecha->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fecha->FldCaption() . "</span>" . $sFilter . "</div>";
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
		$sWrk = ($this->nombreinstitucion->DropDownValue <> EWR_INIT_VALUE) ? $this->nombreinstitucion->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_nombreinstitucion\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field fecha
		$sWrk = "";
		if ($this->fecha->SearchValue <> "" || $this->fecha->SearchValue2 <> "") {
			$sWrk = "\"sv_fecha\":\"" . ewr_JsEncode2($this->fecha->SearchValue) . "\"," .
				"\"so_fecha\":\"" . ewr_JsEncode2($this->fecha->SearchOperator) . "\"," .
				"\"sc_fecha\":\"" . ewr_JsEncode2($this->fecha->SearchCondition) . "\"," .
				"\"sv2_fecha\":\"" . ewr_JsEncode2($this->fecha->SearchValue2) . "\"," .
				"\"so2_fecha\":\"" . ewr_JsEncode2($this->fecha->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->fecha->SelectionList <> EWR_INIT_VALUE) ? $this->fecha->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_fecha\":\"" . ewr_JsEncode2($sWrk) . "\"";
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
		if (array_key_exists("sv_nombreinstitucion", $filter)) {
			$sWrk = $filter["sv_nombreinstitucion"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_nombreinstitucion"], "nombreinstitucion");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombreinstitucion", $filter)) {
			$sWrk = $filter["sel_nombreinstitucion"];
			$sWrk = explode("||", $sWrk);
			$this->nombreinstitucion->SelectionList = $sWrk;
			$_SESSION["sel_viewmarcologicosummary_nombreinstitucion"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nombreinstitucion"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nombreinstitucion");
			$this->nombreinstitucion->SelectionList = "";
			$_SESSION["sel_viewmarcologicosummary_nombreinstitucion"] = "";
		}

		// Field fecha
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_fecha", $filter) || array_key_exists("so_fecha", $filter) ||
			array_key_exists("sc_fecha", $filter) ||
			array_key_exists("sv2_fecha", $filter) || array_key_exists("so2_fecha", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_fecha"], @$filter["so_fecha"], @$filter["sc_fecha"], @$filter["sv2_fecha"], @$filter["so2_fecha"], "fecha");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_fecha", $filter)) {
			$sWrk = $filter["sel_fecha"];
			$sWrk = explode("||", $sWrk);
			$this->fecha->SelectionList = $sWrk;
			$_SESSION["sel_viewmarcologicosummary_fecha"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha");
			$this->fecha->SelectionList = "";
			$_SESSION["sel_viewmarcologicosummary_fecha"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->DropDownFilterExist($this->nombreinstitucion, $this->nombreinstitucion->SearchOperator)) {
			if (is_array($this->nombreinstitucion->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombreinstitucion, "`nombreinstitucion`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreinstitucion, $sFilter, "popup");
				$this->nombreinstitucion->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->fecha)) {
			if (is_array($this->fecha->SelectionList)) {
				$sFilter = ewr_FilterSql($this->fecha, "`fecha`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha, $sFilter, "popup");
				$this->fecha->CurrentFilter = $sFilter;
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

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->nombreinstitucion); // nombreinstitucion
			$this->UpdateSort($this->fecha); // fecha
			$this->UpdateSort($this->cuadro1); // cuadro1
			$this->UpdateSort($this->cuadro2); // cuadro2
			$this->UpdateSort($this->cuadro3); // cuadro3
			$this->UpdateSort($this->cuadro4); // cuadro4
			$this->UpdateSort($this->cuadro5); // cuadro5
			$this->UpdateSort($this->cuadro6); // cuadro6
			$this->UpdateSort($this->cuadro7); // cuadro7
			$this->UpdateSort($this->cuadro8); // cuadro8
			$this->UpdateSort($this->cuadro9); // cuadro9
			$this->UpdateSort($this->cuadro10); // cuadro10
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export email
	function ExportEmail($EmailContent, $options = array()) {
		global $grTmpImages, $ReportLanguage;
		$bGenRequest = @$options["reporttype"] == "email";
		$sFailRespPfx = $bGenRequest ? "" : "<p class=\"text-error\">";
		$sSuccessRespPfx = $bGenRequest ? "" : "<p class=\"text-success\">";
		$sRespPfx = $bGenRequest ? "" : "</p>";
		$sContentType = (@$options["contenttype"] <> "") ? $options["contenttype"] : @$_POST["contenttype"];
		$sSender = (@$options["sender"] <> "") ? $options["sender"] : @$_POST["sender"];
		$sRecipient = (@$options["recipient"] <> "") ? $options["recipient"] : @$_POST["recipient"];
		$sCc = (@$options["cc"] <> "") ? $options["cc"] : @$_POST["cc"];
		$sBcc = (@$options["bcc"] <> "") ? $options["bcc"] : @$_POST["bcc"];

		// Subject
		$sEmailSubject = (@$options["subject"] <> "") ? $options["subject"] : @$_POST["subject"];

		// Message
		$sEmailMessage = (@$options["message"] <> "") ? $options["message"] : @$_POST["message"];

		// Check sender
		if ($sSender == "")
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterSenderEmail") . $sRespPfx;
		if (!ewr_CheckEmail($sSender))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperSenderEmail") . $sRespPfx;

		// Check recipient
		if (!ewr_CheckEmailList($sRecipient, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperRecipientEmail") . $sRespPfx;

		// Check cc
		if (!ewr_CheckEmailList($sCc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperCcEmail") . $sRespPfx;

		// Check bcc
		if (!ewr_CheckEmailList($sBcc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperBccEmail") . $sRespPfx;

		// Check email sent count
		$emailcount = $bGenRequest ? 0 : ewr_LoadEmailCount();
		if (intval($emailcount) >= EWR_MAX_EMAIL_SENT_COUNT)
			return $sFailRespPfx . $ReportLanguage->Phrase("ExceedMaxEmailExport") . $sRespPfx;
		if ($sEmailMessage <> "") {
			if (EWR_REMOVE_XSS) $sEmailMessage = ewr_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		$sAttachmentContent = ewr_AdjustEmailContent($EmailContent);
		$sAppPath = ewr_FullUrl();
		$sAppPath = substr($sAppPath, 0, strrpos($sAppPath, "/")+1);
		if (strpos($sAttachmentContent, "<head>") !== FALSE)
			$sAttachmentContent = str_replace("<head>", "<head><base href=\"" . $sAppPath . "\">", $sAttachmentContent); // Add <base href> statement inside the header
		else
			$sAttachmentContent = "<base href=\"" . $sAppPath . "\">" . $sAttachmentContent; // Add <base href> statement as the first statement

		//$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . ".html";
		$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . "_" . ewr_Random() . ".html";
		if ($sContentType == "url") {
			ewr_SaveFile(EWR_UPLOAD_DEST_PATH, $sAttachmentFile, $sAttachmentContent);
			$sAttachmentFile = EWR_UPLOAD_DEST_PATH . $sAttachmentFile;
			$sUrl = $sAppPath . $sAttachmentFile;
			$sEmailMessage .= $sUrl; // Send URL only
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		} else {
			$sEmailMessage .= $sAttachmentContent;
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		}

		// Send email
		$Email = new crEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Content = $sEmailMessage; // Content
		if ($sAttachmentFile <> "")
			$Email->AddAttachment($sAttachmentFile, $sAttachmentContent);
		if ($sContentType <> "url") {
			foreach ($grTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
		}
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EWR_EMAIL_CHARSET;
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();
		ewr_DeleteTmpImages($EmailContent);

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count and write log
			ewr_AddEmailLog($sSender, $sRecipient, $sEmailSubject, $sEmailMessage);

			// Sent email success
			return $sSuccessRespPfx . $ReportLanguage->Phrase("SendEmailSuccess") . $sRespPfx; // Set up success message
		} else {

			// Sent email failure
			return $sFailRespPfx . $Email->SendErrDescription . $sRespPfx;
		}
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
if (!isset($viewmarcologicosummary_rpt)) $viewmarcologicosummary_rpt = new crviewmarcologicosummary_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewmarcologicosummary_rpt;

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
var viewmarcologicosummary_rpt = new ewr_Page("viewmarcologicosummary_rpt");

// Page properties
viewmarcologicosummary_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewmarcologicosummary_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewmarcologicosummaryrpt = new ewr_Form("fviewmarcologicosummaryrpt");

// Validate method
fviewmarcologicosummaryrpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_fecha;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fecha->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_fecha;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fecha->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewmarcologicosummaryrpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewmarcologicosummaryrpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewmarcologicosummaryrpt.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fviewmarcologicosummaryrpt.Lists["sv_nombreinstitucion"] = {"LinkField":"sv_nombreinstitucion","Ajax":true,"DisplayFields":["sv_nombreinstitucion","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewmarcologicosummaryrpt.AutoSuggests["sv_nombreinstitucion"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $viewmarcologicosummary_rpt->nombreinstitucion->LookupFilterQuery(TRUE))) ?>;
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
<form name="fviewmarcologicosummaryrpt" id="fviewmarcologicosummaryrpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewmarcologicosummaryrpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_nombreinstitucion" class="ewCell form-group">
	<label for="sv_nombreinstitucion" class="ewSearchCaption ewLabel"><?php echo $Page->nombreinstitucion->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->nombreinstitucion->EditAttrs["class"], "form-control"); ?>
<select data-table="viewmarcologicosummary" data-field="x_nombreinstitucion" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->nombreinstitucion->DisplayValueSeparator) ? json_encode($Page->nombreinstitucion->DisplayValueSeparator) : $Page->nombreinstitucion->DisplayValueSeparator) ?>" id="sv_nombreinstitucion" name="sv_nombreinstitucion"<?php echo $Page->nombreinstitucion->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->nombreinstitucion->AdvancedFilters) ? count($Page->nombreinstitucion->AdvancedFilters) : 0;
	$cntd = is_array($Page->nombreinstitucion->DropDownList) ? count($Page->nombreinstitucion->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->nombreinstitucion->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->nombreinstitucion->DropDownValue, $filter->ID) ? " selected" : "";
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
<option value="<?php echo $Page->nombreinstitucion->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->nombreinstitucion->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_nombreinstitucion" id="s_sv_nombreinstitucion" value="<?php echo $Page->nombreinstitucion->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewmarcologicosummaryrpt.Lists["sv_nombreinstitucion"].Options = <?php echo ewr_ArrayToJson($Page->nombreinstitucion->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_fecha" class="ewCell form-group">
	<label for="sv_fecha" class="ewSearchCaption ewLabel"><?php echo $Page->fecha->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so_fecha" id="so_fecha" value="BETWEEN"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fecha->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewmarcologicosummary" data-field="x_fecha" id="sv_fecha" name="sv_fecha" placeholder="<?php echo $Page->fecha->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha->SearchValue) ?>" data-calendar='true' data-options='{"ignoreReadonly":true,"useCurrent":false,"format":0}'<?php echo $Page->fecha->EditAttributes() ?>>
</span>
	<span class="ewSearchCond btw1_fecha"><?php echo $ReportLanguage->Phrase("AND") ?></span>
	<span class="ewSearchField btw1_fecha">
<?php ewr_PrependClass($Page->fecha->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewmarcologicosummary" data-field="x_fecha" id="sv2_fecha" name="sv2_fecha" placeholder="<?php echo $Page->fecha->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha->SearchValue2) ?>" data-calendar='true' data-options='{"ignoreReadonly":true,"useCurrent":false,"format":0}'<?php echo $Page->fecha->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewmarcologicosummaryrpt.Init();
fviewmarcologicosummaryrpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<div id="gmp_viewmarcologicosummary" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->nombreinstitucion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="viewmarcologicosummary_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewmarcologicosummary_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewmarcologicosummaryrpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewmarcologicosummary_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewmarcologicosummaryrpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha"><div class="viewmarcologicosummary_fecha"><span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha">
<?php if ($Page->SortUrl($Page->fecha) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_fecha">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewmarcologicosummary_fecha', range: false, from: '<?php echo $Page->fecha->RangeFrom; ?>', to: '<?php echo $Page->fecha->RangeTo; ?>', url: 'viewmarcologicosummaryrpt.php' });" id="x_fecha<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_fecha" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewmarcologicosummary_fecha', range: false, from: '<?php echo $Page->fecha->RangeFrom; ?>', to: '<?php echo $Page->fecha->RangeTo; ?>', url: 'viewmarcologicosummaryrpt.php' });" id="x_fecha<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro1->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro1"><div class="viewmarcologicosummary_cuadro1"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro1->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro1">
<?php if ($Page->SortUrl($Page->cuadro1) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro1">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro1->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro1" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro1) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro1->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro2->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro2"><div class="viewmarcologicosummary_cuadro2"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro2->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro2">
<?php if ($Page->SortUrl($Page->cuadro2) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro2">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro2->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro2" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro2) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro2->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro3->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro3"><div class="viewmarcologicosummary_cuadro3"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro3->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro3">
<?php if ($Page->SortUrl($Page->cuadro3) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro3">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro3->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro3" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro3) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro3->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro4->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro4"><div class="viewmarcologicosummary_cuadro4"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro4->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro4">
<?php if ($Page->SortUrl($Page->cuadro4) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro4">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro4->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro4" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro4) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro4->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro5->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro5"><div class="viewmarcologicosummary_cuadro5"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro5->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro5">
<?php if ($Page->SortUrl($Page->cuadro5) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro5">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro5->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro5" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro5) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro5->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro6->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro6"><div class="viewmarcologicosummary_cuadro6"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro6->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro6">
<?php if ($Page->SortUrl($Page->cuadro6) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro6">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro6->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro6" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro6) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro6->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro6->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro6->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro7->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro7"><div class="viewmarcologicosummary_cuadro7"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro7->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro7">
<?php if ($Page->SortUrl($Page->cuadro7) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro7">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro7->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro7" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro7) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro7->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro7->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro7->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro8->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro8"><div class="viewmarcologicosummary_cuadro8"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro8->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro8">
<?php if ($Page->SortUrl($Page->cuadro8) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro8">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro8->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro8" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro8) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro8->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro8->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro8->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro9->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro9"><div class="viewmarcologicosummary_cuadro9"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro9->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro9">
<?php if ($Page->SortUrl($Page->cuadro9) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro9">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro9->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro9" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro9) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro9->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro9->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro9->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cuadro10->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cuadro10"><div class="viewmarcologicosummary_cuadro10"><span class="ewTableHeaderCaption"><?php echo $Page->cuadro10->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cuadro10">
<?php if ($Page->SortUrl($Page->cuadro10) == "") { ?>
		<div class="ewTableHeaderBtn viewmarcologicosummary_cuadro10">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro10->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewmarcologicosummary_cuadro10" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cuadro10) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cuadro10->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cuadro10->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cuadro10->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
<div id="gmp_viewmarcologicosummary" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
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
<?php include "viewmarcologicosummaryrptpager.php" ?>
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
