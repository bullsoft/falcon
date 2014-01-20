(function($, win, doc){
	var consignee = {
		showConsigneeList: function(event){
			var context =  consignee, self = this, $self = $(this);
			if($self.hasClass('doing'))return;
			$self.addClass('doing');
			context.$personInfo.slideUp(function(){
				$self.removeClass('doing');
			});
			context.$modifyBox.slideDown();
		},
		showEditBox: function(event){
			var context = consignee, $self = $(this),
				$current = context.$modifyBox.find('li.current'),
				$currentCheckInput = $current.find('input[type="radio"]'),
				$li = $self.parents('li'),
				$liCheckInput = $li.find('input[type="radio"]'),
				$box = $li.find('.consignee-info-edit');
			
			if($self.hasClass('editing')){
				$self.removeClass('editing');
				$box.stop(true,true).slideUp();
				$self.text('[编辑]');
			}else{
				$self.addClass('editing');
				$current.removeClass('current');
				if($current[0] != $li[0]){
					$current.find('.edit').text('[编辑]').removeClass('editing');
					$current.find('.consignee-info-edit').slideUp();
					$currentCheckInput[0].checked = false;
				}
				$liCheckInput[0].checked = true;
				$self.text('[取消编辑]');
				$li.addClass('current');
				$box.stop(true,true).slideDown();
			}
			
		},
		setToDefault : function(event){
			var $self = $(this), context = consignee,
				$lis = context.$modifyBox.find('li'),
				$li = $self.parents('li');
			$lis.find('.setToDefault').show();
			$lis.find('.default-txt').remove();
			$self.hide();
			$self.after('<span class="default-txt uk-icon-ok">已经设置为默认地址</span>');
		},
		doSelected: function(event){
			var $self = $(this), context = consignee,
				$currentLi = context.$modifyBox.find('li.current'),
				$currentCheckInput = $currentLi.find('input[type="radio"]'),
				$li = $self.parents('li');
			if($currentLi[0] != $li[0]){
				$currentLi.removeClass('current');	
				$li.addClass('current');
			}
		},
		bindEvents : function(){
			var self = this;
			self.$InfoBox.find('.modify').click(self.showConsigneeList);
			self.$modifyBox.on('click','.edit',self.showEditBox);
			self.$modifyBox.on('click','.setToDefault',self.setToDefault);
			self.$modifyBox.on('click','.consignee-check',self.doSelected);
			self.$modifyBox.find('.consignee-info-edit').validate({
				onBlur: true
			});
		},
		init : function(){
			this.$InfoBox = $('#consignee-info-box');
			this.$modifyBox = $('#consignee-modify-box');
			this.$personInfo = $('#consignee-person-info');
			this.bindEvents();
		}
	};
	consignee.init();
})(jQuery, window, document);
