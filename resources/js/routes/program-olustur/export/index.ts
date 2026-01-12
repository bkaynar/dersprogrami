import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
import universite from './universite'
import template from './template'
/**
* @see \App\Http\Controllers\ProgramOlusturController::excel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
export const excel = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: excel.url(options),
    method: 'get',
})

excel.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/excel',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::excel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
excel.url = (options?: RouteQueryOptions) => {
    return excel.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::excel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
excel.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: excel.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::excel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
excel.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: excel.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::excel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
const excelForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: excel.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::excel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
excelForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: excel.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::excel
* @see app/Http/Controllers/ProgramOlusturController.php:226
* @route '/program-olustur/export/excel'
*/
excelForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: excel.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

excel.form = excelForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::pdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
export const pdf = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(options),
    method: 'get',
})

pdf.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/pdf',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::pdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
pdf.url = (options?: RouteQueryOptions) => {
    return pdf.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::pdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
pdf.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::pdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
pdf.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pdf.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::pdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
const pdfForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pdf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::pdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
pdfForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pdf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::pdf
* @see app/Http/Controllers/ProgramOlusturController.php:234
* @route '/program-olustur/export/pdf'
*/
pdfForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pdf.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

pdf.form = pdfForm

const exportMethod = {
    excel: Object.assign(excel, excel),
    pdf: Object.assign(pdf, pdf),
    universite: Object.assign(universite, universite),
    template: Object.assign(template, template),
}

export default exportMethod