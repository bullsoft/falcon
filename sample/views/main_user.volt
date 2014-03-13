{# main_user.volt #}
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
    Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>login</title>
    <meta name="description" content="">
    <meta name="author" content="xiejunfeng">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">

    {% block style %}
    <link href="/css/uikit.css" rel="stylesheet" media="screen" />
    <link href="/css/main.css" rel="stylesheet" media="screen" />
    <link href="/css/common/css3.css" rel="stylesheet" media="screen" />
    <link href="/css/common/common.css" rel="stylesheet" media="screen" />
    <link href="/css/common/nav.css" rel="stylesheet" media="screen" />
    <link href="/css/login/login.css" rel="stylesheet" media="screen" />
    <link href="/css/check-skins/all.css" rel="stylesheet" media="screen" />
    <link rel="stylesheet/less" href="/css/ms-css/dialog.less"/>
    <link href="/css/user/user-center.css" rel="stylesheet" media="screen" />
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
      <div class="user-center-wapper">
	<div class="user-center-container clearfix">
          {% block user_right %}
          {% include "layouts/user_center_right.volt" %}
          {% endblock %}

	  <div class="user-center-left">
	    <div class="user-center-nav">
	      <ul class="clearfix">
		<li class="line{% if action == 'wishlist'%} current{% endif %}">
		  <a class="transition-all" href="{{url('user/wishlist')}}"><span>愿望清单</span></a>
		</li>
		<li class="line{% if action == 'home'%} current{% endif %}">
		  <a class="transition-all" href="{{url('user/home')}}"><span>我的宝贝</span></a>
		</li>
		<li class="line{% if action == 'providers'%} current{% endif %}">
		  <a class="transition-all" href="{{url('user/providers')}}"><span>售卖的宝贝</span></a>
		</li>
		<li class="line{% if action == 'orderlist'%} current{% endif %}">
		  <a class="transition-all" href="{{url('user/orderlist')}}"><span>订单中心</span></a>
		</li>
		<li class="line{% if action == 'messages'%} current{% endif %}">
		  <a class="transition-all" href="{{url('user/messages')}}"><span>消息中心</span></a>
		</li>
	      </ul>
	    </div>

            {% block user_left %}
            
            {% endblock %}
            
          </div>
        </div>
      </div>
    </div>
    
  </body>
</html>
