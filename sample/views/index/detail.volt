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
	  <a href="#"><img src="/images/goods.jpg"></a>
	</div>
	<div class="right">
	  <p class="goods-name">现货 日本 京都老店豆政 代表之作 豆腐布丁~ 3种口味可选 单个~</p>
	  <p class="goods-pt"><span>￥34.20</span> / <span class="type">食品</span> </p>
	  <div class="oprate-box">
	    <div class="oprate clearfix">
	      <a class="star transition-all" href="#"><span class="uk-icon-star-empty"></span><i> 42</i></a>
	      <a class="prefer transition-all" href="#"><span class="uk-icon-heart-empty"></span><i> 35</i></a>
	    </div>
	  </div>
	  <div class="orgin">
	    <span>来自:<a href="#">京东商城</a></span>
	  </div>
	</div>
      </div>
      <div class="recom">
	<div class="user">
	  <a href="#"><img src="/images/img/u2815691-25.jpg"/></a>
	  <a class="transition-all nick" href="#"><span>我是测试用户</span></a>
	  <strong>推荐</strong>
	</div>
	<div class="info">
	  <div class="">
	    <span class="uk-icon-quote-left"></span>
	    给自己看鞋。。。却发现男款不错 这是病 得治，不错的谢，赞一个给自己看鞋。。。却发现男款不错 这是病 得治，不错的谢，赞一个给自己看鞋。。。却发现男款不错 这是病 得治，不错的谢，赞一个
	  </div>
	</div>
      </div>
    </div>
    <div class="merchant">
      <div class="hd">
	商家信息
	<span class="count">4</span>
      </div>
      <ul class="merchant-list">
	<li class="clearfix">
	  <span><a href="" class="name">套一套</a></span>
	  <span>
	    <i class="ev-star star-one">
	      <i class="star-light"></i>
	    </i>
	  </span>
	  <span class="price">￥34.20</span>
	  <span class="buy">
	    <a href="#">Go>></a>
	  </span>
	</li>
	<li class="clearfix">
	  <span><a href="" class="name">套一套</a></span>
	  <span>
	    <i class="ev-star star-one">
	      <i class="star-light"></i>
	    </i>
	  </span>
	  <span class="price">￥34.20</span>
	  <span class="buy">
	    <a href="#">Go>></a>
	  </span>
	</li>
	<li class="clearfix">
	  <span><a href="" class="name">套一套</a></span>
	  <span>
	    <i class="ev-star star-one">
	      <i class="star-light"></i>
	    </i>
	  </span>
	  <span class="price">￥34.20</span>
	  <span class="buy">
	    <a href="#">Go>></a>
	  </span>
	</li>
	<li class="clearfix">
	  <span><a href="" class="name">套一套</a></span>
	  <span>
	    <i class="ev-star star-one">
	      <i class="star-light"></i>
	    </i>
	  </span>
	  <span class="price">￥34.20</span>
	  <span class="buy">
	    <a href="#">Go>></a>
	  </span>
	</li>
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
