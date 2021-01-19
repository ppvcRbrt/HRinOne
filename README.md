# HRinOne
HR in 1 HackCamp Project

This project represents a feedback - report generator. It is intended to be used in a PACS exam simulation as 
it can help in marking the papers and generating a feedback.

As a PACS simulation is specific to a single candidate, the template should be able to
generate more specific feedback, based on the candidate individual mark for each individual section.

The project used PHP as the underlying active state mechanism, while a MySQL database holds 
the required information. 
Cookies are used to make the user experience more pleasant as well as ensuring the functionality works.

SQL locks have also been used as the client requested that simultaneous operations should
be taken into consideration. 

PDFs are generated using the FPDF framework. This framework also includes a Protection class which we have used to ensure
overall security when generating a PDF. 



# FPDF Documentation
http://www.fpdf.org/en/doc/index.php

FPDF Security fix documentation http://www.fpdf.org/en/script/script37.php
