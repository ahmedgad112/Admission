import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/api/attendance/scan'
 */
const recordScan377c8f4ace464b17c47f597571d2375c = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordScan377c8f4ace464b17c47f597571d2375c.url(options),
    method: 'post',
})

recordScan377c8f4ace464b17c47f597571d2375c.definition = {
    methods: ["post"],
    url: '/api/attendance/scan',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/api/attendance/scan'
 */
recordScan377c8f4ace464b17c47f597571d2375c.url = (options?: RouteQueryOptions) => {
    return recordScan377c8f4ace464b17c47f597571d2375c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/api/attendance/scan'
 */
recordScan377c8f4ace464b17c47f597571d2375c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordScan377c8f4ace464b17c47f597571d2375c.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/api/attendance/scan'
 */
    const recordScan377c8f4ace464b17c47f597571d2375cForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: recordScan377c8f4ace464b17c47f597571d2375c.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/api/attendance/scan'
 */
        recordScan377c8f4ace464b17c47f597571d2375cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: recordScan377c8f4ace464b17c47f597571d2375c.url(options),
            method: 'post',
        })
    
    recordScan377c8f4ace464b17c47f597571d2375c.form = recordScan377c8f4ace464b17c47f597571d2375cForm
    /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/attendance/scan'
 */
const recordScanddce63a16b0b53f5a2240db59ef67b94 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordScanddce63a16b0b53f5a2240db59ef67b94.url(options),
    method: 'post',
})

recordScanddce63a16b0b53f5a2240db59ef67b94.definition = {
    methods: ["post"],
    url: '/attendance/scan',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/attendance/scan'
 */
recordScanddce63a16b0b53f5a2240db59ef67b94.url = (options?: RouteQueryOptions) => {
    return recordScanddce63a16b0b53f5a2240db59ef67b94.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/attendance/scan'
 */
recordScanddce63a16b0b53f5a2240db59ef67b94.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordScanddce63a16b0b53f5a2240db59ef67b94.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/attendance/scan'
 */
    const recordScanddce63a16b0b53f5a2240db59ef67b94Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: recordScanddce63a16b0b53f5a2240db59ef67b94.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:199
 * @route '/attendance/scan'
 */
        recordScanddce63a16b0b53f5a2240db59ef67b94Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: recordScanddce63a16b0b53f5a2240db59ef67b94.url(options),
            method: 'post',
        })
    
    recordScanddce63a16b0b53f5a2240db59ef67b94.form = recordScanddce63a16b0b53f5a2240db59ef67b94Form

/**
* Multiple routes resolve to \App\Http\Controllers\AttendanceController::recordScan, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `recordScan['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const recordScan = {
    '/api/attendance/scan': recordScan377c8f4ace464b17c47f597571d2375c,
    '/attendance/scan': recordScanddce63a16b0b53f5a2240db59ef67b94,
}

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/api/attendance/check-in'
 */
const checkInca906fe3cd23bd0bb151765c39e5dd75 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkInca906fe3cd23bd0bb151765c39e5dd75.url(options),
    method: 'post',
})

checkInca906fe3cd23bd0bb151765c39e5dd75.definition = {
    methods: ["post"],
    url: '/api/attendance/check-in',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/api/attendance/check-in'
 */
checkInca906fe3cd23bd0bb151765c39e5dd75.url = (options?: RouteQueryOptions) => {
    return checkInca906fe3cd23bd0bb151765c39e5dd75.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/api/attendance/check-in'
 */
checkInca906fe3cd23bd0bb151765c39e5dd75.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkInca906fe3cd23bd0bb151765c39e5dd75.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/api/attendance/check-in'
 */
    const checkInca906fe3cd23bd0bb151765c39e5dd75Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkInca906fe3cd23bd0bb151765c39e5dd75.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/api/attendance/check-in'
 */
        checkInca906fe3cd23bd0bb151765c39e5dd75Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkInca906fe3cd23bd0bb151765c39e5dd75.url(options),
            method: 'post',
        })
    
    checkInca906fe3cd23bd0bb151765c39e5dd75.form = checkInca906fe3cd23bd0bb151765c39e5dd75Form
    /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/attendance/check-in'
 */
const checkIn11e0d2dbbb15db192e96441e676bcf73 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIn11e0d2dbbb15db192e96441e676bcf73.url(options),
    method: 'post',
})

checkIn11e0d2dbbb15db192e96441e676bcf73.definition = {
    methods: ["post"],
    url: '/attendance/check-in',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/attendance/check-in'
 */
checkIn11e0d2dbbb15db192e96441e676bcf73.url = (options?: RouteQueryOptions) => {
    return checkIn11e0d2dbbb15db192e96441e676bcf73.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/attendance/check-in'
 */
checkIn11e0d2dbbb15db192e96441e676bcf73.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIn11e0d2dbbb15db192e96441e676bcf73.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/attendance/check-in'
 */
    const checkIn11e0d2dbbb15db192e96441e676bcf73Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkIn11e0d2dbbb15db192e96441e676bcf73.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:219
 * @route '/attendance/check-in'
 */
        checkIn11e0d2dbbb15db192e96441e676bcf73Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkIn11e0d2dbbb15db192e96441e676bcf73.url(options),
            method: 'post',
        })
    
    checkIn11e0d2dbbb15db192e96441e676bcf73.form = checkIn11e0d2dbbb15db192e96441e676bcf73Form

/**
* Multiple routes resolve to \App\Http\Controllers\AttendanceController::checkIn, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `checkIn['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const checkIn = {
    '/api/attendance/check-in': checkInca906fe3cd23bd0bb151765c39e5dd75,
    '/attendance/check-in': checkIn11e0d2dbbb15db192e96441e676bcf73,
}

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/api/attendance/check-out'
 */
const checkOutbf654428f1f04ccaf3affdb4efefae2c = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOutbf654428f1f04ccaf3affdb4efefae2c.url(options),
    method: 'post',
})

checkOutbf654428f1f04ccaf3affdb4efefae2c.definition = {
    methods: ["post"],
    url: '/api/attendance/check-out',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/api/attendance/check-out'
 */
checkOutbf654428f1f04ccaf3affdb4efefae2c.url = (options?: RouteQueryOptions) => {
    return checkOutbf654428f1f04ccaf3affdb4efefae2c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/api/attendance/check-out'
 */
checkOutbf654428f1f04ccaf3affdb4efefae2c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOutbf654428f1f04ccaf3affdb4efefae2c.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/api/attendance/check-out'
 */
    const checkOutbf654428f1f04ccaf3affdb4efefae2cForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkOutbf654428f1f04ccaf3affdb4efefae2c.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/api/attendance/check-out'
 */
        checkOutbf654428f1f04ccaf3affdb4efefae2cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkOutbf654428f1f04ccaf3affdb4efefae2c.url(options),
            method: 'post',
        })
    
    checkOutbf654428f1f04ccaf3affdb4efefae2c.form = checkOutbf654428f1f04ccaf3affdb4efefae2cForm
    /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/attendance/check-out'
 */
const checkOut4ccddd629ce379200fc4b7819ec6eadf = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOut4ccddd629ce379200fc4b7819ec6eadf.url(options),
    method: 'post',
})

checkOut4ccddd629ce379200fc4b7819ec6eadf.definition = {
    methods: ["post"],
    url: '/attendance/check-out',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/attendance/check-out'
 */
checkOut4ccddd629ce379200fc4b7819ec6eadf.url = (options?: RouteQueryOptions) => {
    return checkOut4ccddd629ce379200fc4b7819ec6eadf.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/attendance/check-out'
 */
checkOut4ccddd629ce379200fc4b7819ec6eadf.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOut4ccddd629ce379200fc4b7819ec6eadf.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/attendance/check-out'
 */
    const checkOut4ccddd629ce379200fc4b7819ec6eadfForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkOut4ccddd629ce379200fc4b7819ec6eadf.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:233
 * @route '/attendance/check-out'
 */
        checkOut4ccddd629ce379200fc4b7819ec6eadfForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkOut4ccddd629ce379200fc4b7819ec6eadf.url(options),
            method: 'post',
        })
    
    checkOut4ccddd629ce379200fc4b7819ec6eadf.form = checkOut4ccddd629ce379200fc4b7819ec6eadfForm

/**
* Multiple routes resolve to \App\Http\Controllers\AttendanceController::checkOut, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `checkOut['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const checkOut = {
    '/api/attendance/check-out': checkOutbf654428f1f04ccaf3affdb4efefae2c,
    '/attendance/check-out': checkOut4ccddd629ce379200fc4b7819ec6eadf,
}

/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/q/{token}'
 */
const openfa218040de12b4ef0c54297902ae2bc8 = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: openfa218040de12b4ef0c54297902ae2bc8.url(args, options),
    method: 'get',
})

openfa218040de12b4ef0c54297902ae2bc8.definition = {
    methods: ["get","head"],
    url: '/q/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/q/{token}'
 */
openfa218040de12b4ef0c54297902ae2bc8.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { token: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    token: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        token: args.token,
                }

    return openfa218040de12b4ef0c54297902ae2bc8.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/q/{token}'
 */
openfa218040de12b4ef0c54297902ae2bc8.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: openfa218040de12b4ef0c54297902ae2bc8.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/q/{token}'
 */
openfa218040de12b4ef0c54297902ae2bc8.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: openfa218040de12b4ef0c54297902ae2bc8.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/q/{token}'
 */
    const openfa218040de12b4ef0c54297902ae2bc8Form = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: openfa218040de12b4ef0c54297902ae2bc8.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/q/{token}'
 */
        openfa218040de12b4ef0c54297902ae2bc8Form.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: openfa218040de12b4ef0c54297902ae2bc8.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/q/{token}'
 */
        openfa218040de12b4ef0c54297902ae2bc8Form.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: openfa218040de12b4ef0c54297902ae2bc8.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    openfa218040de12b4ef0c54297902ae2bc8.form = openfa218040de12b4ef0c54297902ae2bc8Form
    /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/attendance/open'
 */
const open4376a2f5d911608fb94e642a7e8d7a51 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: open4376a2f5d911608fb94e642a7e8d7a51.url(options),
    method: 'get',
})

open4376a2f5d911608fb94e642a7e8d7a51.definition = {
    methods: ["get","head"],
    url: '/attendance/open',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/attendance/open'
 */
open4376a2f5d911608fb94e642a7e8d7a51.url = (options?: RouteQueryOptions) => {
    return open4376a2f5d911608fb94e642a7e8d7a51.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/attendance/open'
 */
open4376a2f5d911608fb94e642a7e8d7a51.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: open4376a2f5d911608fb94e642a7e8d7a51.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/attendance/open'
 */
open4376a2f5d911608fb94e642a7e8d7a51.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: open4376a2f5d911608fb94e642a7e8d7a51.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/attendance/open'
 */
    const open4376a2f5d911608fb94e642a7e8d7a51Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: open4376a2f5d911608fb94e642a7e8d7a51.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/attendance/open'
 */
        open4376a2f5d911608fb94e642a7e8d7a51Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: open4376a2f5d911608fb94e642a7e8d7a51.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:147
 * @route '/attendance/open'
 */
        open4376a2f5d911608fb94e642a7e8d7a51Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: open4376a2f5d911608fb94e642a7e8d7a51.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    open4376a2f5d911608fb94e642a7e8d7a51.form = open4376a2f5d911608fb94e642a7e8d7a51Form

/**
* Multiple routes resolve to \App\Http\Controllers\AttendanceController::open, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `open['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const open = {
    '/q/{token}': openfa218040de12b4ef0c54297902ae2bc8,
    '/attendance/open': open4376a2f5d911608fb94e642a7e8d7a51,
}

/**
* @see \App\Http\Controllers\AttendanceController::recordOpen
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
export const recordOpen = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordOpen.url(options),
    method: 'post',
})

recordOpen.definition = {
    methods: ["post"],
    url: '/attendance/open',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::recordOpen
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
recordOpen.url = (options?: RouteQueryOptions) => {
    return recordOpen.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::recordOpen
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
recordOpen.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordOpen.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::recordOpen
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
    const recordOpenForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: recordOpen.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::recordOpen
 * @see app/Http/Controllers/AttendanceController.php:163
 * @route '/attendance/open'
 */
        recordOpenForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: recordOpen.url(options),
            method: 'post',
        })
    
    recordOpen.form = recordOpenForm
/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:38
 * @route '/attendance'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/attendance',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:38
 * @route '/attendance'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:38
 * @route '/attendance'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:38
 * @route '/attendance'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:38
 * @route '/attendance'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:38
 * @route '/attendance'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:38
 * @route '/attendance'
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
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:112
 * @route '/attendance/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/attendance/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:112
 * @route '/attendance/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:112
 * @route '/attendance/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:112
 * @route '/attendance/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:112
 * @route '/attendance/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:112
 * @route '/attendance/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:112
 * @route '/attendance/export'
 */
        exportMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportMethod.form = exportMethodForm
/**
* @see \App\Http\Controllers\AttendanceController::syncEntries
 * @see app/Http/Controllers/AttendanceController.php:71
 * @route '/attendance/entries'
 */
export const syncEntries = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: syncEntries.url(options),
    method: 'put',
})

syncEntries.definition = {
    methods: ["put"],
    url: '/attendance/entries',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\AttendanceController::syncEntries
 * @see app/Http/Controllers/AttendanceController.php:71
 * @route '/attendance/entries'
 */
syncEntries.url = (options?: RouteQueryOptions) => {
    return syncEntries.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::syncEntries
 * @see app/Http/Controllers/AttendanceController.php:71
 * @route '/attendance/entries'
 */
syncEntries.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: syncEntries.url(options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\AttendanceController::syncEntries
 * @see app/Http/Controllers/AttendanceController.php:71
 * @route '/attendance/entries'
 */
    const syncEntriesForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: syncEntries.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::syncEntries
 * @see app/Http/Controllers/AttendanceController.php:71
 * @route '/attendance/entries'
 */
        syncEntriesForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: syncEntries.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    syncEntries.form = syncEntriesForm
/**
* @see \App\Http\Controllers\AttendanceController::clearRecords
 * @see app/Http/Controllers/AttendanceController.php:86
 * @route '/attendance/records'
 */
export const clearRecords = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearRecords.url(options),
    method: 'delete',
})

clearRecords.definition = {
    methods: ["delete"],
    url: '/attendance/records',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\AttendanceController::clearRecords
 * @see app/Http/Controllers/AttendanceController.php:86
 * @route '/attendance/records'
 */
clearRecords.url = (options?: RouteQueryOptions) => {
    return clearRecords.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::clearRecords
 * @see app/Http/Controllers/AttendanceController.php:86
 * @route '/attendance/records'
 */
clearRecords.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearRecords.url(options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\AttendanceController::clearRecords
 * @see app/Http/Controllers/AttendanceController.php:86
 * @route '/attendance/records'
 */
    const clearRecordsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: clearRecords.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::clearRecords
 * @see app/Http/Controllers/AttendanceController.php:86
 * @route '/attendance/records'
 */
        clearRecordsForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: clearRecords.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    clearRecords.form = clearRecordsForm
/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:129
 * @route '/attendance/scan'
 */
export const scan = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: scan.url(options),
    method: 'get',
})

scan.definition = {
    methods: ["get","head"],
    url: '/attendance/scan',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:129
 * @route '/attendance/scan'
 */
scan.url = (options?: RouteQueryOptions) => {
    return scan.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:129
 * @route '/attendance/scan'
 */
scan.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: scan.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:129
 * @route '/attendance/scan'
 */
scan.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: scan.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:129
 * @route '/attendance/scan'
 */
    const scanForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: scan.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:129
 * @route '/attendance/scan'
 */
        scanForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: scan.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:129
 * @route '/attendance/scan'
 */
        scanForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: scan.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    scan.form = scanForm
const AttendanceController = { recordScan, checkIn, checkOut, open, recordOpen, index, exportMethod, syncEntries, clearRecords, scan, export: exportMethod }

export default AttendanceController