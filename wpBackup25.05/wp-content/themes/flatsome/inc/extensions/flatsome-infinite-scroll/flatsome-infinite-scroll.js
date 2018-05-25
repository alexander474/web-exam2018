jQuery(document).ready(function () {
  let container = jQuery('.shop-container')
  let paginationNext = '.woocommerce-pagination li a.next'

  if (container.length === 0 || jQuery(paginationNext).length === 0) {
    return
  }

  let viewMoreButton = jQuery('button.view-more-button.products-archive')
  let byButton = flatsome_infinite_scroll.type === 'button'

  let $container = container.infiniteScroll({
    path: paginationNext,
    checkLastPage: true,
    append: '.products',
    status: '.page-load-status',
    hideNav: '.woocommerce-pagination',
    button: '.view-more-button',
    history: false,
    debug: false,
    scrollThreshold: parseInt(flatsome_infinite_scroll.scroll_threshold)
  })

  if (byButton) {
    viewMoreButton.removeClass('hidden')
    $container.infiniteScroll('option', {
      scrollThreshold: false,
      loadOnScroll: false
    })
  }

  // Loaded but not added
  $container.on('load.infiniteScroll', function (event, response, path) {
    Flatsome.attach('quick-view', response)
    Flatsome.attach('tooltips', response)
    Flatsome.attach('add-qty', response)
    Flatsome.attach('wishlist', response)
  })

  // On request
  $container.on('request.infiniteScroll', function (event, path) {
    if (byButton) viewMoreButton.addClass('loading')
  })

  // Appended to the container
  $container.on('append.infiniteScroll', function (event, response, path, items) {
    if (byButton) viewMoreButton.removeClass('loading')

    // Fix Safari bug
    jQuery(items).find('img').each(function (index, img) {
      img.outerHTML = img.outerHTML
    })

    Flatsome.attach('lazy-load-images', container)
    jQuery(items).hide().fadeIn(parseInt(flatsome_infinite_scroll.fade_in_duration))

    if (window.ga && ga.loaded && typeof ga === 'function') {
      let link = document.createElement('a')
      link.href = path
      ga('set', 'page', link.pathname)
      ga('send', 'pageview')
    }
  })
})
