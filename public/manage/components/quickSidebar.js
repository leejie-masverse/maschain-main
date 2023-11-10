function init () {
    const $quickSidebarToggler = $('[data-quick-sidebar]')
    if (!$quickSidebarToggler.length) {
        return
    }

    const quickSidebarId = $quickSidebarToggler.data('quick-sidebar')

    new mOffcanvas(quickSidebarId, {
        overlay: true,
        baseClass: 'm-quick-sidebar',
        closeBy: `${quickSidebarId}__close`,
        toggleBy: `${quickSidebarId}-toggler`
    });
}

export default {
    init
}
