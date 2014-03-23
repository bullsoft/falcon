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
<script src="/js/plus/ms-validate.js"></script>
<script src="/js/order/order.js"></script>
{% endblock %}

{% block main_content %}

{% if msg %}
<div class="success-box clearfix">
  <div class="success-text">
    <h3><span class="uk-icon-ok"></span>{{msg}}</h3>
  </div>
  <div class="go-on">
    <button class="ms-button">
      继续购物
    </button>
    <button class="ms-button ms-button-normal settle-accounts">
      去结算<span class="uk-icon-chevron-right"></span>
    </button>
  </div>
</div>
{% else %}
<div class="goods-list-title">
  <h3>商品列表</h3>
  <span class="split-line"></span>
  <span class="split-line">编辑订单</span>
</div>
<div class="shopping-content">
  <div class="shopping-address">
    <div class="consignee-info" id="consignee-info-box">
      <div class="consignee-info-title clearfix">
	<h4>收货人信息</h4>
      </div>
      <div class="consignee-list-box">
	<div class="consignee-person-info" id="consignee-person-info">
	  <div class="line">
	    <span class="name">刘宇涵</span>
	    <span class="address">北京市 海淀区 四环到五环之间 回龙观龙腾苑五区12号楼1单元301 </span>
	  </div>
	  <div class="line">
	    <span class="phone">13580050089</span>
	    <span>邮编:</span>
	    <span class="postcode">100000</span>
	    <span><a class="modify" href="#">[修改]</a></span>
	  </div>
	</div>
	<div class="consignee-modify-box" id="consignee-modify-box">
	  <ul class="consignee-list-ul">
	    <li class="current">
	      <input name="consignee-info" class="consignee-check" checked="checked" type="radio"/>
	      <div class="line">
		<span class="name">刘宇涵</span>
		<span class="address">北京市 海淀区 四环到五环之间 回龙观龙腾苑五区12号楼1单元301 </span>
	      </div>
	      <div class="line">
		<span class="phone">13580050089</span>
		<span>邮编:</span>
		<span>100000</span>
		<a href="javascript:;" class="edit">[编辑]</a>
		<a href="javascript:;" class="setToDefault">[设置为默认地址]</a>
	      </div>
	      <div class="consignee-info-edit" id="test">
		<div class="edit-line">
		  <label>收货人姓名:</label>
		  <span>
		    <input type="text" />
		  </span>
		</div>
		<div class="edit-line">
		  <label>地区:</label>
		  <span>
		    <select class="city">
		      <option>北京</option>
		    </select>
		    <select class="town">
		      <option>海定区</option>
		    </select>
		    <select class="county">
		      <option>回龙观</option>
		    </select>
		    <select class="street">
		      <option>西大区</option>
		    </select> </span>
		</div>
		<div class="edit-line street-address">
		  <label>街道地址:</label>
		  <span>
		    <input type="text" class="street-address-input"/>
		  </span>
		</div>
		<div class="edit-line street-address">
		  <label>联系电话:</label>
		  <span>
		    <input  type="text" class="" data-describedby="phone-test" data-validate="phone"/>
		  </span>
		  <span id="phone-test"></span>
		</div>
		<div class="edit-line street-address">
		  <label>邮政编码:</label>
		  <span>
		    <input type="text" class=""/>
		  </span>
		</div>
	      </div>
	    </li>
	    <li>
	      <input name="consignee-info" class="consignee-check" type="radio"/>
	      <div class="line">
		<span class="name">刘宇涵</span><span class="address">北京市 海淀区 四环到五环之间 回龙观龙腾苑五区12号楼1单元301 </span>
	      </div>
	      <div class="line">
		<span class="phone">13580050089</span><span>邮编:</span><span>100000</span><a href="javascript:;" class="edit">[编辑]</a><a href="javascript:;" class="setToDefault">[设置为默认地址]</a>
	      </div>
	      <div class="consignee-info-edit">
		<div class="edit-line">
		  <label>收货人姓名:</label><span>
		    <input type="text" />
		  </span>
		</div>
		<div class="edit-line">
		  <label>地区:</label><span>
		    <select class="city">
		      <option>北京</option>
		    </select>
		    <select class="town">
		      <option>海定区</option>
		    </select>
		    <select class="county">
		      <option>回龙观</option>
		    </select>
		    <select class="street">
		      <option>西大区</option>
		    </select></span>
		</div>
		<div class="edit-line street-address">
		  <label>街道地址:</label><span>
		    <input type="text" class="street-address-input"/>
		  </span>
		</div>
		<div class="edit-line street-address">
		  <label>联系电话:</label><span>
		    <input type="text" class=""/>
		  </span>
		</div>
		<div class="edit-line street-address">
		  <label>邮政编码:</label><span>
		    <input type="text" class=""/>
		  </span>
		</div>
	      </div>
	    </li>
	    <li class="new-address-li">
	      <input name="consignee-info" class="consignee-check new-address" type="radio"/>
	      <div class="line">
		<span>新建联系人信息</span>
	      </div>
	      <div class="line new-address-line">
		<a href="javascript:;" class="edit">[编辑]</a><a href="javascript:;" class="setToDefault">[设置为默认地址]</a>
	      </div>
	      <div class="consignee-info-edit">
		<div class="edit-line">
		  <label>收货人姓名:</label><span>
		    <input type="text" />
		  </span>
		</div>
		<div class="edit-line">
		  <label>地区:</label><span>
		    <select class="city">
		      <option>北京</option>
		    </select>
		    <select class="town">
		      <option>海定区</option>
		    </select>
		    <select class="county">
		      <option>回龙观</option>
		    </select>
		    <select class="street">
		      <option>西大区</option>
		    </select></span>
		</div>
		<div class="edit-line street-address">
		  <label>街道地址:</label><span>
		    <input type="text" class="street-address-input"/>
		  </span>
		</div>
		<div class="edit-line street-address">
		  <label>联系电话:</label><span>
		    <input type="text" class=""/>
		  </span>
		</div>
		<div class="edit-line street-address">
		  <label>邮政编码:</label><span>
		    <input type="text" class=""/>
		  </span>
		</div>
	      </div>
	    </li>
	  </ul>
	  <div class="save-adress-box">
	    <button class="ms-button ms-button-normal">
	      保存收货人信息
	    </button>
	  </div>
	</div>
      </div>
    </div>
    <div class="goods-list">
      <div class="consignee-info-title clearfix">
	<h4>商品清单</h4>
      </div>
      <div class="goods-list-box">
	<div class="consignee-list-div">
	  <div class="head clearfix">
	    <div class="product"><span>商品</span></div>
	    <div class="merchant"><span>商家</span></div>
	    <div class="price"><span>单价(元)</span></div>
	    <div class="num"><span>数量</span></div>
	    <div class="subtotal"><span>小计</span></div>
	  </div>
	  <div class="merchant-goods-list">
	    {% for key,cart in carts %}
	    <div class="merchant-goods-el">
	      <?php
	      $provider = BullSoft\Sample\Models\User::findFirst(intval($key));
	      ?>
	      {% for item in cart.getItemsAsArray() %}
	      <div class="goods-info clearfix">
		<div class="product">
		  <div>
		    <div class="p-img">
		      <a href="#" target="_blank"><img src="{{item['custom']['image_url']}}"/></a>
		    </div>
		    <div class="p-detail">
		      <p class="name">
			<a href="#">{{ item['name'] }}</a>
		      </p>
		    </div>
		  </div>
		</div>
		<div class="merchant"><a href="#">{{provider.nickname}}</a></div>
		<div class="price"><div>{{item['price']}}</div></div>
		<div class="num"><div>{{item['qty']}}</div></div>
		<div class="subtotal"><div>{{ item['qty'] * item['price'] }}</div></div>
	      </div>
	      {% endfor %}
	      <div class="goods-shop-info clearfix">
		<div class="message-box">
		  <div class="inner">
		    <span>给商家留言:</span>
		    <textarea></textarea>
		  </div>
		</div>
		<div class="goods-shop-cost-box clearfix">
		  <div class="inner-box">
		    <div class="inner">
		      <div class="goods-shop-cost">
			{% set cart_total = cart.getTotals() %}
			<span class="goods-shop-cost-price">{{cart_total['shipments']}}</span>
			<span class="goods-shop-cost-label">运费：</span>
		      </div>
		      <div class="goods-shop-cost">
			<span class="goods-shop-cost-price">暂无优惠</span>
			<span class="goods-shop-cost-label">优惠：</span>
		      </div>
		    </div>
		  </div>
		  <div class="goods-subtotal-box">
		    <span class="goods-subtotal-price">{{cart_total['grand_total']}}</span>
		    <span class="goods-subtotal-label">商家合计(含运费):</span>
		  </div>
		</div>
	      </div>
	    </div>
	    {% endfor %}
	  </div>
	</div>
      </div>
      <div class="close-accounts">
	<div class="item clearfix">
	  <span>{{ array_sum(totals_goods) + array_sum(totals_shipments) }}</span>
	  <span class="label">商品总额:</span>
	</div>
	<div class="item clearfix">
	  <span>0.00</span>
	  <span class="label">优惠:</span>
	</div>
	<div class="item clearfix">
	  <span>{{ array_sum(totals_goods) + array_sum(totals_shipments) }}</span>
	  <span class="label">应付总额:</span>
	</div>
      </div>
    </div>
    <div class="submit-order-box clearfix">
      <div>
	<span class="price-box">应付总额：<span class="price">{{ array_sum(totals_goods) + array_sum(totals_shipments) }}</span></span>
	<a class="ck-btn" href="#"> 提交订单 </a>
      </div>
    </div>
  </div>
</div>
{% endif %}
{% endblock %}
