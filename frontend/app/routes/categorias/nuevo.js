import Route from '@ember/routing/route';
import { action } from "@ember/object"

export default class CategoriasNuevoRoute extends Route {
    @action
    resetController(controller, isExisting){
        if (isExisting) {
            controller.nombre = "";
            controller.error = "";
        }
    }
}
