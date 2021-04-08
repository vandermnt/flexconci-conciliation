const checker = new Checker();
const modalFilter = new ModalFilter();
const formatter = new Formatter({
	locale: 'pt-BR',
	currencyOptions: {
		type: 'BRL',
	},
});
const searchForm = createSearchForm({
	form: '#js-form-pesquisa',
	inputs: ['_token', 'data_inicial', 'data_final'],
	checker,
});
const boxes = getBoxes();
boxes.forEach((box) => {
	const boxDOM = box.get('element');

	if (boxDOM.dataset.key === 'TOTAL_PENDENCIAS_OPERADORAS') {
		boxDOM.addEventListener('click', () => {
			window.scrollTo(0, document.querySelector('.vendas + .vendas').offsetTop);
		});

		return;
	}

	boxDOM.addEventListener('click', (event) => {
		toggleElementVisibility('#js-loader');
		const status = event.target.closest('.box').dataset.status;

		if (status === '*') {
			activeStatus = selectedStatus;
		} else {
			activeStatus = [status];
		}

		salesErpContainer.set('active', 'search');

		buildRequest('erp', { page: 1 }, { status_conciliacao: activeStatus })
			.get()
			.then(() => {
				updateUIOperadoras();
				tableRenderErp.clearFilters();
				toggleElementVisibility('#js-loader');
			});
	});
});

const apiConfig = {
	headers: {
		'X-CSRF-TOKEN': searchForm.getInput('_token').value,
		'Content-Type': 'application/json',
	},
};
const selectedSales = {
	erp: [],
	operadoras: [],
};
let selectedStatus = [];
let activeStatus = [];

const salesContainer = new SalesContainerProxy({
	id: 'vendas-operadoras',
	links: {
		search: searchForm.get('form').dataset.urlBuscarOperadoras,
		filter: searchForm.get('form').dataset.urlFiltrarOperadoras,
	},
});
const salesErpContainer = new SalesContainerProxy({
	id: 'vendas-erp',
	links: {
		search: searchForm.get('form').dataset.urlBuscarErp,
		filter: searchForm.get('form').dataset.urlFiltrarErp,
	},
});

const tableRender = createTableRender({
	table: '#js-tabela-operadoras',
	locale: 'pt-br',
	formatter,
});
const tableRenderErp = createTableRender({
	table: '#js-tabela-erp',
	locale: 'pt-br',
	formatter,
});

const _events = {
	salesContainer: {
		onFetch: (key, sales) => {
			document.querySelector(
				`#js-quantidade-registros-${key}`
			).textContent = `(${
				sales.get('pagination').options.total || 0
			} registros)`;
			const currentTableRender = key === 'erp' ? tableRenderErp : tableRender;
			currentTableRender.set('data', {
				body: sales.get('sales') || [],
				footer: sales.get('totals') || {},
			});
			currentTableRender.render();
			sales.get('pagination').render();
		},
	},
	table: {
		onFilter: async (key, filters) => {
			toggleElementVisibility('#js-loader');
			const currentSalesContainer =
				key === 'erp' ? salesErpContainer : salesContainer;

			const params = {
				por_pagina: document.querySelector(`#js-por-pagina-${key}`).value,
			};

			currentSalesContainer.toggleActiveData('filter');
			if (Object.keys(filters).length === 0) {
				currentSalesContainer.toggleActiveData('search');
				params.page = 1;
			}

			await buildRequest(key, params).get();

			toggleElementVisibility('#js-loader');
		},
		shouldSelectRow: (elementDOM) => {
			let shouldSelect = _defaultEvents.table.shouldSelectRow(elementDOM);
			if (['i', 'input'].includes(elementDOM.tagName.toLowerCase())) {
				shouldSelect = false;
			} else {
				shouldSelect = true;
			}

			return shouldSelect;
		},
		onRenderRow: (key, row, data, tableRenderInstance) => {
			const checkboxDOM = row.querySelector('td input[data-value-key]');
			const value = data[checkboxDOM.dataset.valueKey];
			checkboxDOM.value = value;
			checkboxDOM.checked = selectedSales[key].includes(value);

			checkboxDOM.addEventListener('change', (event) => {
				const target = event.target;
				const value = event.target.value;

				if (target.checked && !selectedSales[key].includes(value)) {
					selectedSales[key].push(value);
				} else if (!target.checked && selectedSales[key].includes(value)) {
					selectedSales[key] = [
						...selectedSales[key].filter((selected) => selected !== value),
					];
				}
			});
			_defaultEvents.table.onRenderRow(row, data, tableRenderInstance);
		},
		onSort: async (key, elementDOM, tableInstance) => {
			toggleElementVisibility('#js-loader');

			const params = {
				por_pagina: document.querySelector(`#js-por-pagina-${key}`).value,
			};

			_defaultEvents.table.onSort(elementDOM, tableInstance);
			await buildRequest(key, params).get();

			toggleElementVisibility('#js-loader');
		},
		onRenderCell: async (cell, data, tableInstance) => {
			_defaultEvents.table.onRenderCell(cell, data);
			const columnName = cell.dataset.column;

			if (columnName !== 'DIFERENCA_LIQUIDO') return;

			const diffValue = Number(data[columnName]) || 0;

			if (diffValue < 0) {
				cell.classList.add('text-danger');
			} else if (diffValue > 0) {
				cell.classList.add('text-primary');
			}
		},
	},
};

checker.addGroups([
	{ name: 'empresa', options: { inputName: 'grupos_clientes' } },
	{ name: 'status-conciliacao', options: { inputName: 'status_conciliacao' } },
]);

modalFilter.addGroups(['empresa']);

function buildRequest(key = 'erp', params, body = {}) {
	let requestHandler = () => {};

	const currentSalesContainer =
		key === 'erp' ? salesErpContainer : salesContainer;
	const currentTableRender = key === 'erp' ? tableRenderErp : tableRender;
	const isSearchActive = currentSalesContainer.get('active') === 'search';
	const sendRequest = isSearchActive
		? currentSalesContainer.search.bind(currentSalesContainer)
		: currentSalesContainer.filter.bind(currentSalesContainer);

	const filters = {
		...searchForm.serialize(),
		...currentTableRender.serializeSortFilter(),
		status_conciliacao: activeStatus,
	};
	const bodyPayload = isSearchActive
		? { ...filters, ...body }
		: {
				filters: { ...filters },
				subfilters: { ...currentTableRender.serializeTableFilters() },
		  };

	const requestPayload = {
		params: {},
		body: bodyPayload,
	};

	requestHandler = async (params) => {
		requestPayload.params = {
			por_pagina: currentSalesContainer.get('search').get('pagination').options
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

function getPaginationConfig(key) {
	return {
		paginationContainer: document.querySelector(`#js-paginacao-${key}`),
		navigationHandler: async (page, pagination, event) => {
			toggleElementVisibility('#js-loader');

			await buildRequest(
				key,
				{
					page,
					por_pagina: pagination.options.perPage,
				},
				{ status_conciliacao: [...activeStatus] }
			).get();

			toggleElementVisibility('#js-loader');
		},
	};
}

searchForm.onSubmit(async (event) => {
	const statusNaoConciliado = document.querySelector(
		'.box[data-key="TOTAL_PENDENCIAS_OPERADORAS"]'
	).dataset.status;
	const resultadosDOM = document.querySelector('.resultados');
	toggleElementVisibility('#js-loader');

	selectedStatus = activeStatus = checker.getCheckedValues(
		'status-conciliacao'
	);
	const responses = await Promise.all([
		await salesContainer.search({
			params: {
				por_pagina: document.querySelector('#js-por-pagina-operadoras').value,
			},
			body: {
				...searchForm.serialize(),
				status_conciliacao: [statusNaoConciliado],
			},
		}),
		await salesErpContainer.search({
			params: {
				por_pagina: document.querySelector('#js-por-pagina-erp').value,
			},
			body: { ...searchForm.serialize() },
		}),
	]);

	tableRender.clearFilters();
	tableRenderErp.clearFilters();
	tableRender.clearSortFilter();
	tableRenderErp.clearSortFilter();

	if (resultadosDOM.classList.contains('hidden')) {
		resultadosDOM.classList.remove('hidden');
	}
	updateUIOperadoras();
	window.scrollTo(0, document.querySelector('.resultados').offsetTop);

	toggleElementVisibility('#js-loader');
});

salesContainer.setupApi(apiConfig);
salesContainer.setPaginationConfig(
	{
		paginationContainer: getPaginationConfig('operadoras').paginationContainer,
	},
	getPaginationConfig('operadoras').navigationHandler
);
salesErpContainer.setupApi(apiConfig);
salesErpContainer.setPaginationConfig(
	{ paginationContainer: getPaginationConfig('erp').paginationContainer },
	getPaginationConfig('erp').navigationHandler
);

salesContainer.onEvent('fetch', (sales) =>
	_events.salesContainer.onFetch('operadoras', sales)
);
salesErpContainer.onEvent('fetch', (sales) =>
	_events.salesContainer.onFetch('erp', sales)
);

salesContainer.onEvent('search', (sales) => {
	const totals = sales.get('totals');
	updateBoxes(boxes, {
		TOTAL_PENDENCIAS_OPERADORAS: totals.TOTAL_PENDENCIAS_OPERADORAS,
	});
});
salesErpContainer.onEvent('search', (sales) => {
	const totals = sales.get('totals');
	let boxTotal;

	updateBoxes(boxes, {
		...totals,
	});

	boxes.forEach((box) => {
		const boxStatus = box.get('element').dataset.status;
		if (boxStatus === '*') {
			boxTotal = box;
			return;
		}

		if (!selectedStatus.includes(boxStatus)) {
			box.set('value', 0);
			box.render();
		}
	});
	const total = boxes.reduce((sum, currentBox) => {
		const key = currentBox.get('element').dataset.key;

		if (!['TOTAL_BRUTO', 'TOTAL_PENDENCIAS_OPERADORAS'].includes(key)) {
			const boxValue = Number(currentBox.get('value')) || 0;
			sum = sum + boxValue;
		}

		return sum;
	}, 0);
	boxTotal.set('value', total);
	boxTotal.render();
});

tableRenderErp.onRenderCell(
	async (cell, data) =>
		await _events.table.onRenderCell(cell, data, tableRenderErp)
);

tableRenderErp.onFilter(
	async (filters) => await _events.table.onFilter('erp', filters)
);
tableRender.onFilter(async (filters) => {
	const statusNaoConciliada = document.querySelector(
		'.box[data-key="TOTAL_PENDENCIAS_OPERADORAS"]'
	).dataset.status;

	if (!activeStatus.includes(statusNaoConciliada)) return;

	await _events.table.onFilter('operadoras', filters);
});
tableRenderErp.onSort(
	async (elementDOM, tableInstance) =>
		await _events.table.onSort('erp', elementDOM, tableInstance)
);
tableRender.onSort(
	async (elementDOM, tableInstance) =>
		await _events.table.onSort('operadoras', elementDOM, tableInstance)
);

tableRender.onRenderRow((row, data, instance) =>
	_events.table.onRenderRow('operadoras', row, data, instance)
);
tableRenderErp.onRenderRow((row, data, instance) =>
	_events.table.onRenderRow('erp', row, data, instance)
);

tableRender.shouldSelectRow(_events.table.shouldSelectRow);
tableRenderErp.shouldSelectRow(_events.table.shouldSelectRow);

function findBoxStatusConc(key = '') {
	const box = document.querySelector(`.box[data-key=${key}]`);

	return box || null;
}

async function onPerPageChanged(key = 'erp', event) {
	const currentSalesContainer =
		key === 'erp' ? salesErpContainer : salesContainer;
	currentSalesContainer
		.get('search')
		.get('pagination')
		.setOptions({ perPage: event.target.value });
	currentSalesContainer
		.get('filtered')
		.get('pagination')
		.setOptions({ perPage: event.target.value });

	toggleElementVisibility('#js-loader');

	await buildRequest(
		key,
		{
			page: 1,
			por_pagina: event.target.value,
		},
		{ status_conciliacao: [...activeStatus] }
	).get();

	updateUIOperadoras();
	toggleElementVisibility('#js-loader');
}

function mockPagination() {
	return new Pagination([], {
		paginationContainer: document.querySelector('#js-paginacao-operadoras'),
		total: 0,
		currentPage: 1,
		lastPage: 1,
		perPage: 5,
	});
}

function updateUIOperadoras() {
	const statusNaoConciliada = document.querySelector(
		'.box[data-key="TOTAL_PENDENCIAS_OPERADORAS"]'
	).dataset.status;
	const isNaoConciliada = selectedStatus.includes(statusNaoConciliada);
	const boxOperadorasTotal = isNaoConciliada
		? salesContainer.get('search').get('totals').TOTAL_PENDENCIAS_OPERADORAS
		: 0;
	const totalRegisters = isNaoConciliada
		? salesContainer.get('data').get('pagination').options.total || 0
		: 0;
	const paginationFake = mockPagination();
	const tableRenderFake = createTableRender({
		table: '#js-tabela-operadoras',
		locale: 'pt-br',
		formatter,
	});
	const pagination = isNaoConciliada
		? salesContainer.get('data').get('pagination')
		: paginationFake;
	const currentTableRender = isNaoConciliada ? tableRender : tableRenderFake;

	pagination.render();
	currentTableRender.render();
	updateBoxes(boxes, {
		TOTAL_PENDENCIAS_OPERADORAS: boxOperadorasTotal,
	});
	document.querySelector(
		`#js-quantidade-registros-operadoras`
	).textContent = `(${totalRegisters} registros)`;
}

function confirmConciliacao() {
	if (selectedSales.erp.length !== 1 || selectedSales.operadoras.length !== 1) {
		swal(
			'Ooops...',
			'Selecione apenas uma venda ERP e uma operadora para realizar a conciliação.',
			'error'
		);
		return;
	}

	openConfirmDialog(
		'Tem certeza que deseja realizar a conciliação?',
		(value) => {
			if (value) conciliar();
		}
	);
}

function conciliar() {
	const statusManual = findBoxStatusConc('TOTAL_CONCILIADO_MANUAL').dataset
		.status;
	const statusNaoConciliado = findBoxStatusConc('TOTAL_NAO_CONCILIADO').dataset
		.status;

	toggleElementVisibility('#js-loader');
	const baseUrl = searchForm.get('form').dataset.urlConciliarManualmente;
	api
		.post(baseUrl, {
			...apiConfig,
			body: JSON.stringify({
				_token: searchForm.getInput('_token').value,
				id_erp: selectedSales.erp,
				id_operadora: selectedSales.operadoras,
			}),
		})
		.then((json) => {
			toggleElementVisibility('#js-loader');
			if (json.status === 'erro' && json.mensagem) {
				swal('Ooops...', json.mensagem, 'error');
				return;
			}
			selectedSales.erp = [];
			selectedSales.operadoras = [];

			const salesErp = salesErpContainer.get('data');
			const sales = salesContainer.get('data');

			const updatedSalesErp = updateData(
				[...salesErp.get('sales')],
				{
					...json.erp,
					ID_ERP: json.erp.ID,
					STATUS_CONCILIACAO: json.STATUS_CONCILIACAO,
					STATUS_CONCILIACAO_IMAGEM: json.STATUS_CONCILIACAO_IMAGEM,
				},
				'ID_ERP'
			).data;

			const updatedSales = removeFromData(
				[...sales.get('sales')],
				json.operadora.ID,
				'ID'
			).data;

			const totalsOperadoras = updateTotals(
				salesContainer.get('search').get('totals'),
				{
					TOTAL_BRUTO: json.operadora.TOTAL_BRUTO * -1,
					TOTAL_LIQUIDO: json.operadora.TOTAL_LIQUIDO * -1,
					TOTAL_TAXA: json.operadora.TOTAL_TAXA * -1,
					TOTAL_PENDENCIAS_OPERADORAS: json.operadora.TOTAL_BRUTO * -1,
				}
			);

			const totalsErp = updateTotals(
				salesErpContainer.get('search').get('totals'),
				{
					TOTAL_CONCILIADO_MANUAL: json.erp.TOTAL_BRUTO,
					TOTAL_NAO_CONCILIADO: json.erp.TOTAL_BRUTO * -1,
				}
			);

			sales.set('sales', [...updatedSales]);
			sales.set('totals', { ...totalsOperadoras });
			salesErp.set('sales', [...updatedSalesErp]);
			salesErp.set('totals', { ...totalsErp });

			sales.get('pagination').setOptions({
				total: sales.get('pagination').options.total - 1,
			});
			document.querySelector(
				`#js-quantidade-registros-operadoras`
			).textContent = `(${
				sales.get('pagination').options.total || 0
			} registros)`;
			tableRenderErp.set('data', {
				body: salesErp.get('sales'),
				footer: totalsErp,
			});
			tableRenderErp.render();
			tableRender.set('data', {
				body: sales.get('sales'),
				footer: totalsOperadoras,
			});
			tableRender.render();

			updateBoxes(boxes, {
				TOTAL_NAO_CONCILIADO: selectedStatus.includes(statusNaoConciliado)
					? totalsErp.TOTAL_NAO_CONCILIADO
					: 0,
				TOTAL_CONCILIADO_MANUAL: selectedStatus.includes(statusManual)
					? totalsErp.TOTAL_CONCILIADO_MANUAL
					: 0,
				TOTAL_PENDENCIAS_OPERADORAS: selectedStatus.includes(
					statusNaoConciliado
				)
					? totalsOperadoras.TOTAL_PENDENCIAS_OPERADORAS
					: 0,
			});

			swal('Conciliação realizada!', json.mensagem, 'success');
		});
}

function confirmDesconciliacao() {
	if (selectedSales.erp.length !== 1) {
		swal(
			'Ooops...',
			'Para realizar a desconciliação selecione apenas uma venda ERP.',
			'error'
		);
		return;
	}

	openConfirmDialog('Tem certeza que deseja desconciliar a venda?', (value) => {
		if (value) desconciliar();
	});
}

function desconciliar() {
	const statusManual = findBoxStatusConc('TOTAL_CONCILIADO_MANUAL').dataset
		.status;
	const statusNaoConciliado = findBoxStatusConc('TOTAL_NAO_CONCILIADO').dataset
		.status;

	toggleElementVisibility('#js-loader');
	const baseUrl = searchForm.get('form').dataset.urlDesconciliarManualmente;
	api
		.post(baseUrl, {
			...apiConfig,
			body: JSON.stringify({
				_token: searchForm.getInput('_token').value,
				id_erp: selectedSales.erp,
			}),
		})
		.then((json) => {
			toggleElementVisibility('#js-loader');
			if (json.status === 'erro' && json.mensagem) {
				swal('Ooops...', json.mensagem, 'error');
				return;
			}
			selectedSales.erp = [];
			selectedSales.operadoras = [];

			const salesErp = salesErpContainer.get('data');
			const sales = salesContainer.get('data');

			const updatedSalesErp = updateData(
				[...salesErp.get('sales')],
				{
					...json.erp,
					ID_ERP: json.erp.ID,
					STATUS_CONCILIACAO: json.STATUS_CONCILIACAO,
					STATUS_CONCILIACAO_IMAGEM: json.STATUS_CONCILIACAO_IMAGEM,
				},
				'ID_ERP'
			).data;

			const totalsOperadoras = updateTotals(
				salesContainer.get('search').get('totals'),
				{
					TOTAL_BRUTO: json.operadora.TOTAL_BRUTO,
					TOTAL_LIQUIDO: json.operadora.TOTAL_LIQUIDO,
					TOTAL_TAXA: json.operadora.TOTAL_TAXA,
					TOTAL_PENDENCIAS_OPERADORAS: json.operadora.TOTAL_BRUTO,
				}
			);

			const totalsErp = updateTotals(
				salesErpContainer.get('search').get('totals'),
				{
					TOTAL_CONCILIADO_MANUAL: selectedStatus.includes(statusManual)
						? json.erp.TOTAL_BRUTO * -1
						: 0,
					TOTAL_NAO_CONCILIADO: selectedStatus.includes(statusNaoConciliado)
						? json.erp.TOTAL_BRUTO
						: 0,
				}
			);

			sales.set('totals', { ...totalsOperadoras });
			salesErp.set('sales', [...updatedSalesErp]);
			salesErp.set('totals', { ...totalsErp });

			sales.get('pagination').setOptions({
				total: sales.get('pagination').options.total + 1,
			});
			document.querySelector(
				`#js-quantidade-registros-operadoras`
			).textContent = `(${
				sales.get('pagination').options.total || 0
			} registros)`;
			tableRenderErp.set('data', {
				body: salesErp.get('sales'),
				footer: totalsErp,
			});
			tableRenderErp.render();
			tableRender.set('data', {
				body: sales.get('sales'),
				footer: totalsOperadoras,
			});
			tableRender.render();

			updateBoxes(boxes, {
				TOTAL_NAO_CONCILIADO: selectedStatus.includes(statusNaoConciliado)
					? totalsErp.TOTAL_NAO_CONCILIADO
					: 0,
				TOTAL_CONCILIADO_MANUAL: selectedStatus.includes(statusManual)
					? totalsErp.TOTAL_CONCILIADO_MANUAL
					: 0,
				TOTAL_PENDENCIAS_OPERADORAS: selectedStatus.includes(
					statusNaoConciliado
				)
					? totalsOperadoras.TOTAL_PENDENCIAS_OPERADORAS
					: 0,
			});

			swal('Desconciliação realizada!', json.mensagem, 'success');
		});
}

function openJustifyModal(event) {
	const buttonDOM = event.target;
	const isErp = buttonDOM.dataset.type === 'erp';

	if (isErp && selectedSales.erp.length < 1) {
		swal('Ooops...', 'Selecione ao menos uma venda ERP.', 'error');
		return;
	} else if (!isErp && selectedSales.operadoras.length < 1) {
		swal('Ooops...', 'Selecione ao menos uma venda operadora.', 'error');
		return;
	}

	const justifyButtonDOM = recreateNode('#js-justificar-modal #js-justificar');
	if (justifyButtonDOM) {
		justifyButtonDOM.addEventListener(
			'click',
			isErp ? justifyErp : justifyOperadora
		);
	}

	$('#js-justificar-modal').modal('show');
}

function closeJustifyModal() {
	const justifyButtonDOM = recreateNode('#js-justificar-modal #js-justificar');
	if (justifyButtonDOM) {
		justifyButtonDOM.addEventListener('click', (e) =>
			$('#js-justificar-modal').modal('hide')
		);
	}

	document.querySelector('select[name="justificativa"]').value = '';
	$('#js-justificar-modal').modal('hide');
}

function justifyErp() {
	const statusJustificado = findBoxStatusConc('TOTAL_JUSTIFICADO').dataset
		.status;
	const statusNaoConciliado = findBoxStatusConc('TOTAL_NAO_CONCILIADO').dataset
		.status;

	const baseUrl = searchForm.get('form').dataset.urlJustificarErp;
	const justificativaDOM = document.querySelector(
		'select[name="justificativa"]'
	);
	const justificativa = justificativaDOM.value;
	toggleElementVisibility('#js-loader');
	api
		.post(baseUrl, {
			...apiConfig,
			body: JSON.stringify({
				id: selectedSales.erp,
				justificativa,
			}),
		})
		.then((json) => {
			if (json.status !== 'sucesso' && json.mensagem) {
				swal('Ooops...', json.mensagem, 'error');
				return;
			}

			const salesErp = salesErpContainer.get('data');

			const updatedSalesErp = updateData(
				[...salesErp.get('sales')],
				[...json.vendas],
				'ID_ERP'
			).data;
			const totalsErp = updateTotals(
				{ ...salesErpContainer.get('search').get('totals') },
				{
					TOTAL_JUSTIFICADO: json.totais.TOTAL_BRUTO,
					TOTAL_NAO_CONCILIADO: json.totais.TOTAL_BRUTO * -1,
				}
			);

			salesErp.set('sales', [...updatedSalesErp]);
			salesErp.set('totals', { ...totalsErp });

			selectedSales.erp = [];

			updateBoxes(boxes, {
				TOTAL_JUSTIFICADO: selectedStatus.includes(statusJustificado)
					? totalsErp.TOTAL_JUSTIFICADO
					: 0,
				TOTAL_NAO_CONCILIADO: selectedStatus.includes(statusNaoConciliado)
					? totalsErp.TOTAL_NAO_CONCILIADO
					: 0,
			});

			tableRenderErp.set('data', {
				body: [...updatedSalesErp] || [],
				footer: { ...totalsErp } || {},
			});

			tableRenderErp.render();
			swal('Justificativa realizada!', json.mensagem, 'success');
		})
		.finally(() => {
			justificativaDOM.value = '';
			toggleElementVisibility('#js-loader');
			closeJustifyModal();
		});
}

function justifyOperadora() {
	const baseUrl = searchForm.get('form').dataset.urlJustificarOperadoras;
	const justificativaDOM = document.querySelector(
		'select[name="justificativa"]'
	);
	const justificativa = justificativaDOM.value;

	toggleElementVisibility('#js-loader');
	api
		.post(baseUrl, {
			...apiConfig,
			body: JSON.stringify({
				id: selectedSales.operadoras,
				justificativa,
			}),
		})
		.then((json) => {
			if (json.status !== 'sucesso' && json.mensagem) {
				swal('Ooops...', json.mensagem, 'error');
				return;
			}

			const sales = salesContainer.get('data');
			const ids = json.vendas.reduce(
				(values, venda) => [...values, venda.ID],
				[]
			);

			const updatedSales = removeFromData([...sales.get('sales')], ids, 'ID')
				.data;
			const totals = updateTotals(
				{ ...salesContainer.get('search').get('totals') },
				{
					TOTAL_BRUTO: json.totais.TOTAL_BRUTO * -1,
					TOTAL_LIQUIDO: json.totais.TOTAL_LIQUIDO * -1,
					TOTAL_TAXA: json.totais.TOTAL_TAXA * -1,
					TOTAL_PENDENCIAS_OPERADORAS: json.totais.TOTAL_BRUTO * -1,
				}
			);
			const totalRegister = updateTotals(
				{
					total: sales.get('pagination').options.total,
				},
				{
					total: json.vendas.length * -1,
				}
			).total;

			sales.get('pagination').setOptions({
				total: totalRegister,
			});

			selectedSales.operadoras = [];

			document.querySelector(
				`#js-quantidade-registros-operadoras`
			).textContent = `(${totalRegister || 0} registros)`;

			sales.set('sales', [...updatedSales]);
			sales.set('totals', { ...totals });

			updateBoxes(boxes, {
				TOTAL_PENDENCIAS_OPERADORAS: totals.TOTAL_PENDENCIAS_OPERADORAS,
			});

			console.log(boxes);

			tableRender.set('data', {
				body: [...updatedSales] || [],
				footer: { ...totals } || {},
			});
			tableRender.render();

			swal('Justificativa realizada!', json.mensagem, 'success');
		})
		.finally(() => {
			justificativaDOM.value = '';
			toggleElementVisibility('#js-loader');
			closeJustifyModal();
		});
}

function confirmUnjustify() {
	if (selectedSales.erp.length < 1) {
		swal('Ooops...', 'Selecione ao menos uma venda ERP.', 'error');
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
	const statusJustificado = findBoxStatusConc('TOTAL_JUSTIFICADO').dataset
		.status;
	const statusNaoConciliado = findBoxStatusConc('TOTAL_NAO_CONCILIADO').dataset
		.status;

	const baseUrl = searchForm.get('form').dataset.urlDesjustificarErp;
	toggleElementVisibility('#js-loader');
	api
		.post(baseUrl, {
			...apiConfig,
			body: JSON.stringify({
				id: selectedSales.erp,
			}),
		})
		.then((json) => {
			if (json.status !== 'sucesso' && json.mensagem) {
				swal('Ooops...', json.mensagem, 'error');
				return;
			}

			const salesErp = salesErpContainer.get('data');

			const updatedSalesErp = updateData(
				[...salesErp.get('sales')],
				[...json.vendas],
				'ID_ERP'
			).data;
			const totalsErp = updateTotals(
				{ ...salesErpContainer.get('search').get('totals') },
				{
					TOTAL_JUSTIFICADO: json.totais.TOTAL_BRUTO * -1,
					TOTAL_NAO_CONCILIADO: json.totais.TOTAL_BRUTO,
				}
			);

			salesErp.set('sales', [...updatedSalesErp]);
			salesErp.set('totals', { ...totalsErp });

			selectedSales.erp = [];

			updateBoxes(boxes, {
				TOTAL_JUSTIFICADO: selectedStatus.includes(statusJustificado)
					? totalsErp.TOTAL_JUSTIFICADO
					: 0,
				TOTAL_NAO_CONCILIADO: selectedStatus.includes(statusNaoConciliado)
					? totalsErp.TOTAL_NAO_CONCILIADO
					: 0,
			});

			tableRenderErp.set('data', {
				body: [...updatedSalesErp] || [],
				footer: { ...totalsErp } || {},
			});

			tableRenderErp.render();
			swal('Justificativa desfeita!', json.mensagem, 'success');
		})
		.finally(() => {
			toggleElementVisibility('#js-loader');
			closeJustifyModal();
		});
}

function exportar(event) {
	const isErp = event.target.dataset.type === 'erp';
	const baseUrl = searchForm.get('form').dataset[
		isErp ? 'urlExportarErp' : 'urlExportarOperadoras'
	];
	const currentTableRender = isErp ? tableRenderErp : tableRender;

	swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
	setTimeout(() => {
		openUrl(baseUrl, {
			...{ ...searchForm.serialize(), status_conciliacao: [...activeStatus] },
			...currentTableRender.serializeTableFilters(),
			...serializeTableSortToExport(currentTableRender.serializeSortFilter()),
		});
	}, 500);
}

function updateSales(sales, newSales, idKey = 'ID') {
	const updated = updateData([...sales], [...newSales], idKey);
	const updatedSales = updated.data;
	const affectedRows = updated.updated.reduce(
		(ids, value) => [...ids, value.ID_ERP],
		[]
	);

	salesErpContainer.get('data').set('sales', [...updatedSales]);

	tableRenderErp.set('selectedRows', affectedRows);
	tableRenderErp.set('data', {
		body: [...updatedSales] || [],
		footer: { ...salesErpContainer.get('data').get('totals') } || {},
	});

	tableRenderErp.render();
}

function closeRetornoErpModal() {
	document.querySelector(
		'#js-retorno-erp-modal #js-data-inicial'
	).valueAsDate = new Date();
	document.querySelector(
		'#js-retorno-erp-modal #js-data-final'
	).valueAsDate = new Date();

	$('#js-retorno-erp-modal').modal('hide');
}

function retornoErp() {
	const dataInicial = document.querySelector(
		'#js-retorno-erp-modal #js-data-inicial'
	).value;
	const dataFinal = document.querySelector(
		'#js-retorno-erp-modal #js-data-final'
	).value;

	if (!dataInicial || !dataFinal) {
		swal('Ooops...', 'A data inicial e final devem ser informadas!', 'error');
	}

	swal(
		'Aguarde um momento...',
		'O processo pode levar alguns segundos.',
		'warning'
	);
	toggleElementVisibility('#js-loader');
	api
		.get(searchForm.get('form').dataset.urlRetornoErp, {
			params: {
				'data-inicial': dataInicial,
				'data-final': dataFinal,
			},
		})
		.then((res) => {
			if (res.status === 'erro' && res.mensagem) {
				swal('Ooops...', res.mensagem, 'error');
				return;
			}

			const updatedSales = res.vendas.reduce((values, id) => {
				return [
					...values,
					{
						ID_ERP: id,
						RETORNO_ERP: 'Sim',
					},
				];
			}, []);

			updateSales(
				salesErpContainer.get('data').get('sales'),
				[...updatedSales],
				'ID_ERP'
			);

			swal(
				'Correção ERP realizado!',
				`${res.vendas.length} de ${res.total} registros atualizados!`,
				'success'
			);
		})
		.catch((err) => {
			swal(
				'Ooops...',
				'Um erro inesperado ocorreu. Tente novamente mais tarde!',
				'error'
			);
		})
		.finally(() => {
			toggleElementVisibility('#js-loader');
		});

	closeRetornoErpModal();
}

searchForm
	.get('form')
	.querySelector('button[data-form-action="submit"')
	.addEventListener('click', searchForm.get('onSubmitHandler'));

document
	.querySelector('#js-por-pagina-erp')
	.addEventListener('change', (event) => onPerPageChanged('erp', event));
document
	.querySelector('#js-por-pagina-operadoras')
	.addEventListener('change', (event) => onPerPageChanged('operadoras', event));

document
	.querySelector('#js-conciliar')
	.addEventListener('click', confirmConciliacao);
document
	.querySelector('#js-desconciliar')
	.addEventListener('click', confirmDesconciliacao);
document
	.querySelector('#js-justificar-erp')
	.addEventListener('click', openJustifyModal);
document
	.querySelector('#js-justificar-operadora')
	.addEventListener('click', openJustifyModal);
document
	.querySelector('#js-desjustificar-erp')
	.addEventListener('click', confirmUnjustify);
document.querySelector('#js-exportar-erp').addEventListener('click', exportar);
document
	.querySelector('#js-exportar-operadoras')
	.addEventListener('click', exportar);
document.querySelector('#js-retorno-erp').addEventListener('click', retornoErp);
document
	.querySelector('#js-abrir-modal-retorno-erp')
	.addEventListener('click', () => $('#js-retorno-erp-modal').modal('show'));
document
	.querySelector('#js-cancelar-retorno-erp')
	.addEventListener('click', closeRetornoErpModal);
document
	.querySelector('#js-retorno-erp-modal *[data-dismiss]')
	.addEventListener('click', closeRetornoErpModal);

Array.from(
	document.querySelectorAll('#js-justificar-modal *[data-dismiss="modal"]')
).forEach((element) => {
	element.addEventListener('click', closeJustifyModal);
});

document
	.querySelector('#dropdownUserSettings')
	.addEventListener('click', (e) => {
		$('#dropdownUserSettings').dropdown('toggle');
	});
document.querySelector('#dropdownCadastros').addEventListener('click', (e) => {
	$('#dropdownCadastros').dropdown('toggle');
});
document
	.querySelector('#dropdownAdministrativo')
	.addEventListener('click', (e) => {
		$('#dropdownAdministrativo').dropdown('toggle');
	});

[
	'VALOR_TAXA',
	'PERCENTUAL_TAXA',
	'TAXA',
	'TAXA_OPERADORA',
	'TAXA_DIFERENCA',
].forEach((column) => {
	const tdErp = document.querySelector(
		`#js-tabela-erp td[data-column=${column}]`
	);
	const tdOperadora = document.querySelector(
		`#js-tabela-operadoras td[data-column=${column}]`
	);

	if (tdErp) {
		tdErp.classList.remove('text-danger');
	}

	if (tdOperadora) {
		tdOperadora.classList.remove('text-danger');
	}
});

document
	.querySelector('#js-tabela-erp tfoot td[data-column="TOTAL_TAXA"]')
	.classList.remove('text-danger');
document
	.querySelector('#js-tabela-operadoras tfoot td[data-column="TOTAL_TAXA"]')
	.classList.remove('text-danger');
