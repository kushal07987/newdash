<aside id="sidebar" class="bg-gray-800 text-white w-64 min-h-screen p-4 flex flex-col items-center">
    <h1 class="text-2xl font-semibold mb-6">Admin</h1>
    <nav class="w-full">
        <ul class="space-y-2">
            <?php
            $menu_items = [
                'Dashboard' => ['index.php', 'fa-tachometer-alt'],
                'Surveys' => ['surveys.php', 'fa-clipboard-list'],
                'Clients' => ['clients.php', 'fa-users'],
                'Vendors' => ['vendors.php', 'fa-building'],
                'Participants' => ['participants.php', 'fa-user-friends'],
                'Settings' => ['settings.php', 'fa-cog'],
                'Client Details' => ['client_details.php', 'fa-cog'],
                'Vendor Details' => ['vendor_details.php', 'fa-cog']
            ];

            foreach ($menu_items as $item => $data) {
                $url = $data[0];
                $icon = $data[1];
                $active = basename($_SERVER['PHP_SELF']) == $url ? 'bg-gray-900' : '';
                echo "<li><a href=\"$url\" class=\"flex items-center py-2 px-4 rounded hover:bg-gray-700 $active\"><i class=\"fas $icon mr-3\"></i>$item</a></li>";
            }
            ?>
        </ul>
    </nav>
</aside>