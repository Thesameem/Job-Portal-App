<template>
  <!-- Authentication required message -->
  <div v-if="!isAuthenticated" class="auth-required-container">
    <div class="auth-required-message">
      <h2>Authentication Required</h2>
      <p>{{ errorMessage }}</p>
      <div class="loading-spinner"></div>
    </div>
  </div>
  
  <!-- Post Job Section - only show if authenticated -->
  <section v-if="isAuthenticated" class="post-job-section">
    <div class="post-job-container">
      <div class="post-job-header">
        <h2>Post a New Job</h2>
        <p>Fill out the form below to create your job listing</p>
      </div>

      <form class="post-job-form" @submit.prevent="submitJob">
        <!-- Company Information -->
        <div class="form-section">
          <h3>Company Information</h3>
          <div class="form-grid">
            <div class="form-group">
              <label for="company-name">Company Name</label>
              <input type="text" id="company-name" v-model="formData.company_name" required />
            </div>
            <div class="form-group">
              <label for="company-logo">Company Logo</label>
              <div class="file-upload">
                <input type="file" id="company-logo" @change="handleLogoUpload" accept="image/*" />
                <label for="company-logo" class="file-upload-label">
                  <i class="fas fa-upload"></i>
                  <span>Choose Logo</span>
                </label>
                <span class="file-name">{{ logoFileName || 'No file chosen' }}</span>
              </div>
            </div>
            <div class="form-group">
              <label for="company-website">Company Website</label>
              <input
                type="url"
                id="company-website"
                v-model="formData.company_website"
                placeholder="https://"
              />
            </div>
            <div class="form-group">
              <label for="company-size">Company Size</label>
              <select id="company-size" v-model="formData.company_size" required>
                <option value="">Select size</option>
                <option value="1-10">1-10 employees</option>
                <option value="11-50">11-50 employees</option>
                <option value="51-200">51-200 employees</option>
                <option value="201-500">201-500 employees</option>
                <option value="501+">501+ employees</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Job Details -->
        <div class="form-section">
          <h3>Job Details</h3>
          <div class="form-grid">
            <div class="form-group">
              <label for="job-title">Job Title</label>
              <input
                type="text"
                id="job-title"
                v-model="formData.title"
                placeholder="e.g., Senior Frontend Developer"
                required
              />
            </div>
            <div class="form-group">
              <label for="job-type">Job Type</label>
              <select id="job-type" v-model="formData.type" required>
                <option value="">Select type</option>
                <option value="full-time">Full-time</option>
                <option value="part-time">Part-time</option>
                <option value="contract">Contract</option>
                <option value="freelance">Freelance</option>
              </select>
            </div>
            <div class="form-group">
              <label for="job-location">Location</label>
              <input
                type="text"
                id="job-location"
                v-model="formData.location"
                placeholder="e.g., Remote, New York, etc."
                required
              />
            </div>
            <div class="form-group">
              <label for="job-salary">Salary Range</label>
              <input
                type="text"
                id="job-salary"
                v-model="formData.salary_range"
                placeholder="e.g., $80,000 - $100,000"
                required
              />
            </div>
          </div>
          <div class="form-group">
            <label for="job-description">Job Description</label>
            <textarea
              id="job-description"
              v-model="formData.description"
              rows="6"
              required
            ></textarea>
          </div>
          <div class="form-group">
            <label for="job-responsibilities">Key Responsibilities</label>
            <textarea
              id="job-responsibilities"
              v-model="formData.responsibilities"
              rows="4"
              required
            ></textarea>
          </div>
        </div>

        <!-- Requirements -->
        <div class="form-section">
          <h3>Requirements</h3>
          <div class="form-grid">
            <div class="form-group">
              <label for="experience-level">Experience Level</label>
              <select id="experience-level" v-model="formData.experience_level" required>
                <option value="">Select level</option>
                <option value="entry">Entry Level</option>
                <option value="intermediate">Intermediate</option>
                <option value="senior">Senior</option>
                <option value="lead">Lead</option>
              </select>
            </div>
            <div class="form-group">
              <label for="education-level">Education Level</label>
              <select id="education-level" v-model="formData.education_level" required>
                <option value="">Select level</option>
                <option value="high-school">High School</option>
                <option value="associate">Associate Degree</option>
                <option value="bachelor">Bachelor's Degree</option>
                <option value="master">Master's Degree</option>
                <option value="phd">PhD</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="required-skills">Required Skills (comma separated)</label>
            <input
              type="text"
              id="required-skills"
              v-model="formData.required_skills"
              placeholder="e.g., React, JavaScript, CSS, TypeScript"
              required
            />
          </div>
          <div class="form-group">
            <label for="preferred-skills">Preferred Skills (comma separated)</label>
            <input
              type="text"
              id="preferred-skills"
              v-model="formData.preferred_skills"
              placeholder="e.g., AWS, Docker, Kubernetes"
            />
          </div>
        </div>

        <!-- Additional Information -->
        <div class="form-section">
          <h3>Additional Information</h3>
          <div class="form-group">
            <label for="benefits">Benefits & Perks</label>
            <textarea
              id="benefits"
              v-model="formData.benefits"
              rows="4"
              placeholder="List the benefits and perks offered"
            ></textarea>
          </div>
          <div class="form-group">
            <label for="application-deadline">Application Deadline</label>
            <input type="date" id="application-deadline" v-model="formData.application_deadline" />
          </div>
          <div class="form-group">
            <label for="contact-email">Contact Email</label>
            <input type="email" id="contact-email" v-model="formData.contact_email" required />
          </div>
        </div>

        <!-- Error message -->
        <div v-if="error" class="error-message">
          <p>{{ errorMessage }}</p>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
          <button type="submit" class="submit-btn" :disabled="isSubmitting">
            {{ isSubmitting ? 'Submitting...' : 'Post Job' }}
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { POST } from '@/scripts/Fetch'
import { useRouter } from 'vue-router'
import Cookie from '@/scripts/Cookie'
import { useJobStore } from '@/stores/job'
import { useToast } from '@/scripts/toast'

const router = useRouter()
const jobStore = useJobStore()
const toast = useToast()
const isSubmitting = ref(false)
const error = ref(false)
const errorMessage = ref('')
const logoFileName = ref('')
const isAuthenticated = ref(true)

// Authentication is now handled in router/index.js with beforeEach guard

// Form data structure matching the backend expectations
const formData = reactive({
  company_name: '',
  company_logo: null,
  company_website: '',
  company_size: '',
  title: '',
  type: '',
  location: '',
  salary_range: '',
  description: '',
  responsibilities: '',
  experience_level: '',
  education_level: '',
  required_skills: '',
  preferred_skills: '',
  benefits: '',
  application_deadline: '',
  contact_email: '',
})

// Handle logo file upload
const handleLogoUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    formData.company_logo = file
    logoFileName.value = file.name
  } else {
    formData.company_logo = null
    logoFileName.value = ''
  }
}

// Submit job function
const submitJob = async () => {
  try {
    // Check if user is authenticated
    const token = Cookie.getCookie('job-app')
    if (!token || !jobStore.user || Object.keys(jobStore.user).length === 0) {
      // Redirect to auth page immediately
      router.replace('/auth')
      return
    }

    isSubmitting.value = true
    error.value = false

    // Create FormData object for file upload
    const jobFormData = new FormData()

    // Append all form fields to FormData
    for (const key in formData) {
      if (formData[key] !== null && formData[key] !== undefined) {
        jobFormData.append(key, formData[key])
      }
    }

    // Submit to backend
    const result = await POST('my-jobs', jobFormData)

    if (!result.error) {
      // Show success toast notification
      toast.success(`Job "${formData.title}" posted successfully!`)
      
      // Success - redirect to home page
      router.push('/')
    } else {
      // Handle errors
      error.value = true

      // Check for authentication error
      if (result.reason && result.reason.toLowerCase().includes('unauthenticated')) {
        // Clear token and user data
        Cookie.setCookie('job-app', '', 0)
        jobStore.user = {}
        
        // Show error toast
        toast.error('Authentication failed. Please log in again.')
        
        // Redirect to auth page immediately
        router.replace('/auth')
      } else {
        errorMessage.value = result.reason || 'Failed to create job listing. Please try again.'
        
        // Show error toast
        toast.error(result.reason || 'Failed to create job listing. Please try again.')

        // If there are validation errors, show them in detail
        if (result.response) {
          const errors = Object.values(result.response).flat()
          errorMessage.value = errors.join(', ')
        }
      }
    }
  } catch (err) {
    error.value = true
    errorMessage.value = 'An unexpected error occurred. Please try again.'
    
    // Show error toast
    toast.error('An unexpected error occurred. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.error-message {
  color: #e74c3c;
  background-color: #fdecea;
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.auth-required-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 50vh;
  padding: 2rem;
}

.auth-required-message {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  text-align: center;
  max-width: 500px;
}

.auth-required-message h2 {
  color: #e74c3c;
  margin-bottom: 1rem;
}

.loading-spinner {
  display: inline-block;
  width: 40px;
  height: 40px;
  margin-top: 1rem;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #0d6efd;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
