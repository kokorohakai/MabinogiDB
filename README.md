*Mabinogi Database*

I needed something to sharpen up my web application developement skills, and remind my self how to do framework development and design. This is a custom framework designed for a site I wrote, "Mabinogi Database". Which is a database driven collection of Applications that are designed to be tools to help players of the game. The three obvious things I did not write, are jQuery, jQuery UI, and jqGrid.

As you can see I focused on designing a LAPP stack. (Linux, Apache, PostGreSQL and PHP) With this stack I designed a some what MVC (more View / Controller driven) stack. The views are loaded and using the API, data is collected from controllers, which use an abstracted model to pull data from the database. 

I hope this will also be useful to anyone else out there to learn a bit about this stuff.

This project is unfortunately far from complete, but as I finish parts of the site, I will be updating this repository.

*Explanation*

The folder "WWW" is what is public accessable. You will notice the .htaccess file. It pushes any unresolved url to index.php. If apache fails to find a requested url, it forces it to be parsed by that file. 

From there the index.php access it's application information from "protected". Where it looks for a route via "sections". "layout.php" is the basic view that is used across the entire site, and then each "section" has it's own view. "utility_methods.php" is a collection of global methods used globally by php. "api" contains all of the controllers for data accessed via ajax to /api . It will parse m (for module) and a (for action) and use those to determine how to format the data. 