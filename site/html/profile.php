<?php
    session_start();

    require_once "functions/humanResources.php";
    require_once "functions/securityUtils.php";

    $passwordNotMatching = 0;
    $newPasswordIsSet    = 0;
    $passwordStrength    = 0;

    /* ------------------------------------ *
     * SESSION TESTING & HEADER REDIRECTION *
     * ------------------------------------ */

    // If the user isn't logged in, he will be redirected to the login page
    if (!isset($_SESSION['username']) || empty($_SESSION['username']) || empty($_SESSION['csrf-token'])) {
        header ('location: login.php');
        exit();
    }
    $token = $_SESSION['csrf-token'];

    /* ------------------------------------------------------- *
     * POST VARIABLES TESTING & FUNCTIONALITY REQUEST HANDLING *
     * ------------------------------------------------------- */

    // Try to set a new password for the user if newPassword and newPasswordConfirmation are set
    if(isset($_POST['newPassword']) && isset($_POST['newPasswordConfirmation'])) {
        // Check if token is valid
        SecurityUtils::verify_csrf_token($_POST['csrf-token']);
        // Check if the password and the confirmation match
        if(checkIfPasswordsMatch($_POST['newPassword'], $_POST['newPasswordConfirmation'])) {
            var_dump($_POST['newPassword']);
            if (SecurityUtils::isPasswordStrong($_POST['newPassword'])) {
                // If they do the password is changed
                changeUserPassword($_SESSION['username'], password_hash($_POST['newPassword'], PASSWORD_DEFAULT));
                $newPasswordIsSet = 1;
            } else {
                $passwordStrength = 1;
            }
        } else {
            $passwordNotMatching = 1;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Profile</title>

    <link href="./css/output.css" rel="stylesheet">
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<div class="flex md:flex-row-reverse flex-wrap">

    <!--Main Content-->
    <div class="w-full md:w-4/5 bg-gray-100">
        <div class="container bg-gray-100 pt-16 px-6">

            <!-- NEW MESSAGE FORM -->
            <div class="shadow rounded-lg mb-4">
                <div class="bg-gray-50 rounded-lg flex flex-col rounded-b-none border-b border-gray-200">
                    <label class="px-8 pt-6 pb-6 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Your profile</label>
                </div>
                <div class="bg-white rounded-lg pt-6 px-8 pb-8  flex flex-col rounded-t-none border-b border-gray-200">
                    <form action="" method="POST">
                        <input type="hidden" name="csrf-token" value="<?php echo $token ?>">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-last-name">
                                    Username
                                </label>
                                <input readonly class="font-medium text-gray-500 appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="grid-zip" type="text" value="<?php echo SecurityUtils::sanitize_output($_SESSION['username']); ?>">
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-last-name">
                                    Role
                                </label>
                                <input readonly class="font-medium text-gray-500 appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="grid-zip" type="text" value="<?php if ($_SESSION['admin'] == 0) { echo 'Collaborator'; } else { echo 'Administrator'; } ?>">
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-password">
                                    New password
                                </label>
                                <input required name="newPassword" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" type="password" placeholder="******************"></input>
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-password">
                                    Confirm password
                                </label>
                                <input required name="newPasswordConfirmation" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" type="password" placeholder="******************"></input>
                                <?php
                                if ($passwordNotMatching) {
                                    echo '<p class="text-red-600 text-xs italic">Passwords do not match</p>';
                                } else if ($passwordStrength) {
                                    echo '<p class="text-indigo-600 text-xs italic">The new password does not match policy (8 car, 1 upper case letter, 1 number and 1 special car)</p>';
                                }
                                if ($newPasswordIsSet) {
                                    echo '<p class="text-indigo-600 text-xs italic">Your new password was successfully changed</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="">
                            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                Change password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <?php include 'fragments/sidebar.php'; ?>
</div>
</body>
</html>
