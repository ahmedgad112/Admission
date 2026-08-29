import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AttendanceDayController::index
 * @see app/Http/Controllers/AttendanceDayController.php:23
 * @route '/attendance/days'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/attendance/days',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceDayController::index
 * @see app/Http/Controllers/AttendanceDayController.php:23
 * @route '/attendance/days'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceDayController::index
 * @see app/Http/Controllers/AttendanceDayController.php:23
 * @route '/attendance/days'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceDayController::index
 * @see app/Http/Controllers/AttendanceDayController.php:23
 * @route '/attendance/days'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceDayController::index
 * @see app/Http/Controllers/AttendanceDayController.php:23
 * @route '/attendance/days'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceDayController::index
 * @see app/Http/Controllers/AttendanceDayController.php:23
 * @route '/attendance/days'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceDayController::index
 * @see app/Http/Controllers/AttendanceDayController.php:23
 * @route '/attendance/days'
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
* @see \App\Http\Controllers\AttendanceDayController::create
 * @see app/Http/Controllers/AttendanceDayController.php:77
 * @route '/attendance/days/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/attendance/days/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceDayController::create
 * @see app/Http/Controllers/AttendanceDayController.php:77
 * @route '/attendance/days/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceDayController::create
 * @see app/Http/Controllers/AttendanceDayController.php:77
 * @route '/attendance/days/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceDayController::create
 * @see app/Http/Controllers/AttendanceDayController.php:77
 * @route '/attendance/days/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceDayController::create
 * @see app/Http/Controllers/AttendanceDayController.php:77
 * @route '/attendance/days/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceDayController::create
 * @see app/Http/Controllers/AttendanceDayController.php:77
 * @route '/attendance/days/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceDayController::create
 * @see app/Http/Controllers/AttendanceDayController.php:77
 * @route '/attendance/days/create'
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
* @see \App\Http\Controllers\AttendanceDayController::store
 * @see app/Http/Controllers/AttendanceDayController.php:84
 * @route '/attendance/days'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/attendance/days',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceDayController::store
 * @see app/Http/Controllers/AttendanceDayController.php:84
 * @route '/attendance/days'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceDayController::store
 * @see app/Http/Controllers/AttendanceDayController.php:84
 * @route '/attendance/days'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceDayController::store
 * @see app/Http/Controllers/AttendanceDayController.php:84
 * @route '/attendance/days'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceDayController::store
 * @see app/Http/Controllers/AttendanceDayController.php:84
 * @route '/attendance/days'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\AttendanceDayController::show
 * @see app/Http/Controllers/AttendanceDayController.php:48
 * @route '/attendance/days/{attendanceDay}'
 */
export const show = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/attendance/days/{attendanceDay}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceDayController::show
 * @see app/Http/Controllers/AttendanceDayController.php:48
 * @route '/attendance/days/{attendanceDay}'
 */
show.url = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { attendanceDay: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { attendanceDay: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    attendanceDay: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        attendanceDay: typeof args.attendanceDay === 'object'
                ? args.attendanceDay.id
                : args.attendanceDay,
                }

    return show.definition.url
            .replace('{attendanceDay}', parsedArgs.attendanceDay.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceDayController::show
 * @see app/Http/Controllers/AttendanceDayController.php:48
 * @route '/attendance/days/{attendanceDay}'
 */
show.get = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceDayController::show
 * @see app/Http/Controllers/AttendanceDayController.php:48
 * @route '/attendance/days/{attendanceDay}'
 */
show.head = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceDayController::show
 * @see app/Http/Controllers/AttendanceDayController.php:48
 * @route '/attendance/days/{attendanceDay}'
 */
    const showForm = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceDayController::show
 * @see app/Http/Controllers/AttendanceDayController.php:48
 * @route '/attendance/days/{attendanceDay}'
 */
        showForm.get = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceDayController::show
 * @see app/Http/Controllers/AttendanceDayController.php:48
 * @route '/attendance/days/{attendanceDay}'
 */
        showForm.head = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\AttendanceDayController::edit
 * @see app/Http/Controllers/AttendanceDayController.php:106
 * @route '/attendance/days/{attendanceDay}/edit'
 */
export const edit = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/attendance/days/{attendanceDay}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceDayController::edit
 * @see app/Http/Controllers/AttendanceDayController.php:106
 * @route '/attendance/days/{attendanceDay}/edit'
 */
edit.url = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { attendanceDay: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { attendanceDay: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    attendanceDay: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        attendanceDay: typeof args.attendanceDay === 'object'
                ? args.attendanceDay.id
                : args.attendanceDay,
                }

    return edit.definition.url
            .replace('{attendanceDay}', parsedArgs.attendanceDay.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceDayController::edit
 * @see app/Http/Controllers/AttendanceDayController.php:106
 * @route '/attendance/days/{attendanceDay}/edit'
 */
edit.get = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceDayController::edit
 * @see app/Http/Controllers/AttendanceDayController.php:106
 * @route '/attendance/days/{attendanceDay}/edit'
 */
edit.head = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceDayController::edit
 * @see app/Http/Controllers/AttendanceDayController.php:106
 * @route '/attendance/days/{attendanceDay}/edit'
 */
    const editForm = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceDayController::edit
 * @see app/Http/Controllers/AttendanceDayController.php:106
 * @route '/attendance/days/{attendanceDay}/edit'
 */
        editForm.get = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceDayController::edit
 * @see app/Http/Controllers/AttendanceDayController.php:106
 * @route '/attendance/days/{attendanceDay}/edit'
 */
        editForm.head = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\AttendanceDayController::update
 * @see app/Http/Controllers/AttendanceDayController.php:123
 * @route '/attendance/days/{attendanceDay}'
 */
export const update = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/attendance/days/{attendanceDay}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\AttendanceDayController::update
 * @see app/Http/Controllers/AttendanceDayController.php:123
 * @route '/attendance/days/{attendanceDay}'
 */
update.url = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { attendanceDay: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { attendanceDay: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    attendanceDay: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        attendanceDay: typeof args.attendanceDay === 'object'
                ? args.attendanceDay.id
                : args.attendanceDay,
                }

    return update.definition.url
            .replace('{attendanceDay}', parsedArgs.attendanceDay.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceDayController::update
 * @see app/Http/Controllers/AttendanceDayController.php:123
 * @route '/attendance/days/{attendanceDay}'
 */
update.put = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\AttendanceDayController::update
 * @see app/Http/Controllers/AttendanceDayController.php:123
 * @route '/attendance/days/{attendanceDay}'
 */
    const updateForm = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceDayController::update
 * @see app/Http/Controllers/AttendanceDayController.php:123
 * @route '/attendance/days/{attendanceDay}'
 */
        updateForm.put = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\AttendanceDayController::destroy
 * @see app/Http/Controllers/AttendanceDayController.php:138
 * @route '/attendance/days/{attendanceDay}'
 */
export const destroy = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/attendance/days/{attendanceDay}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\AttendanceDayController::destroy
 * @see app/Http/Controllers/AttendanceDayController.php:138
 * @route '/attendance/days/{attendanceDay}'
 */
destroy.url = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { attendanceDay: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { attendanceDay: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    attendanceDay: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        attendanceDay: typeof args.attendanceDay === 'object'
                ? args.attendanceDay.id
                : args.attendanceDay,
                }

    return destroy.definition.url
            .replace('{attendanceDay}', parsedArgs.attendanceDay.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceDayController::destroy
 * @see app/Http/Controllers/AttendanceDayController.php:138
 * @route '/attendance/days/{attendanceDay}'
 */
destroy.delete = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\AttendanceDayController::destroy
 * @see app/Http/Controllers/AttendanceDayController.php:138
 * @route '/attendance/days/{attendanceDay}'
 */
    const destroyForm = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceDayController::destroy
 * @see app/Http/Controllers/AttendanceDayController.php:138
 * @route '/attendance/days/{attendanceDay}'
 */
        destroyForm.delete = (args: { attendanceDay: number | { id: number } } | [attendanceDay: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const AttendanceDayController = { index, create, store, show, edit, update, destroy }

export default AttendanceDayController