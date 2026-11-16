import Controller from '@ember/controller';
import { tracked } from '@glimmer/tracking';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class ProductosNuevoController extends Controller {
  @service router;

  @tracked nombre = '';
  @tracked cantidad = '';
  @tracked precio = '';
  @tracked categoria = '';
  @tracked error = '';
  @tracked guardando = false;

  @action
  seleccionarCategoria(event) {
    this.categoria = event.target.value;
  } 

  @action
  async guardar(event) {
    event.preventDefault();

    if (this.guardando) {
      return;
    }

    this.guardando = true;
    this.error = '';

    try {
      let response = await fetch('/api/productos', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({
          nombre: this.nombre.trim(),
          cantidad: Number(this.cantidad),
          precio: String(this.precio),
          categoria: Number(this.categoria),
        }),
      });

      let producto = await response.json();

      if (!response.ok) {
        throw new Error(
          producto.detail || producto.error || 'No se pudo guardar el producto'
        );
      }

      this.nombre = '';
      this.cantidad = '';
      this.precio = '';
      this.categoria = '';

      await this.router.transitionTo('productos');
      this.send("recargarProductos");
    } catch (error) {
      this.error = error.message;
    } finally {
      this.guardando = false;
    }
  }
}
