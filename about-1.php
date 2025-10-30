 <?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login-1.php"); // go back one folder
exit();

}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> About</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="chrch style {.css">
    </head>

    <body>
        <button class="back" onclick="history.back()">Back</button>
        <header>
            <h1>Grace Fellowship Church</h1>
            
    <section class="hero animate-fade-in" style="background: url('images/church_inside.jpg') center/cover no-repeat;">
        <h1 class="animate-slide-up">About Our Church</h1>
        <p class="animate-slide-up delay-1">A community of faith, hope, and love for over 50 years.</p>
    </section>

    <!-- History Section -->
    <section class="animate-fade-in" style="padding: 40px;">
        <h2>Our History</h2>
        <p>
            Makuri Fellowship Church was founded in 1975 with the mission to bring people closer to God and each other.
            Over the years, our church has grown from a small gathering to a thriving congregation serving hundreds every week.
            We believe in strong community bonds, spreading the gospel, and living out God’s word daily.
        </p>
    </section>

    <!--location-->
    <section class="animate-fade-in" style="padding: 40px; background: #3d0487;">
        <h2>Our Location</h2>
        <p>
            We are located at makuri sublocation, Springfield. Join us every Sunday for worship services and community events.
            Our doors are open to everyone seeking spiritual growth and fellowship.
        </p>
    </section>

    <!-- Mission & Vision -->
    <section class="animate-fade-in" style="padding: 40px; background: #f9f9f9;">
        <h2>Our Mission</h2>
        <p>
            To share the love of Christ, nurture spiritual growth, and serve our community with compassion.
        </p>

        <h2>Our Vision</h2>
        <p>
            A church where everyone belongs, lives are transformed, and faith is lived out boldly.
        </p>
    </section>

    <!-- Leadership Team -->
    <section class="animate-fade-in" style="padding: 40px;">
        <h2>Our Leadership</h2>
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px; text-align: center;">
                <img src="images/pastor.jpg" alt="Pastor" style="width: 150px; border-radius: 50%;">
                <h3>Rev. John Smith</h3>
                <p>Senior Pastor</p>
            </div>
            <div style="flex: 1; min-width: 250px; text-align: center;">
                <img src="images/assistant_pastor.jpg" alt="Assistant Pastor" style="width: 150px; border-radius: 50%;">
                <h3>Mary Johnson</h3>
                <p>Assistant Pastor</p>
            </div>
            <div style="flex: 1; min-width: 250px; text-align: center;">
                <img src="images/worship_leader.jpg" alt="Worship Leader" style="width: 150px; border-radius: 50%;">
                <h3>David Lee</h3>
                <p>Worship Leader</p>
            </div>
        </div>
    </section>

    <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="events.html">Events</a></li>
        <li><a href="sermons.html">Sermons</a></li>
        <li><a href="contact.html">Contact</a></li>
    </ul>
    <!-- Footer -->
    <footer>
        <p>© 2025 Grace Fellowship Church. All rights reserved.</p>
    </footer>

    <script scr="animinate-1.js"></script>
</body>
</html>