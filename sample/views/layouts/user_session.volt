{% if login_user %}
<div class="nav-user clearfix">
	<div class="uk-parent cell" data-uk-dropdown="">
		<a class="info transition-all"> <img src="{{login_user.photo}}"/> <span class="arrow uk-icon-caret-right"> </span> </a>
		<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-flip">
			<ul class="uk-nav uk-nav-navbar">
				<li>
					<a href="#">我的主页</a>
				</li>
				<li>
					<a href="{{ url('cart/index') }}">我的购物车</a>
				</li>
				<li>
					<a href="#">购买记录</a>
				</li>
				<li>
					<a href="#">愿望清单</a>
				</li>
				<li>
					<a href="#">消息中心</a>
				</li>
				<li>
					<a href="#">资料修改</a>
				</li>
				<li>
					<a href="#">商家认证</a>
				</li>
				<li class="uk-nav-divider"></li>
				<li>
					<a href="{{url('user/logout')}}">退出</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="cell">
		<a class="publish uk-icon-plus transition-all"  href="{{ url('goods/create/') }}"> </a>
	</div>
	<div class="cell shopping-cart uk-parent" data-uk-dropdown="">
		<a class="uk-icon-shopping-cart transition-all shopping-cart-icon" href="#"></a>
		<span class="shopping-cart-count">{{global_cart_num}}</span>
		<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-flip shopping-cart-dropdown">
			<div class="nav-shopping-cart-box">
				<div class="tit">
					最新加入的商品
				</div>
				<div class="nav-shopping-cart-list">
					{% for cart in global_carts %}
					{% for item in cart.getItemsAsArray() %}
					<div class="shopping-cart-el clearfix">
						<div class="img">
							<a href="#"> <img src="{{item['custom']['image_url']}}"/> </a>
						</div>
						<div class="info">
							<p class="name">
								<a href="#">{{item['name']}}</a>
							</p>
						</div>
						<div class="price-div">
							<div class="price-txt">
								<span class="price uk-icon-jpy" href="#">{{item['price']}}</span>
								<span>×</span>
								<span class="count">{{item['qty']}}</span>
							</div>
							<a href="#" class="del-goods">删除</a>
						</div>
					</div>
					{% endfor %}
					{% endfor %}
				</div>
			</div>
			<div class="shopping-cart-total-box clearfix">
				<div class="shopping-cart-total">
					<div class="shopping-cart-total-tip">
						共<span class="total-count">{{ global_cart_num }}</span>件商品，共计<span class="total-price uk-icon-jpy">{{ array_sum(global_cart_totals) }}</span>
					</div>
					<a href="{{url('cart/order')}}">去购物车结算</a>
				</div>
			</div>
		</div>
	</div>
</div>
{% else %}
<!-- 未登录  -->
<div class="login-register">
	<a class="ck-btn transition-all ms-check-login" href="#">登录</a>
	<a class="ck-btn transition-all ms-check-register" href="{{url('user/register')}}">注册</a>
</div>
<div class="shopping-cart-nologin-box uk-parent" data-uk-dropdown="">
	<span class="shopping-cart-count">{{global_cart_num}}</span>
	<span class="shopping-cart-nologin ck-btn uk-icon-shopping-cart"> <span class="transition-all" href="">购物车</span> <span class="arrow-triangle transition-all uk-icon-caret-right"></span> </span>
	<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-flip shopping-cart-nologin-dropdown">
		<div class="nav-shopping-cart-box">
			<div class="tit">
				最新加入的商品
			</div>
			<div class="nav-shopping-cart-list">
				{% for cart in global_carts %}
				{% for item in cart.getItemsAsArray() %}
				<div class="shopping-cart-el clearfix">
					<div class="img">
						<a href="#"> <img src="{{item['custom']['image_url']}}"/> </a>
					</div>
					<div class="info">
						<p class="name">
							<a href="#">{{item['name']}}</a>
						</p>
					</div>
					<div class="price-div">
						<div class="price-txt">
							<span class="price uk-icon-jpy" href="#">{{item['price']}}</span>
							<span>×</span>
							<span class="count">{{item['qty']}}</span>
						</div>
						<a href="#" class="del-goods">删除</a>
					</div>
				</div>
				{% endfor %}
				{% endfor %}
			</div>
		</div>
		<div class="shopping-cart-total-box">
			<div class="shopping-cart-total clearfix">
				<div class="shopping-cart-total-tip">
					共<span class="total-count">{{global_cart_num}}</span>件商品，共计<span class="total-price uk-icon-jpy">{{ array_sum(global_cart_totals) }}</span>
				</div>
				<a href="{{url('cart/order')}}">去购物车结算</a>
			</div>
		</div>
	</div>
</div>
{% endif %}
