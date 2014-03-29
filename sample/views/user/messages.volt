{% extends "main_user.volt" %}

{% block user_left %}

<div class="message-center-wapper">
  <div class="message-list">
  {% for comment in comments %}
    <div class="message-list-each clearfix">
      
      <div class="img-box">
	<a href="#" hidefocus="true"><img src="{{ comment.user.photo }}"></a>
      </div>

      <div class="info">

	<div class="theme">
	{% if comment.reply_to_comment_id == 0 %}
	<span>评论了您的宝贝</span>
	<a class="goods-name" href="{{url('goods/detail-')}}{{comment.product_id}}.html">{{ comment.product.name}}</a>
	{% else %}
	<span>在 <a class="goods-name" href="{{url('goods/detail-')}}{{comment.product_id}}.html">{{ mb_substr(comment.product.name, 0, 20) }}</a> 宝贝中对您说：</span>
	{% endif %}
	</div>

	<div class="content">
	  <i class="uk-icon-quote-left"> </i>
	  {{comment.content}}
	  <i class="uk-icon-quote-right"> </i>
	</div>

	<div class=""><span class="time">{{comment.addtime}}</span></div>

      </div>

    </div>
    {% endfor %}

  </div>
</div>
 
{% endblock %}          
