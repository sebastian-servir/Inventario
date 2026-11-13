<?php

namespace App\Controller;

use App\Dtos\ActualizarCategotiaDto;
use App\Dtos\CrearCategoriaDto;
use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api/categorias", name: "categorias")]
final class CategoriaController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoriaRepository $categoriaRepository,
        private ValidatorInterface $validator,
        private ObjectMapperInterface $mapper,
    )
    {
    }

    #[Route("", name: "list", methods: ["GET"])]
    public function list()
    {
        $categorias = $this->categoriaRepository->findAll();

        return $this->json($categorias, Response::HTTP_OK, [], ["ignored_attributes"=>["producto"]]);
    }

    #[Route("/{id}", name: "show", methods: ["GET"])]
    public function show(?Categoria $categoria)
    {
        if (!$categoria) {
            return $this->json(["error"=>"not found"], Response::HTTP_NOT_FOUND);
        }

        return $this->json($categoria, Response::HTTP_OK);
    }

    #[Route("", name: "create", methods: ["POST"])]
    public function create(
        Request $request, 
        #[MapRequestPayload] CrearCategoriaDto $dto
        )
    {
        $error = $this->validator->validate($dto);

        if (count($error) > 0) {
            return $this->json(["error", "bad request"], Response::HTTP_BAD_REQUEST);
        }

        $categoria = $this->mapper->map($dto, Categoria::class);

        $this->em->persist($categoria);
        $this->em->flush();

        return $this->json($categoria, Response::HTTP_CREATED);
    }

    #[Route("/{id}", name: "update", methods: ["PUT", "PATCH"])]
    public function update(
        ?Categoria $categoria, 
        Request $request,
        #[MapRequestPayload] ActualizarCategotiaDto $dto,
        )
    {
        if (!$categoria) {
            return $this->json(["error", "not found"], Response::HTTP_NOT_FOUND);
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json(["error"=>"bad request"], Response::HTTP_BAD_REQUEST);
        } 

        $this->mapper->map($dto, $categoria);

        $this->em->persist($categoria);
        $this->em->flush();

        return $this->json($categoria);
    }

    #[Route("/{id}", name: "delete", methods: ["DELETE"])]
    public function delete(?Categoria $categoria)
    {
        if (!$categoria) {
            return $this->json(["error"=>"not found"], Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($categoria);
        $this->em->flush();

        return $this->json("no content", Response::HTTP_NO_CONTENT);
    }

}
