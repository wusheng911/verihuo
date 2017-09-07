<div class="chm-fixed-navigator" data-active="{{$active_nav}}">
    <nav class="top-bar">
        <ul class="title-area inline-list" style="width:100%;">
            <li class="name text-center" style="width:14%;border-right: solid 1px #666; ">
              <a href="javascript:go_back();"><img src="/assets/img/lt.png"/></a>
            </li>
            <li id="nav-wo" class="name text-center" style="width:17%;border-right: solid 1px #666;">
                <h1><a href="/news/{{config('chaohun.art_parent_category_1')}}">我</a></h1>
            </li>
            <li id="nav-yao" class="name text-center" style="width:17%;border-right: solid 1px #666;">
                <h1><a href="/news/{{config('chaohun.art_parent_category_2')}}">要</a></h1>
            </li>
            <li id="nav-chao" class="name text-center" style="width:17%;border-right: solid 1px #666;">
                <h1><a href="/news/{{config('chaohun.art_parent_category_3')}}">潮</a></h1>
            </li>
            <li id="nav-hun" class="name text-center" style="width:17%;border-right: solid 1px #666;">
                <h1><a href="/news/{{config('chaohun.art_parent_category_4')}}">婚</a></h1>
            </li>
            <li id="nav-queen" class="name text-center" style="width:18%;">
                <h1><a href="/m/news/{{config('chaohun.art_parent_category_5')}}">女王</a></h1>
            </li>
        </ul>
    </nav>
    @include('mobile.elements.dividerImage')
</div>

