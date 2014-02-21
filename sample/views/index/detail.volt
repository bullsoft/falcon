{# index/detail.volt #}
{% extends "main.volt" %}

{% block style %}
{{ super() }}
<link href="/css/detail/detail.css" rel="stylesheet" media="screen" />
<link href="/css/detail/comment.css" rel="stylesheet" media="screen" />
{% endblock %}

{% block main_content %}
<div class="goods-content">
	<div class="left">
		<div class="goods-detail">
			<div class="goods-detail-inner clearfix">
				<div class="img">
					<a href="#"><img src="{{product.image_url}}"></a>
				</div>
				<div class="right">
					<p class="goods-name">
						{{product.name}}
					</p>
					<p class="goods-pt">
						<span>{{product.price}}</span> / <span class="type">食品</span>
					</p>
					<div class="oprate-box">
						<div class="oprate clearfix">
							<a class="star goods-collect transition-all" href="#"><span class="uk-icon-star-empty"></span><i> 42</i></a>
							<a class="prefer  goods-love transition-all" href="#"><span class="uk-icon-heart-empty"></span><i> 35</i></a>
						</div>
					</div>
					<div class="">
						
						<div class="orgin">
							<span>来自:<a href="{{product.from_url}}" target="_blank"}>{{product.from}}</a></span>
						</div>
					</div>
				</div>
			</div>
			<div class="recom">
				<div class="user">
					<a href="#"><img src="{{product.user.photo}}"/></a>
					<a class="transition-all nick" href="#"><span>{{product.user.nickname}}</span></a>
					<strong>推荐</strong>
				</div>
				<div class="info">
					<div class="">
						<span class="uk-icon-quote-left"></span>
						{{product.description}}
					</div>
				</div>
			</div>
		</div>
		{% include "goods/goods-comment-list.volt" %}
		{% include "goods/goods-comment.volt" %}
		<div class="merchant">
			<div class="hd">
				商家信息
				<span class="count">{{product.provider|length}}</span>
			</div>
			<ul class="merchant-list">
				{% for provider in product.provider %}
				<li class="clearfix">
					<span><a href="" class="name">{{provider.user.nickname}}</a></span>
					<span> <i class="ev-star star-one"> <i class="star-light"></i> </i> </span>
					<span class="price">￥{{provider.price}}</span>
					<span class="buy"> <a href="{{ url('sample/cart/insertitem/') }}{{product.id}}/{{provider.user_id}}">加入购物车 >></a> </span>
				</li>
				{% endfor %}
			</ul>
		</div>
	</div>
	<div class="right">
		{% include "goods/goods-recommend.volt" %}
	</div>
</div>
{% endblock %}
