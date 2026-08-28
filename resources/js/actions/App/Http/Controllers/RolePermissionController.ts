import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
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
/**
* @see \App\Http\Controllers\RolePermissionController::store
 * @see app/Http/Controllers/RolePermissionController.php:59
 * @route '/permissions/roles'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/permissions/roles',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\RolePermissionController::store
 * @see app/Http/Controllers/RolePermissionController.php:59
 * @route '/permissions/roles'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RolePermissionController::store
 * @see app/Http/Controllers/RolePermissionController.php:59
 * @route '/permissions/roles'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\RolePermissionController::store
 * @see app/Http/Controllers/RolePermissionController.php:59
 * @route '/permissions/roles'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RolePermissionController::store
 * @see app/Http/Controllers/RolePermissionController.php:59
 * @route '/permissions/roles'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\RolePermissionController::updateRole
 * @see app/Http/Controllers/RolePermissionController.php:67
 * @route '/permissions/roles/{role}'
 */
export const updateRole = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateRole.url(args, options),
    method: 'patch',
})

updateRole.definition = {
    methods: ["patch"],
    url: '/permissions/roles/{role}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\RolePermissionController::updateRole
 * @see app/Http/Controllers/RolePermissionController.php:67
 * @route '/permissions/roles/{role}'
 */
updateRole.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return updateRole.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RolePermissionController::updateRole
 * @see app/Http/Controllers/RolePermissionController.php:67
 * @route '/permissions/roles/{role}'
 */
updateRole.patch = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateRole.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\RolePermissionController::updateRole
 * @see app/Http/Controllers/RolePermissionController.php:67
 * @route '/permissions/roles/{role}'
 */
    const updateRoleForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateRole.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RolePermissionController::updateRole
 * @see app/Http/Controllers/RolePermissionController.php:67
 * @route '/permissions/roles/{role}'
 */
        updateRoleForm.patch = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateRole.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateRole.form = updateRoleForm
/**
* @see \App\Http\Controllers\RolePermissionController::destroy
 * @see app/Http/Controllers/RolePermissionController.php:77
 * @route '/permissions/roles/{role}'
 */
export const destroy = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/permissions/roles/{role}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\RolePermissionController::destroy
 * @see app/Http/Controllers/RolePermissionController.php:77
 * @route '/permissions/roles/{role}'
 */
destroy.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return destroy.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RolePermissionController::destroy
 * @see app/Http/Controllers/RolePermissionController.php:77
 * @route '/permissions/roles/{role}'
 */
destroy.delete = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\RolePermissionController::destroy
 * @see app/Http/Controllers/RolePermissionController.php:77
 * @route '/permissions/roles/{role}'
 */
    const destroyForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RolePermissionController::destroy
 * @see app/Http/Controllers/RolePermissionController.php:77
 * @route '/permissions/roles/{role}'
 */
        destroyForm.delete = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const RolePermissionController = { edit, update, store, updateRole, destroy }

export default RolePermissionController