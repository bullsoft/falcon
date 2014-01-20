/**
 * @author xiejunfeng
 */
$.validateExtend({
	chinese : {
		required : true,
		pattern : /[\u4e00-\u9fa5]+/
	},
	phone : {
		required : true,
		pattern : /^[0-9-]+$/,
		description : {
			required : '<div class="alert alert-error">Required</div>',
			pattern : '<span style="color:#000;height:40px;line-height:40px;font-size:14px" class="alert alert-error">Pattern</span>',
			conditional : '<div class="alert alert-error">Conditional</div>',
			valid : '<div class="alert alert-success">Valid</div>'
		}
	},
	postcode : {
		required : true,
		pattern: /^[0-9]+$/
	}
});