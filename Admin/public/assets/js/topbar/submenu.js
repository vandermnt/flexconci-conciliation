const submenuIcons = [
	// {
	// 	name: 'Vendas',
	// 	unhover: 'assets/images/widgets/vendas.png',
	// 	hover: 'assets/images/widgets/vendas-hover.png',
	// },
	{
		name: 'Vendas',
		unhover: 'assets/images/widgets/cartao-de-credito.png',
		hover: 'assets/images/widgets/cartao-de-credito-hover.png',
	},
	// {
	// 	name: 'Recebimentos',
	// 	unhover: 'assets/images/widgets/receber.png',
	// 	hover: 'assets/images/widgets/receber-hover.png',
	// },
	// {
	// 	name: 'Recebimentos',
	// 	unhover: 'assets/images/widgets/bolsa-de-dinheiro.png',
	// 	hover: 'assets/images/widgets/bolsa-de-dinheiro-hover.png',
	// },
	{
		name: 'Recebimentos',
		unhover: 'assets/images/widgets/restituicao.png',
		hover: 'assets/images/widgets/restituicao-hover.png',
	},
	// {
	// 	name: 'Conciliação',
	// 	unhover: 'assets/images/widgets/aprovado.png',
	// 	hover: 'assets/images/widgets/aprovado-hover.png',
	// },
	{
		name: 'Conciliação',
		unhover: 'assets/images/widgets/conciliacao.png',
		hover: 'assets/images/widgets/conciliacao-hover.png',
	},
];

function hoverSubmenu(element) {
	// const text = element.textContent.trim();
	// submenuIcons.map((item) => {
	// 	if (item.name == text) {
	// 		element.querySelector('img').setAttribute('src', `${item.hover}`);
	// 	}
	// });
}

function unhoverSubmenu(element) {
	// const text = element.textContent.trim();
	// submenuIcons.map((item) => {
	// 	if (item.name == text) {
	// 		element.querySelector('img').setAttribute('src', `${item.unhover}`);
	// 	}
	// });
}

function showSubmenu(element) {
	element.classList.add('show');
	const button = element.querySelector('button');
	const dropdownMenu = element.querySelector('.dropdown-menu');
	button.setAttribute('aria-expanded', true);
	dropdownMenu.classList.add('show');
}

function hiddeSubmenu(element) {
	element.classList.remove('show');
	const button = element.querySelector('button');
	const dropdownMenu = element.querySelector('.dropdown-menu');
	button.setAttribute('aria-expanded', false);
	dropdownMenu.classList.remove('show');
}
