<template>
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Senior Frontend Developer</h1>
      <p>Tech Solutions Inc.</p>
      <div class="action-buttons">
        <button class="save-job-btn"><i class="fas fa-bookmark"></i> Save Job</button>
        <a href="/applyjob" class="apply-now-btn" onclick="window.location.href='/applyjob'"
          ><i class="fas fa-paper-plane"></i> Apply Now</a
        >
      </div>
    </div>
  </section>

  <!-- Job Description Section -->
  <section class="job-description">
    <h2>Job Description</h2>
    <p>
      We are looking for a Senior Frontend Developer to join our team. The ideal candidate will have
      experience with React, TypeScript, and modern web technologies.
    </p>
    <h3>Responsibilities:</h3>
    <ul>
      <li>Develop and maintain web applications</li>
      <li>Collaborate with UX designers to implement designs</li>
      <li>Optimize application performance</li>
      <li>Write clean, maintainable, and efficient code</li>
      <li>Participate in code reviews and team meetings</li>
    </ul>
    <h3>Requirements:</h3>
    <ul>
      <li>5+ years of experience in frontend development</li>
      <li>Proficiency in React and TypeScript</li>
      <li>Strong understanding of HTML, CSS, and JavaScript</li>
      <li>Experience with modern build tools and version control</li>
      <li>Excellent problem-solving and communication skills</li>
    </ul>
  </section>

  <!-- Company Information Section -->
  <section class="company-info">
    <div class="company-info-content">
      <h2>Company Information</h2>
      <p>
        Tech Solutions Inc. is a leading technology company specializing in innovative software
        solutions. We are committed to creating a positive and inclusive work environment where
        employees can grow and thrive.
      </p>
    </div>
  </section>

  <!-- Application Instructions Section -->
  <section class="application-instructions">
    <h2>How to Apply</h2>
    <p>
      To apply for this position, please send your resume and a cover letter to
      <a href="mailto:careers@techsolutions.com">careers@techsolutions.com</a>.
    </p>
    <p>We look forward to hearing from you!</p>
  </section>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { RouterLink } from 'vue-router'
import { GET } from '@/scripts/Fetch'
import { useToast } from '@/scripts/toast'
import Config from '@/scripts/Config'
import Cookie from '@/scripts/Cookie'

const route = useRoute()
const router = useRouter()
const toast = useToast()

// State
const isLoading = ref(true)
const error = ref(false)
const errorMessage = ref('')
const jobData = reactive({
  id: null,
  title: '',
  company_name: '',
  company_logo: null,
  company_website: '',
  company_size: '',
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
  created_at: null,
  updated_at: null
})

// Props
const props = defineProps({
  id: {
    type: [String, Number],
    default: null
  }
})

// Load job details
const loadJobDetails = async () => {
  // Use ID from props if available, otherwise from route query
  const jobId = props.id || route.query.id
  
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
    
    // Get job details from public endpoint
    const result = await GET(`jobs/${jobId}`)
    
    console.log('Job details response:', result)
    
    if (!result.error) {
      // Extract job data from the response
      let jobDetails = null
      
      if (result.response && result.response.response) {
        jobDetails = result.response.response
      } else if (result.response) {
        jobDetails = result.response
      }
      
      // Update job data if we found valid job details
      if (jobDetails) {
        // Update all job data fields
        Object.keys(jobData).forEach(key => {
          if (jobDetails[key] !== undefined) {
            jobData[key] = jobDetails[key]
          }
        })
        
        document.title = `${jobData.title} at ${jobData.company_name} | Job Portal`
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

// Helper Functions
const getLogoUrl = (logo) => {
  if (!logo) return null
  
  // If logo is already a full URL, return it
  if (logo.startsWith('http://') || logo.startsWith('https://')) {
    return logo
  }
  
  // Otherwise, construct the URL using the base URL from Config
  return `${Config.baseURL.replace('/api', '')}/images/${logo}`
}

const formatJobType = (type) => {
  if (!type) return ''
  
  const types = {
    'full-time': 'Full Time',
    'part-time': 'Part Time',
    'contract': 'Contract',
    'freelancer': 'Freelance'
  }
  
  return types[type] || type
}

const formatExperienceLevel = (level) => {
  if (!level) return ''
  
  const levels = {
    'entry': 'Entry Level',
    'intermediate': 'Mid Level',
    'senior': 'Senior Level',
    'lead': 'Lead/Management'
  }
  
  return levels[level] || level
}

const formatEducationLevel = (level) => {
  if (!level) return ''
  
  const levels = {
    'high-school': 'High School',
    'associate': 'Associate Degree',
    'bachelor': 'Bachelor\'s Degree',
    'master': 'Master\'s Degree',
    'phd': 'PhD'
  }
  
  return levels[level] || level
}

const formatDescription = (text) => {
  if (!text) return ''
  
  // Replace newlines with <br> tags
  let formatted = text.replace(/\n/g, '<br>')
  
  // Wrap paragraphs
  formatted = formatted.split('<br><br>').map(para => `<p>${para}</p>`).join('')
  
  return formatted
}

const parseSkills = (skillsString) => {
  if (!skillsString) return []
  return skillsString.split(',').map(skill => skill.trim()).filter(skill => skill)
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  } catch (e) {
    console.error('Error formatting date:', e)
    return dateString
  }
}

const formatUrl = (url) => {
  if (!url) return ''
  
  // Add https:// if missing
  if (!url.startsWith('http://') && !url.startsWith('https://')) {
    return `https://${url}`
  }
  
  return url
}

// Check if current user is the owner of this job
const isCurrentUserOwner = computed(() => {
  const token = Cookie.getCookie('job-app');
  if (!token) return false;
  
  try {
    // Parse the token to get user ID (assuming JWT)
    const base64Url = token.split('.')[1];
    if (!base64Url) return false;
    
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(
      atob(base64)
        .split('')
        .map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
        .join('')
    );
    
    const payload = JSON.parse(jsonPayload);
    return payload.user_id === jobData.user_id;
  } catch (error) {
    console.error('Error parsing token:', error);
    return false;
  }
});

// Load job details when component mounts
onMounted(() => {
  loadJobDetails()
})
</script>

<style scoped>
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 5rem 2rem;
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
  padding: 2rem;
  border-radius: 8px;
  margin: 2rem auto;
  max-width: 800px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.error-message i {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.retry-btn, .back-btn {
  margin-top: 1rem;
  padding: 0.5rem 1.5rem;
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

.hero {
  padding: 3rem 1rem;
  background-color: #f3f4f6;
  text-align: center;
}

.hero-content {
  max-width: 800px;
  margin: 0 auto;
}

.company-logo {
  width: 100px;
  height: 100px;
  margin: 0 auto 1.5rem;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
}

.company-logo img {
  max-width: 80%;
  max-height: 80%;
  object-fit: contain;
}

.company-name {
  font-size: 1.25rem;
  color: #4b5563;
  margin-bottom: 1rem;
}

.job-meta {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.job-meta span {
  display: flex;
  align-items: center;
  color: #6b7280;
}

.job-meta i {
  margin-right: 0.5rem;
  color: #0d6efd;
}

.action-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 1.5rem;
}

.save-job-btn, .apply-now-btn, .edit-job-btn {
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.save-job-btn {
  background-color: white;
  color: #0d6efd;
  border: 1px solid #0d6efd;
}

.save-job-btn:hover {
  background-color: #f0f9ff;
}

.apply-now-btn {
  background-color: #0d6efd;
  color: white;
  border: none;
  text-decoration: none;
}

.apply-now-btn:hover {
  background-color: #0b5ed7;
}

.edit-job-btn {
  background-color: #6c757d;
  color: white;
  border: none;
  text-decoration: none;
  margin-left: 0.5rem;
}

.edit-job-btn:hover {
  background-color: #5a6268;
}

.job-description, .company-info, .application-instructions {
  max-width: 800px;
  margin: 2rem auto;
  padding: 0 1rem;
}

.job-description h2, .company-info h2, .application-instructions h2 {
  color: #1f2937;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.job-description h3 {
  margin-top: 1.5rem;
  margin-bottom: 1rem;
  color: #374151;
}

.job-description ul {
  padding-left: 1.5rem;
  margin-bottom: 1.5rem;
}

.job-description li {
  margin-bottom: 0.5rem;
}

.requirements {
  background-color: #f9fafb;
  border-radius: 8px;
  padding: 1.5rem;
  margin: 1.5rem 0;
}

.req-group {
  margin-bottom: 1.5rem;
}

.req-group:last-child {
  margin-bottom: 0;
}

.req-group h4 {
  color: #4b5563;
  margin-bottom: 0.5rem;
}

.skills {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.skill-tag {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
}

.skill-tag.required {
  background-color: #e0f2fe;
  color: #0369a1;
}

.skill-tag.preferred {
  background-color: #f0fdf4;
  color: #166534;
}

.company-info-content {
  background-color: #f9fafb;
  border-radius: 8px;
  padding: 1.5rem;
}

.company-details {
  margin-bottom: 1.5rem;
}

.company-details p {
  margin-bottom: 0.5rem;
}

.benefits h3 {
  margin-bottom: 1rem;
  color: #374151;
}

.application-instructions a {
  color: #0d6efd;
  text-decoration: none;
}

.application-instructions a:hover {
  text-decoration: underline;
}

@media (max-width: 640px) {
  .job-meta {
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
  }
  
  .action-buttons {
    flex-direction: column;
    width: 100%;
  }
  
  .save-job-btn, .apply-now-btn {
    width: 100%;
  }
}
</style>
