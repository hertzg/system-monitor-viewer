function Graph (lastTime, items) {

    var classPrefix = 'Graph'

    items = items.slice()

    var canvas = document.createElement('canvas')
    canvas.className = classPrefix + '-canvas'
    canvas.width = 400
    canvas.height = 100

    var minuteScale = 2

    var element = document.createElement('div')
    element.className = classPrefix
    element.appendChild(canvas)

    if (items.length) {

        function scale (value) {
            return -(value - minValue) / (maxValue - minValue) * canvas.height
        }

        var maxValue = -Infinity, minValue = Infinity
        items.forEach(function (item) {
            maxValue = Math.max(maxValue, item.value)
            minValue = Math.min(minValue, item.value)
        })

        var c = canvas.getContext('2d')
        c.translate(canvas.width, canvas.height)
        c.beginPath()
        var prevItem = items.pop()
        items.reverse()
        c.moveTo(0, scale(prevItem.value))
        items.forEach(function (item) {
            c.translate(-(prevItem.time - item.time) / (1000 * 60) * minuteScale, 0)
            c.lineTo(0, scale(item.value))
            prevItem = item
        })
        c.strokeStyle = '#07f'
        c.stroke()

    }

    return { element: element }

}
