<?php
require_once 'db_connect.php';

$conn = getDBConnection();

if (!$conn) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

function generateProjectId() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $projectId = '';
    for ($i = 0; $i < 8; $i++) {
        $projectId .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $projectId;
}

function generateSurveyId() {
    return str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    
    if (isset($_POST['generate_redirects'])) {
        $client_name = $_POST['client_name'] ?? '';
        $project_name = $_POST['project_name'] ?? '';

        if (empty($client_name) || empty($project_name)) {
            echo json_encode(['error' => 'Client Name and Project Name are required to generate redirects.']);
            exit;
        }

        $project_id = generateProjectId();
        $survey_id = generateSurveyId();

        try {
            $stmt = $conn->prepare("INSERT INTO projects (project_id, survey_id, project_name, client_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $project_id, $survey_id, $project_name, $client_name);
            $stmt->execute();
            $stmt->close();

            echo json_encode(['project_id' => $project_id, 'survey_id' => $survey_id]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to generate redirects: ' . $e->getMessage()]);
        }
        exit;
    } elseif (isset($_POST['save_project'])) {
        try {
            $project_id = $_POST['project_id'];
            $survey_id = $_POST['survey_id'];
            $project_name = $_POST['project_name'];
            $client_name = $_POST['client_name'];
            $client_test_link = $_POST['client_test_link'];
            $client_main_link = $_POST['client_main_link'];
            $complete_redirect = $_POST['complete_redirect'];
            $terminate_redirect = $_POST['terminate_redirect'];
            $quota_full_redirect = $_POST['quota_full_redirect'];
            $main_link = $_POST['main_link'];
            $variables = $_POST['variables'];

            $stmt = $conn->prepare("INSERT INTO projects (project_id, survey_id, project_name, client_name, client_test_link, client_main_link, complete_redirect, terminate_redirect, quota_full_redirect, main_link, variables) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE project_name = VALUES(project_name), client_name = VALUES(client_name), client_test_link = VALUES(client_test_link), client_main_link = VALUES(client_main_link), complete_redirect = VALUES(complete_redirect), terminate_redirect = VALUES(terminate_redirect), quota_full_redirect = VALUES(quota_full_redirect), main_link = VALUES(main_link), variables = VALUES(variables)");
            
            $stmt->bind_param("sssssssssss", $project_id, $survey_id, $project_name, $client_name, $client_test_link, $client_main_link, $complete_redirect, $terminate_redirect, $quota_full_redirect, $main_link, $variables);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Project saved successfully']);
            } else {
                throw new Exception($stmt->error);
            }
            $stmt->close();
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to save project: ' . $e->getMessage()]);
        }
        exit;
    }
}

// Fetch existing client names for autocomplete
try {
    $stmt = $conn->prepare("SELECT DISTINCT client_name FROM projects");
    $stmt->execute();
    $result = $stmt->get_result();
    $client_names = [];
    while ($row = $result->fetch_assoc()) {
        $client_names[] = $row['client_name'];
    }
    $stmt->close();
} catch (Exception $e) {
    // Log the error, but don't expose it to the client
    error_log('Failed to fetch client names: ' . $e->getMessage());
    $client_names = [];
}
?>

<form id="surveyForm" class="space-y-6">
    <!-- Project Details Section -->
    <section class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h3 class="text-lg font-semibold mb-4">Project Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h4 class="text-md font-semibold mb-2">Project ID</h4>
                <p id="project-id" class="text-xl"></p>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <h4 class="text-md font-semibold mb-2">Survey ID</h4>
                <p id="survey-id" class="text-xl"></p>
            </div>
        </div>
        <input type="hidden" name="project_id" id="project_id_input">
        <input type="hidden" name="survey_id" id="survey_id_input">
        <div class="mt-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="project-name">
                Project Name
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="project-name" name="project_name" type="text" required>
        </div>
    </section>

    <!-- Client Details Section -->
    <section class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h3 class="text-lg font-semibold mb-4">Client Details</h3>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="client-name">
            Client Name
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="client-name" name="client_name" type="text" required list="client-list">
        <datalist id="client-list">
            <?php foreach ($client_names as $name): ?>
                <option value="<?php echo htmlspecialchars($name); ?>">
            <?php endforeach; ?>
        </datalist>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="client-test-link">
            Client Test Link
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="client-test-link" name="client_test_link" type="text">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="client-main-link">
            Client Main Link
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="client-main-link" name="client_main_link" type="text">
    </div>
    <button type="button" id="generate_redirects" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Generate Redirects
    </button>
    </section>

    <!-- Variables Section -->
    <section class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h3 class="text-lg font-semibold mb-4">Variables</h3>
        <div class="flex items-center mb-2">
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" id="variable-name" type="text" placeholder="Variable Name">
            <button id="add-variable" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                Add
            </button>
        </div>
        <div id="variables-list" class="mt-2"></div>
        <input type="hidden" name="variables" id="variables-input">
    </section>

    <!-- Redirects Section -->
    <section class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h3 class="text-lg font-semibold mb-4">Redirects</h3>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="complete-redirect">
                Complete
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="complete-redirect" name="complete_redirect" type="text" readonly>
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="terminate-redirect">
                Terminate
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="terminate-redirect" name="terminate_redirect" type="text" readonly>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="quota-full-redirect">
                Quota-Full
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="quota-full-redirect" name="quota_full_redirect" type="text" readonly>
        </div>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button" onclick="copyAllLinks()">
            Copy All Links
        </button>
    </section>

    <!-- Main Link Section -->
    <section class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h3 class="text-lg font-semibold mb-4">Main Link</h3>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="main-link" name="main_link" type="text" readonly>
    </section>

    <div class="flex items-center justify-between">
        <button id="save_project" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
            Save Project
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const generateRedirectsBtn = document.getElementById('generate_redirects');
    const projectNameInput = document.getElementById('project-name');
    const clientNameInput = document.getElementById('client-name');
    const addVariableBtn = document.getElementById('add-variable');
    const variableNameInput = document.getElementById('variable-name');
    const variablesList = document.getElementById('variables-list');
    const mainLinkInput = document.getElementById('main-link');
    const variablesInput = document.getElementById('variables-input');

    const baseUrl = 'https://example.com';

    function generateRedirects() {
        const surveyId = document.getElementById('survey-id').textContent;
        const variables = Array.from(variablesList.children).map(child => child.querySelector('.variable-name').textContent.trim());
        const variableString = variables.map(v => `${v}=XXXX`).join('&');

        const redirects = {
            complete: `${baseUrl}/${surveyId}?status=complete&${variableString}`,
            terminate: `${baseUrl}/${surveyId}?status=terminate&${variableString}`,
            quotaFull: `${baseUrl}/${surveyId}?status=quotafull&${variableString}`
        };

        Object.entries(redirects).forEach(([key, value]) => {
            const element = document.getElementById(`${key.replace(/([A-Z])/g, '-$1').toLowerCase()}-redirect`);
            if (element) {
                element.value = value;
            }
        });

        console.log('Redirects generated successfully');
        alert('Redirects have been generated successfully.');

        updateMainLink();
    }

    function updateMainLink() {
        const surveyId = document.getElementById('survey-id').textContent;
        const variables = Array.from(variablesList.children).map(child => child.querySelector('.variable-name').textContent.trim());
        const variableString = variables.map(v => `${v}=XXXX`).join('&');
        
        mainLinkInput.value = `${baseUrl}/${surveyId}?${variableString}`;
        console.log('Main Link updated:', mainLinkInput.value);

        // Update hidden input with variables
        variablesInput.value = JSON.stringify(variables);
    }

    function addVariable() {
        const variableName = variableNameInput.value.trim();
        
        if (variableName) {
            const variableElement = document.createElement('div');
            variableElement.className = 'mb-2 flex items-center';
            
            const nameSpan = document.createElement('span');
            nameSpan.textContent = variableName;
            nameSpan.className = 'variable-name mr-2';
            
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'Remove';
            removeBtn.className = 'ml-2 text-red-500 hover:text-red-700';
            removeBtn.onclick = function() {
                variablesList.removeChild(variableElement);
                updateMainLink();
            };
            
            variableElement.appendChild(nameSpan);
            variableElement.appendChild(removeBtn);
            variablesList.appendChild(variableElement);
            variableNameInput.value = '';
            updateMainLink();
        }
    }

    generateRedirectsBtn.addEventListener('click', function() {
        if (!projectNameInput.value || !clientNameInput.value) {
            alert('Please fill in both Project Name and Client Name before generating redirects.');
            return;
        }
        
        const formData = new FormData();
        formData.append('generate_redirects', '1');
        formData.append('project_name', projectNameInput.value);
        formData.append('client_name', clientNameInput.value);

        fetch('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            document.getElementById('project-id').textContent = data.project_id;
            document.getElementById('project_id_input').value = data.project_id;
            document.getElementById('survey-id').textContent = data.survey_id;
            document.getElementById('survey_id_input').value = data.survey_id;
            
            generateRedirects();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`An error occurred: ${error.message}`);
        });
    });

    addVariableBtn.addEventListener('click', addVariable);

    // Initialize main link
    updateMainLink();

    // Handle form submission
    document.getElementById('save_project').addEventListener('click', function(e) {
        e.preventDefault();
        const form = document.getElementById('surveyForm');
        const formData = new FormData(form);
        formData.append('save_project', '1');

        fetch('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            alert(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`An error occurred: ${error.message}`);
        });
    });
});

function copyAllLinks() {
    const redirects = ['complete', 'terminate', 'quota-full'].map(type => {
        const element = document.getElementById(`${type}-redirect`);
        return element ? `${type.charAt(0).toUpperCase() + type.slice(1)}: ${element.value}` : '';
    }).filter(Boolean);

    const mainLinkElement = document.getElementById('main-link');
    if (mainLinkElement) {
        redirects.push(`Main Link: ${mainLinkElement.value}`);
    }

    const clientTestLinkElement = document.getElementById('client-test-link');
    if (clientTestLinkElement) {
        redirects.push(`Client Test Link: ${clientTestLinkElement.value}`);
    }

    const clientMainLinkElement = document.getElementById('client-main-link');
    if (clientMainLinkElement) {
        redirects.push(`Client Main Link: ${clientMainLinkElement.value}`);
    }

    const linksToCopy = redirects.join('\n');

    navigator.clipboard.writeText(linksToCopy)
        .then(() => alert('All links have been copied to clipboard!'))
        .catch(err => {
            console.error('Could not copy text: ', err);
            alert('Failed to copy links. Please try again.');
        });
}
</script>