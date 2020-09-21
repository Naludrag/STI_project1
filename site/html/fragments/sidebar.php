<!--Sidebar-->
<div class="w-full md:w-1/5 bg-gray-900 md:bg-gray-900 px-2 text-center fixed bottom-0 md:pt-8 md:top-0 md:left-0 h-16 md:h-screen md:border-r-4 md:border-gray-600">
    <div class="md:relative mx-auto lg:float-right lg:px-6">
        <ul class="list-reset flex flex-row md:flex-col text-center md:text-left">
            <li class="mr-3 flex-1">
                <a href="profile.php" class="block py-1 md:py-3 pl-1 align-middle text-gray-800 no-underline hover:text-indigo-600 border-b-2 border-gray-800 md:border-gray-900 hover:border-indigo-600">
                    <i class="fas fa-user pr-0 md:pr-3"></i><span class="uppercase pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block"><?php echo $_SESSION['username']; ?></span>
                </a>
            </li>
            <li class="mr-3 flex-1">
                <a href="mailbox.php" class="block py-1 md:py-3 pl-1 align-middle text-gray-800 no-underline hover:text-indigo-600 border-b-2 border-gray-800 md:border-gray-900 hover:border-indigo-600">
                    <i class="fas fa-envelope pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">MailBox</span>
                </a>
            </li>
            <?php if ($_SESSION['admin'] == 1): ?>
                <li class="mr-3 flex-1">
                    <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-800 no-underline hover:text-indigo-600 border-b-2 border-gray-800 md:border-gray-900 hover:border-indigo-600">
                        <i class="fas fa-users pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Manage Users</span>
                    </a>
                </li>
            <?php endif ?>
            <li class="mr-3 flex-1">
                <a href="functions/logout.php" class="block py-1 md:py-3 pl-1 align-middle text-gray-800 no-underline hover:text-indigo-600 border-b-2 border-gray-800 md:border-gray-900 hover:border-indigo-600">
                    <i class="fas fa-power-off  pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>