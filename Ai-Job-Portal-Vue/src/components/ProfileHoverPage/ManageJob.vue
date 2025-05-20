<template>
  <!-- AI Matching Jobs Title -->
  <section class="ai-matching-title">
    <div class="title-content">
      <h2>AI Matching Jobs</h2>
      <p>Discover jobs that match your skills and preferences</p>
    </div>
  </section>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Welcome to Your Dashboard</h1>
      <p>Manage your job applications and stay updated with your recent activities.</p>
    </div>
  </section>

  <!-- Job Applications Section -->
  <section class="applications">
    <h2>Applied Applications</h2>
    <div class="application-list">
      <div class="application-item">
        <h3>Senior Frontend Developer</h3>
        <p>Tech Solutions Inc.</p>
        <span class="status">Pending</span>
        <RouterLink to="/jobdetails?id=1" class="view-details">View Details</RouterLink>
      </div>
      <div class="application-item">
        <h3>Full Stack Developer</h3>
        <p>Digital Innovations</p>
        <span class="status">Approved</span>
        <RouterLink to="/jobdetails?id=2" class="view-details">View Details</RouterLink>
      </div>
    </div>
  </section>

  <!-- Received Applications Section -->
  <section class="received-applications">
    <h2>Received Applications</h2>
    <div class="application-list">
      <div class="application-item">
        <h3>Frontend Developer Position</h3>
        <p>Applicant: John Doe</p>
        <p>Experience: 5 years</p>
        <div class="application-actions">
          <span class="status">New</span>
          <RouterLink to="/reviewjob" class="review-btn">Review</RouterLink>
          <button class="message-btn">Message</button>
        </div>
      </div>
      <div class="application-item">
        <h3>Backend Developer Position</h3>
        <p>Applicant: Jane Smith</p>
        <p>Experience: 3 years</p>
        <div class="application-actions">
          <span class="status">Under Review</span>
          <RouterLink to="/reviewjob" class="review-btn">Review</RouterLink>
          <button class="message-btn">Message</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Posted Jobs Section -->
  <section class="posted-jobs">
    <h2>Posted Jobs</h2>
    
    <!-- Loading state -->
    <div v-if="isLoading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>Loading your posted jobs...</p>
    </div>
    
    <!-- Error state -->
    <div v-else-if="error" class="error-message">
      <i class="fas fa-exclamation-circle"></i>
      <p>{{ errorMessage }}</p>
      <button @click="loadUserJobs" class="retry-btn">Try Again</button>
    </div>
    
    <!-- No jobs state -->
    <div v-else-if="userJobs.length === 0" class="no-jobs-message">
      <i class="fas fa-briefcase"></i>
      <p>You haven't posted any jobs yet.</p>
      <RouterLink to="/createjob" class="create-job-btn">Create Your First Job</RouterLink>
    </div>
    
    <!-- Job list -->
    <div v-else class="job-list">
      <div v-for="job in userJobs" :key="job.id" class="application-item">
        <h3>{{ job.title }}</h3>
        <p>Posted: {{ formatDate(job.created_at) }}</p>
        <p>Status: {{ job.status }}</p>
        <div class="application-actions">
          <span :class="['status', getStatusClass(job.status)]">{{ job.status }}</span>
          <RouterLink 
            :to="`/editjob?id=${job.id}`" 
            class="edit-btn"
            @click="() => console.log('Edit clicked for job ID:', job.id)"
          >
            Edit
          </RouterLink>
          <RouterLink to="/reviewjob" class="review-btn">View Applicants</RouterLink>
          <button 
            class="close-btn" 
            @click="updateJobStatus(job.id, job.status === 'posted' ? 'draft' : 'posted')"
          >
            {{ job.status === 'posted' ? 'Close Job' : 'Reactivate Job' }}
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Applied Applications Section 
  <section class="applied-applications">
    <h2>Applied Applications</h2>
    <div class="application-list">
      <div class="application-item">
        <h3>Senior Developer</h3>
        <p>Company: Tech Corp</p>
        <p>Applied: 3 days ago</p>
        <div class="application-status">
          <span class="status">Under Review</span>
          <button class="withdraw-btn">Withdraw</button>
        </div>
      </div>
      <div class="application-item">
        <h3>Lead Developer</h3>
        <p>Company: Digital Solutions</p>
        <p>Applied: 1 week ago</p>
        <div class="application-status">
          <span class="status">Interview Scheduled</span>
          <button class="withdraw-btn">Withdraw</button>
        </div>
      </div>
    </div>
  </section>
-->
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { GET, POST } from '@/scripts/Fetch'
import { useToast } from '@/scripts/toast'
import Config from '@/scripts/Config'
import Cookie from '@/scripts/Cookie'

const router = useRouter()
const toast = useToast()

// State 
const userJobs = ref([])
const isLoading = ref(true)
const error = ref(false)
const errorMessage = ref('')

// Load user's posted jobs
const loadUserJobs = async () => {
  try {
    isLoading.value = true
    error.value = false
    
    const result = await GET('my-jobs')
    
    console.log('User jobs response:', result)
    
    if (!result.error) {
      // Extract jobs based on the API response structure
      if (result.response && Array.isArray(result.response)) {
        userJobs.value = result.response
      } else if (result.response && result.response.response && Array.isArray(result.response.response)) {
        userJobs.value = result.response.response
      } else {
        userJobs.value = []
      }
      
      console.log('Loaded user jobs:', userJobs.value)
      
      if (userJobs.value.length === 0) {
        toast.info('You have not posted any jobs yet.')
      }
    } else {
      error.value = true
      errorMessage.value = result.reason || 'Failed to load your posted jobs.'
      toast.error(errorMessage.value)
    }
  } catch (err) {
    console.error('Error loading user jobs:', err)
    error.value = true
    errorMessage.value = 'An unexpected error occurred.'
    toast.error('Failed to load your posted jobs.')
  } finally {
    isLoading.value = false
  }
}

// Update job status (close/reactivate)
const updateJobStatus = async (jobId, newStatus) => {
  try {
    toast.info(`${newStatus === 'posted' ? 'Activating' : 'Closing'} job...`)
    
    const result = await POST(`my-jobs/${jobId}`, { 
      _method: 'PUT',  // Laravel requires this for PUT requests
      status: newStatus 
    })
    
    if (!result.error) {
      toast.success(`Job successfully ${newStatus === 'posted' ? 'activated' : 'closed'}.`)
      
      // Update the job in the local state
      const index = userJobs.value.findIndex(job => job.id === jobId)
      if (index !== -1) {
        userJobs.value[index].status = newStatus
      }
    } else {
      toast.error(result.reason || 'Failed to update job status.')
    }
  } catch (err) {
    console.error('Error updating job status:', err)
    toast.error('Failed to update job status.')
  }
}

// Format date for display
const formatDate = (dateString) => {
  if (!dateString) return 'recently'
  
  try {
    const postDate = new Date(dateString)
    
    // Check if the date is invalid
    if (isNaN(postDate.getTime())) {
      return 'recently'
    }
    
    const now = new Date()
    const diffTime = Math.abs(now - postDate)
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    
    if (diffDays === 0) return 'today'
    if (diffDays === 1) return 'yesterday'
    if (diffDays < 7) return `${diffDays} days ago`
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`
    if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`
    return `${Math.floor(diffDays / 365)} years ago`
  } catch (error) {
    console.error('Error formatting date:', error)
    return 'recently'
  }
}

// Get CSS class based on job status
const getStatusClass = (status) => {
  switch(status) {
    case 'posted': return 'active'
    case 'draft': return 'inactive'
    case 'closed': return 'closed'
    default: return ''
  }
}

// Load user jobs when component mounts
onMounted(() => {
  loadUserJobs()
})
</script>

<!-- <style scoped>
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

.retry-btn {
  background-color: #ef4444;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  margin-top: 1rem;
  cursor: pointer;
}

.no-jobs-message {
  padding: 3rem;
  text-align: center;
  color: #6b7280;
}

.no-jobs-message i {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: #d1d5db;
}

.create-job-btn {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background-color: #0d6efd;
  color: white;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
}

/* Status colors */
.status.active {
  background-color: #10b981;
  color: white;
}

.status.inactive {
  background-color: #f59e0b;
  color: white;
}

.status.closed {
  background-color: #6b7280;
  color: white;
}

/* Button styles */
.review-btn, .edit-btn {
  display: inline-block;
  padding: 8px 12px;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
  text-align: center;
  cursor: pointer;
  transition: background-color 0.3s, color 0.3s;
}

.review-btn {
  background-color: #0d6efd;
  color: white;
  border: 1px solid #0d6efd;
}

.review-btn:hover {
  background-color: #0b5ed7;
}

.edit-btn {
  background-color: #6c757d;
  color: white;
  border: 1px solid #6c757d;
}

.edit-btn:hover {
  background-color: #5a6268;
}

.close-btn {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.close-btn:hover {
  background-color: #bb2d3b;
}
</style> -->
