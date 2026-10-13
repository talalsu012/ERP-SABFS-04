<?php
error_reporting(E_ALL);
ini_set('display_errors', false);
ini_set('html_errors', false); 

session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include "ewcfg7.php" ?>
<?php include "ewmysql7.php" ?>
<?php include "phpfn7.php" ?>
<?php include "studentsinfo.php" ?>
<?php include "authorizeinfo.php" ?>
<?php include "userfn7.php" ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>
<?php

// Create page object
$students_list = new cstudents_list();
$Page =& $students_list;

// Page init
$students_list->Page_Init();

// Page main
$students_list->Page_Main();
?>
<?php include "header.php" ?>
<?php if ($students->Export == "") { ?>
<script type="text/javascript">
<!--

// Create page object
var students_list = new ew_Page("students_list");

// page properties
students_list.PageID = "list"; // page ID
students_list.FormID = "fstudentslist"; // form ID
var EW_PAGE_ID = students_list.PageID; // for backward compatibility

// extend page with validate function for search
students_list.ValidateSearch = function(fobj) {
	ew_PostAutoSuggest(fobj);
	if (this.ValidateRequired) {
		var infix = "";

		// Call Form Custom Validate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}
	for (var i=0; i<fobj.elements.length; i++) {
		var elem = fobj.elements[i];
		if (elem.name.substring(0,2) == "s_" || elem.name.substring(0,3) == "sv_")
			elem.value = "";
	}
	return true;
}

// extend page with Form_CustomValidate function
students_list.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
students_list.SelectAllKey = function(elem) {
	ew_SelectAll(elem);
	ew_ClickAll(elem);
}
<?php if (EW_CLIENT_VALIDATE) { ?>
students_list.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
students_list.ValidateRequired = false; // no JavaScript validation
<?php } ?>

// search highlight properties
students_list.ShowHighlightText = ewLanguage.Phrase("ShowHighlight"); 
students_list.HideHighlightText = ewLanguage.Phrase("HideHighlight");

//-->
</script>
<script type="text/javascript">
<!--
var ew_DHTMLEditors = [];

//-->
</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js"); 
//-->

</script>
<?php } ?>
<?php if ($students->Export == "") { ?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$students_list->lTotalRecs = $students->SelectRecordCount();
	} else {
		if ($rs = $students_list->LoadRecordset())
			$students_list->lTotalRecs = $rs->RecordCount();
	}
	$students_list->lStartRec = 1;
	if ($students_list->lDisplayRecs <= 0 || ($students->Export <> "" && $students->ExportAll)) // Display all records
		$students_list->lDisplayRecs = $students_list->lTotalRecs;
	if (!($students->Export <> "" && $students->ExportAll))
		$students_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$rs = $students_list->LoadRecordset($students_list->lStartRec-1, $students_list->lDisplayRecs);
?>
<p><a href="" class="name_page"><?php echo $students->TableCaption() ?></a>
<?php if ($students->Export == "" && $students->CurrentAction == "") { ?>
&nbsp;&nbsp;<a href="<?php echo $students_list->ExportPrintUrl ?>" class="view">Printer</a>
&nbsp;&nbsp;<a href="<?php echo $students_list->ExportHtmlUrl ?>" class="view">Html</a>
&nbsp;&nbsp;<a href="<?php echo $students_list->ExportExcelUrl ?>" class="view">Excel</a>
<?php } ?>
</span></p>
<?php if ($Security->CanSearch()) { ?>
<?php if ($students->Export == "" && $students->CurrentAction == "") { ?>
<a href="javascript:ew_ToggleSearchPanel(students_list);" style="text-decoration: none;"><img id="students_list_SearchImage" src="images/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="students_list_SearchPanel">
<form name="fstudentslistsrch" id="fstudentslistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>" onsubmit="return students_list.ValidateSearch(this);">
<input type="hidden" id="t" name="t" value="students">
<?php
if ($gsSearchError == "")
	$students_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$students->RowType = EW_ROWTYPE_SEARCH;

// Render row
$students_list->RenderRow();
?>
<table class="ewBasicSearch">
	<tr>
		<td><span class="phpmaker"><?php echo $students->fac->FldCaption() ?></span></td>
		<td><span class="ewSearchOpr"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_fac" id="z_fac" value="LIKE"></span></td>
		<td>			
			<div style="white-space: nowrap;">
				<span class="phpmaker" style="float: left;">
<?php $students->fac->EditAttrs["onchange"] = "ew_UpdateOpt('x_dept','x_fac',students_list.ar_x_dept); " . @$students->fac->EditAttrs["onchange"]; ?>
<select id="x_fac" name="x_fac" title="<?php echo $students->fac->FldTitle() ?>"<?php echo $students->fac->EditAttributes() ?>>
<?php
if (is_array($students->fac->EditValue)) {
	$arwrk = $students->fac->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($students->fac->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
			</div>
		</td>
	</tr>
	<tr>
		<td><span class="phpmaker"><?php echo $students->dept->FldCaption() ?></span></td>
		<td><span class="ewSearchOpr"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_dept" id="z_dept" value="LIKE"></span></td>
		<td>			
			<div style="white-space: nowrap;">
				<span class="phpmaker" style="float: left;">
<select id="x_dept" name="x_dept" title="<?php echo $students->dept->FldTitle() ?>"<?php echo $students->dept->EditAttributes() ?>>
<?php
if (is_array($students->dept->EditValue)) {
	$arwrk = $students->dept->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($students->dept->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php
$jswrk = "";
if (is_array($students->dept->EditValue)) {
	$arwrk = $students->dept->EditValue;
	$arwrkcnt = count($arwrk);
	for ($rowcntwrk = 1; $rowcntwrk < $arwrkcnt; $rowcntwrk++) {
		if ($jswrk <> "") $jswrk .= ",";
		$jswrk .= "['" . ew_JsEncode($arwrk[$rowcntwrk][0]) . "',"; // Value
		$jswrk .= "'" . ew_JsEncode($arwrk[$rowcntwrk][1]) . "',"; // Display field 1
		$jswrk .= "'" . ew_JsEncode($arwrk[$rowcntwrk][2]) . "',"; // Display field 2
		$jswrk .= "'" . ew_JsEncode($arwrk[$rowcntwrk][3]) . "']"; // Filter field
	}
}
?>
<script type="text/javascript">
<!--
students_list.ar_x_dept = [<?php echo $jswrk ?>];

//-->
</script>
</span>
			</div>
		</td>
	</tr>
	<tr>
		<td><span class="phpmaker"><?php echo $students->admission->FldCaption() ?></span></td>
		<td><span class="ewSearchOpr"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admission" id="z_admission" value="LIKE"></span></td>
		<td>			
			<div style="white-space: nowrap;">
				<span class="phpmaker" style="float: left;">
<select id="x_admission" name="x_admission" title="<?php echo $students->admission->FldTitle() ?>"<?php echo $students->admission->EditAttributes() ?>>
<?php
if (is_array($students->admission->EditValue)) {
	$arwrk = $students->admission->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($students->admission->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
			</div>
		</td>
	</tr>
	<tr>
		<td><span class="phpmaker"><?php echo $students->category->FldCaption() ?></span></td>
		<td><span class="ewSearchOpr"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_category" id="z_category" value="LIKE"></span></td>
		<td>			
			<div style="white-space: nowrap;">
				<span class="phpmaker" style="float: left;">
<select id="x_category" name="x_category" title="<?php echo $students->category->FldTitle() ?>"<?php echo $students->category->EditAttributes() ?>>
<?php
if (is_array($students->category->EditValue)) {
	$arwrk = $students->category->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($students->category->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
			</div>
		</td>
	</tr>
	<tr>
		<td><span class="phpmaker"><?php echo $students->class->FldCaption() ?></span></td>
		<td><span class="ewSearchOpr"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_class" id="z_class" value="LIKE"></span></td>
		<td>			
			<div style="white-space: nowrap;">
				<span class="phpmaker" style="float: left;">
<select id="x_class" name="x_class" title="<?php echo $students->class->FldTitle() ?>"<?php echo $students->class->EditAttributes() ?>>
<?php
if (is_array($students->class->EditValue)) {
	$arwrk = $students->class->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($students->class->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
			</div>
		</td>
	</tr>
	<tr>
		<td><span class="phpmaker"><?php echo $students->xstatus->FldCaption() ?></span></td>
		<td><span class="ewSearchOpr"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_xstatus" id="z_xstatus" value="LIKE"></span></td>
		<td>			
			<div style="white-space: nowrap;">
				<span class="phpmaker" style="float: left;">
<select id="x_xstatus" name="x_xstatus" title="<?php echo $students->xstatus->FldTitle() ?>"<?php echo $students->xstatus->EditAttributes() ?>>
<?php
if (is_array($students->xstatus->EditValue)) {
	$arwrk = $students->xstatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($students->xstatus->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
			</div>
		</td>
	</tr>
	
</table>
<table class="ewBasicSearch">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($students->getSessionBasicSearchKeyword()) ?>">
			<input type="Submit" name="Submit" id="Submit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>"><img src="" alt="" />&nbsp;
 			<a href="<?php echo $students_list->PageUrl() ?>cmd=reset" class="view"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
			<a href="studentssrch.php" class="view"><?php echo $Language->Phrase("AdvancedSearch") ?></a>&nbsp;
			<?php if ($students_list->sSrchWhere <> "" && $students_list->lTotalRecs > 0) { ?>
			<a href="javascript:void(0);" onclick="ew_ToggleHighlight(students_list, this, '<?php echo $students->HighlightName() ?>');" class="view"><?php echo $Language->Phrase("HideHighlight") ?></a>
			<?php } ?>
		</span></td>
	</tr>
	<tr>
	<td><span class="phpmaker"><label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value=""<?php if ($students->getSessionBasicSearchType() == "") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($students->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($students->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label></span></td>
	</tr>
</table>
</form>
</div>
<?php } ?>
<?php } ?>
<?php
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
$students_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($students->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($students->CurrentAction <> "gridadd" && $students->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($students_list->Pager)) $students_list->Pager = new cNumericPager($students_list->lStartRec, $students_list->lDisplayRecs, $students_list->lTotalRecs, $students_list->lRecRange) ?>
<?php if ($students_list->Pager->RecordCount > 0) { ?>
<div class="pagination">
<ul>
	<?php if ($students_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->FirstButton->Start ?>" class="button"><?php echo $Language->Phrase("PagerFirst") ?></a></li>&nbsp;
	<?php } ?>
	<?php if ($students_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->PrevButton->Start ?>" class="button"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>&nbsp;
	<?php } ?>
	<?php foreach ($students_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><li><a href="<?php echo $students_list->PageUrl() ?>start=<?php echo $PagerItem->Start; ?>" ><?php } ?><?php if (!$PagerItem->Enabled) { echo "<li><a href='' class='currentpage'>";} echo $PagerItem->Text ?><?php if ($PagerItem->Enabled or !$PagerItem->Enabled) { ?></a></li><?php } ?>&nbsp;
			<?php } ?>
	<?php if ($students_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->NextButton->Start ?>" class="button"><?php echo $Language->Phrase("PagerNext") ?></a></li>&nbsp;
	<?php } ?>
	<?php if ($students_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->LastButton->Start ?>" class="button"><?php echo $Language->Phrase("PagerLast") ?></a></li>&nbsp;
	<?php } ?>
</ul>
</div>
</td>
<td nowrap>
<a href="" class="view">
	<?php if ($students_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $students_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $students_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $students_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($Security->CanList()) { ?>
	<?php if ($students_list->sSrchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoPermission") ?>
	<?php } ?>
<?php } ?>
</a>

</span>
		</td>
<?php if ($students_list->lTotalRecs > 0) { ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><table border="0" cellspacing="0" cellpadding="0"><tr><td><?php echo $Language->Phrase("RecordsPerPage") ?>&nbsp;</td><td>
<input type="hidden" id="t" name="t" value="students">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" id="<?php echo EW_TABLE_REC_PER_PAGE ?>" onchange="this.form.submit();" class="phpmaker">
<option value="5"<?php if ($students_list->lDisplayRecs == 5) { ?> selected="selected"<?php } ?>>5</option>
<option value="10"<?php if ($students_list->lDisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($students_list->lDisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="40"<?php if ($students_list->lDisplayRecs == 40) { ?> selected="selected"<?php } ?>>40</option>
<option value="80"<?php if ($students_list->lDisplayRecs == 80) { ?> selected="selected"<?php } ?>>80</option>
<option value="100"<?php if ($students_list->lDisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="400"<?php if ($students_list->lDisplayRecs == 400) { ?> selected="selected"<?php } ?>>400</option>
<option value="ALL"<?php if ($students->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select></td></tr></table>
		</td>
<?php } ?>
	</tr>
</table>
</form>
<?php } ?>
<table cellspacing="0" rowhighlightclass="ewTableHighlightRow" rowselectclass="ewTableSelectRow" roweditclass="ewTableEditRow" class="ewTable ewTableSeparate">
<thead><!-- Table header -->
<tr><td colspan=11>-</td><td>
<?php if ($Security->CanAdd()) { ?>
<a href="<?php echo $students_list->AddUrl ?>" class="button add"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
</td><td>
<?php if ($students_list->lTotalRecs > 0) { ?>
<?php if ($Security->CanDelete()) { ?>
<a href="" onclick="ew_SubmitSelected(document.fstudentslist, '<?php echo $students_list->MultiDeleteUrl ?>');return false;" class="button delete"><?php echo $Language->Phrase("DeleteSelectedLink") ?></a>&nbsp;&nbsp;
<?php } ?>
</td><td>
<?php if ($Security->CanEdit()) { ?>
<a href="" onclick="ew_SubmitSelected(document.fstudentslist, '<?php echo $students_list->MultiUpdateUrl ?>');return false;" class="button update"><?php echo $Language->Phrase("UpdateSelectedLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<form name="fstudentslist" id="fstudentslist" class="ewForm" action="" method="post">
<div id="gmp_students" class="ewGridMiddlePanel">
<?php if ($students_list->lTotalRecs > 0) { ?>
</td></tr>
<?php echo $students->TableCustomInnerHtml ?>

	<tr class="ewTableHeader">
<?php

// Render list options
$students_list->RenderListOptions();

// Render list options (header, left)
$students_list->ListOptions->Render("header", "left");
?>
<?php if ($students->name01->Visible) { // name01 ?>
	<?php if ($students->SortUrl($students->name01) == "") { ?>
		<td><?php echo $students->name01->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->name01) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->name01->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($students->name01->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->name01->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->universityno->Visible) { // universityno ?>
	<?php if ($students->SortUrl($students->universityno) == "") { ?>
		<td><?php echo $students->universityno->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->universityno) ?>',1);">

			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->universityno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($students->universityno->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->universityno->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
	
<?php if ($students->stady->Visible) { // stady ?>
	<?php if ($students->SortUrl($students->stady) == "") { ?>
		<td><?php echo $students->stady->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->stady) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->stady->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->stady->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->stady->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>	
<?php if ($students->fac->Visible) { // fac ?>
	<?php if ($students->SortUrl($students->fac) == "") { ?>
		<td><?php echo $students->fac->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->fac) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->fac->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->fac->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->fac->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->dept->Visible) { // dept ?>
	<?php if ($students->SortUrl($students->dept) == "") { ?>
		<td><?php echo $students->dept->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->dept) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->dept->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->dept->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->dept->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->admission->Visible) { // admission ?>
	<?php if ($students->SortUrl($students->admission) == "") { ?>
		<td><?php echo $students->admission->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->admission) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->admission->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->admission->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->admission->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->class->Visible) { // class ?>
	<?php if ($students->SortUrl($students->class) == "") { ?>
		<td><?php echo $students->class->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->class) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->class->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->class->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->class->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->active->Visible) { // active ?>
	<?php if ($students->SortUrl($students->active) == "") { ?>
		<td><?php echo $students->active->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->active) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->active->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->active->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->active->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->xstatus->Visible) { // xstatus ?>
	<?php if ($students->SortUrl($students->xstatus) == "") { ?>
		<td><?php echo $students->xstatus->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->xstatus) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->xstatus->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->xstatus->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->xstatus->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->amt->Visible) { // amt ?>
	<?php if ($students->SortUrl($students->amt) == "") { ?>
		<td><?php echo $students->amt->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->amt) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->amt->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->amt->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->amt->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->allfees->Visible) { // allfees ?>
	<?php if ($students->SortUrl($students->allfees) == "") { ?>
		<td><?php echo $students->allfees->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->allfees) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->allfees->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->allfees->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->allfees->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->xmod->Visible) { // xmod ?>
	<?php if ($students->SortUrl($students->xmod) == "") { ?>
		<td><?php echo $students->xmod->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->xmod) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->xmod->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->xmod->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->xmod->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($students->pay->Visible) { // pay ?>
	<?php if ($students->SortUrl($students->pay) == "") { ?>
		<td><?php echo $students->pay->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $students->SortUrl($students->pay) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $students->pay->FldCaption() ?></td><td style="width: 10px;"><?php if ($students->pay->getSort() == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif ($students->pay->getSort() == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
	
<?php

// Render list options (header, right)
$students_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<?php
if ($students->ExportAll && $students->Export <> "") {
	$students_list->lStopRec = $students_list->lTotalRecs;
} else {
	$students_list->lStopRec = $students_list->lStartRec + $students_list->lDisplayRecs - 1; // Set the last record to display
}
$students_list->lRecCount = $students_list->lStartRec - 1;
if ($rs && !$rs->EOF) {
	$rs->MoveFirst();
	if (!$bSelectLimit && $students_list->lStartRec > 1)
		$rs->Move($students_list->lStartRec - 1);
}

// Initialize aggregate
$students->RowType = EW_ROWTYPE_AGGREGATEINIT;
$students_list->RenderRow();
$students_list->lRowCnt = 0;
while (($students->CurrentAction == "gridadd" || !$rs->EOF) &&
	$students_list->lRecCount < $students_list->lStopRec) {
	$students_list->lRecCount++;
	if (intval($students_list->lRecCount) >= intval($students_list->lStartRec)) {
		$students_list->lRowCnt++;

	// Init row class and style
	$students->CssClass = "";
	$students->CssStyle = "";
	$students->RowAttrs = array('onmouseover'=>'ew_MouseOver(event, this);', 'onmouseout'=>'ew_MouseOut(event, this);', 'onclick'=>'ew_Click(event, this);');
	if ($students->CurrentAction == "gridadd") {
		$students_list->LoadDefaultValues(); // Load default values
	} else {
		$students_list->LoadRowValues($rs); // Load row values
	}
	$students->RowType = EW_ROWTYPE_VIEW; // Render view

	// Render row
	$students_list->RenderRow();

	// Render list options
	$students_list->RenderListOptions();
?>
	<tr<?php echo $students->RowAttributes() ?>>
<?php

// Render list options (body, left)
$students_list->ListOptions->Render("body", "left");
?>
	<?php if ($students->name01->Visible) { // name01 ?>
		<td<?php echo $students->name01->CellAttributes() ?>>
<div<?php echo $students->name01->ViewAttributes() ?>><?php echo $students->name01->ListViewValue() ?></div>
</td>
	<?php } ?>
	<?php if ($students->universityno->Visible) { // universityno ?>
		<td<?php echo $students->universityno->CellAttributes() ?>>
<div<?php echo $students->universityno->ViewAttributes() ?>><?php echo $students->universityno->ListViewValue() ?></div>
</td>
	<?php } ?>
	
	<?php if ($students->stady->Visible) { // stady ?>
		<td<?php echo $students->stady->CellAttributes() ?>>
<div<?php echo $students->stady->ViewAttributes() ?>><?php echo $students->stady->ListViewValue() ?></div>
</td>
	<?php } ?>
	<?php if ($students->fac->Visible) { // fac ?>
		<td<?php echo $students->fac->CellAttributes() ?>>
<div<?php echo $students->fac->ViewAttributes() ?>><?php echo $students->fac->ListViewValue() ?></div>
</td>
	<?php } ?>
	<?php if ($students->dept->Visible) { // dept ?>
		<td<?php echo $students->dept->CellAttributes() ?>>
<div<?php echo $students->dept->ViewAttributes() ?>><?php echo $students->dept->ListViewValue() ?></div>
</td>
	<?php } ?>
	<?php if ($students->admission->Visible) { // admission ?>
		<td<?php echo $students->admission->CellAttributes() ?>>
<div<?php echo $students->admission->ViewAttributes() ?>><?php echo $students->admission->ListViewValue() ?></div>
</td>
	<?php } ?>
	<?php if ($students->class->Visible) { // class ?>
		<td<?php echo $students->class->CellAttributes() ?>>
<div<?php echo $students->class->ViewAttributes() ?>><?php echo $students->class->ListViewValue() ?></div>
</td>
	<?php } ?>
	<?php if ($students->active->Visible) { // active ?>
		<td<?php echo $students->active->CellAttributes() ?>>
<div<?php echo $students->active->ViewAttributes() ?>><?php echo $students->active->ListViewValue() ?></div>
</td>
	<?php } ?>
	<?php if ($students->xstatus->Visible) { // xstatus ?>
		<td<?php echo $students->xstatus->CellAttributes() ?>>
<div<?php echo $students->xstatus->ViewAttributes() ?>><?php echo $students->xstatus->ListViewValue() ?></div>
</td>
	<?php } ?>
	<?php if ($students->amt->Visible) { // amt ?>
		<td<?php echo $students->amt->CellAttributes() ?>>
<div<?php echo $students->amt->ViewAttributes() ?>><?php echo $students->amt->ListViewValue() ?></div>
</td>
	<?php } ?>
		<?php if ($students->allfees->Visible) { // allfees ?>
		<td<?php echo $students->allfees->CellAttributes() ?>>
<div<?php echo $students->allfees->ViewAttributes() ?>><?php echo $students->allfees->ListViewValue() ?></div>
</td>
	<?php } ?>
		<?php if ($students->xmod->Visible) { // xmod ?>
		<td<?php echo $students->xmod->CellAttributes() ?>>
<div<?php echo $students->xmod->ViewAttributes() ?>><?php echo $students->xmod->ListViewValue() ?></div>
</td>
	<?php } ?>
		<?php if ($students->pay->Visible) { // pay ?>
		<td<?php echo $students->pay->CellAttributes() ?>>
<div<?php echo $students->pay->ViewAttributes() ?>><?php echo $students->pay->ListViewValue() ?></div>
</td>
	<?php } ?>

<?php

// Render list options (body, right)
$students_list->ListOptions->Render("body", "right");
?>
	</tr>
<?php
	}
	if ($students->CurrentAction <> "gridadd")
		$rs->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($rs)
	$rs->Close();
?>
</td></tr></table>
<?php if ($students->Export == "" && $students->CurrentAction == "") { ?>
<script type="text/javascript">
<!--
ew_ToggleSearchPanel(students_list); // Init search panel as collapsed

//-->
</script>
<script language="JavaScript" type="text/javascript">
<!--
ew_UpdateOpts([['x_dept','x_fac',students_list.ar_x_dept]]);

//-->
</script>
<?php } ?>
<?php if ($students->Export == "") { ?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php } ?>
<?php include "footer.php" ?>
<?php
$students_list->Page_Terminate();
?>
<?php

//
// Page class
//
class cstudents_list {

	// Page ID
	var $PageID = 'list';

	// Table name
	var $TableName = 'students';

	// Page object name
	var $PageObjName = 'students_list';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		global $students;
		if ($students->UseTokenInUrl) $PageUrl .= "t=" . $students->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		if (@$_SESSION[EW_SESSION_MESSAGE] <> "") { // Append
			$_SESSION[EW_SESSION_MESSAGE] .= "<br>" . $v;
		} else {
			$_SESSION[EW_SESSION_MESSAGE] = $v;
		}
	}

	// Show message
	function ShowMessage() {
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage);
		if ($sMessage <> "") { // Message in Session, display
			echo "<p><span class=\"ewMessage\">" . $sMessage . "</span></p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm, $students;
		if ($students->UseTokenInUrl) {
			if ($objForm)
				return ($students->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($students->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function cstudents_list() {
		global $conn, $Language;

		// Language object
		$Language = new cLanguage();

		// Table object (students)
		$GLOBALS["students"] = new cstudents();

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->AddUrl = $GLOBALS["students"]->AddUrl();
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "studentsdelete.php";
		$this->MultiUpdateUrl = "studentsupdate.php";

		// Table object (authorize)
		$GLOBALS['authorize'] = new cauthorize();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'students', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new cTimer();

		// Open connection
		$conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		global $students;

		// User profile
		$UserProfile = new cUserProfile();
		$UserProfile->LoadProfile(@$_SESSION[EW_SESSION_USER_PROFILE]);

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate("changepwd.php");
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}

		// Update last accessed time
		if ($UserProfile->IsValidUser(session_id())) {
			if (!$Security->IsSysAdmin())
				$UserProfile->SaveProfileToDatabase(CurrentUserName()); // Update last accessed time to user profile
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$students->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$students->Export = $_POST["exporttype"];
		} else {
			$students->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $students->Export; // Get export parameter, used in header
		$gsExportFile = $students->TableVar; // Get export file, used in header
		if (in_array($students->Export, array("html", "email", "excel", "word")))
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1256\">";
		if ($students->Export == "excel") {
			header('Content-Type: application/vnd.ms-excel;charset=windows-1256');
			header('Content-Disposition: attachment; filename=' . $gsExportFile .'.xls');
		}

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		$this->Page_Redirecting($url);
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $lDisplayRecs = 20;
	var $lStartRec;
	var $lStopRec;
	var $lTotalRecs = 0;
	var $lRecRange = 10;
	var $sSrchWhere = ""; // Search WHERE clause
	var $lRecCnt = 0; // Record count
	var $lEditRowCnt;
	var $lRowCnt;
	var $lRowIndex; // Row index
	var $lRecPerRow = 0;
	var $lColCnt = 0;
	var $sDbMasterFilter = ""; // Master filter
	var $sDbDetailFilter = ""; // Detail filter
	var $bMasterRecordExists;	
	var $sMultiSelectKey;
	var $RestoreSearch;
	var $HashValue; // Hash value

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError, $Security, $students;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";
		if ($this->IsPageRequest()) { // Validate request

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up list options
			$this->SetupListOptions();

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setMessage($gsSearchError);

			// Restore search parms from Session
			$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$students->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($students->getRecordsPerPage() <> "") {
			$this->lDisplayRecs = $students->getRecordsPerPage(); // Restore from Session
		} else {
			$this->lDisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build search criteria
		if ($sSrchAdvanced <> "")
			$this->sSrchWhere = ($this->sSrchWhere <> "") ? "(" . $this->sSrchWhere . ") AND (" . $sSrchAdvanced . ")" : $sSrchAdvanced;
		if ($sSrchBasic <> "")
			$this->sSrchWhere = ($this->sSrchWhere <> "") ? "(" . $this->sSrchWhere . ") AND (" . $sSrchBasic. ")" : $sSrchBasic;

		// Call Recordset_Searching event
		$students->Recordset_Searching($this->sSrchWhere);

		// Save search criteria
		if ($this->sSrchWhere <> "") {
			if ($sSrchBasic == "")
				$this->ResetBasicSearchParms();
			if ($sSrchAdvanced == "")
				$this->ResetAdvancedSearchParms();
			$students->setSearchWhere($this->sSrchWhere); // Save to Session
			if (!$this->RestoreSearch) {
				$this->lStartRec = 1; // Reset start record counter
				$students->setStartRecordNumber($this->lStartRec);
			}
		} else {
			$this->sSrchWhere = $students->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		if ($this->sDbDetailFilter <> "")
			$sFilter = ($sFilter <> "") ? "(" . $sFilter . ") AND (" . $this->sDbDetailFilter . ")" : $this->sDbDetailFilter;
		if ($this->sSrchWhere <> "")
			$sFilter = ($sFilter <> "") ? "(" . $sFilter . ") AND (". $this->sSrchWhere . ")" : $this->sSrchWhere;

		// Set up filter in session
		$students->setSessionWhere($sFilter);
		$students->CurrentFilter = "";

		// Export data only
		if (in_array($students->Export, array("html","word","excel","xml","csv","email"))) {
			$this->ExportData();
			if ($students->Export <> "email")
				$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		global $students;
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->lDisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->lDisplayRecs = -1;
				} else {
					$this->lDisplayRecs = 20; // Non-numeric, load default
				}
			}
			$students->setRecordsPerPage($this->lDisplayRecs); // Save to Session

			// Reset start position
			$this->lStartRec = 1;
			$students->setStartRecordNumber($this->lStartRec);
		}
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security, $students;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $students->id, FALSE); // id
		$this->BuildSearchSql($sWhere, $students->name01, FALSE); // name01
		$this->BuildSearchSql($sWhere, $students->name02, FALSE); // name02
		$this->BuildSearchSql($sWhere, $students->universityno, FALSE); // universityno
		$this->BuildSearchSql($sWhere, $students->fac, FALSE); // fac
		$this->BuildSearchSql($sWhere, $students->dept, FALSE); // dept
		$this->BuildSearchSql($sWhere, $students->admission, FALSE); // admission
		$this->BuildSearchSql($sWhere, $students->category, FALSE); // category
		$this->BuildSearchSql($sWhere, $students->specilization, FALSE); // specilization
		$this->BuildSearchSql($sWhere, $students->class, FALSE); // class
		$this->BuildSearchSql($sWhere, $students->classold, FALSE); // classold
		$this->BuildSearchSql($sWhere, $students->indexno, FALSE); // indexno
		$this->BuildSearchSql($sWhere, $students->active, FALSE); // active
		$this->BuildSearchSql($sWhere, $students->adyear, FALSE); // adyear
		$this->BuildSearchSql($sWhere, $students->bplace, FALSE); // bplace
		$this->BuildSearchSql($sWhere, $students->bdate, FALSE); // bdate
		$this->BuildSearchSql($sWhere, $students->sex, FALSE); // sex
		$this->BuildSearchSql($sWhere, $students->nationality, FALSE); // nationality
		$this->BuildSearchSql($sWhere, $students->state, FALSE); // state
		$this->BuildSearchSql($sWhere, $students->certificate, FALSE); // certificate
		$this->BuildSearchSql($sWhere, $students->address, FALSE); // address
		$this->BuildSearchSql($sWhere, $students->telhome, FALSE); // telhome
		$this->BuildSearchSql($sWhere, $students->mobile, FALSE); // mobile
		$this->BuildSearchSql($sWhere, $students->fax, FALSE); // fax
		$this->BuildSearchSql($sWhere, $students->zemail, FALSE); // email
		$this->BuildSearchSql($sWhere, $students->namerel, FALSE); // namerel
		$this->BuildSearchSql($sWhere, $students->relation, FALSE); // relation
		$this->BuildSearchSql($sWhere, $students->addressrel, FALSE); // addressrel
		$this->BuildSearchSql($sWhere, $students->telrel, FALSE); // telrel
		$this->BuildSearchSql($sWhere, $students->acayear, FALSE); // acayear
		$this->BuildSearchSql($sWhere, $students->xstatus, FALSE); // xstatus
		$this->BuildSearchSql($sWhere, $students->gpa01, FALSE); // gpa01
		$this->BuildSearchSql($sWhere, $students->currency, FALSE); // currency
		$this->BuildSearchSql($sWhere, $students->regfees, FALSE); // regfees
		$this->BuildSearchSql($sWhere, $students->fees, FALSE); // fees
		$this->BuildSearchSql($sWhere, $students->support, FALSE); // support
		$this->BuildSearchSql($sWhere, $students->damga, FALSE); // damga
		$this->BuildSearchSql($sWhere, $students->other, FALSE); // other
		$this->BuildSearchSql($sWhere, $students->nopay_inst, FALSE); // nopay_inst
		$this->BuildSearchSql($sWhere, $students->nopay, FALSE); // nopay
		$this->BuildSearchSql($sWhere, $students->pay_01, FALSE); // pay_01
		$this->BuildSearchSql($sWhere, $students->pay_02, FALSE); // pay_02
		$this->BuildSearchSql($sWhere, $students->pay_03, FALSE); // pay_03
		$this->BuildSearchSql($sWhere, $students->pay_04, FALSE); // pay_04
		$this->BuildSearchSql($sWhere, $students->amount_pay, FALSE); // amount_pay
		$this->BuildSearchSql($sWhere, $students->amt, FALSE); // amt
		$this->BuildSearchSql($sWhere, $students->allfees, FALSE); // allfees
		$this->BuildSearchSql($sWhere, $students->xmod, FALSE); // xmod
		$this->BuildSearchSql($sWhere, $students->pay, FALSE); // pay
		$this->BuildSearchSql($sWhere, $students->card, FALSE); // card
		$this->BuildSearchSql($sWhere, $students->stady, FALSE); // stady
		$this->BuildSearchSql($sWhere, $students->ch_fees, FALSE); // ch_fees
		$this->BuildSearchSql($sWhere, $students->remarks, FALSE); // remarks

		// Set up search parm
		if ($sWhere <> "") {
			$this->SetSearchParm($students->id); // id
			$this->SetSearchParm($students->name01); // name01
			$this->SetSearchParm($students->name02); // name02
			$this->SetSearchParm($students->universityno); // universityno
			$this->SetSearchParm($students->fac); // fac
			$this->SetSearchParm($students->dept); // dept
			$this->SetSearchParm($students->admission); // admission
			$this->SetSearchParm($students->category); // category
			$this->SetSearchParm($students->specilization); // specilization
			$this->SetSearchParm($students->class); // class
			$this->SetSearchParm($students->classold); // classold
			$this->SetSearchParm($students->indexno); // indexno
			$this->SetSearchParm($students->active); // active
			$this->SetSearchParm($students->adyear); // adyear
			$this->SetSearchParm($students->bplace); // bplace
			$this->SetSearchParm($students->bdate); // bdate
			$this->SetSearchParm($students->sex); // sex
			$this->SetSearchParm($students->nationality); // nationality
			$this->SetSearchParm($students->state); // state
			$this->SetSearchParm($students->certificate); // certificate
			$this->SetSearchParm($students->address); // address
			$this->SetSearchParm($students->telhome); // telhome
			$this->SetSearchParm($students->mobile); // mobile
			$this->SetSearchParm($students->fax); // fax
			$this->SetSearchParm($students->zemail); // email
			$this->SetSearchParm($students->namerel); // namerel
			$this->SetSearchParm($students->relation); // relation
			$this->SetSearchParm($students->addressrel); // addressrel
			$this->SetSearchParm($students->telrel); // telrel
			$this->SetSearchParm($students->acayear); // acayear
			$this->SetSearchParm($students->xstatus); // xstatus
			$this->SetSearchParm($students->gpa01); // gpa01
			$this->SetSearchParm($students->currency); // currency
			$this->SetSearchParm($students->regfees); // regfees
			$this->SetSearchParm($students->fees); // fees
			$this->SetSearchParm($students->support); // support
			$this->SetSearchParm($students->damga); // damga
			$this->SetSearchParm($students->other); // other
			$this->SetSearchParm($students->nopay_inst); // nopay_inst
			$this->SetSearchParm($students->nopay); // nopay
			$this->SetSearchParm($students->pay_01); // pay_01
			$this->SetSearchParm($students->pay_02); // pay_02
			$this->SetSearchParm($students->pay_03); // pay_03
			$this->SetSearchParm($students->pay_04); // pay_04
			$this->SetSearchParm($students->amount_pay); // amount_pay
			$this->SetSearchParm($students->amt); // amt
			$this->SetSearchParm($students->allfees); // allfees
			$this->SetSearchParm($students->xmod); // xmod
			$this->SetSearchParm($students->pay); // pay
			$this->SetSearchParm($students->card); // card
			$this->SetSearchParm($students->stady); // stady
			$this->SetSearchParm($students->ch_fees); // ch_fees
			$this->SetSearchParm($students->remarks); // remarks
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);		
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		if ($sWrk <> "") {
			if ($Where <> "") $Where .= " AND ";
			$Where .= "(" . $sWrk . ")";
		}
	}

	// Set search parameters
	function SetSearchParm(&$Fld) {
		global $students;
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$students->setAdvancedSearch("x_$FldParm", $FldVal);
		$students->setAdvancedSearch("z_$FldParm", $Fld->AdvancedSearch->SearchOperator); // @$_GET["z_$FldParm"]
		$students->setAdvancedSearch("v_$FldParm", $Fld->AdvancedSearch->SearchCondition); // @$_GET["v_$FldParm"]
		$students->setAdvancedSearch("y_$FldParm", $FldVal2);
		$students->setAdvancedSearch("w_$FldParm", $Fld->AdvancedSearch->SearchOperator2); // @$_GET["w_$FldParm"]
	}

	// Get search parameters
	function GetSearchParm(&$Fld) {
		global $students;
		$FldParm = substr($Fld->FldVar, 2);
		$Fld->AdvancedSearch->SearchValue = $students->GetAdvancedSearch("x_$FldParm");
		$Fld->AdvancedSearch->SearchOperator = $students->GetAdvancedSearch("z_$FldParm");
		$Fld->AdvancedSearch->SearchCondition = $students->GetAdvancedSearch("v_$FldParm");
		$Fld->AdvancedSearch->SearchValue2 = $students->GetAdvancedSearch("y_$FldParm");
		$Fld->AdvancedSearch->SearchOperator2 = $students->GetAdvancedSearch("w_$FldParm");
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		global $students;
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $students->name01, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->name02, $Keyword);
		if (is_numeric($Keyword)) $this->BuildBasicSearchSQL($sWhere, $students->universityno, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->fac, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->dept, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->admission, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->category, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->specilization, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->class, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->classold, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->indexno, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->adyear, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->bplace, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->sex, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->nationality, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->state, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->certificate, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->address, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->telhome, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->mobile, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->fax, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->zemail, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->namerel, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->relation, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->addressrel, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->telrel, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->acayear, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->xstatus, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $students->remarks, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		$sFldExpression = ($Fld->FldVirtualExpression <> "") ? $Fld->FldVirtualExpression : $Fld->FldExpression;
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($lFldDataType == EW_DATATYPE_NUMBER) {
			$sWrk = $sFldExpression . " = " . ew_QuotedValue($Keyword, $lFldDataType);
		} else {
			$sWrk = $sFldExpression . " LIKE " . ew_QuotedValue("%" . $Keyword . "%", $lFldDataType);
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security, $students;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = $students->BasicSearchKeyword;
		$sSearchType = $students->BasicSearchType;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
		}
		if ($sSearchKeyword <> "") {
			$students->setSessionBasicSearchKeyword($sSearchKeyword);
			$students->setSessionBasicSearchType($sSearchType);
		}
		return $sSearchStr;
	}

	// Clear all search parameters
	function ResetSearchParms() {
		global $students;

		// Clear search WHERE clause
		$this->sSrchWhere = "";
		$students->setSearchWhere($this->sSrchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		global $students;
		$students->setSessionBasicSearchKeyword("");
		$students->setSessionBasicSearchType("");
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		global $students;
		$students->setAdvancedSearch("x_id", "");
		$students->setAdvancedSearch("x_name01", "");
		$students->setAdvancedSearch("x_name02", "");
		$students->setAdvancedSearch("x_universityno", "");
		$students->setAdvancedSearch("x_fac", "");
		$students->setAdvancedSearch("x_dept", "");
		$students->setAdvancedSearch("x_admission", "");
		$students->setAdvancedSearch("x_category", "");
		$students->setAdvancedSearch("x_specilization", "");
		$students->setAdvancedSearch("x_class", "");
		$students->setAdvancedSearch("x_classold", "");
		$students->setAdvancedSearch("x_indexno", "");
		$students->setAdvancedSearch("x_active", "");
		$students->setAdvancedSearch("x_adyear", "");
		$students->setAdvancedSearch("x_bplace", "");
		$students->setAdvancedSearch("x_bdate", "");
		$students->setAdvancedSearch("x_sex", "");
		$students->setAdvancedSearch("x_nationality", "");
		$students->setAdvancedSearch("x_state", "");
		$students->setAdvancedSearch("x_certificate", "");
		$students->setAdvancedSearch("x_address", "");
		$students->setAdvancedSearch("x_telhome", "");
		$students->setAdvancedSearch("x_mobile", "");
		$students->setAdvancedSearch("x_fax", "");
		$students->setAdvancedSearch("x_zemail", "");
		$students->setAdvancedSearch("x_namerel", "");
		$students->setAdvancedSearch("x_relation", "");
		$students->setAdvancedSearch("x_addressrel", "");
		$students->setAdvancedSearch("x_telrel", "");
		$students->setAdvancedSearch("x_acayear", "");
		$students->setAdvancedSearch("x_xstatus", "");
		$students->setAdvancedSearch("x_gpa01", "");
		$students->setAdvancedSearch("x_currency", "");
		$students->setAdvancedSearch("x_regfees", "");
		$students->setAdvancedSearch("x_fees", "");
		$students->setAdvancedSearch("x_support", "");
		$students->setAdvancedSearch("x_damga", "");
		$students->setAdvancedSearch("x_other", "");
		$students->setAdvancedSearch("x_nopay_inst", "");
		$students->setAdvancedSearch("x_nopay", "");
		$students->setAdvancedSearch("x_pay_01", "");
		$students->setAdvancedSearch("x_pay_02", "");
		$students->setAdvancedSearch("x_pay_03", "");
		$students->setAdvancedSearch("x_pay_04", "");
		$students->setAdvancedSearch("x_amount_pay", "");
		$students->setAdvancedSearch("x_amt", "");
		$students->setAdvancedSearch("x_allfees", "");
		$students->setAdvancedSearch("x_xmod", "");
		$students->setAdvancedSearch("x_pay", "");
		$students->setAdvancedSearch("x_card", "");
		$students->setAdvancedSearch("x_stady", "");
		$students->setAdvancedSearch("x_ch_fees", "");
		$students->setAdvancedSearch("x_remarks", "");
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		global $students;
		$bRestore = TRUE;
		if (@$_GET[EW_TABLE_BASIC_SEARCH] <> "") $bRestore = FALSE;
		if (@$_GET["x_id"] <> "") $bRestore = FALSE;
		if (@$_GET["x_name01"] <> "") $bRestore = FALSE;
		if (@$_GET["x_name02"] <> "") $bRestore = FALSE;
		if (@$_GET["x_universityno"] <> "") $bRestore = FALSE;
		if (@$_GET["x_fac"] <> "") $bRestore = FALSE;
		if (@$_GET["x_dept"] <> "") $bRestore = FALSE;
		if (@$_GET["x_admission"] <> "") $bRestore = FALSE;
		if (@$_GET["x_category"] <> "") $bRestore = FALSE;
		if (@$_GET["x_specilization"] <> "") $bRestore = FALSE;
		if (@$_GET["x_class"] <> "") $bRestore = FALSE;
		if (@$_GET["x_classold"] <> "") $bRestore = FALSE;
		if (@$_GET["x_indexno"] <> "") $bRestore = FALSE;
		if (@$_GET["x_active"] <> "") $bRestore = FALSE;
		if (@$_GET["x_adyear"] <> "") $bRestore = FALSE;
		if (@$_GET["x_bplace"] <> "") $bRestore = FALSE;
		if (@$_GET["x_bdate"] <> "") $bRestore = FALSE;
		if (@$_GET["x_sex"] <> "") $bRestore = FALSE;
		if (@$_GET["x_nationality"] <> "") $bRestore = FALSE;
		if (@$_GET["x_state"] <> "") $bRestore = FALSE;
		if (@$_GET["x_certificate"] <> "") $bRestore = FALSE;
		if (@$_GET["x_address"] <> "") $bRestore = FALSE;
		if (@$_GET["x_telhome"] <> "") $bRestore = FALSE;
		if (@$_GET["x_mobile"] <> "") $bRestore = FALSE;
		if (@$_GET["x_fax"] <> "") $bRestore = FALSE;
		if (@$_GET["x_zemail"] <> "") $bRestore = FALSE;
		if (@$_GET["x_namerel"] <> "") $bRestore = FALSE;
		if (@$_GET["x_relation"] <> "") $bRestore = FALSE;
		if (@$_GET["x_addressrel"] <> "") $bRestore = FALSE;
		if (@$_GET["x_telrel"] <> "") $bRestore = FALSE;
		if (@$_GET["x_acayear"] <> "") $bRestore = FALSE;
		if (@$_GET["x_xstatus"] <> "") $bRestore = FALSE;
		if (@$_GET["x_gpa01"] <> "") $bRestore = FALSE;
		if (@$_GET["x_currency"] <> "") $bRestore = FALSE;
		if (@$_GET["x_regfees"] <> "") $bRestore = FALSE;
		if (@$_GET["x_fees"] <> "") $bRestore = FALSE;
		if (@$_GET["x_support"] <> "") $bRestore = FALSE;
		if (@$_GET["x_damga"] <> "") $bRestore = FALSE;
		if (@$_GET["x_other"] <> "") $bRestore = FALSE;
		if (@$_GET["x_nopay_inst"] <> "") $bRestore = FALSE;
		if (@$_GET["x_nopay"] <> "") $bRestore = FALSE;
		if (@$_GET["x_pay_01"] <> "") $bRestore = FALSE;
		if (@$_GET["x_pay_02"] <> "") $bRestore = FALSE;
		if (@$_GET["x_pay_03"] <> "") $bRestore = FALSE;
		if (@$_GET["x_pay_04"] <> "") $bRestore = FALSE;
		if (@$_GET["x_amount_pay"] <> "") $bRestore = FALSE;
		if (@$_GET["x_amt"] <> "") $bRestore = FALSE;
		if (@$_GET["x_allfees"] <> "") $bRestore = FALSE;
		if (@$_GET["x_xmod"] <> "") $bRestore = FALSE;
		if (@$_GET["x_pay"] <> "") $bRestore = FALSE;
		if (@$_GET["x_card"] <> "") $bRestore = FALSE;
		if (@$_GET["x_stady"] <> "") $bRestore = FALSE;
		if (@$_GET["x_ch_fees"] <> "") $bRestore = FALSE;
		if (@$_GET["x_remarks"] <> "") $bRestore = FALSE;
		$this->RestoreSearch = $bRestore;
		if ($bRestore) {

			// Restore basic search values
			$students->BasicSearchKeyword = $students->getSessionBasicSearchKeyword();
			$students->BasicSearchType = $students->getSessionBasicSearchType();

			// Restore advanced search values
			$this->GetSearchParm($students->id);
			$this->GetSearchParm($students->name01);
			$this->GetSearchParm($students->name02);
			$this->GetSearchParm($students->universityno);
			$this->GetSearchParm($students->fac);
			$this->GetSearchParm($students->dept);
			$this->GetSearchParm($students->admission);
			$this->GetSearchParm($students->category);
			$this->GetSearchParm($students->specilization);
			$this->GetSearchParm($students->class);
			$this->GetSearchParm($students->classold);
			$this->GetSearchParm($students->indexno);
			$this->GetSearchParm($students->active);
			$this->GetSearchParm($students->adyear);
			$this->GetSearchParm($students->bplace);
			$this->GetSearchParm($students->bdate);
			$this->GetSearchParm($students->sex);
			$this->GetSearchParm($students->nationality);
			$this->GetSearchParm($students->state);
			$this->GetSearchParm($students->certificate);
			$this->GetSearchParm($students->address);
			$this->GetSearchParm($students->telhome);
			$this->GetSearchParm($students->mobile);
			$this->GetSearchParm($students->fax);
			$this->GetSearchParm($students->zemail);
			$this->GetSearchParm($students->namerel);
			$this->GetSearchParm($students->relation);
			$this->GetSearchParm($students->addressrel);
			$this->GetSearchParm($students->telrel);
			$this->GetSearchParm($students->acayear);
			$this->GetSearchParm($students->xstatus);
			$this->GetSearchParm($students->gpa01);
			$this->GetSearchParm($students->currency);
			$this->GetSearchParm($students->regfees);
			$this->GetSearchParm($students->fees);
			$this->GetSearchParm($students->support);
			$this->GetSearchParm($students->damga);
			$this->GetSearchParm($students->other);
			$this->GetSearchParm($students->nopay_inst);
			$this->GetSearchParm($students->nopay);
			$this->GetSearchParm($students->pay_01);
			$this->GetSearchParm($students->pay_02);
			$this->GetSearchParm($students->pay_03);
			$this->GetSearchParm($students->pay_04);
			$this->GetSearchParm($students->amount_pay);
			$this->GetSearchParm($students->amt);
			$this->GetSearchParm($students->allfees);
			$this->GetSearchParm($students->xmod);
			$this->GetSearchParm($students->pay);
			$this->GetSearchParm($students->card);
			$this->GetSearchParm($students->stady);
			$this->GetSearchParm($students->ch_fees);
			$this->GetSearchParm($students->remarks);
		}
	}

	// Set up sort parameters
	function SetUpSortOrder() {
		global $students;

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$students->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$students->CurrentOrderType = @$_GET["ordertype"];
			$students->UpdateSort($students->name01); // name01
			$students->UpdateSort($students->universityno); // universityno
			$students->UpdateSort($students->fac); // fac
			$students->UpdateSort($students->dept); // dept
			$students->UpdateSort($students->admission); // admission
			$students->UpdateSort($students->category); // category
			$students->UpdateSort($students->class); // class
			$students->UpdateSort($students->indexno); // indexno
			$students->UpdateSort($students->active); // active
			$students->UpdateSort($students->xstatus); // xstatus
			$students->UpdateSort($students->amt); // amt
			$students->UpdateSort($students->allfees); // allfees
			$students->UpdateSort($students->xmod); // xmod
			$students->UpdateSort($students->pay); // pay
			$students->UpdateSort($students->card); // card
			$students->UpdateSort($students->stady); // stady
			$students->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		global $students;
		$sOrderBy = $students->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($students->SqlOrderBy() <> "") {
				$sOrderBy = $students->SqlOrderBy();
				$students->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {
		global $students;

		// Get reset command
		if (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];

			// Reset search criteria
			if (strtolower($sCmd) == "reset" || strtolower($sCmd) == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if (strtolower($sCmd) == "resetsort") {
				$sOrderBy = "";
				$students->setSessionOrderBy($sOrderBy);
				$students->name01->setSort("");
				$students->universityno->setSort("");
				$students->fac->setSort("");
				$students->dept->setSort("");
				$students->admission->setSort("");
				$students->category->setSort("");
				$students->class->setSort("");
				$students->indexno->setSort("");
				$students->active->setSort("");
				$students->xstatus->setSort("");
				$students->amt->setSort("");
				$students->allfees->setSort("");
				$students->xmod->setSort("");
				$students->pay->setSort("");
				$students->card->setSort("");
				$students->stady->setSort("");
			}

			// Reset start position
			$this->lStartRec = 1;
			$students->setStartRecordNumber($this->lStartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $students;

		// "view"
		$this->ListOptions->Add("view");
		$item =& $this->ListOptions->Items["view"];
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$this->ListOptions->Add("edit");
		$item =& $this->ListOptions->Items["edit"];
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$this->ListOptions->Add("copy");
		$item =& $this->ListOptions->Items["copy"];
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// "checkbox"
		$this->ListOptions->Add("checkbox");
		$item =& $this->ListOptions->Items["checkbox"];
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = ($Security->CanDelete() || $Security->CanEdit());
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" class=\"phpmaker\" onclick=\"students_list.SelectAllKey(this);\">";

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		if ($students->Export <> "" ||
			$students->CurrentAction == "gridadd" ||
			$students->CurrentAction == "gridedit")
			$this->ListOptions->HideAllOptions();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $students;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt =& $this->ListOptions->Items["view"];
		if ($Security->CanView() && $oListOpt->Visible)
			$oListOpt->Body = "<a href=\"" . $this->ViewUrl . "\" class='view'><img src='images/viewcert.png' border='0'></a>";

		// "edit"
		$oListOpt =& $this->ListOptions->Items["edit"];
		if ($Security->CanEdit() && $oListOpt->Visible) {
			$oListOpt->Body = "<a href=\"" . $this->EditUrl . "\" class='view'><img src='images/edit2.png' border='0'></a>";
		}



		// "checkbox"
		$oListOpt =& $this->ListOptions->Items["checkbox"];
		if (($Security->CanDelete() || $Security->CanEdit()) && $oListOpt->Visible)
			$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" id=\"key_m[]\" value=\"" . ew_HtmlEncode($students->id->CurrentValue) . "\" class=\"phpmaker\" onclick='ew_ClickMultiCheckbox(this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language, $students;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		global $students;
		if ($this->lDisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->lStartRec = $_GET[EW_TABLE_START_REC];
				$students->setStartRecordNumber($this->lStartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$this->nPageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($this->nPageNo)) {
					$this->lStartRec = ($this->nPageNo-1)*$this->lDisplayRecs+1;
					if ($this->lStartRec <= 0) {
						$this->lStartRec = 1;
					} elseif ($this->lStartRec >= intval(($this->lTotalRecs-1)/$this->lDisplayRecs)*$this->lDisplayRecs+1) {
						$this->lStartRec = intval(($this->lTotalRecs-1)/$this->lDisplayRecs)*$this->lDisplayRecs+1;
					}
					$students->setStartRecordNumber($this->lStartRec);
				}
			}
		}
		$this->lStartRec = $students->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->lStartRec) || $this->lStartRec == "") { // Avoid invalid start record counter
			$this->lStartRec = 1; // Reset start record counter
			$students->setStartRecordNumber($this->lStartRec);
		} elseif (intval($this->lStartRec) > intval($this->lTotalRecs)) { // Avoid starting record > total records
			$this->lStartRec = intval(($this->lTotalRecs-1)/$this->lDisplayRecs)*$this->lDisplayRecs+1; // Point to last page first record
			$students->setStartRecordNumber($this->lStartRec);
		} elseif (($this->lStartRec-1) % $this->lDisplayRecs <> 0) {
			$this->lStartRec = intval(($this->lStartRec-1)/$this->lDisplayRecs)*$this->lDisplayRecs+1; // Point to page boundary
			$students->setStartRecordNumber($this->lStartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		global $students;
		$students->BasicSearchKeyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		$students->BasicSearchType = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm, $students;

		// Load search values
		// id

		$students->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		$students->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// name01
		$students->name01->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_name01"]);
		$students->name01->AdvancedSearch->SearchOperator = @$_GET["z_name01"];

		// name02
		$students->name02->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_name02"]);
		$students->name02->AdvancedSearch->SearchOperator = @$_GET["z_name02"];

		// universityno
		$students->universityno->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_universityno"]);
		$students->universityno->AdvancedSearch->SearchOperator = @$_GET["z_universityno"];

		// fac
		$students->fac->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fac"]);
		$students->fac->AdvancedSearch->SearchOperator = @$_GET["z_fac"];

		// dept
		$students->dept->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dept"]);
		$students->dept->AdvancedSearch->SearchOperator = @$_GET["z_dept"];

		// admission
		$students->admission->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_admission"]);
		$students->admission->AdvancedSearch->SearchOperator = @$_GET["z_admission"];

		// category
		$students->category->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_category"]);
		$students->category->AdvancedSearch->SearchOperator = @$_GET["z_category"];

		// specilization
		$students->specilization->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_specilization"]);
		$students->specilization->AdvancedSearch->SearchOperator = @$_GET["z_specilization"];

		// class
		$students->class->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_class"]);
		$students->class->AdvancedSearch->SearchOperator = @$_GET["z_class"];

		// classold
		$students->classold->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_classold"]);
		$students->classold->AdvancedSearch->SearchOperator = @$_GET["z_classold"];

		// indexno
		$students->indexno->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_indexno"]);
		$students->indexno->AdvancedSearch->SearchOperator = @$_GET["z_indexno"];

		// active
		$students->active->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_active"]);
		$students->active->AdvancedSearch->SearchOperator = @$_GET["z_active"];

		// adyear
		$students->adyear->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_adyear"]);
		$students->adyear->AdvancedSearch->SearchOperator = @$_GET["z_adyear"];

		// bplace
		$students->bplace->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bplace"]);
		$students->bplace->AdvancedSearch->SearchOperator = @$_GET["z_bplace"];

		// bdate
		$students->bdate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bdate"]);
		$students->bdate->AdvancedSearch->SearchOperator = @$_GET["z_bdate"];

		// sex
		$students->sex->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_sex"]);
		$students->sex->AdvancedSearch->SearchOperator = @$_GET["z_sex"];

		// nationality
		$students->nationality->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nationality"]);
		$students->nationality->AdvancedSearch->SearchOperator = @$_GET["z_nationality"];

		// state
		$students->state->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_state"]);
		$students->state->AdvancedSearch->SearchOperator = @$_GET["z_state"];

		// certificate
		$students->certificate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_certificate"]);
		$students->certificate->AdvancedSearch->SearchOperator = @$_GET["z_certificate"];

		// address
		$students->address->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_address"]);
		$students->address->AdvancedSearch->SearchOperator = @$_GET["z_address"];

		// telhome
		$students->telhome->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telhome"]);
		$students->telhome->AdvancedSearch->SearchOperator = @$_GET["z_telhome"];

		// mobile
		$students->mobile->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_mobile"]);
		$students->mobile->AdvancedSearch->SearchOperator = @$_GET["z_mobile"];

		// fax
		$students->fax->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fax"]);
		$students->fax->AdvancedSearch->SearchOperator = @$_GET["z_fax"];

		// email
		$students->zemail->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_zemail"]);
		$students->zemail->AdvancedSearch->SearchOperator = @$_GET["z_zemail"];

		// namerel
		$students->namerel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_namerel"]);
		$students->namerel->AdvancedSearch->SearchOperator = @$_GET["z_namerel"];

		// relation
		$students->relation->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_relation"]);
		$students->relation->AdvancedSearch->SearchOperator = @$_GET["z_relation"];

		// addressrel
		$students->addressrel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_addressrel"]);
		$students->addressrel->AdvancedSearch->SearchOperator = @$_GET["z_addressrel"];

		// telrel
		$students->telrel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telrel"]);
		$students->telrel->AdvancedSearch->SearchOperator = @$_GET["z_telrel"];

		// acayear
		$students->acayear->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_acayear"]);
		$students->acayear->AdvancedSearch->SearchOperator = @$_GET["z_acayear"];

		// xstatus
		$students->xstatus->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_xstatus"]);
		$students->xstatus->AdvancedSearch->SearchOperator = @$_GET["z_xstatus"];

		// gpa01
		$students->gpa01->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_gpa01"]);
		$students->gpa01->AdvancedSearch->SearchOperator = @$_GET["z_gpa01"];

		// currency
		$students->currency->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_currency"]);
		$students->currency->AdvancedSearch->SearchOperator = @$_GET["z_currency"];

		// regfees
		$students->regfees->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_regfees"]);
		$students->regfees->AdvancedSearch->SearchOperator = @$_GET["z_regfees"];

		// fees
		$students->fees->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fees"]);
		$students->fees->AdvancedSearch->SearchOperator = @$_GET["z_fees"];

		// support
		$students->support->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_support"]);
		$students->support->AdvancedSearch->SearchOperator = @$_GET["z_support"];

		// damga
		$students->damga->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_damga"]);
		$students->damga->AdvancedSearch->SearchOperator = @$_GET["z_damga"];

		// other
		$students->other->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_other"]);
		$students->other->AdvancedSearch->SearchOperator = @$_GET["z_other"];

		// nopay_inst
		$students->nopay_inst->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nopay_inst"]);
		$students->nopay_inst->AdvancedSearch->SearchOperator = @$_GET["z_nopay_inst"];

		// nopay
		$students->nopay->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nopay"]);
		$students->nopay->AdvancedSearch->SearchOperator = @$_GET["z_nopay"];

		// pay_01
		$students->pay_01->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pay_01"]);
		$students->pay_01->AdvancedSearch->SearchOperator = @$_GET["z_pay_01"];

		// pay_02
		$students->pay_02->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pay_02"]);
		$students->pay_02->AdvancedSearch->SearchOperator = @$_GET["z_pay_02"];

		// pay_03
		$students->pay_03->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pay_03"]);
		$students->pay_03->AdvancedSearch->SearchOperator = @$_GET["z_pay_03"];

		// pay_04
		$students->pay_04->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pay_04"]);
		$students->pay_04->AdvancedSearch->SearchOperator = @$_GET["z_pay_04"];

		// amount_pay
		$students->amount_pay->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_amount_pay"]);
		$students->amount_pay->AdvancedSearch->SearchOperator = @$_GET["z_amount_pay"];

		// amt
		$students->amt->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_amt"]);
		$students->amt->AdvancedSearch->SearchOperator = @$_GET["z_amt"];

		// allfees
		$students->allfees->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_allfees"]);
		$students->allfees->AdvancedSearch->SearchOperator = @$_GET["z_allfees"];

		// xmod
		$students->xmod->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_xmod"]);
		$students->xmod->AdvancedSearch->SearchOperator = @$_GET["z_xmod"];

		// pay
		$students->pay->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pay"]);
		$students->pay->AdvancedSearch->SearchOperator = @$_GET["z_pay"];

		// card
		$students->card->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_card"]);
		$students->card->AdvancedSearch->SearchOperator = @$_GET["z_card"];
		// stady
		$students->stady->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_stady"]);
		$students->stady->AdvancedSearch->SearchOperator = @$_GET["z_stady"];

		// ch_fees
		$students->ch_fees->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ch_fees"]);
		$students->ch_fees->AdvancedSearch->SearchOperator = @$_GET["z_ch_fees"];

		// remarks
		$students->remarks->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_remarks"]);
		$students->remarks->AdvancedSearch->SearchOperator = @$_GET["z_remarks"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn, $students;

		// Call Recordset Selecting event
		$students->Recordset_Selecting($students->CurrentFilter);

		// Load List page SQL
		$sSql = $students->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $offset, $rowcnt";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$students->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $students;
		$sFilter = $students->KeyFilter();

		// Call Row Selecting event
		$students->Row_Selecting($sFilter);

		// Load SQL based on filter
		$students->CurrentFilter = $sFilter;
		$sSql = $students->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values

			// Call Row Selected event
			$students->Row_Selected($rs);
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn, $students;
		$students->id->setDbValue($rs->fields('id'));
		$students->name01->setDbValue($rs->fields('name01'));
		$students->name02->setDbValue($rs->fields('name02'));
		$students->universityno->setDbValue($rs->fields('universityno'));
		$students->fac->setDbValue($rs->fields('fac'));
		$students->dept->setDbValue($rs->fields('dept'));
		$students->admission->setDbValue($rs->fields('admission'));
		$students->category->setDbValue($rs->fields('category'));
		$students->specilization->setDbValue($rs->fields('specilization'));
		$students->class->setDbValue($rs->fields('class'));
		$students->classold->setDbValue($rs->fields('classold'));
		$students->indexno->setDbValue($rs->fields('indexno'));
		$students->active->setDbValue($rs->fields('active'));
		$students->adyear->setDbValue($rs->fields('adyear'));
		$students->bplace->setDbValue($rs->fields('bplace'));
		$students->bdate->setDbValue($rs->fields('bdate'));
		$students->sex->setDbValue($rs->fields('sex'));
		$students->nationality->setDbValue($rs->fields('nationality'));
		$students->state->setDbValue($rs->fields('state'));
		$students->certificate->setDbValue($rs->fields('certificate'));
		$students->address->setDbValue($rs->fields('address'));
		$students->telhome->setDbValue($rs->fields('telhome'));
		$students->mobile->setDbValue($rs->fields('mobile'));
		$students->fax->setDbValue($rs->fields('fax'));
		$students->zemail->setDbValue($rs->fields('email'));
		$students->namerel->setDbValue($rs->fields('namerel'));
		$students->relation->setDbValue($rs->fields('relation'));
		$students->addressrel->setDbValue($rs->fields('addressrel'));
		$students->telrel->setDbValue($rs->fields('telrel'));
		$students->acayear->setDbValue($rs->fields('acayear'));
		$students->xstatus->setDbValue($rs->fields('xstatus'));
		$students->gpa01->setDbValue($rs->fields('gpa01'));
		$students->currency->setDbValue($rs->fields('currency'));
		$students->regfees->setDbValue($rs->fields('regfees'));
		$students->fees->setDbValue($rs->fields('fees'));
		$students->support->setDbValue($rs->fields('support'));
		$students->damga->setDbValue($rs->fields('damga'));
		$students->other->setDbValue($rs->fields('other'));
		$students->nopay_inst->setDbValue($rs->fields('nopay_inst'));
		$students->nopay->setDbValue($rs->fields('nopay'));
		$students->pay_01->setDbValue($rs->fields('pay_01'));
		$students->pay_02->setDbValue($rs->fields('pay_02'));
		$students->pay_03->setDbValue($rs->fields('pay_03'));
		$students->pay_04->setDbValue($rs->fields('pay_04'));
		$students->amount_pay->setDbValue($rs->fields('amount_pay'));
		$students->amt->setDbValue($rs->fields('amt'));
		$students->allfees->setDbValue($rs->fields('allfees'));
		$students->xmod->setDbValue($rs->fields('xmod'));
		$students->pay->setDbValue($rs->fields('pay'));
		$students->card->setDbValue($rs->fields('card'));
		$students->stady->setDbValue($rs->fields('stady'));
		$students->ch_fees->setDbValue($rs->fields('ch_fees'));
		$students->remarks->setDbValue($rs->fields('remarks'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language, $students;

		// Initialize URLs
		$this->ViewUrl = $students->ViewUrl();
		$this->EditUrl = $students->EditUrl();
		$this->InlineEditUrl = $students->InlineEditUrl();
		$this->CopyUrl = $students->CopyUrl();
		$this->InlineCopyUrl = $students->InlineCopyUrl();
		$this->DeleteUrl = $students->DeleteUrl();

		// Call Row_Rendering event
		$students->Row_Rendering();

		// Common render codes for all row types
		// name01

		$students->name01->CellCssStyle = ""; $students->name01->CellCssClass = "";
		$students->name01->CellAttrs = array(); $students->name01->ViewAttrs = array(); $students->name01->EditAttrs = array();

		// universityno
		$students->universityno->CellCssStyle = ""; $students->universityno->CellCssClass = "";
		$students->universityno->CellAttrs = array(); $students->universityno->ViewAttrs = array(); $students->universityno->EditAttrs = array();

		// fac
		$students->fac->CellCssStyle = ""; $students->fac->CellCssClass = "";
		$students->fac->CellAttrs = array(); $students->fac->ViewAttrs = array(); $students->fac->EditAttrs = array();

		// dept
		$students->dept->CellCssStyle = ""; $students->dept->CellCssClass = "";
		$students->dept->CellAttrs = array(); $students->dept->ViewAttrs = array(); $students->dept->EditAttrs = array();

		// admission
		$students->admission->CellCssStyle = ""; $students->admission->CellCssClass = "";
		$students->admission->CellAttrs = array(); $students->admission->ViewAttrs = array(); $students->admission->EditAttrs = array();

		// category
		$students->category->CellCssStyle = ""; $students->category->CellCssClass = "";
		$students->category->CellAttrs = array(); $students->category->ViewAttrs = array(); $students->category->EditAttrs = array();

		// class
		$students->class->CellCssStyle = ""; $students->class->CellCssClass = "";
		$students->class->CellAttrs = array(); $students->class->ViewAttrs = array(); $students->class->EditAttrs = array();

		// indexno
		$students->indexno->CellCssStyle = ""; $students->indexno->CellCssClass = "";
		$students->indexno->CellAttrs = array(); $students->indexno->ViewAttrs = array(); $students->indexno->EditAttrs = array();

		// active
		$students->active->CellCssStyle = ""; $students->active->CellCssClass = "";
		$students->active->CellAttrs = array(); $students->active->ViewAttrs = array(); $students->active->EditAttrs = array();

		// xstatus
		$students->xstatus->CellCssStyle = ""; $students->xstatus->CellCssClass = "";
		$students->xstatus->CellAttrs = array(); $students->xstatus->ViewAttrs = array(); $students->xstatus->EditAttrs = array();

		// amt
		$students->amt->CellCssStyle = ""; $students->amt->CellCssClass = "";
		$students->amt->CellAttrs = array(); $students->amt->ViewAttrs = array(); $students->amt->EditAttrs = array();

		// allfees
		$students->allfees->CellCssStyle = ""; $students->allfees->CellCssClass = "";
		$students->allfees->CellAttrs = array(); $students->allfees->ViewAttrs = array(); $students->allfees->EditAttrs = array();

		// xmod
		$students->xmod->CellCssStyle = ""; $students->xmod->CellCssClass = "";
		$students->xmod->CellAttrs = array(); $students->xmod->ViewAttrs = array(); $students->xmod->EditAttrs = array();

		// pay
		$students->pay->CellCssStyle = ""; $students->pay->CellCssClass = "";
		$students->pay->CellAttrs = array(); $students->pay->ViewAttrs = array(); $students->pay->EditAttrs = array();

		// card
		$students->card->CellCssStyle = ""; $students->card->CellCssClass = "";
		$students->card->CellAttrs = array(); $students->card->ViewAttrs = array(); $students->card->EditAttrs = array();
		
		// stady
		$students->stady->CellCssStyle = ""; $students->stady->CellCssClass = "";
		$students->stady->CellAttrs = array(); $students->stady->ViewAttrs = array(); $students->stady->EditAttrs = array();
		if ($students->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$students->id->ViewValue = $students->id->CurrentValue;
			$students->id->CssStyle = "";
			$students->id->CssClass = "";
			$students->id->ViewCustomAttributes = "";

			// name01
			$students->name01->ViewValue = $students->name01->CurrentValue;
			$students->name01->CssStyle = "";
			$students->name01->CssClass = "";
			$students->name01->ViewCustomAttributes = "";

			// name02
			$students->name02->ViewValue = $students->name02->CurrentValue;
			$students->name02->CssStyle = "";
			$students->name02->CssClass = "";
			$students->name02->ViewCustomAttributes = "";

			// universityno
			$students->universityno->ViewValue = $students->universityno->CurrentValue;
			$students->universityno->CssStyle = "";
			$students->universityno->CssClass = "";
			$students->universityno->ViewCustomAttributes = "";

			// fac
			if (strval($students->fac->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->fac->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title01` FROM `faculties`";
			$sWhereWrk = " `code` IN ($_SESSION[EW_SESSION_USER_FAC])";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->fac->ViewValue = $rswrk->fields('title01');
					$rswrk->Close();
				} else {
					$students->fac->ViewValue = $students->fac->CurrentValue;
				}
			} else {
				$students->fac->ViewValue = NULL;
			}
			$students->fac->CssStyle = "";
			$students->fac->CssClass = "";
			$students->fac->ViewCustomAttributes = "";

			// dept
			if (strval($students->dept->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->dept->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title01` FROM `lt_dept`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->dept->ViewValue = $rswrk->fields('title01');
					$rswrk->Close();
				} else {
					$students->dept->ViewValue = $students->dept->CurrentValue;
				}
			} else {
				$students->dept->ViewValue = NULL;
			}
			$students->dept->CssStyle = "";
			$students->dept->CssClass = "";
			$students->dept->ViewCustomAttributes = "";

			// admission
			if (strval($students->admission->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->admission->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title` FROM `lt_admission`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->admission->ViewValue = $rswrk->fields('title');
					$rswrk->Close();
				} else {
					$students->admission->ViewValue = $students->admission->CurrentValue;
				}
			} else {
				$students->admission->ViewValue = NULL;
			}
			$students->admission->CssStyle = "";
			$students->admission->CssClass = "";
			$students->admission->ViewCustomAttributes = "";

			// category
			if (strval($students->category->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->category->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title` FROM `lt_category`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->category->ViewValue = $rswrk->fields('title');
					$rswrk->Close();
				} else {
					$students->category->ViewValue = $students->category->CurrentValue;
				}
			} else {
				$students->category->ViewValue = NULL;
			}
			$students->category->CssStyle = "";
			$students->category->CssClass = "";
			$students->category->ViewCustomAttributes = "";


			// xstatus
			if (strval($students->xstatus->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->xstatus->CurrentValue) . "'";
			$sSqlWrk = "SELECT name as title01	FROM lt_status";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->xstatus->ViewValue = $rswrk->fields('title01');
					$rswrk->Close();
				} else {
					$students->xstatus->ViewValue = $students->xstatus->CurrentValue;
				}
			} else {
				$students->xstatus->ViewValue = NULL;
			}
			$students->xstatus->CssStyle = "";
			$students->xstatus->CssClass = "";
			$students->xstatus->ViewCustomAttributes = "";


			// specilization
			if (strval($students->specilization->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->specilization->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title01` FROM `lt_spec`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->specilization->ViewValue = $rswrk->fields('title01');
					$rswrk->Close();
				} else {
					$students->specilization->ViewValue = $students->specilization->CurrentValue;
				}
			} else {
				$students->specilization->ViewValue = NULL;
			}
			$students->specilization->CssStyle = "";
			$students->specilization->CssClass = "";
			$students->specilization->ViewCustomAttributes = "";

			// class
			if (strval($students->class->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->class->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title` FROM `lt_class`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->class->ViewValue = $rswrk->fields('title');
					$rswrk->Close();
				} else {
					$students->class->ViewValue = $students->class->CurrentValue;
				}
			} else {
				$students->class->ViewValue = NULL;
			}
			$students->class->CssStyle = "";
			$students->class->CssClass = "";
			$students->class->ViewCustomAttributes = "";

			// classold
			$students->classold->ViewValue = $students->classold->CurrentValue;
			$students->classold->CssStyle = "";
			$students->classold->CssClass = "";
			$students->classold->ViewCustomAttributes = "";

			// indexno
			$students->indexno->ViewValue = $students->indexno->CurrentValue;
			$students->indexno->CssStyle = "";
			$students->indexno->CssClass = "";
			$students->indexno->ViewCustomAttributes = "";

			// active
			if (strval($students->active->CurrentValue) <> "") {
				$sFilterWrk = "`code` = " . ew_AdjustSql($students->active->CurrentValue) . "";
			$sSqlWrk = "SELECT `fld_active` FROM `lt_std_active`";
			$sWhereWrk = "";
			if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
			$sWhereWrk .= "(" . "`groups`=1001" . ")";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->active->ViewValue = $rswrk->fields('fld_active');
					$rswrk->Close();
				} else {
					$students->active->ViewValue = $students->active->CurrentValue;
				}
			} else {
				$students->active->ViewValue = NULL;
			}
			$students->active->CssStyle = "";
			$students->active->CssClass = "";
			$students->active->ViewCustomAttributes = "";

			// adyear
			if (strval($students->adyear->CurrentValue) <> "") {
				$sFilterWrk = "`code` = " . ew_AdjustSql($students->adyear->CurrentValue) . "";
			$sSqlWrk = "SELECT `acayear` FROM `lt_acayer`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->adyear->ViewValue = $rswrk->fields('acayear');
					$rswrk->Close();
				} else {
					$students->adyear->ViewValue = $students->adyear->CurrentValue;
				}
			} else {
				$students->adyear->ViewValue = NULL;
			}
			$students->adyear->CssStyle = "";
			$students->adyear->CssClass = "";
			$students->adyear->ViewCustomAttributes = "";

			// bplace
			$students->bplace->ViewValue = $students->bplace->CurrentValue;
			$students->bplace->CssStyle = "";
			$students->bplace->CssClass = "";
			$students->bplace->ViewCustomAttributes = "";

			// bdate
			$students->bdate->ViewValue = $students->bdate->CurrentValue;
			$students->bdate->ViewValue = ew_FormatDateTime($students->bdate->ViewValue, 7);
			$students->bdate->CssStyle = "";
			$students->bdate->CssClass = "";
			$students->bdate->ViewCustomAttributes = "";

			// sex
			if (strval($students->sex->CurrentValue) <> "") {
				$sFilterWrk = "`code` = " . ew_AdjustSql($students->sex->CurrentValue) . "";
			$sSqlWrk = "SELECT `fld_active` FROM `lt_std_active`";
			$sWhereWrk = "";
			if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
			$sWhereWrk .= "(" . "`groups`=1003" . ")";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->sex->ViewValue = $rswrk->fields('fld_active');
					$rswrk->Close();
				} else {
					$students->sex->ViewValue = $students->sex->CurrentValue;
				}
			} else {
				$students->sex->ViewValue = NULL;
			}
			$students->sex->CssStyle = "";
			$students->sex->CssClass = "";
			$students->sex->ViewCustomAttributes = "";

			// nationality
			if (strval($students->nationality->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->nationality->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title01` FROM `lt_nationality`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->nationality->ViewValue = $rswrk->fields('title01');
					$rswrk->Close();
				} else {
					$students->nationality->ViewValue = $students->nationality->CurrentValue;
				}
			} else {
				$students->nationality->ViewValue = NULL;
			}
			$students->nationality->CssStyle = "";
			$students->nationality->CssClass = "";
			$students->nationality->ViewCustomAttributes = "";

			// state
			if (strval($students->state->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->state->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title01` FROM `lt_states`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->state->ViewValue = $rswrk->fields('title01');
					$rswrk->Close();
				} else {
					$students->state->ViewValue = $students->state->CurrentValue;
				}
			} else {
				$students->state->ViewValue = NULL;
			}
			$students->state->CssStyle = "";
			$students->state->CssClass = "";
			$students->state->ViewCustomAttributes = "";

			// certificate
			if (strval($students->certificate->CurrentValue) <> "") {
				$sFilterWrk = "`code` = '" . ew_AdjustSql($students->certificate->CurrentValue) . "'";
			$sSqlWrk = "SELECT `title01` FROM `lt_cert`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->certificate->ViewValue = $rswrk->fields('title01');
					$rswrk->Close();
				} else {
					$students->certificate->ViewValue = $students->certificate->CurrentValue;
				}
			} else {
				$students->certificate->ViewValue = NULL;
			}
			$students->certificate->CssStyle = "";
			$students->certificate->CssClass = "";
			$students->certificate->ViewCustomAttributes = "";

			// address
			$students->address->ViewValue = $students->address->CurrentValue;
			$students->address->CssStyle = "";
			$students->address->CssClass = "";
			$students->address->ViewCustomAttributes = "";

			// telhome
			$students->telhome->ViewValue = $students->telhome->CurrentValue;
			$students->telhome->CssStyle = "";
			$students->telhome->CssClass = "";
			$students->telhome->ViewCustomAttributes = "";

			// mobile
			$students->mobile->ViewValue = $students->mobile->CurrentValue;
			$students->mobile->CssStyle = "";
			$students->mobile->CssClass = "";
			$students->mobile->ViewCustomAttributes = "";

			// fax
			$students->fax->ViewValue = $students->fax->CurrentValue;
			$students->fax->CssStyle = "";
			$students->fax->CssClass = "";
			$students->fax->ViewCustomAttributes = "";

			// email
			$students->zemail->ViewValue = $students->zemail->CurrentValue;
			$students->zemail->CssStyle = "";
			$students->zemail->CssClass = "";
			$students->zemail->ViewCustomAttributes = "";

			// namerel
			$students->namerel->ViewValue = $students->namerel->CurrentValue;
			$students->namerel->CssStyle = "";
			$students->namerel->CssClass = "";
			$students->namerel->ViewCustomAttributes = "";

			// relation
			$students->relation->ViewValue = $students->relation->CurrentValue;
			$students->relation->CssStyle = "";
			$students->relation->CssClass = "";
			$students->relation->ViewCustomAttributes = "";

			// addressrel
			$students->addressrel->ViewValue = $students->addressrel->CurrentValue;
			$students->addressrel->CssStyle = "";
			$students->addressrel->CssClass = "";
			$students->addressrel->ViewCustomAttributes = "";

			// telrel
			$students->telrel->ViewValue = $students->telrel->CurrentValue;
			$students->telrel->CssStyle = "";
			$students->telrel->CssClass = "";
			$students->telrel->ViewCustomAttributes = "";

			// acayear
			if (strval($students->acayear->CurrentValue) <> "") {
				$sFilterWrk = "`code` = " . ew_AdjustSql($students->acayear->CurrentValue) . "";
			$sSqlWrk = "SELECT `acayear` FROM `lt_acayer`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->acayear->ViewValue = $rswrk->fields('acayear');
					$rswrk->Close();
				} else {
					$students->acayear->ViewValue = $students->acayear->CurrentValue;
				}
			} else {
				$students->acayear->ViewValue = NULL;
			}
			$students->acayear->CssStyle = "";
			$students->acayear->CssClass = "";
			$students->acayear->ViewCustomAttributes = "";

			// gpa01
			$students->gpa01->ViewValue = $students->gpa01->CurrentValue;
			$students->gpa01->CssStyle = "";
			$students->gpa01->CssClass = "";
			$students->gpa01->ViewCustomAttributes = "";

			// currency
			if (strval($students->currency->CurrentValue) <> "") {
				$sFilterWrk = "`code` = " . ew_AdjustSql($students->currency->CurrentValue) . "";
			$sSqlWrk = "SELECT `fld_active` FROM `lt_std_active`";
			$sWhereWrk = "";
			if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
			$sWhereWrk .= "(" . "`groups`=1002" . ")";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->currency->ViewValue = $rswrk->fields('fld_active');
					$rswrk->Close();
				} else {
					$students->currency->ViewValue = $students->currency->CurrentValue;
				}
			} else {
				$students->currency->ViewValue = NULL;
			}
			$students->currency->CssStyle = "";
			$students->currency->CssClass = "";
			$students->currency->ViewCustomAttributes = "";

			// regfees
			$students->regfees->ViewValue = $students->regfees->CurrentValue;
			$students->regfees->CssStyle = "";
			$students->regfees->CssClass = "";
			$students->regfees->ViewCustomAttributes = "";

			// fees
			$students->fees->ViewValue = $students->fees->CurrentValue;
			$students->fees->CssStyle = "";
			$students->fees->CssClass = "";
			$students->fees->ViewCustomAttributes = "";

			// support
			$students->support->ViewValue = $students->support->CurrentValue;
			$students->support->CssStyle = "";
			$students->support->CssClass = "";
			$students->support->ViewCustomAttributes = "";

			// damga
			$students->damga->ViewValue = $students->damga->CurrentValue;
			$students->damga->CssStyle = "";
			$students->damga->CssClass = "";
			$students->damga->ViewCustomAttributes = "";

			// other
			$students->other->ViewValue = $students->other->CurrentValue;
			$students->other->CssStyle = "";
			$students->other->CssClass = "";
			$students->other->ViewCustomAttributes = "";

			// nopay_inst
			if (strval($students->nopay_inst->CurrentValue) <> "") {
				$sFilterWrk = "`id` = " . ew_AdjustSql($students->nopay_inst->CurrentValue) . "";
			$sSqlWrk = "SELECT `pay` FROM `lt_payother`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->nopay_inst->ViewValue = $rswrk->fields('pay');
					$rswrk->Close();
				} else {
					$students->nopay_inst->ViewValue = $students->nopay_inst->CurrentValue;
				}
			} else {
				$students->nopay_inst->ViewValue = NULL;
			}
			$students->nopay_inst->CssStyle = "";
			$students->nopay_inst->CssClass = "";
			$students->nopay_inst->ViewCustomAttributes = "";

			// nopay
			if (strval($students->nopay->CurrentValue) <> "") {
				$sFilterWrk = "`id` = " . ew_AdjustSql($students->nopay->CurrentValue) . "";
			$sSqlWrk = "SELECT `pay` FROM `lt_payinstall`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->nopay->ViewValue = $rswrk->fields('pay');
					$rswrk->Close();
				} else {
					$students->nopay->ViewValue = $students->nopay->CurrentValue;
				}
			} else {
				$students->nopay->ViewValue = NULL;
			}
			$students->nopay->CssStyle = "";
			$students->nopay->CssClass = "";
			$students->nopay->ViewCustomAttributes = "";

			// pay_01
			$students->pay_01->ViewValue = $students->pay_01->CurrentValue;
			$students->pay_01->CssStyle = "";
			$students->pay_01->CssClass = "";
			$students->pay_01->ViewCustomAttributes = "";

			// pay_02
			$students->pay_02->ViewValue = $students->pay_02->CurrentValue;
			$students->pay_02->CssStyle = "";
			$students->pay_02->CssClass = "";
			$students->pay_02->ViewCustomAttributes = "";

			// pay_03
			$students->pay_03->ViewValue = $students->pay_03->CurrentValue;
			$students->pay_03->CssStyle = "";
			$students->pay_03->CssClass = "";
			$students->pay_03->ViewCustomAttributes = "";

			// pay_04
			$students->pay_04->ViewValue = $students->pay_04->CurrentValue;
			$students->pay_04->CssStyle = "";
			$students->pay_04->CssClass = "";
			$students->pay_04->ViewCustomAttributes = "";

			// amount_pay
			$students->amount_pay->ViewValue = $students->amount_pay->CurrentValue;
			$students->amount_pay->CssStyle = "";
			$students->amount_pay->CssClass = "";
			$students->amount_pay->ViewCustomAttributes = "";

			// amt
			$students->amt->ViewValue = $students->amt->CurrentValue;
			$students->amt->CssStyle = "";
			$students->amt->CssClass = "";
			$students->amt->ViewCustomAttributes = "";

			// allfees
			$students->allfees->ViewValue = $students->allfees->CurrentValue;
			$students->allfees->CssStyle = "";
			$students->allfees->CssClass = "";
			$students->allfees->ViewCustomAttributes = "";

			// xmod
			$students->xmod->ViewValue = $students->xmod->CurrentValue;
			$students->xmod->CssStyle = "";
			$students->xmod->CssClass = "";
			$students->xmod->ViewCustomAttributes = "";

			// pay
			$students->pay->ViewValue = $students->pay->CurrentValue;
			$students->pay->CssStyle = "";
			$students->pay->CssClass = "";
			$students->pay->ViewCustomAttributes = "";

			// card
			if (strval($students->card->CurrentValue) <> "") {
				$sFilterWrk = "`code` = " . ew_AdjustSql($students->card->CurrentValue) . "";
			$sSqlWrk = "SELECT `fld_active` FROM `lt_std_active`";
			$sWhereWrk = "";
			if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
			$sWhereWrk .= "(" . "`groups`=1004" . ")";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->card->ViewValue = $rswrk->fields('fld_active');
					$rswrk->Close();
				} else {
					$students->card->ViewValue = $students->card->CurrentValue;
				}
			} else {
				$students->card->ViewValue = NULL;
			}
			$students->card->CssStyle = "";
			$students->card->CssClass = "";
			$students->card->ViewCustomAttributes = "";

			
			// stady
			if (strval($students->stady->CurrentValue) <> "") {
				$sFilterWrk = "`code` = " . ew_AdjustSql($students->stady->CurrentValue) . "";
			$sSqlWrk = "SELECT `fld_active` FROM `lt_std_active`";
			$sWhereWrk = "";
			if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
			$sWhereWrk .= "(" . "`groups`=1006" . ")";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->stady->ViewValue = $rswrk->fields('fld_active');
					$rswrk->Close();
				} else {
					$students->stady->ViewValue = $students->stady->CurrentValue;
				}
			} else {
				$students->stady->ViewValue = NULL;
			}
			$students->stady->CssStyle = "";
			$students->stady->CssClass = "";
			$students->stady->ViewCustomAttributes = "";


			
			// ch_fees
			if (strval($students->ch_fees->CurrentValue) <> "") {
				$sFilterWrk = "`code` = " . ew_AdjustSql($students->ch_fees->CurrentValue) . "";
			$sSqlWrk = "SELECT `fld_active` FROM `lt_std_active`";
			$sWhereWrk = "";
			if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
			$sWhereWrk .= "(" . "`groups`=1005" . ")";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$students->ch_fees->ViewValue = $rswrk->fields('fld_active');
					$rswrk->Close();
				} else {
					$students->ch_fees->ViewValue = $students->ch_fees->CurrentValue;
				}
			} else {
				$students->ch_fees->ViewValue = NULL;
			}
			$students->ch_fees->CssStyle = "";
			$students->ch_fees->CssClass = "";
			$students->ch_fees->ViewCustomAttributes = "";

			// remarks
			$students->remarks->ViewValue = $students->remarks->CurrentValue;
			$students->remarks->CssStyle = "";
			$students->remarks->CssClass = "";
			$students->remarks->ViewCustomAttributes = "";

			// name01
			$students->name01->HrefValue = "";
			$students->name01->TooltipValue = "";
			if ($students->Export == "")
				$students->name01->ViewValue = ew_Highlight($students->HighlightName(), $students->name01->ViewValue, $students->getSessionBasicSearchKeyword(), $students->getSessionBasicSearchType(), $students->getAdvancedSearch("x_name01"));

			// universityno
			$students->universityno->HrefValue = "";
			$students->universityno->TooltipValue = "";

			// fac
			$students->fac->HrefValue = "";
			$students->fac->TooltipValue = "";

			// dept
			$students->dept->HrefValue = "";
			$students->dept->TooltipValue = "";

			// admission
			$students->admission->HrefValue = "";
			$students->admission->TooltipValue = "";

			// category
			$students->category->HrefValue = "";
			$students->category->TooltipValue = "";

			// class
			$students->class->HrefValue = "";
			$students->class->TooltipValue = "";

			// indexno
			$students->indexno->HrefValue = "";
			$students->indexno->TooltipValue = "";
			if ($students->Export == "")
				$students->indexno->ViewValue = ew_Highlight($students->HighlightName(), $students->indexno->ViewValue, $students->getSessionBasicSearchKeyword(), $students->getSessionBasicSearchType(), $students->getAdvancedSearch("x_indexno"));

			// active
			$students->active->HrefValue = "";
			$students->active->TooltipValue = "";

			// xstatus
			$students->xstatus->HrefValue = "";
			$students->xstatus->TooltipValue = "";

			// amt
			$students->amt->HrefValue = "";
			$students->amt->TooltipValue = "";

			// allfees
			$students->allfees->HrefValue = "";
			$students->allfees->TooltipValue = "";

			// xmod
			$students->xmod->HrefValue = "";
			$students->xmod->TooltipValue = "";

			// pay
			$students->pay->HrefValue = "";
			$students->pay->TooltipValue = "";

			
			// card
			$students->card->HrefValue = "";
			$students->card->TooltipValue = "";
			
			// stady
			$students->stady->HrefValue = "";
			$students->stady->TooltipValue = "";
		} elseif ($students->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// name01
			$students->name01->EditCustomAttributes = "";
			$students->name01->EditValue = ew_HtmlEncode($students->name01->AdvancedSearch->SearchValue);

			// universityno
			$students->universityno->EditCustomAttributes = "";
			$students->universityno->EditValue = ew_HtmlEncode($students->universityno->AdvancedSearch->SearchValue);

			// fac
			$students->fac->EditCustomAttributes = "";
				$sFilterWrk = "";
			$sSqlWrk = "SELECT `code`, `title01`, '' AS Disp2Fld, '' AS SelectFilterFld FROM `faculties`";
			$sWhereWrk = "`code` IN ($_SESSION[EW_SESSION_USER_FAC])";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$students->fac->EditValue = $arwrk;

			// dept
			$students->dept->EditCustomAttributes = "";
				$sFilterWrk = "";
			$sSqlWrk = "SELECT `code`, `title01`, '' AS Disp2Fld, `fac` FROM `lt_dept`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), ""));
			$students->dept->EditValue = $arwrk;

			// admission
			$students->admission->EditCustomAttributes = "";
				$sFilterWrk = "";
			$sSqlWrk = "SELECT `code`, `title`, '' AS Disp2Fld, '' AS SelectFilterFld FROM `lt_admission`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$students->admission->EditValue = $arwrk;

			// category
			$students->category->EditCustomAttributes = "";
				$sFilterWrk = "";
			$sSqlWrk = "SELECT `code`, `title`, '' AS Disp2Fld, '' AS SelectFilterFld FROM `lt_category`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$students->category->EditValue = $arwrk;

			// xstatus
			$students->xstatus->EditCustomAttributes = "";
				$sFilterWrk = "";
			//$sSqlWrk = "SELECT `code`, `title`, '' AS Disp2Fld, '' AS SelectFilterFld FROM `lt_xstatus`";
			$sSqlWrk = "SELECT `code`,name as title	FROM lt_status";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$students->xstatus->EditValue = $arwrk;

			// class
			$students->class->EditCustomAttributes = "";
				$sFilterWrk = "";
			$sSqlWrk = "SELECT `code`, `title`, '' AS Disp2Fld, '' AS SelectFilterFld FROM `lt_class`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				if ($sWhereWrk <> "") $sWhereWrk .= " AND ";
				$sWhereWrk .= "(" . $sFilterWrk . ")";
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$students->class->EditValue = $arwrk;

			// indexno
			$students->indexno->EditCustomAttributes = "";
			$students->indexno->EditValue = ew_HtmlEncode($students->indexno->AdvancedSearch->SearchValue);

			// active
			$students->active->EditCustomAttributes = "";

			// xstatus
			$students->xstatus->EditCustomAttributes = "";

			// amt
			$students->amt->EditCustomAttributes = "";
			$students->amt->EditValue = ew_HtmlEncode($students->amt->AdvancedSearch->SearchValue);

			// allfees
			$students->allfees->EditCustomAttributes = "";
			$students->allfees->EditValue = ew_HtmlEncode($students->allfees->AdvancedSearch->SearchValue);

			// xmod
			$students->xmod->EditCustomAttributes = "";
			$students->xmod->EditValue = ew_HtmlEncode($students->xmod->AdvancedSearch->SearchValue);

			// pay
			$students->pay->EditCustomAttributes = "";
			$students->pay->EditValue = ew_HtmlEncode($students->pay->AdvancedSearch->SearchValue);

			// card
			$students->card->EditCustomAttributes = "";
			
			// stady
			$students->stady->EditCustomAttributes = "";
		}

		// Call Row Rendered event
		if ($students->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$students->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError, $students;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			if ($gsSearchError <> "") $gsSearchError .= "<br>";
			$gsSearchError .= $sFormCustomError;
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		global $students;
		$students->id->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_id");
		$students->name01->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_name01");
		$students->name02->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_name02");
		$students->universityno->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_universityno");
		$students->fac->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_fac");
		$students->dept->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_dept");
		$students->admission->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_admission");
		$students->category->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_category");
		$students->specilization->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_specilization");
		$students->class->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_class");
		$students->classold->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_classold");
		$students->indexno->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_indexno");
		$students->active->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_active");
		$students->adyear->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_adyear");
		$students->bplace->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_bplace");
		$students->bdate->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_bdate");
		$students->sex->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_sex");
		$students->nationality->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_nationality");
		$students->state->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_state");
		$students->certificate->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_certificate");
		$students->address->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_address");
		$students->telhome->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_telhome");
		$students->mobile->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_mobile");
		$students->fax->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_fax");
		$students->zemail->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_zemail");
		$students->namerel->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_namerel");
		$students->relation->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_relation");
		$students->addressrel->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_addressrel");
		$students->telrel->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_telrel");
		$students->acayear->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_acayear");
		$students->xstatus->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_xstatus");
		$students->gpa01->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_gpa01");
		$students->currency->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_currency");
		$students->regfees->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_regfees");
		$students->fees->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_fees");
		$students->support->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_support");
		$students->damga->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_damga");
		$students->other->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_other");
		$students->nopay_inst->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_nopay_inst");
		$students->nopay->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_nopay");
		$students->pay_01->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_pay_01");
		$students->pay_02->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_pay_02");
		$students->pay_03->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_pay_03");
		$students->pay_04->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_pay_04");
		$students->amount_pay->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_amount_pay");
		$students->amt->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_amt");
		$students->allfees->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_allfees");
		$students->xmod->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_xmod");
		$students->pay->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_pay");
		$students->card->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_card");
		$students->stady->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_stady");
		$students->ch_fees->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_ch_fees");
		$students->remarks->AdvancedSearch->SearchValue = $students->getAdvancedSearch("x_remarks");
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email format
	function ExportData() {
		global $students;
		$utf8 = FALSE;
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->lTotalRecs = $students->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->lTotalRecs = $rs->RecordCount();
		}
		$this->lStartRec = 1;

		// Export all
		if ($students->ExportAll) {
			$this->lDisplayRecs = $this->lTotalRecs;
			$this->lStopRec = $this->lTotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->lDisplayRecs < 0) {
				$this->lStopRec = $this->lTotalRecs;
			} else {
				$this->lStopRec = $this->lStartRec + $this->lDisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->lStartRec-1, $this->lDisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		if ($students->Export == "xml") {
			$XmlDoc = new cXMLDocument(EW_XML_ENCODING);
			$XmlDoc->AddRoot();
		} else {
			$ExportDoc = new cExportDocument($students, "h");
			$ExportDoc->ExportHeader();
			if ($ExportDoc->Horizontal) { // Horizontal format, write header
				$ExportDoc->BeginExportRow();
				$ExportDoc->ExportCaption($students->id);
				$ExportDoc->ExportCaption($students->name01);
				$ExportDoc->ExportCaption($students->name02);
				$ExportDoc->ExportCaption($students->universityno);
				$ExportDoc->ExportCaption($students->fac);
				$ExportDoc->ExportCaption($students->dept);
				$ExportDoc->ExportCaption($students->admission);
				$ExportDoc->ExportCaption($students->category);
				$ExportDoc->ExportCaption($students->specilization);
				$ExportDoc->ExportCaption($students->class);
				$ExportDoc->ExportCaption($students->classold);
				$ExportDoc->ExportCaption($students->indexno);
				$ExportDoc->ExportCaption($students->active);
				$ExportDoc->ExportCaption($students->adyear);
				$ExportDoc->ExportCaption($students->bplace);
				$ExportDoc->ExportCaption($students->bdate);
				$ExportDoc->ExportCaption($students->sex);
				$ExportDoc->ExportCaption($students->nationality);
				$ExportDoc->ExportCaption($students->state);
				$ExportDoc->ExportCaption($students->certificate);
				$ExportDoc->ExportCaption($students->address);
				$ExportDoc->ExportCaption($students->telhome);
				$ExportDoc->ExportCaption($students->mobile);
				$ExportDoc->ExportCaption($students->fax);
				$ExportDoc->ExportCaption($students->zemail);
				$ExportDoc->ExportCaption($students->namerel);
				$ExportDoc->ExportCaption($students->relation);
				$ExportDoc->ExportCaption($students->addressrel);
				$ExportDoc->ExportCaption($students->telrel);
				$ExportDoc->ExportCaption($students->acayear);
				$ExportDoc->ExportCaption($students->xstatus);
				$ExportDoc->ExportCaption($students->gpa01);
				$ExportDoc->ExportCaption($students->currency);
				$ExportDoc->ExportCaption($students->regfees);
				$ExportDoc->ExportCaption($students->fees);
				$ExportDoc->ExportCaption($students->support);
				$ExportDoc->ExportCaption($students->damga);
				$ExportDoc->ExportCaption($students->other);
				$ExportDoc->ExportCaption($students->nopay_inst);
				$ExportDoc->ExportCaption($students->nopay);
				$ExportDoc->ExportCaption($students->pay_01);
				$ExportDoc->ExportCaption($students->pay_02);
				$ExportDoc->ExportCaption($students->pay_03);
				$ExportDoc->ExportCaption($students->pay_04);
				$ExportDoc->ExportCaption($students->amount_pay);
				$ExportDoc->ExportCaption($students->amt);
				$ExportDoc->ExportCaption($students->card);
				$ExportDoc->ExportCaption($students->stady);
				$ExportDoc->ExportCaption($students->ch_fees);
				$ExportDoc->ExportCaption($students->remarks);
				$ExportDoc->EndExportRow();
			}
		}

		// Move to first record
		$this->lRecCnt = $this->lStartRec - 1;
		if (!$rs->EOF) {
			$rs->MoveFirst();
			if (!$bSelectLimit && $this->lStartRec > 1)
				$rs->Move($this->lStartRec - 1);
		}
		while (!$rs->EOF && $this->lRecCnt < $this->lStopRec) {
			$this->lRecCnt++;
			if (intval($this->lRecCnt) >= intval($this->lStartRec)) {
				$this->LoadRowValues($rs);

				// Render row
				$students->CssClass = "";
				$students->CssStyle = "";
				$students->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->RenderRow();
				if ($students->Export == "xml") {
					$XmlDoc->AddRow();
					$XmlDoc->AddField('id', $students->id->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('name01', $students->name01->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('name02', $students->name02->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('universityno', $students->universityno->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('fac', $students->fac->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('dept', $students->dept->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('admission', $students->admission->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('category', $students->category->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('specilization', $students->specilization->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('class', $students->class->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('classold', $students->classold->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('indexno', $students->indexno->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('active', $students->active->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('adyear', $students->adyear->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('bplace', $students->bplace->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('bdate', $students->bdate->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('sex', $students->sex->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('nationality', $students->nationality->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('state', $students->state->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('certificate', $students->certificate->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('address', $students->address->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('telhome', $students->telhome->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('mobile', $students->mobile->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('fax', $students->fax->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('zemail', $students->zemail->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('namerel', $students->namerel->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('relation', $students->relation->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('addressrel', $students->addressrel->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('telrel', $students->telrel->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('acayear', $students->acayear->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('xstatus', $students->xstatus->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('gpa01', $students->gpa01->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('currency', $students->currency->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('regfees', $students->regfees->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('fees', $students->fees->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('support', $students->support->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('damga', $students->damga->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('other', $students->other->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('nopay_inst', $students->nopay_inst->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('nopay', $students->nopay->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('pay_01', $students->pay_01->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('pay_02', $students->pay_02->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('pay_03', $students->pay_03->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('pay_04', $students->pay_04->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('amount_pay', $students->amount_pay->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('amt', $students->amt->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('card', $students->card->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('stady', $students->stady->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('ch_fees', $students->ch_fees->ExportValue($students->Export, $students->ExportOriginalValue));
					$XmlDoc->AddField('remarks', $students->remarks->ExportValue($students->Export, $students->ExportOriginalValue));
				} else {
					$ExportDoc->BeginExportRow(TRUE); // Allow CSS styles if enabled
					$ExportDoc->ExportField($students->id);
					$ExportDoc->ExportField($students->name01);
					$ExportDoc->ExportField($students->name02);
					$ExportDoc->ExportField($students->universityno);
					$ExportDoc->ExportField($students->fac);
					$ExportDoc->ExportField($students->dept);
					$ExportDoc->ExportField($students->admission);
					$ExportDoc->ExportField($students->category);
					$ExportDoc->ExportField($students->specilization);
					$ExportDoc->ExportField($students->class);
					$ExportDoc->ExportField($students->classold);
					$ExportDoc->ExportField($students->indexno);
					$ExportDoc->ExportField($students->active);
					$ExportDoc->ExportField($students->adyear);
					$ExportDoc->ExportField($students->bplace);
					$ExportDoc->ExportField($students->bdate);
					$ExportDoc->ExportField($students->sex);
					$ExportDoc->ExportField($students->nationality);
					$ExportDoc->ExportField($students->state);
					$ExportDoc->ExportField($students->certificate);
					$ExportDoc->ExportField($students->address);
					$ExportDoc->ExportField($students->telhome);
					$ExportDoc->ExportField($students->mobile);
					$ExportDoc->ExportField($students->fax);
					$ExportDoc->ExportField($students->zemail);
					$ExportDoc->ExportField($students->namerel);
					$ExportDoc->ExportField($students->relation);
					$ExportDoc->ExportField($students->addressrel);
					$ExportDoc->ExportField($students->telrel);
					$ExportDoc->ExportField($students->acayear);
					$ExportDoc->ExportField($students->xstatus);
					$ExportDoc->ExportField($students->gpa01);
					$ExportDoc->ExportField($students->currency);
					$ExportDoc->ExportField($students->regfees);
					$ExportDoc->ExportField($students->fees);
					$ExportDoc->ExportField($students->support);
					$ExportDoc->ExportField($students->damga);
					$ExportDoc->ExportField($students->other);
					$ExportDoc->ExportField($students->nopay_inst);
					$ExportDoc->ExportField($students->nopay);
					$ExportDoc->ExportField($students->pay_01);
					$ExportDoc->ExportField($students->pay_02);
					$ExportDoc->ExportField($students->pay_03);
					$ExportDoc->ExportField($students->pay_04);
					$ExportDoc->ExportField($students->amount_pay);
					$ExportDoc->ExportField($students->amt);
					$ExportDoc->ExportField($students->card);
					$ExportDoc->ExportField($students->stady);
					$ExportDoc->ExportField($students->ch_fees);
					$ExportDoc->ExportField($students->remarks);
					$ExportDoc->EndExportRow();
				}
			}
			$rs->MoveNext();
		}
		if ($students->Export <> "xml")
			$ExportDoc->ExportFooter();

		// Close recordset
		$rs->Close();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write BOM if utf-8
		if ($utf8 && !in_array($students->Export, array("email", "xml")))
			echo "\xEF\xBB\xBF";

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		if ($students->Export == "xml") {
			header("Content-Type: text/xml");
			echo $XmlDoc->XML();
		} elseif ($students->Export == "email") {
			$this->ExportEmail($ExportDoc->Text);
			$this->Page_Terminate($students->ExportReturnUrl());
		} else {
			echo $ExportDoc->Text;
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
	function Message_Showing(&$msg) {

		// Example:
		//$msg = "your new message";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example: 
		//$this->ListOptions->Add("new");
		//$this->ListOptions->Items["new"]->OnLeft = TRUE; // Link on left
		//$this->ListOptions->MoveItem("new", 0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
