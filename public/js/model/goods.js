(function($, win, doc) {

    var Goods = {};

    Goods.Case = {};

    Goods.emotionAction = function(params, callback, url) {

        $.ajax({
            url : url,
            type : global.config.requestType,
            data : params,
            dataType : 'json',
            success : function(res) {

                var data = res.data ,Login;

                if (res.status == 200) {
                    if ( typeof callback == 'function') {
                        callback(data);
                    }
                } else if (res.status == 403) {
                    //todo
                    
                    new $.MSspirit.login($('body'), {}).showDialog();
                    
                    
                }
                
                callback(data);
            },
            error : function() {
                alert('网络错误');
            }
        });

    };

    Goods.collectInit = function() {

        var that = this;

        that.$container.on('click', '.goods-collect', function() {

            var $this = $(this), $span = $this.find('span'), fun, params, url, Login, loginFlag;

            loginFlag = new $.MSspirit.login($('body'), {}).check();

            if (!loginFlag)
                return false;

            if ($this.hasClass('collected')) {

                url = global.config.goods.wishRemoveUrl;
                $this.removeClass('collected');
            } else {

                url = global.config.goods.wishCreateUrl;
                $this.addClass('collected').removeClass('hover');
            }

            params = {
                product_id : $this.attr('data-id')
            };

            Goods.emotionAction(params, function(data) {

                var $count = $this.find('.count');

                if (data.type == 'create') {

                    if(data.count != undefined)$count.text(data.count);
                    $this.addClass('collected');
                } else {

                    if(data.count != undefined)$count.text(data.count);
                    $this.removeClass('collected');
                }

            }, url);
            
            return false;

        });

        that.$container.on('mouseenter', '.goods-collect', function() {

            var $this = $(this), $span = $this.find('span');

            if ($this.hasClass('collected')) {

                $this.addClass('hover');
            } else {

                $this.removeClass('hover');
            }

        });

        that.$container.on('mouseleave', '.goods-collect', function() {

            var $this = $(this), $span = $this.find('span');

            $this.removeClass('hover');
        });
    };

    Goods.loveInit = function() {

        var that = this;

        that.$container.on('click', '.goods-love', function() {

            var $this = $(this), $span = $this.find('span');

            if ($this.hasClass('loved')) {

                $this.removeClass('loved');
            } else {

                $this.addClass('loved').removeClass('hover');
            }

            Goods.loveAction({}, function() {

            });

        });

        that.$container.on('mouseenter', '.goods-love', function() {

            var $this = $(this), $span = $this.find('span');

            if ($this.hasClass('loved')) {

                $this.addClass('hover');
            } else {

                $this.removeClass('hover');
            }

        });

        that.$container.on('mouseleave', '.goods-love', function() {

            var $this = $(this), $span = $this.find('span');

            $this.removeClass('hover');

        });
    };

    Goods.init = function(options) {

        this.$container = $(options.container);

        Goods.collectInit();
        Goods.loveInit();

    };

    win.GoodsEntity = Goods;

})(jQuery, window, document);
