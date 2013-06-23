(function () {
function FormatBytes (bytes) {
    var names = ['', 'K', 'M', 'G', 'T']
    for (var i in names) {
        if (bytes > 1024) bytes /= 1024
        else {
            return bytes.toFixed(1) + names[i] + 'B'
        }
    }
}
;
function FormatPercent (n) {
    return (n * 100).toFixed(2) + '%'
}
;
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
;
function Legend () {

    var element = document.createElement('div')
    element.className = 'Legend'

    return {
        element: element,
        addLine: function (lineElement) {
            var div = document.createElement('div')
            div.appendChild(lineElement)
            element.appendChild(div)
        },
    }

}
;
function MemoryGraph (lastTime, items) {

    var graph = Graph(lastTime, items)

    if (items.length) {

        var minValue = Infinity,
            maxValue = -Infinity
        items.forEach(function (item) {
            var value = item.value
            if (value > maxValue) maxValue = value
            if (value < minValue) minValue = value
        })

        var legend = Legend()
        legend.addLine(document.createTextNode('Max: ' + FormatBytes(maxValue)))
        legend.addLine(document.createTextNode('Min: ' + FormatBytes(minValue)))
        graph.element.appendChild(legend.element)

    }

    return graph

}
;
function NetworkGraph (lastTime, items) {

    var graph = Graph(lastTime, items)

    if (items.length) {

        var minValue = Infinity,
            maxValue = -Infinity
        items.forEach(function (item) {
            var value = item.value
            if (value > maxValue) maxValue = value
            if (value < minValue) minValue = value
        })

        var legend = Legend()
        legend.addLine(document.createTextNode('Max: ' + FormatBytes(maxValue)))
        legend.addLine(document.createTextNode('Min: ' + FormatBytes(minValue)))
        graph.element.appendChild(legend.element)

    }

    return graph

}
;
function ProcessorGraph (lastTime, items) {

    var graph = Graph(lastTime, items)

    if (items.length) {

        var minValue = Infinity,
            maxValue = -Infinity
        items.forEach(function (item) {
            var value = item.value
            if (value > maxValue) maxValue = value
            if (value < minValue) minValue = value
        })

        var legend = Legend()
        legend.addLine(document.createTextNode('Max: ' + FormatPercent(maxValue)))
        legend.addLine(document.createTextNode('Min: ' + FormatPercent(minValue)))
        graph.element.appendChild(legend.element)

    }

    return graph

}
;
var dateNow = data.dateNow

var memoryGraph = MemoryGraph(dateNow, data.memory)
document.getElementById('memoryGraphWrapper').appendChild(memoryGraph.element)

var processorGraph = ProcessorGraph(dateNow, data.processor)
document.getElementById('processorGraphWrapper').appendChild(processorGraph.element)

var networkGraph = NetworkGraph(dateNow, data.network)
document.getElementById('networkGraphWrapper').appendChild(networkGraph.element)

;

})()