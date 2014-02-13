(function($, win, doc) {"use strict";

	var MS, Template, TemplateInit, entity, TemplateMap={};

	if ($ === undefined) {throw '$未定义';return false;}

	if ($.MSspirit === undefined) {throw '$.MSspirit未定义';return false;}

	MS = $.MSspirit;
	
	function rewriteUrl(options){
		var url, urlPostfix, urlPrefix;
		urlPostfix = options.urlPostfix ? options.urlPostfix : 'html';
		urlPrefix	= options.urlPrefix ? options.urlPrefix  : '/';
		url = options.id.replace(/-/g,'/');
		url = urlPrefix + url + '.' +  urlPostfix;
		return url;
	};
	
	Template = function(element, options){
		var html='', url='', _options, id;
		_options = options = $.isPlainObject(element) ? element : options;
		
		if($.trim(options.id) === ''){
			throw 'Template-->options.id不能为空 ';
		}
		this.options = $.extend(true, {}, this._options, options);
		html = TemplateMap[options.id];
		if(!html){
			url = rewriteUrl(options);
			$.ajax({
				url : url,
				async: false,
				dataType:'text',
				success:function(data){
					html = data;
				}
			});

			if(html){
				TemplateMap[options.id] = html;
			}
		}
		return this;
	};
	
	TemplateInit = function(element,options){
		
		return new Template(element,options);
		
	};
	
	$.extend(Template.prototype,{
		options: {
			urlPostfix: '',
			urlPrefix: ''
		},
		getHtml:function(){
			return TemplateMap[this.options.id];
		},
	});
	
	
	
	 MS["template"] = TemplateInit;
	 
	 
})(jQuery, window, document);
