//初始化登录检测
$('body').ms('login.paramsInit', {
    checkUrl : global.config.login.checkUrl,
    templateId : global.config.login.templateId,
    user : global.config.user.name,
});

//设置全局模板，标签不转
nunjucks.configure({
    autoescape : true
});

$.MSspirit.template({
    id : global.config.login.templateId,
    urlPostfix : false
});
// $.MSspirit.template({id:global.config.login.templateId});

window.onbeforeunload = function() {
    if (window.openWin) {
        for (var p in window.openWin) {
            window.openWin[p].close();
        }
    }
};

$('body').on('click.clearNavShopping', '.clear-shopping-cart', function() {

    var ajaxTime = new Date().getTime(), $this = $(this);

    $this.data('ajax-time', ajaxTime);

    $.ajax({
        url : global.config.order.clearUrl,
        type : global.config.requestType,
        dataType : 'json',
        success : function(res) {

            if ($this.data('ajax-time') != ajaxTime)
                return;

            if (res.status == 200) {

                $('#nav-shopping-cart-list').html('<div class="null-shopping">购物车已空，请挑选商品...</div>');
                $('#nav-shopping-total-count').text(0);
                $('#nav-shopping-total-price').text('0.00');
                $('#shopping-cart-count').text(0);
            } else {

                alert(res.msg || '清空失败');
            }
        },
        error : function() {
            alert('网络错误');
        }
    });

});

$('body').on('click', '.del-goods', function() {

    var $this = $(this), $el = $this.parents('.shopping-cart-el'), params = {};

    params['product_id'] = $this.attr('data-id');
    params['provider_id'] = $this.attr('data-providerid');

    $el.fadeOut();
    
    console.log(params);

    $.ajax({
        url : global.config.order.deleteUrl,
        type : global.config.requestType,
        dataType : 'json',
        data: params,
        success : function(res) {
            
            var data = res.data;

            if (res.status == 200) {
                
                
                $el.remove();
                if($('#nav-shopping-cart-list').find('.shopping-cart-el').length == 0){
                    $('#nav-shopping-cart-list').html('<div class="null-shopping">购物车已空，请挑选商品...</div>');
                }
                $('#nav-shopping-total-count').text(data.total_num);
                $('#nav-shopping-total-price').text(data.total_price);
                $('#shopping-cart-count').text(data.total_num);

            } else {
                
                $el.fadeIn();
                alert(res.msg || '清空失败');
            }
        },
        error : function() {
            $el.fadeIn();
            alert('网络错误');
        }
    });

});

