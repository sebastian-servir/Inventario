import { module, test } from 'qunit';
import { setupTest } from 'ember-qunit';

module('Unit | Route | productos/producto/editar', function (hooks) {
  setupTest(hooks);

  test('it exists', function (assert) {
    let route = this.owner.lookup('route:productos/producto/editar');
    assert.ok(route);
  });
});
