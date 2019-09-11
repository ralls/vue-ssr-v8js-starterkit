module.exports = {
  extname: filename => filename.slice(filename.lastIndexOf('.')),

  posix: {
    join: (...args) => args.join('/').replace(/\.\//g, ''),
  },
}
