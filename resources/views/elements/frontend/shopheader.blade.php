<div class="header">
    <header>
        @include('elements/frontend/headerbase') 
        <span class="search">
            <input id="header-search-txt" placeholder="产品搜索">
            <i class="search-btn" ></i>
            <script type="text/javascript">
             $(document).ready(function(){
                 var tag_id = '';
                 $(".search-btn").click(function(){
                     var text = $.trim($("#header-search-txt").val());
                     (text=='') ? alert('请输入要搜索的关键字！') : searchTagId(text);
                 });
                 $("#header-search-txt").on("keydown", function(event){
                     if ( event.which == 13 ) {
                         event.preventDefault();
                         var text = $.trim($("#header-search-txt").val());
                         (text=='') ? alert('请输入要搜索的关键字！') : searchTagId(text);
                     }
                 });
                 function searchTagId(s_tag) {
                     window.open("/shop/productsearch/"+s_tag);
                     /* $.ajax({
                      *     async: false,
                      *     url:"/shop/productsearch/"+s_tag,
                      *     type:'get',
                      *     success: function (data) {
                      *         var tag_id = data;
                      *         window.open("/content/searchbytag/"+tag_id);
                      *     },
                      *     error : function() {
                      *         //alert('请勿搜索非法关键字！');
                      *     }
                      * });*/
                 }
             });
            </script>
        </span>
    </header>
</div>
