<template>
  <!-- Search Section -->
  <section class="search-section">
    <div class="search-container">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search for jobs, skills, or companies..." />
      </div>
      <button class="search-btn">Search Jobs</button>
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
          <select>
            <option>Most Relevant</option>
            <option>Newest</option>
            <option>Highest Salary</option>
          </select>
        </div>
      </div>

      <div class="job-cards">
        <!-- Job Card 1 -->
        <div class="job-card">
          <div class="job-header">
            <img src="https://via.placeholder.com/50" alt="Company Logo" />
            <div class="job-title">
              <h3><RouterLink to="/jobdetails" class="job-title-link">Senior Frontend Developer</RouterLink></h3>
              <RouterLink to="/companyprofile" class="company-name">TechCorp Inc.</RouterLink>
            </div>
            <button class="save-job"><i class="far fa-bookmark"></i></button>
          </div>
          <div class="job-details">
            <span><i class="fas fa-map-marker-alt"></i> Remote</span>
            <span><i class="fas fa-clock"></i> Full-time</span>
            <span><i class="fas fa-dollar-sign"></i> $80-100k</span>
          </div>
          <p class="job-description">
            Looking for an experienced frontend developer with React expertise to join our growing
            team. You'll be responsible for building and maintaining our web applications.
          </p>
          <div class="job-tags">
            <span>React</span>
            <span>JavaScript</span>
            <span>CSS</span>
            <span>TypeScript</span>
          </div>
          <div class="job-footer">
            <span class="posted-date">Posted 2 days ago</span>
            <RouterLink to="/applyjob" class="apply-btn" onclick="window.location.href='applyjob.html'"
              >Apply Now</RouterLink>
          </div>
        </div>

        <!-- Job Card 2 -->
        <div class="job-card">
          <div class="job-header">
            <img src="https://via.placeholder.com/50" alt="Company Logo" />
            <div class="job-title">
              <h3><a href="/jobdetails" class="job-title-link">UI/UX Designer</a></h3>
              <RouterLink to="/companyprofile" class="company-name">DesignHub</RouterLink>
            </div>
            <button class="save-job"><i class="far fa-bookmark"></i></button>
          </div>
          <div class="job-details">
            <span><i class="fas fa-map-marker-alt"></i> New York</span>
            <span><i class="fas fa-clock"></i> Contract</span>
            <span><i class="fas fa-dollar-sign"></i> $60-80k</span>
          </div>
          <p class="job-description">
            Seeking a creative UI/UX designer to join our growing team. You'll be responsible for
            creating beautiful and intuitive user interfaces for our products.
          </p>
          <div class="job-tags">
            <span>Figma</span>
            <span>UI Design</span>
            <span>UX Research</span>
            <span>Prototyping</span>
          </div>
          <div class="job-footer">
            <span class="posted-date">Posted 1 week ago</span>
            <RouterLink to="/applyjob" class="apply-btn" onclick="window.location.href='applyjob.html'"
              >Apply Now</RouterLink>
          </div>
        </div>

        <!-- Job Card 3 -->
        <div class="job-card">
          <div class="job-header">
            <img src="https://via.placeholder.com/50" alt="Company Logo" />
            <div class="job-title">
              <h3><RouterLink to="/jobdetails" class="job-title-link">Backend Developer</RouterLink></h3>
              <RouterLink to="/companyprofile" class="company-name">DataTech Solutions</RouterLink>
            </div>
            <button class="save-job"><i class="far fa-bookmark"></i></button>
          </div>
          <div class="job-details">
            <span><i class="fas fa-map-marker-alt"></i> San Francisco</span>
            <span><i class="fas fa-clock"></i> Full-time</span>
            <span><i class="fas fa-dollar-sign"></i> $90-120k</span>
          </div>
          <p class="job-description">
            Looking for a skilled backend developer to help build and scale our infrastructure.
            Experience with Node.js and cloud services required.
          </p>
          <div class="job-tags">
            <span>Node.js</span>
            <span>Python</span>
            <span>AWS</span>
            <span>MongoDB</span>
          </div>
          <div class="job-footer">
            <span class="posted-date">Posted 3 days ago</span>
            <RouterLink to="/applyjob" class="apply-btn" onclick="window.location.href='applyjob.html'"
              >Apply Now</RouterLink>
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
