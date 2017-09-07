<aside class="left-off-canvas-menu chm-offcanvas">
    <ul class="off-canvas-list">
        <li>
            <label style="padding:0;height:5rem;">
                <div class="row">
                    <div class="small-12 columns text-center" 
                        style="padding-top:2.35em;padding-bottom:1.5em;padding-left:0.8rem;padding-right:0.8rem;">                        
                        @if(Auth::check())
                            <span  id="chm_account" style="float:left;">你好,  {{ Auth::user()->getUserName() }}</span>
                            <span id="chm_logout" style="float:right;text-decoration: underline;">退出</span>
                        @else 
                            <h6 style="font-weight:600;letter-spacing:0.1em;">
                            <span id="chm_register">注册</span>
                            /
                            <span id="chm_login">登录</span>
                            </h6>
                        @endif
                    </div>
                </div>
            </label>
        </li>
    </ul>
    <ul class="off-canvas-list">
        <li><a href="http://cs.ecqun.com/mobile/rand?id=1678907" style="padding-left:1.4rem;"><i class="fa fa-laptop"></i> 在线咨询</a></li>
        <li><a href="tel:4009005151" style="padding-left:1.4rem;"><i class="fa fa-phone"></i> 4009005151（点击拨打）</a></li>       
    </ul>
    <ul class="off-canvas-list" style="margin: 0 0.8rem;">
        <li><a href="/">首页</a></li>
        @foreach ($news_categories as $category)
            @if (!empty($category['title']))
                <li>
                    <a href="{{$category['mobile_link']}}" class="off-canvas-submenu-call">{{$category['title']}} <!--<span class="right chm-offcanvas-toggle-on"></span> --></a>
                </li>  
                @if (is_array($category['child']))
                    <ul class="off-canvas-submenu no-bullet">
                        @foreach($category['child'] as $child)
                            <li><a href="{{$child['mobile_link']}}">{{$child['title']}}</a></li>
                        @endforeach
                    </ul>
                @endif                 
            @endif
        @endforeach
        <li><a href="/view/article/654">关于我们</a></li>
    </ul>
</aside>

