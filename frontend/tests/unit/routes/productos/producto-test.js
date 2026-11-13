import { module, test } from 'qunit';
import { setupTest } from 'ember-qunit';

module('Unit | Route | productos/producto', function (hooks) {
  setupTest(hooks);

  test('it exists', function (assert) {
    let route = this.owner.lookup('route:productos/producto');
    assert.ok(route);
  });
});
