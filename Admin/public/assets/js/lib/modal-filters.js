function ModalFilter() {
  this.groups = {};
}

ModalFilter.prototype._convertStringAsArray = function(arrayAsString) {
  const stringValues = arrayAsString.split(',');
  const arrayValues = stringValues
    .map(value => value.trim())
    .filter(trimmedValue => !!trimmedValue);

  return arrayValues;
}

ModalFilter.prototype._toggleVisibility = function(item, isVisible = true) {
  if(isVisible) {
    item.style.removeProperty('display');
  } else {
    item.style.display = 'none';
  }
}

ModalFilter.prototype._toggleVisibilityAll = function(items, isVisible = true) {
  [...items].forEach(item => {
    this._toggleVisibility(item, isVisible);
  });
}

ModalFilter.prototype.hideAll = function(name) {
  const items = document.querySelectorAll(`*[data-filter-item-container=${name}]`);

  this._toggleVisibilityAll(items, false);
}

ModalFilter.prototype.showAll = function(name) {
  const items = document.querySelectorAll(`*[data-filter-item-container=${name}]`);

  this._toggleVisibilityAll(items, true);
}

ModalFilter.prototype._onInputHandler = function(event) {
  const fieldInput = event.target;
  const valueToFilter = fieldInput.value;
  const groupName = fieldInput.dataset.filterGroup;
  const group = this.groups[groupName];

  if(!valueToFilter) {
    this.showAll(groupName);
    return;
  }

  this.hideAll(groupName);

  const selectorPrefix = `*[data-filter-item-container=${groupName}]`;
  const querySelector = group.fields.map(field => {
    return `${selectorPrefix}[data-filter-${field}*="${valueToFilter}" i]`;
  }).join(', ');

  const filteredElements = document.querySelectorAll(querySelector);

  this._toggleVisibilityAll(filteredElements, true);
}

ModalFilter.prototype._registerHandler = function(name, callback = () => {}) {
  const { filterInput } = this.groups[name];

  filterInput.addEventListener('input', (event) => {
    this._onInputHandler(event);

    if(callback && typeof callback === 'function') {
      callback(event);
    }
  })
}

ModalFilter.prototype.addGroup = function(name, callback = () => {}) {
  const filterInput = document.querySelector(`input[data-filter-group="${name}"]`);
  const fieldsAsString = filterInput.dataset.filterFields;
  const fields = this._convertStringAsArray(fieldsAsString);

  this.groups = {
    ...this.groups,
    [name]: {
      filterInput,
      fields,
    }
  }

  this._registerHandler(name, callback);
  return this;
}