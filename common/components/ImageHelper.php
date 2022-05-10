<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\imagine\Image;
use Imagine\Image\Box;
use yii\helpers\FileHelper;

/**
 * 
 * Component for Yii2 Framework to manipulate the uploaded image and show in front-end
 * 
 * @author S.Eyvazi <saman3yvazi@gmail.com>
 * 
 */

class ImageHelper extends Component
{

    /**
     *  fetch image from protected location and manipulate it and copy to public folder to show in front-end
     *  This function cache the fetched image with same width and height before
     *
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     *
     * @param string $path original image path
     * @param float $width width of image for resize
     * @param float $heigh height of image for resize
     * @param integer $quality quality of output image
     * @return string fetched image url
     */
    public function getImage($path, $width, $heigh, $quality = 70)
    {
        $path = Yii::getAlias($path);
        $fileName = $this->getFileName($path);
        $fileNameWithoutExt = $this->getFileNameWithoutExtension($fileName);
        $ext = strtolower($this->getFileExtension($fileName));
        
        try
        {

            $newFileName = $fileNameWithoutExt."-w{$width}h{$heigh}q{$quality}.{$ext}";

            $savePath = Yii::getAlias("@static/images-cache");
            $baseImageUrl = Yii::$app->params['staticUrl'] . "/images-cache";
            FileHelper::createDirectory($savePath, 0755, true);
            $savePath .= "/{$newFileName}";
            if (!file_exists($savePath)) {
                
                $image = Image::getImagine()->open($path);
                $size = $image->getSize();
                if ($width <= 0 || $width > $size->getWidth()) {
                    $width = $size->getWidth();
                }
                if ($heigh <= 0 || $heigh > $size->getHeight()) {
                    $heigh = $size->getHeight();
                }
                
                if ($ext == 'jpg' || $ext == 'png')
                {
                    $image->thumbnail(new Box($width, $heigh))
                            ->interlace(\Imagine\Image\ImageInterface::INTERLACE_PLANE)
                            ->save($savePath, ['quality' => $quality]);
                }
                else
                {
                    $image->thumbnail(new Box($width, $heigh))                       
                            ->save($savePath, ['quality' => $quality]);
                }
            }

            return $baseImageUrl.'/'.$newFileName;
            
        } catch (\Exception $ex) {
            Yii::error($ex, 'Image Resize');
            return null;
        }
    }

    /**
     * extract file name from path
     *
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     *
     * @param string $name file path
     * @return string
     */
    public function getFileName($name)
    {
        return basename($name);
    }

    /**
     * get file name extension
     *
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     *
     * @param string $name file name
     * @return string
     */
    public function getFileExtension($name)
    {
        return pathinfo($name, PATHINFO_EXTENSION);
    }

    /**
     * get file name without extension
     *
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     *
     * @param string $name file name
     * @return string
     */
    public function getFileNameWithoutExtension($name)
    {
        $ext = $this->getFileExtension($name);
        return basename($name, ".{$ext}");
    }
}

