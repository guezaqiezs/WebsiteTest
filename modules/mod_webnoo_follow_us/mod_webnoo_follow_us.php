<?php



/**

 * @package		mod_webnoo_follow_us

 * @subpackage	Module

 * @copyright	Copyright (C)2010 Webnoo.com - All Rights Reserved.

 * @author		Shekhar Sagar Srivastava <admin@webnoo.com>

 * @version		1.1

 * @license		GNU/GPL, see LICENSE

 *

 *

 * This program is free software: you can redistribute it and/or modify

 * it under the terms of the GNU General Public License as published by

 * the Free Software Foundation, either version 3 of the License, or

 * (at your option) any later version.

 *

 * This program is distributed in the hope that it will be useful,

 * but WITHOUT ANY WARRANTY; without even the implied warranty of

 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

 * GNU General Public License for more details.

 *

 * You should have received a copy of the GNU General Public License

 * along with this program.  If not, see <http://www.gnu.org/licenses/>

 *

 *

 * This version may have been modified pursuant

 * to the GNU General Public License, and as distributed it includes or

 * is derivative of works licensed under the GNU General Public License or

 * other free or open source software licenses.

 * See LICENSE for more details.

 */





//don't allow other scripts to grab and execute our file

defined('_JEXEC') or die('Direct Access to this location is not allowed.');





$outputValue = "";

$this->_params = $params;

$this->location = $params->get('location', 'top');

$this->alignment = $params->get('alignment', 'right');

$this->show_credit = $params->get('showCredit', 'true');

$this->plusone = $params->get('plusone', 'true');

$this->plusone_size = $params->get('plusone_size', 'true');

$this->facebook = $params->get('facebook', 'true');

$this->twitter = $params->get('twitter', 'true');

$this->orkut = $params->get('orkut', 'true');

$this->bebo = $params->get('bebo', 'true');

$this->hi5 = $params->get('hi5', 'true');

$this->youtube = $params->get('youtube', 'true');

$this->google = $params->get('google', 'true');

$this->blinklist = $params->get('blinklist', 'true');

$this->flickr = $params->get('flickr', 'true');

$this->friendster = $params->get('friendster', 'true');

$this->lastfm = $params->get('lastfm', 'true');

$this->delicious = $params->get('delicious', 'true');

$this->digg = $params->get('digg', 'true');

$this->reddit = $params->get('reddit', 'true');

$this->stumbleupon = $params->get('stumbleupon', 'true');

$this->blogger = $params->get('blogger', 'true');

$this->tumblr = $params->get('tumblr', 'true');

$this->reader = $params->get('reader', 'true');

$this->feedburner = $params->get('feedburner', 'true');

$this->photobucket = $params->get('photobucket', 'true');

$this->picasa = $params->get('picasa', 'true');

$this->linkedin = $params->get('linkedin', 'true');

$this->ning = $params->get('ning', 'true');



$width = $params->get( 'width_ico', '' );

$height = $params->get( 'height_ico', '' );

$moutopacity = $params->get( 'mout_opacity_ico', '' );

$moveropacity = $params->get( 'mover_opacity_ico', '' );

if ($params->get('centered', 'true')) {

    $center= "<center>";

}











/* Custon Starts */



echo <<<EOT



<style type="text/css" media="all">



.custom_images a img {

    

	width="'.$width.'";

    height="'.$height. '";

	opacity:$moutopacity; 

}

.custom_images a:hover img {

    opacity: $moveropacity; 

}

</style>



   <div class="custom_images" align="center">

EOT;



	  if ($this->facebook){ echo ' <a title="Facebook" target="_blank" href="' . $params->get('fburl') . '"><img alt="Facebook" width="'.$width.'" height="'.$height. '" src="' . $params->get('fb_ico') . '" /></a>'; } 

	  if ($this->twitter)

    { echo ' <a title="Twitter" target="_blank" href="' . $params->get('twurl') . '"><img  alt="Twitter" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('tw_ico') . '" /></a>'; }	

	





if ($this->orkut)

{ echo ' <a title="Orkut" href="' . $params->get('orkurl') . '" rel="nofollow" target="_blank"><img  alt="Orkut" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('ork_ico') . '" /></a>'; }



if ($this->bebo)

{ echo ' <a title="Bebo" href="' . $params->get('beburl') . '" rel="nofollow" target="_blank"><img  alt="Bebo" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('beb_ico') . '" /></a>'; }



if ($this->hi5)

{ echo ' <a title="hi5" href="' . $params->get('hi5url') . '" rel="nofollow" target="_blank"><img  alt="hi5" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('hi5_ico') . '" /></a>'; }



if ($this->youtube)

{ echo ' <a title="Youtube" href="' . $params->get('youturl') . '" rel="nofollow" target="_blank"><img  alt="Youtube" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('yout_ico') . '" /></a>'; }





if ($this->google)

    { echo ' <a title="Google Bookmarks!" href="' . $params->get('googurl') . '" rel="nofollow" target="_blank"><img  alt="Google Bookmarks" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('goog_ico') . '" /></a>'; }

	

if ($this->blinklist)

    { echo ' <a title="Blinklist" href="' . $params->get('blinkgurl') . '" rel="nofollow" target="_blank"><img  alt="Blinklist" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('blink_ico') . '" /></a>'; }

	

	

if ($this->flickr)

    { echo ' <a title="Flickr" href="' . $params->get('flickgurl') . '" rel="nofollow" target="_blank"><img  alt="Flickr" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('flick_ico') . '" /></a>'; }	

	

if ($this->friendster)

    { echo ' <a title="Friendster" href="' . $params->get('friendurl') . '" rel="nofollow" target="_blank"><img  alt="Friendster" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('friend_ico') . '" /></a>'; }	

	

if ($this->lastfm)

    { echo ' <a title="Lastfm" href="' . $params->get('lstfgurl') . '" rel="nofollow" target="_blank"><img  alt="Lastfm" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('lstf_ico') . '" /></a>'; }		

	

if ($this->delicious)

    { echo ' <a title="Delicious" href="' . $params->get('delurl') . '"><img src="' . $params->get('del_ico') . '"  alt="Delicious" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" /></a>'; }		



if ($this->digg)

    { echo ' <a title="Digg" href="' . $params->get('diggurl') . '" rel="nofollow" target="_blank"><img  alt="Digg" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('digg_ico') . '" /></a>'; }

	

if ($this->reddit)

    { echo ' <a title="Reddit" href="' . $params->get('reddurl') . '" rel="nofollow" target="_blank"><img  alt="Reddit" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('redd_ico') . '" /></a>'; }	

	

if ($this->stumbleupon)

    { echo ' <a title="Stumbleupon" href="' . $params->get('stumburl') . '" rel="nofollow" target="_blank"><img  alt="Stumbleupon" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('stumb_ico') . '" /></a>'; }	

	

if ($this->blogger)

    { echo ' <a title="Blogger" href="' . $params->get('blogurl') . '" rel="nofollow" target="_blank"><img  alt="Blogger" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('blog_ico') . '" /></a>'; }		

	

if ($this->tumblr)

    { echo ' <a title="Tumblr" href="' . $params->get('tumburl') . '" rel="nofollow" target="_blank"><img  alt="Tumblr" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('tumb_ico') . '" /></a>'; }	

	

if ($this->reader)

    { echo ' <a title="Reader" href="' . $params->get('readerurl') . '" rel="nofollow" target="_blank"><img  alt="Reader" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('reader_ico') . '" /></a>'; }		

		

if ($this->feedburner)

    { echo ' <a title="Feedburner" href="' . $params->get('feedburl') . '" rel="nofollow" target="_blank"><img  alt="Feedburner" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('feedb_ico') . '" /></a>'; }	

	

if ($this->photobucket)

    { echo ' <a title="Photobucket" href="' . $params->get('photourl') . '" rel="nofollow" target="_blank"><img  alt="Photobucket" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('photo_ico') . '" /></a>'; }	

	

if ($this->picasa)

    { echo ' <a title="Picasa" href="' . $params->get('picasaurl') . '" rel="nofollow" target="_blank"><img  alt="Picasa" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('picasa_ico') . '" /></a>'; }	

	

if ($this->linkedin)

    { echo ' <a title="Linkedin" href="' . $params->get('linkdurl') . '" rel="nofollow" target="_blank"><img  alt="Linkedin" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('linkd_ico') . '" /></a>'; }						



if ($this->ning)

    { echo ' <a title="Ning" href="' . $params->get('ningurl') . '" rel="nofollow" target="_blank"><img  alt="Ning" width="'.$params->get('width_ico') . '" height="'.$params->get('height_ico') . '" src="' . $params->get('ning_ico') . '" /></a>'; }	



if ($this->plusone)

    { 

if ($this->plusone_size==1)
{
echo ' <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<g:plusone size="small"></g:plusone> '; 
}
if ($this->plusone_size==2)
{
echo ' <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<g:plusone size="medium"></g:plusone> '; 
}
if ($this->plusone_size==3)
{
echo ' <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<g:plusone></g:plusone> '; 
}
if ($this->plusone_size==4)
{
echo ' <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<g:plusone size="tall"></g:plusone> '; 
}

}	  

echo <<<EOT



</div>

EOT;



/* Custon Ends */




