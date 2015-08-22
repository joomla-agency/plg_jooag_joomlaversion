<?php
/**
 * @package 	JooAg JoomlaVersion
 * @version 	3.0.0 Beta
 * @for 	Joomla 3.3+ 
 * @author 	Joomla Agentur - http://www.joomla-agentur.de
 * @copyright 	Copyright (c) 2009 - 2015 Joomla-Agentur All rights reserved.
 * @license 	GNU General Public License version 2 or later;
 * @description A small to Display the Joomla Version
 */
defined( '_JEXEC' ) or die;
class plgContentJooag_JoomlaVersion extends JPlugin
{
	public function onContentPrepare($context, &$article, &$params, $page=0 )
	{	
		if ( JString::strpos( $article->text, '{joomlaversion}' ) === false ) {
			return true;
		}
		
		$article->text = preg_replace_callback( '#{joomlaversion}(.*?){/joomlaversion}#s', array($this,'plgJoomlaVersionOutput'), $article->text );
		
		if(!empty($article->introtext)){
			$article->introtext = preg_replace_callback( $regex, array($this,'plgJoomlaVersionOutput'), $article->introtext );
		}	
	}
		
	protected function plgJoomlaVersionOutput ($matches) 
	{
		$matches = explode(".", $matches[1]); 
		$versions = json_decode($this->params->def('versionarray'));
		
		foreach($matches as $key => $match)
		{
			if($match == 'last'){
				
				foreach($versions as $keyValue => $version)
				{
					$versionValues[$keyValue] = $version[$key];
				}
	
				$match = end($versionValues);
			}
			
			foreach($versions as $index => $version)
			{				
				if($version[$key] != $match)
				{
					unset($versions[$index]);
				}
			}

		}
		
		$html = '';
		$versions = reset($versions);
		
		foreach($versions as $keyVersion => $version)
		{
			$html .= $version;
			if($keyVersion == $key)
			{
				break;
			}
			else
			{
				$html .= '.';
			}
		}
		
		return $html;
	}
}