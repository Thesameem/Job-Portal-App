// Company Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize UI elements
    initializeDropdowns();
    initializeFormValidation();
    initializeValueAndBenefitControls();
    initializeLogoUpload();
    initializeActionButtons();
});

// Dropdown functionality
function initializeDropdowns() {
    // Profile dropdown toggle (if not already handled in main script)
    const profileDropdowns = document.querySelectorAll('.profile-dropdown');
    profileDropdowns.forEach(dropdown => {
        const profileBadge = dropdown.querySelector('.profile-badge');
        const dropdownContent = dropdown.querySelector('.dropdown-content');
        
        if (profileBadge && dropdownContent) {
            profileBadge.addEventListener('click', function(e) {
                e.preventDefault();
                dropdownContent.classList.toggle('show');
            });
        }
    });

    // Job action dropdowns
    const moreButtons = document.querySelectorAll('.more-btn');
    moreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.parentElement.querySelector('.actions-dropdown');
            
            // Close all other dropdowns first
            document.querySelectorAll('.actions-dropdown').forEach(d => {
                if (d !== dropdown) {
                    d.style.display = 'none';
                }
            });
            
            // Toggle the clicked dropdown
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.actions-dropdown').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    });
}

// Form validation for edit company profile
function initializeFormValidation() {
    const editCompanyForm = document.querySelector('.edit-profile-form');
    if (!editCompanyForm) return;
    
    editCompanyForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const requiredFields = editCompanyForm.querySelectorAll('[required]');
        
        // Reset previous error states
        document.querySelectorAll('.form-error').forEach(error => error.remove());
        
        // Validate required fields
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                const errorMessage = document.createElement('div');
                errorMessage.className = 'form-error';
                errorMessage.textContent = 'This field is required';
                errorMessage.style.color = '#e53e3e';
                errorMessage.style.fontSize = '0.85rem';
                errorMessage.style.marginTop = '0.25rem';
                
                field.style.borderColor = '#e53e3e';
                field.parentNode.appendChild(errorMessage);
            }
        });
        
        if (isValid) {
            // Show success message
            const successMessage = document.createElement('div');
            successMessage.className = 'form-success';
            successMessage.textContent = 'Company profile updated successfully!';
            successMessage.style.backgroundColor = 'rgba(46, 184, 92, 0.1)';
            successMessage.style.color = '#2eb85c';
            successMessage.style.padding = '1rem';
            successMessage.style.borderRadius = '4px';
            successMessage.style.marginBottom = '1rem';
            
            const formControls = editCompanyForm.querySelector('.form-controls');
            formControls.parentNode.insertBefore(successMessage, formControls);
            
            // Scroll to success message
            successMessage.scrollIntoView({ behavior: 'smooth' });
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                successMessage.remove();
            }, 3000);
            
            // In a real application, you would submit the form data to the server here
            // For demonstration, we'll just log the form data
            console.log('Form submitted successfully');
        }
    });
    
    // Clear error states on input
    editCompanyForm.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', function() {
            this.style.borderColor = '';
            const errorMessage = this.parentNode.querySelector('.form-error');
            if (errorMessage) {
                errorMessage.remove();
            }
        });
    });
}

// Initialize dynamic company values and benefits sections
function initializeValueAndBenefitControls() {
    // Company Values
    const addValueBtn = document.getElementById('add-value-btn');
    if (addValueBtn) {
        addValueBtn.addEventListener('click', function() {
            const valuesContainer = document.getElementById('values-container');
            const valueCount = valuesContainer.querySelectorAll('.value-item').length;
            
            const valueItem = document.createElement('div');
            valueItem.className = 'value-item';
            valueItem.innerHTML = `
                <div class="value-inputs">
                    <input type="text" name="value-title[]" placeholder="Value Title">
                    <input type="text" name="value-description[]" placeholder="Short description">
                    <select name="value-icon[]">
                        <option value="lightbulb">Lightbulb</option>
                        <option value="users">Users</option>
                        <option value="chart-line">Chart Line</option>
                        <option value="heart">Heart</option>
                        <option value="shield">Shield</option>
                        <option value="star">Star</option>
                    </select>
                </div>
                <button type="button" class="remove-value-btn"><i class="fas fa-times"></i></button>
            `;
            
            valuesContainer.appendChild(valueItem);
            
            // Add event listener to the new remove button
            valueItem.querySelector('.remove-value-btn').addEventListener('click', function() {
                valueItem.remove();
            });
        });
        
        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-value-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.value-item').remove();
            });
        });
    }
    
    // Company Benefits
    const addBenefitBtn = document.getElementById('add-benefit-btn');
    if (addBenefitBtn) {
        addBenefitBtn.addEventListener('click', function() {
            const benefitsContainer = document.getElementById('benefits-container');
            const benefitCount = benefitsContainer.querySelectorAll('.benefit-item').length;
            
            const benefitItem = document.createElement('div');
            benefitItem.className = 'benefit-item';
            benefitItem.innerHTML = `
                <div class="benefit-inputs">
                    <input type="text" name="benefit-title[]" placeholder="Benefit Title">
                    <textarea name="benefit-description[]" placeholder="Description" rows="2"></textarea>
                    <select name="benefit-icon[]">
                        <option value="heart">Heart</option>
                        <option value="graduation-cap">Graduation Cap</option>
                        <option value="home">Home</option>
                        <option value="dumbbell">Dumbbell</option>
                        <option value="dollar-sign">Dollar Sign</option>
                        <option value="plane">Plane</option>
                    </select>
                </div>
                <button type="button" class="remove-benefit-btn"><i class="fas fa-times"></i></button>
            `;
            
            benefitsContainer.appendChild(benefitItem);
            
            // Add event listener to the new remove button
            benefitItem.querySelector('.remove-benefit-btn').addEventListener('click', function() {
                benefitItem.remove();
            });
        });
        
        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-benefit-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.benefit-item').remove();
            });
        });
    }
}

// Logo upload preview functionality
function initializeLogoUpload() {
    const logoInput = document.getElementById('company-logo');
    const logoPreview = document.getElementById('logo-preview');
    const removeBtn = document.querySelector('.remove-btn');
    
    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
        
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                logoPreview.src = 'https://via.placeholder.com/150';
                logoInput.value = '';
            });
        }
    }
}

// Job action buttons functionality
function initializeActionButtons() {
    // Close/reopen job buttons
    const jobActionBtns = document.querySelectorAll('.action-btn[title="Close Job"], .action-btn[title="Reopen Job"]');
    jobActionBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const jobItem = this.closest('.job-item');
            const jobStatus = jobItem.querySelector('.job-status');
            
            if (jobStatus.classList.contains('active')) {
                // Close job
                jobStatus.classList.remove('active');
                jobStatus.classList.add('closed');
                jobStatus.textContent = 'Closed';
                
                this.innerHTML = '<i class="fas fa-redo"></i>';
                this.title = 'Reopen Job';
                
                // Show confirmation message
                showNotification('Job closed successfully', 'success');
            } else if (jobStatus.classList.contains('closed')) {
                // Reopen job
                jobStatus.classList.remove('closed');
                jobStatus.classList.add('active');
                jobStatus.textContent = 'Active';
                
                this.innerHTML = '<i class="fas fa-times-circle"></i>';
                this.title = 'Close Job';
                
                // Show confirmation message
                showNotification('Job reopened successfully', 'success');
            }
        });
    });
    
    // Publish draft job button
    const publishBtns = document.querySelectorAll('.action-btn[title="Publish Job"]');
    publishBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const jobItem = this.closest('.job-item');
            const jobStatus = jobItem.querySelector('.job-status');
            
            if (jobStatus.classList.contains('draft')) {
                // Publish job
                jobStatus.classList.remove('draft');
                jobStatus.classList.add('active');
                jobStatus.textContent = 'Active';
                
                this.innerHTML = '<i class="fas fa-times-circle"></i>';
                this.title = 'Close Job';
                
                // Enable applications button
                const applicationsBtn = jobItem.querySelector('.action-btn.disabled');
                if (applicationsBtn) {
                    applicationsBtn.classList.remove('disabled');
                }
                
                // Show confirmation message
                showNotification('Job published successfully', 'success');
            }
        });
    });
    
    // Delete job action
    const deleteActions = document.querySelectorAll('.delete-action');
    deleteActions.forEach(action => {
        action.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
                const jobItem = this.closest('.job-item');
                
                // Animate removal
                jobItem.style.opacity = '0';
                jobItem.style.height = jobItem.offsetHeight + 'px';
                
                setTimeout(() => {
                    jobItem.style.height = '0';
                    jobItem.style.padding = '0';
                    jobItem.style.margin = '0';
                    jobItem.style.overflow = 'hidden';
                    
                    setTimeout(() => {
                        jobItem.remove();
                        
                        // Update job count stats
                        updateJobStats();
                        
                        // Show confirmation message
                        showNotification('Job deleted successfully', 'success');
                    }, 300);
                }, 300);
            }
        });
    });
    
    // Search functionality
    const searchBox = document.querySelector('.search-box input');
    if (searchBox) {
        searchBox.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const jobItems = document.querySelectorAll('.job-item');
            
            jobItems.forEach(job => {
                const jobTitle = job.querySelector('.job-title').textContent.toLowerCase();
                const jobMeta = job.querySelector('.job-meta').textContent.toLowerCase();
                
                if (jobTitle.includes(searchTerm) || jobMeta.includes(searchTerm)) {
                    job.style.display = '';
                } else {
                    job.style.display = 'none';
                }
            });
        });
    }
    
    // Status filter functionality
    const statusFilter = document.querySelector('.filter-options select:first-child');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            const jobItems = document.querySelectorAll('.job-item');
            
            jobItems.forEach(job => {
                const jobStatus = job.querySelector('.job-status');
                
                if (selectedStatus === 'all' || jobStatus.classList.contains(selectedStatus)) {
                    job.style.display = '';
                } else {
                    job.style.display = 'none';
                }
            });
        });
    }
}

// Update job statistics after changes
function updateJobStats() {
    const totalJobsElement = document.querySelector('.jobs-stats .stat-card:nth-child(1) .stat-number');
    const activeJobsElement = document.querySelector('.jobs-stats .stat-card:nth-child(2) .stat-number');
    const draftJobsElement = document.querySelector('.jobs-stats .stat-card:nth-child(3) .stat-number');
    const closedJobsElement = document.querySelector('.jobs-stats .stat-card:nth-child(4) .stat-number');
    
    if (totalJobsElement) {
        const totalJobs = document.querySelectorAll('.job-item').length;
        const activeJobs = document.querySelectorAll('.job-item .job-status.active').length;
        const draftJobs = document.querySelectorAll('.job-item .job-status.draft').length;
        const closedJobs = document.querySelectorAll('.job-item .job-status.closed').length;
        
        totalJobsElement.textContent = totalJobs;
        
        if (activeJobsElement) activeJobsElement.textContent = activeJobs;
        if (draftJobsElement) draftJobsElement.textContent = draftJobs;
        if (closedJobsElement) closedJobsElement.textContent = closedJobs;
    }
}

// Display notification messages
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;
    
    // Style the notification
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.backgroundColor = type === 'success' ? '#2eb85c' : '#3498db';
    notification.style.color = 'white';
    notification.style.padding = '12px 20px';
    notification.style.borderRadius = '4px';
    notification.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    notification.style.display = 'flex';
    notification.style.alignItems = 'center';
    notification.style.justifyContent = 'space-between';
    notification.style.minWidth = '300px';
    notification.style.zIndex = '1000';
    notification.style.transform = 'translateY(-20px)';
    notification.style.opacity = '0';
    notification.style.transition = 'all 0.3s ease';
    
    // Add the notification to the DOM
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateY(0)';
        notification.style.opacity = '1';
    }, 10);
    
    // Set up close button
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        closeNotification(notification);
    });
    
    // Auto close after 5 seconds
    setTimeout(() => {
        closeNotification(notification);
    }, 5000);
}

function closeNotification(notification) {
    notification.style.transform = 'translateY(-20px)';
    notification.style.opacity = '0';
    
    setTimeout(() => {
        notification.remove();
    }, 300);
} 