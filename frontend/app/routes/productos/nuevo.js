import Route from '@ember/routing/route';

export default class ProductosNuevoRoute extends Route {
    async model() {
        let response = await fetch("/api/categorias");

        if (!response.ok) {
            throw new Error("no se pudieron cargar las categorias");
        }

        return response.json();
    }
}
