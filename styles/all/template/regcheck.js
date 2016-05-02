/**
* Javascript for tas2580 regcheck
*/
(function($) {
	'use strict';
	$.fn.checkUsername = function() {
		return this.each(function() {
			var inputField = $('input[name=username]');
			function trigger() {
				var res = check(inputField.val());
			}
			function check(name){
				$.getJSON(regcheck_url+'?username='+name,function(returndata){
					$('#username_status').html(returndata['message']);
					if(returndata['code'] === 1){
						inputField.css('background-color','#a9f5a9');
					} else {
						inputField.css('background-color','#f5a9a9');
					}
				 });
			}
			inputField.bind('blur', trigger).after('<div id="username_status"></div>');
		});
	};
	$.fn.checkEmail = function() {
		return this.each(function() {
			var inputField = $('input[name=email]');
			function trigger() {
				var res = check(inputField.val());
			}
			function check(email) {
				$.getJSON(regcheck_url+'?email='+email,function(returndata){

					$('#email_status').html(returndata['message']);
					if(returndata['code'] === 1){
						inputField.css('background-color','#a9f5a9');
					} else {
						inputField.css('background-color','#f5a9a9');
					}
				 });
			}
			inputField.bind('blur', trigger).after('<div id="email_status"></div>');
		});
	};
	$.fn.checkPassword = function() {
		return this.each(function() {
			var inputField = $('input[name=new_password]');
			function trigger() {
				var res = check(inputField.val());
			}
			function check(new_password){
				$.getJSON(regcheck_url+'?new_password='+new_password,function(returndata){

					$('#password_status').html(returndata['message']);
					if(returndata['code'] === 1){
						inputField.css('background-color','#a9f5a9');
					} else {
						inputField.css('background-color','#f5a9a9');
					}
				 });
			}
			inputField.bind('blur', trigger).after('<div id="password_status"></div>');
		});
	};
	$.fn.confirmPassword = function() {
		return this.each(function() {
			var inputField1 = $('input[name=password_confirm]');
			var inputField2 = $('input[name=new_password]');
			function trigger() {
				var res = check(inputField1.val(), inputField2.val());
			}
			function check(password_confirm, new_password) {
				$.getJSON(regcheck_url+'?password_confirm='+password_confirm+'&new_password='+new_password,function(returndata){
					$('#password_confirm_status').html(returndata['message']);
					if(returndata['code'] === 1){
						inputField1.css('background-color','#a9f5a9');
					} else {
						inputField1.css('background-color','#f5a9a9');
					}
				 });
			}
			inputField1.bind('blur', trigger).after('<div id="password_confirm_status"></div>');
		});
	};
	$(function() {
		$('input[name=username]').checkUsername();
		$('input[name=email]').checkEmail();
		$('input[name=new_password]').checkPassword();
		$('input[name=password_confirm]').confirmPassword();
	});
})(jQuery);