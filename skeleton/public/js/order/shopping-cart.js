(function($, win, doc){
	var goods = {
		
		countRecord:{},

		changeXHR : {},
		
		goodsListPre:'goods-list-',
		
		changeAjax: function(id){
			
			var that = goods;
			
			params = {};
			params.id = id;
			params.number = that.countRecord['goods'+id];
			
			if(that.changeXHR['goods'+id])that.changeXHR['goods'+id].abort();

			that.changeXHR['goods'+id] = $.ajax({
				url : global.config.order.carChangeUrl,
				type: global.config.requestType,
				data:params,
				dataType:'json',
				success: function(res){
					if(res.status == 200){
						that.changeParam(res.data);
					}
				},
				error: function(data){
					alert('网络错误');
				}
			});
		},
		
		changeParam: function(data){
			var that = goods,
				$goods = $('#goods-list-'+data.id),
				$goodsInfo = $('#goods-sequence-'+data.id),
				$price = $goods.find('.price-text'),
				$num = $goods.find('.num-text'),
				$total = $('#total-price-text'),
				$totalMin = $('#total-price-txt');
				
				
			if($goods.length == 0){
				alert('您的购物车中没有该商品，请全新页面重新尝试');
				return false;
			}
			
			$price.text(data.price);
			$num.val(data.count);
			$total.text(data['total-price']);
			$totalMin.text(data['total-price']);
			
			$goodsInfo.find('input[name="price"]').val(data.price);
			$goodsInfo.find('input[name="count"]').val(data.count);
			
		},
		
		changePiece : function(event,options){
			var that = goods, id = 0,
				$this = (options && options.el) ? $(options.el) : $(this),
				$input = $this.parent().find('input'),
				val	= parseInt($input.val(),10) || 0;
				
			id = parseInt($this.attr('data-id'),10) || 0;
			
			that.countRecord['goods'+id] = that.countRecord['goods'+id] ? that.countRecord['goods'+id] : val;
			
			that.countRecord['goods'+id] = $this.hasClass('add') ? (that.countRecord['goods'+id] + 1) : (that.countRecord['goods'+id] -1);
			//val = $this.hasClass('add') ? (val + 1) : val -1;
			
			that.countRecord['goods'+id] = that.countRecord['goods'+id] < 0 ? 0 : that.countRecord['goods'+id];
			that.countRecord['goods'+id] = that.countRecord['goods'+id] > 1000 ? 1000 : that.countRecord['goods'+id];
			
			that.changeAjax(id);
			
			$input.val(that.countRecord['goods'+id]);
			
			event.preventDefault();
			event.stopPropagation();
		},
		
		modifyPrice: function(event,options){
			var $this = (options && options.el) ? $(options.el) : $(this),
				val = parseInt($this.val(),10) || 0;
			val = val > 1000 ? 1000 : val;
			$this.val(val);
			
			event.preventDefault();
			event.stopPropagation();
		},
		
		settleAccounts:function(){
			window.location.href = 'order.html';
		},
		
		confirmDelete : function(){
			
		},
		
		delelteGoods: function(envent,options){
			var that = goods,params ={},
				$this = (options && options.el) ? $(options.el) : $(this),
				id = $this.attr('data-id');
			$('#' + that.goodsListPre + id).fadeOut();
			
			$.ajax({
				url : global.config.order.deleteUrl,
				type: global.config.requestType,
				data:params,
				dataType:'json',
				success: function(res){
					var data = res.data, html = '';
					if(res.status == 200){
						$('#' + that.goodsListPre + data.id).remove();
						if($('.goods-info').length == 0){
							html = '<div class="uk-alert uk-alert-warning no-goods">请前往挑选商品,<a href="">go>></a></div>';
							$('#goods-list-contanier').html(html);
						}
					}else{
						$('#' + that.goodsListPre + data.id).fadeIn();
						alert('商品删除失败');
					}
				},
				error: function(data){
					$('#' + that.goodsListPre + data.id).fadeIn();
					alert('网络错误，商品删除失败');
					
				}
			});
			
			
			event.preventDefault();
			event.stopPropagation();
		},		

		bindEvent : function(){
			var self = this;
			self.$goodsList.on('click','.subtract, .add',self.changePiece);
			self.$goodsList.on('keyup','.num-input',self.modifyPrice);
			self.$goodsList.on('click','.del-goods',self.delelteGoods);
			$('body').on('click','.settle-accounts',self.settleAccounts);
		},
		
		init : function(){
			var self = this;
			self.$goodsList =  $('#goods-list');
			$('#shopping-cart-form').html($('#goods-sequence-box').html());
			self.bindEvent();
		}
	};
	
	goods.init();
	
})(jQuery, window, document);
