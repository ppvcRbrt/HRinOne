# HRinOne
HR in 1 HackCamp Project

This project represents a feedback - report generator. It is intended to be used in a PACS exam simulation as it can help in marking the papers and generating a feedback.

As a PACS simulation is specific to a single candidate, the template should be able to generate more specific feedback, based on the candidate individual mark for each individual section.

The project used PHP as the underlying active state mechanism, while a MySQL database holds the required information. Cookies are used to make the user experience more pleasant as well as ensuring the functionality works.

SQL locks have also been used as the client requested that simultaneous operations should be taken into consideration.

PDFs are generated using the FPDF framework. This framework also includes a Protection class which we have used to ensure overall security when generating a PDF.

# Project information
The main idea and the most important one in this project was the ability to construct a report generator which would be as flexible as possible.
Simulating a PACS exmination should allow the candidate to get extensive and tailored feedback based on his performance.
Because of this, the ability to enter customised assessment types, sections, questions, indicator and feedback for indicators and question were the main
things to consider when building this solution.

Another important idea to consider was to make sure the system runs fast. Interviewers don't have the neccesary time
to wait for long time while time-consuming operations are made. Bacause of this, the solution runs on mainly PHP and cookies to
have the ability to circle through each and every category that needs to be edited or manipulated.

# Code information
The PHP models for manipulating data in the database are stored in the Models folder.

The views are stores in the Views folder.

The controllers are the main "index" pages, they help to link backend information and structure with the frontend part of the website.


# Instalation details
1. You need a server which can host a website. MySQL and PHP should also be supported as an active state mechanism 
   as well as a relational database information hosting.
2. The database schema should be uploaded and executed. Usually, this can be fone from the PHPmyAdmin interface or any Database interface which allow operations to be done
   on the database schema. The user should also have the priviliges to create and edit the database. The database file is called "database.sql"
3. Move the files to a remove server using either the ZIP archive being extracted on the remote location or by using a FTP client such as FileZilla
  to move the files to the remote location.
4. The database connection details have to be edited in the file Database.php from the Models folder. Line 23-26 should be edited
accordingly. You can chose to use the plain-text version of the details (which we do not reccommend) or you can use any 
   Base64 encoding / decoding website to encode your username and password an use that information in Database.php.
5. Mail connecting information needs to be updated accordingly in the file """""""".



# FPDF Documentation
http://www.fpdf.org/en/doc/index.php

FPDF Security fix documentation http://www.fpdf.org/en/script/script37.php

# PHP Mailer documentation
https://phpmailer.github.io/PHPMailer/

https://blog.mailtrap.io/phpmailer/
