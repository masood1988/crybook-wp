

# Requirement #
  * Minimum WordPress 2.7
  * PHP5 completed with cURL and SimpleXMLElement enabled

# Downloading #
## Via Package ##
CryBook's latest version (in .zip) can be downloaded from Downloads tab.
## Via SVN Export ##
You can also get a latest copy from our repository via this command:
```
svn export http://crybook-wp.googlecode.com/svn/trunk/ <your_folder_name> 
```

# Customization #
## Comments Avatar ##
By default, we have enable you to use Gravatar service for all commenters. You can control all configuration related to Gravatar usage from the Control Panel located under Setting > Discussion
## About Me Avatar ##
To customize the About Me avatar at the home page please change the file located under /wp-content/themes/crybook/images/site.jpg, best resolution should be 60px for both width and height.

## Advertisement ##
We doesn't restrict you from adding or using any form of advertisement package you may find in the Internet, but in our singular sidebar we already include a placeholder for you to add your `advertisement code`.

Open `path/to/wordpress/wp-content/themes/crybook/extra/sidebar-page.php` in any text editor and go to line 53:
```
<!-- add your advertisement here -->
```
Replace the code above with your `advertisement code` mostly in JavaScript.