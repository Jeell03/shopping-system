<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

// Check admin login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

// Handle message status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $messageId = (int)$_POST['message_id'];
    $status = sanitize($_POST['status']);
    
    $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
    $stmt->execute([$status, $messageId]);
    
    header('Location: contact-messages.php');
    exit();
}

// Get contact messages
$status = isset($_GET['status']) ? sanitize($_GET['status']) : 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = $status !== 'all' ? "WHERE status = '$status'" : '';
$stmt = $pdo->prepare("SELECT * FROM contact_messages $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM contact_messages $whereClause");
$countStmt->execute();
$totalMessages = $countStmt->fetchColumn();
$totalPages = ceil($totalMessages / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-container {
            padding: 2rem;
            background: #f8f9fa;
            min-height: 100vh;
        }
        .admin-header {
            background: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .messages-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .messages-header {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e1e8ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status-filter {
            display: flex;
            gap: 0.5rem;
        }
        .status-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e1e8ed;
            background: white;
            color: #666;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .status-btn.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }
        .message-item {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e1e8ed;
            transition: background 0.3s;
        }
        .message-item:hover {
            background: #f8f9fa;
        }
        .message-item:last-child {
            border-bottom: none;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        .message-info h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .message-meta {
            color: #666;
            font-size: 0.9rem;
        }
        .message-status {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-new {
            background: #fff3cd;
            color: #856404;
        }
        .status-read {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-replied {
            background: #d4edda;
            color: #155724;
        }
        .message-content {
            margin-bottom: 1rem;
        }
        .message-subject {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .message-text {
            color: #666;
            line-height: 1.6;
        }
        .message-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .status-form {
            display: inline;
        }
        .status-select {
            padding: 0.25rem 0.5rem;
            border: 1px solid #e1e8ed;
            border-radius: 3px;
            font-size: 0.8rem;
        }
        .pagination {
            padding: 1.5rem 2rem;
            text-align: center;
            background: #f8f9fa;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-envelope"></i> Contact Messages</h1>
            <div>
                <a href="index.php" class="btn btn-outline">← Back to Dashboard</a>
            </div>
        </div>
        
        <div class="messages-container">
            <div class="messages-header">
                <h2>Customer Messages (<?php echo $totalMessages; ?>)</h2>
                <div class="status-filter">
                    <a href="?status=all" class="status-btn <?php echo $status === 'all' ? 'active' : ''; ?>">All</a>
                    <a href="?status=new" class="status-btn <?php echo $status === 'new' ? 'active' : ''; ?>">New</a>
                    <a href="?status=read" class="status-btn <?php echo $status === 'read' ? 'active' : ''; ?>">Read</a>
                    <a href="?status=replied" class="status-btn <?php echo $status === 'replied' ? 'active' : ''; ?>">Replied</a>
                </div>
            </div>
            
            <?php if (empty($messages)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No messages found</h3>
                    <p>No contact messages match your current filter.</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                <div class="message-item">
                    <div class="message-header">
                        <div class="message-info">
                            <h3><?php echo htmlspecialchars($message['name']); ?></h3>
                            <div class="message-meta">
                                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($message['email']); ?>
                                <span style="margin: 0 1rem;">•</span>
                                <i class="fas fa-clock"></i> <?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?>
                            </div>
                        </div>
                        <div class="message-actions">
                            <span class="message-status status-<?php echo $message['status']; ?>">
                                <?php echo ucfirst($message['status']); ?>
                            </span>
                            <form class="status-form" method="POST">
                                <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                <select name="status" class="status-select" onchange="this.form.submit()">
                                    <option value="new" <?php echo $message['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                    <option value="read" <?php echo $message['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                    <option value="replied" <?php echo $message['status'] === 'replied' ? 'selected' : ''; ?>>Replied</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    
                    <div class="message-content">
                        <div class="message-subject">
                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($message['subject']); ?>
                        </div>
                        <div class="message-text">
                            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                        </div>
                    </div>
                    
                    <div class="message-actions">
                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" 
                           class="btn btn-outline btn-small">
                            <i class="fas fa-reply"></i> Reply
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" 
                           class="btn btn-outline btn-small">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="btn btn-outline">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    <?php endif; ?>
                    
                    <span>Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="btn btn-outline">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>




