<?php
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
		if( empty( $exploded_url["scheme"] ) ) {
			$new_url["scheme"] = $scheme;
		} else {
			$new_url["scheme"] = $exploded_url["scheme"];
		}
		
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
		if( is_array( $exploded_base_domain ) > 1 ) {
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