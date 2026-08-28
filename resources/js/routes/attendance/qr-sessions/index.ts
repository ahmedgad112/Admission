import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:46
 * @route '/attendance/qr-sessions/current'
 */
export const current = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: current.url(options),
    method: 'get',
})

current.definition = {
    methods: ["get","head"],
    url: '/attendance/qr-sessions/current',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:46
 * @route '/attendance/qr-sessions/current'
 */
current.url = (options?: RouteQueryOptions) => {
    return current.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:46
 * @route '/attendance/qr-sessions/current'
 */
current.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: current.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:46
 * @route '/attendance/qr-sessions/current'
 */
current.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: current.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:46
 * @route '/attendance/qr-sessions/current'
 */
    const currentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: current.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:46
 * @route '/attendance/qr-sessions/current'
 */
        currentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: current.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::current
 * @see app/Http/Controllers/QrSessionController.php:46
 * @route '/attendance/qr-sessions/current'
 */
        currentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: current.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    current.form = currentForm
/**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:71
 * @route '/attendance/qr-sessions/open'
 */
export const open = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: open.url(options),
    method: 'post',
})

open.definition = {
    methods: ["post"],
    url: '/attendance/qr-sessions/open',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:71
 * @route '/attendance/qr-sessions/open'
 */
open.url = (options?: RouteQueryOptions) => {
    return open.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:71
 * @route '/attendance/qr-sessions/open'
 */
open.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: open.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:71
 * @route '/attendance/qr-sessions/open'
 */
    const openForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: open.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::open
 * @see app/Http/Controllers/QrSessionController.php:71
 * @route '/attendance/qr-sessions/open'
 */
        openForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: open.url(options),
            method: 'post',
        })
    
    open.form = openForm
/**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:98
 * @route '/attendance/qr-sessions/close'
 */
export const close = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close.url(options),
    method: 'post',
})

close.definition = {
    methods: ["post"],
    url: '/attendance/qr-sessions/close',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:98
 * @route '/attendance/qr-sessions/close'
 */
close.url = (options?: RouteQueryOptions) => {
    return close.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:98
 * @route '/attendance/qr-sessions/close'
 */
close.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:98
 * @route '/attendance/qr-sessions/close'
 */
    const closeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: close.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::close
 * @see app/Http/Controllers/QrSessionController.php:98
 * @route '/attendance/qr-sessions/close'
 */
        closeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: close.url(options),
            method: 'post',
        })
    
    close.form = closeForm
const qrSessions = {
    current: Object.assign(current, current),
open: Object.assign(open, open),
close: Object.assign(close, close),
}

export default qrSessions