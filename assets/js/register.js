$(document).ready(function() {
	$('#hideLogin').click(function() {
		showRegisterForm();
	});

	$('#hideRegister').click(function() {
		showLoginForm();
	});
});

function showRegisterForm() {
	$('#loginForm').hide();
	$('#registerForm').show();
}

function showLoginForm() {
	$('#loginForm').show();
	$('#registerForm').hide();
}
