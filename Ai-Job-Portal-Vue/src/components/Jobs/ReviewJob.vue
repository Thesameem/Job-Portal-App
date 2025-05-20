<template>
  <div class="review-container">
    <h1>Review Applications</h1>

    <!-- Loading state -->
    <div v-if="loading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>Loading applications...</p>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="error-message">
      <i class="fas fa-exclamation-circle"></i>
      <p>{{ errorMessage }}</p>
      <button @click="loadApplications" class="retry-btn">Try Again</button>
    </div>

    <!-- No applications state -->
    <div v-else-if="applications.length === 0" class="no-applications">
      <i class="fas fa-briefcase"></i>
      <h2>No Applications Received Yet</h2>
      <p>You haven't received any applications for this job posting.</p>
      <div class="action-links">
        <RouterLink to="/createjob" class="create-job-btn">
          <i class="fas fa-plus"></i> Create New Job
        </RouterLink>
        <RouterLink to="/managejob" class="manage-jobs-btn">
          <i class="fas fa-tasks"></i> Manage Jobs
        </RouterLink>
      </div>
    </div>

    <!-- No jobs state -->
    <div v-else-if="!route.query.id" class="no-applications">
      <i class="fas fa-briefcase"></i>
      <h2>No Job Selected</h2>
      <p>Please select a job to view its applications.</p>
      <div class="action-links">
        <RouterLink to="/createjob" class="create-job-btn">
          <i class="fas fa-plus"></i> Create New Job
        </RouterLink>
        <RouterLink to="/managejob" class="manage-jobs-btn">
          <i class="fas fa-tasks"></i> Manage Jobs
        </RouterLink>
      </div>
    </div>

    <!-- Applications list -->
    <div v-else>
      <!-- Filter Section -->
      <div class="filter-section">
        <select v-model="statusFilter" class="filter-select">
          <option value="all">All Applications</option>
          <option value="new">New</option>
          <option value="reviewing">Under Review</option>
          <option value="interview">Interview Scheduled</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>

      <!-- Application Cards -->
      <div v-for="application in filteredApplications" :key="application.id" class="application-card">
        <div class="applicant-header">
          <div class="applicant-avatar">
            {{ application.fullname.charAt(0).toUpperCase() }}
          </div>
          <div class="applicant-info">
            <h2>{{ application.fullname }}</h2>
            <p>{{ application.email }}</p>
            <p>Applied {{ formatDate(application.created_at) }}</p>
          </div>
          <span :class="['status-badge', `status-${application.status}`]">
            {{ formatStatus(application.status) }}
          </span>
        </div>

        <div class="application-details">
          <div class="detail-row">
            <span class="detail-label">Phone:</span>
            <span class="detail-value">{{ application.phone }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Location:</span>
            <span class="detail-value">{{ application.location }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Skills:</span>
            <span class="detail-value">{{ application.skills }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Professional Summary:</span>
            <span class="detail-value">{{ application.summary }}</span>
          </div>
        </div>

        <div class="resume-section">
          <h3>Documents</h3>
          <div class="document-links">
            <button 
              @click="viewDocument(application.id, 'resume')" 
              class="document-link"
              :disabled="loading"
            >
              <i class="fas fa-eye"></i> 
              {{ loading ? 'Loading...' : 'View Resume' }}
            </button>
            <button 
              v-if="application.cover_letter" 
              @click="viewDocument(application.id, 'cover_letter')" 
              class="document-link"
              :disabled="loading"
            >
              <i class="fas fa-eye"></i> 
              {{ loading ? 'Loading...' : 'View Cover Letter' }}
            </button>
          </div>
        </div>

        <div class="action-buttons">
          <button 
            @click="updateStatus(application.id, 'reviewing')" 
            class="action-btn review-btn"
            :disabled="application.status === 'reviewing'"
          >
            <i class="fas fa-eye"></i> Start Review
          </button>
          <button 
            @click="updateStatus(application.id, 'interview')" 
            class="action-btn schedule-btn"
            :disabled="application.status === 'interview'"
          >
            <i class="fas fa-calendar-alt"></i> Schedule Interview
          </button>
          <button 
            @click="updateStatus(application.id, 'rejected')" 
            class="action-btn reject-btn"
            :disabled="application.status === 'rejected'"
          >
            <i class="fas fa-times"></i> Reject
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { GET, POST } from '@/scripts/Fetch';
import { useToast } from '@/scripts/toast';
import Config from '@/scripts/Config';

const route = useRoute();
const toast = useToast();

// State
const applications = ref([]);
const loading = ref(true);
const error = ref(false);
const errorMessage = ref('');
const statusFilter = ref('all');

// Computed
const filteredApplications = computed(() => {
  if (statusFilter.value === 'all') {
    return applications.value;
  }
  return applications.value.filter(app => app.status === statusFilter.value);
});

// Load applications
const loadApplications = async () => {
  try {
    loading.value = true;
    error.value = false;

    const jobId = route.query.id;
    if (!jobId) {
      throw new Error('Job ID is missing');
    }

    const response = await GET(`jobs/${jobId}/applications`);
    
    if (!response.error && response.response) {
      applications.value = response.response;
    } else {
      throw new Error(response.reason || 'Failed to load applications');
    }
  } catch (err) {
    console.error('Error loading applications:', err);
    error.value = true;
    errorMessage.value = err.message || 'Failed to load applications';
    toast.error(errorMessage.value);
  } finally {
    loading.value = false;
  }
};

// Update application status
const updateStatus = async (applicationId, newStatus) => {
  try {
    const response = await POST(`applications/${applicationId}`, {
      _method: 'PUT',
      status: newStatus
    });

    if (!response.error) {
      // Update local state
      const index = applications.value.findIndex(app => app.id === applicationId);
      if (index !== -1) {
        applications.value[index].status = newStatus;
      }
      toast.success('Application status updated successfully');
    } else {
      throw new Error(response.reason || 'Failed to update application status');
    }
  } catch (err) {
    console.error('Error updating application status:', err);
    toast.error(err.message || 'Failed to update application status');
  }
};

// Helper functions
const formatDate = (dateString) => {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  const now = new Date();
  const diffTime = Math.abs(now - date);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  
  if (diffDays === 0) return 'today';
  if (diffDays === 1) return 'yesterday';
  if (diffDays < 7) return `${diffDays} days ago`;
  if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
  if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`;
  return `${Math.floor(diffDays / 365)} years ago`;
};

const formatStatus = (status) => {
  const statusMap = {
    new: 'New',
    reviewing: 'Under Review',
    interview: 'Interview Scheduled',
    rejected: 'Rejected'
  };
  return statusMap[status] || status;
};

// Update the viewDocument function
const viewDocument = async (applicationId, fileType) => {
  try {
    const response = await GET(`applications/${applicationId}/download/${fileType}`);

    if (!response || response.error) {
      toast.error('Failed to view file. Please try again.');
      return;
    }

    // Open the file URL in a new tab
    window.open(response.url, '_blank');

  } catch (err) {
    console.error('Error viewing file:', err);
    if (err.response) {
      console.error('Response status:', err.response.status);
      console.error('Response data:', err.response.data);
    }
    toast.error('Failed to view file. Please try again later.');
  }
};

// Load applications when component mounts
onMounted(() => {
  loadApplications();
});
</script>

<style scoped>
.review-container {
  max-width: 1000px;
  margin: 2rem auto;
  padding: 2rem;
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
  background: #f8f9fa;
  border-radius: 8px;
  margin: 2rem auto;
  max-width: 600px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.no-applications i {
  font-size: 4rem;
  margin-bottom: 1rem;
  color: #0d6efd;
  display: block;
}

.no-applications h2 {
  color: #2c3e50;
  margin-bottom: 0.5rem;
  font-size: 1.5rem;
  text-align: center;
  width: 100%;
}

.no-applications p {
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
  text-align: center;
  width: 100%;
  max-width: 400px;
}

.action-links {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-top: 1.5rem;
  width: 100%;
  max-width: 400px;
}

.create-job-btn,
.manage-jobs-btn {
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  font-weight: 500;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  flex: 1;
  min-width: 160px;
}

.create-job-btn {
  background: #0d6efd;
  color: white;
}

.create-job-btn:hover {
  background: #0b5ed7;
  transform: translateY(-2px);
}

.manage-jobs-btn {
  background: #6c757d;
  color: white;
}

.manage-jobs-btn:hover {
  background: #5a6268;
  transform: translateY(-2px);
}

.application-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 1.5rem;
  padding: 1.5rem;
}

.applicant-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.applicant-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #0d6efd;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: bold;
}

.applicant-info h2 {
  margin: 0;
  color: #2c3e50;
}

.applicant-info p {
  margin: 0.25rem 0;
  color: #666;
}

.application-details {
  margin: 1rem 0;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 4px;
}

.detail-row {
  display: flex;
  margin-bottom: 0.5rem;
}

.detail-label {
  width: 150px;
  font-weight: 500;
  color: #333;
}

.detail-value {
  flex: 1;
  color: #666;
}

.document-links {
  display: flex;
  gap: 1rem;
  margin-top: 0.5rem;
}

.document-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  color: #0d6efd;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  font-size: 0.875rem;
}

.document-link:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.document-link:hover:not(:disabled) {
  background: #e9ecef;
  color: #0a58ca;
}

.document-link i {
  font-size: 1rem;
}

.action-buttons {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
  flex-wrap: wrap;
}

.action-btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  min-width: 160px;
  justify-content: center;
}

.action-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.review-btn {
  background: #0d6efd;
  color: white;
}

.review-btn:hover:not(:disabled) {
  background: #0b5ed7;
}

.schedule-btn {
  background: #17a2b8;
  color: white;
}

.schedule-btn:hover:not(:disabled) {
  background: #138496;
}

.reject-btn {
  background: #dc3545;
  color: white;
}

.reject-btn:hover:not(:disabled) {
  background: #c82333;
}

.action-btn i {
  font-size: 1rem;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
  margin-left: auto;
}

.status-new {
  background: #fff3cd;
  color: #856404;
}

.status-reviewing {
  background: #cce5ff;
  color: #004085;
}

.status-interview {
  background: #d4edda;
  color: #155724;
}

.status-rejected {
  background: #f8d7da;
  color: #721c24;
}

.filter-section {
  margin-bottom: 2rem;
  display: flex;
  gap: 1rem;
  align-items: center;
}

.filter-select {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  min-width: 150px;
}

@media (max-width: 768px) {
  .applicant-header {
    flex-direction: column;
    text-align: center;
  }

  .status-badge {
    margin: 1rem 0;
  }

  .action-buttons {
    flex-direction: column;
  }

  .action-btn {
    width: 100%;
  }

  .filter-section {
    flex-direction: column;
  }

  .filter-select {
    width: 100%;
  }

  .action-links {
    flex-direction: column;
  }

  .create-job-btn,
  .manage-jobs-btn {
    width: 100%;
    justify-content: center;
  }

  .no-applications {
    margin: 1rem;
    padding: 2rem;
  }

  .action-links {
    flex-direction: column;
    width: 100%;
  }

  .create-job-btn,
  .manage-jobs-btn {
    width: 100%;
  }
}
</style>
