<?php
/**
 * Created by PhpStorm.
 * User: mteixeira
 * Date: 8/7/14
 * Time: 9:52 AM
 */

namespace Anuncios\Model;

use Zend\File\Transfer\Adapter;

class ImageUpload
{
    protected $images;
    protected $imagesFilter = [];
    const SIZE = 1048576;
    protected $imageNamesForSave =[];

    /**
     * constructor para upload de imagens
     * @param array $images
     */
    public function __construct(array $images)
    {
        $this->images = $images;
        $this->excludeImagesEmpty();
    }

    /**
     * Excluir do array as imagens que sao enviadas vazias
     */
    public function excludeImagesEmpty()
    {
        foreach($this->images as $key => $image) {
            if (!empty($image[$key]['images']['name'])) {
                $this->imagesFilter[] =  $image[$key]['images'];
            }
        }
    }

    /**
     * Validar a imagem
     * @return array|bool
     */
    public function validateImage()
    {
       if (!empty($this->imagesFilter)) {
           foreach($this->imagesFilter as $image) {
               $path_info = pathinfo($image['name']);
               if (!$this->isValidFileType($path_info['extension'])) {
                   return array('error' => 'Alguma das imagens inseridos não são validas - Tipos permitidos jpg, jpeg, png, gif!');
               }

               if ($image['size'] > self::SIZE || empty($image['size'])) {
                   return array('error' => 'Alguma das imagens inseridos não são simplesmente imagens. A imagem tem de ser inferior a 1MB!');
               }
           }
       }
       return true;
    }

    /**
     * Fazer o upload
     * @return bool
     */
    public function uploadImages()
    {
        $pathToUpload =ROOT_PATH . '/public/data/uploads/';
        foreach($this->imagesFilter as $image) {
            $this->functionCreateImage($pathToUpload, $image);
        }
        return true;
    }

    /**
     * Criar uma imagem
     * @param $pathToUpload
     * @param $image
     */
    public function  functionCreateImage($pathToUpload, $image)
    {
        $img = $image['tmp_name'];
        $name = $this->generateImageName();
        $dst = $pathToUpload . $name;
        $saved = $pathToUpload.'resize/'.$name.'.png';
        $img_info = getimagesize($img);
        $width = $img_info[0];
        $height = $img_info[1];
        switch ($img_info[2]) {
            case IMAGETYPE_GIF  : $src = imagecreatefromgif($img);  break;
            case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($img); break;
            case IMAGETYPE_PNG  : $src = imagecreatefrompng($img);  break;
            default : $src = imagecreatefromjpeg($img);              break;
        }
        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
        $dest =  $dst.".png";
        imagepng($tmp, $dest);
        $this->addWatermarksSexoJa($dest, $pathToUpload);
        $this->resizeImagePlaceHolder($dest, $saved);
    }

    /**
     * Adicionionar a nossa marca
     * @param $imageSaved
     * @param $pathToUpload
     */
    public function addWatermarksSexoJa($imageSaved, $pathToUpload)
    {
        $watermark = $pathToUpload . 'watermark.png';
        // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefrompng($watermark);
        $im = imagecreatefrompng($imageSaved);

        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        // Copy the stamp image onto our photo using the margin offsets and the photo
        // width to calculate positioning of the stamp.
        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0,
            imagesx($stamp), imagesy($stamp));
        // Output and free memory
        imagepng($im, $imageSaved);
        imagedestroy($im);
    }

    public function resizeImagePlaceHolder($source, $save)
    {
        $source = imagecreatefrompng($source);
        $o_w = imagesx($source);
        $o_h = imagesy($source);
        $w = 270;
        $h = 270;
        $newImg = imagecreatetruecolor($w, $h);
        imagealphablending($newImg, false);
        imagesavealpha($newImg,true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $w, $h, $transparent);
        imagecopyresampled($newImg, $source, 0, 0, 0, 0, $w, $h, $o_w, $o_h);
        imagepng($newImg, $save);
        imagedestroy($newImg);
    }

    /**
     * Gerar o nome da imagem
     * @return string
     */
    public function  generateImageName()
    {
        $name = substr( base_convert( time(), 10, 36 ) . md5( microtime() ), 0, 16 );
        $this->imageNamesForSave [] = $name;
        return $name;
    }

    /**
     * Verificar se a imagem é valida
     * @param $ext
     * @return boo
     */
    private function isValidFileType($ext)
    {
        $ext = strtolower($ext);
        return in_array($ext, array('jpg', 'jpeg', 'png', 'gif'));
    }
    /**
     * @param mixed $imageNamesForSave
     */
    public function setImageNamesForSave($imageNamesForSave)
    {
        $this->imageNamesForSave = $imageNamesForSave;
    }

    /**
     * @return mixed
     */
    public function getImageNamesForSave()
    {
        return $this->imageNamesForSave;
    }

    /**
     * @param array $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $imagesFilter
     */
    public function setImagesFilter($imagesFilter)
    {
        $this->imagesFilter = $imagesFilter;
    }

    /**
     * @return mixed
     */
    public function getImagesFilter()
    {
        return $this->imagesFilter;
    }
}