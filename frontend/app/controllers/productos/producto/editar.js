import Controller from '@ember/controller';
import { tracked } from "@glimmer/tracking";
import { action } from "@ember/object";
import { inject as service } from "@ember/service"; 

export default class ProductosProductoEditarController extends Controller {
    @service router;
    
    @tracked nombre = "";
    @tracked cantidad = "";
    @tracked precio = "";
    @tracked error = "";
    @tracked guardando = false;

    @action
    async guardar(event) {
        event.preventDefault();

        if (this.guardando) {
            return;
        }

        this.guardando = true;
        this.error = "";
    }
}
