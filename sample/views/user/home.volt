{% extends "main.volt" %}

{% block style %}
{{ super() }}
<link href="/css/user/user-center.css" rel="stylesheet" media="screen" />
{% endblock %}

{% block javascript_bottom %}
{{ super() }}
<script src="/js/index/index.js"></script>
{% endblock %}

{% block main_content %}
<div class="wapper">
  <div class="user-center-wapper">
    <div class="user-center-container clearfix">
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
      <div class="user-center-left">
	<div class="user-center-nav">
	  <ul class="clearfix">
	    <li class="line"><a class="transition-all" href="#"><span>收藏的宝贝</span></a></li>
	    <li class="line current"><a class="transition-all" href="#"><span>发布的宝贝</span></a></li>
	    <li class="line"><a class="transition-all" href="#"><span>订单中心</span></a></li>
	  </ul>
	</div>
	<div class="goods-list-box">
	  <div class="goods-list-inner">
	    <div class="publish-goods goods-list-each">
	      <a href="{{url('goods/create')}}">
		<span class="pulish-icon uk-icon-plus"> </span>
		<span class="txt">推荐新宝贝</span>
	      </a>
	    </div>
            {% for product in products %}
	    <div class="goods-list-each">
	      <div class="goods-list-el">
		<a class="skip" href="{{url('goods/detail-')}}{{product.id}}.html">
		  <div class="goods-img-box">
		    <div class="img-box">
		      <img src="{{product.image_url}}" />
		    </div>
		    <div class="price">
		      <span class="">{{product.price}}</span>
		    </div>
		  </div>
		  <div class="name-box">
		    <p class="name">{{product.name}}</p>
		  </div>
		</a>
		<div class="user-emotion">
		  <a class="comment" href="#">评论
		    <span class="count">(<i>{{product.comment.count()}}</i>)</span>
		  </a>
		  <span class="split-line">|</span>
		  <span class="collect">收藏
		    <span class="count">(<i>{{product.wishlist.count()}}</i>)</span>
		  </span>
		</div>
	      </div>
	    </div>
            {% endfor %}
	  </div>
	</div>
      </div>
    </div>
  </div>
</div>

{% endblock %}
