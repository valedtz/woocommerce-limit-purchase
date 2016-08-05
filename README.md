#DM Book Test
Write a PHP script that will display a form to update a MySQL/MariaDB table.
The table is called “newsletter_subscriptions” and contains email addresses. Please provide the CREATE TABLE statement in your answer.
The form must ask for for the email address. When the form is submitted:
- if the email address already exists in the table, it will show an error message.
- if the email doesn’t exists, then it will insert a new row in the table, and display a success message.
For this exercise, only use built­in PHP functions and classes, do not use any third­ party framework or library. Use your best judgement to make something as production­ ready as possible, as if it was a real­ life assignment. You will not  be judged on the user interface, so it can be just plain old HTML.

##Note
I Used MySQL for developing this script.
The script creates newsletter_subscriptions table automatically. 

##File Structure
- index.php //Main Script
- config.php //Config File for DB settings
- README.md 
- classes/abstract/AbstractTableClass.php //Abstract for tables
- classes/DatabaseClass.php //Class to Connect into DB
- classes/DmbookNewsletterSubscriptionsClass.php //Class for the newsletter_subscriptions table

##Installation
1. Open config.php
2. Insert DB values
DONE!

Open index.php to see the result.
