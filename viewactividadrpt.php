<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewactividadrptinfo.php" ?>
<?php

//
// Page class
//

$viewactividad_rpt = NULL; // Initialize page object first

class crviewactividad_rpt extends crviewactividad {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewactividad_rpt';

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

		// Table object (viewactividad)
		if (!isset($GLOBALS["viewactividad"])) {
			$GLOBALS["viewactividad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewactividad"];
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
			define("EWR_TABLE_NAME", 'viewactividad', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewactividadrpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewactividad');
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
		$this->sector->PlaceHolder = $this->sector->FldCaption();
		$this->tipoactividad->PlaceHolder = $this->tipoactividad->FldCaption();
		$this->organizador->PlaceHolder = $this->organizador->FldCaption();
		$this->nombreactividad->PlaceHolder = $this->nombreactividad->FldCaption();
		$this->fecha_inicio->PlaceHolder = $this->fecha_inicio->FldCaption();
		$this->fecha_fin->PlaceHolder = $this->fecha_fin->FldCaption();
		$this->contenido->PlaceHolder = $this->contenido->FldCaption();
		$this->observaciones->PlaceHolder = $this->observaciones->FldCaption();
		$this->nombreinstitucion->PlaceHolder = $this->nombreinstitucion->FldCaption();

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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewactividad\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewactividad',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewactividadrpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewactividadrpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewactividadrpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->sector->SetVisibility();
		$this->tipoactividad->SetVisibility();
		$this->organizador->SetVisibility();
		$this->nombreactividad->SetVisibility();
		$this->nombrelocal->SetVisibility();
		$this->direccionlocal->SetVisibility();
		$this->fecha_inicio->SetVisibility();
		$this->fecha_fin->SetVisibility();
		$this->horasprogramadas->SetVisibility();
		$this->perosnanombre->SetVisibility();
		$this->personaapellidomaterno->SetVisibility();
		$this->personaapellidopaterno->SetVisibility();
		$this->contenido->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->nombreinstitucion->SetVisibility();

		// Handle drill down
		$sDrillDownFilter = $this->GetDrillDownFilter();
		$grDrillDownInPanel = $this->DrillDownInPanel;
		if ($this->DrillDown)
			ewr_AddFilter($this->Filter, $sDrillDownFilter);

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
		$this->sector->SelectionList = "";
		$this->sector->DefaultSelectionList = "";
		$this->sector->ValueList = "";
		$this->tipoactividad->SelectionList = "";
		$this->tipoactividad->DefaultSelectionList = "";
		$this->tipoactividad->ValueList = "";
		$this->organizador->SelectionList = "";
		$this->organizador->DefaultSelectionList = "";
		$this->organizador->ValueList = "";
		$this->fecha_inicio->SelectionList = "";
		$this->fecha_inicio->DefaultSelectionList = "";
		$this->fecha_inicio->ValueList = "";
		$this->fecha_fin->SelectionList = "";
		$this->fecha_fin->DefaultSelectionList = "";
		$this->fecha_fin->ValueList = "";
		$this->nombreinstitucion->SelectionList = "";
		$this->nombreinstitucion->DefaultSelectionList = "";
		$this->nombreinstitucion->ValueList = "";

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
				$this->FirstRowData['sector'] = ewr_Conv($rs->fields('sector'), 200);
				$this->FirstRowData['tipoactividad'] = ewr_Conv($rs->fields('tipoactividad'), 200);
				$this->FirstRowData['organizador'] = ewr_Conv($rs->fields('organizador'), 3);
				$this->FirstRowData['nombreactividad'] = ewr_Conv($rs->fields('nombreactividad'), 200);
				$this->FirstRowData['nombrelocal'] = ewr_Conv($rs->fields('nombrelocal'), 200);
				$this->FirstRowData['direccionlocal'] = ewr_Conv($rs->fields('direccionlocal'), 200);
				$this->FirstRowData['fecha_inicio'] = ewr_Conv($rs->fields('fecha_inicio'), 133);
				$this->FirstRowData['fecha_fin'] = ewr_Conv($rs->fields('fecha_fin'), 133);
				$this->FirstRowData['horasprogramadas'] = ewr_Conv($rs->fields('horasprogramadas'), 200);
				$this->FirstRowData['perosnanombre'] = ewr_Conv($rs->fields('perosnanombre'), 200);
				$this->FirstRowData['personaapellidomaterno'] = ewr_Conv($rs->fields('personaapellidomaterno'), 200);
				$this->FirstRowData['personaapellidopaterno'] = ewr_Conv($rs->fields('personaapellidopaterno'), 200);
				$this->FirstRowData['contenido'] = ewr_Conv($rs->fields('contenido'), 200);
				$this->FirstRowData['observaciones'] = ewr_Conv($rs->fields('observaciones'), 200);
				$this->FirstRowData['nombreinstitucion'] = ewr_Conv($rs->fields('nombreinstitucion'), 200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->sector->setDbValue($rs->fields('sector'));
			$this->tipoactividad->setDbValue($rs->fields('tipoactividad'));
			$this->organizador->setDbValue($rs->fields('organizador'));
			$this->nombreactividad->setDbValue($rs->fields('nombreactividad'));
			$this->nombrelocal->setDbValue($rs->fields('nombrelocal'));
			$this->direccionlocal->setDbValue($rs->fields('direccionlocal'));
			$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
			$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
			$this->horasprogramadas->setDbValue($rs->fields('horasprogramadas'));
			$this->perosnanombre->setDbValue($rs->fields('perosnanombre'));
			$this->personaapellidomaterno->setDbValue($rs->fields('personaapellidomaterno'));
			$this->personaapellidopaterno->setDbValue($rs->fields('personaapellidopaterno'));
			$this->contenido->setDbValue($rs->fields('contenido'));
			$this->observaciones->setDbValue($rs->fields('observaciones'));
			$this->nombreinstitucion->setDbValue($rs->fields('nombreinstitucion'));
			$this->Val[1] = $this->sector->CurrentValue;
			$this->Val[2] = $this->tipoactividad->CurrentValue;
			$this->Val[3] = $this->organizador->CurrentValue;
			$this->Val[4] = $this->nombreactividad->CurrentValue;
			$this->Val[5] = $this->nombrelocal->CurrentValue;
			$this->Val[6] = $this->direccionlocal->CurrentValue;
			$this->Val[7] = $this->fecha_inicio->CurrentValue;
			$this->Val[8] = $this->fecha_fin->CurrentValue;
			$this->Val[9] = $this->horasprogramadas->CurrentValue;
			$this->Val[10] = $this->perosnanombre->CurrentValue;
			$this->Val[11] = $this->personaapellidomaterno->CurrentValue;
			$this->Val[12] = $this->personaapellidopaterno->CurrentValue;
			$this->Val[13] = $this->contenido->CurrentValue;
			$this->Val[14] = $this->observaciones->CurrentValue;
			$this->Val[15] = $this->nombreinstitucion->CurrentValue;
		} else {
			$this->sector->setDbValue("");
			$this->tipoactividad->setDbValue("");
			$this->organizador->setDbValue("");
			$this->nombreactividad->setDbValue("");
			$this->nombrelocal->setDbValue("");
			$this->direccionlocal->setDbValue("");
			$this->fecha_inicio->setDbValue("");
			$this->fecha_fin->setDbValue("");
			$this->horasprogramadas->setDbValue("");
			$this->perosnanombre->setDbValue("");
			$this->personaapellidomaterno->setDbValue("");
			$this->personaapellidopaterno->setDbValue("");
			$this->contenido->setDbValue("");
			$this->observaciones->setDbValue("");
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
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Build distinct values for sector

			if ($popupname == 'viewactividad_sector') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->sector, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->sector->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->sector->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->sector->setDbValue($rswrk->fields[0]);
					$this->sector->ViewValue = @$rswrk->fields[1];
					if (is_null($this->sector->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->sector->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->sector->ValueList, $this->sector->CurrentValue, $this->sector->ViewValue, FALSE, $this->sector->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->sector->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->sector->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->sector;
			}

			// Build distinct values for tipoactividad
			if ($popupname == 'viewactividad_tipoactividad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->tipoactividad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->tipoactividad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->tipoactividad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->tipoactividad->setDbValue($rswrk->fields[0]);
					$this->tipoactividad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->tipoactividad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->tipoactividad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->tipoactividad->ValueList, $this->tipoactividad->CurrentValue, $this->tipoactividad->ViewValue, FALSE, $this->tipoactividad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->tipoactividad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->tipoactividad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->tipoactividad;
			}

			// Build distinct values for organizador
			if ($popupname == 'viewactividad_organizador') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->organizador, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->organizador->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->organizador->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->organizador->setDbValue($rswrk->fields[0]);
					$this->organizador->ViewValue = @$rswrk->fields[1];
					if (is_null($this->organizador->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->organizador->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->organizador->ValueList, $this->organizador->CurrentValue, $this->organizador->ViewValue, FALSE, $this->organizador->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->organizador->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->organizador->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->organizador;
			}

			// Build distinct values for fecha_inicio
			if ($popupname == 'viewactividad_fecha_inicio') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha_inicio, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->fecha_inicio->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->fecha_inicio->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->fecha_inicio->setDbValue($rswrk->fields[0]);
					$this->fecha_inicio->ViewValue = @$rswrk->fields[1];
					if (is_null($this->fecha_inicio->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->fecha_inicio->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->fecha_inicio->ValueList, $this->fecha_inicio->CurrentValue, $this->fecha_inicio->ViewValue, FALSE, $this->fecha_inicio->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->fecha_inicio->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->fecha_inicio->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->fecha_inicio;
			}

			// Build distinct values for fecha_fin
			if ($popupname == 'viewactividad_fecha_fin') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha_fin, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->fecha_fin->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->fecha_fin->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->fecha_fin->setDbValue($rswrk->fields[0]);
					$this->fecha_fin->ViewValue = @$rswrk->fields[1];
					if (is_null($this->fecha_fin->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->fecha_fin->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->fecha_fin->ValueList, $this->fecha_fin->CurrentValue, $this->fecha_fin->ViewValue, FALSE, $this->fecha_fin->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->fecha_fin->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->fecha_fin->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->fecha_fin;
			}

			// Build distinct values for nombreinstitucion
			if ($popupname == 'viewactividad_nombreinstitucion') {
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
				$this->ClearSessionSelection('sector');
				$this->ClearSessionSelection('tipoactividad');
				$this->ClearSessionSelection('organizador');
				$this->ClearSessionSelection('fecha_inicio');
				$this->ClearSessionSelection('fecha_fin');
				$this->ClearSessionSelection('nombreinstitucion');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get sector selected values

		if (is_array(@$_SESSION["sel_viewactividad_sector"])) {
			$this->LoadSelectionFromSession('sector');
		} elseif (@$_SESSION["sel_viewactividad_sector"] == EWR_INIT_VALUE) { // Select all
			$this->sector->SelectionList = "";
		}

		// Get tipoactividad selected values
		if (is_array(@$_SESSION["sel_viewactividad_tipoactividad"])) {
			$this->LoadSelectionFromSession('tipoactividad');
		} elseif (@$_SESSION["sel_viewactividad_tipoactividad"] == EWR_INIT_VALUE) { // Select all
			$this->tipoactividad->SelectionList = "";
		}

		// Get organizador selected values
		if (is_array(@$_SESSION["sel_viewactividad_organizador"])) {
			$this->LoadSelectionFromSession('organizador');
		} elseif (@$_SESSION["sel_viewactividad_organizador"] == EWR_INIT_VALUE) { // Select all
			$this->organizador->SelectionList = "";
		}

		// Get fecha_inicio selected values
		if (is_array(@$_SESSION["sel_viewactividad_fecha_inicio"])) {
			$this->LoadSelectionFromSession('fecha_inicio');
		} elseif (@$_SESSION["sel_viewactividad_fecha_inicio"] == EWR_INIT_VALUE) { // Select all
			$this->fecha_inicio->SelectionList = "";
		}

		// Get fecha_fin selected values
		if (is_array(@$_SESSION["sel_viewactividad_fecha_fin"])) {
			$this->LoadSelectionFromSession('fecha_fin');
		} elseif (@$_SESSION["sel_viewactividad_fecha_fin"] == EWR_INIT_VALUE) { // Select all
			$this->fecha_fin->SelectionList = "";
		}

		// Get nombreinstitucion selected values
		if (is_array(@$_SESSION["sel_viewactividad_nombreinstitucion"])) {
			$this->LoadSelectionFromSession('nombreinstitucion');
		} elseif (@$_SESSION["sel_viewactividad_nombreinstitucion"] == EWR_INIT_VALUE) { // Select all
			$this->nombreinstitucion->SelectionList = "";
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

			// sector
			$this->sector->HrefValue = "";

			// tipoactividad
			$this->tipoactividad->HrefValue = "";

			// organizador
			$this->organizador->HrefValue = "";

			// nombreactividad
			$this->nombreactividad->HrefValue = "";

			// nombrelocal
			$this->nombrelocal->HrefValue = "";

			// direccionlocal
			$this->direccionlocal->HrefValue = "";

			// fecha_inicio
			$this->fecha_inicio->HrefValue = "";

			// fecha_fin
			$this->fecha_fin->HrefValue = "";

			// horasprogramadas
			$this->horasprogramadas->HrefValue = "";

			// perosnanombre
			$this->perosnanombre->HrefValue = "";

			// personaapellidomaterno
			$this->personaapellidomaterno->HrefValue = "";

			// personaapellidopaterno
			$this->personaapellidopaterno->HrefValue = "";

			// contenido
			$this->contenido->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// sector
			$this->sector->ViewValue = $this->sector->CurrentValue;
			$this->sector->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipoactividad
			$this->tipoactividad->ViewValue = $this->tipoactividad->CurrentValue;
			$this->tipoactividad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// organizador
			$this->organizador->ViewValue = $this->organizador->CurrentValue;
			$this->organizador->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreactividad
			$this->nombreactividad->ViewValue = $this->nombreactividad->CurrentValue;
			$this->nombreactividad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombrelocal
			$this->nombrelocal->ViewValue = $this->nombrelocal->CurrentValue;
			$this->nombrelocal->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// direccionlocal
			$this->direccionlocal->ViewValue = $this->direccionlocal->CurrentValue;
			$this->direccionlocal->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha_inicio
			$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
			$this->fecha_inicio->ViewValue = ewr_FormatDateTime($this->fecha_inicio->ViewValue, 0);
			$this->fecha_inicio->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewValue = ewr_FormatDateTime($this->fecha_fin->ViewValue, 0);
			$this->fecha_fin->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// horasprogramadas
			$this->horasprogramadas->ViewValue = $this->horasprogramadas->CurrentValue;
			$this->horasprogramadas->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// perosnanombre
			$this->perosnanombre->ViewValue = $this->perosnanombre->CurrentValue;
			$this->perosnanombre->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// personaapellidomaterno
			$this->personaapellidomaterno->ViewValue = $this->personaapellidomaterno->CurrentValue;
			$this->personaapellidomaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// personaapellidopaterno
			$this->personaapellidopaterno->ViewValue = $this->personaapellidopaterno->CurrentValue;
			$this->personaapellidopaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// contenido
			$this->contenido->ViewValue = $this->contenido->CurrentValue;
			$this->contenido->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreinstitucion
			$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
			$this->nombreinstitucion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// sector
			$this->sector->HrefValue = "";

			// tipoactividad
			$this->tipoactividad->HrefValue = "";

			// organizador
			$this->organizador->HrefValue = "";

			// nombreactividad
			$this->nombreactividad->HrefValue = "";

			// nombrelocal
			$this->nombrelocal->HrefValue = "";

			// direccionlocal
			$this->direccionlocal->HrefValue = "";

			// fecha_inicio
			$this->fecha_inicio->HrefValue = "";

			// fecha_fin
			$this->fecha_fin->HrefValue = "";

			// horasprogramadas
			$this->horasprogramadas->HrefValue = "";

			// perosnanombre
			$this->perosnanombre->HrefValue = "";

			// personaapellidomaterno
			$this->personaapellidomaterno->HrefValue = "";

			// personaapellidopaterno
			$this->personaapellidopaterno->HrefValue = "";

			// contenido
			$this->contenido->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// sector
			$CurrentValue = $this->sector->CurrentValue;
			$ViewValue = &$this->sector->ViewValue;
			$ViewAttrs = &$this->sector->ViewAttrs;
			$CellAttrs = &$this->sector->CellAttrs;
			$HrefValue = &$this->sector->HrefValue;
			$LinkAttrs = &$this->sector->LinkAttrs;
			$this->Cell_Rendered($this->sector, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipoactividad
			$CurrentValue = $this->tipoactividad->CurrentValue;
			$ViewValue = &$this->tipoactividad->ViewValue;
			$ViewAttrs = &$this->tipoactividad->ViewAttrs;
			$CellAttrs = &$this->tipoactividad->CellAttrs;
			$HrefValue = &$this->tipoactividad->HrefValue;
			$LinkAttrs = &$this->tipoactividad->LinkAttrs;
			$this->Cell_Rendered($this->tipoactividad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// organizador
			$CurrentValue = $this->organizador->CurrentValue;
			$ViewValue = &$this->organizador->ViewValue;
			$ViewAttrs = &$this->organizador->ViewAttrs;
			$CellAttrs = &$this->organizador->CellAttrs;
			$HrefValue = &$this->organizador->HrefValue;
			$LinkAttrs = &$this->organizador->LinkAttrs;
			$this->Cell_Rendered($this->organizador, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombreactividad
			$CurrentValue = $this->nombreactividad->CurrentValue;
			$ViewValue = &$this->nombreactividad->ViewValue;
			$ViewAttrs = &$this->nombreactividad->ViewAttrs;
			$CellAttrs = &$this->nombreactividad->CellAttrs;
			$HrefValue = &$this->nombreactividad->HrefValue;
			$LinkAttrs = &$this->nombreactividad->LinkAttrs;
			$this->Cell_Rendered($this->nombreactividad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombrelocal
			$CurrentValue = $this->nombrelocal->CurrentValue;
			$ViewValue = &$this->nombrelocal->ViewValue;
			$ViewAttrs = &$this->nombrelocal->ViewAttrs;
			$CellAttrs = &$this->nombrelocal->CellAttrs;
			$HrefValue = &$this->nombrelocal->HrefValue;
			$LinkAttrs = &$this->nombrelocal->LinkAttrs;
			$this->Cell_Rendered($this->nombrelocal, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// direccionlocal
			$CurrentValue = $this->direccionlocal->CurrentValue;
			$ViewValue = &$this->direccionlocal->ViewValue;
			$ViewAttrs = &$this->direccionlocal->ViewAttrs;
			$CellAttrs = &$this->direccionlocal->CellAttrs;
			$HrefValue = &$this->direccionlocal->HrefValue;
			$LinkAttrs = &$this->direccionlocal->LinkAttrs;
			$this->Cell_Rendered($this->direccionlocal, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fecha_inicio
			$CurrentValue = $this->fecha_inicio->CurrentValue;
			$ViewValue = &$this->fecha_inicio->ViewValue;
			$ViewAttrs = &$this->fecha_inicio->ViewAttrs;
			$CellAttrs = &$this->fecha_inicio->CellAttrs;
			$HrefValue = &$this->fecha_inicio->HrefValue;
			$LinkAttrs = &$this->fecha_inicio->LinkAttrs;
			$this->Cell_Rendered($this->fecha_inicio, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fecha_fin
			$CurrentValue = $this->fecha_fin->CurrentValue;
			$ViewValue = &$this->fecha_fin->ViewValue;
			$ViewAttrs = &$this->fecha_fin->ViewAttrs;
			$CellAttrs = &$this->fecha_fin->CellAttrs;
			$HrefValue = &$this->fecha_fin->HrefValue;
			$LinkAttrs = &$this->fecha_fin->LinkAttrs;
			$this->Cell_Rendered($this->fecha_fin, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// horasprogramadas
			$CurrentValue = $this->horasprogramadas->CurrentValue;
			$ViewValue = &$this->horasprogramadas->ViewValue;
			$ViewAttrs = &$this->horasprogramadas->ViewAttrs;
			$CellAttrs = &$this->horasprogramadas->CellAttrs;
			$HrefValue = &$this->horasprogramadas->HrefValue;
			$LinkAttrs = &$this->horasprogramadas->LinkAttrs;
			$this->Cell_Rendered($this->horasprogramadas, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// perosnanombre
			$CurrentValue = $this->perosnanombre->CurrentValue;
			$ViewValue = &$this->perosnanombre->ViewValue;
			$ViewAttrs = &$this->perosnanombre->ViewAttrs;
			$CellAttrs = &$this->perosnanombre->CellAttrs;
			$HrefValue = &$this->perosnanombre->HrefValue;
			$LinkAttrs = &$this->perosnanombre->LinkAttrs;
			$this->Cell_Rendered($this->perosnanombre, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// personaapellidomaterno
			$CurrentValue = $this->personaapellidomaterno->CurrentValue;
			$ViewValue = &$this->personaapellidomaterno->ViewValue;
			$ViewAttrs = &$this->personaapellidomaterno->ViewAttrs;
			$CellAttrs = &$this->personaapellidomaterno->CellAttrs;
			$HrefValue = &$this->personaapellidomaterno->HrefValue;
			$LinkAttrs = &$this->personaapellidomaterno->LinkAttrs;
			$this->Cell_Rendered($this->personaapellidomaterno, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// personaapellidopaterno
			$CurrentValue = $this->personaapellidopaterno->CurrentValue;
			$ViewValue = &$this->personaapellidopaterno->ViewValue;
			$ViewAttrs = &$this->personaapellidopaterno->ViewAttrs;
			$CellAttrs = &$this->personaapellidopaterno->CellAttrs;
			$HrefValue = &$this->personaapellidopaterno->HrefValue;
			$LinkAttrs = &$this->personaapellidopaterno->LinkAttrs;
			$this->Cell_Rendered($this->personaapellidopaterno, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// contenido
			$CurrentValue = $this->contenido->CurrentValue;
			$ViewValue = &$this->contenido->ViewValue;
			$ViewAttrs = &$this->contenido->ViewAttrs;
			$CellAttrs = &$this->contenido->CellAttrs;
			$HrefValue = &$this->contenido->HrefValue;
			$LinkAttrs = &$this->contenido->LinkAttrs;
			$this->Cell_Rendered($this->contenido, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// observaciones
			$CurrentValue = $this->observaciones->CurrentValue;
			$ViewValue = &$this->observaciones->ViewValue;
			$ViewAttrs = &$this->observaciones->ViewAttrs;
			$CellAttrs = &$this->observaciones->CellAttrs;
			$HrefValue = &$this->observaciones->HrefValue;
			$LinkAttrs = &$this->observaciones->LinkAttrs;
			$this->Cell_Rendered($this->observaciones, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombreinstitucion
			$CurrentValue = $this->nombreinstitucion->CurrentValue;
			$ViewValue = &$this->nombreinstitucion->ViewValue;
			$ViewAttrs = &$this->nombreinstitucion->ViewAttrs;
			$CellAttrs = &$this->nombreinstitucion->CellAttrs;
			$HrefValue = &$this->nombreinstitucion->HrefValue;
			$LinkAttrs = &$this->nombreinstitucion->LinkAttrs;
			$this->Cell_Rendered($this->nombreinstitucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->sector->Visible) $this->DtlColumnCount += 1;
		if ($this->tipoactividad->Visible) $this->DtlColumnCount += 1;
		if ($this->organizador->Visible) $this->DtlColumnCount += 1;
		if ($this->nombreactividad->Visible) $this->DtlColumnCount += 1;
		if ($this->nombrelocal->Visible) $this->DtlColumnCount += 1;
		if ($this->direccionlocal->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha_inicio->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha_fin->Visible) $this->DtlColumnCount += 1;
		if ($this->horasprogramadas->Visible) $this->DtlColumnCount += 1;
		if ($this->perosnanombre->Visible) $this->DtlColumnCount += 1;
		if ($this->personaapellidomaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->personaapellidopaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->contenido->Visible) $this->DtlColumnCount += 1;
		if ($this->observaciones->Visible) $this->DtlColumnCount += 1;
		if ($this->nombreinstitucion->Visible) $this->DtlColumnCount += 1;
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

			// Clear extended filter for field sector
			if ($this->ClearExtFilter == 'viewactividad_sector')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'sector');

			// Clear extended filter for field tipoactividad
			if ($this->ClearExtFilter == 'viewactividad_tipoactividad')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'tipoactividad');

			// Clear extended filter for field organizador
			if ($this->ClearExtFilter == 'viewactividad_organizador')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'organizador');

			// Clear extended filter for field fecha_inicio
			if ($this->ClearExtFilter == 'viewactividad_fecha_inicio')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'fecha_inicio');

			// Clear extended filter for field fecha_fin
			if ($this->ClearExtFilter == 'viewactividad_fecha_fin')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'fecha_fin');

			// Clear extended filter for field nombreinstitucion
			if ($this->ClearExtFilter == 'viewactividad_nombreinstitucion')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'nombreinstitucion');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionFilterValues($this->sector->SearchValue, $this->sector->SearchOperator, $this->sector->SearchCondition, $this->sector->SearchValue2, $this->sector->SearchOperator2, 'sector'); // Field sector
			$this->SetSessionFilterValues($this->tipoactividad->SearchValue, $this->tipoactividad->SearchOperator, $this->tipoactividad->SearchCondition, $this->tipoactividad->SearchValue2, $this->tipoactividad->SearchOperator2, 'tipoactividad'); // Field tipoactividad
			$this->SetSessionFilterValues($this->organizador->SearchValue, $this->organizador->SearchOperator, $this->organizador->SearchCondition, $this->organizador->SearchValue2, $this->organizador->SearchOperator2, 'organizador'); // Field organizador
			$this->SetSessionFilterValues($this->nombreactividad->SearchValue, $this->nombreactividad->SearchOperator, $this->nombreactividad->SearchCondition, $this->nombreactividad->SearchValue2, $this->nombreactividad->SearchOperator2, 'nombreactividad'); // Field nombreactividad
			$this->SetSessionFilterValues($this->fecha_inicio->SearchValue, $this->fecha_inicio->SearchOperator, $this->fecha_inicio->SearchCondition, $this->fecha_inicio->SearchValue2, $this->fecha_inicio->SearchOperator2, 'fecha_inicio'); // Field fecha_inicio
			$this->SetSessionFilterValues($this->fecha_fin->SearchValue, $this->fecha_fin->SearchOperator, $this->fecha_fin->SearchCondition, $this->fecha_fin->SearchValue2, $this->fecha_fin->SearchOperator2, 'fecha_fin'); // Field fecha_fin
			$this->SetSessionFilterValues($this->contenido->SearchValue, $this->contenido->SearchOperator, $this->contenido->SearchCondition, $this->contenido->SearchValue2, $this->contenido->SearchOperator2, 'contenido'); // Field contenido
			$this->SetSessionFilterValues($this->observaciones->SearchValue, $this->observaciones->SearchOperator, $this->observaciones->SearchCondition, $this->observaciones->SearchValue2, $this->observaciones->SearchOperator2, 'observaciones'); // Field observaciones
			$this->SetSessionFilterValues($this->nombreinstitucion->SearchValue, $this->nombreinstitucion->SearchOperator, $this->nombreinstitucion->SearchCondition, $this->nombreinstitucion->SearchValue2, $this->nombreinstitucion->SearchOperator2, 'nombreinstitucion'); // Field nombreinstitucion

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field sector
			if ($this->GetFilterValues($this->sector)) {
				$bSetupFilter = TRUE;
			}

			// Field tipoactividad
			if ($this->GetFilterValues($this->tipoactividad)) {
				$bSetupFilter = TRUE;
			}

			// Field organizador
			if ($this->GetFilterValues($this->organizador)) {
				$bSetupFilter = TRUE;
			}

			// Field nombreactividad
			if ($this->GetFilterValues($this->nombreactividad)) {
				$bSetupFilter = TRUE;
			}

			// Field fecha_inicio
			if ($this->GetFilterValues($this->fecha_inicio)) {
				$bSetupFilter = TRUE;
			}

			// Field fecha_fin
			if ($this->GetFilterValues($this->fecha_fin)) {
				$bSetupFilter = TRUE;
			}

			// Field contenido
			if ($this->GetFilterValues($this->contenido)) {
				$bSetupFilter = TRUE;
			}

			// Field observaciones
			if ($this->GetFilterValues($this->observaciones)) {
				$bSetupFilter = TRUE;
			}

			// Field nombreinstitucion
			if ($this->GetFilterValues($this->nombreinstitucion)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionFilterValues($this->sector); // Field sector
			$this->GetSessionFilterValues($this->tipoactividad); // Field tipoactividad
			$this->GetSessionFilterValues($this->organizador); // Field organizador
			$this->GetSessionFilterValues($this->nombreactividad); // Field nombreactividad
			$this->GetSessionFilterValues($this->fecha_inicio); // Field fecha_inicio
			$this->GetSessionFilterValues($this->fecha_fin); // Field fecha_fin
			$this->GetSessionFilterValues($this->contenido); // Field contenido
			$this->GetSessionFilterValues($this->observaciones); // Field observaciones
			$this->GetSessionFilterValues($this->nombreinstitucion); // Field nombreinstitucion
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildExtendedFilter($this->sector, $sFilter, FALSE, TRUE); // Field sector
		$this->BuildExtendedFilter($this->tipoactividad, $sFilter, FALSE, TRUE); // Field tipoactividad
		$this->BuildExtendedFilter($this->organizador, $sFilter, FALSE, TRUE); // Field organizador
		$this->BuildExtendedFilter($this->nombreactividad, $sFilter, FALSE, TRUE); // Field nombreactividad
		$this->BuildExtendedFilter($this->fecha_inicio, $sFilter, FALSE, TRUE); // Field fecha_inicio
		$this->BuildExtendedFilter($this->fecha_fin, $sFilter, FALSE, TRUE); // Field fecha_fin
		$this->BuildExtendedFilter($this->contenido, $sFilter, FALSE, TRUE); // Field contenido
		$this->BuildExtendedFilter($this->observaciones, $sFilter, FALSE, TRUE); // Field observaciones
		$this->BuildExtendedFilter($this->nombreinstitucion, $sFilter, FALSE, TRUE); // Field nombreinstitucion

		// Save parms to session
		$this->SetSessionFilterValues($this->sector->SearchValue, $this->sector->SearchOperator, $this->sector->SearchCondition, $this->sector->SearchValue2, $this->sector->SearchOperator2, 'sector'); // Field sector
		$this->SetSessionFilterValues($this->tipoactividad->SearchValue, $this->tipoactividad->SearchOperator, $this->tipoactividad->SearchCondition, $this->tipoactividad->SearchValue2, $this->tipoactividad->SearchOperator2, 'tipoactividad'); // Field tipoactividad
		$this->SetSessionFilterValues($this->organizador->SearchValue, $this->organizador->SearchOperator, $this->organizador->SearchCondition, $this->organizador->SearchValue2, $this->organizador->SearchOperator2, 'organizador'); // Field organizador
		$this->SetSessionFilterValues($this->nombreactividad->SearchValue, $this->nombreactividad->SearchOperator, $this->nombreactividad->SearchCondition, $this->nombreactividad->SearchValue2, $this->nombreactividad->SearchOperator2, 'nombreactividad'); // Field nombreactividad
		$this->SetSessionFilterValues($this->fecha_inicio->SearchValue, $this->fecha_inicio->SearchOperator, $this->fecha_inicio->SearchCondition, $this->fecha_inicio->SearchValue2, $this->fecha_inicio->SearchOperator2, 'fecha_inicio'); // Field fecha_inicio
		$this->SetSessionFilterValues($this->fecha_fin->SearchValue, $this->fecha_fin->SearchOperator, $this->fecha_fin->SearchCondition, $this->fecha_fin->SearchValue2, $this->fecha_fin->SearchOperator2, 'fecha_fin'); // Field fecha_fin
		$this->SetSessionFilterValues($this->contenido->SearchValue, $this->contenido->SearchOperator, $this->contenido->SearchCondition, $this->contenido->SearchValue2, $this->contenido->SearchOperator2, 'contenido'); // Field contenido
		$this->SetSessionFilterValues($this->observaciones->SearchValue, $this->observaciones->SearchOperator, $this->observaciones->SearchCondition, $this->observaciones->SearchValue2, $this->observaciones->SearchOperator2, 'observaciones'); // Field observaciones
		$this->SetSessionFilterValues($this->nombreinstitucion->SearchValue, $this->nombreinstitucion->SearchOperator, $this->nombreinstitucion->SearchCondition, $this->nombreinstitucion->SearchValue2, $this->nombreinstitucion->SearchOperator2, 'nombreinstitucion'); // Field nombreinstitucion

		// Setup filter
		if ($bSetupFilter) {

			// Field sector
			$sWrk = "";
			$this->BuildExtendedFilter($this->sector, $sWrk);
			ewr_LoadSelectionFromFilter($this->sector, $sWrk, $this->sector->SelectionList);
			$_SESSION['sel_viewactividad_sector'] = ($this->sector->SelectionList == "") ? EWR_INIT_VALUE : $this->sector->SelectionList;

			// Field tipoactividad
			$sWrk = "";
			$this->BuildExtendedFilter($this->tipoactividad, $sWrk);
			ewr_LoadSelectionFromFilter($this->tipoactividad, $sWrk, $this->tipoactividad->SelectionList);
			$_SESSION['sel_viewactividad_tipoactividad'] = ($this->tipoactividad->SelectionList == "") ? EWR_INIT_VALUE : $this->tipoactividad->SelectionList;

			// Field organizador
			$sWrk = "";
			$this->BuildExtendedFilter($this->organizador, $sWrk);
			ewr_LoadSelectionFromFilter($this->organizador, $sWrk, $this->organizador->SelectionList);
			$_SESSION['sel_viewactividad_organizador'] = ($this->organizador->SelectionList == "") ? EWR_INIT_VALUE : $this->organizador->SelectionList;

			// Field fecha_inicio
			$sWrk = "";
			$this->BuildExtendedFilter($this->fecha_inicio, $sWrk);
			ewr_LoadSelectionFromFilter($this->fecha_inicio, $sWrk, $this->fecha_inicio->SelectionList);
			$_SESSION['sel_viewactividad_fecha_inicio'] = ($this->fecha_inicio->SelectionList == "") ? EWR_INIT_VALUE : $this->fecha_inicio->SelectionList;

			// Field fecha_fin
			$sWrk = "";
			$this->BuildExtendedFilter($this->fecha_fin, $sWrk);
			ewr_LoadSelectionFromFilter($this->fecha_fin, $sWrk, $this->fecha_fin->SelectionList);
			$_SESSION['sel_viewactividad_fecha_fin'] = ($this->fecha_fin->SelectionList == "") ? EWR_INIT_VALUE : $this->fecha_fin->SelectionList;

			// Field nombreinstitucion
			$sWrk = "";
			$this->BuildExtendedFilter($this->nombreinstitucion, $sWrk);
			ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SelectionList);
			$_SESSION['sel_viewactividad_nombreinstitucion'] = ($this->nombreinstitucion->SelectionList == "") ? EWR_INIT_VALUE : $this->nombreinstitucion->SelectionList;
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewactividad_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewactividad_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewactividad_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewactividad_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewactividad_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewactividad_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewactividad_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewactividad_' . $parm] = $sv;
		$_SESSION['so_viewactividad_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewactividad_' . $parm] = $sv1;
		$_SESSION['so_viewactividad_' . $parm] = $so1;
		$_SESSION['sc_viewactividad_' . $parm] = $sc;
		$_SESSION['sv2_viewactividad_' . $parm] = $sv2;
		$_SESSION['so2_viewactividad_' . $parm] = $so2;
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
		if (!ewr_CheckInteger($this->organizador->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->organizador->FldErrMsg();
		}
		if (!ewr_CheckDateDef($this->fecha_inicio->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fecha_inicio->FldErrMsg();
		}
		if (!ewr_CheckDateDef($this->fecha_fin->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fecha_fin->FldErrMsg();
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
		$_SESSION["sel_viewactividad_$parm"] = "";
		$_SESSION["rf_viewactividad_$parm"] = "";
		$_SESSION["rt_viewactividad_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewactividad_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewactividad_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewactividad_$parm"];
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

		// Field sector
		$this->SetDefaultExtFilter($this->sector, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->sector);
		$sWrk = "";
		$this->BuildExtendedFilter($this->sector, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->sector, $sWrk, $this->sector->DefaultSelectionList);
		if (!$this->SearchCommand) $this->sector->SelectionList = $this->sector->DefaultSelectionList;

		// Field tipoactividad
		$this->SetDefaultExtFilter($this->tipoactividad, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->tipoactividad);
		$sWrk = "";
		$this->BuildExtendedFilter($this->tipoactividad, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->tipoactividad, $sWrk, $this->tipoactividad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->tipoactividad->SelectionList = $this->tipoactividad->DefaultSelectionList;

		// Field organizador
		$this->SetDefaultExtFilter($this->organizador, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->organizador);
		$sWrk = "";
		$this->BuildExtendedFilter($this->organizador, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->organizador, $sWrk, $this->organizador->DefaultSelectionList);
		if (!$this->SearchCommand) $this->organizador->SelectionList = $this->organizador->DefaultSelectionList;

		// Field nombreactividad
		$this->SetDefaultExtFilter($this->nombreactividad, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombreactividad);

		// Field fecha_inicio
		$this->SetDefaultExtFilter($this->fecha_inicio, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->fecha_inicio);
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha_inicio, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->fecha_inicio, $sWrk, $this->fecha_inicio->DefaultSelectionList);
		if (!$this->SearchCommand) $this->fecha_inicio->SelectionList = $this->fecha_inicio->DefaultSelectionList;

		// Field fecha_fin
		$this->SetDefaultExtFilter($this->fecha_fin, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->fecha_fin);
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha_fin, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->fecha_fin, $sWrk, $this->fecha_fin->DefaultSelectionList);
		if (!$this->SearchCommand) $this->fecha_fin->SelectionList = $this->fecha_fin->DefaultSelectionList;

		// Field contenido
		$this->SetDefaultExtFilter($this->contenido, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->contenido);

		// Field observaciones
		$this->SetDefaultExtFilter($this->observaciones, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->observaciones);

		// Field nombreinstitucion
		$this->SetDefaultExtFilter($this->nombreinstitucion, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombreinstitucion);
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreinstitucion, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombreinstitucion->SelectionList = $this->nombreinstitucion->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field sector
		// $this->sector->DefaultSelectionList = array("val1", "val2");
		// Field tipoactividad
		// $this->tipoactividad->DefaultSelectionList = array("val1", "val2");
		// Field organizador
		// $this->organizador->DefaultSelectionList = array("val1", "val2");
		// Field fecha_inicio
		// $this->fecha_inicio->DefaultSelectionList = array("val1", "val2");
		// Field fecha_fin
		// $this->fecha_fin->DefaultSelectionList = array("val1", "val2");
		// Field nombreinstitucion
		// $this->nombreinstitucion->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check sector text filter
		if ($this->TextFilterApplied($this->sector))
			return TRUE;

		// Check sector popup filter
		if (!ewr_MatchedArray($this->sector->DefaultSelectionList, $this->sector->SelectionList))
			return TRUE;

		// Check tipoactividad text filter
		if ($this->TextFilterApplied($this->tipoactividad))
			return TRUE;

		// Check tipoactividad popup filter
		if (!ewr_MatchedArray($this->tipoactividad->DefaultSelectionList, $this->tipoactividad->SelectionList))
			return TRUE;

		// Check organizador text filter
		if ($this->TextFilterApplied($this->organizador))
			return TRUE;

		// Check organizador popup filter
		if (!ewr_MatchedArray($this->organizador->DefaultSelectionList, $this->organizador->SelectionList))
			return TRUE;

		// Check nombreactividad text filter
		if ($this->TextFilterApplied($this->nombreactividad))
			return TRUE;

		// Check fecha_inicio text filter
		if ($this->TextFilterApplied($this->fecha_inicio))
			return TRUE;

		// Check fecha_inicio popup filter
		if (!ewr_MatchedArray($this->fecha_inicio->DefaultSelectionList, $this->fecha_inicio->SelectionList))
			return TRUE;

		// Check fecha_fin text filter
		if ($this->TextFilterApplied($this->fecha_fin))
			return TRUE;

		// Check fecha_fin popup filter
		if (!ewr_MatchedArray($this->fecha_fin->DefaultSelectionList, $this->fecha_fin->SelectionList))
			return TRUE;

		// Check contenido text filter
		if ($this->TextFilterApplied($this->contenido))
			return TRUE;

		// Check observaciones text filter
		if ($this->TextFilterApplied($this->observaciones))
			return TRUE;

		// Check nombreinstitucion text filter
		if ($this->TextFilterApplied($this->nombreinstitucion))
			return TRUE;

		// Check nombreinstitucion popup filter
		if (!ewr_MatchedArray($this->nombreinstitucion->DefaultSelectionList, $this->nombreinstitucion->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field sector
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->sector, $sExtWrk);
		if (is_array($this->sector->SelectionList))
			$sWrk = ewr_JoinArray($this->sector->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->sector->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field tipoactividad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->tipoactividad, $sExtWrk);
		if (is_array($this->tipoactividad->SelectionList))
			$sWrk = ewr_JoinArray($this->tipoactividad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tipoactividad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field organizador
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->organizador, $sExtWrk);
		if (is_array($this->organizador->SelectionList))
			$sWrk = ewr_JoinArray($this->organizador->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->organizador->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombreactividad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreactividad, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreactividad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field fecha_inicio
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha_inicio, $sExtWrk);
		if (is_array($this->fecha_inicio->SelectionList))
			$sWrk = ewr_JoinArray($this->fecha_inicio->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fecha_inicio->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field fecha_fin
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->fecha_fin, $sExtWrk);
		if (is_array($this->fecha_fin->SelectionList))
			$sWrk = ewr_JoinArray($this->fecha_fin->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fecha_fin->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field contenido
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->contenido, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->contenido->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field observaciones
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->observaciones, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->observaciones->FldCaption() . "</span>" . $sFilter . "</div>";

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

		// Field sector
		$sWrk = "";
		if ($this->sector->SearchValue <> "" || $this->sector->SearchValue2 <> "") {
			$sWrk = "\"sv_sector\":\"" . ewr_JsEncode2($this->sector->SearchValue) . "\"," .
				"\"so_sector\":\"" . ewr_JsEncode2($this->sector->SearchOperator) . "\"," .
				"\"sc_sector\":\"" . ewr_JsEncode2($this->sector->SearchCondition) . "\"," .
				"\"sv2_sector\":\"" . ewr_JsEncode2($this->sector->SearchValue2) . "\"," .
				"\"so2_sector\":\"" . ewr_JsEncode2($this->sector->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->sector->SelectionList <> EWR_INIT_VALUE) ? $this->sector->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_sector\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field tipoactividad
		$sWrk = "";
		if ($this->tipoactividad->SearchValue <> "" || $this->tipoactividad->SearchValue2 <> "") {
			$sWrk = "\"sv_tipoactividad\":\"" . ewr_JsEncode2($this->tipoactividad->SearchValue) . "\"," .
				"\"so_tipoactividad\":\"" . ewr_JsEncode2($this->tipoactividad->SearchOperator) . "\"," .
				"\"sc_tipoactividad\":\"" . ewr_JsEncode2($this->tipoactividad->SearchCondition) . "\"," .
				"\"sv2_tipoactividad\":\"" . ewr_JsEncode2($this->tipoactividad->SearchValue2) . "\"," .
				"\"so2_tipoactividad\":\"" . ewr_JsEncode2($this->tipoactividad->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->tipoactividad->SelectionList <> EWR_INIT_VALUE) ? $this->tipoactividad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_tipoactividad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field organizador
		$sWrk = "";
		if ($this->organizador->SearchValue <> "" || $this->organizador->SearchValue2 <> "") {
			$sWrk = "\"sv_organizador\":\"" . ewr_JsEncode2($this->organizador->SearchValue) . "\"," .
				"\"so_organizador\":\"" . ewr_JsEncode2($this->organizador->SearchOperator) . "\"," .
				"\"sc_organizador\":\"" . ewr_JsEncode2($this->organizador->SearchCondition) . "\"," .
				"\"sv2_organizador\":\"" . ewr_JsEncode2($this->organizador->SearchValue2) . "\"," .
				"\"so2_organizador\":\"" . ewr_JsEncode2($this->organizador->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->organizador->SelectionList <> EWR_INIT_VALUE) ? $this->organizador->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_organizador\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombreactividad
		$sWrk = "";
		if ($this->nombreactividad->SearchValue <> "" || $this->nombreactividad->SearchValue2 <> "") {
			$sWrk = "\"sv_nombreactividad\":\"" . ewr_JsEncode2($this->nombreactividad->SearchValue) . "\"," .
				"\"so_nombreactividad\":\"" . ewr_JsEncode2($this->nombreactividad->SearchOperator) . "\"," .
				"\"sc_nombreactividad\":\"" . ewr_JsEncode2($this->nombreactividad->SearchCondition) . "\"," .
				"\"sv2_nombreactividad\":\"" . ewr_JsEncode2($this->nombreactividad->SearchValue2) . "\"," .
				"\"so2_nombreactividad\":\"" . ewr_JsEncode2($this->nombreactividad->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field fecha_inicio
		$sWrk = "";
		if ($this->fecha_inicio->SearchValue <> "" || $this->fecha_inicio->SearchValue2 <> "") {
			$sWrk = "\"sv_fecha_inicio\":\"" . ewr_JsEncode2($this->fecha_inicio->SearchValue) . "\"," .
				"\"so_fecha_inicio\":\"" . ewr_JsEncode2($this->fecha_inicio->SearchOperator) . "\"," .
				"\"sc_fecha_inicio\":\"" . ewr_JsEncode2($this->fecha_inicio->SearchCondition) . "\"," .
				"\"sv2_fecha_inicio\":\"" . ewr_JsEncode2($this->fecha_inicio->SearchValue2) . "\"," .
				"\"so2_fecha_inicio\":\"" . ewr_JsEncode2($this->fecha_inicio->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->fecha_inicio->SelectionList <> EWR_INIT_VALUE) ? $this->fecha_inicio->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_fecha_inicio\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field fecha_fin
		$sWrk = "";
		if ($this->fecha_fin->SearchValue <> "" || $this->fecha_fin->SearchValue2 <> "") {
			$sWrk = "\"sv_fecha_fin\":\"" . ewr_JsEncode2($this->fecha_fin->SearchValue) . "\"," .
				"\"so_fecha_fin\":\"" . ewr_JsEncode2($this->fecha_fin->SearchOperator) . "\"," .
				"\"sc_fecha_fin\":\"" . ewr_JsEncode2($this->fecha_fin->SearchCondition) . "\"," .
				"\"sv2_fecha_fin\":\"" . ewr_JsEncode2($this->fecha_fin->SearchValue2) . "\"," .
				"\"so2_fecha_fin\":\"" . ewr_JsEncode2($this->fecha_fin->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->fecha_fin->SelectionList <> EWR_INIT_VALUE) ? $this->fecha_fin->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_fecha_fin\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field contenido
		$sWrk = "";
		if ($this->contenido->SearchValue <> "" || $this->contenido->SearchValue2 <> "") {
			$sWrk = "\"sv_contenido\":\"" . ewr_JsEncode2($this->contenido->SearchValue) . "\"," .
				"\"so_contenido\":\"" . ewr_JsEncode2($this->contenido->SearchOperator) . "\"," .
				"\"sc_contenido\":\"" . ewr_JsEncode2($this->contenido->SearchCondition) . "\"," .
				"\"sv2_contenido\":\"" . ewr_JsEncode2($this->contenido->SearchValue2) . "\"," .
				"\"so2_contenido\":\"" . ewr_JsEncode2($this->contenido->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field observaciones
		$sWrk = "";
		if ($this->observaciones->SearchValue <> "" || $this->observaciones->SearchValue2 <> "") {
			$sWrk = "\"sv_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchValue) . "\"," .
				"\"so_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchOperator) . "\"," .
				"\"sc_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchCondition) . "\"," .
				"\"sv2_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchValue2) . "\"," .
				"\"so2_observaciones\":\"" . ewr_JsEncode2($this->observaciones->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

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

		// Field sector
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_sector", $filter) || array_key_exists("so_sector", $filter) ||
			array_key_exists("sc_sector", $filter) ||
			array_key_exists("sv2_sector", $filter) || array_key_exists("so2_sector", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_sector"], @$filter["so_sector"], @$filter["sc_sector"], @$filter["sv2_sector"], @$filter["so2_sector"], "sector");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_sector", $filter)) {
			$sWrk = $filter["sel_sector"];
			$sWrk = explode("||", $sWrk);
			$this->sector->SelectionList = $sWrk;
			$_SESSION["sel_viewactividad_sector"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "sector"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "sector");
			$this->sector->SelectionList = "";
			$_SESSION["sel_viewactividad_sector"] = "";
		}

		// Field tipoactividad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_tipoactividad", $filter) || array_key_exists("so_tipoactividad", $filter) ||
			array_key_exists("sc_tipoactividad", $filter) ||
			array_key_exists("sv2_tipoactividad", $filter) || array_key_exists("so2_tipoactividad", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_tipoactividad"], @$filter["so_tipoactividad"], @$filter["sc_tipoactividad"], @$filter["sv2_tipoactividad"], @$filter["so2_tipoactividad"], "tipoactividad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_tipoactividad", $filter)) {
			$sWrk = $filter["sel_tipoactividad"];
			$sWrk = explode("||", $sWrk);
			$this->tipoactividad->SelectionList = $sWrk;
			$_SESSION["sel_viewactividad_tipoactividad"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "tipoactividad"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "tipoactividad");
			$this->tipoactividad->SelectionList = "";
			$_SESSION["sel_viewactividad_tipoactividad"] = "";
		}

		// Field organizador
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_organizador", $filter) || array_key_exists("so_organizador", $filter) ||
			array_key_exists("sc_organizador", $filter) ||
			array_key_exists("sv2_organizador", $filter) || array_key_exists("so2_organizador", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_organizador"], @$filter["so_organizador"], @$filter["sc_organizador"], @$filter["sv2_organizador"], @$filter["so2_organizador"], "organizador");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_organizador", $filter)) {
			$sWrk = $filter["sel_organizador"];
			$sWrk = explode("||", $sWrk);
			$this->organizador->SelectionList = $sWrk;
			$_SESSION["sel_viewactividad_organizador"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "organizador"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "organizador");
			$this->organizador->SelectionList = "";
			$_SESSION["sel_viewactividad_organizador"] = "";
		}

		// Field nombreactividad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombreactividad", $filter) || array_key_exists("so_nombreactividad", $filter) ||
			array_key_exists("sc_nombreactividad", $filter) ||
			array_key_exists("sv2_nombreactividad", $filter) || array_key_exists("so2_nombreactividad", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombreactividad"], @$filter["so_nombreactividad"], @$filter["sc_nombreactividad"], @$filter["sv2_nombreactividad"], @$filter["so2_nombreactividad"], "nombreactividad");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreactividad");
		}

		// Field fecha_inicio
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_fecha_inicio", $filter) || array_key_exists("so_fecha_inicio", $filter) ||
			array_key_exists("sc_fecha_inicio", $filter) ||
			array_key_exists("sv2_fecha_inicio", $filter) || array_key_exists("so2_fecha_inicio", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_fecha_inicio"], @$filter["so_fecha_inicio"], @$filter["sc_fecha_inicio"], @$filter["sv2_fecha_inicio"], @$filter["so2_fecha_inicio"], "fecha_inicio");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_fecha_inicio", $filter)) {
			$sWrk = $filter["sel_fecha_inicio"];
			$sWrk = explode("||", $sWrk);
			$this->fecha_inicio->SelectionList = $sWrk;
			$_SESSION["sel_viewactividad_fecha_inicio"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha_inicio"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha_inicio");
			$this->fecha_inicio->SelectionList = "";
			$_SESSION["sel_viewactividad_fecha_inicio"] = "";
		}

		// Field fecha_fin
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_fecha_fin", $filter) || array_key_exists("so_fecha_fin", $filter) ||
			array_key_exists("sc_fecha_fin", $filter) ||
			array_key_exists("sv2_fecha_fin", $filter) || array_key_exists("so2_fecha_fin", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_fecha_fin"], @$filter["so_fecha_fin"], @$filter["sc_fecha_fin"], @$filter["sv2_fecha_fin"], @$filter["so2_fecha_fin"], "fecha_fin");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_fecha_fin", $filter)) {
			$sWrk = $filter["sel_fecha_fin"];
			$sWrk = explode("||", $sWrk);
			$this->fecha_fin->SelectionList = $sWrk;
			$_SESSION["sel_viewactividad_fecha_fin"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha_fin"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fecha_fin");
			$this->fecha_fin->SelectionList = "";
			$_SESSION["sel_viewactividad_fecha_fin"] = "";
		}

		// Field contenido
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_contenido", $filter) || array_key_exists("so_contenido", $filter) ||
			array_key_exists("sc_contenido", $filter) ||
			array_key_exists("sv2_contenido", $filter) || array_key_exists("so2_contenido", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_contenido"], @$filter["so_contenido"], @$filter["sc_contenido"], @$filter["sv2_contenido"], @$filter["so2_contenido"], "contenido");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "contenido");
		}

		// Field observaciones
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_observaciones", $filter) || array_key_exists("so_observaciones", $filter) ||
			array_key_exists("sc_observaciones", $filter) ||
			array_key_exists("sv2_observaciones", $filter) || array_key_exists("so2_observaciones", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_observaciones"], @$filter["so_observaciones"], @$filter["sc_observaciones"], @$filter["sv2_observaciones"], @$filter["so2_observaciones"], "observaciones");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "observaciones");
		}

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
			$_SESSION["sel_viewactividad_nombreinstitucion"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreinstitucion"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreinstitucion");
			$this->nombreinstitucion->SelectionList = "";
			$_SESSION["sel_viewactividad_nombreinstitucion"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->ExtendedFilterExist($this->sector)) {
			if (is_array($this->sector->SelectionList)) {
				$sFilter = ewr_FilterSql($this->sector, "`sector`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->sector, $sFilter, "popup");
				$this->sector->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->tipoactividad)) {
			if (is_array($this->tipoactividad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->tipoactividad, "`tipoactividad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->tipoactividad, $sFilter, "popup");
				$this->tipoactividad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->organizador)) {
			if (is_array($this->organizador->SelectionList)) {
				$sFilter = ewr_FilterSql($this->organizador, "`organizador`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->organizador, $sFilter, "popup");
				$this->organizador->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->fecha_inicio)) {
			if (is_array($this->fecha_inicio->SelectionList)) {
				$sFilter = ewr_FilterSql($this->fecha_inicio, "`fecha_inicio`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha_inicio, $sFilter, "popup");
				$this->fecha_inicio->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->fecha_fin)) {
			if (is_array($this->fecha_fin->SelectionList)) {
				$sFilter = ewr_FilterSql($this->fecha_fin, "`fecha_fin`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->fecha_fin, $sFilter, "popup");
				$this->fecha_fin->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->nombreinstitucion)) {
			if (is_array($this->nombreinstitucion->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombreinstitucion, "`nombreinstitucion`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreinstitucion, $sFilter, "popup");
				$this->nombreinstitucion->CurrentFilter = $sFilter;
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
			$sql = @$post["sector"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@sector", "`sector`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->sector->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["tipoactividad"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@tipoactividad", "`tipoactividad`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tipoactividad->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["fecha_inicio"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@fecha_inicio", "`fecha_inicio`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fecha_inicio->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["fecha_fin"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@fecha_fin", "`fecha_fin`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fecha_fin->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
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
			$_SESSION['do_viewactividad'] = $opt;
			$_SESSION['df_viewactividad'] = $filter;
			$_SESSION['dl_viewactividad'] = $sFilterList;
		} elseif (@$_GET["cmd"] == "resetdrilldown") { // Clear drill down
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_" . EWR_TABLE_MASTER_TABLE] = "";
			$_SESSION['do_viewactividad'] = "";
			$_SESSION['df_viewactividad'] = "";
			$_SESSION['dl_viewactividad'] = "";
		} else { // Restore from Session
			$opt = @$_SESSION['do_viewactividad'];
			$filter = @$_SESSION['df_viewactividad'];
			$sFilterList = @$_SESSION['dl_viewactividad'];
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
			$this->sector->setSort("");
			$this->tipoactividad->setSort("");
			$this->organizador->setSort("");
			$this->nombreactividad->setSort("");
			$this->nombrelocal->setSort("");
			$this->direccionlocal->setSort("");
			$this->fecha_inicio->setSort("");
			$this->fecha_fin->setSort("");
			$this->horasprogramadas->setSort("");
			$this->perosnanombre->setSort("");
			$this->personaapellidomaterno->setSort("");
			$this->personaapellidopaterno->setSort("");
			$this->contenido->setSort("");
			$this->observaciones->setSort("");
			$this->nombreinstitucion->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->sector); // sector
			$this->UpdateSort($this->tipoactividad); // tipoactividad
			$this->UpdateSort($this->organizador); // organizador
			$this->UpdateSort($this->nombreactividad); // nombreactividad
			$this->UpdateSort($this->nombrelocal); // nombrelocal
			$this->UpdateSort($this->direccionlocal); // direccionlocal
			$this->UpdateSort($this->fecha_inicio); // fecha_inicio
			$this->UpdateSort($this->fecha_fin); // fecha_fin
			$this->UpdateSort($this->horasprogramadas); // horasprogramadas
			$this->UpdateSort($this->perosnanombre); // perosnanombre
			$this->UpdateSort($this->personaapellidomaterno); // personaapellidomaterno
			$this->UpdateSort($this->personaapellidopaterno); // personaapellidopaterno
			$this->UpdateSort($this->contenido); // contenido
			$this->UpdateSort($this->observaciones); // observaciones
			$this->UpdateSort($this->nombreinstitucion); // nombreinstitucion
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
if (!isset($viewactividad_rpt)) $viewactividad_rpt = new crviewactividad_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewactividad_rpt;

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
var viewactividad_rpt = new ewr_Page("viewactividad_rpt");

// Page properties
viewactividad_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewactividad_rpt.PageID;
</script>
<?php if (!$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewactividadrpt = new ewr_Form("fviewactividadrpt");

// Validate method
fviewactividadrpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_organizador;
	if (elm && !ewr_CheckInteger(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->organizador->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv_fecha_inicio;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fecha_inicio->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv_fecha_fin;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fecha_fin->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewactividadrpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewactividadrpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewactividadrpt.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
</script>
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
<?php if (!$Page->DrillDown && !$grDashboardReport) { ?>
<!-- Search form (begin) -->
<form name="fviewactividadrpt" id="fviewactividadrpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewactividadrpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_sector" class="ewCell form-group">
	<label for="sv_sector" class="ewSearchCaption ewLabel"><?php echo $Page->sector->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_sector" id="so_sector" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->sector->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_sector" id="sv_sector" name="sv_sector" size="30" maxlength="100" placeholder="<?php echo $Page->sector->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->sector->SearchValue) ?>"<?php echo $Page->sector->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_tipoactividad" class="ewCell form-group">
	<label for="sv_tipoactividad" class="ewSearchCaption ewLabel"><?php echo $Page->tipoactividad->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_tipoactividad" id="so_tipoactividad" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->tipoactividad->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_tipoactividad" id="sv_tipoactividad" name="sv_tipoactividad" size="30" maxlength="100" placeholder="<?php echo $Page->tipoactividad->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->tipoactividad->SearchValue) ?>"<?php echo $Page->tipoactividad->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_3" class="ewRow">
<div id="c_organizador" class="ewCell form-group">
	<label for="sv_organizador" class="ewSearchCaption ewLabel"><?php echo $Page->organizador->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_organizador" id="so_organizador" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->organizador->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_organizador" id="sv_organizador" name="sv_organizador" size="30" placeholder="<?php echo $Page->organizador->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->organizador->SearchValue) ?>"<?php echo $Page->organizador->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_4" class="ewRow">
<div id="c_nombreactividad" class="ewCell form-group">
	<label for="sv_nombreactividad" class="ewSearchCaption ewLabel"><?php echo $Page->nombreactividad->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombreactividad" id="so_nombreactividad" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombreactividad->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_nombreactividad" id="sv_nombreactividad" name="sv_nombreactividad" size="30" maxlength="100" placeholder="<?php echo $Page->nombreactividad->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombreactividad->SearchValue) ?>"<?php echo $Page->nombreactividad->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_5" class="ewRow">
<div id="c_fecha_inicio" class="ewCell form-group">
	<label for="sv_fecha_inicio" class="ewSearchCaption ewLabel"><?php echo $Page->fecha_inicio->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_fecha_inicio" id="so_fecha_inicio" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fecha_inicio->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_fecha_inicio" id="sv_fecha_inicio" name="sv_fecha_inicio" placeholder="<?php echo $Page->fecha_inicio->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha_inicio->SearchValue) ?>"<?php echo $Page->fecha_inicio->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_6" class="ewRow">
<div id="c_fecha_fin" class="ewCell form-group">
	<label for="sv_fecha_fin" class="ewSearchCaption ewLabel"><?php echo $Page->fecha_fin->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_fecha_fin" id="so_fecha_fin" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fecha_fin->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_fecha_fin" id="sv_fecha_fin" name="sv_fecha_fin" placeholder="<?php echo $Page->fecha_fin->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fecha_fin->SearchValue) ?>"<?php echo $Page->fecha_fin->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_7" class="ewRow">
<div id="c_contenido" class="ewCell form-group">
	<label for="sv_contenido" class="ewSearchCaption ewLabel"><?php echo $Page->contenido->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_contenido" id="so_contenido" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->contenido->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_contenido" id="sv_contenido" name="sv_contenido" size="30" maxlength="100" placeholder="<?php echo $Page->contenido->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->contenido->SearchValue) ?>"<?php echo $Page->contenido->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_8" class="ewRow">
<div id="c_observaciones" class="ewCell form-group">
	<label for="sv_observaciones" class="ewSearchCaption ewLabel"><?php echo $Page->observaciones->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_observaciones" id="so_observaciones" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->observaciones->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_observaciones" id="sv_observaciones" name="sv_observaciones" size="30" maxlength="100" placeholder="<?php echo $Page->observaciones->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->observaciones->SearchValue) ?>"<?php echo $Page->observaciones->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_9" class="ewRow">
<div id="c_nombreinstitucion" class="ewCell form-group">
	<label for="sv_nombreinstitucion" class="ewSearchCaption ewLabel"><?php echo $Page->nombreinstitucion->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombreinstitucion" id="so_nombreinstitucion" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombreinstitucion->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewactividad" data-field="x_nombreinstitucion" id="sv_nombreinstitucion" name="sv_nombreinstitucion" size="30" maxlength="100" placeholder="<?php echo $Page->nombreinstitucion->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombreinstitucion->SearchValue) ?>"<?php echo $Page->nombreinstitucion->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewactividadrpt.Init();
fviewactividadrpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_viewactividad" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->sector->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sector"><div class="viewactividad_sector"><span class="ewTableHeaderCaption"><?php echo $Page->sector->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sector">
<?php if ($Page->SortUrl($Page->sector) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_sector">
			<span class="ewTableHeaderCaption"><?php echo $Page->sector->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_sector', range: false, from: '<?php echo $Page->sector->RangeFrom; ?>', to: '<?php echo $Page->sector->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_sector<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_sector" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sector) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->sector->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_sector', range: false, from: '<?php echo $Page->sector->RangeFrom; ?>', to: '<?php echo $Page->sector->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_sector<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipoactividad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipoactividad"><div class="viewactividad_tipoactividad"><span class="ewTableHeaderCaption"><?php echo $Page->tipoactividad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipoactividad">
<?php if ($Page->SortUrl($Page->tipoactividad) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_tipoactividad">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipoactividad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_tipoactividad', range: false, from: '<?php echo $Page->tipoactividad->RangeFrom; ?>', to: '<?php echo $Page->tipoactividad->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_tipoactividad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_tipoactividad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipoactividad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipoactividad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipoactividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipoactividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_tipoactividad', range: false, from: '<?php echo $Page->tipoactividad->RangeFrom; ?>', to: '<?php echo $Page->tipoactividad->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_tipoactividad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->organizador->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="organizador"><div class="viewactividad_organizador"><span class="ewTableHeaderCaption"><?php echo $Page->organizador->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="organizador">
<?php if ($Page->SortUrl($Page->organizador) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_organizador">
			<span class="ewTableHeaderCaption"><?php echo $Page->organizador->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_organizador', range: false, from: '<?php echo $Page->organizador->RangeFrom; ?>', to: '<?php echo $Page->organizador->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_organizador<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_organizador" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->organizador) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->organizador->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->organizador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->organizador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_organizador', range: false, from: '<?php echo $Page->organizador->RangeFrom; ?>', to: '<?php echo $Page->organizador->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_organizador<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreactividad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreactividad"><div class="viewactividad_nombreactividad"><span class="ewTableHeaderCaption"><?php echo $Page->nombreactividad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreactividad">
<?php if ($Page->SortUrl($Page->nombreactividad) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_nombreactividad">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreactividad->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_nombreactividad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreactividad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreactividad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreactividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreactividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombrelocal->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombrelocal"><div class="viewactividad_nombrelocal"><span class="ewTableHeaderCaption"><?php echo $Page->nombrelocal->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombrelocal">
<?php if ($Page->SortUrl($Page->nombrelocal) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_nombrelocal">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrelocal->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_nombrelocal" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombrelocal) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombrelocal->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombrelocal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombrelocal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->direccionlocal->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="direccionlocal"><div class="viewactividad_direccionlocal"><span class="ewTableHeaderCaption"><?php echo $Page->direccionlocal->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="direccionlocal">
<?php if ($Page->SortUrl($Page->direccionlocal) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_direccionlocal">
			<span class="ewTableHeaderCaption"><?php echo $Page->direccionlocal->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_direccionlocal" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->direccionlocal) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->direccionlocal->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->direccionlocal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->direccionlocal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha_inicio->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_inicio"><div class="viewactividad_fecha_inicio"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_inicio->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_inicio">
<?php if ($Page->SortUrl($Page->fecha_inicio) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_fecha_inicio">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_inicio->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_fecha_inicio', range: false, from: '<?php echo $Page->fecha_inicio->RangeFrom; ?>', to: '<?php echo $Page->fecha_inicio->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_fecha_inicio<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_fecha_inicio" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_inicio) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_inicio->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_fecha_inicio', range: false, from: '<?php echo $Page->fecha_inicio->RangeFrom; ?>', to: '<?php echo $Page->fecha_inicio->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_fecha_inicio<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha_fin->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_fin"><div class="viewactividad_fecha_fin"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_fin->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_fin">
<?php if ($Page->SortUrl($Page->fecha_fin) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_fecha_fin">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_fin->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_fecha_fin', range: false, from: '<?php echo $Page->fecha_fin->RangeFrom; ?>', to: '<?php echo $Page->fecha_fin->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_fecha_fin<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_fecha_fin" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_fin) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_fin->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_fin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_fin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_fecha_fin', range: false, from: '<?php echo $Page->fecha_fin->RangeFrom; ?>', to: '<?php echo $Page->fecha_fin->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_fecha_fin<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->horasprogramadas->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="horasprogramadas"><div class="viewactividad_horasprogramadas"><span class="ewTableHeaderCaption"><?php echo $Page->horasprogramadas->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="horasprogramadas">
<?php if ($Page->SortUrl($Page->horasprogramadas) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_horasprogramadas">
			<span class="ewTableHeaderCaption"><?php echo $Page->horasprogramadas->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_horasprogramadas" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->horasprogramadas) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->horasprogramadas->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->horasprogramadas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->horasprogramadas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->perosnanombre->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="perosnanombre"><div class="viewactividad_perosnanombre"><span class="ewTableHeaderCaption"><?php echo $Page->perosnanombre->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="perosnanombre">
<?php if ($Page->SortUrl($Page->perosnanombre) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_perosnanombre">
			<span class="ewTableHeaderCaption"><?php echo $Page->perosnanombre->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_perosnanombre" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->perosnanombre) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->perosnanombre->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->perosnanombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->perosnanombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->personaapellidomaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="personaapellidomaterno"><div class="viewactividad_personaapellidomaterno"><span class="ewTableHeaderCaption"><?php echo $Page->personaapellidomaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="personaapellidomaterno">
<?php if ($Page->SortUrl($Page->personaapellidomaterno) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_personaapellidomaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->personaapellidomaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_personaapellidomaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->personaapellidomaterno) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->personaapellidomaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->personaapellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->personaapellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->personaapellidopaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="personaapellidopaterno"><div class="viewactividad_personaapellidopaterno"><span class="ewTableHeaderCaption"><?php echo $Page->personaapellidopaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="personaapellidopaterno">
<?php if ($Page->SortUrl($Page->personaapellidopaterno) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_personaapellidopaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->personaapellidopaterno->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_personaapellidopaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->personaapellidopaterno) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->personaapellidopaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->personaapellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->personaapellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->contenido->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="contenido"><div class="viewactividad_contenido"><span class="ewTableHeaderCaption"><?php echo $Page->contenido->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="contenido">
<?php if ($Page->SortUrl($Page->contenido) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_contenido">
			<span class="ewTableHeaderCaption"><?php echo $Page->contenido->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_contenido" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->contenido) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->contenido->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->contenido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->contenido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="observaciones"><div class="viewactividad_observaciones"><span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="observaciones">
<?php if ($Page->SortUrl($Page->observaciones) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_observaciones">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_observaciones" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->observaciones) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->observaciones->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreinstitucion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="viewactividad_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn viewactividad_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewactividad_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewactividad_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewactividadrpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
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
<?php if ($Page->sector->Visible) { ?>
		<td data-field="sector"<?php echo $Page->sector->CellAttributes() ?>>
<span<?php echo $Page->sector->ViewAttributes() ?>><?php echo $Page->sector->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipoactividad->Visible) { ?>
		<td data-field="tipoactividad"<?php echo $Page->tipoactividad->CellAttributes() ?>>
<span<?php echo $Page->tipoactividad->ViewAttributes() ?>><?php echo $Page->tipoactividad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->organizador->Visible) { ?>
		<td data-field="organizador"<?php echo $Page->organizador->CellAttributes() ?>>
<span<?php echo $Page->organizador->ViewAttributes() ?>><?php echo $Page->organizador->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombreactividad->Visible) { ?>
		<td data-field="nombreactividad"<?php echo $Page->nombreactividad->CellAttributes() ?>>
<span<?php echo $Page->nombreactividad->ViewAttributes() ?>><?php echo $Page->nombreactividad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombrelocal->Visible) { ?>
		<td data-field="nombrelocal"<?php echo $Page->nombrelocal->CellAttributes() ?>>
<span<?php echo $Page->nombrelocal->ViewAttributes() ?>><?php echo $Page->nombrelocal->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->direccionlocal->Visible) { ?>
		<td data-field="direccionlocal"<?php echo $Page->direccionlocal->CellAttributes() ?>>
<span<?php echo $Page->direccionlocal->ViewAttributes() ?>><?php echo $Page->direccionlocal->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fecha_inicio->Visible) { ?>
		<td data-field="fecha_inicio"<?php echo $Page->fecha_inicio->CellAttributes() ?>>
<span<?php echo $Page->fecha_inicio->ViewAttributes() ?>><?php echo $Page->fecha_inicio->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fecha_fin->Visible) { ?>
		<td data-field="fecha_fin"<?php echo $Page->fecha_fin->CellAttributes() ?>>
<span<?php echo $Page->fecha_fin->ViewAttributes() ?>><?php echo $Page->fecha_fin->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->horasprogramadas->Visible) { ?>
		<td data-field="horasprogramadas"<?php echo $Page->horasprogramadas->CellAttributes() ?>>
<span<?php echo $Page->horasprogramadas->ViewAttributes() ?>><?php echo $Page->horasprogramadas->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->perosnanombre->Visible) { ?>
		<td data-field="perosnanombre"<?php echo $Page->perosnanombre->CellAttributes() ?>>
<span<?php echo $Page->perosnanombre->ViewAttributes() ?>><?php echo $Page->perosnanombre->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->personaapellidomaterno->Visible) { ?>
		<td data-field="personaapellidomaterno"<?php echo $Page->personaapellidomaterno->CellAttributes() ?>>
<span<?php echo $Page->personaapellidomaterno->ViewAttributes() ?>><?php echo $Page->personaapellidomaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->personaapellidopaterno->Visible) { ?>
		<td data-field="personaapellidopaterno"<?php echo $Page->personaapellidopaterno->CellAttributes() ?>>
<span<?php echo $Page->personaapellidopaterno->ViewAttributes() ?>><?php echo $Page->personaapellidopaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->contenido->Visible) { ?>
		<td data-field="contenido"<?php echo $Page->contenido->CellAttributes() ?>>
<span<?php echo $Page->contenido->ViewAttributes() ?>><?php echo $Page->contenido->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->observaciones->Visible) { ?>
		<td data-field="observaciones"<?php echo $Page->observaciones->CellAttributes() ?>>
<span<?php echo $Page->observaciones->ViewAttributes() ?>><?php echo $Page->observaciones->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes() ?>>
<span<?php echo $Page->nombreinstitucion->ViewAttributes() ?>><?php echo $Page->nombreinstitucion->ListViewValue() ?></span></td>
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
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_viewactividad" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || TRUE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "viewactividadrptpager.php" ?>
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
