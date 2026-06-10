<?php
session_start();

$succes = false;
$erori  = [];
$nume   = $email = $subiect = $mesaj = '';
$emailTrimis = '';

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
    $nume    = $_POST['nume']    ?? '';
    $email   = $_POST['email']   ?? '';
    $subiect = $_POST['subiect'] ?? '';
    $mesaj   = $_POST['mesaj']   ?? '';

    $nume    = htmlspecialchars(trim($nume));
    $email   = htmlspecialchars(trim($email));
    $subiect = htmlspecialchars(trim($subiect));
    $mesaj   = htmlspecialchars(trim($mesaj));

    if (empty($nume)) {
        $erori[] = "Numele este obligatoriu.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erori[] = "Email-ul nu este valid.";
    }

    if (empty($subiect)) {
        $erori[] = "Alege un subiect.";
    }

    if (empty($mesaj)) {
        $erori[] = "Mesajul nu poate fi gol.";
    } elseif (strlen($mesaj) > 1000) {
        $erori[] = "Mesajul depășește 1000 de caractere.";
    }

    if (empty($erori)) {
        $emailTrimis = $email;
        $cale = 'data/data.json';
        $date = incarcaDateSite($cale);

        $date['mesaje'][] = [
            'id'      => uniqid(),
            'data'    => date('d.m.Y H:i:s'),
            'nume'    => $nume,
            'email'   => $email,
            'subiect' => $subiect,
            'mesaj'   => $mesaj
        ];

        file_put_contents($cale, json_encode($date, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $succes = true;
        $nume = '';
        $email = '';
        $subiect = '';
        $mesaj = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact — The Boys</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/contact.css">
    <script>document.documentElement.classList.add(localStorage.getItem("theme") === "light" ? "light-mode" : "dark-mode");</script>
</head>
<body>

<div class="nav-bar">
    <a href="index.php">
        <img src="images/theboys.png" class="logo">
    </a>

    <div class="search-bar">
        <input type="text" placeholder="Search..." data-i18n-placeholder="search_characters">
        <button type="button">
            <img src="images/search-icon-png-5.png">
        </button>
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
        <li><a href="index.php" data-i18n="nav_home">Home</a></li>
        <li><a href="index.php" data-i18n="nav_characters">Characters</a></li>
        <li><a href="index.php" data-i18n="nav_episodes">Episodes</a></li>
        <li><a href="index.php#loreSection" data-i18n="nav_lore">Lore</a></li>

        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="messages.php" data-i18n="my_messages">My Messages</a></li>
            <li><a href="php/logout.php" data-i18n="nav_logout">Log Out</a></li>
        <?php else: ?>
            <li><a href="php/login.php" data-i18n="nav_auth">Authentication</a></li>
        <?php endif; ?>

        <li><a href="contact.php" class="active" data-i18n="nav_contact">Contact</a></li>
    </ul>
</div>

<div class="contact-wrapper">

    <div class="contact-header">
        <h1 class="contact-title" data-i18n="contact_title">CONTACT</h1>
        <p class="contact-subtitle" data-i18n="contact_subtitle">You have a theory? Found an error? Contact us.</p>
    </div>

    <?php if ($succes): ?>
        <div class="alert alert-success">
            ✔ Message has been sent successfully! We're going to contact you on
            <strong><?php echo $emailTrimis; ?></strong>.
        </div>
    <?php endif; ?>

    <?php if (!empty($erori)): ?>
        <div class="alert alert-error">
            <?php foreach ($erori as $e): ?>
                <p>✖ <?php echo $e; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="contact-form" action="contact.php" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="nume"><span data-i18n="name">Name</span> <span class="req">*</span></label>
                <input type="text" id="nume" name="nume" placeholder="Ex: Billy Butcher" value="<?php echo $nume; ?>" required>
            </div>

            <div class="form-group">
                <label for="email"><span data-i18n="email">Email</span> <span class="req">*</span></label>
                <input type="email" id="email" name="email" placeholder="you@email.com" value="<?php echo $email; ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="subiect"><span data-i18n="subject">Subject</span> <span class="req">*</span></label>
            <select id="subiect" name="subiect" required>
                <option value="" disabled <?php echo empty($subiect) ? 'selected' : ''; ?> data-i18n="choose_subject">Choose a subject...</option>
                <option value="intrebare" <?php echo $subiect === 'intrebare' ? 'selected' : ''; ?> data-i18n="general_question">General question</option>
                <option value="teorie" <?php echo $subiect === 'teorie' ? 'selected' : ''; ?> data-i18n="theory">Theory / speculation</option>
                <option value="eroare" <?php echo $subiect === 'eroare' ? 'selected' : ''; ?> data-i18n="report_error">Report an error</option>
                <option value="colaborare" <?php echo $subiect === 'colaborare' ? 'selected' : ''; ?> data-i18n="collaboration">Collaboration</option>
                <option value="altceva" <?php echo $subiect === 'altceva' ? 'selected' : ''; ?> data-i18n="other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="mesaj"><span data-i18n="message">Message</span> <span class="req">*</span></label>
            <textarea id="mesaj" name="mesaj" placeholder="Write your message..." maxlength="1000" required><?php echo $mesaj; ?></textarea>
            <span class="char-hint" data-i18n="max_1000">Max. 1000 characters</span>
        </div>

        <button type="submit" class="submit-btn" data-i18n="send_message">Send message</button>
    </form>

</div>

<script src="js/index.js"></script>
</body>
</html>
