<?php
//színek
$colors = [
    "red" => "#FF0000",
    "green" => "#008000",
    "blue" => "#0000FF",
    "yellow" => "#FFFF00",
    "purple" => "#800080",
    "cyan" => "#00FFFF",
    "orange" => "#FFA500",
    "pink" => "#FFC0CB",
    "brown" => "#6E2C00",
    "lime" => "#00FF00",
    "teal" => "#008080",
    "magenta" => "#FF00FF"
];

// véletlenszerű szín kiválasztása
function getRandomColor($colors) {
    return array_rand($colors);
}

$selectedTime = 30; // alapértelmezett 30 másodperc
$endTime = null;
$score = 0;
$correctAnswers = 0;
$currentColor = null;
$isGameActive = false;
$isGameEnded = false;

if (isset($_GET['start'])) {
    $selectedTime = (int)$_GET['time'];
    $endTime = time() + $selectedTime; //végidő
    $isGameActive = true;
    $currentColor = getRandomColor($colors);
}

if ($endTime !== null && time() >= $endTime) {
    $isGameActive = false; // leállítás, ha az idő véget ér
    $isGameEnded = true; // vége
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Színfelismerő Játék</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #121212;
            color: #E0E0E0;
            margin: 0;
            overflow: hidden;
            position: relative;
            animation: backgroundAnim 10s infinite alternate;
        }

        @keyframes backgroundAnim {
            0% { background-color: #121212; }
            50% { background-color: #1e1e1e; }
            100% { background-color: #2e2e2e; }
        }

        .game-container {
            text-align: center;
            padding: 20px;
            background-color: rgba(30, 30, 30, 0.9);
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            z-index: 1;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1 {
            font-size: 2.5rem;
            color: #ff4081;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .color-btn {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: none;
            margin: 10px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: wiggle 1s infinite;
            opacity: 0;
        }

        @keyframes wiggle {
            0%, 100% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(5deg);
            }
            50% {
                transform: rotate(-5deg);
            }
            75% {
                transform: rotate(2deg);
            }
        }

        .color-btn:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
        }

        .message {
            font-size: 2rem;
            margin-top: 20px;
            color: #4CAF50;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.3);
        }

        .score, .final-score {
            font-size: 2rem;
            margin-top: 20px;
            color: #ff4081;
        }

        .btn-reset, .btn-time {
            background-color: #ff4081;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.5rem;
            margin-top: 20px;
            transition: background-color 0.3s;
            position: relative;
            z-index: 1;
        }

        .btn-reset:hover, .btn-time:hover {
            background-color: #d5006d;
        }

        .timer {
            font-size: 2rem;
            color: #ff9800;
            margin-top: 10px;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.3);
        }

        /* startmenu */
        .start-menu {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .time-selection {
            margin-top: 20px;
        }

        .color-buttons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .color-buttons.active {
            opacity: 1;
        }
        .final-score-container {
            display: none;
            flex-direction: column;
            align-items: center;
        }

        .final-score-message {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-top: 20px;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>

<div class="game-container">
    <?php if (!$isGameActive && $isGameEnded): ?>
        <div class="final-score-container">
            <div class="final-score-message">A végső pontszámod: <?= ($correctAnswers * 10); ?></div>
            <button class="btn-reset" onclick="window.location.href='?';">Új Játék</button>
        </div>
    <?php elseif (!$isGameActive): ?>
        <h1>Színfelismerő Játék</h1>
        <p><sup>nem szín vakoknak készítve</sup></p>
        <div class="start-menu">
            <h2>Válaszd ki a játékidőt!</h2>
            <div class="time-selection">
                <a href="?start=true&time=30"><button class="btn-time">30 másodperc</button></a>
                <a href="?start=true&time=60"><button class="btn-time">1 perc</button></a>
                <a href="?start=true&time=120"><button class="btn-time">2 perc</button></a>
            </div>
        </div>
    <?php else: ?>
        <h1>Válaszd ki a következő színt: <strong><?= ucfirst(array_keys($colors)[$currentColor]); ?></strong></h1>
        <div class="color-buttons active">
            <?php foreach ($colors as $colorName => $colorValue): ?>
                <button class="color-btn" data-color="<?= $colorName; ?>" style="background-color: <?= $colorValue; ?>;"></button>
            <?php endforeach; ?>
        </div>
        
        <div class="message" id="message"></div>
        <div class="score" id="score">Pontszám: <?= $score; ?></div>
        <div class="timer" id="timer">Maradt idő: <?= $selectedTime; ?> másodperc</div>

        <button class="btn-reset" onclick="window.location.href='?';">Vissza a menübe</button>
    <?php endif; ?>
</div>

<script>
    let timerId;
    let currentColor = '<?= $currentColor; ?>';
    const colors = <?= json_encode($colors); ?>;
    const colorButtons = document.querySelectorAll('.color-btn');
    const messageElement = document.getElementById('message');
    const scoreElement = document.getElementById('score');
    const timerElement = document.getElementById('timer');
    let totalTime = <?= $selectedTime; ?>;
    let score = 0;
    let correctAnswers = 0;

    //indításs
    function startGame() {
        score = 0;
        correctAnswers = 0;
        currentColor = getRandomColor();
        document.querySelector('h1 strong').textContent = currentColor;
        startTimer();
        showColorButtons();
    }

    // szin kivalasztas
    colorButtons.forEach(button => {
        button.style.opacity = '1'; // szinek megjelenitese kezdeskor
        button.addEventListener('click', function() {
            const selectedColor = this.getAttribute('data-color');
            if (selectedColor === currentColor) {
                score += 10;
                correctAnswers++;
                messageElement.textContent = 'Helyes válasz! 🎉';
                scoreElement.textContent = 'Pontszám: ' + score;
            } else {
                messageElement.textContent = 'Rossz válasz. 😢 Próbáld újra!';
            }
            currentColor = getRandomColor();
            document.querySelector('h1 strong').textContent = currentColor;
        });
    });

    // veletlenszeru szin
    function getRandomColor() {
        const colorKeys = Object.keys(colors);
        return colorKeys[Math.floor(Math.random() * colorKeys.length)];
    }

    function startTimer() {
        timerId = setInterval(() => {
            totalTime--;
            timerElement.textContent = 'Maradt idő: ' + totalTime + ' másodperc';

            if (totalTime <= 0) {
                clearInterval(timerId);
                endGame();
            }
        }, 1000);
    }

    // VÉGE
    function endGame() {
        messageElement.textContent = 'Játék vége! A végső pontszámod: ' + (correctAnswers * 10);
        scoreElement.textContent = 'Pontszám: ' + (correctAnswers * 10);
        timerElement.textContent = '';
        hideColorButtons();
        document.querySelector('.final-score-container').style.display = 'flex';
    }

    //gombok elrejtése
    function hideColorButtons() {
        document.querySelector('.color-buttons').classList.remove('active');
    }

    //gombok megjelenítése
    function showColorButtons() {
        document.querySelector('.color-buttons').classList.add('active');
    }

    //indítas a megfelelőu időtartammal
    if (<?= $isGameActive ? 'true' : 'false'; ?>) {
        startGame();
    }
</script>
</body>
</html>
