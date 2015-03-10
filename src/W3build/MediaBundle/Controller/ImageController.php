<?php

namespace W3build\MediaBundle\Controller;

use Symfony\Bundle\AsseticBundle\DependencyInjection\AsseticExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\TwigBundle\Extension\AssetsExtension;
use Symfony\Component\HttpFoundation\Request;
use W3build\MediaBundle\Entity\Image;
use W3build\MediaBundle\Service\Image\ImageInterface;

class ImageController extends Controller
{

    private function resize(Request $request, ImageInterface $image){
        /** @var \W3build\MediaBundle\Service\Image $imageService */
        $imageService = $this->get('media.image')->setImage($image);

        $crop = false;
        $height = $request->get('height');
        $width = $request->get('width');
        $cropX = $request->get('crop-x');
        $cropY = $request->get('crop-y');
        $pixelRatio = $request->get('pixel-ratio', 1);
        if($cropX || $cropX){
            $crop = true;
        }

        $resizedImage = $imageService->resize($width, $height, $crop, $cropX, $cropY, $pixelRatio);
        header("Content-Type: " . image_type_to_mime_type($image->getImageType()));
        echo file_get_contents($resizedImage);
        exit;
    }

    /**
     * @Route("/image/{id}", name="route.media.image")
     * @Template()
     */
    public function resizeAction(Request $request, Image $image)
    {
        $this->resize($request, $image);
    }

    /**
     * @Route("/image/asset/", name="route.media.asset")
     * @Template()
     */
    public function assetResizeAction(Request $request){
        $asset = $request->get('asset');
        $imageAssetService = $this->get('media.image.asset');

        $image = $imageAssetService->setAsset($asset);

        $this->resize($request, $image);
    }
}
