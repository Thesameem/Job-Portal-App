<template>
  <!-- Edit Job Form -->
  <div class="edit-job-container">
    <h1>Edit Job Details</h1>
    <form id="editJobForm">
      <div class="form-group">
        <label for="jobTitle">Job Title</label>
        <input
          type="text"
          id="jobTitle"
          name="jobTitle"
          value="Senior Frontend Developer"
          required
        />
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="jobType">Job Type</label>
          <select id="jobType" name="jobType" required>
            <option value="full-time">Full Time</option>
            <option value="part-time">Part Time</option>
            <option value="contract">Contract</option>
            <option value="internship">Internship</option>
          </select>
        </div>

        <div class="form-group">
          <label for="experience">Experience Level</label>
          <select id="experience" name="experience" required>
            <option value="entry">Entry Level</option>
            <option value="mid">Mid Level</option>
            <option value="senior">Senior Level</option>
            <option value="lead">Lead Level</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="salary">Salary Range</label>
          <input type="text" id="salary" name="salary" value="$80,000 - $120,000" required />
        </div>

        <div class="form-group">
          <label for="location">Location</label>
          <input type="text" id="location" name="location" value="New York, NY" required />
        </div>
      </div>

      <div class="form-group">
        <label for="jobDescription">Job Description</label>
        <textarea id="jobDescription" name="jobDescription" required>
        We are looking for a Senior Frontend Developer to join our team...</textarea
        >
      </div>

      <div class="form-group">
        <label>Requirements</label>
        <div class="requirements-list">
          <div class="requirement-item">
            <input type="text" value="5+ years of experience with React" required />
            <button type="button" class="remove-requirement">×</button>
          </div>
          <div class="requirement-item">
            <input type="text" value="Strong knowledge of JavaScript and TypeScript" required />
            <button type="button" class="remove-requirement">×</button>
          </div>
          <div class="requirement-item">
            <input type="text" value="Experience with modern frontend build tools" required />
            <button type="button" class="remove-requirement">×</button>
          </div>
        </div>
        <button type="button" class="add-requirement">Add Requirement</button>
      </div>

      <div class="form-group">
        <label for="benefits">Benefits</label>
        <textarea id="benefits" name="benefits">
            - Health insurance
            - 401(k) matching
            - Flexible work hours
            - Remote work options
        </textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="save-btn">Save Changes</button>
        <button type="button" class="cancel-btn" onclick="window.location.href='dashboard.html'">
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>
