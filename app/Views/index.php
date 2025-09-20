<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="hero">
    <h1>Learning Management System</h1>
    <p>A comprehensive educational platform designed to facilitate effective learning and academic excellence</p>
    
    <div class="mt-3">
        <a href="<?= base_url('about') ?>" class="btn">Learn More</a>
        <a href="<?= base_url('contact') ?>" class="btn btn-outline">Contact Us</a>
    </div>
</div>
<?= $this->endSection() ?>
