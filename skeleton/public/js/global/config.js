

global={};

global.config = {
	requestType : 'get',//请求方式
	
	user:{
		name: '',
		nick: ''
	},
	
	login:{
		templateId:'js%ms-js%login',
		//templateId:'sample%user%loginform',
		checkUrl : '/mock/check-login.json',
		url: '/mock/login.json'
	},
	
	goods: {
		//publisNowUrl : '/mock/goods-publish-now.josn',
		publisNowUrl : '/sample/goods/insert'
	},
	
	order: {
		carChangeUrl: '/mock/car-change.json',
		deleteUrl:'/mock/car-change.json'
	}
};


