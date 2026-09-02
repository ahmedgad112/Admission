import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:137
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
 * @see app/Http/Controllers/AttendanceController.php:137
 * @route '/api/attendance/scan'
 */
recordScan377c8f4ace464b17c47f597571d2375c.url = (options?: RouteQueryOptions) => {
    return recordScan377c8f4ace464b17c47f597571d2375c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:137
 * @route '/api/attendance/scan'
 */
recordScan377c8f4ace464b17c47f597571d2375c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordScan377c8f4ace464b17c47f597571d2375c.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:137
 * @route '/api/attendance/scan'
 */
    const recordScan377c8f4ace464b17c47f597571d2375cForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: recordScan377c8f4ace464b17c47f597571d2375c.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:137
 * @route '/api/attendance/scan'
 */
        recordScan377c8f4ace464b17c47f597571d2375cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: recordScan377c8f4ace464b17c47f597571d2375c.url(options),
            method: 'post',
        })
    
    recordScan377c8f4ace464b17c47f597571d2375c.form = recordScan377c8f4ace464b17c47f597571d2375cForm
    /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:137
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
 * @see app/Http/Controllers/AttendanceController.php:137
 * @route '/attendance/scan'
 */
recordScanddce63a16b0b53f5a2240db59ef67b94.url = (options?: RouteQueryOptions) => {
    return recordScanddce63a16b0b53f5a2240db59ef67b94.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:137
 * @route '/attendance/scan'
 */
recordScanddce63a16b0b53f5a2240db59ef67b94.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordScanddce63a16b0b53f5a2240db59ef67b94.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:137
 * @route '/attendance/scan'
 */
    const recordScanddce63a16b0b53f5a2240db59ef67b94Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: recordScanddce63a16b0b53f5a2240db59ef67b94.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::recordScan
 * @see app/Http/Controllers/AttendanceController.php:137
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
 * @see app/Http/Controllers/AttendanceController.php:157
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
 * @see app/Http/Controllers/AttendanceController.php:157
 * @route '/api/attendance/check-in'
 */
checkInca906fe3cd23bd0bb151765c39e5dd75.url = (options?: RouteQueryOptions) => {
    return checkInca906fe3cd23bd0bb151765c39e5dd75.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:157
 * @route '/api/attendance/check-in'
 */
checkInca906fe3cd23bd0bb151765c39e5dd75.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkInca906fe3cd23bd0bb151765c39e5dd75.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:157
 * @route '/api/attendance/check-in'
 */
    const checkInca906fe3cd23bd0bb151765c39e5dd75Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkInca906fe3cd23bd0bb151765c39e5dd75.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:157
 * @route '/api/attendance/check-in'
 */
        checkInca906fe3cd23bd0bb151765c39e5dd75Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkInca906fe3cd23bd0bb151765c39e5dd75.url(options),
            method: 'post',
        })
    
    checkInca906fe3cd23bd0bb151765c39e5dd75.form = checkInca906fe3cd23bd0bb151765c39e5dd75Form
    /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:157
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
 * @see app/Http/Controllers/AttendanceController.php:157
 * @route '/attendance/check-in'
 */
checkIn11e0d2dbbb15db192e96441e676bcf73.url = (options?: RouteQueryOptions) => {
    return checkIn11e0d2dbbb15db192e96441e676bcf73.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:157
 * @route '/attendance/check-in'
 */
checkIn11e0d2dbbb15db192e96441e676bcf73.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIn11e0d2dbbb15db192e96441e676bcf73.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:157
 * @route '/attendance/check-in'
 */
    const checkIn11e0d2dbbb15db192e96441e676bcf73Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkIn11e0d2dbbb15db192e96441e676bcf73.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:157
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
 * @see app/Http/Controllers/AttendanceController.php:171
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
 * @see app/Http/Controllers/AttendanceController.php:171
 * @route '/api/attendance/check-out'
 */
checkOutbf654428f1f04ccaf3affdb4efefae2c.url = (options?: RouteQueryOptions) => {
    return checkOutbf654428f1f04ccaf3affdb4efefae2c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:171
 * @route '/api/attendance/check-out'
 */
checkOutbf654428f1f04ccaf3affdb4efefae2c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOutbf654428f1f04ccaf3affdb4efefae2c.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:171
 * @route '/api/attendance/check-out'
 */
    const checkOutbf654428f1f04ccaf3affdb4efefae2cForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkOutbf654428f1f04ccaf3affdb4efefae2c.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:171
 * @route '/api/attendance/check-out'
 */
        checkOutbf654428f1f04ccaf3affdb4efefae2cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkOutbf654428f1f04ccaf3affdb4efefae2c.url(options),
            method: 'post',
        })
    
    checkOutbf654428f1f04ccaf3affdb4efefae2c.form = checkOutbf654428f1f04ccaf3affdb4efefae2cForm
    /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:171
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
 * @see app/Http/Controllers/AttendanceController.php:171
 * @route '/attendance/check-out'
 */
checkOut4ccddd629ce379200fc4b7819ec6eadf.url = (options?: RouteQueryOptions) => {
    return checkOut4ccddd629ce379200fc4b7819ec6eadf.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:171
 * @route '/attendance/check-out'
 */
checkOut4ccddd629ce379200fc4b7819ec6eadf.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOut4ccddd629ce379200fc4b7819ec6eadf.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:171
 * @route '/attendance/check-out'
 */
    const checkOut4ccddd629ce379200fc4b7819ec6eadfForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkOut4ccddd629ce379200fc4b7819ec6eadf.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:171
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
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:35
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
 * @see app/Http/Controllers/AttendanceController.php:35
 * @route '/attendance'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:35
 * @route '/attendance'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:35
 * @route '/attendance'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:35
 * @route '/attendance'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:35
 * @route '/attendance'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:35
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
 * @see app/Http/Controllers/AttendanceController.php:108
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
 * @see app/Http/Controllers/AttendanceController.php:108
 * @route '/attendance/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:108
 * @route '/attendance/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:108
 * @route '/attendance/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:108
 * @route '/attendance/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:108
 * @route '/attendance/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:108
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
 * @see app/Http/Controllers/AttendanceController.php:68
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
 * @see app/Http/Controllers/AttendanceController.php:68
 * @route '/attendance/entries'
 */
syncEntries.url = (options?: RouteQueryOptions) => {
    return syncEntries.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::syncEntries
 * @see app/Http/Controllers/AttendanceController.php:68
 * @route '/attendance/entries'
 */
syncEntries.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: syncEntries.url(options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\AttendanceController::syncEntries
 * @see app/Http/Controllers/AttendanceController.php:68
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
 * @see app/Http/Controllers/AttendanceController.php:68
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
 * @see app/Http/Controllers/AttendanceController.php:83
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
 * @see app/Http/Controllers/AttendanceController.php:83
 * @route '/attendance/records'
 */
clearRecords.url = (options?: RouteQueryOptions) => {
    return clearRecords.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::clearRecords
 * @see app/Http/Controllers/AttendanceController.php:83
 * @route '/attendance/records'
 */
clearRecords.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearRecords.url(options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\AttendanceController::clearRecords
 * @see app/Http/Controllers/AttendanceController.php:83
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
 * @see app/Http/Controllers/AttendanceController.php:83
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
 * @see app/Http/Controllers/AttendanceController.php:120
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
 * @see app/Http/Controllers/AttendanceController.php:120
 * @route '/attendance/scan'
 */
scan.url = (options?: RouteQueryOptions) => {
    return scan.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:120
 * @route '/attendance/scan'
 */
scan.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: scan.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:120
 * @route '/attendance/scan'
 */
scan.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: scan.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:120
 * @route '/attendance/scan'
 */
    const scanForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: scan.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:120
 * @route '/attendance/scan'
 */
        scanForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: scan.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:120
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
const AttendanceController = { recordScan, checkIn, checkOut, index, exportMethod, syncEntries, clearRecords, scan, export: exportMethod }

export default AttendanceController