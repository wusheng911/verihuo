<div class="chm-fixed-navigator" style="border-bottom: #999 solid 1px;">
    <nav class="top-bar" style="height:1.875rem;margin:0.15625rem 0;">
      <div class="row" style="line-height:1.875rem;height:1.875rem;">
        <div class="small-12 columns text-center" style="line-height:1.875rem;height:1.875rem;">
          <a href="javascript:window.history.back();">
              <img style="position:absolute;top:0;left:0;height:100%;
                  width:auto;padding:0.35rem 0.875rem;" src="/assets/img/lt.png"/>
          </a>
          <span style="color:#333;font-size:1rem;">{{$title}}</span>    
          @if(isset($type) && $type == 'list')
              <?php
                  $options = [
                    'show_price|asc' => [
                        'sort' => 'show_price',
                        'order' => 'asc',
                        'value' => '价格 &#8593;'
                    ],
                    'show_price|desc' => [
                        'sort' => 'show_price',
                        'order' => 'desc',
                        'value' => '价格 &#8595;'
                    ],
                    'sale_quantity|asc' => [
                        'sort' => 'sale_quantity',
                        'order' => 'asc',
                        'value' => '销量 &#8593;'
                    ],
                    'sale_quantity|desc' => [
                        'sort' => 'sale_quantity',
                        'order' => 'desc',
                        'value' => '销量 &#8595;'
                    ]
                  ];
              ?>
              <select class="chm-select-products">
                @foreach($options as $key => $option)
                    @if($sort == $option['sort'] && $order == $option['order'])
                        <option value="{{$option['sort'] . '|' . $option['order']}}" selected="selected">{{$option['value']}}</option>
                    @else
                        <option value="{{$option['sort'] . '|' . $option['order']}}">{{$option['value']}}</option>
                    @endif
                @endforeach
              </select>  
          @endif
          @if(isset($type) && $type == 'cart')
              <i class="fa fa-trash-o fa-lg chm-cart-trash"></i>
          @endif   
          @if(isset($type) && ($type == 'address') && isset($show_trash) && ($show_trash == true))
              <i class="fa fa-trash-o fa-lg chm-address-trash"></i>
          @endif 
        </div>
      </div>
    </nav>
</div>

