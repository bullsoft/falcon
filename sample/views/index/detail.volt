{# index/detail.volt #}
{% extends "main.volt" %}

{% block style %}
{{ super() }}
<link href="/css/detail/detail.css" rel="stylesheet" media="screen" />
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
	  <p class="goods-name">{{product.name}}</p>
	  <p class="goods-pt"><span>{{product.price}}</span> / <span class="type">食品</span> </p>
	  <div class="oprate-box">
	    <div class="oprate clearfix">
	      <a class="star transition-all" href="#"><span class="uk-icon-star-empty"></span><i> 42</i></a>
	      <a class="prefer transition-all" href="#"><span class="uk-icon-heart-empty"></span><i> 35</i></a>
	    </div>
	  </div>
	  <div class="orgin">
	    <span>来自:<a href="{{product.from_url}}" target="_blank"}>{{product.from}}</a></span>
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
    <div class="merchant">
      <div class="hd">
	商家信息
	<span class="count">{{product.provider|length}}</span>
      </div>
      <ul class="merchant-list">
        {% for provider in product.provider %}
	<li class="clearfix">
	  <span><a href="" class="name">{{provider.user.nickname}}</a></span>
	  <span>
	    <i class="ev-star star-one">
	      <i class="star-light"></i>
	    </i>
	  </span>
	  <span class="price">￥{{provider.price}}</span>
	  <span class="buy">
	    <a href="{{ url('sample/cart/insertitem/') }}{{product.id}}/{{provider.user_id}}">加入购物车 >></a>
	  </span>
	</li>
        {% endfor %}
      </ul>
    </div>
  </div>
  <div class="right">
    <div class="hot-goods">
      <div class="hd">推荐商品</div>
      <ul class="hot-goods-list">
	<li class="clearfix transition-all">
	  <div class="img"><img src="/images/goods.jpg"></div>
	  <div class="info">
	    <p><a class="name">美丽心情美丽心情美丽心情</a></p>
	    <p><span class="price">￥34.20</span></p>
	  </div>
	</li>
	<li class="clearfix transition-all">
	  <div class="img"><img src="/images/goods.jpg"></div>
	  <div class="info">
	    <p><a class="name">美丽心情美丽心情美丽心情</a></p>
	    <p><span class="price">￥34.20</span></p>
	  </div>
	</li>
	<li class="clearfix transition-all">
	  <div class="img"><img src="/images/goods.jpg"></div>
	  <div class="info">
	    <p><a class="name">美丽心情美丽心情美丽心情</a></p>
	    <p><span class="price">￥34.20</span></p>
	  </div>
	</li>
	<li class="clearfix transition-all">
	  <div class="img"><img src="/images/goods.jpg"></div>
	  <div class="info">
	    <p><a class="name">美丽心情美丽心情美丽心情</a></p>
	    <p><span class="price">￥34.20</span></p>
	  </div>
	</li>
	<li class="clearfix transition-all">
	  <div class="img"><img src="/images/goods.jpg"></div>
	  <div class="info">
	    <p><a class="name">美丽心情美丽心情美丽心情</a></p>
	    <p><span class="price">￥34.20</span></p>
	  </div>
	</li>
      </ul>
    </div>
  </div>
</div>
{% endblock %}
