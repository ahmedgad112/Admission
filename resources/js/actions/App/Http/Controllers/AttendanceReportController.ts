import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
const AttendanceReportController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AttendanceReportController.url(options),
    method: 'get',
})

AttendanceReportController.definition = {
    methods: ["get","head"],
    url: '/attendance/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
AttendanceReportController.url = (options?: RouteQueryOptions) => {
    return AttendanceReportController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
AttendanceReportController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AttendanceReportController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
AttendanceReportController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: AttendanceReportController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
    const AttendanceReportControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: AttendanceReportController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
        AttendanceReportControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AttendanceReportController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
        AttendanceReportControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AttendanceReportController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    AttendanceReportController.form = AttendanceReportControllerForm
export default AttendanceReportController