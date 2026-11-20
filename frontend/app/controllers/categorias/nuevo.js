import Controller from '@ember/controller';
import { tracked } from '@glimmer/tracking';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class CategoriasNuevoController extends Controller {
  @service router;

  @tracked nombre = '';
  @tracked guardando = false;
  @tracked error = '';

  @action
  async guardar(event) {
    event.preventDefault();

    if (this.guardando) {
      return;
    }

    this.guardando = true;
    this.error = '';

    try {
      let response = await fetch('/api/categorias', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({
          nombre: this.nombre.trim(),
        }),
      });

      let categoria = await response.json();

      if (!response.ok) {
        throw new Error(
          categoria.detail ||
            categoria.error ||
            'No se pudo guardar el producto'
        );
      }

      this.nombre = '';

      await this.router.transitionTo('categorias');
      this.send('recargarCategorias');
    } catch (error) {
      this.error = error.message;
    } finally {
      this.guardando = false;
    }
  }
}
