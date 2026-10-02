import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\StaffController::index
 * @see app/Http/Controllers/StaffController.php:46
 * @route '/staff'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/staff',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\StaffController::index
 * @see app/Http/Controllers/StaffController.php:46
 * @route '/staff'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::index
 * @see app/Http/Controllers/StaffController.php:46
 * @route '/staff'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\StaffController::index
 * @see app/Http/Controllers/StaffController.php:46
 * @route '/staff'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\StaffController::index
 * @see app/Http/Controllers/StaffController.php:46
 * @route '/staff'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\StaffController::index
 * @see app/Http/Controllers/StaffController.php:46
 * @route '/staff'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\StaffController::index
 * @see app/Http/Controllers/StaffController.php:46
 * @route '/staff'
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
* @see \App\Http\Controllers\StaffController::create
 * @see app/Http/Controllers/StaffController.php:150
 * @route '/staff/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/staff/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\StaffController::create
 * @see app/Http/Controllers/StaffController.php:150
 * @route '/staff/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::create
 * @see app/Http/Controllers/StaffController.php:150
 * @route '/staff/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\StaffController::create
 * @see app/Http/Controllers/StaffController.php:150
 * @route '/staff/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\StaffController::create
 * @see app/Http/Controllers/StaffController.php:150
 * @route '/staff/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\StaffController::create
 * @see app/Http/Controllers/StaffController.php:150
 * @route '/staff/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\StaffController::create
 * @see app/Http/Controllers/StaffController.php:150
 * @route '/staff/create'
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
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:323
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
 * @see app/Http/Controllers/StaffController.php:323
 * @route '/staff/import/template'
 */
template.url = (options?: RouteQueryOptions) => {
    return template.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:323
 * @route '/staff/import/template'
 */
template.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: template.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:323
 * @route '/staff/import/template'
 */
template.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: template.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:323
 * @route '/staff/import/template'
 */
    const templateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: template.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:323
 * @route '/staff/import/template'
 */
        templateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: template.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\StaffController::template
 * @see app/Http/Controllers/StaffController.php:323
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
/**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:330
 * @route '/staff/import'
 */
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/staff/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:330
 * @route '/staff/import'
 */
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:330
 * @route '/staff/import'
 */
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:330
 * @route '/staff/import'
 */
    const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::importMethod
 * @see app/Http/Controllers/StaffController.php:330
 * @route '/staff/import'
 */
        importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
/**
* @see \App\Http\Controllers\StaffController::leaveDaysTemplate
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
export const leaveDaysTemplate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leaveDaysTemplate.url(options),
    method: 'get',
})

leaveDaysTemplate.definition = {
    methods: ["get","head"],
    url: '/staff/leave-days/template',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\StaffController::leaveDaysTemplate
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
leaveDaysTemplate.url = (options?: RouteQueryOptions) => {
    return leaveDaysTemplate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::leaveDaysTemplate
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
leaveDaysTemplate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leaveDaysTemplate.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\StaffController::leaveDaysTemplate
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
leaveDaysTemplate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: leaveDaysTemplate.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\StaffController::leaveDaysTemplate
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
    const leaveDaysTemplateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: leaveDaysTemplate.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\StaffController::leaveDaysTemplate
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
        leaveDaysTemplateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leaveDaysTemplate.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\StaffController::leaveDaysTemplate
 * @see app/Http/Controllers/StaffController.php:266
 * @route '/staff/leave-days/template'
 */
        leaveDaysTemplateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leaveDaysTemplate.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    leaveDaysTemplate.form = leaveDaysTemplateForm
/**
* @see \App\Http\Controllers\StaffController::importLeaveDays
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
export const importLeaveDays = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importLeaveDays.url(options),
    method: 'post',
})

importLeaveDays.definition = {
    methods: ["post"],
    url: '/staff/leave-days/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\StaffController::importLeaveDays
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
importLeaveDays.url = (options?: RouteQueryOptions) => {
    return importLeaveDays.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::importLeaveDays
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
importLeaveDays.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importLeaveDays.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\StaffController::importLeaveDays
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
    const importLeaveDaysForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importLeaveDays.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::importLeaveDays
 * @see app/Http/Controllers/StaffController.php:273
 * @route '/staff/leave-days/import'
 */
        importLeaveDaysForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importLeaveDays.url(options),
            method: 'post',
        })
    
    importLeaveDays.form = importLeaveDaysForm
/**
* @see \App\Http\Controllers\StaffController::bulkAdjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
export const bulkAdjustLeaveDays = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAdjustLeaveDays.url(options),
    method: 'post',
})

bulkAdjustLeaveDays.definition = {
    methods: ["post"],
    url: '/staff/leave-days',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\StaffController::bulkAdjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
bulkAdjustLeaveDays.url = (options?: RouteQueryOptions) => {
    return bulkAdjustLeaveDays.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::bulkAdjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
bulkAdjustLeaveDays.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAdjustLeaveDays.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\StaffController::bulkAdjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
    const bulkAdjustLeaveDaysForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkAdjustLeaveDays.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::bulkAdjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:236
 * @route '/staff/leave-days'
 */
        bulkAdjustLeaveDaysForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkAdjustLeaveDays.url(options),
            method: 'post',
        })
    
    bulkAdjustLeaveDays.form = bulkAdjustLeaveDaysForm
/**
* @see \App\Http\Controllers\StaffController::store
 * @see app/Http/Controllers/StaffController.php:157
 * @route '/staff'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/staff',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\StaffController::store
 * @see app/Http/Controllers/StaffController.php:157
 * @route '/staff'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::store
 * @see app/Http/Controllers/StaffController.php:157
 * @route '/staff'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\StaffController::store
 * @see app/Http/Controllers/StaffController.php:157
 * @route '/staff'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::store
 * @see app/Http/Controllers/StaffController.php:157
 * @route '/staff'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\StaffController::show
 * @see app/Http/Controllers/StaffController.php:118
 * @route '/staff/{user}'
 */
export const show = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/staff/{user}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\StaffController::show
 * @see app/Http/Controllers/StaffController.php:118
 * @route '/staff/{user}'
 */
show.url = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::show
 * @see app/Http/Controllers/StaffController.php:118
 * @route '/staff/{user}'
 */
show.get = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\StaffController::show
 * @see app/Http/Controllers/StaffController.php:118
 * @route '/staff/{user}'
 */
show.head = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\StaffController::show
 * @see app/Http/Controllers/StaffController.php:118
 * @route '/staff/{user}'
 */
    const showForm = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\StaffController::show
 * @see app/Http/Controllers/StaffController.php:118
 * @route '/staff/{user}'
 */
        showForm.get = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\StaffController::show
 * @see app/Http/Controllers/StaffController.php:118
 * @route '/staff/{user}'
 */
        showForm.head = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\StaffController::edit
 * @see app/Http/Controllers/StaffController.php:174
 * @route '/staff/{user}/edit'
 */
export const edit = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/staff/{user}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\StaffController::edit
 * @see app/Http/Controllers/StaffController.php:174
 * @route '/staff/{user}/edit'
 */
edit.url = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::edit
 * @see app/Http/Controllers/StaffController.php:174
 * @route '/staff/{user}/edit'
 */
edit.get = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\StaffController::edit
 * @see app/Http/Controllers/StaffController.php:174
 * @route '/staff/{user}/edit'
 */
edit.head = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\StaffController::edit
 * @see app/Http/Controllers/StaffController.php:174
 * @route '/staff/{user}/edit'
 */
    const editForm = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\StaffController::edit
 * @see app/Http/Controllers/StaffController.php:174
 * @route '/staff/{user}/edit'
 */
        editForm.get = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\StaffController::edit
 * @see app/Http/Controllers/StaffController.php:174
 * @route '/staff/{user}/edit'
 */
        editForm.head = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\StaffController::update
 * @see app/Http/Controllers/StaffController.php:196
 * @route '/staff/{user}'
 */
export const update = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/staff/{user}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\StaffController::update
 * @see app/Http/Controllers/StaffController.php:196
 * @route '/staff/{user}'
 */
update.url = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::update
 * @see app/Http/Controllers/StaffController.php:196
 * @route '/staff/{user}'
 */
update.put = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\StaffController::update
 * @see app/Http/Controllers/StaffController.php:196
 * @route '/staff/{user}'
 */
    const updateForm = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::update
 * @see app/Http/Controllers/StaffController.php:196
 * @route '/staff/{user}'
 */
        updateForm.put = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\StaffController::adjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
export const adjustLeaveDays = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: adjustLeaveDays.url(args, options),
    method: 'post',
})

adjustLeaveDays.definition = {
    methods: ["post"],
    url: '/staff/{user}/leave-days',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\StaffController::adjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
adjustLeaveDays.url = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return adjustLeaveDays.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::adjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
adjustLeaveDays.post = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: adjustLeaveDays.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\StaffController::adjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
    const adjustLeaveDaysForm = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: adjustLeaveDays.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::adjustLeaveDays
 * @see app/Http/Controllers/StaffController.php:211
 * @route '/staff/{user}/leave-days'
 */
        adjustLeaveDaysForm.post = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: adjustLeaveDays.url(args, options),
            method: 'post',
        })
    
    adjustLeaveDays.form = adjustLeaveDaysForm
/**
* @see \App\Http\Controllers\StaffController::destroy
 * @see app/Http/Controllers/StaffController.php:301
 * @route '/staff/{user}'
 */
export const destroy = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/staff/{user}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\StaffController::destroy
 * @see app/Http/Controllers/StaffController.php:301
 * @route '/staff/{user}'
 */
destroy.url = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\StaffController::destroy
 * @see app/Http/Controllers/StaffController.php:301
 * @route '/staff/{user}'
 */
destroy.delete = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\StaffController::destroy
 * @see app/Http/Controllers/StaffController.php:301
 * @route '/staff/{user}'
 */
    const destroyForm = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\StaffController::destroy
 * @see app/Http/Controllers/StaffController.php:301
 * @route '/staff/{user}'
 */
        destroyForm.delete = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const StaffController = { index, create, template, importMethod, leaveDaysTemplate, importLeaveDays, bulkAdjustLeaveDays, store, show, edit, update, adjustLeaveDays, destroy, import: importMethod }

export default StaffController