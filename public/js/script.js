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
            document.getElementById('user').innerText = response['data']['firstName'] + ' ' + response['data']['lastName'];
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
        for (key in response['data']) {
            let link = response['data'][key];
            document.getElementById('links').innerHTML += showLink(link['original_link'], link['short_link'], link['redirected_count'], link['id']);
        }
    }
}

function deleteBtn(id) {
    let xhr = new XMLHttpRequest();
    xhr.open('DELETE', '/links/' + id);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    xhr.send();
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('link' + id).remove();
        }
    }
}

function showLink(originalLink, shortLink, redirectedCount, buttonId) {
    return '<div class="d-flex flex-row justify-content-between mb-3 link" id="link' + buttonId + '">' +
        '<div class="d-flex flex-column justify-content-between">' +
        '<div class="me-3 pb-2"><h3 class="d-inline">Original URL:</h3> <span id="originalLink" class="fst-italic text-break">' + originalLink + '</span></div>' +
        '<div class="me-3 pb-2"><h3 class="d-inline">Short URL:</h3> <span id="shortLink" class="fst-italic">' + shortLink + '</span></div>' +
        '<div class="me-3"><h3 class="d-inline">Redirected number:</h3> <span id="count" class="fst-italic">' + redirectedCount + '</span></div>' +
        '</div>' +
        '<div class="d-flex flex-column justify-content-center">' +
        '<button type="button" onclick="deleteBtn(' + buttonId + ')" class="btn btn-primary btn-lg button" >Delete</button>' +
        '</div>' + '</div>';
}