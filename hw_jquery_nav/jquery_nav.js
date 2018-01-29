/* 
The only edits you can make for this exercise are in this file.
Use JQuery objects and methods, not DOM objects.

For help:
See my JQuery vs Dom Example.
See my JQuery Each Iterator Example.
*/
 
/* <!-- 
    Instructions:
    Edit the jquery_nav.js file to automatically turn the lists into styled nav bars when the page loads.
  
    You may NOT edit the CSS or HTML files provided with this assignment. Only edit the JS file, period. 
    The lists that do not have the fancy-nav-bar-type attributes should not be changed.
    The fancy-nav-bar-orientation should default to horizontal, but setting to vertical should accomplish that.
  
    Note that fancy-nav-bar-type is obviously not an attribute defined in the HTML language.
    
    This example is expired by CSS/JQuery frameworks such as Bootstrap and Angular where custom 
    framework-specific attributes are used to induce special formatting.  In such frameworks,
    you sometimes only have to place a special atribute in the HTML code, and the framework does 
    its magic.   But here, you have to supply the magic!!!!!!
    
    Remember, no editing this file or the CSS file.  Not even for the vertical option!
  
  -->*/
$(document).ready(function() {
	$('ul').each( function(){
		if($(this).attr('fancy-nav-bar-type') == 'fancy'){
			$(this).addClass('fancy');
		}
	});
	$('ul').each(function () {
		if($(this).attr('fancy-nav-bar-type') == 'fancier'){
			$(this).addClass('fancier');
		}
	});
	
});
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									
									