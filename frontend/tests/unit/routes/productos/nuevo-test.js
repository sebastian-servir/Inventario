import { module, test } from 'qunit';
import { setupTest } from 'ember-qunit';

module('Unit | Route | productos/nuevo', function (hooks) {
  setupTest(hooks);

  test('it exists', function (assert) {
    let route = this.owner.lookup('route:productos/nuevo');
    assert.ok(route);
  });
});
