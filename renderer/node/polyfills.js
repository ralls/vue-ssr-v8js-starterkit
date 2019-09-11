const create = Object.create;
Object.create = function() {
  // sadly, undefined !== null, and v8js crashes if undefined is passed.
  return create.call(null, arguments[0] ? arguments[0] : null)
}
