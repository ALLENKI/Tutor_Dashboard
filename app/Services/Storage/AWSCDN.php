<?php

namespace Aham\Services\Storage;

use AWS;
use Config;

class AWSCDN implements CDNInterface
{
    /**
     * Upload a file to CDN.
     *
     * @author
     **/
    public function upload($data)
    {
        $result = array();

        $s3 = AWS::createClient('s3');

        $key = $data['key'];
        $source = $data['source'];

        try {
            $data = $s3->putObject(array(
                                'Bucket' => env('AWS_BUCKET'),
                                'Key' => $key,
                                'SourceFile' => $source,
                                'ACL' => 'public-read-write',
                                'CacheControl' => 'max-age=172800',
                            ));

            $result['success'] = 'success';
            $result['url'] = $data['ObjectURL'];

            // $s3->waitUntilObjectExists(array(
            //     'Bucket' => env('AWS_BUCKET'),
            //     'Key'    => $key
            // ));
        } catch (\Exception $e) {
            $result['success'] = 'error';
        }

        return $result;
    }

    /**
     * Get objects from CDN.
     *
     * @author
     **/
    public function get($prefix = '')
    {
        $s3 = AWS::get('s3');

        if ($prefix != '') {
            $prefix = $prefix.'/';
        }

        $iterator = $s3->getIterator('ListObjects', array(
            'Bucket' => env('AWS_BUCKET'),
            'Prefix' => $prefix,
        ));

        foreach ($iterator as $object) {
            $keys[] = $object['Key'];
        }

        return $keys;
    }

    /**
     * Description.
     *
     * @param string $prefix
     *
     * @return nothing
     */
    public function updateMetaData($prefix = '')
    {
        $s3 = AWS::get('s3');

        $bucket = env('AWS_BUCKET'); //Config::get('app.s3bucket');

        if ($prefix != '') {
            $prefix = $prefix.'/';
        }

        $iterator = $s3->getIterator('ListObjects', array(
            'Bucket' => $bucket,
            'Prefix' => $prefix,
        ));

        foreach ($iterator as $object) {
            $keys[] = $object['Key'];
        }

        foreach ($keys as $key) {
            $params = array(

                'ACL' => 'public-read-write',
                'CacheControl' => 'max-age=172800',
                'Bucket' => $bucket,
                'CopySource' => $bucket.'/'.$key,
                'Key' => $key,
                'MetadataDirective' => 'REPLACE',

            );

            $s3->copyObject($params);
        }

        dd($keys);
    }

    public function deleteItem($key)
    {
        $s3 = AWS::get('s3');

        try {
            $data = $s3->deleteObjects(array(
                                'Bucket' => env('AWS_BUCKET'),
                                'Objects' => array(array('Key' => $key)),
                            ));
        } catch (\Exception $e) {
            $result['success'] = 'error';
        }

        return true;
    }
}
