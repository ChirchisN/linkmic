<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href={{asset('css/styles.css')}}>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LinkMic</title>
</head>
<body>
<div class="container">
    <header>
        <nav class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <a class="logo text-decoration-none" href="{{route('home')}}">LinkMic</a>
            </div>
            <div class="menu mr-5">
                <span class="mr-5" id="user"></span>
                <a href="{{route('login')}}" id="signIn" class="text-decoration-none">Sign In</a>
                <a href="{{route('registration')}}" id="signUp" class="text-decoration-none">Sign Up</a>
                <a href="#" id="logout" class="text-decoration-none" style="display: none">Logout</a>
            </div>
        </nav>
    </header>

    <section class="convert_link d-flex flex-column justify-content-center align-items-center">
        <h1 class="pt-3 mb-4">Paste the URL to be shortened</h1>
        <div id="generalErrors" class="error_text"></div>
        <form class="d-flex flex-column justify-content-center align-items-center">
            @csrf
            <label>Destination</label>
            <input class="mb-3" type="text" name="link" id="link" placeholder="Enter the link here ...">
            <div id="linkError" class="error_text"></div>
            <label>Custom link (optional)</label>
            <input class="mb-2" type="text" name="short_code" id="customLink">
            <div id="short_codeError" class="error_text"></div>
            <button type="button" class="btn btn-primary btn-lg button" id="linkBtn">Shorten URL</button>
        </form>
        <div class="d-flex justify-content-center mt-3">
            <div class="short_link" id="createdShortLink"></div>
            <button id="copy" class="btn btn-primary button copy_btn display_none">Copy</button>
        </div>
    </section>
</div>

<section class="container" id="containerLinks" style="display: none">
    <hr>
    <h2 class="fw-bold">Your Links</h2>
    <div id="links"></div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous">
</script>

<script src="{{asset('/js/script.js')}}"></script>

<script>
    let linkInput = document.getElementById('link');
    let customLinkInput = document.getElementById('customLink');
    let createdShortLinkDiv = document.getElementById('createdShortLink');
    let copyButton = document.getElementById('copy');
    let linksDiv = document.getElementById('links');

    loadCurrentUserDetails();

    copyButton.addEventListener('click', function () {
        navigator.clipboard.writeText(createdShortLinkDiv.innerText);
    });

    document.getElementById('linkBtn').addEventListener('click', function () {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/link');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');

        let data = JSON.stringify(
            {
                'link': linkInput.value,
                'short_code': customLinkInput.value
            }
        );

        xhr.send(data);

        xhr.onload = function () {
            cleanErrors();
            if (xhr.status === 201) {
                let response = JSON.parse(xhr.response);
                createdShortLinkDiv.style.display = 'inline-block';
                createdShortLinkDiv.innerText = response['data']['short_link'];
                copyButton.style.display = 'inline';

                linksDiv.innerHTML = showLink(response['data']['original_link'], response['data']['short_link'], 0, response['data']['id']) + linksDiv.innerHTML;
            } else if (xhr.status === 400) {
                let response = JSON.parse(xhr.response);
                showErrors(response, 'link');
                showErrors(response, 'short_code');
            } else if (xhr.status === 401) {
                window.location.href = '/login';
            } else {
                showGeneralErrors("Something went wrong! Please try again!");
            }
        }
    });

    document.getElementById('logout').addEventListener('click', function () {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/logout');
        xhr.send();
        xhr.onload = function () {
            window.location.href = '/';
        }
    });

</script>
</body>
</html>