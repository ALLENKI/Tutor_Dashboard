<?php

namespace Aham\Services\Storage;

interface CDNInterface
{
    /**
     * Upload a file to CDN.
     *
     * @author
     **/
    public function upload($data);

    /**
     * Get objects from CDN.
     *
     * @author
     **/
    public function get();
}
