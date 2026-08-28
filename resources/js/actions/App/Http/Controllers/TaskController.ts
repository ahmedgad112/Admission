import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/api/tasks'
 */
const index91948aa56ff77048c3814550de6febb9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index91948aa56ff77048c3814550de6febb9.url(options),
    method: 'get',
})

index91948aa56ff77048c3814550de6febb9.definition = {
    methods: ["get","head"],
    url: '/api/tasks',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/api/tasks'
 */
index91948aa56ff77048c3814550de6febb9.url = (options?: RouteQueryOptions) => {
    return index91948aa56ff77048c3814550de6febb9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/api/tasks'
 */
index91948aa56ff77048c3814550de6febb9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index91948aa56ff77048c3814550de6febb9.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/api/tasks'
 */
index91948aa56ff77048c3814550de6febb9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index91948aa56ff77048c3814550de6febb9.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/api/tasks'
 */
    const index91948aa56ff77048c3814550de6febb9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index91948aa56ff77048c3814550de6febb9.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/api/tasks'
 */
        index91948aa56ff77048c3814550de6febb9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index91948aa56ff77048c3814550de6febb9.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/api/tasks'
 */
        index91948aa56ff77048c3814550de6febb9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index91948aa56ff77048c3814550de6febb9.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index91948aa56ff77048c3814550de6febb9.form = index91948aa56ff77048c3814550de6febb9Form
    /**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/tasks'
 */
const indexaefb1b9af2c8d8e723708dc6f7e19610 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexaefb1b9af2c8d8e723708dc6f7e19610.url(options),
    method: 'get',
})

indexaefb1b9af2c8d8e723708dc6f7e19610.definition = {
    methods: ["get","head"],
    url: '/tasks',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/tasks'
 */
indexaefb1b9af2c8d8e723708dc6f7e19610.url = (options?: RouteQueryOptions) => {
    return indexaefb1b9af2c8d8e723708dc6f7e19610.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/tasks'
 */
indexaefb1b9af2c8d8e723708dc6f7e19610.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexaefb1b9af2c8d8e723708dc6f7e19610.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/tasks'
 */
indexaefb1b9af2c8d8e723708dc6f7e19610.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexaefb1b9af2c8d8e723708dc6f7e19610.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/tasks'
 */
    const indexaefb1b9af2c8d8e723708dc6f7e19610Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexaefb1b9af2c8d8e723708dc6f7e19610.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/tasks'
 */
        indexaefb1b9af2c8d8e723708dc6f7e19610Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexaefb1b9af2c8d8e723708dc6f7e19610.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\TaskController::index
 * @see app/Http/Controllers/TaskController.php:33
 * @route '/tasks'
 */
        indexaefb1b9af2c8d8e723708dc6f7e19610Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexaefb1b9af2c8d8e723708dc6f7e19610.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexaefb1b9af2c8d8e723708dc6f7e19610.form = indexaefb1b9af2c8d8e723708dc6f7e19610Form

/**
* Multiple routes resolve to \App\Http\Controllers\TaskController::index, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `index['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const index = {
    '/api/tasks': index91948aa56ff77048c3814550de6febb9,
    '/tasks': indexaefb1b9af2c8d8e723708dc6f7e19610,
}

/**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/api/tasks'
 */
const store91948aa56ff77048c3814550de6febb9 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store91948aa56ff77048c3814550de6febb9.url(options),
    method: 'post',
})

store91948aa56ff77048c3814550de6febb9.definition = {
    methods: ["post"],
    url: '/api/tasks',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/api/tasks'
 */
store91948aa56ff77048c3814550de6febb9.url = (options?: RouteQueryOptions) => {
    return store91948aa56ff77048c3814550de6febb9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/api/tasks'
 */
store91948aa56ff77048c3814550de6febb9.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store91948aa56ff77048c3814550de6febb9.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/api/tasks'
 */
    const store91948aa56ff77048c3814550de6febb9Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store91948aa56ff77048c3814550de6febb9.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/api/tasks'
 */
        store91948aa56ff77048c3814550de6febb9Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store91948aa56ff77048c3814550de6febb9.url(options),
            method: 'post',
        })
    
    store91948aa56ff77048c3814550de6febb9.form = store91948aa56ff77048c3814550de6febb9Form
    /**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/tasks'
 */
const storeaefb1b9af2c8d8e723708dc6f7e19610 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeaefb1b9af2c8d8e723708dc6f7e19610.url(options),
    method: 'post',
})

storeaefb1b9af2c8d8e723708dc6f7e19610.definition = {
    methods: ["post"],
    url: '/tasks',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/tasks'
 */
storeaefb1b9af2c8d8e723708dc6f7e19610.url = (options?: RouteQueryOptions) => {
    return storeaefb1b9af2c8d8e723708dc6f7e19610.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/tasks'
 */
storeaefb1b9af2c8d8e723708dc6f7e19610.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeaefb1b9af2c8d8e723708dc6f7e19610.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/tasks'
 */
    const storeaefb1b9af2c8d8e723708dc6f7e19610Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: storeaefb1b9af2c8d8e723708dc6f7e19610.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:71
 * @route '/tasks'
 */
        storeaefb1b9af2c8d8e723708dc6f7e19610Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: storeaefb1b9af2c8d8e723708dc6f7e19610.url(options),
            method: 'post',
        })
    
    storeaefb1b9af2c8d8e723708dc6f7e19610.form = storeaefb1b9af2c8d8e723708dc6f7e19610Form

/**
* Multiple routes resolve to \App\Http\Controllers\TaskController::store, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `store['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const store = {
    '/api/tasks': store91948aa56ff77048c3814550de6febb9,
    '/tasks': storeaefb1b9af2c8d8e723708dc6f7e19610,
}

/**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/api/tasks/{task}'
 */
const updatecd5b7b7ad136d9ce29054183b4a0e99d = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updatecd5b7b7ad136d9ce29054183b4a0e99d.url(args, options),
    method: 'put',
})

updatecd5b7b7ad136d9ce29054183b4a0e99d.definition = {
    methods: ["put"],
    url: '/api/tasks/{task}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/api/tasks/{task}'
 */
updatecd5b7b7ad136d9ce29054183b4a0e99d.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return updatecd5b7b7ad136d9ce29054183b4a0e99d.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/api/tasks/{task}'
 */
updatecd5b7b7ad136d9ce29054183b4a0e99d.put = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updatecd5b7b7ad136d9ce29054183b4a0e99d.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/api/tasks/{task}'
 */
    const updatecd5b7b7ad136d9ce29054183b4a0e99dForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updatecd5b7b7ad136d9ce29054183b4a0e99d.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/api/tasks/{task}'
 */
        updatecd5b7b7ad136d9ce29054183b4a0e99dForm.put = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updatecd5b7b7ad136d9ce29054183b4a0e99d.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updatecd5b7b7ad136d9ce29054183b4a0e99d.form = updatecd5b7b7ad136d9ce29054183b4a0e99dForm
    /**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/tasks/{task}'
 */
const updatea9210b42b2fb5ac9933186a51e3242ee = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updatea9210b42b2fb5ac9933186a51e3242ee.url(args, options),
    method: 'put',
})

updatea9210b42b2fb5ac9933186a51e3242ee.definition = {
    methods: ["put"],
    url: '/tasks/{task}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/tasks/{task}'
 */
updatea9210b42b2fb5ac9933186a51e3242ee.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return updatea9210b42b2fb5ac9933186a51e3242ee.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/tasks/{task}'
 */
updatea9210b42b2fb5ac9933186a51e3242ee.put = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updatea9210b42b2fb5ac9933186a51e3242ee.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/tasks/{task}'
 */
    const updatea9210b42b2fb5ac9933186a51e3242eeForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updatea9210b42b2fb5ac9933186a51e3242ee.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::update
 * @see app/Http/Controllers/TaskController.php:133
 * @route '/tasks/{task}'
 */
        updatea9210b42b2fb5ac9933186a51e3242eeForm.put = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updatea9210b42b2fb5ac9933186a51e3242ee.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updatea9210b42b2fb5ac9933186a51e3242ee.form = updatea9210b42b2fb5ac9933186a51e3242eeForm

/**
* Multiple routes resolve to \App\Http\Controllers\TaskController::update, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `update['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const update = {
    '/api/tasks/{task}': updatecd5b7b7ad136d9ce29054183b4a0e99d,
    '/tasks/{task}': updatea9210b42b2fb5ac9933186a51e3242ee,
}

/**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/api/tasks/{task}/transition'
 */
const transition686ea5cb07e7cd6b5f6c4c1e9052b425 = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transition686ea5cb07e7cd6b5f6c4c1e9052b425.url(args, options),
    method: 'post',
})

transition686ea5cb07e7cd6b5f6c4c1e9052b425.definition = {
    methods: ["post"],
    url: '/api/tasks/{task}/transition',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/api/tasks/{task}/transition'
 */
transition686ea5cb07e7cd6b5f6c4c1e9052b425.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return transition686ea5cb07e7cd6b5f6c4c1e9052b425.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/api/tasks/{task}/transition'
 */
transition686ea5cb07e7cd6b5f6c4c1e9052b425.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transition686ea5cb07e7cd6b5f6c4c1e9052b425.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/api/tasks/{task}/transition'
 */
    const transition686ea5cb07e7cd6b5f6c4c1e9052b425Form = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: transition686ea5cb07e7cd6b5f6c4c1e9052b425.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/api/tasks/{task}/transition'
 */
        transition686ea5cb07e7cd6b5f6c4c1e9052b425Form.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: transition686ea5cb07e7cd6b5f6c4c1e9052b425.url(args, options),
            method: 'post',
        })
    
    transition686ea5cb07e7cd6b5f6c4c1e9052b425.form = transition686ea5cb07e7cd6b5f6c4c1e9052b425Form
    /**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/tasks/{task}/transition'
 */
const transitionc7052dda729b132055f6611b9ce787b2 = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transitionc7052dda729b132055f6611b9ce787b2.url(args, options),
    method: 'post',
})

transitionc7052dda729b132055f6611b9ce787b2.definition = {
    methods: ["post"],
    url: '/tasks/{task}/transition',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/tasks/{task}/transition'
 */
transitionc7052dda729b132055f6611b9ce787b2.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return transitionc7052dda729b132055f6611b9ce787b2.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/tasks/{task}/transition'
 */
transitionc7052dda729b132055f6611b9ce787b2.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transitionc7052dda729b132055f6611b9ce787b2.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/tasks/{task}/transition'
 */
    const transitionc7052dda729b132055f6611b9ce787b2Form = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: transitionc7052dda729b132055f6611b9ce787b2.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::transition
 * @see app/Http/Controllers/TaskController.php:180
 * @route '/tasks/{task}/transition'
 */
        transitionc7052dda729b132055f6611b9ce787b2Form.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: transitionc7052dda729b132055f6611b9ce787b2.url(args, options),
            method: 'post',
        })
    
    transitionc7052dda729b132055f6611b9ce787b2.form = transitionc7052dda729b132055f6611b9ce787b2Form

/**
* Multiple routes resolve to \App\Http\Controllers\TaskController::transition, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `transition['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const transition = {
    '/api/tasks/{task}/transition': transition686ea5cb07e7cd6b5f6c4c1e9052b425,
    '/tasks/{task}/transition': transitionc7052dda729b132055f6611b9ce787b2,
}

/**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/api/tasks/{task}/comments'
 */
const comment5257c59bca4317f48eb551a76ed3dc73 = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: comment5257c59bca4317f48eb551a76ed3dc73.url(args, options),
    method: 'post',
})

comment5257c59bca4317f48eb551a76ed3dc73.definition = {
    methods: ["post"],
    url: '/api/tasks/{task}/comments',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/api/tasks/{task}/comments'
 */
comment5257c59bca4317f48eb551a76ed3dc73.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return comment5257c59bca4317f48eb551a76ed3dc73.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/api/tasks/{task}/comments'
 */
comment5257c59bca4317f48eb551a76ed3dc73.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: comment5257c59bca4317f48eb551a76ed3dc73.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/api/tasks/{task}/comments'
 */
    const comment5257c59bca4317f48eb551a76ed3dc73Form = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: comment5257c59bca4317f48eb551a76ed3dc73.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/api/tasks/{task}/comments'
 */
        comment5257c59bca4317f48eb551a76ed3dc73Form.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: comment5257c59bca4317f48eb551a76ed3dc73.url(args, options),
            method: 'post',
        })
    
    comment5257c59bca4317f48eb551a76ed3dc73.form = comment5257c59bca4317f48eb551a76ed3dc73Form
    /**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/tasks/{task}/comments'
 */
const commente22b99cc138c6a76b4bd5f803e30fb9d = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: commente22b99cc138c6a76b4bd5f803e30fb9d.url(args, options),
    method: 'post',
})

commente22b99cc138c6a76b4bd5f803e30fb9d.definition = {
    methods: ["post"],
    url: '/tasks/{task}/comments',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/tasks/{task}/comments'
 */
commente22b99cc138c6a76b4bd5f803e30fb9d.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return commente22b99cc138c6a76b4bd5f803e30fb9d.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/tasks/{task}/comments'
 */
commente22b99cc138c6a76b4bd5f803e30fb9d.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: commente22b99cc138c6a76b4bd5f803e30fb9d.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/tasks/{task}/comments'
 */
    const commente22b99cc138c6a76b4bd5f803e30fb9dForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: commente22b99cc138c6a76b4bd5f803e30fb9d.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::comment
 * @see app/Http/Controllers/TaskController.php:202
 * @route '/tasks/{task}/comments'
 */
        commente22b99cc138c6a76b4bd5f803e30fb9dForm.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: commente22b99cc138c6a76b4bd5f803e30fb9d.url(args, options),
            method: 'post',
        })
    
    commente22b99cc138c6a76b4bd5f803e30fb9d.form = commente22b99cc138c6a76b4bd5f803e30fb9dForm

/**
* Multiple routes resolve to \App\Http\Controllers\TaskController::comment, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `comment['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const comment = {
    '/api/tasks/{task}/comments': comment5257c59bca4317f48eb551a76ed3dc73,
    '/tasks/{task}/comments': commente22b99cc138c6a76b4bd5f803e30fb9d,
}

/**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/api/tasks/{task}/attachments'
 */
const attache5b895ec6f59e72e0d04f3ef63da5e3f = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: attache5b895ec6f59e72e0d04f3ef63da5e3f.url(args, options),
    method: 'post',
})

attache5b895ec6f59e72e0d04f3ef63da5e3f.definition = {
    methods: ["post"],
    url: '/api/tasks/{task}/attachments',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/api/tasks/{task}/attachments'
 */
attache5b895ec6f59e72e0d04f3ef63da5e3f.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return attache5b895ec6f59e72e0d04f3ef63da5e3f.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/api/tasks/{task}/attachments'
 */
attache5b895ec6f59e72e0d04f3ef63da5e3f.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: attache5b895ec6f59e72e0d04f3ef63da5e3f.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/api/tasks/{task}/attachments'
 */
    const attache5b895ec6f59e72e0d04f3ef63da5e3fForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: attache5b895ec6f59e72e0d04f3ef63da5e3f.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/api/tasks/{task}/attachments'
 */
        attache5b895ec6f59e72e0d04f3ef63da5e3fForm.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: attache5b895ec6f59e72e0d04f3ef63da5e3f.url(args, options),
            method: 'post',
        })
    
    attache5b895ec6f59e72e0d04f3ef63da5e3f.form = attache5b895ec6f59e72e0d04f3ef63da5e3fForm
    /**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
const attach5019879a9ff688c41e006fcb75f96980 = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: attach5019879a9ff688c41e006fcb75f96980.url(args, options),
    method: 'post',
})

attach5019879a9ff688c41e006fcb75f96980.definition = {
    methods: ["post"],
    url: '/tasks/{task}/attachments',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
attach5019879a9ff688c41e006fcb75f96980.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return attach5019879a9ff688c41e006fcb75f96980.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
attach5019879a9ff688c41e006fcb75f96980.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: attach5019879a9ff688c41e006fcb75f96980.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
    const attach5019879a9ff688c41e006fcb75f96980Form = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: attach5019879a9ff688c41e006fcb75f96980.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::attach
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
        attach5019879a9ff688c41e006fcb75f96980Form.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: attach5019879a9ff688c41e006fcb75f96980.url(args, options),
            method: 'post',
        })
    
    attach5019879a9ff688c41e006fcb75f96980.form = attach5019879a9ff688c41e006fcb75f96980Form

/**
* Multiple routes resolve to \App\Http\Controllers\TaskController::attach, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `attach['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const attach = {
    '/api/tasks/{task}/attachments': attache5b895ec6f59e72e0d04f3ef63da5e3f,
    '/tasks/{task}/attachments': attach5019879a9ff688c41e006fcb75f96980,
}

/**
* @see \App\Http\Controllers\TaskController::create
 * @see app/Http/Controllers/TaskController.php:64
 * @route '/tasks/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/tasks/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TaskController::create
 * @see app/Http/Controllers/TaskController.php:64
 * @route '/tasks/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::create
 * @see app/Http/Controllers/TaskController.php:64
 * @route '/tasks/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\TaskController::create
 * @see app/Http/Controllers/TaskController.php:64
 * @route '/tasks/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\TaskController::create
 * @see app/Http/Controllers/TaskController.php:64
 * @route '/tasks/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\TaskController::create
 * @see app/Http/Controllers/TaskController.php:64
 * @route '/tasks/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\TaskController::create
 * @see app/Http/Controllers/TaskController.php:64
 * @route '/tasks/create'
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
* @see \App\Http\Controllers\TaskController::show
 * @see app/Http/Controllers/TaskController.php:101
 * @route '/tasks/{task}'
 */
export const show = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/tasks/{task}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TaskController::show
 * @see app/Http/Controllers/TaskController.php:101
 * @route '/tasks/{task}'
 */
show.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return show.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::show
 * @see app/Http/Controllers/TaskController.php:101
 * @route '/tasks/{task}'
 */
show.get = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\TaskController::show
 * @see app/Http/Controllers/TaskController.php:101
 * @route '/tasks/{task}'
 */
show.head = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\TaskController::show
 * @see app/Http/Controllers/TaskController.php:101
 * @route '/tasks/{task}'
 */
    const showForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\TaskController::show
 * @see app/Http/Controllers/TaskController.php:101
 * @route '/tasks/{task}'
 */
        showForm.get = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\TaskController::show
 * @see app/Http/Controllers/TaskController.php:101
 * @route '/tasks/{task}'
 */
        showForm.head = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\TaskController::edit
 * @see app/Http/Controllers/TaskController.php:121
 * @route '/tasks/{task}/edit'
 */
export const edit = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/tasks/{task}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TaskController::edit
 * @see app/Http/Controllers/TaskController.php:121
 * @route '/tasks/{task}/edit'
 */
edit.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return edit.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::edit
 * @see app/Http/Controllers/TaskController.php:121
 * @route '/tasks/{task}/edit'
 */
edit.get = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\TaskController::edit
 * @see app/Http/Controllers/TaskController.php:121
 * @route '/tasks/{task}/edit'
 */
edit.head = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\TaskController::edit
 * @see app/Http/Controllers/TaskController.php:121
 * @route '/tasks/{task}/edit'
 */
    const editForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\TaskController::edit
 * @see app/Http/Controllers/TaskController.php:121
 * @route '/tasks/{task}/edit'
 */
        editForm.get = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\TaskController::edit
 * @see app/Http/Controllers/TaskController.php:121
 * @route '/tasks/{task}/edit'
 */
        editForm.head = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\TaskController::destroy
 * @see app/Http/Controllers/TaskController.php:171
 * @route '/tasks/{task}'
 */
export const destroy = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/tasks/{task}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\TaskController::destroy
 * @see app/Http/Controllers/TaskController.php:171
 * @route '/tasks/{task}'
 */
destroy.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return destroy.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::destroy
 * @see app/Http/Controllers/TaskController.php:171
 * @route '/tasks/{task}'
 */
destroy.delete = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\TaskController::destroy
 * @see app/Http/Controllers/TaskController.php:171
 * @route '/tasks/{task}'
 */
    const destroyForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::destroy
 * @see app/Http/Controllers/TaskController.php:171
 * @route '/tasks/{task}'
 */
        destroyForm.delete = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Http\Controllers\TaskController::download
 * @see app/Http/Controllers/TaskController.php:254
 * @route '/tasks/{task}/attachments/{attachment}'
 */
export const download = (args: { task: number | { id: number }, attachment: number | { id: number } } | [task: number | { id: number }, attachment: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/tasks/{task}/attachments/{attachment}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TaskController::download
 * @see app/Http/Controllers/TaskController.php:254
 * @route '/tasks/{task}/attachments/{attachment}'
 */
download.url = (args: { task: number | { id: number }, attachment: number | { id: number } } | [task: number | { id: number }, attachment: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                    attachment: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                                attachment: typeof args.attachment === 'object'
                ? args.attachment.id
                : args.attachment,
                }

    return download.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace('{attachment}', parsedArgs.attachment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::download
 * @see app/Http/Controllers/TaskController.php:254
 * @route '/tasks/{task}/attachments/{attachment}'
 */
download.get = (args: { task: number | { id: number }, attachment: number | { id: number } } | [task: number | { id: number }, attachment: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\TaskController::download
 * @see app/Http/Controllers/TaskController.php:254
 * @route '/tasks/{task}/attachments/{attachment}'
 */
download.head = (args: { task: number | { id: number }, attachment: number | { id: number } } | [task: number | { id: number }, attachment: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\TaskController::download
 * @see app/Http/Controllers/TaskController.php:254
 * @route '/tasks/{task}/attachments/{attachment}'
 */
    const downloadForm = (args: { task: number | { id: number }, attachment: number | { id: number } } | [task: number | { id: number }, attachment: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: download.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\TaskController::download
 * @see app/Http/Controllers/TaskController.php:254
 * @route '/tasks/{task}/attachments/{attachment}'
 */
        downloadForm.get = (args: { task: number | { id: number }, attachment: number | { id: number } } | [task: number | { id: number }, attachment: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\TaskController::download
 * @see app/Http/Controllers/TaskController.php:254
 * @route '/tasks/{task}/attachments/{attachment}'
 */
        downloadForm.head = (args: { task: number | { id: number }, attachment: number | { id: number } } | [task: number | { id: number }, attachment: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    download.form = downloadForm
const TaskController = { index, store, update, transition, comment, attach, create, show, edit, destroy, download }

export default TaskController