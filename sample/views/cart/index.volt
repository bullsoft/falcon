{# goods/create.volt #}
{% extends "main.volt" %}

{% block style %}
{{ super() }}
<link href="/css/order/order.css" rel="stylesheet" media="screen" />
<style type="text/css">
</style>
{% endblock %}

{% block javascript_bottom %}
{{ super() }}
<script src="/js/order/shopping-cart.js"></script>
{% endblock %}

{% block main_content %}

<div class="shopping-cart" id="shopping-cart-box">
  {% if msg %}
  <div class="shopping-null">
    <div class="null-txt">
      购物车为空，请挑选商品。
    </div>
    <div class="go-on">
      <a class="ck-btn" href="{{url('')}}">
	继续购物
      </a>
    </div>
  </div>
  {% else %}
  <div class="">

    <div class="goods-list">
      <div class="goods-list-title">
	<h3>商品列表</h3>
	<span class="split-line"></span>
      </div>
      <div id="goods-list-contanier" class="goods-list-contanier">
	<div class="goods-list-box">
	  <div class="goods-list-div" id="goods-list">
	    <div class="head clearfix">
	      <div class="product line">
		<span>商品</span>
	      </div>
	      <div class="merchant line">
		<span>商家</span>
	      </div>
	      <div class="price line">
		<span>单价(元)</span>
	      </div>
	      <div class="num line">
		<span>数量</span>
	      </div>
	      <div class="oprate-del line">
		<span>数量</span>
	      </div>
	    </div>
	    <div id="shopping-list">
	      {% for cart in carts %}
	      {% for item in cart.getItemsAsArray() %}
	      <?php
	      $provider = BullSoft\Sample\Models\Provider::findFirst("user_id=" . $item['provider'] . " AND product_id=" . $item['id']);
	      ?>
	      <div class="goods-info clearfix" id='goods-list-{{item["id"]}}-{{provider.user_id}}'>
		<div class="goods-sequence-box">
		  <div id='goods-sequence-{{item["id"]}}-{{provider.user_id}}'>
		    <input  type="hidden" name="id" value='{{item["id"]}}'/>
		    <input  type="hidden" name="count" value='{{item["qty"]}}'/>
		    <input  type="hidden" name="price" value='{{item["price"]}}'/>
		    <input  type="hidden" name="provider" value="{{provider.user_id}}"/>
		  </div>
		</div>
		<div class="product">
		  <div class="p-img">
		    <a href="#" target="_blank"> <img src="{{provider.product.image_url}}"/> </a>
		  </div>
		  <div class="p-detail">
		    <p class="name">
		      <a target="_blank"  href="{{url('goods/detail-')}}{{item['id']}}.html"> {{ item['name'] }} </a>
		    </p>
		  </div>
		</div>
		<div class="merchant">
		  <div>
		    <a href="#">{{ provider.user.nickname }}</a>
		  </div>
		</div>
		<div class="price">
		  <div class="price-text">
		    {{ item['price'] }}
		  </div>
		</div>
		<div class="num">
		  <div data-id="{{item['id']}}" data-providerid="{{provider.user_id}}">
		    <a class="uk-icon-minus subtract" href="#"> </a>
		    <input class="num-input num-text" data-id="{{item['id']}}" type="text" value="{{item['qty']}}"/>
		    <a class="uk-icon-plus add" data-id="{{item["id"]}}" data-providerid="{{provider.user_id}}"  href="#"> </a>
		  </div>
		</div>
		<div class="oprate-del">
		  <div>
		    <a href="#" data-id="{{item["id"]}}" data-providerid="{{provider.user_id}}" class="del-goods">[删除]</a>
		  </div>
		</div>
	      </div>
	      {% endfor %}
	      {% endfor %}
	    </div>
	    <form id="shopping-cart-form"></form>
	    <div class="close-accounts">
	      <div class="item clearfix">
		<span id="total-price-txt">{{ array_sum(totals_goods) }}</span>
		<span class="label">商品总额:</span>
	      </div>
	      <div class="item clearfix">
		<span>{{ array_sum(totals_shipments) }}</span>
		<span class="label">运费:</span>
	      </div>
	    </div>
	    <div class="submit-order-box">
	      <div>
		<span class="price-box">总计：<span class="price" id="total-price-text">{{ array_sum(totals_goods) + array_sum(totals_shipments) }}</span></span>
		<a class="ck-btn settle-accounts" href="{{url('order')}}"> 去结算 </a>
	      </div>
	    </div>
	  </div>
	</div>
      </div>
    </div>
  </div>
  {% endif %}
  {% endblock %}
