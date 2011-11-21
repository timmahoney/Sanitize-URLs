<?php
/**
 * Copyright 2011 Tim Mahoney (email : tim@timothymahoney.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Use this function to make URLs more standard for insertion into your database. 
 * That way, when you search, you can ensure standardization.
 * 
 * @access public 
 * @param string, string
 * @return string
 */

	function sanitize_url( $url, $scheme="http" ) {
	    $new_url = array();
		
		$exploded_url = parse_url( $url );
		// Set the scheme. Default is HTTP
		$new_url["scheme"] = ( empty( $exploded_url["scheme"] ) ? $scheme : $exploded_url["scheme"] );
		
		// If host is not set
		if( empty( $exploded_url["host"] ) ) {
			// If path is not set - url is not good. Send it back.
			if( empty( $exploded_url["path"] ) ) {
				return false;
			// Path is set, host is not. Path = Host
			} else {
				//There might be other data in the path
				$exploded_base_domain = explode( "/", $exploded_url["path"] );
			}
		// Host is set. 
		} else {
			$exploded_base_domain = $exploded_url["host"];
		}
		
		// Take care of the main domain
		if( is_array( $exploded_base_domain ) ) {
			$domain_only = array_shift( $exploded_base_domain );
			$new_url["domain_path"] = implode( "/", $exploded_base_domain );
		} else {
			$domain_only = $exploded_base_domain;
			$new_url["domain_path"] = ( ! empty( $exploded_url["path"] ) ? substr( $exploded_url["path"], 1 ) : "" );
		}
		
		//Check for subdomain?
		$exploded_host = explode( ".", $domain_only );
		$new_url["domain"] = ( count( $exploded_host ) == 2 ? "www.".$domain_only : $domain_only );
		
		// Take care of the query string
		$new_url["query"] = ( ! empty( $exploded_url["query"] ) ? "?".$exploded_url["query"] : "" );
		
		// Take care of the fragment (anything after the #)
		$new_url["fragment"] = ( ! empty( $exploded_url["fragment"] ) ? "#".$exploded_url["fragment"] : "" );
		
		$url = $new_url["scheme"]."://".$new_url["domain"]."/".$new_url["domain_path"].$new_url["query"].$new_url["fragment"];
		return $url;
	}

/* End of file sanitize-urls.php */