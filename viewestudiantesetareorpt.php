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
		$this->unidadeducativa->PlaceHolder = $this->unidadeducativa->FldCaption();
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
		$this->unidadeducativa->SetVisibility();
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
		$this->fecha->SetVisibility();

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
				$this->FirstRowData['unidadeducativa'] = ewr_Conv($rs->fields('unidadeducativa'), 200);
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
				$this->FirstRowData['fecha'] = ewr_Conv($rs->fields('fecha'), 133);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->unidadeducativa->setDbValue($rs->fields('unidadeducativa'));
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
			$this->fecha->setDbValue($rs->fields('fecha'));
			$this->Val[1] = $this->unidadeducativa->CurrentValue;
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
			$this->Val[16] = $this->fecha->CurrentValue;
		} else {
			$this->unidadeducativa->setDbValue("");
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
			$this->fecha->setDbValue("");
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

			// unidadeducativa
			$this->unidadeducativa->HrefValue = "";

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

			// fecha
			$this->fecha->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// unidadeducativa
			$this->unidadeducativa->ViewValue = $this->unidadeducativa->CurrentValue;
			$this->unidadeducativa->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

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

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ewr_FormatDateTime($this->fecha->ViewValue, 0);
			$this->fecha->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// unidadeducativa
			$this->unidadeducativa->HrefValue = "";

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

			// fecha
			$this->fecha->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// unidadeducativa
			$CurrentValue = $this->unidadeducativa->CurrentValue;
			$ViewValue = &$this->unidadeducativa->ViewValue;
			$ViewAttrs = &$this->unidadeducativa->ViewAttrs;
			$CellAttrs = &$this->unidadeducativa->CellAttrs;
			$HrefValue = &$this->unidadeducativa->HrefValue;
			$LinkAttrs = &$this->unidadeducativa->LinkAttrs;
			$this->Cell_Rendered($this->unidadeducativa, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// fecha
			$CurrentValue = $this->fecha->CurrentValue;
			$ViewValue = &$this->fecha->ViewValue;
			$ViewAttrs = &$this->fecha->ViewAttrs;
			$CellAttrs = &$this->fecha->CellAttrs;
			$HrefValue = &$this->fecha->HrefValue;
			$LinkAttrs = &$this->fecha->LinkAttrs;
			$this->Cell_Rendered($this->fecha, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->unidadeducativa->Visible) $this->DtlColumnCount += 1;
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
		if ($this->fecha->Visible) $this->DtlColumnCount += 1;
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

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionFilterValues($this->unidadeducativa->SearchValue, $this->unidadeducativa->SearchOperator, $this->unidadeducativa->SearchCondition, $this->unidadeducativa->SearchValue2, $this->unidadeducativa->SearchOperator2, 'unidadeducativa'); // Field unidadeducativa
			$this->SetSessionFilterValues($this->fecha->SearchValue, $this->fecha->SearchOperator, $this->fecha->SearchCondition, $this->fecha->SearchValue2, $this->fecha->SearchOperator2, 'fecha'); // Field fecha

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field unidadeducativa
			if ($this->GetFilterValues($this->unidadeducativa)) {
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
			$this->GetSessionFilterValues($this->unidadeducativa); // Field unidadeducativa
			$this->GetSessionFilterValues($this->fecha); // Field fecha
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildExtendedFilter($this->unidadeducativa, $sFilter, FALSE, TRUE); // Field unidadeducativa
		$this->BuildExtendedFilter($this->fecha, $sFilter, FALSE, TRUE); // Field fecha

		// Save parms to session
		$this->SetSessionFilterValues($this->unidadeducativa->SearchValue, $this->unidadeducativa->SearchOperator, $this->unidadeducativa->SearchCondition, $this->unidadeducativa->SearchValue2, $this->unidadeducativa->SearchOperator2, 'unidadeducativa'); // Field unidadeducativa
		$this->SetSessionFilterValues($this->fecha->SearchValue, $this->fecha->SearchOperator, $this->fecha->SearchCondition, $this->fecha->SearchValue2, $this->fecha->SearchOperator2, 'fecha'); // Field fecha

		// Setup filter
		if ($bSetupFilter) {
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewestudiantesetareo_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewestudiantesetareo_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewestudiantesetareo_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewestudiantesetareo_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewestudiantesetareo_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewestudiantesetareo_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewestudiantesetareo_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewestudiantesetareo_' . $parm] = $sv;
		$_SESSION['so_viewestudiantesetareo_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewestudiantesetareo_' . $parm] = $sv1;
		$_SESSION['so_viewestudiantesetareo_' . $parm] = $so1;
		$_SESSION['sc_viewestudiantesetareo_' . $parm] = $sc;
		$_SESSION['sv2_viewestudiantesetareo_' . $parm] = $sv2;
		$_SESSION['so2_viewestudiantesetareo_' . $parm] = $so2;
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
		$_SESSION["sel_viewestudiantesetareo_$parm"] = "";
		$_SESSION["rf_viewestudiantesetareo_$parm"] = "";
		$_SESSION["rt_viewestudiantesetareo_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewestudiantesetareo_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewestudiantesetareo_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewestudiantesetareo_$parm"];
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

		// Field unidadeducativa
		$this->SetDefaultExtFilter($this->unidadeducativa, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->unidadeducativa);

		// Field fecha
		$this->SetDefaultExtFilter($this->fecha, "BETWEEN", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->fecha);
		/**
		* Set up default values for popup filters
		*/
	}

	// Check if filter applied
	function CheckFilter() {

		// Check unidadeducativa text filter
		if ($this->TextFilterApplied($this->unidadeducativa))
			return TRUE;

		// Check fecha text filter
		if ($this->TextFilterApplied($this->fecha))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field unidadeducativa
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->unidadeducativa, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->unidadeducativa->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field fecha
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha, $sExtWrk);
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

		// Field unidadeducativa
		$sWrk = "";
		if ($this->unidadeducativa->SearchValue <> "" || $this->unidadeducativa->SearchValue2 <> "") {
			$sWrk = "\"sv_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchValue) . "\"," .
				"\"so_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchOperator) . "\"," .
				"\"sc_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchCondition) . "\"," .
				"\"sv2_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchValue2) . "\"," .
				"\"so2_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchOperator2) . "\"";
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

		// Field unidadeducativa
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_unidadeducativa", $filter) || array_key_exists("so_unidadeducativa", $filter) ||
			array_key_exists("sc_unidadeducativa", $filter) ||
			array_key_exists("sv2_unidadeducativa", $filter) || array_key_exists("so2_unidadeducativa", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_unidadeducativa"], @$filter["so_unidadeducativa"], @$filter["sc_unidadeducativa"], @$filter["sv2_unidadeducativa"], @$filter["so2_unidadeducativa"], "unidadeducativa");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "unidadeducativa");
		}

		// Field fecha
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_fecha", $filter) || array_key_exists("so_fecha", $filter) ||
			array_key_exists("sc_fecha", $filter) ||
			array_key_exists("sv2_fecha", $filter) || array_key_exists("so2_fecha", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_fecha"], @$filter["so_fecha"], @$filter["sc_fecha"], @$filter["sv2_fecha"], @$filter["so2_fecha"], "fecha");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha");
		}
		return TRUE;
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
			$this->unidadeducativa->setSort("");
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
			$this->fecha->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->unidadeducativa); // unidadeducativa
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
			$this->UpdateSort($this->fecha); // fecha
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
<script type="text/javascript">

// Form object
var CurrentForm = fviewestudiantesetareorpt = new ewr_Form("fviewestudiantesetareorpt");

// Validate method
fviewestudiantesetareorpt.Validate = function() {
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
fviewestudiantesetareorpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewestudiantesetareorpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewestudiantesetareorpt.ValidateRequired = false; // No JavaScript validation
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
<form name="fviewestudiantesetareorpt" id="fviewestudiantesetareorpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewestudiantesetareorpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_unidadeducativa" class="ewCell form-group">
	<label for="sv_unidadeducativa" class="ewSearchCaption ewLabel"><?php echo $Page->unidadeducativa->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_unidadeducativa" id="so_unidadeducativa" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->unidadeducativa->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantesetareo" data-field="x_unidadeducativa" id="sv_unidadeducativa" name="sv_unidadeducativa" size="30" maxlength="100" placeholder="<?php echo $Page->unidadeducativa->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->unidadeducativa->SearchValue) ?>"<?php echo $Page->unidadeducativa->EditAttributes() ?>>
</span>
</div>
<div id="c_fecha" class="ewCell form-group">
	<label for="sv_fecha" class="ewSearchCaption ewLabel"><?php echo $Page->fecha->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so_fecha" id="so_fecha" value="BETWEEN"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fecha->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantesetareo" data-field="x_fecha" id="sv_fecha" name="sv_fecha" placeholder="<?php echo $Page->fecha->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha->SearchValue) ?>"<?php echo $Page->fecha->EditAttributes() ?>>
</span>
	<span class="ewSearchCond btw1_fecha"><?php echo $ReportLanguage->Phrase("AND") ?></span>
	<span class="ewSearchField btw1_fecha">
<?php ewr_PrependClass($Page->fecha->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantesetareo" data-field="x_fecha" id="sv2_fecha" name="sv2_fecha" placeholder="<?php echo $Page->fecha->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha->SearchValue2) ?>"<?php echo $Page->fecha->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewestudiantesetareorpt.Init();
fviewestudiantesetareorpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<?php if ($Page->unidadeducativa->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="unidadeducativa"><div class="viewestudiantesetareo_unidadeducativa"><span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="unidadeducativa">
<?php if ($Page->SortUrl($Page->unidadeducativa) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo_unidadeducativa">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo_unidadeducativa" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->unidadeducativa) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->unidadeducativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->unidadeducativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
<?php if ($Page->fecha->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha"><div class="viewestudiantesetareo_fecha"><span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha">
<?php if ($Page->SortUrl($Page->fecha) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareo_fecha">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareo_fecha" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
<?php if ($Page->unidadeducativa->Visible) { ?>
		<td data-field="unidadeducativa"<?php echo $Page->unidadeducativa->CellAttributes() ?>>
<span<?php echo $Page->unidadeducativa->ViewAttributes() ?>><?php echo $Page->unidadeducativa->ListViewValue() ?></span></td>
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
<?php if ($Page->fecha->Visible) { ?>
		<td data-field="fecha"<?php echo $Page->fecha->CellAttributes() ?>>
<span<?php echo $Page->fecha->ViewAttributes() ?>><?php echo $Page->fecha->ListViewValue() ?></span></td>
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
