{% if login_user %}
<div class="nav-user clearfix">
	<div class="uk-parent cell" data-uk-dropdown="">
		<a class="info transition-all">
  			<img src="{{login_user.photo}}"/>
  			<span class="arrow uk-icon-caret-right"> </span>
		</a>
		<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-flip">
	 		<ul class="uk-nav uk-nav-navbar">
				<li><a href="#">我的主页</a></li>
				<li><a href="{{ url('sample/cart/index') }}">我的购物车</a></li>        
				<li><a href="#">购买记录</a></li>
				<li><a href="#">愿望清单</a></li>
				<li><a href="#">消息中心</a></li>        
				<li><a href="#">资料修改</a></li>
				<li><a href="#">商家认证</a></li>
				<li class="uk-nav-divider"> </li>
				<li><a href="{{url('sample/user/logout')}}">退出</a></li>
	  		</ul>
		</div>
	</div>
	<div class="cell">
		<a class="publish uk-icon-plus transition-all"  href="{{ url('sample/goods/create/') }}"> </a>
	</div>
</div>
{% else %}
<!-- 未登录  -->
<div class="login-register">
	<a class="ck-btn transition-all ms-check-login">登录</a>
	<a class="ck-btn transition-all ms-check-register">注册</a>
</div>
{% endif %}
