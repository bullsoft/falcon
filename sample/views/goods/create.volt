{# goods/create.volt #}
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
    </h1>
  </div>
  
  <div class="capture-link clearfix">
    <form action="/goods/fetch" method="POST">
    <div class="capture-link-input">
      <input type="text" name="url" />
    </div>
    <div class="capture-link-btn">
      <!-- <span class="ck-btn" type="submit">获取详情</span> -->
      <button class="ck-btn" type="submit">获取详情</button>
    </div>
    </form>
  </div>
  
  <div class="website-box">
    <div class="website">
      <div class="line">
	<div class="txt">国内网站</div>
	<div class="ck-line">
	</div>
      </div>
      <div class="links">
	<a class="transition-all" href="#">YOHO!有货</a>
	<a class="transition-all" href="#">无印良品中国</a>
	<a class="transition-all" href="#">丽芙家居</a>
	<a class="transition-all" href="#">优集品 </a>
	<a class="transition-all" href="#">国美在线</a>
	<a class="transition-all" href="#">国美在线</a>
	<a class="transition-all" href="#">国美在线</a>
	<a class="transition-all" href="#">国美在线</a>
	<a class="transition-all" href="#">薄荷糯米葱</a>
	<a class="transition-all" href="#">NOP</a>
	<a class="transition-all" href="#">好乐买 </a>
	<a class="transition-all" href="#">库巴 </a>
      </div>
    </div>
    <div class="website foreign">
      <div class="line">
	<div class="txt">国外网站</div>
	<div class="ck-line">
	</div>
      </div>
      <div class="links">
	<a class="transition-all" href="#">YOHO!有货</a>
	<a class="transition-all" href="#">无印良品中国</a>
	<a class="transition-all" href="#">丽芙家居</a>
	<a class="transition-all" href="#">优集品 </a>
	<a class="transition-all" href="#">国美在线</a>
	<a class="transition-all" href="#">国美在线</a>
	<a class="transition-all" href="#">国美在线</a>
	<a class="transition-all" href="#">国美在线</a>
	<a class="transition-all" href="#">薄荷糯米葱</a>
	<a class="transition-all" href="#">NOP</a>
	<a class="transition-all" href="#">好乐买 </a>
	<a class="transition-all" href="#">库巴 </a>
      </div>
    </div>
  </div>
  
</div>


{% endblock %}
