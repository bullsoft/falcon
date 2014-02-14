{# index/index.volt #}
{% extends "main.volt" %}
{% block main_content %}
<div class="goods-box">
  <div class="goods-box-inner">

    {% for product in products %}
    <div class="single-goods transition-all">
      <div class="goods-show">
        <div class="goods-info">
          <div class="img">
	    <img src="{{product.image_url}}" style="margin-top: -20px"/>
          </div>
          <div class="shadow transition-all">
	    <a class="name transition-all" title="" href="{{ url('sample/index/detail/') }}{{product.id}}"> {{product.name}}</a>
	    <span class="price transition-all" title="">￥{{product.price}}</span>
          </div>
          <div class="txt-info transition-all">
	    <div class="recom-box clearfix">
	      <div class="recom clearfix">
	        <img src="{{product.user.photo}}">
	        <span class="nick">{{product.user.nickname}}</span>
	        <span>推荐</span>
	      </div>
	    </div>
	    <div class="info">
	      <div class="quote-l">
	        <q class="quote-r">
	          {{ mb_substr(product.description, 0, 80, "UTF-8") }}
	        </q>
	      </div>
	    </div>
	    <div class="skip">
	      <a class="skip-a" href="{{ url('sample/index/detail/') }}{{product.id}}"> <span>查看详情</span> </a>
	    </div>

          </div>
        </div>
      </div>

      <div class="merchant-box">
        {% for provider in product.provider %}
        <div>
          <div class="line clearfix">
	    <span class="name"><a href="">{{provider.user.nickname}}</a></span>
	    <!-- <span class="star-level star-one"> <i class="star-light star"> </i> <i class="star-grey star"> </i> </span> -->
	    <span class="price" title="{{provider.slogan}}">零售一口价￥{{provider.price}}</span>
	    <a class="go" href="{{ url('sample/index/detail/') }}{{product.id}}">GO >></a>
          </div>
        </div>
        {% endfor %}
      </div>
      <div class="oprate-box ">
        <div class="inner clearfix">
          <div class="oprate clearfix">
	    <a class="prefer transition-all" href="#"><span class="uk-icon-heart"></span><i> 42 </i></a>
	    <a class="star transition-all" href="#"><span class="uk-icon-star"></span><i> {{product.like}}</i></a>
          </div>
        </div>
      </div>
    </div>
    {% endfor %}

    
  </div>
</div>
{% endblock %}
