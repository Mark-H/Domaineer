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
        // First get the host to compare to, and make sure it is lowercase.
        $host = strtolower($_SERVER['HTTP_HOST']);

        // If we are debugging, show the plugin triggered and for what host.
        if ($debug) { $modx->log(1,'Plugin triggered for '.$host); }

        // If the host starts with www. let's remove that to make it easier to match.
        if (substr($host,0,4) === 'www.') {
            $host = substr($host,4);
            // If we're debugging, make clear we rewrote the host var.
            if ($debug) { $modx->log(1,'Rewrote host to '.$host); }
        }

        // Lets see if we have an entry for the current host
        if ($scriptProperties[$host]) {
            // We found something, let's make that clear if we're debugging and output what we got.
            if ($debug) { $modx->log(1,'Properties found for '.$host.': '.print_r($scriptProperties[$host],true)); }

            // For easy access, put in $props var.
            $props = $scriptProperties[$host];
            // Let's make it into an associative array, based on JSON.
            $props = $modx->fromJSON($props);

            // If we're debugging, output the array we got based on the JSON - if the JSON is malformed this will show
            if ($debug) { $modx->log(1,'Properties from JSON: '.print_r($props,true)); }

            // Set up an empty array to put the placeholders to modify in.
            $phs = array();
            // Only if we have any properties of course
            if (count($props) > 0) {
                // Loop through the properties, and set them in the new $phs var as key => value.
                foreach ($props as $key => $value) {
                    $phs[$key] = $value;
                }
                // Set the placeholders
                $modx->setPlaceholders($phs);
            }
        }
        // We didn't find any properties for this host.
        else {
            // Let's add that in the error log if we're debugging.
            if ($debug) { $modx->log(1,'Props not found for '.$host.'. Properties: '.print_r($scriptProperties,true)); }
        }
    // We're done with this event.
    break;
}