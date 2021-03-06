function TableRender(options = {}) {
	this.proxy = new Proxy(
		{
      originalTable: document.querySelector(options.table).cloneNode(true),
			table: options.table || '#js-table',
			data: options.data || { body: [], footer: {} },
			formatter:
				options.formatter ||
				new Formatter({
					locale: options.locale || 'en-US',
					currencyOptions: {
						type: 'USD',
					},
				}),
			sort: {
				by: null,
				order: null /** Accepts: '', 'asc' or 'desc' */,
			},
			selectedRows: [],
			onRender: null,
			onRenderRow: () => {},
			onRenderCell: () => {},
      afterRender: null,
      afterReset: () => {},
      beforeReset: () => {},
			shouldSelectRow: () => true,
			onSelectRow: () => {},
			onFilter: () => {},
			onSort: () => {},
		},
		tableRenderHandler()
	);
}

TableRender.prototype.get = function (prop = null) {
	if (!prop) {
		return this.proxy;
	}

	return this.proxy[prop];
};

TableRender.prototype.set = function (prop = '', value = null) {
	this.proxy[prop] = value;
};

TableRender.prototype.recreateNode = function (element = '') {
	const elementDOM =
		typeof element === 'string' ? document.querySelector(element) : element;
	if (!elementDOM) return;

	const elementCloneDOM = elementDOM.cloneNode(true);
	elementDOM.parentNode.replaceChild(elementCloneDOM, elementDOM);
	return elementCloneDOM;
};

TableRender.prototype.formatCell = function (
	value,
	type = 'text',
	defaultValue = ''
) {
	const formatter = this.get('formatter');
	const formatedValue = formatter.format(type, value, defaultValue);

	return formatedValue;
};

TableRender.prototype.onRender = function (handler = (instance = this) => {}) {
	this.set('onRender', handler);
};

TableRender.prototype.onRenderRow = function (handler = (row = null) => {}) {
	this.set('onRenderRow', handler);
};

TableRender.prototype.onRenderCell = function (
	handler = (element = null, data = {}) => {}
) {
	this.set('onRenderCell', handler);
};

TableRender.prototype.afterRender = function (handler = (instance = this) => {}) {
	this.set('afterRender', handler);
};

TableRender.prototype.beforeReset = function(handler = (instance = this) => {}) {
  this.set('beforeReset', handler);
}

TableRender.prototype.afterReset = function(handler = (instance = this) => {}) {
  this.set('afterReset', handler);
}

TableRender.prototype.shouldSelectRow = function (handler = (element) => true) {
	this.set('shouldSelectRow', handler);
};

TableRender.prototype.onSelectRow = function (handler = (element) => {}) {
	this.set('onSelectRow', handler);
};

TableRender.prototype.onFilter = function (handler = (filters = {}) => {}) {
	this.set('onFilter', handler);
	this.addTableFiltersListener();
};

TableRender.prototype.onSort = function (handler = (filters = {}) => {}) {
	this.set('onSort', handler);
};

TableRender.prototype.addTableFiltersListener = function () {
	const table = this.get('table');
	const tableInputs = Array.from(table.querySelectorAll('thead input[name]'));
	const onFilter = this.get('onFilter');

	tableInputs.forEach((input) => {
		input.addEventListener('keyup', (event) => {
			if (event.key === 'Enter') {
				if (onFilter && typeof onFilter === 'function') {
					onFilter({ ...this.serializeTableFilters() });
				}
			}
		});
	});
};

TableRender.prototype.serializeTableFilters = function () {
	const table = this.get('table');
	const tableInputs = Array.from(table.querySelectorAll('thead input[name]'));

	const filters = tableInputs.reduce((data, input) => {
		const value = input.value.trim();

		if (value) {
			data[input.name] = value;
		}

		return data;
	}, {});

	return { ...filters };
};

TableRender.prototype.serializeSortFilter = function () {
	if (!this.get('sort').by) return {};

	return {
		sort: {
			column: this.get('sort').by,
			direction: this.get('sort').order,
		},
	};
};

TableRender.prototype.clearFilters = function () {
	const table = this.get('table');
	const tableInputs = Array.from(table.querySelectorAll('thead input[name]'));

	tableInputs.forEach((inputDOM) => {
		inputDOM.value = '';
	});
};

TableRender.prototype.clearSortFilter = function () {
	const activeSortColumn = this.get('sort').by;
	if (!activeSortColumn) {
		return;
	}

	const selector = `.table-sorter[data-tbsort-by="${activeSortColumn}"] .table-sort-icon`;
	const activeSortIcon = this.get('table').querySelector(selector);
	activeSortIcon.dataset.sortOrder = 'none';

	this.set('sort', {
		by: null,
		order: null,
	});
};

TableRender.prototype.refreshTableFilters = function (tableFilters = {}) {
  const table = this.get('table');

  if(!table) return false;

  Object.keys(tableFilters).forEach(filterKey => {
    const input = table.querySelector(`input[name="${filterKey}"]`);
    input.value = tableFilters[filterKey];
  });

  return true;
}

TableRender.prototype.refreshSortFilter = function (sortFilter) {
  const table = this.get('table');

  if(!sortFilter.sort) return false;

  const selector = `.table-sorter[data-tbsort-by="${sortFilter.sort.column}"] .table-sort-icon`;
	const sortIcon = table.querySelector(selector);
  sortIcon.dataset.sortOrder = sortFilter.sort.direction;

  return true;
}

TableRender.prototype.resetTable = function () {
  const afterResetHandler = this.get('afterReset');
  const beforeResetHandler = this.get('beforeReset');

  if(beforeResetHandler && typeof beforeResetHandler === 'function')
    beforeResetHandler(this);

  const tableFilters = this.serializeTableFilters();
  const sortFilter = this.serializeSortFilter();
  const resetedTable = this.get('originalTable').cloneNode(true);
  this.get('table').replaceWith(resetedTable);
  this.set('table', resetedTable);

  this.refreshTableFilters(tableFilters);
  this.refreshSortFilter(sortFilter);
  this.addTableFiltersListener();
  this.render();

  if(afterResetHandler && typeof afterResetHandler === 'function')
    afterResetHandler(this);
}

TableRender.prototype.render = function () {
	const onRender = this.get('onRender');

	if (onRender && typeof onRender === 'function') {
		onRender(this);
	}

	const table = this.get('table');
	const onRenderRow = this.get('onRenderRow');
	const onRenderCell = this.get('onRenderCell');
	const onSelectRow = this.get('onSelectRow');
	const onSort = this.get('onSort');
	const afterRender = this.get('afterRender');

	if (!table) {
		return;
	}

	const thead = table.querySelector('thead');
	const tbody = table.querySelector('tbody');
	const tfooter = table.querySelector('tfoot');

	const tableHeaders = Array.from(thead.querySelectorAll('th'));

	tableHeaders.forEach((th) => {
		const tableSorter = this.recreateNode(th.querySelector('.table-sorter'));

		if (tableSorter && onSort && typeof onSort === 'function') {
			tableSorter.addEventListener('click', (e) => {
				onSort(e.target, this);
			});
		}
	});

	const templateRow = table
		.querySelector('tbody .table-row-template')
		.cloneNode(true);

	templateRow.classList.remove('hidden');
	templateRow.classList.add('hidden');

	tbody.innerHTML = '';
	tbody.appendChild(templateRow);

	(this.get('data').body || []).forEach((data) => {
		const tableRow = templateRow.cloneNode(true);
		const tableCells = Array.from(tableRow.querySelectorAll('td[data-column]'));

		tableRow.dataset.id = data[tableRow.dataset.id];

		tableCells.forEach((tableCell) => {
			if (onRenderCell && typeof onRenderCell === 'function') {
				onRenderCell(tableCell, data);
			}
		});

		if (onSelectRow && typeof onSelectRow === 'function') {
			tableRow.addEventListener('click', (e) => {
				const shouldUpdate = this.get('shouldSelectRow')(e.target);

				if (!shouldUpdate) return;

				const selectedRows = this.get('selectedRows');
				if (selectedRows.includes(tableRow.dataset.id)) {
					this.set(
						'selectedRows',
						selectedRows.filter((value) => value != tableRow.dataset.id)
					);
				} else {
					selectedRows.push(tableRow.dataset.id);
				}

				this.get('onSelectRow')(tableRow, this.get('selectedRows'));
			});
		}

		tableRow.classList.remove('hidden');
		tableRow.classList.remove('table-row-template');

		if (onRenderRow && typeof onRenderRow === 'function') {
			onRenderRow(tableRow, data, this);
		}

		tbody.appendChild(tableRow);
	});

	const tableFooterCells = Array.from(
		tfooter.querySelectorAll('tr td[data-column]')
	);
	tableFooterCells.forEach((tableCell) => {
		if (onRenderCell && typeof onRenderCell === 'function') {
			onRenderCell(tableCell, this.get('data').footer || {});
		}
	});

  if(afterRender && typeof afterRender === 'function') {
    afterRender(this);
  }
};

function tableRenderHandler() {
	return {
		set: function (target, name, value) {
			if (['formatter'].includes(name)) {
				return;
			}

			target[name] = value;
		},
		get: function (target, name) {
			if (name === 'table') {
				if (typeof target[name] !== 'string') {
					return target[name];
				}

				return document.querySelector(target[name]);
			}

			return target[name];
		},
	};
}
