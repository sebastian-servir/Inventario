import Route from '@ember/routing/route';
import { action } from "@ember/object";

export default class ProductosRoute extends Route {
  async model() {
    let response = await fetch('/api/productos');

    if (!response.ok) {
      throw new Error('NO se pudieron cargar los productos');
    }

    return response.json();
  }
  @action
  recargarProductos() {
    this.refresh();
  }
}
