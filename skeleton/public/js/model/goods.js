(function($, win, doc) {

	var Goods = {};

	Goods.Case = {};

	Goods.collectInit = function() {

		var that = this;

		that.$container.on('click', '.goods-collect', function() {

			var $this = $(this), $span = $this.find('span');

			if ($this.hasClass('collected')) {

				$this.removeClass('collected');
			} else {

				$this.addClass('collected').removeClass('hover');
			}

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
		
		console.log(that.$container);
		that.$container.on('click', '.goods-love', function() {

			var $this = $(this), $span = $this.find('span');

			if ($this.hasClass('loved')) {

				$this.removeClass('loved');
			} else {

				$this.addClass('loved').removeClass('hover');
				;
			}

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
