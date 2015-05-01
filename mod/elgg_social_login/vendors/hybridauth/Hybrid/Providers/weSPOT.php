<?php
/**
 * Hybrid_Providers_weSPOT provider adapter based on OAuth2 protocol
 * 
 */
class Hybrid_Providers_weSPOT extends Hybrid_Provider_Model_weSPOT
{
	// default permissions 
	public $scope = "profile email";

	/**
	* IDp wrappers initializer 
	*/
	function initialize() 
	{
		parent::initialize();

        $this->api->authorize_url  = "https://wespot-arlearn.appspot.com/Login.html";
		$this->api->token_url      = "https://wespot-arlearn.appspot.com/oauth/token";
	}

	/**
	* begin login step 
	*/
	function loginBegin()
	{
		Hybrid_Auth::redirect( $this->api->authorizeUrl( array( "scope" => $this->scope ) ) ); 
	}

    
	/**
	* load the user profile from the IDp api client
	*/
	function getUserProfile()
	{
		// refresh tokens if needed
		$this->refreshToken();

		// ask wespot api for user infos
		$response = $this->api->api( "https://wespot-arlearn.appspot.com/oauth/resource_query" ); 

		if ( ! isset( $response->id ) || isset( $response->error ) ){
			throw new Exception( "User profile request failed! {$this->providerId} returned an invalide response.", 6 );
		}

		$this->user->profile->identifier    = @ $response->id;
		$this->user->profile->firstName     = @ $response->given_name;
		$this->user->profile->lastName      = @ $response->family_name;
		$this->user->profile->displayName   = @ $response->name;
		$this->user->profile->photoURL      = @ $response->picture;
		$this->user->profile->email         = @ $response->email;
		$this->user->profile->c = @ $response->email;

		return $this->user->profile;
	}
}