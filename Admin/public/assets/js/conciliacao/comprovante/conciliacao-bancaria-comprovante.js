const salesContainerComprovante = new SalesContainerProxy({
	id: 'comprovante',
	links: {
		search: searchForm.get('form').dataset.urlComprovantes,
		filter: searchForm.get('form').dataset.urlFiltrarComprovantes,
	},
});
const tableRenderComprovante = createTableRender({
	table: '#js-tabela-conciliacao-bancaria-comprovante',
	locale: 'pt-br',
	formatter,
});

let selectedComprovanteSales = [];

salesContainerComprovante.setupApi(apiConfig);

salesContainerComprovante.onEvent('beforeFetch', () => {
	toggleElementVisibility('#js-loader');
});

salesContainerComprovante.onEvent('fetch', (sales) => {
	toggleElementVisibility('#js-loader');
	document.querySelector(
		'#js-quantidade-registros-comprovante'
	).textContent = `(${sales.get('pagination').options.total || 0} registros)`;

	tableRenderComprovante.set('data', {
		body: sales.get('sales') || [],
		footer: sales.get('totals') || {},
	});

	tableRenderComprovante.render();
	sales.get('pagination').render();
});

salesContainerComprovante.onEvent('search', (sales) => {
	const resultadosDOM = document.querySelector('.resultados');

	const totals = sales.get('totals');
	updateBoxes(boxes, {
		...totals,
		TOTAL_TAXA: totals.TOTAL_TAXA,
		TOTAL_TARIFA_MINIMA: totals.TOTAL_TARIFA_MINIMA * -1,
	});

	if (resultadosDOM.classList.contains('hidden')) {
		resultadosDOM.classList.remove('hidden');
	}
	updateTotals();
});

salesContainerComprovante.onEvent('fail', (err) => {
	document.querySelector('#js-loader').classList.remove('hidden');
	document.querySelector('#js-loader').classList.add('hidden');
});

salesContainerComprovante.setPaginationConfig(
	{
		paginationContainer: document.querySelector('#js-paginacao-comprovante'),
	},
	async (page, pagination, event) => {
		await buildRequestComprovante({
			page,
			por_pagina: pagination.options.perPage,
		}).get();
	}
);

function buildRequestComprovante(params) {
	let requestHandler = () => {};

	const isSearchActive = salesContainerComprovante.get('active') === 'search';
	const sendRequest = isSearchActive
		? salesContainerComprovante.search.bind(salesContainerComprovante)
		: salesContainerComprovante.filter.bind(salesContainerComprovante);

	const filters = {
		...searchForm.serialize(),
		...comprovanteTableFilters,
		...tableRenderComprovante.serializeSortFilter(),
	};
	const bodyPayload = isSearchActive
		? {...filters}
		: {
				filters: {...filters},
				subfilters: {...tableRenderComprovante.serializeTableFilters()},
		  };

	const requestPayload = {
		params: {},
		body: bodyPayload,
	};

	requestHandler = async (params) => {
		requestPayload.params = {
			por_pagina: salesContainerComprovante.get('search').get('pagination')
				.options.perPage,
			...params,
		};

		await sendRequest(requestPayload);
	};

	return {
		requestHandler,
		params,
		get: async function () {
			await this.requestHandler(this.params);
		},
	};
}

tableRenderComprovante.shouldSelectRow((elementDOM) => {
	let shouldSelect = _defaultEvents.table.shouldSelectRow(elementDOM);
	if (['i', 'input'].includes(elementDOM.tagName.toLowerCase())) {
		shouldSelect = false;
	} else {
		shouldSelect = true;
	}

	return shouldSelect;
});

tableRenderComprovante.onRenderRow((row, data, tableRenderInstance) => {
	const checkboxDOM = row.querySelector('td input[data-value-key]');
	const value = data[checkboxDOM.dataset.valueKey];
	checkboxDOM.value = value;
	checkboxDOM.checked = false;

	checkboxDOM.addEventListener('change', (event) => {
		const target = event.target;
		const value = event.target.value;
		if (target.checked && !selectedComprovanteSales.includes(value)) {
			selectedComprovanteSales.push(value);
		} else if (!target.checked && selectedComprovanteSales.includes(value)) {
			selectedComprovanteSales = [
				...selectedComprovanteSales.filter((selected) => selected !== value),
			];
		}
		updateSelectedValue();
	});
	_defaultEvents.table.onRenderRow(row, data, tableRenderInstance);
});

tableRenderComprovante.onFilter(async (filters) => {
	const params = {
		por_pagina: document.querySelector('#js-por-pagina-comprovante').value,
	};

	salesContainerComprovante.toggleActiveData('filter');
	if (Object.keys(filters).length === 0) {
		salesContainerComprovante.toggleActiveData('search');
		params.page = 1;
	}

	await buildRequestComprovante(params).get();
	updateTotals();
});

tableRenderComprovante.onSort(async (elementDOM, tableInstance) => {
	const params = {
		por_pagina: document.querySelector('#js-por-pagina-comprovante').value,
	};

	_defaultEvents.table.onSort(elementDOM, tableInstance);
	await buildRequestComprovante(params).get();
});

async function onComprovantePerPageChanged(event) {
	salesContainerComprovante
		.get('search')
		.get('pagination')
		.setOptions({perPage: event.target.value});
	salesContainerComprovante
		.get('filtered')
		.get('pagination')
		.setOptions({perPage: event.target.value});
	await buildRequestComprovante({
		page: 1,
		por_pagina: event.target.value,
	}).get();
}

function exportar() {
	swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
	setTimeout(() => {
		openUrl(searchForm.get('form').dataset.urlExportar, {
			...searchForm.serialize(),
			...tableRenderComprovante.serializeTableFilters(),
			...serializeTableSortToExport(
				tableRenderComprovante.serializeSortFilter()
			),
		});
	}, 500);
}

function retornoCsv() {
	swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
	setTimeout(() => {
		openUrl(searchForm.get('form').dataset.urlRetornoCsv, {
			...searchForm.serialize(),
			...tableRenderComprovante.serializeTableFilters(),
			...serializeTableSortToExport(
				tableRenderComprovante.serializeSortFilter()
			),
		});
	}, 500);
}

function showTicket(id) {
	const sale = salesContainerComprovante
		.get('data')
		.get('sales')
		.find((sale) => sale.ID === id);
	Array.from(
		document.querySelectorAll('#comprovante-modal *[data-key]')
	).forEach((element) => {
		element.textContent = formatter.format(
			element.dataset.format || 'text',
			sale[element.dataset.key],
			''
		);
	});

	document.querySelector('#comprovante-modal').dataset.saleId = id;
}

function confirmUnjustify() {
	if (selectedComprovanteSales.length < 1) {
		swal('Ooops...', 'Selecione ao menos uma venda operadora.', 'error');
		return;
	}

	openConfirmDialog(
		'Tem certeza que deseja desfazer a justificativa?',
		(value) => {
			if (value) unjustify();
		}
	);
}

function unjustify() {
	const baseUrl = searchForm.get('form').dataset.urlDesjustificar;
	toggleElementVisibility('#js-loader');
	api
		.post(baseUrl, {
			...apiConfig,
			body: JSON.stringify({
				id: selectedComprovanteSales,
			}),
		})
		.then((json) => {
			if (json.status !== 'sucesso' && json.mensagem) {
				swal('Ooops...', json.mensagem, 'error');
				return;
			}

			const sales = salesContainerComprovante.get('data');

			const updatedSales = updateData(
				[...sales.get('sales')],
				[...json.vendas],
				'ID'
			).data;

			sales.set('sales', [...updatedSales]);

			selectedComprovanteSales = [];

			tableRenderComprovante.set('data', {
				body: [...updatedSales] || [],
				footer: sales.get('totals') || {},
			});

			tableRenderComprovante.render();
			swal('Justificativa desfeita!', json.mensagem, 'success');
		})
		.finally(() => {
			toggleElementVisibility('#js-loader');
		});
}

searchForm
	.get('form')
	.querySelector('button[data-form-action="submit"')
	.addEventListener('click', searchForm.get('onSubmitHandler'));

Array.from(
	document.querySelectorAll('.modal button[data-action="print"]')
).forEach((buttonDOM) => {
	buttonDOM.addEventListener('click', (e) => {
		const id = document.querySelector('#comprovante-modal').dataset.saleId || 0;
		openUrl(searchForm.get('form').dataset.urlImprimir.replace(':id', id));
	});
});

document
	.querySelector('#js-por-pagina-comprovante')
	.addEventListener('change', onComprovantePerPageChanged);

function renderComprovanteModal(id) {
	const sale = salesContainer
		.get('data')
		.get('sales')
		.find((sale) => sale.ID === id);
	const modal = document.querySelector('#comprovante-modal');
	const headerBoxes = modal
		.querySelector('.boxes')
		.querySelectorAll('[data-key]');
	headerBoxes.forEach((box) => {
		let content;
		if (!box.dataset.format) {
			content = `<img src="${sale[box.dataset.key]}" alt>`;
		} else {
			content = formatter.format(
				box.dataset.format || 'text',
				sale[box.dataset.key],
				''
			);
		}
		box.querySelector('.content').innerHTML = content;
	});
	document.querySelector(
		'#js-comprovante-table-title'
	).innerHTML = `Recebimentos ${sale.ADQUIRENTE} <span id="js-quantidade-registros-comprovante"></span>`;

	tableRenderComprovante.clearFilters();
	tableRenderComprovante.clearSortFilter();
	renderComprovanteTable(sale);
	renderExtratoTable(sale);
}

async function renderComprovanteTable(sale) {
	setComprovanteTableFilters(sale);
	await salesContainerComprovante.search({
		params: {
			por_pagina: document.querySelector('#js-por-pagina-comprovante').value,
		},
		body: {
			...comprovanteTableFilters,
		},
	});

	const total = salesContainerComprovante.get('data').get('pagination').options
		.total;
	document.querySelector(
		'#js-quantidade-registros-comprovante'
	).textContent = `(${total} registros)`;
	setComprovanteTotalValue();

	tableRender.clearFilters();
	tableRender.clearSortFilter();
}

let comprovanteTableFilters = {};

function setComprovanteTableFilters(sale) {
	comprovanteTableFilters = {
		data_pagamento: sale.DATA_PAGAMENTO,
		conta: sale.CONTA,
		agencia: sale.AGENCIA,
		adquirente: sale.ADQUIRENTE,
		banco: sale.BANCO,
	};
}

function updateSelectedValue() {
	if (selectedComprovanteSales.length > 0) {
		let totalValue = 0;
		selectedComprovanteSales.forEach((id) => {
			const sale = salesContainerComprovante
				.get('data')
				.get('sales')
				.find((sale) => sale.ID === id);
			totalValue += parseFloat(sale['VALOR']);
		});
		const cellValue = totalValue;
		const defaultCellValue = 0;
		const format = 'currency';

		const formattedValue = tableRender.formatCell(
			cellValue,
			format,
			defaultCellValue
		);
		document.querySelector(
			'#total-selecionado-comprovante'
		).innerHTML = `Total Selecionado ${formattedValue}`;
	} else {
		clearSelectedValue();
		clearTotalDiff();
	}
	updateTotalDiff();
}

function updateTotalDiff() {
	if (selectedComprovanteSales.length > 0 && selectedExtratoSales.length > 0) {
		let totalComprovante = 0;
		selectedComprovanteSales.forEach((id) => {
			const sale = salesContainerComprovante
				.get('data')
				.get('sales')
				.find((sale) => sale.ID === id);
			totalComprovante += parseFloat(sale['VALOR']);
		});
		let totalExtrato = 0;
		selectedExtratoSales.forEach((id) => {
			const sale = salesContainerExtrato
				.get('data')
				.get('sales')
				.find((sale) => sale.ID === id);
			totalExtrato += parseFloat(sale['VALOR']);
		});
		const formattedValue = tableRenderComprovante.formatCell(
			totalComprovante - totalExtrato,
			'currency',
			0
		);
		document.querySelector(
			'#total-diferenca'
		).innerHTML = `Diferença (Operadora - Extrato) <span>${formattedValue}</span>`;
	}
}

function clearTotalDiff() {
	document.querySelector(
		'#total-diferenca'
	).innerHTML = `Diferença (Operadora - Extrato) R$ 0,00`;
}

function setComprovanteTotalValue() {
	const totalValue = parseFloat(
		salesContainerComprovante.get('data').get('totals').TOTAL_PREVISTO_OPERADORA
	);

	const totalValueDOM = document.querySelector('#total-comprovante');
	totalValueDOM.innerHTML = tableRenderComprovante.formatCell(
		totalValue,
		'currency',
		0
	);
}

function clearSelectedValue() {
	selectedComprovanteSales = [];
	document.querySelector('#total-selecionado-comprovante').innerHTML =
		'Total Selecionado R$ 0,00';
}

function updateTotals() {
	clearSelectedValue();
	setComprovanteTotalValue();
}
