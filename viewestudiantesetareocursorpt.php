<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewestudiantesetareocursorptinfo.php" ?>
<?php

//
// Page class
//

$viewestudiantesetareocurso_rpt = NULL; // Initialize page object first

class crviewestudiantesetareocurso_rpt extends crviewestudiantesetareocurso {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewestudiantesetareocurso_rpt';

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

		// Table object (viewestudiantesetareocurso)
		if (!isset($GLOBALS["viewestudiantesetareocurso"])) {
			$GLOBALS["viewestudiantesetareocurso"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewestudiantesetareocurso"];
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
			define("EWR_TABLE_NAME", 'viewestudiantesetareocurso', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewestudiantesetareocursorpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewestudiantesetareocurso');
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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewestudiantesetareocurso\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewestudiantesetareocurso',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewestudiantesetareocursorpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewestudiantesetareocursorpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewestudiantesetareocursorpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->curso->SetVisibility();
		$this->id->SetVisibility();
		$this->nombres->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->codigorude_es->SetVisibility();
		$this->ci->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapacidad->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->fecha->SetVisibility();

		// Handle drill down
		$sDrillDownFilter = $this->GetDrillDownFilter();
		$grDrillDownInPanel = $this->DrillDownInPanel;
		if ($this->DrillDown)
			ewr_AddFilter($this->Filter, $sDrillDownFilter);

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 12;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->curso->SelectionList = "";
		$this->curso->DefaultSelectionList = "";
		$this->curso->ValueList = "";
		$this->discapacidad->SelectionList = "";
		$this->discapacidad->DefaultSelectionList = "";
		$this->discapacidad->ValueList = "";
		$this->tipodiscapacidad->SelectionList = "";
		$this->tipodiscapacidad->DefaultSelectionList = "";
		$this->tipodiscapacidad->ValueList = "";
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
				$this->FirstRowData['curso'] = ewr_Conv($rs->fields('curso'), 200);
				$this->FirstRowData['id'] = ewr_Conv($rs->fields('id'), 3);
				$this->FirstRowData['nombres'] = ewr_Conv($rs->fields('nombres'), 200);
				$this->FirstRowData['apellidopaterno'] = ewr_Conv($rs->fields('apellidopaterno'), 200);
				$this->FirstRowData['apellidomaterno'] = ewr_Conv($rs->fields('apellidomaterno'), 200);
				$this->FirstRowData['codigorude_es'] = ewr_Conv($rs->fields('codigorude_es'), 200);
				$this->FirstRowData['ci'] = ewr_Conv($rs->fields('ci'), 200);
				$this->FirstRowData['discapacidad'] = ewr_Conv($rs->fields('discapacidad'), 200);
				$this->FirstRowData['tipodiscapacidad'] = ewr_Conv($rs->fields('tipodiscapacidad'), 200);
				$this->FirstRowData['unidadeducativa'] = ewr_Conv($rs->fields('unidadeducativa'), 200);
				$this->FirstRowData['fecha'] = ewr_Conv($rs->fields('fecha'), 133);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->curso->setDbValue($rs->fields('curso'));
			$this->id->setDbValue($rs->fields('id'));
			$this->nombres->setDbValue($rs->fields('nombres'));
			$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
			$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
			$this->codigorude_es->setDbValue($rs->fields('codigorude_es'));
			$this->ci->setDbValue($rs->fields('ci'));
			$this->discapacidad->setDbValue($rs->fields('discapacidad'));
			$this->tipodiscapacidad->setDbValue($rs->fields('tipodiscapacidad'));
			$this->unidadeducativa->setDbValue($rs->fields('unidadeducativa'));
			$this->fecha->setDbValue($rs->fields('fecha'));
			$this->Val[1] = $this->curso->CurrentValue;
			$this->Val[2] = $this->id->CurrentValue;
			$this->Val[3] = $this->nombres->CurrentValue;
			$this->Val[4] = $this->apellidopaterno->CurrentValue;
			$this->Val[5] = $this->apellidomaterno->CurrentValue;
			$this->Val[6] = $this->codigorude_es->CurrentValue;
			$this->Val[7] = $this->ci->CurrentValue;
			$this->Val[8] = $this->discapacidad->CurrentValue;
			$this->Val[9] = $this->tipodiscapacidad->CurrentValue;
			$this->Val[10] = $this->unidadeducativa->CurrentValue;
			$this->Val[11] = $this->fecha->CurrentValue;
		} else {
			$this->curso->setDbValue("");
			$this->id->setDbValue("");
			$this->nombres->setDbValue("");
			$this->apellidopaterno->setDbValue("");
			$this->apellidomaterno->setDbValue("");
			$this->codigorude_es->setDbValue("");
			$this->ci->setDbValue("");
			$this->discapacidad->setDbValue("");
			$this->tipodiscapacidad->setDbValue("");
			$this->unidadeducativa->setDbValue("");
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
			// Build distinct values for curso

			if ($popupname == 'viewestudiantesetareocurso_curso') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->curso, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->curso->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->curso->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->curso->setDbValue($rswrk->fields[0]);
					$this->curso->ViewValue = @$rswrk->fields[1];
					if (is_null($this->curso->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->curso->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->curso->ValueList, $this->curso->CurrentValue, $this->curso->ViewValue, FALSE, $this->curso->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->curso->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->curso->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->curso;
			}

			// Build distinct values for discapacidad
			if ($popupname == 'viewestudiantesetareocurso_discapacidad') {
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
			if ($popupname == 'viewestudiantesetareocurso_tipodiscapacidad') {
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

			// Build distinct values for fecha
			if ($popupname == 'viewestudiantesetareocurso_fecha') {
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
				$this->ClearSessionSelection('curso');
				$this->ClearSessionSelection('discapacidad');
				$this->ClearSessionSelection('tipodiscapacidad');
				$this->ClearSessionSelection('fecha');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get curso selected values

		if (is_array(@$_SESSION["sel_viewestudiantesetareocurso_curso"])) {
			$this->LoadSelectionFromSession('curso');
		} elseif (@$_SESSION["sel_viewestudiantesetareocurso_curso"] == EWR_INIT_VALUE) { // Select all
			$this->curso->SelectionList = "";
		}

		// Get discapacidad selected values
		if (is_array(@$_SESSION["sel_viewestudiantesetareocurso_discapacidad"])) {
			$this->LoadSelectionFromSession('discapacidad');
		} elseif (@$_SESSION["sel_viewestudiantesetareocurso_discapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->discapacidad->SelectionList = "";
		}

		// Get tipodiscapacidad selected values
		if (is_array(@$_SESSION["sel_viewestudiantesetareocurso_tipodiscapacidad"])) {
			$this->LoadSelectionFromSession('tipodiscapacidad');
		} elseif (@$_SESSION["sel_viewestudiantesetareocurso_tipodiscapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->tipodiscapacidad->SelectionList = "";
		}

		// Get fecha selected values
		if (is_array(@$_SESSION["sel_viewestudiantesetareocurso_fecha"])) {
			$this->LoadSelectionFromSession('fecha');
		} elseif (@$_SESSION["sel_viewestudiantesetareocurso_fecha"] == EWR_INIT_VALUE) { // Select all
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

			// curso
			$this->curso->HrefValue = "";

			// id
			$this->id->HrefValue = "";

			// nombres
			$this->nombres->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// codigorude_es
			$this->codigorude_es->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->HrefValue = "";

			// unidadeducativa
			$this->unidadeducativa->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// curso
			$this->curso->ViewValue = $this->curso->CurrentValue;
			$this->curso->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombres
			$this->nombres->ViewValue = $this->nombres->CurrentValue;
			$this->nombres->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// apellidopaterno
			$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
			$this->apellidopaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// apellidomaterno
			$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
			$this->apellidomaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// codigorude_es
			$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
			$this->codigorude_es->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ci
			$this->ci->ViewValue = $this->ci->CurrentValue;
			$this->ci->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// discapacidad
			$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
			$this->discapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipodiscapacidad
			$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->CurrentValue;
			$this->tipodiscapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// unidadeducativa
			$this->unidadeducativa->ViewValue = $this->unidadeducativa->CurrentValue;
			$this->unidadeducativa->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ewr_FormatDateTime($this->fecha->ViewValue, 0);
			$this->fecha->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// curso
			$this->curso->HrefValue = "";

			// id
			$this->id->HrefValue = "";

			// nombres
			$this->nombres->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// codigorude_es
			$this->codigorude_es->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->HrefValue = "";

			// unidadeducativa
			$this->unidadeducativa->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// curso
			$CurrentValue = $this->curso->CurrentValue;
			$ViewValue = &$this->curso->ViewValue;
			$ViewAttrs = &$this->curso->ViewAttrs;
			$CellAttrs = &$this->curso->CellAttrs;
			$HrefValue = &$this->curso->HrefValue;
			$LinkAttrs = &$this->curso->LinkAttrs;
			$this->Cell_Rendered($this->curso, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id
			$CurrentValue = $this->id->CurrentValue;
			$ViewValue = &$this->id->ViewValue;
			$ViewAttrs = &$this->id->ViewAttrs;
			$CellAttrs = &$this->id->CellAttrs;
			$HrefValue = &$this->id->HrefValue;
			$LinkAttrs = &$this->id->LinkAttrs;
			$this->Cell_Rendered($this->id, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombres
			$CurrentValue = $this->nombres->CurrentValue;
			$ViewValue = &$this->nombres->ViewValue;
			$ViewAttrs = &$this->nombres->ViewAttrs;
			$CellAttrs = &$this->nombres->CellAttrs;
			$HrefValue = &$this->nombres->HrefValue;
			$LinkAttrs = &$this->nombres->LinkAttrs;
			$this->Cell_Rendered($this->nombres, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// codigorude_es
			$CurrentValue = $this->codigorude_es->CurrentValue;
			$ViewValue = &$this->codigorude_es->ViewValue;
			$ViewAttrs = &$this->codigorude_es->ViewAttrs;
			$CellAttrs = &$this->codigorude_es->CellAttrs;
			$HrefValue = &$this->codigorude_es->HrefValue;
			$LinkAttrs = &$this->codigorude_es->LinkAttrs;
			$this->Cell_Rendered($this->codigorude_es, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// ci
			$CurrentValue = $this->ci->CurrentValue;
			$ViewValue = &$this->ci->ViewValue;
			$ViewAttrs = &$this->ci->ViewAttrs;
			$CellAttrs = &$this->ci->CellAttrs;
			$HrefValue = &$this->ci->HrefValue;
			$LinkAttrs = &$this->ci->LinkAttrs;
			$this->Cell_Rendered($this->ci, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// unidadeducativa
			$CurrentValue = $this->unidadeducativa->CurrentValue;
			$ViewValue = &$this->unidadeducativa->ViewValue;
			$ViewAttrs = &$this->unidadeducativa->ViewAttrs;
			$CellAttrs = &$this->unidadeducativa->CellAttrs;
			$HrefValue = &$this->unidadeducativa->HrefValue;
			$LinkAttrs = &$this->unidadeducativa->LinkAttrs;
			$this->Cell_Rendered($this->unidadeducativa, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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
		if ($this->curso->Visible) $this->DtlColumnCount += 1;
		if ($this->id->Visible) $this->DtlColumnCount += 1;
		if ($this->nombres->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidopaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidomaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->codigorude_es->Visible) $this->DtlColumnCount += 1;
		if ($this->ci->Visible) $this->DtlColumnCount += 1;
		if ($this->discapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->tipodiscapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->unidadeducativa->Visible) $this->DtlColumnCount += 1;
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

			// Set/clear dropdown for field curso
			if ($this->PopupName == 'viewestudiantesetareocurso_curso' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->curso->DropDownValue = EWR_ALL_VALUE;
				else
					$this->curso->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewestudiantesetareocurso_curso') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'curso');
			}

			// Set/clear dropdown for field discapacidad
			if ($this->PopupName == 'viewestudiantesetareocurso_discapacidad' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->discapacidad->DropDownValue = EWR_ALL_VALUE;
				else
					$this->discapacidad->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewestudiantesetareocurso_discapacidad') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'discapacidad');
			}

			// Set/clear dropdown for field tipodiscapacidad
			if ($this->PopupName == 'viewestudiantesetareocurso_tipodiscapacidad' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->tipodiscapacidad->DropDownValue = EWR_ALL_VALUE;
				else
					$this->tipodiscapacidad->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewestudiantesetareocurso_tipodiscapacidad') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'tipodiscapacidad');
			}

			// Clear extended filter for field fecha
			if ($this->ClearExtFilter == 'viewestudiantesetareocurso_fecha')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'fecha');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->curso->DropDownValue, $this->curso->SearchOperator, 'curso'); // Field curso
			$this->SetSessionDropDownValue($this->discapacidad->DropDownValue, $this->discapacidad->SearchOperator, 'discapacidad'); // Field discapacidad
			$this->SetSessionDropDownValue($this->tipodiscapacidad->DropDownValue, $this->tipodiscapacidad->SearchOperator, 'tipodiscapacidad'); // Field tipodiscapacidad
			$this->SetSessionFilterValues($this->fecha->SearchValue, $this->fecha->SearchOperator, $this->fecha->SearchCondition, $this->fecha->SearchValue2, $this->fecha->SearchOperator2, 'fecha'); // Field fecha

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field curso
			if ($this->GetDropDownValue($this->curso)) {
				$bSetupFilter = TRUE;
			} elseif ($this->curso->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewestudiantesetareocurso_curso'])) {
				$bSetupFilter = TRUE;
			}

			// Field discapacidad
			if ($this->GetDropDownValue($this->discapacidad)) {
				$bSetupFilter = TRUE;
			} elseif ($this->discapacidad->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewestudiantesetareocurso_discapacidad'])) {
				$bSetupFilter = TRUE;
			}

			// Field tipodiscapacidad
			if ($this->GetDropDownValue($this->tipodiscapacidad)) {
				$bSetupFilter = TRUE;
			} elseif ($this->tipodiscapacidad->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewestudiantesetareocurso_tipodiscapacidad'])) {
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
			$this->GetSessionDropDownValue($this->curso); // Field curso
			$this->GetSessionDropDownValue($this->discapacidad); // Field discapacidad
			$this->GetSessionDropDownValue($this->tipodiscapacidad); // Field tipodiscapacidad
			$this->GetSessionFilterValues($this->fecha); // Field fecha
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->curso, $sFilter, $this->curso->SearchOperator, FALSE, TRUE); // Field curso
		$this->BuildDropDownFilter($this->discapacidad, $sFilter, $this->discapacidad->SearchOperator, FALSE, TRUE); // Field discapacidad
		$this->BuildDropDownFilter($this->tipodiscapacidad, $sFilter, $this->tipodiscapacidad->SearchOperator, FALSE, TRUE); // Field tipodiscapacidad
		$this->BuildExtendedFilter($this->fecha, $sFilter, FALSE, TRUE); // Field fecha

		// Save parms to session
		$this->SetSessionDropDownValue($this->curso->DropDownValue, $this->curso->SearchOperator, 'curso'); // Field curso
		$this->SetSessionDropDownValue($this->discapacidad->DropDownValue, $this->discapacidad->SearchOperator, 'discapacidad'); // Field discapacidad
		$this->SetSessionDropDownValue($this->tipodiscapacidad->DropDownValue, $this->tipodiscapacidad->SearchOperator, 'tipodiscapacidad'); // Field tipodiscapacidad
		$this->SetSessionFilterValues($this->fecha->SearchValue, $this->fecha->SearchOperator, $this->fecha->SearchCondition, $this->fecha->SearchValue2, $this->fecha->SearchOperator2, 'fecha'); // Field fecha

		// Setup filter
		if ($bSetupFilter) {

			// Field curso
			$sWrk = "";
			$this->BuildDropDownFilter($this->curso, $sWrk, $this->curso->SearchOperator);
			ewr_LoadSelectionFromFilter($this->curso, $sWrk, $this->curso->SelectionList, $this->curso->DropDownValue);
			$_SESSION['sel_viewestudiantesetareocurso_curso'] = ($this->curso->SelectionList == "") ? EWR_INIT_VALUE : $this->curso->SelectionList;

			// Field discapacidad
			$sWrk = "";
			$this->BuildDropDownFilter($this->discapacidad, $sWrk, $this->discapacidad->SearchOperator);
			ewr_LoadSelectionFromFilter($this->discapacidad, $sWrk, $this->discapacidad->SelectionList, $this->discapacidad->DropDownValue);
			$_SESSION['sel_viewestudiantesetareocurso_discapacidad'] = ($this->discapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->discapacidad->SelectionList;

			// Field tipodiscapacidad
			$sWrk = "";
			$this->BuildDropDownFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->SearchOperator);
			ewr_LoadSelectionFromFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->SelectionList, $this->tipodiscapacidad->DropDownValue);
			$_SESSION['sel_viewestudiantesetareocurso_tipodiscapacidad'] = ($this->tipodiscapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->tipodiscapacidad->SelectionList;

			// Field fecha
			$sWrk = "";
			$this->BuildExtendedFilter($this->fecha, $sWrk);
			ewr_LoadSelectionFromFilter($this->fecha, $sWrk, $this->fecha->SelectionList);
			$_SESSION['sel_viewestudiantesetareocurso_fecha'] = ($this->fecha->SelectionList == "") ? EWR_INIT_VALUE : $this->fecha->SelectionList;
		}

		// Field curso
		ewr_LoadDropDownList($this->curso->DropDownList, $this->curso->DropDownValue);

		// Field discapacidad
		ewr_LoadDropDownList($this->discapacidad->DropDownList, $this->discapacidad->DropDownValue);

		// Field tipodiscapacidad
		ewr_LoadDropDownList($this->tipodiscapacidad->DropDownList, $this->tipodiscapacidad->DropDownValue);
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewestudiantesetareocurso_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewestudiantesetareocurso_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewestudiantesetareocurso_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewestudiantesetareocurso_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewestudiantesetareocurso_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewestudiantesetareocurso_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewestudiantesetareocurso_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewestudiantesetareocurso_' . $parm] = $sv;
		$_SESSION['so_viewestudiantesetareocurso_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewestudiantesetareocurso_' . $parm] = $sv1;
		$_SESSION['so_viewestudiantesetareocurso_' . $parm] = $so1;
		$_SESSION['sc_viewestudiantesetareocurso_' . $parm] = $sc;
		$_SESSION['sv2_viewestudiantesetareocurso_' . $parm] = $sv2;
		$_SESSION['so2_viewestudiantesetareocurso_' . $parm] = $so2;
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
		$_SESSION["sel_viewestudiantesetareocurso_$parm"] = "";
		$_SESSION["rf_viewestudiantesetareocurso_$parm"] = "";
		$_SESSION["rt_viewestudiantesetareocurso_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewestudiantesetareocurso_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewestudiantesetareocurso_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewestudiantesetareocurso_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field curso
		$this->curso->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->curso->DropDownValue = $this->curso->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->curso, $sWrk, $this->curso->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->curso, $sWrk, $this->curso->DefaultSelectionList);
		if (!$this->SearchCommand) $this->curso->SelectionList = $this->curso->DefaultSelectionList;

		// Field discapacidad
		$this->discapacidad->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->discapacidad->DropDownValue = $this->discapacidad->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->discapacidad, $sWrk, $this->discapacidad->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->discapacidad, $sWrk, $this->discapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->discapacidad->SelectionList = $this->discapacidad->DefaultSelectionList;

		// Field tipodiscapacidad
		$this->tipodiscapacidad->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->tipodiscapacidad->DropDownValue = $this->tipodiscapacidad->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->tipodiscapacidad->SelectionList = $this->tipodiscapacidad->DefaultSelectionList;
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

		// Field curso
		// $this->curso->DefaultSelectionList = array("val1", "val2");
		// Field discapacidad
		// $this->discapacidad->DefaultSelectionList = array("val1", "val2");
		// Field tipodiscapacidad
		// $this->tipodiscapacidad->DefaultSelectionList = array("val1", "val2");
		// Field fecha
		// $this->fecha->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check curso extended filter
		if ($this->NonTextFilterApplied($this->curso))
			return TRUE;

		// Check curso popup filter
		if (!ewr_MatchedArray($this->curso->DefaultSelectionList, $this->curso->SelectionList))
			return TRUE;

		// Check discapacidad extended filter
		if ($this->NonTextFilterApplied($this->discapacidad))
			return TRUE;

		// Check discapacidad popup filter
		if (!ewr_MatchedArray($this->discapacidad->DefaultSelectionList, $this->discapacidad->SelectionList))
			return TRUE;

		// Check tipodiscapacidad extended filter
		if ($this->NonTextFilterApplied($this->tipodiscapacidad))
			return TRUE;

		// Check tipodiscapacidad popup filter
		if (!ewr_MatchedArray($this->tipodiscapacidad->DefaultSelectionList, $this->tipodiscapacidad->SelectionList))
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

		// Field curso
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->curso, $sExtWrk, $this->curso->SearchOperator);
		if (is_array($this->curso->SelectionList))
			$sWrk = ewr_JoinArray($this->curso->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->curso->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field discapacidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->discapacidad, $sExtWrk, $this->discapacidad->SearchOperator);
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
		$this->BuildDropDownFilter($this->tipodiscapacidad, $sExtWrk, $this->tipodiscapacidad->SearchOperator);
		if (is_array($this->tipodiscapacidad->SelectionList))
			$sWrk = ewr_JoinArray($this->tipodiscapacidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tipodiscapacidad->FldCaption() . "</span>" . $sFilter . "</div>";

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

		// Field curso
		$sWrk = "";
		$sWrk = ($this->curso->DropDownValue <> EWR_INIT_VALUE) ? $this->curso->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_curso\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->curso->SelectionList <> EWR_INIT_VALUE) ? $this->curso->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_curso\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field discapacidad
		$sWrk = "";
		$sWrk = ($this->discapacidad->DropDownValue <> EWR_INIT_VALUE) ? $this->discapacidad->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_discapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
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
		$sWrk = ($this->tipodiscapacidad->DropDownValue <> EWR_INIT_VALUE) ? $this->tipodiscapacidad->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_tipodiscapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field curso
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_curso", $filter)) {
			$sWrk = $filter["sv_curso"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_curso"], "curso");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_curso", $filter)) {
			$sWrk = $filter["sel_curso"];
			$sWrk = explode("||", $sWrk);
			$this->curso->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantesetareocurso_curso"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "curso"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "curso");
			$this->curso->SelectionList = "";
			$_SESSION["sel_viewestudiantesetareocurso_curso"] = "";
		}

		// Field discapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_discapacidad", $filter)) {
			$sWrk = $filter["sv_discapacidad"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_discapacidad"], "discapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_discapacidad", $filter)) {
			$sWrk = $filter["sel_discapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->discapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantesetareocurso_discapacidad"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "discapacidad"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "discapacidad");
			$this->discapacidad->SelectionList = "";
			$_SESSION["sel_viewestudiantesetareocurso_discapacidad"] = "";
		}

		// Field tipodiscapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_tipodiscapacidad", $filter)) {
			$sWrk = $filter["sv_tipodiscapacidad"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_tipodiscapacidad"], "tipodiscapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_tipodiscapacidad", $filter)) {
			$sWrk = $filter["sel_tipodiscapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->tipodiscapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantesetareocurso_tipodiscapacidad"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "tipodiscapacidad"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "tipodiscapacidad");
			$this->tipodiscapacidad->SelectionList = "";
			$_SESSION["sel_viewestudiantesetareocurso_tipodiscapacidad"] = "";
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
			$_SESSION["sel_viewestudiantesetareocurso_fecha"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha");
			$this->fecha->SelectionList = "";
			$_SESSION["sel_viewestudiantesetareocurso_fecha"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->DropDownFilterExist($this->curso, $this->curso->SearchOperator)) {
			if (is_array($this->curso->SelectionList)) {
				$sFilter = ewr_FilterSql($this->curso, "`curso`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->curso, $sFilter, "popup");
				$this->curso->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->discapacidad, $this->discapacidad->SearchOperator)) {
			if (is_array($this->discapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->discapacidad, "`discapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->discapacidad, $sFilter, "popup");
				$this->discapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->tipodiscapacidad, $this->tipodiscapacidad->SearchOperator)) {
			if (is_array($this->tipodiscapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->tipodiscapacidad, "`tipodiscapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->tipodiscapacidad, $sFilter, "popup");
				$this->tipodiscapacidad->CurrentFilter = $sFilter;
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

	// Return drill down filter
	function GetDrillDownFilter() {
		global $ReportLanguage;
		$sFilterList = "";
		$filter = "";
		$post = $_POST;
		$opt = @$post["d"];
		if ($opt == "1" || $opt == "2") {
			$mastertable = @$post["s"]; // Get source table
			$sql = @$post["fecha"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@fecha", "`fecha`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fecha->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}

			// Save to session
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_" . EWR_TABLE_MASTER_TABLE] = $mastertable;
			$_SESSION['do_viewestudiantesetareocurso'] = $opt;
			$_SESSION['df_viewestudiantesetareocurso'] = $filter;
			$_SESSION['dl_viewestudiantesetareocurso'] = $sFilterList;
		} elseif (@$_GET["cmd"] == "resetdrilldown") { // Clear drill down
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_" . EWR_TABLE_MASTER_TABLE] = "";
			$_SESSION['do_viewestudiantesetareocurso'] = "";
			$_SESSION['df_viewestudiantesetareocurso'] = "";
			$_SESSION['dl_viewestudiantesetareocurso'] = "";
		} else { // Restore from Session
			$opt = @$_SESSION['do_viewestudiantesetareocurso'];
			$filter = @$_SESSION['df_viewestudiantesetareocurso'];
			$sFilterList = @$_SESSION['dl_viewestudiantesetareocurso'];
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
			$this->curso->setSort("");
			$this->id->setSort("");
			$this->nombres->setSort("");
			$this->apellidopaterno->setSort("");
			$this->apellidomaterno->setSort("");
			$this->codigorude_es->setSort("");
			$this->ci->setSort("");
			$this->discapacidad->setSort("");
			$this->tipodiscapacidad->setSort("");
			$this->unidadeducativa->setSort("");
			$this->fecha->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->curso); // curso
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->nombres); // nombres
			$this->UpdateSort($this->apellidopaterno); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno); // apellidomaterno
			$this->UpdateSort($this->codigorude_es); // codigorude_es
			$this->UpdateSort($this->ci); // ci
			$this->UpdateSort($this->discapacidad); // discapacidad
			$this->UpdateSort($this->tipodiscapacidad); // tipodiscapacidad
			$this->UpdateSort($this->unidadeducativa); // unidadeducativa
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
if (!isset($viewestudiantesetareocurso_rpt)) $viewestudiantesetareocurso_rpt = new crviewestudiantesetareocurso_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewestudiantesetareocurso_rpt;

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
var viewestudiantesetareocurso_rpt = new ewr_Page("viewestudiantesetareocurso_rpt");

// Page properties
viewestudiantesetareocurso_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewestudiantesetareocurso_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewestudiantesetareocursorpt = new ewr_Form("fviewestudiantesetareocursorpt");

// Validate method
fviewestudiantesetareocursorpt.Validate = function() {
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
fviewestudiantesetareocursorpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewestudiantesetareocursorpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewestudiantesetareocursorpt.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fviewestudiantesetareocursorpt.Lists["sv_curso"] = {"LinkField":"sv_curso","Ajax":true,"DisplayFields":["sv_curso","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewestudiantesetareocursorpt.Lists["sv_discapacidad"] = {"LinkField":"sv_discapacidad","Ajax":true,"DisplayFields":["sv_discapacidad","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewestudiantesetareocursorpt.Lists["sv_tipodiscapacidad"] = {"LinkField":"sv_tipodiscapacidad","Ajax":true,"DisplayFields":["sv_tipodiscapacidad","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
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
<?php if ($Page->ShowDrillDownFilter) { ?>
<?php $Page->ShowDrillDownList() ?>
<?php } ?>
<!-- Summary Report begins -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<!-- Search form (begin) -->
<form name="fviewestudiantesetareocursorpt" id="fviewestudiantesetareocursorpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewestudiantesetareocursorpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_curso" class="ewCell form-group">
	<label for="sv_curso" class="ewSearchCaption ewLabel"><?php echo $Page->curso->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->curso->EditAttrs["class"], "form-control"); ?>
<select data-table="viewestudiantesetareocurso" data-field="x_curso" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->curso->DisplayValueSeparator) ? json_encode($Page->curso->DisplayValueSeparator) : $Page->curso->DisplayValueSeparator) ?>" id="sv_curso" name="sv_curso"<?php echo $Page->curso->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->curso->AdvancedFilters) ? count($Page->curso->AdvancedFilters) : 0;
	$cntd = is_array($Page->curso->DropDownList) ? count($Page->curso->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->curso->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->curso->DropDownValue, $filter->ID) ? " selected" : "";
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
<option value="<?php echo $Page->curso->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->curso->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_curso" id="s_sv_curso" value="<?php echo $Page->curso->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewestudiantesetareocursorpt.Lists["sv_curso"].Options = <?php echo ewr_ArrayToJson($Page->curso->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_discapacidad" class="ewCell form-group">
	<label for="sv_discapacidad" class="ewSearchCaption ewLabel"><?php echo $Page->discapacidad->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->discapacidad->EditAttrs["class"], "form-control"); ?>
<select data-table="viewestudiantesetareocurso" data-field="x_discapacidad" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->discapacidad->DisplayValueSeparator) ? json_encode($Page->discapacidad->DisplayValueSeparator) : $Page->discapacidad->DisplayValueSeparator) ?>" id="sv_discapacidad" name="sv_discapacidad"<?php echo $Page->discapacidad->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->discapacidad->AdvancedFilters) ? count($Page->discapacidad->AdvancedFilters) : 0;
	$cntd = is_array($Page->discapacidad->DropDownList) ? count($Page->discapacidad->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->discapacidad->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->discapacidad->DropDownValue, $filter->ID) ? " selected" : "";
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
<option value="<?php echo $Page->discapacidad->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->discapacidad->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_discapacidad" id="s_sv_discapacidad" value="<?php echo $Page->discapacidad->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewestudiantesetareocursorpt.Lists["sv_discapacidad"].Options = <?php echo ewr_ArrayToJson($Page->discapacidad->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_tipodiscapacidad" class="ewCell form-group">
	<label for="sv_tipodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $Page->tipodiscapacidad->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->tipodiscapacidad->EditAttrs["class"], "form-control"); ?>
<select data-table="viewestudiantesetareocurso" data-field="x_tipodiscapacidad" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->tipodiscapacidad->DisplayValueSeparator) ? json_encode($Page->tipodiscapacidad->DisplayValueSeparator) : $Page->tipodiscapacidad->DisplayValueSeparator) ?>" id="sv_tipodiscapacidad" name="sv_tipodiscapacidad"<?php echo $Page->tipodiscapacidad->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->tipodiscapacidad->AdvancedFilters) ? count($Page->tipodiscapacidad->AdvancedFilters) : 0;
	$cntd = is_array($Page->tipodiscapacidad->DropDownList) ? count($Page->tipodiscapacidad->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->tipodiscapacidad->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->tipodiscapacidad->DropDownValue, $filter->ID) ? " selected" : "";
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
<option value="<?php echo $Page->tipodiscapacidad->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->tipodiscapacidad->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_tipodiscapacidad" id="s_sv_tipodiscapacidad" value="<?php echo $Page->tipodiscapacidad->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewestudiantesetareocursorpt.Lists["sv_tipodiscapacidad"].Options = <?php echo ewr_ArrayToJson($Page->tipodiscapacidad->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_fecha" class="ewCell form-group">
	<label for="sv_fecha" class="ewSearchCaption ewLabel"><?php echo $Page->fecha->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so_fecha" id="so_fecha" value="BETWEEN"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fecha->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantesetareocurso" data-field="x_fecha" id="sv_fecha" name="sv_fecha" placeholder="<?php echo $Page->fecha->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha->SearchValue) ?>" data-calendar='true' data-options='{"ignoreReadonly":true,"useCurrent":false,"format":0}'<?php echo $Page->fecha->EditAttributes() ?>>
</span>
	<span class="ewSearchCond btw1_fecha"><?php echo $ReportLanguage->Phrase("AND") ?></span>
	<span class="ewSearchField btw1_fecha">
<?php ewr_PrependClass($Page->fecha->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantesetareocurso" data-field="x_fecha" id="sv2_fecha" name="sv2_fecha" placeholder="<?php echo $Page->fecha->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha->SearchValue2) ?>" data-calendar='true' data-options='{"ignoreReadonly":true,"useCurrent":false,"format":0}'<?php echo $Page->fecha->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewestudiantesetareocursorpt.Init();
fviewestudiantesetareocursorpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<div id="gmp_viewestudiantesetareocurso" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->curso->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="curso"><div class="viewestudiantesetareocurso_curso"><span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="curso">
<?php if ($Page->SortUrl($Page->curso) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_curso">
			<span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantesetareocurso_curso', range: false, from: '<?php echo $Page->curso->RangeFrom; ?>', to: '<?php echo $Page->curso->RangeTo; ?>', url: 'viewestudiantesetareocursorpt.php' });" id="x_curso<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_curso" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->curso) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->curso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->curso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantesetareocurso_curso', range: false, from: '<?php echo $Page->curso->RangeFrom; ?>', to: '<?php echo $Page->curso->RangeTo; ?>', url: 'viewestudiantesetareocursorpt.php' });" id="x_curso<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id"><div class="viewestudiantesetareocurso_id"><span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id">
<?php if ($Page->SortUrl($Page->id) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_id">
			<span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_id" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombres->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombres"><div class="viewestudiantesetareocurso_nombres"><span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombres">
<?php if ($Page->SortUrl($Page->nombres) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_nombres">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_nombres" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombres) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidopaterno"><div class="viewestudiantesetareocurso_apellidopaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidopaterno">
<?php if ($Page->SortUrl($Page->apellidopaterno) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_apellidopaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_apellidopaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidopaterno) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidomaterno"><div class="viewestudiantesetareocurso_apellidomaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidomaterno">
<?php if ($Page->SortUrl($Page->apellidomaterno) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_apellidomaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_apellidomaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidomaterno) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->codigorude_es->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="codigorude_es"><div class="viewestudiantesetareocurso_codigorude_es"><span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="codigorude_es">
<?php if ($Page->SortUrl($Page->codigorude_es) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_codigorude_es">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_codigorude_es" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->codigorude_es) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->codigorude_es->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->codigorude_es->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ci"><div class="viewestudiantesetareocurso_ci"><span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ci">
<?php if ($Page->SortUrl($Page->ci) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_ci">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_ci" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ci) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="discapacidad"><div class="viewestudiantesetareocurso_discapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="discapacidad">
<?php if ($Page->SortUrl($Page->discapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_discapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantesetareocurso_discapacidad', range: false, from: '<?php echo $Page->discapacidad->RangeFrom; ?>', to: '<?php echo $Page->discapacidad->RangeTo; ?>', url: 'viewestudiantesetareocursorpt.php' });" id="x_discapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_discapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->discapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantesetareocurso_discapacidad', range: false, from: '<?php echo $Page->discapacidad->RangeFrom; ?>', to: '<?php echo $Page->discapacidad->RangeTo; ?>', url: 'viewestudiantesetareocursorpt.php' });" id="x_discapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipodiscapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipodiscapacidad"><div class="viewestudiantesetareocurso_tipodiscapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipodiscapacidad">
<?php if ($Page->SortUrl($Page->tipodiscapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_tipodiscapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantesetareocurso_tipodiscapacidad', range: false, from: '<?php echo $Page->tipodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->tipodiscapacidad->RangeTo; ?>', url: 'viewestudiantesetareocursorpt.php' });" id="x_tipodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_tipodiscapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipodiscapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantesetareocurso_tipodiscapacidad', range: false, from: '<?php echo $Page->tipodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->tipodiscapacidad->RangeTo; ?>', url: 'viewestudiantesetareocursorpt.php' });" id="x_tipodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->unidadeducativa->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="unidadeducativa"><div class="viewestudiantesetareocurso_unidadeducativa"><span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="unidadeducativa">
<?php if ($Page->SortUrl($Page->unidadeducativa) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_unidadeducativa">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_unidadeducativa" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->unidadeducativa) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->unidadeducativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->unidadeducativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha"><div class="viewestudiantesetareocurso_fecha"><span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha">
<?php if ($Page->SortUrl($Page->fecha) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantesetareocurso_fecha">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantesetareocurso_fecha', range: false, from: '<?php echo $Page->fecha->RangeFrom; ?>', to: '<?php echo $Page->fecha->RangeTo; ?>', url: 'viewestudiantesetareocursorpt.php' });" id="x_fecha<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantesetareocurso_fecha" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantesetareocurso_fecha', range: false, from: '<?php echo $Page->fecha->RangeFrom; ?>', to: '<?php echo $Page->fecha->RangeTo; ?>', url: 'viewestudiantesetareocursorpt.php' });" id="x_fecha<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
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
<?php if ($Page->curso->Visible) { ?>
		<td data-field="curso"<?php echo $Page->curso->CellAttributes() ?>>
<span<?php echo $Page->curso->ViewAttributes() ?>><?php echo $Page->curso->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id->Visible) { ?>
		<td data-field="id"<?php echo $Page->id->CellAttributes() ?>>
<span<?php echo $Page->id->ViewAttributes() ?>><?php echo $Page->id->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombres->Visible) { ?>
		<td data-field="nombres"<?php echo $Page->nombres->CellAttributes() ?>>
<span<?php echo $Page->nombres->ViewAttributes() ?>><?php echo $Page->nombres->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
		<td data-field="apellidopaterno"<?php echo $Page->apellidopaterno->CellAttributes() ?>>
<span<?php echo $Page->apellidopaterno->ViewAttributes() ?>><?php echo $Page->apellidopaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
		<td data-field="apellidomaterno"<?php echo $Page->apellidomaterno->CellAttributes() ?>>
<span<?php echo $Page->apellidomaterno->ViewAttributes() ?>><?php echo $Page->apellidomaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->codigorude_es->Visible) { ?>
		<td data-field="codigorude_es"<?php echo $Page->codigorude_es->CellAttributes() ?>>
<span<?php echo $Page->codigorude_es->ViewAttributes() ?>><?php echo $Page->codigorude_es->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
		<td data-field="ci"<?php echo $Page->ci->CellAttributes() ?>>
<span<?php echo $Page->ci->ViewAttributes() ?>><?php echo $Page->ci->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
		<td data-field="discapacidad"<?php echo $Page->discapacidad->CellAttributes() ?>>
<span<?php echo $Page->discapacidad->ViewAttributes() ?>><?php echo $Page->discapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipodiscapacidad->Visible) { ?>
		<td data-field="tipodiscapacidad"<?php echo $Page->tipodiscapacidad->CellAttributes() ?>>
<span<?php echo $Page->tipodiscapacidad->ViewAttributes() ?>><?php echo $Page->tipodiscapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->unidadeducativa->Visible) { ?>
		<td data-field="unidadeducativa"<?php echo $Page->unidadeducativa->CellAttributes() ?>>
<span<?php echo $Page->unidadeducativa->ViewAttributes() ?>><?php echo $Page->unidadeducativa->ListViewValue() ?></span></td>
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
<div id="gmp_viewestudiantesetareocurso" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
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
<?php include "viewestudiantesetareocursorptpager.php" ?>
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
