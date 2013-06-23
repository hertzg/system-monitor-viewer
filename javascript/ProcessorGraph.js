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
