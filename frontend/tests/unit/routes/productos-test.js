import { module, test } from 'qunit';
import { setupTest } from 'ember-qunit';

module('Unit | Route | productos', function (hooks) {
  setupTest(hooks);

  test('it exists', function (assert) {
    let route = this.owner.lookup('route:productos');
    assert.ok(route);
  });
});
