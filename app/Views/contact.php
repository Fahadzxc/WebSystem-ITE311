<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="card grid-2-280">
    <div>
        <h1>Contact Us</h1>
        <form>
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="form-group">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" rows="6" required></textarea>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
    <div>
        <h2>Contact Details</h2>
        <p><strong>Email:</strong> info@lmssystem.com</p>
        <p><strong>Phone:</strong> +63 123 456 7890</p>
        <p><strong>Address:</strong><br>Alabel, Sarangani Province<br>Philippines</p>
        <div class="mt-3">
            <a href="<?= base_url('home') ?>" class="btn btn-outline">Back to Home</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
