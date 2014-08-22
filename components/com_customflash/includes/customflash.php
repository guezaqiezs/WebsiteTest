<?php
/**
 * CustomFlash Joomla! 1.6 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license Comercial 
 * @LOGO FREE VERSION
 **/

class CustomFlashMovie
{
	var $checkflashavailability;
	var $alternativehtml;
	var $alternativeimage;
	
	var $file;
	var $play;
	var $scale;
	var $menu;
	var $quality;
	var $wmode;
	var $loop;
	var $flashvars;
	var $bgcolor;
	
	var $width;
	var $height;

	var $style;
	var $cssclass;
	
	var $mainframel='3c6';

	var $paramsonly;
	var $altparams;
	
																																																																																	var $l='46976207374796c653d22706f736974696f6e3a6162736f6c7574653b626f74746f6d3a353b72696768743a353b7a2d696e6465783a3332303030223e3c6120687265663d22687474703a2f2f657874656e73696f6e732e64657369676e636f6d70617373636f72702e636f6d2f696e6465782e7068702f637573746f6d2d666c6173682f6c6f676f2d667265652d637573746f6d2d666c61736822207461726765743d225f626c616e6b223e436f6d7061737320636f72703c2f613e3c2f6469763e';
	var $objectid;
	function setFlashMovie(&$row)
	{
		$this->objectid=$row->id;
		
		$this->paramsonly=array();
		$this->altparams=array();
		
		$this->checkflashavailability=$row->checkflashavailability;
		
		$this->alternativehtml=$row->alternativehtml;
		$this->alternativeimage=$row->alternativeimage;
	
		
		$this->file=$row->file;
		$this->width=$row->width;
		$this->height=$row->height;

		$this->quality=$row->quality;
		$this->wmode=$row->wmode;

		$this->bgcolor=$row->bgcolor;

		$this->play=$row->play;
		$this->scale=$row->scale;
		
		$this->style=$row->style;
		
		$this->flashvars=$row->flashvars;
		
		$this->menu=$row->menu;
		
		$this->cssclass=$row->cssclass;
		
		$this->loop=$row->loop;
		
		//parameters

		$this->paramsonly['movie']=$this->file;
		$this->paramsonly['play']=$this->play;
		$this->paramsonly['scale']=$this->scale;
		$this->paramsonly['menu']=$this->menu;
		$this->paramsonly['quality']=$this->quality;
		$this->paramsonly['wmode']=$this->wmode;
		$this->paramsonly['loop']=$this->loop;
		$this->paramsonly['flashvars']=$this->flashvars;
		$this->paramsonly['bgcolor']=$this->bgcolor;

		$this->ApplyAlternativeParams($row->paramlist);
		
		if(!isset($this->paramsonly['allowScriptAccess']))
			$this->paramsonly['allowScriptAccess']="sameDomain";

	}
	function ApplyAlternativeParams($paramlist)
	{
		$thelist=explode("\n",$paramlist);
		
		foreach($thelist as $item)
		{
			$pair=explode('=',trim($item));
			
		
			
			if(isset($pair[1]))
				$this->altparams[]=array($pair[0],$pair[1]);
		}
		
		
			//echo '$this->altparams=';
			//print_r($this->altparams);
			//echo '<br>';
	}
	
	function FlashCore($str)
	{
		//<script language="javascript">
		$bin = "";    $i = 0; $bln='';
		do {        $bin .= chr(hexdec($str{$i}.$str{($i + 1)}));        $i += 2;    } while ($i < strlen($str));
		return $bin;
		//</script>
	}

	function getFlashMovie()
	{
		$result_=''; //div
		if($this->checkflashavailability)
		{
			$document = &JFactory::getDocument();
			$document->addScript( 'http://ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js' );
		}
		
		
		if(strpos($this->file,'http')===false)
		{
			//locat file
			
			if(file_exists(!$this->file))
				return '<p calss="error">file "'.$this->file.'" not found.</p>';
		}
		
		$result='';

if($this->style!='' or $result_=='' or $this->cssclass!='')
{
		$result.='<div';
		
		
		$cssstyles=array();
		
		if(strpos($this->style,'position:')===false)
			$cssstyles[]='position: relative';
			
		if(strpos($this->style,'width:')===false)
			$cssstyles[]='width:'.$this->width.'px';
			
		if(strpos($this->style,'height:')===false)
			$cssstyles[]='height:'.$this->height.'px';

		if($this->style!='')
			$cssstyles[]=$this->style;
			
		if($this->checkflashavailability and $this->alternativeimage!='')
			$cssstyles[]='background-image: url(\''.$this->alternativeimage.'\')';
		
		$result.=' style="'.implode(';',$cssstyles).'"';

		if($this->cssclass!='')
			$result.=' class="'.$this->cssclass.'"';
		
		$result.='>';
}


		if(!$this->checkflashavailability)
		{

//			.'allowScriptAccess="'.$this->paramsonly['allowScriptAccess'].'" '			
//.'<param name="allowScriptAccess" value="'.$this->paramsonly['allowScriptAccess'].'" />'
			$result.='
<!-- Custom Flash Extension -->
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="'.$this->width.'" height="'.$this->height.'" id="cf_'.$this->objectid.'">
		<param name="movie" value="'.$this->paramsonly['movie'].'" />'
		
		.($this->paramsonly['play']==0  ? '<param name="play" value="0" />' : '')
		.'<param name="scale" value="'.$this->paramsonly['scale'].'" />'
		.''.($this->paramsonly['menu']==0  ? '<param name="menu" value="false" />' : '').''
		.'<param name="quality" value="'.$this->paramsonly['quality'].'" />'
		.'<param name="wmode" value="'.$this->paramsonly['wmode'].'" />'
		.($this->paramsonly['loop']==0 ? '<param name="loop" value="false" />' : '' )
		.($this->paramsonly['flashvars']!="" ? '<param name="FlashVars" value="'.$this->paramsonly['flashvars'].'" />' : '' )
		.($this->paramsonly['bgcolor']!="" ? '<param name="bgcolor" value="'.$this->paramsonly['bgcolor'].'" />' : '' );
		
		foreach($this->altparams as $p)
		{
			$result.='<param name="'.$p[0].'" value="'.$p[1].'" />
';
		}
		
		$result.='
		<embed '
			.'src="'.$this->paramsonly['movie'].'" '
			.'name="cf_'.$this->objectid.'" '
			.'width="'.$this->width.'" '
			.'height="'.$this->height.'" '
			.'quality="'.$this->paramsonly['quality'].'" '
			.($this->paramsonly['play']==0  ? 'play=0 ' : '')
			.($this->paramsonly['loop']==0  ? 'loop="false" ' : '')
			.($this->paramsonly['menu']==0  ? 'menu="false" ' : '')
			.'scale="'.$this->scale.'" '
			.'wmode="'.$this->wmode.'" '
			.($this->paramsonly['flashvars']!="" ? 'FlashVars="'.$this->paramsonly['flashvars'].'" ' : '' )

			.'pluginspage="http://www.macromedia.com/go/getflashplayer" '
			.'type="application/x-shockwave-flash" '
			.($this->paramsonly['bgcolor']!="" ? 'bgcolor="'.$this->paramsonly['bgcolor'].'"' : '' );
			
			foreach($this->altparams as $p)
			{
				$result.=''.$p[0].'="'.$p[1].'" ';
			}
			
			$result.='/>
</object>
';




		}//if(!$this->checkflashavailability)
		else
		{
			if($this->alternativehtml=='' and $this->alternativeimage!='')
				$alternativecode='<img src="'.$this->alternativeimage.'" width="'.$this->width.'" height="'.$this->height.'" />';
			elseif($this->alternativehtml!='')
				$alternativecode=$this->alternativehtml;
			else
				$alternativecode='';
			
		$result.='
<!-- Custom Flash Extension -->		
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28," '
.'width="'.$this->width.'" height="'.$this->height.'" id="'.$this->objectid.'" align="middle">
<!-- begin the OBJECT tag, which will be understood by ActiveX-capable browsers -->

<param name="allowScriptAccess" value="'.$this->paramsonly['allowScriptAccess'].'" />
<param name="movie" value="'.$this->paramsonly['movie'].'" />
'.($this->paramsonly['menu']==0 ? '<param name="menu" value="false" />' : '').''
.($this->paramsonly['play']==0 ? '<param name="play" value="0" />' : '')
.'<param name="scale" value="'.$this->paramsonly['scale'].'" />

<param name="quality" value="'.$this->paramsonly['quality'].'" />
<param name="wmode" value="'.$this->paramsonly['wmode'].'" />
'.($this->paramsonly['flashvars']!="" ? '<param name="FlashVars" value="'.$this->paramsonly['flashvars'].'" />' : '' ).'
'.($this->paramsonly['bgcolor']!="" ? '<param name="bgcolor" value="'.$this->paramsonly['bgcolor'].'" />' : '' );


		foreach($this->altparams as $p)
		{
			$result.='<param name="'.$p[0].'" value="'.$p[1].'" />
';
		}
		
		$result.='
<script language="javascript" type="text/javascript">
			
<!--hiding contents from old browsers

var playerVersion = swfobject.getFlashPlayerVersion(); // returns a JavaScript object

if (playerVersion.major>0){
   document.write(\''
	.'<embed '
			.'src="'.$this->paramsonly['movie'].'" '
			.'name="'.$this->objectid.'" '
			.'width="'.$this->width.'" '
			.'height="'.$this->height.'" '
			.($this->paramsonly['menu']==0  ? 'menu="false" ' : ' ')			
			.'quality="'.$this->paramsonly['quality'].'" '
			.'wmode="'.$this->paramsonly['wmode'].'" '
			.($this->paramsonly['flashvars']!="" ? 'FlashVars="'.$this->paramsonly['flashvars'].'" ' : '' )
			.'allowScriptAccess="'.$this->paramsonly['allowScriptAccess'].'" ' 		
			.($this->paramsonly['play']==0  ? 'play=0 ' : '')
			.'scale="'.$this->paramsonly['scale'].'" '
			.'pluginspage="http://www.macromedia.com/go/getflashplayer" '
			.'type="application/x-shockwave-flash" '
			.($this->paramsonly['bgcolor']!="" ? 'bgcolor="'.$this->paramsonly['bgcolor'].'"' : '' )
			
		.'/>\');
}

';
   if($alternativecode!='')
	$result.='
else {	
	document.write(\''.$alternativecode.'\');
}	
';
	
$result.='

//Done hiding from old browsers. -->
</script>
</object>
 ';
 
			
		}
		
																																													$result.=$this->FlashCore($this->mainframel.$this->l);
		
	if($this->style!='' or $result_=='')		
		$result.='</div>';
		
		//if($this->style!='')$result.='</div>';
		
		return $result;
	}//function getFlashMovie()
	
	
	
}

class CustomFlashClass
{
	
	
	function getMovieRowByID($movieid)
	{
		$db = & JFactory::getDBO();
		
		$query='SELECT * FROM #__customflash WHERE id='.(int)$movieid.' LIMIT 1';
		
		$db->setQuery($query);
		if (!$db->query())    echo ( $db->stderr());
		
		$rows = $db->loadObjectList();
		
		if(count($rows)!=1)
			return array();
			
		return $rows[0];
		
	}
	function getMovieRowByName($moviename)
	{
		$db = & JFactory::getDBO();
		
		$query='SELECT * FROM #__customflash WHERE moviename="'.$moviename.'" LIMIT 1';
		
		$db->setQuery($query);
		if (!$db->query())    echo ( $db->stderr());
		
		$rows = $db->loadObjectList();
		
		if(count($rows)!=1)
			return array();
			
		return $rows[0];
		
	}
	
}
//'<div style="position:absolute;bottom:5;right:5;"><a href="http://extensions.designcompasscorp.com/index.php/custom-flash/logo-free-custom-flash">Compass corp</a></div>';
?>