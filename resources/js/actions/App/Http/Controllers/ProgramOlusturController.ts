import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/program-olustur',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
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
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
export const generate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(options),
    method: 'post',
})

generate.definition = {
    methods: ["post"],
    url: '/program-olustur/generate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
generate.url = (options?: RouteQueryOptions) => {
    return generate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
generate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
const generateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: generate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
generateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: generate.url(options),
    method: 'post',
})

generate.form = generateForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
export const status = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

status.definition = {
    methods: ["get","head"],
    url: '/program-olustur/status',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
status.url = (options?: RouteQueryOptions) => {
    return status.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
status.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
status.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: status.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
const statusForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
statusForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
statusForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

status.form = statusForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/program-olustur/show',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportExcel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
export const exportExcel = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportExcel.url(options),
    method: 'get',
})

exportExcel.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/excel',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportExcel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
exportExcel.url = (options?: RouteQueryOptions) => {
    return exportExcel.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportExcel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
exportExcel.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportExcel.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportExcel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
exportExcel.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportExcel.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportExcel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
const exportExcelForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportExcel.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportExcel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
exportExcelForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportExcel.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportExcel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
exportExcelForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportExcel.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportExcel.form = exportExcelForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportPdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
export const exportPdf = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportPdf.url(options),
    method: 'get',
})

exportPdf.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/pdf',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportPdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
exportPdf.url = (options?: RouteQueryOptions) => {
    return exportPdf.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportPdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
exportPdf.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportPdf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportPdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
exportPdf.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportPdf.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportPdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
const exportPdfForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportPdf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportPdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
exportPdfForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportPdf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportPdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
exportPdfForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportPdf.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportPdf.form = exportPdfForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelA
* @see app/Http/Controllers/ProgramOlusturController.php:282
* @route '/program-olustur/export/universite/excel/a'
*/
export const exportUniversiteExcelA = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversiteExcelA.url(options),
    method: 'get',
})

exportUniversiteExcelA.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/universite/excel/a',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelA
* @see app/Http/Controllers/ProgramOlusturController.php:282
* @route '/program-olustur/export/universite/excel/a'
*/
exportUniversiteExcelA.url = (options?: RouteQueryOptions) => {
    return exportUniversiteExcelA.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelA
* @see app/Http/Controllers/ProgramOlusturController.php:282
* @route '/program-olustur/export/universite/excel/a'
*/
exportUniversiteExcelA.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversiteExcelA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelA
* @see app/Http/Controllers/ProgramOlusturController.php:282
* @route '/program-olustur/export/universite/excel/a'
*/
exportUniversiteExcelA.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportUniversiteExcelA.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelA
* @see app/Http/Controllers/ProgramOlusturController.php:282
* @route '/program-olustur/export/universite/excel/a'
*/
const exportUniversiteExcelAForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteExcelA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelA
* @see app/Http/Controllers/ProgramOlusturController.php:282
* @route '/program-olustur/export/universite/excel/a'
*/
exportUniversiteExcelAForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteExcelA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelA
* @see app/Http/Controllers/ProgramOlusturController.php:282
* @route '/program-olustur/export/universite/excel/a'
*/
exportUniversiteExcelAForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteExcelA.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportUniversiteExcelA.form = exportUniversiteExcelAForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelB
* @see app/Http/Controllers/ProgramOlusturController.php:293
* @route '/program-olustur/export/universite/excel/b'
*/
export const exportUniversiteExcelB = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversiteExcelB.url(options),
    method: 'get',
})

exportUniversiteExcelB.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/universite/excel/b',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelB
* @see app/Http/Controllers/ProgramOlusturController.php:293
* @route '/program-olustur/export/universite/excel/b'
*/
exportUniversiteExcelB.url = (options?: RouteQueryOptions) => {
    return exportUniversiteExcelB.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelB
* @see app/Http/Controllers/ProgramOlusturController.php:293
* @route '/program-olustur/export/universite/excel/b'
*/
exportUniversiteExcelB.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversiteExcelB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelB
* @see app/Http/Controllers/ProgramOlusturController.php:293
* @route '/program-olustur/export/universite/excel/b'
*/
exportUniversiteExcelB.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportUniversiteExcelB.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelB
* @see app/Http/Controllers/ProgramOlusturController.php:293
* @route '/program-olustur/export/universite/excel/b'
*/
const exportUniversiteExcelBForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteExcelB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelB
* @see app/Http/Controllers/ProgramOlusturController.php:293
* @route '/program-olustur/export/universite/excel/b'
*/
exportUniversiteExcelBForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteExcelB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteExcelB
* @see app/Http/Controllers/ProgramOlusturController.php:293
* @route '/program-olustur/export/universite/excel/b'
*/
exportUniversiteExcelBForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteExcelB.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportUniversiteExcelB.form = exportUniversiteExcelBForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfA
* @see app/Http/Controllers/ProgramOlusturController.php:304
* @route '/program-olustur/export/universite/pdf/a'
*/
export const exportUniversitePdfA = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversitePdfA.url(options),
    method: 'get',
})

exportUniversitePdfA.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/universite/pdf/a',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfA
* @see app/Http/Controllers/ProgramOlusturController.php:304
* @route '/program-olustur/export/universite/pdf/a'
*/
exportUniversitePdfA.url = (options?: RouteQueryOptions) => {
    return exportUniversitePdfA.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfA
* @see app/Http/Controllers/ProgramOlusturController.php:304
* @route '/program-olustur/export/universite/pdf/a'
*/
exportUniversitePdfA.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversitePdfA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfA
* @see app/Http/Controllers/ProgramOlusturController.php:304
* @route '/program-olustur/export/universite/pdf/a'
*/
exportUniversitePdfA.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportUniversitePdfA.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfA
* @see app/Http/Controllers/ProgramOlusturController.php:304
* @route '/program-olustur/export/universite/pdf/a'
*/
const exportUniversitePdfAForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversitePdfA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfA
* @see app/Http/Controllers/ProgramOlusturController.php:304
* @route '/program-olustur/export/universite/pdf/a'
*/
exportUniversitePdfAForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversitePdfA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfA
* @see app/Http/Controllers/ProgramOlusturController.php:304
* @route '/program-olustur/export/universite/pdf/a'
*/
exportUniversitePdfAForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversitePdfA.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportUniversitePdfA.form = exportUniversitePdfAForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfB
* @see app/Http/Controllers/ProgramOlusturController.php:315
* @route '/program-olustur/export/universite/pdf/b'
*/
export const exportUniversitePdfB = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversitePdfB.url(options),
    method: 'get',
})

exportUniversitePdfB.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/universite/pdf/b',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfB
* @see app/Http/Controllers/ProgramOlusturController.php:315
* @route '/program-olustur/export/universite/pdf/b'
*/
exportUniversitePdfB.url = (options?: RouteQueryOptions) => {
    return exportUniversitePdfB.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfB
* @see app/Http/Controllers/ProgramOlusturController.php:315
* @route '/program-olustur/export/universite/pdf/b'
*/
exportUniversitePdfB.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversitePdfB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfB
* @see app/Http/Controllers/ProgramOlusturController.php:315
* @route '/program-olustur/export/universite/pdf/b'
*/
exportUniversitePdfB.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportUniversitePdfB.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfB
* @see app/Http/Controllers/ProgramOlusturController.php:315
* @route '/program-olustur/export/universite/pdf/b'
*/
const exportUniversitePdfBForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversitePdfB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfB
* @see app/Http/Controllers/ProgramOlusturController.php:315
* @route '/program-olustur/export/universite/pdf/b'
*/
exportUniversitePdfBForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversitePdfB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversitePdfB
* @see app/Http/Controllers/ProgramOlusturController.php:315
* @route '/program-olustur/export/universite/pdf/b'
*/
exportUniversitePdfBForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversitePdfB.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportUniversitePdfB.form = exportUniversitePdfBForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateA
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
export const exportUniversiteTemplateA = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversiteTemplateA.url(options),
    method: 'get',
})

exportUniversiteTemplateA.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/template/a',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateA
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
exportUniversiteTemplateA.url = (options?: RouteQueryOptions) => {
    return exportUniversiteTemplateA.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateA
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
exportUniversiteTemplateA.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversiteTemplateA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateA
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
exportUniversiteTemplateA.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportUniversiteTemplateA.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateA
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
const exportUniversiteTemplateAForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteTemplateA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateA
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
exportUniversiteTemplateAForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteTemplateA.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateA
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
exportUniversiteTemplateAForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteTemplateA.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportUniversiteTemplateA.form = exportUniversiteTemplateAForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateB
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
export const exportUniversiteTemplateB = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversiteTemplateB.url(options),
    method: 'get',
})

exportUniversiteTemplateB.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/template/b',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateB
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
exportUniversiteTemplateB.url = (options?: RouteQueryOptions) => {
    return exportUniversiteTemplateB.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateB
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
exportUniversiteTemplateB.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportUniversiteTemplateB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateB
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
exportUniversiteTemplateB.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportUniversiteTemplateB.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateB
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
const exportUniversiteTemplateBForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteTemplateB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateB
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
exportUniversiteTemplateBForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteTemplateB.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::exportUniversiteTemplateB
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
exportUniversiteTemplateBForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportUniversiteTemplateB.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportUniversiteTemplateB.form = exportUniversiteTemplateBForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
export const destroy = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/program-olustur',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
destroy.url = (options?: RouteQueryOptions) => {
    return destroy.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
destroy.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
const destroyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
destroyForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const ProgramOlusturController = { index, generate, status, show, exportExcel, exportPdf, exportUniversiteExcelA, exportUniversiteExcelB, exportUniversitePdfA, exportUniversitePdfB, exportUniversiteTemplateA, exportUniversiteTemplateB, destroy }

export default ProgramOlusturController