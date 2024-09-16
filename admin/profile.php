<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, Amanda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .profile-info {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .profile-email {
            font-size: 14px;
            color: #666;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .email-address {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .email-icon {
            margin-right: 10px;
        }

        .email-text {
            font-size: 14px;
            color: #333;
        }

        .email-date {
            font-size: 12px;
            color: #666;
        }

        .add-email-btn {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .add-email-btn:hover {
            background-color: #007bff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
        }

        .notification {
            position: relative;
            margin-left: 20px;
        }

        .notification-count {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 50%;
        }

        .profile-pic-header {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-pic-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Welcome, Amanda</h1>
        <div class="notification">
            <img src="https://www.pngkey.com/png/full/24-242947_user-icon-person-login-user-icon-png.png" alt="Notification" class="profile-pic-header">
            <span class="notification-count">1</span>
        </div>
    </header>
    <div class="container">
        <h1>Your Profile</h1>
        <div class="profile-info">
            <div class="profile-pic">
                <img src="https://images.unsplash.com/photo-1529665253569-6d01c0eaf7b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Profile Picture">
            </div>
            <div>
                <h2 class="profile-name">Alexa Rawles</h2>
                <p class="profile-email">alexarawles@gmail.com</p>
            </div>
            <button class="btn" id="editButton">Edit</button>
        </div>

        <div class="form-group">
            <label for="full-name">Full Name</label>
            <input type="text" id="full-name" placeholder="Your First Name">
        </div>

        <div class="form-group">
            <label for="nick-name">Nick Name</label>
            <input type="text" id="nick-name" placeholder="Your First Name">
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <select id="country">
                <option value="">Select Country</option>
                <option value="usa">USA</option>
                <option value="canada">Canada</option>
                <option value="uk">UK</option>
            </select>
        </div>

        <div class="form-group">
            <label for="language">Language</label>
            <select id="language">
                <option value="">Select Language</option>
                <option value="english">English</option>
                <option value="spanish">Spanish</option>
                <option value="french">French</option>
            </select>
        </div>

        <div class="form-group">
            <label for="time-zone">Time Zone</label>
            <select id="time-zone">
                <option value="">Select Time Zone</option>
                <option value="est">EST</option>
                <option value="pst">PST</option>
                <option value="utc">UTC</option>
            </select>
        </div>

        <div class="email-address">
            <div class="email-icon">
                <i class="fa fa-envelope"></i>
            </div>
            <div>
                <p class="email-text">alexarawles@gmail.com</p>
                <p class="email-date">1 month ago</p>
            </div>
        </div>

        <button class="add-email-btn" id="addEmailButton">Add Email Address</button>
    </div>
    <script>
        const editButton = document.getElementById('editButton');
        const addEmailButton = document.getElementById('addEmailButton');

        editButton.addEventListener('click', () => {
            // Implement logic to enable editing profile details
            alert('Edit button clicked. Implement editing logic here.');
        });

        addEmailButton.addEventListener('click', () => {
            // Implement logic to add a new email address
            alert('Add Email Address button clicked. Implement adding logic here.');
        });
    </script>
</body>
</html>