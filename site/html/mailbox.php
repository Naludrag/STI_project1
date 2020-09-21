<?php
    session_start();

    $activeUsers = null;
    $mails       = null;
    $mailCounter = 0;

    require_once "functions/humanResources.php";
    require_once "functions/mailman.php";

    if(isset($_SESSION['username'])&&!empty($_SESSION['username'])){
        $activeUsers = retrieveUsers(1);
        $mails       = retrieveMail($_SESSION['username']);
    } else {
        // If the user isn't logged in, he will be redirected to the login page
        header ('location: login.php');
        exit();
    }

    // If receiver, object and body are set, we have to send a message
    echo 'before check sending'; // DEBUG
    if(isset($_POST['receiver']) && isset($_POST['object']) && isset($_POST['body'])){

        echo 'after check sending, before insert'; // DEBUG
        echo $_SESSION['username'].$_POST['receiver'].$_POST['object']. $_POST['body']; // DEBUG

        sendMail($_SESSION['username'], $_POST['receiver'], $_POST['object'], $_POST['body']);

        echo 'after insert'; // DEBUG
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>Mailbox</title>

        <link href="./css/output.css" rel="stylesheet">
        <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

        <script type="text/javascript">
            function toggle_visibility_row(className,callerId, firstText, secondText) {
                let baseTextIsShown = true

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

                <!-- NEW MESSAGE FORM -->
                <div class="shadow rounded-lg mb-4">
                    <div class="bg-gray-50 rounded-lg flex flex-col rounded-b-none border-b border-gray-200 hover:border-gray-400">
                        <a onclick="toggle_visibility('writingZone')" class="rounded-lg rounded-b-none px-8 pt-6 pb-6 hover:bg-gray-200 hover:border-gray-400 text-left text-xs leading-4 font-medium text-gray-500 hover:text-gray-700 uppercase tracking-wider">
                            Write a new message
                        </a>
                    </div>
                    <div style="display:none" class="writingZone bg-white rounded-lg pt-6 px-8 pb-8 flex flex-col rounded-t-none border-b border-gray-200">
                        <form action="" method="POST">
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-full px-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-last-name">
                                        Receiver
                                    </label>
                                    <datalist id="contacts">
                                        <?php foreach($activeUsers as $activeUser): ?>
                                        <option><?php echo $activeUser['username']; ?></option>
                                        <?php endforeach; ?>
                                    </datalist>
                                    <input name="receiver" autoComplete="on" list="contacts" class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded" placeholder="Who is the lucky one?"/>
                                    <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-full px-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-last-name">
                                        Object
                                    </label>
                                    <input name="object" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="grid-zip" type="text" placeholder="What's the object of your email?">
                                </div>
                            </div>
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-full px-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-password">
                                        Body
                                    </label>
                                    <textarea name="body" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" id="grid-password" type="password" placeholder="Write to your heart's content!"></textarea>
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                      <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                      </span>
                                    Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <!-- MESSAGES LIST -->
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Subject
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Sender
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach($mails as $mail): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    <div class="text-sm leading-5 text-gray-900"><?php echo $mail['receptionDate']; ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    <div class="text-sm leading-5 text-gray-900"><?php echo $mail['object']; ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    <div class="text-sm leading-5 text-gray-900"><?php echo $mail['fk_sender']; ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    <button id="showMessage<?php echo ++$mailCounter; ?>-btn" onclick="toggle_visibility_row('mail<?php echo $mailCounter; ?>Body', 'showMessage<?php echo $mailCounter; ?>-btn', 'Show', 'Hide');" class=" bg-transparent hover:bg-green-500 active:bg-green-500 text-green-700 font-semibold hover:text-white active:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                                        Show
                                                    </button>
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    <button id="RespondToMessage<?php echo $mailCounter; ?>-btn" onclick="toggle_visibility_row('respondToMail<?php echo $mailCounter; ?>Body', 'RespondToMessage<?php echo $mailCounter; ?>-btn', 'Respond', 'Cancel');" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                        Respond
                                                    </button>
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap">
                                                    <button class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <!-- SHOW/HIDE MAIL -->
                                            <tr style="display: none;" class="mail<?php echo $mailCounter; ?>Body">
                                                <td colspan="6">
                                                    <div style="display: none;" class="mail<?php echo $mailCounter; ?>Body max-w-sm w-full lg:max-w-full lg:flex">
                                                        <div class="p-4 flex flex-col justify-between leading-normal">
                                                            <div class="mb-8">
                                                                <p class="text-sm text-gray-600 flex items-center">
                                                                    <?php echo $mail['receptionDate']; ?>
                                                                </p>
                                                                <div class="text-gray-900 font-bold text-xl mb-2"><?php echo $mail['object']; ?></div>
                                                                <p class="text-gray-700 text-base"><?php echo $mail['body']; ?></p>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <div class="text-sm">
                                                                    <p class="text-gray-600"><?php echo $mail['fk_sender']; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- RESPOND MAIL -->
                                            <tr style="display: none;" class="respondToMail<?php echo $mailCounter; ?>Body">
                                                <td colspan="6">
                                                    <div style="display: none;" class="respondToMail<?php echo $mailCounter; ?>Body max-w-sm w-full lg:max-w-full lg:flex">
                                                        <div class="p-4 flex justify-between leading-normal">
                                                            <div class="mb-8">
                                                                <form action="" method="POST">
                                                                    <div class="-mx-3 md:flex mb-6">
                                                                        <div class="md:w-full px-3">
                                                                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-password">
                                                                                Body
                                                                            </label>
                                                                            <textarea class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" id="grid-password" type="password" placeholder="Write to your heart's content!"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="">
                                                                        <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                                                          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                                                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
                                                                              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                                                            </svg>
                                                                          </span>
                                                                            Send
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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

        <!--Sidebar-->
        <div class="w-full md:w-1/5 bg-gray-900 md:bg-gray-900 px-2 text-center fixed bottom-0 md:pt-8 md:top-0 md:left-0 h-16 md:h-screen md:border-r-4 md:border-gray-600">
            <div class="md:relative mx-auto lg:float-right lg:px-6">
                <ul class="list-reset flex flex-row md:flex-col text-center md:text-left">
                    <li class="mr-3 flex-1">
                        <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-800 no-underline hover:text-pink-500 border-b-2 border-gray-800 md:border-gray-900 hover:border-pink-500">
                            <i class="fas fa-link pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Link</span>
                        </a>
                    </li>
                    <li class="mr-3 flex-1">
                        <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-800 no-underline hover:text-pink-500 border-b-2 border-gray-800 md:border-gray-900 hover:border-pink-500">
                            <i class="fas fa-link pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Link</span>
                        </a>
                    </li>
                    <li class="mr-3 flex-1">
                        <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-pink-600">
                            <i class="fas fa-link pr-0 md:pr-3 text-pink-500"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-white md:font-bold block md:inline-block">Active Link</span>
                        </a>
                    </li>
                    <li class="mr-3 flex-1">
                        <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-800 no-underline hover:text-pink-500 border-b-2 border-gray-800 md:border-gray-900 hover:border-pink-500">
                            <i class="fas fa-link pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Link</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </body>
</html>