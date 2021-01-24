<?php
    session_start();

    $users               = null;
    $passwordNotMatching = 0;
    $usernameAlreadyUsed = 0;
    $passwordStrength    = 0;

    require_once "functions/humanResources.php";
    require_once "functions/dashboardManager.php";
    require_once "functions/securityUtils.php";

    /* ------------------------------------ *
     * SESSION TESTING & HEADER REDIRECTION *
     * ------------------------------------ */
    // Check if user is logged in
    if (isset($_SESSION['username']) && !empty($_SESSION['username']) && !empty($_SESSION['csrf-token'])) {
        // Check if user is an administrator
        if (isset($_SESSION['admin']) && !empty($_SESSION['admin']) && $_SESSION['admin'] == 1) {

            // Retrieve the users and remove the current user from the users list
            $users = retrieveUsers(0);
            $size = count($users, COUNT_NORMAL);
            for ($i = 0; $i < $size; $i++) {
                if($users[$i]['username'] == $_SESSION['username']) {
                    unset($users[$i]);
                    break;
                }
            }

        } else {
            // If the user isn't an administrator, he will be redirected to the mailbox page
            header ('location: mailbox.php');
            exit();
        }
    } else {
        // If the user isn't logged in, he will be redirected to the login page
        header ('location: login.php');
        exit();
    }
    $token = $_SESSION['csrf-token'];


/* ------------------------------------------------------- *
 * POST VARIABLES TESTING & FUNCTIONALITY REQUEST HANDLING *
 * ------------------------------------------------------- */

    // Check if a user creation was requested
    if(isset($_POST['username']) &&
       isset($_POST['password']) &&
       isset($_POST['passwordConfirmation']) &&
       isset($_POST['validity']) &&
       isset($_POST['role'])) {
        SecurityUtils::verify_csrf_token($_POST['csrf-token']);
        // Check if the username already exist and if the the passwords match
        if (isUsernameUsed($_POST['username'])) {
            $usernameAlreadyUsed = 1;
        } elseif(!checkIfPasswordsMatch($_POST['password'], $_POST['passwordConfirmation'])) {
            $passwordNotMatching = 1;
        } else {
            if (SecurityUtils::isPasswordStrong($_POST['password'])) {
                addUser(SecurityUtils::sanitize_for_db($_POST['username']), password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['validity'], $_POST['role']);
            } else {
                $passwordStrength = 1;
            }
        }
    }

    // Check if a role change was requested
    if (isset($_POST['changeRoleUsername'])&&!empty($_POST['changeRoleUsername'])
        &&isset($_POST['changeRoleCurrent'])) {
        SecurityUtils::verify_csrf_token($_POST['csrf-token']);
        changeRole($_POST['changeRoleUsername'], $_POST['changeRoleCurrent']);
    }

    // Check if a validity change was requested
    if (isset($_POST['changeValidityUsername'])&&!empty($_POST['changeValidityUsername'])
        &&isset($_POST['changeValidityCurrent'])) {
        SecurityUtils::verify_csrf_token($_POST['csrf-token']);
        changeValidity($_POST['changeValidityUsername'], $_POST['changeValidityCurrent']);
    }

    // Check if a password change was requested
    if (isset($_POST['username']) && isset($_POST['newPassword']) && isset($_POST['newPasswordConfirmation'])) {
        SecurityUtils::verify_csrf_token($_POST['csrf-token']);
        // Check if the password and the confirmation match
        if (checkIfPasswordsMatch($_POST['newPassword'], $_POST['newPasswordConfirmation'])) {
            if (SecurityUtils::isPasswordStrong($_POST['newPassword'])) {
                // If they do the password is changed
                changeUserPassword($_POST['username'], password_hash($_POST['newPassword'], PASSWORD_DEFAULT));
            } else {
                $passwordStrength = 1;
            }
        } else {
            $passwordNotMatching = 1;
        }
    }

    // Check if a user deletion was requested
    if (isset($_POST['deleteUser'])&&!empty($_POST['deleteUser'])) {
        SecurityUtils::verify_csrf_token($_POST['csrf-token']);
        deleteUser($_POST['deleteUser']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Admin Dashboard</title>

    <link href="./css/output.css" rel="stylesheet">
    <link href="./css/toggleButton.css" rel="stylesheet">
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">


    <script type="text/javascript">
        function toggle_visibility_row(className,callerId, firstText, secondText) {

            let elements = document.getElementsByClassName(className);
            for(let i = 0; i < elements.length; i++){
                elements[i].style.display = elements[i].style.display === 'table-row' ? 'none' : 'table-row';
            }

            if(callerId != null){
                let caller = document.getElementById(callerId);
                if(caller.innerText === firstText){
                    caller.innerText = secondText;
                } else {
                    caller.innerText = firstText;
                }
            }
        }

        function toggle_visibility(className) {
            let elements = document.getElementsByClassName(className);
            for(let i = 0; i < elements.length; i++){
                elements[i].style.display = elements[i].style.display === 'block' ? 'none' : 'block';
            }
        }
    </script>

</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<div class="flex md:flex-row-reverse flex-wrap">

    <!--Main Content-->
    <div class="w-full md:w-4/5 bg-gray-100">
        <div class="container bg-gray-100 pt-16 px-6">

            <!-- INFO MESSAGE -->
            <?php
            if ($usernameAlreadyUsed) {
                echo '<p class="text-red-600 text-xs italic mb-6">The username \'' . SecurityUtils::sanitize_output($_POST['username)']) . '\' is already used.</p>';
            }
            if ($passwordNotMatching) {
                echo '<p class="text-red-600 text-xs italic mb-6">The new passwords for ' . SecurityUtils::sanitize_output($_POST['username']) . ' do not match.</p>';
            }
            if ($passwordStrength) {
                echo '<p class="text-red-600 text-xs italic mb-6">The new password does not match policy (8 car, 1 upper case letter, 1 number and 1 special car) for ' . SecurityUtils::sanitize_output($_POST['username']). ' </p>';
            }
            ?>

            <!-- ADD USER -->
            <div class="shadow rounded-lg mb-4">
                <div class="bg-gray-50 rounded-lg flex flex-col rounded-b-none border-b border-gray-200 hover:border-gray-400">
                    <a onclick="toggle_visibility('AddingZone')" class="rounded-lg rounded-b-none px-8 pt-6 pb-6 hover:bg-gray-200 hover:border-gray-400 text-left text-xs leading-4 font-medium text-gray-500 hover:text-gray-700 uppercase tracking-wider">
                        Add a new user
                    </a>
                </div>
                <div style="display:none" class="AddingZone bg-white rounded-lg pt-6 px-8 pb-8 flex flex-col rounded-t-none border-b border-gray-200">
                    <form action="" method="POST">
                        <input type="hidden" name="csrf-token" value="<?php echo $token ?>">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-last-name">
                                    Username
                                </label>
                                <input required name="username" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" type="text" placeholder="A sweet name for our newcomer?">
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-password">
                                    Password
                                </label>
                                <input required name="password" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" type="password" placeholder="******************"></input>
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-password">
                                    Confirm password
                                </label>
                                <input required name="passwordConfirmation" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" type="password" placeholder="******************"></input>
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-last-name">
                                    Validity
                                </label>
                                <select required name="validity" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" type="text" placeholder="What's the object of your email?">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-last-name">
                                    Role
                                </label>
                                <select required name="role" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" type="text" placeholder="What's the object of your email?">
                                    <option value="0">Collaborator</option>
                                    <option value="1">Administrator</option>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <!-- USERS LIST -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Username
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Validity
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($users as $user):
                                    // !!! Sanitize all attributes that are user input !!!
                                    $user['username'] = SecurityUtils::sanitize_output($user['username']); //Only username is a potential "user input"
                                ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900"><?php echo $user['username']; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <form method="POST">
                                                <input type="hidden" name="csrf-token" value="<?php echo $token ?>">
                                                <input type="hidden" name="changeRoleUsername" value="<?php echo $user['username']; ?>">
                                                <input type="hidden" name="changeRoleCurrent" value="<?php echo $user['admin']; ?>">
                                                <button class="bg-transparent hover:bg-<?php echo adaptRoleColor($user['admin']) ?>-500 text-<?php echo adaptRoleColor($user['admin']) ?>-700 font-semibold hover:text-white ?> py-2 px-4 border border-<?php echo adaptRoleColor($user['admin']) ?>-500 hover:border-transparent rounded">
                                                    <?php echo adaptRoleText($user['admin']) ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <form action="" method="POST" class="m-0">
                                                <input type="hidden" name="csrf-token" value="<?php echo $token ?>">
                                                <input type="hidden" name="changeValidityUsername" value="<?php echo $user['username']; ?>">
                                                <input type="hidden" name="changeValidityCurrent" value="<?php echo $user['validity']; ?>">
                                                <button class="bg-transparent hover:bg-<?php echo adaptValidityColor($user['validity']) ?>-500 text-<?php echo adaptValidityColor($user['validity']) ?>-700 font-semibold hover:text-white ?> py-2 px-4 border border-<?php echo adaptValidityColor($user['validity']) ?>-500 hover:border-transparent rounded">
                                                    <?php echo adaptValidityText($user['validity']) ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <button id="changePwd<?php echo $user['username']; ?>-btn" onclick="toggle_visibility_row('changePwd<?php echo $user['username']; ?>Body', 'changePwd<?php echo $user['username']; ?>-btn', 'Change password', 'Cancel');" class=" bg-transparent hover:bg-blue-500 active:bg-blue-500 text-blue-700 font-semibold hover:text-white active:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                Change password
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <form action="" method="POST" class="m-0">
                                                <input type="hidden" name="csrf-token" value="<?php echo $token ?>">
                                                <input type="hidden" name="deleteUser" value="<?php echo $user['username']; ?>">
                                                <button type="submit" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <!-- RESPOND MAIL -->
                                    <tr style="display: none;" class="changePwd<?php echo $user['username']; ?>Body">
                                        <td colspan="6">
                                            <form action="" method="POST" class="pt-6 px-8 flex flex-col">
                                                <input type="hidden" name="csrf-token" value="<?php echo $token ?>">
                                                <div class="-mx-3 md:flex mb-6">
                                                    <div class="md:w-full px-3">
                                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-password">
                                                            Password
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
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <input type="hidden" name="username" value="<?php echo $user['username']; ?>">
                                                    <button type="submit" class="group relative w-full flex justify-center mb-6 py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                                        Change password
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include 'fragments/sidebar.php'; ?>
</div>
</body>
</html>
