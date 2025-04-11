document.addEventListener('DOMContentLoaded', function() {
    // FAQ Toggle
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', () => {
            faqItems.forEach(otherItem => {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                }
            });
            item.classList.toggle('active');
        });
    });

    // Testimonial Slider
    const testimonialSlider = document.querySelector('.testimonial-slider');
    if (testimonialSlider) {
        const slides = testimonialSlider.querySelectorAll('.testimonial-slide');
        const dots = document.querySelectorAll('.slider-dots .dot');
        const prevBtn = document.querySelector('.prev-slide');
        const nextBtn = document.querySelector('.next-slide');
        let currentSlide = 0;

        updateSlider();

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                updateSlider();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % slides.length;
                updateSlider();
            });
        }

        if (dots) {
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    updateSlider();
                });
            });
        }

        function updateSlider() {
            slides.forEach((slide, index) => {
                slide.style.transform = `translateX(${(index - currentSlide) * 100}%)`;
            });

            if (dots) {
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }
        }

        let autoSlideInterval;

        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                updateSlider();
            }, 5000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }

        startAutoSlide();
        testimonialSlider.addEventListener('mouseenter', stopAutoSlide);
        testimonialSlider.addEventListener('mouseleave', startAutoSlide);
    }

    // Tab Switching
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabsContainer = button.closest('.tabs-container');
            tabsContainer.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            button.classList.add('active');
            const tabId = button.getAttribute('data-tab');

            tabsContainer.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            tabsContainer.querySelector(`#${tabId}`).classList.add('active');
        });
    });

    // Family Card Form - Dynamic Family Members
    const familyMembersSelect = document.getElementById('family-members');
    const familyMembersContainer = document.getElementById('family-members-container');

    if (familyMembersSelect && familyMembersContainer) {
        familyMembersSelect.addEventListener('change', updateFamilyMembers);
        updateFamilyMembers();

        function updateFamilyMembers() {
            const count = parseInt(familyMembersSelect.value);
            familyMembersContainer.innerHTML = '';
            for (let i = 1; i < count; i++) {
                const memberEntry = document.createElement('div');
                memberEntry.className = 'family-member-entry';
                memberEntry.innerHTML = `
                    <h4>Family Member ${i}</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="member-name-${i}">Full Name <span class="required">*</span></label>
                            <input type="text" id="member-name-${i}" name="member-name-${i}" required>
                        </div>
                        <div class="form-group">
                            <label for="member-relation-${i}">Relation <span class="required">*</span></label>
                            <select id="member-relation-${i}" name="member-relation-${i}" required>
                                <option value="">Select Relation</option>
                                <option value="spouse">Spouse</option>
                                <option value="child">Child</option>
                                <option value="parent">Parent</option>
                                <option value="sibling">Sibling</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="member-dob-${i}">Date of Birth <span class="required">*</span></label>
                            <input type="date" id="member-dob-${i}" name="member-dob-${i}" required>
                        </div>
                        <div class="form-group">
                            <label for="member-gender-${i}">Gender <span class="required">*</span></label>
                            <select id="member-gender-${i}" name="member-gender-${i}" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                `;
                familyMembersContainer.appendChild(memberEntry);
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
    
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');
    
                // Validate required fields
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
    
                // If the form is invalid, prevent submission
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        });
    });

    // Set minimum date for date inputs to today
    const dateInputs = document.querySelectorAll('input[type="date"]');
    if (dateInputs.length > 0) {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const formattedToday = `${yyyy}-${mm}-${dd}`;

        dateInputs.forEach(input => {
            if (input.id.includes('appointment') || input.id.includes('date')) {
                input.setAttribute('min', formattedToday);
            }
        });
    }
});


    
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
;