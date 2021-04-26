const usernameInput = document.querySelector('#username');
const passwordInput = document.querySelector('#userpassword');
if (localStorage.getItem('email')) {
	usernameInput.value = localStorage.getItem('email');
}

function rememberCredentials() {
	const username = usernameInput.value;
	const password = passwordInput.value;
	localStorage.setItem('email', username);
	console.log(password);
}
