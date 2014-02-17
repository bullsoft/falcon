

global={};

global.config = {
	requestType : 'post',//请求方式
	
	user:{
		name: '',
		nick: ''
	},
	
	login:{
		templateId:'js%ms-js%login',
		checkUrl : '/mock/check-login.json',
		url: '/mock/login.json'
	},
	
	goods: {
		//publisNowUrl : '/mock/goods-publish-now.josn',
		publisNowUrl : '/sample/goods/insert'
	}
};


