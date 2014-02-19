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

<div class="shopping-content shopping-cart">
	<div class="success-box clearfix">
		<div class="success-text">
			<h3><span class="uk-icon-ok"> </span>成功加入购物车！</h3>
		</div>
		<div class="go-on">
			<button class="ms-button">
				继续购物
			</button>
			<button class="ms-button ms-button-normal settle-accounts">
				去结算<span class="uk-icon-chevron-right"> </span>
			</button>
		</div>
	</div>
	<div class="">
		<div class="goods-list" id="goods-list">
			<div class="title clearfix">
				<h3>商品列表</h3>
				<span class="title-line"> </span>
			</div>
			<div class="goods-list-box">
				<div class="goods-list-div">
					<div class="head clearfix">
						<div class="product"><div>商品</div></div>
						<div class="merchant"><div>商家</div></div>
						<div class="price"><div>单价(元)</div></div>
						<div class="num"><div>数量</div></div>
						<div class="oprate-del"><div>操作</div>
						</div>
					</div>
					{% for item in cart.getItemsAsArray() %}
					<?php
					$product=BullSoft\Sample\Models\Product::findFirst("id=".$item['id']);
					?>
					<div class="goods-info clearfix" id='goods-list-{{item["id"]}}'>
						<div id='goods-sequence-{{item["id"]}}'>
							<input  type="hidden" name="id" value='{{item["id"]}}'/>
							<input  type="hidden" name="count" value='{{item["count"]}}'/>
							<input  type="hidden" name="price" value='{{item["price"]}}'/>
						</div>
						<div class="product">
							<div class="p-img">
								<a href="#" target="_blank"> <img src="{{product.image_url}}"/> </a>
							</div>
							<div class="p-detail">
								<p class="name">
									<a href="#"> {{item["name"]}} </a>
								</p>
							</div>
						</div>
						<div class="merchant">
							<div>
								<a href="#">{{product.user.nickname}}</a>
							</div>
						</div>
						<div class="price">
							<div class="price-text">
								{{item['price']}}
							</div>
						</div>
						<div class="num">
							<div>
								<a class="uk-icon-minus subtract" data-id="{{item["id"]}}" href="#"> </a>
								<input class="num-input num-text" data-id="{{item["id"]}}" type="text" value="1"/>
								<a class="uk-icon-plus add" data-id="{{item["id"]}}" href="#"> </a>
							</div>
						</div>
						<div class="oprate-del">
							<div>
								<a href="#" data-id="{{item["id"]}}" class="del-goods">[删除]</a>
							</div>
						</div>
					</div>
					{% endfor %}
					{% set total = cart.getTotals() %}
					<div class="close-accounts">
						<div class="item clearfix">
							<span>{{total['items']}}</span>
							<span class="label">商品总额:</span>
						</div>
						<div class="item clearfix">
							<span>0.00</span>
							<span class="label">运费:</span>
						</div>
					</div>
					<div class="submit-order-box">
						<div>
							<span class="price-box">总计：<span class="price" id="total-price-text">{{total['items']}}</span></span>
							<button class="ms-button ms-button-normal settle-accounts">
								去结算
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{% endblock %}
