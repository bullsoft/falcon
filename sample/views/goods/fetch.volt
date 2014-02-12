{# goods/fetch #}
{% extends "main.volt" %}

{% block style %}
{{ super() }}
<link href="/css/publish/publish.css" rel="stylesheet" media="screen" />
{% endblock %}

{% block main_content %}

<div class="content">
  <div class="recom">
    <h1>
      <span class="txt">推荐商品</span>
      <span class="line"></span>
      <a class="back-step uk-icon-reply-all" href=""></a>
    </h1>
  </div>
  <div class="goods-info clearfix">
    <div class="img-box">
      <img src="{{ goods['m_imgs'][0] }}" />
      <div class="change-img">
	<a class="normal-btn transition-all" href="">更换主图</a>
      </div>
    </div>
    <div class="info">
      <p class="txt">商品名称</p>
      <p class="name"><input class="normal-input" type="text" value="{{ goods['name'] }}" /></p>
      <p class="price"><strong>{{ goods['price'] }}</strong></p>
    </div>
  </div>
  <div class="goods-sprite">
    <div class="line">
      <div class="ck-line"></div>
    </div>
    <div class="resaon-box">
      <div class="tit">
	推荐理由：<span>(不要超过300字哦)</span>
      </div>
      <textarea class="normal-textarea"></textarea>
    </div>
  </div>
  <div class="goods-sprite goods-images">
    <div class="line">
      <div class="ck-line"></div>
    </div>
    <div class="goods-images-box">
      <div class="tit">
	商品图片：<span>(最多10张图片)</span>
	<div class="change-img">
	  <a class="normal-btn transition-all" href="#">上传或修改图片</a>
	</div>
      </div>
      <div class="image-list-box">
	<ul class="image-list">
          {% for item in goods['m_imgs'] %}
	  <li><img src="{{ item }}"/></li>
          {% endfor %}
	</ul>
      </div>
      <div class="publish-btn-box clearfix">
	<div class="line"></div>
	<a class="normal-btn cancel transition-all">取消推荐</a>
	<a class="normal-btn publish transition-all">立刻推荐</a> 
      </div>
    </div>
  </div>
</div>

{% endblock %}
