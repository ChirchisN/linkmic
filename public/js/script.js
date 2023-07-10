
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