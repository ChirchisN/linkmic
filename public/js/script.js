
function cleanErrors() {
    document.querySelectorAll('div[class="error_text"]').forEach(function (item) {
        item.innerText = '';
    });
}

function showErrors(errorsArray, errorKey) {
    if (errorsArray[errorKey] != null) {
        errorsArray[errorKey].forEach(function (item) {
            document.getElementById(errorKey + 'Error').innerText += item + '\n';
        });
    }
}

function showGeneralErrors(error) {
    document.getElementById('generalErrors').innerText = error;
}

function loadCurrentUserDetails() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/user');
    xhr.send();
    xhr.onload = function () {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.response);
            document.getElementById('user').innerText = response['firstName'] + ' ' + response['lastName'];
            document.getElementById('signIn').style.display = "none";
            document.getElementById('signUp').style.display = "none";
            document.getElementById('logout').style.display = "inline";
        }
    }
}
