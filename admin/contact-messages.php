<?php
$adminTitle = 'Customer Inquiries';
$activeMenu = 'messages';

require_once 'header.php';

// Handle message status updates
$msgNotice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $messageId = (int)$_POST['message_id'];
    $newStatus = sanitize($_POST['status']);
    if (in_array($newStatus, ['new', 'read', 'replied'])) {
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $messageId]);
        $msgNotice = 'Message #' . $messageId . ' status updated to ' . strtoupper($newStatus);
    }
}

// Handle message deletion
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $delStmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    $delStmt->execute([$delId]);
    $msgNotice = 'Message #' . $delId . ' deleted successfully.';
}

// Get contact messages
$status = isset($_GET['status']) ? sanitize($_GET['status']) : 'all';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = '';
$params = [];
if ($status !== 'all' && in_array($status, ['new', 'read', 'replied'])) {
    $whereClause = "WHERE status = ?";
    $params[] = $status;
}

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM contact_messages $whereClause");
$countStmt->execute($params);
$totalMessages = (int)$countStmt->fetchColumn();
$totalPages = ceil($totalMessages / $limit);

$stmt = $pdo->prepare("SELECT * FROM contact_messages $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">Customer Inquiries & Messages</h1>
        <p style="font-size: 13px; color: #64748b;">Read and respond to questions submitted by customers via Contact Support</p>
    </div>
</div>

<?php if ($msgNotice): ?>
    <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($msgNotice); ?>
    </div>
<?php endif; ?>

<!-- Status Filter Toolbar -->
<div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 14px 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; gap: 8px;">
        <a href="contact-messages.php?status=all" class="btn btn-sm <?php echo $status === 'all' ? 'btn-primary' : 'btn-outline'; ?>">
            All Messages (<?php echo $totalMessages; ?>)
        </a>
        <a href="contact-messages.php?status=new" class="btn btn-sm <?php echo $status === 'new' ? 'btn-primary' : 'btn-outline'; ?>">
            <i class="fas fa-envelope"></i> New
        </a>
        <a href="contact-messages.php?status=read" class="btn btn-sm <?php echo $status === 'read' ? 'btn-primary' : 'btn-outline'; ?>">
            <i class="fas fa-envelope-open"></i> Read
        </a>
        <a href="contact-messages.php?status=replied" class="btn btn-sm <?php echo $status === 'replied' ? 'btn-primary' : 'btn-outline'; ?>">
            <i class="fas fa-reply"></i> Replied
        </a>
    </div>
</div>

<!-- Messages Listing -->
<?php if (empty($messages)): ?>
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 60px 20px; text-align: center;">
        <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e1; margin-bottom: 14px;"></i>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">No messages found</h3>
        <p style="font-size: 13px; color: #64748b;">There are no contact messages matching your current filter.</p>
    </div>
<?php else: ?>
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <?php foreach ($messages as $msg): ?>
        <?php 
            $statusBadge = 'background: #fef3c7; color: #92400e;';
            if ($msg['status'] === 'read') $statusBadge = 'background: #eff6ff; color: #1e40af;';
            if ($msg['status'] === 'replied') $statusBadge = 'background: #ecfdf5; color: #065f46;';
        ?>
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        <?php echo htmlspecialchars($msg['name']); ?>
                    </h3>
                    <div style="font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 12px;">
                        <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($msg['email']); ?></span>
                        <span>&bull;</span>
                        <span><i class="far fa-clock"></i> <?php echo date('M j, Y \a\t g:i A', strtotime($msg['created_at'])); ?></span>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 3px 10px; border-radius: 99px; <?php echo $statusBadge; ?>">
                        <?php echo ucfirst($msg['status']); ?>
                    </span>
                    <form method="POST" style="margin: 0;">
                        <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                        <input type="hidden" name="update_status" value="1">
                        <select name="status" onchange="this.form.submit()" style="padding: 4px 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; background: #fff;">
                            <option value="new" <?php echo $msg['status'] === 'new' ? 'selected' : ''; ?>>Mark New</option>
                            <option value="read" <?php echo $msg['status'] === 'read' ? 'selected' : ''; ?>>Mark Read</option>
                            <option value="replied" <?php echo $msg['status'] === 'replied' ? 'selected' : ''; ?>>Mark Replied</option>
                        </select>
                    </form>
                    <a href="contact-messages.php?delete=<?php echo $msg['id']; ?>" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: #fca5a5; padding: 4px 8px;" onclick="return confirm('Delete this message?');">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <div style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                    <i class="fas fa-comment-alt" style="color: #2563eb; margin-right: 6px;"></i> Subject: <?php echo htmlspecialchars($msg['subject']); ?>
                </div>
                <div style="font-size: 13px; color: #475569; line-height: 1.6; background: #f8fafc; padding: 12px 16px; border-radius: 8px;">
                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                </div>
            </div>

            <div style="display: flex; gap: 8px;">
                <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>?subject=Re: <?php echo urlencode($msg['subject']); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 8px; margin-top: 24px;">
            <?php if ($page > 1): ?>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            <?php endif; ?>
            <span style="font-size: 13px; color: #64748b; line-height: 32px;">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
            <?php if ($page < $totalPages): ?>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="btn btn-outline btn-sm">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php require_once 'footer.php'; ?>
