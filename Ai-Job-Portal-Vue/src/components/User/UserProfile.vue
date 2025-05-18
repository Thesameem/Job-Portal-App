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
                  <!-- Debug info overlay -->
                  <div v-if="profileData.profile_photo" class="debug-info">
                      <a :href="`http://127.0.0.1:8000/view-profile-photo/${profileData.profile_photo}`" 
                         target="_blank" 
                         title="View image directly"
                         class="debug-link">
                          <i class="fas fa-external-link-alt"></i>
                      </a>
                  </div>
                  
                  <!-- Profile image with loading state -->
                  <img v-if="profileData.profile_photo && !profilePhotoError" 
                       :src="getProfilePhotoUrl(profileData.profile_photo)" 
                       alt="Profile Picture"
                       @load="imageLoaded"
                       @error="imageError"
                       ref="profileImage">
                  
                  <!-- Fallback initial when no image or error -->
                  <div v-else class="profile-initial">{{ userInitial }}</div>
            </div>
            <div class="profile-details">
                  <h1>{{ userData.fullname }}</h1>
                  <p class="title">{{ profileData.professional_title || 'No title set' }}</p>
                <div class="profile-meta">
                      <span v-if="profileData.location"><i class="fas fa-map-marker-alt"></i> {{ profileData.location }}</span>
                      <span><i class="fas fa-envelope"></i> {{ userData.email }}</span>
                      <span v-if="profileData.phone"><i class="fas fa-phone"></i> {{ profileData.phone }}</span>
                </div>
                <div class="profile-actions">
                    <RouterLink to="/editprofile" class="edit-profile-btn"><i class="fas fa-edit"></i> Edit Profile</RouterLink>
                    <button class="share-profile-btn"><i class="fas fa-share-alt"></i> Share Profile</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="profile-content">
        <!-- Left Column -->
        <div class="profile-left">
            <!-- About Section -->
              <section class="profile-section" v-if="profileData.about">
                  <h2>About</h2>
                  <p>{{ profileData.about }}</p>
              </section>
              <section class="profile-section empty-section" v-else>
                <h2>About</h2>
                  <p class="empty-message">No information added yet.</p>
            </section>

            <!-- Skills Section -->
              <section class="profile-section" v-if="hasSkills">
                <h2>Skills</h2>
                <div class="skills-grid">
                      <div class="skill-category" v-if="profileData.frontend_skills">
                        <h3>Frontend</h3>
                        <div class="skill-tags">
                              <span v-for="(skill, index) in parseSkills(profileData.frontend_skills)" :key="'fe-'+index">{{ skill }}</span>
                        </div>
                    </div>
                      <div class="skill-category" v-if="profileData.backend_skills">
                        <h3>Backend</h3>
                        <div class="skill-tags">
                              <span v-for="(skill, index) in parseSkills(profileData.backend_skills)" :key="'be-'+index">{{ skill }}</span>
                        </div>
                    </div>
                      <div class="skill-category" v-if="profileData.other_skills">
                        <h3>Tools & Others</h3>
                        <div class="skill-tags">
                              <span v-for="(skill, index) in parseSkills(profileData.other_skills)" :key="'ot-'+index">{{ skill }}</span>
                        </div>
                    </div>
                </div>
            </section>
              <section class="profile-section empty-section" v-else>
                  <h2>Skills</h2>
                  <p class="empty-message">No skills added yet.</p>
            </section>

            <!-- Work Experience Section -->
              <section class="profile-section" v-if="hasWorkExperience">
                <h2>Work Experience</h2>
                <div class="timeline">
                      <div class="timeline-item" v-for="(exp, index) in profileData.work_experience" :key="'exp-'+index">
                          <div class="timeline-date">{{ exp.duration }}</div>
                        <div class="timeline-content">
                              <h3>{{ exp.position }}</h3>
                              <p class="company">{{ exp.company }}</p>
                              <div v-if="exp.responsibilities" v-html="formatBulletPoints(exp.responsibilities)"></div>
                        </div>
                    </div>
                </div>
            </section>
              <section class="profile-section empty-section" v-else>
                  <h2>Work Experience</h2>
                  <p class="empty-message">No work experience added yet.</p>
            </section>

            <!-- Education Section -->
              <section class="profile-section" v-if="hasEducation">
                <h2>Education</h2>
                <div class="timeline">
                      <div class="timeline-item" v-for="(edu, index) in profileData.education" :key="'edu-'+index">
                          <div class="timeline-date">{{ edu.duration }}</div>
                        <div class="timeline-content">
                              <h3>{{ edu.degree }}</h3>
                              <p class="institution">{{ edu.institution }}</p>
                              <p v-if="edu.description">{{ edu.description }}</p>
                        </div>
                    </div>
                </div>
            </section>
              <section class="profile-section empty-section" v-else>
                  <h2>Education</h2>
                  <p class="empty-message">No education details added yet.</p>
            </section>
        </div>

        <!-- Right Column -->
        <div class="profile-right">
            <!-- Availability Section -->
              <section class="profile-section" v-if="hasAvailability">
                <h2>Availability</h2>
                <div class="availability-info">
                      <div class="availability-status" :class="profileData.availability_status || 'not-available'">
                        <i class="fas fa-circle"></i>
                          <span>{{ formatAvailabilityStatus(profileData.availability_status) }}</span>
                    </div>
                      <div class="availability-details" v-if="hasAvailabilityDetails">
                          <p v-if="profileData.preferred_work_type"><strong>Preferred Work:</strong> {{ profileData.preferred_work_type }}</p>
                          <p v-if="profileData.notice_period"><strong>Notice Period:</strong> {{ profileData.notice_period }}</p>
                          <p v-if="profileData.expected_salary"><strong>Expected Salary:</strong> {{ profileData.expected_salary }}</p>
                    </div>
                </div>
            </section>
              <section class="profile-section empty-section" v-else>
                  <h2>Availability</h2>
                  <p class="empty-message">No availability information added yet.</p>
            </section>

            <!-- Languages Section -->
              <section class="profile-section" v-if="hasLanguages">
                <h2>Languages</h2>
                <div class="languages-list">
                      <div class="language-item" v-for="(lang, index) in profileData.languages" :key="'lang-'+index">
                          <span>{{ lang.language }}</span>
                        <div class="proficiency">
                              <span v-for="n in 5" :key="n" class="dot" :class="{ filled: n <= parseInt(lang.proficiency) }"></span>
                        </div>
                    </div>
                </div>
            </section>
              <section class="profile-section empty-section" v-else>
                  <h2>Languages</h2>
                  <p class="empty-message">No languages added yet.</p>
            </section>

            <!-- Certifications Section -->
              <section class="profile-section" v-if="hasCertifications">
                <h2>Certifications</h2>
                <div class="certifications-list">
                      <div class="certification-item" v-for="(cert, index) in profileData.certifications" :key="'cert-'+index">
                        <i class="fas fa-certificate"></i>
                        <div class="certification-info">
                              <h4>{{ cert.name }}</h4>
                              <p>{{ cert.organization }}</p>
                              <span class="cert-date">{{ cert.year }}</span>
                        </div>
                    </div>
                </div>
            </section>
              <section class="profile-section empty-section" v-else>
                  <h2>Certifications</h2>
                  <p class="empty-message">No certifications added yet.</p>
            </section>

            <!-- Social Links Section -->
              <section class="profile-section" v-if="hasSocialLinks">
                <h2>Social Links</h2>
                <div class="social-links">
                      <a v-if="profileData.github_url" :href="profileData.github_url" target="_blank" class="social-link"><i class="fab fa-github"></i> GitHub</a>
                      <a v-if="profileData.linkedin_url" :href="profileData.linkedin_url" target="_blank" class="social-link"><i class="fab fa-linkedin"></i> LinkedIn</a>
                      <a v-if="profileData.twitter_url" :href="profileData.twitter_url" target="_blank" class="social-link"><i class="fab fa-twitter"></i> Twitter</a>
                      <a v-if="profileData.portfolio_url" :href="profileData.portfolio_url" target="_blank" class="social-link"><i class="fas fa-globe"></i> Portfolio</a>
                </div>
            </section>
              <section class="profile-section empty-section" v-else>
                  <h2>Social Links</h2>
                  <p class="empty-message">No social links added yet.</p>
            </section>
        </div>
    </main>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { GET } from '@/scripts/Fetch';
import { useToast } from '@/scripts/toast';
import Config from '@/scripts/Config';
import Cookie from '@/scripts/Cookie';

const router = useRouter();
const toast = useToast();

const isLoading = ref(true);
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
  id: null,
  user_id: null,
  professional_title: null,
  location: null,
  phone: null,
  about: null,
  profile_photo: null,
  frontend_skills: null,
  backend_skills: null,
  other_skills: null,
  work_experience: null,
  education: null,
  availability_status: null,
  preferred_work_type: null,
  notice_period: null,
  expected_salary: null,
  languages: null,
  certifications: null,
  github_url: null,
  linkedin_url: null,
  twitter_url: null,
  portfolio_url: null
});

// Load user profile data
const loadProfile = async () => {
  isLoading.value = true;
  error.value = false;
  profilePhotoError.value = false;
  
  try {
    console.log('Fetching user profile data...');
    // Get current user profile
    const result = await GET('my-profile');
    
    console.log('Profile API response:', result);
    
    if (!result.error && result.response) {
      // Update user data
      Object.assign(userData, result.response.user);
      console.log('User data loaded:', userData);
      
      // Update profile data
      const profile = result.response.profile;
      if (profile) {
        console.log('Raw profile data:', profile);
        
        // Special logging for profile photo to help debug
        if (profile.profile_photo) {
          console.log('Profile photo found:', profile.profile_photo);
          
          // Show direct URL for diagnosis
          const filename = profile.profile_photo;
          const directUrl = `http://127.0.0.1:8000/view-profile-photo/${filename}`;
          console.log('Direct image URL:', directUrl);
          console.log('View this photo at:', `http://127.0.0.1:8000/view-profile-photo/${filename}`);
        } else {
          console.log('No profile photo found in data');
        }
        
        // Parse JSON fields if they're stored as strings
        if (profile.work_experience && typeof profile.work_experience === 'string') {
          profile.work_experience = JSON.parse(profile.work_experience);
        }
        
        if (profile.education && typeof profile.education === 'string') {
          profile.education = JSON.parse(profile.education);
        }
        
        if (profile.languages && typeof profile.languages === 'string') {
          profile.languages = JSON.parse(profile.languages);
        }
        
        if (profile.certifications && typeof profile.certifications === 'string') {
          profile.certifications = JSON.parse(profile.certifications);
        }
        
        // Update profile data
        Object.assign(profileData, profile);
        console.log('Profile data loaded successfully:', profileData);
      }
    } else {
      throw new Error(result.reason || 'Failed to load profile');
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
  if (photo.includes('tmp') || photo.includes('php')) {
    console.error('Temporary path detected:', photo);
    // Return a non-existent URL to trigger error handler
    return 'error://invalid-temp-path';
  }
  
  // Get just the filename if full path is given
  let filename = photo;
  if (photo.includes('/')) {
    filename = photo.split('/').pop();
  }
  
  // Direct approach
  const url = `${getBaseUrl()}storage/profile-photos/${filename}`;
  console.log('Final URL:', url);
  return url;
};

const parseSkills = (skillsString) => {
  if (!skillsString) return [];
  return skillsString.split(',').map(skill => skill.trim()).filter(skill => skill);
};

const formatBulletPoints = (text) => {
  if (!text) return '';
  
  // Parse bullet points if text contains • or *
  if (text.includes('•') || text.includes('*')) {
    return `<ul>${text.split(/[•*]/).filter(item => item.trim()).map(item => `<li>${item.trim()}</li>`).join('')}</ul>`;
  }
  
  return `<p>${text}</p>`;
};

const formatAvailabilityStatus = (status) => {
  if (!status) return 'Not Available';
  
  const statuses = {
    'available': 'Available for Work',
    'not-available': 'Not Available',
    'open-to-offers': 'Open to Offers'
  };
  
  return statuses[status] || status;
};

// Computed properties to check if sections have data
const userInitial = computed(() => {
  return userData.fullname ? userData.fullname.charAt(0).toUpperCase() : '?';
});

const hasSkills = computed(() => {
  return profileData.frontend_skills || profileData.backend_skills || profileData.other_skills;
});

const hasWorkExperience = computed(() => {
  return profileData.work_experience && Array.isArray(profileData.work_experience) && profileData.work_experience.length > 0;
});

const hasEducation = computed(() => {
  return profileData.education && Array.isArray(profileData.education) && profileData.education.length > 0;
});

const hasAvailability = computed(() => {
  return profileData.availability_status;
});

const hasAvailabilityDetails = computed(() => {
  return profileData.preferred_work_type || profileData.notice_period || profileData.expected_salary;
});

const hasLanguages = computed(() => {
  return profileData.languages && Array.isArray(profileData.languages) && profileData.languages.length > 0;
});

const hasCertifications = computed(() => {
  return profileData.certifications && Array.isArray(profileData.certifications) && profileData.certifications.length > 0;
});

const hasSocialLinks = computed(() => {
  return profileData.github_url || profileData.linkedin_url || profileData.twitter_url || profileData.portfolio_url;
});

// Debug image loading
const imageLoaded = () => {
  profilePhotoError.value = false;
};

const imageError = (event) => {
  console.error('Profile image failed to load - showing initials instead');
  console.error('Image source that failed:', event.target.src);
  
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

// Load profile when component mounts
onMounted(() => {
  loadProfile();
});
</script>

<style scoped>
/* Only keep styles for loading and error states */
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

/* Debug overlay styles */
.debug-info {
  position: absolute;
  top: 5px;
  right: 5px;
  z-index: 10;
}

.debug-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border-radius: 50%;
  font-size: 12px;
  text-decoration: none;
}

.debug-link:hover {
  background-color: rgba(0, 0, 0, 0.8);
}
</style>