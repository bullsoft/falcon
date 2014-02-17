//TODO  需要测试一下长时间请求，响应缓慢时 数据请求


(function($, MS) {
	
	"use strict";

	var renderers = {}, 
		Search = function(element, options) {
			var self = this, $element = $(element);
			if ($element.data("search"))
				return;
	
			this.options = $.extend({}, this.options, options);
	
			this.element = $element;
	
			this.timer = null;
			this.value = '';
			this.jqxhr = null;
			this.$input = this.element.find(".ms-search-field");
			this.form = this.$input.length ? $(this.$input.get(0).form) : $();
			this.$input.attr('autocomplete', 'off');
	
			this.$input.on({
				keydown : function(event) {

					if (event && event.which && !event.shiftKey) {
	
						switch (event.which) {
							case 13:
								// enter
								self.done(self.selected);
								event.preventDefault();
								break;
							case 38:
								// up
								self.pick('prev');
								event.preventDefault();
								break;
							case 40:
								// down
								self.pick('next');
								event.preventDefault();
								break;
							case 27:
							case 9:
								// esc, tab
								self.hide();
								break;
							default:
								break;
						}
					}
	
				},
				keyup : function(event) {
					self.form[self.$input.val() ? 'addClass' : 'removeClass'](self.options.filledClass);
					self.trigger();
				},
				blur : function(event) {
					 setTimeout(function() { self.hide(event); }, 200);
				}
			});
	
			self.form.find('button[type=reset]').bind('click', function() {
	            self.form.removeClass("ms-open").removeClass("ms-loading").removeClass("ms-active");
	            self.value = null;
	            self.$input.focus();
	        });
	
	        self.dropdown = $('<div class="ms-dropdown ms-search-dropdown "><ul class="ms-ini-nav"></ul></div>').appendTo(this.form).find('.ms-ini-nav');
	
	        if (self.options.flipDropdown) {
	            self.dropdown.parent().addClass('ms-dropdown-flip');
	        }
	
	        self.dropdown.on("mouseover", ">li", function(){
	            $this.pick($(this));
	        });
	
	        self.renderer = new renderers[self.options.renderer](self);
	
	        self.element.data("search", self);
		};

	$.extend(Search.prototype, {

		options : {
			source : false,
			param : 'search',
			method : 'post',
			minLength : 3,
			delay : 300,
			animation : 'slideDown',
			flipDropdown : false,
			match : ':not(.ms-skip)',
			skipClass : 'ms-skip',
			loadingClass : 'ms-loading',
			filledClass : 'ms-active',
			listClass : 'ms-search-results',
			hoverClass : 'ms-hover',
			onLoadedResults : function() {},
			renderer : "default"
		},
		//发起请求
		request : function(options){
			var self = this;

            self.form.addClass(self.options.loadingClass);

            if (self.options.source) {
            	if(self.jqxhr)self.jqxhr.abort();
                self.jqxhr = $.ajax($.extend({
                    url: self.options.source,
					//TODO 需要修改 测试需要
                    type: 'get' || self.options.method,
                   	dataType: 'json',
                    success: function(data) {
                        data = self.options.onLoadedResults.apply(self, [data]) || data;
                        self.form.removeClass(self.options.loadingClass);
                        self.suggest(data);
                    },
                    error: function(data){
                    	self.form.removeClass(self.options.loadingClass);
                    }
                }, options));

            } else {
                self.form.removeClass(self.loadingClass);
            }
		},
		
		pick: function(item) {
            var selected = false;

            if (typeof item !== "string" && !item.hasClass(this.options.skipClass)) {
                selected = item;
            }

            if (item == 'next' || item == 'prev') {

                var items = this.dropdown.children().filter(this.options.match);

                if (this.selected) {
                    var index = items.index(this.selected);

                    if (item == 'next') {
                        selected = items.eq(index + 1 < items.length ? index + 1 : 0);
                    } else {
                        selected = items.eq(index - 1 < 0 ? items.length - 1 : index - 1);
                    }

                } else {
                    selected = items[(item == 'next') ? 'first' : 'last']();
                }

            }

            if (selected && selected.length) {
                this.selected = selected;
                this.dropdown.children().removeClass(this.options.hoverClass);
                this.selected.addClass(this.options.hoverClass);
            }
        },
		//触发请求
		trigger : function() {

			var self = this, old = self.value, data = {};

			self.value = self.$input.val();

			if (self.value.length < self.options.minLength) {
				return self.hide();
			}

			if (self.value != old) {

				if (self.timer)
					window.clearTimeout(self.timer);
				self.timer = window.setTimeout(function() {
					data[self.options.param] = self.value;
					self.request({
						'data' : data
					});
				}, self.options.delay, self);
			}

			return self;
		},
		
		done : function(selected){
			this.renderer.done(selected);
		},
		//数据处理入口，显示数据
		suggest: function(data) {

            if (!data) return;

            if (data === false) {
                this.hide();
            } else {

                this.selected = null;

                this.dropdown.empty();

                this.renderer.suggest(data);

                this.show();
            }
        },
        show : function(){
        	if (this.visible) return;
            this.visible = true;
            this.form.addClass("ms-open");
        },
		hide : function(){
			if (!this.visible) return;
            this.visible = false;
            this.form.removeClass(this.options.loadingClass).removeClass("ms-open");
		}
	});
	
	
	Search.addRenderer = function(name, klass) {
        renderers[name] = klass;
    };

	//搜索结果渲染
    var DefaultRenderer = function(search) {
        this.search = search;
        this.options = $.extend({}, DefaultRenderer.defaults, search.options);
    };

    $.extend(DefaultRenderer.prototype, {

        done: function(selected) {

            if (!selected) {
                this.search.form.submit();
                return;
            }
			
            if (selected.hasClass(this.options.moreResultsClass)) {
                this.search.form.submit();
            } else if (selected.data('choice')) {
                window.location = selected.data('choice').url;
            }

            this.search.hide();
        },

        suggest: function(data) {

           var self  = this,
               events = {
                   'click': function(e) {
                       e.preventDefault();
                       self.done($(this).parent());
                   }
               };

            if (self.options.msgResultsHeader) {
                $('<li>').addClass(self.options.resultsHeaderClass + ' ' + self.options.skipClass).html(self.options.msgResultsHeader).appendTo(self.search.dropdown);
            }

            if (data.results && data.results.length > 0) {

                $(data.results).each(function(i) {
					if(i < self.options.maxCount){ 
                    	var temp,
                    		$item = $('<li><a href="' + (this.url || '#') + '">' + this.title + '</a></li>').data('choice', this);
						
						if($.isPlainObject(this['data-*'])){
							for(temp in this['data-*']){
									$item.attr('data-' + temp, this['data-*'][temp]);
							}
						}
						
                    	if (this["text"]) {
                        	$item.find("a").append('<span>' + this.text + '</span>');
                    	}
                    	
                    	self.search.dropdown.append($item);
                   }
                });

                if (this.options.msgMoreResults) {
                    $('<li>').addClass('ms-search-result-divider ' + self.options.skipClass).appendTo(self.dropdown);
                    if(data.results.length > self.options.maxCount){
                    	$('<li>').addClass(self.options.moreResultsClass).html('<a href="#">' + self.options.msgMoreResults + '</a>').appendTo(self.search.dropdown).on(events);
                	}
                }

                self.search.dropdown.find("li>a").on(events);

            } else if (this.options.msgNoResults) {
                $('<li>').addClass(this.options.noResultsClass + ' ' + this.options.skipClass).html('<a>' + this.options.msgNoResults + '</a>').appendTo(self.search.dropdown);
            }
        }
    });

    DefaultRenderer.defaults = {
        resultsHeaderClass: 'ms-search-results-header',
        moreResultsClass: 'ms-more-results'',
        noResultsClass: 'ms-no-results',
        maxCount : 5,
        msgResultsHeader: '\u641C\u7D22\u7ED3\u679C',
        msgMoreResults: '\u66F4\u591A...',
        msgNoResults: '\u6CA1\u6709\u641C\u5230\u7ED3\u679C'
    };

    Search.addRenderer("default", DefaultRenderer);


    MS["search"] = Search;
	
	// init code
	$(document).on("focus.search.mssprite", "[data-ms-search]", function(e) {
		var ele = $(this);

		if (!ele.data("search")) {
			var obj = new Search(ele, MS.Utils.options(ele.attr("data-ms-search")));
		}
	});
})(jQuery, jQuery.MSspirit); 