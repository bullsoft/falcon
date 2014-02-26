<div class="goods-comment-list" id="goods-comment-list">
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
						<a class="showRespond" data-id="{{comment.id}}" href="#">回应</a>
					</div>
				</div>
			</div>
		</div>
		<div class="respond-list" id="respond-list-{{comment.id}}">
			<div class="respond-box clearfix">
				<textarea class="ms-textarea"></textarea>
				<button class="ck-btn">
					发布
				</button>
			</div>
			{% for reply in comment.reply %}
			<div class="respond-el clearfix">
				<div class="user-img">
					<a href="#"><img src="{{ reply.user.photo }}"/></a>
				</div>
				<div class="info content">

					<a class="user-nick transition-all" href="#">{{ reply.user.nickname }}</a>
					<span class="respond-text">{{reply.content}}</span>
					<p class="time" data-time="{{ strtotime(reply.addtime) * 1000 }}">
						{{ reply.addtime }}
					</p>
				</div>
			</div>
			{% endfor %}
		</div>
	</div>
	{% endfor %}
</div>
