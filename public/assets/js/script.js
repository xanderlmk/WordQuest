// Function to handle login

//  TODO: Handle auth errors => like err hint into forms

function login(email, password) {
    fetch('api/auth/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('auth_token', data.token);
            //window.location.href = 'home.php';
        } else {
            document.getElementById('error-message').innerText = data.error;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to handle signup
function signup(username, email, password) {
    fetch('api/auth/signup.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username: username, email: email, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('auth_token', data.token);
            //window.location.href = 'home.php';
        } else {
            document.getElementById('error-message').innerText = data.error;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to handle logout
function logout() {
    fetch('api/auth/logout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('auth_token');
            window.location.href = 'login.php';
        }
    })
    .catch(error => console.error('Error:', error));
}

// Event listener for login form
const loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        login(email, password);
    });
}

// Event listener for signup form
const signupForm = document.getElementById('signup-form');
if (signupForm) {
    signupForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        signup(username, email, password);
    });
}