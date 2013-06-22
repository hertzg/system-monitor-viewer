function Graph (lastTime, items) {

    items = items.slice()

    var canvas = document.createElement('canvas')
    canvas.width = 300
    canvas.height = 100
    canvas.style.background = '#fff'
    canvas.style.verticalAlign = 'top'
    canvas.style.border = '1px solid #ccc'
    canvas.style.padding = '8px'

    var minuteScale = 2

    var element = document.createElement('div')
    element.appendChild(canvas)
    element.style.position = 'relative'
    element.style.display = 'inline-block'
    element.style.background = '#eee'

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

        var maxElement = document.createElement('div')
        maxElement.appendChild(document.createTextNode('Max: ' + maxValue.toFixed(2)))

        var minElement = document.createElement('div')
        minElement.appendChild(document.createTextNode('Min: ' + minValue.toFixed(2)))

        var legendElement = document.createElement('div')
        legendElement.style.padding = '4px'
        legendElement.style.fontSize = '12px'
        legendElement.style.lineHeight = '14px'
        legendElement.style.border = '1px solid #ccc'
        legendElement.style.position = 'absolute'
        legendElement.style.background = 'rgba(255, 255, 255, 0.8)'
        legendElement.style.left = legendElement.style.bottom = '0'
        legendElement.appendChild(maxElement)
        legendElement.appendChild(minElement)

        element.appendChild(legendElement)

    }

    return { element: element }

}
