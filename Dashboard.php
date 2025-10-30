<?php
session_start();

// Start timer
$start = microtime(true);

// Protect dashboard
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Admin Dashboard</title>
    <style>
      body {
        margin:0;
        font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        background-image: url("images/Copilot_20251029_191447.jpeg");
        font-size: large;
        padding:0;
        background-size: cover;
        background-attachment: fixed;

      }
        /*header*/
        h1 {
          text-align:center;
          color:green;

        }
        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #ebeceeff;
            color: white;
            padding: 20px;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
/*rotate icon*/
         .rotate-icon {
    width: 100px;
    height: 100px;
    animation: spin 6s linear infinite;
    transition: transform 0.3s ease;
  }
  .input-container {
    print-color-adjust: exact;
  }
  .rotate-icon:hover {
    animation-play-state: paused;
    transform: scale(1.1);
  }

  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
        .sidebar a:hover {
            background: #34495e;
        }
        /* Content area */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        /* Top navbar */
        .topnav {
            background: #1891c5;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
/*button*/
.button {
  color: pink;
}

        .main-content {
            flex: 1;
            padding: 20px;
            background-image: url("");
            background-size: cover;
        }

.upload-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 10px;
}

.preview-media {
  width: 150px;
  height: 100px;
  object-fit: cover;
  border-radius: 10px;
  box-shadow: 0 0 5px rgba(0,0,0,0.2);
}

    </style>
</head>
<body>
 <img src="images/WhatsApp Image 2025-10-17 at 22.02.46_ff48d86e.jpg"alt="cross logo" class="rotate-icon">
 
    <!-- Sidebar -->
    <div class="navbar">
      <h1>Makuri Fellowship Church</h1>
      <button class="mobile-menu-toggle" id="mobile-menu-toggle">☰</button>
      <ul class="nav-links">
        <li><a href="#" class="active">Dashboard</a></li>
        <li><a href="#">Sermons</a></li>
        <li><a href="#">Fellowships</a></li>
        <li><a href="#">Events</a></li>
        <li><a href="#">Media</a></li>
        <li><a href="dashboard.html">Admin2</a></li>
      </ul>
    
    <!-- Main Content -->
    <div class="content">
        <!-- Top Navbar -->
        <div class="topnav">
            <div>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?> ✅</div>
            <div>Church Admin Dashboard</div>
        </div>

        <!-- Dashboard main area -->
        <div class="main-content">
            <h1>📊 Dashboard Overview</h1>
            <p>This is your admin dashboard. You can manage members, events, donations, and settings from the sidebar.</p>
  <h2>📅 Post Event</h2>
      <form id="eventForm">
        <label>Event Name</label>
        <input type="text" id="eventName" placeholder="Event name" required>
        <label>Date</label>
        <input type="date" id="eventDate" required>
        <label>Event Description</label>
        <textarea id="eventDesc" placeholder="What is this event about?" required></textarea>
        <label>Upload Image/Video (Optional)</label>
        <input type="file" id="eventMedia" accept="image/*,video/*" multiple>
        <div class="upload-preview" id="eventPreview"></div>
        <button type="button" onclick="postEvent()">Post Event <span>📅</span></button>
      </form>
</div>
    <h2>post memories</h2>
     <form id="mediaForm" enctype="multipart/form-data"></form>
        <div class="main-content">
      <form id="mediaForm">
        <label>Title</label>
        <input type="text" id="mediaTitle" placeholder="Enter media title" required>


  <form id="mediaForm" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" id="mediaTitle" name="title" placeholder="Enter media title" required>

    <label>Select Media (Image/Video)</label>
    <input type="file" id="mediaFile" name="files[]" accept="image/*,video/*" multiple required>

    <div id="mediaPreview" class="upload-preview"></div>

    <button type="button" id="uploadBtn">Upload</button>
  </form>
</div>


        <div class="footer">
              <p>&copy; 2024 Makuri Fellowship Church. All rights reserved.</p>
          </div>
      </div> <!-- Close .content -->
  </div> <!-- Close .navbar -->
  <script>
// ✅ Media upload preview + upload

function uploadMedia() {
  const title = document.getElementById('mediaTitle').value.trim();
  const files = document.getElementById('mediaFile').files;

  if (!title || files.length === 0) {
    alert('Please fill all required fields.');
    return;
  }

  const formData = new FormData();
  formData.append('title', title); // lowercase 'title' matches PHP

  for (let i = 0; i < files.length; i++) {
    formData.append('files[]', files[i]); // lowercase 'files[]' matches PHP
  }

  fetch('upload_media.php', {  // ✅ use your actual backend file name (upload.php)
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    console.log(data);
    if (data.status === 'success') {
      alert('✅ Media uploaded successfully!');
      document.getElementById('mediaForm').reset();
      document.getElementById('mediaPreview').innerHTML = '';
    } else {
      alert('❌ Upload failed: ' + data.message);
    }
  })
  .catch(err => {
    console.error(err);
    alert('⚠️ Something went wrong. Try again.');
  });
}

// ✅ Preview selected media files before upload
document.getElementById('mediaFile').addEventListener('change', function() {
  const previewContainer = document.getElementById('mediaPreview');
  previewContainer.innerHTML = '';

  Array.from(this.files).forEach(file => {
    const reader = new FileReader();
    reader.onload = function(e) {
      const isImage = file.type.startsWith('image/');
      const element = document.createElement(isImage ? 'img' : 'video');
      element.src = e.target.result;
      element.classList.add('preview-media');
      if (!isImage) element.controls = true;
      previewContainer.appendChild(element);
    };
    reader.readAsDataURL(file);
  });
});

// ✅ Make sure the button triggers uploadMedia()
document.addEventListener('DOMContentLoaded', () => {
  const uploadBtn = document.getElementById('uploadBtn');
  if (uploadBtn) {
    uploadBtn.addEventListener('click', uploadMedia);
  } else {
    console.error("❌ Upload button not found in DOM.");
  }
});
  </script>
</body>
</html>
  