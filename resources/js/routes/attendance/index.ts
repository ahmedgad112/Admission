import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import entries from './entries'
import records from './records'
import days from './days'
import kiosk3dab19 from './kiosk'
import qrSessions from './qr-sessions'
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
 * @see app/Http/Controllers/AttendanceController.php:101
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
 * @see app/Http/Controllers/AttendanceController.php:101
 * @route '/attendance/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:101
 * @route '/attendance/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:101
 * @route '/attendance/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:101
 * @route '/attendance/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:101
 * @route '/attendance/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::exportMethod
 * @see app/Http/Controllers/AttendanceController.php:101
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
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:113
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
 * @see app/Http/Controllers/AttendanceController.php:113
 * @route '/attendance/scan'
 */
scan.url = (options?: RouteQueryOptions) => {
    return scan.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:113
 * @route '/attendance/scan'
 */
scan.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: scan.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:113
 * @route '/attendance/scan'
 */
scan.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: scan.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:113
 * @route '/attendance/scan'
 */
    const scanForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: scan.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:113
 * @route '/attendance/scan'
 */
        scanForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: scan.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::scan
 * @see app/Http/Controllers/AttendanceController.php:113
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
 * @see app/Http/Controllers/AttendanceController.php:131
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
 * @see app/Http/Controllers/AttendanceController.php:131
 * @route '/attendance/check-in'
 */
checkIn.url = (options?: RouteQueryOptions) => {
    return checkIn.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:131
 * @route '/attendance/check-in'
 */
checkIn.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIn.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:131
 * @route '/attendance/check-in'
 */
    const checkInForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkIn.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkIn
 * @see app/Http/Controllers/AttendanceController.php:131
 * @route '/attendance/check-in'
 */
        checkInForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkIn.url(options),
            method: 'post',
        })
    
    checkIn.form = checkInForm
/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:150
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
 * @see app/Http/Controllers/AttendanceController.php:150
 * @route '/attendance/check-out'
 */
checkOut.url = (options?: RouteQueryOptions) => {
    return checkOut.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:150
 * @route '/attendance/check-out'
 */
checkOut.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkOut.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:150
 * @route '/attendance/check-out'
 */
    const checkOutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkOut.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::checkOut
 * @see app/Http/Controllers/AttendanceController.php:150
 * @route '/attendance/check-out'
 */
        checkOutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkOut.url(options),
            method: 'post',
        })
    
    checkOut.form = checkOutForm
/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
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
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
kiosk.url = (options?: RouteQueryOptions) => {
    return kiosk.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
kiosk.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kiosk.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
kiosk.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: kiosk.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
    const kioskForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: kiosk.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
 * @route '/attendance/kiosk'
 */
        kioskForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: kiosk.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\QrSessionController::kiosk
 * @see app/Http/Controllers/QrSessionController.php:21
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
    index: Object.assign(index, index),
export: Object.assign(exportMethod, exportMethod),
entries: Object.assign(entries, entries),
records: Object.assign(records, records),
scan: Object.assign(scan, scan),
checkIn: Object.assign(checkIn, checkIn),
checkOut: Object.assign(checkOut, checkOut),
days: Object.assign(days, days),
kiosk: Object.assign(kiosk, kiosk3dab19),
qrSessions: Object.assign(qrSessions, qrSessions),
}

export default attendance