<template>
  <!-- Search Section -->
  <section class="search-section">
    <div class="search-container">
      <form @submit.prevent="searchJobs">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search for jobs, skills, or companies..." v-model="searchQuery" />
        </div>
        <button type="submit" class="search-btn">Search Jobs</button>
      </form>
    </div>
  </section>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Filters Sidebar -->
    <aside class="filters-sidebar">
      <div class="filter-group">
        <h3>Job Type</h3>
        <label class="filter-option">
          <input type="checkbox" checked />
          <span>Full-time</span>
        </label>
        <label class="filter-option">
          <input type="checkbox" />
          <span>Part-time</span>
        </label>
        <label class="filter-option">
          <input type="checkbox" />
          <span>Contract</span>
        </label>
        <label class="filter-option">
          <input type="checkbox" />
          <span>Freelance</span>
        </label>
      </div>

      <div class="filter-group">
        <h3>Experience Level</h3>
        <label class="filter-option">
          <input type="checkbox" />
          <span>Entry Level</span>
        </label>
        <label class="filter-option">
          <input type="checkbox" checked />
          <span>Intermediate</span>
        </label>
        <label class="filter-option">
          <input type="checkbox" />
          <span>Expert</span>
        </label>
      </div>

      <div class="filter-group">
        <h3>Salary Range</h3>
        <div class="salary-range">
          <input type="range" min="0" max="200" value="50" />
          <div class="range-values">
            <span>$0</span>
            <span>$200k+</span>
          </div>
        </div>
      </div>

      <div class="filter-group">
        <h3>Location</h3>
        <div class="location-input">
          <i class="fas fa-map-marker-alt"></i>
          <input type="text" placeholder="Enter location" />
        </div>
        <label class="filter-option">
          <input type="checkbox" checked />
          <span>Remote</span>
        </label>
      </div>

      <button class="apply-filters-btn">Apply Filters</button>
    </aside>

    <!-- Jobs List -->
    <section class="jobs-list">
      <div class="jobs-header">
        <h2>Available Jobs</h2>
        <div class="sort-options">
          <select v-model="sortOption" @change="loadJobs">
            <option value="newest">Newest</option>
            <option value="relevant">Most Relevant</option>
            <option value="salary">Highest Salary</option>
          </select>
        </div>
      </div>

      <!-- Loading indicator -->
      <div v-if="isLoading" class="loading-container">
        <div class="loading-spinner"></div>
        <p>Loading jobs...</p>
      </div>

      <!-- Error message -->
      <div v-else-if="error" class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        <p>{{ errorMessage }}</p>
        <button @click="loadJobs" class="retry-btn">Try Again</button>
      </div>

      <!-- No jobs found message -->
      <div v-else-if="jobs.length === 0" class="no-jobs-message">
        <i class="fas fa-search"></i>
        <p>No jobs found. Try adjusting your search criteria.</p>
      </div>

      <!-- Job listings -->
      <div v-else class="job-cards">
        <!-- Job Card (dynamically generated) -->
        <div v-for="job in jobs" :key="job.id" class="job-card">
          <div class="job-header">
            <img 
              :src="job.company_logo ? `${Config.baseURL.replace('api/', '')}images/${job.company_logo}` : ''" 
              alt="Company Logo" 
            />
            <div class="job-title">
              <h3>
                <RouterLink :to="`/jobdetails?id=${job.id}`" class="job-title-link">
                  {{ job.title || 'Job Title Not Available' }}
                </RouterLink>
              </h3>
              <span class="company-name">{{ job.company_name || 'Company Not Specified' }}</span>
            </div>
            <button class="save-job"><i class="far fa-bookmark"></i></button>
          </div>
          <div class="job-details">
            <span><i class="fas fa-map-marker-alt"></i> {{ job.location || 'Location Not Specified' }}</span>
            <span><i class="fas fa-clock"></i> {{ job.type || 'Job Type Not Specified' }}</span>
            <span><i class="fas fa-dollar-sign"></i> {{ job.salary_range || 'Salary Not Specified' }}</span>
          </div>
          <p class="job-description">
            {{ truncateDescription(job.description) }}
          </p>
          <div class="job-tags">
            <span v-for="(skill, index) in getSkills(job.required_skills)" :key="index">{{ skill }}</span>
          </div>
          <div class="job-footer">
            <span class="posted-date">Posted {{ formatDate(job.created_at) }}</span>
            <RouterLink :to="`/applyjob?id=${job.id}`" class="apply-btn">Apply Now</RouterLink>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="pagination" v-if="jobs.length > 0">
        <button class="pagination-btn active">1</button>
        <button class="pagination-btn">2</button>
        <button class="pagination-btn">3</button>
        <button class="pagination-btn">4</button>
        <button class="pagination-btn">5</button>
        <button class="pagination-btn next">Next <i class="fas fa-chevron-right"></i></button>
      </div>
    </section>
  </main>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue'
import { GET, POST } from '@/scripts/Fetch'
import { useToast } from '@/scripts/toast'
import axios from 'axios'
import Config from '@/scripts/Config'
import Cookie from '@/scripts/Cookie'

// State
const jobs = ref([])
const isLoading = ref(true)
const error = ref(false)
const errorMessage = ref('')
const searchQuery = ref('')
const sortOption = ref('newest')
const toast = useToast()

// Load jobs from API
const loadJobs = async () => {
  try {
    isLoading.value = true
    error.value = false
    
    // Construct API endpoint with query params
    let endpoint = 'jobs'
    
    // Add search query if present
    if (searchQuery.value) {
      endpoint += `?search=${encodeURIComponent(searchQuery.value)}`
    }
    
    // Add sort option
    const sortParam = searchQuery.value ? '&' : '?'
    if (sortOption.value === 'newest') {
      endpoint += `${sortParam}sort=newest`
    } else if (sortOption.value === 'salary') {
      endpoint += `${sortParam}sort=salary`
    } else {
      endpoint += `${sortParam}sort=relevant`
    }
    
    console.log('Fetching jobs from API endpoint:', endpoint)
    console.log('API URL:', Config.baseURL + endpoint)
    
    // Fetch jobs with our GET utility
    const result = await GET(endpoint)
    
    console.log('API response:', result)
    
    if (!result.error) {
      // The Laravel backend returns jobs directly in result.response.response format
      if (result.response && Array.isArray(result.response)) {
        jobs.value = result.response
      } 
      // Handle case where response is nested one level deeper
      else if (result.response && typeof result.response === 'object' && result.response.response) {
        // This is the correct format from our Laravel backend
        jobs.value = Array.isArray(result.response.response) ? result.response.response : []
      } 
      // Fallback if format doesn't match the expected structure
      else {
        jobs.value = []
        console.error('Unexpected response format:', result)
      }
      
      console.log('Jobs loaded successfully:', jobs.value.length)
      
      // If no jobs found with search criteria
      if (jobs.value.length === 0 && searchQuery.value) {
        toast.info(`No jobs found for "${searchQuery.value}". Try different keywords.`)
      } else if (jobs.value.length === 0) {
        toast.info('No jobs found. Please check if there are any jobs posted in the database.')
      } else {
        toast.success(`Found ${jobs.value.length} jobs`)
      }
    } else {
      error.value = true
      errorMessage.value = result.reason || 'Failed to load jobs. Please try again.'
      toast.error(errorMessage.value)
    }
  } catch (err) {
    console.error('Error loading jobs:', err)
    error.value = true
    errorMessage.value = 'An unexpected error occurred. Please try again.'
    toast.error(errorMessage.value)
  } finally {
    isLoading.value = false
  }
}

// Search jobs function
const searchJobs = () => {
  loadJobs()
}

// Helper functions
const truncateDescription = (description) => {
  if (!description) return 'No description provided'
  
  // Strip HTML tags if present
  const strippedDesc = description.replace(/<[^>]*>/g, '')
  
  // Truncate to 150 characters
  return strippedDesc.length > 150 
    ? strippedDesc.substring(0, 150) + '...' 
    : strippedDesc
}

const getSkills = (skillsString) => {
  if (!skillsString) return []
  
  try {
    // Check if it's already an array
    if (Array.isArray(skillsString)) {
      return skillsString.slice(0, 4)
    }
    
    // Split by comma and clean up
    return skillsString
      .split(',')
      .map(skill => skill.trim())
      .filter(skill => skill) // Remove empty strings
      .slice(0, 4) // Get at most 4 skills
  } catch (error) {
    console.error('Error parsing skills:', error)
    return []
  }
}

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

// Load jobs on component mount
onMounted(() => {
  loadJobs()
})
</script>

<style scoped>
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
</style>
