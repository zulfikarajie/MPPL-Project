    <?php
    require 'config.php';

    // Validate actions
    $action = $_GET['action'] ?? '';

    try {
        $pdo = getConnection();

        if ($action === 'fetch_users') {
            // Fetch all users
            $stmt = $pdo->query("SELECT id, username, role, subscription_role FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);

        } elseif ($action === 'fetch_complaints') {
            // Fetch all complaints
            $stmt = $pdo->query("SELECT * FROM complain ORDER BY created_at DESC");
            $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($complaints);

        } elseif ($action === 'delete_user' && isset($_GET['id'])) {
            // Delete user
            $id = (int) $_GET['id'];
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);

        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    ?>
