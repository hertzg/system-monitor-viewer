#!/usr/bin/env node

function compress (outputFile, files) {
    var source = ''
    files.forEach(function (file) {
        source += fs.readFileSync(file, 'utf8') + '\n'
    })
    source = uglifyCss.processString(source)
    fs.writeFileSync(outputFile, source)
}

process.chdir(__dirname)
process.chdir('..')

var fs = require('fs')

try {
    var uglifyCss = require('uglifycss')
} catch (e) {
    console.log('ERROR: module uglifycss not found. run "npm install uglifycss" to install.')
    process.exit(1)
}

compress('view.compressed.css', [
    'javascript/Graph.css',
    'javascript/Legend.css',
    'index.css',
    'view.css',
])
