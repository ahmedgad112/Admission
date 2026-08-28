import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import roles from './roles'
/**
* @see \App\Http\Controllers\RolePermissionController::edit
 * @see app/Http/Controllers/RolePermissionController.php:23
 * @route '/permissions'
 */
export const edit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/permissions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RolePermissionController::edit
 * @see app/Http/Controllers/RolePermissionController.php:23
 * @route '/permissions'
 */
edit.url = (options?: RouteQueryOptions) => {
    return edit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RolePermissionController::edit
 * @see app/Http/Controllers/RolePermissionController.php:23
 * @route '/permissions'
 */
edit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\RolePermissionController::edit
 * @see app/Http/Controllers/RolePermissionController.php:23
 * @route '/permissions'
 */
edit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\RolePermissionController::edit
 * @see app/Http/Controllers/RolePermissionController.php:23
 * @route '/permissions'
 */
    const editForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\RolePermissionController::edit
 * @see app/Http/Controllers/RolePermissionController.php:23
 * @route '/permissions'
 */
        editForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\RolePermissionController::edit
 * @see app/Http/Controllers/RolePermissionController.php:23
 * @route '/permissions'
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
* @see \App\Http\Controllers\RolePermissionController::update
 * @see app/Http/Controllers/RolePermissionController.php:42
 * @route '/permissions'
 */
export const update = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/permissions',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\RolePermissionController::update
 * @see app/Http/Controllers/RolePermissionController.php:42
 * @route '/permissions'
 */
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RolePermissionController::update
 * @see app/Http/Controllers/RolePermissionController.php:42
 * @route '/permissions'
 */
update.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\RolePermissionController::update
 * @see app/Http/Controllers/RolePermissionController.php:42
 * @route '/permissions'
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
* @see \App\Http\Controllers\RolePermissionController::update
 * @see app/Http/Controllers/RolePermissionController.php:42
 * @route '/permissions'
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
const permissions = {
    edit: Object.assign(edit, edit),
update: Object.assign(update, update),
roles: Object.assign(roles, roles),
}

export default permissions