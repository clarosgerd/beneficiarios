<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "participanteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$participante_delete = NULL; // Initialize page object first

class cparticipante_delete extends cparticipante {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'participante';

	// Page object name
	var $PageObjName = 'participante_delete';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (participante)
		if (!isset($GLOBALS["participante"]) || get_class($GLOBALS["participante"]) == "cparticipante") {
			$GLOBALS["participante"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["participante"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'participante', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new cusuario();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("participantelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->id_sector->SetVisibility();
		$this->id_actividad->SetVisibility();
		$this->id_categoria->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nombre->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->ci->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->celular->SetVisibility();
		$this->direcciondomicilio->SetVisibility();
		$this->ocupacion->SetVisibility();
		$this->_email->SetVisibility();
		$this->cargo->SetVisibility();
		$this->nivelestudio->SetVisibility();
		$this->id_institucion->SetVisibility();
		$this->observaciones->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $participante;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($participante);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("participantelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in participante class, participanteinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("participantelist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->id->setDbValue($row['id']);
		$this->id_sector->setDbValue($row['id_sector']);
		$this->id_actividad->setDbValue($row['id_actividad']);
		$this->id_categoria->setDbValue($row['id_categoria']);
		$this->apellidopaterno->setDbValue($row['apellidopaterno']);
		$this->apellidomaterno->setDbValue($row['apellidomaterno']);
		$this->nombre->setDbValue($row['nombre']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->sexo->setDbValue($row['sexo']);
		$this->ci->setDbValue($row['ci']);
		$this->nrodiscapacidad->setDbValue($row['nrodiscapacidad']);
		$this->celular->setDbValue($row['celular']);
		$this->direcciondomicilio->setDbValue($row['direcciondomicilio']);
		$this->ocupacion->setDbValue($row['ocupacion']);
		$this->_email->setDbValue($row['email']);
		$this->cargo->setDbValue($row['cargo']);
		$this->nivelestudio->setDbValue($row['nivelestudio']);
		$this->id_institucion->setDbValue($row['id_institucion']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->id_centro->setDbValue($row['id_centro']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['id_sector'] = NULL;
		$row['id_actividad'] = NULL;
		$row['id_categoria'] = NULL;
		$row['apellidopaterno'] = NULL;
		$row['apellidomaterno'] = NULL;
		$row['nombre'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['sexo'] = NULL;
		$row['ci'] = NULL;
		$row['nrodiscapacidad'] = NULL;
		$row['celular'] = NULL;
		$row['direcciondomicilio'] = NULL;
		$row['ocupacion'] = NULL;
		$row['email'] = NULL;
		$row['cargo'] = NULL;
		$row['nivelestudio'] = NULL;
		$row['id_institucion'] = NULL;
		$row['observaciones'] = NULL;
		$row['id_centro'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->id_sector->DbValue = $row['id_sector'];
		$this->id_actividad->DbValue = $row['id_actividad'];
		$this->id_categoria->DbValue = $row['id_categoria'];
		$this->apellidopaterno->DbValue = $row['apellidopaterno'];
		$this->apellidomaterno->DbValue = $row['apellidomaterno'];
		$this->nombre->DbValue = $row['nombre'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->sexo->DbValue = $row['sexo'];
		$this->ci->DbValue = $row['ci'];
		$this->nrodiscapacidad->DbValue = $row['nrodiscapacidad'];
		$this->celular->DbValue = $row['celular'];
		$this->direcciondomicilio->DbValue = $row['direcciondomicilio'];
		$this->ocupacion->DbValue = $row['ocupacion'];
		$this->_email->DbValue = $row['email'];
		$this->cargo->DbValue = $row['cargo'];
		$this->nivelestudio->DbValue = $row['nivelestudio'];
		$this->id_institucion->DbValue = $row['id_institucion'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_centro->DbValue = $row['id_centro'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// id_sector
		// id_actividad
		// id_categoria
		// apellidopaterno
		// apellidomaterno
		// nombre
		// fecha_nacimiento
		// sexo
		// ci
		// nrodiscapacidad
		// celular
		// direcciondomicilio
		// ocupacion
		// email
		// cargo
		// nivelestudio
		// id_institucion
		// observaciones
		// id_centro

		$this->id_centro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// id_sector
		if (strval($this->id_sector->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_sector->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sector`";
		$sWhereWrk = "";
		$this->id_sector->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_sector, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_sector->ViewValue = $this->id_sector->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_sector->ViewValue = $this->id_sector->CurrentValue;
			}
		} else {
			$this->id_sector->ViewValue = NULL;
		}
		$this->id_sector->ViewCustomAttributes = "";

		// id_actividad
		if (strval($this->id_actividad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombreactividad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
		$sWhereWrk = "";
		$this->id_actividad->LookupFilters = array("dx1" => '`nombreactividad`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_actividad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_actividad->ViewValue = $this->id_actividad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_actividad->ViewValue = $this->id_actividad->CurrentValue;
			}
		} else {
			$this->id_actividad->ViewValue = NULL;
		}
		$this->id_actividad->ViewCustomAttributes = "";

		// id_categoria
		if (strval($this->id_categoria->CurrentValue) <> "") {
			$arwrk = explode(",", $this->id_categoria->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
		$sWhereWrk = "";
		$this->id_categoria->LookupFilters = array("dx1" => '`nombre`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_categoria, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_categoria->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->id_categoria->ViewValue .= $this->id_categoria->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->id_categoria->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->id_categoria->ViewValue = $this->id_categoria->CurrentValue;
			}
		} else {
			$this->id_categoria->ViewValue = NULL;
		}
		$this->id_categoria->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 7);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			$this->sexo->ViewValue = $this->sexo->OptionCaption($this->sexo->CurrentValue);
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// nrodiscapacidad
		$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
		$this->nrodiscapacidad->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// direcciondomicilio
		$this->direcciondomicilio->ViewValue = $this->direcciondomicilio->CurrentValue;
		$this->direcciondomicilio->ViewCustomAttributes = "";

		// ocupacion
		$this->ocupacion->ViewValue = $this->ocupacion->CurrentValue;
		$this->ocupacion->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// cargo
		$this->cargo->ViewValue = $this->cargo->CurrentValue;
		$this->cargo->ViewCustomAttributes = "";

		// nivelestudio
		$this->nivelestudio->ViewValue = $this->nivelestudio->CurrentValue;
		$this->nivelestudio->ViewCustomAttributes = "";

		// id_institucion
		if (strval($this->id_institucion->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_institucion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidadeducativa`";
		$sWhereWrk = "";
		$this->id_institucion->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_institucion, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_institucion->ViewValue = $this->id_institucion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_institucion->ViewValue = $this->id_institucion->CurrentValue;
			}
		} else {
			$this->id_institucion->ViewValue = NULL;
		}
		$this->id_institucion->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// id_sector
			$this->id_sector->LinkCustomAttributes = "";
			$this->id_sector->HrefValue = "";
			$this->id_sector->TooltipValue = "";

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";
			$this->id_actividad->TooltipValue = "";

			// id_categoria
			$this->id_categoria->LinkCustomAttributes = "";
			$this->id_categoria->HrefValue = "";
			$this->id_categoria->TooltipValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";
			$this->apellidopaterno->TooltipValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";
			$this->apellidomaterno->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";
			$this->sexo->TooltipValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->LinkCustomAttributes = "";
			$this->nrodiscapacidad->HrefValue = "";
			$this->nrodiscapacidad->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// direcciondomicilio
			$this->direcciondomicilio->LinkCustomAttributes = "";
			$this->direcciondomicilio->HrefValue = "";
			$this->direcciondomicilio->TooltipValue = "";

			// ocupacion
			$this->ocupacion->LinkCustomAttributes = "";
			$this->ocupacion->HrefValue = "";
			$this->ocupacion->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// cargo
			$this->cargo->LinkCustomAttributes = "";
			$this->cargo->HrefValue = "";
			$this->cargo->TooltipValue = "";

			// nivelestudio
			$this->nivelestudio->LinkCustomAttributes = "";
			$this->nivelestudio->HrefValue = "";
			$this->nivelestudio->TooltipValue = "";

			// id_institucion
			$this->id_institucion->LinkCustomAttributes = "";
			$this->id_institucion->HrefValue = "";
			$this->id_institucion->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("participantelist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($participante_delete)) $participante_delete = new cparticipante_delete();

// Page init
$participante_delete->Page_Init();

// Page main
$participante_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$participante_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fparticipantedelete = new ew_Form("fparticipantedelete", "delete");

// Form_CustomValidate event
fparticipantedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fparticipantedelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fparticipantedelete.Lists["x_id_sector"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sector"};
fparticipantedelete.Lists["x_id_sector"].Data = "<?php echo $participante_delete->id_sector->LookupFilterQuery(FALSE, "delete") ?>";
fparticipantedelete.Lists["x_id_actividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombreactividad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"actividad"};
fparticipantedelete.Lists["x_id_actividad"].Data = "<?php echo $participante_delete->id_actividad->LookupFilterQuery(FALSE, "delete") ?>";
fparticipantedelete.Lists["x_id_categoria[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categoria"};
fparticipantedelete.Lists["x_id_categoria[]"].Data = "<?php echo $participante_delete->id_categoria->LookupFilterQuery(FALSE, "delete") ?>";
fparticipantedelete.Lists["x_sexo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fparticipantedelete.Lists["x_sexo"].Options = <?php echo json_encode($participante_delete->sexo->Options()) ?>;
fparticipantedelete.Lists["x_id_institucion"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidadeducativa"};
fparticipantedelete.Lists["x_id_institucion"].Data = "<?php echo $participante_delete->id_institucion->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $participante_delete->ShowPageHeader(); ?>
<?php
$participante_delete->ShowMessage();
?>
<form name="fparticipantedelete" id="fparticipantedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($participante_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $participante_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="participante">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($participante_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($participante->id->Visible) { // id ?>
		<th class="<?php echo $participante->id->HeaderCellClass() ?>"><span id="elh_participante_id" class="participante_id"><?php echo $participante->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->id_sector->Visible) { // id_sector ?>
		<th class="<?php echo $participante->id_sector->HeaderCellClass() ?>"><span id="elh_participante_id_sector" class="participante_id_sector"><?php echo $participante->id_sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->id_actividad->Visible) { // id_actividad ?>
		<th class="<?php echo $participante->id_actividad->HeaderCellClass() ?>"><span id="elh_participante_id_actividad" class="participante_id_actividad"><?php echo $participante->id_actividad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->id_categoria->Visible) { // id_categoria ?>
		<th class="<?php echo $participante->id_categoria->HeaderCellClass() ?>"><span id="elh_participante_id_categoria" class="participante_id_categoria"><?php echo $participante->id_categoria->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->apellidopaterno->Visible) { // apellidopaterno ?>
		<th class="<?php echo $participante->apellidopaterno->HeaderCellClass() ?>"><span id="elh_participante_apellidopaterno" class="participante_apellidopaterno"><?php echo $participante->apellidopaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->apellidomaterno->Visible) { // apellidomaterno ?>
		<th class="<?php echo $participante->apellidomaterno->HeaderCellClass() ?>"><span id="elh_participante_apellidomaterno" class="participante_apellidomaterno"><?php echo $participante->apellidomaterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->nombre->Visible) { // nombre ?>
		<th class="<?php echo $participante->nombre->HeaderCellClass() ?>"><span id="elh_participante_nombre" class="participante_nombre"><?php echo $participante->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<th class="<?php echo $participante->fecha_nacimiento->HeaderCellClass() ?>"><span id="elh_participante_fecha_nacimiento" class="participante_fecha_nacimiento"><?php echo $participante->fecha_nacimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->sexo->Visible) { // sexo ?>
		<th class="<?php echo $participante->sexo->HeaderCellClass() ?>"><span id="elh_participante_sexo" class="participante_sexo"><?php echo $participante->sexo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->ci->Visible) { // ci ?>
		<th class="<?php echo $participante->ci->HeaderCellClass() ?>"><span id="elh_participante_ci" class="participante_ci"><?php echo $participante->ci->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<th class="<?php echo $participante->nrodiscapacidad->HeaderCellClass() ?>"><span id="elh_participante_nrodiscapacidad" class="participante_nrodiscapacidad"><?php echo $participante->nrodiscapacidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->celular->Visible) { // celular ?>
		<th class="<?php echo $participante->celular->HeaderCellClass() ?>"><span id="elh_participante_celular" class="participante_celular"><?php echo $participante->celular->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->direcciondomicilio->Visible) { // direcciondomicilio ?>
		<th class="<?php echo $participante->direcciondomicilio->HeaderCellClass() ?>"><span id="elh_participante_direcciondomicilio" class="participante_direcciondomicilio"><?php echo $participante->direcciondomicilio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->ocupacion->Visible) { // ocupacion ?>
		<th class="<?php echo $participante->ocupacion->HeaderCellClass() ?>"><span id="elh_participante_ocupacion" class="participante_ocupacion"><?php echo $participante->ocupacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->_email->Visible) { // email ?>
		<th class="<?php echo $participante->_email->HeaderCellClass() ?>"><span id="elh_participante__email" class="participante__email"><?php echo $participante->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->cargo->Visible) { // cargo ?>
		<th class="<?php echo $participante->cargo->HeaderCellClass() ?>"><span id="elh_participante_cargo" class="participante_cargo"><?php echo $participante->cargo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->nivelestudio->Visible) { // nivelestudio ?>
		<th class="<?php echo $participante->nivelestudio->HeaderCellClass() ?>"><span id="elh_participante_nivelestudio" class="participante_nivelestudio"><?php echo $participante->nivelestudio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->id_institucion->Visible) { // id_institucion ?>
		<th class="<?php echo $participante->id_institucion->HeaderCellClass() ?>"><span id="elh_participante_id_institucion" class="participante_id_institucion"><?php echo $participante->id_institucion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($participante->observaciones->Visible) { // observaciones ?>
		<th class="<?php echo $participante->observaciones->HeaderCellClass() ?>"><span id="elh_participante_observaciones" class="participante_observaciones"><?php echo $participante->observaciones->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$participante_delete->RecCnt = 0;
$i = 0;
while (!$participante_delete->Recordset->EOF) {
	$participante_delete->RecCnt++;
	$participante_delete->RowCnt++;

	// Set row properties
	$participante->ResetAttrs();
	$participante->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$participante_delete->LoadRowValues($participante_delete->Recordset);

	// Render row
	$participante_delete->RenderRow();
?>
	<tr<?php echo $participante->RowAttributes() ?>>
<?php if ($participante->id->Visible) { // id ?>
		<td<?php echo $participante->id->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_id" class="participante_id">
<span<?php echo $participante->id->ViewAttributes() ?>>
<?php echo $participante->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->id_sector->Visible) { // id_sector ?>
		<td<?php echo $participante->id_sector->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_id_sector" class="participante_id_sector">
<span<?php echo $participante->id_sector->ViewAttributes() ?>>
<?php echo $participante->id_sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->id_actividad->Visible) { // id_actividad ?>
		<td<?php echo $participante->id_actividad->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_id_actividad" class="participante_id_actividad">
<span<?php echo $participante->id_actividad->ViewAttributes() ?>>
<?php echo $participante->id_actividad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->id_categoria->Visible) { // id_categoria ?>
		<td<?php echo $participante->id_categoria->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_id_categoria" class="participante_id_categoria">
<span<?php echo $participante->id_categoria->ViewAttributes() ?>>
<?php echo $participante->id_categoria->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->apellidopaterno->Visible) { // apellidopaterno ?>
		<td<?php echo $participante->apellidopaterno->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_apellidopaterno" class="participante_apellidopaterno">
<span<?php echo $participante->apellidopaterno->ViewAttributes() ?>>
<?php echo $participante->apellidopaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->apellidomaterno->Visible) { // apellidomaterno ?>
		<td<?php echo $participante->apellidomaterno->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_apellidomaterno" class="participante_apellidomaterno">
<span<?php echo $participante->apellidomaterno->ViewAttributes() ?>>
<?php echo $participante->apellidomaterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->nombre->Visible) { // nombre ?>
		<td<?php echo $participante->nombre->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_nombre" class="participante_nombre">
<span<?php echo $participante->nombre->ViewAttributes() ?>>
<?php echo $participante->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td<?php echo $participante->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_fecha_nacimiento" class="participante_fecha_nacimiento">
<span<?php echo $participante->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $participante->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->sexo->Visible) { // sexo ?>
		<td<?php echo $participante->sexo->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_sexo" class="participante_sexo">
<span<?php echo $participante->sexo->ViewAttributes() ?>>
<?php echo $participante->sexo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->ci->Visible) { // ci ?>
		<td<?php echo $participante->ci->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_ci" class="participante_ci">
<span<?php echo $participante->ci->ViewAttributes() ?>>
<?php echo $participante->ci->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->nrodiscapacidad->Visible) { // nrodiscapacidad ?>
		<td<?php echo $participante->nrodiscapacidad->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_nrodiscapacidad" class="participante_nrodiscapacidad">
<span<?php echo $participante->nrodiscapacidad->ViewAttributes() ?>>
<?php echo $participante->nrodiscapacidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->celular->Visible) { // celular ?>
		<td<?php echo $participante->celular->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_celular" class="participante_celular">
<span<?php echo $participante->celular->ViewAttributes() ?>>
<?php echo $participante->celular->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->direcciondomicilio->Visible) { // direcciondomicilio ?>
		<td<?php echo $participante->direcciondomicilio->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_direcciondomicilio" class="participante_direcciondomicilio">
<span<?php echo $participante->direcciondomicilio->ViewAttributes() ?>>
<?php echo $participante->direcciondomicilio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->ocupacion->Visible) { // ocupacion ?>
		<td<?php echo $participante->ocupacion->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_ocupacion" class="participante_ocupacion">
<span<?php echo $participante->ocupacion->ViewAttributes() ?>>
<?php echo $participante->ocupacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->_email->Visible) { // email ?>
		<td<?php echo $participante->_email->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante__email" class="participante__email">
<span<?php echo $participante->_email->ViewAttributes() ?>>
<?php echo $participante->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->cargo->Visible) { // cargo ?>
		<td<?php echo $participante->cargo->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_cargo" class="participante_cargo">
<span<?php echo $participante->cargo->ViewAttributes() ?>>
<?php echo $participante->cargo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->nivelestudio->Visible) { // nivelestudio ?>
		<td<?php echo $participante->nivelestudio->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_nivelestudio" class="participante_nivelestudio">
<span<?php echo $participante->nivelestudio->ViewAttributes() ?>>
<?php echo $participante->nivelestudio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->id_institucion->Visible) { // id_institucion ?>
		<td<?php echo $participante->id_institucion->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_id_institucion" class="participante_id_institucion">
<span<?php echo $participante->id_institucion->ViewAttributes() ?>>
<?php echo $participante->id_institucion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($participante->observaciones->Visible) { // observaciones ?>
		<td<?php echo $participante->observaciones->CellAttributes() ?>>
<span id="el<?php echo $participante_delete->RowCnt ?>_participante_observaciones" class="participante_observaciones">
<span<?php echo $participante->observaciones->ViewAttributes() ?>>
<?php echo $participante->observaciones->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$participante_delete->Recordset->MoveNext();
}
$participante_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $participante_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fparticipantedelete.Init();
</script>
<?php
$participante_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$participante_delete->Page_Terminate();
?>
