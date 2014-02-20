{# main.volt #}

<!DOCTYPE html>
<html lang="zh_CN">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		{% block style %}
		<link href="/css/uikit.css" rel="stylesheet" media="screen" />
		<link href="/css/main.css" rel="stylesheet" media="screen" />
		<link href="/css/common/css3.css" rel="stylesheet" media="screen" />
		<link href="/css/common/common.css" rel="stylesheet" media="screen" />
		<link href="/css/common/nav.css" rel="stylesheet" media="screen" />
		<link href="/css/login/login.css" rel="stylesheet" media="screen" />
		<link href="/css/check-skins/all.css" rel="stylesheet" media="screen" />
		<link rel="stylesheet/less" href="/css/ms-css/dialog.less"/>
		{% endblock %}

		{% block javascript %}
		<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="/js/less-1.5.0.min.js"></script>
		<script type="text/javascript" src="/js/uikit.js"></script>
		{% endblock %}

		<title>血拼大爆炸！！！</title>
		<meta name="description" content="" />

	</head>
	<body>
		<div class="wapper">
			<div class="nav-stance"></div>
			<div class="nav-wapper">
				<div class="nav">
					{% include "layouts/nav.volt" %}
					{% include "layouts/user_session.volt" %}
				</div>
			</div>
			<div class="main-wapper">
				<div class="main clearfix">
					{% block main_content %}
					{% endblock %}
				</div>
			</div>
		</div>
		{% block javascript_bottom %}
		<script type="text/javascript" src="/js/plus/nunjucks.js"></script>
		<script type="text/javascript" src="/js/global/ms.js"></script>
		<script type="text/javascript" src="/js/global/config.js"></script>
		<script type="text/javascript" src="/js/ms-js/ms-storage.js"></script>
		<script type="text/javascript" src="/js/ms-js/ms-login.js"></script>
		<script type="text/javascript" src="/js/ms-js/ms-dialog.js"></script>
		<script type="text/javascript" src="/js/ms-js/ms-template.js"></script>
		<script type="text/javascript" src="/js/global/init.js"></script>
		<script type="text/javascript" src="/js/plus/icheck.js"></script>
		{% endblock %}
	</body>
</html>
