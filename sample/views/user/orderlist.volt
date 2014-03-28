{% extends "main_user.volt" %}

{% block user_left %}
<div class="order-center-wapper">
  <div class="order-center-box">
    <div class="order-title clearfix">
      <div class="order-info each">订单信息</div>
      <div class="order-consignee-price each">收货人/订单金额</div>
      <div class="oreder-time each"><select><option>全部时间</option></select></div>
      <div class="oreder-status each"><select><option>全部状态</option></select></div>
      <div class="oprate each">操作</div>
    </div>
    <div >
      <table class="order-center-list">
        {% for order in orderlist %}
	<tbody class="order-center-list-each">
	  <tr class="order-th">
	    <td colspan="5">
	      <div class="clearfix">
		<div class="order-number-box">
		  <span class="">订单编号：</span>
		  <a class="" href="#">{{order.sn}}</a>
		</div>
		<div class="merchant">
		  商家：<a href="#">{{order.user.nickname}}</a>
		</div>
	      </div></td>
	  </tr>
	  <tr class="order-tr">
	    <td class="order-info">
              <?php $cart = json_decode($order->detail, true); ?>
              {% for item in cart['items'] %}
	      <a href="#" hidefocus="true"><img src="{{item['custom']['image_url']}}"/></a>
              {% endfor %}
	    </td>
	    <td class="order-consignee-price">
	      <div class="order-consignee-price-box">
		<p class="consignee">流浪的猫咪</p>
		<p class="price">{{order.price}}</p>
	      </div>
	    </td>
	    <td class="oreder-time">
	      <span>{{order.addtime}}</span>
	    </td>
	    <td class="oreder-status">
	      <span class="wait">等待收货</span>
	    </td>
	    <td class="oprate">
	      <a href="#" hidefocus="true" class="del">删除</a>
	    </td>
	  </tr>
	</tbody>
        {% endfor %}
      </table>
    </div>
  </div>
</div>


{% endblock %}
