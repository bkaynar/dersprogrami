import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:264
* @route '/ogretmen-musaitlik/import-advanced'
*/
export const advanced = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: advanced.url(options),
    method: 'post',
})

advanced.definition = {
    methods: ["post"],
    url: '/ogretmen-musaitlik/import-advanced',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:264
* @route '/ogretmen-musaitlik/import-advanced'
*/
advanced.url = (options?: RouteQueryOptions) => {
    return advanced.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:264
* @route '/ogretmen-musaitlik/import-advanced'
*/
advanced.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: advanced.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:264
* @route '/ogretmen-musaitlik/import-advanced'
*/
const advancedForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: advanced.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:264
* @route '/ogretmen-musaitlik/import-advanced'
*/
advancedForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: advanced.url(options),
    method: 'post',
})

advanced.form = advancedForm

const importMethod = {
    advanced: Object.assign(advanced, advanced),
}

export default importMethod