<div class="user-center-right">
  <div class="user-info-wapper">
    <div class="user-info clearfix">
      <div class="img-box">
	<img src="{{login_user.photo}}">
      </div>
      <div class="user-info-detail">
	<p class="name">{{login_user.nickname}}</p>
	<p class="time-box"><span class="time">{{date("Y-m-d", strtotime(login_user.addtime))}}</span> 加入</p>
      </div>
    </div>
    <div class="user-info-emotion">
      <span class="split">
	<span class="txt">被评论</span>
	<span class="count-box">
	  <i class="count">11</i>
	</span>
      </span>
      <span class="split">
	<span class="txt">被收藏</span>
	<span class="count-box">
	  <i class="count">11</i>
	</span>
      </span>
      <span class="split">
	<span class="txt">被关注</span>
	<span class="count-box">
	  <i class="count">11</i>
	</span>
      </span>
    </div>
    <div class="function-ft">
      <a href="#">关注他</a>
    </div>
  </div>
</div>
