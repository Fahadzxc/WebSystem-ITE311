<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-bell"></i> Notifications</h4>
                    <?php if (!empty($notifications) && array_filter($notifications, function($n) { return $n['is_read'] == 0; })): ?>
                        <button class="btn btn-light btn-sm" onclick="markAllAsRead()">
                            <i class="fas fa-check-double"></i> Mark All as Read
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body bg-white">
                    <?php if (empty($notifications)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications</h5>
                            <p class="text-muted">You don't have any notifications at the moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="notifications-list">
                            <?php foreach ($notifications as $notification): ?>
                                <div class="notification-item <?= $notification['is_read'] == 0 ? 'unread' : 'read' ?> mb-3" data-id="<?= $notification['id'] ?>">
                                    <div class="card <?= $notification['is_read'] == 0 ? 'border-primary' : 'border-light' ?>">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="notification-content flex-grow-1">
                                                    <p class="mb-2"><?= esc($notification['message']) ?></p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i> 
                                                        <?php 
                                                            helper('date');
                                                            $date = new \DateTime($notification['created_at'], new \DateTimeZone('UTC'));
                                                            $date->setTimezone(new \DateTimeZone('Asia/Manila'));
                                                            echo $date->format('M j, Y \a\t g:i A');
                                                        ?>
                                                    </small>
                                                </div>
                                                <div class="notification-actions">
                                                    <?php if ($notification['is_read'] == 0): ?>
                                                        <span class="badge bg-primary">New</span>
                                                        <button class="btn btn-sm btn-outline-primary ms-2" 
                                                                onclick="markAsRead(<?= $notification['id'] ?>)">
                                                            Mark as Read
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Read</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-item.unread {
    background-color: #f8f9ff;
}
.notification-item.read {
    opacity: 0.8;
}
.card-body {
    background-color: white !important;
}
</style>

<?= $this->endSection() ?>