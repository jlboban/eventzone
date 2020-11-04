<?php


namespace App\Service;

use Imagick;
use ImagickException;

/**
 * Class ThumbnailGenerator
 * @package App\Service
 */
class ThumbnailGenerator
{
    /**
     * @param int $resolution
     * @param string $filePath
     * @param string $thumbnailPath
     * @throws ImagickException
     */
    public function createThumbnail(int $resolution, string $filePath, string $thumbnailPath): void
    {
        $thumbnail = new Imagick($filePath);

        // Resizes to whichever is larger, width or height
        if($thumbnail->getImageHeight() <= $thumbnail->getImageWidth())
        {
            // Resize image based on X
            $thumbnail->resizeImage($resolution, 0, Imagick::FILTER_LANCZOS, 1);
        }
        else
        {
            // Resize image based on Y
            $thumbnail->resizeImage(0, $resolution, Imagick::FILTER_LANCZOS, 1);
        }

        // Set compression level (1 lowest quality, 100 highest quality)
        $thumbnail->setImageCompressionQuality(50);

        // Remove meta data
        $thumbnail->stripImage();
        $thumbnail->writeImage($thumbnailPath);
        $thumbnail->destroy();
    }
}
