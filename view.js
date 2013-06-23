var dateNow = data.dateNow

var memoryGraph = MemoryGraph(dateNow, data.memory)
document.getElementById('memoryGraphWrapper').appendChild(memoryGraph.element)

var processorGraph = ProcessorGraph(dateNow, data.processor)
document.getElementById('processorGraphWrapper').appendChild(processorGraph.element)

var networkGraph = NetworkGraph(dateNow, data.network)
document.getElementById('networkGraphWrapper').appendChild(networkGraph.element)

