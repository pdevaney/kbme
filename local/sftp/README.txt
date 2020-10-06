Export reports to SFTP remote folder
------------------------------------

# Plugin /local/sftp
SFTP report files uploader plugin

# Compatibility
Totara: 12.17 and later

# Author
Solin, Bartosz

# Functional description
Used to automatically connect to any SFTP server and upload reports CSV files. Stores CSV files in additional local folders keeping 5 recent version of any report.

# Technical description

## Installation

1. Place the contents of this directory inside the `/public_html/local/stp` folder relative to your install path;
2. Log in into your platform as an administrator and confirm the plugin installation;

## Configuration

### Plugins settings
1. Go to **Site administration > Plugins > Local plugins > Export reports to SFTP**.
2. On the setting page you can configure: SFTP account, files store folder, which reports should be upload and scheduling. Configure those as you wish and save the changes.

### Scheduled tasks settings
1. Go to **Site administration > Server > Scheduled tasks**.
2. Lookup the **Export reports to SFTP** (\local_sftp\task\sftp_report) task and click on the gear icon
2. Configure the task to run everytime cron job is called (all set to asterisk).

### Manual task execution (verbose trace/debug mode)
1. Authenticate in the server running your platform instance (eg: using SSH);
2. Navigate directories until you're inside the `public_html` folder relative to your install path;
3. Run the following command: `php admin/tool/task/cli/schedule_task.php --execute='\local_sftp\task\sftp_report'`

## Execution logs
- Information and error logs can be viewed in the Logs Report. To view them, go to **Site administration > Reports > Logs**.
