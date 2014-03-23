{% extends "main_user.volt" %}

{% block user_left %}
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
{% endblock %}
