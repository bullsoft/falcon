(function($, win, doc){
	var goods = {
		
		countRecord:{},

		changeXHR : {},
		
		goodsListPre:'goods-list-',
		
		getIndex: function(id, providerId){
		    
		    return  id + '-' + providerId;
		},
		
		changeAjax: function(id, providerId){
			
			var that = goods, index = that.getIndex(id, providerId);
			
			params = {};
			params['product_id'] = id;
			params['provider_id'] = providerId;
			params['qty'] = that.countRecord['goods'+index];
			
			if(that.changeXHR['goods'+index])that.changeXHR['goods'+index].abort();

			that.changeXHR['goods'+index] = $.ajax({
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

				    if(data.readyState != 0){
				        
    					alert('网络错误');
				    }
				}
			});
		},
		
		changeParam: function(data){
			var that = goods, index = that.getIndex(data.id, data.provider),
				$goods = $('#goods-list-' + index),
				$goodsInfo = $('#goods-sequence-' + index),
				$price = $goods.find('.price-text'),
				$num = $goods.find('.num-text'),
				$total = $('#total-price-text'),
				$totalMin = $('#total-price-txt');
				
				
			if($goods.length == 0){
				alert('您的购物车中没有该商品，请全新页面重新尝试');
				return false;
			}
			
			$price.text(data.price);
			$num.val(data.qty);
			$total.text(data.totals.items);
			$totalMin.text(data.totals.items);
			
			$goodsInfo.find('input[name="price"]').val(data.price);
			$goodsInfo.find('input[name="count"]').val($.trim(data.qty));
			$goodsInfo.find('input[name="provider"]').val(data.provider);
			
		},
		
		changePiece : function(event,options){
			var that = goods, id = 0, providerId, index = 0, elType ='', val ='',
				$this = (options && options.el) ? $(options.el) : $(this),
				$input = $this.parent().find('input');
				
				
		    elType = $this[0].tagName;
		     
			id = parseInt($this.parent().attr('data-id'),10) || 0;
			providerId = parseInt($this.parent().attr('data-providerid'),10) || 0;
			index = that.getIndex(id, providerId);

		    if(elType == 'INPUT'){
		        
		        $input = $this;
		    }
		    
		    val   = parseInt($input.val(),10) || 1;
		    
		    val = val > 999 ? 999 : val;
				
			
			if(elType != 'INPUT'){
    			that.countRecord['goods'+index] = that.countRecord['goods'+index] ? that.countRecord['goods'+index] : val;
			    that.countRecord['goods'+index] = $this.hasClass('add') ? (that.countRecord['goods'+index] + 1) : (that.countRecord['goods'+index] -1);
			}else{

			    that.countRecord['goods'+index] = val;
			}
			//val = $this.hasClass('add') ? (val + 1) : val -1;
			
			that.countRecord['goods'+index] = that.countRecord['goods'+index] < 0 ? 0 : that.countRecord['goods'+index];
			that.countRecord['goods'+index] = that.countRecord['goods'+index] > 1000 ? 1000 : that.countRecord['goods'+index];
			
			that.changeAjax(id, providerId);
			
			$input.val(that.countRecord['goods'+index]);
			
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
				id = $this.attr('data-id'),
				providerId = $this.attr('data-providerid'),
				index = that.getIndex(id, providerId);

			$('#' + that.goodsListPre + index).fadeOut();
			
			$.ajax({
				url : global.config.order.deleteUrl,
				type: global.config.requestType,
				data:params,
				dataType:'json',
				success: function(res){
					var data = res.data, html = '', index;
					
					index = that.getIndex(data.id, data.provider);
					
					if(res.status == 200){
						$('#' + that.goodsListPre + index).remove();

						if($('.goods-info').length == 0){
							html = '<div class="uk-alert uk-alert-warning no-goods">请前往挑选商品,<a href="">go>></a></div>';
							$('#goods-list-contanier').html(html);
						}
					}else{
						$('#' + that.goodsListPre + index).fadeIn();
						alert('商品删除失败');
					}
				},
				error: function(data){
					$('#' + that.goodsListPre + index).fadeIn();
					
					if(data.readyState != 0){
					    
                        alert('网络错误');
                    }
					
				}
			});
			
			
			event.preventDefault();
			event.stopPropagation();
		},		

		bindEvent : function(){
			var self = this;
			self.$goodsList.on('click','.subtract, .add',self.changePiece);
		//	self.$goodsList.on('keyup','.num-input',self.modifyPrice);
			self.$goodsList.on('keyup','.num-input',self.changePiece);
			self.$goodsList.on('click','.del-goods',self.delelteGoods);
			$('body').on('click','.settle-accounts',self.settleAccounts);
		},
		
		init : function(){
			var self = this, html = '';
			self.$goodsList =  $('#goods-list');
			
			
			$('.goods-sequence-box').each(function(){
			    html += $(this).html();
			});
			$('#shopping-cart-form').html(html);
			self.bindEvent();
		}
	};
	
	goods.init();
	
})(jQuery, window, document);
