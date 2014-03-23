(function($, win, doc) {"use strict";

	var MS, Validate, validateField, filedType, allTypes, defaultOptions = {},
		validationExtend = $.extend(true, {}, win.MSValidationExtend);

	if ($ === undefined) {throw '$未定义';return false;}

	if ($.MSspirit === undefined) {throw '$.MSspirit未定义';return false;}

	MS = $.MSspirit;
	
	
	filedType = ['input:not([type]),input[type="color"],input[type="date"],input[type="datetime"],input[type="datetime-local"],input[type="email"],input[type="file"],input[type="hidden"],input[type="month"],input[type="number"],input[type="password"],input[type="range"],input[type="search"],input[type="tel"],input[type="text"],input[type="time"],input[type="url"],input[type="week"],textarea', 'select', 'input[type="checkbox"],input[type="radio"]'];

	// All field types
	allTypes = filedType.join(',');
	
	defaultOptions = {
		sendForm : true, //是否只有经过验证的表单才能提交

		// Validate on submit?   // Validate on onKeyup? // Validate on onBlur? // Validate on onChange?
		onSubmit : true, onKeyup : false, onBlur : true, onChange : false,

		nameSpace : 'validate',// Default namespace

		conditional : {},// Conditional functions
		
		prepare : {},// Prepare functions
		
		describable: true,
		
		description : {},// Fields descriptions
		
		// Callback
		eachField : $.noop, eachInvalidField : $.noop, eachValidField : $.noop, invalid : $.noop, valid : $.noop,

		filter : '*'// A fielter to the fields
	};
	
	validateField = function(event, options){
		
		var status, $field, fieldValue, fieldValidate, validation,
			fieldPattern, fieldPrepare, fieldIgnoreCase, fieldConditional, fieldRequired,
			fieldDescribedby, fieldDescription, fieldTrim, 
			reTrue, reFalse, name, log;
		// Field status 
		status = {pattern : true, conditional : true, required : true};
		// Current field
		$field = $(this);
	    // Current field value
		fieldValue = $field.val() || '';
        // An index of extend
		fieldValidate = $field.data('ms-validate');
		
		// A validation object (jQuery.fn.validateExtend)
		validation = (fieldValidate !== undefined && fieldValidate)  ? validationExtend[fieldValidate] : {};
		
		// A regular expression to validate field value
		fieldPattern = ($field.data('ms-validate-pattern') || ($.type(validation.pattern) == 'regexp' ? validation.pattern : /(?:)/));
		
		// One index or more separated for spaces to prepare the field value
		fieldPrepare = $field.data('ms-validate-prepare') || validation.prepare,
		
		// Is case sensitive? (Boolean)
		fieldIgnoreCase = $field.data('ms-validate-ignoreCase') || validation.ignoreCase;
		
		// A index in the conditional object containing a function to validate the field value
		fieldConditional = $field.data('ms-validate-conditional') || validation.conditional;
		
		// Is the field required?
		fieldRequired = $field.data('ms-validate-required');
		fieldRequired = fieldRequired != '' ? (fieldRequired || !!validation.required) : true;

		// The description element id
		fieldDescribedby = $field.data('ms-validate-describedby') || validation.describedby;

		// An index of description object
		fieldDescription = $field.data('ms-validate-description') || validation.description;

		// The description object
		fieldDescription = $.isPlainObject(fieldDescription) ? fieldDescription : (options.description[fieldDescription] || {}),
		
		// Trim spaces?
		fieldTrim = $field.data('ms-validate-trim');
		fieldTrim = fieldTrim != '' ? (fieldTrim || !!validation.trim) : true;

		reTrue = /^(true|)$/i;

		reFalse = /^false$/i;

		name = 'ms-validate';
		
		// Trim spaces?
		if(reTrue.test(fieldTrim)) {

			fieldValue = $.trim(fieldValue);
		}
		
		
		// The fieldPrepare is a function?
		if($.isFunction(fieldPrepare)) {

			// Updates the fieldValue variable
			fieldValue = String(fieldPrepare.call(field, fieldValue));
		} else {

			// Is a function?
			if($.isFunction(options.prepare[fieldPrepare])) {

				// Updates the fieldValue variable
				fieldValue = String(options.prepare[fieldPrepare].call(field, fieldValue));
			}
		}
		
		// Is not RegExp?
		if($.type(fieldPattern) != 'regexp') {

			fieldIgnoreCase = !reFalse.test(fieldIgnoreCase);

			// Converts to RegExp
			fieldPattern = fieldIgnoreCase ? RegExp(fieldPattern, 'i') : RegExp(fieldPattern);
		}
		
		// The conditional exists?
		if(fieldConditional != undefined) {

			// The fieldConditional is a function?
			if($.isFunction(fieldConditional)) {

				status.conditional = !!fieldConditional.call($field, fieldValue, options);
			} else {

				var
					// Splits the conditionals in an array
					conditionals = fieldConditional.split(/[\s\t]+/);

				// Each conditional
				for(var counter = 0, len = conditionals.length; counter < len; counter++) {

					if(options.conditional.hasOwnProperty(conditionals[counter]) && !options.conditional[conditionals[counter]].call(field, fieldValue, options)) {

						status.conditional = false;
					}
				}
			}
		}

		// Is required?
		if(reTrue.test(fieldRequired)) {

			// Verifies the field type
			if($field.is(filedType[0] + ',' + filedType[1])) {

				// Is empty?
				if(!fieldValue.length > 0) {

					status.required = false;
				}
			} else if($field.is(filedType[2])) {

				if($field.is('[name]')) {

					// Is checked?
					if($('[name="' + $field.prop('name') + '"]:checked').length == 0) {

						status.required = false;
					}
				} else {

					status.required = field.is(':checked');
				}
			}
		}
		
		var	$describedby = $('[id="' + fieldDescribedby +'"]'),
				
			$describedTarget = $describedby.length ? $describedby : $field.parents('.ms-validate-line').find('.ms-validate-describe');
			$describedTarget = $describedTarget.length ? $describedTarget : $field.parent().find('.ms-validate-describe');
			
		log = fieldDescription.valid;

		if(!status.required) {

			log = fieldDescription.required;
		} else if(!status.pattern) {

			log = fieldDescription.pattern;
		} else if(!status.conditional) {

			log = fieldDescription.conditional;
		}
		if(event.type != 'keyup'){
			
			if($describedTarget.length > 0) {
	
				$describedTarget.html(log || '');
			}else if(options.describable){
				console.log(log);
				$field.after('<div class="ms-validate-describe">' + log || '' + '<div>');	
			}
		}
		
		
	};

	Validate = function(element,options) {
		var _options, $form, $fields, self = this;
		this.options = $.extend(true, {}, defaultOptions, options);
		
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
	
	$.extend({
	
		// Method to extends validations
		msValidateDescribeExtend : function(options) {

			return $.extend(validationExtend, options);
		},

		// Method to change the default properties
		msValidateOptionsExtend : function(options) {

			return $.extend(defaultOptions, options);
		}
	})

	$.extend(Validate.prototype, {
		nameSpace : 'ms.Validate'
	});
	
	
	
	 MS["Validate"] = Validate;
	 MS["Validate"] = Validate;
	 
})(jQuery, window, document);
