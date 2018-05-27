var __wf_timeout_handle = null;
(function($){
	$(document.body).on('wf/form_registered', function(e, $parent){
		$parent.find('.wf').each(function(){
			var $form = $(this), $inputs = $form.find('.has-logic :input');
			$inputs.each(function(){
				if ($.inArray( $(this).attr('type'), ['checkbox', 'radio'])) {
					if ($(this).is(':checked')) {
						$(this).trigger('change');
					}
				}
				else {
					$(this).trigger('change');
				}
			});
		});
	});
})(jQuery);
function wf_apply_rules(formId, fields, isInit){
	var rule_applied = 0;
	for(var i=0; i < fields.length; i++){
		wf_apply_field_rule(formId, fields[i], isInit, function(){
			rule_applied++;
			if(rule_applied == fields.length){
			}
		});
	}
}

function wf_apply_field_rule(formId, fieldId, isInit, callback){

	//console.log(window['wf_form_conditional_logic'][formId][fieldId]);
	var action = wf_check_field_rule(formId, fieldId, isInit, callback);
	//console.log(fieldId);
	if ('show' === action) {
		 $('#'+ formId).find('.wf-field-wrap-id-'+ fieldId).show();
	} else {
		 $('#'+ formId).find('.wf-field-wrap-id-'+ fieldId).hide();
	}
}

function wf_check_field_rule(formId, fieldId, isInit, callback){

	//if conditional logic is not specified for that field, it is supposed to be displayed
	if(!window["wf_form_conditional_logic"] || !window["wf_form_conditional_logic"][formId] || !window["wf_form_conditional_logic"][formId][fieldId])
		return "show";

	var conditionalLogic = window["wf_form_conditional_logic"][formId][fieldId];
	var action = wf_get_field_action(formId, conditionalLogic);

	return action;
}

function wf_get_field_action(formId, conditionalLogic){
	if(!conditionalLogic)
		return "show";

	//console.log('checking');
	//console.log(conditionalLogic["rules"]);

	var matches = 0;
	for(var i = 0; i < conditionalLogic["rules"].length; i++){
		var rule = conditionalLogic["rules"][i];
		if(wf_is_match(formId, rule))
			matches++;
	}

	var action;
	if( (conditionalLogic["logicType"] == "all" && matches == conditionalLogic["rules"].length) || (conditionalLogic["logicType"] == "any"  && matches > 0) )
		action = conditionalLogic["actionType"];
	else
		action = conditionalLogic["actionType"] == "show" ? "hide" : "show";

	return action;
}

function wf_is_match( formId, rule ) {

	var $               = jQuery,
		inputId         = rule.key,
		$inputs;

	$inputs = $('#'+ formId).find('[name="'+ inputId +'"]');

	var isCheckable = $.inArray( $inputs.attr( 'type' ), [ 'checkbox', 'radio' ] ) !== -1,
		isMatch     = isCheckable ? 
			wf_is_match_checkable( $inputs, rule, formId, inputId ) : 
			wf_is_match_default( $inputs, rule, formId, inputId );

	//console.log(rule);
	//console.log(wf_is_match_default( $inputs, rule, formId, inputId ));

	return isMatch;
}


function wf_is_match_default( $input, rule, formId, fieldId ) {

	var val        = $input.val(),
		values     = ( val instanceof Array ) ? val : [ val ], // transform regular value into array to support multi-select (which returns an array of selected items)
		matchCount = 0;
	
	

	for( var i = 0; i < values.length; i++ ) {

		// fields with pipes in the value will use the label for conditional logic comparison
		var hasLabel   = values[i] ? values[i].indexOf( '|' ) >= 0 : true,
			fieldValue = wf_get_value( values[i] );

		var fieldNumberFormat = wf_get_field_number_format( rule.fieldId, formId, 'value' );
		if( fieldNumberFormat && ! hasLabel ) {
			fieldValue = wf_format_number( fieldValue, fieldNumberFormat );
		}

		var ruleValue = rule.value;
		//if ( fieldNumberFormat ) {
		//	ruleValue = wf_format_number( ruleValue, fieldNumberFormat );
		//}
		//console.log(ruleValue);
		//console.log(fieldValue);

		if( wf_matches_operation( fieldValue, ruleValue, rule.operator ) ) {
			matchCount++;
		}

	}

	// if operator is 'isnot', none of the values can match
	var isMatch = rule.operator == 'isnot' ? matchCount == values.length : matchCount > 0;

	return isMatch;
}

function wf_is_match_checkable( $inputs, rule, formId, fieldId ) {

	var isMatch = false;

	$inputs.each( function() {

		var $input           = jQuery( this ),
			fieldValue       = wf_get_value( $input.val() ),
			isRangeOperator  = jQuery.inArray( rule.operator, [ '<', '>' ] ) !== -1,
			isStringOperator = jQuery.inArray( rule.operator, [ 'contains', 'starts_with', 'ends_with' ] ) !== -1;

		// if we are looking for a specific value and this is not it, skip
		if( fieldValue != rule.value && ! isRangeOperator && ! isStringOperator ) {
			return; // continue
		}

		// force an empty value for unchecked items
		if( ! $input.is( ':checked' ) ) {
			fieldValue = '';
		}

		if( wf_matches_operation( fieldValue, rule.value, rule.operator ) ) {
			isMatch = true;
			return false; // break
		}

	} );

	return isMatch;
}

function wf_format_number( value, fieldNumberFormat ) {

	decimalSeparator = '.';

	if( fieldNumberFormat == 'currency' ) {
		decimalSeparator = wfGetDecimalSeparator( 'currency' );
	} else if( fieldNumberFormat == 'decimal_comma' ) {
		decimalSeparator = ',';
	} else if( fieldNumberFormat == 'decimal_dot' ) {
		decimalSeparator = '.';
	}

	// transform to a decimal dot number
	value = wfCleanNumber( value, '', '', decimalSeparator );

	if( ! value ) {
		value = 0;
	}

	number = value.toString();

	return number;
}

function wf_try_convert_float(text){

	var format = 'decimal_dot';
	if( wfIsNumeric( text, format ) ) {
		var decimal_separator = format == "decimal_comma" ? "," : ".";
		return wfCleanNumber( text, "", "", decimal_separator );
	}

	return text;
}

function wf_matches_operation(val1, val2, operation){
	val1 = val1 ? val1.toLowerCase() : "";
	val2 = val2 ? val2.toLowerCase() : "";

	switch(operation){
		case "is" :
			return val1 == val2;
			break;

		case "isnot" :
			return val1 != val2;
			break;

		case ">" :
			val1 = wf_try_convert_float(val1);
			val2 = wf_try_convert_float(val2);

			return wfIsNumber(val1) && wfIsNumber(val2) ? val1 > val2 : false;
			break;

		case "<" :
			val1 = wf_try_convert_float(val1);
			val2 = wf_try_convert_float(val2);

			return wfIsNumber(val1) && wfIsNumber(val2) ? val1 < val2 : false;
			break;

		case "contains" :
			return val1.indexOf(val2) >=0;
			break;

		case "starts_with" :
			return val1.indexOf(val2) ==0;
			break;

		case "ends_with" :
			var start = val1.length - val2.length;
			if(start < 0)
				return false;


			var tail = val1.substring(start);
			return val2 == tail;
			break;
	}
	return false;
}

function wf_get_value(val){
	if(!val)
		return "";

	val = val.split("|");
	return val[0];
}

function wf_do_field_action(formId, action, fieldId, isInit, callback){
	var conditional_logic = window["wf_form_conditional_logic"][formId];
	var dependent_fields = conditional_logic["dependents"][fieldId];

	for(var i=0; i < dependent_fields.length; i++){
		var targetId = fieldId == 0 ? "#wf_submit_button_" + formId : "#field_" + formId + "_" + dependent_fields[i];
		var defaultValues = conditional_logic["defaults"][dependent_fields[i]];

		//calling callback function on the last dependent field, to make sure it is only called once
		do_callback = (i+1) == dependent_fields.length ? callback : null;

		wf_do_action(action, targetId, conditional_logic["animation"], defaultValues, isInit, do_callback, formId);

		wf.doAction('wf_post_conditional_logic_field_action', formId, action, targetId, defaultValues, isInit);
	}
}

function wf_do_next_button_action(formId, action, fieldId, isInit){
	var conditional_logic = window["wf_form_conditional_logic"][formId];
	var targetId = "#wf_next_button_" + formId + "_" + fieldId;

	wf_do_action(action, targetId, conditional_logic["animation"], null, isInit, null, formId);
}

function wf_do_action(action, targetId, useAnimation, defaultValues, isInit, callback, formId){
	var $target = jQuery(targetId);
	if(action == "show"){

		// reset tabindex for selects
		$target.find( 'select' ).each( function() {
			$select = jQuery( this );
			$select.attr( 'tabindex', $select.data( 'tabindex' ) );
		} );

		if(useAnimation && !isInit){
			if($target.length > 0){
				$target.slideDown(callback);
			} else if(callback){
				callback();
			}
		}
		else{

			var display = $target.data('wf_display');

			//defaults to list-item if previous (saved) display isn't set for any reason
			if ( display == '' || display == 'none' ){
				display = 'list-item';
			}

			$target.css('display', display);

			if(callback){
				callback();
			}
		}
	}
	else{

		//if field is not already hidden, reset its values to the default
		var child = $target.children().first();
		if (child.length > 0){
			var reset = wf.applyFilters('wf_reset_pre_conditional_logic_field_action', true, formId, targetId, defaultValues, isInit);

			if(reset && !wfIsHidden(child)){
				wf_reset_to_default(targetId, defaultValues);
			}
		}

		// remove tabindex and stash as a data attr for selects
		$target.find( 'select' ).each( function() {
			$select = jQuery( this );
			$select.data( 'tabindex', $select.attr( 'tabindex' ) ).removeAttr( 'tabindex' );
		} );

		//Saving existing display so that it can be reset when showing the field
		if( ! $target.data('wf_display') ){
			$target.data('wf_display', $target.css('display'));
		}

		if(useAnimation && !isInit){
			if($target.length > 0 && $target.is(":visible")) {
				$target.slideUp(callback);
			} else if(callback) {
				callback();
			}
		} else{
			$target.hide();
			if(callback){
				callback();
			}
		}
	}
}

function wf_reset_to_default(targetId, defaultValue){

	var dateFields = jQuery( targetId ).find( '.wfield_date_month input, .wfield_date_day input, .wfield_date_year input, .wfield_date_dropdown_month select, .wfield_date_dropdown_day select, .wfield_date_dropdown_year select' );
	if( dateFields.length > 0 ) {

		dateFields.each( function(){

			var element = jQuery( this );

			// defaultValue is associative array (i.e. [ m: 1, d: 13, y: 1987 ] )
			if( defaultValue ) {

				var key = 'd';
				if (element.parents().hasClass('wfield_date_month') || element.parents().hasClass('wfield_date_dropdown_month') ){
					key = 'm';
				}
				else if(element.parents().hasClass('wfield_date_year') || element.parents().hasClass('wfield_date_dropdown_year') ){
					key = 'y';
				}

				val = defaultValue[ key ];

			}
			else{
				val = "";
			}

			if(element.prop("tagName") == "SELECT" && val != '' )
				val = parseInt(val);


			if(element.val() != val)
				element.val(val).trigger("change");
			else
				element.val(val);

		});

		return;
	}

	//cascading down conditional logic to children to suppport nested conditions
	//text fields and drop downs, filter out list field text fields name with "_shim"
	var target = jQuery(targetId).find('select, input[type="text"]:not([id*="_shim"]), input[type="number"], textarea');

	var target_index = 0;

	target.each(function(){
		var val = "";

		var element = jQuery(this);

		//get name of previous input field to see if it is the radio button which goes with the "Other" text box
		//otherwise field is populated with input field name
		var radio_button_name = element.prev("input").attr("value");
		if(radio_button_name == "wf_other_choice"){
			val = element.attr("value");
		}
		else if(jQuery.isArray(defaultValue)){
			val = defaultValue[target_index];
		}
		else if(jQuery.isPlainObject(defaultValue)){
			val = defaultValue[element.attr("name")];
			if( ! val ) {
				// 'input_123_3_1' => '3.1'
				var inputId = element.attr( 'id' ).split( '_' ).slice( 2 ).join( '.' );
				val = defaultValue[ inputId ];
			}
		}
		else if(defaultValue){
			val = defaultValue;
		}

		if( element.is('select:not([multiple])') && ! val ) {
			val = element.find( 'option' ).not( ':disabled' ).eq(0).val();
		}

		if(element.val() != val) {
			element.val(val).trigger('change');
			if (element.is('select') && element.next().hasClass('chosen-container')) {
				element.trigger('chosen:updated');
			}
		}
		else{
			element.val(val);
		}


		target_index++;
	});

	//checkboxes and radio buttons
	var elements = jQuery(targetId).find('input[type="radio"], input[type="checkbox"]:not(".copy_values_activated")');

	elements.each(function(){

		//is input currently checked?
		var isChecked = jQuery(this).is(':checked') ? true : false;

		//does input need to be marked as checked or unchecked?
		var doCheck = defaultValue ? jQuery.inArray(jQuery(this).attr('id'), defaultValue) > -1 : false;

		//if value changed, trigger click event
		if(isChecked != doCheck){
			//setting input as checked or unchecked appropriately

			if(jQuery(this).attr("type") == "checkbox"){
				jQuery(this).trigger('click');
			}
			else{
				jQuery(this).prop("checked", doCheck);

				//need to set the prop again after the click is triggered
				jQuery(this).trigger('click').prop('checked', doCheck);
			}

		}
	});
}


function wf_get_field_number_format(fieldId, formId, context) {

    var fieldNumberFormats = '',
        format = false;

    if (fieldNumberFormats === '') {
        return format;
    }

    if (typeof context == 'undefined') {
        format = fieldNumberFormats.price !== false ? fieldNumberFormats.price : fieldNumberFormats.value;
    } else {
        format = fieldNumberFormats[context];
    }

    return format;
}
