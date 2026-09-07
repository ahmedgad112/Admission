import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Settings\TimerController::edit
 * @see app/Http/Controllers/Settings/TimerController.php:19
 * @route '/settings/timer'
 */
export const edit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/settings/timer',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\TimerController::edit
 * @see app/Http/Controllers/Settings/TimerController.php:19
 * @route '/settings/timer'
 */
edit.url = (options?: RouteQueryOptions) => {
    return edit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\TimerController::edit
 * @see app/Http/Controllers/Settings/TimerController.php:19
 * @route '/settings/timer'
 */
edit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Settings\TimerController::edit
 * @see app/Http/Controllers/Settings/TimerController.php:19
 * @route '/settings/timer'
 */
edit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Settings\TimerController::edit
 * @see app/Http/Controllers/Settings/TimerController.php:19
 * @route '/settings/timer'
 */
    const editForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Settings\TimerController::edit
 * @see app/Http/Controllers/Settings/TimerController.php:19
 * @route '/settings/timer'
 */
        editForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Settings\TimerController::edit
 * @see app/Http/Controllers/Settings/TimerController.php:19
 * @route '/settings/timer'
 */
        editForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\Settings\TimerController::update
 * @see app/Http/Controllers/Settings/TimerController.php:31
 * @route '/settings/timer'
 */
export const update = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/settings/timer',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Settings\TimerController::update
 * @see app/Http/Controllers/Settings/TimerController.php:31
 * @route '/settings/timer'
 */
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\TimerController::update
 * @see app/Http/Controllers/Settings/TimerController.php:31
 * @route '/settings/timer'
 */
update.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\Settings\TimerController::update
 * @see app/Http/Controllers/Settings/TimerController.php:31
 * @route '/settings/timer'
 */
    const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Settings\TimerController::update
 * @see app/Http/Controllers/Settings/TimerController.php:31
 * @route '/settings/timer'
 */
        updateForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
const timer = {
    edit: Object.assign(edit, edit),
update: Object.assign(update, update),
}

export default timer