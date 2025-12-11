<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold mb-3" style="color: var(--bs-text-dark);">
                <?= esc($title ?? 'Available Courses') ?>
            </h1>
        </div>
    </div>

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form id="searchForm" class="d-flex" action="<?= base_url('courses/search') ?>" method="GET">
                <div class="input-group">
                    <input type="text" 
                           id="searchInput" 
                           class="form-control" 
                           placeholder="Search courses..." 
                           name="search_term"
                           value="<?= esc($searchTerm ?? '') ?>">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses List -->
    <div id="coursesContainer" class="row">
        <?php if (empty($courses)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>No courses found.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card course-card">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($course['title']) ?></h5>
                            <p class="card-text"><?= esc($course['description'] ?? '') ?></p>
                            <a href="<?= base_url('courses/' . $course['id']) ?>" class="btn btn-primary">View Course</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Client-side filtering
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.course-card').each(function() {
            var courseText = $(this).text().toLowerCase();
            $(this).toggle(courseText.indexOf(value) > -1);
        });
    });

    // Server-side search with AJAX
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        var searchTerm = $('#searchInput').val();
        
        $.ajax({
            url: '<?= base_url('courses/search') ?>',
            type: 'GET',
            data: { search_term: searchTerm },
            dataType: 'json',
            success: function(data) {
                $('#coursesContainer').empty();
                
                if (data.length > 0) {
                    $.each(data, function(index, course) {
                        var courseHtml = '<div class="col-md-4 mb-4">' +
                            '<div class="card course-card">' +
                            '<div class="card-body">' +
                            '<h5 class="card-title">' + course.title + '</h5>' +
                            '<p class="card-text">' + (course.description || '') + '</p>' +
                            '<a href="<?= base_url('courses/') ?>' + course.id + '" class="btn btn-primary">View Course</a>' +
                            '</div></div></div>';
                        
                        $('#coursesContainer').append(courseHtml);
                    });
                } else {
                    $('#coursesContainer').html(
                        '<div class="col-12">' +
                        '<div class="alert alert-info text-center">' +
                        '<i class="fas fa-info-circle me-2"></i>No courses found matching your search.' +
                        '</div></div>'
                    );
                }
            },
            error: function() {
                $('#coursesContainer').html(
                    '<div class="col-12">' +
                    '<div class="alert alert-danger text-center">' +
                    '<i class="fas fa-exclamation-triangle me-2"></i>Error loading search results.' +
                    '</div></div>'
                );
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
