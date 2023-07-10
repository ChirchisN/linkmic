<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href={{asset('css/styles.css')}}>
    <title>Short URL</title>
</head>
<body>
<section>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign in</p>
                                <form class="mx-1 mx-md-4">
                                    @csrf
                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <div id="generalErrors" class="error_text"></div>
                                    </div>
                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" id="login" name="login" class="form-control"/>
                                            <div id="loginError" class="error_text"></div>
                                            <label class="form-label" for="login">Login</label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="password" id="password" name="password" class="form-control"/>
                                            <div id="passwordError" class="error_text"></div>
                                            <label class="form-label" for="password">Password</label>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="button" id="loginButton" class="btn btn-primary btn-lg">Sign in
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex flex-column justify-content-center align-items-center order-1 order-lg-2">
                                <div class="mb-3">
                                    <a class="logo text-decoration-none" href="{{route('home')}}">Short URL</a>
                                </div>
                                <div class="mb-5">
                                    <span>Go to the</span>
                                    <a class="text-decoration-none text-black link-primary fw-bold"
                                       href="{{route('home')}}">Home Page
                                    </a>
                                    <span>or</span>
                                    <a class="text-decoration-none text-black link-primary fw-bold"
                                       href="{{route('registration')}}">Sign
                                        up</a>
                                    <span>page</span>
                                </div>
                                <div>
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp"
                                         class="img-fluid" alt="Sample image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous">
</script>

<script src="{{asset('/js/script.js')}}"></script>

<script>
    document.getElementById('loginButton').addEventListener('click', function () {

        let xhr = new XMLHttpRequest();

        xhr.open('POST', '/login');

        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);
        xhr.setRequestHeader("content-type", "application/json");

        let data = JSON.stringify({
            'login': document.getElementById('login').value,
            'password': document.getElementById('password').value
        });

        xhr.send(data);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                window.location.href = '/';
            } else {
                cleanErrors();

                if (xhr.status === 400) {
                    let response = JSON.parse(xhr.response);
                    showErrors(response, 'login');
                    showErrors(response, 'password');
                } else if (xhr.status === 401) {
                    let response = JSON.parse(xhr.response);
                    showGeneralErrors(response['message']);
                } else {
                    showGeneralErrors("Something went wrong! Please try again!");
                }
            }
        }
    });
</script>
</body>
</html>