<html>
<head>
<link rel="stylesheet" href="/js/libs/jquerytree/css/jquery.treeview.css" />
<script src="/js/libs/jquerytree/lib/jquery.js" type="text/javascript"></script>
<script src="/js/libs/jquerytree/treeview/jquery.treeview.js" type="text/javascript"></script>
<script src="/js/libs/jquerytree/lib/jquery.cookie.js" type="text/javascript"></script>
<script type="text/javascript">

    //$(document).ready(function(){$("#tree1").hide();});
    function displaytree() {
		$("#tree").treeview({
			collapsed: true,
			animated: "medium",
			control:"#sidetreecontrol",
			persist: "location"
		});
	}
	var args;
	var callback;
	function setCallback(_args,_callback){
		args = _args;
		callback = _callback;
	}
	function onClickItem(){
		alert(callback);
		if(callback && callback=="function"){
			alert("我是回调函数");
		}
	}

</script>
<style type="text/css">
	.treeview a.selected
	{
	 background-color:#385DB1;
	}
	.list-group-item {
	    position: relative;
	    display: block;
	    padding: 3px 3px 3px 15px;
	    margin-bottom: -1px;
	    background-color: #fff;
	    border: 1px solid #ddd;
	}
	a.list-group-item:hover
	{
	color:#ffffff;
	background-color:#339933;
	}
</style>
</head>
<body>
<div class="row">
					<div class="col-md-2"> 
			 			<ul id="tree">
			 			@if (!empty($categoryData) )
				 			@foreach ($categoryData[0] as $firstKey => $firstValue)
						 				<li><a class="list-group-item" onclick="onClickItem()" href="{{ $categoryData[1][$firstKey]['href'] }}">{{ $categoryData[1][$firstKey]['title'] }}</a>
						 					@if (count($firstValue)>0)
							 					<ul>
							 					@foreach($firstValue as $secondKey => $secondValue)
							 						<li><a class="list-group-item" href="{{ $categoryData[1][$secondKey]['href'] }}"> {{ $categoryData[1][$secondKey]['title'] }}</a>
							 							@if (count($secondValue)>0)
								 							<ul>  
								 							 @foreach($secondValue as $thirdKey => $thirdValue)
								 							 	<li><a class="list-group-item" href="{{ $categoryData[1][$thirdKey]['href'] }}"> {{ $categoryData[1][$thirdKey]['title'] }}</a>
								 							 	</li>
								 							 @endforeach	
								 							</ul>
								 						@endif
							 						</li>
							 					@endforeach
							 					</ul>
							 				@endif
						 				</li>
				 			@endforeach
				 		@endif
						</ul>
			 		</div>

</body>
   <script type="text/javascript">
    	displaytree();
    </script>
</html>

