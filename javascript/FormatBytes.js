function FormatBytes (bytes) {
    var names = ['', 'K', 'M', 'G', 'T']
    for (var i in names) {
        if (bytes > 1024) bytes /= 1024
        else {
            return bytes.toFixed(1) + names[i] + 'B'
        }
    }
}
