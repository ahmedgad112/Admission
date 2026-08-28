import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\AttendanceController::sync
 * @see app/Http/Controllers/AttendanceController.php:61
 * @route '/attendance/entries'
 */
export const sync = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: sync.url(options),
    method: 'put',
})

sync.definition = {
    methods: ["put"],
    url: '/attendance/entries',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\AttendanceController::sync
 * @see app/Http/Controllers/AttendanceController.php:61
 * @route '/attendance/entries'
 */
sync.url = (options?: RouteQueryOptions) => {
    return sync.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::sync
 * @see app/Http/Controllers/AttendanceController.php:61
 * @route '/attendance/entries'
 */
sync.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: sync.url(options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\AttendanceController::sync
 * @see app/Http/Controllers/AttendanceController.php:61
 * @route '/attendance/entries'
 */
    const syncForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sync.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::sync
 * @see app/Http/Controllers/AttendanceController.php:61
 * @route '/attendance/entries'
 */
        syncForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sync.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    sync.form = syncForm
const entries = {
    sync: Object.assign(sync, sync),
}

export default entries