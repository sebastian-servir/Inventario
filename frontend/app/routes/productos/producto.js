import Route from '@ember/routing/route';

export default class ProductosProductoRoute extends Route {
    model({ producto_id }) {
        let productos = this.modelFor('productos');

        return productos.find(
            (producto) => String(producto.id) === producto_id
        );
    } 
}
