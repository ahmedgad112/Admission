import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
export const store = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/tasks/{task}/attachments',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
store.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
store.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
    const storeForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\TaskController::store
 * @see app/Http/Controllers/TaskController.php:220
 * @route '/tasks/{task}/attachments'
 */
        storeForm.post = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
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
const attachments = {
    store: Object.assign(store, store),
download: Object.assign(download, download),
}

export default attachments