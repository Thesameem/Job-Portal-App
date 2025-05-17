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
    <h2>Job Applications</h2>
    <div class="application-list">
      <div class="application-item">
        <h3>Senior Frontend Developer</h3>
        <p>Tech Solutions Inc.</p>
        <span class="status">Pending</span>
        <RouterLink to="/jobdetails" class="view-details">View Details</RouterLink>
      </div>
      <div class="application-item">
        <h3>Full Stack Developer</h3>
        <p>Digital Innovations</p>
        <span class="status">Approved</span>
        <RouterLink to="/jobdetails" class="view-details">View Details</RouterLink>
      </div>
    </div>
  </section>

  <!-- Received Applications Section -->
  <section class="received-applications">
    <h2>Received Applications</h2>
    <div class="application-list">
      <div class="application-item">
        <h3>Frontend Developer Position</h3>
        <p>Applicant: John Doe</p>
        <p>Experience: 5 years</p>
        <div class="application-actions">
          <span class="status">New</span>
          <RouterLink to="/reviewjob" class="review-btn">Review</RouterLink>
          <button class="message-btn">Message</button>
        </div>
      </div>
      <div class="application-item">
        <h3>Backend Developer Position</h3>
        <p>Applicant: Jane Smith</p>
        <p>Experience: 3 years</p>
        <div class="application-actions">
          <span class="status">Under Review</span>
          <RouterLink to="/reviewjob" class="review-btn">Review</RouterLink>
          <button class="message-btn">Message</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Posted Jobs Section -->
  <section class="posted-jobs">
    <h2>Posted Jobs</h2>
    <div class="job-list">
      <div class="application-item">
        <h3>Senior Frontend Developer</h3>
        <p>Posted: 2 days ago</p>
        <p>Applications: 15</p>
        <div class="application-actions">
          <span class="status">Active</span>
          <RouterLink to="/editjob" class="edit-btn">Edit</RouterLink>
          <RouterLink to="/reviewjob" class="review-btn">View Applicants</RouterLink>
          <button class="close-btn">Close Job</button>
        </div>
      </div>
      <div class="application-item">
        <h3>Full Stack Developer</h3>
        <p>Posted: 1 week ago</p>
        <p>Applications: 25</p>
        <div class="application-actions">
          <span class="status">Active</span>
          <RouterLink to="/editjob" class="edit-btn">Edit</RouterLink>
          <RouterLink to="/reviewjob" class="review-btn">View Applicants</RouterLink>
          <button class="close-btn">Close Job</button>
        </div>
      </div>
      <div class="application-item">
        <h3>Backend Developer</h3>
        <p>Posted: 3 days ago</p>
        <p>Applications: 8</p>
        <div class="application-actions">
          <span class="status">Active</span>
          <RouterLink to="/editjob" class="edit-btn">Edit</RouterLink>
          <RouterLink to="/reviewjob" class="review-btn">View Applicants</RouterLink>
          <button class="close-btn">Close Job</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Applied Applications Section -->
  <section class="applied-applications">
    <h2>Applied Applications</h2>
    <div class="application-list">
      <div class="application-item">
        <h3>Senior Developer</h3>
        <p>Company: Tech Corp</p>
        <p>Applied: 3 days ago</p>
        <div class="application-status">
          <span class="status">Under Review</span>
          <button class="withdraw-btn">Withdraw</button>
        </div>
      </div>
      <div class="application-item">
        <h3>Lead Developer</h3>
        <p>Company: Digital Solutions</p>
        <p>Applied: 1 week ago</p>
        <div class="application-status">
          <span class="status">Interview Scheduled</span>
          <button class="withdraw-btn">Withdraw</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// Add script setup to ensure RouterLink is properly recognized
</script>

<!-- <style scoped>
/* Make RouterLinks look like buttons when needed */
.review-btn, .edit-btn {
  display: inline-block;
  padding: 8px 12px;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
  text-align: center;
  cursor: pointer;
  transition: background-color 0.3s, color 0.3s;
}

.review-btn {
  background-color: #0d6efd;
  color: white;
  border: 1px solid #0d6efd;
}

.review-btn:hover {
  background-color: #0b5ed7;
}

.edit-btn {
  background-color: #6c757d;
  color: white;
  border: 1px solid #6c757d;
}

.edit-btn:hover {
  background-color: #5a6268;
}
</style> -->
