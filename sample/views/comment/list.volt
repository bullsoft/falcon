{% for reply in comments %}
<div class="respond-el clearfix">
	<div class="user-img">
		<a href="#"><img src="{{ reply.user.photo }}"/></a>
	</div>
	<div class="info content">
		<div>
			<a class="user-nick transition-all" href="#">{{ reply.user.nickname }}</a>
			{% if reply.reply_to_user_id > 0 %}
			<span class="reply-to-txt">回复 </span>
			<a href="#" class="reply-to-nick">{{reply.replyto.nickname}}</a>
			{% endif %}
			<span class="respond-text">{{reply.content}}</span>
		</div>
		<div class="clearer">
			<span class="time"> {{ reply.addtime }} </span>
			{% if login_user %}
			<a href="#" class="reply-to">回应</a>
			{% endif %}
		</div>
	</div>
	{% if login_user %}
	<div class="respond-to-user">
		<textarea class="normal-textarea"  data-ms-autoarea="{}"></textarea>
		<button class="ck-btn doRespond" data-productid="{{reply.product_id}}" data-id="{{reply.reply_to_comment_id}}" data-userid="{{reply.user_id}}">
			发布
		</button>
	</div>
	{% endif %}
</div>
{% endfor %}

