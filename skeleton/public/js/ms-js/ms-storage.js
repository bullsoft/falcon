(function($, win, doc) {"use strict";

	var MS, Storage, InitStorage, thisObj;

	if ($ === undefined) {throw '$未定义';}

	if ($.MSspirit === undefined) {throw '$.MSspirit未定义';}

	MS = $.MSspirit;
	
	Storage = function(element, options){
		var that = this, _options;
		
		_options = options = $.isPlainObject(element) ? element : options;
		
		this.options = $.extend(true, {}, _options, options);
		
		if(!options.nameSpace){
			throw 'Storage没有命名空间';
		}
		
		that.nameSpace = new RegExp(options.nameSpace, "i");
		
	};
	
	InitStorage = function(element, options){
		if(!thisObj) thisObj = new Storage(element, options);
		
		return thisObj;
	};
	
	$.extend(Storage.prototype,{
		_body:$('<input style="display:none;behavior:url(#default#userData)" id="usersData">'),
		_append:0,
		able:false,
		nameSpace: /^top/,
		ini : function(atr,val) {
			var that, el;
			that = this;
			
			try {
				if (window.localStorage) {
					if (val != undefined) {
						localStorage[atr] = val;
					} else {
						return localStorage[atr];
					};
				} else if ($.browser.msie) {
					el = that._body[0];
					if (!that._append) {
						document.body.appendChild(el);
						that._append = 1;
					};
					try {
						el.load('oXMLBranch');
					} catch(f) {
						alert(f);
					};
					if (val != undefined) {
						$(el).attr(atr,val);
						el.save("oXMLBranch");
					} else {
						return $(el).attr(atr) || "";
					};
				} else {
					return "$NO$";
				};
			} catch(f) {
				alert(f);
			};
		},
		clear : function() {
			var that, local, reg;
			that = this;
			//TODO 可以利用全局变量来控制是正则，也可以通过传参来控制
			reg = this.nameSpace;
			local = window.localStorage;
			if (local) {
				for (var i in local) {
					i.match(reg) || (local[i] = "");
				}
			} else if ($.browser.msie) {
				var el = that._body[0];
				if (!that._append) {
					document.body.appendChild(el);
					that._append = 1;
				};
				el.load('oXMLBranch');
				// console.log(el.xmlDocument.firstChild.attributes.toStirng());
				$.each(el.xmlDocument.firstChild.attributes, function(index, v) {
					try {
						v.nodeName.match(reg) || f.removeAttribute(v.nodeName);
					} catch(f) {
						alert(f);
					};
				});
				el.save("oXMLBranch");
			};
		}
	});
	
	Storage.prototype.able = new Storage({nameSpace: 'test'}).ini('x') != '$NON$';
	
	MS['storage'] = InitStorage;
	
})(jQuery, window, document); 