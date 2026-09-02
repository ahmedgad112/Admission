import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
export const pending = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pending.url(options),
    method: 'get',
})

pending.definition = {
    methods: ["get","head"],
    url: '/attendance/kiosk/pending',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
pending.url = (options?: RouteQueryOptions) => {
    return pending.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
pending.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pending.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
pending.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pending.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
    const pendingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pending.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
        pendingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pending.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::pending
 * @see app/Http/Controllers/QrSessionController.php:105
 * @route '/attendance/kiosk/pending'
 */
        pendingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pending.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pending.form = pendingForm
const kiosk = {
    pending: Object.assign(pending, pending),
}

export default kiosk