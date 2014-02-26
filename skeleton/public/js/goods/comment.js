(function($, win, doc){
    
    var Comment;
    
    Comment = function(elment, options){
        
        var self  = this;
        
        
        self.options = $.extend({},options,self.options,true);
        self.$container = $(elment);
        
        self.init();
    };
    
    $.extend(Comment.prototype,{
        
        options : {
            doRespondClass : ".doRespond",
            el:''
        },
        
        doRespond : function(){
            
        },
        
        bindEvents: function(){
            
            var self = this;

            self.$container.on('click.doRespond.ms', self.options.doRespondClass, function(){
                var $this = $(this),  id = $this.attr('data-id');
            });
            
        },
        
        //初始化元素对象
        initEl : function(){
            
            var self = this;
            
           // self.$respond = self.$container.find('.'+self.options.doRespondClass);
            
        },
        
        
        init: function(){
            
            var self = this;
            
            self.bindEvents();
        }
        
        
    });
    
    new Comment($('#goods-comment-list'));
    
})(jQuery, window, document);
