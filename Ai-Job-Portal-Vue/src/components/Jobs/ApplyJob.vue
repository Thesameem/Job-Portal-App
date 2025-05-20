<template>
  <!-- Application Form Section -->
  <section class="application-section">
    <div v-if="loading" class="loading-overlay">
      <div class="loading-spinner"></div>
    </div>

    <div v-if="error" class="error-message">
      {{ error }}
    </div>

    <div v-if="success" class="success-message">
      Application submitted successfully!
    </div>

    <div class="application-container" v-if="job">
      <div class="application-header">
        <h2>Apply for Position</h2>
        <div class="job-preview">
          <div class="company-logo">
            <img 
              v-if="job.company_logo" 
              :src="getLogoUrl(job.company_logo)" 
              :alt="job.company_name + ' logo'"
              @error="handleLogoError"
            />
            <div v-else class="company-initial">
              {{ job.company_name ? job.company_name.charAt(0).toUpperCase() : 'C' }}
            </div>
          </div>
          <div class="job-info">
            <h3>{{ job.title }}</h3>
            <p>{{ job.company_name }}</p>
            <div class="job-meta">
              <span><i class="fas fa-map-marker-alt"></i> {{ job.location }}</span>
              <span><i class="fas fa-clock"></i> {{ job.type }}</span>
              <span><i class="fas fa-dollar-sign"></i> {{ job.salary_range }}</span>
            </div>
          </div>
        </div>
      </div>

      <form class="application-form" @submit.prevent="submitApplication">
        <!-- Personal Information -->
        <div class="form-section">
          <h3>Personal Information</h3>
          <div class="form-grid">
            <div class="form-group">
              <label for="fullname">Full Name</label>
              <input 
                type="text" 
                id="fullname" 
                v-model="formData.fullname" 
                :class="{ 'error': errors.fullname }"
                required 
              />
              <span class="error-message" v-if="errors.fullname">{{ errors.fullname }}</span>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input 
                type="email" 
                id="email" 
                v-model="formData.email" 
                :class="{ 'error': errors.email }"
                required 
              />
              <span class="error-message" v-if="errors.email">{{ errors.email }}</span>
            </div>
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input 
                type="tel" 
                id="phone" 
                v-model="formData.phone" 
                :class="{ 'error': errors.phone }"
                required 
              />
              <span class="error-message" v-if="errors.phone">{{ errors.phone }}</span>
            </div>
            <div class="form-group">
              <label for="location">Location</label>
              <input 
                type="text" 
                id="location" 
                v-model="formData.location" 
                :class="{ 'error': errors.location }"
                required 
              />
              <span class="error-message" v-if="errors.location">{{ errors.location }}</span>
            </div>
          </div>
        </div>

        <!-- Professional Summary -->
        <div class="form-section">
          <h3>Professional Summary</h3>
          <div class="form-group">
            <label for="summary">Tell us about yourself</label>
            <textarea 
              id="summary" 
              rows="4" 
              v-model="formData.summary" 
              :class="{ 'error': errors.summary }"
              required
            ></textarea>
            <span class="error-message" v-if="errors.summary">{{ errors.summary }}</span>
          </div>
        </div>

        <!-- Skills -->
        <div class="form-section">
          <h3>Skills</h3>
          <div class="form-group">
            <label for="skills">Add your skills (comma separated)</label>
            <input
              type="text"
              id="skills"
              v-model="formData.skills"
              :class="{ 'error': errors.skills }"
              placeholder="e.g., React, JavaScript, CSS, TypeScript"
              required
            />
            <span class="error-message" v-if="errors.skills">{{ errors.skills }}</span>
          </div>
        </div>

        <!-- Documents -->
        <div class="form-section">
          <h3>Documents</h3>
          <div class="form-group">
            <label for="resume">Resume/CV</label>
            <div class="file-upload">
              <input 
                type="file" 
                id="resume" 
                @change="handleResumeUpload" 
                accept=".pdf,.doc,.docx" 
                :class="{ 'error': errors.resume }"
                required 
              />
              <label for="resume" class="file-upload-label">
                <i class="fas fa-upload"></i>
                <span>Choose File</span>
              </label>
              <span class="file-name">{{ resumeFileName || 'No file chosen' }}</span>
            </div>
            <span class="error-message" v-if="errors.resume">{{ errors.resume }}</span>
          </div>
          <div class="form-group">
            <label for="cover-letter">Cover Letter (Optional)</label>
            <div class="file-upload">
              <input 
                type="file" 
                id="cover-letter" 
                @change="handleCoverLetterUpload" 
                accept=".pdf,.doc,.docx" 
              />
              <label for="cover-letter" class="file-upload-label">
                <i class="fas fa-upload"></i>
                <span>Choose File</span>
              </label>
              <span class="file-name">{{ coverLetterFileName || 'No file chosen' }}</span>
            </div>
          </div>
        </div>

        <!-- Additional Information -->
        <div class="form-section">
          <h3>Additional Information</h3>
          <div class="form-group">
            <label for="portfolio">Portfolio URL (Optional)</label>
            <input 
              type="url" 
              id="portfolio" 
              v-model="formData.portfolio" 
              :class="{ 'error': errors.portfolio }"
              placeholder="https://" 
            />
            <span class="error-message" v-if="errors.portfolio">{{ errors.portfolio }}</span>
          </div>
          <div class="form-group">
            <label for="linkedin">LinkedIn Profile (Optional)</label>
            <input 
              type="url" 
              id="linkedin" 
              v-model="formData.linkedin" 
              :class="{ 'error': errors.linkedin }"
              placeholder="https://linkedin.com/in/" 
            />
            <span class="error-message" v-if="errors.linkedin">{{ errors.linkedin }}</span>
          </div>
          <div class="form-group">
            <label for="github">GitHub Profile (Optional)</label>
            <input 
              type="url" 
              id="github" 
              v-model="formData.github" 
              :class="{ 'error': errors.github }"
              placeholder="https://github.com/" 
            />
            <span class="error-message" v-if="errors.github">{{ errors.github }}</span>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
          <button type="submit" class="submit-btn" :disabled="loading">
            {{ loading ? 'Submitting...' : 'Submit Application' }}
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { POST, GET } from '@/scripts/Fetch';
import Config from '@/scripts/Config';

const route = useRoute();
const router = useRouter();

// State
const job = ref(null);
const loading = ref(false);
const error = ref('');
const success = ref(false);
const resumeFileName = ref('');
const coverLetterFileName = ref('');

// Form data
const formData = ref({
  fullname: '',
  email: '',
  phone: '',
  location: '',
  summary: '',
  skills: '',
  portfolio: '',
  linkedin: '',
  github: '',
  resume: null,
  coverLetter: null
});

// Validation errors
const errors = ref({});

// Fetch job details
const fetchJobDetails = async () => {
  try {
    loading.value = true;
    error.value = '';
    
    // Get job ID from route query
    const jobId = route.query.id;
    if (!jobId) {
      error.value = 'Job ID is missing';
      return;
    }

    console.log('Fetching job details for ID:', jobId);
    const response = await GET(`jobs/${jobId}`);
    
    console.log('Job details response:', response);
    
    if (response && !response.error) {
      // Extract job data from the response
      let jobDetails = null;
      
      if (response.response && response.response.response) {
        jobDetails = response.response.response;
      } else if (response.response) {
        jobDetails = response.response;
      }
      
      if (jobDetails) {
        job.value = jobDetails;
      } else {
        error.value = 'Invalid job data received';
      }
    } else {
      error.value = response?.reason || 'Failed to load job details';
      console.error('Job fetch error:', response);
    }
  } catch (err) {
    error.value = 'Error loading job details. Please try again later.';
    console.error('Job fetch error:', err);
  } finally {
    loading.value = false;
  }
};

// File upload handlers
const handleResumeUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    formData.value.resume = file;
    resumeFileName.value = file.name;
  }
};

const handleCoverLetterUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    formData.value.coverLetter = file;
    coverLetterFileName.value = file.name;
  }
};

// Form validation
const validateForm = () => {
  errors.value = {};
  let isValid = true;

  // Required fields
  if (!formData.value.fullname) {
    errors.value.fullname = 'Full name is required';
    isValid = false;
  }

  if (!formData.value.email) {
    errors.value.email = 'Email is required';
    isValid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.value.email)) {
    errors.value.email = 'Invalid email format';
    isValid = false;
  }

  if (!formData.value.phone) {
    errors.value.phone = 'Phone number is required';
    isValid = false;
  }

  if (!formData.value.location) {
    errors.value.location = 'Location is required';
    isValid = false;
  }

  if (!formData.value.summary) {
    errors.value.summary = 'Professional summary is required';
    isValid = false;
  }

  if (!formData.value.skills) {
    errors.value.skills = 'Skills are required';
    isValid = false;
  }

  if (!formData.value.resume) {
    errors.value.resume = 'Resume is required';
    isValid = false;
  }

  // Optional fields validation
  if (formData.value.portfolio && !/^https?:\/\/.+/.test(formData.value.portfolio)) {
    errors.value.portfolio = 'Invalid portfolio URL';
    isValid = false;
  }

  if (formData.value.linkedin && !/^https?:\/\/.+/.test(formData.value.linkedin)) {
    errors.value.linkedin = 'Invalid LinkedIn URL';
    isValid = false;
  }

  if (formData.value.github && !/^https?:\/\/.+/.test(formData.value.github)) {
    errors.value.github = 'Invalid GitHub URL';
    isValid = false;
  }

  return isValid;
};

// Submit application
const submitApplication = async () => {
  if (!validateForm()) {
    return;
  }

  try {
    loading.value = true;
    error.value = '';
    success.value = false;

    const formDataToSend = new FormData();
    
    // Append form data
    Object.keys(formData.value).forEach(key => {
      if (formData.value[key] !== null) {
        formDataToSend.append(key, formData.value[key]);
      }
    });
    
    const response = await POST('jobs/apply', formDataToSend);
    
    if (!response.error) {
      success.value = true;
      // Reset form
      formData.value = {
        fullname: '',
        email: '',
        phone: '',
        location: '',
        summary: '',
        skills: '',
        portfolio: '',
        linkedin: '',
        github: '',
        resume: null,
        coverLetter: null
      };
      resumeFileName.value = '';
      coverLetterFileName.value = '';
      
      // Redirect after 2 seconds
      setTimeout(() => {
        router.push('/my-applications');
      }, 2000);
    } else {
      error.value = response.reason || 'Failed to submit application';
    }
  } catch (err) {
    error.value = 'Error submitting application';
  } finally {
    loading.value = false;
  }
};

// Helper function to get logo URL
const getLogoUrl = (logo) => {
  if (!logo) return null;
  
  // If logo is already a full URL, return it
  if (logo.startsWith('http://') || logo.startsWith('https://')) {
    return logo;
  }
  
  // Otherwise, construct the URL using the base URL from Config
  const baseUrlWithoutApi = Config.baseURL.replace('/api/', '/').replace('/api', '/');
  return `${baseUrlWithoutApi}images/${logo}`;
};

// Handle logo loading error
const handleLogoError = (e) => {
  console.error('Logo failed to load:', e);
  e.target.style.display = 'none';
  
  // Create and show company initial instead
  const parent = e.target.parentElement;
  const initial = document.createElement('div');
  initial.className = 'company-initial';
  initial.textContent = job.value.company_name ? job.value.company_name.charAt(0).toUpperCase() : 'C';
  parent.appendChild(initial);
};

// Fetch job details on component mount
onMounted(() => {
  fetchJobDetails();
});
</script>

<style scoped>
.application-section {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 5px solid #f3f3f3;
  border-top: 5px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-message {
  background-color: #ffebee;
  color: #c62828;
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 4px;
}

.success-message {
  background-color: #e8f5e9;
  color: #2e7d32;
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 4px;
}

.application-header {
  margin-bottom: 2rem;
}

.job-preview {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: #f5f5f5;
  border-radius: 8px;
}

.job-preview img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
}

.job-info h3 {
  margin: 0;
  color: #333;
}

.job-meta {
  display: flex;
  gap: 1rem;
  margin-top: 0.5rem;
  color: #666;
}

.form-section {
  margin-bottom: 2rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #333;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.form-group input.error,
.form-group textarea.error {
  border-color: #c62828;
}

.error-message {
  color: #c62828;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.file-upload {
  position: relative;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.file-upload input[type="file"] {
  position: absolute;
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  z-index: -1;
}

.file-upload-label {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
}

.file-name {
  color: #666;
}

.submit-btn {
  width: 100%;
  padding: 1rem;
  background: #2196f3;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
}

.submit-btn:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.submit-btn:hover:not(:disabled) {
  background: #1976d2;
}

.company-logo {
  width: 50px;
  height: 50px;
  border-radius: 4px;
  overflow: hidden;
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
}

.company-logo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.company-initial {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #2196f3;
  color: white;
  font-size: 1.5rem;
  font-weight: bold;
}
</style>
