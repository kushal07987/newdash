<h2 class="text-2xl font-semibold mb-4">Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php
    $cards = [
        ['title' => 'Clients', 'value' => 150],
        ['title' => 'Projects', 'value' => 75],
        ['title' => 'Live', 'value' => 30],
        ['title' => 'On-hold', 'value' => 5]
    ];

    foreach ($cards as $card) {
        echo "
        <div class=\"bg-white p-6 rounded-lg shadow-md\">
            <h3 class=\"text-xl font-semibold mb-2\">{$card['title']}</h3>
            <p class=\"text-3xl font-bold\">{$card['value']}</p>
        </div>
        ";
    }
    ?>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Client</th>
                <th class="py-3 px-6 text-left">Project Name</th>
                <th class="py-3 px-6 text-left">Start Date</th>
                <th class="py-3 px-6 text-left">Completes</th>
                <th class="py-3 px-6 text-left">Status</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            <?php
            $projects = [
                ['client' => 'ABC Corp', 'name' => 'Market Research', 'start_date' => '2023-05-01', 'completes' => 500, 'status' => 'Active'],
                ['client' => 'XYZ Inc', 'name' => 'Customer Satisfaction', 'start_date' => '2023-04-15', 'completes' => 250, 'status' => 'On-hold'],
                ['client' => '123 Industries', 'name' => 'Product Feedback', 'start_date' => '2023-05-10', 'completes' => 100, 'status' => 'Active'],
            ];

            foreach ($projects as $project) {
                echo "
                <tr class=\"border-b border-gray-200 hover:bg-gray-100\">
                    <td class=\"py-3 px-6 text-left whitespace-nowrap\">{$project['client']}</td>
                    <td class=\"py-3 px-6 text-left\">{$project['name']}</td>
                    <td class=\"py-3 px-6 text-left\">{$project['start_date']}</td>
                    <td class=\"py-3 px-6 text-left\">{$project['completes']}</td>
                    <td class=\"py-3 px-6 text-left\">{$project['status']}</td>
                </tr>
                ";
            }
            ?>
        </tbody>
    </table>
</div>