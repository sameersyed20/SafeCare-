// Add this code to your existing JavaScript files or create a new one

document.addEventListener('DOMContentLoaded', function() {
    // Family Card Form AJAX submission
    const familyCardForm = document.getElementById('family-card-form');
    if (familyCardForm) {
        familyCardForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation (you can keep your existing validation)
            let isValid = validateForm(familyCardForm);
            
            if (isValid) {
                // Create FormData object
                const formData = new FormData(familyCardForm);
                
                // Send AJAX request
                fetch('family_card_process.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Show success message
                        showSuccessMessage(familyCardForm, 'family-card', data.application_id);
                    } else {
                        // Show error message
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    }
    
    // Appointment Form AJAX submission
    const appointmentForm = document.getElementById('checkup-form');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            let isValid = validateForm(appointmentForm);
            
            if (isValid) {
                // Create FormData object
                const formData = new FormData(appointmentForm);
                
                // Send AJAX request
                fetch('appointment_process.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Show success message
                        showSuccessMessage(appointmentForm, 'appointment', data.booking_id);
                    } else {
                        // Show error message
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    }
    
    // Insurance Query Form AJAX submission
    const insuranceForm = document.getElementById('insurance-query-form');
    if (insuranceForm) {
        insuranceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            let isValid = validateForm(insuranceForm);
            
            if (isValid) {
                // Create FormData object
                const formData = new FormData(insuranceForm);
                
                // Send AJAX request
                fetch('insurance_process.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Show success message
                        showSuccessMessage(insuranceForm, 'insurance', data.query_id);
                    } else {
                        // Show error message
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    }
    
    // Form validation function
    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                
                // Add error message if it doesn't exist
                const errorMessage = field.parentElement.querySelector('.error-message');
                if (!errorMessage) {
                    const message = document.createElement('p');
                    message.classList.add('error-message');
                    message.style.color = '#d32f2f';
                    message.style.fontSize = '0.85rem';
                    message.style.marginTop = '5px';
                    message.textContent = 'This field is required';
                    field.parentElement.appendChild(message);
                }
            } else {
                field.classList.remove('error');
                
                // Remove error message if it exists
                const errorMessage = field.parentElement.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.remove();
                }
            }
        });
        
        return isValid;
    }
    
    // Show success message function
    function showSuccessMessage(form, type, referenceId) {
        let successMessage = '';
        
        switch (type) {
            case 'family-card':
                successMessage = `
                    <div class="success-message">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #4caf50; margin-bottom: 20px;"></i>
                        <h3>Application Submitted Successfully!</h3>
                        <p>Thank you for applying for the SafeCare Family Card. We have received your application and will process it shortly.</p>
                        <p>Your application reference number: <strong>${referenceId}</strong></p>
                        <p>Please save this reference number for future inquiries.</p>
                        <div style="margin-top: 30px;">
                            <a href="index.html" class="btn btn-primary">Return to Home</a>
                        </div>
                    </div>
                `;
                break;
            case 'appointment':
                successMessage = `
                    <div class="success-message">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #4caf50; margin-bottom: 20px;"></i>
                        <h3>Health Checkup Booked Successfully!</h3>
                        <p>Your health checkup appointment has been scheduled. A confirmation has been sent to your email and phone.</p>
                        <p>Your booking ID: <strong>${referenceId}</strong></p>
                        <p>Please save this ID for future reference.</p>
                        <div style="margin-top: 30px;">
                            <a href="index.html" class="btn btn-primary">Return to Home</a>
                        </div>
                    </div>
                `;
                break;
            case 'insurance':
                successMessage = `
                    <div class="success-message">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #4caf50; margin-bottom: 20px;"></i>
                        <h3>Query Submitted Successfully!</h3>
                        <p>Thank you for your insurance query. Our insurance desk will get back to you within 24 hours.</p>
                        <p>Your query reference number: <strong>${referenceId}</strong></p>
                        <div style="margin-top: 30px;">
                            <a href="index.html" class="btn btn-primary">Return to Home</a>
                        </div>
                    </div>
                `;
                break;
        }
        
        form.innerHTML = successMessage;
        form.scrollIntoView({ behavior: 'smooth' });
    }
});