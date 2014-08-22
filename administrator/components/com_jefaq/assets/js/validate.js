/**
 * jeFAQ package
 * @version 1.1
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

function elementvalidate(id)
{
	if(document.getElementById(id).value != '') {
		document.getElementById(id+'-error').innerHTML = '';
	} else {
		document.getElementById(id+'-error').style.color = 'red';
	}

}

function submitbutton(pressbutton)
{
	var condition  = pressbutton;

	// Operation for save & apply...
	if (condition == 'save' || condition == 'apply') {

		var tables       = document.getElementsByTagName('input');
		var toggletables = [];
		for (i in tables) {
			if (tables[i].id) {
				var requ  = tables[i].className;
				var value = document.getElementById(tables[i].id).value;

				// Required Field...
				if(requ == 'required') {
					if (value == '') {
						var inputerror 				= document.getElementById(tables[i].id+'-error');
						inputerror.style.color 		= 'red';
						inputerror.style.fontWeight = 'bold';
						inputerror.innerHTML 		= 'Please Enter the question(s)';

						return false;
					}
				}
			}
		}

		// Getting the contents or values from the editor(any editor)..
		var editor = editorContent();
		var text   = editor;

		if (text == '') {
			var txtareaerror 				= document.getElementById('answers-error');
			txtareaerror.style.color 		= 'red';
			txtareaerror.style.fontWeight = 'bold';
			txtareaerror.innerHTML 		= 'Please Enter the Answer(s)';

			return false;
		} else {
			document.getElementById('answers-error').innerHTML = '';
		}
		submitform(pressbutton);
		return true;
	}

	// Operations for except save and apply...
	if(condition != 'save' && condition != 'apply') {
		 submitform(pressbutton);
	}
}