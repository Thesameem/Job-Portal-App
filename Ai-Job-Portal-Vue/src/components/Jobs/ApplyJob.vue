<template>
  <!-- Application Form Section -->
  <section class="application-section">
    <div class="application-container">
      <div class="application-header">
        <h2>Apply for Position</h2>
        <div class="job-preview">
          <img src="https://via.placeholder.com/50" alt="Company Logo" />
          <div class="job-info">
            <h3>Senior Frontend Developer</h3>
            <p>TechCorp Inc.</p>
            <div class="job-meta">
              <span><i class="fas fa-map-marker-alt"></i> Remote</span>
              <span><i class="fas fa-clock"></i> Full-time</span>
              <span><i class="fas fa-dollar-sign"></i> $80-100k</span>
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
              <input type="text" id="fullname" v-model="fullname" required />
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" v-model="email" required />
            </div>
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" v-model="phone" required />
            </div>
            <div class="form-group">
              <label for="location">Location</label>
              <input type="text" id="location" v-model="location" required />
            </div>
          </div>
        </div>

        <!-- Professional Summary -->
        <div class="form-section">
          <h3>Professional Summary</h3>
          <div class="form-group">
            <label for="summary">Tell us about yourself</label>
            <textarea id="summary" rows="4" v-model="summary" required></textarea>
          </div>
        </div>

        <!-- Work Experience 
                <div class="form-section">
                    <h3>Work Experience</h3>
                    <div class="experience-entry">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="company">Company</label>
                                <input type="text" id="company" required>
                            </div>
                            <div class="form-group">
                                <label for="position">Position</label>
                                <input type="text" id="position" required>
                            </div>
                            <div class="form-group">
                                <label for="start-date">Start Date</label>
                                <input type="date" id="start-date" required>
                            </div>
                            <div class="form-group">
                                <label for="end-date">End Date</label>
                                <input type="date" id="end-date">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="experience-description">Description</label>
                            <textarea id="experience-description" rows="3" required></textarea>
                        </div>
                        <button type="button" class="add-experience-btn">
                            <i class="fas fa-plus"></i> Add Another Experience
                        </button>
                    </div>
                </div>
                 -->

        <!-- Education
                <div class="form-section">
                    <h3>Education</h3>
                    <div class="education-entry">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="school">School/University</label>
                                <input type="text" id="school" required>
                            </div>
                            <div class="form-group">
                                <label for="degree">Degree</label>
                                <input type="text" id="degree" required>
                            </div>
                            <div class="form-group">
                                <label for="field">Field of Study</label>
                                <input type="text" id="field" required>
                            </div>
                            <div class="form-group">
                                <label for="graduation">Graduation Year</label>
                                <input type="number" id="graduation" required>
                            </div>
                        </div>
                        <button type="button" class="add-education-btn">
                            <i class="fas fa-plus"></i> Add Another Education
                        </button>
                    </div>
                </div>
                -->

        <!-- Skills -->
        <div class="form-section">
          <h3>Skills</h3>
          <div class="form-group">
            <label for="skills">Add your skills (comma separated)</label>
            <input
              type="text"
              id="skills"
              v-model="skills"
              placeholder="e.g., React, JavaScript, CSS, TypeScript"
              required
            />
          </div>
        </div>

        <!-- Documents -->
        <div class="form-section">
          <h3>Documents</h3>
          <div class="form-group">
            <label for="resume">Resume/CV</label>
            <div class="file-upload">
              <input type="file" id="resume" @change="handleResumeUpload" accept=".pdf,.doc,.docx" required />
              <label for="resume" class="file-upload-label">
                <i class="fas fa-upload"></i>
                <span>Choose File</span>
              </label>
              <span class="file-name">No file chosen</span>
            </div>
          </div>
          <div class="form-group">
            <label for="cover-letter">Cover Letter (Optional)</label>
            <div class="file-upload">
              <input type="file" id="cover-letter" @change="handleCoverLetterUpload" accept=".pdf,.doc,.docx" />
              <label for="cover-letter" class="file-upload-label">
                <i class="fas fa-upload"></i>
                <span>Choose File</span>
              </label>
              <span class="file-name">No file chosen</span>
            </div>
          </div>
        </div>

        <!-- Additional Information -->
        <div class="form-section">
          <h3>Additional Information</h3>
          <div class="form-group">
            <label for="portfolio">Portfolio URL (Optional)</label>
            <input type="url" id="portfolio" v-model="portfolio" placeholder="https://" />
          </div>
          <div class="form-group">
            <label for="linkedin">LinkedIn Profile (Optional)</label>
            <input type="url" id="linkedin" v-model="linkedin" placeholder="https://linkedin.com/in/" />
          </div>
          <div class="form-group">
            <label for="github">GitHub Profile (Optional)</label>
            <input type="url" id="github" v-model="github" placeholder="https://github.com/" />
          </div>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
          <button type="submit" class="submit-btn">Submit Application</button>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { POST } from '@/scripts/Fetch';

// Form data
const fullname = ref('');
const email = ref('');
const phone = ref('');
const location = ref('');
const summary = ref('');
const skills = ref('');
const portfolio = ref('');
const linkedin = ref('');
const github = ref('');
const resume = ref(null);
const coverLetter = ref(null);

const handleResumeUpload = (event) => {
  resume.value = event.target.files[0];
};

const handleCoverLetterUpload = (event) => {
  coverLetter.value = event.target.files[0];
};

const submitApplication = async () => {
  const formData = new FormData();
  
  // Append form data
  formData.append('fullname', fullname.value);
  formData.append('email', email.value);
  formData.append('phone', phone.value);
  formData.append('location', location.value);
  formData.append('summary', summary.value);
  formData.append('skills', skills.value);
  formData.append('portfolio', portfolio.value);
  formData.append('linkedin', linkedin.value);
  formData.append('github', github.value);
  
  if (resume.value) {
    formData.append('resume', resume.value);
  }
  
  if (coverLetter.value) {
    formData.append('cover_letter', coverLetter.value);
  }
  
  try {
    const result = await POST('jobs/apply', formData);
    
    if (!result.error) {
      // Handle success
      console.log('Application submitted successfully');
      // Redirect or show success message
    } else {
      // Handle error
      console.error('Error submitting application:', result.reason);
      // Show error message
    }
  } catch (error) {
    console.error('Error submitting application:', error);
  }
};
</script>
