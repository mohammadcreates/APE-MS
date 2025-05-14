<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get and validate input data
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

$requiredParams = ['fitnessGoal', 'duration', 'difficultyLevel', 'daysPerWeek'];
foreach ($requiredParams as $param) {
    if (empty($input[$param])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required parameter: $param"]);
        exit;
    }
}

// API configuration
define('RAPIDAPI_KEY', '7c9c853ba4mshe18a2599a673e2cp117c8fjsn6bd4902bc155');
define('WORKOUT_API_URL', 'https://ai-workout-planner-exercise-fitness-nutrition-guide.p.rapidapi.com/generateWorkoutPlan');
define('API_TIMEOUT', 30);

try {
    $ch = curl_init();

    // Map our form fields to the API's expected format
    $postData = [
        'goal' => $input['fitnessGoal'],
        'fitness_level' => $input['difficultyLevel'],
        'preferences' => ['Weight training'],
        'health_conditions' => ['None'],
        'schedule' => [
            'days_per_week' => (int) $input['daysPerWeek'],
            'session_duration' => (int) $input['duration']
        ],
        'plan_duration_weeks' => 4,
        'lang' => 'en'
    ];

    // Log the request data for debugging
    error_log('API Request Data: ' . json_encode($postData));

    curl_setopt_array($ch, [
        CURLOPT_URL => WORKOUT_API_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => [
            'X-RapidAPI-Host: ai-workout-planner-exercise-fitness-nutrition-guide.p.rapidapi.com',
            'X-RapidAPI-Key: ' . RAPIDAPI_KEY,
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_TIMEOUT => API_TIMEOUT,
        CURLOPT_FAILONERROR => true,
        CURLOPT_SSL_VERIFYPEER => false // Add this for testing only
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('API request failed: ' . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Log the raw response for debugging
    error_log('Raw API Response: ' . $response);
    error_log('HTTP Status Code: ' . $httpCode);

    // Verify the response is JSON
    if (strpos($response, '<!DOCTYPE') === 0) {
        throw new Exception('API returned HTML instead of JSON. Raw response: ' . $response);
    }

    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON response from API. Raw response: ' . $response);
    }

    // Log the decoded response for debugging
    error_log('Decoded API Response: ' . json_encode($decoded));

    // Check if the response indicates the workout plan is still being generated
    if (isset($decoded['status']) && $decoded['status'] === 'pending') {
        // Wait for 30 seconds before retrying
        sleep(30);

        // Retry the request with the queue ID
        $retryUrl = WORKOUT_API_URL . '?queueId=' . $decoded['queueId'];
        curl_setopt($ch, CURLOPT_URL, $retryUrl);
        curl_setopt($ch, CURLOPT_POST, false); // Change to GET request for retry

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Retry request failed: ' . curl_error($ch));
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from retry request. Raw response: ' . $response);
        }

        // Log the retry response
        error_log('Retry Response: ' . json_encode($decoded));
    }

    // Validate the structure of the decoded response
    if (!isset($decoded['result']) || !isset($decoded['result']['exercises'])) {
        if (isset($decoded['status']) && $decoded['status'] === 'pending') {
            throw new Exception('Workout plan is still being generated. Please try again in a few moments.');
        }
        throw new Exception('Invalid workout plan format received. API Response: ' . json_encode($decoded));
    }

    // Return the validated response
    echo json_encode($decoded);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (is_resource($ch)) {
        curl_close($ch);
    }
}
?>