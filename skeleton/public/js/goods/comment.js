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
            showRespondClass : ".showRespond",
            respondIdPre: '#respond-list-',
            el:''
        },
        
        showRespond : function(options){
            
            var self = this;
            
            $.ajax({
                url : global.config.comment.showRespondUrl,
                type: global.config.requestTyp,
                data: options,
                dataType: 'json',
                success: function(res){
                    
                    var data = res.data, $list = $(self.options.respondIdPre + data.id);
                    
                    if(res.status == 200){
                        
                        $list.html(data.html).slideDown();
                        
                    }else if(res.status == 403){
                        
                        
                    };
                    
                },
                error:function(data){
                    
                }
            });
        },
        
        bindEvents: function(){
            
            var self = this;

            self.$container.on('click.showRespond.ms', self.options.showRespondClass, function(){
                
                var $this = $(this),  id = $this.attr('data-id'), $list;
                
                $list = $(self.options.respondIdPre + id);
                
                if($.trim($list.html()) != ''){
                    
                    $list.slideDown();
                    
                }else{
                    
                    self.showRespond({'comment_id':id});
                }
                
                
                return false;
                
            });
            
        },
        
        //初始化元素对象
        initEl : function(){
            
            var self = this;
            
           // self.$respond = self.$container.find('.'+self.options.showRespondClass);
            
        },
        
        
        init: function(){
            
            var self = this;
            
            self.bindEvents();
        }
        
        
    });
    
    new Comment($('#goods-comment-list'));
    
})(jQuery, window, document);
