<?php
require_once 'functions.php';

$message = "";
$section = isset($_GET['section']) ? $_GET['section'] : 'add_user';

if ($_POST) {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'add_user') {
        $userId = 'U' . str_pad($_SESSION['library']['nextTransactionId']++, 3, '0', STR_PAD_LEFT);
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        
        if ($name) {
            $_SESSION['library']['users'][$userId] = array(
                'name' => $name,
                'outstanding_fine' => 0,
                'transactions' => array()
            );
            $message = "<div class='message success'>User added. ID: $userId</div>";
            $section = 'add_user';
        }
    }
    
    elseif ($action === 'borrow_book') {
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        $book = isset($_POST['book']) ? trim($_POST['book']) : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $days = isset($_POST['days']) ? intval($_POST['days']) : 0;
        
        if (isset($_SESSION['library']['users'][$userId])) {
            $user = &$_SESSION['library']['users'][$userId];
            
            if ($user['outstanding_fine'] > 200) {
                $message = "<div class='message error'>Cannot borrow. Fine exceeds R200.</div>";
            } elseif ($book && $days > 0) {
                $dueDate = date('Y-m-d', strtotime("+$days days"));
                
                $transaction = array(
                    'id' => 'T' . date('His'),
                    'book' => $book,
                    'category' => $category,
                    'borrowed_date' => date('Y-m-d'),
                    'due_date' => $dueDate,
                    'status' => 'Borrowed',
                    'fine' => 0
                );
                
                $user['transactions'][] = $transaction;
                $message = "<div class='message success'>Book borrowed successfully.</div>";
                $section = 'borrow';
            }
        }
    }
    
    elseif ($action === 'return_book') {
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        $transactionId = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : '';
        
        if (isset($_SESSION['library']['users'][$userId])) {
            $user = &$_SESSION['library']['users'][$userId];
            
            foreach ($user['transactions'] as &$t) {
                if ($t['id'] === $transactionId && $t['status'] === 'Borrowed') {
                    $daysLate = max(0, (strtotime(date('Y-m-d')) - strtotime($t['due_date'])) / 86400);
                    $rate = getBookDailyRate($t['category']);
                    $fine = calculateFine($daysLate, $rate);
                    
                    $t['status'] = 'Returned';
                    $t['returned_date'] = date('Y-m-d');
                    $t['fine'] = $fine;
                    $user['outstanding_fine'] += $fine;
                    
                    $message = "<div class='message success'>Book returned. Fine: R" . number_format($fine, 2) . "</div>";
                    $section = 'return';
                }
            }
        }
    }
    
    elseif ($action === 'pay_fine') {
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        
        if (isset($_SESSION['library']['users'][$userId]) && $amount > 0) {
            $user = &$_SESSION['library']['users'][$userId];
            
            if ($amount <= $user['outstanding_fine']) {
                $user['outstanding_fine'] -= $amount;
                $message = "<div class='message success'>Payment successful.</div>";
                $section = 'pay_fine';
            } else {
                $message = "<div class='message error'>Payment exceeds outstanding fine.</div>";
            }
        }
    }
}

$users = isset($_SESSION['library']['users']) ? $_SESSION['library']['users'] : array();
?>

<div class="content">
    <a href="?module=home" class="back-link">Back to Home</a>
    
    <?php echo $message; ?>
    
    <h2>Library Management</h2>
    
    <div class="section-links">
        <a href="?module=library&section=add_user" <?php echo $section === 'add_user' ? 'class="active"' : ''; ?>>Add User</a>
        <a href="?module=library&section=borrow" <?php echo $section === 'borrow' ? 'class="active"' : ''; ?>>Borrow</a>
        <a href="?module=library&section=return" <?php echo $section === 'return' ? 'class="active"' : ''; ?>>Return</a>
        <a href="?module=library&section=pay_fine" <?php echo $section === 'pay_fine' ? 'class="active"' : ''; ?>>Pay Fine</a>
        <a href="?module=library&section=summary" <?php echo $section === 'summary' ? 'class="active"' : ''; ?>>Summary</a>
    </div>
    
    <?php if ($section === 'add_user'): ?>
        <h2>Add User</h2>
        <form method="POST" style="max-width: 300px;">
            <input type="hidden" name="action" value="add_user">
            
            <div class="form-group">
                <label>User Name *</label>
                <input type="text" name="name" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Add User</button>
        </form>
    <?php endif; ?>
    
    <?php if ($section === 'borrow'): ?>
        <h2>Borrow Book</h2>
        <form method="POST" style="max-width: 400px;">
            <input type="hidden" name="action" value="borrow_book">
            
            <div class="form-group">
                <label>User *</label>
                <select name="user_id" required>
                    <option value="">Select User</option>
                    <?php foreach ($users as $id => $user): ?>
                        <option value="<?php echo $id; ?>"><?php echo $user['name'] . " ($id)"; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Book Title *</label>
                <input type="text" name="book" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="">Select</option>
                        <option value="textbook">Textbook (R5/day)</option>
                        <option value="journal">Journal (R3/day)</option>
                        <option value="reference book">Reference Book (R10/day)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Days *</label>
                    <input type="number" name="days" min="1" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Borrow</button>
        </form>
    <?php endif; ?>
    
    <?php if ($section === 'return'): ?>
        <h2>Return Book</h2>
        <form method="POST" style="max-width: 400px;">
            <input type="hidden" name="action" value="return_book">
            
            <div class="form-group">
                <label>User *</label>
                <select name="user_id" required>
                    <option value="">Select User</option>
                    <?php foreach ($users as $id => $user): ?>
                        <option value="<?php echo $id; ?>"><?php echo $user['name'] . " ($id)"; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Borrowed Book *</label>
                <select name="transaction_id" required>
                    <option value="">Select Book</option>
                    <?php 
                    foreach ($users as $u) {
                        foreach ($u['transactions'] as $t) {
                            if ($t['status'] === 'Borrowed') {
                                echo "<option value='" . $t['id'] . "'>" . $t['book'] . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Return</button>
        </form>
    <?php endif; ?>
    
    <?php if ($section === 'pay_fine'): ?>
        <h2>Pay Fine</h2>
        <form method="POST" style="max-width: 400px;">
            <input type="hidden" name="action" value="pay_fine">
            
            <div class="form-group">
                <label>User *</label>
                <select name="user_id" required>
                    <option value="">Select User</option>
                    <?php foreach ($users as $id => $user): ?>
                        <option value="<?php echo $id; ?>">
                            <?php echo $user['name'] . " - Fine: R" . number_format($user['outstanding_fine'], 2); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Amount (R) *</label>
                <input type="number" step="0.01" name="amount" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Pay</button>
        </form>
    <?php endif; ?>  
    <?php if ($section === 'summary'): ?>
        <h2>User Summary</h2>

        <?php if (!empty($users)): ?>
            <?php foreach ($users as $id => $user): ?>
                <div class="card">
                    <h3><?php echo $user['name'] . " ($id)"; ?></h3>
                    <p>Outstanding Fine: R<?php echo number_format($user['outstanding_fine'], 2); ?></p>
                    <p>Transactions: <?php echo count($user['transactions']); ?></p>
                    
                    <?php if (!empty($user['transactions'])): ?>
                        <table class="table">
                            <tr>
                                <th>Book</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Fine</th>
                            </tr>
                            <?php foreach ($user['transactions'] as $t): ?>
                                <tr>
                                    <td><?php echo $t['book']; ?></td>
                                    <td><?php echo $t['category']; ?></td>
                                    <td><?php echo $t['status']; ?></td>
                                    <td>R<?php echo number_format($t['fine'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No users yet.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
