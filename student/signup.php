<?php
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enroll = trim($_POST['enrollment_no']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // HASHED
    $name = $_POST['full_name'];
    $father = $_POST['father_name'];
    $mother = $_POST['mother_name'];
    $country = $_POST['countryname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $age = intval($_POST['age']);
    $address = $_POST['address'];
    $mobile = $_POST['mobile_no'];
    $pmobile = $_POST['parent_mobile'];
    $course = $_POST['course'];
    $sem = $_POST['semester'];
    $category = $_POST['category'];
    $degree = $_POST['degree'];
    $dept = $_POST['department_name'];

    // File uploads
   // Allowed file types
$allowedImageTypes = ['jpg','jpeg','png'];
$allowedDocTypes   = ['pdf','jpg','jpeg','png']; // for address proof

// Create upload folders if not exist
if (!is_dir("../uploads/address_proof")) mkdir("../uploads/address_proof", 0777, true);
if (!is_dir("../uploads/photos")) mkdir("../uploads/photos", 0777, true);
if (!is_dir("../uploads/signatures")) mkdir("../uploads/signatures", 0777, true);

// Address Proof
$proof = "";
if (!empty($_FILES['address_proof']['name'])) {
    $ext = strtolower(pathinfo($_FILES['address_proof']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowedDocTypes) && $_FILES['address_proof']['size'] <= 2*1024*1024) {
        $proof = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['address_proof']['tmp_name'], "../uploads/address_proof/" . $proof);
    }
}

// Photo
$photo = "";
if (!empty($_FILES['photo']['name'])) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowedImageTypes) && $_FILES['photo']['size'] <= 2*1024*1024) {
        $photo = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/photos/" . $photo);
    }
}

// Signature
$sign = "";
if (!empty($_FILES['signature']['name'])) {
    $ext = strtolower(pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowedImageTypes) && $_FILES['signature']['size'] <= 2*1024*1024) {
        $sign = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['signature']['tmp_name'], "../uploads/signatures/" . $sign);
    }
}

    // ✅ Step 1: Check if enrollment_no OR email OR username already exists
   $check = $conn->prepare("SELECT id FROM students WHERE enrollment_no = ? OR email = ? OR username = ?");
   $check->bind_param("sss", $enroll, $email, $username);
   $check->execute();
   $check->store_result();

   if ($check->num_rows > 0) {
    echo "<script>alert('Enrollment number, Email, or Username already exists. Please try with a different one.'); window.location='signup.php';</script>";
    exit();
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO students 
        (enrollment_no, username, password, full_name, father_name, mother_name, countryname, email, gender, dob, age, address, mobile_no, parent_mobile, course, semester, category, degree, department_name, address_proof, photo, signature, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");

    $stmt->bind_param("ssssssssssisssssssssss",
        $enroll, $username, $password, $name, $father, $mother, $country, $email, $gender, $dob, $age,
        $address, $mobile, $pmobile, $course, $sem, $category, $degree, $dept,
        $proof, $photo, $sign
    );

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Waiting for admin approval.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Hostel Addemmision Form</title>
  <link rel="stylesheet" href="../includes/signup.css">
</head>

<body>
  

  <header>
    <div class="text-box">
      <img src="../img/logo.png" alt="CampusNest" class="top-right-image">
      <h1 id="title" style="font-family: Comic Sans;">CampusNest</h1>
      <hr>
      <p id="description">Welcome to your home away from home</p>
    <div class="navbar">
      <a class="nav" href="../welcome_page.php">Home</a>
      <a class="nav"  href="../includes/about.php">About Us</a>      
      <a class="nav" href="../student/login.php">Login</a></form>
    </div></div>
  </header>

  <form method="POST" enctype="multipart/form-data">
  <div class="container">
    <div id="Addemmision-form" >
      <div class="form">
        <h1 style="color: blanchedalmond;"><u>HOSTEL ADMISSION FORM</u></h1>
        
      </div>
      <fieldset>
        <legend>
          <h2>Personal Information</h2>
        </legend>
        <!--Name Of the User-->
        <div class="labels">
          <label id="name-label" for="name">* Full Name:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="text" id="full_name" name="full_name" placeholder="Enter your name" required autofocus>
        </div>

        <!--Father's Name-->
        <div class="labels">
          <label id="fname-labels" for="Father name">* Father Name:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="text" id="father_name" name="father_name" placeholder="Enter your Father name"
            required autofocus>
        </div>

        <!--Mother's Name-->
        <div class="labels">
          <label id="Mname-labels" for="Mother name">* Mother Name:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="text" id="mother_name" name="mother_name" placeholder="Enter your Mother name"
            required autofocus>
        </div>

        <!--Address-->
        <div class="labels">
          <label id="Address-label" for="Addressname">* Address:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="text" id="address" name="address"
            placeholder="Enter your Address with pincode" required autofocus>
        </div>
        <div class="labels">
          <label id="Country-label" for="Countryname">* Country:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="text" id="countryname" name="countryname">
        </div>
         <div class="labels">
          <label>Upload Address Proof:</label>
         </div>
        <div class="input-tab">
         
            <input type="file" name="address_proof" required>
        </div>
        <!--Email-ID-->
        <div class="labels">
          <label id="email-label" for="email">* Email:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="email" id="email" name="email" placeholder="email@email.com" required>
        </div>

        <!--Mobile Number-->
        <div class="labels">
          <label id="mob-label" for="mobile">* Mob.Number:</label>
        </div>
        <div class="input-tab">
          <input class="countrycode" type="text" name="country code" placeholder="Country Code" value="+91"
            size="5" /><i style="font-size:15px;"> * Country code</i>
          <input class="input-field" type="tel" id="mobile_no" name="mobile_no" pattern="[0-9]{10}"
            placeholder="Mobile Number" required>
        </div>
        <div class="labels">
          <label id="mob-label" for="mobile">*  Parent Mob.Number:</label>
        </div>
        <div class="input-tab">
          <input class="countrycode" type="text" name="country code" placeholder="Country Code" value="+91"
            size="5" /><i style="font-size:15px;"> * Country code</i>
          <input class="input-field" type="tel" id="parent_mobile" name="parent_mobile" pattern="[0-9]{10}"
            placeholder=" Parent Mobile Number" required>
        </div>

        <!--Age-->
        <div class="labels">
          <label id="number-label" for="number">* Age:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="number" id="age" name="age" min="1" max="120" placeholder="15"
            required>
        </div>

        <!--Date of Birth-->
        <div class="labels">
          <label id="dob-label" for="DObnumber">* Date of Birth: </label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="date" id="dob" name="dob">
        </div>

        <!--Gender-->
        <div class="labels">
          <label for="dropdown">* Gender:</label>
        </div>
        <div class="input-tab">
          <select id="dropbox" name="gender">
            <option disabled value selected>Select an option</option>
            <option value="male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other+</option>
          </select>
        </div>

        <!--User Category-->
        <div class="labels">
          <label for="dropdown">* Category:</label>
        </div>
        <div class="input-tab">
          <select id="dropbox" name="category">
            <option disabled value selected>Select an Category</option>
            <option value="sc/st">ST</option>
            <option value="sc/st">SC</option>
            <option value="obc">OBC</option>
            <option value="GEN">GENERAL</option>
          </select>
        </div>
      </fieldset>

      <fieldset>
        <legend>
          <h2>Academic Information</h2>
        </legend>

       <!-- Reasion of booking a Hostel -->
<div class="labels">
  <label for="reasonSelect">* Select the category You Belong</label>
</div>
<div class="input-tab" >
  <select id="dropbox" name="degree"  required>
    <option value="">Select</option>
    <option value="UnderGraduation">Under Graduation </option>
    <option value="PostGraduation">Post Graduation</option>
    <option value="PhD Scholar">PhD Scholar</option>
  </select>
</div>

   <div class="labels">
          <label id="Enroll-labels" for="Enrollment No">* Enrollment Number:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="text" id="EnrollNo" name="enrollment_no" placeholder="Enter your enrollment No."
            required autofocus>
</div>

        <div class="labels">
          <label id="Department-labels" for="Department name">* Department Name:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="text" id="department_name" name="department_name" placeholder="Department Name"
            required autofocus>
</div>

        <div class="labels">
          <label id="Course-labels" for="course name">* Course Name:</label>
        </div>
        <div class="input-tab">
          <input class="input-field" type="text" id="course" name="course" placeholder="Cource Name"
            required autofocus>
</div>

        <div class="labels">
          <label id="semester-labels" for="semester name">* semester:</label>
        </div>
       <div class="input-tab">
          <input class="input-field" type="text" id="semester" name="semester" placeholder="semester"
            required autofocus>
</div>
      </fieldset>

       <fieldset>
      <legend>
          <h2>Account Informaton</h2>
        </legend>
      
        <div class="labels">
          <label id="Course-labels" for="course name">* Username:</label>
        </div>
      <div class="input-tab" style="width: 20%;" >
          <input type="text" class="input-field" name="username" placeholder="Username" required>
           
</div>

           <div class="labels">
          <label id="Course-labels" for="course name">* Password:</label>
        </div>
        <div class="input-tab" style="width: 20%;">
         <input type="password" class="input-field"  name="password" placeholder="Password" required>
        </div>
     </fieldset>
    </div>

  </div>

  <!--Photo,signature and Declaration-->
  <div id="myForm">
  <div class="thirdbox">

    <fieldset style="border-top:2px solid #ff6969;">
      <legend style="font-size:23px; font-family:monospace; color:#000284;"> Decelaration</legend>
      <div class="image">
        <div class="form-group">
          <img id="passportPreview" class="preview" alt="Passport Photo Preview">
          <label for="passportPhoto">Passport Photo:</label>
          <input type="file" id="Photo" name="photo" accept="image/*" onchange="previewImage(event, 'passportPreview')"
            required>
        </div>

        <div class="form-group">
          <img id="signaturePreview" class="preview" alt="Signature Photo Preview">
          <label for="signaturePhoto">Signature Photo:</label>
          <input type="file" id="signature" name="signature" accept="image/*" onchange="previewImage(event, 'signaturePreview')"
            required>
        </div>
      </div>
      <div class="note">
        <p style="font-family:Berlin Sans FB;font-size:10px;">
        <h3><i>Note:-</i></h3><input type="checkbox" name="conf" value="" required> Please ensure that all the
        information provided in this form is accurate and complete. Incomplete or inaccurate information may result in
        the rejection of your application.
        All submitted documents will be verified, and any discrepancies found may lead to disqualification.
        By submitting this form, you agree to abide by the Hostel's rules and regulations.</p>
        <input type="checkbox" name="conf" value="" required> I accept the <a href="">term and condition</a> of CampusNest Hostel.</p>
      </div>

      <div class="btn">
        <button id="submit" type="submit">Submit</button>
      </div>
  </div>
  </form>
  
  
  <!-- Custom Alert Box -->
    <div id="customAlert" class="custom-alert">
        <p>Form has been submitted successfully! Plese Wait For The Verification </p>
        <button onclick="closeAlert()">OK</button>
    </div>
    
    <!-- Overlay -->
    <div id="alertOverlay" class="custom-alert-overlay"></div>


  <footer class="Copyright" style="text-align: center;">
    <span style="background-color:#ffffffcf; color: black; width: 100px; font-family:'Courier New', Courier, monospace;"> © 2025-26 CampusNest Hostel. All rights reserved.</span>
    
  </footer>

  <!--Javascript use for photo and signatuer preview-->

  <script>
    function previewImage(event, previewId) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onload = function () {
        document.getElementById(previewId).src = reader.result;
      }
      reader.readAsDataURL(file);
    }
	
	  // Add event listener for form submission
        document.getElementById("myForm").addEventListener("submit", function(event) {
            event.preventDefault();  // Prevents the form from submitting in the default way
            showAlert();
        });

        // Function to show the custom alert box
        function showAlert() {
            document.getElementById("customAlert").style.display = 'block';
            document.getElementById("alertOverlay").style.display = 'block';
        }

        // Function to close the custom alert box
        function closeAlert() {
            document.getElementById("customAlert").style.display = 'none';
            document.getElementById("alertOverlay").style.display = 'none';
        }
  </script>
</body>

</html>
