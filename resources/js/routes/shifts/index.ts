import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ShiftController::index
 * @see app/Http/Controllers/ShiftController.php:19
 * @route '/shifts'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/shifts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ShiftController::index
 * @see app/Http/Controllers/ShiftController.php:19
 * @route '/shifts'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ShiftController::index
 * @see app/Http/Controllers/ShiftController.php:19
 * @route '/shifts'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ShiftController::index
 * @see app/Http/Controllers/ShiftController.php:19
 * @route '/shifts'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ShiftController::index
 * @see app/Http/Controllers/ShiftController.php:19
 * @route '/shifts'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ShiftController::index
 * @see app/Http/Controllers/ShiftController.php:19
 * @route '/shifts'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ShiftController::index
 * @see app/Http/Controllers/ShiftController.php:19
 * @route '/shifts'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\ShiftController::create
 * @see app/Http/Controllers/ShiftController.php:39
 * @route '/shifts/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/shifts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ShiftController::create
 * @see app/Http/Controllers/ShiftController.php:39
 * @route '/shifts/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ShiftController::create
 * @see app/Http/Controllers/ShiftController.php:39
 * @route '/shifts/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ShiftController::create
 * @see app/Http/Controllers/ShiftController.php:39
 * @route '/shifts/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ShiftController::create
 * @see app/Http/Controllers/ShiftController.php:39
 * @route '/shifts/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ShiftController::create
 * @see app/Http/Controllers/ShiftController.php:39
 * @route '/shifts/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ShiftController::create
 * @see app/Http/Controllers/ShiftController.php:39
 * @route '/shifts/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\ShiftController::store
 * @see app/Http/Controllers/ShiftController.php:46
 * @route '/shifts'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/shifts',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ShiftController::store
 * @see app/Http/Controllers/ShiftController.php:46
 * @route '/shifts'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ShiftController::store
 * @see app/Http/Controllers/ShiftController.php:46
 * @route '/shifts'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ShiftController::store
 * @see app/Http/Controllers/ShiftController.php:46
 * @route '/shifts'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ShiftController::store
 * @see app/Http/Controllers/ShiftController.php:46
 * @route '/shifts'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ShiftController::edit
 * @see app/Http/Controllers/ShiftController.php:60
 * @route '/shifts/{shift}/edit'
 */
export const edit = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/shifts/{shift}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ShiftController::edit
 * @see app/Http/Controllers/ShiftController.php:60
 * @route '/shifts/{shift}/edit'
 */
edit.url = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { shift: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { shift: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    shift: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        shift: typeof args.shift === 'object'
                ? args.shift.id
                : args.shift,
                }

    return edit.definition.url
            .replace('{shift}', parsedArgs.shift.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ShiftController::edit
 * @see app/Http/Controllers/ShiftController.php:60
 * @route '/shifts/{shift}/edit'
 */
edit.get = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ShiftController::edit
 * @see app/Http/Controllers/ShiftController.php:60
 * @route '/shifts/{shift}/edit'
 */
edit.head = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ShiftController::edit
 * @see app/Http/Controllers/ShiftController.php:60
 * @route '/shifts/{shift}/edit'
 */
    const editForm = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ShiftController::edit
 * @see app/Http/Controllers/ShiftController.php:60
 * @route '/shifts/{shift}/edit'
 */
        editForm.get = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ShiftController::edit
 * @see app/Http/Controllers/ShiftController.php:60
 * @route '/shifts/{shift}/edit'
 */
        editForm.head = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\ShiftController::update
 * @see app/Http/Controllers/ShiftController.php:69
 * @route '/shifts/{shift}'
 */
export const update = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/shifts/{shift}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\ShiftController::update
 * @see app/Http/Controllers/ShiftController.php:69
 * @route '/shifts/{shift}'
 */
update.url = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { shift: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { shift: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    shift: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        shift: typeof args.shift === 'object'
                ? args.shift.id
                : args.shift,
                }

    return update.definition.url
            .replace('{shift}', parsedArgs.shift.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ShiftController::update
 * @see app/Http/Controllers/ShiftController.php:69
 * @route '/shifts/{shift}'
 */
update.put = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\ShiftController::update
 * @see app/Http/Controllers/ShiftController.php:69
 * @route '/shifts/{shift}'
 */
    const updateForm = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ShiftController::update
 * @see app/Http/Controllers/ShiftController.php:69
 * @route '/shifts/{shift}'
 */
        updateForm.put = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\ShiftController::destroy
 * @see app/Http/Controllers/ShiftController.php:83
 * @route '/shifts/{shift}'
 */
export const destroy = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/shifts/{shift}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ShiftController::destroy
 * @see app/Http/Controllers/ShiftController.php:83
 * @route '/shifts/{shift}'
 */
destroy.url = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { shift: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { shift: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    shift: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        shift: typeof args.shift === 'object'
                ? args.shift.id
                : args.shift,
                }

    return destroy.definition.url
            .replace('{shift}', parsedArgs.shift.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ShiftController::destroy
 * @see app/Http/Controllers/ShiftController.php:83
 * @route '/shifts/{shift}'
 */
destroy.delete = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\ShiftController::destroy
 * @see app/Http/Controllers/ShiftController.php:83
 * @route '/shifts/{shift}'
 */
    const destroyForm = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ShiftController::destroy
 * @see app/Http/Controllers/ShiftController.php:83
 * @route '/shifts/{shift}'
 */
        destroyForm.delete = (args: { shift: number | { id: number } } | [shift: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const shifts = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default shifts