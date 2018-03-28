# Welkynd Guild Website

## Setting up the server
### Step 1: Getting the WAMP stack
Go to [BitNami](https://bitnami.com/stack/wamp) to download (and subsequently install) the Windows Apache/MySQL/PHP (WAMP) stack. This will provide all the software necessary to run the website. No additional modules are necessary, but may assist in development.

### Step 2: Direct Apache to the desired Document Root
Suppose you installed the WAMP stack in "wamp_dir." This is usually "C:\Bitnami\wampstack-7.1.15-0", though the trailing numbers can be different depending on the version number. 

Open "wamp_dir\apache2\conf\httpd.conf" for editing. Find a line that starts with "Document Root," likely followed by "wamp_dir/apache2/htdocs" in quotes. Replace the part in quotes with the path to your site folder. Likewise, replace the same quotes in the following line with the same path.

Open "wamp_dir\apache2\conf\bitnami\bitnami.conf" for editing. Edit similar lines the same way.

### Step 2.1: (Optional) Add your site to your computer's HOSTS file
Open the command prompt (cmd) with admin privileges. Edit "C:\Windows\System32\drivers\etc\hosts" adding the following lines:
'''
	127.0.0.1	welkynd.com
	::1			welkynd.com
'''

When you enter "welkynd.com" as a url in a browser, it will redirect to your site. However, both windows and browsers cache DNS lookups for easy routing. If you have looked up "welkynd.com" and it has not successfully routed, you may need to type
'''
ipconfig \flushdns
'''

to flush windows' DNS cache. You will also need to restart the browser.