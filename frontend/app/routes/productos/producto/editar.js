import Route from '@ember/routing/route';

export default class ProductosProductoEditarRoute extends Route {
  model() {
    return this.modelFor('productos.producto');
  }

  setupController(controller, model) {
    super.setupController(controller, model);

    controller.nombre = model.nombre;
    controller.cantidad = model.cantidad;
    controller.precio = model.precio;
    controller.error = '';
  }
}