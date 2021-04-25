const Checker = function() {
    this.groups = {};
}

/**
 * @function _toggleCheck
 *
 * Toggle all checkboxes for a group
 * @param {Strin} name - Group name
 * @param {boolean} value - Set if the checkboxes is checked or not.
 */
Checker.prototype._toggleCheck = function(name, value) {
    const group = this.groups[name];

    const querySelectorOperator = value ? 'not(:checked)' : 'checked';
    const checkboxesToBeToggled = document
        .querySelectorAll(
            `input[type="checkbox"][data-checker="checkbox"][data-group="${name}"]:${querySelectorOperator}`
        );

    if(group.globalCheckbox) {
        group.globalCheckbox.checked = value;
    }

    [...checkboxesToBeToggled].forEach(checkbox => {
        checkbox.checked = value;
    });
}

/**
 * @function _handleGlobalClick
 *
 * Handle the click on global checkbox of one group
 *
 * @param {Event} event
 * @returns
 */
Checker.prototype._handleGlobalClick = function(event, onChangeCallback) {
    const { target } = event;
    const groupName = target.dataset.group;
    const isSelected = target.checked;

    this._toggleCheck(groupName, isSelected);

    if(typeof onChangeCallback === 'function') {
        onChangeCallback(event);
    }
}

/**
 * @function _valuesToString
 *
 * Return the checked values of a group as a string format
 *
 * @param {Object} group
 * @param {String} attributeValueName - The attribute name that has the value @default value
 *
 * @returns {String}
 */
Checker.prototype._valuesToString = function(group, attributeValueName = 'value') {
    const selectedCheckboxes = document
        .querySelectorAll(`input[type="checkbox"][data-checker="checkbox"][data-group="${group.name}"]:checked`);

    const selectedValues = this.getCheckedValues(group.name, attributeValueName);

    return selectedValues.join(group.valuesTextSeparator);
}

/**
 * @function _registerGroup
 * Register the global checkbox handler
 *
 * @param {Object} group
 */
Checker.prototype._registerGroup = function (group, onChangeCallback) {
    if(!group.globalCheckbox) return;

    group.globalCheckbox.addEventListener('change', (event) => {
        this._handleGlobalClick(event, onChangeCallback);
    });
}

/**
 * @function checkAll
 * Check all checkboxes from a group invoking the _toggleCheck method
 *
 * @param {String} name - The group name
 */
Checker.prototype.checkAll = function (name) {
    this._toggleCheck(name, true);
}

/**
 * @function uncheckAll
 * Uncheck all checkboxes from a group invoking the _toggleCheck method
 *
 * @param {String} name - The group name
 */
Checker.prototype.uncheckAll = function (name) {
    this._toggleCheck(name, false);
}

Checker.prototype.checkOnly = function (name, valuesToBeChecked = []) {
  if(valuesToBeChecked.length === this.groups[name].checkboxes.length) {
    this.checkAll(name);
    return;
  }

  const selector = valuesToBeChecked.map((value => {
    return `input[type="checkbox"][data-checker="checkbox"][data-group="${name}"][value="${value}"]:not(:checked)`;
  }))
    .join(', ');

  const checkboxes = [...document.querySelectorAll(selector)];
  checkboxes.forEach(checkbox => checkbox.checked = true);
  return true;
}

/**
 * @function addGroup
 * Add new group to checkers.group array and register the new group event listeners.
 *
 * @param {String} name - The group name
 * @param {String} options.valuesTextSeparator - The string separator for text values. Default to ', '
 */
Checker.prototype.addGroup = function(name, options = {}, onChangeCallback = () => {}) {
    if(this.groups[name]) return;

    const valuesTextSeparator = options.valuesTextSeparator || ', ';

    const globalCheckbox = document
        .querySelector(`input[type="checkbox"][data-checker="global"][data-group="${name}"]`);

    const checkboxes = document
        .querySelectorAll(`input[type="checkbox"][data-checker="checkbox"][data-group="${name}"]`);

    this.groups[name] = {
        name,
        inputName: options.inputName || name || '',
        globalCheckbox,
        checkboxes,
    };

    const toTextElement = document
            .querySelector(`*[data-checker="to-text-element"][data-group="${name}"]`);

    if(toTextElement) {
        this.groups[name] = {
            ...this.groups[name],
            toTextElement,
            valuesTextSeparator
        }
    }

    this._registerGroup(this.groups[name], onChangeCallback);
}

Checker.prototype.addGroups = function(groups = [], onChangeCallback = () => {}) {
    groups.forEach(group => {
      if(typeof group === 'string') {
        return this.addGroup(group, {}, onChangeCallback);
      }

      if(!group.onChangeCallback) {
        group.onChangeCallback = onChangeCallback
      }

      return this.addGroup(group.name, group.options, group.onChangeCallback);
    });

    return this;
}

Checker.prototype._getCheckedOrUncheckedValues = function(name, mustBeChecked = true, attributeValueName = 'value') {
  const { checkboxes } = this.groups[name];

  const callback = mustBeChecked ? (checkbox) => checkbox.checked : (checkbox) => !checkbox.checked;

  return [...checkboxes].reduce((values, checkbox) => {
      if(callback(checkbox)) {
          const value = checkbox[attributeValueName] ||
              checkbox.dataset[attributeValueName] ||
              checkbox.getAttribute(`data-${attributeValueName}`);

          return [...values, value];
      }

      return values;
  }, []);
}

/**
 * @function getCheckedValues
 * Return an array with all checked values
 *
 * @param {String} name - The group name
 * @param {String} attributeValueName - The attribute name that has the value @default value
 */
Checker.prototype.getCheckedValues = function(name, attributeValueName = 'value') {
  return this._getCheckedOrUncheckedValues(name, true, attributeValueName);
}

Checker.prototype.getUncheckedValues = function(name, attributeValueName = 'value') {
  return this._getCheckedOrUncheckedValues(name, false, attributeValueName);
}

/**
 * @function getValuesBy
 * Return values that have specific data attribute values
 *
 * @param {String} groupName - The group name
 * @param {String} attributeValueName - The attribute name that has the value @default value
 * @param {Array} values - The search attribute values
 */
Checker.prototype.getValuesBy = function(groupName, attributeValueName, values) {
    const { checkboxes } = this.groups[groupName];

    return [...checkboxes].reduce((_values, checkbox) => {
        const checkDataValue = checkbox[attributeValueName] ||
            checkbox.dataset[attributeValueName] ||
            checkbox.getAttribute(`data-${attributeValueName}`);

        if(values.includes(checkDataValue)) {
            return [..._values, (checkbox.value || '')];
        }

        return _values;
    }, []);
}

/**
 * @function setValuesToTextElement
 * Convert all checkboxes that are checked to a string invoking the _valuesToString Method
 * and set the returned string to the text element
 *
 * @param {String} name - The group name
 * @param {String} attributeValueName - The attribute name that has the value @default value
 *
 * @returns
 */
Checker.prototype.setValuesToTextElement = function (name, attributeValueName = 'value') {
    const group = this.groups[name];
    let toTextAttribute = 'textContent';

    if(!group.toTextElement) return;

    const textElementTagName = group.toTextElement.tagName.toLowerCase();
    if(['input', 'select'].includes(textElementTagName)) {
        toTextAttribute = 'value';
    }

    group.toTextElement[toTextAttribute] = this._valuesToString(group, attributeValueName);
}

/**
 * @function removeGroup
 * Remove a group from the groups array and remove all event listeners related
 *
 * @param {String} name - The group name
 * @returns
 */
Checker.prototype.removeGroup = function(name) {
    const { globalCheckbox } = this.groups[name];
    if(globalCheckbox) {
        globalCheckbox.removeEventListener('change', this._handleGlobalClick);
    }
    delete this.groups[name];
}

Checker.prototype.serialize = function(groupName = null) {
    if(groupName) {
        return {
            [this.groups[groupName].inputName]: this.getCheckedValues(groupName),
        }
    }

    const serializedData = Object.keys(this.groups).reduce((data, groupName) => {
        const values = this.getCheckedValues(groupName);
        if(values.length > 0) {
            data[this.groups[groupName].inputName] = values;
        }

        return data;
    }, {});

    return serializedData;
}
