<?php
/* 
Plugin Name: Use Any Font
Plugin URI: http://dineshkarki.com.np/use-any-font
Description: Embed any font in your website
Author: Dinesh Karki
Version: 4.9.2
Author URI: http://www.dineshkarki.com.np
*/

/*  Copyright 2012  Dinesh Karki  (email : dnesskarki@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
include('plugin_interface.php');
register_activation_hook( __FILE__, 'uaf_activate' );
?>