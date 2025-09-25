<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="card grid-2-260">
    <div>
        <h1>About Our LMS</h1>
        <p>We build simple, modern tools that help instructors teach effectively and help students learn with focus.</p>
        
        <h2>Our Mission</h2>
        <p>Enable high-quality learning through a clean, accessible platform that reduces friction for learners and educators.</p>
        
        <h2>Our Values</h2>
        <ul>
            <li><strong>Clarity</strong> — prioritize readability and ease of use</li>
            <li><strong>Reliability</strong> — stable performance and data security</li>
            <li><strong>Progress</strong> — continuous improvement with thoughtful features</li>
        </ul>
        
        <div class="mt-3">
            <a href="<?= base_url('contact') ?>" class="btn">Get in Touch</a>
            <a href="<?= base_url('home') ?>" class="btn btn-outline">Back to Home</a>
        </div>
    </div>
    <div>
        <div class="stack-gap">
            <div class="tile">
                <h2>Learning</h2>
                <p>Track progress and manage coursework with ease.</p>
            </div>
            <div class="tile">
                <h2>Teaching</h2>
                <p>Create courses, assignments, and communicate with students.</p>
            </div>
            <div class="tile">
                <h2>Community</h2>
                <p>Bring learners and instructors together around shared goals.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
