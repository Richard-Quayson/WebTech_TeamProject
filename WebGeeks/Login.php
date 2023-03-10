<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/css/signupandlogin.css">
</head>

<body>
    <div class="container" id="cont1">
        <div class="row justify-content-center" id="row1">
            <div class="col-md-9 col-lg-12 col-xl-10" id="col1">
                <div class="card shadow-lg o-hidden border-0 my-5" id="card1">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex" id="col2"><img src="assets/img/picture.png"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-2">Log in into your account.</h4>
                                        <!-- <button class="btn btn-primary" id="btn1" type="button"><img src="assets/img/google_%201.png" width="24" height="24"><strong><span style="color: rgb(33, 30, 30);">&nbsp; &nbsp;</span></strong><span style="color: rgb(115, 114, 114); font-size: 15px;">Sign up with google</span></button> -->
                                    </div>
                                    <form class="user" action="login_proc.php" method="POST">
                                        <div class="mb-3">
                                            <input class="form-control" type="email" id="" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email" style="font-size: 16px;">
                                            <input class="form-control" type="password" placeholder="Password" name="password" style="font-size: 16px;">
                                        </div>
                                        <button type="submit" value="login" class="btn btn-primary d-block btn-user w-100" id="btn2">Sign in</button>

                                    </form>
                                    <p id="forgotten" style="font-size: 14px;">Forgotten your password?&nbsp;<a class="small" href="Reset.php">&nbsp;Reset</a></p>
                                    <p id="remember" style="width: 135px;margin-right: 27px;padding-right: 0px;font-size: 14px;"><input type="checkbox">&nbsp;Remember me</p>
                                    <p style="padding-right: 20px;margin-right: 236px;"></p>
                                    <div class="text-center">
                                        <p id="p2" style="font-size: 13px;">Don't have an account?&nbsp;<a class="small" href="SignUp.php">&nbsp;Create One</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>