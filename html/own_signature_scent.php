<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "../php/database.php";

// Fetch and group notes by layer
$perfume_notes_result = $conn->query("SELECT * FROM perfume_notes ORDER BY layer, note_name ASC");
$notes = ['top' => [], 'heart' => [], 'base' => []];
while ($row = $perfume_notes_result->fetch_assoc()) {
    $notes[$row['layer']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Perfume | Scentaris</title>
    <link rel="stylesheet" href="create.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/own_signature_scent.css">
    <link rel="stylesheet" href="../css/darkmode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../javaScript/own_signature_scent.js"></script>
    <script src="../javaScript/darkmode.js"></script>
</head>

<body>

    <nav>
        <div class="logo"><strong>EDAMAME</strong></div>
        <ul>
            <li><a href="../index.html">Home</a></li>
            <li><a href="services.php">Fragrances</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact Us</a></li>
        </ul>
        <div class="nav-icons">
            <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode">
                <i class="fas fa-moon"></i>
            </button>
            <a href="profile.php">
                <div class="profile-icon" aria-label="Profile"><i class="fas fa-circle-user"></i></div>
            </a>
        </div>
    </nav>

    <section class="builder">

        <h1>Create Your Signature Scent</h1>

        <div class="form-group">
            <label>Perfume Name</label>
            <input type="text" id="perfumeName" placeholder="Enter your perfume name">
        </div>

        <div class="form-group">
            <label>Top Notes</label>
            <select id="topNote">
                <?php if (!empty($notes['top'])): ?>
                    <?php foreach ($notes['top'] as $note): ?>
                        <option value="<?= htmlspecialchars($note['note_name']) ?>">
                            <?= htmlspecialchars($note['note_name']) ?> (Intensity: <?= $note['intensity'] ?>)
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No top notes available</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Heart Notes</label>
            <select id="heartNote">
                <?php if (!empty($notes['heart'])): ?>
                    <?php foreach ($notes['heart'] as $note): ?>
                        <option value="<?= htmlspecialchars($note['note_name']) ?>">
                            <?= htmlspecialchars($note['note_name']) ?> (Intensity: <?= $note['intensity'] ?>)
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No heart notes available</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Base Notes</label>
            <select id="baseNote">
                <?php if (!empty($notes['base'])): ?>
                    <?php foreach ($notes['base'] as $note): ?>
                        <option value="<?= htmlspecialchars($note['note_name']) ?>">
                            <?= htmlspecialchars($note['note_name']) ?> (Intensity: <?= $note['intensity'] ?>)
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No base notes available</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Intensity</label>
            <input type="range" id="intensity" min="1" max="5" value="3">
            <span id="intensityValue">3</span>
        </div>

        <button onclick="createPerfume()">Create Perfume</button>

    </section>

    <section class="result" id="resultSection">
        <h2>Your Custom Perfume</h2>
        <div id="resultBox"></div>
        <button id="orderButton" onclick="orderPerfume()">Order Your Creation</button>
    </section>

    <script src="create.js"></script>
</body>

</html>