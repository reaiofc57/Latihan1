<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $st = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $st->execute([$email]);
        $u = $st->fetch();
        if ($u && password_verify($password, $u['password'])) {
            login_user($u);
            $red = isset($_GET['redirect']) ? (string)$_GET['redirect'] : '';
            if ($red !== '' && str_starts_with($red, '/') && !str_starts_with($red, '//')) {
                header('Location: ' . $red);
            } else {
                header('Location: ' . url('index.php'));
            }
            exit;
        }
        $error = 'Email atau password salah.';
    }
}

$pageTitle = 'Masuk';
$metaDescription = 'Masuk ke akun ' . SITE_NAME . '.';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="auth-card" data-aos="zoom-in">
            <h1 class="mt-0">Masuk</h1>
            <p class="muted">Demo: <code>dewi@techblog.local</code> / <code>demo123</code></p>
            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="username" value="<?= e($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn" style="width:100%">Masuk</button>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
