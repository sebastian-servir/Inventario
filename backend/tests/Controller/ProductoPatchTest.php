<?php

namespace App\Tests\Controller;

use App\Entity\Categoria;
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductoPatchTest extends WebTestCase
{
    private $browser;
    private EntityManagerInterface $em;
    private int $id;
    private int $categoriaId;

    protected function setUp(): void
    {
        $this->browser = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        self::assertSame('pdo_sqlite', $this->em->getConnection()->getParams()['driver']);
        self::assertTrue($this->em->getConnection()->getParams()['memory'] ?? false);
        (new SchemaTool($this->em))->createSchema($this->em->getMetadataFactory()->getAllMetadata());
        $categoria = (new Categoria())->setNombre('Prueba aislada');
        $producto = (new Producto())->setNombre('Teclado')->setCantidad('3')->setPrecio('100.00');
        $categoria->addProducto($producto);
        $this->em->persist($categoria);
        $this->em->persist($producto);
        $this->em->flush();
        $this->id = $producto->getId();
        $this->categoriaId = $categoria->getId();
        $this->em->clear();
    }

    public function testPrecioParcialConservaLosOtrosDatos(): void
    {
        $this->patch(['precio' => '150.00']);
        self::assertResponseStatusCodeSame(200);
        $data = $this->data();
        self::assertSame('Teclado', $data['nombre']);
        self::assertSame('3', $data['cantidad']);
        self::assertSame('150.00', $data['precio']);
        self::assertSame($this->categoriaId, $data['categoria'][0]['id']);
        self::assertArrayNotHasKey('productos', $data['categoria'][0]);
        $this->em->clear();
        self::assertSame(150.0, (float) $this->em->find(Producto::class, $this->id)->getPrecio());
    }

    public function testCantidadCeroEsValida(): void
    {
        $this->patch(['cantidad' => 0]);
        self::assertResponseStatusCodeSame(200);
        self::assertSame('0', $this->data()['cantidad']);
        self::assertSame('Teclado', $this->data()['nombre']);
    }

    public function testNullNoBorraElNombre(): void
    {
        $this->patch(['nombre' => null, 'precio' => '125.50']);
        self::assertResponseStatusCodeSame(200);
        self::assertSame('Teclado', $this->data()['nombre']);
    }

    public function testNombreVacioEsRechazado(): void
    {
        $this->patch(['nombre' => '   ']);
        self::assertResponseStatusCodeSame(422);
    }

    public function testCantidadNegativaEsRechazada(): void
    {
        $this->patch(['cantidad' => -1]);
        self::assertResponseStatusCodeSame(422);
    }

    public function testCategoriaInexistenteNoGuardaCambios(): void
    {
        $this->patch(['categoria' => 999999, 'nombre' => 'Otro']);
        self::assertResponseStatusCodeSame(404);
        $this->em->clear();
        self::assertSame('Teclado', $this->em->find(Producto::class, $this->id)->getNombre());
    }

    public function testPrecioConTresDecimalesEsRechazado(): void
    {
        $this->patch(['precio' => '100.123']);
        self::assertResponseStatusCodeSame(422);
    }

    private function patch(array $payload): void
    {
        $this->browser->jsonRequest('PATCH', '/api/productos/'.$this->id, $payload);
    }

    private function data(): array
    {
        return json_decode($this->browser->getResponse()->getContent(), true, flags: JSON_THROW_ON_ERROR);
    }
}
