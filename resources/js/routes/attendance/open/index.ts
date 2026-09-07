import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\AttendanceController::store
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/attendance/open',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::store
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::store
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::store
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::store
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const open = {
    store: Object.assign(store, store),
}

export default open