<template>
  <!-- Profile Header -->
  <section class="profile-header">
    <div class="profile-cover"></div>
    <div class="profile-info">
      <div class="profile-pic">
        <img src="./../../images/myphoto.JPG" alt="Profile Picture" />
        <button class="edit-pic"><i class="fas fa-camera"></i></button>
      </div>
      <div class="profile-details">
        <form class="profile-form">
          <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="Sameem Miya" required />
          </div>
          <div class="form-group">
            <label for="title">Professional Title</label>
            <input type="text" id="title" name="title" value="Senior Frontend Developer" required />
          </div>
          <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="Bharatpur, Nepal" required />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="iamshamim1999@email.com" required />
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="+977 9800000000" required />
          </div>
        </form>
      </div>
    </div>
  </section>

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
            <textarea id="about" name="about" rows="4" required>
Experienced frontend developer with a passion for creating beautiful and performant user interfaces. Specialized in React, TypeScript, and modern web technologies. Always eager to learn and adapt to new challenges.</textarea
            >
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
                  placeholder="Add skill"
                  value="React, TypeScript, JavaScript, HTML5, CSS3, SASS"
                />
              </div>
            </div>
            <div class="skill-category">
              <h3>Backend</h3>
              <div class="skill-tags">
                <input
                  type="text"
                  class="skill-input"
                  placeholder="Add skill"
                  value="Node.js, Express, MongoDB, REST APIs"
                />
              </div>
            </div>
            <div class="skill-category">
              <h3>Tools & Others</h3>
              <div class="skill-tags">
                <input
                  type="text"
                  class="skill-input"
                  placeholder="Add skill"
                  value="Git, Docker, AWS, Jest, Webpack"
                />
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
            <div class="timeline-item">
              <div class="form-group">
                <label>Duration</label>
                <input type="text" value="2020 - Present" />
              </div>
              <div class="timeline-content">
                <div class="form-group">
                  <label>Position</label>
                  <input type="text" value="Senior Frontend Developer" />
                </div>
                <div class="form-group">
                  <label>Company</label>
                  <input type="text" value="Tech Solutions Inc." />
                </div>
                <div class="form-group">
                  <label>Responsibilities</label>
                  <textarea rows="3">
• Led frontend development for enterprise applications
                                                        • Implemented responsive designs and optimized performance
                                                        • Mentored junior developers and conducted code reviews</textarea
                  >
                </div>
              </div>
            </div>
            <button type="button" class="add-experience-btn">
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
            <div class="timeline-item">
              <div class="form-group">
                <label>Duration</label>
                <input type="text" value="2014 - 2018" />
              </div>
              <div class="timeline-content">
                <div class="form-group">
                  <label>Degree</label>
                  <input type="text" value="Bachelor of Science in Computer Science" />
                </div>
                <div class="form-group">
                  <label>Institution</label>
                  <input type="text" value="University of Technology" />
                </div>
                <div class="form-group">
                  <label>Description</label>
                  <textarea rows="2">
Graduated with honors, specialized in Software Engineering</textarea
                  >
                </div>
              </div>
            </div>
            <button type="button" class="add-education-btn">
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
              <select>
                <option value="available" selected>Available for Work</option>
                <option value="not-available">Not Available</option>
                <option value="open-to-offers">Open to Offers</option>
              </select>
            </div>
            <div class="form-group">
              <label>Preferred Work</label>
              <input type="text" value="Full-time, Remote" />
            </div>
            <div class="form-group">
              <label>Notice Period</label>
              <input type="text" value="2 weeks" />
            </div>
            <div class="form-group">
              <label>Expected Salary</label>
              <input type="text" value="$80,000 - $100,000" />
            </div>
          </div>
        </form>
      </section>

      <!-- Languages Section -->
      <section class="profile-section">
        <h2>Languages</h2>
        <form class="profile-form">
          <div class="languages-list">
            <div class="language-item">
              <div class="form-group">
                <label>Language</label>
                <input type="text" value="English" />
              </div>
              <div class="form-group">
                <label>Proficiency</label>
                <select>
                  <option value="5" selected>Native/Fluent</option>
                  <option value="4">Advanced</option>
                  <option value="3">Intermediate</option>
                  <option value="2">Basic</option>
                  <option value="1">Beginner</option>
                </select>
              </div>
            </div>
            <button type="button" class="add-language-btn">
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
            <div class="certification-item">
              <div class="form-group">
                <label>Certification Name</label>
                <input type="text" value="AWS Certified Developer" />
              </div>
              <div class="form-group">
                <label>Issuing Organization</label>
                <input type="text" value="Amazon Web Services" />
              </div>
              <div class="form-group">
                <label>Year</label>
                <input type="text" value="2022" />
              </div>
            </div>
            <button type="button" class="add-certification-btn">
              <i class="fas fa-plus"></i> Add Certification
            </button>
          </div>
        </form>
      </section>

      <!-- Social Links Section -->
      <section class="profile-section">
        <h2>Social Links</h2>
        <form class="profile-form">
          <div class="social-links">
            <div class="form-group">
              <label><i class="fab fa-github"></i> GitHub</label>
              <input type="url" placeholder="GitHub URL" />
            </div>
            <div class="form-group">
              <label><i class="fab fa-linkedin"></i> LinkedIn</label>
              <input type="url" placeholder="LinkedIn URL" />
            </div>
            <div class="form-group">
              <label><i class="fab fa-twitter"></i> Twitter</label>
              <input type="url" placeholder="Twitter URL" />
            </div>
            <div class="form-group">
              <label><i class="fas fa-globe"></i> Portfolio</label>
              <input type="url" placeholder="Portfolio URL" />
            </div>
          </div>
        </form>
      </section>
    </div>
  </main>

  <!-- Save Changes Button -->
  <div class="save-changes">
    <button type="submit" class="save-btn">Save Changes</button>
    <button type="button" class="cancel-btn">Cancel</button>
  </div>
</template>
