<?php
/*
 * Domaineer
 *
 * Copyright 2010-2011 by Mark Hamstra (contact via www.markhamstra.nl)
 *
 * This file is part of Domaineer, a tool to set domain-specific settings/placeholders.
 *
 * Domaineer is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Domaineer is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Domaineer; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 */

$eventName = $modx->event->name;
switch($eventName) {
  case 'OnWebPageInit':
  $host = strtolower($_SERVER['HTTP_HOST']);
  if ($debug) { $modx->log(1,'Plugin triggered for '.$host); }
  if (substr($host,0,4) === 'www.') { $host = substr($host,4); if ($debug) { $modx->log(1,'Rewrote host to '.$host); } }

  if ($scriptProperties[$host]) {
    if ($debug) { $modx->log(1,'Properties found for '.$host.': '.print_r($scriptProperties[$host],true)); }
    $props = $scriptProperties[$host];
    $props = $modx->fromJSON($props);
    if ($debug) { $modx->log(1,'Properties from JSON: '.print_r($props,true)); }
    $phs = array();
    if (count($props) > 0) {
      foreach ($props as $key => $value) {
	$phs[$key] = $value;
      }
      $modx->setPlaceholders($phs);
    }
  } else {
    if ($debug) { $modx->log(1,'Props not found for '.$host.'. Properties: '.print_r($scriptProperties,true)); }
  }

  break;
}