<?php include("includes/db.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vignan Mahotsav | Registration</title>
<style>
  * { box-sizing: border-box; font-family: "Poppins", sans-serif; }
  body {
    margin: 0;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    color: #fff;
  }
  .container {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    padding: 35px 40px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    width: 480px;
    max-height: 95vh;
    overflow-y: auto;
  }
  h2 {
    text-align: center;
    color: #ffe66d;
    margin-bottom: 25px;
  }
  label { display: block; margin-top: 12px; font-weight: 500; }
  input, select {
    width: 100%; padding: 10px; border-radius: 8px;
    border: none; outline: none; margin-top: 5px;
    background: rgba(255,255,255,0.9); color: #000;
  }
  .gender-group {
    display: flex; gap: 15px; margin-top: 5px;
  }
  .gender-group label { display: flex; align-items: center; gap: 5px; }
  button {
    width: 100%; padding: 12px;
    background: #ffe66d; color: #000; font-weight: 600;
    border: none; border-radius: 8px;
    margin-top: 25px; cursor: pointer; transition: 0.3s;
  }
  button:hover {
    background: #fff; color: #203a43; transform: scale(1.03);
  }
  .login-link { text-align: center; margin-top: 15px; }
  .login-link a { color: #ffe66d; text-decoration: none; }
  .login-link a:hover { text-decoration: underline; }
</style>
</head>

<?php
if (isset($_POST['submit'])) {
  $regno = trim($_POST['regno']);
  $cell = trim($_POST['cellno']);
  $email = trim($_POST['email']);

  // Check for duplicates
  $check = $conn->prepare("SELECT * FROM students WHERE regno=? OR cellno=? OR email=?");
  $check->bind_param("sss", $regno, $cell, $email);
  $check->execute();
  $res = $check->get_result();

  if ($res->num_rows > 0) {
    echo "<script>alert('⚠️ Registration failed: Reg No, Cell No, or Email already exists!');</script>";
  } else {
    // ✅ Generate unique MH ID
    $prefix = "MH";
    $year = date("Y");

    $result = $conn->query("SELECT mh_id FROM students ORDER BY id DESC LIMIT 1");
    $lastID = $result->fetch_assoc();

    if ($lastID && preg_match('/MH\d{4}(\d+)/', $lastID['mh_id'], $match)) {
      $next = intval($match[1]) + 1;
    } else {
      $next = 1;
    }

    $mh_id = $prefix . $year . str_pad($next, 3, "0", STR_PAD_LEFT);

    // Handle "Other" college option
    $college = ($_POST['college'] === 'Other') ? $_POST['other_college'] : $_POST['college'];

    // Insert record with MH ID
    $stmt = $conn->prepare("INSERT INTO students 
      (mh_id, regno, name, dob, cellno, email, gender, branch, state, district, college)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss",
      $mh_id, $_POST['regno'], $_POST['name'], $_POST['dob'], $_POST['cellno'],
      $_POST['email'], $_POST['gender'], $_POST['branch'],
      $_POST['state'], $_POST['district'], $college
    );

    if ($stmt->execute()) {
      // ✅ Alert + Copy to Clipboard + Redirect
      echo "<script>
              const mhid = '$mh_id';
              navigator.clipboard.writeText(mhid)
                .then(() => {
                  alert('🎉 Registration Successful!\\nYour Mahotsav ID: ' + mhid + '\\n(Mahid copied to clipboard!)');
                  window.location.href = 'login.php';
                })
                .catch(() => {
                  alert('🎉 Registration Successful!\\nYour Mahotsav ID: ' + mhid + '\\n(Copy failed, please copy manually.)');
                  window.location.href = 'login.php';
                });
            </script>";
    } else {
      echo "<script>alert('❌ Error occurred while saving data.');</script>";
    }
  }
}
?>



<body>

<div class="container">
  <h2>Student Registration</h2>

  <form method="POST" action="">
    <label>Reg No:</label>
    <input type="text" name="regno" required>

    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Date of Birth:</label>
    <input type="date" name="dob" required>

    <label>Cell No:</label>
    <input type="text" name="cellno" pattern="[0-9]{10}" maxlength="10" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Gender:</label>
    <div class="gender-group">
      <label><input type="radio" name="gender" value="Male" required> Male</label>
      <label><input type="radio" name="gender" value="Female" required> Female</label>
    </div>

    <label>Branch:</label>
    <select name="branch" required>
      <option value="">-- Select Branch --</option>
      <option value="CSE">CSE</option>
      <option value="ACSE-DS">ACSE-DS</option>
      <option value="ACSE-AIML">ACSE-AIML</option>
      <option value="ECE">ECE</option>
      <option value="EEE">EEE</option>
      <option value="MECH">MECH</option>
    </select>

    <label>State:</label>
    <select id="state" name="state" required>
      <option value="">-- Select State --</option>
      <option value="Andhra Pradesh">Andhra Pradesh</option>
      <option value="Telangana">Telangana</option>
      <option value="Tamil Nadu">Tamil Nadu</option>
      <option value="Karnataka">Karnataka</option>
      <option value="Kerala">Kerala</option>
    </select>

    <label>District:</label>
    <select id="district" name="district" required>
      <option value="">-- Select District --</option>
    </select>

    <label>College:</label>
    <select id="college" name="college" required>
      <option value="">-- Select College --</option>
    </select>

    <!-- Hidden text box for 'Other' college name -->
    <div id="otherCollegeContainer" style="display: none; margin-top: 10px;">
      <label for="otherCollege">Enter Other College Name:</label>
      <input type="text" id="otherCollege" name="other_college" placeholder="Enter college name" />
    </div>

    <button type="submit" name="submit">Register</button>

    <div class="login-link">
      <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </form>
</div>

</body>
<script>
// ✅ Data for States → Districts → Colleges
const locationData = {
  "Andhra Pradesh": {
    "Guntur": ["Vignan University", "RVR & JC College", "KL University"],
    "Krishna": ["VIT-AP University", "Andhra Loyola College", "PB Siddhartha College"],
    "Nellore": ["Narayana Engineering College", "Audisankara College"],
    "Vizag": ["GITAM University", "Andhra University"],
    "Prakasam": ["QIS College of Engineering", "Rise Krishna Sai Group"]
  },
  "Telangana": {
    "Hyderabad": ["JNTU Hyderabad", "Osmania University", "CBIT"],
    "Nizamabad": ["Vijay Rural Engineering College", "Kshatriya College"],
    "Karimnagar": ["SR Engineering College", "Trinity College of Engineering"],
    "Warangal": ["Kakatiya University", "Vaagdevi Engineering College"],
    "Khammam": ["Swarna Bharathi College", "Anurag Engineering College"]
  },
  "Tamil Nadu": {
    "Chennai": ["Anna University", "SRM Institute of Science and Technology"],
    "Coimbatore": ["PSG College of Technology", "Kumaraguru College of Technology"],
    "Madurai": ["Thiagarajar College of Engineering"],
    "Salem": ["Sona College of Technology"]
  },
  "Karnataka": {
    "Bengaluru": ["RV College of Engineering", "BMS College of Engineering"],
    "Mysuru": ["SJCE", "Vidya Vikas Institute of Engineering"],
    "Mangaluru": ["NITK Surathkal", "St. Joseph Engineering College"],
    "Hubli": ["KLE Technological University"]
  },
  "Kerala": {
    "Thiruvananthapuram": ["College of Engineering Trivandrum"],
    "Kochi": ["Cochin University of Science and Technology"],
    "Kozhikode": ["NIT Calicut"],
    "Palakkad": ["Government Engineering College Palakkad"]
  }
};

// ✅ DOM Elements
const stateSelect = document.getElementById("state");
const districtSelect = document.getElementById("district");
const collegeSelect = document.getElementById("college");

// ✅ State → District
stateSelect.addEventListener("change", function () {
  const selectedState = this.value;
  districtSelect.innerHTML = '<option value="">-- Select District --</option>';
  collegeSelect.innerHTML = '<option value=\"\">-- Select College --</option>';

  if (locationData[selectedState]) {
    Object.keys(locationData[selectedState]).forEach(function(district) {
      const option = document.createElement("option");
      option.value = district;
      option.textContent = district;
      districtSelect.appendChild(option);
    });
  }
});

// ✅ District → College
districtSelect.addEventListener("change", function () {
  const selectedState = stateSelect.value;
  const selectedDistrict = this.value;
  collegeSelect.innerHTML = '<option value="">-- Select College --</option>';

  if (locationData[selectedState] && locationData[selectedState][selectedDistrict]) {
    locationData[selectedState][selectedDistrict].forEach(function(college) {
      const option = document.createElement("option");
      option.value = college;
      option.textContent = college;
      collegeSelect.appendChild(option);
    });
  }

  // ✅ Add "Other" option at the end
  const otherOption = document.createElement("option");
  otherOption.value = "Other";
  otherOption.textContent = "Other";
  collegeSelect.appendChild(otherOption);
});

// ✅ Show/Hide "Other College" field
collegeSelect.addEventListener("change", function () {
  const otherBox = document.getElementById("otherCollegeContainer");
  if (this.value === "Other") {
    otherBox.style.display = "block";
    document.getElementById("otherCollege").required = true;
  } else {
    otherBox.style.display = "none";
    document.getElementById("otherCollege").required = false;
  }
});
</script>
</html>
