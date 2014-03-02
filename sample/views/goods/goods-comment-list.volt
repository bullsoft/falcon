{% for comment in comments %}
<div class="goods-comment-el">
	<div class="comment-theme clearfix">
		<div class="user-img">
			<a href="#"><img src="{{comment.user.photo}}"/></a>
		</div>
		<div class="info">
			<div class="user-info">
				<a class="user-nick transition-all" href="#">{{comment.user.nickname}}</a>
				<span class="user-type">发布人</span>
				<span class="time ">{{comment.addtime}}</span>
			</div>
			<div class="content">
				<i class="uk-icon-quote-left"></i>
				{{comment.content}}
				<i class="uk-icon-quote-right"></i>
			</div>
			<div class="oprate">
				<div>
					<span class="count" >({{comment.reply|length}})</span>
					<a class="showRespond" data-productid="{{product.id}}" data-id="{{comment.id}}" href="#">回应</a>
				</div>
			</div>
		</div>
	</div>
	<div class="respond-list" id="respond-list-box-{{comment.id}}" data-id="{{comment.id}}">
		{% if login_user %}
		<div class="respond-box clearfix">
			<textarea class="ms-textarea"  data-ms-autoarea="{}"></textarea>
			<button class="ck-btn doRespond" data-productid="{{product.id}}" data-id="{{comment.id}}">
				发布
			</button>
		</div>
		{% else %}
		<div class="respond-box-nologin"><span>请先</span><a class="ms-check-login" href="#">登录</a><span>再回复</span></div>
		{% endif %}
		<div>
			<div id="respond-list-{{comment.id}}"></div>
			<div class="ajax-loading">
				数据正在加载中....
			</div>
		</div>
	</div>
</div>
{% endfor %}
