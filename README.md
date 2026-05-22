# QueueDesk - IT made simple

## Installation requirements
- PHP 8+
- MySQL based DBMS (QueueDesk recommends using MariaDB
- Web server (QueueDesk recommends Apache2)

## Installation instruction
1. Download the zip file from the release or clone the repo onto your machine
2. Ensure all the required software is installed (or use Xampp for a simple setup)
3. Edit the config.php file to point to your sql server
   - db_host = Your IP/domain
   - db_name = Database name
   - db_user = Username of DBMS account (e.g. root)
   - db_password = Password for the DBMS user
4. Edit the rest of the config file to suit your needs
   - ADMIN_UNIVERSAL - This password is the general password used to register new users whether they are technicans or admins
   - ADMIN_PASS - This password is the special password to be entered to sign up specifically admins in the additional password fields
   - ALLOW_REGISTRATION - Defines if new users can register or not
   - OFFSET - The offset of tickets are loaded per page.
   - LIMIT - Limits how many tickets are loaded per page Smaller = Less tickets, faster load, simpler UI
