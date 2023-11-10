function init () {
    initMetronic()
    initSizeRatio()
}

function initMetronic () {
    mApp.init()
    mUtil.init()
    mLayout.init()
}

function initSizeRatio () {
    const $element = $('[data-size-ratio]')
    if (!$element.length) {
        return
    }

    resize()

    mUtil.addResizeHandler(resize);

    function resize() {
        $element.each((key, element) => {
            const $element = $(element)
            $element.height($element.width() * $element.data('size-ratio') + 'px')
        })
    }
}

export default {
    init
}
