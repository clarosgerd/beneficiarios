<?php
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "userfn14.php" ?>
<?php
ew_Header(FALSE, "utf-8", TRUE);
$lookup = new cewlookup;
$lookup->Page_Main();

//
// Page class for lookup
//
class cewlookup {

	// Page ID
	var $PageID = "lookup";

	// Project ID
	var $ProjectID = "{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}";

	// Page object name
	var $PageObjName = "lookup";

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		return ew_CurrentPage() . "?";
	}

	// Connection
	var $Connection;

	// Get record count
	function GetRecordCount($sql) {
		$cnt = -1;
		$rs = NULL;
		$sql = preg_replace('/\/\*BeginOrderBy\*\/[\s\S]+\/\*EndOrderBy\*\//', "", $sql); // Remove ORDER BY clause (MSSQL)
		$pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
		if (preg_match($pattern, $sql) && !preg_match('/^\s*select\s+distinct\s+/i', $sql)) {
			$sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sql);
			$rs = $this->Connection->Execute($sqlwrk);
		}
		if (!$rs) {
			$sqlwrk = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
			$rs = $this->Connection->Execute($sqlwrk);
		}
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$cnt = $rs->fields[0];
			$rs->Close();
			return intval($cnt);
		}

		// Unable to get count, get record count directly
		if ($rs = $this->Connection->Execute($sql)) {
			$cnt = $rs->RecordCount();
			$rs->Close();
			return intval($cnt);
		}
		return $cnt;
	}

	// Main
	function Page_Main() {
		global $Language;
		$post = $_POST;
		$req = array_merge($_GET, $post);
		if (count($post) == 0)
			die('{"Result": "Missing post data."}');
		$Language = new cLanguage("", @$post["lang"]);
		$GLOBALS["Page"] = &$this;

		//$sql = $qs->getValue("s");
		$sql = @$post["s"];
		$sql = ew_Decrypt($sql);
		if ($sql == "")
			die('{"Result": "Missing SQL."}');
		$dbid = @$post["d"];
		$this->Connection = ew_Connect($dbid);

		// Global Page Loading event (in userfn*.php)
		Page_Loading();
		$filters = "";
		if (ew_ContainsStr($sql, "{filter}")) {
			$ar = preg_grep('/^f\d+$/', array_keys($post));
			foreach ($ar as $key) {

				// Get the filter values (for "IN")
				$filter = ew_Decrypt(@$post[$key]);
				if ($filter <> "") {
					$i = preg_replace('/^f/', '', $key);
					$value = @$post["v" . $i];
					if ($value == "") {
						if ($i > 0) // Empty parent field

							//continue; // Allow
							ew_AddFilter($filters, "1=0"); // Disallow
						continue;
					}
					$arValue = explode(EW_LOOKUP_FILTER_VALUE_SEPARATOR, $value);
					$fldtype = intval(@$post["t" . $i]);
					$flddatatype = ew_FieldDataType($fldtype);
					$bValidData = TRUE;
					for ($j = 0, $cnt = count($arValue); $j < $cnt; $j++) {
						if ($flddatatype == EW_DATATYPE_NUMBER && !is_numeric($arValue[$j])) {
							$bValidData = FALSE;
							break;
						} else {
							$arValue[$j] = ew_QuotedValue($arValue[$j], $flddatatype, $dbid);
						}
					}
					if ($bValidData)
						$filter = str_replace("{filter_value}", implode(",", $arValue), $filter);
					else
						$filter = "1=0";
					$fn = @$post["fn" . $i];
					if ($fn == "" || !function_exists($fn)) $fn = "ew_AddFilter";
					$fn($filters, $filter);
				}
			}
			$sql = str_replace("{filter}", ($filters <> "") ? $filters : "1=1", $sql);
		}

		// Get query value (for "LIKE" or "=")
		$value = ew_AdjustSql(@$req["q"], $dbid); // Get query value from querystring/post
		if ($value <> "") {
			$sql = preg_replace('/ LIKE \'(%)?\{query_value\}%\'/', ew_Like('\'$1{query_value}%\'', $dbid), $sql);
			$sql = str_replace("{query_value}", $value, $sql);
		}

		// Replace {query_value_n}
		preg_match_all('/\{query_value_(\d+)\}/', $sql, $out);
		$cnt = count($out[0]);
		for ($i = 0; $i < $cnt; $i++) {
			$j = $out[1][$i];
			$v = ew_AdjustSql(@$post["q" . $j], $dbid);
			$sql = str_replace("{query_value_" . $j . "}", $v, $sql);
		}

		// Page size
		$max = ($filters == "") ? intval(@$req["n"]) : -1;
		$isAutoSuggest = ew_SameText(@$post["ajax"], "autosuggest");
		if ($isAutoSuggest && $max < 1)
			$max = EW_AUTO_SUGGEST_MAX_ENTRIES;
		if ($max < 1)
			$max = -1;

		// Offset
		$offset = -1;
		if (isset($req["start"])) { // Get start from GET/POST
			$start = intval($req["start"]); 
			if ($start > -1)
				$offset = $start;
		} elseif (isset($req["page"])) { // Get page number from GET/POST
			$page = intval($req["page"]); 
			if ($page > 0 && $max > 0)
				$offset = ($page - 1) * $max;
		}

		// Record count for AutoSuggest
		$cnt = ($isAutoSuggest) ? $this->GetRecordCount($sql) : -1;

		// Get lookup values
		$dbtype = ew_GetConnectionType($dbid);
		if ($dbtype == "MSSQL") {
			$inputarr = array("_hasOrderBy" => preg_match('/\/\*BeginOrderBy\*\/[\s\S]+\/\*EndOrderBy\*\//', $sql) == 1); // Has ORDER BY clause (MSSQL)
			$rs = $this->Connection->SelectLimit($sql, $max, $offset, $inputarr);
		} else {
			$rs = $this->Connection->SelectLimit($sql, $max, $offset);
		}
		if (!$rs)
			die(json_encode(array("Result" => "Failed to execute SQL: " . ew_ConvertToUtf8($sql))));
		$fldcnt = $rs->FieldCount();

		// Clean output buffer
		if (ob_get_length())
			ob_clean();

		// Format date
		$ardt = array();
		for ($j = 0; $j < $fldcnt; $j++)
			$ardt[$j] = @$post["df" . $j];

		// Get result
		$rsarr = array();
		while (!$rs->EOF) {
			$row = $rs->fields;
			for ($j = 0; $j < $fldcnt; $j++) {
				$str = strval($row[$j]);
				$str = ew_ConvertToUtf8($str);
				if ($ardt[$j] != "" && intval($ardt[$j]) >= 0) // Format date
					$str = ew_FormatDateTime($str, $ardt[$j]);
				if (isset($post["keepCRLF"])) {
					$str = str_replace(array("\r", "\n", "\t"), array("\\r", "\\n", "\\t"), $str);
				} else {
					$str = str_replace(array("\r", "\n", "\t"), array(" ", " ", " "), $str);
				}
				$row[$j] = $str;
			}
			$rsarr[] = $row;
			$rs->MoveNext();
		}
		$rs->Close();
		$result = ew_ArrayToJson($rsarr);
		if ($isAutoSuggest) {
			$result = '{"Result": "OK", "Records": ' . $result . ', "TotalRecordCount": ' . $cnt;
			if (EW_DEBUG_ENABLED)
				$result .= ', "SQL": "' . str_replace("\"",  "\\\"", ew_ConvertToUtf8($sql)) . '"';
			$result .= '}';
		}

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Close connection
		ew_CloseConn();

		// Clear output
		if (ob_get_length())
			ob_clean();

		// Output
		echo $result;
	}
}
?>
