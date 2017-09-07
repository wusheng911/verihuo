<div id="article_navigator" class="chm-fixed-bottom chm-navigator-article">
    <nav class="top-bar bottom-bar" style="line-height:1.6rem;">
        <div class="row">
            <div class="small-7 columns text-center" style="padding:0.2rem 0.3rem 0.2rem 0.625rem;">
                <a id="artbar_add_comment" style="display:block;" href="javascript:;">
                    <div clas="row">
                      <div class="small-12 columns" style="background-color:white;border-radius:5px;padding:0 0.625rem;">
                        <span style="font-size:0.875rem;color:#666;text-align:left;display:block;float:left;">点击输入评论</span>
                        <span style="font-size:0.875rem;color:#666;text-align:right;display:block;float:right;">&#8629;</span>
                      </div>
                    </div>
                </a>
            </div>
            <div class="small-5 columns text-center">
                <ul class="small-block-grid-2" style="width:100%;">
                  <li class="name text-center">
                      <a href="/view/artcomments/{{$article['id']}}">
                          <span style="color:white;height:100%;font-size:0.875rem;">
                              <img src='/assets/img/comments.png' style="height:100%;width:auto;display:inline-block;margin-bottom:0.15rem;"/>
                              {{(intval($total_comments) > 0)?$total_comments:''}}
                          </span>
                          <!--<i class="fa fa-comment-o fa-2x" style="color:white;"></i>-->
                      </a>
                  </li>
                  <!-- 暂时去掉 -->
              <!--<li class="name text-center">
                      <a href="#" data-reveal-id="modalQRCode">
                          <img src='/assets/img/qrcode.png' style="height:100%;width:auto;"/>
                      </a>
                  </li>-->
                  <li class="name text-center">
                      <a href="#" data-reveal-id="modalShare">
                          <img src='/assets/img/share.png' style="height:100%;width:auto;"/>
                      </a>
                  </li>
                  <!-- 
                  <li class="name text-center">
                      <a href="javascript:;" onclick="addFavorite(this);">
                          <img src='/assets/img/favorite.png' style="height:100%;width:auto;"/>
                      </a>
                  </li>
                  -->
                </ul>              
            </div>
        </div>
    </nav>
</div>