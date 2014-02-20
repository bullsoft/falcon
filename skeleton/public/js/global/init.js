
//初始化登录检测
$('body').ms('login.paramsInit', {
	checkUrl: global.config.login.checkUrl,
	templateId: global.config.login.templateId,
	user: global.config.user.name,
});


//设置全局模板，标签不转
nunjucks.configure({ autoescape: true });

$.MSspirit.template({id:global.config.login.templateId});



window.onbeforeunload = function(){
	if(window.openWin){
		for(var p in window.openWin){
			window.openWin[p].close();
		}
	}
};
