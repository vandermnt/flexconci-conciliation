const _defaultEvents = {
	table: {
		onRenderRow: function (row, data, tableRenderInstance = null) {
			const selectedRows = tableRenderInstance.get('selectedRows');
			row.classList.remove('marcada');
			if (selectedRows.includes(row.dataset.id)) {
				row.classList.add('marcada');
			}

			Array.from(row.querySelectorAll('.actions-cell .tooltip-hint')).forEach(
				(element) => {
					const title = data[element.dataset.title];
					const defaultTitle = element.dataset.defaultTitle;

					element.dataset.title = tableRenderInstance.formatCell(
						title,
						'text',
						defaultTitle
					);
				}
			);

			Array.from(row.querySelectorAll('.actions-cell img[data-image]')).forEach(
				(element) => {
					const image = data[element.dataset.image];
					const defaultImage = element.dataset.defaultImage;

					const src = image || defaultImage;

					if (src) {
						element.dataset.image = src;
						element.src = src;
					}
				}
			);
		},
		onRenderCell: function (cell, data) {
			if (cell.classList.contains('tooltip-hint')) {
				const title = data[cell.dataset.title];
				const defaultTitle = cell.dataset.defaultTitle;

				cell.dataset.title = tableRender.formatCell(
					title,
					'text',
					defaultTitle
				);
			}

			Array.from(cell.querySelectorAll('.tooltip')).forEach((element) => {
				const title = data[element.dataset.title];
				const defaultTitle = element.dataset.defaultTitle;

				element.dataset.title = tableRender.formatCell(
					title,
					'text',
					defaultTitle
				);
			});

			if (cell.dataset.image) {
				const iconContainer = cell.querySelector('.icon-image');
				const imageUrl = data[cell.dataset.image];
				const defaultImageUrl = cell.dataset.defaultImage;

				if (imageUrl || defaultImageUrl) {
					iconContainer.style.backgroundImage = `url("${
						imageUrl || defaultImageUrl
					}")`;
					const title = data[iconContainer.dataset.title];
					const defaultTitle = iconContainer.dataset.defaultTitle;

					iconContainer.dataset.title = tableRender.formatCell(
						title,
						'text',
						defaultTitle
					);
					return;
				}
				iconContainer.classList.toggle('hidden');
			}

			const cellValue = data[cell.dataset.column];
			const defaultCellValue = data[cell.dataset.defaultValue];
			const format = cell.dataset.format || 'text';

			if (cell.dataset.reverseValue) {
				const reverseValue = tableRender.formatCell(
					cellValue * -1,
					format,
					defaultCellValue * -1
				);
				cell.textContent = reverseValue;
				return;
			}

			const value = tableRender.formatCell(cellValue, format, defaultCellValue);
			cell.textContent = value;
		},
		shouldSelectRow: function (elementDOM) {
			if (!elementDOM) return false;

			let tr = elementDOM;

			if (tr.tagName.toLowerCase() !== 'tr') {
				tr = elementDOM.closest('tr');
			}

			if (!tr) {
				return false;
			}

			if (['a', 'i'].includes(elementDOM.tagName.toLowerCase())) {
				return false;
			}

			return true;
		},
		onSelectRow: function (elementDOM, selectedRows) {
			if (!elementDOM) return false;

			const tr = elementDOM;
			tr.classList.remove('marcada');
			if (selectedRows.includes(tr.dataset.id)) {
				tr.classList.add('marcada');
			} else {
				tr.classList.remove('marcada');
			}
		},
		onSort: function (elementDOM, tableInstance) {
			const sortOrderSequence = {
				none: { to: 'asc' },
				asc: { to: 'desc' },
				desc: { to: 'none' },
			};

			if (!elementDOM || elementDOM.tagName.toLowerCase() === 'input') return;

			const tableSorter = elementDOM.closest('.table-sorter');
			if (!tableSorter) return;

			const sortIcon = tableSorter.querySelector('.table-sort-icon');
			if (sortIcon.dataset.sortOrder === 'disabled') return;

			const activeSortColumn = tableInstance.get('sort').by;
			const nextColumn = tableSorter.dataset.tbsortBy;

			if (activeSortColumn && activeSortColumn !== nextColumn) {
				const selector = `.table-sorter[data-tbsort-by="${activeSortColumn}"] .table-sort-icon`;
				const activeSortIcon = tableInstance
					.get('table')
					.querySelector(selector);
				activeSortIcon.dataset.sortOrder = 'none';

				tableInstance.set('sort', {
					by: null,
					order: '',
				});
			}

			const previousOrder = sortIcon.dataset.sortOrder;
			const currentOrder = sortOrderSequence[previousOrder].to;
			sortIcon.dataset.sortOrder = currentOrder;

			tableInstance.set('sort', {
				by: currentOrder !== 'none' ? nextColumn : null,
				order: currentOrder !== 'none' ? currentOrder : '',
			});
		},
	},
};

function createSearchForm({ form, inputs, checker }) {
	const searchForm = new SearchFormProxy({
		form,
		inputs,
		checker,
	});

	Array.from(
		searchForm.get('form').querySelectorAll('button[data-form-action="clear"]')
	).forEach((buttonDOM) => {
		buttonDOM.addEventListener('click', (e) => searchForm.clear());
	});

	return searchForm;
}

function createTableRender({ table = '', locale = 'pt-br', formatter }) {
	const tableRender = new TableRender({
		table,
		locale,
		formatter,
	});

	tableRender.onRenderRow(_defaultEvents.table.onRenderRow);

	tableRender.onRenderCell(_defaultEvents.table.onRenderCell);

	tableRender.shouldSelectRow(_defaultEvents.table.shouldSelectRow);

	tableRender.onSelectRow(_defaultEvents.table.onSelectRow);

	tableRender.onSort(_defaultEvents.table.onSort);

	return tableRender;
}

function toggleElementVisibility(selector = '') {
	const element = document.querySelector(selector);

	if (element) {
		element.classList.toggle('hidden');
	}
}

function getBoxes() {
	const boxes = [];

	Array.from(document.querySelectorAll('.box')).forEach((boxDOM) => {
		const box = new Box({
			element: boxDOM,
			defaultValue: 0,
			format: boxDOM.dataset.format,
			formatter,
		});
		boxes.push(box);
	});

	return boxes;
}

function updateBoxes(boxes, totals) {
	Object.keys(totals).forEach((key) => {
		const box = boxes.find(
			(box) =>
				box.get('element').dataset.key.toLowerCase() === key.toLowerCase()
		);
		if (!box) {
			return;
		}
		box.set('value', totals[key]);
		box.render();
	});
}

function onCancelModalSelection(event) {
	const buttonDOM = event.target;
	const groupName = buttonDOM.dataset.group;

	checker.uncheckAll(groupName);
	checker.setValuesToTextElement(groupName, 'descricao');
}

function onConfirmModalSelection(event) {
	const buttonDOM = event.target;
	const groupName = buttonDOM.dataset.group;

	checker.setValuesToTextElement(groupName, 'descricao');
}

function openConfirmDialog(title = '', onReply = (value) => {}, config = {}) {
	swal(title, {
		...config,
		buttons: config.buttons
			? config.buttons
			: {
					confirm: 'Sim',
					cancel: 'Não',
			  },
	}).then((value) => onReply(value));
}

function openUrl(baseUrl, params) {
	const url = api.urlBuilder(baseUrl, params);
	const a = document.createElement('a');

	a.href = url;
	a.target = '_blank';
	a.click();
}

function recreateNode(element = '') {
	const elementDOM =
		typeof element === 'string' ? document.querySelector(element) : element;
	if (!elementDOM) return;

	const elementCloneDOM = elementDOM.cloneNode(true);
	elementDOM.parentNode.replaceChild(elementCloneDOM, elementDOM);
	return document.querySelector(element);
}

function updateData(array = [], newData = [], idKey = 'ID') {
	const newItens = Array.isArray(newData) ? newData : [newData];
	const data = [];

	newItens.forEach((item) => {
		const index = array.findIndex(
			(sale) => String(sale[idKey]) === String(item[idKey])
		);

		if (index !== -1) {
			array.splice(index, 1, {
				...array[index],
				...item,
			});

			data.push({ ...array[index], ...item });
		}
	});

	return {
		data: array,
		updated: data,
	};
}

function removeFromData(array = [], ids = [], idKey = '') {
	const idArray = Array.isArray(ids) ? ids : [ids];
	const removed = [];

	idArray.forEach((id) => {
		const index = array.findIndex((sale) => String(sale[idKey]) === String(id));

		if (index !== -1) {
			removed.push({ ...array[index] });
			array.splice(index, 1);
		}
	});

	return {
		data: array,
		removed,
	};
}

/** Reference: https://stackoverflow.com/questions/11832914/round-to-at-most-2-decimal-places-only-if-necessary */
function roundDecimals(value = 0) {
	return Math.round((Number(value) + Number.EPSILON) * 100) / 100;
}

function updateTotals(totals, newData) {
	const newTotals = Object.keys(newData).reduce((updated, key) => {
		updated[key] =
			roundDecimals(Number(totals[key]) || 0) +
			roundDecimals(Number(newData[key]) || 0);
		return updated;
	}, {});

	return {
		...totals,
		...newTotals,
	};
}

function serializeTableSortToExport({ sort }) {
	if (!sort) return {};

	return {
		sort_column: sort.column,
		sort_direction: sort.direction,
	};
}

Array.from(
	document.querySelectorAll('.modal button[data-action="confirm"]')
).forEach((buttonDOM) => {
	buttonDOM.addEventListener('click', onConfirmModalSelection);
});

Array.from(
	document.querySelectorAll('.modal button[data-action="cancel"]')
).forEach((buttonDOM) => {
	buttonDOM.addEventListener('click', onCancelModalSelection);
});
