<?php
session_start();

$succes   = false;
$erori    = [];
$username = $email = $parola = '';
$mode     = $_GET['mode'] ?? 'register';

function incarcaDateSite($cale) {
    $date = [
        'mesaje' => [],
        'utilizatori' => []
    ];

    if (file_exists($cale)) {
        $continut = file_get_contents($cale);
        $dateExistente = json_decode($continut, true);

        if (is_array($dateExistente)) {
            if (isset($dateExistente['mesaje']) || isset($dateExistente['utilizatori'])) {
                $date['mesaje'] = $dateExistente['mesaje'] ?? [];
                $date['utilizatori'] = $dateExistente['utilizatori'] ?? [];
            } else {
                $date['mesaje'] = $dateExistente;
            }
        }
    }

    return $date;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? 'register';

    if ($mode === 'register') {
        $username = $_POST['username'] ?? '';
        $email    = $_POST['email']    ?? '';
        $parola   = $_POST['parola']   ?? '';
        $confirma = $_POST['confirma'] ?? '';

        $username = htmlspecialchars(trim($username));
        $email    = htmlspecialchars(trim($email));

        if (empty($username)) {
            $erori[] = "Username-ul este obligatoriu.";
        } elseif (strlen($username) < 3) {
            $erori[] = "Username-ul trebuie să aibă minim 3 caractere.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erori[] = "Email-ul nu este valid.";
        }

        if (empty($parola)) {
            $erori[] = "Parola este obligatorie.";
        } elseif (strlen($parola) < 6) {
            $erori[] = "Parola trebuie să aibă minim 6 caractere.";
        }

        if ($parola !== $confirma) {
            $erori[] = "Parolele nu coincid.";
        }

        if (empty($erori)) {
            $cale = '../data/data.json';
            $date = incarcaDateSite($cale);

            foreach ($date['utilizatori'] as $user) {
                if (($user['username'] ?? '') === $username) {
                    $erori[] = "Username-ul este deja folosit.";
                    break;
                }

                if (($user['email'] ?? '') === $email) {
                    $erori[] = "Email-ul este deja înregistrat.";
                    break;
                }
            }

            if (empty($erori)) {
                $date['utilizatori'][] = [
                    'id'       => uniqid(),
                    'username' => $username,
                    'email'    => $email,
                    'parola'   => password_hash($parola, PASSWORD_DEFAULT),
                    'data'     => date('d.m.Y H:i:s')
                ];

                file_put_contents($cale, json_encode($date, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $succes = true;
                $username = $email = '';
            }
        }
    } else {
        $username = $_POST['username'] ?? '';
        $parola   = $_POST['parola']   ?? '';
        $username = htmlspecialchars(trim($username));

        if (empty($username)) {
            $erori[] = "Username-ul este obligatoriu.";
        }

        if (empty($parola)) {
            $erori[] = "Parola este obligatorie.";
        }

        if (empty($erori)) {
            $cale = '../data/data.json';
            $date = incarcaDateSite($cale);

            $gasit = false;
            foreach ($date['utilizatori'] as $user) {
                if (($user['username'] ?? '') === $username) {
                    $gasit = true;

                    if (password_verify($parola, $user['parola'] ?? '')) {
                        $_SESSION['user'] = [
                            'id'       => $user['id'],
                            'username' => $user['username'],
                            'email'    => $user['email']
                        ];
                        header('Location: ../index.php');
                        exit;
                    } else {
                        $erori[] = "Parola incorectă.";
                    }
                    break;
                }
            }

            if (!$gasit) {
                $erori[] = "Username-ul nu există.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Authentication — The Boys</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/contact.css">
    <script>document.documentElement.classList.add(localStorage.getItem("theme") === "light" ? "light-mode" : "dark-mode");</script>
</head>
<body>

<div class="nav-bar">
    <a href="../index.php"><img src="../images/theboys.png" class="logo"></a>

    <div class="search-bar">
        <input type="text" placeholder="Search..." data-i18n-placeholder="search_characters">
        <button type="button"><img src="../images/search-icon-png-5.png"></button>
    </div>

    <div class="site-controls">
        <button id="themeToggle" class="control-btn" type="button" data-i18n="theme_light">Light mode</button>
        <select id="languageSelect" class="language-select" aria-label="Language">
            <option value="en">EN</option>
            <option value="ro">RO</option>
            <option value="ru">RU</option>
        </select>
    </div>

    <ul class="nav-links">
        <li><a href="../index.php" data-i18n="nav_home">Home</a></li>
        <li><a href="../index.php" data-i18n="nav_characters">Characters</a></li>
        <li><a href="../index.php" data-i18n="nav_episodes">Episodes</a></li>
        <li><a href="../index.php#loreSection" data-i18n="nav_lore">Lore</a></li>
        <li><a href="login.php" class="active" data-i18n="nav_auth">Authentication</a></li>
    </ul>
</div>

<div class="contact-wrapper">
    <div class="contact-header">
        <h1 class="contact-title" data-i18n="auth_title">AUTHENTICATION</h1>
        <p class="contact-subtitle" data-i18n="auth_subtitle">Join the resistance or sign back in.</p>
    </div>

    <div class="auth-tabs">
        <a href="login.php?mode=register" class="auth-tab <?php echo $mode === 'register' ? 'active' : ''; ?>" data-i18n="create_account">Create Account</a>
        <a href="login.php?mode=login" class="auth-tab <?php echo $mode === 'login' ? 'active' : ''; ?>" data-i18n="login">Log In</a>
    </div>

    <?php if ($succes): ?>
        <div class="alert alert-success">
            ✔ Account created! You can now <a href="login.php?mode=login" style="color:#2ecc40">log in here</a>.
        </div>
    <?php endif; ?>

    <?php if (!empty($erori)): ?>
        <div class="alert alert-error">
            <?php foreach ($erori as $e): ?>
                <p>✖ <?php echo $e; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($mode === 'register'): ?>
        <form class="contact-form" action="login.php" method="POST">
            <input type="hidden" name="mode" value="register">

            <div class="form-group">
                <label for="username"><span data-i18n="username">Username</span> <span class="req">*</span></label>
                <input type="text" id="username" name="username" placeholder="Ex: TheBoysFan99" value="<?php echo $username; ?>" required>
            </div>

            <div class="form-group">
                <label for="email"><span data-i18n="email">Email</span> <span class="req">*</span></label>
                <input type="email" id="email" name="email" placeholder="you@email.com" value="<?php echo $email; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="parola"><span data-i18n="password">Password</span> <span class="req">*</span></label>
                    <input type="password" id="parola" name="parola" placeholder="Min. 6 characters" required>
                </div>
                <div class="form-group">
                    <label for="confirma"><span data-i18n="confirm_password">Confirm Password</span> <span class="req">*</span></label>
                    <input type="password" id="confirma" name="confirma" placeholder="Repeat password" required>
                </div>
            </div>

            <button type="submit" class="submit-btn" data-i18n="create_account">Create Account</button>
        </form>
    <?php else: ?>
        <form class="contact-form" action="login.php" method="POST">
            <input type="hidden" name="mode" value="login">

            <div class="form-group">
                <label for="username"><span data-i18n="username">Username</span> <span class="req">*</span></label>
                <input type="text" id="username" name="username" placeholder="Ex: TheBoysFan99" value="<?php echo $username; ?>" required>
            </div>

            <div class="form-group">
                <label for="parola"><span data-i18n="password">Password</span> <span class="req">*</span></label>
                <input type="password" id="parola" name="parola" placeholder="Your password" required>
            </div>

            <button type="submit" class="submit-btn" data-i18n="login">Log In</button>
        </form>
    <?php endif; ?>
</div>

<script src="../js/index.js"></script>
</body>
</html>
