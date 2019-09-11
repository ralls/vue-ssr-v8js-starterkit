const Script = function(code, options) {
  this.code = code;
  this.filename = options.filename || ''
}

Script.prototype.runInThisContext = function() {
  return eval(this.code)
}

Script.prototype.runInNewContext = function(context) {
  return eval(this.code)
}



module.exports = {
  Script
}
