(function($, win, doc){
	var goods = {

		changePiece : function(event,options){
			var $this = (options && options.el) ? $(options.el) : $(this),
				$input = $this.parent().find('input'),
				val	= parseInt($input.val(),10) || 0;
			val = $(this).hasClass('add') ? (val + 1) : val -1;
			val = val < 0 ? 0 : val;
			val = val > 1000 ? 1000 : val;
			$input.val(val);
			event.preventDefault();
		},
		
		modifyPrice: function(event,options){
			var $this = (options && options.el) ? $(options.el) : $(this),
				val = parseInt($this.val(),10) || 0;
			val = val > 1000 ? 1000 : val;
			$this.val(val);
			event.preventDefault();
		},
		
		settleAccounts:function(){
			window.location.href = 'order.html';
		},		

		bindEvent : function(){
			var self = this;
			self.$goodsList.on('click','.subtract, .add',self.changePiece);
			self.$goodsList.on('keyup','.num-input',self.modifyPrice);
			$('body').on('click','.settle-accounts',self.settleAccounts);
		},
		
		init : function(){
			var self = this;
			self.$goodsList =  $('#goods-list');
			self.bindEvent();
		}
	};
	goods.init();
	
})(jQuery, window, document);
