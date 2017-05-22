<?php

namespace Webaccess\BiometLaravel\Services;

use OpenCloud\OpenStack;

class OVHObjectStorage
{
    private $url = "https://auth.cloud.ovh.net/v2.0/";
    private $client;
    private $region;
    private $container;

    public function __construct()
    {
        $this->client = new OpenStack($this->url, array(
            'username' => env('OVH_OBJECT_STORAGE_USERNAME'),
            'password' => env('OVH_OBJECT_STORAGE_PASSWORD'),
            'tenantId' => env('OVH_OBJECT_STORAGE_TENANT_ID'),
        ));
        $this->region = env('OVH_OBJECT_STORAGE_REGION');
    }

    private function getContainer($containerName)
    {
        if (!$this->container){
            $this->container = $this->client->objectStoreService('swift', $this->region, 'publicURL')->getContainer($containerName);
        }

        return $this->container;
    }

    public function getFileTemporaryURL($containerName, $file)
    {
        return $this->getContainer($containerName)->getObject($file)->getTemporaryUrl(60, 'GET');
    }

    public function uploadFileToContainer($containerName, $file, $filename = null)
    {
        $getPath = null;
        $isString = is_string($file);
        if ($isString)
            $getPath = $file;
        else
            $getPath = $file->getRealPath();

        if ($filename == null) {
            if ($isString) {
                $explodePath = explode("/", $file);
                $filename = $explodePath[count($explodePath)-1];
            } else {
                $filename = $file->getClientOriginalName();
            }
        }
        $this->getContainer($containerName)->uploadObject($filename, fopen($getPath, 'r'));
    }
}