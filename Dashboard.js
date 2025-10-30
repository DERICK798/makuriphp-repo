fetch("getAdmin.php")
    .then(res => res.json())
    .then(data => {
        if (data.loggedIn && data.role === "admin") {
            document.getElementById("welcome").innerText =
                "Welcome Admin, " + data.username + " 👋";
        } else {
            window.location.href = "login.php"; // send them back if not admin
        }
    });
let members = [
  { name: "John Doe", phone: "0712345678", status: "active" },
  { name: "Mary Smith", phone: "0798765432", status: "inactive" },
  { name: "marvin somi", phone: "0723456789", status: "active" },
];

// Render table
function renderTable() {
  const tbody = document.querySelector("#membersTable tbody");
  const searchValue = document.getElementById("searchInput").value.toLowerCase();
  const filterValue = document.getElementById("statusFilter").value;

  tbody.innerHTML = "";

  members.forEach((member, index) => {
    // Search filter
    if (
      !member.name.toLowerCase().includes(searchValue) &&
      !member.phone.includes(searchValue)
    ) {
      return;
    }

    // Status filter
    if (filterValue !== "all" && member.status !== filterValue) {
      return;
    }

    // Create row
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${member.name}</td>
      <td>${member.phone}</td>
      <td>${member.status}</td>
      <td>
        <button onclick="deleteMember(${index})">Delete</button>
      </td>
    `;
    tbody.appendChild(row);
  });
}

// Add new member
document.getElementById("add_member-form").addEventListener("submit", function(e) {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
  const phone = document.getElementById("phone").value.trim();
  const status = document.getElementById("status").value;

  if (!name || !phone) {
    alert("Please fill all fields");
    return;
  }

  members.push({ name, phone, status });

  // Clear form
  document.getElementById("name").value = "";
  document.getElementById("phone").value = "";
  document.getElementById("status").value = "active";

  renderTable();
});

// Delete member
function deleteMember(index) {
  if (confirm("Are you sure you want to delete this member?")) {
    members.splice(index, 1);
    renderTable();
  }
}

// Search and filter listeners
document.getElementById("searchInput").addEventListener("input", renderTable);
document.getElementById("statusFilter").addEventListener("change", renderTable);

// Initial render
renderTable();
document.getElementById("searchBtn").addEventListener("click", function() {
    let query = document.getElementById("searchInput").value.toLowerCase();
    alert("Searching for: " + query);

    // Later you can filter members list here
});
// Preview uploaded image/video before posting
document.getElementById('eventMedia').addEventListener('change', function() {
  const preview = document.getElementById('eventPreview');
  preview.innerHTML = ''; // Clear old preview
  const files = this.files;

  Array.from(files).forEach(file => {
    const reader = new FileReader();
    reader.onload = function(e) {
      const mediaElement = document.createElement(file.type.startsWith('video') ? 'video' : 'img');
      mediaElement.src = e.target.result;
      mediaElement.className = 'preview-media';
      if (file.type.startsWith('video')) {
        mediaElement.controls = true;
      }
      preview.appendChild(mediaElement);
    };
    reader.readAsDataURL(file);
  });
});

// Function to send post to backend
function postEvent() {
  const name = document.getElementById('eventName').value.trim();
  const date = document.getElementById('eventDate').value;
  const desc = document.getElementById('eventDesc').value.trim();
  const media = document.getElementById('eventMedia').files;

  if (!name || !date || !desc) {
    alert('Please fill in all required fields.');
    return;
  }

  const formData = new FormData();
  formData.append('eventName', name);
  formData.append('eventDate', date);
  formData.append('eventDesc', desc);

  for (let i = 0; i < media.length; i++) {
    formData.append('eventMedia[]', media[i]);
  }

  fetch('post_event.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      alert('✅ Event posted successfully!');
      document.getElementById('eventForm').reset();
      document.getElementById('eventPreview').innerHTML = '';
    } else {
      alert('❌ Failed to post event: ' + data.message);
    }
  })
  .catch(err => {
    console.error(err);
    alert('⚠️ Something went wrong. Try again.');
  });
}
// Handle preview before upload Gallery media
document.getElementById('mediaFile').addEventListener('change', function () {
  const preview = document.getElementById('mediaPreview');
  preview.innerHTML = '';
  const files = this.files;

  Array.from(files).forEach(file => {
    const reader = new FileReader();
    reader.onload = function (e) {
      const element = document.createElement(file.type.startsWith('video') ? 'video' : 'img');
      element.src = e.target.result;
      element.className = 'preview-media';
      if (file.type.startsWith('video')) element.controls = true;
      preview.appendChild(element);
    };
    reader.readAsDataURL(file);
  });
});

// Upload to backend
function uploadMedia() {
  const title = document.getElementById('mediaTitle').value.trim();
  const files = document.getElementById('mediaFile').files;

  if (!title || files.length === 0) {
    alert('Please fill all required fields.');
    return;
  }

  const formData = new FormData();
  formData.append('mediaTitle', title);
  for (let i = 0; i < files.length; i++) {
    formData.append('mediaFile[]', files[i]);
  }

  fetch('upload_media.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
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



