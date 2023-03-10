<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Register</title>
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
                                <div class="p-4">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-2">Create an account with us.</h4>
                                        <!-- <button class="btn btn-primary" id="btn1" type="button"><img src="assets/img/google_%201.png" width="24" height="24"><strong><span style="color: rgb(33, 30, 30);">&nbsp; &nbsp;</span></strong><span style="color: rgb(115, 114, 114); font-size: 15px;">Sign up with google</span></button> -->
                                    </div>
                                    
                                    <form class="user" action="register_proc.php" method="POST">
                                        <div class="mb-1">
                                            <input class="form-control" type="text" id="" aria-describedby="emailHelp" placeholder="First Name" name="firstname" style="font-size: 16px;">
                                            <input class="form-control" type="text" id="" aria-describedby="emailHelp" placeholder="Last Name" name="lastname" style="font-size: 16px;">
                                            <input class="form-control" type="email" id="" aria-describedby="emailHelp" placeholder="Email" name="email" style="font-size: 16px;">
                                            <input class="form-control" type="password" placeholder="Password" name="password" style="font-size: 16px;">
                                            <input class="form-control" type="password" placeholder="Re-enter Password" name="password-repeat" style="font-size: 16px;">
                                        </div>
                                        <select name="account-type" class="form-select mb-3" aria-label="Default select example" >
                                            <option disabled selected value >Account Type</option>
                                            <option value="1">Individual</option>
                                            <option value="0">Club / Team</option>
                                        </select>

                                        <button class="btn btn-primary d-block btn-user w-100" id="btn2" type="submit" name="register" value="register">Create Account</button>

                                    </form>
                                    <!-- <p id="forgotten" style="font-size: 14px;">Forgotten your password?&nbsp;<a class="small" href="Login.html">&nbsp;Reset</a></p> -->
                                    <!-- <p id="remember" style="width: 135px;margin-right: 27px;padding-right: 0px;font-size: 14px;"><input type="checkbox">&nbsp;Remember me</p> -->
                                    <p style="padding-right: 20px;margin-right: 236px;"></p>
                                    <div class="text-center">
                                        <p id="p2" style="font-size: 13px;">Already have an account?&nbsp;<a class="small" href="Login.php">&nbsp;Login</a></p>
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