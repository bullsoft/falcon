<div class="login-contanier clearfix" >
	<div class="login-left">
		<div class="login-bingbang">
			<h1>登陆<a href="{{url('')}}">血拼大爆炸！</a></h1>
		</div>
		<div class="login-box">
			<div class="login-error-msg uk-alert-danger uk-alert">
				<p class="">
					密码错误
				</p>
			</div>
			<div class="line clearfix">
				<label class="label email">登陆邮箱</label>
				<div class="input">
					<input type="text" placeholder="请输入您的邮箱"/>
				</div>
			</div>
			<div class="line clearfix">
				<label class="label psw">密码</label>
				<div class="input">
					<input type="password" placeholder="请输入您的密码"/>
				</div>
			</div>
			<div class="is-forgot clearfix">
				<input class="is-auto-check" type="checkbox">
				<span class="is-auto-text">下次自动登陆</span>
				<a class="forgot-psw" href="#">忘记密码</a>
			</div>
			<div class="login-submit-box">
				<button class="login-submit-btn ck-btn">
					立刻登陆
				</button>
			</div>
		</div>
	</div>
	<div class="login-right">
		<div class="other-login-tip">
			通过社交网站登录;
		</div>
		<div class="other-login clearfix">
			{% for key,social_url in social_urls %}
			<div class="other-login-el">
				<a class="unite-login" href="{{ social_url }}"> <span class="icon {{key}}-icon"> </span> {{ social_sites[key] }} </a>
			</div>
			{% endfor %}
		</div>
		<div class="login-register-box">
			<div class="tip">
				加入血拼网，更多惊喜等着你...
			</div>
		</div>
	</div>
</div>
