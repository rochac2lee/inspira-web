(function($) {
	$.fn.extend({
		passwordValidation: function(_options, _callback, _confirmcallback) {
			//var _unicodeSpecialSet = "^\\x00-\\x1F\\x7F\\x80-\\x9F0-9A-Za-z"; //All chars other than above (and C0/C1)
			var CHARSETS = {
				upperCaseSet: "A-Z", 	//All UpperCase (Acii/Unicode)
				lowerCaseSet: "a-z", 	//All LowerCase (Acii/Unicode)
				digitSet: "0-9", 		//All digits (Acii/Unicode)
				specialSet: "\\x20-\\x2F\\x3A-\\x40\\x5B-\\x60\\x7B-\\x7E\\x80-\\xFF", //All Other printable Ascii
			}
			var _defaults = {
				minLength: 6,		  //Minimum Length of password 
				minUpperCase: 0,	  //Minimum number of Upper Case Letters caracteres in password
				minLowerCase: 0,	  //Minimum number of Lower Case Letters caracteres in password
				minDigits: 0,		  //Minimum number of digits caracteres in password
				minSpecial: 0,		  //Minimum number of special caracteres in password
				maxRepeats: 6,		  //Maximum number of repeated alphanumeric caracteres in password dhgurAAAfjewd <- 3 A's
				maxConsecutive: 6,	  //Maximum number of alphanumeric caracteres from one set back to back
				noUpper: false,		  //Disallow Upper Case Lettera
				noLower: false,		  //Disallow Lower Case Letters
				noDigit: false,		  //Disallow Digits
				noSpecial: false,	  //Disallow Special caracteres
				//NOT IMPLEMENTED YET allowUnicode: false,  //Switches Ascii Special Set out for Unicode Special Set 
				failRepeats: true,    //Disallow user to have x number of repeated alphanumeric caracteres ex.. ..A..a..A.. <- fails if maxRepeats <= 3 CASE INSENSITIVE
				failConsecutive: true,//Disallow user to have x number of consecutive alphanumeric caracteres from any set ex.. abc <- fails if maxConsecutive <= 3
				confirmField: undefined
			};
			//Ensure parameters are correctly defined
			if($.isFunction(_options)) {
				if($.isFunction(_callback)) {
					if($.isFunction(_confirmcallback)) {
					}
					_confirmcallback = _callback;
				}
				_callback = _options;
				_options = {};
			}

			//concatenate user options with _defaults
			_options = $.extend(_defaults, _options);
			if(_options.maxRepeats < 2) _options.maxRepeats = 2;

			function charsetToString() {
				return CHARSETS.upperCaseSet + CHARSETS.lowerCaseSet + CHARSETS.digitSet + CHARSETS.specialSet; 
			}

			//GENERATE ALL REGEXs FOR EVERY CASE
			function buildPasswordRegex() {
				var cases = [];

				//if(_options.allowUnicode) CHARSETS.specialSet = _unicodeSpecialSet;
				if(_options.noUpper) 	cases.push({"regex": "(?=" + CHARSETS.upperCaseSet + ")",  																				"message": "A senha não pode conter uma letra maiúscula"});
				else 					cases.push({"regex": "(?=" + ("[" + CHARSETS.upperCaseSet + "][^" + CHARSETS.upperCaseSet + "]*").repeat(_options.minUpperCase) + ")", 	"message": "A senha deve conter pelo menos <strong>" + _options.minUpperCase + " Letras maiúsculas.</strong>"});
				if(_options.noLower) 	cases.push({"regex": "(?=" + CHARSETS.lowerCaseSet + ")",  																				"message": "A senha não pode conter <strong>uma letra minúscula </strong>"});
				else 					cases.push({"regex": "(?=" + ("[" + CHARSETS.lowerCaseSet + "][^" + CHARSETS.lowerCaseSet + "]*").repeat(_options.minLowerCase) + ")", 	"message": "A senha deve conter pelo menos <strong>" + _options.minLowerCase + " Letras minúsculas.</strong>"});
				if(_options.noDigit) 	cases.push({"regex": "(?=" + CHARSETS.digitSet + ")", 																					"message": "A senha não pode conter <strong>um número</strong>"});
				else 					cases.push({"regex": "(?=" + ("[" + CHARSETS.digitSet + "][^" + CHARSETS.digitSet + "]*").repeat(_options.minDigits) + ")", 			"message": "A senha deve conter pelo menos <strong>" + _options.minDigits + " digitos.</strong>"});
				if(_options.noSpecial) 	cases.push({"regex": "(?=" + CHARSETS.specialSet + ")", 																				"message": "A senha não pode conter <strong>um caractere especial </strong>"});
				else 					cases.push({"regex": "(?=" + ("[" + CHARSETS.specialSet + "][^" + CHARSETS.specialSet + "]*").repeat(_options.minSpecial) + ")", 		"message": "A senha deve conter pelo menos <strong>" + _options.minSpecial + " caracteres especial.</strong>"});

				cases.push({"regex":"[" + charsetToString() + "]{" + _options.minLength + ",}", "message":"A senha deve conter pelo menos <strong>" + _options.minLength + " caracteres.</strong>"});
				return cases;
			}
			var _cases = buildPasswordRegex();

			var _element = this;
			var $confirmField = (_options.confirmField != undefined)? $(_options.confirmField): undefined;

			//Field validation on every captured event
			function validateField() {
				var failedCases = [];
		
				//Evaluate all verbose cases
				$.each(_cases, function(i, _case) {
					if($(_element).val().search(new RegExp(_case.regex, "g")) == -1) {
						failedCases.push(_case.message);
					}
				});
				if(_options.failRepeats && $(_element).val().search(new RegExp("(.)" + (".*\\1").repeat(_options.maxRepeats - 1), "gi")) != -1) {
					failedCases.push("Senha não pode conter " + _options.maxRepeats + " do mesmo caractere caso insensível.");
				}
				if(_options.failConsecutive && $(_element).val().search(new RegExp("(?=(.)" + ("\\1").repeat(_options.maxConsecutive) + ")", "g")) != -1) {
					failedCases.push("A senha não pode conter o mesmo caractere mais do que " + _options.maxConsecutive + " vezes seguidas.");
				}
				
				//Determine if valid
				var validPassword = (failedCases.length == 0) && ($(_element).val().length >= _options.minLength);
				var fieldsMatch = true;
				if($confirmField != undefined) {
					fieldsMatch = ($confirmField.val() == $(_element).val());
				}

				_callback(_element, validPassword, validPassword && fieldsMatch, failedCases);
			}

			//Add custom classes to fields
			this.each(function() {
				//Validate field if it is already filled
				if($(this).val()) {
					validateField().apply(this);
				}
				$(this).toggleClass("jqPassField", true);
				if($confirmField != undefined) {
					$confirmField.toggleClass("jqPassConfirmField", true);
				}
			});
			
			//Add event bindings to the password fields
			return this.each(function() {
				$(this).bind('keyup focus input proprtychange mouseup', validateField);
				if($confirmField != undefined) {
					$confirmField.bind('keyup focus input proprtychange mouseup', validateField);
				}
			});
		}
	});
})(jQuery);