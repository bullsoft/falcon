(function($, win, doc) {"use strict";

	var MS, Login, InitLogin;

	if ($ === undefined) {throw '$未定义';}

	if ($.MSspirit === undefined) {throw '$.MSspirit未定义';}

	MS = $.MSspirit;
	
	Login = function(element, options){
		
		var _options, template;
		_options = options = $.isPlainObject(element) ? element : options;
		this.options = $.extend(true, {}, _options, this.options);
		
		if(!this.options.checkUrl || !this.options.templateId){
			return this;
		}
		
		if(this.options.templateId) this.template = $.MSspirit.template({id:this.options.templateId}).getHtml();
		
		$('body').data('login',this);
	};
	
	InitLogin = function(element, options){
		return new Login(element, options);
	};
	
	$.extend(Login.prototype,{
		options:{
			templateId:'',
			checkUrl:'',
			user: '',
			loginLoseTime:10 * 60 * 1000,
		},
		paramsInit : function(options){
			Login.prototype.options.checkUrl = options.checkUrl;
			Login.prototype.options.templateId = options.templateId;
			Login.prototype.options.user = options.user;
			
			$('body').data('login',null);
		},
		check: function(){
			var that = this, now = new Date().getTime(), isCheck,
				isCheck = (!that.options.user) || (!that.lastCheck) || ((now - that.lastCheck) > that.options.loginLoseTime);
			
			if(isCheck){
				$.ajax({
					url : that.options.checkUrl,
					data:{},
					dataType:'json',
					success: function(data){
						
						var now = new Date().getTime();
						$.MSspirit.storage({nameSpace: 'bingbang'}).ini('lastLoginTime', now);
						
						that.lastCheck = now;
						
						if(data.status == 200){
							that.showDialog();
						}
					},
					error: function(){
						alert('网络错误');
					}
				});
			}else{

				that.showDialog();
			}
		},
		showDialog:function(){
			
			var that = this;
			
			$('body').ms('dialog',{
				html : that.template,
				decorate :false,
			});
			
			$('body').data('dialog').modal.modifyClosePosition({top:10,right:20});
			
			that.bindEvents();
		},
		bindEvents: function(){
			var that = this, $dialog;
			$dialog = that.$dialog = $('body').data('dialog') && $('body').data('dialog').modal.$dialog;
			
			$dialog.find('.is-auto-check').iCheck({
				checkboxClass : 'icheckbox_square-yellow',
				radioClass : 'iradio_square-yellow',
				increaseArea : '20%' // optional
			});
			
			$dialog.find('.unite-login').click(function(){
				
				var url = $(this).attr('href');
				if(!win.openWin)win.openWin = {};
				if(win.openWin['unite-login'])win.openWin['unite-login'].close();
				win.openWin['unite-login'] = window.open (url,'newwindow','height=400,width=600,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no'); 

				return false;
			});
						$dialog.find('.login-submit').on('click',function(){
				that.doLogin();
			});
			
			
		},
		doLogin: function(){
			
			var that = this, params = {};
			
			params.username = that.$dialog.find('.input-email').val();
			params.psw = that.$dialog.find('.input-psw').val();
			
			$.ajax({
				url : global.config.login.url,
				data: params,
				dataType: 'json',
				success : function(data){
					if(data.status == 200){
						alert('登陆成功');
						window.location.reload();
					}
				},
				error: function(){
					alert("网络错误");
				},
			});
		},
		lastCheck: $.MSspirit.storage({nameSpace: 'bingbang'}).ini('lastLoginTime')
	});
	
	$(doc).on('click', '.ms-check-login',function(){
		  var $this = $('body'), options = {},
        	data = MS.Utils.options($this.attr('data-ms-login'));

        if($.isPlainObject(data)){
        	options = data;
        }
        
		if (!$this.data("login")) {
	        new InitLogin($this, options).check();
       	}else{
       		$this.data("login").check();
       	};	
	});
	
	
	 MS["login"] = InitLogin;
	 
	 
})(jQuery, window, document);
