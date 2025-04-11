document.addEventListener('DOMContentLoaded', function() {
    // Get the necessary elements
    const departmentSelect = document.getElementById('department');
    const doctorSelect = document.getElementById('doctor');
    const appointmentDateInput = document.getElementById('appointment-date');
    const appointmentTimeSelect = document.getElementById('appointment-time');
    
    // Doctor data organized by department
    const doctorsByDepartment = {
        'cardiology': [
            { id: 'c1', name: 'Dr. Rajesh Kumar', specialization: 'Interventional Cardiology' },
            { id: 'c2', name: 'Dr. Priya Sharma', specialization: 'Cardiac Electrophysiology' },
            { id: 'c3', name: 'Dr. Anand Verma', specialization: 'Pediatric Cardiology' }
        ],
        'neurology': [
            { id: 'n1', name: 'Dr. Sunil Mehta', specialization: 'Neurologist' },
            { id: 'n2', name: 'Dr. Meera Patel', specialization: 'Neurosurgeon' },
            { id: 'n3', name: 'Dr. Vikram Singh', specialization: 'Stroke Specialist' }
        ],
        'orthopedics': [
            { id: 'o1', name: 'Dr. Arun Joshi', specialization: 'Joint Replacement' },
            { id: 'o2', name: 'Dr. Neha Gupta', specialization: 'Spine Surgery' },
            { id: 'o3', name: 'Dr. Rahul Khanna', specialization: 'Sports Medicine' }
        ],
        'pediatrics': [
            { id: 'p1', name: 'Dr. Kavita Reddy', specialization: 'General Pediatrics' },
            { id: 'p2', name: 'Dr. Sanjay Malhotra', specialization: 'Pediatric Pulmonology' },
            { id: 'p3', name: 'Dr. Deepa Nair', specialization: 'Neonatology' }
        ],
        'gynecology': [
            { id: 'g1', name: 'Dr. Sunita Agarwal', specialization: 'Obstetrics' },
            { id: 'g2', name: 'Dr. Ravi Desai', specialization: 'Gynecologic Oncology' },
            { id: 'g3', name: 'Dr. Anjali Mathur', specialization: 'Reproductive Endocrinology' }
        ],
        'dermatology': [
            { id: 'd1', name: 'Dr. Kiran Shah', specialization: 'Medical Dermatology' },
            { id: 'd2', name: 'Dr. Pooja Iyer', specialization: 'Cosmetic Dermatology' },
            { id: 'd3', name: 'Dr. Vivek Kapoor', specialization: 'Pediatric Dermatology' }
        ],
        'ophthalmology': [
            { id: 'op1', name: 'Dr. Manish Arora', specialization: 'Cataract Surgery' },
            { id: 'op2', name: 'Dr. Shweta Bose', specialization: 'Glaucoma Specialist' },
            { id: 'op3', name: 'Dr. Prakash Rao', specialization: 'Retina Specialist' }
        ],
        'ent': [
            { id: 'e1', name: 'Dr. Alok Mishra', specialization: 'Otology' },
            { id: 'e2', name: 'Dr. Sarika Jain', specialization: 'Rhinology' },
            { id: 'e3', name: 'Dr. Tarun Bajaj', specialization: 'Head and Neck Surgery' }
        ],
        'dental': [
            { id: 'de1', name: 'Dr. Nisha Menon', specialization: 'Orthodontics' },
            { id: 'de2', name: 'Dr. Rajat Bhatia', specialization: 'Endodontics' },
            { id: 'de3', name: 'Dr. Smita Patil', specialization: 'Oral Surgery' }
        ],
        'general-medicine': [
            { id: 'gm1', name: 'Dr. Ashok Pillai', specialization: 'Internal Medicine' },
            { id: 'gm2', name: 'Dr. Lakshmi Rao', specialization: 'Family Medicine' },
            { id: 'gm3', name: 'Dr. Harish Chandra', specialization: 'Geriatric Medicine' }
        ]
    };
    
    // Time slots by doctor availability (simplified - in a real system this would come from a database)
    const timeSlots = [
        '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
        '12:00 PM', '12:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM',
        '04:00 PM', '04:30 PM', '05:00 PM', '05:30 PM'
    ];
    
    // Event listener for department selection
    departmentSelect.addEventListener('change', function() {
        const selectedDepartment = this.value;
        populateDoctors(selectedDepartment);
    });
    
    // Event listeners for date and doctor selection to update time slots
    appointmentDateInput.addEventListener('change', updateTimeSlots);
    doctorSelect.addEventListener('change', updateTimeSlots);
    
    // Function to populate doctors based on department
    function populateDoctors(department) {
        // Clear existing options
        doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
        
        // If no department is selected, return
        if (!department) return;
        
        // Get doctors for the selected department
        const doctors = doctorsByDepartment[department] || [];
        
        // Add doctor options
        doctors.forEach(doctor => {
            const option = document.createElement('option');
            option.value = doctor.id;
            option.textContent = `${doctor.name} (${doctor.specialization})`;
            doctorSelect.appendChild(option);
        });
        
        // Update time slots if date is already selected
        if (appointmentDateInput.value) {
            updateTimeSlots();
        }
    }
    
    // Function to update available time slots
    function updateTimeSlots() {
        // Clear existing options
        appointmentTimeSelect.innerHTML = '<option value="">Select Time</option>';
        
        const selectedDate = appointmentDateInput.value;
        const selectedDoctor = doctorSelect.value;
        
        // If either date or doctor is not selected, return
        if (!selectedDate || !selectedDoctor) return;
        
        // Get day of week (0 = Sunday, 6 = Saturday)
        const dayOfWeek = new Date(selectedDate).getDay();
        
        // Filter time slots based on doctor availability and day of week
        // This is a simplified example - in a real system, this would check a database
        let availableSlots = [...timeSlots];
        
        // Example: Remove morning slots for weekends
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            availableSlots = availableSlots.filter(slot => !slot.includes('AM'));
        }
        
        // Example: Some doctors might not be available on certain days
        if (selectedDoctor.startsWith('c') && dayOfWeek === 3) { // Cardiologists on Wednesday
            availableSlots = availableSlots.filter(slot => !slot.includes('PM'));
        }
        
        // Add available time slots
        availableSlots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot;
            option.textContent = slot;
            appointmentTimeSelect.appendChild(option);
        });
    }
    
    // Initialize form tabs
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and contents
            tabBtns.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to current button and content
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Multi-step form navigation
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    const formSteps = document.querySelectorAll('.form-step');
    
    nextBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const currentStepNum = parseInt(currentStep.getAttribute('data-step'));
            const nextStepNum = currentStepNum + 1;
            
            // Validate current step before proceeding
            if (validateStep(currentStepNum)) {
                // Hide current step
                currentStep.classList.remove('active');
                
                // Show next step
                document.querySelector(`.form-step[data-step="${nextStepNum}"]`).classList.add('active');
                
                // If moving to review step, populate summary
                if (nextStepNum === 3) {
                    populateSummary();
                }
            }
        });
    });
    
    prevBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const currentStepNum = parseInt(currentStep.getAttribute('data-step'));
            const prevStepNum = currentStepNum - 1;
            
            // Hide current step
            currentStep.classList.remove('active');
            
            // Show previous step
            document.querySelector(`.form-step[data-step="${prevStepNum}"]`).classList.add('active');
        });
    });
    
    // Validate form step
    function validateStep(stepNum) {
        const step = document.querySelector(`.form-step[data-step="${stepNum}"]`);
        const requiredFields = step.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value) {
                isValid = false;
                field.classList.add('invalid');
                
                // Add event listener to remove invalid class when field is filled
                field.addEventListener('input', function() {
                    if (this.value) {
                        this.classList.remove('invalid');
                    }
                }, { once: true });
            }
        });
        
        return isValid;
    }
    
    // Populate summary for review step
    function populateSummary() {
        document.getElementById('summary-name').textContent = 
            `${document.getElementById('first-name').value} ${document.getElementById('last-name').value}`;
        
        document.getElementById('summary-dob').textContent = 
            formatDate(document.getElementById('dob').value);
        
        document.getElementById('summary-contact').textContent = 
            `${document.getElementById('phone').value} | ${document.getElementById('email').value}`;
        
        document.getElementById('summary-branch').textContent = 
            document.getElementById('branch').options[document.getElementById('branch').selectedIndex].text;
        
        document.getElementById('summary-department').textContent = 
            document.getElementById('department').options[document.getElementById('department').selectedIndex].text;
        
        document.getElementById('summary-doctor').textContent = 
            document.getElementById('doctor').options[document.getElementById('doctor').selectedIndex].text;
        
        document.getElementById('summary-datetime').textContent = 
            `${formatDate(document.getElementById('appointment-date').value)} at ${document.getElementById('appointment-time').value}`;
        
        document.getElementById('summary-reason').textContent = 
            document.getElementById('reason').value;
    }
    
    // Format date for display
    function formatDate(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    }
    
    // Handle reschedule/cancel form
    const actionSelect = document.getElementById('action');
    const rescheduleOptions = document.getElementById('reschedule-options');
    const cancelReason = document.getElementById('cancel-reason');
    
    if (actionSelect) {
        actionSelect.addEventListener('change', function() {
            if (this.value === 'reschedule') {
                rescheduleOptions.classList.remove('hidden');
                cancelReason.classList.add('hidden');
            } else if (this.value === 'cancel') {
                rescheduleOptions.classList.add('hidden');
                cancelReason.classList.remove('hidden');
            } else {
                rescheduleOptions.classList.add('hidden');
                cancelReason.classList.add('hidden');
            }
        });
    }
    
    // Handle FAQ accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            // Toggle active class
            this.classList.toggle('active');
            
            // Toggle answer visibility
            if (answer.style.maxHeight) {
                answer.style.maxHeight = null;
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        });
    });
    
    // Form submission
    const appointmentForm = document.getElementById('appointment-form');
    const rescheduleForm = document.getElementById('reschedule-form');
    
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real application, you would send this data to the server
            alert('Appointment booked successfully! You will receive a confirmation email shortly.');
            
            // Reset form
            this.reset();
            
            // Go back to first step
            formSteps.forEach(step => step.classList.remove('active'));
            formSteps[0].classList.add('active');
        });
    }
    
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const action = document.getElementById('action').value;
            let message = '';
            
            if (action === 'reschedule') {
                message = 'Your appointment has been rescheduled successfully!';
            } else if (action === 'cancel') {
                message = 'Your appointment has been cancelled successfully!';
            } else {
                message = 'Please select an action (reschedule or cancel).';
                alert(message);
                return;
            }
            
            alert(message);
            this.reset();
            
            // Reset form state
            rescheduleOptions.classList.add('hidden');
            cancelReason.classList.add('hidden');
        });
    }
});