import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\MekanController::preview
* @see app/Http/Controllers/MekanController.php:154
* @route '/mekanlar/import/preview'
*/
export const preview = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: preview.url(options),
    method: 'post',
})

preview.definition = {
    methods: ["post"],
    url: '/mekanlar/import/preview',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\MekanController::preview
* @see app/Http/Controllers/MekanController.php:154
* @route '/mekanlar/import/preview'
*/
preview.url = (options?: RouteQueryOptions) => {
    return preview.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::preview
* @see app/Http/Controllers/MekanController.php:154
* @route '/mekanlar/import/preview'
*/
preview.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: preview.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::preview
* @see app/Http/Controllers/MekanController.php:154
* @route '/mekanlar/import/preview'
*/
const previewForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: preview.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::preview
* @see app/Http/Controllers/MekanController.php:154
* @route '/mekanlar/import/preview'
*/
previewForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: preview.url(options),
    method: 'post',
})

preview.form = previewForm

/**
* @see \App\Http\Controllers\MekanController::selected
* @see app/Http/Controllers/MekanController.php:189
* @route '/mekanlar/import/selected'
*/
export const selected = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: selected.url(options),
    method: 'post',
})

selected.definition = {
    methods: ["post"],
    url: '/mekanlar/import/selected',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\MekanController::selected
* @see app/Http/Controllers/MekanController.php:189
* @route '/mekanlar/import/selected'
*/
selected.url = (options?: RouteQueryOptions) => {
    return selected.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::selected
* @see app/Http/Controllers/MekanController.php:189
* @route '/mekanlar/import/selected'
*/
selected.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: selected.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::selected
* @see app/Http/Controllers/MekanController.php:189
* @route '/mekanlar/import/selected'
*/
const selectedForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: selected.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::selected
* @see app/Http/Controllers/MekanController.php:189
* @route '/mekanlar/import/selected'
*/
selectedForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: selected.url(options),
    method: 'post',
})

selected.form = selectedForm

const importMethod = {
    preview: Object.assign(preview, preview),
    selected: Object.assign(selected, selected),
}

export default importMethod