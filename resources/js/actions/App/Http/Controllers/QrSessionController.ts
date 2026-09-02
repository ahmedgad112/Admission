import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/api/qr-sessions/current'
 */
const current6dfe566976c63242076e67ab7bdf1724 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: current6dfe566976c63242076e67ab7bdf1724.url(options),
    method: 'get',
})

current6dfe566976c63242076e67ab7bdf1724.definition = {
    methods: ["get","head"],
    url: '/api/qr-sessions/current',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/api/qr-sessions/current'
 */
current6dfe566976c63242076e67ab7bdf1724.url = (options?: RouteQueryOptions) => {
    return current6dfe566976c63242076e67ab7bdf1724.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/api/qr-sessions/current'
 */
current6dfe566976c63242076e67ab7bdf1724.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: current6dfe566976c63242076e67ab7bdf1724.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/api/qr-sessions/current'
 */
current6dfe566976c63242076e67ab7bdf1724.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: current6dfe566976c63242076e67ab7bdf1724.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/api/qr-sessions/current'
 */
    const current6dfe566976c63242076e67ab7bdf1724Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: current6dfe566976c63242076e67ab7bdf1724.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/api/qr-sessions/current'
 */
        current6dfe566976c63242076e67ab7bdf1724Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: current6dfe566976c63242076e67ab7bdf1724.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/api/qr-sessions/current'
 */
        current6dfe566976c63242076e67ab7bdf1724Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: current6dfe566976c63242076e67ab7bdf1724.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    current6dfe566976c63242076e67ab7bdf1724.form = current6dfe566976c63242076e67ab7bdf1724Form
    /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/attendance/qr-sessions/current'
 */
const current0042797756c5989d7db96685a4891319 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: current0042797756c5989d7db96685a4891319.url(options),
    method: 'get',
})

current0042797756c5989d7db96685a4891319.definition = {
    methods: ["get","head"],
    url: '/attendance/qr-sessions/current',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/attendance/qr-sessions/current'
 */
current0042797756c5989d7db96685a4891319.url = (options?: RouteQueryOptions) => {
    return current0042797756c5989d7db96685a4891319.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/attendance/qr-sessions/current'
 */
current0042797756c5989d7db96685a4891319.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: current0042797756c5989d7db96685a4891319.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/attendance/qr-sessions/current'
 */
current0042797756c5989d7db96685a4891319.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: current0042797756c5989d7db96685a4891319.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/attendance/qr-sessions/current'
 */
    const current0042797756c5989d7db96685a4891319Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: current0042797756c5989d7db96685a4891319.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/attendance/qr-sessions/current'
 */
        current0042797756c5989d7db96685a4891319Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: current0042797756c5989d7db96685a4891319.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:45
 * @route '/attendance/qr-sessions/current'
 */
        current0042797756c5989d7db96685a4891319Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: current0042797756c5989d7db96685a4891319.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    current0042797756c5989d7db96685a4891319.form = current0042797756c5989d7db96685a4891319Form

/**
* Multiple routes resolve to \App\Http\Controllers\QrSessionController::current, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `current['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const current = {
    '/api/qr-sessions/current': current6dfe566976c63242076e67ab7bdf1724,
    '/attendance/qr-sessions/current': current0042797756c5989d7db96685a4891319,
}

/**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/api/qr-sessions/open'
 */
const open51c896037c0c951e29a6ea1983f91919 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: open51c896037c0c951e29a6ea1983f91919.url(options),
    method: 'post',
})

open51c896037c0c951e29a6ea1983f91919.definition = {
    methods: ["post"],
    url: '/api/qr-sessions/open',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/api/qr-sessions/open'
 */
open51c896037c0c951e29a6ea1983f91919.url = (options?: RouteQueryOptions) => {
    return open51c896037c0c951e29a6ea1983f91919.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/api/qr-sessions/open'
 */
open51c896037c0c951e29a6ea1983f91919.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: open51c896037c0c951e29a6ea1983f91919.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/api/qr-sessions/open'
 */
    const open51c896037c0c951e29a6ea1983f91919Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: open51c896037c0c951e29a6ea1983f91919.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/api/qr-sessions/open'
 */
        open51c896037c0c951e29a6ea1983f91919Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: open51c896037c0c951e29a6ea1983f91919.url(options),
            method: 'post',
        })
    
    open51c896037c0c951e29a6ea1983f91919.form = open51c896037c0c951e29a6ea1983f91919Form
    /**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/attendance/qr-sessions/open'
 */
const open25defd8c0a7369fae90afd3ae00238dc = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: open25defd8c0a7369fae90afd3ae00238dc.url(options),
    method: 'post',
})

open25defd8c0a7369fae90afd3ae00238dc.definition = {
    methods: ["post"],
    url: '/attendance/qr-sessions/open',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/attendance/qr-sessions/open'
 */
open25defd8c0a7369fae90afd3ae00238dc.url = (options?: RouteQueryOptions) => {
    return open25defd8c0a7369fae90afd3ae00238dc.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/attendance/qr-sessions/open'
 */
open25defd8c0a7369fae90afd3ae00238dc.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: open25defd8c0a7369fae90afd3ae00238dc.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/attendance/qr-sessions/open'
 */
    const open25defd8c0a7369fae90afd3ae00238dcForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: open25defd8c0a7369fae90afd3ae00238dc.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:64
 * @route '/attendance/qr-sessions/open'
 */
        open25defd8c0a7369fae90afd3ae00238dcForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: open25defd8c0a7369fae90afd3ae00238dc.url(options),
            method: 'post',
        })
    
    open25defd8c0a7369fae90afd3ae00238dc.form = open25defd8c0a7369fae90afd3ae00238dcForm

/**
* Multiple routes resolve to \App\Http\Controllers\QrSessionController::open, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `open['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const open = {
    '/api/qr-sessions/open': open51c896037c0c951e29a6ea1983f91919,
    '/attendance/qr-sessions/open': open25defd8c0a7369fae90afd3ae00238dc,
}

/**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/api/qr-sessions/close'
 */
const closedf57ecbc0063c3f8b8ad1aaba0d19ff3 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: closedf57ecbc0063c3f8b8ad1aaba0d19ff3.url(options),
    method: 'post',
})

closedf57ecbc0063c3f8b8ad1aaba0d19ff3.definition = {
    methods: ["post"],
    url: '/api/qr-sessions/close',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/api/qr-sessions/close'
 */
closedf57ecbc0063c3f8b8ad1aaba0d19ff3.url = (options?: RouteQueryOptions) => {
    return closedf57ecbc0063c3f8b8ad1aaba0d19ff3.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/api/qr-sessions/close'
 */
closedf57ecbc0063c3f8b8ad1aaba0d19ff3.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: closedf57ecbc0063c3f8b8ad1aaba0d19ff3.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/api/qr-sessions/close'
 */
    const closedf57ecbc0063c3f8b8ad1aaba0d19ff3Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: closedf57ecbc0063c3f8b8ad1aaba0d19ff3.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/api/qr-sessions/close'
 */
        closedf57ecbc0063c3f8b8ad1aaba0d19ff3Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: closedf57ecbc0063c3f8b8ad1aaba0d19ff3.url(options),
            method: 'post',
        })
    
    closedf57ecbc0063c3f8b8ad1aaba0d19ff3.form = closedf57ecbc0063c3f8b8ad1aaba0d19ff3Form
    /**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/attendance/qr-sessions/close'
 */
const close14034728e367841c0e5d2bf6a8a2caa2 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close14034728e367841c0e5d2bf6a8a2caa2.url(options),
    method: 'post',
})

close14034728e367841c0e5d2bf6a8a2caa2.definition = {
    methods: ["post"],
    url: '/attendance/qr-sessions/close',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/attendance/qr-sessions/close'
 */
close14034728e367841c0e5d2bf6a8a2caa2.url = (options?: RouteQueryOptions) => {
    return close14034728e367841c0e5d2bf6a8a2caa2.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/attendance/qr-sessions/close'
 */
close14034728e367841c0e5d2bf6a8a2caa2.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close14034728e367841c0e5d2bf6a8a2caa2.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/attendance/qr-sessions/close'
 */
    const close14034728e367841c0e5d2bf6a8a2caa2Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: close14034728e367841c0e5d2bf6a8a2caa2.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:85
 * @route '/attendance/qr-sessions/close'
 */
        close14034728e367841c0e5d2bf6a8a2caa2Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: close14034728e367841c0e5d2bf6a8a2caa2.url(options),
            method: 'post',
        })
    
    close14034728e367841c0e5d2bf6a8a2caa2.form = close14034728e367841c0e5d2bf6a8a2caa2Form

/**
* Multiple routes resolve to \App\Http\Controllers\QrSessionController::close, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `close['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const close = {
    '/api/qr-sessions/close': closedf57ecbc0063c3f8b8ad1aaba0d19ff3,
    '/attendance/qr-sessions/close': close14034728e367841c0e5d2bf6a8a2caa2,
}

/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/api/kiosk/pending'
 */
const pending20ec7ba42621e3f1d2f1f8a8343aaa3b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pending20ec7ba42621e3f1d2f1f8a8343aaa3b.url(options),
    method: 'get',
})

pending20ec7ba42621e3f1d2f1f8a8343aaa3b.definition = {
    methods: ["get","head"],
    url: '/api/kiosk/pending',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/api/kiosk/pending'
 */
pending20ec7ba42621e3f1d2f1f8a8343aaa3b.url = (options?: RouteQueryOptions) => {
    return pending20ec7ba42621e3f1d2f1f8a8343aaa3b.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/api/kiosk/pending'
 */
pending20ec7ba42621e3f1d2f1f8a8343aaa3b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pending20ec7ba42621e3f1d2f1f8a8343aaa3b.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/api/kiosk/pending'
 */
pending20ec7ba42621e3f1d2f1f8a8343aaa3b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pending20ec7ba42621e3f1d2f1f8a8343aaa3b.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/api/kiosk/pending'
 */
    const pending20ec7ba42621e3f1d2f1f8a8343aaa3bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pending20ec7ba42621e3f1d2f1f8a8343aaa3b.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/api/kiosk/pending'
 */
        pending20ec7ba42621e3f1d2f1f8a8343aaa3bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pending20ec7ba42621e3f1d2f1f8a8343aaa3b.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/api/kiosk/pending'
 */
        pending20ec7ba42621e3f1d2f1f8a8343aaa3bForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pending20ec7ba42621e3f1d2f1f8a8343aaa3b.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pending20ec7ba42621e3f1d2f1f8a8343aaa3b.form = pending20ec7ba42621e3f1d2f1f8a8343aaa3bForm
    /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
const pending504d1f22973f60f6d1e37320f34e440d = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pending504d1f22973f60f6d1e37320f34e440d.url(options),
    method: 'get',
})

pending504d1f22973f60f6d1e37320f34e440d.definition = {
    methods: ["get","head"],
    url: '/attendance/kiosk/pending',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
pending504d1f22973f60f6d1e37320f34e440d.url = (options?: RouteQueryOptions) => {
    return pending504d1f22973f60f6d1e37320f34e440d.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
pending504d1f22973f60f6d1e37320f34e440d.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pending504d1f22973f60f6d1e37320f34e440d.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
pending504d1f22973f60f6d1e37320f34e440d.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pending504d1f22973f60f6d1e37320f34e440d.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
    const pending504d1f22973f60f6d1e37320f34e440dForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pending504d1f22973f60f6d1e37320f34e440d.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
        pending504d1f22973f60f6d1e37320f34e440dForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pending504d1f22973f60f6d1e37320f34e440d.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
        pending504d1f22973f60f6d1e37320f34e440dForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pending504d1f22973f60f6d1e37320f34e440d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pending504d1f22973f60f6d1e37320f34e440d.form = pending504d1f22973f60f6d1e37320f34e440dForm

/**
* Multiple routes resolve to \App\Http\Controllers\QrSessionController::pending, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `pending['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const pending = {
    '/api/kiosk/pending': pending20ec7ba42621e3f1d2f1f8a8343aaa3b,
    '/attendance/kiosk/pending': pending504d1f22973f60f6d1e37320f34e440d,
}

/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
export const kiosk = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kiosk.url(options),
    method: 'get',
})

kiosk.definition = {
    methods: ["get","head"],
    url: '/attendance/kiosk',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
kiosk.url = (options?: RouteQueryOptions) => {
    return kiosk.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
kiosk.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kiosk.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
kiosk.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: kiosk.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
    const kioskForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: kiosk.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
        kioskForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: kiosk.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
        kioskForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: kiosk.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    kiosk.form = kioskForm
const QrSessionController = { current, open, close, pending, kiosk }

export default QrSessionController