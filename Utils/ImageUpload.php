<?php

trait ImageUpload
{
    public $imageUploadPath = '/upload/images/';
    public $tmpPath = '/tmp/';
    public $types = ['image/gif', 'image/png', 'image/jpeg'];
    public $size = 1024000;
    public $maxSizeX = 320;
    public $maxSizeY = 240;
    public $imageFuncs = [
        'image/jpeg' => 'imagejpg',
        'image/png' => 'imagepng',
        'image/gif' => 'imagegif'
    ];

    /**
     * @return bool
     */
    public function isValidTypes($image)
    {
        return in_array($image['type'], $this->types);
    }

    /**
     * @return bool
     */
    public function isValidSize($image)
    {
        return $image['size'] <= $this->size;
    }

    /**
     * @param $type
     * @param $name
     * @return resource
     */
    public function getSource($type, $name)
    {
        switch ($type) {
            case 'image/png':
                $source = imagecreatefrompng($name);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($name);
                break;
            case 'image/jpeg':
                $source = imagecreatefromjpeg($name);
                break;
            default:
                $source = imagecreatefromjpeg($name);

        }
        return $source;
    }

    /**
     * Get image function according to file extension
     * @param $type
     * @return string
     */
    public function getImageFunc($type)
    {
        if (isset($this->imageFuncs[$type])){
            switch ($type)
            {
                case 'jpeg':
                    return 'imagejpeg';
                    break;
                
                case 'jpg':
                    return 'imagejpeg';
                    break;

                case 'png':
                    return 'ImagePNG';
                    break;

                case 'gif':
                    return 'ImageGIF';
                    break;

                default:
                    return 'imagejpeg';
                    //return false;
            }
        }
    }

    /**
     * Resize image and retrun new name
     * @param $file
     * @return string $newName
     */
    public function resize($file)
    {
        //Get image function for creating image
        $imageFunc = $this->getImageFunc($file['type']);        
        $source = $this->getSource($file['type'], $file['tmp_name']);
        //get size of image
        $width = imagesx($source);
        $height = imagesy($source);
        //get new image name
        $newName = $this->getNewName($file['name']);
        
        //if width and height id valid then resize image
        if ($width > $this->maxSizeX || $height > $this->maxSizeY) {
            $dest = imagecreatetruecolor($this->maxSizeX, $this->maxSizeY);
            imagecopyresampled($dest, $source, 0, 0, 0, 0, $this->maxSizeX, $this->maxSizeY, $width, $height);
            $imageFunc($dest, $this->tmpPath . $newName);
            imagedestroy($dest);
        } else {
            $imageFunc($source, $this->tmpPath . $newName);
        }
        imagedestroy($source);
        return $newName;
    }

    /**
     * Get new name for an image
     * @param $name
     * @return string
     */
    public function getNewName($name)
    {
        $exploded = explode('.', $name);        
        return implode('.', [Comments::getLastCommentId(), end($exploded)]);
        
    }

    /**
     * Copy ready image from temp folder to image folder
     * @param $name
     */
    public function copy($name)
    {
        if (!copy($this->tmpPath . $name, ROOT.$this->imageUploadPath . $name)) {
            echo 'copy error';
        }
        unlink($this->tmpPath . $name);
    }

    /**
     * Upload file on a server and return file name
     * @param $image
     * @return mixed
     */
    public function upload($image)
    {
        if ($this->isValidSize($image) && $this->isValidTypes($image)) {
            $newImageName = $this->resize($image);
            $this->copy($newImageName);
            return $newImageName;
        }
        return false;        
    }
}
