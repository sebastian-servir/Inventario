import { module, test } from 'qunit';
import { setupTest } from 'ember-qunit';

module('Unit | Route | categorias', function (hooks) {
  setupTest(hooks);

  test('it exists', function (assert) {
    let route = this.owner.lookup('route:categorias');
    assert.ok(route);
  });
});
