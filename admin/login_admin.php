<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="./style/style_login.css"> 
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Login</h1>
            <form>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">LOGIN</button>
            </form>
            <p>By clicking "Log In" you agree to our website <a href="#">Terms & Conditions</a>.</p>
        </div>
        <div class="logo-area">
            <img src="../admin/style/img/logo.png" alt="Plee Art logo">
        </div>
    </div>
</body>
</html>