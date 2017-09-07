@if(!empty($popbottom))
<script>
 $(document).ready(function(){
     var popSeconds = {{ config('settings.popbottom_close_seconds') ? config('settings.popbottom_close_seconds') : 5 * 60 }};
     // get last show at
     var lastClosedAt = localStorage.getItem("popbottom.last_closed_at");
     var pastAt = moment().subtract(popSeconds, "seconds");
     if(lastClosedAt && pastAt < moment(lastClosedAt)){
         $(".popbottom-container").delay(moment(lastClosedAt) - pastAt).fadeIn(2000);
     }else{
         $(".popbottom-container").fadeIn(2000);
     }
     
     $("#popbottom-close-btn").click(function(){
         localStorage.setItem("popbottom.last_closed_at", moment().format());
         $(".popbottom-container").fadeOut(1000);
         $("#popbottom-close-btn").fadeOut(1000);
         $( ".popbottom-container" ).delay(1000 * popSeconds).fadeIn( 2000 );
         $( "#popbottom-close-btn" ).delay(1000 * popSeconds).fadeIn( 2000 );
     });
 });
</script>
<div class="popbottom-container" style="display:none;">
    <img src="{{ $popbottom  }}"/>

    <div id="popbottom-close-btn" class="btn-close " >×</div>
</div>


@endif
