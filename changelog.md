--------------------------------------
IMPORTANT FEATURES:
- add product from template (copy product, just for admins) -> 3
- show to user his suggestions and also products which are not yet confirmed -> 1
- check for barcode while submitting -> 2
- do not store images larger than 1mb, use image library -> 4

--> pick the most active slovak user and ofer him/her admin post
--> think about native comments instead of fb comments
--> create some voting -> https://github.com/antennaio/jquery-bar-rating
--> minify your files

THAN: 
- combined filters (multiple tags, categories)

NICE TO HAVE:
- add php cache
- add facebook login
- rotate image


Ver 0.1.5 BETA -> 12.5.2017
- show user's suggestions in admin
- https for cz domain not working
- public list of users with the number of products 
- fix favourites / by author filters (header info)
- some missing titles added

Ver 0.1.4 BETA -> 9.5.2017
- add note to product
- add note and description to categories, supermarkets and tags
- fix allow higher price for cz (max 999)

Ver 0.1.3 BETA -> 8.5.2017
- user can suggest product edits 
- add button to administration -> move to cz / move to sk
- product detail links fix


Ver 0.1.2 BETA -> 6.5.2017
- use get in product filter queries
- fix my products pagination
- disable form for users who are not logged in
- show author name in admin panel
- add twig cache


Ver 0.1.1 BETA
- tag filters front end
- show messages from contact form in admin panel
- allow answer to messages and store both
- fix admin requests image path thumbnail
- active class for current page (frontend)
- add custom pages
- show both flags


Ver 0.1.0 BETA
- quagga code scanner
- if form is not submitted fill the form with data
- font fix
- facebook comments not showing on ipad fix
- google analytics
- something with lazyloading (kind of fixed)
- dropdown on mobile does not work (fixed)

Ver 0.0.9 ALFA
- add 'my product' like / heart
- add facebook like / share 
- add facebook comments

Ver 0.0.8 ALFA
- add barcode input
- add product search
- flags

Ver 0.0.7 ALFA
- add number of users to admin dashboard
- add number of tags to admin dashboard
- add number of emails in a newsletter to the admin dashboard
- add number of messages to admin dashboard
- password recovery

Ver 0.0.6 ALFA
- allow more images per product (main and ingredients)
- separate images to folders cz and sk
- separate thumbnails to different folders

Ver 0.0.5 ALFA
- image lazy loading
- newsletter table
- contact form (public part)
- change order for products fetching
- show products added by author

Ver 0.0.4 ALFA
- Framework migration ( namespaces, core logic )
- Code cleanup
- minor fixes
- changed password hashing
- when uploading product check if it does not exists
- use country separator when selecting product
- add fallback if string is missing

Ver 0.0.3 ALFA
- czech language added
- image slugs fix

Ver 0.0.2 ALFA
- admin tags filters
- improve authority checking
- registration validation

Ver 0.0.1 ALFA
- front end product detail grid fix
- public user details editing
- small front-end fixes

Ver 0.0.0.9
- front end grid
- front end links
- front end product detail

Ver 0.0.0.8
- front end filters
- pagination
- admin filters update

Ver 0.0.0.7
- code cleanup
- database cleanup 
- tags

Ver 0.0.0.6
- trash added
- request managment added
- request form added
- registration and login created
- user authority levels
- product visibility

Ver 0.0.0.5 
- add author id to every product
- add country separator for users
- create basic front end
- slugify image names

Ver 0.0.0.4
- admin tables filtering
- new slugs
- update supermarkets to standard architecture
- delete matching tables while deleting store or category
- delete old image if new is updated

ver 0.0.0.3
- bower component jquery.confirm.js
- delete function
- cross site flash messages
- delete images while deleting
- adjust admin content tables

ver 0.0.0.2
- image popover
- fix decimal prices
- createt international version based on domain
- added sorttable
- 450 x 450 resizing
- composer implemented
- composer parsedown dependency
- version control

ver 0.0.0.1
- admin area created
- login created
- profile updating created
- products created
- categories created
- stores created
- image manipulation with 150 x 150 thumbs
- image upload