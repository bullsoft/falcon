(function($, doc) {

    "use strict";
    
    var MS = $.MSspirit || {};
    
    if (MS.fn) {return;}
    
    
    MS.fn = function(command, options) {

        var args = arguments, cmd = command.match(/^([a-z\-]+)(?:\.([a-z]+))?/i), component = cmd[1], method = cmd[2];

        if (!MS[component]) {
        	
            $.error("MSspirit component [" + component + "] does not exist.");
            return this;
        }
        
        return this.each(function() {
        	
            var $this = $(this), data = $this.data(component);
            
            if (!data) $this.data(component, (data = new MS[component](this, method ? undefined : options)));
           
            if (method) data[method].apply(data, Array.prototype.slice.call(args, 1));
        });
    };

    MS.version = '1.0.0';

    MS.support = {};
    
    MS.support.transition = (function() {

        var transitionEnd = (function() {

            var element = doc.body || doc.documentElement,
                transEndEventNames = {
                    WebkitTransition: 'webkitTransitionEnd',
                    MozTransition: 'transitionend',
                    OTransition: 'oTransitionEnd otransitionend',
                    transition: 'transitionend'
                }, name;
            for (name in transEndEventNames) {
                if (element.style[name] !== undefined) {
                    return transEndEventNames[name];
                }
            }

        }());

        return transitionEnd && { end: transitionEnd };
    })();
    
    MS.support.touch            = (('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch);
    MS.support.mutationobserver = (window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver || null);


    MS.Utils = {};
    
    
    MS.Utils.debounce = function(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    MS.Utils.options = function(string) {

        if ($.isPlainObject(string)) return string;

        var start = (string ? string.indexOf("{") : -1), options = {};

        if (start != -1) {
            try {
                options = (new Function("", "var json = " + string.substr(start) + "; return JSON.parse(JSON.stringify(json));"))();
            } catch (e) {}
        }

        return options;
    };

    $.MSspirit = MS;
    $.fn.ms = MS.fn;
    
})(jQuery, document);









