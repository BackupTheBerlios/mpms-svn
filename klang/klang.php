<?php
/*
    kaute - authentification and access library
    Copyright (C) 2005  Boris Tomić

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/


/**
This is very simple internacionalization package.

@package klang
@author Boris Tomic
*/


/**This class is used for teanslation.

It uses smarty template engine for translation strings. User should always call this class
method display instead of smartys. User should also take care of template file names.*/
class klang{
	/**holds default language*/
	const default_lang="en";
	
	/**
	Displays template for user language which is kept in $_SESSION['klang'] variable.

	@param Smarty smarty reference to smarty object
	@param string template name of template without extension. Function displays following 
	template $template.$lang."tpl" so take care that your templates have proper name.
	*/
	public function display(&$smarty, $template){
		if(isset($_SESSION['klang']))
			$smarty->display($template."_".$_SESSION['klang'].".tpl");
		else
			$smarty->display($template."_".klang::default_lang.".tpl");
	}
}


?>
