function setup(){
    $('.browserpage').hide();    
    change_page();
}

function change_page(){
    
    //work out which page we are on
    var current_page = -1;
    
    for (var i=0; i < $('.browserpage').length; i++) {
        if($(".browserpage:eq(" + i + ")").is(':visible')){
            current_page = i;
        }
    };
    
        console.debug(current_page);     
     //if at start, show first item, if not iterate to next
     if (current_page < 0){
         $(".browserpage:eq(0)").show();
    }else{
         $(".browserpage:eq(" + current_page + ")").hide();
         $(".browserpage:eq(" + (current_page + 1) + ")").show();
    }
    
    //set timeout
    setTimeout('change_page()', $('#refreshdelay').val())
}