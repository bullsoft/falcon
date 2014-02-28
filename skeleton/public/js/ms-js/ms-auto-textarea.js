(function($, win, doc) {"use strict";

    var AutoArea, MS;

    if ($ === undefined) {
        throw '$未定义';
    }

    if ($.MSspirit === undefined) {
        throw '$.MSspirit未定义';
    }

    MS = $.MSspirit;

    AutoArea = function(element, options) {

        var self = this, temp = null, _options;

        _options = self.options = $.extend(true, {}, self.options, options);

        self.$element = $(element);

        self.$element.css('box-sizing', 'content-box');
        self.getBoxParams();
        self.getOrginParams();
        self._bindEvent();
        
        if(self.$element.val() !== ''){
            //alert(1111);
            self.resizeHeight();
        }
        
        self.$heightEl = null;

        if (!self.$element.data("autoarea")) {
            self.$element.data("autoarea", self);
        }

    };

    $.extend(AutoArea.prototype, {
        options : {
            increaseHeight : 18,
            lineNumber : 72,
        },

        getBoxParams : function() {

            var self = this, params = {};

            params['box-sizing'] = self.boxType = self.$element.css('box-sizing');
            params['font-family'] = self.fontType = self.$element.css('font-family');
            params['font-size'] = self.fontSize = self.$element.css('font-size');
            params['height'] = self.height = self.$element.height();
            params['width'] = self.width = self.$element.width();

            self.boxParams = params;
        },

        getOrginParams : function() {

            var self = this;

            self.orginWidth = self.width;
            self.orginHeight = self.minHeight = self.height;


        },

        resizeHeight : function() {
            var self = this, 
                minHeight = self.options.minHeight || self.minHeight,
                maxHeight = self.options.maxheight, height, el;

            el = self.$element[0];
            self.$element.height(10);
            self.$element.css('overflow', 'auto');
            
            height = el.scrollHeight;

            
            if (minHeight) {
                height = el.scrollHeight > minHeight ? el.scrollHeight : minHeight;
            }
            
            
            if (maxHeight) {
                height = el.scrollHeight > maxHeight ? maxHeight : el.scrollHeight;
            }
          
            
            self.$element[0].style.height = height + 'px';
            self.$element.css('overflow', 'hidden');

        },

        _bindEvent : function() {

            var self = this;
               
            //ie  propertychange监听会卡死
            self.$element.on('propertychange.autoarea.ms. input.autoarea.ms', $.proxy(self.resizeHeight , self));
        },
        
        destroy: function(){
            
           var self = this, $el = self.$element;
            
           $el.off('propertychange.autoarea.ms');
           $el.off('input.autoarea.ms');
           $el.data('autoarea', null);
            
        }

       
    });

    // init code
    $(doc).on("focus.autoarea.ms", "[data-ms-autoarea]", function(e) {
        var $this = $(this), options = null, $el = null, Dialog, data = MS.Utils.options($this.attr('data-ms-autoarea'));

        if ($.isPlainObject(data)) {
            options = data;
        }

        if (!$this.data("autoarea")) {
            new AutoArea($this, options);
        }
    });

})(jQuery, window, document);
