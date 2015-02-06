
   function checkCategories(opValue,selId) {
       
       if (opValue!='') {
              
       if (selId=="EventCategory0") {
        
        var cat1 = document.getElementById("EventCategory1").value;
        var cat2 = document.getElementById("EventCategory2").value;
      
        if (cat1!=opValue && cat2!=opValue) {
            $("#cat-error").hide();
            return true;
        }
        else
        {
            $("#cat-error").show();
            $("#cat-error").html("Already selected by other categories dropdown.");
            document.getElementById("EventCategory0").value='';
            return false;
        }
       }
       else if (selId=="EventCategory1") {
        
        var cat1 = document.getElementById("EventCategory0").value;
        var cat2 = document.getElementById("EventCategory2").value;
   
        if (cat1!=opValue && cat2!=opValue) {
            $("#cat-error").hide();
            return true;
        }
        else
        {
            $("#cat-error").show();
            $("#cat-error").html("Already selected by other categories dropdown.");
            document.getElementById("EventCategory1").value='';
            return false;
        }
       }
       else
       {
        var cat1 = document.getElementById("EventCategory0").value;
        var cat2 = document.getElementById("EventCategory1").value;
      
        if (cat1!=opValue && cat2!=opValue) {
            $("#cat-error").hide();
            return true;
        }
        else
        {
            $("#cat-error").show();
            $("#cat-error").html("Already selected by other categories dropdown.");
            document.getElementById("EventCategory2").value='';
            return false;
        }
       }
       }
       return true;
    }
    
    function checkVibes(opValue,selId) {
       
       if (opValue!='') {
              
       if (selId=="EventVibe0") {
        
        var vib1 = document.getElementById("EventVibe1").value;
        var vib2 = document.getElementById("EventVibe2").value;
      
        if (vib1!=opValue && vib2!=opValue) {
            $("#vib-error").hide();
            return true;
        }
        else
        {
            $("#vib-error").show();
            $("#vib-error").html("Already selected by other vibes dropdown.");
            document.getElementById("EventVibe0").value='';
            return false;
        }
       }
       else if (selId=="EventVibe1") {
        
        var vib1 = document.getElementById("EventVibe0").value;
        var vib2 = document.getElementById("EventVibe2").value;
   
        if (vib1!=opValue && vib2!=opValue) {
            $("#vib-error").hide();
            return true;
        }
        else
        {
            $("#vib-error").show();
            $("#vib-error").html("Already selected by other vibes dropdown.");
            document.getElementById("EventVibe1").value='';
            return false;
        }
       }
       else
       {
        var vib1 = document.getElementById("EventVibe0").value;
        var vib2 = document.getElementById("EventVibe1").value;
      
        if (vib1!=opValue && vib2!=opValue) {
            $("#vib-error").hide();
            return true;
        }
        else
        {
            $("#vib-error").show();
            $("#vib-error").html("Already selected by other vibes dropdown.");
            document.getElementById("EventVibe2").value='';
            return false;
        }
       }
       }
       return true;
    }
    
    function checkprice() {
    $(".img-prc-rmv").show();
    }