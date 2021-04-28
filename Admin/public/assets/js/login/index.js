const usernameInput = document.querySelector('#username');
const passwordInput = document.querySelector('#userpassword');

if (localStorage.getItem('email')) {
	usernameInput.value = localStorage.getItem('email');
}

const storedUsername = localStorage.getItem('email');
const storedPassword = localStorage.getItem('password');
const rememberMe = localStorage.getItem('remember-me');

if (rememberMe) {
	document.querySelector('#remember-me').checked = true;
	usernameInput.value = storedUsername;
	decryptPassword();
}

function encryptPassword() {
	$.ajax({
		url: '/encryptPassword',
		type: 'POST',
		header: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
		},
		data: {
			_token: $('meta[name="csrf-token"]').attr('content'),
			autenticacao,
		},
		dataType: 'JSON',
		success: function (res) {
			localStorage.setItem('remember-me', true);
			localStorage.setItem('email', autenticacao.user);
			localStorage.setItem('password', res.password);
		},
	});
}

function decryptPassword() {
	$.ajax({
		url: '/decryptPassword',
		type: 'POST',
		header: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
		},
		data: {
			_token: $('meta[name="csrf-token"]').attr('content'),
			password: localStorage.getItem('password'),
		},
		dataType: 'JSON',
		success: function (res) {
			passwordInput.value = res.password;
		},
	});
}

function clearCredentials() {
	localStorage.removeItem('remember-me');
	localStorage.removeItem('email');
	localStorage.removeItem('password');
}
