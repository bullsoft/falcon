{% for reply in comments %}
<div class="respond-el clearfix">
	<div class="user-img">
		<a href="#"><img src="{{ reply.user.photo }}"/></a>
	</div>
	<div class="info content">

		<a class="user-nick transition-all" href="#">{{ reply.user.nickname }}</a>
		{% if reply.reply_to_user_id > 0 %}
		回复 {{reply.replyto.nickname}}
		{% endif %}
		<span class="respond-text">{{reply.content}}</span>
		<p class="time">
			{{ reply.addtime }}
		</p>
	</div>
</div>
{% endfor %}

