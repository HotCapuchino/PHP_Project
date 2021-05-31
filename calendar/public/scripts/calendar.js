const ADDING_ENDPOINT = '/calendar/add';
const EDITING_ENDPOINT = '/calendar/edit';

let addToDo_btn = document.querySelector('.main__addToDo-btn');
let toDo_form = document.querySelector('.toDo-form');
let createToDo_btn = document.querySelector('.toDo-form__submit-btn');
let toDo_list = document.querySelector('.toDo-list');
let modalWindow = document.querySelector('.modal-wrapper');

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
        Array.from(toDo_form.querySelectorAll('textarea')).forEach(textarea => {
            toDo_params.forEach(param => {
                if (param.className.split('__')[1] 
                    === (textarea.className.split('__')[0]).split('-')[1]) {
                    textarea.value = param.getAttribute('value');
                }
            });
        })
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
    console.log(validation_passed);
    return validation_passed;
}

function hasToBeUpdated() {

}

function showModalWindow(modal_title, modal_message) {
    modalWindow.classList.remove('none');
    modalWindow.querySelector('.modal-header__title').textContent = modal_title;
    modalWindow.querySelector('.modal-body__message').textContent = modal_message;
    setTimeout(() => {
        modalWindow.classList.add('none');
    }, 2000);
}

let closeModalWindow = () => modalWindow.classList.add('none');
modalWindow.querySelector('.modal-header__close-btn').addEventListener('click', closeModalWindow);
modalWindow.querySelector('.modal-footer__close-btn').addEventListener('click', closeModalWindow);