<div class="chm-fixed-navigator">
    <nav class="top-bar" style="height:1.875rem;margin:0.25rem 0;">
      <div class="row" style="line-height:1.875rem;height:1.875rem;">
        <div class="small-12 columns text-center" style="line-height:1.875rem;height:1.875rem;">
          <a href="javascript:window.history.back();">
              <img style="position:absolute;top:0;left:0;height:100%;
                  width:auto;padding:0.35rem 0.875rem;" src="/assets/img/lt.png"/>
          </a>
          <span style="color:#333;font-size:1rem;">{{$title}}</span>                      
        </div>
      </div>
    </nav>
    @if(isset($hide_divider) && $hide_divider)
    @else
        @include('mobile.elements.dividerImage')
    @endif  
</div>