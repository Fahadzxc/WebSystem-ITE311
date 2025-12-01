<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                <i class="fas fa-calendar-alt text-primary-custom me-2"></i>Academic Settings
            </h1>
            <p class="text-muted mb-0">Manage academic years, semesters, terms, and year levels</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Apply Academic Year to Existing Data -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-custom text-white rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-cog me-2"></i>Apply Academic Settings to Existing Data
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Apply academic year, semester, and term to all existing courses and enrollments.</p>
                    <form id="applyAcademicForm">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="academic_year_id" class="form-label fw-semibold">Academic Year</label>
                                <select class="form-select form-select-lg" id="academic_year_id" name="academic_year_id" required>
                                    <option value="">Select Academic Year</option>
                                    <?php foreach ($academic_years as $year): ?>
                                        <option value="<?= $year['id'] ?>" <?= isset($active_academic_year) && $active_academic_year['id'] == $year['id'] ? 'selected' : '' ?>>
                                            <?= esc($year['description']) ?> <?= $year['is_active'] ? '(Active)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="semester_id" class="form-label fw-semibold">Semester</label>
                                <select class="form-select form-select-lg" id="semester_id" name="semester_id">
                                    <option value="">Select Semester (Optional)</option>
                                    <?php foreach ($semesters as $semester): ?>
                                        <option value="<?= $semester['id'] ?>"><?= esc($semester['semester_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="term_id" class="form-label fw-semibold">Term</label>
                                <select class="form-select form-select-lg" id="term_id" name="term_id">
                                    <option value="">Select Term (Optional)</option>
                                    <?php foreach ($terms as $term): ?>
                                        <option value="<?= $term['id'] ?>"><?= esc($term['term_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                <i class="fas fa-check me-2"></i>Apply to All Courses & Enrollments
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assign Year Level to Students -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-custom text-white rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-graduate me-2"></i>Assign Year Level to Students
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Assign year level to students. This will apply to all students without a year level assigned.</p>
                    <form id="assignYearLevelForm">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="year_level_id" class="form-label fw-semibold">Year Level</label>
                                <select class="form-select form-select-lg" id="year_level_id" name="year_level_id" required>
                                    <option value="">Select Year Level</option>
                                    <?php foreach ($year_levels as $level): ?>
                                        <option value="<?= $level['id'] ?>"><?= esc($level['level_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                <i class="fas fa-user-check me-2"></i>Assign to All Students Without Year Level
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Academic Years List -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-calendar me-2 text-primary-custom"></i>Academic Years
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($academic_years)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($academic_years as $year): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <div>
                                        <h6 class="mb-1 fw-semibold"><?= esc($year['description']) ?></h6>
                                        <small class="text-muted"><?= $year['year_start'] ?> - <?= $year['year_end'] ?></small>
                                    </div>
                                    <?php if ($year['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No academic years found. Run the seeder first.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Year Levels List -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-layer-group me-2 text-primary-custom"></i>Year Levels
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($year_levels)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($year_levels as $level): ?>
                                <div class="list-group-item border-0 px-0">
                                    <h6 class="mb-1 fw-semibold"><?= esc($level['level_name']) ?></h6>
                                    <?php if (!empty($level['description'])): ?>
                                        <small class="text-muted"><?= esc($level['description']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No year levels found. Run the seeder first.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Semesters List -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-calendar-week me-2 text-primary-custom"></i>Semesters
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($semesters)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($semesters as $semester): ?>
                                <div class="list-group-item border-0 px-0">
                                    <h6 class="mb-1 fw-semibold"><?= esc($semester['semester_name']) ?></h6>
                                    <?php if (!empty($semester['description'])): ?>
                                        <small class="text-muted"><?= esc($semester['description']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No semesters found. Run the seeder first.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Terms List -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-calendar-day me-2 text-primary-custom"></i>Terms
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($terms)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($terms as $term): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <div>
                                        <h6 class="mb-1 fw-semibold"><?= esc($term['term_name']) ?></h6>
                                        <?php if (!empty($term['description'])): ?>
                                            <small class="text-muted"><?= esc($term['description']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($term['is_summer']): ?>
                                        <span class="badge bg-warning text-dark">Summer</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No terms found. Run the seeder first.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- View Enrollments by Academic Period -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-custom text-white rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2"></i>View Enrollments by Academic Period
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Filter and view student enrollments by academic year, semester, and term.</p>
                    <form method="GET" action="<?= base_url('admin/academic-settings') ?>" id="filterEnrollmentsForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="filter_academic_year_id" class="form-label fw-semibold">Academic Year</label>
                                <select class="form-select form-select-lg" id="filter_academic_year_id" name="academic_year_id">
                                    <option value="">All Academic Years</option>
                                    <?php foreach ($academic_years as $year): ?>
                                        <option value="<?= $year['id'] ?>" <?= isset($filter_academic_year_id) && $filter_academic_year_id == $year['id'] ? 'selected' : '' ?>>
                                            <?= esc($year['description']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filter_semester_id" class="form-label fw-semibold">Semester</label>
                                <select class="form-select form-select-lg" id="filter_semester_id" name="semester_id">
                                    <option value="">All Semesters</option>
                                    <?php foreach ($semesters as $semester): ?>
                                        <option value="<?= $semester['id'] ?>" <?= isset($filter_semester_id) && $filter_semester_id == $semester['id'] ? 'selected' : '' ?>>
                                            <?= esc($semester['semester_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filter_term_id" class="form-label fw-semibold">Term</label>
                                <select class="form-select form-select-lg" id="filter_term_id" name="term_id">
                                    <option value="">All Terms</option>
                                    <?php foreach ($terms as $term): ?>
                                        <option value="<?= $term['id'] ?>" <?= isset($filter_term_id) && $filter_term_id == $term['id'] ? 'selected' : '' ?>>
                                            <?= esc($term['term_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary-custom btn-lg me-2">
                                <i class="fas fa-search me-2"></i>Filter Enrollments
                            </button>
                            <a href="<?= base_url('admin/academic-settings') ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Clear Filters
                            </a>
                        </div>
                    </form>

                    <?php if (!empty($enrollmentsByStudent)): ?>
                        <div class="mt-5">
                            <h5 class="fw-bold mb-4" style="color: var(--bs-text-dark);">
                                <i class="fas fa-users me-2"></i>Enrollments Results
                                <span class="badge bg-primary-custom ms-2"><?= count($enrollmentsByStudent) ?> student(s)</span>
                            </h5>
                            
                            <?php foreach ($enrollmentsByStudent as $studentData): ?>
                                <div class="card border mb-3">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-bold" style="color: var(--bs-text-dark);">
                                                    <i class="fas fa-user-graduate me-2 text-primary-custom"></i>
                                                    <?= esc($studentData['student_name']) ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i><?= esc($studentData['student_email']) ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-success fs-6">
                                                <?= $studentData['count'] ?> subject<?= $studentData['count'] > 1 ? 's' : '' ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <?php foreach ($studentData['enrollments'] as $enrollment): ?>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                                        <i class="fas fa-book text-primary-custom me-2"></i>
                                                        <div class="flex-grow-1">
                                                            <strong><?= esc($enrollment['course_title']) ?></strong>
                                                            <?php if (!empty($enrollment['academic_year'])): ?>
                                                                <br><small class="text-muted">
                                                                    <i class="fas fa-calendar me-1"></i><?= esc($enrollment['academic_year']) ?>
                                                                    <?php if (!empty($enrollment['semester_name'])): ?>
                                                                        | <?= esc($enrollment['semester_name']) ?>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($enrollment['term_name'])): ?>
                                                                        | <?= esc($enrollment['term_name']) ?>
                                                                    <?php endif; ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif (!empty($filter_academic_year_id) || !empty($filter_semester_id) || !empty($filter_term_id)): ?>
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>No enrollments found for the selected filters.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary mt-4">
                            <i class="fas fa-info-circle me-2"></i>Select filters above to view enrollments by academic period.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Apply Academic Year Form
    $('#applyAcademicForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            <?= csrf_token() ?>: '<?= csrf_hash() ?>',
            academic_year_id: $('#academic_year_id').val(),
            semester_id: $('#semester_id').val(),
            term_id: $('#term_id').val()
        };

        if (!formData.academic_year_id) {
            alert('Please select an academic year');
            return;
        }

        if (!confirm('This will apply the selected academic settings to ALL existing courses and enrollments. Continue?')) {
            return;
        }

        $.ajax({
            url: '<?= base_url('admin/academic-settings/apply') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Academic settings applied successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON || {};
                alert('Error: ' + (response.message || 'Failed to apply academic settings'));
            }
        });
    });

    // Assign Year Level Form
    $('#assignYearLevelForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            <?= csrf_token() ?>: '<?= csrf_hash() ?>',
            year_level_id: $('#year_level_id').val()
        };

        if (!formData.year_level_id) {
            alert('Please select a year level');
            return;
        }

        if (!confirm('This will assign the selected year level to ALL students without a year level. Continue?')) {
            return;
        }

        $.ajax({
            url: '<?= base_url('admin/academic-settings/assign-year-level') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Year level assigned successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON || {};
                alert('Error: ' + (response.message || 'Failed to assign year level'));
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
