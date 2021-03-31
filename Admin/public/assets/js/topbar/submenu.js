function showSubmenu(element) {
	element.classList.add('show');
	const dropdownMenu = element.querySelector('.dropdown-menu');
	let button = element.querySelector('button');
	if (!button) {
		button = element.querySelector('a');
		const text = button.querySelector('span').textContent.trim();
		if (text == 'Cadastros') {
			dropdownMenu.style['transform'] = 'translate3d(-43px, 70px, 0px)';
		} else if (text == 'Administrativo') {
			dropdownMenu.style['transform'] = 'translate3d(-95px, 70px, 0px)';
		} else {
			dropdownMenu.style['transform'] = 'translate3d(-10px, 70px, 0px)';
		}
		dropdownMenu.style['position'] = 'absolute';
		dropdownMenu.style['top'] = '0px';
		dropdownMenu.style['left'] = '0px';
		dropdownMenu.style['will-change'] = 'transform';
		dropdownMenu.setAttribute('x-placement', 'bottom-end');
	}
	dropdownMenu.classList.add('show');
	button.setAttribute('aria-expanded', true);
}

function hiddeSubmenu(element) {
	element.classList.remove('show');
	let button = element.querySelector('button');
	if (!button) {
		button = element.querySelector('a');
	}
	const dropdownMenu = element.querySelector('.dropdown-menu');
	button.setAttribute('aria-expanded', false);
	dropdownMenu.classList.remove('show');
}
