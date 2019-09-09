/**
 * Create Date/Time Picker (for PHPMaker 2018)
 * @license (C) 2018 e.World Technology Ltd.
 */
function ew_CreateDateTimePicker(e,t,a){if(t.indexOf("$rowindex$")>-1)return;var r=jQuery,s=ew_GetElement(t,e),i=r(s),n="";if(i.data("DateTimePicker")||i.parent().data("DateTimePicker"))return;var o=function(e,t){if(e==5||e==9)return t?9:5;else if(e==6||e==10)return t?10:6;else if(e==7||e==11)return t?11:7;else if(e==12||e==15)return t?15:12;else if(e==13||e==16)return t?16:13;else if(e==14||e==17)return t?17:14;return e};var c=a.format;if(c==0)c=EW_DATE_FORMAT_ID;else if(c==1)c=o(EW_DATE_FORMAT_ID,true);else if(c==2)c=o(EW_DATE_FORMAT_ID,false);switch(c){case 5:n="YYYY/MM/DD";break;case 6:n="MM/DD/YYYY";break;case 7:n="DD/MM/YYYY";break;case 9:n="YYYY/MM/DD HH:mm:ss";break;case 10:n="MM/DD/YYYY HH:mm:ss";break;case 11:n="DD/MM/YYYY HH:mm:ss";break;case 12:n="YY/MM/DD";break;case 13:n="MM/DD/YY";break;case 14:n="DD/MM/YY";break;case 15:n="YY/MM/DD HH:mm:ss";break;case 16:n="MM/DD/YY HH:mm:ss";break;case 17:n="DD/MM/YY HH:mm:ss";break}n=n.replace(/\//g,EW_DATE_SEPARATOR).replace(/:/g,EW_TIME_SEPARATOR);a.format=n;if(!a.locale)a.locale=EW_LANGUAGE_ID.toLowerCase();var l=r.isBoolean(a.inputGroup)?a.inputGroup:true;delete a.inputGroup;a.debug=EW_DEBUG_ENABLED;var u={id:t,form:e,enabled:true,inputGroup:l,options:a};r(function(){r(document).trigger("datetimepicker",[u]);if(!u.enabled)return;if(u.inputGroup!==false){var e=r('<button type="button"><span class="glyphicon glyphicon-calendar"></span></button>').addClass("btn btn-default btn-sm datepickerbutton").css({"font-size":i.css("font-size"),height:i.outerHeight()}).click(function(){r(this).closest(".has-error").removeClass("has-error")});i.wrap('<div class="input-group date"></div>').after(r('<span class="input-group-btn"></span>').append(e));i=i.parent()}if(u.options.locale&&moment.locale()!=u.options.locale){r.getScript(EW_RELATIVE_PATH+"moment/locale/"+u.options.locale+".js",function(){i.datetimepicker(u.options)})}else{i.datetimepicker(u.options)}})}