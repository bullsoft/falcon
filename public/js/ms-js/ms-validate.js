(function($, win, doc) {"use strict";

	var MS, Validate, validateField, filedType, allTypes, 
		validationExtend = $.extend(true, {}, win.MSValidationExtend);

	if ($ === undefined) {throw '$未定义';return false;}

	if ($.MSspirit === undefined) {throw '$.MSspirit未定义';return false;}

	MS = $.MSspirit;
	
	
	filedType = ['input:not([type]),input[type="color"],input[type="date"],input[type="datetime"],input[type="datetime-local"],input[type="email"],input[type="file"],input[type="hidden"],input[type="month"],input[type="number"],input[type="password"],input[type="range"],input[type="search"],input[type="tel"],input[type="text"],input[type="time"],input[type="url"],input[type="week"],textarea', 'select', 'input[type="checkbox"],input[type="radio"]'],

	// All field types
	allTypes = filedType.join(','),
	
	validateField = function(event, options){
		
		var status, $field, fieldValue, fieldValidate, validation;
		// Field status 
		status = {pattern : true,conditional : true,required : true};
		// Current field
		$field = $(this);
	    // Current field value
		fieldValue = $field.val() || '';
        // An index of extend
		fieldValidate = $field.data('ms-validate');
		
		// A validation object (jQuery.fn.validateExtend)
		validation = (typeof fieldValidate === 'string')  ? validationExtend[fieldValidate] : {};
		
		
	};

	Validate = function(element,options) {
		var _options, $form, $fields, self = this;
		this.options = $.extend(true, {}, this.options, options);
		
		if(!element){throw 'Validate-->element不能为空 ';}
		
		$form = $(element);
		$fields = $form.find(allTypes);
		
		if($form.is('[id]')) {
			$fields = $fields.add('[form="' + $form.prop('id') + '"]').filter(allTypes);
		}
		
		$fields = $fields.filter(self.options.filter);
		
		$fields.each(function(index, el){
			var $this = $(this), validateEvent = $this.data('ms-validate-event');
			
			//自定义检查事件
			if(validateEvent){
				$this.on(validateEvent + '.' + self.namespace, function(event) {
					validateField.call(this, event, self.options);
				});
				return;
			}
			
			// If onKeyup is enabled
			if(!!self.options.onKeyup) {
				$this.filter(filedType[0]).on('keyup.' + self.namespace, function(event) {
					validateField.call(this, event, self.options);
				});
			}
			
			// If onBlur is enabled
			if(!!self.options.onBlur) {
	
				$this.on('blur.' + self.namespace, function(event) {
	
					validateField.call(this, event, self.options);
				});
			}
			
			// If onChange is enabled
			if(!!self.options.onChange) {
	
				$this.on('change.' + self.namespace, function(event) {
	
					validateField.call(this, event, self.options);
				});
			}
		
		});
		
	};
	


	$.extend(Validate.prototype, {
		nameSpace : 'ms.Validate',
		options : {
			sendForm : true, //是否只有经过验证的表单才能提交

			// Validate on submit?   // Validate on onKeyup? // Validate on onBlur? // Validate on onChange?
			onSubmit : true, onKeyup : false, onBlur : true, onChange : false,

			nameSpace : 'validate',// Default namespace

			conditional : {},// Conditional functions
			
			prepare : {},// Prepare functions
			
			description : {},// Fields descriptions
			
			// Callback
			eachField : $.noop, eachInvalidField : $.noop, eachValidField : $.noop, invalid : $.noop, valid : $.noop,

			filter : '*'// A fielter to the fields
		},
	});
	
	
	
	 MS["Validate"] = Validate;
	 MS["Validate"] = Validate;
	 
})(jQuery, window, document);
