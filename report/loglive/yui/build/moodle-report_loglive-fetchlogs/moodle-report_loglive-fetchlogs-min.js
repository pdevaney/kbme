YUI.add("moodle-report_loglive-fetchlogs",function(e,t){function n(){n.superclass.constructor.apply(this,arguments)}var r={NEWROW:"newrow",SPINNER:"fa-spinner",ICONSMALL:"iconsmall"},i={NEWROW:"."+r.NEWROW,TBODY:".flexible tbody",PAUSEBUTTON:"#livelogs-pause-button",SPINNER:"."+r.SPINNER};e.extend(n,e.Base,{callBack:{},spinner:{},pauseButton:{},initializer:function(){this.get("page")===0&&(this.callBack=e.later(this.get("interval")*1e3,this,this.fetchRecentLogs,null,!0)),this.spinner=e.one(i.SPINNER),this.pauseButton=e.one(i.PAUSEBUTTON),this.spinner.hide(),e.on("click",this.toggleUpdate,i.PAUSEBUTTON,this)},fetchRecentLogs:function(){this.spinner.show();var t={logreader:this.get("logreader"),since:this.get("since"),page:this.get("page"),id:this.get("courseid")},n={method:"get",context:this,on:{complete:this.updateLogTable},data:t},r=M.cfg.wwwroot+"/report/loglive/loglive_ajax.php";e.io(r,n)},updateLogTable:function(t,n){e.later(600,this,"hideLoadingIcon");var r;try{r=e.JSON.parse(n.responseText);if(r.error)return e.use("moodle-core-notification-ajaxexception",function(){return new M.core.ajaxException(r)}),this}catch(s){return e.use("moodle-core-notification-exception",function(){return new M.core.exception(s)}),this}this.set("since",r.until);var o=r.logs,u=e.one(i.TBODY),a=null;if(u&&o){a=u.get("firstChild"),a&&u.insertBefore(o,a);var f=u.get("children").slice(this.get("perpage"));f.remove(),e.later(5e3,this,"removeHighlight",r.until)}},removeHighlight:function(t){e.all(".time"+t).removeClass(r.NEWROW)},hideLoadingIcon:function(){this.spinner.hide()},toggleUpdate:function(){this.callBack?(this.callBack.cancel(),this.callBack="",this.pauseButton.setContent(M.util.get_string("resume","report_loglive"))):(this.callBack=e.later(this.get("interval")*1e3,this,this.fetchRecentLogs,null,!0),this.pauseButton.setContent(M.util.get_string("pause","report_loglive")))}},{NAME:"fetchLogs",ATTRS:{since:null,courseid:0,page:0,perpage:100,interval:60,logreader:"logstore_standard"}}),e.namespace("M.report_loglive.FetchLogs").init=function(e){return new n(e)}},"@VERSION@",{requires:["base","event","node","io","node-event-delegate"]});
