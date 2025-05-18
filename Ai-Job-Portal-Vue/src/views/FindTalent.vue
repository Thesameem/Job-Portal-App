<template>
  <!-- Search Section -->
  <section class="search-section">
    <div class="search-container">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search for skills, experience, or location..." />
      </div>
      <button class="search-btn">Search Talent</button>
    </div>
  </section>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Mobile Filter Toggle Button (only visible on mobile) -->
    <button class="filter-toggle" @click="toggleFilters" v-if="isMobile">
      <i class="fas" :class="showFilters ? 'fa-times' : 'fa-filter'"></i>
      {{ showFilters ? 'Close Filters' : 'Show Filters' }}
    </button>
    
    <!-- Filters Sidebar - Always visible on desktop, toggle on mobile -->
    <aside class="filters-sidebar" v-show="!isMobile || showFilters">
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
        <h3>Skills</h3>
        <div class="skills-input">
          <input type="text" placeholder="Add skills..." />
          <button class="add-skill-btn"><i class="fas fa-plus"></i></button>
        </div>
        <div class="selected-skills">
          <span class="skill-tag">React <i class="fas fa-times"></i></span>
          <span class="skill-tag">JavaScript <i class="fas fa-times"></i></span>
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
          <span>Remote Only</span>
        </label>
      </div>

      <div class="filter-group">
        <h3>Availability</h3>
        <label class="filter-option">
          <input type="checkbox" />
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
      </div>

      <button class="apply-filters-btn">Apply Filters</button>
    </aside>

    <!-- Talent List -->
    <section class="talent-list">
      <div class="talent-header">
        <h2>Available Talent</h2>
        <div class="sort-options">
          <select>
            <option>Most Relevant</option>
            <option>Most Recent</option>
            <option>Highest Rated</option>
          </select>
        </div>
      </div>

      <div class="talent-cards">
        <!-- Talent Card 1 -->
        <div class="talent-card">
          <div class="talent-header">
            <img src="" alt="Profile Picture" class="profile-pic" />
            <div class="talent-info">
              <h3>John Doe</h3>
              <p>Senior Frontend Developer</p>
              <div class="talent-meta">
                <span><i class="fas fa-map-marker-alt"></i> New York</span>
                <span><i class="fas fa-star"></i> 4.8</span>
                <span><i class="fas fa-briefcase"></i> 5 years</span>
              </div>
            </div>
            <button class="save-talent"><i class="far fa-bookmark"></i></button>
          </div>
          <p class="talent-description">
            Experienced frontend developer with expertise in React, TypeScript, and modern web
            technologies. Passionate about creating beautiful and performant user interfaces.
          </p>
          <div class="talent-skills">
            <span>React</span>
            <span>TypeScript</span>
            <span>JavaScript</span>
            <span>CSS</span>
          </div>
          <div class="talent-footer">
            <span class="availability">Available for Full-time</span>
            <button class="contact-btn" onclick="window.location.href='contactTalent.html'">
              Contact
            </button>
          </div>
        </div>

        <!-- Talent Card 2 -->
        <div class="talent-card">
          <div class="talent-header">
            <img src="" alt="Profile Picture" class="profile-pic" />
            <div class="talent-info">
              <h3>Jane Smith</h3>
              <p>UI/UX Designer</p>
              <div class="talent-meta">
                <span><i class="fas fa-map-marker-alt"></i> Remote</span>
                <span><i class="fas fa-star"></i> 4.9</span>
                <span><i class="fas fa-briefcase"></i> 3 years</span>
              </div>
            </div>
            <button class="save-talent"><i class="far fa-bookmark"></i></button>
          </div>
          <p class="talent-description">
            Creative UI/UX designer with a strong portfolio of web and mobile applications. Skilled
            in user research, wireframing, and creating intuitive user experiences.
          </p>
          <div class="talent-skills">
            <span>Figma</span>
            <span>UI Design</span>
            <span>UX Research</span>
            <span>Prototyping</span>
          </div>
          <div class="talent-footer">
            <span class="availability">Available for Contract</span>
            <button class="contact-btn" onclick="window.location.href='contactTalent.html'">
              Contact
            </button>
          </div>
        </div>

        <!-- Talent Card 3 -->
        <div class="talent-card">
          <div class="talent-header">
            <img src="" alt="Profile Picture" class="profile-pic" />
            <div class="talent-info">
              <h3>Mike Johnson</h3>
              <p>Backend Developer</p>
              <div class="talent-meta">
                <span><i class="fas fa-map-marker-alt"></i> San Francisco</span>
                <span><i class="fas fa-star"></i> 4.7</span>
                <span><i class="fas fa-briefcase"></i> 6 years</span>
              </div>
            </div>
            <button class="save-talent"><i class="far fa-bookmark"></i></button>
          </div>
          <p class="talent-description">
            Backend developer specializing in Node.js and cloud services. Experienced in building
            scalable applications and microservices architecture.
          </p>
          <div class="talent-skills">
            <span>Node.js</span>
            <span>Python</span>
            <span>AWS</span>
            <span>MongoDB</span>
          </div>
          <div class="talent-footer">
            <span class="availability">Available for Part-time</span>
            <button class="contact-btn" onclick="window.location.href='contactTalent.html'">
              Contact
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="pagination">
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
import { ref, onMounted, onUnmounted } from 'vue'

// State variables for mobile filter handling
const showFilters = ref(false)
const isMobile = ref(false)

// Toggle filters visibility
const toggleFilters = () => {
  showFilters.value = !showFilters.value
}

// Check if the device is mobile
const checkMobileView = () => {
  isMobile.value = window.innerWidth <= 768
  if (!isMobile.value) {
    // If switching to desktop, always show filters
    showFilters.value = true
  }
}

// Initialize event listeners
onMounted(() => {
  checkMobileView()
  window.addEventListener('resize', checkMobileView)
})

// Clean up event listeners
onUnmounted(() => {
  window.removeEventListener('resize', checkMobileView)
})
</script>
