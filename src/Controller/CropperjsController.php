<?php

namespace App\Controller;

use Symfony\UX\Cropperjs\Form\CropperType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CropperjsController extends AbstractController
{
    #[Route('/cropperjs', name: 'app_cropperjs')]
    public function index(CropperInterface $cropper, Request $request): Response
    {
        $crop = $cropper->createCrop('/server/path/to/the/image.jpg');
        $crop->setCroppedMaxSize(2000, 1500);

        $form = $this->createFormBuilder(['crop' => $crop])
            ->add('crop', CropperType::class, [
                'public_url' => 'symfonyConf.jpg',
                'cropper_options' => [
                    'aspectRatio' => 2000 / 1500,
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the cropped image data (as a string)
            $crop->getCroppedImage();

            // Create a thumbnail of the cropped image (as a string)
            $crop->getCroppedThumbnail(200, 150);

            // ...
        }
        return $this->render('cropperjs/index.html.twig', [
            'controller_name' => 'CropperjsController',
            'form' => $form->createView(),
        ]);
    }
}
