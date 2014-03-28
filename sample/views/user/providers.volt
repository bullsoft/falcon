{% extends "main_user.volt" %}

{% block user_left %}
<div class="goods-list-box">
  <div class="goods-list-inner">
    {% for provider in providers %}
    <div class="goods-list-each">
      <div class="goods-list-el">
	<a class="skip" href="{{url('goods/detail-')}}{{provider.product.id}}.html">
	  <div class="goods-img-box">
	    <div class="img-box">
	      <img src="{{provider.product.image_url}}" />
	    </div>
	    <div class="price">
	      <span class="">{{provider.product.price}}</span>
	    </div>
	  </div>
	  <div class="name-box">
	    <p class="name">{{provider.product.name}}</p>
	  </div>
	</a>
	<div class="user-emotion">
	  <a class="comment" href="#">评论
	    <span class="count">(<i>{{provider.product.comment.count()}}</i>)</span>
	  </a>
	  <span class="split-line">|</span>
	  <span class="collect">收藏
	    <span class="count">(<i>{{provider.product.wishlist.count()}}</i>)</span>
	  </span>
	</div>
      </div>
    </div>
    {% endfor %}
  </div>
</div>
{% endblock %}
