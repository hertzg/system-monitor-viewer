#!/usr/bin/env node

function compress (outputFile, files) {

    var source = '(function () {\n'
    files.forEach(function (file) {
        source += fs.readFileSync(file, 'utf8') + ';\n'
    })
    source += '\n})()'

    var ast = uglifyJs.parse(source)
    ast.figure_out_scope()
    var compressor = uglifyJs.Compressor({})
    var compressedAst = ast.transform(compressor)
    compressedAst.figure_out_scope()
    compressedAst.compute_char_frequency()
    compressedAst.mangle_names()
    var compressedSource = compressedAst.print_to_string()

    fs.writeFileSync(outputFile + '.combined.js', source)
    fs.writeFileSync(outputFile + '.compressed.js', compressedSource)

}

process.chdir(__dirname)
process.chdir('..')

var fs = require('fs')

try {
    var uglifyJs = require('uglify-js')
} catch (e) {
    console.log('ERROR: module uglify-js not found. run "npm install uglify-js" to install.')
    process.exit(1)
}

compress('browse', [
    'javascript/FormatBytes.js',
    'javascript/FormatPercent.js',
    'javascript/Graph.js',
    'javascript/Legend.js',
    'javascript/MemoryGraph.js',
    'javascript/NetworkGraph.js',
    'javascript/ProcessorGraph.js',
    'view.js',
])
