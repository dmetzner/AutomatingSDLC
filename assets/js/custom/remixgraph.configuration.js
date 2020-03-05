/**
 * ---------------------------------------------------------------------------------------------------------------------
 * REMIX GRAPH & NETWORK CONFIGURATION
 * ---------------------------------------------------------------------------------------------------------------------
 */
const SCRATCH_PROJECT_BASE_URL = 'https://scratch.mit.edu/projects/'
const SCRATCH_BASE_IMAGE_URL_TEMPLATE = 'https://cdn2.scratch.mit.edu/get_image/project/{}_140x140.png'
const IMAGE_NOT_AVAILABLE_URL = '/images/default/not_available.png'
const CATROBAT_NODE_PREFIX = 'catrobat'
const SCRATCH_NODE_PREFIX = 'scratch'
const NETWORK_OPTIONS = {
  nodes: {
    labelHighlightBold: false,
    borderWidth: 3,
    borderWidthSelected: 3,
    size: 20,
    color: {
      border: '#CCCCCC',
      background: '#000000',
      highlight: {
        border: '#FFFF00'//,
        // background: '#000000'
      }
    },
    font: {
      size: 10,
      color: '#000000'//,
      //                background: '#FFFFFF'
    },
    shapeProperties: {
      useBorderWithImage: true
    }
  },
  layout: { improvedLayout: true },
  edges: {
    labelHighlightBold: false,
    color: {
      color: '#000000',
      highlight: '#000000',
      hover: '#000000',
      opacity: 1.0
    },
    smooth: {
      //            type: 'straightCross'
      //            type: 'dynamic'
      type: 'dynamic'
    },
    arrows: { to: true }
  },
  // smoothCurves: { dynamic:false, type: "continuous" },
  physics: {
    adaptiveTimestep: false,
    stabilization: true
  },
  interaction: {
    dragNodes: false,
    dragView: true,
    hideEdgesOnDrag: true,
    hideNodesOnDrag: false,
    hover: false,
    hoverConnectedEdges: false,
    keyboard: {
      enabled: true,
      speed: { x: 20, y: 20, zoom: 0.1 },
      bindToWindow: true
    },
    multiselect: false,
    navigationButtons: true,
    selectable: true,
    selectConnectedEdges: false,
    zoomView: true
  }
}
