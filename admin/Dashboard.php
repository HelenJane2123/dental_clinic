<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="navigation">
        <nav class="search">
            <input type="text" placeholder="Search...">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </nav>
    </div>

    <div class="sidebar">
        <div class="Name">
            <h1>Dental Clinic</h1>
        </div>
        <ul>
            <li><i class='bx bxs-dashboard'></i> Dashboard</li>
            <li><i class='bx bxs-calendar'></i> Appointment</li>
            <li><i class='bx bxs-bell'></i> Notification</li>
            <li><i class='bx bxs-book'></i> Patient Record</li>
            <li><i class='bx bxs-add-to-queue'></i> Add Doctors</li>
        </ul>
    </div>

<section class="patient-record">
        <h2>Patient Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Contact No./Email</th>
                    <th>Medical Record</th>
                    <th>Update Record</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rosales</td>
                    <td>Mary Anne</td>
                    <td>09456689889<br>maryjane@gmail.com</td>
                    <td><a href="#">View Detail >></a></td>
                    <td><button class="update-btn">Update</button></td>
                </tr>
                <tr>
                    <td>Dela Cruz</td>
                    <td>John Andrew</td>
                    <td>09884563312<br>johnandrew@gmail.com</td>
                    <td><a href="#">View Detail >></a></td>
                    <td><button class="update-btn">Update</button></td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="todo-container">
        <h2>TODO:</h2>
        <ul class="todo-list">
            <input type="checkbox"> Check List
            <li><input type="checkbox"> Check List</li>
            <li><input type="checkbox"> Check List</li>
            <li><input type="checkbox"> Check List</li>
            <li><input type="checkbox"> Check List</li>
            <li><input type="checkbox"> Check List</li>
        </ul>
        
        <div class="appointments">
            <h3>Appointments</h3>
            <ul>
                <li>
                    <span class="patient-name">Mary Anne Rosales</span><br>
                    Set appointment with you @10:00am tomorrow
                </li>
                <li>
                    <span class="patient-name">John Andrew Dela Cruz</span><br>
                    Set appointment with you @10:00am tomorrow
                </li>
                <li>
                    <span class="patient-name">Mikha Buenafe</span><br>
                    Set appointment with you @11:00am on Monday
                </li>
            </ul>
            <a href="#" class="see-all">See all</a>
        </div>
    </section>

    <section class="patient-stats">
        <div class="stat-card">
            <p>Patients today</p>
            <h2>8</h2>
            <p class="stat-info"><span class="increase">+10%</span> than yesterday</p>
        </div>
        <div class="stat-card">
            <p>Total patients</p>
            <h2>254</h2>
            <p class="stat-info"><span class="increase">+15%</span> than last month</p>
        </div>
    </section>
    <footer>
        <div class="footer-bottom">
            <p>copyright &copy; 2024 Capstone's Purpose</p>
        </div>
    </footer>
</body>
</html>
