<?php declare(strict_types=1);

namespace Images\App;

use Gregwar\Image\Image;
use \Exception as Exception;
use \InvalidArgumentException;

/**
 * Class ImageHandler
 * @package Images\App
 * @todo check permissions for read/write
 */
class ImageHandler
{
    public ValidatorHandler $validator;
    public string $uploads = APP_IMAGE_UPLOAD_PATH;
    public string $assetsBasePath = APP_ASSETS_BASE_PATH;
    public $sizeMinWidth = 100;
    public $sizeMinHeight = 100;
    public $newImageName;

    public function __construct()
    {
        $this->validator = new ValidatorHandler();
    }


    public function beforeAction($params)
    {
        /* List of required parameters */
        $requiredFields = ['filename', 'width', 'height', 'title', 'mode'];
        foreach ($requiredFields as $requiredFieldLabel) {
            $this->getValidator()->required($requiredFieldLabel, $params);
        }
    }

    public function handleRequest($params)
    {
        $this->beforeAction($params);
        $imageHandler = $this->create($params);
        $redirectPage = APP_ASSETS_PATH . $imageHandler;
        header('Location: ' . $redirectPage);
        exit;
    }


    public function create($params)
    {
        $imageName = $params['filename'];
        $imagePath = sprintf('%s%s', $this->getUploads(), $imageName);

        /*check if file exist on our storage; */
        $this->getValidator()->pathFileExists($imageName, $imagePath);

        $width = (int)$params['width'];
        $this->getValidator()->integer($width)->minValue('width', $width, $this->sizeMinWidth);

        $height = (int)$params['height'];
        $this->getValidator()->integer($height)->minValue('height', $height, $this->sizeMinHeight);


        $imageTitle = $params['title'];
        $this->getValidator()->minChar('title', $imageTitle, '5');
        /* @Param 'title' set net new filename */

        $imageName = $this->slugify($imageTitle);
        $imageExtension = $this->getExtension($imagePath);
        $this->setNewImageName(sprintf("%s.%s", $imageName, $imageExtension));
        $newImagePath = sprintf("%s%s", $this->getAssetsBasePath(), $this->getNewImageName());

        $mode = $params['mode'];

        try {
            $imageHandler = Image::open($imagePath);
            /*suppress some deprecated warnings*/

            ob_start();  // turns on output buffering

            switch ($mode) {
                case 'resize':
                {
                    $imageHandler->resize($width, $height);
                    break;
                }
                case 'crop':
                {
                    $imageHandler->crop(0, 0, $width, $height);
                    break;
                }
                default:
                    throw new InvalidArgumentException("unknown mode");
                    break;
                /* add more options....*/
            }
            /* clean the output buffer */

            $imageHandler->save($newImagePath);
            $none = ob_get_contents();  // buffer content is now an empty string
            ob_end_clean();  // turn off output buffering


        } catch (Exception) {
            /*some dummy fail message */
            echo "Unable to process the request";
            exit;
        }

        return $this->getNewImageName();
    }

    /**
     * @return mixed
     */
    public function getNewImageName()
    {
        return $this->newImageName;
    }

    /**
     * @param mixed $newImageName
     */
    public function setNewImageName($newImageName): void
    {
        $this->newImageName = $newImageName;
    }


    /**
     * @return string
     */
    public function getAssetsBasePath(): string
    {
        return $this->assetsBasePath;
    }

    /**
     * @param string $assetsBasePath
     */
    public function setAssetsBasePath(string $assetsBasePath): void
    {
        $this->assetsBasePath = $assetsBasePath;
    }

    /**
     * @return int
     */
    public function getSizeMinWidth(): int
    {
        return $this->sizeMinWidth;
    }

    /**
     * @param int $sizeMinWidth
     */
    public function setSizeMinWidth(int $sizeMinWidth): void
    {
        $this->sizeMinWidth = $sizeMinWidth;
    }

    /**
     * @return int
     */
    public function getSizeMinHeight(): int
    {
        return $this->sizeMinHeight;
    }

    /**
     * @param int $sizeMinHeight
     */
    public function setSizeMinHeight(int $sizeMinHeight): void
    {
        $this->sizeMinHeight = $sizeMinHeight;
    }

    /**
     * @return string
     */
    public function getUploads(): string
    {
        return $this->uploads;
    }

    /**
     * @param string $uploads
     */
    public function setUploads(string $uploads): void
    {
        $this->uploads = $uploads;
    }

    /**
     * @return ValidatorHandler
     */
    public function getValidator(): ValidatorHandler
    {
        return $this->validator;
    }

    /**
     * @param ValidatorHandler $validator
     */
    public function setValidator(ValidatorHandler $validator): void
    {
        $this->validator = $validator;
    }

    /*
     * @Params 'filename' required
     * 'width' and 'height' are not required
     * */


    public function getExtension($imagePath): string
    {
        /*skipp testing since we are sure that the file exist;*/
        /*.jpg and .JPG are both valid */
        if (!is_string($imagePath)) {
            throw new InvalidArgumentException("invalid image path");
        }
        $extension = explode(".", $imagePath);
        $extension = end($extension);
        $allowedExtensions = ['png', 'jpg', 'webp', 'gif'];

        if (!in_array(strtolower($extension), $allowedExtensions)) {
            throw new InvalidArgumentException("unknown file extension");
        }
        return $extension;

    }

    public function slugify($string): string
    {
        if (!$string) {
            return '';
        }
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }
}
