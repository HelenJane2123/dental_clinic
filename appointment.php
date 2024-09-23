<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: login.php'); // Redirect to login page if not logged in
        exit();
    }
    include_once('inc/header.php');
?>
<body>
    <header>
        <div class = "navigation">
        <nav class="menu ">
            <a href="#"> HOME </a>
            <a href="#"> ABOUT </a> 
            <a href="#"> SERVICES </a>
            <a href="#"> CONTACTS </a>
            <button class="btn_Logout" onclick="window.location.href ='logout.php'">LOGOUT <i class="fa-duotone fa-solid fa-circle-user fa-lg"></i></button>
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
        <div class="title_form">
            <p>Patient Information Record</p>
        </div>  
    <form action="#" method="post">
        <p>First Name: </p>
        <input type="text" name="firstname" id="FirstName" placeholder="First Name" required>
        <p>Middle Name: </p>
        <input type="text" name="middlename" id="LastName" placeholder="Middle Name" required>
        <p>Last Name: </p>
        <input type="text" name="lastname" id="MiddleName" placeholder="Last Name" required>
        <p>Birthday: </p>
        <input type="date" name="birthday" id="Birthday" required>
        <p>Age: </p>
        <input type="number" name="age" id="Age" required>
        <p>Sex: </p>
        <p>Female</p>
        <input type="radio" name="sex" id="Female">
        <p>Male</p>
        <input type="radio" name="sex" id="Male">
        <p>Nickname</p>
        <input type="text" name="nickname" id="Nickname">
        <p>Religion: </p>
        <input type="text" name="religion" id="Religion">
        <p>Nationality: </p>
        <input type="text" name="nationality" id="Nationality ">
        <p>Phone Number: </p>
        <input type="number" name="phonenum" id="PhoneNum" maxlength="11" required>
        <p>Home Address: </p>
        <input type="text" name ="address" id="Address" required>
        <p>Email: </p>
        <input type="email" name="email" id="Email" placeholder="example@gmail.com">
        <p>Occupation: </p>
        <input type="text" name="occupation" id="Occupation">


    </form>


    </div>

     <!-- Footer -->
     <?php include_once('inc/footer.php'); ?>
    
</body>
</html>