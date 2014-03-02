<div class="goods-comment ">
	<div class="hd">
		发表我的询问/意见
	</div>
	{% if login_user %}
	<div class="goods-comment-box">
		<textarea class="ms-textarea" id="comment-now-textarea"> </textarea>
		<div class="goods-comment-btn-box clearfix">
			<div class="uk-alert uk-alert-warning tip">
				不能超过300字，太多了小拼记不住哦...
			</div>
			<button class="ck-btn" id="comment-now" data-productid="{{product.id}}" data-id="">
				立刻发布
			</button>
		</div>
	</div>
	{% else %}
	<div class="respond-box-nologin">
		<span>请先</span><a class="ms-check-login" href="#">登录</a><span>再询问/发表意见</span>
	</div>
	{% endif %}
</div>