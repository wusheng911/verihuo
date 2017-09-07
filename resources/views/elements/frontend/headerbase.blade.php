<style>
.am-nav-pills>li+li{
	margin-left:0px;
    margin-top: 0;
}
</style>
<script type="text/javascript">
function registerCheck(){
	var msg = "";
	var name = $('#customerName').val();
	var password = $('#customerPassword').val();
	var confirmPassword = $('#customerPassword').val();
	var email = $('#customerEmail').val();
	if(name==""){
		msg = "姓名不能为空";	
	}
	if(password.length<6){
		msg = "密码不能小于六位";
	}
	if(confirmPassword != password){
		msg = "两次输入的密码不一样";
	}
	if(!checkEmail(email)){
		msg = "您输入的邮箱格式不对";
	}
	if(msg !=""){
		alert(msg);
		return false;
	}else{
		return true;
	}
}
function checkEmail(myemail){
	//　　var myforms=document.forms;
	//　　var myemail=myforms[0].email.value;
	　　var myReg=/^[a-zA-Z0-9_-]+@([a-zA-Z0-9]+\.)+(com|cn|net|org)$/;
 
	　　if(myReg.test(myemail)){
		　　　　return true;
		　　}else{
		　　　　return false;
		}
}	
</script>
<header class="am-topbar" style="flex: 0 0 auto;">
<div>
		<div class="home-nav-img" >
			<a class="home-nav-a" href="/"><img style="width:100%;height:100%;" src="/assets/img/frontend/logo.png"></a>
		</div>
	<div style=" font-size:0px;margin:auto;text-align:center;font-size:18px;">
		<div class="home-nav-div"><a class="home-nav-a"  href="/">Home</a></div>
		<div class="home-nav-div"><a class="home-nav-a" href="{{ action('BlogController@getArticleList') }}">Blog</a></div>
		<div class="home-nav-div"><a class="home-nav-a"  data-toggle="modal" data-am-modal="{target: '#my-popup'}" href="">About</a></div>
					@if(Auth::user())
						  <div class="home-nav-div"><a class="home-nav-a"  href="{{ action('CustomerController@personal') }}">My Verihuo</a></div>
					@endif
		@if(Auth::user())
		@else
						  <div class="home-nav-div"><a class="home-nav-a"  data-toggle="modal" data-target="#register" >Register</a></div>
						  <div class="home-nav-div"><a class="home-nav-a" data-toggle="modal" data-target="#login" >Login</a></div>
		@endif
	</div>
		@if(Auth::user())
			 <div class="home-nav-user-info">
				<div class="home-nav-div"><a style="margin-right:0px;" class="home-nav-a" href="javascript:void(none)"> {{str_limit(Auth::user()->user_name)}} </a></div>
				 <div class="home-nav-div"><a style="margin-right:0px;" class="home-nav-a" href="/customer/logout">Logout</a></div>
			</div>
		@endif
<!--
	<div>
		<div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
			<ul style="font-size:22px;" class="am-nav am-nav-pills am-topbar-nav">
			  <li id="nav-home" class="am-active nav-home"><a href="/">Home</a></li>
			  <li id="nav-blog" class="nav-blog"><a href="{{ action('BlogController@getArticleList') }}">Blog</a></li>
			  <li id="nav-aboutme"><a data-toggle="modal" data-am-modal="{target: '#my-popup'}" href="">About</a></li>
				@if(Auth::user())
					  <li id="nav-presonal" class="nav-personal"><a href="{{ action('CustomerController@personal') }}">Personal</a></li>
				@endif
			</ul>
		</div>
	</div>
	@if(Auth::user())
		<div class="home-nav-user-name">
			 <a style="margin-left:15px;font-size:22px;margin-top:20px;margin-right:20px;" href="/customer/logout">Logout</a>
			<a style="margin-top:20px;font-size:22px;"  href="javascript:void(none)"> {{str_limit(Auth::user()->user_name)}} </a>
		</div>
	@else
		<div class="home-nav-login">
			<span class="user">
					<div class="am-topbar-right">
					  <button  data-toggle="modal" data-target="#register" class="am-btn am-btn-primary am-topbar-btn am-btn-sm">Register</button>
					</div>
					<div class="am-topbar-right">
					  <button  data-toggle="modal" data-target="#login" class="am-btn am-btn-primary am-topbar-btn am-btn-sm">Login</button>
					</div>
			</span>
		</div>
	@endif
--!>
</div>
</header>
<div id="register" class="modal fade" tabindex="-1">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Register</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" method="post" onsubmit="return registerCheck()"  action="{{ action('CustomerController@studentRegister') }}">
<input type="hidden" name="_token"   value="{{csrf_token()}}"/>
                              <div class="form-group">
                                  <label for="">User Name</label>
                                  <input id="customerName" class="form-control" type="text" name="customer[name]"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Password</label>
                                  <input  id="customerPassword" class="form-control" type="password" name="customer[password]"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Confirm Password</label>
                                  <input id="customerConfirmPassword" class="form-control" type="password" name="customer[confirmpassword]" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Email</label>
                                  <input class="form-control" id="customerEmail"  type="email" placeholder="" name="customer[email]" >
                              </div>
                              <div class="text-right">
                                 <button id="sureRegisteBtnr" class="btn btn-primary" type="submit">Submit</button>
                                  <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                              </div>
                              <a href="" data-toggle="modal" data-dismiss="modal" data-target="#login">Login</a>
                      </form>
                  </div>
              </div>
          </div>
      </div>
      <!-- 登录窗口 -->
      <div id="login" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Login</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" action="#" method="post">
                          <input type="hidden" name="_token"         value="{{csrf_token()}}"/>
                          <div class="form-group">
                                  <label for="">Email</label>
                                  <input name="email" class="form-control" type="text" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Password</label>
                                  <input name="password" class="form-control" type="password" placeholder="">
                              </div>
                              <div style="color:red;" >
                                  <label id="err"></label>
                              </div>
                              <div class="text-right">
                                  <a class="btn btn-primary" id="login2" >Login</a>
                                 <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                             </div>
                             <a href="" data-toggle="modal" data-dismiss="modal" data-target="#register">Sign Up</a>
                     </form>
                 </div>
             </div>
         </div>
     </div>
<div class="am-popup" id="my-popup">
	@foreach($aboutMe as $key => $value)
  <div class="am-popup-inner">
    <div class="am-popup-hd">
	  <h4 class="am-popup-title">
		 {{$value['nodes'][0]['localAttributes']['title']['values']['1']['value']}}
		</h4>
      <span data-am-modal-close
            class="am-close">&times;</span>
    </div>
    <div class="am-popup-bd">
     {{$value['nodes'][0]['localAttributes']['description']['values']['1']['value']}}
    </div>
		<img  style="margin:auto;margin-top:30px;" src="{{$value['nodes'][0]['localAttributes']['Image Path']['values']['1']['value']}}" class="am-img-responsive" alt=""/>
  </div>
	@endforeach
</div>
@php
$index = 1;
@endphp
@foreach($teacherInfo as $key => $value)
    <div class="modal fade" id="teacherInfo-{{$index}}" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
     {{$value['nodes'][0]['localAttributes']['title']['values']['1']['value']}}
                    </h4>
                </div>
                <div class="modal-body">

				<div style="display:flex;">	
					<div style="width:50%;">
						 {{$value['nodes'][0]['localAttributes']['description']['values']['1']['value']}}
					</div>	
					<div style="width:50%;">
						<img src ="{{$value['nodes'][0]['localAttributes']['Image Path']['values']['1']['value']}}">
					</div>	
				</div>

                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@php
$index++;
@endphp
@endforeach

