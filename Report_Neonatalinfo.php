<?php

// Global variable for table object
$Report_Neonatal = NULL;

//
// Table class for Report Neonatal
//
class cReport_Neonatal extends cTable {
	var $id_neonato;
	var $apellidopaterno;
	var $apellidomaterno;
	var $nombre;
	var $ci;
	var $fecha_nacimiento;
	var $dias;
	var $semanas;
	var $meses;
	var $discapacidad;
	var $resultado;
	var $observaciones;
	var $tipoprueba;
	var $resultadprueba;
	var $recomendacion;
	var $id_tipodiagnosticoaudiologia;
	var $nombrediagnotico;
	var $resultadodiagnostico;
	var $tipotratamiento;
	var $tipoderivacion;
	var $nombreespcialidad;
	var $observaciones1;
	var $fecha;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Report_Neonatal';
		$this->TableName = 'Report Neonatal';
		$this->TableType = 'CUSTOMVIEW';

		// Update Table
		$this->UpdateTable = "audiologia INNER JOIN pruebasaudiologia ON audiologia.id = pruebasaudiologia.id_audiologia INNER JOIN diagnosticoaudiologia ON audiologia.id = diagnosticoaudiologia.id_audiologia INNER JOIN neonatal ON audiologia.id_neonato = neonatal.id INNER JOIN tratamiento ON audiologia.id = tratamiento.id_audiologia INNER JOIN derivacion ON audiologia.id = derivacion.id_audiologia INNER JOIN tipopruebasaudiologia ON pruebasaudiologia.id_tipopruebasaudiologia = tipopruebasaudiologia.id INNER JOIN tipodiagnosticoaudiologia ON diagnosticoaudiologia.id_tipodiagnosticoaudiologia = tipodiagnosticoaudiologia.id INNER JOIN tipotratamientoaudiologia ON tratamiento.id_tipotratamientoaudiologia = tipotratamientoaudiologia.id INNER JOIN tipoespecialidad ON derivacion.id_tipoespecialidad = tipoespecialidad.id";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_neonato
		$this->id_neonato = new cField('Report_Neonatal', 'Report Neonatal', 'x_id_neonato', 'id_neonato', 'audiologia.id_neonato', 'audiologia.id_neonato', 3, -1, FALSE, 'audiologia.id_neonato', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_neonato->Sortable = TRUE; // Allow sort
		$this->id_neonato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_neonato'] = &$this->id_neonato;

		// apellidopaterno
		$this->apellidopaterno = new cField('Report_Neonatal', 'Report Neonatal', 'x_apellidopaterno', 'apellidopaterno', 'neonatal.apellidopaterno', 'neonatal.apellidopaterno', 200, -1, FALSE, 'neonatal.apellidopaterno', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidopaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidopaterno'] = &$this->apellidopaterno;

		// apellidomaterno
		$this->apellidomaterno = new cField('Report_Neonatal', 'Report Neonatal', 'x_apellidomaterno', 'apellidomaterno', 'neonatal.apellidomaterno', 'neonatal.apellidomaterno', 200, -1, FALSE, 'neonatal.apellidomaterno', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->apellidomaterno->Sortable = TRUE; // Allow sort
		$this->fields['apellidomaterno'] = &$this->apellidomaterno;

		// nombre
		$this->nombre = new cField('Report_Neonatal', 'Report Neonatal', 'x_nombre', 'nombre', 'neonatal.nombre', 'neonatal.nombre', 200, -1, FALSE, 'neonatal.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->fields['nombre'] = &$this->nombre;

		// ci
		$this->ci = new cField('Report_Neonatal', 'Report Neonatal', 'x_ci', 'ci', 'neonatal.ci', 'neonatal.ci', 200, -1, FALSE, 'neonatal.ci', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ci->Sortable = TRUE; // Allow sort
		$this->fields['ci'] = &$this->ci;

		// fecha_nacimiento
		$this->fecha_nacimiento = new cField('Report_Neonatal', 'Report Neonatal', 'x_fecha_nacimiento', 'fecha_nacimiento', 'neonatal.fecha_nacimiento', ew_CastDateFieldForLike('neonatal.fecha_nacimiento', 0, "DB"), 133, 0, FALSE, 'neonatal.fecha_nacimiento', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_nacimiento->Sortable = TRUE; // Allow sort
		$this->fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha_nacimiento'] = &$this->fecha_nacimiento;

		// dias
		$this->dias = new cField('Report_Neonatal', 'Report Neonatal', 'x_dias', 'dias', 'neonatal.dias', 'neonatal.dias', 200, -1, FALSE, 'neonatal.dias', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dias->Sortable = TRUE; // Allow sort
		$this->fields['dias'] = &$this->dias;

		// semanas
		$this->semanas = new cField('Report_Neonatal', 'Report Neonatal', 'x_semanas', 'semanas', 'neonatal.semanas', 'neonatal.semanas', 200, -1, FALSE, 'neonatal.semanas', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->semanas->Sortable = TRUE; // Allow sort
		$this->fields['semanas'] = &$this->semanas;

		// meses
		$this->meses = new cField('Report_Neonatal', 'Report Neonatal', 'x_meses', 'meses', 'neonatal.meses', 'neonatal.meses', 200, -1, FALSE, 'neonatal.meses', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->meses->Sortable = TRUE; // Allow sort
		$this->fields['meses'] = &$this->meses;

		// discapacidad
		$this->discapacidad = new cField('Report_Neonatal', 'Report Neonatal', 'x_discapacidad', 'discapacidad', 'neonatal.discapacidad', 'neonatal.discapacidad', 3, -1, FALSE, 'neonatal.discapacidad', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->discapacidad->Sortable = TRUE; // Allow sort
		$this->fields['discapacidad'] = &$this->discapacidad;

		// resultado
		$this->resultado = new cField('Report_Neonatal', 'Report Neonatal', 'x_resultado', 'resultado', 'neonatal.resultado', 'neonatal.resultado', 200, -1, FALSE, 'neonatal.resultado', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resultado->Sortable = TRUE; // Allow sort
		$this->fields['resultado'] = &$this->resultado;

		// observaciones
		$this->observaciones = new cField('Report_Neonatal', 'Report Neonatal', 'x_observaciones', 'observaciones', 'neonatal.observaciones', 'neonatal.observaciones', 200, -1, FALSE, 'neonatal.observaciones', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// tipoprueba
		$this->tipoprueba = new cField('Report_Neonatal', 'Report Neonatal', 'x_tipoprueba', 'tipoprueba', 'tipopruebasaudiologia.nombre', 'tipopruebasaudiologia.nombre', 200, -1, FALSE, 'tipopruebasaudiologia.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipoprueba->Sortable = TRUE; // Allow sort
		$this->fields['tipoprueba'] = &$this->tipoprueba;

		// resultadprueba
		$this->resultadprueba = new cField('Report_Neonatal', 'Report Neonatal', 'x_resultadprueba', 'resultadprueba', 'pruebasaudiologia.resultado', 'pruebasaudiologia.resultado', 200, -1, FALSE, 'pruebasaudiologia.resultado', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resultadprueba->Sortable = TRUE; // Allow sort
		$this->fields['resultadprueba'] = &$this->resultadprueba;

		// recomendacion
		$this->recomendacion = new cField('Report_Neonatal', 'Report Neonatal', 'x_recomendacion', 'recomendacion', 'pruebasaudiologia.recomendacion', 'pruebasaudiologia.recomendacion', 200, -1, FALSE, 'pruebasaudiologia.recomendacion', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->recomendacion->Sortable = TRUE; // Allow sort
		$this->fields['recomendacion'] = &$this->recomendacion;

		// id_tipodiagnosticoaudiologia
		$this->id_tipodiagnosticoaudiologia = new cField('Report_Neonatal', 'Report Neonatal', 'x_id_tipodiagnosticoaudiologia', 'id_tipodiagnosticoaudiologia', 'diagnosticoaudiologia.id_tipodiagnosticoaudiologia', 'diagnosticoaudiologia.id_tipodiagnosticoaudiologia', 3, -1, FALSE, 'diagnosticoaudiologia.id_tipodiagnosticoaudiologia', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_tipodiagnosticoaudiologia->Sortable = TRUE; // Allow sort
		$this->id_tipodiagnosticoaudiologia->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_tipodiagnosticoaudiologia->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_tipodiagnosticoaudiologia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tipodiagnosticoaudiologia'] = &$this->id_tipodiagnosticoaudiologia;

		// nombrediagnotico
		$this->nombrediagnotico = new cField('Report_Neonatal', 'Report Neonatal', 'x_nombrediagnotico', 'nombrediagnotico', 'tipodiagnosticoaudiologia.nombre', 'tipodiagnosticoaudiologia.nombre', 200, -1, FALSE, 'tipodiagnosticoaudiologia.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombrediagnotico->Sortable = TRUE; // Allow sort
		$this->fields['nombrediagnotico'] = &$this->nombrediagnotico;

		// resultadodiagnostico
		$this->resultadodiagnostico = new cField('Report_Neonatal', 'Report Neonatal', 'x_resultadodiagnostico', 'resultadodiagnostico', 'diagnosticoaudiologia.resultado', 'diagnosticoaudiologia.resultado', 200, -1, FALSE, 'diagnosticoaudiologia.resultado', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resultadodiagnostico->Sortable = TRUE; // Allow sort
		$this->fields['resultadodiagnostico'] = &$this->resultadodiagnostico;

		// tipotratamiento
		$this->tipotratamiento = new cField('Report_Neonatal', 'Report Neonatal', 'x_tipotratamiento', 'tipotratamiento', 'tipotratamientoaudiologia.nombre', 'tipotratamientoaudiologia.nombre', 200, -1, FALSE, 'tipotratamientoaudiologia.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipotratamiento->Sortable = TRUE; // Allow sort
		$this->fields['tipotratamiento'] = &$this->tipotratamiento;

		// tipoderivacion
		$this->tipoderivacion = new cField('Report_Neonatal', 'Report Neonatal', 'x_tipoderivacion', 'tipoderivacion', 'derivacion.tipoderivacion', 'derivacion.tipoderivacion', 200, -1, FALSE, 'derivacion.tipoderivacion', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipoderivacion->Sortable = TRUE; // Allow sort
		$this->tipoderivacion->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->tipoderivacion->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->tipoderivacion->OptionCount = 2;
		$this->fields['tipoderivacion'] = &$this->tipoderivacion;

		// nombreespcialidad
		$this->nombreespcialidad = new cField('Report_Neonatal', 'Report Neonatal', 'x_nombreespcialidad', 'nombreespcialidad', 'tipoespecialidad.nombre', 'tipoespecialidad.nombre', 200, -1, FALSE, 'tipoespecialidad.nombre', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombreespcialidad->Sortable = TRUE; // Allow sort
		$this->fields['nombreespcialidad'] = &$this->nombreespcialidad;

		// observaciones1
		$this->observaciones1 = new cField('Report_Neonatal', 'Report Neonatal', 'x_observaciones1', 'observaciones1', 'audiologia.observaciones', 'audiologia.observaciones', 3, -1, FALSE, 'audiologia.observaciones', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->observaciones1->Sortable = TRUE; // Allow sort
		$this->observaciones1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['observaciones1'] = &$this->observaciones1;

		// fecha
		$this->fecha = new cField('Report_Neonatal', 'Report Neonatal', 'x_fecha', 'fecha', 'audiologia.fecha', ew_CastDateFieldForLike('audiologia.fecha', 0, "DB"), 133, 0, FALSE, 'audiologia.fecha', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha'] = &$this->fecha;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "audiologia INNER JOIN pruebasaudiologia ON audiologia.id = pruebasaudiologia.id_audiologia INNER JOIN diagnosticoaudiologia ON audiologia.id = diagnosticoaudiologia.id_audiologia INNER JOIN neonatal ON audiologia.id_neonato = neonatal.id INNER JOIN tratamiento ON audiologia.id = tratamiento.id_audiologia INNER JOIN derivacion ON audiologia.id = derivacion.id_audiologia INNER JOIN tipopruebasaudiologia ON pruebasaudiologia.id_tipopruebasaudiologia = tipopruebasaudiologia.id INNER JOIN tipodiagnosticoaudiologia ON diagnosticoaudiologia.id_tipodiagnosticoaudiologia = tipodiagnosticoaudiologia.id INNER JOIN tipotratamientoaudiologia ON tratamiento.id_tipotratamientoaudiologia = tipotratamientoaudiologia.id INNER JOIN tipoespecialidad ON derivacion.id_tipoespecialidad = tipoespecialidad.id";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT audiologia.id_neonato, neonatal.apellidopaterno, neonatal.apellidomaterno, neonatal.nombre, neonatal.ci, neonatal.fecha_nacimiento, neonatal.dias, neonatal.semanas, neonatal.meses, neonatal.discapacidad, neonatal.resultado, neonatal.observaciones, tipopruebasaudiologia.nombre AS tipoprueba, pruebasaudiologia.resultado AS resultadprueba, pruebasaudiologia.recomendacion, diagnosticoaudiologia.id_tipodiagnosticoaudiologia, tipodiagnosticoaudiologia.nombre AS nombrediagnotico, diagnosticoaudiologia.resultado AS resultadodiagnostico, tipotratamientoaudiologia.nombre AS tipotratamiento, derivacion.tipoderivacion, tipoespecialidad.nombre AS nombreespcialidad, audiologia.observaciones AS observaciones1, audiologia.fecha FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "audiologia.id_neonato, neonatal.apellidopaterno, neonatal.apellidomaterno, neonatal.nombre, neonatal.ci, neonatal.fecha_nacimiento, neonatal.dias, neonatal.semanas, neonatal.meses, neonatal.discapacidad, neonatal.resultado, neonatal.observaciones, audiologia.observaciones, audiologia.fecha";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "Report_Neonatallist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "Report_Neonatalview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "Report_Neonataledit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "Report_Neonataladd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "Report_Neonatallist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("Report_Neonatalview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("Report_Neonatalview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "Report_Neonataladd.php?" . $this->UrlParm($parm);
		else
			$url = "Report_Neonataladd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("Report_Neonataledit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("Report_Neonataladd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("Report_Neonataldelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id_neonato->setDbValue($rs->fields('id_neonato'));
		$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
		$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->ci->setDbValue($rs->fields('ci'));
		$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
		$this->dias->setDbValue($rs->fields('dias'));
		$this->semanas->setDbValue($rs->fields('semanas'));
		$this->meses->setDbValue($rs->fields('meses'));
		$this->discapacidad->setDbValue($rs->fields('discapacidad'));
		$this->resultado->setDbValue($rs->fields('resultado'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->tipoprueba->setDbValue($rs->fields('tipoprueba'));
		$this->resultadprueba->setDbValue($rs->fields('resultadprueba'));
		$this->recomendacion->setDbValue($rs->fields('recomendacion'));
		$this->id_tipodiagnosticoaudiologia->setDbValue($rs->fields('id_tipodiagnosticoaudiologia'));
		$this->nombrediagnotico->setDbValue($rs->fields('nombrediagnotico'));
		$this->resultadodiagnostico->setDbValue($rs->fields('resultadodiagnostico'));
		$this->tipotratamiento->setDbValue($rs->fields('tipotratamiento'));
		$this->tipoderivacion->setDbValue($rs->fields('tipoderivacion'));
		$this->nombreespcialidad->setDbValue($rs->fields('nombreespcialidad'));
		$this->observaciones1->setDbValue($rs->fields('observaciones1'));
		$this->fecha->setDbValue($rs->fields('fecha'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id_neonato
		// apellidopaterno
		// apellidomaterno
		// nombre
		// ci
		// fecha_nacimiento
		// dias
		// semanas
		// meses
		// discapacidad
		// resultado
		// observaciones
		// tipoprueba
		// resultadprueba
		// recomendacion
		// id_tipodiagnosticoaudiologia
		// nombrediagnotico
		// resultadodiagnostico
		// tipotratamiento
		// tipoderivacion
		// nombreespcialidad
		// observaciones1
		// fecha
		// id_neonato

		$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
		if (strval($this->id_neonato->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_neonato->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
		$sWhereWrk = "";
		$this->id_neonato->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_neonato->ViewValue = $this->id_neonato->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
			}
		} else {
			$this->id_neonato->ViewValue = NULL;
		}
		$this->id_neonato->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// dias
		$this->dias->ViewValue = $this->dias->CurrentValue;
		$this->dias->ViewCustomAttributes = "";

		// semanas
		$this->semanas->ViewValue = $this->semanas->CurrentValue;
		$this->semanas->ViewCustomAttributes = "";

		// meses
		$this->meses->ViewValue = $this->meses->CurrentValue;
		$this->meses->ViewCustomAttributes = "";

		// discapacidad
		$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->ViewCustomAttributes = "";

		// resultado
		$this->resultado->ViewValue = $this->resultado->CurrentValue;
		$this->resultado->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// tipoprueba
		$this->tipoprueba->ViewValue = $this->tipoprueba->CurrentValue;
		$this->tipoprueba->ViewCustomAttributes = "";

		// resultadprueba
		$this->resultadprueba->ViewValue = $this->resultadprueba->CurrentValue;
		$this->resultadprueba->ViewCustomAttributes = "";

		// recomendacion
		$this->recomendacion->ViewValue = $this->recomendacion->CurrentValue;
		$this->recomendacion->ViewCustomAttributes = "";

		// id_tipodiagnosticoaudiologia
		if (strval($this->id_tipodiagnosticoaudiologia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipodiagnosticoaudiologia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiagnosticoaudiologia`";
		$sWhereWrk = "";
		$this->id_tipodiagnosticoaudiologia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipodiagnosticoaudiologia, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipodiagnosticoaudiologia->ViewValue = $this->id_tipodiagnosticoaudiologia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipodiagnosticoaudiologia->ViewValue = $this->id_tipodiagnosticoaudiologia->CurrentValue;
			}
		} else {
			$this->id_tipodiagnosticoaudiologia->ViewValue = NULL;
		}
		$this->id_tipodiagnosticoaudiologia->ViewCustomAttributes = "";

		// nombrediagnotico
		$this->nombrediagnotico->ViewValue = $this->nombrediagnotico->CurrentValue;
		$this->nombrediagnotico->ViewCustomAttributes = "";

		// resultadodiagnostico
		$this->resultadodiagnostico->ViewValue = $this->resultadodiagnostico->CurrentValue;
		$this->resultadodiagnostico->ViewCustomAttributes = "";

		// tipotratamiento
		$this->tipotratamiento->ViewValue = $this->tipotratamiento->CurrentValue;
		$this->tipotratamiento->ViewCustomAttributes = "";

		// tipoderivacion
		if (strval($this->tipoderivacion->CurrentValue) <> "") {
			$this->tipoderivacion->ViewValue = $this->tipoderivacion->OptionCaption($this->tipoderivacion->CurrentValue);
		} else {
			$this->tipoderivacion->ViewValue = NULL;
		}
		$this->tipoderivacion->ViewCustomAttributes = "";

		// nombreespcialidad
		$this->nombreespcialidad->ViewValue = $this->nombreespcialidad->CurrentValue;
		$this->nombreespcialidad->ViewCustomAttributes = "";

		// observaciones1
		$this->observaciones1->ViewValue = $this->observaciones1->CurrentValue;
		$this->observaciones1->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// id_neonato
		$this->id_neonato->LinkCustomAttributes = "";
		$this->id_neonato->HrefValue = "";
		$this->id_neonato->TooltipValue = "";

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

		// ci
		$this->ci->LinkCustomAttributes = "";
		$this->ci->HrefValue = "";
		$this->ci->TooltipValue = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->LinkCustomAttributes = "";
		$this->fecha_nacimiento->HrefValue = "";
		$this->fecha_nacimiento->TooltipValue = "";

		// dias
		$this->dias->LinkCustomAttributes = "";
		$this->dias->HrefValue = "";
		$this->dias->TooltipValue = "";

		// semanas
		$this->semanas->LinkCustomAttributes = "";
		$this->semanas->HrefValue = "";
		$this->semanas->TooltipValue = "";

		// meses
		$this->meses->LinkCustomAttributes = "";
		$this->meses->HrefValue = "";
		$this->meses->TooltipValue = "";

		// discapacidad
		$this->discapacidad->LinkCustomAttributes = "";
		$this->discapacidad->HrefValue = "";
		$this->discapacidad->TooltipValue = "";

		// resultado
		$this->resultado->LinkCustomAttributes = "";
		$this->resultado->HrefValue = "";
		$this->resultado->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// tipoprueba
		$this->tipoprueba->LinkCustomAttributes = "";
		$this->tipoprueba->HrefValue = "";
		$this->tipoprueba->TooltipValue = "";

		// resultadprueba
		$this->resultadprueba->LinkCustomAttributes = "";
		$this->resultadprueba->HrefValue = "";
		$this->resultadprueba->TooltipValue = "";

		// recomendacion
		$this->recomendacion->LinkCustomAttributes = "";
		$this->recomendacion->HrefValue = "";
		$this->recomendacion->TooltipValue = "";

		// id_tipodiagnosticoaudiologia
		$this->id_tipodiagnosticoaudiologia->LinkCustomAttributes = "";
		$this->id_tipodiagnosticoaudiologia->HrefValue = "";
		$this->id_tipodiagnosticoaudiologia->TooltipValue = "";

		// nombrediagnotico
		$this->nombrediagnotico->LinkCustomAttributes = "";
		$this->nombrediagnotico->HrefValue = "";
		$this->nombrediagnotico->TooltipValue = "";

		// resultadodiagnostico
		$this->resultadodiagnostico->LinkCustomAttributes = "";
		$this->resultadodiagnostico->HrefValue = "";
		$this->resultadodiagnostico->TooltipValue = "";

		// tipotratamiento
		$this->tipotratamiento->LinkCustomAttributes = "";
		$this->tipotratamiento->HrefValue = "";
		$this->tipotratamiento->TooltipValue = "";

		// tipoderivacion
		$this->tipoderivacion->LinkCustomAttributes = "";
		$this->tipoderivacion->HrefValue = "";
		$this->tipoderivacion->TooltipValue = "";

		// nombreespcialidad
		$this->nombreespcialidad->LinkCustomAttributes = "";
		$this->nombreespcialidad->HrefValue = "";
		$this->nombreespcialidad->TooltipValue = "";

		// observaciones1
		$this->observaciones1->LinkCustomAttributes = "";
		$this->observaciones1->HrefValue = "";
		$this->observaciones1->TooltipValue = "";

		// fecha
		$this->fecha->LinkCustomAttributes = "";
		$this->fecha->HrefValue = "";
		$this->fecha->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id_neonato
		$this->id_neonato->EditAttrs["class"] = "form-control";
		$this->id_neonato->EditCustomAttributes = "";
		$this->id_neonato->EditValue = $this->id_neonato->CurrentValue;
		$this->id_neonato->PlaceHolder = ew_RemoveHtml($this->id_neonato->FldCaption());

		// apellidopaterno
		$this->apellidopaterno->EditAttrs["class"] = "form-control";
		$this->apellidopaterno->EditCustomAttributes = "";
		$this->apellidopaterno->EditValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->PlaceHolder = ew_RemoveHtml($this->apellidopaterno->FldCaption());

		// apellidomaterno
		$this->apellidomaterno->EditAttrs["class"] = "form-control";
		$this->apellidomaterno->EditCustomAttributes = "";
		$this->apellidomaterno->EditValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->PlaceHolder = ew_RemoveHtml($this->apellidomaterno->FldCaption());

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = $this->nombre->CurrentValue;
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

		// ci
		$this->ci->EditAttrs["class"] = "form-control";
		$this->ci->EditCustomAttributes = "";
		$this->ci->EditValue = $this->ci->CurrentValue;
		$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

		// fecha_nacimiento
		$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
		$this->fecha_nacimiento->EditCustomAttributes = "";
		$this->fecha_nacimiento->EditValue = ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 8);
		$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

		// dias
		$this->dias->EditAttrs["class"] = "form-control";
		$this->dias->EditCustomAttributes = "";
		$this->dias->EditValue = $this->dias->CurrentValue;
		$this->dias->PlaceHolder = ew_RemoveHtml($this->dias->FldCaption());

		// semanas
		$this->semanas->EditAttrs["class"] = "form-control";
		$this->semanas->EditCustomAttributes = "";
		$this->semanas->EditValue = $this->semanas->CurrentValue;
		$this->semanas->PlaceHolder = ew_RemoveHtml($this->semanas->FldCaption());

		// meses
		$this->meses->EditAttrs["class"] = "form-control";
		$this->meses->EditCustomAttributes = "";
		$this->meses->EditValue = $this->meses->CurrentValue;
		$this->meses->PlaceHolder = ew_RemoveHtml($this->meses->FldCaption());

		// discapacidad
		$this->discapacidad->EditAttrs["class"] = "form-control";
		$this->discapacidad->EditCustomAttributes = "";
		$this->discapacidad->EditValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

		// resultado
		$this->resultado->EditAttrs["class"] = "form-control";
		$this->resultado->EditCustomAttributes = "";
		$this->resultado->EditValue = $this->resultado->CurrentValue;
		$this->resultado->PlaceHolder = ew_RemoveHtml($this->resultado->FldCaption());

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// tipoprueba
		$this->tipoprueba->EditAttrs["class"] = "form-control";
		$this->tipoprueba->EditCustomAttributes = "";
		$this->tipoprueba->EditValue = $this->tipoprueba->CurrentValue;
		$this->tipoprueba->PlaceHolder = ew_RemoveHtml($this->tipoprueba->FldCaption());

		// resultadprueba
		$this->resultadprueba->EditAttrs["class"] = "form-control";
		$this->resultadprueba->EditCustomAttributes = "";
		$this->resultadprueba->EditValue = $this->resultadprueba->CurrentValue;
		$this->resultadprueba->PlaceHolder = ew_RemoveHtml($this->resultadprueba->FldCaption());

		// recomendacion
		$this->recomendacion->EditAttrs["class"] = "form-control";
		$this->recomendacion->EditCustomAttributes = "";
		$this->recomendacion->EditValue = $this->recomendacion->CurrentValue;
		$this->recomendacion->PlaceHolder = ew_RemoveHtml($this->recomendacion->FldCaption());

		// id_tipodiagnosticoaudiologia
		$this->id_tipodiagnosticoaudiologia->EditAttrs["class"] = "form-control";
		$this->id_tipodiagnosticoaudiologia->EditCustomAttributes = "";

		// nombrediagnotico
		$this->nombrediagnotico->EditAttrs["class"] = "form-control";
		$this->nombrediagnotico->EditCustomAttributes = "";
		$this->nombrediagnotico->EditValue = $this->nombrediagnotico->CurrentValue;
		$this->nombrediagnotico->PlaceHolder = ew_RemoveHtml($this->nombrediagnotico->FldCaption());

		// resultadodiagnostico
		$this->resultadodiagnostico->EditAttrs["class"] = "form-control";
		$this->resultadodiagnostico->EditCustomAttributes = "";
		$this->resultadodiagnostico->EditValue = $this->resultadodiagnostico->CurrentValue;
		$this->resultadodiagnostico->PlaceHolder = ew_RemoveHtml($this->resultadodiagnostico->FldCaption());

		// tipotratamiento
		$this->tipotratamiento->EditAttrs["class"] = "form-control";
		$this->tipotratamiento->EditCustomAttributes = "";
		$this->tipotratamiento->EditValue = $this->tipotratamiento->CurrentValue;
		$this->tipotratamiento->PlaceHolder = ew_RemoveHtml($this->tipotratamiento->FldCaption());

		// tipoderivacion
		$this->tipoderivacion->EditAttrs["class"] = "form-control";
		$this->tipoderivacion->EditCustomAttributes = "";
		$this->tipoderivacion->EditValue = $this->tipoderivacion->Options(TRUE);

		// nombreespcialidad
		$this->nombreespcialidad->EditAttrs["class"] = "form-control";
		$this->nombreespcialidad->EditCustomAttributes = "";
		$this->nombreespcialidad->EditValue = $this->nombreespcialidad->CurrentValue;
		$this->nombreespcialidad->PlaceHolder = ew_RemoveHtml($this->nombreespcialidad->FldCaption());

		// observaciones1
		$this->observaciones1->EditAttrs["class"] = "form-control";
		$this->observaciones1->EditCustomAttributes = "";
		$this->observaciones1->EditValue = $this->observaciones1->CurrentValue;
		$this->observaciones1->PlaceHolder = ew_RemoveHtml($this->observaciones1->FldCaption());

		// fecha
		// Call Row Rendered event

		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id_neonato->Exportable) $Doc->ExportCaption($this->id_neonato);
					if ($this->apellidopaterno->Exportable) $Doc->ExportCaption($this->apellidopaterno);
					if ($this->apellidomaterno->Exportable) $Doc->ExportCaption($this->apellidomaterno);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->dias->Exportable) $Doc->ExportCaption($this->dias);
					if ($this->semanas->Exportable) $Doc->ExportCaption($this->semanas);
					if ($this->meses->Exportable) $Doc->ExportCaption($this->meses);
					if ($this->discapacidad->Exportable) $Doc->ExportCaption($this->discapacidad);
					if ($this->resultado->Exportable) $Doc->ExportCaption($this->resultado);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->tipoprueba->Exportable) $Doc->ExportCaption($this->tipoprueba);
					if ($this->resultadprueba->Exportable) $Doc->ExportCaption($this->resultadprueba);
					if ($this->recomendacion->Exportable) $Doc->ExportCaption($this->recomendacion);
					if ($this->id_tipodiagnosticoaudiologia->Exportable) $Doc->ExportCaption($this->id_tipodiagnosticoaudiologia);
					if ($this->nombrediagnotico->Exportable) $Doc->ExportCaption($this->nombrediagnotico);
					if ($this->resultadodiagnostico->Exportable) $Doc->ExportCaption($this->resultadodiagnostico);
					if ($this->tipotratamiento->Exportable) $Doc->ExportCaption($this->tipotratamiento);
					if ($this->tipoderivacion->Exportable) $Doc->ExportCaption($this->tipoderivacion);
					if ($this->nombreespcialidad->Exportable) $Doc->ExportCaption($this->nombreespcialidad);
					if ($this->observaciones1->Exportable) $Doc->ExportCaption($this->observaciones1);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
				} else {
					if ($this->id_neonato->Exportable) $Doc->ExportCaption($this->id_neonato);
					if ($this->apellidopaterno->Exportable) $Doc->ExportCaption($this->apellidopaterno);
					if ($this->apellidomaterno->Exportable) $Doc->ExportCaption($this->apellidomaterno);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->ci->Exportable) $Doc->ExportCaption($this->ci);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->dias->Exportable) $Doc->ExportCaption($this->dias);
					if ($this->semanas->Exportable) $Doc->ExportCaption($this->semanas);
					if ($this->meses->Exportable) $Doc->ExportCaption($this->meses);
					if ($this->discapacidad->Exportable) $Doc->ExportCaption($this->discapacidad);
					if ($this->resultado->Exportable) $Doc->ExportCaption($this->resultado);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->tipoprueba->Exportable) $Doc->ExportCaption($this->tipoprueba);
					if ($this->resultadprueba->Exportable) $Doc->ExportCaption($this->resultadprueba);
					if ($this->recomendacion->Exportable) $Doc->ExportCaption($this->recomendacion);
					if ($this->id_tipodiagnosticoaudiologia->Exportable) $Doc->ExportCaption($this->id_tipodiagnosticoaudiologia);
					if ($this->nombrediagnotico->Exportable) $Doc->ExportCaption($this->nombrediagnotico);
					if ($this->resultadodiagnostico->Exportable) $Doc->ExportCaption($this->resultadodiagnostico);
					if ($this->tipotratamiento->Exportable) $Doc->ExportCaption($this->tipotratamiento);
					if ($this->tipoderivacion->Exportable) $Doc->ExportCaption($this->tipoderivacion);
					if ($this->nombreespcialidad->Exportable) $Doc->ExportCaption($this->nombreespcialidad);
					if ($this->observaciones1->Exportable) $Doc->ExportCaption($this->observaciones1);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id_neonato->Exportable) $Doc->ExportField($this->id_neonato);
						if ($this->apellidopaterno->Exportable) $Doc->ExportField($this->apellidopaterno);
						if ($this->apellidomaterno->Exportable) $Doc->ExportField($this->apellidomaterno);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->dias->Exportable) $Doc->ExportField($this->dias);
						if ($this->semanas->Exportable) $Doc->ExportField($this->semanas);
						if ($this->meses->Exportable) $Doc->ExportField($this->meses);
						if ($this->discapacidad->Exportable) $Doc->ExportField($this->discapacidad);
						if ($this->resultado->Exportable) $Doc->ExportField($this->resultado);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->tipoprueba->Exportable) $Doc->ExportField($this->tipoprueba);
						if ($this->resultadprueba->Exportable) $Doc->ExportField($this->resultadprueba);
						if ($this->recomendacion->Exportable) $Doc->ExportField($this->recomendacion);
						if ($this->id_tipodiagnosticoaudiologia->Exportable) $Doc->ExportField($this->id_tipodiagnosticoaudiologia);
						if ($this->nombrediagnotico->Exportable) $Doc->ExportField($this->nombrediagnotico);
						if ($this->resultadodiagnostico->Exportable) $Doc->ExportField($this->resultadodiagnostico);
						if ($this->tipotratamiento->Exportable) $Doc->ExportField($this->tipotratamiento);
						if ($this->tipoderivacion->Exportable) $Doc->ExportField($this->tipoderivacion);
						if ($this->nombreespcialidad->Exportable) $Doc->ExportField($this->nombreespcialidad);
						if ($this->observaciones1->Exportable) $Doc->ExportField($this->observaciones1);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
					} else {
						if ($this->id_neonato->Exportable) $Doc->ExportField($this->id_neonato);
						if ($this->apellidopaterno->Exportable) $Doc->ExportField($this->apellidopaterno);
						if ($this->apellidomaterno->Exportable) $Doc->ExportField($this->apellidomaterno);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->ci->Exportable) $Doc->ExportField($this->ci);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->dias->Exportable) $Doc->ExportField($this->dias);
						if ($this->semanas->Exportable) $Doc->ExportField($this->semanas);
						if ($this->meses->Exportable) $Doc->ExportField($this->meses);
						if ($this->discapacidad->Exportable) $Doc->ExportField($this->discapacidad);
						if ($this->resultado->Exportable) $Doc->ExportField($this->resultado);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->tipoprueba->Exportable) $Doc->ExportField($this->tipoprueba);
						if ($this->resultadprueba->Exportable) $Doc->ExportField($this->resultadprueba);
						if ($this->recomendacion->Exportable) $Doc->ExportField($this->recomendacion);
						if ($this->id_tipodiagnosticoaudiologia->Exportable) $Doc->ExportField($this->id_tipodiagnosticoaudiologia);
						if ($this->nombrediagnotico->Exportable) $Doc->ExportField($this->nombrediagnotico);
						if ($this->resultadodiagnostico->Exportable) $Doc->ExportField($this->resultadodiagnostico);
						if ($this->tipotratamiento->Exportable) $Doc->ExportField($this->tipotratamiento);
						if ($this->tipoderivacion->Exportable) $Doc->ExportField($this->tipoderivacion);
						if ($this->nombreespcialidad->Exportable) $Doc->ExportField($this->nombreespcialidad);
						if ($this->observaciones1->Exportable) $Doc->ExportField($this->observaciones1);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
