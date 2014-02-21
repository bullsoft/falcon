(function($, win, doc){
	var goods = {
		
		loadingTimer:null,
		submitDatas:{},
		//检测url
		checkSubmit:function(){
			
			var that = goods;
			
			if($.trim(that.$inputUrl.val()) ===''){
				
				return false;
			}
			
			return true;
		},
		
		render: function(data){
			
			var that = goods, template , str ='';
			
			template = $.MSspirit.template({id:'js%goods%detail'}).getHtml();
			str = nunjucks.renderString(template, {goods: data});
			
			return str;
		},
		
		// afterRender: function(){
			// var that = goods,
				// $lImgs= that.$detailContainer.find('.image-list img.l_img');
			// console.log($lImgs);
			// $lImgs.on('load',function(){
				// $(this).show();
			// });
		// },
		
		bindEvents:function(){
			
			var that = goods, url;
			
			$('#publish-detail-btn').on('click',function(e){
				if(!that.checkSubmit()){
					
					alert('不能为空,请填写');
					that.$inputUrl.focus();
				}else{
					
					that.$publishContainer.hide();
					that.$publishLoading.show();
					url = that.$publishForm.attr('action');
					
					//todo test
					// url = '/mock/publish-detail.json',
					$.ajax({
						url : url,
						type : 'post',
						data: {url: $.trim(that.$inputUrl.val())},
						dataType: 'josn',
						success: function(res){
							
							var html = '';
							
							if(res.status == 200){
								
								html = that.render(res.data || {});
								that.$detailContainer.html(html).show();
								//that.afterRender();
								that.submitParamsInit(res.data);
								that.$publishLoading.hide();
							}else if(res.status == 201){
								
								alert(res.msg);
							}else{
								
								alert(res.msg);
							}
						},
						error: function(){
							alert('网络错误，请稍后重试');
							that.$publishContainer.show();
							that.$publishLoading.hide();
						}
					});
				};
				
				e.preventDefault();
			});
			
			$('#publish-detail-form').submit(function(){
				return false;
			});
			
			$('body').on('click','#publish-now',function(){
				that.submit();
			});
		},
		
		submitParamsInit: function(data){
			
			var that = goods;
			that.submitDatas['name'] = data.name;
			that.submitDatas['price'] = data.price.replace(/[^0-9\.]/g,'');
			that.submitDatas['l_imgs'] = data.l_imgs;
			that.submitDatas['from'] = data.from;
			that.submitDatas['from_url'] = data.from_url;
		},
		
		checkParams : function(){
			var that = this;
			
			that.submitDatas.description = $.trim($('#recommend-description').val());
			
			if(!that.submitDatas.description){
				alert('推荐理由不能为空 ');
				return false;
			}
			
			return true;
		},
		
		submit: function(){
			
			var that = goods;
			
			if(!that.checkParams()){
				return false;
			}
			
			$.ajax({
				url: global.config.goods.publisNowUrl,
				data:that.submitDatas,
				type: global.config.requestType,
				dataType:'json',
				success:function(res){
					
					if(res.status == 200){

						res.data && res.data.forward && (window.location.href = res.data.forward);					
					}else{
						
						alert(res.msg || '未知错误');
					}
				},
				error:function(){
					alert('网络错误，请稍后重试');
				}
				
			});
		},
		
		init: function(){
			
			this.$inputUrl = $('#publish-detail-url');
			this.$publishBox = $('#publish-box');
			this.$publishContainer = $('#publish-container');
			this.$publishLoading = this.$publishBox.find('.loading');
			this.$publishForm = $('#publish-detail-form');
			this.$detailContainer = $('#detail-container');
			this.bindEvents();
		}
	};
	
	goods.init();
})(jQuery, window, document);
