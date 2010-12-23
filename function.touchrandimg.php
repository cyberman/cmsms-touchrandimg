<?php
/**
 * $Id$
 *
 * touchRandImg Plugin
 *
 * Copyright (c) 2010 touchDesign, <www.touchdesign.de>
 *
 * @category Plugin
 * @author Christoph Gruber <www.touchdesign.de>
 * @version 1.1
 * @copyright touchDesign 28.08.2010
 * @link http://www.touchdesign.de/
 * @link http://www.homepage-community.de/index.php?topic=1705
 * @link http://dev.cmsmadesimple.org/projects/touchrandimg
 * @license http://www.gnu.org/licenses/licenses.html#GPL GNU General Public License (GPL 2.0)
 * 
 * --
 *
 * Usage: 
 *
 * {touchrandimg}
 *
 * --
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
 *
 */

function smarty_function_touchrandimg($params, &$smarty) {
  global $gCms;

  $config = &$gCms->config;

  // Grep params
  $folder = !empty($params['folder'])
    ? $params['folder'] : "touchrandimg";

  $maxImg = !empty($params['max_img']) 
    ? (int)$params['max_img'] : 1;

  $linkMap = !empty($params['link_map']) 
    ? linkmap2array(explode(",",$params['link_map'])) : NULL;

  $linkTarget = !empty($params['link_target']) 
    ? explode(",",$params['link_target']) : "_self";

  // Set path and url
  $touchRandImgPath = $config['uploads_path'] . DIRECTORY_SEPARATOR 
    . $folder . DIRECTORY_SEPARATOR;
  $touchRandImgUrl = $config['uploads_url'] . DIRECTORY_SEPARATOR 
    . $folder . DIRECTORY_SEPARATOR;

  if(!is_dir($touchRandImgPath)){
    mkdir($touchRandImgPath);
  }

  // Load image files
  $touchRandImgFiles = scandir($touchRandImgPath);

  $c=0; $html=""; shuffle($touchRandImgFiles);
  foreach($touchRandImgFiles AS $img){
    if(!is_file($touchRandImgPath . $img)){
      continue;
    }

    $name = array_shift(explode(".",$img,2));
    $src = $touchRandImgUrl . $img;

    if(isset($linkMap[$img])){
      $html .= "<a class=\"touchRandImgLink\" target=\"$linkTarget\" href=\"" . $linkMap[$img]['url'] . "\">";
    }  

    $html .= "<img class=\"touchRandImg\" src=\"$src\" alt=\"$img\" title=\"$name\" />";

    if(isset($linkMap[$img])){
      $html .= "</a>";
    }

    $c++;
    if($c >= $maxImg){
      break; 
    }
  }

  if($c == 0){
    return $touchRandImgPath . " has no images to randomize...";
  }

  return "\n<!-- touchRandImg plugin -->\n" . $html . "\n<!-- /touchRandImg plugin -->\n";
}

function linkmap2array($map=array()){
  $resultMap=array();
  foreach($map AS $k => $v){
    $tmp = explode(":",$v,2);
    $resultMap[$tmp[0]] = array(
      'url' => $tmp[1]
    );  
  }

  return $resultMap;
}

function smarty_cms_help_function_touchrandimg() {

  print "<h3>Usage</h3>";
  print "<ul>";
  print "  <li>Basic: {touchrandimg}</li>";
  print "  <li>Display 3 images: {touchrandimg max_img=3}</li>";
  print "  <li>Use links: {touchrandimg link_map='imagename01.png:http://www.cmsmadesimple.org,imagename02.png:http://www.touchdesign.de'}</li>";
  print "  <li>Use link target: {touchrandimg link_map='imagename01.png:http://www.cmsmadesimple.org' link_target='_blank'}</li>";
  print "</ul>";

  print "<h3>Params</h3>";
  print "<ul>";
  print "  <li><em>(optional)</em> folder - Image folder relative to uploads path (default is uploads/touchrandimg/)</li>";
  print "  <li><em>(optional)</em> max_img - Maximum images to display (default is 1)</li>";
  print "  <li><em>(optional)</em> link_map - imagename01.png:http://www.cmsmadesimple.org,imagename02.png:http://www.touchdesign.de... (default none)</li>";    
  print "  <li><em>(optional)</em> link_target - Set target attribute _blank, _self or what ever (default is _self)</li>";
  print "</ul>";

  smarty_cms_about_function_touchrandimg();
}

function smarty_cms_about_function_touchrandimg() {

  print "<h3>About</h3>";
  print "<ul>";
  print "  <li>Copyright by <a href=\"http://www.touchdesign.de/\">touchDesign</a></li>";
  print "  <li>Author Christoph Gruber</li>";
  print "  <li>Support via <a href=\"http://www.homepage-community.de/index.php?topic=1705\">HPC</a></li>";
  print "  <li>License GPL 2.0</li>";
  print "  <li>Version 1.0</li>";
  print "</ul>";
}

?>