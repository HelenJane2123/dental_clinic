<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Appoinment_style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Appoinment</title>
</head>
<body>
    <header>
        <div class = "navigation">
        <nav class="menu ">
            <a href="#"> HOME </a>
            <a href="#"> ABOUT </a> 
            <a href="#"> SERVICES </a>
            <a href="#"> CONTACTS </a>
            <button class =  "btn_Logout" onclick="window.location.href = 'Login.html'">  LOGOUT <i class="fa-duotone fa-solid fa-circle-user fa-lg"></i></button>
  
        </nav>
    </div>
    </header>
    
    <div class ="sidebar">
            <h1>MENU</h1>
            <li> <a href="#"><i class="fa-solid fa-calendar-days fa-lg"></i>  Calendar</a></li>
            <li> <a href="#"><i class="fa-regular fa-calendar-check fa-lg"></i>  Appoinment</a></li>
            <li> <a href="#"><i class="fa-solid fa-money-bill fa-lg"></i>  Payment Method </a></li>
    </div>

    <div class="appoinment_form">
        <button class="btnNewPatient"> New Patient</button>
        <button class="btnOldPatient"onclick="window.location.href = 'Appointment_Old.html'"> Old Patient</button>
        <div class="title-form">
            <p>Patient Information Record</p>
        </div>  
        <form action="#" class="form">
        
        <div class="input-box">
            <label>First Name: </label>
            <input type="text" name="firstname" id="FirstName" placeholder="First Name" required>
            <label>Middle Name: </label>
            <input type="text" name="middlename" id="MiddleName" placeholder="Middle Name" required>
            <label>Last Name: </label>
            <input type="text" name="lastname" id="Last Name" placeholder="Last Name" required>
            <label>Nickname: </label>
            <input type="text" name="nickname" id="Nickname" placeholder="Nickname">
        </div>
        <div class="input-box">
            <label>Birthday: </label>
            <input type="date" name="birthday" id="Birthday" required>
            <label>Age: </label>
            <input type="number" name="age" id="Age" required>
        </div>
        <div class="radio-button">
            <label>Sex: </label>
            <input type="radio" name="sex">
            <label>Female</label>
            <input type="radio" name="sex">
            <label>Male</label> 
        </div>    
       <div class="input-box">
            <label>Religion: </label>
            <input type="text" name="religion" id="Religion">
            <label>Nationality: </label>
            <input type="text" name="nationality" id="Nationality ">
            <label>Phone Number: </label>
            <input type="number" name="phonenum" id="PhoneNum" maxlength="11" required>
        </div>
        <div class="input-box">
            <label>Home Address: </label>
            <input type="text" name ="address" id="Address" required>
        </div>
        <div class="input-box">
            <label>Email: </label>
            <input type="email" name="email" id="Email" placeholder="example@gmail.com">
            <label>Occupation: </label>
            <input type="text" name="occupation" id="Occupation">
        </div>
        <div class="text-title">
            <p>*For Minor</p>
        </div>
        <div class="input-box">
            <label>Parent/Guardia's Name: </label>
            <input type="text" name="parentname" id="Parent Name" required>
            <label>Occupation: </label>
            <input type="text" name="occupation" id="Occupation">
        </div>
        <div class="input-box">
            <label> Whom may we thank for referring you? </label>
            <input type="text" name="referrer">
        </div>
        <div class="input-box">
            <label> What is your reason for dental consultation? </label>
            <input type="text" name="consultation">
        </div>
        <div class="text-title">
            <p>DENTAL HISTORY</p>
        </div>
        <div class="input-box">
            <label> Previous Dentist Dr. </label>
            <input type="text" name="doctor">
            <label> Last Dental visit: </label>
            <input type="date" name="dental">
        </div>
        <div class="text-title">
            <p>MEDICAL HISTORY</p>
        </div>
       <div class="input-box">
            <label>Name of Physician: Dr. </label>
            <input type="text" name="physician" id="Physician"> 
            <label>Specialty if applicable: </label>
            <input type="text" name="specialty" id="Specialty">
       </div>
       <div class="input-box">
            <label>Office Address: </label>
            <input type="text" name="offaddress">
            <label>Office Number: </label>
            <input type="text" name="offnum">
       </div>
       <div class = "radio-button">
            <label>1. Are you in good health? </label>
            <input type="radio" name="yes_no" id="Yes">
            <label>Yes</label>
            <input type="radio" name="yes_no" id="No">
            <label>No</label>
       </div>
       <div class="radio-button">
            <label>2. Are you under medical treatment now? </label>
            <input type="radio" name="yes_no1">
            <label>Yes</label>
            <input type="radio" name="yes_no1"> 
            <label>No</label>
            <label>If so, what is the condition being treated? </label>
            <input type="text" name="condition">
       </div>
       <div class="radio-button">
            <label>3. Have you ever had serious illness or surgical operation? </label>
            <input type="radio" name="yes_no2">
            <label>Yes</label>
            <input type="radio" name="yes_no2"> 
            <label>No</label>
            <label>If so, what illness or operation? </label>
            <input type="text" name="condition">
       </div>
       <div class="radio-button">
            <label>4. Have you ever been hospitalized? </label>
            <input type="radio" name="yes_no3">
            <label>Yes</label>
            <input type="radio" name="yes_no3"> 
            <label>No</label>
            <label>If so, when and why? </label>
            <input type="text" name="condition">
        </div>
        <div class = "radio-button">
            <label>5. Are you taking any prescription/non prescription medication </label>
            <input type="radio" name="yes_no4" id="Yes">
            <label>Yes</label>
            <input type="radio" name="yes_no4" id="No">
            <label>No</label>
        </div>
        <div class="radio-button">
            <label>6. Do you use tobacco products? </label>
            <input type="radio" name="yes_no5" id="Yes">
            <label>Yes</label>
            <input type="radio" name="yes_no5" id="No">
            <label>No</label>
        </div>
        <div class="radio-button">
            <label>7. Do you use alcohol,cocaine or other dangerous drugs? </label>
            <input type="radio" name="yes_no6" id="Yes">
            <label>Yes</label>
            <input type="radio" name="yes_no6" id="No">
            <label>No</label>
        </div>
        <div class="check-box">
            <label>8. Are you allergic to any of the following: </label> <br>
            <input type="checkbox" name="checkbox0">
            <label>Local Anesthetic (ex.Lidocaine)</label>
            <input type="checkbox" name="checkbox1">
            <label>Penicillin</label>
            <input type="checkbox" name="checkbox2">
            <label>Antibiotics</label> <br>
            <input type="checkbox" name="checkbox3">
            <label>Sulfa drugs</label>
            <input type="checkbox" name="checkbox4">
            <label>Aspirin</label>
            <input type="checkbox" name="checkbox5">
            <label>Latex</label>
            <input type="checkbox" name="checkbox6">
            <label>Others</label>
            <input type="text" name="Others">
       </div>
       <div class="input-box">
            <label>9. Bledding Time </label>
            <input type="text" name="bleedtime"> 
        </div>
        <div class="radio-button">
            <h4>10. For women only:</h4> 
            <label>Are you pregnant?</label>
            <input type="radio" name="yes_no7" id="Yes">
            <label>Yes</label>
            <input type="radio" name="yes_no7" id="No">
            <label>No</label> <br>
            <label>Are you nursing?</label>
            <input type="radio" name="yes_no8" id="Yes">
            <label>Yes</label>
            <input type="radio" name="yes_no8" id="No">
            <label>No</label> <br>
            <label>Are you taking birth control pills?</label>
            <input type="radio" name="yes_no9" id="Yes">
            <label>Yes</label>
            <input type="radio" name="yes_no9" id="No">
            <label>No</label>
        </div>
        <div class="input-box">
            <label>11. Blood Type </label>
            <input type="text" name="bloodtype"> 
        </div>
        <div class="input-box">
            <label>12. Blood Pressure </label>
            <input type="text" name="bloodpressure"> 
        </div>
        <div class="check-box">
            <label>13. Do you have or have you had any of the following? Check with apply </label> <br>
            <input type="checkbox" name="checkbox7">
            <label>High Blood Pressure</label>
            <input type="checkbox" name="checkbox8">
            <label>Heart Diseases</label>
            <input type="checkbox" name="checkbox9">
            <label>Cancer/Tumors</label> 
            <input type="checkbox" name="checkbox10">
            <label>Low Blood Pressure</label> <br>
            <input type="checkbox" name="checkbox11">
            <label>Heart Murmur</label>
            <input type="checkbox" name="checkbox12">
            <label>Anemia</label>
            <input type="checkbox" name="checkbox13">
            <label>Epilepsy/Convulsions</label>
            <input type="checkbox" name="checkbox14">
            <label>Hepatitis/Liver Disease</label> <br>
            <input type="checkbox" name="checkbox15">
            <label>Angina</label>
            <input type="checkbox" name="checkbox16">
            <label>AIDS or HIV Infection</label>
            <input type="checkbox" name="checkbox17">
            <label>Rheumatic Fever</label>
            <input type="checkbox" name="checkbox18">
            <label>Stomach Troubles/Ulcers</label> <br>
            <input type="checkbox" name="checkbox19">
            <label>Hay Fever/Allergies</label>
            <input type="checkbox" name="checkbox20">
            <label>Sexually Transmitted Disease</label>
            <input type="checkbox" name="checkbox21">
            <label>Emphysema</label>
            <input type="checkbox" name="checkbox22">
            <label>Joint Replacement/Implant</label> <br>
            <input type="checkbox" name="checkbox23">
            <label>Fainting Seizure</label>
            <input type="checkbox" name="checkbox24">
            <label>Rapid Weight Loss</label>
            <input type="checkbox" name="checkbox25">
            <label>Radiation Theraphy</label>
            <input type="checkbox" name="checkbox26">
            <label>Respiratory Problems</label> <br>
            <input type="checkbox" name="checkbox27">
            <label>Heart Surgery</label>
            <input type="checkbox" name="checkbox28">
            <label>Heart Attack</label>
            <input type="checkbox" name="checkbox29">
            <label>Thyroid Problem</label>
            <input type="checkbox" name="checkbox31">
            <label>Hepatitis/Jaundice</label> <br>
            <input type="checkbox" name="checkbox32">
            <label>Tuberculosis</label>
            <input type="checkbox" name="checkbox33">
            <label>Swollen ankles</label>
            <input type="checkbox" name="checkbox34">
            <label>Diabetes</label>
            <input type="checkbox" name="checkbox35">
            <label>Chestpain</label> <br>
            <input type="checkbox" name="checkbox36">
            <label>Stroke</label> 
            <input type="checkbox" name="checkbox37">
            <label>Blood Disease</label>
            <input type="checkbox" name="checkbox38">
            <label>Head Injuries</label>
            <input type="checkbox" name="checkbox39">
            <label>Arthritis/Rheumatism</label> <br>
            <input type="checkbox" name="checkbox40">
            <label>Kidney Disease</label>
            <input type="checkbox" name="checkbox41">
            <label>Bleeding Problems</label>
            <input type="checkbox" name="checkbox42">
            <label>Asthma</label> 
            <input type="checkbox" name="checkbox30">
            <label>Others</label>
            <input type="text" name="Others">
       </div>
        
 


    </form>
    </div>

    <footer>
        <div class="footer-bottom">
            <p>copyright &copy; 2024 capstones purpose</p>
        </div>

    </footer>
    
</body>
</html>