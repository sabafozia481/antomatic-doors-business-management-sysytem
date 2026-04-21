<?php
$current_page = $_SERVER['PHP_SELF'];
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-door-open"></i>
            <span>ADM System</span>
        </div>
    </div>
    
    <nav class="sidebar-menu">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>index.php" class="nav-link <?php echo (strpos($current_page, 'index.php') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>clients/index.php" class="nav-link <?php echo (strpos($current_page, 'clients') !== false)  ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Clients</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>projects/index.php" class="nav-link <?php echo (strpos($current_page, 'projects') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-project-diagram"></i>
                    <span>Projects</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>income/index.php" class="nav-link <?php echo (strpos($current_page, 'income') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Income</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>expenses/index.php" class="nav-link <?php echo (strpos($current_page, 'expenses') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Expenses</span>
                </a>
            </li>
            <?php if ($_SESSION['user_role'] === 'Admin'): ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>users/index.php" class="nav-link <?php echo (strpos($current_page, 'users') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-user-shield"></i>
                    <span>Users</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="<?php echo BASE_URL; ?>auth/logout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
