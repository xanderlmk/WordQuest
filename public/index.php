<?php

$title = "Home";
ob_start();

?>

<h1>Hello, World!</h1>
<p>Welcome to the WordQuest project.</p>


<button onclick="logout()">Logout</button>


<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>
