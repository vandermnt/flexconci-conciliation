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

    group.globalCheckbox.checked = value;
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
 * @returns {String}
 */
Checker.prototype._valuesToString = function(group) {
    const selectedCheckboxes = document
        .querySelectorAll(`input[type="checkbox"][data-checker="checkbox"][data-group="${group.name}"]:checked`);

    const selectedValues = [...selectedCheckboxes]
        .reduce((values, checkbox) => {
            const value = checkbox.dataset.checkedDescription || '';

            values.push(value);
            return values;
        }, []);

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

Checker.prototype.checkAll = function (name) {
    this._toggleCheck(name, true);
}

Checker.prototype.uncheckAll = function (name) {
    this._toggleCheck(name, false);
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

Checker.prototype.setValuesToTextElement = function (name) {
    const group = this.groups[name];
    
    if(!group.toTextElement) return;
    
    const textElementTagName = group.toTextElement.tagName.toLowerCase();
    if(['input', 'select'].includes(textElementTagName)) {
        group.toTextElement.value = this._valuesToString(group);
        return;
    }

    group.toTextElement.textContent = this._valuesToString(group);
}

Checker.prototype.removeGroup = function(name) {
    const { globalCheckbox } = this.groups[name];
    globalCheckbox.removeEventListener('change', this._handleGlobalClick);
    delete this.groups[name];
}