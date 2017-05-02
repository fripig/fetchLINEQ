<?php
namespace App;

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class GCSFile
{
    protected $storageClient;
    protected $bucket;
    protected $adapter;
    protected $filesystem;
    protected $file_temp = null;

    public function __construct()
    {
        $this->storageClient = new StorageClient([
            'projectId' => 'cool3c-analytics',
        ]);
        $this->bucket = $this->storageClient->bucket('lineq');

        $this->adapter = new GoogleStorageAdapter($this->storageClient, $this->bucket);

        $this->filesystem = new Filesystem($this->adapter);
    }

    public function write($path,$content = null)
    {
        if(empty($content)){
            $content = $this->file_temp;
        }
        return $this->filesystem->write($path, $content);
    }

    public function update($path,$content)
    {
        return $this->filesystem->update($path, $content);
    }

    public function writeOnce($path,$content)
    {
        if(!$this->filesystem->has($path)){
            return $this->filesystem->write($path, $content);
        }

        return 0;
    }

    public function fetchImg($fromUrl)
    {


            try {
                $client = new \GuzzleHttp\Client();
                $response = $client->get('http://lineq.tw'.$fromUrl);
                $this->file_temp = $response->getBody()->getContents();
                return $this;
            } catch (Exception $e) {
                // Log the error or something
                $this->file_temp = false;
                return false;
            }

    }
}