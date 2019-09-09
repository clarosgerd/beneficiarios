<?php if (@$gsExport == "") { ?>
<?php if (@!$gbSkipHeaderFooter) { ?>
		<?php if (isset($gTimer)) $gTimer->Stop() ?>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	<!-- Main Footer -->
	<footer class="main-footer">
		<!-- To the right -->
		<div class="pull-right hidden-xs"></div>
		<!-- Default to the left --><!-- ** Note: Only licensed users are allowed to change the copyright statement. ** -->
		<div class="ewFooterText"><?php echo $Language->ProjectPhrase("FooterText") ?></div>
	</footer>
</div>
<!-- ./wrapper -->
<?php } ?>
<script type="text/html" class="ewJsTemplate" data-name="menu" data-data="menu" data-target="#ewMenu">
<ul class="sidebar-menu" data-widget="tree" data-follow-link="{{:followLink}}" data-accordion="{{:accordion}}">
{{include tmpl="#menu"/}}
</ul>
</script>
<script type="text/html" id="menu">
{{if items}}
	{{for items}}
		<li id="{{:id}}" name="{{:name}}" class="{{if isHeader}}header{{else}}{{if items}}treeview{{/if}}{{if active}} active current{{/if}}{{if open}} menu-open{{/if}}{{/if}}">
			{{if isHeader}}
				{{if icon}}<i class="{{:icon}}"></i>{{/if}}
				<span>{{:text}}</span>
				{{if label}}
				<span class="pull-right-container">
					{{:label}}
				</span>
				{{/if}}
			{{else}}
			<a href="{{:href}}"{{if target}} target="{{:target}}"{{/if}}{{if attrs}}{{:attrs}}{{/if}}>
				{{if icon}}<i class="{{:icon}}"></i>{{/if}}
				<span>{{:text}}</span>
				{{if items}}
				<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					{{if label}}
						<span>{{:label}}</span>
					{{/if}}
				</span>
				{{else}}
					{{if label}}
						<span class="pull-right-container">
							{{:label}}
						</span>
					{{/if}}
				{{/if}}
			</a>
			{{/if}}
			{{if items}}
			<ul class="treeview-menu"{{if open}} style="display: block;"{{/if}}>
				{{include tmpl="#menu"/}}
			</ul>
			{{/if}}
		</li>
	{{/for}}
{{/if}}
</script>
<script type="text/html" class="ewJsTemplate" data-name="languages" data-data="languages" data-method="<?php echo $Language->Method ?>" data-target="<?php echo ew_HtmlEncode($Language->Target) ?>">
<?php echo $Language->GetTemplate() ?>
</script>
<script type="text/html" class="ewJsTemplate" data-name="login" data-data="login" data-method="appendTo" data-target=".navbar-custom-menu .nav">
{{if isLoggedIn}}
<li class="dropdown user user-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
	</a>
	<ul class="dropdown-menu">
		<!--<li class="user-header"></li>-->
		<li class="user-body">
			<p><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;{{:currentUserName}}</p>
		</li>
		<li class="user-footer">
			{{if canChangePassword}}
			<div class="pull-left">
				<a class="btn btn-default btn-flat" href="{{:changePasswordUrl}}">{{:changePasswordText}}</a>
			</div>
			{{/if}}
			{{if canLogout}}
			<div class="pull-right">
				<a class="btn btn-default btn-flat" href="{{:logoutUrl}}">{{:logoutText}}</a>
			</div>
			{{/if}}
		</li>
	</ul>
<li>
{{else}}
	{{if canLogin}}
<li><a href="{{:loginUrl}}">{{:loginText}}</a></li>
	{{/if}}
{{/if}}
</script>
<script type="text/javascript">
ew_RenderJsTemplates();
</script>
<!-- modal dialog -->
<div id="ewModalDialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4></div><div class="modal-body"></div><div class="modal-footer"></div></div></div></div>
<!-- modal lookup dialog -->
<div id="ewModalLookupDialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4></div><div class="modal-body"></div><div class="modal-footer"></div></div></div></div>
<!-- add option dialog -->
<div id="ewAddOptDialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("AddBtn") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!-- message box -->
<div id="ewMsgBox" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- prompt -->
<div id="ewPrompt" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("MessageOK") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!-- session timer -->
<div id="ewTimer" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- tooltip -->
<div id="ewTooltip"></div>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">
jQuery.get("<?php echo $EW_RELATIVE_PATH ?>phpjs/userevt14.js");
</script>
<script type="text/javascript">

// Write your global startup script here
// document.write("page loaded");

</script>
<?php } ?>
</body>
</html>
