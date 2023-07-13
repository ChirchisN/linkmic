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

            loadLinks();
        }
    }
}

function loadLinks() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/links');
    xhr.send();
    xhr.onload = function () {
        let response = JSON.parse(xhr.response);

        document.getElementById('containerLinks').style.display = 'block';

        for (key in response) {
            let link = response[key];
            document.getElementById('links').innerHTML += '<div class="d-flex flex-column mb-3 justify-content-between link">' +
                '<div class="me-3">Original URL: <span id="originalLink" class="fst-italic">' + link['original_link'] + '</span></div>' +
                '<div class="me-3">Short URL: <span id="shortLink" class="fst-italic">' + link['short_link'] + '</span></div>' +
                '<div class="me-3">Redirected number: <span id="count" class="fst-italic">' + link['redirected_count'] + '</span></div></div>';
        }
    }
}
