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

ModalFilter.prototype.onInputHandler = function(event, callback = () => {}) {
  const fieldInput = event.target;
  const valueToFilter = fieldInput.value;
  const groupName = fieldInput.dataset.filterGroup;
  const group = this.groups[groupName];

  const selectorPrefix = `*[data-filter-group="${groupName}"]`;
  const querySelector = group.fields.map(field => {
    return `${selectorPrefix}[data-filter-${field}]*="${valueToFilter}"`;
  }).join(', ');

  const filteredElements = document.querySelectorAll(querySelector);

  if(callback && typeof callback === 'function') {
    callback();
  }
}

ModalFilter.prototype._registerHandler = function(name) {}

ModalFilter.prototype.addGroup = function(name) {
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
}