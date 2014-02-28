{% for reply in comments %}
<div class="respond-el clearfix">
	<div class="user-img">
		<a href="#"><img src="{{ reply.user.photo }}"/></a>
	</div>
	<div class="info content">
		<div>
			<a class="user-nick transition-all" href="#">{{ reply.user.nickname }}</a>
			{% if reply.reply_to_user_id > 0 %}
			回复 {{reply.replyto.nickname}}
			{% endif %}
			<span class="respond-text">{{reply.content}}</span>
		</div>
		<div class="clearer">
			<span class="time"> {{ reply.addtime }} </span>
			<a href="#" class="reply-to">回应</a>
		</div>
	</div>
	<div class="respond-to-user">
		<textarea class="normal-textarea"></textarea>
		<button class="ck-btn doRespond" data-productid="{{product.id}}" data-id="{{comment.id}}" data-userid="{{reply.reply_to_user_id}}">
			发布
		</button>
	</div>
</div>
{% endfor %}

