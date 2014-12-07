<?php
/**
 * @package 	JooAg JoomlaVersion
 * @version 	3.x.0 Beta
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
		// Performance Check
		if ( JString::strpos( $article->text, '{joomlaversion}' ) === false ) {
			return true;
		}
		
		// Regular expression
		$regex = "#{joomlaversion}(.*?){/joomlaversion}#s";

		// Replacement of {joomlaversion}xxx{/joomlaversion}
		$article->text = preg_replace_callback( $regex, array(&$this,'plgJoomlaVersionOutput'), $article->text );
		
		if(!empty($article->introtext)){
			$article->introtext = preg_replace_callback( $regex, array(&$this,'plgJoomlaVersionOutput'), $article->introtext );
		}	
	}
		
	protected function plgJoomlaVersionOutput ( &$matches) 
	{	
		$output = '';
		$versionMatrix = $this->params->def('versionarray');
		$versions = explode("|", $versionMatrix);
		
		$matches = explode(".", $matches[1]);

		
		//Latest Joomla Version
		if($matches[0] == 'last' and $matches[1] == 'last'){
			$arrayIndex = max($versions);
		}
		
		//Latest Major Version
		if($matches[0] != 'last' and $matches[1] == 'last'){
			$key = 0;
			foreach($versions as $version){
				if($version[0] == $matches[0]){
					$versionss[$key] = $version;
					$key++;
				}
			}
			$arrayIndex = end($versionss);
		}
		
		//Individual
		if($matches[0] != 'last' and $matches[1] != 'last'){
			$key = 0;
			foreach($versions as $version){
				if(($version[0] == $matches[0]) and ($version[2] == $matches[1])){
					$versionss[$key] = $version;
					$key++;
				}
			}
			$arrayIndex = end($versionss);
		}
		
		$arrayIndex = explode(".", $arrayIndex);
		
		//Anzahl der Stellen
		if(empty($matches[2])){
			$matches[2] = '3';
		}
		
		//Output
		if($matches[2] >= '1'){
			$output = $arrayIndex[0];
		}
		if($matches[2] >= '2'){
			$output .= '.'.$arrayIndex[1];
		}
		if($matches[2] >= '3'){
			$output .= '.'.$arrayIndex[2];
		}

		return $output;
	}
}