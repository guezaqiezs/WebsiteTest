<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

CHANGELOG:

Legend:

* -> Security Fix
# -> Bug Fix
$ -> Language fix or change
+ -> Addition
^ -> Change
- -> Removed
! -> Note

-------------------- 1.8.5 Upgrade Release [04-Nov-2011] ---------------

! jSeblod CCK becomes SEBLOD (1.8.5 for Joomla 1.5)
* Folder Permissions (777) fixed.
# JS Validator issue (IE) fixed.
# Minor issues fixed.

-------------------- 1.8.2 Upgrade Release [29-Nov-2010] ---------------

+ Calendar (Style Variation) added on Alias Custom.
+ Publish Up & Publish Down (Search In) added on Search Generic.
+ To (Field) improved on E-mail >> Grab multi-values (many e-mail address).
+ Bcc (Field) added on E-mail.
# Minor issues fixed.

-------------------- 1.8.1 Upgrade Release [5-Nov-2010] ---------------

+ "Off" State added on SEF Urls (Search Action).

# Upload Image (1folder/content) fixed on FieldX.
# Minor issues fixed on Search Type.
# SEO/Router issues fixed.

-------------------- 1.8.0 Upgrade Release [15-Oct-2010] ---------------

+ ACL added on Content Types >> Different Fields for different Users.
+ Router added. >> SEF Urls for Articles on List (Search Types).

! Performance Improvements on Search ( Cache + Indexes )
+ 2nd Caching Process added on Search Types (on Display).
+ "Exact Phrase Indexed", "Any Words Exact Indexed" Match added on Search Types.
+ "Indexed" added on Text, Select Simple, Radio. >> Content ID + Value joined in db.
+ Inherited User added (from Live) on Content & Search Types.
+ "Live Url = Var, Var(Int)" Live added on Content & Search Types.
+ Personal Content submission added on User Manager.
+ "Search Results = User" Live added on Search Types.

+ Cache available on List Module.
+ "Module Parameters" Live added on Search Types >> Live added on List Module.
+ Random & Shuffle added on List Module.

# Article Title & Stored Value ( Display on... ) added on External Article.
# Auto Redirection parameter added on Search & List Layout (Menu).
+ Article Meta process added on Import (CSV).
+ Cache (On Display) added on Search Action.
	+ CKEditor compatibility added. { TODO }
+ Created Date (Search In) added on Search Generic.
+ Defaultvalue attribute added + Live available on Save.
+ Number Incrementer added on Text.
+ Message (Edition) added on Form Action.
+ SEF Urls (SEO) added on Search Action.
+ To (Field) available on E-mail >> Grab value from Field to set as To.
+ Link & Typography (Html) available on GroupX.
+ Variables added on Free Code.

- Search into Uncategorized Articles removed.
* SQL Injections Vulnerabilities prevented on Search Types.
* XSS Vulnerabilities prevented on Search Types.
# Various issues fixed.

-------------------- 1.7.0.RC4 Upgrade Release [31-Aug-2010] ---------------

+ Display (Submission & Edition) added on Content Types.
+ Live & Value added on Content Types.

! Performance Improvements on Search ( Cache + Indexes )
+ Caching Process added on Search Types >> Speed Up Search & List.
+ "Indexed" added on External Article >> Content ID + External Value joined in database.
+ "Indexed (as Key)" added on Text >> Content ID + Value (Key,Sku..) joined in database.
+ "Menu Parameters" Live added on Search Types. >> Live added on Search & List Layout.
+ Search Operator Field Type added on Fields >> AND, OR, ...

+ CSV Import improved (Joomfish tranlations compatible).
+ CSV Import improved (FieldX & GroupX & Index compatible, UTF-8).

+ Cache attribute added on Search Action.
+ Debug attribute added on Search Action.
+ Deletable attribute added on FieldX & GroupX.
+ Language (JText) added on Typography (Html) >> J(...) for JText::_( '...' ).
+ Mode added on External Article & Save >> Index (as Key) compatible.
+ Orientation attribute added on GroupX.
+ State & Category Parent Id (Search In) added on Search Generic.
+ Target (_self,_blank) added on Form Action & Search Action.
+ Text attribute added on Checkbox & Select Multiple & (all) Select Dynamic.

^ "Required" column replaced by "Title || Index" filters on Field Manager.
$ "com_cckjseblod_more" language loaded.

+ List Module improved.
# Search module improved & issues fixed.
# Readmore issues fixed. (GroupX, index2.php)
# Various improvements added & issues fixed.

-------------------- 1.7.0.RC3 Upgrade Release [1-Jul-2010] ---------------

+ Group (Content Type) Field Type added on Fields.
+ Free Code (Php) Field Type added on Fields.

+ ACL added on Search Types >> Different Fields for different Users.
+ Live added on Search Types >> Get Live Values.
+ Stage added on Search Types >> Join/Multi Queries (External, ...).
+ Custom Sort Mode added on Search Types.

+ "Advanced List" template added.

+ Copy and Rename Prefix added on Batch Process (Fields).
+ CSV Import improved (FieldX & Readmore process added).
    
+ FieldX improved (Draggable).
+ Free Query added on Select Dynamic.
+ Save style variation added on Alias Custom.
+ Subcategories (In Categories attribute) added on Save.
+ Substitute attribute added on Checkbox & Radio & Select Multiple/Numeric/Simple & External Article & Save.
+ Text attribute added on Radio & Select Dynamic/Simple.
+ Thumb4 & Thumb5 added on Upload Image.

# Alias Custom improved (Search Generic).
# FieldX compatibility improved (External Article, Wysiwyg Editor).

# Various issues fixed.

-------------------- 1.6.2 Upgrade Release [4-May-2010] ---------------

+ Display (Content/List) attributes added on External Article.
+ Javascript Links added on Content dimension (Search Type).
+ Autogenerate Field name function added on Fields.

# Active Menu issue fixed.
# Search Form Submit (IE7) issue fixed.
# Some addtionnal issues fixed on Search/List.
# FieldX (IE7) issue fixed. (Default Form Template updated)
# Validation Alert (IE7) issue fixed.

# Description stored (when Copy) on Templates, Content Types, Fields, Search Types.
# Default Value stored (when Copy) on Fields (Wysiwyg Editor, Free Text).
# Message stored (when Copy) on Fields (Form Action, E-mail).


-------------------- 1.6.1 Upgrade Release [08-Apr-2010] ---------------

+ "1 Folder / Content" attribute added on Upload Image & Upload Simple.

# Bug fixed on Search Multiple.
# Bug fixed on New Template view. (issues since 1.6.0 stable)

-------------------- 1.6.0 Upgrade Release [31-Mar-2010] ---------------

+ Sort added on Search Types >> 4 succeeding levels to sort results.
+ Checkbox, Select (Dynamic, Multiple), Upload (Image, Simple), Wysiwyg searchable.
+ Target added on Search Types >> Use a portion of value.
+ Search Links added on Content dimension (Content & Search Type)
+ "Each Word" Match added on Search Types.
^ "All Words" Match renamed to "Default (Phrase)"
+ Auto Type improved on Search Types.

+ Access added on Content dimension (Search Type) >> Access field by Name or by Location.

+ List module included.
+ "Default Slideshow" template added.
+ "Default Blog" template added.

+ Search Multiple Field Type added on Fields.

+ Value Importer added on SiteForms module.

+ Columns & Table Style attributes added on Checkbox & Radio.
+ Default Value added on Upload Image.
+ Field replacement process in Message added on E-mail.
+ From (E-mail or Field) & To (Field) attributes added on E-mail.
+ Length & Message & Uncategorized attributes added on Search Action.
+ Panel Closer attribute added on Panel & Sub Panel.
+ "Content Hits", "Panel End", "Sub Panel End" field added on Fields.

+ Article Parameters process added on Import (CSV).
- Limit & Ordering filter (static) removed.

# "403 Access Forbidden" (when registration disabled) bug fixed on User Manager.
# "Fakepath" (IE or Opera 10.50) bug fixed on Template.
# Menu (5.0.41 or older database) bug fixed on Joomla Menu field.
# Some issues fixed on Select Dynamic.
# Some issues fixed on Site Form Module.
# Views fixed on Templates. (issues since 1.5.0 stable)

-------------------- 1.5.1 Upgrade Release [22-Feb-2010] ---------------

+ Pack Process updated on Content Types.
+ Language Localization added on Calendar.
# FieldX compatibility improved (Textarea).

-------------------- 1.5.0.STABLE Upgrade Release [22-Feb-2010] ---------------

+ Search Types added >> Create advanced & multi criteria List and/or Search Views.

+ CSV Import on Article Manager >> Import thousands of articles in a few clicks.
+ CSV Import on User Manager >> Import thousands of users in a few clicks.
+ HTML Export on Article Manager >> Export Content as HTML for Newsletters or a Static Html page.

+ Template Type ("Content", "Form", "List") added on Templates.
+ "Default List" template added.

+ New Custom Content Dimension (Link, Typo, Html)
+ Alias Custom Field Type added on Fields.
+ Search Action Field Type added on Fields.
+ Search Generic Field Type added on Fields.

+ Download task added on front-end (File & Upload Simple).
+ Delete Box attribute added on Upload Image & Upload Simple.
+ Filesize property added on File, Upload Image & Upload Simple.
+ Legal Extensions attribute added on Upload Image & Upload Simple.
+ Max File Size attribute added on Upload Image & Upload Simple.
+ Preview attribute added on File, Upload Image & Upload Simple.
+ Images now well supported in Checkbox & Radio options.
+ Separator & Columns attributes added on Field X.
+ User's categories attribute added on Save field.

+ Restriction Levels added on Configuration.

# Cache Issue fixed on Captcha.
# Issues (for Old Packs) fixed on Pack Export/Import
# "Modified by" value fixed.
# Required/Not Required fixed on Wysiwyg Editor (Box) & Plugin Button.

! Performance Improvements

-------------------- 1.5.0.RC5 Upgrade Release [20-Jan-2010] ------------------

+ Joomla Plugin (Content) Field Type added on Fields (Any Content Plugin with Parameters!)
+ Batch Image Process Integration added on Media Manager
+ Watermark added on Upload Image Field Type
+ Update Title & Color added on Configuration/Operations

+ Hide Icon Edit parameter back on Configuration & process improved.

+ Remove value Icon added on Field X. (Available on Default Form Template)
^ Add value Link (on label) replaced by a Icon on Field X. 
# FieldX compatibility improved (Folder/Upload Simple/Upload Image)

# Improvements & Some Bugs fixed on Pack Export/Import
# Captcha Field fixed on Form Edition
# Default Value added on Select Dynamic field
# Icon Edit redirection Fixed with SEF urls
# Readmore Field value fixed
# Subpanel Field fixed
# Template Manager fixed
# Upload Image fixed
# Where Clause Operator (null) added on Select Dynamic field

-------------------- 1.5.0.RC4-2 Upgrade Release [22-Dec-2009] ------------------

^ Site Form Creation/Edition Access updated on Form Action fields (new & existing fields).

+ "Published/Archived" parameter added on User's Articles List Layout (Menu Item Type).
+ "All Authors" parameter added on User's Articles List Layout (Menu Item Type).

+ Icon Edit link updated >> Redirect to suitable Content Type Site Form.
- Hide Icon Edit parameter removed on Configuration.

# Article edition fixed on Article/Category User's List (when Auto Redirection).
# "param.ini" file renamed to "params.ini" on Html Prepare (Template Generator).

-------------------- 1.5.0.RC4 Upgrade Release [14-Dec-2009] ------------------

^ jSeblod CCK Installer rewritten (part 2)

+ Amazing Joomfish Compatibility & Integration on Joomla Article Manager.
+ "Content & Form" Tab on Joomla Template Manager >> List jSeblod CCK templates.
+ "Save & New" + "Apply" buttons added on Content Manager

+ Prepare Html (Template) added on Content Type manager.
+ Php version check added on jSeblod CCK Administration (Control Panel).
+ Remove User's Content feature added on User Manager.
+ Remove User<->Article connection process added on Article Manager.

+ Advanced Content template updated.
+ Business Card template added.
+ Default Avatars added in User Profil template.

+ Joomla User's Content Submission Layout added on Menu Item Type

# Bad order after submission fixed on Article Manager & Category Manager.
# Some compatibility issues with components fixed into System Plugin.
# Some compatibility issues with modules fixed into System Plugin.
# CSS fixed in Portfolio template.
# Password input no more disabled on Add New User (Defaut View)
# PNG with alpha (transparency) fixed on Upload Image Field Type.
# Some issues fixed on Captcha Field Type.
# Some issues fixed on Site Form Module.
# Some improvements & issues fixed on Folder Field Type.
# Some subjects & messages added/updated on existing Email Fields
# "[sitename]","[siteurl]","[username]" values fixed on Email Field Type (Messages & Subjects)

-------------------- 1.5.0.RC3 Upgrade Release [21-Nov-2009] ------------------

Universal CCK for Joomla: Powerfull Templates, Content Types, Custom Fields (40 Field Types)
with Admin & Site Submission and/or Registration & Community Features.
Fully integrated to Joomla Content Manager, Category Manager, User Manager and all other components.
Suitable for Components & Modules using or not Plugin Process.
Advanced Media Manager (soon), Export/Import using CCK Packs, Highly Integrated Content Manager,
Joomla Plugin Button as Field, SubCategories (Catalog & List Layouts), True Preview,
Upload Image with Automatic Thumbs Creation, and much more...

^ jSeblod CCK Installer rewritten (part 1)

+ Joomla Categories Submission (admin/site) added
+ Joomla User Registration (admin/site) added
+ Joomla User's Content Submission (admin/site) added

+ SubCategories added (integrated to Joomla Category Manager)
+ Joomla Plugin Buttons available as Content Field

+ "Content Edition Kit" renamed to "Content Manager"
+ Content Manager available as Box added
+ Content Manager available as Fullscreen added
+ Advanced Content Manager configuration added

+ True Preview added on Article Manager

+ Site Url Views added on Templates
+ Site Views view added from Content Templates >> List any Site Views
+ Multiple parameters + ColorPicker in Templates

+ "Advanced Content" template added.
+ "Default Content" template added.
^ "Default Submission" renamed to "Default Form" on Templates
+ "Simple Form" template added. 

+ Content Fields creation/edition added on Content Types
+ Emails Fields assignment added on Content Types

+ Blog Content Type add.
+ Contact Content Type add.
+ User Profile Content Type add.

+ Alias Field Type added on Fields
+ Button Free Field Type added on Fields
+ Captcha Image Field Type added on Fields
+ External Article Field Type added on Fields
+ External Subcatagories Field Type added on Fields
+ Field X Field Type added on Fields >> Repeat X Time a Field, and add some "on the Fly" (Duplicate)
+ Folder Field Type added on Fields
+ Joomla Menu Field Type added on Fields
+ Joomla Plugin Button Field Type added on Fields
+ Joomla User Field Type added on Fields
+ Password Field Type added on Fields
+ Save Field Type added on Fields
+ Select Numeric Field Type added on Fields
+ Upload Image Field Type added on Fields >> Automatic Thumbs Creation (Backgroud Color, Crop, Max Fit, Stretch)

+ Email Field Type improved >> SendEmail events, Include Fields’ value
^ "Image" Field Type renamed to "Media Field Type (Soon: Advanced Media Manager)
^ "Media" Field Type renamed to "File" Field Type
^ "Joomla Article" Field Type renamed to "Joomla Content" Field Type
^ Wysiwyg Editor improved >> Joomla Editor, on Form or on Box
# Multiples fix, improvements, or attributes added on any Field Types

+ User's Articles List Layout added on Menu Item Type >> List, Add, Publish, Unpublish, Delete
+ User's Categories List Layout added on Menu Item Type >> List, Add, Publish, Unpublish, Delete
+ Content Types List Layout added on Menu Item Type
+ Joomla Category Submission Layout added on Menu Item Type
+ Joomla User Registration Layout added on Menu Item Type
+ Users List Layout added on Menu Item Type >> List, Add, Publish, Unpublish, Delete
+ User's Form Layout added on Menu Item Type
+ User's Homepage Layout added on Menu Item Type (Soon)
+ User's Content List Layout added on Menu Item Type >> List, Add, Publish, Unpublish, Delete

+ Extended Toolbar admin module included
+ Extended Login site module included
+ Toggle CCK admin module included

-------------------- 1.2.5 Update Release [29-August-2009] ------------------

! New jSeblod CCK Club Subscription: Free Subscription
- Domain license check removed
# Joomla Article Fields edition fixed
# Errors if database prefix is not "jos_" fixed
# Automatic Content Type/Field creation fixed on Content Templates
# Other fixs

-------------------- 1.2.0 Upgrade Release [30-July-2009] ------------------

+ Site Forms module included
+ Joomla Module Field Type added on Content Fields
+ Joomla Readmore Field Type added on Content Fields
+ MediaBox added on Basic Site Display
+ Categories created in Tree during Content Pack import
+ Country List added into Database (#__jseblod_cck_extra_country)
# Gold Calendar style ok

-------------------- 1.1.3 Update Release [23-July-2009] ------------------

+ Component Update process (source code)
+ How To? Documentation added
^ "Content Templates" renamed to "jSeblod Templates"
# Content Fields in correct order in Basic Site Display (defaut mode without Content Templates)

-------------------- 1.1.2 Update Release [14-July-2009] ------------------

+ Extended Admin Menu module included
^ "Content Items" renamed to "Content Fields"

-------------------- 1.1.1 Update Release [12-July-2009] ------------------

+ Plugin process for Content Modules added

-------------------- 1.1.0 Upgrade Release [02-July-2009] ------------------

+ E-mail Field Type added on Content Fields
+ Content Pack added >> Import & Export of Content Pack (Content Types, Content Field(s), Content Template(s))
^ "Content Interface" renamed to "jSeblod CEK (Content Edition Kit)"

-------------------- 1.0.1 Update Release [22-June-2009] ------------------

+ Menu Item Views added on Content Templates

-------------------- 1.0.0 Initial Release [12-June-2009] ------------------

+ Initial Release