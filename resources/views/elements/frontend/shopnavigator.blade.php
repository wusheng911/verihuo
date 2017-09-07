@if(!empty($shopCategoryNav))
    <div class="shop-nav-container">
        <div class="shop-sub-nav-container">
            <div class='nav-sub-item catogory-tag'>
                <a href ='/shop'>首页</a>
            </div>
                @foreach($shopCategoryNav as $key => $value)
                    <div class='nav-sub-item catogory-tag'>
                        <a target="_blank" href ={{'/shop/category/'.$value->id}}>{{$value->name}}</a>
                    </div>
                @endforeach
        </div>
    </div>
@endif