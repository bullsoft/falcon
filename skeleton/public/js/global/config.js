

global={};

global.config = {
	requestType : 'post',//请求方式
	
	user:{
		name: '',
		nick: ''
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
		commentTemplateId: 'js%goods%list.html'
	},
	
	order: {
		carChangeUrl: '/mock/car-change.json',
		deleteUrl:'/mock/car-change.json'
	}
};


