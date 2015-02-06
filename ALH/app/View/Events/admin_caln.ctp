<style type="text/css">
	#examplecontainer {height:450px;  position:relative}

	#show2up { position:absolute;clear:both;cursor:pointer;}
	#show1up { position:absolute;clear:both;cursor:pointer;}

	#cal3Container { display:none; position:absolute; left:52px; top:32px; z-index:2}
	#cal2Container { display:none; position:absolute; left:52px; z-index:1}
        #startdates
        {
            padding: 6px;
            margin-right: 4px;
        }
        #enddates
        {
            padding: 6px;
            margin-right: 4px;
            margin-left: 3px;
        }
         .ui-widget
                        {
                            font-size:12px !important;
                        }
           #caleventlog {
                            float:left;
                            margin-bottom: 1em;
                            margin-right: 1em;
                            margin-top: 1em;
                            width: 100%;
                            background-color:#eee;
                            border:1px solid #000;
                        }
                        #caleventlog .bd {
                            overflow:auto;
                            height:20em;
                            padding:5px;
                        }
                        #caleventlog .hd {
                            background-color:#aaa;
                            border-bottom:1px solid #000;
                            font-weight:bold;
                            padding:2px;
                            color: #333333;
                        }
                       #caleventlog .entry {
                            border: 1px solid #D1D1D1;
                            margin: 0 0 8px;
                            padding: 10px;
                        }
                        .starttimepick
			{
			    background: none repeat scroll 0 0 #FFFFFF !important;
			    padding: 5px !important;
			    width: 88px !important;
			}
                        .weekly input[type="checkbox"]
                        {
                            margin-right: 0px !important;
                        }
</style>
<div class="yui-skin-sam">

<script type="text/javascript">
    function deleteDate(el) {
            el.parent().remove();
        }
	YAHOO.namespace("example.calendar");

	YAHOO.example.calendar.init = function() {
            
     

	var startdate = YAHOO.util.Dom.get("startdate");
        var enddate = YAHOO.util.Dom.get("enddate");
        
        var startdates = YAHOO.util.Dom.get("startdates");
        var enddates = YAHOO.util.Dom.get("enddates");
        
        var startDiv = YAHOO.util.Dom.get("cal3Container");
        var endDiv = YAHOO.util.Dom.get("cal2Container");
        
        //var eLog = YAHOO.util.Dom.get("evtentriess");
        
      /* function logEvent(date) {
			eLog.innerHTML = '<div class="entry" id="entry'+date+'"><br>Start Time: <input type="text" name="data[EventDate][start_time][]" id="start_timeE" class="starttimepick" placeholder="8:00" value="8:00">&nbsp;<select name="data[EventDate][start_timeF][]"  class="starttimepick"><option value="am">am</option><option value="pm">pm</option></select>&nbsp;&nbsp;&nbsp;&nbsp;End Time: <input type="text" name="data[EventDate][end_time][]" id="end_timeE" class="starttimepick" placeholder="8:00">&nbsp;<select name="data[EventDate][end_timeF][]"  class="starttimepick"><option value="am">am</option><option value="pm">pm</option></select>'+'</div>' + eLog.innerHTML;
			//eCount++;
		}*/
function dateToLocaleString(dt, cal) {
                	var wStr = cal.cfg.getProperty("WEEKDAYS_LONG")[dt.getDay()];
                	var dStr = dt.getDate();
                	var mStr = cal.cfg.getProperty("MONTHS_LONG")[dt.getMonth()];
               	 	var yStr = dt.getFullYear();
                	return (wStr + ", " + dStr + " " + mStr + " " + yStr);
		}
                
	function StartDate(type,args,obj) {
			var selected = args[0];
			var selDate = this.toDate(selected[0]);
                        var aa = String(args);
                        var a = aa.replace(",", "-").replace(",", "-");
                                              
                         if(enddate.value!=''){
                         
                         var stDateVal = aa.replace(",", "/").replace(",", "/");
                         var sDateVal = Math.round(+new Date(stDateVal)/1000);
                       
                         var endVal = enddate.value;
                         var endDateVal = endVal.replace("-", "/").replace("-", "/");
                         var eDateVal = Math.round(+new Date(endDateVal)/1000);
                         
                         if (eDateVal<sDateVal) {
                            alert("Start Date should be lesser then End End.");
                            return false;
                         }
                         
                        
                         }
                        var disply_date_array = a.split("-");
                        var disply_date = disply_date_array[1]+'-'+disply_date_array[2]+'-'+disply_date_array[0];
                        startdates.value=disply_date;
                        startdate.value=a;
                        startDiv.style.display="none";
                        return true;
                       
	}
	function EndDate(type,args,obj) {
			var selected = args[0];
			var selDate = this.toDate(selected[0]);
                        var aa = String(args);
                        var a = aa.replace(",", "-").replace(",", "-");
                        var strt = startdate.value;
                        var stDate = strt.replace("-", "/").replace("-", "/");
                        var enDate = aa.replace(",", "/").replace(",", "/");
                      
                       // var sd = Date.parse(stDate)/1000;
                       // var ed = Date.parse(enDate)/1000;
                     var sd = Math.round(+new Date(stDate)/1000);
                     var ed = Math.round(+new Date(enDate)/1000);
                   
                        if (sd>ed) {
                            alert("End Date should be greater then Start End.");
                            return false;
                        }
                        var disply_edate_array = a.split("-");
                        var disply_edate = disply_edate_array[1]+'-'+disply_edate_array[2]+'-'+disply_edate_array[0];
                        enddates.value=disply_edate;
                        enddate.value=a;
                        endDiv.style.display="none";
                        return true;
			
	}
        
         var navConfig = {
            strings : {
                month: "Choose Month",
                year: "Enter Year",
                submit: "OK",
                cancel: "Cancel",
                invalidYear: "Please enter a valid year"
            },
            monthFormat: YAHOO.widget.Calendar.SHORT,
            initialFocus: "year"
        };
        
		YAHOO.example.calendar.cal3 = new YAHOO.widget.Calendar("cal3","cal3Container", { title:"Choose a Start Date:", close:true,navigator: navConfig, mindate: "<?php echo date('m/d/Y'); ?>" } );
		YAHOO.example.calendar.cal3.selectEvent.subscribe(StartDate, YAHOO.example.calendar.cal3, true);
		
		YAHOO.example.calendar.cal3.render();

		// Listener to show the 2 page Calendar when the button is clicked
		YAHOO.util.Event.addListener("show2up", "click", YAHOO.example.calendar.cal3.show, YAHOO.example.calendar.cal3, true);

		YAHOO.example.calendar.cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"Choose a End Date:", close:true,navigator: navConfig, mindate: "<?php echo date('m/d/Y'); ?>" } );
		YAHOO.example.calendar.cal2.selectEvent.subscribe(EndDate, YAHOO.example.calendar.cal2, true);
		
		YAHOO.example.calendar.cal2.render();

		// Listener to show the 1-up Calendar when the button is clicked
		YAHOO.util.Event.addListener("show1up", "click", YAHOO.example.calendar.cal2.show, YAHOO.example.calendar.cal2, true);
	}

	YAHOO.util.Event.onDOMReady(YAHOO.example.calendar.init);
        
     
                
        function addList() {
            var sst = document.getElementById("startdate").value;
            var eet = document.getElementById("enddate").value;
            var rec = document.getElementById("reccuring").value;
            var daily_dayss = document.getElementById("daily_days").value;
            var startD = document.getElementById("start_timeN");
            var endD = document.getElementById("end_timeN");
          
            
            if (sst==''||eet=='') {
                alert("You need to select Start Date/End Date first.");
                return false;
            }
            var myNode = document.getElementById("evtentriess");
while (myNode.firstChild) {
    myNode.removeChild(myNode.firstChild);
}
             var stDate = sst.replace("-", "/").replace("-", "/");
             var enDate = eet.replace("-", "/").replace("-", "/");
              var stDateP = sst.replace("-", "_").replace("-", "_");
              
             var sd = Math.round(+new Date(stDate)/1000);
                     var ed = Math.round(+new Date(enDate)/1000);
                    
                   var msg;
                   var datess;
                   datess = new Date(stDate);
                   msg = getWeekDay(datess); 
                   var selectBox = document.getElementById("evtentriess");
		   
		   var stPicker = "start_time_picker"+stDateP;
	           var endPicker = "end_time_picker"+stDateP;
		   var stPickerCall = "timePicker('" + stPicker + "');";
	           var endPickerCall = "timePicker('" + endPicker + "');";
	           timePicker("start_time_picker"+stDateP);
		   timePicker("end_time_picker"+stDateP);
		
		    selectBox.innerHTML = selectBox.innerHTML + '<div class="entry" id="entry' + stDateP + '"><div class="createEvent-date-msg">' + msg + '</div><div class="createEvent-startDate">Start Time:</div><div id="start_time_picker' + stDateP + '" class="input-append date createEvent-startDate-tag"><span class="add-on"  onclick="timePicker(this.id)" id="start_time_picker' + stDateP + '"><input type="text" class="starttimepick" onfocus="'+stPickerCall+'"  name="data[EventDate][start_time][]" id="start_timeE" value="' + startD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><div class="createEvent-endDate">End Time:</div><div id="end_time_picker' + stDateP + '" class="input-append date createEvent-endDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="end_time_picker' + stDateP + '"><input type="text" class="starttimepick" onfocus="'+endPickerCall+'" name="data[EventDate][end_time][]" id="end_timeE" value="' + endD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><input type="hidden" name="data[EventDate][start_date][]" id="data[EventDate][start_date][]" value="'+stDate+'">' + '&nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteDate($(this));" ><img src="/img/admin/delete.png" class="createEvent-delete"></a><br><br></div>';
		    
		timePicker("start_time_picker"+stDateP);
		timePicker("end_time_picker"+stDateP);
		    
                    
                    while (ed>sd) {
                         var sDate=new Date(stDate);
                       // daily_dayss = document.getElementById("daily_days").value;
                        //alert(daily_dayss);
                        sDate.setDate(sDate.getDate()+parseInt(daily_dayss));
                       
                    // format a date
                    var stDate = sDate.getFullYear() + '/' + ("0" + (sDate.getMonth() + 1)).slice(-2) + '/' + sDate.getDate();
                   datess = new Date(stDate);
                   msg = getWeekDay(datess);
                   //var sd = Math.round(+new Date(stDate)/1000);
                 // alert(ed);
                 var stDateP = stDate.replace("/", "_").replace("/", "_");
                  sd = Math.round(+new Date(stDate)/1000);
                   //alert(sd);
                   if (ed>=sd) {
			
			var stPicker = "start_time_picker"+stDateP;
	           var endPicker = "end_time_picker"+stDateP;
		   var stPickerCall = "timePicker('" + stPicker + "');";
	           var endPickerCall = "timePicker('" + endPicker + "');";
	           timePicker("start_time_picker"+stDateP);
		   timePicker("end_time_picker"+stDateP);
                    
		      selectBox.innerHTML = selectBox.innerHTML + '<div class="entry" id="entry' + stDateP + '"><div class="createEvent-date-msg">' + msg + '</div><div class="createEvent-startDate">Start Time:</div><div id="start_time_picker' + stDateP + '" class="input-append date createEvent-startDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="start_time_picker' + stDateP + '"><input type="text" class="starttimepick" onfocus="'+stPickerCall+'" name="data[EventDate][start_time][]" id="start_timeE" value="' + startD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><div class="createEvent-endDate">End Time:</div><div id="end_time_picker' + stDateP + '" class="input-append date createEvent-endDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="end_time_picker' + stDateP + '"><input type="text" class="starttimepick" onfocus="'+endPickerCall+'" name="data[EventDate][end_time][]" id="end_timeE" value="' + endD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><input type="hidden" name="data[EventDate][start_date][]" id="data[EventDate][start_date][]" value="'+stDateP+'">' + '&nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteDate($(this));" ><img src="/img/admin/delete.png" class="createEvent-delete"></a><br><br></div>';
		      
		timePicker("start_time_picker"+stDateP);
		timePicker("end_time_picker"+stDateP);
                   
                  
		}
                
                  
                  // alert("dfgdfg");
                    }
                     return true;  
           // alert(sst);
           // alert(eet);
        }
        
         function addListWeekly() {
            var sst = document.getElementById("startdate").value;
            var eet = document.getElementById("enddate").value;
            var rec = document.getElementById("reccuring").value;
            var startD = document.getElementById("start_timeN");
            var endD = document.getElementById("end_timeN");
         
            if (sst==''||eet=='') {
                alert("You need to select Start Date/End Date first.");
                return false;
            }
            var checkedDay = [];
            for (count=0;count<7;count++) {
               if(addEvent.repeat_day[count].checked)
               {
               checkedDay.push(addEvent.repeat_day[count].value);
               
               }
            }
            if (checkedDay.length == 0)
            {
                alert("Atleast one day you need to checked.");
                return false;
            }
            document.getElementById("weekRepeatDay").value=checkedDay;
            var weekday = [sst,eet,checkedDay];
             
             
              $.post('<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/Events/findWeekDay', {"data[dateDetail]": weekday}, function(data) {
          var datDate = JSON.parse(data);
          var cntday = datDate.length;
           if (cntday == 0)
            {
                alert("No date is in your search criteria.");
                return false;
            }
             var myNode = document.getElementById("evtentriess");
                while (myNode.firstChild) {
                    myNode.removeChild(myNode.firstChild);
                }
          var s;
          for (s=0;s<cntday;s++) {
             var stDate = datDate[s].replace("-", "_").replace("-", "_");
             var stDateMsg = datDate[s].replace("-", "/").replace("-", "/");
            var msg;
                   var datess;
                   datess = new Date(stDateMsg);
                   msg = getWeekDay(datess); 
             var selectBox = document.getElementById("evtentriess");
	     //alert(stDate);
	     var stPicker = "start_time_picker"+stDate;
	           var endPicker = "end_time_picker"+stDate;
		   var stPickerCall = "timePicker('" + stPicker + "');";
	           var endPickerCall = "timePicker('" + endPicker + "');";
	           timePicker("start_time_picker"+stDate);
		   timePicker("end_time_picker"+stDate);
		   
	      selectBox.innerHTML = selectBox.innerHTML + '<div class="entry" id="entry' + stDate + '"><div class="createEvent-date-msg">' + msg + '</div><div class="createEvent-startDate">Start Time:</div><div id="start_time_picker' + stDate + '" class="input-append date createEvent-startDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="start_time_picker' + stDate + '"><input type="text" class="starttimepick" onfocus="'+stPickerCall+'" name="data[EventDate][start_time][]" id="start_timeE" value="' + startD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><div class="createEvent-endDate">End Time:</div><div id="end_time_picker' + stDate + '" class="input-append date createEvent-endDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="end_time_picker' + stDate + '"><input type="text" class="starttimepick" onfocus="'+endPickerCall+'" name="data[EventDate][end_time][]" id="end_timeE" value="' + endD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><input type="hidden" name="data[EventDate][start_date][]" id="data[EventDate][start_date][]" value="'+stDate+'">' + '&nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteDate($(this));" ><img src="/img/admin/delete.png" class="createEvent-delete"></a><br><br></div>';
	      
	      
                  timePicker("start_time_picker"+stDate);
		timePicker("end_time_picker"+stDate);
	
          }
           return true;
           });
              
          return true;  
           // alert(sst);
           // alert(eet);
        }
       
        function addListMonthly() {
            var sst = document.getElementById("startdate").value;
            var eet = document.getElementById("enddate").value;
            var rec = document.getElementById("reccuring").value;
            var startD = document.getElementById("start_timeN");
            var endD = document.getElementById("end_timeN");
           
            if (sst==''||eet=='') {
                alert("You need to select Start Date/End Date first.");
                return false;
            }
            var checkedDay = [];
            for (count=0;count<2;count++) {
               if(addEvent.month_mode[count].checked)
               {
               checkedDay.push(addEvent.month_mode[count].value);
               
               }
            }
            
            if (checkedDay.length == 0)
            {
                alert("Need to check monthly mode.");
                return false;
            }
            //document.getElementById("weekRepeatDay").value=checkedDay;
           var month_day =  document.getElementById("month_day1").value;
           var monthly_period =  document.getElementById("monthly_period").value;
           var monthly_pattern_day =  document.getElementById("monthly_pattern_day").value;
            if (checkedDay=="mode1") {
                var monthday = [sst,eet,month_day];
                var action = "findNoOfDay";
            }
            else
            {
                var monthday = [sst,eet,monthly_period,monthly_pattern_day];
                var action = "dayOfMounth";
            }
            
             
             
              $.post('<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/Events/'+action, {"data[dateDetail]": monthday}, function(data) {
         if (data==0) {
            alert("Please Enter correct day (between 1-30).");
            return false;
         }
          var datDate = JSON.parse(data);
          //console.log(datDate);
          var cntday = datDate.length;
           if (cntday == 0)
            {
                alert("No date is in your search criteria.");
                return false;
            }
             var myNode = document.getElementById("evtentriess");
                while (myNode.firstChild) {
                    myNode.removeChild(myNode.firstChild);
                }
          var s;
          for (s=0;s<cntday;s++) {
             var stDate = datDate[s].replace("-", "_").replace("-", "_");
             var stDateMsg = datDate[s].replace("-", "/").replace("-", "/");
	     
            var msg;
                   var datess;
                   datess = new Date(stDateMsg);
                   msg = getWeekDay(datess); 
             var selectBox = document.getElementById("evtentriess");
	     
	      var stPicker = "start_time_picker"+stDate;
	           var endPicker = "end_time_picker"+stDate;
		   var stPickerCall = "timePicker('" + stPicker + "');";
	           var endPickerCall = "timePicker('" + endPicker + "');";
	           timePicker("start_time_picker"+stDate);
		   timePicker("end_time_picker"+stDate);
		   
	      selectBox.innerHTML = selectBox.innerHTML + '<div class="entry" id="entry' + stDate + '"><div class="createEvent-date-msg">' + msg + '</div><div class="createEvent-startDate">Start Time:</div><div id="start_time_picker' + stDate + '" class="input-append date createEvent-startDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="start_time_picker' + stDate + '"><input type="text" class="starttimepick" onfocus="'+stPickerCall+'" name="data[EventDate][start_time][]" id="start_timeE" value="' + startD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><div class="createEvent-endDate">End Time:</div><div id="end_time_picker' + stDate + '" class="input-append date createEvent-endDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="end_time_picker' + stDate + '"><input type="text" class="starttimepick" onfocus="'+endPickerCall+'" name="data[EventDate][end_time][]" id="end_timeE" value="' + endD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><input type="hidden" name="data[EventDate][start_date][]" id="data[EventDate][start_date][]" value="'+stDate+'">' + '&nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteDate($(this));" ><img src="/img/admin/delete.png" class="createEvent-delete"></a><br><br></div>';
	      
	      
                  timePicker("start_time_picker"+stDate);
		timePicker("end_time_picker"+stDate);
	     
                   
	
          }
           return true;
           });
              
          return true;  
           // alert(sst);
           // alert(eet);
        }
        
        function addListYearly() {
            var sst = document.getElementById("startdate").value;
            var eet = document.getElementById("enddate").value;
            var rec = document.getElementById("reccuring").value;
            var startD = document.getElementById("start_timeN");
            var endD = document.getElementById("end_timeN");
            
                
           // var repeat_day = document.getElementById("repeat_day").value;
           // alert(repeat_day);
            if (sst==''||eet=='') {
                alert("You need to select Start Date/End Date first.");
                return false;
            }
             var yearArray = [sst,eet];
            
             $.post('<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/Events/findNoOfYear', {"data[dateDetail]": yearArray}, function(data) {
                 var datDate = JSON.parse(data);
          //console.log(datDate);
          var cntday = datDate.length;
           if (cntday == 0)
            {
                alert("No date is in your search criteria.");
                return false;
            }
             var myNode = document.getElementById("evtentriess");
                while (myNode.firstChild) {
                    myNode.removeChild(myNode.firstChild);
                }
          var s;
          for (s=0;s<cntday;s++) {
               var stDate = datDate[s].replace("-", "_").replace("-", "_");
             var stDateMsg = datDate[s].replace("-", "/").replace("-", "/");
            var msg;
                   var datess;
                   datess = new Date(stDateMsg);
                   msg = getWeekDay(datess); 
             var selectBox = document.getElementById("evtentriess");
	     
	      var stPicker = "start_time_picker"+stDate;
	           var endPicker = "end_time_picker"+stDate;
		   var stPickerCall = "timePicker('" + stPicker + "');";
	           var endPickerCall = "timePicker('" + endPicker + "');";
	           timePicker("start_time_picker"+stDate);
		   timePicker("end_time_picker"+stDate);
		   
	      selectBox.innerHTML = selectBox.innerHTML + '<div class="entry" id="entry' + stDate + '"><div class="createEvent-date-msg">' + msg + '</div><div class="createEvent-startDate">Start Time:</div><div id="start_time_picker' + stDate + '" class="input-append date createEvent-startDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="start_time_picker' + stDate + '"><input type="text" class="starttimepick" onfocus="'+stPickerCall+'" name="data[EventDate][start_time][]" id="start_timeE" value="' + startD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><div class="createEvent-endDate">End Time:</div><div id="end_time_picker' + stDate + '" class="input-append date createEvent-endDate-tag" ><span class="add-on"  onclick="timePicker(this.id)" id="end_time_picker' + stDate + '"><input type="text" class="starttimepick" onfocus="'+endPickerCall+'" name="data[EventDate][end_time][]" id="end_timeE" value="' + endD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><input type="hidden" name="data[EventDate][start_date][]" id="data[EventDate][start_date][]" value="'+stDate+'">' + '&nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteDate($(this));" ><img src="/img/admin/delete.png" class="createEvent-delete"></a><br><br></div>';
	      
	      
                  timePicker("start_time_picker"+stDate);
		timePicker("end_time_picker"+stDate);
	     
	     
	     
          }
          return true;
             });
            return true;
        }
       
        function getWeekDay(date) {
	  var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']
	  var month = ['January','February','March','April','May','June','July','August','September','October','November','December']
	 var day = days[ date.getDay() ];
         var mon = month[ date.getMonth() ];
         var dates = day+', '+date.getDate()+' '+mon+' '+date.getFullYear();
          return dates;
	}
        
        function reccurings(mode) {
            if (mode=="d") {
               document.getElementById("daily").style.display = "block";
                document.getElementById("weekly").style.display = "none";
                document.getElementById("monthly").style.display = "none";
                document.getElementById("yearly").style.display = "none";
                document.getElementById("examplecontainer").style.height = "542px";
            }
            else if(mode=="w") {
               document.getElementById("daily").style.display = "none";
                document.getElementById("weekly").style.display = "block";
                 document.getElementById("monthly").style.display = "none";
                 document.getElementById("yearly").style.display = "none";
                 document.getElementById("examplecontainer").style.height = "518px";
            }
             else if(mode=="m") {
               document.getElementById("daily").style.display = "none";
                document.getElementById("weekly").style.display = "none";
                 document.getElementById("monthly").style.display = "block";
                 document.getElementById("yearly").style.display = "none";
                 document.getElementById("examplecontainer").style.height = "582px";
            }
             else if(mode=="y") {
               document.getElementById("daily").style.display = "none";
                document.getElementById("weekly").style.display = "none";
                 document.getElementById("monthly").style.display = "none";
                 document.getElementById("yearly").style.display = "block";
                 document.getElementById("examplecontainer").style.height = "518px";
            }
            else
            {
                document.getElementById("daily").style.display = "none";
                document.getElementById("weekly").style.display = "none";
                document.getElementById("monthly").style.display = "none";
                document.getElementById("yearly").style.display = "none";
                document.getElementById("examplecontainer").style.height = "472px";
            }
           
        }
         
         
</script>

 <?php
 
        if(isset($this->data['Event']['recurring_type']))
        {
        $rec_type = $this->data['Event']['recurring_type']; 
        }
        else
        {
        $rec_type = '';
        }
        
        if($rec_type=="d")
        {
             $dis = "display:block;";
             $disweek = "display:none;";
             $dismonth = "display:none;";
             $disyear = "display:none;";
             $hight = "542px";
        }
        elseif($rec_type=="w")
        {
             $dis = "display:none;";
             $disweek = "display:block;";
             $dismonth = "display:none;";
             $disyear = "display:none;";
             $hight = "518px";
        }
         elseif($rec_type=="m")
        {
             $dis = "display:none;";
             $disweek = "display:none;";
             $dismonth = "display:block;";
             $disyear = "display:none;";
             $hight = "582px";
        }
         elseif($rec_type=="y")
        {
             $dis = "display:none;";
             $disweek = "display:none;";
             $dismonth = "display:none;";
             $disyear = "display:block;";
             $hight = "518px";
        }
        else
        {
        $dis = "display:none;";
        $disweek = "display:none;";
        $dismonth = "display:none;";
        $disyear = "display:none;";
        $hight = "472px";
        }
        ?>
        
<div id="examplecontainer" style="height:<?php echo $hight; ?>">
 
   <div class="StartDate"> Start Date: <input type="text" disabled name="start_dates" id="startdates" value="<?php if(isset($this->data['Event']['start_date'])) { $sdat_array = explode('-',$this->data['Event']['start_date']); echo $sdat_array[1].'-'.$sdat_array[2].'-'.$sdat_array[0];} ?>">
   <input type="hidden"  name="start_date" id="startdate" value="<?php if(isset($this->data['Event']['start_date'])) { echo $this->data['Event']['start_date']; } ?>">
    <button id="show2up" type="button"><img width="18" height="18" src="/yui/assets/calbtn.gif" alt="Calendar"></button>
    <div id="cal3Container"></div>
   </div>
    <div class="EndDate">
 End Date: <input type="text"  disabled name="end_dates" id="enddates" value="<?php  if(isset($this->data['Event']['end_date'])) { $edat_array = explode('-',$this->data['Event']['end_date']); echo $edat_array[1].'-'.$edat_array[2].'-'.$edat_array[0]; }?>">
 <input type="hidden"  name="end_date" id="enddate" value="<?php  if(isset($this->data['Event']['end_date'])) { echo $this->data['Event']['end_date']; }?>">
    <button id="show1up" type="button"><img width="18" height="18" src="/yui/assets/calbtn.gif" alt="Calendar"></button>
    <div id="cal2Container"></div>
    </div>
    <div>
        <br><hr><br>
        <?php
       
        if(isset($this->data['Event']['edate_extra']))
        {
        $edate_extra = unserialize($this->data['Event']['edate_extra']);
        }
        else
        {
          $edate_extra = '';  
        }
        if(isset($edate_extra['rec_mode']) && $edate_extra['rec_mode']=="d")
        {
            $day_count = $edate_extra['daily_days'];
        }
        else
        {
            $day_count = 1;
        }

        if(isset($edate_extra['rec_mode']) && $edate_extra['rec_mode']=="w")
        {
            $day_selected = $edate_extra['rept_days'];
            $day_selected_hidden = explode(",",$edate_extra['rept_days']);
        }
        else
        {
            $day_selected = '';
            $day_selected_hidden[] = '';
        }
        
        if(isset($edate_extra['rec_mode']) && $edate_extra['rec_mode']=="m")
        {
            $mode_selected = $edate_extra['month_mode'];
            if($mode_selected=="mode1")
            {
            $month_day = $edate_extra['month_day1'];
            $monthly_period = '';
            $monthly_pattern_day = '';
            }
            else
            {
             $month_day = '1';
             $monthly_period = $edate_extra['monthly_period'];
             $monthly_pattern_day = $edate_extra['monthly_pattern_day'];
            }
        }
        else
        {
            $mode_selected = 'mode1';
            $month_day = '1';
            $monthly_period = '';
            $monthly_pattern_day = '';
        }
        //pr($day_selected_hidden);
        ?>
        
        
        
        <div style="float:left">
 
    <select name="data[Event][recurring_type]" id="reccuring" onchange="reccurings(this.value)">
        <option value="">Reccuring Mode</option>
        <option value="d" <?php if($rec_type=="d") { echo "selected"; } ?>>Daily</option>
        <option value="w" <?php if($rec_type=="w") { echo "selected"; } ?>>Weekly</option>
        <option value="m" <?php if($rec_type=="m") { echo "selected"; } ?>>Monthly</option>
        <option value="y" <?php if($rec_type=="y") { echo "selected"; } ?>>Yearly</option>
    </select></div>
       
    <div class="daily" id="daily" style="<?php echo $dis; ?>"> * Repeat every (Days): <input type="text" name="data[Event][daily_days]" id="daily_days" value="<?php echo $day_count; ?>">
    <br><br>
    <div><a href="javascript:void(0)" onclick="addList();" class="add-more-tt">Add Date to List</a></div></div>
     
    
    <div class="weekly" id="weekly" style="<?php echo $disweek; ?>">
    <input type="hidden" name="data[Event][weekRepeatDay]" id="weekRepeatDay" value="<?php echo $day_selected; ?>">
 
        <input type="checkbox" value="7" name="repeat_day" id="repeat_day[]" <?php if(in_array('7', $day_selected_hidden)) { echo "checked"; } ?>> Su <span style="margin-left: 2px; margin-right: 2px;">|</span>
        <input type="checkbox" value="1" name="repeat_day"  id="repeat_day[]" <?php if(in_array('1', $day_selected_hidden)) { echo "checked"; } ?>> M <span style="margin-left: 2px; margin-right: 2px;">|</span>
        <input type="checkbox" value="2" name="repeat_day"  id="repeat_day[]" <?php if(in_array('2', $day_selected_hidden)) { echo "checked"; } ?>> T <span style="margin-left: 2px; margin-right: 2px;">|</span>
        <input type="checkbox" value="3" name="repeat_day"  id="repeat_day[]" <?php if(in_array('3', $day_selected_hidden)) { echo "checked"; } ?>> W <span style="margin-left: 2px; margin-right: 2px;">|</span>
        <input type="checkbox" value="4" name="repeat_day" id="repeat_day[]" <?php if(in_array('4', $day_selected_hidden)) { echo "checked"; } ?>> Th <span style="margin-left: 2px; margin-right: 2px;">|</span> 
        <input type="checkbox" value="5" name="repeat_day" id="repeat_day[]" <?php if(in_array('5', $day_selected_hidden)) { echo "checked"; } ?>> F <span style="margin-left: 2px; margin-right: 2px;">|</span>
        <input type="checkbox" value="6" name="repeat_day" id="repeat_day[]" <?php if(in_array('6', $day_selected_hidden)) { echo "checked"; } ?>> Sa
   <br><br>
    <div>
        <a href="javascript:void(0)" onclick="addListWeekly();" class="add-more-tt">Add Date to List</a>
    </div>
    </div>

      <div class="monthly" id="monthly" style="<?php echo $dismonth; ?>">
       <input type="radio" name="month_mode" id="month_mode1" value="mode1" <?php if($mode_selected=="mode1") { echo 'checked'; } ?>>
        On Day <input type="text" value="<?php echo $month_day; ?>" name="month_day1" size="10" id="month_day1"> of every month 
        <br>
        <input type="radio" name="month_mode" id="month_mode2" value="mode2"  <?php if($mode_selected=="mode2") { echo 'checked'; } ?>>
         On the  <select id="monthly_period" name="monthly_period">
                    <option value="first" <?php if($monthly_period=='first') { echo 'selected'; } ?>>First</option>
                    <option value="second" <?php if($monthly_period=='second') { echo 'selected'; } ?>>Second</option>
                    <option value="third" <?php if($monthly_period=='third') { echo 'selected'; } ?>>Third</option>
                    <option value="fourth" <?php if($monthly_period=='fourth') { echo 'selected'; } ?>>Fourth</option>
                </select>
                
                <select id="monthly_pattern_day" name="monthly_pattern_day">
                    <option value="sunday" <?php if($monthly_pattern_day=='sunday') { echo 'selected'; } ?>>Sunday</option>
                    <option value="monday" <?php if($monthly_pattern_day=='monday') { echo 'selected'; } ?>>Monday</option>
                    <option value="tuesday" <?php if($monthly_pattern_day=='tuesday') { echo 'selected'; } ?>>Tuesday</option>
                    <option value="wednesday" <?php if($monthly_pattern_day=='wednesday') { echo 'selected'; } ?>>Wednesday</option>
                    <option value="thursday" <?php if($monthly_pattern_day=='thursday') { echo 'selected'; } ?>>Thursday</option>
                    <option value="friday" <?php if($monthly_pattern_day=='friday') { echo 'selected'; } ?>>Friday</option>
                    <option value="saturday" <?php if($monthly_pattern_day=='saturday') { echo 'selected'; } ?>>Saturday</option>
                </select>       
   <br><br>
    <div>
        <a href="javascript:void(0)" onclick="addListMonthly();" class="add-more-tt">Add Date to List</a>
    </div>
    </div>
<div class="yearly" id="yearly" style="<?php echo $disyear; ?>">
   
    <div>
        <a href="javascript:void(0)" onclick="addListYearly();" class="add-more-tt">Add Date to List</a>
    </div>
    </div>
    
   </div>
    <div>&nbsp;</div>
     <div class="step3">
        <h3>
            <img  src="/img/bkg_step3.gif" alt="step3"> Review the start times and dates you've entered below. You can continue to edit them or move on to the next step.
        </h3>
     </div>
    <div id="caleventlog" class="eventlog">
                            <div class="hd">Select/Deselect Events</div>
                            <div id="evtentriess" class="bd">
                 <?php
                        if (!empty($this->data['EventDate'])) {
                            $i = 0;
                           

                        function invenDescSort($item1,$item2)
                        {
                            if ($item1['id'] == $item2['id']) return 0;
                            return ($item1['id'] > $item2['id']) ? 1 : -1;
                        }
                        $date_sort = $this->data['EventDate'];
                        usort($date_sort, 'invenDescSort');
                          // pr($date_sort);
                            foreach ($date_sort as $daten):
                           
                            $ev_date = str_replace("-",",",date("Y_n_j",strtotime($daten['date'])));
                           //$ev_date = $date['date'];
                          
                                ?>
                               <div id="entry<?php echo $ev_date; ?>" class="entry">
		    <div class="createEvent-date-msg">
		    <?php echo date('l, d F Y', strtotime($daten['date'])); ?>
		    </div>
		    <div class="createEvent-startDate">
			Start Time:
		    </div>
		    <div id="start_time_picker<?php echo $ev_date; ?>" class="input-append date createEvent-startDate-tag">
		    <span class="add-on"  onclick="timePicker(this.id)" id="start_time_picker<?php echo $ev_date; ?>">
			<input type="text" class="starttimepick" id="start_timeE" name="data[EventDate][start_time][]" value="<?php echo $daten['start_time']; ?>" onfocus="timePicker('start_time_picker<?php echo $ev_date; ?>')">
                    	<i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i>
		       </span>
		    </div>
		    <div class="createEvent-endDate">
			End Time:
		    </div>
		    <div id="end_time_picker<?php echo $ev_date; ?>" class="input-append date createEvent-endDate-tag">
			<span class="add-on"  onclick="timePicker(this.id)" id="end_time_picker<?php echo $ev_date; ?>">
			<input type="text" value="<?php echo $daten['end_time']; ?>" class="starttimepick" id="end_timeE" name="data[EventDate][end_time][]" onfocus="timePicker('end_time_picker<?php echo $ev_date; ?>')">
			   <i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i>
			</span>
    </div>
	<input type="hidden" value="<?php echo $ev_date; ?>" name="data[EventDate][start_date][]" id="data[EventDate][start_date][]">
                      
                            &nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteDate($(this));"><img src="/img/admin/delete.png" class="createEvent-delete"></a><br><br>
                    </div>
                                <?php
				echo "<script> timePicker('start_time_picker".$ev_date."'); timePicker('end_time_picker".$ev_date."'); </script>";
				
                                $i++;
                            endforeach;
                        }
                            ?>
                            </div>
</div>
<div>
    
    
</div>

<!--END SOURCE CODE FOR EXAMPLE =============================== -->
</div>