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
            doRespondClass : '.doRespond',
            respondIdPre: '#respond-list-',
            respondBoxPre: '#respond-list-box-',
            el:''
        },
        
        showRespond : function(element, options){
            
            var self = this,
                $list = $(self.options.respondIdPre + options.id);

            $.ajax({
                url : global.config.comment.showRespondUrl,
                type: global.config.requestType,
                data: {'comment_id': options.id, 'product_id':options.productId},
                dataType: 'text',
                success: function(data){
                    
                        
                    $list.html(data).slideDown();
                        
                        
                    $list.parent().find('.ajax-loading').hide();
                    $(element).remove('ajax-doing');
                    
                },
                error:function(data){
                    
                    $list.parent().find('.ajax-loading').html('加载失败,请重新加载...');
                    $(element).remove('ajax-doing');
                }
            });
        },
        
        doRespond : function(element, options){
            
            var self = this, params={};
            
            params.comment = options.comment;
            params.product_id = options.productId;
            params.comment_id = options.id;

            $.ajax({
                url : global.config.comment.doRespondUrl,
                type: global.config.requestType,
                data: params,
                dataType: 'json',
                success: function(res){
                    
                    var data = res.data;
                    
                    if(res.status == 200){
                        
                    }else if(res.status == 403){
                        
                        new $.MSspirit.login($('body'), {}).showDialog();
                        
                    }else if(res.status == 500){
                        
                        alert(res.msg);
                    }
                    
                    $(element).removeClass('.ajax-doing');
                },
                error:function(data){
                    
                    $(element).removeClass('.ajax-doing');
                    alert('网络错误');
                }
            });
             
        },
        
        bindEvents: function(){
            
            var self = this;
            
            self.$container.on('click.doRespond.ms', self.options.doRespondClass, function(){
                
                var $this = $(this), $text, val,
                    id = $this.attr('data-id'),
                    productId =  $this.attr('data-productid');
                    
                $text = $this.prev();
                val = $text.val();
                
                if($.trim(val) == ''){
                    alert('请输入内容');
                    return;
                }
                
                if($this.hasClass('.ajax-doing')){
                    
                   return;
                }else{
                    
                    self.doRespond($this, {'id':id, productId:productId,comment : val});
                }
                
            });

            self.$container.on('click.showRespond.ms', self.options.showRespondClass, function(){
                
                var $this = $(this),  id = $this.attr('data-id'),  $box,
                    $list, productId =  $this.attr('data-productid');
                
                $box = $(self.options.respondBoxPre + id);
                $list = $(self.options.respondIdPre + id);
                
                
                if($this.hasClass('open')){
                    
                    $box.slideUp();
                    
                    $this.removeClass('open');
                }else{
                    
                    
                    if($.trim($list.html()) != ''){
                        
                        $box.slideDown();
                        
                    }else{
                        
                        if($this.hasClass('ajax-doing')){return;}
                        
                        $this.addClass('ajax-doing');
                        
                        $box.show();
                        
                        $list.parent().find('.ajax-loading').html('评论正在加载中 ...').show();
                        
                        self.showRespond($this, {
                            id:id, 
                            productId:productId
                            
                         });
                    }
                    
                    $this.addClass('open');
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
