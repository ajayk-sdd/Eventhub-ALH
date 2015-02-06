 var fbtoken;
 window.fbAsyncInit = function() {
       FB.init({
            appId: '725247164234660', // App ID 388966297904278
//channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
            status: true, // check login status
            cookie: true, // enable cookies to allow the server to access the session
            xfbml: true  // parse XFBML
           
        });
        FB.Event.subscribe('auth.authResponseChange', function(response) {
        });
    };
// Load the SDK asynchronously
    (function(d) {
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement('script');
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));
    function testAPI(s,re) {
       //alert(re);
//console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
	    /*var session = FB.getSession();
                fbtoken = session.access_token;*/
		/*response.access = s;
                alert(response);*/
               // console.log(response);exit;
                response.access = s;
            $.post(base_url+'/Users/fb_connect', {"data[User]": response}, function(data) {
                  var aftr = '';
                  
                $( "#dialog" ).dialog({
                  title: "Facebook Events",
                  close: function() {
                        window.location.href = base_url+re;
                   },
                  buttons: {
                    'Fetch Event': function () {
                        $(this).dialog("close");
                        var FBpopupWindow = window.open(base_url+'/Users/facebookEventCron', "FBpopupWindow", "width=300,height=300,scrollbars=yes");
                   
                        window.location.href = base_url+re;
                   },
                   'Cancel':function() {
                        $(this).dialog("close");
                        window.location.href = base_url+re;
                    } 
                    }});
                    //window.open(base_url+'/Users/facebookEventCron', "popupWindow", "width=300,height=300,scrollbars=yes");
                    
                   // window.location.href = base_url+re;
                    
            });
        });
    }

    function facebookLogin(red) {
      $("#loader_login").show();
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                var uid = response.authResponse.userID;
                var accessToken = response.authResponse.accessToken;
                
                testAPI(accessToken,red);
            } else if (response.status === 'not_authorized') {
                FB.login(function(response) {
                     var accessToken = '';
                    testAPI(accessToken,red);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            } else {
                FB.login(function(response) {
                     var accessToken = '';
                    testAPI(accessToken,red);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            }
        });
    }
   
   function createEvent(name, startTime, endTime, location, description) {
    var eventData = {
       // "access_token": "585437454897001|N5ujAK7LxVGqBaZOa7Px1SyazP8",
        "name" : name,
        "start_time" : startTime,
        "end_time":endTime,
        "location" : location,
        
        "description":description,
        "privacy":"OPEN"
    }
    FB.api("/me/events",'post',eventData,function(response){console.log(response);
        if(response.id){
            alert("We have successfully created a Facebook event with ID: "+response.id);
        }
    });
   }
   
    function postEvent(){
	var name = "My Amazing Event";
	var startTime = "2014-08-30T17:54:00-04:00";
	var endTime = "2014-08-30T17:54:00-04:00";
	var location = "Dhaka";
	var description = "It will be freaking awesome";
	createEvent(name, startTime,endTime, location, description);
    }
    
    
     function facebookEvents() {
       
        FB.getLoginStatus(function(response) {
            var aftr = '';
            if (response.status === 'connected') {
                var uid = response.authResponse.userID;
                var accessToken = response.authResponse.accessToken;
                $("#load_fb").show();
                facebookEventsList(0,uid,aftr);
            } else if (response.status === 'not_authorized') {
                FB.login(function(response) {
                   var uid = response.authResponse.userID;
                   $("#load_fb").show();
                    facebookEventsList(0,uid,aftr);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            } else {
                FB.login(function(response) {
                   var uid = response.authResponse.userID;
                   $("#load_fb").show();
                    facebookEventsList(0,uid,aftr);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            }
        });
        
     
       
    }
    
    function facebookEventsList(red,uid,aft) {
    var aa = "/me/events?fields=cover,description,end_time,id,location,name,owner,start_time,ticket_uri,timezone,updated_time,venue&type=attending&limit=30&since=now&after="+aft;
       //alert(aa);
       FB.api(aa,
    function (responses) {
      if (responses && !responses.error) {
            responses.uid = uid;
             if (responses.data[0]) {
            $.post(base_url+'/Users/fb_events', {"data[events]": responses}, function(data) {
                 
            if (data==2) {
                  $("#load_fb").hide();
                  alert("Your ALH Account is linked with other FB Account. Please try with your Connected FB Account.");
                  return false;
            }
            return true;
            });
       
      if (responses.paging.next) {
            facebookEventsList(red,uid,responses.paging.next);
       }
       else
       {
              var aftrM = '';
             facebookEventsListMaybe(red,uid,aftrM);
       }
      
        console.log(responses);
      }
      else
      {
             var aftrM = '';
             facebookEventsListMaybe(red,uid,aftrM);
      }
      }
      else{
             location.reload(); 
      }
    }
       );
       
    }
    
    function facebookEventsListMaybe(red,uid,aftrM) {
     
    var aab = "/me/events?fields=cover,description,end_time,id,location,name,owner,start_time,ticket_uri,timezone,updated_time,venue&type=maybe&limit=30&since=now&after="+aftrM;
    
       FB.api(aab,
    function (responsesmay) {
      if (responsesmay && !responsesmay.error) {
           
            responsesmay.uid = uid;
            if (responsesmay.data[0]) {
                 
            
            $.post(base_url+'/Users/fb_events', {"data[events]": responsesmay}, function(data) {
            
            });
       
      if (responsesmay.paging.next) {
            facebookEventsListMaybe(red,uid,responsesmay.paging.next);
       }
        else
      {
             var aftrD = '';
             facebookEventsListDeclined(red,uid,aftrD);
      }
      
            }
             else
      { 
             var aftrD = '';
             facebookEventsListDeclined(red,uid,aftrD);
      }
       console.log(responsesmay.data);
      }
      else
      {
             location.reload(); 
      }
    }
       );
       
    }
    
     function facebookEventsListDeclined(red,uid,aftrD) {
     
    var aab = "/me/events?fields=cover,description,end_time,id,location,name,owner,start_time,ticket_uri,timezone,updated_time,venue&type=declined&limit=30&since=now&after="+aftrD;
    
       FB.api(aab,
    function (responsesdec) {
      if (responsesdec && !responsesdec.error) {
            
            responsesdec.uid = uid;
            if (responsesdec.data[0]) {
                 
            
            $.post(base_url+'/Users/fb_events', {"data[events]": responsesdec}, function(data) {
            
            });
       
      if (responsesdec.paging.next) {
            facebookEventsListDeclined(red,uid,responsesdec.paging.next);
       }
        else
      {
             var aftrN = '';
             facebookEventsListNotReply(red,uid,aftrN);
      }
      
            }
             else
      {
             var aftrN = '';
             facebookEventsListNotReply(red,uid,aftrN);
      }
      console.log(responsesdec.data);
      }
      else
      {
             location.reload(); 
      }
    }
       );
       
    }
    
     function facebookEventsListNotReply(red,uid,aftrN) {
     
    var aab = "/me/events?fields=cover,description,end_time,id,location,name,owner,start_time,ticket_uri,timezone,updated_time,venue&type=not_replied&limit=30&since=now&after="+aftrN;
    
       FB.api(aab,
    function (responsesnot) {
      if (responsesnot && !responsesnot.error) {
            
            responsesnot.uid = uid;
            if (responsesnot.data[0]) {
                 
            
            $.post(base_url+'/Users/fb_events', {"data[events]": responsesnot}, function(data) {
             if (!responsesnot.paging.next)
             { 
              if (red==0) {
                   $("#load_fb").hide();
                   
                    $("#event_came").html("Your Facebook Event Fetch Successfuly.");
                    
                     setTimeout(function () {
                         window.close(base_url+'/Users/facebookEventCron', "FBpopupWindow");
                     }, 2000);
                   
                 
                          
              }
                  else
                  {
                     window.location.href = base_url+red;
                  }
             }
            });
       
      if (responsesnot.paging.next) {
            facebookEventsListNotReply(red,uid,responsesnot.paging.next);
       }
       
      
            }
            else
            
      {
            if (red==0) {
                   $("#load_fb").hide();
                   
                    $("#event_came").html("Your Facebook Event Fetch Successfuly.");
                    
                     setTimeout(function () {
                         window.close(base_url+'/Users/facebookEventCron', "FBpopupWindow");
                     }, 2000);
                  
                
       
              }
                  else
                  {
                     window.location.href = base_url+red;
                  }
      }
            
      console.log(responsesnot.data);
      }
      else
      {
             location.reload(); 
      }
    }
       );
       
    }
   
   
    function fbEventAttend(EId,event_id) {
       
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                var uid = response.authResponse.userID;
                var accessToken = response.authResponse.accessToken;
                
                fbevntatt(EId,event_id);
            } else if (response.status === 'not_authorized') {
                FB.login(function(response) {
                    fbevntatt(EId,event_id);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            } else {
                FB.login(function(response) {
                    fbevntatt(EId,event_id);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            }
        });
        
     
       
    }
    
    function totalCount(eId,event_id) {
      
         FB.api(
    "/"+eId+"/attending?summary=true&limit=0",
    function (response) {
      if (response && !response.error) {
           response.event_id = event_id;
           $.post(base_url+'/Events/totalCount', {"data[eventattendtotal]": response}, function(data) {
           
           });

      }
    }
       );
         
    }
    function fbevntatt(eId,event_id) {
         //alert(eId);
       
          
       FB.api(
    "/"+eId+"/attending?fields=picture,name,rsvp_status&limit=50",
    function (response) {
      if (response && !response.error) {
       response.pageid = eId;
       response.event_id = event_id;
       totalCount(eId,event_id);
      
         $.post(base_url+'/Events/addattendmult', {"data[eventattend]": response}, function(data) {
              //alert(data);
            location.reload(); 
            //$("#event_came").html() = response;       
           });
       // console.log(response);
      }
    }
       );
    }
    
    
     function fbAddedAccCheck(uId) {
       
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                var uid = response.authResponse.userID;
                var accessToken = response.authResponse.accessToken;
                
                fbAddedAcc(uId,accessToken);
            } else if (response.status === 'not_authorized') {
                FB.login(function(response) {
                      var accessToken = response.authResponse.accessToken;
                    fbAddedAcc(uId,accessToken);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            } else {
                FB.login(function(response) {
                      var accessToken = response.authResponse.accessToken;
                    fbAddedAcc(uId,accessToken);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            }
        });
        
     
       
    }
    
     function fbAddedAcc(uId,AccTkn) {
        // alert(uId);
       FB.api(
    "/me",
    function (response) {
      if (response && !response.error) {
       response.userid = uId;
       response.access_token = AccTkn;
         $.post(base_url+'/Users/fbacc_link', {"data[fbadded]": response}, function(data) {
              if(data==1)
             {
              alert("You ALH account successfully linked with your Facebook Account.");
              var aft = '';
              facebookEventsList("/users/viewProfile",response.id,aft);
              //location.reload(); 
             }
             else
             {
              alert("This Facebook Account is already linked with other Account. Try again with other FB Account.");
             }
             
            //$("#event_came").html() = response;       
           });
        console.log(response);
      }
    }
       );
    }
    
    function fbAttEventPost(id,event_id,log_in) {
       $("#load_fb").show();
     FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                var uid = response.authResponse.userID;
                var accessToken = response.authResponse.accessToken;
               
                fbAttEventPostsub(id,uid,event_id,accessToken,log_in);
            } else if (response.status === 'not_authorized') {
                FB.login(function(response) {
                     var uidn = response.authResponse.userID;
                     var accessToken = response.authResponse.accessToken;
                     
                    fbAttEventPostsub(id,uidn,event_id,accessToken,log_in);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            } else {
                FB.login(function(response) {
                     var uidnm = response.authResponse.userID;
                    var accessToken = response.authResponse.accessToken;
                    
                    fbAttEventPostsub(id,uidnm,event_id,accessToken,log_in);
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            }
        });
    }
    function fbAttEventPostsub(id,user_id,event_id,s,log_in) {
       /* make the API call */
       
      
              
       
       FB.api('/me', function(response) {
	   
                response.access = s;
                 if (log_in==0) {
            $.post(base_url+'/Users/fb_connect', {"data[User]": response}, function(data) {
                 
                   eventattsubmit(id,user_id,event_id);
            });
                 }
                 else
                 {
                     $.post(base_url+'/Users/fbacc_check', {"data[User]": response}, function(data) {
                            if (data==2) {
                            alert("Your FB Account is Successfully linked with ALH Account!");
                            //facebookEventsList(0);
                             eventattsubmit(id,user_id,event_id);
                            }
                            else if(data==1) {
                                    $("#load_fb").hide();
                            alert("Your FB Account is already linked with other ALH account. Please use different FB account!");
                           
                            }
                            else if(data==0) {
                                    $("#load_fb").hide();
                            alert("Your ALH Account is also linked with other FB account. Please use your linked FB account!");
                           
                            }
                            else
                            {
                                   //facebookEventsList(0);
                                   eventattsubmit(id,user_id,event_id);
                            }
                   
            });
                 }
        });
       
     
        
    }
    function eventattsubmit(id,user_id,event_id) {
      
       var eventData = {
     "rsvp_status": "attending",
      "user_id": user_id
       
    }
      
FB.api(
    "/"+id+"/attending",
    "POST",eventData,
    function (response) {
       
      if (response && !response.error) {
       
     asd(id,user_id,event_id);
      
        
      }
      else
      {
            if (response.error.code==100) {
                 asd(id,user_id,event_id);
            }
      }
    }
);
    }
    function asd(id,user_id,event_id) {
      
       FB.api('/me?fields=picture,name', function(response) {
              
             
          response.userfb_id = user_id;
          response.fbeventid = id;
          response.event_id = event_id;
         
          $.post(base_url+'/Events/addattend', {"data[eventattend]": response}, function(data) {
           if (data==0) {
              alert("You are already requested to going to this Event.")
           }
           else
           {
             alert("Thanks for going to this event.");
           }
           //location.reload();
          fbevntatt(id,event_id);
           });
      
       });
    }
    
    
     function fbAttEventRemove(event_id,user_id,Eid) {
       $("#load_fb").show();
     FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                var uid = response.authResponse.userID;
                var accessToken = response.authResponse.accessToken;
               
               if(uid==user_id)
               {
                fbAttEventRemoveFunc(event_id,user_id,Eid);
               }
               else
               {
                     alert("Your ALH Account is also linked with other FB account. Please use your linked FB account!");
                     $("#load_fb").hide();
               }
            } else if (response.status === 'not_authorized') {
                FB.login(function(response) {
                     var uidn = response.authResponse.userID;
                     var accessToken = response.authResponse.accessToken;
                       
                     if(uidn==user_id)
               {
                fbAttEventRemoveFunc(event_id,user_id,Eid);
               }
               else
               {
                     alert("Your ALH Account is also linked with other FB account. Please use your linked FB account!");
                      $("#load_fb").hide();
               }
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            } else {
                FB.login(function(response) {
                     var uidnm = response.authResponse.userID;
                    var accessToken = response.authResponse.accessToken;
                  
                    if(uidnm==user_id)
               {
                fbAttEventRemoveFunc(event_id,user_id,Eid);
               }
               else
               {
                     alert("Your ALH Account is also linked with other FB account. Please use your linked FB account!");
                      $("#load_fb").hide();
               }
                }, {scope: 'email,user_likes,user_events,rsvp_event,public_profile,user_about_me,user_activities,user_friends,user_location'});
            }
        });
    }
    
     function fbAttEventRemoveFunc(event_id,user_id,Eid) {
      
       var eventData = {
         "user_id": user_id
       
    }
      
FB.api(
    "/"+event_id+"/declined",
    "POST",eventData,
    function (response) {
       
      if (response && !response.error) {
       
         var remove = [event_id,user_id];
      
           totalCount(event_id,Eid);
           
       $.post(base_url+'/Events/removeattend', {"data[eventattremove]": remove}, function(data) {
           if(data==1)
           {
             alert("You are not going to this event.");
             location.reload();
           }
           else
           {
              alert("There is some issue in your not going request. Please try again!");
           }
           });
      
        
      }
    }
);
    }

    