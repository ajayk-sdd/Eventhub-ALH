<!------------ New Date Time Start Here ----------------->

<style type="text/css">
    /*margin and padding on body element
      can introduce errors in determining
      element position and are not recommended;
      we turn them off as a foundation for YUI
      CSS treatments. */
    body {
        margin:0;
        padding:0;
    }
    .ui-widget
    {
        font-size:12px !important;
    }
</style>

<!--begin custom header content for this example-->
<style type="text/css">


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
    
</style>

<!--end custom header content for this example-->
<div class="yui-skin-sam">
    <!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->

    <div id="cal1Container"></div>


    <script type="text/javascript">
        function deleteDate(el) {
            el.parent().remove();
        }
        
        YAHOO.namespace("example.calendar");

        YAHOO.example.calendar.init = function() {

            var eLog = YAHOO.util.Dom.get("evtentries");
            var eCount = 1;
            var selectedDate = '';
            var array = '';
            var startD = YAHOO.util.Dom.get("start_timeN");
            var endD = YAHOO.util.Dom.get("end_timeN");
           // var smode = YAHOO.util.Dom.get("start_timeM");
           // var emode = YAHOO.util.Dom.get("end_timeM");


            function logEvent(msg, date) {
             	       
		date = String(date)
	        date = date.replace(",", "_").replace(",", "_");
		var stPicker = "start_time_picker"+date;
	        var endPicker = "end_time_picker"+date;
		var stPickerCall = "timePicker('" + stPicker + "');";
	        var endPickerCall = "timePicker('" + endPicker + "');";
	        timePicker("start_time_picker"+date);
		timePicker("end_time_picker"+date);
		
	        eLog.innerHTML = eLog.innerHTML + '<div class="entry" id="entry' + date + '"><div class="createEvent-date-msg">' + msg + '</div><div class="createEvent-startDate">Start Time:</div><div id="start_time_picker' + date + '" class="input-append date createEvent-startDate-tag"><span class="add-on"  onclick="timePicker(this.id)" id="start_time_picker' + date + '"><input type="text" class="starttimepick" onfocus="'+stPickerCall+'" name="data[EventDate][start_time][]" id="start_timeE" value="' + startD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div><div class="createEvent-endDate">End Time:</div><div id="end_time_picker' + date + '" class="input-append date createEvent-endDate-tag"><span class="add-on"  onclick="timePicker(this.id)" id="end_time_picker' + date + '"><input type="text" name="data[EventDate][end_time][]" class="starttimepick" onfocus="'+endPickerCall+'" id="end_timeE" value="' + endD.value + '" /><i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i></span></div>' + '&nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteDate($(this));" ><img src="/img/admin/delete.png" class="createEvent-delete" ></a><br><br></div>';
		
		timePicker("start_time_picker"+date);
		timePicker("end_time_picker"+date);
		
               
                eCount++;
            }


            function dateToLocaleString(dt, cal) {
                var wStr = cal.cfg.getProperty("WEEKDAYS_LONG")[dt.getDay()];
                var dStr = dt.getDate();
                var mStr = cal.cfg.getProperty("MONTHS_LONG")[dt.getMonth()];
                var yStr = dt.getFullYear();
                return (wStr + ", " + dStr + " " + mStr + " " + yStr);
            }

            function mySelectHandler(type, args, obj) {
                var selected = args[0];
                var selDate = this.toDate(selected[0]);
                //selectedDate += [selected[0]]+'-';
                //alert(selectedDate);
                //dateListArray('SL',selected[0]);
                logEvent(dateToLocaleString(selDate, this), selected[0]);

                var mi = document.createElement("input");
                mi.setAttribute("type", "hidden");
                mi.setAttribute("value", selected[0]);
                mi.setAttribute("name", "data[EventDate][start_date][]");
                mi.setAttribute("id", "data[EventDate][start_date][]");
		
		selDate = String(selected[0]);
	        selDate = selDate.replace(",", "_").replace(",", "_");
                var foo = document.getElementById("entry" + selDate);

                //Append the element in page (in span).
                foo.appendChild(mi);


            }
            ;

            function myDeselectHandler(type, args, obj) {
                var deselected = args[0];
                //alert(deselected);
                var deselDate = this.toDate(deselected[0]);
                //alert(obj);
                //dateListArray('DS',deselected[0]);

		date = String(deselected[0])
	        date = date.replace(",", "_").replace(",", "_");
		
                (elems = document.getElementById("entry" + date)).parentNode.removeChild(elems);
                eCount--;
                //logEvent("DESELECTED: " + dateToLocaleString(deselDate, this));
            }
            ;


<?php
if (!empty($this->data['EventDate'])) {

    foreach ($this->data['EventDate'] as $dates):
        $del_date[] = date("n/j/Y", strtotime($dates['date']));
        //$s = "9/25/2014,9/15/2014,8/22/2014,8/9/2014,8/21/2014";

    endforeach;
    $dateDel = implode(',', $del_date);
    $selected = ',selected:"' . $dateDel . '"';
}
else {
    $selected = '';
}
?>
            // Enable navigator with a custom configuration
            var navConfig = {
                strings: {
                    month: "Choose Month",
                    year: "Enter Year",
                    submit: "OK",
                    cancel: "Cancel",
                    invalidYear: "Please enter a valid year"
                },
                monthFormat: YAHOO.widget.Calendar.SHORT,
                initialFocus: "year"
            };
            YAHOO.example.calendar.cal1 =
                    new YAHOO.widget.CalendarGroup("cal1", "cal1Container", {MULTI_SELECT: true, PAGES: 2, navigator: navConfig, mindate: "<?php echo date('m/d/Y'); ?>"<?php echo $selected; ?>});

            YAHOO.example.calendar.cal1.selectEvent.subscribe(mySelectHandler, YAHOO.example.calendar.cal1, true);
            YAHOO.example.calendar.cal1.deselectEvent.subscribe(myDeselectHandler, YAHOO.example.calendar.cal1, true);

            YAHOO.example.calendar.cal1.render();
        }

        YAHOO.util.Event.onDOMReady(YAHOO.example.calendar.init);
    </script>
    <div class="step3">
        <h3>
            <img  src="/img/bkg_step3.gif" alt="step3"> Review the start times and dates you've entered below. You can continue to edit them or move on to the next step.
        </h3>
    </div>
    <div id="caleventlog" class="eventlog">

        <div class="hd">Select/Deselect Events</div>
        <div id="evtentries" class="bd">
            <?php
            if (!empty($this->data['EventDate'])) {
                $i = 0;

                function invenDescSorts($item1, $item2) {
                    if ($item1['id'] == $item2['id'])
                        return 0;
                    return ($item1['id'] > $item2['id']) ? 1 : -1;
                }

                $date_sorts = $this->data['EventDate'];
                usort($date_sorts, 'invenDescSorts');

                foreach ($date_sorts as $date):
                    $ev_date = str_replace("-", "_", date("Y_n_j", strtotime($date['date'])));
                    ?>
                    <div id="entry<?php echo $ev_date; ?>" class="entry">
		    <div class="createEvent-date-msg">
		    <?php echo date('l, d F Y', strtotime($date['date'])); ?>
		    </div>
		    <div class="createEvent-startDate">
			Start Time:
		    </div>
		    <div id="start_time_picker<?php echo $ev_date; ?>" class="input-append date createEvent-startDate-tag">
			<span class="add-on"  onclick="timePicker(this.id)" id="start_time_picker<?php echo $ev_date; ?>">
			<input type="text" class="starttimepick" id="start_timeE" name="data[EventDate][start_time][]" value="<?php echo $date['start_time']; ?>" onfocus="timePicker('start_time_picker<?php echo $ev_date; ?>')">
                        <i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i>
		       </span>
		    </div>
		    <div class="createEvent-endDate">
			End Time:
		    </div>
		    <div id="end_time_picker<?php echo $ev_date; ?>" class="input-append date createEvent-endDate-tag">
			<span class="add-on"  onclick="timePicker(this.id)" id="end_time_picker<?php echo $ev_date; ?>">
			<input type="text" value="<?php echo $date['end_time']; ?>" class="starttimepick" id="end_timeE" name="data[EventDate][end_time][]" onfocus="timePicker('end_time_picker<?php echo $ev_date; ?>')">
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
    <div style="clear:both"></div>

    <div style="clear:both" ></div>

    <!--END SOURCE CODE FOR EXAMPLE =============================== -->
</div>


<!-------------New Date Time End Here ---------------------->

