<template>
  <!-- Loading State -->
  <div v-if="isLoading" class="loading-container">
    <div class="loading-spinner"></div>
    <p>Loading profile...</p>
  </div>

  <!-- Error State -->
  <div v-else-if="error" class="error-message">
    <i class="fas fa-exclamation-circle"></i>
    <h3>{{ errorMessage }}</h3>
    <button @click="loadProfile" class="retry-btn">Retry</button>
  </div>

  <div v-else>
    <!-- Profile Header -->
    <section class="profile-header">
      <div class="profile-cover"></div>
      <div class="profile-info">
        <div class="profile-pic">
          <img v-if="profileData.profilePhotoPreview" :src="profileData.profilePhotoPreview" alt="Profile Picture Preview" />
          <img v-else-if="profileData.profile_photo_url && !profilePhotoError" 
               :src="profileData.profile_photo_url" 
               alt="Profile Picture" 
               @load="imageLoaded"
               @error="imageError"
               ref="profileImage" />
          <img v-else-if="profileData.profile_photo && !profilePhotoError" 
               :src="getProfilePhotoUrl(profileData.profile_photo)" 
               alt="Profile Picture" 
               @load="imageLoaded"
               @error="imageError"
               ref="profileImage" />
          <div v-else class="profile-initial">{{ userData.fullname ? userData.fullname.charAt(0).toUpperCase() : '?' }}</div>
          <label for="profile-photo-upload" class="edit-pic"><i class="fas fa-camera"></i></label>
          <input type="file" id="profile-photo-upload" accept="image/*" @change="handleProfilePhotoChange" class="hidden-file-input" />
        </div>
        <div class="profile-details">
          <form class="profile-form">
            <div class="form-group">
              <label for="fullname">Full Name</label>
              <input type="text" id="fullname" name="fullname" v-model="userData.fullname" required />
            </div>
            <div class="form-group">
              <label for="title">Professional Title</label>
              <input type="text" id="title" name="title" v-model="profileData.professional_title" placeholder="e.g. Senior Frontend Developer" />
            </div>
            <div class="form-group">
              <label for="location">Location</label>
              <input type="text" id="location" name="location" v-model="profileData.location" placeholder="e.g. New York, USA" />
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" v-model="userData.email" disabled />
              <small class="field-hint">Email cannot be changed</small>
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="tel" id="phone" name="phone" v-model="profileData.phone" placeholder="e.g. +1 123 456 7890" />
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- Form Actions Fixed at Top -->
    <div class="form-actions-sticky">
      <div class="form-actions-container">
        <button @click="saveProfile" class="save-btn" :disabled="isSaving">
          <i class="fas fa-save"></i> {{ isSaving ? 'Saving...' : 'Save Profile' }}
        </button>
        <button @click="resetForm" class="reset-btn" :disabled="isSaving">
          <i class="fas fa-undo"></i> Reset Changes
        </button>
        <button @click="cancelEdit" class="cancel-btn" :disabled="isSaving">
          <i class="fas fa-times"></i> Cancel
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <main class="profile-content">
      <!-- Left Column -->
      <div class="profile-left">
        <!-- About Section -->
        <section class="profile-section">
          <h2>About</h2>
          <form class="profile-form">
            <div class="form-group">
              <label for="about">About Me</label>
              <textarea 
                id="about" 
                name="about" 
                rows="4" 
                v-model="profileData.about"
                placeholder="Write a short professional bio"
              ></textarea>
            </div>
          </form>
        </section>

        <!-- Skills Section -->
        <section class="profile-section">
          <h2>Skills</h2>
          <form class="profile-form">
            <div class="skills-grid">
              <div class="skill-category">
                <h3>Frontend</h3>
                <div class="skill-tags">
                  <input
                    type="text"
                    class="skill-input"
                    placeholder="e.g. HTML, CSS, JavaScript"
                    v-model="profileData.frontend_skills"
                  />
                  <small class="field-hint">Separate skills with commas</small>
                </div>
              </div>
              <div class="skill-category">
                <h3>Backend</h3>
                <div class="skill-tags">
                  <input
                    type="text"
                    class="skill-input"
                    placeholder="e.g. Node.js, PHP, MongoDB"
                    v-model="profileData.backend_skills"
                  />
                  <small class="field-hint">Separate skills with commas</small>
                </div>
              </div>
              <div class="skill-category">
                <h3>Tools & Others</h3>
                <div class="skill-tags">
                  <input
                    type="text"
                    class="skill-input"
                    placeholder="e.g. Git, Docker, AWS"
                    v-model="profileData.other_skills"
                  />
                  <small class="field-hint">Separate skills with commas</small>
                </div>
              </div>
            </div>
          </form>
        </section>

        <!-- Work Experience Section -->
        <section class="profile-section">
          <h2>Work Experience</h2>
          <form class="profile-form">
            <div class="timeline">
              <div class="timeline-item" v-for="(exp, index) in profileData.work_experience" :key="'exp-'+index">
                <div class="form-group">
                  <label>Duration</label>
                  <input type="text" v-model="exp.duration" placeholder="e.g. 2020 - Present" />
                </div>
                <div class="timeline-content">
                  <div class="form-group">
                    <label>Position</label>
                    <input type="text" v-model="exp.position" placeholder="e.g. Senior Developer" />
                  </div>
                  <div class="form-group">
                    <label>Company</label>
                    <input type="text" v-model="exp.company" placeholder="e.g. Tech Solutions Inc." />
                  </div>
                  <div class="form-group">
                    <label>Responsibilities</label>
                    <textarea rows="3" v-model="exp.responsibilities" placeholder="• Key responsibility 1&#10;• Key responsibility 2"></textarea>
                    <small class="field-hint">Use bullet points (•) for better formatting</small>
                  </div>
                  <button type="button" class="remove-item-btn" @click="removeWorkExperience(index)">
                    <i class="fas fa-trash"></i> Remove
                  </button>
                </div>
              </div>
              <button type="button" class="add-item-btn" @click="addWorkExperience">
                <i class="fas fa-plus"></i> Add Experience
              </button>
            </div>
          </form>
        </section>

        <!-- Education Section -->
        <section class="profile-section">
          <h2>Education</h2>
          <form class="profile-form">
            <div class="timeline">
              <div class="timeline-item" v-for="(edu, index) in profileData.education" :key="'edu-'+index">
                <div class="form-group">
                  <label>Duration</label>
                  <input type="text" v-model="edu.duration" placeholder="e.g. 2014 - 2018" />
                </div>
                <div class="timeline-content">
                  <div class="form-group">
                    <label>Degree</label>
                    <input type="text" v-model="edu.degree" placeholder="e.g. Bachelor of Science in Computer Science" />
                  </div>
                  <div class="form-group">
                    <label>Institution</label>
                    <input type="text" v-model="edu.institution" placeholder="e.g. University of Technology" />
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea rows="2" v-model="edu.description" placeholder="e.g. Graduated with honors, specialized in Software Engineering"></textarea>
                  </div>
                  <button type="button" class="remove-item-btn" @click="removeEducation(index)">
                    <i class="fas fa-trash"></i> Remove
                  </button>
                </div>
              </div>
              <button type="button" class="add-item-btn" @click="addEducation">
                <i class="fas fa-plus"></i> Add Education
              </button>
            </div>
          </form>
        </section>
      </div>

      <!-- Right Column -->
      <div class="profile-right">
        <!-- Availability Section -->
        <section class="profile-section">
          <h2>Availability</h2>
          <form class="profile-form">
            <div class="availability-info">
              <div class="form-group">
                <label>Status</label>
                <select v-model="profileData.availability_status">
                  <option value="">Select status</option>
                  <option value="available">Available for Work</option>
                  <option value="not-available">Not Available</option>
                  <option value="open-to-offers">Open to Offers</option>
                </select>
              </div>
              <div class="form-group">
                <label>Preferred Work</label>
                <input type="text" v-model="profileData.preferred_work_type" placeholder="e.g. Full-time, Remote" />
              </div>
              <div class="form-group">
                <label>Notice Period</label>
                <input type="text" v-model="profileData.notice_period" placeholder="e.g. 2 weeks" />
              </div>
              <div class="form-group">
                <label>Expected Salary</label>
                <input type="text" v-model="profileData.expected_salary" placeholder="e.g. $80,000 - $100,000" />
              </div>
            </div>
          </form>
        </section>

        <!-- Languages Section -->
        <section class="profile-section">
          <h2>Languages</h2>
          <form class="profile-form">
            <div class="languages-list">
              <div class="language-item" v-for="(lang, index) in profileData.languages" :key="'lang-'+index">
                <div class="form-group">
                  <label>Language</label>
                  <input type="text" v-model="lang.language" placeholder="e.g. English" />
                </div>
                <div class="form-group">
                  <label>Proficiency</label>
                  <select v-model="lang.proficiency">
                    <option value="5">Native/Fluent</option>
                    <option value="4">Advanced</option>
                    <option value="3">Intermediate</option>
                    <option value="2">Basic</option>
                    <option value="1">Beginner</option>
                  </select>
                </div>
                <button type="button" class="remove-item-btn" @click="removeLanguage(index)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
              <button type="button" class="add-item-btn" @click="addLanguage">
                <i class="fas fa-plus"></i> Add Language
              </button>
            </div>
          </form>
        </section>

        <!-- Certifications Section -->
        <section class="profile-section">
          <h2>Certifications</h2>
          <form class="profile-form">
            <div class="certifications-list">
              <div class="certification-item" v-for="(cert, index) in profileData.certifications" :key="'cert-'+index">
                <div class="form-group">
                  <label>Certification Name</label>
                  <input type="text" v-model="cert.name" placeholder="e.g. AWS Certified Developer" />
                </div>
                <div class="form-group">
                  <label>Issuing Organization</label>
                  <input type="text" v-model="cert.organization" placeholder="e.g. Amazon Web Services" />
                </div>
                <div class="form-group">
                  <label>Year</label>
                  <input type="text" v-model="cert.year" placeholder="e.g. 2022" />
                </div>
                <button type="button" class="remove-item-btn" @click="removeCertification(index)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
              <button type="button" class="add-item-btn" @click="addCertification">
                <i class="fas fa-plus"></i> Add Certification
              </button>
            </div>
          </form>
        </section>

        <!-- Social Links Section -->
        <section class="profile-section">
          <h2>Social Links</h2>
          <form class="profile-form">
            <div class="social-links-form">
              <div class="form-group">
                <label><i class="fab fa-github"></i> GitHub</label>
                <input type="url" v-model="profileData.github_url" placeholder="https://github.com/username" />
              </div>
              <div class="form-group">
                <label><i class="fab fa-linkedin"></i> LinkedIn</label>
                <input type="url" v-model="profileData.linkedin_url" placeholder="https://linkedin.com/in/username" />
              </div>
              <div class="form-group">
                <label><i class="fab fa-twitter"></i> Twitter</label>
                <input type="url" v-model="profileData.twitter_url" placeholder="https://twitter.com/username" />
              </div>
              <div class="form-group">
                <label><i class="fas fa-globe"></i> Portfolio</label>
                <input type="url" v-model="profileData.portfolio_url" placeholder="https://yourportfolio.com" />
              </div>
            </div>
          </form>
        </section>
      </div>
    </main>

    <!-- Form Actions at Bottom -->
    <div class="form-actions">
      <button @click="saveProfile" class="save-btn" :disabled="isSaving">
        <i class="fas fa-save"></i> {{ isSaving ? 'Saving...' : 'Save Profile' }}
      </button>
      <button @click="resetForm" class="reset-btn" :disabled="isSaving">
        <i class="fas fa-undo"></i> Reset Changes
      </button>
      <button @click="cancelEdit" class="cancel-btn" :disabled="isSaving">
        <i class="fas fa-times"></i> Cancel
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { GET, POST } from '@/scripts/Fetch';
import { useToast } from '@/scripts/toast';
import Config from '@/scripts/Config';
import Cookie from '@/scripts/Cookie';
import { useJobStore } from '@/stores/job';

const router = useRouter();
const toast = useToast();
const jobStore = useJobStore();

const isLoading = ref(true);
const isSaving = ref(false);
const error = ref(false);
const errorMessage = ref('');
const profilePhotoError = ref(false);

// User and profile data
const userData = reactive({
  id: null,
  fullname: '',
  email: ''
});

const profileData = reactive({
  professional_title: '',
  location: '',
  phone: '',
  about: '',
  profile_photo: null,
  frontend_skills: '',
  backend_skills: '',
  other_skills: '',
  work_experience: [],
  education: [],
  availability_status: '',
  preferred_work_type: '',
  notice_period: '',
  expected_salary: '',
  languages: [],
  certifications: [],
  github_url: '',
  linkedin_url: '',
  twitter_url: '',
  portfolio_url: ''
});

// Initial backup data for reset functionality
const initialData = reactive({});

// Load user profile data
const loadProfile = async () => {
  isLoading.value = true;
  error.value = false;
  
  try {
    // Get current user profile
    const result = await GET('my-profile');
    
    if (!result.error && result.response) {
      // Update user data
      Object.assign(userData, result.response.user);
      
      // Update profile data
      const profile = result.response.profile;
      if (profile) {
        // Parse JSON fields if they're stored as strings
        if (profile.work_experience && typeof profile.work_experience === 'string') {
          profile.work_experience = JSON.parse(profile.work_experience);
        } else if (!profile.work_experience) {
          profile.work_experience = [];
        }
        
        if (profile.education && typeof profile.education === 'string') {
          profile.education = JSON.parse(profile.education);
        } else if (!profile.education) {
          profile.education = [];
        }
        
        if (profile.languages && typeof profile.languages === 'string') {
          profile.languages = JSON.parse(profile.languages);
        } else if (!profile.languages) {
          profile.languages = [];
        }
        
        if (profile.certifications && typeof profile.certifications === 'string') {
          profile.certifications = JSON.parse(profile.certifications);
        } else if (!profile.certifications) {
          profile.certifications = [];
        }
        
        // Update profile data
        Object.assign(profileData, profile);
        
        // Make a deep copy for reset functionality
        Object.assign(initialData, JSON.parse(JSON.stringify({
          user: { ...userData },
          profile: { ...profileData }
        })));
      }
    } else {
      throw new Error(result.message || 'Failed to load profile');
    }
  } catch (err) {
    console.error('Error loading profile:', err);
    error.value = true;
    errorMessage.value = err.message || 'An error occurred while loading your profile';
    toast.error(errorMessage.value);
  } finally {
    isLoading.value = false;
  }
};

// Handle profile photo changes
const handleProfilePhotoChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    console.log('Selected profile photo:', file.name, file.type, file.size);
    
    // Check file size (2MB limit)
    if (file.size > 2 * 1024 * 1024) {
      toast.error('Profile photo must be less than 2MB in size.');
      event.target.value = '';
      return;
    }
    
    // Store the file
    profileData.profile_photo = file;
    
    // Create a preview URL
    profileData.profilePhotoPreview = URL.createObjectURL(file);
    console.log('Created preview URL:', profileData.profilePhotoPreview);
    
    // Reset error state as we're using a new image
    profilePhotoError.value = false;
    
    // No longer update the Pinia store here - we'll wait for server response
  }
};

// Save profile changes
const saveProfile = async () => {
  isSaving.value = true;
  
  try {
    // Create a fresh FormData instance
    const formData = new FormData();
    
    // Log what we're about to submit
    console.log('Preparing to save profile with data:', {
      user: { ...userData },
      profile: { ...profileData }
    });
    
    // Add user data
    formData.append('fullname', userData.fullname);
    
    // Handle profile photo specially - only include it if it's a File object
    if (profileData.profile_photo instanceof File) {
      console.log('Appending profile_photo as File:', profileData.profile_photo.name);
      formData.append('profile_photo', profileData.profile_photo);
    }
    
    // Add all other profile data EXCEPT photo-related fields
    const fieldsToSkip = ['profilePhotoPreview', 'profile_photo', 'profile_photo_url'];
    
    Object.keys(profileData).forEach(key => {
      if (fieldsToSkip.includes(key)) {
        // Skip these fields
        return;
      }
      
      if (key === 'work_experience' || key === 'education' || 
          key === 'languages' || key === 'certifications') {
        // Convert arrays to JSON strings
        if (profileData[key]) {
          formData.append(key, JSON.stringify(profileData[key]));
        }
      } else if (profileData[key] !== null && profileData[key] !== undefined) {
        // For other fields, add as is if they exist
        formData.append(key, profileData[key]);
      }
    });
    
    // Log all form data keys for debugging
    console.log('FormData keys:', [...formData.keys()].join(', '));
    
    // Send update request with multipart/form-data
    const result = await POST('my-profile', formData, true);
    
    if (!result.error) {
      toast.success('Profile updated successfully');
      
      // Update the profile data with the response data
      if (result.response && result.response.profile) {
        // Update the local component data
        Object.assign(profileData, result.response.profile);
        
        // Use the renamed store action to update the Pinia store
        jobStore.updateUserProfile(result.response.profile);
      }
      
      // Update the initialData backup
      Object.assign(initialData, JSON.parse(JSON.stringify({
        user: { ...userData },
        profile: { ...profileData }
      })));
      
      // Redirect to profile view
      router.push('/profile');
    } else {
      throw new Error(result.reason || result.message || 'Failed to update profile');
    }
  } catch (err) {
    console.error('Error saving profile:', err);
    toast.error(err.message || 'An error occurred while saving your profile');
  } finally {
    isSaving.value = false;
  }
};

// Cancel edits and go back to profile
const cancelEdit = () => {
  router.push('/profile');
};

// Reset form to initial values
const resetForm = () => {
  if (initialData.user) {
    Object.assign(userData, JSON.parse(JSON.stringify(initialData.user)));
  }
  
  if (initialData.profile) {
    Object.assign(profileData, JSON.parse(JSON.stringify(initialData.profile)));
  }
};

// Helper Functions
const getBaseUrl = () => {
  // Get base URL without /api
  return Config.baseURL.replace('/api/', '/').replace('/api', '/');
};

const getProfilePhotoUrl = (photo) => {
  console.log('Original photo path:', photo);
  if (!photo) return null;
  
  // If photo is already a full URL, return it
  if (photo.startsWith('http://') || photo.startsWith('https://')) {
    console.log('Using full URL for photo:', photo);
    return photo;
  }
  
  // If it's a temp path, we can't use it - force error to show initials
  if (photo.includes('tmp') || photo.includes('php') || photo.includes('C:')) {
    console.error('Temporary path detected:', photo);
    // Return a non-existent URL to trigger error handler
    return 'error://invalid-temp-path';
  }
  
  // Get just the filename if full path is given
  let filename = photo;
  if (photo.includes('/')) {
    filename = photo.split('/').pop();
  }
  
  // Use the Laravel API endpoint for accessing images as a workaround
  // This is more reliable than direct storage URL on Windows systems
  const url = `${Config.baseURL.replace('/api', '')}/storage/profile-photos/${filename}`;
  console.log('Final URL:', url);
  return url;
};

// Add/remove functions for arrays
const addWorkExperience = () => {
  profileData.work_experience.push({
    position: '',
    company: '',
    duration: '',
    responsibilities: ''
  });
};

const removeWorkExperience = (index) => {
  profileData.work_experience.splice(index, 1);
};

const addEducation = () => {
  profileData.education.push({
    degree: '',
    institution: '',
    duration: '',
    description: ''
  });
};

const removeEducation = (index) => {
  profileData.education.splice(index, 1);
};

const addLanguage = () => {
  profileData.languages.push({
    language: '',
    proficiency: 3 // Default to intermediate
  });
};

const removeLanguage = (index) => {
  profileData.languages.splice(index, 1);
};

const addCertification = () => {
  profileData.certifications.push({
    name: '',
    organization: '',
    year: ''
  });
};

const removeCertification = (index) => {
  profileData.certifications.splice(index, 1);
};

// Load profile when component mounts
onMounted(() => {
  loadProfile();
});

// Image loading and error handling
const imageLoaded = () => {
  profilePhotoError.value = false;
  console.log('Profile image loaded successfully');
};

const imageError = (event) => {
  console.error('Profile image failed to load - showing initials instead');
  console.error('Image source that failed:', event.target.src);
  
  // If it's a temp path URL, just show initials
  if (event.target.src.includes('tmp') || event.target.src.includes('php') || 
      event.target.src.includes('error://')) {
    profilePhotoError.value = true;
    return;
  }
  
  // Get just the filename from the failed URL
  const urlParts = event.target.src.split('/');
  const filename = urlParts[urlParts.length - 1];
  
  // Try alternative URLs
  const fallbackUrls = [
    // Try direct URL with base domain only
    `http://127.0.0.1:8000/storage/profile-photos/${filename}`,
    // Try URL with baseUrl
    `${getBaseUrl()}storage/profile-photos/${filename}`
  ];
  
  console.log('Trying fallback URLs:', fallbackUrls);
  
  // Try each fallback URL
  let fallbackAttempt = 0;
  const tryFallback = () => {
    if (fallbackAttempt >= fallbackUrls.length) {
      console.error('All fallbacks failed, showing initial');
      profilePhotoError.value = true;
      return;
    }
    
    const testImage = new Image();
    testImage.onload = () => {
      console.log('Fallback URL works:', fallbackUrls[fallbackAttempt]);
      if (event.target) {
        event.target.src = fallbackUrls[fallbackAttempt];
        profilePhotoError.value = false;
      }
    };
    
    testImage.onerror = () => {
      console.log(`Fallback URL ${fallbackAttempt + 1} failed, trying next`);
      fallbackAttempt++;
      tryFallback();
    };
    
    testImage.src = fallbackUrls[fallbackAttempt];
  };
  
  // Start trying fallbacks
  tryFallback();
};
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
  border-top: 4px solid #636ae8;
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

.retry-btn {
  margin-top: 1rem;
  padding: 0.5rem 1.5rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  background-color: #636ae8;
  color: white;
  border: none;
}

/* Profile header styles */
.profile-header {
  background-color: #fff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
}

.profile-cover {
  height: 200px;
  background-color: #636ae8;
  background-image: linear-gradient(135deg, #636ae8 0%, #7e84f0 100%);
}

.profile-info {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  padding: 0 1rem;
  transform: translateY(-50px);
}

@media (min-width: 768px) {
  .profile-info {
    flex-direction: row;
    align-items: flex-start;
    padding: 0 2rem;
  }
}

@media (max-width: 640px) {
  .profile-info {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
}

.profile-pic {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  border: 5px solid #fff;
  overflow: hidden;
  position: relative;
  background-color: #f3f4f6;
  margin-right: 2rem;
  flex-shrink: 0;
}

@media (max-width: 640px) {
  .profile-pic {
    margin-right: 0;
    margin-bottom: 1rem;
  }
}

.profile-pic img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.profile-initial {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 4rem;
  font-weight: bold;
  color: white;
  background-color: #636ae8;
}

.edit-pic {
  position: absolute;
  bottom: 0;
  right: 0;
  background-color: #636ae8;
  color: #fff;
  border: none;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hidden-file-input {
  display: none;
}

.profile-details {
  flex: 1;
  text-align: left;
}

@media (max-width: 640px) {
  .profile-details {
    text-align: center;
    width: 100%;
  }
}

/* Form styles */
.profile-form {
  width: 100%;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #4b5563;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group input[type="url"],
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 0.625rem;
  border: 1px solid #d1d5db;
  border-radius: 0.25rem;
  font-size: 0.875rem;
  transition: border-color 0.15s ease-in-out;
}

.form-group input:disabled {
  background-color: #f3f4f6;
  cursor: not-allowed;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  outline: none;
  border-color: #636ae8;
  box-shadow: 0 0 0 3px rgba(99, 106, 232, 0.1);
}

.field-hint {
  display: block;
  font-size: 0.75rem;
  color: #6b7280;
  margin-top: 0.25rem;
}

/* Form actions */
.form-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  padding: 2rem 0;
  max-width: 800px;
  margin: 0 auto;
}

.form-actions-sticky {
  position: sticky;
  top: 0;
  background-color: rgba(255, 255, 255, 0.9);
  z-index: 10;
  padding: 1rem 0;
  border-bottom: 1px solid #e5e7eb;
  margin-bottom: 2rem;
  backdrop-filter: blur(5px);
}

.form-actions-container {
  display: flex;
  justify-content: center;
  gap: 1rem;
  max-width: 800px;
  margin: 0 auto;
}

.save-btn, .reset-btn, .cancel-btn {
  padding: 0.625rem 1.25rem;
  border-radius: 0.25rem;
  font-weight: 500;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
}

.save-btn {
  background-color: #636ae8;
  color: white;
  border: none;
}

.save-btn:hover:not(:disabled) {
  background-color: #4b50a5;
}

.reset-btn {
  background-color: #f3f4f6;
  color: #4b5563;
  border: 1px solid #d1d5db;
}

.reset-btn:hover:not(:disabled) {
  background-color: #e5e7eb;
}

.cancel-btn {
  background-color: white;
  color: #ef4444;
  border: 1px solid #ef4444;
}

.cancel-btn:hover:not(:disabled) {
  background-color: #fee2e2;
}

.save-btn:disabled, .reset-btn:disabled, .cancel-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.save-btn i, .reset-btn i, .cancel-btn i {
  margin-right: 0.5rem;
}

/* Layout */
.profile-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  
  @media (min-width: 768px) {
    flex-direction: row;
    align-items: flex-start;
  }
}

.profile-left {
  flex: 2;
}

.profile-right {
  flex: 1;
}

.profile-section {
  background-color: #fff;
  border-radius: 0.5rem;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.profile-section h2 {
  color: #636ae8;
  margin-top: 0;
  margin-bottom: 1.25rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #e5e7eb;
  font-size: 1.25rem;
}

/* Skills */
.skills-grid {
  display: grid;
  gap: 1.5rem;
  
  @media (min-width: 640px) {
    grid-template-columns: repeat(2, 1fr);
  }
  
  @media (min-width: 1024px) {
    grid-template-columns: repeat(3, 1fr);
  }
}

.skill-category h3 {
  color: #4b5563;
  font-size: 1rem;
  margin-top: 0;
  margin-bottom: 0.75rem;
}

.skill-input {
  width: 100%;
  padding: 0.625rem;
  border: 1px solid #d1d5db;
  border-radius: 0.25rem;
}

/* Timeline items (work, education) */
.timeline {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.timeline-item {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  padding: 1.25rem;
  position: relative;
  background-color: #f9fafb;
}

.timeline-content {
  margin-top: 1rem;
}

/* Item manipulation buttons */
.add-item-btn, .remove-item-btn {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  font-size: 0.875rem;
  cursor: pointer;
  font-weight: 500;
}

.add-item-btn {
  background-color: #636ae8;
  color: white;
  border: none;
  margin-top: 0.5rem;
}

.add-item-btn:hover {
  background-color: #4b50a5;
}

.remove-item-btn {
  background-color: #fee2e2;
  color: #ef4444;
  border: none;
  margin-top: 1rem;
}

.remove-item-btn:hover {
  background-color: #fecaca;
}

.add-item-btn i, .remove-item-btn i {
  margin-right: 0.5rem;
}

/* Language items */
.languages-list, .certifications-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.language-item, .certification-item {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  padding: 1.25rem;
  position: relative;
  background-color: #f9fafb;
}

/* Social links */
.social-links-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.social-links-form .form-group {
  margin-bottom: 0.5rem;
}

.social-links-form label i {
  margin-right: 0.5rem;
  color: #636ae8;
}
</style>
