<?php
require_once '../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class File
{
    protected $storageClient;
    protected $bucket;
    protected $adapter;
    protected $filesystem;

    public function __construct()
    {
        $this->storageClient = new StorageClient([
            'projectId' => 'cool3c-analytics',
        ]);
        $this->bucket = $this->storageClient->bucket('lineq');

        $this->adapter = new GoogleStorageAdapter($this->storageClient, $this->bucket);

        $this->filesystem = new Filesystem($this->adapter);
    }

    public function write($path,$content)
    {
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
}