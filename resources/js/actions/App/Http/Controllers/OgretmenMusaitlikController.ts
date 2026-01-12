import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::index
* @see app/Http/Controllers/OgretmenMusaitlikController.php:16
* @route '/ogretmen-musaitlik'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/ogretmen-musaitlik',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::index
* @see app/Http/Controllers/OgretmenMusaitlikController.php:16
* @route '/ogretmen-musaitlik'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::index
* @see app/Http/Controllers/OgretmenMusaitlikController.php:16
* @route '/ogretmen-musaitlik'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::index
* @see app/Http/Controllers/OgretmenMusaitlikController.php:16
* @route '/ogretmen-musaitlik'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::index
* @see app/Http/Controllers/OgretmenMusaitlikController.php:16
* @route '/ogretmen-musaitlik'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::index
* @see app/Http/Controllers/OgretmenMusaitlikController.php:16
* @route '/ogretmen-musaitlik'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::index
* @see app/Http/Controllers/OgretmenMusaitlikController.php:16
* @route '/ogretmen-musaitlik'
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
* @see \App\Http\Controllers\OgretmenMusaitlikController::downloadTemplate
* @see app/Http/Controllers/OgretmenMusaitlikController.php:179
* @route '/ogretmen-musaitlik/template'
*/
export const downloadTemplate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

downloadTemplate.definition = {
    methods: ["get","head"],
    url: '/ogretmen-musaitlik/template',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::downloadTemplate
* @see app/Http/Controllers/OgretmenMusaitlikController.php:179
* @route '/ogretmen-musaitlik/template'
*/
downloadTemplate.url = (options?: RouteQueryOptions) => {
    return downloadTemplate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::downloadTemplate
* @see app/Http/Controllers/OgretmenMusaitlikController.php:179
* @route '/ogretmen-musaitlik/template'
*/
downloadTemplate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::downloadTemplate
* @see app/Http/Controllers/OgretmenMusaitlikController.php:179
* @route '/ogretmen-musaitlik/template'
*/
downloadTemplate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadTemplate.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::downloadTemplate
* @see app/Http/Controllers/OgretmenMusaitlikController.php:179
* @route '/ogretmen-musaitlik/template'
*/
const downloadTemplateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::downloadTemplate
* @see app/Http/Controllers/OgretmenMusaitlikController.php:179
* @route '/ogretmen-musaitlik/template'
*/
downloadTemplateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::downloadTemplate
* @see app/Http/Controllers/OgretmenMusaitlikController.php:179
* @route '/ogretmen-musaitlik/template'
*/
downloadTemplateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

downloadTemplate.form = downloadTemplateForm

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::importMethod
* @see app/Http/Controllers/OgretmenMusaitlikController.php:190
* @route '/ogretmen-musaitlik/import'
*/
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/ogretmen-musaitlik/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::importMethod
* @see app/Http/Controllers/OgretmenMusaitlikController.php:190
* @route '/ogretmen-musaitlik/import'
*/
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::importMethod
* @see app/Http/Controllers/OgretmenMusaitlikController.php:190
* @route '/ogretmen-musaitlik/import'
*/
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::importMethod
* @see app/Http/Controllers/OgretmenMusaitlikController.php:190
* @route '/ogretmen-musaitlik/import'
*/
const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::importMethod
* @see app/Http/Controllers/OgretmenMusaitlikController.php:190
* @route '/ogretmen-musaitlik/import'
*/
importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

importMethod.form = importMethodForm

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::create
* @see app/Http/Controllers/OgretmenMusaitlikController.php:31
* @route '/ogretmen-musaitlik/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/ogretmen-musaitlik/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::create
* @see app/Http/Controllers/OgretmenMusaitlikController.php:31
* @route '/ogretmen-musaitlik/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::create
* @see app/Http/Controllers/OgretmenMusaitlikController.php:31
* @route '/ogretmen-musaitlik/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::create
* @see app/Http/Controllers/OgretmenMusaitlikController.php:31
* @route '/ogretmen-musaitlik/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::create
* @see app/Http/Controllers/OgretmenMusaitlikController.php:31
* @route '/ogretmen-musaitlik/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::create
* @see app/Http/Controllers/OgretmenMusaitlikController.php:31
* @route '/ogretmen-musaitlik/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::create
* @see app/Http/Controllers/OgretmenMusaitlikController.php:31
* @route '/ogretmen-musaitlik/create'
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
* @see \App\Http\Controllers\OgretmenMusaitlikController::store
* @see app/Http/Controllers/OgretmenMusaitlikController.php:44
* @route '/ogretmen-musaitlik'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/ogretmen-musaitlik',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::store
* @see app/Http/Controllers/OgretmenMusaitlikController.php:44
* @route '/ogretmen-musaitlik'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::store
* @see app/Http/Controllers/OgretmenMusaitlikController.php:44
* @route '/ogretmen-musaitlik'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::store
* @see app/Http/Controllers/OgretmenMusaitlikController.php:44
* @route '/ogretmen-musaitlik'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::store
* @see app/Http/Controllers/OgretmenMusaitlikController.php:44
* @route '/ogretmen-musaitlik'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::show
* @see app/Http/Controllers/OgretmenMusaitlikController.php:67
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
export const show = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/ogretmen-musaitlik/{ogretmen}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::show
* @see app/Http/Controllers/OgretmenMusaitlikController.php:67
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
show.url = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogretmen: args }
    }

    if (Array.isArray(args)) {
        args = {
            ogretmen: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogretmen: args.ogretmen,
    }

    return show.definition.url
            .replace('{ogretmen}', parsedArgs.ogretmen.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::show
* @see app/Http/Controllers/OgretmenMusaitlikController.php:67
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
show.get = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::show
* @see app/Http/Controllers/OgretmenMusaitlikController.php:67
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
show.head = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::show
* @see app/Http/Controllers/OgretmenMusaitlikController.php:67
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
const showForm = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::show
* @see app/Http/Controllers/OgretmenMusaitlikController.php:67
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
showForm.get = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::show
* @see app/Http/Controllers/OgretmenMusaitlikController.php:67
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
showForm.head = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\OgretmenMusaitlikController::edit
* @see app/Http/Controllers/OgretmenMusaitlikController.php:105
* @route '/ogretmen-musaitlik/{ogretmen}/edit'
*/
export const edit = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/ogretmen-musaitlik/{ogretmen}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::edit
* @see app/Http/Controllers/OgretmenMusaitlikController.php:105
* @route '/ogretmen-musaitlik/{ogretmen}/edit'
*/
edit.url = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogretmen: args }
    }

    if (Array.isArray(args)) {
        args = {
            ogretmen: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogretmen: args.ogretmen,
    }

    return edit.definition.url
            .replace('{ogretmen}', parsedArgs.ogretmen.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::edit
* @see app/Http/Controllers/OgretmenMusaitlikController.php:105
* @route '/ogretmen-musaitlik/{ogretmen}/edit'
*/
edit.get = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::edit
* @see app/Http/Controllers/OgretmenMusaitlikController.php:105
* @route '/ogretmen-musaitlik/{ogretmen}/edit'
*/
edit.head = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::edit
* @see app/Http/Controllers/OgretmenMusaitlikController.php:105
* @route '/ogretmen-musaitlik/{ogretmen}/edit'
*/
const editForm = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::edit
* @see app/Http/Controllers/OgretmenMusaitlikController.php:105
* @route '/ogretmen-musaitlik/{ogretmen}/edit'
*/
editForm.get = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::edit
* @see app/Http/Controllers/OgretmenMusaitlikController.php:105
* @route '/ogretmen-musaitlik/{ogretmen}/edit'
*/
editForm.head = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\OgretmenMusaitlikController::update
* @see app/Http/Controllers/OgretmenMusaitlikController.php:143
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
export const update = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/ogretmen-musaitlik/{ogretmen}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::update
* @see app/Http/Controllers/OgretmenMusaitlikController.php:143
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
update.url = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogretmen: args }
    }

    if (Array.isArray(args)) {
        args = {
            ogretmen: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogretmen: args.ogretmen,
    }

    return update.definition.url
            .replace('{ogretmen}', parsedArgs.ogretmen.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::update
* @see app/Http/Controllers/OgretmenMusaitlikController.php:143
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
update.put = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::update
* @see app/Http/Controllers/OgretmenMusaitlikController.php:143
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
const updateForm = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::update
* @see app/Http/Controllers/OgretmenMusaitlikController.php:143
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
updateForm.put = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::destroy
* @see app/Http/Controllers/OgretmenMusaitlikController.php:168
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
export const destroy = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/ogretmen-musaitlik/{ogretmen}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::destroy
* @see app/Http/Controllers/OgretmenMusaitlikController.php:168
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
destroy.url = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogretmen: args }
    }

    if (Array.isArray(args)) {
        args = {
            ogretmen: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogretmen: args.ogretmen,
    }

    return destroy.definition.url
            .replace('{ogretmen}', parsedArgs.ogretmen.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::destroy
* @see app/Http/Controllers/OgretmenMusaitlikController.php:168
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
destroy.delete = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::destroy
* @see app/Http/Controllers/OgretmenMusaitlikController.php:168
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
const destroyForm = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::destroy
* @see app/Http/Controllers/OgretmenMusaitlikController.php:168
* @route '/ogretmen-musaitlik/{ogretmen}'
*/
destroyForm.delete = (args: { ogretmen: string | number } | [ogretmen: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const OgretmenMusaitlikController = { index, downloadTemplate, importMethod, create, store, show, edit, update, destroy, import: importMethod }

export default OgretmenMusaitlikController