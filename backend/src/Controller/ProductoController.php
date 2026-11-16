<?php

namespace App\Controller;

use App\Dtos\ActualizarProductoDto;
use App\Dtos\CrearProductoDto;
use App\Entity\Producto;
use App\Repository\CategoriaRepository;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;


#[Route("/api/productos", name: "productos")]
final class ProductoController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductoRepository $productoRepository,
        private ObjectMapperInterface $mapper,
        private CategoriaRepository $categoria
    )
    {}

    #[Route("", name: "list", methods: ["GET"])]
    public function list()
    {
         $productos = $this->productoRepository->findAll();

         return $this->json($productos, Response::HTTP_OK, [], ["ignored_attributes"=>["productos"]]);
    }

    #[Route("/{id}", name: "show", methods: ["GET"])]
    public function show(?Producto $producto)
    {
        if (!$producto) {
            return $this->json(["error"=>"not found"], Response::HTTP_OK);
        }

        return $this->json($producto, Response::HTTP_OK, [], ["ignored_attributes"=>["productos"]]);
    }

    #[Route("", name: "create", methods: ["POST"])]
    public function create(
        #[MapRequestPayload] CrearProductoDto $dto,

    )
    {

        $categoria = $this->categoria->find($dto->categoria);

        if (!$categoria) {
            throw $this->createNotFoundException("La categoria no existe");
        }

        $producto = $this->mapper->map($dto, Producto::class);
        $categoria->addProducto($producto);

        $this->em->persist($producto);
        $this->em->flush();

        return $this->json($producto, Response::HTTP_CREATED, [], ['ignored_attributes' => ['productos']]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(
        ?Producto $producto,
        #[MapRequestPayload] ActualizarProductoDto $dto,
    ) {
        if (!$producto) {
            throw $this->createNotFoundException('El producto no existe');
        }

        $categoria = null;
        if ($dto->categoria !== null) {
            $categoria = $this->categoria->find($dto->categoria);
            if (!$categoria) {
                throw $this->createNotFoundException('La categoría no existe');
            }
        }

        $this->mapper->map($dto, $producto);
        if ($categoria !== null) {
            $categoria->addProducto($producto);
        }

        $this->em->flush();

        return $this->json(
            $producto,
            Response::HTTP_OK,
            [],
            ['ignored_attributes' => ['productos']],
        );
    }

    #[Route("/{id}", name: "delete", methods: ["DELETE"])]
    public function delete(?Producto $producto)
    {
        if (!$producto) {
            return $this->json(["error"=>"not found"], Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($producto);
        $this->em->flush();

        return $this->json("no content", Response::HTTP_NO_CONTENT);
    }

}