<?php
$maxlifetime = 0;
$path = '/';
$domain = '';
$secure = false;
$httponly = true;
$samesite = 'lax';

// Used HttpOnly to avoid JavaScript to read the cookie and samesite to lax to limit csrf attacks
session_set_cookie_params($maxlifetime, $path.'; samesite='.$samesite, $domain, $secure, $httponly);
session_start();

// Reference : https://stackoverflow.com/questions/6287903/how-to-properly-add-cross-site-request-forgery-csrf-token-using-php
if (empty($_SESSION['csrf-token'])) {
    $_SESSION['csrf-token'] = bin2hex(openssl_random_pseudo_bytes(32)); // Would use random_bytes if we were using php7
}

require 'functions/authentication.php';

$redirectToMailbox = false;
$error = "";

/* ------------------------------------ *
 * SESSION TESTING & HEADER REDIRECTION *
 * ------------------------------------ */

// Check if user is logged in
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    $redirectToMailbox = true;
}

/* ------------------------------------------------------- *
 * POST VARIABLES TESTING & FUNCTIONALITY REQUEST HANDLING *
 * ------------------------------------------------------- */

// Call the authentication function if the form is submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6Ld4FQwTAAAAAGfqLaIk4FJLztvmrAl93wb5Kyis';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success) {
            if (authentication($_POST['username'], $_POST['password'])) {
                $redirectToMailbox = true;
            } else {
                $error = "Username / password combination is invalid";
            }
        } else {
            $error = 'Robot verification failed, please try again.';
        }
    } else {
        $error = "Please check the CAPTCHA";
    }
}

if ($redirectToMailbox) {
    header ('location: mailbox.php');
    exit();
}
?>

<html lang="en">
<head>
    <title>Login</title>

    <link rel="stylesheet" href="./css/output.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body >
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div>
            <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                Sign in to your account
            </h2>
        </div>
        <form class="mt-8" action="" method="POST">
            <div class="rounded-md shadow-sm">
                <div>
                    <input aria-label="Username" name="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Username">
                </div>
                <div class="-mt-px">
                    <input aria-label="Password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Password">
                </div>
            </div><br />
            <div class="g-recaptcha" data-sitekey="6Ld4FQwTAAAAAFhtfJzM7V7y0RFNCUc_GS3Q-aCE"></div> <br />

            <?php
            if ($error != "") {
                echo '<div><p class="text-red-600 text-xs italic">'. $error .'</p></div>';
            }
            ?>
            <div class="mt-6">
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                      <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                      </span>
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
