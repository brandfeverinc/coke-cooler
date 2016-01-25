# Coke Cooler Project
## Database Tables with Design Descriptions

### <i class="fa fa-table"></i> Homepage Images
- Homepage images are the background images on the main home page of the app
- Multiple images are allowed, sort order is included
- Can turn images on & off
- Assumed functionality is that the images will fade in & fade out (slideshow style) or each page load could show a different background image.

### <i class="fa fa-table"></i> Demonstration Categories
- Demonstration categories are the high-level categories, so far defined as:
    - Equipment
    - Packaging
    - ETA
- Short description and main category image can be specified.
- Contact email implies that there will be a default contact person within each category.
- Can change sort order and make active/inactive (ETA category will be inactive at launch)

### <i class="fa fa-table"></i> Demonstration Items 
- Demonstration items are the specific coolers (or packaging items, etc.).
- Each item must be in a category (Equipment, Packaging or ETA).
- Background color can change per item page.
- Contact email will override default category contact person for any item for which contact email is specified.
- Sort order can be specified. The first 6 in the sort order are the "featured" items.

### <i class="fa fa-table"></i> Demonstration Item Images
- Can specify Front, Left, Right, Back and Open images for each Demonstration Item.

### <i class="fa fa-table"></i> Demonstration Item Image Highlights
- Can specify multiple "hotspot points" on an Item Image.
- Each hotspot has info text (can be html) that pops up.
- Hotspot button image can be specified for each highlight point.

### <i class="fa fa-table"></i> Demonstration Item Info Types
- Each Demonstration Item can have text (or html) info specified for multiple categories
- This table defines the categories and allows future additional categories. Current categories are:
    - About
    - Specs
    - Technology
    - Design
    
### <i class="fa fa-table"></i> Demonstration Item Info
- Holds the descriptive text (or html) for each Demonstration Item Info Type
- Must specify Demonstration Item and Demonstration Category, i.e. "Cooler Type 1" and "Specs" Info Type.

### <i class="fa fa-table"></i> Demonstration Item Info images
- For certain Demonstration Items and certain Info Types, multiple images (i.e., a slideshow) can be specified. 
- The slide sort order may be specified.

### <i class="fa fa-table"></i> Demonstration Item Videos
- Multiple videos may be specified for each Demonstration item
- Videos have title and placeholder image.
- Sort order of videos can be specified since video placeholder images will be displayed slideshow-style.

### <i class="fa fa-table"></i> Demonstration Item Gallery Images
- Gallery images are pictures of a Demonstration Item
- Multiple gallery images can be specified.
- Descriptive text (or html) can be specified for each Gallery Image.
- Sort order of gallery images can be specified.

### <i class="fa fa-table"></i> Demonstration Item Presentations
- Presentations are slides collected into a Powerpoint-style presentation
- Essentially identical to gallery images in implementation, but organized into groups
- Presentation name can be specified
- First image in Presentation Image table may be used as icon/placeholder image for presentation

### <i class="fa fa-table"></i> Demonstration Item Presentation Images
- Images in each presentation
- Can have multiple images in each presentation
- Images must be assigned to a Demonstration Item Presentation
- Sort order of images may be specified.

### <i class="fa fa-table"></i> Technologies
- Name and button image for Technology
- Technology headline
- Sort order of technologies can be specified.

### <i class="fa fa-table"></i> Technology Info
- Multiple Technology Info records can be input for each Technology
- Can specify name of info item, description and provide a button image for popup info about the technology info.
- Must specify an html template to use for display of Technology Info. Known template options:
    - Standard (most technology info popups use simple html text).
    - Sound (template to allow playing of sound files).
    - Lighting (template that displays lighting demonstration).
- Sort order of Technology Info items may be specified.

### <i class="fa fa-table"></i> Technology Info Recordings
- For the "Sound" template type in Technology Info, we need to track sound files
- Sort order of sound files may be specified.

### <i class="fa fa-table"></i> Technology Info Images
- For the "Sound" template type in Technology Info, we also need to track image files (displayed as part of "Sound" template).
- Sort order of image files may be specified.

### <i class="fa fa-table"></i> Patents (Intellectual Property)
- Can specify patent name and provide patent image.
- Can specify descriptive text (or html) for the patent.
- Sort order of patents may be specified.

### <i class="fa fa-table"></i> Settings
- Save miscellaneous info. Current values to be saved:
    - Screen Saver
    - How to Screen Share (text/html)
    - Back End Login
    - Main Headline (on homepage)
    
### <i class="fa fa-table"></i> System Users
- Login info for authorized admin users.
