<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/


defined('_JEXEC') or die('Restricted access');


$mainframe->registerEvent('onPrepareContent', 'plgContentCustomFlash');


function plgContentCustomFlash(&$row, &$params, $page=0)
{
	$cf=new plgCustomFlashClass;
	
	if (is_object($row)) {
		$count=0;
		$count+=$cf->plgCustomFlashByID($row->text, $params,true);
		$count+=$cf->plgCustomFlashByID($row->text, $params,false);
		return (bool)$count;
	}
	
	$count+=$cf->plgCustomFlash($row, $params,true);
	$count+=$cf->plgCustomFlash($row, $params,false);
	
	
	return (bool)$count;
}

class plgCustomFlashClass
{
	
	function plgCustomFlashByID(&$text, &$params,$byId)
	{
	
		$options=array();
		if($byId)
			$fList=$this->getListToReplace('customflashid',$options,$text);
		else
			$fList=$this->getListToReplace('customflash',$options,$text);
	
		if(count($fList)>0)
			require_once(JPATH_SITE.DS.'components'.DS.'com_customflash'.DS.'includes'.DS.'customflash.php');
		else
			return 0;	
	
		for($i=0; $i<count($fList);$i++)
		{
			$replaceWith=$this->getCustomFlash($options[$i],$i,$byId);
			$text=str_replace($fList[$i],$replaceWith,$text);	
		}
	
		return count($fList);
	}



	function getCustomFlash($movieparams,$count,$byId)
	{
		
		$result='';
	
		$cfc=new CustomFlashClass;
		
		if($byId)
			$movierow=$cfc->getMovieRowByID((int)$movieparams);
		else
			$movierow=$cfc->getMovieRowByName($movieparams);
			
		if($movierow->file!='')
		{
		
			$result=$movierow->file;
			
			$cfmovie=new CustomFlashMovie;

			$cfmovie->setFlashMovie($movierow);
			$cfmovie->objectid='CustomFlashPLG_'.$count;
			$result=$cfmovie->getFlashMovie();
		}
		return $result;
	
	}
	
	

	function getListToReplace($par,&$options,&$text)
	{
		$fList=array();
		$l=strlen($par)+2;
	
		$offset=0;
		do{
			if($offset>=strlen($text))
				break;
		
			$ps=strpos($text, '{'.$par.'=', $offset);
			if($ps===false)
				break;
		
		
			if($ps+$l>=strlen($text))
				break;
		
		$pe=strpos($text, '}', $ps+$l);
				
		if($pe===false)
			break;
		
		$notestr=substr($text,$ps,$pe-$ps+1);

			$options[]=substr($text,$ps+$l,$pe-$ps-$l);
			$fList[]=$notestr;
			

		$offset=$ps+$l;
		
			
		}while(!($pe===false));
		
		return $fList;
	}
	
}
?>