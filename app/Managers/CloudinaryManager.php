<?php namespace App\Managers;

use Input;
use File;

class CloudinaryManager {


	public function __construct()
	{
		\Cloudinary::config(array( 
		  "cloud_name" => env('CLOUDINARY_CLOUD_NAME'), 
		  "api_key" => env('CLOUDINARY_API_KEY'), 
		  "api_secret" => env('CLOUDINARY_API_SECRET')
		));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function deleteResources()
	{
		$api = new \Cloudinary\Api();

		$resources = $api->resources(['max_results' => 100]);

		$list = [];

		foreach($resources['resources'] as $index => $resource)
		{
			$list[] = $resource['public_id'];
		}

		$report = $api->delete_resources($list);

		return true;

	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function deletePicture($id)
	{
    	$api = new \Cloudinary\Api();

		$api->delete_resources(array($id));

		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function uploadPicture($slug)
	{
        $formFile = Input::file('picture');

        $extension = $formFile->getClientOriginalExtension();

        $filename = $slug.'-'.time().'.'.$extension;

        $upload_success = $formFile->move(storage_path().'/uploads', $filename);

        $result = \Cloudinary\Uploader::upload(storage_path().'/uploads/'.$filename);

        dd($result);

        return true;
	}
}