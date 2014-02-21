$('.goods-collect').on('click',function(){
	
	var $this = $(this), $span = $this.find('span');

	if($this.hasClass('collected')){
		
		$this.removeClass('collected');
	}else{
		
		$this.addClass('collected').removeClass('hover');;
	}
	
});

$('.goods-collect').on('mouseenter',function(){
	
	var $this = $(this), $span = $this.find('span');

	if($this.hasClass('collected')){
		
		$this.addClass('hover');
	}else{
		
		$this.removeClass('hover');
	}
	
});

$('.goods-collect').on('mouseleave',function(){
	
	var $this = $(this), $span = $this.find('span');
	
	$this.removeClass('hover');
});


$('.goods-love').on('click',function(){
	
	var $this = $(this), $span = $this.find('span');

	if($this.hasClass('loved')){
		
		$this.removeClass('loved');
	}else{
		
		$this.addClass('loved').removeClass('hover');;
	}
	
	
});

$('.goods-love').on('mouseenter',function(){
	
	var $this = $(this), $span = $this.find('span');

	if($this.hasClass('loved')){
		
		$this.addClass('hover');
	}else{
		
		$this.removeClass('hover');
	}
	
});

$('.goods-love').on('mouseleave',function(){
	
	var $this = $(this), $span = $this.find('span');
	
	$this.removeClass('hover');
	
});