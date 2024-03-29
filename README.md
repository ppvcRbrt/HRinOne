# HRinOne
```HR in One HackCamp Project```

This project represents a fully-featured solution to generate PDF reports for candidates 
with the ability to manipulate data accordingly to the needs of a PACS examiner. It is intended 
to be used in a PACS exam simulation as it can help in 
marking the papers, generating feedback and sending the results (or initial instructions) by email.

As a PACS simulation is specific to a single candidate, the template should be able to generate more specific feedback, 
based on the candidate individual mark for each individual section.

The project used PHP as the underlying active state mechanism, while a MySQL database holds the required information. 
Cookies are used to make the user experience more pleasant as well as ensuring the functionality works.

SQL locks have been used as the client requested that simultaneous operations should be taken into consideration.

PDFs are generated using a FPDF framework (FPDF). 
This framework includes a Protection class as well which we have used to ensure overall security when generating a PDF.

# Project information
The main idea and the most important one in this project was the ability to construct a report generator 
which would be as flexible as possible.
Simulating a PACS examination should allow the candidate to get extensive and tailored feedback based on his performance.
Because of this, the ability to enter customized assessment types, sections, questions, indicator and feedback for indicators and question were the main
things to consider when building this solution.

Another important idea to consider was to make sure the system runs fast. Interviewers don't have the necessary time
to wait for a long time while time-consuming operations are made. 
Because of this, the solution runs on mainly PHP and cookies to
have the ability to circle through each and every category that needs to be edited or manipulated. 
Therefore, ```cookies must be enabled``` in order for the website to work correctly.
More information about cookies can be fount at: ```aboutcookies.org```

# Code information
The PHP models for manipulating data in the database are stored in the ```Models``` folder.

The views are stores in the ```Views``` folder.

The controllers are the ```".php" pages```, located in the root folder.
They serve to link backend information with the structure with the frontend part of the website.

The text that is auto-generated when creating a final report (the explanation at the beginning of the report) can be found
in the ```text``` folder. Each section is grouped in a file that holds the corresponding name.

# Instalation details
1. You need a server that can host a website. MySQL and PHP should also be supported as an active state mechanism 
   as well as a relational database information hosting.
2. The database schema should be uploaded and executed. 
   Usually, this can be done from the PHPmyAdmin interface or any Database interface which allows operations to be done
   on the database schema. The user should also have the privileges to create and edit the database. 
   The database file is called "database.sql".
3. Add fulltext functionality to your Candidate_information name column by running 
   ```ALTER TABLE Candidate_info ADD FULLTEXT(name)```.
4. Move the files to a remote server using either the ZIP archive being extracted on the remote location 
   or by using an FTP client such as FileZilla to move the files to the remote location. The SSH protocol can
   also be used to transfer files to / from a remote server.
5. The database connection details have to be edited in the file Database.php from the Models folder. 
   ```Line 23-26``` should be edited accordingly. You can chose to use the plain-text version of the details 
   (which we do not recommend) or you can use any Base64 encoding / decoding website to encode your username and password
   and use that information in ```Database.php```.
6. Email connection information needs to be updated accordingly in the file ```Mailer.php``` from the ```Models``` folder
   on lines 24-27. You will require the SMTP host, Username, Password and port. We reccommend sending emails
   using the TLS mechanism as it will ensure the data is transmited securely.
6. reCaptcha ```SiteKey``` should be modified in ```index.phtml``` which is in the ```Views``` folder. The ```SecretKey``` 
should be modified in ```login.php``` on line 25.
   
# FPDF Documentation
This project uses FPDF framework, which is a class that allows the ability to generate PDF files with pure PHP, 
(without using the PDFlib library). The version used is the latest as of 27th January 2021, version 1.82.

http://www.fpdf.org/en/doc/index.php

FPDF Security fix documentation: http://www.fpdf.org/en/script/script37.php

# PHPMailer documentation
This project uses PHPMailer, which is a PHP script that allows sending mails from a web server. It can use both
SMTP authentication or it can use the basic mail() function in PHP. A note has to be made here that using the mail()
method with the requirements that are used nowadays (attachaments, CC, BCC, certain mail headers needed changings, etc) is 
not well-suited and not recommended. Moreover, the basic mail() function does not offer an encryption mechanism to
ensure data is transferred in a secure manner. Using this framework will also allow the posibility to make changes
or improve this soluton is the future by adding more functionalities. 

The version used in this solution is 6.2.0, the latest as of 27th January 2021.

Documentation:

https://phpmailer.github.io/PHPMailer/

https://blog.mailtrap.io/phpmailer/

# reCaptcha documentation

This solution includes a reCaptcha v2 implementation. The functionality was added on the login page, as a it would have a great impact on the security of the page by making brute force attacks way harder. The main functionality is present is ```login.php```. reCaptcha works by having a pair of API keys. The first key is called a site key and the second one is a secret key.

The secret key should be modified in ```login.php``` on line 25.

The site key should be modified in ```index.phtml``` which is in the ```Views``` folder.

Lines 21-36 in ```login.php``` represents data verification and response handling.

More information about reCaptcha implementation can be found at:
https://developers.google.com/recaptcha/intro
