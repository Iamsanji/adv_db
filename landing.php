<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OB Website</title>
    <style>
        /* Reset padding and margin for all elements */
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #4CAF50;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background-color 0.3s ease, backdrop-filter 0.3s ease;
        }

        .header.light {
            background-color: rgba(152, 152, 84, 0.7); /* Light color */
            backdrop-filter: blur(10px); /* Blur effect */
            color: #333; /* Text color change */
        }

        .header .title {
            font-size: 30px;
            font-weight: bold;
        }

        .header .title span {
            color: #FFD700;
        }

        .menu a {
            text-decoration: none;
            color: inherit;
            margin-left: 20px;
            font-weight: bold;
        }

        .menu a:hover {
            text-decoration: underline;
        }

        /* Main Section */
        main {
            padding: 20px;
        }

        /* Section 1 - About OB */
        .sec-1 {
            background-color: #f9f9f9;
            text-align: center;
            padding: 50px 20px;
            margin-bottom: 30px;
        }

        .sec-1 h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .sec-1 p {
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Section 2 - Features */
        .sec-2 {
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }

        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 15px;
            text-align: center;
            padding: 20px;
        }

        .card img {
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
        }

        .card h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            color: #666;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="title">
            O<span>B</span>
        </div>
        <div class="menu">
            <a href="#">Home</a>
            <a href="signin.php">Sign in</a>
        </div>
    </header>

    <main>
        <!-- Section 1: About OB -->
        <section class="sec-1" id="home">
            <h2>Welcome to OB</h2>
            <p>
                OB is a versatile system designed to simplify patient record management, track prescription history, and enhance role-based access for better collaboration among users, admins, and pharmacists. 
                With features like discount application, history tracking, and a robust booklet feature, OB ensures smooth operations tailored to your needs.
            </p>
        </section>

        <!-- Section 2: OB Features -->
        <section class="sec-2">
            <div class="card">
                <img src="record.png" alt="Record Management">
                <h3>Efficient Record Management</h3>
                <p>Organize and access patient records with ease, ensuring accuracy and efficiency.</p>
            </div>
            <div class="card">
                <img src="booklet.png" alt="Booklet Feature">
                <h3>Comprehensive Booklet</h3>
                <p>Keep a detailed history of prescriptions, discounts, and purchases all in one place.</p>
            </div>
            <div class="card">
                <img src="role.png" alt="Role-Based Access">
                <h3>Role-Based Access</h3>
                <p>Securely manage admin, pharmacist, and user roles for seamless collaboration.</p>
            </div>
        </section>
    </main>

    <footer>
        &copy; 2025 OB System. All Rights Reserved.
    </footer>

    <script>
        // JavaScript to handle scrolling
        const header = document.querySelector('.header');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('light'); // Add light class when scrolled down
            } else {
                header.classList.remove('light'); // Remove light class when scrolled back up
            }
        });
    </script>
</body>
</html>
