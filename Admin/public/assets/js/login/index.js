const usernameInput = document.querySelector('#username');
const passwordInput = document.querySelector('#userpassword');

const storedUsername = localStorage.getItem('email');
const storedPassword = localStorage.getItem('password');

if (storedUsername) {
	usernameInput.value = storedUsername;
}
if (storedPassword) {
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
