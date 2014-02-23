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
				<li><a href="#">退出</a></li>
	  		</ul>
		</div>
	</div>
	<div class="cell">
		<a class="publish uk-icon-plus transition-all"  href="{{ url('sample/goods/create/') }}"> </a>
	</div>
	<div class="cell shopping-cart uk-parent" data-uk-dropdown="">
		<a class="uk-icon-shopping-cart transition-all shopping-cart-icon" href="#"></a>
		<span class="shopping-cart-count">0</span>
		<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-flip shopping-cart-dropdown">
			<div class="nav-shopping-cart-box">
				<div class="tit">最新加入的商品</div>
		 		<div class="nav-shopping-cart-list">
		 			<div class="shopping-cart-el clearfix">
		 				<div class="img">
		 					<a href="#">
		 						<img src="http://img10.360buyimg.com/n0/g9/M02/10/08/rBEHalDBo70IAAAAAAFlhdgFgpAAADLPwEHorsAAWWd322.jpg"/>
		 					</a>
		 				</div>
		 				<div class="info">
		 					<p class="name">
		 						<a href="#">罗蒙 2014春装新款专柜正品长袖衬衫男士衬衣商务休闲格子衬衫3C33442 65深红 41</a>
		 					</p>
		 				</div>
		 				<div class="price-div">
		 					<div class="price-txt">
		 						<span class="price uk-icon-jpy" href="#">169.00</span>
		 						<span>×</span>
		 						<span class="count">1</span>
		 					</div>
		 					<a href="#" class="del-goods">删除</a>
		 				</div>
		 			</div>
		 			<div class="shopping-cart-el clearfix">
		 				<div class="img">
		 					<a href="#">
		 						<img src="http://img10.360buyimg.com/n0/g9/M02/10/08/rBEHalDBo70IAAAAAAFlhdgFgpAAADLPwEHorsAAWWd322.jpg"/>
		 					</a>
		 				</div>
		 				<div class="info">
		 					<p class="name">
		 						<a href="#">罗蒙 2014春装新款专柜正品长袖衬衫男士衬衣商务休闲格子衬衫3C33442 65深红 41</a>
		 					</p>
		 				</div>
		 				<div class="price-div">
		 					<div class="price-txt">
		 						<span class="price uk-icon-jpy" href="#">169.00</span>
		 						<span>×</span>
		 						<span class="count">1</span>
		 					</div>
		 					<a href="#" class="del-goods">删除</a>
		 				</div>
		 			</div>
		 			<div class="shopping-cart-el clearfix">
		 				<div class="img">
		 					<a href="#">
		 						<img src="http://img10.360buyimg.com/n0/g9/M02/10/08/rBEHalDBo70IAAAAAAFlhdgFgpAAADLPwEHorsAAWWd322.jpg"/>
		 					</a>
		 				</div>
		 				<div class="info">
		 					<p class="name">
		 						<a href="#">罗蒙 2014春装新款专柜正品长袖衬衫男士衬衣商务休闲格子衬衫3C33442 65深红 41</a>
		 					</p>
		 				</div>
		 				<div class="price-div">
		 					<div class="price-txt">
		 						<span class="price uk-icon-jpy" href="#">169.00</span>
		 						<span>×</span>
		 						<span class="count">1</span>
		 					</div>
		 					<a href="#" class="del-goods">删除</a>
		 				</div>
		 			</div>
		 			<div class="shopping-cart-el clearfix">
		 				<div class="img">
		 					<a href="#">
		 						<img src="http://img10.360buyimg.com/n0/g9/M02/10/08/rBEHalDBo70IAAAAAAFlhdgFgpAAADLPwEHorsAAWWd322.jpg"/>
		 					</a>
		 				</div>
		 				<div class="info">
		 					<p class="name">
		 						<a href="#">罗蒙 2014春装新款专柜正品长袖衬衫男士衬衣商务休闲格子衬衫3C33442 65深红 41</a>
		 					</p>
		 				</div>
		 				<div class="price-div">
		 					<div class="price-txt">
		 						<span class="price uk-icon-jpy" href="#">169.00</span>
		 						<span>×</span>
		 						<span class="count">1</span>
		 					</div>
		 					<a href="#" class="del-goods">删除</a>
		 				</div>
		 			</div>
		 			<div class="shopping-cart-el clearfix">
		 				<div class="img">
		 					<a href="#">
		 						<img src="http://img10.360buyimg.com/n0/g9/M02/10/08/rBEHalDBo70IAAAAAAFlhdgFgpAAADLPwEHorsAAWWd322.jpg"/>
		 					</a>
		 				</div>
		 				<div class="info">
		 					<p class="name">
		 						<a href="#">罗蒙 2014春装新款专柜正品长袖衬衫男士衬衣商务休闲格子衬衫3C33442 65深红 41</a>
		 					</p>
		 				</div>
		 				<div class="price-div">
		 					<div class="price-txt">
		 						<span class="price uk-icon-jpy" href="#">169.00</span>
		 						<span>×</span>
		 						<span class="count">1</span>
		 					</div>
		 					<a href="#" class="del-goods">删除</a>
		 				</div>
		 			</div>
		 		</div>
			</div>
	 		<div class="shopping-cart-total-box clearfix">
		 		<div class="shopping-cart-total">
		 			<div class="shopping-cart-total-tip">共<span class="total-count">8</span>件商品，共计<span class="total-price uk-icon-jpy">1255.00</span></div>
		 			<a class="ck-btn" href="#">去购物车结算</a>
		 		</div>
	 		</div>
		</div>
	</div>
</div>
{% else %}
<!-- 未登录  -->
<div class="login-register">
	<a class="ck-btn transition-all ms-check-login">登录</a>
	<a class="ck-btn transition-all ms-check-register">注册</a>
</div>
{% endif %}
