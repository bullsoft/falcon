
//设置全局模板，标签不转
nunjucks.configure({ autoescape: true });

global={};

global.config = {
	requestType : 'get' //请求方式
};
