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
    <title>Create Your Perfume | EDAMAME</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/own_signature_scent.css">
    <link rel="stylesheet" href="../css/darkmode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        <!-- Step 1: Build your perfume -->
        <div id="step1">
            <div class="form-group">
                <label>Perfume Name</label>
                <input type="text" id="perfumeName" placeholder="Enter your perfume name">
            </div>

            <div class="form-group">
                <label>Top Notes</label>
                <select id="topNote">
                    <?php foreach ($notes['top'] as $note): ?>
                        <option value="<?= htmlspecialchars($note['note_name']) ?>" data-intensity="<?= $note['intensity'] ?>">
                            <?= htmlspecialchars($note['note_name']) ?> (Intensity: <?= $note['intensity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Heart Notes</label>
                <select id="heartNote">
                    <?php foreach ($notes['heart'] as $note): ?>
                        <option value="<?= htmlspecialchars($note['note_name']) ?>" data-intensity="<?= $note['intensity'] ?>">
                            <?= htmlspecialchars($note['note_name']) ?> (Intensity: <?= $note['intensity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Base Notes</label>
                <select id="baseNote">
                    <?php foreach ($notes['base'] as $note): ?>
                        <option value="<?= htmlspecialchars($note['note_name']) ?>" data-intensity="<?= $note['intensity'] ?>">
                            <?= htmlspecialchars($note['note_name']) ?> (Intensity: <?= $note['intensity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Concentration</label>
                <select id="concentration">
                    <option value="EDC">EDC</option>
                    <option value="EDT">EDT</option>
                    <option value="EDP" selected>EDP</option>
                    <option value="Parfum">Parfum</option>
                </select>
            </div>

            <div class="form-group">
                <label>Size (ml)</label>
                <select id="size_ml">
                    <option value="30">30ml</option>
                    <option value="50" selected>50ml</option>
                    <option value="100">100ml</option>
                </select>
            </div>

            <div class="form-group">
                <label>Overall Intensity</label>
                <input type="range" id="intensity" min="1" max="5" value="3" oninput="document.getElementById('intensityValue').textContent = this.value">
                <span id="intensityValue">3</span>
            </div>

            <button onclick="previewPerfume()">Preview My Perfume</button>
        </div>

        <!-- Step 2: Preview + actions -->
        <div id="step2" style="display:none;">
            <div id="previewBox" style="background:#f9f9f9; border-radius:10px; padding:20px; margin-bottom:20px;"></div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button onclick="savePerfume()" id="saveBtn">
                    <i class="fas fa-heart"></i> Save Perfume
                </button>
                <button onclick="addToCart()" id="cartBtn">
                    <i class="fas fa-cart-plus"></i> Add to Cart
                </button>
                <button onclick="goBack()" style="background:#ccc; color:#333;">
                    <i class="fas fa-arrow-left"></i> Edit
                </button>
            </div>

            <div id="actionMsg" style="margin-top:15px; font-size:0.9rem;"></div>
        </div>
    </section>

    <!-- Hidden form for Save Perfume -->
    <form id="saveForm" action="../php/save_perfume.php" method="POST" style="display:none;">
        <input type="hidden" name="name" id="f_name">
        <input type="hidden" name="top_note" id="f_top">
        <input type="hidden" name="heart_note" id="f_heart">
        <input type="hidden" name="base_note" id="f_base">
        <input type="hidden" name="concentration" id="f_concentration">
        <input type="hidden" name="size_ml" id="f_size">
        <input type="hidden" name="intensity" id="f_intensity">
    </form>

    <!-- Hidden form for Add to Cart -->
    <form id="cartForm" action="../php/add_to_cart.php" method="POST" style="display:none;">
        <input type="hidden" name="name" id="c_name">
        <input type="hidden" name="top_note" id="c_top">
        <input type="hidden" name="heart_note" id="c_heart">
        <input type="hidden" name="base_note" id="c_base">
        <input type="hidden" name="concentration" id="c_concentration">
        <input type="hidden" name="size_ml" id="c_size">
        <input type="hidden" name="intensity" id="c_intensity">
    </form>

    <script>
        function previewPerfume() {
            const name = document.getElementById('perfumeName').value.trim();
            const topNote = document.getElementById('topNote').value;
            const heartNote = document.getElementById('heartNote').value;
            const baseNote = document.getElementById('baseNote').value;
            const concentration = document.getElementById('concentration').value;
            const size = document.getElementById('size_ml').value;
            const intensity = document.getElementById('intensity').value;

            if (!name) {
                alert('Please enter a perfume name.');
                return;
            }

            document.getElementById('previewBox').innerHTML = `
            <h3 style="margin-top:0;">${name}</h3>
            <p><strong>Top Note:</strong> ${topNote}</p>
            <p><strong>Heart Note:</strong> ${heartNote}</p>
            <p><strong>Base Note:</strong> ${baseNote}</p>
            <p><strong>Concentration:</strong> ${concentration}</p>
            <p><strong>Size:</strong> ${size}ml</p>
            <p><strong>Intensity:</strong> ${intensity}/5</p>
        `;

            // Fill both hidden forms
            ['f_', 'c_'].forEach(prefix => {
                document.getElementById(prefix + 'name').value = name;
                document.getElementById(prefix + 'top').value = topNote;
                document.getElementById(prefix + 'heart').value = heartNote;
                document.getElementById(prefix + 'base').value = baseNote;
                document.getElementById(prefix + 'concentration').value = concentration;
                document.getElementById(prefix + 'size').value = size;
                document.getElementById(prefix + 'intensity').value = intensity;
            });

            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        }

        function savePerfume() {
            document.getElementById('saveForm').submit();
        }

        function addToCart() {
            document.getElementById('cartForm').submit();
        }

        function goBack() {
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
        }
    </script>

</body>

</html>