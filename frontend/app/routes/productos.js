import Route from '@ember/routing/route';

export default class ProductosRoute extends Route {
    model() {
        let producto = [
            { id: 1, nombre: "Teclado", cantidad: 3},
            { id: 2, nombre: "Mouse", cantidad: 2 },
        ]

        return producto.map((producto) => ({
            ...producto,
            pocasExistencias: producto.cantidad <= 2,
        }));
    }
}
