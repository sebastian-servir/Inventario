<?php

namespace App\Dtos;

use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

class ActualizarProductoDto
{
    #[Map(if: false)]
    #[Assert\Positive(message: 'El ID de categoría debe ser positivo')]
    public ?int $categoria = null;

    #[Map(if: [self::class, 'tieneValor'])]
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim', message: 'El nombre no puede quedar vacío')]
    #[Assert\Length(max: 255)]
    public ?string $nombre = null;

    #[Map(if: [self::class, 'tieneValor'], transform: [self::class, 'cantidadComoTexto'])]
    #[Assert\PositiveOrZero(message: 'La cantidad no puede ser negativa')]
    public ?int $cantidad = null;

    #[Map(if: [self::class, 'tieneValor'])]
    #[Assert\NotBlank(allowNull: true, message: 'El precio no puede quedar vacío')]
    #[Assert\Regex(pattern: '/^\d{1,8}(?:\.\d{1,2})?$/', message: 'El precio debe ser positivo o cero y tener como máximo dos decimales')]
    public ?string $precio = null;

    public static function tieneValor(mixed $valor): bool
    {
        return $valor !== null;
    }

    public static function cantidadComoTexto(int $cantidad): string
    {
        return (string) $cantidad;
    }
}
