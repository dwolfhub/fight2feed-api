<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\Entity\MediaObject;
use App\Entity\User;
use App\Form\MediaObjectType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateMediaObjectAction
 * @package App\Controller
 */
final class CreateMediaObjectAction
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @var Security
     */
    private $security;

    /**
     * CreateMediaObjectAction constructor.
     *
     * @param Security $security
     * @param RegistryInterface $doctrine
     * @param FormFactoryInterface $factory
     * @param ValidatorInterface $validator
     */
    public function __construct(
        Security $security,
        RegistryInterface $doctrine,
        FormFactoryInterface $factory,
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->factory = $factory;
        $this->security = $security;
    }

    /**
     * @param Request $request
     *
     * @return MediaObject
     */
    public function __invoke(Request $request): MediaObject
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new UnauthorizedHttpException('Unauthorized');
        }

        $mediaObject = new MediaObject();

        $form = $this->factory->create(MediaObjectType::class, $mediaObject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $mediaObject->setCreator($user);
            $em->persist($mediaObject);
            $em->flush();

            // Prevent the serialization of the file property
            $mediaObject->file = null;

            return $mediaObject;
        }

        // This will be handled by API Platform and returns a validation error.
        throw new ValidationException($this->validator->validate($mediaObject));
    }
}