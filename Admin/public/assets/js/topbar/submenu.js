const submenuIcons = [
	{
		name: 'Vendas',
		unhover: 'assets/images/widgets/report.png',
		hover: 'assets/images/widgets/report-hover.png',
	},
	// {
	// 	name: 'Recebimentos',
	// 	unhover: 'assets/images/widgets/receber.png',
	// 	hover: 'assets/images/widgets/receber-hover.png',
	// },
	{
		name: 'Recebimentos',
		unhover: 'assets/images/widgets/bolsa-de-dinheiro.png',
		hover: 'assets/images/widgets/bolsa-de-dinheiro-hover.png',
	},
	{
		name: 'Conciliação',
		unhover: 'assets/images/widgets/aprovado.png',
		hover: 'assets/images/widgets/aprovado-hover.png',
	},
];

function hoverSubmenu(element) {
	const text = element.textContent.trim();
	submenuIcons.map((item) => {
		if (item.name == text) {
			element.querySelector('img').setAttribute('src', `${item.hover}`);
		}
	});
}

function unhoverSubmenu(element) {
	const text = element.textContent.trim();
	submenuIcons.map((item) => {
		if (item.name == text) {
			element.querySelector('img').setAttribute('src', `${item.unhover}`);
		}
	});
}
