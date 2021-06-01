const ADDING_ENDPOINT = '/calendar/add';
const EDITING_ENDPOINT = '/calendar/edit';
const tasks = document.getElementsByClassName('toDo-list__item');

let addToDo_btn = document.querySelector('.main__addToDo-btn');
let toDo_form = document.querySelector('.toDo-form');
let createToDo_btn = document.querySelector('.toDo-form__submit-btn');
let toDo_list = document.querySelector('.toDo-list');
let modalWindow = document.querySelector('.modal-wrapper');
let toDo_form__close_btn = document.querySelector('.toDo-form__close-btn');
let reset_filter_btn = document.querySelector('.filter-wrapper__reset-btn');

toDo_form__close_btn.addEventListener('click', function(event) {
    event.preventDefault();
    toDo_form.classList.add('none');
});

addToDo_btn.addEventListener('click', function() {
    if (!toDo_form.classList.contains('none') && toDo_form.getAttribute('action') == ADDING_ENDPOINT) {
        toDo_form.classList.add('none');
    } else {
        changeFormType('create');
        let task_input = toDo_form.querySelector('.toDo-form_task-id');
        if (task_input) {
            toDo_form.removeChild(task_input);
        }
        clearFormFields();
        toDo_form.classList.remove('none');
    }
});

createToDo_btn.addEventListener('click', function(event) {
    let result = validateToDoFields();
    if (!result) {
        showModalWindow('Warning', 'Please fill in all the fields!');
        event.preventDefault();
        event.stopImmediatePropagation();
    } 
});

toDo_list.addEventListener('click', function(event) {
    let list_elem = event.target.parentElement;
    let toDo_params = Array.from(list_elem.children);
    if (list_elem.classList.contains('toDo-item')) {
        changeFormType('edit');
        toDo_form.classList.remove('none');
        toDo_form.querySelector('.toDo-form_task-id').value = list_elem.getAttribute('value');
        Array.from(toDo_form.querySelectorAll('input')).forEach(input => {
            toDo_params.forEach(param => {
                if (param.className.split('__')[1] 
                    === (input.className.split('__')[0]).split('-')[1]) {
                    input.value = param.getAttribute('value');
                }
            }); 
        });
        Array.from(toDo_form.querySelectorAll('select')).forEach(select => {
            toDo_params.forEach(param => {
                if (param.className.split('__')[1] 
                === (select.className.split('__')[0]).split('-')[1]) {
                    Array.from(select.children).forEach(child => {
                        if (child.hasAttribute('selected')) {
                            child.removeAttribute('selected');
                        }
                        if (child.getAttribute('value') == param.getAttribute('value')) {
                            child.setAttribute('selected', '');
                        }
                    });
                }
            });
        });
        let hidden_content = list_elem.closest('.toDo-list__item').querySelector('.hidden-content');
        toDo_form.querySelector('textarea').value = hidden_content.querySelector('.hidden-content__comment').textContent;
    }
});

toDo_list.addEventListener('contextmenu', function(event) {
    let list_elem = event.target.parentElement;
    if (list_elem.classList.contains('toDo-item')) {
        event.preventDefault();
        event.stopImmediatePropagation();
        let hidden_content = list_elem.closest('.toDo-list__item').querySelector('.hidden-content');
        hidden_content.classList.toggle('none');
    }
});

function changeFormType(type) {
    if (type === 'create') {
        toDo_form.setAttribute('action', ADDING_ENDPOINT);
        toDo_form.querySelector('.toDo-form__title').textContent = 'Creating Task';
        createToDo_btn.textContent = 'Create Task';
    } else if (type === 'edit') {
        let elem = document.createElement('input');
        elem.classList.add('toDo-form_task-id');
        elem.classList.add('none');
        elem.setAttribute('value', 'number');
        elem.setAttribute('name', 'id');
        toDo_form.append(elem);
        toDo_form.setAttribute('action', EDITING_ENDPOINT);
        toDo_form.querySelector('.toDo-form__title').textContent = 'Editing Task';
        createToDo_btn.textContent = 'Edit Task';  
    }
}

function clearFormFields() {
    Array.from(toDo_form.querySelectorAll('input')).forEach(input => input.value = '');
    Array.from(toDo_form.querySelectorAll('select')).forEach(select => {
        Array.from(select.children).forEach(option => {
            option.removeAttribute('selected');
        }); 
        select.firstElementChild.setAttribute('selected', ''); 
    });
    Array.from(toDo_form.querySelectorAll('textarea')).forEach(textarea => {
        textarea.value = '';
    });
}

function validateToDoFields() {
    let fields = Array.from(toDo_form.querySelectorAll('input')).concat(Array.from(toDo_form.querySelectorAll('select')));
    console.log(fields);
    let validation_passed = true;
    fields.forEach(field => {
        if (field.classList.contains('wrong_input')) {
            field.classList.remove('wrong_input');
        }
        if (!field.value) {
            field.classList.add('wrong_input');
            console.log(`Unfilled value: ${field}`);
            validation_passed = false;
        } 
    });
    return validation_passed;
}

function showModalWindow(modal_title, modal_message) {
    modalWindow.classList.remove('none');
    modalWindow.querySelector('.modal-header__title').textContent = modal_title;
    modalWindow.querySelector('.modal-body__message').textContent = modal_message;
    setTimeout(() => {
        modalWindow.classList.add('none');
    }, 5000);
}

let closeModalWindow = () => modalWindow.classList.add('none');
modalWindow.querySelector('.modal-header__close-btn').addEventListener('click', closeModalWindow);
modalWindow.querySelector('.modal-footer__close-btn').addEventListener('click', closeModalWindow);

class Filter {
    enabled_filters = []

    filterByStatus(toDo) {
        if (this.value == 0) return true;
        return toDo.querySelector('.toDo-item').getAttribute('status') == this.value;
    }

    filterByDate(toDo) {
        let taskDate = new Date(String(toDo.querySelector('.toDo-item__date').getAttribute('value')));
        taskDate.setHours(0,0,0,0);
        let userDate = new Date(String(this.value))
        userDate.setHours(0,0,0,0);
        return taskDate.getTime() === userDate.getTime();
    }

    filterByTime(toDo) {
        let taskDate = new Date(String(toDo.querySelector('.toDo-item__date').getAttribute('value')));
        taskDate.setHours(0,0,0,0);
        let startDate = null;
        let endDate = null;
        switch(this.value) {
            case 'all': {
                return true;
            }
            case 'today': {
                startDate = new Date(Date.now());
                endDate = startDate;
                break;
            }
            case 'tomorrow': {
                startDate = new Date(Date.now());
                startDate.setDate(startDate.getDate() + 1);
                endDate = startDate;
                break;
            }
            case 'this_week': {
                let today = new Date(Date.now()).getDay();
                let monday_diff = today - 1 >= 0 ? today - 1 : 7;
                startDate = new Date(Date.now());
                startDate.setDate(startDate.getDate() - monday_diff);
                let sunday_diff = 6 - today + 1 < 6 ? 6 - today + 1 : 7;
                endDate = new Date(Date.now());
                endDate.setDate(endDate.getDate() + sunday_diff);
                break;
            }
            case 'next_week': {
                let today = new Date(Date.now()).getDay();
                let monday_diff = today - 1 >= 0 ? today - 1 : 7;
                startDate = new Date(Date.now());
                startDate.setDate(startDate.getDate() - monday_diff + 7);
                let sunday_diff = 6 - today + 1 < 6 ? 6 - today + 1 : 7;
                endDate = new Date(Date.now());
                endDate.setDate(endDate.getDate() + sunday_diff + 7);
                break;
            }
        }
        startDate.setHours(0,0,0,0);
        endDate.setHours(0,0,0,0);
        // debugger;
        return (taskDate >= startDate) && (taskDate <= endDate) ? true : false;
    }

    addFilter(filter_name, value) {
        let result = this.checkForDuplicatedFilters('filterBy' + filter_name.charAt(0).toUpperCase() + filter_name.slice(1), value);
        if (typeof(result) == 'number') {
            this.enabled_filters.splice(result, 1);
        } else if (result === true) {
            return;
        }
        switch(filter_name) {
            case 'status': {
                this.enabled_filters.push(this.filterByStatus);
                break;
            }
            case 'date': {
                this.enabled_filters.push(this.filterByDate);
                break;
            }
            case 'time': {
                this.enabled_filters.push(this.filterByTime);
                break;
            }
        }
        this.enabled_filters[this.enabled_filters.length - 1].value = value;
        this.generalFilter();
    }

    checkForDuplicatedFilters(filter_name, value) {
        let count = 0;
        for (const filter of this.enabled_filters) {
            console.log(filter.name, filter.value)
            if (filter.name === filter_name && filter.value !== value) {
                return count;
            } else if (filter.name === filter_name && filter.value === value) {
                return true;
            }
            count++;
        }
        return false;
    }

    generalFilter() {
        for (const task of tasks) {
            let should_be_hidden = false;
            for (const filter of this.enabled_filters) {
                if (!filter.bind(filter, task)()) {
                    should_be_hidden = true;
                    break;
                }
            }
            if (should_be_hidden) {
                task.classList.add('none');
            } else {
                task.classList.remove('none');
            }
        }
    }

    resetFilters() {
        this.enabled_filters = [];
        this.generalFilter();
    }

}

let filter = new Filter();

let status_filter = document.querySelector('.status-filter-wrapper__select');
status_filter.addEventListener('change', function(event) {
    filter.addFilter(event.target.className.split('-')[0], event.target.value);
});
let time_filter = document.querySelector('.time-filter-wrapper');
time_filter.addEventListener('click', function(event) {
    if (event.target.classList[0] === 'time-filter-wrapper__option') {
        filter.addFilter(event.target.classList[0].split('-')[0], event.target.getAttribute('value'));
        Array.from(time_filter.children).forEach(filter => {
            filter.classList.remove('checked');
            if (filter === event.target) {
                filter.classList.add('checked');
            }
        })
    }
});
let date_filter = document.querySelector('.date-filter');
date_filter.addEventListener('change', function(event) {
    filter.addFilter(event.target.className.split('-')[0], event.target.value);
});
reset_filter_btn.addEventListener('click', function() {
    filter.resetFilters();
    date_filter.value = null;
})