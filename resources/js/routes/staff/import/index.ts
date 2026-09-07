import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:217
 * @route '/staff/import/template'
 */
export const template = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: template.url(options),
    method: 'get',
})

template.definition = {
    methods: ["get","head"],
    url: '/staff/import/template',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:217
 * @route '/staff/import/template'
 */
template.url = (options?: RouteQueryOptions) => {
    return template.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:217
 * @route '/staff/import/template'
 */
template.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: template.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:217
 * @route '/staff/import/template'
 */
template.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: template.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:217
 * @route '/staff/import/template'
 */
    const templateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: template.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:217
 * @route '/staff/import/template'
 */
        templateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: template.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:217
 * @route '/staff/import/template'
 */
        templateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: template.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    template.form = templateForm