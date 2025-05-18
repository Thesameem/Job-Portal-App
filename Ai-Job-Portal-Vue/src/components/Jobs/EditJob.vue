<template>
  <!-- Edit Job Form -->
  <div class="edit-job-container">
    <div class="page-header">
      <h1>Edit Job Details</h1>
      <RouterLink :to="`/jobdetails?id=${route.query.id}`" class="view-job-btn" v-if="route.query.id">
        <i class="fas fa-eye"></i> View Job Details
      </RouterLink>
    </div>
    
    <!-- Loading state -->
    <div v-if="isLoading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>Loading job details...</p>
    </div>
    
    <!-- Error state -->
    <div v-else-if="error" class="error-message">
      <i class="fas fa-exclamation-circle"></i>
      <p>{{ errorMessage }}</p>
      <button @click="loadJobDetails" class="retry-btn">Try Again</button>
      <RouterLink to="/managejob" class="back-btn">Back to Manage Jobs</RouterLink>
    </div>
    
    <!-- Edit form -->
    <form v-else id="editJobForm" @submit.prevent="saveJobChanges">
      <!-- Company Details -->
      <div class="form-section">
        <h2>Company Details</h2>
        <div class="form-group">
          <label for="companyName">Company Name</label>
          <input 
            type="text" 
            id="companyName" 
            name="companyName" 
            v-model="jobData.company_name" 
            placeholder="e.g. Tech Solutions Inc." 
            required 
          />
        </div>
        
        <div class="form-group">
          <label for="companyLogoInput">Company Logo</label>
          <div class="logo-upload-container">
            <img 
              v-if="jobData.company_logo" 
              :src="getLogoUrl(jobData.company_logo)" 
              alt="Company Logo" 
              class="logo-preview" 
            />
            <div class="logo-upload">
              <input 
                type="file" 
                id="companyLogoInput" 
                name="companyLogo" 
                accept="image/*"
                @change="handleLogoChange" 
              />
              <small class="help-text">Upload a new logo (optional). Recommended size: 300x300px. Max size: 2MB.</small>
            </div>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="companyWebsite">Company Website</label>
            <input 
              type="url" 
              id="companyWebsite" 
              name="companyWebsite" 
              v-model="jobData.company_website" 
              placeholder="https://example.com" 
            />
          </div>
          
          <div class="form-group">
            <label for="companySize">Company Size</label>
            <select id="companySize" name="companySize" v-model="jobData.company_size" required>
              <option value="1-10">1-10 employees</option>
              <option value="11-50">11-50 employees</option>
              <option value="51-200">51-200 employees</option>
              <option value="201-500">201-500 employees</option>
              <option value="501-1000">501-1000 employees</option>
              <option value="1001+">1001+ employees</option>
            </select>
          </div>
        </div>
      </div>
      
      <!-- Job Details -->
      <div class="form-section">
        <h2>Job Details</h2>
        <div class="form-group">
          <label for="jobTitle">Job Title</label>
          <input
            type="text"
            id="jobTitle"
            name="jobTitle"
            v-model="jobData.title"
            required
          />
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="jobType">Job Type</label>
            <select id="jobType" name="jobType" v-model="jobData.type" required>
              <option value="full-time">Full Time</option>
              <option value="part-time">Part Time</option>
              <option value="contract">Contract</option>
              <option value="freelancer">Freelancer</option>
            </select>
          </div>

          <div class="form-group">
            <label for="experienceLevel">Experience Level</label>
            <select id="experienceLevel" name="experienceLevel" v-model="jobData.experience_level" required>
              <option value="entry">Entry Level</option>
              <option value="intermediate">Intermediate</option>
              <option value="senior">Senior Level</option>
              <option value="lead">Lead/Management</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="salary">Salary Range</label>
            <input type="text" id="salary" name="salary" v-model="jobData.salary_range" required />
          </div>

          <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" v-model="jobData.location" required />
          </div>
        </div>
      </div>
      
      <!-- Job Description -->
      <div class="form-section">
        <h2>Job Description</h2>
        <div class="form-group">
          <label for="jobDescription">Description</label>
          <textarea 
            id="jobDescription" 
            name="jobDescription" 
            v-model="jobData.description" 
            rows="6" 
            placeholder="Describe the job role, responsibilities, and company culture..."
            required
          ></textarea>
        </div>
        
        <div class="form-group">
          <label for="responsibilities">Responsibilities</label>
          <textarea 
            id="responsibilities" 
            name="responsibilities" 
            v-model="jobData.responsibilities" 
            rows="6" 
            placeholder="List the key responsibilities for this position..."
            required
          ></textarea>
        </div>
      </div>
      
      <!-- Requirements -->
      <div class="form-section">
        <h2>Requirements</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="educationLevel">Education Level</label>
            <select id="educationLevel" name="educationLevel" v-model="jobData.education_level" required>
              <option value="high-school">High School</option>
              <option value="associate">Associate Degree</option>
              <option value="bachelor">Bachelor's Degree</option>
              <option value="master">Master's Degree</option>
              <option value="phd">PhD</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="contactEmail">Contact Email</label>
            <input 
              type="email" 
              id="contactEmail" 
              name="contactEmail" 
              v-model="jobData.contact_email" 
              placeholder="e.g. careers@example.com"
              required 
            />
          </div>
        </div>
        
        <div class="form-group">
          <label for="requiredSkills">Required Skills (comma separated)</label>
          <input 
            type="text" 
            id="requiredSkills" 
            name="requiredSkills" 
            v-model="jobData.required_skills" 
            placeholder="e.g. JavaScript, Vue, CSS, HTML" 
            required 
          />
        </div>
        
        <div class="form-group">
          <label for="preferredSkills">Preferred Skills (comma separated)</label>
          <input 
            type="text" 
            id="preferredSkills" 
            name="preferredSkills" 
            v-model="jobData.preferred_skills" 
            placeholder="e.g. TypeScript, GraphQL, Cypress" 
          />
        </div>
      </div>
      
      <!-- Additional Information -->
      <div class="form-section">
        <h2>Additional Information</h2>
        <div class="form-group">
          <label for="benefits">Benefits</label>
          <textarea 
            id="benefits" 
            name="benefits" 
            v-model="jobData.benefits" 
            rows="4" 
            placeholder="e.g. Health insurance, flexible hours, remote work option, 401(k) matching..."
          ></textarea>
        </div>
        
        <div class="form-group">
          <label for="applicationDeadline">Application Deadline</label>
          <input 
            type="date" 
            id="applicationDeadline" 
            name="applicationDeadline" 
            v-model="jobData.application_deadline" 
          />
        </div>
        
        <div class="form-group">
          <label for="status">Job Status</label>
          <select id="status" name="status" v-model="jobData.status" required>
            <option value="draft">Draft (Hidden)</option>
            <option value="posted">Posted (Visible to Job Seekers)</option>
            <option value="closed">Closed (No Longer Accepting Applications)</option>
          </select>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="save-btn" :disabled="isSaving">
          {{ isSaving ? 'Saving...' : 'Save Changes' }}
        </button>
        <RouterLink :to="`/jobdetails?id=${route.query.id}`" class="preview-btn" v-if="route.query.id">
          Preview Job
        </RouterLink>
        <RouterLink to="/managejob" class="cancel-btn">Cancel</RouterLink>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { GET, POST } from '@/scripts/Fetch'
import { useToast } from '@/scripts/toast'
import Config from '@/scripts/Config'
import Cookie from '@/scripts/Cookie'

const route = useRoute()
const router = useRouter()
const toast = useToast()

// Add debug information
console.log('Debug - Current route:', route.fullPath)
console.log('Debug - Auth token exists:', !!Cookie.getCookie('job-app'))
console.log('Debug - API base URL:', Config.baseURL)

// State
const isLoading = ref(true)
const isSaving = ref(false)
const error = ref(false)
const errorMessage = ref('')
const jobData = reactive({
  // System fields
  id: null,
  user_id: null,
  created_at: null,
  updated_at: null,
  
  // Company details
  company_name: '',
  company_logo: null,
  company_website: '',
  company_size: '1-10',
  
  // Job details
  title: '',
  type: 'full-time',
  location: '',
  salary_range: '',
  description: '',
  responsibilities: '',
  
  // Requirements
  experience_level: 'intermediate',
  education_level: 'bachelor',
  required_skills: '',
  preferred_skills: '',
  
  // Additional info
  benefits: '',
  application_deadline: '',
  contact_email: '',
  status: 'posted'
})

// Load job details
const loadJobDetails = async () => {
  const jobId = route.query.id || (route.params && route.params.id)
  
  console.log('Job ID:', jobId)
  
  if (!jobId) {
    error.value = true
    errorMessage.value = 'No job ID provided.'
    isLoading.value = false
    return
  }
  
  try {
    isLoading.value = true
    error.value = false
    
    // First try to get the job from the public endpoint (more reliable)
    let result = await GET(`jobs/${jobId}`)
    
    if (result.error) {
      // If that fails, try the authenticated endpoints
      result = await GET(`my-jobs/${jobId}`)
      
      if (result.error) {
        // Last resort, try the POST endpoint
        result = await POST(`my-jobs/get/${jobId}`, {})
      }
    }
    
    if (!result.error) {
      // Extract job details from the response
      let jobDetails = null
      
      if (result.response && result.response.response) {
        jobDetails = result.response.response
      } else if (result.response) {
        jobDetails = result.response
      }
      
      if (jobDetails) {
        // Map job details directly to our form data
        console.log('Loading job details into form:', jobDetails)
        
        // Direct field mapping - cleaner approach
        jobData.id = jobDetails.id || null
        jobData.user_id = jobDetails.user_id || null
        jobData.created_at = jobDetails.created_at || null
        jobData.updated_at = jobDetails.updated_at || null
        
        // Company details
        jobData.company_name = jobDetails.company_name || ''
        jobData.company_logo = jobDetails.company_logo || null
        jobData.company_website = jobDetails.company_website || ''
        jobData.company_size = jobDetails.company_size || '1-10'
        
        // Job details
        jobData.title = jobDetails.title || ''
        jobData.type = jobDetails.type || 'full-time'
        jobData.location = jobDetails.location || ''
        jobData.salary_range = jobDetails.salary_range || ''
        jobData.description = jobDetails.description || ''
        jobData.responsibilities = jobDetails.responsibilities || ''
        
        // Requirements
        jobData.experience_level = jobDetails.experience_level || 'intermediate'
        jobData.education_level = jobDetails.education_level || 'bachelor'
        jobData.required_skills = jobDetails.required_skills || ''
        jobData.preferred_skills = jobDetails.preferred_skills || ''
        
        // Additional info
        jobData.benefits = jobDetails.benefits || ''
        jobData.contact_email = jobDetails.contact_email || ''
        jobData.status = jobDetails.status || 'posted'
        
        // Handle application deadline (date format)
        if (jobDetails.application_deadline) {
          try {
            const date = new Date(jobDetails.application_deadline)
            if (!isNaN(date.getTime())) {
              jobData.application_deadline = date.toISOString().split('T')[0]
            } else {
              jobData.application_deadline = jobDetails.application_deadline
            }
          } catch (e) {
            console.error('Error formatting application_deadline:', e)
            jobData.application_deadline = ''
          }
        } else {
          jobData.application_deadline = ''
        }
        
        console.log('Job data successfully loaded into form:', jobData)
        
        // Update document title
        document.title = `Edit: ${jobData.title} | Job Portal`
      } else {
        throw new Error('Invalid job data received')
      }
    } else {
      error.value = true
      errorMessage.value = result.reason || 'Failed to load job details'
      toast.error(errorMessage.value)
    }
  } catch (err) {
    console.error('Error loading job details:', err)
    error.value = true
    errorMessage.value = 'An unexpected error occurred while loading job details'
    toast.error('Failed to load job details')
  } finally {
    isLoading.value = false
  }
}

// Save job changes
const saveJobChanges = async () => {
  const jobId = route.query.id
  
  if (!jobId) {
    toast.error('No job ID provided')
    return
  }
  
  // Basic validation
  if (!jobData.title) {
    toast.error('Job title is required')
    document.getElementById('jobTitle').focus()
    return
  }
  
  if (!jobData.company_name) {
    toast.error('Company name is required')
    document.getElementById('companyName').focus()
    return
  }
  
  try {
    isSaving.value = true
    toast.info('Saving changes...')
    
    // Prepare the data for submission
    const formData = new FormData()
    
    // Add the _method field for Laravel to interpret as PUT
    formData.append('_method', 'PUT')
    
    // Fields to exclude from form submission (system fields that should not be updated)
    const excludeFields = ['id', 'user_id', 'created_at', 'updated_at', 'company_logo']
    
    // Add all job data fields to the form data
    Object.entries(jobData).forEach(([key, value]) => {
      // Skip excluded fields and null/undefined values
      if (!excludeFields.includes(key) && value !== null && value !== undefined) {
        formData.append(key, value)
      }
    })
    
    // Handle file upload if a new logo was selected
    const fileInput = document.querySelector('#companyLogoInput')
    if (fileInput && fileInput.files.length > 0) {
      formData.append('company_logo', fileInput.files[0])
    }
    
    console.log('Submitting job update...')
    
    // Submit the form data
    const result = await POST(`my-jobs/${jobId}`, formData)
    
    if (!result.error) {
      toast.success('Job updated successfully')
      
      // Show additional options after successful save
      if (confirm('Job updated successfully! Would you like to view the job details?')) {
        router.push(`/jobdetails?id=${jobId}`)
      } else {
        // Redirect back to manage jobs
        setTimeout(() => {
          router.push('/managejob')
        }, 500)
      }
    } else {
      // Display specific error based on the response
      if (result.status === 403) {
        toast.error('You do not have permission to edit this job')
      } else if (result.status === 422) {
        toast.error('Validation error: Please check all fields')
      } else {
        toast.error(result.reason || 'Failed to update job')
      }
      console.error('Error details:', result)
    }
  } catch (err) {
    console.error('Error saving job changes:', err)
    toast.error('Failed to save changes: ' + (err.message || 'Unknown error'))
  } finally {
    isSaving.value = false
  }
}

// Function to get the full URL for the company logo
const getLogoUrl = (logo) => {
  if (!logo) return null
  
  // If logo is already a full URL, return it
  if (logo.startsWith('http://') || logo.startsWith('https://')) {
    return logo
  }
  
  // Otherwise, construct the URL using the base URL from Config
  return `${Config.baseURL.replace('/api', '')}/images/${logo}`
}

// Handle logo file input change
const handleLogoChange = (event) => {
  const file = event.target.files[0]
  if (!file) return
  
  // Check file size (2MB limit)
  if (file.size > 2 * 1024 * 1024) {
    toast.error('Logo is too large. Please upload an image less than 2MB.')
    event.target.value = ''
    return
  }
  
  // Create temporary local URL for preview
  const reader = new FileReader()
  reader.onload = (e) => {
    // Update preview by setting a temporary URL
    jobData.company_logo = e.target.result
  }
  reader.readAsDataURL(file)
}

// Load job details when component mounts
onMounted(() => {
  loadJobDetails()
})
</script>

<style scoped>
.edit-job-container {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 2rem;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.view-job-btn {
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  text-align: center;
  transition: background-color 0.3s;
  background-color: #0d6efd;
  color: white;
  border: none;
}

.view-job-btn:hover {
  background-color: #0b5ed7;
}

.form-section {
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.form-section h2 {
  margin-bottom: 1.5rem;
  color: #374151;
  font-size: 1.5rem;
}

.form-row {
  display: flex;
  gap: 1.5rem;
}

.form-row .form-group {
  flex: 1;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #4b5563;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group textarea {
  resize: vertical;
  min-height: 100px;
}

/* Logo upload styles */
.logo-upload-container {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-top: 0.5rem;
}

.logo-preview {
  width: 100px;
  height: 100px;
  object-fit: contain;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  background-color: #f9fafb;
  padding: 0.5rem;
}

.logo-upload {
  flex: 1;
}

.help-text {
  display: block;
  margin-top: 0.5rem;
  color: #6b7280;
  font-size: 0.875rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
  margin-top: 2rem;
}

.save-btn,
.cancel-btn,
.preview-btn {
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  text-align: center;
  transition: background-color 0.3s;
}

.save-btn {
  background-color: #0d6efd;
  color: white;
  border: none;
}

.save-btn:hover:not(:disabled) {
  background-color: #0b5ed7;
}

.save-btn:disabled {
  background-color: #b2c8f5;
  cursor: not-allowed;
}

.preview-btn {
  background-color: #6c757d;
  color: white;
  border: none;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.preview-btn:hover {
  background-color: #5a6268;
}

.cancel-btn {
  background-color: #f3f4f6;
  color: #4b5563;
  border: 1px solid #d1d5db;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.cancel-btn:hover {
  background-color: #e5e7eb;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  text-align: center;
}

.loading-spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #0d6efd;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-message {
  background-color: #fee2e2;
  color: #ef4444;
  padding: 1.5rem;
  border-radius: 8px;
  margin: 1rem 0;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.error-message i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.retry-btn, .back-btn {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  text-align: center;
}

.retry-btn {
  background-color: #ef4444;
  color: white;
  border: none;
  margin-right: 1rem;
}

.back-btn {
  background-color: #6b7280;
  color: white;
  text-decoration: none;
}
</style>
