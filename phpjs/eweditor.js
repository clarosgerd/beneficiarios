/**
 * Create HTML Editor (for PHPMaker 2018)
 * @license (C) 2018 e.World Technology Ltd.
 */
function ew_CreateEditor(e,t,r,n,a){if(typeof CKEDITOR=="undefined"||t.indexOf("$rowindex$")>-1)return;var i=jQuery,o=i("#"+e)[0],s=ew_GetElement(t,o);if(!s)return;var c=e+"$"+t+"$";var l=(r?Math.abs(r):35)*2+"em";var f=((n?Math.abs(n):4)+4)*1.5+"em";var d=window.location.href.substring(0,window.location.href.lastIndexOf("/")+1);var u=(EW_LANGUAGE_ID||"").toLowerCase();if(u=="zh-hk"||u=="zh-tw"||u=="de-at"||u=="pt-pt"||u=="es-419")u=u.substring(0,2);var h={height:f,language:u,autoUpdateElement:false,filebrowserBrowseUrl:"ckeditor/filemanager/browser/default/browser.html?Connector="+d+"ckeditor/filemanager/connectors/php/connector.php",filebrowserImageBrowseUrl:"ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector="+d+"ckeditor/filemanager/connectors/php/connector.php",filebrowserFlashBrowseUrl:"ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector="+d+"ckeditor/filemanager/connectors/php/connector.php",baseHref:""};var m={id:t,form:o,enabled:true,settings:h};i(document).trigger("create.editor",[m]);if(!m.enabled)return;if(a){m.settings.readOnly=true;m.settings.toolbar=[["Source"]]}var w={name:t,active:false,instance:null,create:function(){this.instance=CKEDITOR.replace(s,m.settings);this.active=true},set:function(){if(this.instance)this.instance.setData(this.instance.element.value)},save:function(){if(this.instance)this.instance.updateElement();var e={id:t,form:o,value:ew_RemoveSpaces(s.value)};i(document).trigger("save.editor",[e]).val(e.value)},focus:function(){if(this.instance)this.instance.focus()},destroy:function(){if(this.instance)this.instance.destroy()}};i(s).data("editor",w).addClass("editor")}