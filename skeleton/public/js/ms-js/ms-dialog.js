(function($, win, doc) {"use strict";

	var MS, Modal, Buttons, active = null, html = '<div class="ms-dialog-wapper">' + '<div class="ms-dialog-box"><div class="ms-dailog-content"></div>' + '</div>' + '</div>';

	if ($ === undefined) {
		throw '$未定义';
	}

	if ($.MSspirit === undefined) {
		throw '$.MSspirit未定义';
	}

	MS = $.MSspirit;

	Buttons = function(options) {
		var temp, _options = options, buttons = {}, $temp;

		for (temp in _options.buttons) {
			if (temp !== 'cancel' || _options.onlyCancelButton === false) {
				$temp = $('<button class="ms-button ' + _options.buttons[temp].themeClass + '">' + _options.buttons[temp].name_cn + '</button>');
				if (_options.buttons[temp].style && $.isPlainObject(_options.buttons[temp].style)) {
					$temp.css(_options.buttons[temp].style);
				}
				buttons[temp] = $temp;
			}
		}

		return buttons;
	};

	Modal = function(element, options) {

		var self = this, temp = null, _options;

		_options = this.options = $.extend(true, {}, this.options, options);
	
		this.transition = MS.support.transition;

		this._initEl(element);
		
		this._bindEvents();
		
		this.$content.css({
			height : _options.height || 'auto',
			width : _options.width || 'auto'
		});

		if (_options.modal !== false) {
			this._createModal();
		}
		
		if(_options.loading !== true){
			this._createCloseEl();
		}else{
			this.$dialog.addClass('ms-loading');
		}
		
		if (_options.decorate !== false) {
			this._createTitle();
			this._createButtons();
		} else {
			this._modifyCloseBtn();
		}
		
		this.$container.append(this.$dialogWapper);

		this.$target.data("modal", this);

		this.show();
	};

	$.extend(Modal.prototype, {
		transition : false,

		options : {
			keyboard : true,
			show : false,
			bgclose : false, //点击背景是否关闭
			modal : true, //是否有遮罩
			decorate : true, //是否显示按钮和提示title
			container : 'body', //包含dialog的元素
			width : '',
			height : '',
			close : function() {
				this.destroy();
			},
			buttonSettings : {
				onlyCancelButton : false,
				buttons : {
					confirm : {
						name_cn : '确定',
						themeClass : 'ms-button-primary',
						style : null,
						callback : function() {
						}
					},
					cancel : {
						name_cn : '取消',
						themeClass : 'ms-button-success',
						style : null,
						close : true,
						callback : function() {
						}
					}
				}
			}
		},

		_initEl : function(element) {
			
			var self = this, temp = null, _options = this.options;

			temp = _options.id ? _options.id : (_options.contentEl ? _options.contentEl : (_options.html ? _options.html : null));
	
			if (_options.html) {
				temp = _options.html;
			}
	
			if (_options.id || _options.contentEl) {
				temp = _options.id || _options.contentEl;
				this.$orginEl = $(temp);
				this.$orginParentEl = this.$orginEl.parent();
				this.$OrginBeforeEl = this.$orginEl.prev();
				this.$OrginNextEl = this.$orginEl.next();
			}
	
			this.$dialogWapper = $(html);
			this.$dialog = this.$dialogWapper.find('.ms-dialog-box');
			this.$content = this.$dialogWapper.find('.ms-dailog-content');
			this.$target = $(element);

			temp && $(temp).appendTo(this.$content);
			

			this.$parent = this.$dialogWapper;
			if (this.options.container && this.options.container == 'body') {
				this.$container = $('html');
				this.$dialogWapper.css('position', 'fixed');
				this.positionType = 'fixed';
			} else {

				this.$dialogWapper.css('position', 'absolute');
				this.$container = $(this.options.container);
				this.$container.css('position', 'relative');
			}

		},

		_bindEvents : function() {

			var self = this;

			this.$dialogWapper.on("click", ".ms-dialog-close-btn", function(e) {

				e.preventDefault();
				self.hide();
			}).on("click", function(e) {

				var $target = $(e.target);

				if ($target[0] == self.$dialogWapper.find('.ms-dialog-shadow')[0] && self.options.bgclose) {
					self.hide();
				}

			});

			if (this.options.keyboard) {

				$(document).on('keyup.ms.dialog.escape', function(e) {

					if (active && e.which == 27 && self.isActive())
						self.hide();
				});
			}

			this.$target.on('click.ms.dialog.show', function(e) {
				
				self.show();
			});

		},

		//修改关闭按钮的位置
		_modifyCloseBtn : function() {
			this.$close && this.$close.css({
				top : 0,
				right : 0
			});
		},
		//创建遮罩
		_createModal : function() {
			
			$('<div class="ms-dialog-shadow"></div>').prependTo(this.$dialogWapper);
		},
		_createTitle : function() {
			
			var self = this, $titleBox = $('<div class="ms-dialog-titlebar"></div>');
			if (self.options.title) {
				$titleBox.html('<h3 class="ms-dialog-title-text">' + self.options.title + '</h3>');
			};

			$titleBox.prependTo(this.$dialog);
		},
		
		//创建关闭按钮
		_createCloseEl: function(){
			$('<a class="ms-dialog-close-btn ms-close" href="#"></a>').prependTo(this.$content);
			this.$close = this.$dialogWapper.find('.ms-dialog-close-btn');
		},
		
		//创建按钮
		_createButtons : function() {
			var self = this, buttons, temp, tempBtnSetting, settings = self.options.buttonSettings, $buttonsBox = $('<div class="ms-dialog-btns"></div>');

			buttons = new Buttons(settings);

			for (temp in buttons) {
				tempBtnSetting = settings.buttons[temp];
				if ( typeof tempBtnSetting.callback == 'function') {
					buttons[temp].on('click', function() {
						tempBtnSetting.callback.apply(self);
					});
					//检测是否关闭dialog
					if (tempBtnSetting.close === true) {
						buttons[temp].on('click', function() {
							self.hide();
						});
					}

				}
				buttons[temp].prependTo($buttonsBox);
			}
			$buttonsBox.appendTo(this.$dialog);
		},
		toggle : function() {
			this[this.isActive() ? "hide" : "show"]();
		},
		
		show : function() {

			var self = this, $dialogWapper = self.$dialogWapper;

			if (self.isActive())return;
			
			if (active)active.hide(true);

			$dialogWapper.removeClass("ms-open").show();

			active = self;
			self.$container.addClass("ms-dialog-page").height();
			// force browser engine redraw

			self.resize();

			$dialogWapper.addClass("ms-open").trigger("ms.dialog.show");
		},

		hide : function(force) {
			var self = this, $dialogWapper = this.$dialogWapper;
			if (!this.isActive())
				return;

			if (!force && this.transition) {

				$dialogWapper.one(this.transition.end, function() {
					self._hide();
				}).removeClass("ms-open");

			} else {

				this._hide();
			}
		},
		destroy : function() {
			
			this.hide();
			
			if (this.$orginEl) {
				
				if (this.$OrginBeforeEl.length) {
					
					this.$OrginBeforeEl.after(this.$orginEl);
				} else if (this.$orginParentEl.length) {
					
					this.$orginParentEl.append(this.$orginEl);
				} else if (this.$orginNextEl.length) {
					
					this.$orginParentEl.before(this.$orginEl);
				}
				;
			}
			delete this.options;

			this.$target.data('modal', null);
			this.$target.data('dialog', null);

			this.$dialogWapper.remove();
			$(doc).off('keyup.ms.dialog.escape');
			this.$target.off('click.ms.dialog.show');
		},
		resize : function() {
			var modalwidth = parseInt(this.$dialog.css("width"), 10), modalheight = parseInt(this.$dialog.css("height"), 10), left = (this.$dialogWapper.width() - modalwidth) / 2, scrollTop = this.$container.scrollTop(), scrollLeft = this.$container.scrollLeft(), top = (this.$dialogWapper.height() - modalheight) / 2;
			if (top < 0)
				top = 0;
			//是否修正滚动条，造成的页面抖动
			if (this.positionType == 'fixed' && win.innerHeight < doc.body.clientHeight) {
				
				this.$container.css({'padding-right' : '17px'});
				this.paddingFixed = true;
			} else {
				
				this.paddingFixed = false;
			}
			
			if (this.positionType != 'fixed') {
				
				this.$dialogWapper.css({
					'top' : scrollTop,
					'left' : scrollLeft
				});
			}
			
			this.$dialog.css({
				'top' : top,
				'left' : left
			});
		},

		_hide : function() {
			
			var self = this, $dialogWapper = this.$dialogWapper;
			
			$dialogWapper.hide().removeClass("ms-open");
			
			//是否修正滚动条，造成的页面抖动
			if (this.paddingFixed) {
				this.$container.css({
					'padding-right' : '0'
				});
			}

			self.$container.removeClass("ms-dialog-page");

			if (active === this)active = false;

			$dialogWapper.trigger("ms.dialog.hide");

			if ( typeof this.options.close == 'function') {
				this.options.close.apply(this);
			}
		},

		isActive : function() {
			return (active == this);
		},
		
		getDialog: function(){
			return this.$dialog;
		},
	});

	var MSDialog = function(element, options) {

		var self = this, $element = $(element);

		if ($element.data("modal"))return;
		
		this.modal = new Modal($element, options);

		//methods
		$.each(["show", "hide", "isActive"], function(index, method) {
			self[method] = function() {
				return self.modal[method]();
			};
		});

		self.destroy = function() {
			self.modal['destroy']();
			self.modal = null;
			delete self.modal;
		};

	};
	
	var MsLoading = function(element, options){
		
		options = $.extend(true, {}, this.options, {loading: true,decorate:false});
		
		return new MSDialog(element, options);
	};

	MSDialog.Modal = Modal;

	MS["dialog"] = MSDialog;
	MS['loading'] = MsLoading;

	// init code
	$(doc).on("click.dialog.ms", "[data-ms-dialog]", function(e) {
		var $this = $(this), options = null, $el = null, Dialog, data = MS.Utils.options($this.attr('data-ms-dialog'));

		if ($.isPlainObject(data)) {
			options = data;
		}
		if (!$this.data("modal")) {
			new MSDialog($this, options);
		}
	});

	$(win).on("resize orientationchange", MS.Utils.debounce(function() {

		if (active)active.resize();

	}, 150));

})(jQuery, window, document);
