(function($, win, doc) {

    var Comment;

    Comment = function(elment, options) {

        var self = this;

        self.options = $.extend({}, options, self.options, true);
        self.$container = $(elment);

        self.init();
    };

    $.extend(Comment.prototype, {

        options : {
            showRespondClass : ".showRespond",
            doRespondClass : '.doRespond',
            respondIdPre : '#respond-list-',
            respondBoxPre : '#respond-list-box-',
            respondBoxClass : '.respond-el',
            showRespondToClass : '.reply-to',
            respondToBoxClass : '.respond-to-user',
            el : ''
        },

        showRespond : function(element, options) {

            var self = this, $list = $(self.options.respondIdPre + options.id);

            $.ajax({
                url : global.config.comment.showRespondUrl,
                type : global.config.requestType,
                data : {
                    'comment_id' : options.id,
                    'product_id' : options.productId
                },
                dataType : 'text',
                success : function(data) {

                    $list.html(data).slideDown();

                    $list.parent().find('.ajax-loading').hide();
                    $(element).removeClass('ajax-doing');

                },
                error : function(data) {

                    $list.parent().find('.ajax-loading').html('加载失败,请重新加载...');
                    $(element).removeClass('ajax-doing');
                }
            });
        },

        doRespond : function(element, options) {

            var self = this, params = {}, $text,
            
            commentListId = options.commentListId;
            
            params.comment = options.comment;
            params.product_id = options.productId;
            params.comment_id = options.id;
            params.user_id = options.userId;
            
            $text = options.txtEl ? $(options.txtEl) : $(element).prev();

            $.ajax({
                url : global.config.comment.doRespondUrl,
                type : global.config.requestType,
                data : params,
                dataType : 'json',
                success : function(res) {

                    var data = res.data, templateHtml='',
                    $html = null, $list, templateId, templateData,
                    $contanier;
                    
                    templateId = global.config.goods.respondTemplateId;
                    
                    $list = $(self.options.respondIdPre + commentListId);
                    $contanier =  $list;
                    
                    if(options.txtEl){
                        templateId = global.config.goods.commentTemplateId;
                        $contanier = $('#goods-comment-list');
                    }
                    

                    if (res.status == 200) {
                        
                        templateHtml = $.MSspirit.template({id : templateId}).getHtml();
                        
                        if(options.txtEl){
                            
                            $html =  $(nunjucks.renderString(templateHtml, {comment: data}));
                        }else{
                            
                            $html =  $(nunjucks.renderString(templateHtml, {reply: data}));
                            $list.find('.respond-to-user').hide();
                        }
                        
                        
                        $html.prependTo($contanier).fadeIn();
                        $text.val('');
                    } else if (res.status == 403) {

                        new $.MSspirit.login($('body'), {}).showDialog();

                    } else if (res.status == 500) {

                        alert(res.msg);
                    }

                    $(element).removeClass('ajax-doing');
                },
                error : function(data) {

                    $(element).removeClass('ajax-doing');
                    alert('网络错误');
                }
            });

        },

        //事件注册
        showReplyTo : function(element, event) {

            var self = this, $el = $(element), $box;

            $box = $el.parents(self.options.respondBoxClass).find(self.options.respondToBoxClass);

            $box.toggle();

        },

        replyTo : function(element, event, txtEl) {
            var self = this, $el = $(element), id = $el.attr('data-id'),
                productId = $el.attr('data-productid'), 
                userId = $el.attr('data-userid'), 
                commentListId = $el.parents('.respond-list').attr('data-id'),
                val = txtEl ? $(txtEl).val() : $el.prev().val();

            if ($.trim(val) == '') {
                alert('请输入内容');
                return;
            }

            if ($el.hasClass('ajax-doing')) {

                return;
            } else {
                $el.addClass('ajax-doing');
                self.doRespond($el, {
                    'id' : id,
                    productId : productId,
                    userId : userId,
                    comment : val,
                    commentListId: commentListId,
                    txtEl : txtEl
                });
            }

        },

        bindEvents : function() {

            var self = this;

            self.$container.on('click.doRespond.ms', self.options.doRespondClass, function(event) {

               self.replyTo(this, event);
               return false;

            });
            
            $('#comment-now').on('click.doRespond.ms',function(event){
                
                self.replyTo(this, event, $('#comment-now-textarea')[0]);
                return false;
            });

            self.$container.on('click.showRespond.ms', self.options.showRespondClass, function() {

                var $this = $(this), id = $this.attr('data-id'), $box, $list, productId = $this.attr('data-productid');

                $box = $(self.options.respondBoxPre + id);
                $list = $(self.options.respondIdPre + id);

                if ($this.hasClass('open')) {

                    $box.slideUp();

                    $this.removeClass('open');
                } else {

                    if ($.trim($list.html()) != '') {

                        $box.slideDown();

                    } else {

                        if ($this.hasClass('ajax-doing')) {
                            return;
                        }

                        $this.addClass('ajax-doing');

                        $box.show();

                        $list.parent().find('.ajax-loading').html('评论正在加载中 ...').show();

                        self.showRespond($this, {
                            id : id,
                            productId : productId

                        });
                    }

                    $this.addClass('open');
                }

                return false;

            });

            self.$container.on('click.showReplyTo.ms', self.options.showRespondToClass, function(event) {
                self.showReplyTo(this, event);
                return false;
            });


        },

        init : function() {

            var self = this;

            self.bindEvents();
        }
    });

    new Comment($('#goods-comment-list'));

})(jQuery, window, document);
