{# index/index.volt #}
{% extends "main.volt" %}

{% block style %}
{{ super() }}
<link href="/css/index/index.css" rel="stylesheet" media="screen" />
{% endblock %}

{% block javascript_bottom %}
{{ super() }}
<script src="/js/index/index.js"></script>
{% endblock %}

{% block main_content %}
{% include "layouts/navigation.volt" %}
<div class="goods-box">
	<div class="goods-box-inner">

		{% for product in products %}
		<div class="single-goods transition-all">
			<div class="goods-show">
				<div class="goods-info">
					<div class="img">
						<img src="{{product.image_url}}"/>
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
								<q class="quote-r"> {{ mb_substr(product.description, 0, 80, "UTF-8") }} </q>
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
				{% if count(product.provider) == 0 %}
				<div class="no-merchant">
					<div class="no-merchant">期待您提供宝贝，方便更多拼友...</div>
					<div><a class="merchant-provider ck-btn" href="#">我有宝贝</a></div>
				</div>
				{% endif %}
			</div>
			<div class="oprate-box ">
				<div class="inner clearfix">
					<div class="oprate clearfix">
						<a class="prefer goods-love transition-all" href="#"><span class="uk-icon-heart-empty"></span><i> 42 </i></a>
						<a class="star goods-collect transition-all" href="#"><span class="uk-icon-star-empty"></span><i> {{product.like}}</i></a>
					</div>
				</div>
			</div>
		</div>
		{% endfor %}

	</div>
</div>

{% endblock %}
