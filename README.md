# dental_clinic
Dental Clinic System

Process:

PATIENT SIDE:
    1. Register/Signup
        Validate Password Regular Expression ($password_regex):
            - The password must be at least 8 characters long.
            - It must include at least one uppercase letter ([A-Z]).
            - It must include at least one lowercase letter ([a-z]).
            - It must include at least one digit (\d).
            - It must include at least one special character ([\W]).
            Logic:
                - If the password does not meet the conditions, an error message is displayed, and the registration is aborted. 
                - Password is hashed when inserting to database    
                - Display error when password does not match

        Validate Email Address:
            Logic:
                - checks whether the $email_address matches the format of a valid email address.
                - Verify valid email address

        Contact Number: 
            Logic:
                - Validates number only

    2. User Login
        Example:
            User: JohnDoe
            Password: P@@swoord1223
        Logic:
            - Validates invalid username and password

    3. User Dashboard
        - Dashboard
        - My Appointments
        - My Record
        - My Profile
        - Change Password
        - Notification
    4. Email Notification
        - When booking an appointment
        - When editing an appointment
        - Once Doctor confirmed the payment

ADMIN/DENTIST SIDE
    1. Admin Login
        Example:
            User: RoselleSantander
            Password: P@@swoord1223@
        Logic:
            - Validates invalid username and passwords
    2. Admin Dashboard
        - Dashboard
        - Patient Records
        - Appointment Recordss
        - Doctors Profile
        - My Profile
        - Change Password
    3. Doctor
        - Once added by Super Admin -> create account in account table with user_type = ádmin
        - account id should be the same in doctor_id
    4. Notification
        - When booking an appointment
        - When editing an appointment
        - When user uploaded the proof of payment
