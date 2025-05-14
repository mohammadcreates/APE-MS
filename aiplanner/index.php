<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Workout Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

<body>
    <div>
        <a class="btn" id="backarrow" href="../dashboard.php">←</a>
    </div>

    <div class="main-content">
        <div class="container">
            <header>
                <h1 class="title">AI Workout Plan Generator</h1>
                <p>Get a personalized workout plan based on your goals</p>
            </header>

            <main>
                <form id="workoutForm">
                    <div class="form-group">
                        <label for="fitnessGoal">Fitness Goal</label>
                        <select id="fitnessGoal" required>
                            <option value="">Select your goal</option>
                            <option value="weightLoss">Lose Weight</option>
                            <option value="muscleGain">Gain Muscle</option>
                            <option value="endurance">Improve Fitness</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="duration">Session Duration (minutes)</label>
                        <input type="number" id="duration" min="10" max="180" value="30" required>
                        <small class="hint">10-180 minutes</small>
                    </div>

                    <div class="form-group">
                        <label for="difficultyLevel">Difficulty Level</label>
                        <select id="difficultyLevel" required>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate" selected>Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="daysPerWeek">Days Per Week</label>
                        <input type="number" id="daysPerWeek" min="1" max="7" value="3" required>
                        <small class="hint">1-7 days</small>
                    </div>

                    <button type="submit" id="generateBtn">Generate Workout Plan</button>
                </form>

                <div id="loading" class="loading" style="display: none;">
                    <div class="spinner"></div>
                    <p>Generating your personalized workout plan...</p>
                </div>

                <div id="results" class="results" style="display: none;">
                    <h2>Your Personalized Workout Plan</h2>
                    <div id="workoutPlan" s></div>
                </div>

                <div id="error" class="error" style="display: none;"></div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>