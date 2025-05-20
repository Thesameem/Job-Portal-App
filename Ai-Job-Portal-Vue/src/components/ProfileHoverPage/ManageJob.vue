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
      <div v-if="loading" class="loading-container">
        <div class="loading-spinner"></div>
        <p>Loading your applications...</p>
      </div>
      
      <div v-else-if="error" class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        <p>{{ errorMessage }}</p>
        <button @click="loadMyApplications" class="retry-btn">Try Again</button>
      </div>
      
      <div v-else-if="myApplications.length === 0" class="no-applications">
        <i class="fas fa-inbox"></i>
        <p>You haven't applied to any jobs yet.</p>
        <RouterLink to="/jobs" class="browse-jobs-btn">Browse Jobs</RouterLink>
      </div>
      
      <div v-else v-for="application in myApplications" :key="application.id" class="application-item">
        <h3>{{ application.job.title }}</h3>
        <p>{{ application.job.company_name }}</p>
        <span :class="['status', `status-${application.status}`]">{{ formatStatus(application.status) }}</span>
        <RouterLink :to="`/jobdetails?id=${application.job_id}`" class="view-details">View Details</RouterLink>
      </div>
    </div>
  </section>

  <!-- Received Applications Section -->
  <section class="received-applications">
    <h2>Received Applications</h2>
    <div class="application-list">
      <div v-if="loadingReceived" class="loading-container">
        <div class="loading-spinner"></div>
        <p>Loading received applications...</p>
      </div>
      
      <div v-else-if="errorReceived" class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        <p>{{ errorMessageReceived }}</p>
        <button @click="loadReceivedApplications" class="retry-btn">Try Again</button>
      </div>
      
      <div v-else-if="receivedApplications.length === 0" class="no-applications">
        <i class="fas fa-inbox"></i>
        <p>No applications received yet.</p>
      </div>
      
      <div v-else v-for="job in receivedApplications" :key="job.id" class="application-item">
        <h3>{{ job.title }}</h3>
        <p>Company: {{ job.company_name }}</p>
        <p>Applications: {{ job.applications_count }}</p>
        <div class="application-actions">
          <span :class="['status', getStatusClass(job.status)]">{{ job.status }}</span>
          <RouterLink :to="`/reviewjob?id=${job.id}`" class="review-btn">
            Review Applications ({{ job.applications_count }})
          </RouterLink>
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
    <div v-else-if="postedJobsError" class="error-message">
      <i class="fas fa-exclamation-circle"></i>
      <p>{{ postedJobsErrorMessage }}</p>
      <button @click="loadUserJobs" class="retry-btn">Try Again</button>
    </div>
    
    <!-- No jobs state -->
    <div v-else-if="userJobs.length === 0" class="no-applications">
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
          >
            Edit
          </RouterLink>
          <RouterLink :to="`/reviewjob?id=${job.id}`" class="review-btn">
            View Applicants ({{ job.applications_count || 0 }})
          </RouterLink>
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
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { GET, POST } from '@/scripts/Fetch'
import { useToast } from '@/scripts/toast'

const router = useRouter()
const toast = useToast()

// State
const myApplications = ref([])
const receivedApplications = ref([])
const userJobs = ref([])
const loading = ref(true)
const loadingReceived = ref(true)
const isLoading = ref(true)
const error = ref(false)
const errorMessage = ref('')
const errorReceived = ref(false)
const errorMessageReceived = ref('')
const postedJobsError = ref(false)
const postedJobsErrorMessage = ref('')

// Load user's posted jobs
const loadUserJobs = async () => {
  try {
    isLoading.value = true
    postedJobsError.value = false
    
    const result = await GET('my-jobs')
    
    if (!result.error) {
      let jobs = []
      if (result.response && Array.isArray(result.response)) {
        jobs = result.response
      } else if (result.response && result.response.response && Array.isArray(result.response.response)) {
        jobs = result.response.response
      }

      // Fetch applications count for each job
      const jobsWithApplications = await Promise.all(
        jobs.map(async (job) => {
          try {
            const applicationsResult = await GET(`jobs/${job.id}/applications`)
            return {
              ...job,
              applications_count: applicationsResult.error ? 0 : 
                (Array.isArray(applicationsResult.response) ? applicationsResult.response.length : 0)
            }
          } catch (err) {
            console.error(`Error fetching applications for job ${job.id}:`, err)
            return {
              ...job,
              applications_count: 0
            }
          }
        })
      )
      
      userJobs.value = jobsWithApplications
      
      if (userJobs.value.length === 0) {
        toast.info('You have not posted any jobs yet.')
      }
    } else {
      postedJobsError.value = true
      postedJobsErrorMessage.value = result.reason || 'Failed to load your posted jobs.'
      toast.error(postedJobsErrorMessage.value)
    }
  } catch (err) {
    console.error('Error loading user jobs:', err)
    postedJobsError.value = true
    postedJobsErrorMessage.value = 'An unexpected error occurred.'
    toast.error('Failed to load your posted jobs.')
  } finally {
    isLoading.value = false
  }
}

// Load received applications
const loadReceivedApplications = async () => {
  try {
    loadingReceived.value = true
    errorReceived.value = false
    
    const result = await GET('my-jobs')
    
    if (!result.error) {
      let jobs = []
      if (result.response && Array.isArray(result.response)) {
        jobs = result.response
      } else if (result.response && result.response.response && Array.isArray(result.response.response)) {
        jobs = result.response.response
      }
      
      // For each job, fetch its applications count
      const jobsWithApplications = await Promise.all(
        jobs.map(async (job) => {
          try {
            const applicationsResult = await GET(`jobs/${job.id}/applications`)
            return {
              ...job,
              applications_count: applicationsResult.error ? 0 : 
                (Array.isArray(applicationsResult.response) ? applicationsResult.response.length : 0)
            }
          } catch (err) {
            console.error(`Error fetching applications for job ${job.id}:`, err)
            return {
              ...job,
              applications_count: 0
            }
          }
        })
      )
      
      // Filter jobs that have applications
      receivedApplications.value = jobsWithApplications.filter(job => job.applications_count > 0)
      
      if (receivedApplications.value.length === 0) {
        toast.info('No applications received yet.')
      }
    } else {
      errorReceived.value = true
      errorMessageReceived.value = result.reason || 'Failed to load received applications.'
      toast.error(errorMessageReceived.value)
    }
  } catch (err) {
    console.error('Error loading received applications:', err)
    errorReceived.value = true
    errorMessageReceived.value = 'An unexpected error occurred.'
    toast.error('Failed to load received applications.')
  } finally {
    loadingReceived.value = false
  }
}

// Load my applications
const loadMyApplications = async () => {
  try {
    loading.value = true
    error.value = false
    
    const result = await GET('my-applications')
    
    if (!result.error) {
      myApplications.value = result.response || []
    } else {
      error.value = true
      errorMessage.value = result.reason || 'Failed to load your applications.'
      toast.error(errorMessage.value)
    }
  } catch (err) {
    console.error('Error loading my applications:', err)
    error.value = true
    errorMessage.value = 'An unexpected error occurred.'
    toast.error('Failed to load your applications.')
  } finally {
    loading.value = false
  }
}

// Update job status
const updateJobStatus = async (jobId, newStatus) => {
  try {
    toast.info(`${newStatus === 'posted' ? 'Activating' : 'Closing'} job...`)
    
    const result = await POST(`my-jobs/${jobId}`, { 
      _method: 'PUT',
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

// Helper functions
const formatDate = (dateString) => {
  if (!dateString) return 'recently'
  
  try {
    const postDate = new Date(dateString)
    
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

const formatStatus = (status) => {
  const statusMap = {
    new: 'New',
    reviewing: 'Under Review',
    interview: 'Interview Scheduled',
    rejected: 'Rejected'
  }
  return statusMap[status] || status
}

const getStatusClass = (status) => {
  switch(status) {
    case 'posted': return 'active'
    case 'draft': return 'inactive'
    case 'closed': return 'closed'
    default: return ''
  }
}

// Load data when component mounts
onMounted(async () => {
  // Load all data in parallel
  await Promise.all([
    loadUserJobs(),
    loadReceivedApplications(),
    loadMyApplications()
  ])
})
</script>

<style scoped>
/* Existing styles */

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  text-align: center;
  min-height: 200px;
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

/* Add skeleton loading animation */
@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

.skeleton {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
  border-radius: 4px;
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

.no-applications {
  text-align: center;
  padding: 3rem;
  color: #6b7280;
}

.no-applications i {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: #d1d5db;
}

.browse-jobs-btn {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background-color: #0d6efd;
  color: white;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
}

.application-item {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 1rem;
}

.application-item h3 {
  margin: 0 0 0.5rem 0;
  color: #2c3e50;
}

.application-item p {
  margin: 0.25rem 0;
  color: #666;
}

.status {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
}

.status.active {
  background-color: #d4edda;
  color: #155724;
}

.status.inactive {
  background-color: #fff3cd;
  color: #856404;
}

.status.closed {
  background-color: #f8d7da;
  color: #721c24;
}

.status-new {
  background-color: #e3f2fd;
  color: #1976d2;
}

.status-reviewing {
  background-color: #fff3cd;
  color: #856404;
}

.status-interview {
  background-color: #d4edda;
  color: #155724;
}

.status-rejected {
  background-color: #f8d7da;
  color: #721c24;
}

.application-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-top: 1rem;
  flex-wrap: wrap;
}

.review-btn,
.edit-btn,
.close-btn {
  padding: 0.5rem 1rem;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
  cursor: pointer;
  border: none;
  transition: background-color 0.3s;
}

.review-btn {
  background-color: #0d6efd;
  color: white;
}

.review-btn:hover {
  background-color: #0b5ed7;
}

.edit-btn {
  background-color: #6c757d;
  color: white;
}

.edit-btn:hover {
  background-color: #5a6268;
}

.close-btn {
  background-color: #dc3545;
  color: white;
}

.close-btn:hover {
  background-color: #c82333;
}

.view-details {
  color: #0d6efd;
  text-decoration: none;
  font-weight: 500;
}

.view-details:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .application-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .review-btn,
  .edit-btn,
  .close-btn {
    width: 100%;
    text-align: center;
  }
}
</style>
