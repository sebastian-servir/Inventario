import Route from '@ember/routing/route';

export default class CategoriasRoute extends Route {
    model() {
        return [
            { id: 1, name: "Perifericos"}
        ]
    }
}
