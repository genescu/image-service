<?php declare(strict_types=1);

namespace Images\App;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

include(__DIR__ . "/../html/app.php");

class ImageHandlerTest extends TestCase
{

    public function fixtures($offset)
    {
        $fixture = [
            0 => ['filename' => 'image.jpg', 'width' => 200, 'height' => 200, 'title' => 'new york central park', 'mode' => 'resize'],
            1 => ['filename' => 'image.JPG', 'width' => 200, 'height' => 200, 'title' => 'new central park 2 ', 'mode' => 'resize'],
            2 => ['filename' => 'image.', 'width' => 10, 'height' => 0, 'title' => 'new central park4 ', 'mode' => 'crop'],
            3 => ['filename' => 'image.MAC', 'width' => 10, 'height' => '', 'title' => '', 'mode' => 'crop'],
        ];
        return $fixture[$offset];
    }

    public function testCreateImage()
    {
        $fixture = $this->fixtures(0);
        $handler = new ImageHandler();
        $handler->setUploads(__DIR__ . '/uploads/');
        $handler->setAssetsBasePath(__DIR__ . '/assets/');
        $handler->create($fixture);

        $imageCreated = $handler->getAssetsBasePath() . '/' . $handler->getNewImageName();
        $size = getimagesize($imageCreated);
        $this->assertEquals(200, $size[0]);
        $this->assertEquals(200, $size[1]);


    }

    public function testWidthCannotBeUsed()
    {
        $fixture = $this->fixtures(2);
        $handler = new ImageHandler();
        $this->expectException(InvalidArgumentException::class);
        $handler->getExtension($fixture['width']);
    }

    public function testHeightCannotBeUsed()
    {
        $fixture = $this->fixtures(2);
        $handler = new ImageHandler();
        $this->expectException(InvalidArgumentException::class);
        $handler->getExtension($fixture['height']);
    }

    public function testExtensionCanBeUsed()
    {
        $handler = new ImageHandler();

        $fixture = $this->fixtures(0);
        $this->assertEquals('jpg', $handler->getExtension($fixture['filename']));

        $fixture = $this->fixtures(1);
        $this->assertEquals('JPG', $handler->getExtension($fixture['filename']));
    }

    public function testExtensionCannotBeUsed()
    {
        $fixture = $this->fixtures(3);
        $handler = new ImageHandler();
        $this->expectException(InvalidArgumentException::class);
        $handler->getExtension($fixture['filename']);
    }

    public function testFilenameSlugify()
    {
        $fixture = $this->fixtures(0);
        $handler = new ImageHandler();
        $this->assertEquals($handler->slugify($fixture['title']), 'new-york-central-park');
    }
}
