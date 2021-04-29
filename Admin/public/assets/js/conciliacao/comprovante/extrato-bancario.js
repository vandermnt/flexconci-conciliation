const salesContainerExtrato = new SalesContainerProxy({
	id: 'extrato',
	links: {
		search: searchForm.get('form').dataset.urlExtratos,
		filter: searchForm.get('form').dataset.urlFiltrarExtratos,
	},
});
const tableRenderExtrato = createTableRender({
	table: '#js-tabela-extrato',
	locale: 'pt-br',
	formatter,
});

let selectedExtratoSales = [];

salesContainerExtrato.setupApi(apiConfig);

salesContainerExtrato.onEvent('beforeFetch', () => {
	toggleElementVisibility('#js-loader');
});

salesContainerExtrato.onEvent('fetch', (sales) => {
	toggleElementVisibility('#js-loader');
	document.querySelector('#js-quantidade-registros-extrato').innerHTML = `(${
		sales.get('pagination').options.total || 0
	} registros)`;

	tableRenderExtrato.set('data', {
		body: sales.get('sales') || [],
		footer: sales.get('totals') || {},
	});

	tableRenderExtrato.render();
	sales.get('pagination').render();
});

salesContainerExtrato.onEvent('search', (sales) => {
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

salesContainerExtrato.onEvent('fail', (err) => {
	document.querySelector('#js-loader').classList.remove('hidden');
	document.querySelector('#js-loader').classList.add('hidden');
});

salesContainerExtrato.setPaginationConfig(
	{
		paginationContainer: document.querySelector('#js-paginacao-extrato'),
	},
	async (page, pagination, event) => {
		await buildRequestExtrato({
			page,
			por_pagina: pagination.options.perPage,
		}).get();
	}
);

function buildRequestExtrato(params) {
	let requestHandler = () => {};

	const isSearchActive = salesContainerExtrato.get('active') === 'search';
	const sendRequest = isSearchActive
		? salesContainerExtrato.search.bind(salesContainerExtrato)
		: salesContainerExtrato.filter.bind(salesContainerExtrato);

	const filters = {
		...searchForm.serialize(),
		...tableRenderExtrato.serializeSortFilter(),
	};
	const bodyPayload = isSearchActive
		? { ...filters }
		: {
				filters: { ...filters },
				subfilters: { ...tableRenderExtrato.serializeTableFilters() },
		  };

	const requestPayload = {
		params: {},
		body: bodyPayload,
	};

	requestHandler = async (params) => {
		requestPayload.params = {
			por_pagina: salesContainerExtrato.get('search').get('pagination').options
				.perPage,
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

tableRenderExtrato.shouldSelectRow((elementDOM) => {
	let shouldSelect = _defaultEvents.table.shouldSelectRow(elementDOM);
	if (['i', 'input'].includes(elementDOM.tagName.toLowerCase())) {
		shouldSelect = false;
	} else {
		shouldSelect = true;
	}

	return shouldSelect;
});

tableRenderExtrato.onRenderRow((row, data, tableRenderInstance) => {
	const checkboxDOM = row.querySelector('td input[data-value-key]');
	const value = data[checkboxDOM.dataset.valueKey];
	checkboxDOM.value = value;
	checkboxDOM.checked = false;

	checkboxDOM.addEventListener('change', (event) => {
		const target = event.target;
		const value = event.target.value;

		if (target.checked && !selectedExtratoSales.includes(value)) {
			selectedExtratoSales.push(value);
		} else if (!target.checked && selectedExtratoSales.includes(value)) {
			selectedExtratoSales = [
				...selectedExtratoSales.filter((selected) => selected !== value),
			];
		}
		updateSelectedValue();
	});
	_defaultEvents.table.onRenderRow(row, data, tableRenderInstance);
});

tableRenderExtrato.onFilter(async (filters) => {
	const params = {
		por_pagina: document.querySelector('#js-por-pagina-extrato').value,
	};

	salesContainerExtrato.toggleActiveData('filter');
	if (Object.keys(filters).length === 0) {
		salesContainerExtrato.toggleActiveData('search');
		params.page = 1;
	}

	await buildRequestExtrato(params).get();
	updateTotals();
});

tableRenderExtrato.onSort(async (elementDOM, tableInstance) => {
	const params = {
		por_pagina: document.querySelector('#js-por-pagina-extrato').value,
	};

	_defaultEvents.table.onSort(elementDOM, tableInstance);
	await buildRequestExtrato(params).get();
});

async function onExtratoPerPageChanged(event) {
	salesContainerExtrato
		.get('search')
		.get('pagination')
		.setOptions({ perPage: event.target.value });
	salesContainerExtrato
		.get('filtered')
		.get('pagination')
		.setOptions({ perPage: event.target.value });
	await buildRequestExtrato({
		page: 1,
		por_pagina: event.target.value,
	}).get();
}

function exportar() {
	swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
	setTimeout(() => {
		openUrl(searchForm.get('form').dataset.urlExportar, {
			...searchForm.serialize(),
			...tableRenderExtrato.serializeTableFilters(),
			...serializeTableSortToExport(tableRenderExtrato.serializeSortFilter()),
		});
	}, 500);
}

function retornoCsv() {
	swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
	setTimeout(() => {
		openUrl(searchForm.get('form').dataset.urlRetornoCsv, {
			...searchForm.serialize(),
			...tableRenderExtrato.serializeTableFilters(),
			...serializeTableSortToExport(tableRenderExtrato.serializeSortFilter()),
		});
	}, 500);
}

function showTicket(id) {
	const sale = salesContainerExtrato
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
	if (selectedExtratoSales.length < 1) {
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
				id: selectedExtratoSales,
			}),
		})
		.then((json) => {
			if (json.status !== 'sucesso' && json.mensagem) {
				swal('Ooops...', json.mensagem, 'error');
				return;
			}

			const sales = salesContainerExtrato.get('data');

			const updatedSales = updateData(
				[...sales.get('sales')],
				[...json.vendas],
				'ID'
			).data;

			sales.set('sales', [...updatedSales]);

			selectedExtratoSales = [];

			tableRenderExtrato.set('data', {
				body: [...updatedSales] || [],
				footer: sales.get('totals') || {},
			});

			tableRenderExtrato.render();
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
	.querySelector('#js-por-pagina-extrato')
	.addEventListener('change', onExtratoPerPageChanged);

async function renderExtratoTable() {
	document.querySelector(
		'#js-extrato-table-title'
	).innerHTML = `Lançamentos do seu Extrato Bancário <span id="js-quantidade-registros-extrato"></span>`;

	await salesContainerExtrato.search({
		params: {
			por_pagina: document.querySelector('#js-por-pagina-extrato').value,
		},
		body: {
			filters: {},
		},
	});

	const total = salesContainerExtrato.get('data').get('pagination').options
		.total;

	document.querySelector(
		'#js-quantidade-registros-extrato'
	).innerHTML = `(${total} registros)`;

	tableRenderExtrato.clearFilters();
	tableRenderExtrato.clearSortFilter();
}

function updateSelectedValue() {
	if (selectedExtratoSales.length > 0) {
		let totalValue = 0;
		selectedExtratoSales.forEach((id) => {
			const sale = salesContainerExtrato
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
		// document.querySelector(
		// 	'#total-selecionado-extrato'
		// ).innerHTML = formattedValue;
	} else {
		clearSelectedValue();
	}
}

function setExtratoTotalValue() {
	if (salesContainerExtrato) {
	}
	const totalValue = parseFloat(
		salesContainerExtrato.get('data').get('totals').TOTAL_PREVISTO_OPERADORA
	);

	// const totalValueDOM = document.querySelector('#total-extrato');
	// totalValueDOM.innerHTML = tableRenderExtrato.formatCell(
	// 	totalValue,
	// 	'currency',
	// 	0
	// );
}

function clearSelectedValue() {
	selectedExtratoSales = [];
	// document.querySelector('#total-selecionado-extrato').innerHTML = 'R$ 0,00';
}

function updateTotals() {
	clearSelectedValue();
	setExtratoTotalValue();
}
