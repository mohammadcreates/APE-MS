document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('workoutForm');
    const generateBtn = document.getElementById('generateBtn');
    const loading = document.getElementById('loading');
    const results = document.getElementById('results');
    const workoutPlan = document.getElementById('workoutPlan');
    const errorDiv = document.getElementById('error');
    
    const API_TIMEOUT = 30000; // 30 seconds

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset UI
        errorDiv.style.display = 'none';
        results.style.display = 'none';
        loading.style.display = 'block';
        generateBtn.disabled = true;
        
        try {
            const formData = {
                fitnessGoal: document.getElementById('fitnessGoal').value,
                duration: document.getElementById('duration').value,
                difficultyLevel: document.getElementById('difficultyLevel').value,
                daysPerWeek: document.getElementById('daysPerWeek').value
            };
            
            // Validate inputs
            if (formData.daysPerWeek < 1 || formData.daysPerWeek > 7) {
                throw new Error('Days per week must be between 1 and 7');
            }
            
            if (formData.duration < 10 || formData.duration > 180) {
                throw new Error('Duration must be between 10 and 180 minutes');
            }
            
            // Create timeout controller
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), API_TIMEOUT);
            
            const response = await fetch('workout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
                signal: controller.signal
            });
            
            clearTimeout(timeout);
            
            if (!response.ok) {
                const error = await response.json().catch(() => ({
                    error: `HTTP error! status: ${response.status}`
                }));
                throw new Error(error.error || 'API request failed');
            }
            
            const data = await response.json();
            console.log(data);
            
            // Check if we got a valid response structure
            if (!data.result || !data.result.exercises) {
                throw new Error('Invalid workout plan format received');
            }
            
            displayWorkoutPlan(data.result);
            results.style.display = 'block';
            
        } catch (error) {
            console.error('Error:', error);
            let errorMessage = error.message;
            
            if (error.name === 'AbortError') {
                errorMessage = 'Request timed out. Please try again.';
            }
            
            showError(errorMessage);
        } finally {
            loading.style.display = 'none';
            generateBtn.disabled = false;
        }
    });
    
    function displayWorkoutPlan(plan) {
        workoutPlan.innerHTML = '';
        
        if (!plan.exercises || plan.exercises.length === 0) {
            workoutPlan.innerHTML = '<p>No workout plan was generated. Please try different parameters.</p>';
            return;
        }
        
        // Create summary section
        const summary = document.createElement('div');
        summary.className = 'summary';
        summary.innerHTML = `
            <h3>Workout Plan Summary</h3>
            <p><strong>Goal:</strong> ${plan.goal || 'Not specified'}</p>
            <p><strong>Fitness Level:</strong> ${plan.fitness_level || 'Not specified'}</p>
            <p><strong>Duration:</strong> ${plan.schedule.session_duration || 'Not specified'} minutes</p>
            <p><strong>Days per Week:</strong> ${plan.schedule.days_per_week || 'Not specified'}</p>
            <p><strong>Plan Duration:</strong> ${plan.total_weeks || 'Not specified'} weeks</p>
        `;
        workoutPlan.appendChild(summary);
        
        // Add workout days
        plan.exercises.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.className = 'workout-day';
            
            dayElement.innerHTML = `<h3>${day.day || 'Day'}</h3>`;
            
            if (day.exercises && day.exercises.length > 0) {
                day.exercises.forEach(exercise => {
                    const exerciseHTML = `
                        <div class="exercise">
                            <h4>${exercise.name || 'Exercise'}</h4>
                            ${exercise.sets ? `<p><strong>Sets:</strong> ${exercise.sets}</p>` : ''}
                            ${exercise.repetitions ? `<p><strong>Reps:</strong> ${exercise.repetitions}</p>` : ''}
                            ${exercise.duration ? `<p><strong>Duration:</strong> ${exercise.duration}</p>` : ''}
                            ${exercise.equipment ? `<p><strong>Equipment:</strong> ${exercise.equipment}</p>` : ''}
                        </div>
                    `;
                    dayElement.innerHTML += exerciseHTML;
                });
            } else {
                dayElement.innerHTML += '<p>Rest day or no exercises specified.</p>';
            }
            
            workoutPlan.appendChild(dayElement);
        });
    }
    
    function showError(message) {
        errorDiv.innerHTML = `
            <strong>Error:</strong> ${message}
            <p>Please check your inputs and try again.</p>
        `;
        errorDiv.style.display = 'block';
    }
});