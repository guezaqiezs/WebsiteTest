<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/



defined('_JEXEC') or die('Restricted access');


?>


<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (trim(form.file.value) == "") {
			alert( "<?php echo JText::_( 'You Must Provide a File Name.', true ); ?>" );
			return false
		}
		if (trim(form.moviename.value) == "") {
			alert( "<?php echo JText::_( 'You Must Provide a Movie Name.', true ); ?>" );
			return false
		}
		
		submitform( pressbutton );
		return true;
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	
	<fieldset class="adminform">
		<div style="position: relative;">
		<div style="position: absolute; right:0">
		<?php
		echo '<a href="http://www.designcompasscorp.com/index.php?option=com_content&view=article&id=508&Itemid=709" target="_blank"><img src="../components/com_customflash/images/compasslogo.png" border=0></a>';
		?>
		</div>
		<legend><?php echo JText::_( 'Custom Flash Movie Details' ); ?></legend>
			<table class="admintable" cellspacing="1" width="100%">

				<?php if($this->row->id!=0):?>
				<tr>
					<td width="150" class="key">
						<label for="id">
							<?php echo JText::_( 'ID' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php echo $this->row->id; ?>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td width="150" class="key">
						<label for="moviename">
							<?php echo JText::_( 'MOVIE NAME' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="moviename" id="moviename" class="inputbox" size="40" value="<?php echo $this->row->moviename; ?>" />
						Some text string to associate this settings with, "My_First_Movie" for example. a-z, A-Z, 0-9, _ alowed
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="file">
							<?php echo JText::_( 'File Path' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="file" id="file" class="inputbox" size="40" value="<?php echo $this->row->file; ?>" />
						example: images/flash/thefile.swf
					</td>
				</tr>
				
								
				<tr>
					<td width="150" class="key">
						<label for="width">
							<?php echo JText::_( 'Width' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="width" id="width" class="inputbox" size="40" value="<?php echo $this->row->width; ?>" />
						px
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="height">
							<?php echo JText::_( 'Height' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="height" id="height" class="inputbox" size="40" value="<?php echo $this->row->height; ?>" />
						px
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="wmode">
							<?php echo JText::_( 'Window Mode' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php
							$wmodelist=array();
							$wmodelist[]=array(name=>JText::_( 'Opaque Windowless' ), value=>"opaque");
							$wmodelist[]=array(name=>JText::_( 'Transparent Windowless' ), value=>"transparent");
							
							echo JHTML::_('select.genericlist', $wmodelist, 'wmode', '' ,'value','name', $this->row->wmode);
						 ?>
						
					</td>
				</tr>
				
				
				<tr>
					<td width="150" class="key">
						<label for="quality">
							<?php echo JText::_( 'Quality' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php
							$qualitylist=array();
							$qualitylist[]=array(name=>JText::_( 'High (default)' ), value=>"high");
							$qualitylist[]=array(name=>JText::_( 'Auto Low' ), value=>"autolow");
							$qualitylist[]=array(name=>JText::_( 'Auto High' ), value=>"autohigh");
							$qualitylist[]=array(name=>JText::_( 'Medium' ), value=>"medium");
							$qualitylist[]=array(name=>JText::_( 'Best' ), value=>"best");
							
							echo JHTML::_('select.genericlist', $qualitylist, 'quality', '' ,'value','name', $this->row->quality);
						 ?>
						
					</td>
				</tr>
		
				
				
				<tr>
					<td width="150" class="key">
						<label for="play">
							<?php echo JText::_( 'Paused at start' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php
							$playlist=array();
							$playlist[]=array(name=>JText::_( 'No' ), value=>"1");
							$playlist[]=array(name=>JText::_( 'Yes' ), value=>"0");
							
							
							echo JHTML::_('select.genericlist', $playlist, 'play', '' ,'value','name', $this->row->play);
						 ?>
						
					</td>
				</tr>
				
				
				
				<tr>
					<td width="150" class="key">
						<label for="scale">
							<?php echo JText::_( 'Scale' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php
							$scalelist=array();
							$scalelist[]=array(name=>JText::_( 'No Scale' ), value=>"noscale");
							$scalelist[]=array(name=>JText::_( 'No Border' ), value=>"noborder");
							$scalelist[]=array(name=>JText::_( 'Exact Fit' ), value=>"exactfit");
							
							
							echo JHTML::_('select.genericlist', $scalelist, 'scale', '' ,'value','name', $this->row->scale);
						 ?>
						
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="menu">
							<?php echo JText::_( 'Menu' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php
							$playlist=array();
							$playlist[]=array(name=>JText::_( 'No' ), value=>"0");
							$playlist[]=array(name=>JText::_( 'Yes' ), value=>"1");
							
							
							
							echo JHTML::_('select.genericlist', $playlist, 'menu', '' ,'value','name', $this->row->menu);
						 ?>
						

					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="checkflashavailability">
							<?php echo JText::_( 'Check Flash Player' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php
							$playlist=array();
							$playlist[]=array(name=>JText::_( 'Yes' ), value=>"1");
							$playlist[]=array(name=>JText::_( 'No' ), value=>"0");
							
							
							echo JHTML::_('select.genericlist', $playlist, 'checkflashavailability', '' ,'value','name', $this->row->checkflashavailability);
						 ?>
						
					</td>
				</tr>
				
				
				
				
				
				<tr>
					<td width="150" class="key">
						<label for="flashvars">
							<?php echo JText::_( 'Flashvars' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="flashvars" id="flashvars" class="inputbox" size="80" value="<?php echo $this->row->flashvars; ?>" />
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="alternativehtml">
							<?php echo JText::_( 'Alternative Html' ); ?>
							
						</label><br>
					</td>
					<td>
						<textarea cols=60 rows=20 name="alternativehtml" id="alternativehtml" class="inputbox" ><?php echo $this->row->alternativehtml; ?></textarea>
						Show this HTML code if there is no Flash Player.
					</td>
				</tr> 
				
				<tr>
					<td width="150" class="key">
						<label for="alternativeimage">
							<?php echo JText::_( 'Alternative Image' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="alternativeimage" id="alternativeimage" class="inputbox" size="40" value="<?php echo $this->row->alternativeimage; ?>" />
						Show this image if there is no Flash Player and no alternative HTML.
					</td>
				</tr>  
				 
		
		<tr>
					<td width="150" class="key">
						<label for="bgcolor">
							<?php echo JText::_( 'Background color' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="bgcolor" id="bgcolor" class="inputbox" size="40" value="<?php echo $this->row->bgcolor; ?>" />
						by default: 000000
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="style">
							<?php echo JText::_( 'CSS Style' ); ?>
							
						</label><br>
					</td>
					<td>
						
						<textarea cols=60 rows=5 name="style" id="style" class="inputbox" ><?php echo $this->row->style; ?></textarea>
						<br>
						EXAMPLE 1:  border: solid 1px #ff0000; //to set black solid border<br>
						EXAMPLE 2:  margin-left:auto; margin-right: auto; //to align it to the center<br>
					</td>
				</tr>
				
				
				<tr>
					<td width="150" class="key">
						<label for="cssclass">
							<?php echo JText::_( 'CSS Class' ); ?>
							
						</label><br>
					</td>
					<td>
						
						<input type="text" name="cssclass" id="cssclass" class="inputbox" size="40" value="<?php echo $this->row->cssclass; ?>" />
						
						To have even move control over position and staff.
					</td>
				</tr>
				 
				 
				<tr>
					<td width="150" class="key">
						<label for="alternativehtml">
							Parameter List<br>(alternative)
							
						</label><br>
					</td>
					<td>
						<textarea cols=60 rows=20 name="paramlist" id="paramlist" class="inputbox" ><?php echo $this->row->paramlist; ?></textarea>
						One per line. <br>Example: allowFullScreen=true
					</td>
				</tr> 
				
			</table>
		</div>
	</fieldset>
</div>
	<input type="hidden" name="option" value="com_customflash" />
	<input type="hidden" name="controller" value="flashmovies" />
	
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="task" value="" />


</form>