import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import openF2f3f6 from './open'
import entries from './entries'
import records from './records'
import scan195c99 from './scan'
import days from './days'
import kiosk3dab19 from './kiosk'
import qrSessions from './qr-sessions'
/**
* @see \App\Http\Controllers\AttendanceController::qr
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/q/{token}'
 */
export const qr = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: qr.url(args, options),
    method: 'get',
})

qr.definition = {
    methods: ["get","head"],
    url: '/q/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::qr
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/q/{token}'
 */
qr.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return qr.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::qr
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/q/{token}'
 */
qr.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: qr.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::qr
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/q/{token}'
 */
qr.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: qr.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::qr
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/q/{token}'
 */
    const qrForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: qr.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::qr
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/q/{token}'
 */
        qrForm.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: qr.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::qr
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/q/{token}'
 */
        qrForm.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: qr.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    qr.form = qrForm
/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/attendance/open'
 */
export const open = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: open.url(options),
    method: 'get',
})

open.definition = {
    methods: ["get","head"],
    url: '/attendance/open',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/attendance/open'
 */
open.url = (options?: RouteQueryOptions) => {
    return open.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/attendance/open'
 */
open.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: open.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/attendance/open'
 */
open.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: open.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/attendance/open'
 */
    const openForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: open.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/attendance/open'
 */
        openForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: open.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::open
 * @see app/Http/Controllers/AttendanceController.php:141
 * @route '/attendance/open'
 */
        openForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: open.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    open.form = openForm
/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:37
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
 * @see app/Http/Controllers/AttendanceController.php:37
 * @route '/attendance'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:37
 * @route '/attendance'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:37
 * @route '/attendance'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:37
 * @route '/attendance'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:37
 * @route '/attendance'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:37
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
 * @see app/Http/Controllers/AttendanceController.php:111
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
 * @see app/Http/Controllers/AttendanceController.php:111
 * @route '/attendance/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:111
 * @route '/attendance/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:111
 * @route '/attendance/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:111
 * @route '/attendance/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:111
 * @route '/attendance/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:111
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
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
export const reports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

reports.definition = {
    methods: ["get","head"],
    url: '/attendance/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
reports.url = (options?: RouteQueryOptions) => {
    return reports.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
reports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
reports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reports.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
    const reportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: reports.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
        reportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reports.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceReportController::__invoke
 * @see app/Http/Controllers/AttendanceReportController.php:15
 * @route '/attendance/reports'
 */
        reportsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reports.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    reports.form = reportsForm
/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:123
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
 * @see app/Http/Controllers/AttendanceController.php:123
 * @route '/attendance/scan'
 */
scan.url = (options?: RouteQueryOptions) => {
    return scan.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:123
 * @route '/attendance/scan'
 */
scan.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: scan.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:123
 * @route '/attendance/scan'
 */
scan.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: scan.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:123
 * @route '/attendance/scan'
 */
    const scanForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: scan.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:123
 * @route '/attendance/scan'
 */
        scanForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: scan.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:123
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
/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:213
 * @route '/attendance/check-in'
 */
export const checkIn = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIn.url(options),
    method: 'post',
})

checkIn.definition = {
    methods: ["post"],
    url: '/attendance/check-in',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:213
 * @route '/attendance/check-in'
 */
checkIn.url = (options?: RouteQueryOptions) => {
    return checkIn.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:213
 * @route '/attendance/check-in'
 */
checkIn.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIn.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:213
 * @route '/attendance/check-in'
 */
    const checkInForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkIn.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:213
 * @route '/attendance/check-in'
 */
        checkInForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkIn.url(options),
            method: 'post',
        })
    
    checkIn.form = checkInForm
/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:227
 * @route '/attendance/check-out'
 */
export const checkOut = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOut.url(options),
    method: 'post',
})

checkOut.definition = {
    methods: ["post"],
    url: '/attendance/check-out',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:227
 * @route '/attendance/check-out'
 */
checkOut.url = (options?: RouteQueryOptions) => {
    return checkOut.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:227
 * @route '/attendance/check-out'
 */
checkOut.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOut.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:227
 * @route '/attendance/check-out'
 */
    const checkOutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkOut.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:227
 * @route '/attendance/check-out'
 */
        checkOutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkOut.url(options),
            method: 'post',
        })
    
    checkOut.form = checkOutForm
/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:25
 * @route '/attendance/kiosk'
 */
export const kiosk = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kiosk.url(options),
    method: 'get',
})

kiosk.definition = {
    methods: ["get","head"],
    url: '/attendance/kiosk',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:25
 * @route '/attendance/kiosk'
 */
kiosk.url = (options?: RouteQueryOptions) => {
    return kiosk.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:25
 * @route '/attendance/kiosk'
 */
kiosk.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kiosk.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:25
 * @route '/attendance/kiosk'
 */
kiosk.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: kiosk.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:25
 * @route '/attendance/kiosk'
 */
    const kioskForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: kiosk.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:25
 * @route '/attendance/kiosk'
 */
        kioskForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: kiosk.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:25
 * @route '/attendance/kiosk'
 */
        kioskForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: kiosk.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    kiosk.form = kioskForm
const attendance = {
    qr: Object.assign(qr, qr),
open: Object.assign(open, openF2f3f6),
index: Object.assign(index, index),
export: Object.assign(exportMethod, exportMethod),
reports: Object.assign(reports, reports),
entries: Object.assign(entries, entries),
records: Object.assign(records, records),
scan: Object.assign(scan, scan195c99),
checkIn: Object.assign(checkIn, checkIn),
checkOut: Object.assign(checkOut, checkOut),
days: Object.assign(days, days),
kiosk: Object.assign(kiosk, kiosk3dab19),
qrSessions: Object.assign(qrSessions, qrSessions),
}

export default attendance