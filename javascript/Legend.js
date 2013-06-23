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
