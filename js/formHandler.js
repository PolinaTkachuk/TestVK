"use strict"

//валидация формы - регистрации
//Правильность email и Подсветка пустых полей

//Проверка на существовние пользователя с таким же логином и почтой,
//а также проверка на непустоту обязательных полей- В Файле formHandler.php


document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form');
    let formInputs = document.querySelectorAll('._required');
    //при отправке формы переходим в функцию formSend

    form.addEventListener('submit', formSend);

    //использование аякс технологий
    async function formSend(e) {
    //запрет на отправку незаполненной формы
    e.preventDefault();

    //объявили переменную, которая вызывает функцию, проверяющую валидность формы
    let error = formValidate();
    let formData = 'login=' + formInputs[0].value + '&namepass=' + formInputs[1].value + '&mail=' + formInputs[2].value;
    if (error === 0) {
    //сообщаем пользователю , что идет успешная отправка формы
    form.classList.add('_sending');
    //успешная отправка
    await fetch('formHandler.php', {
    method: 'POST',
    body: formData,
    headers: { "content-type": "application/x-www-form-urlencoded" }
    })
    .then((response) => {
    if (!response.ok) {
    return Promise.reject();
    }
    return response.text()
    })
    //document.location.replace("../formHandler.php")
    .then((responseHTML) => document.location.replace("../sayt.php"))
    .catch(() => console.log('ошибка'))
    }
    else {
    alert('Заполните обязательные поля');
    }
}



function formValidate() {
    let error = 0;
    //класс какие поля нужно будет выделять- обязательные поля
    let formReq = document.querySelectorAll('._required');

    for (let i = 0; i < formReq.length; i++) {
    //бегунок по объектам

    const input = formReq[i];
    //убираем изначально класс error
    formRemoveError(input);

    if (input.classList.contains('_email')) {
    if (CheckEmail(input)) {
    //добавление объекта
    formAddError(input);
    error++;
    }
    } else if (input.value === '') {
    formAddError(input);
    error++;
    }
    else if (input.classList.contains('_login')) {
    if (input.value.lenght < 2 || input.lenght > 20) {
    formAddError(input);
    error++;
    }
    }
    //if (CheckLenght(input)) {
    // formAddError(input);
    // error++;
    //}
    //}

    }
    return error;
}

//вспомогательные функции добавл(удаляет) самому объекту и его родителю класс error
function formAddError(input) {
    input.parentElement.classList.add('_error');
    input.classList.add('_error');
    }
    function formRemoveError(input) {
    input.parentElement.classList.remove('_error');
    input.classList.remove('_error');
}

//проверка email
function CheckEmail(input) {
    return !/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(input.value);
}

//проверка на длину 2<input<20
function CheckLenght(input) {

    if (login.lenght < 2 || login.lenght > 20) { alert('В логине должен быть от 2 до 20 символов'); return false; }
    //if(/^[a-zA-Z1-9]+$/.test(input) === false){alert('В логине должны быть только латинские буквы'); return false;}
    else return true;
}
//переключение видимости пароля
function myPass() {
    var x = document.getElementById("formPassword");
    if (x.type === "password") {
    x.type = "text";
    } else {
    x.type = "password";
    }
}


});