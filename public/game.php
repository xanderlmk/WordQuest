<?php

require_once '../src/config/session.php';
//require_once '../src/config/logger.php';
require_once '../src/middleware/AuthMiddleware.php';
require_once '../src/controllers/UserController.php';
require_once '../src/controllers/GameController.php';

// $logger = new Logger();
// $logger->console_log("game.php");

$authMiddleware = new AuthMiddleware();
$authMiddleware->checkSession();

$userController = new UserController();
$user = $userController->getUser();

if ($user) {
    $username = $user['username'];
    $token = $_SESSION['auth_token'];

    $gameController = new GameController();
    $game = $gameController->loadGame($token, $username);

    if (!$game) {
        header('Location: game_settings.php');
        exit();
    } else {
        $secretWord = $game['secret_word'];
        $wordLength = strlen($secretWord);  //  test
        $maxAttempts = 5;
    }
} else {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(<?php echo $wordLength; ?>, 1fr);
            gap: 10px;
            max-width: 300px;
            margin: auto;
        }
        .grid input {
            width: 100%;
            text-align: center;
            font-size: 2rem;
            text-transform: uppercase;
        }
        .grid input.disabled {
            background-color: #ddd;
            pointer-events: none;
        }
        .correct {
            background-color: green;
            color: green;
        }
        .misplaced {
            background-color: orange;
            color: orange;
        }
        .incorrect {
            background-color: black;
            color: black;
        }
    </style>
</head>
<body>
    <h2>Wordle Game</h2>
    <form id="gameForm">
        <div class="grid" id="grid">
            <?php for ($i = 0; $i < $maxAttempts; $i++): ?>
                <?php for ($j = 0; $j < $wordLength; $j++): ?>
                    <input type="text" name="guess[<?php echo $i; ?>][<?php echo $j; ?>]" maxlength="1" <?php echo $i === 0 ? '' : 'class="disabled"'; ?>>
                <?php endfor; ?>
            <?php endfor; ?>
        </div>
        <br>
        <button type="button" id="submitGuess">Submit Guess</button>
    </form>
    <form method="POST" action="">
        <button type="submit" name="logout" value="1">Logout</button>
    </form>
    <script>
    document.getElementById('submitGuess').addEventListener('click', function() {
        // Validate the current row
        const grid = document.getElementById('grid');
        const currentRow = Array.from(grid.querySelectorAll('input:not(.disabled)'));
        let valid = true;
        currentRow.forEach(input => {
            if (!input.value) {
                valid = false;
            }
        });

        if (!valid) {
            alert('Please fill out all fields in the current row.');
            return;
        }

        // Collect the current form data from active inputs only
        const currentRowValues = [];
        currentRow.forEach(input => {
            currentRowValues.push(input.value);
        });

        console.log("Current row values:", currentRowValues);

        // Unite letters into a word
        const currentWord = currentRowValues.join('');
        console.log("Current word:", currentWord);

        // Make an AJAX request to send the attempt
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_attempt.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    console.log('res', xhr.responseText)
                    const response = JSON.parse(xhr.responseText);
                    console.log(response);

                    if (typeof response === 'string') {
                        alert(response); // Show success or error message
                    } else if (Array.isArray(response)) {
                        // Show feedback for the attempt
                        response.forEach((status, index) => {
                            currentRow[index].classList.add(status === 2 ? 'correct' : status === 1 ? 'misplaced' : 'incorrect');
                        });
                    }

                    // Disable the current row
                    currentRow.forEach(input => input.classList.add('disabled'));

                    // Find the next row and enable it
                    const allInputs = Array.from(grid.querySelectorAll('input'));
                    const currentRowIndex = currentRow[0].name.match(/\d+/)[0];
                    const nextRowStartIndex = (parseInt(currentRowIndex) + 1) * <?php echo $wordLength; ?>;
                    const nextRow = allInputs.slice(nextRowStartIndex, nextRowStartIndex + <?php echo $wordLength; ?>);

                    if (nextRow.length) {
                        nextRow.forEach(input => input.classList.remove('disabled'));
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    console.error('Response received:', xhr.responseText);
                }
            }
        };
        xhr.send(JSON.stringify({
            token: '<?php echo $token; ?>',
            username: '<?php echo $username; ?>',
            attempt_word: currentWord
        }));
    });
</script>
</body>
</html>
