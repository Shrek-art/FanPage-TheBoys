<?php
session_start();

if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    header('Location: php/login.php?mode=login');
    exit;
}

$userEmail = $_SESSION['user']['email'] ?? '';
$userName  = $_SESSION['user']['username'] ?? 'User';
$cale = 'data/data.json';
$erori = [];
$succes = '';

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

    foreach ($date['mesaje'] as $index => $mesaj) {
        if (!isset($date['mesaje'][$index]['id'])) {
            $date['mesaje'][$index]['id'] = 'msg_' . $index;
        }
    }

    return $date;
}

function salveazaDateSite($cale, $date) {
    file_put_contents($cale, json_encode($date, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$date = incarcaDateSite($cale);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actiune = $_POST['actiune'] ?? '';
    $id = $_POST['id'] ?? '';

    if ($actiune === 'delete') {
        foreach ($date['mesaje'] as $index => $mesaj) {
            if (($mesaj['id'] ?? '') === $id && ($mesaj['email'] ?? '') === $userEmail) {
                unset($date['mesaje'][$index]);
                $date['mesaje'] = array_values($date['mesaje']);
                salveazaDateSite($cale, $date);
                $succes = 'Mesajul a fost șters cu succes.';
                break;
            }
        }
    }

    if ($actiune === 'edit') {
        $subiectNou = htmlspecialchars(trim($_POST['subiect'] ?? ''));
        $mesajNou = htmlspecialchars(trim($_POST['mesaj'] ?? ''));

        if (empty($subiectNou)) {
            $erori[] = 'Subiectul este obligatoriu.';
        }

        if (empty($mesajNou)) {
            $erori[] = 'Mesajul nu poate fi gol.';
        } elseif (strlen($mesajNou) > 1000) {
            $erori[] = 'Mesajul depășește 1000 de caractere.';
        }

        if (empty($erori)) {
            foreach ($date['mesaje'] as $index => $mesaj) {
                if (($mesaj['id'] ?? '') === $id && ($mesaj['email'] ?? '') === $userEmail) {
                    $date['mesaje'][$index]['subiect'] = $subiectNou;
                    $date['mesaje'][$index]['mesaj'] = $mesajNou;
                    $date['mesaje'][$index]['modificat'] = date('d.m.Y H:i:s');
                    salveazaDateSite($cale, $date);
                    $succes = 'Mesajul a fost modificat cu succes.';
                    break;
                }
            }
        }
    }
}

$mesajeleMele = [];
foreach ($date['mesaje'] as $mesaj) {
    if (($mesaj['email'] ?? '') === $userEmail) {
        $mesajeleMele[] = $mesaj;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Messages — The Boys</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/contact.css">
    <script>document.documentElement.classList.add(localStorage.getItem("theme") === "light" ? "light-mode" : "dark-mode");</script>
</head>
<body>

<div class="nav-bar">
    <a href="index.php"><img src="images/theboys.png" class="logo"></a>

    <div class="search-bar">
        <input type="text" placeholder="Search..." data-i18n-placeholder="search_characters">
        <button type="button"><img src="images/search-icon-png-5.png"></button>
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
        <li><a href="contact.php" data-i18n="nav_contact">Contact</a></li>
        <li><a href="messages.php" class="active" data-i18n="my_messages">My Messages</a></li>
        <li><a href="php/logout.php" data-i18n="nav_logout">Log Out</a></li>
    </ul>
</div>

<div class="messages-page">
    <div class="messages-header">
        <h1 data-i18n="messages_title">My Messages</h1>
        <p data-i18n="messages_subtitle">Here you can view, edit or delete the contact messages sent from your account.</p>
    </div>

    <?php if (!empty($succes)): ?>
        <div class="alert alert-success">✔ <?php echo $succes; ?></div>
    <?php endif; ?>

    <?php if (!empty($erori)): ?>
        <div class="alert alert-error">
            <?php foreach ($erori as $eroare): ?>
                <p>✖ <?php echo $eroare; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($mesajeleMele)): ?>
        <div class="message-card">
            <p data-i18n="no_messages">You have not sent any messages yet.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($mesajeleMele as $mesaj): ?>
        <div class="message-card">
            <h3><?php echo htmlspecialchars($mesaj['subiect'] ?? ''); ?></h3>
            <div class="message-meta">
                <?php echo htmlspecialchars($mesaj['data'] ?? ''); ?> • <?php echo htmlspecialchars($mesaj['email'] ?? ''); ?>
                <?php if (!empty($mesaj['modificat'])): ?>
                    • Modified: <?php echo htmlspecialchars($mesaj['modificat']); ?>
                <?php endif; ?>
            </div>

            <p><?php echo nl2br(htmlspecialchars($mesaj['mesaj'] ?? '')); ?></p>

            <form class="message-edit-form" action="messages.php" method="POST">
                <input type="hidden" name="actiune" value="edit">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($mesaj['id'] ?? ''); ?>">

                <select name="subiect" required>
                    <option value="intrebare" <?php echo ($mesaj['subiect'] ?? '') === 'intrebare' ? 'selected' : ''; ?> data-i18n="general_question">General question</option>
                    <option value="teorie" <?php echo ($mesaj['subiect'] ?? '') === 'teorie' ? 'selected' : ''; ?> data-i18n="theory">Theory / speculation</option>
                    <option value="eroare" <?php echo ($mesaj['subiect'] ?? '') === 'eroare' ? 'selected' : ''; ?> data-i18n="report_error">Report an error</option>
                    <option value="colaborare" <?php echo ($mesaj['subiect'] ?? '') === 'colaborare' ? 'selected' : ''; ?> data-i18n="collaboration">Collaboration</option>
                    <option value="altceva" <?php echo ($mesaj['subiect'] ?? '') === 'altceva' ? 'selected' : ''; ?> data-i18n="other">Other</option>
                </select>

                <textarea name="mesaj" maxlength="1000" required><?php echo htmlspecialchars($mesaj['mesaj'] ?? ''); ?></textarea>

                <div class="message-actions">
                    <button type="submit" class="edit-btn" data-i18n="save_changes">Save changes</button>
                </div>
            </form>

            <form action="messages.php" method="POST" class="message-actions" onsubmit="return confirm('Sigur vrei să ștergi acest mesaj?');">
                <input type="hidden" name="actiune" value="delete">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($mesaj['id'] ?? ''); ?>">
                <button type="submit" class="delete-btn" data-i18n="delete">Delete</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<script src="js/index.js"></script>
</body>
</html>
