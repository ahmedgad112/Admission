import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
export const template = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: template.url(options),
    method: 'get',
})

template.definition = {
    methods: ["get","head"],
    url: '/staff/leave-days/template',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
template.url = (options?: RouteQueryOptions) => {
    return template.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
template.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: template.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
template.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: template.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
    const templateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: template.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
        templateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: template.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
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
/**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/staff/leave-days/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
    const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
        importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
/**
* @see \App\Http\Controllers\StaffController::bulk
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
export const bulk = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulk.url(options),
    method: 'post',
})

bulk.definition = {
    methods: ["post"],
    url: '/staff/leave-days',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\StaffController::bulk
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
bulk.url = (options?: RouteQueryOptions) => {
    return bulk.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::bulk
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
bulk.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulk.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\StaffController::bulk
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
    const bulkForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulk.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::bulk
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
        bulkForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulk.url(options),
            method: 'post',
        })
    
    bulk.form = bulkForm
/**
* @see \App\Http\Controllers\StaffController::adjust
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
export const adjust = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: adjust.url(args, options),
    method: 'post',
})

adjust.definition = {
    methods: ["post"],
    url: '/staff/{user}/leave-days',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\StaffController::adjust
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
adjust.url = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { user: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    user: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        user: typeof args.user === 'object'
                ? args.user.id
                : args.user,
                }

    return adjust.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::adjust
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
adjust.post = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: adjust.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\StaffController::adjust
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
    const adjustForm = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: adjust.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::adjust
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
        adjustForm.post = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: adjust.url(args, options),
            method: 'post',
        })
    
    adjust.form = adjustForm
const leaveDays = {
    template: Object.assign(template, template),
import: Object.assign(importMethod, importMethod),
bulk: Object.assign(bulk, bulk),
adjust: Object.assign(adjust, adjust),
}

export default leaveDays