<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href={{asset('css/styles.css')}}>
    <title>LinkMic - registration</title>
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

                                <p class="text-center h1 fw-bold mb-3 mx-1 mx-md-4 mt-4">Sign up</p>

                                <form id="registrationForm" class="mx-1 mx-md-4" method="post">
                                    @csrf
                                    <div class="d-flex flex-row align-items-center mb-3">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" id="firstName" name="firstName" class="form-control"/>
                                            <label class="form-label" for="form3Example1c">First Name</label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-3">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" id="lastName" name="lastName" class="form-control"/>
                                            <label class="form-label" for="form3Example1c">Last Name</label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-3">
                                        <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="email" id="login" name="login" class="form-control"/>
                                            <label class="form-label" for="form3Example3c">Login</label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-3">
                                        <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="password" id="password" name="password" class="form-control"/>
                                            <label class="form-label" for="form3Example4c">Password</label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-3">
                                        <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="password" id="passwordConfirmation"
                                                   name="password_confirmation"
                                                   class="form-control"/>
                                            <label class="form-label" for="form3Example4cd">Repeat your password</label>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="button" id="register" class="btn btn-primary btn-lg">Register
                                        </button>
                                    </div>

                                </form>

                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex flex-column justify-content-center align-items-center order-1 order-lg-2">
                                <div>
                                    <a class="logo text-decoration-none" href="#">LinkMic</a>
                                </div>
                                <div class="mb-5">
                                    <span>Go to the</span>
                                    <a class="text-decoration-none text-black link-primary fw-bold"
                                       href="#">Home Page</a>
                                    <span>or</span>
                                    <a class="text-decoration-none text-black link-primary fw-bold"
                                       href="#">Sign in</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous">
</script>

<script>
    let firstNameInput = document.getElementById('firstName');
    let lastNameInput = document.getElementById('lastName');
    let loginInput = document.getElementById('login');
    let passwordInput = document.getElementById('password');
    let passwordConfirmationInput = document.getElementById('passwordConfirmation');
    let registerButton = document.getElementById('register');
    let tokenInput = document.querySelector('input[name="_token"]');

    registerButton.addEventListener('click', function () {

        let xhr = new XMLHttpRequest();

        xhr.open('POST', '/registration');

        xhr.setRequestHeader('X-CSRF-TOKEN', tokenInput.value);
        xhr.setRequestHeader("content-type", "application/json");

        let data = JSON.stringify({
            'firstName': firstNameInput.value,
            'lastName': lastNameInput.value,
            'login': loginInput.value,
            'password': passwordInput.value,
            'password_confirmation': passwordConfirmationInput.value
        });

        xhr.send(data);

        xhr.onload = function () {
            let response = JSON.parse(xhr.response);
            if (xhr.status >= 200 && xhr.status < 300) {
                window.location.href = '/';
            } else if (xhr.status === 400) {
                alert((response['firstName'] ? response['firstName'] + '\n' : '') +
                    (response['lastName'] ? response['lastName'] + '\n' : '') +
                    (response['login'] ? response['login'] + '\n' : '') +
                    (response['password'] ? response['password'] + '\n' : '') +
                    (response['passwordConfirmation'] ? response['passwordConfirmation'] + '\n' : ''));
            } else {
                alert('Something went wrong...');
            }
        };
    });

</script>

</body>
</html>

