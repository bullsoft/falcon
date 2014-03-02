

global={};

global.config = {
	requestType : 'post',//请求方式
	
	indexUrl : '{{url("")}}',
	
	user:{
	    {% if login_user %}
		name: '{{login_user.username}}',
		nick: '{{login_user.nickname}}'
		{% endif %}
	},
	
	login:{
		// templateId:'js%ms-js%login',
	    templateId:'user%loginform',
		checkUrl : '/mock/check-login.json',
		url: '/mock/login.json'
	},
	
	goods: {
		//publisNowUrl : '/mock/goods-publish-now.josn',
		publisNowUrl : '/goods/insert',
		loveUrl : '/goods/like',
		wishCreateUrl : '/wishlist/create',
		wishRemoveUrl : '/wishlist/remove',
		respondTemplateId: 'js%goods%respond',
		commentTemplateId: 'js%goods%comment',
	},
	
	comment: {
	    
	    showRespondUrl:'/comment/list',
	    doRespondUrl:'/comment/create'
	    
	},
	
	order: {
		carChangeUrl: '/cart/insertitem',
		deleteUrl:'/cart/removeitem',
		clearUrl:'/cart/removeall'
	}
};


